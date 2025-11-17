<?php
/**
 * Homepage Featured Products Block
 *
 * @package AquaLuxe
 */

// Get args from template part
$args = $args ?? array();

// Default values
$defaults = array(
    'featured_products_title' => __( 'Featured Products', 'aqualuxe' ),
    'featured_products_subtitle' => __( 'Our most popular aquarium products', 'aqualuxe' ),
    'featured_products_count' => 4,
    'featured_products_selection' => 'featured',
    'featured_products_ids' => array(),
    'featured_products_button_text' => __( 'View All Products', 'aqualuxe' ),
    'featured_products_button_url' => home_url( '/shop/' ),
);

// Merge defaults with args
$args = wp_parse_args( $args, $defaults );

// Extract variables
$title = $args['featured_products_title'];
$subtitle = $args['featured_products_subtitle'];
$count = $args['featured_products_count'];
$selection = $args['featured_products_selection'];
$product_ids = $args['featured_products_ids'];
$button_text = $args['featured_products_button_text'];
$button_url = $args['featured_products_button_url'];

// Get products based on selection method
// In a real implementation, this would use WooCommerce functions to get actual products
// For demonstration, we'll use placeholder data
$products = array();

switch ( $selection ) {
    case 'featured':
        // Get featured products
        $products = array(
            array(
                'id' => 1,
                'title' => __( 'Blue Neon Tetra', 'aqualuxe' ),
                'price' => '$12.99',
                'image' => get_template_directory_uri() . '/assets/images/products/neon-tetra.jpg',
                'url' => home_url( '/product/blue-neon-tetra/' ),
            ),
            array(
                'id' => 2,
                'title' => __( 'Red Crystal Shrimp', 'aqualuxe' ),
                'price' => '$24.99',
                'image' => get_template_directory_uri() . '/assets/images/products/crystal-shrimp.jpg',
                'url' => home_url( '/product/red-crystal-shrimp/' ),
            ),
            array(
                'id' => 3,
                'title' => __( 'Platinum Angelfish', 'aqualuxe' ),
                'price' => '$34.99',
                'image' => get_template_directory_uri() . '/assets/images/products/angelfish.jpg',
                'url' => home_url( '/product/platinum-angelfish/' ),
            ),
            array(
                'id' => 4,
                'title' => __( 'Fancy Guppy', 'aqualuxe' ),
                'price' => '$8.99',
                'image' => get_template_directory_uri() . '/assets/images/products/guppy.jpg',
                'url' => home_url( '/product/fancy-guppy/' ),
            ),
        );
        break;
    
    case 'best_selling':
        // Get best selling products
        $products = array(
            array(
                'id' => 5,
                'title' => __( 'Betta Fish', 'aqualuxe' ),
                'price' => '$19.99',
                'image' => get_template_directory_uri() . '/assets/images/products/betta.jpg',
                'url' => home_url( '/product/betta-fish/' ),
            ),
            array(
                'id' => 6,
                'title' => __( 'Discus Fish', 'aqualuxe' ),
                'price' => '$49.99',
                'image' => get_template_directory_uri() . '/assets/images/products/discus.jpg',
                'url' => home_url( '/product/discus-fish/' ),
            ),
            array(
                'id' => 7,
                'title' => __( 'Corydoras Catfish', 'aqualuxe' ),
                'price' => '$14.99',
                'image' => get_template_directory_uri() . '/assets/images/products/corydoras.jpg',
                'url' => home_url( '/product/corydoras-catfish/' ),
            ),
            array(
                'id' => 8,
                'title' => __( 'Aquarium Plant Bundle', 'aqualuxe' ),
                'price' => '$29.99',
                'image' => get_template_directory_uri() . '/assets/images/products/plant-bundle.jpg',
                'url' => home_url( '/product/aquarium-plant-bundle/' ),
            ),
        );
        break;
    
    case 'newest':
        // Get newest products
        $products = array(
            array(
                'id' => 9,
                'title' => __( 'Premium Fish Food', 'aqualuxe' ),
                'price' => '$15.99',
                'image' => get_template_directory_uri() . '/assets/images/products/fish-food.jpg',
                'url' => home_url( '/product/premium-fish-food/' ),
            ),
            array(
                'id' => 10,
                'title' => __( 'Water Conditioner', 'aqualuxe' ),
                'price' => '$12.99',
                'image' => get_template_directory_uri() . '/assets/images/products/water-conditioner.jpg',
                'url' => home_url( '/product/water-conditioner/' ),
            ),
            array(
                'id' => 11,
                'title' => __( 'LED Aquarium Light', 'aqualuxe' ),
                'price' => '$79.99',
                'image' => get_template_directory_uri() . '/assets/images/products/led-light.jpg',
                'url' => home_url( '/product/led-aquarium-light/' ),
            ),
            array(
                'id' => 12,
                'title' => __( 'Aquarium Filter', 'aqualuxe' ),
                'price' => '$49.99',
                'image' => get_template_directory_uri() . '/assets/images/products/filter.jpg',
                'url' => home_url( '/product/aquarium-filter/' ),
            ),
        );
        break;
    
    case 'sale':
        // Get sale products
        $products = array(
            array(
                'id' => 13,
                'title' => __( 'Aquarium Heater', 'aqualuxe' ),
                'regular_price' => '$39.99',
                'price' => '$29.99',
                'image' => get_template_directory_uri() . '/assets/images/products/heater.jpg',
                'url' => home_url( '/product/aquarium-heater/' ),
                'sale' => true,
            ),
            array(
                'id' => 14,
                'title' => __( 'Aquarium Gravel', 'aqualuxe' ),
                'regular_price' => '$24.99',
                'price' => '$19.99',
                'image' => get_template_directory_uri() . '/assets/images/products/gravel.jpg',
                'url' => home_url( '/product/aquarium-gravel/' ),
                'sale' => true,
            ),
            array(
                'id' => 15,
                'title' => __( 'Aquarium Decorations', 'aqualuxe' ),
                'regular_price' => '$34.99',
                'price' => '$24.99',
                'image' => get_template_directory_uri() . '/assets/images/products/decorations.jpg',
                'url' => home_url( '/product/aquarium-decorations/' ),
                'sale' => true,
            ),
            array(
                'id' => 16,
                'title' => __( 'Aquarium Test Kit', 'aqualuxe' ),
                'regular_price' => '$29.99',
                'price' => '$19.99',
                'image' => get_template_directory_uri() . '/assets/images/products/test-kit.jpg',
                'url' => home_url( '/product/aquarium-test-kit/' ),
                'sale' => true,
            ),
        );
        break;
    
    case 'specific':
        // Get specific products by ID
        if ( ! empty( $product_ids ) ) {
            // In a real implementation, this would query WooCommerce for the specific products
            // For demonstration, we'll use placeholder data
            $all_products = array(
                1 => array(
                    'id' => 1,
                    'title' => __( 'Blue Neon Tetra', 'aqualuxe' ),
                    'price' => '$12.99',
                    'image' => get_template_directory_uri() . '/assets/images/products/neon-tetra.jpg',
                    'url' => home_url( '/product/blue-neon-tetra/' ),
                ),
                2 => array(
                    'id' => 2,
                    'title' => __( 'Red Crystal Shrimp', 'aqualuxe' ),
                    'price' => '$24.99',
                    'image' => get_template_directory_uri() . '/assets/images/products/crystal-shrimp.jpg',
                    'url' => home_url( '/product/red-crystal-shrimp/' ),
                ),
                3 => array(
                    'id' => 3,
                    'title' => __( 'Platinum Angelfish', 'aqualuxe' ),
                    'price' => '$34.99',
                    'image' => get_template_directory_uri() . '/assets/images/products/angelfish.jpg',
                    'url' => home_url( '/product/platinum-angelfish/' ),
                ),
                4 => array(
                    'id' => 4,
                    'title' => __( 'Fancy Guppy', 'aqualuxe' ),
                    'price' => '$8.99',
                    'image' => get_template_directory_uri() . '/assets/images/products/guppy.jpg',
                    'url' => home_url( '/product/fancy-guppy/' ),
                ),
                5 => array(
                    'id' => 5,
                    'title' => __( 'Betta Fish', 'aqualuxe' ),
                    'price' => '$19.99',
                    'image' => get_template_directory_uri() . '/assets/images/products/betta.jpg',
                    'url' => home_url( '/product/betta-fish/' ),
                ),
            );
            
            foreach ( $product_ids as $id ) {
                if ( isset( $all_products[ $id ] ) ) {
                    $products[] = $all_products[ $id ];
                }
            }
        }
        break;
}

