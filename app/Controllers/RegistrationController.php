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

        return [
            'site'            => $site,
            'themeProperties' => $live['theme_properties'] ?? [],
            'event'           => $live ?? [],
            'isSoldOut'       => $isSoldOut,
            'remainingQuota'  => $remainingQuota,
            'totalQuota'      => $quota,
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
        $postData = [
            'full_name'       => trim(strip_tags((string)$this->request->getPost('full_name'))),
            'email'           => trim(strip_tags((string)$this->request->getPost('email'))),
            'phone'           => trim(strip_tags((string)$this->request->getPost('phone'))),
            'address'         => trim(strip_tags((string)$this->request->getPost('address'))),
            'job_title'       => trim(strip_tags((string)$this->request->getPost('job_title'))),
            'company_name'    => trim(strip_tags((string)$this->request->getPost('company_name'))),
            'visit_day'       => trim(strip_tags((string)$this->request->getPost('visit_day'))),
            'ticket_quantity' => min(5, max(1, (int)$this->request->getPost('ticket_quantity') ?: 1)),
            'force_duplicate' => (int)$this->request->getPost('force_duplicate') ?: 0,
            'pic_name'        => trim(strip_tags((string)$this->request->getPost('pic_name'))),
            'pic_email'       => trim(strip_tags((string)$this->request->getPost('pic_email'))),
            'pic_phone'       => trim(strip_tags((string)$this->request->getPost('pic_phone'))),
            'source_info'     => trim(strip_tags((string)$this->request->getPost('source_info'))),
            'event_slug'      => $this->eventSlug,
        ];

        $isAjax = $this->request->isAJAX() || $this->request->getHeaderLine('Accept') === 'application/json';

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

        foreach ($tickets as $idx => $t) {
            $tCode = $t['ticket_code'] ?? '';
            $tName = $t['full_name'] ?? $participant['full_name'];
            $vDay  = $t['visit_day'] ?? 'all_days';
            
            $dayLabel = 'Semua Hari (10 - 11 Nov 2026)';
            if ($vDay === 'day_1') $dayLabel = 'Hari 1 — Selasa, 10 Nov 2026';
            if ($vDay === 'day_2') $dayLabel = 'Hari 2 — Rabu, 11 Nov 2026';

            // QR Server API (Aktif, Bebas Rate Limit, Format PNG 180x180)
            $qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=180x180&margin=1&data=' . urlencode($tCode);

            $subject = ($ticketCount > 1)
                ? 'E-Badge Tiket #' . ($idx + 1) . ' (' . esc($tCode) . ') — Energi & Mineral Festival 2026'
                : 'Konfirmasi Registrasi Pengunjung — Energi & Mineral Festival 2026';

            $body = '
            <!DOCTYPE html>
            <html>
            <head>
              <meta charset="utf-8">
              <meta name="viewport" content="width=device-width, initial-scale=1.0">
            </head>
            <body style="margin: 0; padding: 24px 0; background-color: #f8fafc; font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', Roboto, Helvetica, Arial, sans-serif; color: #1e293b;">
              <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 580px; background-color: #ffffff; border-radius: 12px; overflow: hidden; border: 1px solid #e2e8f0; box-shadow: 0 4px 12px rgba(0,0,0,0.05); margin: 0 auto;">
                <!-- Header Kuning EMF -->
                <tr>
                  <td style="background-color: #f4d849; padding: 28px 24px; text-align: center;">
                    <div style="font-size: 11px; font-weight: 800; letter-spacing: 0.12em; text-transform: uppercase; color: #214415; margin-bottom: 6px;">
                      10 &ndash; 11 NOVEMBER 2026 &bull; JAKARTA, INDONESIA
                    </div>
                    <h1 style="margin: 0; font-size: 22px; font-weight: 900; color: #143320; letter-spacing: -0.01em;">
                      Energi & Mineral Festival 2026
                    </h1>
                  </td>
                </tr>

                <!-- Content Body -->
                <tr>
                  <td style="padding: 28px 24px;">
                    <p style="font-size: 14.5px; line-height: 1.6; margin: 0 0 16px 0;">
                      Kepada Yth. <strong>' . esc($tName) . '</strong>,
                    </p>
                    <p style="font-size: 14px; line-height: 1.6; color: #334155; margin: 0 0 20px 0;">
                      Terima kasih atas pendaftaran Anda. Registrasi Anda untuk menghadiri <strong>Energi & Mineral Festival 2026</strong> telah berhasil kami konfirmasi ' . ($ticketCount > 1 ? '(Tiket ' . ($idx + 1) . ' dari ' . $ticketCount . ')' : '') . '. Simpan e-badge tiket ini untuk verifikasi kehadiran di meja resepsionis saat acara berlangsung.
                    </p>

                    <!-- Box Barcode / QR Code Tiket -->
                    <div style="background-color: #ffffff; border: 2px solid #edf5ea; box-shadow: 0 4px 14px rgba(73,152,46,0.10); border-radius: 12px; padding: 22px 20px; text-align: center; margin: 20px 0;">
                      <div style="font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.1em; color: #346c21; margin-bottom: 6px;">
                        TIKET MASUK ELEKTRONIK ' . ($ticketCount > 1 ? '#' . ($idx + 1) : '') . '
                      </div>
                      <div style="display: inline-block; background-color: #f0fdf4; border: 1px solid #86efac; color: #166534; font-size: 11.5px; font-weight: 700; border-radius: 6px; padding: 3px 10px; margin-bottom: 14px;">
                        ' . esc($dayLabel) . '
                      </div>
                      
                      <!-- Barcode QR Code Image -->
                      <div style="text-align: center; margin: 0 auto 12px auto;">
                        <img src="' . $qrUrl . '" alt="QR ' . esc($tCode) . '" width="170" height="170" style="display: block; margin: 0 auto; border: 1px solid #e2e8f0; border-radius: 8px; padding: 6px; background: #ffffff;">
                      </div>

                      <div style="font-size: 21px; font-weight: 800; font-family: monospace; letter-spacing: 3px; color: #214415; margin-bottom: 4px;">
                        ' . esc($tCode) . '
                      </div>
                      <div style="font-size: 13.5px; font-weight: 700; color: #1e293b; margin-bottom: 6px;">
                        ' . esc($tName) . '
                      </div>
                      <div style="font-size: 12px; color: #64748b; line-height: 1.4;">
                        Tunjukkan QR Code ini pada layar HP Anda ke resepsionis gate untuk dipindai saat kedatangan.
                      </div>
                    </div>

                    <!-- Detail Pameran Box (Mirip Contoh China Homelife) -->
                    <table width="100%" border="0" cellpadding="0" cellspacing="0" style="background-color: #fdfbee; border: 1px solid #f8e68c; border-radius: 10px; padding: 18px 20px; margin: 22px 0;">
                      <tr>
                        <td>
                          <div style="font-size: 12px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.06em; color: #8b7b2a; margin-bottom: 12px;">
                            INFORMASI KEGIATAN & ACARA
                          </div>
                          <div style="font-size: 13.5px; line-height: 1.8; color: #334155;">
                            <div><strong>Waktu:</strong> 10 &ndash; 11 November 2026</div>
                            <div><strong>Lokasi:</strong> Jakarta, Indonesia</div>
                            <div><strong>Instansi:</strong> ' . esc($participant['company_name'] ?: 'Umum') . '</div>
                            ' . (!empty($participant['job_title']) ? '<div><strong>Jabatan:</strong> ' . esc($participant['job_title']) . '</div>' : '') . '
                          </div>
                        </td>
                      </tr>
                    </table>

                    <p style="font-size: 13px; line-height: 1.6; color: #64748b; margin: 20px 0 0 0;">
                      Sampai bertemu di lokasi acara. Apabila Anda memiliki pertanyaan, silakan hubungi tim kami atau balas email ini.
                    </p>
                  </td>
                </tr>

                <!-- Footer -->
                <tr>
                  <td style="background-color: #f8fafc; border-top: 1px solid #e2e8f0; padding: 16px 24px; text-align: center;">
                    <p style="margin: 0; font-size: 11.5px; color: #94a3b8;">
                      &copy; 2026 Energi & Mineral Festival &bull; B-Universe Media Holdings
                    </p>
                  </td>
                </tr>
              </table>
            </body>
            </html>';

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
        $data = $this->getCommonData();
        $data['isUnlocked'] = true;
        $data['pageTitle']  = 'Desk Presence Terminal';

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
        $data['isUnlocked'] = true;
        $data['pageTitle']  = 'Registrasi OTS Pengunjung — Desk Ops';

        return view('ticket/gate_registration', $data);
    }

    /**
     * Submit Registrasi OTS Pengunjung (Otomatis Check-In Langsung & Kirim Email Barcode)
     */
    public function submitGateRegistration()
    {
        $postData = [
            'full_name'       => trim(strip_tags((string)$this->request->getPost('full_name'))),
            'email'           => trim(strip_tags((string)$this->request->getPost('email'))),
            'phone'           => trim(strip_tags((string)$this->request->getPost('phone'))),
            'address'         => trim(strip_tags((string)$this->request->getPost('address'))),
            'job_title'       => trim(strip_tags((string)$this->request->getPost('job_title'))),
            'company_name'    => trim(strip_tags((string)$this->request->getPost('company_name'))),
            'visit_day'       => trim(strip_tags((string)$this->request->getPost('visit_day'))),
            'ticket_quantity' => 1, // OTS individual pass
            'event_slug'      => $this->eventSlug,
            'auto_checkin'    => 1, // Model A: Langsung berstatus Checked-In
            'checkin_note'    => 'Registrasi OTS Desk Terminal (' . date('d M Y H:i:s') . ')',
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
         $eventDay1 = '2026-11-10';
         $eventDay2 = '2026-11-11';
         $now = date('Y-m-d H:i:s');

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
         if ($visitDay === 'day_1' || $visitDay === 'day_2') {
             // TIKET 1-DAY PASS:
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

             // Syarat 2: Keselarasan Tanggal (Hanya diaktifkan saat bukan development)
             if (!$isDevelopment) {
                 if ($visitDay === 'day_1' && $currentDate !== $eventDay1) {
                     return $this->response->setStatusCode(422)->setJSON([
                         'success'     => false,
                         'status'      => 'invalid_day',
                         'message'     => 'Tiket ini terdaftar khusus untuk HARI 1 (10 Nov 2026). Tidak dapat digunakan pada tanggal ' . date('d M Y') . '.',
                         'participant' => [
                             'full_name'    => $participant['full_name'],
                             'company_name' => $participant['company_name'],
                             'ticket_code'  => $participant['ticket_code'],
                             'visit_day'    => $visitDay,
                         ],
                     ]);
                 }
                 if ($visitDay === 'day_2' && $currentDate !== $eventDay2) {
                     return $this->response->setStatusCode(422)->setJSON([
                         'success'     => false,
                         'status'      => 'invalid_day',
                         'message'     => 'Tiket ini terdaftar khusus untuk HARI 2 (11 Nov 2026). Tidak dapat digunakan pada tanggal ' . date('d M Y') . '.',
                         'participant' => [
                             'full_name'    => $participant['full_name'],
                             'company_name' => $participant['company_name'],
                             'ticket_code'  => $participant['ticket_code'],
                             'visit_day'    => $visitDay,
                         ],
                     ]);
                 }
             }

             $dayLabel = ($visitDay === 'day_1') ? 'Hari 1' : 'Hari 2';
             $successMsg = 'Check-in ' . $dayLabel . ' Berhasil! (Tiket 1-Day Digunakan)';
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
