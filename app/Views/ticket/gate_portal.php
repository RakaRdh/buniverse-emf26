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
      padding: 24px 20px 60px 20px;
      box-sizing: border-box;
      background-color: var(--yellow-6, #f4d849);
    }

    /* Container fluid 1280px */
    .terminal-container {
      width: 100%;
      max-width: 1280px;
      margin: 0 auto;
    }

    /* TOP NAVIGATION BAR */
    .gate-nav-bar {
      background: #ffffff;
      border: 1px solid rgba(73, 152, 46, 0.25);
      box-shadow: 0 4px 16px rgba(33, 68, 21, 0.06);
      border-radius: 14px;
      padding: 12px 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
      flex-wrap: wrap;
      gap: 12px;
    }

    .gate-nav-brand {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .gate-nav-tabs {
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .gate-tab-link {
      padding: 8px 16px;
      font-size: 13px;
      font-weight: 700;
      text-decoration: none;
      border-radius: 8px;
      transition: all 0.2s ease;
      display: inline-flex;
      align-items: center;
      gap: 6px;
    }

    .gate-tab-link.active {
      background: var(--green-6, #49982e);
      color: #ffffff !important;
      box-shadow: 0 2px 8px rgba(73, 152, 46, 0.25);
    }

    .gate-tab-link:not(.active) {
      background: #f1f5f9;
      color: var(--green-10, #214415);
      border: 1px solid #e2e8f0;
    }

    .gate-tab-link:not(.active):hover {
      background: #e2e8f0;
    }

    /* FULL-WIDTH VERTICAL STACK (GAMBAR 1 & 2 SAMA-SAMA FULL WIDTH) */
    .terminal-fullwidth-stack {
      display: flex;
      flex-direction: column;
      gap: 20px;
      width: 100%;
    }

    /* SECTION 1: TELEMETRI & SCANNER (FULL WIDTH) */
    .scanner-fullwidth-card {
      background: #ffffff;
      border: 1px solid rgba(73, 152, 46, 0.25);
      box-shadow: 0 8px 24px rgba(33, 68, 21, 0.08);
      border-radius: 16px;
      padding: 24px;
      box-sizing: border-box;
      width: 100%;
    }

    .telemetry-badges {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 12px;
      margin-bottom: 20px;
      align-items: stretch;
      width: 100%;
      box-sizing: border-box;
    }

    .stat-badge {
      background: #ffffff;
      border: 1px solid rgba(73, 152, 46, 0.25);
      box-shadow: 0 4px 12px rgba(33, 68, 21, 0.05);
      border-radius: 12px;
      padding: 14px 10px;
      text-align: center;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      height: 100%;
      min-height: 86px;
      box-sizing: border-box;
    }

    .stat-badge .lbl {
      font-size: 11px;
      text-transform: uppercase;
      font-weight: 800;
      letter-spacing: 0.04em;
      color: var(--green-8, #346c21);
      margin-bottom: 6px;
      min-height: 28px;
      display: flex;
      align-items: center;
      justify-content: center;
      text-align: center;
      line-height: 1.2;
      width: 100%;
    }

    .stat-badge .num {
      font-size: 28px;
      font-weight: 800;
      color: var(--green-10, #214415);
      line-height: 1;
      margin: 0;
    }

    @media (max-width: 768px) {
      .gate-nav-bar {
        padding: 14px;
        gap: 12px;
      }
      .gate-nav-brand {
        width: 100%;
        text-align: left;
      }
      .gate-nav-tabs {
        width: 100%;
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 6px;
      }
      .gate-tab-link {
        width: 100%;
        height: 100%;
        min-height: 56px;
        padding: 8px 4px;
        font-size: 11px;
        font-weight: 700;
        text-align: center;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        border-radius: 10px;
        line-height: 1.25;
        gap: 4px;
        box-sizing: border-box;
      }
      .gate-tab-link svg {
        width: 15px;
        height: 15px;
        flex-shrink: 0;
      }
      .gate-tab-link span {
        display: block;
        text-align: center;
      }
      .scanner-fullwidth-card {
        padding: 16px;
      }
    }

    @media (max-width: 480px) {
      .telemetry-badges {
        gap: 8px;
      }
      .stat-badge {
        padding: 10px 4px;
        min-height: 78px;
        border-radius: 10px;
      }
      .stat-badge .lbl {
        font-size: 10px;
        letter-spacing: 0.02em;
        min-height: 26px;
        margin-bottom: 4px;
      }
      .stat-badge .num {
        font-size: 24px;
      }
    }

    .scanner-controls-area {
      display: grid;
      grid-template-columns: 1fr auto;
      grid-template-areas:
        "title toggle"
        "camera camera"
        "form form";
      align-items: center;
      border-top: 1px solid #f1f5f9;
      padding-top: 16px;
    }

    .scanner-title {
      grid-area: title;
      font-size: 13.5px;
      font-weight: 800;
      text-transform: uppercase;
      letter-spacing: 0.05em;
      color: var(--green-9, #2a571a);
      margin-bottom: 12px;
    }

    .camera-toggle-btn {
      grid-area: toggle;
      justify-self: end;
      margin-bottom: 12px;
    }

    #reader {
      grid-area: camera;
      width: 100%;
      max-width: 500px;
      margin: 0 auto;
      border-radius: 12px;
      overflow: hidden;
      background: #000000;
      position: relative;
    }

    #reader:not(:empty) {
      margin-bottom: 16px;
    }

    #reader span[style*="position: absolute"],
    #reader div[style*="position: absolute"] {
      display: none !important;
    }

    .btn-manual-in {
      background: #15803d;
      color: #ffffff !important;
      border: 1px solid #166534;
      border-radius: 9999px;
      padding: 8px 16px;
      font-size: 12.5px;
      font-weight: 800;
      cursor: pointer;
      font-family: inherit;
      display: inline-flex;
      align-items: center;
      gap: 6px;
      box-shadow: 0 2px 8px rgba(21, 128, 61, 0.3);
      transition: all 0.2s ease;
    }

    .btn-manual-in:hover {
      background: #166534;
      transform: translateY(-1px);
    }

    .btn-manual-in.active-cam {
      background: #dc2626 !important;
      border-color: #b91c1c !important;
    }

    .scanner-manual-form {
      grid-area: form;
      width: 100%;
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
      font-size: 14px;
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
      padding: 12px 24px;
      font-size: 14px;
      font-weight: 700;
      cursor: pointer;
      font-family: inherit;
      transition: background 0.2s ease;
    }

    .scan-submit-btn:hover {
      background: var(--green-7, #3e8127);
    }

    @media (max-width: 768px) {
      .scanner-controls-area {
        display: flex;
        flex-direction: column;
        align-items: stretch;
      }

      .scanner-title {
        order: 1;
        margin-bottom: 12px;
      }

      #reader {
        order: 2;
        width: 100%;
        max-width: 100%;
        margin: 0 auto;
      }

      #reader:not(:empty) {
        margin-bottom: 12px;
      }

      .camera-toggle-btn {
        order: 3;
        width: 100% !important;
        justify-content: center;
        padding: 12px 18px;
        font-size: 14px;
        box-sizing: border-box;
        border-radius: 10px;
        margin-bottom: 12px;
      }

      .scanner-manual-form {
        order: 4;
        width: 100%;
        margin: 0;
      }

      .scan-input-group {
        display: flex;
        flex-direction: column;
        gap: 10px;
        margin-top: 0;
        width: 100%;
      }

      .scan-text-input {
        width: 100% !important;
        box-sizing: border-box;
        padding: 12px 14px;
        font-size: 14px;
        border-radius: 10px;
      }

      .scan-submit-btn {
        width: 100% !important;
        box-sizing: border-box;
        padding: 12px 20px;
        font-size: 14px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
      }
    }

    /* GAMBAR 4: FEEDBACK BANNER (AUTO HIDE & SMOOTH FADE OUT) */
    .scan-feedback {
      display: none;
      padding: 14px 18px;
      border-radius: 12px;
      margin-top: 16px;
      text-align: left;
      opacity: 1;
      transition: opacity 0.4s ease;
    }

    .scan-feedback.fade-out {
      opacity: 0;
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

    /* SECTION 2: TABLE CARD (FULL WIDTH) */
    .desk-table-card {
      background: #ffffff;
      border: 1px solid rgba(73, 152, 46, 0.25);
      box-shadow: 0 8px 24px rgba(33, 68, 21, 0.08);
      border-radius: 16px;
      padding: 24px;
      box-sizing: border-box;
      width: 100%;
    }

    .desk-toolbar {
      display: flex;
      flex-direction: column;
      gap: 14px;
      margin-bottom: 18px;
    }

    .filter-tabs-row {
      display: flex;
      align-items: center;
      gap: 8px;
      flex-wrap: wrap;
    }

    .filter-pill-btn {
      border: 1px solid #cbd5e1;
      background: #ffffff;
      color: #475569;
      padding: 7px 14px;
      border-radius: 8px;
      font-size: 12.5px;
      font-weight: 700;
      cursor: pointer;
      transition: all 0.15s ease;
    }

    .filter-pill-btn:hover {
      background: #f8fafc;
      color: var(--green-10, #214415);
    }

    .filter-pill-btn.active {
      background: var(--green-6, #49982e);
      color: #ffffff;
      border-color: var(--green-7, #3e8127);
    }

    .search-filter-grid {
      display: grid;
      grid-template-columns: 1fr 220px;
      gap: 12px;
      align-items: center;
    }

    @media (max-width: 768px) {
      .search-filter-grid {
        grid-template-columns: 1fr;
      }
    }

    /* DESKTOP TABLE STYLING */
    .gate-table-wrapper {
      width: 100%;
      overflow-x: auto;
      border: 1px solid #e2e8f0;
      border-radius: 12px;
      background: #ffffff;
    }

    .gate-table {
      width: 100%;
      border-collapse: collapse;
      text-align: left;
      font-size: 13.5px;
    }

    .gate-table th {
      background: #f8fafc;
      color: #475569;
      font-weight: 800;
      padding: 13px 16px;
      border-bottom: 1.5px solid #e2e8f0;
      user-select: none;
      white-space: nowrap;
    }

    .gate-table th.sortable {
      cursor: pointer;
      transition: color 0.15s ease;
    }

    .gate-table th.sortable:hover {
      color: var(--green-7, #3e8127);
    }

    .gate-table td {
      padding: 13px 16px;
      border-bottom: 1px solid #f1f5f9;
      vertical-align: middle;
      color: #1e293b;
    }

    .gate-table tbody tr:hover {
      background: #fcfdfd;
    }

    /* BADGES */
    .badge-hadir {
      display: inline-flex;
      align-items: center;
      gap: 4px;
      background: #dcfce7;
      color: #15803d;
      border: 1.5px solid #86efac;
      border-radius: 8px;
      padding: 5px 12px;
      font-size: 12.5px;
      font-weight: 800;
      user-select: none;
      white-space: nowrap;
    }

    .badge-belum-masuk {
      display: inline-flex;
      align-items: center;
      background: #f1f5f9;
      color: #64748b;
      border: 1.5px solid #cbd5e1;
      border-radius: 8px;
      padding: 5px 12px;
      font-size: 12.5px;
      font-weight: 800;
      user-select: none;
      white-space: nowrap;
    }

    /* BUTTON ACTION CHECK-IN (GAMBAR 3) */
    .btn-action-checkin {
      background: #15803d;
      color: #ffffff;
      border: none;
      border-radius: 8px;
      padding: 7px 16px;
      font-size: 12px;
      font-weight: 800;
      cursor: pointer;
      transition: all 0.15s ease;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 6px;
      box-shadow: 0 2px 6px rgba(21, 128, 61, 0.25);
    }

    .btn-action-checkin:hover {
      background: #166534;
      transform: translateY(-1px);
      box-shadow: 0 4px 10px rgba(21, 128, 61, 0.35);
    }

    /* MOBILE CARD VIEW (< 768px) */
    .gate-mobile-cards {
      display: none;
      flex-direction: column;
      gap: 12px;
    }

    @media (max-width: 768px) {
      .gate-table-wrapper {
        display: none;
      }
      .gate-mobile-cards {
        display: flex;
      }
    }

    .mobile-p-card {
      background: #ffffff;
      border: 1px solid #e2e8f0;
      border-radius: 12px;
      padding: 16px;
      box-sizing: border-box;
      box-shadow: 0 2px 6px rgba(0,0,0,0.03);
    }

    .btn-mobile-checkin {
      width: 100% !important;
      display: flex !important;
      align-items: center;
      justify-content: center;
      padding: 10px 14px !important;
      font-size: 13px !important;
      font-weight: 800 !important;
      border-radius: 8px !important;
      box-sizing: border-box !important;
      text-align: center;
      margin: 0 !important;
    }

    .mobile-checked-in-tag {
      width: 100%;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 6px;
      background: #f0fdf4;
      border: 1px solid #bbf7d0;
      color: #15803d;
      font-size: 12px;
      font-weight: 700;
      padding: 7px 12px;
      border-radius: 8px;
      box-sizing: border-box;
      text-align: center;
    }

    /* VLC PAGINATION BAR */
    .vlc-pagination-bar {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-top: 18px;
      padding-top: 16px;
      border-top: 1px solid #e2e8f0;
      flex-wrap: wrap;
      gap: 12px;
      font-size: 13px;
      color: #64748b;
    }

    .vlc-page-controls {
      display: flex;
      align-items: center;
      gap: 6px;
    }

    .vlc-btn-page {
      border: 1px solid #cbd5e1;
      background: #ffffff;
      color: #334155;
      padding: 6px 12px;
      border-radius: 6px;
      font-size: 12.5px;
      font-weight: 700;
      cursor: pointer;
      transition: all 0.15s ease;
    }

    .vlc-btn-page:hover:not(:disabled) {
      background: #f1f5f9;
      color: #0f172a;
    }

    .vlc-btn-page:disabled {
      opacity: 0.4;
      cursor: not-allowed;
    }

    .vlc-page-current {
      background: var(--green-6, #49982e);
      color: #ffffff;
      padding: 6px 14px;
      border-radius: 6px;
      font-weight: 800;
      font-size: 12.5px;
    }

    /* MODAL UMUM */
    .gate-modal-backdrop {
      display: none;
      position: fixed;
      inset: 0;
      background: rgba(0, 0, 0, 0.65);
      z-index: 99999;
      justify-content: center;
      align-items: center;
      padding: 20px;
      box-sizing: border-box;
    }

    .gate-modal-box {
      background: #ffffff;
      width: 100%;
      max-width: 460px;
      border-radius: 18px;
      overflow: hidden;
      box-shadow: 0 24px 48px rgba(0,0,0,0.35);
      text-align: center;
    }
  </style>

  <!-- HTML5 QR Code library -->
  <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
</head>
<body>

<div class="terminal-wrapper">
  <div class="terminal-container">

    <!-- TOP NAVIGATION BAR (TANPA TOMBOL KUNCI TERMINAL & TANPA EMOJI) -->
    <div class="gate-nav-bar">
      <div class="gate-nav-brand">
        <div>
          <div style="font-size: 16px; font-weight: 800; color: var(--green-10, #214415);"><?= esc($site['title'] ?? 'Energi & Mineral Festival 2026') ?></div>
        </div>
      </div>

      <div class="gate-nav-tabs">
        <a href="<?= base_url('gate/portal_desk_ops_access_verify') ?>" class="gate-tab-link <?= (($activeGateTab ?? 'scanner') === 'scanner') ? 'active' : '' ?>">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M3 7V5a2 2 0 0 1 2-2h2M17 3h2a2 2 0 0 1 2 2v2M21 17v2a2 2 0 0 1-2 2h-2M7 21H5a2 2 0 0 1-2-2v-2"></path><rect x="7" y="7" width="10" height="10" rx="1"></rect></svg>
          <span>Scanner &amp;&nbsp;Presensi</span>
        </a>
        <a href="<?= base_url('gate/portal_desk_ops_access_verify/invitations') ?>" class="gate-tab-link <?= (($activeGateTab ?? 'scanner') === 'invitations') ? 'active' : '' ?>">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
          <span>Presensi Undangan</span>
        </a>
        <a href="<?= base_url('gate/portal_desk_ops_access_verify/registration') ?>" class="gate-tab-link <?= (($activeGateTab ?? 'scanner') === 'registration') ? 'active' : '' ?>">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><line x1="20" y1="8" x2="20" y2="14"></line><line x1="23" y1="11" x2="17" y2="11"></line></svg>
          <span>Registrasi OTS</span>
        </a>
      </div>
    </div>

    <!-- FULL WIDTH VERTICAL STACK (GAMBAR 1 & GAMBAR 2 SAMA-SAMA FULL WIDTH) -->
    <div class="terminal-fullwidth-stack">

      <!-- ELEMEN TELEMETRI CARD (FULL WIDTH) -->
      <div class="scanner-fullwidth-card" id="scannerSection">
        
        <?php if (($activeGateTab ?? 'scanner') === 'invitations'): ?>
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; padding-bottom: 12px; border-bottom: 1.5px solid #f1f5f9; flex-wrap: wrap; gap: 8px;">
          <div>
            <div style="font-size: 11px; text-transform: uppercase; font-weight: 800; color: var(--green-7, #3e8127); letter-spacing: 0.08em;">Buku Kehadiran Tamu</div>
            <h2 style="margin: 0; font-size: 18px; font-weight: 800; color: var(--green-10, #214415);">Presensi Undangan</h2>
          </div>
          <div style="background: #e8f5e9; color: var(--green-8, #346c21); border: 1px solid #a5d6a7; padding: 4px 12px; border-radius: 999px; font-size: 11.5px; font-weight: 700;">
            Data Realtime Terhubung
          </div>
        </div>
        <?php endif; ?>

        <!-- Telemetry row -->
        <div class="telemetry-badges" <?= (($activeGateTab ?? 'scanner') === 'invitations') ? 'style="margin-bottom: 0;"' : '' ?>>
          <div class="stat-badge">
            <div class="lbl">Terdaftar</div>
            <div class="num" id="statRegistered"><?= (int)($initialStats['total_registered'] ?? 0) ?></div>
          </div>
          <div class="stat-badge" style="border-color: rgba(34, 197, 94, 0.4);">
            <div class="lbl" style="color: #15803d;">Hadir</div>
            <div class="num" id="statCheckedIn" style="color: #15803d;"><?= (int)($initialStats['total_checked_in'] ?? 0) ?></div>
          </div>
          <div class="stat-badge">
            <div class="lbl">Belum Hadir</div>
            <div class="num" id="statUnattended"><?= (int)($initialStats['unattended'] ?? 0) ?></div>
          </div>
        </div>

        <?php if (($activeGateTab ?? 'scanner') !== 'invitations'): ?>
        <!-- Scanner Controls Area (Desktop: Title + Toggle button row, Camera, Input row; Mobile: Camera -> Button Kamera full -> Field Kode Tiket full -> Button Verif full) -->
        <div class="scanner-controls-area">
          <div class="scanner-title">Pemindai QR Tiket</div>

          <button id="toggleCameraBtn" class="btn-manual-in camera-toggle-btn">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path><circle cx="12" cy="13" r="4"></circle></svg>
            <span>Nyalakan Kamera</span>
          </button>

          <div id="reader"></div>

          <!-- Manual Barcode / Ticket Code Input -->
          <form id="manualCheckinForm" class="scanner-manual-form">
            <div class="scan-input-group">
              <input type="text" id="manualTicketCode" class="scan-text-input" placeholder="Scan barcode USB / ketik 16-digit kode tiket..." autocomplete="off">
              <button type="submit" class="scan-submit-btn">Verifikasi</button>
            </div>
          </form>
        </div>

        <!-- Feedback Alert Container (GAMBAR 4: Auto-hide setelah 4 detik) -->
        <div id="scanFeedback" class="scan-feedback">
          <div id="feedbackTitle" style="font-weight: 800; font-size: 15px; margin-bottom: 2px;"></div>
          <div id="feedbackDesc" style="font-size: 13px; line-height: 1.4;"></div>
        </div>
        <?php endif; ?>

      </div>

      <!-- ELEMEN GAMBAR 2: DAFTAR PESERTA & PRESENSI (FULL WIDTH) -->
      <div class="desk-table-card">

        <!-- Toolbar & Filter Multi-Kriteria (Tanpa Dropdown Berapa Banyak Data) -->
        <div class="desk-toolbar">
          <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 8px;">
            <div style="font-size: 14.5px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.04em; color: var(--green-9, #2a571a);">
              <?= (($activeGateTab ?? 'scanner') === 'invitations') ? 'DAFTAR PRESENSI UNDANGAN' : 'DAFTAR PESERTA & PRESENSI' ?>
            </div>
            <button type="button" onclick="loadParticipantsData(1)" style="font-size: 12.5px; color: var(--green-8, #346c21); cursor: pointer; text-decoration: underline; background: none; border: none; font-weight: 700;">
              Muat Ulang Data
            </button>
          </div>

          <!-- Filter Tabs Status Hadir -->
          <div class="filter-tabs-row" id="statusFilterTabs">
            <button type="button" class="filter-pill-btn active" data-status="">Semua Status</button>
            <button type="button" class="filter-pill-btn" data-status="unattended">Belum Hadir</button>
            <button type="button" class="filter-pill-btn" data-status="checked_in">Sudah Hadir</button>
          </div>

          <!-- Search Input & Day Filter (Dropdown per-page DIHAPUS) -->
          <div class="search-filter-grid">
            <input type="text" id="searchInput" class="scan-text-input" style="font-family: inherit; font-size: 13.5px;" placeholder="Cari nama, email, telp, instansi, atau kode tiket...">
            <select id="filterVisitDay" class="scan-text-input" style="font-family: inherit; font-size: 13px; padding: 11px 10px;">
              <option value="">Semua Hari Kunjungan</option>
              <?php if (!empty($scheduleDaysList)): ?>
                <?php foreach ($scheduleDaysList as $sd): ?>
                  <option value="<?= esc($sd['key']) ?>"><?= esc($sd['badge_label']) ?></option>
                <?php endforeach; ?>
              <?php else: ?>
                <option value="day_1">Hari 1 (10 Nov)</option>
                <option value="day_2">Hari 2 (11 Nov)</option>
              <?php endif; ?>
              <option value="all_days"><?= esc($scheduleDaysMap['all_days']['badge_label'] ?? 'Semua Hari (Multi-Pass)') ?></option>
            </select>
          </div>
        </div>

        <!-- DESKTOP TABLE VIEW -->
        <div class="gate-table-wrapper" id="tableContainer">
          <table class="gate-table">
            <thead>
              <tr>
                <th style="width: 40px;">#</th>
                <th class="sortable" onclick="handleSort('ticket_code')">
                  Kode Tiket <span id="sort_ticket_code" style="font-size: 11px; color: #94a3b8;">↕</span>
                </th>
                <th class="sortable" onclick="handleSort('full_name')">
                  Nama Peserta <span id="sort_full_name" style="font-size: 11px; color: #94a3b8;">↕</span>
                </th>
                <th class="sortable" onclick="handleSort('company_name')">
                  Instansi & Jabatan <span id="sort_company_name" style="font-size: 11px; color: #94a3b8;">↕</span>
                </th>
                <th class="sortable" onclick="handleSort('visit_day')">
                  Hari Tiket <span id="sort_visit_day" style="font-size: 11px; color: #94a3b8;">↕</span>
                </th>
                <th class="sortable" onclick="handleSort('is_checked_in')">
                  Status Presensi <span id="sort_is_checked_in" style="font-size: 11px; color: #94a3b8;">↕</span>
                </th>
                <th style="text-align: right;">Aksi</th>
              </tr>
            </thead>
            <tbody id="participantTableBody">
              <?php if (empty($initialParticipants)): ?>
                <tr>
                  <td colspan="7" style="text-align: center; padding: 24px; color: #64748b;">
                    Belum ada data peserta terdaftar.
                  </td>
                </tr>
              <?php else: ?>
                <?php foreach ($initialParticipants as $idx => $item): 
                  $rowNum = $idx + 1;
                  $dayInfo = $scheduleDaysMap[$item['visit_day'] ?? ''] ?? null;
                  if ($dayInfo && ($item['visit_day'] ?? '') !== 'all_days') {
                    $bg = (($item['visit_day'] ?? '') === 'day_1') ? '#e0f2fe' : ((($item['visit_day'] ?? '') === 'day_2') ? '#fef3c7' : '#ede9fe');
                    $fg = (($item['visit_day'] ?? '') === 'day_1') ? '#0369a1' : ((($item['visit_day'] ?? '') === 'day_2') ? '#b45309' : '#6d28d9');
                    $dayBadge = '<span style="background:' . $bg . '; color:' . $fg . '; font-size:11px; font-weight:700; padding:3px 7px; border-radius:5px;">' . esc($dayInfo['badge_label']) . '</span>';
                    $dayLabel = $dayInfo['full_label'];
                  } else {
                    $dayBadge = '<span style="background:#f0fdf4; color:#15803d; font-size:11px; font-weight:700; padding:3px 7px; border-radius:5px;">Semua Hari</span>';
                    $dayLabel = $scheduleDaysMap['all_days']['full_label'] ?? 'Semua Hari';
                  }
                  $isChecked = (int)($item['is_checked_in'] ?? 0) === 1 || (int)($item['checkin_count'] ?? 0) > 0;
                  $safeName = esc($item['full_name'] ?? '', 'js');
                  $safeCompany = esc(($item['company_name'] ?? 'Umum') . (!empty($item['job_title']) ? ' (' . $item['job_title'] . ')' : ''), 'js');
                  $safeCode = esc($item['ticket_code'] ?? '', 'js');
                ?>
                  <tr>
                    <td style="color:#64748b; font-size:12px;"><?= $rowNum ?></td>
                    <td><strong style="font-family:monospace; color:#1e293b; letter-spacing:0.02em;"><?= esc($item['ticket_code'] ?? '-') ?></strong></td>
                    <td>
                      <div style="font-weight:700; color:#0f172a;"><?= esc($item['full_name'] ?? '-') ?></div>
                      <div style="font-size:11.5px; color:#64748b;"><?= esc($item['phone'] ?? ($item['email'] ?? '-')) ?></div>
                    </td>
                    <td>
                      <div style="font-weight:600; color:#334155;"><?= esc($item['company_name'] ?: 'Umum') ?></div>
                      <div style="font-size:11px; color:#64748b;"><?= esc($item['job_title'] ?: '-') ?></div>
                    </td>
                    <td><?= $dayBadge ?></td>
                    <td>
                      <?php if ($isChecked): ?>
                        <span class="badge-hadir">Hadir</span>
                      <?php else: ?>
                        <span class="badge-belum-masuk">Belum Hadir</span>
                      <?php endif; ?>
                    </td>
                    <td style="text-align:right;">
                      <?php if ($isChecked): ?>
                        <span style="font-size:11.5px; color:#15803d; font-weight:700;">Sudah Masuk</span>
                      <?php else: ?>
                        <button type="button" class="btn-action-checkin" onclick="openConfirmModal('<?= $safeCode ?>', '<?= $safeName ?>', '<?= $safeCompany ?>', '<?= esc($dayLabel, 'js') ?>')">Check-In</button>
                      <?php endif; ?>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>

        <!-- MOBILE CARD VIEW (< 768px) -->
        <div class="gate-mobile-cards" id="mobileCardsContainer">
          <?php if (empty($initialParticipants)): ?>
            <div style="text-align: center; padding: 20px; color: #64748b; font-size: 13px;">
              Belum ada data peserta terdaftar.
            </div>
          <?php else: ?>
            <?php foreach ($initialParticipants as $item): 
              $dayInfo = $scheduleDaysMap[$item['visit_day'] ?? ''] ?? null;
              $dayTag = $dayInfo ? $dayInfo['badge_label'] : 'Semua Hari';
              $isChecked = (int)($item['is_checked_in'] ?? 0) === 1 || (int)($item['checkin_count'] ?? 0) > 0;
              $safeName = esc($item['full_name'] ?? '', 'js');
              $safeCompany = esc(($item['company_name'] ?? 'Umum') . (!empty($item['job_title']) ? ' (' . $item['job_title'] . ')' : ''), 'js');
              $safeCode = esc($item['ticket_code'] ?? '', 'js');
            ?>
              <div class="mobile-p-card">
                <div style="display:flex; justify-content:space-between; align-items:start; margin-bottom:6px;">
                  <div>
                    <div style="font-weight:800; font-size:14.5px; color:#0f172a;"><?= esc($item['full_name'] ?? '-') ?></div>
                    <div style="font-size:12.5px; color:#475569;"><?= esc($item['company_name'] ?: 'Umum') ?> <?= !empty($item['job_title']) ? '(' . esc($item['job_title']) . ')' : '' ?></div>
                  </div>
                  <div>
                    <?= $isChecked ? '<span class="badge-hadir">Hadir</span>' : '<span class="badge-belum-masuk">Belum Hadir</span>' ?>
                  </div>
                </div>
                <div style="margin-top:10px; padding-top:10px; border-top:1px dashed #e2e8f0;">
                  <div style="font-family:monospace; color:#334155; font-weight:700; font-size:12px; line-height:1.4; margin-bottom:8px;">
                    <?= esc($item['ticket_code'] ?? '-') ?> &bull; <?= $dayTag ?>
                  </div>
                  <div>
                    <?php if (!$isChecked): ?>
                      <button type="button" class="btn-action-checkin btn-mobile-checkin" onclick="openConfirmModal('<?= $safeCode ?>', '<?= $safeName ?>', '<?= $safeCompany ?>', '<?= esc($dayTag, 'js') ?>')">Check-In</button>
                    <?php else: ?>
                      <div class="mobile-checked-in-tag">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        <span>Sudah Masuk</span>
                      </div>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>

        <!-- VLC-STYLE PAGINATION BAR (20 DATA PER HALAMAN) -->
        <div class="vlc-pagination-bar" id="paginationBar">
          <div id="vlcPaginationSummary">
            Menampilkan <strong><?= (int)($initialPagination['showing_from'] ?? 0) ?>–<?= (int)($initialPagination['showing_to'] ?? 0) ?></strong> dari <strong><?= (int)($initialPagination['total_records'] ?? 0) ?></strong> peserta • Halaman <strong><?= (int)($initialPagination['current_page'] ?? 1) ?></strong> dari <strong><?= (int)($initialPagination['total_pages'] ?? 1) ?></strong>
          </div>
          <div class="vlc-page-controls" id="vlcPageControls">
            <span class="vlc-page-current">1</span>
            <?php if (($initialPagination['total_pages'] ?? 1) > 1): ?>
              <button type="button" class="vlc-btn-page" onclick="loadParticipantsData(2)">Next &rsaquo;</button>
              <button type="button" class="vlc-btn-page" onclick="loadParticipantsData(<?= (int)$initialPagination['total_pages'] ?>)" title="Halaman Terakhir">&raquo;</button>
            <?php endif; ?>
          </div>
        </div>

      </div>

    </div>

  </div>
</div>

<!-- MODAL KONFIRMASI ACTION CHECK-IN (POIN 4) -->
<div id="confirmCheckinModal" class="gate-modal-backdrop">
  <div class="gate-modal-box">
    <div style="background: #f8fafc; padding: 24px 20px 16px 20px; border-bottom: 1px solid #e2e8f0;">
      <div style="width: 52px; height: 52px; background: #dcfce7; color: #15803d; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto; font-size: 24px;">
        <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
      </div>
      <h3 style="margin: 14px 0 4px 0; font-size: 18px; font-weight: 800; color: #0f172a;">
        Konfirmasi Check-In Peserta
      </h3>
      <p style="margin: 0; font-size: 13px; color: #64748b;">
        Apakah Anda yakin ingin mengubah status kehadiran peserta ini menjadi <strong>Sudah Hadir</strong>?
      </p>
    </div>

    <div style="padding: 18px 20px; text-align: left; font-size: 13.5px; color: #1e293b; line-height: 1.7; background: #ffffff;">
      <div><strong>Nama:</strong> <span id="confModalName">—</span></div>
      <div><strong>Instansi:</strong> <span id="confModalCompany">—</span></div>
      <div><strong>Kode Tiket:</strong> <span id="confModalCode" style="font-family: monospace; font-weight: 800; color: #15803d;">—</span></div>
      <div><strong>Hari Tiket:</strong> <span id="confModalDay">—</span></div>
    </div>

    <div style="padding: 16px 20px; background: #f8fafc; border-top: 1px solid #e2e8f0; display: flex; gap: 10px;">
      <button type="button" id="btnCancelConfirm" class="vlc-btn-page" style="flex: 1; padding: 10px; font-size: 13px;">
        Batal
      </button>
      <button type="button" id="btnExecuteConfirm" class="btn-action-checkin" style="flex: 1; padding: 10px; font-size: 13px;">
        Ya, Check-In Sekarang
      </button>
    </div>
  </div>
</div>

<!-- MODAL POPUP HASIL SCAN -->
<div id="scanResultModal" class="gate-modal-backdrop">
  <div class="gate-modal-box">
    <div id="modalStatusHeader" style="padding: 24px 20px 16px 20px;">
      <div id="modalStatusIcon" style="width: 56px; height: 56px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto; font-size: 26px;">
        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
      </div>
      <h3 id="modalStatusTitle" style="margin: 14px 0 4px 0; font-size: 19px; font-weight: 800;">
        STATUS SCAN
      </h3>
      <p id="modalStatusSub" style="margin: 0; font-size: 13px; color: #64748b; line-height: 1.4;">
        Deskripsi status tiket
      </p>
    </div>

    <div id="modalParticipantBox" style="background: #f8fafc; border-top: 1px solid #e2e8f0; border-bottom: 1px solid #e2e8f0; padding: 16px 20px; text-align: left; font-size: 13.5px; color: #1e293b; line-height: 1.6;">
      <div><strong>Nama:</strong> <span id="resModalName">—</span></div>
      <div><strong>Instansi:</strong> <span id="resModalCompany">—</span></div>
      <div><strong>Kode Tiket:</strong> <span id="resModalCode" style="font-family: monospace; font-weight: 700;">—</span></div>
      <div id="resModalTimeRow"><strong>Waktu Check-In:</strong> <span id="resModalTime">—</span></div>
    </div>

    <div style="padding: 16px 20px; background: #ffffff;">
      <button type="button" id="btnCloseScanModal" class="btn-submit-ots" style="width: 100%; padding: 11px; font-size: 13.5px; margin: 0;">
        Tutup & Lanjut Scan (<span id="modalCountdown">3</span>s)
      </button>
    </div>
  </div>
</div>

<script>
window.GATE_SCHEDULE_MAP = <?= json_encode($scheduleDaysMap ?? []) ?>;
let html5QrCode = null;
let isCameraRunning = false;
let isScanLocked = false;
let modalCountdownTimer = null;
let feedbackBannerTimer = null;

// State Pagination & Filter
let currentPage = 1;
let currentSortCol = 'id';
let currentSortDir = 'desc';
let currentStatus = '';
let currentVisitDay = '';
let currentKeyword = '';
const perPage = 20; // 20 data per halaman tanpa dropdown

// Target checkin yang sedang menunggu konfirmasi modal
let pendingTicketCode = null;

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

// Banner feedback otomatis memudar dan hilang setelah 4 detik (POIN 5)
function showAutoHideFeedback(cls, title, htmlText) {
  const fb = document.getElementById('scanFeedback');
  const fTitle = document.getElementById('feedbackTitle');
  const fDesc = document.getElementById('feedbackDesc');
  if (!fb) return;

  if (feedbackBannerTimer) clearTimeout(feedbackBannerTimer);

  fb.className = 'scan-feedback ' + cls;
  fb.classList.remove('fade-out');
  fTitle.textContent = title;
  fDesc.innerHTML = htmlText;
  fb.style.display = 'block';

  // Setelah 3.6 detik mulai fade out, lalu sembunyikan total di detik ke-4
  feedbackBannerTimer = setTimeout(() => {
    fb.classList.add('fade-out');
    setTimeout(() => {
      fb.style.display = 'none';
      fb.classList.remove('fade-out');
    }, 400);
  }, 3600);
}

function openScanResultModal(type, data) {
  isScanLocked = true;
  const modal = document.getElementById('scanResultModal');
  const icon = document.getElementById('modalStatusIcon');
  const title = document.getElementById('modalStatusTitle');
  const sub = document.getElementById('modalStatusSub');
  const partBox = document.getElementById('modalParticipantBox');
  const cdSpan = document.getElementById('modalCountdown');

  if (modalCountdownTimer) clearInterval(modalCountdownTimer);

  if (type === 'success') {
    icon.style.background = '#dcfce7';
    icon.style.color = '#15803d';
    icon.innerHTML = '<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>';
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
    icon.innerHTML = '<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>';
    title.textContent = 'TIKET SUDAH CHECK-IN';
    title.style.color = '#b45309';
    sub.textContent = 'Peringatan: Tiket ini sudah pernah digunakan sebelumnya!';
    partBox.style.display = 'block';
    document.getElementById('resModalName').textContent = data.participant?.full_name || '-';
    document.getElementById('resModalCompany').textContent = (data.participant?.company_name || 'Umum') + (data.participant?.job_title ? ' (' + data.participant.job_title + ')' : '');
    document.getElementById('resModalCode').textContent = data.participant?.ticket_code || '-';
    document.getElementById('resModalTimeRow').style.display = 'block';
    document.getElementById('resModalTime').textContent = data.checked_in_at || 'Sebelumnya';
  } else {
    icon.style.background = '#fee2e2';
    icon.style.color = '#dc2626';
    icon.innerHTML = '<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>';
    title.textContent = 'TIKET TIDAK VALID';
    title.style.color = '#dc2626';
    sub.textContent = data.message || 'Kode tiket tidak ditemukan di sistem.';
    partBox.style.display = 'none';
  }

  modal.style.display = 'flex';

  let secLeft = 3;
  cdSpan.textContent = secLeft;
  modalCountdownTimer = setInterval(() => {
    secLeft--;
    cdSpan.textContent = secLeft;
    if (secLeft <= 0) {
      clearInterval(modalCountdownTimer);
      closeScanResultModal();
    }
  }, 1000);
}

function closeScanResultModal() {
  if (modalCountdownTimer) clearInterval(modalCountdownTimer);
  document.getElementById('scanResultModal').style.display = 'none';
  isScanLocked = false;
  document.getElementById('manualTicketCode').focus();
}

document.getElementById('btnCloseScanModal').addEventListener('click', closeScanResultModal);

// Modal Konfirmasi Check-in (POIN 4)
function openConfirmModal(ticketCode, fullName, company, dayLabel) {
  pendingTicketCode = ticketCode;
  document.getElementById('confModalName').textContent = fullName;
  document.getElementById('confModalCompany').textContent = company;
  document.getElementById('confModalCode').textContent = ticketCode;
  document.getElementById('confModalDay').textContent = dayLabel;
  document.getElementById('confirmCheckinModal').style.display = 'flex';
}

document.getElementById('btnCancelConfirm').addEventListener('click', function() {
  pendingTicketCode = null;
  document.getElementById('confirmCheckinModal').style.display = 'none';
});

document.getElementById('btnExecuteConfirm').addEventListener('click', function() {
  if (pendingTicketCode) {
    const code = pendingTicketCode;
    pendingTicketCode = null;
    document.getElementById('confirmCheckinModal').style.display = 'none';
    verifyTicketCode(code);
  }
});

async function verifyTicketCode(code) {
  if (!code) return;
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

    if (res.status === 200) {
      showAutoHideFeedback('success', 'BERHASIL CHECK-IN', `<strong>${data.participant?.full_name}</strong> &bull; Waktu: ${data.checked_in_at}`);
      openScanResultModal('success', data);
      fetchStats();
      loadParticipantsData(currentPage);
    } else if (res.status === 409) {
      showAutoHideFeedback('warning', 'TIKET SUDAH DIGUNAKAN', `Tiket <strong>${data.participant?.full_name || '-'}</strong> sudah pernah check-in.`);
      openScanResultModal('already_checked_in', data);
      loadParticipantsData(currentPage);
    } else {
      showAutoHideFeedback('warning', 'TIKET TIDAK VALID', data.message || 'Kode tiket tidak ditemukan.');
      openScanResultModal('invalid', data);
    }
  } catch (err) {
    showAutoHideFeedback('warning', 'ERROR KONEKSI', 'Gagal memverifikasi ke server. Periksa jaringan.');
    openScanResultModal('invalid', { message: 'Gagal menghubungi server verifikasi.' });
  }
}

const manualCheckinFormEl = document.getElementById('manualCheckinForm');
if (manualCheckinFormEl) {
  manualCheckinFormEl.addEventListener('submit', function(e) {
    e.preventDefault();
    const input = document.getElementById('manualTicketCode');
    const val = input ? input.value.trim() : '';
    if (val) {
      verifyTicketCode(val);
      input.value = '';
    }
  });
}

// Camera Toggle
const toggleCameraBtnEl = document.getElementById('toggleCameraBtn');
if (toggleCameraBtnEl) {
  toggleCameraBtnEl.addEventListener('click', function() {
    const btn = this;
    const scannerCard = document.getElementById('scannerSection');

    if (!isCameraRunning) {
      html5QrCode = new Html5Qrcode("reader");
      html5QrCode.start(
        { facingMode: "environment" },
        { fps: 15, qrbox: { width: 260, height: 260 } },
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
        btn.innerHTML = `<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="9" y1="9" x2="15" y2="15"></line><line x1="15" y1="9" x2="9" y2="15"></line></svg> <span>Matikan Kamera</span>`;
        if (scannerCard) {
          setTimeout(() => scannerCard.scrollIntoView({ behavior: 'smooth', block: 'start' }), 150);
        }
      }).catch(err => {
        alert("Tidak dapat mengakses kamera: " + err);
      });
    } else {
      html5QrCode.stop().then(() => {
        isCameraRunning = false;
        btn.classList.remove('active-cam');
        btn.innerHTML = `<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path><circle cx="12" cy="13" r="4"></circle></svg> <span>Nyalakan Kamera</span>`;
      });
    }
  });
}

// LOAD PARTICIPANTS DATA (VLC AJAX FILTER, SORTING, PAGINATION)
async function loadParticipantsData(page = 1) {
  currentPage = page;
  const tbody = document.getElementById('participantTableBody');
  const mBox = document.getElementById('mobileCardsContainer');

  // Lock minimum height during loading agar halaman tidak loncat ke atas (stay in position)
  if (tbody && tbody.offsetHeight > 100) {
    tbody.style.minHeight = tbody.offsetHeight + 'px';
  }

  tbody.innerHTML = `<tr><td colspan="7" style="text-align:center; padding:32px; color:#64748b;">Memuat data...</td></tr>`;
  mBox.innerHTML = `<div style="text-align:center; padding:20px; color:#64748b; font-size:13px;">Memuat data...</div>`;

  const params = new URLSearchParams({
    page: currentPage,
    per_page: perPage,
    sort: currentSortCol,
    order: currentSortDir,
    status: currentStatus,
    visit_day: currentVisitDay,
    q: currentKeyword,
  });

  try {
    const res = await fetch("<?= base_url('gate/portal_desk_ops_access_verify/search') ?>?" + params.toString());
    const json = await res.json();
    const list = json.data || [];
    const pInfo = json.pagination || { current_page: 1, total_pages: 1, total_records: 0, showing_from: 0, showing_to: 0 };

    renderTableRows(list, pInfo.showing_from);
    renderMobileCards(list);
    renderPaginationControls(pInfo);
    updateSortIndicators();
  } catch (err) {
    tbody.innerHTML = `<tr><td colspan="7" style="text-align:center; padding:24px; color:#dc2626;">Gagal memuat data peserta.</td></tr>`;
    mBox.innerHTML = `<div style="text-align:center; padding:20px; color:#dc2626; font-size:13px;">Gagal memuat data peserta.</div>`;
  }
}

function renderTableRows(list, startIndex) {
  const tbody = document.getElementById('participantTableBody');
  if (tbody) tbody.style.minHeight = '';
  if (!list || list.length === 0) {
    tbody.innerHTML = `<tr><td colspan="7" style="text-align:center; padding:24px; color:#64748b;">Tidak ada data peserta yang cocok.</td></tr>`;
    return;
  }

  tbody.innerHTML = list.map((item, idx) => {
    const rowNum = (startIndex || 1) + idx;
    const dayInfo = (window.GATE_SCHEDULE_MAP && window.GATE_SCHEDULE_MAP[item.visit_day]) ? window.GATE_SCHEDULE_MAP[item.visit_day] : null;
    let dayBadge = '<span style="background:#f0fdf4; color:#15803d; font-size:11px; font-weight:700; padding:3px 7px; border-radius:5px;">Semua Hari</span>';
    let dayLabel = (window.GATE_SCHEDULE_MAP && window.GATE_SCHEDULE_MAP['all_days']) ? window.GATE_SCHEDULE_MAP['all_days'].full_label : 'Semua Hari';
    if (dayInfo && item.visit_day !== 'all_days') {
      const bg = (item.visit_day === 'day_1') ? '#e0f2fe' : ((item.visit_day === 'day_2') ? '#fef3c7' : '#ede9fe');
      const fg = (item.visit_day === 'day_1') ? '#0369a1' : ((item.visit_day === 'day_2') ? '#b45309' : '#6d28d9');
      dayBadge = `<span style="background:${bg}; color:${fg}; font-size:11px; font-weight:700; padding:3px 7px; border-radius:5px;">${dayInfo.badge_label}</span>`;
      dayLabel = dayInfo.full_label;
    }

    const isChecked = parseInt(item.is_checked_in || 0, 10) === 1;
    const cCount = parseInt(item.checkin_count || 0, 10);
    let statusCol = '';
    let actionBtn = '';

    const safeName = (item.full_name || '').replace(/'/g, "\\'");
    const safeCompany = ((item.company_name || 'Umum') + (item.job_title ? ' (' + item.job_title + ')' : '')).replace(/'/g, "\\'");

    if (isChecked || cCount > 0) {
      statusCol = `<span class="badge-hadir">Hadir</span>`;
      actionBtn = `<span style="font-size:11.5px; color:#15803d; font-weight:700;">Sudah Masuk</span>`;
    } else {
      statusCol = `<span class="badge-belum-masuk">Belum Hadir</span>`;
      // Tombol Check-In memanggil modal konfirmasi terlebih dahulu (POIN 4)
      actionBtn = `<button type="button" class="btn-action-checkin" onclick="openConfirmModal('${item.ticket_code}', '${safeName}', '${safeCompany}', '${dayLabel}')">Check-In</button>`;
    }

    return `
      <tr>
        <td style="color:#64748b; font-size:12px;">${rowNum}</td>
        <td><strong style="font-family:monospace; color:#1e293b; letter-spacing:0.02em;">${item.ticket_code}</strong></td>
        <td>
          <div style="font-weight:700; color:#0f172a;">${item.full_name}</div>
          <div style="font-size:11.5px; color:#64748b;">${item.phone || item.email || '-'}</div>
        </td>
        <td>
          <div style="font-weight:600; color:#334155;">${item.company_name || 'Umum'}</div>
          <div style="font-size:11px; color:#64748b;">${item.job_title || '-'}</div>
        </td>
        <td>${dayBadge}</td>
        <td>${statusCol}</td>
        <td style="text-align:right;">${actionBtn}</td>
      </tr>
    `;
  }).join('');
}

function renderMobileCards(list) {
  const mBox = document.getElementById('mobileCardsContainer');
  if (!list || list.length === 0) {
    mBox.innerHTML = `<div style="text-align:center; padding:20px; color:#64748b; font-size:13px;">Tidak ada data peserta ditemukan.</div>`;
    return;
  }

  mBox.innerHTML = list.map(item => {
    const dayInfo = (window.GATE_SCHEDULE_MAP && window.GATE_SCHEDULE_MAP[item.visit_day]) ? window.GATE_SCHEDULE_MAP[item.visit_day] : null;
    let dayTag = dayInfo ? dayInfo.badge_label : 'Semua Hari';
    const isChecked = parseInt(item.is_checked_in || 0, 10) === 1;

    const safeName = (item.full_name || '').replace(/'/g, "\\'");
    const safeCompany = ((item.company_name || 'Umum') + (item.job_title ? ' (' + item.job_title + ')' : '')).replace(/'/g, "\\'");

    return `
      <div class="mobile-p-card">
        <div style="display:flex; justify-content:space-between; align-items:start; margin-bottom:6px;">
          <div>
            <div style="font-weight:800; font-size:14.5px; color:#0f172a;">${item.full_name}</div>
            <div style="font-size:12.5px; color:#475569;">${item.company_name || 'Umum'} ${item.job_title ? '(' + item.job_title + ')' : ''}</div>
          </div>
          <div>
            ${isChecked ? '<span class="badge-hadir">Hadir</span>' : '<span class="badge-belum-masuk">Belum Hadir</span>'}
          </div>
        </div>
        <div style="margin-top:10px; padding-top:10px; border-top:1px dashed #e2e8f0;">
          <div style="font-family:monospace; color:#334155; font-weight:700; font-size:12px; line-height:1.4; margin-bottom:8px;">
            ${item.ticket_code} &bull; ${dayTag}
          </div>
          <div>
            ${!isChecked ? `<button type="button" class="btn-action-checkin btn-mobile-checkin" onclick="openConfirmModal('${item.ticket_code}', '${safeName}', '${safeCompany}', '${dayTag}')">Check-In</button>` : `<div class="mobile-checked-in-tag"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"></polyline></svg><span>Sudah Masuk</span></div>`}
          </div>
        </div>
      </div>
    `;
  }).join('');
}

function renderPaginationControls(pInfo) {
  const sumDiv = document.getElementById('vlcPaginationSummary');
  const ctrlDiv = document.getElementById('vlcPageControls');

  sumDiv.innerHTML = `Menampilkan <strong>${pInfo.showing_from}–${pInfo.showing_to}</strong> dari <strong>${pInfo.total_records}</strong> peserta • Halaman <strong>${pInfo.current_page}</strong> dari <strong>${pInfo.total_pages}</strong>`;

  let html = '';
  if (pInfo.current_page > 1) {
    html += `<button type="button" class="vlc-btn-page" onclick="loadParticipantsData(1)" title="Halaman Pertama">&laquo;</button>`;
    html += `<button type="button" class="vlc-btn-page" onclick="loadParticipantsData(${pInfo.current_page - 1})">&lsaquo; Prev</button>`;
  }

  html += `<span class="vlc-page-current">${pInfo.current_page}</span>`;

  if (pInfo.current_page < pInfo.total_pages) {
    html += `<button type="button" class="vlc-btn-page" onclick="loadParticipantsData(${pInfo.current_page + 1})">Next &rsaquo;</button>`;
    html += `<button type="button" class="vlc-btn-page" onclick="loadParticipantsData(${pInfo.total_pages})" title="Halaman Terakhir">&raquo;</button>`;
  }

  ctrlDiv.innerHTML = html;
}

function handleSort(col) {
  if (currentSortCol === col) {
    currentSortDir = (currentSortDir === 'asc') ? 'desc' : 'asc';
  } else {
    currentSortCol = col;
    currentSortDir = 'asc';
  }
  loadParticipantsData(1);
}

function updateSortIndicators() {
  const cols = ['ticket_code', 'full_name', 'company_name', 'visit_day', 'is_checked_in'];
  cols.forEach(c => {
    const el = document.getElementById('sort_' + c);
    if (!el) return;
    if (currentSortCol === c) {
      el.innerHTML = (currentSortDir === 'asc') ? '▲' : '▼';
      el.style.color = '#15803d';
    } else {
      el.innerHTML = '↕';
      el.style.color = '#94a3b8';
    }
  });
}

// Event Listeners for Filters
document.querySelectorAll('#statusFilterTabs .filter-pill-btn').forEach(btn => {
  btn.addEventListener('click', function() {
    document.querySelectorAll('#statusFilterTabs .filter-pill-btn').forEach(b => b.classList.remove('active'));
    this.classList.add('active');
    currentStatus = this.getAttribute('data-status') || '';
    loadParticipantsData(1);
  });
});

document.getElementById('filterVisitDay').addEventListener('change', function() {
  currentVisitDay = this.value;
  loadParticipantsData(1);
});

let searchDebounce = null;
document.getElementById('searchInput').addEventListener('input', function() {
  clearTimeout(searchDebounce);
  searchDebounce = setTimeout(() => {
    currentKeyword = this.value.trim();
    loadParticipantsData(1);
  }, 300);
});

// Inisialisasi: Data awal 20 peserta & telemetri sudah dirender instan oleh server (SSR).
// Polling telemetri tetap berjalan di background secara berkala.
setInterval(fetchStats, 15000);
</script>

</body>
</html>
