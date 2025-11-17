<?php
/**
 * Admin Init
 * 
 * @package AquaLuxe
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Initialize admin functionality
 */
function aqualuxe_admin_init()
{
    // Register widget areas
    aqualuxe_register_sidebars();
    
    // Load admin includes
    $admin_includes = [
        'demo-importer.php',
        'dashboard-widgets.php',
        'admin-notices.php',
        'theme-settings.php',
    ];

    foreach ($admin_includes as $file) {
        $path = AQUALUXE_INCLUDES_DIR . '/admin/' . $file;
        if (file_exists($path)) {
            require_once $path;
        }
    }
}
add_action('admin_init', 'aqualuxe_admin_init');

/**
 * Register widget areas
 */
function aqualuxe_register_sidebars()
{
    // Primary sidebar
    register_sidebar([
        'name' => esc_html__('Primary Sidebar', 'aqualuxe'),
        'id' => 'sidebar-1',
        'description' => esc_html__('Add widgets here to appear in your primary sidebar.', 'aqualuxe'),
        'before_widget' => '<section id="%1$s" class="widget %2$s bg-white rounded-lg shadow-md p-6 mb-6">',
        'after_widget' => '</section>',
        'before_title' => '<h3 class="widget-title text-lg font-semibold text-gray-900 mb-4">',
        'after_title' => '</h3>',
    ]);

    // Footer widget areas
    for ($i = 1; $i <= 4; $i++) {
        register_sidebar([
            'name' => sprintf(esc_html__('Footer Widget Area %d', 'aqualuxe'), $i),
            'id' => "footer-{$i}",
            'description' => sprintf(esc_html__('Add widgets here to appear in footer area %d.', 'aqualuxe'), $i),
            'before_widget' => '<div id="%1$s" class="widget %2$s mb-8">',
            'after_widget' => '</div>',
            'before_title' => '<h3 class="widget-title text-lg font-semibold text-white mb-4">',
            'after_title' => '</h3>',
        ]);
    }

    // Shop sidebar (if WooCommerce is active)
    if (class_exists('WooCommerce')) {
        register_sidebar([
            'name' => esc_html__('Shop Sidebar', 'aqualuxe'),
            'id' => 'shop-sidebar',
            'description' => esc_html__('Add widgets here to appear in the shop sidebar.', 'aqualuxe'),
            'before_widget' => '<section id="%1$s" class="widget %2$s bg-white rounded-lg shadow-md p-6 mb-6">',
            'after_widget' => '</section>',
            'before_title' => '<h3 class="widget-title text-lg font-semibold text-gray-900 mb-4">',
            'after_title' => '</h3>',
        ]);
    }
}

/**
 * Add admin body classes
 */
function aqualuxe_admin_body_class($classes)
{
    $classes .= ' aqualuxe-admin';
    return $classes;
}
add_filter('admin_body_class', 'aqualuxe_admin_body_class');

/**
 * Customize admin menu
 */
function aqualuxe_admin_menu()
{
    // Add theme options page
    add_theme_page(
        esc_html__('AquaLuxe Settings', 'aqualuxe'),
        esc_html__('Theme Settings', 'aqualuxe'),
        'manage_options',
        'aqualuxe-settings',
        'aqualuxe_settings_page'
    );
}
add_action('admin_menu', 'aqualuxe_admin_menu');

/**
 * Theme settings page
 */
