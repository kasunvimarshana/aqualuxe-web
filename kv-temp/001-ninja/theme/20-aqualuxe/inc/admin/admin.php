<?php
/**
 * Admin functions for the AquaLuxe theme
 *
 * @package AquaLuxe
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Enqueue admin scripts and styles
 */
function aqualuxe_admin_scripts() {
    wp_enqueue_style(
        'aqualuxe-admin-style',
        AQUALUXE_URI . 'assets/css/admin.css',
        array(),
        AQUALUXE_VERSION
    );

    wp_enqueue_script(
        'aqualuxe-admin-script',
        AQUALUXE_URI . 'assets/js/admin.js',
        array( 'jquery' ),
        AQUALUXE_VERSION,
        true
    );
}
add_action( 'admin_enqueue_scripts', 'aqualuxe_admin_scripts' );

/**
 * Add theme info page to the admin menu
 */
function aqualuxe_add_theme_page() {
    add_theme_page(
        __( 'AquaLuxe Theme', 'aqualuxe' ),
        __( 'AquaLuxe Theme', 'aqualuxe' ),
        'manage_options',
        'aqualuxe-theme',
        'aqualuxe_theme_page_content'
    );
}
add_action( 'admin_menu', 'aqualuxe_add_theme_page' );

/**
 * Theme info page content
 */
