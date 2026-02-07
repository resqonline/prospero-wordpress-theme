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
			const submenuPanel = document.querySelector('[data-submenu-id="' + submenuId + '"]');
			if (submenuPanel) {
				submenuPanel.classList.add('is-open');
				openSubmenus.push(submenuPanel);

				// Focus the back button
				const backButton = submenuPanel.querySelector('.submenu-back');
				if (backButton) {
					backButton.focus();
				}
			}
		}

		/**
		 * Close the current submenu and go back
		 */
		function closeSubmenu() {
			const lastSubmenu = openSubmenus.pop();
			if (lastSubmenu) {
				lastSubmenu.classList.remove('is-open');

				// Focus the toggle that opened this submenu
				const submenuId = lastSubmenu.getAttribute('data-submenu-id');
				const toggle = document.querySelector('[data-submenu="' + submenuId + '"]');
				if (toggle) {
					toggle.focus();
				}
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

		// Close menu when clicking a link (unless it has a submenu)
		const menuLinks = menuPanel.querySelectorAll('a');
		menuLinks.forEach(function(link) {
			link.addEventListener('click', function() {
				// Check if this link is in a menu item with a submenu toggle
				const menuItem = this.closest('li');
				const hasSubmenu = menuItem && menuItem.querySelector('.submenu-toggle');

				// If it's a regular link (not parent of submenu), close menu
				if (!hasSubmenu || this.getAttribute('href') !== '#') {
					setTimeout(closeMenu, 100);
				}
			});
		});
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
	 */
	function initStickyHeader() {
		const header = document.getElementById('masthead');
		if (!header || !header.classList.contains('sticky-header')) {
			return;
		}

		let lastScroll = 0;
		const headerHeight = header.offsetHeight;

		window.addEventListener('scroll', function() {
			const currentScroll = window.pageYOffset;

			if (currentScroll > headerHeight) {
				header.classList.add('is-sticky');
				document.body.classList.add('header-is-stuck');
			} else {
				header.classList.remove('is-sticky');
				document.body.classList.remove('header-is-stuck');
			}

			lastScroll = currentScroll;
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
