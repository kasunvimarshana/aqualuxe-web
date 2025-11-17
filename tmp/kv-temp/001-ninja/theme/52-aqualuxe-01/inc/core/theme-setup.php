<?php
/**
 * AquaLuxe Theme Setup
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register custom post types
 */
function aqualuxe_register_post_types() {
    // Services post type
    $services_labels = array(
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

    $services_args = array(
        'labels'             => $services_labels,
        'description'        => __('Aquarium services like installation, maintenance, etc.', 'aqualuxe'),
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'services'),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'menu_icon'          => 'dashicons-admin-tools',
        'supports'           => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
        'show_in_rest'       => true,
    );

    register_post_type('service', $services_args);

    // Events post type
    $events_labels = array(
        'name'               => _x('Events', 'post type general name', 'aqualuxe'),
        'singular_name'      => _x('Event', 'post type singular name', 'aqualuxe'),
        'menu_name'          => _x('Events', 'admin menu', 'aqualuxe'),
        'name_admin_bar'     => _x('Event', 'add new on admin bar', 'aqualuxe'),
        'add_new'            => _x('Add New', 'event', 'aqualuxe'),
        'add_new_item'       => __('Add New Event', 'aqualuxe'),
        'new_item'           => __('New Event', 'aqualuxe'),
        'edit_item'          => __('Edit Event', 'aqualuxe'),
        'view_item'          => __('View Event', 'aqualuxe'),
        'all_items'          => __('All Events', 'aqualuxe'),
        'search_items'       => __('Search Events', 'aqualuxe'),
        'parent_item_colon'  => __('Parent Events:', 'aqualuxe'),
        'not_found'          => __('No events found.', 'aqualuxe'),
        'not_found_in_trash' => __('No events found in Trash.', 'aqualuxe')
    );

    $events_args = array(
        'labels'             => $events_labels,
        'description'        => __('Events, exhibitions, and workshops', 'aqualuxe'),
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'events'),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'menu_icon'          => 'dashicons-calendar-alt',
        'supports'           => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
        'show_in_rest'       => true,
    );

    register_post_type('event', $events_args);

    // Testimonials post type
    $testimonials_labels = array(
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

    $testimonials_args = array(
        'labels'             => $testimonials_labels,
        'description'        => __('Customer testimonials and reviews', 'aqualuxe'),
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'testimonials'),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'menu_icon'          => 'dashicons-format-quote',
        'supports'           => array('title', 'editor', 'thumbnail', 'custom-fields'),
        'show_in_rest'       => true,
    );

    register_post_type('testimonial', $testimonials_args);
}
add_action('init', 'aqualuxe_register_post_types');

/**
 * Register custom taxonomies
 */
