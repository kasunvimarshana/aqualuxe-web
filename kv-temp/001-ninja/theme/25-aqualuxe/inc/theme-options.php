<?php
/**
 * Theme options for AquaLuxe theme
 *
 * @package AquaLuxe
 */

/**
 * Initialize theme options
 */
function aqualuxe_theme_options_init() {
    // Register theme options settings
    register_setting(
        'aqualuxe_theme_options',
        'aqualuxe_theme_options',
        'aqualuxe_theme_options_validate'
    );

    // Add theme options page
    add_action('admin_menu', 'aqualuxe_theme_options_add_page');
}
add_action('init', 'aqualuxe_theme_options_init');

/**
 * Add theme options page to admin menu
 */
function aqualuxe_theme_options_add_page() {
    add_theme_page(
        __('Theme Options', 'aqualuxe'),
        __('Theme Options', 'aqualuxe'),
        'edit_theme_options',
        'aqualuxe-theme-options',
        'aqualuxe_theme_options_render_page'
    );
}

/**
 * Get default theme options
 *
 * @return array Default theme options
 */
function aqualuxe_get_default_theme_options() {
    $defaults = array(
        // General
        'logo_width' => '200',
        'logo_height' => '80',
        'favicon' => '',
        'preloader' => true,
        'back_to_top' => true,
        'breadcrumbs' => true,
        
        // Layout
        'container_width' => '1280',
        'sidebar_position' => 'right',
        'blog_layout' => 'grid',
        'blog_columns' => '3',
        'blog_posts_per_page' => '9',
        'related_posts' => true,
        'related_posts_count' => '3',
        
        // Typography
        'body_font' => 'Inter, sans-serif',
        'heading_font' => 'Montserrat, sans-serif',
        'body_font_size' => '16',
        'heading_font_weight' => '600',
        'body_font_weight' => '400',
        'line_height' => '1.6',
        
        // Colors
        'primary_color' => '#0077b6',
        'secondary_color' => '#00b4d8',
        'accent_color' => '#90e0ef',
        'text_color' => '#333333',
        'heading_color' => '#222222',
        'link_color' => '#0077b6',
        'link_hover_color' => '#00b4d8',
        'button_bg_color' => '#0077b6',
        'button_text_color' => '#ffffff',
        'button_hover_bg_color' => '#00b4d8',
        'button_hover_text_color' => '#ffffff',
        
        // Header
        'header_layout' => 'standard',
        'sticky_header' => true,
        'transparent_header' => false,
        'header_top_bar' => true,
        'top_bar_content' => __('Welcome to AquaLuxe - Bringing elegance to aquatic life – globally.', 'aqualuxe'),
        'header_search' => true,
        'header_cart' => true,
        'header_account' => true,
        'header_wishlist' => true,
        'header_social' => true,
        
        // Footer
        'footer_layout' => '4-columns',
        'footer_logo' => '',
        'copyright_text' => sprintf(__('© %s AquaLuxe. All rights reserved.', 'aqualuxe'), date('Y')),
        'footer_payment_icons' => true,
        'footer_newsletter' => true,
        'footer_social' => true,
        
        // WooCommerce
        'products_per_page' => '12',
        'product_columns' => '4',
        'related_products_count' => '4',
        'related_products_columns' => '4',
        'quick_view' => true,
        'wishlist' => true,
        'product_zoom' => true,
        'product_lightbox' => true,
        'product_slider' => true,
        'ajax_add_to_cart' => true,
        'cart_fragments' => true,
        'product_categories' => true,
        'product_ratings' => true,
        'product_price' => true,
        'multi_currency' => true,
        'free_shipping_threshold' => '100',
        'default_shipping_info' => '',
        'default_return_policy' => '',
        'default_warranty_info' => '',
        'default_size_guide' => '',
        'default_estimated_delivery' => '3-5 business days',
        'gift_wrapping_enabled' => 'yes',
        'gift_wrapping_price' => '5',
        
        // Social Media
        'facebook_url' => '',
        'twitter_url' => '',
        'instagram_url' => '',
        'youtube_url' => '',
        'pinterest_url' => '',
        'linkedin_url' => '',
        'twitter_username' => '',
        'social_sharing' => true,
        
        // Performance
        'lazy_loading' => true,
        'minification' => true,
        'preloading' => true,
        'prefetching' => true,
        'critical_css' => true,
        'responsive_images' => true,
        'webp' => true,
        'defer_js' => true,
        'async_css' => true,
        
        // Advanced
        'custom_css' => '',
        'custom_js' => '',
        'google_analytics' => '',
        'facebook_pixel' => '',
        'header_scripts' => '',
        'footer_scripts' => '',
        'maintenance_mode' => false,
        'maintenance_message' => __('We are currently undergoing scheduled maintenance. Please check back soon.', 'aqualuxe'),
    );
    
    return apply_filters('aqualuxe_default_theme_options', $defaults);
}

/**
 * Get theme options
 *
 * @return array Theme options
 */
function aqualuxe_get_theme_options() {
    $defaults = aqualuxe_get_default_theme_options();
    $options = get_option('aqualuxe_theme_options', $defaults);
    
    return wp_parse_args($options, $defaults);
}

/**
 * Validate theme options
 *
 * @param array $input Theme options input
 * @return array Validated theme options
 */
