<?php
/**
 * Performance Optimization Module
 *
 * @package AquaLuxe
 * @subpackage Modules/Performance
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Performance Optimization Module Class
 */
class AquaLuxe_Performance_Module extends AquaLuxe_Module {
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
    protected $module_name = 'Performance Optimization';

    /**
     * Module description
     *
     * @var string
     */
    protected $module_description = 'Optimizes theme performance with lazy loading, minification, and caching';

    /**
     * Module version
     *
     * @var string
     */
    protected $version = '1.0.0';

    /**
     * Module dependencies
     *
     * @var array
     */
    protected $dependencies = array();

    /**
     * Module settings
     *
     * @var array
     */
    protected $settings = array(
        'enable_lazy_loading' => true,
        'enable_minification' => true,
        'enable_caching' => true,
        'enable_gzip' => true,
        'enable_browser_caching' => true,
        'enable_critical_css' => false,
        'enable_webp_support' => true,
        'enable_preloading' => true,
        'preload_resources' => array(),
        'defer_js' => true,
        'async_css' => false,
    );

    /**
     * Initialize the module
     *
     * @return void
     */
    public function init() {
        // Load module settings
        $this->load_settings();

        // Include module files
        $this->include_files();
    }

    /**
     * Include module files
     *
     * @return void
     */
    private function include_files() {
        // Include helper functions
        require_once dirname( __FILE__ ) . '/includes/helpers.php';

        // Include optimization functions
        require_once dirname( __FILE__ ) . '/includes/lazy-loading.php';
        require_once dirname( __FILE__ ) . '/includes/minification.php';
        require_once dirname( __FILE__ ) . '/includes/caching.php';
        require_once dirname( __FILE__ ) . '/includes/critical-css.php';
    }

    /**
     * Setup module hooks
     *
     * @return void
     */
    public function setup_hooks() {
        // Lazy loading
        if ( $this->settings['enable_lazy_loading'] ) {
            add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_lazy_loading_scripts' ) );
            add_filter( 'the_content', array( $this, 'add_lazy_loading_to_content' ), 99 );
            add_filter( 'post_thumbnail_html', array( $this, 'add_lazy_loading_to_post_thumbnail' ), 10, 5 );
            add_filter( 'wp_get_attachment_image_attributes', array( $this, 'add_lazy_loading_to_attachment_image' ), 10, 3 );
        }

        // Minification
        if ( $this->settings['enable_minification'] ) {
            add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_minified_assets' ), 999 );
            add_filter( 'style_loader_src', array( $this, 'remove_version_query_string' ), 10, 2 );
            add_filter( 'script_loader_src', array( $this, 'remove_version_query_string' ), 10, 2 );
        }

        // Caching
        if ( $this->settings['enable_caching'] ) {
            add_action( 'wp_head', array( $this, 'add_cache_control_headers' ) );
        }

        // Browser caching
        if ( $this->settings['enable_browser_caching'] ) {
            add_action( 'wp_loaded', array( $this, 'add_browser_caching_rules' ) );
        }

        // Critical CSS
        if ( $this->settings['enable_critical_css'] ) {
            add_action( 'wp_head', array( $this, 'add_critical_css' ), 1 );
            add_filter( 'style_loader_tag', array( $this, 'add_async_attribute_to_css' ), 10, 4 );
        }

        // WebP support
        if ( $this->settings['enable_webp_support'] ) {
            add_filter( 'wp_get_attachment_image_src', array( $this, 'use_webp_image_if_available' ), 10, 4 );
            add_filter( 'the_content', array( $this, 'replace_images_with_webp' ), 100 );
        }

        // Preloading
        if ( $this->settings['enable_preloading'] ) {
            add_action( 'wp_head', array( $this, 'add_preload_tags' ), 1 );
        }

        // Defer JavaScript
        if ( $this->settings['defer_js'] ) {
            add_filter( 'script_loader_tag', array( $this, 'add_defer_attribute' ), 10, 3 );
        }

        // Async CSS
        if ( $this->settings['async_css'] ) {
            add_filter( 'style_loader_tag', array( $this, 'add_async_attribute_to_css' ), 10, 4 );
        }

        // Remove unnecessary WordPress features
        add_action( 'init', array( $this, 'remove_unnecessary_features' ) );

        // Add DNS prefetch
        add_action( 'wp_head', array( $this, 'add_dns_prefetch' ), 1 );

        // Add resource hints
        add_filter( 'wp_resource_hints', array( $this, 'add_resource_hints' ), 10, 2 );
    }

