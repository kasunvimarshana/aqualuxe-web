<?php
/**
 * Notifications Class
 *
 * @package AquaLuxe
 * @subpackage Modules\Bookings
 * @since 1.0.0
 */

namespace AquaLuxe\Modules\Bookings;

/**
 * Notifications Class
 * 
 * This class handles booking notifications.
 */
class Notifications {
    /**
     * Instance of this class
     *
     * @var Notifications
     */
    private static $instance = null;

    /**
     * Get the singleton instance
     *
     * @return Notifications
     */
    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    private function __construct() {
        $this->init_hooks();
    }

    /**
     * Initialize hooks
     *
     * @return void
     */
    private function init_hooks() {
        // Booking notifications
        add_action( 'aqualuxe_booking_created', [ $this, 'send_new_booking_notifications' ] );
        add_action( 'aqualuxe_booking_status_updated', [ $this, 'send_status_update_notifications' ], 10, 2 );
        add_action( 'aqualuxe_booking_cancelled', [ $this, 'send_cancellation_notifications' ] );
        
        // Reminder notifications
        add_action( 'aqualuxe_booking_reminder', [ $this, 'send_reminder_notification' ] );
        
        // Schedule reminders
        add_action( 'aqualuxe_booking_created', [ $this, 'schedule_reminder' ] );
        add_action( 'aqualuxe_booking_status_updated', [ $this, 'update_reminder' ], 10, 2 );
        add_action( 'aqualuxe_booking_cancelled', [ $this, 'cancel_reminder' ] );
    }

    /**
     * Send new booking notifications
     *
     * @param int $booking_id Booking ID.
     * @return void
     */
    public function send_new_booking_notifications( $booking_id ) {
        // Get settings
        $settings = get_option( 'aqualuxe_bookings_settings', [] );
        $admin_notification = isset( $settings['admin_notification'] ) ? $settings['admin_notification'] : true;
        $customer_notification = isset( $settings['customer_notification'] ) ? $settings['customer_notification'] : true;
        
        // Get booking
        $booking = new Booking( $booking_id );
        
        // Send admin notification
        if ( $admin_notification ) {
            $this->send_admin_notification( $booking, 'new' );
        }
        
        // Send customer notification
        if ( $customer_notification ) {
            $this->send_customer_notification( $booking, 'new' );
        }
    }

    /**
     * Send status update notifications
     *
     * @param int    $booking_id Booking ID.
     * @param string $status New status.
     * @return void
     */
    public function send_status_update_notifications( $booking_id, $status ) {
        // Get settings
        $settings = get_option( 'aqualuxe_bookings_settings', [] );
        $admin_notification = isset( $settings['admin_notification'] ) ? $settings['admin_notification'] : true;
        $customer_notification = isset( $settings['customer_notification'] ) ? $settings['customer_notification'] : true;
        
        // Get booking
        $booking = new Booking( $booking_id );
        
        // Send admin notification
        if ( $admin_notification ) {
            $this->send_admin_notification( $booking, 'status_update' );
        }
        
        // Send customer notification
        if ( $customer_notification ) {
            $this->send_customer_notification( $booking, 'status_update' );
        }
    }

    /**
     * Send cancellation notifications
     *
     * @param int $booking_id Booking ID.
     * @return void
     */
    public function send_cancellation_notifications( $booking_id ) {
        // Get settings
        $settings = get_option( 'aqualuxe_bookings_settings', [] );
        $admin_notification = isset( $settings['admin_notification'] ) ? $settings['admin_notification'] : true;
        $customer_notification = isset( $settings['customer_notification'] ) ? $settings['customer_notification'] : true;
        
        // Get booking
        $booking = new Booking( $booking_id );
        
        // Send admin notification
        if ( $admin_notification ) {
            $this->send_admin_notification( $booking, 'cancelled' );
        }
        
        // Send customer notification
        if ( $customer_notification ) {
            $this->send_customer_notification( $booking, 'cancelled' );
        }
    }

    /**
     * Send reminder notification
     *
     * @param int $booking_id Booking ID.
     * @return void
     */
    public function send_reminder_notification( $booking_id ) {
        // Get settings
        $settings = get_option( 'aqualuxe_bookings_settings', [] );
        $customer_notification = isset( $settings['customer_notification'] ) ? $settings['customer_notification'] : true;
        
        // Get booking
        $booking = new Booking( $booking_id );
        
        // Check if booking is still confirmed
        if ( $booking->get_status() !== 'confirmed' ) {
            return;
        }
        
        // Send customer notification
        if ( $customer_notification ) {
            $this->send_customer_notification( $booking, 'reminder' );
        }
    }

