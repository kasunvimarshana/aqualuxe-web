<?php
/**
 * AquaLuxe Template Functions
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Add classes to body
 *
 * @param array $classes Body classes
 * @return array Modified body classes
 */
function aqualuxe_body_classes( $classes ) {
    // Add class for dark mode
    if ( aqualuxe_is_dark_mode() ) {
        $classes[] = 'dark-mode';
    }
    
    // Add class for WooCommerce
    if ( aqualuxe_is_woocommerce_active() ) {
        $classes[] = 'woocommerce-active';
    } else {
        $classes[] = 'woocommerce-inactive';
    }
    
    // Add class for RTL
    if ( is_rtl() ) {
        $classes[] = 'rtl';
    }
    
    // Add class for language
    $classes[] = 'lang-' . sanitize_html_class( aqualuxe_get_current_language() );
    
    // Add class for page template
    if ( is_page() ) {
        $template = get_page_template_slug();
        if ( $template ) {
            $classes[] = 'page-template-' . sanitize_html_class( str_replace( '.php', '', $template ) );
        }
    }
    
    // Add class for sidebar
    if ( is_active_sidebar( 'sidebar-1' ) && ! is_page() ) {
        $classes[] = 'has-sidebar';
    } else {
        $classes[] = 'no-sidebar';
    }
    
    return $classes;
}
add_filter( 'body_class', 'aqualuxe_body_classes' );

/**
 * Add classes to post
 *
 * @param array $classes Post classes
 * @return array Modified post classes
 */
function aqualuxe_post_classes( $classes ) {
    // Add class for featured image
    if ( has_post_thumbnail() ) {
        $classes[] = 'has-thumbnail';
    } else {
        $classes[] = 'no-thumbnail';
    }
    
    return $classes;
}
add_filter( 'post_class', 'aqualuxe_post_classes' );

/**
 * Add schema markup to HTML tag
 *
 * @param array $attributes HTML attributes
 * @return array Modified HTML attributes
 */
function aqualuxe_html_schema( $attributes ) {
    $attributes['itemscope'] = '';
    $attributes['itemtype'] = 'https://schema.org/WebPage';
    
    return $attributes;
}
add_filter( 'aqualuxe_html_attributes', 'aqualuxe_html_schema' );

/**
 * Add Open Graph meta tags
 */
function aqualuxe_add_opengraph_tags() {
    // Check if SEO plugin is active
    if ( class_exists( 'WPSEO_Frontend' ) || class_exists( 'All_in_One_SEO_Pack' ) ) {
        return;
    }
    
    // Basic Open Graph tags
    echo '<meta property="og:locale" content="' . esc_attr( get_locale() ) . '" />' . "\n";
    echo '<meta property="og:type" content="website" />' . "\n";
    echo '<meta property="og:title" content="' . esc_attr( wp_get_document_title() ) . '" />' . "\n";
    echo '<meta property="og:url" content="' . esc_url( get_permalink() ) . '" />' . "\n";
    echo '<meta property="og:site_name" content="' . esc_attr( get_bloginfo( 'name' ) ) . '" />' . "\n";
    
    // Description
    if ( is_singular() ) {
        $description = get_the_excerpt();
    } else {
        $description = get_bloginfo( 'description' );
    }
    
    if ( $description ) {
        echo '<meta property="og:description" content="' . esc_attr( $description ) . '" />' . "\n";
    }
    
    // Image
    if ( is_singular() && has_post_thumbnail() ) {
        $image = wp_get_attachment_image_src( get_post_thumbnail_id(), 'large' );
        if ( $image ) {
            echo '<meta property="og:image" content="' . esc_url( $image[0] ) . '" />' . "\n";
            echo '<meta property="og:image:width" content="' . esc_attr( $image[1] ) . '" />' . "\n";
            echo '<meta property="og:image:height" content="' . esc_attr( $image[2] ) . '" />' . "\n";
        }
    }
}
add_action( 'wp_head', 'aqualuxe_add_opengraph_tags' );

/**
 * Add schema.org markup
 *
 * @param array $attributes HTML attributes
 * @param string $context Context
 * @return array Modified HTML attributes
 */
function aqualuxe_add_schema_markup( $attributes, $context ) {
    switch ( $context ) {
        case 'header':
            $attributes['itemscope'] = '';
            $attributes['itemtype'] = 'https://schema.org/WPHeader';
            break;
            
        case 'footer':
            $attributes['itemscope'] = '';
            $attributes['itemtype'] = 'https://schema.org/WPFooter';
            break;
            
        case 'main':
            $attributes['itemscope'] = '';
            $attributes['itemtype'] = 'https://schema.org/WebPageElement';
            $attributes['itemprop'] = 'mainContentOfPage';
            break;
            
        case 'sidebar':
            $attributes['itemscope'] = '';
            $attributes['itemtype'] = 'https://schema.org/WPSideBar';
            break;
            
        case 'navigation':
            $attributes['itemscope'] = '';
            $attributes['itemtype'] = 'https://schema.org/SiteNavigationElement';
            break;
            
        case 'article':
            $attributes['itemscope'] = '';
            $attributes['itemtype'] = 'https://schema.org/Article';
            break;
            
        case 'product':
            $attributes['itemscope'] = '';
            $attributes['itemtype'] = 'https://schema.org/Product';
            break;
    }
    
    return $attributes;
}
add_filter( 'aqualuxe_attr', 'aqualuxe_add_schema_markup', 10, 2 );

/**
 * Get HTML attributes
 *
 * @param string $context Context
 * @return string HTML attributes
 */
function aqualuxe_get_attr( $context ) {
    $attributes = [];
    
    // Apply filters
    $attributes = apply_filters( 'aqualuxe_attr', $attributes, $context );
    $attributes = apply_filters( "aqualuxe_attr_{$context}", $attributes, $context );
    
    // Convert attributes to string
    $output = '';
    foreach ( $attributes as $name => $value ) {
        if ( $value === '' ) {
            $output .= esc_html( $name ) . ' ';
        } else {
            $output .= sprintf( '%s="%s" ', esc_html( $name ), esc_attr( $value ) );
        }
    }
    
    return trim( $output );
}

