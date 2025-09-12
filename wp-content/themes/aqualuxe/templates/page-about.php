<?php
/**
 * Template for displaying About page
 * 
 * Template Name: About Page
 * 
 * @package AquaLuxe
 */

get_header(); ?>

<main id="primary" class="site-main">
    <?php while (have_posts()) : the_post(); ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class('about-page'); ?>>
            
            <!-- Hero Section -->
            <section class="about-hero relative bg-gradient-to-r from-primary-600 to-primary-800 py-20">
                <div class="absolute inset-0 bg-black bg-opacity-20"></div>
                <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                    <h1 class="text-4xl md:text-6xl font-bold text-white mb-6">
                        <?php the_title(); ?>
                    </h1>
                    <p class="text-xl text-blue-100 max-w-3xl mx-auto">
                        Bringing elegance to aquatic life – globally. Premium ornamental fish, aquatic plants, and luxury aquarium solutions.
                    </p>
                </div>
            </section>

            <!-- About Content -->
            <section class="about-content py-16">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="grid lg:grid-cols-2 gap-16 items-center">
                        <div class="about-text">
                            <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">
                                Our Story
                            </h2>
                            <div class="prose prose-lg dark:prose-invert">
                                <?php the_content(); ?>
                            </div>
                        </div>
                        <div class="about-image">
                            <?php if (has_post_thumbnail()) : ?>
                                <?php the_post_thumbnail('aqualuxe-featured', ['class' => 'rounded-lg shadow-xl']); ?>
                            <?php else : ?>
                                <div class="bg-gradient-to-br from-primary-400 to-primary-600 rounded-lg shadow-xl h-96 flex items-center justify-center">
                                    <i class="fas fa-fish text-6xl text-white opacity-50"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Mission & Values -->
            <section class="mission-values bg-gray-50 dark:bg-gray-900 py-16">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center mb-16">
                        <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">
                            Our Mission & Values
                        </h2>
                        <p class="text-lg text-gray-600 dark:text-gray-400 max-w-3xl mx-auto">
                            We are committed to excellence in every aspect of aquatic care and customer service.
                        </p>
                    </div>

                    <div class="grid md:grid-cols-3 gap-8">
                        <div class="mission-card text-center p-8 bg-white dark:bg-gray-800 rounded-lg shadow-lg">
                            <div class="mission-icon w-16 h-16 bg-primary-100 dark:bg-primary-900 rounded-full flex items-center justify-center mx-auto mb-6">
                                <i class="fas fa-leaf text-2xl text-primary-600"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">
                                Sustainability
                            </h3>
                            <p class="text-gray-600 dark:text-gray-400">
                                Committed to environmentally responsible practices and supporting aquatic ecosystem conservation.
                            </p>
                        </div>

                        <div class="mission-card text-center p-8 bg-white dark:bg-gray-800 rounded-lg shadow-lg">
                            <div class="mission-icon w-16 h-16 bg-primary-100 dark:bg-primary-900 rounded-full flex items-center justify-center mx-auto mb-6">
                                <i class="fas fa-award text-2xl text-primary-600"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">
                                Quality
                            </h3>
                            <p class="text-gray-600 dark:text-gray-400">
                                Only the finest specimens from trusted breeders, ensuring healthy and vibrant aquatic life.
                            </p>
                        </div>

                        <div class="mission-card text-center p-8 bg-white dark:bg-gray-800 rounded-lg shadow-lg">
                            <div class="mission-icon w-16 h-16 bg-primary-100 dark:bg-primary-900 rounded-full flex items-center justify-center mx-auto mb-6">
                                <i class="fas fa-users text-2xl text-primary-600"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">
                                Expertise
                            </h3>
                            <p class="text-gray-600 dark:text-gray-400">
                                Years of experience and passionate dedication to helping aquarists create stunning environments.
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Team Section -->
            <?php
            $team_members = new WP_Query([
                'post_type' => 'aqualuxe_team',
                'posts_per_page' => 6,
                'post_status' => 'publish'
            ]);
            
            if ($team_members->have_posts()) : ?>
                <section class="team-section py-16">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div class="text-center mb-16">
                            <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">
                                Meet Our Team
                            </h2>
                            <p class="text-lg text-gray-600 dark:text-gray-400 max-w-3xl mx-auto">
                                Our passionate experts are here to help you create the perfect aquatic environment.
                            </p>
                        </div>

                        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                            <?php while ($team_members->have_posts()) : $team_members->the_post(); ?>
                                <div class="team-member text-center">
                                    <div class="member-photo mb-6">
                                        <?php if (has_post_thumbnail()) : ?>
                                            <?php the_post_thumbnail('aqualuxe-testimonial', ['class' => 'w-32 h-32 rounded-full mx-auto object-cover']); ?>
                                        <?php else : ?>
                                            <div class="w-32 h-32 bg-gray-300 dark:bg-gray-600 rounded-full mx-auto flex items-center justify-center">
                                                <i class="fas fa-user text-3xl text-gray-500"></i>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">
                                        <?php the_title(); ?>
                                    </h3>
                                    <p class="text-primary-600 dark:text-primary-400 mb-4">
                                        <?php echo esc_html(get_post_meta(get_the_ID(), 'position', true)); ?>
                                    </p>
                                    <div class="text-gray-600 dark:text-gray-400">
                                        <?php the_excerpt(); ?>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    </div>
                </section>
            <?php 
            endif;
            wp_reset_postdata();
            ?>

            <!-- CTA Section -->
            <section class="cta-section bg-primary-600 py-16">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                    <h2 class="text-3xl font-bold text-white mb-6">
                        Ready to Start Your Aquatic Journey?
                    </h2>
                    <p class="text-xl text-blue-100 mb-8 max-w-3xl mx-auto">
                        Contact our experts today and discover how we can help you create the perfect aquatic environment.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="<?php echo esc_url(home_url('/contact')); ?>" 
                           class="btn btn-white">
                            Get In Touch
                        </a>
                        <a href="<?php echo esc_url(home_url('/shop')); ?>" 
                           class="btn btn-outline-white">
                            Browse Products
                        </a>
                    </div>
                </div>
            </section>

        </article>
    <?php endwhile; ?>
</main>

<?php get_footer(); ?>