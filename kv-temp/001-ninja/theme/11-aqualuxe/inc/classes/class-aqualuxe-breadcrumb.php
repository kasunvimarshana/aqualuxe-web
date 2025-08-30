<?php
/**
 * Breadcrumb Class
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

/**
 * AquaLuxe_Breadcrumb class
 */
class AquaLuxe_Breadcrumb {

    /**
     * Breadcrumb trail
     *
     * @var array
     */
    private $breadcrumb_trail = array();

    /**
     * Breadcrumb args
     *
     * @var array
     */
    private $args = array();

    /**
     * Constructor
     *
     * @param array $args Arguments.
     */
    public function __construct( $args = array() ) {
        $defaults = array(
            'container'     => 'nav',
            'container_id'  => 'breadcrumbs',
            'container_class' => 'breadcrumbs',
            'list_tag'      => 'ol',
            'list_class'    => 'breadcrumb',
            'item_tag'      => 'li',
            'item_class'    => 'breadcrumb-item',
            'active_class'  => 'active',
            'separator'     => '<i class="fas fa-chevron-right"></i>',
            'home_title'    => esc_html__( 'Home', 'aqualuxe' ),
            'home_icon'     => '<i class="fas fa-home"></i>',
            'show_on_home'  => false,
            'show_browse'   => false,
            'echo'          => true,
            'schema'        => true,
        );

        $this->args = apply_filters( 'aqualuxe_breadcrumb_args', wp_parse_args( $args, $defaults ) );
    }

    /**
     * Build the breadcrumb trail
     *
     * @return string
     */
    public function build_breadcrumb() {
        // Add home link
        $this->add_home_link();

        // Add breadcrumbs based on current page
        if ( is_home() ) {
            // Blog page
            $this->add_blog_page();
        } elseif ( is_singular() ) {
            // Single post or page
            $this->add_singular();
        } elseif ( is_archive() ) {
            // Archive page
            $this->add_archive();
        } elseif ( is_search() ) {
            // Search page
            $this->add_search_page();
        } elseif ( is_404() ) {
            // 404 page
            $this->add_404_page();
        }

        // Return the breadcrumb trail
        return $this->get_breadcrumb();
    }

    /**
     * Add home link to breadcrumb trail
     */
    private function add_home_link() {
        $home_title = $this->args['home_icon'] . ' ' . $this->args['home_title'];
        $this->add_breadcrumb_item( $home_title, home_url( '/' ) );
    }

    /**
     * Add blog page to breadcrumb trail
     */
    private function add_blog_page() {
        $blog_title = get_the_title( get_option( 'page_for_posts', true ) );
        $this->add_breadcrumb_item( $blog_title, '', true );
    }

    /**
     * Add singular post or page to breadcrumb trail
     */
    private function add_singular() {
        // Get post type
        $post_type = get_post_type();

        // Add post type archive link if available
        if ( $post_type !== 'page' && $post_type !== 'post' ) {
            $post_type_object = get_post_type_object( $post_type );
            if ( $post_type_object && $post_type_object->has_archive ) {
                $archive_title = $post_type_object->labels->name;
                $archive_url = get_post_type_archive_link( $post_type );
                $this->add_breadcrumb_item( $archive_title, $archive_url );
            }
        }

        // Add categories for posts
        if ( $post_type === 'post' ) {
            // Add blog page if available
            $blog_page_id = get_option( 'page_for_posts' );
            if ( $blog_page_id ) {
                $blog_title = get_the_title( $blog_page_id );
                $blog_url = get_permalink( $blog_page_id );
                $this->add_breadcrumb_item( $blog_title, $blog_url );
            }

            // Add primary category
            $categories = get_the_category();
            if ( ! empty( $categories ) ) {
                $category = $categories[0];
                $this->add_category_ancestors( $category );
            }
        }

        // Add WooCommerce shop page for products
        if ( $post_type === 'product' && class_exists( 'WooCommerce' ) ) {
            $shop_page_id = wc_get_page_id( 'shop' );
            if ( $shop_page_id > 0 ) {
                $shop_title = get_the_title( $shop_page_id );
                $shop_url = get_permalink( $shop_page_id );
                $this->add_breadcrumb_item( $shop_title, $shop_url );
            }

            // Add product categories
            $product_categories = get_the_terms( get_the_ID(), 'product_cat' );
            if ( ! empty( $product_categories ) && ! is_wp_error( $product_categories ) ) {
                $product_category = $product_categories[0];
                $this->add_term_ancestors( $product_category, 'product_cat' );
            }
        }

        // Add parent pages for hierarchical pages
        if ( $post_type === 'page' ) {
            $ancestors = get_post_ancestors( get_the_ID() );
            if ( ! empty( $ancestors ) ) {
                $ancestors = array_reverse( $ancestors );
                foreach ( $ancestors as $ancestor ) {
                    $this->add_breadcrumb_item( get_the_title( $ancestor ), get_permalink( $ancestor ) );
                }
            }
        }

        // Add current post/page title
        $this->add_breadcrumb_item( get_the_title(), '', true );
    }