/**
 * Print HTML attributes
 *
 * @param string $context Context
 */
function aqualuxe_attr( $context ) {
    echo aqualuxe_get_attr( $context );
}

/**
 * Get HTML classes
 *
 * @param string $context Context
 * @param string|array $class Additional classes
 * @return string HTML classes
 */
function aqualuxe_get_classes( $context, $class = '' ) {
    $classes = [];
    
    // Add context class
    $classes[] = $context;
    
    // Add additional classes
    if ( ! empty( $class ) ) {
        if ( ! is_array( $class ) ) {
            $class = preg_split( '#\s+#', $class );
        }
        $classes = array_merge( $classes, $class );
    }
    
    // Apply filters
    $classes = apply_filters( 'aqualuxe_classes', $classes, $context );
    $classes = apply_filters( "aqualuxe_classes_{$context}", $classes, $context );
    
    // Remove duplicates and sanitize
    $classes = array_unique( $classes );
    $classes = array_map( 'sanitize_html_class', $classes );
    
    return implode( ' ', $classes );
}

/**
 * Print HTML classes
 *
 * @param string $context Context
 * @param string|array $class Additional classes
 */
function aqualuxe_classes( $context, $class = '' ) {
    echo aqualuxe_get_classes( $context, $class );
}

/**
 * Get page title
 *
 * @return string Page title
 */
function aqualuxe_get_page_title() {
    if ( is_home() ) {
        if ( get_option( 'page_for_posts' ) ) {
            return get_the_title( get_option( 'page_for_posts' ) );
        } else {
            return esc_html__( 'Blog', 'aqualuxe' );
        }
    } elseif ( is_archive() ) {
        return get_the_archive_title();
    } elseif ( is_search() ) {
        return sprintf( esc_html__( 'Search Results for: %s', 'aqualuxe' ), '<span>' . get_search_query() . '</span>' );
    } elseif ( is_404() ) {
        return esc_html__( 'Page Not Found', 'aqualuxe' );
    } elseif ( is_singular() ) {
        return get_the_title();
    }
    
    return '';
}

/**
 * Get breadcrumbs
 *
 * @return string Breadcrumbs HTML
 */
function aqualuxe_get_breadcrumbs() {
    // Check if WooCommerce is active
    if ( aqualuxe_is_woocommerce_active() && function_exists( 'woocommerce_breadcrumb' ) && ( is_woocommerce() || is_cart() || is_checkout() || is_account_page() ) ) {
        ob_start();
        woocommerce_breadcrumb();
        return ob_get_clean();
    }
    
    // Home link
    $breadcrumbs = [
        [
            'title' => esc_html__( 'Home', 'aqualuxe' ),
            'url' => home_url( '/' ),
        ],
    ];
    
    // Add breadcrumbs based on current page
    if ( is_home() ) {
        if ( get_option( 'page_for_posts' ) ) {
            $breadcrumbs[] = [
                'title' => get_the_title( get_option( 'page_for_posts' ) ),
                'url' => '',
            ];
        } else {
            $breadcrumbs[] = [
                'title' => esc_html__( 'Blog', 'aqualuxe' ),
                'url' => '',
            ];
        }
    } elseif ( is_category() ) {
        $breadcrumbs[] = [
            'title' => single_cat_title( '', false ),
            'url' => '',
        ];
    } elseif ( is_tag() ) {
        $breadcrumbs[] = [
            'title' => single_tag_title( '', false ),
            'url' => '',
        ];
    } elseif ( is_author() ) {
        $breadcrumbs[] = [
            'title' => get_the_author(),
            'url' => '',
        ];
    } elseif ( is_year() ) {
        $breadcrumbs[] = [
            'title' => get_the_date( 'Y' ),
            'url' => '',
        ];
    } elseif ( is_month() ) {
        $breadcrumbs[] = [
            'title' => get_the_date( 'F Y' ),
            'url' => '',
        ];
    } elseif ( is_day() ) {
        $breadcrumbs[] = [
            'title' => get_the_date(),
            'url' => '',
        ];
    } elseif ( is_singular( 'post' ) ) {
        if ( get_option( 'page_for_posts' ) ) {
            $breadcrumbs[] = [
                'title' => get_the_title( get_option( 'page_for_posts' ) ),
                'url' => get_permalink( get_option( 'page_for_posts' ) ),
            ];
        } else {
            $breadcrumbs[] = [
                'title' => esc_html__( 'Blog', 'aqualuxe' ),
                'url' => home_url( '/' ),
            ];
        }
        
        $breadcrumbs[] = [
            'title' => get_the_title(),
            'url' => '',
        ];
    } elseif ( is_singular() ) {
        $post_type = get_post_type();
        
        if ( $post_type !== 'page' ) {
            $post_type_object = get_post_type_object( $post_type );
            
            if ( $post_type_object ) {
                $breadcrumbs[] = [
                    'title' => $post_type_object->labels->name,
                    'url' => get_post_type_archive_link( $post_type ),
                ];
            }
        }
        
        $breadcrumbs[] = [
            'title' => get_the_title(),
            'url' => '',
        ];
    } elseif ( is_search() ) {
        $breadcrumbs[] = [
            'title' => sprintf( esc_html__( 'Search Results for: %s', 'aqualuxe' ), get_search_query() ),
            'url' => '',
        ];
    } elseif ( is_404() ) {
        $breadcrumbs[] = [
            'title' => esc_html__( 'Page Not Found', 'aqualuxe' ),
            'url' => '',
        ];
    }
    
    // Generate breadcrumbs HTML
    $output = '<nav class="breadcrumbs" aria-label="' . esc_attr__( 'Breadcrumbs', 'aqualuxe' ) . '">';
    $output .= '<ol class="breadcrumbs-list" itemscope itemtype="https://schema.org/BreadcrumbList">';
    
    foreach ( $breadcrumbs as $index => $breadcrumb ) {
        $output .= '<li class="breadcrumbs-item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
        
        if ( ! empty( $breadcrumb['url'] ) ) {
            $output .= '<a href="' . esc_url( $breadcrumb['url'] ) . '" itemprop="item">';
            $output .= '<span itemprop="name">' . esc_html( $breadcrumb['title'] ) . '</span>';
            $output .= '</a>';
        } else {
            $output .= '<span itemprop="name">' . esc_html( $breadcrumb['title'] ) . '</span>';
        }
        
        $output .= '<meta itemprop="position" content="' . esc_attr( $index + 1 ) . '" />';
        $output .= '</li>';
        
        if ( $index < count( $breadcrumbs ) - 1 ) {
            $output .= '<li class="breadcrumbs-separator">' . aqualuxe_get_icon( 'chevron-right' ) . '</li>';
        }
    }
    
    $output .= '</ol>';
    $output .= '</nav>';
    
    return $output;
}

