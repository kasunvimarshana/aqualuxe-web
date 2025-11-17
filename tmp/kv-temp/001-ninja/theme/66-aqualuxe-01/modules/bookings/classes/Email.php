<?php
/**
 * Email Class
 *
 * @package AquaLuxe\Modules\Bookings
 */

namespace AquaLuxe\Modules\Bookings;

/**
 * Email Class
 */
class Email {
    /**
     * Booking object
     *
     * @var Booking
     */
    private $booking;

    /**
     * Email settings
     *
     * @var array
     */
    private $settings = [];

    /**
     * Constructor
     *
     * @param Booking $booking
     */
    public function __construct($booking) {
        $this->booking = $booking;
        $this->load_settings();
    }

    /**
     * Load email settings
     */
    private function load_settings() {
        // Get module instance
        $theme = \AquaLuxe\Theme::get_instance();
        $module = $theme->get_active_modules()['bookings'] ?? null;
        
        if ($module) {
            $this->settings = [
                'admin_email' => $module->get_setting('admin_email', get_option('admin_email')),
                'from_name' => $module->get_setting('email_from_name', get_bloginfo('name')),
                'from_email' => $module->get_setting('email_from_address', get_option('admin_email')),
                'admin_notification' => $module->get_setting('admin_email_notification', true),
                'customer_notification' => $module->get_setting('customer_email_notification', true),
            ];
        } else {
            $this->settings = [
                'admin_email' => get_option('admin_email'),
                'from_name' => get_bloginfo('name'),
                'from_email' => get_option('admin_email'),
                'admin_notification' => true,
                'customer_notification' => true,
            ];
        }
    }

    /**
     * Send admin notification
     *
     * @return bool
     */
    public function send_admin_notification() {
        // Check if admin notification is enabled
        if (!$this->settings['admin_notification']) {
            return false;
        }
        
        // Get booking details
        $booking_id = $this->booking->get_id();
        $bookable_title = $this->booking->get_bookable_title();
        $start_date = $this->booking->get_formatted_start_date();
        $end_date = $this->booking->get_formatted_end_date();
        $status = $this->booking->get_status();
        $price = $this->booking->get_formatted_price();
        $customer_data = $this->booking->get_customer_data();
        $payment_data = $this->booking->get_payment_data();
        
        // Set email subject
        $subject = sprintf(
            __('New Booking: %s', 'aqualuxe'),
            $bookable_title
        );
        
        // Set email headers
        $headers = [
            'Content-Type: text/html; charset=UTF-8',
            'From: ' . $this->settings['from_name'] . ' <' . $this->settings['from_email'] . '>',
        ];
        
        // Build email content
        $content = $this->get_email_template('admin-notification');
        
        // Replace placeholders
        $content = str_replace('{site_name}', get_bloginfo('name'), $content);
        $content = str_replace('{booking_id}', $booking_id, $content);
        $content = str_replace('{bookable_title}', $bookable_title, $content);
        $content = str_replace('{start_date}', $start_date, $content);
        $content = str_replace('{end_date}', $end_date, $content);
        $content = str_replace('{status}', $status, $content);
        $content = str_replace('{price}', $price, $content);
        $content = str_replace('{customer_name}', $customer_data['name'] ?? '', $content);
        $content = str_replace('{customer_email}', $customer_data['email'] ?? '', $content);
        $content = str_replace('{customer_phone}', $customer_data['phone'] ?? '', $content);
        $content = str_replace('{customer_address}', $customer_data['address'] ?? '', $content);
        $content = str_replace('{customer_notes}', $customer_data['notes'] ?? '', $content);
        $content = str_replace('{payment_method}', $payment_data['method'] ?? '', $content);
        $content = str_replace('{payment_status}', $payment_data['status'] ?? '', $content);
        $content = str_replace('{transaction_id}', $payment_data['transaction_id'] ?? '', $content);
        $content = str_replace('{admin_url}', admin_url('post.php?post=' . $booking_id . '&action=edit'), $content);
        
        // Send email
        return wp_mail($this->settings['admin_email'], $subject, $content, $headers);
    }

