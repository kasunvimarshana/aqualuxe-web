<?php
/**
 * Portfolio Archive Template
 */
if ( ! defined( 'ABSPATH' ) ) exit;

get_header();
?>
<main id="main" class="site-main portfolio-archive">
    <header class="page-header">
        <h1 class="page-title"><?php _e( 'Portfolio', 'aqualuxe' ); ?></h1>
    </header>
    <div class="portfolio-grid">
        <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class( 'portfolio-item' ); ?>>
                <a href="<?php the_permalink(); ?>">
                    <?php if ( has_post_thumbnail() ) : ?>
                        <?php the_post_thumbnail( 'medium' ); ?>
                    <?php endif; ?>
                    <h2 class="portfolio-title"><?php the_title(); ?></h2>
                </a>
            </article>
        <?php endwhile; else: ?>
            <p><?php _e( 'No portfolio items found.', 'aqualuxe' ); ?></p>
        <?php endif; ?>
    </div>
</main>
<?php get_footer(); ?>
