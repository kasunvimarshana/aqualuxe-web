<?php
/**
 * AquaLuxe Theme Customizer - Blog Settings
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add blog settings to the Customizer
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_blog_settings($wp_customize) {
    // Blog Section
    $wp_customize->add_section(
        'aqualuxe_blog_section',
        array(
            'title'       => __('Blog Settings', 'aqualuxe'),
            'description' => __('Configure the blog and post display settings.', 'aqualuxe'),
            'priority'    => 50,
        )
    );

    // Featured Image
    $wp_customize->add_setting(
        'aqualuxe_options[show_featured_image]',
        array(
            'default'           => true,
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'transport'         => 'refresh',
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_options[show_featured_image]',
        array(
            'label'       => __('Show Featured Image', 'aqualuxe'),
            'description' => __('Display featured image on single posts.', 'aqualuxe'),
            'section'     => 'aqualuxe_blog_section',
            'type'        => 'checkbox',
        )
    );

    // Featured Image Style
    $wp_customize->add_setting(
        'aqualuxe_options[featured_image_style]',
        array(
            'default'           => 'large',
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'transport'         => 'refresh',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_options[featured_image_style]',
        array(
            'label'       => __('Featured Image Style', 'aqualuxe'),
            'description' => __('Choose how to display the featured image on single posts.', 'aqualuxe'),
            'section'     => 'aqualuxe_blog_section',
            'type'        => 'select',
            'choices'     => array(
                'large'      => __('Large (Full Width)', 'aqualuxe'),
                'medium'     => __('Medium (Content Width)', 'aqualuxe'),
                'banner'     => __('Banner (Full Width with Overlay)', 'aqualuxe'),
                'background' => __('Background (Full Screen)', 'aqualuxe'),
            ),
            'active_callback' => function() {
                $options = get_option('aqualuxe_options', array());
                return isset($options['show_featured_image']) ? $options['show_featured_image'] : true;
            },
        )
    );

    // Post Meta
    $wp_customize->add_setting(
        'aqualuxe_options[show_post_meta]',
        array(
            'default'           => true,
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'transport'         => 'refresh',
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_options[show_post_meta]',
        array(
            'label'       => __('Show Post Meta', 'aqualuxe'),
            'description' => __('Display post meta information (date, author, etc.).', 'aqualuxe'),
            'section'     => 'aqualuxe_blog_section',
            'type'        => 'checkbox',
        )
    );

    // Post Meta Elements
    $wp_customize->add_setting(
        'aqualuxe_options[post_meta_elements]',
        array(
            'default'           => array('date', 'author', 'categories', 'comments'),
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'transport'         => 'refresh',
            'sanitize_callback' => 'aqualuxe_sanitize_multi_select',
        )
    );

    $wp_customize->add_control(
        new AquaLuxe_Customize_Multi_Checkbox_Control(
            $wp_customize,
            'aqualuxe_options[post_meta_elements]',
            array(
                'label'       => __('Post Meta Elements', 'aqualuxe'),
                'description' => __('Select which meta elements to display.', 'aqualuxe'),
                'section'     => 'aqualuxe_blog_section',
                'choices'     => array(
                    'date'       => __('Date', 'aqualuxe'),
                    'author'     => __('Author', 'aqualuxe'),
                    'categories' => __('Categories', 'aqualuxe'),
                    'tags'       => __('Tags', 'aqualuxe'),
                    'comments'   => __('Comments Count', 'aqualuxe'),
                    'read_time'  => __('Reading Time', 'aqualuxe'),
                ),
                'active_callback' => function() {
                    $options = get_option('aqualuxe_options', array());
                    return isset($options['show_post_meta']) ? $options['show_post_meta'] : true;
                },
            )
        )
    );

    // Excerpt Length
    $wp_customize->add_setting(
        'aqualuxe_options[excerpt_length]',
        array(
            'default'           => 55,
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'transport'         => 'refresh',
            'sanitize_callback' => 'absint',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_options[excerpt_length]',
        array(
            'label'       => __('Excerpt Length', 'aqualuxe'),
            'description' => __('Number of words in post excerpts.', 'aqualuxe'),
            'section'     => 'aqualuxe_blog_section',
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
        'aqualuxe_options[read_more_text]',
        array(
            'default'           => __('Read More', 'aqualuxe'),
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'transport'         => 'refresh',
            'sanitize_callback' => 'sanitize_text_field',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_options[read_more_text]',
        array(
            'label'       => __('Read More Text', 'aqualuxe'),
            'description' => __('Text for the read more link.', 'aqualuxe'),
            'section'     => 'aqualuxe_blog_section',
            'type'        => 'text',
        )
    );

    // Show Author Bio
    $wp_customize->add_setting(
        'aqualuxe_options[show_author_bio]',
        array(
            'default'           => true,
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'transport'         => 'refresh',
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_options[show_author_bio]',
        array(
            'label'       => __('Show Author Bio', 'aqualuxe'),
            'description' => __('Display author biography at the end of posts.', 'aqualuxe'),
            'section'     => 'aqualuxe_blog_section',
            'type'        => 'checkbox',
        )
    );

    // Show Related Posts
    $wp_customize->add_setting(
        'aqualuxe_options[show_related_posts]',
        array(
            'default'           => true,
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'transport'         => 'refresh',
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_options[show_related_posts]',
        array(
            'label'       => __('Show Related Posts', 'aqualuxe'),
            'description' => __('Display related posts at the end of single posts.', 'aqualuxe'),
            'section'     => 'aqualuxe_blog_section',
            'type'        => 'checkbox',
        )
    );

    // Related Posts Count
    $wp_customize->add_setting(
        'aqualuxe_options[related_posts_count]',
        array(
            'default'           => 3,
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'transport'         => 'refresh',
            'sanitize_callback' => 'absint',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_options[related_posts_count]',
        array(
            'label'       => __('Related Posts Count', 'aqualuxe'),
            'description' => __('Number of related posts to display.', 'aqualuxe'),
            'section'     => 'aqualuxe_blog_section',
            'type'        => 'number',
            'input_attrs' => array(
                'min'  => 1,
                'max'  => 6,
                'step' => 1,
            ),
            'active_callback' => function() {
                $options = get_option('aqualuxe_options', array());
                return isset($options['show_related_posts']) ? $options['show_related_posts'] : true;
            },
        )
    );

    // Show Post Navigation
    $wp_customize->add_setting(
        'aqualuxe_options[show_post_nav]',
        array(
            'default'           => true,
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'transport'         => 'refresh',
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_options[show_post_nav]',
        array(
            'label'       => __('Show Post Navigation', 'aqualuxe'),
            'description' => __('Display next/previous post navigation.', 'aqualuxe'),
            'section'     => 'aqualuxe_blog_section',
            'type'        => 'checkbox',
        )
    );

    // Post Navigation Style
    $wp_customize->add_setting(
        'aqualuxe_options[post_nav_style]',
        array(
            'default'           => 'simple',
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'transport'         => 'refresh',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_options[post_nav_style]',
        array(
            'label'       => __('Post Navigation Style', 'aqualuxe'),
            'description' => __('Choose the style for post navigation.', 'aqualuxe'),
            'section'     => 'aqualuxe_blog_section',
            'type'        => 'select',
            'choices'     => array(
                'simple'     => __('Simple (Text Only)', 'aqualuxe'),
                'with_title' => __('With Post Title', 'aqualuxe'),
                'with_image' => __('With Featured Image', 'aqualuxe'),
                'fancy'      => __('Fancy (Image + Title)', 'aqualuxe'),
            ),
            'active_callback' => function() {
                $options = get_option('aqualuxe_options', array());
                return isset($options['show_post_nav']) ? $options['show_post_nav'] : true;
            },
        )
    );

    // Show Comments
    $wp_customize->add_setting(
        'aqualuxe_options[show_comments]',
        array(
            'default'           => true,
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'transport'         => 'refresh',
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_options[show_comments]',
        array(
            'label'       => __('Show Comments', 'aqualuxe'),
            'description' => __('Display comments on posts and pages.', 'aqualuxe'),
            'section'     => 'aqualuxe_blog_section',
            'type'        => 'checkbox',
        )
    );

    // Pagination Style
    $wp_customize->add_setting(
        'aqualuxe_options[pagination_style]',
        array(
            'default'           => 'numbered',
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'transport'         => 'refresh',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_options[pagination_style]',
        array(
            'label'       => __('Pagination Style', 'aqualuxe'),
            'description' => __('Choose the pagination style for archives.', 'aqualuxe'),
            'section'     => 'aqualuxe_blog_section',
            'type'        => 'select',
            'choices'     => array(
                'numbered'      => __('Numbered', 'aqualuxe'),
                'prev_next'     => __('Previous / Next', 'aqualuxe'),
                'load_more'     => __('Load More Button', 'aqualuxe'),
                'infinite'      => __('Infinite Scroll', 'aqualuxe'),
            ),
        )
    );
}
add_action('customize_register', 'aqualuxe_customize_blog_settings');

/**
 * Custom control for multi-checkbox
 */
