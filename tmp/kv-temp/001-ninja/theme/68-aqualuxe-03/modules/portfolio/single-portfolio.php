<?php
/**
 * Single Portfolio Item Template
 */
get_header();
?>
<main id="main" class="site-main single-portfolio">
    <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
        <article <?php post_class('portfolio-single'); ?>>
            <header class="portfolio-header">
                <h1 class="portfolio-title"><?php the_title(); ?></h1>
            </header>
            <div class="portfolio-gallery">
                <?php if ( has_post_thumbnail() ) : ?>
                    <div class="portfolio-main-image">
                        <?php the_post_thumbnail('large'); ?>
                    </div>
                <?php endif; ?>
                <?php
                $gallery = get_post_meta( get_the_ID(), 'portfolio_gallery', true );
                if ( is_array( $gallery ) ) :
                    foreach ( $gallery as $img_id ) :
                        $img_url = wp_get_attachment_image_url( $img_id, 'large' );
                        if ( $img_url ) : ?>
                            <a href="<?php echo esc_url( $img_url ); ?>" class="portfolio-lightbox">
                                <img src="<?php echo esc_url( $img_url ); ?>" alt="" />
                            </a>
                        <?php endif;
                    endforeach;
                endif;
                ?>
            </div>
            <div class="portfolio-content">
                <?php the_content(); ?>
            </div>
        </article>
    <?php endwhile; endif; ?>
</main>
<?php get_footer(); ?>
