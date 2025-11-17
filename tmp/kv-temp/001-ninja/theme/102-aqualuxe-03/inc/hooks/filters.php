<?php
/**
 * Filter Hooks
 *
 * Custom filter hooks for this theme.
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Customize the "read more" text for excerpts
 */
function aqualuxe_excerpt_more($more) {
    if (!is_admin()) {
        global $post;
        $more = '... <a href="' . get_permalink($post->ID) . '" class="read-more-link">' . __('Read More', 'aqualuxe') . '</a>';
    }
    return $more;
}
add_filter('excerpt_more', 'aqualuxe_excerpt_more');

/**
 * Add custom classes to WordPress galleries
 */
function aqualuxe_gallery_style($output, $attr) {
    global $post;
    
    $defaults = array(
        'columns' => 3,
        'size' => 'thumbnail',
        'class' => 'aqualuxe-gallery'
    );
    
    $attr = wp_parse_args($attr, $defaults);
    
    // Add custom classes
    $class = $attr['class'] . ' gallery-columns-' . $attr['columns'];
    
    return str_replace('<div class="gallery', '<div class="' . esc_attr($class) . ' gallery', $output);
}
add_filter('gallery_style', 'aqualuxe_gallery_style', 10, 2);

/**
 * Customize WordPress search form
 */
function aqualuxe_search_form($form) {
    $form = '<form role="search" method="get" class="search-form" action="' . home_url('/') . '">
        <div class="search-form-inner flex">
            <label for="search-field" class="screen-reader-text">' . __('Search for:', 'aqualuxe') . '</label>
            <input type="search" id="search-field" class="search-field form-input flex-1" placeholder="' . esc_attr__('Search...', 'aqualuxe') . '" value="' . get_search_query() . '" name="s" required>
            <button type="submit" class="search-submit btn-primary ml-2">
                <span class="screen-reader-text">' . __('Search', 'aqualuxe') . '</span>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </button>
        </div>
    </form>';
    
    return $form;
}
add_filter('get_search_form', 'aqualuxe_search_form');

/**
 * Add custom classes to password form
 */
function aqualuxe_password_form($output) {
    global $post;
    
    $label = 'pwbox-' . (empty($post->ID) ? rand() : $post->ID);
    $output = '<form action="' . esc_url(site_url('wp-login.php?action=postpass', 'login_post')) . '" class="post-password-form space-y-4" method="post">
        <p class="text-secondary-600 dark:text-secondary-400">' . __('This content is password protected. To view it please enter your password below:', 'aqualuxe') . '</p>
        <div class="password-form-inner flex">
            <label for="' . $label . '" class="screen-reader-text">' . __('Password:', 'aqualuxe') . '</label>
            <input name="post_password" id="' . $label . '" type="password" class="form-input flex-1" placeholder="' . esc_attr__('Enter password', 'aqualuxe') . '" required>
            <button type="submit" name="Submit" class="btn-primary ml-2">' . esc_attr__('Enter', 'aqualuxe') . '</button>
        </div>
    </form>';
    
    return $output;
}
add_filter('the_password_form', 'aqualuxe_password_form');

/**
 * Customize pagination arguments
 */
function aqualuxe_posts_pagination_args($args) {
    $args['prev_text'] = '<span class="screen-reader-text">' . __('Previous page', 'aqualuxe') . '</span>
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
        </svg>';
    
    $args['next_text'] = '<span class="screen-reader-text">' . __('Next page', 'aqualuxe') . '</span>
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>';
    
    return $args;
}
add_filter('the_posts_pagination_args', 'aqualuxe_posts_pagination_args');

/**
 * Customize post navigation arguments
 */
function aqualuxe_post_navigation_args($args) {
    $args['prev_text'] = '<span class="nav-subtitle">' . __('Previous Post', 'aqualuxe') . '</span>
        <span class="nav-title">%title</span>';
    
    $args['next_text'] = '<span class="nav-subtitle">' . __('Next Post', 'aqualuxe') . '</span>
        <span class="nav-title">%title</span>';
    
    return $args;
}
add_filter('the_post_navigation_args', 'aqualuxe_post_navigation_args');

/**
 * Add custom attributes to menu links
 */
function aqualuxe_nav_menu_link_attributes($atts, $item, $args, $depth) {
    // Add class to menu links
    $atts['class'] = 'nav-link';
    
    // Add target="_blank" for external links
    if (!empty($item->url) && !str_contains($item->url, home_url())) {
        $atts['target'] = '_blank';
        $atts['rel'] = 'noopener noreferrer';
    }
    
    return $atts;
}
add_filter('nav_menu_link_attributes', 'aqualuxe_nav_menu_link_attributes', 10, 4);

/**
 * Modify archive title
 */
function aqualuxe_get_the_archive_title($title) {
    if (is_category()) {
        $title = single_cat_title('', false);
    } elseif (is_tag()) {
        $title = single_tag_title('', false);
    } elseif (is_author()) {
        $title = '<span class="vcard">' . get_the_author() . '</span>';
    } elseif (is_post_type_archive()) {
        $title = post_type_archive_title('', false);
    } elseif (is_tax()) {
        $title = single_term_title('', false);
    }
    
    return $title;
}
add_filter('get_the_archive_title', 'aqualuxe_get_the_archive_title');

