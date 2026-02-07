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

	// Initialize Flickity sliders when DOM is ready
	document.addEventListener('DOMContentLoaded', function() {
		
		// Initialize all Flickity sliders
		const sliders = document.querySelectorAll('.prospero-slider');
		
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
						cellAlign: 'left'
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
