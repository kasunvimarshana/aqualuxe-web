<?php
/**
 * Utils Helper Class
 *
 * @package AquaLuxe
 * @subpackage Helpers
 * @since 1.0.0
 */

namespace AquaLuxe\Helpers;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Utils Helper Class
 * 
 * This class provides utility functions for the theme.
 */
class Utils {
    /**
     * Get the post thumbnail URL
     *
     * @param int    $post_id Post ID
     * @param string $size    Image size
     * @return string|false
     */
    public static function get_post_thumbnail_url( $post_id = null, $size = 'full' ) {
        if ( ! $post_id ) {
            $post_id = get_the_ID();
        }
        
        if ( has_post_thumbnail( $post_id ) ) {
            return get_the_post_thumbnail_url( $post_id, $size );
        }
        
        return false;
    }

    /**
     * Get the post excerpt
     *
     * @param int  $post_id Post ID
     * @param int  $length  Excerpt length
     * @param bool $more    Show more link
     * @return string
     */
    public static function get_excerpt( $post_id = null, $length = 55, $more = true ) {
        if ( ! $post_id ) {
            $post_id = get_the_ID();
        }
        
        $post = get_post( $post_id );
        
        if ( ! $post ) {
            return '';
        }
        
        if ( has_excerpt( $post_id ) ) {
            $excerpt = $post->post_excerpt;
        } else {
            $excerpt = $post->post_content;
        }
        
        $excerpt = strip_shortcodes( $excerpt );
        $excerpt = excerpt_remove_blocks( $excerpt );
        $excerpt = wp_strip_all_tags( $excerpt );
        $excerpt = str_replace( ']]>', ']]&gt;', $excerpt );
        
        $excerpt_length = apply_filters( 'excerpt_length', $length );
        $excerpt_more = $more ? apply_filters( 'excerpt_more', ' ' . '[&hellip;]' ) : '';
        $excerpt = wp_trim_words( $excerpt, $excerpt_length, $excerpt_more );
        
        return $excerpt;
    }

    /**
     * Get post categories
     *
     * @param int    $post_id  Post ID
     * @param string $taxonomy Taxonomy name
     * @return array
     */
    public static function get_post_categories( $post_id = null, $taxonomy = 'category' ) {
        if ( ! $post_id ) {
            $post_id = get_the_ID();
        }
        
        $categories = get_the_terms( $post_id, $taxonomy );
        
        if ( ! $categories || is_wp_error( $categories ) ) {
            return [];
        }
        
        return $categories;
    }

    /**
     * Get post category names
     *
     * @param int    $post_id  Post ID
     * @param string $taxonomy Taxonomy name
     * @return array
     */
    public static function get_post_category_names( $post_id = null, $taxonomy = 'category' ) {
        $categories = self::get_post_categories( $post_id, $taxonomy );
        
        if ( empty( $categories ) ) {
            return [];
        }
        
        return wp_list_pluck( $categories, 'name' );
    }

    /**
     * Get post tags
     *
     * @param int    $post_id  Post ID
     * @param string $taxonomy Taxonomy name
     * @return array
     */
    public static function get_post_tags( $post_id = null, $taxonomy = 'post_tag' ) {
        if ( ! $post_id ) {
            $post_id = get_the_ID();
        }
        
        $tags = get_the_terms( $post_id, $taxonomy );
        
        if ( ! $tags || is_wp_error( $tags ) ) {
            return [];
        }
        
        return $tags;
    }

    /**
     * Get post tag names
     *
     * @param int    $post_id  Post ID
     * @param string $taxonomy Taxonomy name
     * @return array
     */
    public static function get_post_tag_names( $post_id = null, $taxonomy = 'post_tag' ) {
        $tags = self::get_post_tags( $post_id, $taxonomy );
        
        if ( empty( $tags ) ) {
            return [];
        }
        
        return wp_list_pluck( $tags, 'name' );
    }

    /**
     * Get post author
     *
     * @param int $post_id Post ID
     * @return \WP_User|false
     */
    public static function get_post_author( $post_id = null ) {
        if ( ! $post_id ) {
            $post_id = get_the_ID();
        }
        
        $post = get_post( $post_id );
        
        if ( ! $post ) {
            return false;
        }
        
        return get_userdata( $post->post_author );
    }

