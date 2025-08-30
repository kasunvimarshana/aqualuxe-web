<?php
/**
 * Shipping & Returns Page Returns Policy Section
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Get returns policy settings from customizer or use defaults
$returns_title = get_theme_mod( 'aqualuxe_returns_title', 'Returns Policy' );
$returns_subtitle = get_theme_mod( 'aqualuxe_returns_subtitle', 'Our fair and transparent returns process' );
?>

<section class="returns-policy-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title"><?php echo esc_html( $returns_title ); ?></h2>
            <div class="section-subtitle"><?php echo wp_kses_post( $returns_subtitle ); ?></div>
        </div>
        
        <div class="returns-policy-content">
            <div class="returns-categories">
                <div class="return-category">
                    <div class="category-icon">
                        <span class="icon-fish"></span>
                    </div>
                    <h3><?php esc_html_e( 'Live Fish Returns', 'aqualuxe' ); ?></h3>
                    <div class="category-content">
                        <p><?php esc_html_e( 'Due to the nature of live animals, we do not accept returns of live fish unless they arrive dead or severely stressed. In such cases, our Live Arrival Guarantee applies.', 'aqualuxe' ); ?></p>
                        <p><?php esc_html_e( 'If your fish arrive dead or severely stressed, please take clear photos within 2 hours of delivery and contact our customer service team immediately. We will provide a replacement or refund at our discretion.', 'aqualuxe' ); ?></p>
                        <p><?php esc_html_e( 'For fish that die after the initial 2-hour period but within our 7-day health guarantee period, we require water parameter test results and photos to process a claim.', 'aqualuxe' ); ?></p>
                    </div>
                </div>
                
                <div class="return-category">
                    <div class="category-icon">
                        <span class="icon-plant"></span>
                    </div>
                    <h3><?php esc_html_e( 'Live Plant Returns', 'aqualuxe' ); ?></h3>
                    <div class="category-content">
                        <p><?php esc_html_e( 'Live plants are covered by our Live Arrival Guarantee. If plants arrive damaged or dead, please take clear photos within 2 hours of delivery and contact our customer service team.', 'aqualuxe' ); ?></p>
                        <p><?php esc_html_e( 'We do not accept returns of healthy plants. Please research plant requirements before purchasing to ensure they are suitable for your aquarium conditions.', 'aqualuxe' ); ?></p>
                    </div>
                </div>
                
                <div class="return-category">
                    <div class="category-icon">
                        <span class="icon-equipment"></span>
                    </div>
                    <h3><?php esc_html_e( 'Equipment & Dry Goods', 'aqualuxe' ); ?></h3>
                    <div class="category-content">
                        <p><?php esc_html_e( 'Unused equipment and dry goods can be returned within 30 days of purchase for a full refund or exchange. Items must be in original packaging with all components included.', 'aqualuxe' ); ?></p>
                        <p><?php esc_html_e( 'Defective items can be returned within 30 days for replacement or refund. For items that fail after 30 days but within the manufacturer\'s warranty period, we will assist with warranty claims.', 'aqualuxe' ); ?></p>
                        <p><?php esc_html_e( 'Custom or special order items may not be eligible for return unless defective.', 'aqualuxe' ); ?></p>
                    </div>
                </div>
                
                <div class="return-category">
                    <div class="category-icon">
                        <span class="icon-food"></span>
                    </div>
                    <h3><?php esc_html_e( 'Food & Consumables', 'aqualuxe' ); ?></h3>
                    <div class="category-content">
                        <p><?php esc_html_e( 'For health and safety reasons, we cannot accept returns on opened food, medications, or other consumable products.', 'aqualuxe' ); ?></p>
                        <p><?php esc_html_e( 'If you receive a damaged or expired product, please contact us within 7 days of delivery with photos of the product and packaging for a replacement or refund.', 'aqualuxe' ); ?></p>
                    </div>
                </div>
            </div>
            
            <div class="returns-process">
                <h3><?php esc_html_e( 'Returns Process', 'aqualuxe' ); ?></h3>
                <ol class="process-steps">
                    <li>
                        <h4><?php esc_html_e( 'Contact Customer Service', 'aqualuxe' ); ?></h4>
                        <p><?php esc_html_e( 'Email support@aqualuxe.com or call 1-800-555-FISH with your order number and details about the return.', 'aqualuxe' ); ?></p>
                    </li>
                    <li>
                        <h4><?php esc_html_e( 'Receive Return Authorization', 'aqualuxe' ); ?></h4>
                        <p><?php esc_html_e( 'Our team will provide a Return Authorization (RA) number and return instructions.', 'aqualuxe' ); ?></p>
                    </li>
                    <li>
                        <h4><?php esc_html_e( 'Package the Item', 'aqualuxe' ); ?></h4>
                        <p><?php esc_html_e( 'Securely package the item in its original packaging if possible. Include the RA number on the outside of the package.', 'aqualuxe' ); ?></p>
                    </li>
                    <li>
                        <h4><?php esc_html_e( 'Ship the Return', 'aqualuxe' ); ?></h4>
                        <p><?php esc_html_e( 'Ship the item to the address provided in the return instructions. We recommend using a trackable shipping method.', 'aqualuxe' ); ?></p>
                    </li>
                    <li>
                        <h4><?php esc_html_e( 'Refund or Replacement', 'aqualuxe' ); ?></h4>
                        <p><?php esc_html_e( 'Once we receive and inspect the return, we\'ll process your refund or send a replacement item. Refunds are issued to the original payment method.', 'aqualuxe' ); ?></p>
                    </li>
                </ol>
            </div>
            
            <div class="returns-notes">
                <h3><?php esc_html_e( 'Important Notes', 'aqualuxe' ); ?></h3>
                <ul>
                    <li><?php esc_html_e( 'Return shipping costs are the responsibility of the customer unless the return is due to our error or a defective product.', 'aqualuxe' ); ?></li>
                    <li><?php esc_html_e( 'Items damaged during return shipping due to inadequate packaging may not be eligible for refund.', 'aqualuxe' ); ?></li>
                    <li><?php esc_html_e( 'Refunds typically take 5-7 business days to process after we receive the return.', 'aqualuxe' ); ?></li>
                    <li><?php esc_html_e( 'For items purchased during promotions or with discounts, refunds will be based on the actual amount paid.', 'aqualuxe' ); ?></li>
                </ul>
            </div>
        </div>
    </div>
</section>