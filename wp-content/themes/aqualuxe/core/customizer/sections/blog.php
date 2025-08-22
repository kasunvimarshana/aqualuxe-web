<?php
/**
 * AquaLuxe Theme Customizer - Blog Section
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Add blog settings to the Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_register_blog($wp_customize) {
    // Add Blog section
    $wp_customize->add_section('aqualuxe_blog', array(
        'title'    => __('Blog', 'aqualuxe'),
        'priority' => 80,
        'panel'    => 'aqualuxe_theme_options',
    ));

    // Blog Layout
    $wp_customize->add_setting('aqualuxe_blog_layout', array(
        'default'           => 'standard',
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ));
    
    $wp_customize->add_control('aqualuxe_blog_layout', array(
        'label'       => __('Blog Layout', 'aqualuxe'),
        'description' => __('Choose the layout for the blog archive pages.', 'aqualuxe'),
        'section'     => 'aqualuxe_blog',
        'type'        => 'select',
        'choices'     => array(
            'standard' => __('Standard', 'aqualuxe'),
            'grid'     => __('Grid', 'aqualuxe'),
            'masonry'  => __('Masonry', 'aqualuxe'),
            'list'     => __('List', 'aqualuxe'),
        ),
    ));
    
    // Blog Columns
    $wp_customize->add_setting('aqualuxe_blog_columns', array(
        'default'           => 2,
        'transport'         => 'refresh',
        'sanitize_callback' => 'absint',
    ));
    
    $wp_customize->add_control('aqualuxe_blog_columns', array(
        'label'       => __('Blog Columns', 'aqualuxe'),
        'description' => __('Number of columns for grid and masonry layouts.', 'aqualuxe'),
        'section'     => 'aqualuxe_blog',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 1,
            'max'  => 4,
            'step' => 1,
        ),
        'active_callback' => function() {
            $blog_layout = get_theme_mod('aqualuxe_blog_layout', 'standard');
            return ($blog_layout === 'grid' || $blog_layout === 'masonry');
        },
    ));
    
    // Blog Sidebar
    $wp_customize->add_setting('aqualuxe_blog_sidebar', array(
        'default'           => 'right',
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ));
    
    $wp_customize->add_control('aqualuxe_blog_sidebar', array(
        'label'       => __('Blog Sidebar', 'aqualuxe'),
        'description' => __('Choose the sidebar position for blog pages.', 'aqualuxe'),
        'section'     => 'aqualuxe_blog',
        'type'        => 'select',
        'choices'     => array(
            'right'  => __('Right Sidebar', 'aqualuxe'),
            'left'   => __('Left Sidebar', 'aqualuxe'),
            'none'   => __('No Sidebar', 'aqualuxe'),
        ),
    ));
    
    // Single Post Sidebar
    $wp_customize->add_setting('aqualuxe_single_sidebar', array(
        'default'           => 'right',
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ));
    
    $wp_customize->add_control('aqualuxe_single_sidebar', array(
        'label'       => __('Single Post Sidebar', 'aqualuxe'),
        'description' => __('Choose the sidebar position for single posts.', 'aqualuxe'),
        'section'     => 'aqualuxe_blog',
        'type'        => 'select',
        'choices'     => array(
            'right'  => __('Right Sidebar', 'aqualuxe'),
            'left'   => __('Left Sidebar', 'aqualuxe'),
            'none'   => __('No Sidebar', 'aqualuxe'),
        ),
    ));
    
    // Featured Image Style
    $wp_customize->add_setting('aqualuxe_featured_image_style', array(
        'default'           => 'large',
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ));
    
    $wp_customize->add_control('aqualuxe_featured_image_style', array(
        'label'       => __('Featured Image Style', 'aqualuxe'),
        'description' => __('Choose the style for featured images on single posts.', 'aqualuxe'),
        'section'     => 'aqualuxe_blog',
        'type'        => 'select',
        'choices'     => array(
            'large'     => __('Large (Full Width)', 'aqualuxe'),
            'medium'    => __('Medium (Content Width)', 'aqualuxe'),
            'small'     => __('Small (Aligned Right)', 'aqualuxe'),
            'none'      => __('None (Hide Featured Image)', 'aqualuxe'),
        ),
    ));
    
    // Show Featured Image on Single Posts
    $wp_customize->add_setting('aqualuxe_show_single_featured_image', array(
        'default'           => true,
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));
    
    $wp_customize->add_control('aqualuxe_show_single_featured_image', array(
        'label'       => __('Show Featured Image on Single Posts', 'aqualuxe'),
        'description' => __('Display the featured image on single post pages.', 'aqualuxe'),
        'section'     => 'aqualuxe_blog',
        'type'        => 'checkbox',
    ));
    
    // Show Post Meta
    $wp_customize->add_setting('aqualuxe_show_post_meta', array(
        'default'           => true,
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));
    
    $wp_customize->add_control('aqualuxe_show_post_meta', array(
        'label'       => __('Show Post Meta', 'aqualuxe'),
        'description' => __('Display post meta information (date, author, categories, etc.).', 'aqualuxe'),
        'section'     => 'aqualuxe_blog',
        'type'        => 'checkbox',
    ));
    
    // Post Meta Elements
    $wp_customize->add_setting('aqualuxe_post_meta_elements', array(
        'default'           => array('date', 'author', 'categories', 'comments'),
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_multi_select',
    ));
    
    $wp_customize->add_control(new AquaLuxe_Multi_Checkbox_Control($wp_customize, 'aqualuxe_post_meta_elements', array(
        'label'       => __('Post Meta Elements', 'aqualuxe'),
        'description' => __('Select which meta elements to display.', 'aqualuxe'),
        'section'     => 'aqualuxe_blog',
        'choices'     => array(
            'date'       => __('Date', 'aqualuxe'),
            'author'     => __('Author', 'aqualuxe'),
            'categories' => __('Categories', 'aqualuxe'),
            'tags'       => __('Tags', 'aqualuxe'),
            'comments'   => __('Comments', 'aqualuxe'),
        ),
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_show_post_meta', true);
        },
    )));
    
    // Excerpt Length
    $wp_customize->add_setting('aqualuxe_excerpt_length', array(
        'default'           => 55,
        'transport'         => 'refresh',
        'sanitize_callback' => 'absint',
    ));
    
    $wp_customize->add_control('aqualuxe_excerpt_length', array(
        'label'       => __('Excerpt Length', 'aqualuxe'),
        'description' => __('Number of words in the excerpt.', 'aqualuxe'),
        'section'     => 'aqualuxe_blog',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 10,
            'max'  => 200,
            'step' => 5,
        ),
    ));
    
    // Excerpt Type
    $wp_customize->add_setting('aqualuxe_excerpt_type', array(
        'default'           => 'excerpt',
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ));
    
    $wp_customize->add_control('aqualuxe_excerpt_type', array(
        'label'       => __('Excerpt Type', 'aqualuxe'),
        'description' => __('Choose how to display post content in archives.', 'aqualuxe'),
        'section'     => 'aqualuxe_blog',
        'type'        => 'select',
        'choices'     => array(
            'excerpt' => __('Excerpt', 'aqualuxe'),
            'full'    => __('Full Content', 'aqualuxe'),
        ),
    ));
    
    // Read More Text
    $wp_customize->add_setting('aqualuxe_read_more_text', array(
        'default'           => __('Read More', 'aqualuxe'),
        'transport'         => 'refresh',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('aqualuxe_read_more_text', array(
        'label'       => __('Read More Text', 'aqualuxe'),
        'description' => __('Text for the read more link.', 'aqualuxe'),
        'section'     => 'aqualuxe_blog',
        'type'        => 'text',
    ));
    
    // Show Author Bio
    $wp_customize->add_setting('aqualuxe_show_author_bio', array(
        'default'           => true,
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));
    
    $wp_customize->add_control('aqualuxe_show_author_bio', array(
        'label'       => __('Show Author Bio', 'aqualuxe'),
        'description' => __('Display author biography on single posts.', 'aqualuxe'),
        'section'     => 'aqualuxe_blog',
        'type'        => 'checkbox',
    ));
    
    // Show Post Navigation
    $wp_customize->add_setting('aqualuxe_show_post_navigation', array(
        'default'           => true,
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));
    
    $wp_customize->add_control('aqualuxe_show_post_navigation', array(
        'label'       => __('Show Post Navigation', 'aqualuxe'),
        'description' => __('Display next/previous post navigation on single posts.', 'aqualuxe'),
        'section'     => 'aqualuxe_blog',
        'type'        => 'checkbox',
    ));
    
    // Show Related Posts
    $wp_customize->add_setting('aqualuxe_show_related_posts', array(
        'default'           => true,
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));
    
    $wp_customize->add_control('aqualuxe_show_related_posts', array(
        'label'       => __('Show Related Posts', 'aqualuxe'),
        'description' => __('Display related posts on single posts.', 'aqualuxe'),
        'section'     => 'aqualuxe_blog',
        'type'        => 'checkbox',
    ));
    
    // Related Posts Count
    $wp_customize->add_setting('aqualuxe_related_posts_count', array(
        'default'           => 3,
        'transport'         => 'refresh',
        'sanitize_callback' => 'absint',
    ));
    
    $wp_customize->add_control('aqualuxe_related_posts_count', array(
        'label'       => __('Related Posts Count', 'aqualuxe'),
        'description' => __('Number of related posts to display.', 'aqualuxe'),
        'section'     => 'aqualuxe_blog',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 1,
            'max'  => 12,
            'step' => 1,
        ),
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_show_related_posts', true);
        },
    ));
    
    // Related Posts Criteria
    $wp_customize->add_setting('aqualuxe_related_posts_criteria', array(
        'default'           => 'category',
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ));
    
    $wp_customize->add_control('aqualuxe_related_posts_criteria', array(
        'label'       => __('Related Posts Criteria', 'aqualuxe'),
        'description' => __('Choose how to determine related posts.', 'aqualuxe'),
        'section'     => 'aqualuxe_blog',
        'type'        => 'select',
        'choices'     => array(
            'category' => __('Same Category', 'aqualuxe'),
            'tag'      => __('Same Tags', 'aqualuxe'),
            'author'   => __('Same Author', 'aqualuxe'),
        ),
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_show_related_posts', true);
        },
    ));
    
    // Pagination Style
    $wp_customize->add_setting('aqualuxe_pagination_style', array(
        'default'           => 'numbered',
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ));
    
    $wp_customize->add_control('aqualuxe_pagination_style', array(
        'label'       => __('Pagination Style', 'aqualuxe'),
        'description' => __('Choose the style for pagination on archive pages.', 'aqualuxe'),
        'section'     => 'aqualuxe_blog',
        'type'        => 'select',
        'choices'     => array(
            'numbered'   => __('Numbered', 'aqualuxe'),
            'prev-next'  => __('Previous/Next', 'aqualuxe'),
            'load-more'  => __('Load More Button', 'aqualuxe'),
            'infinite'   => __('Infinite Scroll', 'aqualuxe'),
        ),
    ));
    
    // Archive Title Style
    $wp_customize->add_setting('aqualuxe_archive_title_style', array(
        'default'           => 'default',
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ));
    
    $wp_customize->add_control('aqualuxe_archive_title_style', array(
        'label'       => __('Archive Title Style', 'aqualuxe'),
        'description' => __('Choose the style for archive page titles.', 'aqualuxe'),
        'section'     => 'aqualuxe_blog',
        'type'        => 'select',
        'choices'     => array(
            'default'   => __('Default', 'aqualuxe'),
            'centered'  => __('Centered', 'aqualuxe'),
            'modern'    => __('Modern with Background', 'aqualuxe'),
            'minimal'   => __('Minimal', 'aqualuxe'),
        ),
    ));
    
    // Show Archive Description
    $wp_customize->add_setting('aqualuxe_show_archive_description', array(
        'default'           => true,
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));
    
    $wp_customize->add_control('aqualuxe_show_archive_description', array(
        'label'       => __('Show Archive Description', 'aqualuxe'),
        'description' => __('Display the archive description on archive pages.', 'aqualuxe'),
        'section'     => 'aqualuxe_blog',
        'type'        => 'checkbox',
    ));
    
    // Show Search Results Count
    $wp_customize->add_setting('aqualuxe_show_search_results_count', array(
        'default'           => true,
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));
    
    $wp_customize->add_control('aqualuxe_show_search_results_count', array(
        'label'       => __('Show Search Results Count', 'aqualuxe'),
        'description' => __('Display the number of search results on search pages.', 'aqualuxe'),
        'section'     => 'aqualuxe_blog',
        'type'        => 'checkbox',
    ));
    
    // Show Search Thumbnail
    $wp_customize->add_setting('aqualuxe_show_search_thumbnail', array(
        'default'           => true,
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));
    
    $wp_customize->add_control('aqualuxe_show_search_thumbnail', array(
        'label'       => __('Show Search Thumbnail', 'aqualuxe'),
        'description' => __('Display featured images in search results.', 'aqualuxe'),
        'section'     => 'aqualuxe_blog',
        'type'        => 'checkbox',
    ));
    
    // Show Search Post Type
    $wp_customize->add_setting('aqualuxe_show_search_post_type', array(
        'default'           => true,
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));
    
    $wp_customize->add_control('aqualuxe_show_search_post_type', array(
        'label'       => __('Show Search Post Type', 'aqualuxe'),
        'description' => __('Display the post type in search results.', 'aqualuxe'),
        'section'     => 'aqualuxe_blog',
        'type'        => 'checkbox',
    ));
    
    // Show Search Suggestions
    $wp_customize->add_setting('aqualuxe_show_search_suggestions', array(
        'default'           => true,
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));
    
    $wp_customize->add_control('aqualuxe_show_search_suggestions', array(
        'label'       => __('Show Search Suggestions', 'aqualuxe'),
        'description' => __('Display search suggestions when no results are found.', 'aqualuxe'),
        'section'     => 'aqualuxe_blog',
        'type'        => 'checkbox',
    ));
    
    // Show Recent Posts
    $wp_customize->add_setting('aqualuxe_show_recent_posts', array(
        'default'           => true,
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));
    
    $wp_customize->add_control('aqualuxe_show_recent_posts', array(
        'label'       => __('Show Recent Posts', 'aqualuxe'),
        'description' => __('Display recent posts when no content is found.', 'aqualuxe'),
        'section'     => 'aqualuxe_blog',
        'type'        => 'checkbox',
    ));
}

// Add the blog section to the customizer
add_action('customize_register', 'aqualuxe_customize_register_blog');

/**
 * Sanitize multi-select values for blog settings.
 *
 * @param array $input Multi-select values.
 * @return array Sanitized multi-select values.
 */
