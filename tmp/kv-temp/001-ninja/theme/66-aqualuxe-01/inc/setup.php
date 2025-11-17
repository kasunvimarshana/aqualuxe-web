<?php
/**
 * Theme setup functions
 *
 * @package AquaLuxe
 */

/**
 * Register custom post types
 */
function aqualuxe_register_post_types() {
    // Register Services post type
    register_post_type('aqualuxe_service', [
        'labels' => [
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
        ],
        'public' => true,
        'hierarchical' => false,
        'show_ui' => true,
        'show_in_nav_menus' => true,
        'supports' => ['title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'],
        'has_archive' => true,
        'rewrite' => ['slug' => 'services'],
        'menu_icon' => 'dashicons-admin-tools',
        'show_in_rest' => true,
    ]);

    // Register Testimonials post type
    register_post_type('aqualuxe_testimonial', [
        'labels' => [
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
        ],
        'public' => true,
        'hierarchical' => false,
        'show_ui' => true,
        'show_in_nav_menus' => true,
        'supports' => ['title', 'editor', 'thumbnail', 'custom-fields'],
        'has_archive' => true,
        'rewrite' => ['slug' => 'testimonials'],
        'menu_icon' => 'dashicons-format-quote',
        'show_in_rest' => true,
    ]);

    // Register Team Members post type
    register_post_type('aqualuxe_team', [
        'labels' => [
            'name' => __('Team Members', 'aqualuxe'),
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
        ],
        'public' => true,
        'hierarchical' => false,
        'show_ui' => true,
        'show_in_nav_menus' => true,
        'supports' => ['title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'],
        'has_archive' => true,
        'rewrite' => ['slug' => 'team'],
        'menu_icon' => 'dashicons-groups',
        'show_in_rest' => true,
    ]);

    // Register FAQ post type
    register_post_type('aqualuxe_faq', [
        'labels' => [
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
        ],
        'public' => true,
        'hierarchical' => false,
        'show_ui' => true,
        'show_in_nav_menus' => true,
        'supports' => ['title', 'editor', 'custom-fields'],
        'has_archive' => true,
        'rewrite' => ['slug' => 'faqs'],
        'menu_icon' => 'dashicons-editor-help',
        'show_in_rest' => true,
    ]);
}
add_action('init', 'aqualuxe_register_post_types');

/**
 * Register custom taxonomies
 */
function aqualuxe_register_taxonomies() {
    // Register Service Categories taxonomy
    register_taxonomy('aqualuxe_service_cat', ['aqualuxe_service'], [
        'labels' => [
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
        ],
        'hierarchical' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => ['slug' => 'service-category'],
        'show_in_rest' => true,
    ]);

    // Register FAQ Categories taxonomy
    register_taxonomy('aqualuxe_faq_cat', ['aqualuxe_faq'], [
        'labels' => [
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
        ],
        'hierarchical' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => ['slug' => 'faq-category'],
        'show_in_rest' => true,
    ]);

    // Register Team Member Roles taxonomy
    register_taxonomy('aqualuxe_team_role', ['aqualuxe_team'], [
        'labels' => [
            'name' => __('Team Roles', 'aqualuxe'),
            'singular_name' => __('Team Role', 'aqualuxe'),
            'search_items' => __('Search Team Roles', 'aqualuxe'),
            'all_items' => __('All Team Roles', 'aqualuxe'),
            'parent_item' => __('Parent Team Role', 'aqualuxe'),
            'parent_item_colon' => __('Parent Team Role:', 'aqualuxe'),
            'edit_item' => __('Edit Team Role', 'aqualuxe'),
            'update_item' => __('Update Team Role', 'aqualuxe'),
            'add_new_item' => __('Add New Team Role', 'aqualuxe'),
            'new_item_name' => __('New Team Role Name', 'aqualuxe'),
            'menu_name' => __('Roles', 'aqualuxe'),
        ],
        'hierarchical' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => ['slug' => 'team-role'],
        'show_in_rest' => true,
    ]);
}
add_action('init', 'aqualuxe_register_taxonomies');

