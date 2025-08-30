<?php
/**
 * AJAX handlers for AquaLuxe Theme
 *
 * @package AquaLuxe
 */

/**
 * Quick View AJAX handler
 */
function aqualuxe_quick_view_ajax() {
    // Check nonce
    if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'aqualuxe-nonce' ) ) {
        wp_send_json_error( 'Invalid nonce' );
    }

    // Check product ID
    if ( ! isset( $_POST['product_id'] ) ) {
        wp_send_json_error( 'No product ID provided' );
    }

    $product_id = absint( $_POST['product_id'] );
    $product = wc_get_product( $product_id );

    if ( ! $product ) {
        wp_send_json_error( 'Invalid product' );
    }

    ob_start();
    ?>
    <div class="quick-view-content flex flex-col md:flex-row gap-8">
        <div class="quick-view-images w-full md:w-1/2">
            <?php
            if ( $product->get_image_id() ) {
                echo wp_get_attachment_image( $product->get_image_id(), 'woocommerce_single', false, array( 'class' => 'w-full h-auto rounded-lg' ) );
                
                // Gallery images
                $attachment_ids = $product->get_gallery_image_ids();
                if ( $attachment_ids ) {
                    echo '<div class="quick-view-gallery grid grid-cols-4 gap-2 mt-4">';
                    foreach ( $attachment_ids as $attachment_id ) {
                        echo '<div class="quick-view-gallery-item cursor-pointer">';
                        echo wp_get_attachment_image( $attachment_id, 'thumbnail', false, array( 'class' => 'w-full h-auto rounded' ) );
                        echo '</div>';
                    }
                    echo '</div>';
                }
            } else {
                echo '<div class="woocommerce-product-gallery__image--placeholder">';
                echo wc_placeholder_img( 'woocommerce_single' );
                echo '</div>';
            }
            ?>
        </div>

        <div class="quick-view-details w-full md:w-1/2">
            <h2 class="product-title text-2xl font-bold mb-2 text-gray-900 dark:text-gray-100"><?php echo esc_html( $product->get_name() ); ?></h2>
            
            <div class="product-price text-xl font-bold text-primary-600 dark:text-primary-400 mb-4">
                <?php echo wp_kses_post( $product->get_price_html() ); ?>
            </div>
            
            <div class="product-rating mb-4">
                <?php
                if ( $product->get_average_rating() ) {
                    echo wc_get_rating_html( $product->get_average_rating() );
                    
                    if ( $product->get_review_count() ) {
                        echo '<span class="review-count text-sm text-gray-600 dark:text-gray-400 ml-2">(' . esc_html( $product->get_review_count() ) . ' ' . esc_html__( 'reviews', 'aqualuxe' ) . ')</span>';
                    }
                }
                ?>
            </div>
            
            <div class="product-short-description text-gray-700 dark:text-gray-300 mb-6">
                <?php echo wp_kses_post( $product->get_short_description() ); ?>
            </div>
            
            <div class="product-meta mb-6">
                <?php if ( $product->get_sku() ) : ?>
                    <div class="product-sku text-sm text-gray-600 dark:text-gray-400 mb-1">
                        <span class="font-medium"><?php esc_html_e( 'SKU:', 'aqualuxe' ); ?></span> <?php echo esc_html( $product->get_sku() ); ?>
                    </div>
                <?php endif; ?>
                
                <?php if ( $product->get_stock_status() ) : ?>
                    <div class="product-stock text-sm <?php echo esc_attr( 'instock' === $product->get_stock_status() ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' ); ?> mb-1">
                        <span class="font-medium"><?php esc_html_e( 'Availability:', 'aqualuxe' ); ?></span> 
                        <?php 
                        if ( 'instock' === $product->get_stock_status() ) {
                            esc_html_e( 'In Stock', 'aqualuxe' );
                        } else {
                            esc_html_e( 'Out of Stock', 'aqualuxe' );
                        }
                        ?>
                    </div>
                <?php endif; ?>
                
                <?php if ( $product->get_categories() ) : ?>
                    <div class="product-categories text-sm text-gray-600 dark:text-gray-400">
                        <span class="font-medium"><?php esc_html_e( 'Categories:', 'aqualuxe' ); ?></span> <?php echo wp_kses_post( $product->get_categories() ); ?>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="product-actions">
                <?php
                if ( $product->is_type( 'simple' ) ) {
                    echo '<form class="cart" action="' . esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product->get_permalink() ) ) . '" method="post" enctype="multipart/form-data">';
                    
                    echo woocommerce_quantity_input(
                        array(
                            'min_value'   => apply_filters( 'woocommerce_quantity_input_min', $product->get_min_purchase_quantity(), $product ),
                            'max_value'   => apply_filters( 'woocommerce_quantity_input_max', $product->get_max_purchase_quantity(), $product ),
                            'input_value' => isset( $_POST['quantity'] ) ? wc_stock_amount( wp_unslash( $_POST['quantity'] ) ) : $product->get_min_purchase_quantity(),
                        ),
                        $product
                    );
                    
                    echo '<button type="submit" name="add-to-cart" value="' . esc_attr( $product->get_id() ) . '" class="single_add_to_cart_button button alt bg-primary-600 hover:bg-primary-700 text-white font-medium py-2 px-4 rounded transition-colors duration-300 ml-2">' . esc_html( $product->single_add_to_cart_text() ) . '</button>';
                    
                    echo '</form>';
                } elseif ( $product->is_type( 'variable' ) ) {
                    echo '<p class="quick-view-variable-notice text-gray-600 dark:text-gray-400">' . esc_html__( 'This product has options. Please view the full product page to select options.', 'aqualuxe' ) . '</p>';
                    echo '<a href="' . esc_url( $product->get_permalink() ) . '" class="button view-product-button bg-primary-600 hover:bg-primary-700 text-white font-medium py-2 px-4 rounded transition-colors duration-300 inline-block mt-2">' . esc_html__( 'View Product', 'aqualuxe' ) . '</a>';
                } else {
                    echo '<a href="' . esc_url( $product->get_permalink() ) . '" class="button view-product-button bg-primary-600 hover:bg-primary-700 text-white font-medium py-2 px-4 rounded transition-colors duration-300 inline-block">' . esc_html__( 'View Product', 'aqualuxe' ) . '</a>';
                }
                
                // Wishlist button if enabled
                if ( get_theme_mod( 'aqualuxe_wishlist', true ) ) {
                    echo '<button class="wishlist-btn bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 font-medium py-2 px-4 rounded transition-colors duration-300 ml-2" data-product-id="' . esc_attr( $product->get_id() ) . '">';
                    echo '<i class="far fa-heart"></i> <span class="wishlist-text">' . esc_html__( 'Add to Wishlist', 'aqualuxe' ) . '</span>';
                    echo '</button>';
                }
                ?>
            </div>
        </div>
    </div>
    <?php
    $output = ob_get_clean();
    
    wp_send_json_success( $output );
}
add_action( 'wp_ajax_aqualuxe_quick_view', 'aqualuxe_quick_view_ajax' );
add_action( 'wp_ajax_nopriv_aqualuxe_quick_view', 'aqualuxe_quick_view_ajax' );

