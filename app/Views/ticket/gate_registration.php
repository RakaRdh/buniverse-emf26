<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registrasi OTS Pengunjung — <?= esc($site['title'] ?? 'Energi & Mineral Festival 2026') ?></title>
  
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

    .terminal-container {
      width: 100%;
      max-width: 900px;
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
      margin-bottom: 24px;
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
    }

    .gate-tab-link:not(.active) {
      background: #f1f5f9;
      color: var(--green-10, #214415);
    }

    .gate-tab-link:not(.active):hover {
      background: #e2e8f0;
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
  </style>
</head>
<body>

<div class="terminal-wrapper">
  <div class="terminal-container">

    <!-- TOP NAVIGATION BAR -->
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
          <span>Scanner & Presensi</span>
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
              <option value="day_1">Hari 1 — Selasa, 10 Nov 2026</option>
              <option value="day_2">Hari 2 — Rabu, 11 Nov 2026</option>
              <option value="all_days" selected>2-Day Pass (Semua Hari)</option>
            </select>
          </div>
          <div class="reg-form-group">
            <label class="reg-label" for="address">Alamat Domisili Tamu <span class="req">*</span></label>
            <input type="text" id="address" name="address" class="reg-input" required placeholder="Kota domisili atau alamat kantor..." value="Jakarta">
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

otsForm.addEventListener('submit', async function(e) {
  e.preventDefault();
  otsErrorBox.style.display = 'none';

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
  document.getElementById('address').value = 'Jakarta';
  document.getElementById('full_name').focus();
});
</script>

</body>
</html>
