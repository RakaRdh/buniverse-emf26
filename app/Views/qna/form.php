<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Q&A Session — <?= esc($sessionTitle) ?> | <?= esc($site['title'] ?? 'Energy & Mineral Festival 2026') ?></title>
  
  <!-- Favicon -->
  <link rel="shortcut icon" type="image/x-icon" href="<?= base_url('favicon.ico') ?>?v=<?= file_exists(FCPATH . 'favicon.ico') ? filemtime(FCPATH . 'favicon.ico') : time() ?>">
  <link rel="icon" type="image/x-icon" href="<?= base_url('favicon.ico') ?>?v=<?= file_exists(FCPATH . 'favicon.ico') ? filemtime(FCPATH . 'favicon.ico') : time() ?>">
  
  <!-- Google Fonts: Manrope -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@300;400;500;600;700;800&family=Playfair+Display:ital,wght@0,700;0,800;0,900;1,700&display=swap" rel="stylesheet">
  
  <!-- Core Vanilla CSS & Styling -->
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
    /* ==========================================================================
       EMF QNA FORM — PALET RESMI REGISTER (SOLID YELLOW #F4D849 & FOREST GREEN)
       ========================================================================== */
    html, body {
      background-color: var(--yellow-6, #f4d849) !important;
      color: var(--green-10, #214415);
      font-family: 'Manrope', -apple-system, BlinkMacSystemFont, sans-serif;
      margin: 0;
      padding: 0;
      min-height: 100%;
      overflow-x: hidden;
    }

    .qna-hero-wrapper {
      min-height: 100vh;
      min-height: 100dvh;
      position: relative;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: flex-start;
      padding: 48px 20px 80px 20px;
      box-sizing: border-box;
      background-color: var(--yellow-6, #f4d849);
      overflow: hidden;
    }

    /* --------------------------------------------------------------------------
       RIBBON DECORATIVE ASSETS (POJOK KIRI BAWAH & POJOK KANAN ATAS)
       -------------------------------------------------------------------------- */
    .qna-ribbon-deco {
      position: absolute;
      pointer-events: none;
      z-index: 1;
      width: clamp(260px, 28vw, 380px);
      height: clamp(260px, 28vw, 380px);
      overflow: hidden;
    }

    .qna-ribbon-deco-left {
      bottom: 0;
      left: 0;
      display: block;
    }

    .qna-ribbon-deco-right {
      top: 0;
      right: 0;
      display: block;
    }

    /* Posisi gambar ribbon berputar: hanya 1/4 bagian yang visible dari tepi pojok */
    .qna-ribbon-deco-left img {
      position: absolute;
      bottom: -50%;
      left: -50%;
      width: 200%;
      height: 200%;
      object-fit: contain;
      opacity: 0.9;
      animation: qnaRibbonSpin 32s linear infinite;
    }

    .qna-ribbon-deco-right img {
      position: absolute;
      top: -50%;
      right: -50%;
      width: 200%;
      height: 200%;
      object-fit: contain;
      opacity: 0.9;
      animation: qnaRibbonSpinReverse 32s linear infinite;
    }

    @keyframes qnaRibbonSpin {
      from { transform: rotate(0deg); }
      to   { transform: rotate(360deg); }
    }

    @keyframes qnaRibbonSpinReverse {
      from { transform: rotate(360deg); }
      to   { transform: rotate(0deg); }
    }

    /* Mobile: hanya pojok kanan atas yang tampil, pojok kiri atas di-hide */
    @media (max-width: 768px) {
      .qna-ribbon-deco-left {
        display: none !important;
      }
      .qna-ribbon-deco-right {
        width: 190px;
        height: 190px;
      }
      .qna-ribbon-deco-right img {
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
      }
      .qna-hero-wrapper {
        padding: 36px 16px 60px 16px;
      }
    }

    /* --------------------------------------------------------------------------
       CONTAINER & HEADER
       -------------------------------------------------------------------------- */
    .qna-card-container {
      position: relative;
      z-index: 10;
      width: 100%;
      max-width: 580px;
      margin: 0 auto;
    }

    .qna-brand-header {
      text-align: center;
      margin-bottom: 24px;
    }

    .qna-logo-img {
      max-height: 52px;
      width: auto;
      object-fit: contain;
      margin: 0 auto 18px auto;
      display: inline-block;
    }

    .qna-session-badge {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 6px 16px;
      border-radius: 999px;
      background: rgba(255, 255, 255, 0.92);
      border: 1.5px solid rgba(73, 152, 46, 0.4);
      color: var(--green-9, #2a571a);
      font-size: 12.5px;
      font-weight: 800;
      letter-spacing: 0.03em;
      text-transform: none;
      box-shadow: 0 4px 12px rgba(33, 68, 21, 0.06);
      margin-bottom: 12px;
    }

    .qna-session-title {
      font-size: 13px;
      font-weight: 800;
      text-transform: uppercase;
      letter-spacing: 0.06em;
      color: var(--green-7, #3e8127);
      line-height: 1.4;
      margin: 0 0 6px 0;
    }

    .qna-session-subtitle {
      font-size: 20px;
      font-weight: 800;
      color: var(--green-10, #214415);
      line-height: 1.35;
      margin: 4px 0 8px 0;
      letter-spacing: -0.01em;
    }

    .qna-session-time {
      font-size: 13.5px;
      color: var(--green-8, #346c21);
      font-weight: 600;
    }

    /* --------------------------------------------------------------------------
       FORM CARD (PUTIH BERSIH PERSIS FORM REGISTER)
       -------------------------------------------------------------------------- */
    .qna-form-box {
      background: #ffffff;
      border: 1px solid rgba(73, 152, 46, 0.25);
      box-shadow: 0 10px 30px rgba(33, 68, 21, 0.08);
      border-radius: 18px;
      padding: 32px 28px;
      box-sizing: border-box;
      transition: box-shadow 0.2s ease;
    }

    @media (max-width: 600px) {
      .qna-form-box {
        padding: 24px 20px;
      }
    }

    .qna-field-group {
      margin-bottom: 20px;
      text-align: left;
    }

    .qna-field-label {
      display: block;
      font-size: 13px;
      font-weight: 700;
      color: var(--green-10, #214415);
      margin-bottom: 8px;
    }

    .qna-field-label span.req {
      color: #dc2626;
      margin-left: 2px;
    }

    .qna-input, .qna-textarea {
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

    .qna-input:focus, .qna-textarea:focus {
      border-color: var(--green-6, #49982e);
      box-shadow: 0 0 0 3px rgba(73, 152, 46, 0.15);
    }

    .qna-input::placeholder, .qna-textarea::placeholder {
      color: #94a3b8;
    }

    .qna-textarea {
      resize: vertical;
      min-height: 160px;
      line-height: 1.5;
    }

    .qna-char-counter {
      display: flex;
      justify-content: flex-end;
      font-size: 11.5px;
      color: #64748b;
      margin-top: 6px;
      font-weight: 500;
    }

    .qna-btn-submit {
      width: 100%;
      padding: 14px 20px;
      background: var(--green-6, #49982e);
      color: #ffffff;
      font-size: 15px;
      font-weight: 700;
      border: none;
      border-radius: 10px;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
      transition: background 0.2s ease, transform 0.1s ease;
      letter-spacing: 0.01em;
      margin-top: 10px;
    }

    .qna-btn-submit:hover:not(:disabled) {
      background: var(--green-7, #3e8127);
    }

    .qna-btn-submit:active:not(:disabled) {
      transform: translateY(1px);
    }

    .qna-btn-submit:disabled {
      opacity: 0.6;
      cursor: not-allowed;
    }

    /* Success State Card */
    .qna-success-box {
      display: none;
      text-align: center;
      padding: 32px 20px;
    }

    .qna-success-icon {
      width: 64px;
      height: 64px;
      background: rgba(73, 152, 46, 0.12);
      border: 2px solid var(--green-6, #49982e);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 18px;
      color: var(--green-7, #3e8127);
    }

    .qna-success-title {
      font-size: 22px;
      font-weight: 800;
      color: var(--green-10, #214415);
      margin-bottom: 8px;
    }

    .qna-success-desc {
      font-size: 14px;
      color: var(--green-8, #346c21);
      line-height: 1.5;
      margin-bottom: 24px;
    }

    .qna-btn-secondary {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      padding: 12px 24px;
      border-radius: 10px;
      background: #f1f5f9;
      border: 1px solid #cbd5e1;
      color: var(--green-10, #214415);
      font-size: 14px;
      font-weight: 700;
      cursor: pointer;
      transition: all 0.2s;
    }

    .qna-btn-secondary:hover {
      background: #e2e8f0;
      border-color: #94a3b8;
    }

    .qna-footer-note {
      text-align: center;
      margin-top: 24px;
      font-size: 12.5px;
      font-weight: 600;
      color: var(--green-9, #2a571a);
    }
  </style>
</head>
<body>

  <div class="qna-hero-wrapper">
    
    <?php /* 1. Ribbon Dekoratif Pojok Kiri Atas (Desktop) & Pojok Kanan Atas (Desktop + Mobile) */ ?>
    <div class="qna-ribbon-deco qna-ribbon-deco-left" aria-hidden="true">
      <img src="<?= base_url('assets/img/ribbon.webp') ?>" alt="">
    </div>
    <div class="qna-ribbon-deco qna-ribbon-deco-right" aria-hidden="true">
      <img src="<?= base_url('assets/img/ribbon.webp') ?>" alt="">
    </div>

    <?php /* 2. Main Centered Interactive QnA Container */ ?>
    <div class="qna-card-container">
      
      <?php /* Brand Header: Pakai logo resmi EMF (logo_green_emf26.webp) */ ?>
      <div class="qna-brand-header">
        <a href="<?= base_url('/') ?>">
          <img src="<?= base_url('assets/img/logo_green_emf26.webp') ?>" alt="<?= esc($site['title'] ?? 'Energy & Mineral Festival 2026') ?>" class="qna-logo-img">
        </a>
        
        <?php if (!empty($sessionNotFound)): ?>
          <div>
            <span class="qna-session-badge" style="background: #fef2f2; border-color: #f87171; color: #b91c1c;">
              404 • Sesi Tidak Ditemukan
            </span>
            <h1 class="qna-session-title"><?= esc($sessionTitle) ?></h1>
            <?php if (!empty($sessionSubtitle)): ?>
              <div class="qna-session-subtitle" style="color: #64748b;"><?= esc($sessionSubtitle) ?></div>
            <?php endif; ?>
          </div>
        <?php else: ?>
          <div>
            <span class="qna-session-badge">
              <?= !empty($roomName) ? esc($roomName) : (!empty($dayNumber) ? 'Day ' . esc($dayNumber) : esc($dayLabel ?? 'Q&A Session')) ?>
            </span>
            <?php if (!empty($sessionSubtitle)): ?>
              <div class="qna-session-title"><?= esc($sessionTitle) ?></div>
              <h1 class="qna-session-subtitle"><?= esc($sessionSubtitle) ?></h1>
            <?php else: ?>
              <h1 class="qna-session-subtitle"><?= esc($sessionTitle) ?></h1>
            <?php endif; ?>
            <?php if (!empty($sessionTime)): ?>
              <div class="qna-session-time"><?= esc($sessionTime) ?></div>
            <?php endif; ?>
          </div>
        <?php endif; ?>
      </div>

      <?php /* Form Card or Not Found State */ ?>
      <div class="qna-form-box" id="qnaFormCard">
        <?php if (!empty($sessionNotFound)): ?>
          <div style="text-align: center; padding: 20px 10px;">
            <div style="width: 64px; height: 64px; background: #fee2e2; border: 2px solid #ef4444; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin: 0 auto 16px auto; color: #dc2626;">
              <svg viewBox="0 0 24 24" width="30" height="30" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="8" x2="12" y2="12"></line>
                <line x1="12" y1="16" x2="12.01" y2="16"></line>
              </svg>
            </div>
            <h2 style="font-size: 20px; font-weight: 800; color: #991b1b; margin: 0 0 8px 0;">Sesi Tidak Ditemukan</h2>
            <p style="font-size: 14px; color: #475569; line-height: 1.5; margin: 0 auto 24px auto;">
              Tautan sesi tanya jawab yang Anda buka tidak valid atau tidak sesuai dengan hari kegiatan.
            </p>
            <a href="<?= base_url('/#schedule') ?>" class="qna-btn-secondary" style="text-decoration: none;">
              Kembali ke Jadwal
            </a>
          </div>
        <?php else: ?>
          <?php /* Active QnA Form */ ?>
          <form id="qnaForm" method="POST" action="<?= base_url('qna/submit') ?>">
            <?= csrf_field() ?>
            
            <?php if (!empty($sessionId)): ?>
              <input type="hidden" name="schedule_day_id" value="<?= esc($dayId) ?>">
              <input type="hidden" name="schedule_session_id" value="<?= esc($sessionId) ?>">
              <?php if (!empty($itemId)): ?>
                <input type="hidden" name="session_item_id" value="<?= esc($itemId) ?>">
              <?php endif; ?>
            <?php else: ?>
              <?php /* Manual Session Selector Fallback */ ?>
              <div class="qna-field-group">
                <label for="sessionSelect" class="qna-field-label">Pilih Sesi Acara <span class="req">*</span></label>
                <select name="schedule_session_id" id="sessionSelect" class="qna-input" required>
                  <option value="" disabled selected>-- Pilih Sesi Pertanyaan --</option>
                  <?php if (!empty($allDays)): ?>
                    <?php foreach ($allDays as $d): ?>
                      <?php if (!empty($d['sessions'])): ?>
                        <optgroup label="Day <?= esc($d['day_number']) ?> &mdash; <?= esc($d['label']) ?>">
                          <?php foreach ($d['sessions'] as $s): ?>
                            <?php 
                              $optionVal = $s['id'] . (!empty($s['item_id']) ? ':' . $s['item_id'] : '');
                              $optionLabel = '[' . esc($s['start_time']) . '] ' . esc($s['title']) . (!empty($s['subtitle']) ? ' - ' . esc($s['subtitle']) : '');
                            ?>
                            <option value="<?= $optionVal ?>"><?= $optionLabel ?></option>
                          <?php endforeach; ?>
                        </optgroup>
                      <?php endif; ?>
                    <?php endforeach; ?>
                  <?php endif; ?>
                </select>
              </div>
            <?php endif; ?>

            <?php /* Field 1: Asker Name */ ?>
            <div class="qna-field-group">
              <label for="askerName" class="qna-field-label">Nama Lengkap <span class="req">*</span></label>
              <input 
                type="text" 
                id="askerName" 
                name="asker_name" 
                class="qna-input" 
                placeholder="Ketik nama lengkap Anda..." 
                required 
                maxlength="150"
                autocomplete="name"
              >
            </div>

            <?php /* Field 2: Question Body */ ?>
            <div class="qna-field-group">
              <label for="questionText" class="qna-field-label">Pertanyaan Anda <span class="req">*</span></label>
              <textarea 
                id="questionText" 
                name="question" 
                class="qna-textarea" 
                placeholder="Tuliskan pertanyaan Anda untuk pembicara sesi ini..." 
                required 
                maxlength="2000"
              ></textarea>
              <div class="qna-char-counter">
                <span id="charCount">0</span>/2000 karakter (min. 5 karakter)
              </div>
            </div>

            <?php /* Error Notice */ ?>
            <div id="qnaErrorNotice" style="display: none; background: #fef2f2; border: 1px solid #f87171; color: #b91c1c; border-radius: 8px; padding: 12px 14px; font-size: 13.5px; margin-bottom: 16px;"></div>

            <?php /* Submit Button */ ?>
            <button type="submit" id="btnSubmitQna" class="qna-btn-submit">
              <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="22" y1="2" x2="11" y2="13"></line>
                <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
              </svg>
              <span>Kirim Pertanyaan</span>
            </button>
          </form>

          <?php /* Success Box State */ ?>
          <div class="qna-success-box" id="qnaSuccessBox">
            <div class="qna-success-icon">
              <svg viewBox="0 0 24 24" width="36" height="36" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                <polyline points="22 4 12 14.01 9 11.01"></polyline>
              </svg>
            </div>
            <h2 class="qna-success-title">Pertanyaan Terkirim!</h2>
            <p class="qna-success-desc">
              Terima kasih. Pertanyaan Anda telah berhasil kami terima dan akan ditampilkan kepada moderator pada sesi ini.
            </p>
            <button type="button" id="btnAskAnother" class="qna-btn-secondary">
              Kirim Pertanyaan Lain
            </button>
          </div>
        <?php endif; ?>
      </div>

      <?php /* Footer Copyright */ ?>
      <div class="qna-footer-note">
        &copy; <?= date('Y') ?> <?= esc($site['title'] ?? 'Energy & Mineral Festival 2026') ?>. All rights reserved.
      </div>
    </div>
  </div>

  <?php /* Interactive Client Script */ ?>
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const form = document.getElementById('qnaForm');
      const questionText = document.getElementById('questionText');
      const charCount = document.getElementById('charCount');
      const btnSubmit = document.getElementById('btnSubmitQna');
      const errorNotice = document.getElementById('qnaErrorNotice');
      const successBox = document.getElementById('qnaSuccessBox');
      const btnAskAnother = document.getElementById('btnAskAnother');

      // Character Counter
      if (questionText && charCount) {
        questionText.addEventListener('input', () => {
          const len = questionText.value.length;
          charCount.textContent = len;
          if (len > 1950) {
            charCount.style.color = '#ef4444';
          } else {
            charCount.style.color = '#64748b';
          }
        });
      }

      // AJAX Submission
      if (form) {
        form.addEventListener('submit', async (e) => {
          e.preventDefault();
          errorNotice.style.display = 'none';

          const asker = document.getElementById('askerName').value.trim();
          const qText = questionText.value.trim();

          if (asker.length < 5) {
            errorNotice.textContent = 'Nama lengkap minimal 5 karakter.';
            errorNotice.style.display = 'block';
            return;
          }

          if (qText.length < 5) {
            errorNotice.textContent = 'Pertanyaan minimal 5 karakter.';
            errorNotice.style.display = 'block';
            return;
          }

          btnSubmit.disabled = true;
          const originalBtnHtml = btnSubmit.innerHTML;
          btnSubmit.innerHTML = '<span>Mengirim...</span>';

          try {
            const formData = new FormData(form);
            const response = await fetch(form.action, {
              method: 'POST',
              body: formData,
              headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
              }
            });

            const result = await response.json();

            if (response.ok && result.success) {
              form.style.display = 'none';
              successBox.style.display = 'block';
              questionText.value = '';
              charCount.textContent = '0';
            } else {
              errorNotice.textContent = result.message || 'Gagal mengirim pertanyaan. Silakan coba lagi.';
              errorNotice.style.display = 'block';
            }
          } catch (err) {
            errorNotice.textContent = 'Terjadi kesalahan koneksi. Pastikan internet Anda aktif lalu coba lagi.';
            errorNotice.style.display = 'block';
          } finally {
            btnSubmit.disabled = false;
            btnSubmit.innerHTML = originalBtnHtml;
          }
        });
      }

      if (btnAskAnother) {
        btnAskAnother.addEventListener('click', () => {
          successBox.style.display = 'none';
          form.style.display = 'block';
          errorNotice.style.display = 'none';
          questionText.focus();
        });
      }
    });
  </script>
</body>
</html>
