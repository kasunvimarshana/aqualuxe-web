<?php
/**
 * Custom hooks for AquaLuxe theme
 * 
 * @package AquaLuxe
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Add custom body classes
 */
function aqualuxe_body_classes( $classes ) {
    // Adds a class of hfeed to non-singular pages.
    if ( ! is_singular() ) {
        $classes[] = 'hfeed';
    }
    
    // Adds a class of no-sidebar when there is no sidebar present.
    if ( ! is_active_sidebar( 'main-sidebar' ) ) {
        $classes[] = 'no-sidebar';
    }
    
    // Adds a class for WooCommerce pages
    if ( class_exists( 'WooCommerce' ) && ( is_woocommerce() || is_cart() || is_checkout() || is_account_page() ) ) {
        $classes[] = 'woocommerce-page';
    }
    
    // Adds a class for shop pages
    if ( class_exists( 'WooCommerce' ) && is_shop() ) {
        $classes[] = 'shop-page';
    }
    
    // Adds a class for product pages
    if ( class_exists( 'WooCommerce' ) && is_product() ) {
        $classes[] = 'product-page';
    }
    
    return $classes;
}
add_filter( 'body_class', 'aqualuxe_body_classes' );

/**
 * Add custom classes to posts
 */
function aqualuxe_post_classes( $classes ) {
    // Adds a class for sticky posts
    if ( is_sticky() ) {
        $classes[] = 'sticky-post';
    }
    
    // Adds a class for password-protected posts
    if ( post_password_required() ) {
        $classes[] = 'password-protected';
    }
    
    return $classes;
}
add_filter( 'post_class', 'aqualuxe_post_classes' );

/**
 * Add custom classes to WooCommerce products
 */
function aqualuxe_product_classes( $classes, $class, $product_id ) {
    // Adds a class for featured products
    if ( class_exists( 'WooCommerce' ) ) {
        $product = wc_get_product( $product_id );
        if ( $product && $product->is_featured() ) {
            $classes[] = 'featured-product';
        }
        
        // Adds a class for on-sale products
        if ( $product && $product->is_on_sale() ) {
            $classes[] = 'on-sale-product';
        }
    }
    
    return $classes;
}
add_filter( 'post_class', 'aqualuxe_product_classes', 10, 3 );

/**
 * Add custom image sizes attribute to enhance responsive image functionality
 */
function aqualuxe_content_image_sizes_attr( $sizes, $size ) {
    $width = $size[0];
    
    // Make image sizes responsive
    if ( is_singular() && $width >= 800 ) {
        return '(max-width: 800px) 100vw, 800px';
    }
    
    if ( is_singular() && $width >= 600 ) {
        return '(max-width: 600px) 100vw, 600px';
    }
    
    return $sizes;
}
add_filter( 'wp_calculate_image_sizes', 'aqualuxe_content_image_sizes_attr', 10, 2 );

/**
 * Add custom image attributes
 */
function aqualuxe_post_thumbnail_sizes_attr( $attr, $attachment, $size ) {
    // Make post thumbnails responsive
    if ( is_singular() ) {
        $attr['sizes'] = '(max-width: 800px) 100vw, 800px';
    } else {
        $attr['sizes'] = '(max-width: 600px) 100vw, 600px';
    }
    
    return $attr;
}
add_filter( 'wp_get_attachment_image_attributes', 'aqualuxe_post_thumbnail_sizes_attr', 10, 3 );

/**
 * Add custom excerpt more link
 */
function aqualuxe_excerpt_more( $more ) {
    if ( is_admin() ) {
        return $more;
    }
    
    return '...';
}
add_filter( 'excerpt_more', 'aqualuxe_excerpt_more' );

/**
 * Add custom excerpt length
 */
function aqualuxe_excerpt_length( $length ) {
    if ( is_admin() ) {
        return $length;
    }
    
    return 30;
}
add_filter( 'excerpt_length', 'aqualuxe_excerpt_length', 999 );

/**
 * Add custom comment form fields
 */
function aqualuxe_comment_form_fields( $fields ) {
    $commenter = wp_get_current_commenter();
    $req = get_option( 'require_name_email' );
    $aria_req = ( $req ? " aria-required='true'" : '' );
    
    $fields['author'] = '<p class="comment-form-author">' . 
        '<label for="author">' . __( 'Name', 'aqualuxe' ) . ( $req ? ' <span class="required">*</span>' : '' ) . '</label> ' .
        '<input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30"' . $aria_req . ' /></p>';
    
    $fields['email'] = '<p class="comment-form-email">' . 
        '<label for="email">' . __( 'Email', 'aqualuxe' ) . ( $req ? ' <span class="required">*</span>' : '' ) . '</label> ' .
        '<input id="email" name="email" type="email" value="' . esc_attr( $commenter['comment_author_email'] ) . '" size="30"' . $aria_req . ' /></p>';
    
    $fields['url'] = '<p class="comment-form-url">' . 
        '<label for="url">' . __( 'Website', 'aqualuxe' ) . '</label> ' .
        '<input id="url" name="url" type="url" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" /></p>';
    
    return $fields;
}
add_filter( 'comment_form_default_fields', 'aqualuxe_comment_form_fields' );

/**
 * Add custom comment form textarea
 */
function aqualuxe_comment_form_field_comment( $field ) {
    $field = '<p class="comment-form-comment">' . 
        '<label for="comment">' . _x( 'Comment', 'noun', 'aqualuxe' ) . '</label> ' .
        '<textarea id="comment" name="comment" cols="45" rows="8" aria-required="true"></textarea></p>';
    
    return $field;
}
add_filter( 'comment_form_field_comment', 'aqualuxe_comment_form_field_comment' );

/**
 * Add custom comment form submit button
 */
