<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registrasi OTS Pengunjung — <?= esc($site['title'] ?? 'Energi & Mineral Festival 2026') ?></title>
  
  <link rel="shortcut icon" type="image/x-icon" href="<?= emf_url('favicon.ico') ?>?v=<?= file_exists(FCPATH . 'favicon.ico') ? filemtime(FCPATH . 'favicon.ico') : time() ?>">
  <link rel="icon" type="image/x-icon" href="<?= emf_url('favicon.ico') ?>?v=<?= file_exists(FCPATH . 'favicon.ico') ? filemtime(FCPATH . 'favicon.ico') : time() ?>">
  
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400;1,600&family=Playfair+Display:ital,wght@0,700;0,800;0,900;1,700&display=swap" rel="stylesheet">
  
  <link rel="stylesheet" href="<?= emf_url('assets/css/style.css') ?>">

  <style>
    html, body {
      background-color: var(--yellow-6, #f7ee39) !important;
      color: var(--green-10, #214415);
      font-family: 'Plus Jakarta Sans', sans-serif;
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
      background-color: var(--yellow-6, #f7ee39);
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
    }

    /* FORM OTS CARD */
    .ots-card {
      background: #ffffff;
      border: 1px solid rgba(73, 152, 46, 0.25);
      box-shadow: 0 8px 28px rgba(33, 68, 21, 0.08);
      border-radius: 16px;
      padding: 32px 28px;
      text-align: left;
    }

    .ots-header {
      margin-bottom: 24px;
      padding-bottom: 16px;
      border-bottom: 1.5px solid var(--yellow-4, #f8e68c);
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
      gap: 10px;
    }

    .ots-badge-direct {
      background: #dcfce7;
      color: #15803d;
      border: 1px solid #86efac;
      padding: 6px 12px;
      border-radius: 9999px;
      font-size: 12px;
      font-weight: 800;
      letter-spacing: 0.02em;
      display: inline-flex;
      align-items: center;
      gap: 6px;
    }

    .reg-grid-2 {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 16px;
    }

    @media (max-width: 640px) {
      .reg-grid-2 {
        grid-template-columns: 1fr;
        gap: 0;
      }
    }

    .reg-form-group {
      margin-bottom: 18px;
    }

    .reg-label {
      display: block;
      font-size: 13px;
      font-weight: 700;
      color: var(--green-10, #214415);
      margin-bottom: 6px;
    }

    .reg-label .req {
      color: #dc2626;
    }

    .reg-input, .reg-select, .reg-textarea {
      width: 100%;
      background: #ffffff;
      border: 1.5px solid rgba(73, 152, 46, 0.35);
      border-radius: 10px;
      padding: 12px 14px;
      font-size: 14px;
      color: #1a202c;
      font-family: inherit;
      outline: none;
      box-sizing: border-box;
      transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    .reg-input:focus, .reg-select:focus, .reg-textarea:focus {
      border-color: var(--green-6, #49982e);
      box-shadow: 0 0 0 3px rgba(73, 152, 46, 0.15);
    }

    .btn-submit-ots {
      width: 100%;
      background: var(--green-6, #49982e);
      color: #ffffff;
      border: none;
      border-radius: 12px;
      padding: 15px 24px;
      font-size: 15px;
      font-weight: 800;
      cursor: pointer;
      font-family: inherit;
      transition: all 0.2s ease;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      box-shadow: 0 4px 14px rgba(73, 152, 46, 0.35);
    }

    .btn-submit-ots:hover {
      background: var(--green-7, #3e8127);
      transform: translateY(-1px);
      box-shadow: 0 6px 18px rgba(73, 152, 46, 0.45);
    }

    .btn-submit-ots:disabled {
      background: #94a3b8;
      cursor: not-allowed;
      transform: none;
      box-shadow: none;
    }

    .ots-alert-error {
      background: #fee2e2;
      border: 1px solid #ef4444;
      color: #b91c1c;
      padding: 12px 16px;
      border-radius: 10px;
      font-size: 13.5px;
      margin-bottom: 20px;
      display: none;
    }

    /* Tab Switch Kategori Pengunjung (Pribadi vs Company) */
    .reg-type-tab-wrapper {
      margin-bottom: 24px;
    }

    .reg-type-tabs {
      display: flex;
      background: #f1f5f9;
      padding: 5px;
      border-radius: 12px;
      border: 1.5px solid #e2e8f0;
      gap: 6px;
    }

    .reg-type-tab-btn {
      flex: 1;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      padding: 11px 16px;
      font-size: 13.5px;
      font-weight: 700;
      font-family: inherit;
      color: #64748b;
      background: transparent;
      border: none;
      border-radius: 9px;
      cursor: pointer;
      transition: all 0.22s ease;
      user-select: none;
    }

    .reg-type-tab-btn:hover:not(.active) {
      color: var(--green-9, #2a571a);
      background: rgba(255, 255, 255, 0.7);
    }

    .reg-type-tab-btn.active {
      background: var(--green-6, #49982e);
      color: #ffffff;
      box-shadow: 0 3px 8px rgba(73, 152, 46, 0.28);
    }

    .reg-type-footnote {
      display: flex;
      align-items: center;
      gap: 8px;
      font-size: 12px;
      color: #475569;
      margin-top: 10px;
      line-height: 1.45;
      padding: 8px 12px;
      background: #f8fafc;
      border-left: 3.5px solid var(--green-6, #49982e);
      border-radius: 0 8px 8px 0;
      box-sizing: border-box;
      transition: all 0.2s ease;
    }

    .reg-type-footnote .footnote-icon {
      flex-shrink: 0;
      width: 18px;
      height: 18px;
      border-radius: 50%;
      background: #e6f4ea;
      color: var(--green-7, #3e8127);
      display: inline-flex;
      align-items: center;
      justify-content: center;
      font-size: 11px;
      font-weight: 800;
    }
  </style>
</head>
<body>

<div class="terminal-wrapper">
  <div class="terminal-container">

    <!-- TOP NAVIGATION BAR (3 TABS) -->
    <div class="gate-nav-bar">
      <div class="gate-nav-brand">
        <div>
          <div style="font-size: 10.5px; text-transform: uppercase; color: var(--green-7, #3e8127); font-weight: 800; letter-spacing: 0.08em;">Terminal Resepsionis</div>
          <div style="font-size: 15px; font-weight: 800; color: var(--green-10, #214415);"><?= esc($site['title'] ?? 'Energi & Mineral Festival 2026') ?></div>
        </div>
      </div>

      <div class="gate-nav-tabs">
        <a href="<?= base_url('gate/portal_desk_ops_access_verify') ?>" class="gate-tab-link">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M3 7V5a2 2 0 0 1 2-2h2M17 3h2a2 2 0 0 1 2 2v2M21 17v2a2 2 0 0 1-2 2h-2M7 21H5a2 2 0 0 1-2-2v-2"></path><rect x="7" y="7" width="10" height="10" rx="1"></rect></svg>
          <span>Scanner &amp;&nbsp;Presensi</span>
        </a>
        <a href="<?= base_url('gate/portal_desk_ops_access_verify/invitations') ?>" class="gate-tab-link">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
          <span>Presensi Undangan</span>
        </a>
        <a href="<?= base_url('gate/portal_desk_ops_access_verify/registration') ?>" class="gate-tab-link active">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><line x1="20" y1="8" x2="20" y2="14"></line><line x1="23" y1="11" x2="17" y2="11"></line></svg>
          <span>Registrasi OTS</span>
        </a>
      </div>
    </div>

    <!-- FORM OTS CARD -->
    <div class="ots-card">
      <div class="ots-header">
        <div>
          <h2 style="font-size: 20px; font-weight: 800; margin: 0 0 4px 0; color: var(--green-10, #214415);">
            Pendaftaran On-The-Spot (OTS)
          </h2>
          <p style="font-size: 13px; color: var(--green-8, #346c21); margin: 0;">
            Daftarkan tamu di tempat tanpa scan barcode terpisah. Sistem otomatis mengesahkan status <strong>Sudah Hadir (Checked-In)</strong>.
          </p>
        </div>
        <div class="ots-badge-direct">
          Status Langsung Checked-In
        </div>
      </div>

      <div id="otsErrorBox" class="ots-alert-error"></div>

      <form id="otsForm">
        <?= csrf_field() ?>

        <!-- TAB KATEGORI PENGUNJUNG (PRIBADI VS COMPANY) -->
        <div class="reg-type-tab-wrapper">
          <label class="reg-label" style="margin-bottom: 8px;">Kategori Pengunjung <span class="req">*</span></label>
          <div class="reg-type-tabs" role="tablist">
            <button type="button" class="reg-type-tab-btn active" id="tabPribadi" data-type="personal" role="tab" aria-selected="true">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 7px; flex-shrink: 0;">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                <circle cx="12" cy="7" r="4"></circle>
              </svg>
              Pribadi / Perorangan
            </button>
            <button type="button" class="reg-type-tab-btn" id="tabCompany" data-type="company" role="tab" aria-selected="false">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 7px; flex-shrink: 0;">
                <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
              </svg>
              Instansi / Perusahaan
            </button>
          </div>
          <input type="hidden" name="registration_type" id="registration_type" value="personal">
          
          <!-- Footnote tepat di bawah tab switch -->
          <div id="regTypeFootnote" class="reg-type-footnote">
            <span class="footnote-icon">&#9432;</span>
            <span id="regTypeFootnoteText">Pendaftaran perorangan / umum. Tidak memerlukan data kontak narahubung (PIC).</span>
          </div>
        </div>

        <div class="reg-form-group">
          <label class="reg-label" for="full_name">Nama Lengkap Tamu <span class="req">*</span></label>
          <input type="text" id="full_name" name="full_name" class="reg-input" required minlength="3" placeholder="Contoh: Ir. Bambang Wicaksono" autofocus>
          <div style="font-size: 11px; color: #64748b; margin-top: 4px;">Minimal 3 karakter huruf.</div>
        </div>

        <div class="reg-grid-2">
          <div class="reg-form-group">
            <label class="reg-label" for="email">Alamat Email Tamu <span class="req">*</span></label>
            <input type="email" id="email" name="email" class="reg-input" required placeholder="bambang@instansi.go.id">
            <div style="font-size: 11px; color: #64748b; margin-top: 4px;">Tiket dan QR Code akan dikirim ke email ini.</div>
          </div>
          <div class="reg-form-group">
            <label class="reg-label" for="phone">No. HP / WhatsApp <span class="req">*</span></label>
            <input type="tel" id="phone" name="phone" class="reg-input" required pattern="^(08|\+?628)[0-9]{7,11}$" placeholder="081234567890">
            <div style="font-size: 11px; color: #64748b; margin-top: 4px;">Nomor Indonesia berawalan 08 (9 - 13 digit angka).</div>
          </div>
        </div>

        <div class="reg-grid-2">
          <div class="reg-form-group">
            <label class="reg-label" for="company_name">Instansi / Lembaga / Perusahaan <span class="req">*</span></label>
            <input type="text" id="company_name" name="company_name" class="reg-input" required placeholder="Contoh: Kementerian ESDM">
          </div>
          <div class="reg-form-group">
            <label class="reg-label" for="job_title">Jabatan / Posisi <span class="req">*</span></label>
            <input type="text" id="job_title" name="job_title" class="reg-input" required placeholder="Contoh: Direktur Teknik Lingkungan">
          </div>
        </div>

        <div class="reg-grid-2">
          <div class="reg-form-group">
            <label class="reg-label" for="visit_day">Hari Kunjungan OTS <span class="req">*</span></label>
            <select id="visit_day" name="visit_day" class="reg-select" required>
              <?php if (!empty($visitDayOptions)): ?>
                <?php foreach ($visitDayOptions as $opt): ?>
                  <option value="<?= esc($opt['value']) ?>" <?= ($opt['value'] === 'all_days') ? 'selected' : '' ?>>
                    <?= esc($opt['label']) ?>
                  </option>
                <?php endforeach; ?>
              <?php else: ?>
                <option value="day_1">Hari 1 — Selasa, 10 Nov 2026</option>
                <option value="day_2">Hari 2 — Rabu, 11 Nov 2026</option>
                <option value="all_days" selected>Semua Hari (10 - 11 Nov 2026)</option>
              <?php endif; ?>
            </select>
          </div>
          <div class="reg-form-group">
            <label class="reg-label" for="address">Alamat Domisili Tamu <span class="req">*</span></label>
            <input type="text" id="address" name="address" class="reg-input" required placeholder="Kota domisili atau alamat kantor..." value="Jakarta">
          </div>
        </div>

        <!-- GRUP FIELD PIC (KHUSUS INSTANSI / PERUSAHAAN) -->
        <div id="picSectionWrapper" style="display: none; margin-top: 18px; padding-top: 18px; border-top: 1px dashed rgba(73, 152, 46, 0.35);">
          <div style="font-size: 13.5px; font-weight: 800; color: var(--green-9, #2a571a); margin-bottom: 4px;">
            Kontak Narahubung / PIC Perusahaan <span class="req">*</span>
          </div>
          <div style="font-size: 12px; color: #64748b; margin-bottom: 14px;">
            Wajib diisi untuk tamu delegasi instansi / perusahaan.
          </div>

          <div class="reg-grid-2">
            <div class="reg-form-group">
              <label class="reg-label" for="pic_name">Nama PIC / Narahubung <span class="req">*</span></label>
              <input type="text" id="pic_name" name="pic_name" class="reg-input" placeholder="Nama narahubung penanggung jawab" disabled>
            </div>
            <div class="reg-form-group">
              <label class="reg-label" for="pic_phone">No. Telp / WhatsApp PIC <span class="req">*</span></label>
              <input type="tel" id="pic_phone" name="pic_phone" class="reg-input" placeholder="081987654321" disabled>
            </div>
          </div>

          <div class="reg-form-group">
            <label class="reg-label" for="company_email">Email Perusahaan / Kantor <span class="req">*</span></label>
            <input type="email" id="company_email" name="company_email" class="reg-input" placeholder="corporate@perusahaan.co.id" disabled>
          </div>
        </div>

        <div style="margin-top: 24px; padding-top: 18px; border-top: 1px solid #e2e8f0;">
          <button type="submit" id="btnSubmitOts" class="btn-submit-ots">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
            <span>Daftarkan & Check-In Tamu Langsung</span>
          </button>
        </div>
      </form>
    </div>

  </div>
</div>

<!-- MODAL SUKSES REGISTRASI OTS -->
<div id="otsSuccessModal" style="display: none; position: fixed; inset: 0; background: rgba(0, 0, 0, 0.7); z-index: 99999; justify-content: center; align-items: center; padding: 20px; box-sizing: border-box;">
  <div style="background: #ffffff; width: 100%; max-width: 480px; border-radius: 18px; overflow: hidden; box-shadow: 0 24px 48px rgba(0,0,0,0.35); text-align: center;">
    <div style="background: #f0fdf4; padding: 28px 24px 18px 24px; border-bottom: 1px solid #dcfce7;">
      <div style="width: 64px; height: 64px; background: #22c55e; color: #ffffff; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto; font-size: 32px; font-weight: 900; box-shadow: 0 6px 18px rgba(34, 197, 94, 0.35);">
        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"></polyline></svg>
      </div>
      <h3 style="margin: 16px 0 4px 0; font-size: 20px; font-weight: 800; color: #15803d;">
        REGISTRASI OTS BERHASIL
      </h3>
      <p style="margin: 0; font-size: 13.5px; color: #166534;">
        Tamu telah didaftarkan dan <strong>resmi Checked-In</strong> ke venue.
      </p>
    </div>

    <div style="padding: 20px 24px; text-align: left; font-size: 13.5px; color: #1e293b; line-height: 1.7; background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
      <div><strong>Nama Tamu:</strong> <span id="otsResName">—</span></div>
      <div><strong>Instansi:</strong> <span id="otsResCompany">—</span></div>
      <div><strong>Kode Tiket:</strong> <span id="otsResCode" style="font-family: monospace; font-weight: 800; color: #15803d; letter-spacing: 0.05em;">—</span></div>
      <div><strong>Waktu Check-In:</strong> <span id="otsResTime">—</span></div>
      <div style="margin-top: 6px; font-size: 12px; color: #64748b;">
        *E-ticket barcode & QR Code telah dikirimkan ke email <strong id="otsResEmail">—</strong>.
      </div>
    </div>

    <div style="padding: 20px 24px; display: flex; gap: 10px;">
      <button type="button" id="btnNextOts" style="flex: 1; background: var(--green-6, #49982e); color: #ffffff; border: none; border-radius: 10px; padding: 12px; font-size: 13.5px; font-weight: 700; cursor: pointer;">
        + Daftarkan Tamu Baru
      </button>
      <a href="<?= base_url('gate/portal_desk_ops_access_verify') ?>" style="flex: 1; text-decoration: none; background: #e2e8f0; color: #1e293b; border-radius: 10px; padding: 12px; font-size: 13.5px; font-weight: 700; text-align: center; display: inline-flex; align-items: center; justify-content: center;">
        Kembali ke Scanner
      </a>
    </div>
  </div>
</div>

<script>
const otsForm = document.getElementById('otsForm');
const btnSubmitOts = document.getElementById('btnSubmitOts');
const otsErrorBox = document.getElementById('otsErrorBox');
const otsSuccessModal = document.getElementById('otsSuccessModal');

// Tab Switch Kategori Pengunjung (Pribadi vs Company)
const tabPribadi = document.getElementById('tabPribadi');
const tabCompany = document.getElementById('tabCompany');
const regTypeInput = document.getElementById('registration_type');
const picSection = document.getElementById('picSectionWrapper');
const footnoteText = document.getElementById('regTypeFootnoteText');

function setRegistrationType(type) {
  if (type === 'company') {
    tabCompany.classList.add('active');
    tabCompany.setAttribute('aria-selected', 'true');
    tabPribadi.classList.remove('active');
    tabPribadi.setAttribute('aria-selected', 'false');
    if (regTypeInput) regTypeInput.value = 'company';
    if (footnoteText) {
      footnoteText.textContent = 'Pendaftaran delegasi instansi / perusahaan. Wajib menyertakan data narahubung (PIC) penanggung jawab.';
    }
    
    // Tampilkan & aktifkan PIC fields (mandatory)
    if (picSection) {
      picSection.style.display = 'block';
      const picInputs = picSection.querySelectorAll('input');
      picInputs.forEach(inp => {
        inp.disabled = false;
        inp.required = true;
      });
    }
  } else {
    // Default: personal
    tabPribadi.classList.add('active');
    tabPribadi.setAttribute('aria-selected', 'true');
    tabCompany.classList.remove('active');
    tabCompany.setAttribute('aria-selected', 'false');
    if (regTypeInput) regTypeInput.value = 'personal';
    if (footnoteText) {
      footnoteText.textContent = 'Pendaftaran perorangan / umum. Tidak memerlukan data kontak narahubung (PIC).';
    }
    
    // Sembunyikan & non-aktifkan PIC fields
    if (picSection) {
      picSection.style.display = 'none';
      const picInputs = picSection.querySelectorAll('input');
      picInputs.forEach(inp => {
        inp.disabled = true;
        inp.required = false;
        inp.value = '';
      });
    }
  }
}

if (tabPribadi) {
  tabPribadi.addEventListener('click', () => setRegistrationType('personal'));
}
if (tabCompany) {
  tabCompany.addEventListener('click', () => setRegistrationType('company'));
}

otsForm.addEventListener('submit', async function(e) {
  e.preventDefault();
  otsErrorBox.style.display = 'none';

  // Validasi Kategori Company: PIC wajib lengkap
  const currentRegType = regTypeInput ? regTypeInput.value : 'personal';
  if (currentRegType === 'company') {
    const picNameVal = (document.getElementById('pic_name')?.value || '').trim();
    const picPhoneVal = (document.getElementById('pic_phone')?.value || '').trim();
    const companyEmailVal = (document.getElementById('company_email')?.value || '').trim();

    if (!picNameVal || !picPhoneVal || !companyEmailVal) {
      otsErrorBox.textContent = 'Untuk kategori Instansi / Perusahaan, kolom Nama PIC, No. Telp PIC, dan Email Perusahaan wajib diisi.';
      otsErrorBox.style.display = 'block';
      otsErrorBox.scrollIntoView({ behavior: 'smooth', block: 'center' });
      return;
    }

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(companyEmailVal)) {
      otsErrorBox.textContent = 'Format email perusahaan tidak valid (contoh: corporate@perusahaan.co.id).';
      otsErrorBox.style.display = 'block';
      document.getElementById('company_email')?.focus();
      return;
    }
  }

  btnSubmitOts.disabled = true;
  btnSubmitOts.innerHTML = `<span>Menyimpan & Check-In...</span>`;

  const formData = new FormData(this);

  try {
    const res = await fetch("<?= base_url('gate/portal_desk_ops_access_verify/registration/submit') ?>", {
      method: 'POST',
      headers: {
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
      },
      body: formData
    });

    const json = await res.json();

    if (res.ok && json.success) {
      document.getElementById('otsResName').textContent = json.participant?.full_name || formData.get('full_name');
      document.getElementById('otsResCompany').textContent = (json.participant?.company_name || formData.get('company_name')) + (json.participant?.job_title ? ' (' + json.participant.job_title + ')' : '');
      document.getElementById('otsResCode').textContent = json.ticket_code || '-';
      document.getElementById('otsResTime').textContent = json.checked_in_at || 'Sekarang';
      document.getElementById('otsResEmail').textContent = json.participant?.email || formData.get('email');

      otsSuccessModal.style.display = 'flex';
    } else {
      otsErrorBox.textContent = json.message || 'Gagal memproses pendaftaran OTS.';
      otsErrorBox.style.display = 'block';
    }
  } catch (err) {
    otsErrorBox.textContent = 'Gagal menghubungi server. Periksa koneksi internet Anda.';
    otsErrorBox.style.display = 'block';
  } finally {
    btnSubmitOts.disabled = false;
    btnSubmitOts.innerHTML = `<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg> <span>Daftarkan & Check-In Tamu Langsung</span>`;
  }
});

document.getElementById('btnNextOts').addEventListener('click', function() {
  otsSuccessModal.style.display = 'none';
  otsForm.reset();
  setRegistrationType('personal');
  document.getElementById('address').value = 'Jakarta';
  document.getElementById('full_name').focus();
});
</script>

</body>
</html>
