<?php
/**
 * Single row item in Schedule list
 * $item: array with time, room, title, is_break, moderator, speaker_ids, or is_multi_room & rooms
 * $allSpeakers: dictionary of speakers keyed by slug
 */
$isMultiRoom = !empty($item['is_multi_room']) && !empty($item['rooms']);
$activeClass = !empty($isDefaultOpen) ? 'active' : '';
?>
<div class="schedule-row <?= !empty($item['is_break']) ? 'is-break' : '' ?> <?= $isMultiRoom ? 'is-multi-room-row' : '' ?> <?= $activeClass ?>">
  
  <!-- Left Column: Time with clock icon -->
  <div class="schedule-meta-col">
    <div class="schedule-time">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <circle cx="12" cy="12" r="10"></circle>
        <polyline points="12 6 12 12 16 14"></polyline>
      </svg>
      <span><?= esc(format_schedule_time($item['time'] ?? '', $item['start_time'] ?? null, $item['end_time'] ?? null)) ?></span>
    </div>
  </div>

  <!-- Right Column: Room Pill, Session Title, Moderator, Speakers -->
  <div class="schedule-content-col">
    
    <?php if ($isMultiRoom): ?>
      <!-- Multi-Room Parent Title (e.g. Special Session A) -->
      <h3 class="session-title"><?= esc($item['title']) ?></h3>

      <!-- Multi-Room Container / Rooms Grid -->
      <div class="multi-room-container">
        <?php foreach ($item['rooms'] as $roomData): ?>
          <div class="multi-room-card">
            
            <!-- Room Pill -->
            <?php if (!empty($roomData['room'])): ?>
              <div class="room-pill">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                  <circle cx="12" cy="10" r="3"></circle>
                </svg>
                <span><?= esc($roomData['room']) ?></span>
              </div>
            <?php endif; ?>

            <!-- Sessions within this Room -->
            <div class="room-sessions-list">
              <?php foreach ($roomData['sessions'] as $subSession): ?>
                <div class="room-subsession">
                  <h4 class="subsession-title"><?= esc($subSession['title']) ?></h4>
                  <?php if (!empty($subSession['subtitle'])): ?>
                    <div class="subsession-subtitle" style="font-size: 14px; font-weight: 500; color: rgba(255, 255, 255, 0.82); margin-top: 3px; line-height: 1.4;">
                      <?= esc($subSession['subtitle']) ?>
                    </div>
                  <?php endif; ?>

                  <!-- Moderator if any -->
                  <?php if (!empty($subSession['moderator'])): 
                    $subModPhoto = $subSession['moderator']['photo'] ?? 'placeholder.png';
                    if (empty($subModPhoto)) {
                        $subModPhoto = 'placeholder.png';
                    }
                    if (!str_starts_with($subModPhoto, 'http')) {
                        $subModPhoto = base_url('assets/img/' . ltrim($subModPhoto, '/'));
                    }
                  ?>
                    <div class="moderator-row">
                      <div class="moderator-photo">
                        <img src="<?= esc($subModPhoto) ?>" alt="<?= esc($subSession['moderator']['name']) ?>" loading="lazy">
                      </div>
                      <div class="moderator-text">
                        <div class="moderator-label"><?= esc($subSession['moderator']['label']) ?></div>
                        <div class="moderator-name-role"><?= esc($subSession['moderator']['name']) ?> (<?= esc($subSession['moderator']['role']) ?>)</div>
                      </div>
                    </div>
                  <?php endif; ?>

                  <!-- Speakers Grid -->
                  <?php 
                  $subSpeakersToRender = [];
                  if (!empty($subSession['speakers'])) {
                      $subSpeakersToRender = $subSession['speakers'];
                  } elseif (!empty($subSession['speaker_ids'])) {
                      foreach ($subSession['speaker_ids'] as $spKey) {
                          if (isset($allSpeakers[$spKey])) {
                              $subSpeakersToRender[] = $allSpeakers[$spKey];
                          }
                      }
                  }
                  ?>
                  <?php if (!empty($subSpeakersToRender)): ?>
                    <div class="plenary-speaker-grid">
                      <?php foreach ($subSpeakersToRender as $spkItem): ?>
                        <?= view('partials/schedule/speaker_card', ['speaker' => $spkItem]) ?>
                      <?php endforeach; ?>
                    </div>
                  <?php endif; ?>
                </div>
              <?php endforeach; ?>
            </div>

          </div>
        <?php endforeach; ?>
      </div>

    <?php else: ?>

      <!-- Standard Single Room / Plenary Session Item -->
      <?php if (!empty($item['room'])): ?>
        <div class="room-pill">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
            <circle cx="12" cy="10" r="3"></circle>
          </svg>
          <span><?= esc($item['room']) ?></span>
        </div>
      <?php endif; ?>

      <h3 class="session-title"><?= esc($item['title']) ?></h3>
      <?php if (!empty($item['subtitle'])): ?>
        <div class="session-subtitle" style="font-size: 15px; font-weight: 500; color: rgba(255, 255, 255, 0.85); margin-top: 4px; margin-bottom: 8px; line-height: 1.4;">
          <?= esc($item['subtitle']) ?>
        </div>
      <?php endif; ?>

      <?php 
      $hasPengantar = !empty($item['pengantar']);
      $speakersToRender = [];
      if (!empty($item['speakers'])) {
          $speakersToRender = $item['speakers'];
      } elseif (!empty($item['speaker_ids'])) {
          foreach ($item['speaker_ids'] as $spKey) {
              if (isset($allSpeakers[$spKey])) {
                  $speakersToRender[] = $allSpeakers[$spKey];
              }
          }
      }
      ?>

      <?php if ($hasPengantar): ?>
        <!-- Layout Khusus dengan Pengantar (Sesuai Gambar 3) -->
        <div class="session-speakers-pengantar-wrap">
          
          <!-- MODERATOR (jika ada) -->
          <?php if (!empty($item['moderator'])): 
            $singleModPhoto = $item['moderator']['photo'] ?? 'placeholder.png';
            if (empty($singleModPhoto)) $singleModPhoto = 'placeholder.png';
            if (!str_starts_with($singleModPhoto, 'http')) {
                $singleModPhoto = base_url('assets/img/' . ltrim($singleModPhoto, '/'));
            }
          ?>
            <div class="schedule-group-section">
              <div class="schedule-group-label">Moderator:</div>
              <div class="moderator-row">
                <div class="moderator-photo">
                  <img src="<?= esc($singleModPhoto) ?>" alt="<?= esc($item['moderator']['name']) ?>" loading="lazy">
                </div>
                <div class="moderator-text">
                  <div class="moderator-name-role"><?= esc($item['moderator']['name']) ?> (<?= esc($item['moderator']['role']) ?>)</div>
                </div>
              </div>
            </div>
          <?php endif; ?>

          <!-- PENGANTAR -->
          <div class="schedule-group-section">
            <div class="schedule-group-label">Introductory Speech:</div>
            <div class="plenary-speaker-grid">
              <?php foreach ($item['pengantar'] as $pngItem): ?>
                <?= view('partials/schedule/speaker_card', ['speaker' => $pngItem]) ?>
              <?php endforeach; ?>
            </div>
          </div>

          <!-- SPEAKER -->
          <?php if (!empty($speakersToRender)): ?>
            <div class="schedule-group-section">
              <div class="schedule-group-label">Speaker:</div>
              <div class="plenary-speaker-grid">
                <?php foreach ($speakersToRender as $spkItem): ?>
                  <?= view('partials/schedule/speaker_card', ['speaker' => $spkItem]) ?>
                <?php endforeach; ?>
              </div>
            </div>
          <?php endif; ?>

        </div>

      <?php else: ?>

        <!-- Standard Layout (Tanpa Pengantar) -->
        <!-- Optional Moderator -->
        <?php if (!empty($item['moderator'])): 
          $singleModPhoto = $item['moderator']['photo'] ?? 'placeholder.png';
          if (empty($singleModPhoto)) {
              $singleModPhoto = 'placeholder.png';
          }
          if (!str_starts_with($singleModPhoto, 'http')) {
              $singleModPhoto = base_url('assets/img/' . ltrim($singleModPhoto, '/'));
          }
        ?>
          <div class="moderator-row">
            <div class="moderator-photo">
              <img src="<?= esc($singleModPhoto) ?>" alt="<?= esc($item['moderator']['name']) ?>" loading="lazy">
            </div>
            <div class="moderator-text">
              <div class="moderator-label"><?= esc($item['moderator']['label']) ?></div>
              <div class="moderator-name-role"><?= esc($item['moderator']['name']) ?> (<?= esc($item['moderator']['role']) ?>)</div>
            </div>
          </div>
        <?php endif; ?>

        <!-- Speakers List / Grid (Standardized width) -->
        <?php if (!empty($speakersToRender)): ?>
          <div class="plenary-speaker-grid">
            <?php foreach ($speakersToRender as $spkItem): ?>
              <?= view('partials/schedule/speaker_card', ['speaker' => $spkItem]) ?>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>

      <?php endif; ?>

    <?php endif; ?>

  </div>

</div>

