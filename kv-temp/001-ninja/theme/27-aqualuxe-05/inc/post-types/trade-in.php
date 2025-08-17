<?php
/**
 * AquaLuxe Trade-In System
 *
 * Handles trade-in functionality for fish and equipment including custom post type,
 * meta boxes, trade-in request system, and AJAX handlers.
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Register Trade-In custom post type
 */
function aqualuxe_register_trade_in_post_type() {
    $labels = array(
        'name'                  => _x( 'Trade-Ins', 'Post type general name', 'aqualuxe' ),
        'singular_name'         => _x( 'Trade-In', 'Post type singular name', 'aqualuxe' ),
        'menu_name'             => _x( 'Trade-Ins', 'Admin Menu text', 'aqualuxe' ),
        'name_admin_bar'        => _x( 'Trade-In', 'Add New on Toolbar', 'aqualuxe' ),
        'add_new'               => __( 'Add New', 'aqualuxe' ),
        'add_new_item'          => __( 'Add New Trade-In', 'aqualuxe' ),
        'new_item'              => __( 'New Trade-In', 'aqualuxe' ),
        'edit_item'             => __( 'Edit Trade-In', 'aqualuxe' ),
        'view_item'             => __( 'View Trade-In', 'aqualuxe' ),
        'all_items'             => __( 'All Trade-Ins', 'aqualuxe' ),
        'search_items'          => __( 'Search Trade-Ins', 'aqualuxe' ),
        'not_found'             => __( 'No trade-ins found.', 'aqualuxe' ),
        'not_found_in_trash'    => __( 'No trade-ins found in Trash.', 'aqualuxe' ),
        'featured_image'        => _x( 'Trade-In Image', 'Overrides the "Featured Image" phrase', 'aqualuxe' ),
        'set_featured_image'    => _x( 'Set trade-in image', 'Overrides the "Set featured image" phrase', 'aqualuxe' ),
        'remove_featured_image' => _x( 'Remove trade-in image', 'Overrides the "Remove featured image" phrase', 'aqualuxe' ),
        'use_featured_image'    => _x( 'Use as trade-in image', 'Overrides the "Use as featured image" phrase', 'aqualuxe' ),
        'archives'              => _x( 'Trade-In Archives', 'The post type archive label used in nav menus', 'aqualuxe' ),
        'filter_items_list'     => _x( 'Filter trade-ins list', 'Screen reader text for the filter links', 'aqualuxe' ),
        'items_list_navigation' => _x( 'Trade-ins list navigation', 'Screen reader text for the pagination', 'aqualuxe' ),
        'items_list'            => _x( 'Trade-ins list', 'Screen reader text for the items list', 'aqualuxe' ),
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'trade-in' ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 22,
        'menu_icon'          => 'dashicons-update',
        'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
    );

    register_post_type( 'aqualuxe_trade_in', $args );
}
add_action( 'init', 'aqualuxe_register_trade_in_post_type' );

/**
 * Register Trade-In Category taxonomy
 */
function aqualuxe_register_trade_in_category_taxonomy() {
    $labels = array(
        'name'              => _x( 'Trade-In Categories', 'taxonomy general name', 'aqualuxe' ),
        'singular_name'     => _x( 'Trade-In Category', 'taxonomy singular name', 'aqualuxe' ),
        'search_items'      => __( 'Search Trade-In Categories', 'aqualuxe' ),
        'all_items'         => __( 'All Trade-In Categories', 'aqualuxe' ),
        'parent_item'       => __( 'Parent Trade-In Category', 'aqualuxe' ),
        'parent_item_colon' => __( 'Parent Trade-In Category:', 'aqualuxe' ),
        'edit_item'         => __( 'Edit Trade-In Category', 'aqualuxe' ),
        'update_item'       => __( 'Update Trade-In Category', 'aqualuxe' ),
        'add_new_item'      => __( 'Add New Trade-In Category', 'aqualuxe' ),
        'new_item_name'     => __( 'New Trade-In Category Name', 'aqualuxe' ),
        'menu_name'         => __( 'Categories', 'aqualuxe' ),
    );

    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'trade-in-category' ),
    );

    register_taxonomy( 'trade_in_category', array( 'aqualuxe_trade_in' ), $args );
    
    // Register default trade-in categories
    if ( ! term_exists( 'fish', 'trade_in_category' ) ) {
        wp_insert_term( 'Fish', 'trade_in_category', array( 'slug' => 'fish' ) );
    }
    
    if ( ! term_exists( 'equipment', 'trade_in_category' ) ) {
        wp_insert_term( 'Equipment', 'trade_in_category', array( 'slug' => 'equipment' ) );
    }
    
    if ( ! term_exists( 'aquariums', 'trade_in_category' ) ) {
        wp_insert_term( 'Aquariums', 'trade_in_category', array( 'slug' => 'aquariums' ) );
    }
    
    if ( ! term_exists( 'accessories', 'trade_in_category' ) ) {
        wp_insert_term( 'Accessories', 'trade_in_category', array( 'slug' => 'accessories' ) );
    }
}
add_action( 'init', 'aqualuxe_register_trade_in_category_taxonomy' );

/**
 * Register Trade-In Status taxonomy
 */
function aqualuxe_register_trade_in_status_taxonomy() {
    $labels = array(
        'name'              => _x( 'Trade-In Statuses', 'taxonomy general name', 'aqualuxe' ),
        'singular_name'     => _x( 'Trade-In Status', 'taxonomy singular name', 'aqualuxe' ),
        'search_items'      => __( 'Search Trade-In Statuses', 'aqualuxe' ),
        'all_items'         => __( 'All Trade-In Statuses', 'aqualuxe' ),
        'parent_item'       => __( 'Parent Trade-In Status', 'aqualuxe' ),
        'parent_item_colon' => __( 'Parent Trade-In Status:', 'aqualuxe' ),
        'edit_item'         => __( 'Edit Trade-In Status', 'aqualuxe' ),
        'update_item'       => __( 'Update Trade-In Status', 'aqualuxe' ),
        'add_new_item'      => __( 'Add New Trade-In Status', 'aqualuxe' ),
        'new_item_name'     => __( 'New Trade-In Status Name', 'aqualuxe' ),
        'menu_name'         => __( 'Statuses', 'aqualuxe' ),
    );

    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'trade-in-status' ),
    );

    register_taxonomy( 'trade_in_status', array( 'aqualuxe_trade_in' ), $args );
    
    // Register default trade-in statuses
    if ( ! term_exists( 'available', 'trade_in_status' ) ) {
        wp_insert_term( 'Available', 'trade_in_status', array( 'slug' => 'available' ) );
    }
    
    if ( ! term_exists( 'pending', 'trade_in_status' ) ) {
        wp_insert_term( 'Pending', 'trade_in_status', array( 'slug' => 'pending' ) );
    }
    
    if ( ! term_exists( 'traded', 'trade_in_status' ) ) {
        wp_insert_term( 'Traded', 'trade_in_status', array( 'slug' => 'traded' ) );
    }
    
    if ( ! term_exists( 'rejected', 'trade_in_status' ) ) {
        wp_insert_term( 'Rejected', 'trade_in_status', array( 'slug' => 'rejected' ) );
    }
}
add_action( 'init', 'aqualuxe_register_trade_in_status_taxonomy' );

/**
 * Register Trade-In Request custom post type
 */
function aqualuxe_register_trade_in_request_post_type() {
    $labels = array(
        'name'                  => _x( 'Trade-In Requests', 'Post type general name', 'aqualuxe' ),
        'singular_name'         => _x( 'Trade-In Request', 'Post type singular name', 'aqualuxe' ),
        'menu_name'             => _x( 'Trade-In Requests', 'Admin Menu text', 'aqualuxe' ),
        'name_admin_bar'        => _x( 'Trade-In Request', 'Add New on Toolbar', 'aqualuxe' ),
        'add_new'               => __( 'Add New', 'aqualuxe' ),
        'add_new_item'          => __( 'Add New Trade-In Request', 'aqualuxe' ),
        'new_item'              => __( 'New Trade-In Request', 'aqualuxe' ),
        'edit_item'             => __( 'Edit Trade-In Request', 'aqualuxe' ),
        'view_item'             => __( 'View Trade-In Request', 'aqualuxe' ),
        'all_items'             => __( 'All Trade-In Requests', 'aqualuxe' ),
        'search_items'          => __( 'Search Trade-In Requests', 'aqualuxe' ),
        'not_found'             => __( 'No trade-in requests found.', 'aqualuxe' ),
        'not_found_in_trash'    => __( 'No trade-in requests found in Trash.', 'aqualuxe' ),
        'archives'              => _x( 'Trade-In Request Archives', 'The post type archive label used in nav menus', 'aqualuxe' ),
        'filter_items_list'     => _x( 'Filter trade-in requests list', 'Screen reader text for the filter links', 'aqualuxe' ),
        'items_list_navigation' => _x( 'Trade-in requests list navigation', 'Screen reader text for the pagination', 'aqualuxe' ),
        'items_list'            => _x( 'Trade-in requests list', 'Screen reader text for the items list', 'aqualuxe' ),
    );

    $args = array(
        'labels'             => $labels,
        'public'             => false,
        'publicly_queryable' => false,
        'show_ui'            => true,
        'show_in_menu'       => 'edit.php?post_type=aqualuxe_trade_in',
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'trade-in-request' ),
        'capability_type'    => 'post',
        'has_archive'        => false,
        'hierarchical'       => false,
        'menu_position'      => null,
        'supports'           => array( 'title' ),
    );

    register_post_type( 'trade_in_request', $args );
}
add_action( 'init', 'aqualuxe_register_trade_in_request_post_type' );

