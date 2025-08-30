// Advanced Filtering UI logic for WooCommerce shop/archive pages
jQuery(function($) {
    // Listen for filter form changes
    $(document).on('change', '.aqualuxe-filter-form input, .aqualuxe-filter-form select', function() {
        var $form = $(this).closest('.aqualuxe-filter-form');
        var data = $form.serialize();
        // Optionally, update URL with query params for SEO
        var url = $form.attr('action') + '?' + data;
        window.location = url;
    });
    // Optionally, handle AJAX filtering (uncomment for AJAX)
    /*
    $(document).on('submit', '.aqualuxe-filter-form', function(e) {
        e.preventDefault();
        var $form = $(this);
        var data = $form.serialize();
        $.get(window.location.pathname, data, function(response) {
            // Replace product grid with new results
            $('.products').html($(response).find('.products').html());
        });
    });
    */
});
