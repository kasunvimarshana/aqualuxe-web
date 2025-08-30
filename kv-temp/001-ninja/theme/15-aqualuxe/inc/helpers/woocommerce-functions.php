<?php
/**
 * WooCommerce helper functions for AquaLuxe theme
 *
 * @package AquaLuxe
 */

if (!function_exists('aqualuxe_wc_cart_count')) {
    /**
     * Get the cart count for the header cart icon
     *
     * @return int Cart item count
     */
    function aqualuxe_wc_cart_count() {
        if (!function_exists('WC') || !isset(WC()->cart)) {
            return 0;
        }

        return WC()->cart->get_cart_contents_count();
    }
}

if (!function_exists('aqualuxe_wc_cart_total')) {
    /**
     * Get the cart total for the header cart
     *
     * @return string Formatted cart total
     */
    function aqualuxe_wc_cart_total() {
        if (!function_exists('WC') || !isset(WC()->cart)) {
            return '';
        }

        return WC()->cart->get_cart_total();
    }
}

if (!function_exists('aqualuxe_wc_header_cart')) {
    /**
     * Display the header cart icon and dropdown
     *
     * @param array $args Arguments for controlling the header cart display.
     * @return void
     */
    function aqualuxe_wc_header_cart($args = array()) {
        if (!function_exists('WC') || !isset(WC()->cart)) {
            return;
        }

        $defaults = array(
            'container'      => 'div',
            'container_class' => 'header-cart relative group',
            'icon_class'     => 'cart-icon flex items-center text-dark dark:text-light hover:text-primary dark:hover:text-secondary-light transition-colors duration-200',
            'count_class'    => 'cart-count absolute -top-1 -right-1 w-5 h-5 flex items-center justify-center bg-primary text-white text-xs rounded-full',
            'dropdown_class' => 'cart-dropdown absolute right-0 top-full mt-2 w-80 bg-white dark:bg-dark-light rounded-lg shadow-elegant z-50 hidden group-hover:block',
            'echo'           => true,
        );

        $args = wp_parse_args($args, $defaults);
        $html = '';

        $cart_count = aqualuxe_wc_cart_count();
        $cart_url = wc_get_cart_url();
        $checkout_url = wc_get_checkout_url();

        $html = sprintf(
            '<%1$s class="%2$s">
                <a href="%3$s" class="%4$s">
                    %5$s
                    <span class="%6$s">%7$s</span>
                </a>
                <div class="%8$s">
                    <div class="widget_shopping_cart_content">
                        %9$s
                    </div>
                </div>
            </%1$s>',
            $args['container'],
            esc_attr($args['container_class']),
            esc_url($cart_url),
            esc_attr($args['icon_class']),
            aqualuxe_get_svg('cart'),
            esc_attr($args['count_class']),
            esc_html($cart_count),
            esc_attr($args['dropdown_class']),
            aqualuxe_wc_mini_cart_content()
        );

        if ($args['echo']) {
            echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        } else {
            return $html;
        }
    }
}

if (!function_exists('aqualuxe_wc_mini_cart_content')) {
    /**
     * Get the mini cart content
     *
     * @return string Mini cart HTML
     */
    function aqualuxe_wc_mini_cart_content() {
        if (!function_exists('WC') || !isset(WC()->cart)) {
            return '';
        }

        ob_start();

        if (WC()->cart->is_empty()) {
            echo '<div class="empty-cart p-4 text-center">';
            echo '<p class="mb-4">' . esc_html__('Your cart is currently empty.', 'aqualuxe') . '</p>';
            echo '<a href="' . esc_url(wc_get_page_permalink('shop')) . '" class="btn btn-primary">' . esc_html__('Return to Shop', 'aqualuxe') . '</a>';
            echo '</div>';
        } else {
            echo '<div class="cart-items p-4 max-h-80 overflow-y-auto">';
            
            foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
                $_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
                $product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);

                if ($_product && $_product->exists() && $cart_item['quantity'] > 0) {
                    echo '<div class="cart-item flex items-center mb-4 pb-4 border-b border-light-darker dark:border-dark-lighter">';
                    
                    // Product image
                    echo '<div class="cart-item-image w-16 h-16 mr-4">';
                    echo '<a href="' . esc_url($_product->get_permalink()) . '">';
                    echo $_product->get_image(array(64, 64));
                    echo '</a>';
                    echo '</div>';
                    
                    // Product details
                    echo '<div class="cart-item-details flex-grow">';
                    echo '<h4 class="text-sm font-medium mb-1"><a href="' . esc_url($_product->get_permalink()) . '" class="text-dark dark:text-light hover:text-primary dark:hover:text-secondary-light transition-colors duration-200">' . $_product->get_name() . '</a></h4>';
                    echo '<div class="text-sm text-dark-light dark:text-light-dark">';
                    echo '<span class="quantity">' . esc_html($cart_item['quantity']) . ' × ' . apply_filters('woocommerce_cart_item_price', WC()->cart->get_product_price($_product), $cart_item, $cart_item_key) . '</span>';
                    echo '</div>';
                    echo '</div>';
                    
                    // Remove button
                    echo '<div class="cart-item-remove">';
                    echo apply_filters('woocommerce_cart_item_remove_link', sprintf(
                        '<a href="%s" class="remove text-dark-light dark:text-light-dark hover:text-primary dark:hover:text-secondary-light transition-colors duration-200" aria-label="%s" data-product_id="%s" data-product_sku="%s">%s</a>',
                        esc_url(wc_get_cart_remove_url($cart_item_key)),
                        esc_html__('Remove this item', 'aqualuxe'),
                        esc_attr($product_id),
                        esc_attr($_product->get_sku()),
                        aqualuxe_get_svg('close')
                    ), $cart_item_key);
                    echo '</div>';
                    
                    echo '</div>';
                }
            }
            
            echo '</div>';
            
            // Cart subtotal
            echo '<div class="cart-subtotal p-4 border-t border-light-darker dark:border-dark-lighter">';
            echo '<div class="flex justify-between items-center mb-4">';
            echo '<span class="font-medium">' . esc_html__('Subtotal', 'aqualuxe') . ':</span>';
            echo '<span class="font-bold text-primary dark:text-secondary-light">' . WC()->cart->get_cart_subtotal() . '</span>';
            echo '</div>';
            
            // Cart actions
            echo '<div class="cart-actions grid grid-cols-2 gap-2">';
            echo '<a href="' . esc_url(wc_get_cart_url()) . '" class="btn btn-outline text-sm">' . esc_html__('View Cart', 'aqualuxe') . '</a>';
            echo '<a href="' . esc_url(wc_get_checkout_url()) . '" class="btn btn-primary text-sm">' . esc_html__('Checkout', 'aqualuxe') . '</a>';
            echo '</div>';
            echo '</div>';
        }

        return ob_get_clean();
    }
}

