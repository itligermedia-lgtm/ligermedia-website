/**
 * LIGER MEDIA - Frontend Interactions (Vanilla ES6)
 * Lightweight, high-performance interactions
 */

document.addEventListener('DOMContentLoaded', () => {
  initStickyHeader();
  initMobileMenu();
  initMegaMenuHover();
  initSmoothScroll();
  initSeriesScrollSpy();
  initQuickQuoteModal();
  initNikeHeroSlider();
  initSamsungScrollReveal();
  initPageContactForm();
  initCatalogFilters();
  initKnowledgeHubFilters();
  initInteractiveProductTabs();
  initColorSwatchFilter();
  initProductCategoryPills();
  initNikeSidebarFilter();
  initNikeMaterialGallery();
  initInstantPagePrefetch();
  initNikeCarouselModules();
  initNikeArticlesHub();
  initNikeFilterAccordions();
  initNikeProjectsHub();
  initNikeProjectLightbox();
  initLigerFlipbook();
});

/**
 * Sticky Header elevation on scroll
 */
function initStickyHeader() {
  const header = document.querySelector('.liger-site-header');
  if (!header) return;
  const parentPart = header.closest('.wp-block-template-part');

  let isScrolled = false;
  let ticking = false;

  const onScroll = () => {
    const scrollY = window.pageYOffset || document.documentElement.scrollTop;
    // Hysteresis gap: activate at > 60px, deactivate at < 15px to prevent bounce/jitter
    if (!isScrolled && scrollY > 60) {
      isScrolled = true;
      header.classList.add('is-scrolled');
      if (parentPart) parentPart.classList.add('is-scrolled');
    } else if (isScrolled && scrollY < 15) {
      isScrolled = false;
      header.classList.remove('is-scrolled');
      if (parentPart) parentPart.classList.remove('is-scrolled');
    }
    ticking = false;
  };

  window.addEventListener('scroll', () => {
    if (!ticking) {
      window.requestAnimationFrame(onScroll);
      ticking = true;
    }
  }, { passive: true });

  // Initial check on page load
  onScroll();
}

/**
 * Accessible Mobile Navigation (Right-side Off-Canvas Drawer)
 */
function initMobileMenu() {
  const toggleBtn = document.querySelector('.liger-mobile-menu-btn');
  const mobileNav = document.querySelector('.liger-mobile-nav');
  const backdrop = document.querySelector('.liger-drawer-backdrop');
  const closeBtn = document.querySelector('.liger-drawer-close-btn');
  const accordionBtn = document.querySelector('.liger-drawer-accordion-btn');
  const accordion = document.querySelector('.liger-drawer-accordion');

  if (!toggleBtn || !mobileNav) return;

  function openDrawer() {
    toggleBtn.setAttribute('aria-expanded', 'true');
    mobileNav.classList.add('is-open');
    if (backdrop) backdrop.classList.add('is-active');
    document.body.style.overflow = 'hidden';
  }

  function closeDrawer() {
    toggleBtn.setAttribute('aria-expanded', 'false');
    mobileNav.classList.remove('is-open');
    if (backdrop) backdrop.classList.remove('is-active');
    document.body.style.overflow = '';
  }

  toggleBtn.addEventListener('click', (e) => {
    e.stopPropagation();
    const isExpanded = toggleBtn.getAttribute('aria-expanded') === 'true';
    if (isExpanded) {
      closeDrawer();
    } else {
      openDrawer();
    }
  });

  if (closeBtn) {
    closeBtn.addEventListener('click', (e) => {
      e.stopPropagation();
      closeDrawer();
    });
  }

  if (backdrop) {
    backdrop.addEventListener('click', closeDrawer);
  }

  // Handle Products Accordion
  if (accordionBtn && accordion) {
    accordionBtn.addEventListener('click', (e) => {
      e.stopPropagation();
      const isExpanded = accordionBtn.getAttribute('aria-expanded') === 'true';
      accordionBtn.setAttribute('aria-expanded', !isExpanded);
      accordion.classList.toggle('is-expanded');
    });
  }

  // Close when clicking nav links
  mobileNav.querySelectorAll('a').forEach(link => {
    link.addEventListener('click', () => {
      closeDrawer();
    });
  });

  // Close on Escape key
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && mobileNav.classList.contains('is-open')) {
      closeDrawer();
    }
  });
}

/**
 * Smooth anchor scrolling for Series & Quote links
 */
function initSmoothScroll() {
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
      const targetId = this.getAttribute('href');
      if (!targetId || targetId === '#' || targetId.startsWith('#quote-modal')) return;

      const targetElement = document.querySelector(targetId);
      if (targetElement) {
        e.preventDefault();
        const headerOffset = 115; // Header + Sticky Series Bar offset
        const elementPosition = targetElement.getBoundingClientRect().top;
        const offsetPosition = elementPosition + window.pageYOffset - headerOffset;

        window.scrollTo({
          top: offsetPosition,
          behavior: 'smooth'
        });
      }
    });
  });
}

/**
 * Xiaomi-style Active Series Pill ScrollSpy
 * Highlights active tab without hijacking window scroll
 */
function initSeriesScrollSpy() {
  const pillLinks = document.querySelectorAll('.mi-series-nav-wrap .mi-pill-btn[href^="#"]');
  const allPill = document.querySelector('.mi-series-nav-wrap .mi-pill-btn:first-child');
  if (!pillLinks.length) return;

  const sections = Array.from(pillLinks).map(link => {
    const targetId = link.getAttribute('href');
    return document.querySelector(targetId);
  }).filter(Boolean);

  if (!sections.length) return;

  let ticking = false;
  window.addEventListener('scroll', () => {
    if (!ticking) {
      window.requestAnimationFrame(() => {
        const scrollPos = window.scrollY + 160;

        let currentSectionId = null;
        sections.forEach(sec => {
          if (sec.offsetTop <= scrollPos) {
            currentSectionId = sec.getAttribute('id');
          }
        });

        if (!currentSectionId || window.scrollY < 200) {
          if (allPill && !allPill.classList.contains('is-active')) {
            pillLinks.forEach(l => l.classList.remove('is-active'));
            allPill.classList.add('is-active');
          }
        } else {
          if (allPill) allPill.classList.remove('is-active');
          pillLinks.forEach(link => {
            const linkTarget = link.getAttribute('href').replace('#', '');
            if (linkTarget === currentSectionId) {
              link.classList.add('is-active');
            } else {
              link.classList.remove('is-active');
            }
          });
        }

        ticking = false;
      });
      ticking = true;
    }
  }, { passive: true });
}

/**
 * Xiaomi Quick Quote & Sample Request Drawer / Modal
 */
function initQuickQuoteModal() {
  const modal = document.getElementById('mi-quote-modal');
  if (!modal) return;

  const closeBtn = modal.querySelector('.mi-modal-close');
  const quoteForm = modal.querySelector('#mi-quote-form');
  const materialSelect = modal.querySelector('#quote-material-select');

  const openModal = (productName = '') => {
    modal.classList.add('is-active');
    document.body.style.overflow = 'hidden';

    if (materialSelect && productName) {
      let matched = false;
      for (let i = 0; i < materialSelect.options.length; i++) {
        if (materialSelect.options[i].text.toLowerCase().includes(productName.toLowerCase()) ||
            productName.toLowerCase().includes(materialSelect.options[i].value.toLowerCase())) {
          materialSelect.selectedIndex = i;
          matched = true;
          break;
        }
      }
      if (!matched && productName) {
        materialSelect.value = 'other';
        const notes = modal.querySelector('#quote-notes');
        if (notes && !notes.value) {
          notes.value = `สนใจสินค้า: ${productName}`;
        }
      }
    }
  };

  const closeModal = () => {
    modal.classList.remove('is-active');
    document.body.style.overflow = '';
  };

  // Open triggers
  document.querySelectorAll('[data-open-quote-modal], a[href="#quote-modal"], .trigger-quote-modal').forEach(trigger => {
    trigger.addEventListener('click', (e) => {
      e.preventDefault();
      const productName = trigger.getAttribute('data-product-name') || '';
      openModal(productName);
    });
  });

  // Close triggers
  if (closeBtn) closeBtn.addEventListener('click', closeModal);

  modal.addEventListener('click', (e) => {
    if (e.target === modal) closeModal();
  });

  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && modal.classList.contains('is-active')) {
      closeModal();
    }
  });

  // Handle Form Submission
  if (quoteForm) {
    quoteForm.addEventListener('submit', (e) => {
      e.preventDefault();
      const submitBtn = quoteForm.querySelector('button[type="submit"]');
      if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span>กำลังส่งข้อมูล...</span>';
      }

      setTimeout(() => {
        quoteForm.innerHTML = `
          <div style="text-align:center;padding:2rem 1rem;">
            <div style="width:54px;height:54px;border-radius:50%;background:#E8F8F0;color:#06C755;display:inline-flex;align-items:center;justify-content:center;margin-bottom:1rem;">
              <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
            </div>
            <h3 style="font-size:1.35rem;font-weight:700;color:#111;margin-bottom:0.5rem;">เจ้าหน้าที่ฝ่ายขายได้รับข้อมูลแล้ว</h3>
            <p style="font-size:0.95rem;color:#666;line-height:1.6;margin-bottom:1.5rem;">
              วิศวกรผู้เชี่ยวชาญจะติดต่อกลับเพื่อให้สเปกและใบเสนอราคาโดยเร็วที่สุด
            </p>
            <a href="https://line.me/R/ti/p/@ligermedia" target="_blank" rel="noopener noreferrer" class="mi-modal-direct-line" style="display:inline-flex;">
              <span>ส่งแบบเร่งด่วนทาง LINE Official @ligermedia</span>
            </a>
          </div>
        `;
      }, 600);
    });
  }
}

