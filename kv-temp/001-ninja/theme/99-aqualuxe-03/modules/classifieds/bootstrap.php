<?php
/**
 * Classifieds Module Bootstrap
 *
 * @package AquaLuxe\Modules\Classifieds
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Classifieds Module Class
 */
class AquaLuxe_Classifieds {
    
    /**
     * Constructor
     */
    public function __construct() {
        add_action('init', array($this, 'register_post_types'));
        add_action('init', array($this, 'register_taxonomies'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        
        // AJAX handlers
        add_action('wp_ajax_submit_classified', array($this, 'ajax_submit_classified'));
        add_action('wp_ajax_nopriv_submit_classified', array($this, 'ajax_submit_classified'));
        
        // Shortcodes
        add_shortcode('classified_form', array($this, 'classified_form_shortcode'));
        add_shortcode('classifieds_list', array($this, 'classifieds_list_shortcode'));
        
        // Template hooks
        add_filter('single_template', array($this, 'classified_single_template'));
        add_filter('archive_template', array($this, 'classified_archive_template'));
        
        // Meta boxes
        add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
        add_action('save_post', array($this, 'save_classified_meta'));
        
        // Create pages
        add_action('init', array($this, 'create_classified_pages'));
    }
    
    /**
     * Register custom post types
     */
    public function register_post_types() {
        register_post_type('classified', array(
            'labels' => array(
                'name' => __('Classifieds', 'aqualuxe'),
                'singular_name' => __('Classified', 'aqualuxe'),
                'add_new' => __('Add New Classified', 'aqualuxe'),
                'add_new_item' => __('Add New Classified', 'aqualuxe'),
                'edit_item' => __('Edit Classified', 'aqualuxe'),
                'new_item' => __('New Classified', 'aqualuxe'),
                'view_item' => __('View Classified', 'aqualuxe'),
                'search_items' => __('Search Classifieds', 'aqualuxe'),
                'not_found' => __('No classifieds found', 'aqualuxe'),
                'not_found_in_trash' => __('No classifieds found in trash', 'aqualuxe'),
            ),
            'public' => true,
            'has_archive' => true,
            'rewrite' => array('slug' => 'classifieds'),
            'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'author', 'custom-fields'),
            'menu_icon' => 'dashicons-megaphone',
            'show_in_rest' => true,
            'publicly_queryable' => true,
            'capability_type' => 'post',
        ));
    }
    
    /**
     * Register taxonomies
     */
    public function register_taxonomies() {
        // Classified Category
        register_taxonomy('classified_category', 'classified', array(
            'labels' => array(
                'name' => __('Classified Categories', 'aqualuxe'),
                'singular_name' => __('Classified Category', 'aqualuxe'),
                'search_items' => __('Search Categories', 'aqualuxe'),
                'all_items' => __('All Categories', 'aqualuxe'),
                'edit_item' => __('Edit Category', 'aqualuxe'),
                'update_item' => __('Update Category', 'aqualuxe'),
                'add_new_item' => __('Add New Category', 'aqualuxe'),
                'new_item_name' => __('New Category Name', 'aqualuxe'),
                'menu_name' => __('Categories', 'aqualuxe'),
            ),
            'hierarchical' => true,
            'rewrite' => array('slug' => 'classified-category'),
            'show_in_rest' => true,
        ));
        
        // Classified Location
        register_taxonomy('classified_location', 'classified', array(
            'labels' => array(
                'name' => __('Locations', 'aqualuxe'),
                'singular_name' => __('Location', 'aqualuxe'),
                'search_items' => __('Search Locations', 'aqualuxe'),
                'all_items' => __('All Locations', 'aqualuxe'),
                'edit_item' => __('Edit Location', 'aqualuxe'),
                'update_item' => __('Update Location', 'aqualuxe'),
                'add_new_item' => __('Add New Location', 'aqualuxe'),
                'new_item_name' => __('New Location Name', 'aqualuxe'),
                'menu_name' => __('Locations', 'aqualuxe'),
            ),
            'hierarchical' => true,
            'rewrite' => array('slug' => 'location'),
            'show_in_rest' => true,
        ));
    }
    
    /**
     * Enqueue scripts
     */
    public function enqueue_scripts() {
        wp_enqueue_script(
            'aqualuxe-classifieds',
            AQUALUXE_THEME_URI . '/modules/classifieds/assets/classifieds.js',
            array('jquery'),
            AQUALUXE_VERSION,
            true
        );
        
        wp_localize_script('aqualuxe-classifieds', 'aqualuxe_classifieds', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('classifieds_nonce'),
        ));
    }
    
    /**
     * Add meta boxes
     */
    public function add_meta_boxes() {
        add_meta_box(
            'classified_details',
            __('Classified Details', 'aqualuxe'),
            array($this, 'classified_details_meta_box'),
            'classified',
            'normal',
            'high'
        );
    }
    
    /**
     * Classified details meta box
     */
    public function classified_details_meta_box($post) {
        wp_nonce_field('classified_meta_nonce', 'classified_meta_nonce');
        
        $price = get_post_meta($post->ID, '_classified_price', true);
        $contact_name = get_post_meta($post->ID, '_classified_contact_name', true);
        $contact_email = get_post_meta($post->ID, '_classified_contact_email', true);
        $contact_phone = get_post_meta($post->ID, '_classified_contact_phone', true);
        $location = get_post_meta($post->ID, '_classified_location', true);
        $condition = get_post_meta($post->ID, '_classified_condition', true);
        $expiry_date = get_post_meta($post->ID, '_classified_expiry_date', true);
        $featured = get_post_meta($post->ID, '_classified_featured', true);
        $status = get_post_meta($post->ID, '_classified_status', true) ?: 'active';
        
        ?>
        <table class="form-table">
            <tr>
                <th><label for="classified_price"><?php _e('Price', 'aqualuxe'); ?></label></th>
                <td>
                    <input type="number" id="classified_price" name="classified_price" value="<?php echo esc_attr($price); ?>" step="0.01" min="0" />
                    <p class="description"><?php _e('Enter the price for this item. Leave empty for "Contact for Price".', 'aqualuxe'); ?></p>
                </td>
            </tr>
            <tr>
                <th><label for="classified_condition"><?php _e('Condition', 'aqualuxe'); ?></label></th>
                <td>
                    <select id="classified_condition" name="classified_condition">
                        <option value="new" <?php selected($condition, 'new'); ?>><?php _e('New', 'aqualuxe'); ?></option>
                        <option value="like-new" <?php selected($condition, 'like-new'); ?>><?php _e('Like New', 'aqualuxe'); ?></option>
                        <option value="good" <?php selected($condition, 'good'); ?>><?php _e('Good', 'aqualuxe'); ?></option>
                        <option value="fair" <?php selected($condition, 'fair'); ?>><?php _e('Fair', 'aqualuxe'); ?></option>
                        <option value="poor" <?php selected($condition, 'poor'); ?>><?php _e('Poor', 'aqualuxe'); ?></option>
                    </select>
                </td>
            </tr>
            <tr>
                <th><label for="classified_contact_name"><?php _e('Contact Name', 'aqualuxe'); ?></label></th>
                <td>
                    <input type="text" id="classified_contact_name" name="classified_contact_name" value="<?php echo esc_attr($contact_name); ?>" />
                </td>
            </tr>
            <tr>
                <th><label for="classified_contact_email"><?php _e('Contact Email', 'aqualuxe'); ?></label></th>
                <td>
                    <input type="email" id="classified_contact_email" name="classified_contact_email" value="<?php echo esc_attr($contact_email); ?>" />
                </td>
            </tr>
            <tr>
                <th><label for="classified_contact_phone"><?php _e('Contact Phone', 'aqualuxe'); ?></label></th>
                <td>
                    <input type="tel" id="classified_contact_phone" name="classified_contact_phone" value="<?php echo esc_attr($contact_phone); ?>" />
                </td>
            </tr>
            <tr>
                <th><label for="classified_location"><?php _e('Location', 'aqualuxe'); ?></label></th>
                <td>
                    <input type="text" id="classified_location" name="classified_location" value="<?php echo esc_attr($location); ?>" />
                    <p class="description"><?php _e('City, State/Region, Country', 'aqualuxe'); ?></p>
                </td>
            </tr>
            <tr>
                <th><label for="classified_expiry_date"><?php _e('Expiry Date', 'aqualuxe'); ?></label></th>
                <td>
                    <input type="date" id="classified_expiry_date" name="classified_expiry_date" value="<?php echo esc_attr($expiry_date); ?>" />
                    <p class="description"><?php _e('When should this classified expire?', 'aqualuxe'); ?></p>
                </td>
            </tr>
            <tr>
                <th><label for="classified_status"><?php _e('Status', 'aqualuxe'); ?></label></th>
                <td>
                    <select id="classified_status" name="classified_status">
                        <option value="active" <?php selected($status, 'active'); ?>><?php _e('Active', 'aqualuxe'); ?></option>
                        <option value="sold" <?php selected($status, 'sold'); ?>><?php _e('Sold', 'aqualuxe'); ?></option>
                        <option value="expired" <?php selected($status, 'expired'); ?>><?php _e('Expired', 'aqualuxe'); ?></option>
                        <option value="pending" <?php selected($status, 'pending'); ?>><?php _e('Pending Review', 'aqualuxe'); ?></option>
                    </select>
                </td>
            </tr>
            <tr>
                <th><label for="classified_featured"><?php _e('Featured', 'aqualuxe'); ?></label></th>
                <td>
                    <label>
                        <input type="checkbox" id="classified_featured" name="classified_featured" value="1" <?php checked($featured, '1'); ?> />
                        <?php _e('Mark as featured classified', 'aqualuxe'); ?>
                    </label>
                </td>
            </tr>
        </table>
        <?php
    }
    
    /**
     * Save classified meta
     */
    public function save_classified_meta($post_id) {
        if (!isset($_POST['classified_meta_nonce']) || !wp_verify_nonce($_POST['classified_meta_nonce'], 'classified_meta_nonce')) {
            return;
        }
        
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
        
        $fields = array(
            'classified_price',
            'classified_condition',
            'classified_contact_name',
            'classified_contact_email',
            'classified_contact_phone',
            'classified_location',
            'classified_expiry_date',
            'classified_status',
        );
        
        foreach ($fields as $field) {
            if (isset($_POST[$field])) {
                update_post_meta($post_id, '_' . $field, sanitize_text_field($_POST[$field]));
            }
        }
        
        // Handle featured checkbox
        $featured = isset($_POST['classified_featured']) ? '1' : '0';
        update_post_meta($post_id, '_classified_featured', $featured);
    }
    
    /**
     * Create classified pages
     */
    public function create_classified_pages() {
        $pages = array(
            'post-classified' => array(
                'title' => __('Post a Classified', 'aqualuxe'),
                'content' => '[classified_form]',
            ),
            'browse-classifieds' => array(
                'title' => __('Browse Classifieds', 'aqualuxe'),
                'content' => '[classifieds_list]',
            ),
        );
        
        foreach ($pages as $slug => $page_data) {
            $page = get_page_by_path($slug);
            if (!$page) {
                wp_insert_post(array(
                    'post_title' => $page_data['title'],
                    'post_content' => $page_data['content'],
                    'post_status' => 'publish',
                    'post_type' => 'page',
                    'post_name' => $slug,
                ));
            }
        }
    }
    
    /**
     * Classified form shortcode
     */
    public function classified_form_shortcode($atts) {
        if (!is_user_logged_in()) {
            return '<p>' . sprintf(__('Please <a href="%s">log in</a> to post a classified.', 'aqualuxe'), wp_login_url(get_permalink())) . '</p>';
        }
        
        ob_start();
        ?>
        <div class="classified-form-wrapper">
            <form id="classified-form" class="classified-form" enctype="multipart/form-data">
                <h3><?php _e('Post a Classified Ad', 'aqualuxe'); ?></h3>
                
                <div class="form-group">
                    <label for="classified_title"><?php _e('Title *', 'aqualuxe'); ?></label>
                    <input type="text" id="classified_title" name="title" required>
                </div>
                
                <div class="form-group">
                    <label for="classified_category"><?php _e('Category *', 'aqualuxe'); ?></label>
                    <select id="classified_category" name="category" required>
                        <option value=""><?php _e('Select Category', 'aqualuxe'); ?></option>
                        <?php
                        $categories = get_terms(array(
                            'taxonomy' => 'classified_category',
                            'hide_empty' => false,
                        ));
                        foreach ($categories as $category) {
                            echo '<option value="' . esc_attr($category->term_id) . '">' . esc_html($category->name) . '</option>';
                        }
                        ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="classified_description"><?php _e('Description *', 'aqualuxe'); ?></label>
                    <textarea id="classified_description" name="description" rows="6" required></textarea>
                </div>
                
                <div class="form-row">
                    <div class="form-group half">
                        <label for="classified_price"><?php _e('Price', 'aqualuxe'); ?></label>
                        <input type="number" id="classified_price" name="price" step="0.01" min="0">
                        <small><?php _e('Leave empty for "Contact for Price"', 'aqualuxe'); ?></small>
                    </div>
                    <div class="form-group half">
                        <label for="classified_condition"><?php _e('Condition *', 'aqualuxe'); ?></label>
                        <select id="classified_condition" name="condition" required>
                            <option value="new"><?php _e('New', 'aqualuxe'); ?></option>
                            <option value="like-new"><?php _e('Like New', 'aqualuxe'); ?></option>
                            <option value="good"><?php _e('Good', 'aqualuxe'); ?></option>
                            <option value="fair"><?php _e('Fair', 'aqualuxe'); ?></option>
                            <option value="poor"><?php _e('Poor', 'aqualuxe'); ?></option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="classified_location"><?php _e('Location *', 'aqualuxe'); ?></label>
                    <input type="text" id="classified_location" name="location" placeholder="City, State/Region, Country" required>
                </div>
                
                <div class="form-row">
                    <div class="form-group half">
                        <label for="classified_contact_name"><?php _e('Contact Name *', 'aqualuxe'); ?></label>
                        <input type="text" id="classified_contact_name" name="contact_name" required>
                    </div>
                    <div class="form-group half">
                        <label for="classified_contact_email"><?php _e('Contact Email *', 'aqualuxe'); ?></label>
                        <input type="email" id="classified_contact_email" name="contact_email" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="classified_contact_phone"><?php _e('Contact Phone', 'aqualuxe'); ?></label>
                    <input type="tel" id="classified_contact_phone" name="contact_phone">
                </div>
                
                <div class="form-group">
                    <label for="classified_images"><?php _e('Images', 'aqualuxe'); ?></label>
                    <input type="file" id="classified_images" name="images[]" multiple accept="image/*">
                    <small><?php _e('You can upload up to 5 images (max 2MB each)', 'aqualuxe'); ?></small>
                </div>
                
                <div class="form-group">
                    <label>
                        <input type="checkbox" name="terms_agreed" required>
                        <?php _e('I agree to the terms and conditions *', 'aqualuxe'); ?>
                    </label>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="submit-btn">
                        <?php _e('Submit Classified', 'aqualuxe'); ?>
                    </button>
                </div>
                
                <?php wp_nonce_field('classified_form', 'classified_form_nonce'); ?>
            </form>
        </div>
        <?php
        return ob_get_clean();
    }
    
    /**
     * Classifieds list shortcode
     */
    public function classifieds_list_shortcode($atts) {
        $atts = shortcode_atts(array(
            'posts_per_page' => 12,
            'category' => '',
            'location' => '',
            'featured_only' => false,
        ), $atts);
        
        $args = array(
            'post_type' => 'classified',
            'post_status' => 'publish',
            'posts_per_page' => intval($atts['posts_per_page']),
            'paged' => get_query_var('paged') ? get_query_var('paged') : 1,
            'meta_query' => array(
                array(
                    'key' => '_classified_status',
                    'value' => 'active',
                    'compare' => '=',
                ),
            ),
        );
        
        if (!empty($atts['category'])) {
            $args['tax_query'][] = array(
                'taxonomy' => 'classified_category',
                'field' => 'slug',
                'terms' => $atts['category'],
            );
        }
        
        if (!empty($atts['location'])) {
            $args['tax_query'][] = array(
                'taxonomy' => 'classified_location',
                'field' => 'slug',
                'terms' => $atts['location'],
            );
        }
        
        if ($atts['featured_only']) {
            $args['meta_query'][] = array(
                'key' => '_classified_featured',
                'value' => '1',
                'compare' => '=',
            );
        }
        
        $classifieds_query = new WP_Query($args);
        
        ob_start();
        ?>
        <div class="classifieds-list">
            <?php if ($classifieds_query->have_posts()) : ?>
                <div class="classifieds-grid">
                    <?php while ($classifieds_query->have_posts()) : $classifieds_query->the_post(); ?>
                        <?php $this->render_classified_card(get_the_ID()); ?>
                    <?php endwhile; ?>
                </div>
                
                <?php
                // Pagination
                if ($classifieds_query->max_num_pages > 1) {
                    echo '<div class="classifieds-pagination">';
                    echo paginate_links(array(
                        'total' => $classifieds_query->max_num_pages,
                        'current' => $args['paged'],
                    ));
                    echo '</div>';
                }
                ?>
            <?php else : ?>
                <p><?php _e('No classifieds found.', 'aqualuxe'); ?></p>
            <?php endif; ?>
        </div>
        <?php
        wp_reset_postdata();
        return ob_get_clean();
    }
    
    /**
     * Render classified card
     */
    private function render_classified_card($post_id) {
        $price = get_post_meta($post_id, '_classified_price', true);
        $condition = get_post_meta($post_id, '_classified_condition', true);
        $location = get_post_meta($post_id, '_classified_location', true);
        $featured = get_post_meta($post_id, '_classified_featured', true);
        
        ?>
        <div class="classified-card <?php echo $featured ? 'featured' : ''; ?>">
            <?php if ($featured) : ?>
                <div class="featured-badge"><?php _e('Featured', 'aqualuxe'); ?></div>
            <?php endif; ?>
            
            <div class="classified-image">
                <a href="<?php the_permalink(); ?>">
                    <?php if (has_post_thumbnail()) : ?>
                        <?php the_post_thumbnail('medium'); ?>
                    <?php else : ?>
                        <div class="no-image"><?php _e('No Image', 'aqualuxe'); ?></div>
                    <?php endif; ?>
                </a>
            </div>
            
            <div class="classified-content">
                <h3 class="classified-title">
                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                </h3>
                
                <div class="classified-price">
                    <?php if ($price) : ?>
                        <span class="price"><?php echo wc_price($price); ?></span>
                    <?php else : ?>
                        <span class="contact-price"><?php _e('Contact for Price', 'aqualuxe'); ?></span>
                    <?php endif; ?>
                </div>
                
                <div class="classified-meta">
                    <?php if ($condition) : ?>
                        <span class="condition"><?php echo esc_html(ucfirst(str_replace('-', ' ', $condition))); ?></span>
                    <?php endif; ?>
                    
                    <?php if ($location) : ?>
                        <span class="location"><?php echo esc_html($location); ?></span>
                    <?php endif; ?>
                </div>
                
                <div class="classified-excerpt">
                    <?php echo wp_trim_words(get_the_excerpt(), 20); ?>
                </div>
                
                <div class="classified-date">
                    <?php printf(__('Posted %s ago', 'aqualuxe'), human_time_diff(get_the_time('U'), current_time('timestamp'))); ?>
                </div>
            </div>
        </div>
        <?php
    }
    
    /**
     * AJAX handler for classified submission
     */
    public function ajax_submit_classified() {
        check_ajax_referer('classifieds_nonce', 'nonce');
        
        if (!is_user_logged_in()) {
            wp_send_json_error(__('You must be logged in to submit a classified.', 'aqualuxe'));
        }
        
        $title = sanitize_text_field($_POST['title']);
        $description = wp_kses_post($_POST['description']);
        $category = intval($_POST['category']);
        $price = floatval($_POST['price']);
        $condition = sanitize_text_field($_POST['condition']);
        $location = sanitize_text_field($_POST['location']);
        $contact_name = sanitize_text_field($_POST['contact_name']);
        $contact_email = sanitize_email($_POST['contact_email']);
        $contact_phone = sanitize_text_field($_POST['contact_phone']);
        
        // Create the classified post
        $post_id = wp_insert_post(array(
            'post_title' => $title,
            'post_content' => $description,
            'post_type' => 'classified',
            'post_status' => 'pending', // Require approval
            'post_author' => get_current_user_id(),
        ));
        
        if (is_wp_error($post_id)) {
            wp_send_json_error(__('Error creating classified.', 'aqualuxe'));
        }
        
        // Set category
        if ($category) {
            wp_set_post_terms($post_id, array($category), 'classified_category');
        }
        
        // Save meta fields
        $meta_fields = array(
            '_classified_price' => $price,
            '_classified_condition' => $condition,
            '_classified_location' => $location,
            '_classified_contact_name' => $contact_name,
            '_classified_contact_email' => $contact_email,
            '_classified_contact_phone' => $contact_phone,
            '_classified_status' => 'active',
        );
        
        foreach ($meta_fields as $key => $value) {
            update_post_meta($post_id, $key, $value);
        }
        
        wp_send_json_success(array(
            'message' => __('Classified submitted successfully! It will be reviewed before being published.', 'aqualuxe'),
            'post_id' => $post_id,
        ));
    }
    
    /**
     * Custom single template for classifieds
     */
    public function classified_single_template($template) {
        if (is_singular('classified')) {
            $custom_template = locate_template('single-classified.php');
            if ($custom_template) {
                return $custom_template;
            }
        }
        return $template;
    }
    
    /**
     * Custom archive template for classifieds
     */
    public function classified_archive_template($template) {
        if (is_post_type_archive('classified') || is_tax('classified_category') || is_tax('classified_location')) {
            $custom_template = locate_template('archive-classified.php');
            if ($custom_template) {
                return $custom_template;
            }
        }
        return $template;
    }
}

// Initialize the module
new AquaLuxe_Classifieds();