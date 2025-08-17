<?php
/**
 * The template for displaying trade-in archive
 *
 * @package AquaLuxe
 */

get_header();
?>

<main id="primary" class="site-main">
    <div class="container mx-auto px-4 py-12">
        <header class="page-header mb-8">
            <h1 class="page-title text-3xl md:text-4xl font-bold text-primary-900 dark:text-primary-100">
                <?php esc_html_e( 'Trade-In Items', 'aqualuxe' ); ?>
            </h1>
            
            <?php if ( is_tax( 'trade_in_category' ) ) : ?>
                <div class="archive-description mt-4 text-lg text-gray-600 dark:text-gray-300">
                    <?php the_archive_description(); ?>
                </div>
            <?php else : ?>
                <div class="archive-description mt-4 text-lg text-gray-600 dark:text-gray-300">
                    <p><?php esc_html_e( 'Browse our collection of available trade-in items. These items have been inspected and are available for purchase or trade.', 'aqualuxe' ); ?></p>
                </div>
            <?php endif; ?>
        </header>
        
        <div class="trade-in-filters mb-8">
            <div class="flex flex-wrap gap-4 items-center justify-between">
                <div class="trade-in-categories">
                    <?php
                    $categories = get_terms( array(
                        'taxonomy'   => 'trade_in_category',
                        'hide_empty' => true,
                    ) );
                    
                    if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) :
                    ?>
                        <div class="flex flex-wrap gap-2">
                            <a href="<?php echo esc_url( get_post_type_archive_link( 'aqualuxe_trade_in' ) ); ?>" class="inline-block px-3 py-1 text-sm rounded-full <?php echo is_post_type_archive( 'aqualuxe_trade_in' ) && ! is_tax() ? 'bg-primary-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600'; ?>">
                                <?php esc_html_e( 'All', 'aqualuxe' ); ?>
                            </a>
                            
                            <?php foreach ( $categories as $category ) : ?>
                                <a href="<?php echo esc_url( get_term_link( $category ) ); ?>" class="inline-block px-3 py-1 text-sm rounded-full <?php echo is_tax( 'trade_in_category', $category->term_id ) ? 'bg-primary-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600'; ?>">
                                    <?php echo esc_html( $category->name ); ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="trade-in-sort">
                    <?php
                    $current_sort = isset( $_GET['sort'] ) ? sanitize_text_field( $_GET['sort'] ) : '';
                    ?>
                    <form action="<?php echo esc_url( get_post_type_archive_link( 'aqualuxe_trade_in' ) ); ?>" method="get" class="flex items-center">
                        <label for="sort" class="mr-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                            <?php esc_html_e( 'Sort by:', 'aqualuxe' ); ?>
                        </label>
                        <select name="sort" id="sort" class="py-1 px-2 border border-gray-300 rounded-md text-sm bg-white dark:bg-gray-700 dark:border-gray-600 dark:text-white" onchange="this.form.submit()">
                            <option value="newest" <?php selected( $current_sort, 'newest' ); ?>><?php esc_html_e( 'Newest First', 'aqualuxe' ); ?></option>
                            <option value="oldest" <?php selected( $current_sort, 'oldest' ); ?>><?php esc_html_e( 'Oldest First', 'aqualuxe' ); ?></option>
                            <option value="price_low" <?php selected( $current_sort, 'price_low' ); ?>><?php esc_html_e( 'Price: Low to High', 'aqualuxe' ); ?></option>
                            <option value="price_high" <?php selected( $current_sort, 'price_high' ); ?>><?php esc_html_e( 'Price: High to Low', 'aqualuxe' ); ?></option>
                        </select>
                    </form>
                </div>
            </div>
        </div>
        
        <?php
        // Modify the query based on sorting
        if ( isset( $_GET['sort'] ) ) {
            $sort = sanitize_text_field( $_GET['sort'] );
            
            switch ( $sort ) {
                case 'newest':
                    $args = array(
                        'orderby' => 'date',
                        'order'   => 'DESC',
                    );
                    break;
                    
                case 'oldest':
                    $args = array(
                        'orderby' => 'date',
                        'order'   => 'ASC',
                    );
                    break;
                    
                case 'price_low':
                    $args = array(
                        'meta_key' => '_trade_in_value',
                        'orderby'  => 'meta_value_num',
                        'order'    => 'ASC',
                    );
                    break;
                    
                case 'price_high':
                    $args = array(
                        'meta_key' => '_trade_in_value',
                        'orderby'  => 'meta_value_num',
                        'order'    => 'DESC',
                    );
                    break;
                    
                default:
                    $args = array();
                    break;
            }
            
            // Apply the sorting args to the main query
            $query = new WP_Query( array_merge( $wp_query->query_vars, $args ) );
            
            // Replace the main query with our modified query
            $wp_query = $query;
        }
        ?>
        
        <?php if ( have_posts() ) : ?>
            <div class="trade-in-grid">
                <?php while ( have_posts() ) : the_post(); ?>
                    <?php
                    // Get trade-in data
                    $trade_in_id = get_the_ID();
                    $condition = get_post_meta( $trade_in_id, '_trade_in_condition', true );
                    $trade_value = get_post_meta( $trade_in_id, '_trade_in_value', true );
                    
                    // Get trade-in status
                    $status_terms = get_the_terms( $trade_in_id, 'trade_in_status' );
                    $status = ! empty( $status_terms ) ? $status_terms[0]->slug : '';
                    
                    // Condition labels
                    $condition_labels = array(
                        'new'       => __( 'New', 'aqualuxe' ),
                        'like-new'  => __( 'Like New', 'aqualuxe' ),
                        'excellent' => __( 'Excellent', 'aqualuxe' ),
                        'good'      => __( 'Good', 'aqualuxe' ),
                        'fair'      => __( 'Fair', 'aqualuxe' ),
                        'poor'      => __( 'Poor', 'aqualuxe' ),
                    );
                    
                    $condition_label = isset( $condition_labels[ $condition ] ) ? $condition_labels[ $condition ] : '';
                    ?>
                    
                    <article class="trade-in-item">
                        <div class="trade-in-item-image">
                            <?php if ( has_post_thumbnail() ) : ?>
                                <?php the_post_thumbnail( 'medium', array( 'class' => 'w-full h-full object-cover' ) ); ?>
                            <?php else : ?>
                                <div class="w-full h-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                                    <span class="text-gray-500 dark:text-gray-400">
                                        <?php esc_html_e( 'No Image', 'aqualuxe' ); ?>
                                    </span>
                                </div>
                            <?php endif; ?>
                            
                            <span class="trade-in-status <?php echo esc_attr( $status ); ?> absolute top-2 left-2">
                                <?php echo esc_html( $status_terms[0]->name ); ?>
                            </span>
                        </div>
                        
                        <div class="trade-in-item-content">
                            <h2 class="trade-in-item-title">
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_title(); ?>
                                </a>
                            </h2>
                            
                            <div class="trade-in-item-meta">
                                <?php if ( $condition_label ) : ?>
                                    <div class="trade-in-item-meta-item">
                                        <strong><?php esc_html_e( 'Condition:', 'aqualuxe' ); ?></strong>
                                        <?php echo esc_html( $condition_label ); ?>
                                    </div>
                                <?php endif; ?>
                                
                                <?php
                                // Get trade-in category
                                $category_terms = get_the_terms( $trade_in_id, 'trade_in_category' );
                                if ( ! empty( $category_terms ) && ! is_wp_error( $category_terms ) ) :
                                    ?>
                                    <div class="trade-in-item-meta-item">
                                        <strong><?php esc_html_e( 'Category:', 'aqualuxe' ); ?></strong>
                                        <?php echo esc_html( $category_terms[0]->name ); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="trade-in-item-footer">
                                <?php if ( $trade_value ) : ?>
                                    <div class="trade-in-item-value">
                                        <?php echo esc_html( '$' . number_format( $trade_value, 2 ) ); ?>
                                    </div>
                                <?php endif; ?>
                                
                                <a href="<?php the_permalink(); ?>" class="trade-in-item-button">
                                    <?php esc_html_e( 'View Details', 'aqualuxe' ); ?>
                                </a>
                            </div>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>
            
            <?php
            // Pagination
            the_posts_pagination( array(
                'prev_text' => '<span class="screen-reader-text">' . __( 'Previous', 'aqualuxe' ) . '</span><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>',
                'next_text' => '<span class="screen-reader-text">' . __( 'Next', 'aqualuxe' ) . '</span><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>',
            ) );
            ?>
        <?php else : ?>
            <div class="no-results bg-gray-50 dark:bg-gray-800 p-8 rounded-lg text-center">
                <h2 class="text-xl font-semibold mb-2">
                    <?php esc_html_e( 'No Trade-In Items Found', 'aqualuxe' ); ?>
                </h2>
                <p class="text-gray-600 dark:text-gray-300">
                    <?php esc_html_e( 'There are currently no trade-in items available. Please check back later or submit your own item for trade-in.', 'aqualuxe' ); ?>
                </p>
                <div class="mt-6">
                    <a href="<?php echo esc_url( get_permalink( get_page_by_path( 'trade-in-request' ) ) ); ?>" class="inline-block px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white font-medium rounded-md transition duration-200">
                        <?php esc_html_e( 'Submit a Trade-In Request', 'aqualuxe' ); ?>
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php
get_footer();