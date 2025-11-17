<?php
/**
 * AquaLuxe Admin Functions
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

/**
 * Add theme info page to the admin menu
 */
function aqualuxe_add_theme_page() {
    add_theme_page(
        esc_html__('AquaLuxe Theme', 'aqualuxe'),
        esc_html__('AquaLuxe Theme', 'aqualuxe'),
        'manage_options',
        'aqualuxe-theme',
        'aqualuxe_theme_page_content'
    );
}
add_action('admin_menu', 'aqualuxe_add_theme_page');

/**
 * Display theme info page content
 */
function aqualuxe_theme_page_content() {
    ?>
    <div class="wrap aqualuxe-theme-page">
        <h1><?php esc_html_e('Welcome to AquaLuxe Theme', 'aqualuxe'); ?></h1>
        
        <div class="aqualuxe-theme-info">
            <div class="aqualuxe-theme-description">
                <h2><?php esc_html_e('Premium Ornamental Aquatic Solutions Theme', 'aqualuxe'); ?></h2>
                <p><?php esc_html_e('AquaLuxe is a premium WordPress theme designed specifically for ornamental fish farming businesses targeting local and international markets. The theme combines elegant aquatic visuals with powerful e-commerce capabilities to create a stunning online presence for your aquatic business.', 'aqualuxe'); ?></p>
            </div>
            
            <div class="aqualuxe-theme-version">
                <span><?php esc_html_e('Version', 'aqualuxe'); ?>: <?php echo AQUALUXE_VERSION; ?></span>
            </div>
        </div>
        
        <div class="aqualuxe-theme-tabs">
            <nav class="nav-tab-wrapper">
                <a href="#getting-started" class="nav-tab nav-tab-active"><?php esc_html_e('Getting Started', 'aqualuxe'); ?></a>
                <a href="#theme-features" class="nav-tab"><?php esc_html_e('Theme Features', 'aqualuxe'); ?></a>
                <a href="#customization" class="nav-tab"><?php esc_html_e('Customization', 'aqualuxe'); ?></a>
                <a href="#woocommerce" class="nav-tab"><?php esc_html_e('WooCommerce', 'aqualuxe'); ?></a>
                <a href="#support" class="nav-tab"><?php esc_html_e('Support', 'aqualuxe'); ?></a>
            </nav>
            
            <div class="aqualuxe-tab-content" id="getting-started">
                <h2><?php esc_html_e('Getting Started with AquaLuxe', 'aqualuxe'); ?></h2>
                
                <div class="aqualuxe-column-container">
                    <div class="aqualuxe-column">
                        <h3><?php esc_html_e('Step 1: Install Required Plugins', 'aqualuxe'); ?></h3>
                        <p><?php esc_html_e('AquaLuxe works best with the following plugins:', 'aqualuxe'); ?></p>
                        <ul>
                            <li><?php esc_html_e('WooCommerce - For e-commerce functionality', 'aqualuxe'); ?></li>
                            <li><?php esc_html_e('Elementor - For drag-and-drop page building (optional)', 'aqualuxe'); ?></li>
                            <li><?php esc_html_e('Contact Form 7 - For contact forms', 'aqualuxe'); ?></li>
                            <li><?php esc_html_e('Yoast SEO - For search engine optimization', 'aqualuxe'); ?></li>
                        </ul>
                        <p><a href="<?php echo esc_url(admin_url('themes.php?page=tgmpa-install-plugins')); ?>" class="button button-primary"><?php esc_html_e('Install Plugins', 'aqualuxe'); ?></a></p>
                    </div>
                    
                    <div class="aqualuxe-column">
                        <h3><?php esc_html_e('Step 2: Import Demo Content', 'aqualuxe'); ?></h3>
                        <p><?php esc_html_e('Import the demo content to get started quickly with a pre-designed site.', 'aqualuxe'); ?></p>
                        <p><a href="<?php echo esc_url(admin_url('themes.php?page=aqualuxe-demo-import')); ?>" class="button button-primary"><?php esc_html_e('Import Demo Content', 'aqualuxe'); ?></a></p>
                        
                        <h3><?php esc_html_e('Step 3: Customize Your Site', 'aqualuxe'); ?></h3>
                        <p><?php esc_html_e('Use the WordPress Customizer to personalize your site.', 'aqualuxe'); ?></p>
                        <p><a href="<?php echo esc_url(admin_url('customize.php')); ?>" class="button button-primary"><?php esc_html_e('Customize Your Site', 'aqualuxe'); ?></a></p>
                    </div>
                </div>
            </div>
            
            <div class="aqualuxe-tab-content" id="theme-features" style="display: none;">
                <h2><?php esc_html_e('AquaLuxe Theme Features', 'aqualuxe'); ?></h2>
                
                <div class="aqualuxe-features-grid">
                    <div class="aqualuxe-feature">
                        <h3><?php esc_html_e('Responsive Design', 'aqualuxe'); ?></h3>
                        <p><?php esc_html_e('AquaLuxe is fully responsive and looks great on all devices, from mobile phones to desktop computers.', 'aqualuxe'); ?></p>
                    </div>
                    
                    <div class="aqualuxe-feature">
                        <h3><?php esc_html_e('WooCommerce Integration', 'aqualuxe'); ?></h3>
                        <p><?php esc_html_e('Full WooCommerce support with custom styling for shop pages, product listings, cart, checkout, and more.', 'aqualuxe'); ?></p>
                    </div>
                    
                    <div class="aqualuxe-feature">
                        <h3><?php esc_html_e('Custom Post Types', 'aqualuxe'); ?></h3>
                        <p><?php esc_html_e('Includes custom post types for Services, Events, Projects, Testimonials, and Team Members.', 'aqualuxe'); ?></p>
                    </div>
                    
                    <div class="aqualuxe-feature">
                        <h3><?php esc_html_e('Dark Mode Support', 'aqualuxe'); ?></h3>
                        <p><?php esc_html_e('Built-in dark mode toggle with user preference persistence.', 'aqualuxe'); ?></p>
                    </div>
                    
                    <div class="aqualuxe-feature">
                        <h3><?php esc_html_e('Multilingual Ready', 'aqualuxe'); ?></h3>
                        <p><?php esc_html_e('Compatible with popular translation plugins like WPML and Polylang.', 'aqualuxe'); ?></p>
                    </div>
                    
                    <div class="aqualuxe-feature">
                        <h3><?php esc_html_e('SEO Optimized', 'aqualuxe'); ?></h3>
                        <p><?php esc_html_e('Built with SEO best practices in mind, including schema.org markup and clean code.', 'aqualuxe'); ?></p>
                    </div>
                    
                    <div class="aqualuxe-feature">
                        <h3><?php esc_html_e('Custom Widgets', 'aqualuxe'); ?></h3>
                        <p><?php esc_html_e('Includes custom widgets for recent posts, testimonials, and more.', 'aqualuxe'); ?></p>
                    </div>
                    
                    <div class="aqualuxe-feature">
                        <h3><?php esc_html_e('Wishlist Functionality', 'aqualuxe'); ?></h3>
                        <p><?php esc_html_e('Built-in wishlist feature for customers to save products for later.', 'aqualuxe'); ?></p>
                    </div>
                </div>
            </div>
            
            <div class="aqualuxe-tab-content" id="customization" style="display: none;">
                <h2><?php esc_html_e('Customizing AquaLuxe Theme', 'aqualuxe'); ?></h2>
                
                <div class="aqualuxe-column-container">
                    <div class="aqualuxe-column">
                        <h3><?php esc_html_e('Theme Customizer', 'aqualuxe'); ?></h3>
                        <p><?php esc_html_e('AquaLuxe includes extensive customization options in the WordPress Customizer:', 'aqualuxe'); ?></p>
                        <ul>
                            <li><?php esc_html_e('Colors - Customize primary and secondary colors', 'aqualuxe'); ?></li>
                            <li><?php esc_html_e('Header Options - Choose header style and settings', 'aqualuxe'); ?></li>
                            <li><?php esc_html_e('Footer Options - Configure footer columns and content', 'aqualuxe'); ?></li>
                            <li><?php esc_html_e('Homepage Options - Customize hero section and featured content', 'aqualuxe'); ?></li>
                            <li><?php esc_html_e('Shop Options - Configure WooCommerce display settings', 'aqualuxe'); ?></li>
                            <li><?php esc_html_e('Social Media - Add your social media profiles', 'aqualuxe'); ?></li>
                            <li><?php esc_html_e('Contact Information - Add your business contact details', 'aqualuxe'); ?></li>
                        </ul>
                        <p><a href="<?php echo esc_url(admin_url('customize.php')); ?>" class="button button-primary"><?php esc_html_e('Open Customizer', 'aqualuxe'); ?></a></p>
                    </div>
                    
                    <div class="aqualuxe-column">
                        <h3><?php esc_html_e('Page Templates', 'aqualuxe'); ?></h3>
                        <p><?php esc_html_e('AquaLuxe includes several page templates:', 'aqualuxe'); ?></p>
                        <ul>
                            <li><?php esc_html_e('Default Template - Standard page with sidebar', 'aqualuxe'); ?></li>
                            <li><?php esc_html_e('Full Width Template - Page without sidebar', 'aqualuxe'); ?></li>
                            <li><?php esc_html_e('Homepage Template - Special template for homepage with sections', 'aqualuxe'); ?></li>
                            <li><?php esc_html_e('Services Template - Display services in a grid layout', 'aqualuxe'); ?></li>
                            <li><?php esc_html_e('Projects Template - Display projects in a portfolio layout', 'aqualuxe'); ?></li>
                            <li><?php esc_html_e('Team Template - Display team members in a grid', 'aqualuxe'); ?></li>
                            <li><?php esc_html_e('Contact Template - Page with contact form and map', 'aqualuxe'); ?></li>
                        </ul>
                        
                        <h3><?php esc_html_e('Custom CSS', 'aqualuxe'); ?></h3>
                        <p><?php esc_html_e('Add custom CSS in the Customizer to further personalize your site.', 'aqualuxe'); ?></p>
                        <p><a href="<?php echo esc_url(admin_url('customize.php?autofocus[section]=custom_css')); ?>" class="button button-secondary"><?php esc_html_e('Add Custom CSS', 'aqualuxe'); ?></a></p>
                    </div>
                </div>
            </div>
            
            <div class="aqualuxe-tab-content" id="woocommerce" style="display: none;">
                <h2><?php esc_html_e('WooCommerce Integration', 'aqualuxe'); ?></h2>
                
                <div class="aqualuxe-column-container">
                    <div class="aqualuxe-column">
                        <h3><?php esc_html_e('Shop Features', 'aqualuxe'); ?></h3>
                        <p><?php esc_html_e('AquaLuxe includes enhanced WooCommerce features:', 'aqualuxe'); ?></p>
                        <ul>
                            <li><?php esc_html_e('Custom Product Layouts - Beautifully styled product pages', 'aqualuxe'); ?></li>
                            <li><?php esc_html_e('Quick View - View product details in a modal', 'aqualuxe'); ?></li>
                            <li><?php esc_html_e('Wishlist - Save products for later', 'aqualuxe'); ?></li>
                            <li><?php esc_html_e('Enhanced Cart - Improved cart experience', 'aqualuxe'); ?></li>
                            <li><?php esc_html_e('Streamlined Checkout - Optimized checkout process', 'aqualuxe'); ?></li>
                            <li><?php esc_html_e('Custom Product Tabs - Additional product information tabs', 'aqualuxe'); ?></li>
                        </ul>
                        
                        <h3><?php esc_html_e('Setting Up Your Shop', 'aqualuxe'); ?></h3>
                        <p><?php esc_html_e('Follow these steps to set up your online store:', 'aqualuxe'); ?></p>
                        <ol>
                            <li><?php esc_html_e('Install and activate WooCommerce', 'aqualuxe'); ?></li>
                            <li><?php esc_html_e('Complete the WooCommerce setup wizard', 'aqualuxe'); ?></li>
                            <li><?php esc_html_e('Add your products with high-quality images', 'aqualuxe'); ?></li>
                            <li><?php esc_html_e('Configure shipping and payment methods', 'aqualuxe'); ?></li>
                            <li><?php esc_html_e('Customize shop pages in the Theme Customizer', 'aqualuxe'); ?></li>
                        </ol>
                    </div>
                    
                    <div class="aqualuxe-column">
                        <h3><?php esc_html_e('WooCommerce Settings', 'aqualuxe'); ?></h3>
                        <p><?php esc_html_e('Configure your shop settings in the WooCommerce menu:', 'aqualuxe'); ?></p>
                        <p><a href="<?php echo esc_url(admin_url('admin.php?page=wc-settings')); ?>" class="button button-primary"><?php esc_html_e('WooCommerce Settings', 'aqualuxe'); ?></a></p>
                        
                        <h3><?php esc_html_e('Theme Shop Settings', 'aqualuxe'); ?></h3>
                        <p><?php esc_html_e('Customize the appearance of your shop in the Theme Customizer:', 'aqualuxe'); ?></p>
                        <p><a href="<?php echo esc_url(admin_url('customize.php?autofocus[section]=aqualuxe_shop')); ?>" class="button button-primary"><?php esc_html_e('Shop Customizer', 'aqualuxe'); ?></a></p>
                        
                        <h3><?php esc_html_e('Product Management', 'aqualuxe'); ?></h3>
                        <p><?php esc_html_e('Manage your products, categories, and attributes:', 'aqualuxe'); ?></p>
                        <p><a href="<?php echo esc_url(admin_url('edit.php?post_type=product')); ?>" class="button button-secondary"><?php esc_html_e('Manage Products', 'aqualuxe'); ?></a></p>
                        
                        <h3><?php esc_html_e('Orders', 'aqualuxe'); ?></h3>
                        <p><?php esc_html_e('View and manage customer orders:', 'aqualuxe'); ?></p>
                        <p><a href="<?php echo esc_url(admin_url('edit.php?post_type=shop_order')); ?>" class="button button-secondary"><?php esc_html_e('Manage Orders', 'aqualuxe'); ?></a></p>
                    </div>
                </div>
            </div>
            
            <div class="aqualuxe-tab-content" id="support" style="display: none;">
                <h2><?php esc_html_e('Theme Support', 'aqualuxe'); ?></h2>
                
                <div class="aqualuxe-column-container">
                    <div class="aqualuxe-column">
                        <h3><?php esc_html_e('Documentation', 'aqualuxe'); ?></h3>
                        <p><?php esc_html_e('Comprehensive documentation is available to help you get the most out of AquaLuxe theme.', 'aqualuxe'); ?></p>
                        <p><a href="https://aqualuxe.com/documentation/" class="button button-primary" target="_blank"><?php esc_html_e('View Documentation', 'aqualuxe'); ?></a></p>
                        
                        <h3><?php esc_html_e('Video Tutorials', 'aqualuxe'); ?></h3>
                        <p><?php esc_html_e('Watch video tutorials for step-by-step guidance on using AquaLuxe theme.', 'aqualuxe'); ?></p>
                        <p><a href="https://aqualuxe.com/tutorials/" class="button button-primary" target="_blank"><?php esc_html_e('Watch Tutorials', 'aqualuxe'); ?></a></p>
                    </div>
                    
                    <div class="aqualuxe-column">
                        <h3><?php esc_html_e('Support Center', 'aqualuxe'); ?></h3>
                        <p><?php esc_html_e('If you need assistance, our support team is here to help.', 'aqualuxe'); ?></p>
                        <p><a href="https://aqualuxe.com/support/" class="button button-primary" target="_blank"><?php esc_html_e('Get Support', 'aqualuxe'); ?></a></p>
                        
                        <h3><?php esc_html_e('Frequently Asked Questions', 'aqualuxe'); ?></h3>
                        <p><?php esc_html_e('Find answers to common questions about AquaLuxe theme.', 'aqualuxe'); ?></p>
                        <p><a href="https://aqualuxe.com/faq/" class="button button-primary" target="_blank"><?php esc_html_e('View FAQs', 'aqualuxe'); ?></a></p>
                        
                        <h3><?php esc_html_e('Theme Updates', 'aqualuxe'); ?></h3>
                        <p><?php esc_html_e('Keep your theme up to date for the latest features and security updates.', 'aqualuxe'); ?></p>
                        <p><a href="<?php echo esc_url(admin_url('update-core.php')); ?>" class="button button-secondary"><?php esc_html_e('Check for Updates', 'aqualuxe'); ?></a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
    jQuery(document).ready(function($) {
        // Tab functionality
        $('.aqualuxe-theme-tabs .nav-tab').on('click', function(e) {
            e.preventDefault();
            
            // Get the tab ID from the href attribute
            var tabId = $(this).attr('href');
            
            // Hide all tab content
            $('.aqualuxe-tab-content').hide();
            
            // Show the selected tab content
            $(tabId).show();
            
            // Update active tab
            $('.aqualuxe-theme-tabs .nav-tab').removeClass('nav-tab-active');
            $(this).addClass('nav-tab-active');
        });
    });
    </script>
    
    <style>
    .aqualuxe-theme-page {
        max-width: 1200px;
        margin: 20px 0;
    }
    
    .aqualuxe-theme-info {
        background-color: #fff;
        padding: 20px;
        margin-bottom: 20px;
        border-radius: 5px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .aqualuxe-theme-description {
        flex: 1;
    }
    
    .aqualuxe-theme-version {
        background-color: #f0f0f1;
        padding: 5px 10px;
        border-radius: 3px;
        font-size: 14px;
    }
    
    .aqualuxe-tab-content {
        background-color: #fff;
        padding: 20px;
        border-radius: 0 5px 5px 5px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }
    
    .aqualuxe-column-container {
        display: flex;
        flex-wrap: wrap;
        margin: 0 -15px;
    }
    
    .aqualuxe-column {
        flex: 1;
        min-width: 300px;
        padding: 0 15px;
    }
    
    .aqualuxe-features-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 20px;
    }
    
    .aqualuxe-feature {
        background-color: #f9f9f9;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }
    
    .aqualuxe-feature h3 {
        margin-top: 0;
        color: #2271b1;
    }
    
    @media screen and (max-width: 782px) {
        .aqualuxe-column {
            flex: 100%;
            margin-bottom: 20px;
        }
    }
    </style>
    <?php
}

/**
 * Add theme info to admin dashboard
 */
function aqualuxe_dashboard_widget() {
    wp_add_dashboard_widget(
        'aqualuxe_dashboard_widget',
        esc_html__('AquaLuxe Theme', 'aqualuxe'),
        'aqualuxe_dashboard_widget_content'
    );
}
add_action('wp_dashboard_setup', 'aqualuxe_dashboard_widget');

/**
 * Display theme info in dashboard widget
 */
function aqualuxe_dashboard_widget_content() {
    ?>
    <div class="aqualuxe-dashboard-widget">
        <div class="aqualuxe-dashboard-widget-content">
            <h3><?php esc_html_e('Welcome to AquaLuxe Theme', 'aqualuxe'); ?></h3>
            <p><?php esc_html_e('Thank you for choosing AquaLuxe for your website. Here are some quick links to help you get started:', 'aqualuxe'); ?></p>
            
            <ul class="aqualuxe-dashboard-links">
                <li><a href="<?php echo esc_url(admin_url('themes.php?page=aqualuxe-theme')); ?>"><?php esc_html_e('Theme Info', 'aqualuxe'); ?></a></li>
                <li><a href="<?php echo esc_url(admin_url('customize.php')); ?>"><?php esc_html_e('Customize Theme', 'aqualuxe'); ?></a></li>
                <li><a href="<?php echo esc_url(admin_url('themes.php?page=aqualuxe-demo-import')); ?>"><?php esc_html_e('Import Demo Content', 'aqualuxe'); ?></a></li>
                <li><a href="https://aqualuxe.com/documentation/" target="_blank"><?php esc_html_e('Documentation', 'aqualuxe'); ?></a></li>
                <li><a href="https://aqualuxe.com/support/" target="_blank"><?php esc_html_e('Get Support', 'aqualuxe'); ?></a></li>
            </ul>
        </div>
    </div>
    
    <style>
    .aqualuxe-dashboard-widget-content h3 {
        margin-top: 0;
    }
    
    .aqualuxe-dashboard-links {
        margin-bottom: 0;
    }
    
    .aqualuxe-dashboard-links li {
        margin-bottom: 8px;
    }
    
    .aqualuxe-dashboard-links li:last-child {
        margin-bottom: 0;
    }
    </style>
    <?php
}

/**
 * Add admin styles
 */
function aqualuxe_admin_styles() {
    wp_enqueue_style(
        'aqualuxe-admin-style',
        get_template_directory_uri() . '/assets/css/admin.css',
        array(),
        AQUALUXE_VERSION
    );
}
add_action('admin_enqueue_scripts', 'aqualuxe_admin_styles');

/**
 * Add admin notice for theme setup
 */
function aqualuxe_admin_notice() {
    // Check if notice has been dismissed
    if (get_option('aqualuxe_setup_notice_dismissed')) {
        return;
    }
    
    // Only show notice on theme-related pages
    $screen = get_current_screen();
    if (!in_array($screen->id, array('dashboard', 'themes', 'appearance_page_aqualuxe-theme'))) {
        return;
    }
    
    ?>
    <div class="notice notice-info is-dismissible aqualuxe-setup-notice">
        <h3><?php esc_html_e('Welcome to AquaLuxe Theme!', 'aqualuxe'); ?></h3>
        <p><?php esc_html_e('Thank you for choosing AquaLuxe. To get the most out of your theme, please visit the theme info page.', 'aqualuxe'); ?></p>
        <p>
            <a href="<?php echo esc_url(admin_url('themes.php?page=aqualuxe-theme')); ?>" class="button button-primary"><?php esc_html_e('Theme Info', 'aqualuxe'); ?></a>
            <a href="<?php echo esc_url(admin_url('customize.php')); ?>" class="button button-secondary"><?php esc_html_e('Customize Theme', 'aqualuxe'); ?></a>
            <a href="#" class="aqualuxe-dismiss-notice" data-nonce="<?php echo esc_attr(wp_create_nonce('aqualuxe_dismiss_notice')); ?>"><?php esc_html_e('Dismiss', 'aqualuxe'); ?></a>
        </p>
    </div>
    
    <script>
    jQuery(document).ready(function($) {
        $(document).on('click', '.aqualuxe-dismiss-notice, .aqualuxe-setup-notice .notice-dismiss', function(e) {
            e.preventDefault();
            
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'aqualuxe_dismiss_notice',
                    nonce: $('.aqualuxe-dismiss-notice').data('nonce')
                }
            });
            
            $('.aqualuxe-setup-notice').fadeOut();
        });
    });
    </script>
    
    <style>
    .aqualuxe-setup-notice {
        padding: 20px;
    }
    
    .aqualuxe-setup-notice h3 {
        margin-top: 0;
    }
    
    .aqualuxe-dismiss-notice {
        margin-left: 10px;
        text-decoration: none;
    }
    </style>
    <?php
}
add_action('admin_notices', 'aqualuxe_admin_notice');

