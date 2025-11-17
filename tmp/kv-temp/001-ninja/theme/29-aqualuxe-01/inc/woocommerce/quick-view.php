<?php
/**
 * WooCommerce Quick View functionality
 *
 * @package AquaLuxe
 */

/**
 * Quick View setup
 */
function aqualuxe_quick_view_setup() {
    // Check if quick view is enabled
    if (!get_theme_mod('aqualuxe_enable_quick_view', true)) {
        return;
    }

    // Add quick view button to product loop
    add_action('woocommerce_after_shop_loop_item', 'aqualuxe_quick_view_button', 15);

    // Add quick view modal to footer
    add_action('wp_footer', 'aqualuxe_quick_view_modal');

    // Add AJAX handler for quick view
    add_action('wp_ajax_aqualuxe_quick_view', 'aqualuxe_ajax_quick_view');
    add_action('wp_ajax_nopriv_aqualuxe_quick_view', 'aqualuxe_ajax_quick_view');

    // Enqueue quick view scripts
    add_action('wp_enqueue_scripts', 'aqualuxe_quick_view_scripts');
}
add_action('init', 'aqualuxe_quick_view_setup');

/**
 * Quick View button
 */
function aqualuxe_quick_view_button() {
    global $product;
    
    echo '<div class="aqualuxe-quick-view-button-wrapper">';
    echo '<button class="aqualuxe-quick-view-button" data-product-id="' . esc_attr($product->get_id()) . '">';
    echo '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>';
    echo '<span>' . esc_html__('Quick View', 'aqualuxe') . '</span>';
    echo '</button>';
    echo '</div>';
}

/**
 * Quick View modal
 */
function aqualuxe_quick_view_modal() {
    ?>
    <div id="aqualuxe-quick-view-modal" class="aqualuxe-quick-view-modal">
        <div class="aqualuxe-quick-view-modal-content">
            <div class="aqualuxe-quick-view-modal-header">
                <button class="aqualuxe-quick-view-modal-close">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
            <div class="aqualuxe-quick-view-modal-body">
                <div class="aqualuxe-quick-view-loading">
                    <div class="aqualuxe-quick-view-loading-spinner"></div>
                </div>
                <div class="aqualuxe-quick-view-content"></div>
            </div>
        </div>
    </div>
    <?php
}

/**
 * AJAX Quick View
 */
function aqualuxe_ajax_quick_view() {
    // Check nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'aqualuxe-quick-view-nonce')) {
        wp_send_json_error(array('message' => __('Invalid nonce', 'aqualuxe')));
    }

    // Check product ID
    if (!isset($_POST['product_id']) || empty($_POST['product_id'])) {
        wp_send_json_error(array('message' => __('Invalid product ID', 'aqualuxe')));
    }

    // Get product ID
    $product_id = absint($_POST['product_id']);

    // Get product
    $product = wc_get_product($product_id);

    if (!$product) {
        wp_send_json_error(array('message' => __('Product not found', 'aqualuxe')));
    }

    // Start output buffer
    ob_start();

    // Include quick view template
    include(get_template_directory() . '/woocommerce/quick-view.php');

    // Get output buffer
    $output = ob_get_clean();

    // Send response
    wp_send_json_success(array(
        'html' => $output,
    ));
}

/**
 * Quick View scripts
 */
function aqualuxe_quick_view_scripts() {
    // Check if quick view is enabled
    if (!get_theme_mod('aqualuxe_enable_quick_view', true)) {
        return;
    }

    // Enqueue quick view script
    wp_enqueue_script('aqualuxe-quick-view', get_template_directory_uri() . '/assets/js/quick-view.js', array('jquery'), AQUALUXE_VERSION, true);

    // Localize quick view script
    wp_localize_script('aqualuxe-quick-view', 'aqualuxeQuickView', array(
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('aqualuxe-quick-view-nonce'),
        'i18n' => array(
            'loading' => __('Loading...', 'aqualuxe'),
            'error' => __('Error loading product', 'aqualuxe'),
            'addToCart' => __('Add to cart', 'aqualuxe'),
            'viewProduct' => __('View product', 'aqualuxe'),
        ),
    ));

    // Enqueue quick view style
    wp_enqueue_style('aqualuxe-quick-view', get_template_directory_uri() . '/assets/css/quick-view.css', array(), AQUALUXE_VERSION);
}

