<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Live Q&A Dashboard | <?= esc($site['title'] ?? 'Investor Daily Summit 2026') ?></title>
  
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
    body {
      background-color: var(--primary-1, #010A2D);
      color: #ffffff;
      min-height: 100vh;
      overflow-x: hidden;
      font-family: 'Manrope', -apple-system, BlinkMacSystemFont, sans-serif;
    }

    .qna-dash-wrapper {
      position: relative;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      padding: 30px 24px 60px;
    }

    .qna-dash-container {
      position: relative;
      z-index: 10;
      width: 100%;
      max-width: 1200px;
      margin: 0 auto;
    }

    /* Top Navigation Bar */
    .qna-dash-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
      gap: 20px;
      margin-bottom: 28px;
      padding-bottom: 20px;
      border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .qna-dash-brand {
      display: flex;
      align-items: center;
      gap: 16px;
    }

    .qna-dash-logo {
      height: 48px;
      width: auto;
      object-fit: contain;
    }

    .qna-dash-title {
      font-size: 24px;
      font-weight: 800;
      color: #ffffff;
      margin: 0;
      line-height: 1.2;
    }

    .qna-dash-subtitle {
      font-size: 13px;
      color: rgba(255, 255, 255, 0.6);
      margin: 2px 0 0 0;
    }

    .qna-live-pill {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 6px 14px;
      border-radius: 999px;
      background: rgba(34, 197, 94, 0.12);
      border: 1px solid rgba(34, 197, 94, 0.35);
      color: #4ade80;
      font-size: 12px;
      font-weight: 700;
    }

    .qna-live-dot {
      width: 8px;
      height: 8px;
      background-color: #22c55e;
      border-radius: 50%;
      box-shadow: 0 0 10px #22c55e;
      animation: pulseLive 1.5s infinite ease-in-out;
    }

    @keyframes pulseLive {
      0%, 100% { transform: scale(1); opacity: 1; }
      50% { transform: scale(1.4); opacity: 0.6; }
    }

    /* Filters Section */
    .qna-filter-bar {
      background: rgba(12, 47, 136, 0.25);
      backdrop-filter: blur(16px);
      -webkit-backdrop-filter: blur(16px);
      border: 1px solid rgba(255, 255, 255, 0.1);
      border-radius: 16px;
      padding: 16px 20px;
      display: flex;
      flex-wrap: wrap;
      align-items: center;
      justify-content: space-between;
      gap: 16px;
      margin-bottom: 28px;
      box-shadow: 0 8px 30px rgba(0, 0, 0, 0.25);
    }

    .qna-filter-group {
      display: flex;
      align-items: center;
      flex-wrap: wrap;
      gap: 10px;
    }

    .qna-filter-label {
      font-size: 12px;
      font-weight: 700;
      color: rgba(255, 255, 255, 0.6);
      text-transform: uppercase;
      letter-spacing: 0.06em;
    }

    .qna-select {
      background: rgba(1, 10, 45, 0.7);
      border: 1px solid rgba(255, 255, 255, 0.18);
      color: #ffffff;
      padding: 8px 14px;
      border-radius: 10px;
      font-size: 13.5px;
      font-weight: 600;
      outline: none;
      min-width: 180px;
      cursor: pointer;
      transition: all 0.2s;
    }

    .qna-select:focus {
      border-color: #c99400;
      box-shadow: 0 0 0 2px rgba(201, 148, 0, 0.25);
    }

    .qna-select option {
      background: #010a2d;
      color: #ffffff;
    }

    /* Questions Grid */
    .qna-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(360px, 1fr));
      gap: 20px;
    }

    @media (max-width: 640px) {
      .qna-grid {
        grid-template-columns: 1fr;
      }
    }

    .qna-card {
      background: rgba(12, 47, 136, 0.22);
      backdrop-filter: blur(14px);
      -webkit-backdrop-filter: blur(14px);
      border: 1px solid rgba(255, 255, 255, 0.1);
      border-radius: 18px;
      padding: 22px 24px;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      gap: 16px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
      transition: transform 0.2s ease, border-color 0.2s ease, box-shadow 0.2s ease;
      position: relative;
      overflow: hidden;
    }

    .qna-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 3px;
      background: linear-gradient(90deg, #c99400, #ffd24d, transparent);
      opacity: 0.8;
    }

    .qna-card:hover {
      transform: translateY(-3px);
      border-color: rgba(201, 148, 0, 0.4);
      box-shadow: 0 14px 35px rgba(0, 0, 0, 0.45);
    }

    .qna-card-meta {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      gap: 10px;
    }

    .qna-asker-name {
      font-size: 16px;
      font-weight: 800;
      color: #ffffff;
      line-height: 1.3;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .qna-asker-avatar {
      width: 32px;
      height: 32px;
      border-radius: 50%;
      background: linear-gradient(135deg, #c99400, #e5a900);
      color: #010a2d;
      font-weight: 800;
      font-size: 13px;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
    }

    .qna-session-tag {
      font-size: 11px;
      padding: 3px 8px;
      border-radius: 6px;
      background: rgba(201, 148, 0, 0.12);
      border: 1px solid rgba(201, 148, 0, 0.3);
      color: #ffd24d;
      font-weight: 700;
      white-space: nowrap;
    }

    .qna-question-body {
      font-size: 15px;
      line-height: 1.6;
      color: rgba(255, 255, 255, 0.95);
      white-space: pre-line;
      word-break: break-word;
      font-weight: 400;
    }

    .qna-card-footer {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding-top: 12px;
      border-top: 1px solid rgba(255, 255, 255, 0.08);
      font-size: 12px;
      color: rgba(255, 255, 255, 0.5);
    }

    .qna-empty-state {
      text-align: center;
      padding: 80px 20px;
      background: rgba(12, 47, 136, 0.15);
      border-radius: 20px;
      border: 1px dashed rgba(255, 255, 255, 0.15);
      grid-column: 1 / -1;
    }

    .qna-empty-icon {
      width: 56px;
      height: 56px;
      margin: 0 auto 16px;
      color: rgba(255, 255, 255, 0.3);
    }

    .qna-empty-title {
      font-size: 18px;
      font-weight: 700;
      color: #ffffff;
      margin-bottom: 6px;
    }

    .qna-empty-desc {
      font-size: 13.5px;
      color: rgba(255, 255, 255, 0.6);
      max-width: 420px;
      margin: 0 auto;
    }
  </style>
</head>
<body>

  <div class="qna-dash-wrapper">
    <!-- 1. Pure CSS Hero Canvas Ambient Glow Background -->
    <div class="hero-bg-canvas" aria-hidden="true"></div>

    <!-- 2. Decorative Rotating Discs -->
    <div class="hero-deco-wrap" aria-hidden="true" style="opacity: 0.6;">
      <div class="hero-deco-item hero-deco-topleft">
        <img src="<?= base_url('assets/img/decoration.png') ?>" alt="" class="hero-deco-img">
      </div>
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

    <!-- 3. Main Dashboard Content -->
    <div class="qna-dash-container">
      
      <!-- Top Brand Bar -->
      <div class="qna-dash-header">
        <div class="qna-dash-brand">
          <img src="<?= base_url('assets/img/hero.webp') ?>" alt="Logo" class="qna-dash-logo">
          <div>
            <h1 class="qna-dash-title">Q&A Live Dashboard</h1>
            <p class="qna-dash-subtitle"><?= esc($site['title'] ?? 'Investor Daily Summit 2026') ?> &bull; Forum Pertanyaan Sesi</p>
          </div>
        </div>

        <div style="display: flex; align-items: center; gap: 14px;">
          <div class="qna-live-pill">
            <span class="qna-live-dot"></span>
            <span>Live Sync</span>
          </div>
          <div style="font-size: 13px; font-weight: 700; color: #ffd24d;">
            <span id="totalQuestionsCount"><?= count($questions) ?></span> Pertanyaan
          </div>
        </div>
      </div>

      <!-- Filters Bar -->
      <div class="qna-filter-bar">
        <div class="qna-filter-group">
          <span class="qna-filter-label">Filter:</span>
          
          <!-- Day Filter -->
          <select id="filterDay" class="qna-select">
            <option value="">Semua Hari (All Days)</option>
            <?php if (!empty($days)): ?>
              <?php foreach ($days as $d): ?>
                <option value="<?= $d['day_id'] ?>" <?= (!empty($filterDay) && $filterDay == $d['day_id']) ? 'selected' : '' ?>>
                  Day <?= esc($d['day_number']) ?> &mdash; <?= esc($d['label']) ?>
                </option>
              <?php endforeach; ?>
            <?php endif; ?>
          </select>

          <!-- Session Filter -->
          <select id="filterSession" class="qna-select" style="min-width: 240px;">
            <option value="">Semua Sesi (All Sessions)</option>
            <?php if (!empty($days)): ?>
              <?php foreach ($days as $d): ?>
                <?php if (!empty($d['sessions'])): ?>
                  <optgroup label="Day <?= esc($d['day_number']) ?>">
                    <?php foreach ($d['sessions'] as $s): ?>
                      <option value="<?= $s['id'] ?>" <?= (!empty($filterSession) && $filterSession == $s['id']) ? 'selected' : '' ?>>
                        <?= esc($s['start_time']) ?> &mdash; <?= esc($s['title']) ?>
                      </option>
                    <?php endforeach; ?>
                  </optgroup>
                <?php endif; ?>
              <?php endforeach; ?>
            <?php endif; ?>
          </select>
        </div>

        <div style="font-size: 12px; color: rgba(255,255,255,0.5);">
          Auto-refreshing tiap <strong style="color: #4ade80;">6 detik</strong>
        </div>
      </div>

      <!-- Questions List Grid -->
      <div class="qna-grid" id="qnaGrid">
        <?php if (!empty($questions)): ?>
          <?php foreach ($questions as $q): ?>
            <?php 
              $initial = !empty($q['asker_name']) ? mb_strtoupper(mb_substr($q['asker_name'], 0, 1)) : '?';
            ?>
            <div class="qna-card" data-id="<?= $q['id'] ?>">
              <div class="qna-card-meta">
                <div class="qna-asker-name">
                  <span class="qna-asker-avatar"><?= esc($initial) ?></span>
                  <span><?= esc($q['asker_name']) ?></span>
                </div>
                <?php if (!empty($q['session_title'])): ?>
                  <span class="qna-session-tag" title="<?= esc($q['session_title']) ?>">
                    <?= esc(mb_strimwidth($q['session_title'], 0, 24, '...')) ?>
                  </span>
                <?php endif; ?>
              </div>

              <div class="qna-question-body">
                <?= esc($q['question']) ?>
              </div>

              <div class="qna-card-footer">
                <span>
                  <?php if (!empty($q['day_number'])): ?>
                    Day <?= esc($q['day_number']) ?> &bull; 
                  <?php endif; ?>
                  <?= !empty($q['session_time']) ? esc($q['session_time']) : '' ?>
                </span>
                <span style="color: var(--accent-gold); font-weight: 600;">
                  <?= esc($q['time_ago'] ?? 'Baru saja') ?>
                </span>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <div class="qna-empty-state" id="emptyState">
            <svg class="qna-empty-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
            <div class="qna-empty-title">Belum Ada Pertanyaan Masuk</div>
            <div class="qna-empty-desc">Pertanyaan dari audiens yang mengirimkan formulir via barcode sesi akan otomatis muncul di sini.</div>
          </div>
        <?php endif; ?>
      </div>

    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const qnaGrid = document.getElementById('qnaGrid');
      const totalCountEl = document.getElementById('totalQuestionsCount');
      const filterDay = document.getElementById('filterDay');
      const filterSession = document.getElementById('filterSession');

      let currentDayId = filterDay ? filterDay.value : '';
      let currentSessionId = filterSession ? filterSession.value : '';

      // Helper to render question card HTML
      function createCardHtml(q) {
        const initial = (q.asker_name || '?').charAt(0).toUpperCase();
        const sessionTag = q.session_title 
          ? `<span class="qna-session-tag" title="${escapeHtml(q.session_title)}">${escapeHtml(truncate(q.session_title, 24))}</span>` 
          : '';
        const dayText = q.day_number ? `Day ${q.day_number} &bull; ` : '';
        const timeText = q.session_time || '';

        return `
          <div class="qna-card" data-id="${q.id}">
            <div class="qna-card-meta">
              <div class="qna-asker-name">
                <span class="qna-asker-avatar">${escapeHtml(initial)}</span>
                <span>${escapeHtml(q.asker_name)}</span>
              </div>
              ${sessionTag}
            </div>
            <div class="qna-question-body">${escapeHtml(q.question)}</div>
            <div class="qna-card-footer">
              <span>${dayText}${escapeHtml(timeText)}</span>
              <span style="color: var(--accent-gold); font-weight: 600;">${escapeHtml(q.time_ago || 'Baru saja')}</span>
            </div>
          </div>
        `;
      }

      function escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
      }

      function truncate(str, max) {
        if (!str) return '';
        return str.length > max ? str.substring(0, max) + '...' : str;
      }

      // Fetch questions from API
      async function pollQuestions() {
        try {
          const params = new URLSearchParams();
          if (currentDayId) params.append('day_id', currentDayId);
          if (currentSessionId) params.append('session_id', currentSessionId);

          const response = await fetch(`<?= base_url('qna/api/list') ?>?${params.toString()}`);
          if (!response.ok) return;

          const json = await response.json();
          if (json.success && Array.isArray(json.data)) {
            const list = json.data;
            totalCountEl.textContent = list.length;

            if (list.length === 0) {
              qnaGrid.innerHTML = `
                <div class="qna-empty-state" id="emptyState">
                  <svg class="qna-empty-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
                  <div class="qna-empty-title">Belum Ada Pertanyaan Masuk</div>
                  <div class="qna-empty-desc">Pertanyaan dari audiens yang mengirimkan formulir via barcode sesi akan otomatis muncul di sini.</div>
                </div>
              `;
            } else {
              qnaGrid.innerHTML = list.map(q => createCardHtml(q)).join('');
            }
          }
        } catch (err) {
          console.warn('Live QnA polling paused:', err);
        }
      }

      // Filter change events
      if (filterDay) {
        filterDay.addEventListener('change', () => {
          currentDayId = filterDay.value;
          pollQuestions();
        });
      }

      if (filterSession) {
        filterSession.addEventListener('change', () => {
          currentSessionId = filterSession.value;
          pollQuestions();
        });
      }

      // Start automatic polling every 6 seconds
      setInterval(pollQuestions, 6000);
    });
  </script>
</body>
</html>
