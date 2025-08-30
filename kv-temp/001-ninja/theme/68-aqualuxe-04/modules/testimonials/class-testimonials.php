<?php
/**
 * Testimonials Module
 * Handles custom post type, rating, and frontend display for testimonials.
 */

namespace AquaLuxe\Modules\Testimonials;

if ( ! defined( 'ABSPATH' ) ) exit;

class Testimonials {
    public function __construct() {
        add_action( 'init', [ $this, 'register_post_type' ] );
        add_action( 'add_meta_boxes', [ $this, 'add_rating_meta_box' ] );
        add_action( 'save_post', [ $this, 'save_rating_meta' ] );
        add_shortcode( 'aqualuxe_testimonial_form', [ $this, 'testimonial_form_shortcode' ] );
        add_action( 'init', [ $this, 'handle_form_submission' ] );
    }

    public function register_post_type() {
        $labels = [
            'name' => __( 'Testimonials', 'aqualuxe' ),
            'singular_name' => __( 'Testimonial', 'aqualuxe' ),
            'add_new' => __( 'Add New', 'aqualuxe' ),
            'add_new_item' => __( 'Add New Testimonial', 'aqualuxe' ),
            'edit_item' => __( 'Edit Testimonial', 'aqualuxe' ),
            'new_item' => __( 'New Testimonial', 'aqualuxe' ),
            'view_item' => __( 'View Testimonial', 'aqualuxe' ),
            'search_items' => __( 'Search Testimonials', 'aqualuxe' ),
            'not_found' => __( 'No testimonials found', 'aqualuxe' ),
            'not_found_in_trash' => __( 'No testimonials found in Trash', 'aqualuxe' ),
        ];
        $args = [
            'labels' => $labels,
            'public' => true,
            'has_archive' => true,
            'rewrite' => [ 'slug' => 'testimonials' ],
            'supports' => [ 'title', 'editor', 'thumbnail', 'custom-fields' ],
            'show_in_rest' => true,
        ];
        register_post_type( 'aqualuxe_testimonial', $args );
    }

    public function add_rating_meta_box() {
        add_meta_box(
            'testimonial_rating',
            __( 'Star Rating', 'aqualuxe' ),
            [ $this, 'render_rating_meta_box' ],
            'aqualuxe_testimonial',
            'side',
            'default'
        );
    }

    public function render_rating_meta_box( $post ) {
        $rating = get_post_meta( $post->ID, '_testimonial_rating', true );
        echo '<label for="testimonial_rating">' . __( 'Rating (1-5 stars):', 'aqualuxe' ) . '</label>';
        echo '<select name="testimonial_rating" id="testimonial_rating">';
        for ( $i = 1; $i <= 5; $i++ ) {
            printf( '<option value="%d"%s>%d</option>', $i, selected( $rating, $i, false ), $i );
        }
        echo '</select>';
    }

    public function save_rating_meta( $post_id ) {
        if ( isset( $_POST['testimonial_rating'] ) ) {
            update_post_meta( $post_id, '_testimonial_rating', intval( $_POST['testimonial_rating'] ) );
        }
    }

    public function testimonial_form_shortcode() {
        if ( isset( $_GET['testimonial_submitted'] ) ) {
            return '<div class="testimonial-success">Thank you for your testimonial! It will be reviewed soon.</div>';
        }
        ob_start();
        ?>
        <form method="post" class="testimonial-form">
            <input type="text" name="testimonial_author" placeholder="Your Name" required />
            <input type="email" name="testimonial_email" placeholder="Your Email" required />
            <textarea name="testimonial_content" placeholder="Your Testimonial" required></textarea>
            <label>Rating:
                <select name="testimonial_rating" required>
                    <option value="">Select</option>
                    <?php for ( $i = 1; $i <= 5; $i++ ) echo "<option value='$i'>$i</option>"; ?>
                </select>
            </label>
            <input type="hidden" name="aqualuxe_testimonial_nonce" value="<?php echo wp_create_nonce('aqualuxe_testimonial'); ?>" />
            <button type="submit" name="submit_testimonial">Submit</button>
        </form>
        <?php
        return ob_get_clean();
    }

    public function handle_form_submission() {
        if ( isset( $_POST['submit_testimonial'] ) && isset( $_POST['aqualuxe_testimonial_nonce'] ) && wp_verify_nonce( $_POST['aqualuxe_testimonial_nonce'], 'aqualuxe_testimonial' ) ) {
            $post_id = wp_insert_post([
                'post_type' => 'aqualuxe_testimonial',
                'post_title' => sanitize_text_field( $_POST['testimonial_author'] ),
                'post_content' => sanitize_textarea_field( $_POST['testimonial_content'] ),
                'post_status' => 'pending',
                'meta_input' => [
                    '_testimonial_rating' => intval( $_POST['testimonial_rating'] ),
                    '_testimonial_email' => sanitize_email( $_POST['testimonial_email'] ),
                ]
            ]);
            if ( $post_id ) {
                wp_redirect( add_query_arg( 'testimonial_submitted', '1', wp_get_referer() ) );
                exit;
            }
        }
    }
}

// Initialize the module
new Testimonials();
