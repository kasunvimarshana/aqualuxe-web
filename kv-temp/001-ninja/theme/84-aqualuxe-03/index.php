<?php
/**
 * Main index template.
 *
 * Progressive enhancement: Works with or without JS.
 *
 * @package Aqualuxe
 */

get_header();
?>

<main id="primary" class="site-main" role="main">
    <?php if ( have_posts() ) : ?>
        <div class="posts" data-enhanced="true">
            <?php while ( have_posts() ) : the_post(); ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?> role="article">
                    <header class="entry-header">
                        <?php the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '">', '</a></h2>' ); ?>
                    </header>
                    <div class="entry-summary">
                        <?php the_excerpt(); ?>
                    </div>
                </article>
            <?php endwhile; ?>
        </div>

        <?php the_posts_pagination(); ?>
    <?php else : ?>
        <p><?php esc_html_e( 'No content found.', 'aqualuxe' ); ?></p>
    <?php endif; ?>
</main>

<?php get_footer();
