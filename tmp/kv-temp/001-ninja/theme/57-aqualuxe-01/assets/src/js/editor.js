/**
 * Editor JavaScript file for the AquaLuxe theme
 * 
 * This file handles the editor functionality
 */

// Import editor styles
import '../scss/editor.scss';

// Wait for WordPress editor to be ready
window.addEventListener('DOMContentLoaded', function() {
    // Check if we're in the block editor
    if (window.wp && window.wp.blocks) {
        // Initialize editor customizations
        initEditorCustomizations();
    }
});

/**
 * Initialize editor customizations
 */
function initEditorCustomizations() {
    // Add custom block styles
    addCustomBlockStyles();
    
    // Add custom block patterns
    addCustomBlockPatterns();
    
    // Add custom block variations
    addCustomBlockVariations();
}

/**
 * Add custom block styles
 */
function addCustomBlockStyles() {
    const { registerBlockStyle } = wp.blocks;
    
    // Paragraph styles
    registerBlockStyle('core/paragraph', {
        name: 'lead',
        label: 'Lead Text'
    });
    
    registerBlockStyle('core/paragraph', {
        name: 'small',
        label: 'Small Text'
    });
    
    // Heading styles
    registerBlockStyle('core/heading', {
        name: 'underline',
        label: 'Underlined'
    });
    
    registerBlockStyle('core/heading', {
        name: 'highlight',
        label: 'Highlighted'
    });
    
    // Button styles
    registerBlockStyle('core/button', {
        name: 'outline',
        label: 'Outline'
    });
    
    registerBlockStyle('core/button', {
        name: 'rounded',
        label: 'Rounded'
    });
    
    // Image styles
    registerBlockStyle('core/image', {
        name: 'rounded',
        label: 'Rounded'
    });
    
    registerBlockStyle('core/image', {
        name: 'shadow',
        label: 'Shadow'
    });
    
    // Group styles
    registerBlockStyle('core/group', {
        name: 'card',
        label: 'Card'
    });
    
    registerBlockStyle('core/group', {
        name: 'shadow',
        label: 'Shadow'
    });
    
    // List styles
    registerBlockStyle('core/list', {
        name: 'checked',
        label: 'Checked List'
    });
    
    registerBlockStyle('core/list', {
        name: 'arrow',
        label: 'Arrow List'
    });
}

/**
 * Add custom block patterns
 */
