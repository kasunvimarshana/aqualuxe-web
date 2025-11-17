<?php
/**
 * Core functionality for the AquaLuxe theme
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

/**
 * Register and enqueue scripts and styles for the admin area.
 */
function aqualuxe_admin_scripts() {
    wp_enqueue_style(
        'aqualuxe-admin-style',
        AQUALUXE_ASSETS_URI . 'css/admin.css',
        array(),
        AQUALUXE_VERSION
    );

    wp_enqueue_script(
        'aqualuxe-admin-script',
        AQUALUXE_ASSETS_URI . 'js/admin.js',
        array('jquery'),
        AQUALUXE_VERSION,
        true
    );
}
add_action('admin_enqueue_scripts', 'aqualuxe_admin_scripts');

/**
 * Add preconnect for Google Fonts.
 *
 * @param array  $urls           URLs to print for resource hints.
 * @param string $relation_type  The relation type the URLs are printed.
 * @return array $urls           URLs to print for resource hints.
 */
function aqualuxe_resource_hints($urls, $relation_type) {
    if ('preconnect' === $relation_type) {
        $urls[] = array(
            'href' => 'https://fonts.gstatic.com',
            'crossorigin',
        );
    }

    return $urls;
}
add_filter('wp_resource_hints', 'aqualuxe_resource_hints', 10, 2);

/**
 * Register custom query vars
 *
 * @param array $vars The array of available query variables
 * @return array Modified array of query variables
 */
function aqualuxe_query_vars($vars) {
    $vars[] = 'dark_mode';
    return $vars;
}
add_filter('query_vars', 'aqualuxe_query_vars');

/**
 * Add custom image sizes to WordPress
 */
function aqualuxe_add_custom_image_sizes() {
    add_image_size('aqualuxe-hero', 1920, 800, true);
    add_image_size('aqualuxe-featured', 800, 600, true);
    add_image_size('aqualuxe-thumbnail', 400, 300, true);
}
add_action('after_setup_theme', 'aqualuxe_add_custom_image_sizes');

/**
 * Add custom image sizes to media library dropdown
 *
 * @param array $sizes Array of image sizes
 * @return array Modified array of image sizes
 */
function aqualuxe_custom_image_sizes_names($sizes) {
    return array_merge($sizes, array(
        'aqualuxe-hero' => __('Hero Image', 'aqualuxe'),
        'aqualuxe-featured' => __('Featured Image', 'aqualuxe'),
        'aqualuxe-thumbnail' => __('Custom Thumbnail', 'aqualuxe'),
    ));
}
add_filter('image_size_names_choose', 'aqualuxe_custom_image_sizes_names');

/**
 * Add custom body classes
 *
 * @param array $classes Array of body classes
 * @return array Modified array of body classes
 */
function aqualuxe_custom_body_classes($classes) {
    // Add a class if WooCommerce is active
    if (class_exists('WooCommerce')) {
        $classes[] = 'woocommerce-active';
    }

    // Add a class for the dark mode
    if (isset($_COOKIE['aqualuxe_dark_mode']) && $_COOKIE['aqualuxe_dark_mode'] === 'true') {
        $classes[] = 'dark-mode';
    }

    return $classes;
}
add_filter('body_class', 'aqualuxe_custom_body_classes');

/**
 * Register custom post types
 */
