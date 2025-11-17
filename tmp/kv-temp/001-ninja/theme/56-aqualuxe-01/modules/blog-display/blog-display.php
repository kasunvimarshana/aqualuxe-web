<?php
/**
 * Blog Display Module
 *
 * @package AquaLuxe
 */

namespace AquaLuxe\Modules\BlogDisplay;

use AquaLuxe\Core\Module;
use AquaLuxe\Core\ModuleInterface;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Blog Display Module class
 */
class BlogDisplayModule extends Module implements ModuleInterface {
    /**
     * Setup module properties
     *
     * @return void
     */
    protected function setup() {
        $this->id          = 'blog-display';
        $this->name        = __( 'Blog Display', 'aqualuxe' );
        $this->description = __( 'Display blog posts in various layouts with filtering options.', 'aqualuxe' );
        $this->version     = '1.0.0';
        $this->dependencies = [];
        $this->settings     = [
            'title'           => __( 'Latest News', 'aqualuxe' ),
            'subtitle'        => __( 'Our Blog', 'aqualuxe' ),
            'description'     => __( 'Stay updated with our latest articles and industry insights.', 'aqualuxe' ),
            'layout'          => 'grid',
            'style'           => 'default',
            'columns'         => 3,
            'posts_per_page'  => 6,
            'show_image'      => true,
            'show_date'       => true,
            'show_author'     => true,
            'show_excerpt'    => true,
            'show_categories' => true,
            'show_tags'       => false,
            'show_comments'   => true,
            'show_readmore'   => true,
            'excerpt_length'  => 20,
            'order_by'        => 'date',
            'order'           => 'DESC',
            'animation'       => 'fade',
            'category'        => '',
            'tag'             => '',
            'exclude'         => '',
            'pagination'      => 'none', // none, numbers, load_more
            'featured_post'   => false,
        ];
    }

    /**
     * Initialize the module
     *
     * @return void
     */
    public function initialize() {
        // Register hooks
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_assets' ] );
        
        // Register customizer settings
        add_action( 'customize_register', [ $this, 'register_customizer_settings' ] );
    }

    /**
     * Enqueue module assets
     *
     * @return void
     */
    public function enqueue_assets() {
        // Enqueue only if module is active on the page
        if ( $this->is_active() ) {
            wp_enqueue_style( 'aqualuxe-blog-display', get_template_directory_uri() . '/assets/css/modules/blog-display.css', [], $this->version );
            wp_enqueue_script( 'aqualuxe-blog-display', get_template_directory_uri() . '/assets/js/modules/blog-display.js', [ 'jquery' ], $this->version, true );
        }
    }

