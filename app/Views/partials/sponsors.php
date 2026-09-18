<section class="sponsors-section" id="sponsor">
  <div class="container">
    
    <!-- Group 1: SPONSORED BY -->
    <?php if (!empty($sponsors['sponsored_by'])): ?>
      <div class="sponsor-group">
        <div class="sponsor-badge">
          <?= esc($sponsors['sponsored_by']['badge'] ?? 'SPONSORED BY:') ?>
        </div>

        <?php if (!empty($sponsors['sponsored_by']['rows'])): ?>
          <?php /* MockData format */ ?>
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
          <?php /* CMS API dynamic tiers format */ ?>
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
          <?php /* MockData format */ ?>
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
          <?php /* CMS API dynamic tiers format */ ?>
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

  </div>
</section>
