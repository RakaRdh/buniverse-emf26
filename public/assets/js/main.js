/**
 * Main JavaScript for Investor Daily Summit 2026 Microsite
 * Handles:
 * 1. Main Tabs (SCHEDULE vs SPEAKERS)
 * 2. Day Selector (Day 1 vs Day 2) with auto-hide on SPEAKERS tab
 * 3. Gallery Thumbnail Click to update Featured Image
 * 4. Responsive Mobile Navigation Toggle
 */

document.addEventListener('DOMContentLoaded', () => {
  // 1. Main Tabs: SCHEDULE vs SPEAKERS
  const mainTabBtns = document.querySelectorAll('.main-tab-btn');
  const scheduleListView = document.getElementById('scheduleListView');
  const speakersTabView = document.getElementById('speakersTabView');
  const daySelectorWrapper = document.getElementById('daySelectorWrapper');

  mainTabBtns.forEach(btn => {
    btn.addEventListener('click', () => {
      const targetTab = btn.getAttribute('data-tab');

      mainTabBtns.forEach(b => b.classList.remove('active'));
      btn.classList.add('active');

      if (targetTab === 'schedule') {
        scheduleListView.style.display = 'block';
        speakersTabView.classList.remove('active');
        // Kondisioning: Tampilkan Day selector di tab Schedule
        if (daySelectorWrapper) {
          daySelectorWrapper.classList.remove('hidden');
        }
      } else if (targetTab === 'speakers') {
        scheduleListView.style.display = 'none';
        speakersTabView.classList.add('active');
        // Kondisioning spesifik sesuai permintaan user: Day selector disembunyikan saat tab SPEAKERS aktif!
        if (daySelectorWrapper) {
          daySelectorWrapper.classList.add('hidden');
        }
      }
    });
  });

  // 2. Day Selector: Day 1 vs Day 2
  const dayBtns = document.querySelectorAll('.day-btn');
  const daySchedules = document.querySelectorAll('.day-schedule-content');

  dayBtns.forEach(btn => {
    btn.addEventListener('click', () => {
      const targetDay = btn.getAttribute('data-day');

      dayBtns.forEach(b => b.classList.remove('active'));
      btn.classList.add('active');

      daySchedules.forEach(schedule => {
        if (schedule.getAttribute('data-day') === targetDay) {
          schedule.style.display = 'block';
        } else {
          schedule.style.display = 'none';
        }
      });
    });
  });

  // 3. Accordion Gallery Interaction
  // - Desktop (>768px): Hover expands card and shows color, others stay B&W.
  // - Mobile/Tablet (<=768px): Accordion layout with smooth auto-open sequence and tap-to-expand.
  const galleryPanels = document.querySelectorAll('.gallery-panel');

  if (galleryPanels.length > 0) {
    const isMobile = () => window.innerWidth <= 768;

    const activatePanel = (panel) => {
      galleryPanels.forEach(p => p.classList.remove('active'));
      panel.classList.add('active');
    };

    galleryPanels.forEach(panel => {
      // Desktop: mouseenter activates panel
      panel.addEventListener('mouseenter', () => {
        if (!isMobile()) {
          activatePanel(panel);
        }
      });

      // Click / tap (works on both mobile and desktop)
      panel.addEventListener('click', () => {
        activatePanel(panel);
        resetAutoTimer();
      });

      panel.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' || e.key === ' ') {
          e.preventDefault();
          activatePanel(panel);
          resetAutoTimer();
        }
      });
    });

    // Auto-open sequence: every 4 seconds, expand next accordion panel automatically
    let currentAutoIndex = 0;
    let autoInterval = null;

    const startAutoSequence = () => {
      if (autoInterval) clearInterval(autoInterval);
      autoInterval = setInterval(() => {
        currentAutoIndex = (currentAutoIndex + 1) % galleryPanels.length;
        activatePanel(galleryPanels[currentAutoIndex]);
      }, 4000);
    };

    const resetAutoTimer = () => {
      if (autoInterval) {
        clearInterval(autoInterval);
        startAutoSequence();
      }
    };

    // Initialize auto sequence if section is present
    startAutoSequence();
  }

  // 4. Mobile Nav Toggle
  const navToggle = document.getElementById('navToggle');
  const siteNav = document.getElementById('siteNav');

  if (navToggle && siteNav) {
    navToggle.addEventListener('click', () => {
      siteNav.classList.toggle('open');
    });
  }

  // 5. Hero Stage Carousel (Smooth Fade Looping)
  const heroSlides = document.querySelectorAll('.hero-carousel-slide');
  if (heroSlides.length > 1) {
    let currentSlideIdx = 0;
    setInterval(() => {
      heroSlides[currentSlideIdx].classList.remove('active');
      currentSlideIdx = (currentSlideIdx + 1) % heroSlides.length;
      heroSlides[currentSlideIdx].classList.add('active');
    }, 4500);
  }

  // 6. Speaker Detail Modal Popup Handler
  const speakerModal = document.getElementById('speakerModal');
  const modalSpeakerPhoto = document.getElementById('modalSpeakerPhoto');
  const modalSpeakerName = document.getElementById('modalSpeakerName');
  const modalSpeakerRole = document.getElementById('modalSpeakerRole');
  const modalCloseBtn = document.getElementById('speakerModalClose');

  const openSpeakerModal = (card) => {
    const name = card.getAttribute('data-speaker-name');
    const role = card.getAttribute('data-speaker-role');
    const photo = card.getAttribute('data-speaker-photo');

    if (modalSpeakerName) modalSpeakerName.textContent = name || '';
    if (modalSpeakerRole) modalSpeakerRole.textContent = role || '';
    if (modalSpeakerPhoto && photo) {
      modalSpeakerPhoto.src = photo;
      modalSpeakerPhoto.alt = name || 'Speaker';
    }

    if (speakerModal) {
      speakerModal.classList.add('open');
      speakerModal.setAttribute('aria-hidden', 'false');
    }
  };

  const closeSpeakerModal = () => {
    if (speakerModal) {
      speakerModal.classList.remove('open');
      speakerModal.setAttribute('aria-hidden', 'true');
    }
  };

  // Attach listener to all speaker cards and tiles
  const speakerCards = document.querySelectorAll('.speaker-card, .speaker-tile');
  speakerCards.forEach(card => {
    card.addEventListener('click', () => openSpeakerModal(card));
    card.addEventListener('keydown', (e) => {
      if (e.key === 'Enter' || e.key === ' ') {
        e.preventDefault();
        openSpeakerModal(card);
      }
    });
  });

  if (modalCloseBtn) {
    modalCloseBtn.addEventListener('click', closeSpeakerModal);
  }

  if (speakerModal) {
    speakerModal.addEventListener('click', (e) => {
      if (e.target === speakerModal) {
        closeSpeakerModal();
      }
    });
  }

  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && speakerModal && speakerModal.classList.contains('open')) {
      closeSpeakerModal();
    }
  });

  // 7. "View More Speakers" Toggle Button
  const btnLoadMoreSpeakers = document.getElementById('btnLoadMoreSpeakers');
  if (btnLoadMoreSpeakers) {
    btnLoadMoreSpeakers.addEventListener('click', () => {
      const isExpanded = btnLoadMoreSpeakers.getAttribute('data-expanded') === 'true';
      const extraSpeakers = document.querySelectorAll('.speaker-tile-extra');

      if (!isExpanded) {
        extraSpeakers.forEach(tile => tile.classList.remove('hidden'));
        btnLoadMoreSpeakers.setAttribute('data-expanded', 'true');
        btnLoadMoreSpeakers.classList.add('expanded');
        const textSpan = btnLoadMoreSpeakers.querySelector('span');
        if (textSpan) textSpan.textContent = 'Show Less Speakers';
      } else {
        extraSpeakers.forEach(tile => tile.classList.add('hidden'));
        btnLoadMoreSpeakers.setAttribute('data-expanded', 'false');
        btnLoadMoreSpeakers.classList.remove('expanded');
        const textSpan = btnLoadMoreSpeakers.querySelector('span');
        if (textSpan) textSpan.textContent = 'View More Speakers';
        // Scroll smoothly back to top of speakers section if needed
        const speakersSection = document.getElementById('speakersTabView');
        if (speakersSection) {
          speakersSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
      }
    });
  }

  // 8. Scroll-triggered Entry Animation for Speaker Cards in Schedule
  const observedSpeakerCards = document.querySelectorAll('.speaker-card');
  if ('IntersectionObserver' in window && observedSpeakerCards.length > 0) {
    const cardObserver = new IntersectionObserver((entries, observer) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('visible');
          observer.unobserve(entry.target);
        }
      });
    }, {
      threshold: 0.15,
      rootMargin: '0px 0px -40px 0px'
    });

    observedSpeakerCards.forEach(card => cardObserver.observe(card));
  } else {
    // Fallback if IntersectionObserver is not supported
    observedSpeakerCards.forEach(card => card.classList.add('visible'));
  }

  // 9. Related News Slider with Arrows and Gesture Support
  const newsSliderContainer = document.getElementById('newsSliderContainer');
  const newsPrevBtn = document.getElementById('newsPrevBtn');
  const newsNextBtn = document.getElementById('newsNextBtn');

  if (newsSliderContainer && newsPrevBtn && newsNextBtn) {
    // Update arrow visibility based on scroll position
    const updateNewsArrowState = () => {
      const scrollLeft = newsSliderContainer.scrollLeft;
      const maxScrollLeft = newsSliderContainer.scrollWidth - newsSliderContainer.clientWidth;

      // Mentok ke kiri (toleransi 4px) -> sembunyikan panah kiri
      if (scrollLeft <= 4) {
        newsPrevBtn.classList.add('is-disabled');
        newsPrevBtn.disabled = true;
      } else {
        newsPrevBtn.classList.remove('is-disabled');
        newsPrevBtn.disabled = false;
      }

      // Mentok ke kanan (toleransi 4px) -> sembunyikan panah kanan
      if (scrollLeft >= maxScrollLeft - 4) {
        newsNextBtn.classList.add('is-disabled');
        newsNextBtn.disabled = true;
      } else {
        newsNextBtn.classList.remove('is-disabled');
        newsNextBtn.disabled = false;
      }
    };

    // Calculate scroll step (width of one card + gap)
    const getScrollStep = () => {
      const firstCard = newsSliderContainer.querySelector('.news-card');
      if (firstCard) {
        return firstCard.offsetWidth + 28; // width + gap
      }
      return 360;
    };

    // Arrow button click listeners
    newsPrevBtn.addEventListener('click', () => {
      newsSliderContainer.scrollBy({
        left: -getScrollStep(),
        behavior: 'smooth'
      });
    });

    newsNextBtn.addEventListener('click', () => {
      newsSliderContainer.scrollBy({
        left: getScrollStep(),
        behavior: 'smooth'
      });
    });

    // Native scroll event listener (throttled via requestAnimationFrame)
    let isTicking = false;
    newsSliderContainer.addEventListener('scroll', () => {
      if (!isTicking) {
        window.requestAnimationFrame(() => {
          updateNewsArrowState();
          isTicking = false;
        });
        isTicking = true;
      }
    });

    // Window resize adjustment
    window.addEventListener('resize', updateNewsArrowState);

    // Initial check on load
    updateNewsArrowState();

    // Mouse Drag to Scroll Gesture (Desktop swipe experience)
    let isMouseDown = false;
    let startX = 0;
    let initialScrollLeft = 0;
    let hasDragged = false;

    newsSliderContainer.addEventListener('mousedown', (e) => {
      isMouseDown = true;
      hasDragged = false;
      startX = e.pageX - newsSliderContainer.offsetLeft;
      initialScrollLeft = newsSliderContainer.scrollLeft;
      newsSliderContainer.style.cursor = 'grabbing';
      newsSliderContainer.style.userSelect = 'none';
    });

    window.addEventListener('mousemove', (e) => {
      if (!isMouseDown) return;
      e.preventDefault();
      const x = e.pageX - newsSliderContainer.offsetLeft;
      const walk = (x - startX) * 1.5; // multiplier for responsive drag
      if (Math.abs(walk) > 5) {
        hasDragged = true;
      }
      newsSliderContainer.scrollLeft = initialScrollLeft - walk;
    });

    window.addEventListener('mouseup', () => {
      if (!isMouseDown) return;
      isMouseDown = false;
      newsSliderContainer.style.cursor = 'default';
      newsSliderContainer.style.removeProperty('user-select');
    });

    // Prevent unintentional card link clicks when dragging
    const newsLinks = newsSliderContainer.querySelectorAll('.news-card-link');
    newsLinks.forEach(link => {
      link.addEventListener('click', (e) => {
        if (hasDragged) {
          e.preventDefault();
          hasDragged = false;
        }
      });
    });
  }

  // =========================================================================
  // 6. Automatic Section Decorations Zigzag Layout Engine
  // =========================================================================
  const autoDecorationGroups = document.querySelectorAll('.decoration-auto-group');

  if (autoDecorationGroups.length > 0) {
    const layoutAutoGroup = (group) => {
      const section = group.closest('section');
      if (!section) return;

      const sectionHeight = section.offsetHeight;
      if (sectionHeight <= 0) return;

      const count = Math.max(1, parseInt(group.getAttribute('data-count'), 10) || 1);
      const gapRaw = parseFloat(group.getAttribute('data-gap')) || 100;
      const gapUnit = group.getAttribute('data-gap-unit') || 'px';
      const startSide = group.getAttribute('data-start-side') || 'left';
      const edgeOffset = group.getAttribute('data-edge-offset') || '50%';
      const width = parseInt(group.getAttribute('data-width'), 10) || 200;
      const opacity = group.getAttribute('data-opacity') || '1.0';
      const imgSrc = group.getAttribute('data-img') || '';
      const isRotated = group.getAttribute('data-rotated') === '1';
      const rotDir = group.getAttribute('data-rot-dir') || 'clockwise';
      const rotClass = isRotated ? `is-rotating rot-${rotDir}` : '';

      // Convert gap to px based on unit
      let gap = gapRaw;
      if (gapUnit === 'vh') {
        gap = (gapRaw / 100) * window.innerHeight;
      } else if (gapUnit === 'rem') {
        const rootFs = parseFloat(getComputedStyle(document.documentElement).fontSize) || 16;
        gap = gapRaw * rootFs;
      } else if (gapUnit === 'percent') {
        gap = (gapRaw / 100) * sectionHeight;
      }
      gap = Math.max(20, gap);

      // Clear previous generated items
      group.innerHTML = '';

      const startLeft = (startSide === 'left');

      if (count === 1) {
        const item = document.createElement('div');
        const edge = startLeft ? 'edge-left' : 'edge-right';
        item.className = `decoration-auto-item ${edge} ${rotClass}`;
        item.style.top = '50%';
        item.style.setProperty('--dec-width', `${width}px`);
        item.style.setProperty('--dec-opacity', opacity);
        item.style.setProperty('--dec-edge-offset', edgeOffset);
        item.innerHTML = `<img src="${imgSrc}" alt="" loading="lazy">`;
        group.appendChild(item);
        return;
      }

      // If count > 1, adjust gap if total height exceeds section
      let totalSpan = (count - 1) * gap;
      const maxAvailableSpan = Math.max(80, sectionHeight - width);

      if (totalSpan > maxAvailableSpan) {
        gap = maxAvailableSpan / (count - 1);
        totalSpan = maxAvailableSpan;
      }

      const startY = Math.max(width / 2, (sectionHeight - totalSpan) / 2);

      for (let i = 0; i < count; i++) {
        const topPx = startY + i * gap;
        const edge = ((i % 2 === 0) === startLeft) ? 'edge-left' : 'edge-right';

        const item = document.createElement('div');
        item.className = `decoration-auto-item ${edge} ${rotClass}`;
        item.style.top = `${Math.round(topPx)}px`;
        item.style.setProperty('--dec-width', `${width}px`);
        item.style.setProperty('--dec-opacity', opacity);
        item.style.setProperty('--dec-edge-offset', edgeOffset);
        item.innerHTML = `<img src="${imgSrc}" alt="" loading="lazy">`;
        group.appendChild(item);
      }
    };

    autoDecorationGroups.forEach(group => {
      layoutAutoGroup(group);

      const section = group.closest('section');
      if (section && window.ResizeObserver) {
        const observer = new ResizeObserver(() => {
          layoutAutoGroup(group);
        });
        observer.observe(section);
      }
    });

    // Also recompute on window resize
    window.addEventListener('resize', () => {
      autoDecorationGroups.forEach(layoutAutoGroup);
    });
  }

  // 12. Schedule Row Accordion (Gambar 5: Maksimal 1 Card Aktif)
  const scheduleRows = document.querySelectorAll('.schedule-row');
  scheduleRows.forEach(row => {
    // Make sure rows with speakers can be toggled
    const speakerGrid = row.querySelector('.plenary-speaker-grid');
    if (!speakerGrid) return;

    row.addEventListener('click', (e) => {
      // Don't toggle if clicking directly on a speaker card to open modal
      if (e.target.closest('.speaker-card')) return;

      const isMobile = window.innerWidth <= 768;
      if (!isMobile) return;

      const isActive = row.classList.contains('active');

      // Close ALL open schedule rows first (Ensures max 1 card is open)
      scheduleRows.forEach(r => r.classList.remove('active'));

      // If it wasn't active before, open this one
      if (!isActive) {
        row.classList.add('active');
      }
    });
  });

  // 13. Highlights Section: Fetch 3 Fixed YouTube Videos via YouTube Data API v3
  const initHighlightsSection = async () => {
    const highlightsGrid = document.getElementById('highlightsGrid');
    if (!highlightsGrid) return;

    const API_KEY = 'AIzaSyAZjlB9xf0R-SjsRrOqnLHjuRJ0sbU3sds';
    const HASHTAG = '#investordailysummit';
    const CACHE_KEY = 'ids_highlights_yt_videos_v1';
    const CACHE_TTL_MS = 60 * 60 * 1000; // 1 Jam Cache

    // Fallback static videos in case API quota is reached or network is offline
    const fallbackVideos = [
      {
        videoId: 'BCq3ycyxkf4',
        title: 'Jakarta Tawarkan 37 proyek senilai US$7 M di U20 Mayors Summit 2026',
        thumbnail: 'https://i.ytimg.com/vi/BCq3ycyxkf4/hqdefault.jpg',
        videoUrl: 'https://www.youtube.com/watch?v=BCq3ycyxkf4'
      },
      {
        videoId: 'svEJI3ph_VI',
        title: 'Kiprah Indonesia Dalam Peta Ekonomi Dunia | Investor Daily Summit',
        thumbnail: 'https://i.ytimg.com/vi/svEJI3ph_VI/hqdefault.jpg',
        videoUrl: 'https://www.youtube.com/watch?v=svEJI3ph_VI'
      },
      {
        videoId: 'dtIBlTHtotk',
        title: 'Special Remarks & Solek Negeri Pikat Investasi | Investor Daily Summit',
        thumbnail: 'https://i.ytimg.com/vi/dtIBlTHtotk/hqdefault.jpg',
        videoUrl: 'https://www.youtube.com/watch?v=dtIBlTHtotk'
      }
    ];

    const renderVideos = (videos) => {
      // Ensure exactly 3 videos are displayed
      const itemsToRender = (videos && videos.length >= 3) ? videos.slice(0, 3) : fallbackVideos.slice(0, 3);
      highlightsGrid.innerHTML = '';

      itemsToRender.forEach(v => {
        const card = document.createElement('article');
        card.className = 'highlights-card';
        card.innerHTML = `
          <a href="${v.videoUrl}" target="_blank" rel="noopener noreferrer" class="highlights-card-inner" title="${escapeHtml(v.title)}">
            <div class="highlights-thumb-wrap">
              <div class="highlights-thumb-bg" style="background-image: url('${v.thumbnail}');"></div>
              <div class="highlights-play-badge" aria-hidden="true">
                <svg viewBox="0 0 68 48" width="52" height="36" class="yt-play-svg">
                  <path class="yt-play-bg" d="M66.52,7.74c-0.78-2.93-2.49-5.41-5.42-6.19C55.79,.13,34,0,34,0S12.21,.13,6.9,1.55 C3.97,2.33,2.27,4.81,1.48,7.74C0.06,13.05,0,24,0,24s0.06,10.95,1.48,16.26c0.78,2.93,2.49,5.41,5.42,6.19 C12.21,47.87,34,48,34,48s21.79-0.13,27.1-1.55c2.93-0.78,4.64-3.26,5.42-6.19C67.94,34.95,68,24,68,24S67.94,13.05,66.52,7.74z" fill="#FF0000"></path>
                  <path d="M 45,24 27,14 27,34" fill="#FFFFFF"></path>
                </svg>
              </div>
              <div class="highlights-title-embedded">
                <span class="highlights-title-text">${escapeHtml(v.title)}</span>
              </div>
            </div>
          </a>
        `;
        highlightsGrid.appendChild(card);
      });
    };

    const escapeHtml = (str) => {
      if (!str) return '';
      return str
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
    };

    // Check localStorage cache
    try {
      const cachedDataStr = localStorage.getItem(CACHE_KEY);
      if (cachedDataStr) {
        const cachedObj = JSON.parse(cachedDataStr);
        if (cachedObj && cachedObj.timestamp && (Date.now() - cachedObj.timestamp < CACHE_TTL_MS)) {
          if (Array.isArray(cachedObj.videos) && cachedObj.videos.length >= 3) {
            renderVideos(cachedObj.videos);
            return;
          }
        }
      }
    } catch (e) {
      // LocalStorage access restricted or invalid, proceed to fetch
    }

    // Fetch from YouTube Data API v3
    try {
      const encodedQuery = encodeURIComponent(HASHTAG);
      const url = `https://www.googleapis.com/youtube/v3/search?part=snippet&q=${encodedQuery}&type=video&maxResults=6&key=${API_KEY}`;

      const response = await fetch(url);
      if (!response.ok) {
        throw new Error(`YouTube API returned status ${response.status}`);
      }
      const data = await response.json();

      if (data && Array.isArray(data.items) && data.items.length > 0) {
        const videos = data.items
          .filter(item => item.id && item.id.videoId)
          .map(item => ({
            videoId: item.id.videoId,
            title: item.snippet.title,
            channelTitle: item.snippet.channelTitle,
            publishedAt: item.snippet.publishedAt,
            thumbnail: (item.snippet.thumbnails.high && item.snippet.thumbnails.high.url) || 
                       (item.snippet.thumbnails.medium && item.snippet.thumbnails.medium.url) || 
                       (item.snippet.thumbnails.default && item.snippet.thumbnails.default.url),
            videoUrl: `https://www.youtube.com/watch?v=${item.id.videoId}`
          }));

        if (videos.length >= 3) {
          try {
            localStorage.setItem(CACHE_KEY, JSON.stringify({
              timestamp: Date.now(),
              videos: videos
            }));
          } catch (e) {}

          renderVideos(videos);
          return;
        }
      }

      // If items count < 3, use fallback
      renderVideos(fallbackVideos);
    } catch (error) {
      console.warn('YouTube Highlights API fetch error, rendering fallback videos:', error);
      renderVideos(fallbackVideos);
    }
  };

  initHighlightsSection();

  // 10. Ticket Click Telemetry Tracking
  const trackTicketClick = () => {
    try {
      if (navigator.sendBeacon) {
        navigator.sendBeacon('/api/track-ticket');
      } else {
        fetch('/api/track-ticket', {
          method: 'POST',
          keepalive: true,
          headers: { 'Content-Type': 'application/json' }
        }).catch(() => {});
      }
    } catch (err) {
      console.warn('Telemetry error:', err);
    }
  };

  document.querySelectorAll('.btn-ticket, [data-track-ticket]').forEach(btn => {
    btn.addEventListener('click', () => {
      trackTicketClick();
    });
  });
});




