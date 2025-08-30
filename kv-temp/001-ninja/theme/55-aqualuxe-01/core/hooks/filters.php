<?php
/**
 * AquaLuxe Filters
 *
 * This file contains filter hooks used throughout the theme.
 *
 * @package AquaLuxe
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Filter the excerpt length
 *
 * @param int $length Excerpt length.
 * @return int Modified excerpt length.
 */
function aqualuxe_excerpt_length($length) {
    return aqualuxe_get_option('excerpt_length', 55);
}
add_filter('excerpt_length', 'aqualuxe_excerpt_length');

/**
 * Filter the excerpt "read more" string
 *
 * @param string $more "Read more" excerpt string.
 * @return string Modified "read more" excerpt string.
 */
function aqualuxe_excerpt_more($more) {
    return '&hellip;';
}
add_filter('excerpt_more', 'aqualuxe_excerpt_more');

/**
 * Filter the content "read more" link
 *
 * @param string $link Read more link.
 * @return string Modified read more link.
 */
function aqualuxe_content_more_link($link) {
    $read_more_text = aqualuxe_get_option('read_more_text', __('Read More', 'aqualuxe'));
    return str_replace('more-link', 'more-link button', $link);
}
add_filter('the_content_more_link', 'aqualuxe_content_more_link');

/**
 * Filter the archive title
 *
 * @param string $title Archive title.
 * @return string Modified archive title.
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
 * Filter the archive description
 *
 * @param string $description Archive description.
 * @return string Modified archive description.
 */
function aqualuxe_archive_description($description) {
    if (is_author()) {
        $description = get_the_author_meta('description');
    }

    return $description;
}
add_filter('get_the_archive_description', 'aqualuxe_archive_description');

/**
 * Filter the navigation menu items
 *
 * @param array $items Menu items.
 * @param object $args Menu arguments.
 * @return array Modified menu items.
 */
function aqualuxe_nav_menu_items($items, $args) {
    // Add custom menu items based on theme options
    if ($args->theme_location === 'primary') {
        // Add custom menu items here
    }

    return $items;
}
add_filter('wp_nav_menu_items', 'aqualuxe_nav_menu_items', 10, 2);

/**
 * Filter the navigation menu CSS class
 *
 * @param array $classes Menu item classes.
 * @param object $item Menu item.
 * @param object $args Menu arguments.
 * @return array Modified menu item classes.
 */
function aqualuxe_nav_menu_css_class($classes, $item, $args) {
    // Add custom classes to menu items
    if ($args->theme_location === 'primary') {
        // Add custom classes here
    }

    return $classes;
}
add_filter('nav_menu_css_class', 'aqualuxe_nav_menu_css_class', 10, 3);

/**
 * Filter the body classes
 *
 * @param array $classes Body classes.
 * @return array Modified body classes.
 */
function aqualuxe_body_classes($classes) {
    // Add a class if there is a custom header image
    if (has_header_image()) {
        $classes[] = 'has-header-image';
    }

    // Add a class if the site is using a custom logo
    if (has_custom_logo()) {
        $classes[] = 'has-custom-logo';
    }

    // Add a class for the layout
    $layout = aqualuxe_get_option('layout_type', 'right-sidebar');
    $classes[] = 'layout-' . $layout;

    // Add a class for the header layout
    $header_layout = aqualuxe_get_option('header_layout', 'default');
    $classes[] = 'header-layout-' . $header_layout;

    // Add a class for the footer layout
    $footer_layout = aqualuxe_get_option('footer_layout', 'default');
    $classes[] = 'footer-layout-' . $footer_layout;

    // Add a class if WooCommerce is active
    if (aqualuxe_is_woocommerce_active()) {
        $classes[] = 'woocommerce-active';
    }

    return $classes;
}
add_filter('body_class', 'aqualuxe_body_classes');

/**
 * Filter the post classes
 *
 * @param array $classes Post classes.
 * @return array Modified post classes.
 */
function aqualuxe_post_classes($classes) {
    // Add custom post classes
    $classes[] = 'entry';

    // Add a class if the post has a featured image
    if (has_post_thumbnail()) {
        $classes[] = 'has-post-thumbnail';
    }

    return $classes;
}
add_filter('post_class', 'aqualuxe_post_classes');

/**
 * Filter the comment form defaults
 *
 * @param array $defaults Comment form defaults.
 * @return array Modified comment form defaults.
 */
function aqualuxe_comment_form_defaults($defaults) {
    // Modify comment form defaults
    $defaults['comment_notes_before'] = '<p class="comment-notes">' . esc_html__('Your email address will not be published.', 'aqualuxe') . '</p>';
    $defaults['comment_field'] = '<div class="comment-form-comment"><label for="comment">' . esc_html__('Comment', 'aqualuxe') . '</label><textarea id="comment" name="comment" cols="45" rows="8" required></textarea></div>';
    $defaults['class_submit'] = 'submit button';

    return $defaults;
}
add_filter('comment_form_defaults', 'aqualuxe_comment_form_defaults');

/**
 * Filter the comment form fields
 *
 * @param array $fields Comment form fields.
 * @return array Modified comment form fields.
 */
function aqualuxe_comment_form_fields($fields) {
    // Modify comment form fields
    $commenter = wp_get_current_commenter();
    $req = get_option('require_name_email');
    $aria_req = ($req ? ' aria-required="true"' : '');

    $fields['author'] = '<div class="comment-form-author"><label for="author">' . esc_html__('Name', 'aqualuxe') . ($req ? ' <span class="required">*</span>' : '') . '</label><input id="author" name="author" type="text" value="' . esc_attr($commenter['comment_author']) . '" size="30" maxlength="245"' . $aria_req . ' /></div>';
    $fields['email'] = '<div class="comment-form-email"><label for="email">' . esc_html__('Email', 'aqualuxe') . ($req ? ' <span class="required">*</span>' : '') . '</label><input id="email" name="email" type="email" value="' . esc_attr($commenter['comment_author_email']) . '" size="30" maxlength="100" aria-describedby="email-notes"' . $aria_req . ' /></div>';
    $fields['url'] = '<div class="comment-form-url"><label for="url">' . esc_html__('Website', 'aqualuxe') . '</label><input id="url" name="url" type="url" value="' . esc_attr($commenter['comment_author_url']) . '" size="30" maxlength="200" /></div>';

    return $fields;
}
add_filter('comment_form_default_fields', 'aqualuxe_comment_form_fields');

/**
 * Filter the search form
 *
 * @param string $form Search form HTML.
 * @return string Modified search form HTML.
 */
function aqualuxe_search_form($form) {
    $form = '<form role="search" method="get" class="search-form" action="' . esc_url(home_url('/')) . '">
        <label>
            <span class="screen-reader-text">' . esc_html__('Search for:', 'aqualuxe') . '</span>
            <input type="search" class="search-field" placeholder="' . esc_attr__('Search...', 'aqualuxe') . '" value="' . get_search_query() . '" name="s" />
        </label>
        <button type="submit" class="search-submit">
            <span class="screen-reader-text">' . esc_html__('Search', 'aqualuxe') . '</span>
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
        </button>
    </form>';

    return $form;
}
add_filter('get_search_form', 'aqualuxe_search_form');

