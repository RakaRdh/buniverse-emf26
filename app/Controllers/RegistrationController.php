<?php

namespace App\Controllers;

use Config\MockData;

class RegistrationController extends BaseController
{
    protected string $eventSlug = 'emf-2026';

    protected function fetchApi(string $endpoint, string $method = 'GET', array $data = [])
    {
        if (!isset($data['event_slug'])) {
            $data['event_slug'] = $this->eventSlug;
        }

        $apiUrls = [
            // 'https://investordailysummit.com/backend/api/v1/' . ltrim($endpoint, '/'),
            'http://127.0.0.1:8081/api/v1/' . ltrim($endpoint, '/'),
            'http://localhost:8081/api/v1/' . ltrim($endpoint, '/'),
        ];

        foreach ($apiUrls as $apiUrl) {
            try {
                $client = \Config\Services::curlrequest([
                    'timeout' => 4.0,
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

                if ($response->getStatusCode() >= 200 && $response->getStatusCode() < 500) {
                    $body = json_decode($response->getBody(), true);
                    return [
                        'status_code' => $response->getStatusCode(),
                        'data'        => $body,
                    ];
                }
            } catch (\Throwable $e) {
                // Try next URL fallback
            }
        }

        return null;
    }

    /**
     * Format tanggal ke bahasa Indonesia
     */
    protected function formatIndonesianDate(?string $dateStr, bool $includeDayName = true, bool $shortMonth = true): string
    {
        if (empty($dateStr)) {
            return '';
        }
        $ts = strtotime($dateStr);
        if ($ts === false) {
            return $dateStr;
        }

        $hariMap = [0 => 'Minggu', 1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu', 4 => 'Kamis', 5 => 'Jumat', 6 => 'Sabtu'];
        $bulanMap = [
            1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'Mei', 6 => 'Jun',
            7 => 'Jul', 8 => 'Agu', 9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des'
        ];
        $bulanFullMap = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
            7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        $dayIndex = (int)date('w', $ts);
        $dayNum   = (int)date('j', $ts);
        $monthNum = (int)date('n', $ts);
        $year     = date('Y', $ts);

        $dayName   = $hariMap[$dayIndex] ?? '';
        $monthName = $shortMonth ? ($bulanMap[$monthNum] ?? '') : ($bulanFullMap[$monthNum] ?? '');

        if ($includeDayName) {
            return "{$dayName}, {$dayNum} {$monthName} {$year}";
        }
        return "{$dayNum} {$monthName} {$year}";
    }

    /**
     * Resolusi dinamis daftar hari kunjungan dari Jadwal / Schedule CMS & Event
     */
    protected function resolveScheduleDays(?array $live = null, ?array $eventDb = null): array
    {
        $db = \Config\Database::connect();
        if ($eventDb === null) {
            $eventDb = $db->table('events')->where('slug', $this->eventSlug)->get()->getRowArray();
        }

        $days = [];

        // 1. Coba ambil dari tabel schedule_days di database lokal jika event ditemukan
        if (!empty($eventDb['id'])) {
            try {
                $daysDb = $db->table('schedule_days')
                    ->where('event_id', $eventDb['id'])
                    ->orderBy('day_number', 'ASC')
                    ->get()
                    ->getResultArray();

                if (!empty($daysDb)) {
                    foreach ($daysDb as $d) {
                        $rawDate = $d['event_date'] ?? null;
                        $dayNum  = (int)($d['day_number'] ?? (count($days) + 1));
                        $days[] = [
                            'day_number' => $dayNum,
                            'label'      => !empty($d['label']) ? $d['label'] : ('Hari ' . $dayNum),
                            'date'       => $rawDate,
                        ];
                    }
                }
            } catch (\Throwable $e) {
                // Failover jika tabel schedule_days tidak ada
            }
        }

        // 2. Fallback: Ambil dari CMS Live payload ($live['schedule'])
        if (empty($days) && !empty($live['schedule'])) {
            $sched = $live['schedule'];
            if (!empty($sched['days']) && is_array($sched['days'])) {
                foreach ($sched['days'] as $idx => $ld) {
                    $dayNum = (int)($ld['day_number'] ?? ($idx + 1));
                    $days[] = [
                        'day_number' => $dayNum,
                        'label'      => $ld['label'] ?? ('Hari ' . $dayNum),
                        'date'       => $ld['date'] ?? ($ld['event_date'] ?? null),
                    ];
                }
            } else {
                foreach ($sched as $k => $val) {
                    if (str_starts_with($k, 'day_') && is_array($val)) {
                        $num = (int)str_replace('day_', '', $k);
                        $days[] = [
                            'day_number' => $num ?: (count($days) + 1),
                            'label'      => $val['label'] ?? ('Hari ' . ($num ?: (count($days) + 1))),
                            'date'       => $val['date'] ?? null,
                        ];
                    }
                }
            }
        }

        // 3. Fallback: Ambil dari MockData config
        if (empty($days)) {
            $mock = config(MockData::class);
            if (!empty($mock->schedule) && is_array($mock->schedule)) {
                foreach ($mock->schedule as $k => $val) {
                    if (str_starts_with($k, 'day_') && is_array($val)) {
                        $num = (int)str_replace('day_', '', $k);
                        $days[] = [
                            'day_number' => $num ?: (count($days) + 1),
                            'label'      => $val['label'] ?? ('Hari ' . ($num ?: (count($days) + 1))),
                            'date'       => $val['date'] ?? null,
                        ];
                    }
                }
            }
        }

        // 4. Default Fallback jika semua data kosong sama sekali
        if (empty($days)) {
            $defaultStart = $eventDb['event_date_start'] ?? '2026-11-10';
            $defaultEnd   = $eventDb['event_date_end'] ?? '2026-11-11';
            $days = [
                ['day_number' => 1, 'label' => 'Hari 1', 'date' => $defaultStart],
                ['day_number' => 2, 'label' => 'Hari 2', 'date' => $defaultEnd],
            ];
        }

        // Susun struktur rapi untuk options dan map
        $options  = [];
        $map      = [];
        $list     = [];
        $datesYmd = [];

        foreach ($days as $d) {
            $num = (int)($d['day_number'] ?? 1);
            $key = 'day_' . $num;

            // Bersihkan label e.g. "Day 1" -> "Hari 1"
            $rawLabel = trim($d['label'] ?? ('Hari ' . $num));
            if (preg_match('/^Day\s+(\d+)$/i', $rawLabel, $m)) {
                $cleanLabel = 'Hari ' . $m[1];
            } else {
                $cleanLabel = $rawLabel;
            }

            // Normalisasi tanggal
            $rawDate = $d['date'] ?? null;
            $ts = $rawDate ? strtotime((string)$rawDate) : null;
            $dateYmd = $ts ? date('Y-m-d', $ts) : null;
            if ($dateYmd) {
                $datesYmd[] = $dateYmd;
            }

            $formattedDate = $dateYmd ? $this->formatIndonesianDate($dateYmd, true, true) : '';
            $shortDate     = $dateYmd ? $this->formatIndonesianDate($dateYmd, false, true) : '';
            $monthShort    = $dateYmd ? date('M', $ts) : '';
            $dayOnly       = $dateYmd ? date('j', $ts) : '';
            $veryShort     = ($dayOnly && $monthShort) ? "{$dayOnly} {$monthShort}" : $shortDate;

            $dropdownLabel = $cleanLabel . ($formattedDate ? " — {$formattedDate}" : '');
            $badgeLabel    = $cleanLabel . ($veryShort ? " ({$veryShort})" : '');
            $fullLabel     = $cleanLabel . ($shortDate ? " ({$shortDate})" : '');

            $item = [
                'day_number'     => $num,
                'key'            => $key,
                'date_ymd'       => $dateYmd,
                'raw_date'       => $rawDate,
                'label'          => $cleanLabel,
                'formatted_date' => $formattedDate,
                'short_date'     => $shortDate,
                'very_short'     => $veryShort,
                'dropdown_label' => $dropdownLabel,
                'badge_label'    => $badgeLabel,
                'full_label'     => $fullLabel,
            ];

            $options[] = [
                'value' => $key,
                'label' => $dropdownLabel,
            ];
            $map[$key] = $item;
            $list[] = $item;
        }

        // Tambahkan opsi "all_days" jika ada 2 hari atau lebih
        if (count($list) >= 2) {
            $allDaysText = 'Semua Hari';
            if (count($datesYmd) >= 2) {
                $firstTs = strtotime(min($datesYmd));
                $lastTs  = strtotime(max($datesYmd));
                $firstDay = date('j', $firstTs);
                $lastDay  = date('j', $lastTs);
                $lastMonth = $this->formatIndonesianDate(date('Y-m-d', $lastTs), false, true);
                $allDaysText = "Semua Hari ({$firstDay} - {$lastMonth})";
            }

            $options[] = [
                'value' => 'all_days',
                'label' => $allDaysText,
            ];

            $map['all_days'] = [
                'day_number'     => 0,
                'key'            => 'all_days',
                'date_ymd'       => null,
                'raw_date'       => null,
                'label'          => 'Semua Hari',
                'formatted_date' => $allDaysText,
                'short_date'     => $allDaysText,
                'very_short'     => 'Semua Hari',
                'dropdown_label' => $allDaysText,
                'badge_label'    => 'Semua Hari',
                'full_label'     => $allDaysText,
            ];
        }

        return [
            'options' => $options,
            'map'     => $map,
            'list'    => $list,
        ];
    }

    protected function getCommonData(): array
    {
        $mock = config(MockData::class);
        $site = $mock->site;

        $cacheKey = 'cms_live_event_' . $this->eventSlug;
        $live = cache($cacheKey);
        if ($live === null) {
            $liveResp = $this->fetchApi('event/' . $this->eventSlug);
            if (!empty($liveResp['data']['data'])) {
                $live = $liveResp['data']['data'];
                cache()->save($cacheKey, $live, 120);
            }
        }

        if (!empty($live['site'])) {
            $site['title'] = $live['site']['name'] ?? 'Energi & Mineral Festival 2026';
            $site['dates'] = $live['site']['dates'] ?? '10 - 11 November 2026';
            $site['venue'] = $live['site']['venue'] ?? 'Jakarta';
        } else {
            $site['title'] = 'Energi & Mineral Festival 2026';
            $site['dates'] = '10 - 11 November 2026';
            $site['venue'] = 'Jakarta';
        }

        $db = \Config\Database::connect();
        $eventDb = $db->table('events')->where('slug', $this->eventSlug)->get()->getRowArray();
        $quota = (int)($eventDb['quota'] ?? 0);
        $confirmedCount = 0;
        if (!empty($eventDb['id'])) {
            $confirmedCount = $db->table('participants')
                ->where('event_id', $eventDb['id'])
                ->where('status', 'confirmed')
                ->countAllResults();
        }

        $isSoldOut = ($quota > 0 && $confirmedCount >= $quota);
        $remainingQuota = ($quota > 0) ? max(0, $quota - $confirmedCount) : 9999;

        // Dapatkan data schedule hari kunjungan yang dinamis
        $scheduleData = $this->resolveScheduleDays($live, $eventDb);

        return [
            'site'             => $site,
            'themeProperties'  => $live['theme_properties'] ?? [],
            'event'            => $live ?? [],
            'isSoldOut'        => $isSoldOut,
            'remainingQuota'   => $remainingQuota,
            'totalQuota'       => $quota,
            'visitDayOptions'  => $scheduleData['options'],
            'scheduleDaysMap'  => $scheduleData['map'],
            'scheduleDaysList' => $scheduleData['list'],
        ];
    }

    /**
     * Tampilkan Halaman Form Registrasi Pengunjung
     */
    public function index()
    {
        $data = $this->getCommonData();
        return view('ticket/register', $data);
    }

    /**
     * Submit Registrasi Form
     */
    public function submit()
    {
        $registrationType = trim(strip_tags((string)$this->request->getPost('registration_type') ?: 'personal'));
        if (!in_array($registrationType, ['personal', 'company'])) {
            $registrationType = 'personal';
        }

        $ticketQuantity = min(5, max(1, (int)$this->request->getPost('ticket_quantity') ?: 1));

        $additionalNamesRaw = $this->request->getPost('additional_names');
        $additionalNames = [];
        if (is_array($additionalNamesRaw)) {
            foreach ($additionalNamesRaw as $an) {
                $clean = trim(strip_tags((string)$an));
                if ($clean !== '') {
                    $additionalNames[] = $clean;
                }
            }
        }

        // Penanganan PIC sesuai kategori (Pribadi vs Company)
        $picName      = '';
        $picPhone     = '';
        $companyEmail = '';

        $isAjax = $this->request->isAJAX() || $this->request->getHeaderLine('Accept') === 'application/json';

        if ($registrationType === 'company') {
            $picName      = trim(strip_tags((string)$this->request->getPost('pic_name')));
            $picPhone     = trim(strip_tags((string)$this->request->getPost('pic_phone')));
            $companyEmail = trim(strip_tags((string)$this->request->getPost('company_email')));

            if (empty($picName) || empty($picPhone) || empty($companyEmail)) {
                $err = 'Untuk kategori Instansi / Perusahaan, kolom Nama PIC, No. Telp PIC, dan Email Perusahaan wajib diisi.';
                if ($isAjax) {
                    return $this->response->setStatusCode(422)->setJSON(['success' => false, 'message' => $err]);
                }
                return redirect()->back()->withInput()->with('error', $err);
            }

            if (!filter_var($companyEmail, FILTER_VALIDATE_EMAIL)) {
                $err = 'Format alamat email perusahaan tidak valid (contoh: corporate@perusahaan.co.id).';
                if ($isAjax) {
                    return $this->response->setStatusCode(422)->setJSON(['success' => false, 'message' => $err]);
                }
                return redirect()->back()->withInput()->with('error', $err);
            }

            $picPhoneVal = \App\Helpers\PhoneHelper::validate($picPhone);
            if (!$picPhoneVal['valid']) {
                $err = 'Nomor telepon PIC tidak valid: ' . $picPhoneVal['message'];
                if ($isAjax) {
                    return $this->response->setStatusCode(422)->setJSON(['success' => false, 'message' => $err]);
                }
                return redirect()->back()->withInput()->with('error', $err);
            }
            $picPhone = $picPhoneVal['normalized'];
        }

        // Validasi Nama Tambahan jika Tiket > 1
        if ($ticketQuantity > 1) {
            if (count($additionalNames) < ($ticketQuantity - 1)) {
                $err = 'Mohon lengkapi nama seluruh pemegang tiket tambahan (' . ($ticketQuantity - 1) . ' orang).';
                if ($isAjax) {
                    return $this->response->setStatusCode(422)->setJSON(['success' => false, 'message' => $err]);
                }
                return redirect()->back()->withInput()->with('error', $err);
            }

            foreach ($additionalNames as $idx => $addName) {
                if (mb_strlen($addName) < 3) {
                    $err = 'Nama lengkap pemegang tiket #' . ($idx + 2) . ' minimal harus terdiri dari 3 karakter.';
                    if ($isAjax) {
                        return $this->response->setStatusCode(422)->setJSON(['success' => false, 'message' => $err]);
                    }
                    return redirect()->back()->withInput()->with('error', $err);
                }
            }
        }

        $postData = [
            'full_name'         => trim(strip_tags((string)$this->request->getPost('full_name'))),
            'email'             => trim(strip_tags((string)$this->request->getPost('email'))),
            'phone'             => trim(strip_tags((string)$this->request->getPost('phone'))),
            'address'           => trim(strip_tags((string)$this->request->getPost('address'))),
            'job_title'         => trim(strip_tags((string)$this->request->getPost('job_title'))),
            'company_name'      => trim(strip_tags((string)$this->request->getPost('company_name'))),
            'company_email'     => $companyEmail,
            'visit_day'         => trim(strip_tags((string)$this->request->getPost('visit_day'))),
            'ticket_quantity'   => $ticketQuantity,
            'force_duplicate'   => (int)$this->request->getPost('force_duplicate') ?: 0,
            'registration_type' => $registrationType,
            'additional_names'  => $additionalNames,
            'pic_name'          => $picName,
            'pic_phone'         => $picPhone,
            'source_info'       => trim(strip_tags((string)$this->request->getPost('source_info'))),
            'event_slug'        => $this->eventSlug,
        ];

        // Validasi required
        if (empty($postData['full_name']) || empty($postData['email']) || empty($postData['phone']) || 
            empty($postData['address']) || empty($postData['job_title']) || empty($postData['company_name'])) {
            
            $err = 'Mohon lengkapi semua kolom wajib yang bertanda bintang (*).';
            if ($isAjax) {
                return $this->response->setStatusCode(422)->setJSON(['success' => false, 'message' => $err]);
            }
            return redirect()->back()->withInput()->with('error', $err);
        }

        // 1. Validasi Nama Lengkap: Minimal 3 karakter
        if (mb_strlen($postData['full_name']) < 3) {
            $err = 'Nama lengkap minimal harus terdiri dari 3 karakter.';
            if ($isAjax) {
                return $this->response->setStatusCode(422)->setJSON(['success' => false, 'message' => $err]);
            }
            return redirect()->back()->withInput()->with('error', $err);
        }

        // 2. Validasi Format Email
        if (!filter_var($postData['email'], FILTER_VALIDATE_EMAIL)) {
            $err = 'Format alamat email tidak valid (contoh: nama@domain.com).';
            if ($isAjax) {
                return $this->response->setStatusCode(422)->setJSON(['success' => false, 'message' => $err]);
            }
            return redirect()->back()->withInput()->with('error', $err);
        }

        // 3. Validasi & Normalisasi Nomor Telepon (Mendukung Multi-Country VLC Helper)
        $phoneVal = \App\Helpers\PhoneHelper::validate($postData['phone']);
        if (!$phoneVal['valid']) {
            $err = $phoneVal['message'];
            if ($isAjax) {
                return $this->response->setStatusCode(422)->setJSON(['success' => false, 'message' => $err]);
            }
            return redirect()->back()->withInput()->with('error', $err);
        }
        $postData['phone'] = $phoneVal['normalized'];

        $res = $this->fetchApi('participants/register', 'POST', $postData);

        if (!$res || empty($res['data'])) {
            $err = 'Gagal menghubungi server pendaftaran. Silakan coba kembali sesaat lagi.';
            if ($isAjax) {
                return $this->response->setStatusCode(500)->setJSON(['success' => false, 'message' => $err]);
            }
            return redirect()->back()->withInput()->with('error', $err);
        }

        $code = $res['status_code'];
        $body = $res['data'];

        if ($code === 201 || !empty($body['success'])) {
            $participant = $body['participant'] ?? [];
            if (empty($participant['email']) && !empty($postData['email'])) {
                $participant['email'] = $postData['email'];
            }
            if (empty($participant['full_name']) && !empty($postData['full_name'])) {
                $participant['full_name'] = $postData['full_name'];
            }
            if (empty($participant['company_name']) && !empty($postData['company_name'])) {
                $participant['company_name'] = $postData['company_name'];
            }
            
            $tickets = $body['tickets'] ?? [];
            if (empty($tickets) && !empty($body['ticket_code'])) {
                $tickets = [[
                    'ticket_code'  => $body['ticket_code'],
                    'full_name'    => $participant['full_name'],
                    'visit_day'    => $postData['visit_day'] ?: 'all_days',
                    'ticket_index' => 1,
                ]];
            }

            // Kirim email konfirmasi registrasi (mendukung 1 atau banyak tiket)
            $this->sendRegistrationEmail($participant, $tickets, $body['order_id'] ?? '');

            if ($isAjax) {
                // Ambil sisa kuota terbaru setelah insert
                $common = $this->getCommonData();
                return $this->response->setJSON([
                    'success'         => true,
                    'message'         => 'Pendaftaran berhasil!',
                    'ticket_quantity' => count($tickets),
                    'participant'     => $participant,
                    'remaining_quota' => $common['remainingQuota'] ?? 9999,
                    'is_sold_out'     => $common['isSoldOut'] ?? false,
                ]);
            }

            // Fallback non-ajax: tampilkan alert sukses di register page
            session()->setFlashdata('success_participant', $participant);
            return redirect()->to(base_url('register'));
        }

        // Jika respons 409 (Duplikasi Email yang Membutuhkan Konfirmasi)
        if ($code === 409 || !empty($body['requires_confirmation'])) {
            if ($isAjax) {
                return $this->response->setStatusCode(409)->setJSON($body);
            }
        }

        // Jika respons 422 (misal Kuota Habis saat Race Condition)
        if ($isAjax) {
            $common = $this->getCommonData();
            $body['remaining_quota'] = $common['remainingQuota'] ?? 0;
            $body['is_sold_out'] = $common['isSoldOut'] ?? false;
            return $this->response->setStatusCode($code ?: 400)->setJSON($body);
        }

        $msg = $body['message'] ?? 'Terjadi kesalahan saat pendaftaran.';
        return redirect()->back()->withInput()->with('error', $msg);
    }

    /**
     * Build HTML email konfirmasi tiket (Branded EMF 2026, Dinamis & Responsif)
     */
    protected function buildRegistrationEmailHtml(array $participant, array $t, int $idx, int $ticketCount, array $scheduleDaysMap, array $commonData): string
    {
        $site = $commonData['site'] ?? [];
        $live = $commonData['event'] ?? [];

        // 1. Tanggal dinamis acara
        $eventDatesStr = !empty($site['dates']) ? $site['dates'] : '01 - 02 Desember 2026';

        // 2. Lokasi dinamis (gabungkan hotel_name dan hotel_address)
        $locData = !empty($live['locations']) ? $live['locations'] : [];
        if (empty($locData['hotel_name']) && empty($locData['hotel_address'])) {
            try {
                $db = \Config\Database::connect();
                $eventDb = $db->table('events')->where('slug', $this->eventSlug)->get()->getRowArray();
                $eventId = (int)($eventDb['id'] ?? 3);
                $locDb = $db->table('event_locations')
                    ->where('event_id', $eventId)
                    ->where('is_active', 1)
                    ->get()
                    ->getRowArray();
                if (!empty($locDb)) {
                    $locData = $locDb;
                }
            } catch (\Throwable $e) {
                log_message('error', 'Gagal load event_locations: ' . $e->getMessage());
            }
        }

        $rawHotelName = trim($locData['hotel_name'] ?? 'The Tribrata Darmawangsa Jakarta');
        // Bersihkan typo trailing bracket/parenthesis jika ada di DB
        $cleanHotelName = rtrim($rawHotelName, " (\t\n\r\0\x0B");
        $hotelAddress = trim($locData['hotel_address'] ?? ($locData['address'] ?? ''));

        $tCode = $t['ticket_code'] ?? '';
        $tName = $t['full_name'] ?? ($participant['full_name'] ?? '');
        $vDay  = $t['visit_day'] ?? 'all_days';

        $dayInfo = $scheduleDaysMap[$vDay] ?? null;
        if ($dayInfo) {
            $dayLabel = $dayInfo['dropdown_label'] ?? $dayInfo['full_label'];
        } else {
            $dayLabel = ($vDay === 'day_1') ? 'Hari 1' : (($vDay === 'day_2') ? 'Hari 2' : 'Semua Hari');
        }

        // QR Server API (Aktif, Bebas Rate Limit, Format PNG 180x180)
        $qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=180x180&margin=1&data=' . urlencode($tCode);
        $headerImgUrl = emf_url('assets/img/header-email.png');

        $company = esc($participant['company_name'] ?: 'Umum');
        $jobTitle = !empty($participant['job_title']) ? esc($participant['job_title']) : '';

        return '<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Konfirmasi Tiket — Energi &amp; Mineral Festival 2026</title>
  <style>
    @import url(\'https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap\');
    body, table, td, p, a, div {
      font-family: \'Plus Jakarta Sans\', -apple-system, BlinkMacSystemFont, \'Segoe UI\', Roboto, Helvetica, Arial, sans-serif;
    }
  </style>
</head>
<body style="margin: 0; padding: 28px 12px; background-color: #f6f8fa; color: #1e293b; -webkit-font-smoothing: antialiased;">
  <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 580px; background-color: #ffffff; border-radius: 14px; overflow: hidden; border: 1px solid #e2e8f0; box-shadow: 0 4px 20px rgba(0,0,0,0.06); margin: 0 auto;">
    
    <!-- Header Banner Image (header-email.png) -->
    <tr>
      <td style="padding: 0; line-height: 0; background-color: #f7ee39; border-top-left-radius: 14px; border-top-right-radius: 14px; overflow: hidden; text-align: center;">
        <img src="' . esc($headerImgUrl) . '" alt="Energi &amp; Mineral Festival 2026" width="580" style="width: 100%; max-width: 580px; height: auto; display: block; border: 0; outline: none; text-decoration: none;">
      </td>
    </tr>

    <!-- Content Body -->
    <tr>
      <td style="padding: 30px 24px 26px 24px;">
        <p style="font-size: 15px; line-height: 1.5; color: #0b1a0d; margin: 0 0 14px 0;">
          Kepada Yth. <strong>' . esc($tName) . '</strong>,
        </p>
        <p style="font-size: 13.5px; line-height: 1.65; color: #334155; margin: 0 0 22px 0;">
          Terima kasih atas pendaftaran Anda. Registrasi Anda untuk menghadiri <strong>Energi &amp; Mineral Festival 2026</strong> telah berhasil kami konfirmasi' . ($ticketCount > 1 ? ' (Tiket ' . ($idx + 1) . ' dari ' . $ticketCount . ')' : '') . '. Simpan e-badge tiket ini untuk verifikasi kehadiran di meja resepsionis gate saat acara berlangsung.
        </p>

        <!-- Box Barcode / QR Code Tiket (EMF Theme) -->
        <table width="100%" border="0" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border: 1.5px solid #e2e8f0; border-top: 4px solid #1b5319; border-radius: 12px; margin: 20px 0 24px 0; box-shadow: 0 4px 16px rgba(27,83,25,0.07);">
          <tr>
            <td style="padding: 22px 18px; text-align: center;">
              <div style="font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 1.2px; color: #1b5319; margin-bottom: 8px;">
                TIKET MASUK ELEKTRONIK ' . ($ticketCount > 1 ? '#' . ($idx + 1) : '') . '
              </div>
              <div style="display: inline-block; background-color: #f7ee39; border: 1px solid #d2ca30; color: #143320; font-size: 12px; font-weight: 800; border-radius: 20px; padding: 4px 14px; margin-bottom: 16px; letter-spacing: 0.2px;">
                ' . esc($dayLabel) . '
              </div>
              
              <!-- QR Code Frame -->
              <div style="text-align: center; margin: 0 auto 14px auto;">
                <div style="display: inline-block; padding: 10px; background: #ffffff; border: 1.5px solid #e2e8f0; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
                  <img src="' . $qrUrl . '" alt="QR ' . esc($tCode) . '" width="170" height="170" style="display: block; margin: 0 auto; border: 0;">
                </div>
              </div>

              <!-- Ticket Code -->
              <div style="font-size: 22px; font-weight: 800; font-family: \'Courier New\', Courier, monospace; letter-spacing: 3.5px; color: #143320; margin-bottom: 4px;">
                ' . esc($tCode) . '
              </div>
              <div style="font-size: 14px; font-weight: 700; color: #0b1a0d; margin-bottom: 6px;">
                ' . esc($tName) . '
              </div>
              <div style="font-size: 12px; color: #64748b; line-height: 1.5; max-width: 380px; margin: 0 auto;">
                Tunjukkan QR Code ini pada layar HP Anda ke resepsionis gate untuk dipindai saat kedatangan.
              </div>
            </td>
          </tr>
        </table>

        <!-- Detail Kegiatan Box (EMF Theme: Yellow-1 Surface, Yellow-7 Border, Forest Green Header) -->
        <table width="100%" border="0" cellpadding="0" cellspacing="0" style="background-color: #fefdeb; border: 1.5px solid #d2ca30; border-radius: 12px; margin: 22px 0;">
          <tr>
            <td style="padding: 18px 20px;">
              <div style="font-size: 12px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.08em; color: #143320; border-bottom: 1px solid rgba(210, 202, 48, 0.45); padding-bottom: 8px; margin-bottom: 12px;">
                INFORMASI KEGIATAN &amp; ACARA
              </div>
              <table width="100%" border="0" cellpadding="0" cellspacing="0">
                <tr>
                  <td style="padding: 5px 0; font-size: 13.5px; color: #475569; font-weight: 600; width: 120px; vertical-align: top;">Waktu Acara</td>
                  <td style="padding: 5px 0; font-size: 13.5px; color: #0b1a0d; font-weight: 700; vertical-align: top;">: ' . esc($eventDatesStr) . '</td>
                </tr>
                <tr>
                  <td style="padding: 5px 0; font-size: 13.5px; color: #475569; font-weight: 600; vertical-align: top;">Hari Tiket</td>
                  <td style="padding: 5px 0; font-size: 13.5px; color: #1b5319; font-weight: 700; vertical-align: top;">: ' . esc($dayLabel) . '</td>
                </tr>
                <tr>
                  <td style="padding: 5px 0; font-size: 13.5px; color: #475569; font-weight: 600; vertical-align: top;">Lokasi</td>
                  <td style="padding: 5px 0; font-size: 13.5px; color: #0b1a0d; vertical-align: top;">
                    : <strong>' . esc($cleanHotelName) . '</strong>' .
                    (!empty($hotelAddress) ? '<br><span style="color: #64748b; font-size: 12.5px; font-weight: normal; line-height: 1.45; display: inline-block; margin-top: 3px; margin-left: 10px;">' . esc($hotelAddress) . '</span>' : '') . '
                  </td>
                </tr>
                <tr>
                  <td style="padding: 5px 0; font-size: 13.5px; color: #475569; font-weight: 600; vertical-align: top;">Instansi</td>
                  <td style="padding: 5px 0; font-size: 13.5px; color: #0b1a0d; vertical-align: top;">: ' . $company . '</td>
                </tr>' .
                (!empty($jobTitle) ? '
                <tr>
                  <td style="padding: 5px 0; font-size: 13.5px; color: #475569; font-weight: 600; vertical-align: top;">Jabatan</td>
                  <td style="padding: 5px 0; font-size: 13.5px; color: #0b1a0d; vertical-align: top;">: ' . $jobTitle . '</td>
                </tr>' : '') . '
              </table>
            </td>
          </tr>
        </table>

        <p style="font-size: 13px; line-height: 1.6; color: #64748b; margin: 20px 0 0 0;">
          Sampai bertemu di lokasi acara. Apabila Anda memiliki pertanyaan atau kendala, silakan hubungi tim kami atau balas email ini.
        </p>
      </td>
    </tr>

    <!-- Footer (Dark Forest Green EMF) -->
    <tr>
      <td style="background-color: #143320; padding: 18px 24px; text-align: center; border-bottom-left-radius: 14px; border-bottom-right-radius: 14px;">
        <p style="margin: 0; font-size: 11.5px; color: rgba(255, 255, 255, 0.8);">
          &copy; ' . date('Y') . ' Energi &amp; Mineral Festival &bull; B-Universe Media Network
        </p>
      </td>
    </tr>
  </table>
</body>
</html>';
    }

    /**
     * Kirim email konfirmasi tiket / kehadiran ke pemesan (1 Email per 1 Tiket Terpisah)
     */
    protected function sendRegistrationEmail(array $participant, array $tickets, string $orderId = ''): bool
    {
        $targetEmail = $participant['email'] ?? '';
        if (empty($targetEmail)) {
            log_message('error', 'sendRegistrationEmail: target email kosong!');
            return false;
        }

        $allSent = true;
        $ticketCount = count($tickets);
        $emailService = \Config\Services::email();

        $scheduleInfo = $this->resolveScheduleDays();
        $scheduleDaysMap = $scheduleInfo['map'];
        $commonData = $this->getCommonData();

        foreach ($tickets as $idx => $t) {
            $tCode = $t['ticket_code'] ?? '';
            $subject = ($ticketCount > 1)
                ? 'E-Badge Tiket #' . ($idx + 1) . ' (' . esc($tCode) . ') — Energi & Mineral Festival 2026'
                : 'Konfirmasi Registrasi Pengunjung — Energi & Mineral Festival 2026';

            $body = $this->buildRegistrationEmailHtml($participant, $t, $idx, $ticketCount, $scheduleDaysMap, $commonData);

            try {
                $emailService->clear();
                $emailService->setFrom($emailService->fromEmail, $emailService->fromName);
                $emailService->setTo($targetEmail);
                $emailService->setSubject($subject);
                $emailService->setMessage($body);
                $sent = $emailService->send(false);
                if (!$sent) {
                    $allSent = false;
                    log_message('error', 'Gagal kirim email tiket #' . ($idx + 1) . ': ' . $emailService->printDebugger(['headers', 'subject']));
                }
            } catch (\Throwable $e) {
                $allSent = false;
                log_message('error', 'Exception kirim email tiket #' . ($idx + 1) . ': ' . $e->getMessage());
            }
        }

        return $allSent;
    }

    /**
     * Preview Email Konfirmasi Registrasi untuk Keperluan Debug / Testing
     */
    public function previewEmail()
    {
        $commonData = $this->getCommonData();
        $scheduleInfo = $this->resolveScheduleDays();

        $participant = [
            'full_name'    => 'John Doe',
            'email'        => 'johndow@example.com',
            'phone'        => '081234567890',
            'company_name' => 'Dev',
            'job_title'    => 'Dev',
        ];

        $ticket = [
            'ticket_code' => '0335545015204463',
            'full_name'   => 'John Doe',
            'visit_day'   => 'day_1',
        ];

        return $this->buildRegistrationEmailHtml($participant, $ticket, 0, 1, $scheduleInfo['map'], $commonData);
    }

    /**
     * Halaman Sukses & E-Badge Digital ID
     */
    public function success($ticketCode)
    {
        $data = $this->getCommonData();
        $ticketCode = trim(strip_tags((string)$ticketCode));

        $participant = session()->getFlashdata('registered_participant');

        $data['ticketCode'] = $ticketCode;
        $data['participant'] = $participant ?: [
            'ticket_code'  => $ticketCode,
            'full_name'    => 'Pengunjung Terdaftar',
            'company_name' => '',
            'job_title'    => '',
            'event_title'  => $data['site']['title'] ?? 'Energi & Mineral Festival 2026',
        ];

        return view('ticket/success', $data);
    }

    /**
     * Halaman Scanner Resepsionis (Disguised Route: gate/portal_desk_ops_access_verify)
     */
    public function gatePortal()
    {
        return $this->renderGatePortal('scanner', 'Desk Presence Terminal');
    }

    /**
     * Halaman Presensi Undangan (Tanpa Scanner, Card Stats & List Tabel)
     */
    public function gateInvitations()
    {
        return $this->renderGatePortal('invitations', 'Presensi Undangan — Desk Ops');
    }

    /**
     * Helper render terminal portal (Mendukung Tab Scanner vs Presensi Undangan)
     */
    protected function renderGatePortal(string $tab = 'scanner', string $pageTitle = 'Desk Presence Terminal')
    {
        $data = $this->getCommonData();
        $data['isUnlocked']    = true;
        $data['pageTitle']     = $pageTitle;
        $data['activeGateTab'] = $tab;

        // Pre-query data awal (20 data pertama) & telemetri agar halaman langsung tampil instan (tanpa jeda kosong AJAX)
        $db = \Config\Database::connect();
        $event = $db->table('events')->where('slug', $this->eventSlug)->get()->getRowArray();
        
        $initialParticipants = [];
        $initialPagination = [
            'current_page'  => 1,
            'per_page'      => 20,
            'total_records' => 0,
            'total_pages'   => 1,
            'showing_from'  => 0,
            'showing_to'    => 0,
        ];
        $initialStats = [
            'total_registered' => 0,
            'total_checked_in' => 0,
            'unattended'       => 0,
        ];

        if ($event) {
            $totalRegistered = $db->table('participants')
                ->where('event_id', $event['id'])
                ->where('status', 'confirmed')
                ->countAllResults();

            $totalCheckedIn = $db->table('participants')
                ->where('event_id', $event['id'])
                ->where('status', 'confirmed')
                ->where('is_checked_in', 1)
                ->countAllResults();

            $initialStats = [
                'total_registered' => $totalRegistered,
                'total_checked_in' => $totalCheckedIn,
                'unattended'       => max(0, $totalRegistered - $totalCheckedIn),
            ];

            // 20 data peserta pertama
            $perPage = 20;
            $builder = $db->table('participants')->where('event_id', $event['id']);
            $totalRecords = $builder->countAllResults(false);
            $totalPages = max(1, (int)ceil($totalRecords / $perPage));

            $selectFields = 'participants.*, (SELECT COUNT(*) FROM participant_checkins WHERE participant_checkins.participant_id = participants.id) as checkin_count';
            $initialParticipants = $builder->select($selectFields)
                ->orderBy('participants.id', 'desc')
                ->limit($perPage, 0)
                ->get()
                ->getResultArray();

            $initialPagination = [
                'current_page'  => 1,
                'per_page'      => $perPage,
                'total_records' => $totalRecords,
                'total_pages'   => $totalPages,
                'showing_from'  => $totalRecords > 0 ? 1 : 0,
                'showing_to'    => min(count($initialParticipants), $totalRecords),
            ];
        }

        $data['initialParticipants'] = $initialParticipants;
        $data['initialPagination']   = $initialPagination;
        $data['initialStats']        = $initialStats;

        return view('ticket/gate_portal', $data);
    }

    /**
     * Verifikasi 6-Digit Passcode (Fallback jika dipanggil)
     */
    public function verifyGatePasscode()
    {
        return $this->response->setJSON([
            'success' => true,
            'message' => 'Akses terminal dibuka.',
        ]);
    }

    /**
     * Lock / Keluar dari Terminal Resepsionis
     */
    public function lockGatePortal()
    {
        return redirect()->to(base_url('gate/portal_desk_ops_access_verify'));
    }

    /**
     * Halaman Registrasi On-The-Spot (OTS) Resepsionis (Model A Murni: Direct Check-In)
     */
    public function gateRegistration()
    {
        $data = $this->getCommonData();
        $data['isUnlocked']    = true;
        $data['pageTitle']     = 'Registrasi OTS Pengunjung — Desk Ops';
        $data['activeGateTab'] = 'registration';

        return view('ticket/gate_registration', $data);
    }

    /**
     * Submit Registrasi OTS Pengunjung (Otomatis Check-In Langsung & Kirim Email Barcode)
     */
    public function submitGateRegistration()
    {
        $registrationType = trim(strip_tags((string)$this->request->getPost('registration_type') ?: 'personal'));
        if (!in_array($registrationType, ['personal', 'company'])) {
            $registrationType = 'personal';
        }

        $picName      = '';
        $picPhone     = '';
        $companyEmail = '';

        if ($registrationType === 'company') {
            $picName      = trim(strip_tags((string)$this->request->getPost('pic_name')));
            $picPhone     = trim(strip_tags((string)$this->request->getPost('pic_phone')));
            $companyEmail = trim(strip_tags((string)$this->request->getPost('company_email')));

            if (empty($picName) || empty($picPhone) || empty($companyEmail)) {
                return $this->response->setStatusCode(422)->setJSON([
                    'success' => false,
                    'message' => 'Untuk kategori Instansi / Perusahaan, kolom Nama PIC, No. Telp PIC, dan Email Perusahaan wajib diisi.',
                ]);
            }

            if (!filter_var($companyEmail, FILTER_VALIDATE_EMAIL)) {
                return $this->response->setStatusCode(422)->setJSON([
                    'success' => false,
                    'message' => 'Format email perusahaan tidak valid (contoh: corporate@perusahaan.co.id).',
                ]);
            }

            $picPhoneVal = \App\Helpers\PhoneHelper::validate($picPhone);
            if (!$picPhoneVal['valid']) {
                return $this->response->setStatusCode(422)->setJSON([
                    'success' => false,
                    'message' => 'Nomor telepon PIC tidak valid: ' . $picPhoneVal['message'],
                ]);
            }
            $picPhone = $picPhoneVal['normalized'];
        }

        $postData = [
            'full_name'         => trim(strip_tags((string)$this->request->getPost('full_name'))),
            'email'             => trim(strip_tags((string)$this->request->getPost('email'))),
            'phone'             => trim(strip_tags((string)$this->request->getPost('phone'))),
            'address'           => trim(strip_tags((string)$this->request->getPost('address'))),
            'job_title'         => trim(strip_tags((string)$this->request->getPost('job_title'))),
            'company_name'      => trim(strip_tags((string)$this->request->getPost('company_name'))),
            'company_email'     => $companyEmail,
            'visit_day'         => trim(strip_tags((string)$this->request->getPost('visit_day'))),
            'ticket_quantity'   => 1, // OTS individual pass
            'registration_type' => $registrationType,
            'pic_name'          => $picName,
            'pic_phone'         => $picPhone,
            'event_slug'        => $this->eventSlug,
            'auto_checkin'      => 1, // Model A: Langsung berstatus Checked-In
            'checkin_note'      => 'Registrasi OTS Desk Terminal (' . date('d M Y H:i:s') . ')',
        ];

        // Validasi wajib
        if (empty($postData['full_name']) || empty($postData['email']) || empty($postData['phone']) || 
            empty($postData['address']) || empty($postData['job_title']) || empty($postData['company_name'])) {
            return $this->response->setStatusCode(422)->setJSON([
                'success' => false,
                'message' => 'Mohon lengkapi seluruh kolom wajib yang bertanda bintang (*).',
            ]);
        }

        // 1. Validasi Nama
        if (mb_strlen($postData['full_name']) < 3) {
            return $this->response->setStatusCode(422)->setJSON([
                'success' => false,
                'message' => 'Nama lengkap minimal harus 3 karakter.',
            ]);
        }

        // 2. Validasi Email RFC
        if (!filter_var($postData['email'], FILTER_VALIDATE_EMAIL)) {
            return $this->response->setStatusCode(422)->setJSON([
                'success' => false,
                'message' => 'Format email pengunjung tidak valid.',
            ]);
        }

        // 3. Validasi & Normalisasi Nomor Telepon (08..., 9-13 digit, anti-spam)
        $phoneVal = \App\Helpers\PhoneHelper::validate($postData['phone']);
        if (!$phoneVal['valid']) {
            return $this->response->setStatusCode(422)->setJSON([
                'success' => false,
                'message' => $phoneVal['message'],
            ]);
        }
        $postData['phone'] = $phoneVal['normalized'];

        $res = $this->fetchApi('participants/register', 'POST', $postData);
        if (!$res || empty($res['data'])) {
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'message' => 'Gagal menghubungi server pendaftaran. Silakan coba sesaat lagi.',
            ]);
        }

        $code = $res['status_code'];
        $body = $res['data'];

        if ($code === 201 || !empty($body['success'])) {
            $participant = $body['participant'] ?? [];
            if (empty($participant['email']) && !empty($postData['email'])) {
                $participant['email'] = $postData['email'];
            }
            if (empty($participant['full_name']) && !empty($postData['full_name'])) {
                $participant['full_name'] = $postData['full_name'];
            }
            if (empty($participant['company_name']) && !empty($postData['company_name'])) {
                $participant['company_name'] = $postData['company_name'];
            }
            
            $tickets = $body['tickets'] ?? [];
            if (empty($tickets) && !empty($body['ticket_code'])) {
                $tickets = [[
                    'ticket_code'  => $body['ticket_code'],
                    'full_name'    => $participant['full_name'],
                    'visit_day'    => $postData['visit_day'] ?: 'all_days',
                    'ticket_index' => 1,
                ]];
            }

            // Kirim email konfirmasi tiket (Barcode SVG & QR Code) ke pengunjung
            $this->sendRegistrationEmail($participant, $tickets, $body['order_id'] ?? '');

            return $this->response->setJSON([
                'success'         => true,
                'status'          => 'success',
                'message'         => 'Registrasi OTS Berhasil! Tamu langsung berstatus "Sudah Hadir" (Checked-In).',
                'ticket_code'     => $tickets[0]['ticket_code'] ?? ($body['ticket_code'] ?? '-'),
                'checked_in_at'   => date('H:i:s, d M Y'),
                'participant'     => [
                    'full_name'    => $participant['full_name'],
                    'company_name' => $participant['company_name'],
                    'job_title'    => $participant['job_title'] ?? $postData['job_title'],
                    'email'        => $participant['email'],
                    'phone'        => $postData['phone'],
                    'visit_day'    => $postData['visit_day'],
                ],
            ]);
        }

        return $this->response->setStatusCode($code ?: 400)->setJSON([
            'success' => false,
            'message' => $body['message'] ?? 'Gagal memproses registrasi OTS.',
        ]);
    }

    /**
     * Proxy scan checkin dari FE scanner ke BE API
     */
    /**
     * Checkin langsung ke DB lokal (Instant, Bebas Antrean cURL)
     */
     public function proxyCheckin()
     {
         $ticketCode = trim((string)$this->request->getPost('ticket_code'));
         if (empty($ticketCode)) {
             return $this->response->setStatusCode(400)->setJSON([
                 'success' => false,
                 'message' => 'Kode tiket tidak boleh kosong.',
             ]);
         }

         $db = \Config\Database::connect();
         $event = $db->table('events')->where('slug', $this->eventSlug)->get()->getRowArray();
         if (!$event) {
             return $this->response->setStatusCode(404)->setJSON(['success' => false, 'message' => 'Event tidak ditemukan.']);
         }

         $participant = $db->table('participants')
             ->where('event_id', $event['id'])
             ->where('ticket_code', $ticketCode)
             ->get()
             ->getRowArray();

         if (!$participant) {
             return $this->response->setStatusCode(404)->setJSON([
                 'success' => false,
                 'status'  => 'not_found',
                 'message' => 'Kode tiket tidak terdaftar di sistem.',
             ]);
         }

          // 1. Identifikasi Jenis Tiket & Tanggal Aktif Hari Ini
         $visitDay = $participant['visit_day'] ?? 'all_days';
         $isDevelopment = (ENVIRONMENT === 'development');
         $currentDate = date('Y-m-d');
         $now = date('Y-m-d H:i:s');

         // Resolusi dinamis jadwal hari & tanggal event
         $scheduleInfo = $this->resolveScheduleDays();
         $scheduleDaysMap = $scheduleInfo['map'];

         // 2. Ambil seluruh riwayat check-in peserta ini dari tabel log participant_checkins
         $checkinLogs = $db->table('participant_checkins')
             ->where('participant_id', $participant['id'])
             ->orderBy('id', 'ASC')
             ->get()
             ->getResultArray();
         $totalCheckins = count($checkinLogs);

         // Tentukan effective date untuk check-in kali ini
         // Di mode development, jika sudah pernah checkin 1x, otomatis simulasikan hari berikutnya agar leluasa testing multi-day
         $effectiveDate = $currentDate;
         if ($isDevelopment && $visitDay === 'all_days') {
             if ($totalCheckins === 1 && $checkinLogs[0]['checkin_date'] === $currentDate) {
                 $effectiveDate = date('Y-m-d', strtotime('+1 day', strtotime($currentDate)));
             }
         }

         // Cek apakah peserta sudah pernah check-in di tanggal yang sama (effectiveDate)
         $todayLog = null;
         foreach ($checkinLogs as $log) {
             if ($log['checkin_date'] === $effectiveDate) {
                 $todayLog = $log;
                 break;
             }
         }

         // 3. Validasi Hak Akses Tiket: 1-Day Pass vs Multi-Day Pass (all_days)
         if ($visitDay !== 'all_days') {
             // TIKET 1-DAY PASS (day_1, day_2, day_3, dst):
             // Syarat 1: Jika sudah pernah check-in 1 kali saja seumur hidup -> LANGSUNG TOLAK (Maksimal 1x pakai)
             if ($totalCheckins >= 1) {
                 $firstLog = $checkinLogs[0];
                 return $this->response->setStatusCode(409)->setJSON([
                     'success'       => false,
                     'status'        => 'already_checked_in',
                     'message'       => 'Tiket 1-Day Pass sudah pernah digunakan pada ' . date('H:i:s, d M Y', strtotime($firstLog['checkin_time'])) . ' dan sudah tidak berlaku lagi.',
                     'checked_in_at' => date('H:i:s, d M Y', strtotime($firstLog['checkin_time'])),
                     'participant'   => [
                         'full_name'    => $participant['full_name'],
                         'company_name' => $participant['company_name'],
                         'job_title'    => $participant['job_title'],
                         'ticket_code'  => $participant['ticket_code'],
                         'visit_day'    => $visitDay,
                     ],
                 ]);
             }

             // Syarat 2: Keselarasan Tanggal Dinamis (Hanya diaktifkan saat bukan development)
             $dayInfo = $scheduleDaysMap[$visitDay] ?? null;
             $targetDate = $dayInfo['date_ymd'] ?? null;
             $targetLabel = $dayInfo['label'] ?? ('Hari ' . str_replace('day_', '', $visitDay));
             $targetFormatted = $dayInfo['short_date'] ?? $targetDate;

             if (!$isDevelopment && !empty($targetDate)) {
                 if ($currentDate !== $targetDate) {
                     return $this->response->setStatusCode(422)->setJSON([
                         'success'     => false,
                         'status'      => 'invalid_day',
                         'message'     => 'Tiket ini terdaftar khusus untuk ' . $targetLabel . ' (' . $targetFormatted . '). Tidak dapat digunakan pada tanggal ' . $this->formatIndonesianDate($currentDate, false, true) . '.',
                         'participant' => [
                             'full_name'    => $participant['full_name'],
                             'company_name' => $participant['company_name'],
                             'ticket_code'  => $participant['ticket_code'],
                             'visit_day'    => $visitDay,
                         ],
                     ]);
                 }
             }

             $successMsg = 'Check-in ' . $targetLabel . ' Berhasil! (Tiket 1-Day Digunakan)';
         } else {
             // TIKET MULTI-DAY / ALL-DAYS PASS:
             // Syarat 1: Cek apakah hari ini sudah pernah check-in
             if ($todayLog !== null) {
                 return $this->response->setStatusCode(409)->setJSON([
                     'success'       => false,
                     'status'        => 'already_checked_in',
                     'message'       => 'Tiket Semua Hari (Multi-Pass) sudah check-in hari ini pada jam ' . date('H:i:s', strtotime($todayLog['checkin_time'])) . '. Tiket dapat digunakan kembali di hari berikutnya.',
                     'checked_in_at' => date('H:i:s, d M Y', strtotime($todayLog['checkin_time'])),
                     'participant'   => [
                         'full_name'    => $participant['full_name'],
                         'company_name' => $participant['company_name'],
                         'job_title'    => $participant['job_title'],
                         'ticket_code'  => $participant['ticket_code'],
                         'visit_day'    => $visitDay,
                     ],
                 ]);
             }

             $kehadiranKe = $totalCheckins + 1;
             $successMsg = ($kehadiranKe === 1)
                 ? 'Check-in Hari 1 Berhasil! (Tiket Semua Hari Aktif)'
                 : 'Check-in Hari ke-' . $kehadiranKe . ' Berhasil! Selamat Datang Kembali.';
         }

         // 4. Simpan Log Check-In Baru ke tabel participant_checkins
         $db->table('participant_checkins')->insert([
             'participant_id'  => $participant['id'],
             'event_id'        => $event['id'],
             'checkin_date'    => $effectiveDate,
             'checkin_time'    => $now,
             'gate_officer_id' => session()->get('admin_id') ?: null,
             'note'            => $successMsg,
             'created_at'      => $now,
         ]);

         // Update status utama di tabel participants
         $db->table('participants')->where('id', $participant['id'])->update([
             'is_checked_in' => 1,
             'checked_in_at' => $now,
         ]);

         return $this->response->setJSON([
             'success'       => true,
             'status'        => 'success',
             'message'       => $successMsg,
             'checked_in_at' => date('H:i:s, d M Y', strtotime($now)),
             'participant'   => [
                 'full_name'    => $participant['full_name'],
                 'company_name' => $participant['company_name'],
                 'job_title'    => $participant['job_title'],
                 'ticket_code'  => $participant['ticket_code'],
                 'visit_day'    => $visitDay,
                 'email'        => $participant['email'],
                 'phone'        => $participant['phone'],
             ],
         ]);
     }

    /**
     * Pencarian peserta langsung dari DB lokal dengan Pagination (default 5 item), Sorting, dan Multi-Filter (VLC style)
     */
    public function proxySearch()
    {
        $db = \Config\Database::connect();
        $event = $db->table('events')->where('slug', $this->eventSlug)->get()->getRowArray();
        if (!$event) {
            return $this->response->setJSON([
                'success' => true,
                'data' => [],
                'pagination' => [
                    'current_page'  => 1,
                    'per_page'      => 20,
                    'total_records' => 0,
                    'total_pages'   => 1,
                    'showing_from'  => 0,
                    'showing_to'    => 0,
                ],
            ]);
        }

        $q          = trim((string)$this->request->getGet('q'));
        $status     = trim((string)$this->request->getGet('status')); // '', 'checked_in', 'unattended'
        $visitDay   = trim((string)$this->request->getGet('visit_day')); // '', 'day_1', 'day_2', 'all_days'
        $sortCol    = trim((string)$this->request->getGet('sort') ?: 'id');
        $sortDir    = strtolower(trim((string)$this->request->getGet('order') ?: 'desc')) === 'asc' ? 'asc' : 'desc';
        $page       = max(1, (int)($this->request->getGet('page') ?: 1));
        $perPage    = max(1, min(100, (int)($this->request->getGet('per_page') ?: 20))); // default 20 data/page

        // Validasi sort column
        $allowedSort = [
            'id'            => 'participants.id',
            'full_name'     => 'participants.full_name',
            'company_name'  => 'participants.company_name',
            'visit_day'     => 'participants.visit_day',
            'is_checked_in' => 'participants.is_checked_in',
            'ticket_code'   => 'participants.ticket_code',
        ];
        $orderBySql = $allowedSort[$sortCol] ?? 'participants.id';

        // Base query
        $builder = $db->table('participants')->where('event_id', $event['id']);

        if ($status === 'checked_in') {
            $builder->where('is_checked_in', 1);
        } elseif ($status === 'unattended') {
            $builder->where('is_checked_in', 0);
        }

        if (!empty($visitDay) && in_array($visitDay, ['day_1', 'day_2', 'all_days'])) {
            $builder->where('visit_day', $visitDay);
        }

        if (!empty($q) && $q !== 'all') {
            $builder->groupStart()
                ->like('full_name', $q)
                ->orLike('email', $q)
                ->orLike('phone', $q)
                ->orLike('ticket_code', $q)
                ->orLike('company_name', $q)
            ->groupEnd();
        }

        // Hitung total records
        $totalRecords = $builder->countAllResults(false);
        $totalPages   = max(1, (int)ceil($totalRecords / $perPage));
        if ($page > $totalPages) {
            $page = $totalPages;
        }
        $offset = ($page - 1) * $perPage;

        // Ambil data halaman aktif
        $selectFields = 'participants.*, (SELECT COUNT(*) FROM participant_checkins WHERE participant_checkins.participant_id = participants.id) as checkin_count';
        $builder->select($selectFields)
            ->orderBy($orderBySql, $sortDir)
            ->limit($perPage, $offset);

        $results = $builder->get()->getResultArray();

        $showingFrom = $totalRecords > 0 ? ($offset + 1) : 0;
        $showingTo   = min($offset + count($results), $totalRecords);

        return $this->response->setJSON([
            'success' => true,
            'data'    => $results,
            'pagination' => [
                'current_page'  => $page,
                'per_page'      => $perPage,
                'total_records' => $totalRecords,
                'total_pages'   => $totalPages,
                'showing_from'  => $showingFrom,
                'showing_to'    => $showingTo,
            ],
            'sorting' => [
                'col'   => $sortCol,
                'order' => $sortDir,
            ],
            'filters' => [
                'q'         => $q,
                'status'    => $status,
                'visit_day' => $visitDay,
            ],
        ]);
    }

    /**
     * Telemetry statistik kehadiran langsung dari DB lokal (Instant Response)
     */
    public function proxyStats()
    {
        $db = \Config\Database::connect();
        $event = $db->table('events')->where('slug', $this->eventSlug)->get()->getRowArray();
        if (!$event) {
            return $this->response->setJSON(['total_registered' => 0, 'total_checked_in' => 0, 'unattended' => 0]);
        }

        $totalRegistered = $db->table('participants')
            ->where('event_id', $event['id'])
            ->where('status', 'confirmed')
            ->countAllResults();

        $totalCheckedIn = $db->table('participants')
            ->where('event_id', $event['id'])
            ->where('status', 'confirmed')
            ->where('is_checked_in', 1)
            ->countAllResults();

        $unattended = max(0, $totalRegistered - $totalCheckedIn);

        return $this->response->setJSON([
            'success'          => true,
            'quota'            => (int)($event['quota'] ?? 0),
            'total_registered' => $totalRegistered,
            'total_checked_in' => $totalCheckedIn,
            'unattended'       => $unattended,
        ]);
    }
}
