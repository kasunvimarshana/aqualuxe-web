/**
 * AquaLuxe Custom Blocks
 * Main entry point for custom Gutenberg blocks
 */

// Import blocks
import './feature-box';
import './testimonial';

// Import CSS
import '../css/editor.css';

// Register custom category
wp.blocks.updateCategory('aqualuxe', {
    icon: (
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
            <path d="M21.2,12c0,5.1-4.1,9.2-9.2,9.2S2.8,17.1,2.8,12S6.9,2.8,12,2.8S21.2,6.9,21.2,12z M12,4.8c-4,0-7.2,3.2-7.2,7.2s3.2,7.2,7.2,7.2s7.2-3.2,7.2-7.2S16,4.8,12,4.8z M13.5,7.5v3h3v3h-3v3h-3v-3h-3v-3h3v-3H13.5z" fill="currentColor" />
        </svg>
    )
});

// Add custom block category
wp.hooks.addFilter(
    'blocks.registerBlockType',
    'aqualuxe/custom-attributes',
    (settings, name) => {
        // Add custom attributes to core blocks if needed
        if (name === 'core/paragraph') {
            return {
                ...settings,
                attributes: {
                    ...settings.attributes,
                    // Add custom attributes here
                }
            };
        }
        return settings;
    }
);

// Add custom block styles
wp.domReady(() => {
    // Button styles
    wp.blocks.unregisterBlockStyle('core/button', 'fill');
    wp.blocks.unregisterBlockStyle('core/button', 'outline');
    
    wp.blocks.registerBlockStyle('core/button', {
        name: 'primary',
        label: 'Primary',
        isDefault: true,
    });
    
    wp.blocks.registerBlockStyle('core/button', {
        name: 'secondary',
        label: 'Secondary',
    });
    
    wp.blocks.registerBlockStyle('core/button', {
        name: 'outline',
        label: 'Outline',
    });
    
    wp.blocks.registerBlockStyle('core/button', {
        name: 'link',
        label: 'Link',
    });
    
    // Heading styles
    wp.blocks.registerBlockStyle('core/heading', {
        name: 'underline',
        label: 'Underline',
    });
    
    wp.blocks.registerBlockStyle('core/heading', {
        name: 'fancy',
        label: 'Fancy',
    });
    
    // Image styles
    wp.blocks.registerBlockStyle('core/image', {
        name: 'rounded',
        label: 'Rounded',
    });
    
    wp.blocks.registerBlockStyle('core/image', {
        name: 'shadow',
        label: 'Shadow',
    });
    
    // Group styles
    wp.blocks.registerBlockStyle('core/group', {
        name: 'card',
        label: 'Card',
    });
    
    wp.blocks.registerBlockStyle('core/group', {
        name: 'glass',
        label: 'Glass Morphism',
    });
    
    // Quote styles
    wp.blocks.registerBlockStyle('core/quote', {
        name: 'fancy',
        label: 'Fancy',
    });
    
    // Separator styles
    wp.blocks.registerBlockStyle('core/separator', {
        name: 'wide',
        label: 'Wide',
    });
    
    wp.blocks.registerBlockStyle('core/separator', {
        name: 'decorative',
        label: 'Decorative',
    });
});

// Add custom formats
const { registerFormatType, toggleFormat } = wp.richText;
const { RichTextToolbarButton } = wp.blockEditor;
const { createElement } = wp.element;

// Register highlight format
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