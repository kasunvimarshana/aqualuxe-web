<?php
/**
 * Search Results Template
 *
 * Displays search results with filtering options, advanced search features,
 * and helpful suggestions when no results are found.
 *
 * @package AquaLuxe
 * @version 1.0.0
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

get_header();

$search_query = get_search_query();
$search_results = $wp_query->found_posts;
?>

<main id="main" class="site-main search-results-main" role="main" aria-label="<?php esc_attr_e('Search results', 'aqualuxe'); ?>">
    
    <div class="container mx-auto px-4 py-8">
        
        <!-- Search Header -->
        <header class="search-header mb-8">
            
            <!-- Breadcrumbs -->
            <nav class="breadcrumbs mb-6" aria-label="<?php esc_attr_e('Breadcrumb navigation', 'aqualuxe'); ?>">
                <ol class="flex items-center space-x-2 text-sm text-gray-600 dark:text-gray-400" itemscope itemtype="https://schema.org/BreadcrumbList">
                    <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                        <a href="<?php echo esc_url(home_url('/')); ?>" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-200" itemprop="item">
                            <span itemprop="name"><?php esc_html_e('Home', 'aqualuxe'); ?></span>
                        </a>
                        <meta itemprop="position" content="1">
                    </li>
                    <li class="before:content-['›'] before:mx-2 before:text-gray-400" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                        <span class="text-gray-900 dark:text-white" itemprop="name">
                            <?php esc_html_e('Search Results', 'aqualuxe'); ?>
                        </span>
                        <meta itemprop="position" content="2">
                    </li>
                </ol>
            </nav>
            
            <!-- Search Title and Stats -->
            <div class="search-title-section mb-6">
                <h1 class="search-title text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-2">
                    <?php
                    if ($search_query) {
                        printf(
                            esc_html__('Search Results for "%s"', 'aqualuxe'),
                            '<span class="text-blue-600 dark:text-blue-400">' . esc_html($search_query) . '</span>'
                        );
                    } else {
                        esc_html_e('Search Results', 'aqualuxe');
                    }
                    ?>
                </h1>
                
                <?php if ($search_query) : ?>
                    <div class="search-stats text-gray-600 dark:text-gray-400">
                        <?php
                        if ($search_results > 0) {
                            printf(
                                esc_html(_n(
                                    'Found %d result',
                                    'Found %d results',
                                    $search_results,
                                    'aqualuxe'
                                )),
                                number_format_i18n($search_results)
                            );
                        } else {
                            esc_html_e('No results found', 'aqualuxe');
                        }
                        ?>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Enhanced Search Form -->
            <div class="search-form-container bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border border-gray-200 dark:border-gray-700">
                <form role="search" method="get" class="enhanced-search-form" action="<?php echo esc_url(home_url('/')); ?>">
                    <div class="grid gap-4 md:grid-cols-4">
                        
                        <!-- Search Input -->
                        <div class="md:col-span-2">
                            <label for="search-input" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <?php esc_html_e('Search Keywords', 'aqualuxe'); ?>
                            </label>
                            <div class="relative">
                                <input 
                                    type="search" 
                                    id="search-input"
                                    name="s"
                                    class="block w-full pl-4 pr-12 py-3 border border-gray-300 rounded-md leading-5 bg-white dark:bg-gray-700 dark:border-gray-600 placeholder-gray-500 dark:placeholder-gray-400 text-gray-900 dark:text-white focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500" 
                                    placeholder="<?php esc_attr_e('Enter your search terms...', 'aqualuxe'); ?>" 
                                    value="<?php echo esc_attr($search_query); ?>"
                                />
                                <button 
                                    type="submit"
                                    class="absolute inset-y-0 right-0 flex items-center px-4 text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-200"
                                    aria-label="<?php esc_attr_e('Submit search', 'aqualuxe'); ?>"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Post Type Filter -->
                        <div>
                            <label for="post-type-filter" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <?php esc_html_e('Content Type', 'aqualuxe'); ?>
                            </label>
                            <select id="post-type-filter" name="post_type" class="block w-full px-3 py-3 border border-gray-300 rounded-md bg-white dark:bg-gray-700 dark:border-gray-600 text-gray-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                                <option value=""><?php esc_html_e('All Types', 'aqualuxe'); ?></option>
                                <option value="post" <?php selected(get_query_var('post_type'), 'post'); ?>><?php esc_html_e('Blog Posts', 'aqualuxe'); ?></option>
                                <option value="page" <?php selected(get_query_var('post_type'), 'page'); ?>><?php esc_html_e('Pages', 'aqualuxe'); ?></option>
                                <?php if (post_type_exists('product')) : ?>
                                    <option value="product" <?php selected(get_query_var('post_type'), 'product'); ?>><?php esc_html_e('Products', 'aqualuxe'); ?></option>
                                <?php endif; ?>
                            </select>
                        </div>
                        
                        <!-- Sort Options -->
                        <div>
                            <label for="sort-filter" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <?php esc_html_e('Sort By', 'aqualuxe'); ?>
                            </label>
                            <select id="sort-filter" name="orderby" class="block w-full px-3 py-3 border border-gray-300 rounded-md bg-white dark:bg-gray-700 dark:border-gray-600 text-gray-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                                <option value="relevance" <?php selected(get_query_var('orderby'), 'relevance'); ?>><?php esc_html_e('Relevance', 'aqualuxe'); ?></option>
                                <option value="date" <?php selected(get_query_var('orderby'), 'date'); ?>><?php esc_html_e('Date (Newest)', 'aqualuxe'); ?></option>
                                <option value="title" <?php selected(get_query_var('orderby'), 'title'); ?>><?php esc_html_e('Title (A-Z)', 'aqualuxe'); ?></option>
                                <option value="modified" <?php selected(get_query_var('orderby'), 'modified'); ?>><?php esc_html_e('Last Modified', 'aqualuxe'); ?></option>
                            </select>
                        </div>
                        
                    </div>
                    
                    <!-- Advanced Search Toggle -->
                    <div class="mt-4">
                        <button type="button" class="advanced-search-toggle flex items-center text-sm text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 transition-colors duration-200" onclick="toggleAdvancedSearch()">
                            <svg class="w-4 h-4 mr-2 transform transition-transform duration-200" id="advanced-toggle-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                            <span id="advanced-toggle-text"><?php esc_html_e('Show Advanced Options', 'aqualuxe'); ?></span>
                        </button>
                    </div>
                    
                    <!-- Advanced Search Options -->
                    <div id="advanced-search-options" class="hidden mt-4 pt-4 border-t border-gray-200 dark:border-gray-600">
                        <div class="grid gap-4 md:grid-cols-3">
                            
                            <!-- Category Filter -->
                            <?php
                            $categories = get_categories(['hide_empty' => true]);
                            if ($categories) :
                            ?>
                                <div>
                                    <label for="category-filter" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        <?php esc_html_e('Category', 'aqualuxe'); ?>
                                    </label>
                                    <select id="category-filter" name="cat" class="block w-full px-3 py-2 border border-gray-300 rounded-md bg-white dark:bg-gray-700 dark:border-gray-600 text-gray-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                                        <option value=""><?php esc_html_e('All Categories', 'aqualuxe'); ?></option>
                                        <?php foreach ($categories as $category) : ?>
                                            <option value="<?php echo esc_attr($category->term_id); ?>" <?php selected(get_query_var('cat'), $category->term_id); ?>>
                                                <?php echo esc_html($category->name); ?> (<?php echo esc_html($category->count); ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            <?php endif; ?>
                            
                            <!-- Date Range -->
                            <div>
                                <label for="date-filter" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    <?php esc_html_e('Date Range', 'aqualuxe'); ?>
                                </label>
                                <select id="date-filter" name="date_range" class="block w-full px-3 py-2 border border-gray-300 rounded-md bg-white dark:bg-gray-700 dark:border-gray-600 text-gray-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                                    <option value=""><?php esc_html_e('Any Time', 'aqualuxe'); ?></option>
                                    <option value="week"><?php esc_html_e('Past Week', 'aqualuxe'); ?></option>
                                    <option value="month"><?php esc_html_e('Past Month', 'aqualuxe'); ?></option>
                                    <option value="year"><?php esc_html_e('Past Year', 'aqualuxe'); ?></option>
                                </select>
                            </div>
                            
                            <!-- Author Filter -->
                            <div>
                                <label for="author-filter" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    <?php esc_html_e('Author', 'aqualuxe'); ?>
                                </label>
                                <select id="author-filter" name="author" class="block w-full px-3 py-2 border border-gray-300 rounded-md bg-white dark:bg-gray-700 dark:border-gray-600 text-gray-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                                    <option value=""><?php esc_html_e('Any Author', 'aqualuxe'); ?></option>
                                    <?php
                                    $authors = get_users(['who' => 'authors']);
                                    foreach ($authors as $author) :
                                    ?>
                                        <option value="<?php echo esc_attr($author->ID); ?>" <?php selected(get_query_var('author'), $author->ID); ?>>
                                            <?php echo esc_html($author->display_name); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                        </div>
                    </div>
                    
                </form>
            </div>
            
        </header>
        
        <div class="grid lg:grid-cols-4 gap-8">
            
            <!-- Search Results Content -->
            <div class="lg:col-span-3">
                
                <?php if (have_posts()) : ?>
                    
                    <!-- Results Count and Sorting -->
                    <div class="results-meta flex items-center justify-between mb-6 pb-4 border-b border-gray-200 dark:border-gray-700">
                        <div class="results-info text-sm text-gray-600 dark:text-gray-400">
                            <?php
                            printf(
                                esc_html__('Showing %1$d-%2$d of %3$d results', 'aqualuxe'),
                                ($wp_query->get('paged') > 1 ? ($wp_query->get('paged') - 1) * $wp_query->get('posts_per_page') + 1 : 1),
                                min($wp_query->get('paged') * $wp_query->get('posts_per_page'), $search_results),
                                $search_results
                            );
                            ?>
                        </div>
                        
                        <div class="results-view-toggle">
                            <button class="view-toggle grid-view active" onclick="setResultsView('grid')" aria-label="<?php esc_attr_e('Grid view', 'aqualuxe'); ?>">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                                </svg>
                            </button>
                            <button class="view-toggle list-view ml-2" onclick="setResultsView('list')" aria-label="<?php esc_attr_e('List view', 'aqualuxe'); ?>">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Search Results -->
                    <div id="search-results-container" class="search-results grid gap-6 md:grid-cols-2 mb-8">
                        
                        <?php while (have_posts()) : the_post(); ?>
                            
                            <article id="post-<?php the_ID(); ?>" <?php post_class('search-result bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300 border border-gray-200 dark:border-gray-700'); ?> itemscope itemtype="https://schema.org/Article">
                                
                                <!-- Post Thumbnail -->
                                <?php if (has_post_thumbnail()) : ?>
                                    <div class="result-thumbnail">
                                        <a href="<?php the_permalink(); ?>" class="block">
                                            <?php the_post_thumbnail('aqualuxe-gallery', [
                                                'class' => 'w-full h-48 object-cover',
                                                'alt' => get_the_title(),
                                                'itemprop' => 'image'
                                            ]); ?>
                                        </a>
                                    </div>
                                <?php endif; ?>
                                
                                <!-- Post Content -->
                                <div class="result-content p-6">
                                    
                                    <!-- Post Meta -->
                                    <div class="result-meta flex items-center gap-4 text-xs text-gray-500 dark:text-gray-400 mb-3">
                                        
                                        <!-- Post Type -->
                                        <span class="post-type inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                            <?php echo esc_html(get_post_type_object(get_post_type())->labels->singular_name); ?>
                                        </span>
                                        
                                        <!-- Date -->
                                        <time datetime="<?php echo esc_attr(get_the_date('c')); ?>" itemprop="datePublished">
                                            <?php echo esc_html(get_the_date()); ?>
                                        </time>
                                        
                                        <!-- Author -->
                                        <?php if (get_post_type() === 'post') : ?>
                                            <span class="author" itemprop="author" itemscope itemtype="https://schema.org/Person">
                                                <?php esc_html_e('by', 'aqualuxe'); ?> 
                                                <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-200" itemprop="name">
                                                    <?php the_author(); ?>
                                                </a>
                                            </span>
                                        <?php endif; ?>
                                        
                                        <!-- Categories -->
                                        <?php if (get_post_type() === 'post' && has_category()) : ?>
                                            <span class="categories">
                                                <?php the_category(', '); ?>
                                            </span>
                                        <?php endif; ?>
                                        
                                    </div>
                                    
                                    <!-- Post Title -->
                                    <h2 class="result-title text-xl font-semibold mb-3" itemprop="headline">
                                        <a href="<?php the_permalink(); ?>" class="text-gray-900 dark:text-white hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-200" itemprop="url">
                                            <?php 
                                            // Highlight search terms in title
                                            $title = get_the_title();
                                            if ($search_query) {
                                                $title = preg_replace('/(' . preg_quote($search_query, '/') . ')/i', '<mark class="bg-yellow-200 dark:bg-yellow-600 px-1 rounded">$1</mark>', $title);
                                            }
                                            echo wp_kses_post($title);
                                            ?>
                                        </a>
                                    </h2>
                                    
                                    <!-- Post Excerpt -->
                                    <div class="result-excerpt text-gray-700 dark:text-gray-300 mb-4" itemprop="description">
                                        <?php
                                        $excerpt = get_the_excerpt();
                                        if ($search_query) {
                                            $excerpt = preg_replace('/(' . preg_quote($search_query, '/') . ')/i', '<mark class="bg-yellow-200 dark:bg-yellow-600 px-1 rounded">$1</mark>', $excerpt);
                                        }
                                        echo wp_kses_post($excerpt);
                                        ?>
                                    </div>
                                    
                                    <!-- Read More Link -->
                                    <a href="<?php the_permalink(); ?>" class="inline-flex items-center text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 font-medium transition-colors duration-200">
                                        <?php esc_html_e('Read More', 'aqualuxe'); ?>
                                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </a>
                                    
                                </div>
                                
                            </article>
                            
                        <?php endwhile; ?>
                        
                    </div>
                    
                    <!-- Pagination -->
                    <?php
                    the_posts_pagination([
                        'mid_size' => 2,
                        'prev_text' => sprintf(
                            '<span class="sr-only">%s</span><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>',
                            esc_html__('Previous', 'aqualuxe')
                        ),
                        'next_text' => sprintf(
                            '<span class="sr-only">%s</span><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>',
                            esc_html__('Next', 'aqualuxe')
                        ),
                        'class' => 'flex justify-center items-center space-x-2',
                        'screen_reader_text' => esc_html__('Search results navigation', 'aqualuxe')
                    ]);
                    ?>
                    
                <?php else : ?>
                    
                    <!-- No Results Found -->
                    <div class="no-results bg-white dark:bg-gray-800 rounded-lg shadow-md p-8 text-center border border-gray-200 dark:border-gray-700">
                        
                        <!-- No Results Icon -->
                        <div class="no-results-icon mb-6">
                            <svg class="w-20 h-20 mx-auto text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">
                            <?php esc_html_e('No Results Found', 'aqualuxe'); ?>
                        </h2>
                        
                        <p class="text-gray-600 dark:text-gray-400 mb-6">
                            <?php
                            if ($search_query) {
                                printf(
                                    esc_html__('Sorry, no results were found for "%s". Try adjusting your search terms or filters.', 'aqualuxe'),
                                    '<strong>' . esc_html($search_query) . '</strong>'
                                );
                            } else {
                                esc_html_e('Please enter a search term to find relevant content.', 'aqualuxe');
                            }
                            ?>
                        </p>
                        
                        <!-- Search Suggestions -->
                        <div class="search-suggestions">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                                <?php esc_html_e('Search Suggestions:', 'aqualuxe'); ?>
                            </h3>
                            <ul class="text-left max-w-md mx-auto space-y-2 text-gray-700 dark:text-gray-300">
                                <li class="flex items-start">
                                    <svg class="w-4 h-4 text-blue-500 mr-2 mt-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    <?php esc_html_e('Check your spelling and try again', 'aqualuxe'); ?>
                                </li>
                                <li class="flex items-start">
                                    <svg class="w-4 h-4 text-blue-500 mr-2 mt-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    <?php esc_html_e('Try using more general keywords', 'aqualuxe'); ?>
                                </li>
                                <li class="flex items-start">
                                    <svg class="w-4 h-4 text-blue-500 mr-2 mt-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    <?php esc_html_e('Remove filters to expand your search', 'aqualuxe'); ?>
                                </li>
                                <li class="flex items-start">
                                    <svg class="w-4 h-4 text-blue-500 mr-2 mt-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    <?php esc_html_e('Browse our categories below', 'aqualuxe'); ?>
                                </li>
                            </ul>
                        </div>
                        
                    </div>
                    
                <?php endif; ?>
                
            </div>
            
            <!-- Search Sidebar -->
            <aside class="lg:col-span-1 search-sidebar" role="complementary" aria-label="<?php esc_attr_e('Search sidebar', 'aqualuxe'); ?>">
                
                <!-- Popular Searches -->
                <div class="widget bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-6 border border-gray-200 dark:border-gray-700">
                    <h3 class="widget-title text-lg font-semibold mb-4 text-gray-900 dark:text-white">
                        <?php esc_html_e('Popular Searches', 'aqualuxe'); ?>
                    </h3>
                    <div class="popular-searches">
                        <?php
                        // You can customize these popular search terms
                        $popular_searches = [
                            'Web Design', 'WordPress', 'SEO', 'E-commerce', 
                            'Digital Marketing', 'Responsive Design', 'Branding'
                        ];
                        
                        foreach ($popular_searches as $term) :
                        ?>
                            <a href="<?php echo esc_url(home_url('/?s=' . urlencode($term))); ?>" class="inline-block px-3 py-1 mb-2 mr-2 text-sm bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-full hover:bg-blue-100 hover:text-blue-800 dark:hover:bg-blue-900 dark:hover:text-blue-200 transition-colors duration-200">
                                <?php echo esc_html($term); ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <?php
                // Display regular sidebar
                if (is_active_sidebar('search-sidebar')) {
                    dynamic_sidebar('search-sidebar');
                } elseif (is_active_sidebar('blog-sidebar')) {
                    dynamic_sidebar('blog-sidebar');
                }
                ?>
                
            </aside>
            
        </div>
        
    </div>
    
</main>

<!-- Search Results JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Advanced search toggle
    window.toggleAdvancedSearch = function() {
        const options = document.getElementById('advanced-search-options');
        const icon = document.getElementById('advanced-toggle-icon');
        const text = document.getElementById('advanced-toggle-text');
        
        if (options.classList.contains('hidden')) {
            options.classList.remove('hidden');
            icon.style.transform = 'rotate(180deg)';
            text.textContent = '<?php echo esc_js(__('Hide Advanced Options', 'aqualuxe')); ?>';
        } else {
            options.classList.add('hidden');
            icon.style.transform = 'rotate(0deg)';
            text.textContent = '<?php echo esc_js(__('Show Advanced Options', 'aqualuxe')); ?>';
        }
    };
    
    // Results view toggle
    window.setResultsView = function(view) {
        const container = document.getElementById('search-results-container');
        const gridToggle = document.querySelector('.view-toggle.grid-view');
        const listToggle = document.querySelector('.view-toggle.list-view');
        
        if (view === 'list') {
            container.classList.remove('grid', 'md:grid-cols-2');
            container.classList.add('space-y-4');
            gridToggle.classList.remove('active');
            listToggle.classList.add('active');
        } else {
            container.classList.add('grid', 'md:grid-cols-2');
            container.classList.remove('space-y-4');
            listToggle.classList.remove('active');
            gridToggle.classList.add('active');
        }
    };
    
    // Auto-submit form when filters change
    const filterInputs = document.querySelectorAll('#post-type-filter, #sort-filter, #category-filter, #date-filter, #author-filter');
    filterInputs.forEach(input => {
        input.addEventListener('change', function() {
            this.form.submit();
        });
    });
});
</script>

<!-- Search Results Styles -->
<style>
.view-toggle {
    @apply p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors duration-200;
}

.view-toggle.active {
    @apply text-blue-600 dark:text-blue-400;
}

.search-result.list-view {
    @apply flex;
}

.search-result.list-view .result-thumbnail {
    @apply w-32 flex-shrink-0;
}

.search-result.list-view .result-thumbnail img {
    @apply h-full;
}

mark {
    @apply bg-yellow-200 dark:bg-yellow-600 px-1 rounded;
}
</style>

<?php
get_sidebar();
get_footer();
