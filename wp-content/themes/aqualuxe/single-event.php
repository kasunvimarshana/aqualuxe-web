<?php
/**
 * The template for displaying all single events.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Aqualuxe
 */

get_header();
?>

<div id="primary" class="content-area">
    <main id="main" class="site-main py-12">

        <div class="container mx-auto px-4">
            <?php
            while (have_posts()) :
                the_post();
            ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class('bg-white shadow-lg rounded-lg overflow-hidden'); ?>>
                    <header class="entry-header p-8">
                        <?php the_title('<h1 class="entry-title text-4xl font-bold text-gray-800">', '</h1>'); ?>
                        <div class="entry-meta mt-2 text-sm text-gray-500">
                            <?php
                            // Display event categories
                            $terms = get_the_terms(get_the_ID(), 'event-category');
                            if ($terms && !is_wp_error($terms)) {
                                echo '<span class="cat-links">';
                                foreach ($terms as $term) {
                                    echo '<a href="' . get_term_link($term) . '" class="mr-2">' . $term->name . '</a>';
                                }
                                echo '</span>';
                            }
                            ?>
                        </div><!-- .entry-meta -->
                    </header><!-- .entry-header -->

                    <?php if (has_post_thumbnail()) : ?>
                        <div class="post-thumbnail p-8">
                            <?php the_post_thumbnail('large', ['class' => 'w-full h-auto rounded-md']); ?>
                        </div><!-- .post-thumbnail -->
                    <?php endif; ?>

                    <div class="entry-content p-8">
                        <?php
                        the_content();

                        wp_link_pages(
                            array(
                                'before' => '<div class="page-links">' . esc_html__('Pages:', 'aqualuxe'),
                                'after'  => '</div>',
                            )
                        );
                        ?>
                    </div><!-- .entry-content -->

                </article><!-- #post-<?php the_ID(); ?> -->

                <?php
                // If comments are open or we have at least one comment, load up the comment template.
                if (comments_open() || get_comments_number()) :
                    comments_template();
                endif;

            endwhile; // End of the loop.
            ?>
        </div><!-- .container -->

    </main><!-- #main -->
</div><!-- #primary -->

<?php
get_footer();
