<?php get_header(); ?>
<main id="primary" class="container mx-auto px-4 py-8">
  <header class="mb-6">
    <h1 class="text-3xl font-semibold"><?php bloginfo('name'); ?></h1>
    <p class="text-slate-500"><?php bloginfo('description'); ?></p>
  </header>
  <?php if (have_posts()): ?>
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
    <?php while (have_posts()): the_post(); ?>
      <article id="post-<?php the_ID(); ?>" <?php post_class('card p-4 bg-white dark:bg-slate-800 rounded-lg'); ?>>
        <a href="<?php the_permalink(); ?>" class="block mb-3"><?php if (has_post_thumbnail()) the_post_thumbnail('aqlx-card', ['class'=>'rounded-md w-full h-auto','loading'=>'lazy']); ?></a>
        <h2 class="text-xl font-semibold mb-2"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
        <div class="prose dark:prose-invert"><?php the_excerpt(); ?></div>
      </article>
    <?php endwhile; ?>
    </div>
    <div class="mt-8"><?php the_posts_pagination(); ?></div>
  <?php else: ?>
    <p><?php esc_html_e('No content found.', 'aqualuxe'); ?></p>
  <?php endif; ?>
</main>
<?php get_footer(); ?>