/**
 * Filter the gallery shortcode output
 *
 * @param string $output Gallery output.
 * @param array $attr Gallery attributes.
 * @return string Modified gallery output.
 */
function aqualuxe_gallery_shortcode($output, $attr) {
    // Modify gallery shortcode output
    return $output;
}
add_filter('post_gallery', 'aqualuxe_gallery_shortcode', 10, 2);

/**
 * Filter the allowed HTML tags
 *
 * @param array $tags Allowed HTML tags.
 * @param string $context Context.
 * @return array Modified allowed HTML tags.
 */
function aqualuxe_allowed_html($tags, $context) {
    if ($context === 'post') {
        // Add SVG support
        $tags['svg'] = [
            'xmlns' => true,
            'width' => true,
            'height' => true,
            'viewbox' => true,
            'fill' => true,
            'stroke' => true,
            'stroke-width' => true,
            'stroke-linecap' => true,
            'stroke-linejoin' => true,
            'class' => true,
        ];
        $tags['path'] = [
            'd' => true,
            'fill' => true,
            'stroke' => true,
            'stroke-width' => true,
        ];
        $tags['circle'] = [
            'cx' => true,
            'cy' => true,
            'r' => true,
            'fill' => true,
            'stroke' => true,
            'stroke-width' => true,
        ];
        $tags['line'] = [
            'x1' => true,
            'y1' => true,
            'x2' => true,
            'y2' => true,
            'fill' => true,
            'stroke' => true,
            'stroke-width' => true,
        ];
        $tags['polyline'] = [
            'points' => true,
            'fill' => true,
            'stroke' => true,
            'stroke-width' => true,
        ];
    }

    return $tags;
}
add_filter('wp_kses_allowed_html', 'aqualuxe_allowed_html', 10, 2);

/**
 * Filter the upload mimes
 *
 * @param array $mimes Allowed mime types.
 * @return array Modified allowed mime types.
 */
function aqualuxe_upload_mimes($mimes) {
    // Add SVG support
    $mimes['svg'] = 'image/svg+xml';
    $mimes['svgz'] = 'image/svg+xml';

    return $mimes;
}
add_filter('upload_mimes', 'aqualuxe_upload_mimes');

/**
 * Filter the script loader tag
 *
 * @param string $tag Script tag.
 * @param string $handle Script handle.
 * @param string $src Script source.
 * @return string Modified script tag.
 */
function aqualuxe_script_loader_tag($tag, $handle, $src) {
    // Add defer attribute to main script
    if ($handle === 'aqualuxe-script') {
        $tag = str_replace(' src', ' defer src', $tag);
    }

    return $tag;
}
add_filter('script_loader_tag', 'aqualuxe_script_loader_tag', 10, 3);

/**
 * Filter the style loader tag
 *
 * @param string $html Style tag.
 * @param string $handle Style handle.
 * @param string $href Style source.
 * @param string $media Style media.
 * @return string Modified style tag.
 */
function aqualuxe_style_loader_tag($html, $handle, $href, $media) {
    // Add media attribute to print styles
    if ($handle === 'aqualuxe-print-style') {
        $html = str_replace('media=\'all\'', 'media=\'print\'', $html);
    }

    return $html;
}
add_filter('style_loader_tag', 'aqualuxe_style_loader_tag', 10, 4);

/**
 * Filter the embed defaults
 *
 * @param array $embed_defaults Embed defaults.
 * @return array Modified embed defaults.
 */
function aqualuxe_embed_defaults($embed_defaults) {
    // Modify embed defaults
    $embed_defaults['width'] = 1280;
    $embed_defaults['height'] = 720;

    return $embed_defaults;
}
add_filter('embed_defaults', 'aqualuxe_embed_defaults');

/**
 * Filter the embed HTML
 *
 * @param string $html Embed HTML.
 * @param string $url Embed URL.
 * @param array $attr Embed attributes.
 * @param int $post_id Post ID.
 * @return string Modified embed HTML.
 */
function aqualuxe_embed_html($html, $url, $attr, $post_id) {
    // Wrap embeds in a responsive container
    return '<div class="embed-responsive">' . $html . '</div>';
}
add_filter('embed_oembed_html', 'aqualuxe_embed_html', 10, 4);

/**
 * Filter the content width
 *
 * @param int $content_width Content width.
 * @return int Modified content width.
 */
function aqualuxe_content_width($content_width) {
    // Modify content width based on layout
    $layout = aqualuxe_get_option('layout_type', 'right-sidebar');

    if ($layout === 'full-width') {
        $content_width = 1280;
    } else {
        $content_width = 840;
    }

    return $content_width;
}
add_filter('aqualuxe_content_width', 'aqualuxe_content_width');

/**
 * Filter the image size attributes
 *
 * @param array $attr Image attributes.
 * @param WP_Post $attachment Image attachment.
 * @param string|array $size Image size.
 * @return array Modified image attributes.
 */
function aqualuxe_image_size_attributes($attr, $attachment, $size) {
    // Add loading="lazy" attribute to images
    if (!isset($attr['loading'])) {
        $attr['loading'] = 'lazy';
    }

    return $attr;
}
add_filter('wp_get_attachment_image_attributes', 'aqualuxe_image_size_attributes', 10, 3);

/**
 * Filter the content images
 *
 * @param string $content Post content.
 * @return string Modified post content.
 */
function aqualuxe_content_images($content) {
    // Add loading="lazy" attribute to content images
    if (!is_admin()) {
        $content = preg_replace('/<img(.*?)>/i', '<img$1 loading="lazy">', $content);
    }

    return $content;
}
add_filter('the_content', 'aqualuxe_content_images');

/**
 * Filter the navigation menu link attributes
 *
 * @param array $atts Link attributes.
 * @param object $item Menu item.
 * @param object $args Menu arguments.
 * @param int $depth Menu depth.
 * @return array Modified link attributes.
 */
function aqualuxe_nav_menu_link_attributes($atts, $item, $args, $depth) {
    // Add custom attributes to menu links
    if ($args->theme_location === 'primary') {
        // Add custom attributes here
    }

    return $atts;
}
add_filter('nav_menu_link_attributes', 'aqualuxe_nav_menu_link_attributes', 10, 4);

/**
 * Filter the page template
 *
 * @param string $template Page template.
 * @return string Modified page template.
 */
function aqualuxe_page_template($template) {
    // Modify page template based on page ID or other conditions
    return $template;
}
add_filter('page_template', 'aqualuxe_page_template');

/**
 * Filter the single template
 *
 * @param string $template Single template.
 * @return string Modified single template.
 */
function aqualuxe_single_template($template) {
    // Modify single template based on post type or other conditions
    return $template;
}
add_filter('single_template', 'aqualuxe_single_template');

/**
 * Filter the archive template
 *
 * @param string $template Archive template.
 * @return string Modified archive template.
 */
function aqualuxe_archive_template($template) {
    // Modify archive template based on post type or other conditions
    return $template;
}
add_filter('archive_template', 'aqualuxe_archive_template');

