/**
 * AquaLuxe Auction System
 * Handles auction functionality including bidding and countdown timer
 */

(function($) {
  'use strict';
  
  // Auction System Object
  const AquaLuxeAuction = {
    // Cache DOM elements
    dom: {
      auctionContainer: $('.auction-container'),
      countdownTimer: $('.auction-countdown'),
      currentBid: $('.auction-current-bid'),
      bidForm: $('.auction-bid-form'),
      bidAmount: $('.auction-bid-amount'),
      bidButton: $('.auction-bid-button'),
      bidResult: $('.auction-bid-result'),
      bidHistory: $('.auction-bid-history'),
      loginForm: $('.auction-login-form'),
      guestForm: $('.auction-guest-form'),
      loadingOverlay: $('.auction-loading')
    },
    
    // Initialize auction system
    init: function() {
      const self = this;
      
      // Skip if auction container doesn't exist
      if (!this.dom.auctionContainer.length) return;
      
      // Initialize countdown timer
      if (this.dom.countdownTimer.length) {
        this.initCountdown();
      }
      
      // Handle bid form submission
      if (this.dom.bidForm.length) {
        this.dom.bidForm.on('submit', function(e) {
          e.preventDefault();
          self.submitBid();
        });
      }
      
      // Handle bid amount input
      if (this.dom.bidAmount.length) {
        this.dom.bidAmount.on('input', function() {
          self.validateBidAmount();
        });
      }
      
      // Toggle between login and guest forms
      $('.auction-toggle-form').on('click', function(e) {
        e.preventDefault();
        self.toggleBidForm();
      });
      
      // Refresh auction data periodically
      this.startRefreshTimer();
    },
    
    // Initialize countdown timer
    initCountdown: function() {
      const self = this;
      const $timer = this.dom.countdownTimer;
      
      // Skip if countdown element doesn't exist or no end date
      if (!$timer.length || !$timer.data('end-date')) return;
      
      const endDate = $timer.data('end-date');
      
      // Initialize countdown
      $timer.countdown(endDate, function(event) {
        // Format countdown display
        $(this).html(event.strftime(
          '<div class="auction-countdown-item"><span class="auction-countdown-value">%D</span><span class="auction-countdown-label">' + aqualuxeAuctionSettings.i18n.days + '</span></div>' +
          '<div class="auction-countdown-item"><span class="auction-countdown-value">%H</span><span class="auction-countdown-label">' + aqualuxeAuctionSettings.i18n.hours + '</span></div>' +
          '<div class="auction-countdown-item"><span class="auction-countdown-value">%M</span><span class="auction-countdown-label">' + aqualuxeAuctionSettings.i18n.minutes + '</span></div>' +
          '<div class="auction-countdown-item"><span class="auction-countdown-value">%S</span><span class="auction-countdown-label">' + aqualuxeAuctionSettings.i18n.seconds + '</span></div>'
        ));
      }).on('finish.countdown', function() {
        // When countdown finishes
        $(this).html('<div class="auction-ended">Auction has ended</div>');
        
        // Disable bid form
        self.dom.bidForm.addClass('disabled');
        self.dom.bidButton.prop('disabled', true);
        
        // Reload page after a short delay to show final status
        setTimeout(function() {
          location.reload();
        }, 5000);
      });
    },
    
    // Validate bid amount
    validateBidAmount: function() {
      const bidAmount = parseFloat(this.dom.bidAmount.val());
      const minBid = parseFloat(this.dom.bidAmount.data('min-bid'));
      
      if (isNaN(bidAmount) || bidAmount < minBid) {
        this.dom.bidButton.prop('disabled', true);
        this.dom.bidAmount.addClass('error');
        return false;
      } else {
        this.dom.bidButton.prop('disabled', false);
        this.dom.bidAmount.removeClass('error');
        return true;
      }
    },
    
    // Toggle between login and guest forms
    toggleBidForm: function() {
      this.dom.loginForm.toggleClass('hidden');
      this.dom.guestForm.toggleClass('hidden');
    },
    
    // Submit bid
    submitBid: function() {
      const self = this;
      
      // Validate bid amount
      if (!this.validateBidAmount()) {
        return;
      }
      
      // Show loading overlay
      this.dom.loadingOverlay.removeClass('hidden');
      
      // Get form data
      const formData = this.dom.bidForm.serialize();
      
      // Submit bid to the server
      $.ajax({
        url: aqualuxeAuctionSettings.ajaxUrl,
        type: 'POST',
        data: formData + '&action=aqualuxe_place_bid&nonce=' + aqualuxeAuctionSettings.nonce,
        success: function(response) {
          // Hide loading overlay
          self.dom.loadingOverlay.addClass('hidden');
          
          if (response.success) {
            // Show success message
            self.showBidResult(true, response.data.message);
            
            // Update current bid
            self.dom.currentBid.text(response.data.formatted);
            
            // Update minimum bid
            self.dom.bidAmount.data('min-bid', response.data.next_bid);
            self.dom.bidAmount.attr('min', response.data.next_bid);
            self.dom.bidAmount.attr('placeholder', response.data.next_formatted);
            
            // Clear bid amount
            self.dom.bidAmount.val('');
            
            // Refresh bid history
            self.refreshBidHistory();
          } else {
            // Show error message
            self.showBidResult(false, response.data.message);
          }
        },
        error: function(xhr, status, error) {
          // Hide loading overlay
          self.dom.loadingOverlay.addClass('hidden');
          
          // Show error message
          self.showBidResult(false, 'An error occurred while processing your bid. Please try again.');
          console.error('AJAX error:', error);
        }
      });
    },
    
    // Show bid result message
    showBidResult: function(success, message) {
      const resultElement = this.dom.bidResult;
      
      // Skip if result element doesn't exist
      if (!resultElement.length) return;
      
      // Set message and class
      resultElement.text(message);
      resultElement.removeClass('hidden success error');
      resultElement.addClass(success ? 'success' : 'error');
      
      // Scroll to result message
      $('html, body').animate({
        scrollTop: resultElement.offset().top - 100
      }, 500);
      
      // Hide message after 10 seconds if it's a success message
      if (success) {
        setTimeout(function() {
          resultElement.addClass('hidden');
        }, 10000);
      }
    },
    
    // Refresh bid history
    refreshBidHistory: function() {
      const self = this;
      const $history = this.dom.bidHistory;
      
      // Skip if history element doesn't exist
      if (!$history.length) return;
      
      // Get auction ID
      const auctionId = $history.data('auction-id');
      
      // Fetch bid history from the server
      $.ajax({
        url: aqualuxeAuctionSettings.ajaxUrl,
        type: 'POST',
        data: {
          action: 'aqualuxe_get_bid_history',
          auction_id: auctionId,
          nonce: aqualuxeAuctionSettings.nonce
        },
        success: function(response) {
          if (response.success) {
            // Update bid history
            $history.html(response.data.html);
          }
        }
      });
    },
    
    // Start refresh timer
    startRefreshTimer: function() {
      const self = this;
      
      // Refresh auction data every 30 seconds
      setInterval(function() {
        self.refreshAuctionData();
      }, 30000);
    },
    
    // Refresh auction data
    refreshAuctionData: function() {
      const self = this;
      const $container = this.dom.auctionContainer;
      
      // Skip if container doesn't exist
      if (!$container.length) return;
      
      // Get auction ID
      const auctionId = $container.data('auction-id');
      
      // Fetch auction data from the server
      $.ajax({
        url: aqualuxeAuctionSettings.ajaxUrl,
        type: 'POST',
        data: {
          action: 'aqualuxe_get_auction_data',
          auction_id: auctionId,
          nonce: aqualuxeAuctionSettings.nonce
        },
        success: function(response) {
          if (response.success) {
            // Update current bid
            if (self.dom.currentBid.length) {
              self.dom.currentBid.text(response.data.current_bid_formatted);
            }
            
            // Update minimum bid
            if (self.dom.bidAmount.length) {
              self.dom.bidAmount.data('min-bid', response.data.min_bid);
              self.dom.bidAmount.attr('min', response.data.min_bid);
              self.dom.bidAmount.attr('placeholder', response.data.min_bid_formatted);
            }
            
            // Refresh bid history
            self.refreshBidHistory();
          }
        }
      });
    }
  };
  
  // Initialize auction system when document is ready
  $(document).ready(function() {
    AquaLuxeAuction.init();
  });
  
})(jQuery);