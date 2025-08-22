/**
 * Auctions module admin scripts
 *
 * @package AquaLuxe
 * @subpackage Modules
 */

(function($) {
    'use strict';

    /**
     * Auction admin functionality
     */
    var AuctionAdmin = {
        /**
         * Initialize admin functionality
         */
        init: function() {
            var self = this;
            
            // Initialize datetime pickers
            self.initDateTimePickers();
            
            // Initialize auction product type
            self.initProductType();
            
            // Initialize auction actions
            self.initActions();
        },
        
        /**
         * Initialize datetime pickers
         */
        initDateTimePickers: function() {
            // Check if jQuery UI datepicker is available
            if ($.fn.datepicker) {
                $('.datetimepicker').each(function() {
                    var $input = $(this);
                    
                    $input.datepicker({
                        dateFormat: aqualuxeAuctionsAdmin.i18n.date_format,
                        timeFormat: aqualuxeAuctionsAdmin.i18n.time_format,
                        showSecond: false,
                        showMillisec: false,
                        showMicrosec: false,
                        showTimezone: false,
                        controlType: 'select',
                        oneLine: true
                    });
                });
            }
        },
        
        /**
         * Initialize auction product type
         */
        initProductType: function() {
            // Hide irrelevant product fields for auction products
            $('body').on('woocommerce-product-type-change', function(event, select_val) {
                if (select_val === 'auction') {
                    // Hide these fields
                    $('.general_options, .inventory_options, .shipping_options').hide();
                    $('._regular_price_field, .sale_price_field, ._sale_price_dates_field').hide();
                    $('._manage_stock_field, ._stock_field, ._backorders_field, ._stock_status_field').hide();
                    $('._sold_individually_field, .product_shipping_class_field').hide();
                    
                    // Show auction fields
                    $('.show_if_auction').show();
                } else {
                    $('.show_if_auction').hide();
                }
            });
            
            // Trigger change on page load
            $('select#product-type').trigger('change');
            
            // Show/hide buy now price field
            function toggleBuyNowPrice() {
                if ($('#_auction_allow_buy_now').is(':checked')) {
                    $('.show_if_allow_buy_now').show();
                } else {
                    $('.show_if_allow_buy_now').hide();
                }
            }
            
            $('#_auction_allow_buy_now').on('change', toggleBuyNowPrice);
            toggleBuyNowPrice();
        },
        
        /**
         * Initialize auction actions
         */
        initActions: function() {
            var self = this;
            
            // End auction action
            $('.end-auction').on('click', function(e) {
                e.preventDefault();
                
                var $button = $(this);
                var productId = $button.data('product-id');
                
                if (confirm(aqualuxeAuctionsAdmin.i18n.confirm_end)) {
                    self.endAuction(productId, $button);
                }
            });
            
            // Delete bid action
            $('.delete-bid').on('click', function(e) {
                e.preventDefault();
                
                var $button = $(this);
                var bidId = $button.data('bid-id');
                
                if (confirm(aqualuxeAuctionsAdmin.i18n.confirm_delete)) {
                    self.deleteBid(bidId, $button);
                }
            });
            
            // Set winner action
            $('.set-winner').on('click', function(e) {
                e.preventDefault();
                
                var $button = $(this);
                var productId = $button.data('product-id');
                var userId = $button.data('user-id');
                
                if (confirm(aqualuxeAuctionsAdmin.i18n.confirm_winner)) {
                    self.setWinner(productId, userId, $button);
                }
            });
        },
        
        /**
         * End auction
         *
         * @param {number} productId Product ID
         * @param {jQuery} $button Button element
         */
        endAuction: function(productId, $button) {
            $button.prop('disabled', true).text('Processing...');
            
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'aqualuxe_admin_end_auction',
                    product_id: productId,
                    nonce: aqualuxeAuctionsAdmin.nonce
                },
                success: function(response) {
                    if (response.success) {
                        alert(response.data.message);
                        location.reload();
                    } else {
                        alert(response.data.message);
                        $button.prop('disabled', false).text('End Auction');
                    }
                },
                error: function() {
                    alert('An error occurred. Please try again.');
                    $button.prop('disabled', false).text('End Auction');
                }
            });
        },
        
        /**
         * Delete bid
         *
         * @param {number} bidId Bid ID
         * @param {jQuery} $button Button element
         */
        deleteBid: function(bidId, $button) {
            $button.prop('disabled', true).text('Processing...');
            
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'aqualuxe_admin_delete_bid',
                    bid_id: bidId,
                    nonce: aqualuxeAuctionsAdmin.nonce
                },
                success: function(response) {
                    if (response.success) {
                        alert(response.data.message);
                        location.reload();
                    } else {
                        alert(response.data.message);
                        $button.prop('disabled', false).text('Delete Bid');
                    }
                },
                error: function() {
                    alert('An error occurred. Please try again.');
                    $button.prop('disabled', false).text('Delete Bid');
                }
            });
        },
        
        /**
         * Set winner
         *
         * @param {number} productId Product ID
         * @param {number} userId User ID
         * @param {jQuery} $button Button element
         */
        setWinner: function(productId, userId, $button) {
            $button.prop('disabled', true).text('Processing...');
            
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'aqualuxe_admin_set_winner',
                    product_id: productId,
                    user_id: userId,
                    nonce: aqualuxeAuctionsAdmin.nonce
                },
                success: function(response) {
                    if (response.success) {
                        alert(response.data.message);
                        location.reload();
                    } else {
                        alert(response.data.message);
                        $button.prop('disabled', false).text('Set as Winner');
                    }
                },
                error: function() {
                    alert('An error occurred. Please try again.');
                    $button.prop('disabled', false).text('Set as Winner');
                }
            });
        }
    };
    
    /**
     * Initialize on document ready
     */
    $(document).ready(function() {
        AuctionAdmin.init();
    });
    
})(jQuery);