    /**
     * Send customer notification
     *
     * @return bool
     */
    public function send_customer_notification() {
        // Check if customer notification is enabled
        if (!$this->settings['customer_notification']) {
            return false;
        }
        
        // Get customer email
        $customer_data = $this->booking->get_customer_data();
        $customer_email = $customer_data['email'] ?? '';
        
        if (!$customer_email) {
            return false;
        }
        
        // Get booking details
        $booking_id = $this->booking->get_id();
        $bookable_title = $this->booking->get_bookable_title();
        $start_date = $this->booking->get_formatted_start_date();
        $end_date = $this->booking->get_formatted_end_date();
        $status = $this->booking->get_status();
        $price = $this->booking->get_formatted_price();
        $payment_data = $this->booking->get_payment_data();
        
        // Set email subject
        $subject = sprintf(
            __('Your Booking: %s', 'aqualuxe'),
            $bookable_title
        );
        
        // Set email headers
        $headers = [
            'Content-Type: text/html; charset=UTF-8',
            'From: ' . $this->settings['from_name'] . ' <' . $this->settings['from_email'] . '>',
        ];
        
        // Build email content
        $content = $this->get_email_template('customer-notification');
        
        // Replace placeholders
        $content = str_replace('{site_name}', get_bloginfo('name'), $content);
        $content = str_replace('{customer_name}', $customer_data['name'] ?? '', $content);
        $content = str_replace('{booking_id}', $booking_id, $content);
        $content = str_replace('{bookable_title}', $bookable_title, $content);
        $content = str_replace('{start_date}', $start_date, $content);
        $content = str_replace('{end_date}', $end_date, $content);
        $content = str_replace('{status}', $status, $content);
        $content = str_replace('{price}', $price, $content);
        $content = str_replace('{payment_method}', $payment_data['method'] ?? '', $content);
        $content = str_replace('{payment_status}', $payment_data['status'] ?? '', $content);
        $content = str_replace('{transaction_id}', $payment_data['transaction_id'] ?? '', $content);
        $content = str_replace('{booking_url}', $this->booking->get_permalink(), $content);
        
        // Send email
        return wp_mail($customer_email, $subject, $content, $headers);
    }

    /**
     * Send status change notification
     *
     * @param string $old_status
     * @param string $new_status
     * @return bool
     */
    public function send_status_change_notification($old_status, $new_status) {
        // Check if customer notification is enabled
        if (!$this->settings['customer_notification']) {
            return false;
        }
        
        // Get customer email
        $customer_data = $this->booking->get_customer_data();
        $customer_email = $customer_data['email'] ?? '';
        
        if (!$customer_email) {
            return false;
        }
        
        // Get booking details
        $booking_id = $this->booking->get_id();
        $bookable_title = $this->booking->get_bookable_title();
        $start_date = $this->booking->get_formatted_start_date();
        $end_date = $this->booking->get_formatted_end_date();
        $price = $this->booking->get_formatted_price();
        
        // Set email subject
        $subject = sprintf(
            __('Booking Status Update: %s', 'aqualuxe'),
            $bookable_title
        );
        
        // Set email headers
        $headers = [
            'Content-Type: text/html; charset=UTF-8',
            'From: ' . $this->settings['from_name'] . ' <' . $this->settings['from_email'] . '>',
        ];
        
        // Build email content
        $content = $this->get_email_template('status-change');
        
        // Replace placeholders
        $content = str_replace('{site_name}', get_bloginfo('name'), $content);
        $content = str_replace('{customer_name}', $customer_data['name'] ?? '', $content);
        $content = str_replace('{booking_id}', $booking_id, $content);
        $content = str_replace('{bookable_title}', $bookable_title, $content);
        $content = str_replace('{start_date}', $start_date, $content);
        $content = str_replace('{end_date}', $end_date, $content);
        $content = str_replace('{old_status}', $old_status, $content);
        $content = str_replace('{new_status}', $new_status, $content);
        $content = str_replace('{price}', $price, $content);
        $content = str_replace('{booking_url}', $this->booking->get_permalink(), $content);
        
        // Send email
        return wp_mail($customer_email, $subject, $content, $headers);
    }

