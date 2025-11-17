<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package AquaLuxe
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function aqualuxe_body_classes($classes) {
    // Adds a class of hfeed to non-singular pages.
    if (!is_singular()) {
        $classes[] = 'hfeed';
    }

    // Adds a class of no-sidebar when there is no sidebar present.
    if (!is_active_sidebar('sidebar-blog') && !is_active_sidebar('sidebar-shop')) {
        $classes[] = 'no-sidebar';
    }

    // Add class for dark mode
    $classes[] = 'dark-transition';

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
 * Add schema markup to the body element.
 *
 * @param array $attr Array of attributes.
 * @return array
 */
function aqualuxe_body_attributes($attr) {
    $attr['itemscope'] = '';
    $attr['itemtype']  = 'https://schema.org/WebPage';

    return $attr;
}
add_filter('aqualuxe_body_attributes', 'aqualuxe_body_attributes');

/**
 * Add schema markup to the html element.
 *
 * @param array $attr Array of attributes.
 * @return array
 */
function aqualuxe_html_attributes($attr) {
    $attr['class'] = 'no-js';
    return $attr;
}
add_filter('aqualuxe_html_attributes', 'aqualuxe_html_attributes');

/**
 * Add a Sub Nav Toggle to the Mobile Menu.
 *
 * @param string   $item_output The menu item's starting HTML output.
 * @param WP_Post  $item        Menu item data object.
 * @param int      $depth       Depth of menu item. Used for padding.
 * @param stdClass $args        An object of wp_nav_menu() arguments.
 * @return string The menu item output with social icon.
 */
function aqualuxe_add_sub_toggles_to_main_menu($item_output, $item, $depth, $args) {
    // Add sub menu toggles to the mobile menu.
    if ($args->theme_location === 'mobile' && in_array('menu-item-has-children', $item->classes, true)) {
        // Add toggle button.
        $item_output .= '<button class="sub-menu-toggle flex items-center justify-center w-10 h-10 absolute right-0 top-0 text-dark dark:text-light" aria-expanded="false">';
        $item_output .= aqualuxe_get_svg('chevron-down');
        $item_output .= '<span class="sr-only">' . esc_html__('Open menu', 'aqualuxe') . '</span>';
        $item_output .= '</button>';
    }

    return $item_output;
}
add_filter('walker_nav_menu_start_el', 'aqualuxe_add_sub_toggles_to_main_menu', 10, 4);

/**
 * Wrap the post thumbnail in a figure element.
 *
 * @param string $html The post thumbnail HTML.
 * @return string
 */
function aqualuxe_wrap_post_thumbnail($html) {
    if ($html) {
        $html = '<figure class="post-thumbnail">' . $html . '</figure>';
    }

    return $html;
}
add_filter('post_thumbnail_html', 'aqualuxe_wrap_post_thumbnail');

/**
 * Change the excerpt more string.
 *
 * @param string $more The string shown within the more link.
 * @return string
 */
function aqualuxe_excerpt_more($more) {
    if (is_admin()) {
        return $more;
    }

    return '&hellip;';
}
add_filter('excerpt_more', 'aqualuxe_excerpt_more');

/**
 * Filter the excerpt length.
 *
 * @param int $length Excerpt length.
 * @return int
 */
function aqualuxe_excerpt_length($length) {
    if (is_admin()) {
        return $length;
    }

    return 20;
}
add_filter('excerpt_length', 'aqualuxe_excerpt_length');

/**
 * Add custom meta tags for SEO.
 */
function aqualuxe_meta_tags() {
    global $post;

    if (is_singular()) {
        $excerpt = '';
        if (has_excerpt($post->ID)) {
            $excerpt = get_the_excerpt();
        } else {
            $excerpt = wp_trim_words(strip_shortcodes($post->post_content), 20);
        }

        if ($excerpt) {
            echo '<meta name="description" content="' . esc_attr($excerpt) . '" />' . "\n";
        }

        echo '<meta property="og:title" content="' . esc_attr(get_the_title()) . '" />' . "\n";
        echo '<meta property="og:type" content="article" />' . "\n";
        echo '<meta property="og:url" content="' . esc_url(get_permalink()) . '" />' . "\n";
        
        if (has_post_thumbnail()) {
            $thumbnail_src = wp_get_attachment_image_src(get_post_thumbnail_id(), 'large');
            echo '<meta property="og:image" content="' . esc_url($thumbnail_src[0]) . '" />' . "\n";
        }
        
        echo '<meta property="og:site_name" content="' . esc_attr(get_bloginfo('name')) . '" />' . "\n";
        
        if ($excerpt) {
            echo '<meta property="og:description" content="' . esc_attr($excerpt) . '" />' . "\n";
        }
    }
}
add_action('wp_head', 'aqualuxe_meta_tags', 1);

/**
 * Add custom attributes to the script and style tags.
 *
 * @param string $tag    The link tag for the enqueued style or script.
 * @param string $handle The style or script handle.
 * @return string
 */
function aqualuxe_add_script_attributes($tag, $handle) {
    if ('aqualuxe-main' === $handle) {
        return str_replace(' src', ' async defer src', $tag);
    }

    return $tag;
}
add_filter('script_loader_tag', 'aqualuxe_add_script_attributes', 10, 2);

/**
 * Add preconnect for Google Fonts.
 *
 * @param array  $urls          URLs to print for resource hints.
 * @param string $relation_type The relation type the URLs are printed.
 * @return array URLs to print for resource hints.
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
 * Add custom image sizes attribute to enhance responsive image functionality.
 *
 * @param array $attr Attributes for the image markup.
 * @param WP_Post $attachment Image attachment post.
 * @param array|string $size Requested size.
 * @return array
 */
function aqualuxe_image_sizes_attr($attr, $attachment, $size) {
    if (is_array($size)) {
        $attr['sizes'] = '100vw';
    } elseif ('aqualuxe-featured' === $size) {
        $attr['sizes'] = '(min-width: 1200px) 1200px, 100vw';
    } elseif ('aqualuxe-card' === $size) {
        $attr['sizes'] = '(min-width: 1200px) 600px, (min-width: 768px) 50vw, 100vw';
    }

    return $attr;
}
add_filter('wp_get_attachment_image_attributes', 'aqualuxe_image_sizes_attr', 10, 3);

/**
 * Add lazy loading attribute to images.
 *
 * @param array $attr Attributes for the image markup.
 * @return array
 */
function aqualuxe_add_lazyload_to_images($attr) {
    $attr['loading'] = 'lazy';
    return $attr;
}
add_filter('wp_get_attachment_image_attributes', 'aqualuxe_add_lazyload_to_images');

/**
 * Add custom classes to navigation menus.
 *
 * @param array $classes Array of the CSS classes.
 * @param object $item The current item in the menu.
 * @param array $args Arguments from wp_nav_menu().
 * @return array
 */
function aqualuxe_nav_menu_css_class($classes, $item, $args) {
    if ('primary' === $args->theme_location) {
        $classes[] = 'nav-item';
    }

    if (in_array('current-menu-item', $classes, true) || in_array('current-menu-parent', $classes, true)) {
        $classes[] = 'active';
    }

    return $classes;
}
add_filter('nav_menu_css_class', 'aqualuxe_nav_menu_css_class', 10, 3);

/**
 * Add custom classes to navigation menu links.
 *
 * @param array $atts The HTML attributes applied to the menu item's link element.
 * @param object $item The current menu item.
 * @param array $args Arguments from wp_nav_menu().
 * @return array
 */
function aqualuxe_nav_menu_link_attributes($atts, $item, $args) {
    if ('primary' === $args->theme_location) {
        $atts['class'] = 'nav-link';
    }

    if (in_array('current-menu-item', $item->classes, true) || in_array('current-menu-parent', $item->classes, true)) {
        $atts['class'] .= ' nav-link-active';
    }

    return $atts;
}
add_filter('nav_menu_link_attributes', 'aqualuxe_nav_menu_link_attributes', 10, 3);

/**
 * Add a dropdown icon to top-level menu items.
 *
 * @param string $title The menu item's title.
 * @param object $item The current menu item.
 * @param array $args An array of wp_nav_menu() arguments.
 * @param int $depth Depth of menu item.
 * @return string The menu item's title with dropdown icon.
 */
function aqualuxe_dropdown_icon_to_menu_link($title, $item, $args, $depth) {
    if ('primary' === $args->theme_location || 'mobile' === $args->theme_location) {
        foreach ($item->classes as $value) {
            if ('menu-item-has-children' === $value || 'page_item_has_children' === $value) {
                $title = $title . ' ' . aqualuxe_get_svg('chevron-down');
            }
        }
    }

    return $title;
}
add_filter('nav_menu_item_title', 'aqualuxe_dropdown_icon_to_menu_link', 10, 4);

/**
 * Modify comment form fields.
 *
 * @param array $fields The comment fields.
 * @return array
 */
function aqualuxe_comment_form_fields($fields) {
    $commenter = wp_get_current_commenter();
    $req       = get_option('require_name_email');
    $aria_req  = ($req ? " aria-required='true'" : '');

    $fields['author'] = '<div class="comment-form-author mb-4"><label for="author" class="form-label">' . __('Name', 'aqualuxe') . ($req ? ' <span class="required">*</span>' : '') . '</label><input id="author" name="author" type="text" value="' . esc_attr($commenter['comment_author']) . '" size="30" maxlength="245"' . $aria_req . ' class="form-input" /></div>';

    $fields['email'] = '<div class="comment-form-email mb-4"><label for="email" class="form-label">' . __('Email', 'aqualuxe') . ($req ? ' <span class="required">*</span>' : '') . '</label><input id="email" name="email" type="email" value="' . esc_attr($commenter['comment_author_email']) . '" size="30" maxlength="100" aria-describedby="email-notes"' . $aria_req . ' class="form-input" /></div>';

    $fields['url'] = '<div class="comment-form-url mb-4"><label for="url" class="form-label">' . __('Website', 'aqualuxe') . '</label><input id="url" name="url" type="url" value="' . esc_attr($commenter['comment_author_url']) . '" size="30" maxlength="200" class="form-input" /></div>';

    return $fields;
}
add_filter('comment_form_default_fields', 'aqualuxe_comment_form_fields');

/**
 * Modify comment form defaults.
 *
 * @param array $defaults The default comment form arguments.
 * @return array
 */
function aqualuxe_comment_form_defaults($defaults) {
    $defaults['comment_field'] = '<div class="comment-form-comment mb-4"><label for="comment" class="form-label">' . _x('Comment', 'noun', 'aqualuxe') . ' <span class="required">*</span></label><textarea id="comment" name="comment" cols="45" rows="8" maxlength="65525" aria-required="true" required="required" class="form-input"></textarea></div>';
    
    $defaults['class_submit'] = 'btn btn-primary';
    $defaults['submit_button'] = '<button name="%1$s" type="submit" id="%2$s" class="%3$s">%4$s</button>';

    return $defaults;
}
add_filter('comment_form_defaults', 'aqualuxe_comment_form_defaults');

/**
 * Add custom classes to the array of post classes.
 *
 * @param array $classes Classes for the post element.
 * @return array
 */
function aqualuxe_post_classes($classes) {
    if (is_singular()) {
        $classes[] = 'single-post-content';
    } else {
        $classes[] = 'post-card';
    }

    return $classes;
}
add_filter('post_class', 'aqualuxe_post_classes');

/**
 * Add custom classes to archive title.
 *
 * @param string $title Archive title.
 * @return string
 */
function aqualuxe_archive_title($title) {
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
add_filter('get_the_archive_title', 'aqualuxe_archive_title');

/**
 * Add schema markup to the authors post link.
 *
 * @param string $link The author posts link.
 * @return string
 */
function aqualuxe_get_the_author_posts_link($link) {
    $pattern = array(
        '/(<a.*?>)/',
        '/(<a.*?>)(.*)(<\/a>)/',
    );

    $replace = array(
        '$1<span itemprop="name">',
        '$1<span itemprop="name">$2</span>$3',
    );

    return preg_replace($pattern, $replace, $link);
}
add_filter('the_author_posts_link', 'aqualuxe_get_the_author_posts_link');

/**
 * Modify the "read more" excerpt string.
 *
 * @param string $more The string shown within the more link.
 * @return string
 */
function aqualuxe_excerpt_more_link($more) {
    if (!is_admin()) {
        global $post;
        return '&hellip; <a href="' . esc_url(get_permalink($post->ID)) . '" class="more-link">' . esc_html__('Read More', 'aqualuxe') . ' <span class="screen-reader-text">' . esc_html(get_the_title($post->ID)) . '</span></a>';
    }
    return $more;
}
add_filter('excerpt_more', 'aqualuxe_excerpt_more_link');

/**
 * Add schema markup to the comment author link.
 *
 * @param string $link The comment author link.
 * @return string
 */
function aqualuxe_get_comment_author_link($link) {
    $pattern = array(
        '/(<a.*?>)/',
        '/(<a.*?>)(.*)(<\/a>)/',
    );

    $replace = array(
        '$1<span itemprop="name">',
        '$1<span itemprop="name">$2</span>$3',
    );

    return preg_replace($pattern, $replace, $link);
}
add_filter('get_comment_author_link', 'aqualuxe_get_comment_author_link');

/**
 * Add schema markup to the comment author URL link.
 *
 * @param string $link The comment author URL link.
 * @return string
 */
function aqualuxe_get_comment_author_url_link($link) {
    $pattern = array(
        '/(<a.*?)(?=>)/',
        '/(<a.*?>)(.*)(<\/a>)/',
    );

    $replace = array(
        '$1 itemprop="url"',
        '$1<span itemprop="name">$2</span>$3',
    );

    return preg_replace($pattern, $replace, $link);
}
add_filter('get_comment_author_url_link', 'aqualuxe_get_comment_author_url_link');

/**
 * Add custom classes to the array of comment classes.
 *
 * @param array $classes Classes for the comment element.
 * @return array
 */
function aqualuxe_comment_class($classes) {
    $classes[] = 'comment-item';
    return $classes;
}
add_filter('comment_class', 'aqualuxe_comment_class');

/**
 * Filter the default gallery shortcode output.
 *
 * @param string $output The gallery output.
 * @param array  $attr   Attributes of the gallery shortcode.
 * @return string
 */
function aqualuxe_gallery_shortcode($output, $attr) {
    global $post, $wp_locale;

    static $instance = 0;
    $instance++;

    // We're trusting author input, so let's at least make sure it looks like a valid orderby statement.
    if (isset($attr['orderby'])) {
        $attr['orderby'] = sanitize_sql_orderby($attr['orderby']);
        if (!$attr['orderby']) {
            unset($attr['orderby']);
        }
    }

    $html5 = current_theme_supports('html5', 'gallery');
    $atts  = shortcode_atts(
        array(
            'order'      => 'ASC',
            'orderby'    => 'menu_order ID',
            'id'         => $post ? $post->ID : 0,
            'itemtag'    => $html5 ? 'figure' : 'dl',
            'icontag'    => $html5 ? 'div' : 'dt',
            'captiontag' => $html5 ? 'figcaption' : 'dd',
            'columns'    => 3,
            'size'       => 'thumbnail',
            'include'    => '',
            'exclude'    => '',
            'link'       => '',
        ),
        $attr,
        'gallery'
    );

    $id = intval($atts['id']);

    if (!empty($atts['include'])) {
        $_attachments = get_posts(
            array(
                'include'        => $atts['include'],
                'post_status'    => 'inherit',
                'post_type'      => 'attachment',
                'post_mime_type' => 'image',
                'order'          => $atts['order'],
                'orderby'        => $atts['orderby'],
            )
        );

        $attachments = array();
        foreach ($_attachments as $key => $val) {
            $attachments[$val->ID] = $_attachments[$key];
        }
    } elseif (!empty($atts['exclude'])) {
        $attachments = get_children(
            array(
                'post_parent'    => $id,
                'exclude'        => $atts['exclude'],
                'post_status'    => 'inherit',
                'post_type'      => 'attachment',
                'post_mime_type' => 'image',
                'order'          => $atts['order'],
                'orderby'        => $atts['orderby'],
            )
        );
    } else {
        $attachments = get_children(
            array(
                'post_parent'    => $id,
                'post_status'    => 'inherit',
                'post_type'      => 'attachment',
                'post_mime_type' => 'image',
                'order'          => $atts['order'],
                'orderby'        => $atts['orderby'],
            )
        );
    }

    if (empty($attachments)) {
        return '';
    }

    if (is_feed()) {
        $output = "\n";
        foreach ($attachments as $att_id => $attachment) {
            $output .= wp_get_attachment_link($att_id, $atts['size'], true) . "\n";
        }
        return $output;
    }

    $itemtag    = tag_escape($atts['itemtag']);
    $captiontag = tag_escape($atts['captiontag']);
    $icontag    = tag_escape($atts['icontag']);
    $valid_tags = wp_kses_allowed_html('post');
    if (!isset($valid_tags[$itemtag])) {
        $itemtag = 'dl';
    }
    if (!isset($valid_tags[$captiontag])) {
        $captiontag = 'dd';
    }
    if (!isset($valid_tags[$icontag])) {
        $icontag = 'dt';
    }

    $columns   = intval($atts['columns']);
    $itemwidth = $columns > 0 ? floor(100 / $columns) : 100;
    $float     = is_rtl() ? 'right' : 'left';

    $selector = "gallery-{$instance}";

    $gallery_style = '';

    $size_class  = sanitize_html_class($atts['size']);
    $gallery_div = "<div id='$selector' class='gallery galleryid-{$id} gallery-columns-{$columns} gallery-size-{$size_class} grid grid-cols-1 md:grid-cols-{$columns} gap-4'>";

    $output = $gallery_style . $gallery_div;

    $i = 0;
    foreach ($attachments as $id => $attachment) {
        $attr = (trim($attachment->post_excerpt)) ? array('aria-describedby' => "$selector-$id") : '';
        if (!empty($atts['link']) && 'file' === $atts['link']) {
            $image_output = wp_get_attachment_link($id, $atts['size'], false, false, false, $attr);
        } elseif (!empty($atts['link']) && 'none' === $atts['link']) {
            $image_output = wp_get_attachment_image($id, $atts['size'], false, $attr);
        } else {
            $image_output = wp_get_attachment_link($id, $atts['size'], true, false, false, $attr);
        }
        $image_meta = wp_get_attachment_metadata($id);

        $orientation = '';
        if (isset($image_meta['height'], $image_meta['width'])) {
            $orientation = ($image_meta['height'] > $image_meta['width']) ? 'portrait' : 'landscape';
        }
        $output .= "<{$itemtag} class='gallery-item'>";
        $output .= "
            <{$icontag} class='gallery-icon {$orientation}'>
                $image_output
            </{$icontag}>";
        if ($captiontag && trim($attachment->post_excerpt)) {
            $output .= "
                <{$captiontag} class='wp-caption-text gallery-caption' id='$selector-$id'>
                " . wptexturize($attachment->post_excerpt) . "
                </{$captiontag}>";
        }
        $output .= "</{$itemtag}>";
    }

    $output .= "
        </div>\n";

    return $output;
}
add_filter('post_gallery', 'aqualuxe_gallery_shortcode', 10, 2);

/**
 * Add custom classes to the array of gallery image attributes.
 *
 * @param array $attr Attributes for the image markup.
 * @return array
 */
function aqualuxe_gallery_image_attributes($attr) {
    $attr['class'] .= ' rounded-lg';
    return $attr;
}
add_filter('wp_get_attachment_image_attributes', 'aqualuxe_gallery_image_attributes', 10, 1);

/**
 * Add custom classes to the array of gallery link attributes.
 *
 * @param string $link The gallery image link.
 * @return string
 */
function aqualuxe_gallery_link_attributes($link) {
    return str_replace('<a href', '<a class="block overflow-hidden rounded-lg" href', $link);
}
add_filter('wp_get_attachment_link', 'aqualuxe_gallery_link_attributes', 10, 1);

/**
 * Add custom classes to the array of gallery image attributes.
 *
 * @param array $attr Attributes for the image markup.
 * @return array
 */
function aqualuxe_gallery_image_classes($attr) {
    $attr['class'] .= ' w-full h-auto transition-transform duration-300 hover:scale-105';
    return $attr;
}
add_filter('wp_get_attachment_image_attributes', 'aqualuxe_gallery_image_classes', 10, 1);

/**
 * Add custom classes to the array of gallery link attributes.
 *
 * @param string $link The gallery image link.
 * @return string
 */
function aqualuxe_gallery_link_classes($link) {
    return str_replace('<a href', '<a class="block overflow-hidden rounded-lg" href', $link);
}
add_filter('wp_get_attachment_link', 'aqualuxe_gallery_link_classes', 10, 1);

/**
 * Add custom classes to the array of gallery image attributes.
 *
 * @param array $attr Attributes for the image markup.
 * @return array
 */
function aqualuxe_gallery_image_classes_filter($attr) {
    $attr['class'] .= ' w-full h-auto transition-transform duration-300 hover:scale-105';
    return $attr;
}
add_filter('wp_get_attachment_image_attributes', 'aqualuxe_gallery_image_classes_filter', 10, 1);

/**
 * Add custom classes to the array of gallery link attributes.
 *
 * @param string $link The gallery image link.
 * @return string
 */
function aqualuxe_gallery_link_classes_filter($link) {
    return str_replace('<a href', '<a class="block overflow-hidden rounded-lg" href', $link);
}
add_filter('wp_get_attachment_link', 'aqualuxe_gallery_link_classes_filter', 10, 1);