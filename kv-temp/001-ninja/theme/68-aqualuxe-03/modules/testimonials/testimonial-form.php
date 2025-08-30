<?php
/**
 * Frontend Testimonial Submission Form
 */
if ( ! defined( 'ABSPATH' ) ) exit;

function aqualuxe_testimonial_form() {
    if ( isset($_POST['aqualuxe_submit_testimonial']) && wp_verify_nonce($_POST['aqualuxe_testimonial_nonce'], 'aqualuxe_submit_testimonial') ) {
        $author = sanitize_text_field($_POST['testimonial_author']);
        $content = sanitize_textarea_field($_POST['testimonial_content']);
        if ( $author && $content ) {
            $post_id = wp_insert_post([
                'post_type' => 'aqualuxe_testimonial',
                'post_title' => $author,
                'post_content' => $content,
                'post_status' => 'pending',
            ]);
            if ( $post_id ) {
                echo '<div class="testimonial-form-success">Thank you for your testimonial! It will be reviewed soon.</div>';
            }
        }
    }
    ?>
    <form class="testimonial-form" method="post">
        <label for="testimonial_author">Your Name</label>
        <input type="text" name="testimonial_author" id="testimonial_author" required />
        <label for="testimonial_content">Your Testimonial</label>
        <textarea name="testimonial_content" id="testimonial_content" required></textarea>
        <?php wp_nonce_field('aqualuxe_submit_testimonial', 'aqualuxe_testimonial_nonce'); ?>
        <button type="submit" name="aqualuxe_submit_testimonial">Submit Testimonial</button>
    </form>
    <?php
}

add_shortcode('aqualuxe_testimonial_form', 'aqualuxe_testimonial_form');