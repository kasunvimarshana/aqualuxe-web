<?php
/**
 * AquaLuxe Theme Setup
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Theme Setup Class
 */
class AquaLuxe_Setup {
    /**
     * Constructor
     */
    public function __construct() {
        // Theme setup actions
        add_action( 'after_setup_theme', array( $this, 'content_width' ), 0 );
        add_filter( 'body_class', array( $this, 'body_classes' ) );
        add_filter( 'excerpt_more', array( $this, 'excerpt_more' ) );
        add_filter( 'excerpt_length', array( $this, 'excerpt_length' ) );
        add_action( 'wp_head', array( $this, 'pingback_header' ) );
        
        // Add schema markup
        add_filter( 'language_attributes', array( $this, 'add_schema_markup' ) );
        
        // Add Open Graph metadata
        add_action( 'wp_head', array( $this, 'add_open_graph_tags' ) );
    }

    /**
     * Set the content width in pixels
     */
    public function content_width() {
        $GLOBALS['content_width'] = apply_filters( 'aqualuxe_content_width', 1200 );
    }

    /**
     * Adds custom classes to the array of body classes.
     *
     * @param array $classes Classes for the body element.
     * @return array
     */
    public function body_classes( $classes ) {
        // Add a class if there is a custom header.
        if ( has_header_image() ) {
            $classes[] = 'has-header-image';
        }

        // Add a class if there is a custom background.
        if ( get_background_image() ) {
            $classes[] = 'has-background-image';
        }

        // Add a class if the site is using a sidebar
        if ( is_active_sidebar( 'sidebar-1' ) && ! is_page_template( 'templates/full-width.php' ) ) {
            $classes[] = 'has-sidebar';
        } else {
            $classes[] = 'no-sidebar';
        }

        // Add a class for the color scheme
        $color_scheme = get_theme_mod( 'aqualuxe_color_scheme', 'default' );
        $classes[] = 'color-scheme-' . esc_attr( $color_scheme );

        return $classes;
    }

    /**
     * Replaces "[...]" (appended to automatically generated excerpts) with ... and a more link.
     *
     * @param string $more The current more string.
     * @return string
     */
    public function excerpt_more( $more ) {
        if ( is_admin() ) {
            return $more;
        }
        
        return '&hellip; <a href="' . esc_url( get_permalink() ) . '" class="more-link">' . sprintf(
            /* translators: %s: Post title */
            __( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'aqualuxe' ),
            get_the_title()
        ) . '</a>';
    }

    /**
     * Filters the excerpt length.
     *
     * @param int $length Excerpt length.
     * @return int
     */
    public function excerpt_length( $length ) {
        if ( is_admin() ) {
            return $length;
        }
        
        return 30;
    }

    /**
     * Add a pingback url auto-discovery header for singularly identifiable articles.
     */
    public function pingback_header() {
        if ( is_singular() && pings_open() ) {
            echo '<link rel="pingback" href="', esc_url( get_bloginfo( 'pingback_url' ) ), '">';
        }
    }

    /**
     * Add schema markup to the <html> tag
     *
     * @param string $output The language attributes.
     * @return string
     */
    public function add_schema_markup( $output ) {
        $schema = 'http://schema.org/';
        
        // Check what type of page we're on
        if ( is_singular( 'product' ) ) {
            $type = 'Product';
        } elseif ( is_singular( 'post' ) ) {
            $type = 'Article';
        } elseif ( is_author() ) {
            $type = 'ProfilePage';
        } elseif ( is_search() ) {
            $type = 'SearchResultsPage';
        } else {
            $type = 'WebPage';
        }
        
        $schema_type = apply_filters( 'aqualuxe_schema_type', $type );
        
        return $output . ' itemscope itemtype="' . esc_attr( $schema . $schema_type ) . '"';
    }

    /**
     * Add Open Graph meta tags to the header
     */
    public function add_open_graph_tags() {
        // Skip if an SEO plugin is active
        if ( class_exists( 'WPSEO_Options' ) || defined( 'RANK_MATH_VERSION' ) ) {
            return;
        }
        
        global $post;
        
        if ( is_singular() && $post ) {
            // Basic Open Graph tags
            echo '<meta property="og:type" content="' . ( is_single() ? 'article' : 'website' ) . '" />' . "\n";
            echo '<meta property="og:title" content="' . esc_attr( get_the_title() ) . '" />' . "\n";
            echo '<meta property="og:url" content="' . esc_url( get_permalink() ) . '" />' . "\n";
            echo '<meta property="og:site_name" content="' . esc_attr( get_bloginfo( 'name' ) ) . '" />' . "\n";
            
            // Description
            $excerpt = has_excerpt( $post->ID ) ? $post->post_excerpt : wp_trim_words( $post->post_content, 55, '...' );
            echo '<meta property="og:description" content="' . esc_attr( $excerpt ) . '" />' . "\n";
            
            // Featured image
            if ( has_post_thumbnail( $post->ID ) ) {
                $thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'large' );
                if ( $thumbnail ) {
                    echo '<meta property="og:image" content="' . esc_url( $thumbnail[0] ) . '" />' . "\n";
                    echo '<meta property="og:image:width" content="' . esc_attr( $thumbnail[1] ) . '" />' . "\n";
                    echo '<meta property="og:image:height" content="' . esc_attr( $thumbnail[2] ) . '" />' . "\n";
                }
            }
            
            // Article specific tags
            if ( is_single() ) {
                echo '<meta property="article:published_time" content="' . esc_attr( get_the_date( 'c' ) ) . '" />' . "\n";
                echo '<meta property="article:modified_time" content="' . esc_attr( get_the_modified_date( 'c' ) ) . '" />' . "\n";
                
                // Categories as article:section
                $categories = get_the_category();
                if ( ! empty( $categories ) ) {
                    echo '<meta property="article:section" content="' . esc_attr( $categories[0]->name ) . '" />' . "\n";
                }
                
                // Tags as article:tag
                $tags = get_the_tags();
                if ( ! empty( $tags ) ) {
                    foreach ( $tags as $tag ) {
                        echo '<meta property="article:tag" content="' . esc_attr( $tag->name ) . '" />' . "\n";
                    }
                }
            }
        } else {
            // Default tags for non-singular pages
            echo '<meta property="og:type" content="website" />' . "\n";
            echo '<meta property="og:title" content="' . esc_attr( get_bloginfo( 'name' ) ) . '" />' . "\n";
            echo '<meta property="og:url" content="' . esc_url( home_url( '/' ) ) . '" />' . "\n";
            echo '<meta property="og:site_name" content="' . esc_attr( get_bloginfo( 'name' ) ) . '" />' . "\n";
            echo '<meta property="og:description" content="' . esc_attr( get_bloginfo( 'description' ) ) . '" />' . "\n";
            
            // Site logo or default image
            $custom_logo_id = get_theme_mod( 'custom_logo' );
            if ( $custom_logo_id ) {
                $logo = wp_get_attachment_image_src( $custom_logo_id, 'full' );
                if ( $logo ) {
                    echo '<meta property="og:image" content="' . esc_url( $logo[0] ) . '" />' . "\n";
                }
            }
        }
    }
}

// Initialize the class
new AquaLuxe_Setup();