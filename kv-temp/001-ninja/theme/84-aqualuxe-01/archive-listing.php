<?php
/**
 * Archive template for Listings
 * @package Aqualuxe
 */
get_header(); ?>

<main id="primary" class="site-main" role="main">
    <header class="page-header container">
        <h1><?php post_type_archive_title(); ?></h1>
        <?php get_search_form(); ?>
    </header>
    <div class="container">
        <?php if ( have_posts() ) : ?>
            <div class="posts">
            <?php while ( have_posts() ) : the_post();
                get_template_part( 'template-parts/content', 'listing' );
            endwhile; ?>
            </div>
            <?php the_posts_pagination(); ?>
        <?php else : ?>
            <p><?php esc_html_e( 'No listings found.', 'aqualuxe' ); ?></p>
        <?php endif; ?>
    </div>
</main>

<?php get_footer();
