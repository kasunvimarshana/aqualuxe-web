<?php
/**
 * AquaLuxe Assets Class
 *
 * This class handles asset loading for the AquaLuxe theme.
 *
 * @package AquaLuxe
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * AquaLuxe Assets Class
 */
class AquaLuxe_Assets {
    /**
     * Instance of the class
     *
     * @var AquaLuxe_Assets
     */
    private static $instance = null;

    /**
     * Asset manifest
     *
     * @var array
     */
    private $manifest = [];

    /**
     * Constructor
     */
    private function __construct() {
        // Load asset manifest
        $this->load_manifest();

        // Register hooks
        add_action('wp_enqueue_scripts', [$this, 'enqueue_styles']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_styles']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_scripts']);
        add_action('enqueue_block_editor_assets', [$this, 'enqueue_editor_assets']);
        add_action('wp_head', [$this, 'add_preload_links']);
        add_action('wp_footer', [$this, 'add_inline_scripts']);
        add_filter('script_loader_tag', [$this, 'add_script_attributes'], 10, 3);
        add_filter('style_loader_tag', [$this, 'add_style_attributes'], 10, 4);
    }

    /**
     * Get instance of the class
     *
     * @return AquaLuxe_Assets
     */
    public static function get_instance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Load asset manifest
     */
    private function load_manifest() {
        $manifest_path = AQUALUXE_DIR . '/assets/dist/mix-manifest.json';

        if (file_exists($manifest_path)) {
            $manifest = json_decode(file_get_contents($manifest_path), true);

            // Remove leading slash from keys
            if (is_array($manifest)) {
                $this->manifest = array_combine(
                    array_map(function($key) {
                        return ltrim($key, '/');
                    }, array_keys($manifest)),
                    array_map(function($value) {
                        return ltrim($value, '/');
                    }, array_values($manifest))
                );
            }
        }
    }

    /**
     * Get asset URL
     *
     * @param string $path
     * @return string
     */
    public function get_asset_url($path) {
        $asset_path = isset($this->manifest[$path]) ? $this->manifest[$path] : $path;
        return AQUALUXE_ASSETS_URI . '/' . $asset_path;
    }

    /**
     * Enqueue styles
     */
    public function enqueue_styles() {
        // Enqueue Google Fonts
        wp_enqueue_style(
            'aqualuxe-google-fonts',
            'https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&family=JetBrains+Mono&display=swap',
            [],
            AQUALUXE_VERSION
        );

        // Enqueue main stylesheet
        wp_enqueue_style(
            'aqualuxe-style',
            $this->get_asset_url('css/main.css'),
            [],
            AQUALUXE_VERSION
        );

        // Enqueue Tailwind CSS
        wp_enqueue_style(
            'aqualuxe-tailwind',
            $this->get_asset_url('css/tailwind.css'),
            [],
            AQUALUXE_VERSION
        );

        // Enqueue WooCommerce styles if active
        if (aqualuxe_is_woocommerce_active()) {
            wp_enqueue_style(
                'aqualuxe-woocommerce',
                $this->get_asset_url('css/woocommerce.css'),
                [],
                AQUALUXE_VERSION
            );
        }

        // Enqueue dark mode styles
        wp_enqueue_style(
            'aqualuxe-dark-mode',
            $this->get_asset_url('css/dark-mode.css'),
            [],
            AQUALUXE_VERSION
        );

        // Add inline styles
        $this->add_inline_styles();
    }

    /**
     * Enqueue scripts
     */
    public function enqueue_scripts() {
        // Enqueue main script
        wp_enqueue_script(
            'aqualuxe-script',
            $this->get_asset_url('js/app.js'),
            ['jquery'],
            AQUALUXE_VERSION,
            true
        );

        // Add script data
        wp_localize_script('aqualuxe-script', 'aqualuxeData', [
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'themeUrl' => AQUALUXE_URI,
            'assetsUrl' => AQUALUXE_ASSETS_URI,
            'nonce' => wp_create_nonce('aqualuxe-nonce'),
            'isRtl' => is_rtl(),
            'isWooCommerce' => aqualuxe_is_woocommerce_active(),
            'isMobile' => wp_is_mobile(),
            'i18n' => [
                'searchPlaceholder' => esc_html__('Search...', 'aqualuxe'),
                'searchNoResults' => esc_html__('No results found', 'aqualuxe'),
                'menuToggle' => esc_html__('Toggle Menu', 'aqualuxe'),
                'cartEmpty' => esc_html__('Your cart is empty', 'aqualuxe'),
                'addToCart' => esc_html__('Add to Cart', 'aqualuxe'),
                'addingToCart' => esc_html__('Adding...', 'aqualuxe'),
                'addedToCart' => esc_html__('Added to Cart', 'aqualuxe'),
                'viewCart' => esc_html__('View Cart', 'aqualuxe'),
                'checkout' => esc_html__('Checkout', 'aqualuxe'),
                'lightMode' => esc_html__('Light Mode', 'aqualuxe'),
                'darkMode' => esc_html__('Dark Mode', 'aqualuxe'),
            ],
        ]);

        // Add comment-reply script
        if (is_singular() && comments_open() && get_option('thread_comments')) {
            wp_enqueue_script('comment-reply');
        }
    }

    /**
     * Enqueue admin styles
     */
    public function enqueue_admin_styles() {
        // Enqueue admin styles
        wp_enqueue_style(
            'aqualuxe-admin-style',
            $this->get_asset_url('css/admin.css'),
            [],
            AQUALUXE_VERSION
        );
    }

    /**
     * Enqueue admin scripts
     */
    public function enqueue_admin_scripts() {
        // Enqueue admin script
        wp_enqueue_script(
            'aqualuxe-admin-script',
            $this->get_asset_url('js/admin.js'),
            ['jquery'],
            AQUALUXE_VERSION,
            true
        );

        // Add admin script data
        wp_localize_script('aqualuxe-admin-script', 'aqualuxeAdminData', [
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'themeUrl' => AQUALUXE_URI,
            'assetsUrl' => AQUALUXE_ASSETS_URI,
            'nonce' => wp_create_nonce('aqualuxe-admin-nonce'),
        ]);
    }

    /**
     * Enqueue editor assets
     */
    public function enqueue_editor_assets() {
        // Enqueue editor styles
        wp_enqueue_style(
            'aqualuxe-editor-style',
            $this->get_asset_url('css/editor-style.css'),
            [],
            AQUALUXE_VERSION
        );

        // Enqueue editor script
        wp_enqueue_script(
            'aqualuxe-editor-script',
            $this->get_asset_url('js/customizer.js'),
            ['jquery'],
            AQUALUXE_VERSION,
            true
        );
    }

    /**
     * Add preload links
     */
    public function add_preload_links() {
        // Preload Google Fonts
        echo '<link rel="preconnect" href="https://fonts.googleapis.com">';
        echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>';

        // Preload main stylesheet
        echo '<link rel="preload" href="' . esc_url($this->get_asset_url('css/main.css')) . '" as="style">';

        // Preload main script
        echo '<link rel="preload" href="' . esc_url($this->get_asset_url('js/app.js')) . '" as="script">';

        // Preload critical fonts
        $fonts = [
            'Montserrat-Regular.woff2' => 'font/woff2',
            'Montserrat-Bold.woff2' => 'font/woff2',
            'PlayfairDisplay-Regular.woff2' => 'font/woff2',
            'PlayfairDisplay-Bold.woff2' => 'font/woff2',
        ];

        foreach ($fonts as $font => $type) {
            if (file_exists(AQUALUXE_DIR . '/assets/dist/fonts/' . $font)) {
                echo '<link rel="preload" href="' . esc_url(AQUALUXE_ASSETS_URI . '/fonts/' . $font) . '" as="font" type="' . esc_attr($type) . '" crossorigin>';
            }
        }
    }

    /**
     * Add inline styles
     */
    public function add_inline_styles() {
        // Get theme colors
        $primary_color = aqualuxe_get_color('primary');
        $secondary_color = aqualuxe_get_color('secondary');
        $accent_color = aqualuxe_get_color('accent');
        $luxe_color = aqualuxe_get_color('luxe');

        // Add custom CSS variables
        $custom_css = "
            :root {
                --color-primary: {$primary_color};
                --color-secondary: {$secondary_color};
                --color-accent: {$accent_color};
                --color-luxe: {$luxe_color};
            }
        ";

        // Add custom CSS
        wp_add_inline_style('aqualuxe-style', $custom_css);
    }

    /**
     * Add inline scripts
     */
    public function add_inline_scripts() {
        // Add dark mode script
        echo '<script>
            (function() {
                // Check for saved theme preference or respect OS preference
                const savedTheme = localStorage.getItem("theme");
                const prefersDark = window.matchMedia("(prefers-color-scheme: dark)").matches;
                
                if (savedTheme === "dark" || (!savedTheme && prefersDark)) {
                    document.documentElement.classList.add("dark");
                } else {
                    document.documentElement.classList.remove("dark");
                }
            })();
        </script>';
    }

    /**
     * Add script attributes
     *
     * @param string $tag
     * @param string $handle
     * @param string $src
     * @return string
     */
    public function add_script_attributes($tag, $handle, $src) {
        // Add defer attribute to main script
        if ($handle === 'aqualuxe-script') {
            $tag = str_replace(' src', ' defer src', $tag);
        }

        return $tag;
    }

    /**
     * Add style attributes
     *
     * @param string $html
     * @param string $handle
     * @param string $href
     * @param string $media
     * @return string
     */
    public function add_style_attributes($html, $handle, $href, $media) {
        // Add media attribute to print styles
        if ($handle === 'aqualuxe-print-style') {
            $html = str_replace('media=\'all\'', 'media=\'print\'', $html);
        }

        return $html;
    }
}

// Initialize assets
AquaLuxe_Assets::get_instance();