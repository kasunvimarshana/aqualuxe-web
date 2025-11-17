<?php
/**
 * Helper functions for AquaLuxe theme
 *
 * @package AquaLuxe
 */

// Prevent direct access to this file
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Check if WooCommerce is active
 *
 * @return bool
 */
function aqualuxe_is_woocommerce_active() {
    return class_exists('WooCommerce');
}

/**
 * Get theme option from customizer
 *
 * @param string $option Option name
 * @param mixed $default Default value
 * @return mixed
 */
function aqualuxe_get_option($option, $default = '') {
    $options = get_theme_mod('aqualuxe_options');
    return isset($options[$option]) ? $options[$option] : $default;
}

/**
 * Check if dark mode is enabled
 *
 * @return bool
 */
function aqualuxe_is_dark_mode() {
    // Check user preference from cookie
    if (isset($_COOKIE['aqualuxe_dark_mode']) && $_COOKIE['aqualuxe_dark_mode'] === 'true') {
        return true;
    }
    
    // Check theme default setting
    return aqualuxe_get_option('default_dark_mode', false);
}

/**
 * Get asset URL with version
 *
 * @param string $path Asset path
 * @return string
 */
function aqualuxe_asset($path) {
    // Get the asset manifest
    static $manifest = null;
    
    if ($manifest === null) {
        $manifest_path = AQUALUXE_DIR . 'assets/dist/mix-manifest.json';
        $manifest = file_exists($manifest_path) ? json_decode(file_get_contents($manifest_path), true) : [];
    }
    
    // Get versioned path
    $versioned_path = isset($manifest[$path]) ? $manifest[$path] : $path;
    
    return AQUALUXE_ASSETS_URI . ltrim($versioned_path, '/');
}

/**
 * Get SVG icon
 *
 * @param string $icon Icon name
 * @param array $args Icon arguments
 * @return string
 */
function aqualuxe_get_icon($icon, $args = []) {
    // Set defaults
    $defaults = [
        'class' => '',
        'size' => 24,
        'title' => '',
        'desc' => '',
    ];
    
    // Parse args
    $args = wp_parse_args($args, $defaults);
    
    // Icon path
    $icon_path = AQUALUXE_DIR . 'assets/dist/images/icons/' . $icon . '.svg';
    
    // Check if icon exists
    if (!file_exists($icon_path)) {
        return '';
    }
    
    // Get icon content
    $icon_content = file_get_contents($icon_path);
    
    // Add class
    if (!empty($args['class'])) {
        $icon_content = str_replace('<svg', '<svg class="' . esc_attr($args['class']) . '"', $icon_content);
    }
    
    // Add size
    $icon_content = str_replace('<svg', '<svg width="' . esc_attr($args['size']) . '" height="' . esc_attr($args['size']) . '"', $icon_content);
    
    // Add accessibility
    if (!empty($args['title']) || !empty($args['desc'])) {
        $unique_id = uniqid('icon-');
        $aria_labelledby = [];
        
        if (!empty($args['title'])) {
            $aria_labelledby[] = $unique_id . '-title';
        }
        
        if (!empty($args['desc'])) {
            $aria_labelledby[] = $unique_id . '-desc';
        }
        
        $icon_content = str_replace('<svg', '<svg aria-labelledby="' . esc_attr(implode(' ', $aria_labelledby)) . '" role="img"', $icon_content);
        
        $title_desc = '';
        
        if (!empty($args['title'])) {
            $title_desc .= '<title id="' . esc_attr($unique_id) . '-title">' . esc_html($args['title']) . '</title>';
        }
        
        if (!empty($args['desc'])) {
            $title_desc .= '<desc id="' . esc_attr($unique_id) . '-desc">' . esc_html($args['desc']) . '</desc>';
        }
        
        $icon_content = str_replace('<path', $title_desc . '<path', $icon_content);
    } else {
        $icon_content = str_replace('<svg', '<svg aria-hidden="true" focusable="false"', $icon_content);
    }
    
    return $icon_content;
}

/**
 * Get image URL by ID
 *
 * @param int $attachment_id Attachment ID
 * @param string $size Image size
 * @return string
 */
function aqualuxe_get_image_url($attachment_id, $size = 'full') {
    if (!$attachment_id) {
        return '';
    }
    
    $image = wp_get_attachment_image_src($attachment_id, $size);
    
    return $image ? $image[0] : '';
}

/**
 * Get image alt text by ID
 *
 * @param int $attachment_id Attachment ID
 * @return string
 */
function aqualuxe_get_image_alt($attachment_id) {
    if (!$attachment_id) {
        return '';
    }
    
    $alt = get_post_meta($attachment_id, '_wp_attachment_image_alt', true);
    
    return $alt ? $alt : '';
}

/**
 * Get post thumbnail with fallback
 *
 * @param int $post_id Post ID
 * @param string $size Image size
 * @param array $attr Image attributes
 * @return string
 */
function aqualuxe_get_post_thumbnail($post_id = null, $size = 'post-thumbnail', $attr = []) {
    if (null === $post_id) {
        $post_id = get_the_ID();
    }
    
    if (has_post_thumbnail($post_id)) {
        return get_the_post_thumbnail($post_id, $size, $attr);
    }
    
    // Fallback image
    $fallback_image = aqualuxe_get_option('fallback_image');
    
    if ($fallback_image) {
        return wp_get_attachment_image($fallback_image, $size, false, $attr);
    }
    
    // Default fallback
    $placeholder = '<div class="aqualuxe-placeholder" style="aspect-ratio: 16/9;"></div>';
    
    return $placeholder;
}

/**
 * Get excerpt with custom length
 *
 * @param int $length Excerpt length
 * @param int $post_id Post ID
 * @return string
 */
