<?php
/**
 * REST API functions for AquaLuxe theme
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register REST API endpoints
 */
function aqualuxe_register_rest_routes() {
    // Register product endpoint
    register_rest_route('aqualuxe/v1', '/product/(?P<id>\d+)', [
        'methods' => 'GET',
        'callback' => 'aqualuxe_rest_get_product',
        'permission_callback' => '__return_true',
    ]);
    
    // Register wishlist endpoint
    register_rest_route('aqualuxe/v1', '/wishlist', [
        'methods' => 'POST',
        'callback' => 'aqualuxe_rest_update_wishlist',
        'permission_callback' => function() {
            return is_user_logged_in();
        },
    ]);
    
    // Register newsletter endpoint
    register_rest_route('aqualuxe/v1', '/newsletter', [
        'methods' => 'POST',
        'callback' => 'aqualuxe_rest_newsletter_subscribe',
        'permission_callback' => '__return_true',
    ]);
    
    // Register search endpoint
    register_rest_route('aqualuxe/v1', '/search', [
        'methods' => 'GET',
        'callback' => 'aqualuxe_rest_search',
        'permission_callback' => '__return_true',
    ]);
    
    // Register contact form endpoint
    register_rest_route('aqualuxe/v1', '/contact', [
        'methods' => 'POST',
        'callback' => 'aqualuxe_rest_contact_form',
        'permission_callback' => '__return_true',
    ]);
    
    // Register theme options endpoint
    register_rest_route('aqualuxe/v1', '/theme-options', [
        'methods' => 'GET',
        'callback' => 'aqualuxe_rest_theme_options',
        'permission_callback' => function() {
            return current_user_can('edit_theme_options');
        },
    ]);
    
    // Register menu endpoint
    register_rest_route('aqualuxe/v1', '/menu/(?P<location>[a-zA-Z0-9_-]+)', [
        'methods' => 'GET',
        'callback' => 'aqualuxe_rest_get_menu',
        'permission_callback' => '__return_true',
    ]);
    
    // Register cart endpoint
    if (aqualuxe_is_woocommerce_active()) {
        register_rest_route('aqualuxe/v1', '/cart', [
            'methods' => 'GET',
            'callback' => 'aqualuxe_rest_get_cart',
            'permission_callback' => '__return_true',
        ]);
        
        register_rest_route('aqualuxe/v1', '/cart/add', [
            'methods' => 'POST',
            'callback' => 'aqualuxe_rest_add_to_cart',
            'permission_callback' => '__return_true',
        ]);
        
        register_rest_route('aqualuxe/v1', '/cart/remove', [
            'methods' => 'POST',
            'callback' => 'aqualuxe_rest_remove_from_cart',
            'permission_callback' => '__return_true',
        ]);
        
        register_rest_route('aqualuxe/v1', '/cart/update', [
            'methods' => 'POST',
            'callback' => 'aqualuxe_rest_update_cart',
            'permission_callback' => '__return_true',
        ]);
    }
}
add_action('rest_api_init', 'aqualuxe_register_rest_routes');

/**
 * Get product data
 *
 * @param WP_REST_Request $request Request object
 * @return WP_REST_Response|WP_Error
 */
