<?php
/**
 * Shipping & Returns Page FAQ Section
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Get shipping FAQ settings from customizer or use defaults
$shipping_faq_title = get_theme_mod( 'aqualuxe_shipping_faq_title', 'Frequently Asked Questions' );
$shipping_faq_subtitle = get_theme_mod( 'aqualuxe_shipping_faq_subtitle', 'Common questions about shipping, returns, and guarantees' );

// Demo FAQ items
$faq_items = array(
    array(
        'question' => 'How do you ensure fish survive shipping?',
        'answer'   => 'We use specialized packaging with insulated boxes, heat or cold packs (depending on season), and oxygen-filled bags. Each fish is carefully packaged to minimize stress. We also use water conditioners and ship via overnight services to ensure minimal transit time.',
    ),
    array(
        'question' => 'What happens if my fish arrive dead?',
        'answer'   => 'If your fish arrive dead or severely stressed, take clear photos within 2 hours of delivery and contact our customer service team immediately. We\'ll provide a replacement or refund under our Live Arrival Guarantee.',
    ),
    array(
        'question' => 'Can I return fish if they don\'t work in my aquarium?',
        'answer'   => 'We do not accept returns of live fish unless they arrive dead or diseased. This is why we emphasize research and compatibility before purchase. Our support team is always available to help you select appropriate species for your setup.',
    ),
    array(
        'question' => 'How do I properly acclimate my new fish?',
        'answer'   => 'We recommend the drip acclimation method for most species. Float the sealed bag in your aquarium for 15 minutes to equalize temperature, then transfer the fish to a container and slowly add aquarium water over 30-60 minutes before transferring the fish to the tank. Detailed instructions come with every fish shipment.',
    ),
    array(
        'question' => 'Do you ship internationally?',
        'answer'   => 'Yes, we ship to over 50 countries worldwide. International shipping requires additional documentation and may be subject to customs regulations in your country. Please contact us before placing an international order so we can provide you with specific information for your location.',
    ),
    array(
        'question' => 'How long will it take to receive my refund?',
        'answer'   => 'Once we receive and inspect your return, refunds are typically processed within 3 business days. It may take an additional 2-5 business days for the refund to appear in your account, depending on your payment method and financial institution.',
    ),
    array(
        'question' => 'What is the AquaLuxe Club and how do I join?',
        'answer'   => 'AquaLuxe Club is our loyalty program that offers enhanced guarantees, exclusive discounts, early access to new arrivals, and other benefits. You can join during checkout by selecting the membership option or from your account dashboard. There\'s an annual fee of $49.95, which quickly pays for itself through benefits and discounts.',
    ),
    array(
        'question' => 'Do you offer expedited shipping for all products?',
        'answer'   => 'Expedited shipping is available for most products. Live fish require overnight or priority overnight shipping to ensure their health and safety. Some large or heavy items may have shipping restrictions or additional fees.',
    ),
);

// Filter FAQ items through a hook to allow customization
$faq_items = apply_filters( 'aqualuxe_shipping_faq_items', $faq_items );

// Return if no FAQ items
if ( empty( $faq_items ) ) {
    return;
}
?>

<section class="shipping-faq-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title"><?php echo esc_html( $shipping_faq_title ); ?></h2>
            <div class="section-subtitle"><?php echo wp_kses_post( $shipping_faq_subtitle ); ?></div>
        </div>
        
        <div class="faq-container">
            <div class="faq-list" id="shipping-faq-accordion">
                <?php foreach ( $faq_items as $index => $item ) : ?>
                    <div class="faq-item">
                        <div class="faq-question" id="shipping-faq-question-<?php echo esc_attr( $index ); ?>">
                            <h3>
                                <button class="faq-button" type="button" data-toggle="collapse" data-target="#shipping-faq-answer-<?php echo esc_attr( $index ); ?>" aria-expanded="<?php echo $index === 0 ? 'true' : 'false'; ?>" aria-controls="shipping-faq-answer-<?php echo esc_attr( $index ); ?>">
                                    <?php echo esc_html( $item['question'] ); ?>
                                    <span class="faq-icon"></span>
                                </button>
                            </h3>
                        </div>
                        
                        <div id="shipping-faq-answer-<?php echo esc_attr( $index ); ?>" class="faq-answer collapse <?php echo $index === 0 ? 'show' : ''; ?>" aria-labelledby="shipping-faq-question-<?php echo esc_attr( $index ); ?>">
                            <div class="faq-answer-inner">
                                <?php echo wpautop( esc_html( $item['answer'] ) ); ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        
        <div class="faq-footer">
            <p><?php esc_html_e( 'Still have questions about shipping or returns?', 'aqualuxe' ); ?></p>
            <a href="<?php echo esc_url( get_permalink( get_page_by_path( 'contact' ) ) ); ?>" class="btn btn-primary"><?php esc_html_e( 'Contact Us', 'aqualuxe' ); ?></a>
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const faqButtons = document.querySelectorAll('.shipping-faq-section .faq-button');
        
        faqButtons.forEach(button => {
            button.addEventListener('click', function() {
                const expanded = this.getAttribute('aria-expanded') === 'true';
                
                // Toggle the current FAQ item
                this.setAttribute('aria-expanded', !expanded);
                const target = document.querySelector(this.getAttribute('data-target'));
                target.classList.toggle('show');
            });
        });
    });
</script>