function aqualuxe_get_excerpt($length = 55, $post_id = null) {
    if (null === $post_id) {
        $post_id = get_the_ID();
    }
    
    $post = get_post($post_id);
    
    if (!$post) {
        return '';
    }
    
    if (has_excerpt($post_id)) {
        return wp_trim_words(get_the_excerpt($post_id), $length, '&hellip;');
    }
    
    $content = get_the_content('', false, $post_id);
    $content = apply_filters('the_content', $content);
    $content = str_replace(']]>', ']]&gt;', $content);
    
    return wp_trim_words($content, $length, '&hellip;');
}

/**
 * Get primary term for a post
 *
 * @param int $post_id Post ID
 * @param string $taxonomy Taxonomy name
 * @return WP_Term|false
 */
function aqualuxe_get_primary_term($post_id = null, $taxonomy = 'category') {
    if (null === $post_id) {
        $post_id = get_the_ID();
    }
    
    // Check for Yoast primary term
    if (function_exists('yoast_get_primary_term_id')) {
        $primary_term_id = yoast_get_primary_term_id($taxonomy, $post_id);
        
        if ($primary_term_id) {
            return get_term($primary_term_id, $taxonomy);
        }
    }
    
    // Get first term
    $terms = get_the_terms($post_id, $taxonomy);
    
    if (!$terms || is_wp_error($terms)) {
        return false;
    }
    
    return reset($terms);
}

/**
 * Get related posts
 *
 * @param int $post_id Post ID
 * @param int $count Number of posts
 * @param string $taxonomy Taxonomy name
 * @return array
 */
function aqualuxe_get_related_posts($post_id = null, $count = 3, $taxonomy = 'category') {
    if (null === $post_id) {
        $post_id = get_the_ID();
    }
    
    $terms = get_the_terms($post_id, $taxonomy);
    
    if (!$terms || is_wp_error($terms)) {
        return [];
    }
    
    $term_ids = wp_list_pluck($terms, 'term_id');
    
    $args = [
        'post_type' => get_post_type($post_id),
        'posts_per_page' => $count,
        'post__not_in' => [$post_id],
        'tax_query' => [
            [
                'taxonomy' => $taxonomy,
                'field' => 'term_id',
                'terms' => $term_ids,
            ],
        ],
    ];
    
    $query = new WP_Query($args);
    
    return $query->posts;
}

/**
 * Get social share links
 *
 * @param int $post_id Post ID
 * @return array
 */
function aqualuxe_get_social_share_links($post_id = null) {
    if (null === $post_id) {
        $post_id = get_the_ID();
    }
    
    $url = get_permalink($post_id);
    $title = get_the_title($post_id);
    $thumbnail = aqualuxe_get_image_url(get_post_thumbnail_id($post_id));
    
    $links = [
        'facebook' => [
            'url' => 'https://www.facebook.com/sharer/sharer.php?u=' . urlencode($url),
            'label' => __('Share on Facebook', 'aqualuxe'),
            'icon' => 'facebook',
        ],
        'twitter' => [
            'url' => 'https://twitter.com/intent/tweet?url=' . urlencode($url) . '&text=' . urlencode($title),
            'label' => __('Share on Twitter', 'aqualuxe'),
            'icon' => 'twitter',
        ],
        'linkedin' => [
            'url' => 'https://www.linkedin.com/shareArticle?mini=true&url=' . urlencode($url) . '&title=' . urlencode($title),
            'label' => __('Share on LinkedIn', 'aqualuxe'),
            'icon' => 'linkedin',
        ],
        'pinterest' => [
            'url' => 'https://pinterest.com/pin/create/button/?url=' . urlencode($url) . '&media=' . urlencode($thumbnail) . '&description=' . urlencode($title),
            'label' => __('Share on Pinterest', 'aqualuxe'),
            'icon' => 'pinterest',
        ],
        'email' => [
            'url' => 'mailto:?subject=' . urlencode($title) . '&body=' . urlencode($url),
            'label' => __('Share via Email', 'aqualuxe'),
            'icon' => 'email',
        ],
    ];
    
    return $links;
}

/**
 * Get breadcrumbs
 *
 * @return array
 */
