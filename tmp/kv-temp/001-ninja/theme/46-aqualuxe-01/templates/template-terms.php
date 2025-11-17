<?php
/**
 * Template Name: Terms and Conditions
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

<div class="terms-container container mx-auto px-4 py-8">
    <header class="page-header mb-8">
        <h1 class="page-title text-3xl font-bold mb-4"><?php the_title(); ?></h1>
        <div class="page-description text-gray-600">
            <?php esc_html_e( 'Please read these terms and conditions carefully before using our website and services.', 'aqualuxe' ); ?>
        </div>
    </header>

    <div class="terms-content prose max-w-none">
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
         * Hook: aqualuxe_terms_content
         * 
         * @hooked aqualuxe_terms_last_updated - 10
         * @hooked aqualuxe_terms_contact_info - 20
         */
        do_action( 'aqualuxe_terms_content' );
        ?>
        
        <div class="terms-acceptance mt-8 p-4 bg-gray-100 rounded-lg">
            <h3 class="text-xl font-semibold mb-2"><?php esc_html_e( 'Acceptance of Terms', 'aqualuxe' ); ?></h3>
            <p><?php esc_html_e( 'By accessing and using this website, you acknowledge that you have read, understood, and agree to be bound by these Terms and Conditions.', 'aqualuxe' ); ?></p>
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