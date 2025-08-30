<?php
/**
 * Helpers class
 *
 * @package AquaLuxe
 * @subpackage Core
 * @since 1.0.0
 */

namespace AquaLuxe\Core;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Helpers class
 */
class Helpers {
    /**
     * Get theme option
     *
     * @param string $option The option name.
     * @param mixed  $default The default value.
     * @return mixed The option value.
     */
    public static function get_option( $option, $default = false ) {
        return get_theme_mod( $option, $default );
    }

    /**
     * Get asset URL
     *
     * @param string $path The asset path.
     * @return string The asset URL.
     */
    public static function get_asset_url( $path ) {
        $assets = new Assets();
        return $assets->get_asset_url( $path );
    }

    /**
     * Get template part
     *
     * @param string $slug The template slug.
     * @param string $name The template name.
     * @param array  $args The template arguments.
     */
    public static function get_template_part( $slug, $name = '', $args = [] ) {
        if ( $args && is_array( $args ) ) {
            extract( $args ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract
        }

        $template = '';

        // Look in yourtheme/slug-name.php and yourtheme/templates/slug-name.php
        if ( $name && file_exists( AQUALUXE_DIR . "{$slug}-{$name}.php" ) ) {
            $template = AQUALUXE_DIR . "{$slug}-{$name}.php";
        } elseif ( $name && file_exists( AQUALUXE_TEMPLATES_DIR . "{$slug}-{$name}.php" ) ) {
            $template = AQUALUXE_TEMPLATES_DIR . "{$slug}-{$name}.php";
        } elseif ( file_exists( AQUALUXE_DIR . "{$slug}.php" ) ) {
            $template = AQUALUXE_DIR . "{$slug}.php";
        } elseif ( file_exists( AQUALUXE_TEMPLATES_DIR . "{$slug}.php" ) ) {
            $template = AQUALUXE_TEMPLATES_DIR . "{$slug}.php";
        }

        // Allow 3rd party plugins to filter template file from their plugin.
        $template = apply_filters( 'aqualuxe_get_template_part', $template, $slug, $name );

        if ( $template ) {
            load_template( $template, false, $args );
        }
    }

    /**
     * Get template
     *
     * @param string $template_name The template name.
     * @param array  $args The template arguments.
     * @param string $template_path The template path.
     * @param string $default_path The default path.
     */
    public static function get_template( $template_name, $args = [], $template_path = '', $default_path = '' ) {
        $template_loader = new Template_Loader();
        $template_loader::get_template( $template_name, $args, $template_path, $default_path );
    }

    /**
     * Locate template
     *
     * @param string $template_name The template name.
     * @param string $template_path The template path.
     * @param string $default_path The default path.
     * @return string The template path.
     */
    public static function locate_template( $template_name, $template_path = '', $default_path = '' ) {
        $template_loader = new Template_Loader();
        return $template_loader::locate_template( $template_name, $template_path, $default_path );
    }

    /**
     * Get post thumbnail
     *
     * @param int    $post_id The post ID.
     * @param string $size The thumbnail size.
     * @param array  $attr The thumbnail attributes.
     * @return string The post thumbnail.
     */
    public static function get_post_thumbnail( $post_id = null, $size = 'post-thumbnail', $attr = [] ) {
        if ( ! $post_id ) {
            $post_id = get_the_ID();
        }

        if ( has_post_thumbnail( $post_id ) ) {
            return get_the_post_thumbnail( $post_id, $size, $attr );
        }

        return '';
    }

    /**
     * Get post excerpt
     *
     * @param int    $post_id The post ID.
     * @param int    $length The excerpt length.
     * @param string $more The excerpt more.
     * @return string The post excerpt.
     */
    public static function get_post_excerpt( $post_id = null, $length = 55, $more = '&hellip;' ) {
        if ( ! $post_id ) {
            $post_id = get_the_ID();
        }

        $post = get_post( $post_id );
        if ( empty( $post ) ) {
            return '';
        }

        if ( post_password_required( $post ) ) {
            return esc_html__( 'This post is password protected.', 'aqualuxe' );
        }

        $excerpt = $post->post_excerpt;
        if ( ! $excerpt ) {
            $excerpt = $post->post_content;
        }

        $excerpt = strip_shortcodes( $excerpt );
        $excerpt = excerpt_remove_blocks( $excerpt );
        $excerpt = wp_strip_all_tags( $excerpt );
        $excerpt = str_replace( ']]>', ']]&gt;', $excerpt );

        $excerpt_length = apply_filters( 'excerpt_length', $length );
        $excerpt_more = apply_filters( 'excerpt_more', $more );

        $words = preg_split( '/[\n\r\t ]+/', $excerpt, $excerpt_length + 1, PREG_SPLIT_NO_EMPTY );
        if ( count( $words ) > $excerpt_length ) {
            array_pop( $words );
            $excerpt = implode( ' ', $words );
            $excerpt = $excerpt . $excerpt_more;
        } else {
            $excerpt = implode( ' ', $words );
        }

        return $excerpt;
    }

    /**
     * Get post categories
     *
     * @param int    $post_id The post ID.
     * @param string $separator The separator.
     * @return string The post categories.
     */
    public static function get_post_categories( $post_id = null, $separator = ', ' ) {
        if ( ! $post_id ) {
            $post_id = get_the_ID();
        }

        $categories = get_the_category( $post_id );
        if ( ! $categories ) {
            return '';
        }

        $output = '';
        foreach ( $categories as $category ) {
            $output .= '<a href="' . esc_url( get_category_link( $category->term_id ) ) . '" rel="category tag">' . esc_html( $category->name ) . '</a>' . $separator;
        }

        return trim( $output, $separator );
    }

    /**
     * Get post tags
     *
     * @param int    $post_id The post ID.
     * @param string $separator The separator.
     * @return string The post tags.
     */
    public static function get_post_tags( $post_id = null, $separator = ', ' ) {
        if ( ! $post_id ) {
            $post_id = get_the_ID();
        }

        $tags = get_the_tags( $post_id );
        if ( ! $tags ) {
            return '';
        }

        $output = '';
        foreach ( $tags as $tag ) {
            $output .= '<a href="' . esc_url( get_tag_link( $tag->term_id ) ) . '" rel="tag">' . esc_html( $tag->name ) . '</a>' . $separator;
        }

        return trim( $output, $separator );
    }

    /**
     * Get post author
     *
     * @param int $post_id The post ID.
     * @return string The post author.
     */
    public static function get_post_author( $post_id = null ) {
        if ( ! $post_id ) {
            $post_id = get_the_ID();
        }

        $post = get_post( $post_id );
        if ( ! $post ) {
            return '';
        }

        $author_id = $post->post_author;
        $author_name = get_the_author_meta( 'display_name', $author_id );
        $author_url = get_author_posts_url( $author_id );

        return '<a href="' . esc_url( $author_url ) . '" rel="author">' . esc_html( $author_name ) . '</a>';
    }

    /**
     * Get post date
     *
     * @param int    $post_id The post ID.
     * @param string $format The date format.
     * @return string The post date.
     */
    public static function get_post_date( $post_id = null, $format = '' ) {
        if ( ! $post_id ) {
            $post_id = get_the_ID();
        }

        $post = get_post( $post_id );
        if ( ! $post ) {
            return '';
        }

        if ( ! $format ) {
            $format = get_option( 'date_format' );
        }

        $date = get_the_date( $format, $post );
        $url = get_day_link( get_post_time( 'Y', false, $post ), get_post_time( 'm', false, $post ), get_post_time( 'j', false, $post ) );

        return '<a href="' . esc_url( $url ) . '" rel="bookmark">' . esc_html( $date ) . '</a>';
    }

    /**
     * Get post comments
     *
     * @param int $post_id The post ID.
     * @return string The post comments.
     */
    public static function get_post_comments( $post_id = null ) {
        if ( ! $post_id ) {
            $post_id = get_the_ID();
        }

        $post = get_post( $post_id );
        if ( ! $post ) {
            return '';
        }

        $comments_number = get_comments_number( $post );
        if ( ! comments_open( $post ) && $comments_number === 0 ) {
            return '';
        }

        $comments_url = get_comments_link( $post );
        $comments_text = sprintf(
            /* translators: %s: number of comments */
            _n( '%s Comment', '%s Comments', $comments_number, 'aqualuxe' ),
            number_format_i18n( $comments_number )
        );

        return '<a href="' . esc_url( $comments_url ) . '">' . esc_html( $comments_text ) . '</a>';
    }

    /**
     * Get related posts
     *
     * @param int   $post_id The post ID.
     * @param int   $number The number of posts.
     * @param array $args The query arguments.
     * @return array The related posts.
     */
    public static function get_related_posts( $post_id = null, $number = 3, $args = [] ) {
        if ( ! $post_id ) {
            $post_id = get_the_ID();
        }

        $post = get_post( $post_id );
        if ( ! $post ) {
            return [];
        }

        $categories = get_the_category( $post_id );
        if ( ! $categories ) {
            return [];
        }

        $category_ids = [];
        foreach ( $categories as $category ) {
            $category_ids[] = $category->term_id;
        }

        $default_args = [
            'post_type'      => 'post',
            'posts_per_page' => $number,
            'post__not_in'   => [ $post_id ],
            'category__in'   => $category_ids,
        ];

        $args = wp_parse_args( $args, $default_args );
        $query = new \WP_Query( $args );

        return $query->posts;
    }

    /**
     * Get breadcrumbs
     *
     * @return string The breadcrumbs.
     */
    public static function get_breadcrumbs() {
        if ( is_front_page() ) {
            return '';
        }

        $output = '<nav class="breadcrumbs" aria-label="' . esc_attr__( 'Breadcrumbs', 'aqualuxe' ) . '">';
        $output .= '<ol class="breadcrumbs-list">';
        $output .= '<li class="breadcrumbs-item"><a href="' . esc_url( home_url() ) . '">' . esc_html__( 'Home', 'aqualuxe' ) . '</a></li>';

        if ( is_category() ) {
            $category = get_queried_object();
            $output .= '<li class="breadcrumbs-item">' . esc_html( $category->name ) . '</li>';
        } elseif ( is_tag() ) {
            $tag = get_queried_object();
            $output .= '<li class="breadcrumbs-item">' . esc_html( $tag->name ) . '</li>';
        } elseif ( is_author() ) {
            $author = get_queried_object();
            $output .= '<li class="breadcrumbs-item">' . esc_html( $author->display_name ) . '</li>';
        } elseif ( is_year() ) {
            $output .= '<li class="breadcrumbs-item">' . esc_html( get_the_date( 'Y' ) ) . '</li>';
        } elseif ( is_month() ) {
            $output .= '<li class="breadcrumbs-item">' . esc_html( get_the_date( 'F Y' ) ) . '</li>';
        } elseif ( is_day() ) {
            $output .= '<li class="breadcrumbs-item">' . esc_html( get_the_date() ) . '</li>';
        } elseif ( is_tax() ) {
            $term = get_queried_object();
            $output .= '<li class="breadcrumbs-item">' . esc_html( $term->name ) . '</li>';
        } elseif ( is_post_type_archive() ) {
            $post_type = get_post_type_object( get_post_type() );
            $output .= '<li class="breadcrumbs-item">' . esc_html( $post_type->labels->name ) . '</li>';
        } elseif ( is_single() ) {
            $post_type = get_post_type_object( get_post_type() );
            if ( $post_type && $post_type->has_archive ) {
                $output .= '<li class="breadcrumbs-item"><a href="' . esc_url( get_post_type_archive_link( get_post_type() ) ) . '">' . esc_html( $post_type->labels->name ) . '</a></li>';
            }
            $output .= '<li class="breadcrumbs-item">' . esc_html( get_the_title() ) . '</li>';
        } elseif ( is_page() ) {
            $output .= '<li class="breadcrumbs-item">' . esc_html( get_the_title() ) . '</li>';
        } elseif ( is_search() ) {
            $output .= '<li class="breadcrumbs-item">' . esc_html__( 'Search Results', 'aqualuxe' ) . '</li>';
        } elseif ( is_404() ) {
            $output .= '<li class="breadcrumbs-item">' . esc_html__( 'Page Not Found', 'aqualuxe' ) . '</li>';
        }

        $output .= '</ol>';
        $output .= '</nav>';

        return $output;
    }

    /**
     * Get pagination
     *
     * @param array $args The pagination arguments.
     * @return string The pagination.
     */
    public static function get_pagination( $args = [] ) {
        $defaults = [
            'total'     => 1,
            'current'   => 1,
            'format'    => '?paged=%#%',
            'base'      => get_pagenum_link( 1 ) . '%_%',
            'prev_text' => '&laquo;',
            'next_text' => '&raquo;',
            'type'      => 'array',
            'end_size'  => 3,
            'mid_size'  => 3,
        ];

        $args = wp_parse_args( $args, $defaults );
        $links = paginate_links( $args );

        if ( ! $links ) {
            return '';
        }

        $output = '<nav class="pagination" aria-label="' . esc_attr__( 'Pagination', 'aqualuxe' ) . '">';
        $output .= '<ul class="pagination-list">';

        foreach ( $links as $link ) {
            $output .= '<li class="pagination-item">' . $link . '</li>';
        }

        $output .= '</ul>';
        $output .= '</nav>';

        return $output;
    }

    /**
     * Get social links
     *
     * @return string The social links.
     */
    public static function get_social_links() {
        $social_links = [
            'facebook'  => [
                'url'   => get_theme_mod( 'aqualuxe_social_facebook', '' ),
                'icon'  => 'fab fa-facebook-f',
                'label' => esc_html__( 'Facebook', 'aqualuxe' ),
            ],
            'twitter'   => [
                'url'   => get_theme_mod( 'aqualuxe_social_twitter', '' ),
                'icon'  => 'fab fa-twitter',
                'label' => esc_html__( 'Twitter', 'aqualuxe' ),
            ],
            'instagram' => [
                'url'   => get_theme_mod( 'aqualuxe_social_instagram', '' ),
                'icon'  => 'fab fa-instagram',
                'label' => esc_html__( 'Instagram', 'aqualuxe' ),
            ],
            'linkedin'  => [
                'url'   => get_theme_mod( 'aqualuxe_social_linkedin', '' ),
                'icon'  => 'fab fa-linkedin-in',
                'label' => esc_html__( 'LinkedIn', 'aqualuxe' ),
            ],
            'youtube'   => [
                'url'   => get_theme_mod( 'aqualuxe_social_youtube', '' ),
                'icon'  => 'fab fa-youtube',
                'label' => esc_html__( 'YouTube', 'aqualuxe' ),
            ],
            'pinterest' => [
                'url'   => get_theme_mod( 'aqualuxe_social_pinterest', '' ),
                'icon'  => 'fab fa-pinterest-p',
                'label' => esc_html__( 'Pinterest', 'aqualuxe' ),
            ],
        ];

        $output = '<div class="social-links">';
        foreach ( $social_links as $network => $data ) {
            if ( ! empty( $data['url'] ) ) {
                $output .= '<a href="' . esc_url( $data['url'] ) . '" class="social-link social-link-' . esc_attr( $network ) . '" target="_blank" rel="noopener noreferrer" aria-label="' . esc_attr( $data['label'] ) . '">';
                $output .= '<i class="' . esc_attr( $data['icon'] ) . '" aria-hidden="true"></i>';
                $output .= '</a>';
            }
        }
        $output .= '</div>';

        return $output;
    }

    /**
     * Get contact info
     *
     * @return string The contact info.
     */
    public static function get_contact_info() {
        $phone = get_theme_mod( 'aqualuxe_contact_phone', '' );
        $email = get_theme_mod( 'aqualuxe_contact_email', '' );
        $address = get_theme_mod( 'aqualuxe_contact_address', '' );

        $output = '<div class="contact-info">';
        if ( ! empty( $phone ) ) {
            $output .= '<div class="contact-info-item contact-info-phone">';
            $output .= '<i class="fas fa-phone" aria-hidden="true"></i>';
            $output .= '<a href="tel:' . esc_attr( preg_replace( '/[^0-9+]/', '', $phone ) ) . '">' . esc_html( $phone ) . '</a>';
            $output .= '</div>';
        }
        if ( ! empty( $email ) ) {
            $output .= '<div class="contact-info-item contact-info-email">';
            $output .= '<i class="fas fa-envelope" aria-hidden="true"></i>';
            $output .= '<a href="mailto:' . esc_attr( $email ) . '">' . esc_html( $email ) . '</a>';
            $output .= '</div>';
        }
        if ( ! empty( $address ) ) {
            $output .= '<div class="contact-info-item contact-info-address">';
            $output .= '<i class="fas fa-map-marker-alt" aria-hidden="true"></i>';
            $output .= '<span>' . esc_html( $address ) . '</span>';
            $output .= '</div>';
        }
        $output .= '</div>';

        return $output;
    }

    /**
     * Get logo
     *
     * @param string $type The logo type.
     * @return string The logo.
     */
    public static function get_logo( $type = 'default' ) {
        $logo_id = '';
        $logo_class = 'site-logo';

        switch ( $type ) {
            case 'dark':
                $logo_id = get_theme_mod( 'aqualuxe_logo_dark' );
                $logo_class .= ' site-logo-dark';
                break;
            case 'light':
                $logo_id = get_theme_mod( 'aqualuxe_logo_light' );
                $logo_class .= ' site-logo-light';
                break;
            case 'mobile':
                $logo_id = get_theme_mod( 'aqualuxe_logo_mobile' );
                $logo_class .= ' site-logo-mobile';
                break;
            default:
                $logo_id = get_theme_mod( 'custom_logo' );
                break;
        }

        $logo_url = wp_get_attachment_image_url( $logo_id, 'full' );
        $logo_width = get_theme_mod( 'aqualuxe_logo_width', 200 );
        $logo_height = get_theme_mod( 'aqualuxe_logo_height', 60 );

        if ( $logo_url ) {
            $output = '<a href="' . esc_url( home_url( '/' ) ) . '" class="' . esc_attr( $logo_class ) . '" rel="home">';
            $output .= '<img src="' . esc_url( $logo_url ) . '" alt="' . esc_attr( get_bloginfo( 'name' ) ) . '" width="' . esc_attr( $logo_width ) . '" height="' . esc_attr( $logo_height ) . '">';
            $output .= '</a>';
        } else {
            $output = '<a href="' . esc_url( home_url( '/' ) ) . '" class="site-title" rel="home">' . esc_html( get_bloginfo( 'name' ) ) . '</a>';
            $description = get_bloginfo( 'description', 'display' );
            if ( $description || is_customize_preview() ) {
                $output .= '<p class="site-description">' . esc_html( $description ) . '</p>';
            }
        }

        return $output;
    }

    /**
     * Get menu
     *
     * @param string $location The menu location.
     * @param array  $args The menu arguments.
     * @return string The menu.
     */
    public static function get_menu( $location, $args = [] ) {
        if ( ! has_nav_menu( $location ) ) {
            return '';
        }

        $defaults = [
            'theme_location' => $location,
            'container'      => false,
            'echo'           => false,
            'fallback_cb'    => false,
        ];

        $args = wp_parse_args( $args, $defaults );
        return wp_nav_menu( $args );
    }

    /**
     * Get sidebar
     *
     * @param string $sidebar The sidebar ID.
     * @return string The sidebar.
     */
    public static function get_sidebar( $sidebar = 'sidebar-1' ) {
        if ( ! is_active_sidebar( $sidebar ) ) {
            return '';
        }

        ob_start();
        dynamic_sidebar( $sidebar );
        return ob_get_clean();
    }

    /**
     * Get page title
     *
     * @return string The page title.
     */
    public static function get_page_title() {
        if ( is_front_page() ) {
            return '';
        }

        $title = '';

        if ( is_home() ) {
            $title = get_the_title( get_option( 'page_for_posts', true ) );
        } elseif ( is_category() ) {
            $title = single_cat_title( '', false );
        } elseif ( is_tag() ) {
            $title = single_tag_title( '', false );
        } elseif ( is_author() ) {
            $title = get_the_author();
        } elseif ( is_year() ) {
            $title = get_the_date( 'Y' );
        } elseif ( is_month() ) {
            $title = get_the_date( 'F Y' );
        } elseif ( is_day() ) {
            $title = get_the_date();
        } elseif ( is_tax() ) {
            $title = single_term_title( '', false );
        } elseif ( is_post_type_archive() ) {
            $title = post_type_archive_title( '', false );
        } elseif ( is_single() || is_page() ) {
            $title = get_the_title();
        } elseif ( is_search() ) {
            $title = sprintf(
                /* translators: %s: search query */
                esc_html__( 'Search Results for: %s', 'aqualuxe' ),
                '<span>' . get_search_query() . '</span>'
            );
        } elseif ( is_404() ) {
            $title = esc_html__( 'Page Not Found', 'aqualuxe' );
        }

        return $title;
    }

    /**
     * Get page description
     *
     * @return string The page description.
     */
    public static function get_page_description() {
        $description = '';

        if ( is_category() || is_tag() || is_tax() ) {
            $description = term_description();
        } elseif ( is_author() ) {
            $description = get_the_author_meta( 'description' );
        } elseif ( is_post_type_archive() ) {
            $post_type = get_post_type_object( get_post_type() );
            if ( $post_type ) {
                $description = $post_type->description;
            }
        }

        return $description;
    }

    /**
     * Is WooCommerce active
     *
     * @return bool Whether WooCommerce is active.
     */
    public static function is_woocommerce_active() {
        return class_exists( 'WooCommerce' );
    }

    /**
     * Is EDD active
     *
     * @return bool Whether EDD is active.
     */
    public static function is_edd_active() {
        return class_exists( 'Easy_Digital_Downloads' );
    }

    /**
     * Is WPML active
     *
     * @return bool Whether WPML is active.
     */
    public static function is_wpml_active() {
        return class_exists( 'SitePress' );
    }

    /**
     * Is Polylang active
     *
     * @return bool Whether Polylang is active.
     */
    public static function is_polylang_active() {
        return function_exists( 'pll_current_language' );
    }

    /**
     * Is RTL
     *
     * @return bool Whether the site is RTL.
     */
    public static function is_rtl() {
        return is_rtl();
    }

    /**
     * Is dark mode
     *
     * @return bool Whether dark mode is enabled.
     */
    public static function is_dark_mode() {
        $default = get_theme_mod( 'aqualuxe_dark_mode_default', false );
        $cookie = isset( $_COOKIE['aqualuxe_dark_mode'] ) ? sanitize_text_field( wp_unslash( $_COOKIE['aqualuxe_dark_mode'] ) ) : '';

        if ( $cookie === 'true' ) {
            return true;
        } elseif ( $cookie === 'false' ) {
            return false;
        }

        return $default;
    }

    /**
     * Get current language
     *
     * @return string The current language.
     */
    public static function get_current_language() {
        if ( self::is_wpml_active() ) {
            return apply_filters( 'wpml_current_language', '' );
        } elseif ( self::is_polylang_active() ) {
            return pll_current_language();
        }

        return get_locale();
    }

    /**
     * Get language switcher
     *
     * @return string The language switcher.
     */
    public static function get_language_switcher() {
        if ( self::is_wpml_active() ) {
            ob_start();
            do_action( 'wpml_add_language_selector' );
            return ob_get_clean();
        } elseif ( self::is_polylang_active() && function_exists( 'pll_the_languages' ) ) {
            $args = [
                'show_flags' => 1,
                'show_names' => 1,
                'echo'       => 0,
            ];
            return '<div class="language-switcher">' . pll_the_languages( $args ) . '</div>';
        }

        return '';
    }

    /**
     * Get currency switcher
     *
     * @return string The currency switcher.
     */
    public static function get_currency_switcher() {
        if ( self::is_woocommerce_active() && class_exists( 'WOOCS' ) ) {
            ob_start();
            echo do_shortcode( '[woocs]' );
            return ob_get_clean();
        }

        return '';
    }

    /**
     * Get mini cart
     *
     * @return string The mini cart.
     */
    public static function get_mini_cart() {
        if ( ! self::is_woocommerce_active() ) {
            return '';
        }

        ob_start();
        woocommerce_mini_cart();
        return ob_get_clean();
    }

    /**
     * Get cart count
     *
     * @return int The cart count.
     */
    public static function get_cart_count() {
        if ( ! self::is_woocommerce_active() ) {
            return 0;
        }

        return WC()->cart ? WC()->cart->get_cart_contents_count() : 0;
    }

    /**
     * Get cart total
     *
     * @return string The cart total.
     */
    public static function get_cart_total() {
        if ( ! self::is_woocommerce_active() ) {
            return '';
        }

        return WC()->cart ? WC()->cart->get_cart_total() : '';
    }

    /**
     * Get product price
     *
     * @param int $product_id The product ID.
     * @return string The product price.
     */
    public static function get_product_price( $product_id ) {
        if ( ! self::is_woocommerce_active() ) {
            return '';
        }

        $product = wc_get_product( $product_id );
        if ( ! $product ) {
            return '';
        }

        return $product->get_price_html();
    }

    /**
     * Get product rating
     *
     * @param int $product_id The product ID.
     * @return string The product rating.
     */
    public static function get_product_rating( $product_id ) {
        if ( ! self::is_woocommerce_active() ) {
            return '';
        }

        $product = wc_get_product( $product_id );
        if ( ! $product ) {
            return '';
        }

        if ( ! $product->get_rating_count() ) {
            return '';
        }

        $rating_html = wc_get_rating_html( $product->get_average_rating(), $product->get_rating_count() );
        if ( ! $rating_html ) {
            return '';
        }

        return $rating_html;
    }

    /**
     * Get product categories
     *
     * @param int    $product_id The product ID.
     * @param string $separator The separator.
     * @return string The product categories.
     */
    public static function get_product_categories( $product_id, $separator = ', ' ) {
        if ( ! self::is_woocommerce_active() ) {
            return '';
        }

        $product = wc_get_product( $product_id );
        if ( ! $product ) {
            return '';
        }

        return wc_get_product_category_list( $product_id, $separator );
    }

    /**
     * Get product tags
     *
     * @param int    $product_id The product ID.
     * @param string $separator The separator.
     * @return string The product tags.
     */
    public static function get_product_tags( $product_id, $separator = ', ' ) {
        if ( ! self::is_woocommerce_active() ) {
            return '';
        }

        $product = wc_get_product( $product_id );
        if ( ! $product ) {
            return '';
        }

        return wc_get_product_tag_list( $product_id, $separator );
    }

    /**
     * Get related products
     *
     * @param int   $product_id The product ID.
     * @param int   $limit The limit.
     * @param array $args The arguments.
     * @return array The related products.
     */
    public static function get_related_products( $product_id, $limit = 3, $args = [] ) {
        if ( ! self::is_woocommerce_active() ) {
            return [];
        }

        $product = wc_get_product( $product_id );
        if ( ! $product ) {
            return [];
        }

        $defaults = [
            'posts_per_page' => $limit,
            'columns'        => 3,
            'orderby'        => 'rand',
        ];

        $args = wp_parse_args( $args, $defaults );
        return array_filter( array_map( 'wc_get_product', wc_get_related_products( $product_id, $args['posts_per_page'], $product->get_upsell_ids() ) ), 'wc_products_array_filter_visible' );
    }

    /**
     * Get upsell products
     *
     * @param int   $product_id The product ID.
     * @param int   $limit The limit.
     * @param array $args The arguments.
     * @return array The upsell products.
     */
    public static function get_upsell_products( $product_id, $limit = 3, $args = [] ) {
        if ( ! self::is_woocommerce_active() ) {
            return [];
        }

        $product = wc_get_product( $product_id );
        if ( ! $product ) {
            return [];
        }

        $defaults = [
            'posts_per_page' => $limit,
            'columns'        => 3,
            'orderby'        => 'rand',
        ];

        $args = wp_parse_args( $args, $defaults );
        $upsell_ids = $product->get_upsell_ids();

        if ( ! $upsell_ids ) {
            return [];
        }

        $args['post__in'] = $upsell_ids;
        $args['post_type'] = 'product';
        $args['orderby'] = 'post__in';

        $query = new \WP_Query( $args );
        return $query->posts;
    }

    /**
     * Get cross-sell products
     *
     * @param int   $product_id The product ID.
     * @param int   $limit The limit.
     * @param array $args The arguments.
     * @return array The cross-sell products.
     */
    public static function get_cross_sell_products( $product_id, $limit = 3, $args = [] ) {
        if ( ! self::is_woocommerce_active() ) {
            return [];
        }

        $product = wc_get_product( $product_id );
        if ( ! $product ) {
            return [];
        }

        $defaults = [
            'posts_per_page' => $limit,
            'columns'        => 3,
            'orderby'        => 'rand',
        ];

        $args = wp_parse_args( $args, $defaults );
        $cross_sell_ids = $product->get_cross_sell_ids();

        if ( ! $cross_sell_ids ) {
            return [];
        }

        $args['post__in'] = $cross_sell_ids;
        $args['post_type'] = 'product';
        $args['orderby'] = 'post__in';

        $query = new \WP_Query( $args );
        return $query->posts;
    }

    /**
     * Get product gallery
     *
     * @param int $product_id The product ID.
     * @return array The product gallery.
     */
    public static function get_product_gallery( $product_id ) {
        if ( ! self::is_woocommerce_active() ) {
            return [];
        }

        $product = wc_get_product( $product_id );
        if ( ! $product ) {
            return [];
        }

        $attachment_ids = $product->get_gallery_image_ids();
        $gallery = [];

        if ( $product->get_image_id() ) {
            $gallery[] = [
                'id'  => $product->get_image_id(),
                'url' => wp_get_attachment_url( $product->get_image_id() ),
                'alt' => get_post_meta( $product->get_image_id(), '_wp_attachment_image_alt', true ),
            ];
        }

        foreach ( $attachment_ids as $attachment_id ) {
            $gallery[] = [
                'id'  => $attachment_id,
                'url' => wp_get_attachment_url( $attachment_id ),
                'alt' => get_post_meta( $attachment_id, '_wp_attachment_image_alt', true ),
            ];
        }

        return $gallery;
    }

    /**
     * Get product attributes
     *
     * @param int $product_id The product ID.
     * @return array The product attributes.
     */
    public static function get_product_attributes( $product_id ) {
        if ( ! self::is_woocommerce_active() ) {
            return [];
        }

        $product = wc_get_product( $product_id );
        if ( ! $product ) {
            return [];
        }

        $attributes = [];
        $product_attributes = $product->get_attributes();

        if ( ! $product_attributes ) {
            return [];
        }

        foreach ( $product_attributes as $attribute_name => $attribute ) {
            if ( $attribute->is_taxonomy() ) {
                $attribute_taxonomy = $attribute->get_taxonomy_object();
                $attribute_values = wc_get_product_terms( $product_id, $attribute_name, [ 'fields' => 'names' ] );
                $attribute_label = $attribute_taxonomy->attribute_label;
            } else {
                $attribute_values = $attribute->get_options();
                $attribute_label = $attribute->get_name();
            }

            $attributes[ $attribute_name ] = [
                'label'  => $attribute_label,
                'values' => $attribute_values,
            ];
        }

        return $attributes;
    }

    /**
     * Get product variations
     *
     * @param int $product_id The product ID.
     * @return array The product variations.
     */
    public static function get_product_variations( $product_id ) {
        if ( ! self::is_woocommerce_active() ) {
            return [];
        }

        $product = wc_get_product( $product_id );
        if ( ! $product || ! $product->is_type( 'variable' ) ) {
            return [];
        }

        $variations = [];
        $available_variations = $product->get_available_variations();

        foreach ( $available_variations as $variation ) {
            $variation_id = $variation['variation_id'];
            $variation_obj = wc_get_product( $variation_id );

            $variations[ $variation_id ] = [
                'id'         => $variation_id,
                'price'      => $variation_obj->get_price_html(),
                'attributes' => $variation['attributes'],
                'image'      => $variation['image'],
                'is_in_stock' => $variation_obj->is_in_stock(),
            ];
        }

        return $variations;
    }

    /**
     * Get product stock status
     *
     * @param int $product_id The product ID.
     * @return string The product stock status.
     */
    public static function get_product_stock_status( $product_id ) {
        if ( ! self::is_woocommerce_active() ) {
            return '';
        }

        $product = wc_get_product( $product_id );
        if ( ! $product ) {
            return '';
        }

        $availability = $product->get_availability();
        $availability_html = empty( $availability['availability'] ) ? '' : '<p class="stock ' . esc_attr( $availability['class'] ) . '">' . esc_html( $availability['availability'] ) . '</p>';

        return $availability_html;
    }

    /**
     * Get product dimensions
     *
     * @param int $product_id The product ID.
     * @return string The product dimensions.
     */
    public static function get_product_dimensions( $product_id ) {
        if ( ! self::is_woocommerce_active() ) {
            return '';
        }

        $product = wc_get_product( $product_id );
        if ( ! $product ) {
            return '';
        }

        return wc_format_dimensions( $product->get_dimensions( false ) );
    }

    /**
     * Get product weight
     *
     * @param int $product_id The product ID.
     * @return string The product weight.
     */
    public static function get_product_weight( $product_id ) {
        if ( ! self::is_woocommerce_active() ) {
            return '';
        }

        $product = wc_get_product( $product_id );
        if ( ! $product || ! $product->has_weight() ) {
            return '';
        }

        return wc_format_weight( $product->get_weight() );
    }

    /**
     * Get product SKU
     *
     * @param int $product_id The product ID.
     * @return string The product SKU.
     */
    public static function get_product_sku( $product_id ) {
        if ( ! self::is_woocommerce_active() ) {
            return '';
        }

        $product = wc_get_product( $product_id );
        if ( ! $product || ! $product->get_sku() ) {
            return '';
        }

        return $product->get_sku();
    }

    /**
     * Get product reviews
     *
     * @param int $product_id The product ID.
     * @return array The product reviews.
     */
    public static function get_product_reviews( $product_id ) {
        if ( ! self::is_woocommerce_active() ) {
            return [];
        }

        $product = wc_get_product( $product_id );
        if ( ! $product ) {
            return [];
        }

        $args = [
            'post_id' => $product_id,
            'status'  => 'approve',
        ];

        return get_comments( $args );
    }

    /**
     * Get product review count
     *
     * @param int $product_id The product ID.
     * @return int The product review count.
     */
    public static function get_product_review_count( $product_id ) {
        if ( ! self::is_woocommerce_active() ) {
            return 0;
        }

        $product = wc_get_product( $product_id );
        if ( ! $product ) {
            return 0;
        }

        return $product->get_review_count();
    }

    /**
     * Get product average rating
     *
     * @param int $product_id The product ID.
     * @return float The product average rating.
     */
    public static function get_product_average_rating( $product_id ) {
        if ( ! self::is_woocommerce_active() ) {
            return 0;
        }

        $product = wc_get_product( $product_id );
        if ( ! $product ) {
            return 0;
        }

        return $product->get_average_rating();
    }

    /**
     * Get product rating count
     *
     * @param int $product_id The product ID.
     * @return int The product rating count.
     */
    public static function get_product_rating_count( $product_id ) {
        if ( ! self::is_woocommerce_active() ) {
            return 0;
        }

        $product = wc_get_product( $product_id );
        if ( ! $product ) {
            return 0;
        }

        return $product->get_rating_count();
    }

    /**
     * Get product rating counts
     *
     * @param int $product_id The product ID.
     * @return array The product rating counts.
     */
    public static function get_product_rating_counts( $product_id ) {
        if ( ! self::is_woocommerce_active() ) {
            return [];
        }

        $product = wc_get_product( $product_id );
        if ( ! $product ) {
            return [];
        }

        return $product->get_rating_counts();
    }

    /**
     * Get product add to cart URL
     *
     * @param int $product_id The product ID.
     * @return string The product add to cart URL.
     */
    public static function get_product_add_to_cart_url( $product_id ) {
        if ( ! self::is_woocommerce_active() ) {
            return '';
        }

        $product = wc_get_product( $product_id );
        if ( ! $product ) {
            return '';
        }

        return $product->add_to_cart_url();
    }

    /**
     * Get product add to cart text
     *
     * @param int $product_id The product ID.
     * @return string The product add to cart text.
     */
    public static function get_product_add_to_cart_text( $product_id ) {
        if ( ! self::is_woocommerce_active() ) {
            return '';
        }

        $product = wc_get_product( $product_id );
        if ( ! $product ) {
            return '';
        }

        return $product->add_to_cart_text();
    }

    /**
     * Get product permalink
     *
     * @param int $product_id The product ID.
     * @return string The product permalink.
     */
    public static function get_product_permalink( $product_id ) {
        if ( ! self::is_woocommerce_active() ) {
            return '';
        }

        $product = wc_get_product( $product_id );
        if ( ! $product ) {
            return '';
        }

        return $product->get_permalink();
    }

    /**
     * Get product thumbnail
     *
     * @param int    $product_id The product ID.
     * @param string $size The thumbnail size.
     * @return string The product thumbnail.
     */
    public static function get_product_thumbnail( $product_id, $size = 'woocommerce_thumbnail' ) {
        if ( ! self::is_woocommerce_active() ) {
            return '';
        }

        $product = wc_get_product( $product_id );
        if ( ! $product ) {
            return '';
        }

        return $product->get_image( $size );
    }

    /**
     * Get product short description
     *
     * @param int $product_id The product ID.
     * @return string The product short description.
     */
    public static function get_product_short_description( $product_id ) {
        if ( ! self::is_woocommerce_active() ) {
            return '';
        }

        $product = wc_get_product( $product_id );
        if ( ! $product ) {
            return '';
        }

        return $product->get_short_description();
    }

    /**
     * Get product description
     *
     * @param int $product_id The product ID.
     * @return string The product description.
     */
    public static function get_product_description( $product_id ) {
        if ( ! self::is_woocommerce_active() ) {
            return '';
        }

        $product = wc_get_product( $product_id );
        if ( ! $product ) {
            return '';
        }

        return $product->get_description();
    }

    /**
     * Get product type
     *
     * @param int $product_id The product ID.
     * @return string The product type.
     */
    public static function get_product_type( $product_id ) {
        if ( ! self::is_woocommerce_active() ) {
            return '';
        }

        $product = wc_get_product( $product_id );
        if ( ! $product ) {
            return '';
        }

        return $product->get_type();
    }

    /**
     * Get product price
     *
     * @param int $product_id The product ID.
     * @return float The product price.
     */
    public static function get_product_price_raw( $product_id ) {
        if ( ! self::is_woocommerce_active() ) {
            return 0;
        }

        $product = wc_get_product( $product_id );
        if ( ! $product ) {
            return 0;
        }

        return $product->get_price();
    }

    /**
     * Get product regular price
     *
     * @param int $product_id The product ID.
     * @return float The product regular price.
     */
    public static function get_product_regular_price( $product_id ) {
        if ( ! self::is_woocommerce_active() ) {
            return 0;
        }

        $product = wc_get_product( $product_id );
        if ( ! $product ) {
            return 0;
        }

        return $product->get_regular_price();
    }

    /**
     * Get product sale price
     *
     * @param int $product_id The product ID.
     * @return float The product sale price.
     */
    public static function get_product_sale_price( $product_id ) {
        if ( ! self::is_woocommerce_active() ) {
            return 0;
        }

        $product = wc_get_product( $product_id );
        if ( ! $product ) {
            return 0;
        }

        return $product->get_sale_price();
    }

    /**
     * Is product on sale
     *
     * @param int $product_id The product ID.
     * @return bool Whether the product is on sale.
     */
    public static function is_product_on_sale( $product_id ) {
        if ( ! self::is_woocommerce_active() ) {
            return false;
        }

        $product = wc_get_product( $product_id );
        if ( ! $product ) {
            return false;
        }

        return $product->is_on_sale();
    }

    /**
     * Is product in stock
     *
     * @param int $product_id The product ID.
     * @return bool Whether the product is in stock.
     */
    public static function is_product_in_stock( $product_id ) {
        if ( ! self::is_woocommerce_active() ) {
            return false;
        }

        $product = wc_get_product( $product_id );
        if ( ! $product ) {
            return false;
        }

        return $product->is_in_stock();
    }

    /**
     * Is product purchasable
     *
     * @param int $product_id The product ID.
     * @return bool Whether the product is purchasable.
     */
    public static function is_product_purchasable( $product_id ) {
        if ( ! self::is_woocommerce_active() ) {
            return false;
        }

        $product = wc_get_product( $product_id );
        if ( ! $product ) {
            return false;
        }

        return $product->is_purchasable();
    }

    /**
     * Is product downloadable
     *
     * @param int $product_id The product ID.
     * @return bool Whether the product is downloadable.
     */
    public static function is_product_downloadable( $product_id ) {
        if ( ! self::is_woocommerce_active() ) {
            return false;
        }

        $product = wc_get_product( $product_id );
        if ( ! $product ) {
            return false;
        }

        return $product->is_downloadable();
    }

    /**
     * Is product virtual
     *
     * @param int $product_id The product ID.
     * @return bool Whether the product is virtual.
     */
    public static function is_product_virtual( $product_id ) {
        if ( ! self::is_woocommerce_active() ) {
            return false;
        }

        $product = wc_get_product( $product_id );
        if ( ! $product ) {
            return false;
        }

        return $product->is_virtual();
    }

    /**
     * Is product featured
     *
     * @param int $product_id The product ID.
     * @return bool Whether the product is featured.
     */
    public static function is_product_featured( $product_id ) {
        if ( ! self::is_woocommerce_active() ) {
            return false;
        }

        $product = wc_get_product( $product_id );
        if ( ! $product ) {
            return false;
        }

        return $product->is_featured();
    }

    /**
     * Is product visible
     *
     * @param int $product_id The product ID.
     * @return bool Whether the product is visible.
     */
    public static function is_product_visible( $product_id ) {
        if ( ! self::is_woocommerce_active() ) {
            return false;
        }

        $product = wc_get_product( $product_id );
        if ( ! $product ) {
            return false;
        }

        return $product->is_visible();
    }

    /**
     * Is product taxable
     *
     * @param int $product_id The product ID.
     * @return bool Whether the product is taxable.
     */
    public static function is_product_taxable( $product_id ) {
        if ( ! self::is_woocommerce_active() ) {
            return false;
        }

        $product = wc_get_product( $product_id );
        if ( ! $product ) {
            return false;
        }

        return $product->is_taxable();
    }

    /**
     * Is product shipping taxable
     *
     * @param int $product_id The product ID.
     * @return bool Whether the product is shipping taxable.
     */
    public static function is_product_shipping_taxable( $product_id ) {
        if ( ! self::is_woocommerce_active() ) {
            return false;
        }

        $product = wc_get_product( $product_id );
        if ( ! $product ) {
            return false;
        }

        return $product->is_shipping_taxable();
    }

    /**
     * Get product tax class
     *
     * @param int $product_id The product ID.
     * @return string The product tax class.
     */
    public static function get_product_tax_class( $product_id ) {
        if ( ! self::is_woocommerce_active() ) {
            return '';
        }

        $product = wc_get_product( $product_id );
        if ( ! $product ) {
            return '';
        }

        return $product->get_tax_class();
    }

    /**
     * Get product tax status
     *
     * @param int $product_id The product ID.
     * @return string The product tax status.
     */
    public static function get_product_tax_status( $product_id ) {
        if ( ! self::is_woocommerce_active() ) {
            return '';
        }

        $product = wc_get_product( $product_id );
        if ( ! $product ) {
            return '';
        }

        return $product->get_tax_status();
    }

    /**
     * Get product shipping class
     *
     * @param int $product_id The product ID.
     * @return string The product shipping class.
     */
    public static function get_product_shipping_class( $product_id ) {
        if ( ! self::is_woocommerce_active() ) {
            return '';
        }

        $product = wc_get_product( $product_id );
        if ( ! $product ) {
            return '';
        }

        return $product->get_shipping_class();
    }

    /**
     * Get product shipping class ID
     *
     * @param int $product_id The product ID.
     * @return int The product shipping class ID.
     */
    public static function get_product_shipping_class_id( $product_id ) {
        if ( ! self::is_woocommerce_active() ) {
            return 0;
        }

        $product = wc_get_product( $product_id );
        if ( ! $product ) {
            return 0;
        }

        return $product->get_shipping_class_id();
    }

    /**
     * Get product dimensions
     *
     * @param int $product_id The product ID.
     * @return array The product dimensions.
     */
    public static function get_product_dimensions_array( $product_id ) {
        if ( ! self::is_woocommerce_active() ) {
            return [];
        }

        $product = wc_get_product( $product_id );
        if ( ! $product ) {
            return [];
        }

        return [
            'length' => $product->get_length(),
            'width'  => $product->get_width(),
            'height' => $product->get_height(),
        ];
    }

    /**
     * Get product weight
     *
     * @param int $product_id The product ID.
     * @return float The product weight.
     */
    public static function get_product_weight_raw( $product_id ) {
        if ( ! self::is_woocommerce_active() ) {
            return 0;
        }

        $product = wc_get_product( $product_id );
        if ( ! $product ) {
            return 0;
        }

        return $product->get_weight();
    }

    /**
     * Get product length
     *
     * @param int $product_id The product ID.
     * @return float The product length.
     */
    public static function get_product_length( $product_id ) {
        if ( ! self::is_woocommerce_active() ) {
            return 0;
        }

        $product = wc_get_product( $product_id );
        if ( ! $product ) {
            return 0;
        }

        return $product->get_length();
    }

    /**
     * Get product width
     *
     * @param int $product_id The product ID.
     * @return float The product width.
     */
    public static function get_product_width( $product_id ) {
        if ( ! self::is_woocommerce_active() ) {
            return 0;
        }

        $product = wc_get_product( $product_id );
        if ( ! $product ) {
            return 0;
        }

        return $product->get_width();
    }

    /**
     * Get product height
     *
     * @param int $product_id The product ID.
     * @return float The product height.
     */
    public static function get_product_height( $product_id ) {
        if ( ! self::is_woocommerce_active() ) {
            return 0;
        }

        $product = wc_get_product( $product_id );
        if ( ! $product ) {
            return 0;
        }

        return $product->get_height();
    }

    /**
     * Get product stock quantity
     *
     * @param int $product_id The product ID.
     * @return int The product stock quantity.
     */
    public static function get_product_stock_quantity( $product_id ) {
        if ( ! self::is_woocommerce_active() ) {
            return 0;
        }

        $product = wc_get_product( $product_id );
        if ( ! $product ) {
            return 0;
        }

        return $product->get_stock_quantity();
    }

    /**
     * Get product stock status
     *
     * @param int $product_id The product ID.
     * @return string The product stock status.
     */
    public static function get_product_stock_status_raw( $product_id ) {
        if ( ! self::is_woocommerce_active() ) {
            return '';
        }

        $product = wc_get_product( $product_id );
        if ( ! $product ) {
            return '';
        }

        return $product->get_stock_status();
    }

    /**
     * Get product backorders
     *
     * @param int $product_id The product ID.
     * @return string The product backorders.
     */
    public static function get_product_backorders( $product_id ) {
        if ( ! self::is_woocommerce_active() ) {
            return '';
        }

        $product = wc_get_product( $product_id );
        if ( ! $product ) {
            return '';
        }

        return $product->get_backorders();
    }

    /**
     * Get product low stock amount
     *
     * @param int $product_id The product ID.
     * @return int The product low stock amount.
     */
    public static function get_product_low_stock_amount( $product_id ) {
        if ( ! self::is_woocommerce_active() ) {
            return 0;
        }

        $product = wc_get_product( $product_id );
        if ( ! $product ) {
            return 0;
        }

        return $product->get_low_stock_amount();
    }

    /**
     * Get product sold individually
     *
     * @param int $product_id The product ID.
     * @return bool Whether the product is sold individually.
     */
    public static function get_product_sold_individually( $product_id ) {
        if ( ! self::is_woocommerce_active() ) {
            return false;
        }

        $product = wc_get_product( $product_id );
        if ( ! $product ) {
            return false;
        }

        return $product->is_sold_individually();
    }

    /**
     * Get product purchase note
     *
     * @param int $product_id The product ID.
     * @return string The product purchase note.
     */
    public static function get_product_purchase_note( $product_id ) {
        if ( ! self::is_woocommerce_active() ) {
            return '';
        }

        $product = wc_get_product( $product_id );
        if ( ! $product ) {
            return '';
        }

        return $product->get_purchase_note();
    }

    /**
     * Get product shipping required
     *
     * @param int $product_id The product ID.
     * @return bool Whether the product requires shipping.
     */
    public static function get_product_shipping_required( $product_id ) {
        if ( ! self::is_woocommerce_active() ) {
            return false;
        }

        $product = wc_get_product( $product_id );
        if ( ! $product ) {
            return false;
        }

        return $product->needs_shipping();
    }

    /**
     * Get product is downloadable
     *
     * @param int $product_id The product ID.
     * @return bool Whether the product is downloadable.
     */
    public static function get_product_is_downloadable( $product_id ) {
        if ( ! self::is_woocommerce_active() ) {
            return false;
        }

        $product = wc_get_product( $product_id );
        if ( ! $product ) {
            return false;
        }

        return $product->is_downloadable();
    }

    /**
     * Get product is virtual
     *
     * @param int $product_id The product ID.
     * @return bool Whether the product is virtual.
     */
    public static function get_product_is_virtual( $product_id ) {
        if ( ! self::is_woocommerce_active() ) {
            return false;
        }

        $product = wc_get_product( $product_id );
        if ( ! $product ) {
            return false;
        }

        return $product->is_virtual();
    }

    /**
     * Get product downloads
     *
     * @param int $product_id The product ID.
     * @return array The product downloads.
     */
    public static function get_product_downloads( $product_id ) {
        if ( ! self::is_woocommerce_active() ) {
            return [];
        }

        $product = wc_get_product( $product_id );
        if ( ! $product ) {
            return [];
        }

        return $product->get_downloads();
    }

    /**
     * Get product download expiry
     *
     * @param int $product_id The product ID.
     * @return int The product download expiry.
     */
    public static function get_product_download_expiry( $product_id ) {
        if ( ! self::is_woocommerce_active() ) {
            return 0;
        }

        $product = wc_get_product( $product_id );
        if ( ! $product ) {
            return 0;
        }

        return $product->get_download_expiry();
    }

    /**
     * Get product download limit
     *
     * @param int $product_id The product ID.
     * @return int The product download limit.
     */
    public static function get_product_download_limit( $product_id ) {
        if ( ! self::is_woocommerce_active() ) {
            return 0;
        }

        $product = wc_get_product( $product_id );
        if ( ! $product ) {
            return 0;
        }

        return $product->get_download_limit();
    }

    /**
     * Get product image ID
     *
     * @param int $product_id The product ID.
     * @return int The product image ID.
     */
    public static function get_product_image_id( $product_id ) {
        if ( ! self::is_woocommerce_active() ) {
            return 0;
        }

        $product = wc_get_product( $product_id );
        if ( ! $product ) {
            return 0;
        }

        return $product->get_image_id();
    }

    /**
     * Get product gallery image IDs
     *
     * @param int $product_id The product ID.
     * @return array The product gallery image IDs.
     */
    public static function get_product_gallery_image_ids( $product_id ) {
        if ( ! self::is_woocommerce_active() ) {
            return [];
        }

        $product = wc_get_product( $product_id );
        if ( ! $product ) {
            return [];
        }

        return $product->get_gallery_image_ids();
    }

    /**
     * Get product reviews allowed
     *
     * @param int $product_id The product ID.
     * @return bool Whether reviews are allowed for the product.
     */
    public static function get_product_reviews_allowed( $product_id ) {
        if ( ! self::is_woocommerce_active() ) {
            return false;
        }

        $product = wc_get_product( $product_id );
        if ( ! $product ) {
            return false;
        }

        return $product->get_reviews_allowed();
    }

    /**
     * Get product review count
     *
     * @param int $product_id The product ID.
     * @return int The product review count.
     */
    public static function get_product_review_count_raw( $product_id ) {
        if ( ! self::is_woocommerce_active() ) {
            return 0;
        }

        $product = wc_get_product( $product_id );
        if ( ! $product ) {
            return 0;
        }

        return $product->get_review_count();
    }

    /**
     * Get product average rating
     *
     * @param int $product_id The product ID.
     * @return float The product average rating.
     */
    public static function get_product_average_rating_raw( $product_id ) {
        if ( ! self::is_woocommerce_active() ) {
            return 0;
        }

        $product = wc_get_product( $product_id );
        if ( ! $product ) {
            return 0;
        }

        return $product->get_average_rating();
    }

    /**
     * Get product rating count
     *
     * @param int $product_id The product ID.
     * @return int The product rating count.
     */
    public static function get_product_rating_count_raw( $product_id ) {
        if ( ! self::is_woocommerce_active() ) {
            return 0;
        }

        $product = wc_get_product( $product_id );
        if ( ! $product ) {
            return 0;
        }

        return $product->get_rating_count();
    }

    /**
     * Get product rating counts
     *
     * @param int $product_id The product ID.
     * @return array The product rating counts.
     */
    public static function get_product_rating_counts_raw( $product_id ) {
        if ( ! self::is_woocommerce_active() ) {
            return [];
        }

        $product = wc_get_product( $product_id );
        if ( ! $product ) {
            return [];
        }

        return $product->get_rating_counts();
    }

    /**
     * Get product parent ID
     *
     * @param int $product_id The product ID.
     * @return int The product parent ID.
     */
    public static function get_product_parent_id( $product_id ) {
        if ( ! self::is_woocommerce_active() ) {
            return 0;
        }

        $product = wc_get_product( $product_id );
        if ( ! $product ) {
            return 0;
        }

        return $product->get_parent_id();
    }

    /**
     * Get product menu order
     *
     * @param int $product_id The product ID.
     * @return int The product menu order.
     */
    public static function get_product_menu_order( $product_id ) {
        if ( ! self::is_woocommerce_active() ) {
            return 0;
        }

        $product = wc_get_product( $product_id );
        if ( ! $product ) {
            return 0;
        }

        return $product->get_menu_order();
    }

    /**
     * Get product post password
     *
     * @param int $product_id The product ID.
     * @return string The product post password.
     */
    public static function get_product_post_password( $product_id ) {
        if ( ! self::is_woocommerce_active() ) {
            return '';
        }

        $product = wc_get_product( $product_id );
        if ( ! $product ) {
            return '';
        }

        return $product->get_post_password();
    }

    /**
     * Get product post status
     *
     * @param int $product_id The product ID.
     * @return string The product post status.
     */
    public static function get_product_post_status( $product_id ) {
        if ( ! self::is_woocommerce_active() ) {
            return '';
        }

        $product = wc_get_product( $product_id );
        if ( ! $product ) {
            return '';
        }

        return $product->get_status();
    }

    /**
     * Get product post date
     *
     * @param int $product_id The product ID.
     * @return string The product post date.
     */
    public static function get_product_post_date( $product_id ) {
        if ( ! self::is_woocommerce_active() ) {
            return '';
        }

        $product = wc_get_product( $product_id );
        if ( ! $product ) {
            return '';
        }

        return $product->get_date_created();
    }

    /**
     * Get product post modified date
     *
     * @param int $product_id The product ID.
     * @return string The product post modified date.
     */
    public static function get_product_post_modified_date( $product_id ) {
        if ( ! self::is_woocommerce_active() ) {
            return '';
        }

        $product = wc_get_product( $product_id );
        if ( ! $product ) {
            return '';
        }

        return $product->get_date_modified();
    }

    /**
     * Get product post author
     *
     * @param int $product_id The product ID.
     * @return int The product post author.
     */
    public static function get_product_post_author( $product_id ) {
        if ( ! self::is_woocommerce_active() ) {
            return 0;
        }

        $product = wc_get_product( $product_id );
        if ( ! $product ) {
            return 0;
        }

        return $product->get_post_field( 'post_author' );
    }

    /**
     * Get product post excerpt
     *
     * @param int $product_id The product ID.
     * @return string The product post excerpt.
     */
    public static function get_product_post_excerpt( $product_id ) {
        if ( ! self::is_woocommerce_active() ) {
            return '';
        }

        $product = wc_get_product( $product_id );
        if ( ! $product ) {
            return '';
        }

        return $product->get_post_field( 'post_excerpt' );
    }

    /**
     * Get product post content
     *
     * @param int $product_id The product ID.
     * @return string The product post content.
     */
    public static function get_product_post_content( $product_id ) {
        if ( ! self::is_woocommerce_active() ) {
            return '';
        }

        $product = wc_get_product( $product_id );
        if ( ! $product ) {
            return '';
        }

        return $product->get_post_field( 'post_content' );
    }

    /**
     * Get product post title
     *
     * @param int $product_id The product ID.
     * @return string The product post title.
     */
    public static function get_product_post_title( $product_id ) {
        if ( ! self::is_woocommerce_active() ) {
            return '';
        }

        $product = wc_get_product( $product_id );
        if ( ! $product ) {
            return '';
        }

        return $product->get_post_field( 'post_title' );
    }

    /**
     * Get product post name
     *
     * @param int $product_id The product ID.
     * @return string The product post name.
     */
    public static function get_product_post_name( $product_id ) {
        if ( ! self::is_woocommerce_active() ) {
            return '';
        }

        $product = wc_get_product( $product_id );
        if ( ! $product ) {
            return '';
        }

        return $product->get_post_field( 'post_name' );
    }

    /**
     * Get product post type
     *
     * @param int $product_id The product ID.
     * @return string The product post type.
     */
    public static function get_product_post_type( $product_id ) {
        if ( ! self::is_woocommerce_active() ) {
            return '';
        }

        $product = wc_get_product( $product_id );
        if ( ! $product ) {
            return '';
        }

        return $product->get_post_field( 'post_type' );
    }

    /**
     * Get product post mime type
     *
     * @param int $product_id The product ID.
     * @return string The product post mime type.
     */
    public static function get_product_post_mime_type( $product_id ) {
        if ( ! self::is_woocommerce_active() ) {
            return '';
        }

        $product = wc_get_product( $product_id );
        if ( ! $product ) {
            return '';
        }

        return $product->get_post_field( 'post_mime_type' );
    }

    /**
     * Get product post comment status
     *
     * @param int $product_id The product ID.
     * @return string The product post comment status.
     */
    public static function get_product_post_comment_status( $product_id ) {
        if ( ! self::is_woocommerce_active() ) {
            return '';
        }

        $product = wc_get_product( $product_id );
        if ( ! $product ) {
            return '';
        }

        return $product->get_post_field( 'comment_status' );
    }

    /**
     * Get product post ping status
     *
     * @param int $product_id The product ID.
     * @return string The product post ping status.
     */
    public static function get_product_post_ping_status( $product_id ) {
        if ( ! self::is_woocommerce_active() ) {
            return '';
        }

        $product = wc_get_product( $product_id );
        if ( ! $product ) {
            return '';
        }

        return $product->get_post_field( 'ping_status' );
    }

    /**
     * Get product post comment count
     *
     * @param int $product_id The product ID.
     * @return int The product post comment count.
     */
    public static function get_product_post_comment_count( $product_id ) {
        if ( ! self::is_woocommerce_active() ) {
            return 0;
        }

        $product = wc_get_product( $product_id );
        if ( ! $product ) {
            return 0;
        }

        return $product->get_post_field( 'comment_count' );
    }

    /**
     * Get product post filter
     *
     * @param int $product_id The product ID.
     * @return string The product post filter.
     */
    public static function get_product_post_filter( $product_id ) {
        if ( ! self::is_woocommerce_active() ) {
            return '';
        }

        $product = wc_get_product( $product_id );
        if ( ! $product ) {
            return '';
        }

        return $product->get_post_field( 'filter' );
    }

    /**
     * Get product post meta
     *
     * @param int    $product_id The product ID.
     * @param string $meta_key The meta key.
     * @param bool   $single Whether to return a single value.
     * @return mixed The product post meta.
     */
    public static function get_product_post_meta( $product_id, $meta_key, $single = true ) {
        if ( ! self::is_woocommerce_active() ) {
            return '';
        }

        $product = wc_get_product( $product_id );
        if ( ! $product ) {
            return '';
        }

        return get_post_meta( $product_id, $meta_key, $single );
    }

    /**
     * Get product post terms
     *
     * @param int    $product_id The product ID.
     * @param string $taxonomy The taxonomy.
     * @param array  $args The arguments.
     * @return array The product post terms.
     */
    public static function get_product_post_terms( $product_id, $taxonomy, $args = [] ) {
        if ( ! self::is_woocommerce_active() ) {
            return [];
        }

        $product = wc_get_product( $product_id );
        if ( ! $product ) {
            return [];
        }

        return wp_get_post_terms( $product_id, $taxonomy, $args );
    }

    /**
     * Get product post term IDs
     *
     * @param int    $product_id The product ID.
     * @param string $taxonomy The taxonomy.
     * @return array The product post term IDs.
     */
    public static function get_product_post_term_ids( $product_id, $taxonomy ) {
        if ( ! self::is_woocommerce_active() ) {
            return [];
        }

        $product = wc_get_product( $product_id );
        if ( ! $product ) {
            return [];
        }

        return wp_get_post_terms( $product_id, $taxonomy, [ 'fields' => 'ids' ] );
    }

    /**
     * Get product post term names
     *
     * @param int    $product_id The product ID.
     * @param string $taxonomy The taxonomy.
     * @return array The product post term names.
     */
    public static function get_product_post_term_names( $product_id, $taxonomy ) {
        if ( ! self::is_woocommerce_active() ) {
            return [];
        }

        $product = wc_get_product( $product_id );
        if ( ! $product ) {
            return [];
        }

        return wp_get_post_terms( $product_id, $taxonomy, [ 'fields' => 'names' ] );
    }

    /**
     * Get product post term slugs
     *
     * @param int    $product_id The product ID.
     * @param string $taxonomy The taxonomy.
     * @return array The product post term slugs.
     */
    public static function get_product_post_term_slugs( $product_id, $taxonomy ) {
        if ( ! self::is_woocommerce_active() ) {
            return [];
        }

        $product = wc_get_product( $product_id );
        if ( ! $product ) {
            return [];
        }

        return wp_get_post_terms( $product_id, $taxonomy, [ 'fields' => 'slugs' ] );
    }

    /**
     * Get product post term links
     *
     * @param int    $product_id The product ID.
     * @param string $taxonomy The taxonomy.
     * @return array The product post term links.
     */
    public static function get_product_post_term_links( $product_id, $taxonomy ) {
        if ( ! self::is_woocommerce_active() ) {
            return [];
        }

        $product = wc_get_product( $product_id );
        if ( ! $product ) {
            return [];
        }

        $terms = wp_get_post_terms( $product_id, $taxonomy );
        $links = [];

        foreach ( $terms as $term ) {
            $links[] = [
                'id'   => $term->term_id,
                'name' => $term->name,
                'slug' => $term->slug,
                'url'  => get_term_link( $term ),
            ];
        }

        return $links;
    }

    /**
     * Get product post term list
     *
     * @param int    $product_id The product ID.
     * @param string $taxonomy The taxonomy.
     * @param string $before The before text.
     * @param string $sep The separator.
     * @param string $after The after text.
     * @return string The product post term list.
     */
    public static function get_product_post_term_list( $product_id, $taxonomy, $before = '', $sep = ', ', $after = '' ) {
        if ( ! self::is_woocommerce_active() ) {
            return '';
        }

        $product = wc_get_product( $product_id );
        if ( ! $product ) {
            return '';
        }

        return get_the_term_list( $product_id, $taxonomy, $before, $sep, $after );
    }

    /**
     * Get product post thumbnail
     *
     * @param int    $product_id The product ID.
     * @param string $size The thumbnail size.
     * @param array  $attr The thumbnail attributes.
     * @return string The product post thumbnail.
     */
    public static function get_product_post_thumbnail( $product_id, $size = 'post-thumbnail', $attr = [] ) {
        if ( ! self::is_woocommerce_active() ) {
            return '';
        }

        $product = wc_get_product( $product_id );
        if ( ! $product ) {
            return '';
        }

        return get_the_post_thumbnail( $product_id, $size, $attr );
    }

    /**
     * Get product post thumbnail ID
     *
     * @param int $product_id The product ID.
     * @return int The product post thumbnail ID.
     */
    public static function get_product_post_thumbnail_id( $product_id ) {
        if ( ! self::is_woocommerce_active() ) {
            return 0;
        }

        $product = wc_get_product( $product_id );
        if ( ! $product ) {
            return 0;
        }

        return get_post_thumbnail_id( $product_id );
    }

    /**
     * Get product post thumbnail URL
     *
     * @param int    $product_id The product ID.
     * @param string $size The thumbnail size.
     * @return string The product post thumbnail URL.
     */
    public static function get_product_post_thumbnail_url( $product_id, $size = 'post-thumbnail' ) {
        if ( ! self::is_woocommerce_active() ) {
            return '';
        }

        $product = wc_get_product( $product_id );
        if ( ! $product ) {
            return '';
        }

        return get_the_post_thumbnail_url( $product_id, $size );
    }

    /**
     * Get product post permalink
     *
     * @param int $product_id The product ID.
     * @return string The product post permalink.
     */
    public static function get_product_post_permalink( $product_id ) {
        if ( ! self::is_woocommerce_active() ) {
            return '';
        }

        $product = wc_get_product( $product_id );
        if ( ! $product ) {
            return '';
        }

        return get_permalink( $product_id );
    }

    /**
     * Get product post edit link
     *
     * @param int $product_id The product ID.
     * @return string The product post edit link.
     */
    public static function get_product_post_edit_link( $product_id ) {
        if ( ! self::is_woocommerce_active() ) {
            return '';
        }

        $product = wc_get_product( $product_id );
        if ( ! $product ) {
            return '';
        }

        return get_edit_post_link( $product_id );
    }

    /**
     * Get product post delete link
     *
     * @param int $product_id The product ID.
     * @return string The product post delete link.
     */
    public static function get_product_post_delete_link( $product_id ) {
        if ( ! self::is_woocommerce_active() ) {
            return '';
        }

        $product = wc_get_product( $product_id );
        if ( ! $product ) {
            return '';
        }

        return get_delete_post_link( $product_id );
    }

    /**
     * Get product post comments
     *
     * @param int   $product_id The product ID.
     * @param array $args The arguments.
     * @return array The product post comments.
     */
    public static function get_product_post_comments( $product_id, $args = [] ) {
        if ( ! self::is_woocommerce_active() ) {
            return [];
        }

        $product = wc_get_product( $product_id );
        if ( ! $product ) {
            return [];
        }

        $defaults = [
            'post_id' => $product_id,
            'status'  => 'approve',
        ];

        $args = wp_parse_args( $args, $defaults );
        return get_comments( $args );
    }

    /**
     * Get product post comment count
     *
     * @param int $product_id The product ID.
     * @return int The product post comment count.
     */
    public static function get_product_post_comment_count_raw( $product_id ) {
        if ( ! self::is_woocommerce_active() ) {
            return 0;
        }

        $product = wc_get_product( $product_id );
        if ( ! $product ) {
            return 0;
        }

        return get_comments_number( $product_id );
    }

    /**
     * Get product post comment link
     *
     * @param int $product_id The product ID.
     * @return string The product post comment link.
     */
    public static function get_product_post_comment_link( $product_id ) {
        if ( ! self::is_woocommerce_active() ) {
            return '';
        }

        $product = wc_get_product( $product_id );
        if ( ! $product ) {
            return '';
        }

        return get_comments_link( $product_id );
    }

    /**
     * Get product post comment form
     *
     * @param int   $product_id The product ID.
     * @param array $args The arguments.
     * @return string The product post comment form.
     */
    public static function get_product_post_comment_form( $product_id, $args = [] ) {
        if ( ! self::is_woocommerce_active() ) {
            return '';
        }

        $product = wc_get_product( $product_id );
        if ( ! $product ) {
            return '';
        }

        ob_start();
        comment_form( $args, $product_id );
        return ob_get_clean();
    }

    /**
     * Get product post comments template
     *
     * @param int   $product_id The product ID.
     * @param array $args The arguments.
     * @return string The product post comments template.
     */
    public static function get_product_post_comments_template( $product_id, $args = [] ) {
        if ( ! self::is_woocommerce_active() ) {
            return '';
        }

        $product = wc_get_product( $product_id );
        if ( ! $product ) {
            return '';
        }

        ob_start();
        comments_template( '', true );
        return ob_get_clean();
    }

    /**
     * Get product post comments pagination
     *
     * @param int   $product_id The product ID.
     * @param array $args The arguments.
     * @return string The product post comments pagination.
     */
    public static function get_product_post_comments_pagination( $product_id, $args = [] ) {
        if ( ! self::is_woocommerce_active() ) {
            return '';
        }

        $product = wc_get_product( $product_id );
        if ( ! $product ) {
            return '';
        }

        ob_start();
        paginate_comments_links( $args );
        return ob_get_clean();
    }

    /**
     * Get product post comments number
     *
     * @param int $product_id The product ID.
     * @return string The product post comments number.
     */
    public static function get_product_post_comments_number( $product_id ) {
        if ( ! self::is_woocommerce_active() ) {
            return '';
        }

        $product = wc_get_product( $product_id );
        if ( ! $product ) {
            return '';
        }

        return get_comments_number_text( '', esc_html__( '1 Comment', 'aqualuxe' ), esc_html__( '% Comments', 'aqualuxe' ), $product_id );
    }

    /**
     * Get product post comments open
     *
     * @param int $product_id The product ID.
     * @return bool Whether comments are open for the product.
     */
    public static function get_product_post_comments_open( $product_id ) {
        if ( ! self::is_woocommerce_active() ) {
            return false;
        }

        $product = wc_get_product( $product_id );
        if ( ! $product ) {
            return false;
        }

        return comments_open( $product_id );
    }

    /**
     * Get product post comments closed
     *
     * @param int $product_id The product ID.
     * @return bool Whether comments are closed for the product.
     */
    public static function get_product_post_comments_closed( $product_id ) {
        if ( ! self::is_woocommerce_active() ) {
            return true;
        }

        $product = wc_get_product( $product_id );
        if ( ! $product ) {
            return true;
        }

        return ! comments_open( $product_id );
    }

    /**
     * Get product post comments feed link
     *
     * @param int $product_id The product ID.
     * @return string The product post comments feed link.
     */
    public static function get_product_post_comments_feed_link( $product_id ) {
        if ( ! self::is_woocommerce_active() ) {
            return '';
        }

        $product = wc_get_product( $product_id );
        if ( ! $product ) {
            return '';
        }

        return get_post_comments_feed_link( $product_id );
    }

    /**
     * Get product post comments popup link
     *
     * @param int    $product_id The product ID.
     * @param string $zero The zero text.
     * @param string $one The one text.
     * @param string $more The more text.
     * @return string The product post comments popup link.
     */
    public static function get_product_post_comments_popup_link( $product_id, $zero = '', $one = '', $more = '' ) {
        if ( ! self::is_woocommerce_active() ) {
            return '';
        }

        $product = wc_get_product( $product_id );
        if ( ! $product ) {
            return '';
        }

        ob_start();
        comments_popup_link( $zero, $one, $more, '', '', $product_id );
        return ob_get_clean();
    }

    /**
     * Get product post comments link attributes
     *
     * @param int $product_id The product ID.
     * @return string The product post comments link attributes.
     */
    public static function get_product_post_comments_link_attributes( $product_id ) {
        if ( ! self::is_woocommerce_active() ) {
            return '';
        }

        $product = wc_get_product( $product_id );
        if ( ! $product ) {
            return '';
        }

        return comments_link_attributes( $product_id );
    }

    /**
     * Get product post comments link href
     *
     * @param int $product_id The product ID.
     * @return string The product post comments link href.
     */
    public static function get_product_post_comments_link_href( $product_id ) {
        if ( ! self::is_woocommerce_active() ) {
            return '';
        }

        $product = wc_get_product( $product_id );
        if ( ! $product ) {
            return '';
        }

        return get_comments_link( $product_id );
    }

    /**
     * Get product post comments link text
     *
     * @param int $product_id The product ID.
     * @return string The product post comments link text.
     */
    public static function get_product_post_comments_link_text( $product_id ) {
        if ( ! self::is_woocommerce_active() ) {
            return '';
        }

        $product = wc_get_product( $product_id );
        if ( ! $product ) {
            return '';
        }

        return get_comments_number_text( '', esc_html__( '1 Comment', 'aqualuxe' ), esc_html__( '% Comments', 'aqualuxe' ), $product_id );
    }

    /**
     * Get product post comments link
     *
     * @param int $product_id The product ID.
     * @return string The product post comments link.
     */
    public static function get_product_post_comments_link( $product_id ) {
        if ( ! self::is_woocommerce_active() ) {
            return '';
        }

        $product = wc_get_product( $product_id );
        if ( ! $product ) {
            return '';
        }

        $href = self::get_product_post_comments_link_href( $product_id );
        $text = self::get_product_post_comments_link_text( $product_id );
        $attr = self::get_product_post_comments_link_attributes( $product_id );

        return '<a href="' . esc_url( $href ) . '" ' . $attr . '>' . $text . '</a>';
    }

    /**
     * Get product post comments template
     *
     * @param int $product_id The product ID.
     * @return string The product post comments template.
     */
    public static function get_product_post_comments_template_raw( $product_id ) {
        if ( ! self::is_woocommerce_active() ) {
            return '';
        }

        $product = wc_get_product( $product_id );
        if ( ! $product ) {
            return '';
        }

        ob_start();
        comments_template( '', true );
        return ob_get_clean();
    }

    /**
     * Get product post comments form
     *
     * @param int   $product_id The product ID.
     * @param array $args The arguments.
     * @return string The product post comments form.
     */
    public static function get_product_post_comments_form( $product_id, $args = [] ) {
        if ( ! self::is_woocommerce_active() ) {
            return '';
        }

        $product = wc_get_product( $product_id );
        if ( ! $product ) {
            return '';
        }

        ob_start();
        comment_form( $args, $product_id );
        return ob_get_clean();
    }

    /**
     * Get product post comments list
     *
     * @param int   $product_id The product ID.
     * @param array $args The arguments.
     * @return string The product post comments list.
     */
    public static function get_product_post_comments_list( $product_id, $args = [] ) {
        if ( ! self::is_woocommerce_active() ) {
            return '';
        }

        $product = wc_get_product( $product_id );
        if ( ! $product ) {
            return '';
        }

        ob_start();
        wp_list_comments( $args );
        return ob_get_clean();
    }

    /**
     * Get product post comments count
     *
     * @param int $product_id The product ID.
     * @return int The product post comments count.
     */
    public static function get_product_post_comments_count( $product_id ) {
        if ( ! self::is_woocommerce_active() ) {
            return 0;
        }

        $product = wc_get_product( $product_id );
        if ( ! $product ) {
            return 0;
        }

        return get_comments_number( $product_id );
    }

    /**
     * Get product post comments count text
     *
     * @param int    $product_id The product ID.
     * @param string $zero The zero text.
     * @param string $one The one text.
     * @param string $more The more text.
     * @return string The product post comments count text.
     */
    public static function get_product_post_comments_count_text( $product_id, $zero = '', $one = '', $more = '' ) {
        if ( ! self::is_woocommerce_active() ) {
            return '';
        }

        $product = wc_get_product( $product_id );
        if ( ! $product ) {
            return '';
        }

        return get_comments_number_text( $zero, $one, $more, $product_id );
    }

    /**
     * Get product post comments count link
     *
     * @param int    $product_id The product ID.
     * @param string $zero The zero text.
     * @param string $one The one text.
     * @param string $more The more text.
     * @return string The product post comments count link.
     */
    public static function get_product_post_comments_count_link( $product_id, $zero = '', $one = '', $more = '' ) {
        if ( ! self::is_woocommerce_active() ) {
            return '';
        }

        $product = wc_get_product( $product_id );
        if ( ! $product ) {
            return '';
        }

        $href = self::get_product_post_comments_link_href( $product_id );
        $text = self::get_product_post_comments_count_text( $product_id, $zero, $one, $more );
        $attr = self::get_product_post_comments_link_attributes( $product_id );

        return '<a href="' . esc_url( $href ) . '" ' . $attr . '>' . $text . '</a>';
    }

    /**
     * Get product post comments pagination
     *
     * @param int   $product_id The product ID.
     * @param array $args The arguments.
     * @return string The product post comments pagination.
     */
    public static function get_product_post_comments_pagination_raw( $product_id, $args = [] ) {
        if ( ! self::is_woocommerce_active() ) {
            return '';
        }

        $product = wc_get_product( $product_id );
        if ( ! $product ) {
            return '';
        }

        ob_start();
        paginate_comments_links( $args );
        return ob_get_clean();
    }

    /**
     * Get product post comments navigation
     *
     * @param int   $product_id The product ID.
     * @param array $args The arguments.
     * @return string The product post comments navigation.
     */
    public static function get_product_post_comments_navigation( $product_id, $args = [] ) {
        if ( ! self::is_woocommerce_active() ) {
            return '';
        }

        $product = wc_get_product( $product_id );
        if ( ! $product ) {
            return '';
        }

        ob_start();
        the_comments_navigation( $args );
        return ob_get_clean();
    }

    /**
     * Get product post comments pagination
     *
     * @param int   $product_id The product ID.
     * @param array $args The arguments.
     * @return string The product post comments pagination.
     */
    public static function get_product_post_comments_pagination( $product_id, $args = [] ) {
        if ( ! self::is_woocommerce_active() ) {
            return '';
        }

        $product = wc_get_product( $product_id );
        if ( ! $product ) {
            return '';
        }

        ob_start();
        the_comments_pagination( $args );
        return ob_get_clean();
    }

    /**
     * Get product post comments feed
     *
     * @param int $product_id The product ID.
     * @return string The product post comments feed.
     */
    public static function get_product_post_comments_feed( $product_id ) {
        if ( ! self::is_woocommerce_active() ) {
            return '';
        }

        $product = wc_get_product( $product_id );
        if ( ! $product ) {
            return '';
        }

        return get_post_comments_feed_link( $product_id );
    }

    /**
     * Get product post comments feed link
     *
     * @param int $product_id The product ID.
     * @return string The product post comments feed link.
     */
    public static function get_product_post_comments_feed_link_raw( $product_id ) {
        if ( ! self::is_woocommerce_active() ) {
            return '';
        }

        $product = wc_get_product( $product_id );
        if ( ! $product ) {
            return '';
        }

        $href = self::get_product_post_comments_feed( $product_id );
        $text = esc_html__( 'Comments Feed', 'aqualuxe' );

        return '<a href="' . esc_url( $href ) . '">' . $text . '</a>';
    }

    /**
     * Get product post comments feed
     *
     * @param int $product_id The product ID.
     * @return string The product post comments feed.
     */
    public static function get_product_post_comments_feed_raw( $product_id ) {
        if ( ! self::is_woocommerce_active() ) {
            return '';
        }

        $product = wc_get_product( $product_id );
        if ( ! $product ) {
            return '';
        }

        return get_post_comments_feed_link( $product_id );
    }

    /**
     * Get product post comments feed link
     *
     * @param int $product_id The product ID.
     * @return string The product post comments feed link.
     */
    public static function get_product_post_comments_feed_link_html( $product_id ) {
        if ( ! self::is_woocommerce_active() ) {
            return '';
        }

        $product = wc_get_product( $product_id );
        if ( ! $product ) {
            return '';
        }

        $href = self::get_product_post_comments_feed( $product_id );
        $text = esc_html__( 'Comments Feed', 'aqualuxe' );

        return '<a href="' . esc_url( $href ) . '">' . $text . '</a>';
    }

    /**
     * Get product post comments feed link
     *
     * @param int $product_id The product ID.
     * @return string The product post comments feed link.
     */
    public static function get_product_post_comments_feed_link_url( $product_id ) {
        if ( ! self::is_woocommerce_active() ) {
            return '';
        }

        $product = wc_get_product( $product_id );
        if ( ! $product ) {
            return '';
        }

        return get_post_comments_feed_link( $product_id );
    }

    /**
     * Get product post comments feed link
     *
     * @param int $product_id The product ID.
     * @return string The product post comments feed link.
     */
    public static function get_product_post_comments_feed_link_text( $product_id ) {
        if ( ! self::is_woocommerce_active() ) {
            return '';
        }

        $product = wc_get_product( $product_id );
        if ( ! $product ) {
            return '';
        }

        return esc_html__( 'Comments Feed', 'aqualuxe' );
    }

    /**
     * Get product post comments feed link
     *
     * @param int $product_id The product ID.
     * @return string The product post comments feed link.
     */
    public static function get_product_post_comments_feed_link_attr( $product_id ) {
        if ( ! self::is_woocommerce_active() ) {
            return '';
        }

        $product = wc_get_product( $product_id );
        if ( ! $product ) {
            return '';
        }

        return '';
    }

    /**
     * Get product post comments feed link
     *
     * @param int $product_id The product ID.
     * @return string The product post comments feed link.
     */
    public static function get_product_post_comments_feed_link_full( $product_id ) {
        if ( ! self::is_woocommerce_active() ) {
            return '';
        }

        $product = wc_get_product( $product_id );
        if ( ! $product ) {
            return '';
        }

        $href = self::get_product_post_comments_feed_link_url( $product_id );
        $text = self::get_product_post_comments_feed_link_text( $product_id );
        $attr = self::get_product_post_comments_feed_link_attr( $product_id );

        return '<a href="' . esc_url( $href ) . '" ' . $attr . '>' . $text . '</a>';
    }
}

// Initialize the class
new Helpers();