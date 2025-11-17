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

    </div><!-- #content -->

    <?php
    // Get the footer style from customizer
    $footer_style = get_theme_mod('aqualuxe_footer_style', 'default');
    
    // Load the appropriate footer template part
    get_template_part('template-parts/footer/footer', $footer_style);
    ?>

</div><!-- #page -->

<?php
// Display back to top button if enabled
if (get_theme_mod('aqualuxe_back_to_top_enable', true)) {
    get_template_part('template-parts/components/back-to-top');
}

// Display cookie notice if enabled
if (get_theme_mod('aqualuxe_cookie_notice_enable', true)) {
    get_template_part('template-parts/components/cookie-notice');
}

// Display dark mode toggle if enabled
if (get_theme_mod('aqualuxe_dark_mode_enable', true)) {
    get_template_part('template-parts/components/dark-mode-toggle');
}

wp_footer();
?>

</body>
</html>