    /**
     * Add archive page to breadcrumb trail
     */
    private function add_archive() {
        if ( is_category() ) {
            // Category archive
            $category = get_queried_object();
            $this->add_category_ancestors( $category );
            $this->add_breadcrumb_item( single_cat_title( '', false ), '', true );
        } elseif ( is_tag() ) {
            // Tag archive
            $this->add_breadcrumb_item( sprintf( esc_html__( 'Tag: %s', 'aqualuxe' ), single_tag_title( '', false ) ), '', true );
        } elseif ( is_author() ) {
            // Author archive
            $this->add_breadcrumb_item( sprintf( esc_html__( 'Author: %s', 'aqualuxe' ), get_the_author() ), '', true );
        } elseif ( is_day() ) {
            // Day archive
            $this->add_breadcrumb_item( sprintf( esc_html__( 'Day: %s', 'aqualuxe' ), get_the_date() ), '', true );
        } elseif ( is_month() ) {
            // Month archive
            $this->add_breadcrumb_item( sprintf( esc_html__( 'Month: %s', 'aqualuxe' ), get_the_date( 'F Y' ) ), '', true );
        } elseif ( is_year() ) {
            // Year archive
            $this->add_breadcrumb_item( sprintf( esc_html__( 'Year: %s', 'aqualuxe' ), get_the_date( 'Y' ) ), '', true );
        } elseif ( is_tax() ) {
            // Taxonomy archive
            $term = get_queried_object();
            $taxonomy = get_taxonomy( $term->taxonomy );
            
            // Add WooCommerce shop page for product taxonomies
            if ( class_exists( 'WooCommerce' ) && ( $term->taxonomy === 'product_cat' || $term->taxonomy === 'product_tag' ) ) {
                $shop_page_id = wc_get_page_id( 'shop' );
                if ( $shop_page_id > 0 ) {
                    $shop_title = get_the_title( $shop_page_id );
                    $shop_url = get_permalink( $shop_page_id );
                    $this->add_breadcrumb_item( $shop_title, $shop_url );
                }
            }
            
            // Add term ancestors
            $this->add_term_ancestors( $term, $term->taxonomy );
            
            // Add current term
            $this->add_breadcrumb_item( single_term_title( '', false ), '', true );
        } elseif ( is_post_type_archive() ) {
            // Post type archive
            $post_type = get_post_type();
            $post_type_object = get_post_type_object( $post_type );
            
            // Special handling for WooCommerce shop page
            if ( class_exists( 'WooCommerce' ) && $post_type === 'product' ) {
                $shop_page_id = wc_get_page_id( 'shop' );
                if ( $shop_page_id > 0 ) {
                    $shop_title = get_the_title( $shop_page_id );
                    $this->add_breadcrumb_item( $shop_title, '', true );
                } else {
                    $this->add_breadcrumb_item( $post_type_object->labels->name, '', true );
                }
            } else {
                $this->add_breadcrumb_item( $post_type_object->labels->name, '', true );
            }
        }
    }

    /**
     * Add search page to breadcrumb trail
     */
    private function add_search_page() {
        $this->add_breadcrumb_item( sprintf( esc_html__( 'Search Results for: %s', 'aqualuxe' ), get_search_query() ), '', true );
    }

    /**
     * Add 404 page to breadcrumb trail
     */
    private function add_404_page() {
        $this->add_breadcrumb_item( esc_html__( 'Page Not Found', 'aqualuxe' ), '', true );
    }

    /**
     * Add category ancestors to breadcrumb trail
     *
     * @param object $category Category object.
     */
    private function add_category_ancestors( $category ) {
        if ( $category->parent ) {
            $ancestors = get_ancestors( $category->term_id, 'category' );
            $ancestors = array_reverse( $ancestors );
            
            foreach ( $ancestors as $ancestor_id ) {
                $ancestor = get_term( $ancestor_id, 'category' );
                $this->add_breadcrumb_item( $ancestor->name, get_term_link( $ancestor ) );
            }
        }
        
        // Add blog page if available
        $blog_page_id = get_option( 'page_for_posts' );
        if ( $blog_page_id ) {
            $blog_title = get_the_title( $blog_page_id );
            $blog_url = get_permalink( $blog_page_id );
            $this->add_breadcrumb_item( $blog_title, $blog_url );
        }
    }

    /**
     * Add term ancestors to breadcrumb trail
     *
     * @param object $term     Term object.
     * @param string $taxonomy Taxonomy name.
     */
    private function add_term_ancestors( $term, $taxonomy ) {
        if ( $term->parent ) {
            $ancestors = get_ancestors( $term->term_id, $taxonomy );
            $ancestors = array_reverse( $ancestors );
            
            foreach ( $ancestors as $ancestor_id ) {
                $ancestor = get_term( $ancestor_id, $taxonomy );
                $this->add_breadcrumb_item( $ancestor->name, get_term_link( $ancestor ) );
            }
        }
    }

