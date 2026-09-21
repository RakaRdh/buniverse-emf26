<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php
    $metaTitle = !empty($seo['meta_title']) ? $seo['meta_title'] : ($site['title'] . ' — ' . $site['theme']);
    $metaDesc  = !empty($seo['meta_description']) ? $seo['meta_description'] : ($site['title'] . '. ' . $site['dates'] . ' di ' . $site['venue'] . '. Forum investasi terbesar di Indonesia.');
    $ogImage   = !empty($seo['og_image_url']) ? $seo['og_image_url'] : base_url('assets/img/image1.png');
  ?>
  <title><?= esc($metaTitle) ?></title>
  <meta name="description" content="<?= esc($metaDesc) ?>">
  <meta property="og:title" content="<?= esc($metaTitle) ?>">
  <meta property="og:description" content="<?= esc($metaDesc) ?>">
  <meta property="og:image" content="<?= esc($ogImage) ?>">
  <meta property="og:type" content="website">
  
  <!-- Google Fonts: Montserrat & Playfair Display -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,400;1,700&family=Playfair+Display:ital,wght@0,700;0,800;0,900;1,700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  
  <?php if (!empty($themeProperties)): ?>
    <!-- Live Dynamic Theme CSS Custom Properties from BackendCMS -->
    <style id="live-theme-properties">
      :root {
        <?php foreach ($themeProperties as $tp): ?>
          <?= esc($tp['property']) ?>: <?= $tp['value'] ?>;
        <?php endforeach; ?>
      }
    </style>
  <?php endif; ?>

  <!-- Core Vanilla CSS -->
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
  <!-- Tailwind Perintilan CSS -->
  <link rel="stylesheet" href="<?= base_url('assets/css/tailwind.css') ?>">
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

    <!-- 4. Schedule & Speakers Section -->
    <?= $this->include('partials/schedule/index') ?>

    <!-- 5. Gallery Section -->
    <?= $this->include('partials/gallery') ?>

    <!-- 6. Related News Section -->
    <?= $this->include('partials/related_news') ?>

    <!-- 6.1 Highlights (YouTube) Section -->
    <?= $this->include('partials/highlights') ?>

    <!-- 7. Sponsor & Partners Section -->
    <?= $this->include('partials/sponsors') ?>
  </main>

  <!-- 8. Footer -->
  <?= $this->include('partials/footer') ?>

  <!-- 9. Speaker Detail Modal -->
  <?= $this->include('partials/speaker_modal') ?>

  <!-- Interactive JavaScript -->
  <script src="<?= base_url('assets/js/main.js') ?>"></script>
</body>
</html>
