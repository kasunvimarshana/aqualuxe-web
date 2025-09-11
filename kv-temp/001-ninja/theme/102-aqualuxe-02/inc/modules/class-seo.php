<?php
/**
 * SEO Module
 *
 * Handles theme SEO optimization features
 *
 * @package AquaLuxe\Modules
 * @since 1.0.0
 */

namespace AquaLuxe\Modules;

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class SEO
 *
 * Implements SEO optimization features
 *
 * @since 1.0.0
 */
class SEO {

    /**
     * Initialize the SEO module
     *
     * @since 1.0.0
     */
    public function init() {
        add_action( 'wp_head', array( $this, 'add_meta_tags' ), 1 );
        add_action( 'wp_head', array( $this, 'add_schema_markup' ), 2 );
        add_action( 'wp_head', array( $this, 'add_open_graph_tags' ), 3 );
        add_action( 'wp_head', array( $this, 'add_twitter_cards' ), 4 );
        add_action( 'wp_head', array( $this, 'add_canonical_url' ), 5 );
        
        // Sitemap generation
        add_action( 'init', array( $this, 'setup_sitemap' ) );
        
        // Breadcrumbs
        add_action( 'aqualuxe_breadcrumbs', array( $this, 'display_breadcrumbs' ) );
        
        // Title optimization
        add_filter( 'wp_title', array( $this, 'optimize_title' ), 10, 3 );
        add_filter( 'document_title_separator', array( $this, 'title_separator' ) );
        
        // Remove unnecessary meta
        remove_action( 'wp_head', 'wp_generator' );
    }

    /**
     * Add meta tags
     *
     * @since 1.0.0
     */
    public function add_meta_tags() {
        if ( is_singular() ) {
            global $post;
            
            $description = $this->get_meta_description( $post );
            $keywords = $this->get_meta_keywords( $post );
            
            if ( $description ) {
                echo '<meta name="description" content="' . esc_attr( $description ) . '">' . "\n";
            }
            
            if ( $keywords ) {
                echo '<meta name="keywords" content="' . esc_attr( $keywords ) . '">' . "\n";
            }
            
            // Robots meta
            $robots = $this->get_robots_meta( $post );
            if ( $robots ) {
                echo '<meta name="robots" content="' . esc_attr( $robots ) . '">' . "\n";
            }
            
        } elseif ( is_home() || is_front_page() ) {
            $description = get_bloginfo( 'description' );
            if ( $description ) {
                echo '<meta name="description" content="' . esc_attr( $description ) . '">' . "\n";
            }
        }
        
        // Add mobile optimization
        echo '<meta name="viewport" content="width=device-width, initial-scale=1">' . "\n";
        echo '<meta name="format-detection" content="telephone=no">' . "\n";
    }

    /**
     * Add Schema.org markup
     *
     * @since 1.0.0
     */
    public function add_schema_markup() {
        if ( is_singular( 'post' ) ) {
            $this->add_article_schema();
        } elseif ( is_singular( 'page' ) ) {
            $this->add_webpage_schema();
        } elseif ( is_home() || is_front_page() ) {
            $this->add_website_schema();
        }
        
        // Organization schema
        $this->add_organization_schema();
    }

    /**
     * Add article schema
     *
     * @since 1.0.0
     */
    private function add_article_schema() {
        global $post;
        
        $schema = array(
            '@context' => 'https://schema.org',
            '@type'    => 'Article',
            'headline' => get_the_title(),
            'author'   => array(
                '@type' => 'Person',
                'name'  => get_the_author_meta( 'display_name', $post->post_author ),
                'url'   => get_author_posts_url( $post->post_author ),
            ),
            'publisher' => array(
                '@type' => 'Organization',
                'name'  => get_bloginfo( 'name' ),
                'logo'  => array(
                    '@type' => 'ImageObject',
                    'url'   => $this->get_logo_url(),
                ),
            ),
            'datePublished' => get_the_date( 'c' ),
            'dateModified'  => get_the_modified_date( 'c' ),
            'description'   => $this->get_meta_description( $post ),
            'url'          => get_permalink(),
        );
        
        if ( has_post_thumbnail() ) {
            $image_url = get_the_post_thumbnail_url( $post->ID, 'full' );
            $schema['image'] = array(
                '@type' => 'ImageObject',
                'url'   => $image_url,
                'width' => 1200,
                'height' => 630,
            );
        }
        
        $this->output_schema( $schema );
    }

