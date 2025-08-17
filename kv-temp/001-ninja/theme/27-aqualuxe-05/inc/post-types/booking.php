<?php
/**
 * AquaLuxe Booking System
 *
 * Handles booking functionality including custom post type, meta boxes, and AJAX handlers.
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Register Booking custom post type
 */
function aqualuxe_register_booking_post_type() {
    $labels = array(
        'name'                  => _x( 'Bookings', 'Post type general name', 'aqualuxe' ),
        'singular_name'         => _x( 'Booking', 'Post type singular name', 'aqualuxe' ),
        'menu_name'             => _x( 'Bookings', 'Admin Menu text', 'aqualuxe' ),
        'name_admin_bar'        => _x( 'Booking', 'Add New on Toolbar', 'aqualuxe' ),
        'add_new'               => __( 'Add New', 'aqualuxe' ),
        'add_new_item'          => __( 'Add New Booking', 'aqualuxe' ),
        'new_item'              => __( 'New Booking', 'aqualuxe' ),
        'edit_item'             => __( 'Edit Booking', 'aqualuxe' ),
        'view_item'             => __( 'View Booking', 'aqualuxe' ),
        'all_items'             => __( 'All Bookings', 'aqualuxe' ),
        'search_items'          => __( 'Search Bookings', 'aqualuxe' ),
        'not_found'             => __( 'No bookings found.', 'aqualuxe' ),
        'not_found_in_trash'    => __( 'No bookings found in Trash.', 'aqualuxe' ),
        'featured_image'        => _x( 'Booking Image', 'Overrides the "Featured Image" phrase', 'aqualuxe' ),
        'archives'              => _x( 'Booking Archives', 'The post type archive label used in nav menus', 'aqualuxe' ),
        'filter_items_list'     => _x( 'Filter bookings list', 'Screen reader text for the filter links', 'aqualuxe' ),
        'items_list_navigation' => _x( 'Bookings list navigation', 'Screen reader text for the pagination', 'aqualuxe' ),
        'items_list'            => _x( 'Bookings list', 'Screen reader text for the items list', 'aqualuxe' ),
    );

    $args = array(
        'labels'             => $labels,
        'public'             => false,
        'publicly_queryable' => false,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'booking' ),
        'capability_type'    => 'post',
        'has_archive'        => false,
        'hierarchical'       => false,
        'menu_position'      => 20,
        'menu_icon'          => 'dashicons-calendar-alt',
        'supports'           => array( 'title' ),
    );

    register_post_type( 'aqualuxe_booking', $args );
}
add_action( 'init', 'aqualuxe_register_booking_post_type' );

/**
 * Register Booking Status taxonomy
 */
function aqualuxe_register_booking_status_taxonomy() {
    $labels = array(
        'name'              => _x( 'Booking Statuses', 'taxonomy general name', 'aqualuxe' ),
        'singular_name'     => _x( 'Booking Status', 'taxonomy singular name', 'aqualuxe' ),
        'search_items'      => __( 'Search Booking Statuses', 'aqualuxe' ),
        'all_items'         => __( 'All Booking Statuses', 'aqualuxe' ),
        'parent_item'       => __( 'Parent Booking Status', 'aqualuxe' ),
        'parent_item_colon' => __( 'Parent Booking Status:', 'aqualuxe' ),
        'edit_item'         => __( 'Edit Booking Status', 'aqualuxe' ),
        'update_item'       => __( 'Update Booking Status', 'aqualuxe' ),
        'add_new_item'      => __( 'Add New Booking Status', 'aqualuxe' ),
        'new_item_name'     => __( 'New Booking Status Name', 'aqualuxe' ),
        'menu_name'         => __( 'Booking Status', 'aqualuxe' ),
    );

    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'booking-status' ),
    );

    register_taxonomy( 'booking_status', array( 'aqualuxe_booking' ), $args );
    
    // Register default booking statuses
    if ( ! term_exists( 'pending', 'booking_status' ) ) {
        wp_insert_term( 'Pending', 'booking_status', array( 'slug' => 'pending' ) );
    }
    
    if ( ! term_exists( 'confirmed', 'booking_status' ) ) {
        wp_insert_term( 'Confirmed', 'booking_status', array( 'slug' => 'confirmed' ) );
    }
    
    if ( ! term_exists( 'completed', 'booking_status' ) ) {
        wp_insert_term( 'Completed', 'booking_status', array( 'slug' => 'completed' ) );
    }
    
    if ( ! term_exists( 'cancelled', 'booking_status' ) ) {
        wp_insert_term( 'Cancelled', 'booking_status', array( 'slug' => 'cancelled' ) );
    }
}
add_action( 'init', 'aqualuxe_register_booking_status_taxonomy' );

