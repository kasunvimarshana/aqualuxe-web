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

    </div><!-- #content -->

    <?php do_action( 'aqualuxe_before_footer' ); ?>

    <footer id="colophon" class="site-footer">
        <?php do_action( 'aqualuxe_footer' ); ?>
    </footer><!-- #colophon -->

    <?php do_action( 'aqualuxe_after_footer' ); ?>

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>