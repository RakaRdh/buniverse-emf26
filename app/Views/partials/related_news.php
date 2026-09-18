<section class="news-section" id="news">
  <div class="container">
    
    <!-- Section Header Centered -->
    <div class="section-header-center">
      <h2 class="section-title">Related News</h2>
    </div>

    <!-- News Slider Carousel Wrapper -->
    <div class="news-slider-wrapper">
      
      <!-- Prev Navigation Arrow -->
      <button type="button" 
              class="news-arrow-btn news-arrow-prev is-disabled" 
              id="newsPrevBtn" 
              aria-label="Previous news" 
              disabled>
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
          <polyline points="15 18 9 12 15 6"></polyline>
        </svg>
      </button>

      <!-- Scrollable / Swipeable Track -->
      <div class="news-slider-container" id="newsSliderContainer">
        <div class="news-slider-track" id="newsSliderTrack">
          <?php foreach ($relatedNews as $news): ?>
            <article class="news-card">
              <a href="<?= esc($news['url']) ?>" class="news-card-link">
                <div class="news-image-wrap">
                  <img src="<?= base_url('assets/img/' . esc($news['image'])) ?>" 
                       alt="<?= esc($news['title']) ?>" 
                       loading="lazy">
                </div>
                <h3 class="news-title"><?= esc($news['title']) ?></h3>
              </a>
            </article>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- Next Navigation Arrow -->
      <button type="button" 
              class="news-arrow-btn news-arrow-next" 
              id="newsNextBtn" 
              aria-label="Next news">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
          <polyline points="9 18 15 12 9 6"></polyline>
        </svg>
      </button>

    </div>

  </div>
</section>
