<section class="gallery-section" id="gallery">
  <?= view('partials/section_decorations', ['sectionKey' => 'gallery', 'decorations' => $sectionDecorations ?? []]) ?>
  <?= view('partials/custom_placements', ['sectionKey' => 'gallery', 'customPlacements' => $customPlacements ?? []]) ?>
  <div class="container">
    
    <!-- Section Header Centered -->
    <div class="section-header-center">
      <h2 class="section-title">GALLERY</h2>
    </div>

    <!-- Accordion / Carousel Gallery Container -->
    <div class="gallery-accordion" id="galleryAccordion">
      <?php if (!empty($galleryImages)): ?>
        <?php foreach ($galleryImages as $idx => $g): 
          $isFeatured = !empty($g['is_featured']) || $idx === 3;
        ?>
          <div class="gallery-panel <?= $isFeatured ? 'is-featured' : '' ?> <?= $isFeatured ? 'active' : '' ?>" 
               data-index="<?= $idx + 1 ?>"
               data-featured="<?= $isFeatured ? '1' : '0' ?>"
               tabindex="0"
               role="button"
               aria-label="<?= esc($g['caption'] ?: 'Gallery photo ' . ($idx + 1)) ?>">
            <div class="gallery-panel-inner">
              <img src="<?= esc($g['image_url']) ?>" 
                   alt="<?= esc($g['caption'] ?: 'Investor Daily Summit Highlight ' . ($idx + 1)) ?>" 
                   loading="lazy">
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <?php for ($i = 1; $i <= 7; $i++): 
          $isFeatured = ($i === 4);
        ?>
          <div class="gallery-panel <?= $isFeatured ? 'is-featured' : '' ?> <?= $isFeatured ? 'active' : '' ?>" 
               data-index="<?= $i ?>"
               data-featured="<?= $isFeatured ? '1' : '0' ?>"
               tabindex="0"
               role="button"
               aria-label="Gallery photo <?= $i ?>">
            <div class="gallery-panel-inner">
              <img src="<?= base_url("assets/img/gallery{$i}.webp") ?>" 
                   alt="Investor Daily Summit Highlight <?= $i ?>" 
                   loading="lazy">
            </div>
          </div>
        <?php endfor; ?>
      <?php endif; ?>
    </div>

    <!-- Mobile Carousel Indicators -->
    <div class="gallery-dots-mobile" id="galleryDotsMobile"></div>

  </div>
</section>