function aqualuxe_sanitize_blog_multi_select($input) {
    if (!is_array($input)) {
        return array();
    }
    
    $valid_keys = array('date', 'author', 'categories', 'tags', 'comments');
    
    return array_intersect($input, $valid_keys);
}

/**
 * Get excerpt type.
 *
 * @return string Excerpt type.
 */
function aqualuxe_get_excerpt_type() {
    return get_theme_mod('aqualuxe_excerpt_type', 'excerpt');
}

/**
 * Get archive layout.
 *
 * @return string Archive layout.
 */
function aqualuxe_get_archive_layout() {
    return get_theme_mod('aqualuxe_blog_layout', 'standard');
}

/**
 * Check if post thumbnail should be shown on single posts.
 *
 * @return bool Whether to show post thumbnail.
 */
function aqualuxe_show_post_thumbnail() {
    return get_theme_mod('aqualuxe_show_single_featured_image', true);
}

/**
 * Check if post meta should be shown.
 *
 * @return bool Whether to show post meta.
 */
function aqualuxe_show_post_meta() {
    return get_theme_mod('aqualuxe_show_post_meta', true);
}

/**
 * Check if author bio should be shown.
 *
 * @return bool Whether to show author bio.
 */
function aqualuxe_show_author_bio() {
    return get_theme_mod('aqualuxe_show_author_bio', true);
}

