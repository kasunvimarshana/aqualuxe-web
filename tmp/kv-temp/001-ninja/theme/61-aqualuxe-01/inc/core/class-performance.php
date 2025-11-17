<?php
/**
 * Performance optimization functionality.
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

namespace AquaLuxe\Core;

/**
 * Performance class.
 *
 * Handles performance optimizations like lazy loading, critical CSS, etc.
 */
class Performance {

    /**
     * Constructor.
     */
    public function __construct() {
        $this->init_hooks();
    }

    /**
     * Initialize hooks.
     */
    private function init_hooks() {
        // Lazy loading for images.
        add_filter( 'wp_get_attachment_image_attributes', array( $this, 'add_lazyload_to_attachment_images' ), 10, 3 );
        add_filter( 'the_content', array( $this, 'add_lazyload_to_content_images' ) );
        add_filter( 'post_thumbnail_html', array( $this, 'add_lazyload_to_post_thumbnails' ), 10, 5 );
        add_filter( 'get_avatar', array( $this, 'add_lazyload_to_avatars' ), 10, 6 );
        
        // Add preload for critical assets.
        add_action( 'wp_head', array( $this, 'add_preload_assets' ), 1 );
        
        // Add critical CSS.
        add_action( 'wp_head', array( $this, 'add_critical_css' ), 1 );
        
        // Responsive images.
        add_filter( 'wp_calculate_image_srcset', array( $this, 'customize_image_srcset' ), 10, 5 );
        add_filter( 'wp_calculate_image_sizes', array( $this, 'customize_image_sizes' ), 10, 5 );
    }

    /**
     * Add lazy loading attributes to attachment images.
     *
     * @param array   $attr       Attributes for the image markup.
     * @param WP_Post $attachment Image attachment post.
     * @param string  $size       Requested image size.
     * @return array Modified attributes.
     */
    public function add_lazyload_to_attachment_images( $attr, $attachment, $size ) {
        // Skip lazy loading for admin area.
        if ( is_admin() ) {
            return $attr;
        }

        // Skip if already has loading attribute.
        if ( isset( $attr['loading'] ) ) {
            return $attr;
        }

        // Add loading attribute.
        $attr['loading'] = 'lazy';
        
        // Add data-src attribute for custom lazy loading.
        if ( isset( $attr['src'] ) ) {
            $attr['data-src'] = $attr['src'];
            
            // Add a low-quality placeholder if needed.
            // $attr['src'] = $this->get_placeholder_image( $attachment->ID, $size );
        }
        
        // Add data-srcset attribute for custom lazy loading.
        if ( isset( $attr['srcset'] ) ) {
            $attr['data-srcset'] = $attr['srcset'];
            // $attr['srcset'] = ''; // Uncomment if using custom lazy loading.
        }
        
        // Add lazy loading class.
        $attr['class'] = isset( $attr['class'] ) ? $attr['class'] . ' lazyload' : 'lazyload';
        
        return $attr;
    }

    /**
     * Add lazy loading to content images.
     *
     * @param string $content The content.
     * @return string Modified content.
     */
    public function add_lazyload_to_content_images( $content ) {
        // Skip lazy loading for admin area or feeds.
        if ( is_admin() || is_feed() ) {
            return $content;
        }

        // Don't lazy load if the content has already been processed.
        if ( false !== strpos( $content, 'loading="lazy"' ) ) {
            return $content;
        }

        // Add loading attribute to images.
        $content = preg_replace( 
            '/<img([^>]+)>/i', 
            '<img$1 loading="lazy" class="lazyload">', 
            $content 
        );

        return $content;
    }

    /**
     * Add lazy loading to post thumbnails.
     *
     * @param string       $html              The post thumbnail HTML.
     * @param int          $post_id           The post ID.
     * @param int          $post_thumbnail_id The post thumbnail ID.
     * @param string|array $size              The post thumbnail size.
     * @param string       $attr              Query string of attributes.
     * @return string Modified HTML.
     */
    public function add_lazyload_to_post_thumbnails( $html, $post_id, $post_thumbnail_id, $size, $attr ) {
        // Skip lazy loading for admin area.
        if ( is_admin() ) {
            return $html;
        }

        // Don't lazy load if the HTML has already been processed.
        if ( false !== strpos( $html, 'loading="lazy"' ) ) {
            return $html;
        }

        // Add loading attribute to images.
        $html = preg_replace( 
            '/<img([^>]+)>/i', 
            '<img$1 loading="lazy" class="lazyload">', 
            $html 
        );

        return $html;
    }