    /**
     * Get post author name
     *
     * @param int $post_id Post ID
     * @return string
     */
    public static function get_post_author_name( $post_id = null ) {
        $author = self::get_post_author( $post_id );
        
        if ( ! $author ) {
            return '';
        }
        
        return $author->display_name;
    }

    /**
     * Get post author avatar
     *
     * @param int    $post_id Post ID
     * @param int    $size    Avatar size
     * @param string $default Default avatar
     * @param string $alt     Alternative text
     * @return string
     */
    public static function get_post_author_avatar( $post_id = null, $size = 96, $default = '', $alt = '' ) {
        $author = self::get_post_author( $post_id );
        
        if ( ! $author ) {
            return '';
        }
        
        return get_avatar( $author->ID, $size, $default, $alt );
    }

    /**
     * Get post date
     *
     * @param int    $post_id Post ID
     * @param string $format  Date format
     * @return string
     */
    public static function get_post_date( $post_id = null, $format = '' ) {
        if ( ! $post_id ) {
            $post_id = get_the_ID();
        }
        
        if ( ! $format ) {
            $format = get_option( 'date_format' );
        }
        
        return get_the_date( $format, $post_id );
    }

    /**
     * Get post modified date
     *
     * @param int    $post_id Post ID
     * @param string $format  Date format
     * @return string
     */
    public static function get_post_modified_date( $post_id = null, $format = '' ) {
        if ( ! $post_id ) {
            $post_id = get_the_ID();
        }
        
        if ( ! $format ) {
            $format = get_option( 'date_format' );
        }
        
        return get_the_modified_date( $format, $post_id );
    }

    /**
     * Get post comments count
     *
     * @param int $post_id Post ID
     * @return int
     */
    public static function get_post_comments_count( $post_id = null ) {
        if ( ! $post_id ) {
            $post_id = get_the_ID();
        }
        
        return get_comments_number( $post_id );
    }

    /**
     * Get post views count
     *
     * @param int $post_id Post ID
     * @return int
     */
    public static function get_post_views( $post_id = null ) {
        if ( ! $post_id ) {
            $post_id = get_the_ID();
        }
        
        $count = get_post_meta( $post_id, '_aqualuxe_post_views', true );
        
        if ( ! $count ) {
            return 0;
        }
        
        return (int) $count;
    }

    /**
     * Set post views count
     *
     * @param int $post_id Post ID
     * @return void
     */
    public static function set_post_views( $post_id = null ) {
        if ( ! $post_id ) {
            $post_id = get_the_ID();
        }
        
        $count = self::get_post_views( $post_id );
        $count++;
        
        update_post_meta( $post_id, '_aqualuxe_post_views', $count );
    }

    /**
     * Get related posts
     *
     * @param int   $post_id  Post ID
     * @param int   $count    Number of posts
     * @param array $args     Query arguments
     * @return array
     */
    public static function get_related_posts( $post_id = null, $count = 3, $args = [] ) {
        if ( ! $post_id ) {
            $post_id = get_the_ID();
        }
        
        $categories = self::get_post_categories( $post_id );
        
        if ( empty( $categories ) ) {
            return [];
        }
        
        $category_ids = wp_list_pluck( $categories, 'term_id' );
        
        $query_args = [
            'post_type'      => 'post',
            'posts_per_page' => $count,
            'post__not_in'   => [ $post_id ],
            'tax_query'      => [
                [
                    'taxonomy' => 'category',
                    'field'    => 'term_id',
                    'terms'    => $category_ids,
                ],
            ],
        ];
        
        $query_args = wp_parse_args( $args, $query_args );
        
        $query = new \WP_Query( $query_args );
        
        if ( ! $query->have_posts() ) {
            return [];
        }
        
        return $query->posts;
    }

    /**
     * Get popular posts
     *
     * @param int   $count Number of posts
     * @param array $args  Query arguments
     * @return array
     */
    public static function get_popular_posts( $count = 5, $args = [] ) {
        $query_args = [
            'post_type'      => 'post',
            'posts_per_page' => $count,
            'meta_key'       => '_aqualuxe_post_views',
            'orderby'        => 'meta_value_num',
            'order'          => 'DESC',
        ];
        
        $query_args = wp_parse_args( $args, $query_args );
        
        $query = new \WP_Query( $query_args );
        
        if ( ! $query->have_posts() ) {
            return [];
        }
        
        return $query->posts;
    }

