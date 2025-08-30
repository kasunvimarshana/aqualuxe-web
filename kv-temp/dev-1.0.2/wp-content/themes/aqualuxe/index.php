<?php

/**
 * Main Blog Template
 *
 * @package aqualuxe
 */

get_header(); ?>

<div class="aqualuxe-page blog-page">
    <div class="container">
        <div class="page-header">
            <h1 class="page-title"><?php esc_html_e('AquaLuxe Blog', 'aqualuxe'); ?></h1>
            <p class="page-description"><?php esc_html_e('Fish care tips, breeding techniques, and industry news', 'aqualuxe'); ?></p>
        </div>

        <div class="blog-container">
            <div class="blog-posts">
                <?php if (have_posts()) : ?>
                    <div class="posts-grid">
                        <?php while (have_posts()) : the_post(); ?>
                            <article id="post-<?php the_ID(); ?>" <?php post_class('post-item'); ?>>
                                <?php if (has_post_thumbnail()) : ?>
                                    <div class="post-thumbnail">
                                        <a href="<?php the_permalink(); ?>">
                                            <?php the_post_thumbnail('medium_large'); ?>
                                        </a>
                                    </div>
                                <?php endif; ?>

                                <div class="post-content">
                                    <div class="post-category">
                                        <?php
                                        $categories = get_the_category();
                                        if (!empty($categories)) {
                                            echo '<a href="' . esc_url(get_category_link($categories[0]->term_id)) . '">' . esc_html($categories[0]->name) . '</a>';
                                        }
                                        ?>
                                    </div>

                                    <h2 class="post-title">
                                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                    </h2>

                                    <div class="post-meta">
                                        <span class="post-date"><?php echo get_the_date(); ?></span>
                                        <span class="post-author"><?php esc_html_e('by', 'aqualuxe'); ?> <?php the_author(); ?></span>
                                    </div>

                                    <div class="post-excerpt">
                                        <?php the_excerpt(); ?>
                                    </div>

                                    <div class="read-more">
                                        <a href="<?php the_permalink(); ?>" class="button button-small"><?php esc_html_e('Read More', 'aqualuxe'); ?></a>
                                    </div>
                                </div>
                            </article>
                        <?php endwhile; ?>
                    </div>

                    <div class="pagination">
                        <?php
                        the_posts_pagination(array(
                            'mid_size'  => 2,
                            'prev_text' => __('&laquo; Previous', 'aqualuxe'),
                            'next_text' => __('Next &raquo;', 'aqualuxe'),
                        ));
                        ?>
                    </div>
                <?php else : ?>
                    <div class="no-posts">
                        <h2><?php esc_html_e('No posts found', 'aqualuxe'); ?></h2>
                        <p><?php esc_html_e('Sorry, but there are no posts in the blog yet.', 'aqualuxe'); ?></p>
                    </div>
                <?php endif; ?>
            </div>

            <div class="blog-sidebar">
                <?php get_sidebar(); ?>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>