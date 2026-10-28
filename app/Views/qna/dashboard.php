<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Live Q&A Dashboard | <?= esc($site['title'] ?? 'Energy & Mineral Festival 2026') ?></title>
  
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
       EMF QNA LIVE DASHBOARD — SOLID YELLOW & CLEAN FOREST GREEN PALETTE
       ========================================================================== */
    html, body {
      background-color: var(--yellow-6, #f4d849) !important;
      color: var(--green-10, #214415);
      min-height: 100vh;
      overflow-x: hidden;
      font-family: 'Manrope', -apple-system, BlinkMacSystemFont, sans-serif;
      margin: 0;
      padding: 0;
    }

    .qna-dash-wrapper {
      position: relative;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      padding: 36px 24px 60px;
      background-color: var(--yellow-6, #f4d849);
      overflow: hidden;
      box-sizing: border-box;
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
      .qna-dash-wrapper {
        padding: 24px 16px 50px 16px;
      }
    }

    /* --------------------------------------------------------------------------
       MAIN DASHBOARD CONTAINER
       -------------------------------------------------------------------------- */
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
      margin-bottom: 24px;
      padding-bottom: 18px;
      border-bottom: 2px solid rgba(73, 152, 46, 0.25);
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
      color: var(--green-10, #214415);
      margin: 0;
      line-height: 1.2;
    }

    .qna-dash-subtitle {
      font-size: 13.5px;
      font-weight: 600;
      color: var(--green-8, #346c21);
      margin: 3px 0 0 0;
    }

    /* Filters Section */
    .qna-filter-bar {
      background: #ffffff;
      border: 1px solid rgba(73, 152, 46, 0.25);
      border-radius: 16px;
      padding: 14px 20px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 16px;
      margin-bottom: 28px;
      box-shadow: 0 6px 20px rgba(33, 68, 21, 0.06);
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
      font-weight: 800;
      color: var(--green-9, #2a571a);
      text-transform: uppercase;
      letter-spacing: 0.06em;
      flex-shrink: 0;
    }

    .qna-select {
      background: #ffffff;
      border: 1.5px solid rgba(73, 152, 46, 0.3);
      color: #1a202c;
      padding: 9px 14px;
      border-radius: 10px;
      font-size: 13.5px;
      font-weight: 600;
      outline: none;
      cursor: pointer;
      transition: border-color 0.2s, box-shadow 0.2s;
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
      border-color: var(--green-6, #49982e);
      box-shadow: 0 0 0 3px rgba(73, 152, 46, 0.15);
    }

    .qna-select option {
      background: #ffffff;
      color: #1a202c;
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
      background: #ffffff;
      border: 1px solid rgba(73, 152, 46, 0.25);
      border-radius: 18px;
      padding: 24px 24px 20px 24px;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      gap: 16px;
      box-shadow: 0 8px 24px rgba(33, 68, 21, 0.06);
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
      height: 4px;
      background: linear-gradient(90deg, var(--green-6, #49982e), var(--yellow-6, #f4d849));
    }

    .qna-card:hover {
      transform: translateY(-3px);
      border-color: var(--green-6, #49982e);
      box-shadow: 0 12px 30px rgba(33, 68, 21, 0.12);
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
      color: var(--green-10, #214415);
      line-height: 1.3;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .qna-asker-avatar {
      width: 34px;
      height: 34px;
      border-radius: 50%;
      background: var(--green-6, #49982e);
      color: #ffffff;
      font-weight: 800;
      font-size: 13.5px;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
    }

    .qna-session-tag {
      font-size: 11.5px;
      padding: 4px 10px;
      border-radius: 6px;
      background: #f1f8ee;
      border: 1px solid rgba(73, 152, 46, 0.3);
      color: var(--green-8, #346c21);
      font-weight: 700;
      white-space: nowrap;
      max-width: 170px;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    .qna-question-body {
      font-size: 15px;
      line-height: 1.6;
      color: #1e293b;
      white-space: pre-line;
      word-break: break-word;
      font-weight: 500;
    }

    .qna-card-footer {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding-top: 14px;
      border-top: 1px solid #f1f5f9;
      font-size: 12.5px;
      color: #64748b;
      font-weight: 500;
    }

    /* View Switcher */
    .qna-view-switch {
      display: inline-flex;
      align-items: center;
      background: #f8fafc;
      border: 1.5px solid rgba(73, 152, 46, 0.3);
      border-radius: 10px;
      padding: 3px;
      gap: 3px;
    }

    .qna-switch-btn {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 6px 12px;
      border-radius: 7px;
      border: none;
      background: transparent;
      color: #64748b;
      font-size: 12.5px;
      font-weight: 700;
      cursor: pointer;
      transition: all 0.2s;
    }

    .qna-switch-btn:hover {
      color: var(--green-10, #214415);
      background: #e2e8f0;
    }

    .qna-switch-btn.active {
      background: var(--green-6, #49982e);
      color: #ffffff;
      box-shadow: 0 2px 6px rgba(33, 68, 21, 0.15);
    }

    /* Table View Layout */
    .qna-table-container {
      display: none;
      width: 100%;
      background: #ffffff;
      border: 1px solid rgba(73, 152, 46, 0.25);
      border-radius: 18px;
      overflow: hidden;
      box-shadow: 0 8px 24px rgba(33, 68, 21, 0.06);
    }

    .qna-table {
      width: 100%;
      border-collapse: collapse;
      text-align: left;
      font-size: 13.5px;
    }

    .qna-table th {
      background: #f8fafc;
      color: var(--green-9, #2a571a);
      font-size: 12px;
      font-weight: 800;
      text-transform: uppercase;
      letter-spacing: 0.06em;
      padding: 14px 18px;
      border-bottom: 2px solid #e2e8f0;
      white-space: nowrap;
    }

    .qna-table td {
      padding: 16px 18px;
      border-bottom: 1px solid #f1f5f9;
      vertical-align: middle;
      color: #1e293b;
    }

    .qna-table tbody tr {
      transition: background 0.18s ease;
      cursor: pointer;
    }

    .qna-table tbody tr:hover {
      background: #f1f8ee;
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
      color: var(--green-10, #214415);
      display: flex;
      align-items: center;
      gap: 8px;
      white-space: nowrap;
    }

    .qna-btn-focus {
      padding: 6px 12px;
      background: #f1f8ee;
      border: 1px solid rgba(73, 152, 46, 0.4);
      color: var(--green-8, #346c21);
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
      background: var(--green-6, #49982e);
      color: #ffffff;
      border-color: var(--green-6, #49982e);
    }

    /* Spotlight / Zoom Modal */
    .qna-spotlight-modal {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      z-index: 9999;
      background: rgba(15, 23, 42, 0.7);
      backdrop-filter: blur(8px);
      -webkit-backdrop-filter: blur(8px);
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
      background: #ffffff;
      border: 1.5px solid rgba(73, 152, 46, 0.35);
      box-shadow: 0 25px 60px rgba(15, 23, 42, 0.25);
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
      background: #f1f5f9;
      border: 1px solid #cbd5e1;
      color: #64748b;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      transition: all 0.2s;
    }

    .qna-spotlight-close:hover {
      background: #fee2e2;
      border-color: #ef4444;
      color: #dc2626;
      transform: rotate(90deg);
    }

    .qna-spotlight-header {
      display: flex;
      align-items: center;
      gap: 16px;
      border-bottom: 2px solid #f1f5f9;
      padding-bottom: 20px;
    }

    .qna-spotlight-avatar {
      width: 54px;
      height: 54px;
      border-radius: 50%;
      background: var(--green-6, #49982e);
      color: #ffffff;
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
      color: var(--green-10, #214415);
      margin: 0 0 4px 0;
    }

    .qna-spotlight-session {
      font-size: 13.5px;
      color: var(--green-7, #3e8127);
      font-weight: 700;
    }

    .qna-spotlight-body {
      font-size: 24px;
      line-height: 1.55;
      font-weight: 600;
      color: #1e293b;
      white-space: pre-line;
      word-break: break-word;
      max-height: 52vh;
      overflow-y: auto;
      padding-right: 10px;
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
      border-top: 1px solid #f1f5f9;
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
      background: #f1f5f9;
      border: 1px solid #cbd5e1;
      border-radius: 10px;
      color: var(--green-10, #214415);
      font-size: 13px;
      font-weight: 700;
      display: inline-flex;
      align-items: center;
      gap: 6px;
      cursor: pointer;
      transition: all 0.2s;
    }

    .qna-nav-btn:hover:not(:disabled) {
      background: var(--green-6, #49982e);
      color: #ffffff;
      border-color: var(--green-6, #49982e);
    }

    .qna-nav-btn:disabled {
      opacity: 0.4;
      cursor: not-allowed;
    }

    .qna-spotlight-counter {
      font-size: 13px;
      font-weight: 700;
      color: #64748b;
    }

    /* Empty State */
    .qna-empty-state {
      text-align: center;
      padding: 70px 20px;
      background: #ffffff;
      border-radius: 18px;
      border: 2px dashed rgba(73, 152, 46, 0.35);
      grid-column: 1 / -1;
      box-shadow: 0 6px 20px rgba(33, 68, 21, 0.04);
    }

    .qna-empty-icon {
      width: 56px;
      height: 56px;
      margin: 0 auto 16px;
      color: var(--green-7, #3e8127);
    }

    .qna-empty-title {
      font-size: 19px;
      font-weight: 800;
      color: var(--green-10, #214415);
      margin-bottom: 6px;
    }

    .qna-empty-desc {
      font-size: 14px;
      color: var(--green-8, #346c21);
      max-width: 440px;
      margin: 0 auto;
      line-height: 1.5;
    }
  </style>
</head>
<body>

  <div class="qna-dash-wrapper">
    
    <?php /* 1. Ribbon Dekoratif Pojok Kiri Atas (Desktop) & Pojok Kanan Atas (Desktop + Mobile) */ ?>
    <div class="qna-ribbon-deco qna-ribbon-deco-left" aria-hidden="true">
      <img src="<?= base_url('assets/img/ribbon.webp') ?>" alt="">
    </div>
    <div class="qna-ribbon-deco qna-ribbon-deco-right" aria-hidden="true">
      <img src="<?= base_url('assets/img/ribbon.webp') ?>" alt="">
    </div>

    <?php /* 2. Main Dashboard Content */ ?>
    <div class="qna-dash-container">
      
      <?php /* Top Brand Bar */ ?>
      <div class="qna-dash-header">
        <div class="qna-dash-brand">
          <a href="<?= base_url('/') ?>">
            <img src="<?= base_url('assets/img/logo_green_emf26.webp') ?>" alt="Logo EMF 2026" class="qna-dash-logo">
          </a>
          <div>
            <h1 class="qna-dash-title">Q&A Live Dashboard</h1>
            <p class="qna-dash-subtitle"><?= esc($site['title'] ?? 'Energy & Mineral Festival 2026') ?> &bull; Forum Pertanyaan Sesi</p>
          </div>
        </div>

        <div style="display: flex; align-items: center; gap: 14px;">
          <div style="font-size: 13.5px; font-weight: 700; color: var(--green-9, #2a571a);">
            Total: <span id="totalQuestionsCount" style="font-size: 16px; font-weight: 800;"><?= count($questions) ?></span> Pertanyaan
          </div>
        </div>
      </div>

      <?php /* Filters Bar */ ?>
      <div class="qna-filter-bar">
        <div class="qna-filter-group">
          <span class="qna-filter-label">Filter:</span>
          
          <?php /* Day Filter */ ?>
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

          <?php /* Session Filter */ ?>
          <select id="filterSession" class="qna-select">
            <option value="">Semua Sesi (All Sessions)</option>
            <?php if (!empty($days)): ?>
              <?php foreach ($days as $d): ?>
                <?php if (!empty($d['sessions'])): ?>
                  <?php 
                    $roomGroupedSessions = [];
                    foreach ($d['sessions'] as $s) {
                        $roomKey = !empty(trim($s['room_name'] ?? '')) ? trim($s['room_name']) : '';
                        $roomGroupedSessions[$roomKey][] = $s;
                    }
                    $dayPrefix = 'Day ' . esc($d['day_number']) . (!empty($d['formatted_date']) ? ' (' . esc($d['formatted_date']) . ')' : '');
                  ?>
                  <?php foreach ($roomGroupedSessions as $rName => $sessList): ?>
                    <?php 
                      $optGroupLabel = !empty($rName) ? ($dayPrefix . ' — ' . esc($rName)) : $dayPrefix;
                    ?>
                    <optgroup label="<?= $optGroupLabel ?>">
                      <?php foreach ($sessList as $s): ?>
                        <?php 
                          $optVal = $s['id'] . (!empty($s['item_id']) ? ':' . $s['item_id'] : '');
                        ?>
                        <option value="<?= $optVal ?>" <?= (!empty($filterSession) && $filterSession == $optVal) ? 'selected' : '' ?>>
                          [<?= esc($s['start_time']) ?>] <?= esc($s['title']) ?><?= !empty($s['subtitle']) ? ' — ' . esc($s['subtitle']) : '' ?>
                        </option>
                      <?php endforeach; ?>
                    </optgroup>
                  <?php endforeach; ?>
                <?php endif; ?>
              <?php endforeach; ?>
            <?php endif; ?>
          </select>
        </div>

        <?php /* View Switcher */ ?>
        <div class="qna-view-switch">
          <button type="button" id="viewCardBtn" class="qna-switch-btn active" title="Tampilan Kartu">
            <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
            <span>Cards</span>
          </button>
          <button type="button" id="viewTableBtn" class="qna-switch-btn" title="Tampilan Tabel">
            <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="8" y1="6" x2="21" y2="6"></line><line x1="8" y1="12" x2="21" y2="12"></line><line x1="8" y1="18" x2="21" y2="18"></line><line x1="3" y1="6" x2="3.01" y2="6"></line><line x1="3" y1="12" x2="3.01" y2="12"></line><line x1="3" y1="18" x2="3.01" y2="18"></line></svg>
            <span>Tabel</span>
          </button>
        </div>
      </div>

      <?php /* 1. Card View Grid */ ?>
      <div id="qnaGrid" class="qna-grid">
        <?php if (empty($questions)): ?>
          <div class="qna-empty-state" id="emptyStateCards">
            <svg class="qna-empty-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
              <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
              <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
              <line x1="12" y1="17" x2="12.01" y2="17"></line>
            </svg>
            <div class="qna-empty-title">Belum Ada Pertanyaan Masuk</div>
            <div class="qna-empty-desc">Pertanyaan dari audiens yang mengirimkan formulir via barcode sesi akan otomatis muncul di sini.</div>
          </div>
        <?php else: ?>
          <?php foreach ($questions as $idx => $q): ?>
            <?php 
              $initial = strtoupper(mb_substr($q['asker_name'] ?? '?', 0, 1));
              $sessionTitle = $q['session_title'] ?? '';
              $dayLabel = !empty($q['day_number']) ? ('Day ' . esc($q['day_number']) . ' &bull; ') : '';
              $timeLabel = !empty($q['session_time']) ? esc($q['session_time']) : '';
            ?>
            <div class="qna-card" data-index="<?= $idx ?>" data-id="<?= esc($q['id']) ?>">
              <div class="qna-card-meta">
                <div class="qna-asker-name">
                  <span class="qna-asker-avatar"><?= esc($initial) ?></span>
                  <span><?= esc($q['asker_name']) ?></span>
                </div>
                <?php if (!empty($sessionTitle)): ?>
                  <span class="qna-session-tag" title="<?= esc($sessionTitle) ?>"><?= esc(mb_strimwidth($sessionTitle, 0, 24, '...')) ?></span>
                <?php endif; ?>
              </div>
              <div class="qna-question-body"><?= nl2br(esc($q['question'])) ?></div>
              <div class="qna-card-footer">
                <span><?= $dayLabel ?><?= $timeLabel ?></span>
                <span style="color: var(--green-7, #3e8127); font-weight: 700;"><?= esc($q['time_ago'] ?? 'Baru saja') ?></span>
              </div>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>

      <?php /* 2. Table View Container */ ?>
      <div id="qnaTableContainer" class="qna-table-container">
        <table class="qna-table">
          <thead>
            <tr>
              <th style="width: 50px; text-align: center;">#</th>
              <th style="width: 140px;">Waktu</th>
              <th style="width: 220px;">Penanya</th>
              <th style="width: 250px;">Sesi Acara</th>
              <th>Isi Pertanyaan</th>
              <th style="width: 90px; text-align: center;">Aksi</th>
            </tr>
          </thead>
          <tbody id="qnaTableBody">
            <?php if (empty($questions)): ?>
              <tr>
                <td colspan="6" style="text-align: center; padding: 60px 20px;">
                  <div class="qna-empty-title">Belum Ada Pertanyaan Masuk</div>
                  <div class="qna-empty-desc">Pertanyaan dari audiens akan otomatis muncul di sini.</div>
                </td>
              </tr>
            <?php else: ?>
              <?php foreach ($questions as $idx => $q): ?>
                <?php 
                  $initial = strtoupper(mb_substr($q['asker_name'] ?? '?', 0, 1));
                  $dayText = !empty($q['day_number']) ? ('Day ' . esc($q['day_number']) . ' &bull; ') : '';
                  $sessionTime = !empty($q['session_time']) ? esc($q['session_time']) : '';
                ?>
                <tr data-index="<?= $idx ?>" data-id="<?= esc($q['id']) ?>">
                  <td style="text-align: center; color: #94a3b8; font-weight: 700;"><?= $idx + 1 ?></td>
                  <td style="color: var(--green-7, #3e8127); font-weight: 600; white-space: nowrap;"><?= esc($q['time_ago'] ?? 'Baru saja') ?></td>
                  <td>
                    <div class="qna-table-asker">
                      <span class="qna-asker-avatar" style="width: 28px; height: 28px; font-size: 11.5px;"><?= esc($initial) ?></span>
                      <span><?= esc($q['asker_name']) ?></span>
                    </div>
                  </td>
                  <td>
                    <div style="font-weight: 700; font-size: 13.5px; color: var(--green-10, #214415);"><?= esc($q['session_title'] ?? '-') ?></div>
                    <div style="font-size: 12px; color: #64748b;"><?= $dayText ?><?= $sessionTime ?></div>
                  </td>
                  <td>
                    <div class="qna-table-question"><?= nl2br(esc($q['question'])) ?></div>
                  </td>
                  <td style="text-align: center;">
                    <button type="button" class="qna-btn-focus" data-index="<?= $idx ?>" title="Focus Zoom Pertanyaan">
                      <svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M15 3h6v6M9 21H3v-6M21 3l-7 7M3 21l7-7"></path></svg>
                      <span>Focus</span>
                    </button>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>

    </div>
  </div>

  <?php /* Spotlight Focus Zoom Modal */ ?>
  <div id="qnaSpotlightModal" class="qna-spotlight-modal" aria-hidden="true">
    <div class="qna-spotlight-card">
      <button type="button" id="modalCloseBtn" class="qna-spotlight-close" title="Tutup (Esc)">
        <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
          <line x1="18" y1="6" x2="6" y2="18"></line>
          <line x1="6" y1="6" x2="18" y2="18"></line>
        </svg>
      </button>

      <div class="qna-spotlight-header">
        <div id="modalAskerAvatar" class="qna-spotlight-avatar">?</div>
        <div>
          <h2 id="modalAskerName" class="qna-spotlight-asker">Nama Penanya</h2>
          <div id="modalSessionInfo" class="qna-spotlight-session">Sesi Acara</div>
        </div>
      </div>

      <div id="modalQuestionText" class="qna-spotlight-body">
        Isi teks pertanyaan...
      </div>

      <div class="qna-spotlight-footer">
        <div class="qna-spotlight-counter">
          <span id="modalCounter">Pertanyaan 1 dari 1</span>
        </div>
        <div class="qna-spotlight-nav">
          <button type="button" id="btnPrevQuestion" class="qna-nav-btn">
            <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"></polyline></svg>
            <span>Prev</span>
          </button>
          <button type="button" id="btnNextQuestion" class="qna-nav-btn">
            <span>Next</span>
            <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"></polyline></svg>
          </button>
        </div>
      </div>
    </div>
  </div>

  <?php /* Dashboard Interactive Logic */ ?>
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      let currentQuestions = <?= json_encode($questions ?? []) ?>;
      let activeIndex = -1;
      let currentView = 'cards';
      let currentDayId = '<?= esc($filterDay ?? '') ?>';
      let currentSessionId = '<?= esc($filterSession ?? '') ?>';

      const qnaGrid = document.getElementById('qnaGrid');
      const qnaTableContainer = document.getElementById('qnaTableContainer');
      const qnaTableBody = document.getElementById('qnaTableBody');
      const filterDay = document.getElementById('filterDay');
      const filterSession = document.getElementById('filterSession');
      const viewCardBtn = document.getElementById('viewCardBtn');
      const viewTableBtn = document.getElementById('viewTableBtn');
      const totalCount = document.getElementById('totalQuestionsCount');

      const modal = document.getElementById('qnaSpotlightModal');
      const modalCloseBtn = document.getElementById('modalCloseBtn');
      const modalAskerAvatar = document.getElementById('modalAskerAvatar');
      const modalAskerName = document.getElementById('modalAskerName');
      const modalSessionInfo = document.getElementById('modalSessionInfo');
      const modalQuestionText = document.getElementById('modalQuestionText');
      const modalCounter = document.getElementById('modalCounter');
      const btnPrevQuestion = document.getElementById('btnPrevQuestion');
      const btnNextQuestion = document.getElementById('btnNextQuestion');

      function escapeHtml(text) {
        if (!text) return '';
        const map = { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' };
        return text.toString().replace(/[&<>"']/g, m => map[m]);
      }

      function truncate(str, max) {
        if (!str) return '';
        return str.length > max ? str.substr(0, max - 3) + '...' : str;
      }

      function setViewMode(mode) {
        currentView = mode;
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
              <span style="color: var(--green-7, #3e8127); font-weight: 700;">${escapeHtml(q.time_ago || 'Baru saja')}</span>
            </div>
          </div>
        `;
      }

      function createTableRowHtml(q, idx) {
        const initial = (q.asker_name || '?').charAt(0).toUpperCase();
        const dayText = q.day_number ? `Day ${escapeHtml(q.day_number)} &bull; ` : '';
        const sessionTimeText = q.session_time ? escapeHtml(q.session_time) : '';

        return `
          <tr data-index="${idx}" data-id="${escapeHtml(q.id)}">
            <td style="text-align: center; color: #94a3b8; font-weight: 700;">${idx + 1}</td>
            <td style="color: var(--green-7, #3e8127); font-weight: 600; white-space: nowrap;">${escapeHtml(q.time_ago || 'Baru saja')}</td>
            <td>
              <div class="qna-table-asker">
                <span class="qna-asker-avatar" style="width: 28px; height: 28px; font-size: 11.5px;">${escapeHtml(initial)}</span>
                <span>${escapeHtml(q.asker_name)}</span>
              </div>
            </td>
            <td>
              <div style="font-weight: 700; font-size: 13.5px; color: var(--green-10, #214415);">${escapeHtml(q.session_title || '-')}</div>
              <div style="font-size: 12px; color: #64748b;">${dayText}${sessionTimeText}</div>
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

      function renderAllViews(list) {
        currentQuestions = list;
        if (totalCount) totalCount.textContent = list.length;

        if (list.length === 0) {
          qnaGrid.innerHTML = `
            <div class="qna-empty-state" id="emptyStateCards">
              <svg class="qna-empty-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
                <line x1="12" y1="17" x2="12.01" y2="17"></line>
              </svg>
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

        qnaGrid.innerHTML = list.map((q, idx) => createCardHtml(q, idx)).join('');
        qnaTableBody.innerHTML = list.map((q, idx) => createTableRowHtml(q, idx)).join('');

        if (modal.classList.contains('show') && activeIndex >= 0) {
          if (activeIndex >= list.length) activeIndex = list.length - 1;
          showSpotlight(activeIndex);
        }
      }

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

      document.addEventListener('keydown', (e) => {
        if (!modal.classList.contains('show')) return;
        if (e.key === 'Escape') closeSpotlight();
        else if (e.key === 'ArrowLeft' && activeIndex > 0) showSpotlight(activeIndex - 1);
        else if (e.key === 'ArrowRight' && activeIndex < currentQuestions.length - 1) showSpotlight(activeIndex + 1);
      });

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

      setInterval(pollQuestions, 6000);
    });
  </script>
</body>
</html>