/**
 * Register meta boxes for the trade-in post type
 */
function aqualuxe_register_trade_in_meta_boxes() {
    add_meta_box(
        'aqualuxe_trade_in_details',
        __( 'Trade-In Details', 'aqualuxe' ),
        'aqualuxe_trade_in_details_meta_box_callback',
        'aqualuxe_trade_in',
        'normal',
        'high'
    );
    
    add_meta_box(
        'aqualuxe_trade_in_value',
        __( 'Trade-In Value', 'aqualuxe' ),
        'aqualuxe_trade_in_value_meta_box_callback',
        'aqualuxe_trade_in',
        'side',
        'default'
    );
}
add_action( 'add_meta_boxes', 'aqualuxe_register_trade_in_meta_boxes' );

/**
 * Trade-In Details meta box callback
 */
function aqualuxe_trade_in_details_meta_box_callback( $post ) {
    // Add nonce for security
    wp_nonce_field( 'aqualuxe_trade_in_details_nonce', 'trade_in_details_nonce' );
    
    // Get saved values
    $condition = get_post_meta( $post->ID, '_trade_in_condition', true );
    $age = get_post_meta( $post->ID, '_trade_in_age', true );
    $brand = get_post_meta( $post->ID, '_trade_in_brand', true );
    $model = get_post_meta( $post->ID, '_trade_in_model', true );
    $specifications = get_post_meta( $post->ID, '_trade_in_specifications', true );
    $original_owner = get_post_meta( $post->ID, '_trade_in_original_owner', true ) === 'yes';
    ?>
    <div class="trade-in-details-meta-box">
        <style>
            .trade-in-details-meta-box .form-field {
                margin-bottom: 15px;
            }
            .trade-in-details-meta-box label {
                display: block;
                font-weight: 600;
                margin-bottom: 5px;
            }
            .trade-in-details-meta-box input[type="text"],
            .trade-in-details-meta-box select,
            .trade-in-details-meta-box textarea {
                width: 100%;
                max-width: 400px;
            }
            .trade-in-details-meta-box .form-row {
                display: flex;
                flex-wrap: wrap;
                margin: 0 -10px;
            }
            .trade-in-details-meta-box .form-col {
                flex: 1;
                padding: 0 10px;
                min-width: 200px;
            }
            .trade-in-details-meta-box .checkbox-field {
                margin-top: 20px;
            }
            .trade-in-details-meta-box .checkbox-field label {
                display: inline;
                margin-left: 5px;
            }
        </style>
        
        <div class="form-row">
            <div class="form-col">
                <div class="form-field">
                    <label for="trade_in_condition"><?php esc_html_e( 'Condition', 'aqualuxe' ); ?></label>
                    <select id="trade_in_condition" name="trade_in_condition">
                        <option value=""><?php esc_html_e( 'Select Condition', 'aqualuxe' ); ?></option>
                        <option value="new" <?php selected( $condition, 'new' ); ?>><?php esc_html_e( 'New', 'aqualuxe' ); ?></option>
                        <option value="like-new" <?php selected( $condition, 'like-new' ); ?>><?php esc_html_e( 'Like New', 'aqualuxe' ); ?></option>
                        <option value="excellent" <?php selected( $condition, 'excellent' ); ?>><?php esc_html_e( 'Excellent', 'aqualuxe' ); ?></option>
                        <option value="good" <?php selected( $condition, 'good' ); ?>><?php esc_html_e( 'Good', 'aqualuxe' ); ?></option>
                        <option value="fair" <?php selected( $condition, 'fair' ); ?>><?php esc_html_e( 'Fair', 'aqualuxe' ); ?></option>
                        <option value="poor" <?php selected( $condition, 'poor' ); ?>><?php esc_html_e( 'Poor', 'aqualuxe' ); ?></option>
                    </select>
                </div>
                
                <div class="form-field">
                    <label for="trade_in_age"><?php esc_html_e( 'Age', 'aqualuxe' ); ?></label>
                    <input type="text" id="trade_in_age" name="trade_in_age" value="<?php echo esc_attr( $age ); ?>" placeholder="<?php esc_attr_e( 'e.g., 2 years', 'aqualuxe' ); ?>" />
                </div>
                
                <div class="checkbox-field">
                    <input type="checkbox" id="trade_in_original_owner" name="trade_in_original_owner" value="yes" <?php checked( $original_owner ); ?> />
                    <label for="trade_in_original_owner"><?php esc_html_e( 'Original Owner', 'aqualuxe' ); ?></label>
                </div>
            </div>
            
            <div class="form-col">
                <div class="form-field">
                    <label for="trade_in_brand"><?php esc_html_e( 'Brand', 'aqualuxe' ); ?></label>
                    <input type="text" id="trade_in_brand" name="trade_in_brand" value="<?php echo esc_attr( $brand ); ?>" />
                </div>
                
                <div class="form-field">
                    <label for="trade_in_model"><?php esc_html_e( 'Model', 'aqualuxe' ); ?></label>
                    <input type="text" id="trade_in_model" name="trade_in_model" value="<?php echo esc_attr( $model ); ?>" />
                </div>
                
                <div class="form-field">
                    <label for="trade_in_specifications"><?php esc_html_e( 'Specifications', 'aqualuxe' ); ?></label>
                    <textarea id="trade_in_specifications" name="trade_in_specifications" rows="4"><?php echo esc_textarea( $specifications ); ?></textarea>
                    <p class="description"><?php esc_html_e( 'Enter specifications such as size, capacity, wattage, etc.', 'aqualuxe' ); ?></p>
                </div>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Trade-In Value meta box callback
 */
function aqualuxe_trade_in_value_meta_box_callback( $post ) {
    // Add nonce for security
    wp_nonce_field( 'aqualuxe_trade_in_value_nonce', 'trade_in_value_nonce' );
    
    // Get saved values
    $trade_value = get_post_meta( $post->ID, '_trade_in_value', true );
    $store_credit = get_post_meta( $post->ID, '_trade_in_store_credit', true );
    $cash_value = get_post_meta( $post->ID, '_trade_in_cash_value', true );
    ?>
    <div class="trade-in-value-meta-box">
        <p>
            <label for="trade_in_value"><strong><?php esc_html_e( 'Trade-In Value ($)', 'aqualuxe' ); ?></strong></label><br>
            <input type="number" id="trade_in_value" name="trade_in_value" value="<?php echo esc_attr( $trade_value ); ?>" step="0.01" min="0" style="width: 100%;" />
            <span class="description"><?php esc_html_e( 'Base value for this item', 'aqualuxe' ); ?></span>
        </p>
        
        <p>
            <label for="trade_in_store_credit"><strong><?php esc_html_e( 'Store Credit Value ($)', 'aqualuxe' ); ?></strong></label><br>
            <input type="number" id="trade_in_store_credit" name="trade_in_store_credit" value="<?php echo esc_attr( $store_credit ); ?>" step="0.01" min="0" style="width: 100%;" />
            <span class="description"><?php esc_html_e( 'Value if customer chooses store credit', 'aqualuxe' ); ?></span>
        </p>
        
        <p>
            <label for="trade_in_cash_value"><strong><?php esc_html_e( 'Cash Value ($)', 'aqualuxe' ); ?></strong></label><br>
            <input type="number" id="trade_in_cash_value" name="trade_in_cash_value" value="<?php echo esc_attr( $cash_value ); ?>" step="0.01" min="0" style="width: 100%;" />
            <span class="description"><?php esc_html_e( 'Value if customer chooses cash', 'aqualuxe' ); ?></span>
        </p>
    </div>
    <?php
}

/**
 * Save trade-in meta box data
 */
function aqualuxe_save_trade_in_meta_box_data( $post_id ) {
    // Check if our nonces are set and verify them
    if ( ! isset( $_POST['trade_in_details_nonce'] ) || ! wp_verify_nonce( $_POST['trade_in_details_nonce'], 'aqualuxe_trade_in_details_nonce' ) ) {
        return;
    }
    
    if ( ! isset( $_POST['trade_in_value_nonce'] ) || ! wp_verify_nonce( $_POST['trade_in_value_nonce'], 'aqualuxe_trade_in_value_nonce' ) ) {
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
    
    // Trade-in details
    if ( isset( $_POST['trade_in_condition'] ) ) {
        update_post_meta( $post_id, '_trade_in_condition', sanitize_text_field( $_POST['trade_in_condition'] ) );
    }
    
    if ( isset( $_POST['trade_in_age'] ) ) {
        update_post_meta( $post_id, '_trade_in_age', sanitize_text_field( $_POST['trade_in_age'] ) );
    }
    
    if ( isset( $_POST['trade_in_brand'] ) ) {
        update_post_meta( $post_id, '_trade_in_brand', sanitize_text_field( $_POST['trade_in_brand'] ) );
    }
    
    if ( isset( $_POST['trade_in_model'] ) ) {
        update_post_meta( $post_id, '_trade_in_model', sanitize_text_field( $_POST['trade_in_model'] ) );
    }
    
    if ( isset( $_POST['trade_in_specifications'] ) ) {
        update_post_meta( $post_id, '_trade_in_specifications', sanitize_textarea_field( $_POST['trade_in_specifications'] ) );
    }
    
    $original_owner = isset( $_POST['trade_in_original_owner'] ) ? 'yes' : 'no';
    update_post_meta( $post_id, '_trade_in_original_owner', $original_owner );
    
    // Trade-in value
    if ( isset( $_POST['trade_in_value'] ) ) {
        update_post_meta( $post_id, '_trade_in_value', (float) $_POST['trade_in_value'] );
    }
    
    if ( isset( $_POST['trade_in_store_credit'] ) ) {
        update_post_meta( $post_id, '_trade_in_store_credit', (float) $_POST['trade_in_store_credit'] );
    }
    
    if ( isset( $_POST['trade_in_cash_value'] ) ) {
        update_post_meta( $post_id, '_trade_in_cash_value', (float) $_POST['trade_in_cash_value'] );
    }
}
add_action( 'save_post_aqualuxe_trade_in', 'aqualuxe_save_trade_in_meta_box_data' );

/**
 * Register meta boxes for the trade-in request post type
 */
function aqualuxe_register_trade_in_request_meta_boxes() {
    add_meta_box(
        'aqualuxe_trade_in_request_details',
        __( 'Request Details', 'aqualuxe' ),
        'aqualuxe_trade_in_request_details_meta_box_callback',
        'trade_in_request',
        'normal',
        'high'
    );
    
    add_meta_box(
        'aqualuxe_trade_in_request_item',
        __( 'Item Information', 'aqualuxe' ),
        'aqualuxe_trade_in_request_item_meta_box_callback',
        'trade_in_request',
        'normal',
        'default'
    );
    
    add_meta_box(
        'aqualuxe_trade_in_request_customer',
        __( 'Customer Information', 'aqualuxe' ),
        'aqualuxe_trade_in_request_customer_meta_box_callback',
        'trade_in_request',
        'normal',
        'default'
    );
    
    add_meta_box(
        'aqualuxe_trade_in_request_status',
        __( 'Request Status', 'aqualuxe' ),
        'aqualuxe_trade_in_request_status_meta_box_callback',
        'trade_in_request',
        'side',
        'default'
    );
}
add_action( 'add_meta_boxes', 'aqualuxe_register_trade_in_request_meta_boxes' );

/**
 * Trade-In Request Details meta box callback
 */
function aqualuxe_trade_in_request_details_meta_box_callback( $post ) {
    // Add nonce for security
    wp_nonce_field( 'aqualuxe_trade_in_request_details_nonce', 'trade_in_request_details_nonce' );
    
    // Get saved values
    $trade_in_id = get_post_meta( $post->ID, '_trade_in_request_item_id', true );
    $request_date = get_post_meta( $post->ID, '_trade_in_request_date', true );
    $preferred_value = get_post_meta( $post->ID, '_trade_in_request_preferred_value', true );
    $notes = get_post_meta( $post->ID, '_trade_in_request_notes', true );
    
    // Format date
    $formatted_date = $request_date ? date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), strtotime( $request_date ) ) : '';
    ?>
    <div class="trade-in-request-details-meta-box">
        <style>
            .trade-in-request-details-meta-box .form-field {
                margin-bottom: 15px;
            }
            .trade-in-request-details-meta-box label {
                display: block;
                font-weight: 600;
                margin-bottom: 5px;
            }
            .trade-in-request-details-meta-box input[type="text"],
            .trade-in-request-details-meta-box select,
            .trade-in-request-details-meta-box textarea {
                width: 100%;
                max-width: 400px;
            }
            .trade-in-request-details-meta-box .form-row {
                display: flex;
                flex-wrap: wrap;
                margin: 0 -10px;
            }
            .trade-in-request-details-meta-box .form-col {
                flex: 1;
                padding: 0 10px;
                min-width: 200px;
            }
        </style>
        
        <div class="form-row">
            <div class="form-col">
                <div class="form-field">
                    <label for="trade_in_request_item_id"><?php esc_html_e( 'Trade-In Item', 'aqualuxe' ); ?></label>
                    <select id="trade_in_request_item_id" name="trade_in_request_item_id">
                        <option value=""><?php esc_html_e( 'Select Trade-In Item', 'aqualuxe' ); ?></option>
                        <?php
                        $trade_in_items = get_posts( array(
                            'post_type'      => 'aqualuxe_trade_in',
                            'posts_per_page' => -1,
                            'orderby'        => 'title',
                            'order'          => 'ASC',
                        ) );
                        
                        foreach ( $trade_in_items as $item ) {
                            echo '<option value="' . esc_attr( $item->ID ) . '" ' . selected( $trade_in_id, $item->ID, false ) . '>' . esc_html( $item->post_title ) . '</option>';
                        }
                        ?>
                    </select>
                </div>
                
                <div class="form-field">
                    <label for="trade_in_request_date"><?php esc_html_e( 'Request Date', 'aqualuxe' ); ?></label>
                    <input type="text" id="trade_in_request_date" name="trade_in_request_date" value="<?php echo esc_attr( $formatted_date ); ?>" readonly />
                </div>
            </div>
            
            <div class="form-col">
                <div class="form-field">
                    <label for="trade_in_request_preferred_value"><?php esc_html_e( 'Preferred Value Type', 'aqualuxe' ); ?></label>
                    <select id="trade_in_request_preferred_value" name="trade_in_request_preferred_value">
                        <option value=""><?php esc_html_e( 'Select Value Type', 'aqualuxe' ); ?></option>
                        <option value="store_credit" <?php selected( $preferred_value, 'store_credit' ); ?>><?php esc_html_e( 'Store Credit', 'aqualuxe' ); ?></option>
                        <option value="cash" <?php selected( $preferred_value, 'cash' ); ?>><?php esc_html_e( 'Cash', 'aqualuxe' ); ?></option>
                    </select>
                </div>
                
                <div class="form-field">
                    <label for="trade_in_request_notes"><?php esc_html_e( 'Notes', 'aqualuxe' ); ?></label>
                    <textarea id="trade_in_request_notes" name="trade_in_request_notes" rows="4"><?php echo esc_textarea( $notes ); ?></textarea>
                </div>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Trade-In Request Item meta box callback
 */
function aqualuxe_trade_in_request_item_meta_box_callback( $post ) {
    // Add nonce for security
    wp_nonce_field( 'aqualuxe_trade_in_request_item_nonce', 'trade_in_request_item_nonce' );
    
    // Get saved values
    $item_type = get_post_meta( $post->ID, '_trade_in_request_item_type', true );
    $item_name = get_post_meta( $post->ID, '_trade_in_request_item_name', true );
    $item_condition = get_post_meta( $post->ID, '_trade_in_request_item_condition', true );
    $item_age = get_post_meta( $post->ID, '_trade_in_request_item_age', true );
    $item_description = get_post_meta( $post->ID, '_trade_in_request_item_description', true );
    ?>
    <div class="trade-in-request-item-meta-box">
        <style>
            .trade-in-request-item-meta-box .form-field {
                margin-bottom: 15px;
            }
            .trade-in-request-item-meta-box label {
                display: block;
                font-weight: 600;
                margin-bottom: 5px;
            }
            .trade-in-request-item-meta-box input[type="text"],
            .trade-in-request-item-meta-box select,
            .trade-in-request-item-meta-box textarea {
                width: 100%;
                max-width: 400px;
            }
            .trade-in-request-item-meta-box .form-row {
                display: flex;
                flex-wrap: wrap;
                margin: 0 -10px;
            }
            .trade-in-request-item-meta-box .form-col {
                flex: 1;
                padding: 0 10px;
                min-width: 200px;
            }
        </style>
        
        <div class="form-row">
            <div class="form-col">
                <div class="form-field">
                    <label for="trade_in_request_item_type"><?php esc_html_e( 'Item Type', 'aqualuxe' ); ?></label>
                    <select id="trade_in_request_item_type" name="trade_in_request_item_type">
                        <option value=""><?php esc_html_e( 'Select Item Type', 'aqualuxe' ); ?></option>
                        <option value="fish" <?php selected( $item_type, 'fish' ); ?>><?php esc_html_e( 'Fish', 'aqualuxe' ); ?></option>
                        <option value="equipment" <?php selected( $item_type, 'equipment' ); ?>><?php esc_html_e( 'Equipment', 'aqualuxe' ); ?></option>
                        <option value="aquarium" <?php selected( $item_type, 'aquarium' ); ?>><?php esc_html_e( 'Aquarium', 'aqualuxe' ); ?></option>
                        <option value="accessory" <?php selected( $item_type, 'accessory' ); ?>><?php esc_html_e( 'Accessory', 'aqualuxe' ); ?></option>
                        <option value="other" <?php selected( $item_type, 'other' ); ?>><?php esc_html_e( 'Other', 'aqualuxe' ); ?></option>
                    </select>
                </div>
                
                <div class="form-field">
                    <label for="trade_in_request_item_name"><?php esc_html_e( 'Item Name', 'aqualuxe' ); ?></label>
                    <input type="text" id="trade_in_request_item_name" name="trade_in_request_item_name" value="<?php echo esc_attr( $item_name ); ?>" />
                </div>
                
                <div class="form-field">
                    <label for="trade_in_request_item_condition"><?php esc_html_e( 'Condition', 'aqualuxe' ); ?></label>
                    <select id="trade_in_request_item_condition" name="trade_in_request_item_condition">
                        <option value=""><?php esc_html_e( 'Select Condition', 'aqualuxe' ); ?></option>
                        <option value="new" <?php selected( $item_condition, 'new' ); ?>><?php esc_html_e( 'New', 'aqualuxe' ); ?></option>
                        <option value="like-new" <?php selected( $item_condition, 'like-new' ); ?>><?php esc_html_e( 'Like New', 'aqualuxe' ); ?></option>
                        <option value="excellent" <?php selected( $item_condition, 'excellent' ); ?>><?php esc_html_e( 'Excellent', 'aqualuxe' ); ?></option>
                        <option value="good" <?php selected( $item_condition, 'good' ); ?>><?php esc_html_e( 'Good', 'aqualuxe' ); ?></option>
                        <option value="fair" <?php selected( $item_condition, 'fair' ); ?>><?php esc_html_e( 'Fair', 'aqualuxe' ); ?></option>
                        <option value="poor" <?php selected( $item_condition, 'poor' ); ?>><?php esc_html_e( 'Poor', 'aqualuxe' ); ?></option>
                    </select>
                </div>
            </div>
            
            <div class="form-col">
                <div class="form-field">
                    <label for="trade_in_request_item_age"><?php esc_html_e( 'Age', 'aqualuxe' ); ?></label>
                    <input type="text" id="trade_in_request_item_age" name="trade_in_request_item_age" value="<?php echo esc_attr( $item_age ); ?>" />
                </div>
                
                <div class="form-field">
                    <label for="trade_in_request_item_description"><?php esc_html_e( 'Description', 'aqualuxe' ); ?></label>
                    <textarea id="trade_in_request_item_description" name="trade_in_request_item_description" rows="4"><?php echo esc_textarea( $item_description ); ?></textarea>
                </div>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Trade-In Request Customer meta box callback
 */
function aqualuxe_trade_in_request_customer_meta_box_callback( $post ) {
    // Add nonce for security
    wp_nonce_field( 'aqualuxe_trade_in_request_customer_nonce', 'trade_in_request_customer_nonce' );
    
    // Get saved values
    $customer_name = get_post_meta( $post->ID, '_trade_in_request_customer_name', true );
    $customer_email = get_post_meta( $post->ID, '_trade_in_request_customer_email', true );
    $customer_phone = get_post_meta( $post->ID, '_trade_in_request_customer_phone', true );
    $customer_user_id = get_post_meta( $post->ID, '_trade_in_request_customer_user_id', true );
    ?>
    <div class="trade-in-request-customer-meta-box">
        <style>
            .trade-in-request-customer-meta-box .form-field {
                margin-bottom: 15px;
            }
            .trade-in-request-customer-meta-box label {
                display: block;
                font-weight: 600;
                margin-bottom: 5px;
            }
            .trade-in-request-customer-meta-box input[type="text"],
            .trade-in-request-customer-meta-box input[type="email"],
            .trade-in-request-customer-meta-box input[type="tel"] {
                width: 100%;
                max-width: 400px;
            }
            .trade-in-request-customer-meta-box .form-row {
                display: flex;
                flex-wrap: wrap;
                margin: 0 -10px;
            }
            .trade-in-request-customer-meta-box .form-col {
                flex: 1;
                padding: 0 10px;
                min-width: 200px;
            }
        </style>
        
        <div class="form-row">
            <div class="form-col">
                <div class="form-field">
                    <label for="trade_in_request_customer_name"><?php esc_html_e( 'Name', 'aqualuxe' ); ?></label>
                    <input type="text" id="trade_in_request_customer_name" name="trade_in_request_customer_name" value="<?php echo esc_attr( $customer_name ); ?>" />
                </div>
                
                <div class="form-field">
                    <label for="trade_in_request_customer_email"><?php esc_html_e( 'Email', 'aqualuxe' ); ?></label>
                    <input type="email" id="trade_in_request_customer_email" name="trade_in_request_customer_email" value="<?php echo esc_attr( $customer_email ); ?>" />
                </div>
            </div>
            
            <div class="form-col">
                <div class="form-field">
                    <label for="trade_in_request_customer_phone"><?php esc_html_e( 'Phone', 'aqualuxe' ); ?></label>
                    <input type="tel" id="trade_in_request_customer_phone" name="trade_in_request_customer_phone" value="<?php echo esc_attr( $customer_phone ); ?>" />
                </div>
                
                <?php if ( $customer_user_id ) : ?>
                    <div class="form-field">
                        <label><?php esc_html_e( 'User Account', 'aqualuxe' ); ?></label>
                        <?php
                        $user = get_user_by( 'id', $customer_user_id );
                        if ( $user ) {
                            echo '<p><a href="' . esc_url( get_edit_user_link( $customer_user_id ) ) . '">' . esc_html( $user->display_name ) . ' (' . esc_html( $user->user_email ) . ')</a></p>';
                        } else {
                            echo '<p>' . esc_html__( 'User not found', 'aqualuxe' ) . '</p>';
                        }
                        ?>
                        <input type="hidden" name="trade_in_request_customer_user_id" value="<?php echo esc_attr( $customer_user_id ); ?>" />
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Trade-In Request Status meta box callback
 */
function aqualuxe_trade_in_request_status_meta_box_callback( $post ) {
    // Add nonce for security
    wp_nonce_field( 'aqualuxe_trade_in_request_status_nonce', 'trade_in_request_status_nonce' );
    
    // Get saved values
    $status = get_post_meta( $post->ID, '_trade_in_request_status', true );
    $admin_notes = get_post_meta( $post->ID, '_trade_in_request_admin_notes', true );
    $estimated_value = get_post_meta( $post->ID, '_trade_in_request_estimated_value', true );
    ?>
    <div class="trade-in-request-status-meta-box">
        <p>
            <label for="trade_in_request_status"><strong><?php esc_html_e( 'Status', 'aqualuxe' ); ?></strong></label><br>
            <select id="trade_in_request_status" name="trade_in_request_status" style="width: 100%;">
                <option value="new" <?php selected( $status, 'new' ); ?>><?php esc_html_e( 'New', 'aqualuxe' ); ?></option>
                <option value="reviewing" <?php selected( $status, 'reviewing' ); ?>><?php esc_html_e( 'Reviewing', 'aqualuxe' ); ?></option>
                <option value="approved" <?php selected( $status, 'approved' ); ?>><?php esc_html_e( 'Approved', 'aqualuxe' ); ?></option>
                <option value="rejected" <?php selected( $status, 'rejected' ); ?>><?php esc_html_e( 'Rejected', 'aqualuxe' ); ?></option>
                <option value="completed" <?php selected( $status, 'completed' ); ?>><?php esc_html_e( 'Completed', 'aqualuxe' ); ?></option>
            </select>
        </p>
        
        <p>
            <label for="trade_in_request_estimated_value"><strong><?php esc_html_e( 'Estimated Value ($)', 'aqualuxe' ); ?></strong></label><br>
            <input type="number" id="trade_in_request_estimated_value" name="trade_in_request_estimated_value" value="<?php echo esc_attr( $estimated_value ); ?>" step="0.01" min="0" style="width: 100%;" />
        </p>
        
        <p>
            <label for="trade_in_request_admin_notes"><strong><?php esc_html_e( 'Admin Notes', 'aqualuxe' ); ?></strong></label><br>
            <textarea id="trade_in_request_admin_notes" name="trade_in_request_admin_notes" rows="4" style="width: 100%;"><?php echo esc_textarea( $admin_notes ); ?></textarea>
        </p>
        
        <?php if ( 'new' !== $status ) : ?>
            <p>
                <button type="button" class="button" id="trade_in_request_notify_customer">
                    <?php esc_html_e( 'Notify Customer', 'aqualuxe' ); ?>
                </button>
            </p>
            
            <script>
            jQuery(document).ready(function($) {
                $('#trade_in_request_notify_customer').on('click', function() {
                    if (confirm('<?php esc_html_e( 'Send notification email to customer?', 'aqualuxe' ); ?>')) {
                        var data = {
                            action: 'aqualuxe_notify_trade_in_customer',
                            request_id: <?php echo intval( $post->ID ); ?>,
                            nonce: '<?php echo wp_create_nonce( 'aqualuxe_notify_trade_in_customer' ); ?>'
                        };
                        
                        $.post(ajaxurl, data, function(response) {
                            if (response.success) {
                                alert('<?php esc_html_e( 'Notification sent successfully!', 'aqualuxe' ); ?>');
                            } else {
                                alert('<?php esc_html_e( 'Error sending notification.', 'aqualuxe' ); ?>');
                            }
                        });
                    }
                });
            });
            </script>
        <?php endif; ?>
    </div>
    <?php
}

/**
 * Save trade-in request meta box data
 */
function aqualuxe_save_trade_in_request_meta_box_data( $post_id ) {
    // Check if our nonces are set and verify them
    if ( ! isset( $_POST['trade_in_request_details_nonce'] ) || ! wp_verify_nonce( $_POST['trade_in_request_details_nonce'], 'aqualuxe_trade_in_request_details_nonce' ) ) {
        return;
    }
    
    if ( ! isset( $_POST['trade_in_request_item_nonce'] ) || ! wp_verify_nonce( $_POST['trade_in_request_item_nonce'], 'aqualuxe_trade_in_request_item_nonce' ) ) {
        return;
    }
    
    if ( ! isset( $_POST['trade_in_request_customer_nonce'] ) || ! wp_verify_nonce( $_POST['trade_in_request_customer_nonce'], 'aqualuxe_trade_in_request_customer_nonce' ) ) {
        return;
    }
    
    if ( ! isset( $_POST['trade_in_request_status_nonce'] ) || ! wp_verify_nonce( $_POST['trade_in_request_status_nonce'], 'aqualuxe_trade_in_request_status_nonce' ) ) {
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
    
    // Request details
    if ( isset( $_POST['trade_in_request_item_id'] ) ) {
        update_post_meta( $post_id, '_trade_in_request_item_id', intval( $_POST['trade_in_request_item_id'] ) );
    }
    
    if ( isset( $_POST['trade_in_request_preferred_value'] ) ) {
        update_post_meta( $post_id, '_trade_in_request_preferred_value', sanitize_text_field( $_POST['trade_in_request_preferred_value'] ) );
    }
    
    if ( isset( $_POST['trade_in_request_notes'] ) ) {
        update_post_meta( $post_id, '_trade_in_request_notes', sanitize_textarea_field( $_POST['trade_in_request_notes'] ) );
    }
    
    // Item information
    if ( isset( $_POST['trade_in_request_item_type'] ) ) {
        update_post_meta( $post_id, '_trade_in_request_item_type', sanitize_text_field( $_POST['trade_in_request_item_type'] ) );
    }
    
    if ( isset( $_POST['trade_in_request_item_name'] ) ) {
        update_post_meta( $post_id, '_trade_in_request_item_name', sanitize_text_field( $_POST['trade_in_request_item_name'] ) );
    }
    
    if ( isset( $_POST['trade_in_request_item_condition'] ) ) {
        update_post_meta( $post_id, '_trade_in_request_item_condition', sanitize_text_field( $_POST['trade_in_request_item_condition'] ) );
    }
    
    if ( isset( $_POST['trade_in_request_item_age'] ) ) {
        update_post_meta( $post_id, '_trade_in_request_item_age', sanitize_text_field( $_POST['trade_in_request_item_age'] ) );
    }
    
    if ( isset( $_POST['trade_in_request_item_description'] ) ) {
        update_post_meta( $post_id, '_trade_in_request_item_description', sanitize_textarea_field( $_POST['trade_in_request_item_description'] ) );
    }
    
    // Customer information
    if ( isset( $_POST['trade_in_request_customer_name'] ) ) {
        update_post_meta( $post_id, '_trade_in_request_customer_name', sanitize_text_field( $_POST['trade_in_request_customer_name'] ) );
    }
    
    if ( isset( $_POST['trade_in_request_customer_email'] ) ) {
        update_post_meta( $post_id, '_trade_in_request_customer_email', sanitize_email( $_POST['trade_in_request_customer_email'] ) );
    }
    
    if ( isset( $_POST['trade_in_request_customer_phone'] ) ) {
        update_post_meta( $post_id, '_trade_in_request_customer_phone', sanitize_text_field( $_POST['trade_in_request_customer_phone'] ) );
    }
    
    if ( isset( $_POST['trade_in_request_customer_user_id'] ) ) {
        update_post_meta( $post_id, '_trade_in_request_customer_user_id', intval( $_POST['trade_in_request_customer_user_id'] ) );
    }
    
    // Status information
    $old_status = get_post_meta( $post_id, '_trade_in_request_status', true );
    $new_status = isset( $_POST['trade_in_request_status'] ) ? sanitize_text_field( $_POST['trade_in_request_status'] ) : '';
    
    if ( $new_status && $old_status !== $new_status ) {
        update_post_meta( $post_id, '_trade_in_request_status', $new_status );
        
        // Log status change
        $log = get_post_meta( $post_id, '_trade_in_request_status_log', true );
        if ( ! is_array( $log ) ) {
            $log = array();
        }
        
        $log[] = array(
            'status'    => $new_status,
            'timestamp' => current_time( 'mysql' ),
            'user_id'   => get_current_user_id(),
        );
        
        update_post_meta( $post_id, '_trade_in_request_status_log', $log );
    }
    
    if ( isset( $_POST['trade_in_request_estimated_value'] ) ) {
        update_post_meta( $post_id, '_trade_in_request_estimated_value', (float) $_POST['trade_in_request_estimated_value'] );
    }
    
    if ( isset( $_POST['trade_in_request_admin_notes'] ) ) {
        update_post_meta( $post_id, '_trade_in_request_admin_notes', sanitize_textarea_field( $_POST['trade_in_request_admin_notes'] ) );
    }
}
add_action( 'save_post_trade_in_request', 'aqualuxe_save_trade_in_request_meta_box_data' );

/**
 * Add custom columns to the trade-in list table
 */
function aqualuxe_trade_in_columns( $columns ) {
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
    $new_columns['category'] = __( 'Category', 'aqualuxe' );
    $new_columns['condition'] = __( 'Condition', 'aqualuxe' );
    $new_columns['value'] = __( 'Value', 'aqualuxe' );
    $new_columns['status'] = __( 'Status', 'aqualuxe' );
    
    // Add date at the end
    if ( isset( $columns['date'] ) ) {
        $new_columns['date'] = $columns['date'];
    }
    
    return $new_columns;
}
add_filter( 'manage_aqualuxe_trade_in_posts_columns', 'aqualuxe_trade_in_columns' );

/**
 * Display data in custom columns for the trade-in list table
 */
function aqualuxe_trade_in_custom_column( $column, $post_id ) {
    switch ( $column ) {
        case 'thumbnail':
            if ( has_post_thumbnail( $post_id ) ) {
                echo get_the_post_thumbnail( $post_id, array( 50, 50 ) );
            } else {
                echo '<div style="width: 50px; height: 50px; background-color: #f0f0f0; display: flex; align-items: center; justify-content: center; color: #999;">N/A</div>';
            }
            break;
            
        case 'category':
            $terms = get_the_terms( $post_id, 'trade_in_category' );
            
            if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
                $term_names = array();
                
                foreach ( $terms as $term ) {
                    $term_names[] = $term->name;
                }
                
                echo esc_html( implode( ', ', $term_names ) );
            } else {
                echo '—';
            }
            break;
            
        case 'condition':
            $condition = get_post_meta( $post_id, '_trade_in_condition', true );
            
            $condition_labels = array(
                'new'       => __( 'New', 'aqualuxe' ),
                'like-new'  => __( 'Like New', 'aqualuxe' ),
                'excellent' => __( 'Excellent', 'aqualuxe' ),
                'good'      => __( 'Good', 'aqualuxe' ),
                'fair'      => __( 'Fair', 'aqualuxe' ),
                'poor'      => __( 'Poor', 'aqualuxe' ),
            );
            
            if ( isset( $condition_labels[ $condition ] ) ) {
                echo esc_html( $condition_labels[ $condition ] );
            } else {
                echo '—';
            }
            break;
            
        case 'value':
            $trade_value = get_post_meta( $post_id, '_trade_in_value', true );
            $store_credit = get_post_meta( $post_id, '_trade_in_store_credit', true );
            $cash_value = get_post_meta( $post_id, '_trade_in_cash_value', true );
            
            if ( $trade_value ) {
                echo '<strong>' . esc_html__( 'Base:', 'aqualuxe' ) . '</strong> ' . esc_html( '$' . number_format( $trade_value, 2 ) );
                
                if ( $store_credit ) {
                    echo '<br><strong>' . esc_html__( 'Credit:', 'aqualuxe' ) . '</strong> ' . esc_html( '$' . number_format( $store_credit, 2 ) );
                }
                
                if ( $cash_value ) {
                    echo '<br><strong>' . esc_html__( 'Cash:', 'aqualuxe' ) . '</strong> ' . esc_html( '$' . number_format( $cash_value, 2 ) );
                }
            } else {
                echo '—';
            }
            break;
            
        case 'status':
            $terms = get_the_terms( $post_id, 'trade_in_status' );
            
            if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
                $status = $terms[0]->name;
                $slug = $terms[0]->slug;
                
                $status_colors = array(
                    'available' => '#5cb85c',
                    'pending'   => '#f0ad4e',
                    'traded'    => '#5bc0de',
                    'rejected'  => '#d9534f',
                );
                
                $color = isset( $status_colors[ $slug ] ) ? $status_colors[ $slug ] : '#777777';
                
                echo '<span style="display: inline-block; padding: 3px 8px; border-radius: 3px; background-color: ' . esc_attr( $color ) . '; color: #fff;">' . esc_html( $status ) . '</span>';
            } else {
                echo '—';
            }
            break;
    }
}
add_action( 'manage_aqualuxe_trade_in_posts_custom_column', 'aqualuxe_trade_in_custom_column', 10, 2 );

/**
 * Add custom columns to the trade-in request list table
 */
function aqualuxe_trade_in_request_columns( $columns ) {
    $new_columns = array();
    
    // Add checkbox and title at the beginning
    if ( isset( $columns['cb'] ) ) {
        $new_columns['cb'] = $columns['cb'];
    }
    
    if ( isset( $columns['title'] ) ) {
        $new_columns['title'] = __( 'Request ID', 'aqualuxe' );
    }
    
    // Add custom columns
    $new_columns['customer'] = __( 'Customer', 'aqualuxe' );
    $new_columns['item'] = __( 'Item', 'aqualuxe' );
    $new_columns['value'] = __( 'Estimated Value', 'aqualuxe' );
    $new_columns['status'] = __( 'Status', 'aqualuxe' );
    
    // Add date at the end
    if ( isset( $columns['date'] ) ) {
        $new_columns['date'] = $columns['date'];
    }
    
    return $new_columns;
}
add_filter( 'manage_trade_in_request_posts_columns', 'aqualuxe_trade_in_request_columns' );

/**
 * Display data in custom columns for the trade-in request list table
 */
function aqualuxe_trade_in_request_custom_column( $column, $post_id ) {
    switch ( $column ) {
        case 'customer':
            $customer_name = get_post_meta( $post_id, '_trade_in_request_customer_name', true );
            $customer_email = get_post_meta( $post_id, '_trade_in_request_customer_email', true );
            $customer_user_id = get_post_meta( $post_id, '_trade_in_request_customer_user_id', true );
            
            if ( $customer_name ) {
                echo esc_html( $customer_name );
                
                if ( $customer_email ) {
                    echo '<br><a href="mailto:' . esc_attr( $customer_email ) . '">' . esc_html( $customer_email ) . '</a>';
                }
                
                if ( $customer_user_id ) {
                    $user = get_user_by( 'id', $customer_user_id );
                    if ( $user ) {
                        echo '<br><small>' . esc_html__( 'User:', 'aqualuxe' ) . ' <a href="' . esc_url( get_edit_user_link( $customer_user_id ) ) . '">' . esc_html( $user->user_login ) . '</a></small>';
                    }
                }
            } else {
                echo '—';
            }
            break;
            
        case 'item':
            $item_name = get_post_meta( $post_id, '_trade_in_request_item_name', true );
            $item_type = get_post_meta( $post_id, '_trade_in_request_item_type', true );
            $item_condition = get_post_meta( $post_id, '_trade_in_request_item_condition', true );
            
            if ( $item_name ) {
                echo esc_html( $item_name );
                
                $type_labels = array(
                    'fish'      => __( 'Fish', 'aqualuxe' ),
                    'equipment' => __( 'Equipment', 'aqualuxe' ),
                    'aquarium'  => __( 'Aquarium', 'aqualuxe' ),
                    'accessory' => __( 'Accessory', 'aqualuxe' ),
                    'other'     => __( 'Other', 'aqualuxe' ),
                );
                
                $condition_labels = array(
                    'new'       => __( 'New', 'aqualuxe' ),
                    'like-new'  => __( 'Like New', 'aqualuxe' ),
                    'excellent' => __( 'Excellent', 'aqualuxe' ),
                    'good'      => __( 'Good', 'aqualuxe' ),
                    'fair'      => __( 'Fair', 'aqualuxe' ),
                    'poor'      => __( 'Poor', 'aqualuxe' ),
                );
                
                if ( isset( $type_labels[ $item_type ] ) ) {
                    echo '<br><small>' . esc_html__( 'Type:', 'aqualuxe' ) . ' ' . esc_html( $type_labels[ $item_type ] ) . '</small>';
                }
                
                if ( isset( $condition_labels[ $item_condition ] ) ) {
                    echo '<br><small>' . esc_html__( 'Condition:', 'aqualuxe' ) . ' ' . esc_html( $condition_labels[ $item_condition ] ) . '</small>';
                }
            } else {
                echo '—';
            }
            break;
            
        case 'value':
            $estimated_value = get_post_meta( $post_id, '_trade_in_request_estimated_value', true );
            
            if ( $estimated_value ) {
                echo esc_html( '$' . number_format( $estimated_value, 2 ) );
            } else {
                echo '—';
            }
            break;
            
        case 'status':
            $status = get_post_meta( $post_id, '_trade_in_request_status', true );
            
            $status_labels = array(
                'new'       => __( 'New', 'aqualuxe' ),
                'reviewing' => __( 'Reviewing', 'aqualuxe' ),
                'approved'  => __( 'Approved', 'aqualuxe' ),
                'rejected'  => __( 'Rejected', 'aqualuxe' ),
                'completed' => __( 'Completed', 'aqualuxe' ),
            );
            
            $status_colors = array(
                'new'       => '#5bc0de',
                'reviewing' => '#f0ad4e',
                'approved'  => '#5cb85c',
                'rejected'  => '#d9534f',
                'completed' => '#777777',
            );
            
            if ( isset( $status_labels[ $status ] ) ) {
                $color = isset( $status_colors[ $status ] ) ? $status_colors[ $status ] : '#777777';
                
                echo '<span style="display: inline-block; padding: 3px 8px; border-radius: 3px; background-color: ' . esc_attr( $color ) . '; color: #fff;">' . esc_html( $status_labels[ $status ] ) . '</span>';
            } else {
                echo '—';
            }
            break;
    }
}
add_action( 'manage_trade_in_request_posts_custom_column', 'aqualuxe_trade_in_request_custom_column', 10, 2 );

/**
 * AJAX handler for submitting trade-in request
 */
function aqualuxe_ajax_submit_trade_in_request() {
    // Check nonce
    if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'aqualuxe_ajax_nonce' ) ) {
        wp_send_json_error( array( 'message' => __( 'Security check failed.', 'aqualuxe' ) ) );
    }
    
    // Check required fields
    $required_fields = array(
        'item_name'     => __( 'Item Name', 'aqualuxe' ),
        'item_type'     => __( 'Item Type', 'aqualuxe' ),
        'item_condition' => __( 'Item Condition', 'aqualuxe' ),
        'customer_name' => __( 'Your Name', 'aqualuxe' ),
        'customer_email' => __( 'Your Email', 'aqualuxe' ),
    );
    
    foreach ( $required_fields as $field => $label ) {
        if ( empty( $_POST[ $field ] ) ) {
            wp_send_json_error( array( 'message' => sprintf( __( '%s is required.', 'aqualuxe' ), $label ) ) );
        }
    }
    
    // Validate email
    if ( ! is_email( $_POST['customer_email'] ) ) {
        wp_send_json_error( array( 'message' => __( 'Please enter a valid email address.', 'aqualuxe' ) ) );
    }
    
    // Create trade-in request
    $request_data = array(
        'post_title'   => sprintf( __( 'Trade-In Request - %s', 'aqualuxe' ), sanitize_text_field( $_POST['item_name'] ) ),
        'post_content' => '',
        'post_status'  => 'publish',
        'post_type'    => 'trade_in_request',
    );
    
    $request_id = wp_insert_post( $request_data );
    
    if ( is_wp_error( $request_id ) ) {
        wp_send_json_error( array( 'message' => __( 'Failed to create trade-in request. Please try again.', 'aqualuxe' ) ) );
    }
    
    // Save request details
    update_post_meta( $request_id, '_trade_in_request_date', current_time( 'mysql' ) );
    update_post_meta( $request_id, '_trade_in_request_preferred_value', sanitize_text_field( $_POST['preferred_value'] ) );
    update_post_meta( $request_id, '_trade_in_request_notes', sanitize_textarea_field( $_POST['notes'] ) );
    
    // Save item information
    update_post_meta( $request_id, '_trade_in_request_item_type', sanitize_text_field( $_POST['item_type'] ) );
    update_post_meta( $request_id, '_trade_in_request_item_name', sanitize_text_field( $_POST['item_name'] ) );
    update_post_meta( $request_id, '_trade_in_request_item_condition', sanitize_text_field( $_POST['item_condition'] ) );
    update_post_meta( $request_id, '_trade_in_request_item_age', sanitize_text_field( $_POST['item_age'] ) );
    update_post_meta( $request_id, '_trade_in_request_item_description', sanitize_textarea_field( $_POST['item_description'] ) );
    
    // Save customer information
    update_post_meta( $request_id, '_trade_in_request_customer_name', sanitize_text_field( $_POST['customer_name'] ) );
    update_post_meta( $request_id, '_trade_in_request_customer_email', sanitize_email( $_POST['customer_email'] ) );
    update_post_meta( $request_id, '_trade_in_request_customer_phone', sanitize_text_field( $_POST['customer_phone'] ) );
    
    // If user is logged in, save user ID
    if ( is_user_logged_in() ) {
        update_post_meta( $request_id, '_trade_in_request_customer_user_id', get_current_user_id() );
    }
    
    // Set initial status
    update_post_meta( $request_id, '_trade_in_request_status', 'new' );
    
    // Initialize status log
    $log = array(
        array(
            'status'    => 'new',
            'timestamp' => current_time( 'mysql' ),
            'user_id'   => get_current_user_id(),
        ),
    );
    
    update_post_meta( $request_id, '_trade_in_request_status_log', $log );
    
    // Send confirmation email
    aqualuxe_send_trade_in_request_confirmation( $request_id );
    
    // Send admin notification
    aqualuxe_send_trade_in_request_admin_notification( $request_id );
    
    // Send success response
    wp_send_json_success( array(
        'message'    => __( 'Your trade-in request has been submitted successfully. We will contact you shortly to discuss your item.', 'aqualuxe' ),
        'request_id' => $request_id,
    ) );
}
add_action( 'wp_ajax_aqualuxe_submit_trade_in_request', 'aqualuxe_ajax_submit_trade_in_request' );
add_action( 'wp_ajax_nopriv_aqualuxe_submit_trade_in_request', 'aqualuxe_ajax_submit_trade_in_request' );

