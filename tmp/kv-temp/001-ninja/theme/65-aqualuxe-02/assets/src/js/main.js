/**
 * AquaLuxe Theme Main JavaScript
 */

// Import Alpine.js
import Alpine from 'alpinejs';

// Make Alpine available globally
window.Alpine = Alpine;

// Initialize Alpine
Alpine.start();

// Document Ready
document.addEventListener('DOMContentLoaded', function() {
    // Mobile Menu Toggle
    const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
    const mobileMenu = document.getElementById('mobile-menu');

    if (mobileMenuToggle && mobileMenu) {
        mobileMenuToggle.addEventListener('click', function() {
            mobileMenu.classList.toggle('hidden');
        });
    }

    // Header Cart Dropdown
    const headerCart = document.querySelector('.header-cart');
    const headerCartDropdown = document.querySelector('.header-cart-dropdown');

    if (headerCart && headerCartDropdown) {
        headerCart.addEventListener('mouseenter', function() {
            headerCartDropdown.classList.remove('hidden');
        });

        headerCart.addEventListener('mouseleave', function() {
            headerCartDropdown.classList.add('hidden');
        });
    }

    // Responsive Tables
    const tables = document.querySelectorAll('table');
    tables.forEach(function(table) {
        const wrapper = document.createElement('div');
        wrapper.classList.add('table-responsive', 'overflow-x-auto');
        table.parentNode.insertBefore(wrapper, table);
        wrapper.appendChild(table);
    });

    // Responsive Embeds
    const embeds = document.querySelectorAll('iframe, embed, object');
    embeds.forEach(function(embed) {
        if (!embed.parentNode.classList.contains('responsive-embed')) {
            const wrapper = document.createElement('div');
            wrapper.classList.add('responsive-embed', 'relative', 'overflow-hidden', 'w-full', 'pt-[56.25%]');
            embed.classList.add('absolute', 'top-0', 'left-0', 'w-full', 'h-full');
            embed.parentNode.insertBefore(wrapper, embed);
            wrapper.appendChild(embed);
        }
    });

    // Smooth Scroll
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            
            if (href !== '#') {
                e.preventDefault();
                
                const target = document.querySelector(href);
                
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth'
                    });
                }
            }
        });
    });

    // Back to Top Button
    const backToTopButton = document.createElement('button');
    backToTopButton.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M12 10.828l-4.95 4.95-1.414-1.414L12 8l6.364 6.364-1.414 1.414z"/></svg>';
    backToTopButton.classList.add('back-to-top', 'fixed', 'bottom-8', 'right-8', 'bg-primary-600', 'text-white', 'p-2', 'rounded-full', 'shadow-lg', 'z-50', 'opacity-0', 'invisible', 'transition-all', 'duration-300');
    document.body.appendChild(backToTopButton);

    backToTopButton.addEventListener('click', function() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });

    window.addEventListener('scroll', function() {
        if (window.pageYOffset > 300) {
            backToTopButton.classList.remove('opacity-0', 'invisible');
            backToTopButton.classList.add('opacity-100', 'visible');
        } else {
            backToTopButton.classList.remove('opacity-100', 'visible');
            backToTopButton.classList.add('opacity-0', 'invisible');
        }
    });

    // Initialize any components that need JavaScript
    initializeComponents();
});

/**
 * Initialize components
 */
function initializeComponents() {
    // Tabs
    initTabs();

    // Accordions
    initAccordions();

    // Modals
    initModals();

    // Tooltips
    initTooltips();
}

/**
 * Initialize tabs
 */
function initTabs() {
    const tabGroups = document.querySelectorAll('.tabs');
    
    tabGroups.forEach(function(tabGroup) {
        const tabs = tabGroup.querySelectorAll('.tab');
        const tabContents = tabGroup.querySelectorAll('.tab-content');
        
        tabs.forEach(function(tab) {
            tab.addEventListener('click', function() {
                const target = this.getAttribute('data-tab');
                
                // Remove active class from all tabs
                tabs.forEach(function(tab) {
                    tab.classList.remove('active');
                });
                
                // Add active class to current tab
                this.classList.add('active');
                
                // Hide all tab contents
                tabContents.forEach(function(content) {
                    content.classList.add('hidden');
                });
                
                // Show current tab content
                document.getElementById(target).classList.remove('hidden');
            });
        });
    });
}