function aqualuxe_get_breadcrumbs() {
    $breadcrumbs = [];
    
    // Home
    $breadcrumbs[] = [
        'title' => __('Home', 'aqualuxe'),
        'url' => home_url('/'),
    ];
    
    // WooCommerce
    if (aqualuxe_is_woocommerce_active() && (is_woocommerce() || is_cart() || is_checkout() || is_account_page())) {
        // Shop page
        $shop_page_id = wc_get_page_id('shop');
        
        if ($shop_page_id > 0) {
            $breadcrumbs[] = [
                'title' => get_the_title($shop_page_id),
                'url' => get_permalink($shop_page_id),
            ];
        }
        
        // Product category
        if (is_product_category()) {
            $current_term = get_queried_object();
            $taxonomy = 'product_cat';
            
            // Get parent terms
            $parent_terms = get_ancestors($current_term->term_id, $taxonomy, 'taxonomy');
            
            // Add parent terms
            foreach (array_reverse($parent_terms) as $parent_term_id) {
                $parent_term = get_term($parent_term_id, $taxonomy);
                
                $breadcrumbs[] = [
                    'title' => $parent_term->name,
                    'url' => get_term_link($parent_term),
                ];
            }
            
            // Add current term
            $breadcrumbs[] = [
                'title' => $current_term->name,
                'url' => get_term_link($current_term),
            ];
        }
        
        // Product tag
        elseif (is_product_tag()) {
            $current_term = get_queried_object();
            
            $breadcrumbs[] = [
                'title' => $current_term->name,
                'url' => get_term_link($current_term),
            ];
        }
        
        // Single product
        elseif (is_product()) {
            global $post;
            
            // Get product categories
            $terms = wc_get_product_terms($post->ID, 'product_cat', ['orderby' => 'parent', 'order' => 'DESC']);
            
            if ($terms) {
                $main_term = $terms[0];
                $term_ancestors = get_ancestors($main_term->term_id, 'product_cat');
                
                // Add parent terms
                foreach (array_reverse($term_ancestors) as $ancestor_id) {
                    $ancestor = get_term($ancestor_id, 'product_cat');
                    
                    $breadcrumbs[] = [
                        'title' => $ancestor->name,
                        'url' => get_term_link($ancestor),
                    ];
                }
                
                // Add main term
                $breadcrumbs[] = [
                    'title' => $main_term->name,
                    'url' => get_term_link($main_term),
                ];
            }
            
            // Add product
            $breadcrumbs[] = [
                'title' => get_the_title(),
                'url' => '',
            ];
        }
        
        // Cart
        elseif (is_cart()) {
            $breadcrumbs[] = [
                'title' => __('Cart', 'aqualuxe'),
                'url' => '',
            ];
        }
        
        // Checkout
        elseif (is_checkout()) {
            $breadcrumbs[] = [
                'title' => __('Checkout', 'aqualuxe'),
                'url' => '',
            ];
        }
        
        // Account
        elseif (is_account_page()) {
            $breadcrumbs[] = [
                'title' => __('My Account', 'aqualuxe'),
                'url' => '',
            ];
        }
    }
    
    // Blog
    elseif (is_home() || is_category() || is_tag() || is_date() || is_author() || is_single() && 'post' === get_post_type()) {
        // Blog page
        $blog_page_id = get_option('page_for_posts');
        
        if ($blog_page_id > 0) {
            $breadcrumbs[] = [
                'title' => get_the_title($blog_page_id),
                'url' => get_permalink($blog_page_id),
            ];
        }
        
        // Category
        if (is_category()) {
            $current_term = get_queried_object();
            
            // Get parent categories
            $parent_terms = get_ancestors($current_term->term_id, 'category', 'taxonomy');
            
            // Add parent categories
            foreach (array_reverse($parent_terms) as $parent_term_id) {
                $parent_term = get_term($parent_term_id, 'category');
                
                $breadcrumbs[] = [
                    'title' => $parent_term->name,
                    'url' => get_term_link($parent_term),
                ];
            }
            
            // Add current category
            $breadcrumbs[] = [
                'title' => $current_term->name,
                'url' => '',
            ];
        }
        
        // Tag
        elseif (is_tag()) {
            $current_term = get_queried_object();
            
            $breadcrumbs[] = [
                'title' => $current_term->name,
                'url' => '',
            ];
        }
        
        // Date
        elseif (is_date()) {
            if (is_year()) {
                $breadcrumbs[] = [
                    'title' => get_the_date('Y'),
                    'url' => '',
                ];
            } elseif (is_month()) {
                $breadcrumbs[] = [
                    'title' => get_the_date('F Y'),
                    'url' => '',
                ];
            } elseif (is_day()) {
                $breadcrumbs[] = [
                    'title' => get_the_date(),
                    'url' => '',
                ];
            }
        }
        
        // Author
        elseif (is_author()) {
            $author = get_queried_object();
            
            $breadcrumbs[] = [
                'title' => $author->display_name,
                'url' => '',
            ];
        }
        
        // Single post
        elseif (is_single()) {
            // Get post categories
            $categories = get_the_category();
            
            if ($categories) {
                $category = $categories[0];
                $category_ancestors = get_ancestors($category->term_id, 'category');
                
                // Add parent categories
                foreach (array_reverse($category_ancestors) as $ancestor_id) {
                    $ancestor = get_term($ancestor_id, 'category');
                    
                    $breadcrumbs[] = [
                        'title' => $ancestor->name,
                        'url' => get_term_link($ancestor),
                    ];
                }
                
                // Add main category
                $breadcrumbs[] = [
                    'title' => $category->name,
                    'url' => get_term_link($category),
                ];
            }
            
            // Add post
            $breadcrumbs[] = [
                'title' => get_the_title(),
                'url' => '',
            ];
        }
    }
    
    // Page
    elseif (is_page()) {
        global $post;
        
        // Get parent pages
        $parent_pages = get_post_ancestors($post);
        
        // Add parent pages
        foreach (array_reverse($parent_pages) as $parent_page_id) {
            $breadcrumbs[] = [
                'title' => get_the_title($parent_page_id),
                'url' => get_permalink($parent_page_id),
            ];
        }
        
        // Add current page
        $breadcrumbs[] = [
            'title' => get_the_title(),
            'url' => '',
        ];
    }
    
    // Search
    elseif (is_search()) {
        $breadcrumbs[] = [
            'title' => sprintf(__('Search Results for: %s', 'aqualuxe'), get_search_query()),
            'url' => '',
        ];
    }
    
    // 404
    elseif (is_404()) {
        $breadcrumbs[] = [
            'title' => __('Page Not Found', 'aqualuxe'),
            'url' => '',
        ];
    }
    
    return $breadcrumbs;
}

/**
 * Get pagination
 *
 * @param array $args Pagination arguments
 * @return array
 */
