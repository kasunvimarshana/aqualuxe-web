<?php
/**
 * Admin functions for the AquaLuxe theme
 *
 * @package AquaLuxe
 */

/**
 * Enqueue admin scripts and styles
 */
function aqualuxe_admin_scripts() {
    wp_enqueue_style('aqualuxe-admin-style', AQUALUXE_URI . 'assets/css/admin.css', array(), AQUALUXE_VERSION);
    wp_enqueue_script('aqualuxe-admin-script', AQUALUXE_URI . 'assets/js/admin.js', array('jquery'), AQUALUXE_VERSION, true);
}
add_action('admin_enqueue_scripts', 'aqualuxe_admin_scripts');

/**
 * Add theme info page
 */
function aqualuxe_add_theme_page() {
    add_theme_page(
        __('AquaLuxe Theme', 'aqualuxe'),
        __('AquaLuxe Theme', 'aqualuxe'),
        'manage_options',
        'aqualuxe-theme',
        'aqualuxe_theme_page_content'
    );
}
add_action('admin_menu', 'aqualuxe_add_theme_page');

/**
 * Theme page content
 */
function aqualuxe_theme_page_content() {
    ?>
    <div class="wrap aqualuxe-theme-page">
        <h1><?php esc_html_e('Welcome to AquaLuxe Theme', 'aqualuxe'); ?></h1>
        
        <div class="aqualuxe-theme-info">
            <div class="aqualuxe-theme-description">
                <h2><?php esc_html_e('About AquaLuxe', 'aqualuxe'); ?></h2>
                <p><?php esc_html_e('AquaLuxe is a premium WordPress theme designed specifically for ornamental fish farming businesses targeting local and international markets. The theme features full WooCommerce integration, custom post types for services and events, and a modern, elegant design.', 'aqualuxe'); ?></p>
            </div>
            
            <div class="aqualuxe-theme-version">
                <h3><?php esc_html_e('Theme Version', 'aqualuxe'); ?></h3>
                <p><?php echo esc_html(AQUALUXE_VERSION); ?></p>
            </div>
        </div>
        
        <div class="aqualuxe-theme-tabs">
            <h2 class="nav-tab-wrapper">
                <a href="#getting-started" class="nav-tab nav-tab-active"><?php esc_html_e('Getting Started', 'aqualuxe'); ?></a>
                <a href="#theme-options" class="nav-tab"><?php esc_html_e('Theme Options', 'aqualuxe'); ?></a>
                <a href="#support" class="nav-tab"><?php esc_html_e('Support', 'aqualuxe'); ?></a>
            </h2>
            
            <div id="getting-started" class="aqualuxe-tab-content active">
                <h3><?php esc_html_e('Getting Started with AquaLuxe', 'aqualuxe'); ?></h3>
                
                <div class="aqualuxe-feature-section">
                    <h4><?php esc_html_e('1. Install Required Plugins', 'aqualuxe'); ?></h4>
                    <p><?php esc_html_e('AquaLuxe works best with the following plugins:', 'aqualuxe'); ?></p>
                    <ul>
                        <li><?php esc_html_e('WooCommerce - For e-commerce functionality', 'aqualuxe'); ?></li>
                        <li><?php esc_html_e('Contact Form 7 - For contact forms', 'aqualuxe'); ?></li>
                        <li><?php esc_html_e('Yoast SEO - For search engine optimization', 'aqualuxe'); ?></li>
                    </ul>
                </div>
                
                <div class="aqualuxe-feature-section">
                    <h4><?php esc_html_e('2. Customize Your Theme', 'aqualuxe'); ?></h4>
                    <p><?php esc_html_e('Use the WordPress Customizer to configure your theme settings:', 'aqualuxe'); ?></p>
                    <a href="<?php echo esc_url(admin_url('customize.php')); ?>" class="button button-primary"><?php esc_html_e('Open Customizer', 'aqualuxe'); ?></a>
                </div>
                
                <div class="aqualuxe-feature-section">
                    <h4><?php esc_html_e('3. Set Up Your Homepage', 'aqualuxe'); ?></h4>
                    <p><?php esc_html_e('Create a new page and assign it as your homepage in Settings > Reading.', 'aqualuxe'); ?></p>
                    <a href="<?php echo esc_url(admin_url('options-reading.php')); ?>" class="button button-secondary"><?php esc_html_e('Reading Settings', 'aqualuxe'); ?></a>
                </div>
                
                <div class="aqualuxe-feature-section">
                    <h4><?php esc_html_e('4. Add Your Products', 'aqualuxe'); ?></h4>
                    <p><?php esc_html_e('If you have WooCommerce installed, start adding your products.', 'aqualuxe'); ?></p>
                    <a href="<?php echo esc_url(admin_url('post-new.php?post_type=product')); ?>" class="button button-secondary"><?php esc_html_e('Add New Product', 'aqualuxe'); ?></a>
                </div>
                
                <div class="aqualuxe-feature-section">
                    <h4><?php esc_html_e('5. Create Services and Events', 'aqualuxe'); ?></h4>
                    <p><?php esc_html_e('AquaLuxe includes custom post types for Services and Events.', 'aqualuxe'); ?></p>
                    <a href="<?php echo esc_url(admin_url('post-new.php?post_type=service')); ?>" class="button button-secondary"><?php esc_html_e('Add New Service', 'aqualuxe'); ?></a>
                    <a href="<?php echo esc_url(admin_url('post-new.php?post_type=event')); ?>" class="button button-secondary"><?php esc_html_e('Add New Event', 'aqualuxe'); ?></a>
                </div>
            </div>
            
            <div id="theme-options" class="aqualuxe-tab-content">
                <h3><?php esc_html_e('Theme Options', 'aqualuxe'); ?></h3>
                
                <div class="aqualuxe-feature-section">
                    <h4><?php esc_html_e('Customizer Options', 'aqualuxe'); ?></h4>
                    <p><?php esc_html_e('AquaLuxe provides many customization options through the WordPress Customizer:', 'aqualuxe'); ?></p>
                    <ul>
                        <li><?php esc_html_e('Colors - Customize primary, secondary, and accent colors', 'aqualuxe'); ?></li>
                        <li><?php esc_html_e('Typography - Choose from a selection of Google Fonts', 'aqualuxe'); ?></li>
                        <li><?php esc_html_e('Layout - Adjust container width and sidebar position', 'aqualuxe'); ?></li>
                        <li><?php esc_html_e('Header - Configure header style and options', 'aqualuxe'); ?></li>
                        <li><?php esc_html_e('Footer - Set footer columns and copyright text', 'aqualuxe'); ?></li>
                        <li><?php esc_html_e('WooCommerce - Customize shop settings', 'aqualuxe'); ?></li>
                        <li><?php esc_html_e('Social Media - Add your social media links', 'aqualuxe'); ?></li>
                    </ul>
                    <a href="<?php echo esc_url(admin_url('customize.php')); ?>" class="button button-primary"><?php esc_html_e('Open Customizer', 'aqualuxe'); ?></a>
                </div>
                
                <div class="aqualuxe-feature-section">
                    <h4><?php esc_html_e('Widgets', 'aqualuxe'); ?></h4>
                    <p><?php esc_html_e('AquaLuxe includes several widget areas and custom widgets:', 'aqualuxe'); ?></p>
                    <ul>
                        <li><?php esc_html_e('Sidebar - Main sidebar for blog and pages', 'aqualuxe'); ?></li>
                        <li><?php esc_html_e('Footer Widgets - Up to 4 footer widget areas', 'aqualuxe'); ?></li>
                        <li><?php esc_html_e('Shop Sidebar - Sidebar for WooCommerce pages', 'aqualuxe'); ?></li>
                        <li><?php esc_html_e('Custom Widgets - Recent Posts with Thumbnails, Upcoming Events', 'aqualuxe'); ?></li>
                    </ul>
                    <a href="<?php echo esc_url(admin_url('widgets.php')); ?>" class="button button-secondary"><?php esc_html_e('Manage Widgets', 'aqualuxe'); ?></a>
                </div>
                
                <div class="aqualuxe-feature-section">
                    <h4><?php esc_html_e('Menus', 'aqualuxe'); ?></h4>
                    <p><?php esc_html_e('AquaLuxe supports multiple menu locations:', 'aqualuxe'); ?></p>
                    <ul>
                        <li><?php esc_html_e('Primary Menu - Main navigation menu', 'aqualuxe'); ?></li>
                        <li><?php esc_html_e('Secondary Menu - Additional top navigation', 'aqualuxe'); ?></li>
                        <li><?php esc_html_e('Footer Menu - Links in the footer area', 'aqualuxe'); ?></li>
                    </ul>
                    <a href="<?php echo esc_url(admin_url('nav-menus.php')); ?>" class="button button-secondary"><?php esc_html_e('Manage Menus', 'aqualuxe'); ?></a>
                </div>
            </div>
            
            <div id="support" class="aqualuxe-tab-content">
                <h3><?php esc_html_e('Support', 'aqualuxe'); ?></h3>
                
                <div class="aqualuxe-feature-section">
                    <h4><?php esc_html_e('Documentation', 'aqualuxe'); ?></h4>
                    <p><?php esc_html_e('For detailed information on how to use AquaLuxe, please refer to our documentation.', 'aqualuxe'); ?></p>
                    <a href="#" class="button button-primary"><?php esc_html_e('View Documentation', 'aqualuxe'); ?></a>
                </div>
                
                <div class="aqualuxe-feature-section">
                    <h4><?php esc_html_e('Support', 'aqualuxe'); ?></h4>
                    <p><?php esc_html_e('If you need help with AquaLuxe, please contact our support team.', 'aqualuxe'); ?></p>
                    <a href="#" class="button button-secondary"><?php esc_html_e('Contact Support', 'aqualuxe'); ?></a>
                </div>
                
                <div class="aqualuxe-feature-section">
                    <h4><?php esc_html_e('Updates', 'aqualuxe'); ?></h4>
                    <p><?php esc_html_e('AquaLuxe is regularly updated with new features and improvements. Make sure to keep your theme updated for the best experience.', 'aqualuxe'); ?></p>
                </div>
            </div>
        </div>
    </div>
    
    <script>
    jQuery(document).ready(function($) {
        // Tab functionality
        $('.aqualuxe-theme-tabs .nav-tab').on('click', function(e) {
            e.preventDefault();
            var target = $(this).attr('href');
            
            // Update tabs
            $('.aqualuxe-theme-tabs .nav-tab').removeClass('nav-tab-active');
            $(this).addClass('nav-tab-active');
            
            // Update content
            $('.aqualuxe-tab-content').removeClass('active');
            $(target).addClass('active');
        });
    });
    </script>
    
    <style>
    .aqualuxe-theme-page {
        max-width: 1200px;
        margin: 0 auto;
    }
    
    .aqualuxe-theme-info {
        display: flex;
        justify-content: space-between;
        margin-bottom: 30px;
        background: #fff;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }
    
    .aqualuxe-theme-description {
        flex: 2;
        padding-right: 30px;
    }
    
    .aqualuxe-theme-version {
        flex: 1;
        border-left: 1px solid #eee;
        padding-left: 30px;
    }
    
    .aqualuxe-tab-content {
        display: none;
        background: #fff;
        padding: 20px;
        border-radius: 0 5px 5px 5px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }
    
    .aqualuxe-tab-content.active {
        display: block;
    }
    
    .aqualuxe-feature-section {
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 1px solid #eee;
    }
    
    .aqualuxe-feature-section:last-child {
        border-bottom: none;
    }
    
    .button {
        margin-right: 10px;
        margin-top: 10px;
    }
    </style>
    <?php
}

/**
 * Add meta box for page options
 */
function aqualuxe_add_page_options_meta_box() {
    add_meta_box(
        'aqualuxe_page_options',
        __('Page Options', 'aqualuxe'),
        'aqualuxe_page_options_callback',
        'page',
        'side',
        'default'
    );
}
add_action('add_meta_boxes', 'aqualuxe_add_page_options_meta_box');

/**
 * Page options meta box callback
 */
function aqualuxe_page_options_callback($post) {
    wp_nonce_field('aqualuxe_page_options', 'aqualuxe_page_options_nonce');

    $hide_title = get_post_meta($post->ID, '_aqualuxe_hide_title', true);
    $hide_featured_image = get_post_meta($post->ID, '_aqualuxe_hide_featured_image', true);
    $layout = get_post_meta($post->ID, '_aqualuxe_page_layout', true);
    
    if (empty($layout)) {
        $layout = 'default';
    }
    ?>
    <p>
        <label for="aqualuxe_hide_title">
            <input type="checkbox" id="aqualuxe_hide_title" name="aqualuxe_hide_title" value="1" <?php checked($hide_title, '1'); ?> />
            <?php esc_html_e('Hide Page Title', 'aqualuxe'); ?>
        </label>
    </p>
    <p>
        <label for="aqualuxe_hide_featured_image">
            <input type="checkbox" id="aqualuxe_hide_featured_image" name="aqualuxe_hide_featured_image" value="1" <?php checked($hide_featured_image, '1'); ?> />
            <?php esc_html_e('Hide Featured Image', 'aqualuxe'); ?>
        </label>
    </p>
    <p>
        <label for="aqualuxe_page_layout"><?php esc_html_e('Page Layout:', 'aqualuxe'); ?></label><br>
        <select id="aqualuxe_page_layout" name="aqualuxe_page_layout">
            <option value="default" <?php selected($layout, 'default'); ?>><?php esc_html_e('Default', 'aqualuxe'); ?></option>
            <option value="full-width" <?php selected($layout, 'full-width'); ?>><?php esc_html_e('Full Width', 'aqualuxe'); ?></option>
            <option value="left-sidebar" <?php selected($layout, 'left-sidebar'); ?>><?php esc_html_e('Left Sidebar', 'aqualuxe'); ?></option>
            <option value="right-sidebar" <?php selected($layout, 'right-sidebar'); ?>><?php esc_html_e('Right Sidebar', 'aqualuxe'); ?></option>
        </select>
    </p>
    <?php
}

/**
 * Save page options meta box data
 */
function aqualuxe_save_page_options($post_id) {
    // Check if our nonce is set
    if (!isset($_POST['aqualuxe_page_options_nonce'])) {
        return;
    }

    // Verify that the nonce is valid
    if (!wp_verify_nonce($_POST['aqualuxe_page_options_nonce'], 'aqualuxe_page_options')) {
        return;
    }

    // If this is an autosave, our form has not been submitted, so we don't want to do anything
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Check the user's permissions
    if (isset($_POST['post_type']) && 'page' == $_POST['post_type']) {
        if (!current_user_can('edit_page', $post_id)) {
            return;
        }
    } else {
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
    }

    // Save hide title option
    $hide_title = isset($_POST['aqualuxe_hide_title']) ? '1' : '';
    update_post_meta($post_id, '_aqualuxe_hide_title', $hide_title);

    // Save hide featured image option
    $hide_featured_image = isset($_POST['aqualuxe_hide_featured_image']) ? '1' : '';
    update_post_meta($post_id, '_aqualuxe_hide_featured_image', $hide_featured_image);

    // Save page layout option
    if (isset($_POST['aqualuxe_page_layout'])) {
        update_post_meta($post_id, '_aqualuxe_page_layout', sanitize_text_field($_POST['aqualuxe_page_layout']));
    }
}
add_action('save_post', 'aqualuxe_save_page_options');

/**
 * Add theme options to the admin bar
 */
function aqualuxe_admin_bar_theme_options($wp_admin_bar) {
    if (!is_admin() && current_user_can('manage_options')) {
        $wp_admin_bar->add_node(array(
            'id'    => 'aqualuxe-theme-options',
            'title' => __('AquaLuxe Options', 'aqualuxe'),
            'href'  => admin_url('customize.php'),
        ));

        $wp_admin_bar->add_node(array(
            'id'     => 'aqualuxe-colors',
            'title'  => __('Colors', 'aqualuxe'),
            'href'   => admin_url('customize.php?autofocus[section]=aqualuxe_colors'),
            'parent' => 'aqualuxe-theme-options',
        ));

        $wp_admin_bar->add_node(array(
            'id'     => 'aqualuxe-typography',
            'title'  => __('Typography', 'aqualuxe'),
            'href'   => admin_url('customize.php?autofocus[section]=aqualuxe_typography'),
            'parent' => 'aqualuxe-theme-options',
        ));

        $wp_admin_bar->add_node(array(
            'id'     => 'aqualuxe-layout',
            'title'  => __('Layout', 'aqualuxe'),
            'href'   => admin_url('customize.php?autofocus[section]=aqualuxe_layout'),
            'parent' => 'aqualuxe-theme-options',
        ));

        $wp_admin_bar->add_node(array(
            'id'     => 'aqualuxe-header',
            'title'  => __('Header', 'aqualuxe'),
            'href'   => admin_url('customize.php?autofocus[section]=aqualuxe_header'),
            'parent' => 'aqualuxe-theme-options',
        ));

        $wp_admin_bar->add_node(array(
            'id'     => 'aqualuxe-footer',
            'title'  => __('Footer', 'aqualuxe'),
            'href'   => admin_url('customize.php?autofocus[section]=aqualuxe_footer'),
            'parent' => 'aqualuxe-theme-options',
        ));

        if (class_exists('WooCommerce')) {
            $wp_admin_bar->add_node(array(
                'id'     => 'aqualuxe-woocommerce',
                'title'  => __('WooCommerce', 'aqualuxe'),
                'href'   => admin_url('customize.php?autofocus[section]=aqualuxe_woocommerce'),
                'parent' => 'aqualuxe-theme-options',
            ));
        }

        $wp_admin_bar->add_node(array(
            'id'     => 'aqualuxe-social',
            'title'  => __('Social Media', 'aqualuxe'),
            'href'   => admin_url('customize.php?autofocus[section]=aqualuxe_social'),
            'parent' => 'aqualuxe-theme-options',
        ));
    }
}
add_action('admin_bar_menu', 'aqualuxe_admin_bar_theme_options', 100);

/**
 * Add dashboard widget with theme information
 */
function aqualuxe_add_dashboard_widget() {
    wp_add_dashboard_widget(
        'aqualuxe_dashboard_widget',
        __('AquaLuxe Theme', 'aqualuxe'),
        'aqualuxe_dashboard_widget_content'
    );
}
add_action('wp_dashboard_setup', 'aqualuxe_add_dashboard_widget');

/**
 * Dashboard widget content
 */
function aqualuxe_dashboard_widget_content() {
    ?>
    <div class="aqualuxe-dashboard-widget">
        <h3><?php esc_html_e('Welcome to AquaLuxe Theme', 'aqualuxe'); ?></h3>
        <p><?php esc_html_e('Thank you for choosing AquaLuxe for your website. Here are some quick links to get you started:', 'aqualuxe'); ?></p>
        
        <ul>
            <li><a href="<?php echo esc_url(admin_url('customize.php')); ?>"><?php esc_html_e('Customize Your Theme', 'aqualuxe'); ?></a></li>
            <li><a href="<?php echo esc_url(admin_url('themes.php?page=aqualuxe-theme')); ?>"><?php esc_html_e('Theme Documentation', 'aqualuxe'); ?></a></li>
            <?php if (class_exists('WooCommerce')) : ?>
                <li><a href="<?php echo esc_url(admin_url('post-new.php?post_type=product')); ?>"><?php esc_html_e('Add New Product', 'aqualuxe'); ?></a></li>
            <?php endif; ?>
            <li><a href="<?php echo esc_url(admin_url('post-new.php?post_type=service')); ?>"><?php esc_html_e('Add New Service', 'aqualuxe'); ?></a></li>
            <li><a href="<?php echo esc_url(admin_url('post-new.php?post_type=event')); ?>"><?php esc_html_e('Add New Event', 'aqualuxe'); ?></a></li>
        </ul>
        
        <p><?php esc_html_e('Need help? Visit our support page for assistance.', 'aqualuxe'); ?></p>
    </div>
    
    <style>
    .aqualuxe-dashboard-widget ul {
        margin-bottom: 15px;
    }
    
    .aqualuxe-dashboard-widget ul li {
        margin-bottom: 8px;
    }
    </style>
    <?php
}