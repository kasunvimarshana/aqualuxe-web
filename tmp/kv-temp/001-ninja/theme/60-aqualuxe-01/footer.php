<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package AquaLuxe
 */

?>
        <?php do_action('aqualuxe_after_main_content'); ?>
    </div><!-- #content -->

    <?php aqualuxe_display_footer_complete(); ?>

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>