function aqualuxe_register_taxonomies() {
    // Service Categories
    $service_cat_labels = array(
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

    $service_cat_args = array(
        'hierarchical'      => true,
        'labels'            => $service_cat_labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'service-category'),
        'show_in_rest'      => true,
    );

    register_taxonomy('service_category', array('service'), $service_cat_args);

    // Event Categories
    $event_cat_labels = array(
        'name'              => _x('Event Categories', 'taxonomy general name', 'aqualuxe'),
        'singular_name'     => _x('Event Category', 'taxonomy singular name', 'aqualuxe'),
        'search_items'      => __('Search Event Categories', 'aqualuxe'),
        'all_items'         => __('All Event Categories', 'aqualuxe'),
        'parent_item'       => __('Parent Event Category', 'aqualuxe'),
        'parent_item_colon' => __('Parent Event Category:', 'aqualuxe'),
        'edit_item'         => __('Edit Event Category', 'aqualuxe'),
        'update_item'       => __('Update Event Category', 'aqualuxe'),
        'add_new_item'      => __('Add New Event Category', 'aqualuxe'),
        'new_item_name'     => __('New Event Category Name', 'aqualuxe'),
        'menu_name'         => __('Categories', 'aqualuxe'),
    );

    $event_cat_args = array(
        'hierarchical'      => true,
        'labels'            => $event_cat_labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'event-category'),
        'show_in_rest'      => true,
    );

    register_taxonomy('event_category', array('event'), $event_cat_args);

    // Testimonial Categories
    $testimonial_cat_labels = array(
        'name'              => _x('Testimonial Categories', 'taxonomy general name', 'aqualuxe'),
        'singular_name'     => _x('Testimonial Category', 'taxonomy singular name', 'aqualuxe'),
        'search_items'      => __('Search Testimonial Categories', 'aqualuxe'),
        'all_items'         => __('All Testimonial Categories', 'aqualuxe'),
        'parent_item'       => __('Parent Testimonial Category', 'aqualuxe'),
        'parent_item_colon' => __('Parent Testimonial Category:', 'aqualuxe'),
        'edit_item'         => __('Edit Testimonial Category', 'aqualuxe'),
        'update_item'       => __('Update Testimonial Category', 'aqualuxe'),
        'add_new_item'      => __('Add New Testimonial Category', 'aqualuxe'),
        'new_item_name'     => __('New Testimonial Category Name', 'aqualuxe'),
        'menu_name'         => __('Categories', 'aqualuxe'),
    );

    $testimonial_cat_args = array(
        'hierarchical'      => true,
        'labels'            => $testimonial_cat_labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'testimonial-category'),
        'show_in_rest'      => true,
    );

    register_taxonomy('testimonial_category', array('testimonial'), $testimonial_cat_args);
}
add_action('init', 'aqualuxe_register_taxonomies');

/**
 * Register custom image sizes
 */
function aqualuxe_register_image_sizes() {
    // Add custom image sizes
    add_image_size('aqualuxe-featured', 1200, 600, true);
    add_image_size('aqualuxe-card', 600, 400, true);
    add_image_size('aqualuxe-square', 600, 600, true);
    add_image_size('aqualuxe-portrait', 600, 800, true);
    add_image_size('aqualuxe-thumbnail', 300, 300, true);
}
add_action('after_setup_theme', 'aqualuxe_register_image_sizes');

/**
 * Add custom image sizes to media library dropdown
 */
function aqualuxe_custom_image_sizes($sizes) {
    return array_merge($sizes, array(
        'aqualuxe-featured' => __('Featured Image (1200x600)', 'aqualuxe'),
        'aqualuxe-card' => __('Card Image (600x400)', 'aqualuxe'),
        'aqualuxe-square' => __('Square Image (600x600)', 'aqualuxe'),
        'aqualuxe-portrait' => __('Portrait Image (600x800)', 'aqualuxe'),
        'aqualuxe-thumbnail' => __('Thumbnail Image (300x300)', 'aqualuxe'),
    ));
}
add_filter('image_size_names_choose', 'aqualuxe_custom_image_sizes');

/**
 * Primary menu fallback
 */
function aqualuxe_primary_menu_fallback() {
    echo '<ul class="main-navigation__menu">';
    echo '<li class="main-navigation__menu-item"><a href="' . esc_url(admin_url('nav-menus.php')) . '" class="main-navigation__menu-item-link">' . esc_html__('Create a Menu', 'aqualuxe') . '</a></li>';
    echo '</ul>';
}

/**
 * Register custom sidebars
 */
