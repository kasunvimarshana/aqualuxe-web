<?php
/**
 * The Template for displaying all single products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product.php.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package AquaLuxe
 * @version 1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

get_header( 'shop' );

/**
 * woocommerce_before_main_content hook.
 *
 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
 * @hooked woocommerce_breadcrumb - 20
 */
do_action( 'woocommerce_before_main_content' );

?>

<div class="product-container">
	<?php while ( have_posts() ) : ?>
		<?php the_post(); ?>

		<?php wc_get_template_part( 'content', 'single-product' ); ?>

	<?php endwhile; // end of the loop. ?>
</div>

<?php
/**
 * woocommerce_after_main_content hook.
 *
 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
 */
do_action( 'woocommerce_after_main_content' );

/**
 * woocommerce_sidebar hook.
 *
 * @hooked woocommerce_get_sidebar - 10
 */
do_action( 'woocommerce_sidebar' );

// Related products section with custom styling
$related_products = array();
if ( function_exists( 'wc_get_related_products' ) ) {
    $related_products = wc_get_related_products( get_the_ID(), 4 );
}

if ( $related_products ) : ?>
    <section class="related-products py-12 bg-gray-50">
        <div class="container mx-auto px-4">
            <h2 class="text-2xl font-bold mb-8"><?php esc_html_e( 'Related Products', 'aqualuxe' ); ?></h2>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <?php foreach ( $related_products as $related_product_id ) : ?>
                    <?php
                    $related_product = wc_get_product( $related_product_id );
                    if ( ! $related_product ) {
                        continue;
                    }
                    ?>
                    <div class="product-card bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow duration-300 overflow-hidden">
                        <a href="<?php echo esc_url( get_permalink( $related_product_id ) ); ?>" class="block relative">
                            <?php echo $related_product->get_image( 'woocommerce_thumbnail', array( 'class' => 'w-full h-auto' ) ); ?>
                            
                            <?php if ( $related_product->is_on_sale() ) : ?>
                                <span class="onsale absolute top-4 left-4 bg-red-500 text-white text-xs font-bold uppercase py-1 px-2 rounded-sm z-10">
                                    <?php esc_html_e( 'Sale', 'aqualuxe' ); ?>
                                </span>
                            <?php endif; ?>
                        </a>
                        
                        <div class="p-4">
                            <h3 class="text-lg font-medium mb-2">
                                <a href="<?php echo esc_url( get_permalink( $related_product_id ) ); ?>" class="text-gray-900 hover:text-primary">
                                    <?php echo esc_html( $related_product->get_name() ); ?>
                                </a>
                            </h3>
                            
                            <div class="mb-4">
                                <?php echo $related_product->get_price_html(); ?>
                            </div>
                            
                            <a href="<?php echo esc_url( $related_product->add_to_cart_url() ); ?>" class="button add_to_cart_button ajax_add_to_cart bg-primary hover:bg-primary-dark text-white py-2 px-4 rounded-md inline-block transition-colors duration-200" data-product_id="<?php echo esc_attr( $related_product_id ); ?>">
                                <?php esc_html_e( 'Add to cart', 'aqualuxe' ); ?>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
<?php endif; ?>

<?php
get_footer( 'shop' );
?>