if (!function_exists('aqualuxe_wc_product_categories_nav')) {
    /**
     * Display product categories navigation
     *
     * @param array $args Arguments for controlling the categories nav display.
     * @return void
     */
    function aqualuxe_wc_product_categories_nav($args = array()) {
        if (!function_exists('WC')) {
            return;
        }

        $defaults = array(
            'container'      => 'div',
            'container_class' => 'product-categories-nav mb-8',
            'title'          => __('Categories', 'aqualuxe'),
            'title_class'    => 'text-xl font-serif font-bold mb-4',
            'list_class'     => 'flex flex-wrap gap-2',
            'item_class'     => 'category-item',
            'link_class'     => 'block px-4 py-2 bg-light-dark dark:bg-dark-light rounded-md text-dark dark:text-light hover:bg-primary hover:text-white dark:hover:bg-secondary dark:hover:text-white transition-colors duration-200',
            'active_class'   => 'bg-primary text-white dark:bg-secondary',
            'show_count'     => true,
            'limit'          => 10,
            'echo'           => true,
        );

        $args = wp_parse_args($args, $defaults);
        $html = '';

        $product_categories = get_terms(array(
            'taxonomy'   => 'product_cat',
            'hide_empty' => true,
            'number'     => $args['limit'],
            'orderby'    => 'count',
            'order'      => 'DESC',
        ));

        if (!empty($product_categories) && !is_wp_error($product_categories)) {
            $items = '';
            $current_cat = get_queried_object_id();

            foreach ($product_categories as $category) {
                $is_active = ($current_cat === $category->term_id);
                $link_class = $is_active ? $args['link_class'] . ' ' . $args['active_class'] : $args['link_class'];
                
                $count_html = '';
                if ($args['show_count']) {
                    $count_html = sprintf(
                        '<span class="count ml-1 text-sm">(%s)</span>',
                        esc_html($category->count)
                    );
                }

                $items .= sprintf(
                    '<li class="%1$s">
                        <a href="%2$s" class="%3$s">%4$s%5$s</a>
                    </li>',
                    esc_attr($args['item_class']),
                    esc_url(get_term_link($category)),
                    esc_attr($link_class),
                    esc_html($category->name),
                    $count_html
                );
            }

            $title_html = '';
            if ($args['title']) {
                $title_html = sprintf(
                    '<h3 class="%1$s">%2$s</h3>',
                    esc_attr($args['title_class']),
                    esc_html($args['title'])
                );
            }

            $html = sprintf(
                '<%1$s class="%2$s">
                    %3$s
                    <ul class="%4$s">%5$s</ul>
                </%1$s>',
                $args['container'],
                esc_attr($args['container_class']),
                $title_html,
                esc_attr($args['list_class']),
                $items
            );
        }

        if ($args['echo']) {
            echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        } else {
            return $html;
        }
    }
}

if (!function_exists('aqualuxe_wc_product_filters')) {
    /**
     * Display product filters
     *
     * @param array $args Arguments for controlling the filters display.
     * @return void
     */
    function aqualuxe_wc_product_filters($args = array()) {
        if (!function_exists('WC')) {
            return;
        }

        $defaults = array(
            'container'      => 'div',
            'container_class' => 'product-filters mb-8',
            'title'          => __('Filter Products', 'aqualuxe'),
            'title_class'    => 'text-xl font-serif font-bold mb-4',
            'form_class'     => 'filter-form',
            'section_class'  => 'filter-section mb-6',
            'section_title_class' => 'text-lg font-medium mb-2',
            'list_class'     => 'space-y-2',
            'item_class'     => 'filter-item flex items-center',
            'label_class'    => 'ml-2 text-dark dark:text-light',
            'button_class'   => 'btn btn-primary mt-4',
            'echo'           => true,
        );

        $args = wp_parse_args($args, $defaults);
        $html = '';

        // Get attribute taxonomies
        $attribute_taxonomies = wc_get_attribute_taxonomies();
        
        if (!empty($attribute_taxonomies)) {
            $title_html = '';
            if ($args['title']) {
                $title_html = sprintf(
                    '<h3 class="%1$s">%2$s</h3>',
                    esc_attr($args['title_class']),
                    esc_html($args['title'])
                );
            }

            $form_html = sprintf(
                '<form class="%1$s" method="get" action="%2$s">',
                esc_attr($args['form_class']),
                esc_url(wc_get_page_permalink('shop'))
            );

            // Price filter
            $min_price = isset($_GET['min_price']) ? wc_clean(wp_unslash($_GET['min_price'])) : '';
            $max_price = isset($_GET['max_price']) ? wc_clean(wp_unslash($_GET['max_price'])) : '';

            $form_html .= sprintf(
                '<div class="%1$s">
                    <h4 class="%2$s">%3$s</h4>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label for="min_price" class="sr-only">%4$s</label>
                            <input type="number" id="min_price" name="min_price" value="%5$s" placeholder="%6$s" min="0" step="1" class="form-input">
                        </div>
                        <div>
                            <label for="max_price" class="sr-only">%7$s</label>
                            <input type="number" id="max_price" name="max_price" value="%8$s" placeholder="%9$s" min="0" step="1" class="form-input">
                        </div>
                    </div>
                </div>',
                esc_attr($args['section_class']),
                esc_attr($args['section_title_class']),
                esc_html__('Price', 'aqualuxe'),
                esc_html__('Min Price', 'aqualuxe'),
                esc_attr($min_price),
                esc_attr__('Min', 'aqualuxe'),
                esc_html__('Max Price', 'aqualuxe'),
                esc_attr($max_price),
                esc_attr__('Max', 'aqualuxe')
            );

            // Categories filter
            $product_categories = get_terms(array(
                'taxonomy'   => 'product_cat',
                'hide_empty' => true,
            ));

            if (!empty($product_categories) && !is_wp_error($product_categories)) {
                $form_html .= sprintf(
                    '<div class="%1$s">
                        <h4 class="%2$s">%3$s</h4>
                        <ul class="%4$s">',
                    esc_attr($args['section_class']),
                    esc_attr($args['section_title_class']),
                    esc_html__('Categories', 'aqualuxe'),
                    esc_attr($args['list_class'])
                );

                $selected_cats = isset($_GET['product_cat']) ? (array) wc_clean(wp_unslash($_GET['product_cat'])) : array();

                foreach ($product_categories as $category) {
                    $checked = in_array($category->slug, $selected_cats) ? 'checked' : '';
                    
                    $form_html .= sprintf(
                        '<li class="%1$s">
                            <input type="checkbox" id="product_cat_%2$s" name="product_cat[]" value="%3$s" %4$s>
                            <label for="product_cat_%2$s" class="%5$s">%6$s <span class="count">(%7$s)</span></label>
                        </li>',
                        esc_attr($args['item_class']),
                        esc_attr($category->term_id),
                        esc_attr($category->slug),
                        $checked,
                        esc_attr($args['label_class']),
                        esc_html($category->name),
                        esc_html($category->count)
                    );
                }

                $form_html .= '</ul></div>';
            }

            // Attribute filters
            foreach ($attribute_taxonomies as $attribute) {
                $taxonomy = wc_attribute_taxonomy_name($attribute->attribute_name);
                $terms = get_terms(array(
                    'taxonomy'   => $taxonomy,
                    'hide_empty' => true,
                ));

                if (!empty($terms) && !is_wp_error($terms)) {
                    $form_html .= sprintf(
                        '<div class="%1$s">
                            <h4 class="%2$s">%3$s</h4>
                            <ul class="%4$s">',
                        esc_attr($args['section_class']),
                        esc_attr($args['section_title_class']),
                        esc_html(wc_attribute_label($taxonomy)),
                        esc_attr($args['list_class'])
                    );

                    $filter_name = 'filter_' . wc_attribute_taxonomy_slug($attribute->attribute_name);
                    $selected_terms = isset($_GET[$filter_name]) ? (array) wc_clean(wp_unslash($_GET[$filter_name])) : array();

                    foreach ($terms as $term) {
                        $checked = in_array($term->slug, $selected_terms) ? 'checked' : '';
                        
                        $form_html .= sprintf(
                            '<li class="%1$s">
                                <input type="checkbox" id="%2$s_%3$s" name="%2$s[]" value="%4$s" %5$s>
                                <label for="%2$s_%3$s" class="%6$s">%7$s <span class="count">(%8$s)</span></label>
                            </li>',
                            esc_attr($args['item_class']),
                            esc_attr($filter_name),
                            esc_attr($term->term_id),
                            esc_attr($term->slug),
                            $checked,
                            esc_attr($args['label_class']),
                            esc_html($term->name),
                            esc_html($term->count)
                        );
                    }

                    $form_html .= '</ul></div>';
                }
            }

            // Submit button
            $form_html .= sprintf(
                '<button type="submit" class="%1$s">%2$s</button>',
                esc_attr($args['button_class']),
                esc_html__('Apply Filters', 'aqualuxe')
            );

            // Add hidden inputs for any other query args
            if (isset($_GET['s'])) {
                $form_html .= sprintf(
                    '<input type="hidden" name="s" value="%s">',
                    esc_attr($_GET['s'])
                );
            }

            if (isset($_GET['post_type'])) {
                $form_html .= sprintf(
                    '<input type="hidden" name="post_type" value="%s">',
                    esc_attr($_GET['post_type'])
                );
            }

            $form_html .= '</form>';

            $html = sprintf(
                '<%1$s class="%2$s" x-data="{ filtersOpen: window.innerWidth >= 768 }">
                    <div class="flex justify-between items-center mb-4">
                        %3$s
                        <button @click="filtersOpen = !filtersOpen" class="md:hidden text-dark dark:text-light hover:text-primary dark:hover:text-secondary-light transition-colors duration-200">
                            <span x-show="!filtersOpen">%4$s %5$s</span>
                            <span x-show="filtersOpen">%6$s %7$s</span>
                        </button>
                    </div>
                    <div x-show="filtersOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform -translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0">
                        %8$s
                    </div>
                </%1$s>',
                $args['container'],
                esc_attr($args['container_class']),
                $title_html,
                aqualuxe_get_svg('filter'),
                esc_html__('Show Filters', 'aqualuxe'),
                aqualuxe_get_svg('close'),
                esc_html__('Hide Filters', 'aqualuxe'),
                $form_html
            );
        }

        if ($args['echo']) {
            echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        } else {
            return $html;
        }
    }
}

