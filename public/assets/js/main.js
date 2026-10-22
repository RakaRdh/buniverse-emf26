/**
 * Main JavaScript for Investor Daily Summit 2026 Microsite
 * Handles:
 * 1. Main Tabs (SCHEDULE vs SPEAKERS)
 * 2. Day Selector (Day 1 vs Day 2) with auto-hide on SPEAKERS tab
 * 3. Gallery Thumbnail Click to update Featured Image
 * 4. Responsive Mobile Navigation Toggle
 */

document.addEventListener('DOMContentLoaded', () => {
  // 1. Main Tabs: SCHEDULE vs SPEAKERS (Persisted across page refresh via sessionStorage & hash)
  const mainTabBtns = document.querySelectorAll('.main-tab-btn');
  const scheduleListView = document.getElementById('scheduleListView');
  const speakersTabView = document.getElementById('speakersTabView');
  const daySelectorWrapper = document.getElementById('daySelectorWrapper');

  const switchMainTab = (targetTab, updateHistory = true) => {
    const activeBtn = document.querySelector(`.main-tab-btn[data-tab="${targetTab}"]`);
    if (!activeBtn) return;

    mainTabBtns.forEach(b => {
      b.classList.remove('active');
      b.setAttribute('aria-selected', 'false');
    });
    activeBtn.classList.add('active');
    activeBtn.setAttribute('aria-selected', 'true');

    if (targetTab === 'schedule') {
      if (scheduleListView) scheduleListView.style.display = 'block';
      if (speakersTabView) speakersTabView.classList.remove('active');
      // Tampilkan Day selector di tab Schedule
      if (daySelectorWrapper) {
        daySelectorWrapper.classList.remove('hidden');
      }
      const dayHeadingWrap = document.getElementById('scheduleDayTitleWrap');
      if (dayHeadingWrap) {
        dayHeadingWrap.style.display = 'block';
      }
    } else if (targetTab === 'speakers') {
      if (scheduleListView) scheduleListView.style.display = 'none';
      if (speakersTabView) speakersTabView.classList.add('active');
      // Sembunyikan Day selector saat tab SPEAKERS aktif
      if (daySelectorWrapper) {
        daySelectorWrapper.classList.add('hidden');
      }
      const dayHeadingWrap = document.getElementById('scheduleDayTitleWrap');
      if (dayHeadingWrap) {
        dayHeadingWrap.style.display = 'none';
      }
    }

    try {
      sessionStorage.setItem('active_ids_tab', targetTab);
    } catch (e) {}

    if (updateHistory) {
      if (targetTab === 'speakers') {
        history.replaceState(null, null, '#speakers');
      } else if (targetTab === 'schedule' && window.location.hash === '#speakers') {
        history.replaceState(null, null, '#schedule');
      }
    }
  };

  mainTabBtns.forEach(btn => {
    btn.addEventListener('click', () => {
      const targetTab = btn.getAttribute('data-tab');
      switchMainTab(targetTab, true);
    });
  });

  // Restore Main Tab state on initial load / refresh
  const initialHash = (window.location.hash || '').toLowerCase();
  let savedTab = null;
  try {
    savedTab = sessionStorage.getItem('active_ids_tab');
  } catch (e) {}

  if (initialHash === '#speakers' || initialHash === '#speaker' || savedTab === 'speakers') {
    switchMainTab('speakers', false);
  } else if (savedTab === 'schedule') {
    switchMainTab('schedule', false);
  }

  // Support direct hash change navigation
  window.addEventListener('hashchange', () => {
    const currentHash = (window.location.hash || '').toLowerCase();
    if (currentHash === '#speakers' || currentHash === '#speaker') {
      switchMainTab('speakers', false);
    } else if (currentHash === '#schedule') {
      switchMainTab('schedule', false);
    }
  });

  // 2. Day Selector: Day 1 vs Day 2 (October 7th vs October 8th)
  const dayBtns = document.querySelectorAll('.day-btn');
  const daySchedules = document.querySelectorAll('.day-schedule-content');
  const scheduleDayHeading = document.getElementById('scheduleDayHeading');

  const switchDay = (targetDay) => {
    const activeBtn = document.querySelector(`.day-btn[data-day="${targetDay}"]`);
    if (!activeBtn) return;

    dayBtns.forEach(b => b.classList.remove('active'));
    activeBtn.classList.add('active');

    const scheduleDayBadge = document.getElementById('scheduleDayBadge');
    const scheduleDayDate = document.getElementById('scheduleDayDate');

    if (scheduleDayBadge) {
      scheduleDayBadge.textContent = activeBtn.getAttribute('data-heading-label') || ((targetDay === 'day_2') ? 'Day 2' : 'Day 1');
    }
    if (scheduleDayDate) {
      scheduleDayDate.textContent = activeBtn.getAttribute('data-heading-detail') || '';
    }

    daySchedules.forEach(schedule => {
      if (schedule.getAttribute('data-day') === targetDay) {
        schedule.style.display = 'block';
      } else {
        schedule.style.display = 'none';
      }
    });

    // No sessionStorage needed: let server date dictate the default on load/refresh,
    // and click handler handles runtime switching smoothly.
  };

  dayBtns.forEach(btn => {
    btn.addEventListener('click', () => {
      const targetDay = btn.getAttribute('data-day');
      switchDay(targetDay);
    });
  });

  // Clean up any stale sessionStorage from previous tests/sessions
  try {
    sessionStorage.removeItem('active_ids_day');
  } catch (e) {}

  // 3. Cascade Stacked Cards Gallery with Synced 5-Thumbnail Navigation
  const galleryCascadeWrapper = document.getElementById('galleryCascade');
  const galleryDataEl = document.getElementById('galleryData');
  const prevBtn = document.getElementById('galleryPrevBtn');
  const nextBtn = document.getElementById('galleryNextBtn');
  const viewport = galleryCascadeWrapper ? galleryCascadeWrapper.querySelector('.gallery-cascade-viewport') : null;

  if (galleryCascadeWrapper && galleryDataEl) {
    let galleryItems = [];
    try {
      galleryItems = JSON.parse(galleryDataEl.textContent || '[]');
    } catch (e) {
      galleryItems = [];
    }

    if (galleryItems.length > 0) {
      const totalItems = galleryItems.length;
      let currentIndex = 0;
      let autoSlideTimer = null;
      const AUTO_SLIDE_DELAY = 5000; // 6.5 seconds (relaxed autoscroll pace)

      // Cache elements
      const stage = document.getElementById('galleryCascadeStage');
      const cards = stage ? stage.querySelectorAll('.gallery-cascade-card') : [];
      const thumbList = document.getElementById('galleryThumbList');
      const thumbItems = thumbList ? thumbList.querySelectorAll('.gallery-thumb-item') : [];

      // Check prefers-reduced-motion
      const prefersReducedMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

      // Safe modulo helper for circular infinite cycling
      const getNormalizedIndex = (idx) => {
        return ((idx % totalItems) + totalItems) % totalItems;
      };

      // Preload next and previous images for ultra-smooth transition
      const preloadedCache = new Set();
      const preloadImages = (centerIdx) => {
        for (let offset = -4; offset <= 4; offset++) {
          const targetIndex = getNormalizedIndex(centerIdx + offset);
          const item = galleryItems[targetIndex];
          if (item && item.image_url && !preloadedCache.has(item.image_url)) {
            const img = new Image();
            img.src = item.image_url;
            preloadedCache.add(item.image_url);
          }
        }
      };

      // Render the gallery with actual motion transitions:
      // Lightbox Modal Handlers for Center Card
      const galleryModal = document.getElementById('galleryModal');
      const modalOverlay = document.getElementById('galleryModalOverlay');
      const modalClose = document.getElementById('galleryModalClose');
      const modalImg = document.getElementById('galleryModalImg');
      const modalCaption = document.getElementById('galleryModalCaption');

      const openModal = (item) => {
        if (!item || !galleryModal) return;
        if (modalImg) {
          modalImg.src = item.image_url;
          modalImg.alt = item.caption || '';
        }
        if (modalCaption) {
          modalCaption.textContent = item.caption || '';
        }
        galleryModal.classList.add('is-open');
        galleryModal.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
      };

      const closeModal = () => {
        if (!galleryModal) return;
        galleryModal.classList.remove('is-open');
        galleryModal.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
      };

      if (modalOverlay) modalOverlay.addEventListener('click', closeModal);
      if (modalClose) modalClose.addEventListener('click', closeModal);
      document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && galleryModal && galleryModal.classList.contains('is-open')) {
          closeModal();
        }
      });

      // 7-card rotating architecture (-3 to 3) for continuous 60fps CSS hardware-accelerated movement
      const POSITIONS = [-3, -2, -1, 0, 1, 2, 3];
      let cardList = stage ? Array.from(stage.querySelectorAll('.gallery-cascade-card')) : [];
      const thumbListItems = thumbList ? Array.from(thumbList.querySelectorAll('.gallery-thumb-item')) : [];
      let isAnimating = false;

      // Update active state and sliding 5-slot window for thumbnails matching user specification:
      // index 0 -> [1(ac), 2, 3, 4, 5] (active slot 0)
      // index 1 -> [1, 2(ac), 3, 4, 5] (active slot 1)
      // index 2 -> [1, 2, 3(ac), 4, 5] (active slot 2)
      // index 3 -> [2, 3, 4(ac), 5, 6] (active slot 2)
      // index 4 -> [3, 4, 5(ac), 6, 7] (active slot 2)
      // Update active state and sliding window for thumbnails:
      // Mobile (<= 768px, 3 visible slots):
      // - index 0 -> [0(ac), 1, 2] (active slot 0)
      // - index >= 1 -> [i-1, i(ac), i+1] (active slot 1)
      // Desktop (> 768px, 5 visible slots):
      // - index 0 -> [0(ac), 1, 2, 3, 4] (active slot 0)
      // - index 1 -> [0, 1(ac), 2, 3, 4] (active slot 1)
      // - index >= 2 -> [i-2, i-1, i(ac), i+1, i+2] (active slot 2)
      const updateThumbnails = () => {
        const isMobile = window.innerWidth <= 768;
        let activeSlot = 0;
        let slotIndices = [];

        if (isMobile) {
          if (currentIndex === 0) {
            activeSlot = 0;
            slotIndices = [0, 1, 2].map(idx => getNormalizedIndex(idx));
          } else {
            activeSlot = 1;
            slotIndices = [-1, 0, 1].map(offset => getNormalizedIndex(currentIndex + offset));
          }
          // Pad remaining slots 3 and 4 so DOM elements stay safe
          slotIndices.push(getNormalizedIndex(currentIndex + 2), getNormalizedIndex(currentIndex + 3));
        } else {
          if (currentIndex === 0) {
            activeSlot = 0;
            slotIndices = [0, 1, 2, 3, 4].map(idx => getNormalizedIndex(idx));
          } else if (currentIndex === 1) {
            activeSlot = 1;
            slotIndices = [0, 1, 2, 3, 4].map(idx => getNormalizedIndex(idx));
          } else {
            activeSlot = 2;
            slotIndices = [-2, -1, 0, 1, 2].map(offset => getNormalizedIndex(currentIndex + offset));
          }
        }

        thumbListItems.forEach((thumb, slot) => {
          const itemIdx = slotIndices[slot];
          const item = galleryItems[itemIdx];
          if (!item) return;

          thumb.setAttribute('data-gallery-index', itemIdx);
          thumb.setAttribute('aria-label', `Pindah ke foto ${itemIdx + 1}: ${item.caption || ''}`);

          const img = thumb.querySelector('img');
          if (img) {
            if (img.getAttribute('src') !== item.image_url) {
              img.src = item.image_url;
            }
            img.alt = item.caption || `Thumbnail ${itemIdx + 1}`;
          }

          if (slot === activeSlot) {
            thumb.classList.add('is-active');
            thumb.setAttribute('aria-selected', 'true');
          } else {
            thumb.classList.remove('is-active');
            thumb.setAttribute('aria-selected', 'false');
          }
        });
      };

      // Ensure thumbnails update if resizing between mobile and desktop
      window.addEventListener('resize', () => {
        updateThumbnails();
      });

      // Initial layout setup: assign positions and initial images to all 7 cards
      const initGallery = () => {
        cardList.forEach((card, idx) => {
          const relPos = POSITIONS[idx];
          card.className = `gallery-cascade-card card-pos-${relPos}`;
          card.setAttribute('data-rel-pos', relPos);

          const targetIndex = getNormalizedIndex(currentIndex + relPos);
          const item = galleryItems[targetIndex];
          const img = card.querySelector('img');
          if (img && item) {
            img.src = item.image_url;
            img.alt = item.caption || `Gallery Foto ${targetIndex + 1}`;
          }
          if (relPos === 0) {
            card.setAttribute('aria-current', 'true');
            card.style.cursor = 'pointer';
          } else {
            card.removeAttribute('aria-current');
            card.style.cursor = 'pointer';
          }
        });
        updateThumbnails();
        preloadImages(currentIndex);
      };

      // Core Step Function: Rotates cards continuously with pure GPU CSS transforms
      const stepGallery = (dir) => {
        if (isAnimating || cardList.length < 7) return;
        isAnimating = true;

        currentIndex = getNormalizedIndex(currentIndex + dir);

        if (dir === 1) {
          // FORWARD / NEXT
          // cardList[0] is currently offscreen at pos -3. Pop from start, push to end.
          const exitingCard = cardList.shift();
          cardList.push(exitingCard);

          // Place exitingCard at pos 3 instantly without transition
          exitingCard.style.transition = 'none';
          exitingCard.className = 'gallery-cascade-card card-pos-3';
          exitingCard.setAttribute('data-rel-pos', '3');
          exitingCard.removeAttribute('aria-current');

          const nextItem = galleryItems[getNormalizedIndex(currentIndex + 3)];
          const nextImg = exitingCard.querySelector('img');
          if (nextImg && nextItem) {
            nextImg.src = nextItem.image_url;
            nextImg.alt = nextItem.caption || '';
          }
          void exitingCard.offsetHeight; // Force browser layout flush
          exitingCard.style.transition = '';

          // Transition the other 6 cards smoothly to their new positions (-3, -2, -1, 0, 1, 2)
          cardList.forEach((card, idx) => {
            if (idx === 6) return; // already placed at pos 3
            const newPos = idx - 3;
            card.className = `gallery-cascade-card card-pos-${newPos}`;
            card.setAttribute('data-rel-pos', newPos);
            if (newPos === 0) {
              card.setAttribute('aria-current', 'true');
            } else {
              card.removeAttribute('aria-current');
            }
          });
        } else if (dir === -1) {
          // BACKWARD / PREV
          // cardList[6] is currently offscreen at pos 3. Pop from end, unshift to start.
          const exitingCard = cardList.pop();
          cardList.unshift(exitingCard);

          // Place exitingCard at pos -3 instantly without transition
          exitingCard.style.transition = 'none';
          exitingCard.className = 'gallery-cascade-card card-pos--3';
          exitingCard.setAttribute('data-rel-pos', '-3');
          exitingCard.removeAttribute('aria-current');

          const prevItem = galleryItems[getNormalizedIndex(currentIndex - 3)];
          const prevImg = exitingCard.querySelector('img');
          if (prevImg && prevItem) {
            prevImg.src = prevItem.image_url;
            prevImg.alt = prevItem.caption || '';
          }
          void exitingCard.offsetHeight; // Force browser layout flush
          exitingCard.style.transition = '';

          // Transition the other 6 cards smoothly to their new positions (-2, -1, 0, 1, 2, 3)
          cardList.forEach((card, idx) => {
            if (idx === 0) return; // already placed at pos -3
            const newPos = idx - 3;
            card.className = `gallery-cascade-card card-pos-${newPos}`;
            card.setAttribute('data-rel-pos', newPos);
            if (newPos === 0) {
              card.setAttribute('aria-current', 'true');
            } else {
              card.removeAttribute('aria-current');
            }
          });
        }

        updateThumbnails();
        preloadImages(currentIndex);

        setTimeout(() => {
          isAnimating = false;
        }, 500);
      };

      const goToPrev = () => {
        stepGallery(-1);
        resetAutoSlideTimer();
      };

      const goToNext = () => {
        stepGallery(1);
        resetAutoSlideTimer();
      };

      const goToIndex = (targetIndex) => {
        if (targetIndex === currentIndex || isAnimating) return;
        const total = totalItems;
        let diff = (targetIndex - currentIndex + total) % total;
        if (diff > total / 2) diff -= total;

        if (diff === 1) {
          goToNext();
        } else if (diff === -1) {
          goToPrev();
        } else if (diff > 0) {
          stepGallery(1);
          resetAutoSlideTimer();
          setTimeout(() => {
            if (currentIndex !== targetIndex) {
              goToIndex(targetIndex);
            }
          }, 320);
        } else {
          stepGallery(-1);
          resetAutoSlideTimer();
          setTimeout(() => {
            if (currentIndex !== targetIndex) {
              goToIndex(targetIndex);
            }
          }, 320);
        }
      };

      // Click on cards in stage:
      // - Center card (relPos === 0): Open lightbox modal!
      // - Below center (relPos > 0): Advance forward!
      // - Above center (relPos < 0): Advance backward!
      if (stage) {
        stage.addEventListener('click', (e) => {
          const card = e.target.closest('.gallery-cascade-card');
          if (!card) return;
          const relPos = parseInt(card.getAttribute('data-rel-pos'), 10);
          if (relPos === 0) {
            const activeItem = galleryItems[currentIndex];
            openModal(activeItem);
          } else if (relPos > 0) {
            goToNext();
          } else if (relPos < 0) {
            goToPrev();
          }
        });
      }

      // Mobile touch swipe gesture support
      if (viewport) {
        let touchStartX = 0;
        let touchStartY = 0;
        let hasSwiped = false;

        viewport.addEventListener('touchstart', (e) => {
          if (e.touches.length !== 1) return;
          touchStartX = e.touches[0].clientX;
          touchStartY = e.touches[0].clientY;
          hasSwiped = false;
        }, { passive: true });

        viewport.addEventListener('touchmove', (e) => {
          if (hasSwiped || e.touches.length !== 1) return;
          const diffX = e.touches[0].clientX - touchStartX;
          const diffY = e.touches[0].clientY - touchStartY;
          if (Math.abs(diffX) > 42 && Math.abs(diffX) > Math.abs(diffY)) {
            hasSwiped = true;
            if (diffX < 0) {
              goToNext();
            } else {
              goToPrev();
            }
          }
        }, { passive: true });
      }

      // Auto scroll logic (runs the exact same stepGallery as button clicks!)
      const startAutoSlide = () => {
        if (prefersReducedMotion) return;
        stopAutoSlide();
        autoSlideTimer = setInterval(() => {
          if (galleryModal && galleryModal.classList.contains('is-open')) return;
          stepGallery(1);
        }, AUTO_SLIDE_DELAY);
      };

      const stopAutoSlide = () => {
        if (autoSlideTimer) {
          clearInterval(autoSlideTimer);
          autoSlideTimer = null;
        }
      };

      const resetAutoSlideTimer = () => {
        stopAutoSlide();
        startAutoSlide();
      };

      // Button Event Listeners
      if (prevBtn) {
        prevBtn.addEventListener('click', (e) => {
          e.preventDefault();
          goToPrev();
        });
      }

      if (nextBtn) {
        nextBtn.addEventListener('click', (e) => {
          e.preventDefault();
          goToNext();
        });
      }

      // Thumbnail Clicks: directly navigate to clicked photo
      if (thumbList) {
        thumbList.addEventListener('click', (e) => {
          const thumbBtn = e.target.closest('.gallery-thumb-item');
          if (!thumbBtn) return;
          const targetIdx = parseInt(thumbBtn.getAttribute('data-gallery-index'), 10);
          if (!isNaN(targetIdx)) {
            goToIndex(targetIdx);
          }
        });

        // Keyboard navigation for thumbnails
        thumbList.addEventListener('keydown', (e) => {
          if (e.key === 'ArrowUp' || e.key === 'ArrowLeft') {
            e.preventDefault();
            goToPrev();
          } else if (e.key === 'ArrowDown' || e.key === 'ArrowRight') {
            e.preventDefault();
            goToNext();
          }
        });
      }

      // Pause autoscroll on mouse enter, resume on leave
      galleryCascadeWrapper.addEventListener('mouseenter', stopAutoSlide);
      galleryCascadeWrapper.addEventListener('mouseleave', startAutoSlide);

      // Initialize
      initGallery();
      startAutoSlide();
    }
  }

  // 3.1 Locations Floor Plan Lightbox Modal Trigger
  const floorPlanCard = document.getElementById('locationFloorPlanCard');
  if (floorPlanCard) {
    floorPlanCard.addEventListener('click', () => {
      const imgUrl = floorPlanCard.getAttribute('data-img-url');
      const galleryModal = document.getElementById('galleryModal');
      const modalImg = document.getElementById('galleryModalImg');
      const modalCaption = document.getElementById('galleryModalCaption');

      if (imgUrl && modalImg) {
        modalImg.src = imgUrl;
        modalImg.alt = '';
      }
      if (modalCaption) {
        modalCaption.textContent = '';
      }
      const modalInfo = galleryModal ? galleryModal.querySelector('.gallery-modal-info') : null;
      if (modalInfo) {
        modalInfo.style.display = 'none';
      }
      if (galleryModal) {
        galleryModal.classList.add('is-open');
        galleryModal.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
      }
    });
  }

  // 4. Mobile Nav Toggle & Drawer
  const navToggle = document.getElementById('navToggle');
  const navCloseBtn = document.getElementById('navCloseBtn');
  const siteNav = document.getElementById('siteNav');

  const closeMobileNav = () => {
    if (siteNav) siteNav.classList.remove('open');
    if (navToggle) navToggle.classList.remove('open');
  };

  const openMobileNav = () => {
    if (siteNav) siteNav.classList.add('open');
    if (navToggle) navToggle.classList.add('open');
  };

  if (navToggle && siteNav) {
    navToggle.addEventListener('click', (e) => {
      e.stopPropagation();
      if (siteNav.classList.contains('open')) {
        closeMobileNav();
      } else {
        openMobileNav();
      }
    });

    if (navCloseBtn) {
      navCloseBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        closeMobileNav();
      });
    }

    // Close when clicking nav links
    const mobileLinks = siteNav.querySelectorAll('.nav-link, .btn-ticket');
    mobileLinks.forEach(link => {
      link.addEventListener('click', () => {
        closeMobileNav();
      });
    });

    // ScrollSpy: Update active state on nav-links based on current visible section
    const navLinks = document.querySelectorAll('.site-nav .nav-link');
    const updateActiveNav = () => {
      const scrollPos = (window.pageYOffset || document.documentElement.scrollTop || 0) + 160;
      const sections = [];

      navLinks.forEach(link => {
        const href = link.getAttribute('href');
        if (href && href.startsWith('#') && href.length > 1) {
          const targetEl = document.querySelector(href);
          if (targetEl) {
            sections.push({ link, targetEl, top: targetEl.offsetTop, bottom: targetEl.offsetTop + targetEl.offsetHeight });
          }
        }
      });

      if (sections.length === 0) return;

      let currentActiveLink = null;
      for (let i = sections.length - 1; i >= 0; i--) {
        if (scrollPos >= sections[i].top) {
          currentActiveLink = sections[i].link;
          break;
        }
      }

      if (!currentActiveLink && sections.length > 0) {
        currentActiveLink = sections[0].link;
      }

      navLinks.forEach(link => {
        if (link === currentActiveLink) {
          link.classList.add('active');
        } else {
          link.classList.remove('active');
        }
      });
    };

    window.addEventListener('scroll', updateActiveNav, { passive: true });
    window.addEventListener('resize', updateActiveNav, { passive: true });
    updateActiveNav();

    // Close when clicking outside of drawer on mobile
    document.addEventListener('click', (e) => {
      if (siteNav.classList.contains('open') && !siteNav.contains(e.target) && !navToggle.contains(e.target)) {
        closeMobileNav();
      }
    });
  }

  // 4.1 Navbar Hide-on-Scroll-Down, Show-on-Scroll-Up
  const siteHeader = document.getElementById('header') || document.querySelector('.site-header');
  if (siteHeader) {
    let lastScrollY = window.pageYOffset || document.documentElement.scrollTop || 0;
    let headerTicking = false;
    const heroSection = document.getElementById('home') || document.querySelector('.hero-section');
    const heroScrollIndicator = document.getElementById('heroScrollIndicator');

    const onScroll = () => {
      const currentScrollY = window.pageYOffset || document.documentElement.scrollTop || 0;
      
      // Hide mobile hero scroll down indicator if scrolled down slightly (> 10px)
      if (heroScrollIndicator) {
        if (currentScrollY > 10) {
          heroScrollIndicator.classList.add('is-hidden');
        } else {
          heroScrollIndicator.classList.remove('is-hidden');
        }
      }

      // Check if within hero section
      const isMobile = window.innerWidth <= 768;
      if (isMobile && heroSection) {
        const heroBottom = heroSection.offsetHeight - 40;
        if (currentScrollY < heroBottom) {
          siteHeader.classList.add('header-transparent');
        } else {
          siteHeader.classList.remove('header-transparent');
        }
        siteHeader.classList.remove('header-hero-transparent');
      } else {
        siteHeader.classList.remove('header-transparent');

        // Desktop: Transparent background (preserving blur) at top of hero section, colored when scrolled slightly
        if (currentScrollY <= 20) {
          siteHeader.classList.add('header-hero-transparent');
        } else {
          siteHeader.classList.remove('header-hero-transparent');
        }
      }

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
    window.addEventListener('resize', onScroll, { passive: true });
    onScroll();

    if (heroScrollIndicator) {
      heroScrollIndicator.addEventListener('click', () => {
        const nextSection = heroSection ? heroSection.nextElementSibling : null;
        if (nextSection) {
          nextSection.scrollIntoView({ behavior: 'smooth' });
        } else {
          window.scrollBy({ top: window.innerHeight * 0.85, behavior: 'smooth' });
        }
      });
    } // initial state check
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

    // Back to top appears after scrolling down 350px and stays visible all the way down
    if (backToTopBtn) {
      if (scrollPos > 350) {
        backToTopBtn.classList.add('is-visible');
      } else {
        backToTopBtn.classList.remove('is-visible');
      }
    }

    // Floating bottom bar appears when reaching overview section (#about-us) and continues downwards,
    // but hides at the end of sponsor section so footer stays clean at the bottom
    let isFloatingBarShown = false;
    if (floatingBottomBar) {
      if (isPastSponsor) {
        floatingBottomBar.classList.remove('is-visible');
      } else if (overviewSection) {
        const overviewTop = overviewSection.getBoundingClientRect().top + scrollPos;
        if (scrollPos >= overviewTop - 300) {
          floatingBottomBar.classList.add('is-visible');
          isFloatingBarShown = true;
        } else {
          floatingBottomBar.classList.remove('is-visible');
        }
      } else if (scrollPos > 450) {
        floatingBottomBar.classList.add('is-visible');
        isFloatingBarShown = true;
      } else {
        floatingBottomBar.classList.remove('is-visible');
      }
    }

    // In mobile, back-to-top stays lifted only when floatingBottomBar is visible, but drops to bottom corner cleanly in footer
    if (backToTopBtn) {
      if (isFloatingBarShown) {
        backToTopBtn.classList.add('is-lifted');
      } else {
        backToTopBtn.classList.remove('is-lifted');
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

    // Mouse Drag to Scroll Gesture (Mobile/tablet swipe experience when slider is active)
    let isMouseDown = false;
    let startX = 0;
    let initialScrollLeft = 0;
    let hasDragged = false;

    newsSliderContainer.addEventListener('mousedown', (e) => {
      if (window.innerWidth > 768) return; // Desktop is a static 4-column grid, no dragging needed
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
      const tintColor = group.getAttribute('data-tint') || '';
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

      const createItemInner = () => {
        if (tintColor) {
          const maskDiv = document.createElement('div');
          maskDiv.className = 'decoration-tint-mask';
          maskDiv.style.setProperty('--tint-color', tintColor);
          maskDiv.style.setProperty('--mask-url', `url('${imgSrc}')`);
          maskDiv.style.width = '100%';
          maskDiv.style.height = '100%';
          return maskDiv;
        } else if (imgSrc) {
          const img = document.createElement('img');
          img.src = imgSrc;
          img.alt = '';
          img.loading = 'lazy';
          return img;
        }
        return document.createTextNode('');
      };

      const startLeft = (startSide === 'left');

      if (count === 1) {
        const item = document.createElement('div');
        const edge = startLeft ? 'edge-left' : 'edge-right';
        item.className = `decoration-auto-item ${edge} ${rotClass}`;
        item.style.top = '50%';
        item.style.setProperty('--dec-width', `${width}px`);
        item.style.setProperty('--dec-opacity', opacity);
        item.style.setProperty('--dec-edge-offset', edgeOffset);
        item.appendChild(createItemInner());
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
        item.appendChild(createItemInner());
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

  // 12. Schedule Row Accordion: Disabled per user request (seluruh card speaker tampil langsung dan row tidak clickable)
  // Hanya .speaker-card yang clickable untuk membuka popup modal bio.

  // 13. Highlights Section: Mobile Swipe / Drag Support
  const highlightsGrid = document.getElementById('highlightsGrid');
  if (highlightsGrid) {
    let isDown = false;
    let startX = 0;
    let scrollLeft = 0;
    let hasMoved = false;

    highlightsGrid.addEventListener('mousedown', (e) => {
      if (window.innerWidth > 768) return;
      isDown = true;
      startX = e.pageX - highlightsGrid.offsetLeft;
      scrollLeft = highlightsGrid.scrollLeft;
      hasMoved = false;
      highlightsGrid.style.cursor = 'grabbing';
      highlightsGrid.style.scrollSnapType = 'none';
    });

    const stopDrag = () => {
      if (!isDown) return;
      isDown = false;
      highlightsGrid.style.cursor = '';
      highlightsGrid.style.scrollSnapType = '';
    };

    highlightsGrid.addEventListener('mouseleave', stopDrag);
    highlightsGrid.addEventListener('mouseup', stopDrag);

    highlightsGrid.addEventListener('mousemove', (e) => {
      if (!isDown) return;
      const x = e.pageX - highlightsGrid.offsetLeft;
      const walk = (x - startX);
      if (Math.abs(walk) > 6) {
        hasMoved = true;
      }
      highlightsGrid.scrollLeft = scrollLeft - walk;
    });

    highlightsGrid.querySelectorAll('.highlights-card-inner').forEach(link => {
      link.addEventListener('click', (e) => {
        if (hasMoved) {
          e.preventDefault();
          hasMoved = false;
        }
      });
    });
  }

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
    const textToType = typingEl.getAttribute('data-phrase') || 'Get Your Seat Now!';
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

  // 12. CMS Custom Placement Studio Live Sync Handler
  // Allows CMS iframe to request target section bounding rect & exact height for 1:1 coordinate precision
  window.addEventListener('message', (event) => {
    if (!event.data || event.data.type !== 'cms_request_section_info') return;
    const secKey = event.data.sectionKey;
    const targetEl = document.getElementById(secKey) || document.querySelector(`[data-section="${secKey}"]`);
    if (targetEl) {
      const rect = targetEl.getBoundingClientRect();
      const scrollY = window.scrollY || window.pageYOffset || 0;
      const topOffset = rect.top + scrollY;
      const height = targetEl.offsetHeight || rect.height;

      // Scroll smoothly to section inside iframe
      targetEl.scrollIntoView({ behavior: 'auto', block: 'start' });

      // Reply back to CMS parent window with exact dimensions
      if (window.parent && window.parent !== window) {
        window.parent.postMessage({
          type: 'cms_section_info_response',
          sectionKey: secKey,
          top: topOffset,
          height: height,
          width: targetEl.offsetWidth || rect.width
        }, '*');
      }
    }
  });

  // --------------------------------------------------------------------------
  // Hero Countdown Timer
  // --------------------------------------------------------------------------
  const initHeroCountdown = () => {
    const wrap = document.getElementById('heroCountdown');
    if (!wrap) return;

    const elDays = document.getElementById('cdDays');
    const elHours = document.getElementById('cdHours');
    const elMinutes = document.getElementById('cdMinutes');
    const elSeconds = document.getElementById('cdSeconds');
    if (!elDays || !elHours || !elMinutes || !elSeconds) return;

    const targetDateStr = wrap.dataset.targetDate || '2026-10-07T09:00:00+07:00';
    const targetTime = new Date(targetDateStr).getTime();

    let prevValues = { days: '', hours: '', minutes: '', seconds: '' };

    const updateUnit = (el, val, key) => {
      const formatted = String(val).padStart(2, '0');
      if (formatted !== prevValues[key]) {
        el.textContent = formatted;
        el.classList.remove('is-tick');
        // Trigger reflow for animation restart
        void el.offsetWidth;
        el.classList.add('is-tick');
        prevValues[key] = formatted;
      }
    };

    const tick = () => {
      const now = new Date().getTime();
      const diff = targetTime - now;

      if (diff <= 0) {
        updateUnit(elDays, 0, 'days');
        updateUnit(elHours, 0, 'hours');
        updateUnit(elMinutes, 0, 'minutes');
        updateUnit(elSeconds, 0, 'seconds');
        return;
      }

      const days = Math.floor(diff / (1000 * 60 * 60 * 24));
      const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
      const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
      const seconds = Math.floor((diff % (1000 * 60)) / 1000);

      updateUnit(elDays, days, 'days');
      updateUnit(elHours, hours, 'hours');
      updateUnit(elMinutes, minutes, 'minutes');
      updateUnit(elSeconds, seconds, 'seconds');
    };

    tick();
    setInterval(tick, 1000);
  };
  initHeroCountdown();
});

