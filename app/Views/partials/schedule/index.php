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

    <!-- 2. Day Selector: Auto-hidden when SPEAKERS tab is selected -->
    <?php
      $formatScheduleDate = function($dateVal, $fallback) {
        if (!empty($dateVal)) {
          $ts = strtotime($dateVal);
          if ($ts !== false) {
            return date('F j, Y', $ts);
          }
          return $dateVal;
        }
        return $fallback;
      };

      // Determine default active day based on current date in Indonesia (WIB)
      $nowWib = (new \DateTime('now', new \DateTimeZone('Asia/Jakarta')))->format('Y-m-d');
      $day2DateRaw = $schedule['day_2']['date'] ?? '';
      $day2Ts = !empty($day2DateRaw) ? strtotime($day2DateRaw) : strtotime('2026-10-08');
      $day2Date = ($day2Ts !== false) ? date('Y-m-d', $day2Ts) : '2026-10-08';

      $defaultActiveDay = ($nowWib >= $day2Date) ? 'day_2' : 'day_1';
      $activeHeading = ($defaultActiveDay === 'day_2') 
        ? strtoupper($schedule['day_2']['label'] ?? 'DAY 2')
        : strtoupper($schedule['day_1']['label'] ?? 'DAY 1');
    ?>
    <div class="day-selector-wrapper" id="daySelectorWrapper">
      <div class="day-selector" role="tablist">
        <button class="day-btn <?= ($defaultActiveDay === 'day_1') ? 'active' : '' ?>" data-day="day_1" data-heading="<?= esc(strtoupper($schedule['day_1']['label'] ?? 'DAY 1')) ?>" role="tab">
          <?= esc($formatScheduleDate($schedule['day_1']['date'] ?? '', $schedule['day_1']['label'] ?? 'October 7, 2026')) ?>
        </button>
        <button class="day-btn <?= ($defaultActiveDay === 'day_2') ? 'active' : '' ?>" data-day="day_2" data-heading="<?= esc(strtoupper($schedule['day_2']['label'] ?? 'DAY 2')) ?>" role="tab">
          <?= esc($formatScheduleDate($schedule['day_2']['date'] ?? '', $schedule['day_2']['label'] ?? 'October 8, 2026')) ?>
        </button>
      </div>
    </div>

    <!-- Active Day Title between tab pill and schedule content -->
    <div class="schedule-day-title-wrap" id="scheduleDayTitleWrap">
      <h3 class="schedule-day-heading" id="scheduleDayHeading"><?= esc($activeHeading) ?></h3>
    </div>

    <!-- 3. Schedule Tab Content (Day 1 & Day 2 lists) -->
    <div class="schedule-list-view" id="scheduleListView">
      
      <!-- Day 1 Schedule -->
      <div class="day-schedule-content" data-day="day_1" style="display: <?= ($defaultActiveDay === 'day_1') ? 'block' : 'none' ?>;">
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
      <div class="day-schedule-content" data-day="day_2" style="display: <?= ($defaultActiveDay === 'day_2') ? 'block' : 'none' ?>;">
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