/**
 * Check if post navigation should be shown.
 *
 * @return bool Whether to show post navigation.
 */
function aqualuxe_show_post_navigation() {
    return get_theme_mod('aqualuxe_show_post_navigation', true);
}

/**
 * Check if related posts should be shown.
 *
 * @return bool Whether to show related posts.
 */
function aqualuxe_show_related_posts() {
    return get_theme_mod('aqualuxe_show_related_posts', true);
}

/**
 * Check if search thumbnail should be shown.
 *
 * @return bool Whether to show search thumbnail.
 */
function aqualuxe_show_search_thumbnail() {
    return get_theme_mod('aqualuxe_show_search_thumbnail', true);
}

/**
 * Check if search post type should be shown.
 *
 * @return bool Whether to show search post type.
 */
function aqualuxe_show_search_post_type() {
    return get_theme_mod('aqualuxe_show_search_post_type', true);
}

/**
 * Check if search suggestions should be shown.
 *
 * @return bool Whether to show search suggestions.
 */
function aqualuxe_show_search_suggestions() {
    return get_theme_mod('aqualuxe_show_search_suggestions', true);
}

/**
 * Check if recent posts should be shown.
 *
 * @return bool Whether to show recent posts.
 */
function aqualuxe_show_recent_posts() {
    return get_theme_mod('aqualuxe_show_recent_posts', true);
}

