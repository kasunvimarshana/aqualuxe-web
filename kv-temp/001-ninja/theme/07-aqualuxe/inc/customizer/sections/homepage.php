<?php
/**
 * Homepage Customizer Settings
 *
 * @package AquaLuxe
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Add homepage settings to the Customizer
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_register_homepage($wp_customize) {
    // Add Homepage section
    $wp_customize->add_section('aqualuxe_homepage', array(
        'title' => esc_html__('Homepage Settings', 'aqualuxe'),
        'priority' => 50,
    ));

    // Hero Section
    $wp_customize->add_setting('aqualuxe_homepage_hero_heading', array(
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Heading($wp_customize, 'aqualuxe_homepage_hero_heading', array(
        'label' => esc_html__('Hero Section', 'aqualuxe'),
        'section' => 'aqualuxe_homepage',
        'priority' => 10,
    )));

    // Enable Hero Section
    $wp_customize->add_setting('aqualuxe_enable_hero', array(
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Toggle($wp_customize, 'aqualuxe_enable_hero', array(
        'label' => esc_html__('Enable Hero Section', 'aqualuxe'),
        'description' => esc_html__('Show the hero section on the homepage.', 'aqualuxe'),
        'section' => 'aqualuxe_homepage',
        'priority' => 20,
    )));

    // Hero Title
    $wp_customize->add_setting('aqualuxe_hero_title', array(
        'default' => esc_html__('Welcome to AquaLuxe', 'aqualuxe'),
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('aqualuxe_hero_title', array(
        'label' => esc_html__('Hero Title', 'aqualuxe'),
        'section' => 'aqualuxe_homepage',
        'type' => 'text',
        'priority' => 30,
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_enable_hero', true);
        },
    ));

    // Hero Subtitle
    $wp_customize->add_setting('aqualuxe_hero_subtitle', array(
        'default' => esc_html__('Premium Ornamental Fish for Collectors and Enthusiasts', 'aqualuxe'),
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('aqualuxe_hero_subtitle', array(
        'label' => esc_html__('Hero Subtitle', 'aqualuxe'),
        'section' => 'aqualuxe_homepage',
        'type' => 'text',
        'priority' => 40,
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_enable_hero', true);
        },
    ));

    // Hero Text
    $wp_customize->add_setting('aqualuxe_hero_text', array(
        'default' => esc_html__('Discover our exclusive collection of rare and exotic ornamental fish, sourced from sustainable farms around the world.', 'aqualuxe'),
        'sanitize_callback' => 'sanitize_textarea_field',
    ));

    $wp_customize->add_control('aqualuxe_hero_text', array(
        'label' => esc_html__('Hero Text', 'aqualuxe'),
        'section' => 'aqualuxe_homepage',
        'type' => 'textarea',
        'priority' => 50,
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_enable_hero', true);
        },
    ));

    // Hero Button Text
    $wp_customize->add_setting('aqualuxe_hero_button_text', array(
        'default' => esc_html__('Shop Now', 'aqualuxe'),
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('aqualuxe_hero_button_text', array(
        'label' => esc_html__('Primary Button Text', 'aqualuxe'),
        'section' => 'aqualuxe_homepage',
        'type' => 'text',
        'priority' => 60,
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_enable_hero', true);
        },
    ));

    // Hero Button URL
    $wp_customize->add_setting('aqualuxe_hero_button_url', array(
        'default' => '#',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control('aqualuxe_hero_button_url', array(
        'label' => esc_html__('Primary Button URL', 'aqualuxe'),
        'section' => 'aqualuxe_homepage',
        'type' => 'url',
        'priority' => 70,
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_enable_hero', true);
        },
    ));

    // Hero Button 2 Text
    $wp_customize->add_setting('aqualuxe_hero_button_2_text', array(
        'default' => esc_html__('Learn More', 'aqualuxe'),
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('aqualuxe_hero_button_2_text', array(
        'label' => esc_html__('Secondary Button Text', 'aqualuxe'),
        'section' => 'aqualuxe_homepage',
        'type' => 'text',
        'priority' => 80,
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_enable_hero', true);
        },
    ));

    // Hero Button 2 URL
    $wp_customize->add_setting('aqualuxe_hero_button_2_url', array(
        'default' => '#',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control('aqualuxe_hero_button_2_url', array(
        'label' => esc_html__('Secondary Button URL', 'aqualuxe'),
        'section' => 'aqualuxe_homepage',
        'type' => 'url',
        'priority' => 90,
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_enable_hero', true);
        },
    ));

    // Hero Image
    $wp_customize->add_setting('aqualuxe_hero_image', array(
        'default' => '',
        'sanitize_callback' => 'absint',
    ));

    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'aqualuxe_hero_image', array(
        'label' => esc_html__('Hero Background Image', 'aqualuxe'),
        'description' => esc_html__('Upload an image for the hero section background.', 'aqualuxe'),
        'section' => 'aqualuxe_homepage',
        'mime_type' => 'image',
        'priority' => 100,
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_enable_hero', true);
        },
    )));

    // Hero Height
    $wp_customize->add_setting('aqualuxe_hero_height', array(
        'default' => 600,
        'transport' => 'postMessage',
        'sanitize_callback' => 'absint',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Slider($wp_customize, 'aqualuxe_hero_height', array(
        'label' => esc_html__('Hero Height', 'aqualuxe'),
        'description' => esc_html__('Set the height of the hero section in pixels.', 'aqualuxe'),
        'section' => 'aqualuxe_homepage',
        'priority' => 110,
        'min' => 300,
        'max' => 1000,
        'step' => 10,
        'unit' => 'px',
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_enable_hero', true);
        },
    )));

    // Hero Overlay Opacity
    $wp_customize->add_setting('aqualuxe_hero_overlay_opacity', array(
        'default' => 0.5,
        'transport' => 'postMessage',
        'sanitize_callback' => 'aqualuxe_sanitize_float',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Slider($wp_customize, 'aqualuxe_hero_overlay_opacity', array(
        'label' => esc_html__('Hero Overlay Opacity', 'aqualuxe'),
        'description' => esc_html__('Set the opacity of the dark overlay on the hero image.', 'aqualuxe'),
        'section' => 'aqualuxe_homepage',
        'priority' => 120,
        'min' => 0,
        'max' => 1,
        'step' => 0.1,
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_enable_hero', true);
        },
    )));

    // Featured Products Section
    $wp_customize->add_setting('aqualuxe_homepage_featured_products_heading', array(
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Heading($wp_customize, 'aqualuxe_homepage_featured_products_heading', array(
        'label' => esc_html__('Featured Products Section', 'aqualuxe'),
        'section' => 'aqualuxe_homepage',
        'priority' => 130,
    )));

    // Enable Featured Products Section
    $wp_customize->add_setting('aqualuxe_enable_featured_products', array(
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Toggle($wp_customize, 'aqualuxe_enable_featured_products', array(
        'label' => esc_html__('Enable Featured Products Section', 'aqualuxe'),
        'description' => esc_html__('Show the featured products section on the homepage.', 'aqualuxe'),
        'section' => 'aqualuxe_homepage',
        'priority' => 140,
    )));

    // Featured Products Title
    $wp_customize->add_setting('aqualuxe_featured_products_title', array(
        'default' => esc_html__('Featured Products', 'aqualuxe'),
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('aqualuxe_featured_products_title', array(
        'label' => esc_html__('Section Title', 'aqualuxe'),
        'section' => 'aqualuxe_homepage',
        'type' => 'text',
        'priority' => 150,
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_enable_featured_products', true);
        },
    ));

    // Featured Products Subtitle
    $wp_customize->add_setting('aqualuxe_featured_products_subtitle', array(
        'default' => esc_html__('Our most exclusive and sought-after specimens', 'aqualuxe'),
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('aqualuxe_featured_products_subtitle', array(
        'label' => esc_html__('Section Subtitle', 'aqualuxe'),
        'section' => 'aqualuxe_homepage',
        'type' => 'text',
        'priority' => 160,
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_enable_featured_products', true);
        },
    ));

    // Featured Products Count
    $wp_customize->add_setting('aqualuxe_featured_products_count', array(
        'default' => 4,
        'sanitize_callback' => 'absint',
    ));

    $wp_customize->add_control('aqualuxe_featured_products_count', array(
        'label' => esc_html__('Number of Products', 'aqualuxe'),
        'description' => esc_html__('Select how many featured products to display.', 'aqualuxe'),
        'section' => 'aqualuxe_homepage',
        'type' => 'number',
        'input_attrs' => array(
            'min' => 1,
            'max' => 12,
            'step' => 1,
        ),
        'priority' => 170,
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_enable_featured_products', true);
        },
    ));

    // Testimonials Section
    $wp_customize->add_setting('aqualuxe_homepage_testimonials_heading', array(
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Heading($wp_customize, 'aqualuxe_homepage_testimonials_heading', array(
        'label' => esc_html__('Testimonials Section', 'aqualuxe'),
        'section' => 'aqualuxe_homepage',
        'priority' => 180,
    )));

    // Enable Testimonials Section
    $wp_customize->add_setting('aqualuxe_enable_testimonials', array(
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Toggle($wp_customize, 'aqualuxe_enable_testimonials', array(
        'label' => esc_html__('Enable Testimonials Section', 'aqualuxe'),
        'description' => esc_html__('Show the testimonials section on the homepage.', 'aqualuxe'),
        'section' => 'aqualuxe_homepage',
        'priority' => 190,
    )));

    // Testimonials Title
    $wp_customize->add_setting('aqualuxe_testimonials_title', array(
        'default' => esc_html__('What Our Customers Say', 'aqualuxe'),
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('aqualuxe_testimonials_title', array(
        'label' => esc_html__('Section Title', 'aqualuxe'),
        'section' => 'aqualuxe_homepage',
        'type' => 'text',
        'priority' => 200,
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_enable_testimonials', true);
        },
    ));

    // Testimonials Subtitle
    $wp_customize->add_setting('aqualuxe_testimonials_subtitle', array(
        'default' => esc_html__('Hear from our satisfied collectors and enthusiasts', 'aqualuxe'),
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('aqualuxe_testimonials_subtitle', array(
        'label' => esc_html__('Section Subtitle', 'aqualuxe'),
        'section' => 'aqualuxe_homepage',
        'type' => 'text',
        'priority' => 210,
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_enable_testimonials', true);
        },
    ));

    // Testimonial 1
    $wp_customize->add_setting('aqualuxe_testimonial_1_heading', array(
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Separator($wp_customize, 'aqualuxe_testimonial_1_heading', array(
        'label' => esc_html__('Testimonial 1', 'aqualuxe'),
        'section' => 'aqualuxe_homepage',
        'priority' => 220,
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_enable_testimonials', true);
        },
    )));

    // Testimonial 1 Name
    $wp_customize->add_setting('aqualuxe_testimonial_1_name', array(
        'default' => 'John Smith',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('aqualuxe_testimonial_1_name', array(
        'label' => esc_html__('Name', 'aqualuxe'),
        'section' => 'aqualuxe_homepage',
        'type' => 'text',
        'priority' => 230,
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_enable_testimonials', true);
        },
    ));

    // Testimonial 1 Role
    $wp_customize->add_setting('aqualuxe_testimonial_1_role', array(
        'default' => 'Aquarium Enthusiast',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('aqualuxe_testimonial_1_role', array(
        'label' => esc_html__('Role', 'aqualuxe'),
        'section' => 'aqualuxe_homepage',
        'type' => 'text',
        'priority' => 240,
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_enable_testimonials', true);
        },
    ));

    // Testimonial 1 Content
    $wp_customize->add_setting('aqualuxe_testimonial_1_content', array(
        'default' => 'The rare Asian Arowana I purchased from AquaLuxe is the centerpiece of my collection. The fish arrived in perfect health, and the customer service was exceptional.',
        'sanitize_callback' => 'sanitize_textarea_field',
    ));

    $wp_customize->add_control('aqualuxe_testimonial_1_content', array(
        'label' => esc_html__('Content', 'aqualuxe'),
        'section' => 'aqualuxe_homepage',
        'type' => 'textarea',
        'priority' => 250,
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_enable_testimonials', true);
        },
    ));

    // Testimonial 1 Image
    $wp_customize->add_setting('aqualuxe_testimonial_1_image', array(
        'default' => '',
        'sanitize_callback' => 'absint',
    ));

    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'aqualuxe_testimonial_1_image', array(
        'label' => esc_html__('Image', 'aqualuxe'),
        'section' => 'aqualuxe_homepage',
        'mime_type' => 'image',
        'priority' => 260,
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_enable_testimonials', true);
        },
    )));

    // Testimonial 2
    $wp_customize->add_setting('aqualuxe_testimonial_2_heading', array(
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Separator($wp_customize, 'aqualuxe_testimonial_2_heading', array(
        'label' => esc_html__('Testimonial 2', 'aqualuxe'),
        'section' => 'aqualuxe_homepage',
        'priority' => 270,
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_enable_testimonials', true);
        },
    )));

    // Testimonial 2 Name
    $wp_customize->add_setting('aqualuxe_testimonial_2_name', array(
        'default' => 'Emily Johnson',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('aqualuxe_testimonial_2_name', array(
        'label' => esc_html__('Name', 'aqualuxe'),
        'section' => 'aqualuxe_homepage',
        'type' => 'text',
        'priority' => 280,
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_enable_testimonials', true);
        },
    ));

    // Testimonial 2 Role
    $wp_customize->add_setting('aqualuxe_testimonial_2_role', array(
        'default' => 'Professional Breeder',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('aqualuxe_testimonial_2_role', array(
        'label' => esc_html__('Role', 'aqualuxe'),
        'section' => 'aqualuxe_homepage',
        'type' => 'text',
        'priority' => 290,
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_enable_testimonials', true);
        },
    ));

    // Testimonial 2 Content
    $wp_customize->add_setting('aqualuxe_testimonial_2_content', array(
        'default' => 'As a professional breeder, I demand the highest quality specimens. AquaLuxe consistently delivers healthy, genetically superior fish that thrive in my breeding program.',
        'sanitize_callback' => 'sanitize_textarea_field',
    ));

    $wp_customize->add_control('aqualuxe_testimonial_2_content', array(
        'label' => esc_html__('Content', 'aqualuxe'),
        'section' => 'aqualuxe_homepage',
        'type' => 'textarea',
        'priority' => 300,
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_enable_testimonials', true);
        },
    ));

    // Testimonial 2 Image
    $wp_customize->add_setting('aqualuxe_testimonial_2_image', array(
        'default' => '',
        'sanitize_callback' => 'absint',
    ));

    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'aqualuxe_testimonial_2_image', array(
        'label' => esc_html__('Image', 'aqualuxe'),
        'section' => 'aqualuxe_homepage',
        'mime_type' => 'image',
        'priority' => 310,
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_enable_testimonials', true);
        },
    )));

    // Testimonial 3
    $wp_customize->add_setting('aqualuxe_testimonial_3_heading', array(
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Separator($wp_customize, 'aqualuxe_testimonial_3_heading', array(
        'label' => esc_html__('Testimonial 3', 'aqualuxe'),
        'section' => 'aqualuxe_homepage',
        'priority' => 320,
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_enable_testimonials', true);
        },
    )));

    // Testimonial 3 Name
    $wp_customize->add_setting('aqualuxe_testimonial_3_name', array(
        'default' => 'Michael Chen',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('aqualuxe_testimonial_3_name', array(
        'label' => esc_html__('Name', 'aqualuxe'),
        'section' => 'aqualuxe_homepage',
        'type' => 'text',
        'priority' => 330,
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_enable_testimonials', true);
        },
    ));

    // Testimonial 3 Role
    $wp_customize->add_setting('aqualuxe_testimonial_3_role', array(
        'default' => 'Collector',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('aqualuxe_testimonial_3_role', array(
        'label' => esc_html__('Role', 'aqualuxe'),
        'section' => 'aqualuxe_homepage',
        'type' => 'text',
        'priority' => 340,
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_enable_testimonials', true);
        },
    ));

    // Testimonial 3 Content
    $wp_customize->add_setting('aqualuxe_testimonial_3_content', array(
        'default' => 'I\'ve been collecting exotic fish for over 20 years, and AquaLuxe offers the most impressive selection I\'ve seen. Their rare Japanese Koi varieties are simply stunning.',
        'sanitize_callback' => 'sanitize_textarea_field',
    ));

    $wp_customize->add_control('aqualuxe_testimonial_3_content', array(
        'label' => esc_html__('Content', 'aqualuxe'),
        'section' => 'aqualuxe_homepage',
        'type' => 'textarea',
        'priority' => 350,
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_enable_testimonials', true);
        },
    ));

    // Testimonial 3 Image
    $wp_customize->add_setting('aqualuxe_testimonial_3_image', array(
        'default' => '',
        'sanitize_callback' => 'absint',
    ));

    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'aqualuxe_testimonial_3_image', array(
        'label' => esc_html__('Image', 'aqualuxe'),
        'section' => 'aqualuxe_homepage',
        'mime_type' => 'image',
        'priority' => 360,
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_enable_testimonials', true);
        },
    )));

    // Newsletter Section
    $wp_customize->add_setting('aqualuxe_homepage_newsletter_heading', array(
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Heading($wp_customize, 'aqualuxe_homepage_newsletter_heading', array(
        'label' => esc_html__('Newsletter Section', 'aqualuxe'),
        'section' => 'aqualuxe_homepage',
        'priority' => 370,
    )));

    // Enable Newsletter Section
    $wp_customize->add_setting('aqualuxe_enable_newsletter', array(
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Toggle($wp_customize, 'aqualuxe_enable_newsletter', array(
        'label' => esc_html__('Enable Newsletter Section', 'aqualuxe'),
        'description' => esc_html__('Show the newsletter section on the homepage.', 'aqualuxe'),
        'section' => 'aqualuxe_homepage',
        'priority' => 380,
    )));

    // Newsletter Title
    $wp_customize->add_setting('aqualuxe_newsletter_title', array(
        'default' => esc_html__('Subscribe to Our Newsletter', 'aqualuxe'),
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('aqualuxe_newsletter_title', array(
        'label' => esc_html__('Section Title', 'aqualuxe'),
        'section' => 'aqualuxe_homepage',
        'type' => 'text',
        'priority' => 390,
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_enable_newsletter', true);
        },
    ));

    // Newsletter Subtitle
    $wp_customize->add_setting('aqualuxe_newsletter_subtitle', array(
        'default' => esc_html__('Stay updated with our latest arrivals, breeding success stories, and exclusive offers', 'aqualuxe'),
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('aqualuxe_newsletter_subtitle', array(
        'label' => esc_html__('Section Subtitle', 'aqualuxe'),
        'section' => 'aqualuxe_homepage',
        'type' => 'text',
        'priority' => 400,
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_enable_newsletter', true);
        },
    ));

    // Newsletter Background
    $wp_customize->add_setting('aqualuxe_newsletter_background', array(
        'default' => '',
        'sanitize_callback' => 'absint',
    ));

    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'aqualuxe_newsletter_background', array(
        'label' => esc_html__('Background Image', 'aqualuxe'),
        'description' => esc_html__('Upload a background image for the newsletter section.', 'aqualuxe'),
        'section' => 'aqualuxe_homepage',
        'mime_type' => 'image',
        'priority' => 410,
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_enable_newsletter', true);
        },
    )));

    // Newsletter Form Shortcode
    $wp_customize->add_setting('aqualuxe_newsletter_shortcode', array(
        'default' => '',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('aqualuxe_newsletter_shortcode', array(
        'label' => esc_html__('Newsletter Form Shortcode', 'aqualuxe'),
        'description' => esc_html__('Enter the shortcode for your newsletter form plugin (e.g., Mailchimp, Contact Form 7).', 'aqualuxe'),
        'section' => 'aqualuxe_homepage',
        'type' => 'text',
        'priority' => 420,
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_enable_newsletter', true);
        },
    ));
}
add_action('customize_register', 'aqualuxe_customize_register_homepage');