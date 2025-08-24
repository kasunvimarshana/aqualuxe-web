<?php
/**
 * Wishlist page template
 * Place in page-wishlist.php or use as a shortcode output
 */
if ( ! is_user_logged_in() ) {
    echo '<p>' . __( 'You must be logged in to view your wishlist.', 'aqualuxe' ) . '</p>';
    return;
}
$user_id = get_current_user_id();
$wishlist = (array) get_user_meta( $user_id, '_aqualuxe_wishlist', true );
if ( empty( $wishlist ) ) {
    echo '<p>' . __( 'Your wishlist is empty.', 'aqualuxe' ) . '</p>';
    return;
}
$args = array(
    'post_type' => 'product',
    'post__in' => $wishlist,
    'posts_per_page' => -1,
    'orderby' => 'post__in',
);
$products = new WP_Query( $args );
if ( $products->have_posts() ) :
    echo '<ul class="aqualuxe-wishlist-list">';
    while ( $products->have_posts() ) : $products->the_post();
        global $product;
        echo '<li class="aqualuxe-wishlist-item">';
        echo '<a href="' . get_permalink() . '">' . get_the_post_thumbnail( get_the_ID(), 'thumbnail' ) . ' ' . get_the_title() . '</a>';
        echo do_shortcode('[aqualuxe_wishlist_button product_id="' . get_the_ID() . '"]');
        echo '</li>';
    endwhile;
    echo '</ul>';
    wp_reset_postdata();
else:
    echo '<p>' . __( 'No products found in your wishlist.', 'aqualuxe' ) . '</p>';
endif;
