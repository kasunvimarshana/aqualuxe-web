<?php
/**
 * AquaLuxe Admin Functions
 *
 * @package AquaLuxe
 */

/**
 * Add admin menu pages
 */
function aqualuxe_admin_menu() {
    add_theme_page(
        esc_html__( 'AquaLuxe Theme', 'aqualuxe' ),
        esc_html__( 'AquaLuxe Theme', 'aqualuxe' ),
        'manage_options',
        'aqualuxe-theme',
        'aqualuxe_theme_page'
    );
}
add_action( 'admin_menu', 'aqualuxe_admin_menu' );

/**
 * Theme page callback
 */
function aqualuxe_theme_page() {
    ?>
    <div class="wrap aqualuxe-admin-page">
        <h1><?php esc_html_e( 'AquaLuxe Theme', 'aqualuxe' ); ?></h1>
        
        <div class="aqualuxe-admin-content">
            <div class="aqualuxe-admin-section">
                <h2><?php esc_html_e( 'Welcome to AquaLuxe', 'aqualuxe' ); ?></h2>
                <p><?php esc_html_e( 'AquaLuxe is a premium WordPress theme designed for high-end ornamental fish farming businesses. This theme combines elegance with functionality to create a luxurious e-commerce experience.', 'aqualuxe' ); ?></p>
                
                <div class="aqualuxe-admin-columns">
                    <div class="aqualuxe-admin-column">
                        <h3><?php esc_html_e( 'Theme Customization', 'aqualuxe' ); ?></h3>
                        <p><?php esc_html_e( 'Customize your theme appearance, colors, fonts, and layout options.', 'aqualuxe' ); ?></p>
                        <a href="<?php echo esc_url( admin_url( 'customize.php' ) ); ?>" class="button button-primary"><?php esc_html_e( 'Customize Theme', 'aqualuxe' ); ?></a>
                    </div>
                    
                    <div class="aqualuxe-admin-column">
                        <h3><?php esc_html_e( 'Import Demo Content', 'aqualuxe' ); ?></h3>
                        <p><?php esc_html_e( 'Import demo content to get started quickly with a pre-designed website.', 'aqualuxe' ); ?></p>
                        <a href="<?php echo esc_url( admin_url( 'themes.php?page=aqualuxe-demo-import' ) ); ?>" class="button button-primary"><?php esc_html_e( 'Import Demo', 'aqualuxe' ); ?></a>
                    </div>
                    
                    <div class="aqualuxe-admin-column">
                        <h3><?php esc_html_e( 'Documentation', 'aqualuxe' ); ?></h3>
                        <p><?php esc_html_e( 'Read the theme documentation to learn how to use all features.', 'aqualuxe' ); ?></p>
                        <a href="#" class="button button-primary"><?php esc_html_e( 'View Documentation', 'aqualuxe' ); ?></a>
                    </div>
                </div>
            </div>
            
            <div class="aqualuxe-admin-section">
                <h2><?php esc_html_e( 'System Status', 'aqualuxe' ); ?></h2>
                
                <table class="widefat aqualuxe-system-status">
                    <thead>
                        <tr>
                            <th><?php esc_html_e( 'System Check', 'aqualuxe' ); ?></th>
                            <th><?php esc_html_e( 'Status', 'aqualuxe' ); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php esc_html_e( 'WordPress Version', 'aqualuxe' ); ?></td>
                            <td>
                                <?php
                                $wp_version = get_bloginfo( 'version' );
                                $min_wp_version = '5.9';
                                $status_class = version_compare( $wp_version, $min_wp_version, '>=' ) ? 'aqualuxe-status-good' : 'aqualuxe-status-bad';
                                $status_icon = version_compare( $wp_version, $min_wp_version, '>=' ) ? '✓' : '✗';
                                ?>
                                <span class="<?php echo esc_attr( $status_class ); ?>">
                                    <?php echo esc_html( $wp_version ); ?> <?php echo esc_html( $status_icon ); ?>
                                </span>
                                <?php if ( version_compare( $wp_version, $min_wp_version, '<' ) ) : ?>
                                    <span class="aqualuxe-status-note"><?php printf( esc_html__( 'AquaLuxe requires WordPress %s or higher', 'aqualuxe' ), esc_html( $min_wp_version ) ); ?></span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <td><?php esc_html_e( 'PHP Version', 'aqualuxe' ); ?></td>
                            <td>
                                <?php
                                $php_version = phpversion();
                                $min_php_version = '7.4';
                                $status_class = version_compare( $php_version, $min_php_version, '>=' ) ? 'aqualuxe-status-good' : 'aqualuxe-status-bad';
                                $status_icon = version_compare( $php_version, $min_php_version, '>=' ) ? '✓' : '✗';
                                ?>
                                <span class="<?php echo esc_attr( $status_class ); ?>">
                                    <?php echo esc_html( $php_version ); ?> <?php echo esc_html( $status_icon ); ?>
                                </span>
                                <?php if ( version_compare( $php_version, $min_php_version, '<' ) ) : ?>
                                    <span class="aqualuxe-status-note"><?php printf( esc_html__( 'AquaLuxe requires PHP %s or higher', 'aqualuxe' ), esc_html( $min_php_version ) ); ?></span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <td><?php esc_html_e( 'WooCommerce', 'aqualuxe' ); ?></td>
                            <td>
                                <?php
                                $wc_active = class_exists( 'WooCommerce' );
                                $status_class = $wc_active ? 'aqualuxe-status-good' : 'aqualuxe-status-warning';
                                $status_icon = $wc_active ? '✓' : '!';
                                $wc_version = $wc_active ? WC()->version : '';
                                ?>
                                <span class="<?php echo esc_attr( $status_class ); ?>">
                                    <?php if ( $wc_active ) : ?>
                                        <?php echo esc_html( $wc_version ); ?> <?php echo esc_html( $status_icon ); ?>
                                    <?php else : ?>
                                        <?php esc_html_e( 'Not Active', 'aqualuxe' ); ?> <?php echo esc_html( $status_icon ); ?>
                                    <?php endif; ?>
                                </span>
                                <?php if ( ! $wc_active ) : ?>
                                    <span class="aqualuxe-status-note"><?php esc_html_e( 'WooCommerce is recommended for full theme functionality', 'aqualuxe' ); ?></span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <td><?php esc_html_e( 'Memory Limit', 'aqualuxe' ); ?></td>
                            <td>
                                <?php
                                $memory_limit = wp_convert_hr_to_bytes( WP_MEMORY_LIMIT );
                                $min_memory = 67108864; // 64MB
                                $status_class = $memory_limit >= $min_memory ? 'aqualuxe-status-good' : 'aqualuxe-status-warning';
                                $status_icon = $memory_limit >= $min_memory ? '✓' : '!';
                                ?>
                                <span class="<?php echo esc_attr( $status_class ); ?>">
                                    <?php echo esc_html( size_format( $memory_limit ) ); ?> <?php echo esc_html( $status_icon ); ?>
                                </span>
                                <?php if ( $memory_limit < $min_memory ) : ?>
                                    <span class="aqualuxe-status-note"><?php esc_html_e( 'We recommend at least 64MB of memory', 'aqualuxe' ); ?></span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <td><?php esc_html_e( 'Max Upload Size', 'aqualuxe' ); ?></td>
                            <td>
                                <?php
                                $upload_max = wp_max_upload_size();
                                $min_upload = 8388608; // 8MB
                                $status_class = $upload_max >= $min_upload ? 'aqualuxe-status-good' : 'aqualuxe-status-warning';
                                $status_icon = $upload_max >= $min_upload ? '✓' : '!';
                                ?>
                                <span class="<?php echo esc_attr( $status_class ); ?>">
                                    <?php echo esc_html( size_format( $upload_max ) ); ?> <?php echo esc_html( $status_icon ); ?>
                                </span>
                                <?php if ( $upload_max < $min_upload ) : ?>
                                    <span class="aqualuxe-status-note"><?php esc_html_e( 'We recommend at least 8MB for upload size', 'aqualuxe' ); ?></span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <div class="aqualuxe-admin-section">
                <h2><?php esc_html_e( 'Theme Information', 'aqualuxe' ); ?></h2>
                
                <table class="widefat aqualuxe-theme-info">
                    <tbody>
                        <tr>
                            <td><?php esc_html_e( 'Theme Name', 'aqualuxe' ); ?></td>
                            <td><?php echo esc_html( wp_get_theme()->get( 'Name' ) ); ?></td>
                        </tr>
                        <tr>
                            <td><?php esc_html_e( 'Theme Version', 'aqualuxe' ); ?></td>
                            <td><?php echo esc_html( wp_get_theme()->get( 'Version' ) ); ?></td>
                        </tr>
                        <tr>
                            <td><?php esc_html_e( 'Author', 'aqualuxe' ); ?></td>
                            <td><?php echo esc_html( wp_get_theme()->get( 'Author' ) ); ?></td>
                        </tr>
                        <tr>
                            <td><?php esc_html_e( 'Theme URI', 'aqualuxe' ); ?></td>
                            <td><a href="<?php echo esc_url( wp_get_theme()->get( 'ThemeURI' ) ); ?>" target="_blank"><?php echo esc_html( wp_get_theme()->get( 'ThemeURI' ) ); ?></a></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <style>
        .aqualuxe-admin-page {
            max-width: 1200px;
            margin: 20px auto;
        }
        .aqualuxe-admin-section {
            background: #fff;
            border: 1px solid #e5e5e5;
            box-shadow: 0 1px 1px rgba(0,0,0,.04);
            margin-bottom: 20px;
            padding: 20px;
        }
        .aqualuxe-admin-columns {
            display: flex;
            flex-wrap: wrap;
            margin: 0 -10px;
        }
        .aqualuxe-admin-column {
            flex: 1;
            min-width: 250px;
            padding: 10px;
        }
        .aqualuxe-system-status,
        .aqualuxe-theme-info {
            width: 100%;
            border-collapse: collapse;
        }
        .aqualuxe-system-status th,
        .aqualuxe-system-status td,
        .aqualuxe-theme-info th,
        .aqualuxe-theme-info td {
            padding: 10px;
        }
        .aqualuxe-status-good {
            color: #46b450;
        }
        .aqualuxe-status-warning {
            color: #ffb900;
        }
        .aqualuxe-status-bad {
            color: #dc3232;
        }
        .aqualuxe-status-note {
            display: block;
            color: #777;
            font-size: 12px;
            margin-top: 5px;
        }
    </style>
    <?php
}

