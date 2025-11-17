/**
 * AquaLuxe Theme WooCommerce Admin JavaScript
 *
 * This file contains all the WooCommerce admin-specific JavaScript functionality.
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

(function($) {
    'use strict';

    // AquaLuxe WooCommerce Admin object
    var AquaLuxeWCAdmin = {
        /**
         * Initialize the WooCommerce admin scripts
         */
        init: function() {
            this.initProductHighlights();
            this.initProductSpecifications();
            this.initProduct360View();
            this.initProductFAQ();
            this.initCustomTabs();
            this.initMediaUpload();
        },

        /**
         * Initialize product highlights
         */
        initProductHighlights: function() {
            // Add highlight
            $(document).on('click', '.add_highlight', function(e) {
                e.preventDefault();
                
                var $container = $('#product_highlights');
                var index = $container.find('.highlight').length;
                
                var html = '<div class="highlight">';
                html += '<input type="text" name="product_highlight[' + index + ']" class="widefat" placeholder="' + aqualuxe_wc_admin_params.i18n_highlight_placeholder + '" />';
                html += '<button type="button" class="button remove_highlight">' + aqualuxe_wc_admin_params.i18n_remove + '</button>';
                html += '</div>';
                
                $container.append(html);
            });
            
            // Remove highlight
            $(document).on('click', '.remove_highlight', function(e) {
                e.preventDefault();
                $(this).closest('.highlight').remove();
            });
            
            // Make highlights sortable
            if ($.fn.sortable) {
                $('#product_highlights').sortable({
                    items: '.highlight',
                    cursor: 'move',
                    axis: 'y',
                    handle: 'input',
                    scrollSensitivity: 40,
                    forcePlaceholderSize: true,
                    helper: 'clone',
                    opacity: 0.65,
                    placeholder: 'wc-metabox-sortable-placeholder',
                    start: function(event, ui) {
                        ui.item.css('background-color', '#f6f6f6');
                    },
                    stop: function(event, ui) {
                        ui.item.removeAttr('style');
                    }
                });
            }
        },

        /**
         * Initialize product specifications
         */
        initProductSpecifications: function() {
            // Add specification
            $(document).on('click', '.add_specification', function(e) {
                e.preventDefault();
                
                var $container = $('#product_specifications');
                var index = $container.find('.specification').length;
                
                var html = '<div class="specification">';
                html += '<input type="text" name="specification_name[' + index + ']" placeholder="' + aqualuxe_wc_admin_params.i18n_spec_name + '" />';
                html += '<input type="text" name="specification_value[' + index + ']" placeholder="' + aqualuxe_wc_admin_params.i18n_spec_value + '" />';
                html += '<button type="button" class="button remove_specification">' + aqualuxe_wc_admin_params.i18n_remove + '</button>';
                html += '</div>';
                
                $container.append(html);
            });
            
            // Remove specification
            $(document).on('click', '.remove_specification', function(e) {
                e.preventDefault();
                $(this).closest('.specification').remove();
            });
            
            // Make specifications sortable
            if ($.fn.sortable) {
                $('#product_specifications').sortable({
                    items: '.specification',
                    cursor: 'move',
                    axis: 'y',
                    handle: 'input:first',
                    scrollSensitivity: 40,
                    forcePlaceholderSize: true,
                    helper: 'clone',
                    opacity: 0.65,
                    placeholder: 'wc-metabox-sortable-placeholder',
                    start: function(event, ui) {
                        ui.item.css('background-color', '#f6f6f6');
                    },
                    stop: function(event, ui) {
                        ui.item.removeAttr('style');
                    }
                });
            }
        },

        /**
         * Initialize product 360 view
         */
        initProduct360View: function() {
            // Product 360 images
            var product_360_frame;
            var $product_360_images_container = $('#product_360_images_container');
            var $product_360_images_list = $product_360_images_container.find('ul.product_360_images');
            var $product_360_images_input = $('#product_360_images');
            
            // Add 360 view images
            $(document).on('click', '.add_product_360_images', function(e) {
                e.preventDefault();
                
                // If the media frame already exists, reopen it
                if (product_360_frame) {
                    product_360_frame.open();
                    return;
                }
                
                // Create the media frame
                product_360_frame = wp.media({
                    title: aqualuxe_wc_admin_params.i18n_add_360_images,
                    button: {
                        text: aqualuxe_wc_admin_params.i18n_add_to_gallery
                    },
                    multiple: true
                });
                
                // When an image is selected, run a callback
                product_360_frame.on('select', function() {
                    var attachments = product_360_frame.state().get('selection').toJSON();
                    var attachment_ids = $product_360_images_input.val() ? $product_360_images_input.val().split(',') : [];
                    
                    $.each(attachments, function(i, attachment) {
                        if ($.inArray(attachment.id.toString(), attachment_ids) === -1) {
                            attachment_ids.push(attachment.id.toString());
                            
                            $product_360_images_list.append(
                                '<li class="image" data-attachment_id="' + attachment.id + '">' +
                                '<img src="' + attachment.sizes.thumbnail.url + '" alt="" />' +
                                '<ul class="actions">' +
                                '<li><a href="#" class="delete" title="' + aqualuxe_wc_admin_params.i18n_delete_image + '">' + aqualuxe_wc_admin_params.i18n_delete + '</a></li>' +
                                '</ul>' +
                                '</li>'
                            );
                        }
                    });
                    
                    $product_360_images_input.val(attachment_ids.join(','));
                });
                
                // Finally, open the modal
                product_360_frame.open();
            });
            
            // Remove 360 view image
            $(document).on('click', 'a.delete', function(e) {
                e.preventDefault();
                
                var $image = $(this).closest('li.image');
                var attachment_id = $image.data('attachment_id');
                var attachment_ids = $product_360_images_input.val().split(',');
                
                // Remove the attachment ID from the array
                attachment_ids = attachment_ids.filter(function(value) {
                    return value != attachment_id;
                });
                
                $product_360_images_input.val(attachment_ids.join(','));
                $image.remove();
            });
            
            // Make 360 view images sortable
            if ($.fn.sortable && $product_360_images_list.length) {
                $product_360_images_list.sortable({
                    items: 'li.image',
                    cursor: 'move',
                    scrollSensitivity: 40,
                    forcePlaceholderSize: true,
                    forceHelperSize: false,
                    helper: 'clone',
                    opacity: 0.65,
                    placeholder: 'wc-metabox-sortable-placeholder',
                    start: function(event, ui) {
                        ui.item.css('background-color', '#f6f6f6');
                    },
                    stop: function(event, ui) {
                        ui.item.removeAttr('style');
                    },
                    update: function() {
                        var attachment_ids = [];
                        
                        $product_360_images_list.find('li.image').each(function() {
                            attachment_ids.push($(this).data('attachment_id'));
                        });
                        
                        $product_360_images_input.val(attachment_ids.join(','));
                    }
                });
            }
        },

        /**
         * Initialize product FAQ
         */
        initProductFAQ: function() {
            // Add FAQ
            $(document).on('click', '.add_faq', function(e) {
                e.preventDefault();
                
                var $container = $('#product_faqs');
                var index = $container.find('.faq').length;
                
                var html = '<div class="faq">';
                html += '<p>';
                html += '<label>' + aqualuxe_wc_admin_params.i18n_question + '</label>';
                html += '<input type="text" name="faq_question[' + index + ']" class="widefat" />';
                html += '</p>';
                html += '<p>';
                html += '<label>' + aqualuxe_wc_admin_params.i18n_answer + '</label>';
                html += '<textarea name="faq_answer[' + index + ']" rows="3" class="widefat"></textarea>';
                html += '</p>';
                html += '<p><button type="button" class="button remove_faq">' + aqualuxe_wc_admin_params.i18n_remove_faq + '</button></p>';
                html += '</div>';
                
                $container.append(html);
            });
            
            // Remove FAQ
            $(document).on('click', '.remove_faq', function(e) {
                e.preventDefault();
                $(this).closest('.faq').remove();
            });
            
            // Make FAQs sortable
            if ($.fn.sortable) {
                $('#product_faqs').sortable({
                    items: '.faq',
                    cursor: 'move',
                    axis: 'y',
                    handle: 'label:first',
                    scrollSensitivity: 40,
                    forcePlaceholderSize: true,
                    helper: 'clone',
                    opacity: 0.65,
                    placeholder: 'wc-metabox-sortable-placeholder',
                    start: function(event, ui) {
                        ui.item.css('background-color', '#f6f6f6');
                    },
                    stop: function(event, ui) {
                        ui.item.removeAttr('style');
                    }
                });
            }
        },

        /**
         * Initialize custom tabs
         */
        initCustomTabs: function() {
            // Add custom tab
            $(document).on('click', '.add_custom_tab', function(e) {
                e.preventDefault();
                
                var $container = $('#product_custom_tabs');
                var index = $container.find('.custom-tab').length;
                
                var html = '<div class="custom-tab">';
                html += '<p>';
                html += '<label>' + aqualuxe_wc_admin_params.i18n_tab_title + '</label>';
                html += '<input type="text" name="custom_tab_title[' + index + ']" class="widefat" />';
                html += '</p>';
                html += '<p>';
                html += '<label>' + aqualuxe_wc_admin_params.i18n_tab_content + '</label>';
                html += '<textarea name="custom_tab_content[' + index + ']" rows="5" class="widefat"></textarea>';
                html += '</p>';
                html += '<p><button type="button" class="button remove_custom_tab">' + aqualuxe_wc_admin_params.i18n_remove_tab + '</button></p>';
                html += '</div>';
                
                $container.append(html);
            });
            
            // Remove custom tab
            $(document).on('click', '.remove_custom_tab', function(e) {
                e.preventDefault();
                $(this).closest('.custom-tab').remove();
            });
            
            // Make custom tabs sortable
            if ($.fn.sortable) {
                $('#product_custom_tabs').sortable({
                    items: '.custom-tab',
                    cursor: 'move',
                    axis: 'y',
                    handle: 'label:first',
                    scrollSensitivity: 40,
                    forcePlaceholderSize: true,
                    helper: 'clone',
                    opacity: 0.65,
                    placeholder: 'wc-metabox-sortable-placeholder',
                    start: function(event, ui) {
                        ui.item.css('background-color', '#f6f6f6');
                    },
                    stop: function(event, ui) {
                        ui.item.removeAttr('style');
                    }
                });
            }
        },

        /**
         * Initialize media upload
         */
        initMediaUpload: function() {
            // Product video upload
            var product_video_frame;
            
            $(document).on('click', '.upload_product_video_button', function(e) {
                e.preventDefault();
                
                var $button = $(this);
                var $input = $button.prev('input');
                
                // If the media frame already exists, reopen it
                if (product_video_frame) {
                    product_video_frame.open();
                    return;
                }
                
                // Create the media frame
                product_video_frame = wp.media({
                    title: aqualuxe_wc_admin_params.i18n_upload_video,
                    button: {
                        text: aqualuxe_wc_admin_params.i18n_use_video
                    },
                    multiple: false,
                    library: {
                        type: 'video'
                    }
                });
                
                // When a video is selected, run a callback
                product_video_frame.on('select', function() {
                    var attachment = product_video_frame.state().get('selection').first().toJSON();
                    $input.val(attachment.url);
                });
                
                // Finally, open the modal
                product_video_frame.open();
            });
        }
    };

    // Initialize on document ready
    $(document).ready(function() {
        AquaLuxeWCAdmin.init();
    });

})(jQuery);