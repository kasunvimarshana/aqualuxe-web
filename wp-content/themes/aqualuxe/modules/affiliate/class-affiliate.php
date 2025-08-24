<?php
/**
 * Affiliate/Referrals Module
 * Handles affiliate user roles, registration, referral tracking, dashboard, and commission calculation.
 */

namespace AquaLuxe\Modules\Affiliate;

if ( ! defined( 'ABSPATH' ) ) exit;

class Affiliate {
    public function __construct() {
        add_action( 'init', [ $this, 'register_affiliate_role' ] );
        add_shortcode( 'aqualuxe_affiliate_registration', [ $this, 'registration_form_shortcode' ] );
        add_action( 'init', [ $this, 'handle_registration_form' ] );
        add_shortcode( 'aqualuxe_affiliate_dashboard', [ $this, 'dashboard_shortcode' ] );
        // TODO: Implement referral tracking and commission system
    }

    public function register_affiliate_role() {
        add_role( 'aqualuxe_affiliate', __( 'Affiliate', 'aqualuxe' ), [
            'read' => true,
            'edit_posts' => false,
            'aqualuxe_affiliate' => true
        ] );
    }

    public function registration_form_shortcode() {
        if ( isset( $_GET['affiliate_registered'] ) ) {
            return '<div class="affiliate-success">Thank you for registering! Your account will be reviewed.</div>';
        }
        ob_start();
        ?>
        <form method="post" class="affiliate-registration-form">
            <input type="text" name="affiliate_name" placeholder="Your Name" required />
            <input type="email" name="affiliate_email" placeholder="Email" required />
            <input type="hidden" name="aqualuxe_affiliate_nonce" value="<?php echo wp_create_nonce('aqualuxe_affiliate'); ?>" />
            <button type="submit" name="submit_affiliate">Register</button>
        </form>
        <?php
        return ob_get_clean();
    }

    public function handle_registration_form() {
        if ( isset( $_POST['submit_affiliate'] ) && isset( $_POST['aqualuxe_affiliate_nonce'] ) && wp_verify_nonce( $_POST['aqualuxe_affiliate_nonce'], 'aqualuxe_affiliate' ) ) {
            $user_id = wp_create_user( sanitize_email( $_POST['affiliate_email'] ), wp_generate_password(), sanitize_email( $_POST['affiliate_email'] ) );
            if ( $user_id ) {
                wp_update_user([
                    'ID' => $user_id,
                    'display_name' => sanitize_text_field( $_POST['affiliate_name'] ),
                ]);
                $user = new \WP_User( $user_id );
                $user->add_role( 'aqualuxe_affiliate' );
                wp_redirect( add_query_arg( 'affiliate_registered', '1', wp_get_referer() ) );
                exit;
            }
        }
    }

    public function dashboard_shortcode() {
        if ( ! is_user_logged_in() || ! current_user_can( 'aqualuxe_affiliate' ) ) {
            return '<div class="affiliate-dashboard-error">You must be logged in as an affiliate to view this dashboard.</div>';
        }
        $user_id = get_current_user_id();
        // TODO: Display referral stats, commission, and link generator
        ob_start();
        ?>
        <div class="affiliate-dashboard">
            <h2>Affiliate Dashboard</h2>
            <p>Welcome, <?php echo esc_html( wp_get_current_user()->display_name ); ?>!</p>
            <p>Your referral link: <input type="text" value="<?php echo esc_url( home_url( '?ref=' . $user_id ) ); ?>" readonly /></p>
            <div class="affiliate-stats">
                <p><strong>Referrals:</strong> (demo)</p>
                <p><strong>Commission:</strong> (demo)</p>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
}

// Initialize the module
new Affiliate();
