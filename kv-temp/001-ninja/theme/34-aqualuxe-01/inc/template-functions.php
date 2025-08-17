<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package AquaLuxe
 * @since 1.0.0
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
    if (!is_active_sidebar('sidebar-1')) {
        $classes[] = 'no-sidebar';
    }

    // Add a class if WooCommerce is active
    if (class_exists('WooCommerce')) {
        $classes[] = 'woocommerce-active';
    }

    // Add a class for the dark mode state
    if (isset($_COOKIE['aqualuxe_dark_mode']) && $_COOKIE['aqualuxe_dark_mode'] === 'true') {
        $classes[] = 'dark-mode';
    }

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
 * Custom comment callback
 */
function aqualuxe_comment_callback($comment, $args, $depth) {
    $tag = ($args['style'] === 'div') ? 'div' : 'li';
?>
    <<?php echo $tag; ?> id="comment-<?php comment_ID(); ?>" <?php comment_class(empty($args['has_children']) ? 'parent' : '', $comment); ?>>
        <article id="div-comment-<?php comment_ID(); ?>" class="comment-body p-4 bg-gray-50 rounded">
            <footer class="comment-meta mb-2">
                <div class="comment-author vcard flex items-center">
                    <?php
                    if ($args['avatar_size'] != 0) {
                        echo '<div class="mr-4">';
                        echo get_avatar($comment, $args['avatar_size'], '', '', array('class' => 'rounded-full'));
                        echo '</div>';
                    }
                    ?>
                    <div>
                        <?php printf('<b class="fn">%s</b>', get_comment_author_link($comment)); ?>
                        <div class="comment-metadata text-sm text-gray-600">
                            <time datetime="<?php comment_time('c'); ?>">
                                <?php
                                /* translators: 1: comment date, 2: comment time */
                                printf(esc_html__('%1$s at %2$s', 'aqualuxe'), get_comment_date('', $comment), get_comment_time());
                                ?>
                            </time>
                            <?php edit_comment_link(esc_html__('Edit', 'aqualuxe'), ' <span class="edit-link">', '</span>'); ?>
                        </div>
                    </div>
                </div><!-- .comment-author -->

                <?php if ('0' === $comment->comment_approved) : ?>
                    <p class="comment-awaiting-moderation text-sm text-yellow-600 mt-2"><?php esc_html_e('Your comment is awaiting moderation.', 'aqualuxe'); ?></p>
                <?php endif; ?>
            </footer><!-- .comment-meta -->

            <div class="comment-content prose prose-sm max-w-none">
                <?php comment_text(); ?>
            </div><!-- .comment-content -->

            <?php
            comment_reply_link(
                array_merge(
                    $args,
                    array(
                        'add_below' => 'div-comment',
                        'depth'     => $depth,
                        'max_depth' => $args['max_depth'],
                        'before'    => '<div class="reply mt-2">',
                        'after'     => '</div>',
                    )
                )
            );
            ?>
        </article><!-- .comment-body -->
<?php
}

/**
 * Change the excerpt length
 */
function aqualuxe_excerpt_length($length) {
    return 30;
}
add_filter('excerpt_length', 'aqualuxe_excerpt_length');

/**
 * Change the excerpt more string
 */
function aqualuxe_excerpt_more($more) {
    return '&hellip;';
}
add_filter('excerpt_more', 'aqualuxe_excerpt_more');

/**
 * Add custom image sizes
 */
function aqualuxe_add_image_sizes() {
    add_image_size('aqualuxe-featured', 1200, 600, true);
    add_image_size('aqualuxe-product', 600, 600, true);
    add_image_size('aqualuxe-thumbnail', 300, 300, true);
}
add_action('after_setup_theme', 'aqualuxe_add_image_sizes');

/**
 * Add custom image sizes to media library
 */
function aqualuxe_custom_image_sizes($sizes) {
    return array_merge($sizes, array(
        'aqualuxe-featured' => __('Featured Image', 'aqualuxe'),
        'aqualuxe-product' => __('Product Image', 'aqualuxe'),
        'aqualuxe-thumbnail' => __('Custom Thumbnail', 'aqualuxe'),
    ));
}
add_filter('image_size_names_choose', 'aqualuxe_custom_image_sizes');

