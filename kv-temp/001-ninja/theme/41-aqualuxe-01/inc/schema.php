<?php
/**
 * Schema.org Markup Functions
 *
 * Functions for implementing schema.org structured data markup.
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Class AquaLuxe_Schema
 */
class AquaLuxe_Schema {

    /**
     * Constructor
     */
    public function __construct() {
        // Add schema markup to HTML tag
        add_filter( 'language_attributes', array( $this, 'html_schema_markup' ) );
        
        // Add schema markup to the body
        add_filter( 'body_class', array( $this, 'body_schema_markup' ) );
        
        // Add schema markup to the header
        add_action( 'aqualuxe_header_before', array( $this, 'header_schema_markup' ) );
        
        // Add schema markup to the footer
        add_action( 'aqualuxe_footer_before', array( $this, 'footer_schema_markup' ) );
        
        // Add schema markup to the main content
        add_action( 'aqualuxe_content_before', array( $this, 'content_schema_markup' ) );
        
        // Add schema markup to the sidebar
        add_action( 'aqualuxe_sidebar_before', array( $this, 'sidebar_schema_markup' ) );
        
        // Add schema markup to the breadcrumbs
        add_filter( 'aqualuxe_breadcrumb_args', array( $this, 'breadcrumb_schema_markup' ) );
        
        // Add schema markup to the navigation
        add_filter( 'wp_nav_menu_args', array( $this, 'nav_menu_schema_markup' ) );
        
        // Add schema markup to the post
        add_filter( 'post_class', array( $this, 'post_schema_markup' ), 10, 3 );
        
        // Add schema markup to the comment
        add_filter( 'comment_class', array( $this, 'comment_schema_markup' ), 10, 3 );
        
        // Add schema markup to the comment author link
        add_filter( 'get_comment_author_link', array( $this, 'comment_author_schema_markup' ) );
        
        // Add schema markup to the comment reply link
        add_filter( 'comment_reply_link', array( $this, 'comment_reply_schema_markup' ) );
        
        // Add schema markup to the search form
        add_filter( 'get_search_form', array( $this, 'search_form_schema_markup' ) );
        
        // Add schema markup to the logo
        add_filter( 'get_custom_logo', array( $this, 'logo_schema_markup' ) );
        
        // Add Organization schema markup
        add_action( 'wp_footer', array( $this, 'organization_schema_markup' ) );
        
        // Add WebSite schema markup
        add_action( 'wp_footer', array( $this, 'website_schema_markup' ) );
        
        // Add WebPage schema markup
        add_action( 'wp_footer', array( $this, 'webpage_schema_markup' ) );
        
        // Add Article schema markup
        add_action( 'wp_footer', array( $this, 'article_schema_markup' ) );
        
        // Add Product schema markup for WooCommerce
        add_action( 'wp_footer', array( $this, 'product_schema_markup' ) );
        
        // Add BreadcrumbList schema markup
        add_action( 'wp_footer', array( $this, 'breadcrumblist_schema_markup' ) );
    }

    /**
     * Add schema markup to HTML tag
     *
     * @param string $output The output.
     * @return string Modified output.
     */
    public function html_schema_markup( $output ) {
        return $output . ' itemscope itemtype="https://schema.org/WebPage"';
    }

    /**
     * Add schema markup to the body
     *
     * @param array $classes The body classes.
     * @return array Modified body classes.
     */
    public function body_schema_markup( $classes ) {
        $classes[] = 'itemscope';
        $classes[] = 'itemtype-https-schema-org-WebPage';
        
        return $classes;
    }

    /**
     * Add schema markup to the header
     */
    public function header_schema_markup() {
        echo '<div itemscope itemtype="https://schema.org/WPHeader">';
    }

    /**
     * Add schema markup to the footer
     */
    public function footer_schema_markup() {
        echo '<div itemscope itemtype="https://schema.org/WPFooter">';
    }

