/**
 * AquaLuxe SEO Admin JavaScript
 *
 * @package AquaLuxe
 * @subpackage Modules/SEO
 * @since 1.0.0
 */

(function($) {
    'use strict';
    
    // SEO Admin
    var AquaLuxeSEOAdmin = {
        /**
         * Initialize
         */
        init: function() {
            this.bindEvents();
            this.initColorPicker();
            this.initMediaUploader();
            this.initSocialPreview();
            this.initSEOAnalysis();
        },
        
        /**
         * Bind events
         */
        bindEvents: function() {
            // Title input
            $('#aqualuxe_seo_title').on('input', this.updateTitleAnalysis);
            
            // Description input
            $('#aqualuxe_seo_description').on('input', this.updateDescriptionAnalysis);
            
            // Social preview tabs
            $('.aqualuxe-seo-social-preview-tab').on('click', this.switchSocialPreviewTab);
        },
        
        /**
         * Initialize color picker
         */
        initColorPicker: function() {
            // Check if color picker exists
            if ($.fn.wpColorPicker) {
                $('.aqualuxe-color-picker').wpColorPicker();
            }
        },
        
        /**
         * Initialize media uploader
         */
        initMediaUploader: function() {
            // Check if media uploader button exists
            if ($('.aqualuxe-seo-upload-image').length === 0) {
                return;
            }
            
            // Create media uploader
            var mediaUploader;
            
            // Handle upload button click
            $('.aqualuxe-seo-upload-image').on('click', function(e) {
                e.preventDefault();
                
                // Get input field
                var inputField = $(this).prev('input');
                
                // Check if media uploader exists
                if (mediaUploader) {
                    mediaUploader.open();
                    return;
                }
                
                // Create media uploader
                mediaUploader = wp.media({
                    title: 'Select Image',
                    button: {
                        text: 'Use this image'
                    },
                    multiple: false
                });
                
                // Handle selection
                mediaUploader.on('select', function() {
                    var attachment = mediaUploader.state().get('selection').first().toJSON();
                    
                    // Set input value
                    inputField.val(attachment.url);
                    
                    // Update preview
                    var previewContainer = inputField.parent().find('.aqualuxe-seo-image-preview');
                    
                    // Check if preview container exists
                    if (previewContainer.length === 0) {
                        // Create preview container
                        previewContainer = $('<div class="aqualuxe-seo-image-preview"><img src="' + attachment.url + '" alt=""></div>');
                        inputField.parent().append(previewContainer);
                    } else {
                        // Update preview image
                        previewContainer.find('img').attr('src', attachment.url);
                    }
                });
                
                // Open media uploader
                mediaUploader.open();
            });
        },
        
        /**
         * Initialize social preview
         */
        initSocialPreview: function() {
            // Check if social preview exists
            if ($('.aqualuxe-seo-social-preview').length === 0) {
                return;
            }
            
            // Show first tab
            $('.aqualuxe-seo-social-preview-tab:first').addClass('active');
            $('.aqualuxe-seo-social-preview-content:first').addClass('active');
            
            // Update Facebook preview
            this.updateFacebookPreview();
            
            // Update Twitter preview
            this.updateTwitterPreview();
            
            // Bind events
            $('#aqualuxe_seo_title').on('input', this.updateSocialPreview);
            $('#aqualuxe_seo_description').on('input', this.updateSocialPreview);
        },
        
        /**
         * Switch social preview tab
         */
        switchSocialPreviewTab: function() {
            // Get tab ID
            var tabId = $(this).data('tab');
            
            // Remove active class from all tabs
            $('.aqualuxe-seo-social-preview-tab').removeClass('active');
            $('.aqualuxe-seo-social-preview-content').removeClass('active');
            
            // Add active class to current tab
            $(this).addClass('active');
            $('#' + tabId).addClass('active');
        },
        
        /**
         * Update social preview
         */
        updateSocialPreview: function() {
            // Update Facebook preview
            AquaLuxeSEOAdmin.updateFacebookPreview();
            
            // Update Twitter preview
            AquaLuxeSEOAdmin.updateTwitterPreview();
        },
        
        /**
         * Update Facebook preview
         */
        updateFacebookPreview: function() {
            // Get title
            var title = $('#aqualuxe_seo_title').val();
            
            // Get description
            var description = $('#aqualuxe_seo_description').val();
            
            // Get URL
            var url = window.location.href;
            
            // Get domain
            var domain = window.location.hostname;
            
            // Update preview
            $('.aqualuxe-seo-facebook-preview-title').text(title || 'Title');
            $('.aqualuxe-seo-facebook-preview-description').text(description || 'Description');
            $('.aqualuxe-seo-facebook-preview-domain').text(domain);
        },
        
        /**
         * Update Twitter preview
         */
        updateTwitterPreview: function() {
            // Get title
            var title = $('#aqualuxe_seo_title').val();
            
            // Get description
            var description = $('#aqualuxe_seo_description').val();
            
            // Get URL
            var url = window.location.href;
            
            // Get domain
            var domain = window.location.hostname;
            
            // Update preview
            $('.aqualuxe-seo-twitter-preview-title').text(title || 'Title');
            $('.aqualuxe-seo-twitter-preview-description').text(description || 'Description');
            $('.aqualuxe-seo-twitter-preview-domain').text(domain);
        },
        
        /**
         * Initialize SEO analysis
         */
        initSEOAnalysis: function() {
            // Check if SEO analysis exists
            if ($('.aqualuxe-seo-analysis').length === 0) {
                return;
            }
            
            // Update title analysis
            this.updateTitleAnalysis();
            
            // Update description analysis
            this.updateDescriptionAnalysis();
        },
        
        /**
         * Update title analysis
         */
        updateTitleAnalysis: function() {
            // Get title
            var title = $('#aqualuxe_seo_title').val();
            
            // Get title length
            var titleLength = title ? title.length : 0;
            
            // Get title analysis element
            var titleAnalysis = $('.aqualuxe-seo-title-analysis');
            
            // Check title length
            if (titleLength === 0) {
                // No title
                titleAnalysis.removeClass('good ok bad').hide();
            } else if (titleLength < 30) {
                // Title too short
                titleAnalysis.removeClass('good ok').addClass('bad').text(aqualuxeSEO.i18n.titleTooShort).show();
            } else if (titleLength > 60) {
                // Title too long
                titleAnalysis.removeClass('good bad').addClass('ok').text(aqualuxeSEO.i18n.titleTooLong).show();
            } else {
                // Title good
                titleAnalysis.removeClass('ok bad').addClass('good').text(aqualuxeSEO.i18n.good).show();
            }
        },
        
        /**
         * Update description analysis
         */
        updateDescriptionAnalysis: function() {
            // Get description
            var description = $('#aqualuxe_seo_description').val();
            
            // Get description length
            var descriptionLength = description ? description.length : 0;
            
            // Get description analysis element
            var descriptionAnalysis = $('.aqualuxe-seo-description-analysis');
            
            // Check description length
            if (descriptionLength === 0) {
                // No description
                descriptionAnalysis.removeClass('good ok bad').hide();
            } else if (descriptionLength < 120) {
                // Description too short
                descriptionAnalysis.removeClass('good ok').addClass('bad').text(aqualuxeSEO.i18n.descTooShort).show();
            } else if (descriptionLength > 160) {
                // Description too long
                descriptionAnalysis.removeClass('good bad').addClass('ok').text(aqualuxeSEO.i18n.descTooLong).show();
            } else {
                // Description good
                descriptionAnalysis.removeClass('ok bad').addClass('good').text(aqualuxeSEO.i18n.good).show();
            }
        }
    };
    
    // Initialize on document ready
    $(document).ready(function() {
        AquaLuxeSEOAdmin.init();
    });
    
})(jQuery);