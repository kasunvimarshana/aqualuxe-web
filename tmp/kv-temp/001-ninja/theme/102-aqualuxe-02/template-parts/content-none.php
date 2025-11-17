<?php
/**
 * Template part for displaying a message that posts cannot be found
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>

<section class="no-results not-found bg-white dark:bg-gray-800 rounded-lg shadow-md p-8 text-center">
    <header class="page-header mb-6">
        <h1 class="page-title text-2xl font-bold text-gray-900 dark:text-white mb-4">
            <?php esc_html_e( 'Nothing here', 'aqualuxe' ); ?>
        </h1>
    </header><!-- .page-header -->

    <div class="page-content text-gray-600 dark:text-gray-400">
        <?php
        if ( is_home() && current_user_can( 'publish_posts' ) ) :

            printf(
                '<p>' . wp_kses(
                    /* translators: 1: link to WP admin new post page. */
                    __( 'Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'aqualuxe' ),
                    array(
                        'a' => array(
                            'href' => array(),
                        ),
                    )
                ) . '</p>',
                esc_url( admin_url( 'post-new.php' ) )
            );

        elseif ( is_search() ) :
            ?>

            <p class="mb-6"><?php esc_html_e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'aqualuxe' ); ?></p>
            
            <div class="search-form-wrapper max-w-md mx-auto">
                <?php get_search_form(); ?>
            </div>

            <?php
        else :
            ?>

            <p class="mb-6"><?php esc_html_e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'aqualuxe' ); ?></p>
            
            <div class="search-form-wrapper max-w-md mx-auto">
                <?php get_search_form(); ?>
            </div>

            <?php
        endif;
        ?>
    </div><!-- .page-content -->
</section><!-- .no-results -->