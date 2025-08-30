/**
 * Newsletter Module
 *
 * This file contains the JavaScript code for the newsletter functionality.
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Get newsletter forms
const newsletterForms = document.querySelectorAll('.newsletter-subscribe-form');

// Handle form submission
const handleFormSubmit = (form) => {
    form.addEventListener('submit', (e) => {
        e.preventDefault();
        
        // Get email input
        const emailInput = form.querySelector('input[type="email"]');
        if (!emailInput || !emailInput.value) return;
        
        // Get form data
        const formData = new FormData(form);
        formData.append('action', 'aqualuxe_newsletter_subscribe');
        formData.append('nonce', aqualuxeSettings.nonce);
        
        // Show loading state
        form.classList.add('loading');
        const submitButton = form.querySelector('button[type="submit"]');
        if (submitButton) {
            submitButton.disabled = true;
            submitButton.dataset.originalText = submitButton.innerHTML;
            submitButton.innerHTML = '<span class="loading-spinner"></span> ' + (aqualuxeSettings.loadingText || 'Loading...');
        }
        
        // Send AJAX request
        fetch(aqualuxeSettings.ajaxUrl, {
            method: 'POST',
            body: formData,
            credentials: 'same-origin'
        })
        .then(response => response.json())
        .then(data => {
            // Remove loading state
            form.classList.remove('loading');
            if (submitButton) {
                submitButton.disabled = false;
                submitButton.innerHTML = submitButton.dataset.originalText;
            }
            
            // Show success or error message
            const messageContainer = form.querySelector('.newsletter-message') || document.createElement('div');
            messageContainer.className = 'newsletter-message';
            
            if (data.success) {
                // Success
                messageContainer.className += ' success';
                messageContainer.textContent = data.data.message || 'Thank you for subscribing!';
                form.reset();
            } else {
                // Error
                messageContainer.className += ' error';
                messageContainer.textContent = data.data.message || 'An error occurred. Please try again.';
            }
            
            // Add message to form if it doesn't exist
            if (!form.querySelector('.newsletter-message')) {
                form.appendChild(messageContainer);
            }
            
            // Hide message after 5 seconds
            setTimeout(() => {
                messageContainer.classList.add('fade-out');
                setTimeout(() => {
                    messageContainer.remove();
                }, 500);
            }, 5000);
        })
        .catch(error => {
            // Remove loading state
            form.classList.remove('loading');
            if (submitButton) {
                submitButton.disabled = false;
                submitButton.innerHTML = submitButton.dataset.originalText;
            }
            
            // Show error message
            const messageContainer = form.querySelector('.newsletter-message') || document.createElement('div');
            messageContainer.className = 'newsletter-message error';
            messageContainer.textContent = 'An error occurred. Please try again.';
            
            // Add message to form if it doesn't exist
            if (!form.querySelector('.newsletter-message')) {
                form.appendChild(messageContainer);
            }
            
            // Hide message after 5 seconds
            setTimeout(() => {
                messageContainer.classList.add('fade-out');
                setTimeout(() => {
                    messageContainer.remove();
                }, 500);
            }, 5000);
            
            console.error('Newsletter subscription error:', error);
        });
    });
};

// Initialize newsletter functionality
const initNewsletter = () => {
    if (!newsletterForms.length) return;
    
    // Add event listeners to forms
    newsletterForms.forEach(form => {
        handleFormSubmit(form);
    });
};

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', initNewsletter);

// Export module
export default {
    initNewsletter
};