function aqualuxe_get_pagination($args = []) {
    global $wp_query;
    
    // Set defaults
    $defaults = [
        'total' => $wp_query->max_num_pages,
        'current' => max(1, get_query_var('paged')),
        'prev_text' => __('Previous', 'aqualuxe'),
        'next_text' => __('Next', 'aqualuxe'),
        'end_size' => 1,
        'mid_size' => 2,
    ];
    
    // Parse args
    $args = wp_parse_args($args, $defaults);
    
    // Get paginate links
    $links = paginate_links([
        'base' => str_replace(999999999, '%#%', esc_url(get_pagenum_link(999999999))),
        'format' => '?paged=%#%',
        'current' => $args['current'],
        'total' => $args['total'],
        'prev_text' => $args['prev_text'],
        'next_text' => $args['next_text'],
        'end_size' => $args['end_size'],
        'mid_size' => $args['mid_size'],
        'type' => 'array',
    ]);
    
    if (!$links) {
        return [];
    }
    
    $pagination = [];
    
    foreach ($links as $link) {
        $is_current = strpos($link, 'current') !== false;
        $is_prev = strpos($link, $args['prev_text']) !== false;
        $is_next = strpos($link, $args['next_text']) !== false;
        $is_dots = strpos($link, 'dots') !== false;
        
        // Get URL
        preg_match('/href=["\']([^"\']+)["\']/', $link, $url_match);
        $url = $url_match ? $url_match[1] : '';
        
        // Get text
        $text = strip_tags($link);
        
        $pagination[] = [
            'url' => $url,
            'text' => $text,
            'current' => $is_current,
            'prev' => $is_prev,
            'next' => $is_next,
            'dots' => $is_dots,
        ];
    }
    
    return $pagination;
}

/**
 * Get comment form fields
 *
 * @return array
 */
function aqualuxe_get_comment_form_fields() {
    $commenter = wp_get_current_commenter();
    $req = get_option('require_name_email');
    $aria_req = ($req ? ' aria-required="true"' : '');
    
    $fields = [
        'author' => '<div class="comment-form-author"><label for="author">' . __('Name', 'aqualuxe') . ($req ? ' <span class="required">*</span>' : '') . '</label><input id="author" name="author" type="text" value="' . esc_attr($commenter['comment_author']) . '" size="30" maxlength="245"' . $aria_req . ' /></div>',
        'email' => '<div class="comment-form-email"><label for="email">' . __('Email', 'aqualuxe') . ($req ? ' <span class="required">*</span>' : '') . '</label><input id="email" name="email" type="email" value="' . esc_attr($commenter['comment_author_email']) . '" size="30" maxlength="100" aria-describedby="email-notes"' . $aria_req . ' /></div>',
        'url' => '<div class="comment-form-url"><label for="url">' . __('Website', 'aqualuxe') . '</label><input id="url" name="url" type="url" value="' . esc_attr($commenter['comment_author_url']) . '" size="30" maxlength="200" /></div>',
        'cookies' => '<div class="comment-form-cookies-consent"><input id="wp-comment-cookies-consent" name="wp-comment-cookies-consent" type="checkbox" value="yes"' . (empty($commenter['comment_author_email']) ? '' : ' checked="checked"') . ' /><label for="wp-comment-cookies-consent">' . __('Save my name, email, and website in this browser for the next time I comment.', 'aqualuxe') . '</label></div>',
    ];
    
    return $fields;
}

/**
 * Get comment form defaults
 *
 * @return array
 */
function aqualuxe_get_comment_form_defaults() {
    $defaults = [
        'comment_field' => '<div class="comment-form-comment"><label for="comment">' . __('Comment', 'aqualuxe') . ' <span class="required">*</span></label><textarea id="comment" name="comment" cols="45" rows="8" maxlength="65525" required="required"></textarea></div>',
        'logged_in_as' => '',
        'title_reply' => __('Leave a Comment', 'aqualuxe'),
        'title_reply_before' => '<h3 id="reply-title" class="comment-reply-title">',
        'title_reply_after' => '</h3>',
        'class_submit' => 'submit button',
        'submit_button' => '<button name="%1$s" type="submit" id="%2$s" class="%3$s">%4$s</button>',
        'submit_field' => '<div class="form-submit">%1$s %2$s</div>',
    ];
    
    return $defaults;
}

/**
 * Sanitize HTML class
 *
 * @param string|array $class Class name or array of class names
 * @return string
 */
function aqualuxe_sanitize_html_class($class) {
    if (is_array($class)) {
        $class = implode(' ', $class);
    }
    
    $class = explode(' ', $class);
    $class = array_map('sanitize_html_class', $class);
    $class = implode(' ', $class);
    
    return $class;
}

/**
 * Get allowed HTML for wp_kses
 *
 * @param string $context Context
 * @return array
 */
