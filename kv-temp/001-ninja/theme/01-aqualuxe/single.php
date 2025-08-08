<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package AquaLuxe
 */

get_header();

$sidebar_position = get_theme_mod('aqualuxe_single_post_sidebar_position', 'right');
$container_class = 'container mx-auto px-4 py-12';
$content_class = 'site-main';
$has_sidebar = is_active_sidebar('sidebar-blog') && $sidebar_position !== 'none';

if ($has_sidebar) {
    $content_class .= ' lg:w-2/3';
    if ($sidebar_position === 'left') {
        $content_class .= ' lg:order-2';
    } else {
        $content_class .= ' lg:order-1';
    }
}
?>

<div class="<?php echo esc_attr($container_class); ?>">
    <div class="flex flex-wrap lg:flex-nowrap <?php echo $has_sidebar ? 'lg:space-x-8' : ''; ?>">
        <main id="primary" class="<?php echo esc_attr($content_class); ?>">
            <?php
            while (have_posts()) :
                the_post();

                get_template_part('templates/components/content', 'single');

                // If comments are open or we have at least one comment, load up the comment template.
                if (comments_open() || get_comments_number()) :
                    comments_template();
                endif;

                // Post Navigation
                $prev_post = get_previous_post();
                $next_post = get_next_post();

                if ($prev_post || $next_post) :
            ?>
                    <nav class="post-navigation border-t border-b border-gray-200 dark:border-gray-700 py-6 my-8" aria-label="<?php esc_attr_e('Post Navigation', 'aqualuxe'); ?>">
                        <div class="flex flex-wrap justify-between">
                            <?php if ($prev_post) : ?>
                                <div class="post-navigation-prev w-full md:w-1/2 md:pr-4 mb-4 md:mb-0">
                                    <span class="text-sm text-gray-600 dark:text-gray-400 block mb-1"><?php esc_html_e('Previous Post', 'aqualuxe'); ?></span>
                                    <a href="<?php echo esc_url(get_permalink($prev_post->ID)); ?>" class="text-lg font-medium hover:text-primary-600 dark:hover:text-primary-400">
                                        <?php echo esc_html(get_the_title($prev_post->ID)); ?>
                                    </a>
                                </div>
                            <?php endif; ?>

                            <?php if ($next_post) : ?>
                                <div class="post-navigation-next w-full md:w-1/2 md:pl-4 text-left md:text-right">
                                    <span class="text-sm text-gray-600 dark:text-gray-400 block mb-1"><?php esc_html_e('Next Post', 'aqualuxe'); ?></span>
                                    <a href="<?php echo esc_url(get_permalink($next_post->ID)); ?>" class="text-lg font-medium hover:text-primary-600 dark:hover:text-primary-400">
                                        <?php echo esc_html(get_the_title($next_post->ID)); ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </nav>
            <?php
                endif;

                // Related Posts
                if (get_theme_mod('aqualuxe_show_related_posts', true)) :
                    get_template_part('templates/components/content', 'related');
                endif;

            endwhile; // End of the loop.
            ?>
        </main><!-- #main -->

        <?php if ($has_sidebar) : ?>
            <aside id="secondary" class="widget-area lg:w-1/3 <?php echo $sidebar_position === 'left' ? 'lg:order-1' : 'lg:order-2'; ?> mt-8 lg:mt-0">
                <?php dynamic_sidebar('sidebar-blog'); ?>
            </aside><!-- #secondary -->
        <?php endif; ?>
    </div>
</div>

<?php
get_footer();