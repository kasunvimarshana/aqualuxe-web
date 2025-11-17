<?php
/**
 * Template Name: Cookie Policy
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

<div class="cookie-policy-container container mx-auto px-4 py-8">
    <header class="page-header mb-8">
        <h1 class="page-title text-3xl font-bold mb-4"><?php the_title(); ?></h1>
        <div class="page-description text-gray-600">
            <?php esc_html_e( 'Information about how we use cookies and similar technologies on our website.', 'aqualuxe' ); ?>
        </div>
    </header>

    <div class="cookie-policy-content prose max-w-none">
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
         * Hook: aqualuxe_cookie_policy_content
         * 
         * @hooked aqualuxe_cookie_policy_last_updated - 10
         * @hooked aqualuxe_cookie_policy_table - 20
         */
        do_action( 'aqualuxe_cookie_policy_content' );
        ?>
        
        <div class="cookie-types mt-8">
            <h2 class="text-2xl font-semibold mb-4"><?php esc_html_e( 'Types of Cookies We Use', 'aqualuxe' ); ?></h2>
            
            <div class="cookie-type p-4 mb-4 bg-gray-50 rounded-lg">
                <h3 class="text-xl font-medium mb-2"><?php esc_html_e( 'Essential Cookies', 'aqualuxe' ); ?></h3>
                <p><?php esc_html_e( 'These cookies are necessary for the website to function and cannot be switched off in our systems. They are usually only set in response to actions made by you which amount to a request for services, such as setting your privacy preferences, logging in or filling in forms.', 'aqualuxe' ); ?></p>
            </div>
            
            <div class="cookie-type p-4 mb-4 bg-gray-50 rounded-lg">
                <h3 class="text-xl font-medium mb-2"><?php esc_html_e( 'Performance Cookies', 'aqualuxe' ); ?></h3>
                <p><?php esc_html_e( 'These cookies allow us to count visits and traffic sources so we can measure and improve the performance of our site. They help us to know which pages are the most and least popular and see how visitors move around the site.', 'aqualuxe' ); ?></p>
            </div>
            
            <div class="cookie-type p-4 mb-4 bg-gray-50 rounded-lg">
                <h3 class="text-xl font-medium mb-2"><?php esc_html_e( 'Functional Cookies', 'aqualuxe' ); ?></h3>
                <p><?php esc_html_e( 'These cookies enable the website to provide enhanced functionality and personalization. They may be set by us or by third party providers whose services we have added to our pages.', 'aqualuxe' ); ?></p>
            </div>
            
            <div class="cookie-type p-4 mb-4 bg-gray-50 rounded-lg">
                <h3 class="text-xl font-medium mb-2"><?php esc_html_e( 'Targeting Cookies', 'aqualuxe' ); ?></h3>
                <p><?php esc_html_e( 'These cookies may be set through our site by our advertising partners. They may be used by those companies to build a profile of your interests and show you relevant adverts on other sites.', 'aqualuxe' ); ?></p>
            </div>
        </div>
        
        <div class="cookie-management mt-8">
            <h2 class="text-2xl font-semibold mb-4"><?php esc_html_e( 'Managing Your Cookie Preferences', 'aqualuxe' ); ?></h2>
            <p><?php esc_html_e( 'You can set your cookie preferences using our cookie consent tool. You can also manage cookies through your browser settings.', 'aqualuxe' ); ?></p>
            
            <button id="cookie-settings-btn" class="mt-4 px-6 py-2 bg-primary text-white rounded-md hover:bg-primary-dark transition-colors">
                <?php esc_html_e( 'Cookie Settings', 'aqualuxe' ); ?>
            </button>
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