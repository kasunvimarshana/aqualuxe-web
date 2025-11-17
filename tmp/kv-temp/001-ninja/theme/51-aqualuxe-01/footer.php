<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

?>

        <?php do_action('aqualuxe_content_bottom'); ?>
    </div><!-- #content -->

    <?php
    // Check if this is a distraction-free checkout page
    $is_distraction_free_checkout = false;
    
    if (aqualuxe_is_woocommerce_active()) {
        if (is_checkout() && aqualuxe_get_option('checkout_distraction_free', false)) {
            $is_distraction_free_checkout = true;
        }
    }
    
    // Display footer if not a distraction-free checkout page
    if (!$is_distraction_free_checkout) :
    ?>
    
    <footer id="colophon" class="site-footer aqualuxe-footer">
        <?php do_action('aqualuxe_footer'); ?>
    </footer><!-- #colophon -->
    
    <?php endif; ?>
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>