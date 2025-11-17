// Wishlist JS for AquaLuxe theme
jQuery(function($) {
    $(document).on('click', '.aqualuxe-wishlist-btn', function(e) {
        e.preventDefault();
        var $btn = $(this);
        var productId = $btn.data('product-id');
        if (!productId) return;
        $btn.prop('disabled', true);
        $.post(aqualuxe_wishlist.ajax_url, {
            action: 'aqualuxe_wishlist_toggle',
            product_id: productId,
            nonce: aqualuxe_wishlist.nonce
        }, function(response) {
            $btn.prop('disabled', false);
            if (response.success) {
                if (response.data.action === 'added') {
                    $btn.addClass('in-wishlist');
                    $btn.find('.text').text('Remove from Wishlist');
                } else {
                    $btn.removeClass('in-wishlist');
                    $btn.find('.text').text('Add to Wishlist');
                }
                // Trigger event for header count update
                document.body.dispatchEvent(new CustomEvent('wishlist:updated'));
            }
        });
    });
});