if (!function_exists('aqualuxe_wc_product_sorting')) {
    /**
     * Display product sorting options
     *
     * @param array $args Arguments for controlling the sorting display.
     * @return void
     */
    function aqualuxe_wc_product_sorting($args = array()) {
        if (!function_exists('WC')) {
            return;
        }

        $defaults = array(
            'container'      => 'div',
            'container_class' => 'product-sorting mb-8',
            'label'          => __('Sort by:', 'aqualuxe'),
            'label_class'    => 'mr-2 text-dark dark:text-light',
            'select_class'   => 'form-select',
            'echo'           => true,
        );

        $args = wp_parse_args($args, $defaults);
        $html = '';

        $orderby_options = array(
            'menu_order' => __('Default sorting', 'aqualuxe'),
            'popularity' => __('Sort by popularity', 'aqualuxe'),
            'rating'     => __('Sort by average rating', 'aqualuxe'),
            'date'       => __('Sort by latest', 'aqualuxe'),
            'price'      => __('Sort by price: low to high', 'aqualuxe'),
            'price-desc' => __('Sort by price: high to low', 'aqualuxe'),
        );

        $current_orderby = isset($_GET['orderby']) ? wc_clean(wp_unslash($_GET['orderby'])) : 'menu_order';

        $form_html = sprintf(
            '<form method="get" action="%1$s" class="flex items-center">
                <label for="orderby" class="%2$s">%3$s</label>
                <select name="orderby" id="orderby" class="%4$s" onchange="this.form.submit()">',
            esc_url(wc_get_page_permalink('shop')),
            esc_attr($args['label_class']),
            esc_html($args['label']),
            esc_attr($args['select_class'])
        );

        foreach ($orderby_options as $id => $name) {
            $form_html .= sprintf(
                '<option value="%1$s" %2$s>%3$s</option>',
                esc_attr($id),
                selected($current_orderby, $id, false),
                esc_html($name)
            );
        }

        $form_html .= '</select>';

        // Add hidden inputs for any other query args
        foreach ($_GET as $key => $value) {
            if ('orderby' === $key || 'submit' === $key) {
                continue;
            }

            if (is_array($value)) {
                foreach ($value as $val) {
                    $form_html .= sprintf(
                        '<input type="hidden" name="%1$s[]" value="%2$s">',
                        esc_attr($key),
                        esc_attr($val)
                    );
                }
            } else {
                $form_html .= sprintf(
                    '<input type="hidden" name="%1$s" value="%2$s">',
                    esc_attr($key),
                    esc_attr($value)
                );
            }
        }

        $form_html .= '</form>';

        $html = sprintf(
            '<%1$s class="%2$s">%3$s</%1$s>',
            $args['container'],
            esc_attr($args['container_class']),
            $form_html
        );

        if ($args['echo']) {
            echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        } else {
            return $html;
        }
    }
}