    /**
     * Enqueue lazy loading scripts
     *
     * @return void
     */
    public function enqueue_lazy_loading_scripts() {
        wp_enqueue_script(
            'aqualuxe-lazy-loading',
            AQUALUXE_THEME_URI . 'modules/performance/assets/js/lazy-loading.js',
            array( 'jquery' ),
            $this->version,
            true
        );

        wp_localize_script(
            'aqualuxe-lazy-loading',
            'aqualuxeLazyLoading',
            array(
                'threshold' => 200,
                'fadeIn' => true,
                'fadeInDuration' => 400,
            )
        );
    }

    /**
     * Add lazy loading to content images
     *
     * @param string $content Post content.
     * @return string
     */
    public function add_lazy_loading_to_content( $content ) {
        if ( is_admin() || is_feed() ) {
            return $content;
        }

        // Replace images with lazy loading attributes
        $content = preg_replace_callback(
            '/<img([^>]+)>/i',
            array( $this, 'add_lazy_loading_attributes_to_image' ),
            $content
        );

        return $content;
    }

    /**
     * Add lazy loading attributes to image
     *
     * @param array $matches Regex matches.
     * @return string
     */
    public function add_lazy_loading_attributes_to_image( $matches ) {
        $image = $matches[0];
        $attributes = $matches[1];

        // Skip if already has lazy loading attributes
        if ( strpos( $attributes, 'data-src' ) !== false || strpos( $attributes, 'loading="lazy"' ) !== false ) {
            return $image;
        }

        // Extract src attribute
        preg_match( '/src=(["\'])(.*?)\1/', $attributes, $src_matches );
        if ( empty( $src_matches ) ) {
            return $image;
        }

        $src = $src_matches[2];

        // Create placeholder
        $placeholder = 'data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 1 1\'%3E%3C/svg%3E';

        // Replace src with data-src and add placeholder
        $new_attributes = str_replace(
            'src="' . $src . '"',
            'src="' . $placeholder . '" data-src="' . $src . '" class="lazy-load"',
            $attributes
        );

        // Add loading="lazy" for native lazy loading
        if ( strpos( $new_attributes, 'loading=' ) === false ) {
            $new_attributes .= ' loading="lazy"';
        }

        return '<img' . $new_attributes . '>';
    }

    /**
     * Add lazy loading to post thumbnail
     *
     * @param string $html Post thumbnail HTML.
     * @param int    $post_id Post ID.
     * @param int    $post_thumbnail_id Post thumbnail ID.
     * @param string $size Size.
     * @param array  $attr Attributes.
     * @return string
     */
    public function add_lazy_loading_to_post_thumbnail( $html, $post_id, $post_thumbnail_id, $size, $attr ) {
        if ( is_admin() || is_feed() ) {
            return $html;
        }

        return $this->add_lazy_loading_to_content( $html );
    }

    /**
     * Add lazy loading to attachment image
     *
     * @param array  $attr Attributes.
     * @param object $attachment Attachment.
     * @param string $size Size.
     * @return array
     */
    public function add_lazy_loading_to_attachment_image( $attr, $attachment, $size ) {
        if ( is_admin() || is_feed() ) {
            return $attr;
        }

        // Skip if already has lazy loading attributes
        if ( isset( $attr['data-src'] ) || ( isset( $attr['loading'] ) && $attr['loading'] === 'lazy' ) ) {
            return $attr;
        }

        // Add lazy loading attributes
        $attr['data-src'] = $attr['src'];
        $attr['src'] = 'data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 1 1\'%3E%3C/svg%3E';
        $attr['class'] = isset( $attr['class'] ) ? $attr['class'] . ' lazy-load' : 'lazy-load';
        $attr['loading'] = 'lazy';

        return $attr;
    }