/**
 * Filter the search template
 *
 * @param string $template Search template.
 * @return string Modified search template.
 */
function aqualuxe_search_template($template) {
    // Modify search template based on search query or other conditions
    return $template;
}
add_filter('search_template', 'aqualuxe_search_template');

/**
 * Filter the 404 template
 *
 * @param string $template 404 template.
 * @return string Modified 404 template.
 */
function aqualuxe_404_template($template) {
    // Modify 404 template based on conditions
    return $template;
}
add_filter('404_template', 'aqualuxe_404_template');

/**
 * Filter the document title
 *
 * @param string $title Document title.
 * @return string Modified document title.
 */
function aqualuxe_document_title($title) {
    // Modify document title based on conditions
    return $title;
}
add_filter('document_title', 'aqualuxe_document_title');

/**
 * Filter the document title parts
 *
 * @param array $title_parts Document title parts.
 * @return array Modified document title parts.
 */
function aqualuxe_document_title_parts($title_parts) {
    // Modify document title parts based on conditions
    return $title_parts;
}
add_filter('document_title_parts', 'aqualuxe_document_title_parts');

/**
 * Filter the document title separator
 *
 * @param string $sep Document title separator.
 * @return string Modified document title separator.
 */
function aqualuxe_document_title_separator($sep) {
    // Modify document title separator
    return '|';
}
add_filter('document_title_separator', 'aqualuxe_document_title_separator');

/**
 * Filter the login URL
 *
 * @param string $login_url Login URL.
 * @param string $redirect Redirect URL.
 * @param bool $force_reauth Force reauth.
 * @return string Modified login URL.
 */
function aqualuxe_login_url($login_url, $redirect, $force_reauth) {
    // Modify login URL based on conditions
    return $login_url;
}
add_filter('login_url', 'aqualuxe_login_url', 10, 3);

/**
 * Filter the logout URL
 *
 * @param string $logout_url Logout URL.
 * @param string $redirect Redirect URL.
 * @return string Modified logout URL.
 */
function aqualuxe_logout_url($logout_url, $redirect) {
    // Modify logout URL based on conditions
    return $logout_url;
}
add_filter('logout_url', 'aqualuxe_logout_url', 10, 2);

/**
 * Filter the registration URL
 *
 * @param string $registration_url Registration URL.
 * @return string Modified registration URL.
 */
function aqualuxe_registration_url($registration_url) {
    // Modify registration URL based on conditions
    return $registration_url;
}
add_filter('register_url', 'aqualuxe_registration_url');

/**
 * Filter the lost password URL
 *
 * @param string $lostpassword_url Lost password URL.
 * @param string $redirect Redirect URL.
 * @return string Modified lost password URL.
 */
function aqualuxe_lostpassword_url($lostpassword_url, $redirect) {
    // Modify lost password URL based on conditions
    return $lostpassword_url;
}
add_filter('lostpassword_url', 'aqualuxe_lostpassword_url', 10, 2);

/**
 * Filter the author URL
 *
 * @param string $link Author URL.
 * @param int $author_id Author ID.
 * @param string $author_nicename Author nicename.
 * @return string Modified author URL.
 */
function aqualuxe_author_link($link, $author_id, $author_nicename) {
    // Modify author URL based on conditions
    return $link;
}
add_filter('author_link', 'aqualuxe_author_link', 10, 3);

/**
 * Filter the post type archive link
 *
 * @param string $link Post type archive link.
 * @param string $post_type Post type.
 * @return string Modified post type archive link.
 */
function aqualuxe_post_type_archive_link($link, $post_type) {
    // Modify post type archive link based on conditions
    return $link;
}
add_filter('post_type_archive_link', 'aqualuxe_post_type_archive_link', 10, 2);

/**
 * Filter the term link
 *
 * @param string $link Term link.
 * @param object $term Term object.
 * @param string $taxonomy Taxonomy.
 * @return string Modified term link.
 */
function aqualuxe_term_link($link, $term, $taxonomy) {
    // Modify term link based on conditions
    return $link;
}
add_filter('term_link', 'aqualuxe_term_link', 10, 3);

/**
 * Filter the post link
 *
 * @param string $permalink Post permalink.
 * @param object $post Post object.
 * @param bool $leavename Whether to leave the post name.
 * @return string Modified post permalink.
 */
function aqualuxe_post_link($permalink, $post, $leavename) {
    // Modify post permalink based on conditions
    return $permalink;
}
add_filter('post_link', 'aqualuxe_post_link', 10, 3);

/**
 * Filter the page link
 *
 * @param string $link Page link.
 * @param int $post_id Post ID.
 * @param bool $sample Whether it is a sample permalink.
 * @return string Modified page link.
 */
function aqualuxe_page_link($link, $post_id, $sample) {
    // Modify page link based on conditions
    return $link;
}
add_filter('page_link', 'aqualuxe_page_link', 10, 3);

/**
 * Filter the attachment link
 *
 * @param string $link Attachment link.
 * @param int $post_id Post ID.
 * @return string Modified attachment link.
 */
function aqualuxe_attachment_link($link, $post_id) {
    // Modify attachment link based on conditions
    return $link;
}
add_filter('attachment_link', 'aqualuxe_attachment_link', 10, 2);

/**
 * Filter the get terms args
 *
 * @param array $args Get terms args.
 * @param array $taxonomies Taxonomies.
 * @return array Modified get terms args.
 */
function aqualuxe_get_terms_args($args, $taxonomies) {
    // Modify get terms args based on conditions
    return $args;
}
add_filter('get_terms_args', 'aqualuxe_get_terms_args', 10, 2);

/**
 * Filter the terms clauses
 *
 * @param array $clauses Terms clauses.
 * @param array $taxonomies Taxonomies.
 * @param array $args Get terms args.
 * @return array Modified terms clauses.
 */
function aqualuxe_terms_clauses($clauses, $taxonomies, $args) {
    // Modify terms clauses based on conditions
    return $clauses;
}
add_filter('terms_clauses', 'aqualuxe_terms_clauses', 10, 3);

/**
 * Filter the posts clauses
 *
 * @param array $clauses Posts clauses.
 * @param WP_Query $wp_query WP_Query object.
 * @return array Modified posts clauses.
 */
function aqualuxe_posts_clauses($clauses, $wp_query) {
    // Modify posts clauses based on conditions
    return $clauses;
}
add_filter('posts_clauses', 'aqualuxe_posts_clauses', 10, 2);

/**
 * Filter the posts join
 *
 * @param string $join Posts join.
 * @param WP_Query $wp_query WP_Query object.
 * @return string Modified posts join.
 */
function aqualuxe_posts_join($join, $wp_query) {
    // Modify posts join based on conditions
    return $join;
}
add_filter('posts_join', 'aqualuxe_posts_join', 10, 2);

/**
 * Filter the posts where
 *
 * @param string $where Posts where.
 * @param WP_Query $wp_query WP_Query object.
 * @return string Modified posts where.
 */
function aqualuxe_posts_where($where, $wp_query) {
    // Modify posts where based on conditions
    return $where;
}
add_filter('posts_where', 'aqualuxe_posts_where', 10, 2);

