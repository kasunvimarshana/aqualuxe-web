<?php
/**
 * Template Name: Portfolio
 * Template Post Type: page
 *
 * A template for displaying portfolio items in a grid layout.
 *
 * @package AquaLuxe
 */

get_header();

// Get portfolio settings
$columns = get_post_meta( get_the_ID(), '_aqualuxe_portfolio_columns', true );
$columns = $columns ? $columns : '3'; // Default to 3 columns

$items_per_page = get_post_meta( get_the_ID(), '_aqualuxe_portfolio_items_per_page', true );
$items_per_page = $items_per_page ? $items_per_page : 9; // Default to 9 items

$show_filters = get_post_meta( get_the_ID(), '_aqualuxe_portfolio_show_filters', true );
$show_filters = $show_filters !== '' ? $show_filters : true; // Default to showing filters

// Set column classes
$column_class = 'grid-cols-1 md:grid-cols-2 lg:grid-cols-3';
if ( $columns === '2' ) {
    $column_class = 'grid-cols-1 md:grid-cols-2';
} elseif ( $columns === '4' ) {
    $column_class = 'grid-cols-1 md:grid-cols-2 lg:grid-cols-4';
}

// Get current page for pagination
$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

// Get selected category if filtering
$current_category = isset( $_GET['portfolio_category'] ) ? sanitize_text_field( $_GET['portfolio_category'] ) : '';

// Portfolio query args
$portfolio_args = array(
    'post_type'      => 'project', // Assuming 'project' is the portfolio CPT
    'posts_per_page' => $items_per_page,
    'paged'          => $paged,
);

// Add category filter if selected
if ( $current_category ) {
    $portfolio_args['tax_query'] = array(
        array(
            'taxonomy' => 'project_category',
            'field'    => 'slug',
            'terms'    => $current_category,
        ),
    );
}

$portfolio_query = new WP_Query( $portfolio_args );

// Get all portfolio categories for filter
$portfolio_categories = get_terms( array(
    'taxonomy'   => 'project_category',
    'hide_empty' => true,
) );
?>

