<?php
/**
 * Template part for displaying services page content
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;
?>

<div class="services-page-content">
    <!-- Hero Section -->
    <section class="services-hero bg-primary text-white py-16 md:py-24 mb-12">
        <div class="container mx-auto px-4">
            <div class="max-w-3xl mx-auto text-center">
                <h1 class="text-3xl md:text-4xl lg:text-5xl font-serif font-bold mb-6">Our Services</h1>
                <p class="text-xl md:text-2xl opacity-90 mb-8">Comprehensive Aquarium Solutions for Every Need</p>
                <div class="flex justify-center">
                    <div class="w-24 h-1 bg-accent"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Overview Section -->
    <section class="services-overview py-12 md:py-16">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <div class="section-subtitle mb-2">
                    <span class="inline-block px-4 py-1 bg-primary text-white text-sm font-medium rounded-full">
                        What We Offer
                    </span>
                </div>
                
                <h2 class="text-2xl md:text-3xl lg:text-4xl font-serif font-bold mb-6">
                    Comprehensive Aquarium Services
                </h2>
                
                <p class="max-w-3xl mx-auto text-gray-600 dark:text-gray-400">
                    From custom design and installation to regular maintenance and emergency support, we provide end-to-end solutions for aquarium enthusiasts and businesses.
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Service 1 -->
                <div class="service-card bg-white dark:bg-dark-medium rounded-lg shadow-soft overflow-hidden transition-transform duration-300 hover:-translate-y-1">
                    <div class="service-image aspect-w-16 aspect-h-9">
                        <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/service-design.jpg' ); ?>" alt="Custom Aquarium Design" class="w-full h-full object-cover">
                    </div>
                    <div class="service-content p-6">
                        <div class="service-icon text-3xl text-primary mb-4">
                            <i class="fas fa-pencil-ruler"></i>
                        </div>
                        <h3 class="text-xl font-bold mb-3">Custom Aquarium Design</h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-4">
                            Our expert designers work with you to create a custom aquarium that perfectly complements your space and meets your specific requirements.
                        </p>
                        <a href="#" class="inline-flex items-center text-primary hover:text-primary-dark transition-colors">
                            Learn More
                            <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                </div>
                
                <!-- Service 2 -->
                <div class="service-card bg-white dark:bg-dark-medium rounded-lg shadow-soft overflow-hidden transition-transform duration-300 hover:-translate-y-1">
                    <div class="service-image aspect-w-16 aspect-h-9">
                        <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/service-installation.jpg' ); ?>" alt="Professional Installation" class="w-full h-full object-cover">
                    </div>
                    <div class="service-content p-6">
                        <div class="service-icon text-3xl text-primary mb-4">
                            <i class="fas fa-tools"></i>
                        </div>
                        <h3 class="text-xl font-bold mb-3">Professional Installation</h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-4">
                            Our skilled technicians handle every aspect of the installation process, ensuring your aquarium is set up correctly and safely.
                        </p>
                        <a href="#" class="inline-flex items-center text-primary hover:text-primary-dark transition-colors">
                            Learn More
                            <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                </div>
                
                <!-- Service 3 -->
                <div class="service-card bg-white dark:bg-dark-medium rounded-lg shadow-soft overflow-hidden transition-transform duration-300 hover:-translate-y-1">
                    <div class="service-image aspect-w-16 aspect-h-9">
                        <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/service-maintenance.jpg' ); ?>" alt="Regular Maintenance" class="w-full h-full object-cover">
                    </div>
                    <div class="service-content p-6">
                        <div class="service-icon text-3xl text-primary mb-4">
                            <i class="fas fa-hand-holding-water"></i>
                        </div>
                        <h3 class="text-xl font-bold mb-3">Regular Maintenance</h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-4">
                            Keep your aquarium looking its best with our comprehensive maintenance services, including water testing, cleaning, and equipment checks.
                        </p>
                        <a href="#" class="inline-flex items-center text-primary hover:text-primary-dark transition-colors">
                            Learn More
                            <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                </div>
                
                <!-- Service 4 -->
                <div class="service-card bg-white dark:bg-dark-medium rounded-lg shadow-soft overflow-hidden transition-transform duration-300 hover:-translate-y-1">
                    <div class="service-image aspect-w-16 aspect-h-9">
                        <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/service-aquascaping.jpg' ); ?>" alt="Aquascaping" class="w-full h-full object-cover">
                    </div>
                    <div class="service-content p-6">
                        <div class="service-icon text-3xl text-primary mb-4">
                            <i class="fas fa-mountain"></i>
                        </div>
                        <h3 class="text-xl font-bold mb-3">Aquascaping</h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-4">
                            Transform your aquarium into a living work of art with our professional aquascaping services, creating stunning underwater landscapes.
                        </p>
                        <a href="#" class="inline-flex items-center text-primary hover:text-primary-dark transition-colors">
                            Learn More
                            <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                </div>
                
                <!-- Service 5 -->
                <div class="service-card bg-white dark:bg-dark-medium rounded-lg shadow-soft overflow-hidden transition-transform duration-300 hover:-translate-y-1">
                    <div class="service-image aspect-w-16 aspect-h-9">
                        <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/service-consultation.jpg' ); ?>" alt="Expert Consultation" class="w-full h-full object-cover">
                    </div>
                    <div class="service-content p-6">
                        <div class="service-icon text-3xl text-primary mb-4">
                            <i class="fas fa-comments"></i>
                        </div>
                        <h3 class="text-xl font-bold mb-3">Expert Consultation</h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-4">
                            Get personalized advice from our marine biologists and aquarium specialists to help you make informed decisions about your aquatic setup.
                        </p>
                        <a href="#" class="inline-flex items-center text-primary hover:text-primary-dark transition-colors">
                            Learn More
                            <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                </div>
                
                <!-- Service 6 -->
                <div class="service-card bg-white dark:bg-dark-medium rounded-lg shadow-soft overflow-hidden transition-transform duration-300 hover:-translate-y-1">
                    <div class="service-image aspect-w-16 aspect-h-9">
                        <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/service-emergency.jpg' ); ?>" alt="Emergency Support" class="w-full h-full object-cover">
                    </div>
                    <div class="service-content p-6">
                        <div class="service-icon text-3xl text-primary mb-4">
                            <i class="fas fa-ambulance"></i>
                        </div>
                        <h3 class="text-xl font-bold mb-3">Emergency Support</h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-4">
                            Our 24/7 emergency support team is ready to respond quickly to any aquarium issues to protect your valuable aquatic life.
                        </p>
                        <a href="#" class="inline-flex items-center text-primary hover:text-primary-dark transition-colors">
                            Learn More
                            <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Service Section -->
    <section class="featured-service py-12 md:py-16 bg-light-dark dark:bg-dark-light">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div class="featured-service-content">
                    <div class="section-subtitle mb-2">
                        <span class="inline-block px-4 py-1 bg-primary text-white text-sm font-medium rounded-full">
                            Featured Service
                        </span>
                    </div>
                    
                    <h2 class="text-2xl md:text-3xl lg:text-4xl font-serif font-bold mb-6">
                        Custom Aquarium Design & Installation
                    </h2>
                    
                    <div class="prose dark:prose-invert max-w-none mb-6">
                        <p>Our flagship service combines expert design with flawless installation to create a stunning aquatic showcase tailored to your space and preferences.</p>
                        
                        <p>The process begins with a thorough consultation to understand your vision, space requirements, and aesthetic preferences. Our designers then create detailed renderings and plans for your approval before our skilled technicians bring the design to life.</p>
                        
                        <h4>What's Included:</h4>
                        <ul>
                            <li><strong>On-site consultation</strong> to assess your space and requirements</li>
                            <li><strong>Custom design</strong> tailored to your preferences and space</li>
                            <li><strong>3D renderings</strong> to visualize the final result</li>
                            <li><strong>Professional installation</strong> by our experienced team</li>
                            <li><strong>Complete setup</strong> including water conditioning and initial stocking</li>
                            <li><strong>Comprehensive training</strong> on system operation and maintenance</li>
                            <li><strong>30-day follow-up</strong> to ensure everything is functioning perfectly</li>
                        </ul>
                    </div>
                    
                    <div class="flex flex-wrap gap-4">
                        <a href="#" class="inline-block px-6 py-3 bg-primary hover:bg-primary-dark text-white rounded-lg font-medium transition-colors">
                            Request a Consultation
                        </a>
                        <a href="#" class="inline-block px-6 py-3 bg-transparent border border-primary text-primary hover:bg-primary hover:text-white font-medium rounded-lg transition-colors">
                            View Portfolio
                        </a>
                    </div>
                </div>
                
                <div class="featured-service-gallery grid grid-cols-2 gap-4">
                    <div class="gallery-item">
                        <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/custom-aquarium-1.jpg' ); ?>" alt="Custom Aquarium Design" class="rounded-lg shadow-md w-full">
                    </div>
                    <div class="gallery-item">
                        <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/custom-aquarium-2.jpg' ); ?>" alt="Custom Aquarium Design" class="rounded-lg shadow-md w-full">
                    </div>
                    <div class="gallery-item">
                        <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/custom-aquarium-3.jpg' ); ?>" alt="Custom Aquarium Design" class="rounded-lg shadow-md w-full">
                    </div>
                    <div class="gallery-item">
                        <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/custom-aquarium-4.jpg' ); ?>" alt="Custom Aquarium Design" class="rounded-lg shadow-md w-full">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Service Process Section -->
    <section class="service-process py-12 md:py-16">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <div class="section-subtitle mb-2">
                    <span class="inline-block px-4 py-1 bg-primary text-white text-sm font-medium rounded-full">
                        Our Process
                    </span>
                </div>
                
                <h2 class="text-2xl md:text-3xl lg:text-4xl font-serif font-bold mb-6">
                    How We Work
                </h2>
                
                <p class="max-w-3xl mx-auto text-gray-600 dark:text-gray-400">
                    Our streamlined process ensures a smooth experience from initial consultation to final installation and beyond.
                </p>
            </div>
            
            <div class="process-steps">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    <!-- Step 1 -->
                    <div class="process-step text-center">
                        <div class="process-step-number relative mx-auto w-16 h-16 rounded-full bg-primary text-white text-xl font-bold flex items-center justify-center mb-6">
                            1
                            <div class="absolute -right-8 top-1/2 transform -translate-y-1/2 w-8 h-1 bg-primary hidden lg:block"></div>
                        </div>
                        <h3 class="text-xl font-bold mb-3">Consultation</h3>
                        <p class="text-gray-600 dark:text-gray-400">
                            We begin with a thorough consultation to understand your vision, space, and requirements.
                        </p>
                    </div>
                    
                    <!-- Step 2 -->
                    <div class="process-step text-center">
                        <div class="process-step-number relative mx-auto w-16 h-16 rounded-full bg-primary text-white text-xl font-bold flex items-center justify-center mb-6">
                            2
                            <div class="absolute -right-8 top-1/2 transform -translate-y-1/2 w-8 h-1 bg-primary hidden lg:block"></div>
                        </div>
                        <h3 class="text-xl font-bold mb-3">Design & Planning</h3>
                        <p class="text-gray-600 dark:text-gray-400">
                            Our designers create detailed plans and 3D renderings for your approval.
                        </p>
                    </div>
                    
                    <!-- Step 3 -->
                    <div class="process-step text-center">
                        <div class="process-step-number relative mx-auto w-16 h-16 rounded-full bg-primary text-white text-xl font-bold flex items-center justify-center mb-6">
                            3
                            <div class="absolute -right-8 top-1/2 transform -translate-y-1/2 w-8 h-1 bg-primary hidden lg:block"></div>
                        </div>
                        <h3 class="text-xl font-bold mb-3">Installation</h3>
                        <p class="text-gray-600 dark:text-gray-400">
                            Our expert technicians handle the complete installation with minimal disruption.
                        </p>
                    </div>
                    
                    <!-- Step 4 -->
                    <div class="process-step text-center">
                        <div class="process-step-number mx-auto w-16 h-16 rounded-full bg-primary text-white text-xl font-bold flex items-center justify-center mb-6">
                            4
                        </div>
                        <h3 class="text-xl font-bold mb-3">Ongoing Support</h3>
                        <p class="text-gray-600 dark:text-gray-400">
                            We provide training, maintenance services, and ongoing support for your aquarium.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Service Packages Section -->
    <section class="service-packages py-12 md:py-16 bg-light-dark dark:bg-dark-light">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <div class="section-subtitle mb-2">
                    <span class="inline-block px-4 py-1 bg-primary text-white text-sm font-medium rounded-full">
                        Service Packages
                    </span>
                </div>
                
                <h2 class="text-2xl md:text-3xl lg:text-4xl font-serif font-bold mb-6">
                    Choose the Right Plan for You
                </h2>
                
                <p class="max-w-3xl mx-auto text-gray-600 dark:text-gray-400">
                    We offer a range of service packages to meet different needs and budgets, from basic maintenance to comprehensive care.
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Package 1 -->
                <div class="package-card bg-white dark:bg-dark-medium rounded-lg shadow-soft overflow-hidden transition-transform duration-300 hover:-translate-y-1">
                    <div class="package-header bg-primary-light dark:bg-primary-dark p-6 text-center">
                        <h3 class="package-name text-xl font-bold text-primary mb-2">Essential Care</h3>
                        <div class="package-price">
                            <span class="text-3xl font-bold">$99</span>
                            <span class="text-gray-600 dark:text-gray-400">/month</span>
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">For small to medium aquariums</p>
                    </div>
                    <div class="package-features p-6">
                        <ul class="space-y-3">
                            <li class="flex items-start">
                                <i class="fas fa-check text-primary mt-1 mr-3"></i>
                                <span>Monthly maintenance visit</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check text-primary mt-1 mr-3"></i>
                                <span>Water quality testing</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check text-primary mt-1 mr-3"></i>
                                <span>Filter cleaning and maintenance</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check text-primary mt-1 mr-3"></i>
                                <span>Glass cleaning</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check text-primary mt-1 mr-3"></i>
                                <span>Basic water changes</span>
                            </li>
                            <li class="flex items-start text-gray-400">
                                <i class="fas fa-times mt-1 mr-3"></i>
                                <span>Aquascaping maintenance</span>
                            </li>
                            <li class="flex items-start text-gray-400">
                                <i class="fas fa-times mt-1 mr-3"></i>
                                <span>Fish health assessment</span>
                            </li>
                            <li class="flex items-start text-gray-400">
                                <i class="fas fa-times mt-1 mr-3"></i>
                                <span>24/7 emergency support</span>
                            </li>
                        </ul>
                        <div class="package-button mt-6">
                            <a href="#" class="block w-full py-3 px-4 bg-primary hover:bg-primary-dark text-white text-center rounded-lg transition-colors">
                                Select Plan
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Package 2 -->
                <div class="package-card bg-white dark:bg-dark-medium rounded-lg shadow-soft overflow-hidden transition-transform duration-300 hover:-translate-y-1 transform scale-105 z-10">
                    <div class="package-header bg-primary p-6 text-center text-white">
                        <div class="package-badge inline-block px-3 py-1 bg-accent text-dark text-xs font-bold rounded-full mb-3">
                            Most Popular
                        </div>
                        <h3 class="package-name text-xl font-bold mb-2">Premium Care</h3>
                        <div class="package-price">
                            <span class="text-3xl font-bold">$199</span>
                            <span class="opacity-80">/month</span>
                        </div>
                        <p class="text-sm opacity-80 mt-2">For medium to large aquariums</p>
                    </div>
                    <div class="package-features p-6">
                        <ul class="space-y-3">
                            <li class="flex items-start">
                                <i class="fas fa-check text-primary mt-1 mr-3"></i>
                                <span>Bi-weekly maintenance visits</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check text-primary mt-1 mr-3"></i>
                                <span>Comprehensive water testing</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check text-primary mt-1 mr-3"></i>
                                <span>Complete system maintenance</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check text-primary mt-1 mr-3"></i>
                                <span>Interior and exterior cleaning</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check text-primary mt-1 mr-3"></i>
                                <span>Regular water changes</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check text-primary mt-1 mr-3"></i>
                                <span>Aquascaping maintenance</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check text-primary mt-1 mr-3"></i>
                                <span>Fish health assessment</span>
                            </li>
                            <li class="flex items-start text-gray-400">
                                <i class="fas fa-times mt-1 mr-3"></i>
                                <span>24/7 emergency support</span>
                            </li>
                        </ul>
                        <div class="package-button mt-6">
                            <a href="#" class="block w-full py-3 px-4 bg-primary hover:bg-primary-dark text-white text-center rounded-lg transition-colors">
                                Select Plan
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Package 3 -->
                <div class="package-card bg-white dark:bg-dark-medium rounded-lg shadow-soft overflow-hidden transition-transform duration-300 hover:-translate-y-1">
                    <div class="package-header bg-primary-light dark:bg-primary-dark p-6 text-center">
                        <h3 class="package-name text-xl font-bold text-primary mb-2">Ultimate Care</h3>
                        <div class="package-price">
                            <span class="text-3xl font-bold">$349</span>
                            <span class="text-gray-600 dark:text-gray-400">/month</span>
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">For large or complex aquariums</p>
                    </div>
                    <div class="package-features p-6">
                        <ul class="space-y-3">
                            <li class="flex items-start">
                                <i class="fas fa-check text-primary mt-1 mr-3"></i>
                                <span>Weekly maintenance visits</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check text-primary mt-1 mr-3"></i>
                                <span>Advanced water parameter testing</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check text-primary mt-1 mr-3"></i>
                                <span>Complete system maintenance</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check text-primary mt-1 mr-3"></i>
                                <span>Deep cleaning of all components</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check text-primary mt-1 mr-3"></i>
                                <span>Scheduled water changes</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check text-primary mt-1 mr-3"></i>
                                <span>Professional aquascaping</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check text-primary mt-1 mr-3"></i>
                                <span>Comprehensive fish health care</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check text-primary mt-1 mr-3"></i>
                                <span>24/7 emergency support</span>
                            </li>
                        </ul>
                        <div class="package-button mt-6">
                            <a href="#" class="block w-full py-3 px-4 bg-primary hover:bg-primary-dark text-white text-center rounded-lg transition-colors">
                                Select Plan
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="custom-package text-center mt-12">
                <p class="mb-4 text-gray-600 dark:text-gray-400">
                    Need a custom solution for your specific requirements?
                </p>
                <a href="#" class="inline-block px-6 py-3 bg-transparent border border-primary text-primary hover:bg-primary hover:text-white font-medium rounded-lg transition-colors">
                    Contact Us for a Custom Quote
                </a>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="services-faq py-12 md:py-16">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <div class="section-subtitle mb-2">
                    <span class="inline-block px-4 py-1 bg-primary text-white text-sm font-medium rounded-full">
                        FAQ
                    </span>
                </div>
                
                <h2 class="text-2xl md:text-3xl lg:text-4xl font-serif font-bold mb-6">
                    Frequently Asked Questions
                </h2>
                
                <p class="max-w-3xl mx-auto text-gray-600 dark:text-gray-400">
                    Find answers to common questions about our services, processes, and aquarium care.
                </p>
            </div>
            
            <div class="max-w-3xl mx-auto">
                <div class="faq-list space-y-4">
                    <!-- FAQ Item 1 -->
                    <div class="faq-item bg-white dark:bg-dark-medium rounded-lg shadow-soft overflow-hidden">
                        <button class="faq-question w-full flex justify-between items-center p-6 text-left font-medium focus:outline-none">
                            <span>How often should my aquarium be serviced?</span>
                            <i class="fas fa-chevron-down text-primary transition-transform"></i>
                        </button>
                        <div class="faq-answer px-6 pb-6">
                            <p class="text-gray-600 dark:text-gray-400">
                                The frequency of aquarium maintenance depends on several factors, including tank size, fish population, and filtration system. Generally, we recommend professional maintenance every 2-4 weeks for optimal results. Our service packages are designed to provide the appropriate level of care for different aquarium setups.
                            </p>
                        </div>
                    </div>
                    
                    <!-- FAQ Item 2 -->
                    <div class="faq-item bg-white dark:bg-dark-medium rounded-lg shadow-soft overflow-hidden">
                        <button class="faq-question w-full flex justify-between items-center p-6 text-left font-medium focus:outline-none">
                            <span>What's included in a standard maintenance visit?</span>
                            <i class="fas fa-chevron-down text-primary transition-transform"></i>
                        </button>
                        <div class="faq-answer px-6 pb-6">
                            <p class="text-gray-600 dark:text-gray-400">
                                A standard maintenance visit includes water testing (pH, ammonia, nitrite, nitrate), glass cleaning, substrate vacuuming, filter maintenance, equipment checks, and a partial water change. We also inspect fish and plants for signs of health issues and make adjustments as needed to ensure optimal water conditions.
                            </p>
                        </div>
                    </div>
                    
                    <!-- FAQ Item 3 -->
                    <div class="faq-item bg-white dark:bg-dark-medium rounded-lg shadow-soft overflow-hidden">
                        <button class="faq-question w-full flex justify-between items-center p-6 text-left font-medium focus:outline-none">
                            <span>How long does it take to design and install a custom aquarium?</span>
                            <i class="fas fa-chevron-down text-primary transition-transform"></i>
                        </button>
                        <div class="faq-answer px-6 pb-6">
                            <p class="text-gray-600 dark:text-gray-400">
                                The timeline for a custom aquarium project varies depending on complexity and size. Typically, the design phase takes 2-3 weeks, while fabrication requires 4-8 weeks. Installation usually takes 1-3 days, followed by a cycling period of 3-4 weeks before adding fish. In total, most custom projects are completed within 2-4 months from initial consultation to fully stocked aquarium.
                            </p>
                        </div>
                    </div>
                    
                    <!-- FAQ Item 4 -->
                    <div class="faq-item bg-white dark:bg-dark-medium rounded-lg shadow-soft overflow-hidden">
                        <button class="faq-question w-full flex justify-between items-center p-6 text-left font-medium focus:outline-none">
                            <span>Do you offer emergency services?</span>
                            <i class="fas fa-chevron-down text-primary transition-transform"></i>
                        </button>
                        <div class="faq-answer px-6 pb-6">
                            <p class="text-gray-600 dark:text-gray-400">
                                Yes, we offer emergency services for critical situations such as equipment failure, water quality issues, or health concerns with aquatic life. Our Ultimate Care package includes 24/7 emergency support, but we also provide emergency assistance to all clients as needed (additional fees may apply for non-subscription clients).
                            </p>
                        </div>
                    </div>
                    
                    <!-- FAQ Item 5 -->
                    <div class="faq-item bg-white dark:bg-dark-medium rounded-lg shadow-soft overflow-hidden">
                        <button class="faq-question w-full flex justify-between items-center p-6 text-left font-medium focus:outline-none">
                            <span>Can you help with selecting fish and plants for my aquarium?</span>
                            <i class="fas fa-chevron-down text-primary transition-transform"></i>
                        </button>
                        <div class="faq-answer px-6 pb-6">
                            <p class="text-gray-600 dark:text-gray-400">
                                Absolutely! Our marine biologists and aquarium specialists can provide expert guidance on selecting compatible fish species and plants that will thrive in your specific aquarium environment. We consider factors such as water parameters, tank size, existing inhabitants, and your aesthetic preferences to create a balanced and beautiful ecosystem.
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="more-questions text-center mt-8">
                    <p class="mb-4 text-gray-600 dark:text-gray-400">
                        Have more questions about our services?
                    </p>
                    <a href="#" class="inline-block px-6 py-3 bg-primary hover:bg-primary-dark text-white font-medium rounded-lg transition-colors">
                        Contact Our Support Team
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="services-cta py-12 md:py-16 bg-primary text-white">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto text-center">
                <h2 class="text-2xl md:text-3xl lg:text-4xl font-serif font-bold mb-6">
                    Ready to Transform Your Aquatic Environment?
                </h2>
                
                <p class="text-lg opacity-90 mb-8">
                    Whether you're looking for a stunning custom installation or professional maintenance for your existing aquarium, our team of experts is here to help.
                </p>
                
                <div class="flex flex-wrap justify-center gap-4">
                    <a href="#" class="inline-block px-8 py-4 bg-white text-primary hover:bg-accent hover:text-dark font-medium rounded-lg transition-colors">
                        Schedule a Consultation
                    </a>
                    <a href="#" class="inline-block px-8 py-4 bg-transparent border border-white text-white hover:bg-white hover:text-primary font-medium rounded-lg transition-colors">
                        View Our Portfolio
                    </a>
                </div>
            </div>
        </div>
    </section>
</div>