/**
 * Display post meta.
 */
function aqualuxe_post_meta() {
    if (!aqualuxe_show_post_meta()) {
        return;
    }
    
    $meta_elements = get_theme_mod('aqualuxe_post_meta_elements', array('date', 'author', 'categories', 'comments'));
    
    if (!is_array($meta_elements)) {
        $meta_elements = explode(',', $meta_elements);
    }
    
    echo '<div class="entry-meta">';
    
    if (in_array('date', $meta_elements)) {
        aqualuxe_posted_on();
    }
    
    if (in_array('author', $meta_elements)) {
        aqualuxe_posted_by();
    }
    
    if (in_array('categories', $meta_elements)) {
        aqualuxe_post_categories();
    }
    
    if (in_array('comments', $meta_elements)) {
        aqualuxe_comments_link();
    }
    
    echo '</div>';
    
    if (in_array('tags', $meta_elements) && has_tag()) {
        echo '<div class="entry-tags">';
        aqualuxe_post_tags();
        echo '</div>';
    }
}

/**
 * Display posted on date.
 */
function aqualuxe_posted_on() {
    $time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
    
    if (get_the_time('U') !== get_the_modified_time('U')) {
        $time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
    }
    
    $time_string = sprintf(
        $time_string,
        esc_attr(get_the_date(DATE_W3C)),
        esc_html(get_the_date()),
        esc_attr(get_the_modified_date(DATE_W3C)),
        esc_html(get_the_modified_date())
    );
    
    echo '<span class="posted-on"><i class="far fa-calendar-alt"></i> <a href="' . esc_url(get_permalink()) . '" rel="bookmark">' . $time_string . '</a></span>';
}

