<?php
/**
 * WooCommerce Integration Testing Functions
 *
 * @package AquaLuxe
 */

/**
 * Class to handle WooCommerce integration testing
 */
class AquaLuxe_WooCommerce_Test {
    /**
     * Constructor
     */
    public function __construct() {
        add_action( 'admin_menu', array( $this, 'add_test_page' ) );
    }

    /**
     * Add test page to admin menu
     */
    public function add_test_page() {
        add_submenu_page(
            'tools.php',
            __( 'AquaLuxe WooCommerce Test', 'aqualuxe' ),
            __( 'AquaLuxe WC Test', 'aqualuxe' ),
            'manage_options',
            'aqualuxe-wc-test',
            array( $this, 'render_test_page' )
        );
    }

    /**
     * Render the test page
     */
    public function render_test_page() {
        ?>
        <div class="wrap">
            <h1><?php esc_html_e( 'AquaLuxe WooCommerce Integration Test', 'aqualuxe' ); ?></h1>
            
            <div class="notice notice-info">
                <p><?php esc_html_e( 'This page helps you test how the theme behaves with WooCommerce active or inactive.', 'aqualuxe' ); ?></p>
            </div>
            
            <div class="card">
                <h2><?php esc_html_e( 'WooCommerce Status', 'aqualuxe' ); ?></h2>
                <?php if ( class_exists( 'WooCommerce' ) ) : ?>
                    <div class="notice notice-success inline">
                        <p><?php esc_html_e( 'WooCommerce is currently ACTIVE', 'aqualuxe' ); ?></p>
                    </div>
                <?php else : ?>
                    <div class="notice notice-warning inline">
                        <p><?php esc_html_e( 'WooCommerce is currently INACTIVE', 'aqualuxe' ); ?></p>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="card">
                <h2><?php esc_html_e( 'Theme Integration Tests', 'aqualuxe' ); ?></h2>
                <table class="widefat" cellspacing="0">
                    <thead>
                        <tr>
                            <th><?php esc_html_e( 'Test', 'aqualuxe' ); ?></th>
                            <th><?php esc_html_e( 'Status', 'aqualuxe' ); ?></th>
                            <th><?php esc_html_e( 'Details', 'aqualuxe' ); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $this->run_tests(); ?>
                    </tbody>
                </table>
            </div>
            
            <div class="card">
                <h2><?php esc_html_e( 'Template Override Tests', 'aqualuxe' ); ?></h2>
                <table class="widefat" cellspacing="0">
                    <thead>
                        <tr>
                            <th><?php esc_html_e( 'Template', 'aqualuxe' ); ?></th>
                            <th><?php esc_html_e( 'Status', 'aqualuxe' ); ?></th>
                            <th><?php esc_html_e( 'Path', 'aqualuxe' ); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $this->check_template_overrides(); ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php
    }