/**
 * Print breadcrumbs
 */
function aqualuxe_breadcrumbs() {
    echo aqualuxe_get_breadcrumbs();
}

/**
 * Get post meta
 *
 * @param int $post_id Post ID
 * @return string Post meta HTML
 */
function aqualuxe_get_post_meta( $post_id = null ) {
    if ( ! $post_id ) {
        $post_id = get_the_ID();
    }
    
    $output = '<div class="entry-meta">';
    
    // Author
    $output .= '<span class="entry-author">';
    $output .= '<span class="entry-author-avatar">' . get_avatar( get_the_author_meta( 'ID' ), 32 ) . '</span>';
    $output .= '<span class="entry-author-name">' . esc_html__( 'By', 'aqualuxe' ) . ' ';
    $output .= '<a href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a>';
    $output .= '</span>';
    $output .= '</span>';
    
    // Date
    $output .= '<span class="entry-date">';
    $output .= '<time datetime="' . esc_attr( get_the_date( 'c' ) ) . '">' . esc_html( get_the_date() ) . '</time>';
    $output .= '</span>';
    
    // Categories
    $categories = get_the_category();
    if ( ! empty( $categories ) ) {
        $output .= '<span class="entry-categories">';
        $output .= '<span class="entry-categories-label">' . esc_html__( 'In', 'aqualuxe' ) . '</span> ';
        
        $category_links = [];
        foreach ( $categories as $category ) {
            $category_links[] = '<a href="' . esc_url( get_category_link( $category->term_id ) ) . '">' . esc_html( $category->name ) . '</a>';
        }
        
        $output .= implode( ', ', $category_links );
        $output .= '</span>';
    }
    
    // Comments
    if ( comments_open( $post_id ) ) {
        $output .= '<span class="entry-comments">';
        $output .= '<a href="' . esc_url( get_comments_link( $post_id ) ) . '">';
        $output .= get_comments_number( $post_id ) . ' ' . esc_html__( 'Comments', 'aqualuxe' );
        $output .= '</a>';
        $output .= '</span>';
    }
    
    $output .= '</div>';
    
    return $output;
}

/**
 * Print post meta
 *
 * @param int $post_id Post ID
 */
function aqualuxe_post_meta( $post_id = null ) {
    echo aqualuxe_get_post_meta( $post_id );
}

/**
 * Get post thumbnail
 *
 * @param int $post_id Post ID
 * @param string $size Image size
 * @return string Post thumbnail HTML
 */
function aqualuxe_get_post_thumbnail( $post_id = null, $size = 'large' ) {
    if ( ! $post_id ) {
        $post_id = get_the_ID();
    }
    
    if ( ! has_post_thumbnail( $post_id ) ) {
        return '';
    }
    
    $output = '<div class="entry-thumbnail">';
    $output .= '<a href="' . esc_url( get_permalink( $post_id ) ) . '">';
    $output .= get_the_post_thumbnail( $post_id, $size, [
        'class' => 'entry-thumbnail-image',
        'alt' => get_the_title( $post_id ),
        'loading' => 'lazy',
    ] );
    $output .= '</a>';
    $output .= '</div>';
    
    return $output;
}

/**
 * Print post thumbnail
 *
 * @param int $post_id Post ID
 * @param string $size Image size
 */
function aqualuxe_post_thumbnail( $post_id = null, $size = 'large' ) {
    echo aqualuxe_get_post_thumbnail( $post_id, $size );
}

/**
 * Get post excerpt
 *
 * @param int $post_id Post ID
 * @param int $length Excerpt length
 * @return string Post excerpt
 */
function aqualuxe_get_post_excerpt( $post_id = null, $length = 55 ) {
    if ( ! $post_id ) {
        $post_id = get_the_ID();
    }
    
    $excerpt = get_the_excerpt( $post_id );
    
    if ( ! $excerpt ) {
        $post = get_post( $post_id );
        $excerpt = $post->post_content;
    }
    
    $excerpt = strip_shortcodes( $excerpt );
    $excerpt = excerpt_remove_blocks( $excerpt );
    $excerpt = wp_strip_all_tags( $excerpt );
    $excerpt = wp_trim_words( $excerpt, $length, '&hellip;' );
    
    return $excerpt;
}

/**
 * Print post excerpt
 *
 * @param int $post_id Post ID
 * @param int $length Excerpt length
 */
function aqualuxe_post_excerpt( $post_id = null, $length = 55 ) {
    echo aqualuxe_get_post_excerpt( $post_id, $length );
}

/**
 * Get pagination
 *
 * @param array $args Pagination arguments
 * @return string Pagination HTML
 */
function aqualuxe_get_pagination( $args = [] ) {
    global $wp_query;
    
    $defaults = [
        'total' => $wp_query->max_num_pages,
        'current' => max( 1, get_query_var( 'paged' ) ),
        'prev_text' => aqualuxe_get_icon( 'chevron-left' ) . '<span class="screen-reader-text">' . esc_html__( 'Previous', 'aqualuxe' ) . '</span>',
        'next_text' => '<span class="screen-reader-text">' . esc_html__( 'Next', 'aqualuxe' ) . '</span>' . aqualuxe_get_icon( 'chevron-right' ),
        'mid_size' => 2,
        'end_size' => 1,
    ];
    
    $args = wp_parse_args( $args, $defaults );
    
    if ( $args['total'] <= 1 ) {
        return '';
    }
    
    $output = '<nav class="pagination" aria-label="' . esc_attr__( 'Pagination', 'aqualuxe' ) . '">';
    $output .= paginate_links( $args );
    $output .= '</nav>';
    
    return $output;
}

