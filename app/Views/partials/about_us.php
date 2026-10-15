<?php
$overviewData = $overview ?? $economicModel ?? [];
$paragraphs = $overviewData['paragraphs'] ?? [];
?>
<section class="about-us-section editorial-section" id="about-us">
  <?= view('partials/section_decorations', ['sectionKey' => 'about-us', 'decorations' => $sectionDecorations ?? []]) ?>
  <?= view('partials/custom_placements', ['sectionKey' => 'about-us', 'customPlacements' => $customPlacements ?? []]) ?>
  <div class="container">
    <div class="about-us-grid editorial-grid">
      
      <?php /* Left Column: About Us Logo & Intro */ ?>
      <div class="about-us-left editorial-left">
        <div class="about-us-logo editorial-logo">
          <?php 
            $overviewSlot = $site['logo_placements']['overview'] ?? null;
            $overviewSlotActive = !isset($overviewSlot['is_active']) || !empty($overviewSlot['is_active']);
            $overviewVariant = $overviewSlot['variant'] ?? 'auto';

            if ($overviewVariant === 'white') {
                $aboutLogo = !empty($site['logo_a_white']) ? $site['logo_a_white'] : base_url('assets/img/logo_green_emf26.webp');
            } else {
                $aboutLogo = !empty($site['logo_overview']) ? $site['logo_overview'] : (!empty($site['logo_a_color']) ? $site['logo_a_color'] : base_url('assets/img/logo_green_emf26.webp')); 
            }
            $aboutLogoTarget = !empty($site['logo_overview_target_url']) ? $site['logo_overview_target_url'] : null;
          ?>
          <?php if ($overviewSlotActive): ?>
            <img src="<?= esc($aboutLogo) ?>" alt="<?= esc($site['title']) ?>" class="about-us-brand-logo editorial-brand-logo">
          <?php endif; ?>
        </div>

        <p class="about-us-intro editorial-intro">
          <?= esc(!empty($overviewData['intro']) ? $overviewData['intro'] : "Energi & Mineral Festival 2026 merupakan forum strategis nasional yang mempertemukan para pemangku kepentingan di sektor energi dan sumber daya mineral untuk mendorong terwujudnya swasembada energi berkelanjutan di Indonesia.") ?>
        </p>
      </div>

      <?php /* Right Column: Topic Badge & Paragraphs */ ?>
      <div class="about-us-right editorial-right">
        <div class="topic-badge">
          <?= esc(!empty($overviewData['badge']) && $overviewData['badge'] !== "INDONESIA'S ECONOMIC MODEL" ? $overviewData['badge'] : "ABOUT US") ?>
        </div>

        <div class="editorial-paragraphs">
          <?php foreach ($paragraphs as $p): ?>
            <p><?= strip_tags($p, '<span><em><strong><i><b><u><br>') ?></p>
          <?php endforeach; ?>
        </div>
      </div>

    </div>
  </div>

  <?php /* Slanted Photo Collage */ ?>
  <div class="editorial-wave-mask-wrap" aria-hidden="true">
    <img src="<?= base_url('assets/img/overview_kolase.webp') ?>" alt="Overview collage" class="editorial-collage-img" loading="lazy">
  </div>
</section>