/**
 * Send trade-in request confirmation email to customer
 * 
 * @param int $request_id Request post ID
 */
function aqualuxe_send_trade_in_request_confirmation( $request_id ) {
    $customer_name = get_post_meta( $request_id, '_trade_in_request_customer_name', true );
    $customer_email = get_post_meta( $request_id, '_trade_in_request_customer_email', true );
    $item_name = get_post_meta( $request_id, '_trade_in_request_item_name', true );
    $item_type = get_post_meta( $request_id, '_trade_in_request_item_type', true );
    
    $subject = sprintf( __( 'Your Trade-In Request - %s', 'aqualuxe' ), get_bloginfo( 'name' ) );
    
    $message = sprintf(
        __( 'Dear %s,', 'aqualuxe' ),
        $customer_name
    ) . "\n\n";
    
    $message .= __( 'Thank you for submitting a trade-in request. Your request has been received and is being reviewed by our team.', 'aqualuxe' ) . "\n\n";
    
    $message .= __( 'Request Details:', 'aqualuxe' ) . "\n";
    $message .= sprintf( __( 'Request ID: %s', 'aqualuxe' ), $request_id ) . "\n";
    $message .= sprintf( __( 'Item: %s', 'aqualuxe' ), $item_name ) . "\n";
    
    $type_labels = array(
        'fish'      => __( 'Fish', 'aqualuxe' ),
        'equipment' => __( 'Equipment', 'aqualuxe' ),
        'aquarium'  => __( 'Aquarium', 'aqualuxe' ),
        'accessory' => __( 'Accessory', 'aqualuxe' ),
        'other'     => __( 'Other', 'aqualuxe' ),
    );
    
    if ( isset( $type_labels[ $item_type ] ) ) {
        $message .= sprintf( __( 'Type: %s', 'aqualuxe' ), $type_labels[ $item_type ] ) . "\n";
    }
    
    $message .= sprintf( __( 'Date: %s', 'aqualuxe' ), date_i18n( get_option( 'date_format' ) ) ) . "\n\n";
    
    $message .= __( 'Next Steps:', 'aqualuxe' ) . "\n";
    $message .= __( '1. Our team will review your trade-in request.', 'aqualuxe' ) . "\n";
    $message .= __( '2. We may contact you for additional information or to schedule an inspection.', 'aqualuxe' ) . "\n";
    $message .= __( '3. Once reviewed, we will provide you with a trade-in value estimate.', 'aqualuxe' ) . "\n";
    $message .= __( '4. If you accept our offer, we will arrange for the trade-in process.', 'aqualuxe' ) . "\n\n";
    
    $message .= __( 'If you have any questions, please contact us.', 'aqualuxe' ) . "\n\n";
    
    $message .= sprintf(
        __( 'Best regards,', 'aqualuxe' ) . "\n%s",
        get_bloginfo( 'name' )
    );
    
    $headers = array( 'Content-Type: text/plain; charset=UTF-8' );
    
    wp_mail( $customer_email, $subject, $message, $headers );
}

