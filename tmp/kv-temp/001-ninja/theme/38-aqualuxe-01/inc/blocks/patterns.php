<?php
/**
 * Block Patterns
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register Block Patterns and Pattern Categories
 */
function aqualuxe_register_block_patterns() {
    // Register pattern categories
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
            'aqualuxe-footers',
            array( 'label' => __( 'AquaLuxe Footers', 'aqualuxe' ) )
        );
        
        register_block_pattern_category(
            'aqualuxe-hero',
            array( 'label' => __( 'AquaLuxe Hero Sections', 'aqualuxe' ) )
        );
        
        register_block_pattern_category(
            'aqualuxe-features',
            array( 'label' => __( 'AquaLuxe Features', 'aqualuxe' ) )
        );
        
        register_block_pattern_category(
            'aqualuxe-products',
            array( 'label' => __( 'AquaLuxe Products', 'aqualuxe' ) )
        );
        
        register_block_pattern_category(
            'aqualuxe-cta',
            array( 'label' => __( 'AquaLuxe Call to Action', 'aqualuxe' ) )
        );
        
        register_block_pattern_category(
            'aqualuxe-testimonials',
            array( 'label' => __( 'AquaLuxe Testimonials', 'aqualuxe' ) )
        );
    }
    
    // Register patterns
    if ( function_exists( 'register_block_pattern' ) ) {
        // Hero Section with Image and CTA
        register_block_pattern(
            'aqualuxe/hero-with-image-cta',
            array(
                'title'       => __( 'Hero with Image and CTA', 'aqualuxe' ),
                'description' => __( 'A hero section with an image, heading, text, and call to action buttons.', 'aqualuxe' ),
                'categories'  => array( 'aqualuxe', 'aqualuxe-hero' ),
                'content'     => '<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"6rem","bottom":"6rem"}}},"backgroundColor":"gray-100","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull has-gray-100-background-color has-background" style="padding-top:6rem;padding-bottom:6rem"><!-- wp:columns {"verticalAlignment":"center","align":"wide"} -->
<div class="wp-block-columns alignwide are-vertically-aligned-center"><!-- wp:column {"verticalAlignment":"center"} -->
<div class="wp-block-column is-vertically-aligned-center"><!-- wp:heading {"level":1,"style":{"typography":{"fontStyle":"normal","fontWeight":"700"}}} -->
<h1 class="wp-block-heading" style="font-style:normal;font-weight:700">Discover Premium Aquatic Luxury</h1>
<!-- /wp:heading -->

<!-- wp:paragraph {"fontSize":"lg"} -->
<p class="has-lg-font-size">Experience the perfect blend of elegance and functionality with our exclusive collection of premium water-inspired products.</p>
<!-- /wp:paragraph -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button">Shop Now</a></div>
<!-- /wp:button -->

<!-- wp:button {"className":"is-style-outline"} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button">Learn More</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"center"} -->
<div class="wp-block-column is-vertically-aligned-center"><!-- wp:image {"align":"center","sizeSlug":"large","linkDestination":"none","className":"is-style-rounded"} -->
<figure class="wp-block-image aligncenter size-large is-style-rounded"><img src="https://images.unsplash.com/photo-1534349762230-e0cadf78f5da?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80" alt="Luxury bathroom with modern design"/></figure>
<!-- /wp:image --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group -->',
            )
        );
        
        // Featured Products Grid
        register_block_pattern(
            'aqualuxe/featured-products-grid',
            array(
                'title'       => __( 'Featured Products Grid', 'aqualuxe' ),
                'description' => __( 'A grid layout showcasing featured products with images, titles, and prices.', 'aqualuxe' ),
                'categories'  => array( 'aqualuxe', 'aqualuxe-products' ),
                'content'     => '<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"4rem","bottom":"4rem"}}},"backgroundColor":"white","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull has-white-background-color has-background" style="padding-top:4rem;padding-bottom:4rem"><!-- wp:heading {"textAlign":"center","style":{"spacing":{"margin":{"bottom":"2rem"}}}} -->
<h2 class="wp-block-heading has-text-align-center" style="margin-bottom:2rem">Featured Products</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center","style":{"spacing":{"margin":{"bottom":"3rem"}}}} -->
<p class="has-text-align-center" style="margin-bottom:3rem">Discover our most popular premium water-inspired products</p>
<!-- /wp:paragraph -->

<!-- wp:columns {"align":"wide"} -->
<div class="wp-block-columns alignwide"><!-- wp:column -->
<div class="wp-block-column"><!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->
<figure class="wp-block-image size-large"><img src="https://images.unsplash.com/photo-1507920672708-3fca4f3855f1?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80" alt="Luxury bathroom faucet"/></figure>
<!-- /wp:image -->

<!-- wp:heading {"level":3,"style":{"spacing":{"margin":{"top":"1rem","bottom":"0.5rem"}}}} -->
<h3 class="wp-block-heading" style="margin-top:1rem;margin-bottom:0.5rem">Modern Waterfall Faucet</h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"style":{"typography":{"fontStyle":"normal","fontWeight":"600"}},"textColor":"primary"} -->
<p class="has-primary-color has-text-color" style="font-style:normal;font-weight:600">$129.99</p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"left"}} -->
<div class="wp-block-buttons"><!-- wp:button {"className":"is-style-fill"} -->
<div class="wp-block-button is-style-fill"><a class="wp-block-button__link wp-element-button">Add to Cart</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->
<figure class="wp-block-image size-large"><img src="https://images.unsplash.com/photo-1584622650111-993a426fbf0a?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80" alt="Luxury shower head"/></figure>
<!-- /wp:image -->

