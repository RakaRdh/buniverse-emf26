<?php
/**
 * Locations & Floor Plan Section
 * Displays Venue Name, Address, Google Maps iframe embed, and Floor Plan layout image.
 */
$loc = $locations ?? [];
$hotelName   = !empty($loc['hotel_name']) ? $loc['hotel_name'] : 'Raffles Hotel Jakarta';
$address     = !empty($loc['address']) ? $loc['address'] : (!empty($loc['hotel_address']) ? $loc['hotel_address'] : "Ciputra World, Jl. Prof. DR. Satrio, Kuningan, Karet Kuningan, Kecamatan Setiabudi, Kota Jakarta Selatan, Daerah Khusus Ibukota Jakarta 12940");
$gmapsEmbed  = !empty($loc['gmaps_embed_url']) ? $loc['gmaps_embed_url'] : (!empty($loc['gmaps_iframe']) ? $loc['gmaps_iframe'] : 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.3075253818617!2d106.82115167570138!3d-6.223120660947683!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f3fb40954b83%3A0x6bfe76d05fca8764!2sRaffles%20Jakarta!5e0!3m2!1sen!2sid!4v1700000000000!5m2!1sen!2sid');
$floorPlanImg = !empty($loc['floor_plan_image']) ? $loc['floor_plan_image'] : (!empty($loc['layout_image_url']) ? $loc['layout_image_url'] : base_url('assets/img/denah.png'));
$isActive    = !isset($loc['is_active']) || !empty($loc['is_active']);

if (!$isActive) {
    return;
}
?>
<section class="locations-section" id="location">
  <?= view('partials/section_decorations', ['sectionKey' => 'location', 'decorations' => $sectionDecorations ?? []]) ?>
  <?= view('partials/custom_placements', ['sectionKey' => 'location', 'customPlacements' => $customPlacements ?? []]) ?>
  <div class="container">
    
    <!-- Top: Centered Hotel Name & Address -->
    <div class="locations-header-center">
      <h2 class="locations-hotel-name"><?= esc($hotelName) ?></h2>
      <div class="locations-address">
        <?= nl2br(esc($address)) ?>
      </div>
    </div>

    <!-- Bottom Side-by-Side: Floor Plan (Left) & Google Maps Embed (Right) -->
    <div class="locations-content-grid">
      
      <!-- Left: General Layout / Floor Plan Banner (Clickable Lightbox) -->
      <?php if (!empty($floorPlanImg)): ?>
        <div class="locations-floorplan-card is-clickable" id="locationFloorPlanCard" data-img-url="<?= esc($floorPlanImg) ?>" data-title="<?= esc($hotelName) ?> - General Layout" title="Klik untuk memperbesar gambar">
          <!-- <div class="locations-floorplan-badge">
            <span>GENERAL LAYOUT</span>
            <span class="locations-zoom-hint">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line><line x1="11" y1="8" x2="11" y2="14"></line><line x1="8" y1="11" x2="14" y2="11"></line></svg>
              Perbesar
            </span>
          </div> -->
          <div class="locations-floorplan-img-wrapper">
            <img src="<?= esc($floorPlanImg) ?>" alt="<?= esc($hotelName) ?> - General Layout" loading="lazy">
          </div>
        </div>
      <?php endif; ?>

      <!-- Right: Google Maps Embed (Sejajar dengan Floor Design) -->
      <div class="locations-map-card">
        <?php if (str_contains($gmapsEmbed, '<iframe')): ?>
          <div class="locations-map-embed-wrapper">
            <?= $gmapsEmbed ?>
          </div>
        <?php else: ?>
          <div class="locations-map-embed-wrapper">
            <iframe 
              src="<?= esc($gmapsEmbed) ?>" 
              width="100%" 
              height="100%" 
              style="border:0;" 
              allowfullscreen="" 
              loading="lazy" 
              referrerpolicy="no-referrer-when-downgrade"
              title="<?= esc($hotelName) ?> Map">
            </iframe>
          </div>
        <?php endif; ?>
      </div>

    </div>

  </div>
</section>
