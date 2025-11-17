<?php
/**
 * The template for displaying search results pages
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

get_header(); ?>

<main id="primary" class="site-main">

  <?php if (have_posts()) : ?>
    
    <header class="page-header">
      <h1 class="page-title">
        <?php
        /* translators: %s: search query. */
        printf(esc_html__('Search Results for: %s', 'aqualuxe'), '<span>' . get_search_query() . '</span>');
        ?>
      </h1>
    </header><!-- .page-header -->
    
    <?php
    // Start the Loop
    while (have_posts()) :
      the_post();
      
      // Include the post content template
      get_template_part('template-parts/content', 'search');
      
    endwhile;
    
    the_posts_navigation();
    
  else :
    
    get_template_part('template-parts/content', 'none');
    
  endif;
  ?>

</main><!-- #main -->

<?php
get_sidebar();
get_footer();