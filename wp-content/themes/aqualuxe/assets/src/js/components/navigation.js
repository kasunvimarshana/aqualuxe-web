/**
 * Navigation Component
 * 
 * Handles responsive navigation, mobile menu, scroll effects,
 * and accessibility features.
 */

class Navigation {
    constructor() {
        this.nav = document.querySelector('.main-navigation');
        this.mobileToggle = document.querySelector('.mobile-menu-toggle');
        this.mobileMenu = document.querySelector('.mobile-menu');
        this.dropdownToggles = document.querySelectorAll('.dropdown-toggle');
        
        this.isOpen = false;
        this.scrolled = false;
        this.lastScrollY = 0;
        
        this.init();
    }

    init() {
        if (!this.nav) return;
        
        this.bindEvents();
        this.initDropdowns();
        this.initScrollEffects();
        this.initAccessibility();
        this.initSearchToggle();
        
        console.log('Navigation initialized');
    }

    bindEvents() {
        // Mobile menu toggle
        if (this.mobileToggle) {
            this.mobileToggle.addEventListener('click', (e) => {
                e.preventDefault();
                this.toggleMobileMenu();
            });
        }

        // Close mobile menu on outside click
        document.addEventListener('click', (e) => {
            if (this.isOpen && !this.nav.contains(e.target)) {
                this.closeMobileMenu();
            }
        });

        // Close mobile menu on escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.isOpen) {
                this.closeMobileMenu();
            }
        });

        // Handle window resize
        window.addEventListener('resize', () => {
            if (window.innerWidth > 768 && this.isOpen) {
                this.closeMobileMenu();
            }
        });

        // Scroll effects
        window.addEventListener('scroll', () => {
            this.handleScroll();
        });
    }

    toggleMobileMenu() {
        if (this.isOpen) {
            this.closeMobileMenu();
        } else {
            this.openMobileMenu();
        }
    }

    openMobileMenu() {
        this.isOpen = true;
        this.nav.classList.add('mobile-open');
        this.mobileToggle.classList.add('active');
        this.mobileToggle.setAttribute('aria-expanded', 'true');
        
        // Prevent body scroll
        document.body.classList.add('mobile-menu-open');
        
        // Focus management
        const firstFocusable = this.mobileMenu.querySelector('a, button');
        if (firstFocusable) {
            firstFocusable.focus();
        }
        
        // Animate menu items
        this.animateMenuItems('in');
    }

    closeMobileMenu() {
        this.isOpen = false;
        this.nav.classList.remove('mobile-open');
        this.mobileToggle.classList.remove('active');
        this.mobileToggle.setAttribute('aria-expanded', 'false');
        
        // Restore body scroll
        document.body.classList.remove('mobile-menu-open');
        
        // Close all dropdowns
        this.closeAllDropdowns();
        
        // Return focus to toggle button
        this.mobileToggle.focus();
        
        // Animate menu items
        this.animateMenuItems('out');
    }

    animateMenuItems(direction) {
        const menuItems = this.mobileMenu.querySelectorAll('.menu-item');
        
        menuItems.forEach((item, index) => {
            const delay = index * 0.1;
            
            if (direction === 'in') {
                item.style.opacity = '0';
                item.style.transform = 'translateY(-20px)';
                
                setTimeout(() => {
                    item.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                    item.style.opacity = '1';
                    item.style.transform = 'translateY(0)';
                }, delay * 1000);
            } else {
                item.style.transition = 'opacity 0.2s ease, transform 0.2s ease';
                item.style.opacity = '0';
                item.style.transform = 'translateY(-10px)';
            }
        });
    }

    initDropdowns() {
        this.dropdownToggles.forEach(toggle => {
            const dropdown = toggle.nextElementSibling;
            if (!dropdown) return;
            
            // Click handler
            toggle.addEventListener('click', (e) => {
                e.preventDefault();
                this.toggleDropdown(toggle, dropdown);
            });
            
            // Hover handlers for desktop
            if (window.innerWidth > 768) {
                const menuItem = toggle.closest('.menu-item');
                
                menuItem.addEventListener('mouseenter', () => {
                    this.openDropdown(toggle, dropdown);
                });
                
                menuItem.addEventListener('mouseleave', () => {
                    this.closeDropdown(toggle, dropdown);
                });
            }
            
            // Keyboard navigation
            toggle.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    this.toggleDropdown(toggle, dropdown);
                } else if (e.key === 'ArrowDown') {
                    e.preventDefault();
                    this.openDropdown(toggle, dropdown);
                    const firstLink = dropdown.querySelector('a');
                    if (firstLink) firstLink.focus();
                }
            });
            
            // Dropdown keyboard navigation
            const dropdownLinks = dropdown.querySelectorAll('a');
            dropdownLinks.forEach((link, index) => {
                link.addEventListener('keydown', (e) => {
                    if (e.key === 'ArrowDown') {
                        e.preventDefault();
                        const nextLink = dropdownLinks[index + 1];
                        if (nextLink) nextLink.focus();
                    } else if (e.key === 'ArrowUp') {
                        e.preventDefault();
                        if (index === 0) {
                            toggle.focus();
                        } else {
                            dropdownLinks[index - 1].focus();
                        }
                    } else if (e.key === 'Escape') {
                        this.closeDropdown(toggle, dropdown);
                        toggle.focus();
                    }
                });
            });
        });
    }

    toggleDropdown(toggle, dropdown) {
        const isOpen = dropdown.classList.contains('open');
        
        if (isOpen) {
            this.closeDropdown(toggle, dropdown);
        } else {
            this.closeAllDropdowns();
            this.openDropdown(toggle, dropdown);
        }
    }

    openDropdown(toggle, dropdown) {
        dropdown.classList.add('open');
        toggle.setAttribute('aria-expanded', 'true');
        toggle.classList.add('active');
        
        // Position dropdown if needed
        this.positionDropdown(dropdown);
    }

    closeDropdown(toggle, dropdown) {
        dropdown.classList.remove('open');
        toggle.setAttribute('aria-expanded', 'false');
        toggle.classList.remove('active');
    }

    closeAllDropdowns() {
        this.dropdownToggles.forEach(toggle => {
            const dropdown = toggle.nextElementSibling;
            if (dropdown) {
                this.closeDropdown(toggle, dropdown);
            }
        });
    }

    positionDropdown(dropdown) {
        const rect = dropdown.getBoundingClientRect();
        const viewportWidth = window.innerWidth;
        
        // Check if dropdown goes off-screen
        if (rect.right > viewportWidth) {
            dropdown.classList.add('dropdown-right');
        } else {
            dropdown.classList.remove('dropdown-right');
        }
    }

    initScrollEffects() {
        this.handleScroll();
    }

    handleScroll() {
        const currentScrollY = window.scrollY;
        
        // Add/remove scrolled class
        if (currentScrollY > 50 && !this.scrolled) {
            this.nav.classList.add('scrolled');
            this.scrolled = true;
        } else if (currentScrollY <= 50 && this.scrolled) {
            this.nav.classList.remove('scrolled');
            this.scrolled = false;
        }
        
        // Hide/show navigation on scroll
        if (currentScrollY > 100) {
            if (currentScrollY > this.lastScrollY) {
                // Scrolling down
                this.nav.classList.add('nav-hidden');
            } else {
                // Scrolling up
                this.nav.classList.remove('nav-hidden');
            }
        } else {
            this.nav.classList.remove('nav-hidden');
        }
        
        this.lastScrollY = currentScrollY;
    }

    initAccessibility() {
        // Skip link functionality
        const skipLink = document.querySelector('.skip-link');
        if (skipLink) {
            skipLink.addEventListener('click', (e) => {
                e.preventDefault();
                const target = document.querySelector(skipLink.getAttribute('href'));
                if (target) {
                    target.focus();
                    target.scrollIntoView();
                }
            });
        }
        
        // ARIA labels and roles
        this.nav.setAttribute('role', 'navigation');
        this.nav.setAttribute('aria-label', 'Main navigation');
        
        if (this.mobileMenu) {
            this.mobileMenu.setAttribute('role', 'menu');
        }
        
        // Set initial ARIA states
        this.dropdownToggles.forEach(toggle => {
            toggle.setAttribute('aria-expanded', 'false');
            toggle.setAttribute('aria-haspopup', 'true');
        });
        
        if (this.mobileToggle) {
            this.mobileToggle.setAttribute('aria-expanded', 'false');
            this.mobileToggle.setAttribute('aria-controls', 'mobile-menu');
        }
    }

    initSearchToggle() {
        const searchToggle = document.querySelector('.search-toggle');
        const searchForm = document.querySelector('.search-form');
        
        if (searchToggle && searchForm) {
            searchToggle.addEventListener('click', (e) => {
                e.preventDefault();
                this.toggleSearch(searchForm);
            });
            
            // Close search on outside click
            document.addEventListener('click', (e) => {
                if (!searchForm.contains(e.target) && !searchToggle.contains(e.target)) {
                    this.closeSearch(searchForm);
                }
            });
            
            // Close search on escape
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && searchForm.classList.contains('open')) {
                    this.closeSearch(searchForm);
                }
            });
        }
    }

    toggleSearch(searchForm) {
        if (searchForm.classList.contains('open')) {
            this.closeSearch(searchForm);
        } else {
            this.openSearch(searchForm);
        }
    }

    openSearch(searchForm) {
        searchForm.classList.add('open');
        const searchInput = searchForm.querySelector('input[type="search"]');
        if (searchInput) {
            searchInput.focus();
        }
    }

    closeSearch(searchForm) {
        searchForm.classList.remove('open');
    }

    // Public methods for external use
    highlightCurrentPage() {
        const currentUrl = window.location.pathname;
        const navLinks = this.nav.querySelectorAll('a');
        
        navLinks.forEach(link => {
            const linkUrl = new URL(link.href).pathname;
            if (linkUrl === currentUrl || (currentUrl.startsWith(linkUrl) && linkUrl !== '/')) {
                link.classList.add('current');
                
                // Also highlight parent menu item if in dropdown
                const dropdown = link.closest('.dropdown-menu');
                if (dropdown) {
                    const parentToggle = dropdown.previousElementSibling;
                    if (parentToggle) {
                        parentToggle.classList.add('current');
                    }
                }
            }
        });
    }

    addActiveStates() {
        // Add active states for better UX
        const menuLinks = this.nav.querySelectorAll('a');
        
        menuLinks.forEach(link => {
            link.addEventListener('mousedown', () => {
                link.classList.add('pressed');
            });
            
            link.addEventListener('mouseup', () => {
                link.classList.remove('pressed');
            });
            
            link.addEventListener('mouseleave', () => {
                link.classList.remove('pressed');
            });
        });
    }

    destroy() {
        // Clean up event listeners
        if (this.mobileToggle) {
            this.mobileToggle.removeEventListener('click', this.toggleMobileMenu);
        }
        
        window.removeEventListener('scroll', this.handleScroll);
        window.removeEventListener('resize', this.handleResize);
        
        // Remove classes
        document.body.classList.remove('mobile-menu-open');
        this.nav.classList.remove('mobile-open', 'scrolled', 'nav-hidden');
    }
}

export default Navigation;