    /**
     * Get recent posts
     *
     * @param int   $count Number of posts
     * @param array $args  Query arguments
     * @return array
     */
    public static function get_recent_posts( $count = 5, $args = [] ) {
        $query_args = [
            'post_type'      => 'post',
            'posts_per_page' => $count,
            'orderby'        => 'date',
            'order'          => 'DESC',
        ];
        
        $query_args = wp_parse_args( $args, $query_args );
        
        $query = new \WP_Query( $query_args );
        
        if ( ! $query->have_posts() ) {
            return [];
        }
        
        return $query->posts;
    }

    /**
     * Get post navigation
     *
     * @param int    $post_id Post ID
     * @param string $prev    Previous text
     * @param string $next    Next text
     * @return array
     */
    public static function get_post_navigation( $post_id = null, $prev = '', $next = '' ) {
        if ( ! $post_id ) {
            $post_id = get_the_ID();
        }
        
        if ( ! $prev ) {
            $prev = __( 'Previous Post', 'aqualuxe' );
        }
        
        if ( ! $next ) {
            $next = __( 'Next Post', 'aqualuxe' );
        }
        
        $prev_post = get_previous_post();
        $next_post = get_next_post();
        
        $navigation = [
            'prev' => false,
            'next' => false,
        ];
        
        if ( $prev_post ) {
            $navigation['prev'] = [
                'id'    => $prev_post->ID,
                'title' => get_the_title( $prev_post->ID ),
                'url'   => get_permalink( $prev_post->ID ),
                'text'  => $prev,
            ];
        }
        
        if ( $next_post ) {
            $navigation['next'] = [
                'id'    => $next_post->ID,
                'title' => get_the_title( $next_post->ID ),
                'url'   => get_permalink( $next_post->ID ),
                'text'  => $next,
            ];
        }
        
        return $navigation;
    }

    /**
     * Get pagination
     *
     * @param int   $pages   Number of pages
     * @param int   $range   Range of pages
     * @param array $options Pagination options
     * @return array
     */
    public static function get_pagination( $pages = 0, $range = 2, $options = [] ) {
        global $paged, $wp_query;
        
        $options = wp_parse_args(
            $options,
            [
                'prev_text' => __( '&laquo; Previous', 'aqualuxe' ),
                'next_text' => __( 'Next &raquo;', 'aqualuxe' ),
            ]
        );
        
        $showitems = ( $range * 2 ) + 1;
        
        if ( empty( $paged ) ) {
            $paged = 1;
        }
        
        if ( ! $pages ) {
            $pages = $wp_query->max_num_pages;
            
            if ( ! $pages ) {
                $pages = 1;
            }
        }
        
        $pagination = [
            'pages'     => $pages,
            'current'   => $paged,
            'prev'      => false,
            'next'      => false,
            'items'     => [],
            'show_prev' => false,
            'show_next' => false,
        ];
        
        if ( $pages > 1 ) {
            if ( $paged > 1 ) {
                $pagination['prev'] = [
                    'url'  => get_pagenum_link( $paged - 1 ),
                    'text' => $options['prev_text'],
                ];
                
                $pagination['show_prev'] = true;
            }
            
            for ( $i = 1; $i <= $pages; $i++ ) {
                if ( 1 !== $pages && ( ! ( $i >= $paged + $range + 1 || $i <= $paged - $range - 1 ) || $pages <= $showitems ) ) {
                    $pagination['items'][] = [
                        'url'     => get_pagenum_link( $i ),
                        'text'    => $i,
                        'current' => $paged === $i,
                    ];
                }
            }
            
            if ( $paged < $pages ) {
                $pagination['next'] = [
                    'url'  => get_pagenum_link( $paged + 1 ),
                    'text' => $options['next_text'],
                ];
                
                $pagination['show_next'] = true;
            }
        }
        
        return $pagination;
    }