/**
 * Nike-Style Full-Width Hero Slider Engine
 * Features: Auto-play, pause on hover, touch/swipe support, dot navigation
 */
function initNikeHeroSlider() {
  const slider = document.getElementById('nike-hero-slider');
  if (!slider) return;

  const slides = slider.querySelectorAll('.nike-slide');
  const dots = slider.querySelectorAll('.nike-slider-dot, .nike-dot');
  const prevBtn = slider.querySelector('#nike-prev-btn');
  const nextBtn = slider.querySelector('#nike-next-btn');
  const counterNum = slider.querySelector('#nike-active-num');
  const ctaBtn = slider.querySelector('#nike-hero-cta-btn');

  if (!slides.length) return;

  let currentIndex = 0;
  const totalSlides = slides.length;
  let autoPlayTimer = null;
  const autoPlayInterval = 5500; // 5.5 seconds

  const updateSlide = (newIndex) => {
    // Wrap index
    if (newIndex >= totalSlides) {
      currentIndex = 0;
    } else if (newIndex < 0) {
      currentIndex = totalSlides - 1;
    } else {
      currentIndex = newIndex;
    }

    // Update slides
    slides.forEach((slide, idx) => {
      if (idx === currentIndex) {
        slide.classList.add('is-active');
      } else {
        slide.classList.remove('is-active');
      }
    });

    // Update dots
    dots.forEach((dot, idx) => {
      if (idx === currentIndex) {
        dot.classList.add('is-active');
      } else {
        dot.classList.remove('is-active');
      }
    });

    // Update counter (e.g. "01")
    if (counterNum) {
      counterNum.textContent = String(currentIndex + 1).padStart(2, '0');
    }

    // Update dynamic shop button link based on active slide
    if (ctaBtn) {
      const activeSlide = slides[currentIndex];
      const link = activeSlide ? (activeSlide.getAttribute('data-link') || activeSlide.querySelector('a.nike-slide-bg')?.getAttribute('href')) : null;
      if (link) {
        ctaBtn.setAttribute('href', link);
      }
    }
  };

  const nextSlide = () => updateSlide(currentIndex + 1);
  const prevSlide = () => updateSlide(currentIndex - 1);

  // Auto Play controls
  const startAutoPlay = () => {
    stopAutoPlay();
    autoPlayTimer = setInterval(nextSlide, autoPlayInterval);
  };

  const stopAutoPlay = () => {
    if (autoPlayTimer) {
      clearInterval(autoPlayTimer);
      autoPlayTimer = null;
    }
  };

  // Button clicks
  if (nextBtn) {
    nextBtn.addEventListener('click', () => {
      nextSlide();
      startAutoPlay();
    });
  }

  if (prevBtn) {
    prevBtn.addEventListener('click', () => {
      prevSlide();
      startAutoPlay();
    });
  }

  // Dot clicks
  dots.forEach(dot => {
    dot.addEventListener('click', () => {
      const targetIdx = parseInt(dot.getAttribute('data-index'), 10);
      if (!isNaN(targetIdx)) {
        updateSlide(targetIdx);
        startAutoPlay();
      }
    });
  });

  // Pause on hover
  slider.addEventListener('mouseenter', stopAutoPlay);
  slider.addEventListener('mouseleave', startAutoPlay);

  // Touch Swipe for Mobile / Tablet
  let touchStartX = 0;
  let touchEndX = 0;

  slider.addEventListener('touchstart', (e) => {
    touchStartX = e.changedTouches[0].screenX;
    stopAutoPlay();
  }, { passive: true });

  slider.addEventListener('touchend', (e) => {
    touchEndX = e.changedTouches[0].screenX;
    const diff = touchStartX - touchEndX;

    if (Math.abs(diff) > 40) {
      if (diff > 0) {
        nextSlide(); // Swiped left -> next
      } else {
        prevSlide(); // Swiped right -> prev
      }
    }
    startAutoPlay();
  }, { passive: true });

  // Keyboard navigation when slider has focus
  slider.setAttribute('tabindex', '0');
  slider.addEventListener('keydown', (e) => {
    if (e.key === 'ArrowRight') {
      nextSlide();
      startAutoPlay();
    } else if (e.key === 'ArrowLeft') {
      prevSlide();
      startAutoPlay();
    }
  });

  // Initial start
  startAutoPlay();
}

/**
 * Xiaomi-style Mega Menu with Hover Intent and Graceful Hold
 * Strictly ensures only ONE menu is open at a time with zero overlap/jitter
 */
function initMegaMenuHover() {
  const desktopNav = document.querySelector('.liger-desktop-nav');
  const navItems = document.querySelectorAll('.mi-nav-item.has-mega-menu');
  if (!navItems.length) return;

  let activeItem = null;
  let closeTimer = null;
  const CLOSE_DELAY = 220; // ms grace period to cross the gap into drawer

  const closeAll = () => {
    if (closeTimer) {
      clearTimeout(closeTimer);
      closeTimer = null;
    }
    navItems.forEach(item => item.classList.remove('is-open'));
    activeItem = null;
  };

  const openDrawer = (item) => {
    if (closeTimer) {
      clearTimeout(closeTimer);
      closeTimer = null;
    }

    if (activeItem === item) return;

    // Immediately close any other open drawer so they never overlap
    navItems.forEach(other => {
      if (other !== item) {
        other.classList.remove('is-open');
      }
    });

    item.classList.add('is-open');
    activeItem = item;
  };

  const scheduleClose = () => {
    if (closeTimer) clearTimeout(closeTimer);
    closeTimer = setTimeout(() => {
      closeAll();
    }, CLOSE_DELAY);
  };

  const cancelClose = () => {
    if (closeTimer) {
      clearTimeout(closeTimer);
      closeTimer = null;
    }
  };

  navItems.forEach(item => {
    const drawer = item.querySelector('.mi-mega-menu-drawer');

    // Entering nav item triggers immediate switch
    item.addEventListener('mouseenter', () => {
      openDrawer(item);
    });

    item.addEventListener('mouseleave', () => {
      scheduleClose();
    });

    // Keeping cursor in drawer cancels close timer
    if (drawer) {
      drawer.addEventListener('mouseenter', () => {
        cancelClose();
      });
      drawer.addEventListener('mouseleave', () => {
        scheduleClose();
      });
    }
  });

  // If hovering any non-mega nav links, close immediately with zero delay
  if (desktopNav) {
    const nonMegaLinks = desktopNav.querySelectorAll(':scope > a.liger-nav-link');
    nonMegaLinks.forEach(link => {
      link.addEventListener('mouseenter', () => {
        closeAll();
      });
    });
  }

  // Left Sub-category Hover Switcher (Xiaomi style active state)
  const leftSubItems = document.querySelectorAll('.mi-mega-left-col .mi-mega-sub-item');
  leftSubItems.forEach(subItem => {
    subItem.addEventListener('mouseenter', () => {
      const parentCol = subItem.closest('.mi-mega-left-col');
      if (parentCol) {
        parentCol.querySelectorAll('.mi-mega-sub-item').forEach(el => el.classList.remove('is-active'));
        subItem.classList.add('is-active');
      }
    });
  });

  // Close menus on Escape or click outside
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
      closeAll();
    }
  });

  document.addEventListener('click', (e) => {
    if (!e.target.closest('.liger-site-header')) {
      closeAll();
    }
  });
}

/**
 * Samsung-style Interactive Scroll Reveal & Stagger Animation
 * Smooth, 60fps GPU-accelerated reveals on scroll (left, right, up, stagger)
 */
