<?php
/**
 * AquaLuxe Customizer Blog Section
 *
 * @package AquaLuxe
 * @subpackage Modules/Customizer
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Register blog section
 *
 * @param WP_Customize_Manager $wp_customize Customizer manager
 * @return void
 */
function aqualuxe_customizer_register_blog_section( $wp_customize ) {
    // Add section
    $wp_customize->add_section( 'aqualuxe_blog', array(
        'title' => __( 'Blog Settings', 'aqualuxe' ),
        'description' => __( 'Customize the blog section.', 'aqualuxe' ),
        'panel' => 'aqualuxe_theme_options',
        'priority' => 70,
    ) );
    
    // Blog layout
    $wp_customize->add_setting( 'aqualuxe_blog_layout', array(
        'default' => 'standard',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_blog_layout', array(
        'label' => __( 'Blog Layout', 'aqualuxe' ),
        'section' => 'aqualuxe_blog',
        'type' => 'select',
        'choices' => aqualuxe_get_blog_layouts(),
    ) );
    
    // Blog columns
    $wp_customize->add_setting( 'aqualuxe_blog_columns', array(
        'default' => '2',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_blog_columns', array(
        'label' => __( 'Blog Columns', 'aqualuxe' ),
        'section' => 'aqualuxe_blog',
        'type' => 'select',
        'choices' => array(
            '1' => __( '1 Column', 'aqualuxe' ),
            '2' => __( '2 Columns', 'aqualuxe' ),
            '3' => __( '3 Columns', 'aqualuxe' ),
            '4' => __( '4 Columns', 'aqualuxe' ),
        ),
    ) );
    
    // Blog sidebar
    $wp_customize->add_setting( 'aqualuxe_blog_sidebar', array(
        'default' => 'right',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_blog_sidebar', array(
        'label' => __( 'Blog Sidebar', 'aqualuxe' ),
        'section' => 'aqualuxe_blog',
        'type' => 'select',
        'choices' => aqualuxe_get_sidebar_positions(),
    ) );
    
    // Blog posts per page
    $wp_customize->add_setting( 'aqualuxe_blog_posts_per_page', array(
        'default' => '10',
        'sanitize_callback' => 'aqualuxe_sanitize_number',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_blog_posts_per_page', array(
        'label' => __( 'Posts Per Page', 'aqualuxe' ),
        'section' => 'aqualuxe_blog',
        'type' => 'number',
        'input_attrs' => array(
            'min' => 1,
            'max' => 50,
            'step' => 1,
        ),
    ) );
    
    // Blog pagination
    $wp_customize->add_setting( 'aqualuxe_blog_pagination', array(
        'default' => 'numbered',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_blog_pagination', array(
        'label' => __( 'Blog Pagination', 'aqualuxe' ),
        'section' => 'aqualuxe_blog',
        'type' => 'select',
        'choices' => array(
            'numbered' => __( 'Numbered', 'aqualuxe' ),
            'prev_next' => __( 'Previous / Next', 'aqualuxe' ),
            'load_more' => __( 'Load More', 'aqualuxe' ),
            'infinite' => __( 'Infinite Scroll', 'aqualuxe' ),
        ),
    ) );
    
    // Featured image
    $wp_customize->add_setting( 'aqualuxe_blog_featured_image', array(
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_blog_featured_image', array(
        'label' => __( 'Show Featured Image', 'aqualuxe' ),
        'section' => 'aqualuxe_blog',
        'type' => 'checkbox',
    ) );
    
    // Featured image size
    $wp_customize->add_setting( 'aqualuxe_featured_image_size', array(
        'default' => 'large',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_featured_image_size', array(
        'label' => __( 'Featured Image Size', 'aqualuxe' ),
        'section' => 'aqualuxe_blog',
        'type' => 'select',
        'choices' => aqualuxe_get_featured_image_sizes(),
    ) );
    
    // Post meta
    $wp_customize->add_setting( 'aqualuxe_blog_post_meta', array(
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_blog_post_meta', array(
        'label' => __( 'Show Post Meta', 'aqualuxe' ),
        'section' => 'aqualuxe_blog',
        'type' => 'checkbox',
    ) );
    
    // Post meta position
    $wp_customize->add_setting( 'aqualuxe_post_meta_position', array(
        'default' => 'below-title',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_post_meta_position', array(
        'label' => __( 'Post Meta Position', 'aqualuxe' ),
        'section' => 'aqualuxe_blog',
        'type' => 'select',
        'choices' => aqualuxe_get_post_meta_positions(),
    ) );
    
    // Post meta items
    $wp_customize->add_setting( 'aqualuxe_post_meta_items', array(
        'default' => array( 'author', 'date', 'categories', 'comments' ),
        'sanitize_callback' => 'aqualuxe_sanitize_sortable',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_post_meta_items', array(
        'label' => __( 'Post Meta Items', 'aqualuxe' ),
        'section' => 'aqualuxe_blog',
        'type' => 'select',
        'choices' => array(
            'author' => __( 'Author', 'aqualuxe' ),
            'date' => __( 'Date', 'aqualuxe' ),
            'categories' => __( 'Categories', 'aqualuxe' ),
            'tags' => __( 'Tags', 'aqualuxe' ),
            'comments' => __( 'Comments', 'aqualuxe' ),
        ),
        'multiple' => true,
    ) );
    
    // Post excerpt
    $wp_customize->add_setting( 'aqualuxe_blog_post_excerpt', array(
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_blog_post_excerpt', array(
        'label' => __( 'Show Post Excerpt', 'aqualuxe' ),
        'section' => 'aqualuxe_blog',
        'type' => 'checkbox',
    ) );
    
    // Excerpt length
    $wp_customize->add_setting( 'aqualuxe_excerpt_length', array(
        'default' => '55',
        'sanitize_callback' => 'aqualuxe_sanitize_number',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_excerpt_length', array(
        'label' => __( 'Excerpt Length', 'aqualuxe' ),
        'section' => 'aqualuxe_blog',
        'type' => 'number',
        'input_attrs' => array(
            'min' => 10,
            'max' => 200,
            'step' => 5,
        ),
    ) );
    
    // Read more text
    $wp_customize->add_setting( 'aqualuxe_read_more_text', array(
        'default' => __( 'Read More', 'aqualuxe' ),
        'sanitize_callback' => 'aqualuxe_sanitize_text',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_read_more_text', array(
        'label' => __( 'Read More Text', 'aqualuxe' ),
        'section' => 'aqualuxe_blog',
        'type' => 'text',
    ) );
    
    // Show read more button
    $wp_customize->add_setting( 'aqualuxe_read_more_button', array(
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_read_more_button', array(
        'label' => __( 'Show Read More Button', 'aqualuxe' ),
        'section' => 'aqualuxe_blog',
        'type' => 'checkbox',
    ) );
    
    // Single post featured image
    $wp_customize->add_setting( 'aqualuxe_single_featured_image', array(
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_single_featured_image', array(
        'label' => __( 'Show Featured Image on Single Post', 'aqualuxe' ),
        'section' => 'aqualuxe_blog',
        'type' => 'checkbox',
    ) );
    
    // Single post meta
    $wp_customize->add_setting( 'aqualuxe_single_post_meta', array(
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_single_post_meta', array(
        'label' => __( 'Show Post Meta on Single Post', 'aqualuxe' ),
        'section' => 'aqualuxe_blog',
        'type' => 'checkbox',
    ) );
    
    // Single post tags
    $wp_customize->add_setting( 'aqualuxe_single_post_tags', array(
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_single_post_tags', array(
        'label' => __( 'Show Tags on Single Post', 'aqualuxe' ),
        'section' => 'aqualuxe_blog',
        'type' => 'checkbox',
    ) );
    
    // Single post navigation
    $wp_customize->add_setting( 'aqualuxe_single_post_navigation', array(
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_single_post_navigation', array(
        'label' => __( 'Show Post Navigation on Single Post', 'aqualuxe' ),
        'section' => 'aqualuxe_blog',
        'type' => 'checkbox',
    ) );
    
    // Single post author box
    $wp_customize->add_setting( 'aqualuxe_single_post_author_box', array(
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_single_post_author_box', array(
        'label' => __( 'Show Author Box on Single Post', 'aqualuxe' ),
        'section' => 'aqualuxe_blog',
        'type' => 'checkbox',
    ) );
    
    // Single post related posts
    $wp_customize->add_setting( 'aqualuxe_single_post_related', array(
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_single_post_related', array(
        'label' => __( 'Show Related Posts on Single Post', 'aqualuxe' ),
        'section' => 'aqualuxe_blog',
        'type' => 'checkbox',
    ) );
    
    // Related posts count
    $wp_customize->add_setting( 'aqualuxe_related_posts_count', array(
        'default' => '3',
        'sanitize_callback' => 'aqualuxe_sanitize_number',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_related_posts_count', array(
        'label' => __( 'Related Posts Count', 'aqualuxe' ),
        'section' => 'aqualuxe_blog',
        'type' => 'number',
        'input_attrs' => array(
            'min' => 1,
            'max' => 12,
            'step' => 1,
        ),
    ) );
    
    // Related posts columns
    $wp_customize->add_setting( 'aqualuxe_related_posts_columns', array(
        'default' => '3',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_related_posts_columns', array(
        'label' => __( 'Related Posts Columns', 'aqualuxe' ),
        'section' => 'aqualuxe_blog',
        'type' => 'select',
        'choices' => array(
            '1' => __( '1 Column', 'aqualuxe' ),
            '2' => __( '2 Columns', 'aqualuxe' ),
            '3' => __( '3 Columns', 'aqualuxe' ),
            '4' => __( '4 Columns', 'aqualuxe' ),
        ),
    ) );
    
    // Related posts by
    $wp_customize->add_setting( 'aqualuxe_related_posts_by', array(
        'default' => 'category',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_related_posts_by', array(
        'label' => __( 'Related Posts By', 'aqualuxe' ),
        'section' => 'aqualuxe_blog',
        'type' => 'select',
        'choices' => array(
            'category' => __( 'Category', 'aqualuxe' ),
            'tag' => __( 'Tag', 'aqualuxe' ),
            'author' => __( 'Author', 'aqualuxe' ),
        ),
    ) );
    
    // Single post comments
    $wp_customize->add_setting( 'aqualuxe_single_post_comments', array(
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_single_post_comments', array(
        'label' => __( 'Show Comments on Single Post', 'aqualuxe' ),
        'section' => 'aqualuxe_blog',
        'type' => 'checkbox',
    ) );
    
    // Archive title
    $wp_customize->add_setting( 'aqualuxe_archive_title', array(
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_archive_title', array(
        'label' => __( 'Show Archive Title', 'aqualuxe' ),
        'section' => 'aqualuxe_blog',
        'type' => 'checkbox',
    ) );
    
    // Archive description
    $wp_customize->add_setting( 'aqualuxe_archive_description', array(
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_archive_description', array(
        'label' => __( 'Show Archive Description', 'aqualuxe' ),
        'section' => 'aqualuxe_blog',
        'type' => 'checkbox',
    ) );
    
    // Archive breadcrumbs
    $wp_customize->add_setting( 'aqualuxe_archive_breadcrumbs', array(
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_archive_breadcrumbs', array(
        'label' => __( 'Show Archive Breadcrumbs', 'aqualuxe' ),
        'section' => 'aqualuxe_blog',
        'type' => 'checkbox',
    ) );
}