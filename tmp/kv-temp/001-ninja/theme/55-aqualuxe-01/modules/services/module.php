<?php
/**
 * Services Module
 *
 * @package AquaLuxe
 * @subpackage Modules
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Services Module Class
 */
class AquaLuxe_Module_Services extends AquaLuxe_Module {
    /**
     * Module ID
     *
     * @var string
     */
    protected $id = 'services';

    /**
     * Module name
     *
     * @var string
     */
    protected $name = 'Services';

    /**
     * Module description
     *
     * @var string
     */
    protected $description = 'Adds services post type and functionality to the theme.';

    /**
     * Module version
     *
     * @var string
     */
    protected $version = '1.0.0';

    /**
     * Module dependencies
     *
     * @var array
     */
    protected $dependencies = [];

    /**
     * Initialize module
     */
    protected function init() {
        // Include module files
        $this->include_files();

        // Register post type and taxonomies
        add_action('init', [$this, 'register_post_type']);
        add_action('init', [$this, 'register_taxonomies']);

        // Register shortcodes
        add_action('init', [$this, 'register_shortcodes']);

        // Register Gutenberg blocks
        add_action('init', [$this, 'register_blocks']);

        // Add services to menu
        add_filter('aqualuxe_nav_menu_items', [$this, 'add_services_menu_item']);

        // Add services widget areas
        add_action('widgets_init', [$this, 'register_widget_areas']);

        // Add services dashboard widget
        add_action('wp_dashboard_setup', [$this, 'add_dashboard_widget']);

        // Add services to REST API
        add_action('rest_api_init', [$this, 'register_rest_fields']);

        // Add services metaboxes
        add_action('add_meta_boxes', [$this, 'add_meta_boxes']);
        add_action('save_post_service', [$this, 'save_meta_boxes']);

        // Add services columns
        add_filter('manage_service_posts_columns', [$this, 'add_columns']);
        add_action('manage_service_posts_custom_column', [$this, 'render_columns'], 10, 2);
        add_filter('manage_edit-service_sortable_columns', [$this, 'sortable_columns']);

        // Add services filters
        add_action('restrict_manage_posts', [$this, 'add_filters']);
        add_filter('parse_query', [$this, 'filter_query']);

        // Add services template redirects
        add_filter('template_include', [$this, 'template_include']);

        // Add services body classes
        add_filter('body_class', [$this, 'body_class']);

        // Add services to sitemap
        add_filter('wp_sitemaps_post_types', [$this, 'add_to_sitemap']);
    }

    /**
     * Include module files
     */
    private function include_files() {
        // Include template functions
        require_once $this->dir . '/includes/template-functions.php';

        // Include template hooks
        require_once $this->dir . '/includes/template-hooks.php';

        // Include shortcodes
        require_once $this->dir . '/includes/shortcodes.php';

        // Include blocks
        require_once $this->dir . '/includes/blocks.php';

        // Include widgets
        require_once $this->dir . '/includes/widgets.php';
    }