function initSamsungScrollReveal() {
  // 1. Auto-tag key layout elements for cinematic scroll choreography
  // Section Headers: title glides in from Left, "view all" link glides in from Right
  document.querySelectorAll('.apple-section-header').forEach(header => {
    const leftText = header.querySelector(':scope > div:first-child');
    const rightLink = header.querySelector(':scope > .apple-see-all-link, :scope > a');
    if (leftText && !leftText.hasAttribute('data-reveal')) {
      leftText.setAttribute('data-reveal', 'left');
    }
    if (rightLink && !rightLink.hasAttribute('data-reveal')) {
      rightLink.setAttribute('data-reveal', 'right');
    }
  });

  // Product Shelves: 3 cards slide up with staggered cinematic delay
  document.querySelectorAll('.apple-shelf-grid').forEach(shelf => {
    const cards = shelf.querySelectorAll(':scope > .apple-card, :scope > .mi-product-card');
    cards.forEach((card, idx) => {
      if (!card.hasAttribute('data-reveal')) {
        card.setAttribute('data-reveal', 'up');
        card.setAttribute('data-reveal-delay', ((idx % 3) + 1).toString());
      }
    });
  });

  // Nike Shop Product Cards: Staggered reveal on scroll
  document.querySelectorAll('.nike-prod-card').forEach((card, idx) => {
    if (!card.hasAttribute('data-reveal')) {
      card.setAttribute('data-reveal', 'up');
      card.setAttribute('data-reveal-delay', ((idx % 3) + 1).toString());
    }
  });

  // VIP Service Grid: Staggered reveal for 4 service cards
  document.querySelectorAll('.mi-service-grid').forEach(grid => {
    const boxes = grid.querySelectorAll(':scope > .mi-service-box');
    boxes.forEach((box, idx) => {
      if (!box.hasAttribute('data-reveal')) {
        box.setAttribute('data-reveal', idx % 2 === 0 ? 'left' : 'right');
        box.setAttribute('data-reveal-delay', ((idx % 2) + 1).toString());
      }
    });
  });

  // Spec Comparison Table: Left title, Right button, Center table
  const specSection = document.querySelector('.liger-spec-table-wrap')?.closest('.wp-block-group.alignfull');
  if (specSection) {
    const specHeader = specSection.querySelector(':scope > .alignwide > div:first-child');
    if (specHeader) {
      const leftCol = specHeader.querySelector(':scope > div:first-child');
      const rightCol = specHeader.querySelector(':scope > div:last-child');
      if (leftCol && !leftCol.hasAttribute('data-reveal')) leftCol.setAttribute('data-reveal', 'left');
      if (rightCol && !rightCol.hasAttribute('data-reveal')) rightCol.setAttribute('data-reveal', 'right');
    }
    const tableWrap = specSection.querySelector('.liger-spec-table-wrap');
    if (tableWrap && !tableWrap.hasAttribute('data-reveal')) {
      tableWrap.setAttribute('data-reveal', 'up');
    }
  }

  // Quality & Trust Stats Columns: Left col from left, Right col from right
  const trustColumns = document.querySelectorAll('.wp-block-columns.are-vertically-aligned-center > .wp-block-column');
  if (trustColumns.length === 2) {
    if (!trustColumns[0].hasAttribute('data-reveal')) trustColumns[0].setAttribute('data-reveal', 'left');
    if (!trustColumns[1].hasAttribute('data-reveal')) trustColumns[1].setAttribute('data-reveal', 'right');
  }

  // CTA Banner: Left text from left, Right action buttons from right
  const ctaSection = document.querySelector('.liger-badge--orange')?.closest('.wp-block-group.alignwide');
  if (ctaSection) {
    const textCol = ctaSection.querySelector(':scope > div:first-child');
    const btnCol = ctaSection.querySelector(':scope > div:last-child');
    if (textCol && !textCol.hasAttribute('data-reveal')) textCol.setAttribute('data-reveal', 'left');
    if (btnCol && !btnCol.hasAttribute('data-reveal')) btnCol.setAttribute('data-reveal', 'right');
  }

  // 2. Query all reveal targets
  const elements = document.querySelectorAll('[data-reveal]');
  if (!elements.length) return;

  // Fallback for browsers without IntersectionObserver
  if (!('IntersectionObserver' in window)) {
    elements.forEach(el => el.classList.add('is-revealed'));
    return;
  }

  // 3. High-performance IntersectionObserver (Samsung/Apple smooth easing)
  const revealObserver = new IntersectionObserver((entries, observer) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('is-revealed');
        observer.unobserve(entry.target); // Reveal once smoothly and remain in place
      }
    });
  }, {
    root: null,
    rootMargin: '0px 0px -50px 0px', // Triggers smoothly right as element rolls into viewport
    threshold: 0.08
  });

  elements.forEach(el => revealObserver.observe(el));
}

/**
 * Interactive Contact Form Handler (Supports Homepage & Contact Page)
 */
function initPageContactForm() {
  const forms = document.querySelectorAll('.liger-page-form');
  if (!forms.length) return;

  forms.forEach(form => {
    const feedback = form.querySelector('.liger-feedback-msg');
    const submitBtn = form.querySelector('.liger-contact-submit-btn');

    form.addEventListener('submit', (e) => {
      e.preventDefault();

      if (!form.checkValidity()) {
        form.reportValidity();
        return;
      }

      const originalBtnText = submitBtn ? submitBtn.innerHTML : '';
      if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span>กำลังส่งข้อความ...</span>';
      }

      setTimeout(() => {
        if (submitBtn) {
          submitBtn.disabled = false;
          submitBtn.innerHTML = originalBtnText;
        }

        if (feedback) {
          feedback.className = 'liger-feedback-msg is-success';
          feedback.style.display = 'block';
          feedback.innerHTML = '✓ ส่งข้อความเรียบร้อยแล้ว! เจ้าหน้าที่ LIGER MEDIA จะติดต่อกลับโดยเร็วที่สุด หรือสามารถแอด LINE: <a href="https://line.me/R/ti/p/@ligermedia" target="_blank" style="color:#06C755;font-weight:700;">@ligermedia</a> เพื่อสอบถามด่วน';
        }

        form.reset();
      }, 600);
    });
  });
}

/**
 * Catalogs & Specs Interactive Filtering & Instant Search (Nike Two-Column Layout)
 */
function initCatalogFilters() {
  const catalogsLayout = document.getElementById('nike-catalogs-layout');
  const toggleBtn = document.getElementById('nike-catalogs-filter-toggle');
  const toggleText = toggleBtn ? toggleBtn.querySelector('.nike-filter-btn-text') : null;
  const mobileFilterBtn = document.getElementById('nike-catalogs-mobile-filter-btn');
  const sidebar = document.getElementById('nike-catalogs-sidebar');
  const closeBtn = document.getElementById('nike-catalogs-sidebar-close-btn');
  const backdrop = document.getElementById('nike-catalogs-sidebar-backdrop');

  const tabs = document.querySelectorAll('.liger-cat-tab');
  const cards = document.querySelectorAll('.liger-cat-card');
  const searchInput = document.getElementById('liger-cat-search-input');
  const clearBtn = document.getElementById('liger-cat-search-clear');
  const emptyState = document.getElementById('liger-cat-no-results');
  const resetBtn = document.getElementById('liger-cat-reset-btn');
  const liveCountEl = document.getElementById('nike-catalogs-live-count');

  // Desktop Toggle: Hide / Show Sidebar
  if (toggleBtn && catalogsLayout) {
    toggleBtn.addEventListener('click', () => {
      const isHidden = catalogsLayout.classList.toggle('is-sidebar-hidden');
      toggleBtn.setAttribute('aria-expanded', !isHidden);
      if (toggleText) {
        toggleText.textContent = isHidden ? 'แสดงตัวกรอง' : 'ซ่อนตัวกรอง';
      }
    });
  }

  // Mobile Drawer Open / Close
  const openMobileDrawer = () => {
    if (sidebar) sidebar.classList.add('is-open');
    if (backdrop) backdrop.classList.add('is-active');
    document.body.style.overflow = 'hidden';
  };

  const closeMobileDrawer = () => {
    if (sidebar) sidebar.classList.remove('is-open');
    if (backdrop) backdrop.classList.remove('is-active');
    document.body.style.overflow = '';
  };

  if (mobileFilterBtn) mobileFilterBtn.addEventListener('click', openMobileDrawer);
  if (closeBtn) closeBtn.addEventListener('click', closeMobileDrawer);
  if (backdrop) backdrop.addEventListener('click', closeMobileDrawer);

  if (!cards.length) return;

  let currentCategory = 'all';
  let searchQuery = '';

  const applyFilters = () => {
    let visibleCount = 0;
    const query = searchQuery.trim().toLowerCase();

    cards.forEach(card => {
      const cardCat = card.dataset.category || '';
      const cardText = (card.innerText || card.textContent).toLowerCase();

      const matchesCat = (currentCategory === 'all') || (cardCat === currentCategory);
      const matchesSearch = !query || cardText.includes(query);

      if (matchesCat && matchesSearch) {
        card.style.display = 'flex';
        visibleCount++;
      } else {
        card.style.display = 'none';
      }
    });

    if (liveCountEl) {
      liveCountEl.textContent = visibleCount;
    }

    if (emptyState) {
      emptyState.style.display = visibleCount === 0 ? 'block' : 'none';
    }
  };

  // Tab Filtering
  tabs.forEach(tab => {
    tab.addEventListener('click', () => {
      tabs.forEach(t => t.classList.remove('is-active'));
      tab.classList.add('is-active');
      currentCategory = tab.dataset.filter || 'all';
      applyFilters();

      if (window.innerWidth <= 991) {
        closeMobileDrawer();
      }
    });
  });

  // Search Input
  if (searchInput) {
    searchInput.addEventListener('input', (e) => {
      searchQuery = e.target.value;
      if (clearBtn) {
        clearBtn.style.display = searchQuery ? 'inline-block' : 'none';
      }
      applyFilters();
    });
  }

  // Clear Button
  if (clearBtn) {
    clearBtn.addEventListener('click', () => {
      if (searchInput) searchInput.value = '';
      searchQuery = '';
      clearBtn.style.display = 'none';
      applyFilters();
      if (searchInput) searchInput.focus();
    });
  }

  // Reset Button in Empty State
  if (resetBtn) {
    resetBtn.addEventListener('click', () => {
      currentCategory = 'all';
      tabs.forEach(t => {
        if (t.dataset.filter === 'all') t.classList.add('is-active');
        else t.classList.remove('is-active');
      });
      if (searchInput) searchInput.value = '';
      searchQuery = '';
      if (clearBtn) clearBtn.style.display = 'none';
      applyFilters();
    });
  }

  // Check URL query parameter (e.g. ?cat=acp, ?cat=interior)
  try {
    const urlParams = new URLSearchParams(window.location.search);
    const catParam = urlParams.get('cat');
    if (catParam) {
      tabs.forEach(t => {
        if (t.dataset.filter === catParam) {
          tabs.forEach(tab => tab.classList.remove('is-active'));
          t.classList.add('is-active');
          currentCategory = catParam;
        }
      });
    }
  } catch (err) {}

  applyFilters();
}

