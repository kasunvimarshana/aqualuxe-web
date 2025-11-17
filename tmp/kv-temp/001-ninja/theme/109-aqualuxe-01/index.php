<?php
/**
 * Main template file
 * 
 * @package AquaLuxe
 * @since 1.0.0
 */

get_header(); ?>

<main id="main" class="site-main" role="main">
    <div class="container mx-auto px-4">
        <?php if (have_posts()) : ?>
            <div class="posts-grid grid gap-8 md:grid-cols-2 lg:grid-cols-3">
                <?php while (have_posts()) : the_post(); ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class('post-card bg-white rounded-lg shadow-md overflow-hidden'); ?>>
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="post-thumbnail">
                                <a href="<?php the_permalink(); ?>" class="block">
                                    <?php the_post_thumbnail('medium_large', ['class' => 'w-full h-48 object-cover']); ?>
                                </a>
                            </div>
                        <?php endif; ?>
                        
                        <div class="post-content p-6">
                            <header class="entry-header mb-4">
                                <?php the_title('<h2 class="entry-title text-xl font-semibold mb-2"><a href="' . esc_url(get_permalink()) . '" class="text-gray-900 hover:text-blue-600">', '</a></h2>'); ?>
                                
                                <div class="entry-meta text-sm text-gray-600">
                                    <time datetime="<?php echo esc_attr(get_the_date('c')); ?>" class="published">
                                        <?php echo esc_html(get_the_date()); ?>
                                    </time>
                                    <span class="byline">
                                        <?php esc_html_e('by', 'aqualuxe'); ?> 
                                        <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>" class="author">
                                            <?php echo esc_html(get_the_author()); ?>
                                        </a>
                                    </span>
                                </div>
                            </header>

                            <div class="entry-summary">
                                <?php the_excerpt(); ?>
                            </div>

                            <footer class="entry-footer mt-4">
                                <a href="<?php the_permalink(); ?>" class="read-more inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition-colors">
                                    <?php esc_html_e('Read More', 'aqualuxe'); ?>
                                </a>
                            </footer>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>

            <?php
            // Pagination
            the_posts_pagination([
                'mid_size' => 2,
                'prev_text' => esc_html__('&larr; Previous', 'aqualuxe'),
                'next_text' => esc_html__('Next &rarr;', 'aqualuxe'),
                'class' => 'pagination mt-12 flex justify-center',
            ]);
            ?>

        <?php else : ?>
            <div class="no-posts text-center py-16">
                <h1 class="text-3xl font-bold text-gray-900 mb-4">
                    <?php esc_html_e('Nothing Found', 'aqualuxe'); ?>
                </h1>
                <p class="text-gray-600 mb-8">
                    <?php esc_html_e('It seems we can\'t find what you\'re looking for. Perhaps searching can help.', 'aqualuxe'); ?>
                </p>
                <?php get_search_form(); ?>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php
get_sidebar();
get_footer();