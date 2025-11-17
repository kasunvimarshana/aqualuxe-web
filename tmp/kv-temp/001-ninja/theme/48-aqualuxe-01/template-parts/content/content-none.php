<?php
/**
 * Template part for displaying a message that posts cannot be found
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;
?>

<section class="no-results not-found bg-white dark:bg-dark-light rounded-lg shadow-soft overflow-hidden p-6 mb-8">
    <header class="page-header mb-6">
        <h1 class="page-title text-2xl md:text-3xl font-serif font-bold">
            <?php esc_html_e( 'Nothing Found', 'aqualuxe' ); ?>
        </h1>
    </header>

    <div class="page-content prose dark:prose-invert max-w-none">
        <?php if ( is_search() ) : ?>
            <p><?php esc_html_e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'aqualuxe' ); ?></p>
            
            <div class="mt-6">
                <?php get_search_form(); ?>
            </div>
            
        <?php elseif ( is_home() && current_user_can( 'publish_posts' ) ) : ?>
            <p>
                <?php
                printf(
                    wp_kses(
                        /* translators: %1$s: link to new post page */
                        __( 'Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'aqualuxe' ),
                        array(
                            'a' => array(
                                'href' => array(),
                            ),
                        )
                    ),
                    esc_url( admin_url( 'post-new.php' ) )
                );
                ?>
            </p>
            
        <?php elseif ( is_archive() ) : ?>
            <p><?php esc_html_e( 'It seems we can&rsquo;t find what you&rsquo;re looking for in this archive. Perhaps searching can help.', 'aqualuxe' ); ?></p>
            
            <div class="mt-6">
                <?php get_search_form(); ?>
            </div>
            
        <?php else : ?>
            <p><?php esc_html_e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'aqualuxe' ); ?></p>
            
            <div class="mt-6">
                <?php get_search_form(); ?>
            </div>
            
        <?php endif; ?>
    </div>
</section>