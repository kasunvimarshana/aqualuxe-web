<?php
/**
 * Template part for displaying about page content
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;
?>

<div class="about-page-content">
    <!-- Hero Section -->
    <section class="about-hero bg-primary text-white py-16 md:py-24 mb-12">
        <div class="container mx-auto px-4">
            <div class="max-w-3xl mx-auto text-center">
                <h1 class="text-3xl md:text-4xl lg:text-5xl font-serif font-bold mb-6">About AquaLuxe</h1>
                <p class="text-xl md:text-2xl opacity-90 mb-8">Premium Aquarium Solutions for Enthusiasts and Professionals</p>
                <div class="flex justify-center">
                    <div class="w-24 h-1 bg-accent"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Our Story Section -->
    <section class="about-story py-12 md:py-16">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div class="about-story-content">
                    <div class="section-subtitle mb-2">
                        <span class="inline-block px-4 py-1 bg-primary text-white text-sm font-medium rounded-full">
                            Our Story
                        </span>
                    </div>
                    
                    <h2 class="text-2xl md:text-3xl lg:text-4xl font-serif font-bold mb-6">
                        A Passion for Aquatic Excellence Since 2010
                    </h2>
                    
                    <div class="prose dark:prose-invert max-w-none mb-6">
                        <p>AquaLuxe was founded in 2010 by marine biologist Dr. James Mitchell and aquarium design expert Sarah Chen with a shared vision: to create stunning aquatic environments that bring the beauty and tranquility of underwater ecosystems into homes and businesses.</p>
                        
                        <p>What began as a small boutique shop in San Francisco has grown into a global brand known for premium aquarium products, innovative designs, and exceptional customer service. Our journey has been guided by our unwavering commitment to quality, sustainability, and the wellbeing of aquatic life.</p>
                        
                        <p>Today, AquaLuxe serves customers in over 30 countries, providing everything from custom-designed aquarium installations for luxury hotels to specialized equipment for home enthusiasts. Our team of experts continues to push the boundaries of what's possible in aquarium technology and design.</p>
                    </div>
                    
                    <div class="founder-signature flex items-center">
                        <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/signature.png' ); ?>" alt="Founder's Signature" class="h-16 mr-4">
                        <div class="founder-info">
                            <div class="founder-name font-medium">Dr. James Mitchell</div>
                            <div class="founder-title text-sm text-gray-600 dark:text-gray-400">Founder & CEO</div>
                        </div>
                    </div>
                </div>
                
                <div class="about-story-image">
                    <div class="relative">
                        <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/about-story.jpg' ); ?>" alt="AquaLuxe Story" class="rounded-lg shadow-lg w-full">
                        <div class="absolute -bottom-6 -right-6 bg-white dark:bg-dark-light p-6 rounded-lg shadow-lg">
                            <div class="text-4xl font-bold text-primary mb-1">15+</div>
                            <div class="text-sm">Years of Excellence</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Mission & Values Section -->
    <section class="about-mission-values py-12 md:py-16 bg-light-dark dark:bg-dark-light">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <div class="section-subtitle mb-2">
                    <span class="inline-block px-4 py-1 bg-primary text-white text-sm font-medium rounded-full">
                        Our Mission & Values
                    </span>
                </div>
                
                <h2 class="text-2xl md:text-3xl lg:text-4xl font-serif font-bold mb-6">
                    What Drives Us Forward
                </h2>
                
                <p class="max-w-3xl mx-auto text-gray-600 dark:text-gray-400">
                    At AquaLuxe, we're guided by a set of core principles that inform everything we do, from product development to customer service.
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Mission -->
                <div class="mission-values-card bg-white dark:bg-dark-medium rounded-lg shadow-soft p-6 transition-transform duration-300 hover:-translate-y-1">
                    <div class="card-icon text-4xl text-primary mb-4">
                        <i class="fas fa-compass"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Our Mission</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-4">
                        To create exceptional aquatic environments that inspire wonder and connection with the natural world, while promoting sustainable practices and the wellbeing of aquatic life.
                    </p>
                </div>
                
                <!-- Vision -->
                <div class="mission-values-card bg-white dark:bg-dark-medium rounded-lg shadow-soft p-6 transition-transform duration-300 hover:-translate-y-1">
                    <div class="card-icon text-4xl text-primary mb-4">
                        <i class="fas fa-eye"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Our Vision</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-4">
                        To be the global leader in premium aquarium solutions, recognized for innovation, quality, and commitment to environmental stewardship in every aspect of our business.
                    </p>
                </div>
                
                <!-- Values -->
                <div class="mission-values-card bg-white dark:bg-dark-medium rounded-lg shadow-soft p-6 transition-transform duration-300 hover:-translate-y-1">
                    <div class="card-icon text-4xl text-primary mb-4">
                        <i class="fas fa-heart"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Our Values</h3>
                    <ul class="text-gray-600 dark:text-gray-400 space-y-2">
                        <li class="flex items-center">
                            <i class="fas fa-check-circle text-primary mr-2"></i>
                            <span>Excellence in everything we do</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check-circle text-primary mr-2"></i>
                            <span>Sustainability and environmental responsibility</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check-circle text-primary mr-2"></i>
                            <span>Innovation and continuous improvement</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check-circle text-primary mr-2"></i>
                            <span>Integrity and transparency</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Team Section -->
    <section class="about-team py-12 md:py-16">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <div class="section-subtitle mb-2">
                    <span class="inline-block px-4 py-1 bg-primary text-white text-sm font-medium rounded-full">
                        Our Team
                    </span>
                </div>
                
                <h2 class="text-2xl md:text-3xl lg:text-4xl font-serif font-bold mb-6">
                    Meet the Experts Behind AquaLuxe
                </h2>
                
                <p class="max-w-3xl mx-auto text-gray-600 dark:text-gray-400">
                    Our diverse team of marine biologists, designers, engineers, and customer service specialists work together to deliver exceptional products and experiences.
                </p>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Team Member 1 -->
                <div class="team-member bg-white dark:bg-dark-medium rounded-lg shadow-soft overflow-hidden transition-transform duration-300 hover:-translate-y-1">
                    <div class="team-member-image aspect-w-1 aspect-h-1">
                        <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/team-1.jpg' ); ?>" alt="Dr. James Mitchell" class="w-full h-full object-cover">
                    </div>
                    <div class="team-member-info p-4 text-center">
                        <h3 class="text-lg font-bold mb-1">Dr. James Mitchell</h3>
                        <div class="team-member-position text-sm text-primary mb-3">Founder & CEO</div>
                        <div class="team-member-social flex justify-center space-x-3">
                            <a href="#" class="text-gray-400 hover:text-primary transition-colors"><i class="fab fa-linkedin-in"></i></a>
                            <a href="#" class="text-gray-400 hover:text-primary transition-colors"><i class="fab fa-twitter"></i></a>
                        </div>
                    </div>
                </div>
                
                <!-- Team Member 2 -->
                <div class="team-member bg-white dark:bg-dark-medium rounded-lg shadow-soft overflow-hidden transition-transform duration-300 hover:-translate-y-1">
                    <div class="team-member-image aspect-w-1 aspect-h-1">
                        <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/team-2.jpg' ); ?>" alt="Sarah Chen" class="w-full h-full object-cover">
                    </div>
                    <div class="team-member-info p-4 text-center">
                        <h3 class="text-lg font-bold mb-1">Sarah Chen</h3>
                        <div class="team-member-position text-sm text-primary mb-3">Co-Founder & Design Director</div>
                        <div class="team-member-social flex justify-center space-x-3">
                            <a href="#" class="text-gray-400 hover:text-primary transition-colors"><i class="fab fa-linkedin-in"></i></a>
                            <a href="#" class="text-gray-400 hover:text-primary transition-colors"><i class="fab fa-instagram"></i></a>
                        </div>
                    </div>
                </div>
                
                <!-- Team Member 3 -->
                <div class="team-member bg-white dark:bg-dark-medium rounded-lg shadow-soft overflow-hidden transition-transform duration-300 hover:-translate-y-1">
                    <div class="team-member-image aspect-w-1 aspect-h-1">
                        <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/team-3.jpg' ); ?>" alt="Dr. Michael Rodriguez" class="w-full h-full object-cover">
                    </div>
                    <div class="team-member-info p-4 text-center">
                        <h3 class="text-lg font-bold mb-1">Dr. Michael Rodriguez</h3>
                        <div class="team-member-position text-sm text-primary mb-3">Head Marine Biologist</div>
                        <div class="team-member-social flex justify-center space-x-3">
                            <a href="#" class="text-gray-400 hover:text-primary transition-colors"><i class="fab fa-linkedin-in"></i></a>
                            <a href="#" class="text-gray-400 hover:text-primary transition-colors"><i class="fab fa-twitter"></i></a>
                        </div>
                    </div>
                </div>
                
                <!-- Team Member 4 -->
                <div class="team-member bg-white dark:bg-dark-medium rounded-lg shadow-soft overflow-hidden transition-transform duration-300 hover:-translate-y-1">
                    <div class="team-member-image aspect-w-1 aspect-h-1">
                        <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/team-4.jpg' ); ?>" alt="Emma Thompson" class="w-full h-full object-cover">
                    </div>
                    <div class="team-member-info p-4 text-center">
                        <h3 class="text-lg font-bold mb-1">Emma Thompson</h3>
                        <div class="team-member-position text-sm text-primary mb-3">Product Development Manager</div>
                        <div class="team-member-social flex justify-center space-x-3">
                            <a href="#" class="text-gray-400 hover:text-primary transition-colors"><i class="fab fa-linkedin-in"></i></a>
                            <a href="#" class="text-gray-400 hover:text-primary transition-colors"><i class="fab fa-instagram"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Achievements Section -->
    <section class="about-achievements py-12 md:py-16 bg-primary text-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-2xl md:text-3xl lg:text-4xl font-serif font-bold mb-6">
                    Our Achievements
                </h2>
                
                <p class="max-w-3xl mx-auto opacity-90">
                    We're proud of our accomplishments and the recognition we've received for our commitment to excellence.
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Achievement 1 -->
                <div class="achievement-card text-center">
                    <div class="achievement-icon text-5xl text-accent mb-4">
                        <i class="fas fa-trophy"></i>
                    </div>
                    <div class="achievement-number text-4xl font-bold mb-2">30+</div>
                    <div class="achievement-title text-lg font-medium mb-1">Countries Served</div>
                    <div class="achievement-description opacity-80">Global presence with satisfied customers worldwide</div>
                </div>
                
                <!-- Achievement 2 -->
                <div class="achievement-card text-center">
                    <div class="achievement-icon text-5xl text-accent mb-4">
                        <i class="fas fa-award"></i>
                    </div>
                    <div class="achievement-number text-4xl font-bold mb-2">15</div>
                    <div class="achievement-title text-lg font-medium mb-1">Industry Awards</div>
                    <div class="achievement-description opacity-80">Recognition for design and innovation excellence</div>
                </div>
                
                <!-- Achievement 3 -->
                <div class="achievement-card text-center">
                    <div class="achievement-icon text-5xl text-accent mb-4">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="achievement-number text-4xl font-bold mb-2">50,000+</div>
                    <div class="achievement-title text-lg font-medium mb-1">Happy Customers</div>
                    <div class="achievement-description opacity-80">Building aquatic dreams for enthusiasts worldwide</div>
                </div>
                
                <!-- Achievement 4 -->
                <div class="achievement-card text-center">
                    <div class="achievement-icon text-5xl text-accent mb-4">
                        <i class="fas fa-project-diagram"></i>
                    </div>
                    <div class="achievement-number text-4xl font-bold mb-2">500+</div>
                    <div class="achievement-title text-lg font-medium mb-1">Custom Projects</div>
                    <div class="achievement-description opacity-80">Unique installations for homes and businesses</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Sustainability Section -->
    <section class="about-sustainability py-12 md:py-16">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div class="about-sustainability-image">
                    <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/sustainability.jpg' ); ?>" alt="Sustainability at AquaLuxe" class="rounded-lg shadow-lg w-full">
                </div>
                
                <div class="about-sustainability-content">
                    <div class="section-subtitle mb-2">
                        <span class="inline-block px-4 py-1 bg-primary text-white text-sm font-medium rounded-full">
                            Sustainability
                        </span>
                    </div>
                    
                    <h2 class="text-2xl md:text-3xl lg:text-4xl font-serif font-bold mb-6">
                        Our Commitment to the Environment
                    </h2>
                    
                    <div class="prose dark:prose-invert max-w-none mb-6">
                        <p>At AquaLuxe, sustainability isn't just a buzzword—it's a core part of our business philosophy. We recognize our responsibility to protect the natural environments that inspire our work.</p>
                        
                        <p>Our sustainability initiatives include:</p>
                        
                        <ul>
                            <li><strong>Responsible Sourcing:</strong> We carefully select suppliers who share our commitment to ethical and sustainable practices.</li>
                            <li><strong>Energy-Efficient Products:</strong> Our equipment is designed to minimize energy consumption without compromising performance.</li>
                            <li><strong>Packaging Reduction:</strong> We've reduced plastic packaging by 75% since 2018, using recycled and biodegradable materials wherever possible.</li>
                            <li><strong>Conservation Support:</strong> A portion of every sale goes to marine conservation efforts around the world.</li>
                            <li><strong>Education Programs:</strong> We provide resources to help our customers maintain healthy aquatic ecosystems.</li>
                        </ul>
                        
                        <p>By choosing AquaLuxe, you're not just getting premium products—you're supporting a company that cares about the future of our oceans and waterways.</p>
                    </div>
                    
                    <a href="#" class="inline-block px-6 py-3 bg-primary hover:bg-primary-dark text-white rounded-lg font-medium transition-colors">
                        Learn More About Our Initiatives
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="about-testimonials py-12 md:py-16 bg-light-dark dark:bg-dark-light">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <div class="section-subtitle mb-2">
                    <span class="inline-block px-4 py-1 bg-primary text-white text-sm font-medium rounded-full">
                        Testimonials
                    </span>
                </div>
                
                <h2 class="text-2xl md:text-3xl lg:text-4xl font-serif font-bold mb-6">
                    What Our Clients Say
                </h2>
                
                <p class="max-w-3xl mx-auto text-gray-600 dark:text-gray-400">
                    Don't just take our word for it—hear from some of our satisfied customers about their experience with AquaLuxe.
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Testimonial 1 -->
                <div class="testimonial-card bg-white dark:bg-dark-medium rounded-lg shadow-soft p-6 transition-transform duration-300 hover:-translate-y-1">
                    <div class="testimonial-rating text-accent mb-4">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <div class="testimonial-content italic mb-6 text-gray-600 dark:text-gray-400">
                        "AquaLuxe transformed our office lobby with a stunning custom aquarium that has become the centerpiece of our space. The team's expertise and attention to detail were evident throughout the entire process."
                    </div>
                    <div class="testimonial-author flex items-center">
                        <div class="testimonial-author-image w-12 h-12 rounded-full overflow-hidden mr-4">
                            <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/testimonial-1.jpg' ); ?>" alt="Robert Johnson" class="w-full h-full object-cover">
                        </div>
                        <div class="testimonial-author-info">
                            <div class="testimonial-author-name font-medium">Robert Johnson</div>
                            <div class="testimonial-author-title text-sm text-gray-500 dark:text-gray-400">CEO, Horizon Technologies</div>
                        </div>
                    </div>
                </div>
                
                <!-- Testimonial 2 -->
                <div class="testimonial-card bg-white dark:bg-dark-medium rounded-lg shadow-soft p-6 transition-transform duration-300 hover:-translate-y-1">
                    <div class="testimonial-rating text-accent mb-4">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <div class="testimonial-content italic mb-6 text-gray-600 dark:text-gray-400">
                        "As a long-time aquarium enthusiast, I've tried many products, but AquaLuxe's equipment is truly in a class of its own. The quality is exceptional, and their customer service team is always ready to help with expert advice."
                    </div>
                    <div class="testimonial-author flex items-center">
                        <div class="testimonial-author-image w-12 h-12 rounded-full overflow-hidden mr-4">
                            <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/testimonial-2.jpg' ); ?>" alt="Jennifer Lee" class="w-full h-full object-cover">
                        </div>
                        <div class="testimonial-author-info">
                            <div class="testimonial-author-name font-medium">Jennifer Lee</div>
                            <div class="testimonial-author-title text-sm text-gray-500 dark:text-gray-400">Aquarium Hobbyist</div>
                        </div>
                    </div>
                </div>
                
                <!-- Testimonial 3 -->
                <div class="testimonial-card bg-white dark:bg-dark-medium rounded-lg shadow-soft p-6 transition-transform duration-300 hover:-translate-y-1">
                    <div class="testimonial-rating text-accent mb-4">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <div class="testimonial-content italic mb-6 text-gray-600 dark:text-gray-400">
                        "Our luxury hotel chain has worked with AquaLuxe for over five years, installing their custom aquariums in our properties worldwide. The consistent quality and ongoing support have made them our exclusive aquarium partner."
                    </div>
                    <div class="testimonial-author flex items-center">
                        <div class="testimonial-author-image w-12 h-12 rounded-full overflow-hidden mr-4">
                            <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/testimonial-3.jpg' ); ?>" alt="Marcus Williams" class="w-full h-full object-cover">
                        </div>
                        <div class="testimonial-author-info">
                            <div class="testimonial-author-name font-medium">Marcus Williams</div>
                            <div class="testimonial-author-title text-sm text-gray-500 dark:text-gray-400">Design Director, Azure Hotels</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact CTA Section -->
    <section class="about-contact-cta py-12 md:py-16">
        <div class="container mx-auto px-4">
            <div class="bg-primary text-white rounded-lg shadow-lg p-8 md:p-12">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
                    <div class="contact-cta-content">
                        <h2 class="text-2xl md:text-3xl font-serif font-bold mb-4">
                            Ready to Transform Your Space?
                        </h2>
                        
                        <p class="mb-6 opacity-90">
                            Whether you're looking for a custom aquarium installation, premium equipment, or expert advice, our team is here to help you create the perfect aquatic environment.
                        </p>
                        
                        <div class="flex flex-wrap gap-4">
                            <a href="#" class="inline-block px-6 py-3 bg-white text-primary hover:bg-accent hover:text-dark font-medium rounded-lg transition-colors">
                                Contact Us
                            </a>
                            <a href="#" class="inline-block px-6 py-3 bg-transparent border border-white text-white hover:bg-white hover:text-primary font-medium rounded-lg transition-colors">
                                View Our Products
                            </a>
                        </div>
                    </div>
                    
                    <div class="contact-cta-info">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div class="contact-info-item">
                                <div class="contact-info-icon text-accent text-2xl mb-2">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                                <div class="contact-info-title font-medium mb-1">Visit Us</div>
                                <div class="contact-info-content opacity-90">
                                    123 Aquarium Way<br>
                                    San Francisco, CA 94105
                                </div>
                            </div>
                            
                            <div class="contact-info-item">
                                <div class="contact-info-icon text-accent text-2xl mb-2">
                                    <i class="fas fa-phone"></i>
                                </div>
                                <div class="contact-info-title font-medium mb-1">Call Us</div>
                                <div class="contact-info-content opacity-90">
                                    +1 (800) 123-4567<br>
                                    Mon-Fri: 9am-6pm PST
                                </div>
                            </div>
                            
                            <div class="contact-info-item">
                                <div class="contact-info-icon text-accent text-2xl mb-2">
                                    <i class="fas fa-envelope"></i>
                                </div>
                                <div class="contact-info-title font-medium mb-1">Email Us</div>
                                <div class="contact-info-content opacity-90">
                                    info@aqualuxe.com<br>
                                    support@aqualuxe.com
                                </div>
                            </div>
                            
                            <div class="contact-info-item">
                                <div class="contact-info-icon text-accent text-2xl mb-2">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div class="contact-info-title font-medium mb-1">Opening Hours</div>
                                <div class="contact-info-content opacity-90">
                                    Mon-Sat: 10am-8pm<br>
                                    Sunday: 12pm-6pm
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>