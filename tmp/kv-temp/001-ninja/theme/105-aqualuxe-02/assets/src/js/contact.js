/**
 * Contact Form Enhancement
 * Handles contact form interactions, validation, and AJAX submissions
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

document.addEventListener('DOMContentLoaded', function () {
  'use strict';

  /**
   * Contact Form Handler
   */
  class ContactFormHandler {
    constructor() {
      this.forms = document.querySelectorAll('.contact-form');
      this.init();
    }

    /**
     * Initialize contact forms
     */
    init() {
      this.forms.forEach(form => {
        this.setupFormValidation(form);
        this.setupFormSubmission(form);
      });
    }

    /**
     * Setup form validation
     * @param {HTMLElement} form
     */
    setupFormValidation(form) {
      const fields = form.querySelectorAll('[required]');

      fields.forEach(field => {
        field.addEventListener('blur', () => {
          this.validateField(field);
        });

        field.addEventListener('input', () => {
          this.clearFieldError(field);
        });
      });
    }

    /**
     * Validate individual field
     * @param {HTMLElement} field
     */
    validateField(field) {
      const isValid = field.checkValidity();
      const errorElement = field.parentNode.querySelector('.field-error');

      if (!isValid) {
        field.classList.add('error');
        if (errorElement) {
          errorElement.textContent = field.validationMessage;
          errorElement.style.display = 'block';
        }
      } else {
        this.clearFieldError(field);
      }

      return isValid;
    }

    /**
     * Clear field error state
     * @param {HTMLElement} field
     */
    clearFieldError(field) {
      field.classList.remove('error');
      const errorElement = field.parentNode.querySelector('.field-error');
      if (errorElement) {
        errorElement.style.display = 'none';
      }
    }

    /**
     * Setup form submission
     * @param {HTMLElement} form
     */
    setupFormSubmission(form) {
      form.addEventListener('submit', e => {
        e.preventDefault();
        this.handleSubmission(form);
      });
    }

    /**
     * Handle form submission
     * @param {HTMLElement} form
     */
    async handleSubmission(form) {
      // Validate all fields
      const fields = form.querySelectorAll('[required]');
      let isValid = true;

      fields.forEach(field => {
        if (!this.validateField(field)) {
          isValid = false;
        }
      });

      if (!isValid) {
        return;
      }

      const submitButton = form.querySelector('[type="submit"]');
      const originalText = submitButton.textContent;

      try {
        // Show loading state
        submitButton.disabled = true;
        submitButton.textContent = 'Sending...';
        form.classList.add('loading');

        // Prepare form data
        const formData = new FormData(form);
        formData.append('action', 'aqualuxe_contact_form');

        // Submit form (placeholder - implement AJAX submission)
        await this.simulateSubmission();

        // Show success message
        this.showMessage(form, 'Message sent successfully!', 'success');
        form.reset();
      } catch (error) {
        this.showMessage(
          form,
          'Failed to send message. Please try again.',
          'error'
        );
      } finally {
        // Reset form state
        submitButton.disabled = false;
        submitButton.textContent = originalText;
        form.classList.remove('loading');
      }
    }

    /**
     * Simulate form submission (replace with actual AJAX)
     */
    simulateSubmission() {
      return new Promise(resolve => {
        setTimeout(resolve, 1000);
      });
    }

    /**
     * Show message to user
     * @param {HTMLElement} form
     * @param {string} message
     * @param {string} type
     */
    showMessage(form, message, type) {
      let messageElement = form.querySelector('.form-message');

      if (!messageElement) {
        messageElement = document.createElement('div');
        messageElement.className = 'form-message';
        form.appendChild(messageElement);
      }

      messageElement.textContent = message;
      messageElement.className = `form-message ${type}`;
      messageElement.style.display = 'block';

      // Auto-hide after 5 seconds
      setTimeout(() => {
        messageElement.style.display = 'none';
      }, 5000);
    }
  }

  // Initialize contact form handler
  new ContactFormHandler();
});