/**
 * Liger Knowledge Hub Category Filter Pills
 */
function initKnowledgeHubFilters() {
  const pills = document.querySelectorAll('.liger-hub-pill');
  const cards = document.querySelectorAll('.liger-article-card[data-hub-cat]');
  if (!pills.length || !cards.length) return;

  pills.forEach(pill => {
    pill.addEventListener('click', () => {
      pills.forEach(p => p.classList.remove('is-active'));
      pill.classList.add('is-active');

      const filter = pill.getAttribute('data-hub-filter') || 'all';
      cards.forEach(card => {
        const cat = card.getAttribute('data-hub-cat');
        if (filter === 'all' || cat === filter) {
          card.classList.remove('is-hidden');
          card.style.display = 'flex';
        } else {
          card.classList.add('is-hidden');
          card.style.display = 'none';
        }
      });
    });
  });
}

/**
 * Interactive Product Catalog Grid Filter (All + 8 Categories)
 */
function initInteractiveProductTabs() {
  const pills = document.querySelectorAll('.liger-filter-pill');
  const cards = document.querySelectorAll('.liger-p-card');
  if (!pills.length || !cards.length) return;

  pills.forEach(pill => {
    pill.addEventListener('click', () => {
      const targetCat = pill.getAttribute('data-filter') || 'all';

      pills.forEach(p => {
        p.classList.remove('is-active');
        p.setAttribute('aria-selected', 'false');
      });
      pill.classList.add('is-active');
      pill.setAttribute('aria-selected', 'true');

      cards.forEach(card => {
        const cardCat = card.getAttribute('data-cat');
        if (targetCat === 'all' || cardCat === targetCat) {
          card.classList.remove('is-hidden');
          card.style.display = 'flex';
        } else {
          card.classList.add('is-hidden');
          card.style.display = 'none';
        }
      });
    });
  });
}

/**
 * Color Swatch Filter & Deep-linking
 */
function initColorSwatchFilter() {
  const swatchPills = document.querySelectorAll('.liger-swatch-pill');
  const swatchCards = document.querySelectorAll('.liger-swatch-card');
  if (!swatchPills.length || !swatchCards.length) return;

  swatchPills.forEach(pill => {
    pill.addEventListener('click', () => {
      const targetCat = pill.getAttribute('data-swatch-filter') || 'all';

      swatchPills.forEach(p => p.classList.remove('is-active'));
      pill.classList.add('is-active');

      swatchCards.forEach(card => {
        const cardCat = card.getAttribute('data-swatch-cat');
        if (targetCat === 'all' || cardCat === targetCat) {
          card.classList.remove('is-hidden');
          card.style.display = 'flex';
        } else {
          card.classList.add('is-hidden');
          card.style.display = 'none';
        }
      });
    });
  });

  // Handle URL hash: if page loaded with #color-explorer, scroll smoothly
  if (window.location.hash === '#color-explorer') {
    setTimeout(() => {
      const explorer = document.getElementById('color-explorer');
      if (explorer) {
        const offset = 90;
        const top = explorer.getBoundingClientRect().top + window.pageYOffset - offset;
        window.scrollTo({ top, behavior: 'smooth' });
      }
    }, 200);
  }
}

/**
 * Auto-highlight active Category Pill and toggle ACP Color Swatches on category pages
 */
function initProductCategoryPills() {
  const currentPath = window.location.pathname.replace(/\/+$/, '').toLowerCase();
  const pills = document.querySelectorAll('.mi-pill-btn[data-cat-slug]');
  const allBtn = document.querySelector('.mi-pill-btn[href*="/products/"]');
  const catLinks = document.querySelectorAll('.nike-cat-link[data-cat-slug]');
  const allCatLink = document.querySelector('.nike-cat-link[href*="/products/"]');
  const acpSection = document.getElementById('acp-color-swatches-section');

  let matched = false;
  pills.forEach(pill => {
    const slug = (pill.getAttribute('data-cat-slug') || '').toLowerCase();
    if (slug && (currentPath.endsWith('/' + slug) || currentPath.includes('/product-category/' + slug))) {
      pill.classList.add('is-active');
      matched = true;
      if (slug === 'acp' && acpSection) {
        acpSection.style.display = 'block';
      }
    } else {
      pill.classList.remove('is-active');
    }
  });

  catLinks.forEach(link => {
    const slug = (link.getAttribute('data-cat-slug') || '').toLowerCase();
    if (slug && (currentPath.endsWith('/' + slug) || currentPath.includes('/product-category/' + slug))) {
      link.classList.add('is-active');
    } else {
      link.classList.remove('is-active');
    }
  });

  // Dynamic Category Banner Background Image
  const archiveHeader = document.querySelector('.nike-archive-header');
  if (archiveHeader) {
    const categories = ['pvc-sheet', 'acp', 'interior', 'led', 'sticker', 'vinyl', 'inkjet', 'accessories'];
    for (const cat of categories) {
      if (currentPath.includes('/product-category/' + cat)) {
        archiveHeader.setAttribute('data-bg', cat);
        break;
      }
    }
  }

  if (!matched && (currentPath.endsWith('/products') || currentPath.includes('/products/'))) {
    if (allBtn) allBtn.classList.add('is-active');
    if (allCatLink) allCatLink.classList.add('is-active');
  }
}

/**
 * Nike-Style Collapsible Sidebar & Mobile Filter Drawer
 */
function initNikeSidebarFilter() {
  const shopLayout = document.getElementById('nike-shop-layout');
  const toggleBtn = document.getElementById('nike-filter-toggle');
  const toggleText = toggleBtn ? toggleBtn.querySelector('.nike-filter-btn-text') : null;
  const mobileFilterBtn = document.getElementById('nike-mobile-filter-btn');
  const sidebar = document.getElementById('nike-shop-sidebar');
  const closeBtn = document.getElementById('nike-sidebar-close-btn');
  const backdrop = document.getElementById('nike-sidebar-backdrop');

  // Desktop Toggle: Hide / Show Sidebar
  if (toggleBtn && shopLayout) {
    toggleBtn.addEventListener('click', () => {
      const isHidden = shopLayout.classList.toggle('is-sidebar-hidden');
      toggleBtn.setAttribute('aria-expanded', !isHidden);
      if (toggleText) {
        toggleText.textContent = isHidden ? 'แสดงตัวกรอง' : 'ซ่อนตัวกรอง';
      }
    });
  }

  // Mobile Drawer Open / Close
  const openMobileDrawer = () => {
    if (sidebar) sidebar.classList.add('is-open');
    if (backdrop) backdrop.classList.add('is-active');
    document.body.style.overflow = 'hidden';
  };

  const closeMobileDrawer = () => {
    if (sidebar) sidebar.classList.remove('is-open');
    if (backdrop) backdrop.classList.remove('is-active');
    document.body.style.overflow = '';
  };

  if (mobileFilterBtn) mobileFilterBtn.addEventListener('click', openMobileDrawer);
  if (closeBtn) closeBtn.addEventListener('click', closeMobileDrawer);
  if (backdrop) backdrop.addEventListener('click', closeMobileDrawer);
}

