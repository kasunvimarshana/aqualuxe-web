<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

/**
 * Add a custom walker class for the primary menu
 */
class AquaLuxe_Walker_Nav_Menu extends Walker_Nav_Menu {
    /**
     * Starts the element output.
     */
    public function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
        $indent = ($depth) ? str_repeat("\t", $depth) : '';

        $classes = empty($item->classes) ? array() : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;

        // Add custom classes based on depth
        if ($depth === 0) {
            $classes[] = 'relative group';
        } else {
            $classes[] = 'w-full';
        }

        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args, $depth));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';

        $id = apply_filters('nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args, $depth);
        $id = $id ? ' id="' . esc_attr($id) . '"' : '';

        $output .= $indent . '<li' . $id . $class_names . '>';

        $atts = array();
        $atts['title']  = !empty($item->attr_title) ? $item->attr_title : '';
        $atts['target'] = !empty($item->target) ? $item->target : '';
        $atts['rel']    = !empty($item->xfn) ? $item->xfn : '';
        $atts['href']   = !empty($item->url) ? $item->url : '';

        // Add custom attributes based on depth
        if ($depth === 0) {
            $atts['class'] = 'block py-2 px-4 text-white hover:text-teal-200 transition-colors';
        } else {
            $atts['class'] = 'block py-2 px-4 text-gray-800 hover:text-teal-600 hover:bg-gray-100 transition-colors';
        }

        $atts = apply_filters('nav_menu_link_attributes', $atts, $item, $args, $depth);

        $attributes = '';
        foreach ($atts as $attr => $value) {
            if (!empty($value)) {
                $value = ('href' === $attr) ? esc_url($value) : esc_attr($value);
                $attributes .= ' ' . $attr . '="' . $value . '"';
            }
        }

        $title = apply_filters('the_title', $item->title, $item->ID);
        $title = apply_filters('nav_menu_item_title', $title, $item, $args, $depth);

        $item_output = $args->before;
        $item_output .= '<a' . $attributes . '>';
        $item_output .= $args->link_before . $title . $args->link_after;
        $item_output .= '</a>';
        $item_output .= $args->after;

        // Add dropdown toggle for items with children
        if ($args->walker->has_children) {
            if ($depth === 0) {
                $item_output .= '<button class="dropdown-toggle ml-1 text-white focus:outline-none" aria-expanded="false">';
                $item_output .= '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">';
                $item_output .= '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />';
                $item_output .= '</svg>';
                $item_output .= '</button>';
                
                // Add dropdown container
                $item_output .= '<div class="submenu-wrapper hidden group-hover:block absolute left-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50">';
                $item_output .= '<ul class="py-1" role="menu" aria-orientation="vertical">';
            } else {
                $item_output .= '<button class="dropdown-toggle ml-1 text-gray-800 focus:outline-none" aria-expanded="false">';
                $item_output .= '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">';
                $item_output .= '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />';
                $item_output .= '</svg>';
                $item_output .= '</button>';
                
                // Add nested dropdown container
                $item_output .= '<ul class="submenu hidden pl-4">';
            }
        }

        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }

    /**
     * Ends the element output.
     */
    public function end_el(&$output, $item, $depth = 0, $args = array()) {
        if ($args->walker->has_children) {
            if ($depth === 0) {
                $output .= '</ul></div>';
            } else {
                $output .= '</ul>';
            }
        }
        $output .= "</li>\n";
    }
}

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function aqualuxe_body_classes($classes) {
    // Add a class if there is a custom header.
    if (has_header_image()) {
        $classes[] = 'has-header-image';
    }

    // Add a class if sidebar is used.
    if (is_active_sidebar('sidebar-1') && !is_page_template('templates/full-width.php')) {
        $classes[] = 'has-sidebar';
    } else {
        $classes[] = 'no-sidebar';
    }

    // Add a class for the color scheme
    $color_scheme = get_theme_mod('aqualuxe_color_scheme', 'light');
    $classes[] = 'color-scheme-' . $color_scheme;

    return $classes;
}
add_filter('body_class', 'aqualuxe_body_classes');

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function aqualuxe_pingback_header() {
    if (is_singular() && pings_open()) {
        printf('<link rel="pingback" href="%s">', esc_url(get_bloginfo('pingback_url')));
    }
}
add_action('wp_head', 'aqualuxe_pingback_header');

