<?php
/**
 * AquaLuxe Services Page Template
 */
get_header();
?>
<main id="main" class="site-main services-page">
    <section class="services-list">
        <h1>Our Services</h1>
        <?php
        $services = new WP_Query([
            'post_type' => 'aqualuxe_service',
            'posts_per_page' => -1
        ]);
        if ( $services->have_posts() ) :
            while ( $services->have_posts() ) : $services->the_post(); ?>
                <article <?php post_class('service-item'); ?>>
                    <h2><?php the_title(); ?></h2>
                    <div class="service-content"><?php the_content(); ?></div>
                </article>
            <?php endwhile;
            wp_reset_postdata();
        endif;
        ?>
    </section>
</main>
<?php get_footer(); ?>
