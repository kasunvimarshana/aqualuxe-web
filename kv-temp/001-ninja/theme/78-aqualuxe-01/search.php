<?php
/**
 * The template for displaying search results pages
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

get_header(); ?>

<main id="primary" class="site-main bg-gray-50">
    
    <!-- Search Header -->
    <section class="search-header py-16 bg-white border-b border-gray-200">
        <div class="<?php echo esc_attr(aqualuxe_get_container_classes()); ?>">
            <div class="text-center">
                
                <?php if (have_posts()) : ?>
                    
                    <!-- Search Results Title -->
                    <h1 class="search-title text-4xl md:text-5xl font-bold text-gray-900 mb-6">
                        <?php
                        printf(
                            esc_html__('Search Results for: %s', 'aqualuxe'),
                            '<span class="search-query text-primary">"' . get_search_query() . '"</span>'
                        );
                        ?>
                    </h1>

                    <!-- Search Results Meta -->
                    <div class="search-meta mt-6 text-lg text-gray-600">
                        <?php
                        global $wp_query;
                        if ($wp_query->found_posts > 0) :
                            printf(
                                _n(
                                    'Found %s result',
                                    'Found %s results',
                                    $wp_query->found_posts,
                                    'aqualuxe'
                                ),
                                '<strong>' . number_format_i18n($wp_query->found_posts) . '</strong>'
                            );
                        endif;
                        ?>
                    </div>

                <?php else : ?>
                    
                    <h1 class="search-title text-4xl md:text-5xl font-bold text-gray-900 mb-6">
                        <?php
                        printf(
                            esc_html__('No Results for: %s', 'aqualuxe'),
                            '<span class="search-query text-primary">"' . get_search_query() . '"</span>'
                        );
                        ?>
                    </h1>
                    
                    <p class="text-lg text-gray-600 mb-8">
                        <?php esc_html_e('Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'aqualuxe'); ?>
                    </p>
                    
                <?php endif; ?>

                <!-- Search Form -->
                <div class="search-form-container max-w-2xl mx-auto mt-8">
                    <form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
                        <div class="flex">
                            <input type="search" class="search-field flex-1 px-6 py-4 border border-gray-300 rounded-l-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-lg" placeholder="<?php echo esc_attr_x('Search for posts, products, pages...', 'placeholder', 'aqualuxe'); ?>" value="<?php echo get_search_query(); ?>" name="s" required>
                            <button type="submit" class="search-submit bg-primary hover:bg-primary-dark text-white px-8 py-4 rounded-r-lg transition-colors">
                                <span class="sr-only"><?php echo _x('Search', 'submit button', 'aqualuxe'); ?></span>
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </section>

    <?php if (have_posts()) : ?>
        
        <!-- Search Results -->
        <section class="search-results py-16">
            <div class="<?php echo esc_attr(aqualuxe_get_container_classes()); ?>">
                
                <!-- Search Filters -->
                <div class="search-filters mb-12 p-6 bg-white rounded-lg shadow-sm">
                    <div class="flex flex-col lg:flex-row justify-between items-center gap-4">
                        
                        <!-- Content Type Filter -->
                        <div class="filter-group flex flex-wrap gap-4">
                            <div class="content-type-filter">
                                <label for="content-type" class="block text-sm font-medium text-gray-700 mb-2">
                                    <?php esc_html_e('Content Type:', 'aqualuxe'); ?>
                                </label>
                                <select id="content-type" class="filter-select px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                                    <option value=""><?php esc_html_e('All Content', 'aqualuxe'); ?></option>
                                    <option value="post"><?php esc_html_e('Blog Posts', 'aqualuxe'); ?></option>
                                    <option value="page"><?php esc_html_e('Pages', 'aqualuxe'); ?></option>
                                    <?php if (aqualuxe_is_woocommerce_active()) : ?>
                                        <option value="product"><?php esc_html_e('Products', 'aqualuxe'); ?></option>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>

                        <!-- Sort Options -->
                        <div class="sort-group">
                            <label for="search-sort" class="block text-sm font-medium text-gray-700 mb-2">
                                <?php esc_html_e('Sort by:', 'aqualuxe'); ?>
                            </label>
                            <select id="search-sort" class="sort-select px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                                <option value="relevance"><?php esc_html_e('Relevance', 'aqualuxe'); ?></option>
                                <option value="date-desc"><?php esc_html_e('Newest First', 'aqualuxe'); ?></option>
                                <option value="date-asc"><?php esc_html_e('Oldest First', 'aqualuxe'); ?></option>
                                <option value="title-asc"><?php esc_html_e('Title A-Z', 'aqualuxe'); ?></option>
                                <option value="title-desc"><?php esc_html_e('Title Z-A', 'aqualuxe'); ?></option>
                            </select>
                        </div>

                    </div>
                </div>

                <!-- Search Results Grid -->
                <div class="search-results-grid space-y-8">
                    
                    <?php while (have_posts()) : the_post(); ?>
                        
                        <article id="post-<?php the_ID(); ?>" <?php post_class('search-result-item bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow'); ?>>
                            
                            <div class="flex flex-col md:flex-row">
                                
                                <!-- Result Thumbnail -->
                                <?php if (has_post_thumbnail()) : ?>
                                    <div class="result-thumbnail md:w-64 flex-shrink-0">
                                        <a href="<?php the_permalink(); ?>">
                                            <?php the_post_thumbnail('medium', ['class' => 'w-full h-48 md:h-full object-cover']); ?>
                                        </a>
                                    </div>
                                <?php endif; ?>

                                <!-- Result Content -->
                                <div class="result-content flex-1 p-6">
                                    
                                    <!-- Result Meta -->
                                    <div class="result-meta flex items-center gap-4 text-sm text-gray-500 mb-3">
                                        <span class="result-type bg-primary bg-opacity-10 text-primary px-3 py-1 rounded-full font-medium">
                                            <?php
                                            $post_type = get_post_type();
                                            switch ($post_type) {
                                                case 'product':
                                                    esc_html_e('Product', 'aqualuxe');
                                                    break;
                                                case 'page':
                                                    esc_html_e('Page', 'aqualuxe');
                                                    break;
                                                case 'post':
                                                default:
                                                    esc_html_e('Blog Post', 'aqualuxe');
                                                    break;
                                            }
                                            ?>
                                        </span>
                                        
                                        <time datetime="<?php echo get_the_date('c'); ?>" class="result-date">
                                            <?php echo get_the_date(); ?>
                                        </time>
                                        
                                        <?php if ($post_type === 'post') : ?>
                                            <?php
                                            $categories = get_the_category();
                                            if ($categories) :
                                                ?>
                                                <div class="result-categories">
                                                    <?php foreach ($categories as $category) : ?>
                                                        <a href="<?php echo esc_url(get_category_link($category->term_id)); ?>" class="category-link text-primary hover:text-primary-dark">
                                                            <?php echo esc_html($category->name); ?>
                                                        </a>
                                                    <?php endforeach; ?>
                                                </div>
                                                <?php
                                            endif;
                                            ?>
                                        <?php endif; ?>
                                        
                                        <?php if ($post_type === 'product' && aqualuxe_is_woocommerce_active()) : ?>
                                            <?php
                                            if (function_exists('wc_get_product')) {
                                                $product = wc_get_product(get_the_ID());
                                                if ($product) :
                                                    ?>
                                                    <span class="product-price text-primary font-semibold">
                                                        <?php echo $product->get_price_html(); ?>
                                                    </span>
                                                    <?php
                                                endif;
                                            }
                                            ?>
                                        <?php endif; ?>
                                    </div>

                                    <!-- Result Title -->
                                    <h2 class="result-title text-2xl font-semibold text-gray-900 mb-3">
                                        <a href="<?php the_permalink(); ?>" class="hover:text-primary transition-colors">
                                            <?php 
                                            // Highlight search terms in title
                                            $title = get_the_title();
                                            $search_query = get_search_query();
                                            if ($search_query) {
                                                $highlighted_title = preg_replace('/(' . preg_quote($search_query, '/') . ')/i', '<mark class="bg-yellow-200 px-1 rounded">$1</mark>', $title);
                                                echo $highlighted_title;
                                            } else {
                                                echo $title;
                                            }
                                            ?>
                                        </a>
                                    </h2>

                                    <!-- Result Excerpt -->
                                    <div class="result-excerpt text-gray-600 mb-4">
                                        <?php
                                        $excerpt = get_the_excerpt();
                                        if (!$excerpt) {
                                            $excerpt = wp_trim_words(get_the_content(), 30, '...');
                                        }
                                        
                                        // Highlight search terms in excerpt
                                        $search_query = get_search_query();
                                        if ($search_query) {
                                            $highlighted_excerpt = preg_replace('/(' . preg_quote($search_query, '/') . ')/i', '<mark class="bg-yellow-200 px-1 rounded">$1</mark>', $excerpt);
                                            echo $highlighted_excerpt;
                                        } else {
                                            echo $excerpt;
                                        }
                                        ?>
                                    </div>

                                    <!-- Result Footer -->
                                    <div class="result-footer flex justify-between items-center">
                                        <a href="<?php the_permalink(); ?>" class="read-more inline-flex items-center text-primary hover:text-primary-dark font-medium transition-colors">
                                            <?php
                                            if ($post_type === 'product') {
                                                esc_html_e('View Product', 'aqualuxe');
                                            } elseif ($post_type === 'page') {
                                                esc_html_e('View Page', 'aqualuxe');
                                            } else {
                                                esc_html_e('Read More', 'aqualuxe');
                                            }
                                            ?>
                                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                            </svg>
                                        </a>
                                        
                                        <div class="result-stats flex items-center gap-3 text-sm text-gray-400">
                                            <?php if ($post_type === 'post') : ?>
                                                <span class="result-comments flex items-center gap-1">
                                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    <?php comments_number('0', '1', '%'); ?>
                                                </span>
                                            <?php endif; ?>
                                            
                                            <?php if ($post_type === 'product' && aqualuxe_is_woocommerce_active()) : ?>
                                                <?php
                                                if (function_exists('wc_get_product')) {
                                                    $product = wc_get_product(get_the_ID());
                                                    if ($product) :
                                                        $rating_count = $product->get_rating_count();
                                                        $average_rating = $product->get_average_rating();
                                                        if ($rating_count > 0) :
                                                            ?>
                                                            <span class="product-rating flex items-center gap-1">
                                                                <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                                </svg>
                                                                <?php printf('%.1f (%d)', $average_rating, $rating_count); ?>
                                                            </span>
                                                            <?php
                                                        endif;
                                                    endif;
                                                }
                                                ?>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                </div>

                            </div>
                            
                        </article>

                    <?php endwhile; ?>

                </div>

                <!-- Pagination -->
                <div class="search-pagination mt-12">
                    <?php
                    $pagination_args = [
                        'mid_size' => 2,
                        'prev_text' => '<svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>' . __('Previous', 'aqualuxe'),
                        'next_text' => __('Next', 'aqualuxe') . '<svg class="w-5 h-5 ml-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>',
                    ];
                    
                    echo aqualuxe_get_pagination($pagination_args);
                    ?>
                </div>

            </div>
        </section>

    <?php else : ?>
        
        <!-- No Results Found -->
        <section class="no-results py-16">
            <div class="<?php echo esc_attr(aqualuxe_get_container_classes()); ?>">
                <div class="text-center">
                    
                    <!-- Search Suggestions -->
                    <div class="search-suggestions max-w-4xl mx-auto mb-12">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6">
                            <?php esc_html_e('Search Suggestions', 'aqualuxe'); ?>
                        </h2>
                        
                        <div class="suggestions-grid grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <!-- Search Tips -->
                            <div class="suggestion-card bg-white p-6 rounded-lg shadow-md">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-primary" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                    </svg>
                                    <?php esc_html_e('Search Tips', 'aqualuxe'); ?>
                                </h3>
                                <ul class="text-gray-600 space-y-2 text-sm">
                                    <li>• <?php esc_html_e('Try different or more general keywords', 'aqualuxe'); ?></li>
                                    <li>• <?php esc_html_e('Check your spelling', 'aqualuxe'); ?></li>
                                    <li>• <?php esc_html_e('Use fewer words', 'aqualuxe'); ?></li>
                                    <li>• <?php esc_html_e('Try synonyms or related terms', 'aqualuxe'); ?></li>
                                </ul>
                            </div>

                            <!-- Popular Searches -->
                            <div class="suggestion-card bg-white p-6 rounded-lg shadow-md">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-primary" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.592-.591-.98-.985-1.348-1.467-.363-.476-.724-1.063-1.207-2.03zM12.12 15.12A3 3 0 017 13s.879.5 2.5.5c0-1 .5-4 1.25-4.5.5 1 .786 1.293 1.371 1.879A2.99 2.99 0 0113 13a2.99 2.99 0 01-.879 2.121z" clip-rule="evenodd"></path>
                                    </svg>
                                    <?php esc_html_e('Popular Searches', 'aqualuxe'); ?>
                                </h3>
                                <div class="popular-searches flex flex-wrap gap-2">
                                    <?php
                                    $popular_searches = [
                                        'aquarium',
                                        'fish',
                                        'plants',
                                        'filters',
                                        'lighting',
                                        'tropical fish',
                                        'aquascaping',
                                        'maintenance'
                                    ];
                                    
                                    foreach ($popular_searches as $search_term) :
                                        ?>
                                        <a href="<?php echo esc_url(home_url('/?s=' . urlencode($search_term))); ?>" class="popular-search-tag bg-gray-100 hover:bg-primary hover:text-white text-gray-700 px-3 py-1 rounded-full text-sm transition-colors">
                                            <?php echo esc_html($search_term); ?>
                                        </a>
                                        <?php
                                    endforeach;
                                    ?>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- Helpful Links -->
                    <div class="helpful-links">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6">
                            <?php esc_html_e('Browse Our Content', 'aqualuxe'); ?>
                        </h2>
                        
                        <div class="links-grid grid grid-cols-1 md:grid-cols-3 gap-6 max-w-4xl mx-auto">
                            
                            <a href="<?php echo esc_url(home_url('/blog')); ?>" class="link-card bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow text-center">
                                <div class="link-icon w-16 h-16 bg-primary bg-opacity-10 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8 text-primary" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M2 5a2 2 0 012-2h8a2 2 0 012 2v10a2 2 0 002 2H4a2 2 0 01-2-2V5zm3 1h6v4H5V6zm6 6H5v2h6v-2z" clip-rule="evenodd"></path>
                                        <path d="M15 7h1a2 2 0 012 2v5.5a1.5 1.5 0 01-3 0V9a1 1 0 00-1-1h-1v4.396a1 1 0 01-1.1.99L13 13h-2v.396a1 1 0 01-1.1.99L10 14H8v.396a1 1 0 01-1.1.99L7 15H5v.396a1 1 0 01-1.1.99L4 16H3v1a1 1 0 01-1 1H1v-1h1V9a2 2 0 012-2h1V7z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-2"><?php esc_html_e('Blog Posts', 'aqualuxe'); ?></h3>
                                <p class="text-gray-600 text-sm"><?php esc_html_e('Latest aquarium tips and guides', 'aqualuxe'); ?></p>
                            </a>

                            <?php if (aqualuxe_is_woocommerce_active()) : ?>
                                <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="link-card bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow text-center">
                                    <div class="link-icon w-16 h-16 bg-primary bg-opacity-10 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <svg class="w-8 h-8 text-primary" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 2L3 7v11a1 1 0 001 1h4a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1h4a1 1 0 001-1V7l-7-5z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2"><?php esc_html_e('Products', 'aqualuxe'); ?></h3>
                                    <p class="text-gray-600 text-sm"><?php esc_html_e('Premium aquarium equipment and fish', 'aqualuxe'); ?></p>
                                </a>
                            <?php endif; ?>

                            <a href="<?php echo esc_url(home_url('/contact')); ?>" class="link-card bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow text-center">
                                <div class="link-icon w-16 h-16 bg-primary bg-opacity-10 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8 text-primary" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-2"><?php esc_html_e('Contact Us', 'aqualuxe'); ?></h3>
                                <p class="text-gray-600 text-sm"><?php esc_html_e('Get expert aquarium advice', 'aqualuxe'); ?></p>
                            </a>

                        </div>
                    </div>

                </div>
            </div>
        </section>

    <?php endif; ?>

</main><!-- #main -->

<?php get_footer(); ?>

<style>
/* Search page specific styles */
.search-query {
    word-break: break-word;
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

.popular-search-tag:hover {
    transform: translateY(-1px);
}

.link-card:hover {
    transform: translateY(-2px);
}

mark {
    background-color: #fef08a;
    padding: 0 0.25rem;
    border-radius: 0.25rem;
}
</style>

<script>
// Search page JavaScript functionality
document.addEventListener('DOMContentLoaded', function() {
    
    // Content type filter
    const contentTypeFilter = document.getElementById('content-type');
    if (contentTypeFilter) {
        contentTypeFilter.addEventListener('change', function() {
            const postType = this.value;
            const url = new URL(window.location.href);
            
            if (postType) {
                url.searchParams.set('post_type', postType);
            } else {
                url.searchParams.delete('post_type');
            }
            
            window.location.href = url.toString();
        });
    }
    
    // Sort functionality
    const sortSelect = document.getElementById('search-sort');
    if (sortSelect) {
        sortSelect.addEventListener('change', function() {
            const sortBy = this.value;
            const url = new URL(window.location.href);
            
            switch (sortBy) {
                case 'date-desc':
                    url.searchParams.set('orderby', 'date');
                    url.searchParams.set('order', 'desc');
                    break;
                case 'date-asc':
                    url.searchParams.set('orderby', 'date');
                    url.searchParams.set('order', 'asc');
                    break;
                case 'title-asc':
                    url.searchParams.set('orderby', 'title');
                    url.searchParams.set('order', 'asc');
                    break;
                case 'title-desc':
                    url.searchParams.set('orderby', 'title');
                    url.searchParams.set('order', 'desc');
                    break;
                default:
                    url.searchParams.delete('orderby');
                    url.searchParams.delete('order');
            }
            
            window.location.href = url.toString();
        });
    }
    
});
</script>