/**
 * Display posted by author.
 */
function aqualuxe_posted_by() {
    echo '<span class="byline"><i class="far fa-user"></i> <span class="author vcard"><a class="url fn n" href="' . esc_url(get_author_posts_url(get_the_author_meta('ID'))) . '">' . esc_html(get_the_author()) . '</a></span></span>';
}

/**
 * Display post categories.
 */
function aqualuxe_post_categories() {
    if ('post' === get_post_type()) {
        $categories_list = get_the_category_list(', ');
        
        if ($categories_list) {
            echo '<span class="cat-links"><i class="far fa-folder-open"></i> ' . $categories_list . '</span>';
        }
    }
}

/**
 * Display post tags.
 */
function aqualuxe_post_tags() {
    if ('post' === get_post_type()) {
        $tags_list = get_the_tag_list('', ', ');
        
        if ($tags_list) {
            echo '<span class="tags-links"><i class="fas fa-tags"></i> ' . $tags_list . '</span>';
        }
    }
}

/**
 * Display comments link.
 */
function aqualuxe_comments_link() {
    if (!post_password_required() && (comments_open() || get_comments_number())) {
        echo '<span class="comments-link"><i class="far fa-comment"></i> ';
        comments_popup_link(
            sprintf(
                wp_kses(
                    /* translators: %s: post title */
                    __('Leave a Comment<span class="screen-reader-text"> on %s</span>', 'aqualuxe'),
                    array(
                        'span' => array(
                            'class' => array(),
                        ),
                    )
                ),
                wp_kses_post(get_the_title())
            )
        );
        echo '</span>';
    }
}

/**
 * Display entry footer.
 */
function aqualuxe_entry_footer() {
    // Hide category and tag text for pages.
    if ('post' === get_post_type() && aqualuxe_show_post_meta()) {
        $meta_elements = get_theme_mod('aqualuxe_post_meta_elements', array('date', 'author', 'categories', 'comments'));
        
        if (!is_array($meta_elements)) {
            $meta_elements = explode(',', $meta_elements);
        }
        
        if (in_array('tags', $meta_elements)) {
            aqualuxe_post_tags();
        }
    }
    
    edit_post_link(
        sprintf(
            wp_kses(
                /* translators: %s: Name of current post. Only visible to screen readers */
                __('Edit <span class="screen-reader-text">%s</span>', 'aqualuxe'),
                array(
                    'span' => array(
                        'class' => array(),
                    ),
                )
            ),
            wp_kses_post(get_the_title())
        ),
        '<span class="edit-link">',
        '</span>'
    );
}

/**
 * Display author bio.
 */
function aqualuxe_author_bio() {
    if (!is_single() || !aqualuxe_show_author_bio()) {
        return;
    }
    
    $author_id = get_the_author_meta('ID');
    $author_name = get_the_author_meta('display_name', $author_id);
    $author_description = get_the_author_meta('description', $author_id);
    $author_url = get_author_posts_url($author_id);
    
    if (empty($author_description)) {
        return;
    }
    
    ?>
    <div class="author-bio">
        <div class="author-avatar">
            <?php echo get_avatar($author_id, 100); ?>
        </div>
        <div class="author-content">
            <h3 class="author-title"><?php echo esc_html($author_name); ?></h3>
            <div class="author-description">
                <?php echo wpautop(wp_kses_post($author_description)); ?>
            </div>
            <a class="author-link" href="<?php echo esc_url($author_url); ?>">
                <?php
                printf(
                    /* translators: %s: author name */
                    esc_html__('View all posts by %s', 'aqualuxe'),
                    esc_html($author_name)
                );
                ?>
            </a>
        </div>
    </div>
    <?php
}

/**
 * Display post navigation.
 */
function aqualuxe_post_navigation() {
    if (!is_single() || !aqualuxe_show_post_navigation()) {
        return;
    }
    
    $prev_post = get_previous_post();
    $next_post = get_next_post();
    
    if (!$prev_post && !$next_post) {
        return;
    }
    
    ?>
    <nav class="navigation post-navigation" role="navigation">
        <h2 class="screen-reader-text"><?php esc_html_e('Post navigation', 'aqualuxe'); ?></h2>
        <div class="nav-links">
            <?php if ($prev_post) : ?>
                <div class="nav-previous">
                    <a href="<?php echo esc_url(get_permalink($prev_post->ID)); ?>" rel="prev">
                        <?php if (has_post_thumbnail($prev_post->ID) && aqualuxe_show_post_thumbnail()) : ?>
                            <div class="nav-thumbnail">
                                <?php echo get_the_post_thumbnail($prev_post->ID, 'thumbnail'); ?>
                            </div>
                        <?php endif; ?>
                        <div class="nav-content">
                            <span class="nav-label"><?php esc_html_e('Previous Post', 'aqualuxe'); ?></span>
                            <span class="nav-title"><?php echo esc_html(get_the_title($prev_post->ID)); ?></span>
                        </div>
                    </a>
                </div>
            <?php endif; ?>
            
            <?php if ($next_post) : ?>
                <div class="nav-next">
                    <a href="<?php echo esc_url(get_permalink($next_post->ID)); ?>" rel="next">
                        <div class="nav-content">
                            <span class="nav-label"><?php esc_html_e('Next Post', 'aqualuxe'); ?></span>
                            <span class="nav-title"><?php echo esc_html(get_the_title($next_post->ID)); ?></span>
                        </div>
                        <?php if (has_post_thumbnail($next_post->ID) && aqualuxe_show_post_thumbnail()) : ?>
                            <div class="nav-thumbnail">
                                <?php echo get_the_post_thumbnail($next_post->ID, 'thumbnail'); ?>
                            </div>
                        <?php endif; ?>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </nav>
    <?php
}

