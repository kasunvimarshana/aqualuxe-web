<?php
/**
 * About Page Company History Section
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Get about history settings from customizer or use defaults
$history_title = get_theme_mod( 'aqualuxe_history_title', 'Our Story' );
$history_image = get_theme_mod( 'aqualuxe_history_image', get_template_directory_uri() . '/demo-content/images/about-history.jpg' );
?>

<section class="about-history-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title"><?php echo esc_html( $history_title ); ?></h2>
        </div>
        
        <div class="about-history-content">
            <div class="about-history-image">
                <img src="<?php echo esc_url( $history_image ); ?>" alt="<?php echo esc_attr( $history_title ); ?>">
            </div>
            
            <div class="about-history-text">
                <div class="history-timeline">
                    <div class="timeline-item">
                        <div class="timeline-year">2005</div>
                        <div class="timeline-content">
                            <h3><?php esc_html_e( 'The Beginning', 'aqualuxe' ); ?></h3>
                            <p><?php esc_html_e( 'AquaLuxe was founded by marine biologist Dr. James Wilson with a small breeding facility in Florida, focusing on just three species of rare tropical fish.', 'aqualuxe' ); ?></p>
                        </div>
                    </div>
                    
                    <div class="timeline-item">
                        <div class="timeline-year">2010</div>
                        <div class="timeline-content">
                            <h3><?php esc_html_e( 'Expansion', 'aqualuxe' ); ?></h3>
                            <p><?php esc_html_e( 'After five years of success, we expanded our facilities and began importing rare species from Southeast Asia while maintaining our commitment to ethical and sustainable practices.', 'aqualuxe' ); ?></p>
                        </div>
                    </div>
                    
                    <div class="timeline-item">
                        <div class="timeline-year">2015</div>
                        <div class="timeline-content">
                            <h3><?php esc_html_e( 'Global Recognition', 'aqualuxe' ); ?></h3>
                            <p><?php esc_html_e( 'AquaLuxe received international recognition for our conservation efforts and breeding programs for endangered species, establishing partnerships with marine conservation organizations worldwide.', 'aqualuxe' ); ?></p>
                        </div>
                    </div>
                    
                    <div class="timeline-item">
                        <div class="timeline-year">2020</div>
                        <div class="timeline-content">
                            <h3><?php esc_html_e( 'Innovation', 'aqualuxe' ); ?></h3>
                            <p><?php esc_html_e( 'We pioneered new breeding techniques for previously uncultivated species and launched our state-of-the-art shipping system, ensuring fish arrive in perfect condition anywhere in the world.', 'aqualuxe' ); ?></p>
                        </div>
                    </div>
                    
                    <div class="timeline-item">
                        <div class="timeline-year">2025</div>
                        <div class="timeline-content">
                            <h3><?php esc_html_e( 'Today', 'aqualuxe' ); ?></h3>
                            <p><?php esc_html_e( 'Today, AquaLuxe is a leader in the ornamental fish industry with over 200 species in our collection, serving customers in more than 50 countries while maintaining our commitment to sustainability and ethical practices.', 'aqualuxe' ); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>