/**
 * Print pagination
 *
 * @param array $args Pagination arguments
 */
function aqualuxe_pagination( $args = [] ) {
    echo aqualuxe_get_pagination( $args );
}

/**
 * Get related posts
 *
 * @param int $post_id Post ID
 * @param int $count Number of posts
 * @return WP_Query Related posts query
 */
function aqualuxe_get_related_posts( $post_id = null, $count = 3 ) {
    if ( ! $post_id ) {
        $post_id = get_the_ID();
    }
    
    // Get post categories
    $categories = get_the_category( $post_id );
    $category_ids = [];
    
    if ( ! empty( $categories ) ) {
        foreach ( $categories as $category ) {
            $category_ids[] = $category->term_id;
        }
    }
    
    // Get post tags
    $tags = get_the_tags( $post_id );
    $tag_ids = [];
    
    if ( ! empty( $tags ) ) {
        foreach ( $tags as $tag ) {
            $tag_ids[] = $tag->term_id;
        }
    }
    
    // Query arguments
    $args = [
        'post_type' => 'post',
        'post_status' => 'publish',
        'posts_per_page' => $count,
        'post__not_in' => [ $post_id ],
        'orderby' => 'rand',
    ];
    
    // Add tax query if we have categories or tags
    if ( ! empty( $category_ids ) || ! empty( $tag_ids ) ) {
        $args['tax_query'] = [ 'relation' => 'OR' ];
        
        if ( ! empty( $category_ids ) ) {
            $args['tax_query'][] = [
                'taxonomy' => 'category',
                'field' => 'term_id',
                'terms' => $category_ids,
            ];
        }
        
        if ( ! empty( $tag_ids ) ) {
            $args['tax_query'][] = [
                'taxonomy' => 'post_tag',
                'field' => 'term_id',
                'terms' => $tag_ids,
            ];
        }
    }
    
    return new WP_Query( $args );
}

/**
 * Get social sharing links
 *
 * @param int $post_id Post ID
 * @return string Social sharing links HTML
 */
function aqualuxe_get_social_sharing( $post_id = null ) {
    if ( ! $post_id ) {
        $post_id = get_the_ID();
    }
    
    $url = get_permalink( $post_id );
    $title = get_the_title( $post_id );
    $thumbnail = get_the_post_thumbnail_url( $post_id, 'large' );
    
    $networks = [
        'facebook' => [
            'url' => 'https://www.facebook.com/sharer/sharer.php?u=' . urlencode( $url ),
            'label' => esc_html__( 'Share on Facebook', 'aqualuxe' ),
            'icon' => 'facebook',
        ],
        'twitter' => [
            'url' => 'https://twitter.com/intent/tweet?url=' . urlencode( $url ) . '&text=' . urlencode( $title ),
            'label' => esc_html__( 'Share on Twitter', 'aqualuxe' ),
            'icon' => 'twitter',
        ],
        'linkedin' => [
            'url' => 'https://www.linkedin.com/shareArticle?mini=true&url=' . urlencode( $url ) . '&title=' . urlencode( $title ),
            'label' => esc_html__( 'Share on LinkedIn', 'aqualuxe' ),
            'icon' => 'linkedin',
        ],
        'pinterest' => [
            'url' => 'https://pinterest.com/pin/create/button/?url=' . urlencode( $url ) . '&media=' . urlencode( $thumbnail ) . '&description=' . urlencode( $title ),
            'label' => esc_html__( 'Share on Pinterest', 'aqualuxe' ),
            'icon' => 'pinterest',
        ],
        'email' => [
            'url' => 'mailto:?subject=' . urlencode( $title ) . '&body=' . urlencode( $url ),
            'label' => esc_html__( 'Share via Email', 'aqualuxe' ),
            'icon' => 'mail',
        ],
    ];
    
    $output = '<div class="social-sharing">';
    $output .= '<span class="social-sharing-title">' . esc_html__( 'Share:', 'aqualuxe' ) . '</span>';
    $output .= '<ul class="social-sharing-list">';
    
    foreach ( $networks as $network => $data ) {
        $output .= '<li class="social-sharing-item social-sharing-' . esc_attr( $network ) . '">';
        $output .= '<a href="' . esc_url( $data['url'] ) . '" target="_blank" rel="noopener noreferrer" aria-label="' . esc_attr( $data['label'] ) . '">';
        $output .= aqualuxe_get_icon( $data['icon'] );
        $output .= '</a>';
        $output .= '</li>';
    }
    
    $output .= '</ul>';
    $output .= '</div>';
    
    return $output;
}

/**
 * Print social sharing links
 *
 * @param int $post_id Post ID
 */
function aqualuxe_social_sharing( $post_id = null ) {
    echo aqualuxe_get_social_sharing( $post_id );
}

/**
 * Get language switcher
 *
 * @return string Language switcher HTML
 */
function aqualuxe_get_language_switcher() {
    // Get languages
    $languages = aqualuxe_get_languages();
    
    if ( empty( $languages ) ) {
        return '';
    }
    
    // Get current language
    $current_language = aqualuxe_get_current_language();
    
    $output = '<div class="language-switcher">';
    $output .= '<button class="language-switcher-toggle" aria-expanded="false">';
    $output .= aqualuxe_get_icon( 'globe' );
    $output .= '<span class="language-switcher-current">' . esc_html( $current_language ) . '</span>';
    $output .= aqualuxe_get_icon( 'chevron-down' );
    $output .= '</button>';
    $output .= '<ul class="language-switcher-dropdown">';
    
    foreach ( $languages as $code => $name ) {
        $output .= '<li class="language-switcher-item' . ( $code === $current_language ? ' is-active' : '' ) . '">';
        $output .= '<a href="' . esc_url( add_query_arg( 'lang', $code ) ) . '">' . esc_html( $name ) . '</a>';
        $output .= '</li>';
    }
    
    $output .= '</ul>';
    $output .= '</div>';
    
    return $output;
}

