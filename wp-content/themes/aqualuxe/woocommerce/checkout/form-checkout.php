<?php
/**
 * Checkout page template
 * 
 * @package AquaLuxe
 * @version 1.0.0
 */

defined('ABSPATH') || exit;

get_header('shop'); ?>

<div class="checkout-wrapper">
    <div class="container mx-auto px-4 py-8">
        
        <header class="checkout-header mb-8">
            <h1 class="text-3xl font-bold text-center"><?php esc_html_e('Checkout', 'aqualuxe'); ?></h1>
            
            <!-- Checkout Progress -->
            <div class="checkout-progress max-w-md mx-auto mt-6">
                <div class="flex items-center justify-between">
                    <div class="progress-step completed">
                        <div class="step-circle">1</div>
                        <span class="step-label"><?php esc_html_e('Cart', 'aqualuxe'); ?></span>
                    </div>
                    <div class="progress-line completed"></div>
                    <div class="progress-step active">
                        <div class="step-circle">2</div>
                        <span class="step-label"><?php esc_html_e('Checkout', 'aqualuxe'); ?></span>
                    </div>
                    <div class="progress-line"></div>
                    <div class="progress-step">
                        <div class="step-circle">3</div>
                        <span class="step-label"><?php esc_html_e('Complete', 'aqualuxe'); ?></span>
                    </div>
                </div>
            </div>
        </header>

        <?php
        // If checkout registration is disabled and not logged in, the user cannot checkout.
        if (!$checkout->is_registration_enabled() && $checkout->is_registration_required() && !is_user_logged_in()) {
            echo esc_html(apply_filters('woocommerce_checkout_must_be_logged_in_message', __('You must be logged in to checkout.', 'woocommerce')));
            return;
        }
        ?>

        <form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url(wc_get_checkout_url()); ?>" enctype="multipart/form-data">

            <div class="checkout-layout grid gap-8 lg:grid-cols-3">
                
                <!-- Checkout Fields -->
                <div class="checkout-fields lg:col-span-2">
                    
                    <?php if ($checkout->get_checkout_fields()) : ?>

                        <?php do_action('woocommerce_checkout_before_customer_details'); ?>

                        <!-- Customer Information -->
                        <div class="customer-details">
                            
                            <!-- Billing Details -->
                            <div class="billing-details mb-8">
                                <h3 class="text-xl font-semibold mb-6">
                                    <svg class="w-6 h-6 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    <?php esc_html_e('Billing Details', 'aqualuxe'); ?>
                                </h3>
                                
                                <div class="billing-fields">
                                    <?php
                                    $fields = $checkout->get_checkout_fields('billing');

                                    foreach ($fields as $key => $field) {
                                        woocommerce_form_field($key, $field, $checkout->get_value($key));
                                    }
                                    ?>
                                </div>
                                
                                <?php do_action('woocommerce_checkout_billing'); ?>
                            </div>

                            <!-- Shipping Details -->
                            <?php if (true === WC()->cart->needs_shipping_address()) : ?>
                                <div class="shipping-details mb-8">
                                    
                                    <h3 class="text-xl font-semibold mb-6">
                                        <svg class="w-6 h-6 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        <?php esc_html_e('Shipping Details', 'aqualuxe'); ?>
                                    </h3>
                                    
                                    <!-- Ship to different address toggle -->
                                    <div class="ship-to-different-address mb-4">
                                        <label class="checkbox-label flex items-center">
                                            <input 
                                                id="ship-to-different-address-checkbox" 
                                                class="ship-to-different-address-checkbox form-checkbox mr-3" 
                                                type="checkbox" 
                                                name="ship_to_different_address" 
                                                value="1" 
                                                <?php checked(apply_filters('woocommerce_ship_to_different_address_checked', 'shipping' === get_option('woocommerce_ship_to_destination') ? 1 : 0), 1); ?>
                                            />
                                            <span class="checkmark"></span>
                                            <span><?php esc_html_e('Ship to a different address?', 'woocommerce'); ?></span>
                                        </label>
                                    </div>

                                    <div class="shipping-address" <?php if (!apply_filters('woocommerce_ship_to_different_address_checked', 'shipping' === get_option('woocommerce_ship_to_destination') ? 1 : 0)) echo 'style="display: none;"'; ?>>
                                        <?php
                                        $fields = $checkout->get_checkout_fields('shipping');

                                        foreach ($fields as $key => $field) {
                                            woocommerce_form_field($key, $field, $checkout->get_value($key));
                                        }
                                        ?>
                                    </div>
                                    
                                    <?php do_action('woocommerce_checkout_shipping'); ?>
                                </div>
                            <?php endif; ?>

                        </div>

                        <?php do_action('woocommerce_checkout_after_customer_details'); ?>

                    <?php endif; ?>
                    
                    <!-- Additional Information -->
                    <?php if (apply_filters('woocommerce_enable_order_notes_field', 'yes' === get_option('woocommerce_enable_checkout_order_notes_field'))) : ?>
                        <div class="additional-fields mb-8">
                            <h3 class="text-xl font-semibold mb-6">
                                <svg class="w-6 h-6 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                <?php esc_html_e('Additional Information', 'aqualuxe'); ?>
                            </h3>
                            
                            <?php foreach ($checkout->get_checkout_fields('order') as $key => $field) : ?>
                                <?php woocommerce_form_field($key, $field, $checkout->get_value($key)); ?>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                    
                </div>
                
                <!-- Order Summary -->
                <div class="order-summary lg:col-span-1">
                    <div class="order-summary-wrapper bg-neutral-50 dark:bg-neutral-800 rounded-lg p-6 sticky top-4">
                        
                        <h3 class="text-lg font-semibold mb-6">
                            <svg class="w-6 h-6 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v11a2 2 0 002 2h5.586a1 1 0 00.707-.293l5.414-5.414a1 1 0 00.293-.707V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            <?php esc_html_e('Your Order', 'aqualuxe'); ?>
                        </h3>
                        
                        <?php do_action('woocommerce_checkout_before_order_review_heading'); ?>

                        <?php do_action('woocommerce_checkout_before_order_review'); ?>

                        <div id="order_review" class="woocommerce-checkout-review-order">
                            <?php do_action('woocommerce_checkout_order_review'); ?>
                        </div>

                        <?php do_action('woocommerce_checkout_after_order_review'); ?>
                        
                        <!-- Security Badges -->
                        <div class="security-badges mt-6 pt-6 border-t border-neutral-200 dark:border-neutral-700">
                            <div class="badges-grid space-y-3">
                                <div class="badge flex items-center text-sm">
                                    <svg class="w-4 h-4 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                    </svg>
                                    <span><?php esc_html_e('SSL Encrypted', 'aqualuxe'); ?></span>
                                </div>
                                <div class="badge flex items-center text-sm">
                                    <svg class="w-4 h-4 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                    </svg>
                                    <span><?php esc_html_e('Secure Payments', 'aqualuxe'); ?></span>
                                </div>
                                <div class="badge flex items-center text-sm">
                                    <svg class="w-4 h-4 text-purple-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                    </svg>
                                    <span><?php esc_html_e('Money Back Guarantee', 'aqualuxe'); ?></span>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
                
            </div>

        </form>

        <?php do_action('woocommerce_after_checkout_form', $checkout); ?>
        
    </div>