/**
 * AJAX handler for dismissing admin notice
 */
function aqualuxe_dismiss_notice() {
    // Verify nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'aqualuxe_dismiss_notice')) {
        wp_die('Permission denied');
    }
    
    // Update option to dismiss notice
    update_option('aqualuxe_setup_notice_dismissed', true);
    
    wp_die();
}
add_action('wp_ajax_aqualuxe_dismiss_notice', 'aqualuxe_dismiss_notice');

/**
 * Add theme options to the admin bar
 */
function aqualuxe_admin_bar_menu($wp_admin_bar) {
    if (!current_user_can('manage_options')) {
        return;
    }
    
    // Add parent menu item
    $wp_admin_bar->add_node(array(
        'id'    => 'aqualuxe-theme',
        'title' => esc_html__('AquaLuxe Theme', 'aqualuxe'),
        'href'  => admin_url('themes.php?page=aqualuxe-theme'),
    ));
    
    // Add child menu items
    $wp_admin_bar->add_node(array(
        'id'     => 'aqualuxe-theme-info',
        'parent' => 'aqualuxe-theme',
        'title'  => esc_html__('Theme Info', 'aqualuxe'),
        'href'   => admin_url('themes.php?page=aqualuxe-theme'),
    ));
    
    $wp_admin_bar->add_node(array(
        'id'     => 'aqualuxe-theme-customize',
        'parent' => 'aqualuxe-theme',
        'title'  => esc_html__('Customize Theme', 'aqualuxe'),
        'href'   => admin_url('customize.php'),
    ));
    
    $wp_admin_bar->add_node(array(
        'id'     => 'aqualuxe-theme-documentation',
        'parent' => 'aqualuxe-theme',
        'title'  => esc_html__('Documentation', 'aqualuxe'),
        'href'   => 'https://aqualuxe.com/documentation/',
        'meta'   => array('target' => '_blank'),
    ));
    
    $wp_admin_bar->add_node(array(
        'id'     => 'aqualuxe-theme-support',
        'parent' => 'aqualuxe-theme',
        'title'  => esc_html__('Get Support', 'aqualuxe'),
        'href'   => 'https://aqualuxe.com/support/',
        'meta'   => array('target' => '_blank'),
    ));
}
add_action('admin_bar_menu', 'aqualuxe_admin_bar_menu', 100);

