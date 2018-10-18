/**
 * @copyright  Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */


Joomla = window.Joomla || {};

(function(Joomla, document) {
	'use strict';

	var mm = new MetisMenu("#menu");

	function closest(element, selector) {
		var matchesFn;

		// find vendor prefix
		['matches', 'msMatchesSelector'].some(function(fn) {
			if (typeof document.body[fn] == 'function') {
				matchesFn = fn;
				return true;
			}
			return false;
		})

		var parent;

		// Traverse parents
		while (element) {
			parent = element.parentElement;
			if (parent && parent[matchesFn](selector)) {
				return parent;
			}
			element = parent;
		}

		return null;
	}

	var wrapper        = document.getElementById('wrapper');
	var sidebar        = document.getElementById('sidebar-wrapper');
	var menuToggleIcon = document.getElementById('menu-collapse-icon');
	var body           = document.body;

	// Set the initial state of the sidebar based on the localStorage value
	if (Joomla.localStorageEnabled()) {
		var sidebarState = localStorage.getItem('atum-sidebar');
		if (sidebarState === 'open' || sidebarState === null) {
			wrapper.classList.remove('closed');
			menuToggleIcon.classList.remove('fa-toggle-off');
			menuToggleIcon.classList.add('fa-toggle-on');
			localStorage.setItem('atum-sidebar', 'open');
		} else {
			wrapper.classList.add('closed');
			menuToggleIcon.classList.remove('fa-toggle-on');
			menuToggleIcon.classList.add('fa-toggle-off');
			localStorage.setItem('atum-sidebar', 'closed');
		}
	}

	// If the sidebar doesn't exist, for example, on edit views, then remove the "closed" class
	if (!sidebar) {
		wrapper.classList.remove('closed');
	}

	if (sidebar && !sidebar.getAttribute('data-hidden')) {
		// Sidebar
		var menuToggle = document.getElementById('menu-collapse'),
			first      = sidebar.querySelectorAll('.collapse-level-1');

		// Apply 2nd level collapse
		for (var i = 0; i < first.length; i++) {
			var second = first[i].querySelectorAll('.collapse-level-1');
			for (var j = 0; j < second.length; j++) {
				if (second[j]) {
					second[j].classList.remove('collapse-level-1');
					second[j].classList.add('collapse-level-2');
				}
			}
		}

		var menuClose = function() {
			sidebar.querySelector('.collapse').classList.remove('in');
		};

		// Toggle menu
		menuToggle.addEventListener('click', function(e) {
			wrapper.classList.toggle('closed');
			menuToggleIcon.classList.toggle('fa-toggle-on');
			menuToggleIcon.classList.toggle('fa-toggle-off');

			var listItems = document.querySelectorAll('.main-nav > li');
			for (var i = 0; i < listItems.length; i++) {
				listItems[i].classList.remove('open');
			}

			var elem = document.querySelector('.child-open');
			if (elem) {
				elem.classList.remove('child-open');
			}

			// Save the sidebar state
			if (Joomla.localStorageEnabled()) {
				if (wrapper.classList.contains('closed')) {
					localStorage.setItem('atum-sidebar', 'closed');
				} else {
					localStorage.setItem('atum-sidebar', 'open');
				}
			}
		});


		/**
		 * Sidebar Nav
		 */
		var allLinks     = wrapper.querySelectorAll('a.no-dropdown, a.collapse-arrow');
		var currentUrl   = window.location.href.toLowerCase();
		var mainNav      = document.getElementById('menu');
		var menuParents  = mainNav.querySelectorAll('li.parent > a');
		var subMenuClose = mainNav.querySelectorAll('li.parent .close');

		// Set active class
		for (var i = 0; i < allLinks.length; i++) {
			if (currentUrl === allLinks[i].href) {
				allLinks[i].classList.add('active');
				// Auto Expand Levels
				if (!allLinks[i].parentNode.classList.contains('parent')) {
					var firstLevel = closest(allLinks[i], '.collapse-level-1');
					var secondLevel = closest(allLinks[i], '.collapse-level-2');
						if (firstLevel) firstLevel.parentNode.classList.add('active');
						if (firstLevel) firstLevel.classList.add('in');
						if (secondLevel) secondLevel.parentNode.classList.add('active');
						if (secondLevel) secondLevel.classList.add('in');
				}
			}
		}

		// Child open toggle
		var openToggle = function() {
			var menuItem = this.parentNode;

			if (menuItem.classList.contains('open')) {
				mainNav.classList.remove('child-open');
				menuItem.classList.remove('open');
			}
			else {
				var siblings = menuItem.parentNode.children;
				for (var i = 0; i < siblings.length; i++) {
					siblings[i].classList.remove('open');
				}
				wrapper.classList.remove('closed');
				mainNav.classList.add('child-open');
				if (menuItem.parentNode.classList.contains('main-nav')) {
					menuItem.classList.add('open');
				}
			}
		};

		for (var i = 0; i < menuParents.length; i += 1) {
			menuParents[i].addEventListener('click', openToggle);
			menuParents[i].addEventListener('keyup', openToggle);
		}

		// Menu close
		for (var i = 0; i < subMenuClose.length; i++) {
			subMenuClose[i].addEventListener('click', function() {
				var menuChildOpen = mainNav.querySelectorAll('.open');

				for (var i = 0; i < menuChildOpen.length; i++) {
					menuChildOpen[i].classList.remove('open');
				}
				mainNav.classList.remove('child-open');
			});
		}

		// Accessibility
		var allLiEl = sidebar.querySelectorAll('ul[role="menubar"] li');
		for (var i = 0; i < allLiEl.length; i++) {
			// We care for enter and space
			allLiEl[i].addEventListener('keyup', function(e) {
				if (e.keyCode == 32 || e.keyCode == 13) {
					e.target.querySelector('a').click();
				}
			});
		}

		// Remove 'closed' class on resize
		window.addEventListener('resize', function() {
			setMenuHeight();
		});

		if (Joomla.localStorageEnabled()) {
			if (localStorage.getItem('adminMenuState') == 'true') {
				menuClose();
			}
		}

	}

})(Joomla, document);
