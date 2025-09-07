<?php
/**
 * The template for displaying a single Franchise Opportunity.
 */

get_header();

$post_id = get_the_ID();
$meta = get_post_meta($post_id);

$location = $meta['franchise_location'][0] ?? 'N/A';
$investment = $meta['franchise_investment'][0] ?? 'Contact for details';
$contact_email = get_option('admin_email'); // Default contact

?>

<div id="primary" class="content-area">
    <main id="main" class="site-main">

        <?php while (have_posts()) : the_post(); ?>

            <article id="post-<?php the_ID(); ?>" <?php post_class('aqualuxe-franchise-single'); ?>>
                
                <header class="entry-header bg-gray-100 dark:bg-gray-800 py-20 text-center">
                    <div class="container mx-auto px-4">
                        <?php the_title('<h1 class="entry-title text-4xl md:text-5xl font-extrabold text-gray-900 dark:text-white">', '</h1>'); ?>
                        <p class="mt-4 text-xl text-gray-600 dark:text-gray-400"><?php echo get_the_excerpt(); ?></p>
                    </div>
                </header>

                <div class="container mx-auto py-12 px-4 grid grid-cols-1 lg:grid-cols-3 gap-12">
                    
                    <div class="lg:col-span-2">
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="post-thumbnail mb-8 rounded-lg overflow-hidden shadow-lg">
                                <?php the_post_thumbnail('full'); ?>
                            </div>
                        <?php endif; ?>

                        <div class="entry-content prose lg:prose-xl max-w-none dark:prose-invert">
                            <?php the_content(); ?>
                        </div>
                    </div>

                    <aside class="lg:col-span-1">
                        <div class="sticky top-8 bg-white dark:bg-gray-800 p-8 rounded-lg shadow-lg">
                            <h3 class="text-2xl font-bold mb-6 text-gray-900 dark:text-white">Opportunity Details</h3>
                            <ul class="space-y-4 text-lg">
                                <li class="flex items-center"><span class="dashicons dashicons-location-alt text-blue-500 mr-3"></span><strong>Location:</strong> <span class="ml-auto"><?php echo esc_html($location); ?></span></li>
                                <li class="flex items-center"><span class="dashicons dashicons-money-alt text-green-500 mr-3"></span><strong>Investment:</strong> <span class="ml-auto"><?php echo esc_html($investment); ?></span></li>
                            </ul>
                            <a href="mailto:<?php echo esc_attr($contact_email); ?>?subject=Inquiry about <?php echo esc_attr(get_the_title()); ?>" class="aqualuxe-btn-primary w-full text-center mt-8">
                                <?php _e('Inquire Now', 'aqualuxe'); ?>
                            </a>
                        </div>
                    </aside>

                </div>

            </article>

        <?php endwhile; ?>

    </main>
</div>

<?php
get_footer();