function aqualuxe_get_allowed_html($context = 'default') {
    // Default allowed HTML
    $allowed_html = wp_kses_allowed_html('post');
    
    // Add SVG support
    if ($context === 'svg' || $context === 'all') {
        $allowed_html['svg'] = [
            'xmlns' => true,
            'width' => true,
            'height' => true,
            'viewbox' => true,
            'aria-hidden' => true,
            'aria-labelledby' => true,
            'role' => true,
            'focusable' => true,
            'class' => true,
            'fill' => true,
            'stroke' => true,
            'stroke-width' => true,
        ];
        
        $allowed_html['path'] = [
            'd' => true,
            'fill' => true,
            'stroke' => true,
            'stroke-width' => true,
            'stroke-linecap' => true,
            'stroke-linejoin' => true,
        ];
        
        $allowed_html['g'] = [
            'fill' => true,
            'stroke' => true,
            'stroke-width' => true,
        ];
        
        $allowed_html['circle'] = [
            'cx' => true,
            'cy' => true,
            'r' => true,
            'fill' => true,
            'stroke' => true,
            'stroke-width' => true,
        ];
        
        $allowed_html['rect'] = [
            'x' => true,
            'y' => true,
            'width' => true,
            'height' => true,
            'fill' => true,
            'stroke' => true,
            'stroke-width' => true,
        ];
        
        $allowed_html['line'] = [
            'x1' => true,
            'y1' => true,
            'x2' => true,
            'y2' => true,
            'fill' => true,
            'stroke' => true,
            'stroke-width' => true,
        ];
        
        $allowed_html['polyline'] = [
            'points' => true,
            'fill' => true,
            'stroke' => true,
            'stroke-width' => true,
        ];
        
        $allowed_html['polygon'] = [
            'points' => true,
            'fill' => true,
            'stroke' => true,
            'stroke-width' => true,
        ];
        
        $allowed_html['title'] = [
            'id' => true,
        ];
        
        $allowed_html['desc'] = [
            'id' => true,
        ];
    }
    
    // Add form elements
    if ($context === 'form' || $context === 'all') {
        $allowed_html['form'] = [
            'action' => true,
            'method' => true,
            'class' => true,
            'id' => true,
            'enctype' => true,
            'target' => true,
            'novalidate' => true,
        ];
        
        $allowed_html['input'] = [
            'type' => true,
            'name' => true,
            'value' => true,
            'class' => true,
            'id' => true,
            'placeholder' => true,
            'required' => true,
            'min' => true,
            'max' => true,
            'step' => true,
            'pattern' => true,
            'checked' => true,
            'disabled' => true,
            'readonly' => true,
            'autocomplete' => true,
            'aria-label' => true,
            'aria-describedby' => true,
            'aria-required' => true,
        ];
        
        $allowed_html['select'] = [
            'name' => true,
            'class' => true,
            'id' => true,
            'required' => true,
            'disabled' => true,
            'multiple' => true,
            'aria-label' => true,
            'aria-describedby' => true,
            'aria-required' => true,
        ];
        
        $allowed_html['option'] = [
            'value' => true,
            'selected' => true,
            'disabled' => true,
        ];
        
        $allowed_html['optgroup'] = [
            'label' => true,
            'disabled' => true,
        ];
        
        $allowed_html['textarea'] = [
            'name' => true,
            'class' => true,
            'id' => true,
            'rows' => true,
            'cols' => true,
            'placeholder' => true,
            'required' => true,
            'disabled' => true,
            'readonly' => true,
            'aria-label' => true,
            'aria-describedby' => true,
            'aria-required' => true,
        ];
        
        $allowed_html['button'] = [
            'type' => true,
            'name' => true,
            'value' => true,
            'class' => true,
            'id' => true,
            'disabled' => true,
            'aria-label' => true,
        ];
        
        $allowed_html['label'] = [
            'for' => true,
            'class' => true,
        ];
        
        $allowed_html['fieldset'] = [
            'class' => true,
            'id' => true,
            'disabled' => true,
        ];
        
        $allowed_html['legend'] = [
            'class' => true,
        ];
    }
    
    // Add data attributes
    if ($context === 'data' || $context === 'all') {
        foreach ($allowed_html as $tag => $attributes) {
            $allowed_html[$tag]['data-*'] = true;
        }
    }
    
    return $allowed_html;
}

/**
 * Get theme color mode
 *
 * @return string
 */
function aqualuxe_get_color_mode() {
    // Check user preference from cookie
    if (isset($_COOKIE['aqualuxe_color_mode'])) {
        return $_COOKIE['aqualuxe_color_mode'] === 'dark' ? 'dark' : 'light';
    }
    
    // Check theme default setting
    return aqualuxe_get_option('default_color_mode', 'light');
}

/**
 * Get theme color scheme
 *
 * @return array
 */
function aqualuxe_get_color_scheme() {
    $color_scheme = aqualuxe_get_option('color_scheme', 'default');
    
    $schemes = [
        'default' => [
            'primary' => '#0073aa',
            'secondary' => '#005177',
            'accent' => '#00c6ff',
            'dark' => '#111111',
            'light' => '#f8f9fa',
        ],
        'ocean' => [
            'primary' => '#1e88e5',
            'secondary' => '#0d47a1',
            'accent' => '#00b0ff',
            'dark' => '#263238',
            'light' => '#eceff1',
        ],
        'coral' => [
            'primary' => '#f44336',
            'secondary' => '#c62828',
            'accent' => '#ff8a80',
            'dark' => '#37474f',
            'light' => '#fafafa',
        ],
        'emerald' => [
            'primary' => '#4caf50',
            'secondary' => '#2e7d32',
            'accent' => '#69f0ae',
            'dark' => '#1b5e20',
            'light' => '#f1f8e9',
        ],
        'amethyst' => [
            'primary' => '#9c27b0',
            'secondary' => '#6a1b9a',
            'accent' => '#ea80fc',
            'dark' => '#4a148c',
            'light' => '#f3e5f5',
        ],
    ];
    
    return isset($schemes[$color_scheme]) ? $schemes[$color_scheme] : $schemes['default'];
}

/**
 * Get theme typography
 *
 * @return array
 */
function aqualuxe_get_typography() {
    $typography = aqualuxe_get_option('typography', 'default');
    
    $options = [
        'default' => [
            'body' => "'Open Sans', sans-serif",
            'heading' => "'Montserrat', sans-serif",
            'base_size' => '16px',
            'scale' => 1.25,
        ],
        'classic' => [
            'body' => "'Merriweather', serif",
            'heading' => "'Playfair Display', serif",
            'base_size' => '16px',
            'scale' => 1.33,
        ],
        'modern' => [
            'body' => "'Roboto', sans-serif",
            'heading' => "'Poppins', sans-serif",
            'base_size' => '16px',
            'scale' => 1.2,
        ],
        'minimal' => [
            'body' => "'Inter', sans-serif",
            'heading' => "'Inter', sans-serif",
            'base_size' => '16px',
            'scale' => 1.2,
        ],
        'elegant' => [
            'body' => "'Lora', serif",
            'heading' => "'Cormorant Garamond', serif",
            'base_size' => '17px',
            'scale' => 1.33,
        ],
    ];
    
    return isset($options[$typography]) ? $options[$typography] : $options['default'];
}

/**
 * Get theme layout
 *
 * @return string
 */