/**
 * Add meta box for page options
 */
function aqualuxe_add_page_options_meta_box() {
    add_meta_box(
        'aqualuxe_page_options',
        esc_html__( 'Page Options', 'aqualuxe' ),
        'aqualuxe_page_options_callback',
        'page',
        'side',
        'high'
    );
}
add_action( 'add_meta_boxes', 'aqualuxe_add_page_options_meta_box' );

/**
 * Page options meta box callback
 */
function aqualuxe_page_options_callback( $post ) {
    wp_nonce_field( 'aqualuxe_page_options', 'aqualuxe_page_options_nonce' );
    
    $hide_title = get_post_meta( $post->ID, '_aqualuxe_hide_title', true );
    $hide_featured_image = get_post_meta( $post->ID, '_aqualuxe_hide_featured_image', true );
    $page_layout = get_post_meta( $post->ID, '_aqualuxe_page_layout', true );
    $page_layout = $page_layout ? $page_layout : 'default';
    ?>
    <p>
        <label>
            <input type="checkbox" name="aqualuxe_hide_title" value="1" <?php checked( $hide_title, '1' ); ?>>
            <?php esc_html_e( 'Hide Page Title', 'aqualuxe' ); ?>
        </label>
    </p>
    <p>
        <label>
            <input type="checkbox" name="aqualuxe_hide_featured_image" value="1" <?php checked( $hide_featured_image, '1' ); ?>>
            <?php esc_html_e( 'Hide Featured Image', 'aqualuxe' ); ?>
        </label>
    </p>
    <p>
        <label for="aqualuxe_page_layout"><?php esc_html_e( 'Page Layout', 'aqualuxe' ); ?></label>
        <select name="aqualuxe_page_layout" id="aqualuxe_page_layout" class="widefat">
            <option value="default" <?php selected( $page_layout, 'default' ); ?>><?php esc_html_e( 'Default', 'aqualuxe' ); ?></option>
            <option value="full-width" <?php selected( $page_layout, 'full-width' ); ?>><?php esc_html_e( 'Full Width', 'aqualuxe' ); ?></option>
            <option value="left-sidebar" <?php selected( $page_layout, 'left-sidebar' ); ?>><?php esc_html_e( 'Left Sidebar', 'aqualuxe' ); ?></option>
            <option value="right-sidebar" <?php selected( $page_layout, 'right-sidebar' ); ?>><?php esc_html_e( 'Right Sidebar', 'aqualuxe' ); ?></option>
        </select>
    </p>
    <?php
}

