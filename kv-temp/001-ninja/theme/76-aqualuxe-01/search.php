<?php defined('ABSPATH') || exit; get_header(); ?>
<div class="<?php echo esc_attr(aqlx_container_class()); ?> py-8">
  <h1 class="text-3xl font-bold mb-6"><?php printf(esc_html__('Search results for "%s"', 'aqualuxe'), get_search_query()); ?></h1>
  <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
    <article <?php post_class('border-b py-4'); ?>>
      <h2 class="text-xl font-semibold"><a href="<?php the_permalink(); ?>" class="hover:underline"><?php the_title(); ?></a></h2>
      <div class="entry-summary"><?php the_excerpt(); ?></div>
    </article>
  <?php endwhile; the_posts_pagination(); else: ?>
    <p><?php esc_html_e('No results found.', 'aqualuxe'); ?></p>
  <?php endif; ?>
</div>
<?php get_footer(); ?>
