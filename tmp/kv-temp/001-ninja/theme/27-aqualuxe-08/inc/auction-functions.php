<?php
/**
 * Auction functionality for AquaLuxe Theme
 *
 * @package AquaLuxe
 */

/**
 * Initialize auction functionality
 */
function aqualuxe_auction_init() {
    // Register auction post type if not already registered
    if ( ! post_type_exists( 'aqualuxe_auction' ) ) {
        // The post type is likely registered elsewhere, but we'll add a safety check
        // This is just a placeholder function to prevent fatal errors
    }
}
add_action( 'init', 'aqualuxe_auction_init' );

/**
 * Get auction status options
 *
 * @return array Array of auction status options
 */
function aqualuxe_get_auction_statuses() {
    return apply_filters( 'aqualuxe_auction_statuses', array(
        'upcoming'   => __( 'Upcoming', 'aqualuxe' ),
        'active'     => __( 'Active', 'aqualuxe' ),
        'ended'      => __( 'Ended', 'aqualuxe' ),
        'cancelled'  => __( 'Cancelled', 'aqualuxe' ),
        'sold'       => __( 'Sold', 'aqualuxe' ),
    ) );
}

/**
 * Get auction status label
 *
 * @param string $status Auction status key
 * @return string Auction status label
 */
function aqualuxe_get_auction_status_label( $status ) {
    $statuses = aqualuxe_get_auction_statuses();
    return isset( $statuses[$status] ) ? $statuses[$status] : __( 'Unknown', 'aqualuxe' );
}

/**
 * Check if an auction is active
 *
 * @param int $auction_id Auction ID
 * @return bool True if active, false if not
 */
function aqualuxe_is_auction_active( $auction_id ) {
    $status = get_post_meta( $auction_id, '_auction_status', true );
    $start_date = get_post_meta( $auction_id, '_auction_start_date', true );
    $end_date = get_post_meta( $auction_id, '_auction_end_date', true );
    
    // If status is explicitly set to active, return true
    if ( $status === 'active' ) {
        return true;
    }
    
    // If status is explicitly set to something other than active, return false
    if ( $status && $status !== 'active' ) {
        return false;
    }
    
    // If no explicit status, check dates
    $now = current_time( 'timestamp' );
    $start_timestamp = strtotime( $start_date );
    $end_timestamp = strtotime( $end_date );
    
    return ( $start_timestamp <= $now && $end_timestamp >= $now );
}

/**
 * Get auction details
 *
 * @param int $auction_id Auction ID
 * @return array|false Auction details or false if not found
 */
function aqualuxe_get_auction( $auction_id ) {
    $auction = get_post( $auction_id );
    
    if ( ! $auction || 'aqualuxe_auction' !== $auction->post_type ) {
        return false;
    }
    
    $start_date = get_post_meta( $auction->ID, '_auction_start_date', true );
    $end_date = get_post_meta( $auction->ID, '_auction_end_date', true );
    $status = get_post_meta( $auction->ID, '_auction_status', true );
    $starting_bid = get_post_meta( $auction->ID, '_auction_starting_bid', true );
    $current_bid = get_post_meta( $auction->ID, '_auction_current_bid', true );
    $reserve_price = get_post_meta( $auction->ID, '_auction_reserve_price', true );
    $bid_increment = get_post_meta( $auction->ID, '_auction_bid_increment', true );
    
    // If no explicit status, determine based on dates
    if ( ! $status ) {
        $now = current_time( 'timestamp' );
        $start_timestamp = strtotime( $start_date );
        $end_timestamp = strtotime( $end_date );
        
        if ( $start_timestamp > $now ) {
            $status = 'upcoming';
        } elseif ( $end_timestamp < $now ) {
            $status = 'ended';
        } else {
            $status = 'active';
        }
    }
    
    return array(
        'id'            => $auction->ID,
        'title'         => $auction->post_title,
        'description'   => $auction->post_content,
        'start_date'    => $start_date,
        'end_date'      => $end_date,
        'status'        => $status,
        'starting_bid'  => $starting_bid,
        'current_bid'   => $current_bid,
        'reserve_price' => $reserve_price,
        'bid_increment' => $bid_increment,
        'permalink'     => get_permalink( $auction->ID ),
        'thumbnail'     => get_the_post_thumbnail_url( $auction->ID, 'large' ),
    );
}

