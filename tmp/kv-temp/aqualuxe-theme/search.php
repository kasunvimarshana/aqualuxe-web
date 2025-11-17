<?php
/**
 * The template for displaying search results pages
 *
 * @package AquaLuxe
 * @version 1.0.0
 */

get_header(); ?>

<div class="site-content search-results-page">
    <div class="container">
        
        <main id="main" class="site-main" role="main">
            
            <?php if (have_posts()) : ?>
                
                <header class="page-header">
                    <h1 class="page-title">
                        <?php
                        printf(
                            esc_html__('Search Results for: %s', AquaLuxeTheme::TEXT_DOMAIN),
                            '<span>' . get_search_query() . '</span>'
                        );
                        ?>
                    </h1>
                </header>
                
                <div class="search-results">
                    <?php while (have_posts()) : the_post(); ?>
                        
                        <article id="post-<?php the_ID(); ?>" <?php post_class('search-result'); ?>>
                            
                            <?php if (has_post_thumbnail()) : ?>
                                <div class="result-thumbnail">
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_post_thumbnail('thumbnail'); ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                            
                            <div class="result-content">
                                <header class="entry-header">
                                    <?php the_title('<h2 class="entry-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h2>'); ?>
                                </header>
                                
                                <div class="entry-summary">
                                    <?php the_excerpt(); ?>
                                </div>
                                
                                <footer class="entry-footer">
                                    <a href="<?php the_permalink(); ?>" class="read-more">
                                        <?php _e('Read More', AquaLuxeTheme::TEXT_DOMAIN); ?>
                                    </a>
                                </footer>
                            </div>
                            
                        </article>
                        
                    <?php endwhile; ?>
                </div>
                
                <?php
                the_posts_pagination([
                    'mid_size' => 2,
                    'prev_text' => __('&laquo; Previous', AquaLuxeTheme::TEXT_DOMAIN),
                    'next_text' => __('Next &raquo;', AquaLuxeTheme::TEXT_DOMAIN),
                ]);
                ?>
                
            <?php else : ?>
                
                <section class="no-results not-found">
                    <header class="page-header">
                        <h1 class="page-title"><?php _e('Nothing found', AquaLuxeTheme::TEXT_DOMAIN); ?></h1>
                    </header>
                    
                    <div class="page-content">
                        <p><?php _e('Sorry, but nothing matched your search terms. Please try again with some different keywords.', AquaLuxeTheme::TEXT_DOMAIN); ?></p>
                        <?php get_search_form(); ?>
                    </div>
                </section>
                
            <?php endif; ?>
            
        </main>
        
        <?php get_sidebar(); ?>
        
    </div>
</div>

<?php get_footer(); ?>