function aqualuxe_register_post_types() {
    // Register Service post type
    $labels = array(
        'name'               => _x('Services', 'post type general name', 'aqualuxe'),
        'singular_name'      => _x('Service', 'post type singular name', 'aqualuxe'),
        'menu_name'          => _x('Services', 'admin menu', 'aqualuxe'),
        'name_admin_bar'     => _x('Service', 'add new on admin bar', 'aqualuxe'),
        'add_new'            => _x('Add New', 'service', 'aqualuxe'),
        'add_new_item'       => __('Add New Service', 'aqualuxe'),
        'new_item'           => __('New Service', 'aqualuxe'),
        'edit_item'          => __('Edit Service', 'aqualuxe'),
        'view_item'          => __('View Service', 'aqualuxe'),
        'all_items'          => __('All Services', 'aqualuxe'),
        'search_items'       => __('Search Services', 'aqualuxe'),
        'parent_item_colon'  => __('Parent Services:', 'aqualuxe'),
        'not_found'          => __('No services found.', 'aqualuxe'),
        'not_found_in_trash' => __('No services found in Trash.', 'aqualuxe')
    );

    $args = array(
        'labels'             => $labels,
        'description'        => __('Description.', 'aqualuxe'),
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'service'),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'menu_icon'          => 'dashicons-hammer',
        'supports'           => array('title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments'),
        'show_in_rest'       => true,
    );

    register_post_type('service', $args);

    // Register Testimonial post type
    $labels = array(
        'name'               => _x('Testimonials', 'post type general name', 'aqualuxe'),
        'singular_name'      => _x('Testimonial', 'post type singular name', 'aqualuxe'),
        'menu_name'          => _x('Testimonials', 'admin menu', 'aqualuxe'),
        'name_admin_bar'     => _x('Testimonial', 'add new on admin bar', 'aqualuxe'),
        'add_new'            => _x('Add New', 'testimonial', 'aqualuxe'),
        'add_new_item'       => __('Add New Testimonial', 'aqualuxe'),
        'new_item'           => __('New Testimonial', 'aqualuxe'),
        'edit_item'          => __('Edit Testimonial', 'aqualuxe'),
        'view_item'          => __('View Testimonial', 'aqualuxe'),
        'all_items'          => __('All Testimonials', 'aqualuxe'),
        'search_items'       => __('Search Testimonials', 'aqualuxe'),
        'parent_item_colon'  => __('Parent Testimonials:', 'aqualuxe'),
        'not_found'          => __('No testimonials found.', 'aqualuxe'),
        'not_found_in_trash' => __('No testimonials found in Trash.', 'aqualuxe')
    );

    $args = array(
        'labels'             => $labels,
        'description'        => __('Customer testimonials', 'aqualuxe'),
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'testimonial'),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'menu_icon'          => 'dashicons-format-quote',
        'supports'           => array('title', 'editor', 'thumbnail', 'excerpt'),
        'show_in_rest'       => true,
    );

    register_post_type('testimonial', $args);

    // Register Team Member post type
    $labels = array(
        'name'               => _x('Team Members', 'post type general name', 'aqualuxe'),
        'singular_name'      => _x('Team Member', 'post type singular name', 'aqualuxe'),
        'menu_name'          => _x('Team', 'admin menu', 'aqualuxe'),
        'name_admin_bar'     => _x('Team Member', 'add new on admin bar', 'aqualuxe'),
        'add_new'            => _x('Add New', 'team member', 'aqualuxe'),
        'add_new_item'       => __('Add New Team Member', 'aqualuxe'),
        'new_item'           => __('New Team Member', 'aqualuxe'),
        'edit_item'          => __('Edit Team Member', 'aqualuxe'),
        'view_item'          => __('View Team Member', 'aqualuxe'),
        'all_items'          => __('All Team Members', 'aqualuxe'),
        'search_items'       => __('Search Team Members', 'aqualuxe'),
        'parent_item_colon'  => __('Parent Team Members:', 'aqualuxe'),
        'not_found'          => __('No team members found.', 'aqualuxe'),
        'not_found_in_trash' => __('No team members found in Trash.', 'aqualuxe')
    );

    $args = array(
        'labels'             => $labels,
        'description'        => __('Team members', 'aqualuxe'),
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'team'),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'menu_icon'          => 'dashicons-groups',
        'supports'           => array('title', 'editor', 'thumbnail', 'excerpt'),
        'show_in_rest'       => true,
    );

    register_post_type('team', $args);

    // Register FAQ post type
    $labels = array(
        'name'               => _x('FAQs', 'post type general name', 'aqualuxe'),
        'singular_name'      => _x('FAQ', 'post type singular name', 'aqualuxe'),
        'menu_name'          => _x('FAQs', 'admin menu', 'aqualuxe'),
        'name_admin_bar'     => _x('FAQ', 'add new on admin bar', 'aqualuxe'),
        'add_new'            => _x('Add New', 'faq', 'aqualuxe'),
        'add_new_item'       => __('Add New FAQ', 'aqualuxe'),
        'new_item'           => __('New FAQ', 'aqualuxe'),
        'edit_item'          => __('Edit FAQ', 'aqualuxe'),
        'view_item'          => __('View FAQ', 'aqualuxe'),
        'all_items'          => __('All FAQs', 'aqualuxe'),
        'search_items'       => __('Search FAQs', 'aqualuxe'),
        'parent_item_colon'  => __('Parent FAQs:', 'aqualuxe'),
        'not_found'          => __('No FAQs found.', 'aqualuxe'),
        'not_found_in_trash' => __('No FAQs found in Trash.', 'aqualuxe')
    );

    $args = array(
        'labels'             => $labels,
        'description'        => __('Frequently Asked Questions', 'aqualuxe'),
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'faq'),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'menu_icon'          => 'dashicons-editor-help',
        'supports'           => array('title', 'editor'),
        'show_in_rest'       => true,
    );

    register_post_type('faq', $args);
}
add_action('init', 'aqualuxe_register_post_types');

/**
 * Register custom taxonomies
 */
