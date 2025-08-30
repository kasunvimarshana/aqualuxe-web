<?php
/**
 * AquaLuxe Franchise Inquiry System
 *
 * Handles franchise inquiry functionality including form submission,
 * admin management, and email notifications.
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Register Franchise Inquiry custom post type
 */
function aqualuxe_register_franchise_inquiry_post_type() {
    $labels = array(
        'name'                  => _x( 'Franchise Inquiries', 'Post type general name', 'aqualuxe' ),
        'singular_name'         => _x( 'Franchise Inquiry', 'Post type singular name', 'aqualuxe' ),
        'menu_name'             => _x( 'Franchise Inquiries', 'Admin Menu text', 'aqualuxe' ),
        'name_admin_bar'        => _x( 'Franchise Inquiry', 'Add New on Toolbar', 'aqualuxe' ),
        'add_new'               => __( 'Add New', 'aqualuxe' ),
        'add_new_item'          => __( 'Add New Franchise Inquiry', 'aqualuxe' ),
        'new_item'              => __( 'New Franchise Inquiry', 'aqualuxe' ),
        'edit_item'             => __( 'Edit Franchise Inquiry', 'aqualuxe' ),
        'view_item'             => __( 'View Franchise Inquiry', 'aqualuxe' ),
        'all_items'             => __( 'All Franchise Inquiries', 'aqualuxe' ),
        'search_items'          => __( 'Search Franchise Inquiries', 'aqualuxe' ),
        'not_found'             => __( 'No franchise inquiries found.', 'aqualuxe' ),
        'not_found_in_trash'    => __( 'No franchise inquiries found in Trash.', 'aqualuxe' ),
        'archives'              => _x( 'Franchise Inquiry Archives', 'The post type archive label used in nav menus', 'aqualuxe' ),
        'filter_items_list'     => _x( 'Filter franchise inquiries list', 'Screen reader text for the filter links', 'aqualuxe' ),
        'items_list_navigation' => _x( 'Franchise inquiries list navigation', 'Screen reader text for the pagination', 'aqualuxe' ),
        'items_list'            => _x( 'Franchise inquiries list', 'Screen reader text for the items list', 'aqualuxe' ),
    );

    $args = array(
        'labels'             => $labels,
        'public'             => false,
        'publicly_queryable' => false,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'franchise-inquiry' ),
        'capability_type'    => 'post',
        'has_archive'        => false,
        'hierarchical'       => false,
        'menu_position'      => 23,
        'menu_icon'          => 'dashicons-store',
        'supports'           => array( 'title' ),
    );

    register_post_type( 'franchise_inquiry', $args );
}
add_action( 'init', 'aqualuxe_register_franchise_inquiry_post_type' );

/**
 * Register Franchise Inquiry Status taxonomy
 */
function aqualuxe_register_franchise_inquiry_status_taxonomy() {
    $labels = array(
        'name'              => _x( 'Inquiry Statuses', 'taxonomy general name', 'aqualuxe' ),
        'singular_name'     => _x( 'Inquiry Status', 'taxonomy singular name', 'aqualuxe' ),
        'search_items'      => __( 'Search Inquiry Statuses', 'aqualuxe' ),
        'all_items'         => __( 'All Inquiry Statuses', 'aqualuxe' ),
        'parent_item'       => __( 'Parent Inquiry Status', 'aqualuxe' ),
        'parent_item_colon' => __( 'Parent Inquiry Status:', 'aqualuxe' ),
        'edit_item'         => __( 'Edit Inquiry Status', 'aqualuxe' ),
        'update_item'       => __( 'Update Inquiry Status', 'aqualuxe' ),
        'add_new_item'      => __( 'Add New Inquiry Status', 'aqualuxe' ),
        'new_item_name'     => __( 'New Inquiry Status Name', 'aqualuxe' ),
        'menu_name'         => __( 'Statuses', 'aqualuxe' ),
    );

    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'inquiry-status' ),
    );

    register_taxonomy( 'inquiry_status', array( 'franchise_inquiry' ), $args );
    
    // Register default inquiry statuses
    if ( ! term_exists( 'new', 'inquiry_status' ) ) {
        wp_insert_term( 'New', 'inquiry_status', array( 'slug' => 'new' ) );
    }
    
    if ( ! term_exists( 'in-review', 'inquiry_status' ) ) {
        wp_insert_term( 'In Review', 'inquiry_status', array( 'slug' => 'in-review' ) );
    }
    
    if ( ! term_exists( 'qualified', 'inquiry_status' ) ) {
        wp_insert_term( 'Qualified', 'inquiry_status', array( 'slug' => 'qualified' ) );
    }
    
    if ( ! term_exists( 'not-qualified', 'inquiry_status' ) ) {
        wp_insert_term( 'Not Qualified', 'inquiry_status', array( 'slug' => 'not-qualified' ) );
    }
    
    if ( ! term_exists( 'in-progress', 'inquiry_status' ) ) {
        wp_insert_term( 'In Progress', 'inquiry_status', array( 'slug' => 'in-progress' ) );
    }
    
    if ( ! term_exists( 'completed', 'inquiry_status' ) ) {
        wp_insert_term( 'Completed', 'inquiry_status', array( 'slug' => 'completed' ) );
    }
}
add_action( 'init', 'aqualuxe_register_franchise_inquiry_status_taxonomy' );

/**
 * Register meta boxes for the franchise inquiry post type
 */
function aqualuxe_register_franchise_inquiry_meta_boxes() {
    add_meta_box(
        'aqualuxe_franchise_inquiry_details',
        __( 'Inquiry Details', 'aqualuxe' ),
        'aqualuxe_franchise_inquiry_details_meta_box_callback',
        'franchise_inquiry',
        'normal',
        'high'
    );
    
    add_meta_box(
        'aqualuxe_franchise_inquiry_contact',
        __( 'Contact Information', 'aqualuxe' ),
        'aqualuxe_franchise_inquiry_contact_meta_box_callback',
        'franchise_inquiry',
        'normal',
        'default'
    );
    
    add_meta_box(
        'aqualuxe_franchise_inquiry_business',
        __( 'Business Information', 'aqualuxe' ),
        'aqualuxe_franchise_inquiry_business_meta_box_callback',
        'franchise_inquiry',
        'normal',
        'default'
    );
    
    add_meta_box(
        'aqualuxe_franchise_inquiry_financial',
        __( 'Financial Information', 'aqualuxe' ),
        'aqualuxe_franchise_inquiry_financial_meta_box_callback',
        'franchise_inquiry',
        'normal',
        'default'
    );
    
    add_meta_box(
        'aqualuxe_franchise_inquiry_status',
        __( 'Inquiry Status', 'aqualuxe' ),
        'aqualuxe_franchise_inquiry_status_meta_box_callback',
        'franchise_inquiry',
        'side',
        'default'
    );
}
add_action( 'add_meta_boxes', 'aqualuxe_register_franchise_inquiry_meta_boxes' );

/**
 * Franchise Inquiry Details meta box callback
 */
