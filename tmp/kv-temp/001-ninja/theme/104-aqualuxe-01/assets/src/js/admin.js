/**
 * Admin JavaScript
 * 
 * @package AquaLuxe
 */

jQuery(document).ready(function($) {
    'use strict';
    
    /**
     * Admin functionality
     */
    const AquaLuxeAdmin = {
        
        /**
         * Initialize
         */
        init: function() {
            this.setupTabs();
            this.setupTooltips();
            this.setupConfirmDialogs();
            this.setupAjaxForms();
            this.setupDataTables();
            this.setupImageUpload();
            this.setupColorPickers();
        },
        
        /**
         * Setup admin tabs
         */
        setupTabs: function() {
            $('.nav-tab').on('click', function(e) {
                e.preventDefault();
                
                const $tab = $(this);
                const target = $tab.attr('href');
                
                // Update active tab
                $('.nav-tab').removeClass('active');
                $tab.addClass('active');
                
                // Show target content
                $('.tab-content').hide();
                $(target).show();
                
                // Save active tab
                localStorage.setItem('aqualuxe_active_tab', target);
            });
            
            // Restore active tab
            const activeTab = localStorage.getItem('aqualuxe_active_tab');
            if (activeTab) {
                $('.nav-tab[href="' + activeTab + '"]').trigger('click');
            }
        },
        
        /**
         * Setup tooltips
         */
        setupTooltips: function() {
            $('[data-tooltip]').each(function() {
                const $element = $(this);
                const text = $element.data('tooltip');
                
                $element.on('mouseenter', function() {
                    const tooltip = $(`<div class="admin-tooltip">${text}</div>`);
                    $('body').append(tooltip);
                    
                    const rect = this.getBoundingClientRect();
                    tooltip.css({
                        top: rect.top - tooltip.outerHeight() - 5,
                        left: rect.left + (rect.width / 2) - (tooltip.outerWidth() / 2)
                    });
                });
                
                $element.on('mouseleave', function() {
                    $('.admin-tooltip').remove();
                });
            });
        },
        
        /**
         * Setup confirmation dialogs
         */
        setupConfirmDialogs: function() {
            $('[data-confirm]').on('click', function(e) {
                const message = $(this).data('confirm');
                if (!confirm(message)) {
                    e.preventDefault();
                    return false;
                }
            });
        },
        
        /**
         * Setup AJAX forms
         */
        setupAjaxForms: function() {
            $('.ajax-form').on('submit', function(e) {
                e.preventDefault();
                
                const $form = $(this);
                const $submit = $form.find('[type="submit"]');
                const originalText = $submit.text();
                
                $submit.text('Saving...').prop('disabled', true);
                
                $.post($form.attr('action'), $form.serialize())
                    .done(function(response) {
                        if (response.success) {
                            AquaLuxeAdmin.showNotice('Settings saved successfully!', 'success');
                        } else {
                            AquaLuxeAdmin.showNotice(response.data || 'Error saving settings.', 'error');
                        }
                    })
                    .fail(function() {
                        AquaLuxeAdmin.showNotice('Error saving settings.', 'error');
                    })
                    .always(function() {
                        $submit.text(originalText).prop('disabled', false);
                    });
            });
        },
        
        /**
         * Setup data tables
         */
        setupDataTables: function() {
            $('.data-table table').each(function() {
                // Add sorting functionality
                $(this).find('th[data-sort]').addClass('sortable').on('click', function() {
                    const $th = $(this);
                    const column = $th.data('sort');
                    const direction = $th.hasClass('sort-asc') ? 'desc' : 'asc';
                    
                    // Update sorting indicators
                    $th.siblings().removeClass('sort-asc sort-desc');
                    $th.removeClass('sort-asc sort-desc').addClass('sort-' + direction);
                    
                    // Perform sort (you can implement actual sorting logic here)
                    console.log('Sorting by', column, direction);
                });
            });
        },
        
        /**
         * Setup image upload
         */
        setupImageUpload: function() {
            $('.image-upload-btn').on('click', function(e) {
                e.preventDefault();
                
                const $button = $(this);
                const $input = $button.siblings('input[type="hidden"]');
                const $preview = $button.siblings('.image-preview');
                
                const frame = wp.media({
                    title: 'Select Image',
                    button: { text: 'Use Image' },
                    multiple: false
                });
                
                frame.on('select', function() {
                    const attachment = frame.state().get('selection').first().toJSON();
                    
                    $input.val(attachment.id);
                    $preview.html(`<img src="${attachment.url}" alt="" style="max-width: 200px; height: auto;">`);
                    $button.text('Change Image');
                });
                
                frame.open();
            });
            
            $('.image-remove-btn').on('click', function(e) {
                e.preventDefault();
                
                const $button = $(this);
                const $input = $button.siblings('input[type="hidden"]');
                const $preview = $button.siblings('.image-preview');
                const $uploadBtn = $button.siblings('.image-upload-btn');
                
                $input.val('');
                $preview.empty();
                $uploadBtn.text('Select Image');
            });
        },
        
        /**
         * Setup color pickers
         */
        setupColorPickers: function() {
            if ($.fn.wpColorPicker) {
                $('.color-picker').wpColorPicker();
            }
        },
        
        /**
         * Show admin notice
         */
        showNotice: function(message, type) {
            const notice = $(`
                <div class="notice notice-${type} is-dismissible">
                    <p>${message}</p>
                    <button type="button" class="notice-dismiss">
                        <span class="screen-reader-text">Dismiss this notice.</span>
                    </button>
                </div>
            `);
            
            $('.wrap > h1').after(notice);
            
            // Auto-dismiss after 5 seconds
            setTimeout(() => {
                notice.fadeOut(() => notice.remove());
            }, 5000);
            
            // Manual dismiss
            notice.find('.notice-dismiss').on('click', function() {
                notice.fadeOut(() => notice.remove());
            });
        }
    };
    
    // Initialize admin functionality
    AquaLuxeAdmin.init();
    
    // Make it globally available
    window.AquaLuxeAdmin = AquaLuxeAdmin;
});