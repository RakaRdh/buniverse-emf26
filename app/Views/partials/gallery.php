<section class="gallery-section" id="gallery">
  <div class="container">
    
    <!-- Section Header Centered -->
    <div class="section-header-center">
      <h2 class="section-title">GALLERY</h2>
    </div>

    <!-- Accordion Gallery Container -->
    <div class="gallery-accordion" id="galleryAccordion">
      <?php if (!empty($galleryImages)): ?>
        <?php foreach ($galleryImages as $idx => $g): ?>
          <div class="gallery-panel <?= (!empty($g['is_featured']) || $idx === 3) ? 'active' : '' ?>" 
               data-index="<?= $idx + 1 ?>"
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
        <?php for ($i = 1; $i <= 7; $i++): ?>
          <div class="gallery-panel <?= $i === 4 ? 'active' : '' ?>" 
               data-index="<?= $i ?>"
               tabindex="0"
               role="button"
               aria-label="Gallery photo <?= $i ?>">
            <div class="gallery-panel-inner">
              <img src="<?= base_url("assets/img/gallery{$i}.svg") ?>" 
                   alt="Investor Daily Summit Highlight <?= $i ?>" 
                   loading="lazy">
            </div>
          </div>
        <?php endfor; ?>
      <?php endif; ?>
    </div>

  </div>
</section>
