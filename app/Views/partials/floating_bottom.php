<?php
  $floatingBar = $site['floating_bar'] ?? [];
  if (isset($floatingBar['enabled']) && empty($floatingBar['enabled'])) {
      return;
  }
  $floatingText     = !empty($floatingBar['text']) ? $floatingBar['text'] : 'Get Your Seat Now!';
  $floatingBtnLabel = !empty($floatingBar['btn_label']) ? $floatingBar['btn_label'] : ($site['cta_ticket_label'] ?? 'Get Tickets');
  $floatingBtnUrl   = !empty($floatingBar['btn_url']) ? $floatingBar['btn_url'] : ($site['ticket_url'] ?? '#');
  $floatingSlot = $site['logo_placements']['floating_bottom'] ?? null;
  $floatingSlotActive = !isset($floatingSlot['is_active']) || !empty($floatingSlot['is_active']);
  $floatingVariant = $floatingSlot['variant'] ?? 'auto';

  if ($floatingVariant === 'color') {
      $floatingLogo = !empty($site['logo_a_color']) ? $site['logo_a_color'] : (!empty($site['logo_floating']) ? $site['logo_floating'] : base_url('assets/img/logo_ids26_color.webp'));
  } else {
      $floatingLogo = !empty($site['logo_floating']) ? $site['logo_floating'] : (!empty($site['logo_a_white']) ? $site['logo_a_white'] : base_url('assets/img/logo_ids26_white.webp'));
  }
?>
<div class="floating-bottom-bar" id="floatingBottomBar" role="region" aria-label="Quick registration bar">
  <div class="floating-bottom-card">
    
    <!-- Left: IDS 2026 Brand Logo -->
    <?php if ($floatingSlotActive): ?>
      <div class="floating-bottom-brand">
        <img src="<?= esc($floatingLogo) ?>" alt="<?= esc($site['title']) ?>" class="floating-bottom-logo">
      </div>
    <?php endif; ?>

    <!-- Center: Looping Typing Text Animation -->
    <div class="floating-bottom-center">
      <span class="typing-text" id="floatingTypingText" data-phrase="<?= esc($floatingText) ?>"></span><span class="typing-cursor" aria-hidden="true">|</span>
    </div>

    <!-- Right: Get Ticket Button (matching navbar style with radius 10) -->
    <div class="floating-bottom-action">
      <?php if (!empty($site['is_sold_out'])): ?>
        <span class="btn-floating-ticket disabled" style="background:#cbd5e1; color:#64748b; border:1px solid #94a3b8; cursor:not-allowed; opacity:0.85;">
          Sold Out
        </span>
      <?php else: ?>
        <a href="<?= base_url('register') ?>" class="btn-floating-ticket" data-track-ticket="true">
          <?= esc($floatingBtnLabel) ?>
        </a>
      <?php endif; ?>
    </div>

  </div>
</div>

