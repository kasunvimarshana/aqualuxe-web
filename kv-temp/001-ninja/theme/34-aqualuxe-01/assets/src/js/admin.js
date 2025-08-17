/**
 * AquaLuxe Theme Admin JavaScript
 *
 * This file contains JavaScript for the WordPress admin area.
 */

// Import admin styles
import '../css/admin.scss';

/**
 * Admin functionality
 */
const AquaLuxeAdmin = {
  /**
   * Initialize admin functionality
   */
  init() {
    this.setupCustomizerControls();
    this.setupMetaBoxes();
    this.setupMediaUploader();
  },

  /**
   * Setup customizer controls
   */
  setupCustomizerControls() {
    // Only run in the customizer
    if (!window.wp || !window.wp.customize) return;

    // Handle live preview for custom controls
    wp.customize.bind('ready', () => {
      // Typography controls
      this.setupTypographyControls();
      
      // Color controls
      this.setupColorControls();
      
      // Layout controls
      this.setupLayoutControls();
    });
  },

  /**
   * Setup typography controls
   */
  setupTypographyControls() {
    // Font family controls
    const fontFamilyControls = [
      'aqualuxe_body_font',
      'aqualuxe_headings_font',
      'aqualuxe_menu_font'
    ];

    fontFamilyControls.forEach(control => {
      wp.customize(control, value => {
        value.bind(newValue => {
          // Create font link if it doesn't exist
          let fontLink = document.getElementById(`${control}-css`);
          
          if (!fontLink) {
            fontLink = document.createElement('link');
            fontLink.id = `${control}-css`;
            fontLink.rel = 'stylesheet';
            document.head.appendChild(fontLink);
          }
          
          // Update font link
          const fontName = newValue.replace(' ', '+');
          fontLink.href = `https://fonts.googleapis.com/css?family=${fontName}:400,700&display=swap`;
          
          // Apply font to elements
          let selector = '';
          
          switch (control) {
            case 'aqualuxe_body_font':
              selector = 'body';
              break;
            case 'aqualuxe_headings_font':
              selector = 'h1, h2, h3, h4, h5, h6';
              break;
            case 'aqualuxe_menu_font':
              selector = '.main-navigation';
              break;
          }
          
          if (selector) {
            const style = document.createElement('style');
            style.textContent = `${selector} { font-family: "${newValue}", sans-serif; }`;
            document.head.appendChild(style);
          }
        });
      });
    });

    // Font size controls
    const fontSizeControls = [
      'aqualuxe_body_font_size',
      'aqualuxe_h1_font_size',
      'aqualuxe_h2_font_size',
      'aqualuxe_h3_font_size',
      'aqualuxe_h4_font_size',
      'aqualuxe_h5_font_size',
      'aqualuxe_h6_font_size'
    ];

    fontSizeControls.forEach(control => {
      wp.customize(control, value => {
        value.bind(newValue => {
          let selector = '';
          
          switch (control) {
            case 'aqualuxe_body_font_size':
              selector = 'body';
              break;
            case 'aqualuxe_h1_font_size':
              selector = 'h1';
              break;
            case 'aqualuxe_h2_font_size':
              selector = 'h2';
              break;
            case 'aqualuxe_h3_font_size':
              selector = 'h3';
              break;
            case 'aqualuxe_h4_font_size':
              selector = 'h4';
              break;
            case 'aqualuxe_h5_font_size':
              selector = 'h5';
              break;
            case 'aqualuxe_h6_font_size':
              selector = 'h6';
              break;
          }
          
          if (selector) {
            const style = document.createElement('style');
            style.textContent = `${selector} { font-size: ${newValue}px; }`;
            document.head.appendChild(style);
          }
        });
      });
    });
  },

  /**
   * Setup color controls
   */
  setupColorControls() {
    // Color controls
    const colorControls = [
      { setting: 'aqualuxe_primary_color', selector: '.primary-color, a, .btn-primary, .site-header__logo .site-title' },
      { setting: 'aqualuxe_secondary_color', selector: '.secondary-color, .btn-secondary, .widget-title' },
      { setting: 'aqualuxe_text_color', selector: 'body' },
      { setting: 'aqualuxe_heading_color', selector: 'h1, h2, h3, h4, h5, h6' },
      { setting: 'aqualuxe_background_color', selector: 'body', property: 'background-color' }
    ];

    colorControls.forEach(control => {
      wp.customize(control.setting, value => {
        value.bind(newValue => {
          const style = document.createElement('style');
          const property = control.property || 'color';
          style.textContent = `${control.selector} { ${property}: ${newValue}; }`;
          document.head.appendChild(style);
        });
      });
    });
  },

  /**
   * Setup layout controls
   */
  setupLayoutControls() {
    // Container width
    wp.customize('aqualuxe_container_width', value => {
      value.bind(newValue => {
        const style = document.createElement('style');
        style.textContent = `.container { max-width: ${newValue}px; }`;
        document.head.appendChild(style);
      });
    });

    // Sidebar position
    wp.customize('aqualuxe_sidebar_position', value => {
      value.bind(newValue => {
        const contentArea = document.querySelector('.content-area');
        const sidebar = document.querySelector('.widget-area');
        
        if (contentArea && sidebar) {
          if (newValue === 'left') {
            contentArea.classList.remove('has-sidebar-right');
            contentArea.classList.add('has-sidebar-left');
            document.body.classList.remove('sidebar-right');
            document.body.classList.add('sidebar-left');
          } else if (newValue === 'right') {
            contentArea.classList.remove('has-sidebar-left');
            contentArea.classList.add('has-sidebar-right');
            document.body.classList.remove('sidebar-left');
            document.body.classList.add('sidebar-right');
          } else {
            contentArea.classList.remove('has-sidebar-left', 'has-sidebar-right');
            document.body.classList.remove('sidebar-left', 'sidebar-right');
          }
        }
      });
    });
  },

  /**
   * Setup meta boxes
   */
  setupMetaBoxes() {
    // Only run on admin pages
    if (!document.body.classList.contains('wp-admin')) return;

    // Handle tabs in meta boxes
    const metaBoxTabs = document.querySelectorAll('.aqualuxe-meta-tabs');
    
    metaBoxTabs.forEach(tabContainer => {
      const tabs = tabContainer.querySelectorAll('.aqualuxe-tab');
      const tabContents = tabContainer.querySelectorAll('.aqualuxe-tab-content');
      
      tabs.forEach(tab => {
        tab.addEventListener('click', event => {
          event.preventDefault();
          
          // Remove active class from all tabs
          tabs.forEach(t => t.classList.remove('active'));
          
          // Add active class to clicked tab
          tab.classList.add('active');
          
          // Hide all tab contents
          tabContents.forEach(content => {
            content.style.display = 'none';
          });
          
          // Show selected tab content
          const tabId = tab.getAttribute('data-tab');
          const tabContent = tabContainer.querySelector(`.aqualuxe-tab-content[data-tab="${tabId}"]`);
          
          if (tabContent) {
            tabContent.style.display = 'block';
          }
        });
      });
      
      // Activate first tab by default
      if (tabs.length > 0) {
        tabs[0].click();
      }
    });
  },

  /**
   * Setup media uploader
   */
  setupMediaUploader() {
    // Only run on admin pages
    if (!document.body.classList.contains('wp-admin')) return;
    
    // Check if wp.media is available
    if (!window.wp || !window.wp.media) return;

    // Handle image upload buttons
    const imageUploadButtons = document.querySelectorAll('.aqualuxe-upload-image');
    
    imageUploadButtons.forEach(button => {
      button.addEventListener('click', event => {
        event.preventDefault();
        
        const inputField = document.getElementById(button.dataset.input);
        const previewImage = document.getElementById(button.dataset.preview);
        
        // Create media uploader
        const mediaUploader = wp.media({
          title: 'Select Image',
          button: {
            text: 'Use this image'
          },
          multiple: false
        });
        
        // When image is selected
        mediaUploader.on('select', () => {
          const attachment = mediaUploader.state().get('selection').first().toJSON();
          
          // Update input field
          if (inputField) {
            inputField.value = attachment.id;
          }
          
          // Update preview image
          if (previewImage) {
            previewImage.src = attachment.url;
            previewImage.style.display = 'block';
          }
        });
        
        // Open media uploader
        mediaUploader.open();
      });
    });
    
    // Handle image remove buttons
    const imageRemoveButtons = document.querySelectorAll('.aqualuxe-remove-image');
    
    imageRemoveButtons.forEach(button => {
      button.addEventListener('click', event => {
        event.preventDefault();
        
        const inputField = document.getElementById(button.dataset.input);
        const previewImage = document.getElementById(button.dataset.preview);
        
        // Clear input field
        if (inputField) {
          inputField.value = '';
        }
        
        // Clear preview image
        if (previewImage) {
          previewImage.src = '';
          previewImage.style.display = 'none';
        }
      });
    });
  }
};

// Initialize admin functionality
document.addEventListener('DOMContentLoaded', () => {
  AquaLuxeAdmin.init();
});