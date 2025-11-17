<?php
/**
 * Helper functions for the AquaLuxe theme
 *
 * @package AquaLuxe
 */

/**
 * Check if WooCommerce is active
 *
 * @return bool
 */
function aqualuxe_is_woocommerce_active() {
    return class_exists( 'WooCommerce' );
}

/**
 * Check if current page is a WooCommerce page
 *
 * @return bool
 */
function aqualuxe_is_woocommerce_page() {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return false;
    }
    
    return ( is_woocommerce() || is_cart() || is_checkout() || is_account_page() );
}

/**
 * Get the current page layout
 *
 * @return string
 */
function aqualuxe_get_page_layout() {
    // Default layout
    $layout = 'right-sidebar';
    
    // Check if it's a WooCommerce page
    if ( aqualuxe_is_woocommerce_page() ) {
        if ( is_product() ) {
            $layout = get_theme_mod( 'aqualuxe_product_layout', 'no-sidebar' );
        } else {
            $layout = get_theme_mod( 'aqualuxe_shop_layout', 'left-sidebar' );
        }
    } else {
        // Check if it's a single post or page
        if ( is_singular() ) {
            // Get the layout meta value
            $meta_layout = get_post_meta( get_the_ID(), 'aqualuxe_page_layout', true );
            
            // If meta layout is set and not default, use it
            if ( ! empty( $meta_layout ) && 'default' !== $meta_layout ) {
                $layout = $meta_layout;
            } else {
                // Otherwise use the default layout from customizer
                if ( is_page() ) {
                    $layout = get_theme_mod( 'aqualuxe_page_layout', 'right-sidebar' );
                } else {
                    $layout = get_theme_mod( 'aqualuxe_post_layout', 'right-sidebar' );
                }
            }
        } elseif ( is_home() || is_archive() || is_search() ) {
            $layout = get_theme_mod( 'aqualuxe_blog_layout', 'right-sidebar' );
        }
    }
    
    return apply_filters( 'aqualuxe_page_layout', $layout );
}

/**
 * Check if the current page should display the sidebar
 *
 * @return bool
 */
function aqualuxe_has_sidebar() {
    $layout = aqualuxe_get_page_layout();
    
    return ( 'right-sidebar' === $layout || 'left-sidebar' === $layout );
}

/**
 * Get the container class based on the current page layout
 *
 * @return string
 */
function aqualuxe_get_container_class() {
    $layout = aqualuxe_get_page_layout();
    $container_class = 'container mx-auto px-4';
    
    if ( 'full-width' === $layout ) {
        $container_class = 'container-fluid px-0';
    }
    
    return apply_filters( 'aqualuxe_container_class', $container_class );
}

/**
 * Get the content class based on the current page layout
 *
 * @return string
 */
function aqualuxe_get_content_class() {
    $layout = aqualuxe_get_page_layout();
    $content_class = 'w-full';
    
    if ( 'right-sidebar' === $layout || 'left-sidebar' === $layout ) {
        $content_class = 'w-full lg:w-2/3';
        
        if ( 'left-sidebar' === $layout ) {
            $content_class .= ' lg:order-last';
        }
    }
    
    return apply_filters( 'aqualuxe_content_class', $content_class );
}

/**
 * Get the sidebar class based on the current page layout
 *
 * @return string
 */
function aqualuxe_get_sidebar_class() {
    $layout = aqualuxe_get_page_layout();
    $sidebar_class = 'w-full lg:w-1/3';
    
    if ( 'left-sidebar' === $layout ) {
        $sidebar_class .= ' lg:pr-8';
    } else {
        $sidebar_class .= ' lg:pl-8';
    }
    
    return apply_filters( 'aqualuxe_sidebar_class', $sidebar_class );
}

/**
 * Get theme mod with default value
 *
 * @param string $name
 * @param mixed $default
 * @return mixed
 */
function aqualuxe_get_theme_mod( $name, $default = '' ) {
    return get_theme_mod( $name, $default );
}

/**
 * Get the current language code
 *
 * @return string
 */
function aqualuxe_get_current_language() {
    $language = '';
    
    // WPML
    if ( defined( 'ICL_LANGUAGE_CODE' ) ) {
        $language = ICL_LANGUAGE_CODE;
    }
    
    // Polylang
    if ( function_exists( 'pll_current_language' ) ) {
        $language = pll_current_language();
    }
    
    return $language;
}

/**
 * Get the current currency code
 *
 * @return string
 */
function aqualuxe_get_current_currency() {
    $currency = '';
    
    // WooCommerce
    if ( function_exists( 'get_woocommerce_currency' ) ) {
        $currency = get_woocommerce_currency();
    }
    
    // WPML WooCommerce Multilingual
    if ( function_exists( 'wcml_get_woocommerce_currency_option' ) ) {
        $currency = wcml_get_woocommerce_currency_option();
    }
    
    return $currency;
}