function aqualuxe_rest_get_product($request) {
    // Check if WooCommerce is active
    if (!aqualuxe_is_woocommerce_active()) {
        return new WP_Error('woocommerce_required', __('WooCommerce is required for this endpoint.', 'aqualuxe'), ['status' => 400]);
    }
    
    $product_id = $request->get_param('id');
    $product = wc_get_product($product_id);
    
    if (!$product) {
        return new WP_Error('product_not_found', __('Product not found.', 'aqualuxe'), ['status' => 404]);
    }
    
    // Get quick view HTML
    ob_start();
    ?>
    <div class="quick-view-product">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="quick-view-images">
                <?php
                if ($product->get_image_id()) {
                    echo wp_get_attachment_image($product->get_image_id(), 'woocommerce_single', false, [
                        'class' => 'w-full h-auto rounded-lg',
                        'alt' => $product->get_name(),
                    ]);
                } else {
                    echo wc_placeholder_img([
                        'class' => 'w-full h-auto rounded-lg',
                        'alt' => $product->get_name(),
                    ]);
                }
                ?>
            </div>
            <div class="quick-view-summary">
                <h2 class="product-title text-2xl font-bold mb-2"><?php echo esc_html($product->get_name()); ?></h2>
                
                <?php if ($product->get_rating_count() > 0) : ?>
                <div class="product-rating flex items-center mb-3">
                    <?php echo wc_get_rating_html($product->get_average_rating(), $product->get_rating_count()); ?>
                    <span class="text-xs text-gray-600 dark:text-gray-400 ml-2">(<?php echo $product->get_rating_count(); ?>)</span>
                </div>
                <?php endif; ?>
                
                <div class="product-price text-xl font-bold text-primary mb-4">
                    <?php echo $product->get_price_html(); ?>
                </div>
                
                <div class="product-description mb-4">
                    <?php echo wp_kses_post($product->get_short_description()); ?>
                </div>
                
                <?php if ($product->is_in_stock()) : ?>
                <div class="stock-status mb-4 flex items-center text-sm text-green-600 dark:text-green-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span><?php esc_html_e('In Stock', 'aqualuxe'); ?></span>
                </div>
                <?php else : ?>
                <div class="stock-status mb-4 flex items-center text-sm text-red-600 dark:text-red-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    <span><?php esc_html_e('Out of Stock', 'aqualuxe'); ?></span>
                </div>
                <?php endif; ?>
                
                <?php if ($product->is_type('simple') && $product->is_purchasable() && $product->is_in_stock()) : ?>
                <form class="cart" action="<?php echo esc_url(apply_filters('woocommerce_add_to_cart_form_action', $product->get_permalink())); ?>" method="post" enctype="multipart/form-data">
                    <div class="quantity mb-4">
                        <label for="quantity" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"><?php esc_html_e('Quantity', 'aqualuxe'); ?></label>
                        <div class="flex">
                            <button type="button" class="quantity-button minus bg-gray-200 dark:bg-gray-700 px-3 py-1 rounded-l">-</button>
                            <input type="number" id="quantity" name="quantity" value="1" min="1" max="<?php echo esc_attr($product->get_max_purchase_quantity()); ?>" class="w-16 text-center border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                            <button type="button" class="quantity-button plus bg-gray-200 dark:bg-gray-700 px-3 py-1 rounded-r">+</button>
                        </div>
                    </div>
                    
                    <button type="submit" name="add-to-cart" value="<?php echo esc_attr($product->get_id()); ?>" class="btn btn-primary w-full"><?php esc_html_e('Add to Cart', 'aqualuxe'); ?></button>
                </form>
                <?php elseif ($product->is_type('variable')) : ?>
                <a href="<?php echo esc_url($product->get_permalink()); ?>" class="btn btn-primary w-full"><?php esc_html_e('Select Options', 'aqualuxe'); ?></a>
                <?php else : ?>
                <a href="<?php echo esc_url($product->get_permalink()); ?>" class="btn btn-primary w-full"><?php esc_html_e('View Product', 'aqualuxe'); ?></a>
                <?php endif; ?>
                
                <div class="quick-view-actions flex justify-between mt-4">
                    <a href="<?php echo esc_url($product->get_permalink()); ?>" class="text-primary hover:text-primary-dark transition-colors duration-300"><?php esc_html_e('View Details', 'aqualuxe'); ?></a>
                    
                    <?php if (get_theme_mod('aqualuxe_wishlist', true)) : ?>
                    <button class="wishlist-button flex items-center text-gray-600 dark:text-gray-400 hover:text-primary dark:hover:text-primary-light transition-colors duration-300 <?php echo aqualuxe_is_in_wishlist($product->get_id()) ? 'wishlist-active text-primary' : ''; ?>" data-product-id="<?php echo esc_attr($product->get_id()); ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="<?php echo aqualuxe_is_in_wishlist($product->get_id()) ? 'currentColor' : 'none'; ?>" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                        <span><?php esc_html_e('Add to Wishlist', 'aqualuxe'); ?></span>
                    </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <?php
    $html = ob_get_clean();
    
    // Get product data
    $data = [
        'id' => $product->get_id(),
        'name' => $product->get_name(),
        'permalink' => $product->get_permalink(),
        'price_html' => $product->get_price_html(),
        'price' => $product->get_price(),
        'regular_price' => $product->get_regular_price(),
        'sale_price' => $product->get_sale_price(),
        'on_sale' => $product->is_on_sale(),
        'stock_status' => $product->get_stock_status(),
        'stock_quantity' => $product->get_stock_quantity(),
        'rating' => $product->get_average_rating(),
        'rating_count' => $product->get_rating_count(),
        'short_description' => $product->get_short_description(),
        'description' => $product->get_description(),
        'categories' => wp_get_post_terms($product->get_id(), 'product_cat', ['fields' => 'names']),
        'tags' => wp_get_post_terms($product->get_id(), 'product_tag', ['fields' => 'names']),
        'attributes' => aqualuxe_get_product_attributes($product->get_id()),
        'images' => aqualuxe_get_product_gallery_images($product->get_id()),
        'in_wishlist' => aqualuxe_is_in_wishlist($product->get_id()),
        'html' => $html,
    ];
    
    return rest_ensure_response($data);
}

