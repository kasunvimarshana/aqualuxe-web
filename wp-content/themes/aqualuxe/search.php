<?php
/**
 * Search results template
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

get_header();
?>

<div class="content-wrapper">
    <div class="container">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <main class="main-content lg:col-span-2">
                <?php if ( have_posts() ) : ?>
                    
                    <header class="search-header mb-8 text-center">
                        <div class="search-header-content">
                            <h1 class="search-title text-3xl lg:text-4xl font-bold text-gray-900 dark:text-white mb-4">
                                <?php
                                printf(
                                    /* translators: %s: search query */
                                    esc_html__( 'Search Results for: %s', 'aqualuxe' ),
                                    '<span class="text-primary-600 dark:text-primary-400">' . get_search_query() . '</span>'
                                );
                                ?>
                            </h1>
                            
                            <div class="search-description text-lg text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">
                                <?php
                                printf(
                                    /* translators: %s: number of search results */
                                    _n(
                                        'Found %s result matching your search.',
                                        'Found %s results matching your search.',
                                        $wp_query->found_posts,
                                        'aqualuxe'
                                    ),
                                    '<strong>' . number_format_i18n( $wp_query->found_posts ) . '</strong>'
                                );
                                ?>
                            </div>
                        </div>
                        
                        <!-- Search Again Form -->
                        <div class="search-again-form mt-6 max-w-xl mx-auto">
                            <?php get_search_form(); ?>
                        </div>
                        
                        <!-- Search Meta -->
                        <div class="search-meta mt-6 flex flex-wrap justify-center gap-4 text-sm text-gray-500 dark:text-gray-400">
                            <span class="search-query">
                                <i class="fas fa-search mr-1" aria-hidden="true"></i>
                                "<?php echo esc_html( get_search_query() ); ?>"
                            </span>
                            
                            <span class="search-time">
                                <i class="fas fa-clock mr-1" aria-hidden="true"></i>
                                <?php
                                printf(
                                    /* translators: %s: search time in seconds */
                                    esc_html__( 'Search took %s seconds', 'aqualuxe' ),
                                    '<span id="search-time">0.00</span>'
                                );
                                ?>
                            </span>
                            
                            <?php if ( isset( $_GET['post_type'] ) && ! empty( $_GET['post_type'] ) ) : ?>
                                <span class="search-filter">
                                    <i class="fas fa-filter mr-1" aria-hidden="true"></i>
                                    <?php
                                    $post_type_obj = get_post_type_object( sanitize_text_field( $_GET['post_type'] ) );
                                    if ( $post_type_obj ) {
                                        printf(
                                            /* translators: %s: post type name */
                                            esc_html__( 'Filtered by: %s', 'aqualuxe' ),
                                            $post_type_obj->labels->name
                                        );
                                    }
                                    ?>
                                </span>
                            <?php endif; ?>
                        </div>
                    </header>
                    
                    <!-- Search Filters -->
                    <div class="search-filters mb-8">
                        <div class="filter-controls flex flex-wrap justify-between items-center gap-4 p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                            <div class="filter-left flex flex-wrap gap-4">
                                <!-- Sort Control -->
                                <div class="sort-control">
                                    <label for="search-sort" class="sr-only"><?php esc_html_e( 'Sort results by', 'aqualuxe' ); ?></label>
                                    <select id="search-sort" class="form-select text-sm">
                                        <option value="relevance"><?php esc_html_e( 'Most Relevant', 'aqualuxe' ); ?></option>
                                        <option value="date-desc"><?php esc_html_e( 'Newest First', 'aqualuxe' ); ?></option>
                                        <option value="date-asc"><?php esc_html_e( 'Oldest First', 'aqualuxe' ); ?></option>
                                        <option value="title-asc"><?php esc_html_e( 'Title A-Z', 'aqualuxe' ); ?></option>
                                        <option value="title-desc"><?php esc_html_e( 'Title Z-A', 'aqualuxe' ); ?></option>
                                    </select>
                                </div>
                                
                                <!-- Content Type Filter -->
                                <div class="type-filter">
                                    <label for="search-type-filter" class="sr-only"><?php esc_html_e( 'Filter by content type', 'aqualuxe' ); ?></label>
                                    <select id="search-type-filter" class="form-select text-sm">
                                        <option value=""><?php esc_html_e( 'All Types', 'aqualuxe' ); ?></option>
                                        <option value="post"><?php esc_html_e( 'Posts', 'aqualuxe' ); ?></option>
                                        <option value="page"><?php esc_html_e( 'Pages', 'aqualuxe' ); ?></option>
                                        <?php if ( class_exists( 'WooCommerce' ) ) : ?>
                                            <option value="product"><?php esc_html_e( 'Products', 'aqualuxe' ); ?></option>
                                        <?php endif; ?>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="filter-right flex gap-4">
                                <!-- View Toggle -->
                                <div class="view-control">
                                    <div class="view-toggle flex">
                                        <button class="view-btn view-list active" data-view="list" aria-label="<?php esc_attr_e( 'List view', 'aqualuxe' ); ?>">
                                            <i class="fas fa-list" aria-hidden="true"></i>
                                        </button>
                                        <button class="view-btn view-grid" data-view="grid" aria-label="<?php esc_attr_e( 'Grid view', 'aqualuxe' ); ?>">
                                            <i class="fas fa-th" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                </div>
                                
                                <!-- Results Per Page -->
                                <div class="per-page-control">
                                    <label for="search-per-page" class="sr-only"><?php esc_html_e( 'Results per page', 'aqualuxe' ); ?></label>
                                    <select id="search-per-page" class="form-select text-sm">
                                        <option value="10">10</option>
                                        <option value="20" selected>20</option>
                                        <option value="50">50</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Search Results -->
                    <div class="search-results" data-view="list">
                        <div class="results-list space-y-6">
                            <?php
                            while ( have_posts() ) :
                                the_post();
                                
                                get_template_part( 'template-parts/content', 'search' );
                                
                            endwhile;
                            ?>
                        </div>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="pagination-wrapper mt-12">
                        <?php aqualuxe_pagination(); ?>
                    </div>
                    
                <?php else : ?>
                    
                    <!-- No Results -->
                    <div class="no-results text-center py-16">
                        <div class="no-results-icon mb-6">
                            <i class="fas fa-search text-6xl text-gray-300 dark:text-gray-600" aria-hidden="true"></i>
                        </div>
                        
                        <h1 class="no-results-title text-3xl font-bold text-gray-900 dark:text-white mb-4">
                            <?php esc_html_e( 'No Results Found', 'aqualuxe' ); ?>
                        </h1>
                        
                        <div class="no-results-content max-w-2xl mx-auto">
                            <p class="text-lg text-gray-600 dark:text-gray-300 mb-6">
                                <?php
                                printf(
                                    /* translators: %s: search query */
                                    esc_html__( 'Sorry, no results were found for "%s". Please try a different search term or browse our content below.', 'aqualuxe' ),
                                    '<strong>' . get_search_query() . '</strong>'
                                );
                                ?>
                            </p>
                            
                            <!-- Search Again Form -->
                            <div class="search-again-form mb-8 max-w-xl mx-auto">
                                <?php get_search_form(); ?>
                            </div>
                            
                            <!-- Search Suggestions -->
                            <div class="search-suggestions">
                                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">
                                    <?php esc_html_e( 'Search Suggestions', 'aqualuxe' ); ?>
                                </h2>
                                
                                <ul class="suggestions-list list-disc list-inside text-gray-600 dark:text-gray-300 space-y-2">
                                    <li><?php esc_html_e( 'Check your spelling and try again', 'aqualuxe' ); ?></li>
                                    <li><?php esc_html_e( 'Try using fewer or different keywords', 'aqualuxe' ); ?></li>
                                    <li><?php esc_html_e( 'Use more general terms', 'aqualuxe' ); ?></li>
                                    <li><?php esc_html_e( 'Try browsing our categories below', 'aqualuxe' ); ?></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Popular Content -->
                    <section class="popular-content mt-12 pt-8 border-t border-gray-200 dark:border-gray-700">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6 text-center">
                            <?php esc_html_e( 'Popular Content', 'aqualuxe' ); ?>
                        </h2>
                        
                        <?php
                        $popular_posts = new WP_Query( array(
                            'post_type'      => array( 'post', 'page' ),
                            'posts_per_page' => 6,
                            'meta_key'       => 'post_views_count',
                            'orderby'        => 'meta_value_num',
                            'order'          => 'DESC',
                        ) );
                        
                        if ( $popular_posts->have_posts() ) :
                        ?>
                            <div class="popular-posts-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                <?php
                                while ( $popular_posts->have_posts() ) :
                                    $popular_posts->the_post();
                                ?>
                                    <article class="popular-post-card bg-white dark:bg-gray-800 rounded-lg overflow-hidden shadow-sm hover:shadow-lg transition-all duration-300">
                                        <?php if ( has_post_thumbnail() ) : ?>
                                            <div class="post-thumbnail">
                                                <a href="<?php the_permalink(); ?>">
                                                    <?php the_post_thumbnail( 'medium', array( 'class' => 'w-full h-32 object-cover' ) ); ?>
                                                </a>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <div class="post-content p-4">
                                            <h3 class="post-title font-semibold text-gray-900 dark:text-white mb-2 line-clamp-2">
                                                <a href="<?php the_permalink(); ?>" class="hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
                                                    <?php the_title(); ?>
                                                </a>
                                            </h3>
                                            
                                            <p class="post-excerpt text-sm text-gray-600 dark:text-gray-300 line-clamp-2">
                                                <?php echo wp_trim_words( get_the_excerpt(), 15, '...' ); ?>
                                            </p>
                                        </div>
                                    </article>
                                <?php
                                endwhile;
                                wp_reset_postdata();
                                ?>
                            </div>
                        <?php endif; ?>
                    </section>
                    
                    <!-- Categories -->
                    <section class="browse-categories mt-12 pt-8 border-t border-gray-200 dark:border-gray-700">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6 text-center">
                            <?php esc_html_e( 'Browse Categories', 'aqualuxe' ); ?>
                        </h2>
                        
                        <?php
                        $categories = get_categories( array(
                            'number'     => 8,
                            'orderby'    => 'count',
                            'order'      => 'DESC',
                            'hide_empty' => true,
                        ) );
                        
                        if ( ! empty( $categories ) ) :
                        ?>
                            <div class="categories-grid grid grid-cols-2 md:grid-cols-4 gap-4">
                                <?php foreach ( $categories as $category ) : ?>
                                    <a href="<?php echo esc_url( get_category_link( $category->term_id ) ); ?>" 
                                       class="category-card block p-4 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 hover:border-primary-500 dark:hover:border-primary-500 text-center transition-colors">
                                        <h3 class="category-name font-semibold text-gray-900 dark:text-white mb-1">
                                            <?php echo esc_html( $category->name ); ?>
                                        </h3>
                                        <p class="category-count text-sm text-gray-600 dark:text-gray-400">
                                            <?php
                                            printf(
                                                /* translators: %s: Number of posts in category */
                                                _n( '%s post', '%s posts', $category->count, 'aqualuxe' ),
                                                number_format_i18n( $category->count )
                                            );
                                            ?>
                                        </p>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </section>
                    
                <?php endif; ?>
            </main>
            
            <!-- Sidebar -->
            <aside class="sidebar lg:col-span-1">
                <?php get_sidebar(); ?>
            </aside>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Calculate and display search time
    const searchTime = (performance.now() / 1000).toFixed(2);
    const searchTimeElement = document.getElementById('search-time');
    if (searchTimeElement) {
        searchTimeElement.textContent = searchTime;
    }
    
    // View toggle functionality
    const viewButtons = document.querySelectorAll('.view-btn');
    const searchResults = document.querySelector('.search-results');
    const resultsList = document.querySelector('.results-list');
    
    viewButtons.forEach(button => {
        button.addEventListener('click', function() {
            const view = this.dataset.view;
            
            // Update active button
            viewButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            
            // Update view
            searchResults.dataset.view = view;
            
            if (view === 'grid') {
                resultsList.classList.remove('space-y-6');
                resultsList.classList.add('grid', 'grid-cols-1', 'md:grid-cols-2', 'gap-6');
            } else {
                resultsList.classList.add('space-y-6');
                resultsList.classList.remove('grid', 'grid-cols-1', 'md:grid-cols-2', 'gap-6');
            }
        });
    });
    
    // Filter functionality
    const sortSelect = document.getElementById('search-sort');
    const typeFilter = document.getElementById('search-type-filter');
    const perPageSelect = document.getElementById('search-per-page');
    
    function updateSearchResults() {
        const currentUrl = new URL(window.location);
        
        if (sortSelect) {
            const sortValue = sortSelect.value;
            if (sortValue === 'relevance') {
                currentUrl.searchParams.delete('orderby');
                currentUrl.searchParams.delete('order');
            } else {
                const [orderby, order] = sortValue.split('-');
                currentUrl.searchParams.set('orderby', orderby);
                currentUrl.searchParams.set('order', order);
            }
        }
        
        if (typeFilter) {
            const typeValue = typeFilter.value;
            if (typeValue) {
                currentUrl.searchParams.set('post_type', typeValue);
            } else {
                currentUrl.searchParams.delete('post_type');
            }
        }
        
        if (perPageSelect) {
            const perPageValue = perPageSelect.value;
            currentUrl.searchParams.set('posts_per_page', perPageValue);
        }
        
        window.location.href = currentUrl.toString();
    }
    
    if (sortSelect) {
        sortSelect.addEventListener('change', updateSearchResults);
    }
    
    if (typeFilter) {
        typeFilter.addEventListener('change', updateSearchResults);
    }
    
    if (perPageSelect) {
        perPageSelect.addEventListener('change', updateSearchResults);
    }
    
    // Set current values from URL
    const urlParams = new URLSearchParams(window.location.search);
    
    if (sortSelect) {
        const orderby = urlParams.get('orderby');
        const order = urlParams.get('order');
        if (orderby && order) {
            sortSelect.value = `${orderby}-${order}`;
        }
    }
    
    if (typeFilter) {
        const postType = urlParams.get('post_type');
        if (postType) {
            typeFilter.value = postType;
        }
    }
    
    if (perPageSelect) {
        const postsPerPage = urlParams.get('posts_per_page');
        if (postsPerPage) {
            perPageSelect.value = postsPerPage;
        }
    }
});
</script>

<?php
get_footer();
