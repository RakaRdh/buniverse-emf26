<section class="sponsors-section" id="sponsor">
  <?= view('partials/section_decorations', ['sectionKey' => 'sponsor', 'decorations' => $sectionDecorations ?? []]) ?>
  <?= view('partials/custom_placements', ['sectionKey' => 'sponsor', 'customPlacements' => $customPlacements ?? []]) ?>

  <div class="container">
    <?php
      $sponsorDisplayMode = $sponsors['display_mode'] ?? ($sponsorPartnership['sponsor_display_mode'] ?? 'dedicated');
      $hasDedicatedSponsors = false;

      // Collect active tiers for sponsored_by
      $activeSponsoredTiers = [];
      if (!empty($sponsors['sponsored_by']) && is_array($sponsors['sponsored_by'])) {
          foreach ($sponsors['sponsored_by'] as $st) {
              if (!empty($st['items'])) {
                  $activeSponsoredTiers[] = $st;
                  $hasDedicatedSponsors = true;
              }
          }
      }

      // Collect active tiers for collaborations
      $activeCollabTiers = [];
      if (!empty($sponsors['collaborations']) && is_array($sponsors['collaborations'])) {
          foreach ($sponsors['collaborations'] as $ct) {
              if (!empty($ct['items'])) {
                  $activeCollabTiers[] = $ct;
                  $hasDedicatedSponsors = true;
              }
          }
      }

      // Collect active tiers for partnerships
      $activePartnerTiers = [];
      if (!empty($sponsors['partnerships']) && is_array($sponsors['partnerships'])) {
          foreach ($sponsors['partnerships'] as $pt) {
              if (!empty($pt['items'])) {
                  $activePartnerTiers[] = $pt;
                  $hasDedicatedSponsors = true;
              }
          }
      }

      $marqueeCounter = 0; // Tracks zig-zag alternating marquee scroll direction
    ?>

    <?php if ($sponsorDisplayMode === 'package' && !empty($sponsorPartnership['is_active']) && !empty($sponsorPartnership['logo_url'])): ?>
      <!-- Mode: Single Consolidated Package Banner -->
      <?php 
        $bannerUrl = $sponsorPartnership['logo_url'];
        $bannerName = !empty($sponsorPartnership['name']) ? $sponsorPartnership['name'] : 'Sponsors & Partnerships';
      ?>
      <div class="sponsor-single-wrapper">
        <img src="<?= esc($bannerUrl) ?>" 
             alt="<?= esc($bannerName) ?>" 
             class="sponsor-banner-img" 
             loading="lazy">
      </div>

    <?php elseif ($hasDedicatedSponsors): ?>
      <!-- Mode: Dedicated Sponsors (Tier-Based Dynamic Grid & Zig-zag Marquee) -->

      <!-- Group 1: SPONSORED BY -->
      <?php if (!empty($activeSponsoredTiers)): ?>
        <div class="sponsor-group">
          <div class="sponsor-badge">SPONSORED BY:</div>

          <?php 
            $rank = 0;
            foreach ($activeSponsoredTiers as $tier): 
              $rank++;
              $tierTitle = $tier['tier_name'] ?? $tier['name'] ?? '';
              $itemCount = count($tier['items'] ?? []);

              // Sizing & Marquee Rule:
              // Rank 1 (Top tier, e.g. Platinum if present, else Gold): max 3 logo for grid. >3 => Marquee.
              // Other ranks (Rank 2, 3, etc): max 4 logo for grid. >4 => Marquee.
              if ($rank === 1) {
                  $isMarquee = ($itemCount > 3);
                  $sizeClass = 'tier-size-rank-1';
              } else {
                  $isMarquee = ($itemCount > 4);
                  $sizeClass = 'tier-size-rank-' . min($rank, 4);
              }

              $scrollDirClass = '';
              if ($isMarquee) {
                  $scrollDirClass = ($marqueeCounter % 2 === 0) ? 'marquee-dir-left' : 'marquee-dir-right';
                  $marqueeCounter++;
              }
          ?>
            <div class="sponsor-tier-block <?= $sizeClass ?> <?= $isMarquee ? 'is-marquee-tier' : 'is-grid-tier' ?>">
              <div class="sponsor-tier-title"><?= esc($tierTitle) ?></div>

              <?php if (!$isMarquee): ?>
                <!-- Static Centered Grid -->
                <div class="sponsor-grid-row">
                  <?php foreach ($tier['items'] as $item): ?>
                    <div class="sponsor-logo-item" title="<?= esc($item['name']) ?>">
                      <?php if (!empty($item['website_url'])): ?>
                        <a href="<?= esc($item['website_url']) ?>" target="_blank" rel="noopener noreferrer">
                          <img src="<?= esc($item['logo']) ?>" alt="<?= esc($item['name']) ?>" loading="lazy">
                        </a>
                      <?php else: ?>
                        <img src="<?= esc($item['logo']) ?>" alt="<?= esc($item['name']) ?>" loading="lazy">
                      <?php endif; ?>
                    </div>
                  <?php endforeach; ?>
                </div>
              <?php else: ?>
                <!-- Zig-zag Autoscroll Marquee -->
                <div class="sponsor-marquee-container" aria-label="<?= esc($tierTitle) ?> Sponsors">
                  <div class="sponsor-marquee-track <?= $scrollDirClass ?>">
                    <!-- Set 1 -->
                    <div class="sponsor-marquee-content">
                      <?php foreach ($tier['items'] as $item): ?>
                        <div class="sponsor-logo-item" title="<?= esc($item['name']) ?>">
                          <?php if (!empty($item['website_url'])): ?>
                            <a href="<?= esc($item['website_url']) ?>" target="_blank" rel="noopener noreferrer">
                              <img src="<?= esc($item['logo']) ?>" alt="<?= esc($item['name']) ?>" loading="lazy">
                            </a>
                          <?php else: ?>
                            <img src="<?= esc($item['logo']) ?>" alt="<?= esc($item['name']) ?>" loading="lazy">
                          <?php endif; ?>
                        </div>
                      <?php endforeach; ?>
                    </div>
                    <!-- Set 2 for seamless loop -->
                    <div class="sponsor-marquee-content" aria-hidden="true">
                      <?php foreach ($tier['items'] as $item): ?>
                        <div class="sponsor-logo-item" title="<?= esc($item['name']) ?>">
                          <?php if (!empty($item['website_url'])): ?>
                            <a href="<?= esc($item['website_url']) ?>" target="_blank" rel="noopener noreferrer" tabindex="-1">
                              <img src="<?= esc($item['logo']) ?>" alt="<?= esc($item['name']) ?>" loading="lazy">
                            </a>
                          <?php else: ?>
                            <img src="<?= esc($item['logo']) ?>" alt="<?= esc($item['name']) ?>" loading="lazy">
                          <?php endif; ?>
                        </div>
                      <?php endforeach; ?>
                    </div>
                  </div>
                </div>
              <?php endif; ?>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

      <!-- Group 2: COLLABORATION WITH -->
      <?php if (!empty($activeCollabTiers)): ?>
        <div class="sponsor-group sponsor-group-collaborations">
          <div class="sponsor-badge">COLLABORATION WITH:</div>

          <?php foreach ($activeCollabTiers as $tier): ?>
            <?php 
              $itemCount = count($tier['items'] ?? []);
              $isMarquee = ($itemCount > 4);
              $sizeClass = 'tier-size-rank-3';

              $scrollDirClass = '';
              if ($isMarquee) {
                  $scrollDirClass = ($marqueeCounter % 2 === 0) ? 'marquee-dir-left' : 'marquee-dir-right';
                  $marqueeCounter++;
              }
            ?>
            <div class="sponsor-tier-block <?= $sizeClass ?> <?= $isMarquee ? 'is-marquee-tier' : 'is-grid-tier' ?>">
              <?php if (!$isMarquee): ?>
                <div class="sponsor-grid-row">
                  <?php foreach ($tier['items'] as $item): ?>
                    <div class="sponsor-logo-item" title="<?= esc($item['name']) ?>">
                      <?php if (!empty($item['website_url'])): ?>
                        <a href="<?= esc($item['website_url']) ?>" target="_blank" rel="noopener noreferrer">
                          <img src="<?= esc($item['logo']) ?>" alt="<?= esc($item['name']) ?>" loading="lazy">
                        </a>
                      <?php else: ?>
                        <img src="<?= esc($item['logo']) ?>" alt="<?= esc($item['name']) ?>" loading="lazy">
                      <?php endif; ?>
                    </div>
                  <?php endforeach; ?>
                </div>
              <?php else: ?>
                <div class="sponsor-marquee-container" aria-label="Collaboration With">
                  <div class="sponsor-marquee-track <?= $scrollDirClass ?>">
                    <!-- Set 1 -->
                    <div class="sponsor-marquee-content">
                      <?php foreach ($tier['items'] as $item): ?>
                        <div class="sponsor-logo-item" title="<?= esc($item['name']) ?>">
                          <?php if (!empty($item['website_url'])): ?>
                            <a href="<?= esc($item['website_url']) ?>" target="_blank" rel="noopener noreferrer">
                              <img src="<?= esc($item['logo']) ?>" alt="<?= esc($item['name']) ?>" loading="lazy">
                            </a>
                          <?php else: ?>
                            <img src="<?= esc($item['logo']) ?>" alt="<?= esc($item['name']) ?>" loading="lazy">
                          <?php endif; ?>
                        </div>
                      <?php endforeach; ?>
                    </div>
                    <!-- Set 2 for seamless loop -->
                    <div class="sponsor-marquee-content" aria-hidden="true">
                      <?php foreach ($tier['items'] as $item): ?>
                        <div class="sponsor-logo-item" title="<?= esc($item['name']) ?>">
                          <?php if (!empty($item['website_url'])): ?>
                            <a href="<?= esc($item['website_url']) ?>" target="_blank" rel="noopener noreferrer" tabindex="-1">
                              <img src="<?= esc($item['logo']) ?>" alt="<?= esc($item['name']) ?>" loading="lazy">
                            </a>
                          <?php else: ?>
                            <img src="<?= esc($item['logo']) ?>" alt="<?= esc($item['name']) ?>" loading="lazy">
                          <?php endif; ?>
                        </div>
                      <?php endforeach; ?>
                    </div>
                  </div>
                </div>
              <?php endif; ?>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

      <!-- Group 3: PARTNERSHIP -->
      <?php if (!empty($activePartnerTiers)): ?>
        <div class="sponsor-group sponsor-group-partnerships">
          <div class="sponsor-badge">PARTNERSHIP:</div>

          <?php foreach ($activePartnerTiers as $tier): ?>
            <?php 
              $partnerTierTitle = $tier['tier_name'] ?? $tier['name'] ?? '';
              $itemCount = count($tier['items'] ?? []);
              $isMarquee = ($itemCount > 4);
              $sizeClass = 'tier-size-rank-4';

              $scrollDirClass = '';
              if ($isMarquee) {
                  $scrollDirClass = ($marqueeCounter % 2 === 0) ? 'marquee-dir-left' : 'marquee-dir-right';
                  $marqueeCounter++;
              }
            ?>
            <div class="sponsor-tier-block <?= $sizeClass ?> <?= $isMarquee ? 'is-marquee-tier' : 'is-grid-tier' ?>">
              <?php if (!empty($partnerTierTitle) && strtolower($partnerTierTitle) !== 'partnership' && strtolower($partnerTierTitle) !== 'official partnerships'): ?>
                <div class="sponsor-tier-title"><?= esc($partnerTierTitle) ?></div>
              <?php endif; ?>

              <?php if (!$isMarquee): ?>
                <div class="sponsor-grid-row">
                  <?php foreach ($tier['items'] as $item): ?>
                    <div class="sponsor-logo-item" title="<?= esc($item['name']) ?>">
                      <?php if (!empty($item['website_url'])): ?>
                        <a href="<?= esc($item['website_url']) ?>" target="_blank" rel="noopener noreferrer">
                          <img src="<?= esc($item['logo']) ?>" alt="<?= esc($item['name']) ?>" loading="lazy">
                        </a>
                      <?php else: ?>
                        <img src="<?= esc($item['logo']) ?>" alt="<?= esc($item['name']) ?>" loading="lazy">
                      <?php endif; ?>
                    </div>
                  <?php endforeach; ?>
                </div>
              <?php else: ?>
                <div class="sponsor-marquee-container" aria-label="Official Partnerships">
                  <div class="sponsor-marquee-track <?= $scrollDirClass ?>">
                    <!-- Set 1 -->
                    <div class="sponsor-marquee-content">
                      <?php foreach ($tier['items'] as $item): ?>
                        <div class="sponsor-logo-item" title="<?= esc($item['name']) ?>">
                          <?php if (!empty($item['website_url'])): ?>
                            <a href="<?= esc($item['website_url']) ?>" target="_blank" rel="noopener noreferrer">
                              <img src="<?= esc($item['logo']) ?>" alt="<?= esc($item['name']) ?>" loading="lazy">
                            </a>
                          <?php else: ?>
                            <img src="<?= esc($item['logo']) ?>" alt="<?= esc($item['name']) ?>" loading="lazy">
                          <?php endif; ?>
                        </div>
                      <?php endforeach; ?>
                    </div>
                    <!-- Set 2 for seamless loop -->
                    <div class="sponsor-marquee-content" aria-hidden="true">
                      <?php foreach ($tier['items'] as $item): ?>
                        <div class="sponsor-logo-item" title="<?= esc($item['name']) ?>">
                          <?php if (!empty($item['website_url'])): ?>
                            <a href="<?= esc($item['website_url']) ?>" target="_blank" rel="noopener noreferrer" tabindex="-1">
                              <img src="<?= esc($item['logo']) ?>" alt="<?= esc($item['name']) ?>" loading="lazy">
                            </a>
                          <?php else: ?>
                            <img src="<?= esc($item['logo']) ?>" alt="<?= esc($item['name']) ?>" loading="lazy">
                          <?php endif; ?>
                        </div>
                      <?php endforeach; ?>
                    </div>
                  </div>
                </div>
              <?php endif; ?>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

    <?php else: ?>
      <!-- Placeholder Sponsors & Partnerships Empty State Box -->
      <div class="news-empty-state sponsor-empty-state">
        <p class="news-empty-text">Belum ada sponsor saat ini</p>
      </div>
    <?php endif; ?>

  </div>
</section>
