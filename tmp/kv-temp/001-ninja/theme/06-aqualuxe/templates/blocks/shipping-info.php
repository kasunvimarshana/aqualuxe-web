<?php
/**
 * Shipping & Returns Page Shipping Information Section
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Get shipping info settings from customizer or use defaults
$shipping_info_title = get_theme_mod( 'aqualuxe_shipping_info_title', 'Shipping Information' );
$shipping_info_subtitle = get_theme_mod( 'aqualuxe_shipping_info_subtitle', 'How we ensure safe delivery of your aquatic treasures' );
?>

<section class="shipping-info-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title"><?php echo esc_html( $shipping_info_title ); ?></h2>
            <div class="section-subtitle"><?php echo wp_kses_post( $shipping_info_subtitle ); ?></div>
        </div>
        
        <div class="shipping-info-content">
            <div class="shipping-info-text">
                <h3><?php esc_html_e( 'Our Shipping Process', 'aqualuxe' ); ?></h3>
                <p><?php esc_html_e( 'At AquaLuxe, we take extraordinary care to ensure your fish and aquatic products arrive in perfect condition. Our specialized shipping methods have been refined over years of experience to minimize stress and maintain optimal conditions during transit.', 'aqualuxe' ); ?></p>
                
                <h3><?php esc_html_e( 'Shipping Schedule', 'aqualuxe' ); ?></h3>
                <p><?php esc_html_e( 'We ship live fish Monday through Wednesday to ensure they don\'t get held over the weekend. Orders placed by 2:00 PM EST will be processed the same day. Plants, dry goods, and equipment can be shipped any day of the week.', 'aqualuxe' ); ?></p>
                
                <h3><?php esc_html_e( 'Shipping Methods', 'aqualuxe' ); ?></h3>
                <p><?php esc_html_e( 'For live fish, we exclusively use overnight or express shipping services to minimize transit time. Dry goods can be shipped via standard shipping methods. International shipping is available to over 50 countries, with specific requirements varying by destination.', 'aqualuxe' ); ?></p>
            </div>
            
            <div class="shipping-methods-table">
                <h3><?php esc_html_e( 'Domestic Shipping Options', 'aqualuxe' ); ?></h3>
                <table class="shipping-table">
                    <thead>
                        <tr>
                            <th><?php esc_html_e( 'Shipping Method', 'aqualuxe' ); ?></th>
                            <th><?php esc_html_e( 'Estimated Delivery', 'aqualuxe' ); ?></th>
                            <th><?php esc_html_e( 'Cost', 'aqualuxe' ); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php esc_html_e( 'Standard Shipping (Dry Goods Only)', 'aqualuxe' ); ?></td>
                            <td><?php esc_html_e( '3-5 Business Days', 'aqualuxe' ); ?></td>
                            <td><?php esc_html_e( '$8.95', 'aqualuxe' ); ?></td>
                        </tr>
                        <tr>
                            <td><?php esc_html_e( 'Express Shipping', 'aqualuxe' ); ?></td>
                            <td><?php esc_html_e( '2 Business Days', 'aqualuxe' ); ?></td>
                            <td><?php esc_html_e( '$19.95', 'aqualuxe' ); ?></td>
                        </tr>
                        <tr>
                            <td><?php esc_html_e( 'Overnight Shipping', 'aqualuxe' ); ?></td>
                            <td><?php esc_html_e( 'Next Business Day', 'aqualuxe' ); ?></td>
                            <td><?php esc_html_e( '$29.95', 'aqualuxe' ); ?></td>
                        </tr>
                        <tr>
                            <td><?php esc_html_e( 'Priority Overnight (Required for Live Fish)', 'aqualuxe' ); ?></td>
                            <td><?php esc_html_e( 'Next Business Day by 10:30 AM', 'aqualuxe' ); ?></td>
                            <td><?php esc_html_e( '$39.95', 'aqualuxe' ); ?></td>
                        </tr>
                    </tbody>
                </table>
                
                <div class="shipping-note">
                    <p><?php esc_html_e( 'Note: Free shipping is available on orders over $150 (excluding live fish).', 'aqualuxe' ); ?></p>
                </div>
            </div>
            
            <div class="international-shipping">
                <h3><?php esc_html_e( 'International Shipping', 'aqualuxe' ); ?></h3>
                <p><?php esc_html_e( 'We ship to over 50 countries worldwide. International shipping rates and delivery times vary by destination. Please note that international orders may be subject to customs duties and taxes, which are the responsibility of the recipient.', 'aqualuxe' ); ?></p>
                
                <div class="shipping-zones">
                    <div class="zone-item">
                        <h4><?php esc_html_e( 'Europe', 'aqualuxe' ); ?></h4>
                        <p><?php esc_html_e( 'Estimated Delivery: 2-3 Business Days', 'aqualuxe' ); ?></p>
                        <p><?php esc_html_e( 'Starting at $59.95', 'aqualuxe' ); ?></p>
                    </div>
                    
                    <div class="zone-item">
                        <h4><?php esc_html_e( 'Canada & Mexico', 'aqualuxe' ); ?></h4>
                        <p><?php esc_html_e( 'Estimated Delivery: 1-2 Business Days', 'aqualuxe' ); ?></p>
                        <p><?php esc_html_e( 'Starting at $49.95', 'aqualuxe' ); ?></p>
                    </div>
                    
                    <div class="zone-item">
                        <h4><?php esc_html_e( 'Asia Pacific', 'aqualuxe' ); ?></h4>
                        <p><?php esc_html_e( 'Estimated Delivery: 2-4 Business Days', 'aqualuxe' ); ?></p>
                        <p><?php esc_html_e( 'Starting at $79.95', 'aqualuxe' ); ?></p>
                    </div>
                    
                    <div class="zone-item">
                        <h4><?php esc_html_e( 'Rest of World', 'aqualuxe' ); ?></h4>
                        <p><?php esc_html_e( 'Estimated Delivery: 3-5 Business Days', 'aqualuxe' ); ?></p>
                        <p><?php esc_html_e( 'Starting at $99.95', 'aqualuxe' ); ?></p>
                    </div>
                </div>
                
                <div class="shipping-note">
                    <p><?php esc_html_e( 'Important: International customers should contact us before placing an order to confirm shipping availability to your location and to receive information about any required import permits or documentation.', 'aqualuxe' ); ?></p>
                </div>
            </div>
            
            <div class="shipping-packaging">
                <h3><?php esc_html_e( 'Our Packaging', 'aqualuxe' ); ?></h3>
                <div class="packaging-content">
                    <div class="packaging-image">
                        <img src="<?php echo esc_url( get_template_directory_uri() . '/demo-content/images/shipping-packaging.jpg' ); ?>" alt="<?php esc_attr_e( 'AquaLuxe Packaging', 'aqualuxe' ); ?>">
                    </div>
                    <div class="packaging-text">
                        <p><?php esc_html_e( 'We use specialized packaging designed specifically for shipping live aquatic species:', 'aqualuxe' ); ?></p>
                        <ul>
                            <li><?php esc_html_e( 'Insulated boxes to maintain stable temperatures', 'aqualuxe' ); ?></li>
                            <li><?php esc_html_e( 'Heat or cold packs (depending on season and destination)', 'aqualuxe' ); ?></li>
                            <li><?php esc_html_e( 'Oxygen-filled bags for each fish', 'aqualuxe' ); ?></li>
                            <li><?php esc_html_e( 'Protective barriers to prevent bag punctures', 'aqualuxe' ); ?></li>
                            <li><?php esc_html_e( 'Water conditioners and stress reducers', 'aqualuxe' ); ?></li>
                            <li><?php esc_html_e( 'Detailed unpacking and acclimation instructions', 'aqualuxe' ); ?></li>
                        </ul>
                        <p><?php esc_html_e( 'Our packaging is also environmentally responsible, using recyclable and biodegradable materials whenever possible.', 'aqualuxe' ); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>