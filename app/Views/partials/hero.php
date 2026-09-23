<section class="hero-section" id="home">
  <!-- 1. Pure CSS Hero Background Canvas (Base #010A2D with ambient royal blue glow #0C2F88 at top-left) -->
  <div class="hero-bg-canvas" aria-hidden="true"></div>

  <!-- Hero Hardcoded Decorative Discs -->
  <div class="hero-deco-wrap" aria-hidden="true">
    <!-- Deco A: Top Left Subtle Accent -->
    <div class="hero-deco-item hero-deco-topleft">
      <img src="<?= base_url('assets/img/decoration.png') ?>" alt="" class="hero-deco-img">
    </div>

    <!-- Deco B: Center-Right Perspective 3D Spinning Disc wrapped with static Luminance mask -->
    <div class="hero-deco-main-mask">
      <div class="hero-deco-item hero-deco-main-perspective">
        <div class="hero-deco-tilt">
          <div class="hero-deco-spin">
            <img src="<?= base_url('assets/img/decoration.png') ?>" alt="" class="hero-deco-img">
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- 2. Centered Content Group: Bundle single image hero.png -->
  <div class="container hero-container">
    <div class="hero-content hero-content-centered">
      <div class="hero-bundle-wrap">
        <img src="<?= base_url('assets/img/hero.png') ?>" alt="<?= esc($site['title'] ?? 'Investor Daily Summit 2026') ?>" class="hero-bundle-img">
      </div>
    </div>
  </div>

  <!-- Mobile Scroll Indicator (Polos, statis, menghilang saat scroll ke atas sedikitpun) -->
  <div class="hero-scroll-indicator" id="heroScrollIndicator" aria-hidden="true">
    <svg class="hero-scroll-icon" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
      <circle cx="24" cy="24" r="22.5" stroke="#ffffff" stroke-width="1.8"/>
      <path d="M24 16V31M24 31L18 24.5M24 31L30 24.5" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
    </svg>
  </div>
</section>
