/**
 * Dark Mode functionality
 *
 * @package Prospero
 * @since 1.0.0
 */

(function() {
	'use strict';

	const STORAGE_KEY = 'prospero-theme-mode';
	const STORAGE_AUTO_KEY = 'prospero-theme-auto';
	const darkModeToggle = document.getElementById('dark-mode-toggle');
	const body = document.body;

	/**
	 * Get system preference
	 */
	function getSystemPreference() {
		return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
	}

	/**
	 * Check if user wants auto mode
	 */
	function isAutoMode() {
		const stored = localStorage.getItem(STORAGE_KEY);
		// If no manual preference is stored, check if auto is enabled
		if (!stored) {
			if (typeof prosperoSettings !== 'undefined' && prosperoSettings.defaultMode === 'auto') {
				return true;
			}
		}
		return localStorage.getItem(STORAGE_AUTO_KEY) === 'true';
	}

	/**
	 * Get user preference from localStorage or system
	 */
	function getUserPreference() {
		// Check if auto mode is active
		if (isAutoMode()) {
			return getSystemPreference();
		}

		const stored = localStorage.getItem(STORAGE_KEY);
		if (stored) {
			return stored;
		}

		// Check default mode from settings
		if (typeof prosperoSettings !== 'undefined' && prosperoSettings.defaultMode) {
			if (prosperoSettings.defaultMode === 'auto') {
				return getSystemPreference();
			}
			return prosperoSettings.defaultMode;
		}

		return 'light';
	}

	/**
	 * Set theme mode
	 */
	function setThemeMode(mode, savePreference = true) {
		if (mode === 'dark') {
			body.classList.add('dark-mode');
			body.classList.remove('light-mode');
		} else {
			body.classList.add('light-mode');
			body.classList.remove('dark-mode');
		}
		
		if (savePreference) {
			localStorage.setItem(STORAGE_KEY, mode);
		}
		
		// Update toggle button title
		if (darkModeToggle) {
			const nextMode = mode === 'dark' ? 'light' : 'dark';
			const label = nextMode === 'dark' 
				? 'Switch to dark mode' 
				: 'Switch to light mode';
			darkModeToggle.setAttribute('aria-label', label);
			darkModeToggle.setAttribute('title', label);
		}
	}

	/**
	 * Toggle theme mode
	 */
	function toggleThemeMode() {
		const currentMode = body.classList.contains('dark-mode') ? 'dark' : 'light';
		const newMode = currentMode === 'dark' ? 'light' : 'dark';
		
		// When user manually toggles, disable auto mode
		localStorage.removeItem(STORAGE_AUTO_KEY);
		setThemeMode(newMode, true);
	}

	/**
	 * Handle system preference changes (only if in auto mode)
	 */
	function handleSystemChange(e) {
		if (isAutoMode()) {
			const newMode = e.matches ? 'dark' : 'light';
			setThemeMode(newMode, false);
		}
	}

	/**
	 * Initialize dark mode
	 */
	function initDarkMode() {
		// Set initial mode based on user preference or system
		const userPreference = getUserPreference();
		setThemeMode(userPreference, false);

		// Add click event to toggle button
		if (darkModeToggle) {
			darkModeToggle.addEventListener('click', toggleThemeMode);
			
			// Add keyboard support
			darkModeToggle.addEventListener('keydown', function(e) {
				if (e.key === 'Enter' || e.key === ' ') {
					e.preventDefault();
					toggleThemeMode();
				}
			});
		}

		// Listen for system preference changes
		const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
		if (mediaQuery.addEventListener) {
			mediaQuery.addEventListener('change', handleSystemChange);
		} else {
			// Fallback for older browsers
			mediaQuery.addListener(handleSystemChange);
		}
	}

	// Initialize when DOM is ready
	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', initDarkMode);
	} else {
		initDarkMode();
	}
})();
