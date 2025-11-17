<?php
/**
 * Wholesale Portal: Access Denied Message
 *
 * @package AquaLuxe
 */
?>
<div class="text-center bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-6 rounded-lg" role="alert">
    <h2 class="text-2xl font-bold mb-2"><?php esc_html_e( 'Access Restricted', 'aqualuxe' ); ?></h2>
    <p class="mb-4"><?php esc_html_e( 'This portal is for authorized wholesale partners only. Your current account does not have access.', 'aqualuxe' ); ?></p>
    <p>
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="font-bold underline"><?php esc_html_e( 'Return to Homepage', 'aqualuxe' ); ?></a>
        <?php esc_html_e( 'or', 'aqualuxe' ); ?>
        <a href="<?php echo esc_url( wp_logout_url( get_permalink() ) ); ?>" class="font-bold underline"><?php esc_html_e( 'log out', 'aqualuxe' ); ?></a>
        <?php esc_html_e( 'and try a different account.', 'aqualuxe' ); ?>
    </p>
</div>