/**
 * Wishlist AJAX handler
 */
function aqualuxe_wishlist_ajax() {
    // Check nonce
    if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'aqualuxe-nonce' ) ) {
        wp_send_json_error( 'Invalid nonce' );
    }

    // Check product ID
    if ( ! isset( $_POST['product_id'] ) ) {
        wp_send_json_error( 'No product ID provided' );
    }

    $product_id = absint( $_POST['product_id'] );
    $action = isset( $_POST['wishlist_action'] ) ? sanitize_text_field( wp_unslash( $_POST['wishlist_action'] ) ) : 'add';
    
    // Get current wishlist
    $wishlist = array();
    if ( isset( $_COOKIE['aqualuxe_wishlist'] ) ) {
        $wishlist = json_decode( sanitize_text_field( wp_unslash( $_COOKIE['aqualuxe_wishlist'] ) ), true );
    }
    
    if ( 'add' === $action ) {
        // Add product to wishlist
        if ( ! in_array( $product_id, $wishlist, true ) ) {
            $wishlist[] = $product_id;
        }
        
        $message = __( 'Product added to wishlist', 'aqualuxe' );
        $button_text = __( 'Remove from Wishlist', 'aqualuxe' );
        $icon_class = 'fas fa-heart';
    } else {
        // Remove product from wishlist
        $key = array_search( $product_id, $wishlist, true );
        if ( false !== $key ) {
            unset( $wishlist[ $key ] );
            $wishlist = array_values( $wishlist ); // Re-index array
        }
        
        $message = __( 'Product removed from wishlist', 'aqualuxe' );
        $button_text = __( 'Add to Wishlist', 'aqualuxe' );
        $icon_class = 'far fa-heart';
    }
    
    // Save wishlist to cookie
    setcookie( 'aqualuxe_wishlist', json_encode( $wishlist ), time() + ( 30 * DAY_IN_SECONDS ), COOKIEPATH, COOKIE_DOMAIN );
    
    wp_send_json_success( array(
        'message'     => $message,
        'button_text' => $button_text,
        'icon_class'  => $icon_class,
        'count'       => count( $wishlist ),
    ) );
}
add_action( 'wp_ajax_aqualuxe_wishlist', 'aqualuxe_wishlist_ajax' );
add_action( 'wp_ajax_nopriv_aqualuxe_wishlist', 'aqualuxe_wishlist_ajax' );