function aqualuxe_register_taxonomies() {
    // Register Service Category taxonomy
    $labels = array(
        'name'              => _x('Service Categories', 'taxonomy general name', 'aqualuxe'),
        'singular_name'     => _x('Service Category', 'taxonomy singular name', 'aqualuxe'),
        'search_items'      => __('Search Service Categories', 'aqualuxe'),
        'all_items'         => __('All Service Categories', 'aqualuxe'),
        'parent_item'       => __('Parent Service Category', 'aqualuxe'),
        'parent_item_colon' => __('Parent Service Category:', 'aqualuxe'),
        'edit_item'         => __('Edit Service Category', 'aqualuxe'),
        'update_item'       => __('Update Service Category', 'aqualuxe'),
        'add_new_item'      => __('Add New Service Category', 'aqualuxe'),
        'new_item_name'     => __('New Service Category Name', 'aqualuxe'),
        'menu_name'         => __('Categories', 'aqualuxe'),
    );

    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'service-category'),
        'show_in_rest'      => true,
    );

    register_taxonomy('service_category', array('service'), $args);

    // Register FAQ Category taxonomy
    $labels = array(
        'name'              => _x('FAQ Categories', 'taxonomy general name', 'aqualuxe'),
        'singular_name'     => _x('FAQ Category', 'taxonomy singular name', 'aqualuxe'),
        'search_items'      => __('Search FAQ Categories', 'aqualuxe'),
        'all_items'         => __('All FAQ Categories', 'aqualuxe'),
        'parent_item'       => __('Parent FAQ Category', 'aqualuxe'),
        'parent_item_colon' => __('Parent FAQ Category:', 'aqualuxe'),
        'edit_item'         => __('Edit FAQ Category', 'aqualuxe'),
        'update_item'       => __('Update FAQ Category', 'aqualuxe'),
        'add_new_item'      => __('Add New FAQ Category', 'aqualuxe'),
        'new_item_name'     => __('New FAQ Category Name', 'aqualuxe'),
        'menu_name'         => __('Categories', 'aqualuxe'),
    );

    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'faq-category'),
        'show_in_rest'      => true,
    );

    register_taxonomy('faq_category', array('faq'), $args);

    // Register Team Department taxonomy
    $labels = array(
        'name'              => _x('Departments', 'taxonomy general name', 'aqualuxe'),
        'singular_name'     => _x('Department', 'taxonomy singular name', 'aqualuxe'),
        'search_items'      => __('Search Departments', 'aqualuxe'),
        'all_items'         => __('All Departments', 'aqualuxe'),
        'parent_item'       => __('Parent Department', 'aqualuxe'),
        'parent_item_colon' => __('Parent Department:', 'aqualuxe'),
        'edit_item'         => __('Edit Department', 'aqualuxe'),
        'update_item'       => __('Update Department', 'aqualuxe'),
        'add_new_item'      => __('Add New Department', 'aqualuxe'),
        'new_item_name'     => __('New Department Name', 'aqualuxe'),
        'menu_name'         => __('Departments', 'aqualuxe'),
    );

    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'department'),
        'show_in_rest'      => true,
    );

    register_taxonomy('department', array('team'), $args);
}
add_action('init', 'aqualuxe_register_taxonomies');

/**
 * Add custom meta boxes
 */
