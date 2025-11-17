/**
 * Form Validation Module
 * 
 * Handles client-side form validation for various forms in the theme.
 * Provides real-time feedback and prevents submission of invalid forms.
 */

class FormValidation {
    constructor() {
        this.forms = document.querySelectorAll('form.validate');
        this.validationMessages = {
            required: 'This field is required.',
            email: 'Please enter a valid email address.',
            url: 'Please enter a valid URL.',
            number: 'Please enter a valid number.',
            minLength: 'Please enter at least {min} characters.',
            maxLength: 'Please enter no more than {max} characters.',
            pattern: 'Please match the requested format.',
            match: 'Fields do not match.',
            custom: 'Please enter a valid value.'
        };
    }

    init() {
        if (!this.forms.length) {
            return;
        }

        this.forms.forEach(form => {
            this.setupFormValidation(form);
        });
    }

    setupFormValidation(form) {
        const inputs = form.querySelectorAll('input, textarea, select');
        
        // Add validation to each input
        inputs.forEach(input => {
            // Skip submit buttons and elements with no-validate class
            if (input.type === 'submit' || input.classList.contains('no-validate')) {
                return;
            }
            
            // Create error message element
            const errorElement = document.createElement('div');
            errorElement.className = 'error-message';
            errorElement.style.display = 'none';
            input.parentNode.appendChild(errorElement);
            
            // Add validation events
            input.addEventListener('blur', () => {
                this.validateInput(input);
            });
            
            input.addEventListener('input', () => {
                // Clear error on input
                if (input.classList.contains('invalid')) {
                    errorElement.style.display = 'none';
                    input.classList.remove('invalid');
                }
            });
        });
        
        // Form submission validation
        form.addEventListener('submit', (e) => {
            let isValid = true;
            
            // Validate all inputs
            inputs.forEach(input => {
                if (input.type !== 'submit' && !input.classList.contains('no-validate')) {
                    if (!this.validateInput(input)) {
                        isValid = false;
                    }
                }
            });
            
            // Prevent submission if invalid
            if (!isValid) {
                e.preventDefault();
                
                // Focus first invalid input
                const firstInvalid = form.querySelector('.invalid');
                if (firstInvalid) {
                    firstInvalid.focus();
                }
            }
        });
    }

    validateInput(input) {
        const errorElement = input.parentNode.querySelector('.error-message');
        let isValid = true;
        let errorMessage = '';
        
        // Required validation
        if (input.required && !input.value.trim()) {
            isValid = false;
            errorMessage = this.validationMessages.required;
        }
        
        // Email validation
        else if (input.type === 'email' && input.value.trim() && !this.isValidEmail(input.value)) {
            isValid = false;
            errorMessage = this.validationMessages.email;
        }
        
        // URL validation
        else if (input.type === 'url' && input.value.trim() && !this.isValidUrl(input.value)) {
            isValid = false;
            errorMessage = this.validationMessages.url;
        }
        
        // Number validation
        else if (input.type === 'number' && input.value.trim()) {
            const min = parseFloat(input.min);
            const max = parseFloat(input.max);
            const value = parseFloat(input.value);
            
            if (isNaN(value)) {
                isValid = false;
                errorMessage = this.validationMessages.number;
            } else if (!isNaN(min) && value < min) {
                isValid = false;
                errorMessage = `Please enter a value greater than or equal to ${min}.`;
            } else if (!isNaN(max) && value > max) {
                isValid = false;
                errorMessage = `Please enter a value less than or equal to ${max}.`;
            }
        }
        
        // Min length validation
        else if (input.minLength && input.value.length < input.minLength) {
            isValid = false;
            errorMessage = this.validationMessages.minLength.replace('{min}', input.minLength);
        }
        
        // Max length validation
        else if (input.maxLength && input.value.length > input.maxLength) {
            isValid = false;
            errorMessage = this.validationMessages.maxLength.replace('{max}', input.maxLength);
        }
        
        // Pattern validation
        else if (input.pattern && input.value.trim() && !new RegExp(input.pattern).test(input.value)) {
            isValid = false;
            errorMessage = input.dataset.patternMessage || this.validationMessages.pattern;
        }
        
        // Match validation (for password confirmation etc.)
        else if (input.dataset.match) {
            const matchInput = document.getElementById(input.dataset.match);
            if (matchInput && input.value !== matchInput.value) {
                isValid = false;
                errorMessage = input.dataset.matchMessage || this.validationMessages.match;
            }
        }
        
        // Custom validation
        else if (input.dataset.validate) {
            try {
                const customValidator = new Function('value', input.dataset.validate);
                if (!customValidator(input.value)) {
                    isValid = false;
                    errorMessage = input.dataset.validateMessage || this.validationMessages.custom;
                }
            } catch (e) {
                console.error('Custom validation error:', e);
            }
        }
        
        // Update UI based on validation result
        if (!isValid) {
            input.classList.add('invalid');
            if (errorElement) {
                errorElement.textContent = errorMessage;
                errorElement.style.display = 'block';
            }
        } else {
            input.classList.remove('invalid');
            if (errorElement) {
                errorElement.style.display = 'none';
            }
        }
        
        return isValid;
    }

    isValidEmail(email) {
        const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(String(email).toLowerCase());
    }

    isValidUrl(url) {
        try {
            new URL(url);
            return true;
        } catch (e) {
            return false;
        }
    }
}

export default FormValidation;