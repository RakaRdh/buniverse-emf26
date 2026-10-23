<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registrasi Pengunjung — <?= esc($site['title'] ?? 'Energi & Mineral Festival 2026') ?></title>
  
  <link rel="shortcut icon" type="image/x-icon" href="<?= base_url('favicon.ico') ?>?v=<?= file_exists(FCPATH . 'favicon.ico') ? filemtime(FCPATH . 'favicon.ico') : time() ?>">
  <link rel="icon" type="image/x-icon" href="<?= base_url('favicon.ico') ?>?v=<?= file_exists(FCPATH . 'favicon.ico') ? filemtime(FCPATH . 'favicon.ico') : time() ?>">
  
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@300;400;500;600;700;800&family=Playfair+Display:ital,wght@0,700;0,800;0,900;1,700&display=swap" rel="stylesheet">
  
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/css/tailwind.css') ?>">

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
      background-color: var(--yellow-6, #f4d849) !important;
      color: var(--green-10, #214415);
      font-family: 'Manrope', sans-serif;
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
      text-align: center;
      margin-bottom: 32px;
    }

    .reg-logo {
      max-height: 52px;
      margin-bottom: 20px;
    }

    .reg-title {
      font-size: 26px;
      font-weight: 800;
      color: var(--green-9, #2a571a);
      margin: 0 0 8px 0;
      letter-spacing: -0.01em;
    }

    .reg-subtitle {
      font-size: 14px;
      color: var(--green-8, #346c21);
      margin: 0;
      line-height: 1.5;
      font-weight: 500;
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
  </style>
</head>
<body>

<div class="reg-wrapper">
  <div class="reg-container">
    
    <div class="reg-header">
      <a href="<?= base_url('/') ?>">
        <img src="<?= base_url('assets/img/logo_green_emf26.webp') ?>" alt="<?= esc($site['title'] ?? 'Energi & Mineral Festival 2026') ?>" class="reg-logo">
      </a>
      <h1 class="reg-title">Formulir Registrasi Pengunjung</h1>
      <p class="reg-subtitle"><?= esc($site['title'] ?? 'Energi & Mineral Festival 2026') ?> &bull; <?= esc($site['dates'] ?? '10 - 11 November 2026') ?><br><?= esc($site['venue'] ?? 'Jakarta') ?></p>
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

        <!-- SEKSI 1: DATA DIRI -->
        <div class="reg-section-title">1. Data Diri & Kontak</div>

        <div class="reg-form-group">
          <label class="reg-label" for="full_name">Nama Lengkap <span class="req">*</span></label>
          <input type="text" id="full_name" name="full_name" class="reg-input" required placeholder="Contoh: Budi Santoso" value="<?= old('full_name') ?>">
        </div>

        <div class="reg-grid-2">
          <div class="reg-form-group">
            <label class="reg-label" for="email">Email Aktif <span class="req">*</span></label>
            <input type="email" id="email" name="email" class="reg-input" required placeholder="budi@example.com" value="<?= old('email') ?>">
          </div>
          <div class="reg-form-group">
            <label class="reg-label" for="phone">Nomor Telepon / WhatsApp <span class="req">*</span></label>
            <input type="tel" id="phone" name="phone" class="reg-input" required placeholder="081234567890" value="<?= old('phone') ?>">
          </div>
        </div>

        <!-- SEKSI 2: JADWAL KUNJUNGAN & JUMLAH TIKET -->
        <div class="reg-section-title" style="margin-top: 28px;">2. Pilihan Hari & Jumlah Tiket</div>

        <div class="reg-grid-2">
          <div class="reg-form-group">
            <label class="reg-label" for="visit_day">Hari Kunjungan <span class="req">*</span></label>
            <select id="visit_day" name="visit_day" class="reg-select" required>
              <option value="day_1">Hari 1 — Selasa, 10 Nov 2026</option>
              <option value="day_2">Hari 2 — Rabu, 11 Nov 2026</option>
              <option value="all_days">Semua Hari (10 - 11 Nov 2026)</option>
            </select>
          </div>
          <div class="reg-form-group">
            <label class="reg-label" for="ticket_quantity">
              Jumlah Tiket <span class="req">*</span>
              <span id="remainingQuotaBadge" style="font-size: 11.5px; font-weight: 700; color: #dc2626; margin-left: 4px; <?= (!empty($totalQuota) && $remainingQuota > 0) ? '' : 'display: none;' ?>">
                (Sisa: <?= (int)($remainingQuota ?? 0) ?> tiket)
              </span>
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

        <!-- SEKSI 4: PIC (OPSIONAL) -->
        <div class="reg-section-title" style="margin-top: 28px;">4. Kontak Narahubung / PIC (Opsional)</div>

        <div class="reg-form-group">
          <label class="reg-label" for="pic_name">Nama PIC / Pendamping</label>
          <input type="text" id="pic_name" name="pic_name" class="reg-input" placeholder="Nama narahubung jika didaftarkan pihak lain" value="<?= old('pic_name') ?>">
        </div>

        <div class="reg-grid-2">
          <div class="reg-form-group">
            <label class="reg-label" for="pic_email">Email PIC</label>
            <input type="email" id="pic_email" name="pic_email" class="reg-input" placeholder="pic@example.com" value="<?= old('pic_email') ?>">
          </div>
          <div class="reg-form-group">
            <label class="reg-label" for="pic_phone">No. Telp / WhatsApp PIC</label>
            <input type="tel" id="pic_phone" name="pic_phone" class="reg-input" placeholder="081987654321" value="<?= old('pic_phone') ?>">
          </div>
        </div>

        <!-- SEKSI 5: SUMBER INFORMASI (OPSIONAL) -->
        <div class="reg-section-title" style="margin-top: 28px;">5. Informasi Tambahan</div>

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

document.getElementById('regForm').addEventListener('submit', async function(e) {
  e.preventDefault();
  
  const btn = document.getElementById('btnSubmitReg');
  const errorBox = document.querySelector('.reg-alert-error');
  if (errorBox) errorBox.style.display = 'none';

  btn.disabled = true;
  btn.textContent = 'Memproses Pendaftaran...';
  showLoadingModal();

  const formData = new FormData(this);

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
      // Tampilkan Modal Berhasil (scroll tetap terkunci agar fokus di modal)
      lockScroll();
      document.getElementById('modalEmail').textContent = json.participant?.email || formData.get('email');

      // Update sisa kuota dan dropdown jumlah tiket secara realtime
      const remainingQuota = (typeof json.remaining_quota !== 'undefined') ? parseInt(json.remaining_quota) : null;
      const isSoldOut = Boolean(json.is_sold_out || (remainingQuota !== null && remainingQuota <= 0));

      if (isSoldOut) {
        // Jika kuota pas habis setelah pemesanan ini
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
      btn.disabled = false;
      btn.textContent = 'Kirim Pendaftaran';
    } else {
      const modalTitle = (res.status === 422 && json.message && json.message.toLowerCase().includes('kuota'))
        ? 'Kuota Tiket Habis'
        : 'Pendaftaran Belum Berhasil';
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
