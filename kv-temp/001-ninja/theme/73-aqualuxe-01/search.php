<?php
/**
 * Search results template
 *
 * @package AquaLuxe
 * @version 1.0.0
 */

get_header(); ?>

<main id="main" class="site-main" role="main">
    <div class="container mx-auto px-4 py-8">
        
        <!-- Search Header -->
        <header class="search-header text-center mb-12">
            <h1 class="search-title text-4xl md:text-5xl font-bold mb-4">
                <?php
                printf(
                    esc_html__('Search Results for: %s', 'aqualuxe'),
                    '<span class="text-primary-600">' . get_search_query() . '</span>'
                );
                ?>
            </h1>
            
            <?php if (have_posts()): ?>
                <div class="search-meta text-xl text-gray-600 dark:text-gray-400">
                    <?php
                    global $wp_query;
                    $total_results = $wp_query->found_posts;
                    printf(
                        esc_html(_n('%s result found', '%s results found', $total_results, 'aqualuxe')),
                        '<strong>' . number_format_i18n($total_results) . '</strong>'
                    );
                    ?>
                </div>
            <?php endif; ?>
        </header>
        
        <!-- Search Form -->
        <div class="search-form-container mb-12">
            <div class="max-w-2xl mx-auto">
                <?php get_search_form(); ?>
            </div>
        </div>
        
        <?php if (have_posts()): ?>
            
            <!-- Search Filters -->
            <div class="search-filters mb-8 p-4 bg-gray-50 dark:bg-dark-800 rounded-lg">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    
                    <!-- Content Type Filter -->
                    <div class="filter-group">
                        <label for="content-type" class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 block">
                            <?php esc_html_e('Content Type:', 'aqualuxe'); ?>
                        </label>
                        <select class="form-select" id="content-type" aria-label="<?php esc_attr_e('Filter by content type', 'aqualuxe'); ?>">
                            <option value=""><?php esc_html_e('All Types', 'aqualuxe'); ?></option>
                            <option value="post"><?php esc_html_e('Blog Posts', 'aqualuxe'); ?></option>
                            <option value="page"><?php esc_html_e('Pages', 'aqualuxe'); ?></option>
                            <?php if (class_exists('WooCommerce')): ?>
                                <option value="product"><?php esc_html_e('Products', 'aqualuxe'); ?></option>
                            <?php endif; ?>
                            <option value="service"><?php esc_html_e('Services', 'aqualuxe'); ?></option>
                            <option value="event"><?php esc_html_e('Events', 'aqualuxe'); ?></option>
                        </select>
                    </div>
                    
                    <!-- Date Filter -->
                    <div class="filter-group">
                        <label for="date-filter" class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 block">
                            <?php esc_html_e('Date:', 'aqualuxe'); ?>
                        </label>
                        <select class="form-select" id="date-filter" aria-label="<?php esc_attr_e('Filter by date', 'aqualuxe'); ?>">
                            <option value=""><?php esc_html_e('Any Date', 'aqualuxe'); ?></option>
                            <option value="week"><?php esc_html_e('Past Week', 'aqualuxe'); ?></option>
                            <option value="month"><?php esc_html_e('Past Month', 'aqualuxe'); ?></option>
                            <option value="year"><?php esc_html_e('Past Year', 'aqualuxe'); ?></option>
                        </select>
                    </div>
                    
                    <!-- Sort Options -->
                    <div class="filter-group">
                        <label for="sort-results" class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 block">
                            <?php esc_html_e('Sort by:', 'aqualuxe'); ?>
                        </label>
                        <select class="form-select" id="sort-results" aria-label="<?php esc_attr_e('Sort results', 'aqualuxe'); ?>">
                            <option value="relevance"><?php esc_html_e('Relevance', 'aqualuxe'); ?></option>
                            <option value="date-desc"><?php esc_html_e('Newest First', 'aqualuxe'); ?></option>
                            <option value="date-asc"><?php esc_html_e('Oldest First', 'aqualuxe'); ?></option>
                            <option value="title-asc"><?php esc_html_e('Title A-Z', 'aqualuxe'); ?></option>
                        </select>
                    </div>
                    
                </div>
            </div>
            
            <div class="search-content">
                <div class="grid lg:grid-cols-4 gap-8">
                    
                    <!-- Search Results -->
                    <div class="lg:col-span-3">
                        <div id="search-results" class="search-results space-y-8">
                            
                            <?php
                            while (have_posts()):
                                the_post();
                                ?>
                                <article id="post-<?php the_ID(); ?>" <?php post_class('search-result card p-6'); ?>>
                                    
                                    <!-- Post Type Badge -->
                                    <div class="post-type-badge mb-4">
                                        <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full <?php echo esc_attr(aqualuxe_get_post_type_color(get_post_type())); ?>">
                                            <?php echo esc_html(aqualuxe_get_post_type_label(get_post_type())); ?>
                                        </span>
                                    </div>
                                    
                                    <div class="flex flex-col md:flex-row gap-6">
                                        
                                        <!-- Thumbnail -->
                                        <?php if (has_post_thumbnail()): ?>
                                            <div class="search-thumbnail flex-shrink-0">
                                                <a href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
                                                    <?php
                                                    the_post_thumbnail('aqualuxe-search-thumb', [
                                                        'class' => 'w-full md:w-32 h-32 object-cover rounded-lg',
                                                        'loading' => 'lazy'
                                                    ]);
                                                    ?>
                                                </a>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <!-- Content -->
                                        <div class="search-content flex-1">
                                            
                                            <header class="search-entry-header mb-3">
                                                <h2 class="search-entry-title text-xl font-bold mb-2">
                                                    <a href="<?php the_permalink(); ?>" class="hover:text-primary-600 transition-colors">
                                                        <?php echo aqualuxe_highlight_search_terms(get_the_title(), get_search_query()); ?>
                                                    </a>
                                                </h2>
                                                
                                                <div class="search-entry-meta text-sm text-gray-500 dark:text-gray-400">
                                                    <time datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                                                        <?php echo esc_html(get_the_date()); ?>
                                                    </time>
                                                    <?php if ('post' === get_post_type() && get_the_author()): ?>
                                                        <span class="mx-2">•</span>
                                                        <span class="author">
                                                            <?php
                                                            printf(
                                                                esc_html__('by %s', 'aqualuxe'),
                                                                '<a href="' . esc_url(get_author_posts_url(get_the_author_meta('ID'))) . '" class="hover:text-primary-600">' . esc_html(get_the_author()) . '</a>'
                                                            );
                                                            ?>
                                                        </span>
                                                    <?php endif; ?>
                                                    <?php if (get_comments_number() > 0): ?>
                                                        <span class="mx-2">•</span>
                                                        <a href="<?php comments_link(); ?>" class="hover:text-primary-600">
                                                            <?php
                                                            printf(
                                                                esc_html(_n('%s comment', '%s comments', get_comments_number(), 'aqualuxe')),
                                                                number_format_i18n(get_comments_number())
                                                            );
                                                            ?>
                                                        </a>
                                                    <?php endif; ?>
                                                </div>
                                            </header>
                                            
                                            <div class="search-entry-summary text-gray-600 dark:text-gray-400 mb-4">
                                                <?php echo aqualuxe_highlight_search_terms(aqualuxe_get_search_excerpt(), get_search_query()); ?>
                                            </div>
                                            
                                            <!-- Categories/Tags -->
                                            <?php
                                            $categories = get_the_category();
                                            $tags = get_the_tags();
                                            if ($categories || $tags):
                                            ?>
                                                <div class="search-terms mb-4">
                                                    <?php if ($categories): ?>
                                                        <div class="categories mb-2">
                                                            <span class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                                                                <?php esc_html_e('Categories:', 'aqualuxe'); ?>
                                                            </span>
                                                            <?php
                                                            foreach ($categories as $category) {
                                                                echo '<a href="' . esc_url(get_category_link($category->term_id)) . '" class="inline-block bg-gray-100 dark:bg-dark-700 text-gray-700 dark:text-gray-300 px-2 py-1 rounded text-xs mr-2 mb-1 hover:bg-primary-100 dark:hover:bg-primary-900 transition-colors">' . esc_html($category->name) . '</a>';
                                                            }
                                                            ?>
                                                        </div>
                                                    <?php endif; ?>
                                                    
                                                    <?php if ($tags): ?>
                                                        <div class="tags">
                                                            <span class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                                                                <?php esc_html_e('Tags:', 'aqualuxe'); ?>
                                                            </span>
                                                            <?php
                                                            foreach (array_slice($tags, 0, 5) as $tag) {
                                                                echo '<a href="' . esc_url(get_tag_link($tag->term_id)) . '" class="inline-block bg-gray-100 dark:bg-dark-700 text-gray-700 dark:text-gray-300 px-2 py-1 rounded text-xs mr-2 mb-1 hover:bg-primary-100 dark:hover:bg-primary-900 transition-colors">' . esc_html($tag->name) . '</a>';
                                                            }
                                                            ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <a href="<?php the_permalink(); ?>" class="btn btn-outline btn-sm">
                                                <?php esc_html_e('Read More', 'aqualuxe'); ?>
                                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                </svg>
                                            </a>
                                            
                                        </div>
                                    </div>
                                </article>
                                <?php
                            endwhile;
                            ?>
                            
                        </div>
                        
                        <!-- Pagination -->
                        <?php
                        $pagination = paginate_links([
                            'type' => 'array',
                            'prev_text' => '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>' . esc_html__('Previous', 'aqualuxe'),
                            'next_text' => esc_html__('Next', 'aqualuxe') . '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>',
                        ]);
                        
                        if ($pagination):
                        ?>
                            <nav class="pagination-nav mt-12" aria-label="<?php esc_attr_e('Search results pagination', 'aqualuxe'); ?>">
                                <ul class="pagination flex flex-wrap justify-center space-x-2">
                                    <?php
                                    foreach ($pagination as $page):
                                        $classes = 'page-link btn';
                                        if (strpos($page, 'current') !== false) {
                                            $classes .= ' btn-primary';
                                        } else {
                                            $classes .= ' btn-outline';
                                        }
                                        
                                        $page = str_replace('class="', 'class="' . $classes . ' ', $page);
                                        echo '<li class="page-item">' . $page . '</li>';
                                    endforeach;
                                    ?>
                                </ul>
                            </nav>
                        <?php endif; ?>
                        
                    </div>
                    
                    <!-- Search Sidebar -->
                    <div class="lg:col-span-1">
                        <div class="search-sidebar space-y-8">
                            
                            <!-- Search Tips -->
                            <div class="widget search-tips card p-6">
                                <h3 class="widget-title text-lg font-semibold mb-4"><?php esc_html_e('Search Tips', 'aqualuxe'); ?></h3>
                                <ul class="text-sm text-gray-600 dark:text-gray-400 space-y-2">
                                    <li><?php esc_html_e('Use quotes for exact phrases: "aquarium maintenance"', 'aqualuxe'); ?></li>
                                    <li><?php esc_html_e('Use + to require words: +fish +tank', 'aqualuxe'); ?></li>
                                    <li><?php esc_html_e('Use - to exclude words: fish -saltwater', 'aqualuxe'); ?></li>
                                    <li><?php esc_html_e('Use * for partial matches: aqua*', 'aqualuxe'); ?></li>
                                </ul>
                            </div>
                            
                            <!-- Related Searches -->
                            <?php
                            $related_searches = aqualuxe_get_related_searches(get_search_query());
                            if ($related_searches):
                            ?>
                                <div class="widget related-searches card p-6">
                                    <h3 class="widget-title text-lg font-semibold mb-4"><?php esc_html_e('Related Searches', 'aqualuxe'); ?></h3>
                                    <ul class="space-y-2">
                                        <?php foreach ($related_searches as $search): ?>
                                            <li>
                                                <a href="<?php echo esc_url(home_url('/?s=' . urlencode($search))); ?>" class="text-primary-600 hover:text-primary-700 transition-colors">
                                                    <?php echo esc_html($search); ?>
                                                </a>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php endif; ?>
                            
                            <!-- Popular Content -->
                            <?php if (is_active_sidebar('search-sidebar')): ?>
                                <?php dynamic_sidebar('search-sidebar'); ?>
                            <?php else: ?>
                                <?php get_sidebar(); ?>
                            <?php endif; ?>
                            
                        </div>
                    </div>
                    
                </div>
            </div>
            
        <?php else: ?>
            
            <!-- No Results -->
            <div class="no-results text-center py-16">
                <div class="max-w-lg mx-auto">
                    <div class="mb-8">
                        <svg class="w-24 h-24 mx-auto text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold mb-4"><?php esc_html_e('No results found', 'aqualuxe'); ?></h2>
                    <p class="text-gray-600 dark:text-gray-400 mb-8">
                        <?php
                        printf(
                            esc_html__('Sorry, but nothing matched your search terms "%s". Please try again with different keywords.', 'aqualuxe'),
                            '<strong>' . get_search_query() . '</strong>'
                        );
                        ?>
                    </p>
                    
                    <!-- Search Suggestions -->
                    <div class="search-suggestions mb-8">
                        <h3 class="text-lg font-semibold mb-4"><?php esc_html_e('Search Suggestions:', 'aqualuxe'); ?></h3>
                        <ul class="text-sm text-gray-600 dark:text-gray-400 space-y-2">
                            <li><?php esc_html_e('Check your spelling', 'aqualuxe'); ?></li>
                            <li><?php esc_html_e('Try different or more general keywords', 'aqualuxe'); ?></li>
                            <li><?php esc_html_e('Try fewer keywords', 'aqualuxe'); ?></li>
                            <li><?php esc_html_e('Browse our categories below', 'aqualuxe'); ?></li>
                        </ul>
                    </div>
                    
                    <!-- Popular Categories -->
                    <?php
                    $popular_categories = get_categories([
                        'orderby' => 'count',
                        'order' => 'DESC',
                        'number' => 6,
                        'hide_empty' => true
                    ]);
                    
                    if ($popular_categories):
                    ?>
                        <div class="popular-categories">
                            <h3 class="text-lg font-semibold mb-4"><?php esc_html_e('Popular Categories', 'aqualuxe'); ?></h3>
                            <div class="flex flex-wrap justify-center gap-2">
                                <?php foreach ($popular_categories as $category): ?>
                                    <a href="<?php echo esc_url(get_category_link($category->term_id)); ?>" class="btn btn-outline btn-sm">
                                        <?php echo esc_html($category->name); ?>
                                        <span class="ml-1 text-xs opacity-75">(<?php echo esc_html($category->count); ?>)</span>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <div class="mt-8">
                        <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn-primary btn-lg">
                            <?php esc_html_e('Back to Home', 'aqualuxe'); ?>
                        </a>
                    </div>
                </div>
            </div>
            
        <?php endif; ?>
        
    </div>
</main>

<?php
get_footer();
?>
