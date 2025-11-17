<?php
/**
 * Template Name: About Page
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
        </div>

        <?php
        // Check if the page has a featured image
        if ( has_post_thumbnail() ) :
        ?>
        <div class="about-hero">
            <?php the_post_thumbnail( 'full', array( 'class' => 'about-hero-image' ) ); ?>
        </div>
        <?php endif; ?>

        <div class="about-content">
            <?php
            while ( have_posts() ) :
                the_post();
                the_content();
            endwhile;
            ?>
        </div>

        <?php
        // About sections
        $about_sections = get_post_meta( get_the_ID(), 'about_sections', true );
        
        if ( empty( $about_sections ) ) {
            // Default sections if none are defined
            $about_sections = array(
                array(
                    'title' => __( 'Our Story', 'aqualuxe' ),
                    'content' => __( 'AquaLuxe was founded in 2010 by a group of passionate aquatic enthusiasts with a vision to bring the beauty and tranquility of aquatic life to homes and businesses around the world. What started as a small local shop has grown into a global brand known for quality, expertise, and innovation in the aquatic industry.', 'aqualuxe' ),
                    'image' => get_template_directory_uri() . '/assets/dist/images/about-story.jpg',
                    'image_position' => 'right',
                ),
                array(
                    'title' => __( 'Our Mission', 'aqualuxe' ),
                    'content' => __( 'Our mission is to provide the highest quality aquatic products and services while promoting responsible and sustainable aquarium keeping. We strive to educate our customers about proper care techniques and conservation efforts to ensure the health and longevity of aquatic ecosystems both in captivity and in the wild.', 'aqualuxe' ),
                    'image' => get_template_directory_uri() . '/assets/dist/images/about-mission.jpg',
                    'image_position' => 'left',
                ),
                array(
                    'title' => __( 'Our Team', 'aqualuxe' ),
                    'content' => __( 'Our team consists of experienced aquarists, marine biologists, and aquascaping artists who are passionate about sharing their knowledge and expertise. Each member brings unique skills and perspectives to create innovative solutions and designs for our clients.', 'aqualuxe' ),
                    'image' => get_template_directory_uri() . '/assets/dist/images/about-team.jpg',
                    'image_position' => 'right',
                ),
            );
        }
        
        if ( ! empty( $about_sections ) && is_array( $about_sections ) ) :
        ?>
        <div class="about-sections">
            <?php foreach ( $about_sections as $index => $section ) : ?>
                <div class="about-section <?php echo esc_attr( 'image-' . ( isset( $section['image_position'] ) ? $section['image_position'] : 'right' ) ); ?>">
                    <div class="about-section-content">
                        <?php if ( ! empty( $section['title'] ) ) : ?>
                            <h2 class="about-section-title"><?php echo esc_html( $section['title'] ); ?></h2>
                        <?php endif; ?>
                        
                        <?php if ( ! empty( $section['content'] ) ) : ?>
                            <div class="about-section-text">
                                <?php echo wp_kses_post( wpautop( $section['content'] ) ); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <?php if ( ! empty( $section['image'] ) ) : ?>
                        <div class="about-section-image">
                            <img src="<?php echo esc_url( $section['image'] ); ?>" alt="<?php echo esc_attr( $section['title'] ); ?>">
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <?php
        // Team members
        $team_members = get_post_meta( get_the_ID(), 'team_members', true );
        
        if ( empty( $team_members ) ) {
            // Default team members if none are defined
            $team_members = array(
                array(
                    'name' => __( 'John Smith', 'aqualuxe' ),
                    'position' => __( 'Founder & CEO', 'aqualuxe' ),
                    'bio' => __( 'With over 20 years of experience in the aquatic industry, John leads our team with passion and vision.', 'aqualuxe' ),
                    'image' => get_template_directory_uri() . '/assets/dist/images/team-1.jpg',
                ),
                array(
                    'name' => __( 'Sarah Johnson', 'aqualuxe' ),
                    'position' => __( 'Marine Biologist', 'aqualuxe' ),
                    'bio' => __( 'Sarah specializes in saltwater ecosystems and has contributed to numerous research papers on coral reef conservation.', 'aqualuxe' ),
                    'image' => get_template_directory_uri() . '/assets/dist/images/team-2.jpg',
                ),
                array(
                    'name' => __( 'David Chen', 'aqualuxe' ),
                    'position' => __( 'Lead Aquascaper', 'aqualuxe' ),
                    'bio' => __( 'David is an award-winning aquascaper whose designs have been featured in international aquarium exhibitions.', 'aqualuxe' ),
                    'image' => get_template_directory_uri() . '/assets/dist/images/team-3.jpg',
                ),
                array(
                    'name' => __( 'Emily Rodriguez', 'aqualuxe' ),
                    'position' => __( 'Customer Experience Director', 'aqualuxe' ),
                    'bio' => __( 'Emily ensures that every customer receives personalized attention and expert guidance for their aquatic needs.', 'aqualuxe' ),
                    'image' => get_template_directory_uri() . '/assets/dist/images/team-4.jpg',
                ),
            );
        }
        
        if ( ! empty( $team_members ) && is_array( $team_members ) ) :
        ?>
        <div class="team-section">
            <h2 class="section-title"><?php esc_html_e( 'Meet Our Team', 'aqualuxe' ); ?></h2>
            
            <div class="team-grid">
                <?php foreach ( $team_members as $member ) : ?>
                    <div class="team-member">
                        <?php if ( ! empty( $member['image'] ) ) : ?>
                            <div class="team-member-image">
                                <img src="<?php echo esc_url( $member['image'] ); ?>" alt="<?php echo esc_attr( $member['name'] ); ?>">
                            </div>
                        <?php endif; ?>
                        
                        <div class="team-member-info">
                            <?php if ( ! empty( $member['name'] ) ) : ?>
                                <h3 class="team-member-name"><?php echo esc_html( $member['name'] ); ?></h3>
                            <?php endif; ?>
                            
                            <?php if ( ! empty( $member['position'] ) ) : ?>
                                <p class="team-member-position"><?php echo esc_html( $member['position'] ); ?></p>
                            <?php endif; ?>
                            
                            <?php if ( ! empty( $member['bio'] ) ) : ?>
                                <div class="team-member-bio">
                                    <?php echo wp_kses_post( wpautop( $member['bio'] ) ); ?>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ( ! empty( $member['social'] ) && is_array( $member['social'] ) ) : ?>
                                <div class="team-member-social">
                                    <?php foreach ( $member['social'] as $network => $url ) : ?>
                                        <a href="<?php echo esc_url( $url ); ?>" class="social-icon <?php echo esc_attr( $network ); ?>" target="_blank" rel="noopener noreferrer">
                                            <?php
                                            switch ( $network ) {
                                                case 'linkedin':
                                                    ?>
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="20" height="20">
                                                        <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                                    </svg>
                                                    <?php
                                                    break;
                                                case 'twitter':
                                                    ?>
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="20" height="20">
                                                        <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723 9.99 9.99 0 01-3.127 1.195 4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                                                    </svg>
                                                    <?php
                                                    break;
                                                case 'instagram':
                                                    ?>
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="20" height="20">
                                                        <path d="M12 0C8.74 0 8.333.015 7.053.072 5.775.132 4.905.333 4.14.63c-.789.306-1.459.717-2.126 1.384S.935 3.35.63 4.14C.333 4.905.131 5.775.072 7.053.012 8.333 0 8.74 0 12s.015 3.667.072 4.947c.06 1.277.261 2.148.558 2.913.306.788.717 1.459 1.384 2.126.667.666 1.336 1.079 2.126 1.384.766.296 1.636.499 2.913.558C8.333 23.988 8.74 24 12 24s3.667-.015 4.947-.072c1.277-.06 2.148-.262 2.913-.558.788-.306 1.459-.718 2.126-1.384.666-.667 1.079-1.335 1.384-2.126.296-.765.499-1.636.558-2.913.06-1.28.072-1.687.072-4.947s-.015-3.667-.072-4.947c-.06-1.277-.262-2.149-.558-2.913-.306-.789-.718-1.459-1.384-2.126C21.319 1.347 20.651.935 19.86.63c-.765-.297-1.636-.499-2.913-.558C15.667.012 15.26 0 12 0zm0 2.16c3.203 0 3.585.016 4.85.071 1.17.055 1.805.249 2.227.415.562.217.96.477 1.382.896.419.42.679.819.896 1.381.164.422.36 1.057.413 2.227.057 1.266.07 1.646.07 4.85s-.015 3.585-.074 4.85c-.061 1.17-.256 1.805-.421 2.227-.224.562-.479.96-.899 1.382-.419.419-.824.679-1.38.896-.42.164-1.065.36-2.235.413-1.274.057-1.649.07-4.859.07-3.211 0-3.586-.015-4.859-.074-1.171-.061-1.816-.256-2.236-.421-.569-.224-.96-.479-1.379-.899-.421-.419-.69-.824-.9-1.38-.165-.42-.359-1.065-.42-2.235-.045-1.26-.061-1.649-.061-4.844 0-3.196.016-3.586.061-4.861.061-1.17.255-1.814.42-2.234.21-.57.479-.96.9-1.381.419-.419.81-.689 1.379-.898.42-.166 1.051-.361 2.221-.421 1.275-.045 1.65-.06 4.859-.06l.045.03zm0 3.678c-3.405 0-6.162 2.76-6.162 6.162 0 3.405 2.76 6.162 6.162 6.162 3.405 0 6.162-2.76 6.162-6.162 0-3.405-2.76-6.162-6.162-6.162zM12 16c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4zm7.846-10.405c0 .795-.646 1.44-1.44 1.44-.795 0-1.44-.646-1.44-1.44 0-.794.646-1.439 1.44-1.439.793-.001 1.44.645 1.44 1.439z"/>
                                                    </svg>
                                                    <?php
                                                    break;
                                                default:
                                                    ?>
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="20" height="20">
                                                        <path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" clip-rule="evenodd"/>
                                                    </svg>
                                                    <?php
                                                    break;
                                            }
                                            ?>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
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
                    'content' => __( 'AquaLuxe transformed our office space with a stunning custom aquarium that has become the centerpiece of our reception area. Their attention to detail and ongoing maintenance service is exceptional.', 'aqualuxe' ),
                    'author' => __( 'Michael Thompson', 'aqualuxe' ),
                    'position' => __( 'CEO, Thompson Enterprises', 'aqualuxe' ),
                    'image' => get_template_directory_uri() . '/assets/dist/images/testimonial-1.jpg',
                ),
                array(
                    'content' => __( 'As a long-time aquarium enthusiast, I\'ve never found a better source for rare fish species than AquaLuxe. Their knowledge and customer service are unmatched in the industry.', 'aqualuxe' ),
                    'author' => __( 'Jennifer Lee', 'aqualuxe' ),
                    'position' => __( 'Aquarium Collector', 'aqualuxe' ),
                    'image' => get_template_directory_uri() . '/assets/dist/images/testimonial-2.jpg',
                ),
                array(
                    'content' => __( 'The aquascaping design service from AquaLuxe helped me create a living piece of art in my home. Their team\'s creativity and expertise turned my vision into reality.', 'aqualuxe' ),
                    'author' => __( 'Robert Garcia', 'aqualuxe' ),
                    'position' => __( 'Interior Designer', 'aqualuxe' ),
                    'image' => get_template_directory_uri() . '/assets/dist/images/testimonial-3.jpg',
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
        // Partners/Clients
        $partners = get_post_meta( get_the_ID(), 'partners', true );
        
        if ( empty( $partners ) ) {
            // Default partners if none are defined
            $partners = array(
                array(
                    'name' => __( 'Aquatic World', 'aqualuxe' ),
                    'logo' => get_template_directory_uri() . '/assets/dist/images/partner-1.png',
                    'url' => '#',
                ),
                array(
                    'name' => __( 'Marine Science Institute', 'aqualuxe' ),
                    'logo' => get_template_directory_uri() . '/assets/dist/images/partner-2.png',
                    'url' => '#',
                ),
                array(
                    'name' => __( 'Global Reef Conservation', 'aqualuxe' ),
                    'logo' => get_template_directory_uri() . '/assets/dist/images/partner-3.png',
                    'url' => '#',
                ),
                array(
                    'name' => __( 'Ocean Sustainability Foundation', 'aqualuxe' ),
                    'logo' => get_template_directory_uri() . '/assets/dist/images/partner-4.png',
                    'url' => '#',
                ),
                array(
                    'name' => __( 'Aquarium Design Association', 'aqualuxe' ),
                    'logo' => get_template_directory_uri() . '/assets/dist/images/partner-5.png',
                    'url' => '#',
                ),
            );
        }
        
        if ( ! empty( $partners ) && is_array( $partners ) ) :
        ?>
        <div class="partners-section">
            <h2 class="section-title"><?php esc_html_e( 'Our Partners', 'aqualuxe' ); ?></h2>
            
            <div class="partners-grid">
                <?php foreach ( $partners as $partner ) : ?>
                    <div class="partner-item">
                        <?php if ( ! empty( $partner['url'] ) ) : ?>
                            <a href="<?php echo esc_url( $partner['url'] ); ?>" target="_blank" rel="noopener noreferrer">
                        <?php endif; ?>
                        
                        <?php if ( ! empty( $partner['logo'] ) ) : ?>
                            <img src="<?php echo esc_url( $partner['logo'] ); ?>" alt="<?php echo esc_attr( $partner['name'] ); ?>" class="partner-logo">
                        <?php else : ?>
                            <span class="partner-name"><?php echo esc_html( $partner['name'] ); ?></span>
                        <?php endif; ?>
                        
                        <?php if ( ! empty( $partner['url'] ) ) : ?>
                            </a>
                        <?php endif; ?>
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
            $cta_title = __( 'Ready to Transform Your Aquatic Experience?', 'aqualuxe' );
        }
        
        if ( empty( $cta_text ) ) {
            $cta_text = __( 'Contact us today to discuss your aquarium needs and discover how we can bring the beauty of aquatic life to your space.', 'aqualuxe' );
        }
        
        if ( empty( $cta_button_text ) ) {
            $cta_button_text = __( 'Get in Touch', 'aqualuxe' );
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

<?php
get_footer();