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

					// Swap the icon-font glyph on the toggle. The element
					// carries `.icon-plus` when collapsed and `.icon-minus`
					// when expanded; the matching CSS rules render the
					// correct glyph via ::before. `aria-hidden` stays true
					// on the toggle so the class swap is purely visual -
					// the button's `aria-expanded` is the accessible state.
					const toggle = this.querySelector('.faq-toggle');
					if (toggle) {
						if (isExpanded) {
							toggle.classList.remove('icon-minus');
							toggle.classList.add('icon-plus');
						} else {
							toggle.classList.remove('icon-plus');
							toggle.classList.add('icon-minus');
						}
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
