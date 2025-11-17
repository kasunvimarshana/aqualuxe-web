<?php
/**
 * Theme setup functions
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register custom post types
 */
function aqualuxe_register_post_types() {
    // Services post type
    register_post_type('aqualuxe_service', array(
        'labels' => array(
            'name' => __('Services', 'aqualuxe'),
            'singular_name' => __('Service', 'aqualuxe'),
            'add_new' => __('Add New', 'aqualuxe'),
            'add_new_item' => __('Add New Service', 'aqualuxe'),
            'edit_item' => __('Edit Service', 'aqualuxe'),
            'new_item' => __('New Service', 'aqualuxe'),
            'view_item' => __('View Service', 'aqualuxe'),
            'search_items' => __('Search Services', 'aqualuxe'),
            'not_found' => __('No services found', 'aqualuxe'),
            'not_found_in_trash' => __('No services found in Trash', 'aqualuxe'),
            'parent_item_colon' => __('Parent Service:', 'aqualuxe'),
            'menu_name' => __('Services', 'aqualuxe'),
        ),
        'public' => true,
        'hierarchical' => false,
        'show_ui' => true,
        'show_in_nav_menus' => true,
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
        'has_archive' => true,
        'rewrite' => array('slug' => 'services'),
        'menu_icon' => 'dashicons-admin-tools',
        'show_in_rest' => true,
    ));

    // Testimonials post type
    register_post_type('aqualuxe_testimonial', array(
        'labels' => array(
            'name' => __('Testimonials', 'aqualuxe'),
            'singular_name' => __('Testimonial', 'aqualuxe'),
            'add_new' => __('Add New', 'aqualuxe'),
            'add_new_item' => __('Add New Testimonial', 'aqualuxe'),
            'edit_item' => __('Edit Testimonial', 'aqualuxe'),
            'new_item' => __('New Testimonial', 'aqualuxe'),
            'view_item' => __('View Testimonial', 'aqualuxe'),
            'search_items' => __('Search Testimonials', 'aqualuxe'),
            'not_found' => __('No testimonials found', 'aqualuxe'),
            'not_found_in_trash' => __('No testimonials found in Trash', 'aqualuxe'),
            'parent_item_colon' => __('Parent Testimonial:', 'aqualuxe'),
            'menu_name' => __('Testimonials', 'aqualuxe'),
        ),
        'public' => true,
        'hierarchical' => false,
        'show_ui' => true,
        'show_in_nav_menus' => false,
        'supports' => array('title', 'editor', 'thumbnail', 'custom-fields'),
        'has_archive' => false,
        'rewrite' => array('slug' => 'testimonials'),
        'menu_icon' => 'dashicons-format-quote',
        'show_in_rest' => true,
    ));

    // Team members post type
    register_post_type('aqualuxe_team', array(
        'labels' => array(
            'name' => __('Team', 'aqualuxe'),
            'singular_name' => __('Team Member', 'aqualuxe'),
            'add_new' => __('Add New', 'aqualuxe'),
            'add_new_item' => __('Add New Team Member', 'aqualuxe'),
            'edit_item' => __('Edit Team Member', 'aqualuxe'),
            'new_item' => __('New Team Member', 'aqualuxe'),
            'view_item' => __('View Team Member', 'aqualuxe'),
            'search_items' => __('Search Team Members', 'aqualuxe'),
            'not_found' => __('No team members found', 'aqualuxe'),
            'not_found_in_trash' => __('No team members found in Trash', 'aqualuxe'),
            'parent_item_colon' => __('Parent Team Member:', 'aqualuxe'),
            'menu_name' => __('Team', 'aqualuxe'),
        ),
        'public' => true,
        'hierarchical' => false,
        'show_ui' => true,
        'show_in_nav_menus' => false,
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
        'has_archive' => true,
        'rewrite' => array('slug' => 'team'),
        'menu_icon' => 'dashicons-groups',
        'show_in_rest' => true,
    ));

    // FAQ post type
    register_post_type('aqualuxe_faq', array(
        'labels' => array(
            'name' => __('FAQs', 'aqualuxe'),
            'singular_name' => __('FAQ', 'aqualuxe'),
            'add_new' => __('Add New', 'aqualuxe'),
            'add_new_item' => __('Add New FAQ', 'aqualuxe'),
            'edit_item' => __('Edit FAQ', 'aqualuxe'),
            'new_item' => __('New FAQ', 'aqualuxe'),
            'view_item' => __('View FAQ', 'aqualuxe'),
            'search_items' => __('Search FAQs', 'aqualuxe'),
            'not_found' => __('No FAQs found', 'aqualuxe'),
            'not_found_in_trash' => __('No FAQs found in Trash', 'aqualuxe'),
            'parent_item_colon' => __('Parent FAQ:', 'aqualuxe'),
            'menu_name' => __('FAQs', 'aqualuxe'),
        ),
        'public' => true,
        'hierarchical' => false,
        'show_ui' => true,
        'show_in_nav_menus' => false,
        'supports' => array('title', 'editor', 'custom-fields'),
        'has_archive' => true,
        'rewrite' => array('slug' => 'faqs'),
        'menu_icon' => 'dashicons-editor-help',
        'show_in_rest' => true,
    ));
}
add_action('init', 'aqualuxe_register_post_types');

