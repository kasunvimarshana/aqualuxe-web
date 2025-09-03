<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Enterprise_Theme
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

get_header(); ?>

<main id="main" class="site-main" role="main">
    <div class="container">
        
        <?php if (have_posts()) : ?>
            
            <!-- Page Header -->
            <header class="page-header mb-8">
                <?php if (is_home() && !is_front_page()) : ?>
                    <h1 class="page-title text-4xl font-bold mb-4">
                        <?php single_post_title(); ?>
                    </h1>
                <?php elseif (is_archive()) : ?>
                    <h1 class="page-title text-4xl font-bold mb-4">
                        <?php the_archive_title(); ?>
                    </h1>
                    <?php if (get_the_archive_description()) : ?>
                        <div class="archive-description text-lg text-secondary">
                            <?php echo wp_kses_post(wpautop(get_the_archive_description())); ?>
                        </div>
                    <?php endif; ?>
                <?php elseif (is_search()) : ?>
                    <h1 class="page-title text-4xl font-bold mb-4">
                        <?php
                        printf(
                            /* translators: %s: search query. */
                            esc_html__('Search Results for: %s', 'enterprise-theme'),
                            '<span class="text-primary">' . get_search_query() . '</span>'
                        );
                        ?>
                    </h1>
                <?php else : ?>
                    <h1 class="page-title text-4xl font-bold mb-4">
                        <?php esc_html_e('Latest Posts', 'enterprise-theme'); ?>
                    </h1>
                <?php endif; ?>
            </header>
            
            <!-- Posts Grid -->
            <div class="posts-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                
                <?php while (have_posts()) : the_post(); ?>
                    
                    <article id="post-<?php the_ID(); ?>" <?php post_class('post-card card'); ?>>
                        
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="post-thumbnail">
                                <a href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
                                    <?php the_post_thumbnail('medium_large', array(
                                        'class' => 'w-full h-48 object-cover',
                                        'alt' => the_title_attribute(array('echo' => false))
                                    )); ?>
                                </a>
                            </div>
                        <?php endif; ?>
                        
                        <div class="card-body">
                            
                            <!-- Post Meta -->
                            <div class="post-meta flex items-center gap-4 text-sm text-muted mb-3">
                                <time datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                                    <?php echo esc_html(get_the_date()); ?>
                                </time>
                                
                                <?php
                                $categories = get_the_category();
                                if (!empty($categories)) :
                                ?>
                                    <span class="post-categories">
                                        <?php
                                        foreach ($categories as $category) {
                                            echo '<a href="' . esc_url(get_category_link($category->term_id)) . '" class="text-primary hover:text-primary-dark">' . esc_html($category->name) . '</a>';
                                            if ($category !== end($categories)) {
                                                echo ', ';
                                            }
                                        }
                                        ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                            
                            <!-- Post Title -->
                            <h2 class="post-title text-xl font-semibold mb-3">
                                <a href="<?php the_permalink(); ?>" class="text-primary hover:text-primary-dark">
                                    <?php the_title(); ?>
                                </a>
                            </h2>
                            
                            <!-- Post Excerpt -->
                            <div class="post-excerpt text-secondary mb-4">
                                <?php the_excerpt(); ?>
                            </div>
                            
                            <!-- Read More -->
                            <a href="<?php the_permalink(); ?>" class="btn btn-outline btn-sm">
                                <?php esc_html_e('Read More', 'enterprise-theme'); ?>
                                <span class="sr-only">
                                    <?php 
                                    /* translators: %s: post title */
                                    printf(esc_html__('about %s', 'enterprise-theme'), get_the_title());
                                    ?>
                                </span>
                            </a>
                            
                        </div>
                        
                    </article>
                    
                <?php endwhile; ?>
                
            </div>
            
            <!-- Pagination -->
            <nav class="pagination-nav" aria-label="<?php esc_attr_e('Posts navigation', 'enterprise-theme'); ?>">
                <?php
                the_posts_pagination(array(
                    'mid_size'  => 2,
                    'prev_text' => sprintf(
                        '%s <span class="nav-prev-text">%s</span>',
                        '<span aria-hidden="true">&larr;</span>',
                        esc_html__('Previous', 'enterprise-theme')
                    ),
                    'next_text' => sprintf(
                        '<span class="nav-next-text">%s</span> %s',
                        esc_html__('Next', 'enterprise-theme'),
                        '<span aria-hidden="true">&rarr;</span>'
                    ),
                    'before_page_number' => '<span class="meta-nav screen-reader-text">' . esc_html__('Page', 'enterprise-theme') . ' </span>',
                ));
                ?>
            </nav>
            
        <?php else : ?>
            
            <!-- No Posts Found -->
            <section class="no-results not-found text-center py-16">
                <div class="page-content max-w-2xl mx-auto">
                    
                    <h1 class="page-title text-4xl font-bold mb-6">
                        <?php esc_html_e('Nothing here', 'enterprise-theme'); ?>
                    </h1>
                    
                    <?php if (is_home() && current_user_can('publish_posts')) : ?>
                        
                        <p class="text-lg text-secondary mb-6">
                            <?php
                            printf(
                                wp_kses(
                                    /* translators: 1: link to WP admin new post page. */
                                    __('Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'enterprise-theme'),
                                    array(
                                        'a' => array(
                                            'href' => array(),
                                        ),
                                    )
                                ),
                                esc_url(admin_url('post-new.php'))
                            );
                            ?>
                        </p>
                        
                    <?php elseif (is_search()) : ?>
                        
                        <p class="text-lg text-secondary mb-6">
                            <?php esc_html_e('Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'enterprise-theme'); ?>
                        </p>
                        
                        <div class="search-form-container">
                            <?php get_search_form(); ?>
                        </div>
                        
                    <?php else : ?>
                        
                        <p class="text-lg text-secondary mb-6">
                            <?php esc_html_e('It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'enterprise-theme'); ?>
                        </p>
                        
                        <div class="search-form-container">
                            <?php get_search_form(); ?>
                        </div>
                        
                    <?php endif; ?>
                    
                </div>
            </section>
            
        <?php endif; ?>
        
    </div>
</main>

<?php
get_sidebar();
get_footer();
