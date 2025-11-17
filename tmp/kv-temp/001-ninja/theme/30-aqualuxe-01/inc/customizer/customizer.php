<?php
/**
 * AquaLuxe Theme Customizer
 *
 * @package AquaLuxe
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_register($wp_customize) {
    $wp_customize->get_setting('blogname')->transport = 'postMessage';
    $wp_customize->get_setting('blogdescription')->transport = 'postMessage';
    $wp_customize->get_setting('header_textcolor')->transport = 'postMessage';

    if (isset($wp_customize->selective_refresh)) {
        $wp_customize->selective_refresh->add_partial(
            'blogname',
            array(
                'selector'        => '.site-title a',
                'render_callback' => 'aqualuxe_customize_partial_blogname',
            )
        );
        $wp_customize->selective_refresh->add_partial(
            'blogdescription',
            array(
                'selector'        => '.site-description',
                'render_callback' => 'aqualuxe_customize_partial_blogdescription',
            )
        );
    }

    // Add theme options panel
    $wp_customize->add_panel('aqualuxe_theme_options', array(
        'title'    => __('Theme Options', 'aqualuxe'),
        'priority' => 30,
    ));

    // Add sections to the theme options panel
    aqualuxe_customize_general_section($wp_customize);
    aqualuxe_customize_header_section($wp_customize);
    aqualuxe_customize_footer_section($wp_customize);
    aqualuxe_customize_colors_section($wp_customize);
    aqualuxe_customize_typography_section($wp_customize);
    aqualuxe_customize_blog_section($wp_customize);
    aqualuxe_customize_woocommerce_section($wp_customize);
    aqualuxe_customize_social_section($wp_customize);
    aqualuxe_customize_performance_section($wp_customize);
}
add_action('customize_register', 'aqualuxe_customize_register');

/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function aqualuxe_customize_partial_blogname() {
    bloginfo('name');
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function aqualuxe_customize_partial_blogdescription() {
    bloginfo('description');
}

/**
 * Add general section to the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_general_section($wp_customize) {
    // Add general section
    $wp_customize->add_section('aqualuxe_general', array(
        'title'    => __('General Settings', 'aqualuxe'),
        'priority' => 10,
        'panel'    => 'aqualuxe_theme_options',
    ));

    // Add layout setting
    $wp_customize->add_setting('aqualuxe_layout', array(
        'default'           => 'wide',
        'sanitize_callback' => 'aqualuxe_sanitize_layout',
        'transport'         => 'postMessage',
    ));

    // Add layout control
    $wp_customize->add_control('aqualuxe_layout', array(
        'label'       => __('Layout', 'aqualuxe'),
        'description' => __('Choose the layout for your site.', 'aqualuxe'),
        'section'     => 'aqualuxe_general',
        'type'        => 'select',
        'choices'     => array(
            'wide'   => __('Wide', 'aqualuxe'),
            'boxed'  => __('Boxed', 'aqualuxe'),
            'framed' => __('Framed', 'aqualuxe'),
            'full'   => __('Full Width', 'aqualuxe'),
        ),
    ));

    // Add sidebar position setting
    $wp_customize->add_setting('aqualuxe_sidebar_position', array(
        'default'           => 'right',
        'sanitize_callback' => 'aqualuxe_sanitize_sidebar_position',
        'transport'         => 'postMessage',
    ));

    // Add sidebar position control
    $wp_customize->add_control('aqualuxe_sidebar_position', array(
        'label'       => __('Sidebar Position', 'aqualuxe'),
        'description' => __('Choose the position for your sidebar.', 'aqualuxe'),
        'section'     => 'aqualuxe_general',
        'type'        => 'select',
        'choices'     => array(
            'right' => __('Right', 'aqualuxe'),
            'left'  => __('Left', 'aqualuxe'),
            'none'  => __('No Sidebar', 'aqualuxe'),
        ),
    ));

    // Add breadcrumbs setting
    $wp_customize->add_setting('aqualuxe_enable_breadcrumbs', array(
        'default'           => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    // Add breadcrumbs control
    $wp_customize->add_control('aqualuxe_enable_breadcrumbs', array(
        'label'       => __('Enable Breadcrumbs', 'aqualuxe'),
        'description' => __('Show breadcrumbs on pages and posts.', 'aqualuxe'),
        'section'     => 'aqualuxe_general',
        'type'        => 'checkbox',
    ));

    // Add back to top setting
    $wp_customize->add_setting('aqualuxe_enable_back_to_top', array(
        'default'           => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    // Add back to top control
    $wp_customize->add_control('aqualuxe_enable_back_to_top', array(
        'label'       => __('Enable Back to Top Button', 'aqualuxe'),
        'description' => __('Show a back to top button on pages.', 'aqualuxe'),
        'section'     => 'aqualuxe_general',
        'type'        => 'checkbox',
    ));

    // Add preloader setting
    $wp_customize->add_setting('aqualuxe_enable_preloader', array(
        'default'           => false,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    // Add preloader control
    $wp_customize->add_control('aqualuxe_enable_preloader', array(
        'label'       => __('Enable Preloader', 'aqualuxe'),
        'description' => __('Show a preloader animation while the page is loading.', 'aqualuxe'),
        'section'     => 'aqualuxe_general',
        'type'        => 'checkbox',
    ));
}

/**
 * Add header section to the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_header_section($wp_customize) {
    // Add header section
    $wp_customize->add_section('aqualuxe_header', array(
        'title'    => __('Header Settings', 'aqualuxe'),
        'priority' => 20,
        'panel'    => 'aqualuxe_theme_options',
    ));

    // Add header layout setting
    $wp_customize->add_setting('aqualuxe_header_layout', array(
        'default'           => 'default',
        'sanitize_callback' => 'aqualuxe_sanitize_header_layout',
        'transport'         => 'postMessage',
    ));

    // Add header layout control
    $wp_customize->add_control('aqualuxe_header_layout', array(
        'label'       => __('Header Layout', 'aqualuxe'),
        'description' => __('Choose the layout for your header.', 'aqualuxe'),
        'section'     => 'aqualuxe_header',
        'type'        => 'select',
        'choices'     => array(
            'default'     => __('Default', 'aqualuxe'),
            'centered'    => __('Centered', 'aqualuxe'),
            'transparent' => __('Transparent', 'aqualuxe'),
            'sticky'      => __('Sticky', 'aqualuxe'),
            'minimal'     => __('Minimal', 'aqualuxe'),
        ),
    ));

    // Add sticky header setting
    $wp_customize->add_setting('aqualuxe_enable_sticky_header', array(
        'default'           => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    // Add sticky header control
    $wp_customize->add_control('aqualuxe_enable_sticky_header', array(
        'label'       => __('Enable Sticky Header', 'aqualuxe'),
        'description' => __('Make the header stick to the top of the page when scrolling.', 'aqualuxe'),
        'section'     => 'aqualuxe_header',
        'type'        => 'checkbox',
    ));

    // Add search icon setting
    $wp_customize->add_setting('aqualuxe_enable_header_search', array(
        'default'           => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    // Add search icon control
    $wp_customize->add_control('aqualuxe_enable_header_search', array(
        'label'       => __('Enable Search Icon', 'aqualuxe'),
        'description' => __('Show a search icon in the header.', 'aqualuxe'),
        'section'     => 'aqualuxe_header',
        'type'        => 'checkbox',
    ));

    // Add cart icon setting
    $wp_customize->add_setting('aqualuxe_enable_header_cart', array(
        'default'           => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    // Add cart icon control
    $wp_customize->add_control('aqualuxe_enable_header_cart', array(
        'label'       => __('Enable Cart Icon', 'aqualuxe'),
        'description' => __('Show a cart icon in the header.', 'aqualuxe'),
        'section'     => 'aqualuxe_header',
        'type'        => 'checkbox',
        'active_callback' => 'aqualuxe_is_woocommerce_active',
    ));

    // Add account icon setting
    $wp_customize->add_setting('aqualuxe_enable_header_account', array(
        'default'           => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    // Add account icon control
    $wp_customize->add_control('aqualuxe_enable_header_account', array(
        'label'       => __('Enable Account Icon', 'aqualuxe'),
        'description' => __('Show an account icon in the header.', 'aqualuxe'),
        'section'     => 'aqualuxe_header',
        'type'        => 'checkbox',
        'active_callback' => 'aqualuxe_is_woocommerce_active',
    ));

    // Add wishlist icon setting
    $wp_customize->add_setting('aqualuxe_enable_header_wishlist', array(
        'default'           => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    // Add wishlist icon control
    $wp_customize->add_control('aqualuxe_enable_header_wishlist', array(
        'label'       => __('Enable Wishlist Icon', 'aqualuxe'),
        'description' => __('Show a wishlist icon in the header.', 'aqualuxe'),
        'section'     => 'aqualuxe_header',
        'type'        => 'checkbox',
        'active_callback' => 'aqualuxe_is_woocommerce_active',
    ));

    // Add top bar setting
    $wp_customize->add_setting('aqualuxe_enable_top_bar', array(
        'default'           => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    // Add top bar control
    $wp_customize->add_control('aqualuxe_enable_top_bar', array(
        'label'       => __('Enable Top Bar', 'aqualuxe'),
        'description' => __('Show a top bar above the header.', 'aqualuxe'),
        'section'     => 'aqualuxe_header',
        'type'        => 'checkbox',
    ));

    // Add top bar content setting
    $wp_customize->add_setting('aqualuxe_top_bar_content', array(
        'default'           => __('Welcome to AquaLuxe - Bringing elegance to aquatic life – globally', 'aqualuxe'),
        'sanitize_callback' => 'aqualuxe_sanitize_html',
        'transport'         => 'postMessage',
    ));

    // Add top bar content control
    $wp_customize->add_control('aqualuxe_top_bar_content', array(
        'label'       => __('Top Bar Content', 'aqualuxe'),
        'description' => __('Enter the content for the top bar.', 'aqualuxe'),
        'section'     => 'aqualuxe_header',
        'type'        => 'textarea',
        'active_callback' => 'aqualuxe_is_top_bar_enabled',
    ));

    // Add top bar phone setting
    $wp_customize->add_setting('aqualuxe_top_bar_phone', array(
        'default'           => '+1 (555) 123-4567',
        'sanitize_callback' => 'aqualuxe_sanitize_phone',
        'transport'         => 'postMessage',
    ));

    // Add top bar phone control
    $wp_customize->add_control('aqualuxe_top_bar_phone', array(
        'label'       => __('Top Bar Phone', 'aqualuxe'),
        'description' => __('Enter the phone number for the top bar.', 'aqualuxe'),
        'section'     => 'aqualuxe_header',
        'type'        => 'text',
        'active_callback' => 'aqualuxe_is_top_bar_enabled',
    ));

    // Add top bar email setting
    $wp_customize->add_setting('aqualuxe_top_bar_email', array(
        'default'           => 'info@aqualuxe.example.com',
        'sanitize_callback' => 'aqualuxe_sanitize_email',
        'transport'         => 'postMessage',
    ));

    // Add top bar email control
    $wp_customize->add_control('aqualuxe_top_bar_email', array(
        'label'       => __('Top Bar Email', 'aqualuxe'),
        'description' => __('Enter the email address for the top bar.', 'aqualuxe'),
        'section'     => 'aqualuxe_header',
        'type'        => 'email',
        'active_callback' => 'aqualuxe_is_top_bar_enabled',
    ));

    // Add top bar social icons setting
    $wp_customize->add_setting('aqualuxe_enable_top_bar_social', array(
        'default'           => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    // Add top bar social icons control
    $wp_customize->add_control('aqualuxe_enable_top_bar_social', array(
        'label'       => __('Enable Top Bar Social Icons', 'aqualuxe'),
        'description' => __('Show social icons in the top bar.', 'aqualuxe'),
        'section'     => 'aqualuxe_header',
        'type'        => 'checkbox',
        'active_callback' => 'aqualuxe_is_top_bar_enabled',
    ));
}

/**
 * Add footer section to the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_footer_section($wp_customize) {
    // Add footer section
    $wp_customize->add_section('aqualuxe_footer', array(
        'title'    => __('Footer Settings', 'aqualuxe'),
        'priority' => 30,
        'panel'    => 'aqualuxe_theme_options',
    ));

    // Add footer layout setting
    $wp_customize->add_setting('aqualuxe_footer_layout', array(
        'default'           => '4-columns',
        'sanitize_callback' => 'aqualuxe_sanitize_footer_layout',
        'transport'         => 'postMessage',
    ));

    // Add footer layout control
    $wp_customize->add_control('aqualuxe_footer_layout', array(
        'label'       => __('Footer Layout', 'aqualuxe'),
        'description' => __('Choose the layout for your footer.', 'aqualuxe'),
        'section'     => 'aqualuxe_footer',
        'type'        => 'select',
        'choices'     => array(
            '1-column'  => __('1 Column', 'aqualuxe'),
            '2-columns' => __('2 Columns', 'aqualuxe'),
            '3-columns' => __('3 Columns', 'aqualuxe'),
            '4-columns' => __('4 Columns', 'aqualuxe'),
            'custom'    => __('Custom', 'aqualuxe'),
        ),
    ));

    // Add footer logo setting
    $wp_customize->add_setting('aqualuxe_footer_logo', array(
        'default'           => '',
        'sanitize_callback' => 'aqualuxe_sanitize_image',
        'transport'         => 'postMessage',
    ));

    // Add footer logo control
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'aqualuxe_footer_logo', array(
        'label'       => __('Footer Logo', 'aqualuxe'),
        'description' => __('Upload a logo for the footer.', 'aqualuxe'),
        'section'     => 'aqualuxe_footer',
    )));

    // Add footer about text setting
    $wp_customize->add_setting('aqualuxe_footer_about', array(
        'default'           => __('AquaLuxe is a premium aquatic retail brand specializing in rare and exotic fish species, aquatic plants, and high-end aquarium equipment. Our mission is to bring elegance to aquatic life globally.', 'aqualuxe'),
        'sanitize_callback' => 'aqualuxe_sanitize_html',
        'transport'         => 'postMessage',
    ));

    // Add footer about text control
    $wp_customize->add_control('aqualuxe_footer_about', array(
        'label'       => __('Footer About Text', 'aqualuxe'),
        'description' => __('Enter the about text for the footer.', 'aqualuxe'),
        'section'     => 'aqualuxe_footer',
        'type'        => 'textarea',
    ));

    // Add footer social icons setting
    $wp_customize->add_setting('aqualuxe_enable_footer_social', array(
        'default'           => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    // Add footer social icons control
    $wp_customize->add_control('aqualuxe_enable_footer_social', array(
        'label'       => __('Enable Footer Social Icons', 'aqualuxe'),
        'description' => __('Show social icons in the footer.', 'aqualuxe'),
        'section'     => 'aqualuxe_footer',
        'type'        => 'checkbox',
    ));

    // Add footer payment icons setting
    $wp_customize->add_setting('aqualuxe_enable_footer_payment', array(
        'default'           => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    // Add footer payment icons control
    $wp_customize->add_control('aqualuxe_enable_footer_payment', array(
        'label'       => __('Enable Footer Payment Icons', 'aqualuxe'),
        'description' => __('Show payment icons in the footer.', 'aqualuxe'),
        'section'     => 'aqualuxe_footer',
        'type'        => 'checkbox',
    ));

    // Add footer payment icons setting
    $wp_customize->add_setting('aqualuxe_footer_payment_icons', array(
        'default'           => array('visa', 'mastercard', 'amex', 'paypal'),
        'sanitize_callback' => 'aqualuxe_sanitize_multi_select',
    ));

    // Add footer payment icons control
    $wp_customize->add_control(new AquaLuxe_Customize_Multi_Select_Control($wp_customize, 'aqualuxe_footer_payment_icons', array(
        'label'       => __('Footer Payment Icons', 'aqualuxe'),
        'description' => __('Select the payment icons to display in the footer.', 'aqualuxe'),
        'section'     => 'aqualuxe_footer',
        'choices'     => array(
            'visa'       => __('Visa', 'aqualuxe'),
            'mastercard' => __('Mastercard', 'aqualuxe'),
            'amex'       => __('American Express', 'aqualuxe'),
            'discover'   => __('Discover', 'aqualuxe'),
            'paypal'     => __('PayPal', 'aqualuxe'),
            'apple-pay'  => __('Apple Pay', 'aqualuxe'),
            'google-pay' => __('Google Pay', 'aqualuxe'),
            'stripe'     => __('Stripe', 'aqualuxe'),
        ),
        'active_callback' => 'aqualuxe_is_footer_payment_enabled',
    )));

    // Add footer copyright setting
    $wp_customize->add_setting('aqualuxe_footer_copyright', array(
        'default'           => __('© 2025 AquaLuxe. All rights reserved.', 'aqualuxe'),
        'sanitize_callback' => 'aqualuxe_sanitize_html',
        'transport'         => 'postMessage',
    ));

    // Add footer copyright control
    $wp_customize->add_control('aqualuxe_footer_copyright', array(
        'label'       => __('Footer Copyright Text', 'aqualuxe'),
        'description' => __('Enter the copyright text for the footer.', 'aqualuxe'),
        'section'     => 'aqualuxe_footer',
        'type'        => 'textarea',
    ));

    // Add footer newsletter setting
    $wp_customize->add_setting('aqualuxe_enable_footer_newsletter', array(
        'default'           => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    // Add footer newsletter control
    $wp_customize->add_control('aqualuxe_enable_footer_newsletter', array(
        'label'       => __('Enable Footer Newsletter', 'aqualuxe'),
        'description' => __('Show a newsletter signup form in the footer.', 'aqualuxe'),
        'section'     => 'aqualuxe_footer',
        'type'        => 'checkbox',
    ));

    // Add footer newsletter heading setting
    $wp_customize->add_setting('aqualuxe_footer_newsletter_heading', array(
        'default'           => __('Subscribe to Our Newsletter', 'aqualuxe'),
        'sanitize_callback' => 'aqualuxe_sanitize_text',
        'transport'         => 'postMessage',
    ));

    // Add footer newsletter heading control
    $wp_customize->add_control('aqualuxe_footer_newsletter_heading', array(
        'label'       => __('Newsletter Heading', 'aqualuxe'),
        'description' => __('Enter the heading for the newsletter form.', 'aqualuxe'),
        'section'     => 'aqualuxe_footer',
        'type'        => 'text',
        'active_callback' => 'aqualuxe_is_footer_newsletter_enabled',
    ));

    // Add footer newsletter text setting
    $wp_customize->add_setting('aqualuxe_footer_newsletter_text', array(
        'default'           => __('Stay updated with our latest products, aquatic care tips, and exclusive offers.', 'aqualuxe'),
        'sanitize_callback' => 'aqualuxe_sanitize_text',
        'transport'         => 'postMessage',
    ));

    // Add footer newsletter text control
    $wp_customize->add_control('aqualuxe_footer_newsletter_text', array(
        'label'       => __('Newsletter Text', 'aqualuxe'),
        'description' => __('Enter the text for the newsletter form.', 'aqualuxe'),
        'section'     => 'aqualuxe_footer',
        'type'        => 'textarea',
        'active_callback' => 'aqualuxe_is_footer_newsletter_enabled',
    ));
}

/**
 * Add colors section to the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_colors_section($wp_customize) {
    // Add colors section
    $wp_customize->add_section('aqualuxe_colors', array(
        'title'    => __('Colors', 'aqualuxe'),
        'priority' => 40,
        'panel'    => 'aqualuxe_theme_options',
    ));

    // Add color scheme setting
    $wp_customize->add_setting('aqualuxe_color_scheme', array(
        'default'           => 'default',
        'sanitize_callback' => 'aqualuxe_sanitize_color_scheme',
        'transport'         => 'postMessage',
    ));

    // Add color scheme control
    $wp_customize->add_control('aqualuxe_color_scheme', array(
        'label'       => __('Color Scheme', 'aqualuxe'),
        'description' => __('Choose a predefined color scheme or select custom to define your own colors.', 'aqualuxe'),
        'section'     => 'aqualuxe_colors',
        'type'        => 'select',
        'choices'     => array(
            'default' => __('Default (Aqua Blue)', 'aqualuxe'),
            'light'   => __('Light', 'aqualuxe'),
            'dark'    => __('Dark', 'aqualuxe'),
            'blue'    => __('Blue', 'aqualuxe'),
            'green'   => __('Green', 'aqualuxe'),
            'red'     => __('Red', 'aqualuxe'),
            'purple'  => __('Purple', 'aqualuxe'),
            'orange'  => __('Orange', 'aqualuxe'),
            'pink'    => __('Pink', 'aqualuxe'),
            'teal'    => __('Teal', 'aqualuxe'),
            'custom'  => __('Custom', 'aqualuxe'),
        ),
    ));

    // Add primary color setting
    $wp_customize->add_setting('aqualuxe_primary_color', array(
        'default'           => '#0ea5e9',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));

    // Add primary color control
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_primary_color', array(
        'label'       => __('Primary Color', 'aqualuxe'),
        'description' => __('Select the primary color for buttons, links, and accents.', 'aqualuxe'),
        'section'     => 'aqualuxe_colors',
        'active_callback' => 'aqualuxe_is_custom_color_scheme',
    )));

    // Add secondary color setting
    $wp_customize->add_setting('aqualuxe_secondary_color', array(
        'default'           => '#0284c7',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));

    // Add secondary color control
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_secondary_color', array(
        'label'       => __('Secondary Color', 'aqualuxe'),
        'description' => __('Select the secondary color for hover states and secondary elements.', 'aqualuxe'),
        'section'     => 'aqualuxe_colors',
        'active_callback' => 'aqualuxe_is_custom_color_scheme',
    )));

    // Add background color setting
    $wp_customize->add_setting('aqualuxe_background_color', array(
        'default'           => '#ffffff',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));

    // Add background color control
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_background_color', array(
        'label'       => __('Background Color', 'aqualuxe'),
        'description' => __('Select the background color for the site.', 'aqualuxe'),
        'section'     => 'aqualuxe_colors',
        'active_callback' => 'aqualuxe_is_custom_color_scheme',
    )));

    // Add text color setting
    $wp_customize->add_setting('aqualuxe_text_color', array(
        'default'           => '#333333',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));

    // Add text color control
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_text_color', array(
        'label'       => __('Text Color', 'aqualuxe'),
        'description' => __('Select the text color for the site.', 'aqualuxe'),
        'section'     => 'aqualuxe_colors',
        'active_callback' => 'aqualuxe_is_custom_color_scheme',
    )));

    // Add heading color setting
    $wp_customize->add_setting('aqualuxe_heading_color', array(
        'default'           => '#111827',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));

    // Add heading color control
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_heading_color', array(
        'label'       => __('Heading Color', 'aqualuxe'),
        'description' => __('Select the color for headings.', 'aqualuxe'),
        'section'     => 'aqualuxe_colors',
        'active_callback' => 'aqualuxe_is_custom_color_scheme',
    )));

    // Add link color setting
    $wp_customize->add_setting('aqualuxe_link_color', array(
        'default'           => '#0ea5e9',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));

    // Add link color control
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_link_color', array(
        'label'       => __('Link Color', 'aqualuxe'),
        'description' => __('Select the color for links.', 'aqualuxe'),
        'section'     => 'aqualuxe_colors',
        'active_callback' => 'aqualuxe_is_custom_color_scheme',
    )));

    // Add link hover color setting
    $wp_customize->add_setting('aqualuxe_link_hover_color', array(
        'default'           => '#0284c7',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));

    // Add link hover color control
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_link_hover_color', array(
        'label'       => __('Link Hover Color', 'aqualuxe'),
        'description' => __('Select the color for links when hovered.', 'aqualuxe'),
        'section'     => 'aqualuxe_colors',
        'active_callback' => 'aqualuxe_is_custom_color_scheme',
    )));

    // Add button text color setting
    $wp_customize->add_setting('aqualuxe_button_text_color', array(
        'default'           => '#ffffff',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));

    // Add button text color control
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_button_text_color', array(
        'label'       => __('Button Text Color', 'aqualuxe'),
        'description' => __('Select the text color for buttons.', 'aqualuxe'),
        'section'     => 'aqualuxe_colors',
        'active_callback' => 'aqualuxe_is_custom_color_scheme',
    )));

    // Add header background color setting
    $wp_customize->add_setting('aqualuxe_header_background_color', array(
        'default'           => '#ffffff',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));

    // Add header background color control
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_header_background_color', array(
        'label'       => __('Header Background Color', 'aqualuxe'),
        'description' => __('Select the background color for the header.', 'aqualuxe'),
        'section'     => 'aqualuxe_colors',
        'active_callback' => 'aqualuxe_is_custom_color_scheme',
    )));

    // Add footer background color setting
    $wp_customize->add_setting('aqualuxe_footer_background_color', array(
        'default'           => '#111827',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));

    // Add footer background color control
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_footer_background_color', array(
        'label'       => __('Footer Background Color', 'aqualuxe'),
        'description' => __('Select the background color for the footer.', 'aqualuxe'),
        'section'     => 'aqualuxe_colors',
        'active_callback' => 'aqualuxe_is_custom_color_scheme',
    )));

    // Add footer text color setting
    $wp_customize->add_setting('aqualuxe_footer_text_color', array(
        'default'           => '#f9fafb',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));

    // Add footer text color control
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_footer_text_color', array(
        'label'       => __('Footer Text Color', 'aqualuxe'),
        'description' => __('Select the text color for the footer.', 'aqualuxe'),
        'section'     => 'aqualuxe_colors',
        'active_callback' => 'aqualuxe_is_custom_color_scheme',
    )));
}

/**
 * Add typography section to the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_typography_section($wp_customize) {
    // Add typography section
    $wp_customize->add_section('aqualuxe_typography', array(
        'title'    => __('Typography', 'aqualuxe'),
        'priority' => 50,
        'panel'    => 'aqualuxe_theme_options',
    ));

    // Add body font setting
    $wp_customize->add_setting('aqualuxe_body_font', array(
        'default'           => 'Inter',
        'sanitize_callback' => 'aqualuxe_sanitize_google_font',
        'transport'         => 'postMessage',
    ));

    // Add body font control
    $wp_customize->add_control('aqualuxe_body_font', array(
        'label'       => __('Body Font', 'aqualuxe'),
        'description' => __('Select the font for the body text.', 'aqualuxe'),
        'section'     => 'aqualuxe_typography',
        'type'        => 'select',
        'choices'     => aqualuxe_get_google_fonts(),
    ));

    // Add heading font setting
    $wp_customize->add_setting('aqualuxe_heading_font', array(
        'default'           => 'Playfair Display',
        'sanitize_callback' => 'aqualuxe_sanitize_google_font',
        'transport'         => 'postMessage',
    ));

    // Add heading font control
    $wp_customize->add_control('aqualuxe_heading_font', array(
        'label'       => __('Heading Font', 'aqualuxe'),
        'description' => __('Select the font for headings.', 'aqualuxe'),
        'section'     => 'aqualuxe_typography',
        'type'        => 'select',
        'choices'     => aqualuxe_get_google_fonts(),
    ));

    // Add body font size setting
    $wp_customize->add_setting('aqualuxe_body_font_size', array(
        'default'           => '16',
        'sanitize_callback' => 'absint',
        'transport'         => 'postMessage',
    ));

    // Add body font size control
    $wp_customize->add_control('aqualuxe_body_font_size', array(
        'label'       => __('Body Font Size (px)', 'aqualuxe'),
        'description' => __('Select the font size for the body text.', 'aqualuxe'),
        'section'     => 'aqualuxe_typography',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 12,
            'max'  => 24,
            'step' => 1,
        ),
    ));

    // Add heading font weight setting
    $wp_customize->add_setting('aqualuxe_heading_font_weight', array(
        'default'           => '700',
        'sanitize_callback' => 'aqualuxe_sanitize_font_weight',
        'transport'         => 'postMessage',
    ));

    // Add heading font weight control
    $wp_customize->add_control('aqualuxe_heading_font_weight', array(
        'label'       => __('Heading Font Weight', 'aqualuxe'),
        'description' => __('Select the font weight for headings.', 'aqualuxe'),
        'section'     => 'aqualuxe_typography',
        'type'        => 'select',
        'choices'     => array(
            '300' => __('Light (300)', 'aqualuxe'),
            '400' => __('Regular (400)', 'aqualuxe'),
            '500' => __('Medium (500)', 'aqualuxe'),
            '600' => __('Semi-Bold (600)', 'aqualuxe'),
            '700' => __('Bold (700)', 'aqualuxe'),
            '800' => __('Extra-Bold (800)', 'aqualuxe'),
            '900' => __('Black (900)', 'aqualuxe'),
        ),
    ));

    // Add body font weight setting
    $wp_customize->add_setting('aqualuxe_body_font_weight', array(
        'default'           => '400',
        'sanitize_callback' => 'aqualuxe_sanitize_font_weight',
        'transport'         => 'postMessage',
    ));

    // Add body font weight control
    $wp_customize->add_control('aqualuxe_body_font_weight', array(
        'label'       => __('Body Font Weight', 'aqualuxe'),
        'description' => __('Select the font weight for body text.', 'aqualuxe'),
        'section'     => 'aqualuxe_typography',
        'type'        => 'select',
        'choices'     => array(
            '300' => __('Light (300)', 'aqualuxe'),
            '400' => __('Regular (400)', 'aqualuxe'),
            '500' => __('Medium (500)', 'aqualuxe'),
            '600' => __('Semi-Bold (600)', 'aqualuxe'),
            '700' => __('Bold (700)', 'aqualuxe'),
        ),
    ));

    // Add line height setting
    $wp_customize->add_setting('aqualuxe_line_height', array(
        'default'           => '1.6',
        'sanitize_callback' => 'aqualuxe_sanitize_float',
        'transport'         => 'postMessage',
    ));

    // Add line height control
    $wp_customize->add_control('aqualuxe_line_height', array(
        'label'       => __('Line Height', 'aqualuxe'),
        'description' => __('Select the line height for body text.', 'aqualuxe'),
        'section'     => 'aqualuxe_typography',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 1,
            'max'  => 2,
            'step' => 0.1,
        ),
    ));

    // Add custom CSS setting
    $wp_customize->add_setting('aqualuxe_custom_css', array(
        'default'           => '',
        'sanitize_callback' => 'aqualuxe_sanitize_css',
        'transport'         => 'postMessage',
    ));

    // Add custom CSS control
    $wp_customize->add_control('aqualuxe_custom_css', array(
        'label'       => __('Custom CSS', 'aqualuxe'),
        'description' => __('Add custom CSS to customize the theme.', 'aqualuxe'),
        'section'     => 'aqualuxe_typography',
        'type'        => 'textarea',
    ));
}

/**
 * Add blog section to the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_blog_section($wp_customize) {
    // Add blog section
    $wp_customize->add_section('aqualuxe_blog', array(
        'title'    => __('Blog Settings', 'aqualuxe'),
        'priority' => 60,
        'panel'    => 'aqualuxe_theme_options',
    ));

    // Add blog layout setting
    $wp_customize->add_setting('aqualuxe_blog_layout', array(
        'default'           => 'grid',
        'sanitize_callback' => 'aqualuxe_sanitize_blog_layout',
        'transport'         => 'postMessage',
    ));

    // Add blog layout control
    $wp_customize->add_control('aqualuxe_blog_layout', array(
        'label'       => __('Blog Layout', 'aqualuxe'),
        'description' => __('Choose the layout for your blog.', 'aqualuxe'),
        'section'     => 'aqualuxe_blog',
        'type'        => 'select',
        'choices'     => array(
            'grid'     => __('Grid', 'aqualuxe'),
            'list'     => __('List', 'aqualuxe'),
            'masonry'  => __('Masonry', 'aqualuxe'),
            'standard' => __('Standard', 'aqualuxe'),
        ),
    ));

    // Add blog sidebar setting
    $wp_customize->add_setting('aqualuxe_blog_sidebar', array(
        'default'           => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    // Add blog sidebar control
    $wp_customize->add_control('aqualuxe_blog_sidebar', array(
        'label'       => __('Enable Blog Sidebar', 'aqualuxe'),
        'description' => __('Show a sidebar on blog pages.', 'aqualuxe'),
        'section'     => 'aqualuxe_blog',
        'type'        => 'checkbox',
    ));

    // Add blog excerpt setting
    $wp_customize->add_setting('aqualuxe_blog_excerpt', array(
        'default'           => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    // Add blog excerpt control
    $wp_customize->add_control('aqualuxe_blog_excerpt', array(
        'label'       => __('Show Excerpts', 'aqualuxe'),
        'description' => __('Show excerpts instead of full content on blog pages.', 'aqualuxe'),
        'section'     => 'aqualuxe_blog',
        'type'        => 'checkbox',
    ));

    // Add blog excerpt length setting
    $wp_customize->add_setting('aqualuxe_blog_excerpt_length', array(
        'default'           => '55',
        'sanitize_callback' => 'absint',
    ));

    // Add blog excerpt length control
    $wp_customize->add_control('aqualuxe_blog_excerpt_length', array(
        'label'       => __('Excerpt Length', 'aqualuxe'),
        'description' => __('Number of words to show in excerpts.', 'aqualuxe'),
        'section'     => 'aqualuxe_blog',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 10,
            'max'  => 200,
            'step' => 5,
        ),
        'active_callback' => 'aqualuxe_is_blog_excerpt_enabled',
    ));

    // Add blog featured image setting
    $wp_customize->add_setting('aqualuxe_blog_featured_image', array(
        'default'           => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    // Add blog featured image control
    $wp_customize->add_control('aqualuxe_blog_featured_image', array(
        'label'       => __('Show Featured Images', 'aqualuxe'),
        'description' => __('Show featured images on blog pages.', 'aqualuxe'),
        'section'     => 'aqualuxe_blog',
        'type'        => 'checkbox',
    ));

    // Add blog meta setting
    $wp_customize->add_setting('aqualuxe_blog_meta', array(
        'default'           => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    // Add blog meta control
    $wp_customize->add_control('aqualuxe_blog_meta', array(
        'label'       => __('Show Post Meta', 'aqualuxe'),
        'description' => __('Show post meta information (author, date, categories) on blog pages.', 'aqualuxe'),
        'section'     => 'aqualuxe_blog',
        'type'        => 'checkbox',
    ));

    // Add blog author setting
    $wp_customize->add_setting('aqualuxe_blog_author', array(
        'default'           => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    // Add blog author control
    $wp_customize->add_control('aqualuxe_blog_author', array(
        'label'       => __('Show Author Info', 'aqualuxe'),
        'description' => __('Show author information on single posts.', 'aqualuxe'),
        'section'     => 'aqualuxe_blog',
        'type'        => 'checkbox',
    ));

    // Add blog related posts setting
    $wp_customize->add_setting('aqualuxe_blog_related_posts', array(
        'default'           => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    // Add blog related posts control
    $wp_customize->add_control('aqualuxe_blog_related_posts', array(
        'label'       => __('Show Related Posts', 'aqualuxe'),
        'description' => __('Show related posts on single posts.', 'aqualuxe'),
        'section'     => 'aqualuxe_blog',
        'type'        => 'checkbox',
    ));

    // Add blog social sharing setting
    $wp_customize->add_setting('aqualuxe_blog_social_sharing', array(
        'default'           => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    // Add blog social sharing control
    $wp_customize->add_control('aqualuxe_blog_social_sharing', array(
        'label'       => __('Show Social Sharing', 'aqualuxe'),
        'description' => __('Show social sharing buttons on single posts.', 'aqualuxe'),
        'section'     => 'aqualuxe_blog',
        'type'        => 'checkbox',
    ));

    // Add single post layout setting
    $wp_customize->add_setting('aqualuxe_single_post_layout', array(
        'default'           => 'standard',
        'sanitize_callback' => 'aqualuxe_sanitize_single_post_layout',
        'transport'         => 'postMessage',
    ));

    // Add single post layout control
    $wp_customize->add_control('aqualuxe_single_post_layout', array(
        'label'       => __('Single Post Layout', 'aqualuxe'),
        'description' => __('Choose the layout for single posts.', 'aqualuxe'),
        'section'     => 'aqualuxe_blog',
        'type'        => 'select',
        'choices'     => array(
            'standard'   => __('Standard', 'aqualuxe'),
            'wide'       => __('Wide', 'aqualuxe'),
            'full-width' => __('Full Width', 'aqualuxe'),
            'narrow'     => __('Narrow', 'aqualuxe'),
        ),
    ));
}

/**
 * Add WooCommerce section to the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_woocommerce_section($wp_customize) {
    // Only add this section if WooCommerce is active
    if (!class_exists('WooCommerce')) {
        return;
    }

    // Add WooCommerce section
    $wp_customize->add_section('aqualuxe_woocommerce', array(
        'title'    => __('WooCommerce Settings', 'aqualuxe'),
        'priority' => 70,
        'panel'    => 'aqualuxe_theme_options',
    ));

    // Add shop layout setting
    $wp_customize->add_setting('aqualuxe_shop_layout', array(
        'default'           => 'grid',
        'sanitize_callback' => 'aqualuxe_sanitize_shop_layout',
        'transport'         => 'postMessage',
    ));

    // Add shop layout control
    $wp_customize->add_control('aqualuxe_shop_layout', array(
        'label'       => __('Shop Layout', 'aqualuxe'),
        'description' => __('Choose the layout for your shop.', 'aqualuxe'),
        'section'     => 'aqualuxe_woocommerce',
        'type'        => 'select',
        'choices'     => array(
            'grid'    => __('Grid', 'aqualuxe'),
            'list'    => __('List', 'aqualuxe'),
            'masonry' => __('Masonry', 'aqualuxe'),
        ),
    ));

    // Add shop sidebar setting
    $wp_customize->add_setting('aqualuxe_shop_sidebar', array(
        'default'           => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    // Add shop sidebar control
    $wp_customize->add_control('aqualuxe_shop_sidebar', array(
        'label'       => __('Enable Shop Sidebar', 'aqualuxe'),
        'description' => __('Show a sidebar on shop pages.', 'aqualuxe'),
        'section'     => 'aqualuxe_woocommerce',
        'type'        => 'checkbox',
    ));

    // Add products per page setting
    $wp_customize->add_setting('aqualuxe_products_per_page', array(
        'default'           => '12',
        'sanitize_callback' => 'absint',
    ));

    // Add products per page control
    $wp_customize->add_control('aqualuxe_products_per_page', array(
        'label'       => __('Products Per Page', 'aqualuxe'),
        'description' => __('Number of products to show per page.', 'aqualuxe'),
        'section'     => 'aqualuxe_woocommerce',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 4,
            'max'  => 48,
            'step' => 4,
        ),
    ));

    // Add product columns setting
    $wp_customize->add_setting('aqualuxe_product_columns', array(
        'default'           => '4',
        'sanitize_callback' => 'absint',
    ));

    // Add product columns control
    $wp_customize->add_control('aqualuxe_product_columns', array(
        'label'       => __('Product Columns', 'aqualuxe'),
        'description' => __('Number of columns to show on shop pages.', 'aqualuxe'),
        'section'     => 'aqualuxe_woocommerce',
        'type'        => 'select',
        'choices'     => array(
            '2' => '2',
            '3' => '3',
            '4' => '4',
            '5' => '5',
            '6' => '6',
        ),
    ));

    // Add quick view setting
    $wp_customize->add_setting('aqualuxe_enable_quick_view', array(
        'default'           => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    // Add quick view control
    $wp_customize->add_control('aqualuxe_enable_quick_view', array(
        'label'       => __('Enable Quick View', 'aqualuxe'),
        'description' => __('Show a quick view button on products.', 'aqualuxe'),
        'section'     => 'aqualuxe_woocommerce',
        'type'        => 'checkbox',
    ));

    // Add wishlist setting
    $wp_customize->add_setting('aqualuxe_enable_wishlist', array(
        'default'           => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    // Add wishlist control
    $wp_customize->add_control('aqualuxe_enable_wishlist', array(
        'label'       => __('Enable Wishlist', 'aqualuxe'),
        'description' => __('Show a wishlist button on products.', 'aqualuxe'),
        'section'     => 'aqualuxe_woocommerce',
        'type'        => 'checkbox',
    ));

    // Add product zoom setting
    $wp_customize->add_setting('aqualuxe_enable_product_zoom', array(
        'default'           => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    // Add product zoom control
    $wp_customize->add_control('aqualuxe_enable_product_zoom', array(
        'label'       => __('Enable Product Zoom', 'aqualuxe'),
        'description' => __('Enable zoom functionality on product images.', 'aqualuxe'),
        'section'     => 'aqualuxe_woocommerce',
        'type'        => 'checkbox',
    ));

    // Add product gallery setting
    $wp_customize->add_setting('aqualuxe_enable_product_gallery', array(
        'default'           => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    // Add product gallery control
    $wp_customize->add_control('aqualuxe_enable_product_gallery', array(
        'label'       => __('Enable Product Gallery', 'aqualuxe'),
        'description' => __('Enable gallery functionality on product images.', 'aqualuxe'),
        'section'     => 'aqualuxe_woocommerce',
        'type'        => 'checkbox',
    ));

    // Add related products setting
    $wp_customize->add_setting('aqualuxe_enable_related_products', array(
        'default'           => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    // Add related products control
    $wp_customize->add_control('aqualuxe_enable_related_products', array(
        'label'       => __('Enable Related Products', 'aqualuxe'),
        'description' => __('Show related products on single product pages.', 'aqualuxe'),
        'section'     => 'aqualuxe_woocommerce',
        'type'        => 'checkbox',
    ));

    // Add upsells setting
    $wp_customize->add_setting('aqualuxe_enable_upsells', array(
        'default'           => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    // Add upsells control
    $wp_customize->add_control('aqualuxe_enable_upsells', array(
        'label'       => __('Enable Upsells', 'aqualuxe'),
        'description' => __('Show upsell products on single product pages.', 'aqualuxe'),
        'section'     => 'aqualuxe_woocommerce',
        'type'        => 'checkbox',
    ));

    // Add cross-sells setting
    $wp_customize->add_setting('aqualuxe_enable_cross_sells', array(
        'default'           => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    // Add cross-sells control
    $wp_customize->add_control('aqualuxe_enable_cross_sells', array(
        'label'       => __('Enable Cross-Sells', 'aqualuxe'),
        'description' => __('Show cross-sell products on the cart page.', 'aqualuxe'),
        'section'     => 'aqualuxe_woocommerce',
        'type'        => 'checkbox',
    ));

    // Add multi-currency setting
    $wp_customize->add_setting('aqualuxe_enable_multi_currency', array(
        'default'           => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    // Add multi-currency control
    $wp_customize->add_control('aqualuxe_enable_multi_currency', array(
        'label'       => __('Enable Multi-Currency', 'aqualuxe'),
        'description' => __('Enable multi-currency support.', 'aqualuxe'),
        'section'     => 'aqualuxe_woocommerce',
        'type'        => 'checkbox',
    ));

    // Add international shipping setting
    $wp_customize->add_setting('aqualuxe_enable_international_shipping', array(
        'default'           => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    // Add international shipping control
    $wp_customize->add_control('aqualuxe_enable_international_shipping', array(
        'label'       => __('Enable International Shipping', 'aqualuxe'),
        'description' => __('Enable international shipping support.', 'aqualuxe'),
        'section'     => 'aqualuxe_woocommerce',
        'type'        => 'checkbox',
    ));

    // Add checkout steps setting
    $wp_customize->add_setting('aqualuxe_enable_checkout_steps', array(
        'default'           => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    // Add checkout steps control
    $wp_customize->add_control('aqualuxe_enable_checkout_steps', array(
        'label'       => __('Enable Checkout Steps', 'aqualuxe'),
        'description' => __('Show steps indicator on the checkout page.', 'aqualuxe'),
        'section'     => 'aqualuxe_woocommerce',
        'type'        => 'checkbox',
    ));
}

/**
 * Add social section to the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_social_section($wp_customize) {
    // Add social section
    $wp_customize->add_section('aqualuxe_social', array(
        'title'    => __('Social Media', 'aqualuxe'),
        'priority' => 80,
        'panel'    => 'aqualuxe_theme_options',
    ));

    // Add social networks
    $social_networks = array(
        'facebook'  => __('Facebook', 'aqualuxe'),
        'twitter'   => __('Twitter', 'aqualuxe'),
        'instagram' => __('Instagram', 'aqualuxe'),
        'linkedin'  => __('LinkedIn', 'aqualuxe'),
        'youtube'   => __('YouTube', 'aqualuxe'),
        'pinterest' => __('Pinterest', 'aqualuxe'),
        'tiktok'    => __('TikTok', 'aqualuxe'),
    );

    foreach ($social_networks as $network => $label) {
        // Add social network setting
        $wp_customize->add_setting('aqualuxe_social_' . $network, array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
            'transport'         => 'postMessage',
        ));

        // Add social network control
        $wp_customize->add_control('aqualuxe_social_' . $network, array(
            'label'       => $label,
            'description' => sprintf(__('Enter your %s URL.', 'aqualuxe'), $label),
            'section'     => 'aqualuxe_social',
            'type'        => 'url',
        ));
    }

    // Add social sharing setting
    $wp_customize->add_setting('aqualuxe_enable_social_sharing', array(
        'default'           => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    // Add social sharing control
    $wp_customize->add_control('aqualuxe_enable_social_sharing', array(
        'label'       => __('Enable Social Sharing', 'aqualuxe'),
        'description' => __('Show social sharing buttons on posts and products.', 'aqualuxe'),
        'section'     => 'aqualuxe_social',
        'type'        => 'checkbox',
    ));

    // Add social sharing networks setting
    $wp_customize->add_setting('aqualuxe_social_sharing_networks', array(
        'default'           => array('facebook', 'twitter', 'pinterest', 'linkedin'),
        'sanitize_callback' => 'aqualuxe_sanitize_multi_select',
    ));

    // Add social sharing networks control
    $wp_customize->add_control(new AquaLuxe_Customize_Multi_Select_Control($wp_customize, 'aqualuxe_social_sharing_networks', array(
        'label'       => __('Social Sharing Networks', 'aqualuxe'),
        'description' => __('Select the social networks to include in sharing buttons.', 'aqualuxe'),
        'section'     => 'aqualuxe_social',
        'choices'     => array(
            'facebook'  => __('Facebook', 'aqualuxe'),
            'twitter'   => __('Twitter', 'aqualuxe'),
            'pinterest' => __('Pinterest', 'aqualuxe'),
            'linkedin'  => __('LinkedIn', 'aqualuxe'),
            'reddit'    => __('Reddit', 'aqualuxe'),
            'email'     => __('Email', 'aqualuxe'),
            'whatsapp'  => __('WhatsApp', 'aqualuxe'),
            'telegram'  => __('Telegram', 'aqualuxe'),
        ),
        'active_callback' => 'aqualuxe_is_social_sharing_enabled',
    )));
}

/**
 * Add performance section to the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_performance_section($wp_customize) {
    // Add performance section
    $wp_customize->add_section('aqualuxe_performance', array(
        'title'    => __('Performance', 'aqualuxe'),
        'priority' => 90,
        'panel'    => 'aqualuxe_theme_options',
    ));

    // Add lazy loading setting
    $wp_customize->add_setting('aqualuxe_enable_lazy_loading', array(
        'default'           => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    // Add lazy loading control
    $wp_customize->add_control('aqualuxe_enable_lazy_loading', array(
        'label'       => __('Enable Lazy Loading', 'aqualuxe'),
        'description' => __('Lazy load images and iframes to improve page load speed.', 'aqualuxe'),
        'section'     => 'aqualuxe_performance',
        'type'        => 'checkbox',
    ));

    // Add minification setting
    $wp_customize->add_setting('aqualuxe_enable_minification', array(
        'default'           => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    // Add minification control
    $wp_customize->add_control('aqualuxe_enable_minification', array(
        'label'       => __('Enable Minification', 'aqualuxe'),
        'description' => __('Minify CSS and JavaScript files to improve page load speed.', 'aqualuxe'),
        'section'     => 'aqualuxe_performance',
        'type'        => 'checkbox',
    ));

    // Add preloading setting
    $wp_customize->add_setting('aqualuxe_enable_preloading', array(
        'default'           => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    // Add preloading control
    $wp_customize->add_control('aqualuxe_enable_preloading', array(
        'label'       => __('Enable Preloading', 'aqualuxe'),
        'description' => __('Preload critical resources to improve page load speed.', 'aqualuxe'),
        'section'     => 'aqualuxe_performance',
        'type'        => 'checkbox',
    ));

    // Add prefetching setting
    $wp_customize->add_setting('aqualuxe_enable_prefetching', array(
        'default'           => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    // Add prefetching control
    $wp_customize->add_control('aqualuxe_enable_prefetching', array(
        'label'       => __('Enable Prefetching', 'aqualuxe'),
        'description' => __('Prefetch resources that are likely to be needed for subsequent pages.', 'aqualuxe'),
        'section'     => 'aqualuxe_performance',
        'type'        => 'checkbox',
    ));

    // Add WebP support setting
    $wp_customize->add_setting('aqualuxe_enable_webp', array(
        'default'           => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    // Add WebP support control
    $wp_customize->add_control('aqualuxe_enable_webp', array(
        'label'       => __('Enable WebP Support', 'aqualuxe'),
        'description' => __('Use WebP image format for better performance when supported by the browser.', 'aqualuxe'),
        'section'     => 'aqualuxe_performance',
        'type'        => 'checkbox',
    ));

    // Add browser caching setting
    $wp_customize->add_setting('aqualuxe_enable_browser_caching', array(
        'default'           => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    // Add browser caching control
    $wp_customize->add_control('aqualuxe_enable_browser_caching', array(
        'label'       => __('Enable Browser Caching', 'aqualuxe'),
        'description' => __('Add browser caching headers to improve page load speed for returning visitors.', 'aqualuxe'),
        'section'     => 'aqualuxe_performance',
        'type'        => 'checkbox',
    ));
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function aqualuxe_customize_preview_js() {
    wp_enqueue_script('aqualuxe-customizer', AQUALUXE_URI . '/assets/js/customizer.js', array('customize-preview'), AQUALUXE_VERSION, true);
}
add_action('customize_preview_init', 'aqualuxe_customize_preview_js');

/**
 * Enqueue scripts for the customizer controls.
 */
