<?php
/**
 * Theme filters
 *
 * @package AquaLuxe
 */

/**
 * Add custom classes to the array of post classes
 *
 * @param array $classes
 * @return array
 */
function aqualuxe_post_classes($classes) {
    // Add custom class to posts
    $classes[] = 'aqualuxe-post';
    
    // Add class based on post format
    $format = get_post_format() ?: 'standard';
    $classes[] = 'format-' . $format;
    
    return $classes;
}
add_filter('post_class', 'aqualuxe_post_classes');

/**
 * Modify the excerpt length
 *
 * @param int $length
 * @return int
 */
function aqualuxe_excerpt_length($length) {
    return 30;
}
add_filter('excerpt_length', 'aqualuxe_excerpt_length');

/**
 * Modify the excerpt more string
 *
 * @param string $more
 * @return string
 */
function aqualuxe_excerpt_more($more) {
    return '&hellip;';
}
add_filter('excerpt_more', 'aqualuxe_excerpt_more');

/**
 * Add custom classes to the array of body classes
 *
 * @param array $classes
 * @return array
 */
function aqualuxe_body_class($classes) {
    // Add class if sidebar is active
    if (is_active_sidebar('sidebar-1')) {
        $classes[] = 'has-sidebar';
    } else {
        $classes[] = 'no-sidebar';
    }
    
    // Add class for dark mode
    if (aqualuxe_is_module_active('dark-mode') && get_theme_mod('aqualuxe_dark_mode_default', false)) {
        $classes[] = 'dark-mode';
    }
    
    // Add class for RTL languages
    if (is_rtl()) {
        $classes[] = 'rtl';
    }
    
    return $classes;
}
add_filter('body_class', 'aqualuxe_body_class');

/**
 * Filter the archive title
 *
 * @param string $title
 * @return string
 */
function aqualuxe_get_the_archive_title($title) {
    if (is_category()) {
        $title = single_cat_title('', false);
    } elseif (is_tag()) {
        $title = single_tag_title('', false);
    } elseif (is_author()) {
        $title = get_the_author();
    } elseif (is_post_type_archive()) {
        $title = post_type_archive_title('', false);
    } elseif (is_tax()) {
        $title = single_term_title('', false);
    }
    
    return $title;
}
add_filter('get_the_archive_title', 'aqualuxe_get_the_archive_title');

/**
 * Filter the archive description
 *
 * @param string $description
 * @return string
 */
function aqualuxe_get_the_archive_description($description) {
    if (is_author()) {
        $description = get_the_author_meta('description');
    }
    
    return $description;
}
add_filter('get_the_archive_description', 'aqualuxe_get_the_archive_description');

/**
 * Filter the content to add schema markup
 *
 * @param string $content
 * @return string
 */
function aqualuxe_add_schema_to_content($content) {
    if (is_singular('post')) {
        // Add schema markup to post content
        $content = '<div ' . aqualuxe_schema_markup('Article') . '>' . $content . '</div>';
    } elseif (is_singular('page')) {
        // Add schema markup to page content
        $content = '<div ' . aqualuxe_schema_markup('WebPage') . '>' . $content . '</div>';
    } elseif (is_singular('product') && aqualuxe_is_woocommerce_active()) {
        // Add schema markup to product content
        $content = '<div ' . aqualuxe_schema_markup('Product') . '>' . $content . '</div>';
    }
    
    return $content;
}
add_filter('the_content', 'aqualuxe_add_schema_to_content');

/**
 * Filter the comment form fields
 *
 * @param array $fields
 * @return array
 */
function aqualuxe_comment_form_fields($fields) {
    // Add custom classes to comment form fields
    $fields['author'] = str_replace('id="author"', 'id="author" class="comment-form-input"', $fields['author']);
    $fields['email'] = str_replace('id="email"', 'id="email" class="comment-form-input"', $fields['email']);
    $fields['url'] = str_replace('id="url"', 'id="url" class="comment-form-input"', $fields['url']);
    
    return $fields;
}
add_filter('comment_form_default_fields', 'aqualuxe_comment_form_fields');

/**
 * Filter the comment form defaults
 *
 * @param array $defaults
 * @return array
 */
function aqualuxe_comment_form_defaults($defaults) {
    // Add custom classes to comment form
    $defaults['comment_field'] = str_replace('id="comment"', 'id="comment" class="comment-form-textarea"', $defaults['comment_field']);
    $defaults['class_submit'] = 'comment-form-submit';
    
    return $defaults;
}
add_filter('comment_form_defaults', 'aqualuxe_comment_form_defaults');

/**
 * Filter the navigation menu items
 *
 * @param array $items
 * @param object $args
 * @return array
 */
function aqualuxe_nav_menu_items($items, $args) {
    // Add custom classes to menu items
    foreach ($items as $item) {
        // Add icon to menu items with specific classes
        if (in_array('menu-item-has-icon', $item->classes)) {
            // Get icon name from menu item title
            $icon_name = sanitize_title($item->title);
            
            // Add icon to menu item title
            $item->title = aqualuxe_get_svg_icon($icon_name, ['class' => 'menu-icon']) . '<span class="menu-text">' . $item->title . '</span>';
        }
    }
    
    return $items;
}
add_filter('wp_nav_menu_objects', 'aqualuxe_nav_menu_items', 10, 2);

