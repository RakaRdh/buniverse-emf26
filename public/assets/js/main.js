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

    // If on mobile on page load, override default open panel to the first one (index 0)
    if (isMobile()) {
      activatePanel(galleryPanels[0]);
    }

    // Auto-open sequence: ONLY for mobile (<=768px), every 4 seconds, expand next accordion panel automatically
    // On desktop (>768px), behavior remains strictly hover-based per user request!
    let currentAutoIndex = 0;
    let autoInterval = null;

    const startAutoSequence = () => {
      if (!isMobile()) return; // strictly only for mobile
      if (autoInterval) clearInterval(autoInterval);
      autoInterval = setInterval(() => {
        if (!isMobile()) {
          clearInterval(autoInterval);
          return;
        }
        currentAutoIndex = (currentAutoIndex + 1) % galleryPanels.length;
        activatePanel(galleryPanels[currentAutoIndex]);
      }, 4000);
    };

    const resetAutoTimer = () => {
      if (isMobile()) {
        if (autoInterval) clearInterval(autoInterval);
        startAutoSequence();
      }
    };

    // Initialize auto sequence only if on mobile
    if (isMobile()) {
      startAutoSequence();
    }

    window.addEventListener('resize', () => {
      if (isMobile()) {
        if (!autoInterval) startAutoSequence();
      } else {
        if (autoInterval) {
          clearInterval(autoInterval);
          autoInterval = null;
        }
      }
    });
  }

  // 4. Mobile Nav Toggle
  const navToggle = document.getElementById('navToggle');
  const siteNav = document.getElementById('siteNav');

  if (navToggle && siteNav) {
    navToggle.addEventListener('click', () => {
      siteNav.classList.toggle('open');
      navToggle.classList.toggle('open');
    });
  }

  // 4.1 Navbar Hide-on-Scroll-Down, Show-on-Scroll-Up
  const siteHeader = document.getElementById('header') || document.querySelector('.site-header');
  if (siteHeader) {
    let lastScrollY = window.pageYOffset || document.documentElement.scrollTop || 0;
    let headerTicking = false;

    const onScroll = () => {
      const currentScrollY = window.pageYOffset || document.documentElement.scrollTop || 0;
      
      // Don't hide if mobile menu is open
      const isMobileMenuOpen = siteNav && siteNav.classList.contains('open');

      if (!isMobileMenuOpen) {
        const delta = currentScrollY - lastScrollY;
        if (delta > 8 && currentScrollY > 70) {
          // Scrolling down past header height -> hide navbar
          siteHeader.classList.add('header-hidden');
        } else if (delta < -5 || currentScrollY <= 20) {
          // Scrolling up or near top -> show navbar immediately
          siteHeader.classList.remove('header-hidden');
        }
      }

      lastScrollY = Math.max(0, currentScrollY);
      headerTicking = false;
    };

    window.addEventListener('scroll', () => {
      if (!headerTicking) {
        window.requestAnimationFrame(onScroll);
        headerTicking = true;
      }
    }, { passive: true });
  }

  // 4.2 Floating Back to Top Button & Floating Bottom Bar Appearance
  const backToTopBtn = document.getElementById('backToTopBtn');
  const floatingBottomBar = document.getElementById('floatingBottomBar');
  const overviewSection = document.getElementById('about-us') || document.querySelector('.editorial-section');
  const sponsorSection = document.getElementById('sponsor') || document.querySelector('.sponsors-section');
  const footerEl = document.querySelector('.site-footer');

  const handleFloatingElements = () => {
    const scrollPos = window.pageYOffset || document.documentElement.scrollTop || 0;
    const windowHeight = window.innerHeight;

    // Cek apakah viewport sudah melewati akhir section sponsor / menyentuh footer
    let isPastSponsor = false;
    if (footerEl) {
      const footerRect = footerEl.getBoundingClientRect();
      if (footerRect.top <= windowHeight - 20) {
        isPastSponsor = true;
      }
    } else if (sponsorSection) {
      const sponsorRect = sponsorSection.getBoundingClientRect();
      if (sponsorRect.bottom <= 60) {
        isPastSponsor = true;
      }
    }

    // Back to top appears after scrolling down 350px, but disappears once reaching footer
    if (backToTopBtn) {
      if (scrollPos > 350 && !isPastSponsor) {
        backToTopBtn.classList.add('is-visible');
      } else {
        backToTopBtn.classList.remove('is-visible');
      }
    }

    // Floating bottom bar appears when reaching overview section (#about-us) and continues downwards,
    // but hides at the end of sponsor section so footer stays clean at the bottom
    if (floatingBottomBar) {
      if (isPastSponsor) {
        floatingBottomBar.classList.remove('is-visible');
      } else if (overviewSection) {
        const overviewTop = overviewSection.getBoundingClientRect().top + scrollPos;
        if (scrollPos >= overviewTop - 300) {
          floatingBottomBar.classList.add('is-visible');
        } else {
          floatingBottomBar.classList.remove('is-visible');
        }
      } else if (scrollPos > 450) {
        floatingBottomBar.classList.add('is-visible');
      } else {
        floatingBottomBar.classList.remove('is-visible');
      }
    }
  };

  window.addEventListener('scroll', handleFloatingElements, { passive: true });
  handleFloatingElements();

  if (backToTopBtn) {
    backToTopBtn.addEventListener('click', () => {
      window.scrollTo({
        top: 0,
        behavior: 'smooth'
      });
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

  // 9. Related News Multi-Instance Slider with Arrows and Gesture Support
  const newsSliderWrappers = document.querySelectorAll('.news-slider-wrapper');

  newsSliderWrappers.forEach(wrapper => {
    const newsSliderContainer = wrapper.querySelector('.news-slider-container');
    const newsPrevBtn = wrapper.querySelector('.news-arrow-prev');
    const newsNextBtn = wrapper.querySelector('.news-arrow-next');

    if (!newsSliderContainer || !newsPrevBtn || !newsNextBtn) return;

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
    }, { passive: true });

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
  });

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

  // Highlights section commented out per user request; disabled init call
  // initHighlightsSection();

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

  // 11. Typewriter Looping Effect for Floating Bottom Bar
  const typingEl = document.getElementById('floatingTypingText');
  if (typingEl) {
    const textToType = 'Get Your Seat Now!';
    let charIndex = 0;
    let isDeleting = false;
    const typingSpeed = 110;
    const deletingSpeed = 65;
    const pauseBeforeDelete = 2000;
    const pauseBeforeRestart = 600;

    const typeLoop = () => {
      if (!isDeleting) {
        // Typing forward
        charIndex++;
        typingEl.textContent = textToType.slice(0, charIndex);

        if (charIndex === textToType.length) {
          // Finished typing string, pause before deleting
          isDeleting = true;
          setTimeout(typeLoop, pauseBeforeDelete);
          return;
        }
        setTimeout(typeLoop, typingSpeed);
      } else {
        // Deleting characters
        charIndex--;
        typingEl.textContent = textToType.slice(0, charIndex);

        if (charIndex === 0) {
          // Finished deleting, pause before typing again
          isDeleting = false;
          setTimeout(typeLoop, pauseBeforeRestart);
          return;
        }
        setTimeout(typeLoop, deletingSpeed);
      }
    };

    typeLoop();
  }

  // 12. TUNING PANEL - hapus setelah final (Hero Revision Brief §D)
  if (window.location.search.includes('tune=1')) {
    const heroSection = document.querySelector('.hero-section');
    if (heroSection) {
      const panel = document.createElement('div');
      panel.id = 'heroTuningPanel';
      panel.style.cssText = `
        position: fixed;
        bottom: 100px;
        right: 20px;
        background: rgba(1, 10, 45, 0.95);
        color: #ffffff;
        padding: 16px;
        border-radius: 12px;
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 10px 30px rgba(0,0,0,0.8);
        z-index: 10000;
        font-family: monospace;
        font-size: 12px;
        width: 280px;
        max-height: 80vh;
        overflow-y: auto;
      `;

      const controls = [
        { id: 'glow-angle', label: 'Glow Angle (deg)', var: '--glow-angle', min: 140, max: 200, step: 1, unit: 'deg', def: 160 },
        { id: 'glow-len', label: 'Glow Len (vh)', var: '--glow-len', min: 25, max: 60, step: 1, unit: 'vh', def: 38 },
        { id: 'deco-size', label: 'Disc Size (vh)', var: '--deco-size', min: 100, max: 220, step: 2, unit: 'vh', def: 150 },
        { id: 'deco-cx', label: 'Disc Center X (%)', var: '--deco-cx', min: 50, max: 80, step: 1, unit: '%', def: 68 },
        { id: 'deco-cy', label: 'Disc Center Y (%)', var: '--deco-cy', min: 35, max: 65, step: 1, unit: '%', def: 50 },
        { id: 'tilt-z', label: 'Tilt Z (deg)', var: '--tilt-z', min: -10, max: 30, step: 1, unit: 'deg', def: 10 },
        { id: 'tilt-x', label: 'Tilt X (deg)', var: '--tilt-x', min: 0, max: 25, step: 1, unit: 'deg', def: 10 },
        { id: 'tilt-y', label: 'Tilt Y (deg)', var: '--tilt-y', min: -60, max: -30, step: 1, unit: 'deg', def: -48 },
        { id: 'persp', label: 'Perspective (vh)', var: '--persp', min: 70, max: 180, step: 5, unit: 'vh', def: 110 },
        { id: 'mask-rw', label: 'Mask Radial W (vw)', var: '--mask-rw', min: 15, max: 40, step: 1, unit: 'vw', def: 25 },
        { id: 'mask-rh', label: 'Mask Radial H (vh)', var: '--mask-rh', min: 25, max: 55, step: 1, unit: 'vh', def: 40 },
      ];

      panel.innerHTML = `<div style="font-weight:bold;margin-bottom:10px;display:flex;justify-content:space-between;align-items:center;">
        <span>HERO TUNING (?tune=1)</span>
        <button id="tuneCloseBtn" style="background:#ef4444;color:#fff;border:none;border-radius:4px;padding:2px 6px;cursor:pointer;">X</button>
      </div>`;

      controls.forEach(ctrl => {
        const wrap = document.createElement('div');
        wrap.style.cssText = 'margin-bottom:8px;';
        wrap.innerHTML = `
          <div style="display:flex;justify-content:space-between;">
            <label>${ctrl.label}</label>
            <span id="val_${ctrl.id}">${ctrl.def}${ctrl.unit}</span>
          </div>
          <input type="range" id="input_${ctrl.id}" min="${ctrl.min}" max="${ctrl.max}" step="${ctrl.step}" value="${ctrl.def}" style="width:100%;cursor:pointer;">
        `;
        panel.appendChild(wrap);
      });

      const copyBtn = document.createElement('button');
      copyBtn.innerText = 'Copy CSS Variables';
      copyBtn.style.cssText = 'width:100%;background:#38bdf8;color:#010a2d;font-weight:bold;border:none;border-radius:6px;padding:8px;margin-top:10px;cursor:pointer;';
      panel.appendChild(copyBtn);

      document.body.appendChild(panel);

      controls.forEach(ctrl => {
        const input = document.getElementById(`input_${ctrl.id}`);
        const valSpan = document.getElementById(`val_${ctrl.id}`);
        input.addEventListener('input', (e) => {
          const val = `${e.target.value}${ctrl.unit}`;
          valSpan.textContent = val;
          heroSection.style.setProperty(ctrl.var, val);
        });
      });

      copyBtn.addEventListener('click', () => {
        const lines = controls.map(ctrl => {
          const val = document.getElementById(`val_${ctrl.id}`).textContent;
          return `  ${ctrl.var}: ${val};`;
        }).join('\n');
        navigator.clipboard.writeText(lines).then(() => {
          copyBtn.innerText = 'Copied to Clipboard!';
          setTimeout(() => { copyBtn.innerText = 'Copy CSS Variables'; }, 2000);
        });
      });

      document.getElementById('tuneCloseBtn').addEventListener('click', () => {
        panel.remove();
      });
    }
  }

  // --------------------------------------------------------------------------
  // Auto Layout Section Decorations Generator
  // --------------------------------------------------------------------------
  const autoGroups = document.querySelectorAll('.decoration-auto-group');
  autoGroups.forEach(group => {
    const count = parseInt(group.getAttribute('data-count') || '1', 10);
    const gap = parseFloat(group.getAttribute('data-gap') || '100');
    const gapUnit = group.getAttribute('data-gap-unit') || 'px';
    const startSide = group.getAttribute('data-start-side') || 'left';
    const edgeOffset = group.getAttribute('data-edge-offset') || '50%';
    const width = parseInt(group.getAttribute('data-width') || '200', 10);
    const opacity = group.getAttribute('data-opacity') || '1';
    const imgUrl = group.getAttribute('data-img') || '';
    const tintColor = group.getAttribute('data-tint') || '';
    const isRotated = group.getAttribute('data-rotated') === '1';
    const rotDir = group.getAttribute('data-rot-dir') || 'clockwise';
    const rotClass = isRotated ? ('is-rotating rot-' + rotDir) : '';

    const parent = group.parentElement;
    const parentHeight = parent ? parent.offsetHeight : 600;

    for (let i = 0; i < count; i++) {
      const item = document.createElement('div');
      // Alternate sides starting from startSide
      const side = (i % 2 === 0) ? startSide : (startSide === 'left' ? 'right' : 'left');
      item.className = `decoration-auto-item edge-${side} ${rotClass}`;
      item.style.setProperty('--dec-width', width + 'px');
      item.style.setProperty('--dec-opacity', opacity);
      item.style.setProperty('--dec-edge-offset', edgeOffset);

      // Vertical position calculation
      if (count === 1) {
        item.style.top = '50%';
      } else if (gapUnit === '%') {
        const topPercent = Math.min(95, Math.max(5, (gap * i) % 100));
        item.style.top = topPercent + '%';
      } else {
        // px gap
        const step = (gap && gap > 0) ? gap : (parentHeight / (count + 1));
        const topPx = (step * (i + 1));
        item.style.top = topPx + 'px';
      }

      if (tintColor) {
        const maskDiv = document.createElement('div');
        maskDiv.className = 'decoration-tint-mask';
        maskDiv.style.setProperty('--tint-color', tintColor);
        maskDiv.style.setProperty('--mask-url', `url('${imgUrl}')`);
        maskDiv.style.width = '100%';
        maskDiv.style.height = '100%';
        item.appendChild(maskDiv);
      } else if (imgUrl) {
        const img = document.createElement('img');
        img.src = imgUrl;
        img.alt = '';
        img.loading = 'lazy';
        item.appendChild(img);
      }

      group.appendChild(item);
    }
  });
});




