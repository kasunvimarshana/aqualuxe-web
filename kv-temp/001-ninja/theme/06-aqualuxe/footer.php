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

    <?php get_template_part( 'templates/parts/footer', get_theme_mod( 'aqualuxe_footer_layout', 'default' ) ); ?>

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>