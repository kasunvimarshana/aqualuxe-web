/**
 * Forms Module
 * Handles form enhancements and validation
 */

class Forms {
    constructor() {
        this.init();
    }
    
    init() {
        this.initFormValidation();
        this.initFormEnhancements();
        this.initFileUploads();
        this.initFormSubmissions();
    }
    
    /**
     * Initialize form validation
     */
    initFormValidation() {
        const forms = document.querySelectorAll('form[data-validate]');
        
        forms.forEach(form => {
            this.setupFormValidation(form);
        });
    }
    
    /**
     * Setup validation for a form
     */
    setupFormValidation(form) {
        const fields = form.querySelectorAll('input, textarea, select');
        
        fields.forEach(field => {
            field.addEventListener('blur', () => this.validateField(field));
            field.addEventListener('input', () => this.clearFieldError(field));
        });
        
        form.addEventListener('submit', (e) => {
            if (!this.validateForm(form)) {
                e.preventDefault();
            }
        });
    }
    
    /**
     * Validate entire form
     */
    validateForm(form) {
        const fields = form.querySelectorAll('input, textarea, select');
        let isValid = true;
        
        fields.forEach(field => {
            if (!this.validateField(field)) {
                isValid = false;
            }
        });
        
        return isValid;
    }
    
    /**
     * Validate individual field
     */
    validateField(field) {
        const value = field.value.trim();
        const type = field.type;
        const required = field.hasAttribute('required');
        let isValid = true;
        let message = '';
        
        // Required validation
        if (required && !value) {
            isValid = false;
            message = 'This field is required.';
        }
        
        // Type-specific validation
        if (value && isValid) {
            switch (type) {
                case 'email':
                    if (!this.isValidEmail(value)) {
                        isValid = false;
                        message = 'Please enter a valid email address.';
                    }
                    break;
                case 'tel':
                    if (!this.isValidPhone(value)) {
                        isValid = false;
                        message = 'Please enter a valid phone number.';
                    }
                    break;
                case 'url':
                    if (!this.isValidUrl(value)) {
                        isValid = false;
                        message = 'Please enter a valid URL.';
                    }
                    break;
            }
        }
        
        // Custom validation patterns
        const pattern = field.getAttribute('pattern');
        if (pattern && value && isValid) {
            const regex = new RegExp(pattern);
            if (!regex.test(value)) {
                isValid = false;
                message = field.getAttribute('data-pattern-message') || 'Please match the required format.';
            }
        }
        
        // Min/max length validation
        const minLength = field.getAttribute('minlength');
        const maxLength = field.getAttribute('maxlength');
        
        if (minLength && value.length < parseInt(minLength)) {
            isValid = false;
            message = `Please enter at least ${minLength} characters.`;
        }
        
        if (maxLength && value.length > parseInt(maxLength)) {
            isValid = false;
            message = `Please enter no more than ${maxLength} characters.`;
        }
        
        // Show/hide error
        if (isValid) {
            this.clearFieldError(field);
        } else {
            this.showFieldError(field, message);
        }
        
        return isValid;
    }
    
    /**
     * Show field error
     */
    showFieldError(field, message) {
        const wrapper = field.closest('.form-field') || field.parentNode;
        
        // Add error class
        field.classList.add('has-error');
        wrapper.classList.add('has-error');
        
        // Remove existing error
        const existingError = wrapper.querySelector('.field-error');
        if (existingError) {
            existingError.remove();
        }
        
        // Add new error
        if (message) {
            const errorElement = document.createElement('div');
            errorElement.className = 'field-error';
            errorElement.textContent = message;
            errorElement.id = `error-${field.id || Math.random().toString(36).substr(2, 9)}`;
            
            wrapper.appendChild(errorElement);
            
            // Update aria attributes
            field.setAttribute('aria-invalid', 'true');
            field.setAttribute('aria-describedby', errorElement.id);
        }
    }
    
    /**
     * Clear field error
     */
    clearFieldError(field) {
        const wrapper = field.closest('.form-field') || field.parentNode;
        
        // Remove error classes
        field.classList.remove('has-error');
        wrapper.classList.remove('has-error');
        
        // Remove error message
        const errorElement = wrapper.querySelector('.field-error');
        if (errorElement) {
            errorElement.remove();
        }
        
        // Remove aria attributes
        field.removeAttribute('aria-invalid');
        field.removeAttribute('aria-describedby');
    }
    
    /**
     * Initialize form enhancements
     */
    initFormEnhancements() {
        this.initFloatingLabels();
        this.initPasswordToggle();
        this.initCharacterCount();
    }
    
    /**
     * Initialize floating labels
     */
    initFloatingLabels() {
        const floatingFields = document.querySelectorAll('.floating-label .field-input');
        
        floatingFields.forEach(field => {
            const updateLabel = () => {
                const wrapper = field.closest('.floating-label');
                if (field.value || field === document.activeElement) {
                    wrapper.classList.add('has-value');
                } else {
                    wrapper.classList.remove('has-value');
                }
            };
            
            field.addEventListener('focus', updateLabel);
            field.addEventListener('blur', updateLabel);
            field.addEventListener('input', updateLabel);
            
            // Initial state
            updateLabel();
        });
    }
    