function aqualuxe_register_sidebars() {
    register_sidebar(
        array(
            'name'          => esc_html__('Blog Sidebar', 'aqualuxe'),
            'id'            => 'sidebar-1',
            'description'   => esc_html__('Add widgets here to appear in your blog sidebar.', 'aqualuxe'),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h2 class="widget-title">',
            'after_title'   => '</h2>',
        )
    );

    register_sidebar(
        array(
            'name'          => esc_html__('Shop Sidebar', 'aqualuxe'),
            'id'            => 'sidebar-shop',
            'description'   => esc_html__('Add widgets here to appear in your shop sidebar.', 'aqualuxe'),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h2 class="widget-title">',
            'after_title'   => '</h2>',
        )
    );

    register_sidebar(
        array(
            'name'          => esc_html__('Footer 1', 'aqualuxe'),
            'id'            => 'footer-1',
            'description'   => esc_html__('Add widgets here to appear in the first footer column.', 'aqualuxe'),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h2 class="widget-title">',
            'after_title'   => '</h2>',
        )
    );

    register_sidebar(
        array(
            'name'          => esc_html__('Footer 2', 'aqualuxe'),
            'id'            => 'footer-2',
            'description'   => esc_html__('Add widgets here to appear in the second footer column.', 'aqualuxe'),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h2 class="widget-title">',
            'after_title'   => '</h2>',
        )
    );

    register_sidebar(
        array(
            'name'          => esc_html__('Footer 3', 'aqualuxe'),
            'id'            => 'footer-3',
            'description'   => esc_html__('Add widgets here to appear in the third footer column.', 'aqualuxe'),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h2 class="widget-title">',
            'after_title'   => '</h2>',
        )
    );

    register_sidebar(
        array(
            'name'          => esc_html__('Footer 4', 'aqualuxe'),
            'id'            => 'footer-4',
            'description'   => esc_html__('Add widgets here to appear in the fourth footer column.', 'aqualuxe'),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h2 class="widget-title">',
            'after_title'   => '</h2>',
        )
    );
}
add_action('widgets_init', 'aqualuxe_register_sidebars');

/**
 * Register custom widgets
 */
function aqualuxe_register_widgets() {
    register_widget('AquaLuxe_About_Widget');
    register_widget('AquaLuxe_Contact_Widget');
    register_widget('AquaLuxe_Social_Widget');
    register_widget('AquaLuxe_Newsletter_Widget');
    register_widget('AquaLuxe_Featured_Posts_Widget');
}
add_action('widgets_init', 'aqualuxe_register_widgets');

/**
 * Add custom body classes
 */
function aqualuxe_body_classes($classes) {
    // Add a class if WooCommerce is active
    if (aqualuxe_is_woocommerce_active()) {
        $classes[] = 'woocommerce-active';
    }

    // Add a class for the dark mode
    $default_mode = get_theme_mod('aqualuxe_default_color_scheme', 'light');
    $classes[] = 'default-' . $default_mode . '-mode';

    // Add a class for the header layout
    $header_layout = get_theme_mod('aqualuxe_header_layout', 'default');
    $classes[] = 'header-layout-' . $header_layout;

    // Add a class for the footer layout
    $footer_layout = get_theme_mod('aqualuxe_footer_layout', 'default');
    $classes[] = 'footer-layout-' . $footer_layout;

    // Add a class for the sidebar layout
    if (is_active_sidebar('sidebar-1') && !is_page_template('templates/full-width.php') && !is_page_template('templates/homepage.php')) {
        $sidebar_layout = get_theme_mod('aqualuxe_sidebar_layout', 'right');
        $classes[] = 'has-sidebar';
        $classes[] = $sidebar_layout . '-sidebar';
    } else {
        $classes[] = 'no-sidebar';
    }

    // Add a class for the shop layout
    if (aqualuxe_is_woocommerce_active() && is_shop()) {
        $shop_layout = get_theme_mod('aqualuxe_shop_layout', 'grid');
        $classes[] = 'shop-layout-' . $shop_layout;
    }

    return $classes;
}
add_filter('body_class', 'aqualuxe_body_classes');

/**
 * Check if WooCommerce is active
 */
function aqualuxe_is_woocommerce_active() {
    return class_exists('WooCommerce');
}

/**
 * Add custom login page styles
 */
function aqualuxe_login_styles() {
    wp_enqueue_style('aqualuxe-login', AQUALUXE_ASSETS_URI . 'css/login.css', array(), AQUALUXE_VERSION);
    
    // Add custom logo to login page
    if (has_custom_logo()) {
        $custom_logo_id = get_theme_mod('custom_logo');
        $logo = wp_get_attachment_image_src($custom_logo_id, 'full');
        
        if ($logo) {
            ?>
            <style type="text/css">
                .login h1 a {
                    background-image: url(<?php echo esc_url($logo[0]); ?>);
                    background-size: contain;
                    width: 320px;
                    height: <?php echo esc_attr($logo[2] / ($logo[1] / 320)); ?>px;
                }
            </style>
            <?php
        }
    }
}
add_action('login_enqueue_scripts', 'aqualuxe_login_styles');

