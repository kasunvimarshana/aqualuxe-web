<?php
/**
 * The template for displaying service archives
 *
 * @package AquaLuxe
 * @subpackage Modules/Services
 */

get_header();
?>

<div class="aqualuxe-container">
    <div class="aqualuxe-content-area">
        <main id="main" class="aqualuxe-main">
            <div class="aqualuxe-services-archive">
                <?php
                /**
                 * Hook: aqualuxe_before_archive_services
                 *
                 * @hooked aqualuxe_services_archive_header - 10
                 * @hooked aqualuxe_services_archive_description - 20
                 * @hooked aqualuxe_services_archive_filters - 30
                 */
                do_action('aqualuxe_before_archive_services');
                ?>

                <?php if (have_posts()) : ?>
                    <?php
                    // Get module instance
                    global $aqualuxe_theme;
                    $module = isset($aqualuxe_theme->modules['services']) ? $aqualuxe_theme->modules['services'] : null;

                    // Get layout
                    $layout = $module ? $module->get_option('layout', 'grid') : 'grid';
                    ?>

                    <div class="aqualuxe-services-layout-<?php echo esc_attr($layout); ?>">
                        <div class="aqualuxe-services-grid">
                            <?php while (have_posts()) : the_post(); ?>
                                <div class="aqualuxe-service">
                                    <div class="aqualuxe-service-inner">
                                        <?php if (has_post_thumbnail() && $module && $module->get_option('featured_image', true)) : ?>
                                            <div class="aqualuxe-service-image">
                                                <a href="<?php the_permalink(); ?>">
                                                    <?php the_post_thumbnail('aqualuxe-card'); ?>
                                                </a>
                                            </div>
                                        <?php endif; ?>

                                        <div class="aqualuxe-service-content">
                                            <h3 class="aqualuxe-service-title">
                                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                            </h3>

                                            <div class="aqualuxe-service-meta">
                                                <?php
                                                // Get service meta
                                                $price = get_post_meta(get_the_ID(), '_service_price', true);
                                                $sale_price = get_post_meta(get_the_ID(), '_service_sale_price', true);
                                                $price_type = get_post_meta(get_the_ID(), '_service_price_type', true);
                                                $duration = get_post_meta(get_the_ID(), '_service_duration', true);
                                                $location = get_post_meta(get_the_ID(), '_service_location', true);
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

                                                <?php if ($location) : ?>
                                                    <div class="aqualuxe-service-location">
                                                        <span class="aqualuxe-service-location-icon"></span>
                                                        <span class="aqualuxe-service-location-text"><?php echo esc_html($location); ?></span>
                                                    </div>
                                                <?php endif; ?>
                                            </div>

                                            <div class="aqualuxe-service-excerpt">
                                                <?php the_excerpt(); ?>
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
                    /**
                     * Hook: aqualuxe_after_archive_services
                     *
                     * @hooked aqualuxe_services_archive_pagination - 10
                     */
                    do_action('aqualuxe_after_archive_services');
                    ?>

                <?php else : ?>
                    <p><?php esc_html_e('No services found.', 'aqualuxe'); ?></p>
                <?php endif; ?>
            </div>
        </main>

        <?php
        // Get module instance
        global $aqualuxe_theme;
        $module = isset($aqualuxe_theme->modules['services']) ? $aqualuxe_theme->modules['services'] : null;

        // Show sidebar if enabled
        if ($module && $module->get_option('sidebar', true)) {
            get_sidebar('services');
        }
        ?>
    </div>
</div>

<?php
get_footer();