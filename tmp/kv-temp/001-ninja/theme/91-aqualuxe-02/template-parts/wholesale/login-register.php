<?php
/**
 * Wholesale Portal: Login & Registration Forms
 *
 * @package AquaLuxe
 */
?>
<div class="grid grid-cols-1 md:grid-cols-2 gap-16">
    
    <!-- Login Form -->
    <div class="wholesale-login">
        <h2 class="text-2xl font-bold mb-6"><?php esc_html_e( 'Partner Login', 'aqualuxe' ); ?></h2>
        <?php wp_login_form( array(
            'redirect' => get_permalink(),
            'label_username' => __( 'Email Address', 'aqualuxe' ),
            'label_log_in' => __( 'Log In', 'aqualuxe' ),
            'remember' => true,
        ) ); ?>
        <a class="text-sm text-primary hover:underline mt-4 block" href="<?php echo esc_url( wp_lostpassword_url() ); ?>">
            <?php esc_html_e( 'Lost your password?', 'aqualuxe' ); ?>
        </a>
    </div>

    <!-- Registration/Application Form -->
    <div class="wholesale-register">
        <h2 class="text-2xl font-bold mb-6"><?php esc_html_e( 'Apply for a Wholesale Account', 'aqualuxe' ); ?></h2>
        
        <?php
        // Display feedback messages
        $status = $_GET['status'] ?? '';
        if ( $status === 'success' ) {
            echo '<div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert"><p>' . esc_html__( 'Thank you for your application! We have sent you an email with your login details. Our team will review your account and notify you upon approval.', 'aqualuxe' ) . '</p></div>';
        } elseif ( $status === 'email_exists' ) {
            echo '<div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert"><p>' . esc_html__( 'This email address is already registered. Please log in or use a different email.', 'aqualuxe' ) . '</p></div>';
        } elseif ( $status === 'error' || $status === 'user_error' ) {
            echo '<div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert"><p>' . esc_html__( 'An error occurred. Please fill out all required fields and try again.', 'aqualuxe' ) . '</p></div>';
        }
        ?>

        <p class="mb-4 text-gray-700"><?php esc_html_e( 'Submit your application to access exclusive wholesale pricing and benefits. Our team will review your application and respond within 2-3 business days.', 'aqualuxe' ); ?></p>
        
        <form id="wholesale-application-form" action="<?php echo esc_url( admin_url('admin-post.php') ); ?>" method="POST" class="space-y-4">
            <div>
                <label for="company_name" class="block text-sm font-medium text-gray-700"><?php esc_html_e( 'Company Name', 'aqualuxe' ); ?></label>
                <input type="text" name="company_name" id="company_name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
            </div>
             <div>
                <label for="contact_name" class="block text-sm font-medium text-gray-700"><?php esc_html_e( 'Contact Name', 'aqualuxe' ); ?></label>
                <input type="text" name="contact_name" id="contact_name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
            </div>
            <div>
                <label for="user_email" class="block text-sm font-medium text-gray-700"><?php esc_html_e( 'Email Address', 'aqualuxe' ); ?></label>
                <input type="email" name="user_email" id="user_email" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
            </div>
            <div>
                <label for="business_website" class="block text-sm font-medium text-gray-700"><?php esc_html_e( 'Business Website', 'aqualuxe' ); ?></label>
                <input type="url" name="business_website" id="business_website" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            <?php wp_nonce_field( 'aqualuxe_wholesale_application' ); ?>
            <input type="hidden" name="action" value="wholesale_application">
            <div>
                <button type="submit" class="w-full bg-secondary hover:bg-secondary-dark text-white font-bold py-3 px-4 rounded-lg transition duration-300">
                    <?php esc_html_e( 'Submit Application', 'aqualuxe' ); ?>
                </button>
            </div>
        </form>
    </div>

</div>
