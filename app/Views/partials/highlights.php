<?php
  $hlData = $highlights ?? [];
  if (empty($hlData['is_active']) || empty($hlData['items'])) {
      return;
  }
  $items = $hlData['items'];
?>
<section class="highlights-section" id="highlights">
  <?= view('partials/section_decorations', ['sectionKey' => 'highlights', 'decorations' => $sectionDecorations ?? []]) ?>
  <?= view('partials/custom_placements', ['sectionKey' => 'highlights', 'customPlacements' => $customPlacements ?? []]) ?>
  <div class="container">
    
    <!-- Section Header Centered -->
    <div class="section-header-center">
      <h2 class="section-title">Highlights</h2>
    </div>

    <!-- Fixed Cards Grid rendered via SSR with official YouTube CDN thumbnails (Zero Google API Quota) -->
    <div class="highlights-grid" id="highlightsGrid">
      <?php foreach ($items as $video): ?>
        <article class="highlights-card">
          <a href="<?= esc($video['watch_url'] ?? $video['youtube_url']) ?>" target="_blank" rel="noopener noreferrer" class="highlights-card-inner" title="<?= esc($video['title']) ?>">
            <div class="highlights-thumb-wrap">
              <div class="highlights-thumb-bg" style="background-image: url('<?= esc($video['thumbnail_url']) ?>');"></div>
              <div class="highlights-play-badge" aria-hidden="true">
                <svg viewBox="0 0 68 48" width="52" height="36" class="yt-play-svg">
                  <path class="yt-play-bg" d="M66.52,7.74c-0.78-2.93-2.49-5.41-5.42-6.19C55.79,.13,34,0,34,0S12.21,.13,6.9,1.55 C3.97,2.33,2.27,4.81,1.48,7.74C0.06,13.05,0,24,0,24s0.06,10.95,1.48,16.26c0.78,2.93,2.49,5.41,5.42,6.19 C12.21,47.87,34,48,34,48s21.79-0.13,27.1-1.55c2.93-0.78,4.64-3.26,5.42-6.19C67.94,34.95,68,24,68,24S67.94,13.05,66.52,7.74z" fill="#FF0000"></path>
                  <path d="M 45,24 27,14 27,34" fill="#FFFFFF"></path>
                </svg>
              </div>
              <div class="highlights-title-embedded">
                <span class="highlights-title-text"><?= esc($video['title']) ?></span>
              </div>
            </div>
          </a>
        </article>
      <?php endforeach; ?>
    </div>

    <!-- Centered Selengkapnya Button -->
    <div class="highlights-action">
      <a href="https://www.youtube.com/results?search_query=%23investordailysummit" 
         target="_blank" 
         rel="noopener noreferrer" 
         class="highlights-more-btn" 
         id="highlightsMoreBtn">
        <span>Selengkapnya</span>
        <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
          <line x1="7" y1="17" x2="17" y2="7"></line>
          <polyline points="7 7 17 7 17 17"></polyline>
        </svg>
      </a>
    </div>

  </div>
</section>
