<?php
/**
 * Template Name: GDPR Compliance
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

<div class="gdpr-container container mx-auto px-4 py-8">
    <header class="page-header mb-8">
        <h1 class="page-title text-3xl font-bold mb-4"><?php the_title(); ?></h1>
        <div class="page-description text-gray-600">
            <?php esc_html_e( 'Information about how we comply with GDPR regulations and protect your data rights.', 'aqualuxe' ); ?>
        </div>
    </header>

    <div class="gdpr-content prose max-w-none">
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
         * Hook: aqualuxe_gdpr_content
         * 
         * @hooked aqualuxe_gdpr_last_updated - 10
         * @hooked aqualuxe_gdpr_contact_info - 20
         */
        do_action( 'aqualuxe_gdpr_content' );
        ?>
        
        <div class="gdpr-rights mt-8">
            <h2 class="text-2xl font-semibold mb-4"><?php esc_html_e( 'Your Data Rights Under GDPR', 'aqualuxe' ); ?></h2>
            
            <div class="gdpr-right p-4 mb-4 bg-gray-50 rounded-lg">
                <h3 class="text-xl font-medium mb-2"><?php esc_html_e( 'Right to Access', 'aqualuxe' ); ?></h3>
                <p><?php esc_html_e( 'You have the right to request copies of your personal data. We may charge you a small fee for this service.', 'aqualuxe' ); ?></p>
            </div>
            
            <div class="gdpr-right p-4 mb-4 bg-gray-50 rounded-lg">
                <h3 class="text-xl font-medium mb-2"><?php esc_html_e( 'Right to Rectification', 'aqualuxe' ); ?></h3>
                <p><?php esc_html_e( 'You have the right to request that we correct any information you believe is inaccurate. You also have the right to request we complete information you believe is incomplete.', 'aqualuxe' ); ?></p>
            </div>
            
            <div class="gdpr-right p-4 mb-4 bg-gray-50 rounded-lg">
                <h3 class="text-xl font-medium mb-2"><?php esc_html_e( 'Right to Erasure', 'aqualuxe' ); ?></h3>
                <p><?php esc_html_e( 'You have the right to request that we erase your personal data, under certain conditions.', 'aqualuxe' ); ?></p>
            </div>
            
            <div class="gdpr-right p-4 mb-4 bg-gray-50 rounded-lg">
                <h3 class="text-xl font-medium mb-2"><?php esc_html_e( 'Right to Restrict Processing', 'aqualuxe' ); ?></h3>
                <p><?php esc_html_e( 'You have the right to request that we restrict the processing of your personal data, under certain conditions.', 'aqualuxe' ); ?></p>
            </div>
            
            <div class="gdpr-right p-4 mb-4 bg-gray-50 rounded-lg">
                <h3 class="text-xl font-medium mb-2"><?php esc_html_e( 'Right to Object to Processing', 'aqualuxe' ); ?></h3>
                <p><?php esc_html_e( 'You have the right to object to our processing of your personal data, under certain conditions.', 'aqualuxe' ); ?></p>
            </div>
            
            <div class="gdpr-right p-4 mb-4 bg-gray-50 rounded-lg">
                <h3 class="text-xl font-medium mb-2"><?php esc_html_e( 'Right to Data Portability', 'aqualuxe' ); ?></h3>
                <p><?php esc_html_e( 'You have the right to request that we transfer the data that we have collected to another organization, or directly to you, under certain conditions.', 'aqualuxe' ); ?></p>
            </div>
        </div>
        
        <div class="gdpr-request mt-8">
            <h2 class="text-2xl font-semibold mb-4"><?php esc_html_e( 'Making a Request', 'aqualuxe' ); ?></h2>
            <p><?php esc_html_e( 'If you would like to exercise any of these rights, please contact us through our:', 'aqualuxe' ); ?></p>
            
            <div class="contact-methods mt-4">
                <p><?php esc_html_e( 'Email:', 'aqualuxe' ); ?> <a href="mailto:<?php echo esc_attr( get_option( 'admin_email' ) ); ?>" class="text-primary hover:underline"><?php echo esc_html( get_option( 'admin_email' ) ); ?></a></p>
                <p><?php esc_html_e( 'Contact Form:', 'aqualuxe' ); ?> <a href="<?php echo esc_url( get_permalink( get_page_by_path( 'contact' ) ) ); ?>" class="text-primary hover:underline"><?php esc_html_e( 'Contact Us', 'aqualuxe' ); ?></a></p>
            </div>
            
            <p class="mt-4"><?php esc_html_e( 'We will respond to your request within 30 days.', 'aqualuxe' ); ?></p>
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