if (!function_exists('aqualuxe_wc_product_gallery')) {
    /**
     * Display product gallery
     *
     * @param int   $product_id Product ID.
     * @param array $args       Arguments for controlling the gallery display.
     * @return void
     */
    function aqualuxe_wc_product_gallery($product_id = null, $args = array()) {
        if (!function_exists('WC')) {
            return;
        }

        if (!$product_id) {
            global $product;
            
            if (!$product) {
                return;
            }
            
            $product_id = $product->get_id();
        } else {
            $product = wc_get_product($product_id);
            
            if (!$product) {
                return;
            }
        }

        $defaults = array(
            'container'      => 'div',
            'container_class' => 'product-gallery',
            'main_class'     => 'main-image mb-4 overflow-hidden rounded-lg',
            'thumbnails_class' => 'thumbnails grid grid-cols-5 gap-2',
            'thumbnail_class' => 'thumbnail-item overflow-hidden rounded-lg cursor-pointer border-2 border-transparent hover:border-primary dark:hover:border-secondary transition-colors duration-200',
            'active_class'   => 'border-primary dark:border-secondary',
            'echo'           => true,
        );

        $args = wp_parse_args($args, $defaults);
        $html = '';

        $attachment_ids = $product->get_gallery_image_ids();
        $post_thumbnail_id = $product->get_image_id();

        if ($post_thumbnail_id) {
            array_unshift($attachment_ids, $post_thumbnail_id);
        }

        if (!empty($attachment_ids)) {
            $main_image = wp_get_attachment_image_src($attachment_ids[0], 'full');
            
            $main_html = sprintf(
                '<div class="%1$s" id="product-main-image">
                    <img src="%2$s" alt="%3$s" class="w-full h-auto">
                </div>',
                esc_attr($args['main_class']),
                esc_url($main_image[0]),
                esc_attr($product->get_name())
            );

            $thumbnails_html = '<div class="' . esc_attr($args['thumbnails_class']) . '">';
            
            foreach ($attachment_ids as $index => $attachment_id) {
                $thumbnail = wp_get_attachment_image_src($attachment_id, 'thumbnail');
                $full_size = wp_get_attachment_image_src($attachment_id, 'full');
                $active_class = ($index === 0) ? ' ' . $args['active_class'] : '';
                
                $thumbnails_html .= sprintf(
                    '<div class="%1$s%2$s" data-image-id="%3$s" data-full-src="%4$s" onclick="document.getElementById(\'product-main-image\').querySelector(\'img\').src=\'%4$s\'; document.querySelectorAll(\'.%1$s\').forEach(el => el.classList.remove(\'%5$s\')); this.classList.add(\'%5$s\');">
                        <img src="%6$s" alt="%7$s" class="w-full h-auto">
                    </div>',
                    esc_attr($args['thumbnail_class']),
                    esc_attr($active_class),
                    esc_attr($attachment_id),
                    esc_url($full_size[0]),
                    esc_attr($args['active_class']),
                    esc_url($thumbnail[0]),
                    esc_attr($product->get_name())
                );
            }
            
            $thumbnails_html .= '</div>';

            $html = sprintf(
                '<%1$s class="%2$s">
                    %3$s
                    %4$s
                </%1$s>',
                $args['container'],
                esc_attr($args['container_class']),
                $main_html,
                $thumbnails_html
            );
        }

        if ($args['echo']) {
            echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        } else {
            return $html;
        }
    }
}

if (!function_exists('aqualuxe_wc_product_tabs')) {
    /**
     * Display product tabs
     *
     * @param int   $product_id Product ID.
     * @param array $args       Arguments for controlling the tabs display.
     * @return void
     */
    function aqualuxe_wc_product_tabs($product_id = null, $args = array()) {
        if (!function_exists('WC')) {
            return;
        }

        if (!$product_id) {
            global $product;
            
            if (!$product) {
                return;
            }
            
            $product_id = $product->get_id();
        } else {
            $product = wc_get_product($product_id);
            
            if (!$product) {
                return;
            }
        }

        $defaults = array(
            'container'      => 'div',
            'container_class' => 'product-tabs mt-8',
            'tabs_class'     => 'flex border-b border-light-darker dark:border-dark-lighter mb-6',
            'tab_class'      => 'mr-4',
            'tab_link_class' => 'inline-block py-2 px-1 border-b-2 border-transparent hover:text-primary dark:hover:text-secondary-light transition-colors duration-200',
            'active_class'   => 'border-primary dark:border-secondary-light text-primary dark:text-secondary-light',
            'content_class'  => 'tab-content',
            'echo'           => true,
        );

        $args = wp_parse_args($args, $defaults);
        $html = '';

        $tabs = apply_filters('woocommerce_product_tabs', array());

        if (!empty($tabs)) {
            $html = sprintf(
                '<%1$s class="%2$s" x-data="{ activeTab: \'tab-0\' }">
                    <div class="%3$s">',
                $args['container'],
                esc_attr($args['container_class']),
                esc_attr($args['tabs_class'])
            );

            foreach ($tabs as $key => $tab) {
                $tab_id = 'tab-' . sanitize_title($key);
                
                $html .= sprintf(
                    '<div class="%1$s">
                        <a @click.prevent="activeTab = \'%2$s\'" :class="{ \'%3$s\': activeTab === \'%2$s\' }" class="%4$s" href="#%2$s">%5$s</a>
                    </div>',
                    esc_attr($args['tab_class']),
                    esc_attr($tab_id),
                    esc_attr($args['active_class']),
                    esc_attr($args['tab_link_class']),
                    esc_html($tab['title'])
                );
            }

            $html .= '</div>';

            $i = 0;
            foreach ($tabs as $key => $tab) {
                $tab_id = 'tab-' . sanitize_title($key);
                
                $html .= sprintf(
                    '<div x-show="activeTab === \'%1$s\'" class="%2$s prose dark:prose-invert max-w-none">',
                    esc_attr($tab_id),
                    esc_attr($args['content_class'])
                );

                if (isset($tab['callback'])) {
                    ob_start();
                    call_user_func($tab['callback'], $key, $tab);
                    $html .= ob_get_clean();
                }

                $html .= '</div>';
                $i++;
            }

            $html .= '</' . $args['container'] . '>';
        }

        if ($args['echo']) {
            echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        } else {
            return $html;
        }
    }
}

if (!function_exists('aqualuxe_wc_product_meta')) {
    /**
     * Display product meta information
     *
     * @param int   $product_id Product ID.
     * @param array $args       Arguments for controlling the meta display.
     * @return void
     */
    function aqualuxe_wc_product_meta($product_id = null, $args = array()) {
        if (!function_exists('WC')) {
            return;
        }

        if (!$product_id) {
            global $product;
            
            if (!$product) {
                return;
            }
            
            $product_id = $product->get_id();
        } else {
            $product = wc_get_product($product_id);
            
            if (!$product) {
                return;
            }
        }

        $defaults = array(
            'container'      => 'div',
            'container_class' => 'product-meta mt-4 pt-4 border-t border-light-darker dark:border-dark-lighter',
            'item_class'     => 'meta-item mb-2',
            'label_class'    => 'font-medium text-dark dark:text-light',
            'value_class'    => 'text-dark-light dark:text-light-dark',
            'echo'           => true,
        );

        $args = wp_parse_args($args, $defaults);
        $html = '';

        $sku = $product->get_sku();
        $categories = get_the_terms($product_id, 'product_cat');
        $tags = get_the_terms($product_id, 'product_tag');

        $meta_html = '';

        if ($sku) {
            $meta_html .= sprintf(
                '<div class="%1$s">
                    <span class="%2$s">%3$s:</span> 
                    <span class="%4$s">%5$s</span>
                </div>',
                esc_attr($args['item_class']),
                esc_attr($args['label_class']),
                esc_html__('SKU', 'aqualuxe'),
                esc_attr($args['value_class']),
                esc_html($sku)
            );
        }

        if (!empty($categories) && !is_wp_error($categories)) {
            $category_links = array();
            
            foreach ($categories as $category) {
                $category_links[] = sprintf(
                    '<a href="%1$s" class="hover:text-primary dark:hover:text-secondary-light transition-colors duration-200">%2$s</a>',
                    esc_url(get_term_link($category)),
                    esc_html($category->name)
                );
            }
            
            $meta_html .= sprintf(
                '<div class="%1$s">
                    <span class="%2$s">%3$s:</span> 
                    <span class="%4$s">%5$s</span>
                </div>',
                esc_attr($args['item_class']),
                esc_attr($args['label_class']),
                esc_html__('Categories', 'aqualuxe'),
                esc_attr($args['value_class']),
                implode(', ', $category_links)
            );
        }

        if (!empty($tags) && !is_wp_error($tags)) {
            $tag_links = array();
            
            foreach ($tags as $tag) {
                $tag_links[] = sprintf(
                    '<a href="%1$s" class="hover:text-primary dark:hover:text-secondary-light transition-colors duration-200">%2$s</a>',
                    esc_url(get_term_link($tag)),
                    esc_html($tag->name)
                );
            }
            
            $meta_html .= sprintf(
                '<div class="%1$s">
                    <span class="%2$s">%3$s:</span> 
                    <span class="%4$s">%5$s</span>
                </div>',
                esc_attr($args['item_class']),
                esc_attr($args['label_class']),
                esc_html__('Tags', 'aqualuxe'),
                esc_attr($args['value_class']),
                implode(', ', $tag_links)
            );
        }

        if ($meta_html) {
            $html = sprintf(
                '<%1$s class="%2$s">%3$s</%1$s>',
                $args['container'],
                esc_attr($args['container_class']),
                $meta_html
            );
        }

        if ($args['echo']) {
            echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        } else {
            return $html;
        }
    }
}

