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

  <!-- 2. Centered Content Group: logo_ids26.webp, tema, tanggal, lokasi -->
  <div class="container hero-container">
    <div class="hero-content hero-content-centered">
      
      <!-- Brand Logo IDS 2026 (logo_ids26.webp) -->
      <div class="hero-brand">
        <?php $heroLogo = !empty($site['logo_a']) ? $site['logo_a'] : base_url('assets/img/logo_ids26.webp'); ?>
        <img src="<?= esc($heroLogo) ?>" alt="<?= esc($site['title']) ?>" class="hero-brand-logo">
      </div>

      <!-- Topic / Theme -->
      <div class="hero-theme-text">
        <?= esc($site['theme'] ?? "INDONESIA'S ECONOMIC MODEL") ?>
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
</section>
