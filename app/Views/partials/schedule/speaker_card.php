<?php
/**
 * Inline SpeakerCard component for Schedule rows
 */
$photoUrl = $speaker['photo'] ?? 'placeholder.png';
if (empty($photoUrl)) {
    $photoUrl = 'placeholder.png';
}
if (!str_starts_with($photoUrl, 'http')) {
    $photoUrl = base_url('assets/img/' . ltrim($photoUrl, '/'));
}
?>
<div class="speaker-card" 
     role="button" 
     tabindex="0" 
     data-speaker-name="<?= esc($speaker['name']) ?>" 
     data-speaker-role="<?= esc($speaker['role']) ?>" 
     data-speaker-photo="<?= esc($photoUrl) ?>"
     data-speaker-bio="<?= esc($speaker['bio'] ?? '') ?>">
  <div class="speaker-photo-wrap">
    <img src="<?= esc($photoUrl) ?>" alt="<?= esc($speaker['name']) ?>" loading="lazy" onerror="this.onerror=null;this.src='<?= base_url('assets/img/placeholder.png') ?>';">
  </div>
  <div class="speaker-info">
    <div class="speaker-name"><?= esc($speaker['name']) ?></div>
    <div class="speaker-role"><?= esc($speaker['role']) ?></div>
  </div>
</div>
