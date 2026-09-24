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
          <?php 
            $overviewSlot = $site['logo_placements']['overview'] ?? null;
            $overviewSlotActive = !isset($overviewSlot['is_active']) || !empty($overviewSlot['is_active']);
            $overviewVariant = $overviewSlot['variant'] ?? 'auto';

            if ($overviewVariant === 'white') {
                $aboutLogo = !empty($site['logo_a_white']) ? $site['logo_a_white'] : base_url('assets/img/logo_ids26_white.webp');
            } else {
                $aboutLogo = !empty($site['logo_overview']) ? $site['logo_overview'] : (!empty($site['logo_a_color']) ? $site['logo_a_color'] : base_url('assets/img/logo_ids26_color.webp')); 
            }
            $aboutLogoTarget = !empty($site['logo_overview_target_url']) ? $site['logo_overview_target_url'] : null;
          ?>
          <?php if ($overviewSlotActive): ?>
            <?php if (!empty($aboutLogoTarget) && $aboutLogoTarget !== '#'): ?>
              <a href="<?= esc($aboutLogoTarget) ?>" <?= str_starts_with($aboutLogoTarget, 'http') ? 'target="_blank" rel="noopener noreferrer"' : '' ?> style="display:inline-block;">
                <img src="<?= esc($aboutLogo) ?>" alt="<?= esc($site['title']) ?>" class="editorial-brand-logo">
              </a>
            <?php else: ?>
              <img src="<?= esc($aboutLogo) ?>" alt="<?= esc($site['title']) ?>" class="editorial-brand-logo">
            <?php endif; ?>
          <?php endif; ?>
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
            <p><?= strip_tags($p, '<span><em><strong><i><b><u><br>') ?></p>
          <?php endforeach; ?>
        </div>
      </div>

    </div>
  </div>

  <!-- 3-Strip Slanted Photo Collage (Anchored directly to bottom left of section) -->
  <?php
    $collageImgs = $overviewData['collage_images'] ?? [];
    $img1 = !empty($collageImgs['img_1']) ? $collageImgs['img_1'] : base_url('assets/img/gallery1.webp');
    $img2 = !empty($collageImgs['img_2']) ? $collageImgs['img_2'] : base_url('assets/img/gallery2.webp');
    $img3 = !empty($collageImgs['img_3']) ? $collageImgs['img_3'] : (!empty($collageImgs['img_1']) ? $collageImgs['img_1'] : base_url('assets/img/gallery3.webp'));
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
  </div>
</section>
