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

    <?php
    /**
     * Functions hooked into aqualuxe_after_content action
     *
     * @hooked aqualuxe_page_footer - 10
     */
    do_action('aqualuxe_after_content');
    ?>

    <footer id="colophon" class="site-footer">
        <?php
        /**
         * Functions hooked into aqualuxe_footer action
         *
         * @hooked aqualuxe_footer_widgets - 10
         * @hooked aqualuxe_footer_bottom - 20
         */
        do_action('aqualuxe_footer');
        ?>
    </footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>