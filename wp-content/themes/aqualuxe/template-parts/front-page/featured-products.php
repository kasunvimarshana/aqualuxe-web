<section class="featured-products-section py-16">
    <div class="container mx-auto text-center">
        <h2 class="text-3xl font-bold mb-8">Featured Products</h2>
        <?php
        $args = array(
            'post_type' => 'product',
            'posts_per_page' => 4,
            'tax_query' => array(
                array(
                    'taxonomy' => 'product_visibility',
                    'field'    => 'name',
                    'terms'    => 'featured',
                ),
            ),
        );
        $featured_query = new WP_Query( $args );
        if ( $featured_query->have_posts() ) :
            echo '<ul class="products grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-8">';
            while ( $featured_query->have_posts() ) : $featured_query->the_post();
                wc_get_template_part( 'content', 'product' );
            endwhile;
            echo '</ul>';
        endif;
        wp_reset_postdata();
        ?>
    </div>
</section>
