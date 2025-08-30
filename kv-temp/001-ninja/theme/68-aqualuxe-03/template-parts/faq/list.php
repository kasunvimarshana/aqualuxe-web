<?php
/**
 * FAQ Page List Section
 * @package AquaLuxe
 */
?>
<section class="faq-list">
    <div class="container">
        <h2>FAQs</h2>
        <div class="faq-items">
            <?php
            $faq_query = new WP_Query([
                'post_type' => 'faq',
                'posts_per_page' => 20,
                'orderby' => 'menu_order',
                'order' => 'ASC',
            ]);
            if ( $faq_query->have_posts() ) :
                while ( $faq_query->have_posts() ) : $faq_query->the_post();
            ?>
                <div class="faq-item">
                    <h3 class="faq-question"><?php the_title(); ?></h3>
                    <div class="faq-answer"><?php the_content(); ?></div>
                </div>
            <?php
                endwhile;
                wp_reset_postdata();
            else:
            ?>
                <p>No FAQs found.</p>
            <?php endif; ?>
        </div>
    </div>
</section>
