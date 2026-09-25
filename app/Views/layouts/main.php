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
  
  <!-- Favicon -->
  <link rel="shortcut icon" type="image/x-icon" href="<?= base_url('favicon.ico') ?>?v=<?= file_exists(FCPATH . 'favicon.ico') ? filemtime(FCPATH . 'favicon.ico') : time() ?>">
  <link rel="icon" type="image/x-icon" href="<?= base_url('favicon.ico') ?>?v=<?= file_exists(FCPATH . 'favicon.ico') ? filemtime(FCPATH . 'favicon.ico') : time() ?>">
  
  <!-- Google Fonts: Manrope & Playfair Display -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@300;400;500;600;700;800&family=Playfair+Display:ital,wght@0,700;0,800;0,900;1,700&display=swap" rel="stylesheet">
  
  <!-- Core Vanilla CSS -->
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
  <!-- Tailwind Perintilan CSS -->
  <link rel="stylesheet" href="<?= base_url('assets/css/tailwind.css') ?>">

  <?php if (!empty($themeProperties)): ?>
    <!-- Live Dynamic Theme CSS Custom Properties from BackendCMS (Placed after style.css to override defaults) -->
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

  <!-- 1. Header / Navigation -->
  <?= $this->include('partials/header') ?>

  <!-- Main Content Body -->
  <main id="mainContent">
    <!-- 2. Hero Section -->
    <?= $this->include('partials/hero') ?>

    <!-- 3. Overview (Indonesia's Economic Model Editorial) -->
    <?= $this->include('partials/overview') ?>

    <!-- 4. Live Streaming (YouTube Embed) Section -->
    <?= $this->include('partials/livestream') ?>

    <!-- 5. Schedule & Speakers Section -->
    <?= $this->include('partials/schedule/index') ?>

    <!-- 6. Gallery Section -->
    <?= $this->include('partials/gallery') ?>

    <!-- 7. Related News Section -->
    <?= $this->include('partials/related_news') ?>

    <!-- 7.1 Highlights (YouTube) Section -->
    <?php if (!empty($highlights['is_active']) && !empty($highlights['items'])): ?>
      <?= $this->include('partials/highlights') ?>
    <?php endif; ?>


    <!-- 8. Sponsor & Partners Section -->
    <?= $this->include('partials/sponsors') ?>

    <!-- 8.1 Locations & Floor Plan Section -->
    <?= $this->include('partials/locations') ?>
  </main>

  <!-- 8. Footer -->
  <?= $this->include('partials/footer') ?>

  <!-- 9. Speaker Detail Modal -->
  <?= $this->include('partials/speaker_modal') ?>

  <!-- 9.1 Gallery Lightbox Modal -->
  <?= $this->include('partials/gallery_modal') ?>

  <!-- 9.2 Persistent Floating Bottom Action Bar -->
  <?= $this->include('partials/floating_bottom') ?>

  <!-- 10. Floating Back to Top Button -->
  <button type="button" class="back-to-top-btn" id="backToTopBtn" aria-label="Kembali ke atas">
    <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
      <line x1="12" y1="19" x2="12" y2="5"></line>
      <polyline points="5 12 12 5 19 12"></polyline>
    </svg>
  </button>

  <!-- Interactive JavaScript -->
  <script src="<?= base_url('assets/js/main.js') ?>"></script>
</body>
</html>
