<?php
/**
 * AquaLuxe Template Functions
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Display site logo
 */
function aqualuxe_site_logo() {
    if (has_custom_logo()) {
        the_custom_logo();
    } else {
        echo '<a href="' . esc_url(home_url('/')) . '" class="custom-logo-link" rel="home" aria-current="page">';
        echo '<img src="' . esc_url(get_theme_mod('aqualuxe_logo', '')) . '" class="custom-logo" alt="' . esc_attr(get_bloginfo('name')) . '" />';
        echo '</a>';
    }
}

/**
 * Display site title
 */
function aqualuxe_site_title() {
    echo '<h1 class="site-title"><a href="' . esc_url(home_url('/')) . '" rel="home">' . esc_html(get_bloginfo('name')) . '</a></h1>';
}

/**
 * Display site description
 */
function aqualuxe_site_description() {
    echo '<p class="site-description">' . esc_html(get_bloginfo('description')) . '</p>';
}

/**
 * Display primary navigation
 */
function aqualuxe_primary_navigation() {
    wp_nav_menu(array(
        'theme_location' => 'primary',
        'menu_class' => 'primary-menu',
        'container' => 'nav',
        'container_class' => 'primary-navigation',
        'fallback_cb' => 'aqualuxe_menu_fallback',
    ));
}

/**
 * Display secondary navigation
 */
function aqualuxe_secondary_navigation() {
    wp_nav_menu(array(
        'theme_location' => 'secondary',
        'menu_class' => 'secondary-menu',
        'container' => 'nav',
        'container_class' => 'secondary-navigation',
        'fallback_cb' => 'aqualuxe_menu_fallback',
    ));
}

/**
 * Display handheld navigation
 */
function aqualuxe_handheld_navigation() {
    wp_nav_menu(array(
        'theme_location' => 'handheld',
        'menu_class' => 'handheld-menu',
        'container' => 'nav',
        'container_class' => 'handheld-navigation',
        'fallback_cb' => 'aqualuxe_menu_fallback',
    ));
}

/**
 * Menu fallback
 */
function aqualuxe_menu_fallback() {
    echo '<ul class="menu">';
    wp_list_pages(array(
        'title_li' => '',
        'depth' => 1,
    ));
    echo '</ul>';
}

/**
 * Display breadcrumbs
 */
function aqualuxe_breadcrumbs() {
    if (!is_front_page()) {
        woocommerce_breadcrumb();
    }
}

/**
 * Display product search form
 */
function aqualuxe_product_search_form() {
    $form = '<form role="search" method="get" class="woocommerce-product-search" action="' . esc_url(home_url('/')) . '">';
    $form .= '<label class="screen-reader-text" for="woocommerce-product-search-field-' . uniqid() . '">' . __('Search for:', 'aqualuxe') . '</label>';
    $form .= '<input type="search" id="woocommerce-product-search-field-' . uniqid() . '" class="search-field" placeholder="' . esc_attr__('Search products&hellip;', 'aqualuxe') . '" value="' . get_search_query() . '" name="s" />';
    $form .= '<button type="submit" value="' . esc_attr_x('Search', 'submit button', 'aqualuxe') . '"><i class="fa fa-search"></i></button>';
    $form .= '<input type="hidden" name="post_type" value="product" />';
    $form .= '</form>';
    
    echo $form;
}

/**
 * Display quick view button
 */
function aqualuxe_quick_view_button($product_id) {
    echo '<a href="#" class="button quick-view-button" data-product-id="' . esc_attr($product_id) . '">' . __('Quick View', 'aqualuxe') . '</a>';
}

/**
 * Display wishlist button
 */
