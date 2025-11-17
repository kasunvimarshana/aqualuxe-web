<?php
/**
 * Single service related services template part
 *
 * @package AquaLuxe
 * @subpackage Modules/Services
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Get related services
$related_services = aqualuxe_get_related_services(get_the_ID(), 3);

// Check if we have related services
if ($related_services->have_posts()) :
?>
<div class="aqualuxe-single-service-related-services">
    <h3 class="aqualuxe-single-service-related-title"><?php esc_html_e('Related Services', 'aqualuxe'); ?></h3>

    <div class="aqualuxe-services-grid">
        <?php while ($related_services->have_posts()) : $related_services->the_post(); ?>
            <div class="aqualuxe-service">
                <div class="aqualuxe-service-inner">
                    <?php if (has_post_thumbnail()) : ?>
                        <div class="aqualuxe-service-image">
                            <a href="<?php the_permalink(); ?>">
                                <?php the_post_thumbnail('aqualuxe-card'); ?>
                            </a>
                        </div>
                    <?php endif; ?>

                    <div class="aqualuxe-service-content">
                        <h4 class="aqualuxe-service-title">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h4>

                        <div class="aqualuxe-service-meta">
                            <?php
                            // Get service meta
                            $price = get_post_meta(get_the_ID(), '_service_price', true);
                            $sale_price = get_post_meta(get_the_ID(), '_service_sale_price', true);
                            $price_type = get_post_meta(get_the_ID(), '_service_price_type', true);
                            $duration = get_post_meta(get_the_ID(), '_service_duration', true);
                            ?>

                            <?php if ($price) : ?>
                                <div class="aqualuxe-service-price">
                                    <?php if ($sale_price) : ?>
                                        <del><?php echo esc_html(aqualuxe_format_price($price)); ?></del>
                                        <ins><?php echo esc_html(aqualuxe_format_price($sale_price)); ?></ins>
                                    <?php else : ?>
                                        <?php echo esc_html(aqualuxe_format_price($price)); ?>
                                    <?php endif; ?>

                                    <?php if ($price_type) : ?>
                                        <span class="aqualuxe-service-price-type"><?php echo esc_html($price_type); ?></span>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>

                            <?php if ($duration) : ?>
                                <div class="aqualuxe-service-duration">
                                    <span class="aqualuxe-service-duration-icon"></span>
                                    <span class="aqualuxe-service-duration-text"><?php echo esc_html($duration); ?></span>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="aqualuxe-service-button">
                            <a href="<?php the_permalink(); ?>" class="button"><?php esc_html_e('View Details', 'aqualuxe'); ?></a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>
<?php
// Reset post data
wp_reset_postdata();
endif;