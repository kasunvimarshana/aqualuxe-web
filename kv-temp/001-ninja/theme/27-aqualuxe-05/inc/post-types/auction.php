<?php
/**
 * AquaLuxe Auction System
 *
 * Handles auction functionality for rare fish specimens including custom post type,
 * meta boxes, bidding system, and AJAX handlers.
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Register Auction custom post type
 */
function aqualuxe_register_auction_post_type() {
    $labels = array(
        'name'                  => _x( 'Auctions', 'Post type general name', 'aqualuxe' ),
        'singular_name'         => _x( 'Auction', 'Post type singular name', 'aqualuxe' ),
        'menu_name'             => _x( 'Auctions', 'Admin Menu text', 'aqualuxe' ),
        'name_admin_bar'        => _x( 'Auction', 'Add New on Toolbar', 'aqualuxe' ),
        'add_new'               => __( 'Add New', 'aqualuxe' ),
        'add_new_item'          => __( 'Add New Auction', 'aqualuxe' ),
        'new_item'              => __( 'New Auction', 'aqualuxe' ),
        'edit_item'             => __( 'Edit Auction', 'aqualuxe' ),
        'view_item'             => __( 'View Auction', 'aqualuxe' ),
        'all_items'             => __( 'All Auctions', 'aqualuxe' ),
        'search_items'          => __( 'Search Auctions', 'aqualuxe' ),
        'not_found'             => __( 'No auctions found.', 'aqualuxe' ),
        'not_found_in_trash'    => __( 'No auctions found in Trash.', 'aqualuxe' ),
        'featured_image'        => _x( 'Auction Image', 'Overrides the "Featured Image" phrase', 'aqualuxe' ),
        'set_featured_image'    => _x( 'Set auction image', 'Overrides the "Set featured image" phrase', 'aqualuxe' ),
        'remove_featured_image' => _x( 'Remove auction image', 'Overrides the "Remove featured image" phrase', 'aqualuxe' ),
        'use_featured_image'    => _x( 'Use as auction image', 'Overrides the "Use as featured image" phrase', 'aqualuxe' ),
        'archives'              => _x( 'Auction Archives', 'The post type archive label used in nav menus', 'aqualuxe' ),
        'filter_items_list'     => _x( 'Filter auctions list', 'Screen reader text for the filter links', 'aqualuxe' ),
        'items_list_navigation' => _x( 'Auctions list navigation', 'Screen reader text for the pagination', 'aqualuxe' ),
        'items_list'            => _x( 'Auctions list', 'Screen reader text for the items list', 'aqualuxe' ),
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'auction' ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 21,
        'menu_icon'          => 'dashicons-money-alt',
        'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt', 'comments' ),
    );

    register_post_type( 'aqualuxe_auction', $args );
}
add_action( 'init', 'aqualuxe_register_auction_post_type' );

/**
 * Register Auction Category taxonomy
 */
function aqualuxe_register_auction_category_taxonomy() {
    $labels = array(
        'name'              => _x( 'Auction Categories', 'taxonomy general name', 'aqualuxe' ),
        'singular_name'     => _x( 'Auction Category', 'taxonomy singular name', 'aqualuxe' ),
        'search_items'      => __( 'Search Auction Categories', 'aqualuxe' ),
        'all_items'         => __( 'All Auction Categories', 'aqualuxe' ),
        'parent_item'       => __( 'Parent Auction Category', 'aqualuxe' ),
        'parent_item_colon' => __( 'Parent Auction Category:', 'aqualuxe' ),
        'edit_item'         => __( 'Edit Auction Category', 'aqualuxe' ),
        'update_item'       => __( 'Update Auction Category', 'aqualuxe' ),
        'add_new_item'      => __( 'Add New Auction Category', 'aqualuxe' ),
        'new_item_name'     => __( 'New Auction Category Name', 'aqualuxe' ),
        'menu_name'         => __( 'Categories', 'aqualuxe' ),
    );

    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'auction-category' ),
    );

    register_taxonomy( 'auction_category', array( 'aqualuxe_auction' ), $args );
    
    // Register default auction categories
    if ( ! term_exists( 'rare-species', 'auction_category' ) ) {
        wp_insert_term( 'Rare Species', 'auction_category', array( 'slug' => 'rare-species' ) );
    }
    
    if ( ! term_exists( 'limited-edition', 'auction_category' ) ) {
        wp_insert_term( 'Limited Edition', 'auction_category', array( 'slug' => 'limited-edition' ) );
    }
    
    if ( ! term_exists( 'exotic-imports', 'auction_category' ) ) {
        wp_insert_term( 'Exotic Imports', 'auction_category', array( 'slug' => 'exotic-imports' ) );
    }
    
    if ( ! term_exists( 'breeding-pairs', 'auction_category' ) ) {
        wp_insert_term( 'Breeding Pairs', 'auction_category', array( 'slug' => 'breeding-pairs' ) );
    }
}
add_action( 'init', 'aqualuxe_register_auction_category_taxonomy' );

/**
 * Register Auction Status taxonomy
 */
