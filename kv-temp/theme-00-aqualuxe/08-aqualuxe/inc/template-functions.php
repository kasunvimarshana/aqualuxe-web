<?php
/**
 * Template Functions
 *
 * @package AquaLuxe
 * @version 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add custom classes to body
 */
function aqualuxe_body_class_additions($classes) {
    // Add page slug class
    if (is_page()) {
        global $post;
        $classes[] = 'page-' . $post->post_name;
    }
    
    // Add template class
    if (is_page_template()) {
        $template = get_page_template_slug();
        $template = str_replace('.php', '', $template);
        $template = str_replace('/', '-', $template);
        $classes[] = 'template-' . $template;
    }
    
    // Add WooCommerce specific classes
    if (class_exists('WooCommerce')) {
        if (is_shop()) {
            $classes[] = 'woocommerce-shop';
        }
        
        if (is_product_category()) {
            $classes[] = 'woocommerce-category';
        }
        
        if (is_product_tag()) {
            $classes[] = 'woocommerce-tag';
        }
    }
    
    return $classes;
}
add_filter('body_class', 'aqualuxe_body_class_additions');

/**
 * Custom post classes
 */
function aqualuxe_post_class_additions($classes, $class, $post_id) {
    if (has_post_thumbnail($post_id)) {
        $classes[] = 'has-featured-image';
    } else {
        $classes[] = 'no-featured-image';
    }
    
    return $classes;
}
add_filter('post_class', 'aqualuxe_post_class_additions', 10, 3);

/**
 * Custom excerpt length for different contexts
 */
function aqualuxe_custom_excerpt_length($length) {
    if (is_admin()) {
        return $length;
    }
    
    if (is_search()) {
        return 30;
    }
    
    if (is_archive()) {
        return 25;
    }
    
    return 20;
}
add_filter('excerpt_length', 'aqualuxe_custom_excerpt_length', 999);

/**
 * Custom read more link
 */
function aqualuxe_excerpt_more_link($more) {
    if (is_admin()) {
        return $more;
    }
    
    global $post;
    return '... <a href="' . esc_url(get_permalink($post->ID)) . '" class="read-more-link">' . esc_html__('Read More', 'aqualuxe') . '</a>';
}
add_filter('excerpt_more', 'aqualuxe_excerpt_more_link');

/**
 * Add custom image sizes to media library
 */
function aqualuxe_custom_image_sizes($sizes) {
    return array_merge($sizes, array(
        'aqualuxe-hero' => esc_html__('Hero Image', 'aqualuxe'),
        'aqualuxe-product-large' => esc_html__('Product Large', 'aqualuxe'),
        'aqualuxe-product-medium' => esc_html__('Product Medium', 'aqualuxe'),
        'aqualuxe-blog-large' => esc_html__('Blog Large', 'aqualuxe'),
    ));
}
add_filter('image_size_names_choose', 'aqualuxe_custom_image_sizes');

/**
 * Modify archive titles
 */
function aqualuxe_archive_title($title) {
    if (is_category()) {
        $title = single_cat_title('', false);
    } elseif (is_tag()) {
        $title = single_tag_title('', false);
    } elseif (is_author()) {
        $title = '<span class="vcard">' . get_the_author() . '</span>';
    } elseif (is_year()) {
        $title = get_the_date(_x('Y', 'yearly archives date format', 'aqualuxe'));
    } elseif (is_month()) {
        $title = get_the_date(_x('F Y', 'monthly archives date format', 'aqualuxe'));
    } elseif (is_day()) {
        $title = get_the_date(_x('F j, Y', 'daily archives date format', 'aqualuxe'));
    } elseif (is_tax('product_cat')) {
        $title = single_term_title('', false);
    } elseif (is_tax('product_tag')) {
        $title = single_term_title('', false);
    } elseif (is_post_type_archive()) {
        $title = post_type_archive_title('', false);
    } elseif (is_tax()) {
        $title = single_term_title('', false);
    }
    
    return $title;
}
add_filter('get_the_archive_title', 'aqualuxe_archive_title');

/**
 * Add custom fields to comment form
 */
function aqualuxe_comment_form_fields($fields) {
    $commenter = wp_get_current_commenter();
    $req = get_option('require_name_email');
    $aria_req = ($req ? " aria-required='true'" : '');
    
    $fields['author'] = '<p class="comment-form-author">' .
                       '<label for="author">' . esc_html__('Name', 'aqualuxe') . ($req ? ' <span class="required">*</span>' : '') . '</label>' .
                       '<input id="author" name="author" type="text" value="' . esc_attr($commenter['comment_author']) . '" size="30"' . $aria_req . ' /></p>';
    
    $fields['email'] = '<p class="comment-form-email">' .
                      '<label for="email">' . esc_html__('Email', 'aqualuxe') . ($req ? ' <span class="required">*</span>' : '') . '</label>' .
                      '<input id="email" name="email" type="email" value="' . esc_attr($commenter['comment_author_email']) . '" size="30"' . $aria_req . ' /></p>';
    
    $fields['url'] = '<p class="comment-form-url">' .
                    '<label for="url">' . esc_html__('Website', 'aqualuxe') . '</label>' .
                    '<input id="url" name="url" type="url" value="' . esc_attr($commenter['comment_author_url']) . '" size="30" /></p>';
    
    return $fields;
}
add_filter('comment_form_default_fields', 'aqualuxe_comment_form_fields');

