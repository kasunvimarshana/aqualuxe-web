<?php get_header(); ?>

<main class="search-page">
    
    <!-- Search Header -->
    <header class="search-header py-16 lg:py-24 bg-gradient-to-br from-primary-600 via-secondary-500 to-aqua-400 text-white">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto text-center">
                
                <h1 class="search-title text-4xl lg:text-6xl font-bold font-secondary mb-6" data-aos="fade-up">
                    <?php if (get_search_query()) : ?>
                        <?php printf(__('Search Results for "%s"', 'aqualuxe'), get_search_query()); ?>
                    <?php else : ?>
                        <?php esc_html_e('Search Results', 'aqualuxe'); ?>
                    <?php endif; ?>
                </h1>
                
                <?php if (get_search_query()) : ?>
                    <p class="search-subtitle text-xl lg:text-2xl text-gray-100 font-light leading-relaxed max-w-3xl mx-auto mb-8" data-aos="fade-up" data-aos-delay="200">
                        <?php
                        global $wp_query;
                        $total_results = $wp_query->found_posts;
                        if ($total_results > 0) {
                            printf(
                                _n(
                                    'Found %d result for your search',
                                    'Found %d results for your search',
                                    $total_results,
                                    'aqualuxe'
                                ),
                                $total_results
                            );
                        } else {
                            esc_html_e('No results found for your search', 'aqualuxe');
                        }
                        ?>
                    </p>
                <?php endif; ?>
                
                <!-- Search Form -->
                <div class="search-form-wrapper max-w-2xl mx-auto" data-aos="fade-up" data-aos-delay="400">
                    <form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
                        <div class="search-input-wrapper relative">
                            <input type="search" 
                                   class="search-field w-full px-6 py-4 pr-16 text-lg text-gray-900 bg-white rounded-full border-0 focus:ring-4 focus:ring-white/30 focus:outline-none" 
                                   placeholder="<?php esc_attr_e('Search for articles, products, or services...', 'aqualuxe'); ?>" 
                                   value="<?php echo get_search_query(); ?>" 
                                   name="s">
                            <button type="submit" 
                                    class="search-submit absolute right-2 top-1/2 transform -translate-y-1/2 w-12 h-12 bg-primary-600 hover:bg-primary-700 text-white rounded-full flex items-center justify-center transition-colors focus:outline-none focus:ring-4 focus:ring-primary-300">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                <span class="sr-only"><?php esc_html_e('Search', 'aqualuxe'); ?></span>
                            </button>
                        </div>
                    </form>
                </div>
                
                <!-- Breadcrumbs -->
                <nav class="breadcrumbs mt-8" data-aos="fade-up" data-aos-delay="600">
                    <ol class="flex items-center justify-center space-x-2 text-gray-200">
                        <li>
                            <a href="<?php echo esc_url(home_url('/')); ?>" 
                               class="hover:text-white transition-colors">
                                <?php esc_html_e('Home', 'aqualuxe'); ?>
                            </a>
                        </li>
                        <li class="flex items-center">
                            <svg class="w-4 h-4 mx-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-accent-400"><?php esc_html_e('Search', 'aqualuxe'); ?></span>
                        </li>
                    </ol>
                </nav>
                
            </div>
        </div>
    </header>

    <!-- Search Content -->
    <section class="search-content py-16 lg:py-24">
        <div class="container mx-auto px-4">
            <div class="max-w-6xl mx-auto">
                
                <?php if (have_posts() && get_search_query()) : ?>
                    
                    <!-- Search Filters -->
                    <div class="search-filters mb-12" data-aos="fade-up">
                        <div class="filters-wrapper bg-white p-6 rounded-2xl shadow-soft">
                            <div class="filters-content flex flex-col lg:flex-row items-center justify-between gap-4">
                                
                                <!-- Results Info -->
                                <div class="results-info text-gray-600">
                                    <?php
                                    global $wp_query;
                                    $total_results = $wp_query->found_posts;
                                    $current_page = max(1, get_query_var('paged'));
                                    $posts_per_page = get_query_var('posts_per_page');
                                    $start_result = (($current_page - 1) * $posts_per_page) + 1;
                                    $end_result = min($current_page * $posts_per_page, $total_results);
                                    
                                    printf(
                                        __('Showing %d-%d of %d results', 'aqualuxe'),
                                        $start_result,
                                        $end_result,
                                        $total_results
                                    );
                                    ?>
                                </div>
                                
                                <!-- Search Suggestions -->
                                <?php
                                $search_suggestions = aqualuxe_get_search_suggestions(get_search_query());
                                if ($search_suggestions) :
                                    ?>
                                    <div class="search-suggestions flex items-center space-x-2">
                                        <span class="text-sm text-gray-600"><?php esc_html_e('Try:', 'aqualuxe'); ?></span>
                                        <?php foreach ($search_suggestions as $suggestion) : ?>
                                            <a href="<?php echo esc_url(add_query_arg('s', $suggestion, home_url('/'))); ?>" 
                                               class="suggestion-tag bg-primary-100 text-primary-600 px-3 py-1 rounded-full text-sm hover:bg-primary-200 transition-colors">
                                                <?php echo esc_html($suggestion); ?>
                                            </a>
                                        <?php endforeach; ?>
                                    </div>
                                    <?php
                                endif;
                                ?>
                                
                            </div>
                        </div>
                    </div>

                    <!-- Search Results -->
                    <div class="search-results">
                        <div class="results-grid space-y-8">
                            <?php
                            $delay = 0;
                            while (have_posts()) : the_post();
                                ?>
                                <article class="search-result-item" data-aos="fade-up" data-aos-delay="<?php echo $delay; ?>">
                                    <div class="result-card bg-white p-6 lg:p-8 rounded-2xl shadow-soft hover:shadow-lg transition-all duration-300 group">
                                        <div class="result-content flex flex-col lg:flex-row gap-6">
                                            
                                            <!-- Result Image -->
                                            <?php if (has_post_thumbnail()) : ?>
                                                <div class="result-image flex-shrink-0">
                                                    <a href="<?php the_permalink(); ?>" class="block">
                                                        <div class="image-container w-full lg:w-48 h-48 lg:h-32 rounded-xl overflow-hidden">
                                                            <?php the_post_thumbnail('medium', array(
                                                                'class' => 'w-full h-full object-cover group-hover:scale-105 transition-transform duration-300'
                                                            )); ?>
                                                        </div>
                                                    </a>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <!-- Result Info -->
                                            <div class="result-info flex-1">
                                                
                                                <!-- Post Type & Category -->
                                                <div class="result-meta mb-3 flex items-center space-x-3">
                                                    <span class="post-type-badge bg-secondary-100 text-secondary-600 px-2 py-1 rounded text-xs font-medium">
                                                        <?php echo esc_html(get_post_type_object(get_post_type())->labels->singular_name); ?>
                                                    </span>
                                                    
                                                    <?php if (get_post_type() === 'post') : ?>
                                                        <?php
                                                        $categories = get_the_category();
                                                        if ($categories) :
                                                            $category = $categories[0];
                                                            ?>
                                                            <a href="<?php echo esc_url(get_category_link($category->term_id)); ?>" 
                                                               class="category-badge bg-primary-100 text-primary-600 px-2 py-1 rounded text-xs font-medium hover:bg-primary-200 transition-colors">
                                                                <?php echo esc_html($category->name); ?>
                                                            </a>
                                                            <?php
                                                        endif;
                                                        ?>
                                                    <?php endif; ?>
                                                </div>
                                                
                                                <!-- Title -->
                                                <h2 class="result-title mb-3">
                                                    <a href="<?php the_permalink(); ?>" 
                                                       class="text-xl lg:text-2xl font-semibold text-gray-900 hover:text-primary-600 transition-colors line-clamp-2">
                                                        <?php 
                                                        // Highlight search terms in title
                                                        $title = get_the_title();
                                                        $search_query = get_search_query();
                                                        if ($search_query) {
                                                            $title = aqualuxe_highlight_search_terms($title, $search_query);
                                                        }
                                                        echo wp_kses_post($title);
                                                        ?>
                                                    </a>
                                                </h2>
                                                
                                                <!-- Excerpt -->
                                                <div class="result-excerpt text-gray-600 mb-4 line-clamp-3">
                                                    <?php
                                                    $excerpt = get_the_excerpt();
                                                    if ($search_query) {
                                                        $excerpt = aqualuxe_highlight_search_terms($excerpt, $search_query);
                                                    }
                                                    echo wp_kses_post($excerpt);
                                                    ?>
                                                </div>
                                                
                                                <!-- Meta Info -->
                                                <div class="result-meta-info flex flex-wrap items-center gap-4 text-sm text-gray-500">
                                                    <time datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                                                        <?php echo esc_html(get_the_date()); ?>
                                                    </time>
                                                    
                                                    <?php if (get_post_type() === 'post') : ?>
                                                        <span class="author">
                                                            <?php esc_html_e('By', 'aqualuxe'); ?>
                                                            <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>" 
                                                               class="text-primary-600 hover:text-primary-700 transition-colors">
                                                                <?php the_author(); ?>
                                                            </a>
                                                        </span>
                                                    <?php endif; ?>
                                                    
                                                    <?php if (comments_open() || get_comments_number()) : ?>
                                                        <a href="<?php comments_link(); ?>" 
                                                           class="comments-link text-gray-500 hover:text-primary-600 transition-colors">
                                                            <?php comments_number('0 Comments', '1 Comment', '% Comments'); ?>
                                                        </a>
                                                    <?php endif; ?>
                                                </div>
                                                
                                            </div>
                                            
                                        </div>
                                    </div>
                                </article>
                                <?php
                                $delay += 100;
                                if ($delay > 500) $delay = 0;
                            endwhile;
                            ?>
                        </div>
                        
                        <!-- Pagination -->
                        <div class="search-pagination mt-16" data-aos="fade-up">
                            <?php
                            the_posts_pagination(array(
                                'mid_size' => 2,
                                'prev_text' => __('← Previous', 'aqualuxe'),
                                'next_text' => __('Next →', 'aqualuxe'),
                                'before_page_number' => '<span class="screen-reader-text">' . __('Page', 'aqualuxe') . ' </span>',
                            ));
                            ?>
                        </div>
                        
                    </div>
                    
                <?php elseif (get_search_query()) : ?>
                    
                    <!-- No Results Found -->
                    <div class="no-results-found text-center py-16" data-aos="fade-up">
                        <div class="no-results-content max-w-2xl mx-auto">
                            <svg class="w-24 h-24 text-gray-300 mx-auto mb-6" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path>
                            </svg>
                            <h2 class="text-2xl lg:text-3xl font-bold text-gray-900 mb-4">
                                <?php esc_html_e('No Results Found', 'aqualuxe'); ?>
                            </h2>
                            <p class="text-gray-600 mb-8">
                                <?php printf(__('Sorry, we couldn\'t find any results for "%s". Try different keywords or browse our categories.', 'aqualuxe'), get_search_query()); ?>
                            </p>
                            
                            <!-- Search Tips -->
                            <div class="search-tips mb-8 p-6 bg-gray-50 rounded-xl text-left">
                                <h3 class="font-semibold text-gray-900 mb-4"><?php esc_html_e('Search Tips:', 'aqualuxe'); ?></h3>
                                <ul class="space-y-2 text-gray-600">
                                    <li>• <?php esc_html_e('Try different or more general keywords', 'aqualuxe'); ?></li>
                                    <li>• <?php esc_html_e('Check your spelling', 'aqualuxe'); ?></li>
                                    <li>• <?php esc_html_e('Use fewer keywords', 'aqualuxe'); ?></li>
                                    <li>• <?php esc_html_e('Try synonyms or related terms', 'aqualuxe'); ?></li>
                                </ul>
                            </div>
                            
                            <div class="no-results-actions space-x-4">
                                <a href="<?php echo esc_url(home_url('/')); ?>" 
                                   class="btn btn-primary bg-primary-600 hover:bg-primary-700 text-white px-6 py-3 rounded-full transition-colors">
                                    <?php esc_html_e('Go Home', 'aqualuxe'); ?>
                                </a>
                                <a href="<?php echo esc_url(get_permalink(get_option('page_for_posts'))); ?>" 
                                   class="btn btn-outline border-2 border-primary-600 text-primary-600 hover:bg-primary-600 hover:text-white px-6 py-3 rounded-full transition-all">
                                    <?php esc_html_e('Browse All Posts', 'aqualuxe'); ?>
                                </a>
                            </div>
                        </div>
                    </div>
                    
                <?php else : ?>
                    
                    <!-- Search Form Only -->
                    <div class="search-form-only text-center py-16" data-aos="fade-up">
                        <div class="search-content max-w-2xl mx-auto">
                            <h2 class="text-2xl lg:text-3xl font-bold text-gray-900 mb-4">
                                <?php esc_html_e('Search Our Site', 'aqualuxe'); ?>
                            </h2>
                            <p class="text-gray-600 mb-8">
                                <?php esc_html_e('Enter your search terms above to find articles, products, and services.', 'aqualuxe'); ?>
                            </p>
                        </div>
                    </div>
                    
                <?php endif; ?>
                
                <!-- Popular Searches -->
                <div class="popular-searches mt-16" data-aos="fade-up">
                    <div class="popular-searches-content bg-gray-50 p-8 rounded-2xl">
                        <h3 class="text-xl font-semibold text-gray-900 mb-6 text-center">
                            <?php esc_html_e('Popular Searches', 'aqualuxe'); ?>
                        </h3>
                        
                        <div class="popular-terms flex flex-wrap justify-center gap-3">
                            <?php
                            $popular_searches = array(
                                __('Rare Fish', 'aqualuxe'),
                                __('Aquarium Setup', 'aqualuxe'),
                                __('Fish Care', 'aqualuxe'),
                                __('Water Quality', 'aqualuxe'),
                                __('Aquatic Plants', 'aqualuxe'),
                                __('Tropical Fish', 'aqualuxe'),
                                __('Filtration', 'aqualuxe'),
                                __('Aquascaping', 'aqualuxe'),
                            );
                            
                            foreach ($popular_searches as $term) :
                                ?>
                                <a href="<?php echo esc_url(add_query_arg('s', $term, home_url('/'))); ?>" 
                                   class="popular-term bg-white text-gray-700 px-4 py-2 rounded-full text-sm hover:bg-primary-100 hover:text-primary-700 transition-colors shadow-sm">
                                    <?php echo esc_html($term); ?>
                                </a>
                                <?php
                            endforeach;
                            ?>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </section>

</main>

<?php get_footer(); ?>