    /**
     * Get breadcrumbs
     *
     * @param array $args Breadcrumbs arguments
     * @return array
     */
    public static function get_breadcrumbs( $args = [] ) {
        $args = wp_parse_args(
            $args,
            [
                'home_text'     => __( 'Home', 'aqualuxe' ),
                'separator'     => '/',
                'show_on_home'  => false,
                'show_on_front' => false,
                'show_current'  => true,
            ]
        );
        
        $breadcrumbs = [];
        
        // Add home link
        $breadcrumbs[] = [
            'url'   => home_url( '/' ),
            'text'  => $args['home_text'],
            'class' => 'home',
        ];
        
        // Front page
        if ( is_front_page() ) {
            if ( ! $args['show_on_front'] ) {
                return [];
            }
            
            if ( $args['show_current'] ) {
                $breadcrumbs[] = [
                    'url'     => false,
                    'text'    => $args['home_text'],
                    'class'   => 'current',
                    'current' => true,
                ];
            }
            
            return $breadcrumbs;
        }
        
        // Home page
        if ( is_home() ) {
            if ( ! $args['show_on_home'] ) {
                return [];
            }
            
            if ( $args['show_current'] ) {
                $breadcrumbs[] = [
                    'url'     => false,
                    'text'    => __( 'Blog', 'aqualuxe' ),
                    'class'   => 'current',
                    'current' => true,
                ];
            }
            
            return $breadcrumbs;
        }
        
        // Single post
        if ( is_single() && 'post' === get_post_type() ) {
            $categories = get_the_category();
            
            if ( $categories ) {
                $category = $categories[0];
                
                $breadcrumbs[] = [
                    'url'   => get_category_link( $category->term_id ),
                    'text'  => $category->name,
                    'class' => 'category',
                ];
            }
            
            if ( $args['show_current'] ) {
                $breadcrumbs[] = [
                    'url'     => false,
                    'text'    => get_the_title(),
                    'class'   => 'current',
                    'current' => true,
                ];
            }
            
            return $breadcrumbs;
        }
        
        // Page
        if ( is_page() && ! is_front_page() ) {
            $post = get_post();
            
            if ( $post->post_parent ) {
                $parent_id = $post->post_parent;
                $parents = [];
                
                while ( $parent_id ) {
                    $page = get_post( $parent_id );
                    $parents[] = [
                        'url'   => get_permalink( $page->ID ),
                        'text'  => get_the_title( $page->ID ),
                        'class' => 'parent',
                    ];
                    $parent_id = $page->post_parent;
                }
                
                $parents = array_reverse( $parents );
                
                foreach ( $parents as $parent ) {
                    $breadcrumbs[] = $parent;
                }
            }
            
            if ( $args['show_current'] ) {
                $breadcrumbs[] = [
                    'url'     => false,
                    'text'    => get_the_title(),
                    'class'   => 'current',
                    'current' => true,
                ];
            }
            
            return $breadcrumbs;
        }
        
        // Category
        if ( is_category() ) {
            $category = get_queried_object();
            
            if ( $category->parent !== 0 ) {
                $parent_categories = [];
                $parent_id = $category->parent;
                
                while ( $parent_id ) {
                    $parent_category = get_term( $parent_id, 'category' );
                    $parent_categories[] = [
                        'url'   => get_category_link( $parent_category->term_id ),
                        'text'  => $parent_category->name,
                        'class' => 'parent',
                    ];
                    $parent_id = $parent_category->parent;
                }
                
                $parent_categories = array_reverse( $parent_categories );
                
                foreach ( $parent_categories as $parent_category ) {
                    $breadcrumbs[] = $parent_category;
                }
            }
            
            if ( $args['show_current'] ) {
                $breadcrumbs[] = [
                    'url'     => false,
                    'text'    => $category->name,
                    'class'   => 'current',
                    'current' => true,
                ];
            }
            
            return $breadcrumbs;
        }
        
        // Tag
        if ( is_tag() ) {
            if ( $args['show_current'] ) {
                $breadcrumbs[] = [
                    'url'     => false,
                    'text'    => sprintf( __( 'Tag: %s', 'aqualuxe' ), single_tag_title( '', false ) ),
                    'class'   => 'current',
                    'current' => true,
                ];
            }
            
            return $breadcrumbs;
        }
        
        // Author
        if ( is_author() ) {
            if ( $args['show_current'] ) {
                $breadcrumbs[] = [
                    'url'     => false,
                    'text'    => sprintf( __( 'Author: %s', 'aqualuxe' ), get_the_author() ),
                    'class'   => 'current',
                    'current' => true,
                ];
            }
            
            return $breadcrumbs;
        }
        
        // Search
        if ( is_search() ) {
            if ( $args['show_current'] ) {
                $breadcrumbs[] = [
                    'url'     => false,
                    'text'    => sprintf( __( 'Search Results for: %s', 'aqualuxe' ), get_search_query() ),
                    'class'   => 'current',
                    'current' => true,
                ];
            }
            
            return $breadcrumbs;
        }
        
        // 404
        if ( is_404() ) {
            if ( $args['show_current'] ) {
                $breadcrumbs[] = [
                    'url'     => false,
                    'text'    => __( '404 Not Found', 'aqualuxe' ),
                    'class'   => 'current',
                    'current' => true,
                ];
            }
            
            return $breadcrumbs;
        }
        
        // Custom post type
        if ( is_post_type_archive() ) {
            if ( $args['show_current'] ) {
                $breadcrumbs[] = [
                    'url'     => false,
                    'text'    => post_type_archive_title( '', false ),
                    'class'   => 'current',
                    'current' => true,
                ];
            }
            
            return $breadcrumbs;
        }
        
        // Custom taxonomy
        if ( is_tax() ) {
            $taxonomy = get_queried_object()->taxonomy;
            $term = get_queried_object();
            
            if ( $term->parent !== 0 ) {
                $parent_terms = [];
                $parent_id = $term->parent;
                
                while ( $parent_id ) {
                    $parent_term = get_term( $parent_id, $taxonomy );
                    $parent_terms[] = [
                        'url'   => get_term_link( $parent_term->term_id, $taxonomy ),
                        'text'  => $parent_term->name,
                        'class' => 'parent',
                    ];
                    $parent_id = $parent_term->parent;
                }
                
                $parent_terms = array_reverse( $parent_terms );
                
                foreach ( $parent_terms as $parent_term ) {
                    $breadcrumbs[] = $parent_term;
                }
            }
            
            if ( $args['show_current'] ) {
                $breadcrumbs[] = [
                    'url'     => false,
                    'text'    => $term->name,
                    'class'   => 'current',
                    'current' => true,
                ];
            }
            
            return $breadcrumbs;
        }
        
        // Custom post type single
        if ( is_singular() && 'post' !== get_post_type() ) {
            $post_type = get_post_type_object( get_post_type() );
            
            if ( $post_type ) {
                $breadcrumbs[] = [
                    'url'   => get_post_type_archive_link( $post_type->name ),
                    'text'  => $post_type->labels->name,
                    'class' => 'post-type',
                ];
            }
            
            if ( $args['show_current'] ) {
                $breadcrumbs[] = [
                    'url'     => false,
                    'text'    => get_the_title(),
                    'class'   => 'current',
                    'current' => true,
                ];
            }
            
            return $breadcrumbs;
        }
        
        return $breadcrumbs;
    }

