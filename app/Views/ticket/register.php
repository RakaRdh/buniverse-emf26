<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registrasi Pengunjung — <?= esc($site['title'] ?? 'Energi & Mineral Festival 2026') ?></title>
  
  <link rel="shortcut icon" type="image/x-icon" href="<?= emf_url('favicon.ico') ?>?v=<?= file_exists(FCPATH . 'favicon.ico') ? filemtime(FCPATH . 'favicon.ico') : time() ?>">
  <link rel="icon" type="image/x-icon" href="<?= emf_url('favicon.ico') ?>?v=<?= file_exists(FCPATH . 'favicon.ico') ? filemtime(FCPATH . 'favicon.ico') : time() ?>">
  
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400;1,600&family=Playfair+Display:ital,wght@0,700;0,800;0,900;1,700&display=swap" rel="stylesheet">
  
  <link rel="stylesheet" href="<?= emf_url('assets/css/style.css') ?>">
  <link rel="stylesheet" href="<?= emf_url('assets/css/tailwind.css') ?>">

  <?php if (!empty($themeProperties)): ?>
    <style id="live-theme-properties">
      :root {
        <?php foreach ($themeProperties as $tp): ?>
          <?= esc($tp['property']) ?>: <?= $tp['value'] ?>;
        <?php endforeach; ?>
      }
    </style>
  <?php endif; ?>

  <style>
    html, body {
      background-color: var(--yellow-6, #f7ee39) !important;
      color: var(--green-10, #214415);
      font-family: 'Plus Jakarta Sans', sans-serif;
      margin: 0;
      padding: 0;
      min-height: 100%;
    }

    .reg-wrapper {
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: flex-start;
      padding: 48px 20px 80px 20px;
      box-sizing: border-box;
      background-color: var(--yellow-6, #f4d849);
    }

    .reg-container {
      width: 100%;
      max-width: 680px;
      margin: 0 auto;
    }

    .reg-header {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      text-align: center;
      margin-bottom: 28px;
      width: 100%;
    }

    .reg-header-content {
      display: flex;
      flex-direction: column;
      align-items: center;
      text-align: center;
    }

    .reg-logo-link {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      text-decoration: none;
      flex-shrink: 0;
      margin-bottom: 18px;
    }

    .reg-logo {
      display: block;
      height: 76px;
      width: auto;
      object-fit: contain;
    }

    .reg-title {
      font-size: 26px;
      font-weight: 800;
      color: var(--green-9, #2a571a);
      margin: 0 0 8px 0;
      letter-spacing: -0.01em;
      line-height: 1.25;
    }

    .reg-subtitle {
      font-size: 14.5px;
      color: var(--green-8, #346c21);
      margin: 0;
      line-height: 1.5;
      font-weight: 600;
    }

    .reg-subtitle-line {
      display: block;
    }

    @media (min-width: 640px) {
      .reg-header {
        flex-direction: row;
        align-items: center;
        justify-content: center;
        gap: 28px;
        text-align: left;
      }

      .reg-logo-link {
        margin-bottom: 0;
      }

      .reg-logo {
        height: 84px;
      }

      .reg-header-content {
        align-items: flex-start;
        text-align: left;
      }

      .reg-title {
        text-align: left;
        font-size: 28px;
        margin-bottom: 4px;
      }

      .reg-subtitle {
        text-align: left;
      }

      .reg-subtitle-line {
        text-align: left;
      }
    }

    .reg-card {
      background: #ffffff;
      border: 1px solid rgba(73, 152, 46, 0.25);
      box-shadow: 0 8px 24px rgba(33, 68, 21, 0.08);
      border-radius: 16px;
      padding: 32px 28px;
      box-sizing: border-box;
    }

    .reg-section-title {
      font-size: 13px;
      font-weight: 800;
      text-transform: uppercase;
      letter-spacing: 0.06em;
      color: var(--green-7, #3e8127);
      margin: 0 0 20px 0;
      padding-bottom: 8px;
      border-bottom: 2px solid var(--yellow-4, #f8e68c);
    }

    .reg-grid-2 {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 16px;
    }

    @media (max-width: 600px) {
      .reg-grid-2 {
        grid-template-columns: 1fr;
        gap: 0;
      }
    }

    .reg-form-group {
      margin-bottom: 18px;
      text-align: left;
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
      border: 1.5px solid rgba(73, 152, 46, 0.3);
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

    .reg-input::placeholder, .reg-textarea::placeholder {
      color: #94a3b8;
    }

    .reg-select option {
      background: #ffffff;
      color: #1a202c;
    }

    .reg-phone-group {
      display: flex;
      gap: 8px;
      align-items: stretch;
      width: 100%;
    }

    .reg-country-select {
      flex: 0 0 auto;
      width: 110px;
      padding: 12px 8px;
      background: #ffffff;
      border: 1.5px solid rgba(73, 152, 46, 0.3);
      border-radius: 10px;
      font-size: 13.5px;
      font-weight: 600;
      color: #1a202c;
      font-family: inherit;
      outline: none;
      box-sizing: border-box;
      cursor: pointer;
    }

    .reg-country-select:focus {
      border-color: var(--green-6, #49982e);
      box-shadow: 0 0 0 3px rgba(73, 152, 46, 0.15);
    }

    .reg-phone-input {
      flex: 1;
      min-width: 0;
    }

    .reg-textarea {
      resize: vertical;
      min-height: 80px;
      line-height: 1.4;
    }

    .reg-btn-submit {
      width: 100%;
      background: var(--green-6, #49982e);
      color: #ffffff;
      border: none;
      border-radius: 10px;
      padding: 14px 20px;
      font-size: 15px;
      font-weight: 700;
      font-family: inherit;
      cursor: pointer;
      margin-top: 10px;
      transition: background 0.2s ease;
    }

    .reg-btn-submit:hover {
      background: var(--green-7, #3e8127);
    }

    .reg-btn-submit:disabled {
      opacity: 0.6;
      cursor: not-allowed;
    }

    .reg-alert-error {
      background: rgba(239, 68, 68, 0.15);
      border: 1px solid rgba(239, 68, 68, 0.4);
      color: #fca5a5;
      padding: 12px 16px;
      border-radius: 10px;
      font-size: 13.5px;
      margin-bottom: 20px;
      line-height: 1.4;
    }

    .reg-back-link {
      display: inline-block;
      margin-top: 24px;
      color: var(--green-9, #2a571a);
      font-size: 13.5px;
      font-weight: 600;
      text-decoration: none;
    }

    .reg-back-link:hover {
      color: var(--green-10, #214415);
      text-decoration: underline;
    }

    /* Tab Switch Kategori Pengunjung (Pribadi vs Company) */
    .reg-type-tab-wrapper {
      margin-bottom: 26px;
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
      font-size: 14px;
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

    /* Box Input Nama Tambahan (Multi-Tiket) */
    .additional-names-box {
      background: #f8fafc;
      border: 1.5px dashed var(--green-6, #49982e);
      border-radius: 12px;
      padding: 18px 16px;
      box-sizing: border-box;
      animation: modalIn 0.25s ease;
    }
  </style>
</head>
<body>

<div class="reg-wrapper">
  <div class="reg-container">
    
    <div class="reg-header">
      <a href="<?= base_url('/') ?>" class="reg-logo-link">
        <img src="<?= emf_url('assets/img/logo-green.webp') ?>" alt="<?= esc($site['title'] ?? 'Energi & Mineral Festival 2026') ?>" class="reg-logo">
      </a>
      <div class="reg-header-content">
        <h1 class="reg-title">Formulir Registrasi</h1>
        <div class="reg-subtitle">
          <span class="reg-subtitle-line"><?= esc($site['title'] ?? 'Energi & Mineral Festival 2026') ?></span>
          <span class="reg-subtitle-line" style="margin-top: 2px;"><?= esc($site['dates'] ?? '10–11 November 2026') ?><?= !empty($site['venue']) ? ' &bull; ' . esc($site['venue']) : '' ?></span>
        </div>
      </div>
    </div>

    <div class="reg-card">

      <?php if (!empty($isSoldOut)): ?>
        <div style="background: #fef2f2; border: 1.5px solid #ef4444; color: #991b1b; padding: 18px 20px; border-radius: 12px; margin-bottom: 24px; text-align: center;">
          <div style="font-size: 20px; font-weight: 800; margin-bottom: 4px;">Pendaftaran Ditutup (Sold Out)</div>
          <div style="font-size: 13.5px; line-height: 1.5; color: #b91c1c;">
            Mohon maaf, kuota tiket pengunjung untuk <strong>Energi & Mineral Festival 2026</strong> telah terpenuhi. Pendaftaran baru saat ini sudah tidak dapat diproses.
          </div>
        </div>
      <?php elseif (session()->getFlashdata('error')): ?>
        <div class="reg-alert-error">
          <?= esc(session()->getFlashdata('error')) ?>
        </div>
      <?php endif; ?>

      <form id="regForm" method="POST" action="<?= base_url('register/submit') ?>">
        <?= csrf_field() ?>
        <fieldset <?= !empty($isSoldOut) ? 'disabled style="border:none; padding:0; margin:0; opacity:0.6;"' : 'style="border:none; padding:0; margin:0;"' ?>>

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

        <!-- SEKSI 1: DATA DIRI -->
        <div class="reg-section-title">1. Data Diri & Kontak</div>

        <div class="reg-form-group">
          <label class="reg-label" for="full_name">Nama Lengkap <span class="req">*</span></label>
          <input type="text" id="full_name" name="full_name" class="reg-input" required minlength="3" placeholder="Contoh: Budi Santoso" value="<?= old('full_name') ?>">
          <div class="reg-hint-text" style="font-size: 11px; color: #64748b; margin-top: 4px;">Minimal 3 karakter.</div>
        </div>

        <div class="reg-grid-2">
          <div class="reg-form-group">
            <label class="reg-label" for="email">Email Aktif <span class="req">*</span></label>
            <input type="email" id="email" name="email" class="reg-input" required placeholder="budi@example.com" value="<?= old('email') ?>">
            <div class="reg-hint-text" style="font-size: 11px; color: #64748b; margin-top: 4px;">Tiket dan barcode akan dikirimkan ke email ini.</div>
          </div>
          <div class="reg-form-group">
            <label class="reg-label" for="phone_number">Nomor Telepon / WhatsApp <span class="req">*</span></label>
            <div class="reg-phone-group">
              <select id="country_dial" class="reg-country-select" aria-label="Kode Negara Telepon">
                <?php foreach (\App\Helpers\PhoneHelper::SUPPORTED_COUNTRIES as $code => $country): ?>
                  <option value="<?= esc($country['dial_code']) ?>" <?= $code === 'ID' ? 'selected' : '' ?>>
                    <?= $country['flag'] ?> +<?= esc($country['dial_code']) ?>
                  </option>
                <?php endforeach; ?>
              </select>
              <input type="tel" id="phone_number" class="reg-input reg-phone-input" required placeholder="81234567890" value="<?= old('phone') ?>">
            </div>
            <input type="hidden" id="phone" name="phone" value="<?= old('phone') ?>">
            <div class="reg-hint-text" style="font-size: 11px; color: #64748b; margin-top: 4px;">Contoh: 81234567890 (tanpa angka 0 di depan jika menggunakan kode negara).</div>
          </div>
        </div>

        <!-- SEKSI 2: JADWAL KUNJUNGAN & JUMLAH TIKET -->
        <div class="reg-section-title" style="margin-top: 28px;">2. Pilihan Hari & Jumlah Tiket</div>

        <div class="reg-grid-2">
          <div class="reg-form-group">
            <label class="reg-label" for="visit_day">Hari Kunjungan <span class="req">*</span></label>
            <select id="visit_day" name="visit_day" class="reg-select" required>
              <?php if (!empty($visitDayOptions)): ?>
                <?php foreach ($visitDayOptions as $opt): ?>
                  <option value="<?= esc($opt['value']) ?>" <?= old('visit_day') === $opt['value'] ? 'selected' : '' ?>>
                    <?= esc($opt['label']) ?>
                  </option>
                <?php endforeach; ?>
              <?php else: ?>
                <option value="day_1">Hari 1 — Selasa, 10 Nov 2026</option>
                <option value="day_2">Hari 2 — Rabu, 11 Nov 2026</option>
                <option value="all_days">Semua Hari (10 - 11 Nov 2026)</option>
              <?php endif; ?>
            </select>
          </div>
          <div class="reg-form-group">
            <label class="reg-label" for="ticket_quantity">
              Jumlah Tiket <span class="req">*</span>
              <!-- <span id="remainingQuotaBadge" style="font-size: 11.5px; font-weight: 700; color: #dc2626; margin-left: 4px; <?= (!empty($totalQuota) && $remainingQuota > 0) ? '' : 'display: none;' ?>">
                (Sisa: <?= (int)($remainingQuota ?? 0) ?> tiket)
              </span> -->
            </label>
            <select id="ticket_quantity" name="ticket_quantity" class="reg-select" required>
              <?php
                $maxSelectable = min(5, max(1, (int)($remainingQuota ?? 5)));
                for ($qty = 1; $qty <= $maxSelectable; $qty++):
              ?>
                <option value="<?= $qty ?>" <?= $qty === 1 ? 'selected' : '' ?>>
                  <?= $qty ?> Tiket
                </option>
              <?php endfor; ?>
            </select>
          </div>
        </div>

        <!-- CONTAINER NAMA TIKET TAMBAHAN (DITAMPILKAN JIKA JUMLAH TIKET > 1) -->
        <div id="additionalParticipantsWrapper" style="display: none; margin-top: 4px; margin-bottom: 20px;">
          <!-- Di-generate otomatis via JavaScript jika tiket > 1 -->
        </div>

        <div class="reg-form-group">
          <label class="reg-label" for="address">Alamat Domisili <span class="req">*</span></label>
          <textarea id="address" name="address" class="reg-textarea" required placeholder="Alamat lengkap domisili atau kantor..."><?= old('address') ?></textarea>
        </div>

        <!-- SEKSI 3: INSTANSI / PROFESIONAL -->
        <div class="reg-section-title" style="margin-top: 28px;">3. Informasi Instansi & Profesi</div>

        <div class="reg-grid-2">
          <div class="reg-form-group">
            <label class="reg-label" for="company_name">Instansi / Perusahaan <span class="req">*</span></label>
            <input type="text" id="company_name" name="company_name" class="reg-input" required placeholder="Contoh: PT Energi Nusantara" value="<?= old('company_name') ?>">
          </div>
          <div class="reg-form-group">
            <label class="reg-label" for="job_title">Jabatan / Posisi <span class="req">*</span></label>
            <input type="text" id="job_title" name="job_title" class="reg-input" required placeholder="Contoh: General Manager" value="<?= old('job_title') ?>">
          </div>
        </div>

        <!-- SEKSI 4: PIC (MANDATORY JIKA TAB INSTANSI / PERUSAHAAN TERPILIH) -->
        <div id="picSectionWrapper" style="display: none; margin-top: 28px;">
          <div class="reg-section-title">4. Kontak Narahubung / PIC Perusahaan <span class="req">*</span></div>
          <p style="font-size: 12.5px; color: #64748b; margin: -14px 0 16px 0; line-height: 1.5;">
            Wajib diisi untuk pendaftaran delegasi instansi / perusahaan.
          </p>

          <div class="reg-grid-2">
            <div class="reg-form-group">
              <label class="reg-label" for="pic_name">Nama PIC / Narahubung <span class="req">*</span></label>
              <input type="text" id="pic_name" name="pic_name" class="reg-input" placeholder="Nama narahubung penanggung jawab" value="<?= old('pic_name') ?>" disabled>
            </div>
            <div class="reg-form-group">
              <label class="reg-label" for="pic_phone">No. Telp / WhatsApp PIC <span class="req">*</span></label>
              <input type="tel" id="pic_phone" name="pic_phone" class="reg-input" placeholder="081987654321" value="<?= old('pic_phone') ?>" disabled>
            </div>
          </div>

          <div class="reg-form-group">
            <label class="reg-label" for="company_email">Email Perusahaan / Kantor <span class="req">*</span></label>
            <input type="email" id="company_email" name="company_email" class="reg-input" placeholder="corporate@perusahaan.co.id" value="<?= old('company_email') ?>" disabled>
            <div class="reg-hint-text" style="font-size: 11px; color: #64748b; margin-top: 4px;">Alamat email resmi instansi / PIC penanggung jawab.</div>
          </div>
        </div>

        <!-- SEKSI INFORMASI TAMBAHAN (PENOMORAN DINAMIS SESUAI TAB) -->
        <div id="sourceInfoSectionTitle" class="reg-section-title" style="margin-top: 28px;">4. Informasi Tambahan</div>

        <div class="reg-form-group">
          <label class="reg-label" for="source_info">Mengetahui Acara Ini Dari Mana?</label>
          <select id="source_info" name="source_info" class="reg-select">
            <option value="">Pilih Sumber Informasi</option>
            <option value="Media Sosial (Instagram, LinkedIn, X)">Media Sosial (Instagram, LinkedIn, X)</option>
            <option value="Website & Berita B-Universe">Website & Berita B-Universe</option>
            <option value="Undangan Resmi / Email Blast">Undangan Resmi / Email Blast</option>
            <option value="Rekan Kerja / Instansi">Rekan Kerja / Instansi</option>
            <option value="Media Cetak / Elektronik">Media Cetak / Elektronik</option>
            <option value="Lainnya">Lainnya</option>
          </select>
        </div>

        <button type="submit" id="btnSubmitReg" class="reg-btn-submit" <?= !empty($isSoldOut) ? 'disabled style="background:#cbd5e1; color:#64748b; cursor:not-allowed;"' : '' ?>>
          <?= !empty($isSoldOut) ? 'Tiket Sudah Habis (Sold Out)' : 'Kirim Pendaftaran' ?>
        </button>
        </fieldset>
      </form>

    </div>

    <div style="text-align: center;">
      <a href="<?= base_url('/') ?>" class="reg-back-link">&larr; Kembali ke Beranda</a>
    </div>

  </div>
</div>

<!-- MODAL REGISTRASI BERHASIL -->
<div id="successModal" style="display: none; position: fixed; inset: 0; background: rgba(0, 0, 0, 0.65); z-index: 9999; justify-content: center; align-items: center; padding: 20px; box-sizing: border-box;">
  <div style="background: #ffffff; width: 100%; max-width: 480px; border-radius: 16px; overflow: hidden; box-shadow: 0 20px 40px rgba(0,0,0,0.25); text-align: center; animation: modalIn 0.25s ease;">
    <!-- Modal Header Kuning EMF -->
    <div style="background: var(--yellow-6, #f4d849); padding: 24px 20px; border-bottom: 2px solid var(--yellow-4, #f8e68c);">
      <div style="font-size: 11px; font-weight: 800; letter-spacing: 0.12em; text-transform: uppercase; color: var(--green-9, #2a571a); margin-bottom: 4px;">
        REGISTRASI BERHASIL
      </div>
      <h3 style="margin: 0; font-size: 20px; font-weight: 800; color: var(--green-10, #214415);">
        Energi & Mineral Festival 2026
      </h3>
    </div>

    <!-- Modal Body -->
    <div style="padding: 32px 24px; color: #1e293b;">
      <div style="width: 56px; height: 56px; background: #edf5ea; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px auto; color: var(--green-6, #49982e); font-size: 28px; font-weight: 800;">
        &check;
      </div>

      <h4 style="margin: 0 0 10px 0; font-size: 18px; font-weight: 800; color: var(--green-10, #214415);">
        Terima Kasih atas Registrasi Anda!
      </h4>
      
      <p style="margin: 0 0 24px 0; font-size: 14px; color: #475569; line-height: 1.6;">
        Pendaftaran kunjungan telah berhasil diproses. Informasi lengkap beserta <strong>Barcode & Kode Tiket</strong> telah kami kirimkan ke email Anda:<br>
        <strong id="modalEmail" style="color: var(--green-8, #346c21); display: inline-block; margin-top: 4px;">email@example.com</strong>
      </p>

      <p style="margin: 0 0 24px 0; font-size: 12.5px; color: #94a3b8; line-height: 1.5;">
        *Silakan periksa kotak masuk (Inbox) atau folder Spam/Promotions pada email Anda.
      </p>

      <!-- Action Buttons -->
      <div style="display: flex; flex-direction: column; gap: 10px;">
        <a href="<?= base_url('/') ?>" class="reg-btn-submit" style="display: block; text-align: center; text-decoration: none; margin: 0; padding: 13px 18px; font-size: 14.5px; font-weight: 800;">
          Selesai & Ke Beranda
        </a>
        <button type="button" id="btnRegisterAgain" style="background: transparent; border: 1.5px solid var(--green-6, #49982e); color: var(--green-9, #2a571a); border-radius: 10px; padding: 11px 18px; font-size: 13.5px; font-weight: 700; cursor: pointer; font-family: inherit; transition: all 0.2s ease;">
          + Pesan Tiket Lagi
        </button>
      </div>
    </div>
  </div>
</div>

<!-- MODAL LOADING ANIMASI (REGISTRASI BERLANGSUNG) -->
<div id="loadingModal" style="display: none; position: fixed; inset: 0; background: rgba(10, 25, 15, 0.78); backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); z-index: 99999; justify-content: center; align-items: center; padding: 20px; box-sizing: border-box;">
  <div style="background: #ffffff; width: 100%; max-width: 420px; border-radius: 20px; overflow: hidden; box-shadow: 0 25px 60px rgba(0,0,0,0.35); text-align: center; animation: modalIn 0.3s cubic-bezier(0.16, 1, 0.3, 1);">
    <!-- Decorative top ribbon with animated gradient -->
    <div style="height: 6px; background: linear-gradient(90deg, var(--green-6, #49982e), var(--yellow-6, #f4d849), var(--green-6, #49982e)); background-size: 200% 100%; animation: gradientShift 2s linear infinite;"></div>
    
    <div style="padding: 38px 28px 32px 28px;">
      <!-- Glowing Animated Spinner & Icon -->
      <div style="position: relative; width: 84px; height: 84px; margin: 0 auto 22px auto; display: flex; align-items: center; justify-content: center;">
        <div class="emf-spinner-ring"></div>
        <div class="emf-spinner-core">
          <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" style="color: var(--green-7, #3e8127);">
            <path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2z"></path>
            <path d="M13 5v2"></path>
            <path d="M13 17v2"></path>
            <path d="M13 11v2"></path>
          </svg>
        </div>
      </div>

      <h3 id="loadingModalTitle" style="margin: 0 0 10px 0; font-size: 19px; font-weight: 800; color: var(--green-10, #214415); letter-spacing: -0.01em;">
        Memproses Pendaftaran...
      </h3>

      <p id="loadingModalDesc" style="margin: 0 0 22px 0; font-size: 13.5px; color: #64748b; line-height: 1.55; min-height: 42px;">
        Sistem sedang mengamankan kuota tiket dan menyiapkan e-badge digital Anda...
      </p>

      <!-- Animated Progress Bar -->
      <div style="width: 100%; height: 6px; background: #f1f5f9; border-radius: 999px; overflow: hidden; position: relative; margin-bottom: 14px;">
        <div class="emf-loading-bar-fill"></div>
      </div>

      <div style="font-size: 11.5px; font-weight: 600; color: #94a3b8; letter-spacing: 0.04em; text-transform: uppercase;">
        Mohon jangan tutup atau refresh halaman ini
      </div>
    </div>
  </div>
</div>

<!-- MODAL KONFIRMASI DUPLIKASI EMAIL -->
<div id="duplicateConfirmModal" style="display: none; position: fixed; inset: 0; background: rgba(0, 0, 0, 0.65); backdrop-filter: blur(5px); z-index: 10000; justify-content: center; align-items: center; padding: 20px; box-sizing: border-box;">
  <div style="background: #ffffff; width: 100%; max-width: 460px; border-radius: 18px; overflow: hidden; box-shadow: 0 24px 48px rgba(0,0,0,0.25); text-align: center; animation: modalIn 0.25s ease;">
    <!-- Modal Header Kuning EMF -->
    <div style="background: var(--yellow-6, #f4d849); padding: 18px 20px; border-bottom: 2px solid var(--yellow-4, #f8e68c);">
      <div style="font-size: 11px; font-weight: 800; letter-spacing: 0.12em; text-transform: uppercase; color: var(--green-9, #2a571a); margin-bottom: 2px;">
        KONFIRMASI EMAIL TERDAFTAR
      </div>
      <h3 style="margin: 0; font-size: 18px; font-weight: 800; color: var(--green-10, #214415);">
        Energi & Mineral Festival 2026
      </h3>
    </div>

    <!-- Modal Body -->
    <div style="padding: 28px 24px; color: #1e293b;">
      <div style="width: 56px; height: 56px; background: #fef3c7; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px auto; color: #d97706; font-size: 26px; font-weight: 800;">
        &#9888;
      </div>

      <h4 style="margin: 0 0 10px 0; font-size: 17px; font-weight: 800; color: #92400e;">
        Email Sudah Pernah Terdaftar
      </h4>
      
      <p id="duplicateConfirmDesc" style="margin: 0 0 24px 0; font-size: 13.5px; color: #475569; line-height: 1.6;">
        Alamat email ini sudah terdaftar pada sistem kami. Apakah Anda yakin ingin melakukan pendaftaran tiket tambahan?
      </p>

      <!-- Action Buttons (Batal vs Ya, Lanjutkan) -->
      <div style="display: flex; gap: 10px;">
        <button type="button" id="btnCancelDuplicate" style="flex: 1; background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; border-radius: 10px; padding: 12px 14px; font-size: 13.5px; font-weight: 700; cursor: pointer; font-family: inherit;">
          Batal / Periksa
        </button>
        <button type="button" id="btnConfirmDuplicate" style="flex: 1.3; background: var(--green-6, #49982e); color: #ffffff; border: none; border-radius: 10px; padding: 12px 14px; font-size: 13.5px; font-weight: 800; cursor: pointer; font-family: inherit; transition: background 0.2s ease;">
          Ya, Lanjutkan
        </button>
      </div>
    </div>
  </div>
</div>

<!-- MODAL PERINGATAN / ERROR (PENGGANTI ALERT BROWSER) -->
<div id="errorModal" style="display: none; position: fixed; inset: 0; background: rgba(0, 0, 0, 0.65); backdrop-filter: blur(4px); z-index: 9999; justify-content: center; align-items: center; padding: 20px; box-sizing: border-box;">
  <div style="background: #ffffff; width: 100%; max-width: 440px; border-radius: 16px; overflow: hidden; box-shadow: 0 20px 40px rgba(0,0,0,0.25); text-align: center; animation: modalIn 0.25s ease;">
    <!-- Modal Header Kuning EMF -->
    <div style="background: var(--yellow-6, #f4d849); padding: 20px 20px; border-bottom: 2px solid var(--yellow-4, #f8e68c);">
      <div style="font-size: 11px; font-weight: 800; letter-spacing: 0.12em; text-transform: uppercase; color: var(--green-9, #2a571a); margin-bottom: 2px;">
        PEMBERITAHUAN TIKET
      </div>
      <h3 style="margin: 0; font-size: 18px; font-weight: 800; color: var(--green-10, #214415);">
        Energi & Mineral Festival 2026
      </h3>
    </div>

    <!-- Modal Body -->
    <div style="padding: 28px 24px; color: #1e293b;">
      <div style="width: 52px; height: 52px; background: #fee2e2; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 14px auto; color: #dc2626; font-size: 26px; font-weight: 800;">
        &times;
      </div>

      <h4 id="errorModalTitle" style="margin: 0 0 10px 0; font-size: 17px; font-weight: 800; color: #991b1b;">
        Kuota Tiket Tidak Mencukupi
      </h4>
      
      <p id="errorModalDesc" style="margin: 0 0 24px 0; font-size: 14px; color: #475569; line-height: 1.6;">
        Pesan error detail...
      </p>

      <!-- Action Button -->
      <button type="button" id="btnDismissError" style="width: 100%; background: var(--green-6, #49982e); color: #ffffff; border: none; border-radius: 10px; padding: 12px 18px; font-size: 14.5px; font-weight: 800; cursor: pointer; font-family: inherit; transition: background 0.2s ease;">
        Tutup & Periksa Kembali
      </button>
    </div>
  </div>
</div>

<style>
@keyframes modalIn {
  from { opacity: 0; transform: translateY(12px) scale(0.97); }
  to { opacity: 1; transform: translateY(0) scale(1); }
}

@keyframes emfSpin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

@keyframes gradientShift {
  0% { background-position: 0% 50%; }
  50% { background-position: 100% 50%; }
  100% { background-position: 0% 50%; }
}

@keyframes emfPulse {
  0%, 100% { transform: scale(1); opacity: 1; }
  50% { transform: scale(1.08); opacity: 0.88; }
}

@keyframes loadingBarAnim {
  0% { transform: translateX(-100%); }
  50% { transform: translateX(30%); }
  100% { transform: translateX(200%); }
}

.emf-spinner-ring {
  position: absolute;
  inset: 0;
  border-radius: 50%;
  border: 4px solid #e2e8f0;
  border-top-color: var(--green-6, #49982e);
  border-right-color: var(--yellow-6, #f4d849);
  animation: emfSpin 0.9s cubic-bezier(0.55, 0.15, 0.45, 0.85) infinite;
}

.emf-spinner-core {
  width: 52px;
  height: 52px;
  background: #f0fdf4;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  box-shadow: 0 4px 14px rgba(73, 152, 46, 0.18);
  animation: emfPulse 1.8s ease-in-out infinite;
}

.emf-loading-bar-fill {
  width: 50%;
  height: 100%;
  background: linear-gradient(90deg, var(--green-6, #49982e), var(--yellow-6, #f4d849));
  border-radius: 999px;
  animation: loadingBarAnim 1.6s ease-in-out infinite;
}
</style>

<script>
function lockScroll() {
  document.body.style.overflow = 'hidden';
  document.documentElement.style.overflow = 'hidden';
}

function unlockScroll() {
  document.body.style.overflow = '';
  document.documentElement.style.overflow = '';
}

function showErrorModal(title, message) {
  lockScroll();
  document.getElementById('errorModalTitle').textContent = title || 'Pemberitahuan';
  document.getElementById('errorModalDesc').textContent = message || 'Terjadi kendala saat memproses pendaftaran.';
  document.getElementById('errorModal').style.display = 'flex';
}

document.getElementById('btnDismissError').addEventListener('click', function() {
  document.getElementById('errorModal').style.display = 'none';
  unlockScroll();
});

let loadingInterval = null;

function showLoadingModal() {
  lockScroll();
  const modal = document.getElementById('loadingModal');
  const title = document.getElementById('loadingModalTitle');
  const desc = document.getElementById('loadingModalDesc');
  
  title.textContent = 'Memproses Pendaftaran...';
  desc.textContent = 'Sistem sedang mengamankan kuota tiket dan menyiapkan e-badge digital Anda...';
  modal.style.display = 'flex';

  const messages = [
    { title: 'Mengamankan Tiket...', desc: 'Memvalidasi ketersediaan kuota dan data peserta...' },
    { title: 'Menyiapkan E-Badge...', desc: 'Membangun QR Code dan digital badge resmi Anda...' },
    { title: 'Mengirimkan Konfirmasi...', desc: 'Menghubungkan ke server email untuk mengirimkan tiket masuk...' }
  ];

  let step = 0;
  clearInterval(loadingInterval);
  loadingInterval = setInterval(() => {
    step = (step + 1) % messages.length;
    title.textContent = messages[step].title;
    desc.textContent = messages[step].desc;
  }, 2200);
}

function hideLoadingModal() {
  clearInterval(loadingInterval);
  document.getElementById('loadingModal').style.display = 'none';
}

// Format nomor telepon dengan strip (-): misal 812-3456-7890
function formatPhoneChunks(digits, dialCode) {
  if (!digits) return '';
  const pattern = (dialCode === '62') ? [3, 4, 4] : [3, 3, 4];
  const chunks = [];
  let cursor = 0;
  for (const len of pattern) {
    if (cursor >= digits.length) break;
    chunks.push(digits.substring(cursor, cursor + len));
    cursor += len;
  }
  if (cursor < digits.length) {
    chunks.push(digits.substring(cursor));
  }
  return chunks.join('-');
}

// Sinkronisasi dan format otomatis nomor telepon dengan strip (-) saat diketik
function handlePhoneInput(e) {
  const dial = document.getElementById('country_dial').value;
  const input = document.getElementById('phone_number');
  let raw = (input.value || '').trim();
  
  // Ambil hanya digit
  let digits = raw.replace(/[^0-9]/g, '');
  if (digits.startsWith('0')) {
    digits = digits.replace(/^0+/, '');
  }

  // Batasi panjang digit wajar (maksimal 14 digit)
  if (digits.length > 14) {
    digits = digits.substring(0, 14);
  }

  // Format dengan strip (-)
  const formatted = formatPhoneChunks(digits, dial);
  input.value = formatted;

  // Set nilai murni (dial + digits tanpa strip) ke hidden input untuk backend
  document.getElementById('phone').value = digits ? (dial + digits) : '';
}

function syncFullPhoneNumber() {
  handlePhoneInput();
}

document.getElementById('country_dial').addEventListener('change', handlePhoneInput);
document.getElementById('phone_number').addEventListener('input', handlePhoneInput);

// ==========================================
// 1. Tab Switch Kategori Pengunjung (Pribadi vs Company)
// ==========================================
const tabPribadi = document.getElementById('tabPribadi');
const tabCompany = document.getElementById('tabCompany');
const regTypeInput = document.getElementById('registration_type');
const picSection = document.getElementById('picSectionWrapper');
const footnoteText = document.getElementById('regTypeFootnoteText');
const sourceInfoTitle = document.getElementById('sourceInfoSectionTitle');

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
    // Update nomor seksi berikutnya menjadi 5
    if (sourceInfoTitle) {
      sourceInfoTitle.textContent = '5. Informasi Tambahan';
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
    // Update nomor seksi berikutnya menjadi 4 (karena seksi PIC ditiadakan)
    if (sourceInfoTitle) {
      sourceInfoTitle.textContent = '4. Informasi Tambahan';
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

// ==========================================
// 2. Dynamic Field Nama Tambahan jika Jumlah Tiket > 1
// ==========================================
const ticketQtySelect = document.getElementById('ticket_quantity');
const addPartWrapper = document.getElementById('additionalParticipantsWrapper');

function updateAdditionalNames() {
  if (!ticketQtySelect || !addPartWrapper) return;
  const qty = parseInt(ticketQtySelect.value) || 1;

  if (qty <= 1) {
    addPartWrapper.style.display = 'none';
    addPartWrapper.innerHTML = '';
    return;
  }

  // Simpan nilai input yang sudah sempat diketik sebelumnya agar tidak hilang saat dropdown diganti
  const currentInputs = Array.from(addPartWrapper.querySelectorAll('.additional-name-input'));
  const existingValues = currentInputs.map(inp => inp.value);

  let html = `
    <div class="additional-names-box">
      <div style="display: flex; align-items: flex-start; gap: 10px; margin-bottom: 14px;">
        <div style="width: 28px; height: 28px; border-radius: 50%; background: #e8f5e9; color: var(--green-7, #3e8127); display: flex; align-items: center; justify-content: center; flex-shrink: 0; margin-top: 2px;">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
            <circle cx="9" cy="7" r="4"></circle>
            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
          </svg>
        </div>
        <div>
          <div style="font-size: 13.5px; font-weight: 800; color: var(--green-10, #214415); line-height: 1.3;">
            Data Pemegang Tiket Tambahan (${qty - 1} Orang)
          </div>
          <div style="font-size: 12px; color: #64748b; margin-top: 2px;">
            Tiket #1 atas nama pemesan utama di atas. Masukkan nama lengkap untuk tiket lainnya:
          </div>
        </div>
      </div>
      <div style="display: flex; flex-direction: column; gap: 12px;">
  `;

  for (let i = 2; i <= qty; i++) {
    const prevVal = existingValues[i - 2] || '';
    html += `
      <div class="reg-form-group" style="margin-bottom: 0;">
        <label class="reg-label" for="additional_name_${i}">
          Nama Lengkap Pemegang Tiket #${i} <span class="req">*</span>
        </label>
        <input type="text" id="additional_name_${i}" name="additional_names[]" class="reg-input additional-name-input" required minlength="3" placeholder="Contoh: Nama Lengkap Tiket #${i}" value="${prevVal.replace(/"/g, '&quot;')}">
        <div class="reg-hint-text" style="font-size: 11px; color: #64748b; margin-top: 3px;">Nama lengkap peserta untuk verifikasi e-badge.</div>
      </div>
    `;
  }

  html += `
      </div>
    </div>
  `;

  addPartWrapper.innerHTML = html;
  addPartWrapper.style.display = 'block';
}

if (ticketQtySelect) {
  ticketQtySelect.addEventListener('change', updateAdditionalNames);
}

// Inisialisasi awal
updateAdditionalNames();

let isForceDuplicate = false;

document.getElementById('btnCancelDuplicate').addEventListener('click', function() {
  document.getElementById('duplicateConfirmModal').style.display = 'none';
  unlockScroll();
  const emailInput = document.getElementById('email');
  if (emailInput) {
    emailInput.focus();
    emailInput.select();
  }
});

document.getElementById('btnConfirmDuplicate').addEventListener('click', function() {
  document.getElementById('duplicateConfirmModal').style.display = 'none';
  unlockScroll();
  isForceDuplicate = true;
  document.getElementById('regForm').dispatchEvent(new Event('submit', { cancelable: true }));
});

document.getElementById('regForm').addEventListener('submit', async function(e) {
  e.preventDefault();
  
  syncFullPhoneNumber();
  const phoneVal = document.getElementById('phone').value;
  if (!phoneVal) {
    showErrorModal('Nomor Telepon Diperlukan', 'Mohon masukkan nomor telepon/WhatsApp Anda.');
    return;
  }

  // Validasi Nama Tambahan jika Tiket > 1
  const additionalInputs = Array.from(document.querySelectorAll('.additional-name-input'));
  for (let idx = 0; idx < additionalInputs.length; idx++) {
    const val = (additionalInputs[idx].value || '').trim();
    if (val.length < 3) {
      showErrorModal(
        'Nama Peserta Tambahan Diperlukan',
        `Mohon lengkapi nama lengkap pemegang tiket #${idx + 2} (minimal 3 karakter).`
      );
      additionalInputs[idx].focus();
      return;
    }
  }

  // Validasi Kategori Company: PIC wajib lengkap
  const currentRegType = regTypeInput ? regTypeInput.value : 'personal';
  if (currentRegType === 'company') {
    const picNameVal = (document.getElementById('pic_name')?.value || '').trim();
    const picPhoneVal = (document.getElementById('pic_phone')?.value || '').trim();
    const companyEmailVal = (document.getElementById('company_email')?.value || '').trim();

    if (!picNameVal || !picPhoneVal || !companyEmailVal) {
      showErrorModal(
        'Data PIC Perusahaan Belum Lengkap',
        'Untuk kategori Instansi / Perusahaan, kolom Nama PIC, No. Telp PIC, dan Email Perusahaan wajib diisi.'
      );
      return;
    }

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(companyEmailVal)) {
      showErrorModal(
        'Format Email Perusahaan Tidak Valid',
        'Mohon masukkan format alamat email perusahaan yang valid (contoh: corporate@perusahaan.co.id).'
      );
      document.getElementById('company_email')?.focus();
      return;
    }
  }

  const btn = document.getElementById('btnSubmitReg');
  const errorBox = document.querySelector('.reg-alert-error');
  if (errorBox) errorBox.style.display = 'none';

  btn.disabled = true;
  btn.textContent = 'Memproses Pendaftaran...';
  showLoadingModal();

  const formData = new FormData(this);
  if (isForceDuplicate) {
    formData.set('force_duplicate', '1');
    isForceDuplicate = false; // reset
  }

  try {
    const res = await fetch("<?= base_url('register/submit') ?>", {
      method: 'POST',
      headers: {
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
      },
      body: formData
    });

    const json = await res.json();
    hideLoadingModal();

    if (res.ok && json.success) {
      // Tampilkan Modal Berhasil
      lockScroll();
      document.getElementById('modalEmail').textContent = json.participant?.email || formData.get('email');

      // Update sisa kuota dan dropdown jumlah tiket secara realtime
      const remainingQuota = (typeof json.remaining_quota !== 'undefined') ? parseInt(json.remaining_quota) : null;
      const isSoldOut = Boolean(json.is_sold_out || (remainingQuota !== null && remainingQuota <= 0));

      if (isSoldOut) {
        const fieldset = document.querySelector('#regForm fieldset');
        if (fieldset) fieldset.disabled = true;
        btn.disabled = true;
        btn.textContent = 'Pendaftaran Ditutup (Sold Out)';
        btn.style.background = '#94a3b8';
      } else if (remainingQuota !== null) {
        const quotaBadge = document.getElementById('remainingQuotaBadge');
        if (quotaBadge) {
          if (remainingQuota > 0) {
            quotaBadge.textContent = `(Sisa: ${remainingQuota} tiket)`;
            quotaBadge.style.display = 'inline';
          } else {
            quotaBadge.style.display = 'none';
          }
        }

        const qtySelect = document.getElementById('ticket_quantity');
        if (qtySelect) {
          qtySelect.innerHTML = '';
          const maxSelectable = Math.min(5, Math.max(1, remainingQuota));
          for (let q = 1; q <= maxSelectable; q++) {
            const opt = document.createElement('option');
            opt.value = q;
            opt.textContent = `${q} Tiket`;
            if (q === 1) opt.selected = true;
            qtySelect.appendChild(opt);
          }
        }
      }

      const modal = document.getElementById('successModal');
      modal.style.display = 'flex';
      
      // Reset form
      document.getElementById('regForm').reset();
      document.getElementById('phone').value = '';
      setRegistrationType('personal');
      updateAdditionalNames();
      btn.disabled = false;
      btn.textContent = 'Kirim Pendaftaran';
    } else if (res.status === 409 && json.requires_confirmation) {
      // Modal Interaktif Konfirmasi Duplikasi Email
      lockScroll();
      document.getElementById('duplicateConfirmDesc').textContent = json.message || 'Alamat email ini sudah terdaftar sebelumnya. Apakah Anda yakin ingin melakukan pendaftaran tiket tambahan dengan email ini?';
      document.getElementById('duplicateConfirmModal').style.display = 'flex';
      btn.disabled = false;
      btn.textContent = 'Kirim Pendaftaran';
    } else {
      // Kuota habis atau error lainnya
      const isQuotaError = (res.status === 422 && json.message && json.message.toLowerCase().includes('kuota'));
      const modalTitle = isQuotaError ? 'Kuota Tiket Habis' : 'Pendaftaran Belum Berhasil';
      
      if (typeof json.remaining_quota !== 'undefined' || json.is_sold_out) {
        const rQuota = parseInt(json.remaining_quota || 0);
        if (rQuota <= 0 || json.is_sold_out) {
          const fieldset = document.querySelector('#regForm fieldset');
          if (fieldset) fieldset.disabled = true;
          btn.disabled = true;
          btn.textContent = 'Pendaftaran Ditutup (Sold Out)';
          btn.style.background = '#94a3b8';
          const badge = document.getElementById('remainingQuotaBadge');
          if (badge) badge.style.display = 'none';
        } else {
          const badge = document.getElementById('remainingQuotaBadge');
          if (badge) {
            badge.textContent = `(Sisa: ${rQuota} tiket)`;
            badge.style.display = 'inline';
          }
          const qtySelect = document.getElementById('ticket_quantity');
          if (qtySelect) {
            qtySelect.innerHTML = '';
            for (let q = 1; q <= Math.min(5, rQuota); q++) {
              const opt = document.createElement('option');
              opt.value = q;
              opt.textContent = `${q} Tiket`;
              qtySelect.appendChild(opt);
            }
          }
        }
      }

      showErrorModal(modalTitle, json.message || 'Pendaftaran gagal diproses. Silakan periksa kembali data Anda.');
      btn.disabled = false;
      btn.textContent = 'Kirim Pendaftaran';
    }
  } catch (err) {
    hideLoadingModal();
    showErrorModal('Koneksi Terputus', 'Gagal menghubungi server. Silakan periksa jaringan internet Anda.');
    btn.disabled = false;
    btn.textContent = 'Kirim Pendaftaran';
  }
});

// Event Pesan Tiket Lagi: Tutup modal dan fokus ke input nama
document.getElementById('btnRegisterAgain').addEventListener('click', function() {
  document.getElementById('successModal').style.display = 'none';
  unlockScroll();
  window.scrollTo({ top: 0, behavior: 'smooth' });
  const nameInput = document.getElementById('full_name');
  if (nameInput) nameInput.focus();
});
</script>

</body>
</html>
