<?php
/**
 * The template for displaying archive pages
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

get_header(); ?>

<main id="primary" class="site-main bg-gray-50">
    
    <!-- Archive Header -->
    <section class="archive-header py-16 bg-white border-b border-gray-200">
        <div class="<?php echo esc_attr(aqualuxe_get_container_classes()); ?>">
            <div class="text-center">
                
                <?php if (have_posts()) : ?>
                    
                    <!-- Archive Title -->
                    <h1 class="archive-title text-4xl md:text-5xl font-bold text-gray-900 mb-6">
                        <?php
                        if (is_category()) {
                            single_cat_title();
                        } elseif (is_tag()) {
                            single_tag_title();
                        } elseif (is_author()) {
                            printf(__('Posts by %s', 'aqualuxe'), '<span class="vcard">' . get_the_author() . '</span>');
                        } elseif (is_date()) {
                            if (is_year()) {
                                printf(__('Posts from %s', 'aqualuxe'), get_the_date('Y'));
                            } elseif (is_month()) {
                                printf(__('Posts from %s', 'aqualuxe'), get_the_date('F Y'));
                            } elseif (is_day()) {
                                printf(__('Posts from %s', 'aqualuxe'), get_the_date());
                            }
                        } elseif (is_post_type_archive()) {
                            post_type_archive_title();
                        } else {
                            _e('Archives', 'aqualuxe');
                        }
                        ?>
                    </h1>

                    <!-- Archive Description -->
                    <?php
                    $archive_description = get_the_archive_description();
                    if ($archive_description) :
                        ?>
                        <div class="archive-description text-lg text-gray-600 max-w-3xl mx-auto">
                            <?php echo $archive_description; ?>
                        </div>
                        <?php
                    endif;
                    ?>

                    <!-- Archive Meta -->
                    <div class="archive-meta mt-6 text-sm text-gray-500">
                        <?php
                        global $wp_query;
                        if ($wp_query->found_posts > 0) :
                            printf(
                                _n(
                                    'Showing %s post',
                                    'Showing %s posts',
                                    $wp_query->found_posts,
                                    'aqualuxe'
                                ),
                                number_format_i18n($wp_query->found_posts)
                            );
                        endif;
                        ?>
                    </div>

                <?php else : ?>
                    
                    <h1 class="archive-title text-4xl md:text-5xl font-bold text-gray-900 mb-6">
                        <?php _e('Nothing Found', 'aqualuxe'); ?>
                    </h1>
                    
                    <p class="text-lg text-gray-600">
                        <?php _e('It seems we can\'t find what you\'re looking for. Perhaps searching can help.', 'aqualuxe'); ?>
                    </p>
                    
                <?php endif; ?>

            </div>
        </div>
    </section>

    <?php if (have_posts()) : ?>
        
        <!-- Archive Content -->
        <section class="archive-content py-16">
            <div class="<?php echo esc_attr(aqualuxe_get_container_classes()); ?>">
                
                <!-- Filter and Sort Options -->
                <div class="archive-controls flex flex-col lg:flex-row justify-between items-center mb-12 p-6 bg-white rounded-lg shadow-sm">
                    
                    <!-- Filter Options -->
                    <div class="archive-filters flex flex-wrap gap-4 mb-4 lg:mb-0">
                        
                        <?php if (is_category() || is_tag() || is_home()) : ?>
                            <!-- Category Filter -->
                            <div class="filter-group">
                                <label for="category-filter" class="sr-only"><?php _e('Filter by category', 'aqualuxe'); ?></label>
                                <select id="category-filter" class="filter-select px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                                    <option value=""><?php _e('All Categories', 'aqualuxe'); ?></option>
                                    <?php
                                    $categories = get_categories(['hide_empty' => true]);
                                    foreach ($categories as $category) :
                                        $selected = (is_category($category->term_id)) ? 'selected' : '';
                                        ?>
                                        <option value="<?php echo esc_attr($category->term_id); ?>" <?php echo $selected; ?>>
                                            <?php echo esc_html($category->name); ?> (<?php echo $category->count; ?>)
                                        </option>
                                        <?php
                                    endforeach;
                                    ?>
                                </select>
                            </div>
                        <?php endif; ?>

                        <?php if (is_date()) : ?>
                            <!-- Date Filter -->
                            <div class="filter-group">
                                <label for="date-filter" class="sr-only"><?php _e('Filter by date', 'aqualuxe'); ?></label>
                                <select id="date-filter" class="filter-select px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                                    <option value=""><?php _e('All Dates', 'aqualuxe'); ?></option>
                                    <?php wp_get_archives(['type' => 'monthly', 'format' => 'option', 'show_post_count' => true]); ?>
                                </select>
                            </div>
                        <?php endif; ?>

                    </div>

                    <!-- Sort Options -->
                    <div class="archive-sort">
                        <label for="sort-posts" class="sr-only"><?php _e('Sort posts', 'aqualuxe'); ?></label>
                        <select id="sort-posts" class="sort-select px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                            <option value="date-desc"><?php _e('Newest First', 'aqualuxe'); ?></option>
                            <option value="date-asc"><?php _e('Oldest First', 'aqualuxe'); ?></option>
                            <option value="title-asc"><?php _e('Title A-Z', 'aqualuxe'); ?></option>
                            <option value="title-desc"><?php _e('Title Z-A', 'aqualuxe'); ?></option>
                            <?php if (function_exists('wc_get_page_id')) : ?>
                                <option value="popularity"><?php _e('Most Popular', 'aqualuxe'); ?></option>
                            <?php endif; ?>
                        </select>
                    </div>

                    <!-- View Toggle -->
                    <div class="view-toggle flex items-center gap-2 ml-4">
                        <span class="text-sm text-gray-600"><?php _e('View:', 'aqualuxe'); ?></span>
                        <button type="button" class="view-btn view-grid active" data-view="grid" title="<?php esc_attr_e('Grid View', 'aqualuxe'); ?>">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                            </svg>
                        </button>
                        <button type="button" class="view-btn view-list" data-view="list" title="<?php esc_attr_e('List View', 'aqualuxe'); ?>">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Posts Grid -->
                <div id="posts-container" class="posts-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    
                    <?php while (have_posts()) : the_post(); ?>
                        
                        <article id="post-<?php the_ID(); ?>" <?php post_class('post-item bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow'); ?>>
                            
                            <!-- Post Thumbnail -->
                            <?php if (has_post_thumbnail()) : ?>
                                <div class="post-thumbnail relative">
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_post_thumbnail('medium_large', ['class' => 'w-full h-48 object-cover']); ?>
                                    </a>
                                    
                                    <!-- Post Format Icon -->
                                    <?php
                                    $post_format = get_post_format();
                                    if ($post_format) :
                                        ?>
                                        <div class="post-format absolute top-4 left-4 w-8 h-8 bg-primary text-white rounded-full flex items-center justify-center">
                                            <?php
                                            switch ($post_format) {
                                                case 'video':
                                                    echo '<svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"></path></svg>';
                                                    break;
                                                case 'gallery':
                                                    echo '<svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path></svg>';
                                                    break;
                                                case 'quote':
                                                    echo '<svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6 3a1 1 0 000 2h8a1 1 0 100-2H6zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM3 11a1 1 0 100 2h3a1 1 0 100-2H3z" clip-rule="evenodd"></path></svg>';
                                                    break;
                                                default:
                                                    echo '<svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path></svg>';
                                                    break;
                                            }
                                            ?>
                                        </div>
                                        <?php
                                    endif;
                                    ?>
                                </div>
                            <?php endif; ?>

                            <!-- Post Content -->
                            <div class="post-content p-6">
                                
                                <!-- Post Meta -->
                                <div class="post-meta flex flex-wrap items-center gap-4 text-sm text-gray-500 mb-3">
                                    <time datetime="<?php echo get_the_date('c'); ?>" class="post-date">
                                        <?php echo get_the_date(); ?>
                                    </time>
                                    
                                    <?php
                                    $categories = get_the_category();
                                    if ($categories) :
                                        ?>
                                        <div class="post-categories">
                                            <?php foreach ($categories as $category) : ?>
                                                <a href="<?php echo esc_url(get_category_link($category->term_id)); ?>" class="category-link text-primary hover:text-primary-dark">
                                                    <?php echo esc_html($category->name); ?>
                                                </a>
                                            <?php endforeach; ?>
                                        </div>
                                        <?php
                                    endif;
                                    ?>
                                    
                                    <div class="post-author">
                                        <?php _e('by', 'aqualuxe'); ?> 
                                        <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>" class="author-link text-gray-700 hover:text-primary">
                                            <?php the_author(); ?>
                                        </a>
                                    </div>
                                </div>

                                <!-- Post Title -->
                                <h2 class="post-title text-xl font-semibold text-gray-900 mb-3 line-clamp-2">
                                    <a href="<?php the_permalink(); ?>" class="hover:text-primary transition-colors">
                                        <?php the_title(); ?>
                                    </a>
                                </h2>

                                <!-- Post Excerpt -->
                                <div class="post-excerpt text-gray-600 mb-4 line-clamp-3">
                                    <?php
                                    if (has_excerpt()) {
                                        the_excerpt();
                                    } else {
                                        echo wp_trim_words(get_the_content(), 20, '...');
                                    }
                                    ?>
                                </div>

                                <!-- Post Footer -->
                                <div class="post-footer flex justify-between items-center">
                                    <a href="<?php the_permalink(); ?>" class="read-more text-primary hover:text-primary-dark font-medium transition-colors">
                                        <?php _e('Read More', 'aqualuxe'); ?> →
                                    </a>
                                    
                                    <div class="post-stats flex items-center gap-3 text-sm text-gray-400">
                                        <?php if (function_exists('get_post_views_count')) : ?>
                                            <span class="post-views flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                                                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path>
                                                </svg>
                                                <?php echo get_post_views_count(get_the_ID()); ?>
                                            </span>
                                        <?php endif; ?>
                                        
                                        <span class="post-comments flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z" clip-rule="evenodd"></path>
                                            </svg>
                                            <?php comments_number('0', '1', '%'); ?>
                                        </span>
                                    </div>
                                </div>

                            </div>
                        </article>

                    <?php endwhile; ?>

                </div>

                <!-- Pagination -->
                <div class="archive-pagination mt-12">
                    <?php
                    $pagination_args = [
                        'mid_size' => 2,
                        'prev_text' => '<svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>' . __('Previous', 'aqualuxe'),
                        'next_text' => __('Next', 'aqualuxe') . '<svg class="w-5 h-5 ml-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>',
                        'class' => 'pagination flex justify-center items-center space-x-2',
                    ];
                    
                    echo aqualuxe_get_pagination($pagination_args);
                    ?>
                </div>

            </div>
        </section>

    <?php else : ?>
        
        <!-- No Posts Found -->
        <section class="no-posts-found py-16">
            <div class="<?php echo esc_attr(aqualuxe_get_container_classes()); ?>">
                <div class="text-center">
                    
                    <!-- No Posts Illustration -->
                    <div class="no-posts-illustration w-64 h-64 mx-auto mb-8 bg-gray-100 rounded-full flex items-center justify-center">
                        <svg class="w-32 h-32 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5 4a3 3 0 00-3 3v6a3 3 0 003 3h10a3 3 0 003-3V7a3 3 0 00-3-3H5zm-1 9v-1h5v2H5a1 1 0 01-1-1zm7 1h4a1 1 0 001-1v-1h-5v2zm0-4h5V8h-5v2zM9 8H4v2h5V8z" clip-rule="evenodd"></path>
                        </svg>
                    </div>

                    <h2 class="text-3xl font-bold text-gray-900 mb-4">
                        <?php _e('No Posts Found', 'aqualuxe'); ?>
                    </h2>
                    
                    <p class="text-lg text-gray-600 mb-8 max-w-2xl mx-auto">
                        <?php _e('Sorry, but nothing matched your search terms. Please try again with some different keywords or browse our other content.', 'aqualuxe'); ?>
                    </p>

                    <!-- Search Form -->
                    <div class="search-form-container max-w-md mx-auto mb-8">
                        <?php get_search_form(); ?>
                    </div>

                    <!-- Helpful Links -->
                    <div class="helpful-links flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="<?php echo esc_url(home_url('/')); ?>" class="inline-block bg-primary hover:bg-primary-dark text-white font-semibold px-6 py-3 rounded-lg transition-colors">
                            <?php _e('Go Home', 'aqualuxe'); ?>
                        </a>
                        
                        <a href="<?php echo esc_url(home_url('/blog')); ?>" class="inline-block border-2 border-primary text-primary hover:bg-primary hover:text-white font-semibold px-6 py-3 rounded-lg transition-colors">
                            <?php _e('Browse All Posts', 'aqualuxe'); ?>
                        </a>
                    </div>

                </div>
            </div>
        </section>

    <?php endif; ?>

</main><!-- #main -->

<?php get_footer(); ?>

<style>
/* Archive page specific styles */
.view-btn {
    padding: 0.5rem;
    color: #6b7280;
    transition: color 0.3s ease;
    border-radius: 0.375rem;
}

