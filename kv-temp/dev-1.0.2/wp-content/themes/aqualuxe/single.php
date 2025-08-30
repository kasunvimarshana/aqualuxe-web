<?php

/**
 * Single Post Template
 *
 * @package aqualuxe
 */

get_header(); ?>

<div class="aqualuxe-page single-post-page">
    <div class="container">
        <div class="post-container">
            <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                        <div class="post-header">
                            <div class="post-category">
                                <?php
                                $categories = get_the_category();
                                if (!empty($categories)) {
                                    echo '<a href="' . esc_url(get_category_link($categories[0]->term_id)) . '">' . esc_html($categories[0]->name) . '</a>';
                                }
                                ?>
                            </div>

                            <h1 class="post-title"><?php the_title(); ?></h1>

                            <div class="post-meta">
                                <span class="post-date"><?php echo get_the_date(); ?></span>
                                <span class="post-author"><?php esc_html_e('by', 'aqualuxe'); ?> <?php the_author(); ?></span>
                                <span class="post-comments"><?php comments_popup_link(__('No Comments', 'aqualuxe'), __('1 Comment', 'aqualuxe'), __('% Comments', 'aqualuxe')); ?></span>
                            </div>
                        </div>

                        <?php if (has_post_thumbnail()) : ?>
                            <div class="post-thumbnail">
                                <?php the_post_thumbnail('large'); ?>
                            </div>
                        <?php endif; ?>

                        <div class="post-content">
                            <?php the_content(); ?>

                            <div class="post-tags">
                                <?php the_tags('<span class="tag-label">' . __('Tags:', 'aqualuxe') . '</span> ', ', ', ''); ?>
                            </div>
                        </div>

                        <div class="post-navigation">
                            <div class="nav-prev">
                                <?php previous_post_link('%link', '<i class="fas fa-arrow-left"></i> %title'); ?>
                            </div>
                            <div class="nav-next">
                                <?php next_post_link('%link', '%title <i class="fas fa-arrow-right"></i>'); ?>
                            </div>
                        </div>

                        <div class="author-bio">
                            <div class="author-avatar">
                                <?php echo get_avatar(get_the_author_meta('ID'), 100); ?>
                            </div>
                            <div class="author-info">
                                <h3><?php esc_html_e('About the Author', 'aqualuxe'); ?></h3>
                                <h4><?php the_author(); ?></h4>
                                <p><?php the_author_meta('description'); ?></p>
                                <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>" class="button button-small">
                                    <?php esc_html_e('View all posts', 'aqualuxe'); ?>
                                </a>
                            </div>
                        </div>

                        <?php
                        // If comments are open or we have at least one comment, load up the comment template.
                        if (comments_open() || get_comments_number()) :
                            comments_template();
                        endif;
                        ?>
                    </article>
            <?php endwhile;
            endif; ?>
        </div>

        <div class="post-sidebar">
            <?php get_sidebar(); ?>
        </div>
    </div>
</div>

<?php get_footer(); ?>