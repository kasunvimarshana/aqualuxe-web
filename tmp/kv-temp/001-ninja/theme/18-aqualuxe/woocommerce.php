<?php
/**
 * The template for displaying WooCommerce content
 *
 * This is the template that displays all WooCommerce pages by default.
 *
 * @link https://docs.woocommerce.com/document/third-party-custom-theme-compatibility/
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>

<main id="primary" class="site-main py-12">
    <div class="container-fluid">
        <?php
        // Breadcrumbs
        if (function_exists('aqualuxe_breadcrumbs')) :
            aqualuxe_breadcrumbs();
        endif;
        ?>

        <div class="flex flex-col lg:flex-row">
            <?php
            // Show sidebar on shop and product archive pages, but not on single product pages
            $show_sidebar = is_shop() || is_product_category() || is_product_tag();
            $content_width = $show_sidebar ? 'lg:w-3/4 lg:pr-8' : 'w-full';
            ?>

            <div class="<?php echo esc_attr($content_width); ?>">
                <?php woocommerce_content(); ?>
            </div>

            <?php if ($show_sidebar) : ?>
                <div class="lg:w-1/4 mt-8 lg:mt-0">
                    <?php
                    /**
                     * Hook: woocommerce_sidebar.
                     *
                     * @hooked woocommerce_get_sidebar - 10
                     */
                    do_action('woocommerce_sidebar');
                    ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main><!-- #main -->

<?php
get_footer();