function aqualuxe_register_auction_status_taxonomy() {
    $labels = array(
        'name'              => _x( 'Auction Statuses', 'taxonomy general name', 'aqualuxe' ),
        'singular_name'     => _x( 'Auction Status', 'taxonomy singular name', 'aqualuxe' ),
        'search_items'      => __( 'Search Auction Statuses', 'aqualuxe' ),
        'all_items'         => __( 'All Auction Statuses', 'aqualuxe' ),
        'parent_item'       => __( 'Parent Auction Status', 'aqualuxe' ),
        'parent_item_colon' => __( 'Parent Auction Status:', 'aqualuxe' ),
        'edit_item'         => __( 'Edit Auction Status', 'aqualuxe' ),
        'update_item'       => __( 'Update Auction Status', 'aqualuxe' ),
        'add_new_item'      => __( 'Add New Auction Status', 'aqualuxe' ),
        'new_item_name'     => __( 'New Auction Status Name', 'aqualuxe' ),
        'menu_name'         => __( 'Statuses', 'aqualuxe' ),
    );

    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'auction-status' ),
    );

    register_taxonomy( 'auction_status', array( 'aqualuxe_auction' ), $args );
    
    // Register default auction statuses
    if ( ! term_exists( 'upcoming', 'auction_status' ) ) {
        wp_insert_term( 'Upcoming', 'auction_status', array( 'slug' => 'upcoming' ) );
    }
    
    if ( ! term_exists( 'active', 'auction_status' ) ) {
        wp_insert_term( 'Active', 'auction_status', array( 'slug' => 'active' ) );
    }
    
    if ( ! term_exists( 'ended', 'auction_status' ) ) {
        wp_insert_term( 'Ended', 'auction_status', array( 'slug' => 'ended' ) );
    }
    
    if ( ! term_exists( 'cancelled', 'auction_status' ) ) {
        wp_insert_term( 'Cancelled', 'auction_status', array( 'slug' => 'cancelled' ) );
    }
}
add_action( 'init', 'aqualuxe_register_auction_status_taxonomy' );

/**
 * Register meta boxes for the auction post type
 */
function aqualuxe_register_auction_meta_boxes() {
    add_meta_box(
        'aqualuxe_auction_details',
        __( 'Auction Details', 'aqualuxe' ),
        'aqualuxe_auction_details_meta_box_callback',
        'aqualuxe_auction',
        'normal',
        'high'
    );
    
    add_meta_box(
        'aqualuxe_auction_bids',
        __( 'Auction Bids', 'aqualuxe' ),
        'aqualuxe_auction_bids_meta_box_callback',
        'aqualuxe_auction',
        'normal',
        'default'
    );
    
    add_meta_box(
        'aqualuxe_auction_winner',
        __( 'Auction Winner', 'aqualuxe' ),
        'aqualuxe_auction_winner_meta_box_callback',
        'aqualuxe_auction',
        'side',
        'default'
    );
}
add_action( 'add_meta_boxes', 'aqualuxe_register_auction_meta_boxes' );

/**
 * Auction Details meta box callback
 */
function aqualuxe_auction_details_meta_box_callback( $post ) {
    // Add nonce for security
    wp_nonce_field( 'aqualuxe_auction_details_nonce', 'auction_details_nonce' );
    
    // Get saved values
    $start_date = get_post_meta( $post->ID, '_auction_start_date', true );
    $end_date = get_post_meta( $post->ID, '_auction_end_date', true );
    $start_price = get_post_meta( $post->ID, '_auction_start_price', true );
    $reserve_price = get_post_meta( $post->ID, '_auction_reserve_price', true );
    $min_bid_increment = get_post_meta( $post->ID, '_auction_min_bid_increment', true );
    $current_bid = get_post_meta( $post->ID, '_auction_current_bid', true );
    $featured = get_post_meta( $post->ID, '_auction_featured', true );
    ?>
    <div class="auction-details-meta-box">
        <style>
            .auction-details-meta-box .form-field {
                margin-bottom: 15px;
            }
            .auction-details-meta-box label {
                display: block;
                font-weight: 600;
                margin-bottom: 5px;
            }
            .auction-details-meta-box input[type="text"],
            .auction-details-meta-box input[type="number"],
            .auction-details-meta-box select {
                width: 100%;
                max-width: 400px;
            }
            .auction-details-meta-box .form-row {
                display: flex;
                flex-wrap: wrap;
                margin: 0 -10px;
            }
            .auction-details-meta-box .form-col {
                flex: 1;
                padding: 0 10px;
                min-width: 200px;
            }
            .auction-details-meta-box .checkbox-field {
                margin-top: 20px;
            }
            .auction-details-meta-box .checkbox-field label {
                display: inline;
                margin-left: 5px;
            }
        </style>
        
        <div class="form-row">
            <div class="form-col">
                <div class="form-field">
                    <label for="auction_start_date"><?php esc_html_e( 'Start Date & Time', 'aqualuxe' ); ?></label>
                    <input type="text" id="auction_start_date" name="auction_start_date" value="<?php echo esc_attr( $start_date ); ?>" class="datepicker" placeholder="YYYY-MM-DD HH:MM" />
                    <p class="description"><?php esc_html_e( 'Format: YYYY-MM-DD HH:MM', 'aqualuxe' ); ?></p>
                </div>
                
                <div class="form-field">
                    <label for="auction_end_date"><?php esc_html_e( 'End Date & Time', 'aqualuxe' ); ?></label>
                    <input type="text" id="auction_end_date" name="auction_end_date" value="<?php echo esc_attr( $end_date ); ?>" class="datepicker" placeholder="YYYY-MM-DD HH:MM" />
                    <p class="description"><?php esc_html_e( 'Format: YYYY-MM-DD HH:MM', 'aqualuxe' ); ?></p>
                </div>
                
                <div class="form-field">
                    <label for="auction_status"><?php esc_html_e( 'Status', 'aqualuxe' ); ?></label>
                    <?php
                    $terms = get_terms( array(
                        'taxonomy'   => 'auction_status',
                        'hide_empty' => false,
                    ) );
                    
                    if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
                        echo '<select id="auction_status" name="auction_status">';
                        foreach ( $terms as $term ) {
                            $selected = has_term( $term->term_id, 'auction_status', $post->ID ) ? 'selected' : '';
                            echo '<option value="' . esc_attr( $term->term_id ) . '" ' . $selected . '>' . esc_html( $term->name ) . '</option>';
                        }
                        echo '</select>';
                    }
                    ?>
                </div>
            </div>
            
            <div class="form-col">
                <div class="form-field">
                    <label for="auction_start_price"><?php esc_html_e( 'Starting Price ($)', 'aqualuxe' ); ?></label>
                    <input type="number" id="auction_start_price" name="auction_start_price" value="<?php echo esc_attr( $start_price ); ?>" step="0.01" min="0" />
                </div>
                
                <div class="form-field">
                    <label for="auction_reserve_price"><?php esc_html_e( 'Reserve Price ($)', 'aqualuxe' ); ?></label>
                    <input type="number" id="auction_reserve_price" name="auction_reserve_price" value="<?php echo esc_attr( $reserve_price ); ?>" step="0.01" min="0" />
                    <p class="description"><?php esc_html_e( 'Minimum price that must be met for the auction to be successful. Leave empty for no reserve.', 'aqualuxe' ); ?></p>
                </div>
                
                <div class="form-field">
                    <label for="auction_min_bid_increment"><?php esc_html_e( 'Minimum Bid Increment ($)', 'aqualuxe' ); ?></label>
                    <input type="number" id="auction_min_bid_increment" name="auction_min_bid_increment" value="<?php echo esc_attr( $min_bid_increment ); ?>" step="0.01" min="0" />
                    <p class="description"><?php esc_html_e( 'Minimum amount by which a bid must exceed the current highest bid.', 'aqualuxe' ); ?></p>
                </div>
                
                <div class="form-field">
                    <label for="auction_current_bid"><?php esc_html_e( 'Current Highest Bid ($)', 'aqualuxe' ); ?></label>
                    <input type="number" id="auction_current_bid" name="auction_current_bid" value="<?php echo esc_attr( $current_bid ); ?>" step="0.01" min="0" readonly />
                    <p class="description"><?php esc_html_e( 'This field is automatically updated when bids are placed.', 'aqualuxe' ); ?></p>
                </div>
                
                <div class="checkbox-field">
                    <input type="checkbox" id="auction_featured" name="auction_featured" value="1" <?php checked( $featured, '1' ); ?> />
                    <label for="auction_featured"><?php esc_html_e( 'Featured Auction', 'aqualuxe' ); ?></label>
                    <p class="description"><?php esc_html_e( 'Featured auctions are highlighted on the website.', 'aqualuxe' ); ?></p>
                </div>
            </div>
        </div>
    </div>
    
    <script>
    jQuery(document).ready(function($) {
        // Initialize datepicker
        if ($.fn.datetimepicker) {
            $('#auction_start_date, #auction_end_date').datetimepicker({
                format: 'Y-m-d H:i',
                step: 30
            });
        }
    });
    </script>
    <?php
}