    /**
     * Enqueue minified assets
     *
     * @return void
     */
    public function enqueue_minified_assets() {
        // Use minified versions of assets in production
        if ( ! defined( 'SCRIPT_DEBUG' ) || ! SCRIPT_DEBUG ) {
            // Replace non-minified assets with minified versions
            global $wp_scripts, $wp_styles;

            // Scripts
            foreach ( $wp_scripts->registered as $handle => $script ) {
                if ( strpos( $script->src, '.min.js' ) === false && strpos( $script->src, AQUALUXE_THEME_URI ) !== false ) {
                    $min_src = str_replace( '.js', '.min.js', $script->src );
                    if ( file_exists( str_replace( AQUALUXE_THEME_URI, AQUALUXE_THEME_DIR, $min_src ) ) ) {
                        $script->src = $min_src;
                    }
                }
            }

            // Styles
            foreach ( $wp_styles->registered as $handle => $style ) {
                if ( strpos( $style->src, '.min.css' ) === false && strpos( $style->src, AQUALUXE_THEME_URI ) !== false ) {
                    $min_src = str_replace( '.css', '.min.css', $style->src );
                    if ( file_exists( str_replace( AQUALUXE_THEME_URI, AQUALUXE_THEME_DIR, $min_src ) ) ) {
                        $style->src = $min_src;
                    }
                }
            }
        }
    }

    /**
     * Remove version query string from assets
     *
     * @param string $src Asset URL.
     * @param string $handle Asset handle.
     * @return string
     */
    public function remove_version_query_string( $src, $handle ) {
        if ( strpos( $src, 'ver=' ) ) {
            $src = remove_query_arg( 'ver', $src );
        }
        return $src;
    }

    /**
     * Add cache control headers
     *
     * @return void
     */
    public function add_cache_control_headers() {
        if ( ! is_admin() && ! is_user_logged_in() ) {
            header( 'Cache-Control: public, max-age=86400, s-maxage=86400' );
        }
    }

    /**
     * Add browser caching rules
     *
     * @return void
     */
    public function add_browser_caching_rules() {
        // Check if .htaccess exists and is writable
        $htaccess_file = ABSPATH . '.htaccess';
        if ( file_exists( $htaccess_file ) && is_writable( $htaccess_file ) ) {
            $htaccess_content = file_get_contents( $htaccess_file );

            // Check if browser caching rules already exist
            if ( strpos( $htaccess_content, '# AquaLuxe Browser Caching Rules' ) === false ) {
                $browser_caching_rules = "
# AquaLuxe Browser Caching Rules
<IfModule mod_expires.c>
    ExpiresActive On
    ExpiresByType image/jpg &quot;access plus 1 year&quot;
    ExpiresByType image/jpeg &quot;access plus 1 year&quot;
    ExpiresByType image/gif &quot;access plus 1 year&quot;
    ExpiresByType image/png &quot;access plus 1 year&quot;
    ExpiresByType image/webp &quot;access plus 1 year&quot;
    ExpiresByType image/svg+xml &quot;access plus 1 year&quot;
    ExpiresByType image/x-icon &quot;access plus 1 year&quot;
    ExpiresByType video/mp4 &quot;access plus 1 year&quot;
    ExpiresByType video/webm &quot;access plus 1 year&quot;
    ExpiresByType text/css &quot;access plus 1 month&quot;
    ExpiresByType text/javascript &quot;access plus 1 month&quot;
    ExpiresByType application/javascript &quot;access plus 1 month&quot;
    ExpiresByType application/pdf &quot;access plus 1 month&quot;
    ExpiresByType application/x-font-woff &quot;access plus 1 year&quot;
    ExpiresByType application/font-woff2 &quot;access plus 1 year&quot;
    ExpiresByType font/woff &quot;access plus 1 year&quot;
    ExpiresByType font/woff2 &quot;access plus 1 year&quot;
</IfModule>

<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/plain
    AddOutputFilterByType DEFLATE text/html
    AddOutputFilterByType DEFLATE text/xml
    AddOutputFilterByType DEFLATE text/css
    AddOutputFilterByType DEFLATE text/javascript
    AddOutputFilterByType DEFLATE application/xml
    AddOutputFilterByType DEFLATE application/xhtml+xml
    AddOutputFilterByType DEFLATE application/rss+xml
    AddOutputFilterByType DEFLATE application/javascript
    AddOutputFilterByType DEFLATE application/x-javascript
    AddOutputFilterByType DEFLATE application/x-font-ttf
    AddOutputFilterByType DEFLATE application/vnd.ms-fontobject
    AddOutputFilterByType DEFLATE font/opentype font/ttf font/eot font/otf
</IfModule>
# End AquaLuxe Browser Caching Rules
";

                // Add browser caching rules to .htaccess
                $htaccess_content = $browser_caching_rules . $htaccess_content;
                file_put_contents( $htaccess_file, $htaccess_content );
            }
        }
    }

