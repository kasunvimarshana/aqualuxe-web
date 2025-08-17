<?php
/**
 * Breadcrumbs
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Display breadcrumbs.
 *
 * @return void
 */
function aqualuxe_breadcrumbs() {
    // Return if breadcrumbs are disabled.
    if ( ! get_theme_mod( 'aqualuxe_enable_breadcrumbs', true ) ) {
        return;
    }

    // Return if on front page.
    if ( is_front_page() ) {
        return;
    }

    $items = aqualuxe_get_breadcrumb_items();

    if ( empty( $items ) ) {
        return;
    }
    ?>
    <div class="breadcrumbs">
        <div class="container">
            <nav class="breadcrumbs-nav" aria-label="<?php esc_attr_e( 'Breadcrumbs', 'aqualuxe' ); ?>">
                <ol class="breadcrumbs-list">
                    <?php foreach ( $items as $key => $item ) : ?>
                        <li class="breadcrumbs-item">
                            <?php if ( isset( $item['url'] ) && $key < count( $items ) - 1 ) : ?>
                                <a href="<?php echo esc_url( $item['url'] ); ?>" class="breadcrumbs-link">
                                    <?php echo esc_html( $item['text'] ); ?>
                                </a>
                                <span class="breadcrumbs-separator"><?php echo apply_filters( 'aqualuxe_breadcrumb_separator', '/' ); ?></span>
                            <?php else : ?>
                                <span class="breadcrumbs-text"><?php echo esc_html( $item['text'] ); ?></span>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ol>
            </nav>
        </div>
    </div>
    <?php
}
add_action( 'aqualuxe_after_header', 'aqualuxe_breadcrumbs', 10 );

/**
 * Get breadcrumb items.
 *
 * @return array
 */
