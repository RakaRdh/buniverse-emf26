<?php
  // Multi-stream support
  $streamsList = $livestreams ?? [];
  $legacyStream = $livestream ?? [];

  // If streamsList is empty but legacyStream is active, use legacyStream
  if (empty($streamsList) && !empty($legacyStream['is_active']) && !empty($legacyStream['youtube_url'])) {
      $streamsList = [$legacyStream];
  }

  // Filter only active streams
  $activeStreams = array_values(array_filter($streamsList, function($s) {
      return !empty($s['is_active']) && !empty(trim($s['youtube_url'] ?? ''));
  }));

  if (empty($activeStreams)) {
      return; // Do not render section if no active streams
  }

  // Helper to extract video ID & format embed URL
  $getEmbedUrl = function($rawUrl) {
      $vid = 'mKr5aNganlc';
      if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?|live)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $rawUrl, $match)) {
          $vid = $match[1];
      } elseif (strlen(trim($rawUrl)) === 11 && !str_contains($rawUrl, '/')) {
          $vid = trim($rawUrl);
      }
      return "https://www.youtube.com/embed/{$vid}?rel=0&modestbranding=1&playsinline=1";
  };

  $firstStream = $activeStreams[0];
  $firstEmbed = $getEmbedUrl($firstStream['youtube_url']);
?>
<section class="livestream-section" id="livestream">
  <?= view('partials/section_decorations', ['sectionKey' => 'livestream', 'decorations' => $sectionDecorations ?? []]) ?>
  <?= view('partials/custom_placements', ['sectionKey' => 'livestream', 'customPlacements' => $customPlacements ?? []]) ?>
  <div class="container">
    
    <?php /* Section Header Centered: Keep generic title as requested */ ?>
    <div class="section-header-center">
      <h2 class="section-title">Live Streaming</h2>
    </div>

    <?php if (count($activeStreams) > 1): ?>
      <?php /* Multi-Room / Multi-Stream Selector Pills */ ?>
      <div class="livestream-room-selector" role="tablist" aria-label="Livestream Rooms">
        <?php foreach ($activeStreams as $idx => $st): 
          $embed = $getEmbedUrl($st['youtube_url']);
          $roomTitle = !empty(trim($st['title'] ?? '')) ? trim($st['title']) : ('Stream ' . ($idx + 1));
        ?>
          <button 
            type="button" 
            class="livestream-room-pill <?= $idx === 0 ? 'active' : '' ?>" 
            data-embed-url="<?= esc($embed) ?>"
            data-room-name="<?= esc($roomTitle) ?>"
            onclick="switchLivestreamRoom(this)"
            role="tab"
            aria-selected="<?= $idx === 0 ? 'true' : 'false' ?>"
          >
            <span class="room-pill-label"><?= esc($roomTitle) ?></span>
          </button>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <?php /* Live Stream Video Player Embed */ ?>
    <div class="livestream-player-wrapper">
      <div class="livestream-aspect-box">
        <iframe 
          id="mainLivestreamIframe"
          src="<?= esc($firstEmbed) ?>" 
          title="<?= esc($firstStream['title'] ?? 'Live Streaming') ?>" 
          frameborder="0" 
          allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
          referrerpolicy="strict-origin-when-cross-origin" 
          allowfullscreen>
        </iframe>
      </div>
    </div>

  </div>
</section>

<?php if (count($activeStreams) > 1): ?>
<script>
function switchLivestreamRoom(btn) {
  if (!btn || btn.classList.contains('active')) return;

  const selector = btn.closest('.livestream-room-selector');
  if (selector) {
    selector.querySelectorAll('.livestream-room-pill').forEach(el => {
      el.classList.remove('active');
      el.setAttribute('aria-selected', 'false');
    });
  }

  btn.classList.add('active');
  btn.setAttribute('aria-selected', 'true');

  const newEmbedUrl = btn.getAttribute('data-embed-url');
  const iframe = document.getElementById('mainLivestreamIframe');
  if (iframe && newEmbedUrl) {
    iframe.src = newEmbedUrl;
  }
}
</script>
<?php endif; ?>
