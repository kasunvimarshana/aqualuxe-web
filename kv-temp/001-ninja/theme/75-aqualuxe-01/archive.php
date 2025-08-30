<?php
/**
 * Archive template for displaying category, tag, author, and date archives
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
                    
                    <header class="archive-header mb-8 text-center">
                        <div class="archive-header-content">
                            <?php
                            the_archive_title( '<h1 class="archive-title text-3xl lg:text-4xl font-bold text-gray-900 dark:text-white mb-4">', '</h1>' );
                            the_archive_description( '<div class="archive-description text-lg text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">', '</div>' );
                            ?>
                        </div>
                        
                        <!-- Archive Meta -->
                        <div class="archive-meta mt-6 flex flex-wrap justify-center gap-4 text-sm text-gray-500 dark:text-gray-400">
                            <?php if ( is_category() || is_tag() ) : ?>
                                <span class="archive-count">
                                    <i class="fas fa-file-alt mr-1" aria-hidden="true"></i>
                                    <?php
                                    printf(
                                        /* translators: %s: Number of posts */
                                        _n( '%s post', '%s posts', $wp_query->found_posts, 'aqualuxe' ),
                                        number_format_i18n( $wp_query->found_posts )
                                    );
                                    ?>
                                </span>
                            <?php endif; ?>
                            
                            <?php if ( is_author() ) : ?>
                                <span class="author-posts-count">
                                    <i class="fas fa-user mr-1" aria-hidden="true"></i>
                                    <?php
                                    printf(
                                        /* translators: %s: Number of posts by author */
                                        _n( '%s post by this author', '%s posts by this author', $wp_query->found_posts, 'aqualuxe' ),
                                        number_format_i18n( $wp_query->found_posts )
                                    );
                                    ?>
                                </span>
                            <?php endif; ?>
                            
                            <?php if ( is_date() ) : ?>
                                <span class="date-range">
                                    <i class="fas fa-calendar mr-1" aria-hidden="true"></i>
                                    <?php
                                    if ( is_year() ) {
                                        printf( esc_html__( 'Posts from %s', 'aqualuxe' ), get_the_date( 'Y' ) );
                                    } elseif ( is_month() ) {
                                        printf( esc_html__( 'Posts from %s', 'aqualuxe' ), get_the_date( 'F Y' ) );
                                    } elseif ( is_day() ) {
                                        printf( esc_html__( 'Posts from %s', 'aqualuxe' ), get_the_date( 'F j, Y' ) );
                                    }
                                    ?>
                                </span>
                            <?php endif; ?>
                        </div>
                    </header>
                    
                    <!-- Archive Filters -->
                    <?php if ( is_category() || is_tag() ) : ?>
                    <div class="archive-filters mb-8">
                        <div class="filter-controls flex flex-wrap justify-center gap-4">
                            <div class="sort-control">
                                <label for="archive-sort" class="sr-only"><?php esc_html_e( 'Sort posts by', 'aqualuxe' ); ?></label>
                                <select id="archive-sort" class="form-select text-sm">
                                    <option value="date-desc"><?php esc_html_e( 'Newest First', 'aqualuxe' ); ?></option>
                                    <option value="date-asc"><?php esc_html_e( 'Oldest First', 'aqualuxe' ); ?></option>
                                    <option value="title-asc"><?php esc_html_e( 'Title A-Z', 'aqualuxe' ); ?></option>
                                    <option value="title-desc"><?php esc_html_e( 'Title Z-A', 'aqualuxe' ); ?></option>
                                    <option value="comments"><?php esc_html_e( 'Most Commented', 'aqualuxe' ); ?></option>
                                </select>
                            </div>
                            
                            <div class="view-control">
                                <div class="view-toggle flex">
                                    <button class="view-btn view-grid active" data-view="grid" aria-label="<?php esc_attr_e( 'Grid view', 'aqualuxe' ); ?>">
                                        <i class="fas fa-th" aria-hidden="true"></i>
                                    </button>
                                    <button class="view-btn view-list" data-view="list" aria-label="<?php esc_attr_e( 'List view', 'aqualuxe' ); ?>">
                                        <i class="fas fa-list" aria-hidden="true"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Posts Grid -->
                    <div class="posts-archive" data-view="grid">
                        <div class="posts-grid grid grid-cols-1 md:grid-cols-2 gap-8">
                            <?php
                            while ( have_posts() ) :
                                the_post();
                                
                                get_template_part( 'template-parts/content', 'archive' );
                                
                            endwhile;
                            ?>
                        </div>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="pagination-wrapper mt-12">
                        <?php aqualuxe_pagination(); ?>
                    </div>
                    
                <?php else : ?>
                    
                    <?php get_template_part( 'template-parts/content', 'none' ); ?>
                    
                <?php endif; ?>
                
                <!-- Related Categories/Tags -->
                <?php if ( is_category() && have_posts() ) : ?>
                    <?php
                    $current_category = get_queried_object();
                    $related_categories = get_categories( array(
                        'exclude' => $current_category->term_id,
                        'number'  => 5,
                        'orderby' => 'count',
                        'order'   => 'DESC',
                    ) );
                    
                    if ( $related_categories ) :
                    ?>
                    <section class="related-categories mt-12 pt-8 border-t border-gray-200 dark:border-gray-700">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">
                            <?php esc_html_e( 'Related Categories', 'aqualuxe' ); ?>
                        </h2>
                        
                        <div class="categories-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <?php foreach ( $related_categories as $category ) : ?>
                                <a href="<?php echo esc_url( get_category_link( $category->term_id ) ); ?>" 
                                   class="category-card block p-4 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 hover:border-primary-500 dark:hover:border-primary-500 transition-colors">
                                    <h3 class="font-semibold text-gray-900 dark:text-white mb-1">
                                        <?php echo esc_html( $category->name ); ?>
                                    </h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        <?php
                                        printf(
                                            /* translators: %s: Number of posts in category */
                                            _n( '%s post', '%s posts', $category->count, 'aqualuxe' ),
                                            number_format_i18n( $category->count )
                                        );
                                        ?>
                                    </p>
                                    <?php if ( $category->description ) : ?>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                                            <?php echo wp_trim_words( $category->description, 15, '...' ); ?>
                                        </p>
                                    <?php endif; ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </section>
                    <?php endif; ?>
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
    // View toggle functionality
    const viewButtons = document.querySelectorAll('.view-btn');
    const postsArchive = document.querySelector('.posts-archive');
    const postsGrid = document.querySelector('.posts-grid');
    
    viewButtons.forEach(button => {
        button.addEventListener('click', function() {
            const view = this.dataset.view;
            
            // Update active button
            viewButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            
            // Update view
            postsArchive.dataset.view = view;
            
            if (view === 'list') {
                postsGrid.classList.remove('grid-cols-1', 'md:grid-cols-2');
                postsGrid.classList.add('space-y-6');
            } else {
                postsGrid.classList.add('grid-cols-1', 'md:grid-cols-2');
                postsGrid.classList.remove('space-y-6');
            }
        });
    });
    
    // Sort functionality
    const sortSelect = document.getElementById('archive-sort');
    if (sortSelect) {
        sortSelect.addEventListener('change', function() {
            const sortValue = this.value;
            const currentUrl = new URL(window.location);
            
            currentUrl.searchParams.set('orderby', sortValue.split('-')[0]);
            currentUrl.searchParams.set('order', sortValue.split('-')[1]);
            
            window.location.href = currentUrl.toString();
        });
    }
});
</script>

<?php
get_footer();
