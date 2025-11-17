/**
 * AquaLuxe Theme Modal Component
 *
 * Handles modal functionality for popups, dialogs, and other overlays.
 */

import { trapFocus } from '../utils/helpers';

document.addEventListener('DOMContentLoaded', function() {
    // Initialize all modals that don't use Alpine.js
    initModals();
});

/**
 * Initialize all modals on the page
 */
function initModals() {
    const modalTriggers = document.querySelectorAll('[data-modal-target]');
    
    modalTriggers.forEach(trigger => {
        const targetId = trigger.getAttribute('data-modal-target');
        const modal = document.getElementById(targetId);
        
        if (!modal) return;
        
        // Set initial ARIA attributes
        trigger.setAttribute('aria-haspopup', 'dialog');
        trigger.setAttribute('aria-expanded', 'false');
        trigger.setAttribute('aria-controls', targetId);
        
        modal.setAttribute('role', 'dialog');
        modal.setAttribute('aria-modal', 'true');
        modal.setAttribute('aria-hidden', 'true');
        
        // Add event listeners
        trigger.addEventListener('click', (e) => {
            e.preventDefault();
            openModal(modal);
        });
        
        // Close button
        const closeButtons = modal.querySelectorAll('[data-modal-close]');
        closeButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                closeModal(modal);
            });
        });
        
        // Close when clicking overlay
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                closeModal(modal);
            }
        });
        
        // Close when pressing Escape
        modal.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                closeModal(modal);
            }
        });
    });
}

/**
 * Open a modal
 * 
 * @param {HTMLElement} modal - The modal element to open
 */
function openModal(modal) {
    if (!modal) return;
    
    // Store the element that had focus before opening the modal
    modal.previouslyFocused = document.activeElement;
    
    // Show the modal
    modal.classList.remove('hidden');
    modal.setAttribute('aria-hidden', 'false');
    
    // Update trigger button if exists
    const triggerId = modal.getAttribute('data-modal-trigger');
    if (triggerId) {
        const trigger = document.getElementById(triggerId);
        if (trigger) {
            trigger.setAttribute('aria-expanded', 'true');
        }
    }
    
    // Prevent body scrolling
    document.body.classList.add('modal-open');
    document.body.style.overflow = 'hidden';
    
    // Trap focus within the modal
    const focusTrap = trapFocus(modal);
    modal.focusTrap = focusTrap;
    
    // Focus the first focusable element
    const firstFocusable = modal.querySelector('a[href], button:not([disabled]), input:not([disabled]), select:not([disabled]), textarea:not([disabled]), [tabindex]:not([tabindex="-1"])');
    if (firstFocusable) {
        firstFocusable.focus();
    }
    
    // Trigger custom event
    modal.dispatchEvent(new CustomEvent('modal:opened'));
}

/**
 * Close a modal
 * 
 * @param {HTMLElement} modal - The modal element to close
 */
function closeModal(modal) {
    if (!modal) return;
    
    // Hide the modal
    modal.classList.add('hidden');
    modal.setAttribute('aria-hidden', 'true');
    
    // Update trigger button if exists
    const triggerId = modal.getAttribute('data-modal-trigger');
    if (triggerId) {
        const trigger = document.getElementById(triggerId);
        if (trigger) {
            trigger.setAttribute('aria-expanded', 'false');
        }
    }
    
    // Restore body scrolling
    document.body.classList.remove('modal-open');
    document.body.style.overflow = '';
    
    // Release focus trap
    if (modal.focusTrap) {
        modal.focusTrap.release();
        modal.focusTrap = null;
    }
    
    // Restore focus to the element that had it before the modal was opened
    if (modal.previouslyFocused) {
        modal.previouslyFocused.focus();
    }
    
    // Trigger custom event
    modal.dispatchEvent(new CustomEvent('modal:closed'));
}

/**
 * Check if a modal is open
 * 
 * @param {HTMLElement} modal - The modal element to check
 * @returns {boolean} - Whether the modal is open
 */
function isModalOpen(modal) {
    return !modal.classList.contains('hidden') && modal.getAttribute('aria-hidden') === 'false';
}

// Export for use in other modules
export { initModals, openModal, closeModal, isModalOpen };