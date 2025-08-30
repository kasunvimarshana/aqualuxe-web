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
                <?php
                /**
                 * Hook: aqualuxe_content_bottom
                 */
                aqualuxe_do_content_bottom();
                ?>
            </div><!-- .site-content-inner -->
        </div><!-- .container -->
    </div><!-- #content -->

    <?php
    /**
     * Hook: aqualuxe_after_content
     */
    aqualuxe_do_after_content();
    ?>

    <?php
    /**
     * Hook: aqualuxe_before_footer
     */
    aqualuxe_do_before_footer();
    ?>

    <footer id="colophon" class="site-footer" <?php aqualuxe_attr( 'footer' ); ?>>
        <?php
        /**
         * Hook: aqualuxe_footer_top
         */
        aqualuxe_do_footer_top();
        ?>

        <?php
        /**
         * Hook: aqualuxe_footer_widgets
         */
        aqualuxe_do_footer_widgets();
        ?>

        <?php
        /**
         * Hook: aqualuxe_footer_bottom
         */
        aqualuxe_do_footer_bottom();
        ?>
    </footer><!-- #colophon -->

    <?php
    /**
     * Hook: aqualuxe_after_footer
     */
    aqualuxe_do_after_footer();
    ?>
</div><!-- #page -->

<?php
// Search modal
aqualuxe_search_modal();

// Mobile menu
aqualuxe_mobile_menu();

// Back to top button
if ( aqualuxe_get_option( 'enable_back_to_top', true ) ) {
    echo '<button id="back-to-top" class="back-to-top" aria-label="' . esc_attr__( 'Back to top', 'aqualuxe' ) . '">';
    echo aqualuxe_get_icon( 'chevron-up' );
    echo '</button>';
}
?>

<?php wp_footer(); ?>

</body>
</html>