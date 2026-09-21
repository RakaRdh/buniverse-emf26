<section class="schedule-section" id="schedule">
  <?= view('partials/section_decorations', ['sectionKey' => 'schedule', 'decorations' => $sectionDecorations ?? []]) ?>
  <?= view('partials/custom_placements', ['sectionKey' => 'schedule', 'customPlacements' => $customPlacements ?? []]) ?>
  <div class="container">
    
    <!-- 1. Main Tabs Header: SCHEDULE vs SPEAKERS -->
    <div class="tabs-container">
      <div class="main-tabs" role="tablist">
        <button class="main-tab-btn active" data-tab="schedule" role="tab" aria-selected="true">
          SCHEDULE
        </button>
        <button class="main-tab-btn" data-tab="speakers" role="tab" aria-selected="false">
          SPEAKERS
        </button>
      </div>
    </div>

    <!-- 2. Day Selector: Day 1 vs Day 2 (Auto-hidden when SPEAKERS tab is selected) -->
    <div class="day-selector-wrapper" id="daySelectorWrapper">
      <div class="day-selector" role="tablist">
        <button class="day-btn active" data-day="day_1" role="tab">
          <?= esc($schedule['day_1']['label']) ?>
        </button>
        <button class="day-btn" data-day="day_2" role="tab">
          <?= esc($schedule['day_2']['label']) ?>
        </button>
      </div>
    </div>

    <!-- 3. Schedule Tab Content (Day 1 & Day 2 lists) -->
    <div class="schedule-list-view" id="scheduleListView">
      
      <!-- Day 1 Schedule -->
      <div class="day-schedule-content" data-day="day_1" style="display: block;">
        <div class="schedule-list">
          <?php 
          $hasOpenedSpeakerCard = false;
          foreach ($schedule['day_1']['items'] as $item): 
            $hasSpeakers = !empty($item['speaker_ids']) || (!empty($item['rooms']) && array_filter($item['rooms'], fn($r) => !empty($r['sessions'])));
            $isDefaultOpen = false;
            if ($hasSpeakers && !$hasOpenedSpeakerCard) {
              $isDefaultOpen = true;
              $hasOpenedSpeakerCard = true;
            }
          ?>
            <?= view('partials/schedule/schedule_item', [
              'item'          => $item,
              'allSpeakers'   => $speakers,
              'isDefaultOpen' => $isDefaultOpen,
            ]) ?>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- Day 2 Schedule -->
      <div class="day-schedule-content" data-day="day_2" style="display: none;">
        <div class="schedule-list">
          <?php 
          $hasOpenedSpeakerCard2 = false;
          foreach ($schedule['day_2']['items'] as $item): 
            $hasSpeakers2 = !empty($item['speaker_ids']) || (!empty($item['rooms']) && array_filter($item['rooms'], fn($r) => !empty($r['sessions'])));
            $isDefaultOpen2 = false;
            if ($hasSpeakers2 && !$hasOpenedSpeakerCard2) {
              $isDefaultOpen2 = true;
              $hasOpenedSpeakerCard2 = true;
            }
          ?>
            <?= view('partials/schedule/schedule_item', [
              'item'          => $item,
              'allSpeakers'   => $speakers,
              'isDefaultOpen' => $isDefaultOpen2,
            ]) ?>
          <?php endforeach; ?>
        </div>
      </div>

    </div>

    <!-- 4. Standalone SPEAKERS Tab View (§9A) -->
    <?= view('partials/schedule/speakers_tab_view', ['speakers' => $speakers]) ?>

  </div>
</section>
