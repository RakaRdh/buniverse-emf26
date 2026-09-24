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
            <img src="<?= esc($aboutLogo) ?>" alt="<?= esc($site['title']) ?>" class="editorial-brand-logo">
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

  <!-- Slanted Photo Collage (Anchored to bottom of section, left aligned with content) -->
  <div class="editorial-wave-mask-wrap" aria-hidden="true">
    <img src="<?= base_url('assets/img/overview_kolase.webp') ?>" alt="Overview collage" class="editorial-collage-img" loading="lazy">
  </div>
</section>
