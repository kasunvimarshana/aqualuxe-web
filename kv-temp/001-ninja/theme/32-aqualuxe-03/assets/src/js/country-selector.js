/**
 * AquaLuxe Theme - Country Selector
 *
 * Handles the country selection functionality for shipping and billing.
 */

(function($) {
    'use strict';

    $(document).ready(function() {
        // Check if we're on a page with country selectors
        if (!$('#billing_country, #shipping_country').length) {
            return;
        }

        // Initialize select2 for country selectors if available
        if (typeof $.fn.select2 !== 'undefined') {
            $('#billing_country, #shipping_country').select2({
                placeholder: 'Select a country',
                allowClear: true,
                width: '100%'
            });
        }

        // Handle country change for billing
        $('#billing_country').on('change', function() {
            const country = $(this).val();
            
            // Show/hide state field based on country
            if (country) {
                // Show loading indicator
                $('#billing_state_field').addClass('loading');
                
                // AJAX call to get states for selected country
                $.ajax({
                    url: aqualuxeSettings.ajaxUrl,
                    type: 'POST',
                    data: {
                        action: 'aqualuxe_get_states',
                        country: country,
                        nonce: aqualuxeSettings.nonce
                    },
                    success: function(response) {
                        if (response.success) {
                            // Update state field
                            updateStateField('#billing_state', response.data.states);
                        }
                        
                        // Remove loading indicator
                        $('#billing_state_field').removeClass('loading');
                    },
                    error: function() {
                        // Remove loading indicator
                        $('#billing_state_field').removeClass('loading');
                    }
                });
                
                // Update address format
                updateAddressFormat('billing', country);
            }
        });

        // Handle country change for shipping
        $('#shipping_country').on('change', function() {
            const country = $(this).val();
            
            // Show/hide state field based on country
            if (country) {
                // Show loading indicator
                $('#shipping_state_field').addClass('loading');
                
                // AJAX call to get states for selected country
                $.ajax({
                    url: aqualuxeSettings.ajaxUrl,
                    type: 'POST',
                    data: {
                        action: 'aqualuxe_get_states',
                        country: country,
                        nonce: aqualuxeSettings.nonce
                    },
                    success: function(response) {
                        if (response.success) {
                            // Update state field
                            updateStateField('#shipping_state', response.data.states);
                        }
                        
                        // Remove loading indicator
                        $('#shipping_state_field').removeClass('loading');
                    },
                    error: function() {
                        // Remove loading indicator
                        $('#shipping_state_field').removeClass('loading');
                    }
                });
                
                // Update address format
                updateAddressFormat('shipping', country);
            }
        });

        // Update state field based on country selection
        function updateStateField(fieldSelector, states) {
            const stateField = $(fieldSelector);
            const stateFieldWrapper = stateField.parent();
            const placeholder = stateField.attr('placeholder') || 'State / County';
            
            // Clear current field
            stateField.empty();
            
            // Check if states exist for this country
            if (states && Object.keys(states).length > 0) {
                // Convert select to dropdown if it's a text input
                if (stateField.is('input')) {
                    const selectField = $('<select></select>')
                        .attr('id', stateField.attr('id'))
                        .attr('name', stateField.attr('name'))
                        .attr('class', stateField.attr('class'));
                    
                    stateField.replaceWith(selectField);
                    stateField = selectField;
                }
                
                // Add placeholder option
                stateField.append(`<option value="">${placeholder}</option>`);
                
                // Add state options
                $.each(states, function(code, name) {
                    stateField.append(`<option value="${code}">${name}</option>`);
                });
                
                // Initialize select2 if available
                if (typeof $.fn.select2 !== 'undefined') {
                    stateField.select2({
                        placeholder: placeholder,
                        allowClear: true,
                        width: '100%'
                    });
                }
                
                // Show field
                stateFieldWrapper.show();
            } else {
                // Convert dropdown to text input if no states
                if (stateField.is('select')) {
                    const inputField = $('<input type="text" />')
                        .attr('id', stateField.attr('id'))
                        .attr('name', stateField.attr('name'))
                        .attr('class', stateField.attr('class'))
                        .attr('placeholder', placeholder);
                    
                    stateField.replaceWith(inputField);
                }
                
                // Hide field if empty
                stateFieldWrapper.hide();
            }
        }

        // Update address format based on country
        function updateAddressFormat(type, country) {
            // Show/hide postcode field based on country
            if (['US', 'CA', 'GB', 'AU', 'DE', 'FR', 'IT', 'ES', 'JP'].includes(country)) {
                $(`#${type}_postcode_field`).show();
            } else {
                $(`#${type}_postcode_field`).hide();
            }
            
            // Adjust field order based on country format
            if (['US', 'CA', 'AU'].includes(country)) {
                // City, State, Postcode format
                $(`#${type}_city_field`).insertBefore(`#${type}_state_field`);
                $(`#${type}_state_field`).insertBefore(`#${type}_postcode_field`);
            } else if (['GB', 'DE', 'FR', 'IT', 'ES'].includes(country)) {
                // Postcode, City format
                $(`#${type}_postcode_field`).insertBefore(`#${type}_city_field`);
                $(`#${type}_state_field`).insertAfter(`#${type}_city_field`);
            } else if (country === 'JP') {
                // Postcode, Prefecture, City format
                $(`#${type}_postcode_field`).insertBefore(`#${type}_state_field`);
                $(`#${type}_state_field`).insertBefore(`#${type}_city_field`);
            }
        }

        // Trigger change on page load to initialize fields
        $('#billing_country, #shipping_country').trigger('change');
    });

})(jQuery);