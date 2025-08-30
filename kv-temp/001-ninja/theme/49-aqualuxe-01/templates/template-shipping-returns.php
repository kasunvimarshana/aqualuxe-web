<?php
/**
 * Template Name: Shipping & Returns Policy
 * Template Post Type: page
 *
 * @package AquaLuxe
 */

get_header();

/**
 * Hook: aqualuxe_before_main_content
 *
 * @hooked aqualuxe_output_content_wrapper - 10
 */
do_action( 'aqualuxe_before_main_content' );
?>

<div class="shipping-returns-container container mx-auto px-4 py-8">
    <header class="page-header mb-8">
        <h1 class="page-title text-3xl font-bold mb-4"><?php the_title(); ?></h1>
        <div class="page-description text-gray-600">
            <?php esc_html_e( 'Information about our shipping methods, delivery times, and return procedures.', 'aqualuxe' ); ?>
        </div>
    </header>

    <div class="shipping-returns-content prose max-w-none">
        <?php
        while ( have_posts() ) :
            the_post();
            the_content();
            
            // If comments are open or we have at least one comment, load up the comment template.
            if ( comments_open() || get_comments_number() ) :
                comments_template();
            endif;
        endwhile; // End of the loop.
        ?>
        
        <?php
        /**
         * Hook: aqualuxe_shipping_returns_content
         * 
         * @hooked aqualuxe_shipping_returns_last_updated - 10
         */
        do_action( 'aqualuxe_shipping_returns_content' );
        ?>
        
        <div class="shipping-info mt-8">
            <h2 class="text-2xl font-semibold mb-4"><?php esc_html_e( 'Shipping Information', 'aqualuxe' ); ?></h2>
            
            <div class="shipping-methods p-4 mb-6 bg-gray-50 rounded-lg">
                <h3 class="text-xl font-medium mb-3"><?php esc_html_e( 'Shipping Methods', 'aqualuxe' ); ?></h3>
                
                <?php if ( class_exists( 'WooCommerce' ) ) : ?>
                    <?php
                    // Get available shipping methods from WooCommerce if active
                    $shipping_zones = WC_Shipping_Zones::get_zones();
                    if ( ! empty( $shipping_zones ) ) :
                    ?>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-gray-100">
                                        <th class="py-2 px-4 border-b"><?php esc_html_e( 'Shipping Method', 'aqualuxe' ); ?></th>
                                        <th class="py-2 px-4 border-b"><?php esc_html_e( 'Estimated Delivery Time', 'aqualuxe' ); ?></th>
                                        <th class="py-2 px-4 border-b"><?php esc_html_e( 'Cost', 'aqualuxe' ); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ( $shipping_zones as $zone_id => $zone ) : ?>
                                        <?php foreach ( $zone['shipping_methods'] as $method ) : ?>
                                            <tr class="border-b hover:bg-gray-50">
                                                <td class="py-2 px-4"><?php echo esc_html( $method->get_title() ); ?></td>
                                                <td class="py-2 px-4">
                                                    <?php 
                                                    // This would need to be customized based on actual shipping methods
                                                    switch ( $method->id ) {
                                                        case 'flat_rate':
                                                            esc_html_e( '3-5 business days', 'aqualuxe' );
                                                            break;
                                                        case 'free_shipping':
                                                            esc_html_e( '5-7 business days', 'aqualuxe' );
                                                            break;
                                                        case 'local_pickup':
                                                            esc_html_e( 'Same day', 'aqualuxe' );
                                                            break;
                                                        default:
                                                            esc_html_e( 'Varies', 'aqualuxe' );
                                                    }
                                                    ?>
                                                </td>
                                                <td class="py-2 px-4">
                                                    <?php 
                                                    if ( $method->id === 'free_shipping' ) {
                                                        esc_html_e( 'Free', 'aqualuxe' );
                                                    } elseif ( $method->id === 'flat_rate' ) {
                                                        echo wp_kses_post( wc_price( $method->cost ?? 0 ) );
                                                    } else {
                                                        esc_html_e( 'Calculated at checkout', 'aqualuxe' );
                                                    }
                                                    ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else : ?>
                        <p><?php esc_html_e( 'Please contact us for shipping information.', 'aqualuxe' ); ?></p>
                    <?php endif; ?>
                <?php else : ?>
                    <p><?php esc_html_e( 'Please contact us for shipping information.', 'aqualuxe' ); ?></p>
                <?php endif; ?>
            </div>
            
            <div class="shipping-restrictions p-4 mb-6 bg-gray-50 rounded-lg">
                <h3 class="text-xl font-medium mb-3"><?php esc_html_e( 'Shipping Restrictions', 'aqualuxe' ); ?></h3>
                <p><?php esc_html_e( 'We currently ship to the following countries:', 'aqualuxe' ); ?></p>
                
                <?php if ( class_exists( 'WooCommerce' ) ) : ?>
                    <?php
                    // Get shipping countries from WooCommerce if active
                    $countries = WC()->countries->get_shipping_countries();
                    if ( ! empty( $countries ) ) :
                    ?>
                        <ul class="list-disc pl-5 mt-2">
                            <?php foreach ( $countries as $code => $country ) : ?>
                                <li><?php echo esc_html( $country ); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else : ?>
                        <p><?php esc_html_e( 'Please contact us for information about shipping destinations.', 'aqualuxe' ); ?></p>
                    <?php endif; ?>
                <?php else : ?>
                    <p><?php esc_html_e( 'Please contact us for information about shipping destinations.', 'aqualuxe' ); ?></p>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="returns-info mt-8">
            <h2 class="text-2xl font-semibold mb-4"><?php esc_html_e( 'Returns & Refunds', 'aqualuxe' ); ?></h2>
            
            <div class="returns-policy p-4 mb-6 bg-gray-50 rounded-lg">
                <h3 class="text-xl font-medium mb-3"><?php esc_html_e( 'Return Policy', 'aqualuxe' ); ?></h3>
                <p><?php esc_html_e( 'We offer a 30-day return policy for most items. To be eligible for a return, your item must be unused and in the same condition that you received it. It must also be in the original packaging.', 'aqualuxe' ); ?></p>
                
                <h4 class="text-lg font-medium mt-4 mb-2"><?php esc_html_e( 'Non-Returnable Items', 'aqualuxe' ); ?></h4>
                <p><?php esc_html_e( 'The following items cannot be returned:', 'aqualuxe' ); ?></p>
                <ul class="list-disc pl-5 mt-2">
                    <li><?php esc_html_e( 'Gift cards', 'aqualuxe' ); ?></li>
                    <li><?php esc_html_e( 'Downloadable products', 'aqualuxe' ); ?></li>
                    <li><?php esc_html_e( 'Personalized items', 'aqualuxe' ); ?></li>
                    <li><?php esc_html_e( 'Personal care items (for hygiene reasons)', 'aqualuxe' ); ?></li>
                </ul>
            </div>
            
            <div class="refund-process p-4 mb-6 bg-gray-50 rounded-lg">
                <h3 class="text-xl font-medium mb-3"><?php esc_html_e( 'Refund Process', 'aqualuxe' ); ?></h3>
                <p><?php esc_html_e( 'Once we receive your item, we will inspect it and notify you that we have received your returned item. We will immediately notify you on the status of your refund after inspecting the item.', 'aqualuxe' ); ?></p>
                <p class="mt-2"><?php esc_html_e( 'If your return is approved, we will initiate a refund to your credit card (or original method of payment). You will receive the credit within a certain amount of days, depending on your card issuer\'s policies.', 'aqualuxe' ); ?></p>
                
                <h4 class="text-lg font-medium mt-4 mb-2"><?php esc_html_e( 'Shipping Returns', 'aqualuxe' ); ?></h4>
                <p><?php esc_html_e( 'You will be responsible for paying for your own shipping costs for returning your item. Shipping costs are non-refundable.', 'aqualuxe' ); ?></p>
            </div>
            
            <div class="contact-support p-4 bg-gray-50 rounded-lg">
                <h3 class="text-xl font-medium mb-3"><?php esc_html_e( 'Need Help?', 'aqualuxe' ); ?></h3>
                <p><?php esc_html_e( 'If you have any questions about our shipping or return policies, please contact our customer support team:', 'aqualuxe' ); ?></p>
                <div class="contact-methods mt-3">
                    <p><?php esc_html_e( 'Email:', 'aqualuxe' ); ?> <a href="mailto:<?php echo esc_attr( get_option( 'admin_email' ) ); ?>" class="text-primary hover:underline"><?php echo esc_html( get_option( 'admin_email' ) ); ?></a></p>
                    <p><?php esc_html_e( 'Phone:', 'aqualuxe' ); ?> <?php echo esc_html( get_option( 'aqualuxe_phone', '+1 (555) 123-4567' ) ); ?></p>
                    <p><?php esc_html_e( 'Contact Form:', 'aqualuxe' ); ?> <a href="<?php echo esc_url( get_permalink( get_page_by_path( 'contact' ) ) ); ?>" class="text-primary hover:underline"><?php esc_html_e( 'Contact Us', 'aqualuxe' ); ?></a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
/**
 * Hook: aqualuxe_after_main_content
 *
 * @hooked aqualuxe_output_content_wrapper_end - 10
 */
do_action( 'aqualuxe_after_main_content' );

get_footer();