/**
 * Add custom classes to comment list
 */
function aqualuxe_comment_list_args($args) {
    $args['avatar_size'] = 64;
    $args['style'] = 'div';
    $args['short_ping'] = true;
    $args['reply_text'] = __('Reply', 'aqualuxe');
    
    return $args;
}
add_filter('wp_list_comments_args', 'aqualuxe_comment_list_args');

/**
 * Customize oEmbed output
 */
function aqualuxe_oembed_dataparse($return, $data, $url) {
    if ($data && isset($data->html)) {
        // Wrap oEmbed content in responsive container
        $return = '<div class="oembed-container">' . $return . '</div>';
    }
    
    return $return;
}
add_filter('oembed_dataparse', 'aqualuxe_oembed_dataparse', 10, 3);

/**
 * Add responsive classes to embedded videos
 */
function aqualuxe_embed_oembed_html($html, $url, $attr, $post_id) {
    // Add responsive wrapper to video embeds
    if (strpos($html, 'video') !== false || strpos($html, 'youtube') !== false || strpos($html, 'vimeo') !== false) {
        $html = '<div class="video-embed-responsive">' . $html . '</div>';
    }
    
    return $html;
}
add_filter('embed_oembed_html', 'aqualuxe_embed_oembed_html', 10, 4);

/**
 * Customize WordPress default gallery output
 */
function aqualuxe_post_gallery($output, $attr, $instance) {
    $post = get_post();
    
    static $instance = 0;
    $instance++;
    
    if (!empty($attr['ids'])) {
        // 'ids' is explicitly ordered, unless you specify otherwise.
        if (empty($attr['orderby'])) {
            $attr['orderby'] = 'post__in';
        }
        $attr['include'] = $attr['ids'];
    }
    
    $atts = shortcode_atts(array(
        'order'      => 'ASC',
        'orderby'    => 'menu_order ID',
        'id'         => $post ? $post->ID : 0,
        'itemtag'    => 'figure',
        'icontag'    => 'div',
        'captiontag' => 'figcaption',
        'columns'    => 3,
        'size'       => 'medium',
        'include'    => '',
        'exclude'    => '',
        'link'       => ''
    ), $attr, 'gallery');
    
    $id = intval($atts['id']);
    
    if (!empty($atts['include'])) {
        $_attachments = get_posts(array(
            'include'        => $atts['include'],
            'post_status'    => 'inherit',
            'post_type'      => 'attachment',
            'post_mime_type' => 'image',
            'order'          => $atts['order'],
            'orderby'        => $atts['orderby']
        ));
        
        $attachments = array();
        foreach ($_attachments as $key => $val) {
            $attachments[$val->ID] = $_attachments[$key];
        }
    } elseif (!empty($atts['exclude'])) {
        $attachments = get_children(array(
            'post_parent'    => $id,
            'exclude'        => $atts['exclude'],
            'post_status'    => 'inherit',
            'post_type'      => 'attachment',
            'post_mime_type' => 'image',
            'order'          => $atts['order'],
            'orderby'        => $atts['orderby']
        ));
    } else {
        $attachments = get_children(array(
            'post_parent'    => $id,
            'post_status'    => 'inherit',
            'post_type'      => 'attachment',
            'post_mime_type' => 'image',
            'order'          => $atts['order'],
            'orderby'        => $atts['orderby']
        ));
    }
    
    if (empty($attachments)) {
        return '';
    }
    
    $columns = intval($atts['columns']);
    $size_class = sanitize_html_class($atts['size']);
    
    $gallery_div = "<div class='aqualuxe-gallery gallery gallery-columns-{$columns} gallery-size-{$size_class}'>";
    
    $i = 0;
    foreach ($attachments as $id => $attachment) {
        $attr = (trim($attachment->post_excerpt)) ? array('aria-describedby' => "gallery-{$instance}-{$id}") : array();
        
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
        
        $gallery_div .= "<{$atts['itemtag']} class='gallery-item'>";
        $gallery_div .= "
            <{$atts['icontag']} class='gallery-icon {$orientation}'>
                $image_output
            </{$atts['icontag']}>";
        
        if ($atts['captiontag'] && trim($attachment->post_excerpt)) {
            $gallery_div .= "
                <{$atts['captiontag']} class='wp-caption-text gallery-caption' id='gallery-{$instance}-{$id}'>
                " . wptexturize($attachment->post_excerpt) . "
                </{$atts['captiontag']}>";
        }
        
        $gallery_div .= "</{$atts['itemtag']}>";
        
        if ($columns > 0 && ++$i % $columns == 0) {
            $gallery_div .= '<br style="clear: both" />';
        }
    }
    
    $gallery_div .= "
        <br style='clear: both;' />
        </div>\n";
    
    return $gallery_div;
}
add_filter('post_gallery', 'aqualuxe_post_gallery', 10, 3);

/**
 * Disable WordPress default jQuery and use CDN version (optional)
 */
function aqualuxe_jquery_cdn() {
    if (!is_admin()) {
        wp_deregister_script('jquery');
        wp_register_script('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/3.7.0/jquery.min.js', false, '3.7.0', true);
        wp_enqueue_script('jquery');
    }
}
// Uncomment the line below to enable CDN jQuery
// add_action('wp_enqueue_scripts', 'aqualuxe_jquery_cdn');