<section class="sponsors-section" id="sponsor">
  <?= view('partials/section_decorations', ['sectionKey' => 'sponsor', 'decorations' => $sectionDecorations ?? []]) ?>
  <?= view('partials/custom_placements', ['sectionKey' => 'sponsor', 'customPlacements' => $customPlacements ?? []]) ?>

  <div class="container">
    <?php if (!empty($sponsorPartnership) && !empty($sponsorPartnership['logo_url'])): ?>
      <!-- Single Consolidated Sponsors & Partnerships Banner -->
      <div class="sponsor-single-wrapper">
        <img src="<?= esc($sponsorPartnership['logo_url']) ?>" 
             alt="<?= esc($sponsorPartnership['name'] ?: 'Sponsors & Partnerships') ?>" 
             class="sponsor-banner-img" 
             loading="lazy">
      </div>
    <?php else: ?>
      <!-- Fallback or empty state if no single banner is uploaded yet -->
      <div class="sponsor-single-wrapper sponsor-placeholder-wrapper" style="text-align: center; padding: 30px 20px;">
        <span style="font-size: 13px; letter-spacing: 2px; text-transform: uppercase; color: var(--gold-light, #d4af37); font-weight: 600; opacity: 0.85;">
          SPONSORS & PARTNERSHIPS
        </span>
      </div>
    <?php endif; ?>

    <?php /* === LEGACY TIER-BASED SPONSORS (COMMENTED OUT) ===
    <!-- Group 1: SPONSORED BY -->
    <?php if (!empty($sponsors['sponsored_by'])): ?>
      <div class="sponsor-group">
        <div class="sponsor-badge">
          <?= esc($sponsors['sponsored_by']['badge'] ?? 'SPONSORED BY:') ?>
        </div>

        <?php if (!empty($sponsors['sponsored_by']['rows'])): ?>
          <?php foreach ($sponsors['sponsored_by']['rows'] as $row): ?>
            <div class="sponsor-row <?= esc($row['tier']) ?>">
              <?php foreach ($row['logos'] as $item): ?>
                <div class="sponsor-logo-item" title="<?= esc($item['name']) ?>">
                  <img src="<?= base_url('assets/img/' . esc($item['logo'])) ?>" 
                       alt="<?= esc($item['name']) ?>" 
                       loading="lazy">
                </div>
              <?php endforeach; ?>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <?php foreach ($sponsors['sponsored_by'] as $tier): ?>
            <?php if (!empty($tier['items'])): ?>
              <div class="sponsor-row tier-<?= esc($tier['logo_size'] ?? 'md') ?>">
                <?php foreach ($tier['items'] as $item): ?>
                  <div class="sponsor-logo-item" title="<?= esc($item['name']) ?>">
                    <img src="<?= esc($item['logo']) ?>" 
                         alt="<?= esc($item['name']) ?>" 
                         loading="lazy">
                  </div>
                <?php endforeach; ?>
              </div>
            <?php endif; ?>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    <?php endif; ?>

    <!-- Group 2: PARTNERSHIP -->
    <?php if (!empty($sponsors['partnerships'])): ?>
      <div class="sponsor-group">
        <div class="sponsor-badge">
          <?= esc($sponsors['partnerships']['badge'] ?? 'PARTNERSHIP:') ?>
        </div>

        <?php if (!empty($sponsors['partnerships']['rows'])): ?>
          <?php foreach ($sponsors['partnerships']['rows'] as $row): ?>
            <div class="sponsor-row <?= esc($row['tier']) ?>">
              <?php foreach ($row['logos'] as $item): ?>
                <div class="sponsor-logo-item" title="<?= esc($item['name']) ?>">
                  <img src="<?= base_url('assets/img/' . esc($item['logo'])) ?>" 
                       alt="<?= esc($item['name']) ?>" 
                       loading="lazy">
                </div>
              <?php endforeach; ?>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <?php foreach ($sponsors['partnerships'] as $tier): ?>
            <?php if (!empty($tier['items'])): ?>
              <div class="sponsor-row tier-<?= esc($tier['logo_size'] ?? 'sm') ?>">
                <?php foreach ($tier['items'] as $item): ?>
                  <div class="sponsor-logo-item" title="<?= esc($item['name']) ?>">
                    <img src="<?= esc($item['logo']) ?>" 
                         alt="<?= esc($item['name']) ?>" 
                         loading="lazy">
                  </div>
                <?php endforeach; ?>
              </div>
            <?php endif; ?>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    <?php endif; ?>
    === END LEGACY TIER-BASED SPONSORS === */ ?>

  </div>
</section>
