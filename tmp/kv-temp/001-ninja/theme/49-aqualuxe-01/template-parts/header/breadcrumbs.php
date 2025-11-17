<?php
/**
 * Template part for displaying breadcrumbs
 *
 * @package AquaLuxe
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// Check if breadcrumbs are enabled
if ( ! get_theme_mod( 'aqualuxe_breadcrumbs_enable', true ) ) {
    return;
}

// Don't show breadcrumbs on the front page
if ( is_front_page() ) {
    return;
}

// Get the transparent header setting
$is_transparent_header = get_theme_mod( 'aqualuxe_header_layout', 'standard' ) === 'transparent';
$breadcrumb_class = $is_transparent_header ? 'pt-32' : 'pt-8';
?>

<div class="breadcrumbs-wrapper bg-gray-100 dark:bg-gray-800 py-4 <?php echo esc_attr( $breadcrumb_class ); ?>">
    <div class="container mx-auto px-4">
        <?php
        if ( function_exists( 'woocommerce_breadcrumb' ) && ( is_woocommerce() || is_cart() || is_checkout() || is_account_page() ) ) {
            woocommerce_breadcrumb(
                array(
                    'wrap_before' => '<nav class="woocommerce-breadcrumb text-sm" aria-label="' . esc_attr__( 'Breadcrumbs', 'aqualuxe' ) . '">',
                    'wrap_after'  => '</nav>',
                    'before'      => '<span class="breadcrumb-item">',
                    'after'       => '</span>',
                    'delimiter'   => '<span class="breadcrumb-separator mx-2">/</span>',
                    'home'        => esc_html__( 'Home', 'aqualuxe' ),
                )
            );
        } else {
            aqualuxe_breadcrumbs();
        }
        ?>
    </div>
</div>

<?php
/**
 * Display breadcrumbs for non-WooCommerce pages
 */