/**
 * Filter the upload size limit
 *
 * @param int $size
 * @return int
 */
function aqualuxe_upload_size_limit($size) {
    // Increase upload size limit to 10MB
    return 10 * 1024 * 1024;
}
add_filter('upload_size_limit', 'aqualuxe_upload_size_limit');

/**
 * Filter the allowed mime types
 *
 * @param array $mimes
 * @return array
 */
function aqualuxe_upload_mimes($mimes) {
    // Add SVG support
    $mimes['svg'] = 'image/svg+xml';
    
    return $mimes;
}
add_filter('upload_mimes', 'aqualuxe_upload_mimes');

/**
 * Filter the image editor output quality
 *
 * @param int $quality
 * @return int
 */
function aqualuxe_image_quality($quality) {
    return 90;
}
add_filter('jpeg_quality', 'aqualuxe_image_quality');
add_filter('wp_editor_set_quality', 'aqualuxe_image_quality');

/**
 * Filter the content to add responsive tables
 *
 * @param string $content
 * @return string
 */
function aqualuxe_responsive_tables($content) {
    // Wrap tables in a responsive container
    if (strpos($content, '<table') !== false) {
        $content = preg_replace('/<table(.*?)>/i', '<div class="table-responsive"><table$1>', $content);
        $content = preg_replace('/<\/table>/i', '</table></div>', $content);
    }
    
    return $content;
}
add_filter('the_content', 'aqualuxe_responsive_tables');

/**
 * Filter the gallery shortcode output
 *
 * @param string $output
 * @param array $attr
 * @return string
 */
function aqualuxe_gallery_shortcode($output, $attr) {
    // Add custom classes to gallery
    $output = str_replace('gallery ', 'gallery aqualuxe-gallery ', $output);
    
    return $output;
}
add_filter('post_gallery', 'aqualuxe_gallery_shortcode', 10, 2);

/**
 * Filter the search form
 *
 * @param string $form
 * @return string
 */
function aqualuxe_search_form($form) {
    // Customize search form
    $form = '<form role="search" method="get" class="search-form" action="' . esc_url(home_url('/')) . '">
        <label>
            <span class="screen-reader-text">' . _x('Search for:', 'label', 'aqualuxe') . '</span>
            <input type="search" class="search-field" placeholder="' . esc_attr_x('Search &hellip;', 'placeholder', 'aqualuxe') . '" value="' . get_search_query() . '" name="s" />
        </label>
        <button type="submit" class="search-submit">
            ' . aqualuxe_get_svg_icon('search', ['class' => 'search-icon']) . '
            <span class="screen-reader-text">' . _x('Search', 'submit button', 'aqualuxe') . '</span>
        </button>
    </form>';
    
    return $form;
}
add_filter('get_search_form', 'aqualuxe_search_form');

/**
 * Filter the password form
 *
 * @param string $output
 * @return string
 */
function aqualuxe_password_form($output) {
    // Customize password form
    $output = '<form action="' . esc_url(site_url('wp-login.php?action=postpass', 'login_post')) . '" class="post-password-form" method="post">
        <p>' . __('This content is password protected. To view it please enter your password below:', 'aqualuxe') . '</p>
        <div class="password-form-fields">
            <label for="pwbox-' . esc_attr(get_the_ID()) . '">
                <span class="screen-reader-text">' . __('Password:', 'aqualuxe') . '</span>
                <input name="post_password" id="pwbox-' . esc_attr(get_the_ID()) . '" type="password" placeholder="' . __('Enter password', 'aqualuxe') . '" size="20" />
            </label>
            <button type="submit" name="Submit" class="password-form-submit">' . __('Submit', 'aqualuxe') . '</button>
        </div>
    </form>';
    
    return $output;
}
add_filter('the_password_form', 'aqualuxe_password_form');

/**
 * Filter the page title
 *
 * @param string $title
 * @return string
 */
function aqualuxe_wp_title($title) {
    // Add site name to title
    if (is_feed()) {
        return $title;
    }
    
    // Add site name to title
    $title .= get_bloginfo('name');
    
    // Add site description for home/front page
    $site_description = get_bloginfo('description', 'display');
    if ($site_description && (is_home() || is_front_page())) {
        $title = "$title $site_description";
    }
    
    return $title;
}
add_filter('wp_title', 'aqualuxe_wp_title');

/**
 * Filter the document title separator
 *
 * @return string
 */
function aqualuxe_document_title_separator() {
    return '|';
}
add_filter('document_title_separator', 'aqualuxe_document_title_separator');

/**
 * Filter the document title parts
 *
 * @param array $title
 * @return array
 */
function aqualuxe_document_title_parts($title) {
    if (is_front_page()) {
        $title['title'] = get_bloginfo('name');
        $title['tagline'] = get_bloginfo('description');
    }
    
    return $title;
}
add_filter('document_title_parts', 'aqualuxe_document_title_parts');