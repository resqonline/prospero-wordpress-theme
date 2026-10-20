/**
 * Main theme functionality
 *
 * @package Prospero
 * @since 1.0.0
 */

(function() {
	'use strict';

	/**
	 * Mobile menu - slide-in panel with hierarchical navigation
	 */
	function initMobileMenu() {
		const menuToggle = document.querySelector('.mobile-menu-toggle');
		const menuPanel = document.getElementById('mobile-menu-panel');
		const menuOverlay = document.querySelector('.mobile-menu-overlay');
		const closeButton = document.querySelector('.mobile-menu-close');

		if (!menuToggle || !menuPanel) return;

		let openSubmenus = [];

		/**
		 * Open the mobile menu panel
		 */
		function openMenu() {
			menuToggle.setAttribute('aria-expanded', 'true');
			menuPanel.classList.add('is-open');
			if (menuOverlay) {
				menuOverlay.classList.add('is-visible');
			}
			document.body.style.overflow = 'hidden';

			// Focus the close button
			if (closeButton) {
				closeButton.focus();
			}
		}

		/**
		 * Close the mobile menu panel
		 */
		function closeMenu() {
			menuToggle.setAttribute('aria-expanded', 'false');
			menuPanel.classList.remove('is-open');
			if (menuOverlay) {
				menuOverlay.classList.remove('is-visible');
			}
			document.body.style.overflow = '';

			// Close all open submenus
			openSubmenus.forEach(panel => {
				panel.classList.remove('is-open');
			});
			openSubmenus = [];

			// Return focus to toggle button
			menuToggle.focus();
		}

		/**
		 * Open a submenu panel
		 */
		function openSubmenu(submenuId) {
			const submenuPanel = menuPanel.querySelector('[data-submenu-id="' + submenuId + '"]');
			if (!submenuPanel) return;

			submenuPanel.classList.add('is-open');
			submenuPanel.setAttribute('aria-hidden', 'false');
			openSubmenus.push(submenuPanel);

			const toggle = menuPanel.querySelector('.submenu-toggle[data-submenu="' + submenuId + '"]');
			if (toggle) {
				toggle.setAttribute('aria-expanded', 'true');
			}

			const backButton = submenuPanel.querySelector('.submenu-back');
			if (backButton) {
				backButton.focus();
			}
		}

		/**
		 * Close the current submenu and go back
		 */
		function closeSubmenu() {
			const lastSubmenu = openSubmenus.pop();
			if (!lastSubmenu) return;

			lastSubmenu.classList.remove('is-open');
			lastSubmenu.setAttribute('aria-hidden', 'true');

			const submenuId = lastSubmenu.getAttribute('data-submenu-id');
			const toggle = menuPanel.querySelector('.submenu-toggle[data-submenu="' + submenuId + '"]');
			if (toggle) {
				toggle.setAttribute('aria-expanded', 'false');
				toggle.focus();
			}
		}

		// Toggle button click
		menuToggle.addEventListener('click', function() {
			const isExpanded = this.getAttribute('aria-expanded') === 'true';
			if (isExpanded) {
				closeMenu();
			} else {
				openMenu();
			}
		});

		// Close button click
		if (closeButton) {
			closeButton.addEventListener('click', closeMenu);
		}

		// Overlay click closes menu
		if (menuOverlay) {
			menuOverlay.addEventListener('click', closeMenu);
		}

		// Submenu toggle clicks
		const submenuToggles = menuPanel.querySelectorAll('.submenu-toggle');
		submenuToggles.forEach(function(toggle) {
			toggle.addEventListener('click', function() {
				const submenuId = this.getAttribute('data-submenu');
				if (submenuId) {
					openSubmenu(submenuId);
				}
			});
		});

		// Back button clicks
		const backButtons = menuPanel.querySelectorAll('.submenu-back');
		backButtons.forEach(function(button) {
			button.addEventListener('click', closeSubmenu);
		});

		// Escape key closes menu
		document.addEventListener('keydown', function(e) {
			if (e.key === 'Escape' && menuPanel.classList.contains('is-open')) {
				if (openSubmenus.length > 0) {
					// Close current submenu first
					closeSubmenu();
				} else {
					// Close the whole menu
					closeMenu();
				}
			}
		});

		// Focus trap within menu panel
		menuPanel.addEventListener('keydown', function(e) {
			if (e.key !== 'Tab') return;

			const focusable = menuPanel.querySelectorAll(
				'button:not([hidden]), [href]:not([hidden]), input:not([hidden]), ' +
				'select:not([hidden]), textarea:not([hidden]), [tabindex]:not([tabindex="-1"]):not([hidden])'
			);
			const visibleFocusable = Array.from(focusable).filter(function(el) {
				return el.offsetParent !== null;
			});

			if (visibleFocusable.length === 0) return;

			const firstFocusable = visibleFocusable[0];
			const lastFocusable = visibleFocusable[visibleFocusable.length - 1];

			if (e.shiftKey && document.activeElement === firstFocusable) {
				e.preventDefault();
				lastFocusable.focus();
			} else if (!e.shiftKey && document.activeElement === lastFocusable) {
				e.preventDefault();
				firstFocusable.focus();
			}
		});

		// Close the menu when a link is clicked. Same-page anchor links
		// are intercepted so the scroll happens AFTER the panel slides
		// out (otherwise the motion is hidden behind the overlay). The
		// pure `#` placeholder on submenu parent items is ignored so it
		// keeps opening the child panel.
		const menuLinks = menuPanel.querySelectorAll('a');
		menuLinks.forEach(function(link) {
			link.addEventListener('click', function(e) {
				const menuItem = this.closest('li');
				const hasSubmenu = menuItem && menuItem.querySelector('.submenu-toggle');
				const href = this.getAttribute('href') || '';

				if (hasSubmenu && href === '#') {
					return;
				}

				const target = resolveSamePageAnchorTarget(this);
				if (target) {
					e.preventDefault();
					closeMenu();
					// 220 ms ≈ the .mobile-menu-panel slide-out transition.
					scrollToAnchor(target, 220);
					return;
				}

				setTimeout(closeMenu, 100);
			});
		});
	}

	/**
	 * Escape an id/name for use in a CSS attribute selector, with a
	 * fallback for browsers without `CSS.escape()`.
	 */
	function escapeSelectorValue(value) {
		if (window.CSS && typeof window.CSS.escape === 'function') {
			return window.CSS.escape(value);
		}

		return value.replace(/["\\]/g, '\\$&');
	}

	/**
	 * Resolve an anchor's `href` to a same-page target element, or
	 * `null` if the link points elsewhere or the target doesn't exist.
	 * Matches by `id` first, then by `a[name="..."]` for legacy anchors.
	 */
	function resolveSamePageAnchorTarget(link) {
		let url;

		try {
			url = new URL(link.href, window.location.href);
		} catch (err) {
			return null;
		}

		if (url.origin !== window.location.origin ||
			url.pathname !== window.location.pathname ||
			url.search !== window.location.search) {
			return null;
		}

		if (!url.hash || url.hash.length <= 1) {
			return null;
		}

		const targetId = decodeURIComponent(url.hash.slice(1));

		return document.getElementById(targetId) ||
			document.querySelector('a[name="' + escapeSelectorValue(targetId) + '"]');
	}

	/**
	 * Smooth-scroll to an anchor target, offsetting for the sticky
	 * header + top bar, moving keyboard focus to the target, and
	 * stripping the hash from the URL. Honors `prefers-reduced-motion`.
	 *
	 * @param {Element} target Element to scroll to.
	 * @param {number}  [delay=0] Milliseconds to wait before scrolling.
	 *                            Used by the hamburger handler to wait
	 *                            for the panel to close.
	 */
	function scrollToAnchor(target, delay) {
		const run = function () {
			const header = document.getElementById('masthead');
			const targetTop = target.getBoundingClientRect().top +
				window.pageYOffset;
			let chromeOffset = 0;
			const reduceMotion = window.matchMedia &&
				window.matchMedia('(prefers-reduced-motion: reduce)').matches;

			// Only reserve room for the sticky chrome when it's actually
			// going to be visible at the destination.
			if (header && header.classList.contains('sticky-header')) {
				const topBarVar = getComputedStyle(document.documentElement)
					.getPropertyValue('--prospero-top-bar-height') || '0';
				const topBarHeight = parseInt(topBarVar, 10) || 0;
				const stickyThreshold = header.offsetTop + header.offsetHeight;
				const willUseStickyChrome = header.classList.contains('is-sticky') ||
					document.body.classList.contains('header-is-stuck') ||
					targetTop > stickyThreshold;

				if (willUseStickyChrome) {
					chromeOffset = header.offsetHeight + topBarHeight;
				}
			}

			// Breathing room above the target (matches --spacing-md).
			const gap = 32;
			const scrollY = Math.max(0, targetTop - chromeOffset - gap);

			window.scrollTo({
				top: scrollY,
				behavior: reduceMotion ? 'auto' : 'smooth'
			});

			// Move focus so screen reader / keyboard users land at the
			// target. Temporary `tabindex="-1"` is removed on next blur.
			const hadTabindex = target.hasAttribute('tabindex');
			if (!hadTabindex) {
				target.setAttribute('tabindex', '-1');
			}
			target.focus({ preventScroll: true });
			if (!hadTabindex) {
				target.addEventListener('blur', function onBlur() {
					target.removeAttribute('tabindex');
					target.removeEventListener('blur', onBlur);
				}, { once: true });
			}

			// Strip the hash from the URL without adding a history entry.
			if (window.history && typeof window.history.replaceState === 'function') {
				window.history.replaceState(
					window.history.state,
					'',
					window.location.pathname + window.location.search
				);
			}
		};

		if (typeof delay === 'number' && delay > 0) {
			setTimeout(run, delay);
		} else {
			run();
		}
	}

	/**
	 * Global same-page anchor link handler.
	 *
	 * Delegated at the document level so it catches `<a href="#...">`
	 * links anywhere - content, blocks, widgets, footer, desktop nav.
	 * Skips hamburger panel links (handled separately with a close-
	 * first delay), modifier-key clicks, non-primary mouse buttons,
	 * `target="_blank"` / `download` links, and any link opting out
	 * via `data-no-smooth-scroll`.
	 */
	function initAnchorScroll() {
		document.addEventListener('click', function (e) {
			if (e.defaultPrevented) return;
			if (e.button !== 0) return;
			if (e.metaKey || e.ctrlKey || e.shiftKey || e.altKey) return;

			const link = e.target.closest('a');

			if (!link) return;
			if (link.closest('.mobile-menu-panel')) return;
			if (link.hasAttribute('download')) return;
			if (link.hasAttribute('data-no-smooth-scroll')) return;
			if (link.target && link.target !== '' && link.target !== '_self') return;

			const target = resolveSamePageAnchorTarget(link);

			if (!target) return;

			e.preventDefault();
			scrollToAnchor(target);
		});

		// Cross-page / reload arrival with a hash: use the same sticky-
		// aware scroller instead of the browser's un-offset jump.
		if (window.location.hash && window.location.hash.length > 1) {
			const hashId = decodeURIComponent(window.location.hash.slice(1));
			const hashTarget = document.getElementById(hashId) ||
				document.querySelector('a[name="' + escapeSelectorValue(hashId) + '"]');

			if (hashTarget) {
				if ('scrollRestoration' in history) {
					history.scrollRestoration = 'manual';
				}
				window.scrollTo(0, 0);
				scrollToAnchor(hashTarget, 100);
			}
		}
	}

	/**
	 * Header search overlay
	 */
	function initHeaderSearch() {
		const searchToggle = document.getElementById('header-search-toggle');
		const searchOverlay = document.getElementById('search-overlay');
		
		if (!searchToggle || !searchOverlay) return;
		
		const closeButton = searchOverlay.querySelector('.search-overlay-close');
		const searchInput = searchOverlay.querySelector('.search-field');
		
		/**
		 * Open search overlay
		 */
		function openSearch() {
			searchToggle.setAttribute('aria-expanded', 'true');
			searchOverlay.classList.add('is-open');
			searchOverlay.setAttribute('aria-hidden', 'false');
			document.body.style.overflow = 'hidden';
			
			// Focus the search input
			if (searchInput) {
				setTimeout(function() {
					searchInput.focus();
				}, 100);
			}
		}
		
		/**
		 * Close search overlay
		 */
		function closeSearch() {
			searchToggle.setAttribute('aria-expanded', 'false');
			searchOverlay.classList.remove('is-open');
			searchOverlay.setAttribute('aria-hidden', 'true');
			document.body.style.overflow = '';
			
			// Return focus to toggle
			searchToggle.focus();
		}
		
		// Toggle button click
		searchToggle.addEventListener('click', function() {
			const isExpanded = this.getAttribute('aria-expanded') === 'true';
			if (isExpanded) {
				closeSearch();
			} else {
				openSearch();
			}
		});
		
		// Close button click
		if (closeButton) {
			closeButton.addEventListener('click', closeSearch);
		}
		
		// Click outside to close
		searchOverlay.addEventListener('click', function(e) {
			if (e.target === searchOverlay) {
				closeSearch();
			}
		});
		
		// Escape key closes overlay
		document.addEventListener('keydown', function(e) {
			if (e.key === 'Escape' && searchOverlay.classList.contains('is-open')) {
				closeSearch();
			}
		});
	}

	/**
	 * Sticky header functionality
	 *
	 * The threshold is the header's own height PLUS the distance from the
	 * top of the document to the header's top edge. That distance is 0
	 * when no top bar is present and equal to the top bar's height when
	 * one is rendered, so the sticky activation preserves the same
	 * "re-appears after being fully scrolled away" feel in both layouts.
	 *
	 * When a top bar is present, its height is also exposed as the
	 * `--prospero-top-bar-height` CSS custom property on <html>. CSS uses
	 * it to pin the top bar alongside the sticky header so the contact
	 * info stays reachable while the user scrolls, and to reserve the
	 * combined height on body.padding-top so content doesn't slip under
	 * either bar.
	 */
	function initStickyHeader() {
		const header = document.getElementById('masthead');
		if (!header || !header.classList.contains('sticky-header')) {
			return;
		}

		const topBar = document.querySelector('.site-top-bar');

		function measureTopBarHeight() {
			const h = topBar ? topBar.offsetHeight : 0;
			document.documentElement.style.setProperty('--prospero-top-bar-height', h + 'px');
			return h;
		}

		measureTopBarHeight();
		let threshold = header.offsetTop + header.offsetHeight;

		// Recalculate on resize because the top bar height can change
		// when the viewport crosses the mobile breakpoint.
		window.addEventListener('resize', function () {
			// Temporarily undo sticky state so offsetTop reflects natural flow.
			const wasSticky = header.classList.contains('is-sticky');
			if (wasSticky) {
				header.classList.remove('is-sticky');
				document.body.classList.remove('header-is-stuck');
			}
			measureTopBarHeight();
			threshold = header.offsetTop + header.offsetHeight;
			if (wasSticky && window.pageYOffset > threshold) {
				header.classList.add('is-sticky');
				document.body.classList.add('header-is-stuck');
			}
		});

		window.addEventListener('scroll', function () {
			if (window.pageYOffset > threshold) {
				header.classList.add('is-sticky');
				document.body.classList.add('header-is-stuck');
			} else {
				header.classList.remove('is-sticky');
				document.body.classList.remove('header-is-stuck');
			}
		});
	}

	/**
	 * Keyboard navigation for desktop menu accessibility
	 */
	function initKeyboardNav() {
		const menu = document.querySelector('.desktop-menu ul');
		if (!menu) return;

		const menuItems = menu.querySelectorAll(':scope > li');

		menuItems.forEach(item => {
			const link = item.querySelector('a');
			const submenu = item.querySelector('.sub-menu');

			if (submenu && link) {
				// Add arrow key navigation
				link.addEventListener('keydown', function(e) {
					if (e.key === 'ArrowDown') {
						e.preventDefault();
						const firstSubLink = submenu.querySelector('a');
						if (firstSubLink) {
							firstSubLink.focus();
						}
					}
				});

				// Submenu navigation
				const subLinks = submenu.querySelectorAll('a');
				subLinks.forEach((subLink, index) => {
					subLink.addEventListener('keydown', function(e) {
						if (e.key === 'ArrowDown') {
							e.preventDefault();
							if (index < subLinks.length - 1) {
								subLinks[index + 1].focus();
							}
						} else if (e.key === 'ArrowUp') {
							e.preventDefault();
							if (index > 0) {
								subLinks[index - 1].focus();
							} else {
								link.focus();
							}
						} else if (e.key === 'Escape') {
							e.preventDefault();
							link.focus();
						}
					});
				});
			}
		});
	}

	/**
	 * Team lightbox functionality
	 */
	function initTeamLightbox() {
		const lightboxContainers = document.querySelectorAll('.prospero-team-lightbox');
		if (!lightboxContainers.length) return;

		// Create overlay element
		const overlay = document.createElement('div');
		overlay.className = 'prospero-team-lightbox-overlay';
		overlay.setAttribute('hidden', '');
		overlay.setAttribute('role', 'dialog');
		overlay.setAttribute('aria-modal', 'true');
		overlay.setAttribute('aria-label', prosperoI18n ? prosperoI18n.teamMemberDetails : 'Team member details');
		document.body.appendChild(overlay);

		// Track currently open lightbox for focus management
		let currentTrigger = null;

		function openLightbox(teamItem) {
			const content = teamItem.querySelector('.team-lightbox-content');
			if (!content) return;

			// Clone content for the lightbox
			const contentClone = content.querySelector('.team-lightbox-inner').cloneNode(true);

			// Get image style class from parent
			const teamContainer = teamItem.closest('.prospero-team');
			const imageStyleClass = teamContainer.className.match(/prospero-team-image-\w+/);

			// Build modal
			overlay.innerHTML = '';
			const modal = document.createElement('div');
			modal.className = 'prospero-team-lightbox-modal';
			if (imageStyleClass) {
				modal.classList.add(imageStyleClass[0]);
			}

			// Add close button
			const closeBtn = document.createElement('button');
			closeBtn.className = 'prospero-team-lightbox-close';
			closeBtn.setAttribute('type', 'button');
			closeBtn.setAttribute('aria-label', prosperoI18n ? prosperoI18n.closeDialog : 'Close dialog');
			closeBtn.innerHTML = '&times;';
			closeBtn.addEventListener('click', closeLightbox);

			modal.appendChild(closeBtn);
			modal.appendChild(contentClone);
			overlay.appendChild(modal);

			// Show overlay
			overlay.removeAttribute('hidden');
			document.body.style.overflow = 'hidden';

			// Focus the close button
			closeBtn.focus();
		}

		function closeLightbox() {
			overlay.setAttribute('hidden', '');
			document.body.style.overflow = '';

			// Return focus to trigger
			if (currentTrigger) {
				currentTrigger.focus();
				currentTrigger = null;
			}
		}

		// Handle clicks on triggers
		lightboxContainers.forEach(container => {
			container.addEventListener('click', function(e) {
				const trigger = e.target.closest('.team-lightbox-trigger');
				if (!trigger) return;

				e.preventDefault();
				currentTrigger = trigger;
				const teamItem = trigger.closest('.team-item');
				openLightbox(teamItem);
			});
		});

		// Close on overlay click
		overlay.addEventListener('click', function(e) {
			if (e.target === overlay) {
				closeLightbox();
			}
		});

		// Close on Escape key
		document.addEventListener('keydown', function(e) {
			if (e.key === 'Escape' && !overlay.hasAttribute('hidden')) {
				closeLightbox();
			}
		});

		// Trap focus in lightbox
		overlay.addEventListener('keydown', function(e) {
			if (e.key !== 'Tab') return;

			const focusable = overlay.querySelectorAll('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])');
			const firstFocusable = focusable[0];
			const lastFocusable = focusable[focusable.length - 1];

			if (e.shiftKey && document.activeElement === firstFocusable) {
				e.preventDefault();
				lastFocusable.focus();
			} else if (!e.shiftKey && document.activeElement === lastFocusable) {
				e.preventDefault();
				firstFocusable.focus();
			}
		});
	}

	/**
	 * Back to top button
	 */
	function initBackToTop() {
		const button = document.querySelector('.back-to-top');
		if (!button) return;

		const scrollThreshold = 300;

		// Show/hide button based on scroll position
		function toggleButton() {
			if (window.pageYOffset > scrollThreshold) {
				button.removeAttribute('hidden');
				button.classList.add('is-visible');
			} else {
				button.classList.remove('is-visible');
				// Delay hiding to allow fade out animation
				setTimeout(() => {
					if (!button.classList.contains('is-visible')) {
						button.setAttribute('hidden', '');
					}
				}, 300);
			}
		}

		// Scroll to top on click
		button.addEventListener('click', function() {
			window.scrollTo({
				top: 0,
				behavior: 'smooth'
			});
		});

		// Listen for scroll with throttling
		let ticking = false;
		window.addEventListener('scroll', function() {
			if (!ticking) {
				window.requestAnimationFrame(function() {
					toggleButton();
					ticking = false;
				});
				ticking = true;
			}
		});

		// Initial check
		toggleButton();
	}

	/**
	 * Initialize all functionality
	 */
	function init() {
		initMobileMenu();
		initAnchorScroll();
		initHeaderSearch();
		initStickyHeader();
		initKeyboardNav();
		initTeamLightbox();
		initBackToTop();
	}

	// Initialize when DOM is ready
	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', init);
	} else {
		init();
	}
})();