/**
 * Filter products AJAX handler
 */
function aqualuxe_filter_products_ajax() {
    // Check nonce
    if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'aqualuxe-nonce' ) ) {
        wp_send_json_error( 'Invalid nonce' );
    }

    $category = isset( $_POST['category'] ) ? sanitize_text_field( wp_unslash( $_POST['category'] ) ) : '';
    $min_price = isset( $_POST['min_price'] ) ? floatval( $_POST['min_price'] ) : 0;
    $max_price = isset( $_POST['max_price'] ) ? floatval( $_POST['max_price'] ) : 9999999;
    $orderby = isset( $_POST['orderby'] ) ? sanitize_text_field( wp_unslash( $_POST['orderby'] ) ) : 'date';
    $order = isset( $_POST['order'] ) ? sanitize_text_field( wp_unslash( $_POST['order'] ) ) : 'DESC';
    $paged = isset( $_POST['paged'] ) ? absint( $_POST['paged'] ) : 1;
    
    $args = array(
        'post_type'      => 'product',
        'posts_per_page' => get_theme_mod( 'aqualuxe_products_per_page', 12 ),
        'paged'          => $paged,
        'orderby'        => $orderby,
        'order'          => $order,
        'meta_query'     => array(
            array(
                'key'     => '_price',
                'value'   => array( $min_price, $max_price ),
                'compare' => 'BETWEEN',
                'type'    => 'NUMERIC',
            ),
        ),
    );
    
    if ( ! empty( $category ) && 'all' !== $category ) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'product_cat',
                'field'    => 'slug',
                'terms'    => $category,
            ),
        );
    }
    
    $products = new WP_Query( $args );
    
    ob_start();
    
    if ( $products->have_posts() ) {
        woocommerce_product_loop_start();
        
        while ( $products->have_posts() ) {
            $products->the_post();
            wc_get_template_part( 'content', 'product' );
        }
        
        woocommerce_product_loop_end();
        
        // Pagination
        echo '<div class="aqualuxe-pagination">';
        echo paginate_links( array(
            'base'      => add_query_arg( 'paged', '%#%' ),
            'format'    => '?paged=%#%',
            'current'   => max( 1, $paged ),
            'total'     => $products->max_num_pages,
            'prev_text' => '&larr;',
            'next_text' => '&rarr;',
            'type'      => 'list',
            'end_size'  => 3,
            'mid_size'  => 3,
        ) );
        echo '</div>';
    } else {
        echo '<p class="woocommerce-info">' . esc_html__( 'No products found', 'aqualuxe' ) . '</p>';
    }
    
    wp_reset_postdata();
    
    $output = ob_get_clean();
    
    wp_send_json_success( array(
        'html'       => $output,
        'found'      => $products->found_posts,
        'max_pages'  => $products->max_num_pages,
    ) );
}
add_action( 'wp_ajax_aqualuxe_filter_products', 'aqualuxe_filter_products_ajax' );
add_action( 'wp_ajax_nopriv_aqualuxe_filter_products', 'aqualuxe_filter_products_ajax' );