/**
 * Change login logo URL
 */
function aqualuxe_login_logo_url() {
    return home_url('/');
}
add_filter('login_headerurl', 'aqualuxe_login_logo_url');

/**
 * Change login logo title
 */
function aqualuxe_login_logo_title() {
    return get_bloginfo('name');
}
add_filter('login_headertext', 'aqualuxe_login_logo_title');

/**
 * Add custom admin footer text
 */
function aqualuxe_admin_footer_text($text) {
    $text = sprintf(
        /* translators: %s: Theme name */
        __('Thank you for creating with %s', 'aqualuxe'),
        '<a href="' . esc_url(home_url('/')) . '" target="_blank">' . esc_html(get_bloginfo('name')) . '</a>'
    );
    return $text;
}
add_filter('admin_footer_text', 'aqualuxe_admin_footer_text');

/**
 * Add custom admin bar menu
 */
function aqualuxe_admin_bar_menu($wp_admin_bar) {
    if (!is_admin_bar_showing() || !current_user_can('manage_options')) {
        return;
    }

    $wp_admin_bar->add_node(array(
        'id'    => 'aqualuxe',
        'title' => __('AquaLuxe', 'aqualuxe'),
        'href'  => admin_url('themes.php?page=aqualuxe-options'),
    ));

    $wp_admin_bar->add_node(array(
        'id'     => 'aqualuxe-theme-options',
        'parent' => 'aqualuxe',
        'title'  => __('Theme Options', 'aqualuxe'),
        'href'   => admin_url('themes.php?page=aqualuxe-options'),
    ));

    $wp_admin_bar->add_node(array(
        'id'     => 'aqualuxe-customize',
        'parent' => 'aqualuxe',
        'title'  => __('Customize', 'aqualuxe'),
        'href'   => admin_url('customize.php'),
    ));

    if (aqualuxe_is_woocommerce_active()) {
        $wp_admin_bar->add_node(array(
            'id'     => 'aqualuxe-woocommerce',
            'parent' => 'aqualuxe',
            'title'  => __('WooCommerce Settings', 'aqualuxe'),
            'href'   => admin_url('admin.php?page=wc-settings'),
        ));
    }
}
add_action('admin_bar_menu', 'aqualuxe_admin_bar_menu', 100);

/**
 * Add custom dashboard widget
 */
function aqualuxe_dashboard_widget() {
    wp_add_dashboard_widget(
        'aqualuxe_dashboard_widget',
        __('AquaLuxe Theme', 'aqualuxe'),
        'aqualuxe_dashboard_widget_content'
    );
}
add_action('wp_dashboard_setup', 'aqualuxe_dashboard_widget');

/**
 * Dashboard widget content
 */
function aqualuxe_dashboard_widget_content() {
    ?>
    <div class="aqualuxe-dashboard-widget">
        <div class="aqualuxe-dashboard-widget__header">
            <img src="<?php echo esc_url(AQUALUXE_ASSETS_URI . 'images/logo.png'); ?>" alt="<?php esc_attr_e('AquaLuxe', 'aqualuxe'); ?>">
            <h3><?php esc_html_e('Welcome to AquaLuxe Theme', 'aqualuxe'); ?></h3>
        </div>
        <div class="aqualuxe-dashboard-widget__content">
            <p><?php esc_html_e('Thank you for choosing AquaLuxe Theme. Here are some useful links to get you started:', 'aqualuxe'); ?></p>
            <ul>
                <li><a href="<?php echo esc_url(admin_url('themes.php?page=aqualuxe-options')); ?>"><?php esc_html_e('Theme Options', 'aqualuxe'); ?></a></li>
                <li><a href="<?php echo esc_url(admin_url('customize.php')); ?>"><?php esc_html_e('Customize Theme', 'aqualuxe'); ?></a></li>
                <?php if (aqualuxe_is_woocommerce_active()) : ?>
                    <li><a href="<?php echo esc_url(admin_url('admin.php?page=wc-settings')); ?>"><?php esc_html_e('WooCommerce Settings', 'aqualuxe'); ?></a></li>
                <?php endif; ?>
                <li><a href="<?php echo esc_url(admin_url('themes.php?page=aqualuxe-demo-importer')); ?>"><?php esc_html_e('Import Demo Content', 'aqualuxe'); ?></a></li>
            </ul>
        </div>
    </div>
    <?php
}

