/**
 * AquaLuxe API Admin JavaScript
 */
(function($) {
    'use strict';

    // Initialize admin functionality
    $(document).ready(function() {
        // Initialize tabs
        initTabs();
        
        // Initialize API test connection
        initTestConnection();
        
        // Initialize API key regeneration
        initRegenerateKeys();
        
        // Initialize log clearing
        initClearLogs();
        
        // Initialize documentation tabs
        initDocsTabs();
    });

    /**
     * Initialize tabs
     */
    function initTabs() {
        $('.aqualuxe-api-tabs-nav-item').on('click', function() {
            var tab = $(this).data('tab');
            
            // Update active tab
            $('.aqualuxe-api-tabs-nav-item').removeClass('active');
            $(this).addClass('active');
            
            // Show active tab content
            $('.aqualuxe-api-tabs-content').removeClass('active');
            $('#' + tab).addClass('active');
            
            // Update hidden input
            $('#aqualuxe_api_active_tab').val(tab);
        });
    }

    /**
     * Initialize API test connection
     */
    function initTestConnection() {
        $('#aqualuxe-api-test-connection').on('click', function(e) {
            e.preventDefault();
            
            var $button = $(this);
            var $loading = $button.next('.aqualuxe-api-loading');
            var $result = $('#aqualuxe-api-test-connection-result');
            
            // Show loading
            $button.prop('disabled', true);
            $loading.show();
            $result.html('');
            
            // Send AJAX request
            $.ajax({
                url: aqualuxe_api.ajax_url,
                type: 'POST',
                data: {
                    action: 'aqualuxe_api_test_connection',
                    nonce: aqualuxe_api.nonce
                },
                success: function(response) {
                    if (response.success) {
                        var html = '<div class="aqualuxe-api-notice success">';
                        html += '<p><strong>' + response.data.message + '</strong></p>';
                        html += '<p>API Version: ' + response.data.data.api_version + '</p>';
                        html += '<p>API Namespace: ' + response.data.data.api_namespace + '</p>';
                        html += '<p>Server Time: ' + response.data.data.server_time + '</p>';
                        html += '</div>';
                        
                        $result.html(html);
                    } else {
                        $result.html('<div class="aqualuxe-api-notice error"><p>' + response.data.message + '</p></div>');
                    }
                },
                error: function() {
                    $result.html('<div class="aqualuxe-api-notice error"><p>An error occurred while testing the API connection.</p></div>');
                },
                complete: function() {
                    // Hide loading
                    $button.prop('disabled', false);
                    $loading.hide();
                }
            });
        });
    }

    /**
     * Initialize API key regeneration
     */
    function initRegenerateKeys() {
        $('#aqualuxe-api-regenerate-keys').on('click', function(e) {
            e.preventDefault();
            
            // Confirm regeneration
            if (!confirm('Are you sure you want to regenerate the API keys? This will invalidate any existing keys.')) {
                return;
            }
            
            var $button = $(this);
            var $loading = $button.next('.aqualuxe-api-loading');
            var $result = $('#aqualuxe-api-regenerate-keys-result');
            
            // Show loading
            $button.prop('disabled', true);
            $loading.show();
            $result.html('');
            
            // Send AJAX request
            $.ajax({
                url: aqualuxe_api.ajax_url,
                type: 'POST',
                data: {
                    action: 'aqualuxe_api_regenerate_keys',
                    nonce: aqualuxe_api.nonce
                },
                success: function(response) {
                    if (response.success) {
                        var html = '<div class="aqualuxe-api-notice success">';
                        html += '<p><strong>' + response.data.message + '</strong></p>';
                        html += '</div>';
                        
                        $result.html(html);
                        
                        // Update key fields
                        $('#aqualuxe-api-key').val(response.data.key);
                        $('#aqualuxe-api-secret').val(response.data.secret);
                    } else {
                        $result.html('<div class="aqualuxe-api-notice error"><p>' + response.data.message + '</p></div>');
                    }
                },
                error: function() {
                    $result.html('<div class="aqualuxe-api-notice error"><p>An error occurred while regenerating the API keys.</p></div>');
                },
                complete: function() {
                    // Hide loading
                    $button.prop('disabled', false);
                    $loading.hide();
                }
            });
        });
    }

    /**
     * Initialize log clearing
     */
    function initClearLogs() {
        $('#aqualuxe-api-clear-logs').on('click', function(e) {
            e.preventDefault();
            
            // Confirm clearing
            if (!confirm('Are you sure you want to clear all API logs? This action cannot be undone.')) {
                return;
            }
            
            var $button = $(this);
            var $loading = $button.next('.aqualuxe-api-loading');
            var $result = $('#aqualuxe-api-clear-logs-result');
            
            // Show loading
            $button.prop('disabled', true);
            $loading.show();
            $result.html('');
            
            // Send AJAX request
            $.ajax({
                url: aqualuxe_api.ajax_url,
                type: 'POST',
                data: {
                    action: 'aqualuxe_api_clear_logs',
                    nonce: aqualuxe_api.nonce
                },
                success: function(response) {
                    if (response.success) {
                        var html = '<div class="aqualuxe-api-notice success">';
                        html += '<p><strong>' + response.data.message + '</strong></p>';
                        html += '</div>';
                        
                        $result.html(html);
                        
                        // Reload page after a delay
                        setTimeout(function() {
                            window.location.reload();
                        }, 1500);
                    } else {
                        $result.html('<div class="aqualuxe-api-notice error"><p>' + response.data.message + '</p></div>');
                    }
                },
                error: function() {
                    $result.html('<div class="aqualuxe-api-notice error"><p>An error occurred while clearing the API logs.</p></div>');
                },
                complete: function() {
                    // Hide loading
                    $button.prop('disabled', false);
                    $loading.hide();
                }
            });
        });
    }

    /**
     * Initialize documentation tabs
     */
    function initDocsTabs() {
        $('.aqualuxe-api-docs-nav-item').on('click', function() {
            var section = $(this).data('section');
            
            // Update active tab
            $('.aqualuxe-api-docs-nav-item').removeClass('active');
            $(this).addClass('active');
            
            // Show active section
            $('.aqualuxe-api-docs-section').removeClass('active');
            $('#' + section).addClass('active');
        });
    }

    /**
     * Copy text to clipboard
     */
    window.copyToClipboard = function(elementId) {
        var element = document.getElementById(elementId);
        
        if (element) {
            element.select();
            document.execCommand('copy');
            
            // Show copied message
            var $message = $('<span class="copied-message">Copied!</span>');
            $(element).after($message);
            
            // Remove message after a delay
            setTimeout(function() {
                $message.fadeOut(function() {
                    $message.remove();
                });
            }, 1500);
        }
    };

})(jQuery);