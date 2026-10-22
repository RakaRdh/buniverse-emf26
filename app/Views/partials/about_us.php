<?php
$overviewData = $overview ?? $economicModel ?? [];
$paragraphs = $overviewData['paragraphs'] ?? [];
?>
<section class="about-us-section editorial-section" id="about-us">
  <?= view('partials/section_decorations', ['sectionKey' => 'about-us', 'decorations' => $sectionDecorations ?? []]) ?>
  <?= view('partials/custom_placements', ['sectionKey' => 'about-us', 'customPlacements' => $customPlacements ?? []]) ?>

  <div class="about-ribbon-showcase-wrap" aria-hidden="true">
    <div class="about-ribbon-showcase">
      <?php /* Inner Framed Image: Selalu tegak (upright) dengan transisi rotate masuk searah jarum jam */ ?>
      <div class="about-ribbon-viewport">
        <div class="about-ribbon-rotor" id="aboutRibbonRotor">
          <div class="about-ribbon-slide slide-0 active" data-index="0">
            <img src="<?= base_url('assets/img/image1.png') ?>" alt="Event Showcase 1" loading="lazy">
          </div>
          <div class="about-ribbon-slide slide-1" data-index="1">
            <img src="<?= base_url('assets/img/image2.jpg') ?>" alt="Event Showcase 2" loading="lazy">
          </div>
          <div class="about-ribbon-slide slide-2" data-index="2">
            <img src="<?= base_url('assets/img/image3.jpg') ?>" alt="Event Showcase 3" loading="lazy">
          </div>
          <div class="about-ribbon-slide slide-3" data-index="3">
            <img src="<?= base_url('assets/img/image1_sq.png') ?>" alt="Event Showcase 4" loading="lazy">
          </div>
        </div>
      </div>

      <?php /* Overlaid Ribbon Frame: Step rotation clockwise */ ?>
      <div class="about-ribbon-frame" id="aboutRibbonFrame">
        <img src="<?= base_url('assets/img/ribbon.png') ?>" alt="Ribbon Frame" class="about-ribbon-img">
      </div>
    </div>
  </div>

  <div class="container">
    <div class="about-us-grid editorial-grid">

      <?php /* Kolom Kiri Dikosongkan untuk tempat Ribbon Frame */ ?>
      <div class="about-us-left-spacer" aria-hidden="true"></div>

      <?php /* Kolom Kanan: Posisi Asli Tetap di Dalam Container */ ?>
      <div class="about-us-right editorial-right">
        <div class="topic-badge">
          <?= esc(!empty($overviewData['badge']) && $overviewData['badge'] !== "INDONESIA'S ECONOMIC MODEL" ? $overviewData['badge'] : "ABOUT US") ?>
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

<script>
  document.addEventListener('DOMContentLoaded', function () {
    var frame = document.getElementById('aboutRibbonFrame');
    var slides = document.querySelectorAll('.about-ribbon-slide');
    if (!frame || slides.length === 0) return;

    var currentStep = 0;
    var currentIndex = 0;
    var total = slides.length;
    var stepAngle = 90; // 90 derajat per step searah jarum jam (clockwise)

    setInterval(function () {
      currentStep++;
      var prevIndex = currentIndex;
      var nextIndex = currentStep % total;
      var rotationDeg = currentStep * stepAngle;

      // Ribbon berputar clockwise
      frame.style.transform = 'rotate(' + rotationDeg + 'deg)';

      // Gambar lama keluar berputar clockwise (+70deg)
      slides[prevIndex].classList.remove('active');
      slides[prevIndex].classList.add('leaving');

      // Gambar baru masuk berputar clockwise mendarat tegak di 0deg (view asli)
      slides[nextIndex].classList.remove('leaving');
      slides[nextIndex].classList.add('active');

      setTimeout(function () {
        slides[prevIndex].classList.remove('leaving');
      }, 900);

      currentIndex = nextIndex;
    }, 3500);
  });
</script>