/**
 * Register custom taxonomies
 */
function aqualuxe_register_taxonomies() {
    // Service categories
    register_taxonomy('aqualuxe_service_cat', 'aqualuxe_service', array(
        'labels' => array(
            'name' => __('Service Categories', 'aqualuxe'),
            'singular_name' => __('Service Category', 'aqualuxe'),
            'search_items' => __('Search Service Categories', 'aqualuxe'),
            'all_items' => __('All Service Categories', 'aqualuxe'),
            'parent_item' => __('Parent Service Category', 'aqualuxe'),
            'parent_item_colon' => __('Parent Service Category:', 'aqualuxe'),
            'edit_item' => __('Edit Service Category', 'aqualuxe'),
            'update_item' => __('Update Service Category', 'aqualuxe'),
            'add_new_item' => __('Add New Service Category', 'aqualuxe'),
            'new_item_name' => __('New Service Category Name', 'aqualuxe'),
            'menu_name' => __('Categories', 'aqualuxe'),
        ),
        'hierarchical' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'service-category'),
        'show_in_rest' => true,
    ));

    // FAQ categories
    register_taxonomy('aqualuxe_faq_cat', 'aqualuxe_faq', array(
        'labels' => array(
            'name' => __('FAQ Categories', 'aqualuxe'),
            'singular_name' => __('FAQ Category', 'aqualuxe'),
            'search_items' => __('Search FAQ Categories', 'aqualuxe'),
            'all_items' => __('All FAQ Categories', 'aqualuxe'),
            'parent_item' => __('Parent FAQ Category', 'aqualuxe'),
            'parent_item_colon' => __('Parent FAQ Category:', 'aqualuxe'),
            'edit_item' => __('Edit FAQ Category', 'aqualuxe'),
            'update_item' => __('Update FAQ Category', 'aqualuxe'),
            'add_new_item' => __('Add New FAQ Category', 'aqualuxe'),
            'new_item_name' => __('New FAQ Category Name', 'aqualuxe'),
            'menu_name' => __('Categories', 'aqualuxe'),
        ),
        'hierarchical' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'faq-category'),
        'show_in_rest' => true,
    ));

    // Team member departments
    register_taxonomy('aqualuxe_team_dept', 'aqualuxe_team', array(
        'labels' => array(
            'name' => __('Departments', 'aqualuxe'),
            'singular_name' => __('Department', 'aqualuxe'),
            'search_items' => __('Search Departments', 'aqualuxe'),
            'all_items' => __('All Departments', 'aqualuxe'),
            'parent_item' => __('Parent Department', 'aqualuxe'),
            'parent_item_colon' => __('Parent Department:', 'aqualuxe'),
            'edit_item' => __('Edit Department', 'aqualuxe'),
            'update_item' => __('Update Department', 'aqualuxe'),
            'add_new_item' => __('Add New Department', 'aqualuxe'),
            'new_item_name' => __('New Department Name', 'aqualuxe'),
            'menu_name' => __('Departments', 'aqualuxe'),
        ),
        'hierarchical' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'department'),
        'show_in_rest' => true,
    ));
}
add_action('init', 'aqualuxe_register_taxonomies');