/**
 * Newsletter subscription AJAX handler
 */
function aqualuxe_newsletter_subscription_ajax() {
    // Check nonce
    if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'aqualuxe-nonce' ) ) {
        wp_send_json_error( 'Invalid nonce' );
    }

    // Check email
    if ( ! isset( $_POST['email'] ) || ! is_email( $_POST['email'] ) ) {
        wp_send_json_error( __( 'Please enter a valid email address', 'aqualuxe' ) );
    }

    $email = sanitize_email( wp_unslash( $_POST['email'] ) );
    $name = isset( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : '';
    
    // Store subscription in database
    $subscription = array(
        'email'     => $email,
        'name'      => $name,
        'date'      => current_time( 'mysql' ),
        'ip'        => sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ),
        'confirmed' => false,
    );
    
    $subscriptions = get_option( 'aqualuxe_newsletter_subscriptions', array() );
    
    // Check if email already exists
    foreach ( $subscriptions as $key => $sub ) {
        if ( $sub['email'] === $email ) {
            wp_send_json_error( __( 'This email is already subscribed', 'aqualuxe' ) );
        }
    }
    
    $subscriptions[] = $subscription;
    update_option( 'aqualuxe_newsletter_subscriptions', $subscriptions );
    
    // Send confirmation email
    $subject = sprintf( __( 'Confirm your subscription to %s newsletter', 'aqualuxe' ), get_bloginfo( 'name' ) );
    
    $token = wp_generate_password( 32, false );
    set_transient( 'aqualuxe_confirm_' . $token, $email, DAY_IN_SECONDS );
    
    $confirm_url = add_query_arg( array(
        'aqualuxe_confirm' => $token,
    ), home_url() );
    
    $message = sprintf(
        __( 'Hello%s,

Thank you for subscribing to our newsletter. Please confirm your subscription by clicking the link below:

%s

If you did not request this subscription, please ignore this email.

Regards,
%s', 'aqualuxe' ),
        $name ? ' ' . $name : '',
        $confirm_url,
        get_bloginfo( 'name' )
    );
    
    $headers = array( 'Content-Type: text/plain; charset=UTF-8' );
    
    $sent = wp_mail( $email, $subject, $message, $headers );
    
    if ( $sent ) {
        wp_send_json_success( __( 'Thank you for subscribing! Please check your email to confirm your subscription.', 'aqualuxe' ) );
    } else {
        wp_send_json_error( __( 'There was an error sending the confirmation email. Please try again later.', 'aqualuxe' ) );
    }
}
add_action( 'wp_ajax_aqualuxe_newsletter_subscription', 'aqualuxe_newsletter_subscription_ajax' );
add_action( 'wp_ajax_nopriv_aqualuxe_newsletter_subscription', 'aqualuxe_newsletter_subscription_ajax' );

/**
 * Handle newsletter confirmation
 */