/**
 * Update wishlist
 *
 * @param WP_REST_Request $request Request object
 * @return WP_REST_Response|WP_Error
 */
function aqualuxe_rest_update_wishlist($request) {
    // Check if user is logged in
    if (!is_user_logged_in()) {
        return new WP_Error('not_logged_in', __('Please log in to add items to your wishlist.', 'aqualuxe'), ['status' => 401]);
    }
    
    $params = $request->get_json_params();
    
    // Check product ID
    if (!isset($params['product_id']) || empty($params['product_id'])) {
        return new WP_Error('no_product', __('No product selected.', 'aqualuxe'), ['status' => 400]);
    }
    
    $product_id = absint($params['product_id']);
    $action = isset($params['action']) ? sanitize_text_field($params['action']) : 'toggle';
    
    if ('add' === $action) {
        $result = aqualuxe_add_to_wishlist($product_id);
        $message = __('Product added to wishlist.', 'aqualuxe');
    } elseif ('remove' === $action) {
        $result = aqualuxe_remove_from_wishlist($product_id);
        $message = __('Product removed from wishlist.', 'aqualuxe');
    } else {
        // Toggle
        if (aqualuxe_is_in_wishlist($product_id)) {
            $result = aqualuxe_remove_from_wishlist($product_id);
            $message = __('Product removed from wishlist.', 'aqualuxe');
            $status = 'removed';
        } else {
            $result = aqualuxe_add_to_wishlist($product_id);
            $message = __('Product added to wishlist.', 'aqualuxe');
            $status = 'added';
        }
    }
    
    if ($result) {
        return rest_ensure_response([
            'success' => true,
            'message' => $message,
            'count' => aqualuxe_get_wishlist_count(),
            'status' => isset($status) ? $status : $action,
        ]);
    } else {
        return new WP_Error('update_failed', __('Failed to update wishlist.', 'aqualuxe'), ['status' => 500]);
    }
}

/**
 * Subscribe to newsletter
 *
 * @param WP_REST_Request $request Request object
 * @return WP_REST_Response|WP_Error
 */
function aqualuxe_rest_newsletter_subscribe($request) {
    $params = $request->get_json_params();
    
    // Check email
    if (!isset($params['email']) || empty($params['email'])) {
        return new WP_Error('no_email', __('No email provided.', 'aqualuxe'), ['status' => 400]);
    }
    
    $email = sanitize_email($params['email']);
    
    if (!is_email($email)) {
        return new WP_Error('invalid_email', __('Invalid email address.', 'aqualuxe'), ['status' => 400]);
    }
    
    // Store email in database
    $subscribers = get_option('aqualuxe_newsletter_subscribers', []);
    
    if (in_array($email, $subscribers)) {
        return rest_ensure_response([
            'success' => false,
            'message' => __('This email is already subscribed.', 'aqualuxe'),
        ]);
    }
    
    $subscribers[] = $email;
    update_option('aqualuxe_newsletter_subscribers', $subscribers);
    
    // Send notification email to admin
    $admin_email = get_option('admin_email');
    $subject = sprintf(__('New Newsletter Subscription on %s', 'aqualuxe'), get_bloginfo('name'));
    $message = sprintf(__('A new user has subscribed to your newsletter with the email: %s', 'aqualuxe'), $email);
    
    wp_mail($admin_email, $subject, $message);
    
    return rest_ensure_response([
        'success' => true,
        'message' => __('Thank you for subscribing to our newsletter!', 'aqualuxe'),
    ]);
}

/**
 * Search content
 *
 * @param WP_REST_Request $request Request object
 * @return WP_REST_Response|WP_Error
 */