/**
 * Register custom meta boxes
 */
function aqualuxe_register_meta_boxes() {
    // Service Details meta box
    add_meta_box(
        'aqualuxe_service_details',
        __('Service Details', 'aqualuxe'),
        'aqualuxe_service_details_callback',
        'aqualuxe_service',
        'normal',
        'high'
    );

    // Team Member Details meta box
    add_meta_box(
        'aqualuxe_team_details',
        __('Team Member Details', 'aqualuxe'),
        'aqualuxe_team_details_callback',
        'aqualuxe_team',
        'normal',
        'high'
    );

    // Testimonial Details meta box
    add_meta_box(
        'aqualuxe_testimonial_details',
        __('Testimonial Details', 'aqualuxe'),
        'aqualuxe_testimonial_details_callback',
        'aqualuxe_testimonial',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'aqualuxe_register_meta_boxes');

/**
 * Service Details meta box callback
 *
 * @param WP_Post $post
 */
function aqualuxe_service_details_callback($post) {
    // Add nonce for security
    wp_nonce_field('aqualuxe_service_details', 'aqualuxe_service_details_nonce');

    // Get current values
    $price = get_post_meta($post->ID, '_aqualuxe_service_price', true);
    $duration = get_post_meta($post->ID, '_aqualuxe_service_duration', true);
    $icon = get_post_meta($post->ID, '_aqualuxe_service_icon', true);

    // Output fields
    ?>
    <div class="aqualuxe-meta-box">
        <div class="aqualuxe-meta-field">
            <label for="aqualuxe_service_price"><?php _e('Price', 'aqualuxe'); ?></label>
            <input type="text" id="aqualuxe_service_price" name="aqualuxe_service_price" value="<?php echo esc_attr($price); ?>" />
            <p class="description"><?php _e('Enter the service price (e.g. $99 or $99-$199)', 'aqualuxe'); ?></p>
        </div>

        <div class="aqualuxe-meta-field">
            <label for="aqualuxe_service_duration"><?php _e('Duration', 'aqualuxe'); ?></label>
            <input type="text" id="aqualuxe_service_duration" name="aqualuxe_service_duration" value="<?php echo esc_attr($duration); ?>" />
            <p class="description"><?php _e('Enter the service duration (e.g. 1 hour or 30-60 minutes)', 'aqualuxe'); ?></p>
        </div>

        <div class="aqualuxe-meta-field">
            <label for="aqualuxe_service_icon"><?php _e('Icon', 'aqualuxe'); ?></label>
            <input type="text" id="aqualuxe_service_icon" name="aqualuxe_service_icon" value="<?php echo esc_attr($icon); ?>" />
            <p class="description"><?php _e('Enter the service icon name (e.g. fish, water, tank)', 'aqualuxe'); ?></p>
        </div>
    </div>
    <?php
}

/**
 * Team Member Details meta box callback
 *
 * @param WP_Post $post
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
    <div class="aqualuxe-meta-box">
        <div class="aqualuxe-meta-field">
            <label for="aqualuxe_team_position"><?php _e('Position', 'aqualuxe'); ?></label>
            <input type="text" id="aqualuxe_team_position" name="aqualuxe_team_position" value="<?php echo esc_attr($position); ?>" />
            <p class="description"><?php _e('Enter the team member\'s position', 'aqualuxe'); ?></p>
        </div>

        <div class="aqualuxe-meta-field">
            <label for="aqualuxe_team_email"><?php _e('Email', 'aqualuxe'); ?></label>
            <input type="email" id="aqualuxe_team_email" name="aqualuxe_team_email" value="<?php echo esc_attr($email); ?>" />
            <p class="description"><?php _e('Enter the team member\'s email address', 'aqualuxe'); ?></p>
        </div>

        <div class="aqualuxe-meta-field">
            <label for="aqualuxe_team_phone"><?php _e('Phone', 'aqualuxe'); ?></label>
            <input type="text" id="aqualuxe_team_phone" name="aqualuxe_team_phone" value="<?php echo esc_attr($phone); ?>" />
            <p class="description"><?php _e('Enter the team member\'s phone number', 'aqualuxe'); ?></p>
        </div>

        <div class="aqualuxe-meta-field">
            <label for="aqualuxe_team_social"><?php _e('Social Media', 'aqualuxe'); ?></label>
            <textarea id="aqualuxe_team_social" name="aqualuxe_team_social" rows="4"><?php echo esc_textarea($social); ?></textarea>
            <p class="description"><?php _e('Enter the team member\'s social media links (one per line, format: platform|url)', 'aqualuxe'); ?></p>
        </div>
    </div>
    <?php
}

/**
 * Testimonial Details meta box callback
 *
 * @param WP_Post $post
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
    <div class="aqualuxe-meta-box">
        <div class="aqualuxe-meta-field">
            <label for="aqualuxe_testimonial_author"><?php _e('Author', 'aqualuxe'); ?></label>
            <input type="text" id="aqualuxe_testimonial_author" name="aqualuxe_testimonial_author" value="<?php echo esc_attr($author); ?>" />
            <p class="description"><?php _e('Enter the testimonial author\'s name', 'aqualuxe'); ?></p>
        </div>

        <div class="aqualuxe-meta-field">
            <label for="aqualuxe_testimonial_position"><?php _e('Position', 'aqualuxe'); ?></label>
            <input type="text" id="aqualuxe_testimonial_position" name="aqualuxe_testimonial_position" value="<?php echo esc_attr($position); ?>" />
            <p class="description"><?php _e('Enter the author\'s position', 'aqualuxe'); ?></p>
        </div>

        <div class="aqualuxe-meta-field">
            <label for="aqualuxe_testimonial_company"><?php _e('Company', 'aqualuxe'); ?></label>
            <input type="text" id="aqualuxe_testimonial_company" name="aqualuxe_testimonial_company" value="<?php echo esc_attr($company); ?>" />
            <p class="description"><?php _e('Enter the author\'s company', 'aqualuxe'); ?></p>
        </div>

        <div class="aqualuxe-meta-field">
            <label for="aqualuxe_testimonial_rating"><?php _e('Rating', 'aqualuxe'); ?></label>
            <select id="aqualuxe_testimonial_rating" name="aqualuxe_testimonial_rating">
                <option value=""><?php _e('Select Rating', 'aqualuxe'); ?></option>
                <option value="5" <?php selected($rating, '5'); ?>><?php _e('5 Stars', 'aqualuxe'); ?></option>
                <option value="4.5" <?php selected($rating, '4.5'); ?>><?php _e('4.5 Stars', 'aqualuxe'); ?></option>
                <option value="4" <?php selected($rating, '4'); ?>><?php _e('4 Stars', 'aqualuxe'); ?></option>
                <option value="3.5" <?php selected($rating, '3.5'); ?>><?php _e('3.5 Stars', 'aqualuxe'); ?></option>
                <option value="3" <?php selected($rating, '3'); ?>><?php _e('3 Stars', 'aqualuxe'); ?></option>
                <option value="2.5" <?php selected($rating, '2.5'); ?>><?php _e('2.5 Stars', 'aqualuxe'); ?></option>
                <option value="2" <?php selected($rating, '2'); ?>><?php _e('2 Stars', 'aqualuxe'); ?></option>
                <option value="1.5" <?php selected($rating, '1.5'); ?>><?php _e('1.5 Stars', 'aqualuxe'); ?></option>
                <option value="1" <?php selected($rating, '1'); ?>><?php _e('1 Star', 'aqualuxe'); ?></option>
            </select>
            <p class="description"><?php _e('Select the testimonial rating', 'aqualuxe'); ?></p>
        </div>
    </div>
    <?php
}

/**
 * Save meta box data
 *
 * @param int $post_id
 */
function aqualuxe_save_meta_box_data($post_id) {
    // Check if we're doing an autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Check user permissions
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Service Details
    if (isset($_POST['aqualuxe_service_details_nonce']) && wp_verify_nonce($_POST['aqualuxe_service_details_nonce'], 'aqualuxe_service_details')) {
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

    // Team Member Details
    if (isset($_POST['aqualuxe_team_details_nonce']) && wp_verify_nonce($_POST['aqualuxe_team_details_nonce'], 'aqualuxe_team_details')) {
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
            update_post_meta($post_id, '_aqualuxe_team_social', sanitize_textarea_field($_POST['aqualuxe_team_social']));
        }
    }

    // Testimonial Details
    if (isset($_POST['aqualuxe_testimonial_details_nonce']) && wp_verify_nonce($_POST['aqualuxe_testimonial_details_nonce'], 'aqualuxe_testimonial_details')) {
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
            update_post_meta($post_id, '_aqualuxe_testimonial_rating', sanitize_text_field($_POST['aqualuxe_testimonial_rating']));
        }
    }
}
add_action('save_post', 'aqualuxe_save_meta_box_data');