function aqualuxe_theme_options_validate($input) {
    $output = array();
    $defaults = aqualuxe_get_default_theme_options();
    
    // General
    $output['logo_width'] = absint($input['logo_width']);
    $output['logo_height'] = absint($input['logo_height']);
    $output['favicon'] = esc_url_raw($input['favicon']);
    $output['preloader'] = isset($input['preloader']) ? (bool) $input['preloader'] : false;
    $output['back_to_top'] = isset($input['back_to_top']) ? (bool) $input['back_to_top'] : false;
    $output['breadcrumbs'] = isset($input['breadcrumbs']) ? (bool) $input['breadcrumbs'] : false;
    
    // Layout
    $output['container_width'] = absint($input['container_width']);
    $output['sidebar_position'] = sanitize_text_field($input['sidebar_position']);
    $output['blog_layout'] = sanitize_text_field($input['blog_layout']);
    $output['blog_columns'] = absint($input['blog_columns']);
    $output['blog_posts_per_page'] = absint($input['blog_posts_per_page']);
    $output['related_posts'] = isset($input['related_posts']) ? (bool) $input['related_posts'] : false;
    $output['related_posts_count'] = absint($input['related_posts_count']);
    
    // Typography
    $output['body_font'] = sanitize_text_field($input['body_font']);
    $output['heading_font'] = sanitize_text_field($input['heading_font']);
    $output['body_font_size'] = absint($input['body_font_size']);
    $output['heading_font_weight'] = sanitize_text_field($input['heading_font_weight']);
    $output['body_font_weight'] = sanitize_text_field($input['body_font_weight']);
    $output['line_height'] = sanitize_text_field($input['line_height']);
    
    // Colors
    $output['primary_color'] = sanitize_hex_color($input['primary_color']);
    $output['secondary_color'] = sanitize_hex_color($input['secondary_color']);
    $output['accent_color'] = sanitize_hex_color($input['accent_color']);
    $output['text_color'] = sanitize_hex_color($input['text_color']);
    $output['heading_color'] = sanitize_hex_color($input['heading_color']);
    $output['link_color'] = sanitize_hex_color($input['link_color']);
    $output['link_hover_color'] = sanitize_hex_color($input['link_hover_color']);
    $output['button_bg_color'] = sanitize_hex_color($input['button_bg_color']);
    $output['button_text_color'] = sanitize_hex_color($input['button_text_color']);
    $output['button_hover_bg_color'] = sanitize_hex_color($input['button_hover_bg_color']);
    $output['button_hover_text_color'] = sanitize_hex_color($input['button_hover_text_color']);
    
    // Header
    $output['header_layout'] = sanitize_text_field($input['header_layout']);
    $output['sticky_header'] = isset($input['sticky_header']) ? (bool) $input['sticky_header'] : false;
    $output['transparent_header'] = isset($input['transparent_header']) ? (bool) $input['transparent_header'] : false;
    $output['header_top_bar'] = isset($input['header_top_bar']) ? (bool) $input['header_top_bar'] : false;
    $output['top_bar_content'] = wp_kses_post($input['top_bar_content']);
    $output['header_search'] = isset($input['header_search']) ? (bool) $input['header_search'] : false;
    $output['header_cart'] = isset($input['header_cart']) ? (bool) $input['header_cart'] : false;
    $output['header_account'] = isset($input['header_account']) ? (bool) $input['header_account'] : false;
    $output['header_wishlist'] = isset($input['header_wishlist']) ? (bool) $input['header_wishlist'] : false;
    $output['header_social'] = isset($input['header_social']) ? (bool) $input['header_social'] : false;
    
    // Footer
    $output['footer_layout'] = sanitize_text_field($input['footer_layout']);
    $output['footer_logo'] = esc_url_raw($input['footer_logo']);
    $output['copyright_text'] = wp_kses_post($input['copyright_text']);
    $output['footer_payment_icons'] = isset($input['footer_payment_icons']) ? (bool) $input['footer_payment_icons'] : false;
    $output['footer_newsletter'] = isset($input['footer_newsletter']) ? (bool) $input['footer_newsletter'] : false;
    $output['footer_social'] = isset($input['footer_social']) ? (bool) $input['footer_social'] : false;
    
    // WooCommerce
    $output['products_per_page'] = absint($input['products_per_page']);
    $output['product_columns'] = absint($input['product_columns']);
    $output['related_products_count'] = absint($input['related_products_count']);
    $output['related_products_columns'] = absint($input['related_products_columns']);
    $output['quick_view'] = isset($input['quick_view']) ? (bool) $input['quick_view'] : false;
    $output['wishlist'] = isset($input['wishlist']) ? (bool) $input['wishlist'] : false;
    $output['product_zoom'] = isset($input['product_zoom']) ? (bool) $input['product_zoom'] : false;
    $output['product_lightbox'] = isset($input['product_lightbox']) ? (bool) $input['product_lightbox'] : false;
    $output['product_slider'] = isset($input['product_slider']) ? (bool) $input['product_slider'] : false;
    $output['ajax_add_to_cart'] = isset($input['ajax_add_to_cart']) ? (bool) $input['ajax_add_to_cart'] : false;
    $output['cart_fragments'] = isset($input['cart_fragments']) ? (bool) $input['cart_fragments'] : false;
    $output['product_categories'] = isset($input['product_categories']) ? (bool) $input['product_categories'] : false;
    $output['product_ratings'] = isset($input['product_ratings']) ? (bool) $input['product_ratings'] : false;
    $output['product_price'] = isset($input['product_price']) ? (bool) $input['product_price'] : false;
    $output['multi_currency'] = isset($input['multi_currency']) ? (bool) $input['multi_currency'] : false;
    $output['free_shipping_threshold'] = sanitize_text_field($input['free_shipping_threshold']);
    $output['default_shipping_info'] = wp_kses_post($input['default_shipping_info']);
    $output['default_return_policy'] = wp_kses_post($input['default_return_policy']);
    $output['default_warranty_info'] = wp_kses_post($input['default_warranty_info']);
    $output['default_size_guide'] = wp_kses_post($input['default_size_guide']);
    $output['default_estimated_delivery'] = sanitize_text_field($input['default_estimated_delivery']);
    $output['gift_wrapping_enabled'] = sanitize_text_field($input['gift_wrapping_enabled']);
    $output['gift_wrapping_price'] = sanitize_text_field($input['gift_wrapping_price']);
    
    // Social Media
    $output['facebook_url'] = esc_url_raw($input['facebook_url']);
    $output['twitter_url'] = esc_url_raw($input['twitter_url']);
    $output['instagram_url'] = esc_url_raw($input['instagram_url']);
    $output['youtube_url'] = esc_url_raw($input['youtube_url']);
    $output['pinterest_url'] = esc_url_raw($input['pinterest_url']);
    $output['linkedin_url'] = esc_url_raw($input['linkedin_url']);
    $output['twitter_username'] = sanitize_text_field($input['twitter_username']);
    $output['social_sharing'] = isset($input['social_sharing']) ? (bool) $input['social_sharing'] : false;
    
    // Performance
    $output['lazy_loading'] = isset($input['lazy_loading']) ? (bool) $input['lazy_loading'] : false;
    $output['minification'] = isset($input['minification']) ? (bool) $input['minification'] : false;
    $output['preloading'] = isset($input['preloading']) ? (bool) $input['preloading'] : false;
    $output['prefetching'] = isset($input['prefetching']) ? (bool) $input['prefetching'] : false;
    $output['critical_css'] = isset($input['critical_css']) ? (bool) $input['critical_css'] : false;
    $output['responsive_images'] = isset($input['responsive_images']) ? (bool) $input['responsive_images'] : false;
    $output['webp'] = isset($input['webp']) ? (bool) $input['webp'] : false;
    $output['defer_js'] = isset($input['defer_js']) ? (bool) $input['defer_js'] : false;
    $output['async_css'] = isset($input['async_css']) ? (bool) $input['async_css'] : false;
    
    // Advanced
    $output['custom_css'] = wp_strip_all_tags($input['custom_css']);
    $output['custom_js'] = wp_strip_all_tags($input['custom_js']);
    $output['google_analytics'] = wp_strip_all_tags($input['google_analytics']);
    $output['facebook_pixel'] = wp_strip_all_tags($input['facebook_pixel']);
    $output['header_scripts'] = wp_strip_all_tags($input['header_scripts']);
    $output['footer_scripts'] = wp_strip_all_tags($input['footer_scripts']);
    $output['maintenance_mode'] = isset($input['maintenance_mode']) ? (bool) $input['maintenance_mode'] : false;
    $output['maintenance_message'] = wp_kses_post($input['maintenance_message']);
    
    return apply_filters('aqualuxe_theme_options_validate', $output, $input, $defaults);
}

/**
 * Render theme options page
 */
