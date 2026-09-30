<section class="gallery-section" id="gallery">
  <?= view('partials/section_decorations', ['sectionKey' => 'gallery', 'decorations' => $sectionDecorations ?? []]) ?>
  <?= view('partials/custom_placements', ['sectionKey' => 'gallery', 'customPlacements' => $customPlacements ?? []]) ?>
  <div class="container">
    
    <?php /* Section Header Centered */ ?>
    <div class="section-header-center">
      <h2 class="section-title">GALLERY</h2>
    </div>

    <?php
      $items = [];
      $defaultImg = base_url('assets/img/image1.webp');

      if (!empty($galleryImages) && is_array($galleryImages)) {
          foreach ($galleryImages as $g) {
              $items[] = [
                  'image_url' => !empty($g['image_url']) ? $g['image_url'] : $defaultImg,
                  'caption'   => $g['caption'] ?? 'Investor Daily Summit Moment',
              ];
          }
      }

      // Fallback images if fewer than 5
      $fallbackImgs = [
          base_url('assets/img/image1.webp'),
          base_url('assets/img/gallery1.webp'),
          base_url('assets/img/gallery2.webp'),
          base_url('assets/img/gallery3.webp'),
          base_url('assets/img/image1.webp'),
      ];

      // If empty or less than 5 items, provide minimum defaults
      $placeholderCaptions = [
          'Investor Daily Summit Opening Ceremony',
          'Keynote Address: Strategic Economic Outlook',
          'Plenary Panel: Decarbonization & Green Growth',
          'Executive Networking & Strategic Discussion',
          'Closing Remarks & Bilateral Collaboration',
      ];

      while (count($items) < 5) {
          $idx = count($items);
          $items[] = [
              'image_url' => $fallbackImgs[$idx % count($fallbackImgs)],
              'caption'   => $placeholderCaptions[$idx] ?? ('Investor Daily Summit Highlight ' . ($idx + 1)),
          ];
      }

      $totalCount = count($items);
      $randomCenter = array_rand($items);
      $leftIdx = ($randomCenter - 1 + $totalCount) % $totalCount;
      $rightIdx = ($randomCenter + 1) % $totalCount;
    ?>

    <?php /* 3D Coverflow Carousel Container */ ?>
    <div class="gallery-coverflow-wrapper" id="galleryCoverflow" tabindex="0" aria-label="IDS Gallery Coverflow Carousel">
      <div class="gallery-coverflow-track">
        <?php foreach ($items as $idx => $item): 
          $initialClass = ($idx === $randomCenter) ? 'is-center' : (($idx === $leftIdx) ? 'is-left' : (($idx === $rightIdx) ? 'is-right' : 'is-hidden'));
        ?>
          <div class="gallery-card <?= $initialClass ?>" 
               data-index="<?= $idx ?>" 
               data-caption="<?= esc($item['caption']) ?>"
               tabindex="0"
               role="button"
               aria-label="<?= esc($item['caption']) ?>">
            <div class="gallery-card-inner">
              <img src="<?= esc($item['image_url']) ?>" 
                   alt="<?= esc($item['caption']) ?>" 
                   loading="lazy">
              <?php /* 
              <div class="gallery-card-overlay">
                <span class="gallery-card-badge">Click to enlarge</span>
              </div>
              */ ?>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>

    <?php /* Navigation Arrows */ ?>
    <div class="gallery-nav-controls">
      <button type="button" class="gallery-nav-btn gallery-prev-btn" id="galleryPrevBtn" aria-label="Previous slide">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
          <polyline points="15 18 9 12 15 6"></polyline>
        </svg>
      </button>
      <button type="button" class="gallery-nav-btn gallery-next-btn" id="galleryNextBtn" aria-label="Next slide">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
          <polyline points="9 18 15 12 9 6"></polyline>
        </svg>
      </button>
    </div>
  </div>
</section>
