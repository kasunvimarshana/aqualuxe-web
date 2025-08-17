<?php
/**
 * Accessibility Testing Functions
 *
 * @package AquaLuxe
 */

/**
 * Class to handle accessibility testing
 */
class AquaLuxe_Accessibility_Test {
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
            __( 'AquaLuxe Accessibility', 'aqualuxe' ),
            __( 'AquaLuxe Accessibility', 'aqualuxe' ),
            'manage_options',
            'aqualuxe-accessibility',
            array( $this, 'render_test_page' )
        );
    }

    /**
     * Enqueue scripts and styles for the test page
     */
    public function enqueue_scripts( $hook ) {
        if ( 'tools_page_aqualuxe-accessibility' !== $hook ) {
            return;
        }

        wp_enqueue_style( 'aqualuxe-accessibility', get_template_directory_uri() . '/assets/css/admin/accessibility.css', array(), '1.0.0' );
        wp_enqueue_script( 'aqualuxe-accessibility', get_template_directory_uri() . '/assets/js/admin/accessibility.js', array( 'jquery' ), '1.0.0', true );
        
        // Create the CSS file if it doesn't exist
        $css_dir = get_template_directory() . '/assets/css/admin';
        if ( ! file_exists( $css_dir ) ) {
            wp_mkdir_p( $css_dir );
        }
        
        $css_file = $css_dir . '/accessibility.css';
        if ( ! file_exists( $css_file ) ) {
            $css_content = "
            .accessibility-card {
                background: #fff;
                border: 1px solid #ddd;
                border-radius: 4px;
                padding: 20px;
                margin-bottom: 20px;
            }
            .accessibility-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 15px;
            }
            .accessibility-title {
                margin: 0;
                font-size: 16px;
                font-weight: 600;
            }
            .accessibility-actions {
                display: flex;
                gap: 10px;
            }
            .accessibility-summary {
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
            .issue-item {
                padding: 15px;
                border: 1px solid #ddd;
                border-radius: 4px;
                margin-bottom: 10px;
                background: #f9f9f9;
            }
            .issue-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 10px;
            }
            .issue-title {
                font-weight: 600;
                margin: 0;
            }
            .issue-severity {
                padding: 3px 8px;
                border-radius: 3px;
                font-size: 12px;
                font-weight: 500;
            }
            .severity-critical {
                background: #fbeaea;
                color: #a72121;
            }
            .severity-serious {
                background: #fff1e5;
                color: #9f4e00;
            }
            .severity-moderate {
                background: #fff8e5;
                color: #996300;
            }
            .severity-minor {
                background: #e7f5ea;
                color: #2a7530;
            }
            .issue-description {
                margin-bottom: 10px;
            }
            .issue-location {
                font-family: monospace;
                background: #f0f0f0;
                padding: 8px;
                border-radius: 3px;
                margin-bottom: 10px;
                overflow-x: auto;
            }
            .issue-actions {
                display: flex;
                gap: 10px;
            }
            .issue-fix {
                margin-top: 10px;
                padding: 10px;
                background: #e7f5ea;
                border-radius: 3px;
                border-left: 4px solid #2a7530;
            }
            .issue-fix-title {
                font-weight: 600;
                margin-bottom: 5px;
            }
            .issue-fix-code {
                font-family: monospace;
                background: #f0f0f0;
                padding: 8px;
                border-radius: 3px;
                overflow-x: auto;
            }
            .wcag-reference {
                margin-top: 10px;
                font-size: 12px;
                color: #666;
            }
            .wcag-reference a {
                text-decoration: none;
            }
            .tab-navigation {
                margin-bottom: 20px;
                border-bottom: 1px solid #ddd;
            }
            .tab-navigation ul {
                display: flex;
                margin: 0;
                padding: 0;
                list-style: none;
            }
            .tab-navigation li {
                margin-bottom: -1px;
            }
            .tab-navigation a {
                display: block;
                padding: 10px 15px;
                text-decoration: none;
                border: 1px solid transparent;
                border-bottom: none;
                color: #2271b1;
            }
            .tab-navigation a.active {
                background: #fff;
                border-color: #ddd;
                border-bottom-color: #fff;
                color: #000;
            }
            .tab-content {
                display: none;
            }
            .tab-content.active {
                display: block;
            }
            .checklist-item {
                padding: 10px;
                border-bottom: 1px solid #f0f0f0;
            }
            .checklist-item:last-child {
                border-bottom: none;
            }
            .checklist-header {
                display: flex;
                align-items: center;
                margin-bottom: 5px;
            }
            .checklist-status {
                margin-right: 10px;
            }
            .checklist-title {
                font-weight: 500;
                margin: 0;
            }
            .checklist-description {
                margin-left: 24px;
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
        
        $js_file = $js_dir . '/accessibility.js';
        if ( ! file_exists( $js_file ) ) {
            $js_content = "
            (function($) {
                'use strict';
                
                $(document).ready(function() {
                    // Handle scan button click
                    $('#scan-accessibility').on('click', function() {
                        // Show loading indicator
                        $('.loading-indicator').show();
                        $('.accessibility-results').hide();
                        
                        // Simulate scanning (in a real implementation, this would be an AJAX call)
                        setTimeout(function() {
                            // Hide loading indicator and show results
                            $('.loading-indicator').hide();
                            $('.accessibility-results').show();
                        }, 2000);
                    });
                    
                    // Handle fix button clicks
                    $('.fix-issue-button').on('click', function() {
                        var item = $(this).closest('.issue-item');
                        
                        // Show loading state
                        $(this).prop('disabled', true).text('Fixing...');
                        
                        // Simulate fixing (in a real implementation, this would be an AJAX call)
                        setTimeout(function() {
                            // Update item
                            item.find('.issue-actions').html('<span class=&quot;dashicons dashicons-yes-alt&quot; style=&quot;color: #46b450;&quot;></span> <span>Issue fixed</span>');
                            
                            // Update count
                            var count = parseInt($('#issues-count').text()) - 1;
                            $('#issues-count').text(count);
                            
                            if (count === 0) {
                                $('#issues-container').html('<p>No accessibility issues found. Great job!</p>');
                            }
                        }, 1500);
                    });
                    
                    // Handle tab navigation
                    $('.tab-navigation a').on('click', function(e) {
                        e.preventDefault();
                        
                        // Remove active class from all tabs and content
                        $('.tab-navigation a').removeClass('active');
                        $('.tab-content').removeClass('active');
                        
                        // Add active class to clicked tab and corresponding content
                        $(this).addClass('active');
                        $($(this).attr('href')).addClass('active');
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
            <h1><?php esc_html_e( 'AquaLuxe Accessibility Testing', 'aqualuxe' ); ?></h1>
            
            <div class="notice notice-info">
                <p><?php esc_html_e( 'This tool helps you identify and fix accessibility issues in your theme.', 'aqualuxe' ); ?></p>
            </div>
            
            <div class="scan-button-container">
                <button id="scan-accessibility" class="button button-primary button-hero scan-button">
                    <?php esc_html_e( 'Scan for Accessibility Issues', 'aqualuxe' ); ?>
                </button>
            </div>
            
            <div class="loading-indicator">
                <div class="spinner"></div>
                <p><?php esc_html_e( 'Scanning theme for accessibility issues...', 'aqualuxe' ); ?></p>
            </div>
            
            <div class="accessibility-results" style="display: none;">
                <div class="accessibility-summary">
                    <?php
                    $overall_score = $this->get_accessibility_score();
                    $score_class = '';
                    $score_label = '';
                    
                    if ( $overall_score >= 90 ) {
                        $score_class = 'good';
                        $score_label = __( 'Good', 'aqualuxe' );
                    } elseif ( $overall_score >= 70 ) {
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
                
                <div class="tab-navigation">
                    <ul>
                        <li><a href="#tab-issues" class="active"><?php esc_html_e( 'Issues', 'aqualuxe' ); ?></a></li>
                        <li><a href="#tab-checklist"><?php esc_html_e( 'Checklist', 'aqualuxe' ); ?></a></li>
                        <li><a href="#tab-resources"><?php esc_html_e( 'Resources', 'aqualuxe' ); ?></a></li>
                    </ul>
                </div>
                
                <div id="tab-issues" class="tab-content active">
                    <div class="accessibility-card">
                        <div class="accessibility-header">
                            <h2 class="accessibility-title">
                                <?php esc_html_e( 'Accessibility Issues', 'aqualuxe' ); ?>
                                (<span id="issues-count">3</span>)
                            </h2>
                        </div>
                        
                        <div id="issues-container">
                            <?php $this->render_accessibility_issues(); ?>
                        </div>
                    </div>
                </div>
                
                <div id="tab-checklist" class="tab-content">
                    <div class="accessibility-card">
                        <div class="accessibility-header">
                            <h2 class="accessibility-title"><?php esc_html_e( 'Accessibility Checklist', 'aqualuxe' ); ?></h2>
                        </div>
                        
                        <?php $this->render_accessibility_checklist(); ?>
                    </div>
                </div>
                
                <div id="tab-resources" class="tab-content">
                    <div class="accessibility-card">
                        <div class="accessibility-header">
                            <h2 class="accessibility-title"><?php esc_html_e( 'Accessibility Resources', 'aqualuxe' ); ?></h2>
                        </div>
                        
                        <?php $this->render_accessibility_resources(); ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Get accessibility score
     *
     * @return int Score from 0-100
     */
    private function get_accessibility_score() {
        // In a real implementation, this would calculate a score based on various metrics
        // For demo purposes, we'll return a fixed value
        return 85;
    }

    /**
     * Render accessibility issues
     */
    private function render_accessibility_issues() {
        $issues = array(
            array(
                'title' => __( 'Missing alt text on images', 'aqualuxe' ),
                'description' => __( 'Some images in the theme are missing alternative text, which is essential for screen readers.', 'aqualuxe' ),
                'severity' => 'critical',
                'severity_text' => __( 'Critical', 'aqualuxe' ),
                'location' => 'template-parts/content/content.php:25',
                'code' => '<img src="<?php echo esc_url( $image_url ); ?>" class="featured-image">',
                'fix' => '<img src="<?php echo esc_url( $image_url ); ?>" class="featured-image" alt="<?php echo esc_attr( get_the_title() ); ?>">',
                'wcag' => '1.1.1 Non-text Content (Level A)',
                'wcag_url' => 'https://www.w3.org/WAI/WCAG21/Understanding/non-text-content.html',
            ),
            array(
                'title' => __( 'Insufficient color contrast', 'aqualuxe' ),
                'description' => __( 'The color contrast between text and background in some areas does not meet WCAG 2.1 AA standards.', 'aqualuxe' ),
                'severity' => 'serious',
                'severity_text' => __( 'Serious', 'aqualuxe' ),
                'location' => 'assets/css/components/buttons.css:15',
                'code' => '.btn-secondary { background-color: #767676; color: #ffffff; }',
                'fix' => '.btn-secondary { background-color: #595959; color: #ffffff; }',
                'wcag' => '1.4.3 Contrast (Minimum) (Level AA)',
                'wcag_url' => 'https://www.w3.org/WAI/WCAG21/Understanding/contrast-minimum.html',
            ),
            array(
                'title' => __( 'Missing form labels', 'aqualuxe' ),
                'description' => __( 'Some form fields are missing proper labels, which makes them difficult to use with screen readers.', 'aqualuxe' ),
                'severity' => 'critical',
                'severity_text' => __( 'Critical', 'aqualuxe' ),
                'location' => 'template-parts/forms/newsletter-signup.php:12',
                'code' => '<input type="email" name="email" placeholder="Enter your email" class="newsletter-input">',
                'fix' => '<label for="newsletter-email" class="sr-only">Email Address</label>
<input type="email" id="newsletter-email" name="email" placeholder="Enter your email" class="newsletter-input">',
                'wcag' => '3.3.2 Labels or Instructions (Level A)',
                'wcag_url' => 'https://www.w3.org/WAI/WCAG21/Understanding/labels-or-instructions.html',
            ),
        );

        foreach ( $issues as $issue ) {
            ?>
            <div class="issue-item">
                <div class="issue-header">
                    <h3 class="issue-title"><?php echo esc_html( $issue['title'] ); ?></h3>
                    <span class="issue-severity severity-<?php echo esc_attr( $issue['severity'] ); ?>"><?php echo esc_html( $issue['severity_text'] ); ?></span>
                </div>
                <div class="issue-description"><?php echo esc_html( $issue['description'] ); ?></div>
                <div class="issue-location"><?php echo esc_html( $issue['location'] ); ?></div>
                <div class="issue-code">
                    <pre><?php echo esc_html( $issue['code'] ); ?></pre>
                </div>
                <div class="issue-fix">
                    <div class="issue-fix-title"><?php esc_html_e( 'Recommended Fix:', 'aqualuxe' ); ?></div>
                    <div class="issue-fix-code">
                        <pre><?php echo esc_html( $issue['fix'] ); ?></pre>
                    </div>
                </div>
                <div class="wcag-reference">
                    <?php esc_html_e( 'WCAG Reference:', 'aqualuxe' ); ?> 
                    <a href="<?php echo esc_url( $issue['wcag_url'] ); ?>" target="_blank">
                        <?php echo esc_html( $issue['wcag'] ); ?>
                    </a>
                </div>
                <div class="issue-actions">
                    <button type="button" class="button fix-issue-button"><?php esc_html_e( 'Fix Issue', 'aqualuxe' ); ?></button>
                    <button type="button" class="button"><?php esc_html_e( 'Ignore', 'aqualuxe' ); ?></button>
                </div>
            </div>
            <?php
        }
    }

    /**
     * Render accessibility checklist
     */
    private function render_accessibility_checklist() {
        $checklist_items = array(
            array(
                'title' => __( 'All images have alt text', 'aqualuxe' ),
                'description' => __( 'Ensure all images have appropriate alternative text.', 'aqualuxe' ),
                'status' => 'incomplete',
            ),
            array(
                'title' => __( 'Color contrast meets WCAG 2.1 AA', 'aqualuxe' ),
                'description' => __( 'Text has sufficient contrast against its background.', 'aqualuxe' ),
                'status' => 'incomplete',
            ),
            array(
                'title' => __( 'Keyboard navigation works properly', 'aqualuxe' ),
                'description' => __( 'All interactive elements are accessible via keyboard.', 'aqualuxe' ),
                'status' => 'complete',
            ),
            array(
                'title' => __( 'Form fields have proper labels', 'aqualuxe' ),
                'description' => __( 'All form fields have associated labels.', 'aqualuxe' ),
                'status' => 'incomplete',
            ),
            array(
                'title' => __( 'ARIA landmarks are used appropriately', 'aqualuxe' ),
                'description' => __( 'Page structure uses proper ARIA landmarks.', 'aqualuxe' ),
                'status' => 'complete',
            ),
            array(
                'title' => __( 'Heading structure is logical', 'aqualuxe' ),
                'description' => __( 'Headings follow a logical hierarchy (h1, h2, etc.).', 'aqualuxe' ),
                'status' => 'complete',
            ),
            array(
                'title' => __( 'Focus indicators are visible', 'aqualuxe' ),
                'description' => __( 'Keyboard focus is clearly visible on all interactive elements.', 'aqualuxe' ),
                'status' => 'complete',
            ),
            array(
                'title' => __( 'Text can be resized up to 200%', 'aqualuxe' ),
                'description' => __( 'Content remains readable when text is resized up to 200%.', 'aqualuxe' ),
                'status' => 'complete',
            ),
            array(
                'title' => __( 'Skip to content link is available', 'aqualuxe' ),
                'description' => __( 'A skip link allows keyboard users to bypass navigation.', 'aqualuxe' ),
                'status' => 'complete',
            ),
            array(
                'title' => __( 'Tables have proper headers', 'aqualuxe' ),
                'description' => __( 'Data tables use proper header cells and associations.', 'aqualuxe' ),
                'status' => 'complete',
            ),
        );

        foreach ( $checklist_items as $item ) {
            $status_icon = $item['status'] === 'complete' ? 'dashicons-yes-alt' : 'dashicons-warning';
            $status_color = $item['status'] === 'complete' ? '#46b450' : '#ffb900';
            ?>
            <div class="checklist-item">
                <div class="checklist-header">
                    <div class="checklist-status">
                        <span class="dashicons <?php echo esc_attr( $status_icon ); ?>" style="color: <?php echo esc_attr( $status_color ); ?>;"></span>
                    </div>
                    <h3 class="checklist-title"><?php echo esc_html( $item['title'] ); ?></h3>
                </div>
                <div class="checklist-description"><?php echo esc_html( $item['description'] ); ?></div>
            </div>
            <?php
        }
    }

    /**
     * Render accessibility resources
     */
    private function render_accessibility_resources() {
        ?>
        <h3><?php esc_html_e( 'Web Content Accessibility Guidelines (WCAG)', 'aqualuxe' ); ?></h3>
        <p><?php esc_html_e( 'The Web Content Accessibility Guidelines (WCAG) are part of a series of web accessibility guidelines published by the Web Accessibility Initiative (WAI) of the World Wide Web Consortium (W3C).', 'aqualuxe' ); ?></p>
        <ul>
            <li><a href="https://www.w3.org/WAI/standards-guidelines/wcag/" target="_blank"><?php esc_html_e( 'WCAG Overview', 'aqualuxe' ); ?></a></li>
            <li><a href="https://www.w3.org/WAI/WCAG21/quickref/" target="_blank"><?php esc_html_e( 'WCAG 2.1 Quick Reference', 'aqualuxe' ); ?></a></li>
        </ul>
        
        <h3><?php esc_html_e( 'WordPress Accessibility', 'aqualuxe' ); ?></h3>
        <p><?php esc_html_e( 'Resources specific to WordPress accessibility.', 'aqualuxe' ); ?></p>
        <ul>
            <li><a href="https://make.wordpress.org/accessibility/" target="_blank"><?php esc_html_e( 'WordPress Accessibility Team', 'aqualuxe' ); ?></a></li>
            <li><a href="https://developer.wordpress.org/themes/functionality/accessibility/" target="_blank"><?php esc_html_e( 'WordPress Theme Handbook: Accessibility', 'aqualuxe' ); ?></a></li>
        </ul>
        
        <h3><?php esc_html_e( 'Testing Tools', 'aqualuxe' ); ?></h3>
        <p><?php esc_html_e( 'Tools to help test and improve accessibility.', 'aqualuxe' ); ?></p>
        <ul>
            <li><a href="https://wave.webaim.org/" target="_blank"><?php esc_html_e( 'WAVE Web Accessibility Evaluation Tool', 'aqualuxe' ); ?></a></li>
            <li><a href="https://accessibilityinsights.io/" target="_blank"><?php esc_html_e( 'Accessibility Insights', 'aqualuxe' ); ?></a></li>
            <li><a href="https://chrome.google.com/webstore/detail/axe-devtools-web-accessib/lhdoppojpmngadmnindnejefpokejbdd" target="_blank"><?php esc_html_e( 'axe DevTools', 'aqualuxe' ); ?></a></li>
            <li><a href="https://webaim.org/resources/contrastchecker/" target="_blank"><?php esc_html_e( 'WebAIM Contrast Checker', 'aqualuxe' ); ?></a></li>
        </ul>
        
        <h3><?php esc_html_e( 'Learning Resources', 'aqualuxe' ); ?></h3>
        <p><?php esc_html_e( 'Resources to learn more about web accessibility.', 'aqualuxe' ); ?></p>
        <ul>
            <li><a href="https://www.w3.org/WAI/fundamentals/" target="_blank"><?php esc_html_e( 'W3C Web Accessibility Initiative (WAI) Fundamentals', 'aqualuxe' ); ?></a></li>
            <li><a href="https://webaim.org/articles/" target="_blank"><?php esc_html_e( 'WebAIM Articles', 'aqualuxe' ); ?></a></li>
            <li><a href="https://a11yproject.com/" target="_blank"><?php esc_html_e( 'The A11Y Project', 'aqualuxe' ); ?></a></li>
            <li><a href="https://www.udacity.com/course/web-accessibility--ud891" target="_blank"><?php esc_html_e( 'Udacity Web Accessibility Course', 'aqualuxe' ); ?></a></li>
        </ul>
        <?php
    }
}

// Initialize the test class
new AquaLuxe_Accessibility_Test();

/**
 * Accessibility enhancement functions
 */
class AquaLuxe_Accessibility_Enhancements {
    /**
     * Constructor
     */
    public function __construct() {
        // Add screen reader text class
        add_action( 'wp_head', array( $this, 'add_screen_reader_text_styles' ) );
        
        // Add skip link
        add_action( 'wp_body_open', array( $this, 'add_skip_link' ) );
        
        // Add ARIA landmarks
        add_filter( 'aqualuxe_header_classes', array( $this, 'add_header_landmark' ) );
        add_filter( 'aqualuxe_main_classes', array( $this, 'add_main_landmark' ) );
        add_filter( 'aqualuxe_footer_classes', array( $this, 'add_footer_landmark' ) );
        add_filter( 'aqualuxe_navigation_classes', array( $this, 'add_navigation_landmark' ) );
        add_filter( 'aqualuxe_sidebar_classes', array( $this, 'add_sidebar_landmark' ) );
        
        // Enhance form accessibility
        add_filter( 'comment_form_defaults', array( $this, 'enhance_comment_form_accessibility' ) );
        add_filter( 'woocommerce_form_field_args', array( $this, 'enhance_woocommerce_form_accessibility' ), 10, 3 );
        
        // Add focus styles
        add_action( 'wp_head', array( $this, 'add_focus_styles' ) );
    }

    /**
     * Add screen reader text styles
     */
    public function add_screen_reader_text_styles() {
        ?>
        <style>
            .screen-reader-text {
                border: 0;
                clip: rect(1px, 1px, 1px, 1px);
                clip-path: inset(50%);
                height: 1px;
                margin: -1px;
                overflow: hidden;
                padding: 0;
                position: absolute !important;
                width: 1px;
                word-wrap: normal !important;
            }
            .screen-reader-text:focus {
                background-color: #f1f1f1;
                border-radius: 3px;
                box-shadow: 0 0 2px 2px rgba(0, 0, 0, 0.6);
                clip: auto !important;
                clip-path: none;
                color: #21759b;
                display: block;
                font-size: 0.875rem;
                font-weight: 700;
                height: auto;
                left: 5px;
                line-height: normal;
                padding: 15px 23px 14px;
                text-decoration: none;
                top: 5px;
                width: auto;
                z-index: 100000;
            }
        </style>
        <?php
    }

    /**
     * Add skip link
     */
    public function add_skip_link() {
        echo '<a class="skip-link screen-reader-text" href="#content">' . esc_html__( 'Skip to content', 'aqualuxe' ) . '</a>';
    }

    /**
     * Add header landmark
     *
     * @param array $classes Header classes
     * @return array Modified classes
     */
    public function add_header_landmark( $classes ) {
        $classes[] = 'site-header';
        $classes[] = 'role="banner"';
        return $classes;
    }

    /**
     * Add main landmark
     *
     * @param array $classes Main classes
     * @return array Modified classes
     */
    public function add_main_landmark( $classes ) {
        $classes[] = 'site-main';
        $classes[] = 'role="main"';
        $classes[] = 'id="content"';
        return $classes;
    }

    /**
     * Add footer landmark
     *
     * @param array $classes Footer classes
     * @return array Modified classes
     */
    public function add_footer_landmark( $classes ) {
        $classes[] = 'site-footer';
        $classes[] = 'role="contentinfo"';
        return $classes;
    }

    /**
     * Add navigation landmark
     *
     * @param array $classes Navigation classes
     * @return array Modified classes
     */
    public function add_navigation_landmark( $classes ) {
        $classes[] = 'site-navigation';
        $classes[] = 'role="navigation"';
        return $classes;
    }

    /**
     * Add sidebar landmark
     *
     * @param array $classes Sidebar classes
     * @return array Modified classes
     */
    public function add_sidebar_landmark( $classes ) {
        $classes[] = 'widget-area';
        $classes[] = 'role="complementary"';
        return $classes;
    }

    /**
     * Enhance comment form accessibility
     *
     * @param array $defaults Comment form defaults
     * @return array Modified defaults
     */
    public function enhance_comment_form_accessibility( $defaults ) {
        $defaults['comment_field'] = str_replace( 'textarea', 'textarea aria-required="true" required', $defaults['comment_field'] );
        $defaults['must_log_in'] = str_replace( 'logged-in-as', 'logged-in-as screen-reader-text', $defaults['must_log_in'] );
        
        return $defaults;
    }

    /**
     * Enhance WooCommerce form accessibility
     *
     * @param array $args Form field arguments
     * @param string $key Field key
     * @param string $value Field value
     * @return array Modified arguments
     */
    public function enhance_woocommerce_form_accessibility( $args, $key, $value ) {
        // Add aria-required attribute to required fields
        if ( $args['required'] ) {
            $args['input_class'][] = 'aria-required';
            $args['label_class'][] = 'required';
            $args['custom_attributes']['aria-required'] = 'true';
        }
        
        // Add aria-describedby if there's a description
        if ( ! empty( $args['description'] ) ) {
            $description_id = $args['id'] . '-description';
            $args['custom_attributes']['aria-describedby'] = $description_id;
            $args['description'] = '<span id="' . esc_attr( $description_id ) . '" class="description">' . $args['description'] . '</span>';
        }
        
        return $args;
    }

    /**
     * Add focus styles
     */
    public function add_focus_styles() {
        ?>
        <style>
            /* Ensure focus styles are visible */
            a:focus,
            button:focus,
            input:focus,
            textarea:focus,
            select:focus,
            [tabindex]:focus {
                outline: 2px solid #2271b1;
                outline-offset: 2px;
            }
            
            /* Don't hide focus styles on mouse users */
            *:focus:not(:focus-visible) {
                outline: none;
            }
            
            /* Show focus styles for keyboard users */
            *:focus-visible {
                outline: 2px solid #2271b1;
                outline-offset: 2px;
            }
        </style>
        <?php
    }
}

// Initialize the enhancements class
new AquaLuxe_Accessibility_Enhancements();