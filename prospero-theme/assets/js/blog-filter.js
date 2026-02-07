/**
 * Blog Category Filter
 *
 * Handles AJAX filtering of blog posts by category.
 *
 * @package Prospero
 * @since 1.0.0
 */

(function() {
	'use strict';

	const filterContainer = document.querySelector('.blog-category-filter');
	const postsContainer = document.querySelector('.blog-posts');
	const paginationContainer = document.querySelector('.blog-pagination');

	if (!filterContainer || !postsContainer) {
		return;
	}

	const filterButtons = filterContainer.querySelectorAll('.filter-button');
	let currentCategory = 'all';
	let currentPage = 1;
	let isLoading = false;

	/**
	 * Filter posts by category
	 */
	function filterPosts(category, page = 1) {
		if (isLoading) {
			return;
		}

		isLoading = true;
		currentCategory = category;
		currentPage = page;

		// Update active button state
		filterButtons.forEach(function(btn) {
			btn.classList.remove('active');
			btn.setAttribute('aria-pressed', 'false');
			if (btn.dataset.category === category) {
				btn.classList.add('active');
				btn.setAttribute('aria-pressed', 'true');
			}
		});

		// Add loading state
		postsContainer.classList.add('loading');
		postsContainer.setAttribute('aria-busy', 'true');

		// Prepare form data
		const formData = new FormData();
		formData.append('action', 'prospero_filter_blog');
		formData.append('nonce', prosperoBlogFilter.nonce);
		formData.append('category', category);
		formData.append('paged', page);

		// Make AJAX request
		fetch(prosperoBlogFilter.ajaxUrl, {
			method: 'POST',
			body: formData,
			credentials: 'same-origin'
		})
		.then(function(response) {
			return response.json();
		})
		.then(function(data) {
			if (data.success) {
				// Update posts
				postsContainer.innerHTML = data.data.html;

				// Update pagination
				if (paginationContainer) {
					if (data.data.pagination) {
						paginationContainer.innerHTML = data.data.pagination;
						paginationContainer.style.display = '';
						bindPaginationLinks();
					} else {
						paginationContainer.innerHTML = '';
						paginationContainer.style.display = 'none';
					}
				}

				// Scroll to top of posts
				const headerHeight = document.querySelector('.site-header')?.offsetHeight || 0;
				const filterTop = filterContainer.getBoundingClientRect().top + window.scrollY - headerHeight - 20;
				window.scrollTo({
					top: filterTop,
					behavior: 'smooth'
				});

				// Update URL without page reload
				updateUrl(category, page);
			}
		})
		.catch(function(error) {
			console.error('Blog filter error:', error);
		})
		.finally(function() {
			isLoading = false;
			postsContainer.classList.remove('loading');
			postsContainer.setAttribute('aria-busy', 'false');
		});
	}

	/**
	 * Update browser URL
	 */
	function updateUrl(category, page) {
		const url = new URL(window.location.href);

		if (category && category !== 'all') {
			url.searchParams.set('category', category);
		} else {
			url.searchParams.delete('category');
		}

		if (page > 1) {
			url.searchParams.set('paged', page);
		} else {
			url.searchParams.delete('paged');
		}

		window.history.replaceState({}, '', url.toString());
	}

	/**
	 * Bind click events to pagination links
	 */
	function bindPaginationLinks() {
		if (!paginationContainer) {
			return;
		}

		const pageLinks = paginationContainer.querySelectorAll('a.page-numbers');
		pageLinks.forEach(function(link) {
			link.addEventListener('click', function(e) {
				e.preventDefault();

				// Extract page number from link
				let page = 1;
				const href = this.href;

				if (this.classList.contains('next')) {
					page = currentPage + 1;
				} else if (this.classList.contains('prev')) {
					page = currentPage - 1;
				} else {
					// Try to get page from URL parameter
					const urlParams = new URL(href).searchParams;
					const pagedParam = urlParams.get('paged');
					if (pagedParam) {
						page = parseInt(pagedParam, 10);
					} else {
						// Try to get from path
						const match = href.match(/\/page\/(\d+)/);
						if (match) {
							page = parseInt(match[1], 10);
						} else {
							// Get from link text
							page = parseInt(this.textContent, 10) || 1;
						}
					}
				}

				filterPosts(currentCategory, page);
			});
		});
	}

	/**
	 * Initialize filter buttons
	 */
	function init() {
		// Bind filter button clicks
		filterButtons.forEach(function(btn) {
			btn.addEventListener('click', function() {
				const category = this.dataset.category;
				filterPosts(category, 1);
			});

			// Keyboard support
			btn.addEventListener('keydown', function(e) {
				if (e.key === 'Enter' || e.key === ' ') {
					e.preventDefault();
					const category = this.dataset.category;
					filterPosts(category, 1);
				}
			});
		});

		// Bind initial pagination links
		bindPaginationLinks();

		// Check URL for initial category
		const urlParams = new URLSearchParams(window.location.search);
		const initialCategory = urlParams.get('category');
		const initialPage = parseInt(urlParams.get('paged'), 10) || 1;

		if (initialCategory) {
			filterPosts(initialCategory, initialPage);
		}
	}

	// Initialize when DOM is ready
	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', init);
	} else {
		init();
	}
})();