/**
 * Filter the posts orderby
 *
 * @param string $orderby Posts orderby.
 * @param WP_Query $wp_query WP_Query object.
 * @return string Modified posts orderby.
 */
function aqualuxe_posts_orderby($orderby, $wp_query) {
    // Modify posts orderby based on conditions
    return $orderby;
}
add_filter('posts_orderby', 'aqualuxe_posts_orderby', 10, 2);

/**
 * Filter the posts groupby
 *
 * @param string $groupby Posts groupby.
 * @param WP_Query $wp_query WP_Query object.
 * @return string Modified posts groupby.
 */
function aqualuxe_posts_groupby($groupby, $wp_query) {
    // Modify posts groupby based on conditions
    return $groupby;
}
add_filter('posts_groupby', 'aqualuxe_posts_groupby', 10, 2);

/**
 * Filter the posts distinct
 *
 * @param string $distinct Posts distinct.
 * @param WP_Query $wp_query WP_Query object.
 * @return string Modified posts distinct.
 */
function aqualuxe_posts_distinct($distinct, $wp_query) {
    // Modify posts distinct based on conditions
    return $distinct;
}
add_filter('posts_distinct', 'aqualuxe_posts_distinct', 10, 2);

/**
 * Filter the posts limits
 *
 * @param string $limits Posts limits.
 * @param WP_Query $wp_query WP_Query object.
 * @return string Modified posts limits.
 */
function aqualuxe_posts_limits($limits, $wp_query) {
    // Modify posts limits based on conditions
    return $limits;
}
add_filter('posts_limits', 'aqualuxe_posts_limits', 10, 2);

/**
 * Filter the posts fields
 *
 * @param string $fields Posts fields.
 * @param WP_Query $wp_query WP_Query object.
 * @return string Modified posts fields.
 */
function aqualuxe_posts_fields($fields, $wp_query) {
    // Modify posts fields based on conditions
    return $fields;
}
add_filter('posts_fields', 'aqualuxe_posts_fields', 10, 2);

/**
 * Filter the posts request
 *
 * @param string $request Posts request.
 * @param WP_Query $wp_query WP_Query object.
 * @return string Modified posts request.
 */
function aqualuxe_posts_request($request, $wp_query) {
    // Modify posts request based on conditions
    return $request;
}
add_filter('posts_request', 'aqualuxe_posts_request', 10, 2);

/**
 * Filter the posts results
 *
 * @param array $posts Posts results.
 * @param WP_Query $wp_query WP_Query object.
 * @return array Modified posts results.
 */
function aqualuxe_posts_results($posts, $wp_query) {
    // Modify posts results based on conditions
    return $posts;
}
add_filter('posts_results', 'aqualuxe_posts_results', 10, 2);

/**
 * Filter the posts pre query
 *
 * @param null $null Null value.
 * @param WP_Query $wp_query WP_Query object.
 * @return null|array Modified posts pre query.
 */
function aqualuxe_posts_pre_query($null, $wp_query) {
    // Modify posts pre query based on conditions
    return $null;
}
add_filter('posts_pre_query', 'aqualuxe_posts_pre_query', 10, 2);

/**
 * Filter the found posts
 *
 * @param int $found_posts Found posts.
 * @param WP_Query $wp_query WP_Query object.
 * @return int Modified found posts.
 */
function aqualuxe_found_posts($found_posts, $wp_query) {
    // Modify found posts based on conditions
    return $found_posts;
}
add_filter('found_posts', 'aqualuxe_found_posts', 10, 2);

/**
 * Filter the found posts query
 *
 * @param string $query Found posts query.
 * @param WP_Query $wp_query WP_Query object.
 * @return string Modified found posts query.
 */
function aqualuxe_found_posts_query($query, $wp_query) {
    // Modify found posts query based on conditions
    return $query;
}
add_filter('found_posts_query', 'aqualuxe_found_posts_query', 10, 2);

/**
 * Filter the post thumbnail HTML
 *
 * @param string $html Post thumbnail HTML.
 * @param int $post_id Post ID.
 * @param int $post_thumbnail_id Post thumbnail ID.
 * @param string|array $size Post thumbnail size.
 * @param array $attr Post thumbnail attributes.
 * @return string Modified post thumbnail HTML.
 */
function aqualuxe_post_thumbnail_html($html, $post_id, $post_thumbnail_id, $size, $attr) {
    // Modify post thumbnail HTML based on conditions
    return $html;
}
add_filter('post_thumbnail_html', 'aqualuxe_post_thumbnail_html', 10, 5);

/**
 * Filter the get attachment image src
 *
 * @param array $image Image src.
 * @param int $attachment_id Attachment ID.
 * @param string|array $size Image size.
 * @param bool $icon Whether to use icon.
 * @return array Modified image src.
 */
function aqualuxe_get_attachment_image_src($image, $attachment_id, $size, $icon) {
    // Modify image src based on conditions
    return $image;
}
add_filter('wp_get_attachment_image_src', 'aqualuxe_get_attachment_image_src', 10, 4);

/**
 * Filter the get attachment image attributes
 *
 * @param array $attr Image attributes.
 * @param WP_Post $attachment Image attachment.
 * @param string|array $size Image size.
 * @return array Modified image attributes.
 */
function aqualuxe_get_attachment_image_attributes($attr, $attachment, $size) {
    // Add loading="lazy" attribute to images
    if (!isset($attr['loading'])) {
        $attr['loading'] = 'lazy';
    }

    return $attr;
}
add_filter('wp_get_attachment_image_attributes', 'aqualuxe_get_attachment_image_attributes', 10, 3);

/**
 * Filter the intermediate image sizes
 *
 * @param array $sizes Intermediate image sizes.
 * @return array Modified intermediate image sizes.
 */
function aqualuxe_intermediate_image_sizes($sizes) {
    // Modify intermediate image sizes based on conditions
    return $sizes;
}
add_filter('intermediate_image_sizes', 'aqualuxe_intermediate_image_sizes');

/**
 * Filter the intermediate image sizes advanced
 *
 * @param array $sizes Intermediate image sizes.
 * @param array $metadata Image metadata.
 * @param int $attachment_id Attachment ID.
 * @return array Modified intermediate image sizes.
 */
function aqualuxe_intermediate_image_sizes_advanced($sizes, $metadata, $attachment_id) {
    // Modify intermediate image sizes based on conditions
    return $sizes;
}
add_filter('intermediate_image_sizes_advanced', 'aqualuxe_intermediate_image_sizes_advanced', 10, 3);

/**
 * Filter the image resize dimensions
 *
 * @param array $dimensions Image resize dimensions.
 * @param string $orig_w Original width.
 * @param string $orig_h Original height.
 * @param string $new_w New width.
 * @param string $new_h New height.
 * @param bool $crop Whether to crop.
 * @return array Modified image resize dimensions.
 */
function aqualuxe_image_resize_dimensions($dimensions, $orig_w, $orig_h, $new_w, $new_h, $crop) {
    // Modify image resize dimensions based on conditions
    return $dimensions;
}
add_filter('image_resize_dimensions', 'aqualuxe_image_resize_dimensions', 10, 6);

