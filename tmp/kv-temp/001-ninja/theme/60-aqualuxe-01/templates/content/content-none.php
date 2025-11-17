<?php
/**
 * Template part for displaying a message that posts cannot be found
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

?>

<section class="no-results not-found">
    <header class="page-header">
        <h1 class="page-title"><?php esc_html_e('Nothing Found', 'aqualuxe'); ?></h1>
    </header><!-- .page-header -->

    <div class="page-content">
        <?php
        if (is_home() && current_user_can('publish_posts')) :

            printf(
                '<p>' . wp_kses(
                    /* translators: 1: link to WP admin new post page. */
                    __('Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'aqualuxe'),
                    array(
                        'a' => array(
                            'href' => array(),
                        ),
                    )
                ) . '</p>',
                esc_url(admin_url('post-new.php'))
            );

        elseif (is_search()) :
            ?>

            <p><?php esc_html_e('Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'aqualuxe'); ?></p>
            
            <div class="search-form-container">
                <?php get_search_form(); ?>
            </div>
            
            <?php if (aqualuxe_show_search_suggestions()) : ?>
                <div class="search-suggestions">
                    <h3><?php esc_html_e('Popular Searches', 'aqualuxe'); ?></h3>
                    <?php aqualuxe_popular_search_terms(); ?>
                </div>
            <?php endif; ?>

        <?php elseif (is_archive()) : ?>

            <p><?php esc_html_e('It seems we can\'t find what you\'re looking for in this archive. Perhaps searching can help.', 'aqualuxe'); ?></p>
            <?php get_search_form(); ?>

        <?php else : ?>

            <p><?php esc_html_e('It seems we can\'t find what you\'re looking for. Perhaps searching can help.', 'aqualuxe'); ?></p>
            <?php get_search_form(); ?>

            <?php if (aqualuxe_show_recent_posts()) : ?>
                <div class="recent-posts">
                    <h3><?php esc_html_e('Recent Posts', 'aqualuxe'); ?></h3>
                    <?php aqualuxe_recent_posts(5); ?>
                </div>
            <?php endif; ?>

        <?php endif; ?>
    </div><!-- .page-content -->
</section><!-- .no-results -->