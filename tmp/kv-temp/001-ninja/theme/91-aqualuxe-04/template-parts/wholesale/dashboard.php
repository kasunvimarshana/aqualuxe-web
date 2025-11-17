<?php
/**
 * Wholesale Portal: Dashboard
 *
 * @package AquaLuxe
 */

$user = wp_get_current_user();
// In a real scenario, you'd query the 'wholesale_account' CPT associated with this user.
$account_tier = 'Gold Partner'; // Placeholder
?>

<div class="wholesale-dashboard">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-3xl font-bold"><?php printf( esc_html__( 'Welcome, %s', 'aqualuxe' ), esc_html( $user->display_name ) ); ?></h2>
            <p class="text-gray-600"><?php printf( esc_html__( 'You are a %s.', 'aqualuxe' ), esc_html( $account_tier ) ); ?></p>
        </div>
        <a href="<?php echo esc_url( wp_logout_url( get_permalink() ) ); ?>" class="text-sm text-red-600 hover:underline"><?php esc_html_e( 'Log Out', 'aqualuxe' ); ?></a>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
        <div class="bg-gray-100 p-6 rounded-lg text-center">
            <span class="text-4xl font-bold text-primary">12</span>
            <span class="block text-gray-600 mt-1"><?php esc_html_e( 'Open Orders', 'aqualuxe' ); ?></span>
        </div>
        <div class="bg-gray-100 p-6 rounded-lg text-center">
            <span class="text-4xl font-bold text-primary">98</span>
            <span class="block text-gray-600 mt-1"><?php esc_html_e( 'Total Orders', 'aqualuxe' ); ?></span>
        </div>
        <div class="bg-gray-100 p-6 rounded-lg text-center">
            <span class="text-4xl font-bold text-primary">5%</span>
            <span class="block text-gray-600 mt-1"><?php esc_html_e( 'Your Discount', 'aqualuxe' ); ?></span>
        </div>
    </div>

    <!-- Wholesale Product Grid -->
    <div>
        <h3 class="text-2xl font-bold mb-6"><?php esc_html_e( 'Wholesale Catalog', 'aqualuxe' ); ?></h3>
        <?php
        // Query for products, potentially a specific category for wholesale
        $wholesale_products_query = new WP_Query( array(
            'post_type' => 'product',
            'posts_per_page' => 12,
            // Add any specific wholesale category/tag queries here
        ) );

        if ( $wholesale_products_query->have_posts() ) : ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <?php while ( $wholesale_products_query->have_posts() ) : $wholesale_products_query->the_post(); ?>
                    <?php
                    // We can create a specific content-product-wholesale template part
                    // for different pricing display if needed.
                    get_template_part( 'template-parts/content', 'product' );
                    ?>
                <?php endwhile; ?>
            </div>
            <?php wp_reset_postdata(); ?>
        <?php else : ?>
            <p><?php esc_html_e( 'No wholesale products available at this time.', 'aqualuxe' ); ?></p>
        <?php endif; ?>
    </div>

</div>
