<?php
/**
 * The template for displaying auction archive
 *
 * @package AquaLuxe
 */

get_header();
?>

<main id="primary" class="site-main">
    <div class="container mx-auto px-4 py-12">
        <header class="page-header mb-8">
            <h1 class="page-title text-3xl md:text-4xl font-bold text-primary-900 dark:text-primary-100">
                <?php esc_html_e( 'Auctions', 'aqualuxe' ); ?>
            </h1>
            
            <?php if ( is_tax( 'auction_category' ) ) : ?>
                <div class="archive-description mt-4 text-lg text-gray-600 dark:text-gray-300">
                    <?php the_archive_description(); ?>
                </div>
            <?php else : ?>
                <div class="archive-description mt-4 text-lg text-gray-600 dark:text-gray-300">
                    <p><?php esc_html_e( 'Browse our collection of rare and exotic fish available for auction.', 'aqualuxe' ); ?></p>
                </div>
            <?php endif; ?>
        </header>
        
        <div class="auction-filters mb-8">
            <div class="flex flex-wrap gap-4 items-center justify-between">
                <div class="auction-categories">
                    <?php
                    $categories = get_terms( array(
                        'taxonomy'   => 'auction_category',
                        'hide_empty' => true,
                    ) );
                    
                    if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) :
                    ?>
                        <div class="flex flex-wrap gap-2">
                            <a href="<?php echo esc_url( get_post_type_archive_link( 'aqualuxe_auction' ) ); ?>" class="inline-block px-3 py-1 text-sm rounded-full <?php echo is_post_type_archive( 'aqualuxe_auction' ) && ! is_tax() ? 'bg-primary-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600'; ?>">
                                <?php esc_html_e( 'All', 'aqualuxe' ); ?>
                            </a>
                            
                            <?php foreach ( $categories as $category ) : ?>
                                <a href="<?php echo esc_url( get_term_link( $category ) ); ?>" class="inline-block px-3 py-1 text-sm rounded-full <?php echo is_tax( 'auction_category', $category->term_id ) ? 'bg-primary-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600'; ?>">
                                    <?php echo esc_html( $category->name ); ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="auction-status-filter">
                    <?php
                    $current_status = isset( $_GET['auction_status'] ) ? sanitize_text_field( $_GET['auction_status'] ) : '';
                    $statuses = get_terms( array(
                        'taxonomy'   => 'auction_status',
                        'hide_empty' => true,
                    ) );
                    
                    if ( ! empty( $statuses ) && ! is_wp_error( $statuses ) ) :
                    ?>
                        <form action="<?php echo esc_url( get_post_type_archive_link( 'aqualuxe_auction' ) ); ?>" method="get" class="flex items-center">
                            <label for="auction_status" class="mr-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                <?php esc_html_e( 'Status:', 'aqualuxe' ); ?>
                            </label>
                            <select name="auction_status" id="auction_status" class="py-1 px-2 border border-gray-300 rounded-md text-sm bg-white dark:bg-gray-700 dark:border-gray-600 dark:text-white" onchange="this.form.submit()">
                                <option value=""><?php esc_html_e( 'All Statuses', 'aqualuxe' ); ?></option>
                                <?php foreach ( $statuses as $status ) : ?>
                                    <option value="<?php echo esc_attr( $status->slug ); ?>" <?php selected( $current_status, $status->slug ); ?>>
                                        <?php echo esc_html( $status->name ); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <?php if ( have_posts() ) : ?>
            <div class="auction-grid">
                <?php while ( have_posts() ) : the_post(); ?>
                    <?php
                    // Get auction data
                    $auction_id = get_the_ID();
                    $start_date = get_post_meta( $auction_id, '_auction_start_date', true );
                    $end_date = get_post_meta( $auction_id, '_auction_end_date', true );
                    $start_price = get_post_meta( $auction_id, '_auction_start_price', true );
                    $current_bid = get_post_meta( $auction_id, '_auction_current_bid', true );
                    $featured = get_post_meta( $auction_id, '_auction_featured', true );
                    
                    // Get auction status
                    $status_terms = get_the_terms( $auction_id, 'auction_status' );
                    $status = ! empty( $status_terms ) ? $status_terms[0]->slug : '';
                    $status_name = ! empty( $status_terms ) ? $status_terms[0]->name : '';
                    
                    // Format dates
                    $formatted_end_date = $end_date ? date_i18n( get_option( 'date_format' ), strtotime( $end_date ) ) : '';
                    
                    // Check if auction has ended
                    $has_ended = $end_date && strtotime( $end_date ) <= current_time( 'timestamp' );
                    
                    // Format prices
                    $formatted_start_price = '$' . number_format( $start_price, 2 );
                    $formatted_current_bid = $current_bid ? '$' . number_format( $current_bid, 2 ) : $formatted_start_price;
                    ?>
                    
                    <article id="post-<?php the_ID(); ?>" <?php post_class( 'auction-card' ); ?>>
                        <div class="auction-card-image">
                            <?php if ( has_post_thumbnail() ) : ?>
                                <?php the_post_thumbnail( 'medium', array( 'class' => 'w-full h-full object-cover' ) ); ?>
                            <?php else : ?>
                                <div class="w-full h-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                                    <span class="text-gray-500 dark:text-gray-400">
                                        <?php esc_html_e( 'No Image', 'aqualuxe' ); ?>
                                    </span>
                                </div>
                            <?php endif; ?>
                            
                            <span class="auction-status <?php echo esc_attr( $status ); ?> absolute top-2 left-2">
                                <?php echo esc_html( $status_name ); ?>
                            </span>
                            
                            <?php if ( $featured ) : ?>
                                <span class="auction-featured-badge">
                                    <?php esc_html_e( 'Featured', 'aqualuxe' ); ?>
                                </span>
                            <?php endif; ?>
                        </div>
                        
                        <div class="auction-card-content">
                            <h2 class="auction-card-title">
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_title(); ?>
                                </a>
                            </h2>
                            
                            <div class="auction-card-meta">
                                <?php if ( 'active' === $status && ! $has_ended && $end_date ) : ?>
                                    <div class="auction-card-meta-item">
                                        <strong><?php esc_html_e( 'Ends:', 'aqualuxe' ); ?></strong>
                                        <?php echo esc_html( $formatted_end_date ); ?>
                                    </div>
                                <?php elseif ( 'upcoming' === $status && $start_date ) : ?>
                                    <div class="auction-card-meta-item">
                                        <strong><?php esc_html_e( 'Starts:', 'aqualuxe' ); ?></strong>
                                        <?php echo esc_html( date_i18n( get_option( 'date_format' ), strtotime( $start_date ) ) ); ?>
                                    </div>
                                <?php endif; ?>
                                
                                <?php
                                // Get bids count
                                $bids = get_post_meta( $auction_id, '_auction_bids', true );
                                $bids_count = is_array( $bids ) ? count( $bids ) : 0;
                                ?>
                                <div class="auction-card-meta-item">
                                    <strong><?php esc_html_e( 'Bids:', 'aqualuxe' ); ?></strong>
                                    <?php echo esc_html( $bids_count ); ?>
                                </div>
                            </div>
                            
                            <div class="auction-card-footer">
                                <div class="auction-card-price">
                                    <?php echo esc_html( $formatted_current_bid ); ?>
                                </div>
                                
                                <a href="<?php the_permalink(); ?>" class="auction-card-button">
                                    <?php
                                    if ( 'active' === $status && ! $has_ended ) {
                                        esc_html_e( 'Bid Now', 'aqualuxe' );
                                    } else {
                                        esc_html_e( 'View Details', 'aqualuxe' );
                                    }
                                    ?>
                                </a>
                            </div>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>
            
            <?php
            // Pagination
            the_posts_pagination( array(
                'prev_text' => '<span class="screen-reader-text">' . __( 'Previous', 'aqualuxe' ) . '</span><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>',
                'next_text' => '<span class="screen-reader-text">' . __( 'Next', 'aqualuxe' ) . '</span><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>',
            ) );
            ?>
        <?php else : ?>
            <div class="no-results bg-gray-50 dark:bg-gray-800 p-8 rounded-lg text-center">
                <h2 class="text-xl font-semibold mb-2">
                    <?php esc_html_e( 'No Auctions Found', 'aqualuxe' ); ?>
                </h2>
                <p class="text-gray-600 dark:text-gray-300">
                    <?php esc_html_e( 'There are currently no auctions available. Please check back later.', 'aqualuxe' ); ?>
                </p>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php
get_footer();