    /**
     * Send payment notification
     *
     * @param array $payment_data
     * @return bool
     */
    public function send_payment_notification($payment_data) {
        // Check if customer notification is enabled
        if (!$this->settings['customer_notification']) {
            return false;
        }
        
        // Get customer email
        $customer_data = $this->booking->get_customer_data();
        $customer_email = $customer_data['email'] ?? '';
        
        if (!$customer_email) {
            return false;
        }
        
        // Get booking details
        $booking_id = $this->booking->get_id();
        $bookable_title = $this->booking->get_bookable_title();
        $start_date = $this->booking->get_formatted_start_date();
        $end_date = $this->booking->get_formatted_end_date();
        $price = $this->booking->get_formatted_price();
        
        // Set email subject
        $subject = sprintf(
            __('Payment Confirmation: %s', 'aqualuxe'),
            $bookable_title
        );
        
        // Set email headers
        $headers = [
            'Content-Type: text/html; charset=UTF-8',
            'From: ' . $this->settings['from_name'] . ' <' . $this->settings['from_email'] . '>',
        ];
        
        // Build email content
        $content = $this->get_email_template('payment-notification');
        
        // Replace placeholders
        $content = str_replace('{site_name}', get_bloginfo('name'), $content);
        $content = str_replace('{customer_name}', $customer_data['name'] ?? '', $content);
        $content = str_replace('{booking_id}', $booking_id, $content);
        $content = str_replace('{bookable_title}', $bookable_title, $content);
        $content = str_replace('{start_date}', $start_date, $content);
        $content = str_replace('{end_date}', $end_date, $content);
        $content = str_replace('{price}', $price, $content);
        $content = str_replace('{payment_method}', $payment_data['method'] ?? '', $content);
        $content = str_replace('{payment_status}', $payment_data['status'] ?? '', $content);
        $content = str_replace('{transaction_id}', $payment_data['transaction_id'] ?? '', $content);
        $content = str_replace('{payment_date}', $payment_data['date'] ?? '', $content);
        $content = str_replace('{booking_url}', $this->booking->get_permalink(), $content);
        
        // Send email
        return wp_mail($customer_email, $subject, $content, $headers);
    }

    /**
     * Get email template
     *
     * @param string $template
     * @return string
     */
    private function get_email_template($template) {
        // Get module instance
        $theme = \AquaLuxe\Theme::get_instance();
        $module = $theme->get_active_modules()['bookings'] ?? null;
        
        if ($module) {
            // Check if template exists in theme
            $theme_template = get_stylesheet_directory() . '/aqualuxe/bookings/emails/' . $template . '.php';
            
            if (file_exists($theme_template)) {
                ob_start();
                include $theme_template;
                return ob_get_clean();
            }
            
            // Check if template exists in module
            $module_template = $module->get_path() . 'templates/emails/' . $template . '.php';
            
            if (file_exists($module_template)) {
                ob_start();
                include $module_template;
                return ob_get_clean();
            }
        }
        
        // Return default template
        return $this->get_default_template($template);
    }

    /**
     * Get default email template
     *
     * @param string $template
     * @return string
     */
    private function get_default_template($template) {
        switch ($template) {
            case 'admin-notification':
                return $this->get_admin_notification_template();
                
            case 'customer-notification':
                return $this->get_customer_notification_template();
                
            case 'status-change':
                return $this->get_status_change_template();
                
            case 'payment-notification':
                return $this->get_payment_notification_template();
                
            default:
                return '';
        }
    }

