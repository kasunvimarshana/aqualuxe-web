<?php
/**
 * AquaLuxe Theme Customizer - Layout Section
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Add layout settings to the Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_register_layout($wp_customize) {
    // Add Layout section
    $wp_customize->add_section('aqualuxe_layout', array(
        'title'    => __('Layout', 'aqualuxe'),
        'priority' => 50,
        'panel'    => 'aqualuxe_theme_options',
    ));

    // Container Width
    $wp_customize->add_setting('aqualuxe_container_width', array(
        'default'           => 1140,
        'transport'         => 'postMessage',
        'sanitize_callback' => 'absint',
    ));
    
    $wp_customize->add_control('aqualuxe_container_width', array(
        'label'       => __('Container Width (px)', 'aqualuxe'),
        'description' => __('Set the maximum width of the content container.', 'aqualuxe'),
        'section'     => 'aqualuxe_layout',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 800,
            'max'  => 1600,
            'step' => 10,
        ),
    ));
    
    // Container Type
    $wp_customize->add_setting('aqualuxe_container_type', array(
        'default'           => 'contained',
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ));
    
    $wp_customize->add_control('aqualuxe_container_type', array(
        'label'       => __('Container Type', 'aqualuxe'),
        'description' => __('Choose between a contained or full-width layout.', 'aqualuxe'),
        'section'     => 'aqualuxe_layout',
        'type'        => 'select',
        'choices'     => array(
            'contained'   => __('Contained', 'aqualuxe'),
            'full-width'  => __('Full Width', 'aqualuxe'),
            'boxed'       => __('Boxed', 'aqualuxe'),
        ),
    ));
    
    // Content Layout
    $wp_customize->add_setting('aqualuxe_content_layout', array(
        'default'           => 'right-sidebar',
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ));
    
    $wp_customize->add_control('aqualuxe_content_layout', array(
        'label'       => __('Content Layout', 'aqualuxe'),
        'description' => __('Choose the default layout for your content.', 'aqualuxe'),
        'section'     => 'aqualuxe_layout',
        'type'        => 'select',
        'choices'     => array(
            'right-sidebar' => __('Right Sidebar', 'aqualuxe'),
            'left-sidebar'  => __('Left Sidebar', 'aqualuxe'),
            'no-sidebar'    => __('No Sidebar', 'aqualuxe'),
        ),
    ));
    
    // Sidebar Width
    $wp_customize->add_setting('aqualuxe_sidebar_width', array(
        'default'           => 30,
        'transport'         => 'postMessage',
        'sanitize_callback' => 'absint',
    ));
    
    $wp_customize->add_control('aqualuxe_sidebar_width', array(
        'label'       => __('Sidebar Width (%)', 'aqualuxe'),
        'description' => __('Set the width of the sidebar as a percentage of the container.', 'aqualuxe'),
        'section'     => 'aqualuxe_layout',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 20,
            'max'  => 40,
            'step' => 1,
        ),
    ));
    
    // Page Layout
    $wp_customize->add_setting('aqualuxe_page_layout', array(
        'default'           => 'default',
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ));
    
    $wp_customize->add_control('aqualuxe_page_layout', array(
        'label'       => __('Page Layout', 'aqualuxe'),
        'description' => __('Choose the default layout for pages.', 'aqualuxe'),
        'section'     => 'aqualuxe_layout',
        'type'        => 'select',
        'choices'     => array(
            'default'       => __('Default', 'aqualuxe'),
            'right-sidebar' => __('Right Sidebar', 'aqualuxe'),
            'left-sidebar'  => __('Left Sidebar', 'aqualuxe'),
            'no-sidebar'    => __('No Sidebar', 'aqualuxe'),
        ),
    ));
    
    // Post Layout
    $wp_customize->add_setting('aqualuxe_post_layout', array(
        'default'           => 'default',
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ));
    
    $wp_customize->add_control('aqualuxe_post_layout', array(
        'label'       => __('Post Layout', 'aqualuxe'),
        'description' => __('Choose the default layout for single posts.', 'aqualuxe'),
        'section'     => 'aqualuxe_layout',
        'type'        => 'select',
        'choices'     => array(
            'default'       => __('Default', 'aqualuxe'),
            'right-sidebar' => __('Right Sidebar', 'aqualuxe'),
            'left-sidebar'  => __('Left Sidebar', 'aqualuxe'),
            'no-sidebar'    => __('No Sidebar', 'aqualuxe'),
        ),
    ));
    
    // Archive Layout
    $wp_customize->add_setting('aqualuxe_archive_layout', array(
        'default'           => 'default',
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ));
    
    $wp_customize->add_control('aqualuxe_archive_layout', array(
        'label'       => __('Archive Layout', 'aqualuxe'),
        'description' => __('Choose the default layout for archives.', 'aqualuxe'),
        'section'     => 'aqualuxe_layout',
        'type'        => 'select',
        'choices'     => array(
            'default'       => __('Default', 'aqualuxe'),
            'right-sidebar' => __('Right Sidebar', 'aqualuxe'),
            'left-sidebar'  => __('Left Sidebar', 'aqualuxe'),
            'no-sidebar'    => __('No Sidebar', 'aqualuxe'),
        ),
    ));
    
    // Blog Layout Style
    $wp_customize->add_setting('aqualuxe_blog_style', array(
        'default'           => 'standard',
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ));
    
    $wp_customize->add_control('aqualuxe_blog_style', array(
        'label'       => __('Blog Layout Style', 'aqualuxe'),
        'description' => __('Choose the style for blog posts on archive pages.', 'aqualuxe'),
        'section'     => 'aqualuxe_layout',
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
        'section'     => 'aqualuxe_layout',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 1,
            'max'  => 4,
            'step' => 1,
        ),
        'active_callback' => function() {
            $blog_style = get_theme_mod('aqualuxe_blog_style', 'standard');
            return ($blog_style === 'grid' || $blog_style === 'masonry');
        },
    ));
    
    // Content Padding
    $wp_customize->add_setting('aqualuxe_content_padding', array(
        'default'           => 40,
        'transport'         => 'postMessage',
        'sanitize_callback' => 'absint',
    ));
    
    $wp_customize->add_control('aqualuxe_content_padding', array(
        'label'       => __('Content Padding (px)', 'aqualuxe'),
        'description' => __('Set the padding around the main content area.', 'aqualuxe'),
        'section'     => 'aqualuxe_layout',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 0,
            'max'  => 100,
            'step' => 5,
        ),
    ));
    
    // Boxed Layout Width
    $wp_customize->add_setting('aqualuxe_boxed_width', array(
        'default'           => 1200,
        'transport'         => 'postMessage',
        'sanitize_callback' => 'absint',
    ));
    
    $wp_customize->add_control('aqualuxe_boxed_width', array(
        'label'       => __('Boxed Layout Width (px)', 'aqualuxe'),
        'description' => __('Set the width of the boxed layout.', 'aqualuxe'),
        'section'     => 'aqualuxe_layout',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 800,
            'max'  => 1600,
            'step' => 10,
        ),
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_container_type', 'contained') === 'boxed';
        },
    ));
    
    // Boxed Layout Margin
    $wp_customize->add_setting('aqualuxe_boxed_margin', array(
        'default'           => 20,
        'transport'         => 'postMessage',
        'sanitize_callback' => 'absint',
    ));
    
    $wp_customize->add_control('aqualuxe_boxed_margin', array(
        'label'       => __('Boxed Layout Margin (px)', 'aqualuxe'),
        'description' => __('Set the margin around the boxed layout.', 'aqualuxe'),
        'section'     => 'aqualuxe_layout',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 0,
            'max'  => 100,
            'step' => 5,
        ),
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_container_type', 'contained') === 'boxed';
        },
    ));
    
    // Boxed Layout Shadow
    $wp_customize->add_setting('aqualuxe_boxed_shadow', array(
        'default'           => true,
        'transport'         => 'postMessage',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));
    
    $wp_customize->add_control('aqualuxe_boxed_shadow', array(
        'label'       => __('Boxed Layout Shadow', 'aqualuxe'),
        'description' => __('Add a shadow to the boxed layout.', 'aqualuxe'),
        'section'     => 'aqualuxe_layout',
        'type'        => 'checkbox',
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_container_type', 'contained') === 'boxed';
        },
    ));
    
    // Responsive Breakpoints
    $wp_customize->add_setting('aqualuxe_breakpoint_mobile', array(
        'default'           => 576,
        'transport'         => 'refresh',
        'sanitize_callback' => 'absint',
    ));
    
    $wp_customize->add_control('aqualuxe_breakpoint_mobile', array(
        'label'       => __('Mobile Breakpoint (px)', 'aqualuxe'),
        'description' => __('Set the breakpoint for mobile devices.', 'aqualuxe'),
        'section'     => 'aqualuxe_layout',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 320,
            'max'  => 767,
            'step' => 1,
        ),
    ));
    
    $wp_customize->add_setting('aqualuxe_breakpoint_tablet', array(
        'default'           => 768,
        'transport'         => 'refresh',
        'sanitize_callback' => 'absint',
    ));
    
    $wp_customize->add_control('aqualuxe_breakpoint_tablet', array(
        'label'       => __('Tablet Breakpoint (px)', 'aqualuxe'),
        'description' => __('Set the breakpoint for tablet devices.', 'aqualuxe'),
        'section'     => 'aqualuxe_layout',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 768,
            'max'  => 991,
            'step' => 1,
        ),
    ));
    
    $wp_customize->add_setting('aqualuxe_breakpoint_desktop', array(
        'default'           => 992,
        'transport'         => 'refresh',
        'sanitize_callback' => 'absint',
    ));
    
    $wp_customize->add_control('aqualuxe_breakpoint_desktop', array(
        'label'       => __('Desktop Breakpoint (px)', 'aqualuxe'),
        'description' => __('Set the breakpoint for desktop devices.', 'aqualuxe'),
        'section'     => 'aqualuxe_layout',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 992,
            'max'  => 1199,
            'step' => 1,
        ),
    ));
    
    $wp_customize->add_setting('aqualuxe_breakpoint_large', array(
        'default'           => 1200,
        'transport'         => 'refresh',
        'sanitize_callback' => 'absint',
    ));
    
    $wp_customize->add_control('aqualuxe_breakpoint_large', array(
        'label'       => __('Large Desktop Breakpoint (px)', 'aqualuxe'),
        'description' => __('Set the breakpoint for large desktop devices.', 'aqualuxe'),
        'section'     => 'aqualuxe_layout',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 1200,
            'max'  => 1600,
            'step' => 1,
        ),
    ));
}

// Add the layout section to the customizer
add_action('customize_register', 'aqualuxe_customize_register_layout');

/**
 * Sanitize checkbox value.
 *
 * @param bool $checked Whether the checkbox is checked.
 * @return bool Whether the checkbox is checked.
 */