/**
 * Save page options meta box data
 */
function aqualuxe_save_page_options( $post_id ) {
    if ( ! isset( $_POST['aqualuxe_page_options_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['aqualuxe_page_options_nonce'] ) ), 'aqualuxe_page_options' ) ) {
        return;
    }
    
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }
    
    $hide_title = isset( $_POST['aqualuxe_hide_title'] ) ? '1' : '';
    update_post_meta( $post_id, '_aqualuxe_hide_title', $hide_title );
    
    $hide_featured_image = isset( $_POST['aqualuxe_hide_featured_image'] ) ? '1' : '';
    update_post_meta( $post_id, '_aqualuxe_hide_featured_image', $hide_featured_image );
    
    if ( isset( $_POST['aqualuxe_page_layout'] ) ) {
        update_post_meta( $post_id, '_aqualuxe_page_layout', sanitize_text_field( wp_unslash( $_POST['aqualuxe_page_layout'] ) ) );
    }
}
add_action( 'save_post', 'aqualuxe_save_page_options' );

/**
 * Add admin notice for theme setup
 */
function aqualuxe_admin_notice() {
    // Check if notice has been dismissed
    if ( get_option( 'aqualuxe_setup_notice_dismissed' ) ) {
        return;
    }
    
    // Only show on dashboard, themes, and plugins pages
    $screen = get_current_screen();
    if ( ! $screen || ! in_array( $screen->id, array( 'dashboard', 'themes', 'plugins' ), true ) ) {
        return;
    }
    
    ?>
    <div class="notice notice-info is-dismissible aqualuxe-admin-notice">
        <h3><?php esc_html_e( 'Welcome to AquaLuxe Theme!', 'aqualuxe' ); ?></h3>
        <p><?php esc_html_e( 'Thank you for choosing AquaLuxe. To get started with your theme setup and import demo content, please visit the theme page.', 'aqualuxe' ); ?></p>
        <p>
            <a href="<?php echo esc_url( admin_url( 'themes.php?page=aqualuxe-theme' ) ); ?>" class="button button-primary"><?php esc_html_e( 'Get Started', 'aqualuxe' ); ?></a>
            <a href="#" class="aqualuxe-dismiss-notice" data-nonce="<?php echo esc_attr( wp_create_nonce( 'aqualuxe_dismiss_notice' ) ); ?>"><?php esc_html_e( 'Dismiss this notice', 'aqualuxe' ); ?></a>
        </p>
    </div>
    <style>
        .aqualuxe-admin-notice {
            padding: 20px;
            border-left-color: #0073aa;
        }
        .aqualuxe-admin-notice h3 {
            margin-top: 0;
        }
        .aqualuxe-dismiss-notice {
            margin-left: 15px;
        }
    </style>
    <script>
        jQuery(document).ready(function($) {
            $(document).on('click', '.aqualuxe-dismiss-notice', function(e) {
                e.preventDefault();
                
                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'aqualuxe_dismiss_notice',
                        nonce: $(this).data('nonce')
                    },
                    success: function(response) {
                        $('.aqualuxe-admin-notice').fadeOut();
                    }
                });
            });
        });
    </script>
    <?php
}
add_action( 'admin_notices', 'aqualuxe_admin_notice' );