function aqualuxe_wishlist_button($product_id) {
    $in_wishlist = false;
    
    if (is_user_logged_in()) {
        $user_id = get_current_user_id();
        $wishlist = get_user_meta($user_id, 'aqualuxe_wishlist', true);
        
        if ($wishlist && in_array($product_id, $wishlist)) {
            $in_wishlist = true;
        }
    }
    
    $class = $in_wishlist ? 'in-wishlist' : '';
    $text = $in_wishlist ? __('Remove from Wishlist', 'aqualuxe') : __('Add to Wishlist', 'aqualuxe');
    
    echo '<a href="#" class="button wishlist-button ' . esc_attr($class) . '" data-product-id="' . esc_attr($product_id) . '">' . esc_html($text) . '</a>';
}

/**
 * Display newsletter signup form
 */
function aqualuxe_newsletter_signup_form() {
    echo '<form class="newsletter-form" method="post">';
    echo '<input type="email" name="email" placeholder="' . esc_attr__('Enter your email address', 'aqualuxe') . '" required />';
    echo '<button type="submit">' . __('Subscribe', 'aqualuxe') . '</button>';
    echo wp_nonce_field('aqualuxe_nonce', 'aqualuxe_nonce_field', false, false);
    echo '</form>';
}

/**
 * Display contact form
 */
function aqualuxe_contact_form() {
    echo '<form class="contact-form" method="post">';
    echo '<p><input type="text" name="name" placeholder="' . esc_attr__('Name', 'aqualuxe') . '" required /></p>';
    echo '<p><input type="email" name="email" placeholder="' . esc_attr__('Email', 'aqualuxe') . '" required /></p>';
    echo '<p><input type="text" name="subject" placeholder="' . esc_attr__('Subject', 'aqualuxe') . '" required /></p>';
    echo '<p><textarea name="message" placeholder="' . esc_attr__('Message', 'aqualuxe') . '" required></textarea></p>';
    echo '<p><button type="submit">' . __('Send Message', 'aqualuxe') . '</button></p>';
    echo wp_nonce_field('aqualuxe_nonce', 'aqualuxe_nonce_field', false, false);
    echo '</form>';
}

/**
 * Display social media links
 */
function aqualuxe_social_media_links() {
    $social_links = get_theme_mod('aqualuxe_social_links', array());
    
    if (!empty($social_links)) {
        echo '<div class="social-media-links">';
        foreach ($social_links as $social_link) {
            echo '<a href="' . esc_url($social_link['url']) . '" target="_blank" rel="noopener noreferrer">';
            echo '<i class="fa fa-' . esc_attr($social_link['icon']) . '"></i>';
            echo '</a>';
        }
        echo '</div>';
    }
}

/**
 * Display copyright text
 */
function aqualuxe_copyright_text() {
    $copyright = get_theme_mod('aqualuxe_copyright_text', '');
    
    if (empty($copyright)) {
        $copyright = '&copy; ' . date('Y') . ' ' . get_bloginfo('name') . '. ' . __('All rights reserved.', 'aqualuxe');
    }
    
    echo wp_kses_post($copyright);
}

/**
 * Display back to top button
 */
function aqualuxe_back_to_top_button() {
    echo '<a href="#" class="back-to-top" title="' . esc_attr__('Back to Top', 'aqualuxe') . '"><i class="fa fa-arrow-up"></i></a>';
}

/**
 * Display sticky header
 */
function aqualuxe_sticky_header() {
    echo '<div class="sticky-header">';
    aqualuxe_site_logo();
    aqualuxe_primary_navigation();
    aqualuxe_product_search_form();
    echo '</div>';
}

/**
 * Display product filter
 */
