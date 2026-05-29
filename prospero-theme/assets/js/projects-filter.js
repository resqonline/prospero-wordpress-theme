/**
 * Projects Ajax Filtering
 *
 * @package Prospero
 * @since 1.0.0
 */

(function() {
	'use strict';

	document.addEventListener('DOMContentLoaded', function() {
		const projectsGrid = document.getElementById('projects-grid');
		const loadingIndicator = document.querySelector('.projects-loading');
		const filterButtons = document.querySelectorAll('.projects-filters .filter-button');
		
		if (!projectsGrid || filterButtons.length === 0) {
			return;
		}

		// Get Ajax settings from the localized prosperoAjax object.
		const nonce = (typeof prosperoAjax !== 'undefined') ? prosperoAjax.nonce : '';

		if (!nonce) {
			console.error('Ajax nonce not found');
			return;
		}

		// Read initial query args from the grid's data attributes — these
		// are written by the template from the same WP_Query args used for
		// the initial render, so the AJAX response always matches.
		const postsPerPage = projectsGrid.dataset.postsPerPage || '12';
		const orderby     = projectsGrid.dataset.orderby      || 'menu_order';
		const order       = projectsGrid.dataset.order        || 'ASC';

		// Filter button click handler
		filterButtons.forEach(function(button) {
			button.addEventListener('click', function(e) {
				e.preventDefault();
				
				const tag = this.dataset.tag;
				
				// Update active button
				filterButtons.forEach(function(btn) {
					btn.classList.remove('active');
				});
				this.classList.add('active');
				
				// Show loading
				if (loadingIndicator) {
					loadingIndicator.style.display = 'block';
				}
				projectsGrid.style.opacity = '0.5';
				
				// Prepare Ajax data
				const formData = new FormData();
					formData.append('action', 'prospero_filter_projects');
					formData.append('nonce', nonce);
					formData.append('tag', tag);
					formData.append('count', postsPerPage);
					formData.append('orderby', orderby);
					formData.append('order', order);
				
				// Send Ajax request
				fetch(prosperoAjax.ajaxUrl, {
					method: 'POST',
					credentials: 'same-origin',
					body: formData
				})
				.then(function(response) {
					return response.json();
				})
				.then(function(data) {
					// Hide loading
					if (loadingIndicator) {
						loadingIndicator.style.display = 'none';
					}
					
					if (data.success && data.data.html) {
						// Fade out
						projectsGrid.style.opacity = '0';
						
						setTimeout(function() {
							// Update content
							projectsGrid.innerHTML = data.data.html;
							
							// Fade in
							projectsGrid.style.opacity = '1';
							
							// Announce to screen readers
							announceToScreenReaders(data.data.count + ' projects found');
						}, 300);
					} else {
						projectsGrid.style.opacity = '1';
						console.error('Ajax request failed:', data);
					}
				})
				.catch(function(error) {
					// Hide loading
					if (loadingIndicator) {
						loadingIndicator.style.display = 'none';
					}
					projectsGrid.style.opacity = '1';
					console.error('Ajax error:', error);
				});
			});
		});
		
		// Announce to screen readers
		function announceToScreenReaders(message) {
			const announcement = document.createElement('div');
			announcement.setAttribute('role', 'status');
			announcement.setAttribute('aria-live', 'polite');
			announcement.setAttribute('aria-atomic', 'true');
			announcement.className = 'screen-reader-text';
			announcement.textContent = message;
			document.body.appendChild(announcement);
			
			setTimeout(function() {
				document.body.removeChild(announcement);
			}, 1000);
		}
	});

})();
