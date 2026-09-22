<header class="site-header" id="header">
  <div class="container">
    <div class="header-inner">
      
      <!-- Dual-logo brand lockup dengan URL terpisah untuk masing-masing logo -->
      <div class="brand-lockup">
        <!-- Logo 1: INVESTOR DAILY SUMMIT 2026 -->
        <?php 
          $slot1 = $site['logo_placements']['navbar_slot_1'] ?? null;
          $slot1Active = !isset($slot1['is_active']) || !empty($slot1['is_active']);
          $slot1Variant = $slot1['variant'] ?? 'auto';

          $logoAUrl = !empty($site['logo_a_target_url']) ? $site['logo_a_target_url'] : '#home';
          if ($slot1Variant === 'color') {
              $logoA = !empty($site['logo_a_color']) ? $site['logo_a_color'] : (!empty($site['logo_a']) ? $site['logo_a'] : base_url('assets/img/logo_ids26_color.webp'));
          } else {
              // auto or white: header is dark background, use white variant
              $logoA = !empty($site['logo_a_white']) ? $site['logo_a_white'] : (!empty($site['logo_a']) ? $site['logo_a'] : base_url('assets/img/logo_ids26_white.webp'));
          }
        ?>
        <?php if ($slot1Active): ?>
          <a href="<?= esc($logoAUrl) ?>" class="brand-logo-link brand-logo-link-a" aria-label="Investor Daily Summit 2026">
            <img src="<?= esc($logoA) ?>" alt="Investor Daily Summit 2026" class="brand-logo-ids">
          </a>
        <?php endif; ?>

        <!-- Vertical Divider -->
        <?php 
          $slot2 = $site['logo_placements']['navbar_slot_2'] ?? null;
          $slot2Active = !isset($slot2['is_active']) || !empty($slot2['is_active']);
        ?>
        <?php if ($slot1Active && $slot2Active): ?>
          <div class="brand-divider" aria-hidden="true"></div>
        <?php endif; ?>

        <!-- Logo 2: INVESTOR DAILY INDONESIA -->
        <?php 
          $slot2Variant = $slot2['variant'] ?? 'auto';
          $logoBUrl = !empty($site['logo_b_target_url']) ? $site['logo_b_target_url'] : 'https://investor.id';
          if ($slot2Variant === 'color') {
              $logoB = !empty($site['logo_b_color']) ? $site['logo_b_color'] : (!empty($site['logo_b']) ? $site['logo_b'] : base_url('assets/img/logo_id_color.webp'));
          } else {
              // auto or white: header is dark background, use white variant
              $logoB = !empty($site['logo_b_white']) ? $site['logo_b_white'] : (!empty($site['logo_b']) ? $site['logo_b'] : base_url('assets/img/logo_id_white.webp'));
          }
        ?>
        <?php if ($slot2Active): ?>
          <a href="<?= esc($logoBUrl) ?>" class="brand-logo-link brand-logo-link-b" aria-label="Investor Daily Indonesia" <?= str_starts_with($logoBUrl, 'http') ? 'target="_blank" rel="noopener noreferrer"' : '' ?>>
            <img src="<?= esc($logoB) ?>" alt="Investor Daily Indonesia" class="brand-logo-id">
          </a>
        <?php endif; ?>
      </div>

      <!-- Right Group: Navigation links + Get Tickets CTA -->
      <div class="header-right-group">
        <nav class="site-nav" id="siteNav" aria-label="Main Navigation">
          <div class="mobile-nav-header">
            <span class="mobile-nav-title">Menu</span>
            <button class="nav-close-btn" id="navCloseBtn" aria-label="Close navigation menu">&times;</button>
          </div>
          <?php
            $navItems = !empty($site['nav_items']) ? $site['nav_items'] : [
                ['label' => 'Home', 'url' => '#home'],
                ['label' => 'About Us', 'url' => '#about-us'],
                ['label' => 'Schedule', 'url' => '#schedule'],
                ['label' => 'Gallery', 'url' => '#gallery'],
                ['label' => 'News', 'url' => '#news'],
                ['label' => 'Sponsor', 'url' => '#sponsor'],
            ];
            foreach ($navItems as $idx => $item):
          ?>
            <a href="<?= esc($item['url']) ?>" class="nav-link <?= $idx === 0 ? 'active' : '' ?>">
              <?= esc($item['label']) ?>
            </a>
          <?php endforeach; ?>
          <?php if (!empty($site['cta_ticket_active'])): ?>
            <a href="<?= esc($site['ticket_url']) ?>" class="btn-ticket btn-ticket-mobile" data-track-ticket="true">
              <?= esc($site['cta_ticket_label'] ?? 'Get Tickets') ?>
            </a>
          <?php endif; ?>
        </nav>

        <?php if (!empty($site['cta_ticket_active'])): ?>
          <a href="<?= esc($site['ticket_url']) ?>" class="btn-ticket btn-ticket-desktop" data-track-ticket="true">
            <?= esc($site['cta_ticket_label'] ?? 'Get Tickets') ?>
          </a>
        <?php endif; ?>
        
        <button class="nav-toggle" id="navToggle" aria-label="Toggle navigation menu">
          <img src="<?= base_url('assets/img/hamburger.svg') ?>" alt="Menu" class="nav-toggle-icon" width="26" height="18">
        </button>
      </div>

    </div>
  </div>
</header>
