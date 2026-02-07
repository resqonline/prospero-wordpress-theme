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
		var $input = $('#prospero_project_gallery');
		var $preview = $('#prospero-gallery-preview');

		// Add images button
		$('#prospero-gallery-add').on('click', function(e) {
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
				var currentIds = $input.val() ? $input.val().split(',').map(function(id) { return parseInt(id, 10); }) : [];

				attachments.forEach(function(attachment) {
					// Skip if already in gallery
					if (currentIds.indexOf(attachment.id) !== -1) return;

					currentIds.push(attachment.id);

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
				$input.val(currentIds.join(','));
			});

			frame.open();
		});

		// Remove image
		$preview.on('click', '.prospero-gallery-remove', function(e) {
			e.preventDefault();

			var $item = $(this).closest('.prospero-gallery-item');
			var idToRemove = parseInt($item.data('id'), 10);
			var currentIds = $input.val() ? $input.val().split(',').map(function(id) { return parseInt(id, 10); }) : [];

			// Remove from array
			currentIds = currentIds.filter(function(id) { return id !== idToRemove; });

			// Update input and remove element
			$input.val(currentIds.join(','));
			$item.remove();
		});

		// Make gallery sortable
		if ($.fn.sortable) {
			$preview.sortable({
				items: '.prospero-gallery-item',
				cursor: 'move',
				tolerance: 'pointer',
				update: function() {
					var newOrder = [];
					$preview.find('.prospero-gallery-item').each(function() {
						newOrder.push($(this).data('id'));
					});
					$input.val(newOrder.join(','));
				}
			});
		}
	}

	// Initialize on document ready
	$(document).ready(function() {
		initSecondaryImageUpload();
		initGalleryUpload();
	});

})(jQuery);
