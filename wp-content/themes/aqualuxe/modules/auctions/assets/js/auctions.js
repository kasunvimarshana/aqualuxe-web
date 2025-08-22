/**
 * Auctions module scripts
 *
 * @package AquaLuxe
 * @subpackage Modules
 */

(function($) {
    'use strict';

    /**
     * Auction countdown timer
     */
    var AuctionCountdown = {
        /**
         * Initialize countdown timers
         */
        init: function() {
            var self = this;
            
            // Find all auction time remaining elements
            $('.auction-time-remaining').each(function() {
                var $element = $(this);
                var seconds = parseInt($element.data('seconds'), 10);
                
                if (seconds > 0) {
                    // Set initial time
                    self.updateTimer($element, seconds);
                    
                    // Start countdown
                    var timerId = setInterval(function() {
                        seconds--;
                        
                        if (seconds <= 0) {
                            clearInterval(timerId);
                            self.auctionEnded($element);
                        } else {
                            self.updateTimer($element, seconds);
                        }
                    }, 1000);
                    
                    // Store timer ID
                    $element.data('timer-id', timerId);
                } else {
                    self.auctionEnded($element);
                }
            });
        },
        
        /**
         * Update timer display
         *
         * @param {jQuery} $element Timer element
         * @param {number} seconds Seconds remaining
         */
        updateTimer: function($element, seconds) {
            var days = Math.floor(seconds / 86400);
            var hours = Math.floor((seconds % 86400) / 3600);
            var minutes = Math.floor((seconds % 3600) / 60);
            var secs = seconds % 60;
            
            var timeString = '';
            
            if (days > 0) {
                timeString += days + ' ' + (days === 1 ? aqualuxeAuctions.i18n.days.replace('days', 'day') : aqualuxeAuctions.i18n.days) + ' ';
            }
            
            if (hours > 0 || days > 0) {
                timeString += hours + ' ' + (hours === 1 ? aqualuxeAuctions.i18n.hours.replace('hours', 'hour') : aqualuxeAuctions.i18n.hours) + ' ';
            }
            
            if (minutes > 0 || hours > 0 || days > 0) {
                timeString += minutes + ' ' + (minutes === 1 ? aqualuxeAuctions.i18n.minutes.replace('minutes', 'minute') : aqualuxeAuctions.i18n.minutes) + ' ';
            }
            
            timeString += secs + ' ' + (secs === 1 ? aqualuxeAuctions.i18n.seconds.replace('seconds', 'second') : aqualuxeAuctions.i18n.seconds);
            
            $element.find('.value').text(timeString);
        },
        
        /**
         * Handle auction ended
         *
         * @param {jQuery} $element Timer element
         */
        auctionEnded: function($element) {
            $element.find('.value').text(aqualuxeAuctions.i18n.auction_ended);
            $element.closest('.product-type-auction').addClass('auction-ended');
            
            // Reload page after a short delay
            setTimeout(function() {
                location.reload();
            }, 3000);
        }
    };
    
    /**
     * Auction bidding
     */
    var AuctionBidding = {
        /**
         * Initialize bidding
         */
        init: function() {
            var self = this;
            
            // Handle bid form submission
            $('.auction-bid-form form').on('submit', function(e) {
                e.preventDefault();
                self.placeBid($(this));
            });
            
            // Set up bid amount validation
            $('.auction-bid-form input[name="bid_amount"]').on('change', function() {
                self.validateBidAmount($(this));
            });
            
            // Set up auto-refresh
            if (aqualuxeAuctions.refreshInterval > 0) {
                setInterval(function() {
                    self.refreshAuctionData();
                }, aqualuxeAuctions.refreshInterval * 1000);
            }
        },
        
        /**
         * Place bid
         *
         * @param {jQuery} $form Bid form
         */
        placeBid: function($form) {
            var self = this;
            var $response = $form.find('.auction-bid-response');
            var bidAmount = parseFloat($form.find('input[name="bid_amount"]').val());
            var productId = parseInt($form.find('input[name="product_id"]').val(), 10);
            var nonce = $form.find('input[name="auction_nonce"]').val();
            
            // Validate bid amount
            if (!self.validateBidAmount($form.find('input[name="bid_amount"]'))) {
                return;
            }
            
            // Confirm bid
            if (!confirm(aqualuxeAuctions.i18n.confirm_bid)) {
                return;
            }
            
            // Disable form
            $form.find('button').prop('disabled', true).text('Processing...');
            
            // Send AJAX request
            $.ajax({
                url: aqualuxeAuctions.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'aqualuxe_place_bid',
                    product_id: productId,
                    bid_amount: bidAmount,
                    auction_nonce: nonce
                },
                success: function(response) {
                    if (response.success) {
                        // Show success message
                        $response.removeClass('error').addClass('success').text(response.data.message).show();
                        
                        // Update bid form
                        $form.find('input[name="bid_amount"]').val(response.data.minimum_bid);
                        $form.find('.auction-current-bid .value').text(response.data.current_price);
                        $form.find('.auction-minimum-bid .value').text(aqualuxeAuctions.currencySymbol + response.data.minimum_bid);
                        
                        // Update auction information
                        $('.auction-information .auction-current-bid .value').text(response.data.current_price);
                        $('.auction-information .auction-bid-count .value').text(response.data.bid_count);
                        
                        // Add highest bidder message if not already present
                        if (!$form.find('.auction-highest-bidder').length) {
                            $form.prepend('<p class="auction-highest-bidder">' + aqualuxeAuctions.i18n.bid_placed + '</p>');
                        }
                        
                        // Reload page after a short delay
                        setTimeout(function() {
                            location.reload();
                        }, 2000);
                    } else {
                        // Show error message
                        $response.removeClass('success').addClass('error').text(response.data.message).show();
                    }
                    
                    // Re-enable form
                    $form.find('button').prop('disabled', false).text('Place Bid');
                },
                error: function() {
                    // Show error message
                    $response.removeClass('success').addClass('error').text(aqualuxeAuctions.i18n.bid_error).show();
                    
                    // Re-enable form
                    $form.find('button').prop('disabled', false).text('Place Bid');
                }
            });
        },
        
        /**
         * Validate bid amount
         *
         * @param {jQuery} $input Bid amount input
         * @return {boolean} Whether bid amount is valid
         */
        validateBidAmount: function($input) {
            var bidAmount = parseFloat($input.val());
            var minBid = parseFloat($input.attr('min'));
            
            if (isNaN(bidAmount) || bidAmount < minBid) {
                $input.addClass('error');
                $input.closest('form').find('.auction-bid-response')
                    .removeClass('success')
                    .addClass('error')
                    .text(aqualuxeAuctions.i18n.bid_too_low)
                    .show();
                return false;
            } else {
                $input.removeClass('error');
                $input.closest('form').find('.auction-bid-response').hide();
                return true;
            }
        },
        
        /**
         * Refresh auction data
         */
        refreshAuctionData: function() {
            // Only refresh on auction product pages
            if (!$('.product-type-auction').length) {
                return;
            }
            
            var productId = $('.auction-bid-form input[name="product_id"]').val();
            
            if (!productId) {
                return;
            }
            
            // Send AJAX request
            $.ajax({
                url: aqualuxeAuctions.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'aqualuxe_refresh_auction',
                    product_id: productId,
                    nonce: aqualuxeAuctions.nonce
                },
                success: function(response) {
                    if (response.success) {
                        // Update auction information
                        $('.auction-information .auction-current-bid .value').text(response.data.current_price);
                        $('.auction-information .auction-bid-count .value').text(response.data.bid_count);
                        
                        // Update bid form
                        $('.auction-bid-form .auction-current-bid .value').text(response.data.current_price);
                        $('.auction-bid-form .auction-minimum-bid .value').text(response.data.minimum_price);
                        $('.auction-bid-form input[name="bid_amount"]').attr('min', response.data.minimum_bid).val(response.data.minimum_bid);
                        
                        // Update highest bidder status
                        if (response.data.is_highest_bidder) {
                            if (!$('.auction-bid-form .auction-highest-bidder').length) {
                                $('.auction-bid-form').prepend('<p class="auction-highest-bidder">' + aqualuxeAuctions.i18n.highest_bidder + '</p>');
                            }
                        } else {
                            $('.auction-bid-form .auction-highest-bidder').remove();
                        }
                        
                        // Update bid history if available
                        if (response.data.bid_history_html) {
                            $('.auction-bid-history').html(response.data.bid_history_html);
                        }
                        
                        // Check if auction has ended
                        if (response.data.has_ended) {
                            location.reload();
                        }
                    }
                }
            });
        }
    };
    
    /**
     * Initialize on document ready
     */
    $(document).ready(function() {
        AuctionCountdown.init();
        AuctionBidding.init();
    });
    
})(jQuery);