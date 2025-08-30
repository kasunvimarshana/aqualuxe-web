<?php get_header(); ?>
<main class="container mx-auto px-4 py-10">
  <?php if (have_posts()): while (have_posts()): the_post(); ?>
    <article class="prose dark:prose-invert max-w-none">
      <h1 class="text-4xl font-bold mb-4"><?php the_title(); ?></h1>
      <?php if (has_post_thumbnail()) { the_post_thumbnail('large', ['class' => 'rounded mb-6']); } ?>
      <?php the_content(); ?>
      <hr class="my-8" />
      <h2><?php esc_html_e('Request this service', 'aqualuxe'); ?></h2>
      <?php echo do_shortcode('[aqualuxe_contact]'); ?>
    </article>
  <?php endwhile; endif; ?>
</main>
<?php get_footer(); ?>