/**
 * Auction Bids meta box callback
 */
function aqualuxe_auction_bids_meta_box_callback( $post ) {
    // Get bids
    $bids = get_post_meta( $post->ID, '_auction_bids', true );
    
    if ( ! is_array( $bids ) ) {
        $bids = array();
    }
    ?>
    <div class="auction-bids-meta-box">
        <style>
            .auction-bids-table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 10px;
            }
            .auction-bids-table th,
            .auction-bids-table td {
                padding: 8px;
                text-align: left;
                border-bottom: 1px solid #ddd;
            }
            .auction-bids-table th {
                background-color: #f5f5f5;
                font-weight: 600;
            }
            .auction-bids-table tr:hover {
                background-color: #f9f9f9;
            }
            .auction-bids-table .no-bids {
                text-align: center;
                padding: 20px;
                color: #777;
            }
        </style>
        
        <?php if ( empty( $bids ) ) : ?>
            <p class="no-bids"><?php esc_html_e( 'No bids have been placed yet.', 'aqualuxe' ); ?></p>
        <?php else : ?>
            <table class="auction-bids-table">
                <thead>
                    <tr>
                        <th><?php esc_html_e( 'Bidder', 'aqualuxe' ); ?></th>
                        <th><?php esc_html_e( 'Bid Amount', 'aqualuxe' ); ?></th>
                        <th><?php esc_html_e( 'Date & Time', 'aqualuxe' ); ?></th>
                        <th><?php esc_html_e( 'IP Address', 'aqualuxe' ); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    // Sort bids by date (newest first)
                    usort( $bids, function( $a, $b ) {
                        return strtotime( $b['date'] ) - strtotime( $a['date'] );
                    } );
                    
                    foreach ( $bids as $bid ) : 
                        $user_info = '';
                        if ( ! empty( $bid['user_id'] ) ) {
                            $user = get_user_by( 'id', $bid['user_id'] );
                            if ( $user ) {
                                $user_info = $user->display_name . ' (' . $user->user_email . ')';
                            }
                        } else {
                            $user_info = $bid['name'] . ' (' . $bid['email'] . ')';
                        }
                    ?>
                        <tr>
                            <td><?php echo esc_html( $user_info ); ?></td>
                            <td><?php echo esc_html( '$' . number_format( $bid['amount'], 2 ) ); ?></td>
                            <td><?php echo esc_html( date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), strtotime( $bid['date'] ) ) ); ?></td>
                            <td><?php echo esc_html( $bid['ip'] ); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
    <?php
}

/**
 * Auction Winner meta box callback
 */