    /**
     * Add critical CSS
     *
     * @return void
     */
    public function add_critical_css() {
        $critical_css_file = AQUALUXE_THEME_DIR . 'modules/performance/assets/css/critical.css';

        if ( file_exists( $critical_css_file ) ) {
            $critical_css = file_get_contents( $critical_css_file );
            echo '<style id="aqualuxe-critical-css">' . wp_strip_all_tags( $critical_css ) . '</style>';
        }
    }

    /**
     * Add async attribute to CSS
     *
     * @param string $html HTML.
     * @param string $handle Handle.
     * @param string $href HREF.
     * @param string $media Media.
     * @return string
     */
    public function add_async_attribute_to_css( $html, $handle, $href, $media ) {
        // Skip critical CSS
        if ( $handle === 'aqualuxe-critical-css' ) {
            return $html;
        }

        // Skip admin styles
        if ( is_admin() ) {
            return $html;
        }

        // Add async attribute
        $html = str_replace( "media='$media'", "media='print' onload=&quot;this.media='$media'&quot;", $html );

        return $html;
    }

    /**
     * Use WebP image if available
     *
     * @param array  $image Image data.
     * @param int    $attachment_id Attachment ID.
     * @param string $size Size.
     * @param bool   $icon Icon.
     * @return array
     */
    public function use_webp_image_if_available( $image, $attachment_id, $size, $icon ) {
        if ( ! $image ) {
            return $image;
        }

        // Check if browser supports WebP
        if ( ! isset( $_SERVER['HTTP_ACCEPT'] ) || strpos( $_SERVER['HTTP_ACCEPT'], 'image/webp' ) === false ) {
            return $image;
        }

        // Check if WebP version exists
        $webp_file = str_replace( array( '.jpg', '.jpeg', '.png' ), '.webp', $image[0] );
        $webp_path = str_replace( site_url(), ABSPATH, $webp_file );

        if ( file_exists( $webp_path ) ) {
            $image[0] = $webp_file;
        }

        return $image;
    }

    /**
     * Replace images with WebP
     *
     * @param string $content Content.
     * @return string
     */
    public function replace_images_with_webp( $content ) {
        // Check if browser supports WebP
        if ( ! isset( $_SERVER['HTTP_ACCEPT'] ) || strpos( $_SERVER['HTTP_ACCEPT'], 'image/webp' ) === false ) {
            return $content;
        }

        // Replace image URLs with WebP versions
        $content = preg_replace_callback(
            '/<img([^>]+)src=(["\'])(.*?)\2([^>]*)>/i',
            function( $matches ) {
                $img_tag = $matches[0];
                $src = $matches[3];

                // Skip if already WebP
                if ( strpos( $src, '.webp' ) !== false ) {
                    return $img_tag;
                }

                // Check if WebP version exists
                $webp_src = preg_replace( '/\.(jpg|jpeg|png)$/i', '.webp', $src );
                $webp_path = str_replace( site_url(), ABSPATH, $webp_src );

                if ( file_exists( $webp_path ) ) {
                    return str_replace( $src, $webp_src, $img_tag );
                }

                return $img_tag;
            },
            $content
        );

        return $content;
    }

