<?php
/**
 * Template Tags
 *
 * @package AquaLuxe
 * @version 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Display post meta information
 */
function aqualuxe_post_meta() {
    if ('post' !== get_post_type()) {
        return;
    }
    
    $time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
    if (get_the_time('U') !== get_the_modified_time('U')) {
        $time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
    }
    
    $time_string = sprintf($time_string,
        esc_attr(get_the_date(DATE_W3C)),
        esc_html(get_the_date()),
        esc_attr(get_the_modified_date(DATE_W3C)),
        esc_html(get_the_modified_date())
    );
    
    $posted_on = sprintf(
        esc_html_x('Posted on %s', 'post date', 'aqualuxe'),
        '<a href="' . esc_url(get_permalink()) . '" rel="bookmark">' . $time_string . '</a>'
    );
    
    $byline = sprintf(
        esc_html_x('by %s', 'post author', 'aqualuxe'),
        '<span class="author vcard"><a class="url fn n" href="' . esc_url(get_author_posts_url(get_the_author_meta('ID'))) . '">' . esc_html(get_the_author()) . '</a></span>'
    );
    
    echo '<span class="posted-on">' . $posted_on . '</span><span class="byline"> ' . $byline . '</span>';
    
    if (!is_single() && !post_password_required() && (comments_open() || get_comments_number())) {
        echo '<span class="comments-link">';
        comments_popup_link(
            sprintf(
                wp_kses(
                    __('Leave a Comment<span class="screen-reader-text"> on %s</span>', 'aqualuxe'),
                    array(
                        'span' => array(
                            'class' => array(),
                        ),
                    )
                ),
                get_the_title()
            )
        );
        echo '</span>';
    }
}

/**
 * Display category list
 */
function aqualuxe_category_list() {
    $categories_list = get_the_category_list(esc_html__(', ', 'aqualuxe'));
    if ($categories_list) {
        printf('<span class="cat-links">' . esc_html__('Posted in %1$s', 'aqualuxe') . '</span>', $categories_list);
    }
}

/**
 * Display tag list
 */
function aqualuxe_tag_list() {
    $tags_list = get_the_tag_list('', esc_html_x(', ', 'list item separator', 'aqualuxe'));
    if ($tags_list) {
        printf('<span class="tags-links">' . esc_html__('Tagged %1$s', 'aqualuxe') . '</span>', $tags_list);
    }
}

/**
 * Display edit post link
 */
function aqualuxe_edit_link() {
    edit_post_link(
        sprintf(
            wp_kses(
                __('Edit <span class="screen-reader-text">%s</span>', 'aqualuxe'),
                array(
                    'span' => array(
                        'class' => array(),
                    ),
                )
            ),
            get_the_title()
        ),
        '<span class="edit-link">',
        '</span>'
    );
}

/**
 * Display post navigation
 */
function aqualuxe_post_navigation() {
    the_post_navigation(array(
        'prev_text' => '<span class="nav-subtitle">' . esc_html__('Previous:', 'aqualuxe') . '</span> <span class="nav-title">%title</span>',
        'next_text' => '<span class="nav-subtitle">' . esc_html__('Next:', 'aqualuxe') . '</span> <span class="nav-title">%title</span>',
    ));
}

/**
 * Display posts navigation
 */
function aqualuxe_posts_navigation() {
    the_posts_navigation(array(
        'prev_text' => esc_html__('Older posts', 'aqualuxe'),
        'next_text' => esc_html__('Newer posts', 'aqualuxe'),
    ));
}

/**
 * Display posts pagination
 */
function aqualuxe_posts_pagination() {
    the_posts_pagination(array(
        'mid_size'  => 2,
        'prev_text' => esc_html__('Previous', 'aqualuxe'),
        'next_text' => esc_html__('Next', 'aqualuxe'),
    ));
}

/**
 * Display author bio
 */