<!-- wp:heading {"level":3,"style":{"spacing":{"margin":{"top":"1rem","bottom":"0.5rem"}}}} -->
<h3 class="wp-block-heading" style="margin-top:1rem;margin-bottom:0.5rem">Rainfall Shower System</h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"style":{"typography":{"fontStyle":"normal","fontWeight":"600"}},"textColor":"primary"} -->
<p class="has-primary-color has-text-color" style="font-style:normal;font-weight:600">$249.99</p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"left"}} -->
<div class="wp-block-buttons"><!-- wp:button {"className":"is-style-fill"} -->
<div class="wp-block-button is-style-fill"><a class="wp-block-button__link wp-element-button">Add to Cart</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->
<figure class="wp-block-image size-large"><img src="https://images.unsplash.com/photo-1600566752355-35792bedcfea?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80" alt="Luxury bathtub"/></figure>
<!-- /wp:image -->

<!-- wp:heading {"level":3,"style":{"spacing":{"margin":{"top":"1rem","bottom":"0.5rem"}}}} -->
<h3 class="wp-block-heading" style="margin-top:1rem;margin-bottom:0.5rem">Freestanding Soaking Tub</h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"style":{"typography":{"fontStyle":"normal","fontWeight":"600"}},"textColor":"primary"} -->
<p class="has-primary-color has-text-color" style="font-style:normal;font-weight:600">$899.99</p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"left"}} -->
<div class="wp-block-buttons"><!-- wp:button {"className":"is-style-fill"} -->
<div class="wp-block-button is-style-fill"><a class="wp-block-button__link wp-element-button">Add to Cart</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"},"style":{"spacing":{"margin":{"top":"3rem"}}}} -->
<div class="wp-block-buttons" style="margin-top:3rem"><!-- wp:button {"className":"is-style-outline"} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button">View All Products</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group -->',
            )
        );
        
        // Testimonials Slider
        register_block_pattern(
            'aqualuxe/testimonials-slider',
            array(
                'title'       => __( 'Testimonials Slider', 'aqualuxe' ),
                'description' => __( 'A slider showcasing customer testimonials with quotes and ratings.', 'aqualuxe' ),
                'categories'  => array( 'aqualuxe', 'aqualuxe-testimonials' ),
                'content'     => '<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"5rem","bottom":"5rem"}},"color":{"gradient":"linear-gradient(135deg, #0e7490 0%, #0891b2 100%)"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull has-background" style="background:linear-gradient(135deg, #0e7490 0%, #0891b2 100%);padding-top:5rem;padding-bottom:5rem"><!-- wp:heading {"textAlign":"center","textColor":"white"} -->
<h2 class="wp-block-heading has-text-align-center has-white-color has-text-color">What Our Customers Say</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center","textColor":"white","style":{"spacing":{"margin":{"bottom":"3rem"}}}} -->
<p class="has-text-align-center has-white-color has-text-color" style="margin-bottom:3rem">Hear from our satisfied customers about their experience with AquaLuxe products</p>
<!-- /wp:paragraph -->

<!-- wp:columns {"align":"wide"} -->
<div class="wp-block-columns alignwide"><!-- wp:column -->
<div class="wp-block-column"><!-- wp:group {"style":{"spacing":{"padding":{"top":"2rem","right":"2rem","bottom":"2rem","left":"2rem"}},"border":{"radius":"0.5rem"}},"backgroundColor":"white","layout":{"type":"constrained"}} -->
<div class="wp-block-group has-white-background-color has-background" style="border-radius:0.5rem;padding-top:2rem;padding-right:2rem;padding-bottom:2rem;padding-left:2rem"><!-- wp:paragraph {"style":{"typography":{"fontStyle":"italic"}}} -->
<p style="font-style:italic">"The rainfall shower system transformed my bathroom into a spa-like retreat. The quality is exceptional, and the installation was straightforward. I couldn't be happier with my purchase!"</p>
<!-- /wp:paragraph -->

<!-- wp:group {"style":{"spacing":{"blockGap":"0.5rem"}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"center"}} -->
<div class="wp-block-group"><!-- wp:image {"width":"48","height":"48","scale":"cover","sizeSlug":"thumbnail","linkDestination":"none","className":"is-style-rounded"} -->
<figure class="wp-block-image size-thumbnail is-resized is-style-rounded"><img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=687&q=80" alt="Customer portrait" width="48" height="48" style="object-fit:cover"/></figure>
<!-- /wp:image -->

<!-- wp:group {"style":{"spacing":{"blockGap":"0"}},"layout":{"type":"flex","orientation":"vertical"}} -->
<div class="wp-block-group"><!-- wp:paragraph {"style":{"typography":{"fontWeight":"600"}}} -->
<p style="font-weight:600">Sarah Johnson</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"fontSize":"sm"} -->
<p class="has-sm-font-size">Verified Customer</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->

<!-- wp:image {"align":"right","width":"100px","sizeSlug":"full","linkDestination":"none"} -->
<figure class="wp-block-image alignright size-full is-resized"><img src="https://raw.githubusercontent.com/WordPress/gutenberg/trunk/packages/block-library/src/image/test/fixtures/5-stars.png" alt="5 star rating" width="100px"/></figure>
<!-- /wp:image --></div>
<!-- /wp:group --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:group {"style":{"spacing":{"padding":{"top":"2rem","right":"2rem","bottom":"2rem","left":"2rem"}},"border":{"radius":"0.5rem"}},"backgroundColor":"white","layout":{"type":"constrained"}} -->
<div class="wp-block-group has-white-background-color has-background" style="border-radius:0.5rem;padding-top:2rem;padding-right:2rem;padding-bottom:2rem;padding-left:2rem"><!-- wp:paragraph {"style":{"typography":{"fontStyle":"italic"}}} -->
<p style="font-style:italic">"I've purchased several faucets from AquaLuxe for my home renovation, and each one exceeded my expectations. The modern waterfall design is stunning, and the quality is top-notch."</p>
<!-- /wp:paragraph -->

<!-- wp:group {"style":{"spacing":{"blockGap":"0.5rem"}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"center"}} -->
<div class="wp-block-group"><!-- wp:image {"width":"48","height":"48","scale":"cover","sizeSlug":"thumbnail","linkDestination":"none","className":"is-style-rounded"} -->
<figure class="wp-block-image size-thumbnail is-resized is-style-rounded"><img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=687&q=80" alt="Customer portrait" width="48" height="48" style="object-fit:cover"/></figure>
<!-- /wp:image -->

<!-- wp:group {"style":{"spacing":{"blockGap":"0"}},"layout":{"type":"flex","orientation":"vertical"}} -->
<div class="wp-block-group"><!-- wp:paragraph {"style":{"typography":{"fontWeight":"600"}}} -->
<p style="font-weight:600">Michael Chen</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"fontSize":"sm"} -->
<p class="has-sm-font-size">Verified Customer</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->

