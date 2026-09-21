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
  <div class="editorial-wave-mask-wrap" aria-hidden="true">
    <div class="editorial-strip editorial-strip-1">
      <img src="<?= base_url('assets/img/gallery1.webp') ?>" alt="" loading="lazy">
    </div>
    <div class="editorial-strip editorial-strip-2">
      <img src="<?= base_url('assets/img/gallery2.webp') ?>" alt="" loading="lazy">
    </div>
    <div class="editorial-strip editorial-strip-3">
      <img src="<?= base_url('assets/img/gallery3.webp') ?>" alt="" loading="lazy">
    </div>
    <div class="editorial-strip editorial-strip-4">
      <img src="<?= base_url('assets/img/gallery4.webp') ?>" alt="" loading="lazy">
    </div>
  </div>
</section>
