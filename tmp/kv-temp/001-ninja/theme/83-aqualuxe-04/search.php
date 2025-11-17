<?php
/**
 * The template for displaying search results pages
 * 
 * @package KV_Enterprise
 * @version 1.0.0
 * @since 1.0.0
 */

get_header(); ?>

<div class="container">
    <div class="row">
        <div class="col-lg-8">
            <main id="primary" class="site-main search-results" role="main">
                
                <header class="page-header search-header">
                    <?php if (have_posts()) : ?>
                        <h1 class="page-title">
                            <?php
                            printf(
                                esc_html__('Search Results for: %s', KV_THEME_TEXTDOMAIN),
                                '<span class="search-term">' . esc_html(get_search_query()) . '</span>'
                            );
                            ?>
                        </h1>
                        
                        <div class="search-meta">
                            <p class="search-found">
                                <?php
                                global $wp_query;
                                printf(
                                    _n(
                                        'Found %d result',
                                        'Found %d results',
                                        $wp_query->found_posts,
                                        KV_THEME_TEXTDOMAIN
                                    ),
                                    $wp_query->found_posts
                                );
                                ?>
                            </p>
                            
                            <!-- Search again form -->
                            <div class="search-again">
                                <p><?php esc_html_e('Try a different search:', KV_THEME_TEXTDOMAIN); ?></p>
                                <?php get_search_form(); ?>
                            </div>
                        </div>
                        
                        <!-- Search filters -->
                        <div class="search-filters">
                            <h3><?php esc_html_e('Filter Results', KV_THEME_TEXTDOMAIN); ?></h3>
                            <form class="search-filter-form" method="get" action="<?php echo esc_url(home_url('/')); ?>">
                                <input type="hidden" name="s" value="<?php echo esc_attr(get_search_query()); ?>">
                                
                                <div class="filter-group">
                                    <label for="search-post-type"><?php esc_html_e('Content Type:', KV_THEME_TEXTDOMAIN); ?></label>
                                    <select name="post_type" id="search-post-type" class="form-control">
                                        <option value=""><?php esc_html_e('All Types', KV_THEME_TEXTDOMAIN); ?></option>
                                        <option value="post" <?php selected(get_query_var('post_type'), 'post'); ?>><?php esc_html_e('Posts', KV_THEME_TEXTDOMAIN); ?></option>
                                        <option value="page" <?php selected(get_query_var('post_type'), 'page'); ?>><?php esc_html_e('Pages', KV_THEME_TEXTDOMAIN); ?></option>
                                        <?php
                                        // Add custom post types
                                        $custom_post_types = get_post_types(array('public' => true, '_builtin' => false), 'objects');
                                        foreach ($custom_post_types as $post_type) {
                                            printf(
                                                '<option value="%s" %s>%s</option>',
                                                esc_attr($post_type->name),
                                                selected(get_query_var('post_type'), $post_type->name, false),
                                                esc_html($post_type->labels->name)
                                            );
                                        }
                                        ?>
                                    </select>
                                </div>
                                
                                <div class="filter-group">
                                    <label for="search-category"><?php esc_html_e('Category:', KV_THEME_TEXTDOMAIN); ?></label>
                                    <?php
                                    wp_dropdown_categories(array(
                                        'show_option_all' => __('All Categories', KV_THEME_TEXTDOMAIN),
                                        'name' => 'cat',
                                        'id' => 'search-category',
                                        'class' => 'form-control',
                                        'selected' => get_query_var('cat'),
                                        'hierarchical' => true
                                    ));
                                    ?>
                                </div>
                                
                                <div class="filter-group">
                                    <label for="search-date"><?php esc_html_e('Date:', KV_THEME_TEXTDOMAIN); ?></label>
                                    <select name="date_range" id="search-date" class="form-control">
                                        <option value=""><?php esc_html_e('Any Time', KV_THEME_TEXTDOMAIN); ?></option>
                                        <option value="last_week" <?php selected($_GET['date_range'] ?? '', 'last_week'); ?>><?php esc_html_e('Last Week', KV_THEME_TEXTDOMAIN); ?></option>
                                        <option value="last_month" <?php selected($_GET['date_range'] ?? '', 'last_month'); ?>><?php esc_html_e('Last Month', KV_THEME_TEXTDOMAIN); ?></option>
                                        <option value="last_year" <?php selected($_GET['date_range'] ?? '', 'last_year'); ?>><?php esc_html_e('Last Year', KV_THEME_TEXTDOMAIN); ?></option>
                                    </select>
                                </div>
                                
                                <button type="submit" class="btn btn-primary"><?php esc_html_e('Filter', KV_THEME_TEXTDOMAIN); ?></button>
                                <a href="<?php echo esc_url(home_url('/?s=' . urlencode(get_search_query()))); ?>" class="btn btn-outline-secondary"><?php esc_html_e('Clear Filters', KV_THEME_TEXTDOMAIN); ?></a>
                            </form>
                        </div>
                        
                    <?php else : ?>
                        <h1 class="page-title">
                            <?php
                            printf(
                                esc_html__('No results found for: %s', KV_THEME_TEXTDOMAIN),
                                '<span class="search-term">' . esc_html(get_search_query()) . '</span>'
                            );
                            ?>
                        </h1>
                        
                        <div class="no-results-meta">
                            <p><?php esc_html_e('Sorry, but nothing matched your search terms. Please try again with some different keywords.', KV_THEME_TEXTDOMAIN); ?></p>
                            
                            <!-- Search again form -->
                            <div class="search-again">
                                <?php get_search_form(); ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </header><!-- .page-header -->
                
                <?php if (have_posts()) : ?>
                    
                    <!-- Search results sorting -->
                    <div class="search-sorting">
                        <form class="sorting-form" method="get" action="<?php echo esc_url(home_url('/')); ?>">
                            <input type="hidden" name="s" value="<?php echo esc_attr(get_search_query()); ?>">
                            <?php
                            // Preserve other query parameters
                            foreach ($_GET as $key => $value) {
                                if (!in_array($key, ['s', 'orderby', 'order'])) {
                                    printf('<input type="hidden" name="%s" value="%s">', esc_attr($key), esc_attr($value));
                                }
                            }
                            ?>
                            
                            <label for="search-orderby"><?php esc_html_e('Sort by:', KV_THEME_TEXTDOMAIN); ?></label>
                            <select name="orderby" id="search-orderby" class="form-control" onchange="this.form.submit()">
                                <option value="relevance" <?php selected($_GET['orderby'] ?? '', 'relevance'); ?>><?php esc_html_e('Relevance', KV_THEME_TEXTDOMAIN); ?></option>
                                <option value="date" <?php selected($_GET['orderby'] ?? '', 'date'); ?>><?php esc_html_e('Date (Newest)', KV_THEME_TEXTDOMAIN); ?></option>
                                <option value="date_asc" <?php selected($_GET['orderby'] ?? '', 'date_asc'); ?>><?php esc_html_e('Date (Oldest)', KV_THEME_TEXTDOMAIN); ?></option>
                                <option value="title" <?php selected($_GET['orderby'] ?? '', 'title'); ?>><?php esc_html_e('Title (A-Z)', KV_THEME_TEXTDOMAIN); ?></option>
                                <option value="title_desc" <?php selected($_GET['orderby'] ?? '', 'title_desc'); ?>><?php esc_html_e('Title (Z-A)', KV_THEME_TEXTDOMAIN); ?></option>
                            </select>
                        </form>
                    </div>
                    
                    <div class="search-results-list">
                        
                        <?php while (have_posts()) : the_post(); ?>
                            
                            <article id="post-<?php the_ID(); ?>" <?php post_class('search-result-item'); ?>>
                                
                                <div class="search-result-content">
                                    
                                    <!-- Post thumbnail -->
                                    <?php if (has_post_thumbnail()) : ?>
                                        <div class="search-result-thumbnail">
                                            <a href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
                                                <?php the_post_thumbnail('medium', array(
                                                    'alt' => esc_attr(get_the_title()),
                                                    'loading' => 'lazy'
                                                )); ?>
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div class="search-result-text">
                                        
                                        <!-- Post meta -->
                                        <div class="search-result-meta">
                                            <span class="post-type"><?php echo esc_html(get_post_type_object(get_post_type())->labels->singular_name); ?></span>
                                            <span class="meta-separator">•</span>
                                            <time class="entry-date" datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                                                <?php echo esc_html(get_the_date()); ?>
                                            </time>
                                            <?php if (get_post_type() === 'post') : ?>
                                                <span class="meta-separator">•</span>
                                                <span class="entry-author">
                                                    <?php printf(
                                                        esc_html__('by %s', KV_THEME_TEXTDOMAIN),
                                                        '<a href="' . esc_url(get_author_posts_url(get_the_author_meta('ID'))) . '">' . esc_html(get_the_author()) . '</a>'
                                                    ); ?>
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                        
                                        <!-- Post title -->
                                        <h2 class="search-result-title">
                                            <a href="<?php the_permalink(); ?>" rel="bookmark">
                                                <?php echo kv_highlight_search_terms(get_the_title(), get_search_query()); ?>
                                            </a>
                                        </h2>
                                        
                                        <!-- Post excerpt -->
                                        <div class="search-result-excerpt">
                                            <?php
                                            $excerpt = get_the_excerpt();
                                            echo kv_highlight_search_terms($excerpt, get_search_query());
                                            ?>
                                        </div>
                                        
                                        <!-- Post categories/tags -->
                                        <?php if (get_post_type() === 'post') : ?>
                                            <div class="search-result-terms">
                                                <?php
                                                $categories = get_the_category();
                                                if ($categories) :
                                                    echo '<span class="result-categories">';
                                                    echo '<i class="fas fa-folder" aria-hidden="true"></i> ';
                                                    $cat_links = array();
                                                    foreach ($categories as $category) {
                                                        $cat_links[] = '<a href="' . esc_url(get_category_link($category->term_id)) . '">' . esc_html($category->name) . '</a>';
                                                    }
                                                    echo implode(', ', $cat_links);
                                                    echo '</span>';
                                                endif;
                                                
                                                $tags = get_the_tags();
                                                if ($tags) :
                                                    echo '<span class="result-tags">';
                                                    echo '<i class="fas fa-tags" aria-hidden="true"></i> ';
                                                    $tag_links = array();
                                                    foreach ($tags as $tag) {
                                                        $tag_links[] = '<a href="' . esc_url(get_tag_link($tag->term_id)) . '">' . esc_html($tag->name) . '</a>';
                                                    }
                                                    echo implode(', ', $tag_links);
                                                    echo '</span>';
                                                endif;
                                                ?>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <!-- Read more link -->
                                        <div class="search-result-actions">
                                            <a href="<?php the_permalink(); ?>" class="read-more-link">
                                                <?php esc_html_e('Read More', KV_THEME_TEXTDOMAIN); ?>
                                                <i class="fas fa-arrow-right" aria-hidden="true"></i>
                                            </a>
                                        </div>
                                        
                                    </div>
                                    
                                </div>
                                
                            </article><!-- #post-<?php the_ID(); ?> -->
                            
                        <?php endwhile; ?>
                        
                    </div><!-- .search-results-list -->
                    
                    <!-- Pagination -->
                    <div class="search-pagination">
                        <?php
                        the_posts_pagination(array(
                            'mid_size'  => 2,
                            'prev_text' => __('&larr; Previous', KV_THEME_TEXTDOMAIN),
                            'next_text' => __('Next &rarr;', KV_THEME_TEXTDOMAIN),
                        ));
                        ?>
                    </div>
                    
                <?php else : ?>
                    
                    <div class="no-search-results">
                        
                        <!-- Search suggestions -->
                        <div class="search-suggestions">
                            <h3><?php esc_html_e('Search Suggestions', KV_THEME_TEXTDOMAIN); ?></h3>
                            <ul>
                                <li><?php esc_html_e('Make sure all words are spelled correctly.', KV_THEME_TEXTDOMAIN); ?></li>
                                <li><?php esc_html_e('Try different keywords.', KV_THEME_TEXTDOMAIN); ?></li>
                                <li><?php esc_html_e('Try more general keywords.', KV_THEME_TEXTDOMAIN); ?></li>
                                <li><?php esc_html_e('Try fewer keywords.', KV_THEME_TEXTDOMAIN); ?></li>
                            </ul>
                        </div>
                        
                        <!-- Popular content -->
                        <?php
                        $popular_posts = get_posts(array(
                            'numberposts' => 5,
                            'meta_key' => 'post_views_count',
                            'orderby' => 'meta_value_num',
                            'order' => 'DESC'
                        ));
                        
                        if ($popular_posts) : ?>
                            <div class="popular-content">
                                <h3><?php esc_html_e('Popular Content', KV_THEME_TEXTDOMAIN); ?></h3>
                                <ul class="popular-posts-list">
                                    <?php foreach ($popular_posts as $post) :
                                        setup_postdata($post); ?>
                                        <li>
                                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                            <span class="post-date"><?php echo get_the_date(); ?></span>
                                        </li>
                                    <?php endforeach;
                                    wp_reset_postdata(); ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Browse categories -->
                        <?php
                        $categories = get_categories(array(
                            'orderby' => 'count',
                            'order'   => 'DESC',
                            'number'  => 10,
                            'hide_empty' => true
                        ));
                        
                        if ($categories) : ?>
                            <div class="browse-categories">
                                <h3><?php esc_html_e('Browse by Category', KV_THEME_TEXTDOMAIN); ?></h3>
                                <div class="categories-list">
                                    <?php foreach ($categories as $category) : ?>
                                        <a href="<?php echo esc_url(get_category_link($category->term_id)); ?>" class="category-link">
                                            <?php echo esc_html($category->name); ?>
                                            <span class="category-count">(<?php echo $category->count; ?>)</span>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                    </div>
                    
                <?php endif; ?>
                
            </main><!-- #primary -->
        </div>
        
        <div class="col-lg-4">
            <?php get_sidebar(); ?>
        </div>
    </div>