function aqualuxe_author_bio() {
    if (!is_single() || !get_the_author_meta('description')) {
        return;
    }
    ?>
    <div class="author-info">
        <div class="author-avatar">
            <?php echo get_avatar(get_the_author_meta('user_email'), 80); ?>
        </div>
        <div class="author-description">
            <h3 class="author-title">
                <?php printf(esc_html__('About %s', 'aqualuxe'), get_the_author()); ?>
            </h3>
            <div class="author-bio">
                <?php the_author_meta('description'); ?>
                <div class="author-link">
                    <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>" rel="author">
                        <?php printf(esc_html__('View all posts by %s', 'aqualuxe'), get_the_author()); ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Display breadcrumbs
 */
function aqualuxe_breadcrumbs() {
    if (is_front_page()) {
        return;
    }
    
    $separator = ' <span class="separator">/</span> ';
    $home_title = esc_html__('Home', 'aqualuxe');
    
    echo '<nav class="breadcrumbs" aria-label="' . esc_attr__('Breadcrumb Navigation', 'aqualuxe') . '">';
    echo '<a href="' . esc_url(home_url('/')) . '">' . $home_title . '</a>';
    
    if (is_category() || is_single()) {
        echo $separator;
        if (is_single()) {
            $category = get_the_category();
            if ($category) {
                echo '<a href="' . esc_url(get_category_link($category[0]->term_id)) . '">' . esc_html($category[0]->cat_name) . '</a>';
                echo $separator;
            }
            echo '<span class="current">' . get_the_title() . '</span>';
        } else {
            echo '<span class="current">' . single_cat_title('', false) . '</span>';
        }
    } elseif (is_page()) {
        $ancestors = get_post_ancestors(get_the_ID());
        if ($ancestors) {
            $ancestors = array_reverse($ancestors);
            foreach ($ancestors as $ancestor) {
                echo $separator;
                echo '<a href="' . esc_url(get_permalink($ancestor)) . '">' . get_the_title($ancestor) . '</a>';
            }
        }
        echo $separator;
        echo '<span class="current">' . get_the_title() . '</span>';
    } elseif (is_search()) {
        echo $separator;
        echo '<span class="current">' . sprintf(esc_html__('Search Results for "%s"', 'aqualuxe'), get_search_query()) . '</span>';
    } elseif (is_404()) {
        echo $separator;
        echo '<span class="current">' . esc_html__('404 Error', 'aqualuxe') . '</span>';
    } elseif (is_archive()) {
        echo $separator;
        echo '<span class="current">' . get_the_archive_title() . '</span>';
    }
    
    echo '</nav>';
}

/**
 * Display search form
 */
function aqualuxe_search_form() {
    $unique_id = esc_attr(uniqid('search-form-'));
    ?>
    <form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
        <label for="<?php echo $unique_id; ?>">
            <span class="screen-reader-text"><?php echo esc_html_x('Search for:', 'label', 'aqualuxe'); ?></span>
        </label>
        <input type="search" id="<?php echo $unique_id; ?>" class="search-field" placeholder="<?php echo esc_attr_x('Search &hellip;', 'placeholder', 'aqualuxe'); ?>" value="<?php echo get_search_query(); ?>" name="s" />
        <button type="submit" class="search-submit">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="11" cy="11" r="8"></circle>
                <path d="m21 21-4.35-4.35"></path>
            </svg>
            <span class="screen-reader-text"><?php echo esc_html_x('Search', 'submit button', 'aqualuxe'); ?></span>
        </button>
    </form>
    <?php
}

/**
 * Display site logo or title
 */
function aqualuxe_site_branding() {
    ?>
    <div class="site-branding">
        <?php
        if (has_custom_logo()) {
            the_custom_logo();
        } else {
            ?>
            <h1 class="site-title">
                <a href="<?php echo esc_url(home_url('/')); ?>" rel="home">
                    <?php bloginfo('name'); ?>
                </a>
            </h1>
            <?php
            $description = get_bloginfo('description', 'display');
            if ($description || is_customize_preview()) {
                ?>
                <p class="site-description"><?php echo $description; ?></p>
                <?php
            }
        }
        ?>
    </div>
    <?php
}

/**
 * Display footer info
 */
function aqualuxe_footer_info() {
    $footer_text = get_theme_mod('aqualuxe_footer_text', sprintf(esc_html__('© %s %s. All rights reserved.', 'aqualuxe'), date('Y'), get_bloginfo('name')));
    echo '<p>' . wp_kses_post($footer_text) . '</p>';
}

/**
 * Display theme credit
 */
function aqualuxe_theme_credit() {
    printf(
        esc_html__('Theme: %1$s by %2$s', 'aqualuxe'),
        'AquaLuxe',
        '<a href="https://github.com/kasunvimarshana">Kasun Vimarshana</a>'
    );
}

/**
 * Display reading time
 */
function aqualuxe_reading_time($post_id = null) {
    if (!$post_id) {
        global $post;
        $post_id = $post->ID;
    }
    
    $content = get_post_field('post_content', $post_id);
    $word_count = str_word_count(strip_tags($content));
    $reading_time = ceil($word_count / 200); // Average reading speed: 200 words per minute
    
    if ($reading_time == 1) {
        $timer = esc_html__('1 min read', 'aqualuxe');
    } else {
        $timer = sprintf(esc_html__('%s min read', 'aqualuxe'), $reading_time);
    }
    
    echo '<span class="reading-time">' . $timer . '</span>';
}

/**
 * Display post format icon
 */
function aqualuxe_post_format_icon() {
    $format = get_post_format();
    
    switch ($format) {
        case 'video':
            echo '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="23 7 16 12 23 17 23 7"></polygon><rect x="1" y="5" width="15" height="14" rx="2" ry="2"></rect></svg>';
            break;
        case 'audio':
            echo '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18V5l12-2v13"></path><circle cx="6" cy="18" r="3"></circle><circle cx="18" cy="16" r="3"></circle></svg>';
            break;
        case 'image':
            echo '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21,15 16,10 5,21"></polyline></svg>';
            break;
        case 'gallery':
            echo '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>';
            break;
        case 'quote':
            echo '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 21c3 0 7-1 7-8V5c0-1.25-.756-2.017-2-2H4c-1.25 0-2 .75-2 1.972V11c0 1.25.75 2 2 2 1 0 1 0 1 1v1c0 1-1 2-2 2s-1 .008-1 1.031V20c0 1 0 1 1 1z"></path><path d="M15 21c3 0 7-1 7-8V5c0-1.25-.757-2.017-2-2h-4c-1.25 0-2 .75-2 1.972V11c0 1.25.75 2 2 2h.75c0 2.25.25 4-2.75 4v3c0 1 0 1 1 1z"></path></svg>';
            break;
        case 'link':
            echo '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path></svg>';
            break;
        default:
            echo '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14,2 14,8 20,8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10,9 9,9 8,9"></polyline></svg>';
            break;
    }
}