/**
 * Initialize accordions
 */
function initAccordions() {
    const accordions = document.querySelectorAll('.accordion');
    
    accordions.forEach(function(accordion) {
        const headers = accordion.querySelectorAll('.accordion-header');
        
        headers.forEach(function(header) {
            header.addEventListener('click', function() {
                const content = this.nextElementSibling;
                
                // Toggle current accordion
                this.classList.toggle('active');
                
                if (content.style.maxHeight) {
                    content.style.maxHeight = null;
                } else {
                    content.style.maxHeight = content.scrollHeight + 'px';
                }
                
                // If accordion is part of an accordion group with data-single="true", close others
                const accordionGroup = this.closest('.accordion-group');
                
                if (accordionGroup && accordionGroup.getAttribute('data-single') === 'true') {
                    const otherHeaders = accordionGroup.querySelectorAll('.accordion-header');
                    const otherContents = accordionGroup.querySelectorAll('.accordion-content');
                    
                    otherHeaders.forEach(function(otherHeader) {
                        if (otherHeader !== header) {
                            otherHeader.classList.remove('active');
                        }
                    });
                    
                    otherContents.forEach(function(otherContent) {
                        if (otherContent !== content) {
                            otherContent.style.maxHeight = null;
                        }
                    });
                }
            });
        });
    });
}

/**
 * Initialize modals
 */
function initModals() {
    const modalTriggers = document.querySelectorAll('[data-modal]');
    const modals = document.querySelectorAll('.modal');
    const closeButtons = document.querySelectorAll('.modal-close');
    
    modalTriggers.forEach(function(trigger) {
        trigger.addEventListener('click', function(e) {
            e.preventDefault();
            
            const modalId = this.getAttribute('data-modal');
            const modal = document.getElementById(modalId);
            
            if (modal) {
                modal.classList.remove('hidden');
                document.body.classList.add('modal-open');
            }
        });
    });
    
    closeButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            const modal = this.closest('.modal');
            
            if (modal) {
                modal.classList.add('hidden');
                document.body.classList.remove('modal-open');
            }
        });
    });
    
    modals.forEach(function(modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.add('hidden');
                document.body.classList.remove('modal-open');
            }
        });
    });
    
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            modals.forEach(function(modal) {
                if (!modal.classList.contains('hidden')) {
                    modal.classList.add('hidden');
                    document.body.classList.remove('modal-open');
                }
            });
        }
    });
}

/**
 * Initialize tooltips
 */
function initTooltips() {
    const tooltips = document.querySelectorAll('[data-tooltip]');
    
    tooltips.forEach(function(tooltip) {
        const content = tooltip.getAttribute('data-tooltip');
        const position = tooltip.getAttribute('data-tooltip-position') || 'top';
        
        tooltip.addEventListener('mouseenter', function() {
            const tooltipElement = document.createElement('div');
            tooltipElement.classList.add('tooltip', `tooltip-${position}`);
            tooltipElement.textContent = content;
            
            document.body.appendChild(tooltipElement);
            
            const rect = tooltip.getBoundingClientRect();
            const tooltipRect = tooltipElement.getBoundingClientRect();
            
            let top, left;
            
            switch (position) {
                case 'top':
                    top = rect.top - tooltipRect.height - 10;
                    left = rect.left + (rect.width / 2) - (tooltipRect.width / 2);
                    break;
                case 'bottom':
                    top = rect.bottom + 10;
                    left = rect.left + (rect.width / 2) - (tooltipRect.width / 2);
                    break;
                case 'left':
                    top = rect.top + (rect.height / 2) - (tooltipRect.height / 2);
                    left = rect.left - tooltipRect.width - 10;
                    break;
                case 'right':
                    top = rect.top + (rect.height / 2) - (tooltipRect.height / 2);
                    left = rect.right + 10;
                    break;
            }
            
            tooltipElement.style.top = `${top + window.scrollY}px`;
            tooltipElement.style.left = `${left + window.scrollX}px`;
            
            tooltip.addEventListener('mouseleave', function() {
                document.body.removeChild(tooltipElement);
            });
        });
    });
}