<?php
$overviewData = $overview ?? $economicModel ?? [];
$paragraphs = $overviewData['paragraphs'] ?? [];

// Ambil gambar dari DB: collage_images atau galleryImages atau gallery
$carouselImages = [];
if (!empty($overviewData['collage_images'])) {
    foreach ($overviewData['collage_images'] as $cImg) {
        if (!empty($cImg)) $carouselImages[] = $cImg;
    }
}
if (empty($carouselImages) && !empty($galleryImages)) {
    foreach ($galleryImages as $gImg) {
        $url = is_array($gImg) ? ($gImg['image_url'] ?? $gImg['url'] ?? '') : (is_object($gImg) ? ($gImg->image_url ?? $gImg->url ?? '') : '');
        if (!empty($url)) $carouselImages[] = $url;
    }
}
if (empty($carouselImages) && !empty($gallery)) {
    foreach ($gallery as $gImg) {
        $url = is_array($gImg) ? ($gImg['image_url'] ?? $gImg['url'] ?? '') : (is_object($gImg) ? ($gImg->image_url ?? $gImg->url ?? '') : '');
        if (!empty($url)) $carouselImages[] = $url;
    }
}

// Fallback jika DB kosong: placeholder foto galeri EMF
if (empty($carouselImages)) {
    $carouselImages = [
        base_url('assets/img/gallery1.webp'),
        base_url('assets/img/gallery2.webp'),
        base_url('assets/img/gallery3.webp'),
        base_url('assets/img/gallery4.webp'),
    ];
}
?>

<section class="editorial-section about-preview-section" id="about-us">
  <?= view('partials/section_decorations', ['sectionKey' => 'about-us', 'decorations' => $sectionDecorations ?? []]) ?>
  <?= view('partials/custom_placements', ['sectionKey' => 'about-us', 'customPlacements' => $customPlacements ?? []]) ?>
  
  <div class="container">
    <div class="editorial-grid about-preview-grid">
      
      <?php /* Left Column: Rotating Ribbon Frame + Circular Fade Ken-Burns Carousel */ ?>
      <div class="editorial-left about-preview-left">
        <div class="about-ribbon-stage" aria-label="About Us Visual Showcase">
          
          <!-- Circular Masked Media Carousel (Layer Belakang Ribbon) -->
          <div class="about-circular-media-wrap">
            <?php foreach ($carouselImages as $idx => $imgSrc): ?>
              <div class="about-circular-slide <?= $idx === 0 ? 'is-active' : '' ?>" data-slide-index="<?= $idx ?>">
                <img src="<?= esc($imgSrc) ?>" alt="EMF Showcase <?= $idx + 1 ?>" loading="<?= $idx === 0 ? 'eager' : 'lazy' ?>">
              </div>
            <?php endforeach; ?>
            <!-- Subtle Radial Vignette Ring Overlay -->
            <div class="about-circular-vignette" aria-hidden="true"></div>
          </div>

          <!-- Spinning Ribbon Star Frame (Layer Depan Membingkai Lingkaran) -->
          <div class="about-spinning-ribbon" aria-hidden="true">
            <img src="<?= base_url('assets/img/ribbon.webp') ?>" alt="Ribbon Star Frame">
          </div>

        </div>
      </div>

      <?php /* Right Column: Topic Badge & Editorial Paragraphs */ ?>
      <div class="editorial-right about-preview-right">
        <div class="topic-badge">
          <?= esc(!empty($overviewData['badge']) ? $overviewData['badge'] : "ABOUT US") ?>
        </div>

        <div class="editorial-paragraphs">
          <?php foreach ($paragraphs as $p): ?>
            <p><?= strip_tags($p, '<span><em><strong><i><b><u><br>') ?></p>
          <?php endforeach; ?>
        </div>
      </div>

    </div>
  </div>
</section>

<style>
/* --------------------------------------------------------------------------
   About Us Preview Style (/preview-about)
   Ribbon Star Frame (Stay / Stationary) + Outward-Fading Photo Carousel
   -------------------------------------------------------------------------- */
