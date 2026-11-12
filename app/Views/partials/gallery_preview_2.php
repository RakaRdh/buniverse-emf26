<?php
  $items = [];
  $defaultImg = base_url('assets/img/image1.webp');

  if (!empty($galleryImages) && is_array($galleryImages)) {
      foreach ($galleryImages as $g) {
          $items[] = [
              'image_url' => !empty($g['image_url']) ? $g['image_url'] : $defaultImg,
              'caption'   => $g['caption'] ?? 'Moment Galeri',
          ];
      }
  }

  $fallbackImgs = [
      base_url('assets/img/image1.webp'),
      base_url('assets/img/gallery1.webp'),
      base_url('assets/img/gallery2.webp'),
      base_url('assets/img/gallery3.webp'),
      base_url('assets/img/gallery4.webp'),
      base_url('assets/img/gallery5.webp'),
      base_url('assets/img/gallery6.webp'),
      base_url('assets/img/gallery7.webp'),
  ];

  while (count($items) < 8) {
      $idx = count($items);
      $items[] = [
          'image_url' => $fallbackImgs[$idx % count($fallbackImgs)],
          'caption'   => 'Highlight Foto ' . ($idx + 1),
      ];
  }

  $totalCount = count($items);
?>

<section class="gallery-section gallery-preview-2-section" id="gallery">
  <?= view('partials/section_decorations', ['sectionKey' => 'gallery', 'decorations' => $sectionDecorations ?? []]) ?>
  <?= view('partials/custom_placements', ['sectionKey' => 'gallery', 'customPlacements' => $customPlacements ?? []]) ?>
  
  <!-- Header: Inside container -->
  <div class="container">
    <div class="section-header-center">
      <h2 class="section-title">GALLERY</h2>
    </div>
  </div>

  <!-- Hidden Master Data JSON -->
  <script type="application/json" id="gallery3DData">
    <?= json_encode($items, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_SLASHES) ?>
  </script>

  <!-- #carousel FULL BLEED (Hanya 3 gambar yang tampil: Kiri, Tengah, Kanan) -->
  <div id="carousel" data-auto="true">
    <div id="scene">
      <?php foreach ($items as $idx => $item): ?>
        <div class="carousel_item <?= $idx <= 1 || $idx === ($totalCount - 1) ? 'is-visible' : '' ?>"
             data-index="<?= $idx ?>"
             data-caption="<?= esc($item['caption']) ?>">
          <div class="carousel_item_inner">
            <img src="<?= esc($item['image_url']) ?>" 
                 alt="<?= esc($item['caption']) ?>" 
                 loading="eager"
                 decoding="async">
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>

  <!-- Navigation Controls & Horizontal Thumbnail Nav Strip -->
  <div class="container">
    <div class="gallery-preview-2-nav-wrapper">
      <!-- Horizontal Thumbnail Navigation Strip (Both Desktop & Mobile) -->
      <div class="gallery-thumb-nav-area horizontal-nav">
        <!-- Prev Button (Left) -->
        <button type="button" class="gallery-thumb-nav-btn btn-prev" id="galleryPrevBtn" aria-label="Foto Sebelumnya">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="15 18 9 12 15 6"></polyline>
          </svg>
        </button>

        <!-- Thumbnails Viewport (Horizontal window with permanent center spotlight frame) -->
        <div class="gallery-thumb-viewport">
          <div class="gallery-thumb-spotlight-frame" aria-hidden="true"></div>

          <div class="gallery-thumb-list" id="galleryThumbList" role="tablist" aria-label="Navigasi Thumbnail Gallery">
            <?php 
              $activeSlot = 4; // Slot index 4 is the center spotlight of the 9 buffer slots (-4 to 4)
              for ($slot = 0; $slot < 9; $slot++): 
                $offset = $slot - 4;
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
        </div>

        <!-- Next Button (Right) -->
        <button type="button" class="gallery-thumb-nav-btn btn-next" id="galleryNextBtn" aria-label="Foto Berikutnya">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="9 18 15 12 9 6"></polyline>
          </svg>
        </button>
      </div>
    </div>
  </div>

</section>

