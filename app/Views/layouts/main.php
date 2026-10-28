<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php
    $metaTitle = !empty($seo['meta_title']) ? $seo['meta_title'] : $site['title'];
    $metaDesc  = !empty($seo['meta_description']) ? $seo['meta_description'] : ($site['title'] . '. ' . $site['dates'] . ' di ' . $site['venue'] . '. Forum investasi terbesar di Indonesia.');
    $ogImage   = !empty($seo['og_image_url']) ? $seo['og_image_url'] : base_url('assets/img/image1.png');
  ?>
  <title><?= esc($metaTitle) ?></title>
  <meta name="description" content="<?= esc($metaDesc) ?>">
  <meta property="og:title" content="<?= esc($metaTitle) ?>">
  <meta property="og:description" content="<?= esc($metaDesc) ?>">
  <meta property="og:image" content="<?= esc($ogImage) ?>">
  <meta property="og:type" content="website">
  
  <?php /* Favicon */ ?>
  <link rel="shortcut icon" type="image/x-icon" href="<?= base_url('favicon.ico') ?>?v=<?= file_exists(FCPATH . 'favicon.ico') ? filemtime(FCPATH . 'favicon.ico') : time() ?>">
  <link rel="icon" type="image/x-icon" href="<?= base_url('favicon.ico') ?>?v=<?= file_exists(FCPATH . 'favicon.ico') ? filemtime(FCPATH . 'favicon.ico') : time() ?>">
  
  <?php /* Google Fonts: Anton, Manrope & Playfair Display */ ?>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Anton&family=Manrope:wght@300;400;500;600;700;800&family=Playfair+Display:ital,wght@0,700;0,800;0,900;1,700&display=swap" rel="stylesheet">
  
  <?php /* Core Vanilla CSS */ ?>
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>?v=<?= file_exists(FCPATH . 'assets/css/style.css') ? filemtime(FCPATH . 'assets/css/style.css') : time() ?>">
  <?php /* Tailwind Perintilan CSS */ ?>
  <link rel="stylesheet" href="<?= base_url('assets/css/tailwind.css') ?>?v=<?= file_exists(FCPATH . 'assets/css/tailwind.css') ? filemtime(FCPATH . 'assets/css/tailwind.css') : time() ?>">

  <?php if (!empty($themeProperties)): ?>
    <?php /* Live Dynamic Theme CSS Custom Properties from BackendCMS */ ?>
    <style id="live-theme-properties">
      :root {
        <?php foreach ($themeProperties as $tp): ?>
          <?= esc($tp['property']) ?>: <?= $tp['value'] ?>;
        <?php endforeach; ?>
      }
    </style>
  <?php endif; ?>
</head>
<body>

  <?php /* 1. Header / Navigation */ ?>
  <?= $this->include('partials/header') ?>

  <?php /* Main Content Body */ ?>
  <main id="mainContent">
    <?php /* 2. Hero Section (Mendukung Preview Template Baru via Route /preview-hero & /preview-hero2) */ ?>
    <?php
      $heroTemplate = 'partials/hero';
      if (!empty($heroView)) {
        if ($heroView === 'preview') {
          $heroTemplate = 'partials/hero_preview';
        } elseif ($heroView === 'preview2') {
          $heroTemplate = 'partials/hero_preview2';
        }
      }
    ?>
    <?= $this->include($heroTemplate) ?>

    <?php /* 3. About Us Section (Energi & Mineral Festival) */ ?>
    <?= $this->include('partials/about_us') ?>

    <?php /* 4. Live Streaming (YouTube Embed) Section */ ?>
    <?= $this->include('partials/livestream') ?>

    <?php /* 5. Schedule & Speakers Section */ ?>
    <?= $this->include('partials/schedule/index') ?>

    <?php /* 5.1 Highlights (YouTube) Section */ ?>
    <?php if (!empty($highlights['is_active']) && !empty($highlights['items'])): ?>
      <?= $this->include('partials/highlights') ?>
    <?php endif; ?>

    <?php /* 6. Gallery Section */ ?>
    <?= $this->include('partials/gallery') ?>

    <?php /* 6.1 Overview (Summary Cards) Section */ ?>
    <?= $this->include('partials/summary') ?>

    <?php /* 7. Related News Section */ ?>
    <?= $this->include('partials/related_news') ?>

    <?php /* 8. Sponsor & Partners Section */ ?>
    <?= $this->include('partials/sponsors') ?>

    <?php /* 8.1 Locations & Floor Plan Section */ ?>
    <?= $this->include('partials/locations') ?>
  </main>

  <?php /* 8. Footer */ ?>
  <?= $this->include('partials/footer') ?>

  <?php /* 9. Speaker Detail Modal */ ?>
  <?= $this->include('partials/speaker_modal') ?>

  <?php /* 9.1 Gallery Lightbox Modal */ ?>
  <?= $this->include('partials/gallery_modal') ?>

  <?php /* 9.2 Persistent Floating Bottom Action Bar */ ?>
  <?= $this->include('partials/floating_bottom') ?>

  <?php /* 10. Floating Back to Top Button */ ?>
  <button type="button" class="back-to-top-btn" id="backToTopBtn" aria-label="Kembali ke atas">
    <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
      <line x1="12" y1="19" x2="12" y2="5"></line>
      <polyline points="5 12 12 5 19 12"></polyline>
    </svg>
  </button>

  <?php /* Interactive JavaScript */ ?>
  <script src="<?= base_url('assets/js/main.js') ?>?v=<?= file_exists(FCPATH . 'assets/js/main.js') ? filemtime(FCPATH . 'assets/js/main.js') : time() ?>"></script>
</body>
</html>
