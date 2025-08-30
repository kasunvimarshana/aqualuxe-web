/**
 * AquaLuxe Theme Options JavaScript
 *
 * Handles all the interactive functionality of the theme options page.
 */
(function($) {
    'use strict';

    // Initialize color pickers
    function initColorPickers() {
        $('.aqualuxe-color-picker').wpColorPicker();
    }

    // Initialize media uploader
    function initMediaUploader() {
        $('.aqualuxe-media-upload').each(function() {
            var $button = $(this);
            var $field = $button.siblings('input[type="text"]');
            var $preview = $button.siblings('.aqualuxe-media-preview');
            var $remove = $button.siblings('.aqualuxe-media-remove');

            $button.on('click', function(e) {
                e.preventDefault();

                var frame = wp.media({
                    title: aqualuxe_admin.i18n.select_image,
                    multiple: false,
                    library: { type: 'image' },
                    button: { text: aqualuxe_admin.i18n.use_image }
                });

                frame.on('select', function() {
                    var attachment = frame.state().get('selection').first().toJSON();
                    $field.val(attachment.url);
                    
                    if ($preview.length) {
                        $preview.html('<img src="' + attachment.url + '" alt="" />');
                    }
                    
                    $remove.show();
                });

                frame.open();
            });

            $remove.on('click', function(e) {
                e.preventDefault();
                $field.val('');
                $preview.empty();
                $(this).hide();
            });
        });
    }

    // Initialize sortable items
    function initSortable() {
        $('.aqualuxe-sortable').sortable({
            handle: '.aqualuxe-sortable-handle',
            update: function(event, ui) {
                // Update order in hidden field
                var $container = $(this);
                var $orderField = $container.siblings('.aqualuxe-sortable-order');
                var items = [];
                
                $container.find('.aqualuxe-sortable-item').each(function() {
                    items.push($(this).data('id'));
                });
                
                $orderField.val(items.join(','));
            }
        });
    }

    // Initialize repeater fields
    function initRepeaterFields() {
        $('.aqualuxe-repeater-container').each(function() {
            var $container = $(this);
            var $addButton = $container.siblings('.aqualuxe-repeater-add');
            var $template = $container.siblings('.aqualuxe-repeater-template').html();
            var fieldName = $container.data('name');
            var maxItems = $container.data('max') || 0;

            // Add item
            $addButton.on('click', function(e) {
                e.preventDefault();
                
                // Check max items
                if (maxItems > 0 && $container.children('.aqualuxe-repeater-item').length >= maxItems) {
                    return;
                }
                
                var index = $container.data('next-index') || 0;
                var newItem = $template.replace(/\{index\}/g, index);
                
                $container.append(newItem);
                $container.data('next-index', index + 1);
                
                // Initialize new item's components
                initColorPickers();
                initMediaUploader();
                
                // Update item count
                updateRepeaterCount($container);
            });

            // Remove item
            $container.on('click', '.aqualuxe-repeater-remove', function(e) {
                e.preventDefault();
                $(this).closest('.aqualuxe-repeater-item').remove();
                
                // Update item count
                updateRepeaterCount($container);
            });
        });
    }

    // Update repeater item count
    function updateRepeaterCount($container) {
        var count = $container.children('.aqualuxe-repeater-item').length;
        $container.siblings('.aqualuxe-repeater-count').text(count);
    }

    // Initialize tabs
    function initTabs() {
        $('.aqualuxe-tabs-nav a').on('click', function(e) {
            e.preventDefault();
            
            var $this = $(this);
            var target = $this.attr('href');
            
            // Update active tab
            $('.aqualuxe-tabs-nav a').removeClass('active');
            $this.addClass('active');
            
            // Show target panel
            $('.aqualuxe-tab-panel').hide();
            $(target).show();
            
            // Update hidden field
            $('#aqualuxe_active_tab').val(target.replace('#', ''));
        });
        
        // Show active tab on load
        var activeTab = $('#aqualuxe_active_tab').val();
        if (activeTab) {
            $('.aqualuxe-tabs-nav a[href="#' + activeTab + '"]').trigger('click');
        } else {
            $('.aqualuxe-tabs-nav a:first').trigger('click');
        }
    }

    // Reset options
    function initResetOptions() {
        $('.aqualuxe-reset-options').on('click', function(e) {
            e.preventDefault();
            
            if (confirm(aqualuxe_admin.i18n.confirm_reset)) {
                // Add hidden field to form
                var $form = $(this).closest('form');
                $form.append('<input type="hidden" name="aqualuxe_reset_options" value="1" />');
                $form.submit();
            }
        });
    }

    // Toggle dependent fields
    function initDependentFields() {
        $('.aqualuxe-field-toggle').on('change', function() {
            var $this = $(this);
            var target = $this.data('target');
            var showOn = $this.data('show-on');
            var $target = $('#' + target);
            
            if ($target.length) {
                if ((showOn === 'checked' && $this.is(':checked')) || 
                    (showOn !== 'checked' && $this.val() === showOn)) {
                    $target.show();
                } else {
                    $target.hide();
                }
            }
        }).trigger('change');
    }

    // Document ready
    $(document).ready(function() {
        initColorPickers();
        initMediaUploader();
        initSortable();
        initRepeaterFields();
        initTabs();
        initResetOptions();
        initDependentFields();
    });

})(jQuery);