    /**
     * Schedule reminder
     *
     * @param int $booking_id Booking ID.
     * @return void
     */
    public function schedule_reminder( $booking_id ) {
        // Get settings
        $settings = get_option( 'aqualuxe_bookings_settings', [] );
        $reminder_notification = isset( $settings['reminder_notification'] ) ? $settings['reminder_notification'] : true;
        $reminder_time = isset( $settings['reminder_time'] ) ? $settings['reminder_time'] : 24;
        
        // Check if reminders are enabled
        if ( ! $reminder_notification ) {
            return;
        }
        
        // Get booking
        $booking = new Booking( $booking_id );
        
        // Calculate reminder time
        $booking_datetime = strtotime( $booking->get_date() . ' ' . $booking->get_time() );
        $reminder_datetime = $booking_datetime - ( $reminder_time * HOUR_IN_SECONDS );
        
        // Schedule reminder
        if ( $reminder_datetime > time() ) {
            wp_schedule_single_event( $reminder_datetime, 'aqualuxe_booking_reminder', [ $booking_id ] );
        }
    }

    /**
     * Update reminder
     *
     * @param int    $booking_id Booking ID.
     * @param string $status New status.
     * @return void
     */
    public function update_reminder( $booking_id, $status ) {
        // Cancel existing reminder
        $this->cancel_reminder( $booking_id );
        
        // Schedule new reminder if status is confirmed
        if ( $status === 'confirmed' ) {
            $this->schedule_reminder( $booking_id );
        }
    }

    /**
     * Cancel reminder
     *
     * @param int $booking_id Booking ID.
     * @return void
     */
    public function cancel_reminder( $booking_id ) {
        wp_clear_scheduled_hook( 'aqualuxe_booking_reminder', [ $booking_id ] );
    }

    /**
     * Send admin notification
     *
     * @param Booking $booking Booking object.
     * @param string  $type Notification type.
     * @return void
     */
    private function send_admin_notification( $booking, $type ) {
        // Get admin email
        $admin_email = get_option( 'admin_email' );
        
        // Get service
        $service = new Service( $booking->get_service_id() );
        
        // Set subject and message based on notification type
        switch ( $type ) {
            case 'new':
                $subject = sprintf( __( 'New Booking: %s', 'aqualuxe' ), $service->get_title() );
                $message = $this->get_admin_new_booking_message( $booking, $service );
                break;
                
            case 'status_update':
                $subject = sprintf( __( 'Booking Status Updated: %s', 'aqualuxe' ), $service->get_title() );
                $message = $this->get_admin_status_update_message( $booking, $service );
                break;
                
            case 'cancelled':
                $subject = sprintf( __( 'Booking Cancelled: %s', 'aqualuxe' ), $service->get_title() );
                $message = $this->get_admin_cancellation_message( $booking, $service );
                break;
                
            default:
                return;
        }
        
        // Set headers
        $headers = [
            'Content-Type: text/html; charset=UTF-8',
        ];
        
        // Send email
        wp_mail( $admin_email, $subject, $message, $headers );
    }

    /**
     * Send customer notification
     *
     * @param Booking $booking Booking object.
     * @param string  $type Notification type.
     * @return void
     */
    private function send_customer_notification( $booking, $type ) {
        // Get customer email
        $customer_email = $booking->get_customer_email();
        
        // Get service
        $service = new Service( $booking->get_service_id() );
        
        // Get site name
        $site_name = get_bloginfo( 'name' );
        
        // Set subject and message based on notification type
        switch ( $type ) {
            case 'new':
                $subject = sprintf( __( '%s: Your Booking Confirmation', 'aqualuxe' ), $site_name );
                $message = $this->get_customer_new_booking_message( $booking, $service );
                break;
                
            case 'status_update':
                $subject = sprintf( __( '%s: Your Booking Status Update', 'aqualuxe' ), $site_name );
                $message = $this->get_customer_status_update_message( $booking, $service );
                break;
                
            case 'cancelled':
                $subject = sprintf( __( '%s: Your Booking Cancellation', 'aqualuxe' ), $site_name );
                $message = $this->get_customer_cancellation_message( $booking, $service );
                break;
                
            case 'reminder':
                $subject = sprintf( __( '%s: Your Upcoming Booking Reminder', 'aqualuxe' ), $site_name );
                $message = $this->get_customer_reminder_message( $booking, $service );
                break;
                
            default:
                return;
        }
        
        // Set headers
        $headers = [
            'Content-Type: text/html; charset=UTF-8',
        ];
        
        // Send email
        wp_mail( $customer_email, $subject, $message, $headers );
    }