function aqualuxe_auction_winner_meta_box_callback( $post ) {
    // Add nonce for security
    wp_nonce_field( 'aqualuxe_auction_winner_nonce', 'auction_winner_nonce' );
    
    // Get saved values
    $winner_id = get_post_meta( $post->ID, '_auction_winner_id', true );
    $winner_name = get_post_meta( $post->ID, '_auction_winner_name', true );
    $winner_email = get_post_meta( $post->ID, '_auction_winner_email', true );
    $winning_bid = get_post_meta( $post->ID, '_auction_winning_bid', true );
    $payment_status = get_post_meta( $post->ID, '_auction_payment_status', true );
    
    // Get auction status
    $status_terms = get_the_terms( $post->ID, 'auction_status' );
    $status = ! empty( $status_terms ) ? $status_terms[0]->slug : '';
    
    // Check if auction has ended
    $has_ended = ( 'ended' === $status );
    ?>
    <div class="auction-winner-meta-box">
        <?php if ( $has_ended && ( $winner_id || $winner_name ) ) : ?>
            <div class="winner-info">
                <p>
                    <strong><?php esc_html_e( 'Winner:', 'aqualuxe' ); ?></strong><br>
                    <?php 
                    if ( $winner_id ) {
                        $user = get_user_by( 'id', $winner_id );
                        if ( $user ) {
                            echo esc_html( $user->display_name ) . '<br>';
                            echo esc_html( $user->user_email );
                        }
                    } else {
                        echo esc_html( $winner_name ) . '<br>';
                        echo esc_html( $winner_email );
                    }
                    ?>
                </p>
                
                <p>
                    <strong><?php esc_html_e( 'Winning Bid:', 'aqualuxe' ); ?></strong><br>
                    <?php echo esc_html( '$' . number_format( $winning_bid, 2 ) ); ?>
                </p>
                
                <p>
                    <label for="auction_payment_status"><strong><?php esc_html_e( 'Payment Status:', 'aqualuxe' ); ?></strong></label><br>
                    <select id="auction_payment_status" name="auction_payment_status">
                        <option value="pending" <?php selected( $payment_status, 'pending' ); ?>><?php esc_html_e( 'Pending', 'aqualuxe' ); ?></option>
                        <option value="paid" <?php selected( $payment_status, 'paid' ); ?>><?php esc_html_e( 'Paid', 'aqualuxe' ); ?></option>
                        <option value="failed" <?php selected( $payment_status, 'failed' ); ?>><?php esc_html_e( 'Failed', 'aqualuxe' ); ?></option>
                    </select>
                </p>
                
                <p>
                    <a href="<?php echo esc_url( admin_url( 'admin.php?page=aqualuxe-auction-notify&auction_id=' . $post->ID ) ); ?>" class="button">
                        <?php esc_html_e( 'Notify Winner', 'aqualuxe' ); ?>
                    </a>
                </p>
            </div>
        <?php elseif ( $has_ended ) : ?>
            <p><?php esc_html_e( 'This auction has ended, but no winner has been determined.', 'aqualuxe' ); ?></p>
            
            <p>
                <label for="manual_winner_name"><?php esc_html_e( 'Manually Set Winner:', 'aqualuxe' ); ?></label><br>
                <input type="text" id="manual_winner_name" name="manual_winner_name" placeholder="<?php esc_attr_e( 'Winner Name', 'aqualuxe' ); ?>" class="widefat" />
            </p>
            
            <p>
                <input type="email" id="manual_winner_email" name="manual_winner_email" placeholder="<?php esc_attr_e( 'Winner Email', 'aqualuxe' ); ?>" class="widefat" />
            </p>
            
            <p>
                <input type="number" id="manual_winning_bid" name="manual_winning_bid" placeholder="<?php esc_attr_e( 'Winning Bid ($)', 'aqualuxe' ); ?>" step="0.01" min="0" class="widefat" />
            </p>
        <?php else : ?>
            <p><?php esc_html_e( 'The auction winner will be determined when the auction ends.', 'aqualuxe' ); ?></p>
        <?php endif; ?>
    </div>
    <?php
}

/**
 * Save auction meta box data
 */
function aqualuxe_save_auction_meta_box_data( $post_id ) {
    // Check if our nonces are set and verify them
    if ( ! isset( $_POST['auction_details_nonce'] ) || ! wp_verify_nonce( $_POST['auction_details_nonce'], 'aqualuxe_auction_details_nonce' ) ) {
        return;
    }
    
    if ( isset( $_POST['auction_winner_nonce'] ) && ! wp_verify_nonce( $_POST['auction_winner_nonce'], 'aqualuxe_auction_winner_nonce' ) ) {
        return;
    }
    
    // Check if this is an autosave
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    
    // Check the user's permissions
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }
    
    // Auction details
    if ( isset( $_POST['auction_start_date'] ) ) {
        update_post_meta( $post_id, '_auction_start_date', sanitize_text_field( $_POST['auction_start_date'] ) );
    }
    
    if ( isset( $_POST['auction_end_date'] ) ) {
        update_post_meta( $post_id, '_auction_end_date', sanitize_text_field( $_POST['auction_end_date'] ) );
    }
    
    if ( isset( $_POST['auction_start_price'] ) ) {
        update_post_meta( $post_id, '_auction_start_price', (float) $_POST['auction_start_price'] );
    }
    
    if ( isset( $_POST['auction_reserve_price'] ) ) {
        update_post_meta( $post_id, '_auction_reserve_price', (float) $_POST['auction_reserve_price'] );
    }
    
    if ( isset( $_POST['auction_min_bid_increment'] ) ) {
        update_post_meta( $post_id, '_auction_min_bid_increment', (float) $_POST['auction_min_bid_increment'] );
    }
    
    // Featured auction
    $featured = isset( $_POST['auction_featured'] ) ? '1' : '0';
    update_post_meta( $post_id, '_auction_featured', $featured );
    
    // Update auction status
    if ( isset( $_POST['auction_status'] ) ) {
        wp_set_object_terms( $post_id, intval( $_POST['auction_status'] ), 'auction_status' );
    }
    
    // Manual winner
    if ( isset( $_POST['manual_winner_name'] ) && ! empty( $_POST['manual_winner_name'] ) ) {
        update_post_meta( $post_id, '_auction_winner_name', sanitize_text_field( $_POST['manual_winner_name'] ) );
        
        if ( isset( $_POST['manual_winner_email'] ) ) {
            update_post_meta( $post_id, '_auction_winner_email', sanitize_email( $_POST['manual_winner_email'] ) );
        }
        
        if ( isset( $_POST['manual_winning_bid'] ) ) {
            update_post_meta( $post_id, '_auction_winning_bid', (float) $_POST['manual_winning_bid'] );
            update_post_meta( $post_id, '_auction_current_bid', (float) $_POST['manual_winning_bid'] );
        }
    }
    
    // Payment status
    if ( isset( $_POST['auction_payment_status'] ) ) {
        update_post_meta( $post_id, '_auction_payment_status', sanitize_text_field( $_POST['auction_payment_status'] ) );
    }
}
add_action( 'save_post_aqualuxe_auction', 'aqualuxe_save_auction_meta_box_data' );

