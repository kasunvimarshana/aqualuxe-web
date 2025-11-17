<?php
/**
 * AquaLuxe FAQ Page Template
 */
get_header();
?>
<main id="main" class="site-main faq-page">
    <section class="faq-list">
        <h1>Frequently Asked Questions</h1>
        <?php
        $faqs = new WP_Query([
            'post_type' => 'aqualuxe_faq',
            'posts_per_page' => -1
        ]);
        if ( $faqs->have_posts() ) :
            while ( $faqs->have_posts() ) : $faqs->the_post(); ?>
                <article <?php post_class('faq-item'); ?>>
                    <h2><?php the_title(); ?></h2>
                    <div class="faq-content"><?php the_content(); ?></div>
                </article>
            <?php endwhile;
            wp_reset_postdata();
        endif;
        ?>
    </section>
</main>
<?php get_footer(); ?>
