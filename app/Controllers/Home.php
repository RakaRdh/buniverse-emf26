<?php

namespace App\Controllers;

use App\Config\MockData;

class Home extends BaseController
{
    public function index(): string
    {
        $mock = config(MockData::class);

        $site          = $mock->site;
        $economicModel = $mock->economicModel;
        $speakers      = $mock->speakers;
        $schedule      = $mock->schedule;
        $gallery       = $mock->gallery;
        $relatedNews   = $mock->relatedNews;
        $sponsors      = $mock->sponsors;

        // Attempt live synchronization with BackendCMS REST API
        $apiUrl = 'http://127.0.0.1:8081/api/v1/event/active';
        try {
            $client = \Config\Services::curlrequest(['timeout' => 1.5]);
            $response = $client->get($apiUrl, ['http_errors' => false]);
            if ($response->getStatusCode() === 200) {
                $json = json_decode($response->getBody(), true);
                if (!empty($json['data'])) {
                    $live = $json['data'];

                    // Sync site metadata
                    if (!empty($live['site'])) {
                        $site['title']      = $live['site']['name'] ?? $site['title'];
                        $site['theme']      = $live['site']['theme'] ?? $site['theme'];
                        $site['dates']      = $live['site']['dates'] ?? $site['dates'];
                        $site['venue']      = $live['site']['venue'] ?? $site['venue'];
                        $site['ticket_url'] = $live['site']['cta_ticket_url'] ?? $site['ticket_url'];
                    }

                    // Sync economic model
                    if (!empty($live['economicModel'])) {
                        if (!empty($live['economicModel']['badge'])) {
                            $economicModel['badge'] = $live['economicModel']['badge'];
                        }
                        if (!empty($live['economicModel']['content'])) {
                            // Split into paragraphs if provided
                            $economicModel['intro'] = $live['economicModel']['title'] ?? $economicModel['intro'];
                            $paras = array_filter(explode("\n\n", str_replace("\r\n", "\n", $live['economicModel']['content'])));
                            if (!empty($paras)) {
                                $economicModel['paragraphs'] = array_values($paras);
                            }
                        }
                    }

                    // Sync speakers if database has them
                    if (!empty($live['speakers']) && count($live['speakers']) > 0) {
                        $syncedSpeakers = [];
                        foreach ($live['speakers'] as $idx => $sp) {
                            $slug = url_title($sp['name'], '-', true);
                            $syncedSpeakers[$slug] = [
                                'id'       => $sp['id'],
                                'slug'     => $slug,
                                'name'     => $sp['name'],
                                'role'     => $sp['role'],
                                'photo'    => $sp['photo'] ?: 'wowo.png',
                                'bio'      => $sp['bio'] ?? '',
                                'featured' => ($idx < 4),
                            ];
                        }
                        $speakers = $syncedSpeakers;
                    }

                    // Sync sponsors from live API if available
                    if (!empty($live['sponsors'])) {
                        $sponsors = $live['sponsors'];
                    }
                }
            }
        } catch (\Throwable $e) {
            // Graceful fallback to MockData if CMS API is unreachable
        }

        $data = [
            'site'          => $site,
            'economicModel' => $economicModel,
            'speakers'      => $speakers,
            'schedule'      => $schedule,
            'gallery'       => $gallery,
            'relatedNews'   => $relatedNews,
            'sponsors'      => $sponsors,
        ];

        return view('home', $data);
    }
}