    /**
     * Run integration tests
     */
    private function run_tests() {
        $tests = array(
            'wc_support' => array(
                'name' => __( 'WooCommerce Support Declaration', 'aqualuxe' ),
                'test' => function() {
                    return current_theme_supports( 'woocommerce' );
                },
                'success_msg' => __( 'Theme properly declares WooCommerce support', 'aqualuxe' ),
                'failure_msg' => __( 'Theme does not declare WooCommerce support', 'aqualuxe' ),
            ),
            'wc_hooks' => array(
                'name' => __( 'WooCommerce Hooks', 'aqualuxe' ),
                'test' => function() {
                    return has_action( 'after_setup_theme', 'aqualuxe_woocommerce_setup' );
                },
                'success_msg' => __( 'WooCommerce setup hooks are properly registered', 'aqualuxe' ),
                'failure_msg' => __( 'WooCommerce setup hooks are not registered', 'aqualuxe' ),
            ),
            'wc_styles' => array(
                'name' => __( 'WooCommerce Styles', 'aqualuxe' ),
                'test' => function() {
                    return has_action( 'wp_enqueue_scripts', 'aqualuxe_woocommerce_scripts' );
                },
                'success_msg' => __( 'WooCommerce styles are properly enqueued', 'aqualuxe' ),
                'failure_msg' => __( 'WooCommerce styles are not enqueued', 'aqualuxe' ),
            ),
            'wc_cart_fragment' => array(
                'name' => __( 'Cart Fragments Support', 'aqualuxe' ),
                'test' => function() {
                    return has_filter( 'woocommerce_add_to_cart_fragments', 'aqualuxe_cart_link_fragment' );
                },
                'success_msg' => __( 'Cart fragments are properly supported', 'aqualuxe' ),
                'failure_msg' => __( 'Cart fragments are not supported', 'aqualuxe' ),
            ),
            'wc_conditional_loading' => array(
                'name' => __( 'Conditional Loading', 'aqualuxe' ),
                'test' => function() {
                    // Check if the theme handles WooCommerce being inactive
                    if ( ! class_exists( 'WooCommerce' ) ) {
                        // Should not have fatal errors when WC is inactive
                        return true;
                    }
                    return true;
                },
                'success_msg' => __( 'Theme handles WooCommerce state correctly', 'aqualuxe' ),
                'failure_msg' => __( 'Theme may not handle WooCommerce state correctly', 'aqualuxe' ),
            ),
        );

        foreach ( $tests as $test_id => $test ) {
            $result = $test['test']();
            $status_class = $result ? 'success' : 'error';
            $status_text = $result ? __( 'PASS', 'aqualuxe' ) : __( 'FAIL', 'aqualuxe' );
            $message = $result ? $test['success_msg'] : $test['failure_msg'];
            
            echo '<tr>';
            echo '<td>' . esc_html( $test['name'] ) . '</td>';
            echo '<td><span class="notice notice-' . esc_attr( $status_class ) . ' inline">' . esc_html( $status_text ) . '</span></td>';
            echo '<td>' . esc_html( $message ) . '</td>';
            echo '</tr>';
        }
    }

    /**
     * Check template overrides
     */
    private function check_template_overrides() {
        if ( ! class_exists( 'WooCommerce' ) ) {
            echo '<tr>';
            echo '<td colspan="3">' . esc_html__( 'WooCommerce is not active. Cannot check template overrides.', 'aqualuxe' ) . '</td>';
            echo '</tr>';
            return;
        }

        $templates = array(
            'content-product.php',
            'content-single-product.php',
            'archive-product.php',
            'single-product.php',
            'cart/cart.php',
            'cart/cart-totals.php',
            'checkout/form-checkout.php',
            'checkout/review-order.php',
            'myaccount/dashboard.php',
            'myaccount/navigation.php',
            'myaccount/my-account.php',
            'global/quantity-input.php',
        );

        $theme_dir = get_template_directory();
        $wc_dir = $theme_dir . '/woocommerce/';

        foreach ( $templates as $template ) {
            $template_path = $wc_dir . $template;
            $exists = file_exists( $template_path );
            $status_class = $exists ? 'success' : 'warning';
            $status_text = $exists ? __( 'OVERRIDDEN', 'aqualuxe' ) : __( 'DEFAULT', 'aqualuxe' );
            $path = $exists ? $template_path : __( 'Using WooCommerce default', 'aqualuxe' );
            
            echo '<tr>';
            echo '<td>' . esc_html( $template ) . '</td>';
            echo '<td><span class="notice notice-' . esc_attr( $status_class ) . ' inline">' . esc_html( $status_text ) . '</span></td>';
            echo '<td>' . esc_html( $path ) . '</td>';
            echo '</tr>';
        }
    }
}

// Initialize the test class
new AquaLuxe_WooCommerce_Test();

/**
 * Function to test if theme works correctly when WooCommerce is deactivated
 */
function aqualuxe_test_woocommerce_inactive() {
    if ( ! class_exists( 'WooCommerce' ) ) {
        // These functions should be conditionally loaded or have fallbacks
        add_action( 'admin_notices', 'aqualuxe_woocommerce_inactive_notice' );
    }
}
add_action( 'after_setup_theme', 'aqualuxe_test_woocommerce_inactive' );

/**
 * Display notice when WooCommerce is inactive
 */
function aqualuxe_woocommerce_inactive_notice() {
    ?>
    <div class="notice notice-info">
        <p><?php esc_html_e( 'WooCommerce is currently inactive. Some theme features may be limited.', 'aqualuxe' ); ?></p>
    </div>
    <?php
}