function aqualuxe_add_meta_boxes() {
    // Add meta box for team members
    add_meta_box(
        'aqualuxe_team_meta',
        __('Team Member Details', 'aqualuxe'),
        'aqualuxe_team_meta_callback',
        'team',
        'normal',
        'high'
    );

    // Add meta box for services
    add_meta_box(
        'aqualuxe_service_meta',
        __('Service Details', 'aqualuxe'),
        'aqualuxe_service_meta_callback',
        'service',
        'normal',
        'high'
    );

    // Add meta box for testimonials
    add_meta_box(
        'aqualuxe_testimonial_meta',
        __('Testimonial Details', 'aqualuxe'),
        'aqualuxe_testimonial_meta_callback',
        'testimonial',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'aqualuxe_add_meta_boxes');

/**
 * Team meta box callback
 */
function aqualuxe_team_meta_callback($post) {
    wp_nonce_field('aqualuxe_team_meta', 'aqualuxe_team_meta_nonce');

    $position = get_post_meta($post->ID, '_aqualuxe_team_position', true);
    $email = get_post_meta($post->ID, '_aqualuxe_team_email', true);
    $phone = get_post_meta($post->ID, '_aqualuxe_team_phone', true);
    $facebook = get_post_meta($post->ID, '_aqualuxe_team_facebook', true);
    $twitter = get_post_meta($post->ID, '_aqualuxe_team_twitter', true);
    $linkedin = get_post_meta($post->ID, '_aqualuxe_team_linkedin', true);
    $instagram = get_post_meta($post->ID, '_aqualuxe_team_instagram', true);
    ?>
    <p>
        <label for="aqualuxe_team_position"><?php esc_html_e('Position', 'aqualuxe'); ?></label><br>
        <input type="text" id="aqualuxe_team_position" name="aqualuxe_team_position" value="<?php echo esc_attr($position); ?>" class="widefat">
    </p>
    <p>
        <label for="aqualuxe_team_email"><?php esc_html_e('Email', 'aqualuxe'); ?></label><br>
        <input type="email" id="aqualuxe_team_email" name="aqualuxe_team_email" value="<?php echo esc_attr($email); ?>" class="widefat">
    </p>
    <p>
        <label for="aqualuxe_team_phone"><?php esc_html_e('Phone', 'aqualuxe'); ?></label><br>
        <input type="text" id="aqualuxe_team_phone" name="aqualuxe_team_phone" value="<?php echo esc_attr($phone); ?>" class="widefat">
    </p>
    <p>
        <label for="aqualuxe_team_facebook"><?php esc_html_e('Facebook URL', 'aqualuxe'); ?></label><br>
        <input type="url" id="aqualuxe_team_facebook" name="aqualuxe_team_facebook" value="<?php echo esc_url($facebook); ?>" class="widefat">
    </p>
    <p>
        <label for="aqualuxe_team_twitter"><?php esc_html_e('Twitter URL', 'aqualuxe'); ?></label><br>
        <input type="url" id="aqualuxe_team_twitter" name="aqualuxe_team_twitter" value="<?php echo esc_url($twitter); ?>" class="widefat">
    </p>
    <p>
        <label for="aqualuxe_team_linkedin"><?php esc_html_e('LinkedIn URL', 'aqualuxe'); ?></label><br>
        <input type="url" id="aqualuxe_team_linkedin" name="aqualuxe_team_linkedin" value="<?php echo esc_url($linkedin); ?>" class="widefat">
    </p>
    <p>
        <label for="aqualuxe_team_instagram"><?php esc_html_e('Instagram URL', 'aqualuxe'); ?></label><br>
        <input type="url" id="aqualuxe_team_instagram" name="aqualuxe_team_instagram" value="<?php echo esc_url($instagram); ?>" class="widefat">
    </p>
    <?php
}

/**
 * Service meta box callback
 */
function aqualuxe_service_meta_callback($post) {
    wp_nonce_field('aqualuxe_service_meta', 'aqualuxe_service_meta_nonce');

    $price = get_post_meta($post->ID, '_aqualuxe_service_price', true);
    $duration = get_post_meta($post->ID, '_aqualuxe_service_duration', true);
    $icon = get_post_meta($post->ID, '_aqualuxe_service_icon', true);
    ?>
    <p>
        <label for="aqualuxe_service_price"><?php esc_html_e('Price', 'aqualuxe'); ?></label><br>
        <input type="text" id="aqualuxe_service_price" name="aqualuxe_service_price" value="<?php echo esc_attr($price); ?>" class="widefat">
    </p>
    <p>
        <label for="aqualuxe_service_duration"><?php esc_html_e('Duration', 'aqualuxe'); ?></label><br>
        <input type="text" id="aqualuxe_service_duration" name="aqualuxe_service_duration" value="<?php echo esc_attr($duration); ?>" class="widefat">
        <small><?php esc_html_e('Example: 2 hours, 30 minutes, etc.', 'aqualuxe'); ?></small>
    </p>
    <p>
        <label for="aqualuxe_service_icon"><?php esc_html_e('Icon Class', 'aqualuxe'); ?></label><br>
        <input type="text" id="aqualuxe_service_icon" name="aqualuxe_service_icon" value="<?php echo esc_attr($icon); ?>" class="widefat">
        <small><?php esc_html_e('Enter an icon class (e.g., "fish", "water", "tank").', 'aqualuxe'); ?></small>
    </p>
    <?php
}

/**
 * Testimonial meta box callback
 */
function aqualuxe_testimonial_meta_callback($post) {
    wp_nonce_field('aqualuxe_testimonial_meta', 'aqualuxe_testimonial_meta_nonce');

    $company = get_post_meta($post->ID, '_aqualuxe_testimonial_company', true);
    $position = get_post_meta($post->ID, '_aqualuxe_testimonial_position', true);
    $rating = get_post_meta($post->ID, '_aqualuxe_testimonial_rating', true);
    ?>
    <p>
        <label for="aqualuxe_testimonial_company"><?php esc_html_e('Company', 'aqualuxe'); ?></label><br>
        <input type="text" id="aqualuxe_testimonial_company" name="aqualuxe_testimonial_company" value="<?php echo esc_attr($company); ?>" class="widefat">
    </p>
    <p>
        <label for="aqualuxe_testimonial_position"><?php esc_html_e('Position', 'aqualuxe'); ?></label><br>
        <input type="text" id="aqualuxe_testimonial_position" name="aqualuxe_testimonial_position" value="<?php echo esc_attr($position); ?>" class="widefat">
    </p>
    <p>
        <label for="aqualuxe_testimonial_rating"><?php esc_html_e('Rating (1-5)', 'aqualuxe'); ?></label><br>
        <select id="aqualuxe_testimonial_rating" name="aqualuxe_testimonial_rating" class="widefat">
            <option value=""><?php esc_html_e('Select Rating', 'aqualuxe'); ?></option>
            <?php for ($i = 1; $i <= 5; $i++) : ?>
                <option value="<?php echo esc_attr($i); ?>" <?php selected($rating, $i); ?>><?php echo esc_html($i); ?></option>
            <?php endfor; ?>
        </select>
    </p>
    <?php
}

/**
 * Save meta box data
 */
function aqualuxe_save_meta_box_data($post_id) {
    // Check if our nonce is set for team
    if (isset($_POST['aqualuxe_team_meta_nonce'])) {
        if (!wp_verify_nonce($_POST['aqualuxe_team_meta_nonce'], 'aqualuxe_team_meta')) {
            return;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        // Save team meta
        if (isset($_POST['aqualuxe_team_position'])) {
            update_post_meta($post_id, '_aqualuxe_team_position', sanitize_text_field($_POST['aqualuxe_team_position']));
        }
        if (isset($_POST['aqualuxe_team_email'])) {
            update_post_meta($post_id, '_aqualuxe_team_email', sanitize_email($_POST['aqualuxe_team_email']));
        }
        if (isset($_POST['aqualuxe_team_phone'])) {
            update_post_meta($post_id, '_aqualuxe_team_phone', sanitize_text_field($_POST['aqualuxe_team_phone']));
        }
        if (isset($_POST['aqualuxe_team_facebook'])) {
            update_post_meta($post_id, '_aqualuxe_team_facebook', esc_url_raw($_POST['aqualuxe_team_facebook']));
        }
        if (isset($_POST['aqualuxe_team_twitter'])) {
            update_post_meta($post_id, '_aqualuxe_team_twitter', esc_url_raw($_POST['aqualuxe_team_twitter']));
        }
        if (isset($_POST['aqualuxe_team_linkedin'])) {
            update_post_meta($post_id, '_aqualuxe_team_linkedin', esc_url_raw($_POST['aqualuxe_team_linkedin']));
        }
        if (isset($_POST['aqualuxe_team_instagram'])) {
            update_post_meta($post_id, '_aqualuxe_team_instagram', esc_url_raw($_POST['aqualuxe_team_instagram']));
        }
    }

    // Check if our nonce is set for service
    if (isset($_POST['aqualuxe_service_meta_nonce'])) {
        if (!wp_verify_nonce($_POST['aqualuxe_service_meta_nonce'], 'aqualuxe_service_meta')) {
            return;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        // Save service meta
        if (isset($_POST['aqualuxe_service_price'])) {
            update_post_meta($post_id, '_aqualuxe_service_price', sanitize_text_field($_POST['aqualuxe_service_price']));
        }
        if (isset($_POST['aqualuxe_service_duration'])) {
            update_post_meta($post_id, '_aqualuxe_service_duration', sanitize_text_field($_POST['aqualuxe_service_duration']));
        }
        if (isset($_POST['aqualuxe_service_icon'])) {
            update_post_meta($post_id, '_aqualuxe_service_icon', sanitize_text_field($_POST['aqualuxe_service_icon']));
        }
    }

    // Check if our nonce is set for testimonial
    if (isset($_POST['aqualuxe_testimonial_meta_nonce'])) {
        if (!wp_verify_nonce($_POST['aqualuxe_testimonial_meta_nonce'], 'aqualuxe_testimonial_meta')) {
            return;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        // Save testimonial meta
        if (isset($_POST['aqualuxe_testimonial_company'])) {
            update_post_meta($post_id, '_aqualuxe_testimonial_company', sanitize_text_field($_POST['aqualuxe_testimonial_company']));
        }
        if (isset($_POST['aqualuxe_testimonial_position'])) {
            update_post_meta($post_id, '_aqualuxe_testimonial_position', sanitize_text_field($_POST['aqualuxe_testimonial_position']));
        }
        if (isset($_POST['aqualuxe_testimonial_rating'])) {
            update_post_meta($post_id, '_aqualuxe_testimonial_rating', intval($_POST['aqualuxe_testimonial_rating']));
        }
    }
}
add_action('save_post', 'aqualuxe_save_meta_box_data');

/**
 * Register custom shortcodes
 */
function aqualuxe_register_shortcodes() {
    // Register shortcodes here
    add_shortcode('aqualuxe_services', 'aqualuxe_services_shortcode');
    add_shortcode('aqualuxe_testimonials', 'aqualuxe_testimonials_shortcode');
    add_shortcode('aqualuxe_team', 'aqualuxe_team_shortcode');
    add_shortcode('aqualuxe_faqs', 'aqualuxe_faqs_shortcode');
    add_shortcode('aqualuxe_contact_form', 'aqualuxe_contact_form_shortcode');
}
add_action('init', 'aqualuxe_register_shortcodes');

/**
 * Services shortcode callback
 */
function aqualuxe_services_shortcode($atts) {
    $atts = shortcode_atts(
        array(
            'count' => 3,
            'category' => '',
            'columns' => 3,
            'orderby' => 'date',
            'order' => 'DESC',
        ),
        $atts,
        'aqualuxe_services'
    );

    $args = array(
        'post_type' => 'service',
        'posts_per_page' => intval($atts['count']),
        'orderby' => $atts['orderby'],
        'order' => $atts['order'],
    );

    if (!empty($atts['category'])) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'service_category',
                'field' => 'slug',
                'terms' => explode(',', $atts['category']),
            ),
        );
    }

    $services = new WP_Query($args);

    ob_start();

    if ($services->have_posts()) :
        echo '<div class="aqualuxe-services grid grid-cols-1 md:grid-cols-' . esc_attr($atts['columns']) . ' gap-6">';
        
        while ($services->have_posts()) : $services->the_post();
            $icon = get_post_meta(get_the_ID(), '_aqualuxe_service_icon', true);
            $price = get_post_meta(get_the_ID(), '_aqualuxe_service_price', true);
            $duration = get_post_meta(get_the_ID(), '_aqualuxe_service_duration', true);
            ?>
            <div class="service-item bg-white rounded-lg shadow-md overflow-hidden transition-transform hover:shadow-lg hover:-translate-y-1">
                <?php if (has_post_thumbnail()) : ?>
                    <div class="service-image">
                        <a href="<?php the_permalink(); ?>">
                            <?php the_post_thumbnail('aqualuxe-thumbnail', array('class' => 'w-full h-auto')); ?>
                        </a>
                    </div>
                <?php endif; ?>
                
                <div class="service-content p-6">
                    <?php if (!empty($icon)) : ?>
                        <div class="service-icon text-primary text-3xl mb-4">
                            <i class="icon-<?php echo esc_attr($icon); ?>"></i>
                        </div>
                    <?php endif; ?>
                    
                    <h3 class="service-title text-xl font-bold mb-2">
                        <a href="<?php the_permalink(); ?>" class="hover:text-primary transition-colors">
                            <?php the_title(); ?>
                        </a>
                    </h3>
                    
                    <div class="service-excerpt mb-4">
                        <?php the_excerpt(); ?>
                    </div>
                    
                    <?php if (!empty($price) || !empty($duration)) : ?>
                        <div class="service-meta flex flex-wrap text-sm text-gray-600 mb-4">
                            <?php if (!empty($price)) : ?>
                                <div class="service-price mr-4">
                                    <span class="font-bold"><?php esc_html_e('Price:', 'aqualuxe'); ?></span> <?php echo esc_html($price); ?>
                                </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($duration)) : ?>
                                <div class="service-duration">
                                    <span class="font-bold"><?php esc_html_e('Duration:', 'aqualuxe'); ?></span> <?php echo esc_html($duration); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    
                    <a href="<?php the_permalink(); ?>" class="inline-block bg-primary hover:bg-primary-dark text-white font-bold py-2 px-4 rounded transition-colors">
                        <?php esc_html_e('Learn More', 'aqualuxe'); ?>
                    </a>
                </div>
            </div>
            <?php
        endwhile;
        
        echo '</div>';
    endif;

    wp_reset_postdata();

    return ob_get_clean();
}