/**
 * Send trade-in request notification email to admin
 * 
 * @param int $request_id Request post ID
 */
function aqualuxe_send_trade_in_request_admin_notification( $request_id ) {
    $customer_name = get_post_meta( $request_id, '_trade_in_request_customer_name', true );
    $customer_email = get_post_meta( $request_id, '_trade_in_request_customer_email', true );
    $customer_phone = get_post_meta( $request_id, '_trade_in_request_customer_phone', true );
    $item_name = get_post_meta( $request_id, '_trade_in_request_item_name', true );
    $item_type = get_post_meta( $request_id, '_trade_in_request_item_type', true );
    $item_condition = get_post_meta( $request_id, '_trade_in_request_item_condition', true );
    $item_description = get_post_meta( $request_id, '_trade_in_request_item_description', true );
    
    $admin_email = get_option( 'admin_email' );
    $subject = sprintf( __( 'New Trade-In Request: %s', 'aqualuxe' ), $item_name );
    
    $message = __( 'A new trade-in request has been submitted. Details are as follows:', 'aqualuxe' ) . "\n\n";
    
    $message .= __( 'Request Details:', 'aqualuxe' ) . "\n";
    $message .= sprintf( __( 'Request ID: %s', 'aqualuxe' ), $request_id ) . "\n";
    $message .= sprintf( __( 'Date: %s', 'aqualuxe' ), date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ) ) ) . "\n\n";
    
    $message .= __( 'Customer Information:', 'aqualuxe' ) . "\n";
    $message .= sprintf( __( 'Name: %s', 'aqualuxe' ), $customer_name ) . "\n";
    $message .= sprintf( __( 'Email: %s', 'aqualuxe' ), $customer_email ) . "\n";
    
    if ( $customer_phone ) {
        $message .= sprintf( __( 'Phone: %s', 'aqualuxe' ), $customer_phone ) . "\n";
    }
    
    $message .= "\n";
    
    $message .= __( 'Item Information:', 'aqualuxe' ) . "\n";
    $message .= sprintf( __( 'Name: %s', 'aqualuxe' ), $item_name ) . "\n";
    
    $type_labels = array(
        'fish'      => __( 'Fish', 'aqualuxe' ),
        'equipment' => __( 'Equipment', 'aqualuxe' ),
        'aquarium'  => __( 'Aquarium', 'aqualuxe' ),
        'accessory' => __( 'Accessory', 'aqualuxe' ),
        'other'     => __( 'Other', 'aqualuxe' ),
    );
    
    if ( isset( $type_labels[ $item_type ] ) ) {
        $message .= sprintf( __( 'Type: %s', 'aqualuxe' ), $type_labels[ $item_type ] ) . "\n";
    }
    
    $condition_labels = array(
        'new'       => __( 'New', 'aqualuxe' ),
        'like-new'  => __( 'Like New', 'aqualuxe' ),
        'excellent' => __( 'Excellent', 'aqualuxe' ),
        'good'      => __( 'Good', 'aqualuxe' ),
        'fair'      => __( 'Fair', 'aqualuxe' ),
        'poor'      => __( 'Poor', 'aqualuxe' ),
    );
    
    if ( isset( $condition_labels[ $item_condition ] ) ) {
        $message .= sprintf( __( 'Condition: %s', 'aqualuxe' ), $condition_labels[ $item_condition ] ) . "\n";
    }
    
    if ( $item_description ) {
        $message .= sprintf( __( 'Description: %s', 'aqualuxe' ), $item_description ) . "\n";
    }
    
    $message .= "\n";
    
    $message .= sprintf(
        __( 'To manage this request, please visit: %s', 'aqualuxe' ),
        admin_url( 'post.php?post=' . $request_id . '&action=edit' )
    );
    
    $headers = array( 'Content-Type: text/plain; charset=UTF-8' );
    
    wp_mail( $admin_email, $subject, $message, $headers );
}

