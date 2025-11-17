<?php
/**
 * Template Name: Wholesale Portal
 *
 * @package AquaLuxe
 */

get_header();
?>

<div id="primary" class="content-area py-12">
    <main id="main" class="site-main container mx-auto px-4">

        <header class="page-header mb-12">
            <h1 class="page-title text-4xl font-bold text-center"><?php esc_html_e( 'Wholesale Partner Portal', 'aqualuxe' ); ?></h1>
        </header><!-- .page-header -->

        <?php
        if ( is_user_logged_in() ) {
            // Check if the user has a wholesale role or capability.
            // This is a simplified check. A real implementation would be more robust.
            $user = wp_get_current_user();
            if ( in_array( 'wholesale_customer', (array) $user->roles ) || current_user_can( 'edit_wholesale_accounts' ) ) {
                // User is a logged-in wholesale customer, show the dashboard.
                get_template_part( 'template-parts/wholesale/dashboard' );
            } else {
                // User is logged in but not a wholesale customer.
                get_template_part( 'template-parts/wholesale/access-denied' );
            }
        } else {
            // User is not logged in, show login and registration forms.
            get_template_part( 'template-parts/wholesale/login-register' );
        }
        ?>

    </main><!-- #main -->
</div><!-- #primary -->

<?php
get_footer();
