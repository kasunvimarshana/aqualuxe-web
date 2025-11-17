/**
 * Contact Form Module
 * 
 * Handles form submission, validation, and map initialization.
 */

(function($) {
    'use strict';

    const ContactForm = {
        /**
         * Initialize the contact form functionality
         */
        init: function() {
            // Initialize variables
            this.contactForm = $('.aqualuxe-contact-form');
            
            // Exit if no contact form found
            if (!this.contactForm.length) {
                return;
            }
            
            // Initialize components
            this.initForm();
            this.initMap();
            this.initAnimations();
        },
        
        /**
         * Initialize form submission and validation
         */
        initForm: function() {
            const self = this;
            const form = $('.aqualuxe-contact-form__form');
            
            if (!form.length) {
                return;
            }
            
            // Form submission
            form.on('submit', function(e) {
                e.preventDefault();
                
                // Reset previous errors
                self.resetFormErrors(form);
                
                // Validate form
                if (!self.validateForm(form)) {
                    return;
                }
                
                // Show loading state
                self.setFormLoading(form, true);
                
                // Collect form data
                const formData = new FormData(form[0]);
                formData.append('action', 'aqualuxe_contact_form');
                formData.append('nonce', aqualuxeContactForm.nonce);
                
                // Submit form via AJAX
                $.ajax({
                    url: aqualuxeContactForm.ajaxUrl,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        // Reset loading state
                        self.setFormLoading(form, false);
                        
                        if (response.success) {
                            // Show success message
                            self.showFormResponse(form, 'success', response.data.message);
                            
                            // Reset form
                            form[0].reset();
                        } else {
                            // Show error message
                            self.showFormResponse(form, 'error', response.data.message);
                            
                            // Show field errors if any
                            if (response.data.errors) {
                                self.showFieldErrors(form, response.data.errors);
                            }
                        }
                    },
                    error: function() {
                        // Reset loading state
                        self.setFormLoading(form, false);
                        
                        // Show error message
                        self.showFormResponse(form, 'error', aqualuxeContactForm.errorMessage);
                    }
                });
            });
            
            // Real-time validation on input change
            form.find('input, textarea, select').on('input change', function() {
                const $field = $(this);
                const $fieldContainer = $field.closest('.form-field');
                const $errorContainer = $fieldContainer.find('.form-error');
                
                // Clear error when user starts typing
                if ($errorContainer.is(':visible')) {
                    $errorContainer.hide().empty();
                    $field.removeClass('border-red-500');
                }
            });
        },
        
        /**
         * Validate form fields
         * 
         * @param {jQuery} form - The form element
         * @return {boolean} - Whether the form is valid
         */
        validateForm: function(form) {
            let isValid = true;
            const self = this;
            
            // Check required fields
            form.find('[required]').each(function() {
                const $field = $(this);
                const $fieldContainer = $field.closest('.form-field');
                const $errorContainer = $fieldContainer.find('.form-error');
                const fieldType = $field.attr('type');
                const fieldName = $field.attr('name');
                const fieldValue = $field.val();
                
                // Check if field is empty
                if (!fieldValue) {
                    isValid = false;
                    self.showFieldError($field, $errorContainer, aqualuxeContactForm.requiredFieldMessage || 'This field is required.');
                }
                
                // Check email format
                if (fieldType === 'email' && fieldValue) {
                    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (!emailPattern.test(fieldValue)) {
                        isValid = false;
                        self.showFieldError($field, $errorContainer, aqualuxeContactForm.invalidEmailMessage || 'Please enter a valid email address.');
                    }
                }
                
                // Check checkbox
                if (fieldType === 'checkbox' && !$field.prop('checked')) {
                    isValid = false;
                    self.showFieldError($field, $errorContainer, aqualuxeContactForm.requiredFieldMessage || 'This field is required.');
                }
            });
            
            return isValid;
        },
        
        /**
         * Show field error
         * 
         * @param {jQuery} $field - The field element
         * @param {jQuery} $errorContainer - The error container
         * @param {string} message - The error message
         */
        showFieldError: function($field, $errorContainer, message) {
            $field.addClass('border-red-500');
            $errorContainer.html(message).removeClass('hidden').show();
        },
        
        /**
         * Show field errors from server response
         * 
         * @param {jQuery} form - The form element
         * @param {Object} errors - The errors object
         */
        showFieldErrors: function(form, errors) {
            const self = this;
            
            $.each(errors, function(fieldName, errorMessage) {
                const $field = form.find(`[name="${fieldName}"]`);
                const $fieldContainer = $field.closest('.form-field');
                const $errorContainer = $fieldContainer.find('.form-error');
                
                self.showFieldError($field, $errorContainer, errorMessage);
            });
        },
        
        /**
         * Reset form errors
         * 
         * @param {jQuery} form - The form element
         */
        resetFormErrors: function(form) {
            // Hide form response
            form.find('.form-response').hide().empty().removeClass('success error');
            
            // Reset field errors
            form.find('.form-error').hide().empty();
            form.find('input, textarea, select').removeClass('border-red-500');
        },
        
        /**
         * Show form response message
         * 
         * @param {jQuery} form - The form element
         * @param {string} type - The response type (success or error)
         * @param {string} message - The response message
         */
        showFormResponse: function(form, type, message) {
            const $response = form.find('.form-response');
            
            $response
                .html(message)
                .removeClass('success error hidden')
                .addClass(type)
                .show();
            
            // Scroll to response if not visible
            if (!this.isElementInViewport($response[0])) {
                $('html, body').animate({
                    scrollTop: $response.offset().top - 100
                }, 500);
            }
        },
        
        /**
         * Set form loading state
         * 
         * @param {jQuery} form - The form element
         * @param {boolean} isLoading - Whether the form is loading
         */
        setFormLoading: function(form, isLoading) {
            const $submitBtn = form.find('.submit-btn');
            const $spinner = form.find('.submit-spinner');
            
            if (isLoading) {
                $submitBtn.prop('disabled', true);
                $spinner.removeClass('hidden');
            } else {
                $submitBtn.prop('disabled', false);
                $spinner.addClass('hidden');
            }
        },
        
        /**
         * Initialize Google Map
         */
        initMap: function() {
            const mapContainer = document.getElementById('aqualuxe-map');
            
            if (!mapContainer || typeof google === 'undefined' || typeof google.maps === 'undefined') {
                return;
            }
            
            // Get map settings from data attributes
            const latitude = parseFloat(mapContainer.getAttribute('data-latitude')) || 40.7128;
            const longitude = parseFloat(mapContainer.getAttribute('data-longitude')) || -74.0060;
            const zoom = parseInt(mapContainer.getAttribute('data-zoom')) || 14;
            
            // Create map
            const map = new google.maps.Map(mapContainer, {
                center: { lat: latitude, lng: longitude },
                zoom: zoom,
                styles: [
                    {
                        "featureType": "water",
                        "elementType": "geometry",
                        "stylers": [
                            {
                                "color": "#e9e9e9"
                            },
                            {
                                "lightness": 17
                            }
                        ]
                    },
                    {
                        "featureType": "landscape",
                        "elementType": "geometry",
                        "stylers": [
                            {
                                "color": "#f5f5f5"
                            },
                            {
                                "lightness": 20
                            }
                        ]
                    },
                    {
                        "featureType": "road.highway",
                        "elementType": "geometry.fill",
                        "stylers": [
                            {
                                "color": "#ffffff"
                            },
                            {
                                "lightness": 17
                            }
                        ]
                    },
                    {
                        "featureType": "road.highway",
                        "elementType": "geometry.stroke",
                        "stylers": [
                            {
                                "color": "#ffffff"
                            },
                            {
                                "lightness": 29
                            },
                            {
                                "weight": 0.2
                            }
                        ]
                    },
                    {
                        "featureType": "road.arterial",
                        "elementType": "geometry",
                        "stylers": [
                            {
                                "color": "#ffffff"
                            },
                            {
                                "lightness": 18
                            }
                        ]
                    },
                    {
                        "featureType": "road.local",
                        "elementType": "geometry",
                        "stylers": [
                            {
                                "color": "#ffffff"
                            },
                            {
                                "lightness": 16
                            }
                        ]
                    },
                    {
                        "featureType": "poi",
                        "elementType": "geometry",
                        "stylers": [
                            {
                                "color": "#f5f5f5"
                            },
                            {
                                "lightness": 21
                            }
                        ]
                    },
                    {
                        "featureType": "poi.park",
                        "elementType": "geometry",
                        "stylers": [
                            {
                                "color": "#dedede"
                            },
                            {
                                "lightness": 21
                            }
                        ]
                    },
                    {
                        "elementType": "labels.text.stroke",
                        "stylers": [
                            {
                                "visibility": "on"
                            },
                            {
                                "color": "#ffffff"
                            },
                            {
                                "lightness": 16
                            }
                        ]
                    },
                    {
                        "elementType": "labels.text.fill",
                        "stylers": [
                            {
                                "saturation": 36
                            },
                            {
                                "color": "#333333"
                            },
                            {
                                "lightness": 40
                            }
                        ]
                    },
                    {
                        "elementType": "labels.icon",
                        "stylers": [
                            {
                                "visibility": "off"
                            }
                        ]
                    },
                    {
                        "featureType": "transit",
                        "elementType": "geometry",
                        "stylers": [
                            {
                                "color": "#f2f2f2"
                            },
                            {
                                "lightness": 19
                            }
                        ]
                    },
                    {
                        "featureType": "administrative",
                        "elementType": "geometry.fill",
                        "stylers": [
                            {
                                "color": "#fefefe"
                            },
                            {
                                "lightness": 20
                            }
                        ]
                    },
                    {
                        "featureType": "administrative",
                        "elementType": "geometry.stroke",
                        "stylers": [
                            {
                                "color": "#fefefe"
                            },
                            {
                                "lightness": 17
                            },
                            {
                                "weight": 1.2
                            }
                        ]
                    }
                ]
            });
            
            // Add marker
            const marker = new google.maps.Marker({
                position: { lat: latitude, lng: longitude },
                map: map,
                animation: google.maps.Animation.DROP
            });
            
            // Add info window
            const infoWindow = new google.maps.InfoWindow({
                content: mapContainer.getAttribute('data-info') || 'Our Location'
            });
            
            // Open info window on marker click
            marker.addListener('click', function() {
                infoWindow.open(map, marker);
            });
        },
        
        /**
         * Initialize animations
         */
        initAnimations: function() {
            const self = this;
            const animationElements = $('.aqualuxe-contact-form__form-container, .aqualuxe-contact-form__info');
            
            if (!animationElements.length) {
                return;
            }
            
            // Animate elements when they come into view
            $(window).on('scroll', function() {
                animationElements.each(function() {
                    const $element = $(this);
                    
                    if (self.isElementInViewport($element[0]) && !$element.hasClass('animated')) {
                        $element.addClass('animated');
                    }
                });
            }).trigger('scroll');
        },
        
        /**
         * Check if element is in viewport
         * 
         * @param {Element} el - The element to check
         * @return {boolean} - Whether the element is in viewport
         */
        isElementInViewport: function(el) {
            if (!el) {
                return false;
            }
            
            const rect = el.getBoundingClientRect();
            
            return (
                rect.top >= 0 &&
                rect.left >= 0 &&
                rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
                rect.right <= (window.innerWidth || document.documentElement.clientWidth)
            );
        }
    };
    
    // Initialize on document ready
    $(document).ready(function() {
        ContactForm.init();
    });
    
})(jQuery);