if (!function_exists('aqualuxe_wc_product_rating')) {
    /**
     * Display product rating
     *
     * @param int   $product_id Product ID.
     * @param array $args       Arguments for controlling the rating display.
     * @return void
     */
    function aqualuxe_wc_product_rating($product_id = null, $args = array()) {
        if (!function_exists('WC')) {
            return;
        }

        if (!$product_id) {
            global $product;
            
            if (!$product) {
                return;
            }
            
            $product_id = $product->get_id();
        } else {
            $product = wc_get_product($product_id);
            
            if (!$product) {
                return;
            }
        }

        $defaults = array(
            'container'      => 'div',
            'container_class' => 'product-rating flex items-center',
            'stars_class'    => 'stars flex text-accent mr-2',
            'count_class'    => 'count text-dark-light dark:text-light-dark',
            'echo'           => true,
        );

        $args = wp_parse_args($args, $defaults);
        $html = '';

        $rating = $product->get_average_rating();
        $count = $product->get_review_count();

        if ($rating > 0) {
            $stars_html = '';
            $rating_floor = floor($rating);
            $rating_decimal = $rating - $rating_floor;
            
            // Full stars
            for ($i = 1; $i <= $rating_floor; $i++) {
                $stars_html .= aqualuxe_get_svg('star');
            }
            
            // Partial star
            if ($rating_decimal >= 0.3 && $rating_decimal <= 0.7) {
                $stars_html .= '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-star"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77" fill="currentColor"></polygon></svg>';
            } elseif ($rating_decimal > 0.7) {
                $stars_html .= aqualuxe_get_svg('star');
            }
            
            // Empty stars
            for ($i = ceil($rating); $i < 5; $i++) {
                $stars_html .= '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-star"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>';
            }

            $html = sprintf(
                '<%1$s class="%2$s">
                    <div class="%3$s">%4$s</div>
                    <div class="%5$s">(%6$s %7$s)</div>
                </%1$s>',
                $args['container'],
                esc_attr($args['container_class']),
                esc_attr($args['stars_class']),
                $stars_html,
                esc_attr($args['count_class']),
                esc_html($count),
                esc_html(_n('review', 'reviews', $count, 'aqualuxe'))
            );
        }

        if ($args['echo']) {
            echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        } else {
            return $html;
        }
    }
}

if (!function_exists('aqualuxe_wc_product_price')) {
    /**
     * Display product price
     *
     * @param int   $product_id Product ID.
     * @param array $args       Arguments for controlling the price display.
     * @return void
     */
    function aqualuxe_wc_product_price($product_id = null, $args = array()) {
        if (!function_exists('WC')) {
            return;
        }

        if (!$product_id) {
            global $product;
            
            if (!$product) {
                return;
            }
            
            $product_id = $product->get_id();
        } else {
            $product = wc_get_product($product_id);
            
            if (!$product) {
                return;
            }
        }

        $defaults = array(
            'container'      => 'div',
            'container_class' => 'product-price my-4',
            'price_class'    => 'text-2xl font-bold text-primary dark:text-secondary-light',
            'sale_class'     => 'text-2xl font-bold text-primary dark:text-secondary-light',
            'regular_class'  => 'text-lg line-through text-dark-light dark:text-light-dark ml-2',
            'echo'           => true,
        );

        $args = wp_parse_args($args, $defaults);
        $html = '';

        $price_html = $product->get_price_html();

        if ($price_html) {
            // Replace default WooCommerce price HTML with our custom styling
            if ($product->is_on_sale()) {
                $regular_price = wc_get_price_to_display($product, array('price' => $product->get_regular_price()));
                $sale_price = wc_get_price_to_display($product, array('price' => $product->get_sale_price()));
                
                $formatted_regular_price = wc_price($regular_price);
                $formatted_sale_price = wc_price($sale_price);
                
                $price_html = sprintf(
                    '<span class="%1$s">%2$s</span> <span class="%3$s">%4$s</span>',
                    esc_attr($args['sale_class']),
                    $formatted_sale_price,
                    esc_attr($args['regular_class']),
                    $formatted_regular_price
                );
            } else {
                $price_html = sprintf(
                    '<span class="%1$s">%2$s</span>',
                    esc_attr($args['price_class']),
                    $price_html
                );
            }

            $html = sprintf(
                '<%1$s class="%2$s">%3$s</%1$s>',
                $args['container'],
                esc_attr($args['container_class']),
                $price_html
            );
        }

        if ($args['echo']) {
            echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        } else {
            return $html;
        }
    }
}

if (!function_exists('aqualuxe_wc_product_add_to_cart')) {
    /**
     * Display product add to cart button
     *
     * @param int   $product_id Product ID.
     * @param array $args       Arguments for controlling the add to cart display.
     * @return void
     */
    function aqualuxe_wc_product_add_to_cart($product_id = null, $args = array()) {
        if (!function_exists('WC')) {
            return;
        }

        if (!$product_id) {
            global $product;
            
            if (!$product) {
                return;
            }
            
            $product_id = $product->get_id();
        } else {
            $product = wc_get_product($product_id);
            
            if (!$product) {
                return;
            }
        }

        $defaults = array(
            'container'      => 'div',
            'container_class' => 'product-add-to-cart mt-6',
            'quantity_class' => 'quantity flex items-center mb-4',
            'button_class'   => 'btn btn-primary',
            'echo'           => true,
        );

        $args = wp_parse_args($args, $defaults);
        $html = '';

        ob_start();

        if ($product->is_type('simple')) {
            // Quantity input
            echo '<div class="' . esc_attr($args['quantity_class']) . '">';
            echo '<label for="quantity" class="mr-4 font-medium">' . esc_html__('Quantity', 'aqualuxe') . '</label>';
            
            woocommerce_quantity_input(
                array(
                    'min_value'   => apply_filters('woocommerce_quantity_input_min', $product->get_min_purchase_quantity(), $product),
                    'max_value'   => apply_filters('woocommerce_quantity_input_max', $product->get_max_purchase_quantity(), $product),
                    'input_value' => isset($_POST['quantity']) ? wc_stock_amount(wp_unslash($_POST['quantity'])) : $product->get_min_purchase_quantity(),
                )
            );
            
            echo '</div>';
            
            // Add to cart button
            echo '<button type="submit" name="add-to-cart" value="' . esc_attr($product_id) . '" class="' . esc_attr($args['button_class']) . '">' . esc_html($product->single_add_to_cart_text()) . '</button>';
        } elseif ($product->is_type('variable')) {
            woocommerce_variable_add_to_cart();
        } elseif ($product->is_type('grouped')) {
            woocommerce_grouped_add_to_cart();
        } elseif ($product->is_type('external')) {
            woocommerce_external_add_to_cart();
        }

        $cart_html = ob_get_clean();

        if ($cart_html) {
            $html = sprintf(
                '<%1$s class="%2$s">
                    <form class="cart" action="%3$s" method="post" enctype="multipart/form-data">
                        %4$s
                    </form>
                </%1$s>',
                $args['container'],
                esc_attr($args['container_class']),
                esc_url(apply_filters('woocommerce_add_to_cart_form_action', $product->get_permalink())),
                $cart_html
            );
        }

        if ($args['echo']) {
            echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        } else {
            return $html;
        }
    }
}

