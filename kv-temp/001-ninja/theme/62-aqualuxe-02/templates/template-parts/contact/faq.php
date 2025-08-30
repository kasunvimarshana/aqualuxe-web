<?php
/**
 * Contact Page FAQ Section
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Get FAQ settings from customizer or ACF
$section_title = get_theme_mod( 'aqualuxe_contact_faq_title', __( 'Frequently Asked Questions', 'aqualuxe' ) );
$section_subtitle = get_theme_mod( 'aqualuxe_contact_faq_subtitle', __( 'Find answers to common questions about our products and services', 'aqualuxe' ) );
$background_color = get_theme_mod( 'aqualuxe_contact_faq_background', 'light' );
$show_faq = get_theme_mod( 'aqualuxe_contact_show_faq', true );
$faq_layout = get_theme_mod( 'aqualuxe_contact_faq_layout', 'accordion' );
$faq_columns = get_theme_mod( 'aqualuxe_contact_faq_columns', 1 );

// Skip if FAQ is disabled
if ( ! $show_faq ) {
    return;
}

// Default FAQs if not set in customizer
$default_faqs = array(
    array(
        'question' => __( 'How do I contact customer support?', 'aqualuxe' ),
        'answer'   => __( 'You can contact our customer support team by email at support@aqualuxe.com, by phone at +1 (555) 123-4567, or by filling out the contact form on this page. Our support hours are Monday through Friday, 9am to 5pm PST.', 'aqualuxe' ),
    ),
    array(
        'question' => __( 'What payment methods do you accept?', 'aqualuxe' ),
        'answer'   => __( 'We accept all major credit cards (Visa, MasterCard, American Express, Discover), PayPal, and Apple Pay. For enterprise customers, we also offer invoice payment options.', 'aqualuxe' ),
    ),
    array(
        'question' => __( 'Do you offer refunds?', 'aqualuxe' ),
        'answer'   => __( 'Yes, we offer a 30-day money-back guarantee on all our products. If you\'re not satisfied with your purchase, you can request a full refund within 30 days of purchase.', 'aqualuxe' ),
    ),
    array(
        'question' => __( 'How long does it take to respond to inquiries?', 'aqualuxe' ),
        'answer'   => __( 'We strive to respond to all inquiries within 24 hours during business days. For urgent matters, we recommend contacting us by phone for the fastest response.', 'aqualuxe' ),
    ),
    array(
        'question' => __( 'Do you offer custom development services?', 'aqualuxe' ),
        'answer'   => __( 'Yes, we offer custom development services for businesses with specific requirements. Please contact our sales team to discuss your project needs and get a quote.', 'aqualuxe' ),
    ),
    array(
        'question' => __( 'Where is your company located?', 'aqualuxe' ),
        'answer'   => __( 'Our headquarters is located in Palo Alto, California. We also have satellite offices in New York, London, and Singapore to serve our global customers.', 'aqualuxe' ),
    ),
);

// Get FAQs from customizer or use defaults
$faqs = array();
for ( $i = 1; $i <= 6; $i++ ) {
    $faq_question = get_theme_mod( 'aqualuxe_contact_faq_' . $i . '_question', $default_faqs[$i-1]['question'] );
    $faq_answer = get_theme_mod( 'aqualuxe_contact_faq_' . $i . '_answer', $default_faqs[$i-1]['answer'] );
    
    if ( $faq_question && $faq_answer ) {
        $faqs[] = array(
            'question' => $faq_question,
            'answer'   => $faq_answer,
        );
    }
}

// Skip if no FAQs
if ( empty( $faqs ) ) {
    return;
}

// Section classes
$section_classes = array( 'faq-section', 'section' );
if ( $background_color === 'dark' ) {
    $section_classes[] = 'bg-dark text-light';
} else {
    $section_classes[] = 'bg-light';
}

// FAQ container classes
$faq_container_classes = array( 'faq-container', 'layout-' . $faq_layout );
if ( $faq_columns > 1 && $faq_layout !== 'accordion' ) {
    $faq_container_classes[] = 'columns-' . $faq_columns;
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
        
        <div class="<?php echo esc_attr( implode( ' ', $faq_container_classes ) ); ?>">
            <?php if ( $faq_layout === 'accordion' ) : ?>
                <div class="accordion" id="faqAccordion">
                    <?php foreach ( $faqs as $index => $faq ) : ?>
                        <div class="accordion-item">
                            <h3 class="accordion-header" id="faqHeading<?php echo esc_attr( $index ); ?>">
                                <button class="accordion-button <?php echo $index === 0 ? '' : 'collapsed'; ?>" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse<?php echo esc_attr( $index ); ?>" aria-expanded="<?php echo $index === 0 ? 'true' : 'false'; ?>" aria-controls="faqCollapse<?php echo esc_attr( $index ); ?>">
                                    <?php echo esc_html( $faq['question'] ); ?>
                                </button>
                            </h3>
                            <div id="faqCollapse<?php echo esc_attr( $index ); ?>" class="accordion-collapse collapse <?php echo $index === 0 ? 'show' : ''; ?>" aria-labelledby="faqHeading<?php echo esc_attr( $index ); ?>" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    <?php echo wp_kses_post( wpautop( $faq['answer'] ) ); ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else : ?>
                <div class="faq-grid">
                    <?php foreach ( $faqs as $faq ) : ?>
                        <div class="faq-item">
                            <h3 class="faq-question"><?php echo esc_html( $faq['question'] ); ?></h3>
                            <div class="faq-answer">
                                <?php echo wp_kses_post( wpautop( $faq['answer'] ) ); ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
        
        <?php
        // More questions CTA
        $show_more_questions = get_theme_mod( 'aqualuxe_contact_faq_show_more_questions', true );
        $more_questions_text = get_theme_mod( 'aqualuxe_contact_faq_more_questions_text', __( 'Still have questions?', 'aqualuxe' ) );
        $more_questions_button_text = get_theme_mod( 'aqualuxe_contact_faq_more_questions_button_text', __( 'Contact Us', 'aqualuxe' ) );
        $more_questions_button_url = get_theme_mod( 'aqualuxe_contact_faq_more_questions_button_url', '#contact-form' );
        
        if ( $show_more_questions && $more_questions_text && $more_questions_button_text ) :
        ?>
            <div class="more-questions text-center">
                <p><?php echo esc_html( $more_questions_text ); ?></p>
                <a href="<?php echo esc_url( $more_questions_button_url ); ?>" class="btn btn-primary"><?php echo esc_html( $more_questions_button_text ); ?></a>
            </div>
        <?php endif; ?>
    </div>
</section>