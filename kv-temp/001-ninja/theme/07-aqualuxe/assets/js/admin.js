/**
 * AquaLuxe Admin Scripts
 */
(function($) {
    'use strict';

    // Document ready
    $(document).ready(function() {
        // Tab navigation
        $('.aqualuxe-admin-tabs .nav-tab').on('click', function(e) {
            e.preventDefault();
            
            // Get the tab ID from the href attribute
            var tabId = $(this).attr('href');
            
            // Remove active class from all tabs and tab panes
            $('.aqualuxe-admin-tabs .nav-tab').removeClass('nav-tab-active');
            $('.aqualuxe-admin-tab-pane').removeClass('active');
            
            // Add active class to the clicked tab and corresponding tab pane
            $(this).addClass('nav-tab-active');
            $(tabId).addClass('active');
            
            // Store the active tab in localStorage
            localStorage.setItem('aqualuxeActiveTab', tabId);
        });
        
        // Check if there's a stored active tab
        var activeTab = localStorage.getItem('aqualuxeActiveTab');
        if (activeTab) {
            // Trigger click on the stored active tab
            $('.aqualuxe-admin-tabs .nav-tab[href="' + activeTab + '"]').trigger('click');
        }
        
        // Plugin installation and activation
        $('.aqualuxe-admin-plugin-card-footer .button').on('click', function(e) {
            var $button = $(this);
            
            // If the button is disabled, prevent the default action
            if ($button.hasClass('button-disabled')) {
                e.preventDefault();
                return;
            }
            
            // Show loading state
            $button.addClass('updating-message').text(aqualuxeAdmin.installing);
        });
        
        // Demo import
        $('.aqualuxe-admin-demo-content-actions .button').on('click', function() {
            var $button = $(this);
            
            // If the button is for demo import
            if ($button.text().indexOf('Import') !== -1) {
                // Show confirmation dialog
                if (confirm(aqualuxeAdmin.confirmImport)) {
                    // Show loading state
                    $button.addClass('updating-message').text(aqualuxeAdmin.importing);
                } else {
                    return false;
                }
            }
        });
        
        // Initialize tooltips
        if ($.fn.tooltip) {
            $('.aqualuxe-admin-wrap [data-tooltip]').tooltip({
                position: {
                    my: 'center bottom-10',
                    at: 'center top'
                },
                tooltipClass: 'aqualuxe-tooltip',
                content: function() {
                    return $(this).attr('data-tooltip');
                }
            });
        }
        
        // System status accordion
        $('.aqualuxe-system-status-accordion').on('click', function() {
            $(this).toggleClass('open');
            $(this).next('.aqualuxe-system-status-panel').slideToggle(300);
        });
        
        // Dismissible notices
        $('.aqualuxe-admin-notice.is-dismissible').on('click', '.notice-dismiss', function() {
            var $notice = $(this).closest('.aqualuxe-admin-notice');
            var noticeId = $notice.data('notice-id');
            
            // Send AJAX request to dismiss the notice
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'aqualuxe_dismiss_admin_notice',
                    notice_id: noticeId,
                    nonce: aqualuxeAdmin.nonce
                }
            });
        });
        
        // Theme activation
        $('#aqualuxe-activate-theme').on('click', function(e) {
            e.preventDefault();
            
            var $button = $(this);
            var purchaseCode = $('#aqualuxe-purchase-code').val();
            
            if (!purchaseCode) {
                alert(aqualuxeAdmin.enterPurchaseCode);
                return;
            }
            
            // Show loading state
            $button.addClass('updating-message').text(aqualuxeAdmin.activating);
            
            // Send AJAX request to activate the theme
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'aqualuxe_activate_theme',
                    purchase_code: purchaseCode,
                    nonce: aqualuxeAdmin.nonce
                },
                success: function(response) {
                    if (response.success) {
                        // Show success message
                        $('.aqualuxe-activation-form').hide();
                        $('.aqualuxe-activation-success').show();
                    } else {
                        // Show error message
                        alert(response.data.message || aqualuxeAdmin.activationError);
                        $button.removeClass('updating-message').text(aqualuxeAdmin.activate);
                    }
                },
                error: function() {
                    // Show error message
                    alert(aqualuxeAdmin.activationError);
                    $button.removeClass('updating-message').text(aqualuxeAdmin.activate);
                }
            });
        });
    });

})(jQuery);