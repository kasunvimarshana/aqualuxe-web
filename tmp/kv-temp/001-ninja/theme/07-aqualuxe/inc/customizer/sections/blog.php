<?php
/**
 * Blog Customizer Settings
 *
 * @package AquaLuxe
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Add blog settings to the Customizer
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_register_blog($wp_customize) {
    // Add Blog section
    $wp_customize->add_section('aqualuxe_blog', array(
        'title' => esc_html__('Blog Settings', 'aqualuxe'),
        'priority' => 60,
    ));

    // Blog Layout
    $wp_customize->add_setting('aqualuxe_blog_layout', array(
        'default' => 'grid',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Radio_Image($wp_customize, 'aqualuxe_blog_layout', array(
        'label' => esc_html__('Blog Layout', 'aqualuxe'),
        'description' => esc_html__('Select the layout for the blog archive pages.', 'aqualuxe'),
        'section' => 'aqualuxe_blog',
        'priority' => 10,
        'choices' => array(
            'grid' => array(
                'label' => esc_html__('Grid', 'aqualuxe'),
                'image' => AQUALUXE_URI . '/assets/images/customizer/blog-grid.png',
            ),
            'list' => array(
                'label' => esc_html__('List', 'aqualuxe'),
                'image' => AQUALUXE_URI . '/assets/images/customizer/blog-list.png',
            ),
            'masonry' => array(
                'label' => esc_html__('Masonry', 'aqualuxe'),
                'image' => AQUALUXE_URI . '/assets/images/customizer/blog-masonry.png',
            ),
            'classic' => array(
                'label' => esc_html__('Classic', 'aqualuxe'),
                'image' => AQUALUXE_URI . '/assets/images/customizer/blog-classic.png',
            ),
        ),
    )));

    // Blog Sidebar Position
    $wp_customize->add_setting('aqualuxe_blog_sidebar', array(
        'default' => 'right',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Radio_Image($wp_customize, 'aqualuxe_blog_sidebar', array(
        'label' => esc_html__('Sidebar Position', 'aqualuxe'),
        'description' => esc_html__('Select the sidebar position for blog pages.', 'aqualuxe'),
        'section' => 'aqualuxe_blog',
        'priority' => 20,
        'choices' => array(
            'right' => array(
                'label' => esc_html__('Right', 'aqualuxe'),
                'image' => AQUALUXE_URI . '/assets/images/customizer/sidebar-right.png',
            ),
            'left' => array(
                'label' => esc_html__('Left', 'aqualuxe'),
                'image' => AQUALUXE_URI . '/assets/images/customizer/sidebar-left.png',
            ),
            'none' => array(
                'label' => esc_html__('None', 'aqualuxe'),
                'image' => AQUALUXE_URI . '/assets/images/customizer/sidebar-none.png',
            ),
        ),
    )));

    // Blog Columns
    $wp_customize->add_setting('aqualuxe_blog_columns', array(
        'default' => 3,
        'sanitize_callback' => 'absint',
    ));

    $wp_customize->add_control('aqualuxe_blog_columns', array(
        'label' => esc_html__('Grid/Masonry Columns', 'aqualuxe'),
        'description' => esc_html__('Select the number of columns for grid or masonry layouts.', 'aqualuxe'),
        'section' => 'aqualuxe_blog',
        'type' => 'select',
        'choices' => array(
            2 => esc_html__('2 Columns', 'aqualuxe'),
            3 => esc_html__('3 Columns', 'aqualuxe'),
            4 => esc_html__('4 Columns', 'aqualuxe'),
        ),
        'priority' => 30,
        'active_callback' => function() {
            $layout = get_theme_mod('aqualuxe_blog_layout', 'grid');
            return ($layout === 'grid' || $layout === 'masonry');
        },
    ));

    // Posts Per Page
    $wp_customize->add_setting('aqualuxe_blog_posts_per_page', array(
        'default' => get_option('posts_per_page'),
        'sanitize_callback' => 'absint',
    ));

    $wp_customize->add_control('aqualuxe_blog_posts_per_page', array(
        'label' => esc_html__('Posts Per Page', 'aqualuxe'),
        'description' => esc_html__('Set the number of posts to display per page.', 'aqualuxe'),
        'section' => 'aqualuxe_blog',
        'type' => 'number',
        'input_attrs' => array(
            'min' => 1,
            'max' => 50,
            'step' => 1,
        ),
        'priority' => 40,
    ));

    // Excerpt Length
    $wp_customize->add_setting('aqualuxe_excerpt_length', array(
        'default' => 55,
        'sanitize_callback' => 'absint',
    ));

    $wp_customize->add_control('aqualuxe_excerpt_length', array(
        'label' => esc_html__('Excerpt Length', 'aqualuxe'),
        'description' => esc_html__('Set the number of words in post excerpts.', 'aqualuxe'),
        'section' => 'aqualuxe_blog',
        'type' => 'number',
        'input_attrs' => array(
            'min' => 10,
            'max' => 200,
            'step' => 5,
        ),
        'priority' => 50,
    ));

    // Show Featured Image
    $wp_customize->add_setting('aqualuxe_show_featured_image', array(
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Toggle($wp_customize, 'aqualuxe_show_featured_image', array(
        'label' => esc_html__('Show Featured Image', 'aqualuxe'),
        'description' => esc_html__('Display the featured image on single posts.', 'aqualuxe'),
        'section' => 'aqualuxe_blog',
        'priority' => 60,
    )));

    // Show Post Meta
    $wp_customize->add_setting('aqualuxe_show_post_meta', array(
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Toggle($wp_customize, 'aqualuxe_show_post_meta', array(
        'label' => esc_html__('Show Post Meta', 'aqualuxe'),
        'description' => esc_html__('Display post meta information (date, author, categories).', 'aqualuxe'),
        'section' => 'aqualuxe_blog',
        'priority' => 70,
    )));

    // Post Meta Elements
    $wp_customize->add_setting('aqualuxe_post_meta_elements', array(
        'default' => array('date', 'author', 'categories', 'comments'),
        'sanitize_callback' => 'aqualuxe_sanitize_multi_select',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Sortable($wp_customize, 'aqualuxe_post_meta_elements', array(
        'label' => esc_html__('Post Meta Elements', 'aqualuxe'),
        'description' => esc_html__('Select and arrange the meta elements to display.', 'aqualuxe'),
        'section' => 'aqualuxe_blog',
        'priority' => 80,
        'choices' => array(
            'date' => esc_html__('Date', 'aqualuxe'),
            'author' => esc_html__('Author', 'aqualuxe'),
            'categories' => esc_html__('Categories', 'aqualuxe'),
            'comments' => esc_html__('Comments', 'aqualuxe'),
            'tags' => esc_html__('Tags', 'aqualuxe'),
        ),
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_show_post_meta', true);
        },
    )));

    // Show Author Bio
    $wp_customize->add_setting('aqualuxe_show_author_bio', array(
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Toggle($wp_customize, 'aqualuxe_show_author_bio', array(
        'label' => esc_html__('Show Author Bio', 'aqualuxe'),
        'description' => esc_html__('Display the author biography on single posts.', 'aqualuxe'),
        'section' => 'aqualuxe_blog',
        'priority' => 90,
    )));

    // Show Post Navigation
    $wp_customize->add_setting('aqualuxe_show_post_navigation', array(
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Toggle($wp_customize, 'aqualuxe_show_post_navigation', array(
        'label' => esc_html__('Show Post Navigation', 'aqualuxe'),
        'description' => esc_html__('Display previous/next post navigation on single posts.', 'aqualuxe'),
        'section' => 'aqualuxe_blog',
        'priority' => 100,
    )));

    // Show Related Posts
    $wp_customize->add_setting('aqualuxe_show_related_posts', array(
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Toggle($wp_customize, 'aqualuxe_show_related_posts', array(
        'label' => esc_html__('Show Related Posts', 'aqualuxe'),
        'description' => esc_html__('Display related posts on single posts.', 'aqualuxe'),
        'section' => 'aqualuxe_blog',
        'priority' => 110,
    )));

    // Related Posts Count
    $wp_customize->add_setting('aqualuxe_related_posts_count', array(
        'default' => 3,
        'sanitize_callback' => 'absint',
    ));

    $wp_customize->add_control('aqualuxe_related_posts_count', array(
        'label' => esc_html__('Related Posts Count', 'aqualuxe'),
        'description' => esc_html__('Set the number of related posts to display.', 'aqualuxe'),
        'section' => 'aqualuxe_blog',
        'type' => 'number',
        'input_attrs' => array(
            'min' => 1,
            'max' => 12,
            'step' => 1,
        ),
        'priority' => 120,
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_show_related_posts', true);
        },
    ));

    // Related Posts Criteria
    $wp_customize->add_setting('aqualuxe_related_posts_criteria', array(
        'default' => 'category',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ));

    $wp_customize->add_control('aqualuxe_related_posts_criteria', array(
        'label' => esc_html__('Related Posts Criteria', 'aqualuxe'),
        'description' => esc_html__('Select how to determine related posts.', 'aqualuxe'),
        'section' => 'aqualuxe_blog',
        'type' => 'select',
        'choices' => array(
            'category' => esc_html__('Same Category', 'aqualuxe'),
            'tag' => esc_html__('Same Tag', 'aqualuxe'),
            'author' => esc_html__('Same Author', 'aqualuxe'),
        ),
        'priority' => 130,
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_show_related_posts', true);
        },
    ));

    // Show Social Sharing
    $wp_customize->add_setting('aqualuxe_show_social_sharing', array(
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Toggle($wp_customize, 'aqualuxe_show_social_sharing', array(
        'label' => esc_html__('Show Social Sharing', 'aqualuxe'),
        'description' => esc_html__('Display social sharing buttons on single posts.', 'aqualuxe'),
        'section' => 'aqualuxe_blog',
        'priority' => 140,
    )));

    // Social Sharing Networks
    $wp_customize->add_setting('aqualuxe_social_sharing_networks', array(
        'default' => array('facebook', 'twitter', 'pinterest', 'linkedin', 'email'),
        'sanitize_callback' => 'aqualuxe_sanitize_multi_select',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Sortable($wp_customize, 'aqualuxe_social_sharing_networks', array(
        'label' => esc_html__('Social Sharing Networks', 'aqualuxe'),
        'description' => esc_html__('Select and arrange the social networks for sharing.', 'aqualuxe'),
        'section' => 'aqualuxe_blog',
        'priority' => 150,
        'choices' => array(
            'facebook' => esc_html__('Facebook', 'aqualuxe'),
            'twitter' => esc_html__('Twitter', 'aqualuxe'),
            'pinterest' => esc_html__('Pinterest', 'aqualuxe'),
            'linkedin' => esc_html__('LinkedIn', 'aqualuxe'),
            'reddit' => esc_html__('Reddit', 'aqualuxe'),
            'tumblr' => esc_html__('Tumblr', 'aqualuxe'),
            'whatsapp' => esc_html__('WhatsApp', 'aqualuxe'),
            'telegram' => esc_html__('Telegram', 'aqualuxe'),
            'email' => esc_html__('Email', 'aqualuxe'),
        ),
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_show_social_sharing', true);
        },
    )));

    // Archive Title Style
    $wp_customize->add_setting('aqualuxe_archive_title_style', array(
        'default' => 'default',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ));

    $wp_customize->add_control('aqualuxe_archive_title_style', array(
        'label' => esc_html__('Archive Title Style', 'aqualuxe'),
        'description' => esc_html__('Select the style for archive page titles.', 'aqualuxe'),
        'section' => 'aqualuxe_blog',
        'type' => 'select',
        'choices' => array(
            'default' => esc_html__('Default', 'aqualuxe'),
            'minimal' => esc_html__('Minimal', 'aqualuxe'),
            'centered' => esc_html__('Centered', 'aqualuxe'),
            'banner' => esc_html__('Banner', 'aqualuxe'),
        ),
        'priority' => 160,
    ));

    // Read More Text
    $wp_customize->add_setting('aqualuxe_read_more_text', array(
        'default' => esc_html__('Read More', 'aqualuxe'),
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('aqualuxe_read_more_text', array(
        'label' => esc_html__('Read More Text', 'aqualuxe'),
        'description' => esc_html__('Set the text for the read more link.', 'aqualuxe'),
        'section' => 'aqualuxe_blog',
        'type' => 'text',
        'priority' => 170,
    ));
}
add_action('customize_register', 'aqualuxe_customize_register_blog');