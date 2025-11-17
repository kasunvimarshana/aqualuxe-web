<?php
/**
 * The main template file
 *
 * @package AquaLuxe
 * @version 1.0.0
 */

get_header(); ?>

<div class="site-content">
    <div class="container">
        
        <?php if (is_home() && !is_front_page()) : ?>
            <header class="page-header">
                <h1 class="page-title"><?php single_post_title(); ?></h1>
            </header>
        <?php endif; ?>

        <main id="main" class="site-main" role="main">
            
            <?php if (have_posts()) : ?>
                
                <div class="posts-grid">
                    <?php while (have_posts()) : the_post(); ?>
                        
                        <article id="post-<?php the_ID(); ?>" <?php post_class('post-card fade-in-up'); ?>>
                            
                            <?php if (has_post_thumbnail()) : ?>
                                <div class="post-thumbnail">
                                    <a href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
                                        <?php the_post_thumbnail('aqualuxe-gallery', [
                                            'alt' => the_title_attribute(['echo' => false])
                                        ]); ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                            
                            <div class="post-content">
                                <header class="entry-header">
                                    <?php
                                    if (is_singular()) :
                                        the_title('<h1 class="entry-title">', '</h1>');
                                    else :
                                        the_title('<h2 class="entry-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h2>');
                                    endif;
                                    ?>
                                    
                                    <div class="entry-meta">
                                        <span class="posted-on">
                                            <time class="entry-date published" datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                                                <?php echo esc_html(get_the_date()); ?>
                                            </time>
                                        </span>
                                        <span class="byline">
                                            <?php _e('by', AquaLuxeTheme::TEXT_DOMAIN); ?>
                                            <span class="author vcard">
                                                <a class="url fn n" href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>">
                                                    <?php echo esc_html(get_the_author()); ?>
                                                </a>
                                            </span>
                                        </span>
                                    </div>
                                </header>
                                
                                <div class="entry-summary">
                                    <?php echo wp_kses_post(aqualuxe_excerpt(25)); ?>
                                </div>
                                
                                <footer class="entry-footer">
                                    <a href="<?php the_permalink(); ?>" class="read-more">
                                        <?php _e('Read More', AquaLuxeTheme::TEXT_DOMAIN); ?>
                                        <span class="sr-only"><?php printf(__('about %s', AquaLuxeTheme::TEXT_DOMAIN), get_the_title()); ?></span>
                                    </a>
                                </footer>
                            </div>
                            
                        </article>
                        
                    <?php endwhile; ?>
                </div>
                
                <?php
                // Pagination
                the_posts_pagination([
                    'mid_size' => 2,
                    'prev_text' => __('&laquo; Previous', AquaLuxeTheme::TEXT_DOMAIN),
                    'next_text' => __('Next &raquo;', AquaLuxeTheme::TEXT_DOMAIN),
                ]);
                ?>
                
            <?php else : ?>
                
                <section class="no-results not-found">
                    <header class="page-header">
                        <h1 class="page-title"><?php _e('Nothing here', AquaLuxeTheme::TEXT_DOMAIN); ?></h1>
                    </header>
                    
                    <div class="page-content">
                        <?php if (is_home() && current_user_can('publish_posts')) : ?>
                            <p>
                                <?php
                                printf(
                                    wp_kses(
                                        __('Ready to publish your first post? <a href="%1$s">Get started here</a>.', AquaLuxeTheme::TEXT_DOMAIN),
                                        ['a' => ['href' => []]]
                                    ),
                                    esc_url(admin_url('post-new.php'))
                                );
                                ?>
                            </p>
                        <?php elseif (is_search()) : ?>
                            <p><?php _e('Sorry, but nothing matched your search terms. Please try again with some different keywords.', AquaLuxeTheme::TEXT_DOMAIN); ?></p>
                            <?php get_search_form(); ?>
                        <?php else : ?>
                            <p><?php _e('It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', AquaLuxeTheme::TEXT_DOMAIN); ?></p>
                            <?php get_search_form(); ?>
                        <?php endif; ?>
                    </div>
                </section>
                
            <?php endif; ?>
            
        </main>
        
        <?php get_sidebar(); ?>
        
    </div>
</div>

<?php get_footer(); ?>
