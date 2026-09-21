<div class="floating-bottom-bar" id="floatingBottomBar" role="region" aria-label="Quick registration bar">
  <div class="floating-bottom-card">
    
    <!-- Left: IDS 2026 Brand Logo -->
    <div class="floating-bottom-brand">
      <?php $barLogo = !empty($site['logo_a']) ? $site['logo_a'] : base_url('assets/img/logo_ids26.png'); ?>
      <img src="<?= esc($barLogo) ?>" alt="<?= esc($site['title']) ?>" class="floating-bottom-logo">
    </div>

    <!-- Center: Looping Typing Text Animation -->
    <div class="floating-bottom-center">
      <span class="typing-text" id="floatingTypingText"></span><span class="typing-cursor" aria-hidden="true">|</span>
    </div>

    <!-- Right: Get Ticket Button (matching navbar style with radius 10) -->
    <div class="floating-bottom-action">
      <a href="<?= esc($site['ticket_url']) ?>" class="btn-floating-ticket" data-track-ticket="true">
        <?= esc($site['cta_ticket_label'] ?? 'Get Tickets') ?>
      </a>
    </div>

  </div>
</div>