function aqualuxe_rest_search($request) {
    $search_query = $request->get_param('query');
    
    if (empty($search_query)) {
        return new WP_Error('no_query', __('No search query provided.', 'aqualuxe'), ['status' => 400]);
    }
    
    $post_types = ['post', 'page'];
    
    // Add product post type if WooCommerce is active
    if (aqualuxe_is_woocommerce_active()) {
        $post_types[] = 'product';
    }
    
    $args = [
        's' => $search_query,
        'post_type' => $post_types,
        'posts_per_page' => 10,
    ];
    
    $search_results = new WP_Query($args);
    $results = [];
    
    if ($search_results->have_posts()) {
        while ($search_results->have_posts()) {
            $search_results->the_post();
            
            $post_type = get_post_type();
            $post_id = get_the_ID();
            
            $result = [
                'id' => $post_id,
                'title' => get_the_title(),
                'url' => get_permalink(),
                'type' => $post_type,
                'excerpt' => get_the_excerpt(),
            ];
            
            // Add featured image if available
            if (has_post_thumbnail()) {
                $result['image'] = get_the_post_thumbnail_url($post_id, 'thumbnail');
            }
            
            // Add product specific data
            if ('product' === $post_type && aqualuxe_is_woocommerce_active()) {
                $product = wc_get_product($post_id);
                
                if ($product) {
                    $result['price_html'] = $product->get_price_html();
                    $result['on_sale'] = $product->is_on_sale();
                    $result['stock_status'] = $product->get_stock_status();
                }
            }
            
            $results[] = $result;
        }
        
        wp_reset_postdata();
    }
    
    return rest_ensure_response([
        'success' => true,
        'query' => $search_query,
        'results' => $results,
        'count' => count($results),
    ]);
}

/**
 * Process contact form
 *
 * @param WP_REST_Request $request Request object
 * @return WP_REST_Response|WP_Error
 */
function aqualuxe_rest_contact_form($request) {
    $params = $request->get_json_params();
    
    // Check required fields
    $required_fields = ['name', 'email', 'message'];
    
    foreach ($required_fields as $field) {
        if (!isset($params[$field]) || empty($params[$field])) {
            return new WP_Error('missing_field', sprintf(__('Please fill in the %s field.', 'aqualuxe'), $field), ['status' => 400]);
        }
    }
    
    // Sanitize input
    $name = sanitize_text_field($params['name']);
    $email = sanitize_email($params['email']);
    $subject = isset($params['subject']) ? sanitize_text_field($params['subject']) : __('New Contact Form Submission', 'aqualuxe');
    $message = sanitize_textarea_field($params['message']);
    
    // Validate email
    if (!is_email($email)) {
        return new WP_Error('invalid_email', __('Please enter a valid email address.', 'aqualuxe'), ['status' => 400]);
    }
    
    // Check for spam
    if (isset($params['honeypot']) && !empty($params['honeypot'])) {
        return rest_ensure_response([
            'success' => true,
            'message' => __('Thank you for your message. We will get back to you soon.', 'aqualuxe'),
        ]);
    }
    
    // Send email
    $to = get_option('admin_email');
    $headers = [
        'Content-Type: text/html; charset=UTF-8',
        'From: ' . $name . ' <' . $email . '>',
        'Reply-To: ' . $email,
    ];
    
    $email_subject = sprintf(__('[%s] %s', 'aqualuxe'), get_bloginfo('name'), $subject);
    
    $email_message = '<p>' . __('You have received a new contact form submission:', 'aqualuxe') . '</p>';
    $email_message .= '<p><strong>' . __('Name:', 'aqualuxe') . '</strong> ' . $name . '</p>';
    $email_message .= '<p><strong>' . __('Email:', 'aqualuxe') . '</strong> ' . $email . '</p>';
    
    if (isset($params['phone']) && !empty($params['phone'])) {
        $phone = sanitize_text_field($params['phone']);
        $email_message .= '<p><strong>' . __('Phone:', 'aqualuxe') . '</strong> ' . $phone . '</p>';
    }
    
    $email_message .= '<p><strong>' . __('Message:', 'aqualuxe') . '</strong></p>';
    $email_message .= '<p>' . nl2br($message) . '</p>';
    
    $sent = wp_mail($to, $email_subject, $email_message, $headers);
    
    if ($sent) {
        return rest_ensure_response([
            'success' => true,
            'message' => __('Thank you for your message. We will get back to you soon.', 'aqualuxe'),
        ]);
    } else {
        return new WP_Error('email_failed', __('Failed to send your message. Please try again later.', 'aqualuxe'), ['status' => 500]);
    }
}

/**
 * Get theme options
 *
 * @param WP_REST_Request $request Request object
 * @return WP_REST_Response|WP_Error
 */