    /**
     * Register module settings in customizer
     *
     * @param WP_Customize_Manager $wp_customize
     */
    public function customize_register($wp_customize) {
        // Call parent method
        parent::customize_register($wp_customize);

        // Add services slug setting
        $wp_customize->add_setting('aqualuxe_module_' . $this->id . '[slug]', [
            'default' => 'services',
            'type' => 'option',
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'sanitize_title',
        ]);

        // Add services slug control
        $wp_customize->add_control('aqualuxe_module_' . $this->id . '_slug', [
            'label' => __('Services Slug', 'aqualuxe'),
            'section' => 'aqualuxe_module_' . $this->id,
            'settings' => 'aqualuxe_module_' . $this->id . '[slug]',
            'type' => 'text',
            'priority' => 20,
        ]);

        // Add services layout setting
        $wp_customize->add_setting('aqualuxe_module_' . $this->id . '[layout]', [
            'default' => 'grid',
            'type' => 'option',
            'capability' => 'edit_theme_options',
            'sanitize_callback' => [$this, 'sanitize_layout'],
        ]);

        // Add services layout control
        $wp_customize->add_control('aqualuxe_module_' . $this->id . '_layout', [
            'label' => __('Services Layout', 'aqualuxe'),
            'section' => 'aqualuxe_module_' . $this->id,
            'settings' => 'aqualuxe_module_' . $this->id . '[layout]',
            'type' => 'select',
            'choices' => [
                'grid' => __('Grid', 'aqualuxe'),
                'list' => __('List', 'aqualuxe'),
                'masonry' => __('Masonry', 'aqualuxe'),
            ],
            'priority' => 30,
        ]);

        // Add services per page setting
        $wp_customize->add_setting('aqualuxe_module_' . $this->id . '[per_page]', [
            'default' => 9,
            'type' => 'option',
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'absint',
        ]);

        // Add services per page control
        $wp_customize->add_control('aqualuxe_module_' . $this->id . '_per_page', [
            'label' => __('Services Per Page', 'aqualuxe'),
            'section' => 'aqualuxe_module_' . $this->id,
            'settings' => 'aqualuxe_module_' . $this->id . '[per_page]',
            'type' => 'number',
            'input_attrs' => [
                'min' => 1,
                'max' => 100,
                'step' => 1,
            ],
            'priority' => 40,
        ]);

        // Add services sidebar setting
        $wp_customize->add_setting('aqualuxe_module_' . $this->id . '[sidebar]', [
            'default' => true,
            'type' => 'option',
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        ]);

        // Add services sidebar control
        $wp_customize->add_control('aqualuxe_module_' . $this->id . '_sidebar', [
            'label' => __('Enable Services Sidebar', 'aqualuxe'),
            'section' => 'aqualuxe_module_' . $this->id,
            'settings' => 'aqualuxe_module_' . $this->id . '[sidebar]',
            'type' => 'checkbox',
            'priority' => 50,
        ]);

        // Add services featured image setting
        $wp_customize->add_setting('aqualuxe_module_' . $this->id . '[featured_image]', [
            'default' => true,
            'type' => 'option',
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        ]);

        // Add services featured image control
        $wp_customize->add_control('aqualuxe_module_' . $this->id . '_featured_image', [
            'label' => __('Show Featured Image', 'aqualuxe'),
            'section' => 'aqualuxe_module_' . $this->id,
            'settings' => 'aqualuxe_module_' . $this->id . '[featured_image]',
            'type' => 'checkbox',
            'priority' => 60,
        ]);

        // Add services related services setting
        $wp_customize->add_setting('aqualuxe_module_' . $this->id . '[related_services]', [
            'default' => true,
            'type' => 'option',
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        ]);

        // Add services related services control
        $wp_customize->add_control('aqualuxe_module_' . $this->id . '_related_services', [
            'label' => __('Show Related Services', 'aqualuxe'),
            'section' => 'aqualuxe_module_' . $this->id,
            'settings' => 'aqualuxe_module_' . $this->id . '[related_services]',
            'type' => 'checkbox',
            'priority' => 70,
        ]);

        // Add services booking form setting
        $wp_customize->add_setting('aqualuxe_module_' . $this->id . '[booking_form]', [
            'default' => true,
            'type' => 'option',
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        ]);

        // Add services booking form control
        $wp_customize->add_control('aqualuxe_module_' . $this->id . '_booking_form', [
            'label' => __('Show Booking Form', 'aqualuxe'),
            'section' => 'aqualuxe_module_' . $this->id,
            'settings' => 'aqualuxe_module_' . $this->id . '[booking_form]',
            'type' => 'checkbox',
            'priority' => 80,
        ]);
    }

    /**
     * Register module assets
     */
    public function register_assets() {
        // Check if module is enabled
        if (!$this->is_enabled()) {
            return;
        }

        // Register services styles
        wp_enqueue_style(
            'aqualuxe-services',
            $this->url . '/assets/css/services.css',
            [],
            $this->version
        );

        // Register services script
        wp_enqueue_script(
            'aqualuxe-services',
            $this->url . '/assets/js/services.js',
            ['jquery', 'alpine'],
            $this->version,
            true
        );

        // Add script data
        wp_localize_script('aqualuxe-services', 'aqualuxeServices', [
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('aqualuxe-services-nonce'),
            'layout' => $this->get_option('layout', 'grid'),
            'i18n' => [
                'bookNow' => esc_html__('Book Now', 'aqualuxe'),
                'viewDetails' => esc_html__('View Details', 'aqualuxe'),
                'loadMore' => esc_html__('Load More', 'aqualuxe'),
                'loading' => esc_html__('Loading...', 'aqualuxe'),
                'noServices' => esc_html__('No services found', 'aqualuxe'),
            ],
        ]);
    }

