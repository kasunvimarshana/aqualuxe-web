<?php
/**
 * Template helper functions for AquaLuxe theme
 *
 * @package AquaLuxe
 */

if (!function_exists('aqualuxe_site_logo')) {
    /**
     * Display the site logo
     *
     * @param array $args Arguments for controlling the logo display.
     * @return void
     */
    function aqualuxe_site_logo($args = array()) {
        $defaults = array(
            'logo_class' => 'site-logo',
            'echo'       => true,
        );

        $args = wp_parse_args($args, $defaults);
        $html = '';

        // Check if custom logo is set
        if (has_custom_logo()) {
            $custom_logo_id = get_theme_mod('custom_logo');
            $logo = wp_get_attachment_image_src($custom_logo_id, 'full');

            if ($logo) {
                $html = sprintf(
                    '<a href="%1$s" class="%2$s" rel="home" itemprop="url">
                        <img src="%3$s" alt="%4$s" class="custom-logo" width="%5$s" height="%6$s" itemprop="logo" />
                    </a>',
                    esc_url(home_url('/')),
                    esc_attr($args['logo_class']),
                    esc_url($logo[0]),
                    esc_attr(get_bloginfo('name')),
                    esc_attr($logo[1]),
                    esc_attr($logo[2])
                );
            }
        } else {
            // If no custom logo, use site title
            $html = sprintf(
                '<a href="%1$s" class="%2$s" rel="home" itemprop="url">
                    <span class="site-title text-2xl font-serif font-bold text-dark dark:text-light">%3$s</span>
                </a>',
                esc_url(home_url('/')),
                esc_attr($args['logo_class']),
                esc_html(get_bloginfo('name'))
            );
        }

        if ($args['echo']) {
            echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        } else {
            return $html;
        }
    }
}

if (!function_exists('aqualuxe_social_links')) {
    /**
     * Display social media links
     *
     * @param array $args Arguments for controlling the social links display.
     * @return void
     */
    function aqualuxe_social_links($args = array()) {
        $defaults = array(
            'container'      => 'div',
            'container_class' => 'social-links flex space-x-3',
            'link_class'     => 'social-link text-dark-light dark:text-light-dark hover:text-primary dark:hover:text-secondary-light transition-colors duration-200',
            'echo'           => true,
        );

        $args = wp_parse_args($args, $defaults);
        $html = '';

        $social_networks = array(
            'facebook'  => array(
                'name' => 'Facebook',
                'url'  => get_theme_mod('aqualuxe_facebook_url', ''),
                'icon' => 'facebook',
            ),
            'twitter'   => array(
                'name' => 'Twitter',
                'url'  => get_theme_mod('aqualuxe_twitter_url', ''),
                'icon' => 'twitter',
            ),
            'instagram' => array(
                'name' => 'Instagram',
                'url'  => get_theme_mod('aqualuxe_instagram_url', ''),
                'icon' => 'instagram',
            ),
            'youtube'   => array(
                'name' => 'YouTube',
                'url'  => get_theme_mod('aqualuxe_youtube_url', ''),
                'icon' => 'youtube',
            ),
            'linkedin'  => array(
                'name' => 'LinkedIn',
                'url'  => get_theme_mod('aqualuxe_linkedin_url', ''),
                'icon' => 'linkedin',
            ),
        );

        $links = '';

        foreach ($social_networks as $network) {
            if (!empty($network['url'])) {
                $links .= sprintf(
                    '<a href="%1$s" class="%2$s" target="_blank" rel="noopener noreferrer" aria-label="%3$s">%4$s</a>',
                    esc_url($network['url']),
                    esc_attr($args['link_class']),
                    esc_attr($network['name']),
                    aqualuxe_get_svg($network['icon'])
                );
            }
        }

        if ($links) {
            $html = sprintf(
                '<%1$s class="%2$s">%3$s</%1$s>',
                $args['container'],
                esc_attr($args['container_class']),
                $links
            );
        }

        if ($args['echo']) {
            echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        } else {
            return $html;
        }
    }
}