/**
 * Register theme sidebars
 */
function aqualuxe_register_sidebars() {
    // Main sidebar
    register_sidebar([
        'name'          => __('Main Sidebar', 'aqualuxe'),
        'id'            => 'sidebar-main',
        'description'   => __('Main sidebar that appears on blog and pages.', 'aqualuxe'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ]);

    // Shop sidebar
    register_sidebar([
        'name'          => __('Shop Sidebar', 'aqualuxe'),
        'id'            => 'sidebar-shop',
        'description'   => __('Sidebar that appears on shop pages.', 'aqualuxe'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ]);

    // Footer widgets
    for ($i = 1; $i <= 4; $i++) {
        register_sidebar([
            'name'          => sprintf(__('Footer %d', 'aqualuxe'), $i),
            'id'            => 'footer-' . $i,
            'description'   => sprintf(__('Footer widget area %d.', 'aqualuxe'), $i),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3 class="widget-title">',
            'after_title'   => '</h3>',
        ]);
    }
}
add_action('widgets_init', 'aqualuxe_register_sidebars');

/**
 * Register theme menus
 */
function aqualuxe_register_menus() {
    register_nav_menus([
        'primary'   => __('Primary Menu', 'aqualuxe'),
        'secondary' => __('Secondary Menu', 'aqualuxe'),
        'footer'    => __('Footer Menu', 'aqualuxe'),
        'social'    => __('Social Links Menu', 'aqualuxe'),
        'mobile'    => __('Mobile Menu', 'aqualuxe'),
    ]);
}
add_action('init', 'aqualuxe_register_menus');

/**
 * Add theme support
 */
function aqualuxe_theme_support() {
    // Add default posts and comments RSS feed links to head
    add_theme_support('automatic-feed-links');

    // Let WordPress manage the document title
    add_theme_support('title-tag');

    // Enable support for Post Thumbnails on posts and pages
    add_theme_support('post-thumbnails');

    // Switch default core markup for search form, comment form, and comments to output valid HTML5
    add_theme_support('html5', [
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ]);

    // Add theme support for selective refresh for widgets
    add_theme_support('customize-selective-refresh-widgets');

    // Add support for core custom logo
    add_theme_support('custom-logo', [
        'height'      => 250,
        'width'       => 250,
        'flex-width'  => true,
        'flex-height' => true,
    ]);

    // Add support for custom backgrounds
    add_theme_support('custom-background', [
        'default-color' => 'ffffff',
        'default-image' => '',
    ]);

    // Add support for post formats
    add_theme_support('post-formats', [
        'aside',
        'image',
        'video',
        'quote',
        'link',
        'gallery',
        'audio',
    ]);

    // Add support for full and wide align images
    add_theme_support('align-wide');

    // Add support for responsive embeds
    add_theme_support('responsive-embeds');

    // Add support for custom line height controls
    add_theme_support('custom-line-height');

    // Add support for experimental link color control
    add_theme_support('experimental-link-color');

    // Add support for custom units
    add_theme_support('custom-units');

    // Add support for editor styles
    add_theme_support('editor-styles');

    // Add support for block styles
    add_theme_support('wp-block-styles');
}
add_action('after_setup_theme', 'aqualuxe_theme_support');