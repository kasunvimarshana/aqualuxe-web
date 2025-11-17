<?php
/**
 * The template for displaying the footer
 *
 * @package Aqualuxe
 */
?>
    </div><!-- #content -->

    <footer id="colophon" class="site-footer" role="contentinfo">
        <div class="container">
            <div class="site-info">
                <small>&copy; <?php echo esc_html( date( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?></small>
            </div>
            <nav class="footer-navigation" aria-label="Footer">
                <?php wp_nav_menu( [ 'theme_location' => 'footer', 'menu_class' => 'menu', 'container' => false ] ); ?>
            </nav>
        </div>
    </footer>

    <?php wp_footer(); ?>
</body>
</html>
