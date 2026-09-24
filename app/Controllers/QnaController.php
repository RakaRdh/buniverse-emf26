<?php

namespace App\Controllers;

use App\Config\MockData;

class QnaController extends BaseController
{
    protected function fetchApi(string $endpoint, string $method = 'GET', array $data = [])
    {
        $apiUrls = [
            'http://localhost:8081/api/v1/' . ltrim($endpoint, '/'),
            'http://127.0.0.1:8081/api/v1/' . ltrim($endpoint, '/'),
            'http://[::1]:8081/api/v1/' . ltrim($endpoint, '/'),
        ];

        foreach ($apiUrls as $apiUrl) {
            try {
                $client = \Config\Services::curlrequest(['timeout' => 3.0]);
                if (strtoupper($method) === 'POST') {
                    $response = $client->post($apiUrl, [
                        'form_params' => $data,
                        'http_errors' => false,
                    ]);
                } else {
                    $response = $client->get($apiUrl, [
                        'query'       => $data,
                        'http_errors' => false,
                    ]);
                }

                if ($response->getStatusCode() >= 200 && $response->getStatusCode() < 300) {
                    return json_decode($response->getBody(), true);
                }
            } catch (\Throwable $e) {
                // Try next fallback URL
            }
        }

        return null;
    }

    protected function getCommonData(): array
    {
        $mock = config(MockData::class);
        $site = $mock->site;

        // Try getting site info from cached active event
        $cacheKey = 'cms_live_event_active';
        $live = cache($cacheKey);
        if ($live === null) {
            $liveResp = $this->fetchApi('event/active');
            if (!empty($liveResp['data'])) {
                $live = $liveResp['data'];
                cache()->save($cacheKey, $live, 120);
            }
        }

        if (!empty($live['site'])) {
            $site['title'] = $live['site']['name'] ?? $site['title'];
            $site['dates'] = $live['site']['dates'] ?? $site['dates'];
            $site['venue'] = $live['site']['venue'] ?? $site['venue'];
        }

        return [
            'site'            => $site,
            'themeProperties' => $live['theme_properties'] ?? [],
        ];
    }

    public function index()
    {
        $dayParam     = $this->request->getGet('day') ?: $this->request->getGet('day_id');
        $sessionParam = $this->request->getGet('session') ?: $this->request->getGet('session_id');

        return $this->renderForm((int)$dayParam, (int)$sessionParam);
    }

    public function daySession($day, $session)
    {
        return $this->renderForm((int)$day, (int)$session);
    }

    public function sessionOnly($session)
    {
        return $this->renderForm(0, (int)$session);
    }

    protected function renderForm(int $dayId, int $sessionId)
    {
        $data = $this->getCommonData();

        // Fetch sessions list to match session details
        $sessionsData = $this->fetchApi('qna/sessions');
        $days = $sessionsData['days'] ?? [];

        $targetSession = null;
        $targetDay     = null;

        if (!empty($sessionId)) {
            foreach ($days as $d) {
                if (!empty($d['sessions'])) {
                    foreach ($d['sessions'] as $s) {
                        if ($s['id'] === $sessionId) {
                            $targetSession = $s;
                            $targetDay = $d;
                            break 2;
                        }
                    }
                }
            }
        }

        // If dayId is provided and matched
        if (!$targetDay && !empty($dayId)) {
            foreach ($days as $d) {
                if ($d['day_id'] === $dayId || $d['day_number'] === $dayId) {
                    $targetDay = $d;
                    break;
                }
            }
        }

        $data['dayId']         = $targetDay['day_id'] ?? $dayId;
        $data['dayNumber']     = $targetDay['day_number'] ?? $dayId;
        $data['dayLabel']      = $targetDay['label'] ?? ($dayId ? 'Day ' . $dayId : 'Day 1');
        $data['sessionId']     = $targetSession['id'] ?? $sessionId;
        $data['sessionTitle']  = $targetSession['title'] ?? 'Sesi Acara';
        $data['sessionTime']   = !empty($targetSession['start_time']) ? ($targetSession['start_time'] . ' - ' . $targetSession['end_time'] . ' WIB') : '';
        $data['targetSession'] = $targetSession;
        $data['targetDay']     = $targetDay;
        $data['allDays']       = $days;

        return view('qna/form', $data);
    }

    public function submit()
    {
        $askerName = trim((string)$this->request->getPost('asker_name'));
        $question  = trim((string)$this->request->getPost('question'));
        $sessionId = (int)$this->request->getPost('schedule_session_id');
        $dayId     = (int)$this->request->getPost('schedule_day_id');

        $isAjax = $this->request->isAJAX() || $this->request->getHeaderLine('Accept') === 'application/json';

        if (empty($askerName) || empty($question) || empty($sessionId)) {
            if ($isAjax) {
                return $this->response->setStatusCode(422)->setJSON([
                    'success' => false,
                    'message' => 'Nama, pertanyaan, dan sesi acara wajib diisi.',
                ]);
            }
            return redirect()->back()->withInput()->with('error', 'Nama dan pertanyaan wajib diisi.');
        }

        $postData = [
            'asker_name'          => $askerName,
            'question'            => $question,
            'schedule_session_id' => $sessionId,
            'schedule_day_id'     => $dayId,
        ];

        $resp = $this->fetchApi('qna', 'POST', $postData);

        if (!empty($resp['success'])) {
            if ($isAjax) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Pertanyaan Anda berhasil dikirimkan.',
                ]);
            }
            return redirect()->back()->with('success', 'Pertanyaan Anda berhasil dikirimkan. Terima kasih atas partisipasi Anda!');
        }

        $errMsg = $resp['message'] ?? 'Gagal mengirim pertanyaan. Silakan coba lagi.';
        if ($isAjax) {
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'message' => $errMsg,
            ]);
        }

        return redirect()->back()->withInput()->with('error', $errMsg);
    }

    public function dashboard()
    {
        $data = $this->getCommonData();

        $filterDay     = $this->request->getGet('day_id') ? (int)$this->request->getGet('day_id') : null;
        $filterSession = $this->request->getGet('session_id') ? (int)$this->request->getGet('session_id') : null;

        // Fetch sessions list for dropdown filters
        $sessionsData = $this->fetchApi('qna/sessions');
        $data['days'] = $sessionsData['days'] ?? [];

        // Fetch questions
        $queryParams = [];
        if (!empty($filterDay))     $queryParams['day_id'] = $filterDay;
        if (!empty($filterSession)) $queryParams['session_id'] = $filterSession;

        $qnaResp = $this->fetchApi('qna', 'GET', $queryParams);
        $data['questions']     = $qnaResp['data'] ?? [];
        $data['filterDay']     = $filterDay;
        $data['filterSession'] = $filterSession;

        return view('qna/dashboard', $data);
    }

    public function apiList()
    {
        $filterDay     = $this->request->getGet('day_id') ? (int)$this->request->getGet('day_id') : null;
        $filterSession = $this->request->getGet('session_id') ? (int)$this->request->getGet('session_id') : null;

        $queryParams = [];
        if (!empty($filterDay))     $queryParams['day_id'] = $filterDay;
        if (!empty($filterSession)) $queryParams['session_id'] = $filterSession;

        $qnaResp = $this->fetchApi('qna', 'GET', $queryParams);

        return $this->response->setJSON([
            'success' => true,
            'count'   => count($qnaResp['data'] ?? []),
            'data'    => $qnaResp['data'] ?? [],
        ]);
    }
}