/**
 * Nike-Style Multi-Angle Material Showcase Gallery Controller
 */
function initNikeMaterialGallery() {
  const gallery = document.getElementById('nike-material-gallery');
  if (!gallery) return;

  const thumbBtns = Array.from(gallery.querySelectorAll('.nike-thumb-btn'));
  const mainImg = document.getElementById('nike-main-img');
  const imgContainer = document.getElementById('nike-zoom-target');
  const indicator = document.getElementById('nike-angle-indicator');
  const prevBtn = document.getElementById('nike-arrow-prev');
  const nextBtn = document.getElementById('nike-arrow-next');

  if (!thumbBtns.length || !mainImg) return;

  let currentIndex = 0;

  const switchView = (index) => {
    if (index < 0) index = thumbBtns.length - 1;
    if (index >= thumbBtns.length) index = 0;
    currentIndex = index;

    const activeBtn = thumbBtns[currentIndex];
    const newSrc = activeBtn.getAttribute('data-full-src');
    const angleTitle = activeBtn.getAttribute('data-angle-title') || '';

    // Active button state
    thumbBtns.forEach((btn, i) => {
      btn.classList.toggle('is-active', i === currentIndex);
    });

    // Update indicator pill
    if (indicator) {
      indicator.textContent = angleTitle;
    }

    // Smooth image transition
    if (imgContainer) imgContainer.classList.add('is-animating');
    
    const preloader = new Image();
    preloader.src = newSrc;
    preloader.onload = () => {
      mainImg.src = newSrc;
      if (imgContainer) imgContainer.classList.remove('is-animating');
    };
  };

  // Click & hover preview on thumbnails
  thumbBtns.forEach((btn, idx) => {
    btn.addEventListener('click', () => switchView(idx));
    btn.addEventListener('mouseenter', () => switchView(idx));
  });

  // Prev / Next arrow buttons
  if (prevBtn) {
    prevBtn.addEventListener('click', (e) => {
      e.stopPropagation();
      switchView(currentIndex - 1);
    });
  }

  if (nextBtn) {
    nextBtn.addEventListener('click', (e) => {
      e.stopPropagation();
      switchView(currentIndex + 1);
    });
  }

  // Interactive Zoom on Hover
  if (imgContainer) {
    imgContainer.addEventListener('mousemove', (e) => {
      const rect = imgContainer.getBoundingClientRect();
      const x = ((e.clientX - rect.left) / rect.width) * 100;
      const y = ((e.clientY - rect.top) / rect.height) * 100;
      mainImg.style.transformOrigin = `${x}% ${y}%`;
    });

    imgContainer.addEventListener('mouseenter', () => {
      imgContainer.classList.add('is-zoomed');
    });

    imgContainer.addEventListener('mouseleave', () => {
      imgContainer.classList.remove('is-zoomed');
      mainImg.style.transformOrigin = 'center center';
    });
  }

  // Keyboard navigation (Arrow keys)
  window.addEventListener('keydown', (e) => {
    if (e.key === 'ArrowLeft') switchView(currentIndex - 1);
    if (e.key === 'ArrowRight') switchView(currentIndex + 1);
  });

  // Copy Link Button Action
  const copyBtn = document.getElementById('nike-copy-link-btn');
  const copyToast = document.getElementById('nike-copy-toast');
  if (copyBtn) {
    copyBtn.addEventListener('click', (e) => {
      e.preventDefault();
      const shareUrl = copyBtn.getAttribute('data-share-url') || window.location.href;
      if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(shareUrl).then(showToast);
      } else {
        const tempInput = document.createElement('input');
        tempInput.value = shareUrl;
        document.body.appendChild(tempInput);
        tempInput.select();
        document.execCommand('copy');
        document.body.removeChild(tempInput);
        showToast();
      }
    });

    function showToast() {
      if (copyToast) {
        copyToast.classList.add('is-show');
        setTimeout(() => {
          copyToast.classList.remove('is-show');
        }, 2200);
      }
    }
  }
}

/**
 * Instant Page Prefetcher on Hover / Touchstart & Idle Warmup
 * Ensures that hovering or touching any internal link preloads the target page immediately
 */
function initInstantPagePrefetch() {
  const prefetched = new Set();

  const shouldPrefetch = (urlStr) => {
    if (!urlStr) return false;
    try {
      const url = new URL(urlStr, window.location.origin);
      if (url.origin !== window.location.origin) return false;
      if (url.pathname === window.location.pathname && url.search === window.location.search) return false;
      if (url.pathname.includes('/wp-admin') || url.pathname.includes('wp-login.php')) return false;
      if (/\.(pdf|zip|rar|jpg|jpeg|png|webp|gif|svg|mp4)$/i.test(url.pathname)) return false;
      return !prefetched.has(url.href);
    } catch (e) {
      return false;
    }
  };

  const prefetchUrl = (urlStr) => {
    if (!shouldPrefetch(urlStr)) return;
    const url = new URL(urlStr, window.location.origin).href;
    prefetched.add(url);

    const link = document.createElement('link');
    link.rel = 'prefetch';
    link.href = url;
    link.as = 'document';
    document.head.appendChild(link);
  };

  // 1. Ensure first 6 visible product/content images decode eagerly with zero delay
  document.querySelectorAll('.nike-prod-media img, .featured-card-media img, .nike-main-img').forEach((img, idx) => {
    if (idx < 8) {
      img.setAttribute('loading', 'eager');
      img.setAttribute('decoding', 'sync');
      img.setAttribute('fetchpriority', 'high');
    }
  });

  // 2. Prefetch immediately on hover or touchstart (plus preload product card thumbnail into browser memory)
  let hoverTimer = null;
  document.addEventListener('mouseover', (e) => {
    const anchor = e.target.closest('a[href]');
    if (!anchor || anchor.hasAttribute('data-open-quote-modal') || anchor.getAttribute('href').startsWith('#')) return;
    clearTimeout(hoverTimer);
    hoverTimer = setTimeout(() => {
      prefetchUrl(anchor.href);
      const card = anchor.closest('.nike-prod-card, .featured-card-item, .nike-related-card');
      const cardImg = card ? card.querySelector('img[src]') : null;
      if (cardImg && cardImg.src) {
        const preImg = new Image();
        preImg.src = cardImg.src;
      }
    }, 30);
  }, { passive: true });

  document.addEventListener('touchstart', (e) => {
    const anchor = e.target.closest('a[href]');
    if (!anchor || anchor.hasAttribute('data-open-quote-modal') || anchor.getAttribute('href').startsWith('#')) return;
    prefetchUrl(anchor.href);
  }, { passive: true });

  // 3. When browser is idle, silently pre-warm top navigation links
  const warmTopNav = () => {
    document.querySelectorAll('.liger-site-header a.liger-nav-link[href], .mi-pill-btn[href]').forEach((a, idx) => {
      setTimeout(() => prefetchUrl(a.href), idx * 120);
    });
  };

  if ('requestIdleCallback' in window) {
    window.requestIdleCallback(warmTopNav, { timeout: 2000 });
  } else {
    setTimeout(warmTopNav, 1200);
  }
}

/**
 * Nike-Style Carousel Engine with Circular Arrow Navigation & Real-time Progress Bar
 */
