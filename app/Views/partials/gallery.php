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

  // Acak urutan foto galeri (randomized / shuffle)
  shuffle($items);
?>

<section class="gallery-section" id="gallery">
  <?= view('partials/section_decorations', ['sectionKey' => 'gallery', 'decorations' => $sectionDecorations ?? []]) ?>
  <?= view('partials/custom_placements', ['sectionKey' => 'gallery', 'customPlacements' => $customPlacements ?? []]) ?>
  <div class="container">
    
    <?php /* Section Header Centered */ ?>
    <div class="section-header-center">
      <h2 class="section-title">GALLERY</h2>
    </div>

    <!-- Gallery Component Wrapper -->
    <div class="gallery-cascade-wrapper" id="galleryCascade">
      
      <!-- Hidden Data Script for Seamless Infinite Cycling -->
      <script type="application/json" id="galleryData">
        <?= json_encode($items, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_SLASHES) ?>
      </script>

      <!-- A. Main Slider (Cascading / Stacked Cards) -->
      <div class="gallery-cascade-viewport" aria-label="Gallery Foto Bertumpuk" role="region">
        <div class="gallery-cascade-stage" id="galleryCascadeStage">
          <?php 
            $totalCount = count($items);
            for ($pos = -3; $pos <= 3; $pos++): 
              $initialIndex = (($pos % $totalCount) + $totalCount) % $totalCount;
              $initialItem = $items[$initialIndex] ?? ($items[0] ?? ['image_url' => '', 'caption' => '']);
          ?>
            <div class="gallery-cascade-card card-pos-<?= $pos ?>" data-rel-pos="<?= $pos ?>" role="img" aria-label="<?= esc($initialItem['caption'] ?? 'Gallery Foto') ?>">
              <div class="gallery-cascade-card-inner">
                <img src="<?= esc($initialItem['image_url']) ?>" alt="<?= esc($initialItem['caption'] ?? '') ?>" loading="eager" decoding="async">
              </div>
            </div>
          <?php endfor; ?>
        </div>
      </div>

      <!-- B. Thumbnail Navigation Strip (Spotlight paten selalu di tengah: Slot 2) -->
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

        <!-- Thumbnails Container (Spotlight paten di posisi tengah slot 2) -->
        <div class="gallery-thumb-list" id="galleryThumbList" role="tablist" aria-label="Navigasi Thumbnail Gallery">
          <?php 
            $activeSlot = 2; // Paten di tengah (1 2 [3/spotlight] 4 5)
            for ($slot = 0; $slot < 5; $slot++): 
              $offset = $slot - 2; // -2, -1, 0, 1, 2
              $itemIdx = (($offset % $totalCount) + $totalCount) % $totalCount;
          ?>
            <button type="button" 
                    role="tab" 
                    class="gallery-thumb-item <?= $slot === $activeSlot ? 'is-active' : '' ?>" 
                    data-slot="<?= $slot ?>" 
                    data-gallery-index="<?= $itemIdx ?>" 
                    aria-selected="<?= $slot === $activeSlot ? 'true' : 'false' ?>"
                    aria-label="Pilih foto <?= $itemIdx + 1 ?>">
              <img src="<?= esc($items[$itemIdx]['image_url'] ?? '') ?>" alt="<?= esc($items[$itemIdx]['caption'] ?? '') ?>" loading="eager" decoding="async">
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