    /**
     * Add schema markup to the main content
     */
    public function content_schema_markup() {
        echo '<div itemscope itemtype="https://schema.org/WebPageElement" itemprop="mainContentOfPage">';
    }

    /**
     * Add schema markup to the sidebar
     */
    public function sidebar_schema_markup() {
        echo '<div itemscope itemtype="https://schema.org/WPSideBar">';
    }

    /**
     * Add schema markup to the breadcrumbs
     *
     * @param array $args The breadcrumb arguments.
     * @return array Modified breadcrumb arguments.
     */
    public function breadcrumb_schema_markup( $args ) {
        $args['before'] = '<nav class="breadcrumbs" itemscope itemtype="https://schema.org/BreadcrumbList">';
        $args['item_before'] = '<span itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
        $args['item_after'] = '</span>';
        
        return $args;
    }

    /**
     * Add schema markup to the navigation
     *
     * @param array $args The nav menu arguments.
     * @return array Modified nav menu arguments.
     */
    public function nav_menu_schema_markup( $args ) {
        $args['container_itemtype'] = 'https://schema.org/SiteNavigationElement';
        
        return $args;
    }

    /**
     * Add schema markup to the post
     *
     * @param array  $classes The post classes.
     * @param string $class   The post class.
     * @param int    $post_id The post ID.
     * @return array Modified post classes.
     */
    public function post_schema_markup( $classes, $class, $post_id ) {
        $classes[] = 'itemscope';
        $classes[] = 'itemtype-https-schema-org-Article';
        
        return $classes;
    }

    /**
     * Add schema markup to the comment
     *
     * @param array  $classes    The comment classes.
     * @param string $class      The comment class.
     * @param int    $comment_id The comment ID.
     * @return array Modified comment classes.
     */
    public function comment_schema_markup( $classes, $class, $comment_id ) {
        $classes[] = 'itemscope';
        $classes[] = 'itemtype-https-schema-org-Comment';
        
        return $classes;
    }

    /**
     * Add schema markup to the comment author link
     *
     * @param string $link The comment author link.
     * @return string Modified comment author link.
     */
    public function comment_author_schema_markup( $link ) {
        return preg_replace( '/(<a.*?>)/i', '$1<span itemprop="name">', $link ) . '</span>';
    }

    /**
     * Add schema markup to the comment reply link
     *
     * @param string $link The comment reply link.
     * @return string Modified comment reply link.
     */
    public function comment_reply_schema_markup( $link ) {
        return preg_replace( '/(<a.*?>)/i', '$1<span itemprop="replyToUrl">', $link ) . '</span>';
    }

    /**
     * Add schema markup to the search form
     *
     * @param string $form The search form.
     * @return string Modified search form.
     */
    public function search_form_schema_markup( $form ) {
        return preg_replace( '/(<form.*?>)/i', '$1<div itemscope itemtype="https://schema.org/WebSite"><meta itemprop="url" content="' . esc_url( home_url( '/' ) ) . '" /><div itemprop="potentialAction" itemscope itemtype="https://schema.org/SearchAction"><meta itemprop="target" content="' . esc_url( home_url( '/' ) ) . '?s={search_term_string}" /><input itemprop="query-input" type="text" name="s" placeholder="' . esc_attr__( 'Search', 'aqualuxe' ) . '" />', $form );
    }

    /**
     * Add schema markup to the logo
     *
     * @param string $html The logo HTML.
     * @return string Modified logo HTML.
     */
    public function logo_schema_markup( $html ) {
        return preg_replace( '/(<a.*?>)/i', '$1<span itemprop="logo" itemscope itemtype="https://schema.org/ImageObject">', $html ) . '</span>';
    }