/**
 * Implement custom breadcrumbs
 */
function aqualuxe_breadcrumbs() {
    // Settings
    $separator = '<svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mx-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>';
    $home_title = __('Home', 'aqualuxe');

    // Get the query & post information
    global $post, $wp_query;

    // Do not display on the homepage
    if (is_front_page()) {
        return;
    }

    // Build the breadcrumbs
    echo '<nav class="breadcrumbs flex items-center text-sm" aria-label="' . esc_attr__('Breadcrumb', 'aqualuxe') . '">';

    // Home page
    echo '<a href="' . esc_url(home_url('/')) . '" class="hover:text-white transition-colors">' . esc_html($home_title) . '</a>';

    if (is_home()) {
        // Blog page
        echo $separator;
        echo esc_html__('Blog', 'aqualuxe');
    } elseif (is_category()) {
        // Category page
        echo $separator;
        echo esc_html__('Category: ', 'aqualuxe') . single_cat_title('', false);
    } elseif (is_tax()) {
        // Taxonomy page
        echo $separator;
        $term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
        echo esc_html($term->name);
    } elseif (is_tag()) {
        // Tag page
        echo $separator;
        echo esc_html__('Tag: ', 'aqualuxe') . single_tag_title('', false);
    } elseif (is_author()) {
        // Author page
        echo $separator;
        echo esc_html__('Author: ', 'aqualuxe') . get_the_author();
    } elseif (is_year()) {
        // Year archive
        echo $separator;
        echo esc_html(get_the_date('Y'));
    } elseif (is_month()) {
        // Month archive
        echo $separator;
        echo esc_html(get_the_date('F Y'));
    } elseif (is_day()) {
        // Day archive
        echo $separator;
        echo esc_html(get_the_date('F j, Y'));
    } elseif (is_post_type_archive()) {
        // Post type archive
        echo $separator;
        echo esc_html(post_type_archive_title('', false));
    } elseif (is_single()) {
        // Single post
        if ('post' != get_post_type()) {
            // Custom post type
            $post_type = get_post_type_object(get_post_type());
            echo $separator;
            echo '<a href="' . esc_url(get_post_type_archive_link(get_post_type())) . '" class="hover:text-white transition-colors">' . esc_html($post_type->labels->name) . '</a>';
            echo $separator;
            echo esc_html(get_the_title());
        } else {
            // Standard post
            echo $separator;
            $categories = get_the_category();
            if ($categories) {
                $category = $categories[0];
                echo '<a href="' . esc_url(get_category_link($category->term_id)) . '" class="hover:text-white transition-colors">' . esc_html($category->name) . '</a>';
            }
            echo $separator;
            echo esc_html(get_the_title());
        }
    } elseif (is_page()) {
        // Standard page
        if ($post->post_parent) {
            // If child page, get parents
            $ancestors = get_post_ancestors($post->ID);
            $ancestors = array_reverse($ancestors);

            // Parent page loop
            foreach ($ancestors as $ancestor) {
                echo $separator;
                echo '<a href="' . esc_url(get_permalink($ancestor)) . '" class="hover:text-white transition-colors">' . esc_html(get_the_title($ancestor)) . '</a>';
            }
        }
        echo $separator;
        echo esc_html(get_the_title());
    } elseif (is_search()) {
        // Search results page
        echo $separator;
        echo esc_html__('Search results for: ', 'aqualuxe') . get_search_query();
    } elseif (is_404()) {
        // 404 page
        echo $separator;
        echo esc_html__('404: Page not found', 'aqualuxe');
    }

    echo '</nav>';
}

/**
 * Get a darker or lighter version of a color
 *
 * @param string $hex Hex color code
 * @param int $steps Steps to darken or lighten (negative for darker, positive for lighter)
 * @return string
 */