/**
 * Testimonials shortcode callback
 */
function aqualuxe_testimonials_shortcode($atts) {
    $atts = shortcode_atts(
        array(
            'count' => 3,
            'orderby' => 'date',
            'order' => 'DESC',
            'style' => 'grid', // grid or slider
        ),
        $atts,
        'aqualuxe_testimonials'
    );

    $args = array(
        'post_type' => 'testimonial',
        'posts_per_page' => intval($atts['count']),
        'orderby' => $atts['orderby'],
        'order' => $atts['order'],
    );

    $testimonials = new WP_Query($args);

    ob_start();

    if ($testimonials->have_posts()) :
        $class = ($atts['style'] === 'slider') ? 'aqualuxe-testimonials-slider' : 'aqualuxe-testimonials grid grid-cols-1 md:grid-cols-3 gap-6';
        
        echo '<div class="' . esc_attr($class) . '">';
        
        while ($testimonials->have_posts()) : $testimonials->the_post();
            $company = get_post_meta(get_the_ID(), '_aqualuxe_testimonial_company', true);
            $position = get_post_meta(get_the_ID(), '_aqualuxe_testimonial_position', true);
            $rating = get_post_meta(get_the_ID(), '_aqualuxe_testimonial_rating', true);
            ?>
            <div class="testimonial-item bg-white rounded-lg shadow-md p-6">
                <div class="testimonial-content mb-4">
                    <div class="testimonial-quote text-gray-600 italic">
                        <?php the_content(); ?>
                    </div>
                </div>
                
                <?php if (!empty($rating)) : ?>
                    <div class="testimonial-rating flex text-yellow-400 mb-4">
                        <?php for ($i = 1; $i <= 5; $i++) : ?>
                            <?php if ($i <= $rating) : ?>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                            <?php else : ?>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-300" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                            <?php endif; ?>
                        <?php endfor; ?>
                    </div>
                <?php endif; ?>
                
                <div class="testimonial-author flex items-center">
                    <?php if (has_post_thumbnail()) : ?>
                        <div class="testimonial-avatar mr-4">
                            <?php the_post_thumbnail('thumbnail', array('class' => 'w-12 h-12 rounded-full')); ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="testimonial-info">
                        <h4 class="testimonial-name font-bold"><?php the_title(); ?></h4>
                        <?php if (!empty($position) || !empty($company)) : ?>
                            <div class="testimonial-meta text-sm text-gray-600">
                                <?php 
                                if (!empty($position)) {
                                    echo esc_html($position);
                                    if (!empty($company)) {
                                        echo ', ';
                                    }
                                }
                                if (!empty($company)) {
                                    echo esc_html($company);
                                }
                                ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php
        endwhile;
        
        echo '</div>';
    endif;

    wp_reset_postdata();

    return ob_get_clean();
}