/**
 * Display related posts.
 */
function aqualuxe_related_posts() {
    if (!is_single() || !aqualuxe_show_related_posts()) {
        return;
    }
    
    $post_id = get_the_ID();
    $related_count = get_theme_mod('aqualuxe_related_posts_count', 3);
    $criteria = get_theme_mod('aqualuxe_related_posts_criteria', 'category');
    $args = array(
        'post_type'      => 'post',
        'posts_per_page' => $related_count,
        'post__not_in'   => array($post_id),
    );
    
    if ($criteria === 'category') {
        $categories = get_the_category($post_id);
        
        if ($categories) {
            $category_ids = array();
            
            foreach ($categories as $category) {
                $category_ids[] = $category->term_id;
            }
            
            $args['category__in'] = $category_ids;
        }
    } elseif ($criteria === 'tag') {
        $tags = get_the_tags($post_id);
        
        if ($tags) {
            $tag_ids = array();
            
            foreach ($tags as $tag) {
                $tag_ids[] = $tag->term_id;
            }
            
            $args['tag__in'] = $tag_ids;
        }
    } elseif ($criteria === 'author') {
        $args['author'] = get_post_field('post_author', $post_id);
    }
    
    $related_query = new WP_Query($args);
    
    if ($related_query->have_posts()) :
        ?>
        <div class="related-posts">
            <h3 class="related-posts-title"><?php esc_html_e('Related Posts', 'aqualuxe'); ?></h3>
            <div class="related-posts-grid columns-<?php echo esc_attr($related_count); ?>">
                <?php while ($related_query->have_posts()) : $related_query->the_post(); ?>
                    <article class="related-post">
                        <?php if (has_post_thumbnail() && aqualuxe_show_post_thumbnail()) : ?>
                            <div class="related-post-thumbnail">
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_post_thumbnail('medium'); ?>
                                </a>
                            </div>
                        <?php endif; ?>
                        <div class="related-post-content">
                            <h4 class="related-post-title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h4>
                            <?php if (aqualuxe_show_post_meta()) : ?>
                                <div class="related-post-meta">
                                    <?php aqualuxe_posted_on(); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>
        </div>
        <?php
    endif;
    
    wp_reset_postdata();
}

/**
 * Display pagination.
 */
function aqualuxe_pagination() {
    $pagination_style = get_theme_mod('aqualuxe_pagination_style', 'numbered');
    
    if ($pagination_style === 'numbered') {
        the_posts_pagination(array(
            'mid_size'  => 2,
            'prev_text' => '<i class="fas fa-chevron-left"></i><span class="screen-reader-text">' . __('Previous', 'aqualuxe') . '</span>',
            'next_text' => '<span class="screen-reader-text">' . __('Next', 'aqualuxe') . '</span><i class="fas fa-chevron-right"></i>',
        ));
    } elseif ($pagination_style === 'prev-next') {
        the_posts_navigation(array(
            'prev_text' => '<i class="fas fa-chevron-left"></i> ' . __('Older Posts', 'aqualuxe'),
            'next_text' => __('Newer Posts', 'aqualuxe') . ' <i class="fas fa-chevron-right"></i>',
        ));
    } elseif ($pagination_style === 'load-more') {
        if (get_next_posts_link()) {
            echo '<div class="load-more-container">';
            echo '<button id="load-more" class="load-more-button">' . esc_html__('Load More', 'aqualuxe') . '</button>';
            echo '</div>';
        }
    } elseif ($pagination_style === 'infinite') {
        if (get_next_posts_link()) {
            echo '<div class="infinite-scroll-status">';
            echo '<div class="infinite-scroll-request">' . esc_html__('Loading...', 'aqualuxe') . '</div>';
            echo '</div>';
        }
    }
}

/**
 * Display popular search terms.
 */
