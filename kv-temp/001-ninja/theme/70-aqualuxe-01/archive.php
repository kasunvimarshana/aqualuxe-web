<?php get_header(); ?>

<main class="archive-page">
    
    <!-- Archive Header -->
    <header class="archive-header py-16 lg:py-24 bg-gradient-to-br from-primary-600 via-secondary-500 to-aqua-400 text-white">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto text-center">
                
                <h1 class="archive-title text-4xl lg:text-6xl font-bold font-secondary mb-6" data-aos="fade-up">
                    <?php
                    if (is_category()) {
                        single_cat_title();
                    } elseif (is_tag()) {
                        single_tag_title();
                    } elseif (is_author()) {
                        printf(__('Posts by %s', 'aqualuxe'), get_the_author());
                    } elseif (is_date()) {
                        if (is_year()) {
                            printf(__('Posts from %s', 'aqualuxe'), get_the_date('Y'));
                        } elseif (is_month()) {
                            printf(__('Posts from %s', 'aqualuxe'), get_the_date('F Y'));
                        } else {
                            printf(__('Posts from %s', 'aqualuxe'), get_the_date());
                        }
                    } else {
                        post_type_archive_title();
                    }
                    ?>
                </h1>
                
                <?php if (is_category() || is_tag()) : ?>
                    <?php
                    $term_description = term_description();
                    if ($term_description) :
                        ?>
                        <div class="archive-description text-xl lg:text-2xl text-gray-100 font-light leading-relaxed max-w-3xl mx-auto" data-aos="fade-up" data-aos-delay="200">
                            <?php echo wp_kses_post($term_description); ?>
                        </div>
                        <?php
                    endif;
                    ?>
                <?php endif; ?>
                
                <!-- Breadcrumbs -->
                <nav class="breadcrumbs mt-8" data-aos="fade-up" data-aos-delay="400">
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
                            <?php if (is_category()) : ?>
                                <a href="<?php echo esc_url(get_permalink(get_option('page_for_posts'))); ?>" 
                                   class="hover:text-white transition-colors">
                                    <?php esc_html_e('Blog', 'aqualuxe'); ?>
                                </a>
                                <svg class="w-4 h-4 mx-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-accent-400"><?php single_cat_title(); ?></span>
                            <?php elseif (is_tag()) : ?>
                                <a href="<?php echo esc_url(get_permalink(get_option('page_for_posts'))); ?>" 
                                   class="hover:text-white transition-colors">
                                    <?php esc_html_e('Blog', 'aqualuxe'); ?>
                                </a>
                                <svg class="w-4 h-4 mx-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-accent-400"><?php single_tag_title(); ?></span>
                            <?php else : ?>
                                <span class="text-accent-400">
                                    <?php
                                    if (is_author()) {
                                        printf(__('Posts by %s', 'aqualuxe'), get_the_author());
                                    } elseif (is_date()) {
                                        echo get_the_date();
                                    } else {
                                        post_type_archive_title();
                                    }
                                    ?>
                                </span>
                            <?php endif; ?>
                        </li>
                    </ol>
                </nav>
                
            </div>
        </div>
    </header>

    <!-- Archive Content -->
    <section class="archive-content py-16 lg:py-24">
        <div class="container mx-auto px-4">
            <div class="max-w-6xl mx-auto">
                
                <!-- Filter/Sort Options -->
                <div class="archive-filters mb-12" data-aos="fade-up">
                    <div class="filters-wrapper bg-white p-6 rounded-2xl shadow-soft">
                        <div class="filters-content flex flex-col lg:flex-row items-center justify-between gap-4">
                            
                            <!-- Results Count -->
                            <div class="results-count text-gray-600">
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
                            
                            <!-- View Toggle -->
                            <div class="view-toggle flex items-center space-x-2">
                                <span class="text-sm text-gray-600"><?php esc_html_e('View:', 'aqualuxe'); ?></span>
                                <button class="view-toggle-btn active" data-view="grid" title="<?php esc_attr_e('Grid View', 'aqualuxe'); ?>">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                                    </svg>
                                </button>
                                <button class="view-toggle-btn" data-view="list" title="<?php esc_attr_e('List View', 'aqualuxe'); ?>">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                </button>
                            </div>
                            
                        </div>
                    </div>
                </div>

                <!-- Posts Grid/List -->
                <?php if (have_posts()) : ?>
                    <div class="posts-container" id="posts-container">
                        <div class="posts-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" id="posts-grid">
                            <?php
                            $delay = 0;
                            while (have_posts()) : the_post();
                                ?>
                                <article class="post-card" data-aos="fade-up" data-aos-delay="<?php echo $delay; ?>">
                                    <div class="card bg-white rounded-2xl overflow-hidden shadow-soft hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 group">
                                        
                                        <!-- Post Image -->
                                        <div class="post-image relative h-48 overflow-hidden">
                                            <a href="<?php the_permalink(); ?>">
                                                <?php if (has_post_thumbnail()) : ?>
                                                    <?php the_post_thumbnail('medium', array(
                                                        'class' => 'w-full h-full object-cover group-hover:scale-105 transition-transform duration-300'
                                                    )); ?>
                                                <?php else : ?>
                                                    <div class="image-placeholder w-full h-full bg-gradient-to-br from-primary-500 to-secondary-400 flex items-center justify-center">
                                                        <svg class="w-16 h-16 text-white opacity-50" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path>
                                                        </svg>
                                                    </div>
                                                <?php endif; ?>
                                                <div class="image-overlay absolute inset-0 bg-gradient-to-t from-black/30 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                            </a>
                                        </div>
                                        
                                        <!-- Post Content -->
                                        <div class="post-content p-6">
                                            
                                            <!-- Categories -->
                                            <div class="post-categories mb-3">
                                                <?php
                                                $categories = get_the_category();
                                                if ($categories) :
                                                    $category = $categories[0];
                                                    ?>
                                                    <a href="<?php echo esc_url(get_category_link($category->term_id)); ?>" 
                                                       class="category-tag text-xs font-medium text-primary-600 bg-primary-100 px-2 py-1 rounded-full hover:bg-primary-200 transition-colors">
                                                        <?php echo esc_html($category->name); ?>
                                                    </a>
                                                    <?php
                                                endif;
                                                ?>
                                            </div>
                                            
                                            <!-- Title -->
                                            <h2 class="post-title mb-3">
                                                <a href="<?php the_permalink(); ?>" 
                                                   class="text-lg font-semibold text-gray-900 hover:text-primary-600 transition-colors line-clamp-2">
                                                    <?php the_title(); ?>
                                                </a>
                                            </h2>
                                            
                                            <!-- Excerpt -->
                                            <p class="post-excerpt text-gray-600 text-sm line-clamp-3 mb-4">
                                                <?php echo wp_trim_words(get_the_excerpt(), 20); ?>
                                            </p>
                                            
                                            <!-- Meta -->
                                            <div class="post-meta flex items-center justify-between text-xs text-gray-500">
                                                <div class="meta-left flex items-center space-x-3">
                                                    <time datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                                                        <?php echo esc_html(get_the_date()); ?>
                                                    </time>
                                                    <span><?php echo aqualuxe_reading_time(); ?> min read</span>
                                                </div>
                                                
                                                <?php if (comments_open() || get_comments_number()) : ?>
                                                    <div class="meta-right">
                                                        <a href="<?php comments_link(); ?>" class="hover:text-primary-600 transition-colors">
                                                            <?php comments_number('0', '1', '%'); ?> 
                                                            <svg class="w-4 h-4 inline ml-1" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z" clip-rule="evenodd"></path>
                                                            </svg>
                                                        </a>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            
                                        </div>
                                        
                                    </div>
                                </article>
                                <?php
                                $delay += 100;
                                if ($delay > 500) $delay = 0; // Reset delay for performance
                            endwhile;
                            ?>
                        </div>
                        
                        <!-- Load More Button -->
                        <?php if (get_next_posts_link()) : ?>
                            <div class="load-more-wrapper text-center mt-12" data-aos="fade-up">
                                <button id="load-more-posts" 
                                        data-url="<?php echo esc_url(get_next_posts_page_link()); ?>"
                                        class="btn btn-outline border-2 border-primary-600 text-primary-600 hover:bg-primary-600 hover:text-white px-8 py-3 rounded-full transition-all">
                                    <?php esc_html_e('Load More Posts', 'aqualuxe'); ?>
                                </button>
                            </div>
                        <?php endif; ?>
                        
                    </div>
                    
                    <!-- Pagination -->
                    <div class="pagination-wrapper mt-16" data-aos="fade-up">
                        <?php
                        the_posts_pagination(array(
                            'mid_size' => 2,
                            'prev_text' => __('← Previous', 'aqualuxe'),
                            'next_text' => __('Next →', 'aqualuxe'),
                            'before_page_number' => '<span class="screen-reader-text">' . __('Page', 'aqualuxe') . ' </span>',
                        ));
                        ?>
                    </div>
                    
                <?php else : ?>
                    
                    <!-- No Posts Found -->
                    <div class="no-posts-found text-center py-16" data-aos="fade-up">
                        <div class="no-posts-content max-w-2xl mx-auto">
                            <svg class="w-24 h-24 text-gray-300 mx-auto mb-6" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            <h2 class="text-2xl lg:text-3xl font-bold text-gray-900 mb-4">
                                <?php esc_html_e('No Posts Found', 'aqualuxe'); ?>
                            </h2>
                            <p class="text-gray-600 mb-8">
                                <?php esc_html_e('Sorry, no posts were found matching your criteria. Try adjusting your search or browse other categories.', 'aqualuxe'); ?>
                            </p>
                            <div class="no-posts-actions space-x-4">
                                <a href="<?php echo esc_url(home_url('/')); ?>" 
                                   class="btn btn-primary bg-primary-600 hover:bg-primary-700 text-white px-6 py-3 rounded-full transition-colors">
                                    <?php esc_html_e('Go Home', 'aqualuxe'); ?>
                                </a>
                                <a href="<?php echo esc_url(get_permalink(get_option('page_for_posts'))); ?>" 
                                   class="btn btn-outline border-2 border-primary-600 text-primary-600 hover:bg-primary-600 hover:text-white px-6 py-3 rounded-full transition-all">
                                    <?php esc_html_e('View All Posts', 'aqualuxe'); ?>
                                </a>
                            </div>
                        </div>
                    </div>
                    
                <?php endif; ?>
                
            </div>
        </div>
    </section>

</main>

<?php get_footer(); ?>
