<?php
/**
 * Contact Page FAQ Section
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Get FAQ settings from customizer or use defaults
$faq_title = get_theme_mod( 'aqualuxe_faq_title', 'Frequently Asked Questions' );
$faq_subtitle = get_theme_mod( 'aqualuxe_faq_subtitle', 'Find answers to common questions about our products and services' );

// Demo FAQ items
$faq_items = array(
    array(
        'question' => 'How do you ship live fish?',
        'answer'   => 'We use a specialized shipping method that includes insulated boxes, heat or cold packs (depending on the season), and oxygen-filled bags. Each fish is carefully packaged to ensure they arrive in perfect condition. We ship via overnight or express services to minimize transit time.',
    ),
    array(
        'question' => 'What happens if my fish arrive dead or damaged?',
        'answer'   => 'We offer a 100% live arrival guarantee on all our fish. If any fish arrive dead or severely stressed, simply take a photo within 2 hours of delivery and contact us. We\'ll provide a replacement or refund as per your preference.',
    ),
    array(
        'question' => 'Do you ship internationally?',
        'answer'   => 'Yes, we ship to over 50 countries worldwide. International shipping requires additional documentation and may be subject to customs regulations in your country. Please contact us before placing an international order so we can provide you with specific information for your location.',
    ),
    array(
        'question' => 'How do I prepare my aquarium for new fish?',
        'answer'   => 'Your aquarium should be fully cycled with stable water parameters before adding new fish. We recommend testing your water for ammonia, nitrite, nitrate, and pH. Each fish species has specific water parameter requirements, which we provide in our care guides. We also recommend a quarantine tank for new arrivals.',
    ),
    array(
        'question' => 'Do you offer wholesale pricing?',
        'answer'   => 'Yes, we offer wholesale pricing for pet stores, public aquariums, and other businesses. Please contact our wholesale department at wholesale@aqualuxe.com with your business information to receive our wholesale catalog and pricing.',
    ),
    array(
        'question' => 'Can I visit your facility?',
        'answer'   => 'Yes, we offer guided tours of our breeding facilities by appointment. Tours are available for hobbyists, schools, and aquarium clubs. Please contact us at least two weeks in advance to schedule a tour.',
    ),
);

// Filter FAQ items through a hook to allow customization
$faq_items = apply_filters( 'aqualuxe_contact_faq_items', $faq_items );

// Return if no FAQ items
if ( empty( $faq_items ) ) {
    return;
}
?>

<section class="contact-faq-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title"><?php echo esc_html( $faq_title ); ?></h2>
            <div class="section-subtitle"><?php echo wp_kses_post( $faq_subtitle ); ?></div>
        </div>
        
        <div class="faq-container">
            <div class="faq-list" id="faq-accordion">
                <?php foreach ( $faq_items as $index => $item ) : ?>
                    <div class="faq-item">
                        <div class="faq-question" id="faq-question-<?php echo esc_attr( $index ); ?>">
                            <h3>
                                <button class="faq-button" type="button" data-toggle="collapse" data-target="#faq-answer-<?php echo esc_attr( $index ); ?>" aria-expanded="<?php echo $index === 0 ? 'true' : 'false'; ?>" aria-controls="faq-answer-<?php echo esc_attr( $index ); ?>">
                                    <?php echo esc_html( $item['question'] ); ?>
                                    <span class="faq-icon"></span>
                                </button>
                            </h3>
                        </div>
                        
                        <div id="faq-answer-<?php echo esc_attr( $index ); ?>" class="faq-answer collapse <?php echo $index === 0 ? 'show' : ''; ?>" aria-labelledby="faq-question-<?php echo esc_attr( $index ); ?>">
                            <div class="faq-answer-inner">
                                <?php echo wpautop( esc_html( $item['answer'] ) ); ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        
        <div class="faq-footer">
            <p><?php esc_html_e( 'Still have questions?', 'aqualuxe' ); ?></p>
            <a href="#contact-form" class="btn btn-primary"><?php esc_html_e( 'Contact Us', 'aqualuxe' ); ?></a>
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const faqButtons = document.querySelectorAll('.faq-button');
        
        faqButtons.forEach(button => {
            button.addEventListener('click', function() {
                const expanded = this.getAttribute('aria-expanded') === 'true';
                
                // Close all FAQ items
                faqButtons.forEach(btn => {
                    btn.setAttribute('aria-expanded', 'false');
                    const target = document.querySelector(btn.getAttribute('data-target'));
                    target.classList.remove('show');
                });
                
                // Open the clicked FAQ item if it was closed
                if (!expanded) {
                    this.setAttribute('aria-expanded', 'true');
                    const target = document.querySelector(this.getAttribute('data-target'));
                    target.classList.add('show');
                }
            });
        });
    });
</script>