    /**
     * Get admin new booking message
     *
     * @param Booking $booking Booking object.
     * @param Service $service Service object.
     * @return string
     */
    private function get_admin_new_booking_message( $booking, $service ) {
        ob_start();
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title><?php esc_html_e( 'New Booking', 'aqualuxe' ); ?></title>
        </head>
        <body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
            <div style="background-color: #f8f8f8; padding: 20px; border-radius: 5px; border-left: 4px solid #0073aa;">
                <h1 style="color: #0073aa; margin-top: 0;"><?php esc_html_e( 'New Booking', 'aqualuxe' ); ?></h1>
                <p><?php esc_html_e( 'A new booking has been made on your website.', 'aqualuxe' ); ?></p>
                
                <h2 style="color: #0073aa; border-bottom: 1px solid #eee; padding-bottom: 10px;"><?php esc_html_e( 'Booking Details', 'aqualuxe' ); ?></h2>
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="padding: 8px 0; border-bottom: 1px solid #eee; width: 40%;"><strong><?php esc_html_e( 'Service', 'aqualuxe' ); ?>:</strong></td>
                        <td style="padding: 8px 0; border-bottom: 1px solid #eee;"><?php echo esc_html( $service->get_title() ); ?></td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; border-bottom: 1px solid #eee;"><strong><?php esc_html_e( 'Date', 'aqualuxe' ); ?>:</strong></td>
                        <td style="padding: 8px 0; border-bottom: 1px solid #eee;"><?php echo esc_html( $booking->get_formatted_date() ); ?></td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; border-bottom: 1px solid #eee;"><strong><?php esc_html_e( 'Time', 'aqualuxe' ); ?>:</strong></td>
                        <td style="padding: 8px 0; border-bottom: 1px solid #eee;"><?php echo esc_html( $booking->get_formatted_time() ); ?></td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; border-bottom: 1px solid #eee;"><strong><?php esc_html_e( 'Duration', 'aqualuxe' ); ?>:</strong></td>
                        <td style="padding: 8px 0; border-bottom: 1px solid #eee;"><?php echo esc_html( $booking->get_formatted_duration() ); ?></td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; border-bottom: 1px solid #eee;"><strong><?php esc_html_e( 'Status', 'aqualuxe' ); ?>:</strong></td>
                        <td style="padding: 8px 0; border-bottom: 1px solid #eee;"><?php echo esc_html( $booking->get_formatted_status() ); ?></td>
                    </tr>
                </table>
                
                <h2 style="color: #0073aa; border-bottom: 1px solid #eee; padding-bottom: 10px; margin-top: 20px;"><?php esc_html_e( 'Customer Details', 'aqualuxe' ); ?></h2>
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="padding: 8px 0; border-bottom: 1px solid #eee; width: 40%;"><strong><?php esc_html_e( 'Name', 'aqualuxe' ); ?>:</strong></td>
                        <td style="padding: 8px 0; border-bottom: 1px solid #eee;"><?php echo esc_html( $booking->get_customer_name() ); ?></td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; border-bottom: 1px solid #eee;"><strong><?php esc_html_e( 'Email', 'aqualuxe' ); ?>:</strong></td>
                        <td style="padding: 8px 0; border-bottom: 1px solid #eee;"><?php echo esc_html( $booking->get_customer_email() ); ?></td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; border-bottom: 1px solid #eee;"><strong><?php esc_html_e( 'Phone', 'aqualuxe' ); ?>:</strong></td>
                        <td style="padding: 8px 0; border-bottom: 1px solid #eee;"><?php echo esc_html( $booking->get_customer_phone() ); ?></td>
                    </tr>
                    <?php if ( $booking->get_customer_address() ) : ?>
                    <tr>
                        <td style="padding: 8px 0; border-bottom: 1px solid #eee;"><strong><?php esc_html_e( 'Address', 'aqualuxe' ); ?>:</strong></td>
                        <td style="padding: 8px 0; border-bottom: 1px solid #eee;"><?php echo esc_html( $booking->get_customer_address() ); ?></td>
                    </tr>
                    <?php endif; ?>
                    <?php if ( $booking->get_customer_city() || $booking->get_customer_state() || $booking->get_customer_zip() ) : ?>
                    <tr>
                        <td style="padding: 8px 0; border-bottom: 1px solid #eee;"><strong><?php esc_html_e( 'City/State/ZIP', 'aqualuxe' ); ?>:</strong></td>
                        <td style="padding: 8px 0; border-bottom: 1px solid #eee;">
                            <?php
                            $location = [];
                            if ( $booking->get_customer_city() ) {
                                $location[] = $booking->get_customer_city();
                            }
                            if ( $booking->get_customer_state() ) {
                                $location[] = $booking->get_customer_state();
                            }
                            if ( $booking->get_customer_zip() ) {
                                $location[] = $booking->get_customer_zip();
                            }
                            echo esc_html( implode( ', ', $location ) );
                            ?>
                        </td>
                    </tr>
                    <?php endif; ?>
                    <?php if ( $booking->get_customer_country() ) : ?>
                    <tr>
                        <td style="padding: 8px 0; border-bottom: 1px solid #eee;"><strong><?php esc_html_e( 'Country', 'aqualuxe' ); ?>:</strong></td>
                        <td style="padding: 8px 0; border-bottom: 1px solid #eee;"><?php echo esc_html( $booking->get_customer_country() ); ?></td>
                    </tr>
                    <?php endif; ?>
                    <?php if ( $booking->get_customer_notes() ) : ?>
                    <tr>
                        <td style="padding: 8px 0; border-bottom: 1px solid #eee;"><strong><?php esc_html_e( 'Notes', 'aqualuxe' ); ?>:</strong></td>
                        <td style="padding: 8px 0; border-bottom: 1px solid #eee;"><?php echo esc_html( $booking->get_customer_notes() ); ?></td>
                    </tr>
                    <?php endif; ?>
                </table>
                
                <div style="margin-top: 30px; text-align: center;">
                    <a href="<?php echo esc_url( $booking->get_url() ); ?>" style="background-color: #0073aa; color: #fff; padding: 10px 20px; text-decoration: none; border-radius: 3px; display: inline-block;"><?php esc_html_e( 'View Booking', 'aqualuxe' ); ?></a>
                </div>
            </div>
        </body>
        </html>
        <?php
        return ob_get_clean();
    }