/**
 * Add meta box for page settings
 */
function aqualuxe_add_page_settings_meta_box() {
    add_meta_box(
        'aqualuxe_page_settings',
        __('Page Settings', 'aqualuxe'),
        'aqualuxe_page_settings_callback',
        'page',
        'side',
        'high'
    );
}
add_action('add_meta_boxes', 'aqualuxe_add_page_settings_meta_box');

/**
 * Page settings meta box callback
 */
function aqualuxe_page_settings_callback($post) {
    wp_nonce_field('aqualuxe_page_settings', 'aqualuxe_page_settings_nonce');
    
    $hide_title = get_post_meta($post->ID, '_aqualuxe_hide_title', true);
    $hide_header = get_post_meta($post->ID, '_aqualuxe_hide_header', true);
    $hide_footer = get_post_meta($post->ID, '_aqualuxe_hide_footer', true);
    $page_subtitle = get_post_meta($post->ID, '_aqualuxe_page_subtitle', true);
    $header_overlay = get_post_meta($post->ID, '_aqualuxe_header_overlay', true);
    
    ?>
    <div class="aqualuxe-meta-field">
        <label for="aqualuxe_page_subtitle"><?php esc_html_e('Page Subtitle', 'aqualuxe'); ?></label>
        <input type="text" id="aqualuxe_page_subtitle" name="aqualuxe_page_subtitle" value="<?php echo esc_attr($page_subtitle); ?>" class="widefat">
        <p class="description"><?php esc_html_e('Enter a subtitle for this page (optional).', 'aqualuxe'); ?></p>
    </div>
    
    <div class="aqualuxe-meta-field">
        <label><input type="checkbox" name="aqualuxe_hide_title" value="1" <?php checked($hide_title, '1'); ?>> <?php esc_html_e('Hide Page Title', 'aqualuxe'); ?></label>
    </div>
    
    <div class="aqualuxe-meta-field">
        <label><input type="checkbox" name="aqualuxe_hide_header" value="1" <?php checked($hide_header, '1'); ?>> <?php esc_html_e('Hide Header', 'aqualuxe'); ?></label>
    </div>
    
    <div class="aqualuxe-meta-field">
        <label><input type="checkbox" name="aqualuxe_hide_footer" value="1" <?php checked($hide_footer, '1'); ?>> <?php esc_html_e('Hide Footer', 'aqualuxe'); ?></label>
    </div>
    
    <div class="aqualuxe-meta-field">
        <label><input type="checkbox" name="aqualuxe_header_overlay" value="1" <?php checked($header_overlay, '1'); ?>> <?php esc_html_e('Transparent Header Overlay', 'aqualuxe'); ?></label>
        <p class="description"><?php esc_html_e('Make the header transparent and overlay the content.', 'aqualuxe'); ?></p>
    </div>
    <?php
}