    /**
     * Add Organization schema markup
     */
    public function organization_schema_markup() {
        // Only add to the front page
        if ( ! is_front_page() ) {
            return;
        }
        
        // Get organization data
        $name = get_bloginfo( 'name' );
        $description = get_bloginfo( 'description' );
        $url = home_url( '/' );
        
        // Get logo
        $logo_id = get_theme_mod( 'custom_logo' );
        $logo_url = wp_get_attachment_image_url( $logo_id, 'full' );
        $logo_width = 0;
        $logo_height = 0;
        
        if ( $logo_id ) {
            $logo_data = wp_get_attachment_metadata( $logo_id );
            
            if ( isset( $logo_data['width'] ) && isset( $logo_data['height'] ) ) {
                $logo_width = $logo_data['width'];
                $logo_height = $logo_data['height'];
            }
        }
        
        // Get social profiles
        $social_profiles = array();
        
        // Facebook
        $facebook = get_theme_mod( 'aqualuxe_facebook_url' );
        if ( $facebook ) {
            $social_profiles[] = $facebook;
        }
        
        // Twitter
        $twitter = get_theme_mod( 'aqualuxe_twitter_url' );
        if ( $twitter ) {
            $social_profiles[] = $twitter;
        }
        
        // Instagram
        $instagram = get_theme_mod( 'aqualuxe_instagram_url' );
        if ( $instagram ) {
            $social_profiles[] = $instagram;
        }
        
        // LinkedIn
        $linkedin = get_theme_mod( 'aqualuxe_linkedin_url' );
        if ( $linkedin ) {
            $social_profiles[] = $linkedin;
        }
        
        // YouTube
        $youtube = get_theme_mod( 'aqualuxe_youtube_url' );
        if ( $youtube ) {
            $social_profiles[] = $youtube;
        }
        
        // Build schema
        $schema = array(
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => $name,
            'url' => $url,
            'description' => $description,
        );
        
        // Add logo if available
        if ( $logo_url ) {
            $schema['logo'] = array(
                '@type' => 'ImageObject',
                'url' => $logo_url,
                'width' => $logo_width,
                'height' => $logo_height,
            );
        }
        
        // Add social profiles if available
        if ( ! empty( $social_profiles ) ) {
            $schema['sameAs'] = $social_profiles;
        }
        
        // Add contact info if available
        $contact_phone = get_theme_mod( 'aqualuxe_contact_phone' );
        $contact_email = get_theme_mod( 'aqualuxe_contact_email' );
        
        if ( $contact_phone || $contact_email ) {
            $schema['contactPoint'] = array(
                '@type' => 'ContactPoint',
                'contactType' => 'customer service',
            );
            
            if ( $contact_phone ) {
                $schema['contactPoint']['telephone'] = $contact_phone;
            }
            
            if ( $contact_email ) {
                $schema['contactPoint']['email'] = $contact_email;
            }
        }
        
        // Output schema
        $this->output_schema( $schema );
    }

    /**
     * Add WebSite schema markup
     */
    public function website_schema_markup() {
        // Only add to the front page
        if ( ! is_front_page() ) {
            return;
        }
        
        // Get website data
        $name = get_bloginfo( 'name' );
        $description = get_bloginfo( 'description' );
        $url = home_url( '/' );
        
        // Build schema
        $schema = array(
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'name' => $name,
            'url' => $url,
            'description' => $description,
            'potentialAction' => array(
                '@type' => 'SearchAction',
                'target' => $url . '?s={search_term_string}',
                'query-input' => 'required name=search_term_string',
            ),
        );
        
        // Output schema
        $this->output_schema( $schema );
    }