    /**
     * Get admin status update message
     *
     * @param Booking $booking Booking object.
     * @param Service $service Service object.
     * @return string
     */
    private function get_admin_status_update_message( $booking, $service ) {
        ob_start();
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title><?php esc_html_e( 'Booking Status Updated', 'aqualuxe' ); ?></title>
        </head>
        <body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
            <div style="background-color: #f8f8f8; padding: 20px; border-radius: 5px; border-left: 4px solid #0073aa;">
                <h1 style="color: #0073aa; margin-top: 0;"><?php esc_html_e( 'Booking Status Updated', 'aqualuxe' ); ?></h1>
                <p><?php esc_html_e( 'A booking status has been updated on your website.', 'aqualuxe' ); ?></p>
                
                <h2 style="color: #0073aa; border-bottom: 1px solid #eee; padding-bottom: 10px;"><?php esc_html_e( 'Booking Details', 'aqualuxe' ); ?></h2>
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="padding: 8px 0; border-bottom: 1px solid #eee; width: 40%;"><strong><?php esc_html_e( 'Service', 'aqualuxe' ); ?>:</strong></td>
                        <td style="padding: 8px 0; border-bottom: 1px solid #eee;"><?php echo esc_html( $service->get_title() ); ?></td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; border-bottom: 1px solid #eee;"><strong><?php esc_html_e( 'Date', 'aqualuxe' ); ?>:</strong></td>
                        <td style="padding: 8px 0; border-bottom: 1px solid #eee;"><?php echo esc_html( $booking->get_formatted_date() ); ?></td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; border-bottom: 1px solid #eee;"><strong><?php esc_html_e( 'Time', 'aqualuxe' ); ?>:</strong></td>
                        <td style="padding: 8px 0; border-bottom: 1px solid #eee;"><?php echo esc_html( $booking->get_formatted_time() ); ?></td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; border-bottom: 1px solid #eee;"><strong><?php esc_html_e( 'Duration', 'aqualuxe' ); ?>:</strong></td>
                        <td style="padding: 8px 0; border-bottom: 1px solid #eee;"><?php echo esc_html( $booking->get_formatted_duration() ); ?></td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; border-bottom: 1px solid #eee;"><strong><?php esc_html_e( 'Status', 'aqualuxe' ); ?>:</strong></td>
                        <td style="padding: 8px 0; border-bottom: 1px solid #eee;"><strong><?php echo esc_html( $booking->get_formatted_status() ); ?></strong></td>
                    </tr>
                </table>
                
                <h2 style="color: #0073aa; border-bottom: 1px solid #eee; padding-bottom: 10px; margin-top: 20px;"><?php esc_html_e( 'Customer Details', 'aqualuxe' ); ?></h2>
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="padding: 8px 0; border-bottom: 1px solid #eee; width: 40%;"><strong><?php esc_html_e( 'Name', 'aqualuxe' ); ?>:</strong></td>
                        <td style="padding: 8px 0; border-bottom: 1px solid #eee;"><?php echo esc_html( $booking->get_customer_name() ); ?></td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; border-bottom: 1px solid #eee;"><strong><?php esc_html_e( 'Email', 'aqualuxe' ); ?>:</strong></td>
                        <td style="padding: 8px 0; border-bottom: 1px solid #eee;"><?php echo esc_html( $booking->get_customer_email() ); ?></td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; border-bottom: 1px solid #eee;"><strong><?php esc_html_e( 'Phone', 'aqualuxe' ); ?>:</strong></td>
                        <td style="padding: 8px 0; border-bottom: 1px solid #eee;"><?php echo esc_html( $booking->get_customer_phone() ); ?></td>
                    </tr>
                </table>
                
                <div style="margin-top: 30px; text-align: center;">
                    <a href="<?php echo esc_url( $booking->get_url() ); ?>" style="background-color: #0073aa; color: #fff; padding: 10px 20px; text-decoration: none; border-radius: 3px; display: inline-block;"><?php esc_html_e( 'View Booking', 'aqualuxe' ); ?></a>
                </div>
            </div>
        </body>
        </html>
        <?php
        return ob_get_clean();
    }