function aqualuxe_theme_page_content() {
    ?>
    <div class="wrap aqualuxe-theme-page">
        <h1><?php esc_html_e( 'Welcome to AquaLuxe Theme', 'aqualuxe' ); ?></h1>
        
        <div class="aqualuxe-theme-info">
            <div class="aqualuxe-theme-info-header">
                <div class="aqualuxe-theme-info-header-left">
                    <h2><?php esc_html_e( 'Thank you for choosing AquaLuxe!', 'aqualuxe' ); ?></h2>
                    <p><?php esc_html_e( 'AquaLuxe is a premium WordPress theme designed specifically for high-end ornamental fish farming businesses. This page will help you get started with the theme.', 'aqualuxe' ); ?></p>
                </div>
                <div class="aqualuxe-theme-info-header-right">
                    <div class="aqualuxe-theme-version">
                        <span><?php esc_html_e( 'Version', 'aqualuxe' ); ?>: <?php echo esc_html( AQUALUXE_VERSION ); ?></span>
                    </div>
                </div>
            </div>
            
            <div class="aqualuxe-theme-tabs">
                <div class="aqualuxe-theme-tabs-nav">
                    <a href="#getting-started" class="aqualuxe-theme-tab-nav active"><?php esc_html_e( 'Getting Started', 'aqualuxe' ); ?></a>
                    <a href="#customization" class="aqualuxe-theme-tab-nav"><?php esc_html_e( 'Customization', 'aqualuxe' ); ?></a>
                    <a href="#woocommerce" class="aqualuxe-theme-tab-nav"><?php esc_html_e( 'WooCommerce', 'aqualuxe' ); ?></a>
                    <a href="#demo-content" class="aqualuxe-theme-tab-nav"><?php esc_html_e( 'Demo Content', 'aqualuxe' ); ?></a>
                    <a href="#support" class="aqualuxe-theme-tab-nav"><?php esc_html_e( 'Support', 'aqualuxe' ); ?></a>
                </div>
                
                <div class="aqualuxe-theme-tabs-content">
                    <div id="getting-started" class="aqualuxe-theme-tab-content active">
                        <h3><?php esc_html_e( 'Getting Started with AquaLuxe', 'aqualuxe' ); ?></h3>
                        
                        <div class="aqualuxe-theme-card">
                            <h4><?php esc_html_e( '1. Install Required Plugins', 'aqualuxe' ); ?></h4>
                            <p><?php esc_html_e( 'AquaLuxe works best with the following plugins:', 'aqualuxe' ); ?></p>
                            <ul>
                                <li><?php esc_html_e( 'WooCommerce - For e-commerce functionality', 'aqualuxe' ); ?></li>
                                <li><?php esc_html_e( 'Elementor - For drag-and-drop page building (optional)', 'aqualuxe' ); ?></li>
                                <li><?php esc_html_e( 'Contact Form 7 - For contact forms', 'aqualuxe' ); ?></li>
                            </ul>
                            <a href="<?php echo esc_url( admin_url( 'themes.php?page=tgmpa-install-plugins' ) ); ?>" class="button button-primary"><?php esc_html_e( 'Install Plugins', 'aqualuxe' ); ?></a>
                        </div>
                        
                        <div class="aqualuxe-theme-card">
                            <h4><?php esc_html_e( '2. Import Demo Content', 'aqualuxe' ); ?></h4>
                            <p><?php esc_html_e( 'Import the demo content to get a head start on setting up your site.', 'aqualuxe' ); ?></p>
                            <a href="#demo-content" class="button button-secondary aqualuxe-theme-tab-link"><?php esc_html_e( 'Go to Demo Import', 'aqualuxe' ); ?></a>
                        </div>
                        
                        <div class="aqualuxe-theme-card">
                            <h4><?php esc_html_e( '3. Customize Your Site', 'aqualuxe' ); ?></h4>
                            <p><?php esc_html_e( 'Use the WordPress Customizer to configure your site\'s appearance and settings.', 'aqualuxe' ); ?></p>
                            <a href="<?php echo esc_url( admin_url( 'customize.php' ) ); ?>" class="button button-primary"><?php esc_html_e( 'Open Customizer', 'aqualuxe' ); ?></a>
                        </div>
                        
                        <div class="aqualuxe-theme-card">
                            <h4><?php esc_html_e( '4. Set Up WooCommerce', 'aqualuxe' ); ?></h4>
                            <p><?php esc_html_e( 'Configure your shop settings and add products.', 'aqualuxe' ); ?></p>
                            <a href="#woocommerce" class="button button-secondary aqualuxe-theme-tab-link"><?php esc_html_e( 'WooCommerce Setup', 'aqualuxe' ); ?></a>
                        </div>
                    </div>
                    
                    <div id="customization" class="aqualuxe-theme-tab-content">
                        <h3><?php esc_html_e( 'Customizing AquaLuxe', 'aqualuxe' ); ?></h3>
                        
                        <div class="aqualuxe-theme-card">
                            <h4><?php esc_html_e( 'Theme Options', 'aqualuxe' ); ?></h4>
                            <p><?php esc_html_e( 'AquaLuxe comes with extensive theme options to customize your site.', 'aqualuxe' ); ?></p>
                            <ul>
                                <li><?php esc_html_e( 'Colors - Customize the color scheme', 'aqualuxe' ); ?></li>
                                <li><?php esc_html_e( 'Typography - Choose fonts and sizes', 'aqualuxe' ); ?></li>
                                <li><?php esc_html_e( 'Layout - Configure site layout options', 'aqualuxe' ); ?></li>
                                <li><?php esc_html_e( 'Header - Customize the header appearance', 'aqualuxe' ); ?></li>
                                <li><?php esc_html_e( 'Footer - Set up footer widgets and content', 'aqualuxe' ); ?></li>
                                <li><?php esc_html_e( 'Blog - Configure blog display options', 'aqualuxe' ); ?></li>
                                <li><?php esc_html_e( 'WooCommerce - Customize shop appearance', 'aqualuxe' ); ?></li>
                            </ul>
                            <a href="<?php echo esc_url( admin_url( 'customize.php?panel=aqualuxe_theme_options' ) ); ?>" class="button button-primary"><?php esc_html_e( 'Theme Options', 'aqualuxe' ); ?></a>
                        </div>
                        
                        <div class="aqualuxe-theme-card">
                            <h4><?php esc_html_e( 'Dark Mode', 'aqualuxe' ); ?></h4>
                            <p><?php esc_html_e( 'AquaLuxe includes a built-in dark mode feature. You can customize the dark mode colors and set the default mode.', 'aqualuxe' ); ?></p>
                            <a href="<?php echo esc_url( admin_url( 'customize.php?autofocus[section]=aqualuxe_colors' ) ); ?>" class="button button-secondary"><?php esc_html_e( 'Dark Mode Settings', 'aqualuxe' ); ?></a>
                        </div>
                        
                        <div class="aqualuxe-theme-card">
                            <h4><?php esc_html_e( 'Custom CSS', 'aqualuxe' ); ?></h4>
                            <p><?php esc_html_e( 'Add custom CSS to further customize your site\'s appearance.', 'aqualuxe' ); ?></p>
                            <a href="<?php echo esc_url( admin_url( 'customize.php?autofocus[section]=aqualuxe_advanced' ) ); ?>" class="button button-secondary"><?php esc_html_e( 'Custom CSS', 'aqualuxe' ); ?></a>
                        </div>
                    </div>
                    
                    <div id="woocommerce" class="aqualuxe-theme-tab-content">
                        <h3><?php esc_html_e( 'WooCommerce Setup', 'aqualuxe' ); ?></h3>
                        
                        <div class="aqualuxe-theme-card">
                            <h4><?php esc_html_e( 'WooCommerce Settings', 'aqualuxe' ); ?></h4>
                            <p><?php esc_html_e( 'Configure your shop settings, shipping, payment methods, and more.', 'aqualuxe' ); ?></p>
                            <a href="<?php echo esc_url( admin_url( 'admin.php?page=wc-settings' ) ); ?>" class="button button-primary"><?php esc_html_e( 'WooCommerce Settings', 'aqualuxe' ); ?></a>
                        </div>
                        
                        <div class="aqualuxe-theme-card">
                            <h4><?php esc_html_e( 'Add Products', 'aqualuxe' ); ?></h4>
                            <p><?php esc_html_e( 'Start adding products to your shop.', 'aqualuxe' ); ?></p>
                            <a href="<?php echo esc_url( admin_url( 'post-new.php?post_type=product' ) ); ?>" class="button button-secondary"><?php esc_html_e( 'Add New Product', 'aqualuxe' ); ?></a>
                        </div>
                        
                        <div class="aqualuxe-theme-card">
                            <h4><?php esc_html_e( 'Product Categories', 'aqualuxe' ); ?></h4>
                            <p><?php esc_html_e( 'Organize your products into categories.', 'aqualuxe' ); ?></p>
                            <a href="<?php echo esc_url( admin_url( 'edit-tags.php?taxonomy=product_cat&post_type=product' ) ); ?>" class="button button-secondary"><?php esc_html_e( 'Product Categories', 'aqualuxe' ); ?></a>
                        </div>
                        
                        <div class="aqualuxe-theme-card">
                            <h4><?php esc_html_e( 'Custom Taxonomies', 'aqualuxe' ); ?></h4>
                            <p><?php esc_html_e( 'AquaLuxe includes custom taxonomies for fish species, water types, care levels, and more.', 'aqualuxe' ); ?></p>
                            <ul>
                                <li><a href="<?php echo esc_url( admin_url( 'edit-tags.php?taxonomy=fish_species&post_type=product' ) ); ?>"><?php esc_html_e( 'Fish Species', 'aqualuxe' ); ?></a></li>
                                <li><a href="<?php echo esc_url( admin_url( 'edit-tags.php?taxonomy=water_type&post_type=product' ) ); ?>"><?php esc_html_e( 'Water Types', 'aqualuxe' ); ?></a></li>
                                <li><a href="<?php echo esc_url( admin_url( 'edit-tags.php?taxonomy=care_level&post_type=product' ) ); ?>"><?php esc_html_e( 'Care Levels', 'aqualuxe' ); ?></a></li>
                                <li><a href="<?php echo esc_url( admin_url( 'edit-tags.php?taxonomy=plant_type&post_type=product' ) ); ?>"><?php esc_html_e( 'Plant Types', 'aqualuxe' ); ?></a></li>
                                <li><a href="<?php echo esc_url( admin_url( 'edit-tags.php?taxonomy=tank_size&post_type=product' ) ); ?>"><?php esc_html_e( 'Tank Sizes', 'aqualuxe' ); ?></a></li>
                            </ul>
                        </div>
                        
                        <div class="aqualuxe-theme-card">
                            <h4><?php esc_html_e( 'WooCommerce Appearance', 'aqualuxe' ); ?></h4>
                            <p><?php esc_html_e( 'Customize the appearance of your shop.', 'aqualuxe' ); ?></p>
                            <a href="<?php echo esc_url( admin_url( 'customize.php?autofocus[section]=aqualuxe_woocommerce' ) ); ?>" class="button button-secondary"><?php esc_html_e( 'Shop Appearance', 'aqualuxe' ); ?></a>
                        </div>
                    </div>
                    
                    <div id="demo-content" class="aqualuxe-theme-tab-content">
                        <h3><?php esc_html_e( 'Demo Content Import', 'aqualuxe' ); ?></h3>
                        
                        <div class="aqualuxe-theme-card">
                            <h4><?php esc_html_e( 'Import Demo Content', 'aqualuxe' ); ?></h4>
                            <p><?php esc_html_e( 'Import the demo content to get a head start on setting up your site. This will import pages, posts, products, menus, widgets, and customizer settings.', 'aqualuxe' ); ?></p>
                            <p><strong><?php esc_html_e( 'Note:', 'aqualuxe' ); ?></strong> <?php esc_html_e( 'It is recommended to import the demo content on a fresh installation to avoid conflicts with existing content.', 'aqualuxe' ); ?></p>
                            
                            <?php if ( class_exists( 'OCDI_Plugin' ) ) : ?>
                                <a href="<?php echo esc_url( admin_url( 'themes.php?page=pt-one-click-demo-import' ) ); ?>" class="button button-primary"><?php esc_html_e( 'Import Demo Content', 'aqualuxe' ); ?></a>
                            <?php else : ?>
                                <p><?php esc_html_e( 'Please install and activate the One Click Demo Import plugin to import the demo content.', 'aqualuxe' ); ?></p>
                                <a href="<?php echo esc_url( admin_url( 'themes.php?page=tgmpa-install-plugins' ) ); ?>" class="button button-primary"><?php esc_html_e( 'Install Plugins', 'aqualuxe' ); ?></a>
                            <?php endif; ?>
                        </div>
                        
                        <div class="aqualuxe-theme-card">
                            <h4><?php esc_html_e( 'Manual Setup', 'aqualuxe' ); ?></h4>
                            <p><?php esc_html_e( 'If you prefer to set up your site manually, follow these steps:', 'aqualuxe' ); ?></p>
                            <ol>
                                <li><?php esc_html_e( 'Create pages for Home, About, Services, Blog, Contact, etc.', 'aqualuxe' ); ?></li>
                                <li><?php esc_html_e( 'Set up your menus from Appearance > Menus.', 'aqualuxe' ); ?></li>
                                <li><?php esc_html_e( 'Configure widgets in Appearance > Widgets.', 'aqualuxe' ); ?></li>
                                <li><?php esc_html_e( 'Customize your site appearance in Appearance > Customize.', 'aqualuxe' ); ?></li>
                                <li><?php esc_html_e( 'Add products to your shop.', 'aqualuxe' ); ?></li>
                            </ol>
                        </div>
                    </div>
                    
                    <div id="support" class="aqualuxe-theme-tab-content">
                        <h3><?php esc_html_e( 'Support', 'aqualuxe' ); ?></h3>
                        
                        <div class="aqualuxe-theme-card">
                            <h4><?php esc_html_e( 'Documentation', 'aqualuxe' ); ?></h4>
                            <p><?php esc_html_e( 'Comprehensive documentation with detailed instructions on how to use the theme.', 'aqualuxe' ); ?></p>
                            <a href="#" class="button button-primary" target="_blank"><?php esc_html_e( 'Documentation', 'aqualuxe' ); ?></a>
                        </div>
                        
                        <div class="aqualuxe-theme-card">
                            <h4><?php esc_html_e( 'Support Forum', 'aqualuxe' ); ?></h4>
                            <p><?php esc_html_e( 'If you have any questions or need help with the theme, please visit our support forum.', 'aqualuxe' ); ?></p>
                            <a href="#" class="button button-secondary" target="_blank"><?php esc_html_e( 'Support Forum', 'aqualuxe' ); ?></a>
                        </div>
                        
                        <div class="aqualuxe-theme-card">
                            <h4><?php esc_html_e( 'Video Tutorials', 'aqualuxe' ); ?></h4>
                            <p><?php esc_html_e( 'Watch video tutorials on how to set up and use the theme.', 'aqualuxe' ); ?></p>
                            <a href="#" class="button button-secondary" target="_blank"><?php esc_html_e( 'Video Tutorials', 'aqualuxe' ); ?></a>
                        </div>
                        
                        <div class="aqualuxe-theme-card">
                            <h4><?php esc_html_e( 'Contact Us', 'aqualuxe' ); ?></h4>
                            <p><?php esc_html_e( 'If you need direct support, please contact us.', 'aqualuxe' ); ?></p>
                            <a href="#" class="button button-secondary" target="_blank"><?php esc_html_e( 'Contact Us', 'aqualuxe' ); ?></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        jQuery(document).ready(function($) {
            // Tab navigation
            $('.aqualuxe-theme-tab-nav').on('click', function(e) {
                e.preventDefault();
                
                var target = $(this).attr('href');
                
                // Update active tab nav
                $('.aqualuxe-theme-tab-nav').removeClass('active');
                $(this).addClass('active');
                
                // Show target tab content
                $('.aqualuxe-theme-tab-content').removeClass('active');
                $(target).addClass('active');
            });
            
            // Tab links within content
            $('.aqualuxe-theme-tab-link').on('click', function(e) {
                e.preventDefault();
                
                var target = $(this).attr('href');
                
                // Update active tab nav
                $('.aqualuxe-theme-tab-nav').removeClass('active');
                $('.aqualuxe-theme-tab-nav[href="' + target + '"]').addClass('active');
                
                // Show target tab content
                $('.aqualuxe-theme-tab-content').removeClass('active');
                $(target).addClass('active');
                
                // Scroll to top of tabs
                $('html, body').animate({
                    scrollTop: $('.aqualuxe-theme-tabs').offset().top - 30
                }, 300);
            });
        });
    </script>
    
    <style>
        .aqualuxe-theme-page {
            max-width: 1200px;
            margin: 20px auto;
        }
        
        .aqualuxe-theme-info {
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        
        .aqualuxe-theme-info-header {
            background-color: #0891B2;
            color: #fff;
            padding: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .aqualuxe-theme-info-header-left {
            flex: 1;
        }
        
        .aqualuxe-theme-info-header-left h2 {
            margin-top: 0;
            color: #fff;
        }
        
        .aqualuxe-theme-info-header-left p {
            margin-bottom: 0;
            font-size: 16px;
        }
        
        .aqualuxe-theme-version {
            background-color: rgba(255, 255, 255, 0.2);
            padding: 5px 10px;
            border-radius: 3px;
            font-size: 14px;
        }
        
        .aqualuxe-theme-tabs-nav {
            display: flex;
            background-color: #f8f9fa;
            border-bottom: 1px solid #e2e4e7;
        }
        
        .aqualuxe-theme-tab-nav {
            padding: 15px 20px;
            text-decoration: none;
            color: #646970;
            font-weight: 500;
            border-bottom: 3px solid transparent;
        }
        
        .aqualuxe-theme-tab-nav:hover {
            color: #0891B2;
        }
        
        .aqualuxe-theme-tab-nav.active {
            color: #0891B2;
            border-bottom-color: #0891B2;
        }
        
        .aqualuxe-theme-tabs-content {
            padding: 30px;
        }
        
        .aqualuxe-theme-tab-content {
            display: none;
        }
        
        .aqualuxe-theme-tab-content.active {
            display: block;
        }
        
        .aqualuxe-theme-card {
            background-color: #f8f9fa;
            border-radius: 5px;
            padding: 20px;
            margin-bottom: 20px;
        }
        
        .aqualuxe-theme-card h4 {
            margin-top: 0;
            color: #1e1e1e;
        }
        
        .aqualuxe-theme-card ul {
            margin-bottom: 20px;
        }
        
        .aqualuxe-theme-card .button {
            margin-right: 10px;
        }
    </style>
    <?php
}

/**
 * Add meta box for page settings
 */
function aqualuxe_add_page_settings_meta_box() {
    add_meta_box(
        'aqualuxe_page_settings',
        __( 'Page Settings', 'aqualuxe' ),
        'aqualuxe_page_settings_meta_box_callback',
        'page',
        'side',
        'high'
    );
}
add_action( 'add_meta_boxes', 'aqualuxe_add_page_settings_meta_box' );

/**
 * Page settings meta box callback
 */
function aqualuxe_page_settings_meta_box_callback( $post ) {
    wp_nonce_field( 'aqualuxe_page_settings', 'aqualuxe_page_settings_nonce' );

    $page_layout = get_post_meta( $post->ID, '_aqualuxe_page_layout', true );
    $hide_title = get_post_meta( $post->ID, '_aqualuxe_hide_title', true );
    $hide_breadcrumbs = get_post_meta( $post->ID, '_aqualuxe_hide_breadcrumbs', true );
    $hide_footer_widgets = get_post_meta( $post->ID, '_aqualuxe_hide_footer_widgets', true );
    $transparent_header = get_post_meta( $post->ID, '_aqualuxe_transparent_header', true );
    $custom_header_color = get_post_meta( $post->ID, '_aqualuxe_custom_header_color', true );

    ?>
    <p>
        <label for="aqualuxe_page_layout"><?php esc_html_e( 'Page Layout', 'aqualuxe' ); ?></label><br>
        <select name="aqualuxe_page_layout" id="aqualuxe_page_layout" class="widefat">
            <option value=""><?php esc_html_e( 'Default', 'aqualuxe' ); ?></option>
            <option value="full-width" <?php selected( $page_layout, 'full-width' ); ?>><?php esc_html_e( 'Full Width', 'aqualuxe' ); ?></option>
            <option value="right-sidebar" <?php selected( $page_layout, 'right-sidebar' ); ?>><?php esc_html_e( 'Right Sidebar', 'aqualuxe' ); ?></option>
            <option value="left-sidebar" <?php selected( $page_layout, 'left-sidebar' ); ?>><?php esc_html_e( 'Left Sidebar', 'aqualuxe' ); ?></option>
            <option value="no-sidebar" <?php selected( $page_layout, 'no-sidebar' ); ?>><?php esc_html_e( 'No Sidebar', 'aqualuxe' ); ?></option>
        </select>
    </p>
    <p>
        <input type="checkbox" id="aqualuxe_hide_title" name="aqualuxe_hide_title" value="1" <?php checked( $hide_title, '1' ); ?> />
        <label for="aqualuxe_hide_title"><?php esc_html_e( 'Hide Page Title', 'aqualuxe' ); ?></label>
    </p>
    <p>
        <input type="checkbox" id="aqualuxe_hide_breadcrumbs" name="aqualuxe_hide_breadcrumbs" value="1" <?php checked( $hide_breadcrumbs, '1' ); ?> />
        <label for="aqualuxe_hide_breadcrumbs"><?php esc_html_e( 'Hide Breadcrumbs', 'aqualuxe' ); ?></label>
    </p>
    <p>
        <input type="checkbox" id="aqualuxe_hide_footer_widgets" name="aqualuxe_hide_footer_widgets" value="1" <?php checked( $hide_footer_widgets, '1' ); ?> />
        <label for="aqualuxe_hide_footer_widgets"><?php esc_html_e( 'Hide Footer Widgets', 'aqualuxe' ); ?></label>
    </p>
    <p>
        <input type="checkbox" id="aqualuxe_transparent_header" name="aqualuxe_transparent_header" value="1" <?php checked( $transparent_header, '1' ); ?> />
        <label for="aqualuxe_transparent_header"><?php esc_html_e( 'Transparent Header', 'aqualuxe' ); ?></label>
    </p>
    <p>
        <label for="aqualuxe_custom_header_color"><?php esc_html_e( 'Custom Header Color', 'aqualuxe' ); ?></label><br>
        <input type="text" id="aqualuxe_custom_header_color" name="aqualuxe_custom_header_color" value="<?php echo esc_attr( $custom_header_color ); ?>" class="widefat color-picker" />
        <span class="description"><?php esc_html_e( 'Leave empty for default', 'aqualuxe' ); ?></span>
    </p>
    <?php
}

/**
 * Save page settings meta box data
 */
function aqualuxe_save_page_settings_meta_box_data( $post_id ) {
    // Check if our nonce is set
    if ( ! isset( $_POST['aqualuxe_page_settings_nonce'] ) ) {
        return;
    }

    // Verify that the nonce is valid
    if ( ! wp_verify_nonce( $_POST['aqualuxe_page_settings_nonce'], 'aqualuxe_page_settings' ) ) {
        return;
    }

    // If this is an autosave, our form has not been submitted, so we don't want to do anything
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    // Check the user's permissions
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    // Save page layout
    if ( isset( $_POST['aqualuxe_page_layout'] ) ) {
        update_post_meta( $post_id, '_aqualuxe_page_layout', sanitize_text_field( $_POST['aqualuxe_page_layout'] ) );
    }

    // Save hide title
    $hide_title = isset( $_POST['aqualuxe_hide_title'] ) ? '1' : '';
    update_post_meta( $post_id, '_aqualuxe_hide_title', $hide_title );

    // Save hide breadcrumbs
    $hide_breadcrumbs = isset( $_POST['aqualuxe_hide_breadcrumbs'] ) ? '1' : '';
    update_post_meta( $post_id, '_aqualuxe_hide_breadcrumbs', $hide_breadcrumbs );

    // Save hide footer widgets
    $hide_footer_widgets = isset( $_POST['aqualuxe_hide_footer_widgets'] ) ? '1' : '';
    update_post_meta( $post_id, '_aqualuxe_hide_footer_widgets', $hide_footer_widgets );

    // Save transparent header
    $transparent_header = isset( $_POST['aqualuxe_transparent_header'] ) ? '1' : '';
    update_post_meta( $post_id, '_aqualuxe_transparent_header', $transparent_header );

    // Save custom header color
    if ( isset( $_POST['aqualuxe_custom_header_color'] ) ) {
        update_post_meta( $post_id, '_aqualuxe_custom_header_color', sanitize_text_field( $_POST['aqualuxe_custom_header_color'] ) );
    }
}
add_action( 'save_post', 'aqualuxe_save_page_settings_meta_box_data' );

/**
 * Add theme info to admin footer
 */
function aqualuxe_admin_footer_text( $text ) {
    $screen = get_current_screen();
    
    if ( $screen && ( $screen->id === 'appearance_page_aqualuxe-theme' || $screen->id === 'toplevel_page_aqualuxe-theme' ) ) {
        $text = sprintf(
            __( 'Thank you for using <a href="%s" target="_blank">AquaLuxe</a> theme by <a href="%s" target="_blank">NinjaTech AI</a>.', 'aqualuxe' ),
            'https://aqualuxe.example.com/',
            'https://ninjatech.ai/'
        );
    }
    
    return $text;
}
add_filter( 'admin_footer_text', 'aqualuxe_admin_footer_text' );

/**
 * Add dashboard widget
 */
function aqualuxe_add_dashboard_widget() {
    wp_add_dashboard_widget(
        'aqualuxe_dashboard_widget',
        __( 'AquaLuxe Theme', 'aqualuxe' ),
        'aqualuxe_dashboard_widget_content'
    );
}
add_action( 'wp_dashboard_setup', 'aqualuxe_add_dashboard_widget' );

/**
 * Dashboard widget content
 */
function aqualuxe_dashboard_widget_content() {
    ?>
    <div class="aqualuxe-dashboard-widget">
        <div class="aqualuxe-dashboard-widget-header">
            <h3><?php esc_html_e( 'Welcome to AquaLuxe Theme', 'aqualuxe' ); ?></h3>
            <p><?php esc_html_e( 'Thank you for choosing AquaLuxe! Here are some quick links to help you get started.', 'aqualuxe' ); ?></p>
        </div>
        
        <div class="aqualuxe-dashboard-widget-content">
            <ul>
                <li><a href="<?php echo esc_url( admin_url( 'themes.php?page=aqualuxe-theme' ) ); ?>"><?php esc_html_e( 'Theme Info', 'aqualuxe' ); ?></a></li>
                <li><a href="<?php echo esc_url( admin_url( 'customize.php' ) ); ?>"><?php esc_html_e( 'Customize Theme', 'aqualuxe' ); ?></a></li>
                <li><a href="<?php echo esc_url( admin_url( 'themes.php?page=tgmpa-install-plugins' ) ); ?>"><?php esc_html_e( 'Install Plugins', 'aqualuxe' ); ?></a></li>
                <li><a href="<?php echo esc_url( admin_url( 'themes.php?page=pt-one-click-demo-import' ) ); ?>"><?php esc_html_e( 'Import Demo Content', 'aqualuxe' ); ?></a></li>
            </ul>
        </div>
    </div>
    
    <style>
        .aqualuxe-dashboard-widget-header {
            margin-bottom: 15px;
        }
        
        .aqualuxe-dashboard-widget-header h3 {
            margin-top: 0;
        }
        
        .aqualuxe-dashboard-widget-content ul {
            margin-bottom: 0;
        }
        
        .aqualuxe-dashboard-widget-content ul li {
            margin-bottom: 8px;
        }
    </style>
    <?php
}

/**
 * Add admin notices
 */
function aqualuxe_admin_notices() {
    // Check if WooCommerce is active
    if ( ! class_exists( 'WooCommerce' ) ) {
        ?>
        <div class="notice notice-warning is-dismissible">
            <p><?php esc_html_e( 'AquaLuxe theme works best with WooCommerce plugin. Please install and activate WooCommerce for full theme functionality.', 'aqualuxe' ); ?></p>
            <p><a href="<?php echo esc_url( admin_url( 'themes.php?page=tgmpa-install-plugins' ) ); ?>" class="button button-primary"><?php esc_html_e( 'Install Plugins', 'aqualuxe' ); ?></a></p>
        </div>
        <?php
    }
}
add_action( 'admin_notices', 'aqualuxe_admin_notices' );

/**
 * Add theme options to admin bar
 */
function aqualuxe_admin_bar_theme_options( $wp_admin_bar ) {
    if ( ! is_super_admin() || ! is_admin_bar_showing() ) {
        return;
    }

    $wp_admin_bar->add_node( array(
        'id'    => 'aqualuxe-theme-options',
        'title' => __( 'AquaLuxe Options', 'aqualuxe' ),
        'href'  => admin_url( 'themes.php?page=aqualuxe-theme' ),
    ) );

    $wp_admin_bar->add_node( array(
        'id'     => 'aqualuxe-theme-customizer',
        'parent' => 'aqualuxe-theme-options',
        'title'  => __( 'Customize Theme', 'aqualuxe' ),
        'href'   => admin_url( 'customize.php' ),
    ) );

    $wp_admin_bar->add_node( array(
        'id'     => 'aqualuxe-theme-plugins',
        'parent' => 'aqualuxe-theme-options',
        'title'  => __( 'Install Plugins', 'aqualuxe' ),
        'href'   => admin_url( 'themes.php?page=tgmpa-install-plugins' ),
    ) );

    $wp_admin_bar->add_node( array(
        'id'     => 'aqualuxe-theme-demo-import',
        'parent' => 'aqualuxe-theme-options',
        'title'  => __( 'Import Demo Content', 'aqualuxe' ),
        'href'   => admin_url( 'themes.php?page=pt-one-click-demo-import' ),
    ) );
}
add_action( 'admin_bar_menu', 'aqualuxe_admin_bar_theme_options', 100 );

/**
 * Register required plugins for the theme
 */
function aqualuxe_register_required_plugins() {
    $plugins = array(
        array(
            'name'     => 'WooCommerce',
            'slug'     => 'woocommerce',
            'required' => true,
        ),
        array(
            'name'     => 'Elementor',
            'slug'     => 'elementor',
            'required' => false,
        ),
        array(
            'name'     => 'Contact Form 7',
            'slug'     => 'contact-form-7',
            'required' => false,
        ),
        array(
            'name'     => 'One Click Demo Import',
            'slug'     => 'one-click-demo-import',
            'required' => false,
        ),
        array(
            'name'     => 'Yoast SEO',
            'slug'     => 'wordpress-seo',
            'required' => false,
        ),
        array(
            'name'     => 'WP Super Cache',
            'slug'     => 'wp-super-cache',
            'required' => false,
        ),
        array(
            'name'     => 'Regenerate Thumbnails',
            'slug'     => 'regenerate-thumbnails',
            'required' => false,
        ),
    );

    $config = array(
        'id'           => 'aqualuxe',
        'default_path' => '',
        'menu'         => 'tgmpa-install-plugins',
        'parent_slug'  => 'themes.php',
        'capability'   => 'edit_theme_options',
        'has_notices'  => true,
        'dismissable'  => true,
        'dismiss_msg'  => '',
        'is_automatic' => false,
        'message'      => '',
    );

    tgmpa( $plugins, $config );
}
add_action( 'tgmpa_register', 'aqualuxe_register_required_plugins' );

/**
 * Configure One Click Demo Import
 */
function aqualuxe_ocdi_import_files() {
    return array(
        array(
            'import_file_name'           => 'AquaLuxe Demo',
            'import_file_url'            => 'https://aqualuxe.example.com/demo/content.xml',
            'import_widget_file_url'     => 'https://aqualuxe.example.com/demo/widgets.wie',
            'import_customizer_file_url' => 'https://aqualuxe.example.com/demo/customizer.dat',
            'import_preview_image_url'   => 'https://aqualuxe.example.com/demo/preview.jpg',
            'preview_url'                => 'https://aqualuxe.example.com/demo/',
        ),
    );
}
add_filter( 'pt-ocdi/import_files', 'aqualuxe_ocdi_import_files' );

/**
 * Actions to perform after demo import
 */
function aqualuxe_ocdi_after_import_setup() {
    // Assign menus to their locations
    $main_menu = get_term_by( 'name', 'Main Menu', 'nav_menu' );
    $footer_menu = get_term_by( 'name', 'Footer Menu', 'nav_menu' );

    set_theme_mod( 'nav_menu_locations', array(
        'primary' => $main_menu->term_id,
        'footer'  => $footer_menu->term_id,
    ) );

    // Assign front page and posts page
    $front_page = get_page_by_title( 'Home' );
    $blog_page = get_page_by_title( 'Blog' );

    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page->ID );
    update_option( 'page_for_posts', $blog_page->ID );

    // Update WooCommerce pages
    $shop_page = get_page_by_title( 'Shop' );
    $cart_page = get_page_by_title( 'Cart' );
    $checkout_page = get_page_by_title( 'Checkout' );
    $account_page = get_page_by_title( 'My Account' );

    update_option( 'woocommerce_shop_page_id', $shop_page->ID );
    update_option( 'woocommerce_cart_page_id', $cart_page->ID );
    update_option( 'woocommerce_checkout_page_id', $checkout_page->ID );
    update_option( 'woocommerce_myaccount_page_id', $account_page->ID );

    // Update Elementor settings
    update_option( 'elementor_disable_color_schemes', 'yes' );
    update_option( 'elementor_disable_typography_schemes', 'yes' );
    update_option( 'elementor_container_width', 1280 );
    update_option( 'elementor_viewport_lg', 1025 );
    update_option( 'elementor_viewport_md', 768 );

    // Flush rewrite rules
    flush_rewrite_rules();
}
add_action( 'pt-ocdi/after_import', 'aqualuxe_ocdi_after_import_setup' );

/**
 * Disable generation of smaller images during demo import
 */
function aqualuxe_ocdi_regenerate_thumbnails_in_content_import( $skip_thumbnails ) {
    return true;
}
add_filter( 'pt-ocdi/regenerate_thumbnails_in_content_import', 'aqualuxe_ocdi_regenerate_thumbnails_in_content_import' );

/**
 * Change the location, title and other parameters of the plugin page
 */
function aqualuxe_ocdi_plugin_page_setup( $default_settings ) {
    $default_settings['parent_slug'] = 'themes.php';
    $default_settings['page_title']  = esc_html__( 'AquaLuxe Demo Import', 'aqualuxe' );
    $default_settings['menu_title']  = esc_html__( 'Import Demo Data', 'aqualuxe' );
    $default_settings['capability']  = 'import';
    $default_settings['menu_slug']   = 'aqualuxe-demo-import';

    return $default_settings;
}
add_filter( 'pt-ocdi/plugin_page_setup', 'aqualuxe_ocdi_plugin_page_setup' );

/**
 * Change the intro text on the demo import page
 */
function aqualuxe_ocdi_plugin_intro_text( $default_text ) {
    $default_text = '<div class="ocdi__intro-text">';
    $default_text .= '<h2>' . esc_html__( 'AquaLuxe Demo Import', 'aqualuxe' ) . '</h2>';
    $default_text .= '<p>' . esc_html__( 'Importing demo data is the easiest way to setup your theme. It will allow you to quickly edit everything instead of creating content from scratch.', 'aqualuxe' ) . '</p>';
    $default_text .= '<p>' . esc_html__( 'When you import the data, the following things will happen:', 'aqualuxe' ) . '</p>';
    $default_text .= '<ul>';
    $default_text .= '<li>' . esc_html__( 'All existing posts, pages, categories, images, custom post types or custom taxonomies will NOT be deleted or modified.', 'aqualuxe' ) . '</li>';
    $default_text .= '<li>' . esc_html__( 'Posts, pages, images, widgets, menus and other theme settings will get imported.', 'aqualuxe' ) . '</li>';
    $default_text .= '<li>' . esc_html__( 'Please click on the Import button only once and wait, it can take a couple of minutes.', 'aqualuxe' ) . '</li>';
    $default_text .= '</ul>';
    $default_text .= '</div>';

    return $default_text;
}
add_filter( 'pt-ocdi/plugin_intro_text', 'aqualuxe_ocdi_plugin_intro_text' );

/**
 * Add admin color scheme
 */
function aqualuxe_admin_color_scheme() {
    wp_admin_css_color(
        'aqualuxe',
        __( 'AquaLuxe', 'aqualuxe' ),
        AQUALUXE_URI . 'assets/css/admin-color-scheme.css',
        array( '#0891B2', '#0E7490', '#14B8A6', '#0D9488' ),
        array(
            'base'    => '#f1f1f1',
            'focus'   => '#fff',
            'current' => '#fff',
        )
    );
}
add_action( 'admin_init', 'aqualuxe_admin_color_scheme' );