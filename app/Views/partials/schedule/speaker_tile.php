<?php
/**
 * Standalone SpeakerTile for SPEAKERS tab grid (§9A Option A)
 * Displays portrait edge-to-edge + Name and Role beneath
 */
$photoUrl = $speaker['photo'] ?? 'placeholder.png';
if (empty($photoUrl)) {
    $photoUrl = 'placeholder.png';
}
if (!str_starts_with($photoUrl, 'http')) {
    $photoUrl = base_url('assets/img/' . ltrim($photoUrl, '/'));
}
?>
<div class="speaker-tile" 
     role="button" 
     tabindex="0" 
     data-speaker-name="<?= esc($speaker['name']) ?>" 
     data-speaker-role="<?= esc($speaker['role']) ?>" 
     data-speaker-photo="<?= esc($photoUrl) ?>"
     data-speaker-bio="<?= esc($speaker['bio'] ?? '') ?>">
  
  <div class="speaker-tile-frame">
    <div class="speaker-tile-photo-wrap">
      <img src="<?= esc($photoUrl) ?>" alt="<?= esc($speaker['name']) ?>" loading="lazy">
    </div>
  </div>

  <div class="speaker-tile-caption">
    <div class="speaker-tile-name"><?= esc($speaker['name']) ?></div>
    <div class="speaker-tile-role"><?= esc($speaker['role']) ?></div>
  </div>
</div>
