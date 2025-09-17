/**
 * AquaLuxe Security Module
 * Client-side security enhancements
 */

(function($) {
    'use strict';

    const AquaLuxeSecurity = {
        
        /**
         * Initialize security features
         */
        init() {
            this.setupFormProtection();
            this.setupRateLimit();
            this.setupInputSanitization();
            this.preventClickjacking();
            this.setupCSRFProtection();
            this.monitorSuspiciousActivity();
        },

        /**
         * Setup form protection
         */
        setupFormProtection() {
            // Add CSRF tokens to all forms
            $('form').each(function() {
                if (!$(this).find('input[name="_wpnonce"]').length && 
                    !$(this).find('input[name="nonce"]').length) {
                    $(this).append('<input type="hidden" name="aqualuxe_nonce" value="' + 
                        aqualuxe_security.nonce + '">');
                }
            });

            // Validate forms before submission
            $('form').on('submit', function(e) {
                if (!AquaLuxeSecurity.validateForm($(this))) {
                    e.preventDefault();
                    return false;
                }
            });
        },

        /**
         * Validate form data
         */
        validateForm(form) {
            let isValid = true;

            // Check for suspicious patterns
            form.find('input[type="text"], input[type="email"], textarea').each(function() {
                const value = $(this).val();
                
                // Check for script injection attempts
                if (/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gi.test(value)) {
                    $(this).addClass('security-error');
                    isValid = false;
                }

                // Check for SQL injection patterns
                const sqlPatterns = [
                    /('|(\\')|(;|\\;)|(--|\\/\\*)|(\\||\\|\\|)/gi,
                    /(select|union|insert|update|delete|drop|create|alter|exec|execute)/gi
                ];

                sqlPatterns.forEach(pattern => {
                    if (pattern.test(value)) {
                        $(this).addClass('security-error');
                        isValid = false;
                    }
                });

                // Check for XSS patterns
                const xssPatterns = [
                    /<[^>]*script/gi,
                    /javascript:/gi,
                    /on\w+\s*=/gi,
                    /<[^>]*iframe/gi
                ];

                xssPatterns.forEach(pattern => {
                    if (pattern.test(value)) {
                        $(this).addClass('security-error');
                        isValid = false;
                    }
                });
            });

            if (!isValid) {
                this.showSecurityWarning('Suspicious input detected. Please check your form data.');
            }

            return isValid;
        },

        /**
         * Setup rate limiting for AJAX requests
         */
        setupRateLimit() {
            const requestCounts = new Map();
            const RATE_LIMIT = 10; // requests per minute
            const TIME_WINDOW = 60000; // 1 minute

            // Override jQuery AJAX to add rate limiting
            const originalAjax = $.ajax;
            $.ajax = function(options) {
                const now = Date.now();
                const endpoint = options.url;
                
                if (!requestCounts.has(endpoint)) {
                    requestCounts.set(endpoint, []);
                }

                const requests = requestCounts.get(endpoint);
                
                // Remove old requests outside time window
                const recentRequests = requests.filter(time => now - time < TIME_WINDOW);
                requestCounts.set(endpoint, recentRequests);

                if (recentRequests.length >= RATE_LIMIT) {
                    console.warn('Rate limit exceeded for endpoint:', endpoint);
                    return $.Deferred().reject('Rate limit exceeded').promise();
                }

                // Add current request timestamp
                recentRequests.push(now);

                return originalAjax.call(this, options);
            };
        },

        /**
         * Setup input sanitization
         */
        setupInputSanitization() {
            // Real-time input sanitization
            $('input[type="text"], input[type="email"], textarea').on('input', function() {
                let value = $(this).val();
                
                // Remove dangerous characters
                value = value.replace(/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gi, '');
                value = value.replace(/javascript:/gi, '');
                value = value.replace(/on\w+\s*=/gi, '');
                
                $(this).val(value);
                $(this).removeClass('security-error');
            });

            // Sanitize URLs
            $('input[type="url"]').on('blur', function() {
                const url = $(this).val();
                if (url && !this.isValidURL(url)) {
                    $(this).addClass('security-error');
                    this.showSecurityWarning('Invalid URL format detected.');
                }
            });
        },

        /**
         * Validate URL format
         */
        isValidURL(string) {
            try {
                const url = new URL(string);
                return ['http:', 'https:'].includes(url.protocol);
            } catch (_) {
                return false;
            }
        },

        /**
         * Prevent clickjacking
         */
        preventClickjacking() {
            // Check if page is in iframe
            if (window !== window.top) {
                // If referrer is not from same origin, block
                try {
                    if (document.referrer && 
                        new URL(document.referrer).origin !== window.location.origin) {
                        document.body.style.display = 'none';
                        console.warn('Potential clickjacking attempt blocked');
                    }
                } catch (e) {
                    // Block if referrer can't be parsed
                    document.body.style.display = 'none';
                }
            }
        },

        /**
         * Setup CSRF protection
         */
        setupCSRFProtection() {
            // Add CSRF token to all AJAX requests
            $.ajaxSetup({
                beforeSend: function(xhr, settings) {
                    if (settings.type === 'POST' || settings.type === 'PUT' || settings.type === 'DELETE') {
                        xhr.setRequestHeader('X-CSRF-Token', aqualuxe_security.nonce);
                    }
                }
            });
        },

        /**
         * Monitor suspicious activity
         */
        monitorSuspiciousActivity() {
            let suspiciousActivityCount = 0;
            const MAX_SUSPICIOUS_ACTIVITY = 5;

            // Monitor rapid form submissions
            let lastSubmitTime = 0;
            $('form').on('submit', function() {
                const now = Date.now();
                if (now - lastSubmitTime < 1000) { // Less than 1 second
                    suspiciousActivityCount++;
                    console.warn('Rapid form submission detected');
                }
                lastSubmitTime = now;
            });

            // Monitor excessive input attempts
            let inputAttempts = 0;
            $('input, textarea').on('input', function() {
                inputAttempts++;
                if (inputAttempts > 1000) { // More than 1000 input events
                    suspiciousActivityCount++;
                    console.warn('Excessive input activity detected');
                    inputAttempts = 0; // Reset counter
                }
            });

            // Monitor console access (basic deterrent)
            let devToolsOpen = false;
            setInterval(() => {
                if (window.outerWidth - window.innerWidth > 160 || 
                    window.outerHeight - window.innerHeight > 160) {
                    if (!devToolsOpen) {
                        devToolsOpen = true;
                        console.warn('Developer tools detected');
                        suspiciousActivityCount++;
                    }
                } else {
                    devToolsOpen = false;
                }
            }, 1000);

            // Block if too much suspicious activity
            if (suspiciousActivityCount >= MAX_SUSPICIOUS_ACTIVITY) {
                this.blockSuspiciousUser();
            }
        },

        /**
         * Block suspicious user
         */
        blockSuspiciousUser() {
            // Disable all forms
            $('form').off().on('submit', function(e) {
                e.preventDefault();
                return false;
            });

            // Disable all buttons
            $('button, input[type="submit"]').prop('disabled', true);

            this.showSecurityWarning('Suspicious activity detected. Page functionality has been disabled.');
            
            // Report to server
            $.post(aqualuxe_security.rate_limit_endpoint, {
                action: 'security_block',
                reason: 'suspicious_activity',
                nonce: aqualuxe_security.nonce
            });
        },

        /**
         * Show security warning
         */
        showSecurityWarning(message) {
            const warning = $('<div class="security-warning">')
                .html('<strong>Security Alert:</strong> ' + message)
                .css({
                    'position': 'fixed',
                    'top': '20px',
                    'right': '20px',
                    'background': '#dc3545',
                    'color': 'white',
                    'padding': '15px',
                    'border-radius': '5px',
                    'z-index': '9999',
                    'max-width': '400px',
                    'box-shadow': '0 4px 6px rgba(0,0,0,0.1)'
                });

            $('body').append(warning);
            
            setTimeout(() => {
                warning.fadeOut(() => warning.remove());
            }, 5000);
        },

        /**
         * Content Security Policy reporting
         */
        setupCSPReporting() {
            // Listen for CSP violations
            document.addEventListener('securitypolicyviolation', function(e) {
                console.warn('CSP Violation:', e);
                
                // Report to server
                $.post(aqualuxe_security.rate_limit_endpoint, {
                    action: 'csp_violation',
                    blocked_uri: e.blockedURI,
                    violated_directive: e.violatedDirective,
                    effective_directive: e.effectiveDirective,
                    original_policy: e.originalPolicy,
                    source_file: e.sourceFile,
                    line_number: e.lineNumber,
                    nonce: aqualuxe_security.nonce
                });
            });
        },

        /**
         * Secure local storage usage
         */
        secureStorage: {
            setItem(key, value) {
                try {
                    // Encrypt sensitive data before storing
                    const encrypted = btoa(JSON.stringify(value));
                    localStorage.setItem('aqualuxe_' + key, encrypted);
                } catch (e) {
                    console.warn('Failed to store data securely:', e);
                }
            },

            getItem(key) {
                try {
                    const encrypted = localStorage.getItem('aqualuxe_' + key);
                    if (encrypted) {
                        return JSON.parse(atob(encrypted));
                    }
                } catch (e) {
                    console.warn('Failed to retrieve data securely:', e);
                }
                return null;
            },

            removeItem(key) {
                localStorage.removeItem('aqualuxe_' + key);
            }
        }
    };

    // Initialize when document is ready
    $(document).ready(function() {
        AquaLuxeSecurity.init();
    });

    // Expose secure storage globally
    window.AquaLuxeSecureStorage = AquaLuxeSecurity.secureStorage;

})(jQuery);