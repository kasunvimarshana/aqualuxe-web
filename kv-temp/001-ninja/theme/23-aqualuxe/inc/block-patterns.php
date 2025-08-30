<?php
/**
 * AquaLuxe Block Patterns
 *
 * @package AquaLuxe
 */

/**
 * Register custom block patterns and categories.
 */
function aqualuxe_register_block_patterns() {
    // Register block pattern categories
    if ( function_exists( 'register_block_pattern_category' ) ) {
        register_block_pattern_category(
            'aqualuxe',
            array( 'label' => __( 'AquaLuxe', 'aqualuxe' ) )
        );
        
        register_block_pattern_category(
            'aqualuxe-headers',
            array( 'label' => __( 'AquaLuxe Headers', 'aqualuxe' ) )
        );
        
        register_block_pattern_category(
            'aqualuxe-features',
            array( 'label' => __( 'AquaLuxe Features', 'aqualuxe' ) )
        );
        
        register_block_pattern_category(
            'aqualuxe-cta',
            array( 'label' => __( 'AquaLuxe Call to Action', 'aqualuxe' ) )
        );
        
        register_block_pattern_category(
            'aqualuxe-testimonials',
            array( 'label' => __( 'AquaLuxe Testimonials', 'aqualuxe' ) )
        );
        
        register_block_pattern_category(
            'aqualuxe-team',
            array( 'label' => __( 'AquaLuxe Team', 'aqualuxe' ) )
        );
        
        register_block_pattern_category(
            'aqualuxe-pricing',
            array( 'label' => __( 'AquaLuxe Pricing', 'aqualuxe' ) )
        );
    }

    // Register block patterns
    if ( function_exists( 'register_block_pattern' ) ) {
        // Hero Section with Image
        register_block_pattern(
            'aqualuxe/hero-with-image',
            array(
                'title'       => __( 'Hero with Image', 'aqualuxe' ),
                'description' => __( 'A hero section with heading, text, buttons and an image.', 'aqualuxe' ),
                'categories'  => array( 'aqualuxe', 'aqualuxe-headers' ),
                'content'     => '<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"4rem","bottom":"4rem"}}},"backgroundColor":"primary","textColor":"white","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull has-white-color has-primary-background-color has-text-color has-background" style="padding-top:4rem;padding-bottom:4rem"><!-- wp:columns {"verticalAlignment":"center","align":"wide"} -->
<div class="wp-block-columns alignwide are-vertically-aligned-center"><!-- wp:column {"verticalAlignment":"center","width":"55%"} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:55%"><!-- wp:heading {"level":1,"fontSize":"x-large"} -->
<h1 class="wp-block-heading has-x-large-font-size">Premium WordPress Theme for Modern Websites</h1>
<!-- /wp:heading -->

<!-- wp:paragraph {"fontSize":"medium"} -->
<p class="has-medium-font-size">A versatile and elegant theme for businesses, portfolios, and online stores. Built with Tailwind CSS for modern, responsive designs.</p>
<!-- /wp:paragraph -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button {"backgroundColor":"white","textColor":"primary"} -->
<div class="wp-block-button"><a class="wp-block-button__link has-primary-color has-white-background-color has-text-color has-background wp-element-button">Get Started</a></div>
<!-- /wp:button -->

<!-- wp:button {"className":"is-style-outline"} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button">Learn More</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"center","width":"45%"} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:45%"><!-- wp:image {"align":"center","sizeSlug":"large","linkDestination":"none","className":"is-style-rounded"} -->
<figure class="wp-block-image aligncenter size-large is-style-rounded"><img src="https://via.placeholder.com/600x400" alt="Hero Image"/></figure>
<!-- /wp:image --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group -->',
            )
        );

        // Features Grid
        register_block_pattern(
            'aqualuxe/features-grid',
            array(
                'title'       => __( 'Features Grid', 'aqualuxe' ),
                'description' => __( 'A grid of features with icons, headings and text.', 'aqualuxe' ),
                'categories'  => array( 'aqualuxe', 'aqualuxe-features' ),
                'content'     => '<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"4rem","bottom":"4rem"}}},"backgroundColor":"background","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull has-background-background-color has-background" style="padding-top:4rem;padding-bottom:4rem"><!-- wp:heading {"textAlign":"center","style":{"spacing":{"margin":{"bottom":"1rem"}}}} -->
<h2 class="wp-block-heading has-text-align-center" style="margin-bottom:1rem">Key Features</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center","style":{"spacing":{"margin":{"bottom":"3rem"}}}} -->
<p class="has-text-align-center" style="margin-bottom:3rem">Discover what makes our theme stand out from the crowd</p>
<!-- /wp:paragraph -->

<!-- wp:columns {"align":"wide"} -->
<div class="wp-block-columns alignwide"><!-- wp:column -->
<div class="wp-block-column"><!-- wp:group {"style":{"border":{"radius":"8px"},"spacing":{"padding":{"top":"2rem","right":"2rem","bottom":"2rem","left":"2rem"}}},"backgroundColor":"white","className":"is-style-aqualuxe-card"} -->
<div class="wp-block-group is-style-aqualuxe-card has-white-background-color has-background" style="border-radius:8px;padding-top:2rem;padding-right:2rem;padding-bottom:2rem;padding-left:2rem"><!-- wp:image {"align":"center","width":64,"height":64,"sizeSlug":"large"} -->
<figure class="wp-block-image aligncenter size-large is-resized"><img src="https://via.placeholder.com/64x64" alt="" width="64" height="64"/></figure>
<!-- /wp:image -->

<!-- wp:heading {"textAlign":"center","level":3,"fontSize":"large"} -->
<h3 class="wp-block-heading has-text-align-center has-large-font-size">Responsive Design</h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">Looks great on all devices, from mobile phones to widescreen monitors.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:group {"style":{"border":{"radius":"8px"},"spacing":{"padding":{"top":"2rem","right":"2rem","bottom":"2rem","left":"2rem"}}},"backgroundColor":"white","className":"is-style-aqualuxe-card"} -->
<div class="wp-block-group is-style-aqualuxe-card has-white-background-color has-background" style="border-radius:8px;padding-top:2rem;padding-right:2rem;padding-bottom:2rem;padding-left:2rem"><!-- wp:image {"align":"center","width":64,"height":64,"sizeSlug":"large"} -->
<figure class="wp-block-image aligncenter size-large is-resized"><img src="https://via.placeholder.com/64x64" alt="" width="64" height="64"/></figure>
<!-- /wp:image -->

<!-- wp:heading {"textAlign":"center","level":3,"fontSize":"large"} -->
<h3 class="wp-block-heading has-text-align-center has-large-font-size">Customizable</h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">Easily customize colors, layouts, and more to match your brand.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:group {"style":{"border":{"radius":"8px"},"spacing":{"padding":{"top":"2rem","right":"2rem","bottom":"2rem","left":"2rem"}}},"backgroundColor":"white","className":"is-style-aqualuxe-card"} -->
<div class="wp-block-group is-style-aqualuxe-card has-white-background-color has-background" style="border-radius:8px;padding-top:2rem;padding-right:2rem;padding-bottom:2rem;padding-left:2rem"><!-- wp:image {"align":"center","width":64,"height":64,"sizeSlug":"large"} -->
<figure class="wp-block-image aligncenter size-large is-resized"><img src="https://via.placeholder.com/64x64" alt="" width="64" height="64"/></figure>
<!-- /wp:image -->

<!-- wp:heading {"textAlign":"center","level":3,"fontSize":"large"} -->
<h3 class="wp-block-heading has-text-align-center has-large-font-size">WooCommerce Ready</h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">Fully compatible with WooCommerce for your online store needs.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group -->',
            )
        );

        // Call to Action
        register_block_pattern(
            'aqualuxe/call-to-action',
            array(
                'title'       => __( 'Call to Action', 'aqualuxe' ),
                'description' => __( 'A call to action section with heading, text and buttons.', 'aqualuxe' ),
                'categories'  => array( 'aqualuxe', 'aqualuxe-cta' ),
                'content'     => '<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"4rem","bottom":"4rem"}}},"backgroundColor":"primary","textColor":"white","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull has-white-color has-primary-background-color has-text-color has-background" style="padding-top:4rem;padding-bottom:4rem"><!-- wp:group {"align":"wide","layout":{"type":"flex","orientation":"vertical","justifyContent":"center"}} -->
<div class="wp-block-group alignwide"><!-- wp:heading {"textAlign":"center","fontSize":"x-large"} -->
<h2 class="wp-block-heading has-text-align-center has-x-large-font-size">Ready to Get Started?</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center","fontSize":"medium"} -->
<p class="has-text-align-center has-medium-font-size">Join thousands of satisfied customers using AquaLuxe for their websites.</p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons"><!-- wp:button {"backgroundColor":"white","textColor":"primary"} -->
<div class="wp-block-button"><a class="wp-block-button__link has-primary-color has-white-background-color has-text-color has-background wp-element-button">Get AquaLuxe Now</a></div>
<!-- /wp:button -->

<!-- wp:button {"className":"is-style-outline"} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button">Learn More</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->',
            )
        );

        // Testimonials
        register_block_pattern(
            'aqualuxe/testimonials',
            array(
                'title'       => __( 'Testimonials', 'aqualuxe' ),
                'description' => __( 'A testimonials section with quotes and images.', 'aqualuxe' ),
                'categories'  => array( 'aqualuxe', 'aqualuxe-testimonials' ),
                'content'     => '<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"4rem","bottom":"4rem"}}},"backgroundColor":"background","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull has-background-background-color has-background" style="padding-top:4rem;padding-bottom:4rem"><!-- wp:heading {"textAlign":"center","style":{"spacing":{"margin":{"bottom":"1rem"}}}} -->
<h2 class="wp-block-heading has-text-align-center" style="margin-bottom:1rem">What Our Customers Say</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center","style":{"spacing":{"margin":{"bottom":"3rem"}}}} -->
<p class="has-text-align-center" style="margin-bottom:3rem">Read testimonials from our satisfied customers</p>
<!-- /wp:paragraph -->

<!-- wp:columns {"align":"wide"} -->
<div class="wp-block-columns alignwide"><!-- wp:column -->
<div class="wp-block-column"><!-- wp:group {"style":{"border":{"radius":"8px"},"spacing":{"padding":{"top":"2rem","right":"2rem","bottom":"2rem","left":"2rem"}}},"backgroundColor":"white","className":"is-style-aqualuxe-card"} -->
<div class="wp-block-group is-style-aqualuxe-card has-white-background-color has-background" style="border-radius:8px;padding-top:2rem;padding-right:2rem;padding-bottom:2rem;padding-left:2rem"><!-- wp:paragraph {"align":"center","style":{"typography":{"fontSize":"18px"}}} -->
<p class="has-text-align-center" style="font-size:18px"><em>"AquaLuxe has transformed our online presence. The theme is not only beautiful but also incredibly easy to customize. Our sales have increased by 30% since we launched our new website!"</em></p>
<!-- /wp:paragraph -->

<!-- wp:group {"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"center"}} -->
<div class="wp-block-group"><!-- wp:image {"width":60,"height":60,"sizeSlug":"full","linkDestination":"none","className":"is-style-rounded"} -->
<figure class="wp-block-image size-full is-resized is-style-rounded"><img src="https://via.placeholder.com/60x60" alt="Jane Smith" width="60" height="60"/></figure>
<!-- /wp:image -->

<!-- wp:group -->
<div class="wp-block-group"><!-- wp:paragraph {"style":{"typography":{"fontStyle":"normal","fontWeight":"600"}}} -->
<p style="font-style:normal;font-weight:600">Jane Smith</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"style":{"typography":{"fontSize":"14px"}}} -->
<p style="font-size:14px">CEO, Oceanview Spa</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:group {"style":{"border":{"radius":"8px"},"spacing":{"padding":{"top":"2rem","right":"2rem","bottom":"2rem","left":"2rem"}}},"backgroundColor":"white","className":"is-style-aqualuxe-card"} -->
<div class="wp-block-group is-style-aqualuxe-card has-white-background-color has-background" style="border-radius:8px;padding-top:2rem;padding-right:2rem;padding-bottom:2rem;padding-left:2rem"><!-- wp:paragraph {"align":"center","style":{"typography":{"fontSize":"18px"}}} -->
<p class="has-text-align-center" style="font-size:18px"><em>"As a developer, I appreciate the clean code and thoughtful architecture of AquaLuxe. It\'s been a joy to work with and my clients are thrilled with the results. Highly recommended!"</em></p>
<!-- /wp:paragraph -->

<!-- wp:group {"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"center"}} -->
<div class="wp-block-group"><!-- wp:image {"width":60,"height":60,"sizeSlug":"full","linkDestination":"none","className":"is-style-rounded"} -->
<figure class="wp-block-image size-full is-resized is-style-rounded"><img src="https://via.placeholder.com/60x60" alt="Michael Johnson" width="60" height="60"/></figure>
<!-- /wp:image -->

<!-- wp:group -->
<div class="wp-block-group"><!-- wp:paragraph {"style":{"typography":{"fontStyle":"normal","fontWeight":"600"}}} -->
<p style="font-style:normal;font-weight:600">Michael Johnson</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"style":{"typography":{"fontSize":"14px"}}} -->
<p style="font-size:14px">Web Developer, DevStudio</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:group {"style":{"border":{"radius":"8px"},"spacing":{"padding":{"top":"2rem","right":"2rem","bottom":"2rem","left":"2rem"}}},"backgroundColor":"white","className":"is-style-aqualuxe-card"} -->
<div class="wp-block-group is-style-aqualuxe-card has-white-background-color has-background" style="border-radius:8px;padding-top:2rem;padding-right:2rem;padding-bottom:2rem;padding-left:2rem"><!-- wp:paragraph {"align":"center","style":{"typography":{"fontSize":"18px"}}} -->
<p class="has-text-align-center" style="font-size:18px"><em>"The WooCommerce integration is flawless. Setting up our online store was a breeze, and the checkout process is smooth and professional. Our customers love the shopping experience!"</em></p>
<!-- /wp:paragraph -->

<!-- wp:group {"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"center"}} -->
<div class="wp-block-group"><!-- wp:image {"width":60,"height":60,"sizeSlug":"full","linkDestination":"none","className":"is-style-rounded"} -->
<figure class="wp-block-image size-full is-resized is-style-rounded"><img src="https://via.placeholder.com/60x60" alt="Sarah Williams" width="60" height="60"/></figure>
<!-- /wp:image -->

<!-- wp:group -->
<div class="wp-block-group"><!-- wp:paragraph {"style":{"typography":{"fontStyle":"normal","fontWeight":"600"}}} -->
<p style="font-style:normal;font-weight:600">Sarah Williams</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"style":{"typography":{"fontSize":"14px"}}} -->
<p style="font-size:14px">E-commerce Manager, Aqua Boutique</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group -->',
            )
        );

        // Team Members
        register_block_pattern(
            'aqualuxe/team-members',
            array(
                'title'       => __( 'Team Members', 'aqualuxe' ),
                'description' => __( 'A section to display team members with images and social links.', 'aqualuxe' ),
                'categories'  => array( 'aqualuxe', 'aqualuxe-team' ),
                'content'     => '<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"4rem","bottom":"4rem"}}},"backgroundColor":"background","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull has-background-background-color has-background" style="padding-top:4rem;padding-bottom:4rem"><!-- wp:heading {"textAlign":"center","style":{"spacing":{"margin":{"bottom":"1rem"}}}} -->
<h2 class="wp-block-heading has-text-align-center" style="margin-bottom:1rem">Meet Our Team</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center","style":{"spacing":{"margin":{"bottom":"3rem"}}}} -->
<p class="has-text-align-center" style="margin-bottom:3rem">The talented people behind our success</p>
<!-- /wp:paragraph -->

<!-- wp:columns {"align":"wide"} -->
<div class="wp-block-columns alignwide"><!-- wp:column -->
<div class="wp-block-column"><!-- wp:group {"style":{"border":{"radius":"8px"},"spacing":{"padding":{"top":"0px","right":"0px","bottom":"0px","left":"0px"}}},"backgroundColor":"white","className":"is-style-aqualuxe-card"} -->
<div class="wp-block-group is-style-aqualuxe-card has-white-background-color has-background" style="border-radius:8px;padding-top:0px;padding-right:0px;padding-bottom:0px;padding-left:0px"><!-- wp:image {"sizeSlug":"full","linkDestination":"none"} -->
<figure class="wp-block-image size-full"><img src="https://via.placeholder.com/400x400" alt="Team Member"/></figure>
<!-- /wp:image -->

<!-- wp:group {"style":{"spacing":{"padding":{"top":"1.5rem","right":"1.5rem","bottom":"1.5rem","left":"1.5rem"}}}} -->
<div class="wp-block-group" style="padding-top:1.5rem;padding-right:1.5rem;padding-bottom:1.5rem;padding-left:1.5rem"><!-- wp:heading {"textAlign":"center","level":3} -->
<h3 class="wp-block-heading has-text-align-center">John Doe</h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center","style":{"typography":{"fontSize":"16px"},"color":{"text":"#666666"}}} -->
<p class="has-text-align-center has-text-color" style="color:#666666;font-size:16px">Founder & CEO</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis.</p>
<!-- /wp:paragraph -->

<!-- wp:social-links {"layout":{"type":"flex","justifyContent":"center"}} -->
<ul class="wp-block-social-links"><!-- wp:social-link {"url":"#","service":"twitter"} /-->

<!-- wp:social-link {"url":"#","service":"linkedin"} /-->

<!-- wp:social-link {"url":"#","service":"instagram"} /--></ul>
<!-- /wp:social-links --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:group {"style":{"border":{"radius":"8px"},"spacing":{"padding":{"top":"0px","right":"0px","bottom":"0px","left":"0px"}}},"backgroundColor":"white","className":"is-style-aqualuxe-card"} -->
<div class="wp-block-group is-style-aqualuxe-card has-white-background-color has-background" style="border-radius:8px;padding-top:0px;padding-right:0px;padding-bottom:0px;padding-left:0px"><!-- wp:image {"sizeSlug":"full","linkDestination":"none"} -->
<figure class="wp-block-image size-full"><img src="https://via.placeholder.com/400x400" alt="Team Member"/></figure>
<!-- /wp:image -->

<!-- wp:group {"style":{"spacing":{"padding":{"top":"1.5rem","right":"1.5rem","bottom":"1.5rem","left":"1.5rem"}}}} -->
<div class="wp-block-group" style="padding-top:1.5rem;padding-right:1.5rem;padding-bottom:1.5rem;padding-left:1.5rem"><!-- wp:heading {"textAlign":"center","level":3} -->
<h3 class="wp-block-heading has-text-align-center">Jane Smith</h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center","style":{"typography":{"fontSize":"16px"},"color":{"text":"#666666"}}} -->
<p class="has-text-align-center has-text-color" style="color:#666666;font-size:16px">Creative Director</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis.</p>
<!-- /wp:paragraph -->

<!-- wp:social-links {"layout":{"type":"flex","justifyContent":"center"}} -->
<ul class="wp-block-social-links"><!-- wp:social-link {"url":"#","service":"twitter"} /-->

<!-- wp:social-link {"url":"#","service":"linkedin"} /-->

<!-- wp:social-link {"url":"#","service":"instagram"} /--></ul>
<!-- /wp:social-links --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:group {"style":{"border":{"radius":"8px"},"spacing":{"padding":{"top":"0px","right":"0px","bottom":"0px","left":"0px"}}},"backgroundColor":"white","className":"is-style-aqualuxe-card"} -->
<div class="wp-block-group is-style-aqualuxe-card has-white-background-color has-background" style="border-radius:8px;padding-top:0px;padding-right:0px;padding-bottom:0px;padding-left:0px"><!-- wp:image {"sizeSlug":"full","linkDestination":"none"} -->
<figure class="wp-block-image size-full"><img src="https://via.placeholder.com/400x400" alt="Team Member"/></figure>
<!-- /wp:image -->

<!-- wp:group {"style":{"spacing":{"padding":{"top":"1.5rem","right":"1.5rem","bottom":"1.5rem","left":"1.5rem"}}}} -->
<div class="wp-block-group" style="padding-top:1.5rem;padding-right:1.5rem;padding-bottom:1.5rem;padding-left:1.5rem"><!-- wp:heading {"textAlign":"center","level":3} -->
<h3 class="wp-block-heading has-text-align-center">Mike Johnson</h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center","style":{"typography":{"fontSize":"16px"},"color":{"text":"#666666"}}} -->
<p class="has-text-align-center has-text-color" style="color:#666666;font-size:16px">Lead Developer</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis.</p>
<!-- /wp:paragraph -->

<!-- wp:social-links {"layout":{"type":"flex","justifyContent":"center"}} -->
<ul class="wp-block-social-links"><!-- wp:social-link {"url":"#","service":"twitter"} /-->

<!-- wp:social-link {"url":"#","service":"linkedin"} /-->

<!-- wp:social-link {"url":"#","service":"instagram"} /--></ul>
<!-- /wp:social-links --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group -->',
            )
        );

        // Pricing Table
        register_block_pattern(
            'aqualuxe/pricing-table',
            array(
                'title'       => __( 'Pricing Table', 'aqualuxe' ),
                'description' => __( 'A pricing table with multiple tiers.', 'aqualuxe' ),
                'categories'  => array( 'aqualuxe', 'aqualuxe-pricing' ),
                'content'     => '<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"4rem","bottom":"4rem"}}},"backgroundColor":"background","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull has-background-background-color has-background" style="padding-top:4rem;padding-bottom:4rem"><!-- wp:heading {"textAlign":"center","style":{"spacing":{"margin":{"bottom":"1rem"}}}} -->
<h2 class="wp-block-heading has-text-align-center" style="margin-bottom:1rem">Simple, Transparent Pricing</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center","style":{"spacing":{"margin":{"bottom":"3rem"}}}} -->
<p class="has-text-align-center" style="margin-bottom:3rem">Choose the perfect plan for your needs</p>
<!-- /wp:paragraph -->

<!-- wp:columns {"align":"wide"} -->
<div class="wp-block-columns alignwide"><!-- wp:column -->
<div class="wp-block-column"><!-- wp:group {"style":{"border":{"radius":"8px"},"spacing":{"padding":{"top":"2rem","right":"2rem","bottom":"2rem","left":"2rem"}}},"backgroundColor":"white","className":"is-style-aqualuxe-card"} -->
<div class="wp-block-group is-style-aqualuxe-card has-white-background-color has-background" style="border-radius:8px;padding-top:2rem;padding-right:2rem;padding-bottom:2rem;padding-left:2rem"><!-- wp:heading {"textAlign":"center","level":3} -->
<h3 class="wp-block-heading has-text-align-center">Basic</h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center","style":{"typography":{"fontSize":"14px"}}} -->
<p class="has-text-align-center" style="font-size:14px">Perfect for beginners</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"textAlign":"center","level":2,"style":{"spacing":{"margin":{"top":"1rem","bottom":"1rem"}}}} -->
<h2 class="wp-block-heading has-text-align-center" style="margin-top:1rem;margin-bottom:1rem">$29</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center","style":{"typography":{"fontSize":"14px"}}} -->
<p class="has-text-align-center" style="font-size:14px">per month</p>
<!-- /wp:paragraph -->

<!-- wp:separator {"backgroundColor":"gray","className":"is-style-wide"} -->
<hr class="wp-block-separator has-text-color has-gray-color has-alpha-channel-opacity has-gray-background-color has-background is-style-wide"/>
<!-- /wp:separator -->

<!-- wp:list -->
<ul><!-- wp:list-item -->
<li>1 Website</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>5GB Storage</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>10,000 Visitors/month</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Basic Support</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><s>Custom Domain</s></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><s>Premium Themes</s></li>
<!-- /wp:list-item --></ul>
<!-- /wp:list -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons"><!-- wp:button {"width":100} -->
<div class="wp-block-button has-custom-width wp-block-button__width-100"><a class="wp-block-button__link wp-element-button">Get Started</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:group {"style":{"border":{"radius":"8px"},"spacing":{"padding":{"top":"2rem","right":"2rem","bottom":"2rem","left":"2rem"}}},"backgroundColor":"primary","textColor":"white","className":"is-style-aqualuxe-card"} -->
<div class="wp-block-group is-style-aqualuxe-card has-white-color has-primary-background-color has-text-color has-background" style="border-radius:8px;padding-top:2rem;padding-right:2rem;padding-bottom:2rem;padding-left:2rem"><!-- wp:heading {"textAlign":"center","level":3} -->
<h3 class="wp-block-heading has-text-align-center">Professional</h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center","style":{"typography":{"fontSize":"14px"}}} -->
<p class="has-text-align-center" style="font-size:14px">Most popular choice</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"textAlign":"center","level":2,"style":{"spacing":{"margin":{"top":"1rem","bottom":"1rem"}}}} -->
<h2 class="wp-block-heading has-text-align-center" style="margin-top:1rem;margin-bottom:1rem">$59</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center","style":{"typography":{"fontSize":"14px"}}} -->
<p class="has-text-align-center" style="font-size:14px">per month</p>
<!-- /wp:paragraph -->

<!-- wp:separator {"backgroundColor":"white","className":"is-style-wide"} -->
<hr class="wp-block-separator has-text-color has-white-color has-alpha-channel-opacity has-white-background-color has-background is-style-wide"/>
<!-- /wp:separator -->

<!-- wp:list -->
<ul><!-- wp:list-item -->
<li>5 Websites</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>20GB Storage</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>100,000 Visitors/month</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Priority Support</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Custom Domain</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Premium Themes</li>
<!-- /wp:list-item --></ul>
<!-- /wp:list -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons"><!-- wp:button {"backgroundColor":"white","textColor":"primary","width":100} -->
<div class="wp-block-button has-custom-width wp-block-button__width-100"><a class="wp-block-button__link has-primary-color has-white-background-color has-text-color has-background wp-element-button">Get Started</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:group {"style":{"border":{"radius":"8px"},"spacing":{"padding":{"top":"2rem","right":"2rem","bottom":"2rem","left":"2rem"}}},"backgroundColor":"white","className":"is-style-aqualuxe-card"} -->
<div class="wp-block-group is-style-aqualuxe-card has-white-background-color has-background" style="border-radius:8px;padding-top:2rem;padding-right:2rem;padding-bottom:2rem;padding-left:2rem"><!-- wp:heading {"textAlign":"center","level":3} -->
<h3 class="wp-block-heading has-text-align-center">Enterprise</h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center","style":{"typography":{"fontSize":"14px"}}} -->
<p class="has-text-align-center" style="font-size:14px">For large organizations</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"textAlign":"center","level":2,"style":{"spacing":{"margin":{"top":"1rem","bottom":"1rem"}}}} -->
<h2 class="wp-block-heading has-text-align-center" style="margin-top:1rem;margin-bottom:1rem">$99</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center","style":{"typography":{"fontSize":"14px"}}} -->
<p class="has-text-align-center" style="font-size:14px">per month</p>
<!-- /wp:paragraph -->

<!-- wp:separator {"backgroundColor":"gray","className":"is-style-wide"} -->
<hr class="wp-block-separator has-text-color has-gray-color has-alpha-channel-opacity has-gray-background-color has-background is-style-wide"/>
<!-- /wp:separator -->

<!-- wp:list -->
<ul><!-- wp:list-item -->
<li>Unlimited Websites</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>100GB Storage</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Unlimited Visitors</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>24/7 Premium Support</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Custom Domain</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Premium Themes</li>
<!-- /wp:list-item --></ul>
<!-- /wp:list -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons"><!-- wp:button {"width":100} -->
<div class="wp-block-button has-custom-width wp-block-button__width-100"><a class="wp-block-button__link wp-element-button">Get Started</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group -->',
            )
        );
    }
}
add_action( 'init', 'aqualuxe_register_block_patterns' );

