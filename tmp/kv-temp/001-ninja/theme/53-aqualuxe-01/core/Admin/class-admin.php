<?php
/**
 * Admin setup
 *
 * @package AquaLuxe
 */

namespace AquaLuxe\Core\Admin;

/**
 * Admin setup class
 */
class Admin {
    /**
     * Constructor
     */
    public function __construct() {
        add_action('admin_menu', [$this, 'add_admin_menu']);
        add_action('admin_init', [$this, 'register_settings']);
        add_action('admin_notices', [$this, 'admin_notices']);
        add_filter('admin_footer_text', [$this, 'admin_footer_text']);
    }

    /**
     * Add admin menu
     *
     * @return void
     */
    public function add_admin_menu() {
        add_theme_page(
            esc_html__('AquaLuxe Theme', 'aqualuxe'),
            esc_html__('AquaLuxe Theme', 'aqualuxe'),
            'manage_options',
            'aqualuxe-theme',
            [$this, 'admin_page']
        );
    }

    /**
     * Register settings
     *
     * @return void
     */
    public function register_settings() {
        register_setting('aqualuxe_options', 'aqualuxe_options');
    }

    /**
     * Admin page
     *
     * @return void
     */
    public function admin_page() {
        ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            <div class="aqualuxe-admin-wrapper">
                <div class="aqualuxe-admin-main">
                    <div class="aqualuxe-admin-card">
                        <h2><?php esc_html_e('Welcome to AquaLuxe Theme', 'aqualuxe'); ?></h2>
                        <p><?php esc_html_e('Thank you for choosing AquaLuxe Theme. This is a premium WordPress theme for aquatic business with WooCommerce integration.', 'aqualuxe'); ?></p>
                        <p><?php esc_html_e('To get started, please check the documentation and customize your theme using the WordPress Customizer.', 'aqualuxe'); ?></p>
                        <div class="aqualuxe-admin-actions">
                            <a href="<?php echo esc_url(admin_url('customize.php')); ?>" class="button button-primary"><?php esc_html_e('Customize Theme', 'aqualuxe'); ?></a>
                            <a href="<?php echo esc_url('https://aqualuxe.com/documentation/'); ?>" class="button" target="_blank"><?php esc_html_e('Documentation', 'aqualuxe'); ?></a>
                        </div>
                    </div>

                    <div class="aqualuxe-admin-card">
                        <h2><?php esc_html_e('Theme Features', 'aqualuxe'); ?></h2>
                        <ul class="aqualuxe-features-list">
                            <li><?php esc_html_e('Modular architecture with dual-state support for WooCommerce', 'aqualuxe'); ?></li>
                            <li><?php esc_html_e('Multitenant, multivendor, multilingual, multicurrency support', 'aqualuxe'); ?></li>
                            <li><?php esc_html_e('Mobile-first, fully responsive design', 'aqualuxe'); ?></li>
                            <li><?php esc_html_e('Customizable colors, typography, and layouts', 'aqualuxe'); ?></li>
                            <li><?php esc_html_e('Dark mode with persistent preference', 'aqualuxe'); ?></li>
                            <li><?php esc_html_e('Advanced WooCommerce integration with fallbacks', 'aqualuxe'); ?></li>
                            <li><?php esc_html_e('Modular feature system for easy extension', 'aqualuxe'); ?></li>
                        </ul>
                    </div>

                    <div class="aqualuxe-admin-card">
                        <h2><?php esc_html_e('Import Demo Content', 'aqualuxe'); ?></h2>
                        <p><?php esc_html_e('You can import the demo content to get started quickly. This will import posts, pages, menus, widgets, and customizer settings.', 'aqualuxe'); ?></p>
                        <div class="aqualuxe-admin-actions">
                            <button class="button button-primary" id="aqualuxe-import-demo"><?php esc_html_e('Import Demo Content', 'aqualuxe'); ?></button>
                        </div>
                        <div class="aqualuxe-import-status" style="display: none;"></div>
                    </div>
                </div>

                <div class="aqualuxe-admin-sidebar">
                    <div class="aqualuxe-admin-card">
                        <h3><?php esc_html_e('Need Help?', 'aqualuxe'); ?></h3>
                        <p><?php esc_html_e('If you need help with the theme, please check our documentation or contact our support team.', 'aqualuxe'); ?></p>
                        <div class="aqualuxe-admin-actions">
                            <a href="<?php echo esc_url('https://aqualuxe.com/support/'); ?>" class="button" target="_blank"><?php esc_html_e('Get Support', 'aqualuxe'); ?></a>
                        </div>
                    </div>

                    <div class="aqualuxe-admin-card">
                        <h3><?php esc_html_e('Rate Our Theme', 'aqualuxe'); ?></h3>
                        <p><?php esc_html_e('If you enjoy using our theme, please take a moment to rate it on ThemeForest. It helps us a lot!', 'aqualuxe'); ?></p>
                        <div class="aqualuxe-admin-actions">
                            <a href="<?php echo esc_url('https://themeforest.net/downloads'); ?>" class="button" target="_blank"><?php esc_html_e('Rate on ThemeForest', 'aqualuxe'); ?></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Admin notices
     *
     * @return void
     */
    public function admin_notices() {
        // Check if WooCommerce is active
        if (!class_exists('WooCommerce') && current_user_can('activate_plugins')) {
            ?>
            <div class="notice notice-info is-dismissible">
                <p>
                    <?php
                    printf(
                        /* translators: %s: WooCommerce URL */
                        esc_html__('AquaLuxe Theme works best with WooCommerce. Please %s to enable all features.', 'aqualuxe'),
                        '<a href="' . esc_url(admin_url('plugin-install.php?s=woocommerce&tab=search&type=term')) . '">' . esc_html__('install WooCommerce', 'aqualuxe') . '</a>'
                    );
                    ?>
                </p>
            </div>
            <?php
        }
    }

    /**
     * Admin footer text
     *
     * @param string $text Footer text
     * @return string
     */
    public function admin_footer_text($text) {
        $screen = get_current_screen();

        if ($screen && 'appearance_page_aqualuxe-theme' === $screen->id) {
            $text = sprintf(
                /* translators: %s: AquaLuxe */
                esc_html__('Thank you for creating with %s.', 'aqualuxe'),
                '<a href="https://aqualuxe.com" target="_blank">AquaLuxe</a>'
            );
        }

        return $text;
    }
}