<!-- Scoped CSS: Ukuran Tengah Lebih Besar, Border Radius 14px, Horizontal Thumbnail Strip -->
<style>
  .gallery-preview-2-section {
    position: relative;
    padding: 60px 0 75px 0;
    width: 100%;
    overflow: hidden;
  }

  /* #carousel: Full Viewport Width */
  #carousel {
    perspective: 1200px;
    overflow: hidden;
    position: relative;
    width: 100vw;
    margin-left: calc(-50vw + 50%);
    margin-right: calc(-50vw + 50%);
    padding: 25px 0 20px 0;
    user-select: none;
    -webkit-user-select: none;
  }

  /* #scene: Ukuran diperbesar sedikit (width: 52%, max-width: 660px) */
  #scene {
    width: 52%;
    max-width: 660px;
    margin: 30px auto 0 auto;
    transform-style: preserve-3d;
    transition: transform 0.5s ease;
    position: relative;
  }

  /* .carousel_item: Batasi hanya 3 gambar yang terlihat (is-visible), border-radius 14px */
  .carousel_item {
    width: 100%;
    box-sizing: border-box;
    padding: 0 16px;
    backface-visibility: hidden;
    -webkit-backface-visibility: hidden;
    cursor: pointer;
    opacity: 0;
    visibility: hidden;
    pointer-events: none;
    transition: opacity 0.45s ease, visibility 0.45s ease;
  }

  .carousel_item_inner {
    width: 100%;
    border-radius: 14px !important;
    overflow: hidden !important;
    transform: translateZ(0);
    -webkit-mask-image: -webkit-radial-gradient(white, black);
  }

  .carousel_item img {
    width: 100%;
    aspect-ratio: 16 / 10;
    object-fit: cover;
    display: block;
    border-radius: 14px !important;
    border: none !important;
    box-shadow: none !important;
  }

  /* Hanya 3 gambar (Kiri, Tengah, Kanan) yang tampil */
  .carousel_item.is-visible {
    opacity: 1;
    visibility: visible;
    pointer-events: auto;
  }

  .carousel_item:not(:first-of-type) {
    position: absolute;
    left: 0;
    top: 0;
  }

  /* Horizontal Navigation & Thumbnail Strip (Both Desktop & Mobile) */
  .gallery-preview-2-nav-wrapper {
    margin-top: 36px;
    display: flex;
    justify-content: center;
    align-items: center;
    width: 100%;
  }

  .gallery-thumb-nav-area.horizontal-nav {
    display: flex !important;
    flex-direction: row !important;
    align-items: center !important;
    justify-content: center !important;
    gap: 14px !important;
    position: relative;
    z-index: 10;
    width: 100%;
    max-width: 540px;
    margin: 0 auto;
  }

  .gallery-thumb-nav-area.horizontal-nav .gallery-thumb-nav-btn {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    background: #ffffff;
    color: #0f172a;
    border: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    box-shadow: 0 4px 14px rgba(0, 0, 0, 0.15);
    transition: transform 0.2s ease, background-color 0.2s ease;
    outline: none;
    flex-shrink: 0;
  }

  .gallery-thumb-nav-area.horizontal-nav .gallery-thumb-nav-btn:hover {
    background: #f8fafc;
    transform: scale(1.08);
  }

  .gallery-thumb-nav-area.horizontal-nav .gallery-thumb-nav-btn:active {
    transform: scale(0.95);
  }

  .gallery-thumb-nav-area.horizontal-nav .gallery-thumb-nav-btn svg {
    width: 20px;
    height: 20px;
    stroke: currentColor;
    pointer-events: none;
  }

  /* Horizontal Thumbnail Viewport (window displaying 5 thumbs on desktop, 3 on mobile) */
  .gallery-thumb-nav-area.horizontal-nav {
    display: flex !important;
    flex-direction: row !important;
    align-items: center !important;
    justify-content: center !important;
    gap: 18px !important;
    position: relative;
    z-index: 10;
    width: 100%;
    max-width: 820px;
    margin: 0 auto;
  }

  .gallery-thumb-nav-area.horizontal-nav .gallery-thumb-viewport {
    position: relative;
    width: calc(5 * 124px + 4 * 12px); /* 5 items: 124px wide + 12px gap */
    height: 82px;
    overflow: hidden;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 2px 0;
  }

  /* Center Spotlight Frame */
  .gallery-thumb-nav-area.horizontal-nav .gallery-thumb-spotlight-frame {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 124px;
    height: 78px;
    border-radius: 10px;
    border: 2.5px solid #ffffff;
    box-shadow: 0 6px 18px rgba(0, 0, 0, 0.4);
    pointer-events: none;
    z-index: 15;
  }

  /* 9-slot Continuous Conveyor Strip */
  .gallery-thumb-nav-area.horizontal-nav .gallery-thumb-list {
    display: flex;
    flex-direction: row;
    align-items: center;
    justify-content: center;
    gap: 12px;
    width: max-content;
    will-change: transform;
    margin: 0;
    padding: 0;
    list-style: none;
  }

  .gallery-thumb-nav-area.horizontal-nav .gallery-thumb-item {
    position: relative;
    width: 124px;
    height: 78px;
    border-radius: 10px;
    overflow: hidden;
    border: 2px solid transparent;
    padding: 0;
    margin: 0;
    background: rgba(0, 0, 0, 0.25);
    cursor: pointer;
    opacity: 0.45;
    transition: opacity 0.3s ease, transform 0.3s ease;
    outline: none;
    flex-shrink: 0;
  }

  .gallery-thumb-nav-area.horizontal-nav .gallery-thumb-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    pointer-events: none;
  }

  .gallery-thumb-nav-area.horizontal-nav .gallery-thumb-item.is-active {
    opacity: 1;
  }

  .gallery-thumb-nav-area.horizontal-nav .gallery-thumb-item:hover:not(.is-active) {
    opacity: 0.8;
  }

  /* Responsive Width for Scene & Thumbnails */
  @media (max-width: 991px) {
    #scene {
      width: 56%;
      max-width: 520px;
    }
    .gallery-thumb-nav-area.horizontal-nav .gallery-thumb-viewport {
      width: calc(5 * 104px + 4 * 10px);
      height: 70px;
    }
    .gallery-thumb-nav-area.horizontal-nav .gallery-thumb-spotlight-frame {
      width: 104px;
      height: 66px;
    }
    .gallery-thumb-nav-area.horizontal-nav .gallery-thumb-list {
      gap: 10px;
    }
    .gallery-thumb-nav-area.horizontal-nav .gallery-thumb-item {
      width: 104px;
      height: 66px;
    }
  }

  @media (max-width: 640px) {
    #carousel {
      perspective: 750px;
      padding: 15px 0 15px 0;
    }
    #scene {
      width: 52%;
      max-width: 250px;
      margin: 15px auto 0 auto;
    }
    .carousel_item {
      padding: 0 6px;
    }
    .carousel_item_inner {
      border-radius: 10px !important;
    }
    .carousel_item img {
      border-radius: 10px !important;
    }
    .gallery-preview-2-section {
      padding: 35px 0 45px 0;
    }
    .gallery-preview-2-nav-wrapper {
      margin-top: 24px;
    }
    /* Mobile: 3 visible thumbnails */
    .gallery-thumb-nav-area.horizontal-nav {
      gap: 10px !important;
      max-width: 100%;
    }
    .gallery-thumb-nav-area.horizontal-nav .gallery-thumb-nav-btn {
      width: 38px;
      height: 38px;
    }
    .gallery-thumb-nav-area.horizontal-nav .gallery-thumb-nav-btn svg {
      width: 18px;
      height: 18px;
    }
    .gallery-thumb-nav-area.horizontal-nav .gallery-thumb-viewport {
      width: calc(3 * 72px + 2 * 8px);
      height: 50px;
    }
    .gallery-thumb-nav-area.horizontal-nav .gallery-thumb-spotlight-frame {
      width: 72px;
      height: 46px;
      border-radius: 8px;
    }
    .gallery-thumb-nav-area.horizontal-nav .gallery-thumb-list {
      gap: 8px;
    }
    .gallery-thumb-nav-area.horizontal-nav .gallery-thumb-item {
      width: 72px;
      height: 46px;
      border-radius: 8px;
    }
  }

  @media (max-width: 420px) {
    #carousel {
      perspective: 650px;
    }
    #scene {
      width: 50%;
      max-width: 200px;
    }
    .carousel_item {
      padding: 0 4px;
    }
    .gallery-thumb-nav-area.horizontal-nav .gallery-thumb-viewport {
      width: calc(3 * 64px + 2 * 6px);
      height: 44px;
    }
    .gallery-thumb-nav-area.horizontal-nav .gallery-thumb-spotlight-frame {
      width: 64px;
      height: 40px;
    }
    .gallery-thumb-nav-area.horizontal-nav .gallery-thumb-list {
      gap: 6px;
    }
    .gallery-thumb-nav-area.horizontal-nav .gallery-thumb-item {
      width: 64px;
      height: 40px;
    }
  }