function aqualuxe_sanitize_checkbox($checked) {
    return (isset($checked) && true === $checked) ? true : false;
}

/**
 * Add layout CSS to the head.
 */
function aqualuxe_layout_css() {
    $container_width = get_theme_mod('aqualuxe_container_width', 1140);
    $container_type = get_theme_mod('aqualuxe_container_type', 'contained');
    $sidebar_width = get_theme_mod('aqualuxe_sidebar_width', 30);
    $content_width = 100 - $sidebar_width;
    $content_padding = get_theme_mod('aqualuxe_content_padding', 40);
    $boxed_width = get_theme_mod('aqualuxe_boxed_width', 1200);
    $boxed_margin = get_theme_mod('aqualuxe_boxed_margin', 20);
    $boxed_shadow = get_theme_mod('aqualuxe_boxed_shadow', true);
    $breakpoint_mobile = get_theme_mod('aqualuxe_breakpoint_mobile', 576);
    $breakpoint_tablet = get_theme_mod('aqualuxe_breakpoint_tablet', 768);
    $breakpoint_desktop = get_theme_mod('aqualuxe_breakpoint_desktop', 992);
    $breakpoint_large = get_theme_mod('aqualuxe_breakpoint_large', 1200);
    
    ?>
    <style type="text/css">
        :root {
            --aqualuxe-container-width: <?php echo esc_attr($container_width); ?>px;
            --aqualuxe-sidebar-width: <?php echo esc_attr($sidebar_width); ?>%;
            --aqualuxe-content-width: <?php echo esc_attr($content_width); ?>%;
            --aqualuxe-content-padding: <?php echo esc_attr($content_padding); ?>px;
            --aqualuxe-boxed-width: <?php echo esc_attr($boxed_width); ?>px;
            --aqualuxe-boxed-margin: <?php echo esc_attr($boxed_margin); ?>px;
            --aqualuxe-breakpoint-mobile: <?php echo esc_attr($breakpoint_mobile); ?>px;
            --aqualuxe-breakpoint-tablet: <?php echo esc_attr($breakpoint_tablet); ?>px;
            --aqualuxe-breakpoint-desktop: <?php echo esc_attr($breakpoint_desktop); ?>px;
            --aqualuxe-breakpoint-large: <?php echo esc_attr($breakpoint_large); ?>px;
        }
        
        /* Container */
        .container {
            width: 100%;
            max-width: var(--aqualuxe-container-width);
            margin-left: auto;
            margin-right: auto;
            padding-left: 15px;
            padding-right: 15px;
        }
        
        /* Full Width Container */
        <?php if ($container_type === 'full-width') : ?>
        .container {
            max-width: 100%;
            padding-left: 30px;
            padding-right: 30px;
        }
        <?php endif; ?>
        
        /* Boxed Layout */
        <?php if ($container_type === 'boxed') : ?>
        body {
            background-color: #f5f5f5;
        }
        
        #page {
            max-width: var(--aqualuxe-boxed-width);
            margin-left: auto;
            margin-right: auto;
            margin-top: var(--aqualuxe-boxed-margin);
            margin-bottom: var(--aqualuxe-boxed-margin);
            background-color: var(--aqualuxe-content-background-color);
            <?php if ($boxed_shadow) : ?>
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            <?php endif; ?>
        }
        <?php endif; ?>
        
        /* Content Area */
        .site-content {
            padding: var(--aqualuxe-content-padding) 0;
        }
        
        /* Sidebar Layouts */
        .content-area {
            width: 100%;
        }
        
        .sidebar {
            width: 100%;
        }
        
        @media (min-width: <?php echo esc_attr($breakpoint_tablet); ?>px) {
            .has-sidebar .content-area {
                width: var(--aqualuxe-content-width);
                float: left;
            }
            
            .has-sidebar .sidebar {
                width: var(--aqualuxe-sidebar-width);
                float: right;
            }
            
            .has-left-sidebar .content-area {
                float: right;
            }
            
            .has-left-sidebar .sidebar {
                float: left;
            }
            
            .no-sidebar .content-area {
                width: 100%;
                float: none;
            }
        }
        
        /* Blog Grid Layout */
        .blog-layout-grid .posts-grid,
        .blog-layout-masonry .posts-grid {
            display: grid;
            grid-gap: 30px;
        }
        
        @media (min-width: <?php echo esc_attr($breakpoint_mobile); ?>px) {
            .blog-layout-grid .posts-grid,
            .blog-layout-masonry .posts-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        @media (min-width: <?php echo esc_attr($breakpoint_desktop); ?>px) {
            .blog-columns-3 .posts-grid {
                grid-template-columns: repeat(3, 1fr);
            }
            
            .blog-columns-4 .posts-grid {
                grid-template-columns: repeat(4, 1fr);
            }
        }
        
        /* Blog List Layout */
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
        
        @media (max-width: <?php echo esc_attr($breakpoint_mobile - 1); ?>px) {
            .blog-layout-list .post {
                flex-direction: column;
            }
            
            .blog-layout-list .post-thumbnail {
                margin-right: 0;
                margin-bottom: 20px;
            }
        }
        
        /* Clearfix */
        .clearfix::after {
            content: "";
            display: table;
            clear: both;
        }
    </style>
    <?php
}
add_action('wp_head', 'aqualuxe_layout_css');

