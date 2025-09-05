<?php
/** Wishlist (simple, cookie-based placeholder) */
namespace AquaLuxe\Modules\Wishlist;
if ( ! defined( 'ABSPATH' ) ) { exit; }

\add_shortcode( 'alx_wishlist', function () {
	$ids = isset( $_COOKIE['alx_wishlist'] ) ? array_filter( array_map( 'absint', explode( ',', $_COOKIE['alx_wishlist'] ) ) ) : [];
	if ( empty( $ids ) ) return '<p>' . esc_html__( 'Your wishlist is empty.', 'aqualuxe' ) . '</p>';
	$q = new \WP_Query( [ 'post_type' => 'product', 'post__in' => $ids, 'posts_per_page' => -1 ] );
	ob_start(); echo '<ul class="space-y-2">';
	while ( $q->have_posts() ) { $q->the_post(); echo '<li><a href="' . esc_url( get_permalink() ) . '">' . esc_html( get_the_title() ) . '</a></li>'; }
	\wp_reset_postdata(); echo '</ul>'; return (string) ob_get_clean();
} );
