<?php get_header(); ?>

<section class="container mx-auto px-4 py-10">
  <h1 class="text-3xl font-semibold mb-6"><?php bloginfo('name'); ?></h1>
  <?php if (have_posts()) : ?>
    <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
    <?php while (have_posts()) : the_post(); ?>
      <article <?php post_class('rounded-lg border border-slate-200/70 dark:border-slate-800/80 p-5'); ?> itemscope itemtype="https://schema.org/Article">
        <a href="<?php the_permalink(); ?>" class="block focus:outline-none focus:ring-2 focus:ring-sky-500">
          <h2 class="text-xl font-semibold mb-2" itemprop="headline"><?php the_title(); ?></h2>
        </a>
        <div class="prose dark:prose-invert max-w-none" itemprop="articleBody">
          <?php the_excerpt(); ?>
        </div>
      </article>
    <?php endwhile; ?>
    </div>
    <div class="mt-8">
      <?php the_posts_pagination(['mid_size' => 2]); ?>
    </div>
  <?php else: ?>
    <p><?php esc_html_e('No content found.', 'aqualuxe'); ?></p>
  <?php endif; ?>
</section>

<?php get_footer(); ?>
