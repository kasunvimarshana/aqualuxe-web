<section class="featured-section py-20">
    <div class="container mx-auto text-center">
        <h2 class="text-3xl font-bold mb-8"><?php esc_html_e( 'Featured Products', 'aqualuxe' ); ?></h2>
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
                echo '<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">';
                while ( $featured_query->have_posts() ) : $featured_query->the_post();
                    wc_get_template_part( 'content', 'product' );
                endwhile;
                echo '</div>';
            else :
                echo '<p>' . esc_html__( 'No featured products found.', 'aqualuxe' ) . '</p>';
            endif;
            wp_reset_postdata();
        ?>
    </div>
</section>
