<?php
/**
 * The template for displaying a single Sustainability Initiative.
 */

get_header();
?>

<div id="primary" class="content-area">
    <main id="main" class="site-main">

        <?php while (have_posts()) : the_post(); ?>

            <article id="post-<?php the_ID(); ?>" <?php post_class('aqualuxe-sustainability-single'); ?>>
                
                <header class="entry-header relative h-[60vh] flex items-center justify-center text-center text-white bg-gray-700">
                    <?php if (has_post_thumbnail()) : ?>
                        <div class="absolute inset-0">
                            <?php the_post_thumbnail('full', ['class' => 'w-full h-full object-cover']); ?>
                            <div class="absolute inset-0 bg-black/50"></div>
                        </div>
                    <?php endif; ?>
                    <div class="relative container mx-auto px-4">
                        <?php the_title('<h1 class="entry-title text-4xl md:text-6xl font-extrabold tracking-tight">', '</h1>'); ?>
                        <p class="mt-4 text-xl opacity-90"><?php echo get_the_excerpt(); ?></p>
                    </div>
                </header>

                <div class="container mx-auto py-16 px-4">
                    <div class="max-w-4xl mx-auto">
                        <div class="entry-content prose lg:prose-xl max-w-none dark:prose-invert">
                            <?php the_content(); ?>
                        </div>

                        <footer class="entry-footer mt-12 pt-8 border-t border-gray-200 dark:border-gray-700">
                            <?php
                            $categories = get_the_terms(get_the_ID(), \AquaLuxe\Core\Services\SustainabilityService::TAXONOMY_SLUG);
                            if ($categories && !is_wp_error($categories)) {
                                echo '<div class="categories-links">';
                                echo '<h3 class="text-lg font-semibold mb-2">' . __('Filed Under:', 'aqualuxe') . '</h3>';
                                foreach ($categories as $category) {
                                    echo '<a href="' . esc_url(get_term_link($category)) . '" class="inline-block bg-gray-100 dark:bg-gray-700 rounded-full px-3 py-1 text-sm font-semibold text-gray-700 dark:text-gray-200 mr-2 mb-2 hover:bg-gray-200 dark:hover:bg-gray-600">' . esc_html($category->name) . '</a>';
                                }
                                echo '</div>';
                            }
                            ?>
                        </footer>
                    </div>
                </div>

            </article>

        <?php endwhile; ?>

    </main>
</div>

<?php
get_footer();