function aqualuxe_breadcrumbs() {
    // Settings
    $separator          = '<span class="breadcrumb-separator mx-2">/</span>';
    $home_title         = esc_html__( 'Home', 'aqualuxe' );
    $breadcrumb_class   = 'breadcrumbs text-sm';
    $home_link          = home_url( '/' );
    
    // Get the query & post information
    global $post, $wp_query;
    
    // Build the breadcrumbs
    echo '<nav class="' . esc_attr( $breadcrumb_class ) . '" aria-label="' . esc_attr__( 'Breadcrumbs', 'aqualuxe' ) . '">';
    
    // Home page
    echo '<span class="breadcrumb-item"><a href="' . esc_url( $home_link ) . '">' . esc_html( $home_title ) . '</a></span>';
    
    if ( is_home() ) {
        // Blog page
        echo $separator;
        echo '<span class="breadcrumb-item current">' . esc_html__( 'Blog', 'aqualuxe' ) . '</span>';
    } elseif ( is_category() ) {
        // Category page
        echo $separator;
        $current_category = get_queried_object();
        
        // Check if the category has a parent
        if ( $current_category->parent ) {
            $parent_categories = array();
            $parent_id = $current_category->parent;
            
            while ( $parent_id ) {
                $parent_category = get_term( $parent_id, 'category' );
                $parent_categories[] = '<span class="breadcrumb-item"><a href="' . esc_url( get_category_link( $parent_category->term_id ) ) . '">' . esc_html( $parent_category->name ) . '</a></span>';
                $parent_id = $parent_category->parent;
            }
            
            // Display parent categories
            echo implode( $separator, array_reverse( $parent_categories ) );
            echo $separator;
        }
        
        echo '<span class="breadcrumb-item current">' . esc_html( $current_category->name ) . '</span>';
    } elseif ( is_tag() ) {
        // Tag page
        echo $separator;
        echo '<span class="breadcrumb-item current">' . esc_html( single_tag_title( '', false ) ) . '</span>';
    } elseif ( is_author() ) {
        // Author page
        echo $separator;
        echo '<span class="breadcrumb-item current">' . esc_html( get_the_author() ) . '</span>';
    } elseif ( is_year() ) {
        // Year archive
        echo $separator;
        echo '<span class="breadcrumb-item current">' . esc_html( get_the_date( 'Y' ) ) . '</span>';
    } elseif ( is_month() ) {
        // Month archive
        echo $separator;
        echo '<span class="breadcrumb-item"><a href="' . esc_url( get_year_link( get_the_date( 'Y' ) ) ) . '">' . esc_html( get_the_date( 'Y' ) ) . '</a></span>';
        echo $separator;
        echo '<span class="breadcrumb-item current">' . esc_html( get_the_date( 'F' ) ) . '</span>';
    } elseif ( is_day() ) {
        // Day archive
        echo $separator;
        echo '<span class="breadcrumb-item"><a href="' . esc_url( get_year_link( get_the_date( 'Y' ) ) ) . '">' . esc_html( get_the_date( 'Y' ) ) . '</a></span>';
        echo $separator;
        echo '<span class="breadcrumb-item"><a href="' . esc_url( get_month_link( get_the_date( 'Y' ), get_the_date( 'm' ) ) ) . '">' . esc_html( get_the_date( 'F' ) ) . '</a></span>';
        echo $separator;
        echo '<span class="breadcrumb-item current">' . esc_html( get_the_date( 'd' ) ) . '</span>';
    } elseif ( is_single() && ! is_attachment() ) {
        // Single post
        if ( get_post_type() !== 'post' ) {
            // Custom post type
            $post_type = get_post_type_object( get_post_type() );
            echo $separator;
            echo '<span class="breadcrumb-item"><a href="' . esc_url( get_post_type_archive_link( get_post_type() ) ) . '">' . esc_html( $post_type->labels->name ) . '</a></span>';
            echo $separator;
            echo '<span class="breadcrumb-item current">' . esc_html( get_the_title() ) . '</span>';
        } else {
            // Standard post
            $categories = get_the_category();
            if ( $categories ) {
                $category = $categories[0];
                echo $separator;
                echo '<span class="breadcrumb-item"><a href="' . esc_url( get_category_link( $category->term_id ) ) . '">' . esc_html( $category->name ) . '</a></span>';
                echo $separator;
                echo '<span class="breadcrumb-item current">' . esc_html( get_the_title() ) . '</span>';
            } else {
                echo $separator;
                echo '<span class="breadcrumb-item current">' . esc_html( get_the_title() ) . '</span>';
            }
        }
    } elseif ( is_page() && ! $post->post_parent ) {
        // Standard page
        echo $separator;
        echo '<span class="breadcrumb-item current">' . esc_html( get_the_title() ) . '</span>';
    } elseif ( is_page() && $post->post_parent ) {
        // Child page
        $parent_id   = $post->post_parent;
        $breadcrumbs = array();
        
        while ( $parent_id ) {
            $page          = get_post( $parent_id );
            $breadcrumbs[] = '<span class="breadcrumb-item"><a href="' . esc_url( get_permalink( $page->ID ) ) . '">' . esc_html( get_the_title( $page->ID ) ) . '</a></span>';
            $parent_id     = $page->post_parent;
        }
        
        // Display parent pages
        echo $separator;
        echo implode( $separator, array_reverse( $breadcrumbs ) );
        echo $separator;
        echo '<span class="breadcrumb-item current">' . esc_html( get_the_title() ) . '</span>';
    } elseif ( is_search() ) {
        // Search results
        echo $separator;
        echo '<span class="breadcrumb-item current">' . esc_html__( 'Search results for: ', 'aqualuxe' ) . '"' . esc_html( get_search_query() ) . '"</span>';
    } elseif ( is_404() ) {
        // 404 page
        echo $separator;
        echo '<span class="breadcrumb-item current">' . esc_html__( 'Page not found', 'aqualuxe' ) . '</span>';
    } elseif ( is_post_type_archive() ) {
        // Custom post type archive
        echo $separator;
        echo '<span class="breadcrumb-item current">' . esc_html( post_type_archive_title( '', false ) ) . '</span>';
    } elseif ( is_tax() ) {
        // Custom taxonomy
        $current_term = get_queried_object();
        $taxonomy     = get_taxonomy( $current_term->taxonomy );
        $post_type    = isset( $taxonomy->object_type[0] ) ? $taxonomy->object_type[0] : 'post';
        
        echo $separator;
        
        // Add post type archive link if available
        if ( $post_type !== 'post' && get_post_type_archive_link( $post_type ) ) {
            $post_type_obj = get_post_type_object( $post_type );
            echo '<span class="breadcrumb-item"><a href="' . esc_url( get_post_type_archive_link( $post_type ) ) . '">' . esc_html( $post_type_obj->labels->name ) . '</a></span>';
            echo $separator;
        }
        
        // Check if the term has a parent
        if ( $current_term->parent ) {
            $parent_terms = array();
            $parent_id    = $current_term->parent;
            
            while ( $parent_id ) {
                $parent_term    = get_term( $parent_id, $current_term->taxonomy );
                $parent_terms[] = '<span class="breadcrumb-item"><a href="' . esc_url( get_term_link( $parent_term->term_id, $current_term->taxonomy ) ) . '">' . esc_html( $parent_term->name ) . '</a></span>';
                $parent_id      = $parent_term->parent;
            }
            
            // Display parent terms
            echo implode( $separator, array_reverse( $parent_terms ) );
            echo $separator;
        }
        
        echo '<span class="breadcrumb-item current">' . esc_html( $current_term->name ) . '</span>';
    }
    
    echo '</nav>';
}