/**
 * Save page settings meta box data
 */
function aqualuxe_save_page_settings($post_id) {
    // Check if our nonce is set and verify that the nonce is valid
    if (!isset($_POST['aqualuxe_page_settings_nonce']) || !wp_verify_nonce($_POST['aqualuxe_page_settings_nonce'], 'aqualuxe_page_settings')) {
        return;
    }
    
    // Check if this is an autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    // Check the user's permissions
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    // Update page settings
    $hide_title = isset($_POST['aqualuxe_hide_title']) ? '1' : '';
    update_post_meta($post_id, '_aqualuxe_hide_title', $hide_title);
    
    $hide_header = isset($_POST['aqualuxe_hide_header']) ? '1' : '';
    update_post_meta($post_id, '_aqualuxe_hide_header', $hide_header);
    
    $hide_footer = isset($_POST['aqualuxe_hide_footer']) ? '1' : '';
    update_post_meta($post_id, '_aqualuxe_hide_footer', $hide_footer);
    
    $header_overlay = isset($_POST['aqualuxe_header_overlay']) ? '1' : '';
    update_post_meta($post_id, '_aqualuxe_header_overlay', $header_overlay);
    
    if (isset($_POST['aqualuxe_page_subtitle'])) {
        update_post_meta($post_id, '_aqualuxe_page_subtitle', sanitize_text_field($_POST['aqualuxe_page_subtitle']));
    }
}
add_action('save_post', 'aqualuxe_save_page_settings');