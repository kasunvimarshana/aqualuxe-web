// Country selector enhancement
(function($) {
    'use strict';
    
    const CountrySelector = {
        init: function() {
            this.enhanceCountryFields();
        },
        
        enhanceCountryFields: function() {
            // Enhance country select fields
            $('.country_select').each(function() {
                const $select = $(this);
                
                // Add flag icons if available
                $select.on('change', function() {
                    const countryCode = $(this).val();
                    console.log('Country changed to:', countryCode);
                    
                    // Could add flag display here
                });
            });
        }
    };
    
    $(document).ready(function() {
        CountrySelector.init();
    });
    
})(jQuery);