/**
 * AJAX handler for notifying trade-in customer
 */
function aqualuxe_ajax_notify_trade_in_customer() {
    // Check if user is admin
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_send_json_error( array( 'message' => __( 'You do not have permission to perform this action.', 'aqualuxe' ) ) );
    }
    
    // Check nonce
    if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'aqualuxe_notify_trade_in_customer' ) ) {
        wp_send_json_error( array( 'message' => __( 'Security check failed.', 'aqualuxe' ) ) );
    }
    
    // Check request ID
    if ( ! isset( $_POST['request_id'] ) ) {
        wp_send_json_error( array( 'message' => __( 'Missing request ID.', 'aqualuxe' ) ) );
    }
    
    $request_id = intval( $_POST['request_id'] );
    
    // Check if request exists
    $request = get_post( $request_id );
    
    if ( ! $request || 'trade_in_request' !== $request->post_type ) {
        wp_send_json_error( array( 'message' => __( 'Invalid trade-in request.', 'aqualuxe' ) ) );
    }
    
    // Get request status
    $status = get_post_meta( $request_id, '_trade_in_request_status', true );
    
    // Send notification based on status
    switch ( $status ) {
        case 'reviewing':
            aqualuxe_send_trade_in_request_reviewing_notification( $request_id );
            break;
            
        case 'approved':
            aqualuxe_send_trade_in_request_approved_notification( $request_id );
            break;
            
        case 'rejected':
            aqualuxe_send_trade_in_request_rejected_notification( $request_id );
            break;
            
        case 'completed':
            aqualuxe_send_trade_in_request_completed_notification( $request_id );
            break;
            
        default:
            wp_send_json_error( array( 'message' => __( 'Invalid request status.', 'aqualuxe' ) ) );
            break;
    }
    
    // Send success response
    wp_send_json_success( array( 'message' => __( 'Notification sent successfully.', 'aqualuxe' ) ) );
}
add_action( 'wp_ajax_aqualuxe_notify_trade_in_customer', 'aqualuxe_ajax_notify_trade_in_customer' );