/**
 * Get auctions
 *
 * @param array $args Query arguments
 * @return array Array of auctions
 */
function aqualuxe_get_auctions( $args = array() ) {
    $defaults = array(
        'status'    => '',
        'limit'     => 10,
        'offset'    => 0,
        'orderby'   => 'meta_value',
        'meta_key'  => '_auction_end_date',
        'order'     => 'ASC',
    );
    
    $args = wp_parse_args( $args, $defaults );
    
    $query_args = array(
        'post_type'      => 'aqualuxe_auction',
        'posts_per_page' => $args['limit'],
        'offset'         => $args['offset'],
        'orderby'        => $args['orderby'],
        'meta_key'       => $args['meta_key'],
        'order'          => $args['order'],
    );
    
    // Add meta query for status
    if ( ! empty( $args['status'] ) ) {
        $query_args['meta_query'][] = array(
            'key'     => '_auction_status',
            'value'   => $args['status'],
            'compare' => '=',
        );
    }
    
    $auctions_query = new WP_Query( $query_args );
    $auctions = array();
    
    if ( $auctions_query->have_posts() ) {
        while ( $auctions_query->have_posts() ) {
            $auctions_query->the_post();
            $auctions[] = aqualuxe_get_auction( get_the_ID() );
        }
        wp_reset_postdata();
    }
    
    return $auctions;
}

/**
 * Place a bid on an auction
 *
 * @param int $auction_id Auction ID
 * @param float $bid_amount Bid amount
 * @param int $user_id User ID
 * @return bool|WP_Error True on success, WP_Error on failure
 */
function aqualuxe_place_bid( $auction_id, $bid_amount, $user_id = 0 ) {
    // Get auction details
    $auction = aqualuxe_get_auction( $auction_id );
    
    if ( ! $auction ) {
        return new WP_Error( 'invalid_auction', __( 'Invalid auction', 'aqualuxe' ) );
    }
    
    // Check if auction is active
    if ( $auction['status'] !== 'active' ) {
        return new WP_Error( 'inactive_auction', __( 'This auction is not active', 'aqualuxe' ) );
    }
    
    // Check if bid is high enough
    $current_bid = floatval( $auction['current_bid'] );
    $starting_bid = floatval( $auction['starting_bid'] );
    $bid_increment = floatval( $auction['bid_increment'] );
    
    $minimum_bid = $current_bid ? $current_bid + $bid_increment : $starting_bid;
    
    if ( floatval( $bid_amount ) < $minimum_bid ) {
        return new WP_Error( 'bid_too_low', sprintf( __( 'Bid must be at least %s', 'aqualuxe' ), aqualuxe_format_price( $minimum_bid ) ) );
    }
    
    // Get user ID if not provided
    if ( ! $user_id ) {
        $user_id = get_current_user_id();
    }
    
    // Check if user is logged in
    if ( ! $user_id ) {
        return new WP_Error( 'not_logged_in', __( 'You must be logged in to place a bid', 'aqualuxe' ) );
    }
    
    // Record the bid
    $bid_id = wp_insert_comment( array(
        'comment_post_ID'      => $auction_id,
        'comment_author'       => get_the_author_meta( 'display_name', $user_id ),
        'comment_author_email' => get_the_author_meta( 'user_email', $user_id ),
        'comment_author_url'   => '',
        'comment_content'      => $bid_amount,
        'comment_type'         => 'auction_bid',
        'comment_parent'       => 0,
        'user_id'              => $user_id,
        'comment_approved'     => 1,
        'comment_meta'         => array(
            'bid_amount' => $bid_amount,
        ),
    ) );
    
    if ( ! $bid_id ) {
        return new WP_Error( 'bid_failed', __( 'Failed to place bid', 'aqualuxe' ) );
    }
    
    // Update auction current bid
    update_post_meta( $auction_id, '_auction_current_bid', $bid_amount );
    update_post_meta( $auction_id, '_auction_current_bidder', $user_id );
    
    // Trigger action
    do_action( 'aqualuxe_auction_bid_placed', $auction_id, $bid_amount, $user_id, $bid_id );
    
    return true;
}

/**
 * Get bids for an auction
 *
 * @param int $auction_id Auction ID
 * @param array $args Query arguments
 * @return array Array of bids
 */
