/**
 * Admin JavaScript for AquaLuxe theme
 */

(function($) {
  'use strict';
  
  // AquaLuxe namespace
  window.AquaLuxe = window.AquaLuxe || {};
  
  // DOM ready
  $(function() {
    AquaLuxe.admin.init();
  });
  
  /**
   * Admin functionality
   */
  AquaLuxe.admin = {
    /**
     * Initialize admin functionality
     */
    init: function() {
      this.setupColorPickers();
      this.setupMediaUploads();
      this.setupModuleSettings();
      this.setupCustomizerControls();
      this.setupMetaBoxes();
    },
    
    /**
     * Setup color pickers
     */
    setupColorPickers: function() {
      $('.color-picker').wpColorPicker();
    },
    
    /**
     * Setup media uploads
     */
    setupMediaUploads: function() {
      // Image upload
      $('.upload-image').on('click', function(e) {
        e.preventDefault();
        
        const $button = $(this);
        const $imageField = $button.siblings('input[type="hidden"]');
        const $imagePreview = $button.siblings('.image-preview');
        const $removeButton = $button.siblings('.remove-image');
        
        // Create media frame
        const frame = wp.media({
          title: 'Select or Upload Image',
          button: {
            text: 'Use this image'
          },
          multiple: false
        });
        
        // When image selected
        frame.on('select', function() {
          const attachment = frame.state().get('selection').first().toJSON();
          
          // Set image ID
          $imageField.val(attachment.id);
          
          // Update preview
          if (attachment.sizes && attachment.sizes.thumbnail) {
            $imagePreview.html('<img src="' + attachment.sizes.thumbnail.url + '" alt="">');
          } else {
            $imagePreview.html('<img src="' + attachment.url + '" alt="">');
          }
          
          // Show remove button
          $removeButton.show();
        });
        
        // Open media frame
        frame.open();
      });
      
      // Remove image
      $('.remove-image').on('click', function(e) {
        e.preventDefault();
        
        const $button = $(this);
        const $imageField = $button.siblings('input[type="hidden"]');
        const $imagePreview = $button.siblings('.image-preview');
        
        // Clear image ID
        $imageField.val('');
        
        // Clear preview
        $imagePreview.html('');
        
        // Hide remove button
        $button.hide();
      });
    },
    
    /**
     * Setup module settings
     */
    setupModuleSettings: function() {
      // Module toggle
      $('.aqualuxe-module-toggle').on('change', function() {
        const $toggle = $(this);
        const moduleId = $toggle.data('module-id');
        const isActive = $toggle.is(':checked');
        const $dependents = $('[data-depends-on="' + moduleId + '"]');
        
        // Toggle dependent modules
        if (isActive) {
          $dependents.removeClass('disabled');
          $dependents.find('input[type="checkbox"]').prop('disabled', false);
        } else {
          $dependents.addClass('disabled');
          $dependents.find('input[type="checkbox"]').prop('disabled', true).prop('checked', false);
        }
      });
      
      // Initialize module dependencies
      $('.aqualuxe-module-toggle').each(function() {
        $(this).trigger('change');
      });
      
      // Module settings tabs
      $('.module-settings-tabs').each(function() {
        const $tabs = $(this);
        const $tabLinks = $tabs.find('.tab-link');
        const $tabContents = $tabs.find('.tab-content');
        
        $tabLinks.on('click', function(e) {
          e.preventDefault();
          
          const $link = $(this);
          const tabId = $link.attr('href');
          
          // Update active tab
          $tabLinks.removeClass('active');
          $link.addClass('active');
          
          // Show tab content
          $tabContents.removeClass('active');
          $(tabId).addClass('active');
        });
      });
    },
    
    /**
     * Setup customizer controls
     */
    setupCustomizerControls: function() {
      // Only run in customizer
      if (!wp.customize) {
        return;
      }
      
      // Header layout control
      wp.customize('aqualuxe_header_layout', function(setting) {
        setting.bind(function(value) {
          // Update header preview
          $('.site-header').removeClass(function(index, className) {
            return (className.match(/(^|\s)header-layout-\S+/g) || []).join(' ');
          }).addClass('header-layout-' + value);
        });
      });
      
      // Footer layout control
      wp.customize('aqualuxe_footer_layout', function(setting) {
        setting.bind(function(value) {
          // Update footer preview
          $('.site-footer').removeClass(function(index, className) {
            return (className.match(/(^|\s)footer-layout-\S+/g) || []).join(' ');
          }).addClass('footer-layout-' + value);
        });
      });
      
      // Dark mode control
      wp.customize('aqualuxe_enable_dark_mode', function(setting) {
        setting.bind(function(value) {
          if (value) {
            $('.dark-mode-toggle').show();
          } else {
            $('.dark-mode-toggle').hide();
          }
        });
      });
      
      // Default color scheme control
      wp.customize('aqualuxe_default_color_scheme', function(setting) {
        setting.bind(function(value) {
          if (value === 'dark') {
            $('html').addClass('dark-mode');
          } else {
            $('html').removeClass('dark-mode');
          }
        });
      });
      
      // Primary color control
      wp.customize('aqualuxe_primary_color', function(setting) {
        setting.bind(function(value) {
          // Update CSS variable
          document.documentElement.style.setProperty('--color-primary', value);
        });
      });
      
      // Secondary color control
      wp.customize('aqualuxe_secondary_color', function(setting) {
        setting.bind(function(value) {
          // Update CSS variable
          document.documentElement.style.setProperty('--color-secondary', value);
        });
      });
    },
    
    /**
     * Setup meta boxes
     */
    setupMetaBoxes: function() {
      // Page layout meta box
      $('.aqualuxe-page-layout-selector').on('change', function() {
        const $selector = $(this);
        const layout = $selector.val();
        
        // Update layout preview
        $('.layout-preview').removeClass('active');
        $('.layout-preview[data-layout="' + layout + '"]').addClass('active');
      });
      
      // Product options meta box
      $('.product-option-toggle').on('change', function() {
        const $toggle = $(this);
        const optionId = $toggle.data('option-id');
        const isEnabled = $toggle.is(':checked');
        const $optionSettings = $('#' + optionId + '-settings');
        
        if (isEnabled) {
          $optionSettings.slideDown();
        } else {
          $optionSettings.slideUp();
        }
      });
      
      // Initialize product option toggles
      $('.product-option-toggle').each(function() {
        $(this).trigger('change');
      });
    }
  };
  
})(jQuery);