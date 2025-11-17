<?php
/**
 * The main template file
 *
 * @package AquaLuxe
 */

get_header(); ?>

<div class="aqualuxe-hero">
    <div class="aqualuxe-hero-content">
        <h1><?php _e('Welcome to AquaLuxe', 'aqualuxe-child'); ?></h1>
        <p><?php _e('Discover the finest collection of ornamental fish and aquatic accessories', 'aqualuxe-child'); ?></p>
        <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="button">
            <?php _e('Shop Now', 'aqualuxe-child'); ?>
        </a>
    </div>
</div>

<main id="main" class="site-main">
    <div class="container">
        <?php if (have_posts()) : ?>
            <div class="posts-grid">
                <?php while (have_posts()) : the_post(); ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class('post-card'); ?>>
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="post-thumbnail">
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_post_thumbnail('medium'); ?>
                                </a>
                            </div>
                        <?php endif; ?>
                        
                        <div class="post-content">
                            <h2 class="post-title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h2>
                            
                            <div class="post-meta">
                                <span class="post-date"><?php echo get_the_date(); ?></span>
                                <span class="post-author"><?php _e('by', 'aqualuxe-child'); ?> <?php the_author(); ?></span>
                            </div>
                            
                            <div class="post-excerpt">
                                <?php the_excerpt(); ?>
                            </div>
                            
                            <a href="<?php the_permalink(); ?>" class="read-more">
                                <?php _e('Read More', 'aqualuxe-child'); ?>
                            </a>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>
            
            <?php the_posts_pagination(); ?>
        <?php else : ?>
            <div class="no-posts">
                <h2><?php _e('Nothing Found', 'aqualuxe-child'); ?></h2>
                <p><?php _e('It seems we can\'t find what you\'re looking for.', 'aqualuxe-child'); ?></p>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php get_footer(); ?>