function aqualuxe_franchise_inquiry_details_meta_box_callback( $post ) {
    // Add nonce for security
    wp_nonce_field( 'aqualuxe_franchise_inquiry_details_nonce', 'franchise_inquiry_details_nonce' );
    
    // Get saved values
    $inquiry_date = get_post_meta( $post->ID, '_franchise_inquiry_date', true );
    $inquiry_source = get_post_meta( $post->ID, '_franchise_inquiry_source', true );
    $inquiry_location = get_post_meta( $post->ID, '_franchise_inquiry_location', true );
    $inquiry_timeline = get_post_meta( $post->ID, '_franchise_inquiry_timeline', true );
    $inquiry_message = get_post_meta( $post->ID, '_franchise_inquiry_message', true );
    
    // Format date
    $formatted_date = $inquiry_date ? date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), strtotime( $inquiry_date ) ) : '';
    ?>
    <div class="franchise-inquiry-details-meta-box">
        <style>
            .franchise-inquiry-details-meta-box .form-field {
                margin-bottom: 15px;
            }
            .franchise-inquiry-details-meta-box label {
                display: block;
                font-weight: 600;
                margin-bottom: 5px;
            }
            .franchise-inquiry-details-meta-box input[type="text"],
            .franchise-inquiry-details-meta-box select,
            .franchise-inquiry-details-meta-box textarea {
                width: 100%;
                max-width: 400px;
            }
            .franchise-inquiry-details-meta-box .form-row {
                display: flex;
                flex-wrap: wrap;
                margin: 0 -10px;
            }
            .franchise-inquiry-details-meta-box .form-col {
                flex: 1;
                padding: 0 10px;
                min-width: 200px;
            }
        </style>
        
        <div class="form-row">
            <div class="form-col">
                <div class="form-field">
                    <label for="franchise_inquiry_date"><?php esc_html_e( 'Inquiry Date', 'aqualuxe' ); ?></label>
                    <input type="text" id="franchise_inquiry_date" name="franchise_inquiry_date" value="<?php echo esc_attr( $formatted_date ); ?>" readonly />
                </div>
                
                <div class="form-field">
                    <label for="franchise_inquiry_source"><?php esc_html_e( 'Inquiry Source', 'aqualuxe' ); ?></label>
                    <select id="franchise_inquiry_source" name="franchise_inquiry_source">
                        <option value=""><?php esc_html_e( 'Select Source', 'aqualuxe' ); ?></option>
                        <option value="website" <?php selected( $inquiry_source, 'website' ); ?>><?php esc_html_e( 'Website', 'aqualuxe' ); ?></option>
                        <option value="referral" <?php selected( $inquiry_source, 'referral' ); ?>><?php esc_html_e( 'Referral', 'aqualuxe' ); ?></option>
                        <option value="social-media" <?php selected( $inquiry_source, 'social-media' ); ?>><?php esc_html_e( 'Social Media', 'aqualuxe' ); ?></option>
                        <option value="trade-show" <?php selected( $inquiry_source, 'trade-show' ); ?>><?php esc_html_e( 'Trade Show', 'aqualuxe' ); ?></option>
                        <option value="advertisement" <?php selected( $inquiry_source, 'advertisement' ); ?>><?php esc_html_e( 'Advertisement', 'aqualuxe' ); ?></option>
                        <option value="other" <?php selected( $inquiry_source, 'other' ); ?>><?php esc_html_e( 'Other', 'aqualuxe' ); ?></option>
                    </select>
                </div>
            </div>
            
            <div class="form-col">
                <div class="form-field">
                    <label for="franchise_inquiry_location"><?php esc_html_e( 'Preferred Location', 'aqualuxe' ); ?></label>
                    <input type="text" id="franchise_inquiry_location" name="franchise_inquiry_location" value="<?php echo esc_attr( $inquiry_location ); ?>" />
                </div>
                
                <div class="form-field">
                    <label for="franchise_inquiry_timeline"><?php esc_html_e( 'Timeline', 'aqualuxe' ); ?></label>
                    <select id="franchise_inquiry_timeline" name="franchise_inquiry_timeline">
                        <option value=""><?php esc_html_e( 'Select Timeline', 'aqualuxe' ); ?></option>
                        <option value="0-3" <?php selected( $inquiry_timeline, '0-3' ); ?>><?php esc_html_e( '0-3 months', 'aqualuxe' ); ?></option>
                        <option value="3-6" <?php selected( $inquiry_timeline, '3-6' ); ?>><?php esc_html_e( '3-6 months', 'aqualuxe' ); ?></option>
                        <option value="6-12" <?php selected( $inquiry_timeline, '6-12' ); ?>><?php esc_html_e( '6-12 months', 'aqualuxe' ); ?></option>
                        <option value="12+" <?php selected( $inquiry_timeline, '12+' ); ?>><?php esc_html_e( '12+ months', 'aqualuxe' ); ?></option>
                    </select>
                </div>
            </div>
        </div>
        
        <div class="form-field">
            <label for="franchise_inquiry_message"><?php esc_html_e( 'Additional Message', 'aqualuxe' ); ?></label>
            <textarea id="franchise_inquiry_message" name="franchise_inquiry_message" rows="4"><?php echo esc_textarea( $inquiry_message ); ?></textarea>
        </div>
    </div>
    <?php
}

/**
 * Franchise Inquiry Contact meta box callback
 */
