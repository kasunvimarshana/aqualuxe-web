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
            </div><!-- .row -->
        </div><!-- .container -->
    </div><!-- #content -->

    <footer id="colophon" class="site-footer">
        <?php
        /**
         * Functions hooked into aqualuxe_footer action
         *
         * @hooked aqualuxe_footer_widgets - 10
         * @hooked aqualuxe_footer_info - 20
         * @hooked aqualuxe_footer_bottom - 30
         */
        do_action( 'aqualuxe_footer' );
        ?>
    </footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>