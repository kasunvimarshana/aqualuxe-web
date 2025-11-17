<?php
/**
 * Archive template for blog posts, categories, tags, etc.
 *
 * @package AquaLuxe
 * @version 1.0.0
 */

get_header(); ?>

<main id="main" class="site-main" role="main">
    <div class="container mx-auto px-4 py-8">
        
        <?php if (have_posts()): ?>
            
            <!-- Archive Header -->
            <header class="archive-header text-center mb-12">
                <?php
                the_archive_title('<h1 class="archive-title text-4xl md:text-5xl font-bold mb-4">', '</h1>');
                
                $archive_description = get_the_archive_description();
                if ($archive_description):
                ?>
                    <div class="archive-description text-xl text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
                        <?php echo wp_kses_post($archive_description); ?>
                    </div>
                <?php endif; ?>
                
                <!-- Archive Meta -->
                <div class="archive-meta mt-6 text-sm text-gray-500 dark:text-gray-400">
                    <?php
                    global $wp_query;
                    $total_posts = $wp_query->found_posts;
                    $posts_per_page = get_option('posts_per_page');
                    $current_page = max(1, get_query_var('paged'));
                    $start_post = (($current_page - 1) * $posts_per_page) + 1;
                    $end_post = min($total_posts, $current_page * $posts_per_page);
                    
                    printf(
                        esc_html__('Showing %1$s-%2$s of %3$s posts', 'aqualuxe'),
                        number_format_i18n($start_post),
                        number_format_i18n($end_post),
                        number_format_i18n($total_posts)
                    );
                    ?>
                </div>
            </header>
            
            <!-- Filter Bar -->
            <div class="filter-bar mb-8 p-4 bg-gray-50 dark:bg-dark-800 rounded-lg">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    
                    <!-- View Toggle -->
                    <div class="view-toggle">
                        <div class="btn-group" role="group" aria-label="<?php esc_attr_e('View toggle', 'aqualuxe'); ?>">
                            <button type="button" class="btn btn-outline btn-sm active" data-view="grid" aria-pressed="true">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                                </svg>
                                <span class="sr-only"><?php esc_html_e('Grid view', 'aqualuxe'); ?></span>
                            </button>
                            <button type="button" class="btn btn-outline btn-sm" data-view="list" aria-pressed="false">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                                </svg>
                                <span class="sr-only"><?php esc_html_e('List view', 'aqualuxe'); ?></span>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Sort Options -->
                    <div class="sort-options">
                        <select class="form-select" id="sort-posts" aria-label="<?php esc_attr_e('Sort posts', 'aqualuxe'); ?>">
                            <option value="date-desc" <?php selected(get_query_var('orderby'), 'date'); ?>><?php esc_html_e('Newest First', 'aqualuxe'); ?></option>
                            <option value="date-asc"><?php esc_html_e('Oldest First', 'aqualuxe'); ?></option>
                            <option value="title-asc"><?php esc_html_e('Title A-Z', 'aqualuxe'); ?></option>
                            <option value="title-desc"><?php esc_html_e('Title Z-A', 'aqualuxe'); ?></option>
                            <option value="comment-count"><?php esc_html_e('Most Comments', 'aqualuxe'); ?></option>
                        </select>
                    </div>
                    
                </div>
            </div>
            
            <div class="archive-content">
                <div class="grid md:grid-cols-3 gap-8">
                    
                    <!-- Posts Grid -->
                    <div class="md:col-span-2">
                        <div id="posts-container" class="posts-grid grid gap-8" data-view="grid">
                            
                            <?php
                            while (have_posts()):
                                the_post();
                                get_template_part('templates/content/content', get_post_type());
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
                            <nav class="pagination-nav mt-12" aria-label="<?php esc_attr_e('Posts pagination', 'aqualuxe'); ?>">
                                <ul class="pagination flex flex-wrap justify-center space-x-2">
                                    <?php
                                    foreach ($pagination as $page):
                                        $classes = 'page-link btn';
                                        if (strpos($page, 'current') !== false) {
                                            $classes .= ' btn-primary';
                                        } else {
                                            $classes .= ' btn-outline';
                                        }
                                        
                                        // Add proper classes to the pagination link
                                        $page = str_replace('class="', 'class="' . $classes . ' ', $page);
                                        echo '<li class="page-item">' . $page . '</li>';
                                    endforeach;
                                    ?>
                                </ul>
                            </nav>
                        <?php endif; ?>
                        
                        <!-- Load More Button -->
                        <?php if (get_next_posts_link()): ?>
                            <div class="load-more text-center mt-8">
                                <button type="button" class="btn btn-outline btn-lg" id="load-more-posts" data-loading="false">
                                    <span class="btn-text"><?php esc_html_e('Load More Posts', 'aqualuxe'); ?></span>
                                    <span class="btn-loading hidden">
                                        <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        <?php esc_html_e('Loading...', 'aqualuxe'); ?>
                                    </span>
                                </button>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Sidebar -->
                    <div class="md:col-span-1">
                        <?php
                        // Archive-specific sidebar
                        if (is_category() && is_active_sidebar('category-sidebar')) {
                            dynamic_sidebar('category-sidebar');
                        } elseif (is_tag() && is_active_sidebar('tag-sidebar')) {
                            dynamic_sidebar('tag-sidebar');
                        } elseif (is_author() && is_active_sidebar('author-sidebar')) {
                            dynamic_sidebar('author-sidebar');
                        } elseif (is_date() && is_active_sidebar('date-sidebar')) {
                            dynamic_sidebar('date-sidebar');
                        } elseif (is_active_sidebar('archive-sidebar')) {
                            dynamic_sidebar('archive-sidebar');
                        } else {
                            get_sidebar();
                        }
                        ?>
                    </div>
                    
                </div>
            </div>
            
        <?php else: ?>
            
            <!-- No Posts Found -->
            <div class="no-posts text-center py-16">
                <div class="max-w-lg mx-auto">
                    <div class="mb-8">
                        <svg class="w-24 h-24 mx-auto text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold mb-4"><?php esc_html_e('No posts found', 'aqualuxe'); ?></h2>
                    <p class="text-gray-600 dark:text-gray-400 mb-8">
                        <?php esc_html_e('Sorry, but nothing matched your search criteria. Please try again with different keywords.', 'aqualuxe'); ?>
                    </p>
                    <div class="space-y-4">
                        <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn-primary btn-lg">
                            <?php esc_html_e('Back to Home', 'aqualuxe'); ?>
                        </a>
                        <p class="text-sm text-gray-500">
                            <?php esc_html_e('or try searching for something else', 'aqualuxe'); ?>
                        </p>
                        <?php get_search_form(); ?>
                    </div>
                </div>
            </div>
            
        <?php endif; ?>
        
    </div>
</main>

<?php
get_footer();
?>