function aqualuxe_customize_controls_js() {
    wp_enqueue_script('aqualuxe-customize-controls', AQUALUXE_URI . '/assets/js/customize-controls.js', array('jquery'), AQUALUXE_VERSION, true);
}
add_action('customize_controls_enqueue_scripts', 'aqualuxe_customize_controls_js');

/**
 * Enqueue styles for the customizer controls.
 */
function aqualuxe_customize_controls_css() {
    wp_enqueue_style('aqualuxe-customize-controls', AQUALUXE_URI . '/assets/css/customize-controls.css', array(), AQUALUXE_VERSION);
}
add_action('customize_controls_enqueue_scripts', 'aqualuxe_customize_controls_css');

/**
 * Get Google Fonts list.
 *
 * @return array
 */
function aqualuxe_get_google_fonts() {
    return array(
        'Open Sans'         => 'Open Sans',
        'Roboto'            => 'Roboto',
        'Lato'              => 'Lato',
        'Montserrat'        => 'Montserrat',
        'Oswald'            => 'Oswald',
        'Source Sans Pro'   => 'Source Sans Pro',
        'Raleway'           => 'Raleway',
        'PT Sans'           => 'PT Sans',
        'Merriweather'      => 'Merriweather',
        'Nunito'            => 'Nunito',
        'Playfair Display'  => 'Playfair Display',
        'Poppins'           => 'Poppins',
        'Ubuntu'            => 'Ubuntu',
        'Rubik'             => 'Rubik',
        'Noto Sans'         => 'Noto Sans',
        'Noto Serif'        => 'Noto Serif',
        'Work Sans'         => 'Work Sans',
        'Fira Sans'         => 'Fira Sans',
        'Quicksand'         => 'Quicksand',
        'Titillium Web'     => 'Titillium Web',
        'Arimo'             => 'Arimo',
        'Mulish'            => 'Mulish',
        'Heebo'             => 'Heebo',
        'Karla'             => 'Karla',
        'Inter'             => 'Inter',
        'Manrope'           => 'Manrope',
        'DM Sans'           => 'DM Sans',
        'Barlow'            => 'Barlow',
        'Josefin Sans'      => 'Josefin Sans',
        'Nunito Sans'       => 'Nunito Sans',
    );
}

