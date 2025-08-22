/**
 * AquaLuxe Theme - Social Sharing Module JavaScript
 */

(function($) {
    'use strict';

    // Social Sharing Module
    const AquaLuxeSocialSharing = {
        /**
         * Initialize the social sharing functionality
         */
        init: function() {
            this.bindEvents();
            this.initShareCounts();
        },

        /**
         * Bind events
         */
        bindEvents: function() {
            // Handle share button clicks
            $(document).on('click', '.aqualuxe-social-sharing__button', this.handleShareClick);
        },

        /**
         * Handle share button click
         * 
         * @param {Event} e - Click event
         */
        handleShareClick: function(e) {
            const $button = $(this);
            const network = $button.data('network');
            
            // Skip for email and print
            if (network === 'email' || network === 'print') {
                return true;
            }
            
            e.preventDefault();
            
            const url = $button.attr('href');
            const windowWidth = 550;
            const windowHeight = 450;
            const windowLeft = (screen.width / 2) - (windowWidth / 2);
            const windowTop = (screen.height / 2) - (windowHeight / 2);
            
            // Open share dialog
            window.open(
                url,
                'share-dialog',
                'width=' + windowWidth + ',height=' + windowHeight + ',left=' + windowLeft + ',top=' + windowTop + ',menubar=no,toolbar=no,resizable=yes,scrollbars=yes'
            );
            
            // Track share event
            AquaLuxeSocialSharing.trackShareEvent(network);
        },

        /**
         * Initialize share counts
         */
        initShareCounts: function() {
            const $buttons = $('.aqualuxe-social-sharing__button[data-show-count="true"]');
            
            if ($buttons.length === 0) {
                return;
            }
            
            // Group buttons by URL to minimize API calls
            const urlGroups = {};
            
            $buttons.each(function() {
                const $button = $(this);
                const network = $button.data('network');
                const url = $button.data('url');
                
                if (!url || !network) {
                    return;
                }
                
                if (!urlGroups[url]) {
                    urlGroups[url] = [];
                }
                
                urlGroups[url].push({
                    network: network,
                    element: $button
                });
            });
            
            // Fetch share counts for each URL
            $.each(urlGroups, function(url, buttons) {
                AquaLuxeSocialSharing.fetchShareCounts(url, buttons);
            });
        },

        /**
         * Fetch share counts for a URL
         * 
         * @param {string} url - URL to fetch share counts for
         * @param {Array} buttons - Array of button objects
         */
        fetchShareCounts: function(url, buttons) {
            // Group buttons by network
            const networkGroups = {};
            
            buttons.forEach(function(button) {
                if (!networkGroups[button.network]) {
                    networkGroups[button.network] = [];
                }
                
                networkGroups[button.network].push(button.element);
            });
            
            // Fetch share counts for each network
            $.each(networkGroups, function(network, elements) {
                switch (network) {
                    case 'facebook':
                        AquaLuxeSocialSharing.fetchFacebookShareCount(url, elements);
                        break;
                    case 'pinterest':
                        AquaLuxeSocialSharing.fetchPinterestShareCount(url, elements);
                        break;
                    // Add more networks as needed
                }
            });
        },

        /**
         * Fetch Facebook share count
         * 
         * @param {string} url - URL to fetch share count for
         * @param {Array} elements - Array of button elements
         */
        fetchFacebookShareCount: function(url, elements) {
            // Facebook requires an App ID and Graph API, which is beyond the scope of this example
            // This would typically be implemented server-side for security reasons
            
            // For demonstration purposes, we'll simulate a share count
            setTimeout(function() {
                const count = Math.floor(Math.random() * 100);
                AquaLuxeSocialSharing.updateShareCount(elements, count);
            }, 500);
        },

        /**
         * Fetch Pinterest share count
         * 
         * @param {string} url - URL to fetch share count for
         * @param {Array} elements - Array of button elements
         */
        fetchPinterestShareCount: function(url, elements) {
            $.ajax({
                url: 'https://api.pinterest.com/v1/urls/count.json',
                dataType: 'jsonp',
                data: {
                    url: url
                },
                success: function(data) {
                    const count = data.count || 0;
                    AquaLuxeSocialSharing.updateShareCount(elements, count);
                }
            });
        },

        /**
         * Update share count
         * 
         * @param {Array} elements - Array of button elements
         * @param {number} count - Share count
         */
        updateShareCount: function(elements, count) {
            if (count <= 0) {
                return;
            }
            
            const formattedCount = AquaLuxeSocialSharing.formatCount(count);
            
            elements.forEach(function($element) {
                let $count = $element.find('.aqualuxe-social-sharing__count');
                
                if ($count.length === 0) {
                    $count = $('<span class="aqualuxe-social-sharing__count"></span>');
                    $element.append($count);
                }
                
                $count.text(formattedCount);
            });
        },

        /**
         * Format count number
         * 
         * @param {number} count - Count to format
         * @return {string} Formatted count
         */
        formatCount: function(count) {
            if (count >= 1000000) {
                return Math.floor(count / 1000000) + 'M';
            } else if (count >= 1000) {
                return Math.floor(count / 1000) + 'K';
            }
            
            return count.toString();
        },

        /**
         * Track share event
         * 
         * @param {string} network - Social network
         */
        trackShareEvent: function(network) {
            // Track with Google Analytics if available
            if (typeof ga !== 'undefined') {
                ga('send', 'event', 'Social', 'Share', network);
            }
            
            // Track with Google Tag Manager if available
            if (typeof dataLayer !== 'undefined') {
                dataLayer.push({
                    'event': 'socialShare',
                    'socialNetwork': network
                });
            }
        }
    };

    // Initialize on document ready
    $(document).ready(function() {
        AquaLuxeSocialSharing.init();
    });

})(jQuery);