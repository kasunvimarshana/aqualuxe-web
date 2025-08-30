<?php get_header(); ?>
<main id="primary" class="container mx-auto px-4 py-8">
  <?php if (have_posts()): while (have_posts()): the_post(); get_template_part('template-parts/content', get_post_type()); comments_template(); endwhile; endif; ?>
</main>
<?php get_footer(); ?>