    /**
     * Add breadcrumb item to trail
     *
     * @param string  $title  Item title.
     * @param string  $url    Item URL.
     * @param boolean $active Whether item is active.
     */
    private function add_breadcrumb_item( $title, $url = '', $active = false ) {
        $this->breadcrumb_trail[] = array(
            'title'  => $title,
            'url'    => $url,
            'active' => $active,
        );
    }

    /**
     * Get breadcrumb trail HTML
     *
     * @return string
     */
    private function get_breadcrumb() {
        // Return empty if no breadcrumb items
        if ( empty( $this->breadcrumb_trail ) ) {
            return '';
        }

        // Return empty if on home page and show_on_home is false
        if ( is_front_page() && ! $this->args['show_on_home'] ) {
            return '';
        }

        // Build breadcrumb HTML
        $html = '';

        // Open container
        if ( $this->args['container'] ) {
            $container_class = $this->args['container_class'] ? ' class="' . esc_attr( $this->args['container_class'] ) . '"' : '';
            $container_id = $this->args['container_id'] ? ' id="' . esc_attr( $this->args['container_id'] ) . '"' : '';
            $html .= '<' . esc_attr( $this->args['container'] ) . $container_class . $container_id;
            
            // Add schema markup
            if ( $this->args['schema'] ) {
                $html .= ' itemscope itemtype="http://schema.org/BreadcrumbList" aria-label="' . esc_attr__( 'Breadcrumb', 'aqualuxe' ) . '"';
            }
            
            $html .= '>';
        }

        // Open list
        if ( $this->args['list_tag'] ) {
            $list_class = $this->args['list_class'] ? ' class="' . esc_attr( $this->args['list_class'] ) . '"' : '';
            $html .= '<' . esc_attr( $this->args['list_tag'] ) . $list_class . '>';
        }

        // Add breadcrumb items
        $count = count( $this->breadcrumb_trail );
        $i = 1;

        foreach ( $this->breadcrumb_trail as $item ) {
            $is_last = ( $i === $count );
            $item_class = $this->args['item_class'];
            
            if ( $item['active'] ) {
                $item_class .= ' ' . $this->args['active_class'];
            }

            // Open item
            $html .= '<' . esc_attr( $this->args['item_tag'] ) . ' class="' . esc_attr( $item_class ) . '"';
            
            // Add schema markup
            if ( $this->args['schema'] ) {
                $html .= ' itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"';
            }
            
            $html .= '>';

            // Add link if not active
            if ( ! empty( $item['url'] ) && ! $item['active'] ) {
                $html .= '<a href="' . esc_url( $item['url'] ) . '"';
                
                // Add schema markup
                if ( $this->args['schema'] ) {
                    $html .= ' itemprop="item"';
                }
                
                $html .= '>';
                
                // Add schema markup
                if ( $this->args['schema'] ) {
                    $html .= '<span itemprop="name">';
                }
                
                $html .= wp_kses_post( $item['title'] );
                
                // Add schema markup
                if ( $this->args['schema'] ) {
                    $html .= '</span>';
                }
                
                $html .= '</a>';
            } else {
                // Add schema markup
                if ( $this->args['schema'] ) {
                    $html .= '<span itemprop="name">';
                }
                
                $html .= wp_kses_post( $item['title'] );
                
                // Add schema markup
                if ( $this->args['schema'] ) {
                    $html .= '</span>';
                }
            }

            // Add schema markup
            if ( $this->args['schema'] ) {
                $html .= '<meta itemprop="position" content="' . esc_attr( $i ) . '" />';
            }

            // Close item
            $html .= '</' . esc_attr( $this->args['item_tag'] ) . '>';

            // Add separator
            if ( ! $is_last && $this->args['separator'] ) {
                $html .= '<span class="breadcrumb-separator">' . wp_kses_post( $this->args['separator'] ) . '</span>';
            }

            $i++;
        }

        // Close list
        if ( $this->args['list_tag'] ) {
            $html .= '</' . esc_attr( $this->args['list_tag'] ) . '>';
        }

        // Close container
        if ( $this->args['container'] ) {
            $html .= '</' . esc_attr( $this->args['container'] ) . '>';
        }

        // Return or echo breadcrumb
        if ( $this->args['echo'] ) {
            echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        } else {
            return $html;
        }
    }
}

/**
 * Display breadcrumb
 *
 * @param array $args Arguments.
 */
function aqualuxe_breadcrumb( $args = array() ) {
    $breadcrumb = new AquaLuxe_Breadcrumb( $args );
    $breadcrumb->build_breadcrumb();
}