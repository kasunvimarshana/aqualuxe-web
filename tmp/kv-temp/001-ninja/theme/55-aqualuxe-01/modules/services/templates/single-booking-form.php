<?php
/**
 * Single service booking form template part
 *
 * @package AquaLuxe
 * @subpackage Modules/Services
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Get service booking
$booking_enabled = get_post_meta(get_the_ID(), '_service_booking_enabled', true);
$booking_type = get_post_meta(get_the_ID(), '_service_booking_type', true);
$booking_form = get_post_meta(get_the_ID(), '_service_booking_form', true);

// Check if booking is enabled
if ($booking_enabled !== 'yes') {
    return;
}
?>

<div class="aqualuxe-single-service-booking-form">
    <h3 class="aqualuxe-single-service-booking-title"><?php esc_html_e('Book This Service', 'aqualuxe'); ?></h3>

    <?php if ($booking_type === 'form' && $booking_form && function_exists('wpcf7_contact_form')) : ?>
        <?php
        // Get Contact Form 7 form
        $form = wpcf7_contact_form($booking_form);

        if ($form) {
            echo '<div class="aqualuxe-service-booking-form">';
            echo do_shortcode('[contact-form-7 id="' . esc_attr($booking_form) . '"]');
            echo '</div>';
        } else {
            echo '<p>' . esc_html__('Booking form not found. Please configure a booking form in the service settings.', 'aqualuxe') . '</p>';
        }
        ?>
    <?php elseif ($booking_type === 'calendar') : ?>
        <div class="aqualuxe-service-booking-calendar">
            <form class="aqualuxe-service-booking-calendar-form" method="post">
                <div class="aqualuxe-service-booking-form-row">
                    <div class="aqualuxe-service-booking-form-field">
                        <label for="booking-date"><?php esc_html_e('Date', 'aqualuxe'); ?></label>
                        <input type="date" id="booking-date" name="booking_date" class="aqualuxe-service-booking-date" required>
                    </div>

                    <div class="aqualuxe-service-booking-form-field">
                        <label for="booking-time"><?php esc_html_e('Time', 'aqualuxe'); ?></label>
                        <input type="time" id="booking-time" name="booking_time" class="aqualuxe-service-booking-time" required>
                    </div>
                </div>

                <div class="aqualuxe-service-booking-form-row">
                    <div class="aqualuxe-service-booking-form-field">
                        <label for="booking-name"><?php esc_html_e('Name', 'aqualuxe'); ?></label>
                        <input type="text" id="booking-name" name="booking_name" required>
                    </div>

                    <div class="aqualuxe-service-booking-form-field">
                        <label for="booking-email"><?php esc_html_e('Email', 'aqualuxe'); ?></label>
                        <input type="email" id="booking-email" name="booking_email" required>
                    </div>
                </div>

                <div class="aqualuxe-service-booking-form-row">
                    <div class="aqualuxe-service-booking-form-field">
                        <label for="booking-phone"><?php esc_html_e('Phone', 'aqualuxe'); ?></label>
                        <input type="tel" id="booking-phone" name="booking_phone" required>
                    </div>

                    <div class="aqualuxe-service-booking-form-field">
                        <label for="booking-participants"><?php esc_html_e('Number of Participants', 'aqualuxe'); ?></label>
                        <input type="number" id="booking-participants" name="booking_participants" min="1" value="1" required>
                    </div>
                </div>

                <div class="aqualuxe-service-booking-form-row">
                    <div class="aqualuxe-service-booking-form-field aqualuxe-service-booking-form-field-full">
                        <label for="booking-message"><?php esc_html_e('Message', 'aqualuxe'); ?></label>
                        <textarea id="booking-message" name="booking_message" rows="4"></textarea>
                    </div>
                </div>

                <div class="aqualuxe-service-booking-form-actions">
                    <button type="submit" class="aqualuxe-service-booking-submit"><?php esc_html_e('Book Now', 'aqualuxe'); ?></button>
                </div>

                <?php wp_nonce_field('aqualuxe_service_booking', 'aqualuxe_service_booking_nonce'); ?>
                <input type="hidden" name="service_id" value="<?php echo esc_attr(get_the_ID()); ?>">
                <input type="hidden" name="action" value="aqualuxe_service_booking">
            </form>
        </div>
    <?php elseif ($booking_type === 'external') : ?>
        <?php
        // Get external booking URL
        $booking_url = get_post_meta(get_the_ID(), '_service_booking_url', true);
        
        if ($booking_url) {
            echo '<div class="aqualuxe-service-booking-external">';
            echo '<p>' . esc_html__('Click the button below to book this service on our booking platform.', 'aqualuxe') . '</p>';
            echo '<a href="' . esc_url($booking_url) . '" class="button aqualuxe-service-booking-external-button" target="_blank">' . esc_html__('Book Now', 'aqualuxe') . '</a>';
            echo '</div>';
        } else {
            echo '<p>' . esc_html__('External booking URL not found. Please configure a booking URL in the service settings.', 'aqualuxe') . '</p>';
        }
        ?>
    <?php else : ?>
        <p><?php esc_html_e('Booking is not available for this service. Please contact us for more information.', 'aqualuxe'); ?></p>
    <?php endif; ?>
</div>