<section class="news-section" id="news">
  <?= view('partials/section_decorations', ['sectionKey' => 'news', 'decorations' => $sectionDecorations ?? []]) ?>
  <?= view('partials/custom_placements', ['sectionKey' => 'news', 'customPlacements' => $customPlacements ?? []]) ?>
  <div class="container">
    
    <!-- Section Header Centered -->
    <div class="section-header-center">
      <h2 class="section-title">Related News</h2>
    </div>

    <?php
      $newsRows = [
        [
          'id'    => 'investor-daily',
          'title' => 'Investor Daily',
          'items' => $relatedNewsInvestor ?? ($relatedNews ?? []),
        ],
        [
          'id'    => 'beritasatu',
          'title' => 'BeritaSatu',
          'items' => $relatedNewsBeritaSatu ?? [],
        ],
        [
          'id'    => 'jakartaglobe',
          'title' => 'JakartaGlobe',
          'items' => $relatedNewsJakartaGlobe ?? [],
        ],
      ];
    ?>

    <?php foreach ($newsRows as $row): 
      $portalKey = $row['id'];
      $portalConfig = $newsPortals[$portalKey] ?? null;
      $portalUrl = $portalConfig['url'] ?? '#';
      if ($portalUrl === '#' || empty($portalUrl)) {
        if ($portalKey === 'investor-daily') $portalUrl = 'https://investor.id';
        elseif ($portalKey === 'beritasatu') $portalUrl = 'https://www.beritasatu.com';
        elseif ($portalKey === 'jakartaglobe') $portalUrl = 'https://jakartaglobe.id';
      }
    ?>
      <div class="news-source-block">
        <div class="news-source-header">
          <h3 class="news-source-title"><?= esc($row['title']) ?></h3>
          <a href="<?= esc($portalUrl) ?>" target="_blank" rel="noopener noreferrer" class="news-source-more-link">
            Selengkapnya &rarr;
          </a>
        </div>

        <?php if (!empty($row['items'])): 
          $itemCount = count($row['items']);
          $isCentered = ($itemCount < 3);
        ?>
          <!-- News Slider Carousel Wrapper -->
          <div class="news-slider-wrapper <?= $isCentered ? 'is-centered' : '' ?>" data-slider-id="<?= esc($row['id']) ?>">
            
            <?php if (!$isCentered): ?>
            <!-- Prev Navigation Arrow -->
            <button type="button" 
                    class="news-arrow-btn news-arrow-prev is-disabled" 
                    aria-label="Previous news" 
                    disabled>
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="15 18 9 12 15 6"></polyline>
              </svg>
            </button>
            <?php endif; ?>

            <!-- Scrollable / Swipeable Track -->
            <div class="news-slider-container">
              <div class="news-slider-track <?= $isCentered ? 'is-centered' : '' ?>">
                <?php foreach ($row['items'] as $news): 
                  $isExternalImage = preg_match('#^https?://#i', $news['image'] ?? '');
                  $imgSrc = $isExternalImage ? $news['image'] : base_url('assets/img/' . $news['image']);
                  $isExternalUrl = preg_match('#^https?://#i', $news['url'] ?? '');
                ?>
                  <article class="news-card">
                    <a href="<?= esc($news['url']) ?>" class="news-card-link" <?= $isExternalUrl ? 'target="_blank" rel="noopener noreferrer"' : '' ?>>
                      <div class="news-image-wrap">
                        <img src="<?= esc($imgSrc) ?>" 
                             alt="<?= esc($news['title']) ?>" 
                             loading="lazy">
                      </div>
                      <h3 class="news-title"><?= esc($news['title']) ?></h3>
                    </a>
                  </article>
                <?php endforeach; ?>
              </div>
            </div>

            <?php if (!$isCentered): ?>
            <!-- Next Navigation Arrow -->
            <button type="button" 
                    class="news-arrow-btn news-arrow-next" 
                    aria-label="Next news">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="9 18 15 12 9 6"></polyline>
              </svg>
            </button>
            <?php endif; ?>

          </div>
        <?php else: ?>
          <!-- Empty State when no news is available -->
          <div class="news-empty-state">
            <p class="news-empty-text">Tidak ada related news saat ini</p>
          </div>
        <?php endif; ?>
      </div>
    <?php endforeach; ?>

  </div>
</section>
