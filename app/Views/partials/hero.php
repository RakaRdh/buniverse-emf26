<section class="hero-section" id="home">
  <?= view('partials/section_decorations', ['sectionKey' => 'home', 'decorations' => $sectionDecorations ?? []]) ?>
  <?= view('partials/custom_placements', ['sectionKey' => 'home', 'customPlacements' => $customPlacements ?? []]) ?>
  
  <!-- 1. Right Layer: Stage Image Carousel with smooth fade (z-index: 1) -->
  <div class="hero-bg-media">
    <div class="hero-graphic-burst"></div>
    <div class="hero-carousel" id="heroCarousel">
      <?php if (!empty($heroCarousel)): ?>
        <?php foreach ($heroCarousel as $cIdx => $slide): ?>
          <div class="hero-carousel-slide <?= $cIdx === 0 ? 'active' : '' ?>">
            <img src="<?= esc($slide['image_url']) ?>" alt="<?= esc($slide['caption'] ?: 'Investor Daily Summit 2026 Stage') ?>" class="hero-full-img">
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <div class="hero-carousel-slide active">
          <img src="<?= base_url('assets/img/image1.png') ?>" alt="Investor Daily Summit 2026 Stage 1" class="hero-full-img">
        </div>
        <div class="hero-carousel-slide">
          <img src="<?= base_url('assets/img/image2.jpg') ?>" alt="Investor Daily Summit 2026 Stage 2" class="hero-full-img">
        </div>
        <div class="hero-carousel-slide">
          <img src="<?= base_url('assets/img/image3.jpg') ?>" alt="Investor Daily Summit 2026 Stage 3" class="hero-full-img">
        </div>
      <?php endif; ?>
    </div>
  </div>

  <!-- 2. Left Layer: Trapesium Shape with Vertical Gradient per User Spec (z-index: 2) -->
  <div class="hero-trapezium" aria-hidden="true"></div>

  <!-- 3. Content Layer on top of Trapesium (z-index: 3) -->
  <div class="container hero-container">
    <div class="hero-content">
      
      <!-- Brand Logo IDS 2026 -->
      <div class="hero-brand">
        <?php $heroLogo = !empty($site['logo_a']) ? $site['logo_a'] : base_url('assets/img/logo_ids26.png'); ?>
        <img src="<?= esc($heroLogo) ?>" alt="<?= esc($site['title']) ?>" class="hero-brand-logo">
      </div>

      <!-- Topic Pill Badge -->
      <div class="topic-badge">
        <?= esc($site['theme']) ?>
      </div>

      <!-- Date & Venue -->
      <div class="hero-date">
        <?= esc($site['dates']) ?>
      </div>
      <div class="hero-venue">
        <?= esc($site['venue']) ?>
      </div>

    </div>
  </div>

  <!-- 4. Wavy Vector at bottom connecting to About section (z-index: 4 / highest) -->
  <div class="hero-wave-wrap" aria-hidden="true">
    <svg class="hero-wave-vector" viewBox="0 0 1440 151" preserveAspectRatio="none" fill="none" xmlns="http://www.w3.org/2000/svg">
      <path fill-rule="evenodd" clip-rule="evenodd" d="M0 96.0941L59.5406 79.807C119.081 63.5198 240.368 32.5743 359.449 37.4604C480.735 42.3466 599.816 84.6931 721.103 107.495C840.184 128.668 959.265 128.668 1080.55 107.495C1199.63 84.6931 1320.92 42.3466 1380.46 21.1733L1440 0L1440 151H1351.5C1291.96 151 1177.08 151 1058 151C936.714 151 853.581 151 734.5 151C613.214 151 495.194 151 373.908 151C254.827 151 133.541 151 74 151H0L0 96.0941Z" fill="var(--bg-section-alt, #052431)"/>
    </svg>
  </div>

</section>