function aqualuxe_get_auction_bids( $auction_id, $args = array() ) {
    $defaults = array(
        'number'  => 10,
        'offset'  => 0,
        'orderby' => 'comment_date',
        'order'   => 'DESC',
    );
    
    $args = wp_parse_args( $args, $defaults );
    $args['type'] = 'auction_bid';
    $args['post_id'] = $auction_id;
    
    $bids = get_comments( $args );
    $formatted_bids = array();
    
    foreach ( $bids as $bid ) {
        $formatted_bids[] = array(
            'id'        => $bid->comment_ID,
            'user_id'   => $bid->user_id,
            'user_name' => $bid->comment_author,
            'amount'    => get_comment_meta( $bid->comment_ID, 'bid_amount', true ),
            'date'      => $bid->comment_date,
        );
    }
    
    return $formatted_bids;
}

/**
 * Format price
 *
 * @param float $price Price to format
 * @return string Formatted price
 */
function aqualuxe_format_price( $price ) {
    return '$' . number_format( $price, 2 );
}

/**
 * Register auction shortcodes
 */
function aqualuxe_register_auction_shortcodes() {
    add_shortcode( 'aqualuxe_auctions', 'aqualuxe_auctions_shortcode' );
    add_shortcode( 'aqualuxe_auction_details', 'aqualuxe_auction_details_shortcode' );
}
add_action( 'init', 'aqualuxe_register_auction_shortcodes' );

/**
 * Auctions shortcode
 *
 * @param array $atts Shortcode attributes
 * @return string Shortcode output
 */
function aqualuxe_auctions_shortcode( $atts ) {
    $atts = shortcode_atts( array(
        'status' => '',
        'limit'  => 10,
        'order'  => 'ASC',
    ), $atts, 'aqualuxe_auctions' );
    
    $auctions = aqualuxe_get_auctions( array(
        'status' => $atts['status'],
        'limit'  => $atts['limit'],
        'order'  => $atts['order'],
    ) );
    
    ob_start();
    
    if ( ! empty( $auctions ) ) {
        ?>
        <div class="aqualuxe-auctions">
            <?php foreach ( $auctions as $auction ) : ?>
                <div class="aqualuxe-auction">
                    <?php if ( $auction['thumbnail'] ) : ?>
                        <div class="aqualuxe-auction-thumbnail">
                            <a href="<?php echo esc_url( $auction['permalink'] ); ?>">
                                <img src="<?php echo esc_url( $auction['thumbnail'] ); ?>" alt="<?php echo esc_attr( $auction['title'] ); ?>">
                            </a>
                        </div>
                    <?php endif; ?>
                    
                    <div class="aqualuxe-auction-details">
                        <h3 class="aqualuxe-auction-title">
                            <a href="<?php echo esc_url( $auction['permalink'] ); ?>"><?php echo esc_html( $auction['title'] ); ?></a>
                        </h3>
                        
                        <div class="aqualuxe-auction-meta">
                            <span class="aqualuxe-auction-status <?php echo esc_attr( $auction['status'] ); ?>">
                                <?php echo esc_html( aqualuxe_get_auction_status_label( $auction['status'] ) ); ?>
                            </span>
                            
                            <?php if ( $auction['current_bid'] ) : ?>
                                <span class="aqualuxe-auction-current-bid">
                                    <?php echo esc_html( sprintf( __( 'Current Bid: %s', 'aqualuxe' ), aqualuxe_format_price( $auction['current_bid'] ) ) ); ?>
                                </span>
                            <?php else : ?>
                                <span class="aqualuxe-auction-starting-bid">
                                    <?php echo esc_html( sprintf( __( 'Starting Bid: %s', 'aqualuxe' ), aqualuxe_format_price( $auction['starting_bid'] ) ) ); ?>
                                </span>
                            <?php endif; ?>
                            
                            <?php if ( $auction['status'] === 'active' ) : ?>
                                <span class="aqualuxe-auction-end-date">
                                    <?php echo esc_html( sprintf( __( 'Ends: %s', 'aqualuxe' ), date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), strtotime( $auction['end_date'] ) ) ) ); ?>
                                </span>
                            <?php elseif ( $auction['status'] === 'upcoming' ) : ?>
                                <span class="aqualuxe-auction-start-date">
                                    <?php echo esc_html( sprintf( __( 'Starts: %s', 'aqualuxe' ), date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), strtotime( $auction['start_date'] ) ) ) ); ?>
                                </span>
                            <?php endif; ?>
                        </div>
                        
                        <a href="<?php echo esc_url( $auction['permalink'] ); ?>" class="aqualuxe-button">
                            <?php echo esc_html( $auction['status'] === 'active' ? __( 'Bid Now', 'aqualuxe' ) : __( 'View Details', 'aqualuxe' ) ); ?>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <?php
    } else {
        ?>
        <p><?php esc_html_e( 'No auctions found.', 'aqualuxe' ); ?></p>
        <?php
    }
    
    return ob_get_clean();
}