/**
 * Team shortcode callback
 */
function aqualuxe_team_shortcode($atts) {
    $atts = shortcode_atts(
        array(
            'count' => -1,
            'department' => '',
            'columns' => 3,
            'orderby' => 'menu_order',
            'order' => 'ASC',
        ),
        $atts,
        'aqualuxe_team'
    );

    $args = array(
        'post_type' => 'team',
        'posts_per_page' => intval($atts['count']),
        'orderby' => $atts['orderby'],
        'order' => $atts['order'],
    );

    if (!empty($atts['department'])) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'department',
                'field' => 'slug',
                'terms' => explode(',', $atts['department']),
            ),
        );
    }

    $team = new WP_Query($args);

    ob_start();

    if ($team->have_posts()) :
        echo '<div class="aqualuxe-team grid grid-cols-1 md:grid-cols-' . esc_attr($atts['columns']) . ' gap-6">';
        
        while ($team->have_posts()) : $team->the_post();
            $position = get_post_meta(get_the_ID(), '_aqualuxe_team_position', true);
            $email = get_post_meta(get_the_ID(), '_aqualuxe_team_email', true);
            $phone = get_post_meta(get_the_ID(), '_aqualuxe_team_phone', true);
            $facebook = get_post_meta(get_the_ID(), '_aqualuxe_team_facebook', true);
            $twitter = get_post_meta(get_the_ID(), '_aqualuxe_team_twitter', true);
            $linkedin = get_post_meta(get_the_ID(), '_aqualuxe_team_linkedin', true);
            $instagram = get_post_meta(get_the_ID(), '_aqualuxe_team_instagram', true);
            ?>
            <div class="team-member bg-white rounded-lg shadow-md overflow-hidden transition-transform hover:shadow-lg hover:-translate-y-1">
                <?php if (has_post_thumbnail()) : ?>
                    <div class="team-image">
                        <a href="<?php the_permalink(); ?>">
                            <?php the_post_thumbnail('aqualuxe-thumbnail', array('class' => 'w-full h-auto')); ?>
                        </a>
                    </div>
                <?php endif; ?>
                
                <div class="team-content p-6">
                    <h3 class="team-name text-xl font-bold mb-1">
                        <a href="<?php the_permalink(); ?>" class="hover:text-primary transition-colors">
                            <?php the_title(); ?>
                        </a>
                    </h3>
                    
                    <?php if (!empty($position)) : ?>
                        <div class="team-position text-gray-600 mb-4">
                            <?php echo esc_html($position); ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="team-excerpt mb-4">
                        <?php the_excerpt(); ?>
                    </div>
                    
                    <div class="team-contact space-y-1 mb-4">
                        <?php if (!empty($email)) : ?>
                            <div class="team-email flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                <a href="mailto:<?php echo esc_attr($email); ?>" class="hover:text-primary transition-colors">
                                    <?php echo esc_html($email); ?>
                                </a>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($phone)) : ?>
                            <div class="team-phone flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                                <a href="tel:<?php echo esc_attr($phone); ?>" class="hover:text-primary transition-colors">
                                    <?php echo esc_html($phone); ?>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <?php if (!empty($facebook) || !empty($twitter) || !empty($linkedin) || !empty($instagram)) : ?>
                        <div class="team-social flex space-x-3">
                            <?php if (!empty($facebook)) : ?>
                                <a href="<?php echo esc_url($facebook); ?>" class="text-gray-600 hover:text-primary transition-colors" target="_blank" rel="noopener noreferrer">
                                    <span class="screen-reader-text"><?php esc_html_e('Facebook', 'aqualuxe'); ?></span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"/>
                                    </svg>
                                </a>
                            <?php endif; ?>
                            
                            <?php if (!empty($twitter)) : ?>
                                <a href="<?php echo esc_url($twitter); ?>" class="text-gray-600 hover:text-primary transition-colors" target="_blank" rel="noopener noreferrer">
                                    <span class="screen-reader-text"><?php esc_html_e('Twitter', 'aqualuxe'); ?></span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                                    </svg>
                                </a>
                            <?php endif; ?>
                            
                            <?php if (!empty($linkedin)) : ?>
                                <a href="<?php echo esc_url($linkedin); ?>" class="text-gray-600 hover:text-primary transition-colors" target="_blank" rel="noopener noreferrer">
                                    <span class="screen-reader-text"><?php esc_html_e('LinkedIn', 'aqualuxe'); ?></span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M4.98 3.5c0 1.381-1.11 2.5-2.48 2.5s-2.48-1.119-2.48-2.5c0-1.38 1.11-2.5 2.48-2.5s2.48 1.12 2.48 2.5zm.02 4.5h-5v16h5v-16zm7.982 0h-4.968v16h4.969v-8.399c0-4.67 6.029-5.052 6.029 0v8.399h4.988v-10.131c0-7.88-8.922-7.593-11.018-3.714v-2.155z"/>
                                    </svg>
                                </a>
                            <?php endif; ?>
                            
                            <?php if (!empty($instagram)) : ?>
                                <a href="<?php echo esc_url($instagram); ?>" class="text-gray-600 hover:text-primary transition-colors" target="_blank" rel="noopener noreferrer">
                                    <span class="screen-reader-text"><?php esc_html_e('Instagram', 'aqualuxe'); ?></span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                    </svg>
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php
        endwhile;
        
        echo '</div>';
    endif;

    wp_reset_postdata();

    return ob_get_clean();
}

