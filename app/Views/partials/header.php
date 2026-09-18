<header class="site-header" id="header">
  <div class="container">
    <div class="header-inner">
      
      <!-- Dual-logo brand lockup dengan asset resmi user per Brief §2 -->
      <a href="<?= base_url() ?>" class="brand-lockup" aria-label="Investor Daily Summit 2026">
        <!-- Logo 1: INVESTOR DAILY SUMMIT 2026 -->
        <?php $logoA = !empty($site['logo_a']) ? $site['logo_a'] : base_url('assets/img/logo_ids26.png'); ?>
        <img src="<?= esc($logoA) ?>" alt="Investor Daily Summit 2026" class="brand-logo-ids">

        <!-- Vertical Divider -->
        <div class="brand-divider" aria-hidden="true"></div>

        <!-- Logo 2: INVESTOR DAILY INDONESIA -->
        <?php $logoB = !empty($site['logo_b']) ? $site['logo_b'] : base_url('assets/img/logo_id.png'); ?>
        <img src="<?= esc($logoB) ?>" alt="Investor Daily Indonesia" class="brand-logo-id">
      </a>

      <!-- Right Group: Navigation links + Get Tickets CTA -->
      <div class="header-right-group">
        <nav class="site-nav" id="siteNav" aria-label="Main Navigation">
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
        </nav>

        <a href="<?= esc($site['ticket_url']) ?>" class="btn-ticket">
          <?= esc($site['cta_ticket_label'] ?? 'Get Tickets') ?>
        </a>
        
        <button class="nav-toggle" id="navToggle" aria-label="Toggle navigation menu">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="3" y1="12" x2="21" y2="12"></line>
            <line x1="3" y1="6" x2="21" y2="6"></line>
            <line x1="3" y1="18" x2="21" y2="18"></line>
          </svg>
        </button>
      </div>

    </div>
  </div>
</header>