<!-- wp:image {"align":"right","width":"100px","sizeSlug":"full","linkDestination":"none"} -->
<figure class="wp-block-image alignright size-full is-resized"><img src="https://raw.githubusercontent.com/WordPress/gutenberg/trunk/packages/block-library/src/image/test/fixtures/5-stars.png" alt="5 star rating" width="100px"/></figure>
<!-- /wp:image --></div>
<!-- /wp:group --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:group {"style":{"spacing":{"padding":{"top":"2rem","right":"2rem","bottom":"2rem","left":"2rem"}},"border":{"radius":"0.5rem"}},"backgroundColor":"white","layout":{"type":"constrained"}} -->
<div class="wp-block-group has-white-background-color has-background" style="border-radius:0.5rem;padding-top:2rem;padding-right:2rem;padding-bottom:2rem;padding-left:2rem"><!-- wp:paragraph {"style":{"typography":{"fontStyle":"italic"}}} -->
<p style="font-style:italic">"The freestanding soaking tub is the centerpiece of our master bathroom. It's not only beautiful but incredibly comfortable. AquaLuxe's customer service was exceptional throughout the entire process."</p>
<!-- /wp:paragraph -->

<!-- wp:group {"style":{"spacing":{"blockGap":"0.5rem"}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"center"}} -->
<div class="wp-block-group"><!-- wp:image {"width":"48","height":"48","scale":"cover","sizeSlug":"thumbnail","linkDestination":"none","className":"is-style-rounded"} -->
<figure class="wp-block-image size-thumbnail is-resized is-style-rounded"><img src="https://images.unsplash.com/photo-1580489944761-15a19d654956?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=761&q=80" alt="Customer portrait" width="48" height="48" style="object-fit:cover"/></figure>
<!-- /wp:image -->

