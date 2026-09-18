<header class="site-header" id="header">
  <div class="container">
    <div class="header-inner">
      
      <!-- Dual-logo brand lockup dengan asset resmi user per Brief §2 -->
      <a href="<?= base_url() ?>" class="brand-lockup" aria-label="Investor Daily Summit 2026">
        <!-- Logo 1: INVESTOR DAILY SUMMIT 2026 -->
        <img src="<?= base_url('assets/img/logo_ids26.png') ?>" alt="Investor Daily Summit 2026" class="brand-logo-ids">

        <!-- Vertical Divider -->
        <div class="brand-divider" aria-hidden="true"></div>

        <!-- Logo 2: INVESTOR DAILY INDONESIA -->
        <img src="<?= base_url('assets/img/logo_id.png') ?>" alt="Investor Daily Indonesia" class="brand-logo-id">
      </a>

      <!-- Right Group: Navigation links + Get Tickets CTA -->
      <div class="header-right-group">
        <nav class="site-nav" id="siteNav" aria-label="Main Navigation">
          <a href="#home" class="nav-link active">Home</a>
          <a href="#about-us" class="nav-link">About Us</a>
          <a href="#schedule" class="nav-link">Schedule</a>
          <a href="#gallery" class="nav-link">Gallery</a>
          <a href="#news" class="nav-link">News</a>
          <a href="#sponsor" class="nav-link">Sponsor</a>
        </nav>

        <a href="<?= esc($site['ticket_url']) ?>" class="btn-ticket">Get Tickets</a>
        
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
