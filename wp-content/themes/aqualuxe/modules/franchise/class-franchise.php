<?php
/**
 * Franchise/Licensing Module
 * Handles franchise location CPT, finder, application form, content areas, and territory management.
 */

namespace AquaLuxe\Modules\Franchise;

if ( ! defined( 'ABSPATH' ) ) exit;

class Franchise {
    public function __construct() {
        add_action( 'init', [ $this, 'register_location_post_type' ] );
        add_shortcode( 'aqualuxe_franchise_application', [ $this, 'application_form_shortcode' ] );
        add_action( 'init', [ $this, 'handle_application_form' ] );
        // TODO: Implement location finder with map and territory management
    }

    public function register_location_post_type() {
        $labels = [
            'name' => __( 'Franchise Locations', 'aqualuxe' ),
            'singular_name' => __( 'Franchise Location', 'aqualuxe' ),
            'add_new' => __( 'Add New', 'aqualuxe' ),
            'add_new_item' => __( 'Add New Franchise Location', 'aqualuxe' ),
            'edit_item' => __( 'Edit Franchise Location', 'aqualuxe' ),
            'new_item' => __( 'New Franchise Location', 'aqualuxe' ),
            'view_item' => __( 'View Franchise Location', 'aqualuxe' ),
            'search_items' => __( 'Search Franchise Locations', 'aqualuxe' ),
            'not_found' => __( 'No franchise locations found', 'aqualuxe' ),
            'not_found_in_trash' => __( 'No franchise locations found in Trash', 'aqualuxe' ),
        ];
        $args = [
            'labels' => $labels,
            'public' => true,
            'has_archive' => true,
            'rewrite' => [ 'slug' => 'franchise-locations' ],
            'supports' => [ 'title', 'editor', 'thumbnail', 'custom-fields' ],
            'show_in_rest' => true,
        ];
    register_post_type( 'aqlx_franchise_loc', $args );
    }

    public function application_form_shortcode() {
        if ( isset( $_GET['franchise_applied'] ) ) {
            return '<div class="franchise-success">Thank you for your application! We will contact you soon.</div>';
        }
        ob_start();
        ?>
        <form method="post" class="franchise-application-form">
            <input type="text" name="franchise_name" placeholder="Your Name" required />
            <input type="email" name="franchise_email" placeholder="Email" required />
            <input type="text" name="franchise_location" placeholder="Desired Location" required />
            <textarea name="franchise_details" placeholder="Details" required></textarea>
            <input type="hidden" name="aqualuxe_franchise_nonce" value="<?php echo wp_create_nonce('aqualuxe_franchise'); ?>" />
            <button type="submit" name="submit_franchise">Apply</button>
        </form>
        <?php
        return ob_get_clean();
    }

    public function handle_application_form() {
        if ( isset( $_POST['submit_franchise'] ) && isset( $_POST['aqualuxe_franchise_nonce'] ) && wp_verify_nonce( $_POST['aqualuxe_franchise_nonce'], 'aqualuxe_franchise' ) ) {
            // For demo: send email to admin
            wp_mail( get_option( 'admin_email' ), 'New Franchise Application',
                'Name: ' . sanitize_text_field( $_POST['franchise_name'] ) . "\n" .
                'Email: ' . sanitize_email( $_POST['franchise_email'] ) . "\n" .
                'Location: ' . sanitize_text_field( $_POST['franchise_location'] ) . "\n" .
                'Details: ' . sanitize_textarea_field( $_POST['franchise_details'] )
            );
            wp_redirect( add_query_arg( 'franchise_applied', '1', wp_get_referer() ) );
            exit;
        }
    }
}

// Initialize the module
new Franchise();
