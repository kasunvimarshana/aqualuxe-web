<?php
/**
 * Booking Form Template
 *
 * @package AquaLuxe\Modules\Bookings
 */

// Get parameters
$bookable_id = isset($bookable_id) ? absint($bookable_id) : 0;
$module = isset($module) ? $module : null;

// Check if bookable ID is valid
if (!$bookable_id || !$module) {
    return;
}

// Get bookable item
$bookable = get_post($bookable_id);

if (!$bookable || $bookable->post_type !== 'aqualuxe_bookable') {
    return;
}

// Get booking type
$booking_type = get_post_meta($bookable_id, '_aqualuxe_booking_type', true);
$booking_type = $booking_type ? $booking_type : 'date';

// Get availability
$availability = new \AquaLuxe\Modules\Bookings\Availability($bookable_id);

// Get calendar
$calendar = new \AquaLuxe\Modules\Bookings\Calendar($bookable_id);

// Get payment methods
$payment = new \AquaLuxe\Modules\Bookings\Payment(new \AquaLuxe\Modules\Bookings\Booking());
$payment_methods = $payment->get_methods();

// Get settings
$enable_payments = $module->get_setting('enable_payments', true);
$require_payment = $module->get_setting('require_payment', true);
?>

<div class="aqualuxe-booking-form" data-bookable-id="<?php echo esc_attr($bookable_id); ?>">
    <h2 class="aqualuxe-booking-form__title"><?php esc_html_e('Book Now', 'aqualuxe'); ?></h2>
    
    <form method="post" action="">
        <div class="aqualuxe-booking-form__section">
            <h3 class="aqualuxe-booking-form__section-title"><?php esc_html_e('Select Date & Time', 'aqualuxe'); ?></h3>
            
            <?php
            // Render calendar
            $calendar->render([
                'id' => 'booking-calendar-' . $bookable_id,
                'class' => 'aqualuxe-calendar',
                'show_title' => false,
                'months' => 1,
                'inline' => true,
            ]);
            ?>
            
            <div class="aqualuxe-booking-form__field aqualuxe-booking-form__field--hidden">
                <input type="hidden" name="bookable_id" value="<?php echo esc_attr($bookable_id); ?>">
                <input type="hidden" name="start_date" value="">
                <?php if ($booking_type === 'date_range') : ?>
                    <input type="hidden" name="end_date" value="">
                <?php endif; ?>
                <?php if ($booking_type === 'date_time' || $booking_type === 'time') : ?>
                    <input type="hidden" name="start_time" value="">
                    <?php if ($booking_type === 'date_time') : ?>
                        <input type="hidden" name="end_time" value="">
                    <?php endif; ?>
                <?php endif; ?>
            </div>
            
            <div class="aqualuxe-booking-form__field">
                <label class="aqualuxe-booking-form__label" for="booking-quantity"><?php esc_html_e('Quantity', 'aqualuxe'); ?> <span class="aqualuxe-booking-form__required">*</span></label>
                <select class="aqualuxe-booking-form__select" id="booking-quantity" name="quantity">
                    <?php
                    // Get capacity
                    $capacity = get_post_meta($bookable_id, '_aqualuxe_booking_capacity', true);
                    $capacity = $capacity ? absint($capacity) : 10;
                    
                    // Generate options
                    for ($i = 1; $i <= $capacity; $i++) {
                        echo '<option value="' . esc_attr($i) . '">' . esc_html($i) . '</option>';
                    }
                    ?>
                </select>
            </div>
            
            <div class="aqualuxe-booking-form__availability"></div>
            
            <div class="aqualuxe-booking-form__price-container">
                <span class="aqualuxe-booking-form__price-label"><?php esc_html_e('Price:', 'aqualuxe'); ?></span>
                <span class="aqualuxe-booking-form__price">
                    <?php
                    // Get base price
                    $base_price = get_post_meta($bookable_id, '_aqualuxe_base_price', true);
                    $base_price = $base_price ? floatval($base_price) : 0;
                    
                    // Format price
                    echo $module->format_price($base_price);
                    ?>
                </span>
            </div>
        </div>
        
        <div class="aqualuxe-booking-form__section">
            <h3 class="aqualuxe-booking-form__section-title"><?php esc_html_e('Your Information', 'aqualuxe'); ?></h3>
            
            <div class="aqualuxe-booking-form__field">
                <label class="aqualuxe-booking-form__label" for="booking-name"><?php esc_html_e('Name', 'aqualuxe'); ?> <span class="aqualuxe-booking-form__required">*</span></label>
                <input class="aqualuxe-booking-form__input" type="text" id="booking-name" name="customer_name" required>
            </div>
            
            <div class="aqualuxe-booking-form__field">
                <label class="aqualuxe-booking-form__label" for="booking-email"><?php esc_html_e('Email', 'aqualuxe'); ?> <span class="aqualuxe-booking-form__required">*</span></label>
                <input class="aqualuxe-booking-form__input" type="email" id="booking-email" name="customer_email" required>
            </div>
            
            <div class="aqualuxe-booking-form__field">
                <label class="aqualuxe-booking-form__label" for="booking-phone"><?php esc_html_e('Phone', 'aqualuxe'); ?> <span class="aqualuxe-booking-form__required">*</span></label>
                <input class="aqualuxe-booking-form__input" type="tel" id="booking-phone" name="customer_phone" required>
            </div>
            
            <div class="aqualuxe-booking-form__field">
                <label class="aqualuxe-booking-form__label" for="booking-address"><?php esc_html_e('Address', 'aqualuxe'); ?></label>
                <textarea class="aqualuxe-booking-form__textarea" id="booking-address" name="customer_address"></textarea>
            </div>
            
            <div class="aqualuxe-booking-form__field">
                <label class="aqualuxe-booking-form__label" for="booking-notes"><?php esc_html_e('Special Requests', 'aqualuxe'); ?></label>
                <textarea class="aqualuxe-booking-form__textarea" id="booking-notes" name="customer_notes"></textarea>
            </div>
        </div>
        
        <?php if ($enable_payments && !empty($payment_methods)) : ?>
            <div class="aqualuxe-booking-form__section">
                <h3 class="aqualuxe-booking-form__section-title"><?php esc_html_e('Payment Method', 'aqualuxe'); ?></h3>
                
                <div class="aqualuxe-payment-methods">
                    <ul class="aqualuxe-payment-methods__list">
                        <?php foreach ($payment_methods as $method) : ?>
                            <li class="aqualuxe-payment-methods__item">
                                <label class="aqualuxe-payment-methods__label">
                                    <input type="radio" class="aqualuxe-payment-methods__radio" name="payment_method" value="<?php echo esc_attr($method['id']); ?>" <?php checked($method['id'], 'stripe'); ?>>
                                    <span class="aqualuxe-payment-methods__icon">
                                        <i class="dashicons dashicons-<?php echo esc_attr($method['icon']); ?>"></i>
                                    </span>
                                    <span class="aqualuxe-payment-methods__name"><?php echo esc_html($method['name']); ?></span>
                                    <span class="aqualuxe-payment-methods__description"><?php echo esc_html($method['description']); ?></span>
                                </label>
                                
                                <div class="aqualuxe-payment-methods__content">
                                    <?php if ($method['id'] === 'stripe') : ?>
                                        <div class="aqualuxe-payment-methods__stripe">
                                            <p><?php esc_html_e('You will be redirected to the secure payment page after submitting your booking.', 'aqualuxe'); ?></p>
                                        </div>
                                    <?php elseif ($method['id'] === 'paypal') : ?>
                                        <div class="aqualuxe-payment-methods__paypal">
                                            <p><?php esc_html_e('You will be redirected to PayPal after submitting your booking.', 'aqualuxe'); ?></p>
                                        </div>
                                    <?php elseif ($method['id'] === 'bank_transfer') : ?>
                                        <div class="aqualuxe-payment-methods__bank-transfer">
                                            <p><?php esc_html_e('Please use the following information to make a bank transfer:', 'aqualuxe'); ?></p>
                                            <p>
                                                <strong><?php esc_html_e('Bank Name:', 'aqualuxe'); ?></strong> Example Bank<br>
                                                <strong><?php esc_html_e('Account Name:', 'aqualuxe'); ?></strong> AquaLuxe<br>
                                                <strong><?php esc_html_e('Account Number:', 'aqualuxe'); ?></strong> 1234567890<br>
                                                <strong><?php esc_html_e('Sort Code:', 'aqualuxe'); ?></strong> 12-34-56<br>
                                                <strong><?php esc_html_e('Reference:', 'aqualuxe'); ?></strong> <?php esc_html_e('Your Name', 'aqualuxe'); ?>
                                            </p>
                                        </div>
                                    <?php elseif ($method['id'] === 'cash') : ?>
                                        <div class="aqualuxe-payment-methods__cash">
                                            <p><?php esc_html_e('Please pay with cash upon arrival.', 'aqualuxe'); ?></p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        <?php endif; ?>
        
        <div class="aqualuxe-booking-form__message"></div>
        
        <div class="aqualuxe-booking-form__actions">
            <button type="submit" class="aqualuxe-booking-form__button"><?php esc_html_e('Book Now', 'aqualuxe'); ?></button>
        </div>
    </form>
</div>