<?php

namespace App\Controllers;

use App\Config\MockData;

class QnaController extends BaseController
{
    protected string $eventSlug = 'emf-2026';
    
    protected function fetchApi(string $endpoint, string $method = 'GET', array $data = [])
    {
        if (!isset($data['event_slug'])) {
            $data['event_slug'] = $this->eventSlug;
        }

        $apiUrls = [
            'http://localhost:8081/api/v1/' . ltrim($endpoint, '/'),
            'http://127.0.0.1:8081/api/v1/' . ltrim($endpoint, '/'),
            'http://[::1]:8081/api/v1/' . ltrim($endpoint, '/'),
            //'https://dev.investordailysummit.com/backend/api/v1/' . ltrim($endpoint, '/'),
            base_url('backend/api/v1/'. ltrim($endpoint, '/')),
        ];

        foreach ($apiUrls as $apiUrl) {
            try {
                //$client = \Config\Services::curlrequest(['timeout' => 3.0]);
                $client = \Config\Services::curlrequest([
                    'timeout' => 8.0,
                    'verify'  => false,
                ]);
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

        // Try getting site info from cached investor-daily-summit-2026 event
        $cacheKey = 'cms_live_event_' . $this->eventSlug;
        $live = cache($cacheKey);
        if ($live === null) {
            $liveResp = $this->fetchApi('event/' . $this->eventSlug);
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
        $dayParam = $this->request->getGet('day') ?: $this->request->getGet('day_id');
        $sessionParam = $this->request->getGet('session') ?: $this->request->getGet('session_id');
        $itemParam = $this->request->getGet('item') ?: $this->request->getGet('item_id');

        return $this->renderForm((int) $dayParam, (int) $sessionParam, (int) $itemParam);
    }

    public function sessionItem($session, $item)
    {
        return $this->renderForm(0, (int) $session, (int) $item);
    }

    public function daySessionItem($day, $session, $item)
    {
        return $this->renderForm((int) $day, (int) $session, (int) $item);
    }

    public function daySession($day, $session)
    {
        return $this->renderForm((int) $day, (int) $session, 0);
    }

    public function sessionOnly($session)
    {
        return $this->renderForm(0, (int) $session, 0);
    }

    protected function renderForm(int $dayId, int $sessionId, int $itemId = 0)
    {
        $data = $this->getCommonData();

        // Fetch sessions list to match session details
        $sessionsData = $this->fetchApi('qna/sessions');
        $days = $sessionsData['days'] ?? [];

        $targetSession = null;
        $targetDay = null;

        // 1. If dayId is specified, find the specific day first
        if (!empty($dayId)) {
            foreach ($days as $d) {
                if ((int) $d['day_id'] === $dayId || (int) $d['day_number'] === $dayId) {
                    $targetDay = $d;
                    break;
                }
            }

            // Strictly search for session/item ONLY within this specific day
            if ($targetDay && (!empty($sessionId) || !empty($itemId))) {
                if (!empty($targetDay['sessions'])) {
                    foreach ($targetDay['sessions'] as $s) {
                        if (!empty($itemId) && !empty($s['item_id']) && (int) $s['item_id'] === $itemId) {
                            if (empty($sessionId) || (int) $s['id'] === $sessionId) {
                                $targetSession = $s;
                                break;
                            }
                        }
                        if (empty($itemId) && !empty($sessionId) && (int) $s['id'] === $sessionId) {
                            $targetSession = $s;
                            break;
                        }
                    }
                }
            }
        } else {
            // 2. If dayId is 0, search across all days
            if (!empty($sessionId) || !empty($itemId)) {
                foreach ($days as $d) {
                    if (!empty($d['sessions'])) {
                        foreach ($d['sessions'] as $s) {
                            if (!empty($itemId) && !empty($s['item_id']) && (int) $s['item_id'] === $itemId) {
                                if (empty($sessionId) || (int) $s['id'] === $sessionId) {
                                    $targetSession = $s;
                                    $targetDay = $d;
                                    break 2;
                                }
                            }
                            if (empty($itemId) && !empty($sessionId) && (int) $s['id'] === $sessionId) {
                                $targetSession = $s;
                                $targetDay = $d;
                                break 2;
                            }
                        }
                    }
                }
            }
        }

        if ($targetSession && $targetDay) {
            $data['dayId'] = $targetDay['day_id'];
            $data['dayNumber'] = $targetDay['day_number'];
            $data['dayLabel'] = $targetDay['label'];
            $data['dayDate'] = !empty($targetDay['formatted_date']) ? $targetDay['formatted_date'] : (!empty($targetDay['event_date']) ? date('F j, Y', strtotime($targetDay['event_date'])) : '');
            $data['sessionId'] = $targetSession['id'];
            $data['itemId'] = $targetSession['item_id'] ?? null;
            $data['sessionTitle'] = $targetSession['title'] ?? 'Sesi Acara';
            $data['sessionSubtitle'] = $targetSession['subtitle'] ?? '';
            $data['roomName'] = $targetSession['room_name'] ?? '';
            $data['sessionTime'] = !empty($targetSession['start_time']) ? ($targetSession['start_time'] . ' - ' . $targetSession['end_time'] . ' WIB') : '';
            $data['targetSession'] = $targetSession;
            $data['targetDay'] = $targetDay;
            $data['sessionNotFound'] = false;
        } else {
            // Strict mismatch or invalid session/day requested
            $isRequestedSpecific = (!empty($sessionId) || !empty($itemId) || (!empty($dayId) && empty($targetDay)));

            $data['dayId'] = $targetDay['day_id'] ?? $dayId;
            $data['dayNumber'] = $targetDay['day_number'] ?? $dayId;
            $data['dayLabel'] = $targetDay['label'] ?? ($dayId ? 'Day ' . $dayId : 'Day 1');
            $data['dayDate'] = !empty($targetDay['formatted_date']) ? $targetDay['formatted_date'] : '';
            $data['sessionId'] = 0;
            $data['itemId'] = 0;
            $data['sessionTitle'] = $isRequestedSpecific ? 'Sesi Tidak Ditemukan' : 'Pilih Sesi Acara';
            $data['sessionSubtitle'] = $isRequestedSpecific ? 'Sesi acara yang Anda akses tidak terdaftar atau tautan tidak valid.' : '';
            $data['roomName'] = '';
            $data['sessionTime'] = '';
            $data['targetSession'] = null;
            $data['targetDay'] = $targetDay;
            $data['sessionNotFound'] = $isRequestedSpecific;
        }

        $data['allDays'] = $days;

        return view('qna/form', $data);
    }

    public function submit()
    {
        $askerName = trim(strip_tags((string) $this->request->getPost('asker_name')));
        $question = trim(strip_tags((string) $this->request->getPost('question')));
        $rawSession = $this->request->getPost('schedule_session_id');
        $dayId = (int) $this->request->getPost('schedule_day_id');
        $itemId = (int) $this->request->getPost('session_item_id');

        if (is_string($rawSession) && strpos($rawSession, ':') !== false) {
            $parts = explode(':', $rawSession, 2);
            $sessionId = (int) $parts[0];
            if (empty($itemId) && !empty($parts[1])) {
                $itemId = (int) $parts[1];
            }
        } else {
            $sessionId = (int) $rawSession;
        }

        $isAjax = $this->request->isAJAX() || $this->request->getHeaderLine('Accept') === 'application/json';

        if (empty($askerName) || empty($question) || empty($sessionId)) {
            if ($isAjax) {
                return $this->response->setStatusCode(422)->setJSON([
                    'success' => false,
                    'message' => 'Nama lengkap, pertanyaan, dan sesi acara wajib diisi.',
                ]);
            }
            return redirect()->back()->withInput()->with('error', 'Nama lengkap dan pertanyaan wajib diisi.');
        }

        if (mb_strlen($askerName) < 5) {
            if ($isAjax) {
                return $this->response->setStatusCode(422)->setJSON([
                    'success' => false,
                    'message' => 'Nama lengkap minimal 5 karakter.',
                ]);
            }
            return redirect()->back()->withInput()->with('error', 'Nama lengkap minimal 5 karakter.');
        }

        if (mb_strlen($question) < 5) {
            if ($isAjax) {
                return $this->response->setStatusCode(422)->setJSON([
                    'success' => false,
                    'message' => 'Pertanyaan minimal 5 karakter.',
                ]);
            }
            return redirect()->back()->withInput()->with('error', 'Pertanyaan minimal 5 karakter.');
        }

        $postData = [
            'asker_name' => $askerName,
            'question' => $question,
            'schedule_session_id' => $sessionId,
            'session_item_id' => $itemId ?: null,
            'schedule_day_id' => $dayId,
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

        $filterDay = $this->request->getGet('day_id') ? (int) $this->request->getGet('day_id') : null;
        $rawSession = $this->request->getGet('session_id');
        $filterSession = null;
        $filterItem = $this->request->getGet('item_id') ? (int) $this->request->getGet('item_id') : null;

        if (!empty($rawSession)) {
            if (is_string($rawSession) && strpos($rawSession, ':') !== false) {
                $parts = explode(':', $rawSession, 2);
                $filterSession = (int) $parts[0];
                if (empty($filterItem) && !empty($parts[1])) {
                    $filterItem = (int) $parts[1];
                }
            } else {
                $filterSession = (int) $rawSession;
            }
        }

        // Fetch sessions list for dropdown filters
        $sessionsData = $this->fetchApi('qna/sessions');
        $data['days'] = $sessionsData['days'] ?? [];

        // Fetch questions
        $queryParams = [];
        if (!empty($filterDay))
            $queryParams['day_id'] = $filterDay;
        if (!empty($filterSession))
            $queryParams['session_id'] = $filterSession;
        if (!empty($filterItem))
            $queryParams['item_id'] = $filterItem;

        $qnaResp = $this->fetchApi('qna', 'GET', $queryParams);
        $data['questions'] = $qnaResp['data'] ?? [];
        $data['filterDay'] = $filterDay;
        $data['filterSession'] = !empty($filterItem) ? ($filterSession . ':' . $filterItem) : $filterSession;
        $data['filterItem'] = $filterItem;

        return view('qna/dashboard', $data);
    }

    public function apiList()
    {
        $filterDay = $this->request->getGet('day_id') ? (int) $this->request->getGet('day_id') : null;
        $rawSession = $this->request->getGet('session_id');
        $filterSession = null;
        $filterItem = $this->request->getGet('item_id') ? (int) $this->request->getGet('item_id') : null;

        if (!empty($rawSession)) {
            if (is_string($rawSession) && strpos($rawSession, ':') !== false) {
                $parts = explode(':', $rawSession, 2);
                $filterSession = (int) $parts[0];
                if (empty($filterItem) && !empty($parts[1])) {
                    $filterItem = (int) $parts[1];
                }
            } else {
                $filterSession = (int) $rawSession;
            }
        }

        $queryParams = [];
        if (!empty($filterDay))
            $queryParams['day_id'] = $filterDay;
        if (!empty($filterSession))
            $queryParams['session_id'] = $filterSession;
        if (!empty($filterItem))
            $queryParams['item_id'] = $filterItem;

        $qnaResp = $this->fetchApi('qna', 'GET', $queryParams);

        return $this->response->setJSON([
            'success' => true,
            'count' => count($qnaResp['data'] ?? []),
            'data' => $qnaResp['data'] ?? [],
        ]);
    }
}
