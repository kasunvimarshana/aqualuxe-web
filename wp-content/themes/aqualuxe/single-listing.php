<?php
/** Single Listing template */
get_header(); ?>
<main id="primary" class="site-main container" role="main">
<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?> role="article">
        <header class="entry-header"><h1><?php the_title(); ?></h1></header>
        <div class="entry-content"><?php the_content(); ?></div>
    </article>
<?php endwhile; endif; ?>
</main>
<?php get_footer();