/**
 * Send trade-in request reviewing notification to customer
 * 
 * @param int $request_id Request post ID
 */
function aqualuxe_send_trade_in_request_reviewing_notification( $request_id ) {
    $customer_name = get_post_meta( $request_id, '_trade_in_request_customer_name', true );
    $customer_email = get_post_meta( $request_id, '_trade_in_request_customer_email', true );
    $item_name = get_post_meta( $request_id, '_trade_in_request_item_name', true );
    
    $subject = sprintf( __( 'Trade-In Request Update - %s', 'aqualuxe' ), get_bloginfo( 'name' ) );
    
    $message = sprintf(
        __( 'Dear %s,', 'aqualuxe' ),
        $customer_name
    ) . "\n\n";
    
    $message .= __( 'We are currently reviewing your trade-in request for the following item:', 'aqualuxe' ) . "\n\n";
    
    $message .= sprintf( __( 'Item: %s', 'aqualuxe' ), $item_name ) . "\n";
    $message .= sprintf( __( 'Request ID: %s', 'aqualuxe' ), $request_id ) . "\n\n";
    
    $message .= __( 'Our team is evaluating your item and will provide you with a trade-in value estimate soon. This process typically takes 1-3 business days.', 'aqualuxe' ) . "\n\n";
    
    $message .= __( 'If we need additional information or have questions about your item, we will contact you directly.', 'aqualuxe' ) . "\n\n";
    
    $message .= __( 'Thank you for your patience.', 'aqualuxe' ) . "\n\n";
    
    $message .= sprintf(
        __( 'Best regards,', 'aqualuxe' ) . "\n%s",
        get_bloginfo( 'name' )
    );
    
    $headers = array( 'Content-Type: text/plain; charset=UTF-8' );
    
    wp_mail( $customer_email, $subject, $message, $headers );
}

