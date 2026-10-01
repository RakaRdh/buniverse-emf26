<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\ResponseInterface;

class MediaController extends BaseController
{
    /**
     * Serve uploaded media files with local caching and proxy to internal CMS
     * Route: uploads/(:any)
     */
    public function serve(...$segments): ResponseInterface
    {
        $path = implode('/', $segments);
        log_message('error', 'MediaController::serve called with path: ' . $path);

        // 1. Path Sanitization (Anti-Traversal & Null-byte security)
        $path = trim($path, '/\\');
        if (empty($path) || strpos($path, '..') !== false || strpos($path, "\0") !== false) {
            return $this->response->setStatusCode(400)->setBody('Invalid media path');
        }

        $cacheDir = WRITEPATH . 'cache/uploads/';
        $cacheFile = $cacheDir . $path;

        // 2. Check Local Disk Cache (Cache Hit)
        if (file_exists($cacheFile) && is_file($cacheFile)) {
            return $this->deliverFile($cacheFile);
        }

        // 3. Cache Miss: Fetch from internal Backend CMS
        $candidateUrls = array_values(array_filter([
            getenv('CMS_INTERNAL_URL') ?: null,
            'http://localhost:8081',
            'http://127.0.0.1:8081',
            'http://[::1]:8081',
        ]));

        $body = null;
        $client = \Config\Services::curlrequest([
            'timeout' => 4.0,
            'http_errors' => false,
        ]);

        foreach ($candidateUrls as $base) {
            $cmsUrl = rtrim($base, '/') . '/uploads/' . $path;
            try {
                $res = $client->get($cmsUrl);
                if ($res->getStatusCode() === 200) {
                    $body = $res->getBody();
                    if (!empty($body)) {
                        break;
                    }
                }
            } catch (\Throwable $e) {
                // Try next candidate
            }
        }

        if (empty($body)) {
            return $this->response->setStatusCode(404)->setBody('Media not found on CMS');
        }
        // Save to local cache directory
        $targetSubdir = dirname($cacheFile);
        if (!is_dir($targetSubdir)) {
            mkdir($targetSubdir, 0755, true);
        }
        file_put_contents($cacheFile, $body);

        return $this->deliverFile($cacheFile);
    }

    /**
     * Stream binary media file with optimal HTTP cache headers
     */
    private function deliverFile(string $filePath): ResponseInterface
    {
        $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        $mimeTypes = [
            'webp' => 'image/webp',
            'png' => 'image/png',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'svg' => 'image/svg+xml',
            'gif' => 'image/gif',
            'ico' => 'image/x-icon',
            'pdf' => 'application/pdf',
            'mp4' => 'video/mp4',
        ];

        $mimeType = $mimeTypes[$ext] ?? mime_content_type($filePath) ?: 'application/octet-stream';
        $fileSize = filesize($filePath);
        $fileTime = filemtime($filePath);
        $etag = '"' . md5($fileTime . $fileSize) . '"';

        // Check 304 Not Modified
        $ifNoneMatch = $this->request->getServer('HTTP_IF_NONE_MATCH');
        if ($ifNoneMatch && trim($ifNoneMatch) === $etag) {
            return $this->response->setStatusCode(304);
        }

        return $this->response
            ->setStatusCode(200)
            ->setContentType($mimeType)
            ->setHeader('Content-Length', (string) $fileSize)
            ->setHeader('Cache-Control', 'public, max-age=2592000, immutable')
            ->setHeader('ETag', $etag)
            ->setHeader('Last-Modified', gmdate('D, d M Y H:i:s', $fileTime) . ' GMT')
            ->setBody(file_get_contents($filePath));
    }
}
