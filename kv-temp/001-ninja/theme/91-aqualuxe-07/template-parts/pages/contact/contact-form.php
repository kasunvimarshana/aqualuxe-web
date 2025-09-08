<?php
/**
 * Displays the contact form section on the contact page.
 *
 * @package AquaLuxe
 */

?>
<div class="contact-form">
    <h2 class="text-2xl font-bold mb-4"><?php esc_html_e( 'Send us a message', 'aqualuxe' ); ?></h2>
    <!-- Placeholder for a contact form plugin shortcode -->
    <?php echo do_shortcode('[contact-form-7 id="1234" title="Contact form 1"]'); ?>
</div>