    /**
     * Register services post type
     */
    public function register_post_type() {
        // Get slug
        $slug = $this->get_option('slug', 'services');

        // Set labels
        $labels = [
            'name' => _x('Services', 'Post Type General Name', 'aqualuxe'),
            'singular_name' => _x('Service', 'Post Type Singular Name', 'aqualuxe'),
            'menu_name' => __('Services', 'aqualuxe'),
            'name_admin_bar' => __('Service', 'aqualuxe'),
            'archives' => __('Service Archives', 'aqualuxe'),
            'attributes' => __('Service Attributes', 'aqualuxe'),
            'parent_item_colon' => __('Parent Service:', 'aqualuxe'),
            'all_items' => __('All Services', 'aqualuxe'),
            'add_new_item' => __('Add New Service', 'aqualuxe'),
            'add_new' => __('Add New', 'aqualuxe'),
            'new_item' => __('New Service', 'aqualuxe'),
            'edit_item' => __('Edit Service', 'aqualuxe'),
            'update_item' => __('Update Service', 'aqualuxe'),
            'view_item' => __('View Service', 'aqualuxe'),
            'view_items' => __('View Services', 'aqualuxe'),
            'search_items' => __('Search Service', 'aqualuxe'),
            'not_found' => __('Not found', 'aqualuxe'),
            'not_found_in_trash' => __('Not found in Trash', 'aqualuxe'),
            'featured_image' => __('Featured Image', 'aqualuxe'),
            'set_featured_image' => __('Set featured image', 'aqualuxe'),
            'remove_featured_image' => __('Remove featured image', 'aqualuxe'),
            'use_featured_image' => __('Use as featured image', 'aqualuxe'),
            'insert_into_item' => __('Insert into service', 'aqualuxe'),
            'uploaded_to_this_item' => __('Uploaded to this service', 'aqualuxe'),
            'items_list' => __('Services list', 'aqualuxe'),
            'items_list_navigation' => __('Services list navigation', 'aqualuxe'),
            'filter_items_list' => __('Filter services list', 'aqualuxe'),
        ];

        // Set arguments
        $args = [
            'label' => __('Service', 'aqualuxe'),
            'description' => __('Service Description', 'aqualuxe'),
            'labels' => $labels,
            'supports' => ['title', 'editor', 'thumbnail', 'excerpt', 'comments', 'custom-fields', 'revisions'],
            'taxonomies' => ['service_category', 'service_tag'],
            'hierarchical' => false,
            'public' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'menu_position' => 5,
            'menu_icon' => 'dashicons-admin-generic',
            'show_in_admin_bar' => true,
            'show_in_nav_menus' => true,
            'can_export' => true,
            'has_archive' => true,
            'exclude_from_search' => false,
            'publicly_queryable' => true,
            'capability_type' => 'page',
            'show_in_rest' => true,
            'rest_base' => 'services',
            'rewrite' => ['slug' => $slug],
        ];

        // Register post type
        register_post_type('service', $args);
    }