if (!function_exists('aqualuxe_wc_product_stock_status')) {
    /**
     * Display product stock status
     *
     * @param int   $product_id Product ID.
     * @param array $args       Arguments for controlling the stock status display.
     * @return void
     */
    function aqualuxe_wc_product_stock_status($product_id = null, $args = array()) {
        if (!function_exists('WC')) {
            return;
        }

        if (!$product_id) {
            global $product;
            
            if (!$product) {
                return;
            }
            
            $product_id = $product->get_id();
        } else {
            $product = wc_get_product($product_id);
            
            if (!$product) {
                return;
            }
        }

        $defaults = array(
            'container'      => 'div',
            'container_class' => 'product-stock-status mb-4',
            'in_stock_class' => 'text-green-600 dark:text-green-400 flex items-center',
            'out_of_stock_class' => 'text-red-600 dark:text-red-400 flex items-center',
            'low_stock_class' => 'text-yellow-600 dark:text-yellow-400 flex items-center',
            'icon_class'     => 'mr-2',
            'echo'           => true,
        );

        $args = wp_parse_args($args, $defaults);
        $html = '';

        $availability = $product->get_availability();
        $stock_status = $availability['class'];
        $stock_html = '';

        if ($stock_status === 'in-stock') {
            $stock_html = sprintf(
                '<div class="%1$s">
                    <span class="%2$s">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </span>
                    %3$s
                </div>',
                esc_attr($args['in_stock_class']),
                esc_attr($args['icon_class']),
                esc_html__('In Stock', 'aqualuxe')
            );
        } elseif ($stock_status === 'out-of-stock') {
            $stock_html = sprintf(
                '<div class="%1$s">
                    <span class="%2$s">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </span>
                    %3$s
                </div>',
                esc_attr($args['out_of_stock_class']),
                esc_attr($args['icon_class']),
                esc_html__('Out of Stock', 'aqualuxe')
            );
        } elseif ($stock_status === 'available-on-backorder') {
            $stock_html = sprintf(
                '<div class="%1$s">
                    <span class="%2$s">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                        </svg>
                    </span>
                    %3$s
                </div>',
                esc_attr($args['low_stock_class']),
                esc_attr($args['icon_class']),
                esc_html__('Available on Backorder', 'aqualuxe')
            );
        }

        if ($stock_html) {
            $html = sprintf(
                '<%1$s class="%2$s">%3$s</%1$s>',
                $args['container'],
                esc_attr($args['container_class']),
                $stock_html
            );
        }

        if ($args['echo']) {
            echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        } else {
            return $html;
        }
    }
}

if (!function_exists('aqualuxe_wc_product_sharing')) {
    /**
     * Display product sharing links
     *
     * @param int   $product_id Product ID.
     * @param array $args       Arguments for controlling the sharing display.
     * @return void
     */
    function aqualuxe_wc_product_sharing($product_id = null, $args = array()) {
        if (!function_exists('WC')) {
            return;
        }

        if (!$product_id) {
            global $product;
            
            if (!$product) {
                return;
            }
            
            $product_id = $product->get_id();
        } else {
            $product = wc_get_product($product_id);
            
            if (!$product) {
                return;
            }
        }

        $defaults = array(
            'container'      => 'div',
            'container_class' => 'product-sharing mt-6 pt-4 border-t border-light-darker dark:border-dark-lighter',
            'title'          => __('Share this product', 'aqualuxe'),
            'title_class'    => 'text-lg font-medium mb-2',
            'links_class'    => 'flex space-x-3',
            'link_class'     => 'text-dark-light dark:text-light-dark hover:text-primary dark:hover:text-secondary-light transition-colors duration-200',
            'echo'           => true,
        );

        $args = wp_parse_args($args, $defaults);
        $html = '';

        $product_url = urlencode(get_permalink($product_id));
        $product_title = urlencode(get_the_title($product_id));
        $product_image = wp_get_attachment_image_src(get_post_thumbnail_id($product_id), 'full');
        $product_image_url = $product_image ? urlencode($product_image[0]) : '';

        $facebook_url = 'https://www.facebook.com/sharer/sharer.php?u=' . $product_url;
        $twitter_url = 'https://twitter.com/intent/tweet?text=' . $product_title . '&url=' . $product_url;
        $pinterest_url = 'https://pinterest.com/pin/create/button/?url=' . $product_url . '&media=' . $product_image_url . '&description=' . $product_title;
        $email_url = 'mailto:?subject=' . $product_title . '&body=' . $product_url;

        $title_html = '';
        if ($args['title']) {
            $title_html = sprintf(
                '<h4 class="%1$s">%2$s</h4>',
                esc_attr($args['title_class']),
                esc_html($args['title'])
            );
        }

        $links_html = sprintf(
            '<div class="%1$s">
                <a href="%2$s" target="_blank" rel="noopener noreferrer" class="%3$s" aria-label="%4$s">%5$s</a>
                <a href="%6$s" target="_blank" rel="noopener noreferrer" class="%3$s" aria-label="%7$s">%8$s</a>
                <a href="%9$s" target="_blank" rel="noopener noreferrer" class="%3$s" aria-label="%10$s">%11$s</a>
                <a href="%12$s" class="%3$s" aria-label="%13$s">%14$s</a>
            </div>',
            esc_attr($args['links_class']),
            esc_url($facebook_url),
            esc_attr($args['link_class']),
            esc_attr__('Share on Facebook', 'aqualuxe'),
            aqualuxe_get_svg('facebook'),
            esc_url($twitter_url),
            esc_attr__('Share on Twitter', 'aqualuxe'),
            aqualuxe_get_svg('twitter'),
            esc_url($pinterest_url),
            esc_attr__('Pin on Pinterest', 'aqualuxe'),
            '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 12a4 4 0 1 0 8 0 4 4 0 0 0-8 0z"></path><path d="M12 2v2"></path><path d="M12 20v2"></path><path d="m4.93 4.93 1.41 1.41"></path><path d="m17.66 17.66 1.41 1.41"></path><path d="M2 12h2"></path><path d="M20 12h2"></path><path d="m6.34 17.66-1.41 1.41"></path><path d="m19.07 4.93-1.41 1.41"></path></svg>',
            esc_url($email_url),
            esc_attr__('Share via Email', 'aqualuxe'),
            aqualuxe_get_svg('mail')
        );

        $html = sprintf(
            '<%1$s class="%2$s">
                %3$s
                %4$s
            </%1$s>',
            $args['container'],
            esc_attr($args['container_class']),
            $title_html,
            $links_html
        );

        if ($args['echo']) {
            echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        } else {
            return $html;
        }
    }
}

