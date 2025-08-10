<?php
/**
 * Template part for displaying a message that posts cannot be found
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}
?>

<section class="no-results not-found bg-white dark:bg-dark-card rounded-xl shadow-soft dark:shadow-none p-6 md:p-8">
    <header class="page-header mb-6">
        <h1 class="page-title text-2xl font-bold"><?php esc_html_e('Nothing Found', 'aqualuxe'); ?></h1>
    </header><!-- .page-header -->

    <div class="page-content prose dark:prose-invert">
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
            
            <div class="search-form-container mt-6">
                <form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
                    <div class="flex">
                        <input type="search" class="w-full rounded-l-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 focus:ring-primary focus:border-primary" placeholder="<?php echo esc_attr_x('Search...', 'placeholder', 'aqualuxe'); ?>" value="<?php echo get_search_query(); ?>" name="s" />
                        <button type="submit" class="bg-primary hover:bg-primary-dark text-white px-6 rounded-r-md transition-colors duration-300">
                            <?php esc_html_e('Search', 'aqualuxe'); ?>
                        </button>
                    </div>
                </form>
            </div>

        <?php else : ?>

            <p><?php esc_html_e('It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'aqualuxe'); ?></p>
            
            <div class="search-form-container mt-6">
                <form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
                    <div class="flex">
                        <input type="search" class="w-full rounded-l-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 focus:ring-primary focus:border-primary" placeholder="<?php echo esc_attr_x('Search...', 'placeholder', 'aqualuxe'); ?>" value="<?php echo get_search_query(); ?>" name="s" />
                        <button type="submit" class="bg-primary hover:bg-primary-dark text-white px-6 rounded-r-md transition-colors duration-300">
                            <?php esc_html_e('Search', 'aqualuxe'); ?>
                        </button>
                    </div>
                </form>
            </div>

        <?php endif; ?>
    </div><!-- .page-content -->
</section><!-- .no-results -->