/**
 * Print language switcher
 */
function aqualuxe_language_switcher() {
    echo aqualuxe_get_language_switcher();
}

/**
 * Get currency switcher
 *
 * @return string Currency switcher HTML
 */
function aqualuxe_get_currency_switcher() {
    // Get currencies
    $currencies = aqualuxe_get_currencies();
    
    if ( empty( $currencies ) ) {
        return '';
    }
    
    // Get current currency
    $current_currency = aqualuxe_get_current_currency();
    
    $output = '<div class="currency-switcher">';
    $output .= '<button class="currency-switcher-toggle" aria-expanded="false">';
    $output .= '<span class="currency-switcher-current">' . esc_html( aqualuxe_get_currency_symbol( $current_currency ) ) . '</span>';
    $output .= aqualuxe_get_icon( 'chevron-down' );
    $output .= '</button>';
    $output .= '<ul class="currency-switcher-dropdown">';
    
    foreach ( $currencies as $code => $name ) {
        $output .= '<li class="currency-switcher-item' . ( $code === $current_currency ? ' is-active' : '' ) . '">';
        $output .= '<a href="' . esc_url( add_query_arg( 'currency', $code ) ) . '">';
        $output .= '<span class="currency-switcher-symbol">' . esc_html( aqualuxe_get_currency_symbol( $code ) ) . '</span>';
        $output .= '<span class="currency-switcher-name">' . esc_html( $name ) . '</span>';
        $output .= '</a>';
        $output .= '</li>';
    }
    
    $output .= '</ul>';
    $output .= '</div>';
    
    return $output;
}

/**
 * Print currency switcher
 */
function aqualuxe_currency_switcher() {
    echo aqualuxe_get_currency_switcher();
}

/**
 * Get dark mode toggle
 *
 * @return string Dark mode toggle HTML
 */
function aqualuxe_get_dark_mode_toggle() {
    // Check if dark mode module is active
    if ( ! aqualuxe_is_module_active( 'dark-mode' ) ) {
        return '';
    }
    
    $is_dark = aqualuxe_is_dark_mode();
    
    $output = '<button class="dark-mode-toggle" aria-pressed="' . ( $is_dark ? 'true' : 'false' ) . '">';
    $output .= '<span class="dark-mode-toggle-icon dark-mode-toggle-icon-light">' . aqualuxe_get_icon( 'sun' ) . '</span>';
    $output .= '<span class="dark-mode-toggle-icon dark-mode-toggle-icon-dark">' . aqualuxe_get_icon( 'moon' ) . '</span>';
    $output .= '<span class="screen-reader-text">' . esc_html__( 'Toggle dark mode', 'aqualuxe' ) . '</span>';
    $output .= '</button>';
    
    return $output;
}

/**
 * Print dark mode toggle
 */
function aqualuxe_dark_mode_toggle() {
    echo aqualuxe_get_dark_mode_toggle();
}

/**
 * Get contact info
 *
 * @return string Contact info HTML
 */
function aqualuxe_get_contact_info_html() {
    $contact_info = aqualuxe_get_contact_info();
    
    if ( empty( $contact_info ) ) {
        return '';
    }
    
    $output = '<div class="contact-info">';
    
    if ( ! empty( $contact_info['phone'] ) ) {
        $output .= '<div class="contact-info-item contact-info-phone">';
        $output .= '<span class="contact-info-icon">' . aqualuxe_get_icon( 'phone' ) . '</span>';
        $output .= '<a href="tel:' . esc_attr( preg_replace( '/[^0-9+]/', '', $contact_info['phone'] ) ) . '">' . esc_html( $contact_info['phone'] ) . '</a>';
        $output .= '</div>';
    }
    
    if ( ! empty( $contact_info['email'] ) ) {
        $output .= '<div class="contact-info-item contact-info-email">';
        $output .= '<span class="contact-info-icon">' . aqualuxe_get_icon( 'mail' ) . '</span>';
        $output .= '<a href="mailto:' . esc_attr( $contact_info['email'] ) . '">' . esc_html( $contact_info['email'] ) . '</a>';
        $output .= '</div>';
    }
    
    if ( ! empty( $contact_info['address'] ) ) {
        $output .= '<div class="contact-info-item contact-info-address">';
        $output .= '<span class="contact-info-icon">' . aqualuxe_get_icon( 'map-pin' ) . '</span>';
        $output .= '<span>' . esc_html( $contact_info['address'] ) . '</span>';
        $output .= '</div>';
    }
    
    if ( ! empty( $contact_info['hours'] ) ) {
        $output .= '<div class="contact-info-item contact-info-hours">';
        $output .= '<span class="contact-info-icon">' . aqualuxe_get_icon( 'clock' ) . '</span>';
        $output .= '<span>' . esc_html( $contact_info['hours'] ) . '</span>';
        $output .= '</div>';
    }
    
    $output .= '</div>';
    
    return $output;
}

/**
 * Print contact info
 */
function aqualuxe_contact_info() {
    echo aqualuxe_get_contact_info_html();
}

/**
 * Get social links
 *
 * @return string Social links HTML
 */
function aqualuxe_get_social_links_html() {
    $social_links = aqualuxe_get_social_links();
    
    if ( empty( $social_links ) ) {
        return '';
    }
    
    $output = '<div class="social-links">';
    $output .= '<ul class="social-links-list">';
    
    foreach ( $social_links as $network => $url ) {
        if ( ! empty( $url ) ) {
            $output .= '<li class="social-links-item social-links-' . esc_attr( $network ) . '">';
            $output .= '<a href="' . esc_url( $url ) . '" target="_blank" rel="noopener noreferrer" aria-label="' . esc_attr( ucfirst( $network ) ) . '">';
            $output .= aqualuxe_get_icon( $network );
            $output .= '</a>';
            $output .= '</li>';
        }
    }
    
    $output .= '</ul>';
    $output .= '</div>';
    
    return $output;
}

/**
 * Print social links
 */