    /**
     * Register services taxonomies
     */
    public function register_taxonomies() {
        // Register service category taxonomy
        $category_labels = [
            'name' => _x('Service Categories', 'Taxonomy General Name', 'aqualuxe'),
            'singular_name' => _x('Service Category', 'Taxonomy Singular Name', 'aqualuxe'),
            'menu_name' => __('Categories', 'aqualuxe'),
            'all_items' => __('All Categories', 'aqualuxe'),
            'parent_item' => __('Parent Category', 'aqualuxe'),
            'parent_item_colon' => __('Parent Category:', 'aqualuxe'),
            'new_item_name' => __('New Category Name', 'aqualuxe'),
            'add_new_item' => __('Add New Category', 'aqualuxe'),
            'edit_item' => __('Edit Category', 'aqualuxe'),
            'update_item' => __('Update Category', 'aqualuxe'),
            'view_item' => __('View Category', 'aqualuxe'),
            'separate_items_with_commas' => __('Separate categories with commas', 'aqualuxe'),
            'add_or_remove_items' => __('Add or remove categories', 'aqualuxe'),
            'choose_from_most_used' => __('Choose from the most used', 'aqualuxe'),
            'popular_items' => __('Popular Categories', 'aqualuxe'),
            'search_items' => __('Search Categories', 'aqualuxe'),
            'not_found' => __('Not Found', 'aqualuxe'),
            'no_terms' => __('No categories', 'aqualuxe'),
            'items_list' => __('Categories list', 'aqualuxe'),
            'items_list_navigation' => __('Categories list navigation', 'aqualuxe'),
        ];

        $category_args = [
            'labels' => $category_labels,
            'hierarchical' => true,
            'public' => true,
            'show_ui' => true,
            'show_admin_column' => true,
            'show_in_nav_menus' => true,
            'show_tagcloud' => true,
            'show_in_rest' => true,
            'rewrite' => ['slug' => 'service-category'],
        ];

        register_taxonomy('service_category', ['service'], $category_args);

        // Register service tag taxonomy
        $tag_labels = [
            'name' => _x('Service Tags', 'Taxonomy General Name', 'aqualuxe'),
            'singular_name' => _x('Service Tag', 'Taxonomy Singular Name', 'aqualuxe'),
            'menu_name' => __('Tags', 'aqualuxe'),
            'all_items' => __('All Tags', 'aqualuxe'),
            'parent_item' => __('Parent Tag', 'aqualuxe'),
            'parent_item_colon' => __('Parent Tag:', 'aqualuxe'),
            'new_item_name' => __('New Tag Name', 'aqualuxe'),
            'add_new_item' => __('Add New Tag', 'aqualuxe'),
            'edit_item' => __('Edit Tag', 'aqualuxe'),
            'update_item' => __('Update Tag', 'aqualuxe'),
            'view_item' => __('View Tag', 'aqualuxe'),
            'separate_items_with_commas' => __('Separate tags with commas', 'aqualuxe'),
            'add_or_remove_items' => __('Add or remove tags', 'aqualuxe'),
            'choose_from_most_used' => __('Choose from the most used', 'aqualuxe'),
            'popular_items' => __('Popular Tags', 'aqualuxe'),
            'search_items' => __('Search Tags', 'aqualuxe'),
            'not_found' => __('Not Found', 'aqualuxe'),
            'no_terms' => __('No tags', 'aqualuxe'),
            'items_list' => __('Tags list', 'aqualuxe'),
            'items_list_navigation' => __('Tags list navigation', 'aqualuxe'),
        ];

        $tag_args = [
            'labels' => $tag_labels,
            'hierarchical' => false,
            'public' => true,
            'show_ui' => true,
            'show_admin_column' => true,
            'show_in_nav_menus' => true,
            'show_tagcloud' => true,
            'show_in_rest' => true,
            'rewrite' => ['slug' => 'service-tag'],
        ];

        register_taxonomy('service_tag', ['service'], $tag_args);
    }

    /**
     * Register shortcodes
     */
    public function register_shortcodes() {
        // Register services shortcode
        add_shortcode('services', 'aqualuxe_services_shortcode');
        
        // Register service shortcode
        add_shortcode('service', 'aqualuxe_service_shortcode');
        
        // Register service categories shortcode
        add_shortcode('service_categories', 'aqualuxe_service_categories_shortcode');
        
        // Register service booking form shortcode
        add_shortcode('service_booking', 'aqualuxe_service_booking_shortcode');
    }

