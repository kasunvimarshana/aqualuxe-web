<?php
/**
 * Template part for displaying featured products
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Display featured products
 *
 * @param array $args {
 *     Optional. Arguments to customize the featured products section.
 *
 *     @type string  $title           Section title.
 *     @type string  $subtitle        Section subtitle.
 *     @type string  $description     Section description.
 *     @type int     $number          Number of products to display.
 *     @type string  $columns         Number of columns (2-6).
 *     @type string  $orderby         Order by parameter (date, price, popularity, rating).
 *     @type string  $order           Order parameter (ASC or DESC).
 *     @type boolean $featured_only   Whether to show only featured products.
 *     @type boolean $sale_only       Whether to show only sale products.
 *     @type boolean $best_selling    Whether to show only best selling products.
 *     @type boolean $show_categories Whether to show product categories.
 *     @type string  $category        Specific category slug to display products from.
 *     @type string  $view_more_text  Text for the view more button.
 *     @type string  $view_more_url   URL for the view more button.
 * }
 */

// Check if WooCommerce is active
if ( ! aqualuxe_is_woocommerce_active() ) {
    return;
}

$defaults = array(
    'title'           => esc_html__( 'Featured Products', 'aqualuxe' ),
    'subtitle'        => '',
    'description'     => '',
    'number'          => 4,
    'columns'         => 4,
    'orderby'         => 'date',
    'order'           => 'DESC',
    'featured_only'   => false,
    'sale_only'       => false,
    'best_selling'    => false,
    'show_categories' => true,
    'category'        => '',
    'view_more_text'  => esc_html__( 'View All Products', 'aqualuxe' ),
    'view_more_url'   => get_permalink( wc_get_page_id( 'shop' ) ),
);

$args = wp_parse_args( $args, $defaults );

// Set columns class
$columns_class = 'grid-cols-1 sm:grid-cols-2';
switch ( $args['columns'] ) {
    case '2':
        $columns_class = 'grid-cols-1 sm:grid-cols-2';
        break;
    case '3':
        $columns_class = 'grid-cols-1 sm:grid-cols-2 md:grid-cols-3';
        break;
    case '4':
        $columns_class = 'grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4';
        break;
    case '5':
        $columns_class = 'grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5';
        break;
    case '6':
        $columns_class = 'grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6';
        break;
}

// Set up query arguments
$query_args = array(
    'post_type'           => 'product',
    'post_status'         => 'publish',
    'ignore_sticky_posts' => 1,
    'posts_per_page'      => $args['number'],
    'orderby'             => $args['orderby'],
    'order'               => $args['order'],
);

// Handle orderby parameter
switch ( $args['orderby'] ) {
    case 'price':
        $query_args['meta_key'] = '_price';
        $query_args['orderby']  = 'meta_value_num';
        break;
    case 'popularity':
        $query_args['meta_key'] = 'total_sales';
        $query_args['orderby']  = 'meta_value_num';
        break;
    case 'rating':
        $query_args['meta_key'] = '_wc_average_rating';
        $query_args['orderby']  = 'meta_value_num';
        break;
}

// Handle featured products
if ( $args['featured_only'] ) {
    $query_args['tax_query'][] = array(
        'taxonomy' => 'product_visibility',
        'field'    => 'name',
        'terms'    => 'featured',
        'operator' => 'IN',
    );
}

// Handle sale products
if ( $args['sale_only'] ) {
    $product_ids_on_sale = wc_get_product_ids_on_sale();
    $query_args['post__in'] = $product_ids_on_sale;
}

// Handle best selling products
if ( $args['best_selling'] ) {
    $query_args['meta_key'] = 'total_sales';
    $query_args['orderby']  = 'meta_value_num';
    $query_args['order']    = 'DESC';
}

// Handle category filter
if ( ! empty( $args['category'] ) ) {
    $query_args['tax_query'][] = array(
        'taxonomy' => 'product_cat',
        'field'    => 'slug',
        'terms'    => $args['category'],
    );
}

// Run the query
$products = new WP_Query( $query_args );
?>

<?php if ( $products->have_posts() ) : ?>
    <section class="featured-products py-12 md:py-16">
        <div class="container mx-auto px-4">
            <div class="section-header text-center mb-8 md:mb-12">
                <?php if ( $args['subtitle'] ) : ?>
                    <div class="section-subtitle mb-2">
                        <span class="inline-block px-4 py-1 bg-primary text-white text-sm font-medium rounded-full">
                            <?php echo esc_html( $args['subtitle'] ); ?>
                        </span>
                    </div>
                <?php endif; ?>
                
                <?php if ( $args['title'] ) : ?>
                    <h2 class="section-title text-2xl md:text-3xl lg:text-4xl font-serif font-bold mb-4">
                        <?php echo esc_html( $args['title'] ); ?>
                    </h2>
                <?php endif; ?>
                
                <?php if ( $args['description'] ) : ?>
                    <div class="section-description max-w-3xl mx-auto text-gray-600 dark:text-gray-400">
                        <?php echo wp_kses_post( $args['description'] ); ?>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="products grid <?php echo esc_attr( $columns_class ); ?> gap-6">
                <?php while ( $products->have_posts() ) : $products->the_post(); ?>
                    <?php wc_get_template_part( 'content', 'product' ); ?>
                <?php endwhile; ?>
            </div>
            
            <?php if ( $args['view_more_text'] && $args['view_more_url'] ) : ?>
                <div class="section-footer text-center mt-8 md:mt-12">
                    <a href="<?php echo esc_url( $args['view_more_url'] ); ?>" class="inline-block px-6 py-3 bg-primary hover:bg-primary-dark text-white rounded-lg font-medium transition-colors duration-300">
                        <?php echo esc_html( $args['view_more_text'] ); ?>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </section>
<?php endif; ?>

<?php wp_reset_postdata(); ?>