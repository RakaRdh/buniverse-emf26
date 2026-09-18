<section class="gallery-section" id="gallery">
  <div class="container">
    
    <!-- Section Header Centered -->
    <div class="section-header-center">
      <h2 class="section-title">GALLERY</h2>
    </div>

    <!-- Accordion Gallery Container (7 items per user request & reference) -->
    <div class="gallery-accordion" id="galleryAccordion">
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
    </div>

  </div>
</section>