/**
 * Filter the image quality
 *
 * @param int $quality Image quality.
 * @param string $mime_type Image mime type.
 * @return int Modified image quality.
 */
function aqualuxe_image_quality($quality, $mime_type) {
    // Modify image quality based on conditions
    return $quality;
}
add_filter('wp_editor_set_quality', 'aqualuxe_image_quality', 10, 2);

/**
 * Filter the image editor default to GD
 *
 * @param array $editors Image editors.
 * @return array Modified image editors.
 */
function aqualuxe_image_editor_default_to_gd($editors) {
    // Modify image editors based on conditions
    return $editors;
}
add_filter('wp_image_editors', 'aqualuxe_image_editor_default_to_gd');

/**
 * Filter the big image size threshold
 *
 * @param int $threshold Big image size threshold.
 * @param array $imagesize Image size.
 * @param string $file File path.
 * @param WP_Image_Editor $editor Image editor.
 * @return int Modified big image size threshold.
 */
function aqualuxe_big_image_size_threshold($threshold, $imagesize, $file, $editor) {
    // Modify big image size threshold based on conditions
    return $threshold;
}
add_filter('big_image_size_threshold', 'aqualuxe_big_image_size_threshold', 10, 4);

/**
 * Filter the image downsize
 *
 * @param array $downsize Image downsize.
 * @param int $attachment_id Attachment ID.
 * @param string|array $size Image size.
 * @return array Modified image downsize.
 */
function aqualuxe_image_downsize($downsize, $attachment_id, $size) {
    // Modify image downsize based on conditions
    return $downsize;
}
add_filter('image_downsize', 'aqualuxe_image_downsize', 10, 3);

/**
 * Filter the upload dir
 *
 * @param array $uploads Upload directory.
 * @return array Modified upload directory.
 */
function aqualuxe_upload_dir($uploads) {
    // Modify upload directory based on conditions
    return $uploads;
}
add_filter('upload_dir', 'aqualuxe_upload_dir');

/**
 * Filter the upload mimes
 *
 * @param array $mimes Allowed mime types.
 * @return array Modified allowed mime types.
 */
function aqualuxe_upload_mimes_filter($mimes) {
    // Add SVG support
    $mimes['svg'] = 'image/svg+xml';
    $mimes['svgz'] = 'image/svg+xml';

    return $mimes;
}
add_filter('upload_mimes', 'aqualuxe_upload_mimes_filter');

/**
 * Filter the wp handle upload prefilter
 *
 * @param array $file File data.
 * @return array Modified file data.
 */
function aqualuxe_wp_handle_upload_prefilter($file) {
    // Modify file data based on conditions
    return $file;
}
add_filter('wp_handle_upload_prefilter', 'aqualuxe_wp_handle_upload_prefilter');

/**
 * Filter the wp handle upload
 *
 * @param array $file File data.
 * @param string $action Upload action.
 * @return array Modified file data.
 */
function aqualuxe_wp_handle_upload($file, $action) {
    // Modify file data based on conditions
    return $file;
}
add_filter('wp_handle_upload', 'aqualuxe_wp_handle_upload', 10, 2);

/**
 * Filter the wp handle sideload
 *
 * @param array $file File data.
 * @param string $action Upload action.
 * @return array Modified file data.
 */
function aqualuxe_wp_handle_sideload($file, $action) {
    // Modify file data based on conditions
    return $file;
}
add_filter('wp_handle_sideload', 'aqualuxe_wp_handle_sideload', 10, 2);

/**
 * Filter the wp generate attachment metadata
 *
 * @param array $metadata Attachment metadata.
 * @param int $attachment_id Attachment ID.
 * @return array Modified attachment metadata.
 */
function aqualuxe_wp_generate_attachment_metadata($metadata, $attachment_id) {
    // Modify attachment metadata based on conditions
    return $metadata;
}
add_filter('wp_generate_attachment_metadata', 'aqualuxe_wp_generate_attachment_metadata', 10, 2);

/**
 * Filter the wp update attachment metadata
 *
 * @param array $metadata Attachment metadata.
 * @param int $attachment_id Attachment ID.
 * @return array Modified attachment metadata.
 */
function aqualuxe_wp_update_attachment_metadata($metadata, $attachment_id) {
    // Modify attachment metadata based on conditions
    return $metadata;
}
add_filter('wp_update_attachment_metadata', 'aqualuxe_wp_update_attachment_metadata', 10, 2);

/**
 * Filter the wp get attachment metadata
 *
 * @param array $metadata Attachment metadata.
 * @param int $attachment_id Attachment ID.
 * @return array Modified attachment metadata.
 */
function aqualuxe_wp_get_attachment_metadata($metadata, $attachment_id) {
    // Modify attachment metadata based on conditions
    return $metadata;
}
add_filter('wp_get_attachment_metadata', 'aqualuxe_wp_get_attachment_metadata', 10, 2);

/**
 * Filter the wp prepare attachment for js
 *
 * @param array $response Attachment response.
 * @param WP_Post $attachment Attachment post.
 * @param array $meta Attachment meta.
 * @return array Modified attachment response.
 */
function aqualuxe_wp_prepare_attachment_for_js($response, $attachment, $meta) {
    // Modify attachment response based on conditions
    return $response;
}
add_filter('wp_prepare_attachment_for_js', 'aqualuxe_wp_prepare_attachment_for_js', 10, 3);

/**
 * Filter the wp get attachment link
 *
 * @param string $link Attachment link.
 * @param int $attachment_id Attachment ID.
 * @return string Modified attachment link.
 */
function aqualuxe_wp_get_attachment_link($link, $attachment_id) {
    // Modify attachment link based on conditions
    return $link;
}
add_filter('wp_get_attachment_link', 'aqualuxe_wp_get_attachment_link', 10, 2);

/**
 * Filter the wp get attachment url
 *
 * @param string $url Attachment URL.
 * @param int $attachment_id Attachment ID.
 * @return string Modified attachment URL.
 */
function aqualuxe_wp_get_attachment_url($url, $attachment_id) {
    // Modify attachment URL based on conditions
    return $url;
}
add_filter('wp_get_attachment_url', 'aqualuxe_wp_get_attachment_url', 10, 2);

/**
 * Filter the wp get attachment caption
 *
 * @param string $caption Attachment caption.
 * @param int $attachment_id Attachment ID.
 * @return string Modified attachment caption.
 */
function aqualuxe_wp_get_attachment_caption($caption, $attachment_id) {
    // Modify attachment caption based on conditions
    return $caption;
}
add_filter('wp_get_attachment_caption', 'aqualuxe_wp_get_attachment_caption', 10, 2);

/**
 * Filter the wp calculate image srcset
 *
 * @param array $sources Image sources.
 * @param array $size_array Image size array.
 * @param string $image_src Image source.
 * @param array $image_meta Image meta.
 * @param int $attachment_id Attachment ID.
 * @return array Modified image sources.
 */