    /**
     * Get admin cancellation message
     *
     * @param Booking $booking Booking object.
     * @param Service $service Service object.
     * @return string
     */
    private function get_admin_cancellation_message( $booking, $service ) {
        ob_start();
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title><?php esc_html_e( 'Booking Cancelled', 'aqualuxe' ); ?></title>
        </head>
        <body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
            <div style="background-color: #f8f8f8; padding: 20px; border-radius: 5px; border-left: 4px solid #dc3232;">
                <h1 style="color: #dc3232; margin-top: 0;"><?php esc_html_e( 'Booking Cancelled', 'aqualuxe' ); ?></h1>
                <p><?php esc_html_e( 'A booking has been cancelled on your website.', 'aqualuxe' ); ?></p>
                
                <h2 style="color: #dc3232; border-bottom: 1px solid #eee; padding-bottom: 10px;"><?php esc_html_e( 'Booking Details', 'aqualuxe' ); ?></h2>
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="padding: 8px 0; border-bottom: 1px solid #eee; width: 40%;"><strong><?php esc_html_e( 'Service', 'aqualuxe' ); ?>:</strong></td>
                        <td style="padding: 8px 0; border-bottom: 1px solid #eee;"><?php echo esc_html( $service->get_title() ); ?></td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; border-bottom: 1px solid #eee;"><strong><?php esc_html_e( 'Date', 'aqualuxe' ); ?>:</strong></td>
                        <td style="padding: 8px 0; border-bottom: 1px solid #eee;"><?php echo esc_html( $booking->get_formatted_date() ); ?></td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; border-bottom: 1px solid #eee;"><strong><?php esc_html_e( 'Time', 'aqualuxe' ); ?>:</strong></td>
                        <td style="padding: 8px 0; border-bottom: 1px solid #eee;"><?php echo esc_html( $booking->get_formatted_time() ); ?></td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; border-bottom: 1px solid #eee;"><strong><?php esc_html_e( 'Duration', 'aqualuxe' ); ?>:</strong></td>
                        <td style="padding: 8px 0; border-bottom: 1px solid #eee;"><?php echo esc_html( $booking->get_formatted_duration() ); ?></td>
                    </tr>
                </table>
                
                <h2 style="color: #dc3232; border-bottom: 1px solid #eee; padding-bottom: 10px; margin-top: 20px;"><?php esc_html_e( 'Customer Details', 'aqualuxe' ); ?></h2>
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="padding: 8px 0; border-bottom: 1px solid #eee; width: 40%;"><strong><?php esc_html_e( 'Name', 'aqualuxe' ); ?>:</strong></td>
                        <td style="padding: 8px 0; border-bottom: 1px solid #eee;"><?php echo esc_html( $booking->get_customer_name() ); ?></td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; border-bottom: 1px solid #eee;"><strong><?php esc_html_e( 'Email', 'aqualuxe' ); ?>:</strong></td>
                        <td style="padding: 8px 0; border-bottom: 1px solid #eee;"><?php echo esc_html( $booking->get_customer_email() ); ?></td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; border-bottom: 1px solid #eee;"><strong><?php esc_html_e( 'Phone', 'aqualuxe' ); ?>:</strong></td>
                        <td style="padding: 8px 0; border-bottom: 1px solid #eee;"><?php echo esc_html( $booking->get_customer_phone() ); ?></td>
                    </tr>
                </table>
            </div>
        </body>
        </html>
        <?php
        return ob_get_clean();
    }