    /**
     * Get admin notification template
     *
     * @return string
     */
    private function get_admin_notification_template() {
        return '
        <!DOCTYPE html>
        <html>
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
            <title>New Booking</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    line-height: 1.6;
                    color: #333;
                    background-color: #f4f4f4;
                    margin: 0;
                    padding: 0;
                }
                .container {
                    max-width: 600px;
                    margin: 0 auto;
                    padding: 20px;
                    background-color: #fff;
                    border: 1px solid #ddd;
                    border-radius: 5px;
                }
                .header {
                    background-color: #0073aa;
                    color: #fff;
                    padding: 10px 20px;
                    border-radius: 5px 5px 0 0;
                    margin: -20px -20px 20px;
                }
                .footer {
                    margin-top: 30px;
                    padding-top: 10px;
                    border-top: 1px solid #ddd;
                    font-size: 12px;
                    color: #777;
                }
                h1 {
                    margin: 0;
                    font-size: 24px;
                }
                h2 {
                    font-size: 18px;
                    margin-top: 30px;
                    margin-bottom: 10px;
                    padding-bottom: 5px;
                    border-bottom: 1px solid #ddd;
                }
                table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-bottom: 20px;
                }
                table th, table td {
                    padding: 10px;
                    text-align: left;
                    border-bottom: 1px solid #ddd;
                }
                table th {
                    background-color: #f9f9f9;
                }
                .button {
                    display: inline-block;
                    background-color: #0073aa;
                    color: #fff;
                    padding: 10px 20px;
                    text-decoration: none;
                    border-radius: 3px;
                    margin-top: 20px;
                }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h1>New Booking</h1>
                </div>
                
                <p>A new booking has been made on {site_name}.</p>
                
                <h2>Booking Details</h2>
                <table>
                    <tr>
                        <th>Booking ID</th>
                        <td>{booking_id}</td>
                    </tr>
                    <tr>
                        <th>Item</th>
                        <td>{bookable_title}</td>
                    </tr>
                    <tr>
                        <th>Start Date</th>
                        <td>{start_date}</td>
                    </tr>
                    <tr>
                        <th>End Date</th>
                        <td>{end_date}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>{status}</td>
                    </tr>
                    <tr>
                        <th>Price</th>
                        <td>{price}</td>
                    </tr>
                </table>
                
                <h2>Customer Details</h2>
                <table>
                    <tr>
                        <th>Name</th>
                        <td>{customer_name}</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>{customer_email}</td>
                    </tr>
                    <tr>
                        <th>Phone</th>
                        <td>{customer_phone}</td>
                    </tr>
                    <tr>
                        <th>Address</th>
                        <td>{customer_address}</td>
                    </tr>
                    <tr>
                        <th>Notes</th>
                        <td>{customer_notes}</td>
                    </tr>
                </table>
                
                <h2>Payment Details</h2>
                <table>
                    <tr>
                        <th>Method</th>
                        <td>{payment_method}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>{payment_status}</td>
                    </tr>
                    <tr>
                        <th>Transaction ID</th>
                        <td>{transaction_id}</td>
                    </tr>
                </table>
                
                <a href="{admin_url}" class="button">View Booking</a>
                
                <div class="footer">
                    <p>This email was sent from {site_name}.</p>
                </div>
            </div>
        </body>
        </html>
        ';
    }

    /**
     * Get customer notification template
     *
     * @return string
     */
    private function get_customer_notification_template() {
        return '
        <!DOCTYPE html>
        <html>
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
            <title>Your Booking Confirmation</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    line-height: 1.6;
                    color: #333;
                    background-color: #f4f4f4;
                    margin: 0;
                    padding: 0;
                }
                .container {
                    max-width: 600px;
                    margin: 0 auto;
                    padding: 20px;
                    background-color: #fff;
                    border: 1px solid #ddd;
                    border-radius: 5px;
                }
                .header {
                    background-color: #0073aa;
                    color: #fff;
                    padding: 10px 20px;
                    border-radius: 5px 5px 0 0;
                    margin: -20px -20px 20px;
                }
                .footer {
                    margin-top: 30px;
                    padding-top: 10px;
                    border-top: 1px solid #ddd;
                    font-size: 12px;
                    color: #777;
                }
                h1 {
                    margin: 0;
                    font-size: 24px;
                }
                h2 {
                    font-size: 18px;
                    margin-top: 30px;
                    margin-bottom: 10px;
                    padding-bottom: 5px;
                    border-bottom: 1px solid #ddd;
                }
                table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-bottom: 20px;
                }
                table th, table td {
                    padding: 10px;
                    text-align: left;
                    border-bottom: 1px solid #ddd;
                }
                table th {
                    background-color: #f9f9f9;
                }
                .button {
                    display: inline-block;
                    background-color: #0073aa;
                    color: #fff;
                    padding: 10px 20px;
                    text-decoration: none;
                    border-radius: 3px;
                    margin-top: 20px;
                }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h1>Your Booking Confirmation</h1>
                </div>
                
                <p>Dear {customer_name},</p>
                
                <p>Thank you for your booking with {site_name}. Your booking has been received and is now being processed.</p>
                
                <h2>Booking Details</h2>
                <table>
                    <tr>
                        <th>Booking ID</th>
                        <td>{booking_id}</td>
                    </tr>
                    <tr>
                        <th>Item</th>
                        <td>{bookable_title}</td>
                    </tr>
                    <tr>
                        <th>Start Date</th>
                        <td>{start_date}</td>
                    </tr>
                    <tr>
                        <th>End Date</th>
                        <td>{end_date}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>{status}</td>
                    </tr>
                    <tr>
                        <th>Price</th>
                        <td>{price}</td>
                    </tr>
                </table>
                
                <h2>Payment Details</h2>
                <table>
                    <tr>
                        <th>Method</th>
                        <td>{payment_method}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>{payment_status}</td>
                    </tr>
                    <tr>
                        <th>Transaction ID</th>
                        <td>{transaction_id}</td>
                    </tr>
                </table>
                
                <p>If you have any questions or need to make changes to your booking, please contact us.</p>
                
                <a href="{booking_url}" class="button">View Booking</a>
                
                <div class="footer">
                    <p>This email was sent from {site_name}.</p>
                </div>
            </div>
        </body>
        </html>
        ';
    }

    /**
     * Get status change template
     *
     * @return string
     */
    private function get_status_change_template() {
        return '
        <!DOCTYPE html>
        <html>
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
            <title>Booking Status Update</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    line-height: 1.6;
                    color: #333;
                    background-color: #f4f4f4;
                    margin: 0;
                    padding: 0;
                }
                .container {
                    max-width: 600px;
                    margin: 0 auto;
                    padding: 20px;
                    background-color: #fff;
                    border: 1px solid #ddd;
                    border-radius: 5px;
                }
                .header {
                    background-color: #0073aa;
                    color: #fff;
                    padding: 10px 20px;
                    border-radius: 5px 5px 0 0;
                    margin: -20px -20px 20px;
                }
                .footer {
                    margin-top: 30px;
                    padding-top: 10px;
                    border-top: 1px solid #ddd;
                    font-size: 12px;
                    color: #777;
                }
                h1 {
                    margin: 0;
                    font-size: 24px;
                }
                h2 {
                    font-size: 18px;
                    margin-top: 30px;
                    margin-bottom: 10px;
                    padding-bottom: 5px;
                    border-bottom: 1px solid #ddd;
                }
                table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-bottom: 20px;
                }
                table th, table td {
                    padding: 10px;
                    text-align: left;
                    border-bottom: 1px solid #ddd;
                }
                table th {
                    background-color: #f9f9f9;
                }
                .button {
                    display: inline-block;
                    background-color: #0073aa;
                    color: #fff;
                    padding: 10px 20px;
                    text-decoration: none;
                    border-radius: 3px;
                    margin-top: 20px;
                }
                .status-update {
                    background-color: #f9f9f9;
                    padding: 15px;
                    border-left: 4px solid #0073aa;
                    margin-bottom: 20px;
                }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h1>Booking Status Update</h1>
                </div>
                
                <p>Dear {customer_name},</p>
                
                <p>The status of your booking has been updated.</p>
                
                <div class="status-update">
                    <p>Your booking status has changed from <strong>{old_status}</strong> to <strong>{new_status}</strong>.</p>
                </div>
                
                <h2>Booking Details</h2>
                <table>
                    <tr>
                        <th>Booking ID</th>
                        <td>{booking_id}</td>
                    </tr>
                    <tr>
                        <th>Item</th>
                        <td>{bookable_title}</td>
                    </tr>
                    <tr>
                        <th>Start Date</th>
                        <td>{start_date}</td>
                    </tr>
                    <tr>
                        <th>End Date</th>
                        <td>{end_date}</td>
                    </tr>
                    <tr>
                        <th>Price</th>
                        <td>{price}</td>
                    </tr>
                </table>
                
                <p>If you have any questions about this status change, please contact us.</p>
                
                <a href="{booking_url}" class="button">View Booking</a>
                
                <div class="footer">
                    <p>This email was sent from {site_name}.</p>
                </div>
            </div>
        </body>
        </html>
        ';
    }

    /**
     * Get payment notification template
     *
     * @return string
     */
    private function get_payment_notification_template() {
        return '
        <!DOCTYPE html>
        <html>
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
            <title>Payment Confirmation</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    line-height: 1.6;
                    color: #333;
                    background-color: #f4f4f4;
                    margin: 0;
                    padding: 0;
                }
                .container {
                    max-width: 600px;
                    margin: 0 auto;
                    padding: 20px;
                    background-color: #fff;
                    border: 1px solid #ddd;
                    border-radius: 5px;
                }
                .header {
                    background-color: #0073aa;
                    color: #fff;
                    padding: 10px 20px;
                    border-radius: 5px 5px 0 0;
                    margin: -20px -20px 20px;
                }
                .footer {
                    margin-top: 30px;
                    padding-top: 10px;
                    border-top: 1px solid #ddd;
                    font-size: 12px;
                    color: #777;
                }
                h1 {
                    margin: 0;
                    font-size: 24px;
                }
                h2 {
                    font-size: 18px;
                    margin-top: 30px;
                    margin-bottom: 10px;
                    padding-bottom: 5px;
                    border-bottom: 1px solid #ddd;
                }
                table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-bottom: 20px;
                }
                table th, table td {
                    padding: 10px;
                    text-align: left;
                    border-bottom: 1px solid #ddd;
                }
                table th {
                    background-color: #f9f9f9;
                }
                .button {
                    display: inline-block;
                    background-color: #0073aa;
                    color: #fff;
                    padding: 10px 20px;
                    text-decoration: none;
                    border-radius: 3px;
                    margin-top: 20px;
                }
                .payment-confirmation {
                    background-color: #f9f9f9;
                    padding: 15px;
                    border-left: 4px solid #4caf50;
                    margin-bottom: 20px;
                }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h1>Payment Confirmation</h1>
                </div>
                
                <p>Dear {customer_name},</p>
                
                <div class="payment-confirmation">
                    <p>Your payment for booking #{booking_id} has been processed successfully.</p>
                </div>
                
                <h2>Payment Details</h2>
                <table>
                    <tr>
                        <th>Amount</th>
                        <td>{price}</td>
                    </tr>
                    <tr>
                        <th>Method</th>
                        <td>{payment_method}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>{payment_status}</td>
                    </tr>
                    <tr>
                        <th>Transaction ID</th>
                        <td>{transaction_id}</td>
                    </tr>
                    <tr>
                        <th>Date</th>
                        <td>{payment_date}</td>
                    </tr>
                </table>
                
                <h2>Booking Details</h2>
                <table>
                    <tr>
                        <th>Booking ID</th>
                        <td>{booking_id}</td>
                    </tr>
                    <tr>
                        <th>Item</th>
                        <td>{bookable_title}</td>
                    </tr>
                    <tr>
                        <th>Start Date</th>
                        <td>{start_date}</td>
                    </tr>
                    <tr>
                        <th>End Date</th>
                        <td>{end_date}</td>
                    </tr>
                </table>
                
                <p>Thank you for your payment. If you have any questions, please contact us.</p>
                
                <a href="{booking_url}" class="button">View Booking</a>
                
                <div class="footer">
                    <p>This email was sent from {site_name}.</p>
                </div>
            </div>
        </body>
        </html>
        ';
    }
}