/**
 * Register meta boxes for the booking post type
 */
function aqualuxe_register_booking_meta_boxes() {
    add_meta_box(
        'aqualuxe_booking_details',
        __( 'Booking Details', 'aqualuxe' ),
        'aqualuxe_booking_details_meta_box_callback',
        'aqualuxe_booking',
        'normal',
        'high'
    );
    
    add_meta_box(
        'aqualuxe_booking_customer',
        __( 'Customer Information', 'aqualuxe' ),
        'aqualuxe_booking_customer_meta_box_callback',
        'aqualuxe_booking',
        'normal',
        'default'
    );
    
    add_meta_box(
        'aqualuxe_booking_notes',
        __( 'Booking Notes', 'aqualuxe' ),
        'aqualuxe_booking_notes_meta_box_callback',
        'aqualuxe_booking',
        'side',
        'default'
    );
}
add_action( 'add_meta_boxes', 'aqualuxe_register_booking_meta_boxes' );

/**
 * Booking Details meta box callback
 */
function aqualuxe_booking_details_meta_box_callback( $post ) {
    // Add nonce for security
    wp_nonce_field( 'aqualuxe_booking_details_nonce', 'booking_details_nonce' );
    
    // Get saved values
    $service = get_post_meta( $post->ID, '_booking_service', true );
    $date = get_post_meta( $post->ID, '_booking_date', true );
    $time = get_post_meta( $post->ID, '_booking_time', true );
    $duration = get_post_meta( $post->ID, '_booking_duration', true );
    $price = get_post_meta( $post->ID, '_booking_price', true );
    
    // Get available services
    $services = aqualuxe_get_booking_services();
    ?>
    <div class="booking-details-meta-box">
        <style>
            .booking-details-meta-box .form-field {
                margin-bottom: 15px;
            }
            .booking-details-meta-box label {
                display: block;
                font-weight: 600;
                margin-bottom: 5px;
            }
            .booking-details-meta-box input[type="text"],
            .booking-details-meta-box select {
                width: 100%;
                max-width: 400px;
            }
            .booking-details-meta-box .form-row {
                display: flex;
                flex-wrap: wrap;
                margin: 0 -10px;
            }
            .booking-details-meta-box .form-col {
                flex: 1;
                padding: 0 10px;
                min-width: 200px;
            }
        </style>
        
        <div class="form-row">
            <div class="form-col">
                <div class="form-field">
                    <label for="booking_service"><?php esc_html_e( 'Service', 'aqualuxe' ); ?></label>
                    <select id="booking_service" name="booking_service">
                        <option value=""><?php esc_html_e( 'Select a service', 'aqualuxe' ); ?></option>
                        <?php foreach ( $services as $service_id => $service_data ) : ?>
                            <option value="<?php echo esc_attr( $service_id ); ?>" <?php selected( $service, $service_id ); ?> data-duration="<?php echo esc_attr( $service_data['duration'] ); ?>" data-price="<?php echo esc_attr( $service_data['price'] ); ?>">
                                <?php echo esc_html( $service_data['name'] ); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-field">
                    <label for="booking_date"><?php esc_html_e( 'Date', 'aqualuxe' ); ?></label>
                    <input type="text" id="booking_date" name="booking_date" value="<?php echo esc_attr( $date ); ?>" class="datepicker" />
                </div>
                
                <div class="form-field">
                    <label for="booking_time"><?php esc_html_e( 'Time', 'aqualuxe' ); ?></label>
                    <input type="text" id="booking_time" name="booking_time" value="<?php echo esc_attr( $time ); ?>" />
                </div>
            </div>
            
            <div class="form-col">
                <div class="form-field">
                    <label for="booking_duration"><?php esc_html_e( 'Duration', 'aqualuxe' ); ?></label>
                    <input type="text" id="booking_duration" name="booking_duration" value="<?php echo esc_attr( $duration ); ?>" />
                </div>
                
                <div class="form-field">
                    <label for="booking_price"><?php esc_html_e( 'Price', 'aqualuxe' ); ?></label>
                    <input type="text" id="booking_price" name="booking_price" value="<?php echo esc_attr( $price ); ?>" />
                </div>
                
                <div class="form-field">
                    <label for="booking_status"><?php esc_html_e( 'Status', 'aqualuxe' ); ?></label>
                    <?php
                    $terms = get_terms( array(
                        'taxonomy'   => 'booking_status',
                        'hide_empty' => false,
                    ) );
                    
                    if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
                        echo '<select id="booking_status" name="booking_status">';
                        foreach ( $terms as $term ) {
                            $selected = has_term( $term->term_id, 'booking_status', $post->ID ) ? 'selected' : '';
                            echo '<option value="' . esc_attr( $term->term_id ) . '" ' . $selected . '>' . esc_html( $term->name ) . '</option>';
                        }
                        echo '</select>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    
    <script>
    jQuery(document).ready(function($) {
        // Initialize datepicker
        if ($.fn.datepicker) {
            $('#booking_date').datepicker({
                dateFormat: 'yy-mm-dd',
                changeMonth: true,
                changeYear: true
            });
        }
        
        // Update duration and price when service changes
        $('#booking_service').on('change', function() {
            var $selected = $(this).find('option:selected');
            $('#booking_duration').val($selected.data('duration'));
            $('#booking_price').val($selected.data('price'));
        });
    });
    </script>
    <?php
}

