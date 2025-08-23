<?php
// Blog: care guides, aquascaping, news
get_header();
?>
<main id="main" class="site-main" role="main" itemscope itemtype="https://schema.org/Blog">
  <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
    <article <?php post_class(); ?> itemscope itemtype="https://schema.org/BlogPosting">
      <h2 itemprop="headline"><?php echo esc_html(get_the_title()); ?></h2>
      <div itemprop="articleBody"><?php echo wp_kses_post(get_the_content()); ?></div>
    </article>
  <?php endwhile; endif; ?>
</main>
<?php get_footer(); ?>
