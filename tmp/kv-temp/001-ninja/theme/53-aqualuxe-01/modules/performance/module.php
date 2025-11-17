<?php
/**
 * Performance Module
 *
 * @package AquaLuxe
 * @subpackage Modules\Performance
 */

namespace AquaLuxe\Modules\Performance;

use AquaLuxe\Core\Module_Base;

/**
 * Performance Module class
 */
class Module extends Module_Base {
    /**
     * Module ID
     *
     * @var string
     */
    protected $module_id = 'performance';

    /**
     * Module name
     *
     * @var string
     */
    protected $module_name = 'Performance';

    /**
     * Module description
     *
     * @var string
     */
    protected $module_description = 'Adds performance optimization features to the theme.';

    /**
     * Module dependencies
     *
     * @var array
     */
    protected $module_dependencies = [];

    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct();

        // Register hooks
        add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);
        add_action('customize_register', [$this, 'customize_register']);
        add_action('wp_head', [$this, 'add_preload_tags'], 1);
        add_action('wp_footer', [$this, 'add_defer_scripts'], 99);
        add_filter('script_loader_tag', [$this, 'add_async_defer_attributes'], 10, 3);
        add_filter('style_loader_tag', [$this, 'add_preload_to_styles'], 10, 4);
        add_filter('wp_resource_hints', [$this, 'add_dns_prefetch'], 10, 2);
        add_filter('body_class', [$this, 'body_classes']);
        
        // Initialize performance optimizations
        $this->init_optimizations();
    }

    /**
     * Initialize optimizations
     *
     * @return void
     */
    private function init_optimizations() {
        // Disable emoji if enabled
        if (get_theme_mod('disable_emoji', true)) {
            $this->disable_emoji();
        }

        // Disable embeds if enabled
        if (get_theme_mod('disable_embeds', true)) {
            $this->disable_embeds();
        }

        // Disable XML-RPC if enabled
        if (get_theme_mod('disable_xmlrpc', true)) {
            $this->disable_xmlrpc();
        }

        // Disable jQuery Migrate if enabled
        if (get_theme_mod('disable_jquery_migrate', true)) {
            $this->disable_jquery_migrate();
        }

        // Enable image lazy loading if enabled
        if (get_theme_mod('enable_lazy_loading', true)) {
            $this->enable_lazy_loading();
        }
    }

    /**
     * Enqueue scripts
     *
     * @return void
     */
    public function enqueue_scripts() {
        // Only load if lazy loading is enabled
        if (get_theme_mod('enable_lazy_loading', true)) {
            wp_enqueue_script(
                'aqualuxe-lazy-loading',
                AQUALUXE_MODULES_DIR . 'performance/assets/js/lazy-loading.js',
                [],
                AQUALUXE_VERSION,
                true
            );
        }
    }

    /**
     * Register customizer settings
     *
     * @param WP_Customize_Manager $wp_customize Customizer manager
     * @return void
     */
    public function customize_register($wp_customize) {
        // Performance Section
        $wp_customize->add_section(
            'performance_section',
            [
                'title' => __('Performance', 'aqualuxe'),
                'priority' => 50,
                'panel' => 'aqualuxe_theme_options',
            ]
        );

        // Disable Emoji
        $wp_customize->add_setting(
            'disable_emoji',
            [
                'default' => true,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            ]
        );

        $wp_customize->add_control(
            'disable_emoji',
            [
                'label' => __('Disable WordPress Emoji', 'aqualuxe'),
                'description' => __('Remove emoji-related scripts and styles.', 'aqualuxe'),
                'section' => 'performance_section',
                'type' => 'checkbox',
            ]
        );

        // Disable Embeds
        $wp_customize->add_setting(
            'disable_embeds',
            [
                'default' => true,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            ]
        );

        $wp_customize->add_control(
            'disable_embeds',
            [
                'label' => __('Disable WordPress Embeds', 'aqualuxe'),
                'description' => __('Remove embed-related scripts.', 'aqualuxe'),
                'section' => 'performance_section',
                'type' => 'checkbox',
            ]
        );

        // Disable XML-RPC
        $wp_customize->add_setting(
            'disable_xmlrpc',
            [
                'default' => true,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            ]
        );

        $wp_customize->add_control(
            'disable_xmlrpc',
            [
                'label' => __('Disable XML-RPC', 'aqualuxe'),
                'description' => __('Disable XML-RPC functionality.', 'aqualuxe'),
                'section' => 'performance_section',
                'type' => 'checkbox',
            ]
        );

        // Disable jQuery Migrate
        $wp_customize->add_setting(
            'disable_jquery_migrate',
            [
                'default' => true,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            ]
        );

        $wp_customize->add_control(
            'disable_jquery_migrate',
            [
                'label' => __('Disable jQuery Migrate', 'aqualuxe'),
                'description' => __('Remove jQuery Migrate script.', 'aqualuxe'),
                'section' => 'performance_section',
                'type' => 'checkbox',
            ]
        );

        // Enable Lazy Loading
        $wp_customize->add_setting(
            'enable_lazy_loading',
            [
                'default' => true,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            ]
        );

        $wp_customize->add_control(
            'enable_lazy_loading',
            [
                'label' => __('Enable Lazy Loading', 'aqualuxe'),
                'description' => __('Lazy load images and iframes.', 'aqualuxe'),
                'section' => 'performance_section',
                'type' => 'checkbox',
            ]
        );

        // Enable Critical CSS
        $wp_customize->add_setting(
            'enable_critical_css',
            [
                'default' => false,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            ]
        );

        $wp_customize->add_control(
            'enable_critical_css',
            [
                'label' => __('Enable Critical CSS', 'aqualuxe'),
                'description' => __('Inline critical CSS for above-the-fold content.', 'aqualuxe'),
                'section' => 'performance_section',
                'type' => 'checkbox',
            ]
        );

        // Enable Preloading
        $wp_customize->add_setting(
            'enable_preloading',
            [
                'default' => true,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            ]
        );

        $wp_customize->add_control(
            'enable_preloading',
            [
                'label' => __('Enable Resource Preloading', 'aqualuxe'),
                'description' => __('Preload critical resources.', 'aqualuxe'),
                'section' => 'performance_section',
                'type' => 'checkbox',
            ]
        );

        // Enable DNS Prefetch
        $wp_customize->add_setting(
            'enable_dns_prefetch',
            [
                'default' => true,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            ]
        );

        $wp_customize->add_control(
            'enable_dns_prefetch',
            [
                'label' => __('Enable DNS Prefetch', 'aqualuxe'),
                'description' => __('Add DNS prefetch for external domains.', 'aqualuxe'),
                'section' => 'performance_section',
                'type' => 'checkbox',
            ]
        );

        // DNS Prefetch Domains
        $wp_customize->add_setting(
            'dns_prefetch_domains',
            [
                'default' => "fonts.googleapis.com\nfonts.gstatic.com\ngoogle-analytics.com",
                'sanitize_callback' => 'sanitize_textarea_field',
            ]
        );

        $wp_customize->add_control(
            'dns_prefetch_domains',
            [
                'label' => __('DNS Prefetch Domains', 'aqualuxe'),
                'description' => __('Enter domains for DNS prefetch (one per line).', 'aqualuxe'),
                'section' => 'performance_section',
                'type' => 'textarea',
            ]
        );

        // Minify HTML
        $wp_customize->add_setting(
            'minify_html',
            [
                'default' => false,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            ]
        );

        $wp_customize->add_control(
            'minify_html',
            [
                'label' => __('Minify HTML', 'aqualuxe'),
                'description' => __('Minify HTML output.', 'aqualuxe'),
                'section' => 'performance_section',
                'type' => 'checkbox',
            ]
        );
    }

    /**
     * Add body classes
     *
     * @param array $classes Body classes
     * @return array
     */
    public function body_classes($classes) {
        if (get_theme_mod('enable_lazy_loading', true)) {
            $classes[] = 'lazy-loading-enabled';
        }
        
        return $classes;
    }

    /**
     * Disable emoji
     *
     * @return void
     */
    private function disable_emoji() {
        remove_action('wp_head', 'print_emoji_detection_script', 7);
        remove_action('admin_print_scripts', 'print_emoji_detection_script');
        remove_action('wp_print_styles', 'print_emoji_styles');
        remove_action('admin_print_styles', 'print_emoji_styles');
        remove_filter('the_content_feed', 'wp_staticize_emoji');
        remove_filter('comment_text_rss', 'wp_staticize_emoji');
        remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
        
        add_filter('tiny_mce_plugins', function ($plugins) {
            if (is_array($plugins)) {
                return array_diff($plugins, ['wpemoji']);
            }
            return [];
        });
        
        add_filter('wp_resource_hints', function ($urls, $relation_type) {
            if ('dns-prefetch' === $relation_type) {
                $emoji_svg_url = apply_filters('emoji_svg_url', 'https://s.w.org/images/core/emoji/');
                $urls = array_diff($urls, [$emoji_svg_url]);
            }
            return $urls;
        }, 10, 2);
    }

    /**
     * Disable embeds
     *
     * @return void
     */
    private function disable_embeds() {
        // Remove the embed script
        wp_deregister_script('wp-embed');
        
        // Remove oEmbed discovery links
        remove_action('wp_head', 'wp_oembed_add_discovery_links');
        
        // Remove oEmbed-specific JavaScript from the front-end and back-end
        remove_action('wp_head', 'wp_oembed_add_host_js');
        
        // Remove filter of the oEmbed result before any HTTP requests are made
        remove_filter('pre_oembed_result', 'wp_filter_pre_oembed_result', 10);
    }

    /**
     * Disable XML-RPC
     *
     * @return void
     */
    private function disable_xmlrpc() {
        // Disable XML-RPC methods that require authentication
        add_filter('xmlrpc_enabled', '__return_false');
        
        // Remove RSD link from header
        remove_action('wp_head', 'rsd_link');
        
        // Block WordPress XML-RPC requests
        add_filter('xmlrpc_methods', function () {
            return [];
        });
    }

    /**
     * Disable jQuery Migrate
     *
     * @return void
     */
    private function disable_jquery_migrate() {
        add_action('wp_default_scripts', function ($scripts) {
            if (!is_admin() && isset($scripts->registered['jquery'])) {
                $script = $scripts->registered['jquery'];
                
                if ($script->deps) {
                    $script->deps = array_diff($script->deps, ['jquery-migrate']);
                }
            }
        });
    }

    /**
     * Enable lazy loading
     *
     * @return void
     */
    private function enable_lazy_loading() {
        // Add loading="lazy" attribute to images
        add_filter('wp_get_attachment_image_attributes', function ($attr) {
            $attr['loading'] = 'lazy';
            return $attr;
        });
        
        // Add loading="lazy" attribute to iframes in content
        add_filter('the_content', function ($content) {
            return preg_replace('/<iframe(.*?)>/i', '<iframe$1 loading="lazy">', $content);
        });
    }

    /**
     * Add preload tags
     *
     * @return void
     */
    public function add_preload_tags() {
        if (!get_theme_mod('enable_preloading', true)) {
            return;
        }
        
        // Preload fonts
        echo '<link rel="preload" href="' . esc_url(AQUALUXE_ASSETS_URI . 'fonts/inter.woff2') . '" as="font" type="font/woff2" crossorigin>' . "\n";
        echo '<link rel="preload" href="' . esc_url(AQUALUXE_ASSETS_URI . 'fonts/playfair-display.woff2') . '" as="font" type="font/woff2" crossorigin>' . "\n";
        
        // Preload logo
        $custom_logo_id = get_theme_mod('custom_logo');
        if ($custom_logo_id) {
            $logo_url = wp_get_attachment_image_url($custom_logo_id, 'full');
            if ($logo_url) {
                echo '<link rel="preload" href="' . esc_url($logo_url) . '" as="image">' . "\n";
            }
        }
        
        // Preload critical CSS
        if (get_theme_mod('enable_critical_css', false)) {
            echo '<link rel="preload" href="' . esc_url(AQUALUXE_ASSETS_URI . 'css/critical.css') . '" as="style">' . "\n";
        }
    }

    /**
     * Add defer scripts
     *
     * @return void
     */
    public function add_defer_scripts() {
        ?>
        <script>
            // Add defer loading for non-critical resources
            function deferResources() {
                // Defer loading of images
                const lazyImages = document.querySelectorAll('img[loading="lazy"]');
                if ('loading' in HTMLImageElement.prototype) {
                    // Browser supports native lazy loading
                } else {
                    // Fallback for browsers that don't support native lazy loading
                    const lazyImageObserver = new IntersectionObserver(function(entries, observer) {
                        entries.forEach(function(entry) {
                            if (entry.isIntersecting) {
                                const lazyImage = entry.target;
                                if (lazyImage.dataset.src) {
                                    lazyImage.src = lazyImage.dataset.src;
                                }
                                if (lazyImage.dataset.srcset) {
                                    lazyImage.srcset = lazyImage.dataset.srcset;
                                }
                                lazyImage.classList.remove('lazy');
                                lazyImageObserver.unobserve(lazyImage);
                            }
                        });
                    });

                    lazyImages.forEach(function(lazyImage) {
                        lazyImageObserver.observe(lazyImage);
                    });
                }
            }

            // Execute when DOM is loaded
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', deferResources);
            } else {
                deferResources();
            }
        </script>
        <?php
    }

    /**
     * Add async/defer attributes to script tags
     *
     * @param string $tag Script tag
     * @param string $handle Script handle
     * @param string $src Script source
     * @return string
     */
    public function add_async_defer_attributes($tag, $handle, $src) {
        // Add defer attribute to non-critical scripts
        $defer_scripts = [
            'aqualuxe-lazy-loading',
            'comment-reply',
            'wp-embed',
        ];
        
        // Add async attribute to analytics scripts
        $async_scripts = [
            'google-analytics',
            'gtag',
            'gtm',
        ];
        
        if (in_array($handle, $defer_scripts, true)) {
            return str_replace(' src', ' defer src', $tag);
        }
        
        if (in_array($handle, $async_scripts, true)) {
            return str_replace(' src', ' async src', $tag);
        }
        
        return $tag;
    }

    /**
     * Add preload to styles
     *
     * @param string $html Style tag
     * @param string $handle Style handle
     * @param string $href Style href
     * @param string $media Style media
     * @return string
     */
    public function add_preload_to_styles($html, $handle, $href, $media) {
        if (!get_theme_mod('enable_preloading', true)) {
            return $html;
        }
        
        // Critical styles to preload
        $critical_styles = [
            'aqualuxe-style',
            'aqualuxe-woocommerce',
        ];
        
        if (in_array($handle, $critical_styles, true)) {
            $preload = '<link rel="preload" href="' . esc_url($href) . '" as="style" media="' . esc_attr($media) . '">' . "\n";
            return $preload . $html;
        }
        
        return $html;
    }

    /**
     * Add DNS prefetch
     *
     * @param array $hints Resource hints
     * @param string $relation_type Relation type
     * @return array
     */
    public function add_dns_prefetch($hints, $relation_type) {
        if (!get_theme_mod('enable_dns_prefetch', true)) {
            return $hints;
        }
        
        if ('dns-prefetch' === $relation_type) {
            $domains = get_theme_mod('dns_prefetch_domains', "fonts.googleapis.com\nfonts.gstatic.com\ngoogle-analytics.com");
            $domains = explode("\n", $domains);
            
            foreach ($domains as $domain) {
                $domain = trim($domain);
                if (!empty($domain)) {
                    $hints[] = '//' . $domain;
                }
            }
        }
        
        return $hints;
    }
}

// Initialize the module
new Module();