function addCustomBlockPatterns() {
    const { registerBlockPattern, registerBlockPatternCategory } = wp.blockPatterns;
    
    // Register custom pattern category
    registerBlockPatternCategory('aqualuxe', {
        label: 'AquaLuxe'
    });
    
    // Hero section pattern
    registerBlockPattern(
        'aqualuxe/hero-section',
        {
            title: 'Hero Section',
            description: 'A hero section with heading, text, and a button',
            categories: ['aqualuxe'],
            content: `
                <!-- wp:cover {"url":"https://via.placeholder.com/1920x1080","id":123,"dimRatio":60,"overlayColor":"dark","align":"full","className":"aqualuxe-hero"} -->
                <div class="wp-block-cover alignfull aqualuxe-hero">
                    <span aria-hidden="true" class="wp-block-cover__background has-dark-background-color has-background-dim-60 has-background-dim"></span>
                    <img class="wp-block-cover__image-background" alt="" src="https://via.placeholder.com/1920x1080" data-object-fit="cover"/>
                    <div class="wp-block-cover__inner-container">
                        <!-- wp:heading {"textAlign":"center","level":1,"textColor":"white","fontSize":"huge"} -->
                        <h1 class="has-text-align-center has-white-color has-text-color has-huge-font-size">Welcome to AquaLuxe</h1>
                        <!-- /wp:heading -->
                        
                        <!-- wp:paragraph {"align":"center","textColor":"white","fontSize":"large"} -->
                        <p class="has-text-align-center has-white-color has-text-color has-large-font-size">Premium WordPress Theme for Modern Websites</p>
                        <!-- /wp:paragraph -->
                        
                        <!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
                        <div class="wp-block-buttons">
                            <!-- wp:button {"backgroundColor":"primary","textColor":"white"} -->
                            <div class="wp-block-button">
                                <a class="wp-block-button__link has-white-color has-primary-background-color has-text-color has-background">Get Started</a>
                            </div>
                            <!-- /wp:button -->
                            
                            <!-- wp:button {"className":"is-style-outline"} -->
                            <div class="wp-block-button is-style-outline">
                                <a class="wp-block-button__link">Learn More</a>
                            </div>
                            <!-- /wp:button -->
                        </div>
                        <!-- /wp:buttons -->
                    </div>
                </div>
                <!-- /wp:cover -->
            `,
        }
    );
    
    // Features section pattern
    registerBlockPattern(
        'aqualuxe/features-section',
        {
            title: 'Features Section',
            description: 'A section with three feature columns',
            categories: ['aqualuxe'],
            content: `
                <!-- wp:group {"align":"full","backgroundColor":"light","className":"aqualuxe-features-section"} -->
                <div class="wp-block-group alignfull aqualuxe-features-section has-light-background-color has-background">
                    <!-- wp:heading {"textAlign":"center"} -->
                    <h2 class="has-text-align-center">Key Features</h2>
                    <!-- /wp:heading -->
                    
                    <!-- wp:paragraph {"align":"center"} -->
                    <p class="has-text-align-center">Discover what makes our theme special</p>
                    <!-- /wp:paragraph -->
                    
                    <!-- wp:columns -->
                    <div class="wp-block-columns">
                        <!-- wp:column -->
                        <div class="wp-block-column">
                            <!-- wp:group {"className":"is-style-card"} -->
                            <div class="wp-block-group is-style-card">
                                <!-- wp:image {"align":"center","width":64,"height":64,"sizeSlug":"full"} -->
                                <figure class="wp-block-image aligncenter size-full is-resized">
                                    <img src="https://via.placeholder.com/64" alt="Feature 1" width="64" height="64"/>
                                </figure>
                                <!-- /wp:image -->
                                
                                <!-- wp:heading {"textAlign":"center","level":3} -->
                                <h3 class="has-text-align-center">Responsive Design</h3>
                                <!-- /wp:heading -->
                                
                                <!-- wp:paragraph {"align":"center"} -->
                                <p class="has-text-align-center">Looks great on any device, from mobile phones to desktop computers.</p>
                                <!-- /wp:paragraph -->
                            </div>
                            <!-- /wp:group -->
                        </div>
                        <!-- /wp:column -->
                        
                        <!-- wp:column -->
                        <div class="wp-block-column">
                            <!-- wp:group {"className":"is-style-card"} -->
                            <div class="wp-block-group is-style-card">
                                <!-- wp:image {"align":"center","width":64,"height":64,"sizeSlug":"full"} -->
                                <figure class="wp-block-image aligncenter size-full is-resized">
                                    <img src="https://via.placeholder.com/64" alt="Feature 2" width="64" height="64"/>
                                </figure>
                                <!-- /wp:image -->
                                
                                <!-- wp:heading {"textAlign":"center","level":3} -->
                                <h3 class="has-text-align-center">Modular Design</h3>
                                <!-- /wp:heading -->
                                
                                <!-- wp:paragraph {"align":"center"} -->
                                <p class="has-text-align-center">Customize your site with our modular components and features.</p>
                                <!-- /wp:paragraph -->
                            </div>
                            <!-- /wp:group -->
                        </div>
                        <!-- /wp:column -->
                        
                        <!-- wp:column -->
                        <div class="wp-block-column">
                            <!-- wp:group {"className":"is-style-card"} -->
                            <div class="wp-block-group is-style-card">
                                <!-- wp:image {"align":"center","width":64,"height":64,"sizeSlug":"full"} -->
                                <figure class="wp-block-image aligncenter size-full is-resized">
                                    <img src="https://via.placeholder.com/64" alt="Feature 3" width="64" height="64"/>
                                </figure>
                                <!-- /wp:image -->
                                
                                <!-- wp:heading {"textAlign":"center","level":3} -->
                                <h3 class="has-text-align-center">Performance Optimized</h3>
                                <!-- /wp:heading -->
                                
                                <!-- wp:paragraph {"align":"center"} -->
                                <p class="has-text-align-center">Built with performance in mind for fast loading times.</p>
                                <!-- /wp:paragraph -->
                            </div>
                            <!-- /wp:group -->
                        </div>
                        <!-- /wp:column -->
                    </div>
                    <!-- /wp:columns -->
                </div>
                <!-- /wp:group -->
            `,
        }
    );
}

/**
 * Add custom block variations
 */
function addCustomBlockVariations() {
    const { registerBlockVariation } = wp.blocks;
    
    // Button variations
    registerBlockVariation('core/button', {
        name: 'primary-button',
        title: 'Primary Button',
        attributes: {
            className: 'is-style-primary',
            backgroundColor: 'primary',
            textColor: 'white'
        },
        isDefault: true
    });
    
    registerBlockVariation('core/button', {
        name: 'secondary-button',
        title: 'Secondary Button',
        attributes: {
            className: 'is-style-secondary',
            backgroundColor: 'secondary',
            textColor: 'white'
        }
    });
    
    // Cover variations
    registerBlockVariation('core/cover', {
        name: 'hero-cover',
        title: 'Hero Cover',
        attributes: {
            className: 'is-style-hero',
            dimRatio: 60,
            overlayColor: 'dark',
            align: 'full',
            minHeight: 80
        }
    });
    
    // Group variations
    registerBlockVariation('core/group', {
        name: 'content-section',
        title: 'Content Section',
        attributes: {
            className: 'aqualuxe-section',
            backgroundColor: 'light',
            align: 'full'
        }
    });
}