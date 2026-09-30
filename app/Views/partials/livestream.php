<?php
  $liveData = $livestream ?? [];
  $isLiveActive = !empty($liveData['is_active']);
  if (!$isLiveActive) {
      return; // Do not render section if disabled in CMS
  }

  $rawUrl = $liveData['youtube_url'] ?? 'https://www.youtube.com/watch?v=rFZHOHl-L8A';
  $title = $liveData['title'] ?? 'Live Stream';

  // Extract YouTube ID if full URL provided
  $videoId = 'rFZHOHl-L8A';
  if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $rawUrl, $match)) {
      $videoId = $match[1];
  } elseif (strlen(trim($rawUrl)) === 11 && !str_contains($rawUrl, '/')) {
      $videoId = trim($rawUrl);
  }
  $embedUrl = "https://www.youtube.com/embed/{$videoId}?rel=0&modestbranding=1&playsinline=1";
?>
<section class="livestream-section" id="livestream">
  <?= view('partials/section_decorations', ['sectionKey' => 'livestream', 'decorations' => $sectionDecorations ?? []]) ?>
  <?= view('partials/custom_placements', ['sectionKey' => 'livestream', 'customPlacements' => $customPlacements ?? []]) ?>
  <div class="container">
    
    <?php /* Section Header Centered */ ?>
    <div class="section-header-center">
      <h2 class="section-title"><?= esc($title) ?></h2>
    </div>

    <?php /* Live Stream Video Player Embed */ ?>
    <div class="livestream-player-wrapper">
      <div class="livestream-aspect-box">
        <iframe 
          src="<?= esc($embedUrl) ?>" 
          title="<?= esc($title) ?>" 
          frameborder="0" 
          allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
          referrerpolicy="strict-origin-when-cross-origin" 
          allowfullscreen>
        </iframe>
      </div>
    </div>

  </div>
</section>