/**
 * Add custom columns to the auction list table
 */
function aqualuxe_auction_columns( $columns ) {
    $new_columns = array();
    
    // Add checkbox and title at the beginning
    if ( isset( $columns['cb'] ) ) {
        $new_columns['cb'] = $columns['cb'];
    }
    
    if ( isset( $columns['title'] ) ) {
        $new_columns['title'] = $columns['title'];
    }
    
    // Add custom columns
    $new_columns['thumbnail'] = __( 'Image', 'aqualuxe' );
    $new_columns['current_bid'] = __( 'Current Bid', 'aqualuxe' );
    $new_columns['bids'] = __( 'Bids', 'aqualuxe' );
    $new_columns['dates'] = __( 'Auction Dates', 'aqualuxe' );
    $new_columns['status'] = __( 'Status', 'aqualuxe' );
    
    // Add date at the end
    if ( isset( $columns['date'] ) ) {
        $new_columns['date'] = $columns['date'];
    }
    
    return $new_columns;
}
add_filter( 'manage_aqualuxe_auction_posts_columns', 'aqualuxe_auction_columns' );

/**
 * Display data in custom columns for the auction list table
 */
function aqualuxe_auction_custom_column( $column, $post_id ) {
    switch ( $column ) {
        case 'thumbnail':
            if ( has_post_thumbnail( $post_id ) ) {
                echo get_the_post_thumbnail( $post_id, array( 50, 50 ) );
            } else {
                echo '<div style="width: 50px; height: 50px; background-color: #f0f0f0; display: flex; align-items: center; justify-content: center; color: #999;">N/A</div>';
            }
            break;
            
        case 'current_bid':
            $current_bid = get_post_meta( $post_id, '_auction_current_bid', true );
            $start_price = get_post_meta( $post_id, '_auction_start_price', true );
            
            if ( $current_bid ) {
                echo '<strong>' . esc_html( '$' . number_format( $current_bid, 2 ) ) . '</strong>';
            } else {
                echo '<span class="description">' . esc_html( '$' . number_format( $start_price, 2 ) ) . '</span>';
            }
            break;
            
        case 'bids':
            $bids = get_post_meta( $post_id, '_auction_bids', true );
            
            if ( is_array( $bids ) ) {
                echo count( $bids );
            } else {
                echo '0';
            }
            break;
            
        case 'dates':
            $start_date = get_post_meta( $post_id, '_auction_start_date', true );
            $end_date = get_post_meta( $post_id, '_auction_end_date', true );
            
            if ( $start_date ) {
                echo '<strong>' . esc_html__( 'Start:', 'aqualuxe' ) . '</strong> ' . esc_html( date_i18n( get_option( 'date_format' ), strtotime( $start_date ) ) );
            }
            
            if ( $end_date ) {
                echo '<br><strong>' . esc_html__( 'End:', 'aqualuxe' ) . '</strong> ' . esc_html( date_i18n( get_option( 'date_format' ), strtotime( $end_date ) ) );
            }
            break;
            
        case 'status':
            $terms = get_the_terms( $post_id, 'auction_status' );
            
            if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
                $status = $terms[0]->name;
                $slug = $terms[0]->slug;
                
                $status_colors = array(
                    'upcoming'  => '#f0ad4e',
                    'active'    => '#5cb85c',
                    'ended'     => '#5bc0de',
                    'cancelled' => '#d9534f',
                );
                
                $color = isset( $status_colors[ $slug ] ) ? $status_colors[ $slug ] : '#777777';
                
                echo '<span style="display: inline-block; padding: 3px 8px; border-radius: 3px; background-color: ' . esc_attr( $color ) . '; color: #fff;">' . esc_html( $status ) . '</span>';
            } else {
                echo '—';
            }
            break;
    }
}
add_action( 'manage_aqualuxe_auction_posts_custom_column', 'aqualuxe_auction_custom_column', 10, 2 );

/**
 * Make custom columns sortable
 */
function aqualuxe_auction_sortable_columns( $columns ) {
    $columns['current_bid'] = 'current_bid';
    $columns['bids'] = 'bids';
    $columns['dates'] = 'start_date';
    $columns['status'] = 'status';
    return $columns;
}
add_filter( 'manage_edit-aqualuxe_auction_sortable_columns', 'aqualuxe_auction_sortable_columns' );

/**
 * Add sorting functionality to custom columns
 */
function aqualuxe_auction_sort_columns( $query ) {
    if ( ! is_admin() || ! $query->is_main_query() ) {
        return;
    }
    
    if ( $query->get( 'post_type' ) !== 'aqualuxe_auction' ) {
        return;
    }
    
    $orderby = $query->get( 'orderby' );
    
    if ( 'current_bid' === $orderby ) {
        $query->set( 'meta_key', '_auction_current_bid' );
        $query->set( 'orderby', 'meta_value_num' );
    } elseif ( 'bids' === $orderby ) {
        $query->set( 'meta_key', '_auction_bids' );
        $query->set( 'orderby', 'meta_value' );
    } elseif ( 'start_date' === $orderby ) {
        $query->set( 'meta_key', '_auction_start_date' );
        $query->set( 'orderby', 'meta_value' );
    } elseif ( 'status' === $orderby ) {
        $query->set( 'orderby', 'tax_query' );
    }
}
add_action( 'pre_get_posts', 'aqualuxe_auction_sort_columns' );