function aqualuxe_get_layout() {
    // Get default layout
    $default_layout = aqualuxe_get_option('default_layout', 'right-sidebar');
    
    // Get post/page specific layout
    $specific_layout = get_post_meta(get_the_ID(), '_aqualuxe_layout', true);
    
    if ($specific_layout && $specific_layout !== 'default') {
        return $specific_layout;
    }
    
    // WooCommerce layouts
    if (aqualuxe_is_woocommerce_active()) {
        if (is_shop() || is_product_category() || is_product_tag()) {
            return aqualuxe_get_option('shop_layout', 'left-sidebar');
        }
        
        if (is_product()) {
            return aqualuxe_get_option('product_layout', 'no-sidebar');
        }
        
        if (is_cart() || is_checkout() || is_account_page()) {
            return 'no-sidebar';
        }
    }
    
    // Blog layouts
    if (is_home() || is_category() || is_tag() || is_date() || is_author()) {
        return aqualuxe_get_option('blog_layout', 'right-sidebar');
    }
    
    // Single post layout
    if (is_singular('post')) {
        return aqualuxe_get_option('post_layout', 'right-sidebar');
    }
    
    // Page layout
    if (is_page()) {
        return aqualuxe_get_option('page_layout', 'no-sidebar');
    }
    
    // Default layout
    return $default_layout;
}

/**
 * Get theme container width
 *
 * @return string
 */
function aqualuxe_get_container_width() {
    return aqualuxe_get_option('container_width', '1200px');
}

/**
 * Get theme spacing
 *
 * @return array
 */
function aqualuxe_get_spacing() {
    $spacing_unit = aqualuxe_get_option('spacing_unit', '1rem');
    
    return [
        'unit' => $spacing_unit,
        'xs' => 'calc(' . $spacing_unit . ' * 0.25)',
        'sm' => 'calc(' . $spacing_unit . ' * 0.5)',
        'md' => $spacing_unit,
        'lg' => 'calc(' . $spacing_unit . ' * 1.5)',
        'xl' => 'calc(' . $spacing_unit . ' * 2)',
        '2xl' => 'calc(' . $spacing_unit . ' * 3)',
        '3xl' => 'calc(' . $spacing_unit . ' * 4)',
    ];
}

/**
 * Get theme border radius
 *
 * @return array
 */
function aqualuxe_get_border_radius() {
    $border_radius = aqualuxe_get_option('border_radius', 'medium');
    
    $options = [
        'none' => [
            'sm' => '0',
            'md' => '0',
            'lg' => '0',
            'pill' => '9999px',
        ],
        'small' => [
            'sm' => '0.125rem',
            'md' => '0.25rem',
            'lg' => '0.5rem',
            'pill' => '9999px',
        ],
        'medium' => [
            'sm' => '0.25rem',
            'md' => '0.5rem',
            'lg' => '1rem',
            'pill' => '9999px',
        ],
        'large' => [
            'sm' => '0.5rem',
            'md' => '1rem',
            'lg' => '2rem',
            'pill' => '9999px',
        ],
    ];
    
    return isset($options[$border_radius]) ? $options[$border_radius] : $options['medium'];
}

/**
 * Get theme box shadow
 *
 * @return array
 */
function aqualuxe_get_box_shadow() {
    $box_shadow = aqualuxe_get_option('box_shadow', 'medium');
    
    $options = [
        'none' => [
            'sm' => 'none',
            'md' => 'none',
            'lg' => 'none',
        ],
        'small' => [
            'sm' => '0 1px 2px 0 rgba(0, 0, 0, 0.05)',
            'md' => '0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06)',
            'lg' => '0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06)',
        ],
        'medium' => [
            'sm' => '0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06)',
            'md' => '0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06)',
            'lg' => '0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05)',
        ],
        'large' => [
            'sm' => '0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06)',
            'md' => '0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05)',
            'lg' => '0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04)',
        ],
    ];
    
    return isset($options[$box_shadow]) ? $options[$box_shadow] : $options['medium'];
}

/**
 * Get theme transition
 *
 * @return array
 */
function aqualuxe_get_transition() {
    return [
        'fast' => 'all 0.15s ease-in-out',
        'normal' => 'all 0.3s ease-in-out',
        'slow' => 'all 0.5s ease-in-out',
    ];
}

/**
 * Get theme breakpoints
 *
 * @return array
 */
function aqualuxe_get_breakpoints() {
    return [
        'xs' => '0px',
        'sm' => '576px',
        'md' => '768px',
        'lg' => '992px',
        'xl' => '1200px',
        '2xl' => '1400px',
    ];
}

/**
 * Get theme grid columns
 *
 * @return int
 */
function aqualuxe_get_grid_columns() {
    return 12;
}

/**
 * Get theme grid gutter
 *
 * @return string
 */
function aqualuxe_get_grid_gutter() {
    return aqualuxe_get_option('grid_gutter', '1.5rem');
}

/**
 * Get theme container padding
 *
 * @return string
 */
function aqualuxe_get_container_padding() {
    return aqualuxe_get_option('container_padding', '1rem');
}

/**
 * Get theme header style
 *
 * @return string
 */
function aqualuxe_get_header_style() {
    return aqualuxe_get_option('header_style', 'default');
}

/**
 * Get theme footer style
 *
 * @return string
 */
function aqualuxe_get_footer_style() {
    return aqualuxe_get_option('footer_style', 'default');
}

/**
 * Get theme button style
 *
 * @return string
 */
function aqualuxe_get_button_style() {
    return aqualuxe_get_option('button_style', 'default');
}

/**
 * Get theme card style
 *
 * @return string
 */
function aqualuxe_get_card_style() {
    return aqualuxe_get_option('card_style', 'default');
}

/**
 * Get theme form style
 *
 * @return string
 */
