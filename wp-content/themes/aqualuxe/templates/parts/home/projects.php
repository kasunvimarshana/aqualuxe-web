<?php
/**
 * Projects Section for Home Page
 *
 * @package AquaLuxe
 */

// Query for projects
$projects_query = new WP_Query([
    'post_type'      => 'project',
    'posts_per_page' => 6,
    'post_status'    => 'publish',
    'orderby'        => 'date',
    'order'          => 'DESC'
]);
?>

<section class="projects-section py-20 bg-white">
    <div class="container mx-auto px-4">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-800 mb-4">Featured Projects</h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Explore our portfolio of stunning aquarium installations and aquatic environments created for clients worldwide.
            </p>
        </div>

        <?php if ($projects_query->have_posts()) : ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php while ($projects_query->have_posts()) : $projects_query->the_post(); ?>
                    <div class="project-card group relative overflow-hidden rounded-xl shadow-lg hover:shadow-2xl transition-all duration-500">
                        <div class="aspect-w-16 aspect-h-12 relative overflow-hidden">
                            <?php if (has_post_thumbnail()) : ?>
                                <?php the_post_thumbnail('large', [
                                    'class' => 'w-full h-64 object-cover transition-transform duration-500 group-hover:scale-110'
                                ]); ?>
                            <?php else : ?>
                                <div class="w-full h-64 bg-gradient-to-br from-blue-200 to-cyan-300 flex items-center justify-center">
                                    <svg class="w-20 h-20 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            <?php endif; ?>

                            <!-- Overlay -->
                            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-60 transition-all duration-300 flex items-center justify-center">
                                <div class="transform translate-y-4 group-hover:translate-y-0 opacity-0 group-hover:opacity-100 transition-all duration-300 text-center text-white">
                                    <h3 class="text-lg font-semibold mb-2"><?php the_title(); ?></h3>
                                    <?php
                                    $project_categories = get_the_terms(get_the_ID(), 'project_category');
                                    if ($project_categories && !is_wp_error($project_categories)) :
                                        $category = $project_categories[0];
                                    ?>
                                        <span class="text-cyan-300 text-sm"><?php echo esc_html($category->name); ?></span>
                                    <?php endif; ?>
                                    <div class="mt-4">
                                        <a href="<?php the_permalink(); ?>" class="inline-flex items-center bg-cyan-500 hover:bg-cyan-400 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                                            View Details
                                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>

            <div class="text-center mt-12">
                <a href="<?php echo get_post_type_archive_link('project'); ?>" class="btn-primary bg-gray-800 hover:bg-gray-700 text-white px-8 py-3 rounded-lg font-semibold transition-all duration-300 transform hover:scale-105">
                    View All Projects
                </a>
            </div>
        <?php else : ?>
            <div class="text-center py-12">
                <p class="text-gray-600 text-lg">No projects available at the moment.</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php wp_reset_postdata(); ?>