    /**
     * Add preload tags
     *
     * @return void
     */
    public function add_preload_tags() {
        // Preload fonts
        $fonts = array(
            'fonts/roboto-v20-latin-regular.woff2',
            'fonts/roboto-v20-latin-700.woff2',
        );

        foreach ( $fonts as $font ) {
            $font_url = AQUALUXE_ASSETS_URI . 'fonts/' . $font;
            echo '<link rel="preload" href="' . esc_url( $font_url ) . '" as="font" type="font/woff2" crossorigin>' . "\n";
        }

        // Preload critical assets
        $preload_resources = $this->settings['preload_resources'];
        if ( ! empty( $preload_resources ) && is_array( $preload_resources ) ) {
            foreach ( $preload_resources as $resource ) {
                if ( isset( $resource['url'] ) && isset( $resource['type'] ) ) {
                    echo '<link rel="preload" href="' . esc_url( $resource['url'] ) . '" as="' . esc_attr( $resource['type'] ) . '"';
                    if ( $resource['type'] === 'font' ) {
                        echo ' crossorigin';
                    }
                    echo '>' . "\n";
                }
            }
        }
    }

    /**
     * Add defer attribute to scripts
     *
     * @param string $tag Script tag.
     * @param string $handle Script handle.
     * @param string $src Script source.
     * @return string
     */
    public function add_defer_attribute( $tag, $handle, $src ) {
        // Skip jQuery
        if ( $handle === 'jquery' || $handle === 'jquery-core' || $handle === 'jquery-migrate' ) {
            return $tag;
        }

        // Skip admin scripts
        if ( is_admin() ) {
            return $tag;
        }

        // Add defer attribute
        if ( strpos( $tag, 'defer' ) === false ) {
            $tag = str_replace( ' src', ' defer src', $tag );
        }

        return $tag;
    }

    /**
     * Remove unnecessary WordPress features
     *
     * @return void
     */
    public function remove_unnecessary_features() {
        // Remove emoji support
        remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
        remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
        remove_action( 'wp_print_styles', 'print_emoji_styles' );
        remove_action( 'admin_print_styles', 'print_emoji_styles' );
        remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
        remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
        remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );

        // Remove WordPress version
        remove_action( 'wp_head', 'wp_generator' );

        // Remove wlwmanifest link
        remove_action( 'wp_head', 'wlwmanifest_link' );

        // Remove RSD link
        remove_action( 'wp_head', 'rsd_link' );

        // Remove shortlink
        remove_action( 'wp_head', 'wp_shortlink_wp_head' );

        // Remove REST API link
        remove_action( 'wp_head', 'rest_output_link_wp_head' );