/**
 * Customer Information meta box callback
 */
function aqualuxe_booking_customer_meta_box_callback( $post ) {
    // Add nonce for security
    wp_nonce_field( 'aqualuxe_booking_customer_nonce', 'booking_customer_nonce' );
    
    // Get saved values
    $name = get_post_meta( $post->ID, '_booking_name', true );
    $email = get_post_meta( $post->ID, '_booking_email', true );
    $phone = get_post_meta( $post->ID, '_booking_phone', true );
    $message = get_post_meta( $post->ID, '_booking_message', true );
    ?>
    <div class="booking-customer-meta-box">
        <style>
            .booking-customer-meta-box .form-field {
                margin-bottom: 15px;
            }
            .booking-customer-meta-box label {
                display: block;
                font-weight: 600;
                margin-bottom: 5px;
            }
            .booking-customer-meta-box input[type="text"],
            .booking-customer-meta-box input[type="email"],
            .booking-customer-meta-box input[type="tel"],
            .booking-customer-meta-box textarea {
                width: 100%;
                max-width: 400px;
            }
            .booking-customer-meta-box .form-row {
                display: flex;
                flex-wrap: wrap;
                margin: 0 -10px;
            }
            .booking-customer-meta-box .form-col {
                flex: 1;
                padding: 0 10px;
                min-width: 200px;
            }
        </style>
        
        <div class="form-row">
            <div class="form-col">
                <div class="form-field">
                    <label for="booking_name"><?php esc_html_e( 'Full Name', 'aqualuxe' ); ?></label>
                    <input type="text" id="booking_name" name="booking_name" value="<?php echo esc_attr( $name ); ?>" />
                </div>
                
                <div class="form-field">
                    <label for="booking_email"><?php esc_html_e( 'Email Address', 'aqualuxe' ); ?></label>
                    <input type="email" id="booking_email" name="booking_email" value="<?php echo esc_attr( $email ); ?>" />
                </div>
                
                <div class="form-field">
                    <label for="booking_phone"><?php esc_html_e( 'Phone Number', 'aqualuxe' ); ?></label>
                    <input type="tel" id="booking_phone" name="booking_phone" value="<?php echo esc_attr( $phone ); ?>" />
                </div>
            </div>
            
            <div class="form-col">
                <div class="form-field">
                    <label for="booking_message"><?php esc_html_e( 'Message', 'aqualuxe' ); ?></label>
                    <textarea id="booking_message" name="booking_message" rows="5"><?php echo esc_textarea( $message ); ?></textarea>
                </div>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Booking Notes meta box callback
 */
function aqualuxe_booking_notes_meta_box_callback( $post ) {
    // Add nonce for security
    wp_nonce_field( 'aqualuxe_booking_notes_nonce', 'booking_notes_nonce' );
    
    // Get saved value
    $notes = get_post_meta( $post->ID, '_booking_admin_notes', true );
    ?>
    <div class="booking-notes-meta-box">
        <p>
            <label for="booking_admin_notes"><?php esc_html_e( 'Admin Notes', 'aqualuxe' ); ?></label>
            <textarea id="booking_admin_notes" name="booking_admin_notes" rows="5" style="width: 100%;"><?php echo esc_textarea( $notes ); ?></textarea>
        </p>
        <p class="description">
            <?php esc_html_e( 'These notes are only visible to administrators.', 'aqualuxe' ); ?>
        </p>
    </div>
    <?php
}

/**
 * Save booking meta box data
 */
function aqualuxe_save_booking_meta_box_data( $post_id ) {
    // Check if our nonces are set and verify them
    if ( ! isset( $_POST['booking_details_nonce'] ) || ! wp_verify_nonce( $_POST['booking_details_nonce'], 'aqualuxe_booking_details_nonce' ) ) {
        return;
    }
    
    if ( ! isset( $_POST['booking_customer_nonce'] ) || ! wp_verify_nonce( $_POST['booking_customer_nonce'], 'aqualuxe_booking_customer_nonce' ) ) {
        return;
    }
    
    if ( ! isset( $_POST['booking_notes_nonce'] ) || ! wp_verify_nonce( $_POST['booking_notes_nonce'], 'aqualuxe_booking_notes_nonce' ) ) {
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
    
    // Booking details
    if ( isset( $_POST['booking_service'] ) ) {
        update_post_meta( $post_id, '_booking_service', sanitize_text_field( $_POST['booking_service'] ) );
    }
    
    if ( isset( $_POST['booking_date'] ) ) {
        update_post_meta( $post_id, '_booking_date', sanitize_text_field( $_POST['booking_date'] ) );
    }
    
    if ( isset( $_POST['booking_time'] ) ) {
        update_post_meta( $post_id, '_booking_time', sanitize_text_field( $_POST['booking_time'] ) );
    }
    
    if ( isset( $_POST['booking_duration'] ) ) {
        update_post_meta( $post_id, '_booking_duration', sanitize_text_field( $_POST['booking_duration'] ) );
    }
    
    if ( isset( $_POST['booking_price'] ) ) {
        update_post_meta( $post_id, '_booking_price', sanitize_text_field( $_POST['booking_price'] ) );
    }
    
    // Customer information
    if ( isset( $_POST['booking_name'] ) ) {
        update_post_meta( $post_id, '_booking_name', sanitize_text_field( $_POST['booking_name'] ) );
    }
    
    if ( isset( $_POST['booking_email'] ) ) {
        update_post_meta( $post_id, '_booking_email', sanitize_email( $_POST['booking_email'] ) );
    }
    
    if ( isset( $_POST['booking_phone'] ) ) {
        update_post_meta( $post_id, '_booking_phone', sanitize_text_field( $_POST['booking_phone'] ) );
    }
    
    if ( isset( $_POST['booking_message'] ) ) {
        update_post_meta( $post_id, '_booking_message', sanitize_textarea_field( $_POST['booking_message'] ) );
    }
    
    // Admin notes
    if ( isset( $_POST['booking_admin_notes'] ) ) {
        update_post_meta( $post_id, '_booking_admin_notes', sanitize_textarea_field( $_POST['booking_admin_notes'] ) );
    }
    
    // Update booking status
    if ( isset( $_POST['booking_status'] ) ) {
        wp_set_object_terms( $post_id, intval( $_POST['booking_status'] ), 'booking_status' );
    }
}
add_action( 'save_post_aqualuxe_booking', 'aqualuxe_save_booking_meta_box_data' );

/**
 * Add custom columns to the booking list table
 */
function aqualuxe_booking_columns( $columns ) {
    $new_columns = array();
    
    // Add checkbox and title at the beginning
    if ( isset( $columns['cb'] ) ) {
        $new_columns['cb'] = $columns['cb'];
    }
    
    if ( isset( $columns['title'] ) ) {
        $new_columns['title'] = __( 'Booking ID', 'aqualuxe' );
    }
    
    // Add custom columns
    $new_columns['customer'] = __( 'Customer', 'aqualuxe' );
    $new_columns['service'] = __( 'Service', 'aqualuxe' );
    $new_columns['date_time'] = __( 'Date & Time', 'aqualuxe' );
    $new_columns['status'] = __( 'Status', 'aqualuxe' );
    
    // Add date at the end
    if ( isset( $columns['date'] ) ) {
        $new_columns['date'] = $columns['date'];
    }
    
    return $new_columns;
}
add_filter( 'manage_aqualuxe_booking_posts_columns', 'aqualuxe_booking_columns' );

/**
 * Display data in custom columns for the booking list table
 */
function aqualuxe_booking_custom_column( $column, $post_id ) {
    switch ( $column ) {
        case 'customer':
            $name = get_post_meta( $post_id, '_booking_name', true );
            $email = get_post_meta( $post_id, '_booking_email', true );
            
            if ( $name ) {
                echo esc_html( $name );
                
                if ( $email ) {
                    echo '<br><a href="mailto:' . esc_attr( $email ) . '">' . esc_html( $email ) . '</a>';
                }
            } else {
                echo '—';
            }
            break;
            
        case 'service':
            $service_id = get_post_meta( $post_id, '_booking_service', true );
            $services = aqualuxe_get_booking_services();
            
            if ( isset( $services[ $service_id ] ) ) {
                echo esc_html( $services[ $service_id ]['name'] );
            } else {
                echo '—';
            }
            break;
            
        case 'date_time':
            $date = get_post_meta( $post_id, '_booking_date', true );
            $time = get_post_meta( $post_id, '_booking_time', true );
            
            if ( $date ) {
                // Format date
                $formatted_date = date_i18n( get_option( 'date_format' ), strtotime( $date ) );
                echo esc_html( $formatted_date );
                
                if ( $time ) {
                    echo '<br>' . esc_html( $time );
                }
            } else {
                echo '—';
            }
            break;
            
        case 'status':
            $terms = get_the_terms( $post_id, 'booking_status' );
            
            if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
                $status = $terms[0]->name;
                $slug = $terms[0]->slug;
                
                $status_colors = array(
                    'pending'   => '#f0ad4e',
                    'confirmed' => '#5cb85c',
                    'completed' => '#5bc0de',
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
add_action( 'manage_aqualuxe_booking_posts_custom_column', 'aqualuxe_booking_custom_column', 10, 2 );

/**
 * Make custom columns sortable
 */
function aqualuxe_booking_sortable_columns( $columns ) {
    $columns['date_time'] = 'date_time';
    $columns['status'] = 'status';
    return $columns;
}
add_filter( 'manage_edit-aqualuxe_booking_sortable_columns', 'aqualuxe_booking_sortable_columns' );

/**
 * Add sorting functionality to custom columns
 */
function aqualuxe_booking_sort_columns( $query ) {
    if ( ! is_admin() || ! $query->is_main_query() ) {
        return;
    }
    
    if ( $query->get( 'post_type' ) !== 'aqualuxe_booking' ) {
        return;
    }
    
    $orderby = $query->get( 'orderby' );
    
    if ( 'date_time' === $orderby ) {
        $query->set( 'meta_key', '_booking_date' );
        $query->set( 'orderby', 'meta_value' );
    } elseif ( 'status' === $orderby ) {
        $query->set( 'orderby', 'tax_query' );
    }
}
add_action( 'pre_get_posts', 'aqualuxe_booking_sort_columns' );

/**
 * Add filter dropdown for booking status
 */
function aqualuxe_booking_status_filter() {
    global $typenow;
    
    if ( 'aqualuxe_booking' !== $typenow ) {
        return;
    }
    
    $current = isset( $_GET['booking_status'] ) ? $_GET['booking_status'] : '';
    $terms = get_terms( array(
        'taxonomy'   => 'booking_status',
        'hide_empty' => false,
    ) );
    
    if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
        echo '<select name="booking_status" id="booking_status" class="postform">';
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
add_action( 'restrict_manage_posts', 'aqualuxe_booking_status_filter' );

/**
 * Get booking services
 * 
 * @return array Array of booking services
 */
function aqualuxe_get_booking_services() {
    // Default services
    $default_services = array(
        'aquarium-consultation' => array(
            'name'     => __( 'Aquarium Setup Consultation', 'aqualuxe' ),
            'duration' => __( '60 minutes', 'aqualuxe' ),
            'price'    => '$99.00',
        ),
        'fish-health-check' => array(
            'name'     => __( 'Fish Health Check', 'aqualuxe' ),
            'duration' => __( '45 minutes', 'aqualuxe' ),
            'price'    => '$79.00',
        ),
        'water-quality-analysis' => array(
            'name'     => __( 'Water Quality Analysis', 'aqualuxe' ),
            'duration' => __( '30 minutes', 'aqualuxe' ),
            'price'    => '$59.00',
        ),
        'breeding-consultation' => array(
            'name'     => __( 'Breeding Consultation', 'aqualuxe' ),
            'duration' => __( '90 minutes', 'aqualuxe' ),
            'price'    => '$149.00',
        ),
        'commercial-setup' => array(
            'name'     => __( 'Commercial Setup Planning', 'aqualuxe' ),
            'duration' => __( '120 minutes', 'aqualuxe' ),
            'price'    => '$199.00',
        ),
    );
    
    // Allow filtering of services
    return apply_filters( 'aqualuxe_booking_services', $default_services );
}

/**
 * AJAX handler for getting booking data
 */
function aqualuxe_ajax_get_booking_data() {
    // Check nonce
    if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'aqualuxe_ajax_nonce' ) ) {
        wp_send_json_error( array( 'message' => __( 'Security check failed.', 'aqualuxe' ) ) );
    }
    
    // Get services
    $services = aqualuxe_get_booking_services();
    
    // Get booked slots
    $booked_slots = aqualuxe_get_booked_slots();
    
    // Send response
    wp_send_json_success( array(
        'services'    => $services,
        'bookedSlots' => $booked_slots,
    ) );
}
add_action( 'wp_ajax_aqualuxe_get_booking_data', 'aqualuxe_ajax_get_booking_data' );
add_action( 'wp_ajax_nopriv_aqualuxe_get_booking_data', 'aqualuxe_ajax_get_booking_data' );

/**
 * Get booked slots
 * 
 * @return array Array of booked slots by date
 */
function aqualuxe_get_booked_slots() {
    $booked_slots = array();
    
    // Query bookings
    $args = array(
        'post_type'      => 'aqualuxe_booking',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
        'tax_query'      => array(
            array(
                'taxonomy' => 'booking_status',
                'field'    => 'slug',
                'terms'    => array( 'pending', 'confirmed' ),
            ),
        ),
        'meta_query'     => array(
            array(
                'key'     => '_booking_date',
                'value'   => date( 'Y-m-d' ),
                'compare' => '>=',
                'type'    => 'DATE',
            ),
        ),
    );
    
    $query = new WP_Query( $args );
    
    if ( $query->have_posts() ) {
        while ( $query->have_posts() ) {
            $query->the_post();
            
            $date = get_post_meta( get_the_ID(), '_booking_date', true );
            $time = get_post_meta( get_the_ID(), '_booking_time', true );
            
            if ( $date && $time ) {
                if ( ! isset( $booked_slots[ $date ] ) ) {
                    $booked_slots[ $date ] = array();
                }
                
                $booked_slots[ $date ][] = $time;
            }
        }
        
        wp_reset_postdata();
    }
    
    return $booked_slots;
}

/**
 * AJAX handler for submitting booking
 */
function aqualuxe_ajax_submit_booking() {
    // Check nonce
    if ( ! isset( $_POST['booking_nonce'] ) || ! wp_verify_nonce( $_POST['booking_nonce'], 'aqualuxe_booking_nonce' ) ) {
        wp_send_json_error( array( 'message' => __( 'Security check failed.', 'aqualuxe' ) ) );
    }
    
    // Validate required fields
    $required_fields = array(
        'booking_name'    => __( 'Full Name', 'aqualuxe' ),
        'booking_email'   => __( 'Email Address', 'aqualuxe' ),
        'booking_phone'   => __( 'Phone Number', 'aqualuxe' ),
        'booking_service' => __( 'Service', 'aqualuxe' ),
        'booking_date'    => __( 'Date', 'aqualuxe' ),
        'booking_time'    => __( 'Time', 'aqualuxe' ),
    );
    
    foreach ( $required_fields as $field => $label ) {
        if ( empty( $_POST[ $field ] ) ) {
            wp_send_json_error( array( 'message' => sprintf( __( '%s is required.', 'aqualuxe' ), $label ) ) );
        }
    }
    
    // Validate email
    if ( ! is_email( $_POST['booking_email'] ) ) {
        wp_send_json_error( array( 'message' => __( 'Please enter a valid email address.', 'aqualuxe' ) ) );
    }
    
    // Check if the slot is already booked
    $date = sanitize_text_field( $_POST['booking_date'] );
    $time = sanitize_text_field( $_POST['booking_time'] );
    $booked_slots = aqualuxe_get_booked_slots();
    
    if ( isset( $booked_slots[ $date ] ) && in_array( $time, $booked_slots[ $date ] ) ) {
        wp_send_json_error( array( 'message' => __( 'This time slot is no longer available. Please select another time.', 'aqualuxe' ) ) );
    }
    
    // Get service details
    $service_id = sanitize_text_field( $_POST['booking_service'] );
    $services = aqualuxe_get_booking_services();
    
    if ( ! isset( $services[ $service_id ] ) ) {
        wp_send_json_error( array( 'message' => __( 'Invalid service selected.', 'aqualuxe' ) ) );
    }
    
    $service_name = $services[ $service_id ]['name'];
    $service_duration = $services[ $service_id ]['duration'];
    $service_price = $services[ $service_id ]['price'];
    
    // Create booking post
    $booking_data = array(
        'post_title'   => sprintf( __( 'Booking - %s', 'aqualuxe' ), sanitize_text_field( $_POST['booking_name'] ) ),
        'post_content' => '',
        'post_status'  => 'publish',
        'post_type'    => 'aqualuxe_booking',
    );
    
    $booking_id = wp_insert_post( $booking_data );
    
    if ( is_wp_error( $booking_id ) ) {
        wp_send_json_error( array( 'message' => __( 'Failed to create booking. Please try again.', 'aqualuxe' ) ) );
    }
    
    // Set booking status to pending
    wp_set_object_terms( $booking_id, 'pending', 'booking_status' );
    
    // Save booking details
    update_post_meta( $booking_id, '_booking_service', $service_id );
    update_post_meta( $booking_id, '_booking_date', $date );
    update_post_meta( $booking_id, '_booking_time', $time );
    update_post_meta( $booking_id, '_booking_duration', $service_duration );
    update_post_meta( $booking_id, '_booking_price', $service_price );
    
    // Save customer information
    update_post_meta( $booking_id, '_booking_name', sanitize_text_field( $_POST['booking_name'] ) );
    update_post_meta( $booking_id, '_booking_email', sanitize_email( $_POST['booking_email'] ) );
    update_post_meta( $booking_id, '_booking_phone', sanitize_text_field( $_POST['booking_phone'] ) );
    
    if ( ! empty( $_POST['booking_message'] ) ) {
        update_post_meta( $booking_id, '_booking_message', sanitize_textarea_field( $_POST['booking_message'] ) );
    }
    
    // Send confirmation email
    aqualuxe_send_booking_confirmation_email( $booking_id );
    
    // Send admin notification
    aqualuxe_send_booking_admin_notification( $booking_id );
    
    // Send success response
    wp_send_json_success( array(
        'message' => __( 'Your booking has been successfully submitted. We will contact you shortly to confirm your appointment.', 'aqualuxe' ),
        'booking' => array(
            'id'       => $booking_id,
            'service'  => $service_name,
            'date'     => date_i18n( get_option( 'date_format' ), strtotime( $date ) ),
            'time'     => $time,
            'duration' => $service_duration,
            'price'    => $service_price,
        ),
    ) );
}
add_action( 'wp_ajax_aqualuxe_submit_booking', 'aqualuxe_ajax_submit_booking' );
add_action( 'wp_ajax_nopriv_aqualuxe_submit_booking', 'aqualuxe_ajax_submit_booking' );

/**
 * Send booking confirmation email to customer
 * 
 * @param int $booking_id Booking post ID
 */
function aqualuxe_send_booking_confirmation_email( $booking_id ) {
    $name = get_post_meta( $booking_id, '_booking_name', true );
    $email = get_post_meta( $booking_id, '_booking_email', true );
    $service_id = get_post_meta( $booking_id, '_booking_service', true );
    $date = get_post_meta( $booking_id, '_booking_date', true );
    $time = get_post_meta( $booking_id, '_booking_time', true );
    $duration = get_post_meta( $booking_id, '_booking_duration', true );
    $price = get_post_meta( $booking_id, '_booking_price', true );
    
    $services = aqualuxe_get_booking_services();
    $service_name = isset( $services[ $service_id ] ) ? $services[ $service_id ]['name'] : '';
    
    $formatted_date = date_i18n( get_option( 'date_format' ), strtotime( $date ) );
    
    $subject = sprintf( __( 'Your Booking Confirmation - %s', 'aqualuxe' ), get_bloginfo( 'name' ) );
    
    $message = sprintf(
        __( 'Dear %s,', 'aqualuxe' ),
        $name
    ) . "\n\n";
    
    $message .= __( 'Thank you for booking a consultation with us. Your booking details are as follows:', 'aqualuxe' ) . "\n\n";
    
    $message .= sprintf( __( 'Booking ID: %s', 'aqualuxe' ), $booking_id ) . "\n";
    $message .= sprintf( __( 'Service: %s', 'aqualuxe' ), $service_name ) . "\n";
    $message .= sprintf( __( 'Date: %s', 'aqualuxe' ), $formatted_date ) . "\n";
    $message .= sprintf( __( 'Time: %s', 'aqualuxe' ), $time ) . "\n";
    $message .= sprintf( __( 'Duration: %s', 'aqualuxe' ), $duration ) . "\n";
    $message .= sprintf( __( 'Price: %s', 'aqualuxe' ), $price ) . "\n\n";
    
    $message .= __( 'We will contact you shortly to confirm your appointment. If you need to make any changes to your booking, please contact us as soon as possible.', 'aqualuxe' ) . "\n\n";
    
    $message .= __( 'Thank you for choosing our services.', 'aqualuxe' ) . "\n\n";
    
    $message .= sprintf(
        __( 'Best regards,', 'aqualuxe' ) . "\n%s",
        get_bloginfo( 'name' )
    );
    
    $headers = array( 'Content-Type: text/plain; charset=UTF-8' );
    
    wp_mail( $email, $subject, $message, $headers );
}

/**
 * Send booking notification email to admin
 * 
 * @param int $booking_id Booking post ID
 */
function aqualuxe_send_booking_admin_notification( $booking_id ) {
    $name = get_post_meta( $booking_id, '_booking_name', true );
    $email = get_post_meta( $booking_id, '_booking_email', true );
    $phone = get_post_meta( $booking_id, '_booking_phone', true );
    $service_id = get_post_meta( $booking_id, '_booking_service', true );
    $date = get_post_meta( $booking_id, '_booking_date', true );
    $time = get_post_meta( $booking_id, '_booking_time', true );
    $message = get_post_meta( $booking_id, '_booking_message', true );
    
    $services = aqualuxe_get_booking_services();
    $service_name = isset( $services[ $service_id ] ) ? $services[ $service_id ]['name'] : '';
    
    $formatted_date = date_i18n( get_option( 'date_format' ), strtotime( $date ) );
    
    $admin_email = get_option( 'admin_email' );
    $subject = sprintf( __( 'New Booking: %s', 'aqualuxe' ), $name );
    
    $message_content = __( 'A new booking has been submitted. Details are as follows:', 'aqualuxe' ) . "\n\n";
    
    $message_content .= sprintf( __( 'Booking ID: %s', 'aqualuxe' ), $booking_id ) . "\n\n";
    
    $message_content .= __( 'Customer Information:', 'aqualuxe' ) . "\n";
    $message_content .= sprintf( __( 'Name: %s', 'aqualuxe' ), $name ) . "\n";
    $message_content .= sprintf( __( 'Email: %s', 'aqualuxe' ), $email ) . "\n";
    $message_content .= sprintf( __( 'Phone: %s', 'aqualuxe' ), $phone ) . "\n\n";
    
    $message_content .= __( 'Booking Details:', 'aqualuxe' ) . "\n";
    $message_content .= sprintf( __( 'Service: %s', 'aqualuxe' ), $service_name ) . "\n";
    $message_content .= sprintf( __( 'Date: %s', 'aqualuxe' ), $formatted_date ) . "\n";
    $message_content .= sprintf( __( 'Time: %s', 'aqualuxe' ), $time ) . "\n\n";
    
    if ( ! empty( $message ) ) {
        $message_content .= __( 'Customer Message:', 'aqualuxe' ) . "\n";
        $message_content .= $message . "\n\n";
    }
    
    $message_content .= sprintf(
        __( 'To manage this booking, please visit: %s', 'aqualuxe' ),
        admin_url( 'post.php?post=' . $booking_id . '&action=edit' )
    );
    
    $headers = array( 'Content-Type: text/plain; charset=UTF-8' );
    
    wp_mail( $admin_email, $subject, $message_content, $headers );
}

/**
 * Add booking system scripts and styles
 */
function aqualuxe_enqueue_booking_scripts() {
    // Only enqueue on the booking page template
    if ( is_page_template( 'page-booking.php' ) ) {
        // Enqueue Flatpickr for date picker
        wp_enqueue_style(
            'flatpickr',
            'https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css',
            array(),
            '4.6.13'
        );
        
        wp_enqueue_script(
            'flatpickr',
            'https://cdn.jsdelivr.net/npm/flatpickr',
            array( 'jquery' ),
            '4.6.13',
            true
        );
        
        // Enqueue booking script
        wp_enqueue_script(
            'aqualuxe-booking',
            AQUALUXE_URI . 'assets/js/booking.js',
            array( 'jquery', 'flatpickr' ),
            AQUALUXE_VERSION,
            true
        );
        
        // Localize script with settings
        wp_localize_script(
            'aqualuxe-booking',
            'aqualuxeSettings',
            array(
                'ajaxUrl' => admin_url( 'admin-ajax.php' ),
                'nonce'   => wp_create_nonce( 'aqualuxe_ajax_nonce' ),
                'i18n'    => array(
                    'selectTime'       => __( 'Select a time', 'aqualuxe' ),
                    'noAvailableSlots' => __( 'No available slots', 'aqualuxe' ),
                    'bookingConfirmed' => __( 'Booking Confirmed', 'aqualuxe' ),
                    'bookingId'        => __( 'Booking ID', 'aqualuxe' ),
                    'service'          => __( 'Service', 'aqualuxe' ),
                    'date'             => __( 'Date', 'aqualuxe' ),
                    'time'             => __( 'Time', 'aqualuxe' ),
                    'duration'         => __( 'Duration', 'aqualuxe' ),
                    'price'            => __( 'Price', 'aqualuxe' ),
                    'bookingNote'      => __( 'A confirmation email has been sent to your email address.', 'aqualuxe' ),
                ),
            )
        );
    }
}
add_action( 'wp_enqueue_scripts', 'aqualuxe_enqueue_booking_scripts' );