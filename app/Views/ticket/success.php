<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>E-Badge & Konfirmasi Registrasi — <?= esc($site['title'] ?? 'Energi & Mineral Festival 2026') ?></title>
  
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

    .ticket-wrapper {
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: flex-start;
      padding: 40px 20px 80px 20px;
      box-sizing: border-box;
      background-color: var(--yellow-6, #f4d849);
    }

    .ticket-container {
      width: 100%;
      max-width: 520px;
      margin: 0 auto;
      text-align: center;
    }

    .ticket-logo {
      max-height: 48px;
      margin-bottom: 24px;
    }

    .ticket-card {
      background: #ffffff;
      color: #0f172a;
      border-radius: 16px;
      overflow: hidden;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
      text-align: left;
    }

    .ticket-header {
      background: #0b1d12;
      color: #ffffff;
      padding: 24px 24px 20px 24px;
      border-bottom: 3px solid #49982e;
    }

    .ticket-badge-type {
      font-size: 11px;
      font-weight: 800;
      text-transform: uppercase;
      letter-spacing: 0.08em;
      background: rgba(73, 152, 46, 0.2);
      color: #8cbe7b;
      border: 1px solid rgba(73, 152, 46, 0.4);
      display: inline-block;
      padding: 4px 10px;
      border-radius: 6px;
      margin-bottom: 12px;
    }

    .ticket-event-title {
      font-size: 18px;
      font-weight: 800;
      margin: 0 0 6px 0;
      letter-spacing: -0.01em;
    }

    .ticket-event-meta {
      font-size: 12px;
      color: rgba(255, 255, 255, 0.75);
      line-height: 1.4;
    }

    .ticket-body {
      padding: 24px;
    }

    .ticket-name {
      font-size: 20px;
      font-weight: 800;
      color: #0f172a;
      margin: 0 0 4px 0;
    }

    .ticket-sub {
      font-size: 14px;
      color: #64748b;
      margin: 0 0 20px 0;
      font-weight: 600;
    }

    .ticket-qr-box {
      background: #f8fafc;
      border: 1px dashed #cbd5e1;
      border-radius: 12px;
      padding: 20px;
      text-align: center;
      margin-bottom: 20px;
    }

    #qrcode img, #qrcode canvas {
      margin: 0 auto;
      display: block;
    }

    .ticket-code-str {
      font-family: monospace;
      font-size: 18px;
      font-weight: 800;
      letter-spacing: 0.1em;
      color: #0f172a;
      margin-top: 12px;
    }

    .ticket-instruction {
      font-size: 12px;
      color: #64748b;
      line-height: 1.5;
      text-align: center;
      margin: 0;
    }

    .ticket-actions {
      display: flex;
      gap: 12px;
      margin-top: 24px;
    }

    .btn-action {
      flex: 1;
      padding: 12px 16px;
      border-radius: 8px;
      font-size: 14px;
      font-weight: 700;
      font-family: inherit;
      cursor: pointer;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      box-sizing: border-box;
    }

    .btn-print {
      background: #49982e;
      color: #ffffff;
      border: none;
      transition: background 0.2s ease;
    }

    .btn-print:hover {
      background: #3e8127;
    }

    .btn-home {
      background: #ffffff;
      color: var(--green-9, #2a571a);
      border: 1.5px solid var(--green-6, #49982e);
    }
    
    .btn-home:hover {
      background: var(--green-1, #edf5ea);
    }

    @media print {
      body {
        background: #ffffff !important;
        color: #000000 !important;
      }
      .ticket-wrapper {
        padding: 0 !important;
        background: none !important;
      }
      .ticket-actions, .ticket-logo {
        display: none !important;
      }
      .ticket-card {
        box-shadow: none !important;
        border: 1px solid #000000 !important;
      }
    }
  </style>

  <!-- QRCode.js lightweight library -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
</head>
<body>

<div class="ticket-wrapper">
  <div class="ticket-container">
    
    <a href="<?= base_url('/') ?>">
      <img src="<?= base_url('assets/img/logo_green_emf26.webp') ?>" alt="<?= esc($site['title'] ?? 'Energi & Mineral Festival 2026') ?>" class="ticket-logo">
    </a>

    <div class="ticket-card" id="printableTicket">
      <div class="ticket-header">
        <span class="ticket-badge-type">Visitor Pass</span>
        <h2 class="ticket-event-title"><?= esc($participant['event_title'] ?? $site['title'] ?? 'Energi & Mineral Festival 2026') ?></h2>
        <div class="ticket-event-meta">
          <?= esc($site['dates'] ?? '10 - 11 November 2026') ?> &bull; <?= esc($site['venue'] ?? 'Jakarta') ?>
        </div>
      </div>

      <div class="ticket-body">
        <div class="ticket-name"><?= esc($participant['full_name'] ?? 'Peserta Terdaftar') ?></div>
        <div class="ticket-sub">
          <?= esc($participant['company_name'] ?: 'Umum') ?>
          <?= !empty($participant['job_title']) ? ' &bull; ' . esc($participant['job_title']) : '' ?>
        </div>

        <div class="ticket-qr-box">
          <div id="qrcode"></div>
          <div class="ticket-code-str"><?= esc($ticketCode) ?></div>
        </div>

        <p class="ticket-instruction">
          Tunjukkan QR Code ini pada layar ponsel Anda ke meja resepsionis saat tiba di venue untuk mendapatkan akses masuk.
        </p>
      </div>
    </div>

    <div class="ticket-actions">
      <button onclick="window.print()" class="btn-action btn-print">
        <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
        <span>Cetak / Simpan Tiket</span>
      </button>
      <a href="<?= base_url('/') ?>" class="btn-action btn-home">
        <span>Ke Beranda</span>
      </a>
    </div>

  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const code = "<?= esc($ticketCode) ?>";
  const qrContainer = document.getElementById('qrcode');
  if (qrContainer && window.QRCode) {
    new QRCode(qrContainer, {
      text: code,
      width: 160,
      height: 160,
      colorDark: "#0f172a",
      colorLight: "#ffffff",
      correctLevel: QRCode.CorrectLevel.M
    });
  }
});
</script>

</body>
</html>
