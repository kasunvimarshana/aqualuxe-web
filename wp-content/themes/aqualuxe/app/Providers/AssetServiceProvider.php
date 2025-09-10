<?php
/**
 * Asset Service Provider
 *
 * This service provider handles the enqueueing of theme assets (CSS, JS)
 * with proper versioning, dependency management, and performance optimizations.
 *
 * @package AquaLuxe\Providers
 */

namespace App\Providers;

use App\Core\ServiceProvider;

class AssetServiceProvider extends ServiceProvider {

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register(): void {
        // Register asset enqueue hooks
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_assets']);
        add_action('customize_preview_init', [$this, 'enqueue_customizer_assets']);

        // Optimize asset loading
        add_filter('script_loader_tag', [$this, 'add_script_attributes'], 10, 3);
        add_action('wp_head', [$this, 'preload_critical_assets'], 1);

        // Remove unnecessary assets
        add_action('wp_enqueue_scripts', [$this, 'dequeue_unnecessary_assets'], 100);
    }

    /**
     * Enqueue frontend assets.
     *
     * @return void
     */
    public function enqueue_assets(): void {
        // Get asset manifest for versioning
        $asset_file = AQUALUXE_DIR . '/assets/dist/mix-manifest.json';
        $assets = file_exists($asset_file) ? json_decode(file_get_contents($asset_file), true) : [];

        // Main stylesheet
        $css_path = isset($assets['/css/app.css']) ? $assets['/css/app.css'] : '/css/app.css';
        wp_enqueue_style(
            'aqualuxe-main-style',
            AQUALUXE_URL . '/assets/dist' . $css_path,
            [],
            AQUALUXE_VERSION
        );

        // Main JavaScript
        $js_path = isset($assets['/js/app.js']) ? $assets['/js/app.js'] : '/js/app.js';
        wp_enqueue_script(
            'aqualuxe-main-script',
            AQUALUXE_URL . '/assets/dist' . $js_path,
            ['jquery'],
            AQUALUXE_VERSION,
            true
        );

        // Localize script data
        wp_localize_script('aqualuxe-main-script', 'aqualuxeConfig', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'rest_url' => rest_url(),
            'nonce' => wp_create_nonce('aqualuxe_nonce'),
            'theme_url' => get_template_directory_uri(),
            'is_rtl' => is_rtl(),
            'is_mobile' => wp_is_mobile(),
            'breakpoints' => [
                'sm' => 640,
                'md' => 768,
                'lg' => 1024,
                'xl' => 1280,
                '2xl' => 1536
            ],
            'animation_enabled' => get_theme_mod('aqualuxe_enable_animations', true),
            'lazy_loading_enabled' => get_theme_mod('aqualuxe_enable_lazy_loading', true)
        ]);

        // Load Google Fonts
        wp_enqueue_style(
            'aqualuxe-google-fonts',
            'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@300;400;500;600;700;800&display=swap',
            [],
            null
        );

        // Conditional assets
        $this->enqueue_conditional_assets($assets);