/**
 * Send trade-in request approved notification to customer
 * 
 * @param int $request_id Request post ID
 */
function aqualuxe_send_trade_in_request_approved_notification( $request_id ) {
    $customer_name = get_post_meta( $request_id, '_trade_in_request_customer_name', true );
    $customer_email = get_post_meta( $request_id, '_trade_in_request_customer_email', true );
    $item_name = get_post_meta( $request_id, '_trade_in_request_item_name', true );
    $estimated_value = get_post_meta( $request_id, '_trade_in_request_estimated_value', true );
    $preferred_value = get_post_meta( $request_id, '_trade_in_request_preferred_value', true );
    
    $subject = sprintf( __( 'Trade-In Request Approved - %s', 'aqualuxe' ), get_bloginfo( 'name' ) );
    
    $message = sprintf(
        __( 'Dear %s,', 'aqualuxe' ),
        $customer_name
    ) . "\n\n";
    
    $message .= __( 'Good news! We have approved your trade-in request for the following item:', 'aqualuxe' ) . "\n\n";
    
    $message .= sprintf( __( 'Item: %s', 'aqualuxe' ), $item_name ) . "\n";
    $message .= sprintf( __( 'Request ID: %s', 'aqualuxe' ), $request_id ) . "\n\n";
    
    if ( $estimated_value ) {
        $message .= sprintf( __( 'Estimated Value: $%s', 'aqualuxe' ), number_format( $estimated_value, 2 ) ) . "\n\n";
    }
    
    $message .= __( 'Next Steps:', 'aqualuxe' ) . "\n";
    $message .= __( '1. Please bring your item to our store for final inspection.', 'aqualuxe' ) . "\n";
    $message .= __( '2. Our staff will verify the condition and details of your item.', 'aqualuxe' ) . "\n";
    $message .= __( '3. Once verified, you can choose to receive store credit or cash value.', 'aqualuxe' ) . "\n\n";
    
    $message .= __( 'Store Location:', 'aqualuxe' ) . "\n";
    $message .= get_bloginfo( 'name' ) . "\n";
    $message .= get_option( 'aqualuxe_store_address', '123 Main Street, Anytown, USA' ) . "\n";
    $message .= get_option( 'aqualuxe_store_phone', '(555) 123-4567' ) . "\n\n";
    
    $message .= __( 'Store Hours:', 'aqualuxe' ) . "\n";
    $message .= get_option( 'aqualuxe_store_hours', 'Monday - Friday: 9:00 AM - 6:00 PM, Saturday: 10:00 AM - 5:00 PM, Sunday: Closed' ) . "\n\n";
    
    $message .= __( 'Please bring this email or your request ID when you visit our store.', 'aqualuxe' ) . "\n\n";
    
    $message .= __( 'If you have any questions, please contact us.', 'aqualuxe' ) . "\n\n";
    
    $message .= sprintf(
        __( 'Best regards,', 'aqualuxe' ) . "\n%s",
        get_bloginfo( 'name' )
    );
    
    $headers = array( 'Content-Type: text/plain; charset=UTF-8' );
    
    wp_mail( $customer_email, $subject, $message, $headers );
}