</div>

<style>
/* Search Results Styles */
.search-header {
    margin-bottom: 2rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid var(--border-color);
}

.search-term {
    color: var(--primary-color);
    font-weight: 600;
}

.search-meta {
    margin-top: 1rem;
}

.search-found {
    color: var(--text-muted);
    margin-bottom: 1rem;
}

.search-again {
    margin-top: 1rem;
}

.search-filters {
    background: var(--background-light);
    padding: 1.5rem;
    border-radius: var(--border-radius);
    margin: 1.5rem 0;
}

.search-filters h3 {
    margin-bottom: 1rem;
    font-size: 1.2rem;
}

.search-filter-form {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    align-items: end;
}

.filter-group {
    display: flex;
    flex-direction: column;
}

.filter-group label {
    margin-bottom: 0.5rem;
    font-weight: 600;
    font-size: 0.9rem;
}

.search-sorting {
    display: flex;
    justify-content: flex-end;
    margin-bottom: 1.5rem;
    padding: 1rem;
    background: var(--background-light);
    border-radius: var(--border-radius);
}

.sorting-form {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.search-results-list {
    margin-bottom: 2rem;
}

.search-result-item {
    border: 1px solid var(--border-color);
    border-radius: var(--border-radius);
    margin-bottom: 1.5rem;
    overflow: hidden;
    transition: box-shadow 0.3s ease;
}

.search-result-item:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.search-result-content {
    display: flex;
    gap: 1rem;
    padding: 1.5rem;
}

.search-result-thumbnail {
    flex-shrink: 0;
    width: 150px;
}

.search-result-thumbnail img {
    width: 100%;
    height: 100px;
    object-fit: cover;
    border-radius: var(--border-radius);
}

.search-result-text {
    flex: 1;
}

.search-result-meta {
    color: var(--text-muted);
    font-size: 0.9rem;
    margin-bottom: 0.5rem;
}

.meta-separator {
    margin: 0 0.5rem;
}

.search-result-title {
    margin: 0 0 1rem 0;
    font-size: 1.3rem;
    line-height: 1.4;
}

.search-result-title a {
    color: var(--heading-color);
    text-decoration: none;
}

.search-result-title a:hover {
    color: var(--primary-color);
}

.search-result-excerpt {
    margin-bottom: 1rem;
    color: var(--text-color);
    line-height: 1.6;
}

.search-result-terms {
    margin-bottom: 1rem;
    font-size: 0.9rem;
}

.result-categories,
.result-tags {
    display: inline-block;
    margin-right: 1rem;
    color: var(--text-muted);
}

.result-categories a,
.result-tags a {
    color: var(--primary-color);
    text-decoration: none;
}

.result-categories a:hover,
.result-tags a:hover {
    text-decoration: underline;
}

.search-result-actions {
    margin-top: 1rem;
}

.read-more-link {
    color: var(--primary-color);
    text-decoration: none;
    font-weight: 600;
    font-size: 0.9rem;
}

.read-more-link:hover {
    text-decoration: underline;
}

/* Search term highlighting */
.search-highlight {
    background-color: #fff59d;
    padding: 0.1em 0.2em;
    border-radius: 2px;
    font-weight: 600;
}

/* No results styles */
.no-search-results {
    text-align: center;
    padding: 2rem 0;
}

.search-suggestions {
    margin-bottom: 2rem;
    text-align: left;
}

.search-suggestions ul {
    list-style: disc;
    padding-left: 2rem;
}

.search-suggestions li {
    margin-bottom: 0.5rem;
}

.popular-content,
.browse-categories {
    margin-bottom: 2rem;
    text-align: left;
}

.popular-posts-list {
    list-style: none;
    padding: 0;
}

.popular-posts-list li {
    padding: 0.5rem 0;
    border-bottom: 1px solid var(--border-color);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.popular-posts-list a {
    color: var(--heading-color);
    text-decoration: none;
}

.popular-posts-list a:hover {
    color: var(--primary-color);
}

.post-date {
    color: var(--text-muted);
    font-size: 0.9rem;
}

.categories-list {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
}

.category-link {
    display: inline-block;
    padding: 0.5rem 1rem;
    background: var(--background-light);
    border: 1px solid var(--border-color);
    border-radius: var(--border-radius);
    text-decoration: none;
    color: var(--text-color);
    font-size: 0.9rem;
    transition: all 0.3s ease;
}

.category-link:hover {
    background: var(--primary-color);
    color: white;
    border-color: var(--primary-color);
}

.category-count {
    opacity: 0.7;
    font-size: 0.8rem;
}

/* Responsive design */
@media (max-width: 768px) {
    .search-filter-form {
        grid-template-columns: 1fr;
    }
    
    .search-result-content {
        flex-direction: column;
    }
    
    .search-result-thumbnail {
        width: 100%;
    }
    
    .search-result-thumbnail img {
        height: 200px;
    }
    
    .sorting-form {
        flex-direction: column;
        gap: 1rem;
    }
}

@media (max-width: 480px) {
    .search-result-content {
        padding: 1rem;
    }
    
    .categories-list {
        flex-direction: column;
    }
    
    .category-link {
        text-align: center;
    }
}
</style>

<?php get_footer(); ?>