function aqualuxe_comment_form_submit_button( $submit_button ) {
    $submit_button = '<p class="form-submit">' . 
        '<input name="submit" type="submit" id="submit" class="submit" value="' . __( 'Post Comment', 'aqualuxe' ) . '" /> ' .
        '<input type="hidden" name="comment_post_ID" value="' . get_the_ID() . '" />' .
        '<input type="hidden" name="comment_parent" id="comment_parent" value="0" />' .
        '</p>';
    
    return $submit_button;
}
add_filter( 'comment_form_submit_button', 'aqualuxe_comment_form_submit_button' );

/**
 * Add custom login form
 */
function aqualuxe_login_form() {
    if ( is_user_logged_in() ) {
        return;
    }
    
    $redirect = isset( $_REQUEST['redirect_to'] ) ? $_REQUEST['redirect_to'] : home_url();
    
    echo '<div class="login-form">';
    wp_login_form( array(
        'echo' => false,
        'redirect' => $redirect,
        'form_id' => 'loginform',
        'label_username' => __( 'Username or Email', 'aqualuxe' ),
        'label_password' => __( 'Password', 'aqualuxe' ),
        'label_remember' => __( 'Remember Me', 'aqualuxe' ),
        'label_log_in' => __( 'Log In', 'aqualuxe' ),
        'id_username' => 'user_login',
        'id_password' => 'user_pass',
        'id_remember' => 'rememberme',
        'id_submit' => 'wp-submit',
        'remember' => true,
        'value_username' => '',
        'value_remember' => false,
    ) );
    echo '</div>';
}

/**
 * Add custom registration form
 */
function aqualuxe_registration_form() {
    if ( is_user_logged_in() ) {
        return;
    }
    
    if ( ! get_option( 'users_can_register' ) ) {
        return;
    }
    
    $redirect = isset( $_REQUEST['redirect_to'] ) ? $_REQUEST['redirect_to'] : home_url();
    
    echo '<div class="registration-form">';
    echo '<form name="registerform" id="registerform" action="' . esc_url( site_url( 'wp-login.php?action=register', 'login_post' ) ) . '" method="post">';
    echo '<p class="form-row form-row-wide">';
    echo '<label for="user_login">' . __( 'Username', 'aqualuxe' ) . ' <span class="required">*</span></label>';
    echo '<input type="text" name="user_login" id="user_login" class="input" value="" size="20" /></p>';
    echo '<p class="form-row form-row-wide">';
    echo '<label for="user_email">' . __( 'Email', 'aqualuxe' ) . ' <span class="required">*</span></label>';
    echo '<input type="email" name="user_email" id="user_email" class="input" value="" size="25" /></p>';
    echo '<p id="reg_passmail">' . __( 'Registration confirmation will be emailed to you.', 'aqualuxe' ) . '</p>';
    echo '<p class="form-row">';
    echo '<input type="hidden" name="redirect_to" value="' . esc_url( $redirect ) . '" />';
    echo '<input type="submit" name="wp-submit" id="wp-submit" class="button button-primary" value="' . esc_attr__( 'Register', 'aqualuxe' ) . '" /></p>';
    echo '</form>';
    echo '</div>';
}

/**
 * Add custom search form
 */
function aqualuxe_search_form( $form ) {
    $form = '<form role="search" method="get" id="searchform" class="search-form" action="' . home_url( '/' ) . '">
    <label class="screen-reader-text" for="s">' . __( 'Search for:', 'aqualuxe' ) . '</label>
    <input type="text" value="' . get_search_query() . '" name="s" id="s" placeholder="' . esc_attr__( 'Search...', 'aqualuxe' ) . '" />
    <input type="submit" id="searchsubmit" value="'. esc_attr__( 'Search', 'aqualuxe' ) .'" />
    </form>';
    
    return $form;
}
add_filter( 'get_search_form', 'aqualuxe_search_form' );

/**
 * Add custom password form
 */
function aqualuxe_password_form() {
    global $post;
    
    $label = 'pwbox-' . ( empty( $post->ID ) ? rand() : $post->ID );
    $output = '<form action="' . esc_url( site_url( 'wp-login.php?action=postpass', 'login_post' ) ) . '" class="post-password-form" method="post">
    <p>' . __( 'This content is password protected. To view it please enter your password below:', 'aqualuxe' ) . '</p>
    <p><label for="' . $label . '">' . __( 'Password:', 'aqualuxe' ) . ' <input name="post_password" id="' . $label . '" type="password" size="20" /></label> <input type="submit" name="Submit" value="' . esc_attr_x( 'Enter', 'post password form', 'aqualuxe' ) . '" /></p></form>
    ';
    
    return $output;
}
add_filter( 'the_password_form', 'aqualuxe_password_form' );

/**
 * Add custom gallery shortcode
 */
function aqualuxe_gallery_shortcode( $output, $atts, $content = false, $tag = false ) {
    // Customize gallery output
    return $output;
}
add_filter( 'post_gallery', 'aqualuxe_gallery_shortcode', 10, 4 );

/**
 * Add custom caption shortcode
 */
function aqualuxe_caption_shortcode( $output, $atts, $content ) {
    // Customize caption output
    return $output;
}
add_filter( 'img_caption_shortcode', 'aqualuxe_caption_shortcode', 10, 3 );

/**
 * Add custom embed HTML
 */
function aqualuxe_embed_html( $html, $url, $attr, $post_ID ) {
    // Customize embed output
    return $html;
}
add_filter( 'embed_oembed_html', 'aqualuxe_embed_html', 10, 4 );

/**
 * Add custom admin footer text
 */
function aqualuxe_admin_footer_text( $footer_text ) {
    if ( ! current_user_can( 'manage_options' ) ) {
        return $