/**
 * Auction details shortcode
 *
 * @param array $atts Shortcode attributes
 * @return string Shortcode output
 */
function aqualuxe_auction_details_shortcode( $atts ) {
    $atts = shortcode_atts( array(
        'id' => 0,
    ), $atts, 'aqualuxe_auction_details' );
    
    if ( ! $atts['id'] ) {
        global $post;
        $auction_id = $post->ID;
    } else {
        $auction_id = $atts['id'];
    }
    
    $auction = aqualuxe_get_auction( $auction_id );
    
    if ( ! $auction ) {
        return '<p>' . esc_html__( 'Auction not found.', 'aqualuxe' ) . '</p>';
    }
    
    ob_start();
    ?>
    <div class="aqualuxe-auction-details-container">
        <div class="aqualuxe-auction-status-bar <?php echo esc_attr( $auction['status'] ); ?>">
            <span class="aqualuxe-auction-status">
                <?php echo esc_html( aqualuxe_get_auction_status_label( $auction['status'] ) ); ?>
            </span>
            
            <?php if ( $auction['status'] === 'active' ) : ?>
                <span class="aqualuxe-auction-time-remaining">
                    <?php echo esc_html( sprintf( __( 'Ends in: %s', 'aqualuxe' ), human_time_diff( current_time( 'timestamp' ), strtotime( $auction['end_date'] ) ) ) ); ?>
                </span>
            <?php elseif ( $auction['status'] === 'upcoming' ) : ?>
                <span class="aqualuxe-auction-time-remaining">
                    <?php echo esc_html( sprintf( __( 'Starts in: %s', 'aqualuxe' ), human_time_diff( current_time( 'timestamp' ), strtotime( $auction['start_date'] ) ) ) ); ?>
                </span>
            <?php endif; ?>
        </div>
        
        <div class="aqualuxe-auction-bid-info">
            <?php if ( $auction['current_bid'] ) : ?>
                <div class="aqualuxe-auction-current-bid">
                    <span class="label"><?php esc_html_e( 'Current Bid:', 'aqualuxe' ); ?></span>
                    <span class="value"><?php echo esc_html( aqualuxe_format_price( $auction['current_bid'] ) ); ?></span>
                </div>
            <?php else : ?>
                <div class="aqualuxe-auction-starting-bid">
                    <span class="label"><?php esc_html_e( 'Starting Bid:', 'aqualuxe' ); ?></span>
                    <span class="value"><?php echo esc_html( aqualuxe_format_price( $auction['starting_bid'] ) ); ?></span>
                </div>
            <?php endif; ?>
            
            <?php if ( $auction['bid_increment'] ) : ?>
                <div class="aqualuxe-auction-bid-increment">
                    <span class="label"><?php esc_html_e( 'Bid Increment:', 'aqualuxe' ); ?></span>
                    <span class="value"><?php echo esc_html( aqualuxe_format_price( $auction['bid_increment'] ) ); ?></span>
                </div>
            <?php endif; ?>
            
            <?php if ( $auction['status'] === 'active' ) : ?>
                <div class="aqualuxe-auction-minimum-bid">
                    <span class="label"><?php esc_html_e( 'Minimum Bid:', 'aqualuxe' ); ?></span>
                    <span class="value">
                        <?php
                        $minimum_bid = $auction['current_bid'] ? $auction['current_bid'] + $auction['bid_increment'] : $auction['starting_bid'];
                        echo esc_html( aqualuxe_format_price( $minimum_bid ) );
                        ?>
                    </span>
                </div>
            <?php endif; ?>
        </div>
        
        <?php if ( $auction['status'] === 'active' && is_user_logged_in() ) : ?>
            <div class="aqualuxe-auction-bid-form">
                <h3><?php esc_html_e( 'Place Your Bid', 'aqualuxe' ); ?></h3>
                
                <form method="post" action="">
                    <?php wp_nonce_field( 'aqualuxe_place_bid', 'aqualuxe_bid_nonce' ); ?>
                    <input type="hidden" name="auction_id" value="<?php echo esc_attr( $auction['id'] ); ?>">
                    
                    <div class="form-row">
                        <label for="bid_amount"><?php esc_html_e( 'Your Bid Amount', 'aqualuxe' ); ?></label>
                        <input type="number" name="bid_amount" id="bid_amount" min="<?php echo esc_attr( $minimum_bid ); ?>" step="0.01" required>
                    </div>
                    
                    <div class="form-row">
                        <button type="submit" name="aqualuxe_place_bid" class="aqualuxe-button"><?php esc_html_e( 'Place Bid', 'aqualuxe' ); ?></button>
                    </div>
                </form>
            </div>
        <?php elseif ( $auction['status'] === 'active' && ! is_user_logged_in() ) : ?>
            <div class="aqualuxe-auction-login-notice">
                <p><?php esc_html_e( 'Please log in to place a bid.', 'aqualuxe' ); ?></p>
                <a href="<?php echo esc_url( wp_login_url( get_permalink() ) ); ?>" class="aqualuxe-button"><?php esc_html_e( 'Log In', 'aqualuxe' ); ?></a>
            </div>
        <?php endif; ?>
        
        <div class="aqualuxe-auction-bids">
            <h3><?php esc_html_e( 'Bid History', 'aqualuxe' ); ?></h3>
            
            <?php
            $bids = aqualuxe_get_auction_bids( $auction['id'] );
            
            if ( ! empty( $bids ) ) {
                ?>
                <table class="aqualuxe-auction-bids-table">
                    <thead>
                        <tr>
                            <th><?php esc_html_e( 'Bidder', 'aqualuxe' ); ?></th>
                            <th><?php esc_html_e( 'Amount', 'aqualuxe' ); ?></th>
                            <th><?php esc_html_e( 'Date', 'aqualuxe' ); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ( $bids as $bid ) : ?>
                            <tr>
                                <td><?php echo esc_html( $bid['user_name'] ); ?></td>
                                <td><?php echo esc_html( aqualuxe_format_price( $bid['amount'] ) ); ?></td>
                                <td><?php echo esc_html( date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), strtotime( $bid['date'] ) ) ); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php
            } else {
                ?>
                <p><?php esc_html_e( 'No bids yet.', 'aqualuxe' ); ?></p>
                <?php
            }
            ?>
        </div>
    </div>
    <?php
    
    return ob_get_clean();
}