    /**
     * Add webpage schema
     *
     * @since 1.0.0
     */
    private function add_webpage_schema() {
        global $post;
        
        $schema = array(
            '@context' => 'https://schema.org',
            '@type'    => 'WebPage',
            'name'     => get_the_title(),
            'description' => $this->get_meta_description( $post ),
            'url'      => get_permalink(),
            'isPartOf' => array(
                '@type' => 'WebSite',
                'name'  => get_bloginfo( 'name' ),
                'url'   => home_url( '/' ),
            ),
        );
        
        $this->output_schema( $schema );
    }

    /**
     * Add website schema
     *
     * @since 1.0.0
     */
    private function add_website_schema() {
        $schema = array(
            '@context' => 'https://schema.org',
            '@type'    => 'WebSite',
            'name'     => get_bloginfo( 'name' ),
            'description' => get_bloginfo( 'description' ),
            'url'      => home_url( '/' ),
            'potentialAction' => array(
                '@type'       => 'SearchAction',
                'target'      => home_url( '/?s={search_term_string}' ),
                'query-input' => 'required name=search_term_string',
            ),
        );
        
        $this->output_schema( $schema );
    }

    /**
     * Add organization schema
     *
     * @since 1.0.0
     */
    private function add_organization_schema() {
        $schema = array(
            '@context' => 'https://schema.org',
            '@type'    => 'Organization',
            'name'     => get_bloginfo( 'name' ),
            'url'      => home_url( '/' ),
            'logo'     => $this->get_logo_url(),
        );
        
        // Add contact information if available
        $phone = get_theme_mod( 'aqualuxe_contact_phone' );
        $email = get_theme_mod( 'aqualuxe_contact_email' );
        $address = get_theme_mod( 'aqualuxe_contact_address' );
        
        if ( $phone || $email || $address ) {
            $schema['contactPoint'] = array(
                '@type' => 'ContactPoint',
                'contactType' => 'customer service',
            );
            
            if ( $phone ) {
                $schema['contactPoint']['telephone'] = $phone;
            }
            
            if ( $email ) {
                $schema['contactPoint']['email'] = $email;
            }
        }
        
        // Add social media profiles
        $social_profiles = array();
        $social_networks = array( 'facebook', 'twitter', 'instagram', 'youtube', 'linkedin' );
        
        foreach ( $social_networks as $network ) {
            $url = get_theme_mod( "aqualuxe_{$network}_url" );
            if ( $url ) {
                $social_profiles[] = $url;
            }
        }
        
        if ( ! empty( $social_profiles ) ) {
            $schema['sameAs'] = $social_profiles;
        }
        
        $this->output_schema( $schema );
    }