function aqualuxe_rest_theme_options($request) {
    // Check user permissions
    if (!current_user_can('edit_theme_options')) {
        return new WP_Error('permission_denied', __('You do not have permission to access theme options.', 'aqualuxe'), ['status' => 403]);
    }
    
    $options = [
        'primary_color' => get_theme_mod('aqualuxe_primary_color', '#0077B6'),
        'secondary_color' => get_theme_mod('aqualuxe_secondary_color', '#00B4D8'),
        'accent_color' => get_theme_mod('aqualuxe_accent_color', '#FFD166'),
        'dark_bg_color' => get_theme_mod('aqualuxe_dark_bg_color', '#0A192F'),
        'body_font' => get_theme_mod('aqualuxe_body_font', 'Montserrat'),
        'heading_font' => get_theme_mod('aqualuxe_heading_font', 'Playfair Display'),
        'base_font_size' => get_theme_mod('aqualuxe_base_font_size', '16'),
        'container_width' => get_theme_mod('aqualuxe_container_width', '1280'),
        'sidebar_position' => get_theme_mod('aqualuxe_sidebar_position', 'right'),
        'blog_layout' => get_theme_mod('aqualuxe_blog_layout', 'grid'),
        'shop_layout' => get_theme_mod('aqualuxe_shop_layout', 'grid'),
        'shop_columns' => get_theme_mod('aqualuxe_shop_columns', '3'),
        'sticky_header' => get_theme_mod('aqualuxe_sticky_header', true),
        'transparent_header' => get_theme_mod('aqualuxe_transparent_header', false),
        'header_style' => get_theme_mod('aqualuxe_header_style', 'default'),
        'footer_style' => get_theme_mod('aqualuxe_footer_style', 'default'),
        'enable_dark_mode' => get_theme_mod('aqualuxe_enable_dark_mode', true),
        'default_dark_mode' => get_theme_mod('aqualuxe_default_dark_mode', false),
        'language_switcher' => get_theme_mod('aqualuxe_language_switcher', true),
        'rtl_support' => get_theme_mod('aqualuxe_rtl_support', true),
        'lazy_loading' => get_theme_mod('aqualuxe_lazy_loading', true),
        'preload_fonts' => get_theme_mod('aqualuxe_preload_fonts', true),
        'minify_assets' => get_theme_mod('aqualuxe_minify_assets', true),
    ];
    
    // Add WooCommerce specific options
    if (aqualuxe_is_woocommerce_active()) {
        $woocommerce_options = [
            'quick_view' => get_theme_mod('aqualuxe_quick_view', true),
            'wishlist' => get_theme_mod('aqualuxe_wishlist', true),
            'product_hover' => get_theme_mod('aqualuxe_product_hover', 'zoom'),
            'product_card' => get_theme_mod('aqualuxe_product_card', 'default'),
            'sale_badge' => get_theme_mod('aqualuxe_sale_badge', 'circle'),
            'cart_icon' => get_theme_mod('aqualuxe_cart_icon', true),
            'mini_cart' => get_theme_mod('aqualuxe_mini_cart', true),
            'product_zoom' => get_theme_mod('aqualuxe_product_zoom', true),
            'product_lightbox' => get_theme_mod('aqualuxe_product_lightbox', true),
            'product_slider' => get_theme_mod('aqualuxe_product_slider', true),
        ];
        
        $options = array_merge($options, $woocommerce_options);
    }
    
    return rest_ensure_response($options);
}

/**
 * Get menu by location
 *
 * @param WP_REST_Request $request Request object
 * @return WP_REST_Response|WP_Error
 */
function aqualuxe_rest_get_menu($request) {
    $location = $request->get_param('location');
    
    if (empty($location)) {
        return new WP_Error('no_location', __('No menu location provided.', 'aqualuxe'), ['status' => 400]);
    }
    
    $locations = get_nav_menu_locations();
    
    if (!isset($locations[$location])) {
        return new WP_Error('location_not_found', __('Menu location not found.', 'aqualuxe'), ['status' => 404]);
    }
    
    $menu_id = $locations[$location];
    $menu_items = wp_get_nav_menu_items($menu_id);
    
    if (!$menu_items) {
        return new WP_Error('menu_not_found', __('Menu not found.', 'aqualuxe'), ['status' => 404]);
    }
    
    $menu_data = [];
    $menu_item_parents = [];
    
    // Build menu items hierarchy
    foreach ($menu_items as $menu_item) {
        $menu_item_data = [
            'id' => $menu_item->ID,
            'title' => $menu_item->title,
            'url' => $menu_item->url,
            'target' => $menu_item->target,
            'classes' => $menu_item->classes,
            'description' => $menu_item->description,
            'parent' => $menu_item->menu_item_parent,
            'children' => [],
        ];
        
        if ($menu_item->menu_item_parent) {
            $menu_item_parents[$menu_item->menu_item_parent][] = $menu_item_data;
        } else {
            $menu_data[] = $menu_item_data;
        }
    }
    
    // Add children to parent menu items
    $menu_data = aqualuxe_add_menu_item_children($menu_data, $menu_item_parents);
    
    return rest_ensure_response([
        'location' => $location,
        'menu_id' => $menu_id,
        'items' => $menu_data,
    ]);
}