/**
 * AJAX handler for dismissing admin notice
 */
function aqualuxe_dismiss_notice_ajax() {
    if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'aqualuxe_dismiss_notice' ) ) {
        wp_send_json_error( 'Invalid nonce' );
    }
    
    update_option( 'aqualuxe_setup_notice_dismissed', true );
    wp_send_json_success();
}
add_action( 'wp_ajax_aqualuxe_dismiss_notice', 'aqualuxe_dismiss_notice_ajax' );

/**
 * Add theme info to admin footer
 */
function aqualuxe_admin_footer_text( $text ) {
    $screen = get_current_screen();
    
    if ( $screen && strpos( $screen->id, 'aqualuxe' ) !== false ) {
        $text = sprintf(
            /* translators: %1$s: theme name, %2$s: theme version */
            esc_html__( 'Thank you for creating with %1$s v%2$s', 'aqualuxe' ),
            'AquaLuxe',
            AQUALUXE_VERSION
        );
    }
    
    return $text;
}
add_filter( 'admin_footer_text', 'aqualuxe_admin_footer_text' );

/**
 * Add demo import functionality
 */
function aqualuxe_demo_import_menu() {
    add_submenu_page(
        'themes.php',
        esc_html__( 'AquaLuxe Demo Import', 'aqualuxe' ),
        esc_html__( 'AquaLuxe Demo Import', 'aqualuxe' ),
        'manage_options',
        'aqualuxe-demo-import',
        'aqualuxe_demo_import_page'
    );
}
add_action( 'admin_menu', 'aqualuxe_demo_import_menu' );