function aqualuxe_adjust_brightness($hex, $steps) {
    // Remove # if present
    $hex = ltrim($hex, '#');

    // Convert to RGB
    $r = hexdec(substr($hex, 0, 2));
    $g = hexdec(substr($hex, 2, 2));
    $b = hexdec(substr($hex, 4, 2));

    // Adjust color
    $r = max(0, min(255, $r + $steps));
    $g = max(0, min(255, $g + $steps));
    $b = max(0, min(255, $b + $steps));

    // Convert back to hex
    return sprintf('#%02x%02x%02x', $r, $g, $b);
}

/**
 * Generate dynamic CSS based on customizer settings
 */
function aqualuxe_dynamic_css() {
    $primary_color = get_theme_mod('aqualuxe_primary_color', '#0891b2'); // Default teal-600
    $secondary_color = get_theme_mod('aqualuxe_secondary_color', '#1e40af'); // Default blue-800
    
    // Calculate darker and lighter versions
    $primary_dark = aqualuxe_adjust_brightness($primary_color, -30);
    $primary_light = aqualuxe_adjust_brightness($primary_color, 30);
    $secondary_dark = aqualuxe_adjust_brightness($secondary_color, -30);
    $secondary_light = aqualuxe_adjust_brightness($secondary_color, 30);
    
    $css = "
        :root {
            --color-primary: {$primary_color};
            --color-primary-dark: {$primary_dark};
            --color-primary-light: {$primary_light};
            --color-secondary: {$secondary_color};
            --color-secondary-dark: {$secondary_dark};
            --color-secondary-light: {$secondary_light};
        }
        
        .bg-primary { background-color: var(--color-primary); }
        .bg-primary-dark { background-color: var(--color-primary-dark); }
        .bg-primary-light { background-color: var(--color-primary-light); }
        .bg-secondary { background-color: var(--color-secondary); }
        .bg-secondary-dark { background-color: var(--color-secondary-dark); }
        .bg-secondary-light { background-color: var(--color-secondary-light); }
        
        .text-primary { color: var(--color-primary); }
        .text-primary-dark { color: var(--color-primary-dark); }
        .text-primary-light { color: var(--color-primary-light); }
        .text-secondary { color: var(--color-secondary); }
        .text-secondary-dark { color: var(--color-secondary-dark); }
        .text-secondary-light { color: var(--color-secondary-light); }
        
        .border-primary { border-color: var(--color-primary); }
        .border-primary-dark { border-color: var(--color-primary-dark); }
        .border-primary-light { border-color: var(--color-primary-light); }
        .border-secondary { border-color: var(--color-secondary); }
        .border-secondary-dark { border-color: var(--color-secondary-dark); }
        .border-secondary-light { border-color: var(--color-secondary-light); }
        
        .btn-primary {
            background-color: var(--color-primary);
            color: white;
        }
        .btn-primary:hover {
            background-color: var(--color-primary-dark);
        }
        
        .btn-secondary {
            background-color: var(--color-secondary);
            color: white;
        }
        .btn-secondary:hover {
            background-color: var(--color-secondary-dark);
        }
    ";
    
    return $css;
}

/**
 * Output dynamic CSS
 */
function aqualuxe_enqueue_dynamic_css() {
    $css = aqualuxe_dynamic_css();
    wp_add_inline_style('aqualuxe-style', $css);
}
add_action('wp_enqueue_scripts', 'aqualuxe_enqueue_dynamic_css', 20);

/**
 * Add schema.org structured data
 */
