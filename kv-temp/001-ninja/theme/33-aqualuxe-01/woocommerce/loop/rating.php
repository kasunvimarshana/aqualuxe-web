<?php
/**
 * Loop Rating
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/rating.php.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package AquaLuxe
 * @version 3.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

global $product;

// Get rating display settings from theme customizer
$rating_style = get_theme_mod( 'aqualuxe_product_rating_style', 'stars' );
$show_count = get_theme_mod( 'aqualuxe_product_rating_count', true );
$show_empty = get_theme_mod( 'aqualuxe_product_rating_empty', false );

if ( ! wc_review_ratings_enabled() ) {
    return;
}

$rating_count = $product->get_rating_count();
$review_count = $product->get_review_count();
$average = $product->get_average_rating();

// Don't show empty ratings unless specifically enabled
if ( $rating_count == 0 && ! $show_empty ) {
    return;
}
?>

<div class="aqualuxe-rating-wrapper aqualuxe-rating-style-<?php echo esc_attr( $rating_style ); ?>">
    <?php if ( $rating_style === 'stars' || $rating_style === 'stars-text' ) : ?>
        <div class="star-rating" role="img" aria-label="<?php echo esc_attr( sprintf( __( 'Rated %s out of 5', 'aqualuxe' ), $average ) ); ?>">
            <span style="width:<?php echo esc_attr( ( $average / 5 ) * 100 ); ?>%">
                <?php echo esc_html( sprintf( __( 'Rated %s out of 5', 'aqualuxe' ), $average ) ); ?>
            </span>
        </div>
    <?php endif; ?>
    
    <?php if ( $rating_style === 'text' || $rating_style === 'stars-text' ) : ?>
        <div class="aqualuxe-rating-text">
            <?php echo esc_html( number_format( $average, 1 ) ); ?>/5
        </div>
    <?php endif; ?>
    
    <?php if ( $show_count && $rating_count > 0 ) : ?>
        <div class="aqualuxe-rating-count">
            <?php echo esc_html( sprintf( _n( '(%s review)', '(%s reviews)', $review_count, 'aqualuxe' ), number_format_i18n( $review_count ) ) ); ?>
        </div>
    <?php endif; ?>
</div>