function aqualuxe_social_links() {
    echo aqualuxe_get_social_links_html();
}

/**
 * Get logo
 *
 * @param string $type Logo type (default or light)
 * @return string Logo HTML
 */
function aqualuxe_get_logo( $type = 'default' ) {
    $logo_id = 0;
    
    if ( $type === 'light' ) {
        $logo_id = aqualuxe_get_option( 'logo_light', 0 );
    }
    
    if ( ! $logo_id ) {
        $logo_id = get_theme_mod( 'custom_logo', 0 );
    }
    
    if ( $logo_id ) {
        $logo_attr = [
            'class' => 'custom-logo',
            'loading' => 'lazy',
        ];
        
        $image = wp_get_attachment_image( $logo_id, 'full', false, $logo_attr );
        $home_url = home_url( '/' );
        $site_name = get_bloginfo( 'name' );
        
        $output = '<a href="' . esc_url( $home_url ) . '" class="custom-logo-link" rel="home">';
        $output .= $image;
        $output .= '<span class="screen-reader-text">' . esc_html( $site_name ) . '</span>';
        $output .= '</a>';
        
        return $output;
    }
    
    // Fallback to site name
    $output = '<a href="' . esc_url( home_url( '/' ) ) . '" class="site-title">';
    $output .= esc_html( get_bloginfo( 'name' ) );
    $output .= '</a>';
    
    return $output;
}

/**
 * Print logo
 *
 * @param string $type Logo type (default or light)
 */
function aqualuxe_logo( $type = 'default' ) {
    echo aqualuxe_get_logo( $type );
}

/**
 * Get mobile menu toggle
 *
 * @return string Mobile menu toggle HTML
 */
function aqualuxe_get_mobile_menu_toggle() {
    $output = '<button class="mobile-menu-toggle" aria-expanded="false" aria-controls="mobile-menu">';
    $output .= '<span class="mobile-menu-toggle-icon">' . aqualuxe_get_icon( 'menu' ) . '</span>';
    $output .= '<span class="mobile-menu-toggle-label">' . esc_html__( 'Menu', 'aqualuxe' ) . '</span>';
    $output .= '</button>';
    
    return $output;
}

/**
 * Print mobile menu toggle
 */
function aqualuxe_mobile_menu_toggle() {
    echo aqualuxe_get_mobile_menu_toggle();
}

/**
 * Get search form
 *
 * @return string Search form HTML
 */
function aqualuxe_get_search_form() {
    $output = '<form role="search" method="get" class="search-form" action="' . esc_url( home_url( '/' ) ) . '">';
    $output .= '<label>';
    $output .= '<span class="screen-reader-text">' . esc_html__( 'Search for:', 'aqualuxe' ) . '</span>';
    $output .= '<input type="search" class="search-field" placeholder="' . esc_attr__( 'Search&hellip;', 'aqualuxe' ) . '" value="' . get_search_query() . '" name="s" />';
    $output .= '</label>';
    $output .= '<button type="submit" class="search-submit">';
    $output .= aqualuxe_get_icon( 'search' );
    $output .= '<span class="screen-reader-text">' . esc_html__( 'Search', 'aqualuxe' ) . '</span>';
    $output .= '</button>';
    $output .= '</form>';
    
    return $output;
}

/**
 * Print search form
 */
function aqualuxe_search_form() {
    echo aqualuxe_get_search_form();
}

/**
 * Get search toggle
 *
 * @return string Search toggle HTML
 */
function aqualuxe_get_search_toggle() {
    $output = '<button class="search-toggle" aria-expanded="false" aria-controls="search-modal">';
    $output .= aqualuxe_get_icon( 'search' );
    $output .= '<span class="screen-reader-text">' . esc_html__( 'Search', 'aqualuxe' ) . '</span>';
    $output .= '</button>';
    
    return $output;
}

/**
 * Print search toggle
 */
function aqualuxe_search_toggle() {
    echo aqualuxe_get_search_toggle();
}

/**
 * Get search modal
 *
 * @return string Search modal HTML
 */
function aqualuxe_get_search_modal() {
    $output = '<div id="search-modal" class="search-modal" aria-hidden="true">';
    $output .= '<div class="search-modal-inner">';
    $output .= '<button class="search-modal-close" aria-label="' . esc_attr__( 'Close search', 'aqualuxe' ) . '">';
    $output .= aqualuxe_get_icon( 'close' );
    $output .= '</button>';
    $output .= '<div class="search-modal-content">';
    $output .= '<h2 class="search-modal-title">' . esc_html__( 'Search', 'aqualuxe' ) . '</h2>';
    $output .= aqualuxe_get_search_form();
    $output .= '</div>';
    $output .= '</div>';
    $output .= '</div>';
    
    return $output;
}

/**
 * Print search modal
 */
function aqualuxe_search_modal() {
    echo aqualuxe_get_search_modal();
}

/**
 * Get mobile menu
 *
 * @return string Mobile menu HTML
 */