    /**
     * Get social share links
     *
     * @param int   $post_id Post ID
     * @param array $networks Social networks
     * @return array
     */
    public static function get_social_share_links( $post_id = null, $networks = [] ) {
        if ( ! $post_id ) {
            $post_id = get_the_ID();
        }
        
        if ( empty( $networks ) ) {
            $networks = [ 'facebook', 'twitter', 'linkedin', 'pinterest', 'email' ];
        }
        
        $post_url = get_permalink( $post_id );
        $post_title = get_the_title( $post_id );
        $post_thumbnail = self::get_post_thumbnail_url( $post_id, 'full' );
        
        $share_links = [];
        
        foreach ( $networks as $network ) {
            switch ( $network ) {
                case 'facebook':
                    $share_links['facebook'] = [
                        'url'   => 'https://www.facebook.com/sharer/sharer.php?u=' . urlencode( $post_url ),
                        'label' => __( 'Share on Facebook', 'aqualuxe' ),
                        'icon'  => 'facebook',
                    ];
                    break;
                
                case 'twitter':
                    $share_links['twitter'] = [
                        'url'   => 'https://twitter.com/intent/tweet?url=' . urlencode( $post_url ) . '&text=' . urlencode( $post_title ),
                        'label' => __( 'Share on Twitter', 'aqualuxe' ),
                        'icon'  => 'twitter',
                    ];
                    break;
                
                case 'linkedin':
                    $share_links['linkedin'] = [
                        'url'   => 'https://www.linkedin.com/shareArticle?mini=true&url=' . urlencode( $post_url ) . '&title=' . urlencode( $post_title ),
                        'label' => __( 'Share on LinkedIn', 'aqualuxe' ),
                        'icon'  => 'linkedin',
                    ];
                    break;
                
                case 'pinterest':
                    if ( $post_thumbnail ) {
                        $share_links['pinterest'] = [
                            'url'   => 'https://pinterest.com/pin/create/button/?url=' . urlencode( $post_url ) . '&media=' . urlencode( $post_thumbnail ) . '&description=' . urlencode( $post_title ),
                            'label' => __( 'Share on Pinterest', 'aqualuxe' ),
                            'icon'  => 'pinterest',
                        ];
                    }
                    break;
                
                case 'email':
                    $share_links['email'] = [
                        'url'   => 'mailto:?subject=' . urlencode( $post_title ) . '&body=' . urlencode( $post_url ),
                        'label' => __( 'Share via Email', 'aqualuxe' ),
                        'icon'  => 'email',
                    ];
                    break;
            }
        }
        
        return $share_links;
    }