        // Remove oEmbed links
        remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
        remove_action( 'wp_head', 'wp_oembed_add_host_js' );
    }

    /**
     * Add DNS prefetch
     *
     * @return void
     */
    public function add_dns_prefetch() {
        $domains = array(
            'fonts.googleapis.com',
            'fonts.gstatic.com',
            'ajax.googleapis.com',
            'apis.google.com',
            'google-analytics.com',
            'www.google-analytics.com',
            'ssl.google-analytics.com',
            'youtube.com',
            'i.ytimg.com',
            'gravatar.com',
            'secure.gravatar.com',
        );

        foreach ( $domains as $domain ) {
            echo '<link rel="dns-prefetch" href="//' . esc_attr( $domain ) . '">' . "\n";
        }
    }

    /**
     * Add resource hints
     *
     * @param array  $hints Resource hints.
     * @param string $relation_type Relation type.
     * @return array
     */
    public function add_resource_hints( $hints, $relation_type ) {
        if ( 'preconnect' === $relation_type ) {
            $domains = array(
                'https://fonts.googleapis.com',
                'https://fonts.gstatic.com',
            );

            foreach ( $domains as $domain ) {
                $hints[] = array(
                    'href' => $domain,
                    'crossorigin' => 'anonymous',
                );
            }
        }

        return $hints;
    }

    /**
     * Register customizer settings
     *
     * @param WP_Customize_Manager $wp_customize Theme Customizer object.
     * @return void
     */
    public function register_customizer_settings( $wp_customize ) {
        // Add Performance section
        $wp_customize->add_section(
            'aqualuxe_performance',
            array(
                'title'    => __( 'Performance Optimization', 'aqualuxe' ),
                'priority' => 40,
            )
        );

        // Lazy loading setting
        $wp_customize->add_setting(
            'aqualuxe_performance_enable_lazy_loading',
            array(
                'default'           => $this->settings['enable_lazy_loading'],
                'type'              => 'option',
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_performance_enable_lazy_loading',
            array(
                'label'    => __( 'Enable Lazy Loading', 'aqualuxe' ),
                'section'  => 'aqualuxe_performance',
                'type'     => 'checkbox',
                'priority' => 10,
            )
        );

        // Minification setting
        $wp_customize->add_setting(
            'aqualuxe_performance_enable_minification',
            array(
                'default'           => $this->settings['enable_minification'],
                'type'              => 'option',
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_performance_enable_minification',
            array(
                'label'    => __( 'Enable Minification', 'aqualuxe' ),
                'section'  => 'aqualuxe_performance',
                'type'     => 'checkbox',
                'priority' => 20,
            )
        );

        // Caching setting
        $wp_customize->add_setting(
            'aqualuxe_performance_enable_caching',
            array(
                'default'           => $this->settings['enable_caching'],
                'type'              => 'option',
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_performance_enable_caching',
            array(
                'label'    => __( 'Enable Caching', 'aqualuxe' ),
                'section'  => 'aqualuxe_performance',
                'type'     => 'checkbox',
                'priority' => 30,
            )
        );

        // Browser caching setting
        $wp_customize->add_setting(
            'aqualuxe_performance_enable_browser_caching',
            array(
                'default'           => $this->settings['enable_browser_caching'],
                'type'              => 'option',
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_performance_enable_browser_caching',
            array(
                'label'    => __( 'Enable Browser Caching', 'aqualuxe' ),
                'section'  => 'aqualuxe_performance',
                'type'     => 'checkbox',
                'priority' => 40,
            )
        );

        // GZIP setting
        $wp_customize->add_setting(
            'aqualuxe_performance_enable_gzip',
            array(
                'default'           => $this->settings['enable_gzip'],
                'type'              => 'option',
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_performance_enable_gzip',
            array(
                'label'    => __( 'Enable GZIP Compression', 'aqualuxe' ),
                'section'  => 'aqualuxe_performance',
                'type'     => 'checkbox',
                'priority' => 50,
            )
        );

        // Critical CSS setting
        $wp_customize->add_setting(
            'aqualuxe_performance_enable_critical_css',
            array(
                'default'           => $this->settings['enable_critical_css'],
                'type'              => 'option',
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_performance_enable_critical_css',
            array(
                'label'    => __( 'Enable Critical CSS', 'aqualuxe' ),
                'section'  => 'aqualuxe_performance',
                'type'     => 'checkbox',
                'priority' => 60,
            )
        );

        // WebP support setting
        $wp_customize->add_setting(
            'aqualuxe_performance_enable_webp_support',
            array(
                'default'           => $this->settings['enable_webp_support'],
                'type'              => 'option',
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_performance_enable_webp_support',
            array(
                'label'    => __( 'Enable WebP Support', 'aqualuxe' ),
                'section'  => 'aqualuxe_performance',
                'type'     => 'checkbox',
                'priority' => 70,
            )
        );

        // Preloading setting
        $wp_customize->add_setting(
            'aqualuxe_performance_enable_preloading',
            array(
                'default'           => $this->settings['enable_preloading'],
                'type'              => 'option',
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_performance_enable_preloading',
            array(
                'label'    => __( 'Enable Resource Preloading', 'aqualuxe' ),
                'section'  => 'aqualuxe_performance',
                'type'     => 'checkbox',
                'priority' => 80,
            )
        );

        // Defer JS setting
        $wp_customize->add_setting(
            'aqualuxe_performance_defer_js',
            array(
                'default'           => $this->settings['defer_js'],
                'type'              => 'option',
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_performance_defer_js',
            array(
                'label'    => __( 'Defer JavaScript Loading', 'aqualuxe' ),
                'section'  => 'aqualuxe_performance',
                'type'     => 'checkbox',
                'priority' => 90,
            )
        );

        // Async CSS setting
        $wp_customize->add_setting(
            'aqualuxe_performance_async_css',
            array(
                'default'           => $this->settings['async_css'],
                'type'              => 'option',
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_performance_async_css',
            array(
                'label'    => __( 'Load CSS Asynchronously', 'aqualuxe' ),
                'section'  => 'aqualuxe_performance',
                'type'     => 'checkbox',
                'priority' => 100,
            )
        );
    }
}