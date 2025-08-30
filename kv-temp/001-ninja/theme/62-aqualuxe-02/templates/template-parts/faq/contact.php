<?php
/**
 * FAQ Page Contact Section
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Get contact settings from customizer or ACF
$section_title = get_theme_mod( 'aqualuxe_faq_contact_title', __( 'Still Have Questions?', 'aqualuxe' ) );
$section_subtitle = get_theme_mod( 'aqualuxe_faq_contact_subtitle', __( 'Our support team is here to help', 'aqualuxe' ) );
$show_contact = get_theme_mod( 'aqualuxe_faq_show_contact', true );
$background_color = get_theme_mod( 'aqualuxe_faq_contact_background', 'primary' );
$background_image = get_theme_mod( 'aqualuxe_faq_contact_background_image', '' );

// Skip if contact section is disabled
if ( ! $show_contact ) {
    return;
}

// Default contact methods if not set in customizer
$default_contact_methods = array(
    array(
        'icon'  => 'envelope',
        'title' => __( 'Email Support', 'aqualuxe' ),
        'text'  => __( 'Get a response within 24 hours', 'aqualuxe' ),
        'link'  => 'mailto:support@aqualuxe.com',
        'link_text' => __( 'Email Us', 'aqualuxe' ),
    ),
    array(
        'icon'  => 'phone',
        'title' => __( 'Phone Support', 'aqualuxe' ),
        'text'  => __( 'Available Mon-Fri, 9am-5pm PST', 'aqualuxe' ),
        'link'  => 'tel:+15551234567',
        'link_text' => __( '+1 (555) 123-4567', 'aqualuxe' ),
    ),
    array(
        'icon'  => 'comments',
        'title' => __( 'Live Chat', 'aqualuxe' ),
        'text'  => __( 'Chat with our support team in real-time', 'aqualuxe' ),
        'link'  => '#',
        'link_text' => __( 'Start Chat', 'aqualuxe' ),
    ),
);

// Get contact methods from customizer or use defaults
$contact_methods = array();
for ( $i = 1; $i <= 3; $i++ ) {
    $method_icon = get_theme_mod( 'aqualuxe_faq_contact_method_' . $i . '_icon', $default_contact_methods[$i-1]['icon'] );
    $method_title = get_theme_mod( 'aqualuxe_faq_contact_method_' . $i . '_title', $default_contact_methods[$i-1]['title'] );
    $method_text = get_theme_mod( 'aqualuxe_faq_contact_method_' . $i . '_text', $default_contact_methods[$i-1]['text'] );
    $method_link = get_theme_mod( 'aqualuxe_faq_contact_method_' . $i . '_link', $default_contact_methods[$i-1]['link'] );
    $method_link_text = get_theme_mod( 'aqualuxe_faq_contact_method_' . $i . '_link_text', $default_contact_methods[$i-1]['link_text'] );
    
    if ( $method_title && $method_link ) {
        $contact_methods[] = array(
            'icon'      => $method_icon,
            'title'     => $method_title,
            'text'      => $method_text,
            'link'      => $method_link,
            'link_text' => $method_link_text,
        );
    }
}

// Skip if no contact methods
if ( empty( $contact_methods ) ) {
    return;
}

// Section classes
$section_classes = array( 'faq-contact-section', 'section' );
if ( $background_color === 'primary' ) {
    $section_classes[] = 'bg-primary text-light';
} elseif ( $background_color === 'dark' ) {
    $section_classes[] = 'bg-dark text-light';
} else {
    $section_classes[] = 'bg-light';
}

if ( $background_image ) {
    $section_classes[] = 'has-background-image';
}

// Section style
$section_style = '';
if ( $background_image ) {
    $section_style = 'background-image: url(' . esc_url( $background_image ) . ');';
}
?>

<section class="<?php echo esc_attr( implode( ' ', $section_classes ) ); ?>" <?php if ( $section_style ) echo 'style="' . esc_attr( $section_style ) . '"'; ?>>
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
        
        <div class="contact-methods">
            <div class="row">
                <?php foreach ( $contact_methods as $method ) : ?>
                    <div class="col-md-4">
                        <div class="contact-method">
                            <?php if ( $method['icon'] ) : ?>
                                <div class="method-icon">
                                    <i class="icon-<?php echo esc_attr( $method['icon'] ); ?>"></i>
                                </div>
                            <?php endif; ?>
                            
                            <h3 class="method-title"><?php echo esc_html( $method['title'] ); ?></h3>
                            
                            <?php if ( $method['text'] ) : ?>
                                <div class="method-text">
                                    <p><?php echo wp_kses_post( $method['text'] ); ?></p>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ( $method['link'] && $method['link_text'] ) : ?>
                                <div class="method-link">
                                    <a href="<?php echo esc_url( $method['link'] ); ?>" class="btn btn-outline-light"><?php echo esc_html( $method['link_text'] ); ?></a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        
        <?php
        // Contact page link
        $show_contact_page_link = get_theme_mod( 'aqualuxe_faq_show_contact_page_link', true );
        $contact_page_text = get_theme_mod( 'aqualuxe_faq_contact_page_text', __( 'Visit our contact page for more ways to reach us', 'aqualuxe' ) );
        $contact_page_url = get_theme_mod( 'aqualuxe_faq_contact_page_url', get_permalink( get_page_by_path( 'contact' ) ) );
        
        if ( $show_contact_page_link && $contact_page_text && $contact_page_url ) :
        ?>
            <div class="contact-page-link text-center">
                <a href="<?php echo esc_url( $contact_page_url ); ?>"><?php echo esc_html( $contact_page_text ); ?> <i class="icon-arrow-right"></i></a>
            </div>
        <?php endif; ?>
    </div>
</section>