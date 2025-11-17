<?php
/**
 * The template for displaying the Sustainability Initiatives archive.
 */

get_header();
?>

<div id="primary" class="content-area bg-gray-50 dark:bg-gray-900">
    <main id="main" class="site-main container mx-auto py-16 px-4">

        <header class="page-header text-center mb-16">
            <h1 class="page-title text-4xl md:text-6xl font-extrabold text-gray-900 dark:text-white tracking-tight">
                <?php post_type_archive_title(); ?>
            </h1>
            <?php the_archive_description('<div class="archive-description text-lg text-gray-600 dark:text-gray-400 mt-4 max-w-3xl mx-auto">', '</div>'); ?>
        </header>

        <?php if (have_posts()) : ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                <?php
                while (have_posts()) :
                    the_post();
                    get_template_part('modules/sustainability/templates/sustainability-summary');
                endwhile;
                ?>
            </div>

            <?php
            the_posts_pagination([
                'prev_text' => __('&larr; Newer Initiatives', 'aqualuxe'),
                'next_text' => __('Older Initiatives &rarr;', 'aqualuxe'),
                'screen_reader_text' => __('Initiatives navigation', 'aqualuxe'),
            ]);
            ?>

        <?php else : ?>
            <div class="text-center">
                <p class="text-xl text-gray-500"><?php _e('No sustainability initiatives have been posted yet.', 'aqualuxe'); ?></p>
            </div>
        <?php endif; ?>

    </main>
</div>

<?php
get_footer();