</style>

<!-- JavaScript Controller: 1:1 isladjan + Active 3-Card Limiter + Synced Horizontal Thumbnails + IDS Autoplay (5000ms) + Modal -->
<script>
document.addEventListener('DOMContentLoaded', () => {
  const carousel = document.getElementById("carousel");
  const scene = document.getElementById("scene");
  const carousel_items_Arrey = Array.from(document.getElementsByClassName("carousel_item"));
  const prevBtn = document.getElementById("galleryPrevBtn");
  const nextBtn = document.getElementById("galleryNextBtn");
  const thumbList = document.getElementById('galleryThumbList');

  const dataScript = document.getElementById('gallery3DData');
  let masterItems = [];
  try {
    masterItems = dataScript ? JSON.parse(dataScript.textContent) : [];
  } catch (e) {
    console.warn('Failed to parse gallery data', e);
  }

  if (!carousel || !scene || carousel_items_Arrey.length === 0) return;

  const n = carousel_items_Arrey.length;
  let curr_carousel_items_Arrey = 0;
  const theta = (Math.PI * 2) / n;
  let interval = null;
  const autoCarousel = carousel.dataset.auto;

  // Autoplay delay matching IDS (5000ms)
  const AUTOPLAY_DELAY = 5000;

  // Modal Lightbox
  const galleryModal = document.getElementById('galleryModal');
  const modalOverlay = document.getElementById('galleryModalOverlay');
  const modalClose = document.getElementById('galleryModalClose');
  const modalImg = document.getElementById('galleryModalImg');
  const modalCaption = document.getElementById('galleryModalCaption');

  const openLightbox = (item) => {
    if (!item || !galleryModal) return;
    if (modalImg) {
      modalImg.src = item.image_url;
      modalImg.alt = item.caption || '';
    }
    if (modalCaption) {
      modalCaption.textContent = item.caption || '';
    }
    galleryModal.classList.add('is-open');
    galleryModal.setAttribute('aria-hidden', 'false');
    document.body.style.overflow = 'hidden';
  };

  const closeLightbox = () => {
    if (!galleryModal) return;
    galleryModal.classList.remove('is-open');
    galleryModal.setAttribute('aria-hidden', 'true');
    document.body.style.overflow = '';
  };

  if (modalClose) modalClose.addEventListener('click', closeLightbox);
  if (modalOverlay) modalOverlay.addEventListener('click', closeLightbox);
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && galleryModal && galleryModal.classList.contains('is-open')) {
      closeLightbox();
    }
  });

  // 9-slot Horizontal Thumbnail Conveyor belt
  const THUMB_OFFSETS = [-4, -3, -2, -1, 0, 1, 2, 3, 4];
  const ACTIVE_THUMB_SLOT = 4;
  let thumbSlotList = thumbList ? Array.from(thumbList.querySelectorAll('.gallery-thumb-item')) : [];

  const getNormalizedIndex = (idx) => {
    return ((idx % n) + n) % n;
  };

  const setThumbContent = (thumb, itemIdx) => {
    const item = masterItems[itemIdx];
    if (!item) return;
    thumb.setAttribute('data-gallery-index', itemIdx);
    thumb.setAttribute('aria-label', `Pindah ke foto ${itemIdx + 1}: ${item.caption || ''}`);
    const img = thumb.querySelector('img');
    if (img) {
      if (img.getAttribute('src') !== item.image_url) {
        img.src = item.image_url;
      }
      img.alt = item.caption || `Thumbnail ${itemIdx + 1}`;
    }
  };

  const renderInitialThumbnails = () => {
    if (!thumbList || thumbSlotList.length === 0) return;
    const activeSlot = ((curr_carousel_items_Arrey % n) + n) % n;
    thumbSlotList.forEach((thumb, slot) => {
      const itemIdx = getNormalizedIndex(activeSlot + THUMB_OFFSETS[slot]);
      setThumbContent(thumb, itemIdx);
      if (slot === ACTIVE_THUMB_SLOT) {
        thumb.classList.add('is-active');
        thumb.setAttribute('aria-selected', 'true');
      } else {
        thumb.classList.remove('is-active');
        thumb.setAttribute('aria-selected', 'false');
      }
    });

    thumbList.style.transition = 'none';
    thumbList.style.transform = 'translate3d(0, 0, 0)';
  };

  const animateThumbnails = (steps, duration = 500) => {
    if (!thumbList || thumbSlotList.length === 0 || steps === 0) return;
    const dir = steps > 0 ? 1 : -1;
    const count = Math.abs(steps);

    const firstThumb = thumbSlotList[0];
    const secondThumb = thumbSlotList[1];
    const pitch = secondThumb ? (secondThumb.offsetLeft - firstThumb.offsetLeft) : (firstThumb.offsetWidth + 10);
    const delta = -steps * pitch;

    const targetActiveSlot = ACTIVE_THUMB_SLOT + steps;
    thumbSlotList.forEach((thumb, slot) => {
      if (slot === targetActiveSlot) {
        thumb.classList.add('is-active');
        thumb.setAttribute('aria-selected', 'true');
      } else {
        thumb.classList.remove('is-active');
        thumb.setAttribute('aria-selected', 'false');
      }
    });

    thumbList.style.transition = `transform ${duration}ms cubic-bezier(0.25, 1, 0.35, 1)`;
    thumbList.style.transform = `translate3d(${delta}px, 0, 0)`;

    setTimeout(() => {
      thumbList.style.transition = 'none';
      thumbList.style.transform = 'translate3d(0, 0, 0)';

      if (dir === 1) {
        for (let s = 0; s < count; s++) {
          const shifted = thumbSlotList.shift();
          thumbSlotList.push(shifted);
          thumbList.appendChild(shifted);
        }
      } else {
        for (let s = 0; s < count; s++) {
          const popped = thumbSlotList.pop();
          thumbSlotList.unshift(popped);
          thumbList.insertBefore(popped, thumbList.firstChild);
        }
      }

      const activeSlot = ((curr_carousel_items_Arrey % n) + n) % n;
      thumbSlotList.forEach((thumb, slot) => {
        thumb.setAttribute('data-slot', slot);
        const itemIdx = getNormalizedIndex(activeSlot + THUMB_OFFSETS[slot]);
        setThumbContent(thumb, itemIdx);

        if (slot === ACTIVE_THUMB_SLOT) {
          thumb.classList.add('is-active');
          thumb.setAttribute('aria-selected', 'true');
        } else {
          thumb.classList.remove('is-active');
          thumb.setAttribute('aria-selected', 'false');
        }
      });
    }, duration);
  };

  // Kunci Hanya 3 Gambar (Kiri, Tengah, Kanan) yang Muncul di Layar
  const updateVisibleCards = () => {
    const activeSlot = ((curr_carousel_items_Arrey % n) + n) % n;
    const leftSlot = (activeSlot - 1 + n) % n;
    const rightSlot = (activeSlot + 1) % n;

    carousel_items_Arrey.forEach((card, idx) => {
      if (idx === activeSlot || idx === leftSlot || idx === rightSlot) {
        card.classList.add('is-visible');
      } else {
        card.classList.remove('is-visible');
      }
    });
  };

  // Navigasi carousel & sinkronisasi thumbnail
  const rotateTo = (newIndex, stepCount = 0) => {
    const prevIndex = curr_carousel_items_Arrey;
    curr_carousel_items_Arrey = newIndex;
    scene.style.transform = `rotateY(${curr_carousel_items_Arrey * -theta}rad)`;
    updateVisibleCards();

    const actualSteps = stepCount !== 0 ? stepCount : (curr_carousel_items_Arrey - prevIndex);
    animateThumbnails(actualSteps, 500);
  };

  // Setup Carousel 1:1 persis isladjan
  function setupCarousel(n, width) {
    const apothem = width / (2 * Math.tan(Math.PI / n));
    scene.style.transformOrigin = `50% 50% ${-apothem}px`;

    for (let i = 1; i < n; i++) {
      carousel_items_Arrey[i].style.transformOrigin = `50% 50% ${-apothem}px`;
      carousel_items_Arrey[i].style.transform = `rotateY(${i * theta}rad)`;
    }

    updateVisibleCards();
    renderInitialThumbnails();

    if (autoCarousel === "true") {
      setCarouselInterval();
    }
  }

  // Autoplay
  function setCarouselInterval() {
    clearInterval(interval);
    interval = setInterval(function () {
      if (!isHovered && (!galleryModal || !galleryModal.classList.contains('is-open'))) {
        rotateTo(curr_carousel_items_Arrey + 1, 1);
      }
    }, AUTOPLAY_DELAY);
  }

  // Setup tombol next & prev
  function setupNavigation() {
    if (nextBtn) {
      nextBtn.addEventListener('click', function(e) {
        clearInterval(interval);
        rotateTo(curr_carousel_items_Arrey + 1, 1);
        if (autoCarousel === "true") {
          setTimeout(setCarouselInterval, AUTOPLAY_DELAY);
        }
      });
    }

    if (prevBtn) {
      prevBtn.addEventListener('click', function(e) {
        clearInterval(interval);
        rotateTo(curr_carousel_items_Arrey - 1, -1);
        if (autoCarousel === "true") {
          setTimeout(setCarouselInterval, AUTOPLAY_DELAY);
        }
      });
    }
  }

  // Klik thumbnail -> Rotasi ke foto tersebut
  if (thumbList) {
    thumbList.addEventListener('click', (e) => {
      const thumb = e.target.closest('.gallery-thumb-item');
      if (!thumb) return;
      const slot = parseInt(thumb.getAttribute('data-slot'), 10);
      if (isNaN(slot)) return;
      const steps = slot - ACTIVE_THUMB_SLOT;
      if (steps === 0) {
        const activeIdx = ((curr_carousel_items_Arrey % n) + n) % n;
        const item = masterItems[activeIdx];
        if (item) openLightbox(item);
        return;
      }
      clearInterval(interval);
      rotateTo(curr_carousel_items_Arrey + steps, steps);
      if (autoCarousel === "true") {
        setTimeout(setCarouselInterval, AUTOPLAY_DELAY);
      }
    });
  }

  // Klik gambar 3D tengah -> Modal, klik gambar samping -> Putar ke depan
  carousel_items_Arrey.forEach((card, idx) => {
    card.addEventListener('click', () => {
      const activeIdx = ((curr_carousel_items_Arrey % n) + n) % n;
      if (idx === activeIdx) {
        const img = card.querySelector('img');
        const item = masterItems[idx] || { image_url: img ? img.src : '', caption: img ? img.alt : '' };
        openLightbox(item);
      } else {
        let diff = (idx - activeIdx + n) % n;
        if (diff > n / 2) diff -= n;
        clearInterval(interval);
        rotateTo(curr_carousel_items_Arrey + diff, diff);
        if (autoCarousel === "true") {
          setTimeout(setCarouselInterval, AUTOPLAY_DELAY);
        }
      }
    });
  });

  // Hover pause
  let isHovered = false;
  carousel.addEventListener('mouseenter', () => { isHovered = true; });
  carousel.addEventListener('mouseleave', () => { isHovered = false; });
  if (thumbList) {
    thumbList.addEventListener('mouseenter', () => { isHovered = true; });
    thumbList.addEventListener('mouseleave', () => { isHovered = false; });
  }

  // Touch Swipe for Mobile
  let startX = 0;
  let startY = 0;
  carousel.addEventListener('touchstart', (e) => {
    if (e.touches.length === 1) {
      startX = e.touches[0].clientX;
      startY = e.touches[0].clientY;
      isHovered = true;
    }
  }, { passive: true });

  carousel.addEventListener('touchend', (e) => {
    isHovered = false;
    if (e.changedTouches.length === 1) {
      const diffX = e.changedTouches[0].clientX - startX;
      const diffY = e.changedTouches[0].clientY - startY;
      if (Math.abs(diffX) > 35 && Math.abs(diffX) > Math.abs(diffY)) {
        clearInterval(interval);
        if (diffX < 0) {
          rotateTo(curr_carousel_items_Arrey + 1, 1);
        } else {
          rotateTo(curr_carousel_items_Arrey - 1, -1);
        }
        if (autoCarousel === "true") {
          setTimeout(setCarouselInterval, AUTOPLAY_DELAY);
        }
      }
    }
  }, { passive: true });

  // Inisialisasi setelah slide siap
  function init() {
    const slideWidth = parseFloat(getComputedStyle(carousel_items_Arrey[0]).width);
    if (slideWidth > 0) {
      setupCarousel(n, slideWidth);
      setupNavigation();
    } else {
      setTimeout(init, 50);
    }
  }

  init();

  window.addEventListener('resize', function () {
    clearInterval(interval);
    const slideWidth = parseFloat(getComputedStyle(carousel_items_Arrey[0]).width);
    setupCarousel(n, slideWidth);
  }, false);
});
</script>
