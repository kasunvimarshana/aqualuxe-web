<?php
/**
 * Template for About page
 *
 * Template Name: About Page
 *
 * @package AquaLuxe
 */

get_header();
?>

<main id="primary" class="site-main">
    
    <?php while (have_posts()) : the_post(); ?>
    
    <!-- Hero Section -->
    <section class="page-hero bg-gradient-to-br from-aqua-50 to-luxe-50 py-20">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto text-center">
                <h1 class="text-4xl lg:text-5xl font-bold text-gray-900 mb-6">
                    <?php the_title(); ?>
                </h1>
                <?php if (has_excerpt()) : ?>
                    <p class="text-xl text-gray-600 leading-relaxed">
                        <?php the_excerpt(); ?>
                    </p>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                <div class="prose prose-lg prose-aqua max-w-none">
                    <?php the_content(); ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Our Story Section -->
    <section class="py-20 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                <div class="story-content" data-animate="fade-in">
                    <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-6">
                        <?php _e('Our Story', 'aqualuxe'); ?>
                    </h2>
                    <p class="text-lg text-gray-600 mb-6 leading-relaxed">
                        <?php _e('Founded with a passion for aquatic excellence, AquaLuxe began as a small local fish store with big dreams. Today, we\'ve grown into a global network of trusted breeders, suppliers, and aquatic experts.', 'aqualuxe'); ?>
                    </p>
                    <p class="text-lg text-gray-600 mb-8 leading-relaxed">
                        <?php _e('Our commitment to quality, sustainability, and customer satisfaction has made us the preferred choice for aquarists around the world. From rare tropical fish to cutting-edge aquarium technology, we bring you the very best the aquatic world has to offer.', 'aqualuxe'); ?>
                    </p>
                    
                    <div class="story-stats grid grid-cols-2 gap-8">
                        <div class="stat-item text-center">
                            <div class="stat-number text-3xl font-bold text-aqua-600 mb-2">15+</div>
                            <div class="stat-label text-gray-600"><?php _e('Years Experience', 'aqualuxe'); ?></div>
                        </div>
                        <div class="stat-item text-center">
                            <div class="stat-number text-3xl font-bold text-aqua-600 mb-2">10k+</div>
                            <div class="stat-label text-gray-600"><?php _e('Happy Customers', 'aqualuxe'); ?></div>
                        </div>
                        <div class="stat-item text-center">
                            <div class="stat-number text-3xl font-bold text-aqua-600 mb-2">50+</div>
                            <div class="stat-label text-gray-600"><?php _e('Countries Served', 'aqualuxe'); ?></div>
                        </div>
                        <div class="stat-item text-center">
                            <div class="stat-number text-3xl font-bold text-aqua-600 mb-2">100+</div>
                            <div class="stat-label text-gray-600"><?php _e('Trusted Partners', 'aqualuxe'); ?></div>
                        </div>
                    </div>
                </div>
                
                <div class="story-image" data-animate="fade-in">
                    <div class="relative">
                        <img src="<?php echo AQUALUXE_THEME_URI . '/assets/images/our-story.jpg'; ?>" 
                             alt="<?php esc_attr_e('Our Story', 'aqualuxe'); ?>"
                             class="rounded-2xl shadow-2xl w-full h-auto"
                             loading="lazy">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Values Section -->
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">
                    <?php _e('Our Values', 'aqualuxe'); ?>
                </h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    <?php _e('These core values guide everything we do and every decision we make.', 'aqualuxe'); ?>
                </p>
            </div>
            
            <div class="grid md:grid-cols-3 gap-8">
                <?php
                $values = array(
                    array(
                        'title' => __('Quality First', 'aqualuxe'),
                        'description' => __('We never compromise on quality. Every fish, plant, and product meets our rigorous standards before reaching our customers.', 'aqualuxe'),
                        'icon' => '⭐',
                    ),
                    array(
                        'title' => __('Sustainability', 'aqualuxe'),
                        'description' => __('We are committed to sustainable practices that protect aquatic ecosystems and promote responsible fishkeeping.', 'aqualuxe'),
                        'icon' => '🌱',
                    ),
                    array(
                        'title' => __('Expert Knowledge', 'aqualuxe'),
                        'description' => __('Our team of aquatic experts provides guidance and support to help you succeed in your aquarium journey.', 'aqualuxe'),
                        'icon' => '🧠',
                    ),
                    array(
                        'title' => __('Global Community', 'aqualuxe'),
                        'description' => __('We connect aquarists worldwide, fostering a community of shared passion and knowledge.', 'aqualuxe'),
                        'icon' => '🌍',
                    ),
                    array(
                        'title' => __('Innovation', 'aqualuxe'),
                        'description' => __('We embrace new technologies and methods to improve the aquatic hobby for everyone.', 'aqualuxe'),
                        'icon' => '💡',
                    ),
                    array(
                        'title' => __('Customer Success', 'aqualuxe'),
                        'description' => __('Your success is our success. We\'re here to support you every step of the way.', 'aqualuxe'),
                        'icon' => '🎯',
                    ),
                );
                
                foreach ($values as $index => $value) :
                ?>
                    <div class="value-card text-center p-8" data-animate="fade-in" style="animation-delay: <?php echo $index * 0.1; ?>s">
                        <div class="value-icon text-5xl mb-4"><?php echo $value['icon']; ?></div>
                        <h3 class="text-xl font-bold text-gray-900 mb-4"><?php echo $value['title']; ?></h3>
                        <p class="text-gray-600 leading-relaxed"><?php echo $value['description']; ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Team Section -->
    <section class="py-20 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">
                    <?php _e('Meet Our Team', 'aqualuxe'); ?>
                </h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    <?php _e('Our passionate team of aquatic experts is here to help you succeed.', 'aqualuxe'); ?>
                </p>
            </div>
            
            <div class="grid md:grid-cols-3 gap-8">
                <?php
                $team_members = array(
                    array(
                        'name' => __('Dr. Marina Torres', 'aqualuxe'),
                        'role' => __('Marine Biologist & Founder', 'aqualuxe'),
                        'bio' => __('Marine biology PhD with 20+ years experience in aquaculture and fish breeding.', 'aqualuxe'),
                        'image' => 'team-marina.jpg',
                    ),
                    array(
                        'name' => __('Alex Chen', 'aqualuxe'),
                        'role' => __('Aquascaping Expert', 'aqualuxe'),
                        'bio' => __('Award-winning aquascaper specializing in nature-style and Dutch aquariums.', 'aqualuxe'),
                        'image' => 'team-alex.jpg',
                    ),
                    array(
                        'name' => __('Sarah Johnson', 'aqualuxe'),
                        'role' => __('Customer Success Manager', 'aqualuxe'),
                        'bio' => __('Dedicated to ensuring every customer has an exceptional experience with AquaLuxe.', 'aqualuxe'),
                        'image' => 'team-sarah.jpg',
                    ),
                );
                
                foreach ($team_members as $index => $member) :
                ?>
                    <div class="team-card bg-white rounded-xl overflow-hidden shadow-lg" data-animate="fade-in" style="animation-delay: <?php echo $index * 0.2; ?>s">
                        <div class="team-image">
                            <img src="<?php echo AQUALUXE_THEME_URI . '/assets/images/' . $member['image']; ?>" 
                                 alt="<?php echo esc_attr($member['name']); ?>"
                                 class="w-full h-64 object-cover"
                                 loading="lazy">
                        </div>
                        <div class="team-content p-6">
                            <h3 class="text-xl font-bold text-gray-900 mb-2"><?php echo $member['name']; ?></h3>
                            <div class="text-aqua-600 font-semibold mb-3"><?php echo $member['role']; ?></div>
                            <p class="text-gray-600 leading-relaxed"><?php echo $member['bio']; ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Certifications & Awards -->
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">
                    <?php _e('Certifications & Awards', 'aqualuxe'); ?>
                </h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    <?php _e('Recognized for excellence in aquaculture and customer service.', 'aqualuxe'); ?>
                </p>
            </div>
            
            <div class="grid md:grid-cols-4 gap-8">
                <?php
                $certifications = array(
                    array(
                        'title' => __('ISO 9001:2015', 'aqualuxe'),
                        'description' => __('Quality Management System', 'aqualuxe'),
                        'year' => '2023',
                    ),
                    array(
                        'title' => __('CITES Permit', 'aqualuxe'),
                        'description' => __('International Trade Certification', 'aqualuxe'),
                        'year' => '2023',
                    ),
                    array(
                        'title' => __('Best Retailer Award', 'aqualuxe'),
                        'description' => __('Aquatic Industry Excellence', 'aqualuxe'),
                        'year' => '2022',
                    ),
                    array(
                        'title' => __('Sustainability Leader', 'aqualuxe'),
                        'description' => __('Environmental Responsibility', 'aqualuxe'),
                        'year' => '2022',
                    ),
                );
                
                foreach ($certifications as $index => $cert) :
                ?>
                    <div class="certification-card text-center p-6 border-2 border-gray-200 rounded-xl hover:border-aqua-300 transition-colors" 
                         data-animate="fade-in" style="animation-delay: <?php echo $index * 0.1; ?>s">
                        <div class="cert-year text-2xl font-bold text-aqua-600 mb-2"><?php echo $cert['year']; ?></div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2"><?php echo $cert['title']; ?></h3>
                        <p class="text-gray-600 text-sm"><?php echo $cert['description']; ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Contact CTA -->
    <section class="py-20 bg-gradient-to-r from-aqua-600 to-luxe-600 text-white">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto text-center">
                <h2 class="text-3xl lg:text-4xl font-bold mb-6">
                    <?php _e('Ready to Start Your Aquatic Journey?', 'aqualuxe'); ?>
                </h2>
                <p class="text-xl mb-8 opacity-90">
                    <?php _e('Get in touch with our experts for personalized advice and recommendations.', 'aqualuxe'); ?>
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="<?php echo home_url('/contact/'); ?>" 
                       class="btn bg-white text-aqua-600 px-8 py-4 rounded-lg font-semibold hover:bg-gray-100 transition-colors">
                        <?php _e('Contact Us', 'aqualuxe'); ?>
                    </a>
                    <?php if (aqualuxe_is_woocommerce_active()) : ?>
                        <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" 
                           class="btn border-2 border-white text-white px-8 py-4 rounded-lg font-semibold hover:bg-white hover:text-aqua-600 transition-all">
                            <?php _e('Explore Products', 'aqualuxe'); ?>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <?php endwhile; ?>

</main>

<?php
get_footer();
?>