if (!function_exists('aqualuxe_wc_product_wishlist_button')) {
    /**
     * Display product wishlist button
     *
     * @param int   $product_id Product ID.
     * @param array $args       Arguments for controlling the wishlist button display.
     * @return void
     */
    function aqualuxe_wc_product_wishlist_button($product_id = null, $args = array()) {
        if (!function_exists('WC')) {
            return;
        }

        if (!$product_id) {
            global $product;
            
            if (!$product) {
                return;
            }
            
            $product_id = $product->get_id();
        } else {
            $product = wc_get_product($product_id);
            
            if (!$product) {
                return;
            }
        }

        $defaults = array(
            'container'      => 'div',
            'container_class' => 'product-wishlist inline-block',
            'button_class'   => 'wishlist-btn inline-flex items-center text-dark-light dark:text-light-dark hover:text-primary dark:hover:text-secondary-light transition-colors duration-200',
            'icon_class'     => 'mr-2',
            'text'           => __('Add to Wishlist', 'aqualuxe'),
            'echo'           => true,
        );

        $args = wp_parse_args($args, $defaults);
        $html = '';

        $html = sprintf(
            '<%1$s class="%2$s">
                <button type="button" class="%3$s" data-product-id="%4$s">
                    <span class="%5$s">%6$s</span>
                    <span>%7$s</span>
                </button>
            </%1$s>',
            $args['container'],
            esc_attr($args['container_class']),
            esc_attr($args['button_class']),
            esc_attr($product_id),
            esc_attr($args['icon_class']),
            aqualuxe_get_svg('heart'),
            esc_html($args['text'])
        );

        if ($args['echo']) {
            echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        } else {
            return $html;
        }
    }
}

if (!function_exists('aqualuxe_wc_product_compare_button')) {
    /**
     * Display product compare button
     *
     * @param int   $product_id Product ID.
     * @param array $args       Arguments for controlling the compare button display.
     * @return void
     */
    function aqualuxe_wc_product_compare_button($product_id = null, $args = array()) {
        if (!function_exists('WC')) {
            return;
        }

        if (!$product_id) {
            global $product;
            
            if (!$product) {
                return;
            }
            
            $product_id = $product->get_id();
        } else {
            $product = wc_get_product($product_id);
            
            if (!$product) {
                return;
            }
        }

        $defaults = array(
            'container'      => 'div',
            'container_class' => 'product-compare inline-block ml-4',
            'button_class'   => 'compare-btn inline-flex items-center text-dark-light dark:text-light-dark hover:text-primary dark:hover:text-secondary-light transition-colors duration-200',
            'icon_class'     => 'mr-2',
            'text'           => __('Compare', 'aqualuxe'),
            'echo'           => true,
        );

        $args = wp_parse_args($args, $defaults);
        $html = '';

        $html = sprintf(
            '<%1$s class="%2$s">
                <button type="button" class="%3$s" data-product-id="%4$s">
                    <span class="%5$s">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="17 1 21 5 17 9"></polyline><path d="M3 11V9a4 4 0 0 1 4-4h14"></path><polyline points="7 23 3 19 7 15"></polyline><path d="M21 13v2a4 4 0 0 1-4 4H3"></path></svg>
                    </span>
                    <span>%6$s</span>
                </button>
            </%1$s>',
            $args['container'],
            esc_attr($args['container_class']),
            esc_attr($args['button_class']),
            esc_attr($product_id),
            esc_attr($args['icon_class']),
            esc_html($args['text'])
        );

        if ($args['echo']) {
            echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        } else {
            return $html;
        }
    }
}

if (!function_exists('aqualuxe_wc_product_actions')) {
    /**
     * Display product actions (wishlist and compare)
     *
     * @param int   $product_id Product ID.
     * @param array $args       Arguments for controlling the actions display.
     * @return void
     */
    function aqualuxe_wc_product_actions($product_id = null, $args = array()) {
        if (!function_exists('WC')) {
            return;
        }

        if (!$product_id) {
            global $product;
            
            if (!$product) {
                return;
            }
            
            $product_id = $product->get_id();
        } else {
            $product = wc_get_product($product_id);
            
            if (!$product) {
                return;
            }
        }

        $defaults = array(
            'container'      => 'div',
            'container_class' => 'product-actions mt-4',
            'echo'           => true,
        );

        $args = wp_parse_args($args, $defaults);
        $html = '';

        $wishlist_button = aqualuxe_wc_product_wishlist_button($product_id, array('echo' => false));
        $compare_button = aqualuxe_wc_product_compare_button($product_id, array('echo' => false));

        $html = sprintf(
            '<%1$s class="%2$s">
                %3$s
                %4$s
            </%1$s>',
            $args['container'],
            esc_attr($args['container_class']),
            $wishlist_button,
            $compare_button
        );

        if ($args['echo']) {
            echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        } else {
            return $html;
        }
    }
}

if (!function_exists('aqualuxe_wc_checkout_steps')) {
    /**
     * Display checkout steps
     *
     * @param array $args Arguments for controlling the checkout steps display.
     * @return void
     */
    function aqualuxe_wc_checkout_steps($args = array()) {
        if (!function_exists('WC')) {
            return;
        }

        $defaults = array(
            'container'      => 'div',
            'container_class' => 'checkout-steps flex items-center justify-center mb-8',
            'step_class'     => 'step flex flex-col items-center mx-4',
            'number_class'   => 'step-number w-8 h-8 flex items-center justify-center rounded-full bg-light-dark dark:bg-dark-light text-dark dark:text-light mb-2',
            'active_class'   => 'bg-primary text-white dark:bg-secondary',
            'completed_class' => 'bg-green-500 text-white',
            'title_class'    => 'step-title text-sm',
            'line_class'     => 'step-line w-16 h-px bg-light-darker dark:bg-dark-lighter mx-2',
            'echo'           => true,
        );

        $args = wp_parse_args($args, $defaults);
        $html = '';

        $current_step = 1;
        
        if (is_checkout()) {
            $current_step = 2;
        }
        
        if (is_wc_endpoint_url('order-received')) {
            $current_step = 3;
        }

        $steps = array(
            1 => __('Cart', 'aqualuxe'),
            2 => __('Checkout', 'aqualuxe'),
            3 => __('Order Complete', 'aqualuxe'),
        );

        $steps_html = '';
        $step_count = count($steps);
        $i = 1;

        foreach ($steps as $step_num => $step_title) {
            $is_active = ($step_num === $current_step);
            $is_completed = ($step_num < $current_step);
            
            $number_class = $args['number_class'];
            
            if ($is_active) {
                $number_class .= ' ' . $args['active_class'];
            } elseif ($is_completed) {
                $number_class .= ' ' . $args['completed_class'];
            }
            
            $step_content = sprintf(
                '<div class="%1$s">
                    <div class="%2$s">
                        %3$s
                    </div>
                    <div class="%4$s">%5$s</div>
                </div>',
                esc_attr($args['step_class']),
                esc_attr($number_class),
                $is_completed ? '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>' : esc_html($step_num),
                esc_attr($args['title_class']),
                esc_html($step_title)
            );
            
            $steps_html .= $step_content;
            
            if ($i < $step_count) {
                $steps_html .= sprintf(
                    '<div class="%1$s"></div>',
                    esc_attr($args['line_class'])
                );
            }
            
            $i++;
        }

        $html = sprintf(
            '<%1$s class="%2$s">%3$s</%1$s>',
            $args['container'],
            esc_attr($args['container_class']),
            $steps_html
        );

        if ($args['echo']) {
            echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        } else {
            return $html;
        }
    }
}

