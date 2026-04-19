/**
 * Admin Meta Boxes - Media Upload Functionality
 *
 * @package Prospero
 * @since 1.0.0
 */

(function($) {
	'use strict';

	/**
	 * Secondary Image Upload (Team)
	 */
	function initSecondaryImageUpload() {
		var $container = $('.prospero-image-upload');
		if (!$container.length) return;

		var frame;

		$container.on('click', '.prospero-upload-image', function(e) {
			e.preventDefault();

			var $button = $(this);
			var $container = $button.closest('.prospero-image-upload');
			var $input = $container.find('input[type="hidden"]');
			var $preview = $container.find('.prospero-image-preview');
			var $removeBtn = $container.find('.prospero-remove-image');

			// Create media frame
			frame = wp.media({
				title: prosperoAdmin.uploadImageTitle || 'Select Image',
				button: {
					text: prosperoAdmin.useImageText || 'Use this image'
				},
				multiple: false
			});

			// On select
			frame.on('select', function() {
				var attachment = frame.state().get('selection').first().toJSON();
				$input.val(attachment.id);
				$preview.html('<img src="' + attachment.url + '" style="max-width: 100%;" />');
				$removeBtn.show();
			});

			frame.open();
		});

		$container.on('click', '.prospero-remove-image', function(e) {
			e.preventDefault();

			var $button = $(this);
			var $container = $button.closest('.prospero-image-upload');
			var $input = $container.find('input[type="hidden"]');
			var $preview = $container.find('.prospero-image-preview');

			$input.val('');
			$preview.html('');
			$button.hide();
		});
	}

	/**
	 * Gallery Upload (Projects)
	 */
	function initGalleryUpload() {
		var $container = $('.prospero-gallery-upload');
		if (!$container.length) return;

		var frame;
		var $input = $container.find('input[name="prospero_project_gallery"]');
		var $preview = $container.find('.prospero-gallery-preview');

		if (!$input.length || !$preview.length) return;

		/**
		 * Update the hidden input with current gallery IDs
		 */
		function updateHiddenInput() {
			var ids = [];
			$preview.find('.prospero-gallery-item').each(function() {
				var id = $(this).data('id');
				if (id) {
					ids.push(parseInt(id, 10));
				}
			});
			$input.val(ids.join(','));
		}

		// Add images button
		$container.find('.prospero-gallery-add').on('click', function(e) {
			e.preventDefault();

			// Create media frame
			frame = wp.media({
				title: prosperoAdmin.addGalleryTitle || 'Add Gallery Images',
				button: {
					text: prosperoAdmin.addToGalleryText || 'Add to gallery'
				},
				multiple: true,
				library: {
					type: 'image'
				}
			});

			// On select
			frame.on('select', function() {
				var attachments = frame.state().get('selection').toJSON();
				
				// Get current IDs from DOM
				var currentIds = [];
				$preview.find('.prospero-gallery-item').each(function() {
					currentIds.push(parseInt($(this).data('id'), 10));
				});

				attachments.forEach(function(attachment) {
					// Skip if already in gallery
					if (currentIds.indexOf(attachment.id) !== -1) return;

					// Get thumbnail URL
					var thumbUrl = attachment.sizes && attachment.sizes.thumbnail 
						? attachment.sizes.thumbnail.url 
						: attachment.url;

					// Add to preview
					var $item = $('<div class="prospero-gallery-item" data-id="' + attachment.id + '">' +
						'<img src="' + thumbUrl + '" alt="" />' +
						'<button type="button" class="prospero-gallery-remove" aria-label="' + (prosperoAdmin.removeImageLabel || 'Remove image') + '">&times;</button>' +
						'</div>');
					$preview.append($item);
				});

				// Update hidden input
				updateHiddenInput();
			});

			frame.open();
		});

		// Remove image
		$preview.on('click', '.prospero-gallery-remove', function(e) {
			e.preventDefault();
			$(this).closest('.prospero-gallery-item').remove();
			updateHiddenInput();
		});

		// Make gallery sortable
		if ($.fn.sortable) {
			$preview.sortable({
				items: '.prospero-gallery-item',
				cursor: 'move',
				tolerance: 'pointer',
				update: function() {
					updateHiddenInput();
				}
			});
		}
	}

	/**
	 * Star Ratings (Testimonials)
	 */
	function initStarRatings() {
		var $container = $('#prospero-ratings-container');
		var $addButton = $('#prospero-add-rating');
		var $addDefaultButton = $('#prospero-add-default-ratings');
		var $template = $('#prospero-rating-template');
		
		if (!$container.length || !$addButton.length) return;
		
		var ratingIndex = $container.find('.prospero-rating-row').length;
		
		// Add new rating row
		$addButton.on('click', function(e) {
			e.preventDefault();
			addRatingRow('', 5);
		});
		
		// Add default ratings
		if ($addDefaultButton.length) {
			$addDefaultButton.on('click', function(e) {
				e.preventDefault();
				
				var labels = prosperoAdmin.defaultRatingLabels || [];
				
				if (labels.length === 0) {
					alert(prosperoAdmin.noDefaultLabels || 'No default labels configured.');
					return;
				}
				
				// Clear existing ratings
				$container.empty();
				ratingIndex = 0;
				// Add default ratings
				labels.forEach(function(label) {
					addRatingRow(label, 5);
				});
			});
		}
		
		// Add rating row function
		function addRatingRow(label, value) {
			var html = $template.html();
			html = html.replace(/\{\{INDEX\}\}/g, ratingIndex);
			var $row = $(html);
			
			if (label) {
				$row.find('input[type="text"]').val(label);
			}
			if (value) {
				$row.find('select').val(value);
				updateStarPreview($row, value);
			}
			
			$container.append($row);
			ratingIndex++;
		}
		
		// Update star preview when value changes
		$container.on('change', 'select', function() {
			var $row = $(this).closest('.prospero-rating-row');
			var value = parseInt($(this).val(), 10);
			updateStarPreview($row, value);
		});
		
		// Update star preview display
		function updateStarPreview($row, value) {
			var $preview = $row.find('.prospero-rating-preview');
			var html = '';
			for (var i = 1; i <= 5; i++) {
				html += '<span class="dashicons ' + (i <= value ? 'dashicons-star-filled' : 'dashicons-star-empty') + '"></span>';
			}
			$preview.html(html);
		}
		
		// Remove rating row
		$container.on('click', '.prospero-remove-rating', function(e) {
			e.preventDefault();
			$(this).closest('.prospero-rating-row').remove();
		});
	}

	// Initialize on document ready
	$(document).ready(function() {
		initSecondaryImageUpload();
		initGalleryUpload();
		initStarRatings();
	});

})(jQuery);
