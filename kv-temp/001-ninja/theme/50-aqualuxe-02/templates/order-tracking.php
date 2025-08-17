<?php
/**
 * Template Name: Order Tracking
 *
 * The template for displaying the order tracking page.
 *
 * @package AquaLuxe
 * @version 1.0.0
 */

get_header();

// Get the page content
$page_id = get_the_ID();
$page_content = get_post_field('post_content', $page_id);
?>

<div class="container">
    <div class="order-tracking-page">
        <header class="page-header">
            <h1 class="page-title"><?php the_title(); ?></h1>
            <?php if (!empty($page_content)) : ?>
                <div class="page-description">
                    <?php echo apply_filters('the_content', $page_content); ?>
                </div>
            <?php endif; ?>
        </header>

        <div class="order-tracking-form-container">
            <form class="order-tracking-form" method="post">
                <?php wp_nonce_field('aqualuxe-order-tracking', 'tracking-nonce'); ?>
                
                <p class="form-row">
                    <label for="order_id"><?php esc_html_e('Order ID', 'aqualuxe'); ?> <span class="required">*</span></label>
                    <input type="text" name="order_id" id="order_id" placeholder="<?php esc_attr_e('Found in your order confirmation email', 'aqualuxe'); ?>" required>
                </p>
                
                <p class="form-row">
                    <label for="order_email"><?php esc_html_e('Billing Email', 'aqualuxe'); ?> <span class="required">*</span></label>
                    <input type="email" name="order_email" id="order_email" placeholder="<?php esc_attr_e('Email you used during checkout', 'aqualuxe'); ?>" required>
                </p>
                
                <p class="form-actions">
                    <button type="submit" class="button" name="track_order" value="1"><?php esc_html_e('Track Order', 'aqualuxe'); ?></button>
                </p>
            </form>
        </div>
        
        <?php
        // Process the form if submitted
        if (isset($_POST['track_order']) && wp_verify_nonce($_POST['tracking-nonce'], 'aqualuxe-order-tracking')) {
            $order_id = isset($_POST['order_id']) ? wc_clean($_POST['order_id']) : '';
            $order_email = isset($_POST['order_email']) ? sanitize_email($_POST['order_email']) : '';
            
            // Try to load the order
            $order_id = wc_get_order_id_by_order_key($order_id);
            
            if (!$order_id) {
                // Try to find by order number
                $order_id = absint($_POST['order_id']);
            }
            
            $order = wc_get_order($order_id);
            
            // Check if order exists and email matches
            if ($order && $order->get_billing_email() === $order_email) {
                // Get tracking information
                $tracking_number = get_post_meta($order_id, '_tracking_number', true);
                $tracking_provider = get_post_meta($order_id, '_tracking_provider', true);
                $shipped_date = get_post_meta($order_id, '_shipped_date', true);
                
                // Display order details
                ?>
                <div class="order-tracking-results">
                    <div class="order-details-heading">
                        <h2><?php esc_html_e('Order #', 'aqualuxe'); ?><?php echo esc_html($order->get_order_number()); ?></h2>
                        <span class="order-status status-<?php echo esc_attr($order->get_status()); ?>">
                            <?php echo esc_html(wc_get_order_status_name($order->get_status())); ?>
                        </span>
                    </div>
                    
                    <div class="order-overview">
                        <div class="order-info-item">
                            <h4><?php esc_html_e('Order date', 'aqualuxe'); ?></h4>
                            <p><?php echo esc_html(wc_format_datetime($order->get_date_created())); ?></p>
                        </div>
                        <div class="order-info-item">
                            <h4><?php esc_html_e('Total', 'aqualuxe'); ?></h4>
                            <p><?php echo wp_kses_post($order->get_formatted_order_total()); ?></p>
                        </div>
                        <div class="order-info-item">
                            <h4><?php esc_html_e('Payment method', 'aqualuxe'); ?></h4>
                            <p><?php echo wp_kses_post($order->get_payment_method_title()); ?></p>
                        </div>
                        <?php if ($tracking_number && $tracking_provider) : ?>
                        <div class="order-info-item">
                            <h4><?php esc_html_e('Tracking', 'aqualuxe'); ?></h4>
                            <p>
                                <a href="<?php echo esc_url(aqualuxe_get_tracking_url($tracking_provider, $tracking_number)); ?>" target="_blank">
                                    <?php echo esc_html($tracking_number); ?>
                                </a>
                                (<?php echo esc_html(ucwords($tracking_provider)); ?>)
                            </p>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <?php if ($order->get_status() === 'processing' || $order->get_status() === 'on-hold' || $order->get_status() === 'completed') : ?>
                    <div class="order-tracking">
                        <div class="tracking-header">
                            <h3><?php esc_html_e('Order tracking', 'aqualuxe'); ?></h3>
                        </div>
                        
                        <div class="tracking-timeline">
                            <div class="tracking-step completed">
                                <div class="step-content">
                                    <div class="step-header">
                                        <h4><?php esc_html_e('Order placed', 'aqualuxe'); ?></h4>
                                        <span class="step-date"><?php echo esc_html(wc_format_datetime($order->get_date_created())); ?></span>
                                    </div>
                                    <p class="step-description"><?php esc_html_e('Your order has been received and is being processed.', 'aqualuxe'); ?></p>
                                </div>
                            </div>
                            
                            <?php if ($order->get_status() === 'processing' || $order->get_status() === 'completed') : ?>
                            <div class="tracking-step <?php echo $order->get_status() === 'processing' ? 'current' : 'completed'; ?>">
                                <div class="step-content">
                                    <div class="step-header">
                                        <h4><?php esc_html_e('Processing', 'aqualuxe'); ?></h4>
                                        <span class="step-date">
                                            <?php 
                                            $processing_date = $order->get_date_modified();
                                            echo esc_html(wc_format_datetime($processing_date)); 
                                            ?>
                                        </span>
                                    </div>
                                    <p class="step-description"><?php esc_html_e('Your order is being prepared for shipping.', 'aqualuxe'); ?></p>
                                </div>
                            </div>
                            <?php endif; ?>
                            
                            <?php if ($tracking_number && $tracking_provider && $shipped_date) : ?>
                            <div class="tracking-step <?php echo $order->get_status() === 'completed' ? 'completed' : 'current'; ?>">
                                <div class="step-content">
                                    <div class="step-header">
                                        <h4><?php esc_html_e('Shipped', 'aqualuxe'); ?></h4>
                                        <span class="step-date"><?php echo esc_html(date_i18n(get_option('date_format'), strtotime($shipped_date))); ?></span>
                                    </div>
                                    <p class="step-description">
                                        <?php esc_html_e('Your order has been shipped.', 'aqualuxe'); ?>
                                        <?php if ($tracking_number && $tracking_provider) : ?>
                                            <br>
                                            <?php esc_html_e('Tracking number:', 'aqualuxe'); ?> 
                                            <a href="<?php echo esc_url(aqualuxe_get_tracking_url($tracking_provider, $tracking_number)); ?>" target="_blank">
                                                <?php echo esc_html($tracking_number); ?>
                                            </a>
                                            (<?php echo esc_html(ucwords($tracking_provider)); ?>)
                                        <?php endif; ?>
                                    </p>
                                </div>
                            </div>
                            <?php endif; ?>
                            
                            <?php if ($order->get_status() === 'completed') : ?>
                            <div class="tracking-step completed">
                                <div class="step-content">
                                    <div class="step-header">
                                        <h4><?php esc_html_e('Delivered', 'aqualuxe'); ?></h4>
                                        <span class="step-date">
                                            <?php 
                                            $completed_date = $order->get_date_completed();
                                            echo esc_html(wc_format_datetime($completed_date)); 
                                            ?>
                                        </span>
                                    </div>
                                    <p class="step-description"><?php esc_html_e('Your order has been delivered. Thank you for shopping with us!', 'aqualuxe'); ?></p>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <div class="order-items">
                        <h3><?php esc_html_e('Order items', 'aqualuxe'); ?></h3>
                        <table class="order-items-table">
                            <thead>
                                <tr>
                                    <th><?php esc_html_e('Product', 'aqualuxe'); ?></th>
                                    <th><?php esc_html_e('Quantity', 'aqualuxe'); ?></th>
                                    <th><?php esc_html_e('Price', 'aqualuxe'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($order->get_items() as $item_id => $item) {
                                    $product = $item->get_product();
                                    $product_name = $item->get_name();
                                    $quantity = $item->get_quantity();
                                    $subtotal = $order->get_formatted_line_subtotal($item);
                                    
                                    echo '<tr>';
                                    echo '<td class="product-name">';
                                    if ($product && $product->get_image_id()) {
                                        echo '<div class="product-image">' . wp_get_attachment_image($product->get_image_id(), 'thumbnail') . '</div>';
                                    }
                                    echo '<div class="product-details">';
                                    echo '<span class="product-title">' . esc_html($product_name) . '</span>';
                                    
                                    // Display variation data
                                    if ($item->get_variation_id()) {
                                        echo '<div class="product-variation">';
                                        foreach ($item->get_formatted_meta_data() as $meta) {
                                            echo '<span class="variation-item">' . wp_kses_post($meta->display_key) . ': ' . wp_kses_post($meta->display_value) . '</span>';
                                        }
                                        echo '</div>';
                                    }
                                    
                                    echo '</div>';
                                    echo '</td>';
                                    echo '<td class="product-quantity">' . esc_html($quantity) . '</td>';
                                    echo '<td class="product-total">' . wp_kses_post($subtotal) . '</td>';
                                    echo '</tr>';
                                }
                                ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="2"><?php esc_html_e('Subtotal', 'aqualuxe'); ?></th>
                                    <td><?php echo wp_kses_post($order->get_subtotal_to_display()); ?></td>
                                </tr>
                                <?php if ($order->get_shipping_total() > 0) : ?>
                                <tr>
                                    <th colspan="2"><?php esc_html_e('Shipping', 'aqualuxe'); ?></th>
                                    <td><?php echo wp_kses_post($order->get_shipping_to_display()); ?></td>
                                </tr>
                                <?php endif; ?>
                                <?php if ($order->get_total_tax() > 0) : ?>
                                <tr>
                                    <th colspan="2"><?php esc_html_e('Tax', 'aqualuxe'); ?></th>
                                    <td><?php echo wp_kses_post(wc_price($order->get_total_tax())); ?></td>
                                </tr>
                                <?php endif; ?>
                                <tr>
                                    <th colspan="2"><?php esc_html_e('Total', 'aqualuxe'); ?></th>
                                    <td><?php echo wp_kses_post($order->get_formatted_order_total()); ?></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    
                    <div class="order-actions">
                        <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="button button-secondary"><?php esc_html_e('Continue Shopping', 'aqualuxe'); ?></a>
                        <?php if (is_user_logged_in() && $order->get_user_id() === get_current_user_id()) : ?>
                            <a href="<?php echo esc_url(wc_get_account_endpoint_url('orders')); ?>" class="button"><?php esc_html_e('View All Orders', 'aqualuxe'); ?></a>
                        <?php endif; ?>
                    </div>
                </div>
                <?php
            } else {
                // Order not found or email doesn't match
                ?>
                <div class="woocommerce-message woocommerce-message--error">
                    <p><?php esc_html_e('Sorry, we couldn\'t find an order matching the information you provided. Please check your order ID and email address and try again.', 'aqualuxe'); ?></p>
                </div>
                <?php
            }
        }
        ?>
    </div>
</div>

<?php
get_footer();