    /**
     * Add WebPage schema markup
     */
    public function webpage_schema_markup() {
        // Skip on front page (covered by WebSite schema)
        if ( is_front_page() ) {
            return;
        }
        
        // Skip on singular posts (covered by Article schema)
        if ( is_singular( 'post' ) ) {
            return;
        }
        
        // Skip on WooCommerce product pages (covered by Product schema)
        if ( function_exists( 'is_product' ) && is_product() ) {
            return;
        }
        
        // Get page data
        $title = wp_get_document_title();
        $url = get_permalink();
        $description = '';
        
        if ( is_singular() ) {
            $description = get_the_excerpt();
        } elseif ( is_archive() ) {
            $description = get_the_archive_description();
        } elseif ( is_search() ) {
            $description = sprintf( __( 'Search results for: %s', 'aqualuxe' ), get_search_query() );
        }
        
        // Build schema
        $schema = array(
            '@context' => 'https://schema.org',
            '@type' => 'WebPage',
            'name' => $title,
            'url' => $url,
            'description' => wp_strip_all_tags( $description ),
        );
        
        // Add last modified date if available
        if ( is_singular() ) {
            $schema['dateModified'] = get_the_modified_date( 'c' );
        }
        
        // Add breadcrumbs if available
        if ( function_exists( 'yoast_breadcrumb' ) || function_exists( 'woocommerce_breadcrumb' ) ) {
            $schema['breadcrumb'] = array(
                '@type' => 'BreadcrumbList',
                'itemListElement' => array(),
            );
        }
        
        // Output schema
        $this->output_schema( $schema );
    }

    /**
     * Add Article schema markup
     */
    public function article_schema_markup() {
        // Only add to singular posts
        if ( ! is_singular( 'post' ) ) {
            return;
        }
        
        // Get post data
        $post = get_post();
        $title = get_the_title();
        $url = get_permalink();
        $description = get_the_excerpt();
        $published_date = get_the_date( 'c' );
        $modified_date = get_the_modified_date( 'c' );
        
        // Get author data
        $author_id = get_the_author_meta( 'ID' );
        $author_name = get_the_author();
        $author_url = get_author_posts_url( $author_id );
        
        // Get featured image
        $image_url = '';
        $image_width = 0;
        $image_height = 0;
        
        if ( has_post_thumbnail() ) {
            $image_id = get_post_thumbnail_id();
            $image_url = wp_get_attachment_image_url( $image_id, 'full' );
            $image_data = wp_get_attachment_metadata( $image_id );
            
            if ( isset( $image_data['width'] ) && isset( $image_data['height'] ) ) {
                $image_width = $image_data['width'];
                $image_height = $image_data['height'];
            }
        }
        
        // Build schema
        $schema = array(
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => $title,
            'url' => $url,
            'description' => wp_strip_all_tags( $description ),
            'datePublished' => $published_date,
            'dateModified' => $modified_date,
            'author' => array(
                '@type' => 'Person',
                'name' => $author_name,
                'url' => $author_url,
            ),
            'publisher' => array(
                '@type' => 'Organization',
                'name' => get_bloginfo( 'name' ),
                'url' => home_url( '/' ),
            ),
        );
        
        // Add featured image if available
        if ( $image_url ) {
            $schema['image'] = array(
                '@type' => 'ImageObject',
                'url' => $image_url,
                'width' => $image_width,
                'height' => $image_height,
            );
            
            // Also add to publisher logo
            $schema['publisher']['logo'] = array(
                '@type' => 'ImageObject',
                'url' => $image_url,
                'width' => $image_width,
                'height' => $image_height,
            );
        }
        
        // Add categories
        $categories = get_the_category();
        
        if ( $categories ) {
            $schema['articleSection'] = array();
            
            foreach ( $categories as $category ) {
                $schema['articleSection'][] = $category->name;
            }
        }
        
        // Add tags
        $tags = get_the_tags();
        
        if ( $tags ) {
            $schema['keywords'] = array();
            
            foreach ( $tags as $tag ) {
                $schema['keywords'][] = $tag->name;
            }
        }
        
        // Output schema
        $this->output_schema( $schema );
    }

