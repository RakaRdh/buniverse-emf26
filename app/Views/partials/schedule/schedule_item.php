<?php
/**
 * Single row item in Schedule list
 * $item: array with time, room, title, is_break, moderator, speaker_ids, or is_multi_room & rooms
 * $allSpeakers: dictionary of speakers keyed by slug
 */
$isMultiRoom = !empty($item['is_multi_room']) && !empty($item['rooms']);
?>
<div class="schedule-row <?= !empty($item['is_break']) ? 'is-break' : '' ?> <?= $isMultiRoom ? 'is-multi-room-row' : '' ?>">
  
  <!-- Left Column: Time with clock icon -->
  <div class="schedule-meta-col">
    <div class="schedule-time">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <circle cx="12" cy="12" r="10"></circle>
        <polyline points="12 6 12 12 16 14"></polyline>
      </svg>
      <span><?= esc($item['time']) ?></span>
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

                  <!-- Moderator if any -->
                  <?php if (!empty($subSession['moderator'])): ?>
                    <div class="moderator-row">
                      <div class="moderator-photo">
                        <img src="<?= base_url('assets/img/' . esc($subSession['moderator']['photo'])) ?>" alt="<?= esc($subSession['moderator']['name']) ?>" loading="lazy">
                      </div>
                      <div class="moderator-text">
                        <div class="moderator-label"><?= esc($subSession['moderator']['label']) ?></div>
                        <div class="moderator-name-role"><?= esc($subSession['moderator']['name']) ?> (<?= esc($subSession['moderator']['role']) ?>)</div>
                      </div>
                    </div>
                  <?php endif; ?>

                  <!-- Speakers Grid -->
                  <?php if (!empty($subSession['speaker_ids'])): ?>
                    <div class="plenary-speaker-grid">
                      <?php foreach ($subSession['speaker_ids'] as $spKey): ?>
                        <?php if (isset($allSpeakers[$spKey])): ?>
                          <?= view('partials/schedule/speaker_card', ['speaker' => $allSpeakers[$spKey]]) ?>
                        <?php endif; ?>
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

      <!-- Optional Moderator -->
      <?php if (!empty($item['moderator'])): ?>
        <div class="moderator-row">
          <div class="moderator-photo">
            <img src="<?= base_url('assets/img/' . esc($item['moderator']['photo'])) ?>" alt="<?= esc($item['moderator']['name']) ?>" loading="lazy">
          </div>
          <div class="moderator-text">
            <div class="moderator-label"><?= esc($item['moderator']['label']) ?></div>
            <div class="moderator-name-role"><?= esc($item['moderator']['name']) ?> (<?= esc($item['moderator']['role']) ?>)</div>
          </div>
        </div>
      <?php endif; ?>

      <!-- Speakers List / Grid (Standardized width) -->
      <?php if (!empty($item['speaker_ids'])): ?>
        <div class="plenary-speaker-grid">
          <?php foreach ($item['speaker_ids'] as $spKey): ?>
            <?php if (isset($allSpeakers[$spKey])): ?>
              <?= view('partials/schedule/speaker_card', ['speaker' => $allSpeakers[$spKey]]) ?>
            <?php endif; ?>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

    <?php endif; ?>

  </div>

</div>

