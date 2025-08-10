<?php
/**
 * WooCommerce template hooks for AquaLuxe theme
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Layout
 *
 * @see aqualuxe_woocommerce_before_content()
 * @see aqualuxe_woocommerce_after_content()
 * @see aqualuxe_woocommerce_breadcrumb()
 */
remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);
add_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 10);

/**
 * Products Loop
 *
 * @see aqualuxe_woocommerce_product_columns_wrapper()
 * @see aqualuxe_woocommerce_product_columns_wrapper_close()
 */
remove_action('woocommerce_before_shop_loop', 'woocommerce_result_count', 20);
remove_action('woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30);
add_action('woocommerce_before_shop_loop', 'woocommerce_result_count', 10);
add_action('woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 20);

/**
 * Product
 *
 * @see aqualuxe_woocommerce_show_product_images()
 * @see aqualuxe_woocommerce_show_product_thumbnails()
 */
remove_action('woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10);
add_action('woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10);

/**
 * Product Summary Box
 *
 * @see aqualuxe_woocommerce_template_single_title()
 * @see aqualuxe_woocommerce_template_single_price()
 * @see aqualuxe_woocommerce_template_single_excerpt()
 * @see aqualuxe_woocommerce_template_single_meta()
 * @see aqualuxe_woocommerce_template_single_sharing()
 */
add_action('woocommerce_single_product_summary', 'aqualuxe_woocommerce_wishlist_button', 31);
add_action('woocommerce_single_product_summary', 'aqualuxe_woocommerce_product_share', 50);

/**
 * Product Tabs
 */
add_filter('woocommerce_product_tabs', function($tabs) {
    // Rename the description tab
    if (isset($tabs['description'])) {
        $tabs['description']['title'] = __('Product Details', 'aqualuxe');
    }
    
    // Rename the additional information tab
    if (isset($tabs['additional_information'])) {
        $tabs['additional_information']['title'] = __('Specifications', 'aqualuxe');
    }
    
    // Add care instructions tab
    $tabs['care_instructions'] = array(
        'title' => __('Care Instructions', 'aqualuxe'),
        'priority' => 30,
        'callback' => function() {
            global $product;
            
            // Get care instructions from product meta
            $care_instructions = get_post_meta($product->get_id(), '_care_instructions', true);
            
            if (empty($care_instructions)) {
                // Default care instructions
                echo '<h3>' . esc_html__('General Care Instructions', 'aqualuxe') . '</h3>';
                echo '<p>' . esc_html__('Proper care is essential for the health and longevity of your aquatic pets. Here are some general guidelines:', 'aqualuxe') . '</p>';
                echo '<ul class="list-disc pl-6 space-y-2 mt-4">';
                echo '<li>' . esc_html__('Maintain consistent water temperature appropriate for your species.', 'aqualuxe') . '</li>';
                echo '<li>' . esc_html__('Regularly test water parameters including pH, ammonia, nitrite, and nitrate levels.', 'aqualuxe') . '</li>';
                echo '<li>' . esc_html__('Perform partial water changes of 20-30% weekly or bi-weekly.', 'aqualuxe') . '</li>';
                echo '<li>' . esc_html__('Feed appropriate food in small amounts 1-2 times daily.', 'aqualuxe') . '</li>';
                echo '<li>' . esc_html__('Maintain proper filtration and aeration systems.', 'aqualuxe') . '</li>';
                echo '<li>' . esc_html__('Provide appropriate lighting cycles (8-12 hours daily).', 'aqualuxe') . '</li>';
                echo '<li>' . esc_html__('Monitor fish behavior for signs of stress or illness.', 'aqualuxe') . '</li>';
                echo '</ul>';
                echo '<p class="mt-4">' . esc_html__('For species-specific care instructions, please contact our customer support team.', 'aqualuxe') . '</p>';
            } else {
                echo wp_kses_post($care_instructions);
            }
        }
    );
    
    return $tabs;
}, 98);

/**
 * After Single Product Summary
 *
 * @see aqualuxe_woocommerce_output_product_data_tabs()
 * @see aqualuxe_woocommerce_upsell_display()
 * @see aqualuxe_woocommerce_output_related_products()
 */
remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);
add_action('woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);
add_action('woocommerce_after_single_product_summary', 'aqualuxe_woocommerce_product_specifications', 15);
add_action('woocommerce_after_single_product_summary', 'aqualuxe_woocommerce_product_guarantee', 25);

/**
 * Cart Page
 *
 * @see aqualuxe_woocommerce_cross_sell_display()
 */
remove_action('woocommerce_cart_collaterals', 'woocommerce_cross_sell_display');
add_action('woocommerce_after_cart', 'woocommerce_cross_sell_display');

/**
 * Checkout Page
 */
add_action('woocommerce_before_checkout_form', function() {
    echo '<div class="checkout-intro bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-8 transition-colors duration-300">';
    echo '<h2 class="text-2xl font-bold mb-4">' . esc_html__('Secure Checkout', 'aqualuxe') . '</h2>';
    echo '<p class="mb-4">' . esc_html__('Complete your purchase by providing your shipping and payment details below. All transactions are secure and encrypted.', 'aqualuxe') . '</p>';
    
    echo '<div class="flex flex-wrap items-center mt-4">';
    echo '<div class="mr-6 mb-4">';
    echo '<svg class="h-6 w-6 text-green-600 dark:text-green-400 inline-block mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">';
    echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />';
    echo '</svg>';
    echo '<span>' . esc_html__('Secure Payments', 'aqualuxe') . '</span>';
    echo '</div>';
    
    echo '<div class="mr-6 mb-4">';
    echo '<svg class="h-6 w-6 text-green-600 dark:text-green-400 inline-block mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">';
    echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />';
    echo '</svg>';
    echo '<span>' . esc_html__('Fast Delivery', 'aqualuxe') . '</span>';
    echo '</div>';
    
    echo '<div class="mr-6 mb-4">';
    echo '<svg class="h-6 w-6 text-green-600 dark:text-green-400 inline-block mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">';
    echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />';
    echo '</svg>';
    echo '<span>' . esc_html__('Multiple Payment Options', 'aqualuxe') . '</span>';
    echo '</div>';
    
    echo '<div class="mb-4">';
    echo '<svg class="h-6 w-6 text-green-600 dark:text-green-400 inline-block mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">';
    echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 15v-1a4 4 0 00-4-4H8m0 0l3 3m-3-3l3-3m9 14V5a2 2 0 00-2-2H6a2 2 0 00-2 2v16l4-2 4 2 4-2 4 2z" />';
    echo '</svg>';
    echo '<span>' . esc_html__('Satisfaction Guaranteed', 'aqualuxe') . '</span>';
    echo '</div>';
    echo '</div>';
    
    echo '</div>';
}, 5);

/**
 * Footer
 *
 * @see aqualuxe_woocommerce_footer_widgets()
 * @see aqualuxe_woocommerce_credit()
 */
add_action('woocommerce_after_footer', function() {
    echo '<div class="payment-methods text-center py-4 border-t border-gray-200 dark:border-gray-700">';
    echo '<p class="text-sm text-gray-600 dark:text-gray-400 mb-2">' . esc_html__('Secure Payment Methods', 'aqualuxe') . '</p>';
    echo '<div class="flex justify-center space-x-4">';
    echo '<img src="' . esc_url(get_template_directory_uri() . '/assets/images/visa.svg') . '" alt="Visa" class="h-8">';
    echo '<img src="' . esc_url(get_template_directory_uri() . '/assets/images/mastercard.svg') . '" alt="Mastercard" class="h-8">';
    echo '<img src="' . esc_url(get_template_directory_uri() . '/assets/images/amex.svg') . '" alt="American Express" class="h-8">';
    echo '<img src="' . esc_url(get_template_directory_uri() . '/assets/images/paypal.svg') . '" alt="PayPal" class="h-8">';
    echo '</div>';
    echo '</div>';
}, 10);

/**
 * Auth
 *
 * @see aqualuxe_woocommerce_login_form()
 * @see aqualuxe_woocommerce_registration_form()
 */
add_action('woocommerce_before_customer_login_form', function() {
    echo '<div class="auth-intro text-center mb-8">';
    echo '<h2 class="text-3xl font-bold mb-4">' . esc_html__('Welcome to AquaLuxe', 'aqualuxe') . '</h2>';
    echo '<p class="max-w-2xl mx-auto">' . esc_html__('Sign in to your account to track orders, manage your wishlist, and enjoy a personalized shopping experience. New to AquaLuxe? Create an account to get started.', 'aqualuxe') . '</p>';
    echo '</div>';
}, 5);

/**
 * My Account
 *
 * @see aqualuxe_woocommerce_account_navigation()
 * @see aqualuxe_woocommerce_account_content()
 */
add_action('woocommerce_before_account_navigation', function() {
    echo '<div class="account-welcome bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-8 transition-colors duration-300">';
    echo '<div class="flex items-center">';
    echo '<div class="mr-4">';
    echo '<div class="w-16 h-16 rounded-full bg-primary text-white flex items-center justify-center text-2xl font-bold">';
    echo esc_html(substr(wp_get_current_user()->display_name, 0, 1));
    echo '</div>';
    echo '</div>';
    echo '<div>';
    echo '<h2 class="text-2xl font-bold">' . esc_html__('Welcome back', 'aqualuxe') . ', ' . esc_html(wp_get_current_user()->display_name) . '</h2>';
    echo '<p class="text-gray-600 dark:text-gray-400">' . esc_html__('Manage your account, orders, and wishlist from this dashboard.', 'aqualuxe') . '</p>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
}, 5);

/**
 * Quick View
 */
add_action('wp_footer', 'aqualuxe_woocommerce_quick_view_modal');

/**
 * Add to wishlist button
 */
add_action('woocommerce_after_add_to_cart_button', 'aqualuxe_woocommerce_wishlist_button', 10);

/**
 * Currency selector
 */
add_action('wp_footer', function() {
    echo '<script>
    (function($) {
        $(document).ready(function() {
            // Toggle currency dropdown
            $(".currency-selector button").on("click", function() {
                $(".currency-dropdown").toggleClass("hidden");
            });
            
            // Close dropdown when clicking outside
            $(document).on("click", function(e) {
                if (!$(e.target).closest(".currency-selector").length) {
                    $(".currency-dropdown").addClass("hidden");
                }
            });
            
            // Currency selection
            $(".currency-dropdown a").on("click", function(e) {
                e.preventDefault();
                var currency = $(this).data("currency");
                
                // Here you would normally send an AJAX request to change the currency
                // For demo purposes, we\'ll just reload the page
                alert("Currency changed to " + currency + " (Demo only)");
            });
        });
    })(jQuery);
    </script>';
});

/**
 * Product tabs interaction
 */
add_action('wp_footer', function() {
    echo '<script>
    (function($) {
        $(document).ready(function() {
            // Category tabs
            $(".category-tab-button").on("click", function() {
                var category = $(this).data("category");
                
                // Update active tab
                $(".category-tab-button").removeClass("border-primary text-primary dark:border-primary-dark dark:text-primary-dark").addClass("border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300 dark:hover:border-gray-600");
                $(this).removeClass("border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300 dark:hover:border-gray-600").addClass("border-primary text-primary dark:border-primary-dark dark:text-primary-dark");
                
                // Show selected content
                $(".category-tab-content").addClass("hidden");
                $(".category-tab-content[data-category=\'" + category + "\']").removeClass("hidden");
            });
            
            // Quick view
            $(".quick-view-button").on("click", function(e) {
                e.preventDefault();
                var productId = $(this).data("product-id");
                
                // Here you would normally send an AJAX request to get product data
                // For demo purposes, we\'ll just show the modal with placeholder content
                $("#quick-view-modal").removeClass("hidden");
                $(".product-title").text("Product #" + productId);
                $(".product-image").html("<img src=\'https://via.placeholder.com/600x600\' alt=\'Product Image\' class=\'w-full rounded-lg\'>");
                $(".product-price").html("<span class=\'text-2xl font-bold\'>$99.99</span>");
                $(".product-excerpt").html("<p>This is a sample product description. In a real implementation, this would be loaded via AJAX.</p>");
                $(".product-add-to-cart").html("<button class=\'w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors duration-300 mb-4\'><svg xmlns=\'http://www.w3.org/2000/svg\' class=\'h-5 w-5 mr-2\' fill=\'none\' viewBox=\'0 0 24 24\' stroke=\'currentColor\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z\' /></svg>Add to Cart</button>");
                $(".view-full-details").attr("href", "?product=" + productId);
            });
            
            // Close quick view modal
            $("#quick-view-close").on("click", function() {
                $("#quick-view-modal").addClass("hidden");
            });
            
            // Close quick view when clicking outside
            $("#quick-view-modal").on("click", function(e) {
                if ($(e.target).is($("#quick-view-modal"))) {
                    $("#quick-view-modal").addClass("hidden");
                }
            });
            
            // Wishlist toggle
            $(".wishlist-toggle").on("click", function(e) {
                e.preventDefault();
                var productId = $(this).data("product-id");
                
                // Here you would normally send an AJAX request to toggle wishlist status
                // For demo purposes, we\'ll just toggle a class
                $(this).find(".wishlist-icon").toggleClass("text-red-500 fill-current");
                
                // Show feedback
                var message = $(this).find(".wishlist-icon").hasClass("text-red-500") ? "Product added to wishlist" : "Product removed from wishlist";
                alert(message + " (Demo only)");
            });
        });
    })(jQuery);
    </script>';
});