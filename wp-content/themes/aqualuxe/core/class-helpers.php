<?php
/**
 * Helper Functions Class
 *
 * Utility functions and helpers for the theme
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * AquaLuxe Helpers Class
 */
class AquaLuxe_Helpers {

    /**
     * Sanitize HTML content
     *
     * @param string $content
     * @return string
     */
    public static function sanitize_html( $content ) {
        $allowed_html = wp_kses_allowed_html( 'post' );
        
        // Add some additional allowed tags
        $allowed_html['iframe'] = array(
            'src'             => array(),
            'height'          => array(),
            'width'           => array(),
            'frameborder'     => array(),
            'allowfullscreen' => array(),
        );
        
        return wp_kses( $content, $allowed_html );
    }

    /**
     * Get theme option with default fallback
     *
     * @param string $option_name
     * @param mixed  $default
     * @return mixed
     */
    public static function get_theme_option( $option_name, $default = '' ) {
        return get_theme_mod( $option_name, $default );
    }

    /**
     * Get post excerpt with custom length
     *
     * @param int    $post_id
     * @param int    $length
     * @param string $more
     * @return string
     */
    public static function get_excerpt( $post_id = null, $length = 55, $more = '...' ) {
        if ( ! $post_id ) {
            $post_id = get_the_ID();
        }

        $excerpt = get_post_field( 'post_excerpt', $post_id );
        
        if ( empty( $excerpt ) ) {
            $excerpt = get_post_field( 'post_content', $post_id );
        }

        $excerpt = strip_tags( $excerpt );
        $excerpt = wp_trim_words( $excerpt, $length, $more );

        return $excerpt;
    }

    /**
     * Get reading time estimate
     *
     * @param int $post_id
     * @return string
     */
    public static function get_reading_time( $post_id = null ) {
        if ( ! $post_id ) {
            $post_id = get_the_ID();
        }

        $content = get_post_field( 'post_content', $post_id );
        $word_count = str_word_count( strip_tags( $content ) );
        $reading_time = ceil( $word_count / 200 ); // Average reading speed

        if ( $reading_time === 1 ) {
            return '1 ' . esc_html__( 'minute read', 'aqualuxe' );
        } else {
            return $reading_time . ' ' . esc_html__( 'minutes read', 'aqualuxe' );
        }
    }

    /**
     * Generate breadcrumbs
     *
     * @return string
     */
    public static function get_breadcrumbs() {
        if ( is_front_page() ) {
            return '';
        }

        $breadcrumbs = array();
        $breadcrumbs[] = '<a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'Home', 'aqualuxe' ) . '</a>';

        if ( is_category() || is_single() ) {
            $category = get_the_category();
            if ( ! empty( $category ) ) {
                $breadcrumbs[] = '<a href="' . esc_url( get_category_link( $category[0]->term_id ) ) . '">' . esc_html( $category[0]->name ) . '</a>';
            }
        }

        if ( is_page() ) {
            $parents = get_post_ancestors( get_the_ID() );
            $parents = array_reverse( $parents );
            
            foreach ( $parents as $parent ) {
                $breadcrumbs[] = '<a href="' . esc_url( get_permalink( $parent ) ) . '">' . esc_html( get_the_title( $parent ) ) . '</a>';
            }
        }

        if ( is_single() || is_page() ) {
            $breadcrumbs[] = '<span class="current">' . get_the_title() . '</span>';
        } elseif ( is_category() ) {
            $breadcrumbs[] = '<span class="current">' . single_cat_title( '', false ) . '</span>';
        } elseif ( is_archive() ) {
            $breadcrumbs[] = '<span class="current">' . get_the_archive_title() . '</span>';
        } elseif ( is_search() ) {
            $breadcrumbs[] = '<span class="current">' . esc_html__( 'Search Results', 'aqualuxe' ) . '</span>';
        } elseif ( is_404() ) {
            $breadcrumbs[] = '<span class="current">' . esc_html__( 'Page Not Found', 'aqualuxe' ) . '</span>';
        }

        $separator = '<span class="separator">›</span>';
        return '<nav class="breadcrumbs" aria-label="' . esc_attr__( 'Breadcrumb Navigation', 'aqualuxe' ) . '">' . implode( ' ' . $separator . ' ', $breadcrumbs ) . '</nav>';
    }

