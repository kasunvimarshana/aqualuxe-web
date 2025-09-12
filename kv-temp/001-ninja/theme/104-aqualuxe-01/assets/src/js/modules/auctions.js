/**
 * Auctions Module JavaScript
 * 
 * Handles auction bidding and trade-in functionality
 */

(function($) {
    'use strict';

    const AuctionModule = {
        
        /**
         * Initialize module
         */
        init() {
            this.bindEvents();
            this.initCountdowns();
            this.updateBidDisplay();
        },

        /**
         * Bind events
         */
        bindEvents() {
            // Bid form submission
            $(document).on('submit', '.bid-form', this.handleBidSubmission.bind(this));
            
            // Trade-in form submission
            $(document).on('submit', '.trade-in-form', this.handleTradeInSubmission.bind(this));
            
            // Bid amount validation
            $(document).on('input', '.bid-amount-input', this.validateBidAmount.bind(this));
            
            // Quick bid buttons
            $(document).on('click', '.quick-bid-btn', this.handleQuickBid.bind(this));
        },

        /**
         * Handle bid submission
         */
        handleBidSubmission(e) {
            e.preventDefault();
            
            const $form = $(e.currentTarget);
            const $submitBtn = $form.find('button[type="submit"]');
            const originalText = $submitBtn.text();
            
            const bidData = {
                action: 'place_bid',
                nonce: aqualuxeAuctions.nonce,
                auction_id: $form.find('input[name="auction_id"]').val(),
                bid_amount: parseFloat($form.find('input[name="bid_amount"]').val())
            };

            // Validate bid amount
            if (!this.isValidBidAmount(bidData.auction_id, bidData.bid_amount)) {
                this.showError(aqualuxeAuctions.messages.minBidError);
                return;
            }

            // Show loading state
            $submitBtn.prop('disabled', true).text('Placing Bid...');

            $.ajax({
                url: aqualuxeAuctions.ajaxUrl,
                type: 'POST',
                data: bidData,
                success: (response) => {
                    if (response.success) {
                        this.showSuccess(response.data.message);
                        this.updateAuctionDisplay(bidData.auction_id, response.data);
                        $form[0].reset();
                    } else {
                        this.showError(response.data.message);
                    }
                },
                error: () => {
                    this.showError(aqualuxeAuctions.messages.bidError);
                },
                complete: () => {
                    $submitBtn.prop('disabled', false).text(originalText);
                }
            });
        },

        /**
         * Handle trade-in submission
         */
        handleTradeInSubmission(e) {
            e.preventDefault();
            
            const $form = $(e.currentTarget);
            const $submitBtn = $form.find('button[type="submit"]');
            const originalText = $submitBtn.text();
            
            const formData = new FormData($form[0]);
            formData.append('action', 'submit_trade_in');
            formData.append('nonce', aqualuxeAuctions.nonce);

            // Show loading state
            $submitBtn.prop('disabled', true).text('Submitting...');

            $.ajax({
                url: aqualuxeAuctions.ajaxUrl,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: (response) => {
                    if (response.success) {
                        this.showSuccess(response.data.message);
                        $form[0].reset();
                        
                        // Optionally redirect or show next steps
                        if (response.data.redirect) {
                            setTimeout(() => {
                                window.location.href = response.data.redirect;
                            }, 2000);
                        }
                    } else {
                        this.showError(response.data.message);
                    }
                },
                error: () => {
                    this.showError('Error submitting trade-in request. Please try again.');
                },
                complete: () => {
                    $submitBtn.prop('disabled', false).text(originalText);
                }
            });
        },

        /**
         * Validate bid amount
         */
        validateBidAmount(e) {
            const $input = $(e.currentTarget);
            const bidAmount = parseFloat($input.val());
            const auctionId = $input.closest('form').find('input[name="auction_id"]').val();
            
            if (bidAmount && !this.isValidBidAmount(auctionId, bidAmount)) {
                $input.addClass('error');
                this.showFieldError($input, 'Bid must be higher than current bid');
            } else {
                $input.removeClass('error');
                this.hideFieldError($input);
            }
        },

        /**
         * Handle quick bid buttons
         */
        handleQuickBid(e) {
            e.preventDefault();
            
            const $btn = $(e.currentTarget);
            const bidAmount = parseFloat($btn.data('amount'));
            const $form = $btn.closest('.auction-bidding');
            const $input = $form.find('.bid-amount-input');
            
            $input.val(bidAmount).trigger('input');
        },

        /**
         * Check if bid amount is valid
         */
        isValidBidAmount(auctionId, bidAmount) {
            const $auction = $('.auction[data-auction-id="' + auctionId + '"]');
            const currentBid = parseFloat($auction.find('.current-bid').data('amount') || 0);
            const minIncrement = parseFloat($auction.data('min-increment') || 1);
            
            return bidAmount >= (currentBid + minIncrement);
        },

        /**
         * Initialize countdown timers
         */
        initCountdowns() {
            $('.auction-countdown').each((index, element) => {
                const $countdown = $(element);
                const endTime = new Date($countdown.data('end-time')).getTime();
                
                this.startCountdown($countdown, endTime);
            });
        },

        /**
         * Start countdown timer
         */
        startCountdown($countdown, endTime) {
            const timer = setInterval(() => {
                const now = new Date().getTime();
                const timeLeft = endTime - now;

                if (timeLeft > 0) {
                    const days = Math.floor(timeLeft / (1000 * 60 * 60 * 24));
                    const hours = Math.floor((timeLeft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    const minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);

                    $countdown.html(`
                        <span class="time-unit">
                            <span class="time-value">${days}</span>
                            <span class="time-label">days</span>
                        </span>
                        <span class="time-unit">
                            <span class="time-value">${hours.toString().padStart(2, '0')}</span>
                            <span class="time-label">hours</span>
                        </span>
                        <span class="time-unit">
                            <span class="time-value">${minutes.toString().padStart(2, '0')}</span>
                            <span class="time-label">min</span>
                        </span>
                        <span class="time-unit">
                            <span class="time-value">${seconds.toString().padStart(2, '0')}</span>
                            <span class="time-label">sec</span>
                        </span>
                    `);
                } else {
                    // Auction ended
                    clearInterval(timer);
                    $countdown.html('<span class="auction-ended">Auction Ended</span>');
                    this.disableAuctionBidding($countdown.closest('.auction'));
                }
            }, 1000);
        },

        /**
         * Update auction display after bid
         */
        updateAuctionDisplay(auctionId, data) {
            const $auction = $('.auction[data-auction-id="' + auctionId + '"]');
            
            if (data.new_bid) {
                $auction.find('.current-bid').text(data.new_bid);
            }
            
            if (data.bid_count) {
                $auction.find('.bid-count').text(data.bid_count);
            }
            
            // Update minimum bid amount
            const $bidInput = $auction.find('.bid-amount-input');
            const currentBid = parseFloat($auction.find('.current-bid').data('amount'));
            const minIncrement = parseFloat($auction.data('min-increment') || 1);
            $bidInput.attr('min', currentBid + minIncrement);
        },

        /**
         * Update bid display on page load
         */
        updateBidDisplay() {
            $('.auction').each((index, element) => {
                const $auction = $(element);
                const $bidInput = $auction.find('.bid-amount-input');
                const currentBid = parseFloat($auction.find('.current-bid').data('amount') || 0);
                const minIncrement = parseFloat($auction.data('min-increment') || 1);
                
                $bidInput.attr('min', currentBid + minIncrement);
                $bidInput.attr('placeholder', 'Min: $' + (currentBid + minIncrement));
            });
        },

        /**
         * Disable auction bidding
         */
        disableAuctionBidding($auction) {
            $auction.find('.bid-form').hide();
            $auction.find('.auction-status').html('<span class="status-ended">Auction Ended</span>');
        },

        /**
         * Show success message
         */
        showSuccess(message) {
            this.showNotification(message, 'success');
        },

        /**
         * Show error message
         */
        showError(message) {
            this.showNotification(message, 'error');
        },

        /**
         * Show notification
         */
        showNotification(message, type = 'info') {
            const $notification = $(`
                <div class="auction-notification ${type}">
                    <span class="notification-message">${message}</span>
                    <button class="notification-close" aria-label="Close">&times;</button>
                </div>
            `);

            $('body').append($notification);

            // Auto remove after 5 seconds
            setTimeout(() => {
                $notification.fadeOut(300, function() {
                    $(this).remove();
                });
            }, 5000);

            // Manual close
            $notification.find('.notification-close').on('click', () => {
                $notification.fadeOut(300, function() {
                    $(this).remove();
                });
            });
        },

        /**
         * Show field error
         */
        showFieldError($field, message) {
            $field.siblings('.field-error').remove();
            $field.after(`<span class="field-error">${message}</span>`);
        },

        /**
         * Hide field error
         */
        hideFieldError($field) {
            $field.siblings('.field-error').remove();
        }
    };

    // Initialize when DOM is ready
    $(document).ready(() => {
        AuctionModule.init();
    });

})(jQuery);