/**
 * Modify comment form defaults
 */
function aqualuxe_comment_form_defaults($defaults) {
    $defaults['comment_field'] = '<p class="comment-form-comment">' .
                                '<label for="comment">' . esc_html__('Comment', 'aqualuxe') . ' <span class="required">*</span></label>' .
                                '<textarea id="comment" name="comment" cols="45" rows="8" aria-required="true"></textarea></p>';
    
    $defaults['submit_button'] = '<input name="%1$s" type="submit" id="%2$s" class="%3$s" value="%4$s" />';
    $defaults['submit_field'] = '<p class="form-submit">%1$s %2$s</p>';
    
    return $defaults;
}
add_filter('comment_form_defaults', 'aqualuxe_comment_form_defaults');

/**
 * Add custom walker for comments
 */
function aqualuxe_comment_callback($comment, $args, $depth) {
    if ('div' === $args['style']) {
        $tag       = 'div';
        $add_below = 'comment';
    } else {
        $tag       = 'li';
        $add_below = 'div-comment';
    }
    ?>
    <<?php echo $tag; ?> <?php comment_class(empty($args['has_children']) ? '' : 'parent'); ?> id="comment-<?php comment_ID(); ?>">
    <?php if ('div' != $args['style']) : ?>
        <div id="div-comment-<?php comment_ID(); ?>" class="comment-body">
    <?php endif; ?>
    
    <div class="comment-author vcard">
        <?php if ($args['avatar_size'] != 0) echo get_avatar($comment, $args['avatar_size']); ?>
        <?php printf(__('<cite class="fn">%s</cite> <span class="says">says:</span>', 'aqualuxe'), get_comment_author_link()); ?>
    </div>
    
    <?php if ($comment->comment_approved == '0') : ?>
        <em class="comment-awaiting-moderation"><?php esc_html_e('Your comment is awaiting moderation.', 'aqualuxe'); ?></em>
        <br />
    <?php endif; ?>
    
    <div class="comment-meta commentmetadata">
        <a href="<?php echo htmlspecialchars(get_comment_link($comment->comment_ID)); ?>">
            <?php printf(__('%1$s at %2$s', 'aqualuxe'), get_comment_date(), get_comment_time()); ?>
        </a>
        <?php edit_comment_link(__('(Edit)', 'aqualuxe'), '  ', ''); ?>
    </div>
    
    <div class="comment-content">
        <?php comment_text(); ?>
    </div>
    
    <div class="reply">
        <?php comment_reply_link(array_merge($args, array('add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth']))); ?>
    </div>
    
    <?php if ('div' != $args['style']) : ?>
        </div>
    <?php endif; ?>
    <?php
}

/**
 * Pagination function
 */
function aqualuxe_pagination($args = array()) {
    $defaults = array(
        'range'           => 4,
        'custom_query'    => false,
        'previous_string' => esc_html__('Prev', 'aqualuxe'),
        'next_string'     => esc_html__('Next', 'aqualuxe'),
        'before_output'   => '<div class="post-nav">',
        'after_output'    => '</div>'
    );
    
    $args = wp_parse_args($args, $defaults);
    
    $args['range'] = (int) $args['range'] - 1;
    if (!$args['custom_query']) {
        $args['custom_query'] = @$GLOBALS['wp_query'];
    }
    
    $count = (int) $args['custom_query']->max_num_pages;
    $page  = intval(get_query_var('paged'));
    $ceil  = ceil($args['range'] / 2);
    
    if ($count <= 1) {
        return false;
    }
    
    if (!$page) {
        $page = 1;
    }
    
    if ($count > $args['range']) {
        if ($page <= $args['range']) {
            $min = 1;
            $max = $args['range'] + 1;
        } elseif ($page >= ($count - $ceil)) {
            $min = $count - $args['range'];
            $max = $count;
        } elseif ($page >= $args['range'] && $page < ($count - $ceil)) {
            $min = $page - $ceil;
            $max = $page + $ceil;
        }
    } else {
        $min = 1;
        $max = $count;
    }
    
    echo $args['before_output'];
    
    if ($page > 1) {
        echo '<a href="' . esc_url(get_pagenum_link($page - 1)) . '" class="prev-page">' . $args['previous_string'] . '</a>';
    }
    
    for ($i = $min; $i <= $max; $i++) {
        if ($page == $i) {
            echo '<span class="current">' . $i . '</span>';
        } else {
            echo '<a href="' . esc_url(get_pagenum_link($i)) . '">' . $i . '</a>';
        }
    }
    
    if ($page < $count) {
        echo '<a href="' . esc_url(get_pagenum_link($page + 1)) . '" class="next-page">' . $args['next_string'] . '</a>';
    }
    
    echo $args['after_output'];
}

