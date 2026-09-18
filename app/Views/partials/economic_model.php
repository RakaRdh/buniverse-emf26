<section class="editorial-section" id="about-us">
  <div class="container">
    <div class="editorial-grid">
      
      <!-- Left Column: Editorial Logo & Intro -->
      <div class="editorial-left">
        <div class="editorial-logo">
          <img src="<?= base_url('assets/img/logo_ids26.png') ?>" alt="Investor Daily Summit 2026" class="editorial-brand-logo">
        </div>

        <p class="editorial-intro">
          <?= esc($economicModel['intro']) ?>
        </p>
      </div>

      <!-- Right Column: Topic Badge & 3 Editorial Paragraphs -->
      <div class="editorial-right">
        <div class="topic-badge">
          <?= esc($economicModel['badge']) ?>
        </div>

        <div class="editorial-paragraphs">
          <?php foreach ($economicModel['paragraphs'] as $p): ?>
            <p><?= esc($p) ?></p>
          <?php endforeach; ?>
        </div>
      </div>

    </div>
  </div>

  <!-- Vector-Masked Stage Image at bottom left corner -->
  <div class="editorial-wave-mask-wrap" aria-hidden="true">
    <svg width="0" height="0" style="position: absolute;">
      <defs>
        <clipPath id="waveAboutClip" clipPathUnits="objectBoundingBox">
          <!-- 645x199 normalized coordinates (x/645, y/199) -->
          <path d="M1 1 L0.9725 0.8762 C0.9442 0.7523 0.8892 0.5047 0.8333 0.3455 C0.7775 0.1863 0.7225 0.1155 0.6667 0.0978 C0.6108 0.0801 0.5558 0.1155 0.5 0.1155 C0.4442 0.1155 0.3892 0.0801 0.3333 0.0448 C0.2775 0.0094 0.2225 -0.0260 0.1667 0.0271 C0.1108 0.0801 0.0558 0.2217 0.0275 0.2924 L0 0.3632 V1 H0.0275 C0.0558 1 0.1108 1 0.1667 1 C0.2225 1 0.2775 1 0.3333 1 C0.3892 1 0.4442 1 0.5 1 C0.5558 1 0.6108 1 0.6667 1 C0.7225 1 0.7775 1 0.8333 1 C0.8892 1 0.9442 1 0.9725 1 H1 Z" />
        </clipPath>
      </defs>
    </svg>
    <div class="editorial-wave-img-box">
      <img src="<?= base_url('assets/img/image1.png') ?>" alt="" class="editorial-wave-img">
    </div>
  </div>
</section>
