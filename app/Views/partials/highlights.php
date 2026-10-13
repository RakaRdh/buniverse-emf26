<?php
  $hlData = $highlights ?? [];
  if (empty($hlData['is_active']) || empty($hlData['items'])) {
      return;
  }
  $items = $hlData['items'];

  // Normalize platform for each item
  foreach ($items as &$it) {
      if (empty($it['platform'])) {
          $raw = $it['youtube_url'] ?? '';
          if (preg_match('%tiktok\.com%i', $raw)) {
              $it['platform'] = 'tiktok';
          } elseif (preg_match('%(?:instagram\.com|instagr\.am)%i', $raw)) {
              $it['platform'] = 'instagram';
          } else {
              $it['platform'] = 'youtube';
          }
      }
  }
  unset($it);

  // Collect platforms present
  $platformsPresent = [];
  foreach ($items as $it) {
      $p = strtolower($it['platform']);
      $platformsPresent[$p] = ($platformsPresent[$p] ?? 0) + 1;
  }

  // Determine default tab: if multiple platforms and youtube exists, default to youtube, else only active platform
  $defaultPlatform = (count($platformsPresent) > 1 && isset($platformsPresent['youtube']))
      ? 'youtube'
      : (array_key_first($platformsPresent) ?? 'youtube');
  $isInitialPortrait = in_array($defaultPlatform, ['tiktok', 'instagram']);
?>
<section class="highlights-section" id="highlights">
  <?= view('partials/section_decorations', ['sectionKey' => 'highlights', 'decorations' => $sectionDecorations ?? []]) ?>
  <?= view('partials/custom_placements', ['sectionKey' => 'highlights', 'customPlacements' => $customPlacements ?? []]) ?>
  <div class="container">
    
    <!-- Section Header Centered -->
    <div class="section-header-center">
      <h2 class="section-title">Highlights</h2>
    </div>

    <?php /* Multi-Platform Tab Selector: Only rendered if more than 1 platform is active */ ?>
    <?php if (count($platformsPresent) > 1): ?>
    <div class="highlights-platform-selector" role="tablist" aria-label="Highlights Platforms">
      <?php if (isset($platformsPresent['youtube'])): ?>
      <button 
        type="button" 
        class="highlights-platform-pill <?= $defaultPlatform === 'youtube' ? 'active' : '' ?>" 
        data-platform-filter="youtube"
        onclick="switchHighlightsPlatform('youtube', this)"
        role="tab"
        aria-selected="<?= $defaultPlatform === 'youtube' ? 'true' : 'false' ?>"
      >
        <span>YouTube</span>
      </button>
      <?php endif; ?>

      <?php if (!empty($platformsPresent['tiktok'])): ?>
      <button 
        type="button" 
        class="highlights-platform-pill <?= $defaultPlatform === 'tiktok' ? 'active' : '' ?>" 
        data-platform-filter="tiktok"
        onclick="switchHighlightsPlatform('tiktok', this)"
        role="tab"
        aria-selected="<?= $defaultPlatform === 'tiktok' ? 'true' : 'false' ?>"
      >
        <span>TikTok</span>
      </button>
      <?php endif; ?>

      <?php if (!empty($platformsPresent['instagram'])): ?>
      <button 
        type="button" 
        class="highlights-platform-pill <?= $defaultPlatform === 'instagram' ? 'active' : '' ?>" 
        data-platform-filter="instagram"
        onclick="switchHighlightsPlatform('instagram', this)"
        role="tab"
        aria-selected="<?= $defaultPlatform === 'instagram' ? 'true' : 'false' ?>"
      >
        <span>Instagram</span>
      </button>
      <?php endif; ?>
    </div>
    <?php endif; ?>

    <!-- Fixed Cards Grid rendered via SSR with dynamic portrait/landscape layout -->
    <div class="highlights-grid <?= $isInitialPortrait ? 'is-portrait' : 'is-landscape' ?>" id="highlightsGrid">
      <?php foreach ($items as $video): 
        $platform = strtolower($video['platform'] ?? 'youtube');
        $isPortrait = in_array($platform, ['tiktok', 'instagram']);
        $isShown = ($platform === $defaultPlatform);
      ?>
        <article class="highlights-card <?= $isPortrait ? 'is-portrait' : 'is-landscape' ?>" data-platform="<?= esc($platform) ?>" style="<?= $isShown ? '' : 'display: none;' ?>">
          <a href="<?= esc($video['watch_url'] ?? $video['youtube_url']) ?>" target="_blank" rel="noopener noreferrer" class="highlights-card-inner" title="<?= esc($video['title']) ?>">
            <div class="highlights-thumb-wrap">
              <div class="highlights-thumb-bg" style="background-image: url('<?= esc($video['thumbnail_url']) ?>');"></div>
              
              <?php if ($platform === 'tiktok'): ?>
                <div class="highlights-play-badge tiktok-badge" aria-hidden="true">
                  <svg viewBox="0 0 48 48" width="48" height="48">
                    <rect width="48" height="48" rx="14" fill="#010618" stroke="rgba(255,255,255,0.22)" stroke-width="1.2"/>
                    <g transform="translate(12, 12)">
                      <!-- TikTok Cyan Shadow -->
                      <path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.24 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z" fill="#00f2fe" transform="translate(1, -1) scale(0.95)" opacity="0.9"/>
                      <!-- TikTok Magenta/Red Shadow -->
                      <path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.24 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z" fill="#fe2c55" transform="translate(-1, 1) scale(0.95)" opacity="0.9"/>
                      <!-- TikTok Main White Note -->
                      <path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.24 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z" fill="#ffffff" transform="scale(0.95)"/>
                    </g>
                  </svg>
                </div>
              <?php elseif ($platform === 'instagram'): ?>
                <div class="highlights-play-badge instagram-badge" aria-hidden="true">
                  <svg viewBox="0 0 48 48" width="48" height="48">
                    <defs>
                      <linearGradient id="ig-grad-card" x1="0%" y1="100%" x2="100%" y2="0%">
                        <stop offset="0%" stop-color="#f09433"/>
                        <stop offset="25%" stop-color="#e6683c"/>
                        <stop offset="50%" stop-color="#dc2743"/>
                        <stop offset="75%" stop-color="#cc2366"/>
                        <stop offset="100%" stop-color="#bc1888"/>
                      </linearGradient>
                    </defs>
                    <rect width="48" height="48" rx="14" fill="url(#ig-grad-card)"/>
                    <rect x="11" y="11" width="26" height="26" rx="7" fill="none" stroke="#ffffff" stroke-width="2.4"/>
                    <circle cx="24" cy="24" r="6" fill="none" stroke="#ffffff" stroke-width="2.4"/>
                    <circle cx="30.5" cy="17.5" r="1.4" fill="#ffffff"/>
                  </svg>
                </div>
              <?php else: ?>
                <div class="highlights-play-badge youtube-badge" aria-hidden="true">
                  <svg viewBox="0 0 68 48" width="52" height="36" class="yt-play-svg">
                    <path class="yt-play-bg" d="M66.52,7.74c-0.78-2.93-2.49-5.41-5.42-6.19C55.79,.13,34,0,34,0S12.21,.13,6.9,1.55 C3.97,2.33,2.27,4.81,1.48,7.74C0.06,13.05,0,24,0,24s0.06,10.95,1.48,16.26c0.78,2.93,2.49,5.41,5.42,6.19 C12.21,47.87,34,48,34,48s21.79-0.13,27.1-1.55c2.93-0.78,4.64-3.26,5.42-6.19C67.94,34.95,68,24,68,24S67.94,13.05,66.52,7.74z" fill="#FF0000"></path>
                    <path d="M 45,24 27,14 27,34" fill="#FFFFFF"></path>
                  </svg>
                </div>
              <?php endif; ?>

              <div class="highlights-title-embedded">
                <span class="highlights-title-text"><?= esc($video['title']) ?></span>
              </div>
            </div>
          </a>
        </article>
      <?php endforeach; ?>
    </div>

    <!-- Empty state for specific tab if no items -->
    <div id="highlightsEmptyState" style="display: none; padding: 40px 20px; text-align: center; color: var(--text-muted); font-size: 14px;">
      Belum ada konten highlights untuk kategori ini.
    </div>

  </div>
