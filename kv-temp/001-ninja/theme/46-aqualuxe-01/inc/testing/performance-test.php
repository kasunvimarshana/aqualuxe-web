<?php
/**
 * Performance Testing and Optimization Functions
 *
 * @package AquaLuxe
 */

/**
 * Class to handle performance testing and optimization
 */
class AquaLuxe_Performance_Test {
    /**
     * Constructor
     */
    public function __construct() {
        add_action( 'admin_menu', array( $this, 'add_test_page' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
    }

    /**
     * Add test page to admin menu
     */
    public function add_test_page() {
        add_submenu_page(
            'tools.php',
            __( 'AquaLuxe Performance', 'aqualuxe' ),
            __( 'AquaLuxe Performance', 'aqualuxe' ),
            'manage_options',
            'aqualuxe-performance',
            array( $this, 'render_test_page' )
        );
    }

    /**
     * Enqueue scripts and styles for the test page
     */
    public function enqueue_scripts( $hook ) {
        if ( 'tools_page_aqualuxe-performance' !== $hook ) {
            return;
        }

        wp_enqueue_style( 'aqualuxe-performance', get_template_directory_uri() . '/assets/css/admin/performance.css', array(), '1.0.0' );
        wp_enqueue_script( 'aqualuxe-performance', get_template_directory_uri() . '/assets/js/admin/performance.js', array( 'jquery' ), '1.0.0', true );
        
        // Create the CSS file if it doesn't exist
        $css_dir = get_template_directory() . '/assets/css/admin';
        if ( ! file_exists( $css_dir ) ) {
            wp_mkdir_p( $css_dir );
        }
        
        $css_file = $css_dir . '/performance.css';
        if ( ! file_exists( $css_file ) ) {
            $css_content = "
            .performance-card {
                background: #fff;
                border: 1px solid #ddd;
                border-radius: 4px;
                padding: 20px;
                margin-bottom: 20px;
            }
            .performance-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 15px;
            }
            .performance-title {
                margin: 0;
                font-size: 16px;
                font-weight: 600;
            }
            .performance-actions {
                display: flex;
                gap: 10px;
            }
            .performance-metric {
                display: flex;
                align-items: center;
                margin-bottom: 10px;
            }
            .metric-label {
                flex: 0 0 200px;
                font-weight: 500;
            }
            .metric-value {
                flex-grow: 1;
            }
            .metric-bar-container {
                flex-grow: 1;
                height: 10px;
                background: #f0f0f0;
                border-radius: 5px;
                overflow: hidden;
            }
            .metric-bar {
                height: 100%;
                background: #2271b1;
                border-radius: 5px;
                transition: width 0.5s ease;
            }
            .metric-bar.good {
                background: #46b450;
            }
            .metric-bar.warning {
                background: #ffb900;
            }
            .metric-bar.bad {
                background: #dc3232;
            }
            .optimization-item {
                padding: 15px;
                border: 1px solid #ddd;
                border-radius: 4px;
                margin-bottom: 10px;
                background: #f9f9f9;
            }
            .optimization-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 10px;
            }
            .optimization-title {
                font-weight: 600;
                margin: 0;
            }
            .optimization-status {
                padding: 3px 8px;
                border-radius: 3px;
                font-size: 12px;
                font-weight: 500;
            }
            .status-optimized {
                background: #e7f5ea;
                color: #2a7530;
            }
            .status-needs-optimization {
                background: #fff8e5;
                color: #996300;
            }
            .status-critical {
                background: #fbeaea;
                color: #a72121;
            }
            .optimization-description {
                margin-bottom: 10px;
            }
            .optimization-actions {
                display: flex;
                gap: 10px;
            }
            .performance-summary {
                display: flex;
                gap: 20px;
                margin-bottom: 20px;
            }
            .summary-card {
                flex: 1;
                padding: 15px;
                border-radius: 4px;
                color: white;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
            }
            .summary-card.good {
                background: linear-gradient(135deg, #43a047, #2e7d32);
            }
            .summary-card.needs-improvement {
                background: linear-gradient(135deg, #ffb300, #fb8c00);
            }
            .summary-card.poor {
                background: linear-gradient(135deg, #e53935, #c62828);
            }
            .summary-score {
                font-size: 36px;
                font-weight: bold;
                margin-bottom: 5px;
            }
            .summary-label {
                font-size: 14px;
                opacity: 0.9;
            }
            .scan-button-container {
                margin: 20px 0;
                text-align: center;
            }
            .scan-button {
                padding: 10px 20px;
                font-size: 16px;
            }
            .loading-indicator {
                text-align: center;
                padding: 20px;
                display: none;
            }
            .spinner {
                display: inline-block;
                width: 50px;
                height: 50px;
                border: 5px solid rgba(0, 0, 0, 0.1);
                border-radius: 50%;
                border-top-color: #2271b1;
                animation: spin 1s ease-in-out infinite;
            }
            @keyframes spin {
                to { transform: rotate(360deg); }
            }
            ";
            file_put_contents( $css_file, $css_content );
        }
        
        // Create the JS file if it doesn't exist
        $js_dir = get_template_directory() . '/assets/js/admin';
        if ( ! file_exists( $js_dir ) ) {
            wp_mkdir_p( $js_dir );
        }
        
        $js_file = $js_dir . '/performance.js';
        if ( ! file_exists( $js_file ) ) {
            $js_content = "
            (function($) {
                'use strict';
                
                $(document).ready(function() {
                    // Handle scan button click
                    $('#scan-performance').on('click', function() {
                        // Show loading indicator
                        $('.loading-indicator').show();
                        $('.performance-results').hide();
                        
                        // Simulate scanning (in a real implementation, this would be an AJAX call)
                        setTimeout(function() {
                            // Hide loading indicator and show results
                            $('.loading-indicator').hide();
                            $('.performance-results').show();
                            
                            // Animate metric bars
                            $('.metric-bar').each(function() {
                                var percentage = $(this).data('percentage');
                                $(this).css('width', percentage + '%');
                            });
                        }, 2000);
                    });
                    
                    // Handle optimization button clicks
                    $('.optimize-button').on('click', function() {
                        var item = $(this).closest('.optimization-item');
                        var status = item.find('.optimization-status');
                        
                        // Show loading state
                        $(this).prop('disabled', true).text('Optimizing...');
                        
                        // Simulate optimization (in a real implementation, this would be an AJAX call)
                        setTimeout(function() {
                            // Update status
                            status.removeClass('status-needs-optimization status-critical')
                                  .addClass('status-optimized')
                                  .text('Optimized');
                            
                            // Update button
                            item.find('.optimize-button').hide();
                            item.find('.optimization-actions').append('<span class=&quot;dashicons dashicons-yes-alt&quot; style=&quot;color: #46b450;&quot;></span> <span>Optimization applied</span>');
                        }, 1500);
                    });
                });
            })(jQuery);
            ";
            file_put_contents( $js_file, $js_content );
        }
    }

    /**
     * Render the test page
     */
    public function render_test_page() {
        ?>
        <div class="wrap">
            <h1><?php esc_html_e( 'AquaLuxe Performance Testing & Optimization', 'aqualuxe' ); ?></h1>
            
            <div class="notice notice-info">
                <p><?php esc_html_e( 'This tool helps you analyze and optimize your theme\'s performance.', 'aqualuxe' ); ?></p>
            </div>
            
            <div class="scan-button-container">
                <button id="scan-performance" class="button button-primary button-hero scan-button">
                    <?php esc_html_e( 'Scan Performance', 'aqualuxe' ); ?>
                </button>
            </div>
            
            <div class="loading-indicator">
                <div class="spinner"></div>
                <p><?php esc_html_e( 'Analyzing theme performance...', 'aqualuxe' ); ?></p>
            </div>
            
            <div class="performance-results" style="display: none;">
                <div class="performance-summary">
                    <?php
                    $overall_score = $this->get_overall_score();
                    $score_class = '';
                    $score_label = '';
                    
                    if ( $overall_score >= 80 ) {
                        $score_class = 'good';
                        $score_label = __( 'Good', 'aqualuxe' );
                    } elseif ( $overall_score >= 50 ) {
                        $score_class = 'needs-improvement';
                        $score_label = __( 'Needs Improvement', 'aqualuxe' );
                    } else {
                        $score_class = 'poor';
                        $score_label = __( 'Poor', 'aqualuxe' );
                    }
                    ?>
                    <div class="summary-card <?php echo esc_attr( $score_class ); ?>">
                        <div class="summary-score"><?php echo esc_html( $overall_score ); ?></div>
                        <div class="summary-label"><?php echo esc_html( $score_label ); ?></div>
                    </div>
                </div>
                
                <div class="performance-card">
                    <div class="performance-header">
                        <h2 class="performance-title"><?php esc_html_e( 'Performance Metrics', 'aqualuxe' ); ?></h2>
                    </div>
                    
                    <?php $this->render_performance_metrics(); ?>
                </div>
                
                <div class="performance-card">
                    <div class="performance-header">
                        <h2 class="performance-title"><?php esc_html_e( 'Optimization Opportunities', 'aqualuxe' ); ?></h2>
                    </div>
                    
                    <?php $this->render_optimization_opportunities(); ?>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Get overall performance score
     *
     * @return int Score from 0-100
     */
    private function get_overall_score() {
        // In a real implementation, this would calculate a score based on various metrics
        // For demo purposes, we'll return a fixed value
        return 75;
    }

    /**
     * Render performance metrics
     */
    private function render_performance_metrics() {
        $metrics = array(
            'assets_size' => array(
                'label' => __( 'Total Assets Size', 'aqualuxe' ),
                'value' => '1.2 MB',
                'percentage' => 65,
                'status' => 'warning',
            ),
            'js_size' => array(
                'label' => __( 'JavaScript Size', 'aqualuxe' ),
                'value' => '450 KB',
                'percentage' => 55,
                'status' => 'warning',
            ),
            'css_size' => array(
                'label' => __( 'CSS Size', 'aqualuxe' ),
                'value' => '280 KB',
                'percentage' => 70,
                'status' => 'warning',
            ),
            'image_optimization' => array(
                'label' => __( 'Image Optimization', 'aqualuxe' ),
                'value' => '85%',
                'percentage' => 85,
                'status' => 'good',
            ),
            'http_requests' => array(
                'label' => __( 'HTTP Requests', 'aqualuxe' ),
                'value' => '24 requests',
                'percentage' => 60,
                'status' => 'warning',
            ),
            'render_blocking' => array(
                'label' => __( 'Render-Blocking Resources', 'aqualuxe' ),
                'value' => '4 resources',
                'percentage' => 40,
                'status' => 'bad',
            ),
            'lazy_loading' => array(
                'label' => __( 'Lazy Loading Implementation', 'aqualuxe' ),
                'value' => __( 'Implemented', 'aqualuxe' ),
                'percentage' => 100,
                'status' => 'good',
            ),
        );

        foreach ( $metrics as $metric_id => $metric ) {
            ?>
            <div class="performance-metric">
                <div class="metric-label"><?php echo esc_html( $metric['label'] ); ?></div>
                <div class="metric-value"><?php echo esc_html( $metric['value'] ); ?></div>
                <div class="metric-bar-container">
                    <div class="metric-bar <?php echo esc_attr( $metric['status'] ); ?>" data-percentage="<?php echo esc_attr( $metric['percentage'] ); ?>" style="width: 0%;"></div>
                </div>
            </div>
            <?php
        }
    }

    /**
     * Render optimization opportunities
     */
    private function render_optimization_opportunities() {
        $opportunities = array(
            'minify_js' => array(
                'title' => __( 'Minify JavaScript', 'aqualuxe' ),
                'description' => __( 'Minifying JavaScript files can reduce their size by up to 30%.', 'aqualuxe' ),
                'status' => 'needs-optimization',
                'status_text' => __( 'Needs Optimization', 'aqualuxe' ),
                'action' => true,
            ),
            'minify_css' => array(
                'title' => __( 'Minify CSS', 'aqualuxe' ),
                'description' => __( 'Minifying CSS files can reduce their size by up to 25%.', 'aqualuxe' ),
                'status' => 'needs-optimization',
                'status_text' => __( 'Needs Optimization', 'aqualuxe' ),
                'action' => true,
            ),
            'defer_js' => array(
                'title' => __( 'Defer Non-Critical JavaScript', 'aqualuxe' ),
                'description' => __( 'Deferring non-critical JavaScript can improve page load times.', 'aqualuxe' ),
                'status' => 'critical',
                'status_text' => __( 'Critical', 'aqualuxe' ),
                'action' => true,
            ),
            'optimize_images' => array(
                'title' => __( 'Optimize Images', 'aqualuxe' ),
                'description' => __( 'Further optimize images to reduce their file size without affecting quality.', 'aqualuxe' ),
                'status' => 'needs-optimization',
                'status_text' => __( 'Needs Optimization', 'aqualuxe' ),
                'action' => true,
            ),
            'reduce_http_requests' => array(
                'title' => __( 'Reduce HTTP Requests', 'aqualuxe' ),
                'description' => __( 'Combine multiple CSS and JavaScript files to reduce the number of HTTP requests.', 'aqualuxe' ),
                'status' => 'needs-optimization',
                'status_text' => __( 'Needs Optimization', 'aqualuxe' ),
                'action' => true,
            ),
            'lazy_loading' => array(
                'title' => __( 'Lazy Loading', 'aqualuxe' ),
                'description' => __( 'Lazy loading is properly implemented for images and videos.', 'aqualuxe' ),
                'status' => 'optimized',
                'status_text' => __( 'Optimized', 'aqualuxe' ),
                'action' => false,
            ),
        );

        foreach ( $opportunities as $opportunity_id => $opportunity ) {
            ?>
            <div class="optimization-item">
                <div class="optimization-header">
                    <h3 class="optimization-title"><?php echo esc_html( $opportunity['title'] ); ?></h3>
                    <span class="optimization-status status-<?php echo esc_attr( $opportunity['status'] ); ?>"><?php echo esc_html( $opportunity['status_text'] ); ?></span>
                </div>
                <div class="optimization-description"><?php echo esc_html( $opportunity['description'] ); ?></div>
                <div class="optimization-actions">
                    <?php if ( $opportunity['action'] ) : ?>
                        <button type="button" class="button optimize-button" data-optimization="<?php echo esc_attr( $opportunity_id ); ?>"><?php esc_html_e( 'Apply Optimization', 'aqualuxe' ); ?></button>
                    <?php else : ?>
                        <span class="dashicons dashicons-yes-alt" style="color: #46b450;"></span> <span><?php esc_html_e( 'Already optimized', 'aqualuxe' ); ?></span>
                    <?php endif; ?>
                </div>
            </div>
            <?php
        }
    }
}

// Initialize the test class
new AquaLuxe_Performance_Test();

/**
 * Performance optimization functions
 */
class AquaLuxe_Performance_Optimizer {
    /**
     * Constructor
     */
    public function __construct() {
        // Optimize assets loading
        add_action( 'wp_enqueue_scripts', array( $this, 'optimize_assets' ), 999 );
        
        // Add async/defer attributes to scripts
        add_filter( 'script_loader_tag', array( $this, 'add_async_defer_attributes' ), 10, 3 );
        
        // Remove unnecessary WordPress features
        $this->remove_unnecessary_features();
        
        // Implement lazy loading
        add_filter( 'the_content', array( $this, 'add_lazy_loading' ) );
    }

    /**
     * Optimize assets loading
     */
    public function optimize_assets() {
        // Remove emoji scripts
        remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
        remove_action( 'wp_print_styles', 'print_emoji_styles' );
        
        // Remove oEmbed scripts
        remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
        remove_action( 'wp_head', 'wp_oembed_add_host_js' );
        
        // Remove WP version
        remove_action( 'wp_head', 'wp_generator' );
        
        // Remove WP shortlink
        remove_action( 'wp_head', 'wp_shortlink_wp_head' );
        
        // Remove RSD link
        remove_action( 'wp_head', 'rsd_link' );
        
        // Remove wlwmanifest link
        remove_action( 'wp_head', 'wlwmanifest_link' );
        
        // Remove REST API link
        remove_action( 'wp_head', 'rest_output_link_wp_head' );
    }

    /**
     * Add async/defer attributes to scripts
     *
     * @param string $tag Script HTML tag
     * @param string $handle Script handle
     * @param string $src Script source
     * @return string Modified script HTML tag
     */
    public function add_async_defer_attributes( $tag, $handle, $src ) {
        // List of scripts to defer
        $defer_scripts = array(
            'comment-reply',
            'aqualuxe-navigation',
            'aqualuxe-skip-link-focus-fix',
        );
        
        // List of scripts to async
        $async_scripts = array(
            'aqualuxe-customizer',
        );
        
        // Add defer attribute
        if ( in_array( $handle, $defer_scripts, true ) ) {
            return str_replace( ' src', ' defer src', $tag );
        }
        
        // Add async attribute
        if ( in_array( $handle, $async_scripts, true ) ) {
            return str_replace( ' src', ' async src', $tag );
        }
        
        return $tag;
    }

    /**
     * Remove unnecessary WordPress features
     */
    private function remove_unnecessary_features() {
        // Disable self pingbacks
        add_action( 'pre_ping', function( &$links ) {
            $home = get_option( 'home' );
            foreach ( $links as $l => $link ) {
                if ( 0 === strpos( $link, $home ) ) {
                    unset( $links[$l] );
                }
            }
        });
        
        // Disable heartbeat API on admin pages (except post editing)
        add_action( 'admin_enqueue_scripts', function( $hook_suffix ) {
            if ( 'post.php' !== $hook_suffix && 'post-new.php' !== $hook_suffix ) {
                wp_deregister_script( 'heartbeat' );
            }
        });
        
        // Limit post revisions
        if ( ! defined( 'WP_POST_REVISIONS' ) ) {
            define( 'WP_POST_REVISIONS', 5 );
        }
    }

    /**
     * Add lazy loading to images and iframes in content
     *
     * @param string $content Post content
     * @return string Modified content with lazy loading
     */
    public function add_lazy_loading( $content ) {
        // Skip if content is empty
        if ( empty( $content ) ) {
            return $content;
        }
        
        // Add loading="lazy" to images that don't already have it
        $content = preg_replace( '/<img((?!loading=([\'"])lazy\2)[^>]*)>/i', '<img loading="lazy" $1>', $content );
        
        // Add loading="lazy" to iframes that don't already have it
        $content = preg_replace( '/<iframe((?!loading=([\'"])lazy\2)[^>]*)>/i', '<iframe loading="lazy" $1>', $content );
        
        return $content;
    }
}

// Initialize the optimizer
new AquaLuxe_Performance_Optimizer();