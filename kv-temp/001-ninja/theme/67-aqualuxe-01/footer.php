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
                <?php do_action( 'aqualuxe_content_after' ); ?>
            </div><!-- .content-wrapper -->
        </div><!-- .container -->
    </div><!-- #content -->

    <footer id="colophon" class="site-footer footer-layout-<?php echo esc_attr( aqualuxe_get_footer_layout() ); ?>">
        <?php
        /**
         * Functions hooked into aqualuxe_footer action
         *
         * @hooked aqualuxe_footer_before - 5
         * @hooked aqualuxe_footer_widgets - 10
         * @hooked aqualuxe_footer_main - 20
         * @hooked aqualuxe_footer_bottom - 30
         * @hooked aqualuxe_footer_after - 35
         */
        do_action( 'aqualuxe_footer' );
        ?>
    </footer><!-- #colophon -->

    <?php
    // Display back to top button
    aqualuxe_back_to_top();
    ?>
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>