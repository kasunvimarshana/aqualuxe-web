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
        <?php do_action('aqualuxe_after_content'); ?>
    </div><!-- #content -->

    <?php do_action('aqualuxe_before_footer'); ?>

    <footer id="colophon" class="site-footer">
        <?php get_template_part('templates/footer/footer', aqualuxe_get_footer_style()); ?>
    </footer><!-- #colophon -->

    <?php do_action('aqualuxe_after_footer'); ?>
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>