/**
 * Customizer JavaScript for AquaLuxe Theme
 * 
 * @package AquaLuxe
 * @version 1.0.0
 */

/**
 * AquaLuxe Customizer Class
 */
class AquaLuxeCustomizer {
    constructor() {
        this.init();
    }

    /**
     * Initialize customizer functionality
     */
    init() {
        // Wait for DOM and customizer API to be ready
        if (typeof wp !== 'undefined' && wp.customize) {
            this.onReady();
        } else {
            document.addEventListener('DOMContentLoaded', () => {
                if (typeof wp !== 'undefined' && wp.customize) {
                    this.onReady();
                }
            });
        }
    }

    /**
     * Execute when customizer is ready
     */
    onReady() {
        this.initColorControls();
        this.initTypographyControls();
        this.initLayoutControls();
        this.initPreviewUpdates();
        this.initConditionalControls();
        
        console.log('AquaLuxe Customizer initialized');
    }

    /**
     * Initialize color controls
     */
    initColorControls() {
        const colorSettings = [
            'aqualuxe_primary_color',
            'aqualuxe_secondary_color',
            'aqualuxe_accent_color',
            'aqualuxe_text_color',
            'aqualuxe_background_color',
            'aqualuxe_header_background',
            'aqualuxe_footer_background'
        ];

        colorSettings.forEach(setting => {
            wp.customize(setting, (value) => {
                value.bind((newValue) => {
                    this.updateColorInPreview(setting, newValue);
                });
            });
        });
    }

    /**
     * Update color in preview
     */
    updateColorInPreview(setting, color) {
        const styleId = `customizer-${setting}`;
        let style = document.getElementById(styleId);
        
        if (!style) {
            style = document.createElement('style');
            style.id = styleId;
            document.head.appendChild(style);
        }

        let css = '';

        switch (setting) {
            case 'aqualuxe_primary_color':
                css = `
                    :root { --color-primary: ${color}; }
                    .bg-primary-600 { background-color: ${color} !important; }
                    .text-primary-600 { color: ${color} !important; }
                    .border-primary-600 { border-color: ${color} !important; }
                `;
                break;
            case 'aqualuxe_secondary_color':
                css = `
                    :root { --color-secondary: ${color}; }
                    .bg-secondary-600 { background-color: ${color} !important; }
                    .text-secondary-600 { color: ${color} !important; }
                `;
                break;
            case 'aqualuxe_accent_color':
                css = `
                    :root { --color-accent: ${color}; }
                    .bg-accent-500 { background-color: ${color} !important; }
                    .text-accent-500 { color: ${color} !important; }
                `;
                break;
            case 'aqualuxe_text_color':
                css = `
                    body { color: ${color} !important; }
                `;
                break;
            case 'aqualuxe_background_color':
                css = `
                    body { background-color: ${color} !important; }
                `;
                break;
            case 'aqualuxe_header_background':
                css = `
                    .site-header { background-color: ${color} !important; }
                `;
                break;
            case 'aqualuxe_footer_background':
                css = `
                    .site-footer { background-color: ${color} !important; }
                `;
                break;
        }

        style.textContent = css;
    }

    /**
     * Initialize typography controls
     */
    initTypographyControls() {
        const typographySettings = [
            'aqualuxe_heading_font',
            'aqualuxe_body_font',
            'aqualuxe_font_size_base',
            'aqualuxe_line_height_base'
        ];

        typographySettings.forEach(setting => {
            wp.customize(setting, (value) => {
                value.bind((newValue) => {
                    this.updateTypographyInPreview(setting, newValue);
                });
            });
        });
    }

    /**
     * Update typography in preview
     */
    updateTypographyInPreview(setting, value) {
        const styleId = `customizer-typography-${setting}`;
        let style = document.getElementById(styleId);
        
        if (!style) {
            style = document.createElement('style');
            style.id = styleId;
            document.head.appendChild(style);
        }

        let css = '';

        switch (setting) {
            case 'aqualuxe_heading_font':
                css = `
                    h1, h2, h3, h4, h5, h6,
                    .font-serif {
                        font-family: ${value}, Georgia, serif !important;
                    }
                `;
                break;
            case 'aqualuxe_body_font':
                css = `
                    body,
                    .font-sans {
                        font-family: ${value}, -apple-system, BlinkMacSystemFont, sans-serif !important;
                    }
                `;
                break;
            case 'aqualuxe_font_size_base':
                css = `
                    body {
                        font-size: ${value}px !important;
                    }
                `;
                break;
            case 'aqualuxe_line_height_base':
                css = `
                    body {
                        line-height: ${value} !important;
                    }
                `;
                break;
        }

        style.textContent = css;
    }

    /**
     * Initialize layout controls
     */
    initLayoutControls() {
        const layoutSettings = [
            'aqualuxe_layout_style',
            'aqualuxe_container_width',
            'aqualuxe_sidebar_position',
            'aqualuxe_header_layout',
            'aqualuxe_footer_layout'
        ];

        layoutSettings.forEach(setting => {
            wp.customize(setting, (value) => {
                value.bind((newValue) => {
                    this.updateLayoutInPreview(setting, newValue);
                });
            });
        });
    }