/**
 * Add filter dropdown for auction status
 */
function aqualuxe_auction_status_filter() {
    global $typenow;
    
    if ( 'aqualuxe_auction' !== $typenow ) {
        return;
    }
    
    $current = isset( $_GET['auction_status'] ) ? $_GET['auction_status'] : '';
    $terms = get_terms( array(
        'taxonomy'   => 'auction_status',
        'hide_empty' => false,
    ) );
    
    if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
        echo '<select name="auction_status" id="auction_status" class="postform">';
        echo '<option value="">' . esc_html__( 'All Statuses', 'aqualuxe' ) . '</option>';
        
        foreach ( $terms as $term ) {
            printf(
                '<option value="%s" %s>%s</option>',
                esc_attr( $term->slug ),
                selected( $current, $term->slug, false ),
                esc_html( $term->name )
            );
        }
        
        echo '</select>';
    }
}
add_action( 'restrict_manage_posts', 'aqualuxe_auction_status_filter' );

/**
 * AJAX handler for placing a bid
 */
function aqualuxe_ajax_place_bid() {
    // Check nonce
    if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'aqualuxe_ajax_nonce' ) ) {
        wp_send_json_error( array( 'message' => __( 'Security check failed.', 'aqualuxe' ) ) );
    }
    
    // Check required fields
    if ( ! isset( $_POST['auction_id'] ) || ! isset( $_POST['bid_amount'] ) ) {
        wp_send_json_error( array( 'message' => __( 'Missing required fields.', 'aqualuxe' ) ) );
    }
    
    $auction_id = intval( $_POST['auction_id'] );
    $bid_amount = floatval( $_POST['bid_amount'] );
    
    // Verify auction exists and is active
    $auction = get_post( $auction_id );
    
    if ( ! $auction || 'aqualuxe_auction' !== $auction->post_type ) {
        wp_send_json_error( array( 'message' => __( 'Invalid auction.', 'aqualuxe' ) ) );
    }
    
    // Check auction status
    $status_terms = get_the_terms( $auction_id, 'auction_status' );
    $status = ! empty( $status_terms ) ? $status_terms[0]->slug : '';
    
    if ( 'active' !== $status ) {
        wp_send_json_error( array( 'message' => __( 'This auction is not active.', 'aqualuxe' ) ) );
    }
    
    // Check if auction has ended
    $end_date = get_post_meta( $auction_id, '_auction_end_date', true );
    
    if ( $end_date && strtotime( $end_date ) < current_time( 'timestamp' ) ) {
        wp_send_json_error( array( 'message' => __( 'This auction has ended.', 'aqualuxe' ) ) );
    }
    
    // Check if auction has started
    $start_date = get_post_meta( $auction_id, '_auction_start_date', true );
    
    if ( $start_date && strtotime( $start_date ) > current_time( 'timestamp' ) ) {
        wp_send_json_error( array( 'message' => __( 'This auction has not started yet.', 'aqualuxe' ) ) );
    }
    
    // Get current bid and minimum increment
    $current_bid = get_post_meta( $auction_id, '_auction_current_bid', true );
    $start_price = get_post_meta( $auction_id, '_auction_start_price', true );
    $min_increment = get_post_meta( $auction_id, '_auction_min_bid_increment', true );
    
    if ( ! $current_bid ) {
        $current_bid = $start_price;
    }
    
    // Check if bid is high enough
    $min_bid = $current_bid + $min_increment;
    
    if ( $bid_amount < $min_bid ) {
        wp_send_json_error( array( 
            'message' => sprintf( __( 'Your bid must be at least %s.', 'aqualuxe' ), '$' . number_format( $min_bid, 2 ) )
        ) );
    }
    
    // Get bidder information
    $user_id = get_current_user_id();
    $name = isset( $_POST['name'] ) ? sanitize_text_field( $_POST['name'] ) : '';
    $email = isset( $_POST['email'] ) ? sanitize_email( $_POST['email'] ) : '';
    
    if ( ! $user_id && ( empty( $name ) || empty( $email ) ) ) {
        wp_send_json_error( array( 'message' => __( 'Please provide your name and email.', 'aqualuxe' ) ) );
    }
    
    // Create bid data
    $bid_data = array(
        'user_id' => $user_id,
        'name'    => $name,
        'email'   => $email,
        'amount'  => $bid_amount,
        'date'    => current_time( 'mysql' ),
        'ip'      => aqualuxe_get_client_ip(),
    );
    
    // Get existing bids
    $bids = get_post_meta( $auction_id, '_auction_bids', true );
    
    if ( ! is_array( $bids ) ) {
        $bids = array();
    }
    
    // Add new bid
    $bids[] = $bid_data;
    
    // Update bids and current bid
    update_post_meta( $auction_id, '_auction_bids', $bids );
    update_post_meta( $auction_id, '_auction_current_bid', $bid_amount );
    
    // If user is logged in, store as their bid
    if ( $user_id ) {
        $user_bids = get_user_meta( $user_id, '_user_auction_bids', true );
        
        if ( ! is_array( $user_bids ) ) {
            $user_bids = array();
        }
        
        if ( ! isset( $user_bids[ $auction_id ] ) ) {
            $user_bids[ $auction_id ] = array();
        }
        
        $user_bids[ $auction_id ][] = array(
            'amount' => $bid_amount,
            'date'   => current_time( 'mysql' ),
        );
        
        update_user_meta( $user_id, '_user_auction_bids', $user_bids );
    }
    
    // Send bid notification to admin
    aqualuxe_send_bid_notification( $auction_id, $bid_data );
    
    // Send success response
    wp_send_json_success( array(
        'message'     => __( 'Your bid has been placed successfully!', 'aqualuxe' ),
        'current_bid' => $bid_amount,
        'formatted'   => '$' . number_format( $bid_amount, 2 ),
        'next_bid'    => $bid_amount + $min_increment,
        'next_formatted' => '$' . number_format( $bid_amount + $min_increment, 2 ),
    ) );
}
add_action( 'wp_ajax_aqualuxe_place_bid', 'aqualuxe_ajax_place_bid' );
add_action( 'wp_ajax_nopriv_aqualuxe_place_bid', 'aqualuxe_ajax_place_bid' );

