<section class="news-section summary-section" id="summary">
  <?= view('partials/section_decorations', ['sectionKey' => 'summary', 'decorations' => $sectionDecorations ?? []]) ?>
  <?= view('partials/custom_placements', ['sectionKey' => 'summary', 'customPlacements' => $customPlacements ?? []]) ?>
  <div class="container">
    
    <!-- Section Header Centered -->
    <div class="section-header-center">
      <h2 class="section-title">Overview</h2>
    </div>

    <?php
      $overviewPortal = $newsPortals['overview'] ?? null;
      $overviewUrl = $overviewPortal['url'] ?? 'https://datasatu.com/tag/investor-daily-summit';
      $items = $summaryNews ?? [];
      $totalItems = count($items);
      $itemsPerPage = 8;
      $totalPages = ceil($totalItems / $itemsPerPage);
    ?>

    <div class="news-source-block" style="margin-bottom: 0;">
      <!-- Header block: tanpa h3, tombol Selengkapnya rata kanan -->
      <div class="news-source-header" style="justify-content: flex-end; margin-bottom: 20px;">
        <a href="<?= esc($overviewUrl) ?>" target="_blank" rel="noopener noreferrer" class="news-source-more-link">
          Selengkapnya &rarr;
        </a>
      </div>

      <?php if (!empty($items)): ?>
        <div class="summary-grid-container" id="summaryGridContainer">
          <?php for ($page = 1; $page <= $totalPages; $page++): 
            $offset = ($page - 1) * $itemsPerPage;
            $pageItems = array_slice($items, $offset, $itemsPerPage);
            $pageCount = count($pageItems);
            // Cek jika jumlah item di halaman kurang dari 4 untuk align center
            $colClass = ($pageCount < 4) ? ('is-col-' . $pageCount) : '';
          ?>
            <div class="summary-page-view <?= $page === 1 ? 'is-active' : '' ?>" data-summary-page="<?= $page ?>" style="<?= $page > 1 ? 'display: none;' : '' ?>">
              <div class="summary-grid-track <?= esc($colClass) ?>">
                <?php 
                  foreach ($pageItems as $news): 
                    $isExternalImage = preg_match('#^https?://#i', $news['image'] ?? '');
                    $imgSrc = $isExternalImage ? $news['image'] : base_url('assets/img/' . $news['image']);
                    $isExternalUrl = preg_match('#^https?://#i', $news['url'] ?? '');
                ?>
                  <article class="news-card">
                    <a href="<?= esc($news['url']) ?>" class="news-card-link" <?= $isExternalUrl ? 'target="_blank" rel="noopener noreferrer"' : '' ?>>
                      <div class="news-image-wrap">
                        <img src="<?= esc($imgSrc) ?>" 
                             alt="<?= esc($news['title']) ?>" 
                             loading="lazy">
                      </div>
                      <h3 class="news-title"><?= esc($news['title']) ?></h3>
                    </a>
                  </article>
                <?php endforeach; ?>
              </div>
            </div>
          <?php endfor; ?>
        </div>

        <?php if ($totalPages > 1): ?>
          <!-- Pagination Controls -->
          <div class="summary-pagination" id="summaryPagination">
            <button type="button" class="summary-page-btn summary-btn-prev" onclick="changeSummaryPage(-1)" disabled aria-label="Previous Page" title="Halaman Sebelumnya">&lsaquo;</button>
            <?php for ($p = 1; $p <= $totalPages; $p++): ?>
              <button type="button" class="summary-page-btn summary-btn-num <?= $p === 1 ? 'active' : '' ?>" data-page="<?= $p ?>" onclick="goToSummaryPage(<?= $p ?>)">
                <?= $p ?>
              </button>
            <?php endfor; ?>
            <button type="button" class="summary-page-btn summary-btn-next" onclick="changeSummaryPage(1)" aria-label="Next Page" title="Halaman Selanjutnya">&rsaquo;</button>
          </div>

          <script>
            (function() {
              let currentSummaryPage = 1;
              const maxSummaryPages = <?= (int)$totalPages ?>;

              function updatePaginationVisibility(page) {
                const isMobile = window.innerWidth <= 640;
                const buttons = document.querySelectorAll('.summary-btn-num');
                
                if (!isMobile) {
                  // Desktop: tampilkan semua nomor
                  buttons.forEach(btn => btn.style.display = 'inline-flex');
                  return;
                }

                // Mobile: max 3 tombol nomor halaman yang aktif di sekitar current page
                let start = page - 1;
                let end = page + 1;

                if (page === 1) {
                  start = 1;
                  end = Math.min(3, maxSummaryPages);
                } else if (page === maxSummaryPages) {
                  start = Math.max(1, maxSummaryPages - 2);
                  end = maxSummaryPages;
                }

                buttons.forEach(btn => {
                  const pNum = parseInt(btn.getAttribute('data-page'));
                  if (pNum >= start && pNum <= end) {
                    btn.style.display = 'inline-flex';
                  } else {
                    btn.style.display = 'none';
                  }
                });
              }

              window.goToSummaryPage = function(page) {
                if (page < 1 || page > maxSummaryPages) return;
                currentSummaryPage = page;
                
                // Show/hide page view
                document.querySelectorAll('.summary-page-view').forEach(function(el) {
                  if (parseInt(el.getAttribute('data-summary-page')) === page) {
                    el.style.display = 'block';
                    el.classList.add('is-active');
                  } else {
                    el.style.display = 'none';
                    el.classList.remove('is-active');
                  }
                });

                // Update pagination buttons active class
                document.querySelectorAll('.summary-btn-num').forEach(function(btn) {
                  const pNum = parseInt(btn.getAttribute('data-page'));
                  if (pNum === page) {
                    btn.classList.add('active');
                  } else {
                    btn.classList.remove('active');
                  }
                });

                const prevBtn = document.querySelector('.summary-btn-prev');
                const nextBtn = document.querySelector('.summary-btn-next');
                if (prevBtn) prevBtn.disabled = (page === 1);
                if (nextBtn) nextBtn.disabled = (page === maxSummaryPages);

                updatePaginationVisibility(page);
              };

              window.changeSummaryPage = function(delta) {
                goToSummaryPage(currentSummaryPage + delta);
              };

              // Inisialisasi tampilan awal
              updatePaginationVisibility(1);
              window.addEventListener('resize', function() {
                updatePaginationVisibility(currentSummaryPage);
              });
            })();
          </script>
        <?php endif; ?>

      <?php else: ?>
        <div class="news-empty-state">
          <p class="news-empty-text">Data belum tersedia</p>
        </div>
      <?php endif; ?>
    </div>

  </div>
</section>
