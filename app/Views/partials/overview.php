<?php
$overviewData = $overview ?? $economicModel ?? [];
$paragraphs = $overviewData['paragraphs'] ?? [];
?>
<section class="editorial-section" id="about-us">
  <?= view('partials/section_decorations', ['sectionKey' => 'about-us', 'decorations' => $sectionDecorations ?? []]) ?>
  <?= view('partials/custom_placements', ['sectionKey' => 'about-us', 'customPlacements' => $customPlacements ?? []]) ?>
  <div class="container">
    <div class="editorial-grid">
      
      <!-- Left Column: Editorial Logo & Intro -->
      <div class="editorial-left">
        <div class="editorial-logo">
          <?php $aboutLogo = !empty($site['logo_a']) ? $site['logo_a'] : base_url('assets/img/logo_ids26.webp'); ?>
          <img src="<?= esc($aboutLogo) ?>" alt="<?= esc($site['title']) ?>" class="editorial-brand-logo">
        </div>

        <p class="editorial-intro">
          <?= esc($overviewData['intro'] ?? '') ?>
        </p>
      </div>

      <!-- Right Column: Topic Badge & Editorial Paragraphs -->
      <div class="editorial-right">
        <div class="topic-badge">
          <?= esc($overviewData['badge'] ?? "INDONESIA'S ECONOMIC MODEL") ?>
        </div>

        <div class="editorial-paragraphs">
          <?php foreach ($paragraphs as $p): ?>
            <p><?= esc($p) ?></p>
          <?php endforeach; ?>
        </div>
      </div>

    </div>
  </div>

  <!-- 4-Strip Slanted Photo Collage (Anchored directly to bottom left of section) -->
  <?php
    $collageImgs = $overviewData['collage_images'] ?? [];
    $img1 = !empty($collageImgs['img_1']) ? $collageImgs['img_1'] : base_url('assets/img/gallery1.webp');
    $img2 = !empty($collageImgs['img_2']) ? $collageImgs['img_2'] : base_url('assets/img/gallery2.webp');
    $img3 = !empty($collageImgs['img_3']) ? $collageImgs['img_3'] : base_url('assets/img/gallery3.webp');
    $img4 = !empty($collageImgs['img_4']) ? $collageImgs['img_4'] : base_url('assets/img/gallery4.webp');
  ?>
  <div class="editorial-wave-mask-wrap" aria-hidden="true">
    <div class="editorial-strip editorial-strip-1">
      <img src="<?= esc($img1) ?>" alt="Overview collage 1" loading="lazy">
    </div>
    <div class="editorial-strip editorial-strip-2">
      <img src="<?= esc($img2) ?>" alt="Overview collage 2" loading="lazy">
    </div>
    <div class="editorial-strip editorial-strip-3">
      <img src="<?= esc($img3) ?>" alt="Overview collage 3" loading="lazy">
    </div>
    <div class="editorial-strip editorial-strip-4">
      <img src="<?= esc($img4) ?>" alt="Overview collage 4" loading="lazy">
    </div>
  </div>
</section>