function aqualuxe_get_form_style() {
    return aqualuxe_get_option('form_style', 'default');
}

/**
 * Get theme navigation style
 *
 * @return string
 */
function aqualuxe_get_navigation_style() {
    return aqualuxe_get_option('navigation_style', 'default');
}

/**
 * Get theme pagination style
 *
 * @return string
 */
function aqualuxe_get_pagination_style() {
    return aqualuxe_get_option('pagination_style', 'default');
}

/**
 * Get theme breadcrumb style
 *
 * @return string
 */
function aqualuxe_get_breadcrumb_style() {
    return aqualuxe_get_option('breadcrumb_style', 'default');
}

/**
 * Get theme alert style
 *
 * @return string
 */
function aqualuxe_get_alert_style() {
    return aqualuxe_get_option('alert_style', 'default');
}

/**
 * Get theme badge style
 *
 * @return string
 */
function aqualuxe_get_badge_style() {
    return aqualuxe_get_option('badge_style', 'default');
}

/**
 * Get theme modal style
 *
 * @return string
 */
function aqualuxe_get_modal_style() {
    return aqualuxe_get_option('modal_style', 'default');
}

/**
 * Get theme tooltip style
 *
 * @return string
 */
function aqualuxe_get_tooltip_style() {
    return aqualuxe_get_option('tooltip_style', 'default');
}

/**
 * Get theme table style
 *
 * @return string
 */
function aqualuxe_get_table_style() {
    return aqualuxe_get_option('table_style', 'default');
}

/**
 * Get theme progress style
 *
 * @return string
 */
function aqualuxe_get_progress_style() {
    return aqualuxe_get_option('progress_style', 'default');
}

/**
 * Get theme spinner style
 *
 * @return string
 */
function aqualuxe_get_spinner_style() {
    return aqualuxe_get_option('spinner_style', 'default');
}

/**
 * Get theme tabs style
 *
 * @return string
 */
function aqualuxe_get_tabs_style() {
    return aqualuxe_get_option('tabs_style', 'default');
}

/**
 * Get theme accordion style
 *
 * @return string
 */
function aqualuxe_get_accordion_style() {
    return aqualuxe_get_option('accordion_style', 'default');
}

/**
 * Get theme carousel style
 *
 * @return string
 */
function aqualuxe_get_carousel_style() {
    return aqualuxe_get_option('carousel_style', 'default');
}

/**
 * Get theme product card style
 *
 * @return string
 */
function aqualuxe_get_product_card_style() {
    return aqualuxe_get_option('product_card_style', 'default');
}

/**
 * Get theme blog card style
 *
 * @return string
 */
function aqualuxe_get_blog_card_style() {
    return aqualuxe_get_option('blog_card_style', 'default');
}

/**
 * Get theme hero style
 *
 * @return string
 */
function aqualuxe_get_hero_style() {
    return aqualuxe_get_option('hero_style', 'default');
}

/**
 * Get theme testimonial style
 *
 * @return string
 */
function aqualuxe_get_testimonial_style() {
    return aqualuxe_get_option('testimonial_style', 'default');
}

/**
 * Get theme team member style
 *
 * @return string
 */
function aqualuxe_get_team_member_style() {
    return aqualuxe_get_option('team_member_style', 'default');
}

/**
 * Get theme feature style
 *
 * @return string
 */
function aqualuxe_get_feature_style() {
    return aqualuxe_get_option('feature_style', 'default');
}

/**
 * Get theme CTA style
 *
 * @return string
 */
function aqualuxe_get_cta_style() {
    return aqualuxe_get_option('cta_style', 'default');
}

/**
 * Get theme pricing style
 *
 * @return string
 */
function aqualuxe_get_pricing_style() {
    return aqualuxe_get_option('pricing_style', 'default');
}

/**
 * Get theme stats style
 *
 * @return string
 */
function aqualuxe_get_stats_style() {
    return aqualuxe_get_option('stats_style', 'default');
}

/**
 * Get theme gallery style
 *
 * @return string
 */
function aqualuxe_get_gallery_style() {
    return aqualuxe_get_option('gallery_style', 'default');
}

/**
 * Get theme timeline style
 *
 * @return string
 */
function aqualuxe_get_timeline_style() {
    return aqualuxe_get_option('timeline_style', 'default');
}

/**
 * Get theme map style
 *
 * @return string
 */
function aqualuxe_get_map_style() {
    return aqualuxe_get_option('map_style', 'default');
}

/**
 * Get theme contact form style
 *
 * @return string
 */
function aqualuxe_get_contact_form_style() {
    return aqualuxe_get_option('contact_form_style', 'default');
}

/**
 * Get theme newsletter style
 *
 * @return string
 */
function aqualuxe_get_newsletter_style() {
    return aqualuxe_get_option('newsletter_style', 'default');
}

/**
 * Get theme social icons style
 *
 * @return string
 */
function aqualuxe_get_social_icons_style() {
    return aqualuxe_get_option('social_icons_style', 'default');
}

/**
 * Get theme logo style
 *
 * @return string
 */
function aqualuxe_get_logo_style() {
    return aqualuxe_get_option('logo_style', 'default');
}

/**
 * Get theme menu style
 *
 * @return string
 */
function aqualuxe_get_menu_style() {
    return aqualuxe_get_option('menu_style', 'default');
}

/**
 * Get theme search style
 *
 * @return string
 */
function aqualuxe_get_search_style() {
    return aqualuxe_get_option('search_style', 'default');
}

/**
 * Get theme cart style
 *
 * @return string
 */
function aqualuxe_get_cart_style() {
    return aqualuxe_get_option('cart_style', 'default');
}

/**
 * Get theme account style
 *
 * @return string
 */
function aqualuxe_get_account_style() {
    return aqualuxe_get_option('account_style', 'default');
}