</section>

<script>
function switchHighlightsPlatform(platform, btn) {
  if (!btn) return;
  
  // Update active pill
  const selector = btn.closest('.highlights-platform-selector');
  if (selector) {
    selector.querySelectorAll('.highlights-platform-pill').forEach(function(pill) {
      pill.classList.remove('active');
      pill.setAttribute('aria-selected', 'false');
    });
  }
  btn.classList.add('active');
  btn.setAttribute('aria-selected', 'true');

  // Filter cards & update grid class (landscape vs portrait)
  const grid = document.getElementById('highlightsGrid');
  const emptyState = document.getElementById('highlightsEmptyState');
  if (!grid) return;

  const isPortrait = (platform === 'tiktok' || platform === 'instagram');
  if (isPortrait) {
    grid.classList.remove('is-landscape');
    grid.classList.add('is-portrait');
  } else {
    grid.classList.remove('is-portrait');
    grid.classList.add('is-landscape');
  }

  const cards = grid.querySelectorAll('.highlights-card');
  let visibleCount = 0;

  cards.forEach(function(card) {
    const cardPlatform = card.getAttribute('data-platform');
    if (cardPlatform === platform) {
      card.style.display = '';
      visibleCount++;
    } else {
      card.style.display = 'none';
    }
  });

  if (emptyState) {
    emptyState.style.display = visibleCount === 0 ? 'block' : 'none';
  }

  // Reset scroll position on mobile swipe
  if (grid.scrollLeft > 0) {
    grid.scrollTo({ left: 0, behavior: 'smooth' });
  }
}
</script>


