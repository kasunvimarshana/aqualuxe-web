<?php
/**
 * My Account navigation
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/navigation.php.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package AquaLuxe
 * @version 2.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

do_action( 'woocommerce_before_account_navigation' );
?>

<nav class="woocommerce-MyAccount-navigation bg-white dark:bg-dark-light rounded-lg shadow-soft overflow-hidden mb-8">
    <div class="account-navigation-header bg-primary text-white p-4">
        <div class="account-user flex items-center">
            <div class="account-avatar w-12 h-12 rounded-full bg-white text-primary flex items-center justify-center text-xl mr-3">
                <?php echo esc_html( substr( $current_user->display_name, 0, 1 ) ); ?>
            </div>
            <div class="account-info">
                <div class="account-name font-bold">
                    <?php echo esc_html( $current_user->display_name ); ?>
                </div>
                <div class="account-email text-sm text-white/80">
                    <?php echo esc_html( $current_user->user_email ); ?>
                </div>
            </div>
        </div>
    </div>
    
    <ul class="account-menu divide-y divide-gray-200 dark:divide-gray-700">
        <?php foreach ( wc_get_account_menu_items() as $endpoint => $label ) : ?>
            <li class="<?php echo wc_get_account_menu_item_classes( $endpoint ); ?> account-menu-item">
                <a href="<?php echo esc_url( wc_get_account_endpoint_url( $endpoint ) ); ?>" class="block p-4 hover:bg-gray-50 dark:hover:bg-dark-medium transition-colors">
                    <div class="flex items-center">
                        <div class="account-menu-icon w-8 text-primary">
                            <?php
                            // Add icons for each menu item
                            switch ( $endpoint ) {
                                case 'dashboard':
                                    echo '<i class="fas fa-tachometer-alt"></i>';
                                    break;
                                case 'orders':
                                    echo '<i class="fas fa-shopping-bag"></i>';
                                    break;
                                case 'downloads':
                                    echo '<i class="fas fa-download"></i>';
                                    break;
                                case 'edit-address':
                                    echo '<i class="fas fa-map-marker-alt"></i>';
                                    break;
                                case 'edit-account':
                                    echo '<i class="fas fa-user-edit"></i>';
                                    break;
                                case 'customer-logout':
                                    echo '<i class="fas fa-sign-out-alt"></i>';
                                    break;
                                default:
                                    echo '<i class="fas fa-circle"></i>';
                            }
                            ?>
                        </div>
                        <div class="account-menu-label">
                            <?php echo esc_html( $label ); ?>
                        </div>
                    </div>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</nav>

<?php do_action( 'woocommerce_after_account_navigation' ); ?>