/**
 * Check if WooCommerce is active.
 *
 * @return bool
 */
function aqualuxe_is_woocommerce_active() {
    return class_exists('WooCommerce');
}

/**
 * Check if top bar is enabled.
 *
 * @return bool
 */
function aqualuxe_is_top_bar_enabled() {
    return get_theme_mod('aqualuxe_enable_top_bar', true);
}

/**
 * Check if footer payment icons are enabled.
 *
 * @return bool
 */
function aqualuxe_is_footer_payment_enabled() {
    return get_theme_mod('aqualuxe_enable_footer_payment', true);
}

/**
 * Check if footer newsletter is enabled.
 *
 * @return bool
 */
function aqualuxe_is_footer_newsletter_enabled() {
    return get_theme_mod('aqualuxe_enable_footer_newsletter', true);
}

/**
 * Check if blog excerpt is enabled.
 *
 * @return bool
 */
function aqualuxe_is_blog_excerpt_enabled() {
    return get_theme_mod('aqualuxe_blog_excerpt', true);
}

/**
 * Check if custom color scheme is selected.
 *
 * @return bool
 */
function aqualuxe_is_custom_color_scheme() {
    return get_theme_mod('aqualuxe_color_scheme', 'default') === 'custom';
}

/**
 * Check if social sharing is enabled.
 *
 * @return bool
 */
