<?php
/**
 * Blog Customizer Section
 *
 * @package AquaLuxe
 */

/**
 * Add blog settings to the Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_register_blog($wp_customize) {
    // Add Blog section
    $wp_customize->add_section(
        'aqualuxe_blog',
        array(
            'title'    => esc_html__('Blog Settings', 'aqualuxe'),
            'priority' => 50,
        )
    );

    // Blog Layout
    $wp_customize->add_setting(
        'aqualuxe_blog_layout',
        array(
            'default'           => 'grid',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_blog_layout',
        array(
            'label'   => esc_html__('Blog Layout', 'aqualuxe'),
            'section' => 'aqualuxe_blog',
            'type'    => 'select',
            'choices' => array(
                'grid'      => esc_html__('Grid', 'aqualuxe'),
                'list'      => esc_html__('List', 'aqualuxe'),
                'masonry'   => esc_html__('Masonry', 'aqualuxe'),
                'standard'  => esc_html__('Standard', 'aqualuxe'),
            ),
        )
    );

    // Blog Columns
    $wp_customize->add_setting(
        'aqualuxe_blog_columns',
        array(
            'default'           => '3',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_blog_columns',
        array(
            'label'   => esc_html__('Blog Columns', 'aqualuxe'),
            'section' => 'aqualuxe_blog',
            'type'    => 'select',
            'choices' => array(
                '1' => '1',
                '2' => '2',
                '3' => '3',
                '4' => '4',
            ),
            'active_callback' => function() {
                return in_array(get_theme_mod('aqualuxe_blog_layout', 'grid'), array('grid', 'masonry'));
            },
        )
    );

    // Featured Posts
    $wp_customize->add_setting(
        'aqualuxe_show_featured_posts',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_show_featured_posts',
        array(
            'label'   => esc_html__('Show Featured Posts', 'aqualuxe'),
            'section' => 'aqualuxe_blog',
            'type'    => 'checkbox',
        )
    );

    $wp_customize->add_setting(
        'aqualuxe_featured_posts_count',
        array(
            'default'           => '3',
            'sanitize_callback' => 'absint',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_featured_posts_count',
        array(
            'label'       => esc_html__('Number of Featured Posts', 'aqualuxe'),
            'section'     => 'aqualuxe_blog',
            'type'        => 'number',
            'input_attrs' => array(
                'min'  => 1,
                'max'  => 10,
                'step' => 1,
            ),
            'active_callback' => function() {
                return get_theme_mod('aqualuxe_show_featured_posts', true);
            },
        )
    );

    $wp_customize->add_setting(
        'aqualuxe_featured_posts_source',
        array(
            'default'           => 'latest',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_featured_posts_source',
        array(
            'label'   => esc_html__('Featured Posts Source', 'aqualuxe'),
            'section' => 'aqualuxe_blog',
            'type'    => 'select',
            'choices' => array(
                'latest'    => esc_html__('Latest Posts', 'aqualuxe'),
                'sticky'    => esc_html__('Sticky Posts', 'aqualuxe'),
                'category'  => esc_html__('Specific Category', 'aqualuxe'),
                'tag'       => esc_html__('Specific Tag', 'aqualuxe'),
            ),
            'active_callback' => function() {
                return get_theme_mod('aqualuxe_show_featured_posts', true);
            },
        )
    );

    // Get all categories
    $categories = get_categories(array(
        'orderby'    => 'name',
        'order'      => 'ASC',
        'hide_empty' => false,
    ));

    $category_choices = array();
    foreach ($categories as $category) {
        $category_choices[$category->term_id] = $category->name;
    }

    $wp_customize->add_setting(
        'aqualuxe_featured_posts_category',
        array(
            'default'           => '',
            'sanitize_callback' => 'absint',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_featured_posts_category',
        array(
            'label'   => esc_html__('Featured Posts Category', 'aqualuxe'),
            'section' => 'aqualuxe_blog',
            'type'    => 'select',
            'choices' => $category_choices,
            'active_callback' => function() {
                return get_theme_mod('aqualuxe_show_featured_posts', true) && get_theme_mod('aqualuxe_featured_posts_source', 'latest') === 'category';
            },
        )
    );

    // Get all tags
    $tags = get_tags(array(
        'orderby'    => 'name',
        'order'      => 'ASC',
        'hide_empty' => false,
    ));

    $tag_choices = array();
    foreach ($tags as $tag) {
        $tag_choices[$tag->term_id] = $tag->name;
    }

    $wp_customize->add_setting(
        'aqualuxe_featured_posts_tag',
        array(
            'default'           => '',
            'sanitize_callback' => 'absint',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_featured_posts_tag',
        array(
            'label'   => esc_html__('Featured Posts Tag', 'aqualuxe'),
            'section' => 'aqualuxe_blog',
            'type'    => 'select',
            'choices' => $tag_choices,
            'active_callback' => function() {
                return get_theme_mod('aqualuxe_show_featured_posts', true) && get_theme_mod('aqualuxe_featured_posts_source', 'latest') === 'tag';
            },
        )
    );

    // Excerpt Length
    $wp_customize->add_setting(
        'aqualuxe_excerpt_length',
        array(
            'default'           => '55',
            'sanitize_callback' => 'absint',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_excerpt_length',
        array(
            'label'       => esc_html__('Excerpt Length (words)', 'aqualuxe'),
            'section'     => 'aqualuxe_blog',
            'type'        => 'number',
            'input_attrs' => array(
                'min'  => 10,
                'max'  => 200,
                'step' => 5,
            ),
        )
    );

    // Read More Text
    $wp_customize->add_setting(
        'aqualuxe_read_more_text',
        array(
            'default'           => esc_html__('Continue reading', 'aqualuxe'),
            'sanitize_callback' => 'sanitize_text_field',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_read_more_text',
        array(
            'label'   => esc_html__('Read More Text', 'aqualuxe'),
            'section' => 'aqualuxe_blog',
            'type'    => 'text',
        )
    );

    // Pagination Type
    $wp_customize->add_setting(
        'aqualuxe_pagination_type',
        array(
            'default'           => 'numbered',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_pagination_type',
        array(
            'label'   => esc_html__('Pagination Type', 'aqualuxe'),
            'section' => 'aqualuxe_blog',
            'type'    => 'select',
            'choices' => array(
                'numbered'   => esc_html__('Numbered', 'aqualuxe'),
                'prev_next'  => esc_html__('Previous / Next', 'aqualuxe'),
                'load_more'  => esc_html__('Load More Button', 'aqualuxe'),
                'infinite'   => esc_html__('Infinite Scroll', 'aqualuxe'),
            ),
        )
    );

    // Posts Per Page
    $wp_customize->add_setting(
        'aqualuxe_posts_per_page',
        array(
            'default'           => get_option('posts_per_page'),
            'sanitize_callback' => 'absint',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_posts_per_page',
        array(
            'label'       => esc_html__('Posts Per Page', 'aqualuxe'),
            'section'     => 'aqualuxe_blog',
            'type'        => 'number',
            'input_attrs' => array(
                'min'  => 1,
                'max'  => 50,
                'step' => 1,
            ),
        )
    );

    // Post Meta
    $wp_customize->add_setting(
        'aqualuxe_show_post_meta',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_show_post_meta',
        array(
            'label'   => esc_html__('Show Post Meta', 'aqualuxe'),
            'section' => 'aqualuxe_blog',
            'type'    => 'checkbox',
        )
    );

    $wp_customize->add_setting(
        'aqualuxe_show_post_author',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_show_post_author',
        array(
            'label'   => esc_html__('Show Post Author', 'aqualuxe'),
            'section' => 'aqualuxe_blog',
            'type'    => 'checkbox',
            'active_callback' => function() {
                return get_theme_mod('aqualuxe_show_post_meta', true);
            },
        )
    );

    $wp_customize->add_setting(
        'aqualuxe_show_post_date',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_show_post_date',
        array(
            'label'   => esc_html__('Show Post Date', 'aqualuxe'),
            'section' => 'aqualuxe_blog',
            'type'    => 'checkbox',
            'active_callback' => function() {
                return get_theme_mod('aqualuxe_show_post_meta', true);
            },
        )
    );

    $wp_customize->add_setting(
        'aqualuxe_show_post_categories',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_show_post_categories',
        array(
            'label'   => esc_html__('Show Post Categories', 'aqualuxe'),
            'section' => 'aqualuxe_blog',
            'type'    => 'checkbox',
            'active_callback' => function() {
                return get_theme_mod('aqualuxe_show_post_meta', true);
            },
        )
    );

    $wp_customize->add_setting(
        'aqualuxe_show_post_comments',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_show_post_comments',
        array(
            'label'   => esc_html__('Show Post Comments Count', 'aqualuxe'),
            'section' => 'aqualuxe_blog',
            'type'    => 'checkbox',
            'active_callback' => function() {
                return get_theme_mod('aqualuxe_show_post_meta', true);
            },
        )
    );

    $wp_customize->add_setting(
        'aqualuxe_show_reading_time',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_show_reading_time',
        array(
            'label'   => esc_html__('Show Reading Time', 'aqualuxe'),
            'section' => 'aqualuxe_blog',
            'type'    => 'checkbox',
            'active_callback' => function() {
                return get_theme_mod('aqualuxe_show_post_meta', true);
            },
        )
    );

    // Single Post Settings
    $wp_customize->add_setting(
        'aqualuxe_show_post_thumbnail',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_show_post_thumbnail',
        array(
            'label'   => esc_html__('Show Featured Image on Single Post', 'aqualuxe'),
            'section' => 'aqualuxe_blog',
            'type'    => 'checkbox',
        )
    );

    $wp_customize->add_setting(
        'aqualuxe_show_post_tags',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_show_post_tags',
        array(
            'label'   => esc_html__('Show Post Tags', 'aqualuxe'),
            'section' => 'aqualuxe_blog',
            'type'    => 'checkbox',
        )
    );

    $wp_customize->add_setting(
        'aqualuxe_show_post_navigation',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_show_post_navigation',
        array(
            'label'   => esc_html__('Show Post Navigation', 'aqualuxe'),
            'section' => 'aqualuxe_blog',
            'type'    => 'checkbox',
        )
    );

    $wp_customize->add_setting(
        'aqualuxe_show_author_bio',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_show_author_bio',
        array(
            'label'   => esc_html__('Show Author Bio', 'aqualuxe'),
            'section' => 'aqualuxe_blog',
            'type'    => 'checkbox',
        )
    );

    $wp_customize->add_setting(
        'aqualuxe_show_related_posts',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_show_related_posts',
        array(
            'label'   => esc_html__('Show Related Posts', 'aqualuxe'),
            'section' => 'aqualuxe_blog',
            'type'    => 'checkbox',
        )
    );

    $wp_customize->add_setting(
        'aqualuxe_related_posts_count',
        array(
            'default'           => '3',
            'sanitize_callback' => 'absint',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_related_posts_count',
        array(
            'label'       => esc_html__('Number of Related Posts', 'aqualuxe'),
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
        )
    );

    // Social Sharing
    $wp_customize->add_setting(
        'aqualuxe_enable_social_sharing',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_enable_social_sharing',
        array(
            'label'   => esc_html__('Enable Social Sharing', 'aqualuxe'),
            'section' => 'aqualuxe_blog',
            'type'    => 'checkbox',
        )
    );

    $wp_customize->add_setting(
        'aqualuxe_enable_facebook_sharing',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_enable_facebook_sharing',
        array(
            'label'   => esc_html__('Enable Facebook Sharing', 'aqualuxe'),
            'section' => 'aqualuxe_blog',
            'type'    => 'checkbox',
            'active_callback' => function() {
                return get_theme_mod('aqualuxe_enable_social_sharing', true);
            },
        )
    );

    $wp_customize->add_setting(
        'aqualuxe_enable_twitter_sharing',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_enable_twitter_sharing',
        array(
            'label'   => esc_html__('Enable Twitter Sharing', 'aqualuxe'),
            'section' => 'aqualuxe_blog',
            'type'    => 'checkbox',
            'active_callback' => function() {
                return get_theme_mod('aqualuxe_enable_social_sharing', true);
            },
        )
    );

    $wp_customize->add_setting(
        'aqualuxe_enable_linkedin_sharing',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_enable_linkedin_sharing',
        array(
            'label'   => esc_html__('Enable LinkedIn Sharing', 'aqualuxe'),
            'section' => 'aqualuxe_blog',
            'type'    => 'checkbox',
            'active_callback' => function() {
                return get_theme_mod('aqualuxe_enable_social_sharing', true);
            },
        )
    );

    $wp_customize->add_setting(
        'aqualuxe_enable_pinterest_sharing',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_enable_pinterest_sharing',
        array(
            'label'   => esc_html__('Enable Pinterest Sharing', 'aqualuxe'),
            'section' => 'aqualuxe_blog',
            'type'    => 'checkbox',
            'active_callback' => function() {
                return get_theme_mod('aqualuxe_enable_social_sharing', true);
            },
        )
    );

    $wp_customize->add_setting(
        'aqualuxe_enable_email_sharing',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_enable_email_sharing',
        array(
            'label'   => esc_html__('Enable Email Sharing', 'aqualuxe'),
            'section' => 'aqualuxe_blog',
            'type'    => 'checkbox',
            'active_callback' => function() {
                return get_theme_mod('aqualuxe_enable_social_sharing', true);
            },
        )
    );

    // Post Views
    $wp_customize->add_setting(
        'aqualuxe_show_post_views',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_show_post_views',
        array(
            'label'   => esc_html__('Show Post Views Count', 'aqualuxe'),
            'section' => 'aqualuxe_blog',
            'type'    => 'checkbox',
        )
    );

    // Fallback Featured Image
    $wp_customize->add_setting(
        'aqualuxe_fallback_thumbnail',
        array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Image_Control(
            $wp_customize,
            'aqualuxe_fallback_thumbnail',
            array(
                'label'   => esc_html__('Fallback Featured Image', 'aqualuxe'),
                'description' => esc_html__('Used when posts do not have a featured image.', 'aqualuxe'),
                'section' => 'aqualuxe_blog',
            )
        )
    );
}
add_action('customize_register', 'aqualuxe_customize_register_blog');