    /**
     * Update layout in preview
     */
    updateLayoutInPreview(setting, value) {
        const body = document.body;

        switch (setting) {
            case 'aqualuxe_layout_style':
                body.className = body.className.replace(/layout-\w+/g, '');
                body.classList.add(`layout-${value}`);
                break;
            case 'aqualuxe_container_width':
                const styleId = `customizer-container-width`;
                let style = document.getElementById(styleId);
                
                if (!style) {
                    style = document.createElement('style');
                    style.id = styleId;
                    document.head.appendChild(style);
                }
                
                style.textContent = `
                    .container {
                        max-width: ${value}px !important;
                    }
                `;
                break;
            case 'aqualuxe_sidebar_position':
                body.className = body.className.replace(/sidebar-\w+/g, '');
                body.classList.add(`sidebar-${value}`);
                break;
            case 'aqualuxe_header_layout':
                const header = document.querySelector('.site-header');
                if (header) {
                    header.className = header.className.replace(/header-layout-\w+/g, '');
                    header.classList.add(`header-layout-${value}`);
                }
                break;
            case 'aqualuxe_footer_layout':
                const footer = document.querySelector('.site-footer');
                if (footer) {
                    footer.className = footer.className.replace(/footer-layout-\w+/g, '');
                    footer.classList.add(`footer-layout-${value}`);
                }
                break;
        }
    }

    /**
     * Initialize live preview updates
     */
    initPreviewUpdates() {
        // Site title and description
        wp.customize('blogname', (value) => {
            value.bind((newValue) => {
                const siteTitles = document.querySelectorAll('.site-title, .custom-logo-link');
                siteTitles.forEach(title => {
                    if (title.tagName === 'A') {
                        title.setAttribute('aria-label', newValue);
                    } else {
                        title.textContent = newValue;
                    }
                });
            });
        });

        wp.customize('blogdescription', (value) => {
            value.bind((newValue) => {
                const descriptions = document.querySelectorAll('.site-description');
                descriptions.forEach(desc => {
                    desc.textContent = newValue;
                });
            });
        });

        // Logo updates
        wp.customize('custom_logo', (value) => {
            value.bind((newValue) => {
                if (newValue) {
                    wp.customize.previewer.previewUrl.get();
                    // Refresh preview to show new logo
                    wp.customize.previewer.refresh();
                }
            });
        });

        // Custom text updates
        const textSettings = [
            'aqualuxe_hero_title',
            'aqualuxe_hero_subtitle',
            'aqualuxe_footer_text',
            'aqualuxe_copyright_text'
        ];

        textSettings.forEach(setting => {
            wp.customize(setting, (value) => {
                value.bind((newValue) => {
                    const elements = document.querySelectorAll(`[data-customize="${setting}"]`);
                    elements.forEach(element => {
                        element.textContent = newValue;
                    });
                });
            });
        });
    }

    /**
     * Initialize conditional controls
     */
    initConditionalControls() {
        // Show/hide controls based on other settings
        wp.customize('aqualuxe_enable_dark_mode', (value) => {
            value.bind((newValue) => {
                const darkModeControls = [
                    'aqualuxe_dark_mode_default',
                    'aqualuxe_dark_mode_auto_switch'
                ];

                darkModeControls.forEach(control => {
                    const controlObj = wp.customize.control(control);
                    if (controlObj) {
                        if (newValue) {
                            controlObj.container.show();
                        } else {
                            controlObj.container.hide();
                        }
                    }
                });
            });
        });

        // WooCommerce conditional controls
        wp.customize('aqualuxe_enable_woocommerce_features', (value) => {
            value.bind((newValue) => {
                const wooControls = [
                    'aqualuxe_shop_layout',
                    'aqualuxe_products_per_page',
                    'aqualuxe_enable_quick_view',
                    'aqualuxe_enable_wishlist'
                ];

                wooControls.forEach(control => {
                    const controlObj = wp.customize.control(control);
                    if (controlObj) {
                        if (newValue) {
                            controlObj.container.show();
                        } else {
                            controlObj.container.hide();
                        }
                    }
                });
            });
        });

        // Header layout conditional controls
        wp.customize('aqualuxe_header_layout', (value) => {
            value.bind((newValue) => {
                const transparentControls = [
                    'aqualuxe_header_transparent',
                    'aqualuxe_header_overlay_color'
                ];

                transparentControls.forEach(control => {
                    const controlObj = wp.customize.control(control);
                    if (controlObj) {
                        if (newValue === 'overlay') {
                            controlObj.container.show();
                        } else {
                            controlObj.container.hide();
                        }
                    }
                });
            });
        });
    }

    /**
     * Add custom control types
     */
    addCustomControls() {
        // Range slider control
        wp.customize.controlConstructor['range'] = wp.customize.Control.extend({
            ready: function() {
                const control = this;
                const slider = control.container.find('input[type="range"]');
                const output = control.container.find('.range-output');

                slider.on('input', function() {
                    const value = this.value;
                    output.text(value);
                    control.setting.set(value);
                });
            }
        });

        // Typography control
        wp.customize.controlConstructor['typography'] = wp.customize.Control.extend({
            ready: function() {
                const control = this;
                const inputs = control.container.find('select, input');

                inputs.on('change', function() {
                    const typography = {};
                    
                    inputs.each(function() {
                        const input = jQuery(this);
                        typography[input.data('property')] = input.val();
                    });
                    
                    control.setting.set(typography);
                });
            }
        });
    }
}

// Initialize customizer when ready
if (typeof wp !== 'undefined' && wp.customize) {
    wp.customize.bind('ready', () => {
        new AquaLuxeCustomizer();
    });
} else {
    document.addEventListener('DOMContentLoaded', () => {
        new AquaLuxeCustomizer();
    });
}