/**
 * Add children to menu items
 *
 * @param array $menu_items Menu items
 * @param array $menu_item_parents Menu item parents
 * @return array
 */
function aqualuxe_add_menu_item_children($menu_items, $menu_item_parents) {
    foreach ($menu_items as &$menu_item) {
        if (isset($menu_item_parents[$menu_item['id']])) {
            $menu_item['children'] = $menu_item_parents[$menu_item['id']];
            $menu_item['children'] = aqualuxe_add_menu_item_children($menu_item['children'], $menu_item_parents);
        }
    }
    
    return $menu_items;
}

/**
 * Get cart
 *
 * @param WP_REST_Request $request Request object
 * @return WP_REST_Response|WP_Error
 */
function aqualuxe_rest_get_cart($request) {
    // Check if WooCommerce is active
    if (!aqualuxe_is_woocommerce_active()) {
        return new WP_Error('woocommerce_required', __('WooCommerce is required for this endpoint.', 'aqualuxe'), ['status' => 400]);
    }
    
    $cart_data = [
        'items' => [],
        'item_count' => WC()->cart->get_cart_contents_count(),
        'subtotal' => WC()->cart->get_cart_subtotal(),
        'subtotal_raw' => WC()->cart->get_subtotal(),
        'total' => WC()->cart->get_cart_total(),
        'total_raw' => WC()->cart->get_cart_contents_total(),
        'tax_total' => WC()->cart->get_cart_tax(),
        'tax_total_raw' => WC()->cart->get_cart_tax(),
        'shipping_total' => WC()->cart->get_shipping_total(),
        'shipping_total_raw' => WC()->cart->get_shipping_total(),
        'discount_total' => WC()->cart->get_discount_total(),
        'discount_total_raw' => WC()->cart->get_discount_total(),
        'cart_url' => wc_get_cart_url(),
        'checkout_url' => wc_get_checkout_url(),
    ];
    
    // Get cart items
    foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
        $product = $cart_item['data'];
        $product_id = $cart_item['product_id'];
        
        $item_data = [
            'key' => $cart_item_key,
            'product_id' => $product_id,
            'variation_id' => $cart_item['variation_id'],
            'variation' => $cart_item['variation'],
            'quantity' => $cart_item['quantity'],
            'name' => $product->get_name(),
            'price' => $product->get_price(),
            'price_html' => $product->get_price_html(),
            'subtotal' => WC()->cart->get_product_subtotal($product, $cart_item['quantity']),
            'link' => $product->get_permalink(),
            'image' => wp_get_attachment_image_src(get_post_thumbnail_id($product_id), 'thumbnail'),
            'attributes' => [],
        ];
        
        // Get variation attributes
        if ($cart_item['variation_id']) {
            $variation = wc_get_product($cart_item['variation_id']);
            
            if ($variation) {
                $item_data['attributes'] = $variation->get_variation_attributes();
            }
        }
        
        $cart_data['items'][] = $item_data;
    }
    
    return rest_ensure_response($cart_data);
}

/**
 * Add to cart
 *
 * @param WP_REST_Request $request Request object
 * @return WP_REST_Response|WP_Error
 */