</div>

<style>
/* Checkout Progress Styles */
.checkout-progress {
    margin: 0 auto;
}

.checkout-progress .flex {
    position: relative;
}

.progress-step {
    display: flex;
    flex-direction: column;
    align-items: center;
    flex: 1;
    position: relative;
    z-index: 2;
}

.step-circle {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 14px;
    background: #e5e7eb;
    color: #6b7280;
    transition: all 0.3s ease;
}

.step-label {
    margin-top: 8px;
    font-size: 12px;
    font-weight: 500;
    color: #6b7280;
    text-align: center;
}

.progress-line {
    height: 2px;
    background: #e5e7eb;
    flex: 1;
    margin: 0 -1px;
    margin-top: 20px;
    position: relative;
    z-index: 1;
}

.progress-step.completed .step-circle {
    background: #10b981;
    color: white;
}

.progress-step.completed .step-label {
    color: #10b981;
}

.progress-step.active .step-circle {
    background: #3b82f6;
    color: white;
}

.progress-step.active .step-label {
    color: #3b82f6;
}

.progress-line.completed {
    background: #10b981;
}

/* Checkout Form Styles */
.checkout-fields .form-row {
    margin-bottom: 1.5rem;
}

.checkout-fields .form-row-first,
.checkout-fields .form-row-last {
    width: 100%;
}

@media (min-width: 768px) {
    .checkout-fields .form-row-first {
        margin-right: 1rem;
        width: calc(50% - 0.5rem);
        display: inline-block;
        vertical-align: top;
    }
    
    .checkout-fields .form-row-last {
        width: calc(50% - 0.5rem);
        display: inline-block;
        vertical-align: top;
    }
}

.checkout-fields label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 500;
    color: #374151;
}

.dark .checkout-fields label {
    color: #d1d5db;
}

.checkout-fields input[type="text"],
.checkout-fields input[type="email"],
.checkout-fields input[type="tel"],
.checkout-fields select,
.checkout-fields textarea {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #d1d5db;
    border-radius: 0.375rem;
    background: white;
    font-size: 14px;
    transition: border-color 0.2s ease, box-shadow 0.2s ease;
}

.dark .checkout-fields input[type="text"],
.dark .checkout-fields input[type="email"],
.dark .checkout-fields input[type="tel"],
.dark .checkout-fields select,
.dark .checkout-fields textarea {
    background: #374151;
    border-color: #4b5563;
    color: #f9fafb;
}

.checkout-fields input:focus,
.checkout-fields select:focus,
.checkout-fields textarea:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.checkout-fields .required label::after {
    content: " *";
    color: #ef4444;
}

/* Checkbox Styles */
.checkbox-label {
    cursor: pointer;
    user-select: none;
}

.form-checkbox {
    width: 1rem;
    height: 1rem;
    color: #3b82f6;
    border-radius: 0.25rem;
}

/* Error Styles */
.woocommerce-error,
.woocommerce-message,
.woocommerce-info {
    padding: 1rem;
    margin-bottom: 1.5rem;
    border-radius: 0.375rem;
    border-left: 4px solid;
}

.woocommerce-error {
    background: #fef2f2;
    border-color: #ef4444;
    color: #991b1b;
}

.woocommerce-message {
    background: #f0fdf4;
    border-color: #10b981;
    color: #064e3b;
}

.woocommerce-info {
    background: #eff6ff;
    border-color: #3b82f6;
    color: #1e3a8a;
}

.dark .woocommerce-error {
    background: #450a0a;
    color: #fca5a5;
}

.dark .woocommerce-message {
    background: #052e16;
    color: #86efac;
}

.dark .woocommerce-info {
    background: #1e3a8a;
    color: #93c5fd;
}
</style>

<?php get_footer('shop'); ?>