    /**
     * Get schema.org markup
     *
     * @param string $type Schema type
     * @param array  $data Schema data
     * @return array
     */
    public static function get_schema_markup( $type, $data = [] ) {
        $schema = [
            '@context' => 'https://schema.org',
            '@type'    => $type,
        ];
        
        return array_merge( $schema, $data );
    }

    /**
     * Get Open Graph tags
     *
     * @param int   $post_id Post ID
     * @param array $args    Open Graph arguments
     * @return array
     */
    public static function get_open_graph_tags( $post_id = null, $args = [] ) {
        if ( ! $post_id ) {
            $post_id = get_the_ID();
        }
        
        $args = wp_parse_args(
            $args,
            [
                'title'       => get_the_title( $post_id ),
                'description' => self::get_excerpt( $post_id, 55, false ),
                'url'         => get_permalink( $post_id ),
                'type'        => 'article',
                'image'       => self::get_post_thumbnail_url( $post_id, 'full' ),
                'site_name'   => get_bloginfo( 'name' ),
                'locale'      => get_locale(),
            ]
        );
        
        $tags = [
            'og:title'       => $args['title'],
            'og:description' => $args['description'],
            'og:url'         => $args['url'],
            'og:type'        => $args['type'],
            'og:site_name'   => $args['site_name'],
            'og:locale'      => $args['locale'],
        ];
        
        if ( $args['image'] ) {
            $tags['og:image'] = $args['image'];
        }
        
        return $tags;
    }

    /**
     * Get Twitter Card tags
     *
     * @param int   $post_id Post ID
     * @param array $args    Twitter Card arguments
     * @return array
     */
    public static function get_twitter_card_tags( $post_id = null, $args = [] ) {
        if ( ! $post_id ) {
            $post_id = get_the_ID();
        }
        
        $args = wp_parse_args(
            $args,
            [
                'card'        => 'summary_large_image',
                'title'       => get_the_title( $post_id ),
                'description' => self::get_excerpt( $post_id, 55, false ),
                'image'       => self::get_post_thumbnail_url( $post_id, 'full' ),
                'site'        => '',
                'creator'     => '',
            ]
        );
        
        $tags = [
            'twitter:card'        => $args['card'],
            'twitter:title'       => $args['title'],
            'twitter:description' => $args['description'],
        ];
        
        if ( $args['image'] ) {
            $tags['twitter:image'] = $args['image'];
        }
        
        if ( $args['site'] ) {
            $tags['twitter:site'] = $args['site'];
        }
        
        if ( $args['creator'] ) {
            $tags['twitter:creator'] = $args['creator'];
        }
        
        return $tags;
    }

    /**
     * Get theme option
     *
     * @param string $option  Option name
     * @param mixed  $default Default value
     * @return mixed
     */
    public static function get_theme_option( $option, $default = false ) {
        return get_theme_mod( 'aqualuxe_' . $option, $default );
    }

    /**
     * Get theme color
     *
     * @param string $color   Color name
     * @param string $default Default color
     * @return string
     */
    public static function get_theme_color( $color, $default = '' ) {
        return self::get_theme_option( 'color_' . $color, $default );
    }