function aqualuxe_rest_add_to_cart($request) {
    // Check if WooCommerce is active
    if (!aqualuxe_is_woocommerce_active()) {
        return new WP_Error('woocommerce_required', __('WooCommerce is required for this endpoint.', 'aqualuxe'), ['status' => 400]);
    }
    
    $params = $request->get_json_params();
    
    // Check product ID
    if (!isset($params['product_id']) || empty($params['product_id'])) {
        return new WP_Error('no_product', __('No product selected.', 'aqualuxe'), ['status' => 400]);
    }
    
    $product_id = absint($params['product_id']);
    $quantity = isset($params['quantity']) ? absint($params['quantity']) : 1;
    $variation_id = isset($params['variation_id']) ? absint($params['variation_id']) : 0;
    $variation = isset($params['variation']) ? $params['variation'] : [];
    
    // Add to cart
    $added = WC()->cart->add_to_cart($product_id, $quantity, $variation_id, $variation);
    
    if ($added) {
        // Get updated cart
        $cart_data = [
            'items' => [],
            'item_count' => WC()->cart->get_cart_contents_count(),
            'subtotal' => WC()->cart->get_cart_subtotal(),
            'subtotal_raw' => WC()->cart->get_subtotal(),
            'total' => WC()->cart->get_cart_total(),
            'total_raw' => WC()->cart->get_cart_contents_total(),
            'tax_total' => WC()->cart->get_cart_tax(),
            'tax_total_raw' => WC()->cart->get_cart_tax(),
            'shipping_total' => WC()->cart->get_shipping_total(),
            'shipping_total_raw' => WC()->cart->get_shipping_total(),
            'discount_total' => WC()->cart->get_discount_total(),
            'discount_total_raw' => WC()->cart->get_discount_total(),
            'cart_url' => wc_get_cart_url(),
            'checkout_url' => wc_get_checkout_url(),
        ];
        
        // Get cart items
        foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
            $product = $cart_item['data'];
            $product_id = $cart_item['product_id'];
            
            $item_data = [
                'key' => $cart_item_key,
                'product_id' => $product_id,
                'variation_id' => $cart_item['variation_id'],
                'variation' => $cart_item['variation'],
                'quantity' => $cart_item['quantity'],
                'name' => $product->get_name(),
                'price' => $product->get_price(),
                'price_html' => $product->get_price_html(),
                'subtotal' => WC()->cart->get_product_subtotal($product, $cart_item['quantity']),
                'link' => $product->get_permalink(),
                'image' => wp_get_attachment_image_src(get_post_thumbnail_id($product_id), 'thumbnail'),
                'attributes' => [],
            ];
            
            // Get variation attributes
            if ($cart_item['variation_id']) {
                $variation = wc_get_product($cart_item['variation_id']);
                
                if ($variation) {
                    $item_data['attributes'] = $variation->get_variation_attributes();
                }
            }
            
            $cart_data['items'][] = $item_data;
        }
        
        return rest_ensure_response([
            'success' => true,
            'message' => __('Product added to cart.', 'aqualuxe'),
            'cart' => $cart_data,
        ]);
    } else {
        return new WP_Error('add_to_cart_failed', __('Failed to add product to cart.', 'aqualuxe'), ['status' => 500]);
    }
}

/**
 * Remove from cart
 *
 * @param WP_REST_Request $request Request object
 * @return WP_REST_Response|WP_Error
 */
function aqualuxe_rest_remove_from_cart($request) {
    // Check if WooCommerce is active
    if (!aqualuxe_is_woocommerce_active()) {
        return new WP_Error('woocommerce_required', __('WooCommerce is required for this endpoint.', 'aqualuxe'), ['status' => 400]);
    }
    
    $params = $request->get_json_params();
    
    // Check cart item key
    if (!isset($params['cart_item_key']) || empty($params['cart_item_key'])) {
        return new WP_Error('no_cart_item_key', __('No cart item key provided.', 'aqualuxe'), ['status' => 400]);
    }
    
    $cart_item_key = sanitize_text_field($params['cart_item_key']);
    
    // Remove from cart
    $removed = WC()->cart->remove_cart_item($cart_item_key);
    
    if ($removed) {
        // Get updated cart
        $cart_data = [
            'items' => [],
            'item_count' => WC()->cart->get_cart_contents_count(),
            'subtotal' => WC()->cart->get_cart_subtotal(),
            'subtotal_raw' => WC()->cart->get_subtotal(),
            'total' => WC()->cart->get_cart_total(),
            'total_raw' => WC()->cart->get_cart_contents_total(),
            'tax_total' => WC()->cart->get_cart_tax(),
            'tax_total_raw' => WC()->cart->get_cart_tax(),
            'shipping_total' => WC()->cart->get_shipping_total(),
            'shipping_total_raw' => WC()->cart->get_shipping_total(),
            'discount_total' => WC()->cart->get_discount_total(),
            'discount_total_raw' => WC()->cart->get_discount_total(),
            'cart_url' => wc_get_cart_url(),
            'checkout_url' => wc_get_checkout_url(),
        ];
        
        // Get cart items
        foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
            $product = $cart_item['data'];
            $product_id = $cart_item['product_id'];
            
            $item_data = [
                'key' => $cart_item_key,
                'product_id' => $product_id,
                'variation_id' => $cart_item['variation_id'],
                'variation' => $cart_item['variation'],
                'quantity' => $cart_item['quantity'],
                'name' => $product->get_name(),
                'price' => $product->get_price(),
                'price_html' => $product->get_price_html(),
                'subtotal' => WC()->cart->get_product_subtotal($product, $cart_item['quantity']),
                'link' => $product->get_permalink(),
                'image' => wp_get_attachment_image_src(get_post_thumbnail_id($product_id), 'thumbnail'),
                'attributes' => [],
            ];
            
            // Get variation attributes
            if ($cart_item['variation_id']) {
                $variation = wc_get_product($cart_item['variation_id']);
                
                if ($variation) {
                    $item_data['attributes'] = $variation->get_variation_attributes();
                }
            }
            
            $cart_data['items'][] = $item_data;
        }
        
        return rest_ensure_response([
            'success' => true,
            'message' => __('Product removed from cart.', 'aqualuxe'),
            'cart' => $cart_data,
        ]);
    } else {
        return new WP_Error('remove_from_cart_failed', __('Failed to remove product from cart.', 'aqualuxe'), ['status' => 500]);
    }
}

