<?php
// Fallback index
get_header();
?>
<main id="primary" class="container mx-auto px-4 py-8">
  <?php if (have_posts()): while (have_posts()): the_post(); get_template_part('template-parts/content', get_post_type()); endwhile; the_posts_pagination(); else: ?>
    <p><?php esc_html_e('No content found.', 'aqualuxe'); ?></p>
  <?php endif; ?>
</main>
<?php get_footer(); ?>
