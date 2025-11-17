<?php
/**
 * Single Portfolio Item Template
 */
get_header();
if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
<main id="main" class="site-main portfolio-single">
    <article <?php post_class(); ?>>
        <header class="entry-header">
            <h1 class="entry-title"><?php the_title(); ?></h1>
        </header>
        <div class="portfolio-gallery">
            <?php if ( has_post_thumbnail() ) : ?>
                <a href="<?php echo esc_url( get_the_post_thumbnail_url( null, 'full' ) ); ?>" class="portfolio-lightbox">
                    <?php the_post_thumbnail( 'large' ); ?>
                </a>
            <?php endif; ?>
            <?php
            $gallery = get_post_meta( get_the_ID(), 'portfolio_gallery', true );
            if ( is_array( $gallery ) ) :
                foreach ( $gallery as $img_id ) :
                    $img_url = wp_get_attachment_image_url( $img_id, 'large' );
                    $full_url = wp_get_attachment_image_url( $img_id, 'full' );
                    if ( $img_url ) : ?>
                        <a href="<?php echo esc_url( $full_url ); ?>" class="portfolio-lightbox">
                            <img src="<?php echo esc_url( $img_url ); ?>" alt="" />
                        </a>
                    <?php endif;
                endforeach;
            endif;
            ?>
        </div>
        <div class="entry-content">
            <?php the_content(); ?>
        </div>
        <footer class="entry-footer">
            <?php the_terms( get_the_ID(), 'portfolio_category', '<span class="cat-links">', ', ', '</span>' ); ?>
            <?php the_terms( get_the_ID(), 'portfolio_tag', '<span class="tag-links">', ', ', '</span>' ); ?>
        </footer>
    </article>
</main>
<?php endwhile; endif;
get_footer();
