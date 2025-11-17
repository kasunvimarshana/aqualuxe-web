<?php
/**
 * AquaLuxe Theme Testing Script
 *
 * This file contains functions to test various aspects of the AquaLuxe theme.
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * AquaLuxe Theme Testing Class
 */
class AquaLuxe_Theme_Test {

    /**
     * Constructor
     */
    public function __construct() {
        // Add admin menu
        add_action( 'admin_menu', array( $this, 'add_test_menu' ) );
        
        // Add AJAX handlers
        add_action( 'wp_ajax_aqualuxe_run_test', array( $this, 'run_test' ) );
    }

    /**
     * Add test menu
     */
    public function add_test_menu() {
        add_submenu_page(
            'aqualuxe-theme',
            esc_html__( 'Theme Testing', 'aqualuxe' ),
            esc_html__( 'Theme Testing', 'aqualuxe' ),
            'manage_options',
            'aqualuxe-theme-testing',
            array( $this, 'render_test_page' )
        );
    }

    /**
     * Render test page
     */
    public function render_test_page() {
        ?>
        <div class="wrap">
            <h1><?php esc_html_e( 'AquaLuxe Theme Testing', 'aqualuxe' ); ?></h1>
            
            <div class="aqualuxe-test-container">
                <div class="aqualuxe-test-sidebar">
                    <ul class="aqualuxe-test-tabs">
                        <li class="active" data-tab="responsive"><?php esc_html_e( 'Responsive Design', 'aqualuxe' ); ?></li>
                        <li data-tab="performance"><?php esc_html_e( 'Performance', 'aqualuxe' ); ?></li>
                        <li data-tab="accessibility"><?php esc_html_e( 'Accessibility', 'aqualuxe' ); ?></li>
                        <li data-tab="woocommerce"><?php esc_html_e( 'WooCommerce', 'aqualuxe' ); ?></li>
                        <li data-tab="seo"><?php esc_html_e( 'SEO', 'aqualuxe' ); ?></li>
                        <li data-tab="multilingual"><?php esc_html_e( 'Multilingual', 'aqualuxe' ); ?></li>
                        <li data-tab="dark-mode"><?php esc_html_e( 'Dark Mode', 'aqualuxe' ); ?></li>
                    </ul>
                </div>
                
                <div class="aqualuxe-test-content">
                    <!-- Responsive Design Tab -->
                    <div class="aqualuxe-test-tab active" id="responsive-tab">
                        <h2><?php esc_html_e( 'Responsive Design Testing', 'aqualuxe' ); ?></h2>
                        <p><?php esc_html_e( 'Test how your theme looks on different screen sizes.', 'aqualuxe' ); ?></p>
                        
                        <div class="aqualuxe-test-actions">
                            <button class="button button-primary" data-test="responsive-mobile"><?php esc_html_e( 'Test Mobile View', 'aqualuxe' ); ?></button>
                            <button class="button button-primary" data-test="responsive-tablet"><?php esc_html_e( 'Test Tablet View', 'aqualuxe' ); ?></button>
                            <button class="button button-primary" data-test="responsive-desktop"><?php esc_html_e( 'Test Desktop View', 'aqualuxe' ); ?></button>
                        </div>
                        
                        <div class="aqualuxe-test-results" id="responsive-results"></div>
                    </div>
                    
                    <!-- Performance Tab -->
                    <div class="aqualuxe-test-tab" id="performance-tab">
                        <h2><?php esc_html_e( 'Performance Testing', 'aqualuxe' ); ?></h2>
                        <p><?php esc_html_e( 'Test your theme\'s performance and optimization.', 'aqualuxe' ); ?></p>
                        
                        <div class="aqualuxe-test-actions">
                            <button class="button button-primary" data-test="performance-assets"><?php esc_html_e( 'Check Asset Loading', 'aqualuxe' ); ?></button>
                            <button class="button button-primary" data-test="performance-images"><?php esc_html_e( 'Check Image Optimization', 'aqualuxe' ); ?></button>
                            <button class="button button-primary" data-test="performance-lazy-loading"><?php esc_html_e( 'Test Lazy Loading', 'aqualuxe' ); ?></button>
                        </div>
                        
                        <div class="aqualuxe-test-results" id="performance-results"></div>
                    </div>
                    
                    <!-- Accessibility Tab -->
                    <div class="aqualuxe-test-tab" id="accessibility-tab">
                        <h2><?php esc_html_e( 'Accessibility Testing', 'aqualuxe' ); ?></h2>
                        <p><?php esc_html_e( 'Test your theme\'s accessibility compliance.', 'aqualuxe' ); ?></p>
                        
                        <div class="aqualuxe-test-actions">
                            <button class="button button-primary" data-test="accessibility-contrast"><?php esc_html_e( 'Check Color Contrast', 'aqualuxe' ); ?></button>
                            <button class="button button-primary" data-test="accessibility-keyboard"><?php esc_html_e( 'Test Keyboard Navigation', 'aqualuxe' ); ?></button>
                            <button class="button button-primary" data-test="accessibility-aria"><?php esc_html_e( 'Check ARIA Attributes', 'aqualuxe' ); ?></button>
                        </div>
                        
                        <div class="aqualuxe-test-results" id="accessibility-results"></div>
                    </div>
                    
                    <!-- WooCommerce Tab -->
                    <div class="aqualuxe-test-tab" id="woocommerce-tab">
                        <h2><?php esc_html_e( 'WooCommerce Testing', 'aqualuxe' ); ?></h2>
                        <p><?php esc_html_e( 'Test your theme\'s WooCommerce integration.', 'aqualuxe' ); ?></p>
                        
                        <div class="aqualuxe-test-actions">
                            <button class="button button-primary" data-test="woocommerce-templates"><?php esc_html_e( 'Check Templates', 'aqualuxe' ); ?></button>
                            <button class="button button-primary" data-test="woocommerce-checkout"><?php esc_html_e( 'Test Checkout Process', 'aqualuxe' ); ?></button>
                            <button class="button button-primary" data-test="woocommerce-cart"><?php esc_html_e( 'Test Cart Functionality', 'aqualuxe' ); ?></button>
                        </div>
                        
                        <div class="aqualuxe-test-results" id="woocommerce-results"></div>
                    </div>
                    
                    <!-- SEO Tab -->
                    <div class="aqualuxe-test-tab" id="seo-tab">
                        <h2><?php esc_html_e( 'SEO Testing', 'aqualuxe' ); ?></h2>
                        <p><?php esc_html_e( 'Test your theme\'s SEO optimization.', 'aqualuxe' ); ?></p>
                        
                        <div class="aqualuxe-test-actions">
                            <button class="button button-primary" data-test="seo-schema"><?php esc_html_e( 'Check Schema.org Markup', 'aqualuxe' ); ?></button>
                            <button class="button button-primary" data-test="seo-opengraph"><?php esc_html_e( 'Test Open Graph Tags', 'aqualuxe' ); ?></button>
                            <button class="button button-primary" data-test="seo-headings"><?php esc_html_e( 'Check Heading Structure', 'aqualuxe' ); ?></button>
                        </div>
                        
                        <div class="aqualuxe-test-results" id="seo-results"></div>
                    </div>
                    
                    <!-- Multilingual Tab -->
                    <div class="aqualuxe-test-tab" id="multilingual-tab">
                        <h2><?php esc_html_e( 'Multilingual Testing', 'aqualuxe' ); ?></h2>
                        <p><?php esc_html_e( 'Test your theme\'s multilingual support.', 'aqualuxe' ); ?></p>
                        
                        <div class="aqualuxe-test-actions">
                            <button class="button button-primary" data-test="multilingual-strings"><?php esc_html_e( 'Check Translatable Strings', 'aqualuxe' ); ?></button>
                            <button class="button button-primary" data-test="multilingual-rtl"><?php esc_html_e( 'Test RTL Support', 'aqualuxe' ); ?></button>
                            <button class="button button-primary" data-test="multilingual-plugins"><?php esc_html_e( 'Check Plugin Compatibility', 'aqualuxe' ); ?></button>
                        </div>
                        
                        <div class="aqualuxe-test-results" id="multilingual-results"></div>
                    </div>
                    
                    <!-- Dark Mode Tab -->
                    <div class="aqualuxe-test-tab" id="dark-mode-tab">
                        <h2><?php esc_html_e( 'Dark Mode Testing', 'aqualuxe' ); ?></h2>
                        <p><?php esc_html_e( 'Test your theme\'s dark mode functionality.', 'aqualuxe' ); ?></p>
                        
                        <div class="aqualuxe-test-actions">
                            <button class="button button-primary" data-test="dark-mode-toggle"><?php esc_html_e( 'Test Dark Mode Toggle', 'aqualuxe' ); ?></button>
                            <button class="button button-primary" data-test="dark-mode-colors"><?php esc_html_e( 'Check Color Scheme', 'aqualuxe' ); ?></button>
                            <button class="button button-primary" data-test="dark-mode-images"><?php esc_html_e( 'Test Image Handling', 'aqualuxe' ); ?></button>
                        </div>
                        
                        <div class="aqualuxe-test-results" id="dark-mode-results"></div>
                    </div>
                </div>
            </div>
        </div>
        
        <style>
            .aqualuxe-test-container {
                display: flex;
                margin-top: 20px;
                background: #fff;
                border: 1px solid #ccd0d4;
                box-shadow: 0 1px 1px rgba(0,0,0,.04);
            }
            
            .aqualuxe-test-sidebar {
                width: 200px;
                background: #f9f9f9;
                border-right: 1px solid #ccd0d4;
            }
            
            .aqualuxe-test-tabs {
                margin: 0;
                padding: 0;
            }
            
            .aqualuxe-test-tabs li {
                margin: 0;
                padding: 12px 15px;
                cursor: pointer;
                border-bottom: 1px solid #eee;
                font-weight: 500;
            }
            
            .aqualuxe-test-tabs li.active {
                background: #fff;
                margin-right: -1px;
                border-left: 4px solid #0ea5e9;
                padding-left: 11px;
            }
            
            .aqualuxe-test-content {
                flex: 1;
                padding: 20px;
            }
            
            .aqualuxe-test-tab {
                display: none;
            }
            
            .aqualuxe-test-tab.active {
                display: block;
            }
            
            .aqualuxe-test-actions {
                margin: 20px 0;
            }
            
            .aqualuxe-test-actions button {
                margin-right: 10px;
                margin-bottom: 10px;
            }
            
            .aqualuxe-test-results {
                margin-top: 20px;
                padding: 15px;
                background: #f9f9f9;
                border: 1px solid #eee;
                border-radius: 3px;
                min-height: 100px;
            }
            
            .aqualuxe-test-result-item {
                margin-bottom: 10px;
                padding-bottom: 10px;
                border-bottom: 1px solid #eee;
            }
            
            .aqualuxe-test-result-item:last-child {
                margin-bottom: 0;
                padding-bottom: 0;
                border-bottom: none;
            }
            
            .aqualuxe-test-result-item.pass {
                color: #46b450;
            }
            
            .aqualuxe-test-result-item.fail {
                color: #dc3232;
            }
            
            .aqualuxe-test-result-item.warning {
                color: #ffb900;
            }
        </style>
        
        <script>
        jQuery(document).ready(function($) {
            // Tab switching
            $('.aqualuxe-test-tabs li').on('click', function() {
                var tab = $(this).data('tab');
                
                // Update active tab
                $('.aqualuxe-test-tabs li').removeClass('active');
                $(this).addClass('active');
                
                // Show active content
                $('.aqualuxe-test-tab').removeClass('active');
                $('#' + tab + '-tab').addClass('active');
            });
            
            // Run tests
            $('.aqualuxe-test-actions button').on('click', function() {
                var test = $(this).data('test');
                var resultsContainer = $('#' + test.split('-')[0] + '-results');
                
                // Show loading
                resultsContainer.html('<p><?php esc_html_e( 'Running test...', 'aqualuxe' ); ?></p>');
                
                // Run test via AJAX
                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'aqualuxe_run_test',
                        test: test,
                        nonce: '<?php echo wp_create_nonce( 'aqualuxe_test_nonce' ); ?>'
                    },
                    success: function(response) {
                        if (response.success) {
                            resultsContainer.html(response.data);
                        } else {
                            resultsContainer.html('<p class="aqualuxe-test-result-item fail"><?php esc_html_e( 'Test failed. Please try again.', 'aqualuxe' ); ?></p>');
                        }
                    },
                    error: function() {
                        resultsContainer.html('<p class="aqualuxe-test-result-item fail"><?php esc_html_e( 'An error occurred. Please try again.', 'aqualuxe' ); ?></p>');
                    }
                });
            });
        });
        </script>
        <?php
    }

    /**
     * Run test
     */
    public function run_test() {
        // Check nonce
        if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'aqualuxe_test_nonce' ) ) {
            wp_send_json_error( esc_html__( 'Security check failed.', 'aqualuxe' ) );
        }

        // Check test
        if ( ! isset( $_POST['test'] ) ) {
            wp_send_json_error( esc_html__( 'No test specified.', 'aqualuxe' ) );
        }

        $test = sanitize_text_field( $_POST['test'] );
        $results = '';

        // Run the appropriate test
        switch ( $test ) {
            case 'responsive-mobile':
                $results = $this->test_responsive_mobile();
                break;
                
            case 'responsive-tablet':
                $results = $this->test_responsive_tablet();
                break;
                
            case 'responsive-desktop':
                $results = $this->test_responsive_desktop();
                break;
                
            case 'performance-assets':
                $results = $this->test_performance_assets();
                break;
                
            case 'performance-images':
                $results = $this->test_performance_images();
                break;
                
            case 'performance-lazy-loading':
                $results = $this->test_performance_lazy_loading();
                break;
                
            case 'accessibility-contrast':
                $results = $this->test_accessibility_contrast();
                break;
                
            case 'accessibility-keyboard':
                $results = $this->test_accessibility_keyboard();
                break;
                
            case 'accessibility-aria':
                $results = $this->test_accessibility_aria();
                break;
                
            case 'woocommerce-templates':
                $results = $this->test_woocommerce_templates();
                break;
                
            case 'woocommerce-checkout':
                $results = $this->test_woocommerce_checkout();
                break;
                
            case 'woocommerce-cart':
                $results = $this->test_woocommerce_cart();
                break;
                
            case 'seo-schema':
                $results = $this->test_seo_schema();
                break;
                
            case 'seo-opengraph':
                $results = $this->test_seo_opengraph();
                break;
                
            case 'seo-headings':
                $results = $this->test_seo_headings();
                break;
                
            case 'multilingual-strings':
                $results = $this->test_multilingual_strings();
                break;
                
            case 'multilingual-rtl':
                $results = $this->test_multilingual_rtl();
                break;
                
            case 'multilingual-plugins':
                $results = $this->test_multilingual_plugins();
                break;
                
            case 'dark-mode-toggle':
                $results = $this->test_dark_mode_toggle();
                break;
                
            case 'dark-mode-colors':
                $results = $this->test_dark_mode_colors();
                break;
                
            case 'dark-mode-images':
                $results = $this->test_dark_mode_images();
                break;
                
            default:
                wp_send_json_error( esc_html__( 'Invalid test.', 'aqualuxe' ) );
        }

        wp_send_json_success( $results );
    }

    /**
     * Test responsive mobile
     */
    private function test_responsive_mobile() {
        $results = '';
        $pass_count = 0;
        $fail_count = 0;
        $warning_count = 0;
        
        // Check viewport meta tag
        $home_url = home_url();
        $response = wp_remote_get( $home_url );
        $body = wp_remote_retrieve_body( $response );
        
        if ( strpos( $body, '<meta name="viewport"' ) !== false ) {
            $results .= $this->format_result( 'pass', esc_html__( 'Viewport meta tag is present.', 'aqualuxe' ) );
            $pass_count++;
        } else {
            $results .= $this->format_result( 'fail', esc_html__( 'Viewport meta tag is missing.', 'aqualuxe' ) );
            $fail_count++;
        }
        
        // Check mobile menu
        if ( strpos( $body, 'mobile-menu' ) !== false || strpos( $body, 'navbar-toggler' ) !== false ) {
            $results .= $this->format_result( 'pass', esc_html__( 'Mobile menu is implemented.', 'aqualuxe' ) );
            $pass_count++;
        } else {
            $results .= $this->format_result( 'warning', esc_html__( 'Mobile menu might not be properly implemented.', 'aqualuxe' ) );
            $warning_count++;
        }
        
        // Check for media queries in CSS
        $css_file = get_template_directory() . '/assets/css/main.css';
        if ( file_exists( $css_file ) ) {
            $css_content = file_get_contents( $css_file );
            if ( preg_match( '/@media\s*\(\s*max-width/', $css_content ) ) {
                $results .= $this->format_result( 'pass', esc_html__( 'Mobile media queries are implemented.', 'aqualuxe' ) );
                $pass_count++;
            } else {
                $results .= $this->format_result( 'warning', esc_html__( 'Mobile media queries might not be properly implemented.', 'aqualuxe' ) );
                $warning_count++;
            }
        } else {
            $results .= $this->format_result( 'warning', esc_html__( 'Could not check CSS file for media queries.', 'aqualuxe' ) );
            $warning_count++;
        }
        
        // Summary
        $results .= '<hr>';
        $results .= '<p><strong>' . esc_html__( 'Summary:', 'aqualuxe' ) . '</strong> ';
        $results .= sprintf(
            esc_html__( '%d passed, %d warnings, %d failed', 'aqualuxe' ),
            $pass_count,
            $warning_count,
            $fail_count
        );
        $results .= '</p>';
        
        return $results;
    }

    /**
     * Test responsive tablet
     */
    private function test_responsive_tablet() {
        $results = '';
        $pass_count = 0;
        $fail_count = 0;
        $warning_count = 0;
        
        // Check for tablet media queries in CSS
        $css_file = get_template_directory() . '/assets/css/main.css';
        if ( file_exists( $css_file ) ) {
            $css_content = file_get_contents( $css_file );
            if ( preg_match( '/@media\s*\(\s*min-width:\s*768px\s*\)/', $css_content ) || 
                 preg_match( '/@media\s*\(\s*max-width:\s*1024px\s*\)/', $css_content ) ) {
                $results .= $this->format_result( 'pass', esc_html__( 'Tablet media queries are implemented.', 'aqualuxe' ) );
                $pass_count++;
            } else {
                $results .= $this->format_result( 'warning', esc_html__( 'Tablet media queries might not be properly implemented.', 'aqualuxe' ) );
                $warning_count++;
            }
        } else {
            $results .= $this->format_result( 'warning', esc_html__( 'Could not check CSS file for media queries.', 'aqualuxe' ) );
            $warning_count++;
        }
        
        // Check for Tailwind responsive classes
        $template_files = glob( get_template_directory() . '/*.php' );
        $responsive_classes_found = false;
        
        foreach ( $template_files as $file ) {
            $content = file_get_contents( $file );
            if ( preg_match( '/md:[a-zA-Z0-9\-]+/', $content ) ) {
                $responsive_classes_found = true;
                break;
            }
        }
        
        if ( $responsive_classes_found ) {
            $results .= $this->format_result( 'pass', esc_html__( 'Responsive classes for tablet are implemented.', 'aqualuxe' ) );
            $pass_count++;
        } else {
            $results .= $this->format_result( 'warning', esc_html__( 'Responsive classes for tablet might not be properly implemented.', 'aqualuxe' ) );
            $warning_count++;
        }
        
        // Summary
        $results .= '<hr>';
        $results .= '<p><strong>' . esc_html__( 'Summary:', 'aqualuxe' ) . '</strong> ';
        $results .= sprintf(
            esc_html__( '%d passed, %d warnings, %d failed', 'aqualuxe' ),
            $pass_count,
            $warning_count,
            $fail_count
        );
        $results .= '</p>';
        
        return $results;
    }

    /**
     * Test responsive desktop
     */
    private function test_responsive_desktop() {
        $results = '';
        $pass_count = 0;
        $fail_count = 0;
        $warning_count = 0;
        
        // Check for desktop media queries in CSS
        $css_file = get_template_directory() . '/assets/css/main.css';
        if ( file_exists( $css_file ) ) {
            $css_content = file_get_contents( $css_file );
            if ( preg_match( '/@media\s*\(\s*min-width:\s*1024px\s*\)/', $css_content ) ) {
                $results .= $this->format_result( 'pass', esc_html__( 'Desktop media queries are implemented.', 'aqualuxe' ) );
                $pass_count++;
            } else {
                $results .= $this->format_result( 'warning', esc_html__( 'Desktop media queries might not be properly implemented.', 'aqualuxe' ) );
                $warning_count++;
            }
        } else {
            $results .= $this->format_result( 'warning', esc_html__( 'Could not check CSS file for media queries.', 'aqualuxe' ) );
            $warning_count++;
        }
        
        // Check for Tailwind responsive classes
        $template_files = glob( get_template_directory() . '/*.php' );
        $responsive_classes_found = false;
        
        foreach ( $template_files as $file ) {
            $content = file_get_contents( $file );
            if ( preg_match( '/lg:[a-zA-Z0-9\-]+/', $content ) ) {
                $responsive_classes_found = true;
                break;
            }
        }
        
        if ( $responsive_classes_found ) {
            $results .= $this->format_result( 'pass', esc_html__( 'Responsive classes for desktop are implemented.', 'aqualuxe' ) );
            $pass_count++;
        } else {
            $results .= $this->format_result( 'warning', esc_html__( 'Responsive classes for desktop might not be properly implemented.', 'aqualuxe' ) );
            $warning_count++;
        }
        
        // Check container width
        $functions_file = get_template_directory() . '/functions.php';
        if ( file_exists( $functions_file ) ) {
            $functions_content = file_get_contents( $functions_file );
            if ( preg_match( '/container_width/', $functions_content ) ) {
                $results .= $this->format_result( 'pass', esc_html__( 'Container width is customizable.', 'aqualuxe' ) );
                $pass_count++;
            } else {
                $results .= $this->format_result( 'warning', esc_html__( 'Container width might not be customizable.', 'aqualuxe' ) );
                $warning_count++;
            }
        } else {
            $results .= $this->format_result( 'warning', esc_html__( 'Could not check functions.php file.', 'aqualuxe' ) );
            $warning_count++;
        }
        
        // Summary
        $results .= '<hr>';
        $results .= '<p><strong>' . esc_html__( 'Summary:', 'aqualuxe' ) . '</strong> ';
        $results .= sprintf(
            esc_html__( '%d passed, %d warnings, %d failed', 'aqualuxe' ),
            $pass_count,
            $warning_count,
            $fail_count
        );
        $results .= '</p>';
        
        return $results;
    }

    /**
     * Test performance assets
     */
    private function test_performance_assets() {
        $results = '';
        $pass_count = 0;
        $fail_count = 0;
        $warning_count = 0;
        
        // Check if scripts are enqueued properly
        $functions_file = get_template_directory() . '/functions.php';
        if ( file_exists( $functions_file ) ) {
            $functions_content = file_get_contents( $functions_file );
            if ( preg_match( '/wp_enqueue_script/', $functions_content ) ) {
                $results .= $this->format_result( 'pass', esc_html__( 'Scripts are enqueued properly.', 'aqualuxe' ) );
                $pass_count++;
            } else {
                $results .= $this->format_result( 'fail', esc_html__( 'Scripts might not be enqueued properly.', 'aqualuxe' ) );
                $fail_count++;
            }
        } else {
            $results .= $this->format_result( 'warning', esc_html__( 'Could not check functions.php file.', 'aqualuxe' ) );
            $warning_count++;
        }
        
        // Check if styles are enqueued properly
        if ( file_exists( $functions_file ) ) {
            $functions_content = file_get_contents( $functions_file );
            if ( preg_match( '/wp_enqueue_style/', $functions_content ) ) {
                $results .= $this->format_result( 'pass', esc_html__( 'Styles are enqueued properly.', 'aqualuxe' ) );
                $pass_count++;
            } else {
                $results .= $this->format_result( 'fail', esc_html__( 'Styles might not be enqueued properly.', 'aqualuxe' ) );
                $fail_count++;
            }
        }
        
        // Check if scripts are loaded in footer
        if ( file_exists( $functions_file ) ) {
            $functions_content = file_get_contents( $functions_file );
            if ( preg_match( '/wp_enqueue_script\([^,]+,[^,]+,[^,]+,[^,]+,\s*true\s*\)/', $functions_content ) ) {
                $results .= $this->format_result( 'pass', esc_html__( 'Scripts are loaded in footer.', 'aqualuxe' ) );
                $pass_count++;
            } else {
                $results .= $this->format_result( 'warning', esc_html__( 'Scripts might not be loaded in footer.', 'aqualuxe' ) );
                $warning_count++;
            }
        }
        
        // Check if assets are minified
        $js_file = get_template_directory() . '/assets/js/main.js';
        if ( file_exists( $js_file ) ) {
            $js_content = file_get_contents( $js_file );
            $js_size = strlen( $js_content );
            $js_lines = substr_count( $js_content, "\n" );
            
            if ( $js_size / $js_lines < 100 ) { // Rough estimate for minified JS
                $results .= $this->format_result( 'warning', esc_html__( 'JavaScript files might not be minified.', 'aqualuxe' ) );
                $warning_count++;
            } else {
                $results .= $this->format_result( 'pass', esc_html__( 'JavaScript files appear to be minified.', 'aqualuxe' ) );
                $pass_count++;
            }
        } else {
            $results .= $this->format_result( 'warning', esc_html__( 'Could not check JavaScript files.', 'aqualuxe' ) );
            $warning_count++;
        }
        
        // Check if CSS is minified
        $css_file = get_template_directory() . '/assets/css/main.css';
        if ( file_exists( $css_file ) ) {
            $css_content = file_get_contents( $css_file );
            $css_size = strlen( $css_content );
            $css_lines = substr_count( $css_content, "\n" );
            
            if ( $css_size / $css_lines < 100 ) { // Rough estimate for minified CSS
                $results .= $this->format_result( 'warning', esc_html__( 'CSS files might not be minified.', 'aqualuxe' ) );
                $warning_count++;
            } else {
                $results .= $this->format_result( 'pass', esc_html__( 'CSS files appear to be minified.', 'aqualuxe' ) );
                $pass_count++;
            }
        } else {
            $results .= $this->format_result( 'warning', esc_html__( 'Could not check CSS files.', 'aqualuxe' ) );
            $warning_count++;
        }
        
        // Summary
        $results .= '<hr>';
        $results .= '<p><strong>' . esc_html__( 'Summary:', 'aqualuxe' ) . '</strong> ';
        $results .= sprintf(
            esc_html__( '%d passed, %d warnings, %d failed', 'aqualuxe' ),
            $pass_count,
            $warning_count,
            $fail_count
        );
        $results .= '</p>';
        
        return $results;
    }

    /**
     * Test performance images
     */
    private function test_performance_images() {
        $results = '';
        $pass_count = 0;
        $fail_count = 0;
        $warning_count = 0;
        
        // Check if responsive images are enabled
        $functions_file = get_template_directory() . '/functions.php';
        if ( file_exists( $functions_file ) ) {
            $functions_content = file_get_contents( $functions_file );
            if ( preg_match( '/add_theme_support\(\s*[\'"]responsive-embeds[\'"]/', $functions_content ) ) {
                $results .= $this->format_result( 'pass', esc_html__( 'Responsive embeds are enabled.', 'aqualuxe' ) );
                $pass_count++;
            } else {
                $results .= $this->format_result( 'warning', esc_html__( 'Responsive embeds might not be enabled.', 'aqualuxe' ) );
                $warning_count++;
            }
        } else {
            $results .= $this->format_result( 'warning', esc_html__( 'Could not check functions.php file.', 'aqualuxe' ) );
            $warning_count++;
        }
        
        // Check if custom image sizes are defined
        if ( file_exists( $functions_file ) ) {
            $functions_content = file_get_contents( $functions_file );
            if ( preg_match( '/add_image_size/', $functions_content ) ) {
                $results .= $this->format_result( 'pass', esc_html__( 'Custom image sizes are defined.', 'aqualuxe' ) );
                $pass_count++;
            } else {
                $results .= $this->format_result( 'warning', esc_html__( 'Custom image sizes might not be defined.', 'aqualuxe' ) );
                $warning_count++;
            }
        }
        
        // Check if images have width and height attributes
        $home_url = home_url();
        $response = wp_remote_get( $home_url );
        $body = wp_remote_retrieve_body( $response );
        
        if ( preg_match_all( '/<img[^>]+>/', $body, $matches ) ) {
            $total_images = count( $matches[0] );
            $images_with_dimensions = 0;
            
            foreach ( $matches[0] as $img ) {
                if ( preg_match( '/width=["\'][0-9]+["\']/', $img ) && preg_match( '/height=["\'][0-9]+["\']/', $img ) ) {
                    $images_with_dimensions++;
                }
            }
            
            if ( $total_images > 0 && $images_with_dimensions / $total_images >= 0.8 ) {
                $results .= $this->format_result( 'pass', sprintf( esc_html__( '%d out of %d images have width and height attributes.', 'aqualuxe' ), $images_with_dimensions, $total_images ) );
                $pass_count++;
            } else {
                $results .= $this->format_result( 'warning', sprintf( esc_html__( 'Only %d out of %d images have width and height attributes.', 'aqualuxe' ), $images_with_dimensions, $total_images ) );
                $warning_count++;
            }
        } else {
            $results .= $this->format_result( 'warning', esc_html__( 'No images found on the homepage.', 'aqualuxe' ) );
            $warning_count++;
        }
        
        // Check if srcset is used
        if ( preg_match_all( '/<img[^>]+>/', $body, $matches ) ) {
            $total_images = count( $matches[0] );
            $images_with_srcset = 0;
            
            foreach ( $matches[0] as $img ) {
                if ( preg_match( '/srcset=["\'][^"\']+["\']/', $img ) ) {
                    $images_with_srcset++;
                }
            }
            
            if ( $total_images > 0 && $images_with_srcset / $total_images >= 0.8 ) {
                $results .= $this->format_result( 'pass', sprintf( esc_html__( '%d out of %d images use srcset.', 'aqualuxe' ), $images_with_srcset, $total_images ) );
                $pass_count++;
            } else {
                $results .= $this->format_result( 'warning', sprintf( esc_html__( 'Only %d out of %d images use srcset.', 'aqualuxe' ), $images_with_srcset, $total_images ) );
                $warning_count++;
            }
        }
        
        // Summary
        $results .= '<hr>';
        $results .= '<p><strong>' . esc_html__( 'Summary:', 'aqualuxe' ) . '</strong> ';
        $results .= sprintf(
            esc_html__( '%d passed, %d warnings, %d failed', 'aqualuxe' ),
            $pass_count,
            $warning_count,
            $fail_count
        );
        $results .= '</p>';
        
        return $results;
    }

    /**
     * Test performance lazy loading
     */
    private function test_performance_lazy_loading() {
        $results = '';
        $pass_count = 0;
        $fail_count = 0;
        $warning_count = 0;
        
        // Check if lazy loading is implemented
        $lazy_loading_file = get_template_directory() . '/inc/lazy-loading.php';
        if ( file_exists( $lazy_loading_file ) ) {
            $results .= $this->format_result( 'pass', esc_html__( 'Lazy loading file exists.', 'aqualuxe' ) );
            $pass_count++;
            
            // Check if lazy loading is properly implemented
            $lazy_loading_content = file_get_contents( $lazy_loading_file );
            if ( preg_match( '/loading=["\']lazy["\']/', $lazy_loading_content ) ) {
                $results .= $this->format_result( 'pass', esc_html__( 'Native lazy loading is implemented.', 'aqualuxe' ) );
                $pass_count++;
            } else {
                $results .= $this->format_result( 'warning', esc_html__( 'Native lazy loading might not be implemented.', 'aqualuxe' ) );
                $warning_count++;
            }
            
            // Check if JavaScript lazy loading is implemented
            if ( preg_match( '/IntersectionObserver/', $lazy_loading_content ) ) {
                $results .= $this->format_result( 'pass', esc_html__( 'JavaScript lazy loading is implemented.', 'aqualuxe' ) );
                $pass_count++;
            } else {
                $results .= $this->format_result( 'warning', esc_html__( 'JavaScript lazy loading might not be implemented.', 'aqualuxe' ) );
                $warning_count++;
            }
        } else {
            $results .= $this->format_result( 'fail', esc_html__( 'Lazy loading file does not exist.', 'aqualuxe' ) );
            $fail_count++;
        }
        
        // Check if lazy loading is applied to images
        $home_url = home_url();
        $response = wp_remote_get( $home_url );
        $body = wp_remote_retrieve_body( $response );
        
        if ( preg_match_all( '/<img[^>]+>/', $body, $matches ) ) {
            $total_images = count( $matches[0] );
            $images_with_lazy_loading = 0;
            
            foreach ( $matches[0] as $img ) {
                if ( preg_match( '/loading=["\']lazy["\']/', $img ) || preg_match( '/data-src=/', $img ) ) {
                    $images_with_lazy_loading++;
                }
            }
            
            if ( $total_images > 0 && $images_with_lazy_loading / $total_images >= 0.8 ) {
                $results .= $this->format_result( 'pass', sprintf( esc_html__( '%d out of %d images have lazy loading.', 'aqualuxe' ), $images_with_lazy_loading, $total_images ) );
                $pass_count++;
            } else {
                $results .= $this->format_result( 'warning', sprintf( esc_html__( 'Only %d out of %d images have lazy loading.', 'aqualuxe' ), $images_with_lazy_loading, $total_images ) );
                $warning_count++;
            }
        } else {
            $results .= $this->format_result( 'warning', esc_html__( 'No images found on the homepage.', 'aqualuxe' ) );
            $warning_count++;
        }
        
        // Check if lazy loading is applied to iframes
        if ( preg_match_all( '/<iframe[^>]+>/', $body, $matches ) ) {
            $total_iframes = count( $matches[0] );
            $iframes_with_lazy_loading = 0;
            
            foreach ( $matches[0] as $iframe ) {
                if ( preg_match( '/loading=["\']lazy["\']/', $iframe ) || preg_match( '/data-src=/', $iframe ) ) {
                    $iframes_with_lazy_loading++;
                }
            }
            
            if ( $total_iframes > 0 && $iframes_with_lazy_loading / $total_iframes >= 0.8 ) {
                $results .= $this->format_result( 'pass', sprintf( esc_html__( '%d out of %d iframes have lazy loading.', 'aqualuxe' ), $iframes_with_lazy_loading, $total_iframes ) );
                $pass_count++;
            } else if ( $total_iframes > 0 ) {
                $results .= $this->format_result( 'warning', sprintf( esc_html__( 'Only %d out of %d iframes have lazy loading.', 'aqualuxe' ), $iframes_with_lazy_loading, $total_iframes ) );
                $warning_count++;
            } else {
                $results .= $this->format_result( 'info', esc_html__( 'No iframes found on the homepage.', 'aqualuxe' ) );
            }
        }
        
        // Summary
        $results .= '<hr>';
        $results .= '<p><strong>' . esc_html__( 'Summary:', 'aqualuxe' ) . '</strong> ';
        $results .= sprintf(
            esc_html__( '%d passed, %d warnings, %d failed', 'aqualuxe' ),
            $pass_count,
            $warning_count,
            $fail_count
        );
        $results .= '</p>';
        
        return $results;
    }

    /**
     * Test accessibility contrast
     */
    private function test_accessibility_contrast() {
        $results = '';
        $pass_count = 0;
        $fail_count = 0;
        $warning_count = 0;
        
        // Check if color variables are defined
        $functions_file = get_template_directory() . '/functions.php';
        if ( file_exists( $functions_file ) ) {
            $functions_content = file_get_contents( $functions_file );
            
            // Check text color
            if ( preg_match( '/text_color/', $functions_content ) ) {
                $results .= $this->format_result( 'pass', esc_html__( 'Text color is customizable.', 'aqualuxe' ) );
                $pass_count++;
            } else {
                $results .= $this->format_result( 'warning', esc_html__( 'Text color might not be customizable.', 'aqualuxe' ) );
                $warning_count++;
            }
            
            // Check background color
            if ( preg_match( '/background_color/', $functions_content ) ) {
                $results .= $this->format_result( 'pass', esc_html__( 'Background color is customizable.', 'aqualuxe' ) );
                $pass_count++;
            } else {
                $results .= $this->format_result( 'warning', esc_html__( 'Background color might not be customizable.', 'aqualuxe' ) );
                $warning_count++;
            }
        } else {
            $results .= $this->format_result( 'warning', esc_html__( 'Could not check functions.php file.', 'aqualuxe' ) );
            $warning_count++;
        }
        
        // Check if dark mode has proper contrast
        $dark_mode_file = get_template_directory() . '/inc/dark-mode.php';
        if ( file_exists( $dark_mode_file ) ) {
            $dark_mode_content = file_get_contents( $dark_mode_file );
            if ( preg_match( '/color/', $dark_mode_content ) ) {
                $results .= $this->format_result( 'pass', esc_html__( 'Dark mode colors are defined.', 'aqualuxe' ) );
                $pass_count++;
            } else {
                $results .= $this->format_result( 'warning', esc_html__( 'Dark mode colors might not be properly defined.', 'aqualuxe' ) );
                $warning_count++;
            }
        } else {
            $results .= $this->format_result( 'warning', esc_html__( 'Could not check dark-mode.php file.', 'aqualuxe' ) );
            $warning_count++;
        }
        
        // Check if links have proper contrast
        $css_file = get_template_directory() . '/assets/css/main.css';
        if ( file_exists( $css_file ) ) {
            $css_content = file_get_contents( $css_file );
            if ( preg_match( '/a\s*{[^}]*color/', $css_content ) ) {
                $results .= $this->format_result( 'pass', esc_html__( 'Link colors are defined.', 'aqualuxe' ) );
                $pass_count++;
            } else {
                $results .= $this->format_result( 'warning', esc_html__( 'Link colors might not be properly defined.', 'aqualuxe' ) );
                $warning_count++;
            }
        } else {
            $results .= $this->format_result( 'warning', esc_html__( 'Could not check CSS file.', 'aqualuxe' ) );
            $warning_count++;
        }
        
        // Check if form elements have proper contrast
        if ( file_exists( $css_file ) ) {
            $css_content = file_get_contents( $css_file );
            if ( preg_match( '/input\s*{[^}]*color/', $css_content ) || preg_match( '/button\s*{[^}]*color/', $css_content ) ) {
                $results .= $this->format_result( 'pass', esc_html__( 'Form element colors are defined.', 'aqualuxe' ) );
                $pass_count++;
            } else {
                $results .= $this->format_result( 'warning', esc_html__( 'Form element colors might not be properly defined.', 'aqualuxe' ) );
                $warning_count++;
            }
        }
        
        // Summary
        $results .= '<hr>';
        $results .= '<p><strong>' . esc_html__( 'Summary:', 'aqualuxe' ) . '</strong> ';
        $results .= sprintf(
            esc_html__( '%d passed, %d warnings, %d failed', 'aqualuxe' ),
            $pass_count,
            $warning_count,
            $fail_count
        );
        $results .= '</p>';
        
        return $results;
    }

    /**
     * Test accessibility keyboard
     */
    private function test_accessibility_keyboard() {
        $results = '';
        $pass_count = 0;
        $fail_count = 0;
        $warning_count = 0;
        
        // Check if focus styles are defined
        $css_file = get_template_directory() . '/assets/css/main.css';
        if ( file_exists( $css_file ) ) {
            $css_content = file_get_contents( $css_file );
            if ( preg_match( '/focus/', $css_content ) ) {
                $results .= $this->format_result( 'pass', esc_html__( 'Focus styles are defined.', 'aqualuxe' ) );
                $pass_count++;
            } else {
                $results .= $this->format_result( 'warning', esc_html__( 'Focus styles might not be properly defined.', 'aqualuxe' ) );
                $warning_count++;
            }
        } else {
            $results .= $this->format_result( 'warning', esc_html__( 'Could not check CSS file.', 'aqualuxe' ) );
            $warning_count++;
        }
        
        // Check if skip links are implemented
        $header_file = get_template_directory() . '/header.php';
        if ( file_exists( $header_file ) ) {
            $header_content = file_get_contents( $header_file );
            if ( preg_match( '/skip-link/', $header_content ) || preg_match( '/skip-to-content/', $header_content ) ) {
                $results .= $this->format_result( 'pass', esc_html__( 'Skip links are implemented.', 'aqualuxe' ) );
                $pass_count++;
            } else {
                $results .= $this->format_result( 'warning', esc_html__( 'Skip links might not be implemented.', 'aqualuxe' ) );
                $warning_count++;
            }
        } else {
            $results .= $this->format_result( 'warning', esc_html__( 'Could not check header.php file.', 'aqualuxe' ) );
            $warning_count++;
        }
        
        // Check if dropdown menus are keyboard accessible
        $js_file = get_template_directory() . '/assets/js/main.js';
        if ( file_exists( $js_file ) ) {
            $js_content = file_get_contents( $js_file );
            if ( preg_match( '/keydown|keyup|keypress/', $js_content ) ) {
                $results .= $this->format_result( 'pass', esc_html__( 'Keyboard events are handled in JavaScript.', 'aqualuxe' ) );
                $pass_count++;
            } else {
                $results .= $this->format_result( 'warning', esc_html__( 'Keyboard events might not be properly handled in JavaScript.', 'aqualuxe' ) );
                $warning_count++;
            }
        } else {
            $results .= $this->format_result( 'warning', esc_html__( 'Could not check JavaScript file.', 'aqualuxe' ) );
            $warning_count++;
        }
        
        // Check if tabindex is used properly
        $template_files = glob( get_template_directory() . '/*.php' );
        $tabindex_issues = false;
        
        foreach ( $template_files as $file ) {
            $content = file_get_contents( $file );
            if ( preg_match( '/tabindex=["\'][1-9][0-9]*["\']/', $content ) ) {
                $tabindex_issues = true;
                break;
            }
        }
        
        if ( ! $tabindex_issues ) {
            $results .= $this->format_result( 'pass', esc_html__( 'No tabindex issues found.', 'aqualuxe' ) );
            $pass_count++;
        } else {
            $results .= $this->format_result( 'warning', esc_html__( 'Positive tabindex values found, which might cause keyboard navigation issues.', 'aqualuxe' ) );
            $warning_count++;
        }
        
        // Summary
        $results .= '<hr>';
        $results .= '<p><strong>' . esc_html__( 'Summary:', 'aqualuxe' ) . '</strong> ';
        $results .= sprintf(
            esc_html__( '%d passed, %d warnings, %d failed', 'aqualuxe' ),
            $pass_count,
            $warning_count,
            $fail_count
        );
        $results .= '</p>';
        
        return $results;
    }

    /**
     * Test accessibility ARIA
     */
    private function test_accessibility_aria() {
        $results = '';
        $pass_count = 0;
        $fail_count = 0;
        $warning_count = 0;
        
        // Check if ARIA attributes are used
        $template_files = glob( get_template_directory() . '/*.php' );
        $aria_used = false;
        
        foreach ( $template_files as $file ) {
            $content = file_get_contents( $file );
            if ( preg_match( '/aria-[a-z]+=["\'][^"\']*["\']/', $content ) ) {
                $aria_used = true;
                break;
            }
        }
        
        if ( $aria_used ) {
            $results .= $this->format_result( 'pass', esc_html__( 'ARIA attributes are used.', 'aqualuxe' ) );
            $pass_count++;
        } else {
            $results .= $this->format_result( 'warning', esc_html__( 'ARIA attributes might not be used.', 'aqualuxe' ) );
            $warning_count++;
        }
        
        // Check if role attributes are used
        $role_used = false;
        
        foreach ( $template_files as $file ) {
            $content = file_get_contents( $file );
            if ( preg_match( '/role=["\'][^"\']*["\']/', $content ) ) {
                $role_used = true;
                break;
            }
        }
        
        if ( $role_used ) {
            $results .= $this->format_result( 'pass', esc_html__( 'Role attributes are used.', 'aqualuxe' ) );
            $pass_count++;
        } else {
            $results .= $this->format_result( 'warning', esc_html__( 'Role attributes might not be used.', 'aqualuxe' ) );
            $warning_count++;
        }
        
        // Check if form elements have labels
        $form_files = array();
        foreach ( $template_files as $file ) {
            $content = file_get_contents( $file );
            if ( preg_match( '/<form/', $content ) || preg_match( '/<input/', $content ) ) {
                $form_files[] = $file;
            }
        }
        
        if ( ! empty( $form_files ) ) {
            $label_issues = false;
            
            foreach ( $form_files as $file ) {
                $content = file_get_contents( $file );
                if ( preg_match_all( '/<input[^>]+>/', $content, $matches ) ) {
                    foreach ( $matches[0] as $input ) {
                        if ( ! preg_match( '/type=["\'](?:submit|button|hidden)["\']/', $input ) && 
                             ! preg_match( '/id=["\'][^"\']*["\']/', $input ) ) {
                            $label_issues = true;
                            break;
                        }
                    }
                }
            }
            
            if ( ! $label_issues ) {
                $results .= $this->format_result( 'pass', esc_html__( 'Form elements have proper ID attributes for labels.', 'aqualuxe' ) );
                $pass_count++;
            } else {
                $results .= $this->format_result( 'warning', esc_html__( 'Some form elements might not have proper ID attributes for labels.', 'aqualuxe' ) );
                $warning_count++;
            }
        } else {
            $results .= $this->format_result( 'info', esc_html__( 'No form elements found.', 'aqualuxe' ) );
        }
        
        // Check if images have alt attributes
        $home_url = home_url();
        $response = wp_remote_get( $home_url );
        $body = wp_remote_retrieve_body( $response );
        
        if ( preg_match_all( '/<img[^>]+>/', $body, $matches ) ) {
            $total_images = count( $matches[0] );
            $images_with_alt = 0;
            
            foreach ( $matches[0] as $img ) {
                if ( preg_match( '/alt=["\'][^"\']*["\']/', $img ) ) {
                    $images_with_alt++;
                }
            }
            
            if ( $total_images > 0 && $images_with_alt / $total_images >= 0.9 ) {
                $results .= $this->format_result( 'pass', sprintf( esc_html__( '%d out of %d images have alt attributes.', 'aqualuxe' ), $images_with_alt, $total_images ) );
                $pass_count++;
            } else {
                $results .= $this->format_result( 'warning', sprintf( esc_html__( 'Only %d out of %d images have alt attributes.', 'aqualuxe' ), $images_with_alt, $total_images ) );
                $warning_count++;
            }
        } else {
            $results .= $this->format_result( 'warning', esc_html__( 'No images found on the homepage.', 'aqualuxe' ) );
            $warning_count++;
        }
        
        // Summary
        $results .= '<hr>';
        $results .= '<p><strong>' . esc_html__( 'Summary:', 'aqualuxe' ) . '</strong> ';
        $results .= sprintf(
            esc_html__( '%d passed, %d warnings, %d failed', 'aqualuxe' ),
            $pass_count,
            $warning_count,
            $fail_count
        );
        $results .= '</p>';
        
        return $results;
    }

    /**
     * Test WooCommerce templates
     */
    private function test_woocommerce_templates() {
        $results = '';
        $pass_count = 0;
        $fail_count = 0;
        $warning_count = 0;
        
        // Check if WooCommerce is active
        if ( ! class_exists( 'WooCommerce' ) ) {
            return $this->format_result( 'warning', esc_html__( 'WooCommerce is not active. Please activate WooCommerce to run this test.', 'aqualuxe' ) );
        }
        
        // Check if WooCommerce support is added
        $functions_file = get_template_directory() . '/functions.php';
        if ( file_exists( $functions_file ) ) {
            $functions_content = file_get_contents( $functions_file );
            if ( preg_match( '/add_theme_support\(\s*[\'"]woocommerce[\'"]/', $functions_content ) ) {
                $results .= $this->format_result( 'pass', esc_html__( 'WooCommerce support is added.', 'aqualuxe' ) );
                $pass_count++;
            } else {
                $results .= $this->format_result( 'fail', esc_html__( 'WooCommerce support is not added.', 'aqualuxe' ) );
                $fail_count++;
            }
        } else {
            $results .= $this->format_result( 'warning', esc_html__( 'Could not check functions.php file.', 'aqualuxe' ) );
            $warning_count++;
        }
        
        // Check if WooCommerce templates are overridden
        $woocommerce_dir = get_template_directory() . '/woocommerce';
        if ( is_dir( $woocommerce_dir ) ) {
            $results .= $this->format_result( 'pass', esc_html__( 'WooCommerce templates directory exists.', 'aqualuxe' ) );
            $pass_count++;
            
            // Check specific templates
            $templates = array(
                'archive-product.php',
                'single-product.php',
                'cart/cart.php',
                'checkout/form-checkout.php',
                'myaccount/my-account.php',
            );
            
            $found_templates = 0;
            foreach ( $templates as $template ) {
                if ( file_exists( $woocommerce_dir . '/' . $template ) ) {
                    $found_templates++;
                }
            }
            
            if ( $found_templates === count( $templates ) ) {
                $results .= $this->format_result( 'pass', esc_html__( 'All required WooCommerce templates are overridden.', 'aqualuxe' ) );
                $pass_count++;
            } else {
                $results .= $this->format_result( 'warning', sprintf( esc_html__( 'Only %d out of %d required WooCommerce templates are overridden.', 'aqualuxe' ), $found_templates, count( $templates ) ) );
                $warning_count++;
            }
        } else {
            $results .= $this->format_result( 'warning', esc_html__( 'WooCommerce templates directory does not exist.', 'aqualuxe' ) );
            $warning_count++;
        }
        
        // Check if WooCommerce functions file exists
        $woocommerce_functions = get_template_directory() . '/inc/woocommerce.php';
        if ( file_exists( $woocommerce_functions ) ) {
            $results .= $this->format_result( 'pass', esc_html__( 'WooCommerce functions file exists.', 'aqualuxe' ) );
            $pass_count++;
            
            // Check if WooCommerce functions are implemented
            $woocommerce_content = file_get_contents( $woocommerce_functions );
            if ( preg_match( '/add_filter\(\s*[\'"]woocommerce_/', $woocommerce_content ) || 
                 preg_match( '/add_action\(\s*[\'"]woocommerce_/', $woocommerce_content ) ) {
                $results .= $this->format_result( 'pass', esc_html__( 'WooCommerce hooks are used.', 'aqualuxe' ) );
                $pass_count++;
            } else {
                $results .= $this->format_result( 'warning', esc_html__( 'WooCommerce hooks might not be used.', 'aqualuxe' ) );
                $warning_count++;
            }
        } else {
            $results .= $this->format_result( 'warning', esc_html__( 'WooCommerce functions file does not exist.', 'aqualuxe' ) );
            $warning_count++;
        }
        
        // Summary
        $results .= '<hr>';
        $results .= '<p><strong>' . esc_html__( 'Summary:', 'aqualuxe' ) . '</strong> ';
        $results .= sprintf(
            esc_html__( '%d passed, %d warnings, %d failed', 'aqualuxe' ),
            $pass_count,
            $warning_count,
            $fail_count
        );
        $results .= '</p>';
        
        return $results;
    }

    /**
     * Test WooCommerce checkout
     */
    private function test_woocommerce_checkout() {
        $results = '';
        $pass_count = 0;
        $fail_count = 0;
        $warning_count = 0;
        
        // Check if WooCommerce is active
        if ( ! class_exists( 'WooCommerce' ) ) {
            return $this->format_result( 'warning', esc_html__( 'WooCommerce is not active. Please activate WooCommerce to run this test.', 'aqualuxe' ) );
        }
        
        // Check if checkout template exists
        $checkout_template = get_template_directory() . '/woocommerce/checkout/form-checkout.php';
        if ( file_exists( $checkout_template ) ) {
            $results .= $this->format_result( 'pass', esc_html__( 'Checkout template exists.', 'aqualuxe' ) );
            $pass_count++;
            
            // Check if checkout template has required hooks
            $checkout_content = file_get_contents( $checkout_template );
            $required_hooks = array(
                'woocommerce_checkout_before_customer_details',
                'woocommerce_checkout_after_customer_details',
                'woocommerce_checkout_before_order_review_heading',
                'woocommerce_checkout_before_order_review',
                'woocommerce_checkout_after_order_review',
            );
            
            $found_hooks = 0;
            foreach ( $required_hooks as $hook ) {
                if ( preg_match( '/' . preg_quote( $hook, '/' ) . '/', $checkout_content ) ) {
                    $found_hooks++;
                }
            }
            
            if ( $found_hooks === count( $required_hooks ) ) {
                $results .= $this->format_result( 'pass', esc_html__( 'All required checkout hooks are implemented.', 'aqualuxe' ) );
                $pass_count++;
            } else {
                $results .= $this->format_result( 'warning', sprintf( esc_html__( 'Only %d out of %d required checkout hooks are implemented.', 'aqualuxe' ), $found_hooks, count( $required_hooks ) ) );
                $warning_count++;
            }
        } else {
            $results .= $this->format_result( 'warning', esc_html__( 'Checkout template does not exist.', 'aqualuxe' ) );
            $warning_count++;
        }
        
        // Check if payment methods template exists
        $payment_template = get_template_directory() . '/woocommerce/checkout/payment.php';
        if ( file_exists( $payment_template ) ) {
            $results .= $this->format_result( 'pass', esc_html__( 'Payment methods template exists.', 'aqualuxe' ) );
            $pass_count++;
        } else {
            $results .= $this->format_result( 'info', esc_html__( 'Payment methods template does not exist. Using WooCommerce default.', 'aqualuxe' ) );
        }
        
        // Check if thankyou template exists
        $thankyou_template = get_template_directory() . '/woocommerce/checkout/thankyou.php';
        if ( file_exists( $thankyou_template ) ) {
            $results .= $this->format_result( 'pass', esc_html__( 'Thank you template exists.', 'aqualuxe' ) );
            $pass_count++;
        } else {
            $results .= $this->format_result( 'info', esc_html__( 'Thank you template does not exist. Using WooCommerce default.', 'aqualuxe' ) );
        }
        
        // Check if checkout styles are defined
        $css_file = get_template_directory() . '/assets/css/main.css';
        if ( file_exists( $css_file ) ) {
            $css_content = file_get_contents( $css_file );
            if ( preg_match( '/woocommerce-checkout|checkout/', $css_content ) ) {
                $results .= $this->format_result( 'pass', esc_html__( 'Checkout styles are defined.', 'aqualuxe' ) );
                $pass_count++;
            } else {
                $results .= $this->format_result( 'warning', esc_html__( 'Checkout styles might not be defined.', 'aqualuxe' ) );
                $warning_count++;
            }
        } else {
            $results .= $this->format_result( 'warning', esc_html__( 'Could not check CSS file.', 'aqualuxe' ) );
            $warning_count++;
        }
        
        // Summary
        $results .= '<hr>';
        $results .= '<p><strong>' . esc_html__( 'Summary:', 'aqualuxe' ) . '</strong> ';
        $results .= sprintf(
            esc_html__( '%d passed, %d warnings, %d failed', 'aqualuxe' ),
            $pass_count,
            $warning_count,
            $fail_count
        );
        $results .= '</p>';
        
        return $results;
    }

    /**
     * Test WooCommerce cart
     */
    private function test_woocommerce_cart() {
        $results = '';
        $pass_count = 0;
        $fail_count = 0;
        $warning_count = 0;
        
        // Check if WooCommerce is active
        if ( ! class_exists( 'WooCommerce' ) ) {
            return $this->format_result( 'warning', esc_html__( 'WooCommerce is not active. Please activate WooCommerce to run this test.', 'aqualuxe' ) );
        }
        
        // Check if cart template exists
        $cart_template = get_template_directory() . '/woocommerce/cart/cart.php';
        if ( file_exists( $cart_template ) ) {
            $results .= $this->format_result( 'pass', esc_html__( 'Cart template exists.', 'aqualuxe' ) );
            $pass_count++;
            
            // Check if cart template has required hooks
            $cart_content = file_get_contents( $cart_template );
            $required_hooks = array(
                'woocommerce_before_cart',
                'woocommerce_after_cart',
                'woocommerce_before_cart_table',
                'woocommerce_after_cart_table',
            );
            
            $found_hooks = 0;
            foreach ( $required_hooks as $hook ) {
                if ( preg_match( '/' . preg_quote( $hook, '/' ) . '/', $cart_content ) ) {
                    $found_hooks++;
                }
            }
            
            if ( $found_hooks === count( $required_hooks ) ) {
                $results .= $this->format_result( 'pass', esc_html__( 'All required cart hooks are implemented.', 'aqualuxe' ) );
                $pass_count++;
            } else {
                $results .= $this->format_result( 'warning', sprintf( esc_html__( 'Only %d out of %d required cart hooks are implemented.', 'aqualuxe' ), $found_hooks, count( $required_hooks ) ) );
                $warning_count++;
            }
        } else {
            $results .= $this->format_result( 'warning', esc_html__( 'Cart template does not exist.', 'aqualuxe' ) );
            $warning_count++;
        }
        
        // Check if mini cart template exists
        $mini_cart_template = get_template_directory() . '/woocommerce/cart/mini-cart.php';
        if ( file_exists( $mini_cart_template ) ) {
            $results .= $this->format_result( 'pass', esc_html__( 'Mini cart template exists.', 'aqualuxe' ) );
            $pass_count++;
        } else {
            $results .= $this->format_result( 'info', esc_html__( 'Mini cart template does not exist. Using WooCommerce default.', 'aqualuxe' ) );
        }
        
        // Check if cart styles are defined
        $css_file = get_template_directory() . '/assets/css/main.css';
        if ( file_exists( $css_file ) ) {
            $css_content = file_get_contents( $css_file );
            if ( preg_match( '/woocommerce-cart|cart/', $css_content ) ) {
                $results .= $this->format_result( 'pass', esc_html__( 'Cart styles are defined.', 'aqualuxe' ) );
                $pass_count++;
            } else {
                $results .= $this->format_result( 'warning', esc_html__( 'Cart styles might not be defined.', 'aqualuxe' ) );
                $warning_count++;
            }
        } else {
            $results .= $this->format_result( 'warning', esc_html__( 'Could not check CSS file.', 'aqualuxe' ) );
            $warning_count++;
        }
        
        // Check if cart totals template exists
        $cart_totals_template = get_template_directory() . '/woocommerce/cart/cart-totals.php';
        if ( file_exists( $cart_totals_template ) ) {
            $results .= $this->format_result( 'pass', esc_html__( 'Cart totals template exists.', 'aqualuxe' ) );
            $pass_count++;
        } else {
            $results .= $this->format_result( 'info', esc_html__( 'Cart totals template does not exist. Using WooCommerce default.', 'aqualuxe' ) );
        }
        
        // Check if AJAX cart functionality is implemented
        $js_file = get_template_directory() . '/assets/js/woocommerce.js';
        if ( file_exists( $js_file ) ) {
            $js_content = file_get_contents( $js_file );
            if ( preg_match( '/add_to_cart|update_cart/', $js_content ) ) {
                $results .= $this->format_result( 'pass', esc_html__( 'AJAX cart functionality is implemented.', 'aqualuxe' ) );
                $pass_count++;
            } else {
                $results .= $this->format_result( 'warning', esc_html__( 'AJAX cart functionality might not be implemented.', 'aqualuxe' ) );
                $warning_count++;
            }
        } else {
            $results .= $this->format_result( 'warning', esc_html__( 'Could not check WooCommerce JavaScript file.', 'aqualuxe' ) );
            $warning_count++;
        }
        
        // Summary
        $results .= '<hr>';
        $results .= '<p><strong>' . esc_html__( 'Summary:', 'aqualuxe' ) . '</strong> ';
        $results .= sprintf(
            esc_html__( '%d passed, %d warnings, %d failed', 'aqualuxe' ),
            $pass_count,
            $warning_count,
            $fail_count
        );
        $results .= '</p>';
        
        return $results;
    }

    /**
     * Test SEO schema
     */
    private function test_seo_schema() {
        $results = '';
        $pass_count = 0;
        $fail_count = 0;
        $warning_count = 0;
        
        // Check if schema.php file exists
        $schema_file = get_template_directory() . '/inc/schema.php';
        if ( file_exists( $schema_file ) ) {
            $results .= $this->format_result( 'pass', esc_html__( 'Schema.org implementation file exists.', 'aqualuxe' ) );
            $pass_count++;
            
            // Check if schema.php has required implementations
            $schema_content = file_get_contents( $schema_file );
            $required_schemas = array(
                'WebPage',
                'Article',
                'Organization',
                'WebSite',
            );
            
            $found_schemas = 0;
            foreach ( $required_schemas as $schema ) {
                if ( preg_match( '/' . preg_quote( $schema, '/' ) . '/', $schema_content ) ) {
                    $found_schemas++;
                }
            }
            
            if ( $found_schemas === count( $required_schemas ) ) {
                $results .= $this->format_result( 'pass', esc_html__( 'All required schema types are implemented.', 'aqualuxe' ) );
                $pass_count++;
            } else {
                $results .= $this->format_result( 'warning', sprintf( esc_html__( 'Only %d out of %d required schema types are implemented.', 'aqualuxe' ), $found_schemas, count( $required_schemas ) ) );
                $warning_count++;
            }
            
            // Check if schema.php has WooCommerce product schema
            if ( preg_match( '/Product/', $schema_content ) ) {
                $results .= $this->format_result( 'pass', esc_html__( 'WooCommerce product schema is implemented.', 'aqualuxe' ) );
                $pass_count++;
            } else {
                $results .= $this->format_result( 'warning', esc_html__( 'WooCommerce product schema might not be implemented.', 'aqualuxe' ) );
                $warning_count++;
            }
        } else {
            $results .= $this->format_result( 'fail', esc_html__( 'Schema.org implementation file does not exist.', 'aqualuxe' ) );
            $fail_count++;
        }
        
        // Check if schema markup is added to HTML tag
        $home_url = home_url();
        $response = wp_remote_get( $home_url );
        $body = wp_remote_retrieve_body( $response );
        
        if ( preg_match( '/<html[^>]*itemscope/', $body ) && preg_match( '/<html[^>]*itemtype/', $body ) ) {
            $results .= $this->format_result( 'pass', esc_html__( 'Schema markup is added to HTML tag.', 'aqualuxe' ) );
            $pass_count++;
        } else {
            $results .= $this->format_result( 'warning', esc_html__( 'Schema markup might not be added to HTML tag.', 'aqualuxe' ) );
            $warning_count++;
        }
        
        // Check if JSON-LD schema is implemented
        if ( preg_match( '/<script type="application\/ld\+json">/', $body ) ) {
            $results .= $this->format_result( 'pass', esc_html__( 'JSON-LD schema is implemented.', 'aqualuxe' ) );
            $pass_count++;
        } else {
            $results .= $this->format_result( 'warning', esc_html__( 'JSON-LD schema might not be implemented.', 'aqualuxe' ) );
            $warning_count++;
        }
        
        // Summary
        $results .= '<hr>';
        $results .= '<p><strong>' . esc_html__( 'Summary:', 'aqualuxe' ) . '</strong> ';
        $results .= sprintf(
            esc_html__( '%d passed, %d warnings, %d failed', 'aqualuxe' ),
            $pass_count,
            $warning_count,
            $fail_count
        );
        $results .= '</p>';
        
        return $results;
    }

    /**
     * Test SEO Open Graph
     */
    private function test_seo_opengraph() {
        $results = '';
        $pass_count = 0;
        $fail_count = 0;
        $warning_count = 0;
        
        // Check if open-graph.php file exists
        $og_file = get_template_directory() . '/inc/open-graph.php';
        if ( file_exists( $og_file ) ) {
            $results .= $this->format_result( 'pass', esc_html__( 'Open Graph implementation file exists.', 'aqualuxe' ) );
            $pass_count++;
            
            // Check if open-graph.php has required implementations
            $og_content = file_get_contents( $og_file );
            $required_tags = array(
                'og:title',
                'og:description',
                'og:url',
                'og:image',
                'og:type',
            );
            
            $found_tags = 0;
            foreach ( $required_tags as $tag ) {
                if ( preg_match( '/' . preg_quote( $tag, '/' ) . '/', $og_content ) ) {
                    $found_tags++;
                }
            }
            
            if ( $found_tags === count( $required_tags ) ) {
                $results .= $this->format_result( 'pass', esc_html__( 'All required Open Graph tags are implemented.', 'aqualuxe' ) );
                $pass_count++;
            } else {
                $results .= $this->format_result( 'warning', sprintf( esc_html__( 'Only %d out of %d required Open Graph tags are implemented.', 'aqualuxe' ), $found_tags, count( $required_tags ) ) );
                $warning_count++;
            }
            
            // Check if Twitter Card is implemented
            if ( preg_match( '/twitter:card/', $og_content ) ) {
                $results .= $this->format_result( 'pass', esc_html__( 'Twitter Card is implemented.', 'aqualuxe' ) );
                $pass_count++;
            } else {
                $results .= $this->format_result( 'warning', esc_html__( 'Twitter Card might not be implemented.', 'aqualuxe' ) );
                $warning_count++;
            }
        } else {
            $results .= $this->format_result( 'fail', esc_html__( 'Open Graph implementation file does not exist.', 'aqualuxe' ) );
            $fail_count++;
        }
        
        // Check if Open Graph tags are added to head
        $home_url = home_url();
        $response = wp_remote_get( $home_url );
        $body = wp_remote_retrieve_body( $response );
        
        if ( preg_match( '/<meta property="og:/', $body ) ) {
            $results .= $this->format_result( 'pass', esc_html__( 'Open Graph tags are added to head.', 'aqualuxe' ) );
            $pass_count++;
        } else {
            $results .= $this->format_result( 'warning', esc_html__( 'Open Graph tags might not be added to head.', 'aqualuxe' ) );
            $warning_count++;
        }
        
        // Check if Twitter Card tags are added to head
        if ( preg_match( '/<meta name="twitter:/', $body ) ) {
            $results .= $this->format_result( 'pass', esc_html__( 'Twitter Card tags are added to head.', 'aqualuxe' ) );
            $pass_count++;
        } else {
            $results .= $this->format_result( 'warning', esc_html__( 'Twitter Card tags might not be added to head.', 'aqualuxe' ) );
            $warning_count++;
        }
        
        // Summary
        $results .= '<hr>';
        $results .= '<p><strong>' . esc_html__( 'Summary:', 'aqualuxe' ) . '</strong> ';
        $results .= sprintf(
            esc_html__( '%d passed, %d warnings, %d failed', 'aqualuxe' ),
            $pass_count,
            $warning_count,
            $fail_count
        );
        $results .= '</p>';
        
        return $results;
    }

    /**
     * Test SEO headings
     */
    private function test_seo_headings() {
        $results = '';
        $pass_count = 0;
        $fail_count = 0;
        $warning_count = 0;
        
        // Check if H1 is used properly
        $home_url = home_url();
        $response = wp_remote_get( $home_url );
        $body = wp_remote_retrieve_body( $response );
        
        if ( preg_match_all( '/<h1[^>]*>/', $body, $matches ) ) {
            $h1_count = count( $matches[0] );
            if ( $h1_count === 1 ) {
                $results .= $this->format_result( 'pass', esc_html__( 'H1 is used properly (exactly one H1 tag).', 'aqualuxe' ) );
                $pass_count++;
            } else {
                $results .= $this->format_result( 'warning', sprintf( esc_html__( 'H1 might not be used properly (%d H1 tags found).', 'aqualuxe' ), $h1_count ) );
                $warning_count++;
            }
        } else {
            $results .= $this->format_result( 'fail', esc_html__( 'No H1 tag found.', 'aqualuxe' ) );
            $fail_count++;
        }
        
        // Check if heading hierarchy is proper
        $headings = array();
        for ( $i = 1; $i <= 6; $i++ ) {
            if ( preg_match_all( '/<h' . $i . '[^>]*>/', $body, $matches ) ) {
                $headings[$i] = count( $matches[0] );
            } else {
                $headings[$i] = 0;
            }
        }
        
        $hierarchy_issues = false;
        for ( $i = 2; $i <= 6; $i++ ) {
            if ( $headings[$i] > 0 && $headings[$i - 1] === 0 ) {
                $hierarchy_issues = true;
                break;
            }
        }
        
        if ( ! $hierarchy_issues ) {
            $results .= $this->format_result( 'pass', esc_html__( 'Heading hierarchy is proper.', 'aqualuxe' ) );
            $pass_count++;
        } else {
            $results .= $this->format_result( 'warning', esc_html__( 'Heading hierarchy might not be proper.', 'aqualuxe' ) );
            $warning_count++;
        }
        
        // Check if headings have content
        $empty_headings = 0;
        for ( $i = 1; $i <= 6; $i++ ) {
            if ( preg_match_all( '/<h' . $i . '[^>]*>\s*<\/h' . $i . '>/i', $body, $matches ) ) {
                $empty_headings += count( $matches[0] );
            }
        }
        
        if ( $empty_headings === 0 ) {
            $results .= $this->format_result( 'pass', esc_html__( 'All headings have content.', 'aqualuxe' ) );
            $pass_count++;
        } else {
            $results .= $this->format_result( 'warning', sprintf( esc_html__( '%d empty headings found.', 'aqualuxe' ), $empty_headings ) );
            $warning_count++;
        }
        
        // Check if title tag is properly set
        if ( preg_match( '/<title>[^<]+<\/title>/', $body ) ) {
            $results .= $this->format_result( 'pass', esc_html__( 'Title tag is properly set.', 'aqualuxe' ) );
            $pass_count++;
        } else {
            $results .= $this->format_result( 'fail', esc_html__( 'Title tag is not properly set.', 'aqualuxe' ) );
            $fail_count++;
        }
        
        // Check if meta description is set
        if ( preg_match( '/<meta name="description" content="[^"]+">/', $body ) ) {
            $results .= $this->format_result( 'pass', esc_html__( 'Meta description is set.', 'aqualuxe' ) );
            $pass_count++;
        } else {
            $results .= $this->format_result( 'warning', esc_html__( 'Meta description might not be set.', 'aqualuxe' ) );
            $warning_count++;
        }
        
        // Summary
        $results .= '<hr>';
        $results .= '<p><strong>' . esc_html__( 'Summary:', 'aqualuxe' ) . '</strong> ';
        $results .= sprintf(
            esc_html__( '%d passed, %d warnings, %d failed', 'aqualuxe' ),
            $pass_count,
            $warning_count,
            $fail_count
        );
        $results .= '</p>';
        
        return $results;
    }

    /**
     * Test multilingual strings
     */
    private function test_multilingual_strings() {
        $results = '';
        $pass_count = 0;
        $fail_count = 0;
        $warning_count = 0;
        
        // Check if textdomain is loaded
        $functions_file = get_template_directory() . '/functions.php';
        if ( file_exists( $functions_file ) ) {
            $functions_content = file_get_contents( $functions_file );
            if ( preg_match( '/load_theme_textdomain/', $functions_content ) ) {
                $results .= $this->format_result( 'pass', esc_html__( 'Text domain is loaded.', 'aqualuxe' ) );
                $pass_count++;
            } else {
                $results .= $this->format_result( 'fail', esc_html__( 'Text domain is not loaded.', 'aqualuxe' ) );
                $fail_count++;
            }
        } else {
            $results .= $this->format_result( 'warning', esc_html__( 'Could not check functions.php file.', 'aqualuxe' ) );
            $warning_count++;
        }
        
        // Check if language directory exists
        $lang_dir = get_template_directory() . '/languages';
        if ( is_dir( $lang_dir ) ) {
            $results .= $this->format_result( 'pass', esc_html__( 'Language directory exists.', 'aqualuxe' ) );
            $pass_count++;
        } else {
            $results .= $this->format_result( 'warning', esc_html__( 'Language directory does not exist.', 'aqualuxe' ) );
            $warning_count++;
        }
        
        // Check if strings are properly translated
        $template_files = glob( get_template_directory() . '/*.php' );
        $translation_functions = array(
            '__',
            '_e',
            'esc_html__',
            'esc_html_e',
            'esc_attr__',
            'esc_attr_e',
        );
        
        $translated_strings = 0;
        $untranslated_strings = 0;
        
        foreach ( $template_files as $file ) {
            $content = file_get_contents( $file );
            
            // Count translated strings
            foreach ( $translation_functions as $function ) {
                $translated_strings += preg_match_all( '/' . preg_quote( $function, '/' ) . '\(\s*[\'"][^\'"]/', $content, $matches );
            }
            
            // Count untranslated strings (rough estimate)
            $untranslated_strings += preg_match_all( '/<[^>]*>[^<>]*[a-zA-Z]{3,}[^<>]*<\//', $content, $matches );
        }
        
        if ( $translated_strings > 0 && $untranslated_strings / $translated_strings < 0.5 ) {
            $results .= $this->format_result( 'pass', sprintf( esc_html__( 'Strings are properly translated (%d translated strings found).', 'aqualuxe' ), $translated_strings ) );
            $pass_count++;
        } else {
            $results .= $this->format_result( 'warning', sprintf( esc_html__( 'Some strings might not be properly translated (%d translated strings, approximately %d untranslated strings).', 'aqualuxe' ), $translated_strings, $untranslated_strings ) );
            $warning_count++;
        }
        
        // Check if POT file exists
        $pot_file = get_template_directory() . '/languages/aqualuxe.pot';
        if ( file_exists( $pot_file ) ) {
            $results .= $this->format_result( 'pass', esc_html__( 'POT file exists.', 'aqualuxe' ) );
            $pass_count++;
        } else {
            $results .= $this->format_result( 'warning', esc_html__( 'POT file does not exist.', 'aqualuxe' ) );
            $warning_count++;
        }
        
        // Check if multilingual support file exists
        $multilingual_file = get_template_directory() . '/inc/multilingual.php';
        if ( file_exists( $multilingual_file ) ) {
            $results .= $this->format_result( 'pass', esc_html__( 'Multilingual support file exists.', 'aqualuxe' ) );
            $pass_count++;
        } else {
            $results .= $this->format_result( 'warning', esc_html__( 'Multilingual support file does not exist.', 'aqualuxe' ) );
            $warning_count++;
        }
        
        // Summary
        $results .= '<hr>';
        $results .= '<p><strong>' . esc_html__( 'Summary:', 'aqualuxe' ) . '</strong> ';
        $results .= sprintf(
            esc_html__( '%d passed, %d warnings, %d failed', 'aqualuxe' ),
            $pass_count,
            $warning_count,
            $fail_count
        );
        $results .= '</p>';
        
        return $results;
    }

    /**
     * Test multilingual RTL
     */
    private function test_multilingual_rtl() {
        $results = '';
        $pass_count = 0;
        $fail_count = 0;
        $warning_count = 0;
        
        // Check if RTL stylesheet exists
        $rtl_file = get_template_directory() . '/assets/css/rtl.css';
        if ( file_exists( $rtl_file ) ) {
            $results .= $this->format_result( 'pass', esc_html__( 'RTL stylesheet exists.', 'aqualuxe' ) );
            $pass_count++;
        } else {
            $results .= $this->format_result( 'warning', esc_html__( 'RTL stylesheet does not exist.', 'aqualuxe' ) );
            $warning_count++;
        }
        
        // Check if is_rtl() function is used
        $functions_file = get_template_directory() . '/functions.php';
        if ( file_exists( $functions_file ) ) {
            $functions_content = file_get_contents( $functions_file );
            if ( preg_match( '/is_rtl\(\)/', $functions_content ) ) {
                $results .= $this->format_result( 'pass', esc_html__( 'is_rtl() function is used.', 'aqualuxe' ) );
                $pass_count++;
            } else {
                $results .= $this->format_result( 'warning', esc_html__( 'is_rtl() function might not be used.', 'aqualuxe' ) );
                $warning_count++;
            }
        } else {
            $results .= $this->format_result( 'warning', esc_html__( 'Could not check functions.php file.', 'aqualuxe' ) );
            $warning_count++;
        }
        
        // Check if RTL is handled in CSS
        $css_file = get_template_directory() . '/assets/css/main.css';
        if ( file_exists( $css_file ) ) {
            $css_content = file_get_contents( $css_file );
            if ( preg_match( '/[^-]right:|[^-]left:/', $css_content ) ) {
                $results .= $this->format_result( 'warning', esc_html__( 'CSS might not be RTL-friendly. Consider using logical properties (start/end) instead of physical properties (left/right).', 'aqualuxe' ) );
                $warning_count++;
            } else {
                $results .= $this->format_result( 'pass', esc_html__( 'CSS appears to be RTL-friendly.', 'aqualuxe' ) );
                $pass_count++;
            }
        } else {
            $results .= $this->format_result( 'warning', esc_html__( 'Could not check CSS file.', 'aqualuxe' ) );
            $warning_count++;
        }
        
        // Check if dir attribute is used
        $template_files = glob( get_template_directory() . '/*.php' );
        $dir_attr_used = false;
        
        foreach ( $template_files as $file ) {
            $content = file_get_contents( $file );
            if ( preg_match( '/dir=(?:["\']|)(?:ltr|rtl|auto)(?:["\']|)/', $content ) ) {
                $dir_attr_used = true;
                break;
            }
        }
        
        if ( $dir_attr_used ) {
            $results .= $this->format_result( 'pass', esc_html__( 'dir attribute is used.', 'aqualuxe' ) );
            $pass_count++;
        } else {
            $results .= $this->format_result( 'warning', esc_html__( 'dir attribute might not be used.', 'aqualuxe' ) );
            $warning_count++;
        }
        
        // Summary
        $results .= '<hr>';
        $results .= '<p><strong>' . esc_html__( 'Summary:', 'aqualuxe' ) . '</strong> ';
        $results .= sprintf(
            esc_html__( '%d passed, %d warnings, %d failed', 'aqualuxe' ),
            $pass_count,
            $warning_count,
            $fail_count
        );
        $results .= '</p>';
        
        return $results;
    }

    /**
     * Test multilingual plugins
     */
    private function test_multilingual_plugins() {
        $results = '';
        $pass_count = 0;
        $fail_count = 0;
        $warning_count = 0;
        
        // Check if WPML compatibility is implemented
        $multilingual_file = get_template_directory() . '/inc/multilingual.php';
        if ( file_exists( $multilingual_file ) ) {
            $multilingual_content = file_get_contents( $multilingual_file );
            if ( preg_match( '/wpml|sitepress/', $multilingual_content ) ) {
                $results .= $this->format_result( 'pass', esc_html__( 'WPML compatibility is implemented.', 'aqualuxe' ) );
                $pass_count++;
            } else {
                $results .= $this->format_result( 'warning', esc_html__( 'WPML compatibility might not be implemented.', 'aqualuxe' ) );
                $warning_count++;
            }
        } else {
            $results .= $this->format_result( 'warning', esc_html__( 'Could not check multilingual.php file.', 'aqualuxe' ) );
            $warning_count++;
        }
        
        // Check if Polylang compatibility is implemented
        if ( file_exists( $multilingual_file ) ) {
            $multilingual_content = file_get_contents( $multilingual_file );
            if ( preg_match( '/polylang|pll_/', $multilingual_content ) ) {
                $results .= $this->format_result( 'pass', esc_html__( 'Polylang compatibility is implemented.', 'aqualuxe' ) );
                $pass_count++;
            } else {
                $results .= $this->format_result( 'warning', esc_html__( 'Polylang compatibility might not be implemented.', 'aqualuxe' ) );
                $warning_count++;
            }
        }
        
        // Check if language switcher is implemented
        $template_files = glob( get_template_directory() . '/*.php' );
        $language_switcher_implemented = false;
        
        foreach ( $template_files as $file ) {
            $content = file_get_contents( $file );
            if ( preg_match( '/language[-_]switcher|wpml|pll_the_languages/', $content ) ) {
                $language_switcher_implemented = true;
                break;
            }
        }
        
        if ( $language_switcher_implemented ) {
            $results .= $this->format_result( 'pass', esc_html__( 'Language switcher is implemented.', 'aqualuxe' ) );
            $pass_count++;
        } else {
            $results .= $this->format_result( 'warning', esc_html__( 'Language switcher might not be implemented.', 'aqualuxe' ) );
            $warning_count++;
        }
        
        // Check if multilingual is handled in customizer
        $customizer_file = get_template_directory() . '/inc/customizer/customizer.php';
        if ( file_exists( $customizer_file ) ) {
            $customizer_content = file_get_contents( $customizer_file );
            if ( preg_match( '/wpml|sitepress|polylang|pll_/', $customizer_content ) ) {
                $results .= $this->format_result( 'pass', esc_html__( 'Multilingual is handled in customizer.', 'aqualuxe' ) );
                $pass_count++;
            } else {
                $results .= $this->format_result( 'warning', esc_html__( 'Multilingual might not be handled in customizer.', 'aqualuxe' ) );
                $warning_count++;
            }
        } else {
            $results .= $this->format_result( 'warning', esc_html__( 'Could not check customizer.php file.', 'aqualuxe' ) );
            $warning_count++;
        }
        
        // Summary
        $results .= '<hr>';
        $results .= '<p><strong>' . esc_html__( 'Summary:', 'aqualuxe' ) . '</strong> ';
        $results .= sprintf(
            esc_html__( '%d passed, %d warnings, %d failed', 'aqualuxe' ),
            $pass_count,
            $warning_count,
            $fail_count
        );
        $results .= '</p>';
        
        return $results;
    }

    /**
     * Test dark mode toggle
     */
    private function test_dark_mode_toggle() {
        $results = '';
        $pass_count = 0;
        $fail_count = 0;
        $warning_count = 0;
        
        // Check if dark mode file exists
        $dark_mode_file = get_template_directory() . '/inc/dark-mode.php';
        if ( file_exists( $dark_mode_file ) ) {
            $results .= $this->format_result( 'pass', esc_html__( 'Dark mode file exists.', 'aqualuxe' ) );
            $pass_count++;
            
            // Check if dark mode toggle is implemented
            $dark_mode_content = file_get_contents( $dark_mode_file );
            if ( preg_match( '/toggle|switch/', $dark_mode_content ) ) {
                $results .= $this->format_result( 'pass', esc_html__( 'Dark mode toggle is implemented.', 'aqualuxe' ) );
                $pass_count++;
            } else {
                $results .= $this->format_result( 'warning', esc_html__( 'Dark mode toggle might not be implemented.', 'aqualuxe' ) );
                $warning_count++;
            }
        } else {
            $results .= $this->format_result( 'fail', esc_html__( 'Dark mode file does not exist.', 'aqualuxe' ) );
            $fail_count++;
        }
        
        // Check if dark mode is saved in localStorage or cookies
        $js_file = get_template_directory() . '/assets/js/main.js';
        if ( file_exists( $js_file ) ) {
            $js_content = file_get_contents( $js_file );
            if ( preg_match( '/localStorage|sessionStorage|cookie/', $js_content ) ) {
                $results .= $this->format_result( 'pass', esc_html__( 'Dark mode preference is saved in storage.', 'aqualuxe' ) );
                $pass_count++;
            } else {
                $results .= $this->format_result( 'warning', esc_html__( 'Dark mode preference might not be saved in storage.', 'aqualuxe' ) );
                $warning_count++;
            }
        } else {
            $results .= $this->format_result( 'warning', esc_html__( 'Could not check JavaScript file.', 'aqualuxe' ) );
            $warning_count++;
        }
        
        // Check if dark mode respects prefers-color-scheme
        if ( file_exists( $js_file ) ) {
            $js_content = file_get_contents( $js_file );
            if ( preg_match( '/prefers-color-scheme/', $js_content ) ) {
                $results .= $this->format_result( 'pass', esc_html__( 'Dark mode respects prefers-color-scheme.', 'aqualuxe' ) );
                $pass_count++;
            } else {
                $results .= $this->format_result( 'warning', esc_html__( 'Dark mode might not respect prefers-color-scheme.', 'aqualuxe' ) );
                $warning_count++;
            }
        }
        
        // Check if dark mode toggle is visible in header or footer
        $header_file = get_template_directory() . '/header.php';
        $footer_file = get_template_directory() . '/footer.php';
        $toggle_visible = false;
        
        if ( file_exists( $header_file ) ) {
            $header_content = file_get_contents( $header_file );
            if ( preg_match( '/dark[-_]mode[-_]toggle|theme[-_]toggle|dark[-_]mode[-_]switch|theme[-_]switch/', $header_content ) ) {
                $toggle_visible = true;
            }
        }
        
        if ( ! $toggle_visible && file_exists( $footer_file ) ) {
            $footer_content = file_get_contents( $footer_file );
            if ( preg_match( '/dark[-_]mode[-_]toggle|theme[-_]toggle|dark[-_]mode[-_]switch|theme[-_]switch/', $footer_content ) ) {
                $toggle_visible = true;
            }
        }
        
        if ( $toggle_visible ) {
            $results .= $this->format_result( 'pass', esc_html__( 'Dark mode toggle is visible in header or footer.', 'aqualuxe' ) );
            $pass_count++;
        } else {
            $results .= $this->format_result( 'warning', esc_html__( 'Dark mode toggle might not be visible in header or footer.', 'aqualuxe' ) );
            $warning_count++;
        }
        
        // Summary
        $results .= '<hr>';
        $results .= '<p><strong>' . esc_html__( 'Summary:', 'aqualuxe' ) . '</strong> ';
        $results .= sprintf(
            esc_html__( '%d passed, %d warnings, %d failed', 'aqualuxe' ),
            $pass_count,
            $warning_count,
            $fail_count
        );
        $results .= '</p>';
        
        return $results;
    }

    /**
     * Test dark mode colors
     */
    private function test_dark_mode_colors() {
        $results = '';
        $pass_count = 0;
        $fail_count = 0;
        $warning_count = 0;
        
        // Check if dark mode colors are defined in CSS
        $css_file = get_template_directory() . '/assets/css/main.css';
        if ( file_exists( $css_file ) ) {
            $css_content = file_get_contents( $css_file );
            if ( preg_match( '/\.dark|body\.dark|\[data-theme=["|\']dark["|\']|@media\s*\(\s*prefers-color-scheme\s*:\s*dark\s*\)/', $css_content ) ) {
                $results .= $this->format_result( 'pass', esc_html__( 'Dark mode colors are defined in CSS.', 'aqualuxe' ) );
                $pass_count++;
            } else {
                $results .= $this->format_result( 'warning', esc_html__( 'Dark mode colors might not be defined in CSS.', 'aqualuxe' ) );
                $warning_count++;
            }
        } else {
            $results .= $this->format_result( 'warning', esc_html__( 'Could not check CSS file.', 'aqualuxe' ) );
            $warning_count++;
        }
        
        // Check if dark mode uses CSS variables
        if ( file_exists( $css_file ) ) {
            $css_content = file_get_contents( $css_file );
            if ( preg_match( '/--[a-zA-Z0-9-_]+:\s*#[a-fA-F0-9]{3,6}/', $css_content ) ) {
                $results .= $this->format_result( 'pass', esc_html__( 'Dark mode uses CSS variables.', 'aqualuxe' ) );
                $pass_count++;
            } else {
                $results .= $this->format_result( 'warning', esc_html__( 'Dark mode might not use CSS variables.', 'aqualuxe' ) );
                $warning_count++;
            }
        }
        
        // Check if dark mode has proper contrast
        if ( file_exists( $css_file ) ) {
            $css_content = file_get_contents( $css_file );
            $dark_mode_section = '';
            
            if ( preg_match( '/\.dark\s*{[^}]*}/', $css_content, $matches ) ) {
                $dark_mode_section = $matches[0];
            } elseif ( preg_match( '/@media\s*\(\s*prefers-color-scheme\s*:\s*dark\s*\)\s*{[^}]*}/', $css_content, $matches ) ) {
                $dark_mode_section = $matches[0];
            }
            
            if ( ! empty( $dark_mode_section ) ) {
                if ( preg_match( '/background(-color)?\s*:\s*#[0-9a-fA-F]{3,6}/', $dark_mode_section ) && 
                     preg_match( '/color\s*:\s*#[0-9a-fA-F]{3,6}/', $dark_mode_section ) ) {
                    $results .= $this->format_result( 'pass', esc_html__( 'Dark mode has defined background and text colors.', 'aqualuxe' ) );
                    $pass_count++;
                } else {
                    $results .= $this->format_result( 'warning', esc_html__( 'Dark mode might not have properly defined background and text colors.', 'aqualuxe' ) );
                    $warning_count++;
                }
            } else {
                $results .= $this->format_result( 'warning', esc_html__( 'Could not find dark mode section in CSS.', 'aqualuxe' ) );
                $warning_count++;
            }
        }
        
        // Check if dark mode is customizable
        $customizer_file = get_template_directory() . '/inc/customizer/customizer.php';
        if ( file_exists( $customizer_file ) ) {
            $customizer_content = file_get_contents( $customizer_file );
            if ( preg_match( '/dark[-_]mode|night[-_]mode/', $customizer_content ) ) {
                $results .= $this->format_result( 'pass', esc_html__( 'Dark mode is customizable.', 'aqualuxe' ) );
                $pass_count++;
            } else {
                $results .= $this->format_result( 'warning', esc_html__( 'Dark mode might not be customizable.', 'aqualuxe' ) );
                $warning_count++;
            }
        } else {
            $results .= $this->format_result( 'warning', esc_html__( 'Could not check customizer.php file.', 'aqualuxe' ) );
            $warning_count++;
        }
        
        // Summary
        $results .= '<hr>';
        $results .= '<p><strong>' . esc_html__( 'Summary:', 'aqualuxe' ) . '</strong> ';
        $results .= sprintf(
            esc_html__( '%d passed, %d warnings, %d failed', 'aqualuxe' ),
            $pass_count,
            $warning_count,
            $fail_count
        );
        $results .= '</p>';
        
        return $results;
    }

    /**
     * Test dark mode images
     */
    private function test_dark_mode_images() {
        $results = '';
        $pass_count = 0;
        $fail_count = 0;
        $warning_count = 0;
        
        // Check if dark mode handles images
        $dark_mode_file = get_template_directory() . '/inc/dark-mode.php';
        if ( file_exists( $dark_mode_file ) ) {
            $dark_mode_content = file_get_contents( $dark_mode_file );
            if ( preg_match( '/logo|image/', $dark_mode_content ) ) {
                $results .= $this->format_result( 'pass', esc_html__( 'Dark mode handles images.', 'aqualuxe' ) );
                $pass_count++;
            } else {
                $results .= $this->format_result( 'warning', esc_html__( 'Dark mode might not handle images.', 'aqualuxe' ) );
                $warning_count++;
            }
        } else {
            $results .= $this->format_result( 'warning', esc_html__( 'Could not check dark-mode.php file.', 'aqualuxe' ) );
            $warning_count++;
        }
        
        // Check if CSS handles dark mode images
        $css_file = get_template_directory() . '/assets/css/main.css';
        if ( file_exists( $css_file ) ) {
            $css_content = file_get_contents( $css_file );
            if ( preg_match( '/\.dark\s+img|\.dark\s+\.logo|@media\s*\(\s*prefers-color-scheme\s*:\s*dark\s*\)\s*{[^}]*img/', $css_content ) ) {
                $results .= $this->format_result( 'pass', esc_html__( 'CSS handles dark mode images.', 'aqualuxe' ) );
                $pass_count++;
            } else {
                $results .= $this->format_result( 'warning', esc_html__( 'CSS might not handle dark mode images.', 'aqualuxe' ) );
                $warning_count++;
            }
        } else {
            $results .= $this->format_result( 'warning', esc_html__( 'Could not check CSS file.', 'aqualuxe' ) );
            $warning_count++;
        }
        
        // Check if dark mode logo is implemented
        $header_file = get_template_directory() . '/header.php';
        if ( file_exists( $header_file ) ) {
            $header_content = file_get_contents( $header_file );
            if ( preg_match( '/dark[-_]logo|logo[-_]dark/', $header_content ) ) {
                $results .= $this->format_result( 'pass', esc_html__( 'Dark mode logo is implemented.', 'aqualuxe' ) );
                $pass_count++;
            } else {
                $results .= $this->format_result( 'warning', esc_html__( 'Dark mode logo might not be implemented.', 'aqualuxe' ) );
                $warning_count++;
            }
        } else {
            $results .= $this->format_result( 'warning', esc_html__( 'Could not check header.php file.', 'aqualuxe' ) );
            $warning_count++;
        }
        
        // Check if dark mode handles SVG icons
        $template_files = glob( get_template_directory() . '/*.php' );
        $svg_dark_mode_handled = false;
        
        foreach ( $template_files as $file ) {
            $content = file_get_contents( $file );
            if ( preg_match( '/<svg[^>]*class=["\'][^"\']*dark[^"\']*["\']/', $content ) || 
                 preg_match( '/\.dark\s+svg|\.dark\s+\.svg/', $css_content ) ) {
                $svg_dark_mode_handled = true;
                break;
            }
        }
        
        if ( $svg_dark_mode_handled ) {
            $results .= $this->format_result( 'pass', esc_html__( 'Dark mode handles SVG icons.', 'aqualuxe' ) );
            $pass_count++;
        } else {
            $results .= $this->format_result( 'warning', esc_html__( 'Dark mode might not handle SVG icons.', 'aqualuxe' ) );
            $warning_count++;
        }
        
        // Summary
        $results .= '<hr>';
        $results .= '<p><strong>' . esc_html__( 'Summary:', 'aqualuxe' ) . '</strong> ';
        $results .= sprintf(
            esc_html__( '%d passed, %d warnings, %d failed', 'aqualuxe' ),
            $pass_count,
            $warning_count,
            $fail_count
        );
        $results .= '</p>';
        
        return $results;
    }

    /**
     * Format test result
     *
     * @param string $type The result type (pass, fail, warning, info).
     * @param string $message The result message.
     * @return string
     */
    private function format_result( $type, $message ) {
        $icon = '';
        
        switch ( $type ) {
            case 'pass':
                $icon = '✅';
                break;
                
            case 'fail':
                $icon = '❌';
                break;
                
            case 'warning':
                $icon = '⚠️';
                break;
                
            case 'info':
                $icon = 'ℹ️';
                break;
        }
        
        return '<div class="aqualuxe-test-result-item ' . esc_attr( $type ) . '">' . $icon . ' ' . $message . '</div>';
    }
}

// Initialize the theme test class
new AquaLuxe_Theme_Test();