function aqualuxe_popular_search_terms() {
    // This is a placeholder function. In a real theme, you would implement a way to track and display popular search terms.
    $popular_terms = array(
        'WordPress',
        'Theme',
        'AquaLuxe',
        'Customizer',
        'WooCommerce',
    );
    
    echo '<ul class="popular-search-terms">';
    foreach ($popular_terms as $term) {
        echo '<li><a href="' . esc_url(home_url('/?s=' . urlencode($term))) . '">' . esc_html($term) . '</a></li>';
    }
    echo '</ul>';
}

/**
 * Display recent posts.
 *
 * @param int $count Number of posts to display.
 */
function aqualuxe_recent_posts($count = 5) {
    $args = array(
        'post_type'      => 'post',
        'posts_per_page' => $count,
        'post_status'    => 'publish',
    );
    
    $recent_posts = new WP_Query($args);
    
    if ($recent_posts->have_posts()) {
        echo '<ul class="recent-posts-list">';
        while ($recent_posts->have_posts()) {
            $recent_posts->the_post();
            echo '<li><a href="' . esc_url(get_permalink()) . '">' . esc_html(get_the_title()) . '</a></li>';
        }
        echo '</ul>';
    }
    
    wp_reset_postdata();
}

/**
 * Add blog CSS to the head.
 */