/**
 * Demo import page callback
 */
function aqualuxe_demo_import_page() {
    ?>
    <div class="wrap aqualuxe-demo-import">
        <h1><?php esc_html_e( 'AquaLuxe Demo Import', 'aqualuxe' ); ?></h1>
        
        <div class="aqualuxe-admin-content">
            <div class="aqualuxe-admin-section">
                <h2><?php esc_html_e( 'Import Demo Content', 'aqualuxe' ); ?></h2>
                <p><?php esc_html_e( 'Import the demo content to get started with a pre-designed website. This will import posts, pages, images, theme options, widgets, menus and more.', 'aqualuxe' ); ?></p>
                <p><strong><?php esc_html_e( 'Please note:', 'aqualuxe' ); ?></strong> <?php esc_html_e( 'The import process may take several minutes. Please be patient.', 'aqualuxe' ); ?></p>
                
                <div class="aqualuxe-demo-options">
                    <div class="aqualuxe-demo-option">
                        <div class="aqualuxe-demo-image">
                            <img src="<?php echo esc_url( AQUALUXE_URI . 'assets/images/admin/demo-main.jpg' ); ?>" alt="<?php esc_attr_e( 'Main Demo', 'aqualuxe' ); ?>">
                        </div>
                        <div class="aqualuxe-demo-content">
                            <h3><?php esc_html_e( 'Main Demo', 'aqualuxe' ); ?></h3>
                            <p><?php esc_html_e( 'Complete shop demo with all pages, products, and features.', 'aqualuxe' ); ?></p>
                            <button class="button button-primary aqualuxe-import-demo" data-demo="main" data-nonce="<?php echo esc_attr( wp_create_nonce( 'aqualuxe_import_demo' ) ); ?>">
                                <?php esc_html_e( 'Import Demo', 'aqualuxe' ); ?>
                            </button>
                        </div>
                    </div>
                    
                    <div class="aqualuxe-demo-option">
                        <div class="aqualuxe-demo-image">
                            <img src="<?php echo esc_url( AQUALUXE_URI . 'assets/images/admin/demo-minimal.jpg' ); ?>" alt="<?php esc_attr_e( 'Minimal Demo', 'aqualuxe' ); ?>">
                        </div>
                        <div class="aqualuxe-demo-content">
                            <h3><?php esc_html_e( 'Minimal Demo', 'aqualuxe' ); ?></h3>
                            <p><?php esc_html_e( 'A clean, minimal design with essential pages and products.', 'aqualuxe' ); ?></p>
                            <button class="button button-primary aqualuxe-import-demo" data-demo="minimal" data-nonce="<?php echo esc_attr( wp_create_nonce( 'aqualuxe_import_demo' ) ); ?>">
                                <?php esc_html_e( 'Import Demo', 'aqualuxe' ); ?>
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="aqualuxe-import-progress" style="display: none;">
                    <div class="aqualuxe-progress-bar">
                        <div class="aqualuxe-progress-bar-inner"></div>
                    </div>
                    <div class="aqualuxe-progress-status">
                        <span class="aqualuxe-progress-percentage">0%</span>
                        <span class="aqualuxe-progress-message"><?php esc_html_e( 'Preparing import...', 'aqualuxe' ); ?></span>
                    </div>
                </div>
                
                <div class="aqualuxe-import-complete" style="display: none;">
                    <div class="aqualuxe-import-success">
                        <h3><?php esc_html_e( 'Import Complete!', 'aqualuxe' ); ?></h3>
                        <p><?php esc_html_e( 'The demo content has been successfully imported.', 'aqualuxe' ); ?></p>
                        <p>
                            <a href="<?php echo esc_url( home_url() ); ?>" class="button button-primary" target="_blank"><?php esc_html_e( 'View Site', 'aqualuxe' ); ?></a>
                            <a href="<?php echo esc_url( admin_url( 'customize.php' ) ); ?>" class="button"><?php esc_html_e( 'Customize Theme', 'aqualuxe' ); ?></a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
        .aqualuxe-demo-import {
            max-width: 1200px;
            margin: 20px auto;
        }
        .aqualuxe-admin-section {
            background: #fff;
            border: 1px solid #e5e5e5;
            box-shadow: 0 1px 1px rgba(0,0,0,.04);
            margin-bottom: 20px;
            padding: 20px;
        }
        .aqualuxe-demo-options {
            display: flex;
            flex-wrap: wrap;
            margin: 0 -10px;
        }
        .aqualuxe-demo-option {
            flex: 1;
            min-width: 300px;
            margin: 10px;
            border: 1px solid #e5e5e5;
            border-radius: 3px;
            overflow: hidden;
        }
        .aqualuxe-demo-image img {
            width: 100%;
            height: auto;
            display: block;
        }
        .aqualuxe-demo-content {
            padding: 15px;
        }
        .aqualuxe-import-progress {
            margin-top: 30px;
        }
        .aqualuxe-progress-bar {
            height: 20px;
            background-color: #f3f3f3;
            border-radius: 10px;
            margin-bottom: 10px;
            overflow: hidden;
        }
        .aqualuxe-progress-bar-inner {
            height: 100%;
            background-color: #0073aa;
            width: 0;
            transition: width 0.3s;
        }
        .aqualuxe-progress-status {
            display: flex;
            justify-content: space-between;
        }
        .aqualuxe-import-complete {
            margin-top: 30px;
            padding: 20px;
            background-color: #f7fcfe;
            border-left: 4px solid #00a0d2;
        }
    </style>
    <script>
        jQuery(document).ready(function($) {
            $('.aqualuxe-import-demo').on('click', function(e) {
                e.preventDefault();
                
                var button = $(this);
                var demo = button.data('demo');
                var nonce = button.data('nonce');
                
                // Disable all buttons
                $('.aqualuxe-import-demo').prop('disabled', true);
                
                // Show progress bar
                $('.aqualuxe-import-progress').show();
                
                // Start import process
                importDemo(demo, nonce, 0);
            });
            
            function importDemo(demo, nonce, step) {
                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'aqualuxe_import_demo',
                        demo: demo,
                        step: step,
                        nonce: nonce
                    },
                    success: function(response) {
                        if (response.success) {
                            // Update progress
                            $('.aqualuxe-progress-bar-inner').css('width', response.data.progress + '%');
                            $('.aqualuxe-progress-percentage').text(response.data.progress + '%');
                            $('.aqualuxe-progress-message').text(response.data.message);
                            
                            if (response.data.complete) {
                                // Import complete
                                $('.aqualuxe-import-progress').hide();
                                $('.aqualuxe-import-complete').show();
                            } else {
                                // Continue with next step
                                importDemo(demo, nonce, response.data.next_step);
                            }
                        } else {
                            alert(response.data.message || 'Import failed. Please try again.');
                            $('.aqualuxe-import-demo').prop('disabled', false);
                        }
                    },
                    error: function() {
                        alert('Import failed. Please try again.');
                        $('.aqualuxe-import-demo').prop('disabled', false);
                    }
                });
            }
        });
    </script>
    <?php
}