.about-preview-section {
  position: relative;
  min-height: 600px;
  padding: 60px 0 90px 0;
  background-color: var(--yellow-6, #fff100);
  overflow: hidden;
}

.about-preview-grid {
  display: grid;
  grid-template-columns: 1fr 1.25fr;
  gap: 50px;
  align-items: center;
  width: 100%;
  max-width: 100%;
  margin: 0;
}

.about-preview-left {
  display: flex;
  justify-content: center;
  align-items: center;
  position: relative;
  width: 100%;
  height: 100%;
}

/* Stage Box Pembungkus Ribbon & Gambar - Diperbesar maksimal */
.about-ribbon-stage {
  position: relative;
  width: min(560px, 100%);
  aspect-ratio: 1 / 1;
  display: flex;
  align-items: center;
  justify-content: center;
  user-select: none;
}

/* Lingkaran Gambar di Belakang Ribbon (Ukuran sekitar setengah dari ribbon ~52%) */
.about-circular-media-wrap {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  width: 62%;
  height: 62%;
  border-radius: 50%;
  overflow: hidden;
  z-index: 1;
  background: transparent;
  /* Masking Radial sesuai konfigurasi Figma: 0%-54% solid, 54%-100% memudar ke transparent */
  -webkit-mask-image: radial-gradient(circle at center,
    rgba(0, 0, 0, 1) 0%,
    rgba(0, 0, 0, 1) 54%,
    rgba(0, 0, 0, 0) 100%
  );
  mask-image: radial-gradient(circle at center,
    rgba(0, 0, 0, 1) 0%,
    rgba(0, 0, 0, 1) 54%,
    rgba(0, 0, 0, 0) 100%
  );
}

.about-circular-slide {
  position: absolute;
  inset: 0;
  width: 100%;
  height: 100%;
  opacity: 0;
  visibility: hidden;
  transform: scale(0.96);
  transition: opacity 1.3s ease-in-out, transform 1.3s cubic-bezier(0.25, 1, 0.5, 1);
  will-change: transform, opacity;
  pointer-events: none;
}

/* Slide Aktif: Diam (Stay / Tenang), tidak ada animasi kedut-kedut */
.about-circular-slide.is-active {
  opacity: 1;
  visibility: visible;
  transform: scale(1.0);
  z-index: 2;
}

/* Slide yang sedang keluar saat transisi berganti */
.about-circular-slide.is-leaving {
  opacity: 0;
  visibility: visible;
  transform: scale(1.05);
  z-index: 1;
}

.about-circular-slide img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  object-position: center;
  display: block;
}

/* Radial Vignette Overlay persis sesuai Stops Figma: 54% (0% opacity) -> 100% (#F9E55E 100% opacity) */
.about-circular-vignette {
  position: absolute;
  inset: 0;
  border-radius: 50%;
  pointer-events: none;
  z-index: 3;
  background: radial-gradient(circle at center,
    rgba(217, 217, 217, 0) 0%,
    rgba(217, 217, 217, 0) 54%,
    #F9E55E 100%
  );
}

/* Ribbon Bintang EMF - Berputar Clockwise dengan Ease-in-out saat pergantian gambar */
.about-spinning-ribbon {
  position: absolute;
  inset: 0;
  width: 100%;
  height: 100%;
  pointer-events: none;
  z-index: 4;
  display: flex;
  align-items: center;
  justify-content: center;
}

.about-spinning-ribbon img {
  width: 100%;
  height: 100%;
  object-fit: contain;
  filter: drop-shadow(0 14px 28px rgba(0, 0, 0, 0.16));
  transform: rotate(0deg);
  transition: transform 1.4s ease-in-out;
  will-change: transform;
}

/* Right Column Tuning: Menempel ke Padding Kanan */
.about-preview-right {
  display: flex;
  flex-direction: column;
  align-items: flex-start;
  width: 100%;
}

.about-preview-right .topic-badge {
  width: 100%;
  max-width: 100%;
  margin-bottom: 26px;
  background-color: var(--green-6, #49982e);
  border: 1px solid rgba(255, 255, 255, 0.2);
  color: #ffffff;
  box-shadow: none;
}

.about-preview-right .editorial-paragraphs {
  display: flex;
  flex-direction: column;
  gap: 20px;
  width: 100%;
  max-width: 100%;
}

/* Responsive Tablet & Mobile */
@media (max-width: 991px) {
  .about-preview-grid {
    grid-template-columns: 1fr;
    gap: 40px;
    text-align: center;
  }
  .about-preview-right {
    align-items: center;
  }
  .about-ribbon-stage {
    width: min(380px, 90vw);
  }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const slides = document.querySelectorAll('.about-circular-slide');
  const ribbonImg = document.querySelector('.about-spinning-ribbon img');
  if (!slides || slides.length <= 1) return;

  let currentIdx = 0;
  let ribbonRotation = 0;
  const intervalTime = 5000; // Berganti setiap 5 detik

  setInterval(function() {
    // 1. Transisi slide gambar (slide lama out, slide baru in)
    const prevSlide = slides[currentIdx];
    prevSlide.classList.remove('is-active');
    prevSlide.classList.add('is-leaving');
    setTimeout(function() {
      prevSlide.classList.remove('is-leaving');
    }, 1350);

    currentIdx = (currentIdx + 1) % slides.length;
    slides[currentIdx].classList.add('is-active');

    // 2. Putar ribbon searah jarum jam (clockwise) dengan ease-in-out sinkron saat gambar berganti
    // 45 derajat per step selaras dengan 8 bilah simetri bintang EMF
    ribbonRotation += 45;
    if (ribbonImg) {
      ribbonImg.style.transform = 'rotate(' + ribbonRotation + 'deg)';
    }
  }, intervalTime);
});
</script>
