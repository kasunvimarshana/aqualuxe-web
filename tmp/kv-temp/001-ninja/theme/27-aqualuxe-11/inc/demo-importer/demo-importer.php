<?php
/**
 * AquaLuxe Demo Content Importer
 *
 * @package AquaLuxe
 * @subpackage Demo_Importer
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Include required files.
 */
require_once AQUALUXE_DIR . 'inc/demo-importer/class-aqualuxe-demo-importer.php';
require_once AQUALUXE_DIR . 'inc/demo-importer/class-aqualuxe-demo-content-processor.php';

/**
 * Initialize the demo importer.
 */
function aqualuxe_init_demo_importer() {
    // Get the demo importer instance.
    $demo_importer = AquaLuxe_Demo_Importer::get_instance();
}
add_action( 'after_setup_theme', 'aqualuxe_init_demo_importer' );

/**
 * Handle AJAX import request.
 */
function aqualuxe_ajax_import_demo_content() {
    // Get the demo content processor instance.
    $demo_processor = AquaLuxe_Demo_Content_Processor::get_instance();
    
    // Process the import request.
    $demo_processor->ajax_import_demo_content();
}
add_action( 'wp_ajax_aqualuxe_import_demo_content', 'aqualuxe_ajax_import_demo_content' );

/**
 * Add demo content notice for new theme installations.
 */
function aqualuxe_demo_content_admin_notice() {
    // Only show this notice once.
    if ( get_option( 'aqualuxe_demo_notice_dismissed' ) ) {
        return;
    }
    
    // Check if we're on the themes or dashboard page.
    $screen = get_current_screen();
    if ( ! $screen || ! in_array( $screen->id, array( 'dashboard', 'themes', 'appearance_page_aqualuxe-demo-importer' ) ) ) {
        return;
    }
    
    ?>
    <div class="notice notice-info is-dismissible aqualuxe-demo-notice">
        <h3><?php esc_html_e( 'Welcome to AquaLuxe!', 'aqualuxe' ); ?></h3>
        <p><?php esc_html_e( 'Would you like to import demo content to get started with the AquaLuxe theme? This will help you set up your site with sample content and settings.', 'aqualuxe' ); ?></p>
        <p>
            <a href="<?php echo esc_url( admin_url( 'themes.php?page=aqualuxe-demo-importer' ) ); ?>" class="button button-primary">
                <?php esc_html_e( 'Import Demo Content', 'aqualuxe' ); ?>
            </a>
            <a href="#" class="button aqualuxe-dismiss-demo-notice">
                <?php esc_html_e( 'No Thanks', 'aqualuxe' ); ?>
            </a>
        </p>
    </div>
    <script>
        jQuery(document).ready(function($) {
            $(document).on('click', '.aqualuxe-dismiss-demo-notice, .aqualuxe-demo-notice .notice-dismiss', function(e) {
                e.preventDefault();
                
                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'aqualuxe_dismiss_demo_notice',
                        nonce: '<?php echo wp_create_nonce( 'aqualuxe_dismiss_demo_notice' ); ?>'
                    }
                });
                
                $('.aqualuxe-demo-notice').fadeOut();
            });
        });
    </script>
    <?php
}
add_action( 'admin_notices', 'aqualuxe_demo_content_admin_notice' );

/**
 * Dismiss demo content notice.
 */
function aqualuxe_ajax_dismiss_demo_notice() {
    // Verify nonce.
    if ( ! check_ajax_referer( 'aqualuxe_dismiss_demo_notice', 'nonce', false ) ) {
        wp_send_json_error( 'Invalid nonce' );
    }
    
    // Update option to dismiss notice.
    update_option( 'aqualuxe_demo_notice_dismissed', true );
    
    wp_send_json_success();
}
add_action( 'wp_ajax_aqualuxe_dismiss_demo_notice', 'aqualuxe_ajax_dismiss_demo_notice' );

/**
 * Add demo content link to theme action links.
 *
 * @param array $actions Theme action links.
 * @return array Modified theme action links.
 */
function aqualuxe_theme_action_links( $actions ) {
    $actions['aqualuxe-demo-importer'] = sprintf(
        '<a href="%s">%s</a>',
        esc_url( admin_url( 'themes.php?page=aqualuxe-demo-importer' ) ),
        esc_html__( 'Import Demo Content', 'aqualuxe' )
    );
    
    return $actions;
}
add_filter( 'theme_action_links_aqualuxe', 'aqualuxe_theme_action_links' );

/**
 * Add demo content link to admin bar.
 *
 * @param WP_Admin_Bar $admin_bar Admin bar object.
 */
function aqualuxe_admin_bar_demo_link( $admin_bar ) {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }
    
    $admin_bar->add_menu( array(
        'id'    => 'aqualuxe-demo-importer',
        'title' => esc_html__( 'Import Demo Content', 'aqualuxe' ),
        'href'  => esc_url( admin_url( 'themes.php?page=aqualuxe-demo-importer' ) ),
        'meta'  => array(
            'title' => esc_html__( 'Import AquaLuxe Demo Content', 'aqualuxe' ),
        ),
    ) );
}
add_action( 'admin_bar_menu', 'aqualuxe_admin_bar_demo_link', 100 );

/**
 * Register demo content directory for cleanup.
 *
 * This function registers the demo content directory for cleanup when the theme is uninstalled.
 */
function aqualuxe_register_demo_content_cleanup() {
    // Register the demo content directory for cleanup.
    add_filter( 'aqualuxe_cleanup_directories', function( $directories ) {
        $directories[] = AQUALUXE_DIR . 'inc/demo-importer/demo-content';
        return $directories;
    } );
}
add_action( 'after_setup_theme', 'aqualuxe_register_demo_content_cleanup' );

/**
 * Add demo content status to system info.
 *
 * @param array $info System info.
 * @return array Modified system info.
 */
function aqualuxe_demo_content_system_info( $info ) {
    $demo_content_imported = get_option( 'aqualuxe_demo_content_imported', false );
    
    $info['theme']['demo_content'] = $demo_content_imported ? 'Imported' : 'Not imported';
    
    return $info;
}
add_filter( 'aqualuxe_system_info', 'aqualuxe_demo_content_system_info' );