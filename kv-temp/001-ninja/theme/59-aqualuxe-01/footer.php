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

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
?>

        <?php
        /**
         * Hook: aqualuxe_after_main_content
         *
         * @hooked aqualuxe_newsletter - 10
         * @hooked aqualuxe_cta_section - 20
         */
        do_action('aqualuxe_after_main_content');
        ?>
    </div><!-- #content -->

    <?php
    /**
     * Hook: aqualuxe_before_footer
     *
     * @hooked aqualuxe_pre_footer - 10
     */
    do_action('aqualuxe_before_footer');
    ?>

    <?php get_template_part('templates/parts/footer'); ?>

    <?php
    /**
     * Hook: aqualuxe_after_footer
     *
     * @hooked aqualuxe_mobile_menu - 10
     * @hooked aqualuxe_search_modal - 20
     * @hooked aqualuxe_cookie_notice - 30
     */
    do_action('aqualuxe_after_footer');
    ?>
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>