    /**
     * Get customer new booking message
     *
     * @param Booking $booking Booking object.
     * @param Service $service Service object.
     * @return string
     */
    private function get_customer_new_booking_message( $booking, $service ) {
        // Get site info
        $site_name = get_bloginfo( 'name' );
        $site_url = get_bloginfo( 'url' );
        
        ob_start();
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title><?php esc_html_e( 'Booking Confirmation', 'aqualuxe' ); ?></title>
        </head>
        <body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
            <div style="text-align: center; margin-bottom: 20px;">
                <h1 style="color: #0073aa;"><?php echo esc_html( $site_name ); ?></h1>
            </div>
            
            <div style="background-color: #f8f8f8; padding: 20px; border-radius: 5px; border-left: 4px solid #0073aa;">
                <h2 style="color: #0073aa; margin-top: 0;"><?php esc_html_e( 'Booking Confirmation', 'aqualuxe' ); ?></h2>
                <p><?php printf( esc_html__( 'Dear %s,', 'aqualuxe' ), esc_html( $booking->get_customer_name() ) ); ?></p>
                <p><?php esc_html_e( 'Thank you for booking with us. Your booking has been received and is now pending confirmation. We will notify you once your booking has been confirmed.', 'aqualuxe' ); ?></p>
                
                <h3 style="color: #0073aa; border-bottom: 1px solid #eee; padding-bottom: 10px;"><?php esc_html_e( 'Booking Details', 'aqualuxe' ); ?></h3>
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="padding: 8px 0; border-bottom: 1px solid #eee; width: 40%;"><strong><?php esc_html_e( 'Service', 'aqualuxe' ); ?>:</strong></td>
                        <td style="padding: 8px 0; border-bottom: 1px solid #eee;"><?php echo esc_html( $service->get_title() ); ?></td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; border-bottom: 1px solid #eee;"><strong><?php esc_html_e( 'Date', 'aqualuxe' ); ?>:</strong></td>
                        <td style="padding: 8px 0; border-bottom: 1px solid #eee;"><?php echo esc_html( $booking->get_formatted_date() ); ?></td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; border-bottom: 1px solid #eee;"><strong><?php esc_html_e( 'Time', 'aqualuxe' ); ?>:</strong></td>
                        <td style="padding: 8px 0; border-bottom: 1px solid #eee;"><?php echo esc_html( $booking->get_formatted_time() ); ?></td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; border-bottom: 1px solid #eee;"><strong><?php esc_html_e( 'Duration', 'aqualuxe' ); ?>:</strong></td>
                        <td style="padding: 8px 0; border-bottom: 1px solid #eee;"><?php echo esc_html( $booking->get_formatted_duration() ); ?></td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; border-bottom: 1px solid #eee;"><strong><?php esc_html_e( 'Status', 'aqualuxe' ); ?>:</strong></td>
                        <td style="padding: 8px 0; border-bottom: 1px solid #eee;"><?php echo esc_html( $booking->get_formatted_status() ); ?></td>
                    </tr>
                </table>
                
                <?php if ( $service->get_location() ) : ?>
                <h3 style="color: #0073aa; border-bottom: 1px solid #eee; padding-bottom: 10px; margin-top: 20px;"><?php esc_html_e( 'Location', 'aqualuxe' ); ?></h3>
                <p><?php echo esc_html( $service->get_location() ); ?></p>
                <?php endif; ?>
                
                <p style="margin-top: 20px;"><?php esc_html_e( 'If you need to cancel or reschedule your booking, please contact us as soon as possible.', 'aqualuxe' ); ?></p>
                
                <p style="margin-top: 30px;"><?php esc_html_e( 'Thank you for choosing us!', 'aqualuxe' ); ?></p>
                <p><?php echo esc_html( $site_name ); ?></p>
            </div>
            
            <div style="text-align: center; margin-top: 20px; font-size: 12px; color: #666;">
                <p><?php echo esc_html( $site_name ); ?> - <a href="<?php echo esc_url( $site_url ); ?>" style="color: #0073aa; text-decoration: none;"><?php echo esc_url( $site_url ); ?></a></p>
            </div>
        </body>
        </html>
        <?php
        return ob_get_clean();
    }