function aqualuxe_get_mobile_menu() {
    $output = '<div id="mobile-menu" class="mobile-menu" aria-hidden="true">';
    $output .= '<div class="mobile-menu-inner">';
    $output .= '<div class="mobile-menu-header">';
    $output .= '<div class="mobile-menu-logo">' . aqualuxe_get_logo() . '</div>';
    $output .= '<button class="mobile-menu-close" aria-label="' . esc_attr__( 'Close menu', 'aqualuxe' ) . '">';
    $output .= aqualuxe_get_icon( 'close' );
    $output .= '</button>';
    $output .= '</div>';
    $output .= '<div class="mobile-menu-content">';
    
    // Mobile search
    $output .= '<div class="mobile-menu-search">';
    $output .= aqualuxe_get_search_form();
    $output .= '</div>';
    
    // Mobile navigation
    $output .= '<nav class="mobile-menu-nav">';
    
    if ( has_nav_menu( 'mobile' ) ) {
        ob_start();
        wp_nav_menu( [
            'theme_location' => 'mobile',
            'container' => false,
            'menu_class' => 'mobile-menu-nav-list',
            'depth' => 3,
            'fallback_cb' => false,
        ] );
        $output .= ob_get_clean();
    } elseif ( has_nav_menu( 'primary' ) ) {
        ob_start();
        wp_nav_menu( [
            'theme_location' => 'primary',
            'container' => false,
            'menu_class' => 'mobile-menu-nav-list',
            'depth' => 3,
            'fallback_cb' => false,
        ] );
        $output .= ob_get_clean();
    }
    
    $output .= '</nav>';
    
    // Mobile language switcher
    if ( aqualuxe_is_module_active( 'multilingual' ) ) {
        $output .= '<div class="mobile-menu-languages">';
        $output .= aqualuxe_get_language_switcher();
        $output .= '</div>';
    }
    
    // Mobile currency switcher
    if ( aqualuxe_is_woocommerce_active() ) {
        $output .= '<div class="mobile-menu-currencies">';
        $output .= aqualuxe_get_currency_switcher();
        $output .= '</div>';
    }
    
    // Mobile dark mode toggle
    if ( aqualuxe_is_module_active( 'dark-mode' ) ) {
        $output .= '<div class="mobile-menu-dark-mode">';
        $output .= aqualuxe_get_dark_mode_toggle();
        $output .= '</div>';
    }
    
    // Mobile contact info
    $output .= '<div class="mobile-menu-contact">';
    $output .= aqualuxe_get_contact_info_html();
    $output .= '</div>';
    
    // Mobile social links
    $output .= '<div class="mobile-menu-social">';
    $output .= aqualuxe_get_social_links_html();
    $output .= '</div>';
    
    $output .= '</div>';
    $output .= '</div>';
    $output .= '</div>';
    
    return $output;
}

/**
 * Print mobile menu
 */
function aqualuxe_mobile_menu() {
    echo aqualuxe_get_mobile_menu();
}

/**
 * Get header cart
 *
 * @return string Header cart HTML
 */
function aqualuxe_get_header_cart() {
    // Check if WooCommerce is active
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return '';
    }
    
    $cart_count = WC()->cart ? WC()->cart->get_cart_contents_count() : 0;
    $cart_url = wc_get_cart_url();
    
    $output = '<div class="header-cart">';
    $output .= '<a href="' . esc_url( $cart_url ) . '" class="header-cart-link">';
    $output .= '<span class="header-cart-icon">' . aqualuxe_get_icon( 'cart' ) . '</span>';
    $output .= '<span class="header-cart-count">' . esc_html( $cart_count ) . '</span>';
    $output .= '</a>';
    $output .= '</div>';
    
    return $output;
}

/**
 * Print header cart
 */
function aqualuxe_header_cart() {
    echo aqualuxe_get_header_cart();
}

/**
 * Get header account
 *
 * @return string Header account HTML
 */
function aqualuxe_get_header_account() {
    // Check if WooCommerce is active
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return '';
    }
    
    $account_url = wc_get_account_endpoint_url( 'dashboard' );
    
    $output = '<div class="header-account">';
    $output .= '<a href="' . esc_url( $account_url ) . '" class="header-account-link">';
    $output .= '<span class="header-account-icon">' . aqualuxe_get_icon( 'user' ) . '</span>';
    $output .= '<span class="header-account-label">' . esc_html__( 'Account', 'aqualuxe' ) . '</span>';
    $output .= '</a>';
    $output .= '</div>';
    
    return $output;
}

/**
 * Print header account
 */
function aqualuxe_header_account() {
    echo aqualuxe_get_header_account();
}

/**
 * Get header wishlist
 *
 * @return string Header wishlist HTML
 */
function aqualuxe_get_header_wishlist() {
    // Check if WooCommerce is active
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return '';
    }
    
    // Check if wishlist module is active
    if ( ! aqualuxe_is_module_active( 'wishlist' ) ) {
        return '';
    }
    
    $wishlist_url = aqualuxe_get_page_url_by_template( 'templates/wishlist.php' );
    
    if ( ! $wishlist_url ) {
        return '';
    }
    
    $wishlist_count = 0;
    
    // Get wishlist count from module
    $module = aqualuxe_module( 'wishlist' );
    if ( $module && method_exists( $module, 'get_wishlist_count' ) ) {
        $wishlist_count = $module->get_wishlist_count();
    }
    
    $output = '<div class="header-wishlist">';
    $output .= '<a href="' . esc_url( $wishlist_url ) . '" class="header-wishlist-link">';
    $output .= '<span class="header-wishlist-icon">' . aqualuxe_get_icon( 'heart' ) . '</span>';
    $output .= '<span class="header-wishlist-count">' . esc_html( $wishlist_count ) . '</span>';
    $output .= '</a>';
    $output .= '</div>';
    
    return $output;
}

/**
 * Print header wishlist
 */
function aqualuxe_header_wishlist() {
    echo aqualuxe_get_header_wishlist();
}

/**
 * Get header actions
 *
 * @return string Header actions HTML
 */
function aqualuxe_get_header_actions() {
    $output = '<div class="header-actions">';
    
    // Search toggle
    $output .= aqualuxe_get_search_toggle();
    
    // Account
    if ( aqualuxe_is_woocommerce_active() ) {
        $output .= aqualuxe_get_header_account();
    }
    
    // Wishlist
    if ( aqualuxe_is_woocommerce_active() && aqualuxe_is_module_active( 'wishlist' ) ) {
        $output .= aqualuxe_get_header_wishlist();
    }
    
    // Cart
    if ( aqualuxe_is_woocommerce_active() ) {
        $output .= aqualuxe_get_header_cart();
    }
    
    $output .= '</div>';
    
    return $output;
}

/**
 * Print header actions
 */
function aqualuxe_header_actions() {
    echo aqualuxe_get_header_actions();
}

/**
 * Get header top bar
 *
 * @return string Header top bar HTML
 */
