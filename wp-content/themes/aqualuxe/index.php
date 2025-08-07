<?php
/**
 * The main template file
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

get_header(); ?>

<main id="content" class="site-main">

  <?php if (have_posts()) : ?>
    
    <?php if (is_home() && !is_front_page()) : ?>
      <header>
        <h1 class="page-title screen-reader-text"><?php single_post_title(); ?></h1>
      </header>
    <?php endif; ?>
    
    <?php
    // Start the loop
    while (have_posts()) : the_post();
      // Include the post content template
      get_template_part('template-parts/content', get_post_format());
    endwhile;
    
    // Previous/next page navigation
    the_posts_pagination(array(
      'prev_text' => esc_html__('Previous', 'aqualuxe'),
      'next_text' => esc_html__('Next', 'aqualuxe'),
      'before_page_number' => '<span class="meta-nav screen-reader-text">' . esc_html__('Page', 'aqualuxe') . ' </span>',
    ));
    
  else :
    // If no content, include the "No posts found" template
    get_template_part('template-parts/content', 'none');
  endif;
  ?>

</main><!-- #content -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>