<?php
/**
 * Template Name: Landing Page
 * Template Post Type: page
 *
 * A template for creating marketing landing pages with no header or footer.
 *
 * @package AquaLuxe
 */

?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">

    <?php wp_head(); ?>
</head>

<body <?php body_class('landing-page'); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site">
    <a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'aqualuxe' ); ?></a>

    <main id="primary" class="site-main">
        <?php
        while ( have_posts() ) :
            the_post();

            get_template_part( 'template-parts/content/content', 'page' );

        endwhile; // End of the loop.
        ?>
    </main><!-- #main -->

    <footer class="landing-page-footer py-6 bg-gray-100 dark:bg-gray-800 text-center text-sm text-gray-600 dark:text-gray-400">
        <div class="container mx-auto px-4">
            <p>
                &copy; <?php echo date_i18n( 'Y' ); ?> <?php echo esc_html( get_bloginfo( 'name' ) ); ?>. 
                <?php esc_html_e( 'All rights reserved.', 'aqualuxe' ); ?>
            </p>
            
            <?php if ( has_nav_menu( 'footer-mini' ) ) : ?>
                <nav class="landing-footer-navigation mt-2">
                    <?php
                    wp_nav_menu(
                        array(
                            'theme_location' => 'footer-mini',
                            'menu_class'     => 'flex flex-wrap justify-center gap-4 mt-2',
                            'container'      => false,
                            'depth'          => 1,
                            'fallback_cb'    => false,
                        )
                    );
                    ?>
                </nav>
            <?php endif; ?>
        </div>
    </footer>
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>