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
    ?>

    <!-- Gallery Component Wrapper -->
    <div class="gallery-cascade-wrapper" id="galleryCascade">
      
      <!-- Hidden Data Script for Seamless Infinite Cycling -->
      <script type="application/json" id="galleryData">
        <?= json_encode($items, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_SLASHES) ?>
      </script>

      <!-- A. Main Slider (Cascading / Stacked Cards) -->
      <div class="gallery-cascade-viewport" aria-label="Gallery Foto Bertumpuk" role="region">
        <div class="gallery-cascade-stage" id="galleryCascadeStage">
          <?php for ($pos = -2; $pos <= 2; $pos++): ?>
            <div class="gallery-cascade-card card-pos-<?= $pos ?>" data-rel-pos="<?= $pos ?>" role="img" aria-label="Gallery Foto">
              <div class="gallery-cascade-card-inner">
                <img src="" alt="" loading="lazy">
              </div>
            </div>
          <?php endfor; ?>
        </div>
      </div>

      <!-- B. Thumbnail Navigation Strip -->
      <div class="gallery-thumb-nav-area">
        <!-- Prev Button (Up on Desktop, Left on Mobile) -->
        <button type="button" class="gallery-thumb-nav-btn btn-prev" id="galleryPrevBtn" aria-label="Foto Sebelumnya">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="desktop-icon">
            <polyline points="18 15 12 9 6 15"></polyline>
          </svg>
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="mobile-icon">
            <polyline points="15 18 9 12 15 6"></polyline>
          </svg>
        </button>

        <!-- Thumbnails Container (Always renders 5 items) -->
        <div class="gallery-thumb-list" id="galleryThumbList" role="tablist" aria-label="Navigasi Thumbnail Gallery">
          <?php for ($pos = -2; $pos <= 2; $pos++): ?>
            <button type="button" 
                    role="tab" 
                    class="gallery-thumb-item thumb-pos-<?= $pos ?> <?= $pos === 0 ? 'is-active' : '' ?>" 
                    data-rel-pos="<?= $pos ?>" 
                    aria-selected="<?= $pos === 0 ? 'true' : 'false' ?>"
                    aria-label="Pilih foto">
              <img src="" alt="" loading="lazy">
            </button>
          <?php endfor; ?>
        </div>

        <!-- Next Button (Down on Desktop, Right on Mobile) -->
        <button type="button" class="gallery-thumb-nav-btn btn-next" id="galleryNextBtn" aria-label="Foto Berikutnya">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="desktop-icon">
            <polyline points="6 9 12 15 18 9"></polyline>
          </svg>
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="mobile-icon">
            <polyline points="9 18 15 12 9 6"></polyline>
          </svg>
        </button>
      </div>

    </div>

  </div>
</section>