/**
 * Process bid form submission
 */
function aqualuxe_process_bid_form() {
    if ( ! isset( $_POST['aqualuxe_place_bid'] ) || ! isset( $_POST['aqualuxe_bid_nonce'] ) ) {
        return;
    }
    
    if ( ! wp_verify_nonce( $_POST['aqualuxe_bid_nonce'], 'aqualuxe_place_bid' ) ) {
        wp_die( __( 'Security check failed', 'aqualuxe' ) );
    }
    
    $auction_id = isset( $_POST['auction_id'] ) ? intval( $_POST['auction_id'] ) : 0;
    $bid_amount = isset( $_POST['bid_amount'] ) ? floatval( $_POST['bid_amount'] ) : 0;
    
    if ( ! $auction_id || ! $bid_amount ) {
        wp_die( __( 'Invalid bid data', 'aqualuxe' ) );
    }
    
    $result = aqualuxe_place_bid( $auction_id, $bid_amount );
    
    if ( is_wp_error( $result ) ) {
        wp_die( $result->get_error_message() );
    }
    
    wp_redirect( add_query_arg( 'bid_placed', '1', get_permalink( $auction_id ) ) );
    exit;
}
add_action( 'template_redirect', 'aqualuxe_process_bid_form' );

/**
 * Display bid success message
 */
function aqualuxe_display_bid_success_message() {
    if ( isset( $_GET['bid_placed'] ) && $_GET['bid_placed'] === '1' ) {
        ?>
        <div class="aqualuxe-message success">
            <?php esc_html_e( 'Your bid has been placed successfully!', 'aqualuxe' ); ?>
        </div>
        <?php
    }
}
add_action( 'aqualuxe_before_content', 'aqualuxe_display_bid_success_message' );