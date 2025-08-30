<?php
/**
 * The template for displaying single services
 *
 * @package AquaLuxe
 * @subpackage Modules/Services
 */

get_header();
?>

<div class="aqualuxe-container">
    <div class="aqualuxe-content-area">
        <main id="main" class="aqualuxe-main">
            <?php while (have_posts()) : the_post(); ?>
                <article id="service-<?php the_ID(); ?>" <?php post_class('aqualuxe-single-service'); ?>>
                    <?php
                    /**
                     * Hook: aqualuxe_before_single_service
                     *
                     * @hooked aqualuxe_service_header - 10
                     */
                    do_action('aqualuxe_before_single_service');
                    ?>

                    <div class="aqualuxe-single-service-header">
                        <h1 class="aqualuxe-single-service-title"><?php the_title(); ?></h1>
                    </div>

                    <div class="aqualuxe-single-service-content-wrapper">
                        <?php
                        /**
                         * Hook: aqualuxe_service_content
                         *
                         * @hooked aqualuxe_service_featured_image - 10
                         * @hooked aqualuxe_service_meta - 20
                         * @hooked aqualuxe_service_content - 30
                         * @hooked aqualuxe_service_booking_form - 40
                         */
                        do_action('aqualuxe_service_content');
                        ?>
                    </div>

                    <?php
                    /**
                     * Hook: aqualuxe_after_single_service
                     *
                     * @hooked aqualuxe_service_related_services - 10
                     * @hooked aqualuxe_service_navigation - 20
                     */
                    do_action('aqualuxe_after_single_service');
                    ?>
                </article>
            <?php endwhile; ?>
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