function aqualuxe_get_breadcrumb_items() {
    $items = array();

    // Add home link.
    $items[] = array(
        'text' => __( 'Home', 'aqualuxe' ),
        'url'  => home_url( '/' ),
    );

    // Add items based on the current page.
    if ( is_home() ) {
        // Blog page.
        $items[] = array(
            'text' => __( 'Blog', 'aqualuxe' ),
        );
    } elseif ( is_category() ) {
        // Category archive.
        $items[] = array(
            'text' => __( 'Category', 'aqualuxe' ),
            'url'  => get_permalink( get_option( 'page_for_posts' ) ),
        );
        $items[] = array(
            'text' => single_cat_title( '', false ),
        );
    } elseif ( is_tag() ) {
        // Tag archive.
        $items[] = array(
            'text' => __( 'Tag', 'aqualuxe' ),
            'url'  => get_permalink( get_option( 'page_for_posts' ) ),
        );
        $items[] = array(
            'text' => single_tag_title( '', false ),
        );
    } elseif ( is_author() ) {
        // Author archive.
        $items[] = array(
            'text' => __( 'Author', 'aqualuxe' ),
            'url'  => get_permalink( get_option( 'page_for_posts' ) ),
        );
        $items[] = array(
            'text' => get_the_author(),
        );
    } elseif ( is_year() ) {
        // Year archive.
        $items[] = array(
            'text' => __( 'Year', 'aqualuxe' ),
            'url'  => get_permalink( get_option( 'page_for_posts' ) ),
        );
        $items[] = array(
            'text' => get_the_date( _x( 'Y', 'yearly archives date format', 'aqualuxe' ) ),
        );
    } elseif ( is_month() ) {
        // Month archive.
        $items[] = array(
            'text' => __( 'Month', 'aqualuxe' ),
            'url'  => get_permalink( get_option( 'page_for_posts' ) ),
        );
        $items[] = array(
            'text' => get_the_date( _x( 'F Y', 'monthly archives date format', 'aqualuxe' ) ),
        );
    } elseif ( is_day() ) {
        // Day archive.
        $items[] = array(
            'text' => __( 'Day', 'aqualuxe' ),
            'url'  => get_permalink( get_option( 'page_for_posts' ) ),
        );
        $items[] = array(
            'text' => get_the_date(),
        );
    } elseif ( is_tax( 'post_format' ) ) {
        // Post format archive.
        $items[] = array(
            'text' => __( 'Format', 'aqualuxe' ),
            'url'  => get_permalink( get_option( 'page_for_posts' ) ),
        );
        $items[] = array(
            'text' => get_post_format_string( get_post_format() ),
        );
    } elseif ( is_post_type_archive() ) {
        // Custom post type archive.
        $items[] = array(
            'text' => post_type_archive_title( '', false ),
        );
    } elseif ( is_tax() ) {
        // Custom taxonomy archive.
        $tax = get_taxonomy( get_queried_object()->taxonomy );
        $items[] = array(
            'text' => $tax->labels->name,
            'url'  => get_post_type_archive_link( $tax->object_type[0] ),
        );
        $items[] = array(
            'text' => single_term_title( '', false ),
        );
    } elseif ( is_singular( 'post' ) ) {
        // Single post.
        $items[] = array(
            'text' => __( 'Blog', 'aqualuxe' ),
            'url'  => get_permalink( get_option( 'page_for_posts' ) ),
        );

        // Add categories.
        $categories = get_the_category();
        if ( ! empty( $categories ) ) {
            $category = $categories[0];
            $items[] = array(
                'text' => $category->name,
                'url'  => get_category_link( $category->term_id ),
            );
        }

        $items[] = array(
            'text' => get_the_title(),
        );
    } elseif ( is_singular( 'page' ) ) {
        // Single page.
        $ancestors = get_post_ancestors( get_the_ID() );
        if ( ! empty( $ancestors ) ) {
            $ancestors = array_reverse( $ancestors );
            foreach ( $ancestors as $ancestor ) {
                $items[] = array(
                    'text' => get_the_title( $ancestor ),
                    'url'  => get_permalink( $ancestor ),
                );
            }
        }
        $items[] = array(
            'text' => get_the_title(),
        );
    } elseif ( is_singular() ) {
        // Single custom post type.
        $post_type = get_post_type_object( get_post_type() );
        if ( $post_type ) {
            $items[] = array(
                'text' => $post_type->labels->name,
                'url'  => get_post_type_archive_link( $post_type->name ),
            );
        }
        $items[] = array(
            'text' => get_the_title(),
        );
    } elseif ( is_search() ) {
        // Search results.
        $items[] = array(
            'text' => sprintf( __( 'Search Results for: %s', 'aqualuxe' ), get_search_query() ),
        );
    } elseif ( is_404() ) {
        // 404 page.
        $items[] = array(
            'text' => __( 'Page Not Found', 'aqualuxe' ),
        );
    }

    return apply_filters( 'aqualuxe_breadcrumb_items', $items );
}

/**
 * Add breadcrumbs options to customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 * @return void
 */
function aqualuxe_breadcrumbs_customizer( $wp_customize ) {
    // Enable Breadcrumbs.
    $wp_customize->add_setting(
        'aqualuxe_enable_breadcrumbs',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_enable_breadcrumbs',
        array(
            'label'    => __( 'Enable Breadcrumbs', 'aqualuxe' ),
            'section'  => 'aqualuxe_layout_options',
            'type'     => 'checkbox',
            'priority' => 10,
        )
    );

    // Breadcrumbs Separator.
    $wp_customize->add_setting(
        'aqualuxe_breadcrumb_separator',
        array(
            'default'           => '/',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_breadcrumb_separator',
        array(
            'label'    => __( 'Breadcrumbs Separator', 'aqualuxe' ),
            'section'  => 'aqualuxe_layout_options',
            'type'     => 'text',
            'priority' => 20,
        )
    );
}
add_action( 'customize_register', 'aqualuxe_breadcrumbs_customizer' );

/**
 * Filter breadcrumb separator.
 *
 * @param string $separator Separator.
 * @return string
 */
function aqualuxe_filter_breadcrumb_separator( $separator ) {
    $custom_separator = get_theme_mod( 'aqualuxe_breadcrumb_separator', '/' );
    return $custom_separator ? $custom_separator : $separator;
}
add_filter( 'aqualuxe_breadcrumb_separator', 'aqualuxe_filter_breadcrumb_separator' );