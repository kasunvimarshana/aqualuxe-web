<section class="hero-section bg-gray-800 text-white text-center py-20">
    <div class="container mx-auto">
        <h1 class="text-5xl font-bold mb-4"><?php echo esc_html( get_bloginfo( 'name' ) ); ?></h1>
        <p class="text-xl mb-8"><?php echo esc_html( get_bloginfo( 'description' ) ); ?></p>
        <a href="<?php echo esc_url( get_permalink( get_option( 'woocommerce_shop_page_id' ) ) ); ?>" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            <?php esc_html_e( 'Shop Now', 'aqualuxe' ); ?>
        </a>
    </div>
</section>