/**
 * FAQs shortcode callback
 */
function aqualuxe_faqs_shortcode($atts) {
    $atts = shortcode_atts(
        array(
            'count' => -1,
            'category' => '',
            'orderby' => 'menu_order',
            'order' => 'ASC',
        ),
        $atts,
        'aqualuxe_faqs'
    );

    $args = array(
        'post_type' => 'faq',
        'posts_per_page' => intval($atts['count']),
        'orderby' => $atts['orderby'],
        'order' => $atts['order'],
    );

    if (!empty($atts['category'])) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'faq_category',
                'field' => 'slug',
                'terms' => explode(',', $atts['category']),
            ),
        );
    }

    $faqs = new WP_Query($args);

    ob_start();

    if ($faqs->have_posts()) :
        echo '<div class="aqualuxe-faqs space-y-4">';
        
        while ($faqs->have_posts()) : $faqs->the_post();
            $faq_id = 'faq-' . get_the_ID();
            ?>
            <div class="faq-item border border-gray-200 rounded-lg overflow-hidden">
                <button class="faq-question w-full flex justify-between items-center p-4 bg-gray-50 hover:bg-gray-100 transition-colors text-left font-bold" 
                        aria-expanded="false" 
                        aria-controls="<?php echo esc_attr($faq_id); ?>">
                    <?php the_title(); ?>
                    <svg xmlns="http://www.w3.org/2000/svg" class="faq-icon h-5 w-5 transform transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div id="<?php echo esc_attr($faq_id); ?>" class="faq-answer p-4 prose max-w-none hidden">
                    <?php the_content(); ?>
                </div>
            </div>
            <?php
        endwhile;
        
        echo '</div>';
    endif;

    wp_reset_postdata();

    return ob_get_clean();
}

