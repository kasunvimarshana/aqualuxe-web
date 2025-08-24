<?php
/**
 * Services Page Grid Section
 * @package AquaLuxe
 */
?>
<section class="services-grid">
    <div class="container">
        <h2>Our Services</h2>
        <div class="service-list">
            <?php
            $services_query = new WP_Query([
                'post_type' => 'service',
                'posts_per_page' => 12,
                'orderby' => 'menu_order',
                'order' => 'ASC',
            ]);
            if ( $services_query->have_posts() ) :
                while ( $services_query->have_posts() ) : $services_query->the_post();
            ?>
                <div class="service-item">
                    <?php if ( has_post_thumbnail() ) : ?>
                        <div class="service-image"><?php the_post_thumbnail('medium'); ?></div>
                    <?php endif; ?>
                    <h3 class="service-title"><?php the_title(); ?></h3>
                    <div class="service-excerpt"><?php the_excerpt(); ?></div>
                    <?php if ( $price = get_post_meta(get_the_ID(), 'service_price', true) ) : ?>
                        <div class="service-price">From <?php echo esc_html( $price ); ?></div>
                    <?php endif; ?>
                    <a href="<?php the_permalink(); ?>" class="btn btn-secondary">View Details</a>
                </div>
            <?php
                endwhile;
                wp_reset_postdata();
            else:
            ?>
                <p>No services found.</p>
            <?php endif; ?>
        </div>
    </div>
</section>