/**
 * Register custom meta boxes
 */
function aqualuxe_register_meta_boxes() {
    // Service details meta box
    add_meta_box(
        'aqualuxe_service_details',
        __('Service Details', 'aqualuxe'),
        'aqualuxe_service_details_callback',
        'aqualuxe_service',
        'normal',
        'high'
    );

    // Testimonial details meta box
    add_meta_box(
        'aqualuxe_testimonial_details',
        __('Testimonial Details', 'aqualuxe'),
        'aqualuxe_testimonial_details_callback',
        'aqualuxe_testimonial',
        'normal',
        'high'
    );

    // Team member details meta box
    add_meta_box(
        'aqualuxe_team_details',
        __('Team Member Details', 'aqualuxe'),
        'aqualuxe_team_details_callback',
        'aqualuxe_team',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'aqualuxe_register_meta_boxes');

/**
 * Service details meta box callback
 *
 * @param WP_Post $post Current post object
 */
function aqualuxe_service_details_callback($post) {
    // Add nonce for security
    wp_nonce_field('aqualuxe_service_details', 'aqualuxe_service_details_nonce');

    // Get current values
    $price = get_post_meta($post->ID, '_aqualuxe_service_price', true);
    $duration = get_post_meta($post->ID, '_aqualuxe_service_duration', true);
    $icon = get_post_meta($post->ID, '_aqualuxe_service_icon', true);
    $featured = get_post_meta($post->ID, '_aqualuxe_service_featured', true);

    // Output fields
    ?>
    <p>
        <label for="aqualuxe_service_price"><?php _e('Price', 'aqualuxe'); ?>:</label>
        <input type="text" id="aqualuxe_service_price" name="aqualuxe_service_price" value="<?php echo esc_attr($price); ?>" />
    </p>
    <p>
        <label for="aqualuxe_service_duration"><?php _e('Duration', 'aqualuxe'); ?>:</label>
        <input type="text" id="aqualuxe_service_duration" name="aqualuxe_service_duration" value="<?php echo esc_attr($duration); ?>" />
    </p>
    <p>
        <label for="aqualuxe_service_icon"><?php _e('Icon Class', 'aqualuxe'); ?>:</label>
        <input type="text" id="aqualuxe_service_icon" name="aqualuxe_service_icon" value="<?php echo esc_attr($icon); ?>" />
    </p>
    <p>
        <input type="checkbox" id="aqualuxe_service_featured" name="aqualuxe_service_featured" <?php checked($featured, 'yes'); ?> />
        <label for="aqualuxe_service_featured"><?php _e('Featured Service', 'aqualuxe'); ?></label>
    </p>
    <?php
}

/**
 * Testimonial details meta box callback
 *
 * @param WP_Post $post Current post object
 */
function aqualuxe_testimonial_details_callback($post) {
    // Add nonce for security
    wp_nonce_field('aqualuxe_testimonial_details', 'aqualuxe_testimonial_details_nonce');

    // Get current values
    $author = get_post_meta($post->ID, '_aqualuxe_testimonial_author', true);
    $position = get_post_meta($post->ID, '_aqualuxe_testimonial_position', true);
    $company = get_post_meta($post->ID, '_aqualuxe_testimonial_company', true);
    $rating = get_post_meta($post->ID, '_aqualuxe_testimonial_rating', true);

    // Output fields
    ?>
    <p>
        <label for="aqualuxe_testimonial_author"><?php _e('Author', 'aqualuxe'); ?>:</label>
        <input type="text" id="aqualuxe_testimonial_author" name="aqualuxe_testimonial_author" value="<?php echo esc_attr($author); ?>" />
    </p>
    <p>
        <label for="aqualuxe_testimonial_position"><?php _e('Position', 'aqualuxe'); ?>:</label>
        <input type="text" id="aqualuxe_testimonial_position" name="aqualuxe_testimonial_position" value="<?php echo esc_attr($position); ?>" />
    </p>
    <p>
        <label for="aqualuxe_testimonial_company"><?php _e('Company', 'aqualuxe'); ?>:</label>
        <input type="text" id="aqualuxe_testimonial_company" name="aqualuxe_testimonial_company" value="<?php echo esc_attr($company); ?>" />
    </p>
    <p>
        <label for="aqualuxe_testimonial_rating"><?php _e('Rating (1-5)', 'aqualuxe'); ?>:</label>
        <input type="number" id="aqualuxe_testimonial_rating" name="aqualuxe_testimonial_rating" min="1" max="5" value="<?php echo esc_attr($rating); ?>" />
    </p>
    <?php
}

/**
 * Team member details meta box callback
 *
 * @param WP_Post $post Current post object
 */
function aqualuxe_team_details_callback($post) {
    // Add nonce for security
    wp_nonce_field('aqualuxe_team_details', 'aqualuxe_team_details_nonce');

    // Get current values
    $position = get_post_meta($post->ID, '_aqualuxe_team_position', true);
    $email = get_post_meta($post->ID, '_aqualuxe_team_email', true);
    $phone = get_post_meta($post->ID, '_aqualuxe_team_phone', true);
    $social = get_post_meta($post->ID, '_aqualuxe_team_social', true);

    // Output fields
    ?>
    <p>
        <label for="aqualuxe_team_position"><?php _e('Position', 'aqualuxe'); ?>:</label>
        <input type="text" id="aqualuxe_team_position" name="aqualuxe_team_position" value="<?php echo esc_attr($position); ?>" />
    </p>
    <p>
        <label for="aqualuxe_team_email"><?php _e('Email', 'aqualuxe'); ?>:</label>
        <input type="email" id="aqualuxe_team_email" name="aqualuxe_team_email" value="<?php echo esc_attr($email); ?>" />
    </p>
    <p>
        <label for="aqualuxe_team_phone"><?php _e('Phone', 'aqualuxe'); ?>:</label>
        <input type="text" id="aqualuxe_team_phone" name="aqualuxe_team_phone" value="<?php echo esc_attr($phone); ?>" />
    </p>
    <p>
        <label for="aqualuxe_team_social"><?php _e('Social Media Links (JSON)', 'aqualuxe'); ?>:</label>
        <textarea id="aqualuxe_team_social" name="aqualuxe_team_social" rows="5" cols="30"><?php echo esc_textarea($social); ?></textarea>
        <span class="description"><?php _e('Enter social media links in JSON format. Example: {"facebook":"https://facebook.com/username","twitter":"https://twitter.com/username"}', 'aqualuxe'); ?></span>
    </p>
    <?php
}

/**
 * Save meta box data
 *
 * @param int $post_id Post ID
 */
function aqualuxe_save_meta_box_data($post_id) {
    // Check if we're doing an autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Service details
    if (isset($_POST['aqualuxe_service_details_nonce']) && wp_verify_nonce($_POST['aqualuxe_service_details_nonce'], 'aqualuxe_service_details')) {
        if (current_user_can('edit_post', $post_id)) {
            // Save service details
            if (isset($_POST['aqualuxe_service_price'])) {
                update_post_meta($post_id, '_aqualuxe_service_price', sanitize_text_field($_POST['aqualuxe_service_price']));
            }
            if (isset($_POST['aqualuxe_service_duration'])) {
                update_post_meta($post_id, '_aqualuxe_service_duration', sanitize_text_field($_POST['aqualuxe_service_duration']));
            }
            if (isset($_POST['aqualuxe_service_icon'])) {
                update_post_meta($post_id, '_aqualuxe_service_icon', sanitize_text_field($_POST['aqualuxe_service_icon']));
            }
            update_post_meta($post_id, '_aqualuxe_service_featured', isset($_POST['aqualuxe_service_featured']) ? 'yes' : 'no');
        }
    }

    // Testimonial details
    if (isset($_POST['aqualuxe_testimonial_details_nonce']) && wp_verify_nonce($_POST['aqualuxe_testimonial_details_nonce'], 'aqualuxe_testimonial_details')) {
        if (current_user_can('edit_post', $post_id)) {
            // Save testimonial details
            if (isset($_POST['aqualuxe_testimonial_author'])) {
                update_post_meta($post_id, '_aqualuxe_testimonial_author', sanitize_text_field($_POST['aqualuxe_testimonial_author']));
            }
            if (isset($_POST['aqualuxe_testimonial_position'])) {
                update_post_meta($post_id, '_aqualuxe_testimonial_position', sanitize_text_field($_POST['aqualuxe_testimonial_position']));
            }
            if (isset($_POST['aqualuxe_testimonial_company'])) {
                update_post_meta($post_id, '_aqualuxe_testimonial_company', sanitize_text_field($_POST['aqualuxe_testimonial_company']));
            }
            if (isset($_POST['aqualuxe_testimonial_rating'])) {
                $rating = intval($_POST['aqualuxe_testimonial_rating']);
                $rating = max(1, min(5, $rating)); // Ensure rating is between 1 and 5
                update_post_meta($post_id, '_aqualuxe_testimonial_rating', $rating);
            }
        }
    }

    // Team member details
    if (isset($_POST['aqualuxe_team_details_nonce']) && wp_verify_nonce($_POST['aqualuxe_team_details_nonce'], 'aqualuxe_team_details')) {
        if (current_user_can('edit_post', $post_id)) {
            // Save team member details
            if (isset($_POST['aqualuxe_team_position'])) {
                update_post_meta($post_id, '_aqualuxe_team_position', sanitize_text_field($_POST['aqualuxe_team_position']));
            }
            if (isset($_POST['aqualuxe_team_email'])) {
                update_post_meta($post_id, '_aqualuxe_team_email', sanitize_email($_POST['aqualuxe_team_email']));
            }
            if (isset($_POST['aqualuxe_team_phone'])) {
                update_post_meta($post_id, '_aqualuxe_team_phone', sanitize_text_field($_POST['aqualuxe_team_phone']));
            }
            if (isset($_POST['aqualuxe_team_social'])) {
                // Validate JSON
                $social = trim($_POST['aqualuxe_team_social']);
                if (!empty($social)) {
                    json_decode($social);
                    if (json_last_error() === JSON_ERROR_NONE) {
                        update_post_meta($post_id, '_aqualuxe_team_social', $social);
                    }
                } else {
                    update_post_meta($post_id, '_aqualuxe_team_social', '');
                }
            }
        }
    }
}
add_action('save_post', 'aqualuxe_save_meta_box_data');

/**
 * Add theme options page
 */
function aqualuxe_add_theme_options_page() {
    add_theme_page(
        __('AquaLuxe Options', 'aqualuxe'),
        __('AquaLuxe Options', 'aqualuxe'),
        'manage_options',
        'aqualuxe-options',
        'aqualuxe_theme_options_page'
    );
}
add_action('admin_menu', 'aqualuxe_add_theme_options_page');

/**
 * Theme options page callback
 */
function aqualuxe_theme_options_page() {
    // Redirect to Customizer
    ?>
    <div class="wrap">
        <h1><?php _e('AquaLuxe Theme Options', 'aqualuxe'); ?></h1>
        <p><?php _e('Theme options are managed through the Customizer.', 'aqualuxe'); ?></p>
        <p><a href="<?php echo esc_url(admin_url('customize.php')); ?>" class="button button-primary"><?php _e('Go to Customizer', 'aqualuxe'); ?></a></p>
    </div>
    <?php
}

/**
 * Add body classes
 *
 * @param array $classes Body classes
 * @return array Modified body classes
 */
function aqualuxe_body_classes($classes) {
    // Add class if WooCommerce is active
    if (class_exists('WooCommerce')) {
        $classes[] = 'woocommerce-active';
    } else {
        $classes[] = 'woocommerce-inactive';
    }

    // Add class for sidebar position
    $sidebar_position = get_theme_mod('aqualuxe_sidebar_position', 'right');
    $classes[] = 'sidebar-' . $sidebar_position;

    // Add class for dark mode
    if (isset($_COOKIE['aqualuxe_dark_mode']) && $_COOKIE['aqualuxe_dark_mode'] === 'true') {
        $classes[] = 'dark-mode';
    }

    return $classes;
}
add_filter('body_class', 'aqualuxe_body_classes');

/**
 * Add admin body classes
 *
 * @param string $classes Admin body classes
 * @return string Modified admin body classes
 */
function aqualuxe_admin_body_classes($classes) {
    $classes .= ' aqualuxe-admin';
    return $classes;
}
add_filter('admin_body_class', 'aqualuxe_admin_body_classes');

/**
 * Add custom image sizes to media library
 *
 * @param array $sizes Image sizes
 * @return array Modified image sizes
 */
function aqualuxe_custom_image_sizes($sizes) {
    return array_merge($sizes, array(
        'aqualuxe-featured' => __('Featured Image', 'aqualuxe'),
        'aqualuxe-product' => __('Product Image', 'aqualuxe'),
        'aqualuxe-thumbnail' => __('Thumbnail', 'aqualuxe'),
    ));
}
add_filter('image_size_names_choose', 'aqualuxe_custom_image_sizes');

/**
 * Add custom query vars
 *
 * @param array $vars Query vars
 * @return array Modified query vars
 */
function aqualuxe_query_vars($vars) {
    $vars[] = 'aqualuxe_filter';
    return $vars;
}
add_filter('query_vars', 'aqualuxe_query_vars');

/**
 * Add theme info page
 */
function aqualuxe_add_theme_info_page() {
    add_theme_page(
        __('AquaLuxe Info', 'aqualuxe'),
        __('AquaLuxe Info', 'aqualuxe'),
        'edit_theme_options',
        'aqualuxe-info',
        'aqualuxe_theme_info_page'
    );
}
add_action('admin_menu', 'aqualuxe_add_theme_info_page');

/**
 * Theme info page callback
 */
function aqualuxe_theme_info_page() {
    ?>
    <div class="wrap about-wrap">
        <h1><?php _e('Welcome to AquaLuxe', 'aqualuxe'); ?></h1>
        <div class="about-text">
            <?php _e('AquaLuxe is a premium WordPress theme for luxury aquatic businesses. Thank you for choosing AquaLuxe!', 'aqualuxe'); ?>
        </div>
        <div class="aqualuxe-badge">
            <span><?php _e('Version', 'aqualuxe'); ?> <?php echo AQUALUXE_VERSION; ?></span>
        </div>

        <h2 class="nav-tab-wrapper">
            <a class="nav-tab nav-tab-active" href="#getting-started"><?php _e('Getting Started', 'aqualuxe'); ?></a>
            <a class="nav-tab" href="#modules"><?php _e('Modules', 'aqualuxe'); ?></a>
            <a class="nav-tab" href="#support"><?php _e('Support', 'aqualuxe'); ?></a>
        </h2>

        <div id="getting-started" class="aqualuxe-tab-content">
            <h3><?php _e('Getting Started with AquaLuxe', 'aqualuxe'); ?></h3>
            <p><?php _e('Follow these steps to set up your AquaLuxe theme:', 'aqualuxe'); ?></p>
            <ol>
                <li><?php _e('Go to Appearance > Customize to configure theme options', 'aqualuxe'); ?></li>
                <li><?php _e('Import demo content from Appearance > Import Demo Data', 'aqualuxe'); ?></li>
                <li><?php _e('Set up your menus from Appearance > Menus', 'aqualuxe'); ?></li>
                <li><?php _e('Configure widgets from Appearance > Widgets', 'aqualuxe'); ?></li>
                <li><?php _e('Create your pages and posts', 'aqualuxe'); ?></li>
            </ol>
        </div>

        <div id="modules" class="aqualuxe-tab-content" style="display: none;">
            <h3><?php _e('AquaLuxe Modules', 'aqualuxe'); ?></h3>
            <p><?php _e('AquaLuxe comes with several modules that can be enabled or disabled from the Customizer:', 'aqualuxe'); ?></p>
            <ul>
                <li><?php _e('Dark Mode: Adds a dark mode toggle with persistent user preference', 'aqualuxe'); ?></li>
                <li><?php _e('Multilingual: Adds multilingual support for content and products', 'aqualuxe'); ?></li>
                <li><?php _e('Subscriptions: Adds subscription functionality for recurring payments and membership tiers', 'aqualuxe'); ?></li>
                <li><?php _e('Bookings: Adds booking and scheduling functionality for services', 'aqualuxe'); ?></li>
                <li><?php _e('Events: Adds events calendar with ticketing functionality', 'aqualuxe'); ?></li>
                <li><?php _e('Wholesale: Adds wholesale functionality for B2B customers', 'aqualuxe'); ?></li>
                <li><?php _e('Auctions: Adds auction and trade-in functionality', 'aqualuxe'); ?></li>
                <li><?php _e('Services: Adds professional services functionality', 'aqualuxe'); ?></li>
                <li><?php _e('Franchise: Adds franchise and licensing functionality', 'aqualuxe'); ?></li>
                <li><?php _e('Sustainability: Adds R&D and sustainability features', 'aqualuxe'); ?></li>
                <li><?php _e('Affiliates: Adds affiliate and referral program functionality', 'aqualuxe'); ?></li>
            </ul>
        </div>

        <div id="support" class="aqualuxe-tab-content" style="display: none;">
            <h3><?php _e('Support', 'aqualuxe'); ?></h3>
            <p><?php _e('If you need help with AquaLuxe, please contact our support team:', 'aqualuxe'); ?></p>
            <ul>
                <li><?php _e('Documentation: <a href="https://docs.aqualuxe.com" target="_blank">https://docs.aqualuxe.com</a>', 'aqualuxe'); ?></li>
                <li><?php _e('Support: <a href="mailto:support@aqualuxe.com">support@aqualuxe.com</a>', 'aqualuxe'); ?></li>
            </ul>
        </div>
    </div>
    <script>
        jQuery(document).ready(function($) {
            // Tab navigation
            $('.nav-tab').on('click', function(e) {
                e.preventDefault();
                var target = $(this).attr('href');
                $('.nav-tab').removeClass('nav-tab-active');
                $(this).addClass('nav-tab-active');
                $('.aqualuxe-tab-content').hide();
                $(target).show();
            });
        });
    </script>
    <style>
        .aqualuxe-badge {
            background: #0077b6;
            color: #fff;
            font-size: 14px;
            font-weight: 600;
            padding: 10px 15px;
            border-radius: 3px;
            margin-bottom: 20px;
            display: inline-block;
        }
        .aqualuxe-tab-content {
            margin-top: 20px;
        }
    </style>
    <?php
}