        // Comment reply script
        if (is_singular() && comments_open() && get_option('thread_comments')) {
            wp_enqueue_script('comment-reply');
        }
    }

    /**
     * Enqueue admin assets.
     *
     * @param string $hook_suffix
     * @return void
     */
    public function enqueue_admin_assets(string $hook_suffix): void {
        // Only load on specific admin pages
        $allowed_pages = [
            'post.php',
            'post-new.php',
            'edit.php',
            'theme-options',
            'customize.php'
        ];

        if (!in_array($hook_suffix, $allowed_pages, true)) {
            return;
        }

        // Admin specific styles (if they exist)
        $admin_css = AQUALUXE_DIR . '/assets/dist/css/admin.css';
        if (file_exists($admin_css)) {
            wp_enqueue_style(
                'aqualuxe-admin-style',
                AQUALUXE_URL . '/assets/dist/css/admin.css',
                ['wp-admin'],
                AQUALUXE_VERSION
            );
        }
    }

    /**
     * Enqueue customizer assets.
     *
     * @return void
     */
    public function enqueue_customizer_assets(): void {
        $asset_file = AQUALUXE_DIR . '/assets/dist/mix-manifest.json';
        $assets = file_exists($asset_file) ? json_decode(file_get_contents($asset_file), true) : [];

        $customizer_path = isset($assets['/js/customizer.js']) ? $assets['/js/customizer.js'] : '/js/customizer.js';
        wp_enqueue_script(
            'aqualuxe-customizer',
            AQUALUXE_URL . '/assets/dist' . $customizer_path,
            ['customize-preview', 'jquery'],
            AQUALUXE_VERSION,
            true
        );
    }

    /**
     * Add attributes to script tags for optimization.
     *
     * @param string $tag
     * @param string $handle
     * @param string $src
     * @return string
     */
    public function add_script_attributes(string $tag, string $handle, string $src): string {
        // Add defer attribute for non-critical scripts
        $defer_scripts = [
            'aqualuxe-main-script',
            'aqualuxe-customizer'
        ];

        if (in_array($handle, $defer_scripts, true)) {
            return str_replace(' src', ' defer src', $tag);
        }

        return $tag;
    }

    /**
     * Preload critical assets.
     *
     * @return void
     */
    public function preload_critical_assets(): void {
        // Preload Google Fonts
        echo '<link rel="preconnect" href="https://fonts.googleapis.com" />' . "\n";
        echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />' . "\n";
    }

    /**
     * Dequeue unnecessary assets.
     *
     * @return void
     */
    public function dequeue_unnecessary_assets(): void {
        // Remove unnecessary WordPress assets on frontend
        if (!is_admin() && !is_customize_preview()) {
            // Remove block library CSS if not using Gutenberg blocks
            if (!$this->is_using_blocks()) {
                wp_dequeue_style('wp-block-library');
                wp_dequeue_style('wp-block-library-theme');
            }

            // Remove WooCommerce block styles if not on WooCommerce pages
            if (class_exists('WooCommerce') && !is_woocommerce() && !is_cart() && !is_checkout()) {
                wp_dequeue_style('wc-block-style');
            }
        }
    }

    /**
     * Enqueue conditional assets based on context.
     *
     * @param array $assets
     * @return void
     */
    private function enqueue_conditional_assets(array $assets): void {
        // WooCommerce specific assets
        if (class_exists('WooCommerce') && (is_woocommerce() || is_cart() || is_checkout() || is_account_page())) {
            $woo_css = isset($assets['/css/woocommerce.css']) ? $assets['/css/woocommerce.css'] : '/css/woocommerce.css';
            $woo_css_path = AQUALUXE_DIR . '/assets/dist' . $woo_css;

            if (file_exists($woo_css_path)) {
                wp_enqueue_style(
                    'aqualuxe-woocommerce',
                    AQUALUXE_URL . '/assets/dist' . $woo_css,
                    ['aqualuxe-main-style'],
                    AQUALUXE_VERSION
                );
            }
        }

        // Contact form specific assets
        if (is_page_template('templates/contact.php')) {
            $contact_js = isset($assets['/js/contact-form.js']) ? $assets['/js/contact-form.js'] : '/js/contact-form.js';
            $contact_js_path = AQUALUXE_DIR . '/assets/dist' . $contact_js;

            if (file_exists($contact_js_path)) {
                wp_enqueue_script(
                    'aqualuxe-contact-form',
                    AQUALUXE_URL . '/assets/dist' . $contact_js,
                    ['aqualuxe-main-script'],
                    AQUALUXE_VERSION,
                    true
                );
            }
        }
    }

    /**
     * Check if the current page is using blocks.
     *
     * @return bool
     */
    private function is_using_blocks(): bool {
        if (is_singular()) {
            global $post;
            return has_blocks($post->post_content);
        }

        return false;
    }
}
