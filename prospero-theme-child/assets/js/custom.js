/* ==========================================================================
 * Prospero Child Theme - Custom JavaScript
 * ==========================================================================
 *
 * How this file is loaded
 * -----------------------
 * The parent theme automatically enqueues this file when the child theme is
 * active and the file exists (see prospero_enqueue_scripts() in
 * prospero-theme/functions.php). The handle is `prospero-child-custom`, it
 * depends on `prospero-main`, and it loads in the page footer. So by the
 * time this file runs:
 *
 *   - The DOM is already parsed (no need to wait for DOMContentLoaded for
 *     most selectors, but use it anyway if you want to be safe).
 *   - The parent theme's main.js has already initialised (sticky header,
 *     mobile menu toggle, keyboard nav, etc.).
 *   - `prosperoSettings` (from Customizer) and `prosperoI18n` localisations
 *     are available on the global window object.
 *
 * How to use it
 * -------------
 * Put any custom behaviour you need on the frontend here. Prefer vanilla
 * JavaScript to keep the theme dependency-free; jQuery is only loaded when
 * the page actually requires it (e.g. comment reply).
 *
 * Wrap code in an IIFE to avoid leaking variables into the global scope,
 * and use `'use strict';` for safer defaults.
 *
 * Examples
 * --------
 * (function () {
 *     'use strict';
 *
 *     // Run after the DOM is ready (safe even if the script is deferred).
 *     const ready = function (fn) {
 *         if (document.readyState !== 'loading') {
 *             fn();
 *         } else {
 *             document.addEventListener('DOMContentLoaded', fn);
 *         }
 *     };
 *
 *     ready(function () {
 *         // Example 1: smooth-scroll for in-page anchor links.
 *         document.querySelectorAll('a[href^="#"]').forEach(function (link) {
 *             link.addEventListener('click', function (event) {
 *                 const target = document.querySelector(link.getAttribute('href'));
 *                 if (target) {
 *                     event.preventDefault();
 *                     target.scrollIntoView({ behavior: 'smooth' });
 *                 }
 *             });
 *         });
 *
 *         // Example 2: toggle a class on a custom element.
 *         const toggle = document.querySelector('.my-toggle');
 *         if (toggle) {
 *             toggle.addEventListener('click', function () {
 *                 toggle.classList.toggle('is-active');
 *             });
 *         }
 *
 *         // Example 3: read a Customizer-localised setting.
 *         if (typeof prosperoSettings !== 'undefined' && prosperoSettings.stickyMenu) {
 *             // ...
 *         }
 *     });
 * })();
 *
 * Remove this comment block once you start adding your own scripts, or keep
 * it as a reference - it is ignored by the JavaScript engine either way.
 * ========================================================================== */