/**
 * Check if dark mode is enabled
 *
 * @return bool
 */
function aqualuxe_is_dark_mode() {
    $dark_mode = false;
    
    // Check if dark mode is enabled in customizer
    $dark_mode_enabled = get_theme_mod( 'aqualuxe_dark_mode_enable', true );
    
    if ( $dark_mode_enabled ) {
        // Check if user has set a preference
        if ( isset( $_COOKIE['aqualuxe_dark_mode'] ) ) {
            $dark_mode = 'true' === $_COOKIE['aqualuxe_dark_mode'];
        } else {
            // Default to system preference
            $dark_mode = get_theme_mod( 'aqualuxe_dark_mode_default', false );
        }
    }
    
    return apply_filters( 'aqualuxe_is_dark_mode', $dark_mode );
}

/**
 * Get the body class for dark mode
 *
 * @return string
 */
function aqualuxe_get_dark_mode_class() {
    return aqualuxe_is_dark_mode() ? 'dark' : '';
}

/**
 * Get social sharing HTML
 *
 * @return string
 */
function aqualuxe_social_sharing() {
    if ( ! is_singular() ) {
        return;
    }
    
    $post_title = get_the_title();
    $post_url = get_permalink();
    $post_thumbnail = get_the_post_thumbnail_url( get_the_ID(), 'large' );
    
    $social_networks = array(
        'facebook' => array(
            'url'   => 'https://www.facebook.com/sharer/sharer.php?u=' . urlencode( $post_url ),
            'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"/></svg>',
            'label' => __( 'Share on Facebook', 'aqualuxe' ),
        ),
        'twitter' => array(
            'url'   => 'https://twitter.com/intent/tweet?text=' . urlencode( $post_title ) . '&url=' . urlencode( $post_url ),
            'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg>',
            'label' => __( 'Share on Twitter', 'aqualuxe' ),
        ),
        'linkedin' => array(
            'url'   => 'https://www.linkedin.com/shareArticle?mini=true&url=' . urlencode( $post_url ) . '&title=' . urlencode( $post_title ),
            'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M4.98 3.5c0 1.381-1.11 2.5-2.48 2.5s-2.48-1.119-2.48-2.5c0-1.38 1.11-2.5 2.48-2.5s2.48 1.12 2.48 2.5zm.02 4.5h-5v16h5v-16zm7.982 0h-4.968v16h4.969v-8.399c0-4.67 6.029-5.052 6.029 0v8.399h4.988v-10.131c0-7.88-8.922-7.593-11.018-3.714v-2.155z"/></svg>',
            'label' => __( 'Share on LinkedIn', 'aqualuxe' ),
        ),
        'pinterest' => array(
            'url'   => 'https://pinterest.com/pin/create/button/?url=' . urlencode( $post_url ) . '&media=' . urlencode( $post_thumbnail ) . '&description=' . urlencode( $post_title ),
            'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0c-6.627 0-12 5.372-12 12 0 5.084 3.163 9.426 7.627 11.174-.105-.949-.2-2.405.042-3.441.218-.937 1.407-5.965 1.407-5.965s-.359-.719-.359-1.782c0-1.668.967-2.914 2.171-2.914 1.023 0 1.518.769 1.518 1.69 0 1.029-.655 2.568-.994 3.995-.283 1.194.599 2.169 1.777 2.169 2.133 0 3.772-2.249 3.772-5.495 0-2.873-2.064-4.882-5.012-4.882-3.414 0-5.418 2.561-5.418 5.207 0 1.031.397 2.138.893 2.738.098.119.112.224.083.345l-.333 1.36c-.053.22-.174.267-.402.161-1.499-.698-2.436-2.889-2.436-4.649 0-3.785 2.75-7.262 7.929-7.262 4.163 0 7.398 2.967 7.398 6.931 0 4.136-2.607 7.464-6.227 7.464-1.216 0-2.359-.631-2.75-1.378l-.748 2.853c-.271 1.043-1.002 2.35-1.492 3.146 1.124.347 2.317.535 3.554.535 6.627 0 12-5.373 12-12 0-6.628-5.373-12-12-12z" fill-rule="evenodd" clip-rule="evenodd"/></svg>',
            'label' => __( 'Share on Pinterest', 'aqualuxe' ),
        ),
        'email' => array(
            'url'   => 'mailto:?subject=' . urlencode( $post_title ) . '&body=' . urlencode( $post_url ),
            'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>',
            'label' => __( 'Share via Email', 'aqualuxe' ),
        ),
    );
    
    $output = '<div class="social-sharing-buttons flex flex-wrap gap-2">';
    
    foreach ( $social_networks as $network => $data ) {
        $output .= sprintf(
            '<a href="%1$s" class="social-share-button %2$s-share inline-flex items-center px-3 py-2 rounded-md text-sm font-medium bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700" target="_blank" rel="noopener noreferrer" aria-label="%3$s">%4$s</a>',
            esc_url( $data['url'] ),
            esc_attr( $network ),
            esc_attr( $data['label'] ),
            $data['icon'] // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        );
    }
    
    $output .= '</div>';
    
    echo $output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Display related posts
 *
 * @param int $count
 * @return void
 */
function aqualuxe_related_posts( $count = 3 ) {
    if ( ! is_singular( 'post' ) ) {
        return;
    }
    
    $post_id = get_the_ID();
    $categories = get_the_category( $post_id );
    
    if ( empty( $categories ) ) {
        return;
    }
    
    $category_ids = array();
    foreach ( $categories as $category ) {
        $category_ids[] = $category->term_id;
    }
    
    $args = array(
        'post_type'      => 'post',
        'posts_per_page' => $count,
        'post_status'    => 'publish',
        'post__not_in'   => array( $post_id ),
        'category__in'   => $category_ids,
        'orderby'        => 'rand',
    );
    
    $related_posts = new WP_Query( $args );
    
    if ( ! $related_posts->have_posts() ) {
        return;
    }
    ?>
    <div class="related-posts mt-12 pt-8 border-t border-gray-200 dark:border-gray-700">
        <h3 class="text-2xl font-bold mb-6"><?php esc_html_e( 'Related Posts', 'aqualuxe' ); ?></h3>
        
        <div class="related-posts-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php
            while ( $related_posts->have_posts() ) :
                $related_posts->the_post();
                ?>
                <article class="related-post bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
                    <?php if ( has_post_thumbnail() ) : ?>
                        <a href="<?php the_permalink(); ?>" class="block">
                            <?php the_post_thumbnail( 'medium', array( 'class' => 'w-full h-40 object-cover' ) ); ?>
                        </a>
                    <?php endif; ?>
                    
                    <div class="p-4">
                        <h4 class="text-lg font-bold mb-2">
                            <a href="<?php the_permalink(); ?>" class="hover:text-primary"><?php the_title(); ?></a>
                        </h4>
                        
                        <div class="text-sm text-gray-600 dark:text-gray-400">
                            <?php echo get_the_date(); ?>
                        </div>
                    </div>
                </article>
            <?php endwhile; ?>
            <?php wp_reset_postdata(); ?>
        </div>
    </div>
    <?php
}

/**
 * Custom comment callback
 *
 * @param object $comment
 * @param array $args
 * @param int $depth
 * @return void
 */
function aqualuxe_comment_callback( $comment, $args, $depth ) {
    $tag = ( 'div' === $args['style'] ) ? 'div' : 'li';
    ?>
    <<?php echo $tag; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> id="comment-<?php comment_ID(); ?>" <?php comment_class( 'comment-item bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6' ); ?>>
        <article id="div-comment-<?php comment_ID(); ?>" class="comment-body">
            <div class="comment-meta flex items-start">
                <div class="comment-author vcard mr-4">
                    <?php
                    if ( 0 !== $args['avatar_size'] ) {
                        echo get_avatar( $comment, $args['avatar_size'], '', '', array( 'class' => 'rounded-full' ) );
                    }
                    ?>
                </div>
                
                <div class="comment-metadata flex-grow">
                    <div class="comment-author-name font-bold">
                        <?php printf( '%s', get_comment_author_link() ); ?>
                    </div>
                    
                    <div class="comment-date text-sm text-gray-600 dark:text-gray-400">
                        <a href="<?php echo esc_url( get_comment_link( $comment ) ); ?>">
                            <time datetime="<?php comment_time( 'c' ); ?>">
                                <?php
                                printf(
                                    /* translators: 1: comment date, 2: comment time */
                                    esc_html__( '%1$s at %2$s', 'aqualuxe' ),
                                    esc_html( get_comment_date() ),
                                    esc_html( get_comment_time() )
                                );
                                ?>
                            </time>
                        </a>
                        
                        <?php edit_comment_link( __( 'Edit', 'aqualuxe' ), ' <span class="edit-link">', '</span>' ); ?>
                    </div>
                </div>
            </div>
            
            <div class="comment-content prose mt-4">
                <?php comment_text(); ?>
            </div>
            
            <div class="reply mt-4">
                <?php
                comment_reply_link(
                    array_merge(
                        $args,
                        array(
                            'add_below' => 'div-comment',
                            'depth'     => $depth,
                            'max_depth' => $args['max_depth'],
                            'before'    => '<div class="reply-link">',
                            'after'     => '</div>',
                        )
                    )
                );
                ?>
            </div>
        </article>
    <?php
}