    /**
     * Add Open Graph tags
     *
     * @since 1.0.0
     */
    public function add_open_graph_tags() {
        if ( is_singular() ) {
            global $post;
            
            $title = get_the_title();
            $description = $this->get_meta_description( $post );
            $url = get_permalink();
            $image = has_post_thumbnail() ? get_the_post_thumbnail_url( $post->ID, 'full' ) : '';
            
            echo '<meta property="og:type" content="article">' . "\n";
            echo '<meta property="og:title" content="' . esc_attr( $title ) . '">' . "\n";
            echo '<meta property="og:description" content="' . esc_attr( $description ) . '">' . "\n";
            echo '<meta property="og:url" content="' . esc_url( $url ) . '">' . "\n";
            echo '<meta property="og:site_name" content="' . esc_attr( get_bloginfo( 'name' ) ) . '">' . "\n";
            
            if ( $image ) {
                echo '<meta property="og:image" content="' . esc_url( $image ) . '">' . "\n";
                echo '<meta property="og:image:width" content="1200">' . "\n";
                echo '<meta property="og:image:height" content="630">' . "\n";
            }
            
            // Article specific tags
            if ( is_single() ) {
                echo '<meta property="article:published_time" content="' . esc_attr( get_the_date( 'c' ) ) . '">' . "\n";
                echo '<meta property="article:modified_time" content="' . esc_attr( get_the_modified_date( 'c' ) ) . '">' . "\n";
                echo '<meta property="article:author" content="' . esc_attr( get_the_author_meta( 'display_name', $post->post_author ) ) . '">' . "\n";
                
                $categories = get_the_category();
                foreach ( $categories as $category ) {
                    echo '<meta property="article:section" content="' . esc_attr( $category->name ) . '">' . "\n";
                }
                
                $tags = get_the_tags();
                if ( $tags ) {
                    foreach ( $tags as $tag ) {
                        echo '<meta property="article:tag" content="' . esc_attr( $tag->name ) . '">' . "\n";
                    }
                }
            }
            
        } elseif ( is_home() || is_front_page() ) {
            echo '<meta property="og:type" content="website">' . "\n";
            echo '<meta property="og:title" content="' . esc_attr( get_bloginfo( 'name' ) ) . '">' . "\n";
            echo '<meta property="og:description" content="' . esc_attr( get_bloginfo( 'description' ) ) . '">' . "\n";
            echo '<meta property="og:url" content="' . esc_url( home_url( '/' ) ) . '">' . "\n";
            echo '<meta property="og:site_name" content="' . esc_attr( get_bloginfo( 'name' ) ) . '">' . "\n";
        }
    }

    /**
     * Add Twitter Card tags
     *
     * @since 1.0.0
     */
    public function add_twitter_cards() {
        echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
        
        $twitter_handle = get_theme_mod( 'aqualuxe_twitter_handle' );
        if ( $twitter_handle ) {
            echo '<meta name="twitter:site" content="@' . esc_attr( str_replace( '@', '', $twitter_handle ) ) . '">' . "\n";
        }
        
        if ( is_singular() ) {
            global $post;
            
            $title = get_the_title();
            $description = $this->get_meta_description( $post );
            $image = has_post_thumbnail() ? get_the_post_thumbnail_url( $post->ID, 'full' ) : '';
            
            echo '<meta name="twitter:title" content="' . esc_attr( $title ) . '">' . "\n";
            echo '<meta name="twitter:description" content="' . esc_attr( $description ) . '">' . "\n";
            
            if ( $image ) {
                echo '<meta name="twitter:image" content="' . esc_url( $image ) . '">' . "\n";
            }
            
            $author_twitter = get_the_author_meta( 'twitter', $post->post_author );
            if ( $author_twitter ) {
                echo '<meta name="twitter:creator" content="@' . esc_attr( str_replace( '@', '', $author_twitter ) ) . '">' . "\n";
            }
        }
    }

    /**
     * Add canonical URL
     *
     * @since 1.0.0
     */
    public function add_canonical_url() {
        if ( is_singular() ) {
            echo '<link rel="canonical" href="' . esc_url( get_permalink() ) . '">' . "\n";
        } elseif ( is_home() || is_front_page() ) {
            echo '<link rel="canonical" href="' . esc_url( home_url( '/' ) ) . '">' . "\n";
        }
    }

    /**
     * Get meta description for a post
     *
     * @since 1.0.0
     * @param WP_Post $post Post object
     * @return string
     */
    private function get_meta_description( $post ) {
        // Custom meta description
        $custom_description = get_post_meta( $post->ID, '_aqualuxe_meta_description', true );
        if ( $custom_description ) {
            return $custom_description;
        }
        
        // Use excerpt if available
        if ( ! empty( $post->post_excerpt ) ) {
            return wp_trim_words( $post->post_excerpt, 30 );
        }
        
        // Generate from content
        $content = wp_strip_all_tags( $post->post_content );
        return wp_trim_words( $content, 30 );
    }

