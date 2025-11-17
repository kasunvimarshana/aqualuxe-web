<?php
/**
 * Contact Page Info Section
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Get info settings from customizer or ACF
$section_title = get_theme_mod( 'aqualuxe_contact_info_title', __( 'Contact Information', 'aqualuxe' ) );
$section_subtitle = get_theme_mod( 'aqualuxe_contact_info_subtitle', __( 'Multiple ways to reach our team', 'aqualuxe' ) );
$background_color = get_theme_mod( 'aqualuxe_contact_info_background', 'light' );
$columns = get_theme_mod( 'aqualuxe_contact_info_columns', 3 );

// Default contact info if not set in customizer
$default_info = array(
    array(
        'icon'  => 'map-marker',
        'title' => __( 'Our Location', 'aqualuxe' ),
        'text'  => "123 Main Street\nPalo Alto, CA 94301\nUnited States",
        'link'  => 'https://maps.google.com',
        'link_text' => __( 'Get Directions', 'aqualuxe' ),
    ),
    array(
        'icon'  => 'envelope',
        'title' => __( 'Email Us', 'aqualuxe' ),
        'text'  => "info@aqualuxe.com\nsupport@aqualuxe.com",
        'link'  => 'mailto:info@aqualuxe.com',
        'link_text' => __( 'Send Email', 'aqualuxe' ),
    ),
    array(
        'icon'  => 'phone',
        'title' => __( 'Call Us', 'aqualuxe' ),
        'text'  => "+1 (555) 123-4567\nMon-Fri, 9am-5pm PST",
        'link'  => 'tel:+15551234567',
        'link_text' => __( 'Call Now', 'aqualuxe' ),
    ),
);

// Get contact info from customizer or use defaults
$contact_info = array();
for ( $i = 1; $i <= 3; $i++ ) {
    $info_icon = get_theme_mod( 'aqualuxe_contact_info_' . $i . '_icon', $default_info[$i-1]['icon'] );
    $info_title = get_theme_mod( 'aqualuxe_contact_info_' . $i . '_title', $default_info[$i-1]['title'] );
    $info_text = get_theme_mod( 'aqualuxe_contact_info_' . $i . '_text', $default_info[$i-1]['text'] );
    $info_link = get_theme_mod( 'aqualuxe_contact_info_' . $i . '_link', $default_info[$i-1]['link'] );
    $info_link_text = get_theme_mod( 'aqualuxe_contact_info_' . $i . '_link_text', $default_info[$i-1]['link_text'] );
    
    if ( $info_title && $info_text ) {
        $contact_info[] = array(
            'icon'      => $info_icon,
            'title'     => $info_title,
            'text'      => $info_text,
            'link'      => $info_link,
            'link_text' => $info_link_text,
        );
    }
}

// Skip if no contact info
if ( empty( $contact_info ) ) {
    return;
}

// Section classes
$section_classes = array( 'contact-info-section', 'section' );
if ( $background_color === 'dark' ) {
    $section_classes[] = 'bg-dark text-light';
} else {
    $section_classes[] = 'bg-light';
}
?>

<section class="<?php echo esc_attr( implode( ' ', $section_classes ) ); ?>">
    <div class="container">
        <div class="section-header text-center">
            <?php if ( $section_title ) : ?>
                <h2 class="section-title"><?php echo esc_html( $section_title ); ?></h2>
            <?php endif; ?>
            
            <?php if ( $section_subtitle ) : ?>
                <div class="section-subtitle">
                    <p><?php echo wp_kses_post( $section_subtitle ); ?></p>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="contact-info-grid columns-<?php echo esc_attr( $columns ); ?>">
            <?php foreach ( $contact_info as $info ) : ?>
                <div class="contact-info-item">
                    <?php if ( $info['icon'] ) : ?>
                        <div class="info-icon">
                            <i class="icon-<?php echo esc_attr( $info['icon'] ); ?>"></i>
                        </div>
                    <?php endif; ?>
                    
                    <div class="info-content">
                        <?php if ( $info['title'] ) : ?>
                            <h3 class="info-title"><?php echo esc_html( $info['title'] ); ?></h3>
                        <?php endif; ?>
                        
                        <?php if ( $info['text'] ) : ?>
                            <div class="info-text">
                                <?php echo wp_kses_post( nl2br( $info['text'] ) ); ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ( $info['link'] && $info['link_text'] ) : ?>
                            <div class="info-link">
                                <a href="<?php echo esc_url( $info['link'] ); ?>" class="btn btn-outline-primary btn-sm"><?php echo esc_html( $info['link_text'] ); ?></a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <?php
        // Social media links
        $show_social = get_theme_mod( 'aqualuxe_contact_info_show_social', true );
        if ( $show_social ) :
            $facebook = get_theme_mod( 'aqualuxe_social_facebook', '#' );
            $twitter = get_theme_mod( 'aqualuxe_social_twitter', '#' );
            $instagram = get_theme_mod( 'aqualuxe_social_instagram', '#' );
            $linkedin = get_theme_mod( 'aqualuxe_social_linkedin', '#' );
            $youtube = get_theme_mod( 'aqualuxe_social_youtube', '' );
            $pinterest = get_theme_mod( 'aqualuxe_social_pinterest', '' );
        ?>
            <div class="social-links-wrapper text-center">
                <h3 class="social-title"><?php esc_html_e( 'Follow Us', 'aqualuxe' ); ?></h3>
                
                <div class="social-links">
                    <?php if ( $facebook ) : ?>
                        <a href="<?php echo esc_url( $facebook ); ?>" class="social-link facebook" target="_blank" rel="noopener noreferrer">
                            <i class="icon-facebook"></i>
                            <span class="screen-reader-text"><?php esc_html_e( 'Facebook', 'aqualuxe' ); ?></span>
                        </a>
                    <?php endif; ?>
                    
                    <?php if ( $twitter ) : ?>
                        <a href="<?php echo esc_url( $twitter ); ?>" class="social-link twitter" target="_blank" rel="noopener noreferrer">
                            <i class="icon-twitter"></i>
                            <span class="screen-reader-text"><?php esc_html_e( 'Twitter', 'aqualuxe' ); ?></span>
                        </a>
                    <?php endif; ?>
                    
                    <?php if ( $instagram ) : ?>
                        <a href="<?php echo esc_url( $instagram ); ?>" class="social-link instagram" target="_blank" rel="noopener noreferrer">
                            <i class="icon-instagram"></i>
                            <span class="screen-reader-text"><?php esc_html_e( 'Instagram', 'aqualuxe' ); ?></span>
                        </a>
                    <?php endif; ?>
                    
                    <?php if ( $linkedin ) : ?>
                        <a href="<?php echo esc_url( $linkedin ); ?>" class="social-link linkedin" target="_blank" rel="noopener noreferrer">
                            <i class="icon-linkedin"></i>
                            <span class="screen-reader-text"><?php esc_html_e( 'LinkedIn', 'aqualuxe' ); ?></span>
                        </a>
                    <?php endif; ?>
                    
                    <?php if ( $youtube ) : ?>
                        <a href="<?php echo esc_url( $youtube ); ?>" class="social-link youtube" target="_blank" rel="noopener noreferrer">
                            <i class="icon-youtube"></i>
                            <span class="screen-reader-text"><?php esc_html_e( 'YouTube', 'aqualuxe' ); ?></span>
                        </a>
                    <?php endif; ?>
                    
                    <?php if ( $pinterest ) : ?>
                        <a href="<?php echo esc_url( $pinterest ); ?>" class="social-link pinterest" target="_blank" rel="noopener noreferrer">
                            <i class="icon-pinterest"></i>
                            <span class="screen-reader-text"><?php esc_html_e( 'Pinterest', 'aqualuxe' ); ?></span>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>