/**
 * Get theme wishlist style
 *
 * @return string
 */
function aqualuxe_get_wishlist_style() {
    return aqualuxe_get_option('wishlist_style', 'default');
}

/**
 * Get theme compare style
 *
 * @return string
 */
function aqualuxe_get_compare_style() {
    return aqualuxe_get_option('compare_style', 'default');
}

/**
 * Get theme quick view style
 *
 * @return string
 */
function aqualuxe_get_quick_view_style() {
    return aqualuxe_get_option('quick_view_style', 'default');
}

/**
 * Get theme filter style
 *
 * @return string
 */
function aqualuxe_get_filter_style() {
    return aqualuxe_get_option('filter_style', 'default');
}

/**
 * Get theme sort style
 *
 * @return string
 */
function aqualuxe_get_sort_style() {
    return aqualuxe_get_option('sort_style', 'default');
}

/**
 * Get theme product gallery style
 *
 * @return string
 */
function aqualuxe_get_product_gallery_style() {
    return aqualuxe_get_option('product_gallery_style', 'default');
}

/**
 * Get theme product tabs style
 *
 * @return string
 */
function aqualuxe_get_product_tabs_style() {
    return aqualuxe_get_option('product_tabs_style', 'default');
}

/**
 * Get theme product meta style
 *
 * @return string
 */
function aqualuxe_get_product_meta_style() {
    return aqualuxe_get_option('product_meta_style', 'default');
}

/**
 * Get theme product related style
 *
 * @return string
 */
function aqualuxe_get_product_related_style() {
    return aqualuxe_get_option('product_related_style', 'default');
}

/**
 * Get theme product upsell style
 *
 * @return string
 */
function aqualuxe_get_product_upsell_style() {
    return aqualuxe_get_option('product_upsell_style', 'default');
}

/**
 * Get theme product cross-sell style
 *
 * @return string
 */
function aqualuxe_get_product_cross_sell_style() {
    return aqualuxe_get_option('product_cross_sell_style', 'default');
}

/**
 * Get theme checkout style
 *
 * @return string
 */
function aqualuxe_get_checkout_style() {
    return aqualuxe_get_option('checkout_style', 'default');
}

/**
 * Get theme order style
 *
 * @return string
 */
function aqualuxe_get_order_style() {
    return aqualuxe_get_option('order_style', 'default');
}

/**
 * Get theme thank you style
 *
 * @return string
 */
function aqualuxe_get_thank_you_style() {
    return aqualuxe_get_option('thank_you_style', 'default');
}

/**
 * Get theme login style
 *
 * @return string
 */
function aqualuxe_get_login_style() {
    return aqualuxe_get_option('login_style', 'default');
}

/**
 * Get theme register style
 *
 * @return string
 */
function aqualuxe_get_register_style() {
    return aqualuxe_get_option('register_style', 'default');
}

/**
 * Get theme lost password style
 *
 * @return string
 */
function aqualuxe_get_lost_password_style() {
    return aqualuxe_get_option('lost_password_style', 'default');
}

/**
 * Get theme reset password style
 *
 * @return string
 */
function aqualuxe_get_reset_password_style() {
    return aqualuxe_get_option('reset_password_style', 'default');
}

/**
 * Get theme edit account style
 *
 * @return string
 */
function aqualuxe_get_edit_account_style() {
    return aqualuxe_get_option('edit_account_style', 'default');
}

/**
 * Get theme edit address style
 *
 * @return string
 */
function aqualuxe_get_edit_address_style() {
    return aqualuxe_get_option('edit_address_style', 'default');
}

/**
 * Get theme payment methods style
 *
 * @return string
 */
function aqualuxe_get_payment_methods_style() {
    return aqualuxe_get_option('payment_methods_style', 'default');
}

/**
 * Get theme downloads style
 *
 * @return string
 */
function aqualuxe_get_downloads_style() {
    return aqualuxe_get_option('downloads_style', 'default');
}

/**
 * Get theme orders style
 *
 * @return string
 */
function aqualuxe_get_orders_style() {
    return aqualuxe_get_option('orders_style', 'default');
}

/**
 * Get theme view order style
 *
 * @return string
 */
function aqualuxe_get_view_order_style() {
    return aqualuxe_get_option('view_order_style', 'default');
}

/**
 * Get theme dashboard style
 *
 * @return string
 */
function aqualuxe_get_dashboard_style() {
    return aqualuxe_get_option('dashboard_style', 'default');
}

/**
 * Get theme logout style
 *
 * @return string
 */
function aqualuxe_get_logout_style() {
    return aqualuxe_get_option('logout_style', 'default');
}

/**
 * Get theme error style
 *
 * @return string
 */
function aqualuxe_get_error_style() {
    return aqualuxe_get_option('error_style', 'default');
}

/**
 * Get theme success style
 *
 * @return string
 */
function aqualuxe_get_success_style() {
    return aqualuxe_get_option('success_style', 'default');
}

/**
 * Get theme info style
 *
 * @return string
 */
function aqualuxe_get_info_style() {
    return aqualuxe_get_option('info_style', 'default');
}

/**
 * Get theme warning style
 *
 * @return string
 */
function aqualuxe_get_warning_style() {
    return aqualuxe_get_option('warning_style', 'default');
}

/**
 * Get theme notice style
 *
 * @return string
 */
function aqualuxe_get_notice_style() {
    return aqualuxe_get_option('notice_style', 'default');
}

/**
 * Get theme message style
 *
 * @return string
 */
function aqualuxe_get_message_style() {
    return aqualuxe_get_option('message_style', 'default');
}

/**
 * Get theme validation style
 *
 * @return string
 */
function aqualuxe_get_validation_style() {
    return aqualuxe_get_option('validation_style', 'default');
}