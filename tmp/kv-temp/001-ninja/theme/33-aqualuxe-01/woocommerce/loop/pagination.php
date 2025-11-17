<?php
/**
 * Pagination - Show numbered pagination for catalog pages
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/pagination.php.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package AquaLuxe
 * @version 3.3.1
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Get pagination settings from theme customizer
$pagination_style = get_theme_mod( 'aqualuxe_product_pagination_style', 'numbered' );
$show_prev_next = get_theme_mod( 'aqualuxe_product_pagination_prev_next', true );
$show_first_last = get_theme_mod( 'aqualuxe_product_pagination_first_last', true );
$infinite_scroll = get_theme_mod( 'aqualuxe_product_pagination_infinite', false );
$load_more = get_theme_mod( 'aqualuxe_product_pagination_load_more', false );

$total   = isset( $total ) ? $total : wc_get_loop_prop( 'total_pages' );
$current = isset( $current ) ? $current : wc_get_loop_prop( 'current_page' );
$base    = isset( $base ) ? $base : esc_url_raw( str_replace( 999999999, '%#%', remove_query_arg( 'add-to-cart', get_pagenum_link( 999999999, false ) ) ) );
$format  = isset( $format ) ? $format : '';

if ( $total <= 1 ) {
    return;
}

// If infinite scroll or load more is enabled, add the necessary data attributes
$container_classes = array( 'aqualuxe-pagination', 'aqualuxe-pagination-style-' . $pagination_style );
$container_attrs = '';

if ( $infinite_scroll ) {
    $container_classes[] = 'aqualuxe-pagination-infinite';
    $container_attrs .= ' data-infinite-scroll="true"';
    $container_attrs .= ' data-infinite-scroll-container=".products"';
    $container_attrs .= ' data-infinite-scroll-next=".next"';
    $container_attrs .= ' data-infinite-scroll-status=".aqualuxe-pagination-status"';
}

if ( $load_more ) {
    $container_classes[] = 'aqualuxe-pagination-load-more';
}

?>
<nav class="<?php echo esc_attr( implode( ' ', $container_classes ) ); ?>"<?php echo $container_attrs; ?>>
    <?php if ( $infinite_scroll ) : ?>
        <div class="aqualuxe-pagination-status">
            <div class="aqualuxe-pagination-loading">
                <span class="aqualuxe-loading-spinner"></span>
                <span class="aqualuxe-loading-text"><?php esc_html_e( 'Loading...', 'aqualuxe' ); ?></span>
            </div>
            <div class="aqualuxe-pagination-last"><?php esc_html_e( 'You\'ve reached the end of the products.', 'aqualuxe' ); ?></div>
            <div class="aqualuxe-pagination-error"><?php esc_html_e( 'Error loading products. Please try again.', 'aqualuxe' ); ?></div>
        </div>
    <?php endif; ?>
    
    <?php if ( $load_more && $current < $total ) : ?>
        <div class="aqualuxe-load-more-wrapper">
            <a href="<?php echo esc_url( str_replace( '%#%', $current + 1, $base ) ); ?>" class="aqualuxe-load-more-button">
                <?php esc_html_e( 'Load More', 'aqualuxe' ); ?>
            </a>
            <span class="aqualuxe-loading-spinner"></span>
        </div>
    <?php endif; ?>
    
    <?php if ( $pagination_style === 'numbered' && ! $infinite_scroll && ! $load_more ) : ?>
        <?php
        $paginate_links = paginate_links(
            apply_filters(
                'woocommerce_pagination_args',
                array( // WPCS: XSS ok.
                    'base'      => $base,
                    'format'    => $format,
                    'add_args'  => false,
                    'current'   => max( 1, $current ),
                    'total'     => $total,
                    'prev_text' => $show_prev_next ? '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>' : '',
                    'next_text' => $show_prev_next ? '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>' : '',
                    'type'      => 'list',
                    'end_size'  => 3,
                    'mid_size'  => 3,
                )
            )
        );
        
        // Add first/last page links if enabled
        if ( $show_first_last ) {
            $first_page_link = '<li class="aqualuxe-pagination-first"><a class="page-numbers" href="' . esc_url( str_replace( '%#%', 1, $base ) ) . '"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="11 17 6 12 11 7"></polyline><polyline points="18 17 13 12 18 7"></polyline></svg></a></li>';
            $last_page_link = '<li class="aqualuxe-pagination-last"><a class="page-numbers" href="' . esc_url( str_replace( '%#%', $total, $base ) ) . '"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="13 17 18 12 13 7"></polyline><polyline points="6 17 11 12 6 7"></polyline></svg></a></li>';
            
            $paginate_links = preg_replace( '/<ul class="page-numbers">/', '<ul class="page-numbers">' . $first_page_link, $paginate_links );
            $paginate_links = preg_replace( '/<\/ul>/', $last_page_link . '</ul>', $paginate_links );
        }
        
        echo wp_kses_post( $paginate_links );
        ?>
    <?php endif; ?>
</nav>