/**
 * Social sharing buttons
 */
function aqualuxe_social_sharing() {
    if (!is_single()) {
        return;
    }
    
    $post_url = urlencode(get_permalink());
    $post_title = urlencode(get_the_title());
    $post_thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
    $post_thumbnail = isset($post_thumbnail[0]) ? urlencode($post_thumbnail[0]) : '';
    
    ?>
    <div class="social-sharing">
        <h4><?php esc_html_e('Share this post:', 'aqualuxe'); ?></h4>
        <div class="social-links">
            <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $post_url; ?>" target="_blank" rel="noopener" class="facebook">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                </svg>
                <?php esc_html_e('Facebook', 'aqualuxe'); ?>
            </a>
            
            <a href="https://twitter.com/intent/tweet?url=<?php echo $post_url; ?>&text=<?php echo $post_title; ?>" target="_blank" rel="noopener" class="twitter">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                </svg>
                <?php esc_html_e('Twitter', 'aqualuxe'); ?>
            </a>
            
            <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo $post_url; ?>" target="_blank" rel="noopener" class="linkedin">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                </svg>
                <?php esc_html_e('LinkedIn', 'aqualuxe'); ?>
            </a>
            
            <a href="https://pinterest.com/pin/create/button/?url=<?php echo $post_url; ?>&media=<?php echo $post_thumbnail; ?>&description=<?php echo $post_title; ?>" target="_blank" rel="noopener" class="pinterest">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.174-.105-.949-.199-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.72-.359-1.781c0-1.663.967-2.911 2.168-2.911 1.024 0 1.518.769 1.518 1.688 0 1.029-.653 2.567-.992 3.992-.285 1.193.6 2.165 1.775 2.165 2.128 0 3.768-2.245 3.768-5.487 0-2.861-2.063-4.869-5.008-4.869-3.41 0-5.409 2.562-5.409 5.199 0 1.033.394 2.143.889 2.741.099.12.112.225.085.345-.09.375-.293 1.199-.334 1.363-.053.225-.172.271-.402.165-1.495-.69-2.433-2.878-2.433-4.646 0-3.776 2.748-7.252 7.92-7.252 4.158 0 7.392 2.967 7.392 6.923 0 4.135-2.607 7.462-6.233 7.462-1.214 0-2.357-.629-2.75-1.378l-.748 2.853c-.271 1.043-1.002 2.35-1.492 3.146C9.57 23.812 10.763 24.009 12.017 24c6.624 0 11.99-5.367 11.99-12C24.007 5.367 18.641.001.012.001z"/>
                </svg>
                <?php esc_html_e('Pinterest', 'aqualuxe'); ?>
            </a>
        </div>
    </div>
    <?php
}

/**
 * Related posts function
 */
function aqualuxe_related_posts($post_id = null, $number_posts = 3) {
    if (!$post_id) {
        global $post;
        $post_id = $post->ID;
    }
    
    $categories = get_the_category($post_id);
    if (empty($categories)) {
        return;
    }
    
    $category_ids = array();
    foreach ($categories as $category) {
        $category_ids[] = $category->term_id;
    }
    
    $args = array(
        'post_type' => 'post',
        'posts_per_page' => $number_posts,
        'post__not_in' => array($post_id),
        'category__in' => $category_ids,
        'orderby' => 'rand'
    );
    
    $related_posts = new WP_Query($args);
    
    if ($related_posts->have_posts()) {
        echo '<div class="related-posts">';
        echo '<h3>' . esc_html__('Related Posts', 'aqualuxe') . '</h3>';
        echo '<div class="related-posts-grid">';
        
        while ($related_posts->have_posts()) {
            $related_posts->the_post();
            ?>
            <article class="related-post">
                <?php if (has_post_thumbnail()) : ?>
                    <div class="related-post-thumbnail">
                        <a href="<?php the_permalink(); ?>">
                            <?php the_post_thumbnail('aqualuxe-medium'); ?>
                        </a>
                    </div>
                <?php endif; ?>
                
                <div class="related-post-content">
                    <h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                    <div class="related-post-meta">
                        <time datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                            <?php echo esc_html(get_the_date()); ?>
                        </time>
                    </div>
                </div>
            </article>
            <?php
        }
        
        echo '</div>';
        echo '</div>';
        
        wp_reset_postdata();
    }
}