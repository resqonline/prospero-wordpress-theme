/**
 * Prospero Lightbox
 * 
 * Simple, accessible lightbox for image links.
 * Automatically attaches to any <a> linking to an image file.
 *
 * @package Prospero
 * @since 1.0.0
 */

(function() {
	'use strict';

	// Image file extensions to detect
	var imageExtensions = /\.(jpe?g|png|gif|webp|avif|svg)(\?.*)?$/i;

	// Lightbox elements
	var lightbox = null;
	var lightboxImage = null;
	var lightboxCaption = null;
	var currentGallery = [];
	var currentIndex = 0;

	/**
	 * Create lightbox DOM elements
	 */
	function createLightbox() {
		if (lightbox) return;

		lightbox = document.createElement('div');
		lightbox.className = 'prospero-lightbox';
		lightbox.setAttribute('role', 'dialog');
		lightbox.setAttribute('aria-modal', 'true');
		lightbox.setAttribute('aria-label', prosperoLightbox.ariaLabel || 'Image lightbox');
		lightbox.innerHTML = 
			'<div class="prospero-lightbox-overlay"></div>' +
			'<div class="prospero-lightbox-content">' +
				'<button type="button" class="prospero-lightbox-close" aria-label="' + (prosperoLightbox.closeLabel || 'Close') + '">&times;</button>' +
				'<button type="button" class="prospero-lightbox-prev" aria-label="' + (prosperoLightbox.prevLabel || 'Previous image') + '">&lsaquo;</button>' +
				'<button type="button" class="prospero-lightbox-next" aria-label="' + (prosperoLightbox.nextLabel || 'Next image') + '">&rsaquo;</button>' +
				'<figure class="prospero-lightbox-figure">' +
					'<img class="prospero-lightbox-image" src="" alt="" />' +
					'<figcaption class="prospero-lightbox-caption"></figcaption>' +
				'</figure>' +
			'</div>';

		document.body.appendChild(lightbox);

		// Cache elements
		lightboxImage = lightbox.querySelector('.prospero-lightbox-image');
		lightboxCaption = lightbox.querySelector('.prospero-lightbox-caption');

		// Event listeners
		lightbox.querySelector('.prospero-lightbox-overlay').addEventListener('click', closeLightbox);
		lightbox.querySelector('.prospero-lightbox-close').addEventListener('click', closeLightbox);
		lightbox.querySelector('.prospero-lightbox-prev').addEventListener('click', showPrev);
		lightbox.querySelector('.prospero-lightbox-next').addEventListener('click', showNext);

		// Keyboard navigation
		lightbox.addEventListener('keydown', handleKeydown);
	}

	/**
	 * Open lightbox with given image
	 */
	function openLightbox(src, alt, caption, gallery, index) {
		createLightbox();

		currentGallery = gallery || [];
		currentIndex = index || 0;

		// Show/hide navigation
		var hasGallery = currentGallery.length > 1;
		lightbox.querySelector('.prospero-lightbox-prev').style.display = hasGallery ? '' : 'none';
		lightbox.querySelector('.prospero-lightbox-next').style.display = hasGallery ? '' : 'none';

		// Set image
		lightboxImage.src = src;
		lightboxImage.alt = alt || '';

		// Set caption
		if (caption) {
			lightboxCaption.textContent = caption;
			lightboxCaption.style.display = '';
		} else {
			lightboxCaption.style.display = 'none';
		}

		// Show lightbox
		lightbox.classList.add('is-open');
		document.body.style.overflow = 'hidden';

		// Focus trap
		lightbox.focus();
	}

	/**
	 * Close lightbox
	 */
	function closeLightbox() {
		if (!lightbox) return;
		lightbox.classList.remove('is-open');
		document.body.style.overflow = '';
		lightboxImage.src = '';
	}

	/**
	 * Show previous image in gallery
	 */
	function showPrev() {
		if (currentGallery.length < 2) return;
		currentIndex = (currentIndex - 1 + currentGallery.length) % currentGallery.length;
		showGalleryImage(currentIndex);
	}

	/**
	 * Show next image in gallery
	 */
	function showNext() {
		if (currentGallery.length < 2) return;
		currentIndex = (currentIndex + 1) % currentGallery.length;
		showGalleryImage(currentIndex);
	}

	/**
	 * Show specific gallery image
	 */
	function showGalleryImage(index) {
		var item = currentGallery[index];
		if (!item) return;

		lightboxImage.src = item.src;
		lightboxImage.alt = item.alt || '';

		if (item.caption) {
			lightboxCaption.textContent = item.caption;
			lightboxCaption.style.display = '';
		} else {
			lightboxCaption.style.display = 'none';
		}
	}

	/**
	 * Handle keyboard events
	 */
	function handleKeydown(e) {
		switch (e.key) {
			case 'Escape':
				closeLightbox();
				break;
			case 'ArrowLeft':
				showPrev();
				break;
			case 'ArrowRight':
				showNext();
				break;
		}
	}

	/**
	 * Get caption from link or image
	 */
	function getCaption(link, img) {
		// Check for data-caption attribute
		if (link.dataset.caption) {
			return link.dataset.caption;
		}
		// Check for title attribute
		if (link.title) {
			return link.title;
		}
		// Check image alt
		if (img && img.alt) {
			return img.alt;
		}
		// Check for figcaption
		var figure = link.closest('figure');
		if (figure) {
			var figcaption = figure.querySelector('figcaption');
			if (figcaption) {
				return figcaption.textContent;
			}
		}
		return '';
	}

	/**
	 * Check if link points to an image
	 */
	function isImageLink(link) {
		var href = link.href;
		if (!href) return false;
		
		// Check file extension
		if (imageExtensions.test(href)) {
			return true;
		}
		
		// Check for data-lightbox attribute (explicit opt-in)
		if (link.hasAttribute('data-lightbox')) {
			return true;
		}

		return false;
	}

	/**
	 * Get gallery group from link
	 */
	function getGalleryGroup(link) {
		// Check for data-lightbox attribute with group name
		var group = link.dataset.lightbox;
		if (group && group !== 'true' && group !== '') {
			return group;
		}

		// Check for gallery parent
		var gallery = link.closest('.gallery, .wp-block-gallery, .project-gallery, [data-lightbox-gallery]');
		if (gallery) {
			return gallery;
		}

		return null;
	}

	/**
	 * Build gallery array from group
	 */
	function buildGallery(group, clickedLink) {
		var links;
		var gallery = [];
		var clickedIndex = 0;

		if (typeof group === 'string') {
			// Named group via data-lightbox attribute
			links = document.querySelectorAll('[data-lightbox="' + group + '"]');
		} else if (group instanceof Element) {
			// Container element
			links = group.querySelectorAll('a');
		} else {
			return { gallery: [], index: 0 };
		}

		links.forEach(function(link, i) {
			if (!isImageLink(link)) return;

			var img = link.querySelector('img');
			gallery.push({
				src: link.href,
				alt: img ? img.alt : '',
				caption: getCaption(link, img)
			});

			if (link === clickedLink) {
				clickedIndex = gallery.length - 1;
			}
		});

		return { gallery: gallery, index: clickedIndex };
	}

	/**
	 * Initialize lightbox
	 */
	function init() {
		// Delegate click events on image links
		document.addEventListener('click', function(e) {
			var link = e.target.closest('a');
			if (!link) return;
			if (!isImageLink(link)) return;

			e.preventDefault();

			var img = link.querySelector('img');
			var caption = getCaption(link, img);
			var group = getGalleryGroup(link);

			if (group) {
				// Part of a gallery
				var result = buildGallery(group, link);
				openLightbox(link.href, img ? img.alt : '', caption, result.gallery, result.index);
			} else {
				// Single image
				openLightbox(link.href, img ? img.alt : '', caption);
			}
		});
	}

	// Initialize when DOM is ready
	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', init);
	} else {
		init();
	}
})();