function aqualuxe_theme_options_render_page() {
    if (!current_user_can('edit_theme_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.', 'aqualuxe'));
    }
    
    $options = aqualuxe_get_theme_options();
    $active_tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'general';
    ?>
    <div class="wrap">
        <h1><?php esc_html_e('AquaLuxe Theme Options', 'aqualuxe'); ?></h1>
        
        <h2 class="nav-tab-wrapper">
            <a href="?page=aqualuxe-theme-options&tab=general" class="nav-tab <?php echo $active_tab === 'general' ? 'nav-tab-active' : ''; ?>"><?php esc_html_e('General', 'aqualuxe'); ?></a>
            <a href="?page=aqualuxe-theme-options&tab=layout" class="nav-tab <?php echo $active_tab === 'layout' ? 'nav-tab-active' : ''; ?>"><?php esc_html_e('Layout', 'aqualuxe'); ?></a>
            <a href="?page=aqualuxe-theme-options&tab=typography" class="nav-tab <?php echo $active_tab === 'typography' ? 'nav-tab-active' : ''; ?>"><?php esc_html_e('Typography', 'aqualuxe'); ?></a>
            <a href="?page=aqualuxe-theme-options&tab=colors" class="nav-tab <?php echo $active_tab === 'colors' ? 'nav-tab-active' : ''; ?>"><?php esc_html_e('Colors', 'aqualuxe'); ?></a>
            <a href="?page=aqualuxe-theme-options&tab=header" class="nav-tab <?php echo $active_tab === 'header' ? 'nav-tab-active' : ''; ?>"><?php esc_html_e('Header', 'aqualuxe'); ?></a>
            <a href="?page=aqualuxe-theme-options&tab=footer" class="nav-tab <?php echo $active_tab === 'footer' ? 'nav-tab-active' : ''; ?>"><?php esc_html_e('Footer', 'aqualuxe'); ?></a>
            <a href="?page=aqualuxe-theme-options&tab=woocommerce" class="nav-tab <?php echo $active_tab === 'woocommerce' ? 'nav-tab-active' : ''; ?>"><?php esc_html_e('WooCommerce', 'aqualuxe'); ?></a>
            <a href="?page=aqualuxe-theme-options&tab=social" class="nav-tab <?php echo $active_tab === 'social' ? 'nav-tab-active' : ''; ?>"><?php esc_html_e('Social Media', 'aqualuxe'); ?></a>
            <a href="?page=aqualuxe-theme-options&tab=performance" class="nav-tab <?php echo $active_tab === 'performance' ? 'nav-tab-active' : ''; ?>"><?php esc_html_e('Performance', 'aqualuxe'); ?></a>
            <a href="?page=aqualuxe-theme-options&tab=advanced" class="nav-tab <?php echo $active_tab === 'advanced' ? 'nav-tab-active' : ''; ?>"><?php esc_html_e('Advanced', 'aqualuxe'); ?></a>
        </h2>
        
        <form method="post" action="options.php">
            <?php settings_fields('aqualuxe_theme_options'); ?>
            
            <?php if ($active_tab === 'general') : ?>
                <h2><?php esc_html_e('General Settings', 'aqualuxe'); ?></h2>
                <table class="form-table">
                    <tr>
                        <th scope="row"><?php esc_html_e('Logo Width (px)', 'aqualuxe'); ?></th>
                        <td>
                            <input type="number" name="aqualuxe_theme_options[logo_width]" value="<?php echo esc_attr($options['logo_width']); ?>" min="1" max="1000" />
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Logo Height (px)', 'aqualuxe'); ?></th>
                        <td>
                            <input type="number" name="aqualuxe_theme_options[logo_height]" value="<?php echo esc_attr($options['logo_height']); ?>" min="1" max="1000" />
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Favicon', 'aqualuxe'); ?></th>
                        <td>
                            <input type="text" name="aqualuxe_theme_options[favicon]" value="<?php echo esc_url($options['favicon']); ?>" class="regular-text" />
                            <button type="button" class="button" id="favicon-upload-button"><?php esc_html_e('Upload', 'aqualuxe'); ?></button>
                            <p class="description"><?php esc_html_e('Upload a favicon for your site (recommended size: 32x32px).', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Enable Preloader', 'aqualuxe'); ?></th>
                        <td>
                            <input type="checkbox" name="aqualuxe_theme_options[preloader]" <?php checked($options['preloader']); ?> />
                            <p class="description"><?php esc_html_e('Show a preloader animation while the page is loading.', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Enable Back to Top Button', 'aqualuxe'); ?></th>
                        <td>
                            <input type="checkbox" name="aqualuxe_theme_options[back_to_top]" <?php checked($options['back_to_top']); ?> />
                            <p class="description"><?php esc_html_e('Show a button to scroll back to the top of the page.', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Enable Breadcrumbs', 'aqualuxe'); ?></th>
                        <td>
                            <input type="checkbox" name="aqualuxe_theme_options[breadcrumbs]" <?php checked($options['breadcrumbs']); ?> />
                            <p class="description"><?php esc_html_e('Show breadcrumb navigation on pages.', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                </table>
            <?php elseif ($active_tab === 'layout') : ?>
                <h2><?php esc_html_e('Layout Settings', 'aqualuxe'); ?></h2>
                <table class="form-table">
                    <tr>
                        <th scope="row"><?php esc_html_e('Container Width (px)', 'aqualuxe'); ?></th>
                        <td>
                            <input type="number" name="aqualuxe_theme_options[container_width]" value="<?php echo esc_attr($options['container_width']); ?>" min="960" max="1920" step="10" />
                            <p class="description"><?php esc_html_e('Set the maximum width of the content container.', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Sidebar Position', 'aqualuxe'); ?></th>
                        <td>
                            <select name="aqualuxe_theme_options[sidebar_position]">
                                <option value="right" <?php selected($options['sidebar_position'], 'right'); ?>><?php esc_html_e('Right', 'aqualuxe'); ?></option>
                                <option value="left" <?php selected($options['sidebar_position'], 'left'); ?>><?php esc_html_e('Left', 'aqualuxe'); ?></option>
                                <option value="none" <?php selected($options['sidebar_position'], 'none'); ?>><?php esc_html_e('No Sidebar', 'aqualuxe'); ?></option>
                            </select>
                            <p class="description"><?php esc_html_e('Choose the default sidebar position for pages and posts.', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Blog Layout', 'aqualuxe'); ?></th>
                        <td>
                            <select name="aqualuxe_theme_options[blog_layout]">
                                <option value="grid" <?php selected($options['blog_layout'], 'grid'); ?>><?php esc_html_e('Grid', 'aqualuxe'); ?></option>
                                <option value="list" <?php selected($options['blog_layout'], 'list'); ?>><?php esc_html_e('List', 'aqualuxe'); ?></option>
                                <option value="masonry" <?php selected($options['blog_layout'], 'masonry'); ?>><?php esc_html_e('Masonry', 'aqualuxe'); ?></option>
                                <option value="standard" <?php selected($options['blog_layout'], 'standard'); ?>><?php esc_html_e('Standard', 'aqualuxe'); ?></option>
                            </select>
                            <p class="description"><?php esc_html_e('Choose the layout for blog listings.', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Blog Columns', 'aqualuxe'); ?></th>
                        <td>
                            <select name="aqualuxe_theme_options[blog_columns]">
                                <option value="2" <?php selected($options['blog_columns'], '2'); ?>><?php esc_html_e('2 Columns', 'aqualuxe'); ?></option>
                                <option value="3" <?php selected($options['blog_columns'], '3'); ?>><?php esc_html_e('3 Columns', 'aqualuxe'); ?></option>
                                <option value="4" <?php selected($options['blog_columns'], '4'); ?>><?php esc_html_e('4 Columns', 'aqualuxe'); ?></option>
                            </select>
                            <p class="description"><?php esc_html_e('Number of columns for grid or masonry layout.', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Blog Posts Per Page', 'aqualuxe'); ?></th>
                        <td>
                            <input type="number" name="aqualuxe_theme_options[blog_posts_per_page]" value="<?php echo esc_attr($options['blog_posts_per_page']); ?>" min="1" max="50" />
                            <p class="description"><?php esc_html_e('Number of posts to display per page.', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Show Related Posts', 'aqualuxe'); ?></th>
                        <td>
                            <input type="checkbox" name="aqualuxe_theme_options[related_posts]" <?php checked($options['related_posts']); ?> />
                            <p class="description"><?php esc_html_e('Display related posts on single posts.', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Related Posts Count', 'aqualuxe'); ?></th>
                        <td>
                            <input type="number" name="aqualuxe_theme_options[related_posts_count]" value="<?php echo esc_attr($options['related_posts_count']); ?>" min="1" max="12" />
                            <p class="description"><?php esc_html_e('Number of related posts to display.', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                </table>
            <?php elseif ($active_tab === 'typography') : ?>
                <h2><?php esc_html_e('Typography Settings', 'aqualuxe'); ?></h2>
                <table class="form-table">
                    <tr>
                        <th scope="row"><?php esc_html_e('Body Font', 'aqualuxe'); ?></th>
                        <td>
                            <select name="aqualuxe_theme_options[body_font]">
                                <option value="Inter, sans-serif" <?php selected($options['body_font'], 'Inter, sans-serif'); ?>><?php esc_html_e('Inter (Default)', 'aqualuxe'); ?></option>
                                <option value="Roboto, sans-serif" <?php selected($options['body_font'], 'Roboto, sans-serif'); ?>><?php esc_html_e('Roboto', 'aqualuxe'); ?></option>
                                <option value="Open Sans, sans-serif" <?php selected($options['body_font'], 'Open Sans, sans-serif'); ?>><?php esc_html_e('Open Sans', 'aqualuxe'); ?></option>
                                <option value="Lato, sans-serif" <?php selected($options['body_font'], 'Lato, sans-serif'); ?>><?php esc_html_e('Lato', 'aqualuxe'); ?></option>
                                <option value="Montserrat, sans-serif" <?php selected($options['body_font'], 'Montserrat, sans-serif'); ?>><?php esc_html_e('Montserrat', 'aqualuxe'); ?></option>
                                <option value="Poppins, sans-serif" <?php selected($options['body_font'], 'Poppins, sans-serif'); ?>><?php esc_html_e('Poppins', 'aqualuxe'); ?></option>
                                <option value="Raleway, sans-serif" <?php selected($options['body_font'], 'Raleway, sans-serif'); ?>><?php esc_html_e('Raleway', 'aqualuxe'); ?></option>
                                <option value="Nunito, sans-serif" <?php selected($options['body_font'], 'Nunito, sans-serif'); ?>><?php esc_html_e('Nunito', 'aqualuxe'); ?></option>
                                <option value="Playfair Display, serif" <?php selected($options['body_font'], 'Playfair Display, serif'); ?>><?php esc_html_e('Playfair Display', 'aqualuxe'); ?></option>
                                <option value="Merriweather, serif" <?php selected($options['body_font'], 'Merriweather, serif'); ?>><?php esc_html_e('Merriweather', 'aqualuxe'); ?></option>
                            </select>
                            <p class="description"><?php esc_html_e('Font family for body text.', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Heading Font', 'aqualuxe'); ?></th>
                        <td>
                            <select name="aqualuxe_theme_options[heading_font]">
                                <option value="Montserrat, sans-serif" <?php selected($options['heading_font'], 'Montserrat, sans-serif'); ?>><?php esc_html_e('Montserrat (Default)', 'aqualuxe'); ?></option>
                                <option value="Inter, sans-serif" <?php selected($options['heading_font'], 'Inter, sans-serif'); ?>><?php esc_html_e('Inter', 'aqualuxe'); ?></option>
                                <option value="Roboto, sans-serif" <?php selected($options['heading_font'], 'Roboto, sans-serif'); ?>><?php esc_html_e('Roboto', 'aqualuxe'); ?></option>
                                <option value="Open Sans, sans-serif" <?php selected($options['heading_font'], 'Open Sans, sans-serif'); ?>><?php esc_html_e('Open Sans', 'aqualuxe'); ?></option>
                                <option value="Lato, sans-serif" <?php selected($options['heading_font'], 'Lato, sans-serif'); ?>><?php esc_html_e('Lato', 'aqualuxe'); ?></option>
                                <option value="Poppins, sans-serif" <?php selected($options['heading_font'], 'Poppins, sans-serif'); ?>><?php esc_html_e('Poppins', 'aqualuxe'); ?></option>
                                <option value="Raleway, sans-serif" <?php selected($options['heading_font'], 'Raleway, sans-serif'); ?>><?php esc_html_e('Raleway', 'aqualuxe'); ?></option>
                                <option value="Nunito, sans-serif" <?php selected($options['heading_font'], 'Nunito, sans-serif'); ?>><?php esc_html_e('Nunito', 'aqualuxe'); ?></option>
                                <option value="Playfair Display, serif" <?php selected($options['heading_font'], 'Playfair Display, serif'); ?>><?php esc_html_e('Playfair Display', 'aqualuxe'); ?></option>
                                <option value="Merriweather, serif" <?php selected($options['heading_font'], 'Merriweather, serif'); ?>><?php esc_html_e('Merriweather', 'aqualuxe'); ?></option>
                            </select>
                            <p class="description"><?php esc_html_e('Font family for headings.', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Body Font Size (px)', 'aqualuxe'); ?></th>
                        <td>
                            <input type="number" name="aqualuxe_theme_options[body_font_size]" value="<?php echo esc_attr($options['body_font_size']); ?>" min="12" max="24" />
                            <p class="description"><?php esc_html_e('Base font size for body text.', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Body Font Weight', 'aqualuxe'); ?></th>
                        <td>
                            <select name="aqualuxe_theme_options[body_font_weight]">
                                <option value="300" <?php selected($options['body_font_weight'], '300'); ?>><?php esc_html_e('Light (300)', 'aqualuxe'); ?></option>
                                <option value="400" <?php selected($options['body_font_weight'], '400'); ?>><?php esc_html_e('Regular (400)', 'aqualuxe'); ?></option>
                                <option value="500" <?php selected($options['body_font_weight'], '500'); ?>><?php esc_html_e('Medium (500)', 'aqualuxe'); ?></option>
                                <option value="600" <?php selected($options['body_font_weight'], '600'); ?>><?php esc_html_e('Semi-Bold (600)', 'aqualuxe'); ?></option>
                                <option value="700" <?php selected($options['body_font_weight'], '700'); ?>><?php esc_html_e('Bold (700)', 'aqualuxe'); ?></option>
                            </select>
                            <p class="description"><?php esc_html_e('Font weight for body text.', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Heading Font Weight', 'aqualuxe'); ?></th>
                        <td>
                            <select name="aqualuxe_theme_options[heading_font_weight]">
                                <option value="400" <?php selected($options['heading_font_weight'], '400'); ?>><?php esc_html_e('Regular (400)', 'aqualuxe'); ?></option>
                                <option value="500" <?php selected($options['heading_font_weight'], '500'); ?>><?php esc_html_e('Medium (500)', 'aqualuxe'); ?></option>
                                <option value="600" <?php selected($options['heading_font_weight'], '600'); ?>><?php esc_html_e('Semi-Bold (600)', 'aqualuxe'); ?></option>
                                <option value="700" <?php selected($options['heading_font_weight'], '700'); ?>><?php esc_html_e('Bold (700)', 'aqualuxe'); ?></option>
                                <option value="800" <?php selected($options['heading_font_weight'], '800'); ?>><?php esc_html_e('Extra-Bold (800)', 'aqualuxe'); ?></option>
                                <option value="900" <?php selected($options['heading_font_weight'], '900'); ?>><?php esc_html_e('Black (900)', 'aqualuxe'); ?></option>
                            </select>
                            <p class="description"><?php esc_html_e('Font weight for headings.', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Line Height', 'aqualuxe'); ?></th>
                        <td>
                            <select name="aqualuxe_theme_options[line_height]">
                                <option value="1.2" <?php selected($options['line_height'], '1.2'); ?>><?php esc_html_e('Tight (1.2)', 'aqualuxe'); ?></option>
                                <option value="1.4" <?php selected($options['line_height'], '1.4'); ?>><?php esc_html_e('Compact (1.4)', 'aqualuxe'); ?></option>
                                <option value="1.6" <?php selected($options['line_height'], '1.6'); ?>><?php esc_html_e('Normal (1.6)', 'aqualuxe'); ?></option>
                                <option value="1.8" <?php selected($options['line_height'], '1.8'); ?>><?php esc_html_e('Relaxed (1.8)', 'aqualuxe'); ?></option>
                                <option value="2.0" <?php selected($options['line_height'], '2.0'); ?>><?php esc_html_e('Loose (2.0)', 'aqualuxe'); ?></option>
                            </select>
                            <p class="description"><?php esc_html_e('Base line height for body text.', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                </table>
            <?php elseif ($active_tab === 'colors') : ?>
                <h2><?php esc_html_e('Color Settings', 'aqualuxe'); ?></h2>
                <table class="form-table">
                    <tr>
                        <th scope="row"><?php esc_html_e('Primary Color', 'aqualuxe'); ?></th>
                        <td>
                            <input type="text" name="aqualuxe_theme_options[primary_color]" value="<?php echo esc_attr($options['primary_color']); ?>" class="color-picker" />
                            <p class="description"><?php esc_html_e('Main brand color used for buttons, links, and accents.', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Secondary Color', 'aqualuxe'); ?></th>
                        <td>
                            <input type="text" name="aqualuxe_theme_options[secondary_color]" value="<?php echo esc_attr($options['secondary_color']); ?>" class="color-picker" />
                            <p class="description"><?php esc_html_e('Secondary brand color for contrast and variety.', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Accent Color', 'aqualuxe'); ?></th>
                        <td>
                            <input type="text" name="aqualuxe_theme_options[accent_color]" value="<?php echo esc_attr($options['accent_color']); ?>" class="color-picker" />
                            <p class="description"><?php esc_html_e('Used for highlights and subtle accents.', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Text Color', 'aqualuxe'); ?></th>
                        <td>
                            <input type="text" name="aqualuxe_theme_options[text_color]" value="<?php echo esc_attr($options['text_color']); ?>" class="color-picker" />
                            <p class="description"><?php esc_html_e('Main text color.', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Heading Color', 'aqualuxe'); ?></th>
                        <td>
                            <input type="text" name="aqualuxe_theme_options[heading_color]" value="<?php echo esc_attr($options['heading_color']); ?>" class="color-picker" />
                            <p class="description"><?php esc_html_e('Color for headings.', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Link Color', 'aqualuxe'); ?></th>
                        <td>
                            <input type="text" name="aqualuxe_theme_options[link_color]" value="<?php echo esc_attr($options['link_color']); ?>" class="color-picker" />
                            <p class="description"><?php esc_html_e('Color for links.', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Link Hover Color', 'aqualuxe'); ?></th>
                        <td>
                            <input type="text" name="aqualuxe_theme_options[link_hover_color]" value="<?php echo esc_attr($options['link_hover_color']); ?>" class="color-picker" />
                            <p class="description"><?php esc_html_e('Color for links on hover.', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Button Background Color', 'aqualuxe'); ?></th>
                        <td>
                            <input type="text" name="aqualuxe_theme_options[button_bg_color]" value="<?php echo esc_attr($options['button_bg_color']); ?>" class="color-picker" />
                            <p class="description"><?php esc_html_e('Background color for buttons.', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Button Text Color', 'aqualuxe'); ?></th>
                        <td>
                            <input type="text" name="aqualuxe_theme_options[button_text_color]" value="<?php echo esc_attr($options['button_text_color']); ?>" class="color-picker" />
                            <p class="description"><?php esc_html_e('Text color for buttons.', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Button Hover Background Color', 'aqualuxe'); ?></th>
                        <td>
                            <input type="text" name="aqualuxe_theme_options[button_hover_bg_color]" value="<?php echo esc_attr($options['button_hover_bg_color']); ?>" class="color-picker" />
                            <p class="description"><?php esc_html_e('Background color for buttons on hover.', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Button Hover Text Color', 'aqualuxe'); ?></th>
                        <td>
                            <input type="text" name="aqualuxe_theme_options[button_hover_text_color]" value="<?php echo esc_attr($options['button_hover_text_color']); ?>" class="color-picker" />
                            <p class="description"><?php esc_html_e('Text color for buttons on hover.', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                </table>
            <?php elseif ($active_tab === 'header') : ?>
                <h2><?php esc_html_e('Header Settings', 'aqualuxe'); ?></h2>
                <table class="form-table">
                    <tr>
                        <th scope="row"><?php esc_html_e('Header Layout', 'aqualuxe'); ?></th>
                        <td>
                            <select name="aqualuxe_theme_options[header_layout]">
                                <option value="standard" <?php selected($options['header_layout'], 'standard'); ?>><?php esc_html_e('Standard', 'aqualuxe'); ?></option>
                                <option value="centered" <?php selected($options['header_layout'], 'centered'); ?>><?php esc_html_e('Centered', 'aqualuxe'); ?></option>
                                <option value="split" <?php selected($options['header_layout'], 'split'); ?>><?php esc_html_e('Split Menu', 'aqualuxe'); ?></option>
                                <option value="transparent" <?php selected($options['header_layout'], 'transparent'); ?>><?php esc_html_e('Transparent', 'aqualuxe'); ?></option>
                                <option value="minimal" <?php selected($options['header_layout'], 'minimal'); ?>><?php esc_html_e('Minimal', 'aqualuxe'); ?></option>
                            </select>
                            <p class="description"><?php esc_html_e('Choose the layout for your site header.', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Sticky Header', 'aqualuxe'); ?></th>
                        <td>
                            <input type="checkbox" name="aqualuxe_theme_options[sticky_header]" <?php checked($options['sticky_header']); ?> />
                            <p class="description"><?php esc_html_e('Keep the header visible when scrolling down.', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Transparent Header', 'aqualuxe'); ?></th>
                        <td>
                            <input type="checkbox" name="aqualuxe_theme_options[transparent_header]" <?php checked($options['transparent_header']); ?> />
                            <p class="description"><?php esc_html_e('Make the header background transparent on the homepage.', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Enable Top Bar', 'aqualuxe'); ?></th>
                        <td>
                            <input type="checkbox" name="aqualuxe_theme_options[header_top_bar]" <?php checked($options['header_top_bar']); ?> />
                            <p class="description"><?php esc_html_e('Show a top bar above the main header.', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Top Bar Content', 'aqualuxe'); ?></th>
                        <td>
                            <textarea name="aqualuxe_theme_options[top_bar_content]" rows="3" cols="50"><?php echo esc_textarea($options['top_bar_content']); ?></textarea>
                            <p class="description"><?php esc_html_e('Add text or HTML for the top bar.', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Show Search in Header', 'aqualuxe'); ?></th>
                        <td>
                            <input type="checkbox" name="aqualuxe_theme_options[header_search]" <?php checked($options['header_search']); ?> />
                            <p class="description"><?php esc_html_e('Display a search icon in the header.', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Show Cart in Header', 'aqualuxe'); ?></th>
                        <td>
                            <input type="checkbox" name="aqualuxe_theme_options[header_cart]" <?php checked($options['header_cart']); ?> />
                            <p class="description"><?php esc_html_e('Display a shopping cart icon in the header.', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Show Account in Header', 'aqualuxe'); ?></th>
                        <td>
                            <input type="checkbox" name="aqualuxe_theme_options[header_account]" <?php checked($options['header_account']); ?> />
                            <p class="description"><?php esc_html_e('Display a user account icon in the header.', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Show Wishlist in Header', 'aqualuxe'); ?></th>
                        <td>
                            <input type="checkbox" name="aqualuxe_theme_options[header_wishlist]" <?php checked($options['header_wishlist']); ?> />
                            <p class="description"><?php esc_html_e('Display a wishlist icon in the header.', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Show Social Icons in Header', 'aqualuxe'); ?></th>
                        <td>
                            <input type="checkbox" name="aqualuxe_theme_options[header_social]" <?php checked($options['header_social']); ?> />
                            <p class="description"><?php esc_html_e('Display social media icons in the header.', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                </table>
            <?php elseif ($active_tab === 'footer') : ?>
                <h2><?php esc_html_e('Footer Settings', 'aqualuxe'); ?></h2>
                <table class="form-table">
                    <tr>
                        <th scope="row"><?php esc_html_e('Footer Layout', 'aqualuxe'); ?></th>
                        <td>
                            <select name="aqualuxe_theme_options[footer_layout]">
                                <option value="1-column" <?php selected($options['footer_layout'], '1-column'); ?>><?php esc_html_e('1 Column', 'aqualuxe'); ?></option>
                                <option value="2-columns" <?php selected($options['footer_layout'], '2-columns'); ?>><?php esc_html_e('2 Columns', 'aqualuxe'); ?></option>
                                <option value="3-columns" <?php selected($options['footer_layout'], '3-columns'); ?>><?php esc_html_e('3 Columns', 'aqualuxe'); ?></option>
                                <option value="4-columns" <?php selected($options['footer_layout'], '4-columns'); ?>><?php esc_html_e('4 Columns', 'aqualuxe'); ?></option>
                                <option value="custom" <?php selected($options['footer_layout'], 'custom'); ?>><?php esc_html_e('Custom Layout', 'aqualuxe'); ?></option>
                            </select>
                            <p class="description"><?php esc_html_e('Choose the layout for footer widgets.', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Footer Logo', 'aqualuxe'); ?></th>
                        <td>
                            <input type="text" name="aqualuxe_theme_options[footer_logo]" value="<?php echo esc_url($options['footer_logo']); ?>" class="regular-text" />
                            <button type="button" class="button" id="footer-logo-upload-button"><?php esc_html_e('Upload', 'aqualuxe'); ?></button>
                            <p class="description"><?php esc_html_e('Upload a logo for the footer (optional).', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Copyright Text', 'aqualuxe'); ?></th>
                        <td>
                            <textarea name="aqualuxe_theme_options[copyright_text]" rows="3" cols="50"><?php echo esc_textarea($options['copyright_text']); ?></textarea>
                            <p class="description"><?php esc_html_e('Enter your copyright text. Use %s for the current year.', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Show Payment Icons', 'aqualuxe'); ?></th>
                        <td>
                            <input type="checkbox" name="aqualuxe_theme_options[footer_payment_icons]" <?php checked($options['footer_payment_icons']); ?> />
                            <p class="description"><?php esc_html_e('Display payment method icons in the footer.', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Show Newsletter Form', 'aqualuxe'); ?></th>
                        <td>
                            <input type="checkbox" name="aqualuxe_theme_options[footer_newsletter]" <?php checked($options['footer_newsletter']); ?> />
                            <p class="description"><?php esc_html_e('Display a newsletter signup form in the footer.', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Show Social Icons in Footer', 'aqualuxe'); ?></th>
                        <td>
                            <input type="checkbox" name="aqualuxe_theme_options[footer_social]" <?php checked($options['footer_social']); ?> />
                            <p class="description"><?php esc_html_e('Display social media icons in the footer.', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                </table>
            <?php elseif ($active_tab === 'woocommerce') : ?>
                <h2><?php esc_html_e('WooCommerce Settings', 'aqualuxe'); ?></h2>
                <table class="form-table">
                    <tr>
                        <th scope="row"><?php esc_html_e('Products Per Page', 'aqualuxe'); ?></th>
                        <td>
                            <input type="number" name="aqualuxe_theme_options[products_per_page]" value="<?php echo esc_attr($options['products_per_page']); ?>" min="1" max="100" />
                            <p class="description"><?php esc_html_e('Number of products to display per page.', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Product Columns', 'aqualuxe'); ?></th>
                        <td>
                            <select name="aqualuxe_theme_options[product_columns]">
                                <option value="2" <?php selected($options['product_columns'], '2'); ?>><?php esc_html_e('2 Columns', 'aqualuxe'); ?></option>
                                <option value="3" <?php selected($options['product_columns'], '3'); ?>><?php esc_html_e('3 Columns', 'aqualuxe'); ?></option>
                                <option value="4" <?php selected($options['product_columns'], '4'); ?>><?php esc_html_e('4 Columns', 'aqualuxe'); ?></option>
                                <option value="5" <?php selected($options['product_columns'], '5'); ?>><?php esc_html_e('5 Columns', 'aqualuxe'); ?></option>
                                <option value="6" <?php selected($options['product_columns'], '6'); ?>><?php esc_html_e('6 Columns', 'aqualuxe'); ?></option>
                            </select>
                            <p class="description"><?php esc_html_e('Number of columns for product grid.', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Related Products Count', 'aqualuxe'); ?></th>
                        <td>
                            <input type="number" name="aqualuxe_theme_options[related_products_count]" value="<?php echo esc_attr($options['related_products_count']); ?>" min="1" max="12" />
                            <p class="description"><?php esc_html_e('Number of related products to display.', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Related Products Columns', 'aqualuxe'); ?></th>
                        <td>
                            <select name="aqualuxe_theme_options[related_products_columns]">
                                <option value="2" <?php selected($options['related_products_columns'], '2'); ?>><?php esc_html_e('2 Columns', 'aqualuxe'); ?></option>
                                <option value="3" <?php selected($options['related_products_columns'], '3'); ?>><?php esc_html_e('3 Columns', 'aqualuxe'); ?></option>
                                <option value="4" <?php selected($options['related_products_columns'], '4'); ?>><?php esc_html_e('4 Columns', 'aqualuxe'); ?></option>
                                <option value="5" <?php selected($options['related_products_columns'], '5'); ?>><?php esc_html_e('5 Columns', 'aqualuxe'); ?></option>
                            </select>
                            <p class="description"><?php esc_html_e('Number of columns for related products.', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Enable Quick View', 'aqualuxe'); ?></th>
                        <td>
                            <input type="checkbox" name="aqualuxe_theme_options[quick_view]" <?php checked($options['quick_view']); ?> />
                            <p class="description"><?php esc_html_e('Show quick view button on products.', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Enable Wishlist', 'aqualuxe'); ?></th>
                        <td>
                            <input type="checkbox" name="aqualuxe_theme_options[wishlist]" <?php checked($options['wishlist']); ?> />
                            <p class="description"><?php esc_html_e('Show wishlist button on products.', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Enable Product Zoom', 'aqualuxe'); ?></th>
                        <td>
                            <input type="checkbox" name="aqualuxe_theme_options[product_zoom]" <?php checked($options['product_zoom']); ?> />
                            <p class="description"><?php esc_html_e('Allow zooming product images on hover.', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Enable Product Gallery Lightbox', 'aqualuxe'); ?></th>
                        <td>
                            <input type="checkbox" name="aqualuxe_theme_options[product_lightbox]" <?php checked($options['product_lightbox']); ?> />
                            <p class="description"><?php esc_html_e('Open product images in lightbox.', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Enable Product Gallery Slider', 'aqualuxe'); ?></th>
                        <td>
                            <input type="checkbox" name="aqualuxe_theme_options[product_slider]" <?php checked($options['product_slider']); ?> />
                            <p class="description"><?php esc_html_e('Show product images in a slider.', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Enable Ajax Add to Cart', 'aqualuxe'); ?></th>
                        <td>
                            <input type="checkbox" name="aqualuxe_theme_options[ajax_add_to_cart]" <?php checked($options['ajax_add_to_cart']); ?> />
                            <p class="description"><?php esc_html_e('Add products to cart without page reload.', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Enable Cart Fragments', 'aqualuxe'); ?></th>
                        <td>
                            <input type="checkbox" name="aqualuxe_theme_options[cart_fragments]" <?php checked($options['cart_fragments']); ?> />
                            <p class="description"><?php esc_html_e('Update cart totals without page reload.', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Show Product Categories', 'aqualuxe'); ?></th>
                        <td>
                            <input type="checkbox" name="aqualuxe_theme_options[product_categories]" <?php checked($options['product_categories']); ?> />
                            <p class="description"><?php esc_html_e('Display product categories on product cards.', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Show Product Ratings', 'aqualuxe'); ?></th>
                        <td>
                            <input type="checkbox" name="aqualuxe_theme_options[product_ratings]" <?php checked($options['product_ratings']); ?> />
                            <p class="description"><?php esc_html_e('Display product ratings on product cards.', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Show Product Price', 'aqualuxe'); ?></th>
                        <td>
                            <input type="checkbox" name="aqualuxe_theme_options[product_price]" <?php checked($options['product_price']); ?> />
                            <p class="description"><?php esc_html_e('Display product price on product cards.', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Enable Multi-Currency Support', 'aqualuxe'); ?></th>
                        <td>
                            <input type="checkbox" name="aqualuxe_theme_options[multi_currency]" <?php checked($options['multi_currency']); ?> />
                            <p class="description"><?php esc_html_e('Support for multiple currencies (requires compatible plugin).', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Free Shipping Threshold', 'aqualuxe'); ?></th>
                        <td>
                            <input type="text" name="aqualuxe_theme_options[free_shipping_threshold]" value="<?php echo esc_attr($options['free_shipping_threshold']); ?>" />
                            <p class="description"><?php esc_html_e('Amount required for free shipping (e.g., 100).', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Default Shipping Information', 'aqualuxe'); ?></th>
                        <td>
                            <textarea name="aqualuxe_theme_options[default_shipping_info]" rows="5" cols="50"><?php echo esc_textarea($options['default_shipping_info']); ?></textarea>
                            <p class="description"><?php esc_html_e('Default shipping information to display on product pages.', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Default Return Policy', 'aqualuxe'); ?></th>
                        <td>
                            <textarea name="aqualuxe_theme_options[default_return_policy]" rows="5" cols="50"><?php echo esc_textarea($options['default_return_policy']); ?></textarea>
                            <p class="description"><?php esc_html_e('Default return policy to display on product pages.', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Default Warranty Information', 'aqualuxe'); ?></th>
                        <td>
                            <textarea name="aqualuxe_theme_options[default_warranty_info]" rows="5" cols="50"><?php echo esc_textarea($options['default_warranty_info']); ?></textarea>
                            <p class="description"><?php esc_html_e('Default warranty information to display on product pages.', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Default Size Guide', 'aqualuxe'); ?></th>
                        <td>
                            <textarea name="aqualuxe_theme_options[default_size_guide]" rows="5" cols="50"><?php echo esc_textarea($options['default_size_guide']); ?></textarea>
                            <p class="description"><?php esc_html_e('Default size guide to display on product pages.', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Default Estimated Delivery', 'aqualuxe'); ?></th>
                        <td>
                            <input type="text" name="aqualuxe_theme_options[default_estimated_delivery]" value="<?php echo esc_attr($options['default_estimated_delivery']); ?>" class="regular-text" />
                            <p class="description"><?php esc_html_e('Default estimated delivery time (e.g., 3-5 business days).', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Enable Gift Wrapping', 'aqualuxe'); ?></th>
                        <td>
                            <select name="aqualuxe_theme_options[gift_wrapping_enabled]">
                                <option value="yes" <?php selected($options['gift_wrapping_enabled'], 'yes'); ?>><?php esc_html_e('Yes', 'aqualuxe'); ?></option>
                                <option value="no" <?php selected($options['gift_wrapping_enabled'], 'no'); ?>><?php esc_html_e('No', 'aqualuxe'); ?></option>
                            </select>
                            <p class="description"><?php esc_html_e('Enable gift wrapping option on product pages.', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Gift Wrapping Price', 'aqualuxe'); ?></th>
                        <td>
                            <input type="text" name="aqualuxe_theme_options[gift_wrapping_price]" value="<?php echo esc_attr($options['gift_wrapping_price']); ?>" />
                            <p class="description"><?php esc_html_e('Price for gift wrapping (e.g., 5).', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                </table>
            <?php elseif ($active_tab === 'social') : ?>
                <h2><?php esc_html_e('Social Media Settings', 'aqualuxe'); ?></h2>
                <table class="form-table">
                    <tr>
                        <th scope="row"><?php esc_html_e('Facebook URL', 'aqualuxe'); ?></th>
                        <td>
                            <input type="url" name="aqualuxe_theme_options[facebook_url]" value="<?php echo esc_url($options['facebook_url']); ?>" class="regular-text" />
                            <p class="description"><?php esc_html_e('Enter your Facebook page URL.', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Twitter URL', 'aqualuxe'); ?></th>
                        <td>
                            <input type="url" name="aqualuxe_theme_options[twitter_url]" value="<?php echo esc_url($options['twitter_url']); ?>" class="regular-text" />
                            <p class="description"><?php esc_html_e('Enter your Twitter profile URL.', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Instagram URL', 'aqualuxe'); ?></th>
                        <td>
                            <input type="url" name="aqualuxe_theme_options[instagram_url]" value="<?php echo esc_url($options['instagram_url']); ?>" class="regular-text" />
                            <p class="description"><?php esc_html_e('Enter your Instagram profile URL.', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('YouTube URL', 'aqualuxe'); ?></th>
                        <td>
                            <input type="url" name="aqualuxe_theme_options[youtube_url]" value="<?php echo esc_url($options['youtube_url']); ?>" class="regular-text" />
                            <p class="description"><?php esc_html_e('Enter your YouTube channel URL.', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Pinterest URL', 'aqualuxe'); ?></th>
                        <td>
                            <input type="url" name="aqualuxe_theme_options[pinterest_url]" value="<?php echo esc_url($options['pinterest_url']); ?>" class="regular-text" />
                            <p class="description"><?php esc_html_e('Enter your Pinterest profile URL.', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('LinkedIn URL', 'aqualuxe'); ?></th>
                        <td>
                            <input type="url" name="aqualuxe_theme_options[linkedin_url]" value="<?php echo esc_url($options['linkedin_url']); ?>" class="regular-text" />
                            <p class="description"><?php esc_html_e('Enter your LinkedIn profile URL.', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Twitter Username', 'aqualuxe'); ?></th>
                        <td>
                            <input type="text" name="aqualuxe_theme_options[twitter_username]" value="<?php echo esc_attr($options['twitter_username']); ?>" class="regular-text" />
                            <p class="description"><?php esc_html_e('Enter your Twitter username without the @ symbol (for Twitter cards).', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Enable Social Sharing', 'aqualuxe'); ?></th>
                        <td>
                            <input type="checkbox" name="aqualuxe_theme_options[social_sharing]" <?php checked($options['social_sharing']); ?> />
                            <p class="description"><?php esc_html_e('Show social sharing buttons on posts and products.', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                </table>
            <?php elseif ($active_tab === 'performance') : ?>
                <h2><?php esc_html_e('Performance Settings', 'aqualuxe'); ?></h2>
                <table class="form-table">
                    <tr>
                        <th scope="row"><?php esc_html_e('Enable Lazy Loading', 'aqualuxe'); ?></th>
                        <td>
                            <input type="checkbox" name="aqualuxe_theme_options[lazy_loading]" <?php checked($options['lazy_loading']); ?> />
                            <p class="description"><?php esc_html_e('Lazy load images for better performance.', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Enable Minification', 'aqualuxe'); ?></th>
                        <td>
                            <input type="checkbox" name="aqualuxe_theme_options[minification]" <?php checked($options['minification']); ?> />
                            <p class="description"><?php esc_html_e('Minify CSS and JavaScript files.', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Enable Preloading', 'aqualuxe'); ?></th>
                        <td>
                            <input type="checkbox" name="aqualuxe_theme_options[preloading]" <?php checked($options['preloading']); ?> />
                            <p class="description"><?php esc_html_e('Preload critical assets.', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Enable Prefetching', 'aqualuxe'); ?></th>
                        <td>
                            <input type="checkbox" name="aqualuxe_theme_options[prefetching]" <?php checked($options['prefetching']); ?> />
                            <p class="description"><?php esc_html_e('Prefetch links for faster navigation.', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Enable Critical CSS', 'aqualuxe'); ?></th>
                        <td>
                            <input type="checkbox" name="aqualuxe_theme_options[critical_css]" <?php checked($options['critical_css']); ?> />
                            <p class="description"><?php esc_html_e('Inline critical CSS for faster rendering.', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Enable Responsive Images', 'aqualuxe'); ?></th>
                        <td>
                            <input type="checkbox" name="aqualuxe_theme_options[responsive_images]" <?php checked($options['responsive_images']); ?> />
                            <p class="description"><?php esc_html_e('Use srcset and sizes attributes for images.', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Enable WebP Images', 'aqualuxe'); ?></th>
                        <td>
                            <input type="checkbox" name="aqualuxe_theme_options[webp]" <?php checked($options['webp']); ?> />
                            <p class="description"><?php esc_html_e('Use WebP image format when supported.', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Enable Defer JavaScript', 'aqualuxe'); ?></th>
                        <td>
                            <input type="checkbox" name="aqualuxe_theme_options[defer_js]" <?php checked($options['defer_js']); ?> />
                            <p class="description"><?php esc_html_e('Defer non-critical JavaScript.', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Enable Async CSS', 'aqualuxe'); ?></th>
                        <td>
                            <input type="checkbox" name="aqualuxe_theme_options[async_css]" <?php checked($options['async_css']); ?> />
                            <p class="description"><?php esc_html_e('Load non-critical CSS asynchronously.', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                </table>
            <?php elseif ($active_tab === 'advanced') : ?>
                <h2><?php esc_html_e('Advanced Settings', 'aqualuxe'); ?></h2>
                <table class="form-table">
                    <tr>
                        <th scope="row"><?php esc_html_e('Custom CSS', 'aqualuxe'); ?></th>
                        <td>
                            <textarea name="aqualuxe_theme_options[custom_css]" rows="10" cols="50" class="large-text code"><?php echo esc_textarea($options['custom_css']); ?></textarea>
                            <p class="description"><?php esc_html_e('Add custom CSS styles.', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Custom JavaScript', 'aqualuxe'); ?></th>
                        <td>
                            <textarea name="aqualuxe_theme_options[custom_js]" rows="10" cols="50" class="large-text code"><?php echo esc_textarea($options['custom_js']); ?></textarea>
                            <p class="description"><?php esc_html_e('Add custom JavaScript code.', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Google Analytics', 'aqualuxe'); ?></th>
                        <td>
                            <textarea name="aqualuxe_theme_options[google_analytics]" rows="5" cols="50" class="large-text code"><?php echo esc_textarea($options['google_analytics']); ?></textarea>
                            <p class="description"><?php esc_html_e('Add Google Analytics tracking code.', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Facebook Pixel', 'aqualuxe'); ?></th>
                        <td>
                            <textarea name="aqualuxe_theme_options[facebook_pixel]" rows="5" cols="50" class="large-text code"><?php echo esc_textarea($options['facebook_pixel']); ?></textarea>
                            <p class="description"><?php esc_html_e('Add Facebook Pixel tracking code.', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Header Scripts', 'aqualuxe'); ?></th>
                        <td>
                            <textarea name="aqualuxe_theme_options[header_scripts]" rows="5" cols="50" class="large-text code"><?php echo esc_textarea($options['header_scripts']); ?></textarea>
                            <p class="description"><?php esc_html_e('Add custom scripts to the header.', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Footer Scripts', 'aqualuxe'); ?></th>
                        <td>
                            <textarea name="aqualuxe_theme_options[footer_scripts]" rows="5" cols="50" class="large-text code"><?php echo esc_textarea($options['footer_scripts']); ?></textarea>
                            <p class="description"><?php esc_html_e('Add custom scripts to the footer.', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Maintenance Mode', 'aqualuxe'); ?></th>
                        <td>
                            <input type="checkbox" name="aqualuxe_theme_options[maintenance_mode]" <?php checked($options['maintenance_mode']); ?> />
                            <p class="description"><?php esc_html_e('Enable maintenance mode.', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Maintenance Message', 'aqualuxe'); ?></th>
                        <td>
                            <textarea name="aqualuxe_theme_options[maintenance_message]" rows="5" cols="50"><?php echo esc_textarea($options['maintenance_message']); ?></textarea>
                            <p class="description"><?php esc_html_e('Message to display during maintenance mode.', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                </table>
            <?php endif; ?>
            
            <?php submit_button(); ?>
        </form>
    </div>
    
    <script>
    jQuery(document).ready(function($) {
        // Initialize color pickers
        $('.color-picker').wpColorPicker();
        
        // Media uploader for favicon
        $('#favicon-upload-button').click(function(e) {
            e.preventDefault();
            
            var faviconFrame = wp.media({
                title: '<?php esc_html_e('Select Favicon', 'aqualuxe'); ?>',
                button: {
                    text: '<?php esc_html_e('Use This Image', 'aqualuxe'); ?>'
                },
                multiple: false
            });
            
            faviconFrame.on('select', function() {
                var attachment = faviconFrame.state().get('selection').first().toJSON();
                $('input[name="aqualuxe_theme_options[favicon]"]').val(attachment.url);
            });
            
            faviconFrame.open();
        });
        
        // Media uploader for footer logo
        $('#footer-logo-upload-button').click(function(e) {
            e.preventDefault();
            
            var logoFrame = wp.media({
                title: '<?php esc_html_e('Select Footer Logo', 'aqualuxe'); ?>',
                button: {
                    text: '<?php esc_html_e('Use This Image', 'aqualuxe'); ?>'
                },
                multiple: false
            });
            
            logoFrame.on('select', function() {
                var attachment = logoFrame.state().get('selection').first().toJSON();
                $('input[name="aqualuxe_theme_options[footer_logo]"]').val(attachment.url);
            });
            
            logoFrame.open();
        });
    });
    </script>
    <?php
}

/**
 * Add theme options to the admin bar
 *
 * @param WP_Admin_Bar $admin_bar Admin bar object.
 */
function aqualuxe_admin_bar_theme_options($admin_bar) {
    if (current_user_can('edit_theme_options')) {
        $admin_bar->add_menu(array(
            'id'    => 'aqualuxe-theme-options',
            'title' => __('Theme Options', 'aqualuxe'),
            'href'  => admin_url('themes.php?page=aqualuxe-theme-options'),
            'meta'  => array(
                'title' => __('AquaLuxe Theme Options', 'aqualuxe'),
            ),
        ));
        
        $admin_bar->add_menu(array(
            'id'     => 'aqualuxe-theme-options-general',
            'parent' => 'aqualuxe-theme-options',
            'title'  => __('General', 'aqualuxe'),
            'href'   => admin_url('themes.php?page=aqualuxe-theme-options&tab=general'),
        ));
        
        $admin_bar->add_menu(array(
            'id'     => 'aqualuxe-theme-options-layout',
            'parent' => 'aqualuxe-theme-options',
            'title'  => __('Layout', 'aqualuxe'),
            'href'   => admin_url('themes.php?page=aqualuxe-theme-options&tab=layout'),
        ));
        
        $admin_bar->add_menu(array(
            'id'     => 'aqualuxe-theme-options-typography',
            'parent' => 'aqualuxe-theme-options',
            'title'  => __('Typography', 'aqualuxe'),
            'href'   => admin_url('themes.php?page=aqualuxe-theme-options&tab=typography'),
        ));
        
        $admin_bar->add_menu(array(
            'id'     => 'aqualuxe-theme-options-colors',
            'parent' => 'aqualuxe-theme-options',
            'title'  => __('Colors', 'aqualuxe'),
            'href'   => admin_url('themes.php?page=aqualuxe-theme-options&tab=colors'),
        ));
        
        $admin_bar->add_menu(array(
            'id'     => 'aqualuxe-theme-options-header',
            'parent' => 'aqualuxe-theme-options',
            'title'  => __('Header', 'aqualuxe'),
            'href'   => admin_url('themes.php?page=aqualuxe-theme-options&tab=header'),
        ));
        
        $admin_bar->add_menu(array(
            'id'     => 'aqualuxe-theme-options-footer',
            'parent' => 'aqualuxe-theme-options',
            'title'  => __('Footer', 'aqualuxe'),
            'href'   => admin_url('themes.php?page=aqualuxe-theme-options&tab=footer'),
        ));
        
        $admin_bar->add_menu(array(
            'id'     => 'aqualuxe-theme-options-woocommerce',
            'parent' => 'aqualuxe-theme-options',
            'title'  => __('WooCommerce', 'aqualuxe'),
            'href'   => admin_url('themes.php?page=aqualuxe-theme-options&tab=woocommerce'),
        ));
        
        $admin_bar->add_menu(array(
            'id'     => 'aqualuxe-theme-options-social',
            'parent' => 'aqualuxe-theme-options',
            'title'  => __('Social Media', 'aqualuxe'),
            'href'   => admin_url('themes.php?page=aqualuxe-theme-options&tab=social'),
        ));
        
        $admin_bar->add_menu(array(
            'id'     => 'aqualuxe-theme-options-performance',
            'parent' => 'aqualuxe-theme-options',
            'title'  => __('Performance', 'aqualuxe'),
            'href'   => admin_url('themes.php?page=aqualuxe-theme-options&tab=performance'),
        ));
        
        $admin_bar->add_menu(array(
            'id'     => 'aqualuxe-theme-options-advanced',
            'parent' => 'aqualuxe-theme-options',
            'title'  => __('Advanced', 'aqualuxe'),
            'href'   => admin_url('themes.php?page=aqualuxe-theme-options&tab=advanced'),
        ));
    }
}
add_action('admin_bar_menu', 'aqualuxe_admin_bar_theme_options', 100);

/**
 * Add theme options link to the appearance submenu
 */
function aqualuxe_theme_options_add_page_to_menu() {
    add_submenu_page(
        'themes.php',
        __('Theme Options', 'aqualuxe'),
        __('Theme Options', 'aqualuxe'),
        'edit_theme_options',
        'aqualuxe-theme-options',
        'aqualuxe_theme_options_render_page'
    );
}
add_action('admin_menu', 'aqualuxe_theme_options_add_page_to_menu');

/**
 * Enqueue admin scripts and styles for theme options
 *
 * @param string $hook Hook suffix for the current admin page.
 */
function aqualuxe_theme_options_admin_enqueue_scripts($hook) {
    if ('appearance_page_aqualuxe-theme-options' !== $hook && 'themes.php?page=aqualuxe-theme-options' !== $hook) {
        return;
    }
    
    wp_enqueue_style('wp-color-picker');
    wp_enqueue_script('wp-color-picker');
    wp_enqueue_media();
}
add_action('admin_enqueue_scripts', 'aqualuxe_theme_options_admin_enqueue_scripts');

/**
 * Add custom CSS from theme options
 */
function aqualuxe_custom_css() {
    $options = aqualuxe_get_theme_options();
    
    if (!empty($options['custom_css'])) {
        echo '<style type="text/css">' . wp_strip_all_tags($options['custom_css']) . '</style>';
    }
}
add_action('wp_head', 'aqualuxe_custom_css', 999);

/**
 * Add custom JavaScript from theme options
 */
function aqualuxe_custom_js() {
    $options = aqualuxe_get_theme_options();
    
    if (!empty($options['custom_js'])) {
        echo '<script>' . wp_strip_all_tags($options['custom_js']) . '</script>';
    }
}
add_action('wp_footer', 'aqualuxe_custom_js', 999);

/**
 * Add Google Analytics from theme options
 */
function aqualuxe_google_analytics() {
    $options = aqualuxe_get_theme_options();
    
    if (!empty($options['google_analytics'])) {
        echo wp_strip_all_tags($options['google_analytics']);
    }
}
add_action('wp_head', 'aqualuxe_google_analytics');

/**
 * Add Facebook Pixel from theme options
 */
function aqualuxe_facebook_pixel() {
    $options = aqualuxe_get_theme_options();
    
    if (!empty($options['facebook_pixel'])) {
        echo wp_strip_all_tags($options['facebook_pixel']);
    }
}
add_action('wp_head', 'aqualuxe_facebook_pixel');

/**
 * Add header scripts from theme options
 */
function aqualuxe_header_scripts() {
    $options = aqualuxe_get_theme_options();
    
    if (!empty($options['header_scripts'])) {
        echo wp_strip_all_tags($options['header_scripts']);
    }
}
add_action('wp_head', 'aqualuxe_header_scripts');

/**
 * Add footer scripts from theme options
 */
function aqualuxe_footer_scripts() {
    $options = aqualuxe_get_theme_options();
    
    if (!empty($options['footer_scripts'])) {
        echo wp_strip_all_tags($options['footer_scripts']);
    }
}
add_action('wp_footer', 'aqualuxe_footer_scripts');

/**
 * Enable maintenance mode
 */
function aqualuxe_maintenance_mode() {
    $options = aqualuxe_get_theme_options();
    
    if (!empty($options['maintenance_mode']) && !is_user_logged_in() && !is_admin()) {
        wp_die(
            '<h1>' . __('Under Maintenance', 'aqualuxe') . '</h1>' .
            '<p>' . esc_html($options['maintenance_message']) . '</p>',
            __('Under Maintenance', 'aqualuxe'),
            array('response' => 503)
        );
    }
}
add_action('template_redirect', 'aqualuxe_maintenance_mode');