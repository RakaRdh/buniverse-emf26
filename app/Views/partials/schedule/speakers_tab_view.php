<?php
/**
 * Standalone view for SPEAKERS tab (§9A)
 * Shows 4-column desktop grid with all unique speakers
 */
?>
<div class="speakers-tab-view" id="speakersTabView">
  <div class="speakers-grid">
    <?php 
      $speakerList = array_values($speakers);
      foreach ($speakerList as $idx => $sp): 
    ?>
      <div class="speaker-tile-item <?= $idx >= 16 ? 'speaker-tile-extra hidden' : '' ?>">
        <?= view('partials/schedule/speaker_tile', ['speaker' => $sp]) ?>
      </div>
    <?php endforeach; ?>
  </div>

  <?php if (count($speakers) > 16): ?>
    <div class="speakers-load-more-wrap">
      <button type="button" id="btnLoadMoreSpeakers" class="btn-load-more-speakers" data-expanded="false">
        <span>View More Speakers</span>
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
          <polyline points="6 9 12 15 18 9"></polyline>
        </svg>
      </button>
    </div>
  <?php endif; ?>
</div>
