/**
 * Flickity Slider Initialization
 *
 * @package Prospero
 * @since 1.0.0
 */

(function() {
	'use strict';

	// Initialize Flickity sliders when DOM is ready
	document.addEventListener('DOMContentLoaded', function() {
		
		// Initialize all Flickity sliders
		const sliders = document.querySelectorAll('.flickity-slider');
		
		if (sliders.length === 0) {
			return;
		}

		sliders.forEach(function(slider) {
			// Get slider type from data attribute
			const sliderType = slider.dataset.sliderType || 'default';
			
			// Base options
			let options = {
				cellAlign: 'left',
				contain: true,
				wrapAround: true,
				autoPlay: false,
				prevNextButtons: true,
				pageDots: true,
				accessibility: true,
				setGallerySize: true,
				imagesLoaded: true,
				lazyLoad: 2
			};

			// Type-specific options
			switch(sliderType) {
				case 'testimonials':
					options = Object.assign(options, {
						cellAlign: 'center',
						autoPlay: 5000,
						wrapAround: true,
						adaptiveHeight: true
					});
					break;

				case 'partners':
					options = Object.assign(options, {
						cellAlign: 'left',
						groupCells: true,
						autoPlay: 4000,
						wrapAround: true,
						pageDots: false
					});
					break;

				case 'team':
					options = Object.assign(options, {
						cellAlign: 'left',
						groupCells: true,
						wrapAround: false,
						pageDots: true
					});
					break;

				case 'projects':
					options = Object.assign(options, {
						cellAlign: 'left',
						wrapAround: false,
						pageDots: true,
						prevNextButtons: true
					});
					break;

				case 'gallery':
					options = Object.assign(options, {
						cellAlign: 'center',
						contain: true,
						wrapAround: true,
						pageDots: false,
						imagesLoaded: true
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

				// Handle window resize
				let resizeTimer;
				window.addEventListener('resize', function() {
					clearTimeout(resizeTimer);
					resizeTimer = setTimeout(function() {
						flkty.resize();
					}, 250);
				});

			} catch (error) {
				console.error('Flickity initialization error:', error);
			}
		});
	});

})();
