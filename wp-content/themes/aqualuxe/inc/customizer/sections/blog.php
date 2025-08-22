<?php
/**
 * AquaLuxe Blog Customizer Settings
 *
 * @package AquaLuxe
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Blog customizer settings
 *
 * @param WP_Customize_Manager $wp_customize Customizer manager
 */
function aqualuxe_customizer_blog_settings($wp_customize) {
    // Blog Panel
    $wp_customize->add_panel('aqualuxe_blog_panel', [
        'title'       => esc_html__('Blog', 'aqualuxe'),
        'description' => esc_html__('Blog settings', 'aqualuxe'),
        'priority'    => 60,
    ]);

    // Blog Archive Section
    $wp_customize->add_section('aqualuxe_blog_archive_section', [
        'title'       => esc_html__('Archive', 'aqualuxe'),
        'description' => esc_html__('Blog archive settings', 'aqualuxe'),
        'panel'       => 'aqualuxe_blog_panel',
        'priority'    => 10,
    ]);

    // Blog Layout
    $wp_customize->add_setting('blog_layout', [
        'default'           => 'default',
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ]);

    $wp_customize->add_control(new AquaLuxe_Radio_Image_Control($wp_customize, 'blog_layout', [
        'label'       => esc_html__('Blog Layout', 'aqualuxe'),
        'description' => esc_html__('Select the blog layout', 'aqualuxe'),
        'section'     => 'aqualuxe_blog_archive_section',
        'choices'     => [
            'default'      => [
                'label' => esc_html__('Default', 'aqualuxe'),
                'url'   => AQUALUXE_ASSETS_URI . 'images/customizer/blog-default.png',
            ],
            'grid'         => [
                'label' => esc_html__('Grid', 'aqualuxe'),
                'url'   => AQUALUXE_ASSETS_URI . 'images/customizer/blog-grid.png',
            ],
            'masonry'      => [
                'label' => esc_html__('Masonry', 'aqualuxe'),
                'url'   => AQUALUXE_ASSETS_URI . 'images/customizer/blog-masonry.png',
            ],
            'list'         => [
                'label' => esc_html__('List', 'aqualuxe'),
                'url'   => AQUALUXE_ASSETS_URI . 'images/customizer/blog-list.png',
            ],
            'classic'      => [
                'label' => esc_html__('Classic', 'aqualuxe'),
                'url'   => AQUALUXE_ASSETS_URI . 'images/customizer/blog-classic.png',
            ],
        ],
    ]));

    // Blog Columns
    $wp_customize->add_setting('blog_columns', [
        'default'           => 3,
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_number',
    ]);

    $wp_customize->add_control('blog_columns', [
        'label'           => esc_html__('Blog Columns', 'aqualuxe'),
        'description'     => esc_html__('Set the number of columns for grid and masonry layouts', 'aqualuxe'),
        'section'         => 'aqualuxe_blog_archive_section',
        'type'            => 'select',
        'choices'         => [
            '2' => esc_html__('2 Columns', 'aqualuxe'),
            '3' => esc_html__('3 Columns', 'aqualuxe'),
            '4' => esc_html__('4 Columns', 'aqualuxe'),
        ],
        'active_callback' => function() {
            return in_array(aqualuxe_get_theme_mod('blog_layout', 'default'), ['grid', 'masonry']);
        },
    ]);

    // Blog Sidebar
    $wp_customize->add_setting('blog_sidebar', [
        'default'           => 'right',
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ]);

    $wp_customize->add_control('blog_sidebar', [
        'label'       => esc_html__('Blog Sidebar', 'aqualuxe'),
        'description' => esc_html__('Select the sidebar position for blog archive', 'aqualuxe'),
        'section'     => 'aqualuxe_blog_archive_section',
        'type'        => 'select',
        'choices'     => [
            'right' => esc_html__('Right', 'aqualuxe'),
            'left'  => esc_html__('Left', 'aqualuxe'),
            'none'  => esc_html__('None', 'aqualuxe'),
        ],
    ]);

    // Blog Posts Per Page
    $wp_customize->add_setting('blog_posts_per_page', [
        'default'           => get_option('posts_per_page'),
        'transport'         => 'refresh',
        'sanitize_callback' => 'absint',
    ]);

    $wp_customize->add_control('blog_posts_per_page', [
        'label'       => esc_html__('Posts Per Page', 'aqualuxe'),
        'description' => esc_html__('Set the number of posts to display per page', 'aqualuxe'),
        'section'     => 'aqualuxe_blog_archive_section',
        'type'        => 'number',
        'input_attrs' => [
            'min'  => 1,
            'max'  => 100,
            'step' => 1,
        ],
    ]);

    // Blog Pagination Type
    $wp_customize->add_setting('blog_pagination_type', [
        'default'           => 'numbered',
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ]);

    $wp_customize->add_control('blog_pagination_type', [
        'label'       => esc_html__('Pagination Type', 'aqualuxe'),
        'description' => esc_html__('Select the pagination type for blog archive', 'aqualuxe'),
        'section'     => 'aqualuxe_blog_archive_section',
        'type'        => 'select',
        'choices'     => [
            'numbered'     => esc_html__('Numbered', 'aqualuxe'),
            'prev_next'    => esc_html__('Previous / Next', 'aqualuxe'),
            'load_more'    => esc_html__('Load More', 'aqualuxe'),
            'infinite'     => esc_html__('Infinite Scroll', 'aqualuxe'),
        ],
    ]);

    // Blog Show Featured Image
    $wp_customize->add_setting('blog_show_featured_image', [
        'default'           => true,
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ]);

    $wp_customize->add_control(new AquaLuxe_Toggle_Control($wp_customize, 'blog_show_featured_image', [
        'label'       => esc_html__('Show Featured Image', 'aqualuxe'),
        'description' => esc_html__('Show featured image in blog archive', 'aqualuxe'),
        'section'     => 'aqualuxe_blog_archive_section',
    ]));

    // Blog Show Excerpt
    $wp_customize->add_setting('blog_show_excerpt', [
        'default'           => true,
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ]);

    $wp_customize->add_control(new AquaLuxe_Toggle_Control($wp_customize, 'blog_show_excerpt', [
        'label'       => esc_html__('Show Excerpt', 'aqualuxe'),
        'description' => esc_html__('Show excerpt in blog archive', 'aqualuxe'),
        'section'     => 'aqualuxe_blog_archive_section',
    ]));

    // Blog Excerpt Length
    $wp_customize->add_setting('blog_excerpt_length', [
        'default'           => 30,
        'transport'         => 'refresh',
        'sanitize_callback' => 'absint',
    ]);

    $wp_customize->add_control('blog_excerpt_length', [
        'label'           => esc_html__('Excerpt Length', 'aqualuxe'),
        'description'     => esc_html__('Set the number of words for excerpt', 'aqualuxe'),
        'section'         => 'aqualuxe_blog_archive_section',
        'type'            => 'number',
        'input_attrs'     => [
            'min'  => 10,
            'max'  => 100,
            'step' => 1,
        ],
        'active_callback' => function() {
            return aqualuxe_get_theme_mod('blog_show_excerpt', true);
        },
    ]);

    // Blog Show Read More
    $wp_customize->add_setting('blog_show_read_more', [
        'default'           => true,
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ]);

    $wp_customize->add_control(new AquaLuxe_Toggle_Control($wp_customize, 'blog_show_read_more', [
        'label'       => esc_html__('Show Read More', 'aqualuxe'),
        'description' => esc_html__('Show read more button in blog archive', 'aqualuxe'),
        'section'     => 'aqualuxe_blog_archive_section',
    ]));

    // Blog Read More Text
    $wp_customize->add_setting('blog_read_more_text', [
        'default'           => esc_html__('Read More', 'aqualuxe'),
        'transport'         => 'refresh',
        'sanitize_callback' => 'sanitize_text_field',
    ]);

    $wp_customize->add_control('blog_read_more_text', [
        'label'           => esc_html__('Read More Text', 'aqualuxe'),
        'description'     => esc_html__('Set the text for read more button', 'aqualuxe'),
        'section'         => 'aqualuxe_blog_archive_section',
        'type'            => 'text',
        'active_callback' => function() {
            return aqualuxe_get_theme_mod('blog_show_read_more', true);
        },
    ]);

    // Blog Meta Section
    $wp_customize->add_section('aqualuxe_blog_meta_section', [
        'title'       => esc_html__('Meta', 'aqualuxe'),
        'description' => esc_html__('Blog meta settings', 'aqualuxe'),
        'panel'       => 'aqualuxe_blog_panel',
        'priority'    => 20,
    ]);

    // Blog Show Meta
    $wp_customize->add_setting('blog_show_meta', [
        'default'           => true,
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ]);

    $wp_customize->add_control(new AquaLuxe_Toggle_Control($wp_customize, 'blog_show_meta', [
        'label'       => esc_html__('Show Meta', 'aqualuxe'),
        'description' => esc_html__('Show meta information in blog archive', 'aqualuxe'),
        'section'     => 'aqualuxe_blog_meta_section',
    ]));

    // Blog Meta Elements
    $wp_customize->add_setting('blog_meta_elements', [
        'default'           => ['author', 'date', 'categories', 'comments'],
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_multi_choices',
    ]);

    $wp_customize->add_control(new AquaLuxe_Sortable_Control($wp_customize, 'blog_meta_elements', [
        'label'           => esc_html__('Meta Elements', 'aqualuxe'),
        'description'     => esc_html__('Select and reorder meta elements', 'aqualuxe'),
        'section'         => 'aqualuxe_blog_meta_section',
        'choices'         => [
            'author'     => esc_html__('Author', 'aqualuxe'),
            'date'       => esc_html__('Date', 'aqualuxe'),
            'categories' => esc_html__('Categories', 'aqualuxe'),
            'tags'       => esc_html__('Tags', 'aqualuxe'),
            'comments'   => esc_html__('Comments', 'aqualuxe'),
        ],
        'active_callback' => function() {
            return aqualuxe_get_theme_mod('blog_show_meta', true);
        },
    ]));

    // Blog Meta Position
    $wp_customize->add_setting('blog_meta_position', [
        'default'           => 'after_title',
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ]);

    $wp_customize->add_control('blog_meta_position', [
        'label'           => esc_html__('Meta Position', 'aqualuxe'),
        'description'     => esc_html__('Select the position of meta information', 'aqualuxe'),
        'section'         => 'aqualuxe_blog_meta_section',
        'type'            => 'select',
        'choices'         => [
            'before_title'  => esc_html__('Before Title', 'aqualuxe'),
            'after_title'   => esc_html__('After Title', 'aqualuxe'),
            'after_content' => esc_html__('After Content', 'aqualuxe'),
        ],
        'active_callback' => function() {
            return aqualuxe_get_theme_mod('blog_show_meta', true);
        },
    ]);

    // Blog Date Format
    $wp_customize->add_setting('blog_date_format', [
        'default'           => 'default',
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ]);

    $wp_customize->add_control('blog_date_format', [
        'label'           => esc_html__('Date Format', 'aqualuxe'),
        'description'     => esc_html__('Select the date format', 'aqualuxe'),
        'section'         => 'aqualuxe_blog_meta_section',
        'type'            => 'select',
        'choices'         => [
            'default'     => esc_html__('Default', 'aqualuxe'),
            'F j, Y'      => date_i18n('F j, Y'),
            'Y-m-d'       => date_i18n('Y-m-d'),
            'm/d/Y'       => date_i18n('m/d/Y'),
            'd/m/Y'       => date_i18n('d/m/Y'),
            'relative'    => esc_html__('Relative (e.g., 5 days ago)', 'aqualuxe'),
        ],
        'active_callback' => function() {
            return aqualuxe_get_theme_mod('blog_show_meta', true) && in_array('date', aqualuxe_get_theme_mod('blog_meta_elements', ['author', 'date', 'categories', 'comments']));
        },
    ]);

    // Blog Author Display
    $wp_customize->add_setting('blog_author_display', [
        'default'           => 'name',
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ]);

    $wp_customize->add_control('blog_author_display', [
        'label'           => esc_html__('Author Display', 'aqualuxe'),
        'description'     => esc_html__('Select how to display the author', 'aqualuxe'),
        'section'         => 'aqualuxe_blog_meta_section',
        'type'            => 'select',
        'choices'         => [
            'name'        => esc_html__('Name Only', 'aqualuxe'),
            'avatar_name' => esc_html__('Avatar and Name', 'aqualuxe'),
        ],
        'active_callback' => function() {
            return aqualuxe_get_theme_mod('blog_show_meta', true) && in_array('author', aqualuxe_get_theme_mod('blog_meta_elements', ['author', 'date', 'categories', 'comments']));
        },
    ]);

    // Blog Single Section
    $wp_customize->add_section('aqualuxe_blog_single_section', [
        'title'       => esc_html__('Single Post', 'aqualuxe'),
        'description' => esc_html__('Single post settings', 'aqualuxe'),
        'panel'       => 'aqualuxe_blog_panel',
        'priority'    => 30,
    ]);

    // Single Post Layout
    $wp_customize->add_setting('single_post_layout', [
        'default'           => 'default',
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ]);

    $wp_customize->add_control(new AquaLuxe_Radio_Image_Control($wp_customize, 'single_post_layout', [
        'label'       => esc_html__('Single Post Layout', 'aqualuxe'),
        'description' => esc_html__('Select the single post layout', 'aqualuxe'),
        'section'     => 'aqualuxe_blog_single_section',
        'choices'     => [
            'default'      => [
                'label' => esc_html__('Default', 'aqualuxe'),
                'url'   => AQUALUXE_ASSETS_URI . 'images/customizer/single-default.png',
            ],
            'wide'         => [
                'label' => esc_html__('Wide', 'aqualuxe'),
                'url'   => AQUALUXE_ASSETS_URI . 'images/customizer/single-wide.png',
            ],
            'narrow'       => [
                'label' => esc_html__('Narrow', 'aqualuxe'),
                'url'   => AQUALUXE_ASSETS_URI . 'images/customizer/single-narrow.png',
            ],
            'cover'        => [
                'label' => esc_html__('Cover', 'aqualuxe'),
                'url'   => AQUALUXE_ASSETS_URI . 'images/customizer/single-cover.png',
            ],
        ],
    ]));

    // Single Post Sidebar
    $wp_customize->add_setting('single_post_sidebar', [
        'default'           => 'right',
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ]);

    $wp_customize->add_control('single_post_sidebar', [
        'label'           => esc_html__('Single Post Sidebar', 'aqualuxe'),
        'description'     => esc_html__('Select the sidebar position for single post', 'aqualuxe'),
        'section'         => 'aqualuxe_blog_single_section',
        'type'            => 'select',
        'choices'         => [
            'right' => esc_html__('Right', 'aqualuxe'),
            'left'  => esc_html__('Left', 'aqualuxe'),
            'none'  => esc_html__('None', 'aqualuxe'),
        ],
        'active_callback' => function() {
            return aqualuxe_get_theme_mod('single_post_layout', 'default') !== 'cover';
        },
    ]);

    // Single Post Show Featured Image
    $wp_customize->add_setting('single_post_show_featured_image', [
        'default'           => true,
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ]);

    $wp_customize->add_control(new AquaLuxe_Toggle_Control($wp_customize, 'single_post_show_featured_image', [
        'label'       => esc_html__('Show Featured Image', 'aqualuxe'),
        'description' => esc_html__('Show featured image in single post', 'aqualuxe'),
        'section'     => 'aqualuxe_blog_single_section',
    ]));

    // Single Post Featured Image Position
    $wp_customize->add_setting('single_post_featured_image_position', [
        'default'           => 'above_title',
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ]);

    $wp_customize->add_control('single_post_featured_image_position', [
        'label'           => esc_html__('Featured Image Position', 'aqualuxe'),
        'description'     => esc_html__('Select the position of featured image', 'aqualuxe'),
        'section'         => 'aqualuxe_blog_single_section',
        'type'            => 'select',
        'choices'         => [
            'above_title'  => esc_html__('Above Title', 'aqualuxe'),
            'below_title'  => esc_html__('Below Title', 'aqualuxe'),
            'below_content' => esc_html__('Below Content', 'aqualuxe'),
        ],
        'active_callback' => function() {
            return aqualuxe_get_theme_mod('single_post_show_featured_image', true) && aqualuxe_get_theme_mod('single_post_layout', 'default') !== 'cover';
        },
    ]);

    // Single Post Show Meta
    $wp_customize->add_setting('single_post_show_meta', [
        'default'           => true,
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ]);

    $wp_customize->add_control(new AquaLuxe_Toggle_Control($wp_customize, 'single_post_show_meta', [
        'label'       => esc_html__('Show Meta', 'aqualuxe'),
        'description' => esc_html__('Show meta information in single post', 'aqualuxe'),
        'section'     => 'aqualuxe_blog_single_section',
    ]));

    // Single Post Meta Elements
    $wp_customize->add_setting('single_post_meta_elements', [
        'default'           => ['author', 'date', 'categories', 'comments'],
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_multi_choices',
    ]);

    $wp_customize->add_control(new AquaLuxe_Sortable_Control($wp_customize, 'single_post_meta_elements', [
        'label'           => esc_html__('Meta Elements', 'aqualuxe'),
        'description'     => esc_html__('Select and reorder meta elements', 'aqualuxe'),
        'section'         => 'aqualuxe_blog_single_section',
        'choices'         => [
            'author'     => esc_html__('Author', 'aqualuxe'),
            'date'       => esc_html__('Date', 'aqualuxe'),
            'categories' => esc_html__('Categories', 'aqualuxe'),
            'tags'       => esc_html__('Tags', 'aqualuxe'),
            'comments'   => esc_html__('Comments', 'aqualuxe'),
        ],
        'active_callback' => function() {
            return aqualuxe_get_theme_mod('single_post_show_meta', true);
        },
    ]));

    // Single Post Meta Position
    $wp_customize->add_setting('single_post_meta_position', [
        'default'           => 'after_title',
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ]);

    $wp_customize->add_control('single_post_meta_position', [
        'label'           => esc_html__('Meta Position', 'aqualuxe'),
        'description'     => esc_html__('Select the position of meta information', 'aqualuxe'),
        'section'         => 'aqualuxe_blog_single_section',
        'type'            => 'select',
        'choices'         => [
            'before_title'  => esc_html__('Before Title', 'aqualuxe'),
            'after_title'   => esc_html__('After Title', 'aqualuxe'),
            'after_content' => esc_html__('After Content', 'aqualuxe'),
        ],
        'active_callback' => function() {
            return aqualuxe_get_theme_mod('single_post_show_meta', true);
        },
    ]);

    // Single Post Show Author Box
    $wp_customize->add_setting('single_post_show_author_box', [
        'default'           => true,
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ]);

    $wp_customize->add_control(new AquaLuxe_Toggle_Control($wp_customize, 'single_post_show_author_box', [
        'label'       => esc_html__('Show Author Box', 'aqualuxe'),
        'description' => esc_html__('Show author box in single post', 'aqualuxe'),
        'section'     => 'aqualuxe_blog_single_section',
    ]));

    // Single Post Show Post Navigation
    $wp_customize->add_setting('single_post_show_post_navigation', [
        'default'           => true,
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ]);

    $wp_customize->add_control(new AquaLuxe_Toggle_Control($wp_customize, 'single_post_show_post_navigation', [
        'label'       => esc_html__('Show Post Navigation', 'aqualuxe'),
        'description' => esc_html__('Show previous/next post navigation in single post', 'aqualuxe'),
        'section'     => 'aqualuxe_blog_single_section',
    ]));

    // Single Post Show Related Posts
    $wp_customize->add_setting('single_post_show_related_posts', [
        'default'           => true,
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ]);

    $wp_customize->add_control(new AquaLuxe_Toggle_Control($wp_customize, 'single_post_show_related_posts', [
        'label'       => esc_html__('Show Related Posts', 'aqualuxe'),
        'description' => esc_html__('Show related posts in single post', 'aqualuxe'),
        'section'     => 'aqualuxe_blog_single_section',
    ]));

    // Single Post Related Posts Number
    $wp_customize->add_setting('single_post_related_posts_number', [
        'default'           => 3,
        'transport'         => 'refresh',
        'sanitize_callback' => 'absint',
    ]);

    $wp_customize->add_control('single_post_related_posts_number', [
        'label'           => esc_html__('Related Posts Number', 'aqualuxe'),
        'description'     => esc_html__('Set the number of related posts to display', 'aqualuxe'),
        'section'         => 'aqualuxe_blog_single_section',
        'type'            => 'number',
        'input_attrs'     => [
            'min'  => 1,
            'max'  => 12,
            'step' => 1,
        ],
        'active_callback' => function() {
            return aqualuxe_get_theme_mod('single_post_show_related_posts', true);
        },
    ]);

    // Single Post Related Posts Columns
    $wp_customize->add_setting('single_post_related_posts_columns', [
        'default'           => 3,
        'transport'         => 'refresh',
        'sanitize_callback' => 'absint',
    ]);

    $wp_customize->add_control('single_post_related_posts_columns', [
        'label'           => esc_html__('Related Posts Columns', 'aqualuxe'),
        'description'     => esc_html__('Set the number of columns for related posts', 'aqualuxe'),
        'section'         => 'aqualuxe_blog_single_section',
        'type'            => 'select',
        'choices'         => [
            '2' => esc_html__('2 Columns', 'aqualuxe'),
            '3' => esc_html__('3 Columns', 'aqualuxe'),
            '4' => esc_html__('4 Columns', 'aqualuxe'),
        ],
        'active_callback' => function() {
            return aqualuxe_get_theme_mod('single_post_show_related_posts', true);
        },
    ]);

    // Single Post Show Comments
    $wp_customize->add_setting('single_post_show_comments', [
        'default'           => true,
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ]);

    $wp_customize->add_control(new AquaLuxe_Toggle_Control($wp_customize, 'single_post_show_comments', [
        'label'       => esc_html__('Show Comments', 'aqualuxe'),
        'description' => esc_html__('Show comments in single post', 'aqualuxe'),
        'section'     => 'aqualuxe_blog_single_section',
    ]));

    // Single Post Show Social Share
    $wp_customize->add_setting('single_post_show_social_share', [
        'default'           => true,
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ]);

    $wp_customize->add_control(new AquaLuxe_Toggle_Control($wp_customize, 'single_post_show_social_share', [
        'label'       => esc_html__('Show Social Share', 'aqualuxe'),
        'description' => esc_html__('Show social share buttons in single post', 'aqualuxe'),
        'section'     => 'aqualuxe_blog_single_section',
    ]));

    // Single Post Social Share Networks
    $wp_customize->add_setting('single_post_social_share_networks', [
        'default'           => ['facebook', 'twitter', 'linkedin', 'pinterest', 'email'],
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_multi_choices',
    ]);

    $wp_customize->add_control(new AquaLuxe_Sortable_Control($wp_customize, 'single_post_social_share_networks', [
        'label'           => esc_html__('Social Share Networks', 'aqualuxe'),
        'description'     => esc_html__('Select and reorder social share networks', 'aqualuxe'),
        'section'         => 'aqualuxe_blog_single_section',
        'choices'         => [
            'facebook'  => esc_html__('Facebook', 'aqualuxe'),
            'twitter'   => esc_html__('Twitter', 'aqualuxe'),
            'linkedin'  => esc_html__('LinkedIn', 'aqualuxe'),
            'pinterest' => esc_html__('Pinterest', 'aqualuxe'),
            'reddit'    => esc_html__('Reddit', 'aqualuxe'),
            'tumblr'    => esc_html__('Tumblr', 'aqualuxe'),
            'whatsapp'  => esc_html__('WhatsApp', 'aqualuxe'),
            'telegram'  => esc_html__('Telegram', 'aqualuxe'),
            'email'     => esc_html__('Email', 'aqualuxe'),
        ],
        'active_callback' => function() {
            return aqualuxe_get_theme_mod('single_post_show_social_share', true);
        },
    ]));

    // Single Post Social Share Position
    $wp_customize->add_setting('single_post_social_share_position', [
        'default'           => 'after_content',
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ]);

    $wp_customize->add_control('single_post_social_share_position', [
        'label'           => esc_html__('Social Share Position', 'aqualuxe'),
        'description'     => esc_html__('Select the position of social share buttons', 'aqualuxe'),
        'section'         => 'aqualuxe_blog_single_section',
        'type'            => 'select',
        'choices'         => [
            'before_content' => esc_html__('Before Content', 'aqualuxe'),
            'after_content'  => esc_html__('After Content', 'aqualuxe'),
            'floating_left'  => esc_html__('Floating Left', 'aqualuxe'),
            'floating_right' => esc_html__('Floating Right', 'aqualuxe'),
            'both'           => esc_html__('Before and After Content', 'aqualuxe'),
        ],
        'active_callback' => function() {
            return aqualuxe_get_theme_mod('single_post_show_social_share', true);
        },
    ]);
}