if (!function_exists('aqualuxe_contact_info')) {
    /**
     * Display contact information
     *
     * @param array $args Arguments for controlling the contact info display.
     * @return void
     */
    function aqualuxe_contact_info($args = array()) {
        $defaults = array(
            'container'      => 'div',
            'container_class' => 'contact-info flex flex-wrap space-x-6',
            'item_class'     => 'contact-item flex items-center',
            'icon_class'     => 'mr-2 text-primary dark:text-secondary',
            'text_class'     => 'text-dark-light dark:text-light-dark',
            'echo'           => true,
        );

        $args = wp_parse_args($args, $defaults);
        $html = '';

        $phone = get_theme_mod('aqualuxe_phone_number', '+1 (234) 567-8900');
        $email = get_theme_mod('aqualuxe_email_address', 'info@aqualuxe.com');

        $items = '';

        if ($phone) {
            $items .= sprintf(
                '<div class="%1$s">
                    <span class="%2$s">%3$s</span>
                    <a href="tel:%4$s" class="%5$s">%6$s</a>
                </div>',
                esc_attr($args['item_class']),
                esc_attr($args['icon_class']),
                aqualuxe_get_svg('phone'),
                esc_attr(preg_replace('/[^0-9+]/', '', $phone)),
                esc_attr($args['text_class']),
                esc_html($phone)
            );
        }

        if ($email) {
            $items .= sprintf(
                '<div class="%1$s">
                    <span class="%2$s">%3$s</span>
                    <a href="mailto:%4$s" class="%5$s">%6$s</a>
                </div>',
                esc_attr($args['item_class']),
                esc_attr($args['icon_class']),
                aqualuxe_get_svg('mail'),
                esc_attr($email),
                esc_attr($args['text_class']),
                esc_html($email)
            );
        }

        if ($items) {
            $html = sprintf(
                '<%1$s class="%2$s">%3$s</%1$s>',
                $args['container'],
                esc_attr($args['container_class']),
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

if (!function_exists('aqualuxe_dark_mode_toggle')) {
    /**
     * Display dark mode toggle button
     *
     * @param array $args Arguments for controlling the dark mode toggle display.
     * @return void
     */
    function aqualuxe_dark_mode_toggle($args = array()) {
        $defaults = array(
            'container'      => 'div',
            'container_class' => 'dark-mode-toggle',
            'button_class'   => 'dark-toggle-btn w-10 h-10 flex items-center justify-center rounded-full bg-light-dark dark:bg-dark-light text-dark dark:text-light hover:bg-primary hover:text-white dark:hover:bg-secondary dark:hover:text-white transition-colors duration-200',
            'echo'           => true,
        );

        $args = wp_parse_args($args, $defaults);
        $html = '';

        $html = sprintf(
            '<%1$s class="%2$s">
                <button type="button" class="%3$s" @click="$store.darkMode.toggle()" aria-label="%4$s">
                    <span x-show="!$store.darkMode.dark" aria-hidden="true">%5$s</span>
                    <span x-show="$store.darkMode.dark" aria-hidden="true">%6$s</span>
                </button>
            </%1$s>',
            $args['container'],
            esc_attr($args['container_class']),
            esc_attr($args['button_class']),
            esc_attr__('Toggle Dark Mode', 'aqualuxe'),
            aqualuxe_get_svg('moon'),
            aqualuxe_get_svg('sun')
        );

        if ($args['echo']) {
            echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        } else {
            return $html;
        }
    }
}

if (!function_exists('aqualuxe_breadcrumbs')) {
    /**
     * Display breadcrumbs
     *
     * @param array $args Arguments for controlling the breadcrumbs display.
     * @return void
     */
    function aqualuxe_breadcrumbs($args = array()) {
        $defaults = array(
            'container'      => 'nav',
            'container_class' => 'breadcrumbs py-3',
            'list_class'     => 'flex flex-wrap items-center text-sm',
            'item_class'     => 'breadcrumb-item',
            'separator'      => aqualuxe_get_svg('chevron-right'),
            'separator_class' => 'mx-2 text-dark-light dark:text-light-dark',
            'link_class'     => 'text-dark-light dark:text-light-dark hover:text-primary dark:hover:text-secondary-light transition-colors duration-200',
            'current_class'  => 'text-primary dark:text-secondary-light',
            'echo'           => true,
        );

        $args = wp_parse_args($args, $defaults);
        $html = '';

        // Get the breadcrumb trail
        $breadcrumb = new AquaLuxe_Breadcrumb();
        $trail = $breadcrumb->get_trail();

        if (!empty($trail)) {
            $items = '';

            foreach ($trail as $key => $item) {
                $is_last = ($key === count($trail) - 1);
                $item_class = $is_last ? $args['item_class'] . ' ' . $args['current_class'] : $args['item_class'];

                if (!$is_last && !empty($item['url'])) {
                    $items .= sprintf(
                        '<li class="%1$s"><a href="%2$s" class="%3$s">%4$s</a></li>',
                        esc_attr($item_class),
                        esc_url($item['url']),
                        esc_attr($args['link_class']),
                        esc_html($item['title'])
                    );
                } else {
                    $items .= sprintf(
                        '<li class="%1$s">%2$s</li>',
                        esc_attr($item_class),
                        esc_html($item['title'])
                    );
                }

                if (!$is_last) {
                    $items .= sprintf(
                        '<li class="%1$s" aria-hidden="true">%2$s</li>',
                        esc_attr($args['separator_class']),
                        $args['separator']
                    );
                }
            }

            $html = sprintf(
                '<%1$s class="%2$s" aria-label="%3$s"><ul class="%4$s">%5$s</ul></%1$s>',
                $args['container'],
                esc_attr($args['container_class']),
                esc_attr__('Breadcrumb', 'aqualuxe'),
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

if (!function_exists('aqualuxe_page_header')) {
    /**
     * Display page header
     *
     * @param array $args Arguments for controlling the page header display.
     * @return void
     */
    function aqualuxe_page_header($args = array()) {
        $defaults = array(
            'container'      => 'div',
            'container_class' => 'page-header py-12 bg-light-dark dark:bg-dark-light mb-8',
            'title_class'    => 'page-title text-3xl md:text-4xl font-serif font-bold text-dark dark:text-light mb-4',
            'description_class' => 'page-description text-lg text-dark-light dark:text-light-dark',
            'show_breadcrumbs' => true,
            'echo'           => true,
        );

        $args = wp_parse_args($args, $defaults);
        $html = '';

        $title = aqualuxe_get_page_title();
        $description = '';

        if (is_archive()) {
            $description = get_the_archive_description();
        } elseif (is_search()) {
            /* translators: %s: search query. */
            $description = sprintf(esc_html__('Search results for: %s', 'aqualuxe'), '<span>' . get_search_query() . '</span>');
        }

        $inner_html = sprintf(
            '<div class="container mx-auto px-4">
                <h1 class="%1$s">%2$s</h1>',
            esc_attr($args['title_class']),
            $title
        );

        if ($description) {
            $inner_html .= sprintf(
                '<div class="%1$s">%2$s</div>',
                esc_attr($args['description_class']),
                $description
            );
        }

        if ($args['show_breadcrumbs']) {
            $inner_html .= aqualuxe_breadcrumbs(array('echo' => false));
        }

        $inner_html .= '</div>';

        $html = sprintf(
            '<%1$s class="%2$s">%3$s</%1$s>',
            $args['container'],
            esc_attr($args['container_class']),
            $inner_html
        );

        if ($args['echo']) {
            echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        } else {
            return $html;
        }
    }
}

if (!function_exists('aqualuxe_post_meta')) {
    /**
     * Display post meta information
     *
     * @param array $args Arguments for controlling the post meta display.
     * @return void
     */
    function aqualuxe_post_meta($args = array()) {
        $defaults = array(
            'container'      => 'div',
            'container_class' => 'post-meta flex flex-wrap items-center text-sm text-dark-light dark:text-light-dark mb-4',
            'date_class'     => 'post-date mr-4',
            'author_class'   => 'post-author mr-4',
            'categories_class' => 'post-categories mr-4',
            'comments_class' => 'post-comments',
            'show_date'      => true,
            'show_author'    => true,
            'show_categories' => true,
            'show_comments'  => true,
            'echo'           => true,
        );

        $args = wp_parse_args($args, $defaults);
        $html = '';

        $meta_items = '';

        if ($args['show_date']) {
            $meta_items .= sprintf(
                '<div class="%1$s">
                    <span class="sr-only">%2$s</span>
                    <time datetime="%3$s">%4$s</time>
                </div>',
                esc_attr($args['date_class']),
                esc_html__('Posted on', 'aqualuxe'),
                esc_attr(get_the_date('c')),
                esc_html(get_the_date())
            );
        }

        if ($args['show_author']) {
            $meta_items .= sprintf(
                '<div class="%1$s">
                    <span class="sr-only">%2$s</span>
                    <a href="%3$s" class="author-link">%4$s</a>
                </div>',
                esc_attr($args['author_class']),
                esc_html__('Posted by', 'aqualuxe'),
                esc_url(get_author_posts_url(get_the_author_meta('ID'))),
                esc_html(get_the_author())
            );
        }

        if ($args['show_categories'] && has_category()) {
            $categories_list = get_the_category_list(', ');
            
            if ($categories_list) {
                $meta_items .= sprintf(
                    '<div class="%1$s">
                        <span class="sr-only">%2$s</span>
                        %3$s
                    </div>',
                    esc_attr($args['categories_class']),
                    esc_html__('Posted in', 'aqualuxe'),
                    $categories_list
                );
            }
        }

        if ($args['show_comments'] && comments_open()) {
            $meta_items .= sprintf(
                '<div class="%1$s">
                    <a href="%2$s">%3$s</a>
                </div>',
                esc_attr($args['comments_class']),
                esc_url(get_comments_link()),
                esc_html(get_comments_number_text(__('No Comments', 'aqualuxe'), __('1 Comment', 'aqualuxe'), __('% Comments', 'aqualuxe')))
            );
        }

        if ($meta_items) {
            $html = sprintf(
                '<%1$s class="%2$s">%3$s</%1$s>',
                $args['container'],
                esc_attr($args['container_class']),
                $meta_items
            );
        }

        if ($args['echo']) {
            echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        } else {
            return $html;
        }
    }
}

if (!function_exists('aqualuxe_post_thumbnail_with_overlay')) {
    /**
     * Display post thumbnail with overlay
     *
     * @param array $args Arguments for controlling the post thumbnail display.
     * @return void
     */
    function aqualuxe_post_thumbnail_with_overlay($args = array()) {
        $defaults = array(
            'container_class' => 'post-thumbnail-wrapper relative overflow-hidden rounded-lg mb-4',
            'thumbnail_class' => 'w-full h-auto transition-transform duration-300 hover:scale-105',
            'overlay_class'   => 'absolute inset-0 bg-dark bg-opacity-30 transition-opacity duration-300 opacity-0 hover:opacity-100 flex items-center justify-center',
            'button_class'    => 'btn btn-primary',
            'button_text'     => __('Read More', 'aqualuxe'),
            'size'            => 'aqualuxe-card',
            'echo'            => true,
        );

        $args = wp_parse_args($args, $defaults);
        $html = '';

        if (has_post_thumbnail()) {
            $html = sprintf(
                '<div class="%1$s">
                    <a href="%2$s">
                        %3$s
                        <div class="%4$s">
                            <span class="%5$s">%6$s</span>
                        </div>
                    </a>
                </div>',
                esc_attr($args['container_class']),
                esc_url(get_permalink()),
                get_the_post_thumbnail(
                    null,
                    $args['size'],
                    array(
                        'class' => $args['thumbnail_class'],
                        'alt'   => the_title_attribute(array('echo' => false)),
                    )
                ),
                esc_attr($args['overlay_class']),
                esc_attr($args['button_class']),
                esc_html($args['button_text'])
            );
        }

        if ($args['echo']) {
            echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        } else {
            return $html;
        }
    }
}

if (!function_exists('aqualuxe_get_sidebar_position')) {
    /**
     * Get the sidebar position based on theme options and context
     *
     * @return string Sidebar position ('left', 'right', or 'none')
     */
    function aqualuxe_get_sidebar_position() {
        $position = get_theme_mod('aqualuxe_sidebar_position', 'right');

        // Check if we're on a WooCommerce page
        if (function_exists('is_woocommerce') && is_woocommerce()) {
            // For single product pages, don't show sidebar
            if (is_product()) {
                $position = 'none';
            }
        }

        // For full-width page template
        if (is_page_template('templates/full-width.php')) {
            $position = 'none';
        }

        return $position;
    }
}

if (!function_exists('aqualuxe_content_classes')) {
    /**
     * Get the content column classes based on sidebar position
     *
     * @return string CSS classes for the content column
     */
    function aqualuxe_content_classes() {
        $sidebar_position = aqualuxe_get_sidebar_position();
        $classes = 'site-content';

        if ($sidebar_position === 'none') {
            $classes .= ' w-full';
        } else {
            $classes .= ' w-full lg:w-2/3';
        }

        return $classes;
    }
}

if (!function_exists('aqualuxe_sidebar_classes')) {
    /**
     * Get the sidebar column classes based on sidebar position
     *
     * @return string CSS classes for the sidebar column
     */
    function aqualuxe_sidebar_classes() {
        $sidebar_position = aqualuxe_get_sidebar_position();
        $classes = 'site-sidebar w-full lg:w-1/3';

        if ($sidebar_position === 'left') {
            $classes .= ' lg:order-first';
        }

        return $classes;
    }
}

if (!function_exists('aqualuxe_get_related_products')) {
    /**
     * Get related products based on categories and tags
     *
     * @param int   $product_id      The product ID.
     * @param int   $related_count   Number of related products to return.
     * @param array $exclude_ids     Product IDs to exclude.
     * @return array Array of product IDs
     */
    function aqualuxe_get_related_products($product_id, $related_count = 4, $exclude_ids = array()) {
        if (!function_exists('wc_get_product')) {
            return array();
        }

        $product = wc_get_product($product_id);
        
        if (!$product) {
            return array();
        }

        // Get categories and tags
        $categories = wp_get_post_terms($product_id, 'product_cat', array('fields' => 'ids'));
        $tags = wp_get_post_terms($product_id, 'product_tag', array('fields' => 'ids'));
        
        // Add the product ID to exclude
        $exclude_ids[] = $product_id;

        $args = array(
            'post_type'      => 'product',
            'posts_per_page' => $related_count,
            'post_status'    => 'publish',
            'post__not_in'   => $exclude_ids,
            'tax_query'      => array(
                'relation' => 'OR',
            ),
        );

        if (!empty($categories)) {
            $args['tax_query'][] = array(
                'taxonomy' => 'product_cat',
                'field'    => 'term_id',
                'terms'    => $categories,
            );
        }

        if (!empty($tags)) {
            $args['tax_query'][] = array(
                'taxonomy' => 'product_tag',
                'field'    => 'term_id',
                'terms'    => $tags,
            );
        }

        $related_query = new WP_Query($args);
        $related_products = array();

        if ($related_query->have_posts()) {
            while ($related_query->have_posts()) {
                $related_query->the_post();
                $related_products[] = get_the_ID();
            }
        }

        wp_reset_postdata();

        return $related_products;
    }
}

if (!function_exists('aqualuxe_get_fish_species')) {
    /**
     * Get all fish species terms
     *
     * @param array $args Arguments for get_terms().
     * @return array Array of term objects
     */
    function aqualuxe_get_fish_species($args = array()) {
        $defaults = array(
            'taxonomy'   => 'fish_species',
            'hide_empty' => true,
        );

        $args = wp_parse_args($args, $defaults);
        $terms = get_terms($args);

        return $terms;
    }
}

if (!function_exists('aqualuxe_get_water_types')) {
    /**
     * Get all water type terms
     *
     * @param array $args Arguments for get_terms().
     * @return array Array of term objects
     */
    function aqualuxe_get_water_types($args = array()) {
        $defaults = array(
            'taxonomy'   => 'water_type',
            'hide_empty' => true,
        );

        $args = wp_parse_args($args, $defaults);
        $terms = get_terms($args);

        return $terms;
    }
}

if (!function_exists('aqualuxe_get_services')) {
    /**
     * Get services
     *
     * @param array $args Arguments for WP_Query.
     * @return WP_Query
     */
    function aqualuxe_get_services($args = array()) {
        $defaults = array(
            'post_type'      => 'aqualuxe_service',
            'posts_per_page' => 6,
            'post_status'    => 'publish',
        );

        $args = wp_parse_args($args, $defaults);
        $query = new WP_Query($args);

        return $query;
    }
}

if (!function_exists('aqualuxe_get_events')) {
    /**
     * Get events
     *
     * @param array $args Arguments for WP_Query.
     * @return WP_Query
     */
    function aqualuxe_get_events($args = array()) {
        $defaults = array(
            'post_type'      => 'aqualuxe_event',
            'posts_per_page' => 6,
            'post_status'    => 'publish',
        );

        $args = wp_parse_args($args, $defaults);
        $query = new WP_Query($args);

        return $query;
    }
}

if (!function_exists('aqualuxe_get_testimonials')) {
    /**
     * Get testimonials
     *
     * @param array $args Arguments for WP_Query.
     * @return WP_Query
     */
    function aqualuxe_get_testimonials($args = array()) {
        $defaults = array(
            'post_type'      => 'aqualuxe_testimonial',
            'posts_per_page' => 6,
            'post_status'    => 'publish',
        );

        $args = wp_parse_args($args, $defaults);
        $query = new WP_Query($args);

        return $query;
    }
}

if (!function_exists('aqualuxe_get_featured_products')) {
    /**
     * Get featured products
     *
     * @param array $args Arguments for WP_Query.
     * @return WP_Query
     */
    function aqualuxe_get_featured_products($args = array()) {
        if (!function_exists('wc_get_product')) {
            return new WP_Query();
        }

        $defaults = array(
            'post_type'      => 'product',
            'posts_per_page' => 8,
            'post_status'    => 'publish',
            'tax_query'      => array(
                array(
                    'taxonomy' => 'product_visibility',
                    'field'    => 'name',
                    'terms'    => 'featured',
                    'operator' => 'IN',
                ),
            ),
        );

        $args = wp_parse_args($args, $defaults);
        $query = new WP_Query($args);

        return $query;
    }
}

if (!function_exists('aqualuxe_get_new_products')) {
    /**
     * Get new products
     *
     * @param array $args Arguments for WP_Query.
     * @return WP_Query
     */
    function aqualuxe_get_new_products($args = array()) {
        if (!function_exists('wc_get_product')) {
            return new WP_Query();
        }

        $defaults = array(
            'post_type'      => 'product',
            'posts_per_page' => 8,
            'post_status'    => 'publish',
            'orderby'        => 'date',
            'order'          => 'DESC',
        );

        $args = wp_parse_args($args, $defaults);
        $query = new WP_Query($args);

        return $query;
    }
}

if (!function_exists('aqualuxe_get_sale_products')) {
    /**
     * Get sale products
     *
     * @param array $args Arguments for WP_Query.
     * @return WP_Query
     */
    function aqualuxe_get_sale_products($args = array()) {
        if (!function_exists('wc_get_product')) {
            return new WP_Query();
        }

        $defaults = array(
            'post_type'      => 'product',
            'posts_per_page' => 8,
            'post_status'    => 'publish',
            'meta_query'     => array(
                'relation' => 'OR',
                array(
                    'key'     => '_sale_price',
                    'value'   => 0,
                    'compare' => '>',
                    'type'    => 'NUMERIC',
                ),
                array(
                    'key'     => '_min_variation_sale_price',
                    'value'   => 0,
                    'compare' => '>',
                    'type'    => 'NUMERIC',
                ),
            ),
        );

        $args = wp_parse_args($args, $defaults);
        $query = new WP_Query($args);

        return $query;
    }
}

if (!function_exists('aqualuxe_get_product_categories')) {
    /**
     * Get product categories
     *
     * @param array $args Arguments for get_terms().
     * @return array Array of term objects
     */
    function aqualuxe_get_product_categories($args = array()) {
        if (!function_exists('wc_get_product')) {
            return array();
        }

        $defaults = array(
            'taxonomy'   => 'product_cat',
            'hide_empty' => true,
            'number'     => 6,
            'orderby'    => 'count',
            'order'      => 'DESC',
        );

        $args = wp_parse_args($args, $defaults);
        $terms = get_terms($args);

        return $terms;
    }
}

if (!function_exists('aqualuxe_get_container_classes')) {
    /**
     * Get container classes based on theme options
     *
     * @return string CSS classes for the container
     */
    function aqualuxe_get_container_classes() {
        $container_width = get_theme_mod('aqualuxe_container_width', '1280');
        $classes = 'container mx-auto px-4';

        if ($container_width !== '1280') {
            $classes .= ' max-w-[' . esc_attr($container_width) . 'px]';
        }

        return $classes;
    }
}

if (!function_exists('aqualuxe_get_copyright_text')) {
    /**
     * Get copyright text with year replacement
     *
     * @return string Formatted copyright text
     */
    function aqualuxe_get_copyright_text() {
        $copyright = get_theme_mod('aqualuxe_copyright_text', sprintf(__('© %s AquaLuxe. All rights reserved.', 'aqualuxe'), date('Y')));
        
        // Replace {year} placeholder with current year
        $copyright = str_replace('{year}', date('Y'), $copyright);
        
        return $copyright;
    }
}

if (!function_exists('aqualuxe_get_footer_logo')) {
    /**
     * Get footer logo URL
     *
     * @return string URL of the footer logo or main logo
     */
    function aqualuxe_get_footer_logo() {
        $footer_logo = get_theme_mod('aqualuxe_footer_logo', '');
        
        if ($footer_logo) {
            return $footer_logo;
        }
        
        // Fallback to main logo
        $custom_logo_id = get_theme_mod('custom_logo');
        if ($custom_logo_id) {
            $logo = wp_get_attachment_image_src($custom_logo_id, 'full');
            if ($logo) {
                return $logo[0];
            }
        }
        
        return '';
    }
}

if (!function_exists('aqualuxe_get_blog_layout')) {
    /**
     * Get blog layout based on theme options
     *
     * @return string Blog layout ('grid', 'list', or 'masonry')
     */
    function aqualuxe_get_blog_layout() {
        return get_theme_mod('aqualuxe_blog_layout', 'grid');
    }
}

if (!function_exists('aqualuxe_get_shop_layout')) {
    /**
     * Get shop layout based on theme options
     *
     * @return string Shop layout ('grid', 'list', or 'masonry')
     */
    function aqualuxe_get_shop_layout() {
        return get_theme_mod('aqualuxe_shop_layout', 'grid');
    }
}

if (!function_exists('aqualuxe_is_blog')) {
    /**
     * Check if the current page is a blog-related page
     *
     * @return bool True if blog page, false otherwise
     */
    function aqualuxe_is_blog() {
        return (is_home() || is_archive() || is_singular('post') || is_search());
    }
}

if (!function_exists('aqualuxe_is_shop')) {
    /**
     * Check if the current page is a shop-related page
     *
     * @return bool True if shop page, false otherwise
     */
    function aqualuxe_is_shop() {
        if (!function_exists('is_woocommerce')) {
            return false;
        }
        
        return (is_woocommerce() || is_shop() || is_product_category() || is_product_tag() || is_product());
    }
}

if (!function_exists('aqualuxe_get_post_card_classes')) {
    /**
     * Get post card classes based on blog layout
     *
     * @return string CSS classes for post cards
     */
    function aqualuxe_get_post_card_classes() {
        $layout = aqualuxe_get_blog_layout();
        $classes = 'post-card bg-white dark:bg-dark-light rounded-lg overflow-hidden shadow-soft transition-shadow duration-300 hover:shadow-elegant';
        
        if ($layout === 'list') {
            $classes .= ' flex flex-col md:flex-row';
        } elseif ($layout === 'masonry') {
            $classes .= ' masonry-item';
        }
        
        return $classes;
    }
}

if (!function_exists('aqualuxe_get_product_card_classes')) {
    /**
     * Get product card classes based on shop layout
     *
     * @return string CSS classes for product cards
     */
    function aqualuxe_get_product_card_classes() {
        $layout = aqualuxe_get_shop_layout();
        $classes = 'product-card bg-white dark:bg-dark-light rounded-lg overflow-hidden shadow-soft transition-shadow duration-300 hover:shadow-elegant';
        
        if ($layout === 'list') {
            $classes .= ' flex flex-col md:flex-row';
        } elseif ($layout === 'masonry') {
            $classes .= ' masonry-item';
        }
        
        return $classes;
    }
}

if (!function_exists('aqualuxe_get_posts_wrapper_classes')) {
    /**
     * Get posts wrapper classes based on blog layout
     *
     * @return string CSS classes for posts wrapper
     */
    function aqualuxe_get_posts_wrapper_classes() {
        $layout = aqualuxe_get_blog_layout();
        $classes = 'posts-wrapper';
        
        if ($layout === 'grid') {
            $classes .= ' grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6';
        } elseif ($layout === 'list') {
            $classes .= ' space-y-6';
        } elseif ($layout === 'masonry') {
            $classes .= ' masonry-grid';
        }
        
        return $classes;
    }
}

if (!function_exists('aqualuxe_get_products_wrapper_classes')) {
    /**
     * Get products wrapper classes based on shop layout
     *
     * @return string CSS classes for products wrapper
     */
    function aqualuxe_get_products_wrapper_classes() {
        $layout = aqualuxe_get_shop_layout();
        $columns = get_theme_mod('aqualuxe_products_per_row', 3);
        $classes = 'products-wrapper';
        
        if ($layout === 'grid') {
            $classes .= ' grid grid-cols-1 md:grid-cols-2 lg:grid-cols-' . esc_attr($columns) . ' gap-6';
        } elseif ($layout === 'list') {
            $classes .= ' space-y-6';
        } elseif ($layout === 'masonry') {
            $classes .= ' masonry-grid';
        }
        
        return $classes;
    }
}