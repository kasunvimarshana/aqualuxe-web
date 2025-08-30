<?php
/**
 * Template part for displaying results in search pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden mb-8 transition-transform hover:-translate-y-1 hover:shadow-lg'); ?>>
    <div class="flex flex-col md:flex-row">
        <?php if (has_post_thumbnail()) : ?>
            <div class="entry-thumbnail md:w-1/4">
                <a href="<?php the_permalink(); ?>" class="block h-full">
                    <?php the_post_thumbnail('medium', ['class' => 'w-full h-full object-cover']); ?>
                </a>
            </div>
        <?php endif; ?>

        <div class="entry-content p-6 <?php echo has_post_thumbnail() ? 'md:w-3/4' : 'w-full'; ?>">
            <header class="entry-header mb-4">
                <?php the_title(sprintf('<h2 class="entry-title text-xl font-bold mb-2"><a href="%s" rel="bookmark" class="hover:text-primary-600 dark:hover:text-primary-400 transition-colors">', esc_url(get_permalink())), '</a></h2>'); ?>

                <?php if ('post' === get_post_type()) : ?>
                    <div class="entry-meta text-sm text-gray-600 dark:text-gray-400">
                        <?php
                        aqualuxe_posted_on();
                        aqualuxe_posted_by();
                        ?>
                    </div><!-- .entry-meta -->
                <?php endif; ?>
            </header><!-- .entry-header -->

            <div class="entry-summary">
                <?php the_excerpt(); ?>
            </div><!-- .entry-summary -->

            <footer class="entry-footer mt-4">
                <div class="flex items-center justify-between">
                    <a href="<?php the_permalink(); ?>" class="inline-flex items-center text-primary-600 dark:text-primary-400 hover:text-primary-800 dark:hover:text-primary-300 transition-colors text-sm">
                        <?php esc_html_e('Read More', 'aqualuxe'); ?>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </a>
                    
                    <div class="text-sm text-gray-600 dark:text-gray-400">
                        <?php
                        $post_type_obj = get_post_type_object(get_post_type());
                        if ($post_type_obj) {
                            echo esc_html($post_type_obj->labels->singular_name);
                        }
                        ?>
                    </div>
                </div>
            </footer><!-- .entry-footer -->
        </div>
    </div>
</article><!-- #post-<?php the_ID(); ?> -->