/**
 * Add custom meta tags to head
 */
function aqualuxe_meta_tags() {
    // Add viewport meta tag
    echo '<meta name="viewport" content="width=device-width, initial-scale=1">' . "\n";
    
    // Add theme color meta tag
    $primary_color = get_theme_mod('aqualuxe_primary_color', '#0B6E99');
    echo '<meta name="theme-color" content="' . esc_attr($primary_color) . '">' . "\n";
    
    // Add mobile app capable meta tag
    echo '<meta name="apple-mobile-web-app-capable" content="yes">' . "\n";
    echo '<meta name="mobile-web-app-capable" content="yes">' . "\n";
    
    // Add Open Graph meta tags
    if (is_single() || is_page()) {
        global $post;
        
        // Get the post title
        $og_title = get_the_title();
        
        // Get the post excerpt
        $og_description = has_excerpt() ? get_the_excerpt() : wp_trim_words(get_the_content(), 55, '...');
        
        // Get the post thumbnail
        $og_image = '';
        if (has_post_thumbnail()) {
            $og_image = get_the_post_thumbnail_url(null, 'large');
        }
        
        // Get the post URL
        $og_url = get_permalink();
        
        // Output the Open Graph meta tags
        echo '<meta property="og:title" content="' . esc_attr($og_title) . '">' . "\n";
        echo '<meta property="og:description" content="' . esc_attr($og_description) . '">' . "\n";
        if ($og_image) {
            echo '<meta property="og:image" content="' . esc_url($og_image) . '">' . "\n";
        }
        echo '<meta property="og:url" content="' . esc_url($og_url) . '">' . "\n";
        echo '<meta property="og:type" content="' . (is_single() ? 'article' : 'website') . '">' . "\n";
        echo '<meta property="og:site_name" content="' . esc_attr(get_bloginfo('name')) . '">' . "\n";
    }
}
add_action('wp_head', 'aqualuxe_meta_tags', 1);

/**
 * Add preconnect for Google Fonts
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
 * Add async/defer attributes to enqueued scripts
 */
function aqualuxe_script_loader_tag($tag, $handle, $src) {
    // Add async attribute to specific scripts
    $async_scripts = array('aqualuxe-app');
    if (in_array($handle, $async_scripts, true)) {
        return str_replace(' src', ' async src', $tag);
    }
    
    // Add defer attribute to specific scripts
    $defer_scripts = array('aqualuxe-customizer');
    if (in_array($handle, $defer_scripts, true)) {
        return str_replace(' src', ' defer src', $tag);
    }
    
    return $tag;
}
add_filter('script_loader_tag', 'aqualuxe_script_loader_tag', 10, 3);

/**
 * Add custom excerpt length
 */
function aqualuxe_excerpt_length($length) {
    return 30;
}
add_filter('excerpt_length', 'aqualuxe_excerpt_length');

/**
 * Add custom excerpt more
 */
function aqualuxe_excerpt_more($more) {
    return '&hellip;';
}
add_filter('excerpt_more', 'aqualuxe_excerpt_more');

/**
 * Add custom favicon
 */
function aqualuxe_favicon() {
    $favicon_id = get_theme_mod('aqualuxe_favicon');
    if ($favicon_id) {
        $favicon_url = wp_get_attachment_url($favicon_id);
        echo '<link rel="shortcut icon" href="' . esc_url($favicon_url) . '">' . "\n";
    }
}
add_action('wp_head', 'aqualuxe_favicon');

/**
 * Add custom logo support
 */
function aqualuxe_custom_logo_setup() {
    add_theme_support(
        'custom-logo',
        array(
            'height'      => 250,
            'width'       => 250,
            'flex-width'  => true,
            'flex-height' => true,
        )
    );
}
add_action('after_setup_theme', 'aqualuxe_custom_logo_setup');