if (!class_exists('AquaLuxe_Customize_Multi_Checkbox_Control')) {
    class AquaLuxe_Customize_Multi_Checkbox_Control extends WP_Customize_Control {
        public $type = 'multi-checkbox';

        public function render_content() {
            if (empty($this->choices)) {
                return;
            }

            if (!empty($this->label)) {
                echo '<span class="customize-control-title">' . esc_html($this->label) . '</span>';
            }

            if (!empty($this->description)) {
                echo '<span class="description customize-control-description">' . esc_html($this->description) . '</span>';
            }

            $multi_values = !is_array($this->value()) ? explode(',', $this->value()) : $this->value();

            echo '<ul>';
            foreach ($this->choices as $value => $label) {
                echo '<li>';
                echo '<label>';
                echo '<input type="checkbox" value="' . esc_attr($value) . '" ' . checked(in_array($value, $multi_values), true, false) . ' />';
                echo esc_html($label);
                echo '</label>';
                echo '</li>';
            }
            echo '</ul>';

            echo '<input type="hidden" ' . $this->get_link() . ' value="' . esc_attr(implode(',', $multi_values)) . '" />';
            
            echo '<script>
                jQuery(document).ready(function($) {
                    $(".customize-control-multi-checkbox input[type=\'checkbox\']").on("change", function() {
                        var checkbox_values = $(this).closest(".customize-control").find("input[type=\'checkbox\']:checked").map(function() {
                            return this.value;
                        }).get().join(",");
                        $(this).closest(".customize-control").find("input[type=\'hidden\']").val(checkbox_values).trigger("change");
                    });
                });
            </script>';
        }
    }
}