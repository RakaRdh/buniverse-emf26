<section class="livestream-section" id="livestream">
  <?= view('partials/section_decorations', ['sectionKey' => 'livestream', 'decorations' => $sectionDecorations ?? []]) ?>
  <?= view('partials/custom_placements', ['sectionKey' => 'livestream', 'customPlacements' => $customPlacements ?? []]) ?>
  <div class="container">
    
    <!-- Section Header Centered -->
    <div class="section-header-center">
      <h2 class="section-title">Live Streaming</h2>
    </div>

    <!-- Live Stream Video Player Embed -->
    <div class="livestream-player-wrapper">
      <div class="livestream-aspect-box">
        <iframe 
          src="https://www.youtube.com/embed/rFZHOHl-L8A?rel=0&modestbranding=1&playsinline=1" 
          title="Investor Daily Summit 2026 Live Stream" 
          frameborder="0" 
          allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
          referrerpolicy="strict-origin-when-cross-origin" 
          allowfullscreen>
        </iframe>
      </div>
    </div>

  </div>
</section>
