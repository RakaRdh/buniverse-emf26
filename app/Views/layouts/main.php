<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= esc($site['title']) ?> — <?= esc($site['theme']) ?></title>
  <meta name="description" content="<?= esc($site['title']) ?>. <?= esc($site['dates']) ?> di <?= esc($site['venue']) ?>. Forum investasi terbesar di Indonesia.">
  
  <!-- Google Fonts: Montserrat & Playfair Display -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,400;1,700&family=Playfair+Display:ital,wght@0,700;0,800;0,900;1,700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  
  <!-- Core Vanilla CSS -->
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
  <!-- Tailwind Perintilan CSS -->
  <link rel="stylesheet" href="<?= base_url('assets/css/tailwind.css') ?>">

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
</head>
<body>

  <!-- 1. Header / Navigation -->
  <?= $this->include('partials/header') ?>

  <!-- Main Content Body -->
  <main id="mainContent">
    <!-- 2. Hero Section -->
    <?= $this->include('partials/hero') ?>

    <!-- 3. Indonesia's Economic Model Editorial -->
    <?= $this->include('partials/economic_model') ?>

    <!-- 4. Schedule & Speakers Section -->
    <?= $this->include('partials/schedule/index') ?>

    <!-- 5. Gallery Section -->
    <?= $this->include('partials/gallery') ?>

    <!-- 6. Related News Section -->
    <?= $this->include('partials/related_news') ?>

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