    /**
     * Add lazy loading to avatars.
     *
     * @param string $avatar      HTML for the user's avatar.
     * @param mixed  $id_or_email The avatar to retrieve.
     * @param int    $size        Square avatar width and height in pixels.
     * @param string $default     URL for the default image.
     * @param string $alt         Alternative text.
     * @param array  $args        Avatar arguments.
     * @return string Modified HTML.
     */
    public function add_lazyload_to_avatars( $avatar, $id_or_email, $size, $default, $alt, $args ) {
        // Skip lazy loading for admin area.
        if ( is_admin() ) {
            return $avatar;
        }

        // Don't lazy load if the avatar has already been processed.
        if ( false !== strpos( $avatar, 'loading="lazy"' ) ) {
            return $avatar;
        }

        // Add loading attribute to images.
        $avatar = preg_replace( 
            '/<img([^>]+)>/i', 
            '<img$1 loading="lazy" class="lazyload">', 
            $avatar 
        );

        return $avatar;
    }

    /**
     * Get a placeholder image for lazy loading.
     *
     * @param int          $attachment_id The attachment ID.
     * @param string|array $size          The image size.
     * @return string Placeholder image URL.
     */
    private function get_placeholder_image( $attachment_id, $size ) {
        // Get image dimensions.
        $dimensions = wp_get_attachment_image_src( $attachment_id, $size );
        
        if ( ! $dimensions ) {
            return 'data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1 1"%3E%3C/svg%3E';
        }
        
        $width = $dimensions[1];
        $height = $dimensions[2];
        
        // Create SVG placeholder with correct aspect ratio.
        return 'data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 ' . $width . ' ' . $height . '"%3E%3C/svg%3E';
    }

    /**
     * Add preload for critical assets.
     */
    public function add_preload_assets() {
        // Preload fonts.
        $fonts = array(
            get_template_directory_uri() . '/assets/fonts/open-sans-v34-latin-regular.woff2',
            get_template_directory_uri() . '/assets/fonts/open-sans-v34-latin-700.woff2',
            get_template_directory_uri() . '/assets/fonts/montserrat-v25-latin-600.woff2',
            get_template_directory_uri() . '/assets/fonts/montserrat-v25-latin-700.woff2',
        );
        
        foreach ( $fonts as $font ) {
            echo '<link rel="preload" href="' . esc_url( $font ) . '" as="font" type="font/woff2" crossorigin>' . "\n";
        }
        
        // Preload critical CSS.
        echo '<link rel="preload" href="' . esc_url( get_template_directory_uri() . '/assets/css/critical.css' ) . '" as="style">' . "\n";
        
        // Preload logo.
        $custom_logo_id = get_theme_mod( 'custom_logo' );
        if ( $custom_logo_id ) {
            $logo_src = wp_get_attachment_image_src( $custom_logo_id, 'full' );
            if ( $logo_src ) {
                echo '<link rel="preload" href="' . esc_url( $logo_src[0] ) . '" as="image">' . "\n";
            }
        }
    }

    /**
     * Add critical CSS.
     */
    public function add_critical_css() {
        $critical_css_path = get_template_directory() . '/assets/css/critical.css';
        
        if ( file_exists( $critical_css_path ) ) {
            echo '<style id="aqualuxe-critical-css">';
            // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
            echo file_get_contents( $critical_css_path );
            echo '</style>' . "\n";
        }
    }

    /**
     * Customize image srcset.
     *
     * @param array  $sources       Sources for the image srcset.
     * @param array  $size_array    Array of width and height values.
     * @param string $image_src     The 'src' of the image.
     * @param array  $image_meta    The image meta data.
     * @param int    $attachment_id The image attachment ID.
     * @return array Modified sources.
     */
    public function customize_image_srcset( $sources, $size_array, $image_src, $image_meta, $attachment_id ) {
        // Customize srcset if needed.
        return $sources;
    }

    /**
     * Customize image sizes.
     *
     * @param string       $sizes         The 'sizes' attribute value.
     * @param array|string $size          Requested image size.
     * @param string       $image_src     The 'src' of the image.
     * @param array        $image_meta    The image meta data.
     * @param int          $attachment_id The image attachment ID.
     * @return string Modified sizes.
     */
    public function customize_image_sizes( $sizes, $size, $image_src, $image_meta, $attachment_id ) {
        // Customize sizes if needed.
        return $sizes;
    }
}