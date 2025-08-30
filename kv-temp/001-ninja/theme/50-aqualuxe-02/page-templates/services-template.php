<?php
/**
 * Template Name: Services Page
 *
 * @package AquaLuxe
 */

get_header();
?>

<main id="primary" class="site-main">
    <div class="container">
        <?php
        // Display breadcrumbs if a breadcrumb plugin is active
        if ( function_exists( 'yoast_breadcrumb' ) ) {
            yoast_breadcrumb( '<div class="breadcrumbs">', '</div>' );
        } elseif ( function_exists( 'bcn_display' ) ) {
            echo '<div class="breadcrumbs">';
            bcn_display();
            echo '</div>';
        }
        ?>

        <div class="page-header">
            <h1 class="page-title"><?php the_title(); ?></h1>
            
            <?php
            // Get page subtitle
            $subtitle = get_post_meta( get_the_ID(), 'page_subtitle', true );
            if ( empty( $subtitle ) ) {
                $subtitle = __( 'Professional aquatic solutions for every need', 'aqualuxe' );
            }
            ?>
            
            <p class="page-subtitle"><?php echo esc_html( $subtitle ); ?></p>
        </div>

        <?php
        // Check if the page has a featured image
        if ( has_post_thumbnail() ) :
        ?>
        <div class="services-hero">
            <?php the_post_thumbnail( 'full', array( 'class' => 'services-hero-image' ) ); ?>
        </div>
        <?php endif; ?>

        <div class="services-intro">
            <?php
            while ( have_posts() ) :
                the_post();
                the_content();
            endwhile;
            ?>
        </div>

        <?php
        // Services
        $services = get_post_meta( get_the_ID(), 'services', true );
        
        if ( empty( $services ) ) {
            // Default services if none are defined
            $services = array(
                array(
                    'icon' => 'design',
                    'title' => __( 'Aquarium Design & Installation', 'aqualuxe' ),
                    'description' => __( 'Our expert designers create custom aquarium solutions tailored to your space and preferences. From concept to installation, we handle every detail to ensure a stunning and functional aquatic display.', 'aqualuxe' ),
                    'features' => array(
                        __( 'Custom aquarium design for homes and businesses', 'aqualuxe' ),
                        __( 'Professional installation by certified technicians', 'aqualuxe' ),
                        __( 'Integrated lighting and filtration systems', 'aqualuxe' ),
                        __( 'Custom cabinetry and furniture design', 'aqualuxe' ),
                    ),
                    'image' => get_template_directory_uri() . '/assets/dist/images/service-design.jpg',
                ),
                array(
                    'icon' => 'maintenance',
                    'title' => __( 'Maintenance Services', 'aqualuxe' ),
                    'description' => __( 'Keep your aquarium looking its best with our comprehensive maintenance services. Our trained technicians provide regular cleaning, water testing, and equipment checks to ensure a healthy environment for your aquatic life.', 'aqualuxe' ),
                    'features' => array(
                        __( 'Weekly, bi-weekly, or monthly maintenance plans', 'aqualuxe' ),
                        __( 'Water quality testing and adjustments', 'aqualuxe' ),
                        __( 'Glass cleaning and substrate maintenance', 'aqualuxe' ),
                        __( 'Equipment inspection and replacement', 'aqualuxe' ),
                    ),
                    'image' => get_template_directory_uri() . '/assets/dist/images/service-maintenance.jpg',
                ),
                array(
                    'icon' => 'health',
                    'title' => __( 'Aquatic Health Services', 'aqualuxe' ),
                    'description' => __( 'Our aquatic health specialists provide expert care for your fish and plants. From quarantine procedures to disease diagnosis and treatment, we ensure the wellbeing of your aquatic ecosystem.', 'aqualuxe' ),
                    'features' => array(
                        __( 'Fish health assessments and treatments', 'aqualuxe' ),
                        __( 'Quarantine services for new specimens', 'aqualuxe' ),
                        __( 'Disease prevention and management', 'aqualuxe' ),
                        __( 'Nutrition and feeding consultations', 'aqualuxe' ),
                    ),
                    'image' => get_template_directory_uri() . '/assets/dist/images/service-health.jpg',
                ),
                array(
                    'icon' => 'consultation',
                    'title' => __( 'Expert Consultation', 'aqualuxe' ),
                    'description' => __( 'Get personalized advice from our team of aquatic experts. Whether you\'re planning a new aquarium or looking to enhance your existing setup, our consultants provide valuable insights and recommendations.', 'aqualuxe' ),
                    'features' => array(
                        __( 'Personalized aquarium planning', 'aqualuxe' ),
                        __( 'Species selection and compatibility advice', 'aqualuxe' ),
                        __( 'Aquascaping design consultations', 'aqualuxe' ),
                        __( 'Equipment recommendations and upgrades', 'aqualuxe' ),
                    ),
                    'image' => get_template_directory_uri() . '/assets/dist/images/service-consultation.jpg',
                ),
                array(
                    'icon' => 'globe',
                    'title' => __( 'Commercial Installations', 'aqualuxe' ),
                    'description' => __( 'Enhance your business space with a stunning aquarium installation. Our commercial services include design, installation, and maintenance for offices, restaurants, hotels, and other commercial spaces.', 'aqualuxe' ),
                    'features' => array(
                        __( 'Custom designs for commercial spaces', 'aqualuxe' ),
                        __( 'Large-scale installation capabilities', 'aqualuxe' ),
                        __( 'Commercial maintenance contracts', 'aqualuxe' ),
                        __( 'Liability insurance and warranty coverage', 'aqualuxe' ),
                    ),
                    'image' => get_template_directory_uri() . '/assets/dist/images/service-commercial.jpg',
                ),
                array(
                    'icon' => 'fish',
                    'title' => __( 'Rare Species Sourcing', 'aqualuxe' ),
                    'description' => __( 'Access our global network of suppliers for rare and exotic aquatic species. We ethically source and carefully transport unique fish, corals, and plants for collectors and enthusiasts.', 'aqualuxe' ),
                    'features' => array(
                        __( 'Ethical sourcing of rare species', 'aqualuxe' ),
                        __( 'Safe transportation and acclimation', 'aqualuxe' ),
                        __( 'Breeding program for endangered species', 'aqualuxe' ),
                        __( 'Documentation and care instructions', 'aqualuxe' ),
                    ),
                    'image' => get_template_directory_uri() . '/assets/dist/images/service-rare.jpg',
                ),
            );
        }
        
        if ( ! empty( $services ) && is_array( $services ) ) :
        ?>
        <div class="services-grid">
            <?php foreach ( $services as $service ) : ?>
                <div class="service-card">
                    <div class="service-header">
                        <?php if ( ! empty( $service['icon'] ) ) : ?>
                            <div class="service-icon">
                                <?php aqualuxe_get_icon( $service['icon'] ); ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ( ! empty( $service['title'] ) ) : ?>
                            <h2 class="service-title"><?php echo esc_html( $service['title'] ); ?></h2>
                        <?php endif; ?>
                    </div>
                    
                    <?php if ( ! empty( $service['image'] ) ) : ?>
                        <div class="service-image">
                            <img src="<?php echo esc_url( $service['image'] ); ?>" alt="<?php echo esc_attr( $service['title'] ); ?>">
                        </div>
                    <?php endif; ?>
                    
                    <?php if ( ! empty( $service['description'] ) ) : ?>
                        <div class="service-description">
                            <?php echo wp_kses_post( wpautop( $service['description'] ) ); ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ( ! empty( $service['features'] ) && is_array( $service['features'] ) ) : ?>
                        <div class="service-features">
                            <h3><?php esc_html_e( 'What We Offer', 'aqualuxe' ); ?></h3>
                            <ul class="features-list">
                                <?php foreach ( $service['features'] as $feature ) : ?>
                                    <li class="feature-item">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="16" height="16">
                                            <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zm13.36-1.814a.75.75 0 10-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 00-1.06 1.06l2.25 2.25a.75.75 0 001.14-.094l3.75-5.25z" clip-rule="evenodd" />
                                        </svg>
                                        <?php echo esc_html( $feature ); ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    
                    <?php
                    // Service pricing
                    $pricing = ! empty( $service['pricing'] ) ? $service['pricing'] : array();
                    if ( ! empty( $pricing ) && is_array( $pricing ) ) :
                    ?>
                        <div class="service-pricing">
                            <h3><?php esc_html_e( 'Pricing Options', 'aqualuxe' ); ?></h3>
                            <div class="pricing-options">
                                <?php foreach ( $pricing as $price ) : ?>
                                    <div class="pricing-option">
                                        <?php if ( ! empty( $price['name'] ) ) : ?>
                                            <h4 class="pricing-name"><?php echo esc_html( $price['name'] ); ?></h4>
                                        <?php endif; ?>
                                        
                                        <?php if ( ! empty( $price['price'] ) ) : ?>
                                            <div class="pricing-price"><?php echo esc_html( $price['price'] ); ?></div>
                                        <?php endif; ?>
                                        
                                        <?php if ( ! empty( $price['description'] ) ) : ?>
                                            <div class="pricing-description"><?php echo wp_kses_post( $price['description'] ); ?></div>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <div class="service-footer">
                        <a href="<?php echo esc_url( home_url( '/contact' ) ); ?>" class="btn btn-primary"><?php esc_html_e( 'Inquire Now', 'aqualuxe' ); ?></a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <?php
        // Process section
        $process_title = get_post_meta( get_the_ID(), 'process_title', true );
        $process_description = get_post_meta( get_the_ID(), 'process_description', true );
        $process_steps = get_post_meta( get_the_ID(), 'process_steps', true );
        
        if ( empty( $process_title ) ) {
            $process_title = __( 'Our Service Process', 'aqualuxe' );
        }
        
        if ( empty( $process_description ) ) {
            $process_description = __( 'We follow a comprehensive process to ensure the highest quality service for every client. Here\'s how we work with you from consultation to ongoing support.', 'aqualuxe' );
        }
        
        if ( empty( $process_steps ) ) {
            $process_steps = array(
                array(
                    'number' => '01',
                    'title' => __( 'Initial Consultation', 'aqualuxe' ),
                    'description' => __( 'We begin with a detailed consultation to understand your vision, space requirements, and preferences.', 'aqualuxe' ),
                ),
                array(
                    'number' => '02',
                    'title' => __( 'Custom Design', 'aqualuxe' ),
                    'description' => __( 'Our designers create a personalized plan including aquarium size, layout, species selection, and equipment specifications.', 'aqualuxe' ),
                ),
                array(
                    'number' => '03',
                    'title' => __( 'Professional Installation', 'aqualuxe' ),
                    'description' => __( 'Our expert technicians handle the complete installation, ensuring everything is set up correctly and functioning properly.', 'aqualuxe' ),
                ),
                array(
                    'number' => '04',
                    'title' => __( 'Ecosystem Development', 'aqualuxe' ),
                    'description' => __( 'We carefully introduce and monitor the aquatic life, allowing the ecosystem to establish and stabilize.', 'aqualuxe' ),
                ),
                array(
                    'number' => '05',
                    'title' => __( 'Ongoing Support', 'aqualuxe' ),
                    'description' => __( 'We provide regular maintenance and support to ensure the long-term health and beauty of your aquatic environment.', 'aqualuxe' ),
                ),
            );
        }
        
        if ( ! empty( $process_steps ) && is_array( $process_steps ) ) :
        ?>
        <div class="process-section">
            <div class="section-header">
                <h2 class="section-title"><?php echo esc_html( $process_title ); ?></h2>
                <p class="section-description"><?php echo esc_html( $process_description ); ?></p>
            </div>
            
            <div class="process-steps">
                <?php foreach ( $process_steps as $step ) : ?>
                    <div class="process-step">
                        <?php if ( ! empty( $step['number'] ) ) : ?>
                            <div class="step-number"><?php echo esc_html( $step['number'] ); ?></div>
                        <?php endif; ?>
                        
                        <div class="step-content">
                            <?php if ( ! empty( $step['title'] ) ) : ?>
                                <h3 class="step-title"><?php echo esc_html( $step['title'] ); ?></h3>
                            <?php endif; ?>
                            
                            <?php if ( ! empty( $step['description'] ) ) : ?>
                                <p class="step-description"><?php echo esc_html( $step['description'] ); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <?php
        // FAQ section
        $faq_title = get_post_meta( get_the_ID(), 'faq_title', true );
        $faq_description = get_post_meta( get_the_ID(), 'faq_description', true );
        $faqs = get_post_meta( get_the_ID(), 'faqs', true );
        
        if ( empty( $faq_title ) ) {
            $faq_title = __( 'Frequently Asked Questions', 'aqualuxe' );
        }
        
        if ( empty( $faq_description ) ) {
            $faq_description = __( 'Find answers to common questions about our services. If you don\'t see your question here, please contact us.', 'aqualuxe' );
        }
        
        if ( empty( $faqs ) ) {
            $faqs = array(
                array(
                    'question' => __( 'How often should my aquarium be maintained?', 'aqualuxe' ),
                    'answer' => __( 'The frequency of maintenance depends on the size and type of your aquarium. Generally, we recommend professional maintenance every 2-4 weeks for optimal results. During these visits, we clean the glass, perform water changes, test water parameters, and ensure all equipment is functioning properly.', 'aqualuxe' ),
                ),
                array(
                    'question' => __( 'What is included in your maintenance service?', 'aqualuxe' ),
                    'answer' => __( 'Our standard maintenance service includes glass cleaning, water testing and adjustments, filter maintenance, equipment checks, algae removal, substrate vacuuming, and minor aquascaping adjustments. We also offer additional services such as deep cleaning, equipment upgrades, and livestock health assessments.', 'aqualuxe' ),
                ),
                array(
                    'question' => __( 'How long does it take to set up a new aquarium?', 'aqualuxe' ),
                    'answer' => __( 'The timeline for a new aquarium setup varies depending on the size and complexity. The physical installation typically takes 1-3 days, but the cycling process (establishing beneficial bacteria) requires 4-6 weeks before the aquarium is ready for most fish. We guide you through this entire process and can provide cycling services to ensure a healthy start.', 'aqualuxe' ),
                ),
                array(
                    'question' => __( 'Do you offer emergency services?', 'aqualuxe' ),
                    'answer' => __( 'Yes, we offer emergency services for our maintenance clients. If you notice any issues such as equipment failure, water quality problems, or fish health concerns, our team is available to provide prompt assistance. Emergency services are available 7 days a week with priority scheduling for existing clients.', 'aqualuxe' ),
                ),
                array(
                    'question' => __( 'How do you source your fish and plants?', 'aqualuxe' ),
                    'answer' => __( 'We work with a global network of reputable suppliers who follow ethical and sustainable practices. Many of our fish are from captive breeding programs rather than wild-caught specimens. We prioritize the health and well-being of all aquatic life and ensure proper acclimation before they reach your aquarium.', 'aqualuxe' ),
                ),
                array(
                    'question' => __( 'Do you offer warranties on your installations?', 'aqualuxe' ),
                    'answer' => __( 'Yes, all of our installations come with a comprehensive warranty. Equipment is covered by manufacturer warranties (typically 1-3 years), and our workmanship is guaranteed for 12 months. Additionally, clients on our maintenance plans receive extended coverage and priority service for any issues that may arise.', 'aqualuxe' ),
                ),
            );
        }
        
        if ( ! empty( $faqs ) && is_array( $faqs ) ) :
        ?>
        <div class="faq-section">
            <div class="section-header">
                <h2 class="section-title"><?php echo esc_html( $faq_title ); ?></h2>
                <p class="section-description"><?php echo esc_html( $faq_description ); ?></p>
            </div>
            
            <div class="faq-accordion">
                <?php foreach ( $faqs as $index => $faq ) : ?>
                    <div class="faq-item">
                        <div class="faq-question" id="faq-question-<?php echo esc_attr( $index ); ?>" aria-expanded="false" aria-controls="faq-answer-<?php echo esc_attr( $index ); ?>">
                            <h3><?php echo esc_html( $faq['question'] ); ?></h3>
                            <span class="faq-toggle">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="24" height="24" class="icon-plus">
                                    <path fill-rule="evenodd" d="M12 3.75a.75.75 0 01.75.75v6.75h6.75a.75.75 0 010 1.5h-6.75v6.75a.75.75 0 01-1.5 0v-6.75H4.5a.75.75 0 010-1.5h6.75V4.5a.75.75 0 01.75-.75z" clip-rule="evenodd" />
                                </svg>
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="24" height="24" class="icon-minus hidden">
                                    <path fill-rule="evenodd" d="M3.75 12a.75.75 0 01.75-.75h15a.75.75 0 010 1.5h-15a.75.75 0 01-.75-.75z" clip-rule="evenodd" />
                                </svg>
                            </span>
                        </div>
                        <div class="faq-answer hidden" id="faq-answer-<?php echo esc_attr( $index ); ?>" aria-labelledby="faq-question-<?php echo esc_attr( $index ); ?>">
                            <?php echo wp_kses_post( wpautop( $faq['answer'] ) ); ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <?php
        // Testimonials
        $testimonials = get_post_meta( get_the_ID(), 'testimonials', true );
        
        if ( empty( $testimonials ) ) {
            // Default testimonials if none are defined
            $testimonials = get_theme_mod( 'aqualuxe_testimonials', array(
                array(
                    'content' => __( 'The maintenance service from AquaLuxe has been exceptional. Their team is professional, knowledgeable, and always goes above and beyond to ensure our office aquarium looks stunning.', 'aqualuxe' ),
                    'author' => __( 'James Wilson', 'aqualuxe' ),
                    'position' => __( 'Office Manager', 'aqualuxe' ),
                    'image' => get_template_directory_uri() . '/assets/dist/images/testimonial-4.jpg',
                ),
                array(
                    'content' => __( 'I was amazed by the custom aquarium design AquaLuxe created for our restaurant. It has become a focal point that our customers love, and their maintenance service keeps it looking perfect.', 'aqualuxe' ),
                    'author' => __( 'Maria Sanchez', 'aqualuxe' ),
                    'position' => __( 'Restaurant Owner', 'aqualuxe' ),
                    'image' => get_template_directory_uri() . '/assets/dist/images/testimonial-5.jpg',
                ),
                array(
                    'content' => __( 'The rare species sourcing service is incredible. AquaLuxe helped me find specimens I\'ve been searching for years, all ethically sourced and in perfect health. Their expertise is unmatched.', 'aqualuxe' ),
                    'author' => __( 'David Chen', 'aqualuxe' ),
                    'position' => __( 'Collector', 'aqualuxe' ),
                    'image' => get_template_directory_uri() . '/assets/dist/images/testimonial-6.jpg',
                ),
            ) );
        }
        
        if ( ! empty( $testimonials ) && is_array( $testimonials ) ) :
        ?>
        <div class="testimonials-section">
            <h2 class="section-title"><?php esc_html_e( 'What Our Clients Say', 'aqualuxe' ); ?></h2>
            
            <div class="testimonials-slider">
                <?php foreach ( $testimonials as $testimonial ) : ?>
                    <div class="testimonial-item">
                        <?php if ( ! empty( $testimonial['content'] ) ) : ?>
                            <div class="testimonial-content">
                                <svg class="quote-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="48" height="48">
                                    <path fill-rule="evenodd" d="M4.804 21.644A6.707 6.707 0 006 21.75a6.721 6.721 0 006.75-6.75c0-2.69-1.545-4.81-3.71-6.24l-.13-.082a9.72 9.72 0 00-1.33-.595c-.3-.11-.443-.23-.443-.504v-.02c0-.232.123-.446.413-.517 1.702-.696 2.95-2.333 2.95-4.277C10.5 1.33 9.17 0 7.5 0S4.5 1.33 4.5 2.964c0 1.424.876 2.7 2.164 3.26.28.11.436.255.436.524v.15c0 .373-.153.522-.4.61A20.244 20.244 0 004.1 8.42l-.113.04c-.217.08-.351.14-.351.385v.01c0 .126.068.243.195.352a7.49 7.49 0 011.142.933c.31.31.53.647.653 1.003.042.118.125.217.246.286l.06.033c.12.065.173.18.173.313 0 .25-.166.475-.414.562l-.23.08c-.217.075-.518.18-.778.312l-.085.037c-.36.153-.601.566-.601.968v.498c0 .312.134.55.398.7.1.05.205.08.3.08.094 0 .19-.028.274-.084l.92-.598c.212-.136.465-.217.742-.217.27 0 .526.08.729.216l.94.6c.083.05.177.08.271.08.095 0 .193-.028.295-.08.264-.152.395-.385.395-.7v-.498c0-.401-.24-.815-.6-.967l-.086-.04c-.258-.13-.559-.234-.777-.311l-.23-.08c-.248-.087-.414-.313-.414-.563 0-.132.052-.246.172-.312l.06-.033c.12-.068.204-.168.246-.286.123-.356.342-.694.652-1.003.41-.41.79-.79 1.142-.933.127-.108.195-.225.195-.352v-.01c0-.245-.134-.305-.35-.385l-.115-.04c-1.06-.398-1.8-.78-2.602-1.31a.75.75 0 00-.827 1.25c1.956 1.301 4.03 1.57 4.654 3.3.12.337.19.693.19 1.062 0 3.313-2.688 6-6 6-.94 0-1.83-.216-2.623-.602-.535-.264-1.131.129-1.131.716v1.94c0 .47.38.85.85.85h3.331c.47 0 .85-.38.85-.85v-1.94c0-.585-.596-.978-1.13-.716a6.698 6.698 0 01-2.624.602c-1.297 0-2.5-.37-3.527-1.01l-.848-.484c-.524-.3-1.188-.008-1.29.564l-.214 1.198c-.086.47.263.904.734.904h.957c.47 0 .852.38.852.85v1.764c0 .47-.382.85-.852.85H4.5c-.47 0-.852-.38-.852-.85V20.57a.5.5 0 01.223-.416l.986-.592a.75.75 0 10-.753-1.298l-.144.087c-.08.05-.182.082-.293.082-.105 0-.203-.03-.28-.08l-.915-.546z" clip-rule="evenodd" />
                                </svg>
                                <?php echo wp_kses_post( wpautop( $testimonial['content'] ) ); ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="testimonial-author">
                            <?php if ( ! empty( $testimonial['image'] ) ) : ?>
                                <div class="testimonial-author-image">
                                    <img src="<?php echo esc_url( $testimonial['image'] ); ?>" alt="<?php echo esc_attr( $testimonial['author'] ); ?>">
                                </div>
                            <?php endif; ?>
                            
                            <div class="testimonial-author-info">
                                <?php if ( ! empty( $testimonial['author'] ) ) : ?>
                                    <h4 class="testimonial-author-name"><?php echo esc_html( $testimonial['author'] ); ?></h4>
                                <?php endif; ?>
                                
                                <?php if ( ! empty( $testimonial['position'] ) ) : ?>
                                    <p class="testimonial-author-position"><?php echo esc_html( $testimonial['position'] ); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <?php
        // Call to action
        $cta_title = get_post_meta( get_the_ID(), 'cta_title', true );
        $cta_text = get_post_meta( get_the_ID(), 'cta_text', true );
        $cta_button_text = get_post_meta( get_the_ID(), 'cta_button_text', true );
        $cta_button_url = get_post_meta( get_the_ID(), 'cta_button_url', true );
        $cta_background = get_post_meta( get_the_ID(), 'cta_background', true );
        
        if ( empty( $cta_title ) ) {
            $cta_title = __( 'Ready to Get Started?', 'aqualuxe' );
        }
        
        if ( empty( $cta_text ) ) {
            $cta_text = __( 'Contact us today to schedule a consultation and discover how our services can enhance your aquatic experience.', 'aqualuxe' );
        }
        
        if ( empty( $cta_button_text ) ) {
            $cta_button_text = __( 'Schedule a Consultation', 'aqualuxe' );
        }
        
        if ( empty( $cta_button_url ) ) {
            $cta_button_url = home_url( '/contact' );
        }
        
        if ( empty( $cta_background ) ) {
            $cta_background = get_template_directory_uri() . '/assets/dist/images/cta-background.jpg';
        }
        ?>
        <div class="cta-section" style="background-image: url('<?php echo esc_url( $cta_background ); ?>');">
            <div class="cta-overlay"></div>
            <div class="cta-content">
                <h2 class="cta-title"><?php echo esc_html( $cta_title ); ?></h2>
                <p class="cta-text"><?php echo esc_html( $cta_text ); ?></p>
                <a href="<?php echo esc_url( $cta_button_url ); ?>" class="btn btn-primary"><?php echo esc_html( $cta_button_text ); ?></a>
            </div>
        </div>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // FAQ accordion functionality
    const faqQuestions = document.querySelectorAll('.faq-question');
    
    faqQuestions.forEach(function(question) {
        question.addEventListener('click', function() {
            const answer = document.getElementById(this.getAttribute('aria-controls'));
            const isExpanded = this.getAttribute('aria-expanded') === 'true';
            const iconPlus = this.querySelector('.icon-plus');
            const iconMinus = this.querySelector('.icon-minus');
            
            // Toggle current FAQ item
            this.setAttribute('aria-expanded', !isExpanded);
            answer.classList.toggle('hidden');
            iconPlus.classList.toggle('hidden');
            iconMinus.classList.toggle('hidden');
            
            // Optional: Close other FAQ items
            if (!isExpanded) {
                faqQuestions.forEach(function(otherQuestion) {
                    if (otherQuestion !== question) {
                        const otherAnswer = document.getElementById(otherQuestion.getAttribute('aria-controls'));
                        const otherIconPlus = otherQuestion.querySelector('.icon-plus');
                        const otherIconMinus = otherQuestion.querySelector('.icon-minus');
                        
                        otherQuestion.setAttribute('aria-expanded', 'false');
                        otherAnswer.classList.add('hidden');
                        otherIconPlus.classList.remove('hidden');
                        otherIconMinus.classList.add('hidden');
                    }
                });
            }
        });
    });
});
</script>

<?php
get_footer();