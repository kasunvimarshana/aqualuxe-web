<?php if (! defined('ABSPATH')) { exit; }
get_header(); ?>
<section class="container mx-auto px-4 py-10">
    <?php if (have_posts()) : ?>
        <div class="prose dark:prose-invert max-w-none">
            <?php while (have_posts()) : the_post(); ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class('mb-12'); ?>>
                    <h1 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
                    <div class="entry-content"><?php the_excerpt(); ?></div>
                </article>
            <?php endwhile; ?>
            <div class="mt-8"><?php the_posts_pagination(); ?></div>
        </div>
    <?php else : ?>
        <p><?php esc_html_e('No content found.', 'aqualuxe'); ?></p>
    <?php endif; ?>
</section>
<?php get_footer();
