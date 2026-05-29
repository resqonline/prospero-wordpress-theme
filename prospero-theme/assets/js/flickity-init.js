/**
 * Flickity Slider Initialization
 *
 * @package Prospero
 * @since 1.0.0
 */

(function() {
	'use strict';

	/**
	 * Calculate cell width based on columns and apply to slider items
	 * @param {Element} slider - The slider container element
	 * @param {number} columns - Number of items to show per view
	 * @param {string} sliderType - Type of slider (testimonials, partners, team, etc.)
	 */
	function applyCellWidths(slider, columns, sliderType) {
		let itemSelector = '';
		let gap = 16; // Gap between items in pixels
		
		// Determine the item selector based on slider type
		switch(sliderType) {
			case 'testimonials':
				itemSelector = '.testimonial-item';
				gap = 20;
				break;
			case 'partners':
				itemSelector = '.partner-item';
				gap = 16;
				break;
			case 'team':
				itemSelector = '.team-item';
				gap = 20;
				break;
			case 'projects':
				itemSelector = '.project-item';
				gap = 20;
				break;
			default:
				return;
		}
		
		const items = slider.querySelectorAll(itemSelector);
		if (items.length === 0) return;
		
		// Calculate width: (100% / columns) minus gaps
		// Total gap space = gap * (columns - 1) for gaps between items
		// Each item width = (100% - total gap space) / columns
		const totalGapSpace = gap * (columns - 1);
		const itemWidth = 'calc((100% - ' + totalGapSpace + 'px) / ' + columns + ')';
		
		items.forEach(function(item, index) {
			item.style.width = itemWidth;
			// Add margin-right to all except last visible item
			item.style.marginRight = gap + 'px';
			item.style.marginLeft = '0';
		});
	}

	/**
	 * Initialize a WP Gallery / classic [gallery] as a Flickity slider.
	 *
	 * Handles three gallery formats:
	 * - Modern Gutenberg (5.9+) `.wp-block-gallery.has-nested-images` with
	 *   direct `.wp-block-image` children (cells are the figures).
	 * - Legacy Gutenberg gallery with an inner `.blocks-gallery-grid`
	 *   `<ul>` (cells are the `.blocks-gallery-item` `<li>`).
	 * - Classic `[gallery]` shortcode `.gallery` with `.gallery-item`
	 *   figures.
	 *
	 * The columns count comes from the wrapper's `columns-N` /
	 * `gallery-columns-N` class, defaulting to 3 if neither is present.
	 * Flickity is initialized on the inner cells container, not the
	 * outer wrapper, so CSS targeting the wrapper class (alignment,
	 * spacing, etc.) keeps working.
	 *
	 * Autoplay is enabled when the wrapper carries either the
	 * `is-style-slider-autoplay` block style (registered for `core/gallery`
	 * in `inc/gutenberg.php`) or the `prospero-gallery-autoplay` additive
	 * class (the manual / classic-gallery opt-in). Default interval is
	 * 5000ms; mouse hover pauses and resumes the player so visitors can
	 * read captions / focus on a slide without it advancing.
	 *
	 * @param {Element} wrapper - The gallery's outer wrapper element.
	 */
	function initGallerySlider(wrapper) {
		// Skip if already initialized (defensive against double-init when a
		// page swaps gallery markup via PJAX / preview reloads).
		if (wrapper.classList.contains('prospero-gallery-slider-init')) {
			return;
		}

		let cellsContainer = null;
		let cellSelector = null;

		// Detect the gallery format and pick the appropriate cell container.
		if (wrapper.classList.contains('wp-block-gallery')) {
			const legacyList = wrapper.querySelector(':scope > .blocks-gallery-grid');
			if (legacyList) {
				// Legacy (<5.9) Gutenberg gallery: <ul> wrapping <li> items.
				cellsContainer = legacyList;
				cellSelector = ':scope > .blocks-gallery-item';
			} else {
				// Modern (5.9+) Gutenberg gallery: figures as direct children.
				cellsContainer = wrapper;
				cellSelector = ':scope > .wp-block-image';
			}
		} else if (wrapper.classList.contains('gallery')) {
			// Classic [gallery] shortcode.
			cellsContainer = wrapper;
			cellSelector = ':scope > .gallery-item';
		} else {
			return;
		}

		const cells = cellsContainer.querySelectorAll(cellSelector);
		if (cells.length === 0) {
			return;
		}

		// Detect the columns count from the wrapper's class list. The
		// `columns-N` (Gutenberg) and `gallery-columns-N` (classic) prefixes
		// both expose a numeric value; fall back to 3 when neither is set
		// (e.g. when the user picks the dynamic "columns-default" option).
		let columns = 3;
		const classList = Array.prototype.slice.call(wrapper.classList);
		for (let i = 0; i < classList.length; i++) {
			const gutenbergMatch = classList[i].match(/^columns-(\d+)$/);
			if (gutenbergMatch) {
				columns = parseInt(gutenbergMatch[1], 10);
				break;
			}
			const classicMatch = classList[i].match(/^gallery-columns-(\d+)$/);
			if (classicMatch) {
				columns = parseInt(classicMatch[1], 10);
				break;
			}
		}
		if (columns < 1) {
			columns = 1;
		}

		const needsSlider = cells.length > columns;
		const gap = 16;
		const totalGapSpace = gap * (columns - 1);
		const cellWidth = 'calc((100% - ' + totalGapSpace + 'px) / ' + columns + ')';

		// Autoplay opt-in via either the dedicated block style or the
		// additive class. Disabled when there aren't enough cells to
		// actually rotate - no point cycling a single static slide.
		const wantsAutoplay = needsSlider && (
			wrapper.classList.contains('is-style-slider-autoplay') ||
			wrapper.classList.contains('prospero-gallery-autoplay')
		);
		const autoPlayInterval = wantsAutoplay ? 5000 : false;

		function applyWidths() {
			cells.forEach(function(cell) {
				cell.style.width = cellWidth;
				cell.style.marginRight = gap + 'px';
				cell.style.marginLeft = '0';
			});
		}
		applyWidths();

		try {
			const flkty = new Flickity(cellsContainer, {
				cellAlign: 'left',
				contain: true,
				wrapAround: needsSlider,
				autoPlay: autoPlayInterval,
				prevNextButtons: needsSlider,
				pageDots: needsSlider,
				accessibility: true,
				setGallerySize: true,
				imagesLoaded: true,
				percentPosition: true,
				draggable: needsSlider
			});

			wrapper.classList.add('prospero-gallery-slider-init');

			// Keyboard navigation
			cellsContainer.addEventListener('keydown', function(event) {
				if (event.keyCode === 37) {
					event.preventDefault();
					flkty.previous();
				} else if (event.keyCode === 39) {
					event.preventDefault();
					flkty.next();
				}
			});

			// Pause autoplay while the visitor is interacting with the
			// slider, resume when they move away. Matches the testimonials /
			// partners listing-slider behaviour above.
			if (autoPlayInterval) {
				cellsContainer.addEventListener('mouseenter', function() {
					flkty.pausePlayer();
				});
				cellsContainer.addEventListener('mouseleave', function() {
					flkty.unpausePlayer();
				});
			}

			// Re-apply widths and resize Flickity on window resize. Debounced
			// the same way as the listing sliders above.
			let resizeTimer;
			window.addEventListener('resize', function() {
				clearTimeout(resizeTimer);
				resizeTimer = setTimeout(function() {
					applyWidths();
					flkty.resize();
				}, 250);
			});
		} catch (error) {
			console.error('Flickity gallery init error:', error);
		}
	}

	// Initialize Flickity sliders when DOM is ready
	document.addEventListener('DOMContentLoaded', function() {
		
		// Initialize all theme listing sliders (testimonials, partners,
		// team, projects, generic) emitted by the theme's shortcodes /
		// custom blocks.
		const sliders = document.querySelectorAll('.prospero-slider');

		// Initialize gallery sliders. Picked up by any of:
		// - `is-style-slider` / `is-style-slider-autoplay` (block style
		//   choices registered for `core/gallery` in `inc/gutenberg.php`).
		// - `prospero-gallery-slider` (manual opt-in via Advanced -> CSS
		//   classes, also works for the classic `[gallery]` shortcode).
		// Autoplay is detected per-wrapper inside `initGallerySlider()`.
		const gallerySliders = document.querySelectorAll(
			'.wp-block-gallery.is-style-slider,' +
			'.wp-block-gallery.is-style-slider-autoplay,' +
			'.wp-block-gallery.prospero-gallery-slider,' +
			'.gallery.prospero-gallery-slider'
		);
		gallerySliders.forEach(function(gallery) {
			initGallerySlider(gallery);
		});

		if (sliders.length === 0) {
			return;
		}

		sliders.forEach(function(slider) {
			// Get slider type and columns from data attributes
			const sliderType = slider.dataset.sliderType || 'default';
			const columns = parseInt(slider.dataset.columns, 10) || 1;
			
			// Apply cell widths based on columns
			applyCellWidths(slider, columns, sliderType);
			
			// Count items to determine if slider is needed
			let itemSelector = '.flickity-cell';
			switch(sliderType) {
				case 'testimonials': itemSelector = '.testimonial-item'; break;
				case 'partners': itemSelector = '.partner-item'; break;
				case 'team': itemSelector = '.team-item'; break;
				case 'projects': itemSelector = '.project-item'; break;
			}
			const itemCount = slider.querySelectorAll(itemSelector).length;
			const needsSlider = itemCount > columns;
			
			// Base options - use left alignment for proper multi-item display
			let options = {
				cellAlign: 'left',
				contain: true,
				wrapAround: needsSlider,
				autoPlay: false,
				prevNextButtons: needsSlider,
				pageDots: needsSlider,
				accessibility: true,
				setGallerySize: true,
				imagesLoaded: true,
				percentPosition: true,
				draggable: needsSlider
			};

			// Type-specific options
			switch(sliderType) {
				case 'testimonials':
					options = Object.assign(options, {
						cellAlign: columns === 1 ? 'center' : 'left',
						autoPlay: needsSlider ? 5000 : false,
						adaptiveHeight: true
					});
					break;

				case 'partners':
					options = Object.assign(options, {
						cellAlign: 'left',
						autoPlay: needsSlider ? 4000 : false
					});
					break;

				case 'team':
					options = Object.assign(options, {
						cellAlign: 'left',
						wrapAround: false
					});
					break;

				case 'projects':
					options = Object.assign(options, {
						cellAlign: 'left'
					});
					break;

				case 'gallery':
					options = Object.assign(options, {
						cellAlign: 'center',
						contain: true,
						pageDots: false
					});
					break;
			}

			// Initialize Flickity
			try {
				const flkty = new Flickity(slider, options);
				
				// Add keyboard navigation
				slider.addEventListener('keydown', function(event) {
					// Left arrow key
					if (event.keyCode === 37) {
						event.preventDefault();
						flkty.previous();
					}
					// Right arrow key
					else if (event.keyCode === 39) {
						event.preventDefault();
						flkty.next();
					}
				});

				// Pause autoplay on hover
				if (options.autoPlay) {
					slider.addEventListener('mouseenter', function() {
						flkty.pausePlayer();
					});
					
					slider.addEventListener('mouseleave', function() {
						flkty.unpausePlayer();
					});
				}

				// Handle window resize - reapply widths and resize Flickity
				let resizeTimer;
				window.addEventListener('resize', function() {
					clearTimeout(resizeTimer);
					resizeTimer = setTimeout(function() {
						applyCellWidths(slider, columns, sliderType);
						flkty.resize();
					}, 250);
				});

			} catch (error) {
				console.error('Flickity initialization error:', error);
			}
		});
	});

})();