<!-- wp:group {"style":{"spacing":{"blockGap":"0"}},"layout":{"type":"flex","orientation":"vertical"}} -->
<div class="wp-block-group"><!-- wp:paragraph {"style":{"typography":{"fontWeight":"600"}}} -->
<p style="font-weight:600">Emily Rodriguez</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"fontSize":"sm"} -->
<p class="has-sm-font-size">Verified Customer</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->

<!-- wp:image {"align":"right","width":"100px","sizeSlug":"full","linkDestination":"none"} -->
<figure class="wp-block-image alignright size-full is-resized"><img src="https://raw.githubusercontent.com/WordPress/gutenberg/trunk/packages/block-library/src/image/test/fixtures/5-stars.png" alt="5 star rating" width="100px"/></figure>
<!-- /wp:image --></div>
<!-- /wp:group --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"},"style":{"spacing":{"margin":{"top":"3rem"}}}} -->
<div class="wp-block-buttons" style="margin-top:3rem"><!-- wp:button {"backgroundColor":"white","textColor":"primary"} -->
<div class="wp-block-button"><a class="wp-block-button__link has-primary-color has-white-background-color has-text-color has-background wp-element-button">Read More Reviews</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group -->',
            )
        );
        
        // Call to Action with Image
        register_block_pattern(
            'aqualuxe/cta-with-image',
            array(
                'title'       => __( 'Call to Action with Image', 'aqualuxe' ),
                'description' => __( 'A call to action section with an image, heading, text, and button.', 'aqualuxe' ),
                'categories'  => array( 'aqualuxe', 'aqualuxe-cta' ),
                'content'     => '<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"5rem","bottom":"5rem"}}},"backgroundColor":"gray-100","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull has-gray-100-background-color has-background" style="padding-top:5rem;padding-bottom:5rem"><!-- wp:columns {"verticalAlignment":"center","align":"wide"} -->
<div class="wp-block-columns alignwide are-vertically-aligned-center"><!-- wp:column {"verticalAlignment":"center"} -->
<div class="wp-block-column is-vertically-aligned-center"><!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->
<figure class="wp-block-image size-large"><img src="https://images.unsplash.com/photo-1604709177225-055f99402ea3?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80" alt="Luxury bathroom design"/></figure>
<!-- /wp:image --></div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"center","style":{"spacing":{"padding":{"top":"2rem","right":"2rem","bottom":"2rem","left":"2rem"}}}} -->
<div class="wp-block-column is-vertically-aligned-center" style="padding-top:2rem;padding-right:2rem;padding-bottom:2rem;padding-left:2rem"><!-- wp:heading -->
<h2 class="wp-block-heading">Transform Your Bathroom Into a Sanctuary</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Elevate your daily routine with our premium bathroom fixtures and accessories. From luxurious faucets to spa-like shower systems, we have everything you need to create your dream bathroom.</p>
<!-- /wp:paragraph -->

<!-- wp:list {"className":"is-style-check-list"} -->
<ul class="is-style-check-list"><!-- wp:list-item -->
<li>Premium quality materials</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Modern, timeless designs</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Expert installation support</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>5-year warranty on all products</li>
<!-- /wp:list-item --></ul>
<!-- /wp:list -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button">Schedule a Consultation</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
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
                'description' => __( 'A grid layout showcasing product features with icons, headings, and text.', 'aqualuxe' ),
                'categories'  => array( 'aqualuxe', 'aqualuxe-features' ),
                'content'     => '<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"5rem","bottom":"5rem"}}},"backgroundColor":"white","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull has-white-background-color has-background" style="padding-top:5rem;padding-bottom:5rem"><!-- wp:heading {"textAlign":"center"} -->
<h2 class="wp-block-heading has-text-align-center">Why Choose AquaLuxe</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center","style":{"spacing":{"margin":{"bottom":"3rem"}}}} -->
<p class="has-text-align-center" style="margin-bottom:3rem">Discover the features that set our products apart from the competition</p>
<!-- /wp:paragraph -->

<!-- wp:columns {"align":"wide"} -->
<div class="wp-block-columns alignwide"><!-- wp:column -->
<div class="wp-block-column"><!-- wp:group {"style":{"spacing":{"padding":{"top":"2rem","right":"2rem","bottom":"2rem","left":"2rem"}},"border":{"radius":"0.5rem"}},"backgroundColor":"gray-50","layout":{"type":"constrained"}} -->
<div class="wp-block-group has-gray-50-background-color has-background" style="border-radius:0.5rem;padding-top:2rem;padding-right:2rem;padding-bottom:2rem;padding-left:2rem"><!-- wp:image {"align":"center","width":"64px","height":"64px","scale":"cover","sizeSlug":"full","linkDestination":"none"} -->
<figure class="wp-block-image aligncenter size-full is-resized"><img src="https://cdn-icons-png.flaticon.com/512/2784/2784589.png" alt="Premium Quality Icon" style="object-fit:cover;width:64px;height:64px"/></figure>
<!-- /wp:image -->

<!-- wp:heading {"textAlign":"center","level":3,"style":{"spacing":{"margin":{"top":"1rem"}}}} -->
<h3 class="wp-block-heading has-text-align-center" style="margin-top:1rem">Premium Quality</h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">Crafted from the highest quality materials to ensure durability and longevity. Each product undergoes rigorous testing to meet our strict standards.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:group {"style":{"spacing":{"padding":{"top":"2rem","right":"2rem","bottom":"2rem","left":"2rem"}},"border":{"radius":"0.5rem"}},"backgroundColor":"gray-50","layout":{"type":"constrained"}} -->
<div class="wp-block-group has-gray-50-background-color has-background" style="border-radius:0.5rem;padding-top:2rem;padding-right:2rem;padding-bottom:2rem;padding-left:2rem"><!-- wp:image {"align":"center","width":"64px","height":"64px","scale":"cover","sizeSlug":"full","linkDestination":"none"} -->
<figure class="wp-block-image aligncenter size-full is-resized"><img src="https://cdn-icons-png.flaticon.com/512/1186/1186715.png" alt="Elegant Design Icon" style="object-fit:cover;width:64px;height:64px"/></figure>
<!-- /wp:image -->

<!-- wp:heading {"textAlign":"center","level":3,"style":{"spacing":{"margin":{"top":"1rem"}}}} -->
<h3 class="wp-block-heading has-text-align-center" style="margin-top:1rem">Elegant Design</h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">Timeless aesthetics combined with modern functionality. Our designs complement any bathroom style while making a statement of luxury and sophistication.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:group {"style":{"spacing":{"padding":{"top":"2rem","right":"2rem","bottom":"2rem","left":"2rem"}},"border":{"radius":"0.5rem"}},"backgroundColor":"gray-50","layout":{"type":"constrained"}} -->
<div class="wp-block-group has-gray-50-background-color has-background" style="border-radius:0.5rem;padding-top:2rem;padding-right:2rem;padding-bottom:2rem;padding-left:2rem"><!-- wp:image {"align":"center","width":"64px","height":"64px","scale":"cover","sizeSlug":"full","linkDestination":"none"} -->
<figure class="wp-block-image aligncenter size-full is-resized"><img src="https://cdn-icons-png.flaticon.com/512/2553/2553652.png" alt="Water Efficiency Icon" style="object-fit:cover;width:64px;height:64px"/></figure>
<!-- /wp:image -->

<!-- wp:heading {"textAlign":"center","level":3,"style":{"spacing":{"margin":{"top":"1rem"}}}} -->
<h3 class="wp-block-heading has-text-align-center" style="margin-top:1rem">Water Efficiency</h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">Innovative technology that conserves water without compromising performance. Our products help reduce water consumption while maintaining an exceptional experience.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->

<!-- wp:columns {"align":"wide","style":{"spacing":{"margin":{"top":"2rem"}}}} -->
<div class="wp-block-columns alignwide" style="margin-top:2rem"><!-- wp:column -->
<div class="wp-block-column"><!-- wp:group {"style":{"spacing":{"padding":{"top":"2rem","right":"2rem","bottom":"2rem","left":"2rem"}},"border":{"radius":"0.5rem"}},"backgroundColor":"gray-50","layout":{"type":"constrained"}} -->
<div class="wp-block-group has-gray-50-background-color has-background" style="border-radius:0.5rem;padding-top:2rem;padding-right:2rem;padding-bottom:2rem;padding-left:2rem"><!-- wp:image {"align":"center","width":"64px","height":"64px","scale":"cover","sizeSlug":"full","linkDestination":"none"} -->
<figure class="wp-block-image aligncenter size-full is-resized"><img src="https://cdn-icons-png.flaticon.com/512/2421/2421232.png" alt="Easy Installation Icon" style="object-fit:cover;width:64px;height:64px"/></figure>
<!-- /wp:image -->

<!-- wp:heading {"textAlign":"center","level":3,"style":{"spacing":{"margin":{"top":"1rem"}}}} -->
<h3 class="wp-block-heading has-text-align-center" style="margin-top:1rem">Easy Installation</h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">Designed with simplicity in mind. Our products come with detailed instructions and all necessary hardware, making installation straightforward for professionals and DIY enthusiasts alike.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:group {"style":{"spacing":{"padding":{"top":"2rem","right":"2rem","bottom":"2rem","left":"2rem"}},"border":{"radius":"0.5rem"}},"backgroundColor":"gray-50","layout":{"type":"constrained"}} -->
<div class="wp-block-group has-gray-50-background-color has-background" style="border-radius:0.5rem;padding-top:2rem;padding-right:2rem;padding-bottom:2rem;padding-left:2rem"><!-- wp:image {"align":"center","width":"64px","height":"64px","scale":"cover","sizeSlug":"full","linkDestination":"none"} -->
<figure class="wp-block-image aligncenter size-full is-resized"><img src="https://cdn-icons-png.flaticon.com/512/1067/1067357.png" alt="Warranty Icon" style="object-fit:cover;width:64px;height:64px"/></figure>
<!-- /wp:image -->

<!-- wp:heading {"textAlign":"center","level":3,"style":{"spacing":{"margin":{"top":"1rem"}}}} -->
<h3 class="wp-block-heading has-text-align-center" style="margin-top:1rem">5-Year Warranty</h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">We stand behind our products with confidence. Every AquaLuxe product comes with a comprehensive 5-year warranty, giving you peace of mind with your investment.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:group {"style":{"spacing":{"padding":{"top":"2rem","right":"2rem","bottom":"2rem","left":"2rem"}},"border":{"radius":"0.5rem"}},"backgroundColor":"gray-50","layout":{"type":"constrained"}} -->
<div class="wp-block-group has-gray-50-background-color has-background" style="border-radius:0.5rem;padding-top:2rem;padding-right:2rem;padding-bottom:2rem;padding-left:2rem"><!-- wp:image {"align":"center","width":"64px","height":"64px","scale":"cover","sizeSlug":"full","linkDestination":"none"} -->
<figure class="wp-block-image aligncenter size-full is-resized"><img src="https://cdn-icons-png.flaticon.com/512/1067/1067555.png" alt="Customer Support Icon" style="object-fit:cover;width:64px;height:64px"/></figure>
<!-- /wp:image -->

<!-- wp:heading {"textAlign":"center","level":3,"style":{"spacing":{"margin":{"top":"1rem"}}}} -->
<h3 class="wp-block-heading has-text-align-center" style="margin-top:1rem">Expert Support</h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">Our dedicated customer service team is always ready to assist you. From product selection to installation guidance and troubleshooting, we're here to help every step of the way.</p>
<!-- /wp:paragraph --></div>
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
 * Register Custom Block Styles
 */
function aqualuxe_register_block_styles() {
    // Register block styles
    register_block_style(
        'core/button',
        array(
            'name'         => 'fill-primary',
            'label'        => __( 'Fill Primary', 'aqualuxe' ),
            'inline_style' => '.wp-block-button.is-style-fill-primary .wp-block-button__link { background-color: var(--wp--preset--color--primary); color: var(--wp--preset--color--white); }',
        )
    );
    
    register_block_style(
        'core/button',
        array(
            'name'         => 'fill-secondary',
            'label'        => __( 'Fill Secondary', 'aqualuxe' ),
            'inline_style' => '.wp-block-button.is-style-fill-secondary .wp-block-button__link { background-color: var(--wp--preset--color--secondary); color: var(--wp--preset--color--white); }',
        )
    );
    
    register_block_style(
        'core/button',
        array(
            'name'         => 'outline-primary',
            'label'        => __( 'Outline Primary', 'aqualuxe' ),
            'inline_style' => '.wp-block-button.is-style-outline-primary .wp-block-button__link { background-color: transparent; color: var(--wp--preset--color--primary); border: 2px solid var(--wp--preset--color--primary); }',
        )
    );
    
    register_block_style(
        'core/button',
        array(
            'name'         => 'outline-secondary',
            'label'        => __( 'Outline Secondary', 'aqualuxe' ),
            'inline_style' => '.wp-block-button.is-style-outline-secondary .wp-block-button__link { background-color: transparent; color: var(--wp--preset--color--secondary); border: 2px solid var(--wp--preset--color--secondary); }',
        )
    );
    
    register_block_style(
        'core/list',
        array(
            'name'         => 'check-list',
            'label'        => __( 'Check List', 'aqualuxe' ),
            'inline_style' => '.wp-block-list.is-style-check-list { list-style: none; padding-left: 0; } .wp-block-list.is-style-check-list li { position: relative; padding-left: 2rem; margin-bottom: 0.5rem; } .wp-block-list.is-style-check-list li:before { content: "✓"; position: absolute; left: 0; color: var(--wp--preset--color--primary); font-weight: bold; }',
        )
    );
    
    register_block_style(
        'core/image',
        array(
            'name'         => 'rounded',
            'label'        => __( 'Rounded', 'aqualuxe' ),
            'inline_style' => '.wp-block-image.is-style-rounded img { border-radius: 0.5rem; }',
        )
    );
    
    register_block_style(
        'core/image',
        array(
            'name'         => 'shadow',
            'label'        => __( 'Shadow', 'aqualuxe' ),
            'inline_style' => '.wp-block-image.is-style-shadow img { box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); }',
        )
    );
    
    register_block_style(
        'core/group',
        array(
            'name'         => 'card',
            'label'        => __( 'Card', 'aqualuxe' ),
            'inline_style' => '.wp-block-group.is-style-card { background-color: var(--wp--preset--color--white); border-radius: 0.5rem; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); padding: 2rem; }',
        )
    );
    
    register_block_style(
        'core/group',
        array(
            'name'         => 'border',
            'label'        => __( 'Border', 'aqualuxe' ),
            'inline_style' => '.wp-block-group.is-style-border { border: 1px solid var(--wp--preset--color--gray-200); border-radius: 0.5rem; padding: 2rem; }',
        )
    );
    
    register_block_style(
        'core/columns',
        array(
            'name'         => 'no-gap',
            'label'        => __( 'No Gap', 'aqualuxe' ),
            'inline_style' => '.wp-block-columns.is-style-no-gap { gap: 0; }',
        )
    );
    
    register_block_style(
        'core/columns',
        array(
            'name'         => 'large-gap',
            'label'        => __( 'Large Gap', 'aqualuxe' ),
            'inline_style' => '.wp-block-columns.is-style-large-gap { gap: 4rem; }',
        )
    );
}
add_action( 'init', 'aqualuxe_register_block_styles' );