function aqualuxe_schema_org() {
    // Default website schema
    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'WebSite',
        'name' => get_bloginfo('name'),
        'url' => home_url(),
        'potentialAction' => array(
            '@type' => 'SearchAction',
            'target' => home_url('/?s={search_term_string}'),
            'query-input' => 'required name=search_term_string'
        )
    );
    
    // Organization schema
    $organization_schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'Organization',
        'name' => get_bloginfo('name'),
        'url' => home_url(),
        'logo' => get_theme_mod('aqualuxe_organization_logo', get_template_directory_uri() . '/assets/images/logo.png'),
        'contactPoint' => array(
            '@type' => 'ContactPoint',
            'telephone' => get_theme_mod('aqualuxe_phone', '+94 123 456 7890'),
            'contactType' => 'customer service',
            'areaServed' => get_theme_mod('aqualuxe_area_served', 'Worldwide'),
            'availableLanguage' => array('English')
        ),
        'sameAs' => array(
            get_theme_mod('aqualuxe_facebook', '#'),
            get_theme_mod('aqualuxe_twitter', '#'),
            get_theme_mod('aqualuxe_instagram', '#'),
            get_theme_mod('aqualuxe_youtube', '#')
        )
    );
    
    // Add address if available
    $street = get_theme_mod('aqualuxe_street_address', '123 Aqua Lane');
    $city = get_theme_mod('aqualuxe_city', 'Marine City');
    $region = get_theme_mod('aqualuxe_region', 'Colombo');
    $postal = get_theme_mod('aqualuxe_postal_code', '10000');
    $country = get_theme_mod('aqualuxe_country', 'Sri Lanka');
    
    if ($street && $city) {
        $organization_schema['address'] = array(
            '@type' => 'PostalAddress',
            'streetAddress' => $street,
            'addressLocality' => $city,
            'addressRegion' => $region,
            'postalCode' => $postal,
            'addressCountry' => $country
        );
    }
    
    // Output schema based on page type
    if (is_front_page()) {
        echo '<script type="application/ld+json">' . wp_json_encode($schema) . '</script>';
        echo '<script type="application/ld+json">' . wp_json_encode($organization_schema) . '</script>';
    } elseif (is_singular('post')) {
        // Article schema for blog posts
        $article_schema = array(
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => get_the_title(),
            'url' => get_permalink(),
            'datePublished' => get_the_date('c'),
            'dateModified' => get_the_modified_date('c'),
            'author' => array(
                '@type' => 'Person',
                'name' => get_the_author()
            ),
            'publisher' => array(
                '@type' => 'Organization',
                'name' => get_bloginfo('name'),
                'logo' => array(
                    '@type' => 'ImageObject',
                    'url' => get_theme_mod('aqualuxe_organization_logo', get_template_directory_uri() . '/assets/images/logo.png')
                )
            )
        );
        
        // Add featured image if available
        if (has_post_thumbnail()) {
            $article_schema['image'] = array(
                '@type' => 'ImageObject',
                'url' => get_the_post_thumbnail_url(null, 'full'),
                'width' => '1200',
                'height' => '630'
            );
        }
        
        echo '<script type="application/ld+json">' . wp_json_encode($article_schema) . '</script>';
    } elseif (is_singular('product') && class_exists('WooCommerce')) {
        // Product schema for WooCommerce products
        global $product;
        
        if ($product) {
            $product_schema = array(
                '@context' => 'https://schema.org',
                '@type' => 'Product',
                'name' => $product->get_name(),
                'description' => wp_strip_all_tags($product->get_short_description() ? $product->get_short_description() : $product->get_description()),
                'sku' => $product->get_sku(),
                'brand' => array(
                    '@type' => 'Brand',
                    'name' => get_bloginfo('name')
                ),
                'offers' => array(
                    '@type' => 'Offer',
                    'price' => $product->get_price(),
                    'priceCurrency' => get_woocommerce_currency(),
                    'availability' => $product->is_in_stock() ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
                    'url' => get_permalink(),
                    'priceValidUntil' => date('Y-12-31', strtotime('+1 year'))
                )
            );
            
            // Add product image
            if (has_post_thumbnail()) {
                $product_schema['image'] = get_the_post_thumbnail_url(null, 'full');
            }
            
            // Add reviews if available
            if ($product->get_review_count() > 0) {
                $product_schema['aggregateRating'] = array(
                    '@type' => 'AggregateRating',
                    'ratingValue' => $product->get_average_rating(),
                    'reviewCount' => $product->get_review_count()
                );
            }
            
            echo '<script type="application/ld+json">' . wp_json_encode($product_schema) . '</script>';
        }
    }
}
add_action('wp_head', 'aqualuxe_schema_org');