    /**
     * Add Product schema markup for WooCommerce
     */
    public function product_schema_markup() {
        // Only add to WooCommerce product pages
        if ( ! function_exists( 'is_product' ) || ! is_product() ) {
            return;
        }
        
        // Get product data
        $product = wc_get_product();
        
        if ( ! $product ) {
            return;
        }
        
        $title = $product->get_name();
        $url = get_permalink();
        $description = wp_strip_all_tags( $product->get_short_description() );
        $sku = $product->get_sku();
        $price = $product->get_price();
        $regular_price = $product->get_regular_price();
        $sale_price = $product->get_sale_price();
        $currency = get_woocommerce_currency();
        $availability = $product->is_in_stock() ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock';
        
        // Get product image
        $image_url = '';
        $image_width = 0;
        $image_height = 0;
        
        if ( has_post_thumbnail() ) {
            $image_id = get_post_thumbnail_id();
            $image_url = wp_get_attachment_image_url( $image_id, 'full' );
            $image_data = wp_get_attachment_metadata( $image_id );
            
            if ( isset( $image_data['width'] ) && isset( $image_data['height'] ) ) {
                $image_width = $image_data['width'];
                $image_height = $image_data['height'];
            }
        }
        
        // Get product gallery images
        $gallery_images = array();
        $gallery_image_ids = $product->get_gallery_image_ids();
        
        if ( $gallery_image_ids ) {
            foreach ( $gallery_image_ids as $gallery_image_id ) {
                $gallery_image_url = wp_get_attachment_image_url( $gallery_image_id, 'full' );
                $gallery_image_data = wp_get_attachment_metadata( $gallery_image_id );
                
                if ( $gallery_image_url && isset( $gallery_image_data['width'] ) && isset( $gallery_image_data['height'] ) ) {
                    $gallery_images[] = array(
                        '@type' => 'ImageObject',
                        'url' => $gallery_image_url,
                        'width' => $gallery_image_data['width'],
                        'height' => $gallery_image_data['height'],
                    );
                }
            }
        }
        
        // Get product reviews
        $reviews = array();
        $review_count = $product->get_review_count();
        $rating = $product->get_average_rating();
        
        if ( $review_count > 0 ) {
            $args = array(
                'post_id' => $product->get_id(),
                'status' => 'approve',
                'type' => 'review',
            );
            
            $comments = get_comments( $args );
            
            foreach ( $comments as $comment ) {
                $review_rating = get_comment_meta( $comment->comment_ID, 'rating', true );
                
                if ( $review_rating ) {
                    $reviews[] = array(
                        '@type' => 'Review',
                        'reviewRating' => array(
                            '@type' => 'Rating',
                            'ratingValue' => $review_rating,
                            'bestRating' => '5',
                        ),
                        'author' => array(
                            '@type' => 'Person',
                            'name' => $comment->comment_author,
                        ),
                        'reviewBody' => wp_strip_all_tags( $comment->comment_content ),
                        'datePublished' => get_comment_date( 'c', $comment->comment_ID ),
                    );
                }
            }
        }
        
        // Build schema
        $schema = array(
            '@context' => 'https://schema.org',
            '@type' => 'Product',
            'name' => $title,
            'url' => $url,
            'description' => $description,
            'sku' => $sku,
            'brand' => array(
                '@type' => 'Brand',
                'name' => get_bloginfo( 'name' ),
            ),
            'offers' => array(
                '@type' => 'Offer',
                'price' => $price,
                'priceCurrency' => $currency,
                'availability' => $availability,
                'url' => $url,
            ),
        );
        
        // Add product image if available
        if ( $image_url ) {
            $schema['image'] = array(
                '@type' => 'ImageObject',
                'url' => $image_url,
                'width' => $image_width,
                'height' => $image_height,
            );
        }
        
        // Add gallery images if available
        if ( ! empty( $gallery_images ) ) {
            if ( isset( $schema['image'] ) ) {
                $schema['image'] = array_merge( array( $schema['image'] ), $gallery_images );
            } else {
                $schema['image'] = $gallery_images;
            }
        }
        
        // Add reviews if available
        if ( ! empty( $reviews ) ) {
            $schema['review'] = $reviews;
            $schema['aggregateRating'] = array(
                '@type' => 'AggregateRating',
                'ratingValue' => $rating,
                'reviewCount' => $review_count,
                'bestRating' => '5',
                'worstRating' => '1',
            );
        }
        
        // Add sale price if available
        if ( $sale_price ) {
            $schema['offers']['price'] = $sale_price;
            $schema['offers']['priceValidUntil'] = date( 'c', strtotime( '+1 year' ) );
            
            if ( $regular_price ) {
                $schema['offers']['highPrice'] = $regular_price;
                $schema['offers']['lowPrice'] = $sale_price;
            }
        }
        
        // Add product categories
        $categories = wp_get_post_terms( $product->get_id(), 'product_cat' );
        
        if ( $categories ) {
            $schema['category'] = array();
            
            foreach ( $categories as $category ) {
                $schema['category'][] = $category->name;
            }
        }
        
        // Output schema
        $this->output_schema( $schema );
    }