/**
 * Get client IP address
 * 
 * @return string Client IP address
 */
function aqualuxe_get_client_ip() {
    $ip_keys = array(
        'HTTP_CLIENT_IP',
        'HTTP_X_FORWARDED_FOR',
        'HTTP_X_FORWARDED',
        'HTTP_X_CLUSTER_CLIENT_IP',
        'HTTP_FORWARDED_FOR',
        'HTTP_FORWARDED',
        'REMOTE_ADDR',
    );
    
    foreach ( $ip_keys as $key ) {
        if ( isset( $_SERVER[ $key ] ) && filter_var( $_SERVER[ $key ], FILTER_VALIDATE_IP ) ) {
            return $_SERVER[ $key ];
        }
    }
    
    return '127.0.0.1';
}

/**
 * Send bid notification to admin
 * 
 * @param int   $auction_id Auction post ID
 * @param array $bid_data   Bid data
 */
function aqualuxe_send_bid_notification( $auction_id, $bid_data ) {
    $auction_title = get_the_title( $auction_id );
    $admin_email = get_option( 'admin_email' );
    $subject = sprintf( __( 'New Bid: %s', 'aqualuxe' ), $auction_title );
    
    $bidder_info = '';
    if ( ! empty( $bid_data['user_id'] ) ) {
        $user = get_user_by( 'id', $bid_data['user_id'] );
        if ( $user ) {
            $bidder_info = $user->display_name . ' (' . $user->user_email . ')';
        }
    } else {
        $bidder_info = $bid_data['name'] . ' (' . $bid_data['email'] . ')';
    }
    
    $message = sprintf(
        __( 'A new bid has been placed on "%s".', 'aqualuxe' ),
        $auction_title
    ) . "\n\n";
    
    $message .= __( 'Bid Details:', 'aqualuxe' ) . "\n";
    $message .= sprintf( __( 'Bidder: %s', 'aqualuxe' ), $bidder_info ) . "\n";
    $message .= sprintf( __( 'Amount: %s', 'aqualuxe' ), '$' . number_format( $bid_data['amount'], 2 ) ) . "\n";
    $message .= sprintf( __( 'Date: %s', 'aqualuxe' ), date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), strtotime( $bid_data['date'] ) ) ) . "\n";
    $message .= sprintf( __( 'IP Address: %s', 'aqualuxe' ), $bid_data['ip'] ) . "\n\n";
    
    $message .= sprintf(
        __( 'View Auction: %s', 'aqualuxe' ),
        admin_url( 'post.php?post=' . $auction_id . '&action=edit' )
    );
    
    $headers = array( 'Content-Type: text/plain; charset=UTF-8' );
    
    wp_mail( $admin_email, $subject, $message, $headers );
}

/**
 * Schedule auction status check
 */
function aqualuxe_schedule_auction_status_check() {
    if ( ! wp_next_scheduled( 'aqualuxe_check_auction_status' ) ) {
        wp_schedule_event( time(), 'hourly', 'aqualuxe_check_auction_status' );
    }
}
add_action( 'wp', 'aqualuxe_schedule_auction_status_check' );

/**
 * Check auction status and update accordingly
 */
function aqualuxe_check_auction_status() {
    $current_time = current_time( 'timestamp' );
    
    // Get upcoming auctions that should be active
    $upcoming_args = array(
        'post_type'      => 'aqualuxe_auction',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
        'tax_query'      => array(
            array(
                'taxonomy' => 'auction_status',
                'field'    => 'slug',
                'terms'    => 'upcoming',
            ),
        ),
        'meta_query'     => array(
            array(
                'key'     => '_auction_start_date',
                'value'   => date( 'Y-m-d H:i:s', $current_time ),
                'compare' => '<=',
                'type'    => 'DATETIME',
            ),
        ),
    );
    
    $upcoming_query = new WP_Query( $upcoming_args );
    
    if ( $upcoming_query->have_posts() ) {
        while ( $upcoming_query->have_posts() ) {
            $upcoming_query->the_post();
            
            // Set status to active
            wp_set_object_terms( get_the_ID(), 'active', 'auction_status' );
        }
        
        wp_reset_postdata();
    }
    
    // Get active auctions that should be ended
    $active_args = array(
        'post_type'      => 'aqualuxe_auction',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
        'tax_query'      => array(
            array(
                'taxonomy' => 'auction_status',
                'field'    => 'slug',
                'terms'    => 'active',
            ),
        ),
        'meta_query'     => array(
            array(
                'key'     => '_auction_end_date',
                'value'   => date( 'Y-m-d H:i:s', $current_time ),
                'compare' => '<=',
                'type'    => 'DATETIME',
            ),
        ),
    );
    
    $active_query = new WP_Query( $active_args );
    
    if ( $active_query->have_posts() ) {
        while ( $active_query->have_posts() ) {
            $active_query->the_post();
            $auction_id = get_the_ID();
            
            // Set status to ended
            wp_set_object_terms( $auction_id, 'ended', 'auction_status' );
            
            // Determine winner
            aqualuxe_determine_auction_winner( $auction_id );
        }
        
        wp_reset_postdata();
    }
}
add_action( 'aqualuxe_check_auction_status', 'aqualuxe_check_auction_status' );

/**
 * Determine auction winner
 * 
 * @param int $auction_id Auction post ID
 */