/**
 * Add layout classes to body.
 *
 * @param array $classes Body classes.
 * @return array Modified body classes.
 */
function aqualuxe_layout_body_classes($classes) {
    $container_type = get_theme_mod('aqualuxe_container_type', 'contained');
    $content_layout = get_theme_mod('aqualuxe_content_layout', 'right-sidebar');
    $blog_style = get_theme_mod('aqualuxe_blog_style', 'standard');
    $blog_columns = get_theme_mod('aqualuxe_blog_columns', 2);
    
    // Container type
    $classes[] = 'container-' . $container_type;
    
    // Content layout
    if (is_page()) {
        $page_layout = get_theme_mod('aqualuxe_page_layout', 'default');
        if ($page_layout !== 'default') {
            $content_layout = $page_layout;
        }
    } elseif (is_single()) {
        $post_layout = get_theme_mod('aqualuxe_post_layout', 'default');
        if ($post_layout !== 'default') {
            $content_layout = $post_layout;
        }
    } elseif (is_archive() || is_home() || is_search()) {
        $archive_layout = get_theme_mod('aqualuxe_archive_layout', 'default');
        if ($archive_layout !== 'default') {
            $content_layout = $archive_layout;
        }
    }
    
    if ($content_layout === 'right-sidebar') {
        $classes[] = 'has-sidebar';
        $classes[] = 'has-right-sidebar';
    } elseif ($content_layout === 'left-sidebar') {
        $classes[] = 'has-sidebar';
        $classes[] = 'has-left-sidebar';
    } else {
        $classes[] = 'no-sidebar';
    }
    
    // Blog style
    if (is_home() || is_archive() || is_search()) {
        $classes[] = 'blog-layout-' . $blog_style;
        if ($blog_style === 'grid' || $blog_style === 'masonry') {
            $classes[] = 'blog-columns-' . $blog_columns;
        }
    }
    
    return $classes;
}
add_filter('body_class', 'aqualuxe_layout_body_classes');

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function aqualuxe_layout_customize_preview_js() {
    wp_add_inline_script('aqualuxe-customizer', '
        // Container width
        wp.customize("aqualuxe_container_width", function(value) {
            value.bind(function(to) {
                $(":root").css("--aqualuxe-container-width", to + "px");
            });
        });
        
        // Sidebar width
        wp.customize("aqualuxe_sidebar_width", function(value) {
            value.bind(function(to) {
                var contentWidth = 100 - to;
                $(":root").css("--aqualuxe-sidebar-width", to + "%");
                $(":root").css("--aqualuxe-content-width", contentWidth + "%");
            });
        });
        
        // Content padding
        wp.customize("aqualuxe_content_padding", function(value) {
            value.bind(function(to) {
                $(":root").css("--aqualuxe-content-padding", to + "px");
                $(".site-content").css("padding-top", to + "px");
                $(".site-content").css("padding-bottom", to + "px");
            });
        });
        
        // Boxed width
        wp.customize("aqualuxe_boxed_width", function(value) {
            value.bind(function(to) {
                $(":root").css("--aqualuxe-boxed-width", to + "px");
                $("#page").css("max-width", to + "px");
            });
        });
        
        // Boxed margin
        wp.customize("aqualuxe_boxed_margin", function(value) {
            value.bind(function(to) {
                $(":root").css("--aqualuxe-boxed-margin", to + "px");
                $("#page").css("margin-top", to + "px");
                $("#page").css("margin-bottom", to + "px");
            });
        });
        
        // Boxed shadow
        wp.customize("aqualuxe_boxed_shadow", function(value) {
            value.bind(function(to) {
                if (to) {
                    $("#page").css("box-shadow", "0 0 20px rgba(0, 0, 0, 0.1)");
                } else {
                    $("#page").css("box-shadow", "none");
                }
            });
        });
    ');
}
add_action('customize_preview_init', 'aqualuxe_layout_customize_preview_js', 20);

/**
 * Get container class based on theme options.
 *
 * @return string Container class.
 */
function aqualuxe_get_container_class() {
    $container_type = get_theme_mod('aqualuxe_container_type', 'contained');
    
    if ($container_type === 'full-width') {
        return 'container-fluid';
    }
    
    return 'container';
}

/**
 * Get content class based on theme options.
 *
 * @param string $sidebar_position Optional. Sidebar position. Default empty.
 * @return string Content class.
 */
function aqualuxe_get_content_class($sidebar_position = '') {
    $content_layout = get_theme_mod('aqualuxe_content_layout', 'right-sidebar');
    
    if (is_page()) {
        $page_layout = get_theme_mod('aqualuxe_page_layout', 'default');
        if ($page_layout !== 'default') {
            $content_layout = $page_layout;
        }
    } elseif (is_single()) {
        $post_layout = get_theme_mod('aqualuxe_post_layout', 'default');
        if ($post_layout !== 'default') {
            $content_layout = $post_layout;
        }
    } elseif (is_archive() || is_home() || is_search()) {
        $archive_layout = get_theme_mod('aqualuxe_archive_layout', 'default');
        if ($archive_layout !== 'default') {
            $content_layout = $archive_layout;
        }
    }
    
    if ($sidebar_position === 'left') {
        return 'content-area';
    } elseif ($sidebar_position === 'right') {
        return 'content-area';
    } elseif ($content_layout === 'no-sidebar') {
        return 'content-area full-width';
    } else {
        return 'content-area';
    }
}

/**
 * Get sidebar class based on theme options.
 *
 * @return string Sidebar class.
 */
function aqualuxe_get_sidebar_class() {
    return 'sidebar widget-area';
}

/**
 * Check if sidebar should be displayed.
 *
 * @return bool Whether to display sidebar.
 */
function aqualuxe_has_sidebar() {
    $content_layout = get_theme_mod('aqualuxe_content_layout', 'right-sidebar');
    
    if (is_page()) {
        $page_layout = get_theme_mod('aqualuxe_page_layout', 'default');
        if ($page_layout !== 'default') {
            $content_layout = $page_layout;
        }
    } elseif (is_single()) {
        $post_layout = get_theme_mod('aqualuxe_post_layout', 'default');
        if ($post_layout !== 'default') {
            $content_layout = $post_layout;
        }
    } elseif (is_archive() || is_home() || is_search()) {
        $archive_layout = get_theme_mod('aqualuxe_archive_layout', 'default');
        if ($archive_layout !== 'default') {
            $content_layout = $archive_layout;
        }
    }
    
    return $content_layout !== 'no-sidebar';
}

/**
 * Get archive layout.
 *
 * @return string Archive layout.
 */
function aqualuxe_get_archive_layout() {
    return get_theme_mod('aqualuxe_blog_style', 'standard');
}