    /**
     * Register Gutenberg blocks
     */
    public function register_blocks() {
        // Check if Gutenberg is active
        if (!function_exists('register_block_type')) {
            return;
        }

        // Register services block
        register_block_type('aqualuxe/services', [
            'editor_script' => 'aqualuxe-services-block',
            'editor_style' => 'aqualuxe-services-block-editor',
            'render_callback' => 'aqualuxe_services_block_render',
            'attributes' => [
                'layout' => [
                    'type' => 'string',
                    'default' => 'grid',
                ],
                'columns' => [
                    'type' => 'number',
                    'default' => 3,
                ],
                'categories' => [
                    'type' => 'array',
                    'default' => [],
                ],
                'tags' => [
                    'type' => 'array',
                    'default' => [],
                ],
                'limit' => [
                    'type' => 'number',
                    'default' => 6,
                ],
                'orderby' => [
                    'type' => 'string',
                    'default' => 'date',
                ],
                'order' => [
                    'type' => 'string',
                    'default' => 'DESC',
                ],
                'showImage' => [
                    'type' => 'boolean',
                    'default' => true,
                ],
                'showExcerpt' => [
                    'type' => 'boolean',
                    'default' => true,
                ],
                'showButton' => [
                    'type' => 'boolean',
                    'default' => true,
                ],
                'buttonText' => [
                    'type' => 'string',
                    'default' => 'View Details',
                ],
                'className' => [
                    'type' => 'string',
                ],
            ],
        ]);

        // Register service block
        register_block_type('aqualuxe/service', [
            'editor_script' => 'aqualuxe-services-block',
            'editor_style' => 'aqualuxe-services-block-editor',
            'render_callback' => 'aqualuxe_service_block_render',
            'attributes' => [
                'id' => [
                    'type' => 'number',
                ],
                'showImage' => [
                    'type' => 'boolean',
                    'default' => true,
                ],
                'showExcerpt' => [
                    'type' => 'boolean',
                    'default' => true,
                ],
                'showButton' => [
                    'type' => 'boolean',
                    'default' => true,
                ],
                'buttonText' => [
                    'type' => 'string',
                    'default' => 'View Details',
                ],
                'className' => [
                    'type' => 'string',
                ],
            ],
        ]);

        // Register service categories block
        register_block_type('aqualuxe/service-categories', [
            'editor_script' => 'aqualuxe-services-block',
            'editor_style' => 'aqualuxe-services-block-editor',
            'render_callback' => 'aqualuxe_service_categories_block_render',
            'attributes' => [
                'layout' => [
                    'type' => 'string',
                    'default' => 'grid',
                ],
                'columns' => [
                    'type' => 'number',
                    'default' => 3,
                ],
                'showCount' => [
                    'type' => 'boolean',
                    'default' => true,
                ],
                'showImage' => [
                    'type' => 'boolean',
                    'default' => true,
                ],
                'showDescription' => [
                    'type' => 'boolean',
                    'default' => true,
                ],
                'className' => [
                    'type' => 'string',
                ],
            ],
        ]);

        // Register service booking form block
        register_block_type('aqualuxe/service-booking', [
            'editor_script' => 'aqualuxe-services-block',
            'editor_style' => 'aqualuxe-services-block-editor',
            'render_callback' => 'aqualuxe_service_booking_block_render',
            'attributes' => [
                'serviceId' => [
                    'type' => 'number',
                ],
                'title' => [
                    'type' => 'string',
                    'default' => 'Book This Service',
                ],
                'buttonText' => [
                    'type' => 'string',
                    'default' => 'Book Now',
                ],
                'className' => [
                    'type' => 'string',
                ],
            ],
        ]);
    }

    /**
     * Add services menu item
     *
     * @param array $items
     * @return array
     */
    public function add_services_menu_item($items) {
        // Add services menu item
        $items['services'] = [
            'title' => __('Services', 'aqualuxe'),
            'url' => get_post_type_archive_link('service'),
            'order' => 20,
        ];

        return $items;
    }

