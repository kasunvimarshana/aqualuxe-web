<?php
/**
 * Template part for displaying the fish catalog section on the homepage
 *
 * @package AquaLuxe
 */

// Get section settings from theme options or use defaults
$section_title = aqualuxe_get_option( 'fish_catalog_title', 'Fish Catalog' );
$section_subtitle = aqualuxe_get_option( 'fish_catalog_subtitle', 'Explore our diverse collection of ornamental fish' );
$fish_count = aqualuxe_get_option( 'fish_catalog_count', 6 );
$show_view_all = aqualuxe_get_option( 'fish_catalog_show_view_all', true );
$view_all_text = aqualuxe_get_option( 'fish_catalog_view_all_text', 'View Full Catalog' );
$catalog_layout = aqualuxe_get_option( 'fish_catalog_layout', 'grid' );

// Check if we have a custom fish catalog page
$catalog_page_id = aqualuxe_get_option( 'fish_catalog_page_id', 0 );
$view_all_url = $catalog_page_id ? get_permalink( $catalog_page_id ) : '#';

// Check if the custom post type exists
$post_type = 'fish';
if ( ! post_type_exists( $post_type ) ) {
    // Fallback to products if WooCommerce is active
    if ( class_exists( 'WooCommerce' ) ) {
        $post_type = 'product';
        $fish_category = aqualuxe_get_option( 'fish_catalog_category', '' );
        
        // If no catalog page is set, use the shop page
        if ( ! $catalog_page_id ) {
            $view_all_url = get_permalink( wc_get_page_id( 'shop' ) );
            
            // If a category is set, link to that category
            if ( $fish_category ) {
                $term = get_term_by( 'slug', $fish_category, 'product_cat' );
                if ( $term ) {
                    $view_all_url = get_term_link( $term );
                }
            }
        }
    } else {
        // Fallback to regular posts with a specific category
        $post_type = 'post';
        $fish_category = aqualuxe_get_option( 'fish_catalog_category', '' );
    }
}

// Set up the query arguments
$args = array(
    'post_type'      => $post_type,
    'posts_per_page' => $fish_count,
    'orderby'        => 'date',
    'order'          => 'DESC',
    'post_status'    => 'publish',
);

// Add category filter if needed
if ( $post_type === 'product' && ! empty( $fish_category ) ) {
    $args['tax_query'] = array(
        array(
            'taxonomy' => 'product_cat',
            'field'    => 'slug',
            'terms'    => $fish_category,
        ),
    );
} elseif ( $post_type === 'post' && ! empty( $fish_category ) ) {
    $args['category_name'] = $fish_category;
}

// Run the query
$fish_query = new WP_Query( $args );

// Only display the section if we have fish
if ( ! $fish_query->have_posts() ) {
    return;
}

// Set up grid columns class
$grid_columns_class = '';
switch ( $fish_count ) {
    case 3:
        $grid_columns_class = 'grid-cols-1 md:grid-cols-3';
        break;
    case 4:
        $grid_columns_class = 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-4';
        break;
    case 6:
        $grid_columns_class = 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-3';
        break;
    case 8:
        $grid_columns_class = 'grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4';
        break;
    case 9:
        $grid_columns_class = 'grid-cols-1 sm:grid-cols-2 md:grid-cols-3';
        break;
    default:
        $grid_columns_class = 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-3';
}

// Set up layout specific classes
$container_class = '';
$item_class = '';

switch ( $catalog_layout ) {
    case 'slider':
        $container_class = 'aqualuxe-slider relative';
        $item_class = 'aqualuxe-slide';
        break;
    case 'masonry':
        $container_class = 'grid ' . $grid_columns_class . ' gap-6';
        $item_class = 'fish-item';
        break;
    case 'list':
        $container_class = 'space-y-8';
        $item_class = 'fish-item flex flex-col md:flex-row gap-6';
        break;
    case 'grid':
    default:
        $container_class = 'grid ' . $grid_columns_class . ' gap-6';
        $item_class = 'fish-item';
}
?>

