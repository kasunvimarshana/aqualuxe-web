<?php
/**
 * Template Name: About Page
 *
 * This is the template that displays the about page.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

get_header();
?>

<main id="primary" class="site-main">
    <!-- Hero Section -->
    <section class="page-hero bg-gradient-to-r from-blue-900 to-teal-800 text-white py-20">
        <div class="container mx-auto px-4">
            <div class="max-w-3xl">
                <h1 class="text-4xl md:text-5xl font-bold mb-6">
                    <?php the_title(); ?>
                </h1>
                <?php if (get_field('page_subtitle')) : ?>
                    <p class="text-xl text-blue-100">
                        <?php echo esc_html(get_field('page_subtitle')); ?>
                    </p>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- About Content -->
    <section class="py-16 bg-white dark:bg-gray-900 transition-colors duration-300">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <?php if (has_post_thumbnail()) : ?>
                    <div class="about-image rounded-lg overflow-hidden shadow-xl">
                        <?php the_post_thumbnail('full', array('class' => 'w-full h-auto')); ?>
                    </div>
                <?php endif; ?>
                <div class="about-content">
                    <?php if (get_field('about_heading')) : ?>
                        <h2 class="text-3xl font-bold mb-6 text-gray-900 dark:text-white">
                            <?php echo esc_html(get_field('about_heading')); ?>
                        </h2>
                    <?php endif; ?>
                    <div class="prose dark:prose-invert max-w-none">
                        <?php the_content(); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Our Mission -->
    <section class="py-16 bg-gray-50 dark:bg-gray-800 transition-colors duration-300">
        <div class="container mx-auto px-4">
            <div class="max-w-3xl mx-auto text-center">
                <h2 class="text-3xl font-bold mb-6 text-gray-900 dark:text-white">
                    <?php echo esc_html(get_field('mission_heading', __('Our Mission', 'aqualuxe'))); ?>
                </h2>
                <div class="prose dark:prose-invert max-w-none mx-auto">
                    <?php echo wp_kses_post(get_field('mission_content')); ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Our Values -->
    <section class="py-16 bg-white dark:bg-gray-900 transition-colors duration-300">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-12 text-gray-900 dark:text-white">
                <?php echo esc_html(get_field('values_heading', __('Our Values', 'aqualuxe'))); ?>
            </h2>
            
            <?php if (have_rows('values')) : ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <?php while (have_rows('values')) : the_row(); ?>
                        <div class="value-card bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 transition-transform duration-300 hover:shadow-lg hover:-translate-y-1">
                            <?php if (get_sub_field('icon')) : ?>
                                <div class="value-icon text-primary dark:text-primary-dark mb-4">
                                    <?php echo wp_kses_post(get_sub_field('icon')); ?>
                                </div>
                            <?php endif; ?>
                            <h3 class="text-xl font-bold mb-3 text-gray-900 dark:text-white">
                                <?php echo esc_html(get_sub_field('title')); ?>
                            </h3>
                            <div class="text-gray-700 dark:text-gray-300">
                                <?php echo wp_kses_post(get_sub_field('description')); ?>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else : ?>
                <!-- Default Values -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div class="value-card bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 transition-transform duration-300 hover:shadow-lg hover:-translate-y-1">
                        <div class="value-icon text-primary dark:text-primary-dark mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold mb-3 text-gray-900 dark:text-white">
                            <?php esc_html_e('Excellence', 'aqualuxe'); ?>
                        </h3>
                        <div class="text-gray-700 dark:text-gray-300">
                            <p><?php esc_html_e('We strive for excellence in everything we do, from breeding and caring for our fish to customer service and shipping.', 'aqualuxe'); ?></p>
                        </div>
                    </div>
                    <div class="value-card bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 transition-transform duration-300 hover:shadow-lg hover:-translate-y-1">
                        <div class="value-icon text-primary dark:text-primary-dark mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold mb-3 text-gray-900 dark:text-white">
                            <?php esc_html_e('Passion', 'aqualuxe'); ?>
                        </h3>
                        <div class="text-gray-700 dark:text-gray-300">
                            <p><?php esc_html_e('Our team shares a deep passion for aquatic life and is committed to sharing that passion with collectors and enthusiasts worldwide.', 'aqualuxe'); ?></p>
                        </div>
                    </div>
                    <div class="value-card bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 transition-transform duration-300 hover:shadow-lg hover:-translate-y-1">
                        <div class="value-icon text-primary dark:text-primary-dark mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold mb-3 text-gray-900 dark:text-white">
                            <?php esc_html_e('Sustainability', 'aqualuxe'); ?>
                        </h3>
                        <div class="text-gray-700 dark:text-gray-300">
                            <p><?php esc_html_e('We are committed to sustainable practices that protect natural habitats and ensure the long-term health of aquatic ecosystems.', 'aqualuxe'); ?></p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Our Team -->
    <section class="py-16 bg-gray-50 dark:bg-gray-800 transition-colors duration-300">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-12 text-gray-900 dark:text-white">
                <?php echo esc_html(get_field('team_heading', __('Meet Our Team', 'aqualuxe'))); ?>
            </h2>
            
            <?php if (have_rows('team_members')) : ?>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                    <?php while (have_rows('team_members')) : the_row(); ?>
                        <div class="team-member text-center">
                            <?php if (get_sub_field('photo')) : ?>
                                <div class="team-photo mb-4 rounded-full overflow-hidden mx-auto w-48 h-48">
                                    <img src="<?php echo esc_url(get_sub_field('photo')); ?>" alt="<?php echo esc_attr(get_sub_field('name')); ?>" class="w-full h-full object-cover">
                                </div>
                            <?php else : ?>
                                <div class="team-photo mb-4 rounded-full overflow-hidden mx-auto w-48 h-48 bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                            <?php endif; ?>
                            <h3 class="text-xl font-bold mb-1 text-gray-900 dark:text-white">
                                <?php echo esc_html(get_sub_field('name')); ?>
                            </h3>
                            <p class="text-primary dark:text-primary-dark font-medium mb-2">
                                <?php echo esc_html(get_sub_field('position')); ?>
                            </p>
                            <div class="text-gray-700 dark:text-gray-300 mb-4">
                                <?php echo wp_kses_post(get_sub_field('bio')); ?>
                            </div>
                            <?php if (have_rows('social_links')) : ?>
                                <div class="social-links flex justify-center space-x-3">
                                    <?php while (have_rows('social_links')) : the_row(); ?>
                                        <a href="<?php echo esc_url(get_sub_field('url')); ?>" class="text-gray-600 hover:text-primary dark:text-gray-400 dark:hover:text-primary-dark transition-colors duration-300" target="_blank" rel="noopener noreferrer">
                                            <?php echo wp_kses_post(get_sub_field('icon')); ?>
                                        </a>
                                    <?php endwhile; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else : ?>
                <!-- Default Team Members -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                    <div class="team-member text-center">
                        <div class="team-photo mb-4 rounded-full overflow-hidden mx-auto w-48 h-48 bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold mb-1 text-gray-900 dark:text-white">
                            <?php esc_html_e('John Smith', 'aqualuxe'); ?>
                        </h3>
                        <p class="text-primary dark:text-primary-dark font-medium mb-2">
                            <?php esc_html_e('Founder & CEO', 'aqualuxe'); ?>
                        </p>
                        <div class="text-gray-700 dark:text-gray-300 mb-4">
                            <p><?php esc_html_e('With over 20 years of experience in ornamental fish breeding, John leads our team with passion and expertise.', 'aqualuxe'); ?></p>
                        </div>
                    </div>
                    <div class="team-member text-center">
                        <div class="team-photo mb-4 rounded-full overflow-hidden mx-auto w-48 h-48 bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold mb-1 text-gray-900 dark:text-white">
                            <?php esc_html_e('Sarah Johnson', 'aqualuxe'); ?>
                        </h3>
                        <p class="text-primary dark:text-primary-dark font-medium mb-2">
                            <?php esc_html_e('Head of Breeding', 'aqualuxe'); ?>
                        </p>
                        <div class="text-gray-700 dark:text-gray-300 mb-4">
                            <p><?php esc_html_e('Sarah oversees our breeding programs, ensuring the highest quality and health standards for all our fish.', 'aqualuxe'); ?></p>
                        </div>
                    </div>
                    <div class="team-member text-center">
                        <div class="team-photo mb-4 rounded-full overflow-hidden mx-auto w-48 h-48 bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold mb-1 text-gray-900 dark:text-white">
                            <?php esc_html_e('Michael Chen', 'aqualuxe'); ?>
                        </h3>
                        <p class="text-primary dark:text-primary-dark font-medium mb-2">
                            <?php esc_html_e('Marine Biologist', 'aqualuxe'); ?>
                        </p>
                        <div class="text-gray-700 dark:text-gray-300 mb-4">
                            <p><?php esc_html_e('Michael brings scientific expertise to our team, researching and developing optimal conditions for rare species.', 'aqualuxe'); ?></p>
                        </div>
                    </div>
                    <div class="team-member text-center">
                        <div class="team-photo mb-4 rounded-full overflow-hidden mx-auto w-48 h-48 bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold mb-1 text-gray-900 dark:text-white">
                            <?php esc_html_e('Emily Rodriguez', 'aqualuxe'); ?>
                        </h3>
                        <p class="text-primary dark:text-primary-dark font-medium mb-2">
                            <?php esc_html_e('Customer Experience', 'aqualuxe'); ?>
                        </p>
                        <div class="text-gray-700 dark:text-gray-300 mb-4">
                            <p><?php esc_html_e('Emily ensures that every customer receives exceptional service from initial inquiry through to delivery and beyond.', 'aqualuxe'); ?></p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Our History -->
    <section class="py-16 bg-white dark:bg-gray-900 transition-colors duration-300">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-12 text-gray-900 dark:text-white">
                <?php echo esc_html(get_field('history_heading', __('Our History', 'aqualuxe'))); ?>
            </h2>
            
            <?php if (have_rows('history_timeline')) : ?>
                <div class="max-w-4xl mx-auto">
                    <div class="timeline relative">
                        <?php while (have_rows('history_timeline')) : the_row(); ?>
                            <div class="timeline-item relative pl-10 pb-12 border-l-2 border-primary dark:border-primary-dark">
                                <div class="timeline-marker absolute -left-2 w-5 h-5 rounded-full bg-primary dark:bg-primary-dark"></div>
                                <div class="timeline-content">
                                    <h3 class="text-xl font-bold mb-2 text-gray-900 dark:text-white">
                                        <?php echo esc_html(get_sub_field('year')); ?>
                                    </h3>
                                    <h4 class="text-lg font-medium mb-3 text-primary dark:text-primary-dark">
                                        <?php echo esc_html(get_sub_field('title')); ?>
                                    </h4>
                                    <div class="text-gray-700 dark:text-gray-300">
                                        <?php echo wp_kses_post(get_sub_field('description')); ?>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            <?php else : ?>
                <!-- Default Timeline -->
                <div class="max-w-4xl mx-auto">
                    <div class="timeline relative">
                        <div class="timeline-item relative pl-10 pb-12 border-l-2 border-primary dark:border-primary-dark">
                            <div class="timeline-marker absolute -left-2 w-5 h-5 rounded-full bg-primary dark:bg-primary-dark"></div>
                            <div class="timeline-content">
                                <h3 class="text-xl font-bold mb-2 text-gray-900 dark:text-white">
                                    <?php esc_html_e('2005', 'aqualuxe'); ?>
                                </h3>
                                <h4 class="text-lg font-medium mb-3 text-primary dark:text-primary-dark">
                                    <?php esc_html_e('The Beginning', 'aqualuxe'); ?>
                                </h4>
                                <div class="text-gray-700 dark:text-gray-300">
                                    <p><?php esc_html_e('AquaLuxe was founded with a small collection of rare discus fish and a passion for aquatic life.', 'aqualuxe'); ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="timeline-item relative pl-10 pb-12 border-l-2 border-primary dark:border-primary-dark">
                            <div class="timeline-marker absolute -left-2 w-5 h-5 rounded-full bg-primary dark:bg-primary-dark"></div>
                            <div class="timeline-content">
                                <h3 class="text-xl font-bold mb-2 text-gray-900 dark:text-white">
                                    <?php esc_html_e('2010', 'aqualuxe'); ?>
                                </h3>
                                <h4 class="text-lg font-medium mb-3 text-primary dark:text-primary-dark">
                                    <?php esc_html_e('Expansion', 'aqualuxe'); ?>
                                </h4>
                                <div class="text-gray-700 dark:text-gray-300">
                                    <p><?php esc_html_e('We expanded our facilities to include state-of-the-art breeding tanks and began shipping internationally.', 'aqualuxe'); ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="timeline-item relative pl-10 pb-12 border-l-2 border-primary dark:border-primary-dark">
                            <div class="timeline-marker absolute -left-2 w-5 h-5 rounded-full bg-primary dark:bg-primary-dark"></div>
                            <div class="timeline-content">
                                <h3 class="text-xl font-bold mb-2 text-gray-900 dark:text-white">
                                    <?php esc_html_e('2015', 'aqualuxe'); ?>
                                </h3>
                                <h4 class="text-lg font-medium mb-3 text-primary dark:text-primary-dark">
                                    <?php esc_html_e('Conservation Initiatives', 'aqualuxe'); ?>
                                </h4>
                                <div class="text-gray-700 dark:text-gray-300">
                                    <p><?php esc_html_e('We launched our conservation program, dedicating a portion of profits to protecting natural aquatic habitats.', 'aqualuxe'); ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="timeline-item relative pl-10 border-l-2 border-primary dark:border-primary-dark">
                            <div class="timeline-marker absolute -left-2 w-5 h-5 rounded-full bg-primary dark:bg-primary-dark"></div>
                            <div class="timeline-content">
                                <h3 class="text-xl font-bold mb-2 text-gray-900 dark:text-white">
                                    <?php esc_html_e('2020', 'aqualuxe'); ?>
                                </h3>
                                <h4 class="text-lg font-medium mb-3 text-primary dark:text-primary-dark">
                                    <?php esc_html_e('Global Recognition', 'aqualuxe'); ?>
                                </h4>
                                <div class="text-gray-700 dark:text-gray-300">
                                    <p><?php esc_html_e('AquaLuxe became recognized worldwide as a premier source for rare and exotic ornamental fish species.', 'aqualuxe'); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-16 bg-primary text-white">
        <div class="container mx-auto px-4">
            <div class="max-w-3xl mx-auto text-center">
                <h2 class="text-3xl font-bold mb-6">
                    <?php echo esc_html(get_field('cta_heading', __('Ready to Start Your Collection?', 'aqualuxe'))); ?>
                </h2>
                <p class="text-lg mb-8 text-blue-100">
                    <?php echo esc_html(get_field('cta_text', __('Browse our selection of rare and exotic fish species and start building your dream aquarium today.', 'aqualuxe'))); ?>
                </p>
                <div class="flex flex-wrap justify-center gap-4">
                    <a href="<?php echo esc_url(get_field('cta_primary_button_url', wc_get_page_permalink('shop'))); ?>" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-primary bg-white hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-white transition-colors duration-300">
                        <?php echo esc_html(get_field('cta_primary_button_text', __('Shop Collection', 'aqualuxe'))); ?>
                    </a>
                    <a href="<?php echo esc_url(get_field('cta_secondary_button_url', get_permalink(get_page_by_path('contact')))); ?>" class="inline-flex items-center px-6 py-3 border border-white text-base font-medium rounded-md text-white hover:bg-white hover:text-primary focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-white transition-colors duration-300">
                        <?php echo esc_html(get_field('cta_secondary_button_text', __('Contact Us', 'aqualuxe'))); ?>
                    </a>
                </div>
            </div>
        </div>
    </section>
</main><!-- #main -->

<?php
get_footer();