/**
 * Add Open Graph meta tags
 */
function aqualuxe_open_graph() {
    global $post;
    
    if (is_singular()) {
        echo '<meta property="og:title" content="' . esc_attr(get_the_title()) . '" />' . "\n";
        echo '<meta property="og:type" content="article" />' . "\n";
        echo '<meta property="og:url" content="' . esc_url(get_permalink()) . '" />' . "\n";
        
        if (has_post_thumbnail()) {
            echo '<meta property="og:image" content="' . esc_url(get_the_post_thumbnail_url(null, 'full')) . '" />' . "\n";
        }
        
        echo '<meta property="og:site_name" content="' . esc_attr(get_bloginfo('name')) . '" />' . "\n";
        
        // Get post excerpt or content
        $description = '';
        if (has_excerpt()) {
            $description = wp_strip_all_tags(get_the_excerpt(), true);
        } else {
            $description = wp_trim_words(wp_strip_all_tags(get_the_content(), true), 30, '...');
        }
        
        echo '<meta property="og:description" content="' . esc_attr($description) . '" />' . "\n";
    } else {
        echo '<meta property="og:title" content="' . esc_attr(get_bloginfo('name')) . '" />' . "\n";
        echo '<meta property="og:type" content="website" />' . "\n";
        echo '<meta property="og:url" content="' . esc_url(home_url('/')) . '" />' . "\n";
        
        // Use site logo or default image
        $logo_id = get_theme_mod('custom_logo');
        if ($logo_id) {
            $logo_image = wp_get_attachment_image_src($logo_id, 'full');
            if ($logo_image) {
                echo '<meta property="og:image" content="' . esc_url($logo_image[0]) . '" />' . "\n";
            }
        }
        
        echo '<meta property="og:site_name" content="' . esc_attr(get_bloginfo('name')) . '" />' . "\n";
        echo '<meta property="og:description" content="' . esc_attr(get_bloginfo('description')) . '" />' . "\n";
    }
}
add_action('wp_head', 'aqualuxe_open_graph');

/**
 * Add Twitter Card meta tags
 */
function aqualuxe_twitter_card() {
    global $post;
    
    echo '<meta name="twitter:card" content="summary_large_image" />' . "\n";
    echo '<meta name="twitter:site" content="@' . esc_attr(get_theme_mod('aqualuxe_twitter_username', 'aqualuxe')) . '" />' . "\n";
    
    if (is_singular()) {
        echo '<meta name="twitter:title" content="' . esc_attr(get_the_title()) . '" />' . "\n";
        
        // Get post excerpt or content
        $description = '';
        if (has_excerpt()) {
            $description = wp_strip_all_tags(get_the_excerpt(), true);
        } else {
            $description = wp_trim_words(wp_strip_all_tags(get_the_content(), true), 30, '...');
        }
        
        echo '<meta name="twitter:description" content="' . esc_attr($description) . '" />' . "\n";
        
        if (has_post_thumbnail()) {
            echo '<meta name="twitter:image" content="' . esc_url(get_the_post_thumbnail_url(null, 'full')) . '" />' . "\n";
        }
    } else {
        echo '<meta name="twitter:title" content="' . esc_attr(get_bloginfo('name')) . '" />' . "\n";
        echo '<meta name="twitter:description" content="' . esc_attr(get_bloginfo('description')) . '" />' . "\n";
        
        // Use site logo or default image
        $logo_id = get_theme_mod('custom_logo');
        if ($logo_id) {
            $logo_image = wp_get_attachment_image_src($logo_id, 'full');
            if ($logo_image) {
                echo '<meta name="twitter:image" content="' . esc_url($logo_image[0]) . '" />' . "\n";
            }
        }
    }
}
add_action('wp_head', 'aqualuxe_twitter_card');