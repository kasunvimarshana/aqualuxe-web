<?php
/**
 * The template for displaying service archives
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

get_header();
?>

<main id="primary" class="site-main">
    <?php
    // Archive Header
    get_template_part('template-parts/components/archive-header', 'service');
    ?>

    <div class="container">
        <?php
        // Get service categories
        $service_categories = get_terms(array(
            'taxonomy'   => 'service_category',
            'hide_empty' => true,
        ));

        if (!empty($service_categories) && !is_wp_error($service_categories)) :
        ?>
        <div class="service-categories">
            <ul class="service-filter">
                <li class="active"><a href="#" data-filter="*"><?php echo esc_html__('All Services', 'aqualuxe'); ?></a></li>
                <?php foreach ($service_categories as $category) : ?>
                    <li><a href="#" data-filter=".<?php echo esc_attr('service-category-' . $category->term_id); ?>"><?php echo esc_html($category->name); ?></a></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>

        <?php if (have_posts()) : ?>
            <div class="services-grid">
                <div class="row">
                    <?php
                    /* Start the Loop */
                    while (have_posts()) :
                        the_post();

                        // Get service categories for filtering
                        $service_category_classes = '';
                        $service_terms = get_the_terms(get_the_ID(), 'service_category');
                        
                        if (!empty($service_terms) && !is_wp_error($service_terms)) {
                            foreach ($service_terms as $term) {
                                $service_category_classes .= ' service-category-' . $term->term_id;
                            }
                        }
                        ?>
                        <div class="col-lg-4 col-md-6 service-item<?php echo esc_attr($service_category_classes); ?>">
                            <div class="service-card">
                                <?php if (has_post_thumbnail()) : ?>
                                    <div class="service-image">
                                        <a href="<?php the_permalink(); ?>">
                                            <?php the_post_thumbnail('aqualuxe-service-thumbnail', array('class' => 'img-fluid')); ?>
                                        </a>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="service-content">
                                    <h3 class="service-title">
                                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                    </h3>
                                    
                                    <?php
                                    // Get service details
                                    $price = get_post_meta(get_the_ID(), 'service_price', true);
                                    $duration = get_post_meta(get_the_ID(), 'service_duration', true);
                                    ?>
                                    
                                    <div class="service-meta">
                                        <?php if (!empty($price)) : ?>
                                            <div class="service-price">
                                                <i class="fas fa-tag"></i>
                                                <span><?php echo esc_html(aqualuxe_format_price($price)); ?></span>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <?php if (!empty($duration)) : ?>
                                            <div class="service-duration">
                                                <i class="far fa-clock"></i>
                                                <span><?php echo esc_html($duration); ?></span>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="service-excerpt">
                                        <?php the_excerpt(); ?>
                                    </div>
                                    
                                    <div class="service-footer">
                                        <a href="<?php the_permalink(); ?>" class="btn btn-outline-primary btn-sm">
                                            <?php echo esc_html__('Learn More', 'aqualuxe'); ?>
                                        </a>
                                        
                                        <?php if (aqualuxe_is_woocommerce_active()) : ?>
                                            <?php
                                            // Get linked product ID
                                            $product_id = get_post_meta(get_the_ID(), 'service_product_id', true);
                                            
                                            if (!empty($product_id)) :
                                                $product = wc_get_product($product_id);
                                                
                                                if ($product && $product->is_purchasable()) :
                                                ?>
                                                <a href="<?php echo esc_url($product->add_to_cart_url()); ?>" class="btn btn-primary btn-sm add-to-cart">
                                                    <?php echo esc_html__('Book Now', 'aqualuxe'); ?>
                                                </a>
                                                <?php
                                                endif;
                                            endif;
                                            ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    endwhile;
                    ?>
                </div><!-- .row -->
            </div><!-- .services-grid -->

            <?php
            // Pagination
            aqualuxe_pagination();

        else :

            get_template_part('template-parts/content/content', 'none');

        endif;
        ?>
    </div><!-- .container -->
</main><!-- #primary -->

<?php
get_footer();