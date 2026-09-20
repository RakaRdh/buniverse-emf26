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

  // 3. Accordion Gallery Interaction (§User Request & Screenshot)
  // - Desktop: Hover expands card and shows color, others stay B&W.
  // - When hover leaves the section, the last hovered card remains expanded and colored!
  // - Mobile: tap/click triggers expansion
  const galleryPanels = document.querySelectorAll('.gallery-panel');
  if (galleryPanels.length > 0) {
    const activatePanel = (panel) => {
      galleryPanels.forEach(p => p.classList.remove('active'));
      panel.classList.add('active');
    };

    galleryPanels.forEach(panel => {
      // Desktop: mouseenter activates panel
      panel.addEventListener('mouseenter', () => {
        activatePanel(panel);
      });

      // Mobile & accessibility: click/tap activates panel
      panel.addEventListener('click', () => {
        activatePanel(panel);
      });

      panel.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' || e.key === ' ') {
          e.preventDefault();
          activatePanel(panel);
        }
      });
    });
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
});