/**
 * Contact form shortcode callback
 */
function aqualuxe_contact_form_shortcode($atts) {
    $atts = shortcode_atts(
        array(
            'title' => __('Contact Us', 'aqualuxe'),
            'submit_text' => __('Send Message', 'aqualuxe'),
            'success_message' => __('Thank you for your message. We will get back to you soon!', 'aqualuxe'),
            'error_message' => __('There was an error sending your message. Please try again.', 'aqualuxe'),
        ),
        $atts,
        'aqualuxe_contact_form'
    );

    ob_start();
    ?>
    <div class="aqualuxe-contact-form">
        <?php if (!empty($atts['title'])) : ?>
            <h3 class="contact-form-title text-2xl font-bold mb-6"><?php echo esc_html($atts['title']); ?></h3>
        <?php endif; ?>
        
        <div class="contact-form-messages"></div>
        
        <form id="aqualuxe-contact-form" class="contact-form space-y-4">
            <div class="form-group">
                <label for="contact-name" class="block mb-2"><?php esc_html_e('Name', 'aqualuxe'); ?> <span class="required">*</span></label>
                <input type="text" id="contact-name" name="contact-name" class="w-full p-2 border border-gray-300 rounded focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50" required>
            </div>
            
            <div class="form-group">
                <label for="contact-email" class="block mb-2"><?php esc_html_e('Email', 'aqualuxe'); ?> <span class="required">*</span></label>
                <input type="email" id="contact-email" name="contact-email" class="w-full p-2 border border-gray-300 rounded focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50" required>
            </div>
            
            <div class="form-group">
                <label for="contact-subject" class="block mb-2"><?php esc_html_e('Subject', 'aqualuxe'); ?></label>
                <input type="text" id="contact-subject" name="contact-subject" class="w-full p-2 border border-gray-300 rounded focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
            </div>
            
            <div class="form-group">
                <label for="contact-message" class="block mb-2"><?php esc_html_e('Message', 'aqualuxe'); ?> <span class="required">*</span></label>
                <textarea id="contact-message" name="contact-message" rows="5" class="w-full p-2 border border-gray-300 rounded focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50" required></textarea>
            </div>
            
            <div class="form-group">
                <button type="submit" class="bg-primary hover:bg-primary-dark text-white font-bold py-2 px-4 rounded transition-colors">
                    <?php echo esc_html($atts['submit_text']); ?>
                </button>
            </div>
            
            <?php wp_nonce_field('aqualuxe_contact_form_nonce', 'aqualuxe_contact_form_nonce'); ?>
            <input type="hidden" name="action" value="aqualuxe_contact_form_submit">
        </form>
    </div>
    <?php
    return ob_get_clean();
}

/**
 * Process contact form submission
 */
function aqualuxe_contact_form_submit() {
    // Check nonce
    if (!isset($_POST['aqualuxe_contact_form_nonce']) || !wp_verify_nonce($_POST['aqualuxe_contact_form_nonce'], 'aqualuxe_contact_form_nonce')) {
        wp_send_json_error(array('message' => __('Security check failed.', 'aqualuxe')));
    }

    // Get form data
    $name = isset($_POST['contact-name']) ? sanitize_text_field($_POST['contact-name']) : '';
    $email = isset($_POST['contact-email']) ? sanitize_email($_POST['contact-email']) : '';
    $subject = isset($_POST['contact-subject']) ? sanitize_text_field($_POST['contact-subject']) : __('New Contact Form Submission', 'aqualuxe');
    $message = isset($_POST['contact-message']) ? sanitize_textarea_field($_POST['contact-message']) : '';

    // Validate form data
    if (empty($name) || empty($email) || empty($message)) {
        wp_send_json_error(array('message' => __('Please fill in all required fields.', 'aqualuxe')));
    }

    if (!is_email($email)) {
        wp_send_json_error(array('message' => __('Please enter a valid email address.', 'aqualuxe')));
    }

    // Set up email
    $to = get_option('admin_email');
    $headers = array(
        'Content-Type: text/html; charset=UTF-8',
        'From: ' . $name . ' <' . $email . '>',
        'Reply-To: ' . $email,
    );

    $email_content = sprintf(
        '<p><strong>%1$s:</strong> %2$s</p>
        <p><strong>%3$s:</strong> %4$s</p>
        <p><strong>%5$s:</strong> %6$s</p>
        <p><strong>%7$s:</strong></p>
        <p>%8$s</p>',
        __('Name', 'aqualuxe'),
        $name,
        __('Email', 'aqualuxe'),
        $email,
        __('Subject', 'aqualuxe'),
        $subject,
        __('Message', 'aqualuxe'),
        nl2br($message)
    );

    // Send email
    $sent = wp_mail($to, $subject, $email_content, $headers);

    if ($sent) {
        wp_send_json_success(array('message' => __('Thank you for your message. We will get back to you soon!', 'aqualuxe')));
    } else {
        wp_send_json_error(array('message' => __('There was an error sending your message. Please try again.', 'aqualuxe')));
    }
}
add_action('wp_ajax_aqualuxe_contact_form_submit', 'aqualuxe_contact_form_submit');
add_action('wp_ajax_nopriv_aqualuxe_contact_form_submit', 'aqualuxe_contact_form_submit');