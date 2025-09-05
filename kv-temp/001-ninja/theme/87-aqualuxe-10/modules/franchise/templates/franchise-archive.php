<?php
/**
 * The template for displaying the Franchise Opportunities archive.
 */

get_header();
?>

<div id="primary" class="content-area">
    <main id="main" class="site-main container mx-auto py-12 px-4">

        <header class="page-header text-center mb-12">
            <h1 class="page-title text-4xl md:text-5xl font-extrabold text-gray-900 dark:text-white">
                <?php post_type_archive_title(); ?>
            </h1>
            <?php the_archive_description('<div class="archive-description text-lg text-gray-600 dark:text-gray-400 mt-4 max-w-3xl mx-auto">', '</div>'); ?>
        </header>

        <?php if (have_posts()) : ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php
                while (have_posts()) :
                    the_post();
                    get_template_part('modules/franchise/templates/franchise-summary');
                endwhile;
                ?>
            </div>

            <?php
            the_posts_pagination([
                'prev_text' => __('&larr; Previous', 'aqualuxe'),
                'next_text' => __('Next &rarr;', 'aqualuxe'),
            ]);
            ?>

        <?php else : ?>
            <p><?php _e('No franchise opportunities found.', 'aqualuxe'); ?></p>
        <?php endif; ?>

    </main>
</div>

<?php
get_footer();