    /**
     * Register widget areas
     */
    public function register_widget_areas() {
        // Register services sidebar
        register_sidebar([
            'name' => esc_html__('Services Sidebar', 'aqualuxe'),
            'id' => 'services-sidebar',
            'description' => esc_html__('Add widgets here to appear in your services sidebar.', 'aqualuxe'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>',
        ]);
    }

    /**
     * Add dashboard widget
     */
    public function add_dashboard_widget() {
        // Add services dashboard widget
        wp_add_dashboard_widget(
            'aqualuxe_services_dashboard_widget',
            __('Services Overview', 'aqualuxe'),
            'aqualuxe_services_dashboard_widget_callback'
        );
    }

    /**
     * Register REST fields
     */
    public function register_rest_fields() {
        // Register service meta fields
        register_rest_field('service', 'service_meta', [
            'get_callback' => 'aqualuxe_get_service_meta',
            'update_callback' => null,
            'schema' => null,
        ]);

        // Register service featured image
        register_rest_field('service', 'featured_image', [
            'get_callback' => 'aqualuxe_get_service_featured_image',
            'update_callback' => null,
            'schema' => null,
        ]);
    }

    /**
     * Add meta boxes
     */
    public function add_meta_boxes() {
        // Add service details meta box
        add_meta_box(
            'service_details',
            __('Service Details', 'aqualuxe'),
            'aqualuxe_service_details_meta_box_callback',
            'service',
            'normal',
            'high'
        );

        // Add service pricing meta box
        add_meta_box(
            'service_pricing',
            __('Service Pricing', 'aqualuxe'),
            'aqualuxe_service_pricing_meta_box_callback',
            'service',
            'normal',
            'high'
        );

        // Add service booking meta box
        add_meta_box(
            'service_booking',
            __('Service Booking', 'aqualuxe'),
            'aqualuxe_service_booking_meta_box_callback',
            'service',
            'normal',
            'high'
        );
    }

    /**
     * Save meta boxes
     *
     * @param int $post_id
     */
    public function save_meta_boxes($post_id) {
        // Check if our nonce is set
        if (!isset($_POST['aqualuxe_service_meta_box_nonce'])) {
            return;
        }

        // Verify that the nonce is valid
        if (!wp_verify_nonce($_POST['aqualuxe_service_meta_box_nonce'], 'aqualuxe_service_meta_box')) {
            return;
        }

        // If this is an autosave, our form has not been submitted, so we don't want to do anything
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        // Check the user's permissions
        if (isset($_POST['post_type']) && 'service' == $_POST['post_type']) {
            if (!current_user_can('edit_page', $post_id)) {
                return;
            }
        } else {
            if (!current_user_can('edit_post', $post_id)) {
                return;
            }
        }

        // Save service details
        if (isset($_POST['service_duration'])) {
            update_post_meta($post_id, '_service_duration', sanitize_text_field($_POST['service_duration']));
        }

        if (isset($_POST['service_location'])) {
            update_post_meta($post_id, '_service_location', sanitize_text_field($_POST['service_location']));
        }

        if (isset($_POST['service_availability'])) {
            update_post_meta($post_id, '_service_availability', sanitize_text_field($_POST['service_availability']));
        }

        // Save service pricing
        if (isset($_POST['service_price'])) {
            update_post_meta($post_id, '_service_price', sanitize_text_field($_POST['service_price']));
        }

        if (isset($_POST['service_sale_price'])) {
            update_post_meta($post_id, '_service_sale_price', sanitize_text_field($_POST['service_sale_price']));
        }

        if (isset($_POST['service_price_type'])) {
            update_post_meta($post_id, '_service_price_type', sanitize_text_field($_POST['service_price_type']));
        }

        // Save service booking
        if (isset($_POST['service_booking_enabled'])) {
            update_post_meta($post_id, '_service_booking_enabled', 'yes');
        } else {
            update_post_meta($post_id, '_service_booking_enabled', 'no');
        }

        if (isset($_POST['service_booking_type'])) {
            update_post_meta($post_id, '_service_booking_type', sanitize_text_field($_POST['service_booking_type']));
        }

        if (isset($_POST['service_booking_form'])) {
            update_post_meta($post_id, '_service_booking_form', absint($_POST['service_booking_form']));
        }
    }

    /**
     * Add columns
     *
     * @param array $columns
     * @return array
     */
    public function add_columns($columns) {
        $new_columns = [];

        // Add checkbox
        if (isset($columns['cb'])) {
            $new_columns['cb'] = $columns['cb'];
        }

        // Add thumbnail
        $new_columns['thumbnail'] = __('Image', 'aqualuxe');

        // Add title
        if (isset($columns['title'])) {
            $new_columns['title'] = $columns['title'];
        }

        // Add price
        $new_columns['price'] = __('Price', 'aqualuxe');

        // Add duration
        $new_columns['duration'] = __('Duration', 'aqualuxe');

        // Add location
        $new_columns['location'] = __('Location', 'aqualuxe');

        // Add booking
        $new_columns['booking'] = __('Booking', 'aqualuxe');

        // Add categories
        if (isset($columns['taxonomy-service_category'])) {
            $new_columns['taxonomy-service_category'] = $columns['taxonomy-service_category'];
        }

        // Add tags
        if (isset($columns['taxonomy-service_tag'])) {
            $new_columns['taxonomy-service_tag'] = $columns['taxonomy-service_tag'];
        }

        // Add date
        if (isset($columns['date'])) {
            $new_columns['date'] = $columns['date'];
        }

        return $new_columns;
    }

    /**
     * Render columns
     *
     * @param string $column
     * @param int $post_id
     */
    public function render_columns($column, $post_id) {
        switch ($column) {
            case 'thumbnail':
                if (has_post_thumbnail($post_id)) {
                    echo '<a href="' . esc_url(get_edit_post_link($post_id)) . '">' . get_the_post_thumbnail($post_id, [50, 50]) . '</a>';
                } else {
                    echo '<a href="' . esc_url(get_edit_post_link($post_id)) . '"><img src="' . esc_url($this->url . '/assets/images/placeholder.png') . '" width="50" height="50" alt="' . esc_attr__('No Image', 'aqualuxe') . '" /></a>';
                }
                break;

            case 'price':
                $price = get_post_meta($post_id, '_service_price', true);
                $sale_price = get_post_meta($post_id, '_service_sale_price', true);
                $price_type = get_post_meta($post_id, '_service_price_type', true);

                if ($price) {
                    if ($sale_price) {
                        echo '<del>' . esc_html(aqualuxe_format_price($price)) . '</del> ' . esc_html(aqualuxe_format_price($sale_price));
                    } else {
                        echo esc_html(aqualuxe_format_price($price));
                    }

                    if ($price_type) {
                        echo ' <small>(' . esc_html($price_type) . ')</small>';
                    }
                } else {
                    echo '&mdash;';
                }
                break;

            case 'duration':
                $duration = get_post_meta($post_id, '_service_duration', true);
                echo $duration ? esc_html($duration) : '&mdash;';
                break;

            case 'location':
                $location = get_post_meta($post_id, '_service_location', true);
                echo $location ? esc_html($location) : '&mdash;';
                break;

            case 'booking':
                $booking_enabled = get_post_meta($post_id, '_service_booking_enabled', true);
                echo $booking_enabled === 'yes' ? '<span class="dashicons dashicons-yes" style="color: green;"></span>' : '<span class="dashicons dashicons-no" style="color: red;"></span>';
                break;
        }
    }

    /**
     * Sortable columns
     *
     * @param array $columns
     * @return array
     */
    public function sortable_columns($columns) {
        $columns['price'] = 'price';
        $columns['duration'] = 'duration';
        $columns['location'] = 'location';
        $columns['booking'] = 'booking';
        return $columns;
    }

    /**
     * Add filters
     *
     * @param string $post_type
     */
    public function add_filters($post_type) {
        // Check if we're on the services admin page
        if ($post_type !== 'service') {
            return;
        }

        // Add booking filter
        $booking_options = [
            '' => __('All Booking Statuses', 'aqualuxe'),
            'yes' => __('Booking Enabled', 'aqualuxe'),
            'no' => __('Booking Disabled', 'aqualuxe'),
        ];

        $current_booking = isset($_GET['booking']) ? $_GET['booking'] : '';

        echo '<select name="booking">';
        foreach ($booking_options as $value => $label) {
            printf(
                '<option value="%s" %s>%s</option>',
                esc_attr($value),
                selected($value, $current_booking, false),
                esc_html($label)
            );
        }
        echo '</select>';

        // Add price filter
        $price_options = [
            '' => __('All Prices', 'aqualuxe'),
            'free' => __('Free', 'aqualuxe'),
            'paid' => __('Paid', 'aqualuxe'),
            'sale' => __('On Sale', 'aqualuxe'),
        ];

        $current_price = isset($_GET['price_filter']) ? $_GET['price_filter'] : '';

        echo '<select name="price_filter">';
        foreach ($price_options as $value => $label) {
            printf(
                '<option value="%s" %s>%s</option>',
                esc_attr($value),
                selected($value, $current_price, false),
                esc_html($label)
            );
        }
        echo '</select>';
    }

    /**
     * Filter query
     *
     * @param WP_Query $query
     */
    public function filter_query($query) {
        global $pagenow;

        // Check if we're on the services admin page
        $is_services_admin = $pagenow === 'edit.php' && isset($_GET['post_type']) && $_GET['post_type'] === 'service';

        if (!$is_services_admin || !$query->is_main_query()) {
            return;
        }

        // Filter by booking status
        if (isset($_GET['booking']) && $_GET['booking'] !== '') {
            $meta_query = $query->get('meta_query');
            if (!is_array($meta_query)) {
                $meta_query = [];
            }

            $meta_query[] = [
                'key' => '_service_booking_enabled',
                'value' => sanitize_text_field($_GET['booking']),
                'compare' => '=',
            ];

            $query->set('meta_query', $meta_query);
        }

        // Filter by price
        if (isset($_GET['price_filter']) && $_GET['price_filter'] !== '') {
            $meta_query = $query->get('meta_query');
            if (!is_array($meta_query)) {
                $meta_query = [];
            }

            switch ($_GET['price_filter']) {
                case 'free':
                    $meta_query[] = [
                        'key' => '_service_price',
                        'value' => '0',
                        'compare' => '=',
                    ];
                    break;

                case 'paid':
                    $meta_query[] = [
                        'key' => '_service_price',
                        'value' => '0',
                        'compare' => '>',
                    ];
                    break;

                case 'sale':
                    $meta_query[] = [
                        'key' => '_service_sale_price',
                        'value' => '',
                        'compare' => '!=',
                    ];
                    break;
            }

            $query->set('meta_query', $meta_query);
        }
    }

    /**
     * Template include
     *
     * @param string $template
     * @return string
     */
    public function template_include($template) {
        // Check if module is enabled
        if (!$this->is_enabled()) {
            return $template;
        }

        // Check if we're on a services page
        if (is_post_type_archive('service') || is_singular('service') || is_tax('service_category') || is_tax('service_tag')) {
            // Get template name
            $template_name = '';

            if (is_post_type_archive('service')) {
                $template_name = 'archive-service.php';
            } elseif (is_singular('service')) {
                $template_name = 'single-service.php';
            } elseif (is_tax('service_category')) {
                $template_name = 'taxonomy-service_category.php';
            } elseif (is_tax('service_tag')) {
                $template_name = 'taxonomy-service_tag.php';
            }

            // Look for template in theme
            $theme_template = locate_template([
                'modules/services/templates/' . $template_name,
                $template_name,
            ]);

            // Use theme template if found
            if ($theme_template) {
                return $theme_template;
            }

            // Look for template in module
            $module_template = $this->dir . '/templates/' . $template_name;
            if (file_exists($module_template)) {
                return $module_template;
            }
        }

        return $template;
    }

    /**
     * Body class
     *
     * @param array $classes
     * @return array
     */
    public function body_class($classes) {
        // Check if module is enabled
        if (!$this->is_enabled()) {
            return $classes;
        }

        // Check if we're on a services page
        if (is_post_type_archive('service') || is_singular('service') || is_tax('service_category') || is_tax('service_tag')) {
            $classes[] = 'aqualuxe-services';

            // Add layout class
            $layout = $this->get_option('layout', 'grid');
            $classes[] = 'aqualuxe-services-layout-' . $layout;

            // Add sidebar class
            if ($this->get_option('sidebar', true)) {
                $classes[] = 'aqualuxe-services-has-sidebar';
            } else {
                $classes[] = 'aqualuxe-services-no-sidebar';
            }
        }

        return $classes;
    }

    /**
     * Add to sitemap
     *
     * @param array $post_types
     * @return array
     */
    public function add_to_sitemap($post_types) {
        // Check if module is enabled
        if (!$this->is_enabled()) {
            return $post_types;
        }

        // Add service post type to sitemap
        $post_types['service'] = 'service';

        return $post_types;
    }

    /**
     * Sanitize layout
     *
     * @param string $value
     * @return string
     */
    public function sanitize_layout($value) {
        $allowed_values = ['grid', 'list', 'masonry'];
        return in_array($value, $allowed_values) ? $value : 'grid';
    }

    /**
     * Get default options
     *
     * @return array
     */
    protected function get_default_options() {
        return array_merge(parent::get_default_options(), [
            'slug' => 'services',
            'layout' => 'grid',
            'per_page' => 9,
            'sidebar' => true,
            'featured_image' => true,
            'related_services' => true,
            'booking_form' => true,
        ]);
    }
}

// Initialize module
new AquaLuxe_Module_Services();