function initNikeCarouselModules() {
  const modules = document.querySelectorAll('.nike-carousel-module');
  if (!modules.length) return;

  modules.forEach(mod => {
    const track = mod.querySelector('.nike-carousel-track');
    const prevBtn = mod.querySelector('.nike-carousel-prev');
    const nextBtn = mod.querySelector('.nike-carousel-next');
    const progressBar = mod.querySelector('.nike-carousel-progress-bar');
    const progressThumb = mod.querySelector('.nike-carousel-progress-thumb');

    if (!track) return;

    // 1. Calculate and update thumb width and position
    const updateProgressAndArrows = () => {
      const scrollLeft = track.scrollLeft;
      const scrollWidth = track.scrollWidth;
      const clientWidth = track.clientWidth;
      const maxScroll = scrollWidth - clientWidth;

      // Update button disabled states
      if (prevBtn) {
        if (scrollLeft <= 4) {
          prevBtn.classList.add('is-disabled');
        } else {
          prevBtn.classList.remove('is-disabled');
        }
      }

      if (nextBtn) {
        if (maxScroll <= 4 || scrollLeft >= maxScroll - 4) {
          nextBtn.classList.add('is-disabled');
        } else {
          nextBtn.classList.remove('is-disabled');
        }
      }

      // Update bottom progress bar thumb
      if (progressThumb && progressBar) {
        if (maxScroll <= 0) {
          progressThumb.style.width = '100%';
          progressThumb.style.transform = 'translateX(0)';
          return;
        }

        const visibleRatio = Math.min(1, clientWidth / scrollWidth);
        const thumbWidthPercent = Math.max(15, visibleRatio * 100);
        progressThumb.style.width = `${thumbWidthPercent}%`;

        const scrollRatio = scrollLeft / maxScroll;
        const maxTranslate = progressBar.clientWidth - (progressBar.clientWidth * (thumbWidthPercent / 100));
        const translateX = scrollRatio * maxTranslate;
        progressThumb.style.transform = `translateX(${translateX}px)`;
      }
    };

    // 2. Click Arrow Navigation (Scroll by 1 visible page)
    let scrollTimeout = null;
    const showProgressOnInteraction = () => {
      mod.classList.add('is-scrolling');
      if (scrollTimeout) clearTimeout(scrollTimeout);
      scrollTimeout = setTimeout(() => {
        if (!isDraggingThumb) {
          mod.classList.remove('is-scrolling');
        }
      }, 1200);
    };

    const getScrollStep = () => {
      const firstSlide = track.querySelector('.nike-carousel-slide');
      if (firstSlide) {
        const slideWidth = firstSlide.offsetWidth;
        const gap = 22;
        // Scroll 2 cards or clientWidth depending on screen
        return track.clientWidth > 800 ? (slideWidth + gap) * 2 : (slideWidth + gap);
      }
      return track.clientWidth * 0.75;
    };

    if (prevBtn) {
      prevBtn.addEventListener('click', () => {
        showProgressOnInteraction();
        track.scrollBy({ left: -getScrollStep(), behavior: 'smooth' });
      });
    }

    if (nextBtn) {
      nextBtn.addEventListener('click', () => {
        showProgressOnInteraction();
        track.scrollBy({ left: getScrollStep(), behavior: 'smooth' });
      });
    }

    // 3. Click / Drag on Progress Bar to directly scroll the carousel
    let isDraggingThumb = false;
    let startX = 0;
    let startScrollLeft = 0;

    const scrollFromBarPosition = (clientX) => {
      showProgressOnInteraction();
      const rect = progressBar.getBoundingClientRect();
      const clickX = clientX - rect.left;
      const barWidth = rect.width;
      const ratio = Math.max(0, Math.min(1, clickX / barWidth));
      const maxScroll = track.scrollWidth - track.clientWidth;
      track.scrollTo({ left: ratio * maxScroll, behavior: 'smooth' });
    };

    progressBar.addEventListener('click', (e) => {
      if (e.target === progressThumb && isDraggingThumb) return;
      scrollFromBarPosition(e.clientX);
    });

    // Dragging thumb with mouse or touch
    if (progressThumb) {
      const onPointerDown = (clientX) => {
        isDraggingThumb = true;
        mod.classList.add('is-scrolling');
        startX = clientX;
        startScrollLeft = track.scrollLeft;
        progressThumb.classList.add('is-dragging');
        document.body.style.userSelect = 'none';
      };

      const onPointerMove = (clientX) => {
        if (!isDraggingThumb) return;
        const deltaX = clientX - startX;
        const barWidth = progressBar.clientWidth;
        const maxScroll = track.scrollWidth - track.clientWidth;
        const scrollDelta = (deltaX / barWidth) * track.scrollWidth;
        track.scrollLeft = startScrollLeft + scrollDelta;
      };

      const onPointerUp = () => {
        if (isDraggingThumb) {
          isDraggingThumb = false;
          progressThumb.classList.remove('is-dragging');
          document.body.style.userSelect = '';
          showProgressOnInteraction();
        }
      };

      progressThumb.addEventListener('mousedown', (e) => {
        e.stopPropagation();
        onPointerDown(e.clientX);
      });

      document.addEventListener('mousemove', (e) => onPointerMove(e.clientX));
      document.addEventListener('mouseup', onPointerUp);

      progressThumb.addEventListener('touchstart', (e) => {
        e.stopPropagation();
        onPointerDown(e.touches[0].clientX);
      }, { passive: true });

      document.addEventListener('touchmove', (e) => {
        if (isDraggingThumb) onPointerMove(e.touches[0].clientX);
      }, { passive: true });

      document.addEventListener('touchend', onPointerUp);
    }

    // 4. Scroll & Resize Listeners
    let ticking = false;
    track.addEventListener('scroll', () => {
      showProgressOnInteraction();
      if (!ticking) {
        window.requestAnimationFrame(() => {
          updateProgressAndArrows();
          ticking = false;
        });
        ticking = true;
      }
    }, { passive: true });

    window.addEventListener('resize', updateProgressAndArrows, { passive: true });

    // Initial calculation after render
    setTimeout(updateProgressAndArrows, 100);
  });
}

/**
 * Nike-Style Knowledge Hub Two-Column Archive Controller
 * Handles sticky subnav, desktop sidebar collapse, mobile slide drawer,
 * instant client-side category filtering, and real-time live search.
 */
function initNikeArticlesHub() {
  const articlesLayout = document.getElementById('nike-articles-layout');
  if (!articlesLayout) return;

  const toggleBtn = document.getElementById('nike-articles-filter-toggle');
  const toggleText = toggleBtn ? toggleBtn.querySelector('.nike-filter-btn-text') : null;
  const mobileFilterBtn = document.getElementById('nike-articles-mobile-filter-btn');
  const sidebar = document.getElementById('nike-articles-sidebar');
  const closeBtn = document.getElementById('nike-articles-sidebar-close-btn');
  const backdrop = document.getElementById('nike-articles-sidebar-backdrop');

  // Desktop Toggle: Hide / Show Sidebar
  if (toggleBtn) {
    toggleBtn.addEventListener('click', () => {
      const isHidden = articlesLayout.classList.toggle('is-sidebar-hidden');
      toggleBtn.setAttribute('aria-expanded', !isHidden);
      if (toggleText) {
        toggleText.textContent = isHidden ? 'แสดงตัวกรอง' : 'ซ่อนตัวกรอง';
      }
    });
  }

  // Mobile Drawer Open / Close
  const openMobileDrawer = () => {
    if (sidebar) sidebar.classList.add('is-open');
    if (backdrop) backdrop.classList.add('is-active');
    document.body.style.overflow = 'hidden';
  };

  const closeMobileDrawer = () => {
    if (sidebar) sidebar.classList.remove('is-open');
    if (backdrop) backdrop.classList.remove('is-active');
    document.body.style.overflow = '';
  };

  if (mobileFilterBtn) mobileFilterBtn.addEventListener('click', openMobileDrawer);
  if (closeBtn) closeBtn.addEventListener('click', closeMobileDrawer);
  if (backdrop) backdrop.addEventListener('click', closeMobileDrawer);

  // Real-Time Category Filtering & Search
  const catBtns = Array.from(document.querySelectorAll('#nike-article-cat-nav .nike-cat-btn'));
  const tagBtns = Array.from(document.querySelectorAll('#nike-article-tags .nike-article-tag-btn'));
  const cards = Array.from(document.querySelectorAll('.liger-hub-card'));
  const searchInput = document.getElementById('nike-article-search-input');
  const searchClear = document.getElementById('nike-article-search-clear');
  const liveCountEl = document.getElementById('nike-articles-live-count');
  const emptyState = document.getElementById('nike-articles-empty-state');
  const resetAllBtn = document.getElementById('nike-articles-reset-all-btn');

  let activeCategory = 'all';
  let searchQuery = '';

  function applyFilters() {
    let visibleCount = 0;
    const query = searchQuery.trim().toLowerCase();

    cards.forEach(card => {
      const cardCategory = card.getAttribute('data-category') || '';
      const cardTitle = card.getAttribute('data-title') || '';
      const cardExcerpt = card.getAttribute('data-excerpt') || '';

      const matchesCat = (activeCategory === 'all' || cardCategory === activeCategory);
      const matchesSearch = !query || cardTitle.includes(query) || cardExcerpt.includes(query);

      if (matchesCat && matchesSearch) {
        card.classList.remove('is-hidden');
        visibleCount++;
      } else {
        card.classList.add('is-hidden');
      }
    });

    if (liveCountEl) {
      liveCountEl.textContent = visibleCount;
    }

    if (emptyState) {
      emptyState.style.display = (visibleCount === 0) ? 'block' : 'none';
    }
  }

  // Category Click
  catBtns.forEach(btn => {
    btn.addEventListener('click', () => {
      catBtns.forEach(b => b.classList.remove('is-active'));
      btn.classList.add('is-active');
      activeCategory = btn.getAttribute('data-cat') || 'all';
      applyFilters();

      if (window.innerWidth <= 991) {
        closeMobileDrawer();
      }
    });
  });

  // Tag Click
  tagBtns.forEach(tagBtn => {
    tagBtn.addEventListener('click', () => {
      const tagText = tagBtn.getAttribute('data-tag') || '';
      if (searchInput) {
        searchInput.value = tagText;
        searchQuery = tagText;
        if (searchClear) searchClear.style.display = 'block';
        applyFilters();
      }
      tagBtns.forEach(t => t.classList.remove('is-active'));
      tagBtn.classList.add('is-active');

      if (window.innerWidth <= 991) {
        closeMobileDrawer();
      }
    });
  });

  // Search Input
  if (searchInput) {
    searchInput.addEventListener('input', (e) => {
      searchQuery = e.target.value;
      if (searchClear) {
        searchClear.style.display = searchQuery.length > 0 ? 'block' : 'none';
      }
      tagBtns.forEach(t => t.classList.remove('is-active'));
      applyFilters();
    });

    if (searchClear) {
      searchClear.addEventListener('click', () => {
        searchInput.value = '';
        searchQuery = '';
        searchClear.style.display = 'none';
        tagBtns.forEach(t => t.classList.remove('is-active'));
        applyFilters();
        searchInput.focus();
      });
    }
  }

  // Reset All Button
  if (resetAllBtn) {
    resetAllBtn.addEventListener('click', () => {
      activeCategory = 'all';
      searchQuery = '';
      if (searchInput) {
        searchInput.value = '';
      }
      if (searchClear) {
        searchClear.style.display = 'none';
      }
      catBtns.forEach(b => {
        if (b.getAttribute('data-cat') === 'all') {
          b.classList.add('is-active');
        } else {
          b.classList.remove('is-active');
        }
      });
      tagBtns.forEach(t => t.classList.remove('is-active'));
      applyFilters();
    });
  }
}

