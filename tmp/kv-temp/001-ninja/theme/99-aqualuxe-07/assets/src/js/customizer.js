/**
 * Theme Customizer JavaScript
 * 
 * @package AquaLuxe
 * @since 1.0.0
 */

(function($) {
    'use strict';

    class AquaLuxeCustomizer {
        constructor() {
            this.init();
        }

        init() {
            this.bindPreviewEvents();
            this.initLivePreview();
            this.initColorControls();
            this.initTypographyControls();
            this.initLayoutControls();
        }

        bindPreviewEvents() {
            // Listen for customizer events
            wp.customize.bind('ready', () => {
                this.onCustomizerReady();
            });

            wp.customize.bind('change', (setting) => {
                this.onSettingChange(setting);
            });
        }

        onCustomizerReady() {
            console.log('AquaLuxe Customizer Ready');
            
            // Add custom CSS to preview frame
            this.addCustomPreviewStyles();
            
            // Initialize preview interactions
            this.initPreviewInteractions();
        }

        onSettingChange(setting) {
            const settingId = setting.id;
            const value = setting.get();
            
            // Handle different setting types
            if (settingId.includes('color')) {
                this.updateColorSetting(settingId, value);
            } else if (settingId.includes('font')) {
                this.updateFontSetting(settingId, value);
            } else if (settingId.includes('layout')) {
                this.updateLayoutSetting(settingId, value);
            }
        }

        initLivePreview() {
            // Logo
            wp.customize('custom_logo', (value) => {
                value.bind((newval) => {
                    if (newval) {
                        wp.media.attachment(newval).fetch().then(function() {
                            const attachment = wp.media.attachment(newval);
                            const logoUrl = attachment.get('sizes').medium?.url || attachment.get('url');
                            $('.custom-logo').attr('src', logoUrl);
                        });
                    } else {
                        $('.custom-logo').attr('src', '');
                    }
                });
            });

            // Site title
            wp.customize('blogname', (value) => {
                value.bind((newval) => {
                    $('.site-title').text(newval);
                });
            });

            // Site description
            wp.customize('blogdescription', (value) => {
                value.bind((newval) => {
                    $('.site-description').text(newval);
                });
            });

            // Primary color
            wp.customize('aqualuxe_primary_color', (value) => {
                value.bind((newval) => {
                    this.updateCSSProperty('--color-primary', newval);
                });
            });

            // Secondary color
            wp.customize('aqualuxe_secondary_color', (value) => {
                value.bind((newval) => {
                    this.updateCSSProperty('--color-secondary', newval);
                });
            });

            // Accent color
            wp.customize('aqualuxe_accent_color', (value) => {
                value.bind((newval) => {
                    this.updateCSSProperty('--color-accent', newval);
                });
            });

            // Typography settings
            wp.customize('aqualuxe_heading_font', (value) => {
                value.bind((newval) => {
                    this.updateFontFamily('headings', newval);
                });
            });

            wp.customize('aqualuxe_body_font', (value) => {
                value.bind((newval) => {
                    this.updateFontFamily('body', newval);
                });
            });

            // Layout settings
            wp.customize('aqualuxe_container_width', (value) => {
                value.bind((newval) => {
                    this.updateCSSProperty('--container-max-width', newval + 'px');
                });
            });

            wp.customize('aqualuxe_sidebar_width', (value) => {
                value.bind((newval) => {
                    this.updateCSSProperty('--sidebar-width', newval + '%');
                });
            });

            // Header settings
            wp.customize('aqualuxe_header_height', (value) => {
                value.bind((newval) => {
                    this.updateCSSProperty('--header-height', newval + 'px');
                });
            });

            wp.customize('aqualuxe_header_sticky', (value) => {
                value.bind((newval) => {
                    if (newval) {
                        $('.site-header').addClass('sticky');
                    } else {
                        $('.site-header').removeClass('sticky');
                    }
                });
            });

            // Footer settings
            wp.customize('aqualuxe_footer_background', (value) => {
                value.bind((newval) => {
                    $('.site-footer').css('background-color', newval);
                });
            });

            wp.customize('aqualuxe_footer_text_color', (value) => {
                value.bind((newval) => {
                    $('.site-footer').css('color', newval);
                });
            });
        }

        updateColorSetting(settingId, value) {
            // Map setting IDs to CSS custom properties
            const colorMap = {
                'aqualuxe_primary_color': '--color-primary',
                'aqualuxe_secondary_color': '--color-secondary',
                'aqualuxe_accent_color': '--color-accent',
                'aqualuxe_text_color': '--color-text',
                'aqualuxe_background_color': '--color-background',
                'aqualuxe_border_color': '--color-border'
            };

            const cssProperty = colorMap[settingId];
            if (cssProperty) {
                this.updateCSSProperty(cssProperty, value);
            }
        }

        updateFontSetting(settingId, value) {
            if (settingId.includes('heading')) {
                this.updateFontFamily('headings', value);
            } else if (settingId.includes('body')) {
                this.updateFontFamily('body', value);
            }
        }

        updateLayoutSetting(settingId, value) {
            // Handle layout-specific updates
            const layoutMap = {
                'aqualuxe_container_width': '--container-max-width',
                'aqualuxe_sidebar_width': '--sidebar-width',
                'aqualuxe_header_height': '--header-height'
            };

            const cssProperty = layoutMap[settingId];
            if (cssProperty) {
                const unit = settingId.includes('width') || settingId.includes('height') ? 'px' : '';
                this.updateCSSProperty(cssProperty, value + unit);
            }
        }

        updateCSSProperty(property, value) {
            // Update CSS custom property in preview frame
            const previewFrame = $('#customize-preview iframe').contents();
            const htmlElement = previewFrame.find('html')[0];
            
            if (htmlElement) {
                htmlElement.style.setProperty(property, value);
            }
        }

        updateFontFamily(target, fontFamily) {
            const previewFrame = $('#customize-preview iframe').contents();
            
            // Load Google Font if needed
            if (fontFamily && !this.isSystemFont(fontFamily)) {
                this.loadGoogleFont(fontFamily, previewFrame);
            }

            // Apply font family
            const cssProperty = target === 'headings' ? '--font-heading' : '--font-body';
            this.updateCSSProperty(cssProperty, fontFamily);
        }

        isSystemFont(fontFamily) {
            const systemFonts = [
                'Arial', 'Helvetica', 'Times', 'Times New Roman', 'Courier',
                'Courier New', 'Verdana', 'Georgia', 'Palatino', 'Garamond',
                'Bookman', 'Comic Sans MS', 'Trebuchet MS', 'Arial Black', 'Impact'
            ];
            
            return systemFonts.includes(fontFamily);
        }

        loadGoogleFont(fontFamily, targetDocument) {
            const fontUrl = `https://fonts.googleapis.com/css2?family=${fontFamily.replace(' ', '+')}:wght@300;400;500;600;700&display=swap`;
            
            // Check if font is already loaded
            const existingLink = targetDocument.find(`link[href*="${fontFamily.replace(' ', '+')}"]`);
            if (existingLink.length > 0) return;

            // Create and append font link
            const fontLink = $('<link>', {
                rel: 'stylesheet',
                href: fontUrl
            });
            
            targetDocument.find('head').append(fontLink);
        }

        initColorControls() {
            // Enhance color controls with additional functionality
            $('.customize-control-color').each(function() {
                const control = $(this);
                const input = control.find('input[type="text"]');
                
                // Add color palette
                if (!control.find('.color-palette').length) {
                    const palette = $('<div class="color-palette"></div>');
                    const colors = ['#0ea5e9', '#06b6d4', '#eab308', '#ef4444', '#10b981', '#8b5cf6'];
                    
                    colors.forEach(color => {
                        const swatch = $(`<button type="button" class="color-swatch" style="background-color: ${color}" data-color="${color}"></button>`);
                        swatch.on('click', function() {
                            input.val(color).trigger('change');
                        });
                        palette.append(swatch);
                    });
                    
                    control.append(palette);
                }
            });
        }

        initTypographyControls() {
            // Add font preview functionality
            $('.customize-control-select').each(function() {
                const control = $(this);
                const select = control.find('select');
                
                if (select.attr('data-customize-setting-link') && 
                    select.attr('data-customize-setting-link').includes('font')) {
                    
                    // Add font preview
                    const preview = $('<div class="font-preview">The quick brown fox jumps over the lazy dog</div>');
                    control.append(preview);
                    
                    // Update preview on change
                    select.on('change', function() {
                        const fontFamily = $(this).val();
                        preview.css('font-family', fontFamily);
                        
                        // Load Google Font for preview
                        if (!this.isSystemFont(fontFamily)) {
                            this.loadGoogleFont(fontFamily, $(document));
                        }
                    }.bind(this));
                }
            }.bind(this));
        }

        initLayoutControls() {
            // Add visual indicators for layout controls
            $('.customize-control-range').each(function() {
                const control = $(this);
                const input = control.find('input[type="range"]');
                const valueDisplay = $('<span class="range-value"></span>');
                
                control.find('.customize-control-title').append(valueDisplay);
                
                input.on('input', function() {
                    valueDisplay.text($(this).val());
                });
                
                // Initialize display
                valueDisplay.text(input.val());
            });
        }

        addCustomPreviewStyles() {
            const previewFrame = $('#customize-preview iframe').contents();
            const customStyles = `
                <style id="aqualuxe-customizer-styles">
                    .customizer-highlight {
                        outline: 2px solid #0073aa;
                        outline-offset: 2px;
                    }
                    
                    .customizer-transition * {
                        transition: all 0.3s ease;
                    }
                </style>
            `;
            
            previewFrame.find('head').append(customStyles);
        }

        initPreviewInteractions() {
            const previewFrame = $('#customize-preview iframe').contents();
            
            // Add hover effects for customizable elements
            previewFrame.find('.site-logo, .site-title, .site-description').on('mouseenter', function() {
                $(this).addClass('customizer-highlight');
            }).on('mouseleave', function() {
                $(this).removeClass('customizer-highlight');
            });

            // Add click to focus functionality
            previewFrame.find('.site-title').on('click', function() {
                wp.customize.control('blogname').focus();
            });

            previewFrame.find('.site-description').on('click', function() {
                wp.customize.control('blogdescription').focus();
            });
        }
    }

    // Initialize when customizer is ready
    $(document).ready(function() {
        if (typeof wp !== 'undefined' && wp.customize) {
            new AquaLuxeCustomizer();
        }
    });

})(jQuery);