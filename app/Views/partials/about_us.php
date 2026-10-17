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
            $aboutLogo = base_url('assets/img/logo_green_emf26.webp');
          ?>
          <img src="<?= esc($aboutLogo) ?>" alt="Energi & Mineral Festival 2026" class="about-us-brand-logo editorial-brand-logo">
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
