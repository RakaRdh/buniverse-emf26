<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Desk Presence Terminal — <?= esc($site['title'] ?? 'Energi & Mineral Festival 2026') ?></title>
  
  <link rel="shortcut icon" type="image/x-icon" href="<?= base_url('favicon.ico') ?>?v=<?= file_exists(FCPATH . 'favicon.ico') ? filemtime(FCPATH . 'favicon.ico') : time() ?>">
  <link rel="icon" type="image/x-icon" href="<?= base_url('favicon.ico') ?>?v=<?= file_exists(FCPATH . 'favicon.ico') ? filemtime(FCPATH . 'favicon.ico') : time() ?>">
  
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@300;400;500;600;700;800&family=Playfair+Display:ital,wght@0,700;0,800;0,900;1,700&display=swap" rel="stylesheet">
  
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">

  <style>
    html, body {
      background-color: var(--yellow-6, #f4d849) !important;
      color: var(--green-10, #214415);
      font-family: 'Manrope', sans-serif;
      margin: 0;
      padding: 0;
      min-height: 100%;
    }

    .terminal-wrapper {
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: flex-start;
      padding: 32px 20px 60px 20px;
      box-sizing: border-box;
      background-color: var(--yellow-6, #f4d849);
    }

    .terminal-container {
      width: 100%;
      max-width: 760px;
      margin: 0 auto;
    }

    /* PASSCODE LOCK SCREEN */
    .pin-card {
      background: #ffffff;
      border: 1px solid rgba(73, 152, 46, 0.25);
      box-shadow: 0 8px 24px rgba(33, 68, 21, 0.08);
      border-radius: 16px;
      padding: 36px 28px;
      text-align: center;
      max-width: 420px;
      margin: 40px auto 0 auto;
    }

    .pin-input {
      letter-spacing: 0.3em;
      font-size: 24px;
      font-weight: 800;
      text-align: center;
      background: #ffffff;
      border: 1.5px solid rgba(73, 152, 46, 0.35);
      border-radius: 12px;
      padding: 14px;
      width: 100%;
      color: var(--green-10, #214415);
      font-family: monospace;
      outline: none;
      box-sizing: border-box;
      margin-bottom: 20px;
    }

    .pin-input:focus {
      border-color: var(--green-6, #49982e);
      box-shadow: 0 0 0 3px rgba(73, 152, 46, 0.15);
    }

    .btn-pin-submit {
      width: 100%;
      background: var(--green-6, #49982e);
      color: #ffffff;
      border: none;
      border-radius: 10px;
      padding: 14px;
      font-size: 15px;
      font-weight: 700;
      cursor: pointer;
      font-family: inherit;
      transition: background 0.2s ease;
    }

    .btn-pin-submit:hover {
      background: var(--green-7, #3e8127);
    }

    /* UNLOCKED TERMINAL DASHBOARD */
    .terminal-topbar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 24px;
      padding-bottom: 16px;
      border-bottom: 1px solid rgba(73, 152, 46, 0.25);
    }

    .telemetry-badges {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 12px;
      margin-bottom: 24px;
    }

    .stat-badge {
      background: #ffffff;
      border: 1px solid rgba(73, 152, 46, 0.25);
      box-shadow: 0 4px 12px rgba(33, 68, 21, 0.05);
      border-radius: 12px;
      padding: 14px 16px;
      text-align: center;
    }

    .stat-badge .lbl {
      font-size: 11px;
      text-transform: uppercase;
      font-weight: 700;
      letter-spacing: 0.05em;
      color: var(--green-8, #346c21);
      margin-bottom: 4px;
    }

    .stat-badge .num {
      font-size: 24px;
      font-weight: 800;
      color: var(--green-10, #214415);
    }

    .scanner-box {
      background: #ffffff;
      border: 1px solid rgba(73, 152, 46, 0.25);
      box-shadow: 0 8px 24px rgba(33, 68, 21, 0.08);
      border-radius: 16px;
      padding: 24px;
      margin-bottom: 24px;
    }

    #reader {
      width: 100%;
      border-radius: 12px;
      overflow: hidden;
      background: #000000;
      margin-bottom: 16px;
      position: relative;
    }

    /* Hilangkan tulisan "Scanner Paused" bawaan library html5-qrcode */
    #reader span[style*="position: absolute"],
    #reader div[style*="position: absolute"] {
      display: none !important;
    }

    .btn-manual-in {
      background: #15803d;
      color: #ffffff !important;
      border: 1px solid #166534;
      border-radius: 9999px;
      padding: 7px 16px;
      font-size: 12.5px;
      font-weight: 800;
      cursor: pointer;
      font-family: inherit;
      display: inline-flex;
      align-items: center;
      gap: 6px;
      box-shadow: 0 2px 8px rgba(21, 128, 61, 0.3);
      transition: all 0.2s ease;
      letter-spacing: 0.02em;
    }

    .btn-manual-in:hover {
      background: #166534;
      transform: translateY(-1px);
      box-shadow: 0 4px 12px rgba(21, 128, 61, 0.4);
    }

    .btn-manual-in:active {
      transform: scale(0.96);
    }

    .btn-manual-in.active-cam {
      background: #dc2626 !important;
      border-color: #b91c1c !important;
      box-shadow: 0 2px 8px rgba(220, 38, 38, 0.35) !important;
    }

    .btn-manual-in.active-cam:hover {
      background: #b91c1c !important;
    }

    .scan-input-group {
      display: flex;
      gap: 10px;
      margin-top: 14px;
    }

    .scan-text-input {
      flex: 1;
      background: #ffffff;
      border: 1.5px solid rgba(73, 152, 46, 0.35);
      border-radius: 10px;
      padding: 12px 14px;
      font-size: 15px;
      color: var(--green-10, #214415);
      font-family: monospace;
      outline: none;
      transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    .scan-text-input:focus {
      border-color: var(--green-6, #49982e);
      box-shadow: 0 0 0 3px rgba(73, 152, 46, 0.15);
    }

    .scan-submit-btn {
      background: var(--green-6, #49982e);
      color: #ffffff;
      border: none;
      border-radius: 10px;
      padding: 12px 20px;
      font-size: 14px;
      font-weight: 700;
      cursor: pointer;
      font-family: inherit;
      transition: background 0.2s ease;
    }

    .scan-submit-btn:hover {
      background: var(--green-7, #3e8127);
    }

    /* RESULT FEEDBACK MODAL / BANNER */
    .scan-feedback {
      display: none;
      padding: 16px 20px;
      border-radius: 12px;
      margin-top: 16px;
      text-align: left;
    }

    .scan-feedback.success {
      background: #f0fdf4;
      border: 1.5px solid #22c55e;
      color: #15803d;
    }

    .scan-feedback.warning {
      background: #fef2f2;
      border: 1.5px solid #ef4444;
      color: #b91c1c;
    }

    /* LIVE SEARCH FALLBACK & LIST PESERTA */
    .search-results-box {
      margin-top: 14px;
      display: flex;
      flex-direction: column;
      gap: 10px;
      max-height: 420px;
      overflow-y: auto;
      padding-right: 4px;
    }

    .participant-card {
      display: flex;
      justify-content: space-between;
      align-items: center;
      background: #ffffff;
      border: 1.5px solid #d1fae5;
      border-radius: 12px;
      padding: 14px 18px;
      box-sizing: border-box;
      transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    .participant-card:hover {
      border-color: #86efac;
      box-shadow: 0 2px 8px rgba(73, 152, 46, 0.08);
    }

    .participant-name {
      font-weight: 700;
      color: #143320;
      font-size: 15px;
      margin-bottom: 2px;
    }

    .participant-meta {
      font-size: 13px;
      color: #64748b;
      margin-bottom: 4px;
    }

    .participant-code {
      font-family: monospace;
      font-size: 12.5px;
      font-weight: 600;
      color: #143320;
    }

    .badge-hadir {
      display: inline-flex;
      align-items: center;
      gap: 4px;
      background: #dcfce7;
      color: #15803d;
      border: 1.5px solid #86efac;
      border-radius: 8px;
      padding: 6px 14px;
      font-size: 13px;
      font-weight: 700;
      user-select: none;
    }

    .badge-belum-masuk {
      display: inline-flex;
      align-items: center;
      background: #f1f5f9;
      color: #64748b;
      border: 1.5px solid #cbd5e1;
      border-radius: 8px;
      padding: 6px 14px;
      font-size: 13px;
      font-weight: 700;
      user-select: none;
    }
  </style>

  <!-- HTML5 QR Code library -->
  <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
</head>
<body>

<div class="terminal-wrapper">
  <div class="terminal-container">

    <?php if (!$isUnlocked): ?>
      <!-- 1. PASSCODE LOCKED SCREEN -->
      <div style="text-align: center; margin-top: 30px;">
        <img src="<?= base_url('assets/img/logo_green_emf26.webp') ?>" alt="Logo" style="max-height: 48px; margin-bottom: 16px;">
        <h2 style="font-size: 22px; margin: 0 0 6px 0; font-weight: 800; color: var(--green-10, #214415);">Desk Presence Terminal</h2>
        <p style="font-size: 13.5px; color: var(--green-8, #346c21); margin: 0; font-weight: 500;">Masukkan 6 digit PIN otoritas gate untuk membuka terminal pemindaian</p>
      </div>

      <div class="pin-card">
        <div id="pinErrorMsg" style="display: none; background: #fee2e2; border: 1px solid #ef4444; color: #b91c1c; padding: 10px 14px; border-radius: 8px; font-size: 13px; margin-bottom: 16px;"></div>

        <form id="passcodeForm">
          <input type="password" id="inputPasscode" class="pin-input" maxlength="6" pattern="[0-9]*" inputmode="numeric" placeholder="••••••" autofocus required>
          <button type="submit" id="btnUnlock" class="btn-pin-submit">Buka Terminal</button>
        </form>
      </div>

    <?php else: ?>
      <!-- 2. UNLOCKED SCANNER SCREEN -->
      <div class="terminal-topbar">
        <div>
          <div style="font-size: 11px; text-transform: uppercase; color: var(--green-7, #3e8127); font-weight: 800; letter-spacing: 0.08em;">Terminal Aktif</div>
          <h2 style="font-size: 20px; font-weight: 800; margin: 2px 0 0 0; color: var(--green-10, #214415);"><?= esc($site['title'] ?? 'Energi & Mineral Festival 2026') ?></h2>
        </div>
        <div style="display: flex; gap: 8px; align-items: center;">
          <a href="<?= base_url('gate/portal_desk_ops_access_verify/lock') ?>" class="btn-pin-submit" style="padding: 8px 16px; font-size: 12px; text-decoration: none; background: #dc2626; color: #ffffff;">
            Kunci / Keluar
          </a>
        </div>
      </div>

      <!-- Telemetry row -->
      <div class="telemetry-badges">
        <div class="stat-badge">
          <div class="lbl">Total Terdaftar</div>
          <div class="num" id="statRegistered">-</div>
        </div>
        <div class="stat-badge" style="border-color: rgba(34, 197, 94, 0.4);">
          <div class="lbl" style="color: #4ade80;">Sudah Hadir</div>
          <div class="num" id="statCheckedIn" style="color: #4ade80;">-</div>
        </div>
        <div class="stat-badge">
          <div class="lbl">Belum Masuk</div>
          <div class="num" id="statUnattended">-</div>
        </div>
      </div>

      <!-- Scanner Interface -->
      <div class="scanner-box" id="scannerSection">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px;">
          <div style="font-size: 13.5px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; color: var(--green-9, #2a571a);">Pemindai QR Tiket</div>
          <button id="toggleCameraBtn" class="btn-manual-in">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path><circle cx="12" cy="13" r="4"></circle></svg>
            <span>Nyalakan Kamera</span>
          </button>
        </div>

        <div id="reader"></div>

        <!-- Manual Barcode / Ticket Code Input -->
        <form id="manualCheckinForm">
          <div class="scan-input-group">
            <input type="text" id="manualTicketCode" class="scan-text-input" placeholder="Scan barcode USB atau ketik kode tiket 16-digit..." autocomplete="off">
            <button type="submit" class="scan-submit-btn">Verifikasi</button>
          </div>
        </form>

        <!-- Feedback Alert Container -->
        <div id="scanFeedback" class="scan-feedback">
          <div id="feedbackTitle" style="font-weight: 800; font-size: 16px; margin-bottom: 4px;"></div>
          <div id="feedbackDesc" style="font-size: 13px; line-height: 1.4;"></div>
        </div>
      </div>

      <!-- Daftar Peserta & Pencarian Cepat -->
      <div class="scanner-box" style="padding: 24px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; flex-wrap: wrap; gap: 8px;">
          <div style="font-size: 13.5px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.04em; color: var(--green-9, #2a571a);">
            DAFTAR PESERTA & PENCARIAN CEPAT
          </div>
          <button type="button" onclick="loadRecentParticipants()" style="font-size: 13px; color: var(--green-8, #346c21); cursor: pointer; text-decoration: underline; background: none; border: none; font-weight: 700;">
            Muat Ulang List
          </button>
        </div>
        <input type="text" id="searchInput" class="scan-text-input" style="width: 100%; box-sizing: border-box; font-family: inherit;" placeholder="Cari nama, email, no HP, instansi, atau kode tiket...">
        
        <div id="searchResults" class="search-results-box">
          <div style="padding: 20px; text-align: center; color: var(--green-8, #346c21); font-size: 13px;">Memuat data peserta...</div>
        </div>
      </div>

    <?php endif; ?>

  </div>
</div>

<!-- MODAL POPUP HASIL SCAN / VERIFIKASI -->
<div id="scanResultModal" style="display: none; position: fixed; inset: 0; background: rgba(0, 0, 0, 0.65); z-index: 99999; justify-content: center; align-items: center; padding: 20px; box-sizing: border-box;">
  <div style="background: #ffffff; width: 100%; max-width: 440px; border-radius: 16px; overflow: hidden; box-shadow: 0 20px 40px rgba(0,0,0,0.3); text-align: center; animation: modalIn 0.2s ease;">
    <!-- Modal Status Icon Bar -->
    <div id="modalStatusHeader" style="padding: 24px 20px 16px 20px;">
      <div id="modalStatusIcon" style="width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto; font-size: 30px; font-weight: 900;">
        &check;
      </div>
      <h3 id="modalStatusTitle" style="margin: 14px 0 4px 0; font-size: 20px; font-weight: 800;">
        STATUS SCAN
      </h3>
      <p id="modalStatusSub" style="margin: 0; font-size: 13.5px; color: #64748b; line-height: 1.4;">
        Deskripsi status tiket
      </p>
    </div>

    <!-- Modal Content Details -->
    <div id="modalParticipantBox" style="background: #f8fafc; border-top: 1px solid #e2e8f0; border-bottom: 1px solid #e2e8f0; padding: 16px 20px; text-align: left; font-size: 13.5px; color: #1e293b; line-height: 1.6;">
      <div><strong>Nama:</strong> <span id="resModalName">—</span></div>
      <div><strong>Instansi:</strong> <span id="resModalCompany">—</span></div>
      <div><strong>Kode Tiket:</strong> <span id="resModalCode" style="font-family: monospace; font-weight: 700;">—</span></div>
      <div id="resModalTimeRow"><strong>Waktu Check-In:</strong> <span id="resModalTime">—</span></div>
    </div>

    <!-- Modal Footer & Countdown Button -->
    <div style="padding: 18px 20px; background: #ffffff;">
      <button type="button" id="btnCloseScanModal" class="reg-btn-submit" style="width: 100%; padding: 12px; font-size: 14px; margin: 0;">
        Tutup & Lanjut Scan (<span id="modalCountdown">3</span>s)
      </button>
    </div>
  </div>
</div>

<script>
// Logic Passcode Verification
<?php if (!$isUnlocked): ?>
document.getElementById('passcodeForm').addEventListener('submit', async function(e) {
  e.preventDefault();
  const input = document.getElementById('inputPasscode');
  const btn = document.getElementById('btnUnlock');
  const errBox = document.getElementById('pinErrorMsg');

  btn.disabled = true;
  btn.textContent = 'Memverifikasi...';
  errBox.style.display = 'none';

  try {
    const res = await fetch("<?= base_url('gate/portal_desk_ops_access_verify/verify') ?>", {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
        'X-Requested-With': 'XMLHttpRequest'
      },
      body: new URLSearchParams({ passcode: input.value.trim() })
    });

    const data = await res.json();
    if (res.ok && data.success) {
      window.location.reload();
    } else {
      errBox.textContent = data.message || 'Passcode salah.';
      errBox.style.display = 'block';
      input.value = '';
      input.focus();
    }
  } catch (err) {
    errBox.textContent = 'Gagal menghubungi server.';
    errBox.style.display = 'block';
  } finally {
    btn.disabled = false;
    btn.textContent = 'Buka Terminal';
  }
});
<?php else: ?>

// Logic Unlocked Scanner & Stats
let html5QrCode = null;
let isCameraRunning = false;
let isScanLocked = false;
let modalCountdownTimer = null;

async function fetchStats() {
  try {
    const res = await fetch("<?= base_url('gate/portal_desk_ops_access_verify/stats') ?>");
    const data = await res.json();
    if (data) {
      document.getElementById('statRegistered').textContent = data.total_registered || 0;
      document.getElementById('statCheckedIn').textContent = data.total_checked_in || 0;
      document.getElementById('statUnattended').textContent = data.unattended || 0;
    }
  } catch (e) {}
}

// Buka Modal Hasil Scan & Kunci Pemindaian Sementara
function openScanResultModal(type, data) {
  isScanLocked = true; // Kunci scanning agar tidak looping scan

  const modal = document.getElementById('scanResultModal');
  const icon = document.getElementById('modalStatusIcon');
  const title = document.getElementById('modalStatusTitle');
  const sub = document.getElementById('modalStatusSub');
  const partBox = document.getElementById('modalParticipantBox');
  const btnClose = document.getElementById('btnCloseScanModal');
  const cdSpan = document.getElementById('modalCountdown');

  // Clear timer sebelumnya bila ada
  if (modalCountdownTimer) clearInterval(modalCountdownTimer);

  if (type === 'success') {
    icon.style.background = '#dcfce7';
    icon.style.color = '#15803d';
    icon.innerHTML = '&check;';
    title.textContent = 'CHECK-IN BERHASIL';
    title.style.color = '#15803d';
    sub.textContent = 'Peserta dipersilakan masuk ke area acara.';
    
    partBox.style.display = 'block';
    document.getElementById('resModalName').textContent = data.participant?.full_name || '-';
    document.getElementById('resModalCompany').textContent = (data.participant?.company_name || 'Umum') + (data.participant?.job_title ? ' (' + data.participant.job_title + ')' : '');
    document.getElementById('resModalCode').textContent = data.participant?.ticket_code || '-';
    document.getElementById('resModalTimeRow').style.display = 'block';
    document.getElementById('resModalTime').textContent = data.checked_in_at || 'Baru saja';
  } else if (type === 'already_checked_in') {
    icon.style.background = '#fef3c7';
    icon.style.color = '#b45309';
    icon.innerHTML = '&#9888;';
    title.textContent = 'TIKET SUDAH CHECK-IN';
    title.style.color = '#b45309';
    sub.textContent = 'Peringatan: Tiket ini sudah pernah digunakan sebelumnya!';

    partBox.style.display = 'block';
    document.getElementById('resModalName').textContent = data.participant?.full_name || '-';
    document.getElementById('resModalCompany').textContent = (data.participant?.company_name || 'Umum') + (data.participant?.job_title ? ' (' + data.participant.job_title + ')' : '');
    document.getElementById('resModalCode').textContent = data.participant?.ticket_code || '-';
    document.getElementById('resModalTimeRow').style.display = 'block';
  } else if (type === 'invalid_day') {
    icon.style.background = '#fef3c7';
    icon.style.color = '#b45309';
    icon.innerHTML = '&#9888;';
    title.textContent = 'HARI TIKET TIDAK SESUAI';
    title.style.color = '#b45309';
    sub.textContent = data.message || 'Hari kunjungan tiket ini tidak sesuai dengan jadwal hari ini.';

    partBox.style.display = 'block';
    document.getElementById('resModalName').textContent = data.participant?.full_name || '-';
    document.getElementById('resModalCompany').textContent = data.participant?.company_name || 'Umum';
    document.getElementById('resModalCode').textContent = data.participant?.ticket_code || '-';
    document.getElementById('resModalTimeRow').style.display = 'none';
  } else {
    icon.style.background = '#fee2e2';
    icon.style.color = '#dc2626';
    icon.innerHTML = '&times;';
    title.textContent = 'TIKET TIDAK VALID';
    title.style.color = '#dc2626';
    sub.textContent = data.message || 'Kode tiket tidak ditemukan di sistem.';
    partBox.style.display = 'none';
  }

  modal.style.display = 'flex';

  // Hitung mundur 3 detik untuk auto-resume scanning
  let remainingSeconds = 3;
  cdSpan.textContent = remainingSeconds;
  
  modalCountdownTimer = setInterval(() => {
    remainingSeconds--;
    cdSpan.textContent = remainingSeconds;
    if (remainingSeconds <= 0) {
      closeScanResultModal();
    }
  }, 1000);
}

// Tutup Modal & Buka Kunci Scanning
function closeScanResultModal() {
  if (modalCountdownTimer) {
    clearInterval(modalCountdownTimer);
    modalCountdownTimer = null;
  }
  document.getElementById('scanResultModal').style.display = 'none';
  
  // Berikan jeda 800ms setelah modal tertutup sebelum scanning dibuka kembali
  setTimeout(() => {
    isScanLocked = false;
  }, 800);
}

document.getElementById('btnCloseScanModal').addEventListener('click', closeScanResultModal);

// Core Verifikasi Tiket
async function verifyTicketCode(code) {
  if (!code) return;
  isScanLocked = true; // Lock agar scanning tidak terpanggil berulang kali saat request

  try {
    const res = await fetch("<?= base_url('gate/portal_desk_ops_access_verify/checkin') ?>", {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
        'X-Requested-With': 'XMLHttpRequest'
      },
      body: new URLSearchParams({ ticket_code: code })
    });

    const data = await res.json();

    let feedbackTimer = null;
    function showInlineFeedback(cls, title, htmlText) {
      const fb = document.getElementById('scanFeedback');
      const fTitle = document.getElementById('feedbackTitle');
      const fDesc = document.getElementById('feedbackDesc');
      if (!fb) return;

      fb.className = 'scan-feedback ' + cls;
      fTitle.textContent = title;
      fDesc.innerHTML = htmlText;
      fb.style.display = 'block';

      if (feedbackTimer) clearTimeout(feedbackTimer);
      // Banner inline otomatis memudar dan hilang setelah 4 detik
      feedbackTimer = setTimeout(() => {
        fb.style.display = 'none';
      }, 4000);
    }

    if (res.status === 200) {
      showInlineFeedback('success', 'BERHASIL CHECK-IN', `<strong>${data.participant?.full_name}</strong> &bull; Waktu: ${data.checked_in_at}`);
      openScanResultModal('success', data);
      fetchStats();
      loadRecentParticipants();
    } else if (res.status === 409) {
      showInlineFeedback('warning', 'TIKET SUDAH CHECK-IN', `Tiket milik <strong>${data.participant?.full_name || '-'}</strong> sudah digunakan.`);
      openScanResultModal('already_checked_in', data);
      loadRecentParticipants();
    } else if (res.status === 422 && data.status === 'invalid_day') {
      showInlineFeedback('warning', 'JADWAL TIKET BERBEDA', data.message);
      openScanResultModal('invalid_day', data);
    } else {
      showInlineFeedback('warning', 'TIKET TIDAK VALID', data.message || 'Kode tiket tidak ditemukan di sistem.');
      openScanResultModal('invalid', data);
    }
  } catch (err) {
    showInlineFeedback('warning', 'ERROR KONEKSI', 'Gagal memverifikasi ke server. Periksa jaringan.');
    openScanResultModal('invalid', { message: 'Gagal menghubungi server verifikasi. Periksa jaringan.' });
  }
}

document.getElementById('manualCheckinForm').addEventListener('submit', function(e) {
  e.preventDefault();
  const input = document.getElementById('manualTicketCode');
  const val = input.value.trim();
  if (val) {
    verifyTicketCode(val);
    input.value = '';
  }
});

// Camera Toggle with Mobile-Friendly Auto-Scroll
document.getElementById('toggleCameraBtn').addEventListener('click', function() {
  const btn = this;
  const scannerCard = document.getElementById('scannerSection');

  if (!isCameraRunning) {
    html5QrCode = new Html5Qrcode("reader");

    const qrboxFunction = function(viewfinderWidth, viewfinderHeight) {
      const minEdgePercentage = 0.75;
      const minEdgeSize = Math.min(viewfinderWidth, viewfinderHeight);
      const qrboxSize = Math.floor(minEdgeSize * minEdgePercentage);
      return {
        width: Math.max(250, Math.min(qrboxSize, 360)),
        height: Math.max(250, Math.min(qrboxSize, 360))
      };
    };

    html5QrCode.start(
      { facingMode: "environment" },
      { fps: 15, qrbox: qrboxFunction },
      (decodedText) => {
        if (!isScanLocked) {
          isScanLocked = true;
          verifyTicketCode(decodedText.trim());
        }
      },
      () => {}
    ).then(() => {
      isCameraRunning = true;
      btn.classList.add('active-cam');
      btn.innerHTML = `<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="9" y1="9" x2="15" y2="15"></line><line x1="15" y1="9" x2="9" y2="15"></line></svg> Matikan Kamera`;

      setTimeout(() => {
        scannerCard.scrollIntoView({ behavior: 'smooth', block: 'start' });
      }, 150);
    }).catch(err => {
      alert("Tidak dapat mengakses kamera: " + err);
    });
  } else {
    html5QrCode.stop().then(() => {
      isCameraRunning = false;
      btn.classList.remove('active-cam');
      btn.innerHTML = `<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path><circle cx="12" cy="13" r="4"></circle></svg> Nyalakan Kamera`;
    }).catch(err => {
      isCameraRunning = false;
      btn.classList.remove('active-cam');
      btn.innerHTML = `<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path><circle cx="12" cy="13" r="4"></circle></svg> Nyalakan Kamera`;
    });
  }
});

// Render List Peserta Sesuai Gambar 1
function renderParticipantList(list, box) {
  if (!list || list.length === 0) {
    box.innerHTML = '<div style="padding:20px; text-align:center; color:var(--green-8, #346c21); font-size:13px;">Tidak ada data peserta ditemukan.</div>';
    return;
  }

  box.innerHTML = list.map(item => {
    let dayTag = '';
    if (item.visit_day === 'day_1') {
      dayTag = '<span style="background:#e0f2fe; color:#0369a1; font-size:11px; font-weight:700; padding:2px 7px; border-radius:5px; margin-left:6px;">Hari 1 (10 Nov)</span>';
    } else if (item.visit_day === 'day_2') {
      dayTag = '<span style="background:#fef3c7; color:#b45309; font-size:11px; font-weight:700; padding:2px 7px; border-radius:5px; margin-left:6px;">Hari 2 (11 Nov)</span>';
    } else {
      dayTag = '<span style="background:#f0fdf4; color:#15803d; font-size:11px; font-weight:700; padding:2px 7px; border-radius:5px; margin-left:6px;">Semua Hari</span>';
    }

    let statusBadge = '<span class="badge-belum-masuk">Belum Hadir</span>';
    const cCount = parseInt(item.checkin_count || 0, 10);
    if (item.visit_day === 'all_days') {
      if (cCount >= 2) {
        statusBadge = '<span class="badge-hadir">&check; Hadir ' + cCount + ' Hari</span>';
      } else if (cCount === 1) {
        statusBadge = '<span class="badge-hadir" style="background:#e0f2fe; color:#0369a1; border-color:#7dd3fc;">&check; Hadir 1 Hari</span>';
      }
    } else if (item.is_checked_in == 1 || cCount >= 1) {
      statusBadge = '<span class="badge-hadir">&check; Hadir</span>';
    }

    return `
    <div class="participant-card">
      <div style="flex: 1; padding-right: 12px;">
        <div class="participant-name">${item.full_name}</div>
        <div class="participant-meta">${item.company_name || 'Umum'} &bull; ${item.phone || item.email || '-'}</div>
        <div class="participant-code">Kode: ${item.ticket_code} ${dayTag}</div>
      </div>
      <div>
        ${statusBadge}
      </div>
    </div>
  `;
  }).join('');
}

// Load List Peserta Awal (Auto Load Otomatis)
async function loadRecentParticipants() {
  const box = document.getElementById('searchResults');
  try {
    const res = await fetch("<?= base_url('gate/portal_desk_ops_access_verify/search') ?>?q=all");
    const json = await res.json();
    renderParticipantList(json.data || [], box);
  } catch (e) {
    box.innerHTML = '<div style="padding:20px; text-align:center; color:#dc2626; font-size:13px;">Gagal memuat daftar peserta.</div>';
  }
}

// Live Search Fallback
let searchTimeout = null;
document.getElementById('searchInput').addEventListener('input', function() {
  clearTimeout(searchTimeout);
  const q = this.value.trim();
  const box = document.getElementById('searchResults');

  if (q.length === 0) {
    loadRecentParticipants();
    return;
  }

  searchTimeout = setTimeout(async () => {
    try {
      const res = await fetch("<?= base_url('gate/portal_desk_ops_access_verify/search') ?>?q=" + encodeURIComponent(q));
      const json = await res.json();
      renderParticipantList(json.data || [], box);
    } catch (e) {}
  }, 250);
});

// Auto-load saat halaman dibuka
fetchStats();
loadRecentParticipants();
setInterval(fetchStats, 15000); // Polling telemetry tiap 15 detik
<?php endif; ?>
</script>

</body>
</html>