/**
 * Update cart
 *
 * @param WP_REST_Request $request Request object
 * @return WP_REST_Response|WP_Error
 */
function aqualuxe_rest_update_cart($request) {
    // Check if WooCommerce is active
    if (!aqualuxe_is_woocommerce_active()) {
        return new WP_Error('woocommerce_required', __('WooCommerce is required for this endpoint.', 'aqualuxe'), ['status' => 400]);
    }
    
    $params = $request->get_json_params();
    
    // Check cart item key
    if (!isset($params['cart_item_key']) || empty($params['cart_item_key'])) {
        return new WP_Error('no_cart_item_key', __('No cart item key provided.', 'aqualuxe'), ['status' => 400]);
    }
    
    // Check quantity
    if (!isset($params['quantity']) || empty($params['quantity'])) {
        return new WP_Error('no_quantity', __('No quantity provided.', 'aqualuxe'), ['status' => 400]);
    }
    
    $cart_item_key = sanitize_text_field($params['cart_item_key']);
    $quantity = absint($params['quantity']);
    
    // Update cart
    $updated = WC()->cart->set_quantity($cart_item_key, $quantity);
    
    if ($updated) {
        // Get updated cart
        $cart_data = [
            'items' => [],
            'item_count' => WC()->cart->get_cart_contents_count(),
            'subtotal' => WC()->cart->get_cart_subtotal(),
            'subtotal_raw' => WC()->cart->get_subtotal(),
            'total' => WC()->cart->get_cart_total(),
            'total_raw' => WC()->cart->get_cart_contents_total(),
            'tax_total' => WC()->cart->get_cart_tax(),
            'tax_total_raw' => WC()->cart->get_cart_tax(),
            'shipping_total' => WC()->cart->get_shipping_total(),
            'shipping_total_raw' => WC()->cart->get_shipping_total(),
            'discount_total' => WC()->cart->get_discount_total(),
            'discount_total_raw' => WC()->cart->get_discount_total(),
            'cart_url' => wc_get_cart_url(),
            'checkout_url' => wc_get_checkout_url(),
        ];
        
        // Get cart items
        foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
            $product = $cart_item['data'];
            $product_id = $cart_item['product_id'];
            
            $item_data = [
                'key' => $cart_item_key,
                'product_id' => $product_id,
                'variation_id' => $cart_item['variation_id'],
                'variation' => $cart_item['variation'],
                'quantity' => $cart_item['quantity'],
                'name' => $product->get_name(),
                'price' => $product->get_price(),
                'price_html' => $product->get_price_html(),
                'subtotal' => WC()->cart->get_product_subtotal($product, $cart_item['quantity']),
                'link' => $product->get_permalink(),
                'image' => wp_get_attachment_image_src(get_post_thumbnail_id($product_id), 'thumbnail'),
                'attributes' => [],
            ];
            
            // Get variation attributes
            if ($cart_item['variation_id']) {
                $variation = wc_get_product($cart_item['variation_id']);
                
                if ($variation) {
                    $item_data['attributes'] = $variation->get_variation_attributes();
                }
            }
            
            $cart_data['items'][] = $item_data;
        }
        
        return rest_ensure_response([
            'success' => true,
            'message' => __('Cart updated.', 'aqualuxe'),
            'cart' => $cart_data,
        ]);
    } else {
        return new WP_Error('update_cart_failed', __('Failed to update cart.', 'aqualuxe'), ['status' => 500]);
    }
}