    /**
     * Initialize password toggle
     */
    initPasswordToggle() {
        const passwordFields = document.querySelectorAll('input[type="password"][data-toggle-password]');
        
        passwordFields.forEach(field => {
            const toggleButton = document.createElement('button');
            toggleButton.type = 'button';
            toggleButton.className = 'password-toggle';
            toggleButton.setAttribute('aria-label', 'Show password');
            toggleButton.innerHTML = '👁️';
            
            field.parentNode.style.position = 'relative';
            field.style.paddingRight = '3rem';
            toggleButton.style.position = 'absolute';
            toggleButton.style.right = '0.75rem';
            toggleButton.style.top = '50%';
            toggleButton.style.transform = 'translateY(-50%)';
            toggleButton.style.border = 'none';
            toggleButton.style.background = 'none';
            toggleButton.style.cursor = 'pointer';
            
            field.parentNode.appendChild(toggleButton);
            
            toggleButton.addEventListener('click', () => {
                if (field.type === 'password') {
                    field.type = 'text';
                    toggleButton.setAttribute('aria-label', 'Hide password');
                    toggleButton.innerHTML = '🙈';
                } else {
                    field.type = 'password';
                    toggleButton.setAttribute('aria-label', 'Show password');
                    toggleButton.innerHTML = '👁️';
                }
            });
        });
    }
    
    /**
     * Initialize character count
     */
    initCharacterCount() {
        const fields = document.querySelectorAll('[data-character-count]');
        
        fields.forEach(field => {
            const maxLength = field.getAttribute('maxlength') || field.getAttribute('data-character-count');
            if (!maxLength) return;
            
            const counter = document.createElement('div');
            counter.className = 'character-count text-sm text-gray-500';
            
            const updateCount = () => {
                const remaining = maxLength - field.value.length;
                counter.textContent = `${remaining} characters remaining`;
                
                if (remaining < 0) {
                    counter.classList.add('text-red-500');
                    counter.classList.remove('text-gray-500');
                } else {
                    counter.classList.add('text-gray-500');
                    counter.classList.remove('text-red-500');
                }
            };
            
            field.addEventListener('input', updateCount);
            field.parentNode.appendChild(counter);
            
            // Initial count
            updateCount();
        });
    }
    
    /**
     * Initialize file uploads
     */
    initFileUploads() {
        const fileInputs = document.querySelectorAll('input[type="file"]');
        
        fileInputs.forEach(input => {
            this.enhanceFileInput(input);
        });
    }
    
    /**
     * Enhance file input
     */
    enhanceFileInput(input) {
        const wrapper = document.createElement('div');
        wrapper.className = 'file-input-wrapper';
        
        const label = document.createElement('label');
        label.className = 'file-input-label btn btn-outline';
        label.textContent = 'Choose File';
        label.htmlFor = input.id;
        
        const fileName = document.createElement('span');
        fileName.className = 'file-name text-sm text-gray-600 ml-2';
        
        input.parentNode.insertBefore(wrapper, input);
        wrapper.appendChild(input);
        wrapper.appendChild(label);
        wrapper.appendChild(fileName);
        
        input.addEventListener('change', () => {
            const files = input.files;
            if (files.length > 0) {
                fileName.textContent = files.length === 1 ? files[0].name : `${files.length} files selected`;
            } else {
                fileName.textContent = '';
            }
        });
    }
    
    /**
     * Initialize form submissions
     */
    initFormSubmissions() {
        const ajaxForms = document.querySelectorAll('form[data-ajax]');
        
        ajaxForms.forEach(form => {
            form.addEventListener('submit', (e) => {
                e.preventDefault();
                this.submitFormAjax(form);
            });
        });
    }
    
    /**
     * Submit form via AJAX
     */
    async submitFormAjax(form) {
        const submitButton = form.querySelector('button[type="submit"], input[type="submit"]');
        const originalText = submitButton ? submitButton.textContent : '';
        
        // Show loading state
        if (submitButton) {
            submitButton.disabled = true;
            submitButton.textContent = 'Submitting...';
        }
        
        form.classList.add('form-loading');
        
        try {
            const formData = new FormData(form);
            const response = await fetch(form.action || window.location.href, {
                method: 'POST',
                body: formData
            });
            
            const result = await response.json();
            
            if (result.success) {
                this.showFormMessage(form, result.message || 'Form submitted successfully!', 'success');
                form.reset();
            } else {
                this.showFormMessage(form, result.message || 'An error occurred. Please try again.', 'error');
            }
        } catch (error) {
            console.error('Form submission error:', error);
            this.showFormMessage(form, 'An error occurred. Please try again.', 'error');
        } finally {
            // Reset loading state
            if (submitButton) {
                submitButton.disabled = false;
                submitButton.textContent = originalText;
            }
            
            form.classList.remove('form-loading');
        }
    }
    
    /**
     * Show form message
     */
    showFormMessage(form, message, type) {
        // Remove existing messages
        const existingMessage = form.querySelector('.form-message');
        if (existingMessage) {
            existingMessage.remove();
        }
        
        // Create new message
        const messageElement = document.createElement('div');
        messageElement.className = `form-message alert alert-${type}`;
        messageElement.textContent = message;
        
        form.insertBefore(messageElement, form.firstChild);
        
        // Auto-remove success messages
        if (type === 'success') {
            setTimeout(() => {
                messageElement.remove();
            }, 5000);
        }
    }
    
    /**
     * Validation helpers
     */
    isValidEmail(email) {
        const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return regex.test(email);
    }
    
    isValidPhone(phone) {
        const regex = /^[\+]?[0-9\s\-\(\)]+$/;
        return regex.test(phone) && phone.replace(/\D/g, '').length >= 10;
    }
    
    isValidUrl(url) {
        try {
            new URL(url);
            return true;
        } catch {
            return false;
        }
    }
}

export default Forms;