function aqualuxe_wp_calculate_image_srcset($sources, $size_array, $image_src, $image_meta, $attachment_id) {
    // Modify image sources based on conditions
    return $sources;
}
add_filter('wp_calculate_image_srcset', 'aqualuxe_wp_calculate_image_srcset', 10, 5);

/**
 * Filter the wp calculate image sizes
 *
 * @param string $sizes Image sizes.
 * @param array $size_array Image size array.
 * @param string $image_src Image source.
 * @param array $image_meta Image meta.
 * @param int $attachment_id Attachment ID.
 * @return string Modified image sizes.
 */
function aqualuxe_wp_calculate_image_sizes($sizes, $size_array, $image_src, $image_meta, $attachment_id) {
    // Modify image sizes based on conditions
    return $sizes;
}
add_filter('wp_calculate_image_sizes', 'aqualuxe_wp_calculate_image_sizes', 10, 5);

/**
 * Filter the wp get attachment image srcset
 *
 * @param array $sources Image sources.
 * @param array $size_array Image size array.
 * @param string $image_src Image source.
 * @param array $image_meta Image meta.
 * @param int $attachment_id Attachment ID.
 * @return array Modified image sources.
 */
function aqualuxe_wp_get_attachment_image_srcset($sources, $size_array, $image_src, $image_meta, $attachment_id) {
    // Modify image sources based on conditions
    return $sources;
}
add_filter('wp_get_attachment_image_srcset', 'aqualuxe_wp_get_attachment_image_srcset', 10, 5);

/**
 * Filter the wp get attachment image sizes
 *
 * @param string $sizes Image sizes.
 * @param array $size_array Image size array.
 * @param string $image_src Image source.
 * @param array $image_meta Image meta.
 * @param int $attachment_id Attachment ID.
 * @return string Modified image sizes.
 */
function aqualuxe_wp_get_attachment_image_sizes($sizes, $size_array, $image_src, $image_meta, $attachment_id) {
    // Modify image sizes based on conditions
    return $sizes;
}
add_filter('wp_get_attachment_image_sizes', 'aqualuxe_wp_get_attachment_image_sizes', 10, 5);

/**
 * Filter the wp get attachment image
 *
 * @param string $html Image HTML.
 * @param int $attachment_id Attachment ID.
 * @param string|array $size Image size.
 * @param bool $icon Whether to use icon.
 * @param array $attr Image attributes.
 * @return string Modified image HTML.
 */
function aqualuxe_wp_get_attachment_image($html, $attachment_id, $size, $icon, $attr) {
    // Modify image HTML based on conditions
    return $html;
}
add_filter('wp_get_attachment_image', 'aqualuxe_wp_get_attachment_image', 10, 5);

/**
 * Filter the wp get attachment image src
 *
 * @param array $image Image src.
 * @param int $attachment_id Attachment ID.
 * @param string|array $size Image size.
 * @param bool $icon Whether to use icon.
 * @return array Modified image src.
 */
function aqualuxe_wp_get_attachment_image_src($image, $attachment_id, $size, $icon) {
    // Modify image src based on conditions
    return $image;
}
add_filter('wp_get_attachment_image_src', 'aqualuxe_wp_get_attachment_image_src', 10, 4);

/**
 * Filter the wp get attachment image attributes
 *
 * @param array $attr Image attributes.
 * @param WP_Post $attachment Image attachment.
 * @param string|array $size Image size.
 * @return array Modified image attributes.
 */
function aqualuxe_wp_get_attachment_image_attributes($attr, $attachment, $size) {
    // Add loading="lazy" attribute to images
    if (!isset($attr['loading'])) {
        $attr['loading'] = 'lazy';
    }

    return $attr;
}
add_filter('wp_get_attachment_image_attributes', 'aqualuxe_wp_get_attachment_image_attributes', 10, 3);

/**
 * WooCommerce filters
 */