function aqualuxe_get_header_top_bar() {
    $output = '<div class="header-top-bar">';
    $output .= '<div class="container">';
    $output .= '<div class="header-top-bar-inner">';
    
    // Contact info
    $output .= '<div class="header-top-bar-left">';
    $output .= aqualuxe_get_contact_info_html();
    $output .= '</div>';
    
    // Language and currency switchers
    $output .= '<div class="header-top-bar-right">';
    
    // Language switcher
    if ( aqualuxe_is_module_active( 'multilingual' ) ) {
        $output .= aqualuxe_get_language_switcher();
    }
    
    // Currency switcher
    if ( aqualuxe_is_woocommerce_active() ) {
        $output .= aqualuxe_get_currency_switcher();
    }
    
    // Dark mode toggle
    if ( aqualuxe_is_module_active( 'dark-mode' ) ) {
        $output .= aqualuxe_get_dark_mode_toggle();
    }
    
    // Social links
    $output .= aqualuxe_get_social_links_html();
    
    $output .= '</div>';
    
    $output .= '</div>';
    $output .= '</div>';
    $output .= '</div>';
    
    return $output;
}

/**
 * Print header top bar
 */
function aqualuxe_header_top_bar() {
    echo aqualuxe_get_header_top_bar();
}

/**
 * Get header main
 *
 * @return string Header main HTML
 */
function aqualuxe_get_header_main() {
    $output = '<div class="header-main">';
    $output .= '<div class="container">';
    $output .= '<div class="header-main-inner">';
    
    // Logo
    $output .= '<div class="header-logo">';
    $output .= aqualuxe_get_logo();
    $output .= '</div>';
    
    // Navigation
    $output .= '<nav class="header-nav" aria-label="' . esc_attr__( 'Main Navigation', 'aqualuxe' ) . '">';
    
    if ( has_nav_menu( 'primary' ) ) {
        ob_start();
        wp_nav_menu( [
            'theme_location' => 'primary',
            'container' => false,
            'menu_class' => 'header-nav-list',
            'depth' => 3,
            'fallback_cb' => false,
        ] );
        $output .= ob_get_clean();
    }
    
    $output .= '</nav>';
    
    // Actions
    $output .= aqualuxe_get_header_actions();
    
    // Mobile menu toggle
    $output .= aqualuxe_get_mobile_menu_toggle();
    
    $output .= '</div>';
    $output .= '</div>';
    $output .= '</div>';
    
    return $output;
}

/**
 * Print header main
 */
function aqualuxe_header_main() {
    echo aqualuxe_get_header_main();
}

/**
 * Get page header
 *
 * @return string Page header HTML
 */
function aqualuxe_get_page_header() {
    $title = aqualuxe_get_page_title();
    
    if ( ! $title ) {
        return '';
    }
    
    $output = '<div class="page-header">';
    $output .= '<div class="container">';
    $output .= '<div class="page-header-inner">';
    $output .= '<h1 class="page-title">' . wp_kses_post( $title ) . '</h1>';
    $output .= aqualuxe_get_breadcrumbs();
    $output .= '</div>';
    $output .= '</div>';
    $output .= '</div>';
    
    return $output;
}

/**
 * Print page header
 */
function aqualuxe_page_header() {
    echo aqualuxe_get_page_header();
}

/**
 * Get footer widgets
 *
 * @return string Footer widgets HTML
 */
function aqualuxe_get_footer_widgets() {
    // Check if footer widgets are active
    if ( ! is_active_sidebar( 'footer-1' ) && ! is_active_sidebar( 'footer-2' ) && ! is_active_sidebar( 'footer-3' ) && ! is_active_sidebar( 'footer-4' ) ) {
        return '';
    }
    
    $output = '<div class="footer-widgets">';
    $output .= '<div class="container">';
    $output .= '<div class="footer-widgets-inner">';
    
    // Footer widget 1
    $output .= '<div class="footer-widget footer-widget-1">';
    ob_start();
    dynamic_sidebar( 'footer-1' );
    $output .= ob_get_clean();
    $output .= '</div>';
    
    // Footer widget 2
    $output .= '<div class="footer-widget footer-widget-2">';
    ob_start();
    dynamic_sidebar( 'footer-2' );
    $output .= ob_get_clean();
    $output .= '</div>';
    
    // Footer widget 3
    $output .= '<div class="footer-widget footer-widget-3">';
    ob_start();
    dynamic_sidebar( 'footer-3' );
    $output .= ob_get_clean();
    $output .= '</div>';
    
    // Footer widget 4
    $output .= '<div class="footer-widget footer-widget-4">';
    ob_start();
    dynamic_sidebar( 'footer-4' );
    $output .= ob_get_clean();
    $output .= '</div>';
    
    $output .= '</div>';
    $output .= '</div>';
    $output .= '</div>';
    
    return $output;
}

/**
 * Print footer widgets
 */
function aqualuxe_footer_widgets() {
    echo aqualuxe_get_footer_widgets();
}

/**
 * Get footer bottom
 *
 * @return string Footer bottom HTML
 */
function aqualuxe_get_footer_bottom() {
    $output = '<div class="footer-bottom">';
    $output .= '<div class="container">';
    $output .= '<div class="footer-bottom-inner">';
    
    // Copyright
    $output .= '<div class="footer-copyright">';
    $output .= '<p>' . sprintf( esc_html__( '&copy; %1$s %2$s. All rights reserved.', 'aqualuxe' ), date( 'Y' ), get_bloginfo( 'name' ) ) . '</p>';
    $output .= '</div>';
    
    // Footer menu
    $output .= '<nav class="footer-nav" aria-label="' . esc_attr__( 'Footer Navigation', 'aqualuxe' ) . '">';
    
    if ( has_nav_menu( 'footer' ) ) {
        ob_start();
        wp_nav_menu( [
            'theme_location' => 'footer',
            'container' => false,
            'menu_class' => 'footer-nav-list',
            'depth' => 1,
            'fallback_cb' => false,
        ] );
        $output .= ob_get_clean();
    }
    
    $output .= '</nav>';
    
    // Social links
    $output .= aqualuxe_get_social_links_html();
    
    $output .= '</div>';
    $output .= '</div>';
    $output .= '</div>';
    
    return $output;
}

/**
 * Print footer bottom
 */
function aqualuxe_footer_bottom() {
    echo aqualuxe_get_footer_bottom();
}