    /**
     * Get theme font
     *
     * @param string $font    Font name
     * @param string $default Default font
     * @return string
     */
    public static function get_theme_font( $font, $default = '' ) {
        return self::get_theme_option( 'font_' . $font, $default );
    }

    /**
     * Get theme layout
     *
     * @param string $default Default layout
     * @return string
     */
    public static function get_theme_layout( $default = 'right-sidebar' ) {
        return self::get_theme_option( 'layout', $default );
    }

    /**
     * Get theme logo
     *
     * @param string $size Logo size
     * @return string|false
     */
    public static function get_theme_logo( $size = 'full' ) {
        $custom_logo_id = get_theme_mod( 'custom_logo' );
        
        if ( ! $custom_logo_id ) {
            return false;
        }
        
        $logo = wp_get_attachment_image_src( $custom_logo_id, $size );
        
        if ( ! $logo ) {
            return false;
        }
        
        return $logo[0];
    }

    /**
     * Get theme favicon
     *
     * @return string|false
     */
    public static function get_theme_favicon() {
        $site_icon_id = get_option( 'site_icon' );
        
        if ( ! $site_icon_id ) {
            return false;
        }
        
        $favicon = wp_get_attachment_image_src( $site_icon_id, 'full' );
        
        if ( ! $favicon ) {
            return false;
        }
        
        return $favicon[0];
    }

    /**
     * Get theme header
     *
     * @return string|false
     */
    public static function get_theme_header() {
        $header_image = get_header_image();
        
        if ( ! $header_image ) {
            return false;
        }
        
        return $header_image;
    }

    /**
     * Get theme background
     *
     * @return array|false
     */
    public static function get_theme_background() {
        $background = get_background_image();
        
        if ( ! $background ) {
            return false;
        }
        
        return [
            'image'      => $background,
            'color'      => get_background_color(),
            'repeat'     => get_theme_mod( 'background_repeat', 'repeat' ),
            'position_x' => get_theme_mod( 'background_position_x', 'left' ),
            'position_y' => get_theme_mod( 'background_position_y', 'top' ),
            'attachment' => get_theme_mod( 'background_attachment', 'scroll' ),
            'size'       => get_theme_mod( 'background_size', 'auto' ),
        ];
    }

    /**
     * Get theme social links
     *
     * @return array
     */
    public static function get_theme_social_links() {
        $social_links = [];
        
        $networks = [
            'facebook',
            'twitter',
            'instagram',
            'linkedin',
            'youtube',
            'pinterest',
            'tiktok',
            'snapchat',
            'whatsapp',
            'telegram',
            'discord',
            'github',
            'dribbble',
            'behance',
            'medium',
            'reddit',
            'tumblr',
            'vimeo',
            'flickr',
            'twitch',
        ];
        
        foreach ( $networks as $network ) {
            $url = self::get_theme_option( 'social_' . $network );
            
            if ( $url ) {
                $social_links[ $network ] = [
                    'url'   => $url,
                    'label' => ucfirst( $network ),
                    'icon'  => $network,
                ];
            }
        }
        
        return $social_links;
    }

    /**
     * Get theme contact info
     *
     * @return array
     */
    public static function get_theme_contact_info() {
        $contact_info = [];
        
        $phone = self::get_theme_option( 'contact_phone' );
        $email = self::get_theme_option( 'contact_email' );
        $address = self::get_theme_option( 'contact_address' );
        $hours = self::get_theme_option( 'contact_hours' );
        
        if ( $phone ) {
            $contact_info['phone'] = [
                'value' => $phone,
                'label' => __( 'Phone', 'aqualuxe' ),
                'icon'  => 'phone',
                'url'   => 'tel:' . preg_replace( '/[^0-9+]/', '', $phone ),
            ];
        }
        
        if ( $email ) {
            $contact_info['email'] = [
                'value' => $email,
                'label' => __( 'Email', 'aqualuxe' ),
                'icon'  => 'email',
                'url'   => 'mailto:' . $email,
            ];
        }
        
        if ( $address ) {
            $contact_info['address'] = [
                'value' => $address,
                'label' => __( 'Address', 'aqualuxe' ),
                'icon'  => 'map-marker',
                'url'   => 'https://maps.google.com/?q=' . urlencode( $address ),
            ];
        }
        
        if ( $hours ) {
            $contact_info['hours'] = [
                'value' => $hours,
                'label' => __( 'Hours', 'aqualuxe' ),
                'icon'  => 'clock',
                'url'   => false,
            ];
        }
        
        return $contact_info;
    }

