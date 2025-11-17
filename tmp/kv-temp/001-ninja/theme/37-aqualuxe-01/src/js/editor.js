/**
 * AquaLuxe Theme Editor JavaScript
 * This file handles the editor-specific functionality for the theme
 */

// Import editor styles
import '../css/editor.css';

// Wait for DOM to be ready
document.addEventListener('DOMContentLoaded', function() {
  // Initialize editor functionality
  AquaLuxeEditor.init();
});

/**
 * AquaLuxe Editor Namespace
 * Contains all editor-specific functionality
 */
const AquaLuxeEditor = {
  /**
   * Initialize all editor functionality
   */
  init() {
    this.setupCustomBlocks();
    this.setupCustomFormats();
    this.setupEditorStyles();
  },
  
  /**
   * Setup custom blocks for the editor
   */
  setupCustomBlocks() {
    // Check if wp and wp.blocks are available
    if (!window.wp || !window.wp.blocks) {
      return;
    }
    
    const { registerBlockStyle, unregisterBlockStyle } = wp.blocks;
    
    // Register custom button styles
    if (registerBlockStyle) {
      // Unregister default button styles
      unregisterBlockStyle('core/button', 'fill');
      unregisterBlockStyle('core/button', 'outline');
      
      // Register custom button styles
      registerBlockStyle('core/button', {
        name: 'primary',
        label: 'Primary',
        isDefault: true,
      });
      
      registerBlockStyle('core/button', {
        name: 'secondary',
        label: 'Secondary',
      });
      
      registerBlockStyle('core/button', {
        name: 'outline',
        label: 'Outline',
      });
      
      registerBlockStyle('core/button', {
        name: 'link',
        label: 'Link',
      });
      
      // Register custom heading styles
      registerBlockStyle('core/heading', {
        name: 'underline',
        label: 'Underline',
      });
      
      registerBlockStyle('core/heading', {
        name: 'fancy',
        label: 'Fancy',
      });
      
      // Register custom image styles
      registerBlockStyle('core/image', {
        name: 'rounded',
        label: 'Rounded',
      });
      
      registerBlockStyle('core/image', {
        name: 'shadow',
        label: 'Shadow',
      });
      
      // Register custom group styles
      registerBlockStyle('core/group', {
        name: 'card',
        label: 'Card',
      });
      
      registerBlockStyle('core/group', {
        name: 'glass',
        label: 'Glass Morphism',
      });
      
      // Register custom quote styles
      registerBlockStyle('core/quote', {
        name: 'fancy',
        label: 'Fancy',
      });
      
      // Register custom separator styles
      registerBlockStyle('core/separator', {
        name: 'wide',
        label: 'Wide',
      });
      
      registerBlockStyle('core/separator', {
        name: 'decorative',
        label: 'Decorative',
      });
    }
  },
  
  /**
   * Setup custom formats for the editor
   */
  setupCustomFormats() {
    // Check if wp and wp.richText are available
    if (!window.wp || !window.wp.richText) {
      return;
    }
    
    const { registerFormatType, toggleFormat } = wp.richText;
    const { RichTextToolbarButton } = wp.blockEditor;
    const { createElement } = wp.element;
    
    // Register highlight format
    if (registerFormatType) {
      registerFormatType('aqualuxe/highlight', {
        title: 'Highlight',
        tagName: 'mark',
        className: 'text-highlight',
        edit({ isActive, value, onChange }) {
          return createElement(
            RichTextToolbarButton, {
              icon: 'admin-customizer',
              title: 'Highlight',
              isActive,
              onClick() {
                onChange(
                  toggleFormat(value, {
                    type: 'aqualuxe/highlight',
                  })
                );
              },
            }
          );
        },
      });
      
      // Register small caps format
      registerFormatType('aqualuxe/small-caps', {
        title: 'Small Caps',
        tagName: 'span',
        className: 'small-caps',
        edit({ isActive, value, onChange }) {
          return createElement(
            RichTextToolbarButton, {
              icon: 'editor-textcolor',
              title: 'Small Caps',
              isActive,
              onClick() {
                onChange(
                  toggleFormat(value, {
                    type: 'aqualuxe/small-caps',
                  })
                );
              },
            }
          );
        },
      });
      
      // Register drop cap format
      registerFormatType('aqualuxe/drop-cap', {
        title: 'Drop Cap',
        tagName: 'span',
        className: 'drop-cap',
        edit({ isActive, value, onChange }) {
          return createElement(
            RichTextToolbarButton, {
              icon: 'editor-paragraph',
              title: 'Drop Cap',
              isActive,
              onClick() {
                onChange(
                  toggleFormat(value, {
                    type: 'aqualuxe/drop-cap',
                  })
                );
              },
            }
          );
        },
      });
    }
  },
  
  /**
   * Setup editor styles
   */
  setupEditorStyles() {
    // Check if wp and wp.data are available
    if (!window.wp || !window.wp.data) {
      return;
    }
    
    const { select, subscribe } = wp.data;
    
    // Add custom classes to the editor
    const editorSelector = '.editor-styles-wrapper';
    const editor = document.querySelector(editorSelector);
    
    if (editor) {
      // Add theme classes to editor
      editor.classList.add('bg-white', 'text-secondary-700');
      
      // Add custom editor styles based on theme settings
      const customStyles = document.createElement('style');
      customStyles.id = 'aqualuxe-editor-custom-styles';
      document.head.appendChild(customStyles);
      
      // Update editor styles when settings change
      if (subscribe) {
        subscribe(() => {
          const settings = select('core/editor').getEditorSettings();
          if (settings && settings.aqualuxeThemeSettings) {
            const { primaryColor, secondaryColor, accentColor, bodyFont, headingFont } = settings.aqualuxeThemeSettings;
            
            let css = '';
            
            if (primaryColor) {
              css += `
                ${editorSelector} .has-primary-color { color: ${primaryColor}; }
                ${editorSelector} .has-primary-background-color { background-color: ${primaryColor}; }
              `;
            }
            
            if (secondaryColor) {
              css += `
                ${editorSelector} .has-secondary-color { color: ${secondaryColor}; }
                ${editorSelector} .has-secondary-background-color { background-color: ${secondaryColor}; }
              `;
            }
            
            if (accentColor) {
              css += `
                ${editorSelector} .has-accent-color { color: ${accentColor}; }
                ${editorSelector} .has-accent-background-color { background-color: ${accentColor}; }
              `;
            }
            
            if (bodyFont) {
              css += `
                ${editorSelector} { font-family: ${bodyFont}; }
              `;
            }
            
            if (headingFont) {
              css += `
                ${editorSelector} h1, 
                ${editorSelector} h2, 
                ${editorSelector} h3, 
                ${editorSelector} h4, 
                ${editorSelector} h5, 
                ${editorSelector} h6 { 
                  font-family: ${headingFont}; 
                }
              `;
            }
            
            customStyles.textContent = css;
          }
        });
      }
    }
  }
};

// Export for potential use in other modules
export default AquaLuxeEditor;