/**
 * Nike-Style Collapsible Sidebar Filter Accordions
 * Enables clicking on section headers to expand/collapse groups with smooth rotation
 */
function initNikeFilterAccordions() {
  document.addEventListener('click', (e) => {
    const header = e.target.closest('.nike-filter-group-header');
    if (!header) return;
    const group = header.closest('.nike-filter-group');
    if (!group) return;
    const isCollapsed = group.classList.toggle('is-collapsed');
    header.setAttribute('aria-expanded', !isCollapsed);
  });
}

/**
 * ==========================================================================
 * Nike-Style Architectural Projects Hub Filtering (v4.5.0)
 * Real-time instant 0ms category switching & multi-attribute search
 * ==========================================================================
 */
function initNikeProjectsHub() {
  const grid = document.getElementById('nikeProjectsGrid');
  if (!grid) return;

  const projectsLayout = document.getElementById('nike-projects-layout');
  const toggleBtn = document.getElementById('nike-projects-filter-toggle');
  const toggleText = toggleBtn ? toggleBtn.querySelector('.nike-filter-btn-text') : null;
  const mobileFilterBtn = document.getElementById('nike-projects-mobile-filter-btn');
  const sidebar = document.getElementById('nike-projects-sidebar');
  const closeBtn = document.getElementById('nike-projects-sidebar-close-btn');
  const backdrop = document.getElementById('nike-projects-sidebar-backdrop');

  const catBtns = document.querySelectorAll('#nike-projects-cat-nav .nike-cat-btn, .nike-project-filter-btn');
  const searchInput = document.getElementById('nikeProjectSearchInput');
  const searchClear = document.getElementById('nikeProjectSearchClear');
  const countDisplay = document.getElementById('nikeProjectsVisibleCount');
  const emptyState = document.getElementById('nikeProjectsEmpty');
  const resetBtn = document.getElementById('nikeResetProjectFilter');
  const cards = Array.from(grid.querySelectorAll('.nike-project-card'));

  // Desktop Toggle: Hide / Show Sidebar
  if (toggleBtn && projectsLayout) {
    toggleBtn.addEventListener('click', () => {
      const isHidden = projectsLayout.classList.toggle('is-sidebar-hidden');
      toggleBtn.setAttribute('aria-expanded', !isHidden);
      if (toggleText) {
        toggleText.textContent = isHidden ? 'แสดงตัวกรอง' : 'ซ่อนตัวกรอง';
      }
    });
  }

  // Mobile Drawer Open / Close
  const openMobileDrawer = () => {
    if (sidebar) sidebar.classList.add('is-open');
    if (backdrop) backdrop.classList.add('is-active');
    document.body.style.overflow = 'hidden';
  };

  const closeMobileDrawer = () => {
    if (sidebar) sidebar.classList.remove('is-open');
    if (backdrop) backdrop.classList.remove('is-active');
    document.body.style.overflow = '';
  };

  if (mobileFilterBtn) mobileFilterBtn.addEventListener('click', openMobileDrawer);
  if (closeBtn) closeBtn.addEventListener('click', closeMobileDrawer);
  if (backdrop) backdrop.addEventListener('click', closeMobileDrawer);

  let activeCategory = 'all';
  let searchQuery = '';

  const applyProjectFilters = () => {
    let visibleCount = 0;
    const q = searchQuery.trim().toLowerCase();

    cards.forEach(card => {
      const cardCat = card.getAttribute('data-cat') || '';
      const cardSearchData = (card.getAttribute('data-search') || '').toLowerCase();

      const matchesCat = (activeCategory === 'all' || cardCat === activeCategory);
      const matchesSearch = (!q || cardSearchData.includes(q));

      if (matchesCat && matchesSearch) {
        card.style.display = 'flex';
        card.style.opacity = '1';
        visibleCount++;
      } else {
        card.style.display = 'none';
        card.style.opacity = '0';
      }
    });

    if (countDisplay) {
      countDisplay.textContent = visibleCount;
    }

    if (emptyState) {
      if (visibleCount === 0) {
        emptyState.style.display = 'block';
        grid.style.display = 'none';
      } else {
        emptyState.style.display = 'none';
        grid.style.display = 'grid';
      }
    }
  };

  // Category Buttons
  catBtns.forEach(btn => {
    btn.addEventListener('click', () => {
      catBtns.forEach(b => b.classList.remove('is-active'));
      btn.classList.add('is-active');
      activeCategory = btn.getAttribute('data-cat') || 'all';
      applyProjectFilters();

      if (window.innerWidth <= 991) {
        closeMobileDrawer();
      }
    });
  });

  // Search Input
  if (searchInput) {
    let searchTimeout = null;
    searchInput.addEventListener('input', (e) => {
      clearTimeout(searchTimeout);
      searchTimeout = setTimeout(() => {
        searchQuery = e.target.value;
        if (searchClear) {
          searchClear.style.display = searchQuery ? 'block' : 'none';
        }
        applyProjectFilters();
      }, 50);
    });

    if (searchClear) {
      searchClear.addEventListener('click', () => {
        searchInput.value = '';
        searchQuery = '';
        searchClear.style.display = 'none';
        applyProjectFilters();
        searchInput.focus();
      });
    }
  }

  // Reset Button
  if (resetBtn) {
    resetBtn.addEventListener('click', () => {
      activeCategory = 'all';
      searchQuery = '';
      if (searchInput) searchInput.value = '';
      if (searchClear) searchClear.style.display = 'none';
      catBtns.forEach(b => {
        if (b.getAttribute('data-cat') === 'all') {
          b.classList.add('is-active');
        } else {
          b.classList.remove('is-active');
        }
      });
      applyProjectFilters();
    });
  }
}

/**
 * ==========================================================================
 * Modern Chic Architectural Bento Lightbox (v4.5.0)
 * High-performance fullscreen modal with touch swipe and keyboard navigation
 * ==========================================================================
 */