function aqualuxe_franchise_inquiry_contact_meta_box_callback( $post ) {
    // Add nonce for security
    wp_nonce_field( 'aqualuxe_franchise_inquiry_contact_nonce', 'franchise_inquiry_contact_nonce' );
    
    // Get saved values
    $first_name = get_post_meta( $post->ID, '_franchise_inquiry_first_name', true );
    $last_name = get_post_meta( $post->ID, '_franchise_inquiry_last_name', true );
    $email = get_post_meta( $post->ID, '_franchise_inquiry_email', true );
    $phone = get_post_meta( $post->ID, '_franchise_inquiry_phone', true );
    $address = get_post_meta( $post->ID, '_franchise_inquiry_address', true );
    $city = get_post_meta( $post->ID, '_franchise_inquiry_city', true );
    $state = get_post_meta( $post->ID, '_franchise_inquiry_state', true );
    $zip = get_post_meta( $post->ID, '_franchise_inquiry_zip', true );
    $country = get_post_meta( $post->ID, '_franchise_inquiry_country', true );
    ?>
    <div class="franchise-inquiry-contact-meta-box">
        <style>
            .franchise-inquiry-contact-meta-box .form-field {
                margin-bottom: 15px;
            }
            .franchise-inquiry-contact-meta-box label {
                display: block;
                font-weight: 600;
                margin-bottom: 5px;
            }
            .franchise-inquiry-contact-meta-box input[type="text"],
            .franchise-inquiry-contact-meta-box input[type="email"],
            .franchise-inquiry-contact-meta-box input[type="tel"],
            .franchise-inquiry-contact-meta-box select {
                width: 100%;
                max-width: 400px;
            }
            .franchise-inquiry-contact-meta-box .form-row {
                display: flex;
                flex-wrap: wrap;
                margin: 0 -10px;
            }
            .franchise-inquiry-contact-meta-box .form-col {
                flex: 1;
                padding: 0 10px;
                min-width: 200px;
            }
        </style>
        
        <div class="form-row">
            <div class="form-col">
                <div class="form-field">
                    <label for="franchise_inquiry_first_name"><?php esc_html_e( 'First Name', 'aqualuxe' ); ?></label>
                    <input type="text" id="franchise_inquiry_first_name" name="franchise_inquiry_first_name" value="<?php echo esc_attr( $first_name ); ?>" />
                </div>
                
                <div class="form-field">
                    <label for="franchise_inquiry_last_name"><?php esc_html_e( 'Last Name', 'aqualuxe' ); ?></label>
                    <input type="text" id="franchise_inquiry_last_name" name="franchise_inquiry_last_name" value="<?php echo esc_attr( $last_name ); ?>" />
                </div>
            </div>
            
            <div class="form-col">
                <div class="form-field">
                    <label for="franchise_inquiry_email"><?php esc_html_e( 'Email', 'aqualuxe' ); ?></label>
                    <input type="email" id="franchise_inquiry_email" name="franchise_inquiry_email" value="<?php echo esc_attr( $email ); ?>" />
                </div>
                
                <div class="form-field">
                    <label for="franchise_inquiry_phone"><?php esc_html_e( 'Phone', 'aqualuxe' ); ?></label>
                    <input type="tel" id="franchise_inquiry_phone" name="franchise_inquiry_phone" value="<?php echo esc_attr( $phone ); ?>" />
                </div>
            </div>
        </div>
        
        <div class="form-field">
            <label for="franchise_inquiry_address"><?php esc_html_e( 'Address', 'aqualuxe' ); ?></label>
            <input type="text" id="franchise_inquiry_address" name="franchise_inquiry_address" value="<?php echo esc_attr( $address ); ?>" />
        </div>
        
        <div class="form-row">
            <div class="form-col">
                <div class="form-field">
                    <label for="franchise_inquiry_city"><?php esc_html_e( 'City', 'aqualuxe' ); ?></label>
                    <input type="text" id="franchise_inquiry_city" name="franchise_inquiry_city" value="<?php echo esc_attr( $city ); ?>" />
                </div>
            </div>
            
            <div class="form-col">
                <div class="form-field">
                    <label for="franchise_inquiry_state"><?php esc_html_e( 'State/Province', 'aqualuxe' ); ?></label>
                    <input type="text" id="franchise_inquiry_state" name="franchise_inquiry_state" value="<?php echo esc_attr( $state ); ?>" />
                </div>
            </div>
            
            <div class="form-col">
                <div class="form-field">
                    <label for="franchise_inquiry_zip"><?php esc_html_e( 'ZIP/Postal Code', 'aqualuxe' ); ?></label>
                    <input type="text" id="franchise_inquiry_zip" name="franchise_inquiry_zip" value="<?php echo esc_attr( $zip ); ?>" />
                </div>
            </div>
            
            <div class="form-col">
                <div class="form-field">
                    <label for="franchise_inquiry_country"><?php esc_html_e( 'Country', 'aqualuxe' ); ?></label>
                    <input type="text" id="franchise_inquiry_country" name="franchise_inquiry_country" value="<?php echo esc_attr( $country ); ?>" />
                </div>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Franchise Inquiry Business meta box callback
 */
function aqualuxe_franchise_inquiry_business_meta_box_callback( $post ) {
    // Add nonce for security
    wp_nonce_field( 'aqualuxe_franchise_inquiry_business_nonce', 'franchise_inquiry_business_nonce' );
    
    // Get saved values
    $business_experience = get_post_meta( $post->ID, '_franchise_inquiry_business_experience', true );
    $aquarium_experience = get_post_meta( $post->ID, '_franchise_inquiry_aquarium_experience', true );
    $current_occupation = get_post_meta( $post->ID, '_franchise_inquiry_current_occupation', true );
    $business_ownership = get_post_meta( $post->ID, '_franchise_inquiry_business_ownership', true );
    $franchise_experience = get_post_meta( $post->ID, '_franchise_inquiry_franchise_experience', true );
    $partners = get_post_meta( $post->ID, '_franchise_inquiry_partners', true );
    ?>
    <div class="franchise-inquiry-business-meta-box">
        <style>
            .franchise-inquiry-business-meta-box .form-field {
                margin-bottom: 15px;
            }
            .franchise-inquiry-business-meta-box label {
                display: block;
                font-weight: 600;
                margin-bottom: 5px;
            }
            .franchise-inquiry-business-meta-box input[type="text"],
            .franchise-inquiry-business-meta-box select,
            .franchise-inquiry-business-meta-box textarea {
                width: 100%;
                max-width: 400px;
            }
            .franchise-inquiry-business-meta-box .form-row {
                display: flex;
                flex-wrap: wrap;
                margin: 0 -10px;
            }
            .franchise-inquiry-business-meta-box .form-col {
                flex: 1;
                padding: 0 10px;
                min-width: 200px;
            }
        </style>
        
        <div class="form-row">
            <div class="form-col">
                <div class="form-field">
                    <label for="franchise_inquiry_business_experience"><?php esc_html_e( 'Business Experience', 'aqualuxe' ); ?></label>
                    <select id="franchise_inquiry_business_experience" name="franchise_inquiry_business_experience">
                        <option value=""><?php esc_html_e( 'Select Experience', 'aqualuxe' ); ?></option>
                        <option value="none" <?php selected( $business_experience, 'none' ); ?>><?php esc_html_e( 'None', 'aqualuxe' ); ?></option>
                        <option value="1-3" <?php selected( $business_experience, '1-3' ); ?>><?php esc_html_e( '1-3 years', 'aqualuxe' ); ?></option>
                        <option value="3-5" <?php selected( $business_experience, '3-5' ); ?>><?php esc_html_e( '3-5 years', 'aqualuxe' ); ?></option>
                        <option value="5-10" <?php selected( $business_experience, '5-10' ); ?>><?php esc_html_e( '5-10 years', 'aqualuxe' ); ?></option>
                        <option value="10+" <?php selected( $business_experience, '10+' ); ?>><?php esc_html_e( '10+ years', 'aqualuxe' ); ?></option>
                    </select>
                </div>
                
                <div class="form-field">
                    <label for="franchise_inquiry_aquarium_experience"><?php esc_html_e( 'Aquarium Industry Experience', 'aqualuxe' ); ?></label>
                    <select id="franchise_inquiry_aquarium_experience" name="franchise_inquiry_aquarium_experience">
                        <option value=""><?php esc_html_e( 'Select Experience', 'aqualuxe' ); ?></option>
                        <option value="none" <?php selected( $aquarium_experience, 'none' ); ?>><?php esc_html_e( 'None', 'aqualuxe' ); ?></option>
                        <option value="hobbyist" <?php selected( $aquarium_experience, 'hobbyist' ); ?>><?php esc_html_e( 'Hobbyist', 'aqualuxe' ); ?></option>
                        <option value="1-3" <?php selected( $aquarium_experience, '1-3' ); ?>><?php esc_html_e( '1-3 years professional', 'aqualuxe' ); ?></option>
                        <option value="3-5" <?php selected( $aquarium_experience, '3-5' ); ?>><?php esc_html_e( '3-5 years professional', 'aqualuxe' ); ?></option>
                        <option value="5+" <?php selected( $aquarium_experience, '5+' ); ?>><?php esc_html_e( '5+ years professional', 'aqualuxe' ); ?></option>
                    </select>
                </div>
            </div>
            
            <div class="form-col">
                <div class="form-field">
                    <label for="franchise_inquiry_current_occupation"><?php esc_html_e( 'Current Occupation', 'aqualuxe' ); ?></label>
                    <input type="text" id="franchise_inquiry_current_occupation" name="franchise_inquiry_current_occupation" value="<?php echo esc_attr( $current_occupation ); ?>" />
                </div>
                
                <div class="form-field">
                    <label for="franchise_inquiry_business_ownership"><?php esc_html_e( 'Previous Business Ownership', 'aqualuxe' ); ?></label>
                    <select id="franchise_inquiry_business_ownership" name="franchise_inquiry_business_ownership">
                        <option value=""><?php esc_html_e( 'Select Option', 'aqualuxe' ); ?></option>
                        <option value="yes" <?php selected( $business_ownership, 'yes' ); ?>><?php esc_html_e( 'Yes', 'aqualuxe' ); ?></option>
                        <option value="no" <?php selected( $business_ownership, 'no' ); ?>><?php esc_html_e( 'No', 'aqualuxe' ); ?></option>
                    </select>
                </div>
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-col">
                <div class="form-field">
                    <label for="franchise_inquiry_franchise_experience"><?php esc_html_e( 'Previous Franchise Experience', 'aqualuxe' ); ?></label>
                    <select id="franchise_inquiry_franchise_experience" name="franchise_inquiry_franchise_experience">
                        <option value=""><?php esc_html_e( 'Select Option', 'aqualuxe' ); ?></option>
                        <option value="yes" <?php selected( $franchise_experience, 'yes' ); ?>><?php esc_html_e( 'Yes', 'aqualuxe' ); ?></option>
                        <option value="no" <?php selected( $franchise_experience, 'no' ); ?>><?php esc_html_e( 'No', 'aqualuxe' ); ?></option>
                    </select>
                </div>
            </div>
            
            <div class="form-col">
                <div class="form-field">
                    <label for="franchise_inquiry_partners"><?php esc_html_e( 'Business Partners', 'aqualuxe' ); ?></label>
                    <select id="franchise_inquiry_partners" name="franchise_inquiry_partners">
                        <option value=""><?php esc_html_e( 'Select Option', 'aqualuxe' ); ?></option>
                        <option value="none" <?php selected( $partners, 'none' ); ?>><?php esc_html_e( 'None (Sole Owner)', 'aqualuxe' ); ?></option>
                        <option value="spouse" <?php selected( $partners, 'spouse' ); ?>><?php esc_html_e( 'Spouse/Partner', 'aqualuxe' ); ?></option>
                        <option value="family" <?php selected( $partners, 'family' ); ?>><?php esc_html_e( 'Family Members', 'aqualuxe' ); ?></option>
                        <option value="investors" <?php selected( $partners, 'investors' ); ?>><?php esc_html_e( 'Investors', 'aqualuxe' ); ?></option>
                        <option value="other" <?php selected( $partners, 'other' ); ?>><?php esc_html_e( 'Other', 'aqualuxe' ); ?></option>
                    </select>
                </div>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Franchise Inquiry Financial meta box callback
 */
function aqualuxe_franchise_inquiry_financial_meta_box_callback( $post ) {
    // Add nonce for security
    wp_nonce_field( 'aqualuxe_franchise_inquiry_financial_nonce', 'franchise_inquiry_financial_nonce' );
    
    // Get saved values
    $investment_range = get_post_meta( $post->ID, '_franchise_inquiry_investment_range', true );
    $liquid_assets = get_post_meta( $post->ID, '_franchise_inquiry_liquid_assets', true );
    $net_worth = get_post_meta( $post->ID, '_franchise_inquiry_net_worth', true );
    $financing = get_post_meta( $post->ID, '_franchise_inquiry_financing', true );
    ?>
    <div class="franchise-inquiry-financial-meta-box">
        <style>
            .franchise-inquiry-financial-meta-box .form-field {
                margin-bottom: 15px;
            }
            .franchise-inquiry-financial-meta-box label {
                display: block;
                font-weight: 600;
                margin-bottom: 5px;
            }
            .franchise-inquiry-financial-meta-box select {
                width: 100%;
                max-width: 400px;
            }
            .franchise-inquiry-financial-meta-box .form-row {
                display: flex;
                flex-wrap: wrap;
                margin: 0 -10px;
            }
            .franchise-inquiry-financial-meta-box .form-col {
                flex: 1;
                padding: 0 10px;
                min-width: 200px;
            }
        </style>
        
        <div class="form-row">
            <div class="form-col">
                <div class="form-field">
                    <label for="franchise_inquiry_investment_range"><?php esc_html_e( 'Investment Range', 'aqualuxe' ); ?></label>
                    <select id="franchise_inquiry_investment_range" name="franchise_inquiry_investment_range">
                        <option value=""><?php esc_html_e( 'Select Range', 'aqualuxe' ); ?></option>
                        <option value="50-100k" <?php selected( $investment_range, '50-100k' ); ?>><?php esc_html_e( '$50,000 - $100,000', 'aqualuxe' ); ?></option>
                        <option value="100-150k" <?php selected( $investment_range, '100-150k' ); ?>><?php esc_html_e( '$100,000 - $150,000', 'aqualuxe' ); ?></option>
                        <option value="150-200k" <?php selected( $investment_range, '150-200k' ); ?>><?php esc_html_e( '$150,000 - $200,000', 'aqualuxe' ); ?></option>
                        <option value="200k+" <?php selected( $investment_range, '200k+' ); ?>><?php esc_html_e( '$200,000+', 'aqualuxe' ); ?></option>
                    </select>
                </div>
                
                <div class="form-field">
                    <label for="franchise_inquiry_liquid_assets"><?php esc_html_e( 'Liquid Assets', 'aqualuxe' ); ?></label>
                    <select id="franchise_inquiry_liquid_assets" name="franchise_inquiry_liquid_assets">
                        <option value=""><?php esc_html_e( 'Select Range', 'aqualuxe' ); ?></option>
                        <option value="25-50k" <?php selected( $liquid_assets, '25-50k' ); ?>><?php esc_html_e( '$25,000 - $50,000', 'aqualuxe' ); ?></option>
                        <option value="50-100k" <?php selected( $liquid_assets, '50-100k' ); ?>><?php esc_html_e( '$50,000 - $100,000', 'aqualuxe' ); ?></option>
                        <option value="100-150k" <?php selected( $liquid_assets, '100-150k' ); ?>><?php esc_html_e( '$100,000 - $150,000', 'aqualuxe' ); ?></option>
                        <option value="150k+" <?php selected( $liquid_assets, '150k+' ); ?>><?php esc_html_e( '$150,000+', 'aqualuxe' ); ?></option>
                    </select>
                </div>
            </div>
            
            <div class="form-col">
                <div class="form-field">
                    <label for="franchise_inquiry_net_worth"><?php esc_html_e( 'Net Worth', 'aqualuxe' ); ?></label>
                    <select id="franchise_inquiry_net_worth" name="franchise_inquiry_net_worth">
                        <option value=""><?php esc_html_e( 'Select Range', 'aqualuxe' ); ?></option>
                        <option value="100-250k" <?php selected( $net_worth, '100-250k' ); ?>><?php esc_html_e( '$100,000 - $250,000', 'aqualuxe' ); ?></option>
                        <option value="250-500k" <?php selected( $net_worth, '250-500k' ); ?>><?php esc_html_e( '$250,000 - $500,000', 'aqualuxe' ); ?></option>
                        <option value="500k-1m" <?php selected( $net_worth, '500k-1m' ); ?>><?php esc_html_e( '$500,000 - $1,000,000', 'aqualuxe' ); ?></option>
                        <option value="1m+" <?php selected( $net_worth, '1m+' ); ?>><?php esc_html_e( '$1,000,000+', 'aqualuxe' ); ?></option>
                    </select>
                </div>
                
                <div class="form-field">
                    <label for="franchise_inquiry_financing"><?php esc_html_e( 'Financing Required', 'aqualuxe' ); ?></label>
                    <select id="franchise_inquiry_financing" name="franchise_inquiry_financing">
                        <option value=""><?php esc_html_e( 'Select Option', 'aqualuxe' ); ?></option>
                        <option value="yes" <?php selected( $financing, 'yes' ); ?>><?php esc_html_e( 'Yes', 'aqualuxe' ); ?></option>
                        <option value="no" <?php selected( $financing, 'no' ); ?>><?php esc_html_e( 'No', 'aqualuxe' ); ?></option>
                        <option value="unsure" <?php selected( $financing, 'unsure' ); ?>><?php esc_html_e( 'Unsure', 'aqualuxe' ); ?></option>
                    </select>
                </div>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Franchise Inquiry Status meta box callback
 */
function aqualuxe_franchise_inquiry_status_meta_box_callback( $post ) {
    // Add nonce for security
    wp_nonce_field( 'aqualuxe_franchise_inquiry_status_nonce', 'franchise_inquiry_status_nonce' );
    
    // Get saved values
    $admin_notes = get_post_meta( $post->ID, '_franchise_inquiry_admin_notes', true );
    $follow_up_date = get_post_meta( $post->ID, '_franchise_inquiry_follow_up_date', true );
    $assigned_to = get_post_meta( $post->ID, '_franchise_inquiry_assigned_to', true );
    
    // Format follow-up date
    $formatted_follow_up_date = $follow_up_date ? date_i18n( get_option( 'date_format' ), strtotime( $follow_up_date ) ) : '';
    ?>
    <div class="franchise-inquiry-status-meta-box">
        <p>
            <label for="inquiry_status"><strong><?php esc_html_e( 'Status', 'aqualuxe' ); ?></strong></label><br>
            <?php
            $terms = get_terms( array(
                'taxonomy'   => 'inquiry_status',
                'hide_empty' => false,
            ) );
            
            if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
                echo '<select id="inquiry_status" name="inquiry_status" style="width: 100%;">';
                foreach ( $terms as $term ) {
                    $selected = has_term( $term->term_id, 'inquiry_status', $post->ID ) ? 'selected' : '';
                    echo '<option value="' . esc_attr( $term->term_id ) . '" ' . $selected . '>' . esc_html( $term->name ) . '</option>';
                }
                echo '</select>';
            }
            ?>
        </p>
        
        <p>
            <label for="franchise_inquiry_assigned_to"><strong><?php esc_html_e( 'Assigned To', 'aqualuxe' ); ?></strong></label><br>
            <?php
            $users = get_users( array( 'role__in' => array( 'administrator', 'editor' ) ) );
            echo '<select id="franchise_inquiry_assigned_to" name="franchise_inquiry_assigned_to" style="width: 100%;">';
            echo '<option value="">' . esc_html__( 'Not Assigned', 'aqualuxe' ) . '</option>';
            foreach ( $users as $user ) {
                echo '<option value="' . esc_attr( $user->ID ) . '" ' . selected( $assigned_to, $user->ID, false ) . '>' . esc_html( $user->display_name ) . '</option>';
            }
            echo '</select>';
            ?>
        </p>
        
        <p>
            <label for="franchise_inquiry_follow_up_date"><strong><?php esc_html_e( 'Follow-up Date', 'aqualuxe' ); ?></strong></label><br>
            <input type="text" id="franchise_inquiry_follow_up_date" name="franchise_inquiry_follow_up_date" value="<?php echo esc_attr( $formatted_follow_up_date ); ?>" class="widefat datepicker" />
        </p>
        
        <p>
            <label for="franchise_inquiry_admin_notes"><strong><?php esc_html_e( 'Admin Notes', 'aqualuxe' ); ?></strong></label><br>
            <textarea id="franchise_inquiry_admin_notes" name="franchise_inquiry_admin_notes" rows="4" style="width: 100%;"><?php echo esc_textarea( $admin_notes ); ?></textarea>
        </p>
        
        <p>
            <button type="button" class="button" id="franchise_inquiry_notify_applicant">
                <?php esc_html_e( 'Notify Applicant', 'aqualuxe' ); ?>
            </button>
        </p>
        
        <script>
        jQuery(document).ready(function($) {
            // Initialize datepicker
            if ($.fn.datepicker) {
                $('#franchise_inquiry_follow_up_date').datepicker({
                    dateFormat: 'yy-mm-dd',
                    changeMonth: true,
                    changeYear: true
                });
            }
            
            // Handle notification button
            $('#franchise_inquiry_notify_applicant').on('click', function() {
                if (confirm('<?php esc_html_e( 'Send notification email to applicant?', 'aqualuxe' ); ?>')) {
                    var data = {
                        action: 'aqualuxe_notify_franchise_applicant',
                        inquiry_id: <?php echo intval( $post->ID ); ?>,
                        nonce: '<?php echo wp_create_nonce( 'aqualuxe_notify_franchise_applicant' ); ?>'
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
    </div>
    <?php
}

/**
 * Save franchise inquiry meta box data
 */
function aqualuxe_save_franchise_inquiry_meta_box_data( $post_id ) {
    // Check if our nonces are set and verify them
    if ( ! isset( $_POST['franchise_inquiry_details_nonce'] ) || ! wp_verify_nonce( $_POST['franchise_inquiry_details_nonce'], 'aqualuxe_franchise_inquiry_details_nonce' ) ) {
        return;
    }
    
    if ( ! isset( $_POST['franchise_inquiry_contact_nonce'] ) || ! wp_verify_nonce( $_POST['franchise_inquiry_contact_nonce'], 'aqualuxe_franchise_inquiry_contact_nonce' ) ) {
        return;
    }
    
    if ( ! isset( $_POST['franchise_inquiry_business_nonce'] ) || ! wp_verify_nonce( $_POST['franchise_inquiry_business_nonce'], 'aqualuxe_franchise_inquiry_business_nonce' ) ) {
        return;
    }
    
    if ( ! isset( $_POST['franchise_inquiry_financial_nonce'] ) || ! wp_verify_nonce( $_POST['franchise_inquiry_financial_nonce'], 'aqualuxe_franchise_inquiry_financial_nonce' ) ) {
        return;
    }
    
    if ( ! isset( $_POST['franchise_inquiry_status_nonce'] ) || ! wp_verify_nonce( $_POST['franchise_inquiry_status_nonce'], 'aqualuxe_franchise_inquiry_status_nonce' ) ) {
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
    
    // Inquiry details
    if ( isset( $_POST['franchise_inquiry_source'] ) ) {
        update_post_meta( $post_id, '_franchise_inquiry_source', sanitize_text_field( $_POST['franchise_inquiry_source'] ) );
    }
    
    if ( isset( $_POST['franchise_inquiry_location'] ) ) {
        update_post_meta( $post_id, '_franchise_inquiry_location', sanitize_text_field( $_POST['franchise_inquiry_location'] ) );
    }
    
    if ( isset( $_POST['franchise_inquiry_timeline'] ) ) {
        update_post_meta( $post_id, '_franchise_inquiry_timeline', sanitize_text_field( $_POST['franchise_inquiry_timeline'] ) );
    }
    
    if ( isset( $_POST['franchise_inquiry_message'] ) ) {
        update_post_meta( $post_id, '_franchise_inquiry_message', sanitize_textarea_field( $_POST['franchise_inquiry_message'] ) );
    }
    
    // Contact information
    if ( isset( $_POST['franchise_inquiry_first_name'] ) ) {
        update_post_meta( $post_id, '_franchise_inquiry_first_name', sanitize_text_field( $_POST['franchise_inquiry_first_name'] ) );
    }
    
    if ( isset( $_POST['franchise_inquiry_last_name'] ) ) {
        update_post_meta( $post_id, '_franchise_inquiry_last_name', sanitize_text_field( $_POST['franchise_inquiry_last_name'] ) );
    }
    
    if ( isset( $_POST['franchise_inquiry_email'] ) ) {
        update_post_meta( $post_id, '_franchise_inquiry_email', sanitize_email( $_POST['franchise_inquiry_email'] ) );
    }
    
    if ( isset( $_POST['franchise_inquiry_phone'] ) ) {
        update_post_meta( $post_id, '_franchise_inquiry_phone', sanitize_text_field( $_POST['franchise_inquiry_phone'] ) );
    }
    
    if ( isset( $_POST['franchise_inquiry_address'] ) ) {
        update_post_meta( $post_id, '_franchise_inquiry_address', sanitize_text_field( $_POST['franchise_inquiry_address'] ) );
    }
    
    if ( isset( $_POST['franchise_inquiry_city'] ) ) {
        update_post_meta( $post_id, '_franchise_inquiry_city', sanitize_text_field( $_POST['franchise_inquiry_city'] ) );
    }
    
    if ( isset( $_POST['franchise_inquiry_state'] ) ) {
        update_post_meta( $post_id, '_franchise_inquiry_state', sanitize_text_field( $_POST['franchise_inquiry_state'] ) );
    }
    
    if ( isset( $_POST['franchise_inquiry_zip'] ) ) {
        update_post_meta( $post_id, '_franchise_inquiry_zip', sanitize_text_field( $_POST['franchise_inquiry_zip'] ) );
    }
    
    if ( isset( $_POST['franchise_inquiry_country'] ) ) {
        update_post_meta( $post_id, '_franchise_inquiry_country', sanitize_text_field( $_POST['franchise_inquiry_country'] ) );
    }
    
    // Business information
    if ( isset( $_POST['franchise_inquiry_business_experience'] ) ) {
        update_post_meta( $post_id, '_franchise_inquiry_business_experience', sanitize_text_field( $_POST['franchise_inquiry_business_experience'] ) );
    }
    
    if ( isset( $_POST['franchise_inquiry_aquarium_experience'] ) ) {
        update_post_meta( $post_id, '_franchise_inquiry_aquarium_experience', sanitize_text_field( $_POST['franchise_inquiry_aquarium_experience'] ) );
    }
    
    if ( isset( $_POST['franchise_inquiry_current_occupation'] ) ) {
        update_post_meta( $post_id, '_franchise_inquiry_current_occupation', sanitize_text_field( $_POST['franchise_inquiry_current_occupation'] ) );
    }
    
    if ( isset( $_POST['franchise_inquiry_business_ownership'] ) ) {
        update_post_meta( $post_id, '_franchise_inquiry_business_ownership', sanitize_text_field( $_POST['franchise_inquiry_business_ownership'] ) );
    }
    
    if ( isset( $_POST['franchise_inquiry_franchise_experience'] ) ) {
        update_post_meta( $post_id, '_franchise_inquiry_franchise_experience', sanitize_text_field( $_POST['franchise_inquiry_franchise_experience'] ) );
    }
    
    if ( isset( $_POST['franchise_inquiry_partners'] ) ) {
        update_post_meta( $post_id, '_franchise_inquiry_partners', sanitize_text_field( $_POST['franchise_inquiry_partners'] ) );
    }
    
    // Financial information
    if ( isset( $_POST['franchise_inquiry_investment_range'] ) ) {
        update_post_meta( $post_id, '_franchise_inquiry_investment_range', sanitize_text_field( $_POST['franchise_inquiry_investment_range'] ) );
    }
    
    if ( isset( $_POST['franchise_inquiry_liquid_assets'] ) ) {
        update_post_meta( $post_id, '_franchise_inquiry_liquid_assets', sanitize_text_field( $_POST['franchise_inquiry_liquid_assets'] ) );
    }
    
    if ( isset( $_POST['franchise_inquiry_net_worth'] ) ) {
        update_post_meta( $post_id, '_franchise_inquiry_net_worth', sanitize_text_field( $_POST['franchise_inquiry_net_worth'] ) );
    }
    
    if ( isset( $_POST['franchise_inquiry_financing'] ) ) {
        update_post_meta( $post_id, '_franchise_inquiry_financing', sanitize_text_field( $_POST['franchise_inquiry_financing'] ) );
    }
    
    // Status information
    if ( isset( $_POST['franchise_inquiry_admin_notes'] ) ) {
        update_post_meta( $post_id, '_franchise_inquiry_admin_notes', sanitize_textarea_field( $_POST['franchise_inquiry_admin_notes'] ) );
    }
    
    if ( isset( $_POST['franchise_inquiry_follow_up_date'] ) ) {
        update_post_meta( $post_id, '_franchise_inquiry_follow_up_date', sanitize_text_field( $_POST['franchise_inquiry_follow_up_date'] ) );
    }
    
    if ( isset( $_POST['franchise_inquiry_assigned_to'] ) ) {
        update_post_meta( $post_id, '_franchise_inquiry_assigned_to', intval( $_POST['franchise_inquiry_assigned_to'] ) );
    }
    
    // Update inquiry status
    if ( isset( $_POST['inquiry_status'] ) ) {
        $old_status = '';
        $terms = get_the_terms( $post_id, 'inquiry_status' );
        if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
            $old_status = $terms[0]->term_id;
        }
        
        $new_status = intval( $_POST['inquiry_status'] );
        
        if ( $old_status !== $new_status ) {
            wp_set_object_terms( $post_id, $new_status, 'inquiry_status' );
            
            // Log status change
            $log = get_post_meta( $post_id, '_franchise_inquiry_status_log', true );
            if ( ! is_array( $log ) ) {
                $log = array();
            }
            
            $status_term = get_term( $new_status, 'inquiry_status' );
            if ( $status_term && ! is_wp_error( $status_term ) ) {
                $log[] = array(
                    'status'    => $status_term->name,
                    'timestamp' => current_time( 'mysql' ),
                    'user_id'   => get_current_user_id(),
                );
                
                update_post_meta( $post_id, '_franchise_inquiry_status_log', $log );
            }
        }
    }
}
add_action( 'save_post_franchise_inquiry', 'aqualuxe_save_franchise_inquiry_meta_box_data' );

/**
 * Add custom columns to the franchise inquiry list table
 */
function aqualuxe_franchise_inquiry_columns( $columns ) {
    $new_columns = array();
    
    // Add checkbox and title at the beginning
    if ( isset( $columns['cb'] ) ) {
        $new_columns['cb'] = $columns['cb'];
    }
    
    if ( isset( $columns['title'] ) ) {
        $new_columns['title'] = __( 'Inquiry ID', 'aqualuxe' );
    }
    
    // Add custom columns
    $new_columns['applicant'] = __( 'Applicant', 'aqualuxe' );
    $new_columns['location'] = __( 'Location', 'aqualuxe' );
    $new_columns['investment'] = __( 'Investment', 'aqualuxe' );
    $new_columns['status'] = __( 'Status', 'aqualuxe' );
    $new_columns['follow_up'] = __( 'Follow-up', 'aqualuxe' );
    
    // Add date at the end
    if ( isset( $columns['date'] ) ) {
        $new_columns['date'] = $columns['date'];
    }
    
    return $new_columns;
}
add_filter( 'manage_franchise_inquiry_posts_columns', 'aqualuxe_franchise_inquiry_columns' );

/**
 * Display data in custom columns for the franchise inquiry list table
 */
function aqualuxe_franchise_inquiry_custom_column( $column, $post_id ) {
    switch ( $column ) {
        case 'applicant':
            $first_name = get_post_meta( $post_id, '_franchise_inquiry_first_name', true );
            $last_name = get_post_meta( $post_id, '_franchise_inquiry_last_name', true );
            $email = get_post_meta( $post_id, '_franchise_inquiry_email', true );
            
            if ( $first_name || $last_name ) {
                echo esc_html( $first_name . ' ' . $last_name );
                
                if ( $email ) {
                    echo '<br><a href="mailto:' . esc_attr( $email ) . '">' . esc_html( $email ) . '</a>';
                }
            } else {
                echo '—';
            }
            break;
            
        case 'location':
            $location = get_post_meta( $post_id, '_franchise_inquiry_location', true );
            $city = get_post_meta( $post_id, '_franchise_inquiry_city', true );
            $state = get_post_meta( $post_id, '_franchise_inquiry_state', true );
            
            if ( $location ) {
                echo esc_html( $location );
            } elseif ( $city && $state ) {
                echo esc_html( $city . ', ' . $state );
            } elseif ( $city ) {
                echo esc_html( $city );
            } elseif ( $state ) {
                echo esc_html( $state );
            } else {
                echo '—';
            }
            break;
            
        case 'investment':
            $investment_range = get_post_meta( $post_id, '_franchise_inquiry_investment_range', true );
            
            $investment_labels = array(
                '50-100k'   => '$50,000 - $100,000',
                '100-150k'  => '$100,000 - $150,000',
                '150-200k'  => '$150,000 - $200,000',
                '200k+'     => '$200,000+',
            );
            
            if ( isset( $investment_labels[ $investment_range ] ) ) {
                echo esc_html( $investment_labels[ $investment_range ] );
            } else {
                echo '—';
            }
            break;
            
        case 'status':
            $terms = get_the_terms( $post_id, 'inquiry_status' );
            
            if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
                $status = $terms[0]->name;
                $slug = $terms[0]->slug;
                
                $status_colors = array(
                    'new'          => '#5bc0de',
                    'in-review'    => '#f0ad4e',
                    'qualified'    => '#5cb85c',
                    'not-qualified' => '#d9534f',
                    'in-progress'  => '#337ab7',
                    'completed'    => '#777777',
                );
                
                $color = isset( $status_colors[ $slug ] ) ? $status_colors[ $slug ] : '#777777';
                
                echo '<span style="display: inline-block; padding: 3px 8px; border-radius: 3px; background-color: ' . esc_attr( $color ) . '; color: #fff;">' . esc_html( $status ) . '</span>';
            } else {
                echo '—';
            }
            break;
            
        case 'follow_up':
            $follow_up_date = get_post_meta( $post_id, '_franchise_inquiry_follow_up_date', true );
            $assigned_to = get_post_meta( $post_id, '_franchise_inquiry_assigned_to', true );
            
            if ( $follow_up_date ) {
                $formatted_date = date_i18n( get_option( 'date_format' ), strtotime( $follow_up_date ) );
                echo esc_html( $formatted_date );
                
                if ( $assigned_to ) {
                    $user = get_user_by( 'id', $assigned_to );
                    if ( $user ) {
                        echo '<br><small>' . esc_html__( 'Assigned to:', 'aqualuxe' ) . ' ' . esc_html( $user->display_name ) . '</small>';
                    }
                }
            } else {
                echo '—';
            }
            break;
    }
}
add_action( 'manage_franchise_inquiry_posts_custom_column', 'aqualuxe_franchise_inquiry_custom_column', 10, 2 );

/**
 * Make custom columns sortable
 */
function aqualuxe_franchise_inquiry_sortable_columns( $columns ) {
    $columns['applicant'] = 'applicant';
    $columns['location'] = 'location';
    $columns['follow_up'] = 'follow_up';
    return $columns;
}
add_filter( 'manage_edit-franchise_inquiry_sortable_columns', 'aqualuxe_franchise_inquiry_sortable_columns' );

/**
 * Add sorting functionality to custom columns
 */
function aqualuxe_franchise_inquiry_sort_columns( $query ) {
    if ( ! is_admin() || ! $query->is_main_query() ) {
        return;
    }
    
    if ( $query->get( 'post_type' ) !== 'franchise_inquiry' ) {
        return;
    }
    
    $orderby = $query->get( 'orderby' );
    
    if ( 'applicant' === $orderby ) {
        $query->set( 'meta_key', '_franchise_inquiry_last_name' );
        $query->set( 'orderby', 'meta_value' );
    } elseif ( 'location' === $orderby ) {
        $query->set( 'meta_key', '_franchise_inquiry_location' );
        $query->set( 'orderby', 'meta_value' );
    } elseif ( 'follow_up' === $orderby ) {
        $query->set( 'meta_key', '_franchise_inquiry_follow_up_date' );
        $query->set( 'orderby', 'meta_value' );
    }
}
add_action( 'pre_get_posts', 'aqualuxe_franchise_inquiry_sort_columns' );

/**
 * Add filter dropdown for inquiry status
 */
function aqualuxe_franchise_inquiry_status_filter() {
    global $typenow;
    
    if ( 'franchise_inquiry' !== $typenow ) {
        return;
    }
    
    $current = isset( $_GET['inquiry_status'] ) ? $_GET['inquiry_status'] : '';
    $terms = get_terms( array(
        'taxonomy'   => 'inquiry_status',
        'hide_empty' => false,
    ) );
    
    if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
        echo '<select name="inquiry_status" id="inquiry_status" class="postform">';
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
add_action( 'restrict_manage_posts', 'aqualuxe_franchise_inquiry_status_filter' );

/**
 * AJAX handler for submitting franchise inquiry
 */
function aqualuxe_ajax_submit_franchise_inquiry() {
    // Check nonce
    if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'aqualuxe_ajax_nonce' ) ) {
        wp_send_json_error( array( 'message' => __( 'Security check failed.', 'aqualuxe' ) ) );
    }
    
    // Check required fields
    $required_fields = array(
        'first_name'     => __( 'First Name', 'aqualuxe' ),
        'last_name'      => __( 'Last Name', 'aqualuxe' ),
        'email'          => __( 'Email', 'aqualuxe' ),
        'phone'          => __( 'Phone', 'aqualuxe' ),
        'location'       => __( 'Preferred Location', 'aqualuxe' ),
        'investment_range' => __( 'Investment Range', 'aqualuxe' ),
    );
    
    foreach ( $required_fields as $field => $label ) {
        if ( empty( $_POST[ $field ] ) ) {
            wp_send_json_error( array( 'message' => sprintf( __( '%s is required.', 'aqualuxe' ), $label ) ) );
        }
    }
    
    // Validate email
    if ( ! is_email( $_POST['email'] ) ) {
        wp_send_json_error( array( 'message' => __( 'Please enter a valid email address.', 'aqualuxe' ) ) );
    }
    
    // Create franchise inquiry
    $inquiry_data = array(
        'post_title'   => sprintf( __( 'Franchise Inquiry - %s %s', 'aqualuxe' ), sanitize_text_field( $_POST['first_name'] ), sanitize_text_field( $_POST['last_name'] ) ),
        'post_content' => '',
        'post_status'  => 'publish',
        'post_type'    => 'franchise_inquiry',
    );
    
    $inquiry_id = wp_insert_post( $inquiry_data );
    
    if ( is_wp_error( $inquiry_id ) ) {
        wp_send_json_error( array( 'message' => __( 'Failed to create franchise inquiry. Please try again.', 'aqualuxe' ) ) );
    }
    
    // Set inquiry status to new
    wp_set_object_terms( $inquiry_id, 'new', 'inquiry_status' );
    
    // Save inquiry details
    update_post_meta( $inquiry_id, '_franchise_inquiry_date', current_time( 'mysql' ) );
    update_post_meta( $inquiry_id, '_franchise_inquiry_source', 'website' );
    update_post_meta( $inquiry_id, '_franchise_inquiry_location', sanitize_text_field( $_POST['location'] ) );
    update_post_meta( $inquiry_id, '_franchise_inquiry_timeline', sanitize_text_field( $_POST['timeline'] ) );
    update_post_meta( $inquiry_id, '_franchise_inquiry_message', sanitize_textarea_field( $_POST['message'] ) );
    
    // Save contact information
    update_post_meta( $inquiry_id, '_franchise_inquiry_first_name', sanitize_text_field( $_POST['first_name'] ) );
    update_post_meta( $inquiry_id, '_franchise_inquiry_last_name', sanitize_text_field( $_POST['last_name'] ) );
    update_post_meta( $inquiry_id, '_franchise_inquiry_email', sanitize_email( $_POST['email'] ) );
    update_post_meta( $inquiry_id, '_franchise_inquiry_phone', sanitize_text_field( $_POST['phone'] ) );
    update_post_meta( $inquiry_id, '_franchise_inquiry_address', sanitize_text_field( $_POST['address'] ) );
    update_post_meta( $inquiry_id, '_franchise_inquiry_city', sanitize_text_field( $_POST['city'] ) );
    update_post_meta( $inquiry_id, '_franchise_inquiry_state', sanitize_text_field( $_POST['state'] ) );
    update_post_meta( $inquiry_id, '_franchise_inquiry_zip', sanitize_text_field( $_POST['zip'] ) );
    update_post_meta( $inquiry_id, '_franchise_inquiry_country', sanitize_text_field( $_POST['country'] ) );
    
    // Save business information
    update_post_meta( $inquiry_id, '_franchise_inquiry_business_experience', sanitize_text_field( $_POST['business_experience'] ) );
    update_post_meta( $inquiry_id, '_franchise_inquiry_aquarium_experience', sanitize_text_field( $_POST['aquarium_experience'] ) );
    update_post_meta( $inquiry_id, '_franchise_inquiry_current_occupation', sanitize_text_field( $_POST['current_occupation'] ) );
    update_post_meta( $inquiry_id, '_franchise_inquiry_business_ownership', sanitize_text_field( $_POST['business_ownership'] ) );
    update_post_meta( $inquiry_id, '_franchise_inquiry_franchise_experience', sanitize_text_field( $_POST['franchise_experience'] ) );
    update_post_meta( $inquiry_id, '_franchise_inquiry_partners', sanitize_text_field( $_POST['partners'] ) );
    
    // Save financial information
    update_post_meta( $inquiry_id, '_franchise_inquiry_investment_range', sanitize_text_field( $_POST['investment_range'] ) );
    update_post_meta( $inquiry_id, '_franchise_inquiry_liquid_assets', sanitize_text_field( $_POST['liquid_assets'] ) );
    update_post_meta( $inquiry_id, '_franchise_inquiry_net_worth', sanitize_text_field( $_POST['net_worth'] ) );
    update_post_meta( $inquiry_id, '_franchise_inquiry_financing', sanitize_text_field( $_POST['financing'] ) );
    
    // Initialize status log
    $log = array(
        array(
            'status'    => 'New',
            'timestamp' => current_time( 'mysql' ),
            'user_id'   => 0,
        ),
    );
    
    update_post_meta( $inquiry_id, '_franchise_inquiry_status_log', $log );
    
    // Send confirmation email
    aqualuxe_send_franchise_inquiry_confirmation( $inquiry_id );
    
    // Send admin notification
    aqualuxe_send_franchise_inquiry_admin_notification( $inquiry_id );
    
    // Send success response
    wp_send_json_success( array(
        'message'    => __( 'Your franchise inquiry has been submitted successfully. We will contact you shortly to discuss your interest in our franchise opportunity.', 'aqualuxe' ),
        'inquiry_id' => $inquiry_id,
    ) );
}
add_action( 'wp_ajax_aqualuxe_submit_franchise_inquiry', 'aqualuxe_ajax_submit_franchise_inquiry' );
add_action( 'wp_ajax_nopriv_aqualuxe_submit_franchise_inquiry', 'aqualuxe_ajax_submit_franchise_inquiry' );

/**
 * Send franchise inquiry confirmation email to applicant
 * 
 * @param int $inquiry_id Inquiry post ID
 */
function aqualuxe_send_franchise_inquiry_confirmation( $inquiry_id ) {
    $first_name = get_post_meta( $inquiry_id, '_franchise_inquiry_first_name', true );
    $email = get_post_meta( $inquiry_id, '_franchise_inquiry_email', true );
    
    $subject = sprintf( __( 'Your Franchise Inquiry - %s', 'aqualuxe' ), get_bloginfo( 'name' ) );
    
    $message = sprintf(
        __( 'Dear %s,', 'aqualuxe' ),
        $first_name
    ) . "\n\n";
    
    $message .= __( 'Thank you for your interest in the AquaLuxe franchise opportunity. We have received your inquiry and are excited about the possibility of partnering with you.', 'aqualuxe' ) . "\n\n";
    
    $message .= __( 'Next Steps:', 'aqualuxe' ) . "\n";
    $message .= __( '1. Our franchise development team will review your inquiry.', 'aqualuxe' ) . "\n";
    $message .= __( '2. We will contact you within 3-5 business days to discuss your interest and answer any initial questions.', 'aqualuxe' ) . "\n";
    $message .= __( '3. If there is mutual interest, we will provide you with our Franchise Disclosure Document (FDD) and schedule a more detailed conversation.', 'aqualuxe' ) . "\n\n";
    
    $message .= __( 'In the meantime, you can learn more about our franchise opportunity by visiting our website or contacting our franchise development team at franchise@aqualuxe.com.', 'aqualuxe' ) . "\n\n";
    
    $message .= __( 'We look forward to speaking with you soon.', 'aqualuxe' ) . "\n\n";
    
    $message .= sprintf(
        __( 'Best regards,', 'aqualuxe' ) . "\n%s\n%s",
        __( 'Franchise Development Team', 'aqualuxe' ),
        get_bloginfo( 'name' )
    );
    
    $headers = array( 'Content-Type: text/plain; charset=UTF-8' );
    
    wp_mail( $email, $subject, $message, $headers );
}

/**
 * Send franchise inquiry notification email to admin
 * 
 * @param int $inquiry_id Inquiry post ID
 */
function aqualuxe_send_franchise_inquiry_admin_notification( $inquiry_id ) {
    $first_name = get_post_meta( $inquiry_id, '_franchise_inquiry_first_name', true );
    $last_name = get_post_meta( $inquiry_id, '_franchise_inquiry_last_name', true );
    $email = get_post_meta( $inquiry_id, '_franchise_inquiry_email', true );
    $phone = get_post_meta( $inquiry_id, '_franchise_inquiry_phone', true );
    $location = get_post_meta( $inquiry_id, '_franchise_inquiry_location', true );
    $investment_range = get_post_meta( $inquiry_id, '_franchise_inquiry_investment_range', true );
    
    $investment_labels = array(
        '50-100k'   => '$50,000 - $100,000',
        '100-150k'  => '$100,000 - $150,000',
        '150-200k'  => '$150,000 - $200,000',
        '200k+'     => '$200,000+',
    );
    
    $investment_label = isset( $investment_labels[ $investment_range ] ) ? $investment_labels[ $investment_range ] : $investment_range;
    
    $admin_email = get_option( 'admin_email' );
    $subject = sprintf( __( 'New Franchise Inquiry: %s %s', 'aqualuxe' ), $first_name, $last_name );
    
    $message = __( 'A new franchise inquiry has been submitted. Details are as follows:', 'aqualuxe' ) . "\n\n";
    
    $message .= __( 'Applicant Information:', 'aqualuxe' ) . "\n";
    $message .= sprintf( __( 'Name: %s %s', 'aqualuxe' ), $first_name, $last_name ) . "\n";
    $message .= sprintf( __( 'Email: %s', 'aqualuxe' ), $email ) . "\n";
    $message .= sprintf( __( 'Phone: %s', 'aqualuxe' ), $phone ) . "\n\n";
    
    $message .= __( 'Inquiry Details:', 'aqualuxe' ) . "\n";
    $message .= sprintf( __( 'Preferred Location: %s', 'aqualuxe' ), $location ) . "\n";
    $message .= sprintf( __( 'Investment Range: %s', 'aqualuxe' ), $investment_label ) . "\n\n";
    
    $message .= sprintf(
        __( 'To view the complete inquiry, please visit: %s', 'aqualuxe' ),
        admin_url( 'post.php?post=' . $inquiry_id . '&action=edit' )
    );
    
    $headers = array( 'Content-Type: text/plain; charset=UTF-8' );
    
    wp_mail( $admin_email, $subject, $message, $headers );
}

/**
 * AJAX handler for notifying franchise applicant
 */
function aqualuxe_ajax_notify_franchise_applicant() {
    // Check if user is admin
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_send_json_error( array( 'message' => __( 'You do not have permission to perform this action.', 'aqualuxe' ) ) );
    }
    
    // Check nonce
    if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'aqualuxe_notify_franchise_applicant' ) ) {
        wp_send_json_error( array( 'message' => __( 'Security check failed.', 'aqualuxe' ) ) );
    }
    
    // Check inquiry ID
    if ( ! isset( $_POST['inquiry_id'] ) ) {
        wp_send_json_error( array( 'message' => __( 'Missing inquiry ID.', 'aqualuxe' ) ) );
    }
    
    $inquiry_id = intval( $_POST['inquiry_id'] );
    
    // Check if inquiry exists
    $inquiry = get_post( $inquiry_id );
    
    if ( ! $inquiry || 'franchise_inquiry' !== $inquiry->post_type ) {
        wp_send_json_error( array( 'message' => __( 'Invalid franchise inquiry.', 'aqualuxe' ) ) );
    }
    
    // Get inquiry status
    $terms = get_the_terms( $inquiry_id, 'inquiry_status' );
    $status = ! empty( $terms ) && ! is_wp_error( $terms ) ? $terms[0]->slug : '';
    
    // Send notification based on status
    switch ( $status ) {
        case 'in-review':
            aqualuxe_send_franchise_inquiry_review_notification( $inquiry_id );
            break;
            
        case 'qualified':
            aqualuxe_send_franchise_inquiry_qualified_notification( $inquiry_id );
            break;
            
        case 'not-qualified':
            aqualuxe_send_franchise_inquiry_not_qualified_notification( $inquiry_id );
            break;
            
        case 'in-progress':
            aqualuxe_send_franchise_inquiry_in_progress_notification( $inquiry_id );
            break;
            
        case 'completed':
            aqualuxe_send_franchise_inquiry_completed_notification( $inquiry_id );
            break;
            
        default:
            wp_send_json_error( array( 'message' => __( 'Invalid inquiry status.', 'aqualuxe' ) ) );
            break;
    }
    
    // Send success response
    wp_send_json_success( array( 'message' => __( 'Notification sent successfully.', 'aqualuxe' ) ) );
}
add_action( 'wp_ajax_aqualuxe_notify_franchise_applicant', 'aqualuxe_ajax_notify_franchise_applicant' );

/**
 * Send franchise inquiry review notification to applicant
 * 
 * @param int $inquiry_id Inquiry post ID
 */
function aqualuxe_send_franchise_inquiry_review_notification( $inquiry_id ) {
    $first_name = get_post_meta( $inquiry_id, '_franchise_inquiry_first_name', true );
    $email = get_post_meta( $inquiry_id, '_franchise_inquiry_email', true );
    
    $subject = sprintf( __( 'Franchise Inquiry Update - %s', 'aqualuxe' ), get_bloginfo( 'name' ) );
    
    $message = sprintf(
        __( 'Dear %s,', 'aqualuxe' ),
        $first_name
    ) . "\n\n";
    
    $message .= __( 'Thank you for your interest in the AquaLuxe franchise opportunity. We are currently reviewing your inquiry and would like to schedule a call to discuss your interest in more detail.', 'aqualuxe' ) . "\n\n";
    
    $message .= __( 'During this call, we will:', 'aqualuxe' ) . "\n";
    $message .= __( '1. Provide an overview of the AquaLuxe franchise opportunity', 'aqualuxe' ) . "\n";
    $message .= __( '2. Discuss your background and qualifications', 'aqualuxe' ) . "\n";
    $message .= __( '3. Answer any questions you may have', 'aqualuxe' ) . "\n";
    $message .= __( '4. Outline the next steps in the franchise application process', 'aqualuxe' ) . "\n\n";
    
    $message .= __( 'Please reply to this email with your availability for a 30-minute call in the next few days, or call us directly at (555) 123-4567.', 'aqualuxe' ) . "\n\n";
    
    $message .= __( 'We look forward to speaking with you soon.', 'aqualuxe' ) . "\n\n";
    
    $message .= sprintf(
        __( 'Best regards,', 'aqualuxe' ) . "\n%s\n%s",
        __( 'Franchise Development Team', 'aqualuxe' ),
        get_bloginfo( 'name' )
    );
    
    $headers = array( 'Content-Type: text/plain; charset=UTF-8' );
    
    wp_mail( $email, $subject, $message, $headers );
}

/**
 * Send franchise inquiry qualified notification to applicant
 * 
 * @param int $inquiry_id Inquiry post ID
 */
function aqualuxe_send_franchise_inquiry_qualified_notification( $inquiry_id ) {
    $first_name = get_post_meta( $inquiry_id, '_franchise_inquiry_first_name', true );
    $email = get_post_meta( $inquiry_id, '_franchise_inquiry_email', true );
    
    $subject = sprintf( __( 'Franchise Inquiry Update - %s', 'aqualuxe' ), get_bloginfo( 'name' ) );
    
    $message = sprintf(
        __( 'Dear %s,', 'aqualuxe' ),
        $first_name
    ) . "\n\n";
    
    $message .= __( 'We are pleased to inform you that after reviewing your franchise inquiry, we have determined that you meet our initial qualifications for becoming an AquaLuxe franchise owner.', 'aqualuxe' ) . "\n\n";
    
    $message .= __( 'Next Steps:', 'aqualuxe' ) . "\n";
    $message .= __( '1. We will send you our Franchise Disclosure Document (FDD) for your review.', 'aqualuxe' ) . "\n";
    $message .= __( '2. After you have had time to review the FDD, we will schedule a follow-up call to discuss any questions you may have.', 'aqualuxe' ) . "\n";
    $message .= __( '3. We will arrange for you to speak with existing franchise owners and potentially visit one of our locations.', 'aqualuxe' ) . "\n";
    $message .= __( '4. If there is mutual interest in moving forward, we will discuss the formal application process.', 'aqualuxe' ) . "\n\n";
    
    $message .= __( 'You will receive the FDD via email within the next 24-48 hours. Please ensure that franchise@aqualuxe.com is added to your safe senders list to prevent our emails from being filtered as spam.', 'aqualuxe' ) . "\n\n";
    
    $message .= __( 'We are excited about the possibility of welcoming you to the AquaLuxe franchise family and look forward to continuing our discussions.', 'aqualuxe' ) . "\n\n";
    
    $message .= sprintf(
        __( 'Best regards,', 'aqualuxe' ) . "\n%s\n%s",
        __( 'Franchise Development Team', 'aqualuxe' ),
        get_bloginfo( 'name' )
    );
    
    $headers = array( 'Content-Type: text/plain; charset=UTF-8' );
    
    wp_mail( $email, $subject, $message, $headers );
}

/**
 * Send franchise inquiry not qualified notification to applicant
 * 
 * @param int $inquiry_id Inquiry post ID
 */
function aqualuxe_send_franchise_inquiry_not_qualified_notification( $inquiry_id ) {
    $first_name = get_post_meta( $inquiry_id, '_franchise_inquiry_first_name', true );
    $email = get_post_meta( $inquiry_id, '_franchise_inquiry_email', true );
    $admin_notes = get_post_meta( $inquiry_id, '_franchise_inquiry_admin_notes', true );
    
    $subject = sprintf( __( 'Franchise Inquiry Update - %s', 'aqualuxe' ), get_bloginfo( 'name' ) );
    
    $message = sprintf(
        __( 'Dear %s,', 'aqualuxe' ),
        $first_name
    ) . "\n\n";
    
    $message .= __( 'Thank you for your interest in the AquaLuxe franchise opportunity. We appreciate the time you took to submit your inquiry and share your background with us.', 'aqualuxe' ) . "\n\n";
    
    $message .= __( 'After careful consideration, we have determined that your current qualifications do not align with our franchise owner requirements at this time.', 'aqualuxe' ) . "\n\n";
    
    if ( $admin_notes ) {
        $message .= __( 'Specifically:', 'aqualuxe' ) . "\n";
        $message .= $admin_notes . "\n\n";
    }
    
    $message .= __( 'We encourage you to reapply in the future if your circumstances change. In the meantime, we invite you to stay connected with AquaLuxe by visiting our stores and following us on social media.', 'aqualuxe' ) . "\n\n";
    
    $message .= __( 'Thank you again for your interest in AquaLuxe.', 'aqualuxe' ) . "\n\n";
    
    $message .= sprintf(
        __( 'Best regards,', 'aqualuxe' ) . "\n%s\n%s",
        __( 'Franchise Development Team', 'aqualuxe' ),
        get_bloginfo( 'name' )
    );
    
    $headers = array( 'Content-Type: text/plain; charset=UTF-8' );
    
    wp_mail( $email, $subject, $message, $headers );
}

/**
 * Send franchise inquiry in progress notification to applicant
 * 
 * @param int $inquiry_id Inquiry post ID
 */
function aqualuxe_send_franchise_inquiry_in_progress_notification( $inquiry_id ) {
    $first_name = get_post_meta( $inquiry_id, '_franchise_inquiry_first_name', true );
    $email = get_post_meta( $inquiry_id, '_franchise_inquiry_email', true );
    
    $subject = sprintf( __( 'Franchise Application Update - %s', 'aqualuxe' ), get_bloginfo( 'name' ) );
    
    $message = sprintf(
        __( 'Dear %s,', 'aqualuxe' ),
        $first_name
    ) . "\n\n";
    
    $message .= __( 'We are pleased to inform you that your franchise application is progressing well. We have completed our initial review of your application and are moving forward with the next steps in the process.', 'aqualuxe' ) . "\n\n";
    
    $message .= __( 'Next Steps:', 'aqualuxe' ) . "\n";
    $message .= __( '1. Background check and financial verification', 'aqualuxe' ) . "\n";
    $message .= __( '2. In-person meeting at our headquarters', 'aqualuxe' ) . "\n";
    $message .= __( '3. Site selection and approval', 'aqualuxe' ) . "\n";
    $message .= __( '4. Franchise agreement review and signing', 'aqualuxe' ) . "\n\n";
    
    $message .= __( 'Our franchise development team will be in touch shortly to schedule the next steps and provide you with any necessary forms or information.', 'aqualuxe' ) . "\n\n";
    
    $message .= __( 'We are excited about your progress and look forward to continuing this journey with you.', 'aqualuxe' ) . "\n\n";
    
    $message .= sprintf(
        __( 'Best regards,', 'aqualuxe' ) . "\n%s\n%s",
        __( 'Franchise Development Team', 'aqualuxe' ),
        get_bloginfo( 'name' )
    );
    
    $headers = array( 'Content-Type: text/plain; charset=UTF-8' );
    
    wp_mail( $email, $subject, $message, $headers );
}

/**
 * Send franchise inquiry completed notification to applicant
 * 
 * @param int $inquiry_id Inquiry post ID
 */
function aqualuxe_send_franchise_inquiry_completed_notification( $inquiry_id ) {
    $first_name = get_post_meta( $inquiry_id, '_franchise_inquiry_first_name', true );
    $email = get_post_meta( $inquiry_id, '_franchise_inquiry_email', true );
    
    $subject = sprintf( __( 'Welcome to the AquaLuxe Franchise Family!', 'aqualuxe' ), get_bloginfo( 'name' ) );
    
    $message = sprintf(
        __( 'Dear %s,', 'aqualuxe' ),
        $first_name
    ) . "\n\n";
    
    $message .= __( 'Congratulations! We are thrilled to officially welcome you to the AquaLuxe franchise family. Your franchise agreement has been processed, and you are now an official AquaLuxe franchise owner.', 'aqualuxe' ) . "\n\n";
    
    $message .= __( 'Next Steps:', 'aqualuxe' ) . "\n";
    $message .= __( '1. You will receive an invitation to our franchise onboarding portal within 24 hours.', 'aqualuxe' ) . "\n";
    $message .= __( '2. Your training is scheduled to begin on [Training Start Date].', 'aqualuxe' ) . "\n";
    $message .= __( '3. Your franchise support specialist will contact you to begin the site development process.', 'aqualuxe' ) . "\n";
    $message .= __( '4. You will receive access to our franchise operations manual and marketing materials.', 'aqualuxe' ) . "\n\n";
    
    $message .= __( 'We are committed to your success and are here to support you every step of the way. If you have any questions or need assistance, please do not hesitate to contact your franchise support specialist or email franchisesupport@aqualuxe.com.', 'aqualuxe' ) . "\n\n";
    
    $message .= __( 'Welcome aboard!', 'aqualuxe' ) . "\n\n";
    
    $message .= sprintf(
        __( 'Best regards,', 'aqualuxe' ) . "\n%s\n%s",
        __( 'Franchise Development Team', 'aqualuxe' ),
        get_bloginfo( 'name' )
    );
    
    $headers = array( 'Content-Type: text/plain; charset=UTF-8' );
    
    wp_mail( $email, $subject, $message, $headers );
}

/**
 * Add franchise system scripts and styles
 */
function aqualuxe_enqueue_franchise_scripts() {
    // Only enqueue on franchise inquiry page
    if ( is_page_template( 'page-franchise.php' ) ) {
        // Enqueue franchise script
        wp_enqueue_script(
            'aqualuxe-franchise',
            AQUALUXE_URI . 'assets/js/franchise.js',
            array( 'jquery' ),
            AQUALUXE_VERSION,
            true
        );
        
        // Localize script with settings
        wp_localize_script(
            'aqualuxe-franchise',
            'aqualuxeFranchiseSettings',
            array(
                'ajaxUrl' => admin_url( 'admin-ajax.php' ),
                'nonce'   => wp_create_nonce( 'aqualuxe_ajax_nonce' ),
            )
        );
    }
}
add_action( 'wp_enqueue_scripts', 'aqualuxe_enqueue_franchise_scripts' );