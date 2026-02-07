/**
 * FAQ Accordion functionality
 *
 * @package Prospero
 * @since 1.0.0
 */

(function() {
	'use strict';

	/**
	 * Initialize FAQ accordion
	 */
	function initFAQAccordion() {
		const accordions = document.querySelectorAll('.faq-accordion');
		
		if (!accordions.length) {
			return;
		}

		accordions.forEach(function(accordion) {
			const items = accordion.querySelectorAll('.faq-accordion-item');
			
			items.forEach(function(item) {
				const question = item.querySelector('.faq-question');
				const answer = item.querySelector('.faq-answer');
				
				if (!question || !answer) {
					return;
				}

				question.addEventListener('click', function() {
					const isExpanded = this.getAttribute('aria-expanded') === 'true';
					
					// Toggle current item
					this.setAttribute('aria-expanded', !isExpanded);
					answer.hidden = isExpanded;
					
					// Update toggle icon
					const toggle = this.querySelector('.faq-toggle');
					if (toggle) {
						toggle.textContent = isExpanded ? '+' : '−';
					}
				});

				// Keyboard accessibility
				question.addEventListener('keydown', function(e) {
					if (e.key === 'Enter' || e.key === ' ') {
						e.preventDefault();
						this.click();
					}
				});
			});
		});
	}

	// Initialize when DOM is ready
	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', initFAQAccordion);
	} else {
		initFAQAccordion();
	}
})();