/**
 * AJAX handler for demo import
 */
function aqualuxe_import_demo_ajax() {
    if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'aqualuxe_import_demo' ) ) {
        wp_send_json_error( array( 'message' => 'Invalid nonce' ) );
    }
    
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_send_json_error( array( 'message' => 'Permission denied' ) );
    }
    
    $demo = isset( $_POST['demo'] ) ? sanitize_text_field( wp_unslash( $_POST['demo'] ) ) : 'main';
    $step = isset( $_POST['step'] ) ? absint( $_POST['step'] ) : 0;
    
    // Demo import steps
    $steps = array(
        0 => array(
            'message' => esc_html__( 'Preparing import...', 'aqualuxe' ),
            'progress' => 5,
        ),
        1 => array(
            'message' => esc_html__( 'Importing theme options...', 'aqualuxe' ),
            'progress' => 15,
        ),
        2 => array(
            'message' => esc_html__( 'Importing widgets...', 'aqualuxe' ),
            'progress' => 30,
        ),
        3 => array(
            'message' => esc_html__( 'Importing pages...', 'aqualuxe' ),
            'progress' => 45,
        ),
        4 => array(
            'message' => esc_html__( 'Importing posts...', 'aqualuxe' ),
            'progress' => 60,
        ),
        5 => array(
            'message' => esc_html__( 'Importing products...', 'aqualuxe' ),
            'progress' => 75,
        ),
        6 => array(
            'message' => esc_html__( 'Importing menus...', 'aqualuxe' ),
            'progress' => 90,
        ),
        7 => array(
            'message' => esc_html__( 'Finalizing import...', 'aqualuxe' ),
            'progress' => 100,
            'complete' => true,
        ),
    );
    
    // Simulate import process
    // In a real implementation, this would actually import the demo content
    sleep(1); // Simulate processing time
    
    $current_step = isset( $steps[$step] ) ? $steps[$step] : $steps[0];
    $next_step = $step + 1;
    $complete = isset( $current_step['complete'] ) && $current_step['complete'];
    
    wp_send_json_success( array(
        'message' => $current_step['message'],
        'progress' => $current_step['progress'],
        'next_step' => $next_step,
        'complete' => $complete,
    ) );
}
add_action( 'wp_ajax_aqualuxe_import_demo', 'aqualuxe_import_demo_ajax' );