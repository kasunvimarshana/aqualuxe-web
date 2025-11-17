<?php
/**
 * The template for displaying single auction
 *
 * @package AquaLuxe
 */

get_header();
?>

<main id="primary" class="site-main">
    <div class="container mx-auto px-4 py-12">
        <?php while ( have_posts() ) : the_post(); ?>
            <?php
            // Get auction data
            $auction_id = get_the_ID();
            $start_date = get_post_meta( $auction_id, '_auction_start_date', true );
            $end_date = get_post_meta( $auction_id, '_auction_end_date', true );
            $start_price = get_post_meta( $auction_id, '_auction_start_price', true );
            $reserve_price = get_post_meta( $auction_id, '_auction_reserve_price', true );
            $min_bid_increment = get_post_meta( $auction_id, '_auction_min_bid_increment', true );
            $current_bid = get_post_meta( $auction_id, '_auction_current_bid', true );
            $featured = get_post_meta( $auction_id, '_auction_featured', true );
            
            // Get auction status
            $status_terms = get_the_terms( $auction_id, 'auction_status' );
            $status = ! empty( $status_terms ) ? $status_terms[0]->slug : '';
            $status_name = ! empty( $status_terms ) ? $status_terms[0]->name : '';
            
            // Format dates
            $formatted_start_date = $start_date ? date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), strtotime( $start_date ) ) : '';
            $formatted_end_date = $end_date ? date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), strtotime( $end_date ) ) : '';
            
            // Check if auction has started
            $has_started = $start_date && strtotime( $start_date ) <= current_time( 'timestamp' );
            
            // Check if auction has ended
            $has_ended = $end_date && strtotime( $end_date ) <= current_time( 'timestamp' );
            
            // Get winner information
            $winner_id = get_post_meta( $auction_id, '_auction_winner_id', true );
            $winner_name = get_post_meta( $auction_id, '_auction_winner_name', true );
            $winning_bid = get_post_meta( $auction_id, '_auction_winning_bid', true );
            
            // Calculate minimum bid
            if ( $current_bid ) {
                $min_bid = $current_bid + $min_bid_increment;
            } else {
                $min_bid = $start_price;
            }
            
            // Format prices
            $formatted_start_price = '$' . number_format( $start_price, 2 );
            $formatted_current_bid = $current_bid ? '$' . number_format( $current_bid, 2 ) : $formatted_start_price;
            $formatted_min_bid = '$' . number_format( $min_bid, 2 );
            $formatted_reserve_price = $reserve_price ? '$' . number_format( $reserve_price, 2 ) : '';
            ?>
            
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <div class="auction-container" data-auction-id="<?php echo esc_attr( $auction_id ); ?>">
                    <header class="entry-header mb-8">
                        <div class="flex flex-wrap items-center justify-between gap-4 mb-4">
                            <h1 class="entry-title text-3xl md:text-4xl font-bold text-primary-900 dark:text-primary-100">
                                <?php the_title(); ?>
                            </h1>
                            
                            <span class="auction-status <?php echo esc_attr( $status ); ?>">
                                <?php echo esc_html( $status_name ); ?>
                            </span>
                        </div>
                        
                        <?php if ( $featured ) : ?>
                            <div class="inline-block bg-primary-600 text-white text-sm font-semibold px-3 py-1 rounded-md mb-4">
                                <?php esc_html_e( 'Featured Auction', 'aqualuxe' ); ?>
                            </div>
                        <?php endif; ?>
                    </header>
                    
                    <div class="entry-content">
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                            <div class="lg:col-span-2">
                                <?php if ( has_post_thumbnail() ) : ?>
                                    <div class="auction-image mb-6">
                                        <?php the_post_thumbnail( 'large', array( 'class' => 'w-full h-auto rounded-lg' ) ); ?>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="auction-description prose dark:prose-invert max-w-none">
                                    <?php the_content(); ?>
                                </div>
                            </div>
                            
                            <div class="auction-sidebar">
                                <?php if ( 'active' === $status && ! $has_ended ) : ?>
                                    <div class="auction-countdown-container mb-6">
                                        <h3 class="text-lg font-semibold mb-2">
                                            <?php esc_html_e( 'Time Remaining', 'aqualuxe' ); ?>
                                        </h3>
                                        <div class="auction-countdown" data-end-date="<?php echo esc_attr( $end_date ); ?>"></div>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="auction-details mb-6">
                                    <div class="auction-detail-item">
                                        <span class="auction-detail-label"><?php esc_html_e( 'Start Date', 'aqualuxe' ); ?></span>
                                        <span class="auction-detail-value"><?php echo esc_html( $formatted_start_date ); ?></span>
                                    </div>
                                    
                                    <div class="auction-detail-item">
                                        <span class="auction-detail-label"><?php esc_html_e( 'End Date', 'aqualuxe' ); ?></span>
                                        <span class="auction-detail-value"><?php echo esc_html( $formatted_end_date ); ?></span>
                                    </div>
                                    
                                    <div class="auction-detail-item">
                                        <span class="auction-detail-label"><?php esc_html_e( 'Starting Price', 'aqualuxe' ); ?></span>
                                        <span class="auction-detail-value"><?php echo esc_html( $formatted_start_price ); ?></span>
                                    </div>
                                    
                                    <?php if ( $reserve_price ) : ?>
                                        <div class="auction-detail-item">
                                            <span class="auction-detail-label"><?php esc_html_e( 'Reserve Price', 'aqualuxe' ); ?></span>
                                            <span class="auction-detail-value"><?php echo esc_html( $formatted_reserve_price ); ?></span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="auction-current-bid-container mb-6">
                                    <div class="auction-current-bid-label">
                                        <?php esc_html_e( 'Current Bid', 'aqualuxe' ); ?>
                                    </div>
                                    <div class="auction-current-bid">
                                        <?php echo esc_html( $formatted_current_bid ); ?>
                                    </div>
                                </div>
                                
                                <?php if ( 'active' === $status && ! $has_ended ) : ?>
                                    <div class="auction-bid-form-container">
                                        <h3 class="auction-bid-form-title">
                                            <?php esc_html_e( 'Place Your Bid', 'aqualuxe' ); ?>
                                        </h3>
                                        
                                        <div class="auction-bid-result hidden"></div>
                                        
                                        <?php if ( is_user_logged_in() ) : ?>
                                            <form class="auction-bid-form">
                                                <input type="hidden" name="auction_id" value="<?php echo esc_attr( $auction_id ); ?>">
                                                
                                                <div class="auction-bid-form-group">
                                                    <label for="bid_amount" class="auction-bid-form-label">
                                                        <?php esc_html_e( 'Bid Amount ($)', 'aqualuxe' ); ?>
                                                    </label>
                                                    <div class="auction-bid-amount-wrapper">
                                                        <input type="number" id="bid_amount" name="bid_amount" class="auction-bid-amount auction-bid-form-input" step="0.01" min="<?php echo esc_attr( $min_bid ); ?>" data-min-bid="<?php echo esc_attr( $min_bid ); ?>" placeholder="<?php echo esc_attr( $formatted_min_bid ); ?>" required>
                                                    </div>
                                                    <p class="text-sm text-gray-500 mt-1">
                                                        <?php printf( esc_html__( 'Minimum bid: %s', 'aqualuxe' ), $formatted_min_bid ); ?>
                                                    </p>
                                                </div>
                                                
                                                <button type="submit" class="auction-bid-button" disabled>
                                                    <?php esc_html_e( 'Place Bid', 'aqualuxe' ); ?>
                                                </button>
                                            </form>
                                        <?php else : ?>
                                            <div class="auction-login-form">
                                                <p class="mb-4">
                                                    <?php esc_html_e( 'Please log in to place a bid.', 'aqualuxe' ); ?>
                                                </p>
                                                
                                                <a href="<?php echo esc_url( wp_login_url( get_permalink() ) ); ?>" class="auction-bid-button inline-block">
                                                    <?php esc_html_e( 'Log In', 'aqualuxe' ); ?>
                                                </a>
                                                
                                                <span class="auction-toggle-form">
                                                    <?php esc_html_e( 'Bid as guest', 'aqualuxe' ); ?>
                                                </span>
                                            </div>
                                            
                                            <form class="auction-bid-form auction-guest-form hidden">
                                                <input type="hidden" name="auction_id" value="<?php echo esc_attr( $auction_id ); ?>">
                                                
                                                <div class="auction-bid-form-group">
                                                    <label for="name" class="auction-bid-form-label">
                                                        <?php esc_html_e( 'Your Name', 'aqualuxe' ); ?>
                                                    </label>
                                                    <input type="text" id="name" name="name" class="auction-bid-form-input" required>
                                                </div>
                                                
                                                <div class="auction-bid-form-group">
                                                    <label for="email" class="auction-bid-form-label">
                                                        <?php esc_html_e( 'Your Email', 'aqualuxe' ); ?>
                                                    </label>
                                                    <input type="email" id="email" name="email" class="auction-bid-form-input" required>
                                                </div>
                                                
                                                <div class="auction-bid-form-group">
                                                    <label for="bid_amount" class="auction-bid-form-label">
                                                        <?php esc_html_e( 'Bid Amount ($)', 'aqualuxe' ); ?>
                                                    </label>
                                                    <div class="auction-bid-amount-wrapper">
                                                        <input type="number" id="bid_amount" name="bid_amount" class="auction-bid-amount auction-bid-form-input" step="0.01" min="<?php echo esc_attr( $min_bid ); ?>" data-min-bid="<?php echo esc_attr( $min_bid ); ?>" placeholder="<?php echo esc_attr( $formatted_min_bid ); ?>" required>
                                                    </div>
                                                    <p class="text-sm text-gray-500 mt-1">
                                                        <?php printf( esc_html__( 'Minimum bid: %s', 'aqualuxe' ), $formatted_min_bid ); ?>
                                                    </p>
                                                </div>
                                                
                                                <button type="submit" class="auction-bid-button" disabled>
                                                    <?php esc_html_e( 'Place Bid', 'aqualuxe' ); ?>
                                                </button>
                                                
                                                <span class="auction-toggle-form">
                                                    <?php esc_html_e( 'Log in to bid', 'aqualuxe' ); ?>
                                                </span>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                <?php elseif ( 'ended' === $status && ( $winner_id || $winner_name ) ) : ?>
                                    <div class="auction-winner-container bg-green-50 dark:bg-green-900/30 p-4 rounded-lg">
                                        <h3 class="text-lg font-semibold mb-2 text-green-800 dark:text-green-200">
                                            <?php esc_html_e( 'Auction Ended', 'aqualuxe' ); ?>
                                        </h3>
                                        <p class="mb-2">
                                            <?php esc_html_e( 'This auction has ended and a winner has been determined.', 'aqualuxe' ); ?>
                                        </p>
                                        <p class="font-medium">
                                            <?php esc_html_e( 'Winning Bid:', 'aqualuxe' ); ?> 
                                            <span class="text-green-700 dark:text-green-300 font-bold">
                                                <?php echo esc_html( '$' . number_format( $winning_bid, 2 ) ); ?>
                                            </span>
                                        </p>
                                    </div>
                                <?php elseif ( 'ended' === $status ) : ?>
                                    <div class="auction-ended-container bg-blue-50 dark:bg-blue-900/30 p-4 rounded-lg">
                                        <h3 class="text-lg font-semibold mb-2 text-blue-800 dark:text-blue-200">
                                            <?php esc_html_e( 'Auction Ended', 'aqualuxe' ); ?>
                                        </h3>
                                        <p>
                                            <?php esc_html_e( 'This auction has ended.', 'aqualuxe' ); ?>
                                        </p>
                                    </div>
                                <?php elseif ( 'upcoming' === $status ) : ?>
                                    <div class="auction-upcoming-container bg-yellow-50 dark:bg-yellow-900/30 p-4 rounded-lg">
                                        <h3 class="text-lg font-semibold mb-2 text-yellow-800 dark:text-yellow-200">
                                            <?php esc_html_e( 'Auction Not Started', 'aqualuxe' ); ?>
                                        </h3>
                                        <p>
                                            <?php esc_html_e( 'This auction has not started yet. Please check back later.', 'aqualuxe' ); ?>
                                        </p>
                                    </div>
                                <?php elseif ( 'cancelled' === $status ) : ?>
                                    <div class="auction-cancelled-container bg-red-50 dark:bg-red-900/30 p-4 rounded-lg">
                                        <h3 class="text-lg font-semibold mb-2 text-red-800 dark:text-red-200">
                                            <?php esc_html_e( 'Auction Cancelled', 'aqualuxe' ); ?>
                                        </h3>
                                        <p>
                                            <?php esc_html_e( 'This auction has been cancelled.', 'aqualuxe' ); ?>
                                        </p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <?php
                        // Get bids
                        $bids = get_post_meta( $auction_id, '_auction_bids', true );
                        
                        if ( is_array( $bids ) && ! empty( $bids ) ) :
                            // Sort bids by date (newest first)
                            usort( $bids, function( $a, $b ) {
                                return strtotime( $b['date'] ) - strtotime( $a['date'] );
                            } );
                        ?>
                            <div class="auction-bid-history-container mt-12">
                                <h3 class="auction-bid-history-title">
                                    <?php esc_html_e( 'Bid History', 'aqualuxe' ); ?>
                                </h3>
                                
                                <div class="auction-bid-history" data-auction-id="<?php echo esc_attr( $auction_id ); ?>">
                                    <table class="auction-bid-history-table">
                                        <thead>
                                            <tr>
                                                <th><?php esc_html_e( 'Bidder', 'aqualuxe' ); ?></th>
                                                <th><?php esc_html_e( 'Bid Amount', 'aqualuxe' ); ?></th>
                                                <th><?php esc_html_e( 'Date & Time', 'aqualuxe' ); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ( $bids as $bid ) : ?>
                                                <tr>
                                                    <td>
                                                        <?php
                                                        if ( ! empty( $bid['user_id'] ) ) {
                                                            $user = get_user_by( 'id', $bid['user_id'] );
                                                            if ( $user ) {
                                                                echo esc_html( $user->display_name );
                                                            }
                                                        } else {
                                                            echo esc_html( $bid['name'] );
                                                        }
                                                        ?>
                                                    </td>
                                                    <td><?php echo esc_html( '$' . number_format( $bid['amount'], 2 ) ); ?></td>
                                                    <td><?php echo esc_html( date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), strtotime( $bid['date'] ) ) ); ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Loading Overlay -->
                <div class="auction-loading hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                    <div class="loader p-5 bg-white dark:bg-gray-800 rounded-lg shadow-lg">
                        <div class="animate-spin rounded-full h-10 w-10 border-t-2 border-b-2 border-primary-600 mx-auto"></div>
                        <p class="mt-3 text-center text-gray-700 dark:text-gray-300"><?php esc_html_e( 'Processing...', 'aqualuxe' ); ?></p>
                    </div>
                </div>
            </article>
            
            <?php
            // If comments are open or we have at least one comment, load up the comment template.
            if ( comments_open() || get_comments_number() ) :
                comments_template();
            endif;
            ?>
        <?php endwhile; ?>
    </div>
</main>

<?php
get_footer();