<section id="fish-catalog" class="fish-catalog py-16 bg-gray-50 dark:bg-dark-400">
    <div class="container-fluid max-w-screen-xl mx-auto px-4">
        <div class="section-header text-center mb-12">
            <?php if ( $section_title ) : ?>
                <h2 class="section-title text-3xl md:text-4xl font-serif font-bold mb-4"><?php echo esc_html( $section_title ); ?></h2>
            <?php endif; ?>
            
            <?php if ( $section_subtitle ) : ?>
                <p class="section-subtitle text-lg text-gray-600 dark:text-gray-300"><?php echo esc_html( $section_subtitle ); ?></p>
            <?php endif; ?>
        </div>
        
        <div class="fish-catalog-container <?php echo esc_attr( $container_class ); ?>">
            <?php
            while ( $fish_query->have_posts() ) :
                $fish_query->the_post();
                
                // Get fish details
                $fish_title = get_the_title();
                $fish_excerpt = get_the_excerpt();
                $fish_permalink = get_permalink();
                
                // Get fish meta data (for custom post types)
                $fish_scientific_name = '';
                $fish_origin = '';
                $fish_size = '';
                $fish_temperature = '';
                $fish_price = '';
                
                if ( function_exists( 'get_field' ) ) {
                    $fish_scientific_name = get_field( 'scientific_name' );
                    $fish_origin = get_field( 'origin' );
                    $fish_size = get_field( 'size' );
                    $fish_temperature = get_field( 'temperature' );
                }
                
                // For WooCommerce products
                if ( $post_type === 'product' ) {
                    $product = wc_get_product( get_the_ID() );
                    if ( $product ) {
                        $fish_price = $product->get_price_html();
                    }
                }
                ?>
                <div class="<?php echo esc_attr( $item_class ); ?>">
                    <?php if ( $catalog_layout === 'list' ) : ?>
                        <?php if ( has_post_thumbnail() ) : ?>
                            <div class="fish-image md:w-1/3">
                                <a href="<?php echo esc_url( $fish_permalink ); ?>" class="block overflow-hidden rounded-lg">
                                    <?php the_post_thumbnail( 'medium', array( 'class' => 'w-full h-auto hover:scale-105 transition-transform duration-300' ) ); ?>
                                </a>
                            </div>
                        <?php endif; ?>
                        
                        <div class="fish-content md:w-2/3">
                            <h3 class="fish-title text-xl font-bold mb-1">
                                <a href="<?php echo esc_url( $fish_permalink ); ?>" class="hover:text-primary-500 transition-colors duration-200">
                                    <?php echo esc_html( $fish_title ); ?>
                                </a>
                            </h3>
                            
                            <?php if ( $fish_scientific_name ) : ?>
                                <p class="fish-scientific-name text-sm italic text-gray-600 dark:text-gray-400 mb-3"><?php echo esc_html( $fish_scientific_name ); ?></p>
                            <?php endif; ?>
                            
                            <div class="fish-excerpt text-gray-600 dark:text-gray-300 mb-4">
                                <?php echo wp_kses_post( $fish_excerpt ); ?>
                            </div>
                            
                            <div class="fish-meta grid grid-cols-2 gap-2 mb-4">
                                <?php if ( $fish_origin ) : ?>
                                    <div class="fish-origin flex items-center text-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span><?php echo esc_html( $fish_origin ); ?></span>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ( $fish_size ) : ?>
                                    <div class="fish-size flex items-center text-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                                        </svg>
                                        <span><?php echo esc_html( $fish_size ); ?></span>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ( $fish_temperature ) : ?>
                                    <div class="fish-temperature flex items-center text-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span><?php echo esc_html( $fish_temperature ); ?></span>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ( $fish_price ) : ?>
                                    <div class="fish-price flex items-center text-sm font-bold">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span><?php echo wp_kses_post( $fish_price ); ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="fish-actions">
                                <a href="<?php echo esc_url( $fish_permalink ); ?>" class="inline-flex items-center text-primary-500 hover:text-primary-600 transition-colors duration-200">
                                    <?php esc_html_e( 'View Details', 'aqualuxe' ); ?>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </a>
                                
                                <?php if ( $post_type === 'product' && $product && $product->is_purchasable() ) : ?>
                                    <a href="<?php echo esc_url( $product->add_to_cart_url() ); ?>" class="add_to_cart_button ajax_add_to_cart ml-4 inline-flex items-center text-primary-500 hover:text-primary-600 transition-colors duration-200" data-product_id="<?php echo esc_attr( $product->get_id() ); ?>" data-product_sku="<?php echo esc_attr( $product->get_sku() ); ?>">
                                        <?php esc_html_e( 'Add to Cart', 'aqualuxe' ); ?>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" />
                                        </svg>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    
                    <?php else : /* Default grid/masonry/slider layout */ ?>
                        <div class="fish-card card overflow-hidden h-full flex flex-col">
                            <?php if ( has_post_thumbnail() ) : ?>
                                <div class="fish-image">
                                    <a href="<?php echo esc_url( $fish_permalink ); ?>" class="block overflow-hidden">
                                        <?php the_post_thumbnail( 'medium', array( 'class' => 'w-full h-48 object-cover hover:scale-105 transition-transform duration-300' ) ); ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                            
                            <div class="fish-details p-4 flex-grow">
                                <h3 class="fish-title text-lg font-bold mb-1">
                                    <a href="<?php echo esc_url( $fish_permalink ); ?>" class="hover:text-primary-500 transition-colors duration-200">
                                        <?php echo esc_html( $fish_title ); ?>
                                    </a>
                                </h3>
                                
                                <?php if ( $fish_scientific_name ) : ?>
                                    <p class="fish-scientific-name text-sm italic text-gray-600 dark:text-gray-400 mb-3"><?php echo esc_html( $fish_scientific_name ); ?></p>
                                <?php endif; ?>
                                
                                <div class="fish-excerpt text-sm text-gray-600 dark:text-gray-300 mb-4">
                                    <?php echo wp_trim_words( $fish_excerpt, 15 ); ?>
                                </div>
                                
                                <div class="fish-meta grid grid-cols-2 gap-2 mb-4 text-xs">
                                    <?php if ( $fish_origin ) : ?>
                                        <div class="fish-origin flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <span><?php echo esc_html( $fish_origin ); ?></span>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if ( $fish_size ) : ?>
                                        <div class="fish-size flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                                            </svg>
                                            <span><?php echo esc_html( $fish_size ); ?></span>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if ( $fish_temperature ) : ?>
                                        <div class="fish-temperature flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <span><?php echo esc_html( $fish_temperature ); ?></span>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if ( $fish_price ) : ?>
                                        <div class="fish-price flex items-center font-bold">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <span><?php echo wp_kses_post( $fish_price ); ?></span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <div class="fish-actions p-4 pt-0 mt-auto">
                                <div class="flex items-center justify-between">
                                    <a href="<?php echo esc_url( $fish_permalink ); ?>" class="inline-flex items-center text-primary-500 hover:text-primary-600 transition-colors duration-200 text-sm">
                                        <?php esc_html_e( 'View Details', 'aqualuxe' ); ?>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </a>
                                    
                                    <?php if ( $post_type === 'product' && $product && $product->is_purchasable() ) : ?>
                                        <a href="<?php echo esc_url( $product->add_to_cart_url() ); ?>" class="add_to_cart_button ajax_add_to_cart inline-flex items-center text-primary-500 hover:text-primary-600 transition-colors duration-200 text-sm" data-product_id="<?php echo esc_attr( $product->get_id() ); ?>" data-product_sku="<?php echo esc_attr( $product->get_sku() ); ?>">
                                            <?php esc_html_e( 'Add to Cart', 'aqualuxe' ); ?>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" />
                                            </svg>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endwhile; ?>
            <?php wp_reset_postdata(); ?>
        </div>
        
        <?php if ( $catalog_layout === 'slider' ) : ?>
            <div class="slider-controls flex justify-center mt-6 space-x-2">
                <button class="aqualuxe-slider-prev w-10 h-10 rounded-full bg-white dark:bg-dark-500 shadow-md flex items-center justify-center text-gray-700 dark:text-gray-300 hover:text-primary-500 dark:hover:text-primary-400 focus:outline-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    <span class="sr-only"><?php esc_html_e( 'Previous', 'aqualuxe' ); ?></span>
                </button>
                
                <button class="aqualuxe-slider-next w-10 h-10 rounded-full bg-white dark:bg-dark-500 shadow-md flex items-center justify-center text-gray-700 dark:text-gray-300 hover:text-primary-500 dark:hover:text-primary-400 focus:outline-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                    <span class="sr-only"><?php esc_html_e( 'Next', 'aqualuxe' ); ?></span>
                </button>
            </div>
            
            <div class="slider-dots flex justify-center mt-4 space-x-2">
                <?php for ( $i = 0; $i < min( $fish_query->post_count, $fish_count ); $i++ ) : ?>
                    <button class="aqualuxe-slider-dot w-3 h-3 rounded-full bg-gray-300 dark:bg-gray-600 focus:outline-none <?php echo $i === 0 ? 'bg-primary-500' : ''; ?>"></button>
                <?php endfor; ?>
            </div>
        <?php endif; ?>
        
        <?php if ( $show_view_all && $view_all_url !== '#' ) : ?>
            <div class="view-all text-center mt-12">
                <a href="<?php echo esc_url( $view_all_url ); ?>" class="btn-outline">
                    <?php echo esc_html( $view_all_text ); ?>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </a>
            </div>
        <?php endif; ?>
    </div>
</section>