<main id="primary" class="site-main">
    <div class="container mx-auto px-4 py-12">
        <?php
        while ( have_posts() ) :
            the_post();
            
            // Display page title and content
            ?>
            <header class="page-header mb-8">
                <?php the_title( '<h1 class="page-title text-3xl font-bold text-gray-900 dark:text-gray-100">', '</h1>' ); ?>
                
                <div class="page-description prose dark:prose-invert max-w-none mt-4">
                    <?php the_content(); ?>
                </div>
            </header>
            <?php
        endwhile; // End of the page loop.
        
        // Display portfolio filters if enabled
        if ( $show_filters && ! empty( $portfolio_categories ) && ! is_wp_error( $portfolio_categories ) ) :
            ?>
            <div class="portfolio-filters mb-8">
                <div class="flex flex-wrap gap-2">
                    <a href="<?php echo esc_url( remove_query_arg( 'portfolio_category' ) ); ?>" class="px-4 py-2 rounded-full text-sm <?php echo empty( $current_category ) ? 'bg-blue-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600'; ?> transition-colors">
                        <?php esc_html_e( 'All', 'aqualuxe' ); ?>
                    </a>
                    
                    <?php foreach ( $portfolio_categories as $category ) : ?>
                        <a href="<?php echo esc_url( add_query_arg( 'portfolio_category', $category->slug ) ); ?>" class="px-4 py-2 rounded-full text-sm <?php echo $current_category === $category->slug ? 'bg-blue-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600'; ?> transition-colors">
                            <?php echo esc_html( $category->name ); ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php
        endif;
        
        // Check if we have portfolio items
        if ( $portfolio_query->have_posts() ) :
            ?>
            <div class="portfolio-grid grid <?php echo esc_attr( $column_class ); ?> gap-6">
                <?php
                while ( $portfolio_query->have_posts() ) :
                    $portfolio_query->the_post();
                    
                    // Get project details
                    $project_url = get_post_meta( get_the_ID(), '_project_url', true );
                    $project_client = get_post_meta( get_the_ID(), '_project_client', true );
                    
                    // Get project categories
                    $project_categories = get_the_terms( get_the_ID(), 'project_category' );
                    ?>
                    
                    <article id="post-<?php the_ID(); ?>" <?php post_class( 'portfolio-item bg-white dark:bg-gray-800 rounded-lg overflow-hidden shadow-md transition-transform hover:-translate-y-1 hover:shadow-lg' ); ?>>
                        <?php if ( has_post_thumbnail() ) : ?>
                            <div class="portfolio-thumbnail">
                                <a href="<?php the_permalink(); ?>" class="block overflow-hidden">
                                    <?php the_post_thumbnail( 'medium_large', array(
                                        'class' => 'w-full h-64 object-cover transition-transform hover:scale-105',
                                    ) ); ?>
                                </a>
                            </div>
                        <?php endif; ?>
                        
                        <div class="portfolio-content p-6">
                            <header class="entry-header mb-3">
                                <?php the_title( '<h2 class="entry-title text-xl font-bold"><a href="' . esc_url( get_permalink() ) . '" class="text-gray-900 dark:text-gray-100 hover:text-blue-600 dark:hover:text-blue-400">', '</a></h2>' ); ?>
                                
                                <?php if ( ! empty( $project_categories ) && ! is_wp_error( $project_categories ) ) : ?>
                                    <div class="portfolio-categories mt-2 flex flex-wrap gap-2">
                                        <?php foreach ( $project_categories as $category ) : ?>
                                            <span class="px-2 py-1 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 rounded text-xs">
                                                <?php echo esc_html( $category->name ); ?>
                                            </span>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </header>
                            
                            <div class="entry-summary">
                                <?php the_excerpt(); ?>
                            </div>
                            
                            <footer class="entry-footer mt-4 flex justify-between items-center">
                                <a href="<?php the_permalink(); ?>" class="inline-flex items-center text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                    <?php esc_html_e( 'View Details', 'aqualuxe' ); ?>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                    </svg>
                                </a>
                                
                                <?php if ( ! empty( $project_client ) ) : ?>
                                    <span class="text-sm text-gray-600 dark:text-gray-400">
                                        <?php echo esc_html( $project_client ); ?>
                                    </span>
                                <?php endif; ?>
                            </footer>
                        </div>
                    </article>
                    
                <?php
                endwhile;
                ?>
            </div>
            
            <?php
            // Portfolio pagination
            $total_pages = $portfolio_query->max_num_pages;
            
            if ( $total_pages > 1 ) :
                ?>
                <div class="portfolio-pagination mt-12">
                    <nav class="pagination flex justify-center">
                        <?php
                        echo paginate_links( array(
                            'base'         => str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ),
                            'total'        => $total_pages,
                            'current'      => max( 1, get_query_var( 'paged' ) ),
                            'format'       => '?paged=%#%',
                            'show_all'     => false,
                            'type'         => 'plain',
                            'end_size'     => 2,
                            'mid_size'     => 1,
                            'prev_next'    => true,
                            'prev_text'    => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>',
                            'next_text'    => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>',
                            'add_args'     => false,
                            'add_fragment' => '',
                            'before_page_number' => '',
                            'after_page_number'  => '',
                        ) );
                        ?>
                    </nav>
                </div>
                <?php
            endif;
            
            // Restore original post data
            wp_reset_postdata();
            
        else :
            ?>
            <div class="no-portfolio-items bg-white dark:bg-gray-800 p-8 rounded-lg shadow-md">
                <p><?php esc_html_e( 'No portfolio items found.', 'aqualuxe' ); ?></p>
            </div>
            <?php
        endif;
        ?>
    </div>
</main><!-- #main -->

<?php
get_footer();