function aqualuxe_product_filter() {
    echo '<div class="product-filter">';
    echo '<form class="filter-form" method="post">';
    
    // Category filter
    $categories = get_terms(array(
        'taxonomy' => 'product_cat',
        'hide_empty' => true,
    ));
    
    if (!empty($categories) && !is_wp_error($categories)) {
        echo '<select name="category">';
        echo '<option value="">' . __('All Categories', 'aqualuxe') . '</option>';
        foreach ($categories as $category) {
            echo '<option value="' . esc_attr($category->slug) . '">' . esc_html($category->name) . '</option>';
        }
        echo '</select>';
    }
    
    // Price filter
    echo '<input type="number" name="min_price" placeholder="' . esc_attr__('Min Price', 'aqualuxe') . '" />';
    echo '<input type="number" name="max_price" placeholder="' . esc_attr__('Max Price', 'aqualuxe') . '" />';
    
    // Sort by
    echo '<select name="orderby">';
    echo '<option value="menu_order">' . __('Default Sorting', 'aqualuxe') . '</option>';
    echo '<option value="popularity">' . __('Sort by Popularity', 'aqualuxe') . '</option>';
    echo '<option value="rating">' . __('Sort by Rating', 'aqualuxe') . '</option>';
    echo '<option value="date">' . __('Sort by Newness', 'aqualuxe') . '</option>';
    echo '<option value="price">' . __('Sort by Price: Low to High', 'aqualuxe') . '</option>';
    echo '<option value="price-desc">' . __('Sort by Price: High to Low', 'aqualuxe') . '</option>';
    echo '</select>';
    
    echo '<button type="submit">' . __('Filter', 'aqualuxe') . '</button>';
    echo wp_nonce_field('aqualuxe_nonce', 'aqualuxe_nonce_field', false, false);
    echo '</form>';
    echo '</div>';
}

/**
 * Display product grid/list toggle
 */
function aqualuxe_product_view_toggle() {
    echo '<div class="product-view-toggle">';
    echo '<a href="#" class="grid-view active" title="' . esc_attr__('Grid View', 'aqualuxe') . '"><i class="fa fa-th"></i></a>';
    echo '<a href="#" class="list-view" title="' . esc_attr__('List View', 'aqualuxe') . '"><i class="fa fa-list"></i></a>';
    echo '</div>';
}

/**
 * Display product pagination
 */
function aqualuxe_product_pagination() {
    global $wp_query;
    
    $big = 999999999; // need an unlikely integer
    
    echo paginate_links(array(
        'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
        'format' => '?paged=%#%',
        'current' => max(1, get_query_var('paged')),
        'total' => $wp_query->max_num_pages,
        'prev_text' => '&laquo;',
        'next_text' => '&raquo;',
        'type' => 'list',
    ));
}

/**
 * Display related products
 */
function aqualuxe_related_products() {
    global $product;
    
    if (!$product) {
        return;
    }
    
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => 4,
        'post__not_in' => array($product->get_id()),
        'tax_query' => array(
            array(
                'taxonomy' => 'product_cat',
                'field' => 'term_id',
                'terms' => wp_get_post_terms($product->get_id(), 'product_cat', array('fields' => 'ids')),
            ),
        ),
    );
    
    $related_products = new WP_Query($args);
    
    if ($related_products->have_posts()) {
        echo '<div class="related-products">';
        echo '<h2>' . __('Related Products', 'aqualuxe') . '</h2>';
        echo '<ul class="products">';
        
        while ($related_products->have_posts()) {
            $related_products->the_post();
            wc_get_template_part('content', 'product');
        }
        
        echo '</ul>';
        echo '</div>';
        
        wp_reset_postdata();
    }
}

/**
 * Display upsell products
 */
function aqualuxe_upsell_products() {
    global $product;
    
    if (!$product) {
        return;
    }
    
    $upsells = $product->get_upsell_ids();
    
    if (empty($upsells)) {
        return;
    }
    
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => 4,
        'post__in' => $upsells,
    );
    
    $upsell_products = new WP_Query($args);
    
    if ($upsell_products->have_posts()) {
        echo '<div class="upsell-products">';
        echo '<h2>' . __('You May Also Like', 'aqualuxe') . '</h2>';
        echo '<ul class="products">';
        
        while ($upsell_products->have_posts()) {
            $upsell_products->the_post();
            wc_get_template_part('content', 'product');
        }
        
        echo '</ul>';
        echo '</div>';
        
        wp_reset_postdata();
    }
}