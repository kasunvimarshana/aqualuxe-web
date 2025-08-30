/**
 * AquaLuxe Block Editor Scripts
 *
 * This file contains scripts for the block editor.
 */

(function() {
    'use strict';

    const { wp } = window;
    const { blocks, blockEditor, element, components, data } = wp;
    const { registerBlockStyle, unregisterBlockStyle } = blocks;
    const { createHigherOrderComponent } = element;
    const { InspectorControls } = blockEditor;
    const { PanelBody, ToggleControl, RangeControl, SelectControl } = components;
    const { select, dispatch } = data;

    /**
     * Add custom block styles
     */
    function registerCustomBlockStyles() {
        // Unregister default styles we don't want
        unregisterBlockStyle('core/button', 'outline');
        
        // Register custom button styles
        registerBlockStyle('core/button', {
            name: 'aqualuxe-outline',
            label: 'Outline',
        });
        
        registerBlockStyle('core/button', {
            name: 'aqualuxe-rounded',
            label: 'Rounded',
        });
        
        // Register custom image styles
        registerBlockStyle('core/image', {
            name: 'aqualuxe-rounded',
            label: 'Rounded',
        });
        
        registerBlockStyle('core/image', {
            name: 'aqualuxe-shadow',
            label: 'Shadow',
        });
        
        // Register custom group styles
        registerBlockStyle('core/group', {
            name: 'aqualuxe-card',
            label: 'Card',
        });
        
        registerBlockStyle('core/group', {
            name: 'aqualuxe-border',
            label: 'Border',
        });
        
        // Register custom quote styles
        registerBlockStyle('core/quote', {
            name: 'aqualuxe-fancy',
            label: 'Fancy',
        });
        
        // Register custom separator styles
        registerBlockStyle('core/separator', {
            name: 'aqualuxe-fancy',
            label: 'Fancy',
        });
    }

    /**
     * Add custom attributes to blocks
     */
    function addCustomAttributes() {
        // Add animation attribute to all blocks
        const enableAnimationAttribute = (settings, name) => {
            if (name.startsWith('core/')) {
                return {
                    ...settings,
                    attributes: {
                        ...settings.attributes,
                        aqualuxeAnimation: {
                            type: 'string',
                            default: '',
                        },
                        aqualuxeAnimationDelay: {
                            type: 'number',
                            default: 0,
                        },
                    },
                };
            }
            return settings;
        };
        
        wp.hooks.addFilter(
            'blocks.registerBlockType',
            'aqualuxe/custom-attributes',
            enableAnimationAttribute
        );
    }

    /**
     * Add custom controls to blocks
     */
    function addCustomControls() {
        const withCustomControls = createHigherOrderComponent((BlockEdit) => {
            return (props) => {
                // Only add controls to core blocks
                if (!props.name.startsWith('core/')) {
                    return <BlockEdit {...props} />;
                }
                
                const { attributes, setAttributes } = props;
                const { aqualuxeAnimation, aqualuxeAnimationDelay } = attributes;
                
                const animationOptions = [
                    { label: 'None', value: '' },
                    { label: 'Fade In', value: 'fade-in' },
                    { label: 'Fade Up', value: 'fade-up' },
                    { label: 'Fade Down', value: 'fade-down' },
                    { label: 'Fade Left', value: 'fade-left' },
                    { label: 'Fade Right', value: 'fade-right' },
                    { label: 'Zoom In', value: 'zoom-in' },
                    { label: 'Zoom Out', value: 'zoom-out' },
                    { label: 'Flip Up', value: 'flip-up' },
                    { label: 'Flip Down', value: 'flip-down' },
                ];
                
                return (
                    <>
                        <BlockEdit {...props} />
                        <InspectorControls>
                            <PanelBody
                                title="Animation Settings"
                                initialOpen={false}
                                icon="slides"
                            >
                                <SelectControl
                                    label="Animation Type"
                                    value={aqualuxeAnimation}
                                    options={animationOptions}
                                    onChange={(value) => setAttributes({ aqualuxeAnimation: value })}
                                />
                                
                                {aqualuxeAnimation && (
                                    <RangeControl
                                        label="Animation Delay (seconds)"
                                        value={aqualuxeAnimationDelay}
                                        onChange={(value) => setAttributes({ aqualuxeAnimationDelay: value })}
                                        min={0}
                                        max={3}
                                        step={0.1}
                                    />
                                )}
                            </PanelBody>
                        </InspectorControls>
                    </>
                );
            };
        }, 'withCustomControls');
        
        wp.hooks.addFilter(
            'editor.BlockEdit',
            'aqualuxe/custom-controls',
            withCustomControls
        );
    }

    /**
     * Add custom classes to blocks
     */
    function addCustomClasses() {
        const withCustomClasses = createHigherOrderComponent((BlockListBlock) => {
            return (props) => {
                // Get the attributes
                const { attributes, className, name } = props;
                
                // Only add classes to core blocks
                if (!name.startsWith('core/')) {
                    return <BlockListBlock {...props} />;
                }
                
                // Get animation attributes
                const { aqualuxeAnimation, aqualuxeAnimationDelay } = attributes;
                
                // Define the custom class
                let customClassName = className || '';
                
                // Add animation class if set
                if (aqualuxeAnimation) {
                    customClassName = `${customClassName} aqualuxe-animate aqualuxe-${aqualuxeAnimation}`;
                    
                    // Add delay class if set
                    if (aqualuxeAnimationDelay) {
                        customClassName = `${customClassName} aqualuxe-delay-${aqualuxeAnimationDelay.toString().replace('.', '')}`;
                    }
                }
                
                // Add the custom class
                return <BlockListBlock {...props} className={customClassName} />;
            };
        }, 'withCustomClasses');
        
        wp.hooks.addFilter(
            'editor.BlockListBlock',
            'aqualuxe/custom-classes',
            withCustomClasses
        );
    }

    /**
     * Save custom attributes to blocks
     */
    function saveCustomAttributes() {
        const saveCustomAttributes = (extraProps, blockType, attributes) => {
            // Only add attributes to core blocks
            if (!blockType.name.startsWith('core/')) {
                return extraProps;
            }
            
            // Get animation attributes
            const { aqualuxeAnimation, aqualuxeAnimationDelay } = attributes;
            
            // Add animation class if set
            if (aqualuxeAnimation) {
                extraProps.className = extraProps.className ? `${extraProps.className} aqualuxe-animate aqualuxe-${aqualuxeAnimation}` : `aqualuxe-animate aqualuxe-${aqualuxeAnimation}`;
                
                // Add delay class if set
                if (aqualuxeAnimationDelay) {
                    extraProps.className = `${extraProps.className} aqualuxe-delay-${aqualuxeAnimationDelay.toString().replace('.', '')}`;
                }
                
                // Add data attributes for JavaScript
                extraProps['data-animation'] = aqualuxeAnimation;
                extraProps['data-animation-delay'] = aqualuxeAnimationDelay;
            }
            
            return extraProps;
        };
        
        wp.hooks.addFilter(
            'blocks.getSaveContent.extraProps',
            'aqualuxe/save-custom-attributes',
            saveCustomAttributes
        );
    }

    /**
     * Initialize custom block editor features
     */
    function initBlockEditor() {
        registerCustomBlockStyles();
        addCustomAttributes();
        addCustomControls();
        addCustomClasses();
        saveCustomAttributes();
    }

    // Initialize when the editor is ready
    wp.domReady(initBlockEditor);
})();