    /**
     * Get customer status update message
     *
     * @param Booking $booking Booking object.
     * @param Service $service Service object.
     * @return string
     */
    private function get_customer_status_update_message( $booking, $service ) {
        // Get site info
        $site_name = get_bloginfo( 'name' );
        $site_url = get_bloginfo( 'url' );
        
        // Get status color
        $status_color = '#0073aa';
        switch ( $booking->get_status() ) {
            case 'confirmed':
                $status_color = '#46b450';
                break;
                
            case 'cancelled':
                $status_color = '#dc3232';
                break;
                
            case 'completed':
                $status_color = '#0073aa';
                break;
                
            case 'rescheduled':
                $status_color = '#ffb900';
                break;
        }
        
        ob_start();
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title><?php esc_html_e( 'Booking Status Update', 'aqualuxe' ); ?></title>
        </head>
        <body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
            <div style="text-align: center; margin-bottom: 20px;">
                <h1 style="color: <?php echo esc_attr( $status_color ); ?>;"><?php echo esc_html( $site_name ); ?></h1>
            </div>
            
            <div style="background-color: #f8f8f8; padding: 20px; border-radius: 5px; border-left: 4px solid <?php echo esc_attr( $status_color ); ?>;">
                <h2 style="color: <?php echo esc_attr( $status_color ); ?>; margin-top: 0;"><?php esc_html_e( 'Booking Status Update', 'aqualuxe' ); ?></h2>
                <p><?php printf( esc_html__( 'Dear %s,', 'aqualuxe' ), esc_html( $booking->get_customer_name() ) ); ?></p>
                <p><?php printf( esc_html__( 'Your booking status has been updated to: %s', 'aqualuxe' ), '<strong>' . esc_html( $booking->get_formatted_status() ) . '</strong>' ); ?></p>
                
                <h3 style="color: <?php echo esc_attr( $status_color ); ?>; border-bottom: 1px solid #eee; padding-bottom: 10px;"><?php esc_html_e( 'Booking Details', 'aqualuxe' ); ?></h3>
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="padding: 8px 0; border-bottom: 1px solid #eee; width: 40%;"><strong><?php esc_html_e( 'Service', 'aqualuxe' ); ?>:</strong></td>
                        <td style="padding: 8px 0; border-bottom: 1px solid #eee;"><?php echo esc_html( $service->get_title() ); ?></td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; border-bottom: 1px solid #eee;"><strong><?php esc_html_e( 'Date', 'aqualuxe' ); ?>:</strong></td>
                        <td style="padding: 8px 0; border-bottom: 1px solid #eee;"><?php echo esc_html( $booking->get_formatted_date() ); ?></td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; border-bottom: 1px solid #eee;"><strong><?php esc_html_e( 'Time', 'aqualuxe' ); ?>:</strong></td>
                        <td style="padding: 8px 0; border-bottom: 1px solid #eee;"><?php echo esc_html( $booking->get_formatted_time() ); ?></td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; border-bottom: 1px solid #eee;"><strong><?php esc_html_e( 'Duration', 'aqualuxe' ); ?>:</strong></td>
                        <td style="padding: 8px 0; border-bottom: 1px solid #eee;"><?php echo esc_html( $booking->get_formatted_duration() ); ?></td>
                    </tr>
                </table>
                
                <?php if ( $service->get_location() ) : ?>
                <h3 style="color: <?php echo esc_attr( $status_color ); ?>; border-bottom: 1px solid #eee; padding-bottom: 10px; margin-top: 20px;"><?php esc_html_e( 'Location', 'aqualuxe' ); ?></h3>
                <p><?php echo esc_html( $service->get_location() ); ?></p>
                <?php endif; ?>
                
                <?php if ( $booking->get_status() === 'confirmed' ) : ?>
                <p style="margin-top: 20px;"><?php esc_html_e( 'Your booking has been confirmed. We look forward to seeing you!', 'aqualuxe' ); ?></p>
                <p><?php esc_html_e( 'If you need to cancel or reschedule your booking, please contact us as soon as possible.', 'aqualuxe' ); ?></p>
                <?php elseif ( $booking->get_status() === 'cancelled' ) : ?>
                <p style="margin-top: 20px;"><?php esc_html_e( 'Your booking has been cancelled. If you did not request this cancellation, please contact us.', 'aqualuxe' ); ?></p>
                <?php elseif ( $booking->get_status() === 'completed' ) : ?>
                <p style="margin-top: 20px;"><?php esc_html_e( 'Your booking has been marked as completed. Thank you for choosing us!', 'aqualuxe' ); ?></p>
                <?php elseif ( $booking->get_status() === 'rescheduled' ) : ?>
                <p style="margin-top: 20px;"><?php esc_html_e( 'Your booking has been rescheduled. If you have any questions, please contact us.', 'aqualuxe' ); ?></p>
                <?php endif; ?>
                
                <p style="margin-top: 30px;"><?php esc_html_e( 'Thank you for choosing us!', 'aqualuxe' ); ?></p>
                <p><?php echo esc_html( $site_name ); ?></p>
            </div>
            
            <div style="text-align: center; margin-top: 20px; font-size: 12px; color: #666;">
                <p><?php echo esc_html( $site_name ); ?> - <a href="<?php echo esc_url( $site_url ); ?>" style="color: <?php echo esc_attr( $status_color ); ?>; text-decoration: none;"><?php echo esc_url( $site_url ); ?></a></p>
            </div>
        </body>
        </html>
        <?php
        return ob_get_clean();
    }

    /**
     * Get customer cancellation message
     *
     * @param Booking $booking Booking object.
     * @param Service $service Service object.
     * @return string
     */
    private function get_customer_cancellation_message( $booking, $service ) {
        // Get site info
        $site_name = get_bloginfo( 'name' );
        $site_url = get_bloginfo( 'url' );
        
        ob_start();
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title><?php esc_html_e( 'Booking Cancellation', 'aqualuxe' ); ?></title>
        </head>
        <body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
            <div style="text-align: center; margin-bottom: 20px;">
                <h1 style="color: #dc3232;"><?php echo esc_html( $site_name ); ?></h1>
            </div>
            
            <div style="background-color: #f8f8f8; padding: 20px; border-radius: 5px; border-left: 4px solid #dc3232;">
                <h2 style="color: #dc3232; margin-top: 0;"><?php esc_html_e( 'Booking Cancellation', 'aqualuxe' ); ?></h2>
                <p><?php printf( esc_html__( 'Dear %s,', 'aqualuxe' ), esc_html( $booking->get_customer_name() ) ); ?></p>
                <p><?php esc_html_e( 'Your booking has been cancelled.', 'aqualuxe' ); ?></p>
                
                <h3 style="color: #dc3232; border-bottom: 1px solid #eee; padding-bottom: 10px;"><?php esc_html_e( 'Booking Details', 'aqualuxe' ); ?></h3>
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="padding: 8px 0; border-bottom: 1px solid #eee; width: 40%;"><strong><?php esc_html_e( 'Service', 'aqualuxe' ); ?>:</strong></td>
                        <td style="padding: 8px 0; border-bottom: 1px solid #eee;"><?php echo esc_html( $service->get_title() ); ?></td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; border-bottom: 1px solid #eee;"><strong><?php esc_html_e( 'Date', 'aqualuxe' ); ?>:</strong></td>
                        <td style="padding: 8px 0; border-bottom: 1px solid #eee;"><?php echo esc_html( $booking->get_formatted_date() ); ?></td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; border-bottom: 1px solid #eee;"><strong><?php esc_html_e( 'Time', 'aqualuxe' ); ?>:</strong></td>
                        <td style="padding: 8px 0; border-bottom: 1px solid #eee;"><?php echo esc_html( $booking->get_formatted_time() ); ?></td>
                    </tr>
                </table>
                
                <p style="margin-top: 20px;"><?php esc_html_e( 'If you did not request this cancellation, please contact us as soon as possible.', 'aqualuxe' ); ?></p>
                
                <p style="margin-top: 30px;"><?php esc_html_e( 'Thank you for your understanding.', 'aqualuxe' ); ?></p>
                <p><?php echo esc_html( $site_name ); ?></p>
            </div>
            
            <div style="text-align: center; margin-top: 20px; font-size: 12px; color: #666;">
                <p><?php echo esc_html( $site_name ); ?> - <a href="<?php echo esc_url( $site_url ); ?>" style="color: #dc3232; text-decoration: none;"><?php echo esc_url( $site_url ); ?></a></p>
            </div>
        </body>
        </html>
        <?php
        return ob_get_clean();
    }

