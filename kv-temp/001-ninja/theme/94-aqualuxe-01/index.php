<?php if (!defined('ABSPATH')) { exit; } get_header(); ?>
<main id="main" class="site_main" role="main">
  <?php if (have_posts()): ?>
    <div class="container mx-auto px-4 py-8">
      <h1 class="sr-only"><?php bloginfo('name'); ?></h1>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <?php while (have_posts()): the_post(); ?>
          <article id="post-<?php the_ID(); ?>" <?php post_class('card bg-white dark:bg-slate-800 rounded-lg shadow'); ?> itemscope itemtype="https://schema.org/BlogPosting">
            <?php if (has_post_thumbnail()): ?>
              <a href="<?php the_permalink(); ?>" class="block aspect-video overflow-hidden rounded-t-lg">
                <?php the_post_thumbnail('card', ['loading' => 'lazy', 'class' => 'w-full h-full object-cover']); ?>
              </a>
            <?php endif; ?>
            <div class="p-4">
              <h2 class="text-xl font-semibold mb-2"><a href="<?php the_permalink(); ?>" class="hover:underline" itemprop="headline"><?php the_title(); ?></a></h2>
              <p class="text-slate-600 dark:text-slate-300"><?php echo wp_kses_post(get_the_excerpt()); ?></p>
            </div>
          </article>
        <?php endwhile; ?>
      </div>
      <nav class="mt-8" aria-label="Pagination"><?php the_posts_pagination(); ?></nav>
    </div>
  <?php else: ?>
    <div class="container mx-auto px-4 py-16 text-center">
      <p><?php esc_html_e('No content found.', 'aqualuxe'); ?></p>
    </div>
  <?php endif; ?>
</main>
<?php get_footer(); ?>
