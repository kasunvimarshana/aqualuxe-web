<?php
/**
 * Booking Form Template
 *
 * @package AquaLuxe
 * @subpackage Modules/Bookings
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

// Get form style
$style = isset($atts['style']) ? $atts['style'] : 'standard';

// Get services
$services = isset($services) ? $services : [];
?>

<div class="aqualuxe-booking-form" data-style="<?php echo esc_attr($style); ?>">
    <h2 class="aqualuxe-booking-form__title"><?php esc_html_e('Book Your Appointment', 'aqualuxe'); ?></h2>
    
    <form>
        <div class="aqualuxe-booking-form__section">
            <h3 class="aqualuxe-booking-form__section-title"><?php esc_html_e('Service Details', 'aqualuxe'); ?></h3>
            
            <div class="aqualuxe-booking-form__field">
                <label for="service-select" class="aqualuxe-booking-form__label"><?php esc_html_e('Select Service', 'aqualuxe'); ?></label>
                <select id="service-select" name="service_id" class="aqualuxe-booking-form__select aqualuxe-booking-form__service-select" required>
                    <option value=""><?php esc_html_e('Select a service', 'aqualuxe'); ?></option>
                    <?php foreach ($services as $service) : ?>
                        <option value="<?php echo esc_attr($service['id']); ?>" <?php selected(isset($_GET['service_id']) && $_GET['service_id'] == $service['id']); ?>>
                            <?php echo esc_html($service['name']); ?> - <?php echo wc_price($service['price']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="aqualuxe-booking-form__field">
                <label for="date-input" class="aqualuxe-booking-form__label"><?php esc_html_e('Select Date', 'aqualuxe'); ?></label>
                <input type="text" id="date-input" name="date" class="aqualuxe-booking-form__input aqualuxe-booking-form__date-input" placeholder="<?php esc_attr_e('Select a date', 'aqualuxe'); ?>" value="<?php echo isset($_GET['date']) ? esc_attr($_GET['date']) : ''; ?>" required disabled>
            </div>
            
            <div class="aqualuxe-booking-form__field">
                <label for="time-select" class="aqualuxe-booking-form__label"><?php esc_html_e('Select Time', 'aqualuxe'); ?></label>
                <select id="time-select" name="time" class="aqualuxe-booking-form__select aqualuxe-booking-form__time-select" required disabled>
                    <option value=""><?php esc_html_e('Select a time', 'aqualuxe'); ?></option>
                </select>
            </div>
        </div>
        
        <div class="aqualuxe-booking-form__customer-fields">
            <div class="aqualuxe-booking-form__section">
                <h3 class="aqualuxe-booking-form__section-title"><?php esc_html_e('Your Information', 'aqualuxe'); ?></h3>
                
                <div class="aqualuxe-booking-form__field">
                    <label for="customer-name" class="aqualuxe-booking-form__label"><?php esc_html_e('Full Name', 'aqualuxe'); ?></label>
                    <input type="text" id="customer-name" name="customer_name" class="aqualuxe-booking-form__input" placeholder="<?php esc_attr_e('Enter your full name', 'aqualuxe'); ?>" required>
                </div>
                
                <div class="aqualuxe-booking-form__field">
                    <label for="customer-email" class="aqualuxe-booking-form__label"><?php esc_html_e('Email Address', 'aqualuxe'); ?></label>
                    <input type="email" id="customer-email" name="customer_email" class="aqualuxe-booking-form__input" placeholder="<?php esc_attr_e('Enter your email address', 'aqualuxe'); ?>" required>
                </div>
                
                <div class="aqualuxe-booking-form__field">
                    <label for="customer-phone" class="aqualuxe-booking-form__label"><?php esc_html_e('Phone Number', 'aqualuxe'); ?></label>
                    <input type="tel" id="customer-phone" name="customer_phone" class="aqualuxe-booking-form__input" placeholder="<?php esc_attr_e('Enter your phone number', 'aqualuxe'); ?>" required>
                </div>
                
                <div class="aqualuxe-booking-form__field">
                    <label for="customer-notes" class="aqualuxe-booking-form__label"><?php esc_html_e('Special Requests', 'aqualuxe'); ?></label>
                    <textarea id="customer-notes" name="customer_notes" class="aqualuxe-booking-form__textarea" placeholder="<?php esc_attr_e('Enter any special requests or notes', 'aqualuxe'); ?>"></textarea>
                </div>
            </div>
            
            <div class="aqualuxe-booking-form__message" style="display: none;"></div>
            
            <button type="submit" class="aqualuxe-booking-form__submit" disabled><?php esc_html_e('Book Now', 'aqualuxe'); ?></button>
        </div>
    </form>
</div>

<script type="text/javascript">
    // Initialize services data for JavaScript
    window.aqualuxeServices = <?php echo json_encode(array_reduce($services, function($result, $service) {
        $result[$service['id']] = [
            'name' => $service['name'],
            'price' => $service['price'],
            'duration' => $service['duration'],
            'capacity' => $service['capacity'],
            'buffer_time' => $service['buffer_time'],
        ];
        return $result;
    }, [])); ?>;
</script>