/**
 * Create the quick view template
 */
function aqualuxe_create_quick_view_template() {
    // Check if quick view template exists
    if (file_exists(get_template_directory() . '/woocommerce/quick-view.php')) {
        return;
    }

    // Create quick view template
    $quick_view_template = '<?php
/**
 * Quick View template
 *
 * @package AquaLuxe
 */

defined(\'ABSPATH\') || exit;

global $product;

// Set the global product variable
$product = wc_get_product($product_id);

if (!$product) {
    return;
}
?>

<div class="aqualuxe-quick-view-product">
    <div class="aqualuxe-quick-view-product-images">
        <?php
        // Product gallery
        $attachment_ids = $product->get_gallery_image_ids();
        $post_thumbnail_id = $product->get_image_id();

        if ($post_thumbnail_id) {
            $html = wc_get_gallery_image_html($post_thumbnail_id, true);
            echo apply_filters(\'woocommerce_single_product_image_thumbnail_html\', $html, $post_thumbnail_id);
        }

        if ($attachment_ids && $product->get_image_id()) {
            foreach ($attachment_ids as $attachment_id) {
                $html = wc_get_gallery_image_html($attachment_id, true);
                echo apply_filters(\'woocommerce_single_product_image_thumbnail_html\', $html, $attachment_id);
            }
        }
        ?>
    </div>
    <div class="aqualuxe-quick-view-product-summary">
        <h2 class="aqualuxe-quick-view-product-title"><?php echo esc_html($product->get_name()); ?></h2>
        
        <div class="aqualuxe-quick-view-product-price">
            <?php echo $product->get_price_html(); ?>
        </div>
        
        <div class="aqualuxe-quick-view-product-rating">
            <?php
            if ($product->get_rating_count()) {
                woocommerce_template_loop_rating();
            } else {
                echo \'<div class="star-rating"></div><a href="#" class="woocommerce-review-link" rel="nofollow">\' . esc_html__( \'Reviews\', \'aqualuxe\' ) . \'</a>\';
            }
            ?>
        </div>
        
        <div class="aqualuxe-quick-view-product-excerpt">
            <?php echo apply_filters(\'woocommerce_short_description\', $product->get_short_description()); ?>
        </div>
        
        <div class="aqualuxe-quick-view-product-add-to-cart">
            <?php woocommerce_template_single_add_to_cart(); ?>
        </div>
        
        <div class="aqualuxe-quick-view-product-meta">
            <?php
            // SKU
            if ($product->get_sku()) {
                echo \'<span class="aqualuxe-quick-view-product-sku">\' . esc_html__( \'SKU:\', \'aqualuxe\' ) . \' \' . esc_html($product->get_sku()) . \'</span>\';
            }
            
            // Categories
            echo wc_get_product_category_list($product->get_id(), \', \', \'<span class="aqualuxe-quick-view-product-categories">\' . esc_html__( \'Category:\', \'aqualuxe\' ) . \' \', \'</span>\');
            
            // Tags
            echo wc_get_product_tag_list($product->get_id(), \', \', \'<span class="aqualuxe-quick-view-product-tags">\' . esc_html__( \'Tags:\', \'aqualuxe\' ) . \' \', \'</span>\');
            ?>
        </div>
        
        <div class="aqualuxe-quick-view-product-actions">
            <a href="<?php echo esc_url($product->get_permalink()); ?>" class="aqualuxe-quick-view-product-view-details button"><?php esc_html_e(\'View Details\', \'aqualuxe\'); ?></a>
            
            <?php
            // Wishlist button
            if (get_theme_mod(\'aqualuxe_enable_wishlist\', true)) {
                // Get wishlist
                $wishlist = isset($_COOKIE[\'aqualuxe_wishlist\']) ? json_decode(stripslashes($_COOKIE[\'aqualuxe_wishlist\']), true) : array();
                
                // Check if product is in wishlist
                $in_wishlist = in_array($product->get_id(), $wishlist);
                
                echo \'<button class="aqualuxe-quick-view-product-wishlist button \' . ($in_wishlist ? \'in-wishlist\' : \'\') . \'" data-product-id="\' . esc_attr($product->get_id()) . \'">\';
                echo \'<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="\' . ($in_wishlist ? \'currentColor\' : \'none\') . \'" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-heart"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>\';
                echo \'<span>\' . ($in_wishlist ? esc_html__(\'Remove from wishlist\', \'aqualuxe\') : esc_html__(\'Add to wishlist\', \'aqualuxe\')) . \'</span>\';
                echo \'</button>\';
            }
            ?>
        </div>
    </div>
</div>
';

    // Create woocommerce directory if it doesn't exist
    if (!file_exists(get_template_directory() . '/woocommerce')) {
        mkdir(get_template_directory() . '/woocommerce', 0755, true);
    }

    // Create quick view template file
    file_put_contents(get_template_directory() . '/woocommerce/quick-view.php', $quick_view_template);
}
add_action('after_setup_theme', 'aqualuxe_create_quick_view_template');

/**
 * Create quick view JavaScript file
 */
function aqualuxe_create_quick_view_js() {
    // Check if quick view JS exists
    if (file_exists(get_template_directory() . '/assets/js/quick-view.js')) {
        return;
    }

    // Create assets/js directory if it doesn't exist
    if (!file_exists(get_template_directory() . '/assets/js')) {
        mkdir(get_template_directory() . '/assets/js', 0755, true);
    }

    // Create quick view JS file
    $quick_view_js = '/**
 * Quick View JavaScript
 *
 * @package AquaLuxe
 */

(function($) {
    "use strict";

    // Quick View
    $(document).on("click", ".aqualuxe-quick-view-button", function(e) {
        e.preventDefault();

        var $button = $(this);
        var productId = $button.data("product-id");

        // Show modal
        $("#aqualuxe-quick-view-modal").addClass("open");
        $(".aqualuxe-quick-view-loading").show();
        $(".aqualuxe-quick-view-content").hide();

        // Get product data
        $.ajax({
            url: aqualuxeQuickView.ajaxUrl,
            type: "POST",
            data: {
                action: "aqualuxe_quick_view",
                nonce: aqualuxeQuickView.nonce,
                product_id: productId
            },
            success: function(response) {
                if (response.success) {
                    $(".aqualuxe-quick-view-content").html(response.data.html);
                    $(".aqualuxe-quick-view-loading").hide();
                    $(".aqualuxe-quick-view-content").show();

                    // Initialize quantity buttons
                    aqualuxeInitQuantityButtons();

                    // Initialize product gallery
                    aqualuxeInitProductGallery();

                    // Initialize variations form
                    aqualuxeInitVariationForm();
                } else {
                    $(".aqualuxe-quick-view-content").html("<p>" + aqualuxeQuickView.i18n.error + "</p>");
                    $(".aqualuxe-quick-view-loading").hide();
                    $(".aqualuxe-quick-view-content").show();
                }
            },
            error: function() {
                $(".aqualuxe-quick-view-content").html("<p>" + aqualuxeQuickView.i18n.error + "</p>");
                $(".aqualuxe-quick-view-loading").hide();
                $(".aqualuxe-quick-view-content").show();
            }
        });
    });

    // Close modal
    $(document).on("click", ".aqualuxe-quick-view-modal-close", function(e) {
        e.preventDefault();
        $("#aqualuxe-quick-view-modal").removeClass("open");
    });

    // Close modal on overlay click
    $(document).on("click", "#aqualuxe-quick-view-modal", function(e) {
        if ($(e.target).is("#aqualuxe-quick-view-modal")) {
            $("#aqualuxe-quick-view-modal").removeClass("open");
        }
    });

    // Close modal on ESC key
    $(document).on("keyup", function(e) {
        if (e.key === "Escape") {
            $("#aqualuxe-quick-view-modal").removeClass("open");
        }
    });

    // Initialize quantity buttons
    function aqualuxeInitQuantityButtons() {
        // Quantity buttons
        $(".aqualuxe-quick-view-product-add-to-cart").on("click", ".qty-button", function() {
            var $button = $(this);
            var $input = $button.parent().find(".qty");
            var oldValue = parseFloat($input.val());
            var newVal;

            if ($button.hasClass("plus")) {
                var max = parseFloat($input.attr("max"));
                if (max && max <= oldValue) {
                    newVal = oldValue;
                } else {
                    newVal = oldValue + 1;
                }
            } else {
                var min = parseFloat($input.attr("min"));
                if (min && min >= oldValue) {
                    newVal = oldValue;
                } else {
                    newVal = oldValue - 1;
                }
            }

            $input.val(newVal);
            $input.trigger("change");
        });
    }

    // Initialize product gallery
    function aqualuxeInitProductGallery() {
        // Product gallery
        $(".aqualuxe-quick-view-product-images").each(function() {
            var $gallery = $(this);
            var $images = $gallery.find("img");

            if ($images.length > 1) {
                // Create gallery navigation
                var $nav = $("<div class=\'aqualuxe-quick-view-product-images-nav\'></div>");
                var $prev = $("<button class=\'aqualuxe-quick-view-product-images-nav-prev\'><svg xmlns=\'http://www.w3.org/2000/svg\' width=\'24\' height=\'24\' viewBox=\'0 0 24 24\' fill=\'none\' stroke=\'currentColor\' stroke-width=\'2\' stroke-linecap=\'round\' stroke-linejoin=\'round\' class=\'feather feather-chevron-left\'><polyline points=\'15 18 9 12 15 6\'></polyline></svg></button>");
                var $next = $("<button class=\'aqualuxe-quick-view-product-images-nav-next\'><svg xmlns=\'http://www.w3.org/2000/svg\' width=\'24\' height=\'24\' viewBox=\'0 0 24 24\' fill=\'none\' stroke=\'currentColor\' stroke-width=\'2\' stroke-linecap=\'round\' stroke-linejoin=\'round\' class=\'feather feather-chevron-right\'><polyline points=\'9 18 15 12 9 6\'></polyline></svg></button>");

                $nav.append($prev);
                $nav.append($next);
                $gallery.append($nav);

                // Create gallery pagination
                var $pagination = $("<div class=\'aqualuxe-quick-view-product-images-pagination\'></div>");

                $images.each(function(index) {
                    var $dot = $("<button class=\'aqualuxe-quick-view-product-images-pagination-dot" + (index === 0 ? " active" : "") + "\' data-index=\'" + index + "\'></button>");
                    $pagination.append($dot);
                });

                $gallery.append($pagination);

                // Hide all images except the first one
                $images.hide();
                $images.first().show();

                // Navigation click
                $prev.on("click", function() {
                    var $current = $gallery.find("img:visible");
                    var $prev = $current.prev("img");

                    if (!$prev.length) {
                        $prev = $images.last();
                    }

                    $current.hide();
                    $prev.show();

                    // Update pagination
                    var index = $images.index($prev);
                    $pagination.find(".aqualuxe-quick-view-product-images-pagination-dot").removeClass("active");
                    $pagination.find(".aqualuxe-quick-view-product-images-pagination-dot[data-index=\'" + index + "\']").addClass("active");
                });

                $next.on("click", function() {
                    var $current = $gallery.find("img:visible");
                    var $next = $current.next("img");

                    if (!$next.length) {
                        $next = $images.first();
                    }

                    $current.hide();
                    $next.show();

                    // Update pagination
                    var index = $images.index($next);
                    $pagination.find(".aqualuxe-quick-view-product-images-pagination-dot").removeClass("active");
                    $pagination.find(".aqualuxe-quick-view-product-images-pagination-dot[data-index=\'" + index + "\']").addClass("active");
                });

                // Pagination click
                $pagination.on("click", ".aqualuxe-quick-view-product-images-pagination-dot", function() {
                    var index = $(this).data("index");

                    $images.hide();
                    $images.eq(index).show();

                    $pagination.find(".aqualuxe-quick-view-product-images-pagination-dot").removeClass("active");
                    $(this).addClass("active");
                });
            }
        });
    }

    // Initialize variations form
    function aqualuxeInitVariationForm() {
        // Variations form
        $(".aqualuxe-quick-view-product-add-to-cart .variations_form").each(function() {
            $(this).wc_variation_form();
        });
    }

    // Wishlist button
    $(document).on("click", ".aqualuxe-quick-view-product-wishlist", function(e) {
        e.preventDefault();

        var $button = $(this);
        var productId = $button.data("product-id");

        // Add or remove from wishlist
        $.ajax({
            url: aqualuxeQuickView.ajaxUrl,
            type: "POST",
            data: {
                action: "aqualuxe_add_to_wishlist",
                nonce: aqualuxeQuickView.nonce,
                product_id: productId
            },
            success: function(response) {
                if (response.success) {
                    if (response.data.status === "added") {
                        $button.addClass("in-wishlist");
                        $button.find("svg").attr("fill", "currentColor");
                        $button.find("span").text(aqualuxeQuickView.i18n.removeFromWishlist);
                    } else {
                        $button.removeClass("in-wishlist");
                        $button.find("svg").attr("fill", "none");
                        $button.find("span").text(aqualuxeQuickView.i18n.addToWishlist);
                    }
                }
            }
        });
    });
})(jQuery);
';

    // Create quick view JS file
    file_put_contents(get_template_directory() . '/assets/js/quick-view.js', $quick_view_js);
}
add_action('after_setup_theme', 'aqualuxe_create_quick_view_js');

/**
 * Create quick view CSS file
 */
function aqualuxe_create_quick_view_css() {
    // Check if quick view CSS exists
    if (file_exists(get_template_directory() . '/assets/css/quick-view.css')) {
        return;
    }

    // Create assets/css directory if it doesn't exist
    if (!file_exists(get_template_directory() . '/assets/css')) {
        mkdir(get_template_directory() . '/assets/css', 0755, true);
    }

    // Create quick view CSS file
    $quick_view_css = '/**
 * Quick View CSS
 *
 * @package AquaLuxe
 */

/* Quick View Button */
.aqualuxe-quick-view-button-wrapper {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    opacity: 0;
    transition: opacity 0.3s ease;
    z-index: 10;
}

.product:hover .aqualuxe-quick-view-button-wrapper {
    opacity: 1;
}

.aqualuxe-quick-view-button {
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: var(--primary-color);
    color: #fff;
    border: none;
    border-radius: 4px;
    padding: 8px 16px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.aqualuxe-quick-view-button:hover {
    background-color: var(--primary-color-dark);
}

.aqualuxe-quick-view-button svg {
    margin-right: 8px;
}

/* Quick View Modal */
.aqualuxe-quick-view-modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 9999;
    display: none;
    align-items: center;
    justify-content: center;
}

.aqualuxe-quick-view-modal.open {
    display: flex;
}

.aqualuxe-quick-view-modal-content {
    background-color: #fff;
    border-radius: 4px;
    max-width: 900px;
    width: 90%;
    max-height: 90vh;
    overflow: hidden;
    position: relative;
}

.aqualuxe-quick-view-modal-header {
    padding: 16px;
    border-bottom: 1px solid #eee;
    display: flex;
    align-items: center;
    justify-content: flex-end;
}

.aqualuxe-quick-view-modal-close {
    background-color: transparent;
    border: none;
    cursor: pointer;
    padding: 0;
    display: flex;
    align-items: center;
    justify-content: center;
}

.aqualuxe-quick-view-modal-body {
    padding: 24px;
    max-height: calc(90vh - 65px);
    overflow-y: auto;
}

/* Quick View Loading */
.aqualuxe-quick-view-loading {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 300px;
}

.aqualuxe-quick-view-loading-spinner {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    border: 3px solid rgba(0, 0, 0, 0.1);
    border-top-color: var(--primary-color);
    animation: spin 1s infinite linear;
}

@keyframes spin {
    0% {
        transform: rotate(0deg);
    }
    100% {
        transform: rotate(360deg);
    }
}

/* Quick View Product */
.aqualuxe-quick-view-product {
    display: flex;
    flex-wrap: wrap;
    margin: -16px;
}

.aqualuxe-quick-view-product-images {
    flex: 0 0 40%;
    padding: 16px;
    position: relative;
}

.aqualuxe-quick-view-product-summary {
    flex: 0 0 60%;
    padding: 16px;
}

.aqualuxe-quick-view-product-title {
    font-size: 24px;
    margin-bottom: 16px;
}

.aqualuxe-quick-view-product-price {
    font-size: 18px;
    margin-bottom: 16px;
}

.aqualuxe-quick-view-product-rating {
    margin-bottom: 16px;
}

.aqualuxe-quick-view-product-excerpt {
    margin-bottom: 24px;
}

.aqualuxe-quick-view-product-add-to-cart {
    margin-bottom: 24px;
}

.aqualuxe-quick-view-product-meta {
    margin-bottom: 24px;
}

.aqualuxe-quick-view-product-actions {
    display: flex;
    align-items: center;
}

.aqualuxe-quick-view-product-view-details {
    margin-right: 16px;
}

.aqualuxe-quick-view-product-wishlist {
    display: flex;
    align-items: center;
}

.aqualuxe-quick-view-product-wishlist svg {
    margin-right: 8px;
}

/* Quick View Product Images Navigation */
.aqualuxe-quick-view-product-images-nav {
    position: absolute;
    top: 50%;
    left: 0;
    right: 0;
    transform: translateY(-50%);
    display: flex;
    justify-content: space-between;
    padding: 0 16px;
    z-index: 1;
}

.aqualuxe-quick-view-product-images-nav-prev,
.aqualuxe-quick-view-product-images-nav-next {
    background-color: rgba(255, 255, 255, 0.8);
    border: none;
    border-radius: 50%;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.aqualuxe-quick-view-product-images-nav-prev:hover,
.aqualuxe-quick-view-product-images-nav-next:hover {
    background-color: rgba(255, 255, 255, 1);
}

/* Quick View Product Images Pagination */
.aqualuxe-quick-view-product-images-pagination {
    position: absolute;
    bottom: 16px;
    left: 0;
    right: 0;
    display: flex;
    justify-content: center;
    z-index: 1;
}

.aqualuxe-quick-view-product-images-pagination-dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background-color: rgba(255, 255, 255, 0.5);
    border: none;
    margin: 0 4px;
    padding: 0;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.aqualuxe-quick-view-product-images-pagination-dot.active {
    background-color: var(--primary-color);
}

/* Responsive */
@media (max-width: 768px) {
    .aqualuxe-quick-view-product {
        flex-direction: column;
    }

    .aqualuxe-quick-view-product-images,
    .aqualuxe-quick-view-product-summary {
        flex: 0 0 100%;
    }
}
';

    // Create quick view CSS file
    file_put_contents(get_template_directory() . '/assets/css/quick-view.css', $quick_view_css);
}
add_action('after_setup_theme', 'aqualuxe_create_quick_view_css');