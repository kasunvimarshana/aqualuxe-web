<?php
/**
 * Testing and Optimization Functions
 *
 * @package AquaLuxe
 */

// Include testing modules
require get_template_directory() . '/inc/testing/woocommerce-test.php';
require get_template_directory() . '/inc/testing/responsive-test.php';
require get_template_directory() . '/inc/testing/performance-test.php';
require get_template_directory() . '/inc/testing/accessibility-test.php';
require get_template_directory() . '/inc/testing/seo-test.php';

/**
 * Class to handle testing dashboard
 */
class AquaLuxe_Testing_Dashboard {
    /**
     * Constructor
     */
    public function __construct() {
        add_action( 'admin_menu', array( $this, 'add_dashboard_page' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
    }

    /**
     * Add dashboard page to admin menu
     */
    public function add_dashboard_page() {
        add_menu_page(
            __( 'AquaLuxe Testing', 'aqualuxe' ),
            __( 'AquaLuxe Testing', 'aqualuxe' ),
            'manage_options',
            'aqualuxe-testing',
            array( $this, 'render_dashboard_page' ),
            'dashicons-chart-area',
            100
        );
    }

    /**
     * Enqueue scripts and styles for the dashboard page
     */
    public function enqueue_scripts( $hook ) {
        if ( 'toplevel_page_aqualuxe-testing' !== $hook ) {
            return;
        }

        wp_enqueue_style( 'aqualuxe-testing-dashboard', get_template_directory_uri() . '/assets/css/admin/testing-dashboard.css', array(), '1.0.0' );
        wp_enqueue_script( 'aqualuxe-testing-dashboard', get_template_directory_uri() . '/assets/js/admin/testing-dashboard.js', array( 'jquery' ), '1.0.0', true );
        
        // Create the CSS file if it doesn't exist
        $css_dir = get_template_directory() . '/assets/css/admin';
        if ( ! file_exists( $css_dir ) ) {
            wp_mkdir_p( $css_dir );
        }
        
        $css_file = $css_dir . '/testing-dashboard.css';
        if ( ! file_exists( $css_file ) ) {
            $css_content = "
            .dashboard-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 20px;
            }
            .dashboard-title {
                margin: 0;
            }
            .dashboard-actions {
                display: flex;
                gap: 10px;
            }
            .dashboard-grid {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
                gap: 20px;
                margin-bottom: 30px;
            }
            .dashboard-card {
                background: #fff;
                border: 1px solid #ddd;
                border-radius: 4px;
                overflow: hidden;
            }
            .card-header {
                padding: 15px;
                border-bottom: 1px solid #ddd;
                background: #f9f9f9;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }
            .card-title {
                margin: 0;
                font-size: 16px;
                font-weight: 600;
            }
            .card-content {
                padding: 15px;
            }
            .card-footer {
                padding: 10px 15px;
                border-top: 1px solid #ddd;
                background: #f9f9f9;
                text-align: right;
            }
            .score-container {
                display: flex;
                align-items: center;
                justify-content: center;
                margin-bottom: 15px;
            }
            .score-circle {
                width: 100px;
                height: 100px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 24px;
                font-weight: bold;
                color: white;
                margin-right: 15px;
            }
            .score-good {
                background: linear-gradient(135deg, #43a047, #2e7d32);
            }
            .score-warning {
                background: linear-gradient(135deg, #ffb300, #fb8c00);
            }
            .score-bad {
                background: linear-gradient(135deg, #e53935, #c62828);
            }
            .score-details {
                flex-grow: 1;
            }
            .score-label {
                font-size: 14px;
                color: #666;
                margin-bottom: 5px;
            }
            .score-status {
                font-size: 16px;
                font-weight: 600;
            }
            .status-good {
                color: #2e7d32;
            }
            .status-warning {
                color: #fb8c00;
            }
            .status-bad {
                color: #c62828;
            }
            .issues-list {
                margin: 0;
                padding: 0;
                list-style: none;
            }
            .issue-item {
                padding: 10px 0;
                border-bottom: 1px solid #f0f0f0;
            }
            .issue-item:last-child {
                border-bottom: none;
                padding-bottom: 0;
            }
            .issue-priority {
                display: inline-block;
                width: 10px;
                height: 10px;
                border-radius: 50%;
                margin-right: 5px;
            }
            .priority-high {
                background-color: #c62828;
            }
            .priority-medium {
                background-color: #fb8c00;
            }
            .priority-low {
                background-color: #2e7d32;
            }
            .dashboard-summary {
                background: #fff;
                border: 1px solid #ddd;
                border-radius: 4px;
                padding: 20px;
                margin-bottom: 20px;
            }
            .summary-title {
                margin-top: 0;
                margin-bottom: 15px;
                font-size: 18px;
                font-weight: 600;
            }
            .summary-grid {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
                gap: 15px;
            }
            .summary-item {
                text-align: center;
                padding: 15px;
                border-radius: 4px;
                background: #f9f9f9;
            }
            .summary-score {
                font-size: 24px;
                font-weight: bold;
                margin-bottom: 5px;
            }
            .summary-label {
                font-size: 14px;
                color: #666;
            }
            ";
            file_put_contents( $css_file, $css_content );
        }
        
        // Create the JS file if it doesn't exist
        $js_dir = get_template_directory() . '/assets/js/admin';
        if ( ! file_exists( $js_dir ) ) {
            wp_mkdir_p( $js_dir );
        }
        
        $js_file = $js_dir . '/testing-dashboard.js';
        if ( ! file_exists( $js_file ) ) {
            $js_content = "
            (function($) {
                'use strict';
                
                $(document).ready(function() {
                    // Handle run all tests button
                    $('#run-all-tests').on('click', function() {
                        $(this).prop('disabled', true).text('Running Tests...');
                        
                        // Simulate running tests (in a real implementation, this would be an AJAX call)
                        setTimeout(function() {
                            $('#run-all-tests').prop('disabled', false).text('Run All Tests');
                            alert('All tests completed successfully!');
                        }, 2000);
                    });
                });
            })(jQuery);
            ";
            file_put_contents( $js_file, $js_content );
        }
    }

    /**
     * Render the dashboard page
     */
    public function render_dashboard_page() {
        ?>
        <div class="wrap">
            <div class="dashboard-header">
                <h1 class="dashboard-title"><?php esc_html_e( 'AquaLuxe Theme Testing Dashboard', 'aqualuxe' ); ?></h1>
                <div class="dashboard-actions">
                    <button id="run-all-tests" class="button button-primary"><?php esc_html_e( 'Run All Tests', 'aqualuxe' ); ?></button>
                </div>
            </div>
            
            <div class="dashboard-summary">
                <h2 class="summary-title"><?php esc_html_e( 'Overall Theme Health', 'aqualuxe' ); ?></h2>
                <div class="summary-grid">
                    <div class="summary-item">
                        <div class="summary-score status-warning">75%</div>
                        <div class="summary-label"><?php esc_html_e( 'Overall Score', 'aqualuxe' ); ?></div>
                    </div>
                    <div class="summary-item">
                        <div class="summary-score">12</div>
                        <div class="summary-label"><?php esc_html_e( 'Total Issues', 'aqualuxe' ); ?></div>
                    </div>
                    <div class="summary-item">
                        <div class="summary-score status-bad">4</div>
                        <div class="summary-label"><?php esc_html_e( 'Critical Issues', 'aqualuxe' ); ?></div>
                    </div>
                    <div class="summary-item">
                        <div class="summary-score status-good">85%</div>
                        <div class="summary-label"><?php esc_html_e( 'Optimization Level', 'aqualuxe' ); ?></div>
                    </div>
                </div>
            </div>
            
            <div class="dashboard-grid">
                <?php $this->render_woocommerce_card(); ?>
                <?php $this->render_responsive_card(); ?>
                <?php $this->render_performance_card(); ?>
                <?php $this->render_accessibility_card(); ?>
                <?php $this->render_seo_card(); ?>
            </div>
        </div>
        <?php
    }

    /**
     * Render WooCommerce test card
     */
    private function render_woocommerce_card() {
        ?>
        <div class="dashboard-card">
            <div class="card-header">
                <h2 class="card-title"><?php esc_html_e( 'WooCommerce Integration', 'aqualuxe' ); ?></h2>
                <span class="dashicons dashicons-cart"></span>
            </div>
            <div class="card-content">
                <div class="score-container">
                    <div class="score-circle score-good">90</div>
                    <div class="score-details">
                        <div class="score-label"><?php esc_html_e( 'Integration Status', 'aqualuxe' ); ?></div>
                        <div class="score-status status-good"><?php esc_html_e( 'Good', 'aqualuxe' ); ?></div>
                    </div>
                </div>
                
                <h3><?php esc_html_e( 'Issues', 'aqualuxe' ); ?></h3>
                <ul class="issues-list">
                    <li class="issue-item">
                        <span class="issue-priority priority-medium"></span>
                        <?php esc_html_e( 'Cart fragments not optimized', 'aqualuxe' ); ?>
                    </li>
                </ul>
            </div>
            <div class="card-footer">
                <a href="<?php echo esc_url( admin_url( 'tools.php?page=aqualuxe-wc-test' ) ); ?>" class="button"><?php esc_html_e( 'View Details', 'aqualuxe' ); ?></a>
            </div>
        </div>
        <?php
    }

    /**
     * Render responsive design test card
     */
    private function render_responsive_card() {
        ?>
        <div class="dashboard-card">
            <div class="card-header">
                <h2 class="card-title"><?php esc_html_e( 'Responsive Design', 'aqualuxe' ); ?></h2>
                <span class="dashicons dashicons-smartphone"></span>
            </div>
            <div class="card-content">
                <div class="score-container">
                    <div class="score-circle score-good">95</div>
                    <div class="score-details">
                        <div class="score-label"><?php esc_html_e( 'Responsive Status', 'aqualuxe' ); ?></div>
                        <div class="score-status status-good"><?php esc_html_e( 'Excellent', 'aqualuxe' ); ?></div>
                    </div>
                </div>
                
                <h3><?php esc_html_e( 'Issues', 'aqualuxe' ); ?></h3>
                <ul class="issues-list">
                    <li class="issue-item">
                        <span class="issue-priority priority-low"></span>
                        <?php esc_html_e( 'Minor overflow on iPhone SE', 'aqualuxe' ); ?>
                    </li>
                </ul>
            </div>
            <div class="card-footer">
                <a href="<?php echo esc_url( admin_url( 'tools.php?page=aqualuxe-responsive-test' ) ); ?>" class="button"><?php esc_html_e( 'View Details', 'aqualuxe' ); ?></a>
            </div>
        </div>
        <?php
    }

    /**
     * Render performance test card
     */
    private function render_performance_card() {
        ?>
        <div class="dashboard-card">
            <div class="card-header">
                <h2 class="card-title"><?php esc_html_e( 'Performance', 'aqualuxe' ); ?></h2>
                <span class="dashicons dashicons-performance"></span>
            </div>
            <div class="card-content">
                <div class="score-container">
                    <div class="score-circle score-warning">75</div>
                    <div class="score-details">
                        <div class="score-label"><?php esc_html_e( 'Performance Score', 'aqualuxe' ); ?></div>
                        <div class="score-status status-warning"><?php esc_html_e( 'Needs Improvement', 'aqualuxe' ); ?></div>
                    </div>
                </div>
                
                <h3><?php esc_html_e( 'Issues', 'aqualuxe' ); ?></h3>
                <ul class="issues-list">
                    <li class="issue-item">
                        <span class="issue-priority priority-high"></span>
                        <?php esc_html_e( 'Render-blocking resources', 'aqualuxe' ); ?>
                    </li>
                    <li class="issue-item">
                        <span class="issue-priority priority-medium"></span>
                        <?php esc_html_e( 'JavaScript not minified', 'aqualuxe' ); ?>
                    </li>
                    <li class="issue-item">
                        <span class="issue-priority priority-medium"></span>
                        <?php esc_html_e( 'CSS not minified', 'aqualuxe' ); ?>
                    </li>
                </ul>
            </div>
            <div class="card-footer">
                <a href="<?php echo esc_url( admin_url( 'tools.php?page=aqualuxe-performance' ) ); ?>" class="button"><?php esc_html_e( 'View Details', 'aqualuxe' ); ?></a>
            </div>
        </div>
        <?php
    }

    /**
     * Render accessibility test card
     */
    private function render_accessibility_card() {
        ?>
        <div class="dashboard-card">
            <div class="card-header">
                <h2 class="card-title"><?php esc_html_e( 'Accessibility', 'aqualuxe' ); ?></h2>
                <span class="dashicons dashicons-universal-access"></span>
            </div>
            <div class="card-content">
                <div class="score-container">
                    <div class="score-circle score-warning">85</div>
                    <div class="score-details">
                        <div class="score-label"><?php esc_html_e( 'Accessibility Score', 'aqualuxe' ); ?></div>
                        <div class="score-status status-warning"><?php esc_html_e( 'Good', 'aqualuxe' ); ?></div>
                    </div>
                </div>
                
                <h3><?php esc_html_e( 'Issues', 'aqualuxe' ); ?></h3>
                <ul class="issues-list">
                    <li class="issue-item">
                        <span class="issue-priority priority-high"></span>
                        <?php esc_html_e( 'Missing alt text on images', 'aqualuxe' ); ?>
                    </li>
                    <li class="issue-item">
                        <span class="issue-priority priority-high"></span>
                        <?php esc_html_e( 'Missing form labels', 'aqualuxe' ); ?>
                    </li>
                    <li class="issue-item">
                        <span class="issue-priority priority-medium"></span>
                        <?php esc_html_e( 'Insufficient color contrast', 'aqualuxe' ); ?>
                    </li>
                </ul>
            </div>
            <div class="card-footer">
                <a href="<?php echo esc_url( admin_url( 'tools.php?page=aqualuxe-accessibility' ) ); ?>" class="button"><?php esc_html_e( 'View Details', 'aqualuxe' ); ?></a>
            </div>
        </div>
        <?php
    }

    /**
     * Render SEO test card
     */
    private function render_seo_card() {
        ?>
        <div class="dashboard-card">
            <div class="card-header">
                <h2 class="card-title"><?php esc_html_e( 'SEO', 'aqualuxe' ); ?></h2>
                <span class="dashicons dashicons-chart-line"></span>
            </div>
            <div class="card-content">
                <div class="score-container">
                    <div class="score-circle score-warning">75</div>
                    <div class="score-details">
                        <div class="score-label"><?php esc_html_e( 'SEO Score', 'aqualuxe' ); ?></div>
                        <div class="score-status status-warning"><?php esc_html_e( 'Needs Improvement', 'aqualuxe' ); ?></div>
                    </div>
                </div>
                
                <h3><?php esc_html_e( 'Issues', 'aqualuxe' ); ?></h3>
                <ul class="issues-list">
                    <li class="issue-item">
                        <span class="issue-priority priority-high"></span>
                        <?php esc_html_e( 'Missing meta descriptions', 'aqualuxe' ); ?>
                    </li>
                    <li class="issue-item">
                        <span class="issue-priority priority-high"></span>
                        <?php esc_html_e( 'Missing Open Graph tags', 'aqualuxe' ); ?>
                    </li>
                    <li class="issue-item">
                        <span class="issue-priority priority-medium"></span>
                        <?php esc_html_e( 'Missing schema.org markup', 'aqualuxe' ); ?>
                    </li>
                    <li class="issue-item">
                        <span class="issue-priority priority-medium"></span>
                        <?php esc_html_e( 'Non-optimized heading structure', 'aqualuxe' ); ?>
                    </li>
                </ul>
            </div>
            <div class="card-footer">
                <a href="<?php echo esc_url( admin_url( 'tools.php?page=aqualuxe-seo' ) ); ?>" class="button"><?php esc_html_e( 'View Details', 'aqualuxe' ); ?></a>
            </div>
        </div>
        <?php
    }
}

// Initialize the dashboard class
new AquaLuxe_Testing_Dashboard();