/**
 * Add custom background support
 */
function aqualuxe_custom_background_setup() {
    add_theme_support(
        'custom-background',
        array(
            'default-color' => 'ffffff',
            'default-image' => '',
        )
    );
}
add_action('after_setup_theme', 'aqualuxe_custom_background_setup');

/**
 * Add custom header support
 */
function aqualuxe_custom_header_setup() {
    add_theme_support(
        'custom-header',
        array(
            'default-image'      => '',
            'default-text-color' => '000000',
            'width'              => 1200,
            'height'             => 400,
            'flex-width'         => true,
            'flex-height'        => true,
        )
    );
}
add_action('after_setup_theme', 'aqualuxe_custom_header_setup');

/**
 * Add editor styles
 */
function aqualuxe_add_editor_styles() {
    add_theme_support('editor-styles');
    add_editor_style('assets/dist/css/editor-style.css');
}
add_action('after_setup_theme', 'aqualuxe_add_editor_styles');

/**
 * Add responsive embeds support
 */
function aqualuxe_responsive_embeds() {
    add_theme_support('responsive-embeds');
}
add_action('after_setup_theme', 'aqualuxe_responsive_embeds');

/**
 * Add HTML5 support
 */
function aqualuxe_html5_support() {
    add_theme_support(
        'html5',
        array(
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
            'style',
            'script',
        )
    );
}
add_action('after_setup_theme', 'aqualuxe_html5_support');

/**
 * Add post formats support
 */
function aqualuxe_post_formats_support() {
    add_theme_support(
        'post-formats',
        array(
            'standard',
            'gallery',
            'video',
            'audio',
        )
    );
}
add_action('after_setup_theme', 'aqualuxe_post_formats_support');

/**
 * Add title tag support
 */
function aqualuxe_title_tag_support() {
    add_theme_support('title-tag');
}
add_action('after_setup_theme', 'aqualuxe_title_tag_support');

/**
 * Add automatic feed links support
 */
function aqualuxe_automatic_feed_links() {
    add_theme_support('automatic-feed-links');
}
add_action('after_setup_theme', 'aqualuxe_automatic_feed_links');

/**
 * Add content width
 */
function aqualuxe_content_width() {
    $GLOBALS['content_width'] = apply_filters('aqualuxe_content_width', 1200);
}
add_action('after_setup_theme', 'aqualuxe_content_width', 0);

/**
 * Add theme support for selective refresh for widgets
 */
function aqualuxe_customize_selective_refresh() {
    add_theme_support('customize-selective-refresh-widgets');
}
add_action('after_setup_theme', 'aqualuxe_customize_selective_refresh');

/**
 * Add theme support for align wide
 */
function aqualuxe_align_wide() {
    add_theme_support('align-wide');
}
add_action('after_setup_theme', 'aqualuxe_align_wide');

/**
 * Add theme support for custom spacing
 */
function aqualuxe_custom_spacing() {
    add_theme_support('custom-spacing');
}
add_action('after_setup_theme', 'aqualuxe_custom_spacing');

/**
 * Add theme support for custom units
 */
function aqualuxe_custom_units() {
    add_theme_support('custom-units');
}
add_action('after_setup_theme', 'aqualuxe_custom_units');

/**
 * Add theme support for custom line height
 */
function aqualuxe_custom_line_height() {
    add_theme_support('custom-line-height');
}
add_action('after_setup_theme', 'aqualuxe_custom_line_height');

/**
 * Add theme support for experimental link color
 */
function aqualuxe_experimental_link_color() {
    add_theme_support('experimental-link-color');
}
add_action('after_setup_theme', 'aqualuxe_experimental_link_color');

/**
 * Add theme support for block templates
 */
function aqualuxe_block_templates() {
    add_theme_support('block-templates');
}
add_action('after_setup_theme', 'aqualuxe_block_templates');

/**
 * Add theme support for block template parts
 */
function aqualuxe_block_template_parts() {
    add_theme_support('block-template-parts');
}
add_action('after_setup_theme', 'aqualuxe_block_template_parts');