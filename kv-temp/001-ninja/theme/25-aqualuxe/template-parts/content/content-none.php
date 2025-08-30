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
    <header class="page-header mb-6">
        <h1 class="page-title text-2xl font-bold"><?php esc_html_e('Nothing Found', 'aqualuxe'); ?></h1>
    </header><!-- .page-header -->

    <div class="page-content prose max-w-none">
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

            <p class="mb-6"><?php esc_html_e('Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'aqualuxe'); ?></p>
            
            <div class="search-form-container mb-8">
                <?php get_search_form(); ?>
            </div>

            <h2 class="text-xl font-bold mb-4"><?php esc_html_e('Popular Topics', 'aqualuxe'); ?></h2>
            
            <div class="popular-topics mb-8">
                <?php
                $popular_tags = get_tags(array(
                    'orderby' => 'count',
                    'order'   => 'DESC',
                    'number'  => 10,
                ));

                if ($popular_tags) :
                    echo '<div class="flex flex-wrap gap-2">';
                    foreach ($popular_tags as $tag) {
                        echo '<a href="' . esc_url(get_tag_link($tag->term_id)) . '" class="inline-block px-3 py-1 bg-gray-100 hover:bg-gray-200 rounded-full text-sm">' . esc_html($tag->name) . '</a>';
                    }
                    echo '</div>';
                endif;
                ?>
            </div>

        <?php else : ?>

            <p><?php esc_html_e('It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'aqualuxe'); ?></p>
            
            <div class="search-form-container mt-6">
                <?php get_search_form(); ?>
            </div>

        <?php endif; ?>
    </div><!-- .page-content -->
</section><!-- .no-results -->