/**
 * Add schema markup to the body
 */
function aqualuxe_body_schema() {
    // Get the schema type
    $schema = 'WebPage';

    // Check if it's a single post or page
    if (is_singular('post')) {
        $schema = 'Article';
    } elseif (is_author()) {
        $schema = 'ProfilePage';
    } elseif (is_search()) {
        $schema = 'SearchResultsPage';
    }

    // Apply filters so that plugins/child themes can override
    $schema = apply_filters('aqualuxe_body_schema', $schema);

    // Output the schema
    echo 'itemscope itemtype="https://schema.org/' . esc_attr($schema) . '"';
}

/**
 * Custom comment form fields
 */
function aqualuxe_comment_form_fields($fields) {
    $commenter = wp_get_current_commenter();
    $req = get_option('require_name_email');
    $aria_req = ($req ? " aria-required='true'" : '');

    $fields['author'] = '<div class="comment-form-author mb-4"><label for="author" class="block mb-2">' . __('Name', 'aqualuxe') . ($req ? ' <span class="required">*</span>' : '') . '</label><input id="author" name="author" type="text" class="w-full p-2 border border-gray-300 rounded focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50" value="' . esc_attr($commenter['comment_author']) . '" size="30"' . $aria_req . ' /></div>';

    $fields['email'] = '<div class="comment-form-email mb-4"><label for="email" class="block mb-2">' . __('Email', 'aqualuxe') . ($req ? ' <span class="required">*</span>' : '') . '</label><input id="email" name="email" type="email" class="w-full p-2 border border-gray-300 rounded focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50" value="' . esc_attr($commenter['comment_author_email']) . '" size="30"' . $aria_req . ' /></div>';

    $fields['url'] = '<div class="comment-form-url mb-4"><label for="url" class="block mb-2">' . __('Website', 'aqualuxe') . '</label><input id="url" name="url" type="url" class="w-full p-2 border border-gray-300 rounded focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50" value="' . esc_attr($commenter['comment_author_url']) . '" size="30" /></div>';

    return $fields;
}
add_filter('comment_form_default_fields', 'aqualuxe_comment_form_fields');

/**
 * Add custom classes to the navigation menus
 */
function aqualuxe_nav_menu_css_class($classes, $item, $args, $depth) {
    if ($args->theme_location === 'primary') {
        $classes[] = 'mx-2';
    }

    if ($args->theme_location === 'footer') {
        $classes[] = 'mr-4';
    }

    return $classes;
}
add_filter('nav_menu_css_class', 'aqualuxe_nav_menu_css_class', 10, 4);

/**
 * Add custom classes to the navigation menu links
 */
function aqualuxe_nav_menu_link_attributes($atts, $item, $args, $depth) {
    if ($args->theme_location === 'primary') {
        $atts['class'] = 'px-3 py-2 hover:text-primary transition-colors';
    }

    if ($args->theme_location === 'footer') {
        $atts['class'] = 'text-sm hover:text-primary transition-colors';
    }

    return $atts;
}
add_filter('nav_menu_link_attributes', 'aqualuxe_nav_menu_link_attributes', 10, 4);

/**
 * Add custom classes to the navigation menu items
 */
function aqualuxe_nav_menu_item_id($id, $item, $args, $depth) {
    return ''; // Remove the default ID to avoid potential conflicts
}
add_filter('nav_menu_item_id', 'aqualuxe_nav_menu_item_id', 10, 4);

/**
 * Add responsive container to video embeds
 */
function aqualuxe_embed_html($html) {
    return '<div class="video-container relative overflow-hidden pb-[56.25%] h-0">' . $html . '</div>';
}
add_filter('embed_oembed_html', 'aqualuxe_embed_html', 10, 3);
add_filter('video_embed_html', 'aqualuxe_embed_html'); // Jetpack

/**
 * Wrap tables in a responsive container
 */
function aqualuxe_wrap_table_in_responsive_container($content) {
    return preg_replace('/<table/', '<div class="table-responsive overflow-x-auto"><table', $content, -1, $count)
        . str_repeat('</div>', $count);
}
add_filter('the_content', 'aqualuxe_wrap_table_in_responsive_container');