function aqualuxe_handle_newsletter_confirmation() {
    if ( isset( $_GET['aqualuxe_confirm'] ) ) {
        $token = sanitize_text_field( wp_unslash( $_GET['aqualuxe_confirm'] ) );
        $email = get_transient( 'aqualuxe_confirm_' . $token );
        
        if ( $email ) {
            $subscriptions = get_option( 'aqualuxe_newsletter_subscriptions', array() );
            
            foreach ( $subscriptions as $key => $sub ) {
                if ( $sub['email'] === $email ) {
                    $subscriptions[ $key ]['confirmed'] = true;
                    break;
                }
            }
            
            update_option( 'aqualuxe_newsletter_subscriptions', $subscriptions );
            delete_transient( 'aqualuxe_confirm_' . $token );
            
            add_action( 'wp_footer', function() {
                ?>
                <div class="aqualuxe-notification fixed bottom-4 right-4 bg-green-600 text-white p-4 rounded shadow-lg z-50">
                    <p><?php esc_html_e( 'Thank you! Your subscription has been confirmed.', 'aqualuxe' ); ?></p>
                </div>
                <script>
                    setTimeout(function() {
                        document.querySelector('.aqualuxe-notification').style.display = 'none';
                    }, 5000);
                </script>
                <?php
            } );
        }
    }
}
add_action( 'template_redirect', 'aqualuxe_handle_newsletter_confirmation' );

/**
 * Contact form AJAX handler
 */