    /**
     * Get theme copyright
     *
     * @return string
     */
    public static function get_theme_copyright() {
        $copyright = self::get_theme_option( 'copyright' );
        
        if ( ! $copyright ) {
            $copyright = sprintf(
                /* translators: %1$s: Current year, %2$s: Site name */
                __( '&copy; %1$s %2$s. All rights reserved.', 'aqualuxe' ),
                date( 'Y' ),
                get_bloginfo( 'name' )
            );
        }
        
        return $copyright;
    }

    /**
     * Get theme footer text
     *
     * @return string
     */
    public static function get_theme_footer_text() {
        $footer_text = self::get_theme_option( 'footer_text' );
        
        if ( ! $footer_text ) {
            $footer_text = sprintf(
                /* translators: %1$s: Theme name, %2$s: Theme author */
                __( 'Powered by %1$s theme by %2$s.', 'aqualuxe' ),
                'AquaLuxe',
                '<a href="https://ninjatech.ai" target="_blank" rel="noopener noreferrer">NinjaTech AI</a>'
            );
        }
        
        return $footer_text;
    }

    /**
     * Get theme custom CSS
     *
     * @return string
     */
    public static function get_theme_custom_css() {
        return self::get_theme_option( 'custom_css', '' );
    }

    /**
     * Get theme custom JavaScript
     *
     * @return string
     */
    public static function get_theme_custom_js() {
        return self::get_theme_option( 'custom_js', '' );
    }

    /**
     * Get theme Google Analytics ID
     *
     * @return string
     */
    public static function get_theme_google_analytics() {
        return self::get_theme_option( 'google_analytics', '' );
    }

    /**
     * Get theme Facebook Pixel ID
     *
     * @return string
     */
    public static function get_theme_facebook_pixel() {
        return self::get_theme_option( 'facebook_pixel', '' );
    }

    /**
     * Get theme Google Maps API Key
     *
     * @return string
     */
    public static function get_theme_google_maps_api_key() {
        return self::get_theme_option( 'google_maps_api_key', '' );
    }

    /**
     * Get theme reCAPTCHA Site Key
     *
     * @return string
     */
    public static function get_theme_recaptcha_site_key() {
        return self::get_theme_option( 'recaptcha_site_key', '' );
    }

    /**
     * Get theme reCAPTCHA Secret Key
     *
     * @return string
     */
    public static function get_theme_recaptcha_secret_key() {
        return self::get_theme_option( 'recaptcha_secret_key', '' );
    }

    /**
     * Get theme Mailchimp API Key
     *
     * @return string
     */
    public static function get_theme_mailchimp_api_key() {
        return self::get_theme_option( 'mailchimp_api_key', '' );
    }

    /**
     * Get theme Mailchimp List ID
     *
     * @return string
     */
    public static function get_theme_mailchimp_list_id() {
        return self::get_theme_option( 'mailchimp_list_id', '' );
    }

    /**
     * Get theme Instagram Access Token
     *
     * @return string
     */
    public static function get_theme_instagram_access_token() {
        return self::get_theme_option( 'instagram_access_token', '' );
    }

    /**
     * Get theme Twitter API Key
     *
     * @return string
     */
    public static function get_theme_twitter_api_key() {
        return self::get_theme_option( 'twitter_api_key', '' );
    }

    /**
     * Get theme Twitter API Secret
     *
     * @return string
     */
    public static function get_theme_twitter_api_secret() {
        return self::get_theme_option( 'twitter_api_secret', '' );
    }

    /**
     * Get theme Twitter Access Token
     *
     * @return string
     */
    public static function get_theme_twitter_access_token() {
        return self::get_theme_option( 'twitter_access_token', '' );
    }

    /**
     * Get theme Twitter Access Token Secret
     *
     * @return string
     */
    public static function get_theme_twitter_access_token_secret() {
        return self::get_theme_option( 'twitter_access_token_secret', '' );
    }
}