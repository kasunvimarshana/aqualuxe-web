<?php
/**
 * AquaLuxe SEO & Meta
 * - Adds Open Graph, schema.org, and meta tags
 */
add_action('wp_head', function() {
    echo '<meta name="description" content="' . esc_attr(get_bloginfo('description')) . '" />';
    echo '<meta property="og:site_name" content="' . esc_attr(get_bloginfo('name')) . '" />';
    echo '<meta property="og:title" content="' . esc_attr(wp_get_document_title()) . '" />';
    echo '<meta property="og:description" content="' . esc_attr(get_bloginfo('description')) . '" />';
    echo '<meta property="og:url" content="' . esc_url(home_url(add_query_arg(array(),$wp->request))) . '" />';
    // Add more Open Graph, Twitter, schema.org as needed
});
