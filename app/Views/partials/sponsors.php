<section class="sponsors-section" id="sponsor">
  <div class="container">
    
    <!-- Group 1: SPONSORED BY (6 baris: 2 logo paling besar, 2 logo lebih kecil, lalu 6 logo reguler per baris) -->
    <div class="sponsor-group">
      <div class="sponsor-badge">
        <?= esc($sponsors['sponsored_by']['badge']) ?>
      </div>

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
    </div>

    <!-- Group 2: PARTNERSHIP (4 logo dengan ukuran lebih kecil lagi) -->
    <div class="sponsor-group">
      <div class="sponsor-badge">
        <?= esc($sponsors['partnerships']['badge']) ?>
      </div>

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
    </div>

  </div>
</section>