function initNikeProjectLightbox() {
  const gallery = document.getElementById('nikeProjectGallery');
  if (!gallery) return;

  const items = Array.from(gallery.querySelectorAll('.nike-bento-item'));
  if (items.length === 0) return;

  // Build images array
  const galleryImages = items.map((item, idx) => ({
    index: idx,
    fullUrl: item.getAttribute('data-full-url') || item.querySelector('img')?.src || '',
    caption: item.getAttribute('data-caption') || '',
    alt: item.querySelector('img')?.alt || ''
  }));

  let currentIndex = 0;

  // Create or retrieve modal
  let modal = document.getElementById('nikeLightboxModal');
  if (!modal) {
    modal = document.createElement('div');
    modal.id = 'nikeLightboxModal';
    modal.className = 'nike-lightbox-modal';
    modal.setAttribute('role', 'dialog');
    modal.setAttribute('aria-modal', 'true');
    modal.setAttribute('aria-label', 'Project Gallery Fullscreen View');
    modal.innerHTML = `
      <div class="nike-lightbox-top-bar">
        <span class="nike-lightbox-counter" id="nikeLightboxCounter">ภาพที่ 1 / ${galleryImages.length}</span>
        <button type="button" class="nike-lightbox-close-btn" id="nikeLightboxCloseBtn" aria-label="ปิดมุมมองภาพขยาย">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
      </div>
      <div class="nike-lightbox-stage" id="nikeLightboxStage">
        <button type="button" class="nike-lightbox-nav-btn nike-lightbox-prev" id="nikeLightboxPrevBtn" aria-label="ภาพก่อนหน้า">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg>
        </button>
        <div class="nike-lightbox-img-wrap">
          <img src="" alt="" class="nike-lightbox-img" id="nikeLightboxImg" />
          <div class="nike-lightbox-caption" id="nikeLightboxCaption"></div>
        </div>
        <button type="button" class="nike-lightbox-nav-btn nike-lightbox-next" id="nikeLightboxNextBtn" aria-label="ภาพถัดไป">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
        </button>
      </div>
    `;
    document.body.appendChild(modal);
  }

  const counterEl = modal.querySelector('#nikeLightboxCounter');
  const imgEl = modal.querySelector('#nikeLightboxImg');
  const captionEl = modal.querySelector('#nikeLightboxCaption');
  const closeBtn = modal.querySelector('#nikeLightboxCloseBtn');
  const prevBtn = modal.querySelector('#nikeLightboxPrevBtn');
  const nextBtn = modal.querySelector('#nikeLightboxNextBtn');
  const stageEl = modal.querySelector('#nikeLightboxStage');

  const updateLightboxView = (idx) => {
    if (idx < 0) idx = galleryImages.length - 1;
    if (idx >= galleryImages.length) idx = 0;
    currentIndex = idx;

    const current = galleryImages[currentIndex];
    imgEl.src = current.fullUrl;
    imgEl.alt = current.alt;
    captionEl.textContent = current.caption;
    counterEl.textContent = `ภาพที่ ${currentIndex + 1} / ${galleryImages.length}`;
  };

  const openLightbox = (idx) => {
    updateLightboxView(idx);
    modal.classList.add('is-open');
    document.body.style.overflow = 'hidden';
  };

  const closeLightbox = () => {
    modal.classList.remove('is-open');
    document.body.style.overflow = '';
  };

  const showNext = () => updateLightboxView(currentIndex + 1);
  const showPrev = () => updateLightboxView(currentIndex - 1);

  // Attach click listeners to bento items
  items.forEach((item, idx) => {
    item.addEventListener('click', () => openLightbox(idx));
  });

  closeBtn?.addEventListener('click', closeLightbox);
  nextBtn?.addEventListener('click', showNext);
  prevBtn?.addEventListener('click', showPrev);

  // Click on dark backdrop to close
  modal.addEventListener('click', (e) => {
    if (e.target === stageEl || e.target === modal) {
      closeLightbox();
    }
  });

  // Keyboard navigation
  document.addEventListener('keydown', (e) => {
    if (!modal.classList.contains('is-open')) return;
    if (e.key === 'Escape') closeLightbox();
    if (e.key === 'ArrowRight') showNext();
    if (e.key === 'ArrowLeft') showPrev();
  });

  // Touch Swipe on mobile
  let touchStartX = 0;
  let touchEndX = 0;
  stageEl.addEventListener('touchstart', (e) => {
    touchStartX = e.changedTouches[0].screenX;
  }, { passive: true });

  stageEl.addEventListener('touchend', (e) => {
    touchEndX = e.changedTouches[0].screenX;
    const diff = touchEndX - touchStartX;
    if (diff < -50) showNext();
    if (diff > 50) showPrev();
  }, { passive: true });
}

/**
 * ==========================================================================
 * LIGER 3D INTERACTIVE FLIPBOOK VIEWER (v5.5.9)
 * 100% Self-Hosted StPageFlip Engine matching composite/index.html
 * With URL query param synchronization (?catalog=...) and history navigation
 * ==========================================================================
 */
function initLigerFlipbook() {
  const modal = document.getElementById('ligerFlipModal');
  if (!modal) return;

  const backdrop = document.getElementById('ligerFlipBackdrop');
  const flipIframe = document.getElementById('ligerFlipIframe');

  const getSlugFromTrigger = (trigger, ebookUrl) => {
    const card = trigger.closest('.liger-cat-card');
    if (card && card.getAttribute('data-catalog-slug')) {
      return card.getAttribute('data-catalog-slug');
    }
    if (trigger.getAttribute('data-catalog-slug')) {
      return trigger.getAttribute('data-catalog-slug');
    }
    if (ebookUrl && ebookUrl.includes('tigon')) return 'tigon';
    return 'sky-high-acp';
  };

  const openFlipbook = (ebookUrl, slug, updateUrl = true) => {
    if (!flipIframe) return;
    flipIframe.src = ebookUrl || '/ligermedia/ebook/composite/index.html';
    modal.classList.add('is-open');
    modal.setAttribute('aria-hidden', 'false');
    document.body.style.overflow = 'hidden';

    if (updateUrl) {
      const activeSlug = slug || (ebookUrl && ebookUrl.includes('tigon') ? 'tigon' : 'sky-high-acp');
      const url = new URL(window.location.href);
      url.searchParams.set('flipbook', activeSlug);
      window.history.pushState({ flipbook: activeSlug, ebookUrl }, '', url.toString());
    }
  };

  const closeFlipbook = (updateUrl = true) => {
    modal.classList.remove('is-open');
    modal.setAttribute('aria-hidden', 'true');
    document.body.style.overflow = '';
    if (flipIframe) flipIframe.src = '';

    if (updateUrl) {
      const url = new URL(window.location.href);
      if (url.searchParams.has('flipbook') || url.searchParams.has('book') || url.searchParams.has('catalog')) {
        url.searchParams.delete('flipbook');
        url.searchParams.delete('book');
        url.searchParams.delete('catalog');
        const cleanUrl = url.pathname + (url.search ? url.search : '') + url.hash;
        window.history.pushState({}, '', cleanUrl);
      }
    }
  };

  // Bind Open Triggers on cards & cover wraps - Open in new dedicated tab
  document.addEventListener('click', (e) => {
    const trigger = e.target.closest('[data-open-flipbook="true"]');
    if (!trigger) return;
    e.preventDefault();
    const ebookUrl = trigger.getAttribute('data-ebook-url') || '/ligermedia/ebook/composite/index.html';
    window.open(ebookUrl, '_blank', 'noopener,noreferrer');
  });

  // Listen to close event from inside the ebook reader iframe
  window.addEventListener('message', (e) => {
    if (e.data === 'closeLigerFlipbook') {
      closeFlipbook(true);
    }
  });

  // Close on backdrop click
  if (backdrop) backdrop.addEventListener('click', () => closeFlipbook(true));

  // Keyboard navigation Esc
  document.addEventListener('keydown', (e) => {
    if (!modal.classList.contains('is-open')) return;
    if (e.key === 'Escape') closeFlipbook(true);
  });

  // Browser back/forward navigation support
  window.addEventListener('popstate', () => {
    const params = new URLSearchParams(window.location.search);
    const slug = params.get('flipbook') || params.get('book') || params.get('catalog');
    if (slug) {
      const targetCard = document.querySelector(`.liger-cat-card[data-catalog-slug="${slug}"], [data-catalog-slug="${slug}"]`);
      let targetUrl = '/ligermedia/ebook/composite/index.html';
      if (targetCard) {
        const wrap = targetCard.querySelector('[data-ebook-url]');
        if (wrap) targetUrl = wrap.getAttribute('data-ebook-url');
      } else if (slug === 'tigon') {
        targetUrl = '/ligermedia/ebook/tigon/index.html';
      }
      openFlipbook(targetUrl, slug, false);
    } else if (modal.classList.contains('is-open')) {
      closeFlipbook(false);
    }
  });

  // Deep-link check on initial page load
  const initialParams = new URLSearchParams(window.location.search);
  const initialSlug = initialParams.get('flipbook') || initialParams.get('book') || initialParams.get('catalog');
  if (initialSlug) {
    const targetCard = document.querySelector(`.liger-cat-card[data-catalog-slug="${initialSlug}"], [data-catalog-slug="${initialSlug}"]`);
    let targetUrl = '/ligermedia/ebook/composite/index.html';
    if (targetCard) {
      const wrap = targetCard.querySelector('[data-ebook-url]');
      if (wrap) targetUrl = wrap.getAttribute('data-ebook-url');
    } else if (initialSlug === 'tigon') {
      targetUrl = '/ligermedia/ebook/tigon/index.html';
    }
    window.setTimeout(() => {
      openFlipbook(targetUrl, initialSlug, false);
    }, 180);
  }
}