function aqualuxe_contact_form_ajax() {
    // Check nonce
    if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'aqualuxe-nonce' ) ) {
        wp_send_json_error( 'Invalid nonce' );
    }

    // Check required fields
    $required_fields = array( 'name', 'email', 'subject', 'message' );
    $errors = array();
    
    foreach ( $required_fields as $field ) {
        if ( ! isset( $_POST[ $field ] ) || empty( $_POST[ $field ] ) ) {
            $errors[ $field ] = sprintf( __( '%s is required', 'aqualuxe' ), ucfirst( $field ) );
        }
    }
    
    if ( ! empty( $errors ) ) {
        wp_send_json_error( array(
            'message' => __( 'Please fill in all required fields', 'aqualuxe' ),
            'errors'  => $errors,
        ) );
    }
    
    // Validate email
    if ( ! is_email( $_POST['email'] ) ) {
        $errors['email'] = __( 'Please enter a valid email address', 'aqualuxe' );
        wp_send_json_error( array(
            'message' => __( 'Please enter a valid email address', 'aqualuxe' ),
            'errors'  => $errors,
        ) );
    }
    
    // Get form data
    $name = sanitize_text_field( wp_unslash( $_POST['name'] ) );
    $email = sanitize_email( wp_unslash( $_POST['email'] ) );
    $subject = sanitize_text_field( wp_unslash( $_POST['subject'] ) );
    $message = sanitize_textarea_field( wp_unslash( $_POST['message'] ) );
    $phone = isset( $_POST['phone'] ) ? sanitize_text_field( wp_unslash( $_POST['phone'] ) ) : '';
    
    // Store contact in database
    $contact = array(
        'name'    => $name,
        'email'   => $email,
        'subject' => $subject,
        'message' => $message,
        'phone'   => $phone,
        'date'    => current_time( 'mysql' ),
        'ip'      => sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ),
        'status'  => 'unread',
    );
    
    $contacts = get_option( 'aqualuxe_contact_submissions', array() );
    $contacts[] = $contact;
    update_option( 'aqualuxe_contact_submissions', $contacts );
    
    // Send email notification
    $to = get_option( 'admin_email' );
    $subject = sprintf( __( '[%s] New Contact Form Submission: %s', 'aqualuxe' ), get_bloginfo( 'name' ), $subject );
    
    $email_message = sprintf(
        __( 'Name: %1$s
Email: %2$s
Phone: %3$s
Subject: %4$s

Message:
%5$s

--
This email was sent from the contact form on %6$s (%7$s)', 'aqualuxe' ),
        $name,
        $email,
        $phone ? $phone : __( 'Not provided', 'aqualuxe' ),
        $subject,
        $message,
        get_bloginfo( 'name' ),
        home_url()
    );
    
    $headers = array(
        'Content-Type: text/plain; charset=UTF-8',
        'Reply-To: ' . $name . ' <' . $email . '>',
    );
    
    $sent = wp_mail( $to, $subject, $email_message, $headers );
    
    if ( $sent ) {
        // Send auto-reply to user
        $auto_reply_subject = sprintf( __( 'Thank you for contacting %s', 'aqualuxe' ), get_bloginfo( 'name' ) );
        
        $auto_reply_message = sprintf(
            __( 'Hello %1$s,

Thank you for contacting us. We have received your message and will respond to you as soon as possible.

Here is a copy of your message:

Subject: %2$s

%3$s

Regards,
%4$s Team', 'aqualuxe' ),
            $name,
            $subject,
            $message,
            get_bloginfo( 'name' )
        );
        
        $auto_reply_headers = array(
            'Content-Type: text/plain; charset=UTF-8',
            'From: ' . get_bloginfo( 'name' ) . ' <' . $to . '>',
        );
        
        wp_mail( $email, $auto_reply_subject, $auto_reply_message, $auto_reply_headers );
        
        wp_send_json_success( __( 'Thank you for your message! We will get back to you as soon as possible.', 'aqualuxe' ) );
    } else {
        wp_send_json_error( __( 'There was an error sending your message. Please try again later.', 'aqualuxe' ) );
    }
}
add_action( 'wp_ajax_aqualuxe_contact_form', 'aqualuxe_contact_form_ajax' );
add_action( 'wp_ajax_nopriv_aqualuxe_contact_form', 'aqualuxe_contact_form_ajax' );

/**
 * Load more posts AJAX handler
 */
function aqualuxe_load_more_posts_ajax() {
    // Check nonce
    if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'aqualuxe-nonce' ) ) {
        wp_send_json_error( 'Invalid nonce' );
    }

    $paged = isset( $_POST['paged'] ) ? absint( $_POST['paged'] ) : 1;
    $category = isset( $_POST['category'] ) ? absint( $_POST['category'] ) : 0;
    $tag = isset( $_POST['tag'] ) ? absint( $_POST['tag'] ) : 0;
    $author = isset( $_POST['author'] ) ? absint( $_POST['author'] ) : 0;
    $search = isset( $_POST['search'] ) ? sanitize_text_field( wp_unslash( $_POST['search'] ) ) : '';
    $post_type = isset( $_POST['post_type'] ) ? sanitize_text_field( wp_unslash( $_POST['post_type'] ) ) : 'post';
    
    $args = array(
        'post_type'      => $post_type,
        'posts_per_page' => get_option( 'posts_per_page' ),
        'paged'          => $paged,
        'post_status'    => 'publish',
    );
    
    if ( $category ) {
        $args['cat'] = $category;
    }
    
    if ( $tag ) {
        $args['tag_id'] = $tag;
    }
    
    if ( $author ) {
        $args['author'] = $author;
    }
    
    if ( $search ) {
        $args['s'] = $search;
    }
    
    $posts = new WP_Query( $args );
    
    ob_start();
    
    if ( $posts->have_posts() ) {
        while ( $posts->have_posts() ) {
            $posts->the_post();
            get_template_part( 'template-parts/content', get_post_type() );
        }
    }
    
    wp_reset_postdata();
    
    $output = ob_get_clean();
    
    wp_send_json_success( array(
        'html'       => $output,
        'found'      => $posts->found_posts,
        'max_pages'  => $posts->max_num_pages,
        'next_page'  => $paged + 1,
    ) );
}
add_action( 'wp_ajax_aqualuxe_load_more_posts', 'aqualuxe_load_more_posts_ajax' );
add_action( 'wp_ajax_nopriv_aqualuxe_load_more_posts', 'aqualuxe_load_more_posts_ajax' );

/**
 * Dark mode toggle AJAX handler
 */
