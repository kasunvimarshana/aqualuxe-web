<?php
/**
 * Single Portfolio Item Template
 */
if ( ! defined( 'ABSPATH' ) ) exit;

global $post;

get_header();
?>
<main id="main" class="site-main portfolio-single">
    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <header class="entry-header">
            <h1 class="entry-title"><?php the_title(); ?></h1>
        </header>
        <div class="entry-content">
            <?php the_content(); ?>
            <?php if ( has_post_thumbnail() ) : ?>
                <div class="portfolio-gallery">
                    <?php the_post_thumbnail( 'large' ); ?>
                </div>
            <?php endif; ?>
        </div>
        <footer class="entry-footer">
            <span class="portfolio-categories">
                <?php echo get_the_term_list( $post->ID, 'portfolio_category', '', ', ' ); ?>
            </span>
            <span class="portfolio-tags">
                <?php echo get_the_term_list( $post->ID, 'portfolio_tag', '', ', ' ); ?>
            </span>
        </footer>
    </article>
</main>
<?php get_footer(); ?>