    /**
     * Register customizer settings for the module
     *
     * @param WP_Customize_Manager $wp_customize The customizer object.
     * @return void
     */
    public function register_customizer_settings( $wp_customize ) {
        // Blog Display Section
        $wp_customize->add_section( 'aqualuxe_blog_display', [
            'title'       => __( 'Blog Display', 'aqualuxe' ),
            'description' => __( 'Configure the blog display module settings.', 'aqualuxe' ),
            'panel'       => 'aqualuxe_modules',
            'priority'    => 50,
        ] );

        // Title
        $wp_customize->add_setting( 'aqualuxe_blog_display_title', [
            'default'           => $this->get_setting( 'title' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage',
        ] );

        $wp_customize->add_control( 'aqualuxe_blog_display_title', [
            'label'    => __( 'Title', 'aqualuxe' ),
            'section'  => 'aqualuxe_blog_display',
            'type'     => 'text',
            'priority' => 10,
        ] );

        // Subtitle
        $wp_customize->add_setting( 'aqualuxe_blog_display_subtitle', [
            'default'           => $this->get_setting( 'subtitle' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage',
        ] );

        $wp_customize->add_control( 'aqualuxe_blog_display_subtitle', [
            'label'    => __( 'Subtitle', 'aqualuxe' ),
            'section'  => 'aqualuxe_blog_display',
            'type'     => 'text',
            'priority' => 20,
        ] );

        // Description
        $wp_customize->add_setting( 'aqualuxe_blog_display_description', [
            'default'           => $this->get_setting( 'description' ),
            'sanitize_callback' => 'wp_kses_post',
            'transport'         => 'postMessage',
        ] );

        $wp_customize->add_control( 'aqualuxe_blog_display_description', [
            'label'    => __( 'Description', 'aqualuxe' ),
            'section'  => 'aqualuxe_blog_display',
            'type'     => 'textarea',
            'priority' => 30,
        ] );

        // Layout
        $wp_customize->add_setting( 'aqualuxe_blog_display_layout', [
            'default'           => $this->get_setting( 'layout' ),
            'sanitize_callback' => 'sanitize_text_field',
        ] );

        $wp_customize->add_control( 'aqualuxe_blog_display_layout', [
            'label'    => __( 'Layout', 'aqualuxe' ),
            'section'  => 'aqualuxe_blog_display',
            'type'     => 'select',
            'choices'  => [
                'grid'    => __( 'Grid', 'aqualuxe' ),
                'list'    => __( 'List', 'aqualuxe' ),
                'masonry' => __( 'Masonry', 'aqualuxe' ),
                'minimal' => __( 'Minimal', 'aqualuxe' ),
                'classic' => __( 'Classic', 'aqualuxe' ),
            ],
            'priority' => 40,
        ] );

        // Style
        $wp_customize->add_setting( 'aqualuxe_blog_display_style', [
            'default'           => $this->get_setting( 'style' ),
            'sanitize_callback' => 'sanitize_text_field',
        ] );

        $wp_customize->add_control( 'aqualuxe_blog_display_style', [
            'label'    => __( 'Style', 'aqualuxe' ),
            'section'  => 'aqualuxe_blog_display',
            'type'     => 'select',
            'choices'  => [
                'default'  => __( 'Default', 'aqualuxe' ),
                'bordered' => __( 'Bordered', 'aqualuxe' ),
                'minimal'  => __( 'Minimal', 'aqualuxe' ),
                'shadow'   => __( 'Shadow', 'aqualuxe' ),
            ],
            'priority' => 50,
        ] );

        // Columns
        $wp_customize->add_setting( 'aqualuxe_blog_display_columns', [
            'default'           => $this->get_setting( 'columns' ),
            'sanitize_callback' => 'absint',
        ] );

        $wp_customize->add_control( 'aqualuxe_blog_display_columns', [
            'label'    => __( 'Columns', 'aqualuxe' ),
            'section'  => 'aqualuxe_blog_display',
            'type'     => 'select',
            'choices'  => [
                '1' => '1',
                '2' => '2',
                '3' => '3',
                '4' => '4',
            ],
            'priority' => 60,
        ] );

        // Posts Per Page
        $wp_customize->add_setting( 'aqualuxe_blog_display_posts_per_page', [
            'default'           => $this->get_setting( 'posts_per_page' ),
            'sanitize_callback' => 'absint',
        ] );

        $wp_customize->add_control( 'aqualuxe_blog_display_posts_per_page', [
            'label'    => __( 'Posts Per Page', 'aqualuxe' ),
            'section'  => 'aqualuxe_blog_display',
            'type'     => 'number',
            'priority' => 70,
        ] );

        // Display Options
        $display_options = [
            'show_image'      => __( 'Show Featured Image', 'aqualuxe' ),
            'show_date'       => __( 'Show Date', 'aqualuxe' ),
            'show_author'     => __( 'Show Author', 'aqualuxe' ),
            'show_excerpt'    => __( 'Show Excerpt', 'aqualuxe' ),
            'show_categories' => __( 'Show Categories', 'aqualuxe' ),
            'show_tags'       => __( 'Show Tags', 'aqualuxe' ),
            'show_comments'   => __( 'Show Comments Count', 'aqualuxe' ),
            'show_readmore'   => __( 'Show Read More Button', 'aqualuxe' ),
            'featured_post'   => __( 'Show Featured Post', 'aqualuxe' ),
        ];

        $priority = 80;
        foreach ( $display_options as $option_id => $option_label ) {
            $wp_customize->add_setting( 'aqualuxe_blog_display_' . $option_id, [
                'default'           => $this->get_setting( $option_id ),
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            ] );

            $wp_customize->add_control( 'aqualuxe_blog_display_' . $option_id, [
                'label'    => $option_label,
                'section'  => 'aqualuxe_blog_display',
                'type'     => 'checkbox',
                'priority' => $priority,
            ] );

            $priority += 10;
        }

        // Excerpt Length
        $wp_customize->add_setting( 'aqualuxe_blog_display_excerpt_length', [
            'default'           => $this->get_setting( 'excerpt_length' ),
            'sanitize_callback' => 'absint',
        ] );

        $wp_customize->add_control( 'aqualuxe_blog_display_excerpt_length', [
            'label'    => __( 'Excerpt Length', 'aqualuxe' ),
            'section'  => 'aqualuxe_blog_display',
            'type'     => 'number',
            'priority' => $priority,
        ] );

        $priority += 10;

        // Order By
        $wp_customize->add_setting( 'aqualuxe_blog_display_order_by', [
            'default'           => $this->get_setting( 'order_by' ),
            'sanitize_callback' => 'sanitize_text_field',
        ] );

        $wp_customize->add_control( 'aqualuxe_blog_display_order_by', [
            'label'    => __( 'Order By', 'aqualuxe' ),
            'section'  => 'aqualuxe_blog_display',
            'type'     => 'select',
            'choices'  => [
                'date'          => __( 'Date', 'aqualuxe' ),
                'title'         => __( 'Title', 'aqualuxe' ),
                'comment_count' => __( 'Comment Count', 'aqualuxe' ),
                'rand'          => __( 'Random', 'aqualuxe' ),
            ],
            'priority' => $priority,
        ] );

        $priority += 10;

        // Order
        $wp_customize->add_setting( 'aqualuxe_blog_display_order', [
            'default'           => $this->get_setting( 'order' ),
            'sanitize_callback' => 'sanitize_text_field',
        ] );

        $wp_customize->add_control( 'aqualuxe_blog_display_order', [
            'label'    => __( 'Order', 'aqualuxe' ),
            'section'  => 'aqualuxe_blog_display',
            'type'     => 'select',
            'choices'  => [
                'DESC' => __( 'Descending', 'aqualuxe' ),
                'ASC'  => __( 'Ascending', 'aqualuxe' ),
            ],
            'priority' => $priority,
        ] );

        $priority += 10;

        // Category
        $wp_customize->add_setting( 'aqualuxe_blog_display_category', [
            'default'           => $this->get_setting( 'category' ),
            'sanitize_callback' => 'sanitize_text_field',
        ] );

        $wp_customize->add_control( 'aqualuxe_blog_display_category', [
            'label'       => __( 'Category', 'aqualuxe' ),
            'description' => __( 'Enter category slug or ID, or leave empty for all categories.', 'aqualuxe' ),
            'section'     => 'aqualuxe_blog_display',
            'type'        => 'text',
            'priority'    => $priority,
        ] );

        $priority += 10;

        // Pagination
        $wp_customize->add_setting( 'aqualuxe_blog_display_pagination', [
            'default'           => $this->get_setting( 'pagination' ),
            'sanitize_callback' => 'sanitize_text_field',
        ] );

        $wp_customize->add_control( 'aqualuxe_blog_display_pagination', [
            'label'    => __( 'Pagination', 'aqualuxe' ),
            'section'  => 'aqualuxe_blog_display',
            'type'     => 'select',
            'choices'  => [
                'none'      => __( 'None', 'aqualuxe' ),
                'numbers'   => __( 'Numbers', 'aqualuxe' ),
                'load_more' => __( 'Load More Button', 'aqualuxe' ),
            ],
            'priority' => $priority,
        ] );

        $priority += 10;

        // Animation
        $wp_customize->add_setting( 'aqualuxe_blog_display_animation', [
            'default'           => $this->get_setting( 'animation' ),
            'sanitize_callback' => 'sanitize_text_field',
        ] );

        $wp_customize->add_control( 'aqualuxe_blog_display_animation', [
            'label'    => __( 'Animation', 'aqualuxe' ),
            'section'  => 'aqualuxe_blog_display',
            'type'     => 'select',
            'choices'  => [
                'none'  => __( 'None', 'aqualuxe' ),
                'fade'  => __( 'Fade', 'aqualuxe' ),
                'slide' => __( 'Slide', 'aqualuxe' ),
                'zoom'  => __( 'Zoom', 'aqualuxe' ),
            ],
            'priority' => $priority,
        ] );
    }

    /**
     * Get posts for the blog display
     *
     * @return array
     */
    public function get_posts() {
        $args = [
            'post_type'      => 'post',
            'post_status'    => 'publish',
            'posts_per_page' => $this->get_setting( 'posts_per_page', 6 ),
            'orderby'        => $this->get_setting( 'order_by', 'date' ),
            'order'          => $this->get_setting( 'order', 'DESC' ),
        ];

        // Filter by category if set
        $category = $this->get_setting( 'category', '' );
        if ( ! empty( $category ) ) {
            if ( is_numeric( $category ) ) {
                $args['cat'] = absint( $category );
            } else {
                $args['category_name'] = sanitize_text_field( $category );
            }
        }

        // Filter by tag if set
        $tag = $this->get_setting( 'tag', '' );
        if ( ! empty( $tag ) ) {
            if ( is_numeric( $tag ) ) {
                $args['tag_id'] = absint( $tag );
            } else {
                $args['tag'] = sanitize_text_field( $tag );
            }
        }

        // Exclude posts if set
        $exclude = $this->get_setting( 'exclude', '' );
        if ( ! empty( $exclude ) ) {
            $exclude_ids = array_map( 'absint', explode( ',', $exclude ) );
            $args['post__not_in'] = $exclude_ids;
        }

        // Featured post handling
        $featured_post_id = 0;
        if ( $this->get_setting( 'featured_post', false ) ) {
            // Get the most recent post or most commented post as featured
            $featured_args = [
                'post_type'      => 'post',
                'post_status'    => 'publish',
                'posts_per_page' => 1,
                'orderby'        => 'comment_count',
                'order'          => 'DESC',
            ];

            $featured_query = new \WP_Query( $featured_args );
            if ( $featured_query->have_posts() ) {
                $featured_query->the_post();
                $featured_post_id = get_the_ID();
                wp_reset_postdata();
            }

            // Exclude featured post from main query
            if ( $featured_post_id ) {
                if ( isset( $args['post__not_in'] ) && is_array( $args['post__not_in'] ) ) {
                    $args['post__not_in'][] = $featured_post_id;
                } else {
                    $args['post__not_in'] = [ $featured_post_id ];
                }
            }
        }

        // Get posts
        $query = new \WP_Query( $args );
        $posts = [];

        if ( $query->have_posts() ) {
            while ( $query->have_posts() ) {
                $query->the_post();
                
                $post_id = get_the_ID();
                $post = [
                    'id'        => $post_id,
                    'title'     => get_the_title(),
                    'url'       => get_permalink(),
                    'date'      => get_the_date(),
                    'timestamp' => get_the_date( 'U' ),
                    'author'    => [
                        'id'   => get_the_author_meta( 'ID' ),
                        'name' => get_the_author(),
                        'url'  => get_author_posts_url( get_the_author_meta( 'ID' ) ),
                    ],
                    'excerpt'   => $this->get_custom_excerpt( $this->get_setting( 'excerpt_length', 20 ) ),
                    'content'   => get_the_content(),
                    'comments'  => get_comments_number(),
                ];

                // Featured image
                if ( has_post_thumbnail() ) {
                    $post['image'] = [
                        'url'    => get_the_post_thumbnail_url( $post_id, 'large' ),
                        'alt'    => get_post_meta( get_post_thumbnail_id(), '_wp_attachment_image_alt', true ),
                        'width'  => 800,
                        'height' => 600,
                    ];
                }

                // Categories
                $categories = get_the_category();
                if ( ! empty( $categories ) ) {
                    $post['categories'] = [];
                    foreach ( $categories as $category ) {
                        $post['categories'][] = [
                            'id'   => $category->term_id,
                            'name' => $category->name,
                            'slug' => $category->slug,
                            'url'  => get_category_link( $category->term_id ),
                        ];
                    }
                }

                // Tags
                $tags = get_the_tags();
                if ( ! empty( $tags ) ) {
                    $post['tags'] = [];
                    foreach ( $tags as $tag ) {
                        $post['tags'][] = [
                            'id'   => $tag->term_id,
                            'name' => $tag->name,
                            'slug' => $tag->slug,
                            'url'  => get_tag_link( $tag->term_id ),
                        ];
                    }
                }

                $posts[] = $post;
            }
            wp_reset_postdata();
        }

        // Add featured post if enabled
        if ( $featured_post_id ) {
            $featured_post = get_post( $featured_post_id );
            if ( $featured_post ) {
                $featured = [
                    'id'        => $featured_post_id,
                    'title'     => $featured_post->post_title,
                    'url'       => get_permalink( $featured_post_id ),
                    'date'      => get_the_date( '', $featured_post_id ),
                    'timestamp' => get_the_date( 'U', $featured_post_id ),
                    'author'    => [
                        'id'   => $featured_post->post_author,
                        'name' => get_the_author_meta( 'display_name', $featured_post->post_author ),
                        'url'  => get_author_posts_url( $featured_post->post_author ),
                    ],
                    'excerpt'   => $this->get_custom_excerpt( $this->get_setting( 'excerpt_length', 20 ), $featured_post_id ),
                    'content'   => $featured_post->post_content,
                    'comments'  => get_comments_number( $featured_post_id ),
                    'featured'  => true,
                ];

                // Featured image
                if ( has_post_thumbnail( $featured_post_id ) ) {
                    $featured['image'] = [
                        'url'    => get_the_post_thumbnail_url( $featured_post_id, 'large' ),
                        'alt'    => get_post_meta( get_post_thumbnail_id( $featured_post_id ), '_wp_attachment_image_alt', true ),
                        'width'  => 800,
                        'height' => 600,
                    ];
                }

                // Categories
                $categories = get_the_category( $featured_post_id );
                if ( ! empty( $categories ) ) {
                    $featured['categories'] = [];
                    foreach ( $categories as $category ) {
                        $featured['categories'][] = [
                            'id'   => $category->term_id,
                            'name' => $category->name,
                            'slug' => $category->slug,
                            'url'  => get_category_link( $category->term_id ),
                        ];
                    }
                }

                // Tags
                $tags = get_the_tags( $featured_post_id );
                if ( ! empty( $tags ) ) {
                    $featured['tags'] = [];
                    foreach ( $tags as $tag ) {
                        $featured['tags'][] = [
                            'id'   => $tag->term_id,
                            'name' => $tag->name,
                            'slug' => $tag->slug,
                            'url'  => get_tag_link( $tag->term_id ),
                        ];
                    }
                }

                // Add featured post to the beginning of the array
                array_unshift( $posts, $featured );
            }
        }

        return $posts;
    }

    /**
     * Get custom excerpt with specific length
     *
     * @param int $length Excerpt length.
     * @param int $post_id Optional post ID.
     * @return string
     */
    public function get_custom_excerpt( $length = 20, $post_id = null ) {
        $post = $post_id ? get_post( $post_id ) : get_post();
        
        if ( ! $post ) {
            return '';
        }

        if ( has_excerpt( $post->ID ) ) {
            $excerpt = $post->post_excerpt;
        } else {
            $excerpt = $post->post_content;
            $excerpt = strip_shortcodes( $excerpt );
            $excerpt = excerpt_remove_blocks( $excerpt );
            $excerpt = strip_tags( $excerpt );
        }

        $words = explode( ' ', $excerpt, $length + 1 );
        
        if ( count( $words ) > $length ) {
            array_pop( $words );
            $excerpt = implode( ' ', $words ) . '...';
        } else {
            $excerpt = implode( ' ', $words );
        }

        return $excerpt;
    }

    /**
     * Render the module
     *
     * @return void
     */
    public function render() {
        // Load template
        $this->load_template( 'blog-display' );
    }
}