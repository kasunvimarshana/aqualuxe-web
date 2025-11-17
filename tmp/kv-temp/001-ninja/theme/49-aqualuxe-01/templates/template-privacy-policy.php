<?php
/**
 * Template Name: Privacy Policy
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

<div class="privacy-policy-container container mx-auto px-4 py-8">
    <header class="page-header mb-8">
        <h1 class="page-title text-3xl font-bold mb-4"><?php the_title(); ?></h1>
        <div class="page-description text-gray-600">
            <?php esc_html_e( 'Our commitment to protecting your privacy and personal data.', 'aqualuxe' ); ?>
        </div>
    </header>

    <div class="privacy-policy-content prose max-w-none">
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
         * Hook: aqualuxe_privacy_policy_content
         * 
         * @hooked aqualuxe_privacy_policy_last_updated - 10
         * @hooked aqualuxe_privacy_policy_contact_info - 20
         */
        do_action( 'aqualuxe_privacy_policy_content' );
        ?>
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