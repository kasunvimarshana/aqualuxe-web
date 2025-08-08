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

        </main><!-- #main -->
    </div><!-- #page -->

    <footer id="colophon" class="site-footer">
        <div class="footer-widgets">
            <?php
            if (is_active_sidebar('footer-1') || is_active_sidebar('footer-2') || is_active_sidebar('footer-3') || is_active_sidebar('footer-4')) :
                ?>
                <div class="footer-widget-area">
                    <?php if (is_active_sidebar('footer-1')) : ?>
                        <div class="footer-column">
                            <?php dynamic_sidebar('footer-1'); ?>
                        </div>
                    <?php endif; ?>

                    <?php if (is_active_sidebar('footer-2')) : ?>
                        <div class="footer-column">
                            <?php dynamic_sidebar('footer-2'); ?>
                        </div>
                    <?php endif; ?>

                    <?php if (is_active_sidebar('footer-3')) : ?>
                        <div class="footer-column">
                            <?php dynamic_sidebar('footer-3'); ?>
                        </div>
                    <?php endif; ?>

                    <?php if (is_active_sidebar('footer-4')) : ?>
                        <div class="footer-column">
                            <?php dynamic_sidebar('footer-4'); ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div><!-- .footer-widgets -->

        <div class="site-info">
            <?php
            $copyright_text = get_theme_mod('aqualuxe_copyright_text', __('Copyright &copy; [year] [sitename]. All rights reserved.', 'aqualuxe'));
            $copyright_text = str_replace('[year]', date('Y'), $copyright_text);
            $copyright_text = str_replace('[sitename]', get_bloginfo('name'), $copyright_text);
            echo wp_kses_post($copyright_text);
            ?>
        </div><!-- .site-info -->
    </footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>