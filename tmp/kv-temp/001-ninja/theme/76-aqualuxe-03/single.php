<?php defined('ABSPATH') || exit; get_header(); ?>
<div class="<?php echo esc_attr(aqlx_container_class()); ?> py-8">
  <?php while (have_posts()) : the_post(); ?>
    <article <?php post_class('prose dark:prose-invert max-w-none'); ?>>
      <h1 class="text-3xl font-bold mb-4"><?php the_title(); ?></h1>
      <?php aqlx_posted_on(); ?>
      <div class="entry-content"><?php the_content(); ?></div>
      <?php wp_link_pages(); ?>
    </article>
    <?php comments_template(); ?>
  <?php endwhile; ?>
</div>
<?php get_footer(); ?>