if (!function_exists('aqualuxe_wc_order_tracking_form')) {
    /**
     * Display order tracking form
     *
     * @param array $args Arguments for controlling the form display.
     * @return void
     */
    function aqualuxe_wc_order_tracking_form($args = array()) {
        if (!function_exists('WC')) {
            return;
        }

        $defaults = array(
            'container'      => 'div',
            'container_class' => 'order-tracking-form p-6 bg-white dark:bg-dark-light rounded-lg shadow-soft',
            'title'          => __('Track Your Order', 'aqualuxe'),
            'title_class'    => 'text-xl font-serif font-bold mb-4',
            'description'    => __('To track your order please enter your Order ID and the email address you used for the order.', 'aqualuxe'),
            'description_class' => 'mb-6 text-dark-light dark:text-light-dark',
            'form_class'     => 'tracking-form',
            'field_class'    => 'mb-4',
            'label_class'    => 'block mb-2 font-medium text-dark dark:text-light',
            'input_class'    => 'form-input',
            'button_class'   => 'btn btn-primary mt-2',
            'echo'           => true,
        );

        $args = wp_parse_args($args, $defaults);
        $html = '';

        $title_html = '';
        if ($args['title']) {
            $title_html = sprintf(
                '<h3 class="%1$s">%2$s</h3>',
                esc_attr($args['title_class']),
                esc_html($args['title'])
            );
        }

        $description_html = '';
        if ($args['description']) {
            $description_html = sprintf(
                '<p class="%1$s">%2$s</p>',
                esc_attr($args['description_class']),
                esc_html($args['description'])
            );
        }

        $form_html = sprintf(
            '<form action="%1$s" method="post" class="%2$s">
                <div class="%3$s">
                    <label for="orderid" class="%4$s">%5$s</label>
                    <input type="text" name="orderid" id="orderid" placeholder="%6$s" class="%7$s" required>
                </div>
                <div class="%3$s">
                    <label for="order_email" class="%4$s">%8$s</label>
                    <input type="email" name="order_email" id="order_email" placeholder="%9$s" class="%7$s" required>
                </div>
                <button type="submit" name="track" value="Track" class="%10$s">%11$s</button>
            </form>',
            esc_url(wc_get_page_permalink('myaccount')),
            esc_attr($args['form_class']),
            esc_attr($args['field_class']),
            esc_attr($args['label_class']),
            esc_html__('Order ID', 'aqualuxe'),
            esc_attr__('Found in your order confirmation email', 'aqualuxe'),
            esc_attr($args['input_class']),
            esc_html__('Billing Email', 'aqualuxe'),
            esc_attr__('Email you used during checkout', 'aqualuxe'),
            esc_attr($args['button_class']),
            esc_html__('Track Order', 'aqualuxe')
        );

        $html = sprintf(
            '<%1$s class="%2$s">
                %3$s
                %4$s
                %5$s
            </%1$s>',
            $args['container'],
            esc_attr($args['container_class']),
            $title_html,
            $description_html,
            $form_html
        );

        if ($args['echo']) {
            echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        } else {
            return $html;
        }
    }
}

if (!function_exists('aqualuxe_wc_get_product_categories_dropdown')) {
    /**
     * Get product categories dropdown
     *
     * @param array $args Arguments for controlling the dropdown.
     * @return string HTML for the dropdown
     */
    function aqualuxe_wc_get_product_categories_dropdown($args = array()) {
        if (!function_exists('WC')) {
            return '';
        }

        $defaults = array(
            'show_option_none' => __('Select a category', 'aqualuxe'),
            'taxonomy'         => 'product_cat',
            'name'             => 'product_cat',
            'id'               => 'product_cat',
            'class'            => 'form-select',
            'selected'         => 0,
            'hide_empty'       => true,
        );

        $args = wp_parse_args($args, $defaults);
        
        $dropdown_args = array(
            'show_option_none' => $args['show_option_none'],
            'taxonomy'         => $args['taxonomy'],
            'name'             => $args['name'],
            'id'               => $args['id'],
            'class'            => $args['class'],
            'selected'         => $args['selected'],
            'hide_empty'       => $args['hide_empty'],
            'echo'             => false,
        );
        
        return wp_dropdown_categories($dropdown_args);
    }
}

if (!function_exists('aqualuxe_wc_get_product_search_form')) {
    /**
     * Get product search form
     *
     * @param array $args Arguments for controlling the search form.
     * @return string HTML for the search form
     */
    function aqualuxe_wc_get_product_search_form($args = array()) {
        if (!function_exists('WC')) {
            return '';
        }

        $defaults = array(
            'container_class' => 'product-search',
            'form_class'      => 'relative',
            'input_class'     => 'form-input pr-10',
            'button_class'    => 'absolute right-0 top-0 h-full px-3 text-dark-light dark:text-light-dark hover:text-primary dark:hover:text-secondary-light transition-colors duration-200',
            'placeholder'     => __('Search products...', 'aqualuxe'),
            'show_categories' => true,
            'categories_class' => 'form-select mb-2',
        );

        $args = wp_parse_args($args, $defaults);
        $html = '';

        $html = '<div class="' . esc_attr($args['container_class']) . '">';
        
        if ($args['show_categories']) {
            $html .= aqualuxe_wc_get_product_categories_dropdown(array(
                'class' => $args['categories_class'],
            ));
        }
        
        $html .= '<form role="search" method="get" class="' . esc_attr($args['form_class']) . '" action="' . esc_url(home_url('/')) . '">';
        $html .= '<input type="hidden" name="post_type" value="product" />';
        $html .= '<input type="search" class="' . esc_attr($args['input_class']) . '" placeholder="' . esc_attr($args['placeholder']) . '" value="' . get_search_query() . '" name="s" />';
        $html .= '<button type="submit" class="' . esc_attr($args['button_class']) . '">' . aqualuxe_get_svg('search') . '</button>';
        $html .= '</form>';
        $html .= '</div>';
        
        return $html;
    }
}

if (!function_exists('aqualuxe_wc_product_search_form')) {
    /**
     * Display product search form
     *
     * @param array $args Arguments for controlling the search form display.
     * @return void
     */
    function aqualuxe_wc_product_search_form($args = array()) {
        echo aqualuxe_wc_get_product_search_form($args); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    }
}