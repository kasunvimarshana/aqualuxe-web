<?php
/**
 * Template for Services page
 *
 * Template Name: Services Page
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
                <p class="text-xl text-gray-600 leading-relaxed mb-8">
                    <?php _e('Professional aquarium services from design to maintenance. Transform your space with expert aquatic solutions.', 'aqualuxe'); ?>
                </p>
                <a href="#contact-form" class="btn btn-primary bg-aqua-600 hover:bg-aqua-700 text-white px-8 py-4 rounded-lg font-semibold transition-colors">
                    <?php _e('Get Free Consultation', 'aqualuxe'); ?>
                </a>
            </div>
        </div>
    </section>

    <!-- Services Overview -->
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <div class="grid lg:grid-cols-3 gap-8">
                <?php
                $services = array(
                    array(
                        'title' => __('Custom Aquarium Design', 'aqualuxe'),
                        'description' => __('Bespoke aquarium designs tailored to your space, style, and aquatic preferences.', 'aqualuxe'),
                        'features' => array(
                            __('3D visualization and planning', 'aqualuxe'),
                            __('Space optimization consultation', 'aqualuxe'),
                            __('Equipment specification', 'aqualuxe'),
                            __('Aquascaping design', 'aqualuxe'),
                        ),
                        'icon' => '🎨',
                        'price_from' => '$500',
                    ),
                    array(
                        'title' => __('Professional Installation', 'aqualuxe'),
                        'description' => __('Expert installation ensuring your aquarium is set up perfectly from day one.', 'aqualuxe'),
                        'features' => array(
                            __('Complete system setup', 'aqualuxe'),
                            __('Water chemistry optimization', 'aqualuxe'),
                            __('Equipment calibration', 'aqualuxe'),
                            __('Initial stocking consultation', 'aqualuxe'),
                        ),
                        'icon' => '🔧',
                        'price_from' => '$300',
                    ),
                    array(
                        'title' => __('Maintenance Services', 'aqualuxe'),
                        'description' => __('Regular maintenance to keep your aquarium healthy, beautiful, and thriving.', 'aqualuxe'),
                        'features' => array(
                            __('Water testing and changes', 'aqualuxe'),
                            __('Equipment cleaning and checks', 'aqualuxe'),
                            __('Algae control and plant care', 'aqualuxe'),
                            __('Fish health monitoring', 'aqualuxe'),
                        ),
                        'icon' => '🧽',
                        'price_from' => '$80/month',
                    ),
                    array(
                        'title' => __('Quarantine Services', 'aqualuxe'),
                        'description' => __('Professional quarantine facilities to ensure your new fish are healthy.', 'aqualuxe'),
                        'features' => array(
                            __('30-day quarantine protocol', 'aqualuxe'),
                            __('Health monitoring and treatment', 'aqualuxe'),
                            __('Veterinary consultation available', 'aqualuxe'),
                            __('Guarantee of fish health', 'aqualuxe'),
                        ),
                        'icon' => '🏥',
                        'price_from' => '$25/fish',
                    ),
                    array(
                        'title' => __('Breeding Consultation', 'aqualuxe'),
                        'description' => __('Expert guidance for successful fish breeding programs and genetics.', 'aqualuxe'),
                        'features' => array(
                            __('Species-specific breeding advice', 'aqualuxe'),
                            __('Genetic planning consultation', 'aqualuxe'),
                            __('Breeding setup design', 'aqualuxe'),
                            __('Fry care protocols', 'aqualuxe'),
                        ),
                        'icon' => '🐠',
                        'price_from' => '$150/session',
                    ),
                    array(
                        'title' => __('Emergency Support', 'aqualuxe'),
                        'description' => __('24/7 emergency support for critical aquarium and fish health issues.', 'aqualuxe'),
                        'features' => array(
                            __('Emergency response available', 'aqualuxe'),
                            __('Remote troubleshooting', 'aqualuxe'),
                            __('On-site emergency visits', 'aqualuxe'),
                            __('Crisis management protocols', 'aqualuxe'),
                        ),
                        'icon' => '🚨',
                        'price_from' => '$200/visit',
                    ),
                );
                
                foreach ($services as $index => $service) :
                ?>
                    <div class="service-card bg-gray-50 rounded-xl p-8 hover:shadow-xl transition-all duration-300" 
                         data-animate="fade-in" style="animation-delay: <?php echo $index * 0.1; ?>s">
                        <div class="service-icon text-5xl mb-4"><?php echo $service['icon']; ?></div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-4"><?php echo $service['title']; ?></h3>
                        <p class="text-gray-600 mb-6 leading-relaxed"><?php echo $service['description']; ?></p>
                        
                        <div class="service-features mb-6">
                            <h4 class="font-semibold text-gray-900 mb-3"><?php _e('Includes:', 'aqualuxe'); ?></h4>
                            <ul class="space-y-2">
                                <?php foreach ($service['features'] as $feature) : ?>
                                    <li class="flex items-center text-gray-600">
                                        <svg class="w-5 h-5 text-aqua-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <?php echo $feature; ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        
                        <div class="service-price mb-6">
                            <div class="text-sm text-gray-500"><?php _e('Starting from', 'aqualuxe'); ?></div>
                            <div class="text-2xl font-bold text-aqua-600"><?php echo $service['price_from']; ?></div>
                        </div>
                        
                        <a href="#contact-form" class="btn btn-outline w-full border-2 border-aqua-600 text-aqua-600 py-3 px-6 rounded-lg font-semibold hover:bg-aqua-600 hover:text-white transition-colors">
                            <?php _e('Request Quote', 'aqualuxe'); ?>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Process Section -->
    <section class="py-20 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">
                    <?php _e('Our Service Process', 'aqualuxe'); ?>
                </h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    <?php _e('From initial consultation to ongoing support, we guide you through every step.', 'aqualuxe'); ?>
                </p>
            </div>
            
            <div class="grid md:grid-cols-4 gap-8">
                <?php
                $process_steps = array(
                    array(
                        'step' => '1',
                        'title' => __('Consultation', 'aqualuxe'),
                        'description' => __('Free consultation to understand your needs, space, and vision.', 'aqualuxe'),
                    ),
                    array(
                        'step' => '2',
                        'title' => __('Design & Quote', 'aqualuxe'),
                        'description' => __('Detailed design proposal with transparent pricing and timeline.', 'aqualuxe'),
                    ),
                    array(
                        'step' => '3',
                        'title' => __('Implementation', 'aqualuxe'),
                        'description' => __('Professional installation and setup by our certified technicians.', 'aqualuxe'),
                    ),
                    array(
                        'step' => '4',
                        'title' => __('Ongoing Support', 'aqualuxe'),
                        'description' => __('Continuous maintenance and support to ensure long-term success.', 'aqualuxe'),
                    ),
                );
                
                foreach ($process_steps as $index => $step) :
                ?>
                    <div class="process-step text-center" data-animate="fade-in" style="animation-delay: <?php echo $index * 0.2; ?>s">
                        <div class="step-number bg-aqua-600 text-white w-16 h-16 rounded-full flex items-center justify-center text-2xl font-bold mx-auto mb-4">
                            <?php echo $step['step']; ?>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3"><?php echo $step['title']; ?></h3>
                        <p class="text-gray-600 leading-relaxed"><?php echo $step['description']; ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Portfolio Section -->
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">
                    <?php _e('Our Work', 'aqualuxe'); ?>
                </h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    <?php _e('Explore some of our recent aquarium projects and transformations.', 'aqualuxe'); ?>
                </p>
            </div>
            
            <div class="grid md:grid-cols-3 gap-8">
                <?php
                $portfolio_items = array(
                    array(
                        'title' => __('Corporate Lobby Aquarium', 'aqualuxe'),
                        'location' => __('Downtown Office Building', 'aqualuxe'),
                        'description' => __('500-gallon reef display with automated feeding and lighting systems.', 'aqualuxe'),
                        'image' => 'portfolio-corporate.jpg',
                    ),
                    array(
                        'title' => __('Residential Living Wall', 'aqualuxe'),
                        'location' => __('Luxury Home', 'aqualuxe'),
                        'description' => __('Custom-built aquatic living wall featuring rare tropical plants.', 'aqualuxe'),
                        'image' => 'portfolio-residential.jpg',
                    ),
                    array(
                        'title' => __('Restaurant Feature Tank', 'aqualuxe'),
                        'location' => __('Fine Dining Restaurant', 'aqualuxe'),
                        'description' => __('Eye-catching centerpiece aquarium with lighting synchronized to dining ambiance.', 'aqualuxe'),
                        'image' => 'portfolio-restaurant.jpg',
                    ),
                );
                
                foreach ($portfolio_items as $index => $item) :
                ?>
                    <div class="portfolio-item bg-gray-50 rounded-xl overflow-hidden shadow-lg hover:shadow-xl transition-all duration-300" 
                         data-animate="fade-in" style="animation-delay: <?php echo $index * 0.2; ?>s">
                        <div class="portfolio-image">
                            <img src="<?php echo AQUALUXE_THEME_URI . '/assets/images/' . $item['image']; ?>" 
                                 alt="<?php echo esc_attr($item['title']); ?>"
                                 class="w-full h-64 object-cover"
                                 loading="lazy">
                        </div>
                        <div class="portfolio-content p-6">
                            <h3 class="text-xl font-bold text-gray-900 mb-2"><?php echo $item['title']; ?></h3>
                            <div class="text-aqua-600 font-semibold mb-3"><?php echo $item['location']; ?></div>
                            <p class="text-gray-600 leading-relaxed"><?php echo $item['description']; ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Service Areas -->
    <section class="py-20 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">
                    <?php _e('Service Areas', 'aqualuxe'); ?>
                </h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    <?php _e('We provide services across multiple regions with local and international reach.', 'aqualuxe'); ?>
                </p>
            </div>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                <?php
                $service_areas = array(
                    array(
                        'region' => __('North America', 'aqualuxe'),
                        'countries' => __('USA, Canada, Mexico', 'aqualuxe'),
                        'flag' => '🇺🇸',
                    ),
                    array(
                        'region' => __('Europe', 'aqualuxe'),
                        'countries' => __('UK, Germany, France, Spain', 'aqualuxe'),
                        'flag' => '🇪🇺',
                    ),
                    array(
                        'region' => __('Asia Pacific', 'aqualuxe'),
                        'countries' => __('Japan, Singapore, Australia', 'aqualuxe'),
                        'flag' => '🇯🇵',
                    ),
                    array(
                        'region' => __('Middle East', 'aqualuxe'),
                        'countries' => __('UAE, Qatar, Saudi Arabia', 'aqualuxe'),
                        'flag' => '🇦🇪',
                    ),
                );
                
                foreach ($service_areas as $index => $area) :
                ?>
                    <div class="service-area text-center p-6 bg-white rounded-xl shadow-lg" 
                         data-animate="fade-in" style="animation-delay: <?php echo $index * 0.1; ?>s">
                        <div class="area-flag text-4xl mb-3"><?php echo $area['flag']; ?></div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2"><?php echo $area['region']; ?></h3>
                        <p class="text-gray-600 text-sm"><?php echo $area['countries']; ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Contact Form Section -->
    <section id="contact-form" class="py-20 bg-gradient-to-r from-aqua-600 to-luxe-600 text-white">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                <div class="text-center mb-12">
                    <h2 class="text-3xl lg:text-4xl font-bold mb-4">
                        <?php _e('Get Your Free Consultation', 'aqualuxe'); ?>
                    </h2>
                    <p class="text-xl opacity-90">
                        <?php _e('Ready to create your dream aquarium? Let\'s discuss your project.', 'aqualuxe'); ?>
                    </p>
                </div>
                
                <form class="service-inquiry-form bg-white/10 backdrop-blur-sm rounded-2xl p-8">
                    <div class="grid md:grid-cols-2 gap-6 mb-6">
                        <div class="form-group">
                            <label for="inquiry_name" class="block text-white font-semibold mb-2"><?php _e('Full Name *', 'aqualuxe'); ?></label>
                            <input type="text" id="inquiry_name" name="name" required 
                                   class="w-full px-4 py-3 rounded-lg bg-white/20 border border-white/30 text-white placeholder-white/70 focus:outline-none focus:ring-2 focus:ring-white/50">
                        </div>
                        <div class="form-group">
                            <label for="inquiry_email" class="block text-white font-semibold mb-2"><?php _e('Email Address *', 'aqualuxe'); ?></label>
                            <input type="email" id="inquiry_email" name="email" required 
                                   class="w-full px-4 py-3 rounded-lg bg-white/20 border border-white/30 text-white placeholder-white/70 focus:outline-none focus:ring-2 focus:ring-white/50">
                        </div>
                    </div>
                    
                    <div class="grid md:grid-cols-2 gap-6 mb-6">
                        <div class="form-group">
                            <label for="inquiry_phone" class="block text-white font-semibold mb-2"><?php _e('Phone Number', 'aqualuxe'); ?></label>
                            <input type="tel" id="inquiry_phone" name="phone" 
                                   class="w-full px-4 py-3 rounded-lg bg-white/20 border border-white/30 text-white placeholder-white/70 focus:outline-none focus:ring-2 focus:ring-white/50">
                        </div>
                        <div class="form-group">
                            <label for="inquiry_service" class="block text-white font-semibold mb-2"><?php _e('Service Interest *', 'aqualuxe'); ?></label>
                            <select id="inquiry_service" name="service" required 
                                    class="w-full px-4 py-3 rounded-lg bg-white/20 border border-white/30 text-white focus:outline-none focus:ring-2 focus:ring-white/50">
                                <option value=""><?php _e('Select a service', 'aqualuxe'); ?></option>
                                <option value="design"><?php _e('Custom Aquarium Design', 'aqualuxe'); ?></option>
                                <option value="installation"><?php _e('Professional Installation', 'aqualuxe'); ?></option>
                                <option value="maintenance"><?php _e('Maintenance Services', 'aqualuxe'); ?></option>
                                <option value="quarantine"><?php _e('Quarantine Services', 'aqualuxe'); ?></option>
                                <option value="breeding"><?php _e('Breeding Consultation', 'aqualuxe'); ?></option>
                                <option value="emergency"><?php _e('Emergency Support', 'aqualuxe'); ?></option>
                                <option value="other"><?php _e('Other', 'aqualuxe'); ?></option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group mb-6">
                        <label for="inquiry_message" class="block text-white font-semibold mb-2"><?php _e('Project Details *', 'aqualuxe'); ?></label>
                        <textarea id="inquiry_message" name="message" rows="5" required 
                                  placeholder="<?php esc_attr_e('Tell us about your project, space, timeline, and any specific requirements...', 'aqualuxe'); ?>"
                                  class="w-full px-4 py-3 rounded-lg bg-white/20 border border-white/30 text-white placeholder-white/70 focus:outline-none focus:ring-2 focus:ring-white/50"></textarea>
                    </div>
                    
                    <div class="text-center">
                        <button type="submit" class="btn bg-white text-aqua-600 px-8 py-4 rounded-lg font-semibold hover:bg-gray-100 transition-colors">
                            <?php _e('Request Free Consultation', 'aqualuxe'); ?>
                        </button>
                        <p class="text-sm mt-4 opacity-75">
                            <?php _e('We\'ll respond within 24 hours to schedule your consultation.', 'aqualuxe'); ?>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <?php endwhile; ?>

</main>

<?php
get_footer();
?>