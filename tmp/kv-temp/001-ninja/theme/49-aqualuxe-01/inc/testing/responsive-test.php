<?php
/**
 * Responsive Design Testing Functions
 *
 * @package AquaLuxe
 */

/**
 * Class to handle responsive design testing
 */
class AquaLuxe_Responsive_Test {
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
            __( 'AquaLuxe Responsive Test', 'aqualuxe' ),
            __( 'AquaLuxe Responsive', 'aqualuxe' ),
            'manage_options',
            'aqualuxe-responsive-test',
            array( $this, 'render_test_page' )
        );
    }

    /**
     * Enqueue scripts and styles for the test page
     */
    public function enqueue_scripts( $hook ) {
        if ( 'tools_page_aqualuxe-responsive-test' !== $hook ) {
            return;
        }

        wp_enqueue_style( 'aqualuxe-responsive-test', get_template_directory_uri() . '/assets/css/admin/responsive-test.css', array(), '1.0.0' );
        wp_enqueue_script( 'aqualuxe-responsive-test', get_template_directory_uri() . '/assets/js/admin/responsive-test.js', array( 'jquery' ), '1.0.0', true );
        
        // Create the CSS file if it doesn't exist
        $css_dir = get_template_directory() . '/assets/css/admin';
        if ( ! file_exists( $css_dir ) ) {
            wp_mkdir_p( $css_dir );
        }
        
        $css_file = $css_dir . '/responsive-test.css';
        if ( ! file_exists( $css_file ) ) {
            $css_content = "
            .device-container {
                margin-bottom: 20px;
                border: 1px solid #ddd;
                border-radius: 4px;
                overflow: hidden;
            }
            .device-header {
                background: #f5f5f5;
                padding: 10px 15px;
                border-bottom: 1px solid #ddd;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }
            .device-title {
                font-weight: bold;
                margin: 0;
            }
            .device-controls {
                display: flex;
                gap: 10px;
            }
            .device-frame {
                width: 100%;
                border: none;
                transition: height 0.3s ease;
            }
            .device-info {
                display: flex;
                gap: 15px;
                font-size: 12px;
                color: #666;
            }
            .breakpoint-indicator {
                background: #f0f0f0;
                padding: 5px 10px;
                border-radius: 3px;
                margin-top: 10px;
                font-size: 12px;
                display: inline-block;
            }
            .responsive-test-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 20px;
            }
            .url-form {
                display: flex;
                gap: 10px;
                align-items: center;
                margin-bottom: 20px;
            }
            .url-input {
                flex-grow: 1;
            }
            .test-grid {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
                gap: 20px;
            }
            @media (max-width: 782px) {
                .test-grid {
                    grid-template-columns: 1fr;
                }
            }
            ";
            file_put_contents( $css_file, $css_content );
        }
        
        // Create the JS file if it doesn't exist
        $js_dir = get_template_directory() . '/assets/js/admin';
        if ( ! file_exists( $js_dir ) ) {
            wp_mkdir_p( $js_dir );
        }
        
        $js_file = $js_dir . '/responsive-test.js';
        if ( ! file_exists( $js_file ) ) {
            $js_content = "
            (function($) {
                'use strict';
                
                $(document).ready(function() {
                    // Handle URL form submission
                    $('#test-url-form').on('submit', function(e) {
                        e.preventDefault();
                        var url = $('#test-url').val();
                        if (url) {
                            $('.device-frame').attr('src', url);
                        }
                    });
                    
                    // Handle orientation toggle
                    $('.orientation-toggle').on('click', function() {
                        var container = $(this).closest('.device-container');
                        var frame = container.find('.device-frame');
                        var width = frame.width();
                        var height = frame.height();
                        
                        // Swap width and height
                        frame.css({
                            'height': width,
                            'width': height
                        });
                        
                        // Update orientation indicator
                        var orientation = $(this).data('orientation');
                        if (orientation === 'portrait') {
                            $(this).data('orientation', 'landscape');
                            $(this).text('Switch to Portrait');
                        } else {
                            $(this).data('orientation', 'portrait');
                            $(this).text('Switch to Landscape');
                        }
                    });
                    
                    // Handle refresh button
                    $('.refresh-frame').on('click', function() {
                        var frame = $(this).closest('.device-container').find('.device-frame');
                        frame.attr('src', frame.attr('src'));
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
        $home_url = home_url();
        ?>
        <div class="wrap">
            <div class="responsive-test-header">
                <h1><?php esc_html_e( 'AquaLuxe Responsive Design Test', 'aqualuxe' ); ?></h1>
                <div class="breakpoint-indicator">
                    <?php esc_html_e( 'Theme Breakpoints:', 'aqualuxe' ); ?>
                    <strong>SM:</strong> 640px | 
                    <strong>MD:</strong> 768px | 
                    <strong>LG:</strong> 1024px | 
                    <strong>XL:</strong> 1280px | 
                    <strong>2XL:</strong> 1536px
                </div>
            </div>
            
            <div class="notice notice-info">
                <p><?php esc_html_e( 'This tool helps you test how the theme appears on different device sizes. Enter a URL to test or use the default homepage.', 'aqualuxe' ); ?></p>
            </div>
            
            <form id="test-url-form" class="url-form">
                <input type="url" id="test-url" class="regular-text url-input" value="<?php echo esc_url( $home_url ); ?>" placeholder="<?php esc_attr_e( 'Enter URL to test', 'aqualuxe' ); ?>">
                <button type="submit" class="button button-primary"><?php esc_html_e( 'Test URL', 'aqualuxe' ); ?></button>
            </form>
            
            <h2><?php esc_html_e( 'Test on Different Devices', 'aqualuxe' ); ?></h2>
            
            <div class="test-grid">
                <?php $this->render_device_frame( 'Mobile Small', '320px', '568px', 'iPhone SE' ); ?>
                <?php $this->render_device_frame( 'Mobile Medium', '375px', '667px', 'iPhone 8/X/11/12' ); ?>
                <?php $this->render_device_frame( 'Mobile Large', '414px', '896px', 'iPhone 8/11/12 Plus/Pro Max' ); ?>
                <?php $this->render_device_frame( 'Tablet', '768px', '1024px', 'iPad' ); ?>
                <?php $this->render_device_frame( 'Laptop', '1024px', '768px', 'Laptop' ); ?>
                <?php $this->render_device_frame( 'Desktop', '1440px', '900px', 'Desktop' ); ?>
            </div>
            
            <h2><?php esc_html_e( 'Test Specific Breakpoints', 'aqualuxe' ); ?></h2>
            
            <div class="test-grid">
                <?php $this->render_device_frame( 'SM Breakpoint', '640px', '480px', 'Small (sm)' ); ?>
                <?php $this->render_device_frame( 'MD Breakpoint', '768px', '480px', 'Medium (md)' ); ?>
                <?php $this->render_device_frame( 'LG Breakpoint', '1024px', '480px', 'Large (lg)' ); ?>
                <?php $this->render_device_frame( 'XL Breakpoint', '1280px', '480px', 'Extra Large (xl)' ); ?>
                <?php $this->render_device_frame( '2XL Breakpoint', '1536px', '480px', '2X Large (2xl)' ); ?>
            </div>
        </div>
        <?php
    }

    /**
     * Render a device frame
     *
     * @param string $title Device title
     * @param string $width Frame width
     * @param string $height Frame height
     * @param string $device_name Device name
     */
    private function render_device_frame( $title, $width, $height, $device_name ) {
        $home_url = home_url();
        ?>
        <div class="device-container">
            <div class="device-header">
                <h3 class="device-title"><?php echo esc_html( $title ); ?></h3>
                <div class="device-controls">
                    <button type="button" class="button orientation-toggle" data-orientation="portrait"><?php esc_html_e( 'Switch to Landscape', 'aqualuxe' ); ?></button>
                    <button type="button" class="button refresh-frame"><?php esc_html_e( 'Refresh', 'aqualuxe' ); ?></button>
                </div>
            </div>
            <div class="device-info">
                <span><?php echo esc_html( $device_name ); ?></span>
                <span><?php echo esc_html( $width ); ?> × <?php echo esc_html( $height ); ?></span>
            </div>
            <iframe src="<?php echo esc_url( $home_url ); ?>" class="device-frame" style="width: <?php echo esc_attr( $width ); ?>; height: <?php echo esc_attr( $height ); ?>;"></iframe>
        </div>
        <?php
    }
}

// Initialize the test class
new AquaLuxe_Responsive_Test();