    /**
     * Add BreadcrumbList schema markup
     */
    public function breadcrumblist_schema_markup() {
        // Skip on front page
        if ( is_front_page() ) {
            return;
        }
        
        // Get breadcrumbs
        $breadcrumbs = array();
        
        // Home page
        $breadcrumbs[] = array(
            '@type' => 'ListItem',
            'position' => 1,
            'name' => __( 'Home', 'aqualuxe' ),
            'item' => home_url( '/' ),
        );
        
        // Build breadcrumbs based on page type
        if ( is_singular( 'post' ) ) {
            // Blog page
            $blog_page_id = get_option( 'page_for_posts' );
            
            if ( $blog_page_id ) {
                $breadcrumbs[] = array(
                    '@type' => 'ListItem',
                    'position' => 2,
                    'name' => get_the_title( $blog_page_id ),
                    'item' => get_permalink( $blog_page_id ),
                );
            }
            
            // Categories
            $categories = get_the_category();
            
            if ( $categories ) {
                $category = $categories[0];
                
                $breadcrumbs[] = array(
                    '@type' => 'ListItem',
                    'position' => 3,
                    'name' => $category->name,
                    'item' => get_category_link( $category->term_id ),
                );
            }
            
            // Current post
            $breadcrumbs[] = array(
                '@type' => 'ListItem',
                'position' => 4,
                'name' => get_the_title(),
                'item' => get_permalink(),
            );
        } elseif ( is_singular( 'page' ) ) {
            // Current page
            $breadcrumbs[] = array(
                '@type' => 'ListItem',
                'position' => 2,
                'name' => get_the_title(),
                'item' => get_permalink(),
            );
        } elseif ( is_category() ) {
            // Current category
            $breadcrumbs[] = array(
                '@type' => 'ListItem',
                'position' => 2,
                'name' => single_cat_title( '', false ),
                'item' => get_category_link( get_query_var( 'cat' ) ),
            );
        } elseif ( is_tag() ) {
            // Current tag
            $breadcrumbs[] = array(
                '@type' => 'ListItem',
                'position' => 2,
                'name' => single_tag_title( '', false ),
                'item' => get_tag_link( get_query_var( 'tag_id' ) ),
            );
        } elseif ( is_author() ) {
            // Current author
            $breadcrumbs[] = array(
                '@type' => 'ListItem',
                'position' => 2,
                'name' => get_the_author(),
                'item' => get_author_posts_url( get_the_author_meta( 'ID' ) ),
            );
        } elseif ( is_year() ) {
            // Current year
            $breadcrumbs[] = array(
                '@type' => 'ListItem',
                'position' => 2,
                'name' => get_the_date( 'Y' ),
                'item' => get_year_link( get_query_var( 'year' ) ),
            );
        } elseif ( is_month() ) {
            // Year
            $breadcrumbs[] = array(
                '@type' => 'ListItem',
                'position' => 2,
                'name' => get_the_date( 'Y' ),
                'item' => get_year_link( get_query_var( 'year' ) ),
            );
            
            // Current month
            $breadcrumbs[] = array(
                '@type' => 'ListItem',
                'position' => 3,
                'name' => get_the_date( 'F' ),
                'item' => get_month_link( get_query_var( 'year' ), get_query_var( 'monthnum' ) ),
            );
        } elseif ( is_day() ) {
            // Year
            $breadcrumbs[] = array(
                '@type' => 'ListItem',
                'position' => 2,
                'name' => get_the_date( 'Y' ),
                'item' => get_year_link( get_query_var( 'year' ) ),
            );
            
            // Month
            $breadcrumbs[] = array(
                '@type' => 'ListItem',
                'position' => 3,
                'name' => get_the_date( 'F' ),
                'item' => get_month_link( get_query_var( 'year' ), get_query_var( 'monthnum' ) ),
            );
            
            // Current day
            $breadcrumbs[] = array(
                '@type' => 'ListItem',
                'position' => 4,
                'name' => get_the_date( 'j' ),
                'item' => get_day_link( get_query_var( 'year' ), get_query_var( 'monthnum' ), get_query_var( 'day' ) ),
            );
        } elseif ( is_search() ) {
            // Current search
            $breadcrumbs[] = array(
                '@type' => 'ListItem',
                'position' => 2,
                'name' => sprintf( __( 'Search results for: %s', 'aqualuxe' ), get_search_query() ),
                'item' => get_search_link( get_search_query() ),
            );
        } elseif ( is_404() ) {
            // 404 page
            $breadcrumbs[] = array(
                '@type' => 'ListItem',
                'position' => 2,
                'name' => __( 'Page not found', 'aqualuxe' ),
                'item' => home_url( $_SERVER['REQUEST_URI'] ),
            );
        }
        
        // WooCommerce breadcrumbs
        if ( function_exists( 'is_woocommerce' ) && is_woocommerce() ) {
            $breadcrumbs = array();
            
            // Home page
            $breadcrumbs[] = array(
                '@type' => 'ListItem',
                'position' => 1,
                'name' => __( 'Home', 'aqualuxe' ),
                'item' => home_url( '/' ),
            );
            
            // Shop page
            $shop_page_id = wc_get_page_id( 'shop' );
            
            if ( $shop_page_id ) {
                $breadcrumbs[] = array(
                    '@type' => 'ListItem',
                    'position' => 2,
                    'name' => get_the_title( $shop_page_id ),
                    'item' => get_permalink( $shop_page_id ),
                );
            }
            
            if ( is_product_category() ) {
                // Current category
                $breadcrumbs[] = array(
                    '@type' => 'ListItem',
                    'position' => 3,
                    'name' => single_term_title( '', false ),
                    'item' => get_term_link( get_queried_object() ),
                );
            } elseif ( is_product_tag() ) {
                // Current tag
                $breadcrumbs[] = array(
                    '@type' => 'ListItem',
                    'position' => 3,
                    'name' => single_term_title( '', false ),
                    'item' => get_term_link( get_queried_object() ),
                );
            } elseif ( is_product() ) {
                // Product category
                $categories = wp_get_post_terms( get_the_ID(), 'product_cat' );
                
                if ( $categories ) {
                    $category = $categories[0];
                    
                    $breadcrumbs[] = array(
                        '@type' => 'ListItem',
                        'position' => 3,
                        'name' => $category->name,
                        'item' => get_term_link( $category ),
                    );
                }
                
                // Current product
                $breadcrumbs[] = array(
                    '@type' => 'ListItem',
                    'position' => 4,
                    'name' => get_the_title(),
                    'item' => get_permalink(),
                );
            }
        }
        
        // Build schema
        $schema = array(
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $breadcrumbs,
        );
        
        // Output schema
        $this->output_schema( $schema );
    }

    /**
     * Output schema markup
     *
     * @param array $schema The schema data.
     */
    private function output_schema( $schema ) {
        echo '<script type="application/ld+json">' . wp_json_encode( $schema ) . '</script>' . "\n";
    }
}

// Initialize the class
new AquaLuxe_Schema();