    /**
     * Get meta keywords for a post
     *
     * @since 1.0.0
     * @param WP_Post $post Post object
     * @return string
     */
    private function get_meta_keywords( $post ) {
        $keywords = array();
        
        // Get tags
        $tags = get_the_tags( $post->ID );
        if ( $tags ) {
            foreach ( $tags as $tag ) {
                $keywords[] = $tag->name;
            }
        }
        
        // Get categories
        $categories = get_the_category( $post->ID );
        if ( $categories ) {
            foreach ( $categories as $category ) {
                $keywords[] = $category->name;
            }
        }
        
        return implode( ', ', array_unique( $keywords ) );
    }

    /**
     * Get robots meta for a post
     *
     * @since 1.0.0
     * @param WP_Post $post Post object
     * @return string
     */
    private function get_robots_meta( $post ) {
        $robots = array();
        
        // Check if post should be indexed
        $noindex = get_post_meta( $post->ID, '_aqualuxe_noindex', true );
        if ( $noindex ) {
            $robots[] = 'noindex';
        } else {
            $robots[] = 'index';
        }
        
        // Check if post should be followed
        $nofollow = get_post_meta( $post->ID, '_aqualuxe_nofollow', true );
        if ( $nofollow ) {
            $robots[] = 'nofollow';
        } else {
            $robots[] = 'follow';
        }
        
        return implode( ', ', $robots );
    }

    /**
     * Get logo URL
     *
     * @since 1.0.0
     * @return string
     */
    private function get_logo_url() {
        $custom_logo_id = get_theme_mod( 'custom_logo' );
        if ( $custom_logo_id ) {
            return wp_get_attachment_image_url( $custom_logo_id, 'full' );
        }
        
        return get_template_directory_uri() . '/assets/images/logo.png';
    }

    /**
     * Output schema markup
     *
     * @since 1.0.0
     * @param array $schema Schema data
     */
    private function output_schema( $schema ) {
        echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_SLASHES ) . '</script>' . "\n";
    }

    /**
     * Setup sitemap
     *
     * @since 1.0.0
     */
    public function setup_sitemap() {
        // WordPress 5.5+ has built-in sitemaps, but we can enhance them
        add_filter( 'wp_sitemaps_posts_entry', array( $this, 'enhance_sitemap_entry' ), 10, 3 );
    }

    /**
     * Enhance sitemap entry
     *
     * @since 1.0.0
     * @param array   $entry Sitemap entry
     * @param WP_Post $post  Post object
     * @param string  $sitemap_type Sitemap type
     * @return array
     */
    public function enhance_sitemap_entry( $entry, $post, $sitemap_type ) {
        // Add images to sitemap
        if ( has_post_thumbnail( $post->ID ) ) {
            $entry['images'] = array(
                array(
                    'loc' => get_the_post_thumbnail_url( $post->ID, 'full' ),
                    'title' => get_the_title( $post->ID ),
                ),
            );
        }
        
        return $entry;
    }

    /**
     * Display breadcrumbs
     *
     * @since 1.0.0
     */
    public function display_breadcrumbs() {
        if ( ! is_front_page() ) {
            aqualuxe_breadcrumbs();
        }
    }

    /**
     * Optimize page title
     *
     * @since 1.0.0
     * @param string $title Page title
     * @param string $sep   Title separator
     * @param string $seplocation Separator location
     * @return string
     */
    public function optimize_title( $title, $sep, $seplocation ) {
        if ( is_home() || is_front_page() ) {
            $title = get_bloginfo( 'name' ) . ' ' . $sep . ' ' . get_bloginfo( 'description' );
        }
        
        return $title;
    }

    /**
     * Set title separator
     *
     * @since 1.0.0
     * @param string $sep Default separator
     * @return string
     */
    public function title_separator( $sep ) {
        return '|';
    }
}