function aqualuxe_settings_page()
{
    ?>
    <div class="wrap aqualuxe-settings">
        <h1><?php esc_html_e('AquaLuxe Theme Settings', 'aqualuxe'); ?></h1>
        
        <nav class="nav-tab-wrapper">
            <a href="#general" class="nav-tab nav-tab-active"><?php esc_html_e('General', 'aqualuxe'); ?></a>
            <a href="#performance" class="nav-tab"><?php esc_html_e('Performance', 'aqualuxe'); ?></a>
            <a href="#seo" class="nav-tab"><?php esc_html_e('SEO', 'aqualuxe'); ?></a>
            <a href="#advanced" class="nav-tab"><?php esc_html_e('Advanced', 'aqualuxe'); ?></a>
        </nav>
        
        <form method="post" action="options.php">
            <?php settings_fields('aqualuxe_settings'); ?>
            
            <div id="general" class="settings-section">
                <h3><?php esc_html_e('General Settings', 'aqualuxe'); ?></h3>
                <table class="form-table">
                    <tr>
                        <th scope="row"><?php esc_html_e('Enable Dark Mode', 'aqualuxe'); ?></th>
                        <td>
                            <label>
                                <input type="checkbox" name="aqualuxe_enable_dark_mode" value="1" <?php checked(get_option('aqualuxe_enable_dark_mode'), 1); ?>>
                                <?php esc_html_e('Allow users to switch between light and dark modes', 'aqualuxe'); ?>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Enable Animations', 'aqualuxe'); ?></th>
                        <td>
                            <label>
                                <input type="checkbox" name="aqualuxe_enable_animations" value="1" <?php checked(get_option('aqualuxe_enable_animations', 1), 1); ?>>
                                <?php esc_html_e('Enable CSS animations and transitions', 'aqualuxe'); ?>
                            </label>
                        </td>
                    </tr>
                </table>
            </div>
            
            <div id="performance" class="settings-section" style="display: none;">
                <h3><?php esc_html_e('Performance Settings', 'aqualuxe'); ?></h3>
                <table class="form-table">
                    <tr>
                        <th scope="row"><?php esc_html_e('Enable Lazy Loading', 'aqualuxe'); ?></th>
                        <td>
                            <label>
                                <input type="checkbox" name="aqualuxe_enable_lazy_loading" value="1" <?php checked(get_option('aqualuxe_enable_lazy_loading', 1), 1); ?>>
                                <?php esc_html_e('Lazy load images for better performance', 'aqualuxe'); ?>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Minify Assets', 'aqualuxe'); ?></th>
                        <td>
                            <label>
                                <input type="checkbox" name="aqualuxe_minify_assets" value="1" <?php checked(get_option('aqualuxe_minify_assets'), 1); ?>>
                                <?php esc_html_e('Enable asset minification (requires build tools)', 'aqualuxe'); ?>
                            </label>
                        </td>
                    </tr>
                </table>
            </div>
            
            <div id="seo" class="settings-section" style="display: none;">
                <h3><?php esc_html_e('SEO Settings', 'aqualuxe'); ?></h3>
                <table class="form-table">
                    <tr>
                        <th scope="row"><?php esc_html_e('Enable Schema Markup', 'aqualuxe'); ?></th>
                        <td>
                            <label>
                                <input type="checkbox" name="aqualuxe_enable_schema" value="1" <?php checked(get_option('aqualuxe_enable_schema', 1), 1); ?>>
                                <?php esc_html_e('Add structured data markup for better SEO', 'aqualuxe'); ?>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Open Graph Tags', 'aqualuxe'); ?></th>
                        <td>
                            <label>
                                <input type="checkbox" name="aqualuxe_enable_opengraph" value="1" <?php checked(get_option('aqualuxe_enable_opengraph', 1), 1); ?>>
                                <?php esc_html_e('Add Open Graph meta tags for social sharing', 'aqualuxe'); ?>
                            </label>
                        </td>
                    </tr>
                </table>
            </div>
            
            <div id="advanced" class="settings-section" style="display: none;">
                <h3><?php esc_html_e('Advanced Settings', 'aqualuxe'); ?></h3>
                <table class="form-table">
                    <tr>
                        <th scope="row"><?php esc_html_e('Custom CSS', 'aqualuxe'); ?></th>
                        <td>
                            <textarea name="aqualuxe_custom_css" rows="10" cols="50" class="large-text code"><?php echo esc_textarea(get_option('aqualuxe_custom_css')); ?></textarea>
                            <p class="description"><?php esc_html_e('Add custom CSS that will be loaded after the theme styles.', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Custom JavaScript', 'aqualuxe'); ?></th>
                        <td>
                            <textarea name="aqualuxe_custom_js" rows="10" cols="50" class="large-text code"><?php echo esc_textarea(get_option('aqualuxe_custom_js')); ?></textarea>
                            <p class="description"><?php esc_html_e('Add custom JavaScript that will be loaded in the footer.', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                </table>
            </div>
            
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

/**
 * Register theme settings
 */
function aqualuxe_register_settings()
{
    register_setting('aqualuxe_settings', 'aqualuxe_enable_dark_mode');
    register_setting('aqualuxe_settings', 'aqualuxe_enable_animations');
    register_setting('aqualuxe_settings', 'aqualuxe_enable_lazy_loading');
    register_setting('aqualuxe_settings', 'aqualuxe_minify_assets');
    register_setting('aqualuxe_settings', 'aqualuxe_enable_schema');
    register_setting('aqualuxe_settings', 'aqualuxe_enable_opengraph');
    register_setting('aqualuxe_settings', 'aqualuxe_custom_css');
    register_setting('aqualuxe_settings', 'aqualuxe_custom_js');
}
add_action('admin_init', 'aqualuxe_register_settings');

/**
 * Output custom CSS
 */
function aqualuxe_custom_css()
{
    $custom_css = get_option('aqualuxe_custom_css');
    if (!empty($custom_css)) {
        echo '<style type="text/css" id="aqualuxe-custom-css">' . wp_strip_all_tags($custom_css) . '</style>';
    }
}
add_action('wp_head', 'aqualuxe_custom_css', 100);

/**
 * Output custom JavaScript
 */
function aqualuxe_custom_js()
{
    $custom_js = get_option('aqualuxe_custom_js');
    if (!empty($custom_js)) {
        echo '<script type="text/javascript" id="aqualuxe-custom-js">' . $custom_js . '</script>';
    }
}
add_action('wp_footer', 'aqualuxe_custom_js', 100);