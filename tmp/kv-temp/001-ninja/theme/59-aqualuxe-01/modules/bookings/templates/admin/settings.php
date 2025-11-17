<?php
/**
 * Admin Settings Template
 *
 * @package AquaLuxe
 * @subpackage Modules/Bookings
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

// Get settings
$settings = isset($settings) ? $settings : [];

// Process form submission
if (isset($_POST['aqualuxe_bookings_settings_nonce']) && wp_verify_nonce($_POST['aqualuxe_bookings_settings_nonce'], 'aqualuxe_bookings_settings')) {
    // Update settings
    $updated_settings = [
        'enabled' => isset($_POST['enabled']),
        'booking_page' => isset($_POST['booking_page']) ? absint($_POST['booking_page']) : 0,
        'confirmation_page' => isset($_POST['confirmation_page']) ? absint($_POST['confirmation_page']) : 0,
        'form_style' => isset($_POST['form_style']) ? sanitize_text_field($_POST['form_style']) : 'standard',
        'calendar_style' => isset($_POST['calendar_style']) ? sanitize_text_field($_POST['calendar_style']) : 'standard',
        'email_notifications' => isset($_POST['email_notifications']),
        'admin_email' => isset($_POST['admin_email']) ? sanitize_email($_POST['admin_email']) : '',
        'confirmation_email_subject' => isset($_POST['confirmation_email_subject']) ? sanitize_text_field($_POST['confirmation_email_subject']) : '',
        'confirmation_email_message' => isset($_POST['confirmation_email_message']) ? wp_kses_post($_POST['confirmation_email_message']) : '',
    ];
    
    // Update option
    update_option('aqualuxe_bookings_settings', $updated_settings);
    
    // Show success message
    echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__('Settings saved successfully.', 'aqualuxe') . '</p></div>';
    
    // Update settings variable
    $settings = $updated_settings;
}
?>

<div class="wrap">
    <h1><?php esc_html_e('Booking Settings', 'aqualuxe'); ?></h1>
    
    <form method="post" action="" class="aqualuxe-booking-settings">
        <?php wp_nonce_field('aqualuxe_bookings_settings', 'aqualuxe_bookings_settings_nonce'); ?>
        
        <div class="aqualuxe-booking-settings__section">
            <h2 class="aqualuxe-booking-settings__section-title"><?php esc_html_e('General Settings', 'aqualuxe'); ?></h2>
            
            <div class="aqualuxe-booking-settings__field">
                <label for="enabled">
                    <input type="checkbox" id="enabled" name="enabled" value="1" <?php checked(isset($settings['enabled']) && $settings['enabled']); ?>>
                    <?php esc_html_e('Enable Bookings', 'aqualuxe'); ?>
                </label>
                <p class="aqualuxe-booking-settings__field-description"><?php esc_html_e('Enable or disable the booking functionality.', 'aqualuxe'); ?></p>
            </div>
            
            <div class="aqualuxe-booking-settings__field">
                <label for="booking_page"><?php esc_html_e('Booking Page', 'aqualuxe'); ?></label>
                <?php
                wp_dropdown_pages([
                    'name' => 'booking_page',
                    'id' => 'booking_page',
                    'selected' => isset($settings['booking_page']) ? $settings['booking_page'] : 0,
                    'show_option_none' => __('Select a page', 'aqualuxe'),
                ]);
                ?>
                <p class="aqualuxe-booking-settings__field-description"><?php esc_html_e('Select the page where the booking form will be displayed. Add the [aqualuxe_booking_form] shortcode to this page.', 'aqualuxe'); ?></p>
            </div>
            
            <div class="aqualuxe-booking-settings__field">
                <label for="confirmation_page"><?php esc_html_e('Confirmation Page', 'aqualuxe'); ?></label>
                <?php
                wp_dropdown_pages([
                    'name' => 'confirmation_page',
                    'id' => 'confirmation_page',
                    'selected' => isset($settings['confirmation_page']) ? $settings['confirmation_page'] : 0,
                    'show_option_none' => __('Select a page', 'aqualuxe'),
                ]);
                ?>
                <p class="aqualuxe-booking-settings__field-description"><?php esc_html_e('Select the page where users will be redirected after a successful booking. Add the [aqualuxe_booking_confirmation] shortcode to this page.', 'aqualuxe'); ?></p>
            </div>
        </div>
        
        <div class="aqualuxe-booking-settings__section">
            <h2 class="aqualuxe-booking-settings__section-title"><?php esc_html_e('Display Settings', 'aqualuxe'); ?></h2>
            
            <div class="aqualuxe-booking-settings__field">
                <label for="form_style"><?php esc_html_e('Booking Form Style', 'aqualuxe'); ?></label>
                <select id="form_style" name="form_style">
                    <option value="standard" <?php selected(isset($settings['form_style']) && $settings['form_style'] === 'standard'); ?>><?php esc_html_e('Standard', 'aqualuxe'); ?></option>
                    <option value="compact" <?php selected(isset($settings['form_style']) && $settings['form_style'] === 'compact'); ?>><?php esc_html_e('Compact', 'aqualuxe'); ?></option>
                    <option value="stepped" <?php selected(isset($settings['form_style']) && $settings['form_style'] === 'stepped'); ?>><?php esc_html_e('Stepped', 'aqualuxe'); ?></option>
                </select>
                <p class="aqualuxe-booking-settings__field-description"><?php esc_html_e('Select the style for the booking form.', 'aqualuxe'); ?></p>
            </div>
            
            <div class="aqualuxe-booking-settings__field">
                <label for="calendar_style"><?php esc_html_e('Calendar Style', 'aqualuxe'); ?></label>
                <select id="calendar_style" name="calendar_style">
                    <option value="standard" <?php selected(isset($settings['calendar_style']) && $settings['calendar_style'] === 'standard'); ?>><?php esc_html_e('Standard', 'aqualuxe'); ?></option>
                    <option value="modern" <?php selected(isset($settings['calendar_style']) && $settings['calendar_style'] === 'modern'); ?>><?php esc_html_e('Modern', 'aqualuxe'); ?></option>
                    <option value="minimal" <?php selected(isset($settings['calendar_style']) && $settings['calendar_style'] === 'minimal'); ?>><?php esc_html_e('Minimal', 'aqualuxe'); ?></option>
                </select>
                <p class="aqualuxe-booking-settings__field-description"><?php esc_html_e('Select the style for the booking calendar.', 'aqualuxe'); ?></p>
            </div>
        </div>
        
        <div class="aqualuxe-booking-settings__section">
            <h2 class="aqualuxe-booking-settings__section-title"><?php esc_html_e('Email Notifications', 'aqualuxe'); ?></h2>
            
            <div class="aqualuxe-booking-settings__field">
                <label for="email_notifications">
                    <input type="checkbox" id="email_notifications" name="email_notifications" value="1" <?php checked(isset($settings['email_notifications']) && $settings['email_notifications']); ?>>
                    <?php esc_html_e('Enable Email Notifications', 'aqualuxe'); ?>
                </label>
                <p class="aqualuxe-booking-settings__field-description"><?php esc_html_e('Send email notifications for new bookings.', 'aqualuxe'); ?></p>
            </div>
            
            <div class="aqualuxe-booking-settings__field">
                <label for="admin_email"><?php esc_html_e('Admin Email', 'aqualuxe'); ?></label>
                <input type="email" id="admin_email" name="admin_email" value="<?php echo esc_attr(isset($settings['admin_email']) ? $settings['admin_email'] : get_option('admin_email')); ?>">
                <p class="aqualuxe-booking-settings__field-description"><?php esc_html_e('Email address to receive booking notifications.', 'aqualuxe'); ?></p>
            </div>
            
            <div class="aqualuxe-booking-settings__field">
                <label for="confirmation_email_subject"><?php esc_html_e('Confirmation Email Subject', 'aqualuxe'); ?></label>
                <input type="text" id="confirmation_email_subject" name="confirmation_email_subject" value="<?php echo esc_attr(isset($settings['confirmation_email_subject']) ? $settings['confirmation_email_subject'] : __('Your Booking Confirmation', 'aqualuxe')); ?>">
                <p class="aqualuxe-booking-settings__field-description"><?php esc_html_e('Subject line for the booking confirmation email.', 'aqualuxe'); ?></p>
            </div>
            
            <div class="aqualuxe-booking-settings__field">
                <label for="confirmation_email_message"><?php esc_html_e('Confirmation Email Message', 'aqualuxe'); ?></label>
                <textarea id="confirmation_email_message" name="confirmation_email_message" rows="10"><?php echo esc_textarea(isset($settings['confirmation_email_message']) ? $settings['confirmation_email_message'] : __("Thank you for your booking!\n\nService: {service}\nDate: {date}\nTime: {time}\n\nWe look forward to seeing you.\n\n{site_name}", 'aqualuxe')); ?></textarea>
                <p class="aqualuxe-booking-settings__field-description">
                    <?php esc_html_e('Message for the booking confirmation email. You can use the following placeholders:', 'aqualuxe'); ?><br>
                    <code>{service}</code> - <?php esc_html_e('Service name', 'aqualuxe'); ?><br>
                    <code>{date}</code> - <?php esc_html_e('Booking date', 'aqualuxe'); ?><br>
                    <code>{time}</code> - <?php esc_html_e('Booking time', 'aqualuxe'); ?><br>
                    <code>{customer_name}</code> - <?php esc_html_e('Customer name', 'aqualuxe'); ?><br>
                    <code>{customer_email}</code> - <?php esc_html_e('Customer email', 'aqualuxe'); ?><br>
                    <code>{customer_phone}</code> - <?php esc_html_e('Customer phone', 'aqualuxe'); ?><br>
                    <code>{site_name}</code> - <?php esc_html_e('Site name', 'aqualuxe'); ?>
                </p>
            </div>
        </div>
        
        <div class="aqualuxe-booking-settings__submit">
            <button type="submit" class="button button-primary"><?php esc_html_e('Save Settings', 'aqualuxe'); ?></button>
        </div>
    </form>
</div>