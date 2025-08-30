<?php
/**
 * Main theme index template
 * 
 * @package AquaLuxe
 * @since 1.0.0
 */

get_header(); ?>

<main id="primary" class="site-main" <?php echo aqualuxe_schema_markup(); ?>>
    <div class="container mx-auto px-4">
        <?php if (have_posts()) : ?>
            <div class="posts-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php while (have_posts()) : the_post(); ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class('post-card card hover:shadow-xl transition-all duration-300'); ?>>
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="post-thumbnail mb-4">
                                <a href="<?php the_permalink(); ?>" class="block">
                                    <?php the_post_thumbnail('aqualuxe-medium', ['class' => 'w-full h-48 object-cover rounded-lg']); ?>
                                </a>
                            </div>
                        <?php endif; ?>
                        
                        <div class="post-content">
                            <header class="entry-header mb-4">
                                <?php the_title('<h2 class="entry-title text-xl font-semibold mb-2"><a href="' . esc_url(get_permalink()) . '" class="text-gray-900 hover:text-primary-600 transition-colors">', '</a></h2>'); ?>
                                
                                <div class="entry-meta text-sm text-gray-600 flex items-center space-x-4">
                                    <time datetime="<?php echo esc_attr(get_the_date('c')); ?>" class="published">
                                        <?php echo get_the_date(); ?>
                                    </time>
                                    <span class="author">
                                        <?php printf(esc_html__('by %s', 'aqualuxe'), get_the_author()); ?>
                                    </span>
                                </div>
                            </header>
                            
                            <div class="entry-summary mb-4">
                                <?php the_excerpt(); ?>
                            </div>
                            
                            <footer class="entry-footer">
                                <a href="<?php the_permalink(); ?>" class="btn btn-primary">
                                    <?php esc_html_e('Read More', 'aqualuxe'); ?>
                                </a>
                            </footer>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>
            
            <?php aqualuxe_posts_pagination(); ?>
            
        <?php else : ?>
            <?php get_template_part('templates/content', 'none'); ?>
        <?php endif; ?>
    </div>
</main>

<?php
get_sidebar();
get_footer();
