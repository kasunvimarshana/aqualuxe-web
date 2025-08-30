/**
 * AquaLuxe Theme Admin JavaScript
 * 
 * JavaScript functionality for the WordPress admin area.
 */

(function($) {
    'use strict';

    // Global variables
    const $document = $(document);

    /**
     * AquaLuxe Admin Object
     */
    const AquaLuxeAdmin = {
        /**
         * Initialize the admin functionality
         */
        init: function() {
            this.mediaUploader();
            this.colorPicker();
            this.metaBoxes();
            this.sortableFields();
            this.repeaterFields();
            this.conditionalFields();
            this.importDemo();
            this.themeOptions();
        },

        /**
         * Media uploader functionality
         */
        mediaUploader: function() {
            let mediaUploader;
            
            $('.aqualuxe-media-upload').on('click', function(e) {
                e.preventDefault();
                
                const $button = $(this);
                const $field = $button.prev('.aqualuxe-media-field');
                const $preview = $button.next('.aqualuxe-media-preview');
                
                // If the uploader object has already been created, reopen the dialog
                if (mediaUploader) {
                    mediaUploader.open();
                    return;
                }
                
                // Create the media uploader
                mediaUploader = wp.media({
                    title: 'Select or Upload Media',
                    button: {
                        text: 'Use this media'
                    },
                    multiple: false
                });
                
                // When a file is selected, grab the URL and set it as the field's value
                mediaUploader.on('select', function() {
                    const attachment = mediaUploader.state().get('selection').first().toJSON();
                    $field.val(attachment.id);
                    
                    // Update preview
                    if (attachment.type === 'image') {
                        $preview.html('<img src="' + attachment.url + '" alt="">');
                    } else {
                        $preview.html('<div class="media-file">' + attachment.filename + '</div>');
                    }
                    
                    $preview.show();
                    $button.text('Change Media');
                    $button.next('.aqualuxe-media-remove').show();
                });
                
                // Open the uploader dialog
                mediaUploader.open();
            });
            
            // Remove media
            $('.aqualuxe-media-remove').on('click', function(e) {
                e.preventDefault();
                
                const $button = $(this);
                const $field = $button.prevAll('.aqualuxe-media-field');
                const $preview = $button.prev('.aqualuxe-media-preview');
                const $upload = $button.prev().prev('.aqualuxe-media-upload');
                
                $field.val('');
                $preview.html('').hide();
                $upload.text('Upload');
                $button.hide();
            });
        },

        /**
         * Color picker functionality
         */
        colorPicker: function() {
            if ($.fn.wpColorPicker) {
                $('.aqualuxe-color-picker').wpColorPicker();
            }
        },

        /**
         * Meta boxes functionality
         */
        metaBoxes: function() {
            // Toggle meta boxes
            $('.aqualuxe-meta-box-toggle').on('click', function() {
                $(this).toggleClass('closed').next('.aqualuxe-meta-box-content').slideToggle(300);
            });
            
            // Tabs in meta boxes
            $('.aqualuxe-meta-box-tabs-nav a').on('click', function(e) {
                e.preventDefault();
                
                const $link = $(this);
                const tabId = $link.attr('href');
                
                $link.parent().addClass('active').siblings().removeClass('active');
                $(tabId).addClass('active').siblings('.aqualuxe-meta-box-tab').removeClass('active');
            });
        },

        /**
         * Sortable fields functionality
         */
        sortableFields: function() {
            $('.aqualuxe-sortable').sortable({
                handle: '.aqualuxe-sortable-handle',
                update: function() {
                    $(this).find('.aqualuxe-sortable-item').each(function(index) {
                        $(this).find('.aqualuxe-sortable-order').val(index);
                    });
                }
            });
        },

        /**
         * Repeater fields functionality
         */
        repeaterFields: function() {
            // Add new item
            $document.on('click', '.aqualuxe-repeater-add', function() {
                const $button = $(this);
                const $repeater = $button.closest('.aqualuxe-repeater');
                const $items = $repeater.find('.aqualuxe-repeater-items');
                const $template = $repeater.find('.aqualuxe-repeater-template').html();
                const index = $items.data('index') || 0;
                
                // Replace placeholder index with actual index
                const newItem = $template.replace(/\{index\}/g, index);
                
                // Append new item
                $items.append(newItem);
                
                // Update index
                $items.data('index', index + 1);
                
                // Initialize color pickers in the new item
                $items.find('.aqualuxe-repeater-item:last-child .aqualuxe-color-picker').wpColorPicker();
            });
            
            // Remove item
            $document.on('click', '.aqualuxe-repeater-remove', function() {
                $(this).closest('.aqualuxe-repeater-item').remove();
            });
            
            // Toggle item
            $document.on('click', '.aqualuxe-repeater-toggle', function() {
                $(this).toggleClass('closed').closest('.aqualuxe-repeater-item').find('.aqualuxe-repeater-item-content').slideToggle(300);
            });
        },

        /**
         * Conditional fields functionality
         */
        conditionalFields: function() {
            const updateConditionalFields = function() {
                $('.aqualuxe-conditional-field').each(function() {
                    const $field = $(this);
                    const depends = $field.data('depends');
                    
                    if (depends) {
                        const $dependsField = $('#' + depends.field);
                        const dependsValue = depends.value;
                        const dependsOperator = depends.operator || '==';
                        
                        let fieldValue;
                        
                        // Get field value based on field type
                        if ($dependsField.is(':checkbox')) {
                            fieldValue = $dependsField.is(':checked');
                        } else if ($dependsField.is(':radio')) {
                            fieldValue = $dependsField.filter(':checked').val();
                        } else {
                            fieldValue = $dependsField.val();
                        }
                        
                        // Check condition
                        let showField = false;
                        
                        switch (dependsOperator) {
                            case '==':
                                showField = fieldValue == dependsValue;
                                break;
                            case '!=':
                                showField = fieldValue != dependsValue;
                                break;
                            case '>':
                                showField = fieldValue > dependsValue;
                                break;
                            case '<':
                                showField = fieldValue < dependsValue;
                                break;
                            case '>=':
                                showField = fieldValue >= dependsValue;
                                break;
                            case '<=':
                                showField = fieldValue <= dependsValue;
                                break;
                            case 'contains':
                                showField = fieldValue && fieldValue.indexOf(dependsValue) !== -1;
                                break;
                            case 'in':
                                showField = dependsValue && dependsValue.indexOf(fieldValue) !== -1;
                                break;
                        }
                        
                        // Show or hide field
                        if (showField) {
                            $field.show();
                        } else {
                            $field.hide();
                        }
                    }
                });
            };
            
            // Update on page load
            updateConditionalFields();
            
            // Update on field change
            $document.on('change', '.aqualuxe-field', function() {
                updateConditionalFields();
            });
        },

        /**
         * Import demo functionality
         */
        importDemo: function() {
            const $importForm = $('#aqualuxe-import-form');
            
            if ($importForm.length) {
                $importForm.on('submit', function(e) {
                    e.preventDefault();
                    
                    const $form = $(this);
                    const $submit = $form.find('.aqualuxe-import-submit');
                    const $progress = $form.find('.aqualuxe-import-progress');
                    const $progressBar = $progress.find('.aqualuxe-import-progress-bar');
                    const $progressText = $progress.find('.aqualuxe-import-progress-text');
                    const $log = $form.find('.aqualuxe-import-log');
                    
                    // Confirm import
                    if (!confirm('Are you sure you want to import the demo content? This will overwrite your current content.')) {
                        return;
                    }
                    
                    // Disable submit button
                    $submit.prop('disabled', true).text('Importing...');
                    
                    // Show progress bar
                    $progress.show();
                    
                    // Get form data
                    const formData = $form.serialize();
                    
                    // Start import
                    $.ajax({
                        url: ajaxurl,
                        type: 'POST',
                        data: {
                            action: 'aqualuxe_import_demo',
                            nonce: aqualuxeAdminData.importNonce,
                            data: formData
                        },
                        success: function(response) {
                            if (response.success) {
                                // Update progress
                                $progressBar.css('width', '100%');
                                $progressText.text('100%');
                                
                                // Show success message
                                $log.append('<div class="notice notice-success"><p>' + response.data.message + '</p></div>');
                                
                                // Enable submit button
                                $submit.prop('disabled', false).text('Import Completed');
                            } else {
                                // Show error message
                                $log.append('<div class="notice notice-error"><p>' + response.data.message + '</p></div>');
                                
                                // Enable submit button
                                $submit.prop('disabled', false).text('Import Failed');
                            }
                        },
                        error: function() {
                            // Show error message
                            $log.append('<div class="notice notice-error"><p>An error occurred during the import process.</p></div>');
                            
                            // Enable submit button
                            $submit.prop('disabled', false).text('Import Failed');
                        }
                    });
                });
            }
        },

        /**
         * Theme options functionality
         */
        themeOptions: function() {
            const $themeOptions = $('#aqualuxe-theme-options');
            
            if ($themeOptions.length) {
                // Save options
                $themeOptions.on('submit', function(e) {
                    e.preventDefault();
                    
                    const $form = $(this);
                    const $submit = $form.find('.aqualuxe-options-submit');
                    const $spinner = $submit.find('.spinner');
                    const $message = $form.find('.aqualuxe-options-message');
                    
                    // Show spinner
                    $spinner.addClass('is-active');
                    
                    // Get form data
                    const formData = $form.serialize();
                    
                    // Save options
                    $.ajax({
                        url: ajaxurl,
                        type: 'POST',
                        data: {
                            action: 'aqualuxe_save_options',
                            nonce: aqualuxeAdminData.optionsNonce,
                            data: formData
                        },
                        success: function(response) {
                            // Hide spinner
                            $spinner.removeClass('is-active');
                            
                            if (response.success) {
                                // Show success message
                                $message.html('<div class="notice notice-success is-dismissible"><p>' + response.data.message + '</p></div>');
                            } else {
                                // Show error message
                                $message.html('<div class="notice notice-error is-dismissible"><p>' + response.data.message + '</p></div>');
                            }
                            
                            // Dismiss notice after 5 seconds
                            setTimeout(function() {
                                $message.find('.notice').fadeOut(500, function() {
                                    $(this).remove();
                                });
                            }, 5000);
                        },
                        error: function() {
                            // Hide spinner
                            $spinner.removeClass('is-active');
                            
                            // Show error message
                            $message.html('<div class="notice notice-error is-dismissible"><p>An error occurred while saving options.</p></div>');
                            
                            // Dismiss notice after 5 seconds
                            setTimeout(function() {
                                $message.find('.notice').fadeOut(500, function() {
                                    $(this).remove();
                                });
                            }, 5000);
                        }
                    });
                });
                
                // Reset options
                $('#aqualuxe-reset-options').on('click', function(e) {
                    e.preventDefault();
                    
                    // Confirm reset
                    if (!confirm('Are you sure you want to reset all options to their default values?')) {
                        return;
                    }
                    
                    const $button = $(this);
                    const $spinner = $button.next('.spinner');
                    const $message = $themeOptions.find('.aqualuxe-options-message');
                    
                    // Show spinner
                    $spinner.addClass('is-active');
                    
                    // Reset options
                    $.ajax({
                        url: ajaxurl,
                        type: 'POST',
                        data: {
                            action: 'aqualuxe_reset_options',
                            nonce: aqualuxeAdminData.optionsNonce
                        },
                        success: function(response) {
                            // Hide spinner
                            $spinner.removeClass('is-active');
                            
                            if (response.success) {
                                // Show success message
                                $message.html('<div class="notice notice-success is-dismissible"><p>' + response.data.message + '</p></div>');
                                
                                // Reload page after 2 seconds
                                setTimeout(function() {
                                    window.location.reload();
                                }, 2000);
                            } else {
                                // Show error message
                                $message.html('<div class="notice notice-error is-dismissible"><p>' + response.data.message + '</p></div>');
                            }
                        },
                        error: function() {
                            // Hide spinner
                            $spinner.removeClass('is-active');
                            
                            // Show error message
                            $message.html('<div class="notice notice-error is-dismissible"><p>An error occurred while resetting options.</p></div>');
                        }
                    });
                });
                
                // Tabs
                $('.aqualuxe-options-tabs-nav a').on('click', function(e) {
                    e.preventDefault();
                    
                    const $link = $(this);
                    const tabId = $link.attr('href');
                    
                    $link.parent().addClass('active').siblings().removeClass('active');
                    $(tabId).addClass('active').siblings('.aqualuxe-options-tab').removeClass('active');
                    
                    // Update URL hash
                    window.location.hash = tabId;
                });
                
                // Set active tab from URL hash
                const hash = window.location.hash;
                if (hash) {
                    $('.aqualuxe-options-tabs-nav a[href="' + hash + '"]').trigger('click');
                }
            }
        }
    };

    // Initialize AquaLuxe admin functionality
    $(document).ready(function() {
        AquaLuxeAdmin.init();
    });

})(jQuery);