    /**
     * Get social share links
     *
     * @param int $post_id
     * @return array
     */
    public static function get_social_share_links( $post_id = null ) {
        if ( ! $post_id ) {
            $post_id = get_the_ID();
        }

        $post_url = urlencode( get_permalink( $post_id ) );
        $post_title = urlencode( get_the_title( $post_id ) );
        $post_excerpt = urlencode( self::get_excerpt( $post_id, 20 ) );

        return array(
            'facebook' => array(
                'url'   => 'https://www.facebook.com/sharer/sharer.php?u=' . $post_url,
                'label' => esc_html__( 'Share on Facebook', 'aqualuxe' ),
                'icon'  => 'facebook',
            ),
            'twitter' => array(
                'url'   => 'https://twitter.com/intent/tweet?url=' . $post_url . '&text=' . $post_title,
                'label' => esc_html__( 'Share on Twitter', 'aqualuxe' ),
                'icon'  => 'twitter',
            ),
            'linkedin' => array(
                'url'   => 'https://www.linkedin.com/shareArticle?mini=true&url=' . $post_url . '&title=' . $post_title . '&summary=' . $post_excerpt,
                'label' => esc_html__( 'Share on LinkedIn', 'aqualuxe' ),
                'icon'  => 'linkedin',
            ),
            'pinterest' => array(
                'url'   => 'https://pinterest.com/pin/create/button/?url=' . $post_url . '&description=' . $post_title,
                'label' => esc_html__( 'Share on Pinterest', 'aqualuxe' ),
                'icon'  => 'pinterest',
            ),
            'email' => array(
                'url'   => 'mailto:?subject=' . $post_title . '&body=' . $post_excerpt . ' ' . $post_url,
                'label' => esc_html__( 'Share via Email', 'aqualuxe' ),
                'icon'  => 'email',
            ),
        );
    }

    /**
     * Get image alt text with fallbacks
     *
     * @param int $attachment_id
     * @return string
     */
    public static function get_image_alt( $attachment_id ) {
        $alt_text = get_post_meta( $attachment_id, '_wp_attachment_image_alt', true );
        
        if ( empty( $alt_text ) ) {
            $alt_text = get_the_title( $attachment_id );
        }

        if ( empty( $alt_text ) ) {
            $alt_text = esc_html__( 'Image', 'aqualuxe' );
        }

        return $alt_text;
    }

    /**
     * Format price with currency
     *
     * @param float  $price
     * @param string $currency
     * @return string
     */
    public static function format_price( $price, $currency = 'USD' ) {
        $formatted_price = number_format( $price, 2 );
        
        $currency_symbols = array(
            'USD' => '$',
            'EUR' => '€',
            'GBP' => '£',
            'JPY' => '¥',
            'CNY' => '¥',
            'INR' => '₹',
        );

        $symbol = isset( $currency_symbols[ $currency ] ) ? $currency_symbols[ $currency ] : $currency . ' ';
        
        return $symbol . $formatted_price;
    }

    /**
     * Check if user has capability
     *
     * @param string $capability
     * @return bool
     */
    public static function user_can( $capability ) {
        return current_user_can( $capability );
    }

    /**
     * Get theme version for cache busting
     *
     * @return string
     */
    public static function get_theme_version() {
        $theme = wp_get_theme();
        return $theme->get( 'Version' );
    }

    /**
     * Check if is mobile device
     *
     * @return bool
     */
    public static function is_mobile() {
        return wp_is_mobile();
    }

    /**
     * Get current page URL
     *
     * @return string
     */
    public static function get_current_url() {
        global $wp;
        return home_url( add_query_arg( array(), $wp->request ) );
    }

    /**
     * Generate unique ID for elements
     *
     * @param string $prefix
     * @return string
     */
    public static function generate_id( $prefix = 'aqualuxe' ) {
        return $prefix . '-' . uniqid();
    }

    /**
     * Check if page is using specific template
     *
     * @param string $template
     * @return bool
     */
    public static function is_page_template( $template ) {
        return is_page_template( $template );
    }

    /**
     * Get theme color palette
     *
     * @return array
     */
    public static function get_color_palette() {
        return array(
            'primary'   => get_theme_mod( 'primary_color', '#14b8a6' ),
            'secondary' => get_theme_mod( 'secondary_color', '#0f766e' ),
            'accent'    => get_theme_mod( 'accent_color', '#eec25a' ),
            'neutral'   => get_theme_mod( 'neutral_color', '#64748b' ),
        );
    }

    /**
     * Get typography settings
     *
     * @return array
     */
    public static function get_typography() {
        return array(
            'heading_font' => get_theme_mod( 'heading_font', 'Playfair Display' ),
            'body_font'    => get_theme_mod( 'body_font', 'Inter' ),
            'font_size'    => get_theme_mod( 'base_font_size', '16' ),
        );
    }

    /**
     * Get layout settings
     *
     * @return array
     */
    public static function get_layout() {
        return array(
            'container_width' => get_theme_mod( 'container_width', '1200' ),
            'sidebar_width'   => get_theme_mod( 'sidebar_width', '25' ),
            'grid_columns'    => get_theme_mod( 'grid_columns', '3' ),
        );
    }
}