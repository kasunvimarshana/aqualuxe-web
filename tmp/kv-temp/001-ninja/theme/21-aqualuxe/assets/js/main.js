/**
 * AquaLuxe Theme Main JavaScript
 * 
 * This is the main JavaScript file for the AquaLuxe theme.
 * It initializes all the components and sets up event listeners.
 */

// Import Alpine.js for interactive components
import Alpine from 'alpinejs';

// Make Alpine available globally
window.Alpine = Alpine;

// Initialize Alpine
Alpine.start();

// Document ready function
document.addEventListener('DOMContentLoaded', function() {
    console.log('AquaLuxe theme initialized');
    
    // Initialize components
    initializeComponents();
    
    // Set up event listeners
    setupEventListeners();
});

/**
 * Initialize all components
 */
function initializeComponents() {
    // Initialize tooltips
    const tooltips = document.querySelectorAll('[data-tooltip]');
    if (tooltips.length > 0) {
        tooltips.forEach(tooltip => {
            tooltip.addEventListener('mouseenter', showTooltip);
            tooltip.addEventListener('mouseleave', hideTooltip);
        });
    }
    
    // Initialize modals
    const modalTriggers = document.querySelectorAll('[data-modal-trigger]');
    if (modalTriggers.length > 0) {
        modalTriggers.forEach(trigger => {
            trigger.addEventListener('click', openModal);
        });
        
        // Close modal on overlay click
        const modalOverlays = document.querySelectorAll('.modal-overlay');
        modalOverlays.forEach(overlay => {
            overlay.addEventListener('click', closeModal);
        });
        
        // Close modal on close button click
        const closeButtons = document.querySelectorAll('.modal-close');
        closeButtons.forEach(button => {
            button.addEventListener('click', closeModal);
        });
    }
    
    // Initialize tabs
    const tabGroups = document.querySelectorAll('[data-tabs]');
    if (tabGroups.length > 0) {
        tabGroups.forEach(tabGroup => {
            initializeTabs(tabGroup);
        });
    }
    
    // Initialize accordions
    const accordions = document.querySelectorAll('.accordion');
    if (accordions.length > 0) {
        accordions.forEach(accordion => {
            initializeAccordion(accordion);
        });
    }
}

/**
 * Set up global event listeners
 */
function setupEventListeners() {
    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const targetId = this.getAttribute('href');
            if (targetId !== '#') {
                e.preventDefault();
                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    targetElement.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            }
        });
    });
    
    // Handle back to top button
    const backToTopButton = document.querySelector('.back-to-top');
    if (backToTopButton) {
        window.addEventListener('scroll', () => {
            if (window.pageYOffset > 300) {
                backToTopButton.classList.add('active');
            } else {
                backToTopButton.classList.remove('active');
            }
        });
        
        backToTopButton.addEventListener('click', (e) => {
            e.preventDefault();
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }
    
    // Handle form submissions
    const forms = document.querySelectorAll('form:not(.cart):not(.checkout):not(.woocommerce-form)');
    forms.forEach(form => {
        form.addEventListener('submit', handleFormSubmit);
    });
}

/**
 * Show tooltip
 * @param {Event} e - Mouse event
 */
function showTooltip(e) {
    const tooltip = e.target;
    const tooltipText = tooltip.getAttribute('data-tooltip');
    
    const tooltipElement = document.createElement('div');
    tooltipElement.classList.add('tooltip');
    tooltipElement.textContent = tooltipText;
    
    document.body.appendChild(tooltipElement);
    
    const rect = tooltip.getBoundingClientRect();
    const tooltipRect = tooltipElement.getBoundingClientRect();
    
    tooltipElement.style.top = `${rect.top - tooltipRect.height - 10}px`;
    tooltipElement.style.left = `${rect.left + (rect.width / 2) - (tooltipRect.width / 2)}px`;
    
    tooltip.tooltipElement = tooltipElement;
}

/**
 * Hide tooltip
 * @param {Event} e - Mouse event
 */
function hideTooltip(e) {
    const tooltip = e.target;
    if (tooltip.tooltipElement) {
        document.body.removeChild(tooltip.tooltipElement);
        tooltip.tooltipElement = null;
    }
}

/**
 * Open modal
 * @param {Event} e - Click event
 */
function openModal(e) {
    e.preventDefault();
    const modalId = e.target.getAttribute('data-modal-trigger');
    const modal = document.getElementById(modalId);
    
    if (modal) {
        modal.classList.add('active');
        document.body.classList.add('modal-open');
    }
}

/**
 * Close modal
 * @param {Event} e - Click event
 */
function closeModal(e) {
    e.preventDefault();
    const modal = e.target.closest('.modal');
    if (modal) {
        modal.classList.remove('active');
        document.body.classList.remove('modal-open');
    }
}

/**
 * Initialize tabs
 * @param {HTMLElement} tabGroup - Tab group element
 */
function initializeTabs(tabGroup) {
    const tabs = tabGroup.querySelectorAll('[data-tab]');
    const tabContents = document.querySelectorAll(`[data-tab-content]`);
    
    tabs.forEach(tab => {
        tab.addEventListener('click', (e) => {
            e.preventDefault();
            
            const tabId = tab.getAttribute('data-tab');
            
            // Remove active class from all tabs and contents
            tabs.forEach(t => t.classList.remove('active'));
            tabContents.forEach(c => c.classList.remove('active'));
            
            // Add active class to current tab and content
            tab.classList.add('active');
            document.querySelector(`[data-tab-content="${tabId}"]`).classList.add('active');
        });
    });
    
    // Activate first tab by default
    if (tabs.length > 0) {
        tabs[0].click();
    }
}

/**
 * Initialize accordion
 * @param {HTMLElement} accordion - Accordion element
 */
function initializeAccordion(accordion) {
    const items = accordion.querySelectorAll('.accordion-item');
    
    items.forEach(item => {
        const header = item.querySelector('.accordion-header');
        const content = item.querySelector('.accordion-content');
        
        header.addEventListener('click', () => {
            const isActive = item.classList.contains('active');
            
            // Close all items
            if (accordion.hasAttribute('data-accordion-single')) {
                items.forEach(i => {
                    i.classList.remove('active');
                    i.querySelector('.accordion-content').style.maxHeight = null;
                });
            }
            
            // Toggle current item
            if (!isActive) {
                item.classList.add('active');
                content.style.maxHeight = content.scrollHeight + 'px';
            } else {
                item.classList.remove('active');
                content.style.maxHeight = null;
            }
        });
    });
}

/**
 * Handle form submission
 * @param {Event} e - Submit event
 */
function handleFormSubmit(e) {
    const form = e.target;
    
    // Basic form validation
    const requiredFields = form.querySelectorAll('[required]');
    let isValid = true;
    
    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            isValid = false;
            field.classList.add('error');
            
            // Add error message if not exists
            let errorMessage = field.nextElementSibling;
            if (!errorMessage || !errorMessage.classList.contains('error-message')) {
                errorMessage = document.createElement('div');
                errorMessage.classList.add('error-message');
                errorMessage.textContent = 'This field is required';
                field.parentNode.insertBefore(errorMessage, field.nextSibling);
            }
        } else {
            field.classList.remove('error');
            
            // Remove error message if exists
            const errorMessage = field.nextElementSibling;
            if (errorMessage && errorMessage.classList.contains('error-message')) {
                errorMessage.remove();
            }
        }
    });
    
    if (!isValid) {
        e.preventDefault();
    }
}

// Export any functions or objects that need to be accessed from other files
export {
    initializeComponents,
    setupEventListeners
};