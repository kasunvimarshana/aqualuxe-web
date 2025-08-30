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

    <footer id="colophon" class="site-footer bg-primary text-white">
        <?php
        // Newsletter section (above footer widgets)
        if (get_theme_mod('aqualuxe_newsletter_position', 'above') === 'above') {
            get_template_part('template-parts/footer/footer', 'newsletter');
        }
        
        // Footer widgets
        get_template_part('template-parts/footer/footer', 'widgets');
        
        // Newsletter section (below footer widgets)
        if (get_theme_mod('aqualuxe_newsletter_position', 'above') === 'below') {
            get_template_part('template-parts/footer/footer', 'newsletter');
        }
        
        // Footer copyright and menu
        get_template_part('template-parts/footer/footer', 'copyright');
        ?>
    </footer><!-- #colophon -->
</div><!-- #page -->

<?php if (get_theme_mod('aqualuxe_back_to_top', true)) : ?>
    <button id="back-to-top" class="back-to-top fixed bottom-8 right-8 bg-accent text-white p-3 rounded-full shadow-lg opacity-0 invisible transition-all duration-300 z-50" aria-label="<?php esc_attr_e('Back to top', 'aqualuxe'); ?>">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
        </svg>
    </button>
<?php endif; ?>

<?php wp_footer(); ?>

</body>
</html>