.view-btn:hover {
    color: var(--color-primary);
}

.view-btn.active {
    color: var(--color-primary);
    background-color: rgba(var(--color-primary-rgb), 0.1);
}

.posts-grid.list-view {
    grid-template-columns: repeat(1, minmax(0, 1fr));
}

.posts-grid.list-view .post-item {
    display: flex;
    flex-direction: row;
}

.posts-grid.list-view .post-thumbnail {
    width: 12rem;
    flex-shrink: 0;
}

.posts-grid.list-view .post-thumbnail img {
    height: 100%;
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.filter-select,
.sort-select {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
    background-position: right 0.5rem center;
    background-repeat: no-repeat;
    background-size: 1.5em 1.5em;
    padding-right: 2.5rem;
    appearance: none;
}
</style>

<script>
// Archive page JavaScript functionality
document.addEventListener('DOMContentLoaded', function() {
    
    // View toggle functionality
    const viewButtons = document.querySelectorAll('.view-btn');
    const postsContainer = document.getElementById('posts-container');
    
    viewButtons.forEach(button => {
        button.addEventListener('click', function() {
            const view = this.getAttribute('data-view');
            
            // Update button states
            viewButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            
            // Update posts container classes
            postsContainer.classList.remove('list-view', 'grid-view');
            postsContainer.classList.add(view + '-view');
        });
    });
    
    // Filter and sort functionality (basic implementation)
    const categoryFilter = document.getElementById('category-filter');
    const sortSelect = document.getElementById('sort-posts');
    
    if (categoryFilter) {
        categoryFilter.addEventListener('change', function() {
            const categoryId = this.value;
            if (categoryId) {
                window.location.href = '<?php echo esc_url(home_url('/category/')); ?>' + categoryId;
            } else {
                window.location.href = '<?php echo esc_url(home_url('/blog')); ?>';
            }
        });
    }
    
    if (sortSelect) {
        sortSelect.addEventListener('change', function() {
            const sortBy = this.value;
            const url = new URL(window.location.href);
            url.searchParams.set('orderby', sortBy);
            window.location.href = url.toString();
        });
    }
    
});
</script>
