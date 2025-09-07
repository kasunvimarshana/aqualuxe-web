<?php
/**
 * Template part for displaying auction content in archive pages.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

// Auction custom fields (placeholders, use your actual meta keys)
$end_date_str = get_post_meta( get_the_ID(), '_auction_end_date', true );
$end_date = $end_date_str ? new DateTime( $end_date_str ) : null;
$current_date = new DateTime();
$is_active = $end_date && $end_date > $current_date;
$time_left = $is_active ? $current_date->diff( $end_date ) : null;

$start_price = get_post_meta( get_the_ID(), '_auction_start_price', true );
// In a real auction plugin, you'd get the current highest bid.
$current_bid = get_post_meta( get_the_ID(), '_auction_current_bid', true ) ?: $start_price;

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'bg-white rounded-lg shadow-lg overflow-hidden transition-transform duration-300 hover:-translate-y-2 flex flex-col' ); ?>>
    <header class="relative">
        <a href="<?php the_permalink(); ?>" class="block">
            <?php if ( has_post_thumbnail() ) : ?>
                <?php the_post_thumbnail( 'medium_large', array( 'class' => 'w-full h-64 object-cover' ) ); ?>
            <?php else : ?>
                <div class="w-full h-64 bg-gray-200 flex items-center justify-center">
                    <span class="text-gray-500"><?php esc_html_e( 'No Image', 'aqualuxe' ); ?></span>
                </div>
            <?php endif; ?>
        </a>
        <?php if ( $is_active ) : ?>
            <div class="absolute top-4 left-4 bg-red-500 text-white text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider">
                <?php esc_html_e( 'Live', 'aqualuxe' ); ?>
            </div>
        <?php else : ?>
             <div class="absolute top-4 left-4 bg-gray-700 text-white text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider">
                <?php esc_html_e( 'Ended', 'aqualuxe' ); ?>
            </div>
        <?php endif; ?>
    </header>

    <div class="p-6 flex-grow flex flex-col">
        <h2 class="entry-title text-2xl font-bold mb-2">
            <a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
        </h2>

        <div class="entry-summary text-gray-600 mb-4 flex-grow">
            <?php the_excerpt(); ?>
        </div>

        <div class="auction-meta mt-auto">
            <div class="flex justify-between items-center mb-4">
                <div class="current-bid">
                    <span class="block text-sm text-gray-500"><?php esc_html_e( 'Current Bid', 'aqualuxe' ); ?></span>
                    <span class="block text-2xl font-bold text-primary"><?php echo wc_price( $current_bid ); ?></span>
                </div>
                <?php if ( $is_active && $time_left ) : ?>
                    <div class="time-left text-right">
                        <span class="block text-sm text-gray-500"><?php esc_html_e( 'Time Left', 'aqualuxe' ); ?></span>
                        <span class="block text-lg font-semibold text-red-600">
                            <?php echo esc_html( $time_left->format( '%ad %hh %im' ) ); ?>
                        </span>
                    </div>
                <?php endif; ?>
            </div>

            <a href="<?php the_permalink(); ?>" class="w-full text-center block bg-secondary hover:bg-secondary-dark text-white font-bold py-3 px-4 rounded-lg transition duration-300">
                <?php echo $is_active ? esc_html__( 'Place Bid', 'aqualuxe' ) : esc_html__( 'View Details', 'aqualuxe' ); ?>
            </a>
        </div>
    </div>
</article>
