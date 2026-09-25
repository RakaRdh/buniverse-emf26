<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Live Q&A Dashboard | <?= esc($site['title'] ?? 'Investor Daily Summit 2026') ?></title>
  
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
      padding: 14px 20px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 16px;
      margin-bottom: 28px;
      box-shadow: 0 8px 30px rgba(0, 0, 0, 0.25);
    }

    .qna-filter-group {
      display: flex;
      align-items: center;
      gap: 12px;
      flex: 1;
      min-width: 0;
    }

    .qna-filter-label {
      font-size: 12px;
      font-weight: 700;
      color: rgba(255, 255, 255, 0.6);
      text-transform: uppercase;
      letter-spacing: 0.06em;
      flex-shrink: 0;
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
      cursor: pointer;
      transition: all 0.2s;
    }

    #filterDay {
      flex: 0 0 auto;
      min-width: 210px;
      max-width: 250px;
    }

    #filterSession {
      flex: 1 1 auto;
      min-width: 240px;
      max-width: 500px;
      text-overflow: ellipsis;
    }

    .qna-select:focus {
      border-color: #5e9cbf;
      box-shadow: 0 0 0 2px rgba(201, 148, 0, 0.25);
    }

    .qna-select option {
      background: #010a2d;
      color: #ffffff;
    }

    @media (max-width: 960px) {
      .qna-filter-bar {
        flex-direction: column;
        align-items: stretch;
      }
      .qna-filter-group {
        flex-wrap: wrap;
        width: 100%;
      }
      #filterDay, #filterSession {
        flex: 1 1 100%;
        max-width: 100%;
      }
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
      cursor: pointer;
    }

    .qna-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 3px;
      background: linear-gradient(90deg, #2e7eac, #5e9cbf, transparent);
      opacity: 0.8;
    }

    .qna-card:hover {
      transform: translateY(-3px);
      border-color: var(--primary-2-7);
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
      background: linear-gradient(135deg, #2e7eac, #5e9cbf);
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
      border: 1px solid var(--primary-2-7);
      color: #5e9cbf;
      font-weight: 700;
      white-space: nowrap;
      max-width: 160px;
      overflow: hidden;
      text-overflow: ellipsis;
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

    /* View Switcher */
    .qna-view-switch {
      display: inline-flex;
      align-items: center;
      background: rgba(1, 10, 45, 0.7);
      border: 1px solid rgba(255, 255, 255, 0.15);
      border-radius: 10px;
      padding: 3px;
      gap: 2px;
    }

    .qna-switch-btn {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 6px 12px;
      border-radius: 7px;
      border: none;
      background: transparent;
      color: rgba(255, 255, 255, 0.65);
      font-size: 12.5px;
      font-weight: 700;
      cursor: pointer;
      transition: all 0.2s;
    }

    .qna-switch-btn:hover {
      color: #ffffff;
      background: rgba(255, 255, 255, 0.08);
    }

    .qna-switch-btn.active {
      background: var(--primary-2-4, #2e7eac);
      color: #010a2d;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
    }

    /* Table View Layout */
    .qna-table-container {
      display: none;
      width: 100%;
      background: rgba(12, 47, 136, 0.22);
      backdrop-filter: blur(14px);
      -webkit-backdrop-filter: blur(14px);
      border: 1px solid rgba(255, 255, 255, 0.1);
      border-radius: 18px;
      overflow: hidden;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
    }

    .qna-table {
      width: 100%;
      border-collapse: collapse;
      text-align: left;
      font-size: 13.5px;
    }

    .qna-table th {
      background: rgba(1, 10, 45, 0.85);
      color: rgba(255, 255, 255, 0.65);
      font-size: 11.5px;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.06em;
      padding: 14px 18px;
      border-bottom: 1px solid rgba(255, 255, 255, 0.12);
      white-space: nowrap;
    }

    .qna-table td {
      padding: 16px 18px;
      border-bottom: 1px solid rgba(255, 255, 255, 0.06);
      vertical-align: middle;
      color: rgba(255, 255, 255, 0.9);
    }

    .qna-table tbody tr {
      transition: background 0.18s ease, transform 0.18s ease;
      cursor: pointer;
    }

    .qna-table tbody tr:hover {
      background: rgba(94, 156, 191, 0.12);
    }

    .qna-table tbody tr:last-child td {
      border-bottom: none;
    }

    .qna-table-question {
      max-width: 480px;
      line-height: 1.5;
      font-weight: 500;
      word-break: break-word;
    }

    .qna-table-asker {
      font-weight: 700;
      color: #ffffff;
      display: flex;
      align-items: center;
      gap: 8px;
      white-space: nowrap;
    }

    .qna-btn-focus {
      padding: 6px 12px;
      background: rgba(94, 156, 191, 0.15);
      border: 1px solid var(--primary-2-7);
      color: #5e9cbf;
      border-radius: 8px;
      font-size: 12px;
      font-weight: 700;
      display: inline-flex;
      align-items: center;
      gap: 5px;
      cursor: pointer;
      transition: all 0.2s;
    }

    .qna-btn-focus:hover {
      background: var(--primary-2-4, #5e9cbf);
      color: #010a2d;
    }

    /* Spotlight / Zoom Modal */
    .qna-spotlight-modal {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      z-index: 9999;
      background: rgba(1, 10, 45, 0.88);
      backdrop-filter: blur(24px);
      -webkit-backdrop-filter: blur(24px);
      display: none;
      align-items: center;
      justify-content: center;
      padding: 30px 20px;
      opacity: 0;
      transition: opacity 0.25s ease;
    }

    .qna-spotlight-modal.show {
      display: flex;
      opacity: 1;
    }

    .qna-spotlight-card {
      position: relative;
      width: 100%;
      max-width: 860px;
      background: rgba(12, 47, 136, 0.4);
      border: 1px solid rgba(255, 255, 255, 0.18);
      box-shadow: 0 25px 60px rgba(0, 0, 0, 0.7), inset 0 1px 0 rgba(255, 255, 255, 0.2);
      border-radius: 24px;
      padding: 36px 40px;
      display: flex;
      flex-direction: column;
      gap: 24px;
      animation: zoomInModal 0.25s cubic-bezier(0.16, 1, 0.3, 1);
    }

    @keyframes zoomInModal {
      from { transform: scale(0.92); opacity: 0; }
      to { transform: scale(1); opacity: 1; }
    }

    .qna-spotlight-close {
      position: absolute;
      top: 20px;
      right: 20px;
      width: 40px;
      height: 40px;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.08);
      border: 1px solid rgba(255, 255, 255, 0.15);
      color: #ffffff;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      transition: all 0.2s;
    }

    .qna-spotlight-close:hover {
      background: rgba(239, 68, 68, 0.3);
      border-color: rgba(239, 68, 68, 0.6);
      transform: rotate(90deg);
    }

    .qna-spotlight-header {
      display: flex;
      align-items: center;
      gap: 16px;
      border-bottom: 1px solid rgba(255, 255, 255, 0.1);
      padding-bottom: 20px;
    }

    .qna-spotlight-avatar {
      width: 54px;
      height: 54px;
      border-radius: 50%;
      background: linear-gradient(135deg, #2e7eac, #5e9cbf);
      color: #010a2d;
      font-size: 22px;
      font-weight: 800;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
    }

    .qna-spotlight-asker {
      font-size: 22px;
      font-weight: 800;
      color: #ffffff;
      margin: 0 0 4px 0;
    }

    .qna-spotlight-session {
      font-size: 13.5px;
      color: #5e9cbf;
      font-weight: 600;
    }

    .qna-spotlight-body {
      font-size: 26px;
      line-height: 1.55;
      font-weight: 600;
      color: #ffffff;
      white-space: pre-line;
      word-break: break-word;
      max-height: 52vh;
      overflow-y: auto;
      padding-right: 10px;
      text-shadow: 0 2px 10px rgba(0, 0, 0, 0.5);
    }

    @media (max-width: 768px) {
      .qna-spotlight-card {
        padding: 24px 20px;
      }
      .qna-spotlight-body {
        font-size: 19px;
      }
    }

    .qna-spotlight-footer {
      display: flex;
      align-items: center;
      justify-content: space-between;
      border-top: 1px solid rgba(255, 255, 255, 0.1);
      padding-top: 20px;
      flex-wrap: wrap;
      gap: 14px;
    }

    .qna-spotlight-nav {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .qna-nav-btn {
      padding: 8px 16px;
      background: rgba(255, 255, 255, 0.08);
      border: 1px solid rgba(255, 255, 255, 0.15);
      border-radius: 10px;
      color: #ffffff;
      font-size: 13px;
      font-weight: 700;
      display: inline-flex;
      align-items: center;
      gap: 6px;
      cursor: pointer;
      transition: all 0.2s;
    }

    .qna-nav-btn:hover:not(:disabled) {
      background: var(--primary-2-4, #2e7eac);
      color: #010a2d;
      border-color: transparent;
    }

    .qna-nav-btn:disabled {
      opacity: 0.35;
      cursor: not-allowed;
    }

    .qna-spotlight-counter {
      font-size: 13px;
      font-weight: 700;
      color: rgba(255, 255, 255, 0.6);
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
          <a href="<?= base_url('/') ?>"><img src="<?= base_url('assets/img/logo_theme.webp') ?>" alt="Logo" class="qna-dash-logo"></a>
          <div>
            <h1 class="qna-dash-title">Q&A Live Dashboard</h1>
            <p class="qna-dash-subtitle"><?= esc($site['title'] ?? 'Investor Daily Summit 2026') ?> &bull; Forum Pertanyaan Sesi</p>
          </div>
        </div>

        <!-- <div style="display: flex; align-items: center; gap: 14px;">
          <div class="qna-live-pill">
            <span class="qna-live-dot"></span>
            <span>Live Sync</span>
          </div>
          <div style="font-size: 13px; font-weight: 700; color: #5e9cbf;">
            <span id="totalQuestionsCount"><?= count($questions) ?></span> Pertanyaan
          </div>
        </div> -->
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
                <?php 
                  $dLabel = !empty($d['formatted_date']) ? $d['formatted_date'] : (!empty($d['event_date']) ? date('F j, Y', strtotime($d['event_date'])) : $d['label']);
                ?>
                <option value="<?= $d['day_id'] ?>" <?= (!empty($filterDay) && $filterDay == $d['day_id']) ? 'selected' : '' ?>>
                  Day <?= esc($d['day_number']) ?> &mdash; <?= esc($dLabel) ?>
                </option>
              <?php endforeach; ?>
            <?php endif; ?>
          </select>

          <!-- Session Filter -->
          <select id="filterSession" class="qna-select">
            <option value="">Semua Sesi (All Sessions)</option>
            <?php if (!empty($days)): ?>
              <?php foreach ($days as $d): ?>
                <?php if (!empty($d['sessions'])): ?>
                  <?php 
                    $optGroupLabel = 'Day ' . esc($d['day_number']) . (!empty($d['formatted_date']) ? ' (' . esc($d['formatted_date']) . ')' : '');
                  ?>
                  <optgroup label="<?= $optGroupLabel ?>">
                    <?php foreach ($d['sessions'] as $s): ?>
                      <?php 
                        $optVal = $s['id'] . (!empty($s['item_id']) ? ':' . $s['item_id'] : '');
                      ?>
                      <option value="<?= $optVal ?>" <?= (!empty($filterSession) && $filterSession == $optVal) ? 'selected' : '' ?>>
                        [<?= esc($s['start_time']) ?>] <?= esc($s['title']) ?><?= !empty($s['subtitle']) ? ' — ' . esc($s['subtitle']) : '' ?>
                      </option>
                    <?php endforeach; ?>
                  </optgroup>
                <?php endif; ?>
              <?php endforeach; ?>
            <?php endif; ?>
          </select>
        </div>

        <!-- View Switcher -->
        <div style="flex-shrink: 0;">
          <div class="qna-view-switch">
            <button type="button" id="viewCardBtn" class="qna-switch-btn active" title="Tampilan Kartu">
              <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.2"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
              <span>Cards</span>
            </button>
            <button type="button" id="viewTableBtn" class="qna-switch-btn" title="Tampilan Tabel">
              <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.2"><line x1="8" y1="6" x2="21" y2="6"></line><line x1="8" y1="12" x2="21" y2="12"></line><line x1="8" y1="18" x2="21" y2="18"></line><line x1="3" y1="6" x2="3.01" y2="6"></line><line x1="3" y1="12" x2="3.01" y2="12"></line><line x1="3" y1="18" x2="3.01" y2="18"></line></svg>
              <span>Tabel</span>
            </button>
          </div>
        </div>
      </div>

      <!-- 1. Questions List Grid (Card View) -->
      <div class="qna-grid" id="qnaGrid">
        <?php if (!empty($questions)): ?>
          <?php foreach ($questions as $idx => $q): ?>
            <?php 
              $initial = !empty($q['asker_name']) ? mb_strtoupper(mb_substr($q['asker_name'], 0, 1)) : '?';
            ?>
            <div class="qna-card" data-index="<?= $idx ?>" data-id="<?= $q['id'] ?>">
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
                <span style="color: #5e9cbf; font-weight: 700;">
                  <?= esc($q['time_ago'] ?? 'Baru saja') ?>
                </span>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <div class="qna-empty-state" id="emptyStateCards">
            <svg class="qna-empty-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
            <div class="qna-empty-title">Belum Ada Pertanyaan Masuk</div>
            <div class="qna-empty-desc">Pertanyaan dari audiens yang mengirimkan formulir via barcode sesi akan otomatis muncul di sini.</div>
          </div>
        <?php endif; ?>
      </div>

      <!-- 2. Questions Table Container (Table View) -->
      <div class="qna-table-container" id="qnaTableContainer">
        <div style="overflow-x: auto;">
          <table class="qna-table" id="qnaTable">
            <thead>
              <tr>
                <th style="width: 50px; text-align: center;">#</th>
                <th style="width: 140px;">Waktu</th>
                <th style="width: 200px;">Penanya</th>
                <th style="width: 220px;">Sesi Acara</th>
                <th>Pertanyaan</th>
                <th style="width: 110px; text-align: center;">Aksi</th>
              </tr>
            </thead>
            <tbody id="qnaTableBody">
              <?php if (!empty($questions)): ?>
                <?php foreach ($questions as $idx => $q): ?>
                  <?php 
                    $initial = !empty($q['asker_name']) ? mb_strtoupper(mb_substr($q['asker_name'], 0, 1)) : '?';
                  ?>
                  <tr data-index="<?= $idx ?>" data-id="<?= $q['id'] ?>">
                    <td style="text-align: center; color: rgba(255,255,255,0.5); font-weight: 700;"><?= $idx + 1 ?></td>
                    <td style="color: #5e9cbf; font-weight: 600; white-space: nowrap;"><?= esc($q['time_ago'] ?? 'Baru saja') ?></td>
                    <td>
                      <div class="qna-table-asker">
                        <span class="qna-asker-avatar" style="width: 26px; height: 26px; font-size: 11px;"><?= esc($initial) ?></span>
                        <span><?= esc($q['asker_name']) ?></span>
                      </div>
                    </td>
                    <td>
                      <div style="font-weight: 600; font-size: 13px; color: #ffffff;"><?= esc($q['session_title'] ?? '-') ?></div>
                      <div style="font-size: 11.5px; color: rgba(255,255,255,0.5);">
                        <?= !empty($q['day_number']) ? 'Day ' . esc($q['day_number']) . ' &bull; ' : '' ?><?= esc($q['session_time'] ?? '') ?>
                      </div>
                    </td>
                    <td>
                      <div class="qna-table-question"><?= esc($q['question']) ?></div>
                    </td>
                    <td style="text-align: center;">
                      <button type="button" class="qna-btn-focus" data-index="<?= $idx ?>" title="Focus Zoom Pertanyaan">
                        <svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M15 3h6v6M9 21H3v-6M21 3l-7 7M3 21l7-7"></path></svg>
                        <span>Focus</span>
                      </button>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr>
                  <td colspan="6" style="text-align: center; padding: 60px 20px;">
                    <div class="qna-empty-title">Belum Ada Pertanyaan Masuk</div>
                    <div class="qna-empty-desc">Pertanyaan dari audiens akan otomatis muncul di sini.</div>
                  </td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>

    </div>
  </div>

  <!-- 4. Spotlight / Zoom Focus Modal for Moderator -->
  <div class="qna-spotlight-modal" id="qnaSpotlightModal" role="dialog" aria-modal="true" aria-labelledby="modalAskerName">
    <div class="qna-spotlight-card" id="qnaSpotlightCard">
      <!-- Close Button -->
      <button type="button" class="qna-spotlight-close" id="btnSpotlightClose" aria-label="Tutup Mode Fokus">
        <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
      </button>

      <!-- Moderator Header -->
      <div class="qna-spotlight-header">
        <div class="qna-spotlight-avatar" id="modalAskerAvatar">?</div>
        <div>
          <h2 class="qna-spotlight-asker" id="modalAskerName">Nama Penanya</h2>
          <div class="qna-spotlight-session" id="modalSessionInfo">Sesi Acara &bull; Waktu</div>
        </div>
      </div>

      <!-- Large Question Body -->
      <div class="qna-spotlight-body" id="modalQuestionText">
        Isi pertanyaan audiens akan tampil di sini dengan ukuran teks yang besar dan mudah dibaca oleh moderator.
      </div>

      <!-- Navigation & Controls -->
      <div class="qna-spotlight-footer">
        <div class="qna-spotlight-nav">
          <button type="button" class="qna-nav-btn" id="btnPrevQuestion">
            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"></polyline></svg>
            <span>Sebelumnya</span>
          </button>
          <button type="button" class="qna-nav-btn" id="btnNextQuestion">
            <span>Berikutnya</span>
            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"></polyline></svg>
          </button>
        </div>

        <div style="display: flex; align-items: center; gap: 16px;">
          <div class="qna-spotlight-counter" id="modalCounter">
            Pertanyaan 1 dari 10
          </div>
          <div style="font-size: 11.5px; color: rgba(255,255,255,0.4); display: none; @media(min-width: 640px){display: block;}">
            Shortcut: <kbd style="background: rgba(255,255,255,0.1); padding: 2px 5px; border-radius: 4px;">&larr;</kbd> <kbd style="background: rgba(255,255,255,0.1); padding: 2px 5px; border-radius: 4px;">&rarr;</kbd> Navigasi &bull; <kbd style="background: rgba(255,255,255,0.1); padding: 2px 5px; border-radius: 4px;">Esc</kbd> Tutup
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      // Elements
      const qnaGrid = document.getElementById('qnaGrid');
      const qnaTableContainer = document.getElementById('qnaTableContainer');
      const qnaTableBody = document.getElementById('qnaTableBody');
      const filterDay = document.getElementById('filterDay');
      const filterSession = document.getElementById('filterSession');
      const viewCardBtn = document.getElementById('viewCardBtn');
      const viewTableBtn = document.getElementById('viewTableBtn');

      // Spotlight Modal Elements
      const modal = document.getElementById('qnaSpotlightModal');
      const modalCard = document.getElementById('qnaSpotlightCard');
      const modalCloseBtn = document.getElementById('btnSpotlightClose');
      const modalAskerAvatar = document.getElementById('modalAskerAvatar');
      const modalAskerName = document.getElementById('modalAskerName');
      const modalSessionInfo = document.getElementById('modalSessionInfo');
      const modalQuestionText = document.getElementById('modalQuestionText');
      const modalCounter = document.getElementById('modalCounter');
      const btnPrevQuestion = document.getElementById('btnPrevQuestion');
      const btnNextQuestion = document.getElementById('btnNextQuestion');

      // State
      let currentQuestions = <?= json_encode($questions ?? []) ?>;
      let activeIndex = -1;
      let currentDayId = filterDay ? filterDay.value : '';
      let currentSessionId = filterSession ? filterSession.value : '';
      let currentView = localStorage.getItem('qna_dash_view_mode') || 'cards';

      // Anti-XSS Sanitizer
      function escapeHtml(text) {
        if (text === null || text === undefined) return '';
        const map = {
          '&': '&amp;',
          '<': '&lt;',
          '>': '&gt;',
          '"': '&quot;',
          "'": '&#039;',
          '/': '&#x2F;'
        };
        return String(text).replace(/[&<>"'/]/g, m => map[m]);
      }

      function truncate(str, max) {
        if (!str) return '';
        return str.length > max ? str.substring(0, max) + '...' : str;
      }

      // View Mode Toggle
      function setViewMode(mode) {
        currentView = mode;
        localStorage.setItem('qna_dash_view_mode', mode);

        if (mode === 'table') {
          viewTableBtn.classList.add('active');
          viewCardBtn.classList.remove('active');
          qnaGrid.style.display = 'none';
          qnaTableContainer.style.display = 'block';
        } else {
          viewCardBtn.classList.add('active');
          viewTableBtn.classList.remove('active');
          qnaGrid.style.display = 'grid';
          qnaTableContainer.style.display = 'none';
        }
      }

      if (viewCardBtn && viewTableBtn) {
        viewCardBtn.addEventListener('click', () => setViewMode('cards'));
        viewTableBtn.addEventListener('click', () => setViewMode('table'));
        setViewMode(currentView);
      }

      // Render Card HTML
      function createCardHtml(q, idx) {
        const initial = (q.asker_name || '?').charAt(0).toUpperCase();
        const sessionTag = q.session_title 
          ? `<span class="qna-session-tag" title="${escapeHtml(q.session_title)}">${escapeHtml(truncate(q.session_title, 24))}</span>` 
          : '';
        const dayText = q.day_number ? `Day ${escapeHtml(q.day_number)} &bull; ` : '';
        const timeText = q.session_time || '';

        return `
          <div class="qna-card" data-index="${idx}" data-id="${escapeHtml(q.id)}">
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
              <span style="color: #5e9cbf; font-weight: 700;">${escapeHtml(q.time_ago || 'Baru saja')}</span>
            </div>
          </div>
        `;
      }

      // Render Table Row HTML
      function createTableRowHtml(q, idx) {
        const initial = (q.asker_name || '?').charAt(0).toUpperCase();
        const dayText = q.day_number ? `Day ${escapeHtml(q.day_number)} &bull; ` : '';
        const sessionTimeText = q.session_time ? escapeHtml(q.session_time) : '';

        return `
          <tr data-index="${idx}" data-id="${escapeHtml(q.id)}">
            <td style="text-align: center; color: rgba(255,255,255,0.5); font-weight: 700;">${idx + 1}</td>
            <td style="color: #5e9cbf; font-weight: 600; white-space: nowrap;">${escapeHtml(q.time_ago || 'Baru saja')}</td>
            <td>
              <div class="qna-table-asker">
                <span class="qna-asker-avatar" style="width: 26px; height: 26px; font-size: 11px;">${escapeHtml(initial)}</span>
                <span>${escapeHtml(q.asker_name)}</span>
              </div>
            </td>
            <td>
              <div style="font-weight: 600; font-size: 13px; color: #ffffff;">${escapeHtml(q.session_title || '-')}</div>
              <div style="font-size: 11.5px; color: rgba(255,255,255,0.5);">${dayText}${sessionTimeText}</div>
            </td>
            <td>
              <div class="qna-table-question">${escapeHtml(q.question)}</div>
            </td>
            <td style="text-align: center;">
              <button type="button" class="qna-btn-focus" data-index="${idx}" title="Focus Zoom Pertanyaan">
                <svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M15 3h6v6M9 21H3v-6M21 3l-7 7M3 21l7-7"></path></svg>
                <span>Focus</span>
              </button>
            </td>
          </tr>
        `;
      }

      // Render All Views
      function renderAllViews(list) {
        currentQuestions = list;

        if (list.length === 0) {
          qnaGrid.innerHTML = `
            <div class="qna-empty-state" id="emptyStateCards">
              <svg class="qna-empty-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
              <div class="qna-empty-title">Belum Ada Pertanyaan Masuk</div>
              <div class="qna-empty-desc">Pertanyaan dari audiens yang mengirimkan formulir via barcode sesi akan otomatis muncul di sini.</div>
            </div>
          `;
          qnaTableBody.innerHTML = `
            <tr>
              <td colspan="6" style="text-align: center; padding: 60px 20px;">
                <div class="qna-empty-title">Belum Ada Pertanyaan Masuk</div>
                <div class="qna-empty-desc">Pertanyaan dari audiens akan otomatis muncul di sini.</div>
              </td>
            </tr>
          `;
          return;
        }

        // Render Cards
        qnaGrid.innerHTML = list.map((q, idx) => createCardHtml(q, idx)).join('');

        // Render Table
        qnaTableBody.innerHTML = list.map((q, idx) => createTableRowHtml(q, idx)).join('');

        // If spotlight modal is open, refresh its counter and state
        if (modal.classList.contains('show') && activeIndex >= 0) {
          if (activeIndex >= list.length) activeIndex = list.length - 1;
          showSpotlight(activeIndex);
        }
      }

      // Spotlight / Zoom Modal Logic
      function showSpotlight(index) {
        if (!currentQuestions || currentQuestions.length === 0) return;
        if (index < 0 || index >= currentQuestions.length) return;

        activeIndex = index;
        const q = currentQuestions[index];

        modalAskerAvatar.textContent = (q.asker_name || '?').charAt(0).toUpperCase();
        modalAskerName.textContent = q.asker_name || 'Penanya Anonim';
        
        let sessionDetails = [];
        if (q.session_title) sessionDetails.push(q.session_title);
        if (q.day_number) sessionDetails.push(`Day ${q.day_number}`);
        if (q.session_time) sessionDetails.push(q.session_time);
        if (q.time_ago) sessionDetails.push(`(${q.time_ago})`);

        modalSessionInfo.textContent = sessionDetails.join(' • ');
        modalQuestionText.textContent = q.question || '';
        modalCounter.textContent = `Pertanyaan ${index + 1} dari ${currentQuestions.length}`;

        btnPrevQuestion.disabled = (index === 0);
        btnNextQuestion.disabled = (index === currentQuestions.length - 1);

        modal.classList.add('show');
        document.body.style.overflow = 'hidden';
      }

      function closeSpotlight() {
        modal.classList.remove('show');
        document.body.style.overflow = '';
      }

      // Event Delegation for Opening Spotlight from Cards or Table
      qnaGrid.addEventListener('click', (e) => {
        const card = e.target.closest('.qna-card');
        if (card) {
          const idx = parseInt(card.getAttribute('data-index'), 10);
          showSpotlight(idx);
        }
      });

      qnaTableBody.addEventListener('click', (e) => {
        const row = e.target.closest('tr');
        if (row && row.hasAttribute('data-index')) {
          const idx = parseInt(row.getAttribute('data-index'), 10);
          showSpotlight(idx);
        }
      });

      if (modalCloseBtn) modalCloseBtn.addEventListener('click', closeSpotlight);

      modal.addEventListener('click', (e) => {
        if (e.target === modal) closeSpotlight();
      });

      if (btnPrevQuestion) {
        btnPrevQuestion.addEventListener('click', () => {
          if (activeIndex > 0) showSpotlight(activeIndex - 1);
        });
      }

      if (btnNextQuestion) {
        btnNextQuestion.addEventListener('click', () => {
          if (activeIndex < currentQuestions.length - 1) showSpotlight(activeIndex + 1);
        });
      }

      // Keyboard Accessibility for Spotlight
      document.addEventListener('keydown', (e) => {
        if (!modal.classList.contains('show')) return;

        if (e.key === 'Escape') {
          closeSpotlight();
        } else if (e.key === 'ArrowLeft' && activeIndex > 0) {
          showSpotlight(activeIndex - 1);
        } else if (e.key === 'ArrowRight' && activeIndex < currentQuestions.length - 1) {
          showSpotlight(activeIndex + 1);
        }
      });

      // Polling from API
      async function pollQuestions() {
        try {
          const params = new URLSearchParams();
          if (currentDayId) params.append('day_id', currentDayId);
          if (currentSessionId) params.append('session_id', currentSessionId);

          const response = await fetch(`<?= base_url('qna/api/list') ?>?${params.toString()}`);
          if (!response.ok) return;

          const json = await response.json();
          if (json.success && Array.isArray(json.data)) {
            renderAllViews(json.data);
          }
        } catch (err) {
          console.warn('Live QnA polling paused:', err);
        }
      }

      // Filter Change Events
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

      // Auto poll every 6 seconds
      setInterval(pollQuestions, 6000);
    });
  </script>
</body>
</html>