/**
 * Send trade-in request rejected notification to customer
 * 
 * @param int $request_id Request post ID
 */
function aqualuxe_send_trade_in_request_rejected_notification( $request_id ) {
    $customer_name = get_post_meta( $request_id, '_trade_in_request_customer_name', true );
    $customer_email = get_post_meta( $request_id, '_trade_in_request_customer_email', true );
    $item_name = get_post_meta( $request_id, '_trade_in_request_item_name', true );
    $admin_notes = get_post_meta( $request_id, '_trade_in_request_admin_notes', true );
    
    $subject = sprintf( __( 'Trade-In Request Update - %s', 'aqualuxe' ), get_bloginfo( 'name' ) );
    
    $message = sprintf(
        __( 'Dear %s,', 'aqualuxe' ),
        $customer_name
    ) . "\n\n";
    
    $message .= __( 'Thank you for submitting your trade-in request. After careful consideration, we regret to inform you that we are unable to accept the following item for trade-in at this time:', 'aqualuxe' ) . "\n\n";
    
    $message .= sprintf( __( 'Item: %s', 'aqualuxe' ), $item_name ) . "\n";
    $message .= sprintf( __( 'Request ID: %s', 'aqualuxe' ), $request_id ) . "\n\n";
    
    if ( $admin_notes ) {
        $message .= __( 'Reason:', 'aqualuxe' ) . "\n";
        $message .= $admin_notes . "\n\n";
    }
    
    $message .= __( 'We appreciate your interest in our trade-in program and encourage you to consider other items that may better fit our current inventory needs.', 'aqualuxe' ) . "\n\n";
    
    $message .= __( 'If you have any questions or would like to discuss this further, please feel free to contact us.', 'aqualuxe' ) . "\n\n";
    
    $message .= sprintf(
        __( 'Best regards,', 'aqualuxe' ) . "\n%s",
        get_bloginfo( 'name' )
    );
    
    $headers = array( 'Content-Type: text/plain; charset=UTF-8' );
    
    wp_mail( $customer_email, $subject, $message, $headers );
}

/**
 * Send trade-in request completed notification to customer
 * 
 * @param int $request_id Request post ID
 */
function aqualuxe_send_trade_in_request_completed_notification( $request_id ) {
    $customer_name = get_post_meta( $request_id, '_trade_in_request_customer_name', true );
    $customer_email = get_post_meta( $request_id, '_trade_in_request_customer_email', true );
    $item_name = get_post_meta( $request_id, '_trade_in_request_item_name', true );
    $estimated_value = get_post_meta( $request_id, '_trade_in_request_estimated_value', true );
    
    $subject = sprintf( __( 'Trade-In Request Completed - %s', 'aqualuxe' ), get_bloginfo( 'name' ) );
    
    $message = sprintf(
        __( 'Dear %s,', 'aqualuxe' ),
        $customer_name
    ) . "\n\n";
    
    $message .= __( 'Thank you for completing your trade-in with us. We have processed the following trade-in:', 'aqualuxe' ) . "\n\n";
    
    $message .= sprintf( __( 'Item: %s', 'aqualuxe' ), $item_name ) . "\n";
    $message .= sprintf( __( 'Request ID: %s', 'aqualuxe' ), $request_id ) . "\n";
    
    if ( $estimated_value ) {
        $message .= sprintf( __( 'Trade-In Value: $%s', 'aqualuxe' ), number_format( $estimated_value, 2 ) ) . "\n";
    }
    
    $message .= sprintf( __( 'Date Completed: %s', 'aqualuxe' ), date_i18n( get_option( 'date_format' ) ) ) . "\n\n";
    
    $message .= __( 'We appreciate your business and hope you enjoy your new purchase or store credit. If you have any questions about your trade-in or would like to explore other items in our inventory, please visit our store or contact us.', 'aqualuxe' ) . "\n\n";
    
    $message .= __( 'We look forward to serving you again in the future.', 'aqualuxe' ) . "\n\n";
    
    $message .= sprintf(
        __( 'Best regards,', 'aqualuxe' ) . "\n%s",
        get_bloginfo( 'name' )
    );
    
    $headers = array( 'Content-Type: text/plain; charset=UTF-8' );
    
    wp_mail( $customer_email, $subject, $message, $headers );
}

/**
 * Add trade-in system scripts and styles
 */
function aqualuxe_enqueue_trade_in_scripts() {
    // Only enqueue on trade-in pages
    if ( is_singular( 'aqualuxe_trade_in' ) || is_post_type_archive( 'aqualuxe_trade_in' ) || is_tax( 'trade_in_category' ) || is_page_template( 'page-trade-in-request.php' ) ) {
        // Enqueue trade-in script
        wp_enqueue_script(
            'aqualuxe-trade-in',
            AQUALUXE_URI . 'assets/js/trade-in.js',
            array( 'jquery' ),
            AQUALUXE_VERSION,
            true
        );
        
        // Localize script with settings
        wp_localize_script(
            'aqualuxe-trade-in',
            'aqualuxeTradeInSettings',
            array(
                'ajaxUrl' => admin_url( 'admin-ajax.php' ),
                'nonce'   => wp_create_nonce( 'aqualuxe_ajax_nonce' ),
            )
        );
    }
}
add_action( 'wp_enqueue_scripts', 'aqualuxe_enqueue_trade_in_scripts' );