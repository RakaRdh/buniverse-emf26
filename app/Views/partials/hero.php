<section class="hero-section" id="home">
  <?= view('partials/section_decorations', ['sectionKey' => 'home', 'decorations' => $sectionDecorations ?? []]) ?>
  <?= view('partials/custom_placements', ['sectionKey' => 'home', 'customPlacements' => $customPlacements ?? []]) ?>
  <?php /* 1. Pure CSS Hero Background Canvas */ ?>
  <div class="hero-bg-canvas" aria-hidden="true"></div>

  <?php /* Hero Hardcoded Decorative Discs */ ?>
  <div class="hero-deco-wrap" aria-hidden="true">
    <?php /* Deco A: Top Left Subtle Accent */ ?>
    <div class="hero-deco-item hero-deco-topleft">
      <img src="<?= base_url('assets/img/ribbon.png') ?>" alt="" class="hero-deco-img">
    </div>

    <?php /* Deco B: Center-Right Perspective 3D Spinning Disc */ ?>
    <div class="hero-deco-main-mask">
      <div class="hero-deco-item hero-deco-main-perspective">
        <div class="hero-deco-tilt">
          <div class="hero-deco-spin">
            <img src="<?= base_url('assets/img/ribbon.png') ?>" alt="" class="hero-deco-img">
          </div>
        </div>
      </div>
    </div>
  </div>

  <?php /* 2. Centered Content Group: Bundle single image */ ?>
  <div class="container hero-container">
    <div class="hero-content hero-content-centered">
      <div class="hero-bundle-wrap">
        <img src="<?= base_url('assets/img/logo_green_emf26.webp') ?>" alt="<?= esc($site['title'] ?? 'Investor Daily Summit 2026') ?>" class="hero-bundle-img">
      </div>
    </div>
  </div>

  <?php /* Mobile Scroll Indicator */ ?>
  <div class="hero-scroll-indicator" id="heroScrollIndicator" aria-hidden="true">
    <svg class="hero-scroll-icon" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
      <circle cx="24" cy="24" r="22.5" stroke="#ffffff" stroke-width="1.8"/>
      <path d="M24 16V31M24 31L18 24.5M24 31L30 24.5" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
    </svg>
  </div>
</section>