function aqualuxe_blog_css() {
    $blog_layout = get_theme_mod('aqualuxe_blog_layout', 'standard');
    $blog_columns = get_theme_mod('aqualuxe_blog_columns', 2);
    $featured_image_style = get_theme_mod('aqualuxe_featured_image_style', 'large');
    $archive_title_style = get_theme_mod('aqualuxe_archive_title_style', 'default');
    
    ?>
    <style type="text/css">
        /* Blog Layout */
        .blog-layout-grid .posts-grid,
        .blog-layout-masonry .posts-grid {
            display: grid;
            grid-gap: 30px;
        }
        
        @media (min-width: 576px) {
            .blog-layout-grid .posts-grid,
            .blog-layout-masonry .posts-grid {
                grid-template-columns: repeat(<?php echo esc_attr($blog_columns); ?>, 1fr);
            }
        }
        
        .blog-layout-list .post {
            display: flex;
            margin-bottom: 30px;
        }
        
        .blog-layout-list .post-thumbnail {
            flex: 0 0 35%;
            margin-right: 30px;
        }
        
        .blog-layout-list .post-content {
            flex: 1;
        }
        
        @media (max-width: 575px) {
            .blog-layout-list .post {
                flex-direction: column;
            }
            
            .blog-layout-list .post-thumbnail {
                margin-right: 0;
                margin-bottom: 20px;
            }
        }
        
        /* Featured Image Style */
        <?php if ($featured_image_style === 'large') : ?>
        .single .post-thumbnail {
            margin-left: calc(-100vw / 2 + 100% / 2);
            margin-right: calc(-100vw / 2 + 100% / 2);
            max-width: 100vw;
            margin-bottom: 30px;
        }
        
        .single .post-thumbnail img {
            width: 100%;
            height: auto;
        }
        <?php elseif ($featured_image_style === 'medium') : ?>
        .single .post-thumbnail {
            margin-bottom: 30px;
        }
        
        .single .post-thumbnail img {
            width: 100%;
            height: auto;
        }
        <?php elseif ($featured_image_style === 'small') : ?>
        .single .post-thumbnail {
            float: right;
            margin: 0 0 20px 20px;
            max-width: 300px;
        }
        
        @media (max-width: 767px) {
            .single .post-thumbnail {
                float: none;
                margin: 0 0 20px 0;
                max-width: 100%;
            }
        }
        <?php elseif ($featured_image_style === 'none') : ?>
        .single .post-thumbnail {
            display: none;
        }
        <?php endif; ?>
        
        /* Archive Title Style */
        <?php if ($archive_title_style === 'centered') : ?>
        .archive-header,
        .search-header {
            text-align: center;
            margin-bottom: 40px;
        }
        <?php elseif ($archive_title_style === 'modern') : ?>
        .archive-header,
        .search-header {
            background-color: var(--aqualuxe-primary-color);
            color: #ffffff;
            padding: 40px 0;
            margin-bottom: 40px;
            text-align: center;
        }
        
        .archive-header .page-title,
        .search-header .page-title {
            color: #ffffff;
        }
        <?php elseif ($archive_title_style === 'minimal') : ?>
        .archive-header,
        .search-header {
            border-bottom: 1px solid #eee;
            padding-bottom: 20px;
            margin-bottom: 40px;
        }
        <?php endif; ?>
        
        /* Post Meta */
        .entry-meta {
            margin-bottom: 20px;
            font-size: 0.875rem;
            color: #666;
        }
        
        .entry-meta > span {
            margin-right: 15px;
        }
        
        .entry-meta a {
            color: #666;
            text-decoration: none;
        }
        
        .entry-meta a:hover {
            color: var(--aqualuxe-primary-color);
        }
        
        .entry-meta i {
            margin-right: 5px;
            color: var(--aqualuxe-primary-color);
        }
        
        /* Author Bio */
        .author-bio {
            display: flex;
            align-items: flex-start;
            margin: 40px 0;
            padding: 30px;
            background-color: #f9f9f9;
            border-radius: 5px;
        }
        
        .author-avatar {
            flex: 0 0 100px;
            margin-right: 20px;
        }
        
        .author-avatar img {
            border-radius: 50%;
        }
        
        .author-content {
            flex: 1;
        }
        
        .author-title {
            margin-top: 0;
            margin-bottom: 10px;
        }
        
        .author-description {
            margin-bottom: 10px;
        }
        
        .author-link {
            display: inline-block;
            color: var(--aqualuxe-primary-color);
            text-decoration: none;
        }
        
        /* Post Navigation */
        .post-navigation {
            margin: 40px 0;
        }
        
        .post-navigation .nav-links {
            display: flex;
            justify-content: space-between;
        }
        
        .post-navigation .nav-previous,
        .post-navigation .nav-next {
            flex: 0 0 48%;
        }
        
        .post-navigation a {
            display: flex;
            align-items: center;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 5px;
            text-decoration: none;
            color: var(--aqualuxe-text-color);
            transition: all 0.3s ease;
        }
        
        .post-navigation a:hover {
            background-color: #f0f0f0;
        }
        
        .post-navigation .nav-thumbnail {
            flex: 0 0 80px;
            margin-right: 15px;
        }
        
        .post-navigation .nav-next .nav-thumbnail {
            margin-right: 0;
            margin-left: 15px;
            order: 2;
        }
        
        .post-navigation .nav-content {
            flex: 1;
        }
        
        .post-navigation .nav-label {
            display: block;
            font-size: 0.75rem;
            text-transform: uppercase;
            color: #666;
            margin-bottom: 5px;
        }
        
        .post-navigation .nav-title {
            display: block;
            font-weight: 600;
        }
        
        /* Related Posts */
        .related-posts {
            margin: 40px 0;
        }
        
        .related-posts-title {
            margin-bottom: 20px;
        }
        
        .related-posts-grid {
            display: grid;
            grid-gap: 20px;
        }
        
        .related-posts-grid.columns-2 {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .related-posts-grid.columns-3 {
            grid-template-columns: repeat(3, 1fr);
        }
        
        .related-posts-grid.columns-4 {
            grid-template-columns: repeat(4, 1fr);
        }
        
        @media (max-width: 767px) {
            .related-posts-grid {
                grid-template-columns: repeat(2, 1fr) !important;
            }
        }
        
        @media (max-width: 575px) {
            .related-posts-grid {
                grid-template-columns: 1fr !important;
            }
        }
        
        .related-post {
            background-color: #f9f9f9;
            border-radius: 5px;
            overflow: hidden;
        }
        
        .related-post-thumbnail img {
            width: 100%;
            height: auto;
        }
        
        .related-post-content {
            padding: 15px;
        }
        
        .related-post-title {
            margin-top: 0;
            margin-bottom: 10px;
            font-size: 1rem;
        }
        
        .related-post-title a {
            text-decoration: none;
            color: var(--aqualuxe-text-color);
        }
        
        .related-post-title a:hover {
            color: var(--aqualuxe-primary-color);
        }
        
        .related-post-meta {
            font-size: 0.75rem;
            color: #666;
        }
        
        /* Pagination */
        .pagination,
        .posts-navigation,
        .post-navigation {
            margin: 40px 0;
        }
        
        .pagination .nav-links {
            display: flex;
            justify-content: center;
        }
        
        .pagination .page-numbers {
            display: inline-block;
            padding: 8px 12px;
            margin: 0 5px;
            background-color: #f9f9f9;
            border-radius: 3px;
            text-decoration: none;
            color: var(--aqualuxe-text-color);
        }
        
        .pagination .page-numbers.current {
            background-color: var(--aqualuxe-primary-color);
            color: #ffffff;
        }
        
        .pagination .page-numbers:hover {
            background-color: #f0f0f0;
        }
        
        .load-more-container {
            text-align: center;
            margin: 40px 0;
        }
        
        .load-more-button {
            display: inline-block;
            padding: 10px 20px;
            background-color: var(--aqualuxe-primary-color);
            color: #ffffff;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .load-more-button:hover {
            background-color: var(--aqualuxe-link-hover-color);
        }
        
        .infinite-scroll-status {
            text-align: center;
            margin: 40px 0;
        }
        
        .infinite-scroll-request {
            display: inline-block;
            padding: 10px 20px;
            background-color: #f9f9f9;
            border-radius: 3px;
        }
    </style>
    <?php
}
add_action('wp_head', 'aqualuxe_blog_css');