/**
 * Register custom block styles.
 */
function aqualuxe_register_block_styles() {
    // Register block styles
    register_block_style(
        'core/group',
        array(
            'name'         => 'aqualuxe-card',
            'label'        => __( 'Card', 'aqualuxe' ),
            'inline_style' => '
                .is-style-aqualuxe-card {
                    border-radius: 0.5rem;
                    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
                    overflow: hidden;
                }
            ',
        )
    );

    register_block_style(
        'core/group',
        array(
            'name'         => 'aqualuxe-notice',
            'label'        => __( 'Notice', 'aqualuxe' ),
            'inline_style' => '
                .is-style-aqualuxe-notice {
                    background-color: #e0f2fe;
                    border-left: 4px solid #0284c7;
                    padding: 1rem 1.5rem;
                    border-radius: 0 0.375rem 0.375rem 0;
                }
                .is-style-aqualuxe-notice.has-background {
                    border-left-width: 4px !important;
                }
            ',
        )
    );

    register_block_style(
        'core/paragraph',
        array(
            'name'         => 'aqualuxe-highlight',
            'label'        => __( 'Highlight', 'aqualuxe' ),
            'inline_style' => '
                .is-style-aqualuxe-highlight {
                    background-color: #fef3c7;
                    padding: 0.2em 0.4em;
                    border-radius: 0.25em;
                }
            ',
        )
    );

    register_block_style(
        'core/image',
        array(
            'name'         => 'aqualuxe-rounded',
            'label'        => __( 'Rounded', 'aqualuxe' ),
            'inline_style' => '
                .is-style-aqualuxe-rounded img {
                    border-radius: 0.5rem;
                    overflow: hidden;
                }
            ',
        )
    );

    register_block_style(
        'core/image',
        array(
            'name'         => 'aqualuxe-shadow',
            'label'        => __( 'Shadow', 'aqualuxe' ),
            'inline_style' => '
                .is-style-aqualuxe-shadow img {
                    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
                }
            ',
        )
    );

    register_block_style(
        'core/button',
        array(
            'name'         => 'aqualuxe-gradient',
            'label'        => __( 'Gradient', 'aqualuxe' ),
            'inline_style' => '
                .is-style-aqualuxe-gradient .wp-block-button__link {
                    background: linear-gradient(135deg, #0284c7 0%, #7dd3fc 100%);
                    transition: all 0.3s ease;
                }
                .is-style-aqualuxe-gradient .wp-block-button__link:hover {
                    background: linear-gradient(135deg, #0369a1 0%, #38bdf8 100%);
                    transform: translateY(-2px);
                }
            ',
        )
    );

    register_block_style(
        'core/quote',
        array(
            'name'         => 'aqualuxe-modern',
            'label'        => __( 'Modern', 'aqualuxe' ),
            'inline_style' => '
                .is-style-aqualuxe-modern {
                    background-color: #f9fafb;
                    border-left: 4px solid #0284c7;
                    padding: 1.5rem;
                    border-radius: 0 0.5rem 0.5rem 0;
                    font-style: italic;
                }
                .is-style-aqualuxe-modern p {
                    position: relative;
                    padding-left: 2rem;
                }
                .is-style-aqualuxe-modern p::before {
                    content: """;
                    position: absolute;
                    left: 0;
                    top: -0.5rem;
                    font-size: 3rem;
                    color: #0284c7;
                    opacity: 0.3;
                    font-family: Georgia, serif;
                }
            ',
        )
    );

    register_block_style(
        'core/separator',
        array(
            'name'         => 'aqualuxe-gradient',
            'label'        => __( 'Gradient', 'aqualuxe' ),
            'inline_style' => '
                .is-style-aqualuxe-gradient {
                    height: 3px;
                    background: linear-gradient(90deg, #0284c7 0%, #7dd3fc 100%);
                    border: none;
                }
            ',
        )
    );

    register_block_style(
        'core/list',
        array(
            'name'         => 'aqualuxe-checked',
            'label'        => __( 'Checked', 'aqualuxe' ),
            'inline_style' => '
                .is-style-aqualuxe-checked {
                    list-style: none;
                    padding-left: 0;
                }
                .is-style-aqualuxe-checked li {
                    position: relative;
                    padding-left: 2rem;
                    margin-bottom: 0.5rem;
                }
                .is-style-aqualuxe-checked li::before {
                    content: "✓";
                    position: absolute;
                    left: 0;
                    top: 0;
                    color: #0284c7;
                    font-weight: bold;
                }
            ',
        )
    );
}
add_action( 'init', 'aqualuxe_register_block_styles' );