function aqualuxe_determine_auction_winner( $auction_id ) {
    // Get bids
    $bids = get_post_meta( $auction_id, '_auction_bids', true );
    
    if ( ! is_array( $bids ) || empty( $bids ) ) {
        return;
    }
    
    // Sort bids by amount (highest first)
    usort( $bids, function( $a, $b ) {
        return $b['amount'] - $a['amount'];
    } );
    
    // Get highest bid
    $highest_bid = $bids[0];
    
    // Check if reserve price is met
    $reserve_price = get_post_meta( $auction_id, '_auction_reserve_price', true );
    
    if ( $reserve_price && $highest_bid['amount'] < $reserve_price ) {
        // Reserve price not met
        return;
    }
    
    // Set winner
    if ( ! empty( $highest_bid['user_id'] ) ) {
        update_post_meta( $auction_id, '_auction_winner_id', $highest_bid['user_id'] );
    } else {
        update_post_meta( $auction_id, '_auction_winner_name', $highest_bid['name'] );
        update_post_meta( $auction_id, '_auction_winner_email', $highest_bid['email'] );
    }
    
    update_post_meta( $auction_id, '_auction_winning_bid', $highest_bid['amount'] );
    update_post_meta( $auction_id, '_auction_payment_status', 'pending' );
    
    // Notify winner
    aqualuxe_notify_auction_winner( $auction_id, $highest_bid );
}

/**
 * Notify auction winner
 * 
 * @param int   $auction_id  Auction post ID
 * @param array $winner_data Winner data
 */
function aqualuxe_notify_auction_winner( $auction_id, $winner_data ) {
    $auction_title = get_the_title( $auction_id );
    $auction_url = get_permalink( $auction_id );
    
    // Get winner email
    $winner_email = '';
    if ( ! empty( $winner_data['user_id'] ) ) {
        $user = get_user_by( 'id', $winner_data['user_id'] );
        if ( $user ) {
            $winner_email = $user->user_email;
        }
    } else {
        $winner_email = $winner_data['email'];
    }
    
    if ( empty( $winner_email ) ) {
        return;
    }
    
    $subject = sprintf( __( 'Congratulations! You won the auction for "%s"', 'aqualuxe' ), $auction_title );
    
    $message = sprintf(
        __( 'Congratulations! You are the winning bidder for "%s".', 'aqualuxe' ),
        $auction_title
    ) . "\n\n";
    
    $message .= __( 'Auction Details:', 'aqualuxe' ) . "\n";
    $message .= sprintf( __( 'Item: %s', 'aqualuxe' ), $auction_title ) . "\n";
    $message .= sprintf( __( 'Your Winning Bid: %s', 'aqualuxe' ), '$' . number_format( $winner_data['amount'], 2 ) ) . "\n\n";
    
    $message .= __( 'Next Steps:', 'aqualuxe' ) . "\n";
    $message .= __( '1. Please complete the payment within 48 hours.', 'aqualuxe' ) . "\n";
    $message .= __( '2. Once payment is received, we will contact you regarding shipping or pickup arrangements.', 'aqualuxe' ) . "\n\n";
    
    $message .= sprintf(
        __( 'View Auction: %s', 'aqualuxe' ),
        $auction_url
    ) . "\n\n";
    
    $message .= __( 'Thank you for participating in our auction!', 'aqualuxe' ) . "\n\n";
    
    $message .= sprintf(
        __( 'Best regards,', 'aqualuxe' ) . "\n%s",
        get_bloginfo( 'name' )
    );
    
    $headers = array( 'Content-Type: text/plain; charset=UTF-8' );
    
    wp_mail( $winner_email, $subject, $message, $headers );
}

/**
 * Add auction system scripts and styles
 */
function aqualuxe_enqueue_auction_scripts() {
    // Only enqueue on auction pages
    if ( is_singular( 'aqualuxe_auction' ) || is_post_type_archive( 'aqualuxe_auction' ) || is_tax( 'auction_category' ) ) {
        // Enqueue datetimepicker for admin
        if ( is_admin() ) {
            wp_enqueue_style(
                'jquery-datetimepicker',
                'https://cdn.jsdelivr.net/npm/jquery-datetimepicker@2.5.20/build/jquery.datetimepicker.min.css',
                array(),
                '2.5.20'
            );
            
            wp_enqueue_script(
                'jquery-datetimepicker',
                'https://cdn.jsdelivr.net/npm/jquery-datetimepicker@2.5.20/build/jquery.datetimepicker.full.min.js',
                array( 'jquery' ),
                '2.5.20',
                true
            );
        }
        
        // Enqueue countdown script for frontend
        wp_enqueue_script(
            'jquery-countdown',
            'https://cdn.jsdelivr.net/npm/jquery.countdown@2.2.0/dist/jquery.countdown.min.js',
            array( 'jquery' ),
            '2.2.0',
            true
        );
        
        // Enqueue auction script
        wp_enqueue_script(
            'aqualuxe-auction',
            AQUALUXE_URI . 'assets/js/auction.js',
            array( 'jquery', 'jquery-countdown' ),
            AQUALUXE_VERSION,
            true
        );
        
        // Localize script with settings
        wp_localize_script(
            'aqualuxe-auction',
            'aqualuxeAuctionSettings',
            array(
                'ajaxUrl' => admin_url( 'admin-ajax.php' ),
                'nonce'   => wp_create_nonce( 'aqualuxe_ajax_nonce' ),
                'i18n'    => array(
                    'days'    => __( 'days', 'aqualuxe' ),
                    'hours'   => __( 'hours', 'aqualuxe' ),
                    'minutes' => __( 'minutes', 'aqualuxe' ),
                    'seconds' => __( 'seconds', 'aqualuxe' ),
                ),
            )
        );
    }
}
add_action( 'wp_enqueue_scripts', 'aqualuxe_enqueue_auction_scripts' );