function aqualuxe_dark_mode_toggle_ajax() {
    // Check nonce
    if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'aqualuxe-nonce' ) ) {
        wp_send_json_error( 'Invalid nonce' );
    }

    $mode = isset( $_POST['mode'] ) ? sanitize_text_field( wp_unslash( $_POST['mode'] ) ) : 'light';
    
    // Set cookie for 30 days
    setcookie( 'aqualuxe_dark_mode', $mode, time() + ( 30 * DAY_IN_SECONDS ), COOKIEPATH, COOKIE_DOMAIN );
    
    wp_send_json_success( array(
        'mode' => $mode,
    ) );
}
add_action( 'wp_ajax_aqualuxe_dark_mode_toggle', 'aqualuxe_dark_mode_toggle_ajax' );
add_action( 'wp_ajax_nopriv_aqualuxe_dark_mode_toggle', 'aqualuxe_dark_mode_toggle_ajax' );

/**
 * Search autocomplete AJAX handler
 */
function aqualuxe_search_autocomplete_ajax() {
    // Check nonce
    if ( ! isset( $_GET['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['nonce'] ) ), 'aqualuxe-nonce' ) ) {
        wp_send_json_error( 'Invalid nonce' );
    }

    $search = isset( $_GET['term'] ) ? sanitize_text_field( wp_unslash( $_GET['term'] ) ) : '';
    
    if ( empty( $search ) ) {
        wp_send_json_error( 'Empty search term' );
    }
    
    $results = array();
    
    // Search posts
    $posts_args = array(
        'post_type'      => 'post',
        'posts_per_page' => 5,
        's'              => $search,
        'post_status'    => 'publish',
    );
    
    $posts = new WP_Query( $posts_args );
    
    if ( $posts->have_posts() ) {
        while ( $posts->have_posts() ) {
            $posts->the_post();
            $results[] = array(
                'id'    => get_the_ID(),
                'title' => get_the_title(),
                'url'   => get_permalink(),
                'type'  => 'post',
                'image' => has_post_thumbnail() ? get_the_post_thumbnail_url( get_the_ID(), 'thumbnail' ) : '',
            );
        }
    }
    
    wp_reset_postdata();
    
    // Search products if WooCommerce is active
    if ( class_exists( 'WooCommerce' ) ) {
        $products_args = array(
            'post_type'      => 'product',
            'posts_per_page' => 5,
            's'              => $search,
            'post_status'    => 'publish',
        );
        
        $products = new WP_Query( $products_args );
        
        if ( $products->have_posts() ) {
            while ( $products->have_posts() ) {
                $products->the_post();
                $product = wc_get_product( get_the_ID() );
                
                $results[] = array(
                    'id'    => get_the_ID(),
                    'title' => get_the_title(),
                    'url'   => get_permalink(),
                    'type'  => 'product',
                    'image' => has_post_thumbnail() ? get_the_post_thumbnail_url( get_the_ID(), 'thumbnail' ) : '',
                    'price' => $product->get_price_html(),
                );
            }
        }
        
        wp_reset_postdata();
    }
    
    // Search pages
    $pages_args = array(
        'post_type'      => 'page',
        'posts_per_page' => 3,
        's'              => $search,
        'post_status'    => 'publish',
    );
    
    $pages = new WP_Query( $pages_args );
    
    if ( $pages->have_posts() ) {
        while ( $pages->have_posts() ) {
            $pages->the_post();
            $results[] = array(
                'id'    => get_the_ID(),
                'title' => get_the_title(),
                'url'   => get_permalink(),
                'type'  => 'page',
                'image' => has_post_thumbnail() ? get_the_post_thumbnail_url( get_the_ID(), 'thumbnail' ) : '',
            );
        }
    }
    
    wp_reset_postdata();
    
    // Add view all search results link
    $results[] = array(
        'id'    => 0,
        'title' => sprintf( __( 'View all results for "%s"', 'aqualuxe' ), $search ),
        'url'   => add_query_arg( 's', $search, home_url( '/' ) ),
        'type'  => 'search',
        'image' => '',
    );
    
    wp_send_json_success( $results );
}
add_action( 'wp_ajax_aqualuxe_search_autocomplete', 'aqualuxe_search_autocomplete_ajax' );
add_action( 'wp_ajax_nopriv_aqualuxe_search_autocomplete', 'aqualuxe_search_autocomplete_ajax' );