    /**
     * Get customer reminder message
     *
     * @param Booking $booking Booking object.
     * @param Service $service Service object.
     * @return string
     */
    private function get_customer_reminder_message( $booking, $service ) {
        // Get site info
        $site_name = get_bloginfo( 'name' );
        $site_url = get_bloginfo( 'url' );
        
        ob_start();
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title><?php esc_html_e( 'Booking Reminder', 'aqualuxe' ); ?></title>
        </head>
        <body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
            <div style="text-align: center; margin-bottom: 20px;">
                <h1 style="color: #46b450;"><?php echo esc_html( $site_name ); ?></h1>
            </div>
            
            <div style="background-color: #f8f8f8; padding: 20px; border-radius: 5px; border-left: 4px solid #46b450;">
                <h2 style="color: #46b450; margin-top: 0;"><?php esc_html_e( 'Booking Reminder', 'aqualuxe' ); ?></h2>
                <p><?php printf( esc_html__( 'Dear %s,', 'aqualuxe' ), esc_html( $booking->get_customer_name() ) ); ?></p>
                <p><?php esc_html_e( 'This is a friendly reminder about your upcoming booking with us.', 'aqualuxe' ); ?></p>
                
                <h3 style="color: #46b450; border-bottom: 1px solid #eee; padding-bottom: 10px;"><?php esc_html_e( 'Booking Details', 'aqualuxe' ); ?></h3>
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="padding: 8px 0; border-bottom: 1px solid #eee; width: 40%;"><strong><?php esc_html_e( 'Service', 'aqualuxe' ); ?>:</strong></td>
                        <td style="padding: 8px 0; border-bottom: 1px solid #eee;"><?php echo esc_html( $service->get_title() ); ?></td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; border-bottom: 1px solid #eee;"><strong><?php esc_html_e( 'Date', 'aqualuxe' ); ?>:</strong></td>
                        <td style="padding: 8px 0; border-bottom: 1px solid #eee;"><?php echo esc_html( $booking->get_formatted_date() ); ?></td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; border-bottom: 1px solid #eee;"><strong><?php esc_html_e( 'Time', 'aqualuxe' ); ?>:</strong></td>
                        <td style="padding: 8px 0; border-bottom: 1px solid #eee;"><?php echo esc_html( $booking->get_formatted_time() ); ?></td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; border-bottom: 1px solid #eee;"><strong><?php esc_html_e( 'Duration', 'aqualuxe' ); ?>:</strong></td>
                        <td style="padding: 8px 0; border-bottom: 1px solid #eee;"><?php echo esc_html( $booking->get_formatted_duration() ); ?></td>
                    </tr>
                </table>
                
                <?php if ( $service->get_location() ) : ?>
                <h3 style="color: #46b450; border-bottom: 1px solid #eee; padding-bottom: 10px; margin-top: 20px;"><?php esc_html_e( 'Location', 'aqualuxe' ); ?></h3>
                <p><?php echo esc_html( $service->get_location() ); ?></p>
                <?php endif; ?>
                
                <p style="margin-top: 20px;"><?php esc_html_e( 'If you need to cancel or reschedule your booking, please contact us as soon as possible.', 'aqualuxe' ); ?></p>
                
                <p style="margin-top: 30px;"><?php esc_html_e( 'We look forward to seeing you!', 'aqualuxe' ); ?></p>
                <p><?php echo esc_html( $site_name ); ?></p>
            </div>
            
            <div style="text-align: center; margin-top: 20px; font-size: 12px; color: #666;">
                <p><?php echo esc_html( $site_name ); ?> - <a href="<?php echo esc_url( $site_url ); ?>" style="color: #46b450; text-decoration: none;"><?php echo esc_url( $site_url ); ?></a></p>
            </div>
        </body>
        </html>
        <?php
        return ob_get_clean();
    }
}