// Limit products to the specified count
$products = array_slice( $products, 0, $count );
?>

<section class="aqualuxe-featured-products">
    <div class="aqualuxe-container">
        <div class="aqualuxe-section-header">
            <?php if ( ! empty( $title ) ) : ?>
                <h2 class="aqualuxe-section-title"><?php echo esc_html( $title ); ?></h2>
            <?php endif; ?>
            
            <?php if ( ! empty( $subtitle ) ) : ?>
                <p class="aqualuxe-section-subtitle"><?php echo esc_html( $subtitle ); ?></p>
            <?php endif; ?>
        </div>
        
        <div class="aqualuxe-products-grid">
            <?php foreach ( $products as $product ) : ?>
                <div class="aqualuxe-product">
                    <div class="aqualuxe-product-image">
                        <a href="<?php echo esc_url( $product['url'] ); ?>">
                            <img src="<?php echo esc_url( $product['image'] ); ?>" alt="<?php echo esc_attr( $product['title'] ); ?>" />
                        </a>
                        <?php if ( isset( $product['sale'] ) && $product['sale'] ) : ?>
                            <span class="aqualuxe-product-sale"><?php esc_html_e( 'Sale', 'aqualuxe' ); ?></span>
                        <?php endif; ?>
                    </div>
                    <h3 class="aqualuxe-product-title">
                        <a href="<?php echo esc_url( $product['url'] ); ?>"><?php echo esc_html( $product['title'] ); ?></a>
                    </h3>
                    <div class="aqualuxe-product-price">
                        <?php if ( isset( $product['regular_price'] ) ) : ?>
                            <del><?php echo esc_html( $product['regular_price'] ); ?></del>
                        <?php endif; ?>
                        <span><?php echo esc_html( $product['price'] ); ?></span>
                    </div>
                    <div class="aqualuxe-product-actions">
                        <a href="<?php echo esc_url( $product['url'] ); ?>" class="aqualuxe-button aqualuxe-button-small"><?php esc_html_e( 'View Product', 'aqualuxe' ); ?></a>
                        <a href="#" class="aqualuxe-button aqualuxe-button-small aqualuxe-button-outline aqualuxe-add-to-cart" data-product-id="<?php echo esc_attr( $product['id'] ); ?>"><?php esc_html_e( 'Add to Cart', 'aqualuxe' ); ?></a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <?php if ( ! empty( $button_text ) && ! empty( $button_url ) ) : ?>
            <div class="aqualuxe-section-footer">
                <a href="<?php echo esc_url( $button_url ); ?>" class="aqualuxe-button aqualuxe-button-secondary"><?php echo esc_html( $button_text ); ?></a>
            </div>
        <?php endif; ?>
    </div>
</section>