if (aqualuxe_is_woocommerce_active()) {
    /**
     * Filter the woocommerce add to cart fragments
     *
     * @param array $fragments Cart fragments.
     * @return array Modified cart fragments.
     */
    function aqualuxe_woocommerce_add_to_cart_fragments($fragments) {
        // Add cart count to fragments
        $cart_count = WC()->cart ? WC()->cart->get_cart_contents_count() : 0;
        
        $fragments['.header-cart-count'] = '<span class="header-cart-count">' . esc_html($cart_count) . '</span>';
        
        return $fragments;
    }
    add_filter('woocommerce_add_to_cart_fragments', 'aqualuxe_woocommerce_add_to_cart_fragments');

    /**
     * Filter the woocommerce breadcrumb args
     *
     * @param array $args Breadcrumb args.
     * @return array Modified breadcrumb args.
     */
    function aqualuxe_woocommerce_breadcrumb_args($args) {
        // Modify breadcrumb args
        $args['delimiter'] = '<span class="breadcrumb-separator">/</span>';
        $args['wrap_before'] = '<nav class="woocommerce-breadcrumb" aria-label="' . esc_attr__('Breadcrumbs', 'aqualuxe') . '">';
        $args['wrap_after'] = '</nav>';
        
        return $args;
    }
    add_filter('woocommerce_breadcrumb_defaults', 'aqualuxe_woocommerce_breadcrumb_args');

    /**
     * Filter the woocommerce product tabs
     *
     * @param array $tabs Product tabs.
     * @return array Modified product tabs.
     */
    function aqualuxe_woocommerce_product_tabs($tabs) {
        // Modify product tabs
        return $tabs;
    }
    add_filter('woocommerce_product_tabs', 'aqualuxe_woocommerce_product_tabs');

    /**
     * Filter the woocommerce related products args
     *
     * @param array $args Related products args.
     * @return array Modified related products args.
     */
    function aqualuxe_woocommerce_related_products_args($args) {
        // Modify related products args
        $args['posts_per_page'] = 4;
        $args['columns'] = 4;
        
        return $args;
    }
    add_filter('woocommerce_output_related_products_args', 'aqualuxe_woocommerce_related_products_args');

    /**
     * Filter the woocommerce product loop start
     *
     * @param string $html Product loop start HTML.
     * @return string Modified product loop start HTML.
     */
    function aqualuxe_woocommerce_product_loop_start($html) {
        // Modify product loop start HTML
        return $html;
    }
    add_filter('woocommerce_product_loop_start', 'aqualuxe_woocommerce_product_loop_start');

    /**
     * Filter the woocommerce product loop end
     *
     * @param string $html Product loop end HTML.
     * @return string Modified product loop end HTML.
     */
    function aqualuxe_woocommerce_product_loop_end($html) {
        // Modify product loop end HTML
        return $html;
    }
    add_filter('woocommerce_product_loop_end', 'aqualuxe_woocommerce_product_loop_end');

    /**
     * Filter the woocommerce loop product link open
     *
     * @param string $html Product link open HTML.
     * @return string Modified product link open HTML.
     */
    function aqualuxe_woocommerce_loop_product_link_open($html) {
        // Modify product link open HTML
        return $html;
    }
    add_filter('woocommerce_loop_product_link_open', 'aqualuxe_woocommerce_loop_product_link_open');

    /**
     * Filter the woocommerce loop product link close
     *
     * @param string $html Product link close HTML.
     * @return string Modified product link close HTML.
     */
    function aqualuxe_woocommerce_loop_product_link_close($html) {
        // Modify product link close HTML
        return $html;
    }
    add_filter('woocommerce_loop_product_link_close', 'aqualuxe_woocommerce_loop_product_link_close');

    /**
     * Filter the woocommerce sale flash
     *
     * @param string $html Sale flash HTML.
     * @param WP_Post $post Post object.
     * @param WC_Product $product Product object.
     * @return string Modified sale flash HTML.
     */
    function aqualuxe_woocommerce_sale_flash($html, $post, $product) {
        // Modify sale flash HTML
        return $html;
    }
    add_filter('woocommerce_sale_flash', 'aqualuxe_woocommerce_sale_flash', 10, 3);

    /**
     * Filter the woocommerce product thumbnail
     *
     * @param string $html Product thumbnail HTML.
     * @param int $post_id Post ID.
     * @return string Modified product thumbnail HTML.
     */
    function aqualuxe_woocommerce_product_get_image($html, $post_id) {
        // Modify product thumbnail HTML
        return $html;
    }
    add_filter('woocommerce_product_get_image', 'aqualuxe_woocommerce_product_get_image', 10, 2);

    /**
     * Filter the woocommerce loop add to cart link
     *
     * @param string $html Add to cart link HTML.
     * @param WC_Product $product Product object.
     * @param array $args Args.
     * @return string Modified add to cart link HTML.
     */
    function aqualuxe_woocommerce_loop_add_to_cart_link($html, $product, $args) {
        // Modify add to cart link HTML
        return $html;
    }
    add_filter('woocommerce_loop_add_to_cart_link', 'aqualuxe_woocommerce_loop_add_to_cart_link', 10, 3);

    /**
     * Filter the woocommerce product price
     *
     * @param string $price Formatted price.
     * @param WC_Product $product Product object.
     * @return string Modified formatted price.
     */
    function aqualuxe_woocommerce_get_price_html($price, $product) {
        // Modify price HTML
        return $price;
    }
    add_filter('woocommerce_get_price_html', 'aqualuxe_woocommerce_get_price_html', 10, 2);

    /**
     * Filter the woocommerce cart item price
     *
     * @param string $price Formatted price.
     * @param array $cart_item Cart item.
     * @param string $cart_item_key Cart item key.
     * @return string Modified formatted price.
     */
    function aqualuxe_woocommerce_cart_item_price($price, $cart_item, $cart_item_key) {
        // Modify cart item price
        return $price;
    }
    add_filter('woocommerce_cart_item_price', 'aqualuxe_woocommerce_cart_item_price', 10, 3);

    /**
     * Filter the woocommerce cart item subtotal
     *
     * @param string $subtotal Formatted subtotal.
     * @param array $cart_item Cart item.
     * @param string $cart_item_key Cart item key.
     * @return string Modified formatted subtotal.
     */
    function aqualuxe_woocommerce_cart_item_subtotal($subtotal, $cart_item, $cart_item_key) {
        // Modify cart item subtotal
        return $subtotal;
    }
    add_filter('woocommerce_cart_item_subtotal', 'aqualuxe_woocommerce_cart_item_subtotal', 10, 3);

    /**
     * Filter the woocommerce cart totals
     *
     * @param array $totals Cart totals.
     * @return array Modified cart totals.
     */
    function aqualuxe_woocommerce_get_cart_totals($totals) {
        // Modify cart totals
        return $totals;
    }
    add_filter('woocommerce_cart_totals_before_order_total', 'aqualuxe_woocommerce_get_cart_totals');

    /**
     * Filter the woocommerce checkout fields
     *
     * @param array $fields Checkout fields.
     * @return array Modified checkout fields.
     */
    function aqualuxe_woocommerce_checkout_fields($fields) {
        // Modify checkout fields
        return $fields;
    }
    add_filter('woocommerce_checkout_fields', 'aqualuxe_woocommerce_checkout_fields');

    /**
     * Filter the woocommerce billing fields
     *
     * @param array $fields Billing fields.
     * @return array Modified billing fields.
     */
    function aqualuxe_woocommerce_billing_fields($fields) {
        // Modify billing fields
        return $fields;
    }
    add_filter('woocommerce_billing_fields', 'aqualuxe_woocommerce_billing_fields');

    /**
     * Filter the woocommerce shipping fields
     *
     * @param array $fields Shipping fields.
     * @return array Modified shipping fields.
     */
    function aqualuxe_woocommerce_shipping_fields($fields) {
        // Modify shipping fields
        return $fields;
    }
    add_filter('woocommerce_shipping_fields', 'aqualuxe_woocommerce_shipping_fields');

    /**
     * Filter the woocommerce default address fields
     *
     * @param array $fields Default address fields.
     * @return array Modified default address fields.
     */
    function aqualuxe_woocommerce_default_address_fields($fields) {
        // Modify default address fields
        return $fields;
    }
    add_filter('woocommerce_default_address_fields', 'aqualuxe_woocommerce_default_address_fields');

    /**
     * Filter the woocommerce countries
     *
     * @param array $countries Countries.
     * @return array Modified countries.
     */
    function aqualuxe_woocommerce_countries($countries) {
        // Modify countries
        return $countries;
    }
    add_filter('woocommerce_countries', 'aqualuxe_woocommerce_countries');

    /**
     * Filter the woocommerce states
     *
     * @param array $states States.
     * @return array Modified states.
     */
    function aqualuxe_woocommerce_states($states) {
        // Modify states
        return $states;
    }
    add_filter('woocommerce_states', 'aqualuxe_woocommerce_states');

    /**
     * Filter the woocommerce currency symbol
     *
     * @param string $currency_symbol Currency symbol.
     * @param string $currency Currency.
     * @return string Modified currency symbol.
     */
    function aqualuxe_woocommerce_currency_symbol($currency_symbol, $currency) {
        // Modify currency symbol
        return $currency_symbol;
    }
    add_filter('woocommerce_currency_symbol', 'aqualuxe_woocommerce_currency_symbol', 10, 2);

    /**
     * Filter the woocommerce available payment gateways
     *
     * @param array $gateways Available payment gateways.
     * @return array Modified available payment gateways.
     */
    function aqualuxe_woocommerce_available_payment_gateways($gateways) {
        // Modify available payment gateways
        return $gateways;
    }
    add_filter('woocommerce_available_payment_gateways', 'aqualuxe_woocommerce_available_payment_gateways');

    /**
     * Filter the woocommerce shipping methods
     *
     * @param array $methods Shipping methods.
     * @return array Modified shipping methods.
     */
    function aqualuxe_woocommerce_shipping_methods($methods) {
        // Modify shipping methods
        return $methods;
    }
    add_filter('woocommerce_shipping_methods', 'aqualuxe_woocommerce_shipping_methods');

    /**
     * Filter the woocommerce order button text
     *
     * @param string $button_text Order button text.
     * @return string Modified order button text.
     */
    function aqualuxe_woocommerce_order_button_text($button_text) {
        // Modify order button text
        return $button_text;
    }
    add_filter('woocommerce_order_button_text', 'aqualuxe_woocommerce_order_button_text');

    /**
     * Filter the woocommerce thankyou order received text
     *
     * @param string $text Order received text.
     * @return string Modified order received text.
     */
    function aqualuxe_woocommerce_thankyou_order_received_text($text) {
        // Modify order received text
        return $text;
    }
    add_filter('woocommerce_thankyou_order_received_text', 'aqualuxe_woocommerce_thankyou_order_received_text');

    /**
     * Filter the woocommerce my account menu items
     *
     * @param array $items My account menu items.
     * @return array Modified my account menu items.
     */
    function aqualuxe_woocommerce_account_menu_items($items) {
        // Modify my account menu items
        return $items;
    }
    add_filter('woocommerce_account_menu_items', 'aqualuxe_woocommerce_account_menu_items');

    /**
     * Filter the woocommerce endpoint title
     *
     * @param string $title Endpoint title.
     * @param string $endpoint Endpoint.
     * @return string Modified endpoint title.
     */
    function aqualuxe_woocommerce_endpoint_title($title, $endpoint) {
        // Modify endpoint title
        return $title;
    }
    add_filter('woocommerce_endpoint_title', 'aqualuxe_woocommerce_endpoint_title', 10, 2);

    /**
     * Filter the woocommerce pagination args
     *
     * @param array $args Pagination args.
     * @return array Modified pagination args.
     */
    function aqualuxe_woocommerce_pagination_args($args) {
        // Modify pagination args
        $args['prev_text'] = '&larr; ' . __('Previous', 'aqualuxe');
        $args['next_text'] = __('Next', 'aqualuxe') . ' &rarr;';
        
        return $args;
    }
    add_filter('woocommerce_pagination_args', 'aqualuxe_woocommerce_pagination_args');

    /**
     * Filter the woocommerce products per page
     *
     * @param int $products_per_page Products per page.
     * @return int Modified products per page.
     */
    function aqualuxe_woocommerce_products_per_page($products_per_page) {
        // Modify products per page
        return aqualuxe_get_option('shop_products_per_page', 12);
    }
    add_filter('loop_shop_per_page', 'aqualuxe_woocommerce_products_per_page');

    /**
     * Filter the woocommerce product columns
     *
     * @param int $columns Product columns.
     * @return int Modified product columns.
     */
    function aqualuxe_woocommerce_product_columns($columns) {
        // Modify product columns
        return aqualuxe_get_option('shop_columns', 4);
    }
    add_filter('loop_shop_columns', 'aqualuxe_woocommerce_product_columns');

    /**
     * Filter the woocommerce show page title
     *
     * @param bool $show Show page title.
     * @return bool Modified show page title.
     */
    function aqualuxe_woocommerce_show_page_title($show) {
        // Modify show page title
        return false;
    }
    add_filter('woocommerce_show_page_title', 'aqualuxe_woocommerce_show_page_title');

    /**
     * Filter the woocommerce product thumbnails columns
     *
     * @param int $columns Product thumbnails columns.
     * @return int Modified product thumbnails columns.
     */
    function aqualuxe_woocommerce_product_thumbnails_columns($columns) {
        // Modify product thumbnails columns
        return 4;
    }
    add_filter('woocommerce_product_thumbnails_columns', 'aqualuxe_woocommerce_product_thumbnails_columns');

    /**
     * Filter the woocommerce output product categories args
     *
     * @param array $args Product categories args.
     * @return array Modified product categories args.
     */
    function aqualuxe_woocommerce_output_product_categories_args($args) {
        // Modify product categories args
        return $args;
    }
    add_filter('woocommerce_output_product_categories_args', 'aqualuxe_woocommerce_output_product_categories_args');

    /**
     * Filter the woocommerce product subcategories args
     *
     * @param array $args Product subcategories args.
     * @return array Modified product subcategories args.
     */
    function aqualuxe_woocommerce_product_subcategories_args($args) {
        // Modify product subcategories args
        return $args;
    }
    add_filter('woocommerce_product_subcategories_args', 'aqualuxe_woocommerce_product_subcategories_args');

    /**
     * Filter the woocommerce product subcategories hide empty
     *
     * @param bool $hide_empty Hide empty.
     * @return bool Modified hide empty.
     */
    function aqualuxe_woocommerce_product_subcategories_hide_empty($hide_empty) {
        // Modify hide empty
        return $hide_empty;
    }
    add_filter('woocommerce_product_subcategories_hide_empty', 'aqualuxe_woocommerce_product_subcategories_hide_empty');

    /**
     * Filter the woocommerce cart actions
     *
     * @param string $actions Cart actions.
     * @return string Modified cart actions.
     */
    function aqualuxe_woocommerce_cart_actions($actions) {
        // Modify cart actions
        return $actions;
    }
    add_filter('woocommerce_cart_actions', 'aqualuxe_woocommerce_cart_actions');

    /**
     * Filter the woocommerce cross sells columns
     *
     * @param int $columns Cross sells columns.
     * @return int Modified cross sells columns.
     */
    function aqualuxe_woocommerce_cross_sells_columns($columns) {
        // Modify cross sells columns
        return 2;
    }
    add_filter('woocommerce_cross_sells_columns', 'aqualuxe_woocommerce_cross_sells_columns');

    /**
     * Filter the woocommerce cross sells total
     *
     * @param int $total Cross sells total.
     * @return int Modified cross sells total.
     */
    function aqualuxe_woocommerce_cross_sells_total($total) {
        // Modify cross sells total
        return 2;
    }
    add_filter('woocommerce_cross_sells_total', 'aqualuxe_woocommerce_cross_sells_total');

    /**
     * Filter the woocommerce upsells columns
     *
     * @param int $columns Upsells columns.
     * @return int Modified upsells columns.
     */
    function aqualuxe_woocommerce_upsells_columns($columns) {
        // Modify upsells columns
        return 4;
    }
    add_filter('woocommerce_upsells_columns', 'aqualuxe_woocommerce_upsells_columns');

    /**
     * Filter the woocommerce upsells total
     *
     * @param int $total Upsells total.
     * @return int Modified upsells total.
     */
    function aqualuxe_woocommerce_upsells_total($total) {
        // Modify upsells total
        return 4;
    }
    add_filter('woocommerce_upsells_total', 'aqualuxe_woocommerce_upsells_total');

    /**
     * Filter the woocommerce related products columns
     *
     * @param int $columns Related products columns.
     * @return int Modified related products columns.
     */
    function aqualuxe_woocommerce_related_products_columns($columns) {
        // Modify related products columns
        return 4;
    }
    add_filter('woocommerce_related_products_columns', 'aqualuxe_woocommerce_related_products_columns');

    /**
     * Filter the woocommerce related products total
     *
     * @param int $total Related products total.
     * @return int Modified related products total.
     */
    function aqualuxe_woocommerce_related_products_total($total) {
        // Modify related products total
        return 4;
    }
    add_filter('woocommerce_related_products_total', 'aqualuxe_woocommerce_related_products_total');
}