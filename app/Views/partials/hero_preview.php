<section class="hero-section hero-preview-section" id="home">
  <?php /* 1. Background Image Full 1 Section (hero-bg.webp) */ ?>
  <div class="hero-preview-bg" aria-hidden="true">
    <img src="<?= base_url('assets/img/hero-bg.webp') ?>" alt="" class="hero-preview-bg-img">
    <div class="hero-preview-overlay"></div>
  </div>

  <?php /* 2. Logo ESDM White (Posisi Kiri Atas seperti existing) */ ?>
  <div class="hero-partner-badge">
    <img src="<?= base_url('assets/img/esdm-white.webp') ?>" alt="Kementerian Energi dan Sumber Daya Mineral" class="hero-partner-img">
  </div>

  <?php /* 3. Kombo Tengah: Ribbon Berputar + Teks Tengah + Hero Bottom Text */ ?>
  <div class="container hero-preview-container">
    <div class="hero-preview-center-wrap">
      
      <?php /* Emblem Kombo: Ribbon Berputar di belakang + emftxt-white.webp di depan */ ?>
      <div class="hero-emblem-combo" aria-label="Energi Mineral Festival 2026">
        <div class="hero-ribbon-spin-wrap" aria-hidden="true">
          <img src="<?= base_url('assets/img/ribbon.webp') ?>" alt="" class="hero-ribbon-spin-img">
        </div>
        <div class="hero-emftxt-wrap">
          <img src="<?= base_url('assets/img/emftxt-white.webp') ?>" alt="Energi Mineral Festival 2026 - Connecting & Shaping the Future of Energy" class="hero-emftxt-img">
        </div>
      </div>

      <?php /* Hero Bottom Graphic Text (hero-btm.webp) */ ?>
      <div class="hero-btm-wrap">
        <img src="<?= base_url('assets/img/hero-btm.webp') ?>" alt="Energi Mineral Festival 2026 - Membangun Nilai, Menyalakan Masa Depan - JICC 10-11 November 2026" class="hero-btm-img">
      </div>

    </div>
  </div>

  <?php /* 4. Scroll Indicator (Existing) di Tengah Bawah */ ?>
  <div class="hero-scroll-indicator" id="heroScrollIndicator" aria-hidden="true">
    <svg class="hero-scroll-icon" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
      <circle cx="24" cy="24" r="22.5" stroke="#ffffff" stroke-width="1.8"/>
      <path d="M24 16V31M24 31L18 24.5M24 31L30 24.5" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
    </svg>
  </div>

  <?php 
    /* 5. Countdown di Sisi Kanan Bawah Hero Section */ 
    $targetTimestamp = !empty($countdownTargetDate) ? strtotime($countdownTargetDate) : strtotime('2026-11-10T09:00:00+07:00');
    $isCountdownPassed = ($targetTimestamp !== false && $targetTimestamp <= time());
  ?>
  <?php if (!$isCountdownPassed): ?>
  <div class="hero-preview-countdown-corner" id="heroCountdown" data-target-date="<?= esc($countdownTargetDate ?? '2026-11-10T09:00:00+07:00') ?>">
    <div class="hero-countdown-wrap hero-countdown-corner-wrap">
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

</section>
