<?php
/**
 * Archive Template
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

get_header(); ?>

<main id="primary" class="site-main">
    <div class="container mx-auto px-4 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2">
                <header class="page-header mb-8">
                    <?php
                    the_archive_title('<h1 class="page-title text-3xl font-bold text-gray-900 mb-4">', '</h1>');
                    the_archive_description('<div class="archive-description text-gray-600">', '</div>');
                    ?>
                </header>

                <?php if (have_posts()) : ?>
                    <div class="posts-grid space-y-8">
                        <?php while (have_posts()) : the_post(); ?>
                            <article id="post-<?php the_ID(); ?>" <?php post_class('post-card bg-white rounded-lg shadow-md overflow-hidden'); ?>>
                                <div class="post-content flex flex-col md:flex-row">
                                    <?php if (has_post_thumbnail()) : ?>
                                        <div class="post-thumbnail md:w-1/3">
                                            <a href="<?php the_permalink(); ?>" class="block">
                                                <?php the_post_thumbnail('medium', array('class' => 'w-full h-48 md:h-full object-cover')); ?>
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div class="post-body p-6 <?php echo has_post_thumbnail() ? 'md:w-2/3' : 'w-full'; ?>">
                                        <header class="entry-header mb-4">
                                            <?php
                                            if (is_singular()) :
                                                the_title('<h1 class="entry-title text-2xl font-bold text-gray-900">', '</h1>');
                                            else :
                                                the_title('<h2 class="entry-title text-xl font-semibold mb-2"><a href="' . esc_url(get_permalink()) . '" class="text-gray-900 hover:text-primary-600">', '</a></h2>');
                                            endif;
                                            ?>
                                            
                                            <div class="entry-meta text-sm text-gray-500">
                                                <span class="posted-on">
                                                    <time class="entry-date published" datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                                                        <?php echo esc_html(get_the_date()); ?>
                                                    </time>
                                                </span>
                                                
                                                <span class="byline ml-4">
                                                    <?php _e('by', 'aqualuxe'); ?>
                                                    <span class="author vcard">
                                                        <a class="url fn n" href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>">
                                                            <?php echo esc_html(get_the_author()); ?>
                                                        </a>
                                                    </span>
                                                </span>
                                                
                                                <?php if (get_comments_number() > 0) : ?>
                                                    <span class="comments-link ml-4">
                                                        <a href="<?php echo esc_url(get_comments_link()); ?>">
                                                            <?php
                                                            printf(
                                                                _n('%d comment', '%d comments', get_comments_number(), 'aqualuxe'),
                                                                get_comments_number()
                                                            );
                                                            ?>
                                                        </a>
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        </header>

                                        <div class="entry-summary text-gray-600 mb-4">
                                            <?php the_excerpt(); ?>
                                        </div>

                                        <footer class="entry-footer">
                                            <a href="<?php the_permalink(); ?>" class="read-more btn btn-outline-primary btn-sm">
                                                <?php _e('Read More', 'aqualuxe'); ?>
                                                <?php aqualuxe_svg_icon('arrow-right', array('class' => 'ml-2 inline w-4 h-4')); ?>
                                            </a>
                                        </footer>
                                    </div>
                                </div>
                            </article>
                        <?php endwhile; ?>
                    </div>

                    <?php
                    the_posts_navigation(array(
                        'prev_text' => __('Older posts', 'aqualuxe'),
                        'next_text' => __('Newer posts', 'aqualuxe'),
                    ));
                    ?>

                <?php else : ?>
                    <div class="no-results not-found bg-white rounded-lg shadow-sm p-8 text-center">
                        <header class="page-header mb-6">
                            <h1 class="page-title text-2xl font-bold text-gray-900"><?php _e('Nothing here', 'aqualuxe'); ?></h1>
                        </header>

                        <div class="page-content text-gray-600">
                            <?php if (is_home() && current_user_can('publish_posts')) : ?>
                                <p><?php
                                    printf(
                                        wp_kses(
                                            /* translators: 1: Link to WP admin new post page. */
                                            __('Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'aqualuxe'),
                                            array(
                                                'a' => array(
                                                    'href' => array(),
                                                ),
                                            )
                                        ),
                                        esc_url(admin_url('post-new.php'))
                                    );
                                    ?></p>
                            <?php elseif (is_search()) : ?>
                                <p><?php _e('Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'aqualuxe'); ?></p>
                                <?php get_search_form(); ?>
                            <?php else : ?>
                                <p><?php _e('It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'aqualuxe'); ?></p>
                                <?php get_search_form(); ?>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <div class="lg:col-span-1">
                <?php get_sidebar(); ?>
            </div>
        </div>
    </div>
</main>

<?php get_footer(); ?>