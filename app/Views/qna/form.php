<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Q&A Session — <?= esc($sessionTitle) ?> | <?= esc($site['title'] ?? 'Investor Daily Summit 2026') ?></title>
  
  <!-- Favicon -->
  <link rel="shortcut icon" type="image/x-icon" href="<?= base_url('favicon.ico') ?>?v=<?= file_exists(FCPATH . 'favicon.ico') ? filemtime(FCPATH . 'favicon.ico') : time() ?>">
  <link rel="icon" type="image/x-icon" href="<?= base_url('favicon.ico') ?>?v=<?= file_exists(FCPATH . 'favicon.ico') ? filemtime(FCPATH . 'favicon.ico') : time() ?>">
  
  <!-- Google Fonts: Manrope & Playfair Display -->
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
    /* QnA Form Specific Layout */
    .qna-hero-wrapper {
      min-height: 100vh;
      min-height: 100dvh;
      position: relative;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 40px 20px;
      overflow: hidden;
      background-color: var(--primary-1, #010A2D);
    }

    .qna-card-container {
      position: relative;
      z-index: 10;
      width: 100%;
      max-width: 540px;
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
      filter: drop-shadow(0 4px 12px rgba(0, 0, 0, 0.4));
      margin-bottom: 12px;
    }

    .qna-session-badge {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 6px 14px;
      border-radius: 999px;
      background: rgba(201, 148, 0, 0.12);
      border: 1px solid var(--primary-2-5);
      color: var(--primary-2-4);
      font-size: 11.5px;
      font-weight: 700;
      letter-spacing: 0.08em;
      text-transform: uppercase;
      margin-bottom: 8px;
    }

    .qna-session-title {
      font-size: 20px;
      font-weight: 800;
      color: #ffffff;
      line-height: 1.35;
      margin: 0 0 4px 0;
      text-shadow: 0 2px 8px rgba(0,0,0,0.5);
    }

    .qna-session-subtitle {
      font-size: 13.5px;
      font-weight: 600;
      color: #5e9cbf;
      line-height: 1.4;
      margin: 4px 0 8px 0;
      text-shadow: 0 1px 4px rgba(0,0,0,0.4);
    }

    .qna-session-time {
      font-size: 13px;
      color: rgba(255, 255, 255, 0.7);
      font-weight: 500;
    }

    .qna-form-box {
      background: rgba(12, 47, 136, 0.28);
      backdrop-filter: blur(20px);
      -webkit-backdrop-filter: blur(20px);
      border: 1px solid rgba(255, 255, 255, 0.12);
      border-radius: 20px;
      padding: 28px 24px;
      box-shadow: 0 16px 40px rgba(0, 0, 0, 0.4), inset 0 1px 0 rgba(255, 255, 255, 0.1);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .qna-field-group {
      margin-bottom: 20px;
      text-align: left;
    }

    .qna-field-label {
      display: block;
      font-size: 13px;
      font-weight: 700;
      color: rgba(255, 255, 255, 0.9);
      margin-bottom: 8px;
      letter-spacing: 0.02em;
    }

    .qna-field-label span {
      color: var(--primary-2-4);
    }

    .qna-input, .qna-textarea {
      width: 100%;
      background: rgba(1, 10, 45, 0.6);
      border: 1.5px solid rgba(255, 255, 255, 0.16);
      border-radius: 12px;
      padding: 14px 16px;
      font-size: 14px;
      color: #ffffff;
      font-family: inherit;
      outline: none;
      transition: all 0.2s ease;
      box-sizing: border-box;
    }

    .qna-input:focus, .qna-textarea:focus {
      border-color: var(--primary-2-4);
      background: rgba(1, 10, 45, 0.85);
      /* box-shadow: 0 0 0 3px var(--primary-2-5); */
    }

    .qna-input::placeholder, .qna-textarea::placeholder {
      color: rgba(255, 255, 255, 0.35);
    }

    .qna-textarea {
      resize: vertical;
      min-height: 200px;
      line-height: 1.5;
    }

    .qna-char-counter {
      display: flex;
      justify-content: flex-end;
      font-size: 11px;
      color: rgba(255, 255, 255, 0.45);
      margin-top: 6px;
    }

    .qna-btn-submit {
      width: 100%;
      padding: 14px 20px;
      background: var(--primary-2-4);
      color: #010a2d;
      font-size: 15px;
      font-weight: 800;
      border: none;
      border-radius: 12px;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
      /* box-shadow: 0 6px 20px var(--primary-2-5); */
      transition: all 0.2s ease;
      letter-spacing: 0.03em;
    }

    .qna-btn-submit:hover {
      transform: translateY(-2px);
      /* box-shadow: 0 8px 25px var(--primary-2-5); */
      /* filter: brightness(1.08); */
    }

    .qna-btn-submit:active {
      transform: translateY(0);
    }

    .qna-btn-submit:disabled {
      opacity: 0.6;
      cursor: not-allowed;
      transform: none;
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
      background: rgba(34, 197, 94, 0.15);
      border: 2px solid #22c55e;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 18px;
      color: #4ade80;
      box-shadow: 0 0 25px rgba(34, 197, 94, 0.3);
    }

    .qna-success-title {
      font-size: 22px;
      font-weight: 800;
      color: #ffffff;
      margin-bottom: 8px;
    }

    .qna-success-desc {
      font-size: 14px;
      color: rgba(255, 255, 255, 0.75);
      line-height: 1.5;
      margin-bottom: 24px;
    }

    .qna-btn-secondary {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      padding: 12px 24px;
      border-radius: 10px;
      background: rgba(255, 255, 255, 0.08);
      border: 1px solid rgba(255, 255, 255, 0.2);
      color: #ffffff;
      font-size: 14px;
      font-weight: 700;
      cursor: pointer;
      transition: all 0.2s;
    }

    .qna-btn-secondary:hover {
      background: rgba(255, 255, 255, 0.16);
      border-color: rgba(255, 255, 255, 0.4);
    }

    .qna-footer-note {
      text-align: center;
      margin-top: 20px;
      font-size: 12px;
      color: rgba(255, 255, 255, 0.4);
    }
  </style>
</head>
<body>

  <div class="qna-hero-wrapper">
    <?php /* 1. Pure CSS Hero Background Canvas (Base #010A2D with ambient royal blue glow #0C2F88 at top-left) */ ?>
    <div class="hero-bg-canvas" aria-hidden="true"></div>

    <?php /* 2. Hero Decorative Discs (Identical positioning & animation to Hero section) */ ?>
    <div class="hero-deco-wrap" aria-hidden="true">
      <?php /* Deco A: Top Left Subtle Accent */ ?>
      <div class="hero-deco-item hero-deco-topleft">
        <img src="<?= base_url('assets/img/decoration.png') ?>" alt="" class="hero-deco-img">
      </div>

      <?php /* Deco B: Center-Right Perspective 3D Spinning Disc wrapped with static Luminance mask */ ?>
      <div class="hero-deco-main-mask">
        <div class="hero-deco-item hero-deco-main-perspective">
          <div class="hero-deco-tilt">
            <div class="hero-deco-spin">
              <img src="<?= base_url('assets/img/decoration.png') ?>" alt="" class="hero-deco-img">
            </div>
          </div>
        </div>
      </div>
    </div>

    <?php /* 3. Main Centered Interactive QnA Container */ ?>
    <div class="qna-card-container">
      
      <?php /* Brand Header */ ?>
      <div class="qna-brand-header">
        <a href="<?= base_url('/') ?>"><img src="<?= base_url('assets/img/logo_theme.webp') ?>" alt="<?= esc($site['title'] ?? 'Investor Daily Summit') ?>" class="qna-logo-img"></a>
        <?php if (!empty($sessionNotFound)): ?>
          <span class="qna-session-badge" style="background: rgba(239, 68, 68, 0.12); border-color: rgba(239, 68, 68, 0.35); color: #f87171;">
            404 • Sesi Tidak Ditemukan
          </span>
          <h1 class="qna-session-title"><?= esc($sessionTitle) ?></h1>
          <?php if (!empty($sessionSubtitle)): ?>
            <div class="qna-session-subtitle" style="color: #94a3b8;"><?= esc($sessionSubtitle) ?></div>
          <?php endif; ?>
        <?php else: ?>
          <span class="qna-session-badge">
            <?= !empty($roomName) ? esc($roomName) : (!empty($dayNumber) ? 'Day ' . esc($dayNumber) : esc($dayLabel ?? 'Q&A Session')) ?>
          </span>
          <h1 class="qna-session-title"><?= esc($sessionTitle) ?></h1>
          <?php if (!empty($sessionSubtitle)): ?>
            <div class="qna-session-subtitle"><?= esc($sessionSubtitle) ?></div>
          <?php endif; ?>
          <?php if (!empty($sessionTime)): ?>
            <div class="qna-session-time"><?= esc($sessionTime) ?></div>
          <?php endif; ?>
        <?php endif; ?>
      </div>

      <?php /* Form Card or Not Found State */ ?>
      <div class="qna-form-box" id="qnaFormCard">
        <?php if (!empty($sessionNotFound)): ?>
          <?php /* Invalid / Not Found Route State (Form is completely hidden) */ ?>
          <div class="qna-not-found-box" style="text-align: center; padding: 24px 12px;">
            <div class="qna-not-found-icon" style="width: 72px; height: 72px; background: rgba(239, 68, 68, 0.12); border: 2px solid rgba(239, 68, 68, 0.35); border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin: 0 auto 20px auto; color: #f87171; box-shadow: 0 0 28px rgba(239, 68, 68, 0.2);">
              <svg viewBox="0 0 24 24" width="34" height="34" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="8" x2="12" y2="12"></line>
                <line x1="12" y1="16" x2="12.01" y2="16"></line>
              </svg>
            </div>
            <h2 style="font-size: 21px; font-weight: 800; color: #ffffff; margin: 0 0 10px 0; letter-spacing: -0.01em;">Sesi Tidak Ditemukan</h2>
            <p style="font-size: 14px; color: rgba(255, 255, 255, 0.72); line-height: 1.6; margin: 0 auto 28px auto; max-width: 420px;">
              Tautan sesi tanya jawab yang Anda buka tidak valid, telah berakhir, atau tidak sesuai dengan hari kegiatan yang dipilih.
            </p>
            <?php /*
            <div style="display: flex; flex-direction: column; gap: 12px;">
              <a href="<?= base_url('/#schedule') ?>" class="qna-btn-submit" style="text-decoration: none; justify-content: center;">
                <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                  <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                  <line x1="16" y1="2" x2="16" y2="6"></line>
                  <line x1="8" y1="2" x2="8" y2="6"></line>
                  <line x1="3" y1="10" x2="21" y2="10"></line>
                </svg>
                <span>Lihat Jadwal Sesi Lengkap</span>
              </a>
              <a href="<?= base_url('/') ?>" class="qna-btn-secondary" style="text-decoration: none; justify-content: center;">
                <span>Kembali ke Beranda Utama</span>
              </a>
            </div>
            */ ?>
          </div>
        <?php else: ?>
          <?php /* Active QnA Form */ ?>
          <form id="qnaForm" method="POST" action="<?= base_url('qna/submit') ?>">
            <?= csrf_field() ?>
            
            <?php if (!empty($sessionId)): ?>
              <?php /* Hidden IDs dynamically extracted from URL/Barcode */ ?>
              <input type="hidden" name="schedule_day_id" value="<?= esc($dayId) ?>">
              <input type="hidden" name="schedule_session_id" value="<?= esc($sessionId) ?>">
              <?php if (!empty($itemId)): ?>
                <input type="hidden" name="session_item_id" value="<?= esc($itemId) ?>">
              <?php endif; ?>
            <?php else: ?>
              <?php /* Session Selector when no specific session barcode is scanned */ ?>
              <div class="qna-field-group">
                <label class="qna-field-label" for="sessionSelect">Pilih Sesi Acara <span>*</span></label>
                <select id="sessionSelect" name="schedule_session_id" class="qna-input" style="background: rgba(1, 10, 45, 0.95); cursor: pointer;" required>
                  <option value="">-- Pilih Sesi Tanya Jawab --</option>
                  <?php if (!empty($allDays)): ?>
                    <?php foreach ($allDays as $d): ?>
                      <?php if (!empty($d['sessions'])): ?>
                        <optgroup label="Day <?= esc($d['day_number']) ?><?= !empty($d['formatted_date']) ? ' (' . esc($d['formatted_date']) . ')' : (!empty($d['event_date']) ? ' (' . date('F j, Y', strtotime($d['event_date'])) . ')' : '') ?>">
                          <?php foreach ($d['sessions'] as $s): ?>
                            <option value="<?= $s['id'] ?><?= !empty($s['item_id']) ? ':' . $s['item_id'] : '' ?>">
                              [<?= esc($s['start_time']) ?>] <?= esc($s['title']) ?><?= !empty($s['subtitle']) ? ' — ' . esc($s['subtitle']) : '' ?>
                            </option>
                          <?php endforeach; ?>
                        </optgroup>
                      <?php endif; ?>
                    <?php endforeach; ?>
                  <?php endif; ?>
                </select>
              </div>
            <?php endif; ?>

            <?php /* Nama Penanya */ ?>
            <div class="qna-field-group">
              <label class="qna-field-label" for="askerName">Nama Penanya <span>*</span></label>
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

            <?php /* Pertanyaan */ ?>
            <div class="qna-field-group">
              <label class="qna-field-label" for="questionText">Pertanyaan Anda <span>*</span></label>
              <textarea 
                id="questionText" 
                name="question" 
                class="qna-textarea" 
                placeholder="Tuliskan pertanyaan Anda untuk pembicara sesi ini..." 
                required 
                maxlength="2000"
              ></textarea>
              <div class="qna-char-counter">
                <span id="charCount">0</span> / 2000 karakter
              </div>
            </div>

            <?php /* Error Message Notice */ ?>
            <div id="qnaErrorNotice" style="display: none; background: rgba(239, 68, 68, 0.15); border: 1px solid rgba(239, 68, 68, 0.4); color: #fca5a5; font-size: 13px; padding: 10px 14px; border-radius: 8px; margin-bottom: 16px;"></div>

            <?php /* Submit Button */ ?>
            <button type="submit" id="btnSubmitQna" class="qna-btn-submit">
              <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
              <span>Kirim Pertanyaan</span>
            </button>
          </form>

          <?php /* Success State View (Revealed after submit) */ ?>
          <div class="qna-success-box" id="qnaSuccessBox">
            <div class="qna-success-icon">
              <svg viewBox="0 0 24 24" width="32" height="32" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
            </div>
            <h2 class="qna-success-title">Pertanyaan Terkirim!</h2>
            <p class="qna-success-desc">Terima kasih atas partisipasi Anda. Pertanyaan Anda telah masuk dan akan dibacakan oleh moderator sesi.</p>
            <button type="button" id="btnAskAnother" class="qna-btn-secondary">
              + Ajukan Pertanyaan Lain
            </button>
          </div>
        <?php endif; ?>
      </div>

      <div class="qna-footer-note">
        &copy; <?= date('Y') ?> <?= esc($site['title'] ?? 'Investor Daily Summit') ?>. All rights reserved.
      </div>

    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const form = document.getElementById('qnaForm');
      const formCard = document.getElementById('qnaFormCard');
      const successBox = document.getElementById('qnaSuccessBox');
      const questionText = document.getElementById('questionText');
      const charCount = document.getElementById('charCount');
      const btnSubmit = document.getElementById('btnSubmitQna');
      const btnAskAnother = document.getElementById('btnAskAnother');
      const errorNotice = document.getElementById('qnaErrorNotice');

      // Character counter
      if (questionText && charCount) {
        questionText.addEventListener('input', () => {
          charCount.textContent = questionText.value.length;
        });
      }

      // AJAX Submit Handling for seamless experience
      if (form) {
        form.addEventListener('submit', async (e) => {
          e.preventDefault();
          errorNotice.style.display = 'none';

          const askerNameVal = document.getElementById('askerName').value.trim();
          const questionVal = questionText.value.trim();

          if (!askerNameVal || !questionVal) {
            errorNotice.textContent = 'Mohon isi nama dan pertanyaan Anda.';
            errorNotice.style.display = 'block';
            return;
          }

          // Disable button
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
              // Show success state
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

      // Reset to ask another question
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