function aqualuxe_is_social_sharing_enabled() {
    return get_theme_mod('aqualuxe_enable_social_sharing', true);
}

/**
 * Multi select control class.
 */
if (class_exists('WP_Customize_Control')) {
    class AquaLuxe_Customize_Multi_Select_Control extends WP_Customize_Control {
        public $type = 'multi-select';

        public function render_content() {
            if (empty($this->choices)) {
                return;
            }
            ?>
            <label>
                <?php if (!empty($this->label)) : ?>
                    <span class="customize-control-title"><?php echo esc_html($this->label); ?></span>
                <?php endif; ?>

                <?php if (!empty($this->description)) : ?>
                    <span class="description customize-control-description"><?php echo esc_html($this->description); ?></span>
                <?php endif; ?>

                <select <?php $this->link(); ?> multiple="multiple" style="height: 150px;">
                    <?php
                    foreach ($this->choices as $value => $label) {
                        $selected = in_array($value, (array) $this->value()) ? 'selected="selected"' : '';
                        echo '<option value="' . esc_attr($value) . '" ' . $selected . '>' . esc_html($label) . '</option>';
                    }
                    ?>
                </select>
            </label>
            <?php
        }
    }
}

// Remove the duplicate function declaration
// The aqualuxe_sanitize_checkbox function is already defined in inc/helpers/sanitize.php