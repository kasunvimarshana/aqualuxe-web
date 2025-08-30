<?php
/**
 * Admin Settings Template
 *
 * @package AquaLuxe
 * @subpackage Bookings
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Get current tab
$current_tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'general';

// Define tabs
$tabs = array(
    'general' => __('General', 'aqualuxe'),
    'display' => __('Display', 'aqualuxe'),
    'notifications' => __('Notifications', 'aqualuxe'),
    'integrations' => __('Integrations', 'aqualuxe'),
);

// Get settings fields
$settings = new AquaLuxe_Bookings_Settings();

switch ($current_tab) {
    case 'general':
        $fields = $settings->get_general_settings_fields();
        break;
    case 'display':
        $fields = $settings->get_display_settings_fields();
        break;
    case 'notifications':
        $fields = $settings->get_notification_settings_fields();
        break;
    case 'integrations':
        $fields = $settings->get_integration_settings_fields();
        break;
    default:
        $fields = $settings->get_general_settings_fields();
        break;
}
?>

<div class="wrap aqualuxe-bookings-settings">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
    
    <h2 class="nav-tab-wrapper">
        <?php foreach ($tabs as $tab_id => $tab_name) : ?>
            <a href="<?php echo esc_url(admin_url('admin.php?page=aqualuxe-bookings-settings&tab=' . $tab_id)); ?>" class="nav-tab <?php echo $current_tab === $tab_id ? 'nav-tab-active' : ''; ?>"><?php echo esc_html($tab_name); ?></a>
        <?php endforeach; ?>
    </h2>
    
    <form method="post" action="options.php" class="aqualuxe-bookings-settings-form">
        <?php settings_fields('aqualuxe_bookings_settings'); ?>
        
        <div class="aqualuxe-bookings-settings-content">
            <?php if ($current_tab === 'general') : ?>
                <div class="settings-section">
                    <h2><?php _e('Pages Setup', 'aqualuxe'); ?></h2>
                    <p><?php _e('Configure the pages used by the bookings module.', 'aqualuxe'); ?></p>
                    
                    <table class="form-table">
                        <?php foreach ($fields as $field) : ?>
                            <?php if (in_array($field['name'], array('aqualuxe_bookings_page_id', 'aqualuxe_bookings_confirmation_page_id', 'aqualuxe_bookings_terms_page_id'))) : ?>
                                <tr>
                                    <th scope="row"><label for="<?php echo esc_attr($field['name']); ?>"><?php echo esc_html($field['label']); ?></label></th>
                                    <td>
                                        <?php $settings->render_field($field); ?>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </table>
                </div>
                
                <div class="settings-section">
                    <h2><?php _e('Booking Options', 'aqualuxe'); ?></h2>
                    <p><?php _e('Configure general booking options.', 'aqualuxe'); ?></p>
                    
                    <table class="form-table">
                        <?php foreach ($fields as $field) : ?>
                            <?php if (in_array($field['name'], array('aqualuxe_bookings_booking_confirmation', 'aqualuxe_bookings_require_account', 'aqualuxe_bookings_allow_guest_bookings', 'aqualuxe_bookings_minimum_notice', 'aqualuxe_bookings_maximum_advance'))) : ?>
                                <tr>
                                    <th scope="row"><label for="<?php echo esc_attr($field['name']); ?>"><?php echo esc_html($field['label']); ?></label></th>
                                    <td>
                                        <?php $settings->render_field($field); ?>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </table>
                </div>
                
                <div class="settings-section">
                    <h2><?php _e('Cancellation Policy', 'aqualuxe'); ?></h2>
                    <p><?php _e('Configure your cancellation policy.', 'aqualuxe'); ?></p>
                    
                    <table class="form-table">
                        <?php foreach ($fields as $field) : ?>
                            <?php if ($field['name'] === 'aqualuxe_bookings_cancellation_policy') : ?>
                                <tr>
                                    <th scope="row"><label for="<?php echo esc_attr($field['name']); ?>"><?php echo esc_html($field['label']); ?></label></th>
                                    <td>
                                        <?php $settings->render_field($field); ?>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </table>
                </div>
            <?php elseif ($current_tab === 'display') : ?>
                <div class="settings-section">
                    <h2><?php _e('Calendar Settings', 'aqualuxe'); ?></h2>
                    <p><?php _e('Configure calendar display settings.', 'aqualuxe'); ?></p>
                    
                    <table class="form-table">
                        <?php foreach ($fields as $field) : ?>
                            <?php if (in_array($field['name'], array('aqualuxe_bookings_calendar_first_day', 'aqualuxe_bookings_time_format', 'aqualuxe_bookings_calendar_style'))) : ?>
                                <tr>
                                    <th scope="row"><label for="<?php echo esc_attr($field['name']); ?>"><?php echo esc_html($field['label']); ?></label></th>
                                    <td>
                                        <?php $settings->render_field($field); ?>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </table>
                </div>
                
                <div class="settings-section">
                    <h2><?php _e('Time Slot Settings', 'aqualuxe'); ?></h2>
                    <p><?php _e('Configure time slot settings.', 'aqualuxe'); ?></p>
                    
                    <table class="form-table">
                        <?php foreach ($fields as $field) : ?>
                            <?php if (in_array($field['name'], array('aqualuxe_bookings_buffer_time', 'aqualuxe_bookings_min_booking_time', 'aqualuxe_bookings_max_booking_time'))) : ?>
                                <tr>
                                    <th scope="row"><label for="<?php echo esc_attr($field['name']); ?>"><?php echo esc_html($field['label']); ?></label></th>
                                    <td>
                                        <?php $settings->render_field($field); ?>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </table>
                </div>
                
                <div class="settings-section">
                    <h2><?php _e('Business Hours', 'aqualuxe'); ?></h2>
                    <p><?php _e('Set your default business hours. These can be overridden for individual services.', 'aqualuxe'); ?></p>
                    
                    <table class="form-table">
                        <?php foreach ($fields as $field) : ?>
                            <?php if ($field['name'] === 'aqualuxe_bookings_business_hours') : ?>
                                <tr>
                                    <th scope="row"><label for="<?php echo esc_attr($field['name']); ?>"><?php echo esc_html($field['label']); ?></label></th>
                                    <td>
                                        <?php $settings->render_field($field); ?>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </table>
                </div>
                
                <div class="settings-section">
                    <h2><?php _e('Appearance', 'aqualuxe'); ?></h2>
                    <p><?php _e('Configure appearance settings.', 'aqualuxe'); ?></p>
                    
                    <table class="form-table">
                        <?php foreach ($fields as $field) : ?>
                            <?php if (in_array($field['name'], array('aqualuxe_bookings_color_scheme', 'aqualuxe_bookings_form_style'))) : ?>
                                <tr>
                                    <th scope="row"><label for="<?php echo esc_attr($field['name']); ?>"><?php echo esc_html($field['label']); ?></label></th>
                                    <td>
                                        <?php $settings->render_field($field); ?>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </table>
                </div>
            <?php elseif ($current_tab === 'notifications') : ?>
                <div class="settings-section">
                    <h2><?php _e('Email Notifications', 'aqualuxe'); ?></h2>
                    <p><?php _e('Configure email notification settings.', 'aqualuxe'); ?></p>
                    
                    <table class="form-table">
                        <?php foreach ($fields as $field) : ?>
                            <?php if (in_array($field['name'], array('aqualuxe_bookings_notification_emails', 'aqualuxe_bookings_admin_notification_email', 'aqualuxe_bookings_customer_notification_email'))) : ?>
                                <tr>
                                    <th scope="row"><label for="<?php echo esc_attr($field['name']); ?>"><?php echo esc_html($field['label']); ?></label></th>
                                    <td>
                                        <?php $settings->render_field($field); ?>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </table>
                </div>
                
                <div class="settings-section">
                    <h2><?php _e('Admin Email Template', 'aqualuxe'); ?></h2>
                    <p><?php _e('Configure the admin notification email template.', 'aqualuxe'); ?></p>
                    
                    <table class="form-table">
                        <?php foreach ($fields as $field) : ?>
                            <?php if (in_array($field['name'], array('aqualuxe_bookings_admin_email_subject', 'aqualuxe_bookings_admin_email_template'))) : ?>
                                <tr>
                                    <th scope="row"><label for="<?php echo esc_attr($field['name']); ?>"><?php echo esc_html($field['label']); ?></label></th>
                                    <td>
                                        <?php $settings->render_field($field); ?>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </table>
                </div>
                
                <div class="settings-section">
                    <h2><?php _e('Customer Email Template', 'aqualuxe'); ?></h2>
                    <p><?php _e('Configure the customer notification email template.', 'aqualuxe'); ?></p>
                    
                    <table class="form-table">
                        <?php foreach ($fields as $field) : ?>
                            <?php if (in_array($field['name'], array('aqualuxe_bookings_customer_email_subject', 'aqualuxe_bookings_customer_email_template'))) : ?>
                                <tr>
                                    <th scope="row"><label for="<?php echo esc_attr($field['name']); ?>"><?php echo esc_html($field['label']); ?></label></th>
                                    <td>
                                        <?php $settings->render_field($field); ?>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </table>
                </div>
                
                <div class="settings-section">
                    <h2><?php _e('Reminder Emails', 'aqualuxe'); ?></h2>
                    <p><?php _e('Configure reminder email settings.', 'aqualuxe'); ?></p>
                    
                    <table class="form-table">
                        <?php foreach ($fields as $field) : ?>
                            <?php if (in_array($field['name'], array('aqualuxe_bookings_reminder_emails', 'aqualuxe_bookings_reminder_time'))) : ?>
                                <tr>
                                    <th scope="row"><label for="<?php echo esc_attr($field['name']); ?>"><?php echo esc_html($field['label']); ?></label></th>
                                    <td>
                                        <?php $settings->render_field($field); ?>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </table>
                </div>
            <?php elseif ($current_tab === 'integrations') : ?>
                <div class="settings-section">
                    <h2><?php _e('Google Calendar Integration', 'aqualuxe'); ?></h2>
                    <p><?php _e('Configure Google Calendar integration.', 'aqualuxe'); ?></p>
                    
                    <table class="form-table">
                        <?php foreach ($fields as $field) : ?>
                            <?php if (in_array($field['name'], array('aqualuxe_bookings_enable_google_calendar', 'aqualuxe_bookings_google_calendar_id', 'aqualuxe_bookings_google_calendar_api_key'))) : ?>
                                <tr>
                                    <th scope="row"><label for="<?php echo esc_attr($field['name']); ?>"><?php echo esc_html($field['label']); ?></label></th>
                                    <td>
                                        <?php $settings->render_field($field); ?>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </table>
                </div>
                
                <div class="settings-section">
                    <h2><?php _e('iCal Integration', 'aqualuxe'); ?></h2>
                    <p><?php _e('Configure iCal integration.', 'aqualuxe'); ?></p>
                    
                    <table class="form-table">
                        <?php foreach ($fields as $field) : ?>
                            <?php if (in_array($field['name'], array('aqualuxe_bookings_enable_ical', 'aqualuxe_bookings_ical_feed_url'))) : ?>
                                <tr>
                                    <th scope="row"><label for="<?php echo esc_attr($field['name']); ?>"><?php echo esc_html($field['label']); ?></label></th>
                                    <td>
                                        <?php $settings->render_field($field); ?>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </table>
                </div>
                
                <div class="settings-section">
                    <h2><?php _e('WooCommerce Integration', 'aqualuxe'); ?></h2>
                    <p><?php _e('Configure WooCommerce integration.', 'aqualuxe'); ?></p>
                    
                    <table class="form-table">
                        <?php foreach ($fields as $field) : ?>
                            <?php if (in_array($field['name'], array('aqualuxe_bookings_enable_woocommerce', 'aqualuxe_bookings_product_id'))) : ?>
                                <tr>
                                    <th scope="row"><label for="<?php echo esc_attr($field['name']); ?>"><?php echo esc_html($field['label']); ?></label></th>
                                    <td>
                                        <?php $settings->render_field($field); ?>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </table>
                </div>
            <?php endif; ?>
        </div>
        
        <?php submit_button(); ?>
    </form>
</div>

<style>
.aqualuxe-bookings-settings {
    max-width: 1200px;
}

.aqualuxe-bookings-settings-content {
    margin-top: 20px;
}

.settings-section {
    background-color: #fff;
    border: 1px solid #ccd0d4;
    border-radius: 4px;
    margin-bottom: 20px;
    padding: 20px;
}

.settings-section h2 {
    margin-top: 0;
    padding-bottom: 10px;
    border-bottom: 1px solid #eee;
    color: #23282d;
    font-size: 1.3em;
}

.settings-section p {
    margin-top: 0;
    color: #646970;
}

.form-table th {
    width: 200px;
    padding: 15px 10px 15px 0;
}

.form-table td {
    padding: 15px 10px;
}

.business-hours-table {
    width: 100%;
    border-collapse: collapse;
}

.business-hours-table th,
.business-hours-table td {
    padding: 8px;
    text-align: left;
    border-bottom: 1px solid #f0f0f0;
}

.business-hours-table input[type="time"] {
    width: 120px;
}

.color-picker {
    width: 80px;
}

@media screen and (max-width: 782px) {
    .form-table th {
        width: 100%;
        display: block;
        padding-bottom: 0;
    }
    
    .form-table td {
        width: 100%;
        display: block;
        padding-top: 0;
    }
}
</style>

<script>
jQuery(document).ready(function($) {
    // Initialize color picker
    $('.color-picker').wpColorPicker();
});
</script>