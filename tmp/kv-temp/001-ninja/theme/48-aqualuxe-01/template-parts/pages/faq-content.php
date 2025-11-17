<?php
/**
 * Template part for displaying FAQ page content
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;
?>

<div class="faq-page-content">
    <!-- Hero Section -->
    <section class="faq-hero bg-primary text-white py-16 md:py-24 mb-12">
        <div class="container mx-auto px-4">
            <div class="max-w-3xl mx-auto text-center">
                <h1 class="text-3xl md:text-4xl lg:text-5xl font-serif font-bold mb-6">Frequently Asked Questions</h1>
                <p class="text-xl md:text-2xl opacity-90 mb-8">Find Answers to Common Questions About AquaLuxe Products and Services</p>
                <div class="flex justify-center">
                    <div class="w-24 h-1 bg-accent"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Search Section -->
    <section class="faq-search py-8 md:py-12">
        <div class="container mx-auto px-4">
            <div class="max-w-3xl mx-auto">
                <div class="faq-search-form bg-white dark:bg-dark-medium rounded-lg shadow-soft p-4 md:p-6">
                    <form class="flex flex-col md:flex-row gap-4">
                        <input type="text" placeholder="Search for answers..." class="flex-grow px-4 py-3 border border-gray-300 dark:border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary dark:bg-dark-light dark:text-white">
                        <button type="submit" class="px-6 py-3 bg-primary hover:bg-primary-dark text-white rounded-lg font-medium transition-colors">
                            <i class="fas fa-search mr-2"></i> Search
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Categories Section -->
    <section class="faq-categories py-8 md:py-12">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Category 1 -->
                <a href="#general" class="faq-category bg-white dark:bg-dark-medium rounded-lg shadow-soft p-6 text-center transition-transform duration-300 hover:-translate-y-1">
                    <div class="category-icon text-4xl text-primary mb-4">
                        <i class="fas fa-info-circle"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-2">General Questions</h3>
                    <p class="text-gray-600 dark:text-gray-400">
                        Basic information about our company and services
                    </p>
                </a>
                
                <!-- Category 2 -->
                <a href="#products" class="faq-category bg-white dark:bg-dark-medium rounded-lg shadow-soft p-6 text-center transition-transform duration-300 hover:-translate-y-1">
                    <div class="category-icon text-4xl text-primary mb-4">
                        <i class="fas fa-box-open"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-2">Products</h3>
                    <p class="text-gray-600 dark:text-gray-400">
                        Information about our aquarium products and equipment
                    </p>
                </a>
                
                <!-- Category 3 -->
                <a href="#services" class="faq-category bg-white dark:bg-dark-medium rounded-lg shadow-soft p-6 text-center transition-transform duration-300 hover:-translate-y-1">
                    <div class="category-icon text-4xl text-primary mb-4">
                        <i class="fas fa-concierge-bell"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-2">Services</h3>
                    <p class="text-gray-600 dark:text-gray-400">
                        Details about our installation and maintenance services
                    </p>
                </a>
                
                <!-- Category 4 -->
                <a href="#support" class="faq-category bg-white dark:bg-dark-medium rounded-lg shadow-soft p-6 text-center transition-transform duration-300 hover:-translate-y-1">
                    <div class="category-icon text-4xl text-primary mb-4">
                        <i class="fas fa-life-ring"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-2">Support</h3>
                    <p class="text-gray-600 dark:text-gray-400">
                        Help with orders, shipping, and technical support
                    </p>
                </a>
            </div>
        </div>
    </section>

    <!-- General Questions Section -->
    <section id="general" class="faq-section py-12 md:py-16 bg-light-dark dark:bg-dark-light">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <div class="section-subtitle mb-2">
                    <span class="inline-block px-4 py-1 bg-primary text-white text-sm font-medium rounded-full">
                        General Questions
                    </span>
                </div>
                
                <h2 class="text-2xl md:text-3xl lg:text-4xl font-serif font-bold mb-6">
                    About AquaLuxe
                </h2>
                
                <p class="max-w-3xl mx-auto text-gray-600 dark:text-gray-400">
                    Learn more about our company, mission, and approach to aquarium excellence.
                </p>
            </div>
            
            <div class="max-w-3xl mx-auto">
                <div class="faq-list space-y-4">
                    <!-- FAQ Item 1 -->
                    <div class="faq-item bg-white dark:bg-dark-medium rounded-lg shadow-soft overflow-hidden">
                        <button class="faq-question w-full flex justify-between items-center p-6 text-left font-medium focus:outline-none">
                            <span>What is AquaLuxe?</span>
                            <i class="fas fa-chevron-down text-primary transition-transform"></i>
                        </button>
                        <div class="faq-answer px-6 pb-6">
                            <p class="text-gray-600 dark:text-gray-400">
                                AquaLuxe is a premium aquarium company founded in 2010 that specializes in high-quality aquarium products, custom installations, and professional maintenance services. We serve both hobbyists and commercial clients with a focus on creating stunning, healthy aquatic environments.
                            </p>
                        </div>
                    </div>
                    
                    <!-- FAQ Item 2 -->
                    <div class="faq-item bg-white dark:bg-dark-medium rounded-lg shadow-soft overflow-hidden">
                        <button class="faq-question w-full flex justify-between items-center p-6 text-left font-medium focus:outline-none">
                            <span>Where is AquaLuxe located?</span>
                            <i class="fas fa-chevron-down text-primary transition-transform"></i>
                        </button>
                        <div class="faq-answer px-6 pb-6">
                            <p class="text-gray-600 dark:text-gray-400">
                                Our headquarters and main showroom are located in San Francisco, California. We also have satellite offices in New York, Miami, and Chicago. Our products are available worldwide through our online store, and our installation and maintenance services are available in select metropolitan areas across the United States.
                            </p>
                        </div>
                    </div>
                    
                    <!-- FAQ Item 3 -->
                    <div class="faq-item bg-white dark:bg-dark-medium rounded-lg shadow-soft overflow-hidden">
                        <button class="faq-question w-full flex justify-between items-center p-6 text-left font-medium focus:outline-none">
                            <span>What sets AquaLuxe apart from other aquarium companies?</span>
                            <i class="fas fa-chevron-down text-primary transition-transform"></i>
                        </button>
                        <div class="faq-answer px-6 pb-6">
                            <p class="text-gray-600 dark:text-gray-400">
                                AquaLuxe distinguishes itself through our commitment to quality, sustainability, and expert knowledge. Our team includes marine biologists and aquarium specialists who ensure that every product and service meets the highest standards. We focus on creating balanced ecosystems rather than just selling products, and our custom designs are tailored to each client's specific needs and aesthetic preferences.
                            </p>
                        </div>
                    </div>
                    
                    <!-- FAQ Item 4 -->
                    <div class="faq-item bg-white dark:bg-dark-medium rounded-lg shadow-soft overflow-hidden">
                        <button class="faq-question w-full flex justify-between items-center p-6 text-left font-medium focus:outline-none">
                            <span>Do you ship internationally?</span>
                            <i class="fas fa-chevron-down text-primary transition-transform"></i>
                        </button>
                        <div class="faq-answer px-6 pb-6">
                            <p class="text-gray-600 dark:text-gray-400">
                                Yes, we ship most of our products internationally. Shipping costs and delivery times vary depending on the destination country. Some restrictions may apply for certain products due to customs regulations or shipping limitations. Please contact our customer service team for specific information about shipping to your location.
                            </p>
                        </div>
                    </div>
                    
                    <!-- FAQ Item 5 -->
                    <div class="faq-item bg-white dark:bg-dark-medium rounded-lg shadow-soft overflow-hidden">
                        <button class="faq-question w-full flex justify-between items-center p-6 text-left font-medium focus:outline-none">
                            <span>What is your return policy?</span>
                            <i class="fas fa-chevron-down text-primary transition-transform"></i>
                        </button>
                        <div class="faq-answer px-6 pb-6">
                            <p class="text-gray-600 dark:text-gray-400">
                                We offer a 30-day return policy for most unused products in their original packaging. Custom-made items, live animals, and plants are not eligible for return unless they arrive damaged or defective. Please contact our customer service team within 7 days of receiving your order if you need to initiate a return. Shipping costs for returns are the responsibility of the customer unless the return is due to our error.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Products Section -->
    <section id="products" class="faq-section py-12 md:py-16">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <div class="section-subtitle mb-2">
                    <span class="inline-block px-4 py-1 bg-primary text-white text-sm font-medium rounded-full">
                        Products
                    </span>
                </div>
                
                <h2 class="text-2xl md:text-3xl lg:text-4xl font-serif font-bold mb-6">
                    Aquarium Products
                </h2>
                
                <p class="max-w-3xl mx-auto text-gray-600 dark:text-gray-400">
                    Information about our aquarium tanks, equipment, and accessories.
                </p>
            </div>
            
            <div class="max-w-3xl mx-auto">
                <div class="faq-list space-y-4">
                    <!-- FAQ Item 1 -->
                    <div class="faq-item bg-white dark:bg-dark-medium rounded-lg shadow-soft overflow-hidden">
                        <button class="faq-question w-full flex justify-between items-center p-6 text-left font-medium focus:outline-none">
                            <span>What types of aquariums do you offer?</span>
                            <i class="fas fa-chevron-down text-primary transition-transform"></i>
                        </button>
                        <div class="faq-answer px-6 pb-6">
                            <p class="text-gray-600 dark:text-gray-400">
                                We offer a wide range of aquariums, including freshwater, saltwater, reef, planted, and specialty aquariums. Our selection includes standard sizes as well as custom-designed tanks. We provide complete aquarium systems with all necessary equipment, as well as standalone tanks for those who prefer to select their components individually.
                            </p>
                        </div>
                    </div>
                    
                    <!-- FAQ Item 2 -->
                    <div class="faq-item bg-white dark:bg-dark-medium rounded-lg shadow-soft overflow-hidden">
                        <button class="faq-question w-full flex justify-between items-center p-6 text-left font-medium focus:outline-none">
                            <span>What is the warranty on your aquariums?</span>
                            <i class="fas fa-chevron-down text-primary transition-transform"></i>
                        </button>
                        <div class="faq-answer px-6 pb-6">
                            <p class="text-gray-600 dark:text-gray-400">
                                Our standard aquariums come with a 3-year warranty against manufacturing defects. Custom aquariums include a 5-year warranty. Electronic equipment typically carries a 1-year warranty. Extended warranties are available for purchase on most products. Please refer to the specific product documentation for detailed warranty information.
                            </p>
                        </div>
                    </div>
                    
                    <!-- FAQ Item 3 -->
                    <div class="faq-item bg-white dark:bg-dark-medium rounded-lg shadow-soft overflow-hidden">
                        <button class="faq-question w-full flex justify-between items-center p-6 text-left font-medium focus:outline-none">
                            <span>Do you sell live fish and plants?</span>
                            <i class="fas fa-chevron-down text-primary transition-transform"></i>
                        </button>
                        <div class="faq-answer px-6 pb-6">
                            <p class="text-gray-600 dark:text-gray-400">
                                Yes, we offer a carefully selected range of freshwater and saltwater fish, corals, invertebrates, and aquatic plants. All our livestock is ethically sourced and quarantined before sale to ensure health and quality. Due to shipping constraints, live animals and plants are available for in-store purchase or local delivery only. We can also help source specific species for your aquarium through our special order service.
                            </p>
                        </div>
                    </div>
                    
                    <!-- FAQ Item 4 -->
                    <div class="faq-item bg-white dark:bg-dark-medium rounded-lg shadow-soft overflow-hidden">
                        <button class="faq-question w-full flex justify-between items-center p-6 text-left font-medium focus:outline-none">
                            <span>What filtration systems do you recommend?</span>
                            <i class="fas fa-chevron-down text-primary transition-transform"></i>
                        </button>
                        <div class="faq-answer px-6 pb-6">
                            <p class="text-gray-600 dark:text-gray-400">
                                The ideal filtration system depends on your specific aquarium setup. For most freshwater aquariums, we recommend canister filters or hang-on-back filters with mechanical, biological, and chemical filtration media. For saltwater and reef tanks, we typically suggest a combination of protein skimmers, mechanical filters, and live rock for biological filtration. Our experts can provide personalized recommendations based on your tank size, livestock, and maintenance preferences.
                            </p>
                        </div>
                    </div>
                    
                    <!-- FAQ Item 5 -->
                    <div class="faq-item bg-white dark:bg-dark-medium rounded-lg shadow-soft overflow-hidden">
                        <button class="faq-question w-full flex justify-between items-center p-6 text-left font-medium focus:outline-none">
                            <span>How do I choose the right lighting for my aquarium?</span>
                            <i class="fas fa-chevron-down text-primary transition-transform"></i>
                        </button>
                        <div class="faq-answer px-6 pb-6">
                            <p class="text-gray-600 dark:text-gray-400">
                                Lighting requirements vary based on your aquarium type and inhabitants. For fish-only tanks, standard LED lights are usually sufficient. Planted freshwater aquariums need full-spectrum lights with appropriate PAR values for plant growth. Reef tanks require high-intensity lights with specific spectrum outputs for coral health. Factors to consider include light intensity, spectrum, coverage area, and energy efficiency. Our lighting guide on our website provides detailed information, or you can consult with our specialists for personalized recommendations.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="faq-section py-12 md:py-16 bg-light-dark dark:bg-dark-light">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <div class="section-subtitle mb-2">
                    <span class="inline-block px-4 py-1 bg-primary text-white text-sm font-medium rounded-full">
                        Services
                    </span>
                </div>
                
                <h2 class="text-2xl md:text-3xl lg:text-4xl font-serif font-bold mb-6">
                    Installation & Maintenance
                </h2>
                
                <p class="max-w-3xl mx-auto text-gray-600 dark:text-gray-400">
                    Information about our professional aquarium services.
                </p>
            </div>
            
            <div class="max-w-3xl mx-auto">
                <div class="faq-list space-y-4">
                    <!-- FAQ Item 1 -->
                    <div class="faq-item bg-white dark:bg-dark-medium rounded-lg shadow-soft overflow-hidden">
                        <button class="faq-question w-full flex justify-between items-center p-6 text-left font-medium focus:outline-none">
                            <span>What areas do you service for installation and maintenance?</span>
                            <i class="fas fa-chevron-down text-primary transition-transform"></i>
                        </button>
                        <div class="faq-answer px-6 pb-6">
                            <p class="text-gray-600 dark:text-gray-400">
                                We currently provide installation and maintenance services in the following metropolitan areas: San Francisco Bay Area, Greater Los Angeles, New York City, Chicago, Miami, and Dallas. For large commercial projects, we can arrange services in other locations. Please contact us to confirm service availability in your specific location.
                            </p>
                        </div>
                    </div>
                    
                    <!-- FAQ Item 2 -->
                    <div class="faq-item bg-white dark:bg-dark-medium rounded-lg shadow-soft overflow-hidden">
                        <button class="faq-question w-full flex justify-between items-center p-6 text-left font-medium focus:outline-none">
                            <span>How much does a custom aquarium installation cost?</span>
                            <i class="fas fa-chevron-down text-primary transition-transform"></i>
                        </button>
                        <div class="faq-answer px-6 pb-6">
                            <p class="text-gray-600 dark:text-gray-400">
                                Custom aquarium costs vary widely depending on size, complexity, materials, and features. Small to medium custom installations typically range from $5,000 to $15,000, while larger or more elaborate systems can range from $20,000 to $100,000+. We provide detailed quotes after an initial consultation where we discuss your vision, space requirements, and budget. Our quotes include all aspects of the installation, from design to setup and initial stocking.
                            </p>
                        </div>
                    </div>
                    
                    <!-- FAQ Item 3 -->
                    <div class="faq-item bg-white dark:bg-dark-medium rounded-lg shadow-soft overflow-hidden">
                        <button class="faq-question w-full flex justify-between items-center p-6 text-left font-medium focus:outline-none">
                            <span>What is included in your maintenance services?</span>
                            <i class="fas fa-chevron-down text-primary transition-transform"></i>
                        </button>
                        <div class="faq-answer px-6 pb-6">
                            <p class="text-gray-600 dark:text-gray-400">
                                Our standard maintenance services include water testing and chemistry adjustment, glass cleaning, substrate vacuuming, filter maintenance, equipment checks, algae removal, and partial water changes. Additional services include fish and coral health assessment, plant trimming and care, deep cleaning of decorations and substrate, and equipment upgrades or replacements as needed. We offer different maintenance packages with varying frequencies and service levels to suit different needs and budgets.
                            </p>
                        </div>
                    </div>
                    
                    <!-- FAQ Item 4 -->
                    <div class="faq-item bg-white dark:bg-dark-medium rounded-lg shadow-soft overflow-hidden">
                        <button class="faq-question w-full flex justify-between items-center p-6 text-left font-medium focus:outline-none">
                            <span>How often should my aquarium be serviced?</span>
                            <i class="fas fa-chevron-down text-primary transition-transform"></i>
                        </button>
                        <div class="faq-answer px-6 pb-6">
                            <p class="text-gray-600 dark:text-gray-400">
                                The optimal service frequency depends on your aquarium type, size, and bioload. For most freshwater aquariums, we recommend maintenance every 2-4 weeks. Saltwater and reef aquariums typically benefit from more frequent service, often every 1-2 weeks. High-bioload tanks or those with sensitive species may require more frequent attention. During your initial consultation, our specialists will recommend an appropriate maintenance schedule for your specific setup.
                            </p>
                        </div>
                    </div>
                    
                    <!-- FAQ Item 5 -->
                    <div class="faq-item bg-white dark:bg-dark-medium rounded-lg shadow-soft overflow-hidden">
                        <button class="faq-question w-full flex justify-between items-center p-6 text-left font-medium focus:outline-none">
                            <span>Do you offer emergency services?</span>
                            <i class="fas fa-chevron-down text-primary transition-transform"></i>
                        </button>
                        <div class="faq-answer px-6 pb-6">
                            <p class="text-gray-600 dark:text-gray-400">
                                Yes, we offer emergency services for critical situations such as equipment failure, water quality issues, or health concerns with aquatic life. Our Ultimate Care maintenance package includes 24/7 emergency support, but we also provide emergency assistance to all clients as needed (additional fees may apply for non-subscription clients). Our emergency response team aims to address critical issues within 24 hours, often much sooner depending on the severity and location.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Support Section -->
    <section id="support" class="faq-section py-12 md:py-16">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <div class="section-subtitle mb-2">
                    <span class="inline-block px-4 py-1 bg-primary text-white text-sm font-medium rounded-full">
                        Support
                    </span>
                </div>
                
                <h2 class="text-2xl md:text-3xl lg:text-4xl font-serif font-bold mb-6">
                    Orders, Shipping & Technical Support
                </h2>
                
                <p class="max-w-3xl mx-auto text-gray-600 dark:text-gray-400">
                    Help with your purchases and technical questions.
                </p>
            </div>
            
            <div class="max-w-3xl mx-auto">
                <div class="faq-list space-y-4">
                    <!-- FAQ Item 1 -->
                    <div class="faq-item bg-white dark:bg-dark-medium rounded-lg shadow-soft overflow-hidden">
                        <button class="faq-question w-full flex justify-between items-center p-6 text-left font-medium focus:outline-none">
                            <span>How do I track my order?</span>
                            <i class="fas fa-chevron-down text-primary transition-transform"></i>
                        </button>
                        <div class="faq-answer px-6 pb-6">
                            <p class="text-gray-600 dark:text-gray-400">
                                Once your order ships, you'll receive an email with tracking information. You can also track your order by logging into your account on our website and viewing your order history. If you created an account during checkout, you can access your order details there. For guest orders, use the order number and email address provided in your confirmation email to check the status on our website.
                            </p>
                        </div>
                    </div>
                    
                    <!-- FAQ Item 2 -->
                    <div class="faq-item bg-white dark:bg-dark-medium rounded-lg shadow-soft overflow-hidden">
                        <button class="faq-question w-full flex justify-between items-center p-6 text-left font-medium focus:outline-none">
                            <span>What shipping methods do you offer?</span>
                            <i class="fas fa-chevron-down text-primary transition-transform"></i>
                        </button>
                        <div class="faq-answer px-6 pb-6">
                            <p class="text-gray-600 dark:text-gray-400">
                                We offer several shipping options depending on the products ordered and your location. Standard shipping typically takes 3-7 business days within the continental US. Expedited shipping (2-3 business days) and overnight shipping are available for most items at an additional cost. Large or heavy items may require freight shipping with special handling. International shipping times vary by destination. Detailed shipping options and costs are displayed during checkout.
                            </p>
                        </div>
                    </div>
                    
                    <!-- FAQ Item 3 -->
                    <div class="faq-item bg-white dark:bg-dark-medium rounded-lg shadow-soft overflow-hidden">
                        <button class="faq-question w-full flex justify-between items-center p-6 text-left font-medium focus:outline-none">
                            <span>How do I return a product?</span>
                            <i class="fas fa-chevron-down text-primary transition-transform"></i>
                        </button>
                        <div class="faq-answer px-6 pb-6">
                            <p class="text-gray-600 dark:text-gray-400">
                                To initiate a return, please contact our customer service team within 30 days of receiving your order. You'll need to provide your order number and reason for return. Once approved, you'll receive return instructions and a return authorization number. Pack the items securely in their original packaging if possible, and include the return authorization number. Please note that custom items, live animals, and plants cannot be returned unless they arrive damaged or defective. Return shipping costs are the customer's responsibility unless the return is due to our error.
                            </p>
                        </div>
                    </div>
                    
                    <!-- FAQ Item 4 -->
                    <div class="faq-item bg-white dark:bg-dark-medium rounded-lg shadow-soft overflow-hidden">
                        <button class="faq-question w-full flex justify-between items-center p-6 text-left font-medium focus:outline-none">
                            <span>How do I set up my new aquarium?</span>
                            <i class="fas fa-chevron-down text-primary transition-transform"></i>
                        </button>
                        <div class="faq-answer px-6 pb-6">
                            <p class="text-gray-600 dark:text-gray-400">
                                Setting up an aquarium involves several steps: placing the tank on a suitable stand, installing filtration and heating equipment, adding substrate and decorations, filling with treated water, cycling the tank to establish beneficial bacteria (which takes 4-6 weeks), and gradually introducing fish. We provide detailed setup guides with all our aquarium kits, and our website features comprehensive tutorials for different types of aquariums. For personalized guidance, our customer support team is available to help, or you can consider our professional installation services.
                            </p>
                        </div>
                    </div>
                    
                    <!-- FAQ Item 5 -->
                    <div class="faq-item bg-white dark:bg-dark-medium rounded-lg shadow-soft overflow-hidden">
                        <button class="faq-question w-full flex justify-between items-center p-6 text-left font-medium focus:outline-none">
                            <span>What should I do if my equipment isn't working properly?</span>
                            <i class="fas fa-chevron-down text-primary transition-transform"></i>
                        </button>
                        <div class="faq-answer px-6 pb-6">
                            <p class="text-gray-600 dark:text-gray-400">
                                If you're experiencing issues with your equipment, first check our troubleshooting guides available on our website. These cover common problems and solutions for most products. If the issue persists, contact our technical support team with your product details, purchase information, and a description of the problem. For products under warranty, we'll guide you through the warranty claim process. Our support team can be reached by phone, email, or through the live chat on our website during business hours.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Still Have Questions Section -->
    <section class="faq-contact py-12 md:py-16 bg-primary text-white">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto text-center">
                <h2 class="text-2xl md:text-3xl lg:text-4xl font-serif font-bold mb-6">
                    Still Have Questions?
                </h2>
                
                <p class="text-lg opacity-90 mb-8">
                    Our team is here to help. Contact us for personalized assistance with any questions about our products or services.
                </p>
                
                <div class="flex flex-wrap justify-center gap-6">
                    <a href="<?php echo esc_url(get_permalink(get_page_by_path('contact'))); ?>" class="inline-block px-8 py-4 bg-white text-primary hover:bg-accent hover:text-dark font-medium rounded-lg transition-colors">
                        Contact Us
                    </a>
                    <a href="tel:+18001234567" class="inline-block px-8 py-4 bg-transparent border border-white text-white hover:bg-white hover:text-primary font-medium rounded-lg transition-colors">
                        <i class="fas fa-phone-alt mr-2"></i> Call (800) 123-4567
                    </a>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    // Simple FAQ accordion functionality
    document.addEventListener('DOMContentLoaded', function() {
        const faqQuestions = document.querySelectorAll('.faq-question');
        
        faqQuestions.forEach(question => {
            question.addEventListener('click', function() {
                const answer = this.nextElementSibling;
                const icon = this.querySelector('i');
                
                // Toggle the current FAQ item
                answer.style.display = answer.style.display === 'block' ? 'none' : 'block';
                icon.style.transform = answer.style.display === 'block' ? 'rotate(180deg)' : 'rotate(0)';
            });
        });
        
        // Hide all answers initially
        document.querySelectorAll('.faq-answer').forEach(answer => {
            answer.style.display = 'none';
        });
    });
</script>