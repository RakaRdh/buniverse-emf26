<section class="hero-section hero-festival-section" id="home">
  <?= view('partials/section_decorations', ['sectionKey' => 'home', 'decorations' => $sectionDecorations ?? []]) ?>
  <?= view('partials/custom_placements', ['sectionKey' => 'home', 'customPlacements' => $customPlacements ?? []]) ?>

  <?php /* 1. Pure Hero Background Canvas with Warm Yellow Base Gradient */ ?>
  <div class="hero-festival-bg" aria-hidden="true"></div>

  <?php /* 2. Hero Partner / Kementerian Logo (Sisi Kiri Atas Hero) */ ?>
  <div class="hero-partner-badge">
    <img src="<?= base_url('assets/img/esdm.webp') ?>" alt="Kementerian Energi dan Sumber Daya Mineral" class="hero-partner-img">
  </div>

  <?php /* 3. Main Hero Content Grid (Left: Spinning Ribbon + EMF Text; Right: Typography & Countdown) */ ?>
  <div class="container hero-festival-container">
    <div class="hero-festival-grid">

      <?php /* SISI KIRI: Kombo Emblem Melingkar (Ribbon Berputar + emftxt-green.webp) */ ?>
      <div class="hero-festival-left">
        <div class="hero-emblem-combo" aria-label="Energi Mineral Festival 2026">
          <div class="hero-ribbon-spin-wrap" aria-hidden="true">
            <img src="<?= base_url('assets/img/ribbon.webp') ?>" alt="" class="hero-ribbon-spin-img">
          </div>
          <div class="hero-emftxt-wrap">
            <img src="<?= base_url('assets/img/emftxt-green.webp') ?>" alt="Energi Mineral Festival 2026 - Connecting & Shaping the Future of Energy" class="hero-emftxt-img">
          </div>
        </div>
      </div>

      <?php /* SISI KANAN: Headline Anton, Garis Hijau, Venue Manrope, dan Countdown */ ?>
      <div class="hero-festival-right">
        <div class="hero-festival-typography">
          <h1 class="hero-festival-title">
            <span class="hero-line">MEMBANGUN NILAI,</span>
            <span class="hero-line">MENYALAKAN MASA DEPAN</span>
          </h1>

          <div class="hero-festival-divider" aria-hidden="true"></div>

          <div class="hero-festival-venue">
            <p class="hero-venue-hall">Cendrawasih Hall,</p>
            <p class="hero-venue-center">Jakarta International Convention Center</p>
            <p class="hero-venue-date">10–11 November 2026</p>
          </div>
        </div>

        <?php 
          /* Countdown Timer (Stylenya pakai seperti yang sekarang) */ 
          $targetTimestamp = !empty($countdownTargetDate) ? strtotime($countdownTargetDate) : strtotime('2026-11-10T09:00:00+07:00');
          $isCountdownPassed = ($targetTimestamp !== false && $targetTimestamp <= time());
        ?>
        <?php if (!$isCountdownPassed): ?>
        <div class="hero-festival-countdown-area">
          <div class="hero-countdown-wrap hero-festival-countdown-card" id="heroCountdown" data-target-date="<?= esc($countdownTargetDate ?? '2026-11-10T09:00:00+07:00') ?>">
            <div class="hero-countdown-timer">
              <div class="hero-countdown-unit" data-unit="days">
                <div class="hero-roller-box" id="rollDays">
                  <div class="hero-roller-col" data-col="t"><span class="hero-roller-digit active">0</span></div>
                  <div class="hero-roller-col" data-col="u"><span class="hero-roller-digit active">0</span></div>
                </div>
                <span class="hero-countdown-sub">Hari</span>
              </div>
              <div class="hero-countdown-sep">:</div>
              <div class="hero-countdown-unit" data-unit="hours">
                <div class="hero-roller-box" id="rollHours">
                  <div class="hero-roller-col" data-col="t"><span class="hero-roller-digit active">0</span></div>
                  <div class="hero-roller-col" data-col="u"><span class="hero-roller-digit active">0</span></div>
                </div>
                <span class="hero-countdown-sub">Jam</span>
              </div>
              <div class="hero-countdown-sep">:</div>
              <div class="hero-countdown-unit" data-unit="minutes">
                <div class="hero-roller-box" id="rollMinutes">
                  <div class="hero-roller-col" data-col="t"><span class="hero-roller-digit active">0</span></div>
                  <div class="hero-roller-col" data-col="u"><span class="hero-roller-digit active">0</span></div>
                </div>
                <span class="hero-countdown-sub">Menit</span>
              </div>
              <div class="hero-countdown-sep">:</div>
              <div class="hero-countdown-unit" data-unit="seconds">
                <div class="hero-roller-box" id="rollSeconds">
                  <div class="hero-roller-col" data-col="t"><span class="hero-roller-digit active">0</span></div>
                  <div class="hero-roller-col" data-col="u"><span class="hero-roller-digit active">0</span></div>
                </div>
                <span class="hero-countdown-sub">Detik</span>
              </div>
            </div>
          </div>
        </div>
        <?php endif; ?>

      </div>

    </div>
  </div>

  <?php /* 4. Hero Bottom Graphic Graphic Elements (Sticks to the very bottom) */ ?>
  <div class="hero-festival-bottom" aria-hidden="true">
    <img src="<?= base_url('assets/img/hero-bottom.webp') ?>" alt="" class="hero-festival-bottom-img">
  </div>

  <?php /* 5. Scroll Indicator di Tengah Bawah */ ?>
  <div class="hero-scroll-indicator" id="heroScrollIndicator" aria-hidden="true">
    <svg class="hero-scroll-icon" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
      <circle cx="24" cy="24" r="22.5" stroke="#2a5d24" stroke-width="1.8"/>
      <path d="M24 16V31M24 31L18 24.5M24 31L30 24.5" stroke="#2a5d24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
    </svg>
  </div>
</section>
