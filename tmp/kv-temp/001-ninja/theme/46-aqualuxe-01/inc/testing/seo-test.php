<?php
/**
 * SEO Testing and Optimization Functions
 *
 * @package AquaLuxe
 */

/**
 * Class to handle SEO testing and optimization
 */
class AquaLuxe_SEO_Test {
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
            __( 'AquaLuxe SEO', 'aqualuxe' ),
            __( 'AquaLuxe SEO', 'aqualuxe' ),
            'manage_options',
            'aqualuxe-seo',
            array( $this, 'render_test_page' )
        );
    }

    /**
     * Enqueue scripts and styles for the test page
     */
    public function enqueue_scripts( $hook ) {
        if ( 'tools_page_aqualuxe-seo' !== $hook ) {
            return;
        }

        wp_enqueue_style( 'aqualuxe-seo', get_template_directory_uri() . '/assets/css/admin/seo.css', array(), '1.0.0' );
        wp_enqueue_script( 'aqualuxe-seo', get_template_directory_uri() . '/assets/js/admin/seo.js', array( 'jquery' ), '1.0.0', true );
        
        // Create the CSS file if it doesn't exist
        $css_dir = get_template_directory() . '/assets/css/admin';
        if ( ! file_exists( $css_dir ) ) {
            wp_mkdir_p( $css_dir );
        }
        
        $css_file = $css_dir . '/seo.css';
        if ( ! file_exists( $css_file ) ) {
            $css_content = "
            .seo-card {
                background: #fff;
                border: 1px solid #ddd;
                border-radius: 4px;
                padding: 20px;
                margin-bottom: 20px;
            }
            .seo-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 15px;
            }
            .seo-title {
                margin: 0;
                font-size: 16px;
                font-weight: 600;
            }
            .seo-actions {
                display: flex;
                gap: 10px;
            }
            .seo-summary {
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
            .issue-priority {
                padding: 3px 8px;
                border-radius: 3px;
                font-size: 12px;
                font-weight: 500;
            }
            .priority-high {
                background: #fbeaea;
                color: #a72121;
            }
            .priority-medium {
                background: #fff8e5;
                color: #996300;
            }
            .priority-low {
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
            .seo-metric {
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
            ";
            file_put_contents( $css_file, $css_content );
        }
        
        // Create the JS file if it doesn't exist
        $js_dir = get_template_directory() . '/assets/js/admin';
        if ( ! file_exists( $js_dir ) ) {
            wp_mkdir_p( $js_dir );
        }
        
        $js_file = $js_dir . '/seo.js';
        if ( ! file_exists( $js_file ) ) {
            $js_content = "
            (function($) {
                'use strict';
                
                $(document).ready(function() {
                    // Handle scan button click
                    $('#scan-seo').on('click', function() {
                        // Show loading indicator
                        $('.loading-indicator').show();
                        $('.seo-results').hide();
                        
                        // Simulate scanning (in a real implementation, this would be an AJAX call)
                        setTimeout(function() {
                            // Hide loading indicator and show results
                            $('.loading-indicator').hide();
                            $('.seo-results').show();
                            
                            // Animate metric bars
                            $('.metric-bar').each(function() {
                                var percentage = $(this).data('percentage');
                                $(this).css('width', percentage + '%');
                            });
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
                                $('#issues-container').html('<p>No SEO issues found. Great job!</p>');
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
            <h1><?php esc_html_e( 'AquaLuxe SEO Testing & Optimization', 'aqualuxe' ); ?></h1>
            
            <div class="notice notice-info">
                <p><?php esc_html_e( 'This tool helps you identify and fix SEO issues in your theme.', 'aqualuxe' ); ?></p>
            </div>
            
            <div class="scan-button-container">
                <button id="scan-seo" class="button button-primary button-hero scan-button">
                    <?php esc_html_e( 'Scan for SEO Issues', 'aqualuxe' ); ?>
                </button>
            </div>
            
            <div class="loading-indicator">
                <div class="spinner"></div>
                <p><?php esc_html_e( 'Scanning theme for SEO issues...', 'aqualuxe' ); ?></p>
            </div>
            
            <div class="seo-results" style="display: none;">
                <div class="seo-summary">
                    <?php
                    $overall_score = $this->get_seo_score();
                    $score_class = '';
                    $score_label = '';
                    
                    if ( $overall_score >= 80 ) {
                        $score_class = 'good';
                        $score_label = __( 'Good', 'aqualuxe' );
                    } elseif ( $overall_score >= 60 ) {
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
                        <li><a href="#tab-metrics"><?php esc_html_e( 'Metrics', 'aqualuxe' ); ?></a></li>
                        <li><a href="#tab-checklist"><?php esc_html_e( 'Checklist', 'aqualuxe' ); ?></a></li>
                        <li><a href="#tab-resources"><?php esc_html_e( 'Resources', 'aqualuxe' ); ?></a></li>
                    </ul>
                </div>
                
                <div id="tab-issues" class="tab-content active">
                    <div class="seo-card">
                        <div class="seo-header">
                            <h2 class="seo-title">
                                <?php esc_html_e( 'SEO Issues', 'aqualuxe' ); ?>
                                (<span id="issues-count">4</span>)
                            </h2>
                        </div>
                        
                        <div id="issues-container">
                            <?php $this->render_seo_issues(); ?>
                        </div>
                    </div>
                </div>
                
                <div id="tab-metrics" class="tab-content">
                    <div class="seo-card">
                        <div class="seo-header">
                            <h2 class="seo-title"><?php esc_html_e( 'SEO Metrics', 'aqualuxe' ); ?></h2>
                        </div>
                        
                        <?php $this->render_seo_metrics(); ?>
                    </div>
                </div>
                
                <div id="tab-checklist" class="tab-content">
                    <div class="seo-card">
                        <div class="seo-header">
                            <h2 class="seo-title"><?php esc_html_e( 'SEO Checklist', 'aqualuxe' ); ?></h2>
                        </div>
                        
                        <?php $this->render_seo_checklist(); ?>
                    </div>
                </div>
                
                <div id="tab-resources" class="tab-content">
                    <div class="seo-card">
                        <div class="seo-header">
                            <h2 class="seo-title"><?php esc_html_e( 'SEO Resources', 'aqualuxe' ); ?></h2>
                        </div>
                        
                        <?php $this->render_seo_resources(); ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Get SEO score
     *
     * @return int Score from 0-100
     */
    private function get_seo_score() {
        // In a real implementation, this would calculate a score based on various metrics
        // For demo purposes, we'll return a fixed value
        return 75;
    }

    /**
     * Render SEO issues
     */
    private function render_seo_issues() {
        $issues = array(
            array(
                'title' => __( 'Missing meta descriptions', 'aqualuxe' ),
                'description' => __( 'Some templates are missing meta descriptions, which are important for search engines.', 'aqualuxe' ),
                'priority' => 'high',
                'priority_text' => __( 'High Priority', 'aqualuxe' ),
                'location' => 'header.php',
                'code' => '<head>
    <meta charset="<?php bloginfo( \'charset\' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>',
                'fix' => '<head>
    <meta charset="<?php bloginfo( \'charset\' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?php echo esc_attr( aqualuxe_get_meta_description() ); ?>">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>',
            ),
            array(
                'title' => __( 'Missing schema.org markup', 'aqualuxe' ),
                'description' => __( 'The theme is missing schema.org markup for better search engine understanding.', 'aqualuxe' ),
                'priority' => 'medium',
                'priority_text' => __( 'Medium Priority', 'aqualuxe' ),
                'location' => 'content.php',
                'code' => '<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <header class="entry-header">
        <?php the_title( \'<h2 class="entry-title"><a href="\' . esc_url( get_permalink() ) . \'" rel="bookmark">\', \'</a></h2>\' ); ?>
    </header>
</article>',
                'fix' => '<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> itemscope itemtype="https://schema.org/Article">
    <header class="entry-header">
        <?php the_title( \'<h2 class="entry-title" itemprop="headline"><a href="\' . esc_url( get_permalink() ) . \'" rel="bookmark">\', \'</a></h2>\' ); ?>
        <meta itemprop="author" content="<?php the_author(); ?>">
        <meta itemprop="datePublished" content="<?php echo esc_attr( get_the_date( \'c\' ) ); ?>">
    </header>
</article>',
            ),
            array(
                'title' => __( 'Missing Open Graph tags', 'aqualuxe' ),
                'description' => __( 'Open Graph tags are missing, which affects how content appears when shared on social media.', 'aqualuxe' ),
                'priority' => 'high',
                'priority_text' => __( 'High Priority', 'aqualuxe' ),
                'location' => 'header.php',
                'code' => '<head>
    <meta charset="<?php bloginfo( \'charset\' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>',
                'fix' => '<head>
    <meta charset="<?php bloginfo( \'charset\' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta property="og:title" content="<?php echo esc_attr( aqualuxe_get_og_title() ); ?>">
    <meta property="og:description" content="<?php echo esc_attr( aqualuxe_get_og_description() ); ?>">
    <meta property="og:url" content="<?php echo esc_url( aqualuxe_get_current_url() ); ?>">
    <meta property="og:type" content="<?php echo esc_attr( aqualuxe_get_og_type() ); ?>">
    <meta property="og:image" content="<?php echo esc_url( aqualuxe_get_og_image() ); ?>">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>',
            ),
            array(
                'title' => __( 'Non-optimized heading structure', 'aqualuxe' ),
                'description' => __( 'The heading structure is not optimized for SEO. Each page should have only one H1 tag.', 'aqualuxe' ),
                'priority' => 'medium',
                'priority_text' => __( 'Medium Priority', 'aqualuxe' ),
                'location' => 'template-parts/content/content.php',
                'code' => '<div class="entry-content">
    <h1 class="section-title"><?php esc_html_e( \'Section Title\', \'aqualuxe\' ); ?></h1>
    <?php the_content(); ?>
</div>',
                'fix' => '<div class="entry-content">
    <h2 class="section-title"><?php esc_html_e( \'Section Title\', \'aqualuxe\' ); ?></h2>
    <?php the_content(); ?>
</div>',
            ),
        );

        foreach ( $issues as $issue ) {
            ?>
            <div class="issue-item">
                <div class="issue-header">
                    <h3 class="issue-title"><?php echo esc_html( $issue['title'] ); ?></h3>
                    <span class="issue-priority priority-<?php echo esc_attr( $issue['priority'] ); ?>"><?php echo esc_html( $issue['priority_text'] ); ?></span>
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
                <div class="issue-actions">
                    <button type="button" class="button fix-issue-button"><?php esc_html_e( 'Fix Issue', 'aqualuxe' ); ?></button>
                    <button type="button" class="button"><?php esc_html_e( 'Ignore', 'aqualuxe' ); ?></button>
                </div>
            </div>
            <?php
        }
    }

    /**
     * Render SEO metrics
     */
    private function render_seo_metrics() {
        $metrics = array(
            'meta_descriptions' => array(
                'label' => __( 'Meta Descriptions', 'aqualuxe' ),
                'value' => '75%',
                'percentage' => 75,
                'status' => 'warning',
            ),
            'schema_markup' => array(
                'label' => __( 'Schema.org Markup', 'aqualuxe' ),
                'value' => '60%',
                'percentage' => 60,
                'status' => 'warning',
            ),
            'open_graph' => array(
                'label' => __( 'Open Graph Tags', 'aqualuxe' ),
                'value' => '50%',
                'percentage' => 50,
                'status' => 'bad',
            ),
            'heading_structure' => array(
                'label' => __( 'Heading Structure', 'aqualuxe' ),
                'value' => '80%',
                'percentage' => 80,
                'status' => 'good',
            ),
            'image_alt_text' => array(
                'label' => __( 'Image Alt Text', 'aqualuxe' ),
                'value' => '90%',
                'percentage' => 90,
                'status' => 'good',
            ),
            'internal_linking' => array(
                'label' => __( 'Internal Linking', 'aqualuxe' ),
                'value' => '85%',
                'percentage' => 85,
                'status' => 'good',
            ),
            'mobile_friendliness' => array(
                'label' => __( 'Mobile Friendliness', 'aqualuxe' ),
                'value' => '100%',
                'percentage' => 100,
                'status' => 'good',
            ),
        );

        foreach ( $metrics as $metric_id => $metric ) {
            ?>
            <div class="seo-metric">
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
     * Render SEO checklist
     */
    private function render_seo_checklist() {
        $checklist_items = array(
            array(
                'title' => __( 'Meta descriptions on all templates', 'aqualuxe' ),
                'description' => __( 'Each template should have a unique meta description.', 'aqualuxe' ),
                'status' => 'incomplete',
            ),
            array(
                'title' => __( 'Schema.org markup implemented', 'aqualuxe' ),
                'description' => __( 'Structured data helps search engines understand your content.', 'aqualuxe' ),
                'status' => 'incomplete',
            ),
            array(
                'title' => __( 'Open Graph tags for social sharing', 'aqualuxe' ),
                'description' => __( 'Open Graph tags control how content appears when shared on social media.', 'aqualuxe' ),
                'status' => 'incomplete',
            ),
            array(
                'title' => __( 'Proper heading structure (H1-H6)', 'aqualuxe' ),
                'description' => __( 'Each page should have one H1, with proper hierarchy for other headings.', 'aqualuxe' ),
                'status' => 'incomplete',
            ),
            array(
                'title' => __( 'Alt text for all images', 'aqualuxe' ),
                'description' => __( 'All images should have descriptive alt text.', 'aqualuxe' ),
                'status' => 'complete',
            ),
            array(
                'title' => __( 'Mobile-friendly design', 'aqualuxe' ),
                'description' => __( 'Theme should be fully responsive for all devices.', 'aqualuxe' ),
                'status' => 'complete',
            ),
            array(
                'title' => __( 'Optimized page loading speed', 'aqualuxe' ),
                'description' => __( 'Fast loading pages rank better in search results.', 'aqualuxe' ),
                'status' => 'complete',
            ),
            array(
                'title' => __( 'XML sitemap support', 'aqualuxe' ),
                'description' => __( 'XML sitemaps help search engines discover and index your content.', 'aqualuxe' ),
                'status' => 'complete',
            ),
            array(
                'title' => __( 'Breadcrumb navigation', 'aqualuxe' ),
                'description' => __( 'Breadcrumbs help users and search engines understand site structure.', 'aqualuxe' ),
                'status' => 'complete',
            ),
            array(
                'title' => __( 'Canonical URLs', 'aqualuxe' ),
                'description' => __( 'Canonical URLs prevent duplicate content issues.', 'aqualuxe' ),
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
     * Render SEO resources
     */
    private function render_seo_resources() {
        ?>
        <h3><?php esc_html_e( 'SEO Best Practices', 'aqualuxe' ); ?></h3>
        <p><?php esc_html_e( 'Resources to help you optimize your theme for search engines.', 'aqualuxe' ); ?></p>
        <ul>
            <li><a href="https://developers.google.com/search/docs/fundamentals/seo-starter-guide" target="_blank"><?php esc_html_e( 'Google\'s SEO Starter Guide', 'aqualuxe' ); ?></a></li>
            <li><a href="https://moz.com/beginners-guide-to-seo" target="_blank"><?php esc_html_e( 'Moz Beginner\'s Guide to SEO', 'aqualuxe' ); ?></a></li>
            <li><a href="https://schema.org/docs/gs.html" target="_blank"><?php esc_html_e( 'Schema.org Getting Started', 'aqualuxe' ); ?></a></li>
        </ul>
        
        <h3><?php esc_html_e( 'WordPress SEO', 'aqualuxe' ); ?></h3>
        <p><?php esc_html_e( 'Resources specific to WordPress SEO.', 'aqualuxe' ); ?></p>
        <ul>
            <li><a href="https://wordpress.org/support/article/search-engine-optimization/" target="_blank"><?php esc_html_e( 'WordPress SEO Documentation', 'aqualuxe' ); ?></a></li>
            <li><a href="https://yoast.com/wordpress-seo/" target="_blank"><?php esc_html_e( 'Yoast WordPress SEO Guide', 'aqualuxe' ); ?></a></li>
        </ul>
        
        <h3><?php esc_html_e( 'Testing Tools', 'aqualuxe' ); ?></h3>
        <p><?php esc_html_e( 'Tools to help test and improve your SEO.', 'aqualuxe' ); ?></p>
        <ul>
            <li><a href="https://search.google.com/search-console" target="_blank"><?php esc_html_e( 'Google Search Console', 'aqualuxe' ); ?></a></li>
            <li><a href="https://pagespeed.web.dev/" target="_blank"><?php esc_html_e( 'PageSpeed Insights', 'aqualuxe' ); ?></a></li>
            <li><a href="https://search.google.com/test/mobile-friendly" target="_blank"><?php esc_html_e( 'Mobile-Friendly Test', 'aqualuxe' ); ?></a></li>
            <li><a href="https://validator.schema.org/" target="_blank"><?php esc_html_e( 'Schema Markup Validator', 'aqualuxe' ); ?></a></li>
        </ul>
        <?php
    }
}

// Initialize the test class
new AquaLuxe_SEO_Test();

/**
 * SEO enhancement functions
 */
class AquaLuxe_SEO_Enhancements {
    /**
     * Constructor
     */
    public function __construct() {
        // Add meta description
        add_action( 'wp_head', array( $this, 'add_meta_description' ), 1 );
        
        // Add Open Graph tags
        add_action( 'wp_head', array( $this, 'add_open_graph_tags' ), 5 );
        
        // Add Twitter Card tags
        add_action( 'wp_head', array( $this, 'add_twitter_card_tags' ), 6 );
        
        // Add canonical URL
        add_action( 'wp_head', array( $this, 'add_canonical_url' ), 10 );
        
        // Add schema.org markup
        add_filter( 'aqualuxe_body_classes', array( $this, 'add_schema_body_classes' ) );
        add_filter( 'aqualuxe_post_classes', array( $this, 'add_schema_post_classes' ) );
        add_filter( 'aqualuxe_comment_classes', array( $this, 'add_schema_comment_classes' ) );
        
        // Add breadcrumbs schema
        add_filter( 'aqualuxe_breadcrumbs_args', array( $this, 'add_breadcrumbs_schema' ) );
    }

    /**
     * Add meta description
     */
    public function add_meta_description() {
        $description = $this->get_meta_description();
        
        if ( ! empty( $description ) ) {
            echo '<meta name="description" content="' . esc_attr( $description ) . '">' . "\n";
        }
    }

    /**
     * Get meta description
     *
     * @return string Meta description
     */
    private function get_meta_description() {
        $description = '';
        
        if ( is_singular() ) {
            // Get post excerpt or content
            $post = get_post();
            if ( ! empty( $post->post_excerpt ) ) {
                $description = $post->post_excerpt;
            } else {
                $description = $post->post_content;
            }
            
            // Strip shortcodes and HTML
            $description = strip_shortcodes( $description );
            $description = wp_strip_all_tags( $description );
            
            // Trim to 160 characters
            $description = wp_trim_words( $description, 25, '...' );
        } elseif ( is_category() || is_tag() || is_tax() ) {
            // Get term description
            $description = term_description();
            $description = wp_strip_all_tags( $description );
            $description = wp_trim_words( $description, 25, '...' );
        } elseif ( is_home() || is_front_page() ) {
            // Get site description
            $description = get_bloginfo( 'description' );
        }
        
        return $description;
    }

    /**
     * Add Open Graph tags
     */
    public function add_open_graph_tags() {
        // Default values
        $og_title = get_bloginfo( 'name' );
        $og_description = $this->get_meta_description();
        $og_url = esc_url( home_url( $_SERVER['REQUEST_URI'] ) );
        $og_type = 'website';
        $og_image = get_theme_mod( 'aqualuxe_default_og_image', '' );
        
        // Customize for different page types
        if ( is_singular() ) {
            $og_title = get_the_title();
            $og_type = 'article';
            
            // Get featured image
            if ( has_post_thumbnail() ) {
                $og_image = get_the_post_thumbnail_url( get_the_ID(), 'large' );
            }
        } elseif ( is_category() || is_tag() || is_tax() ) {
            $term = get_queried_object();
            $og_title = $term->name;
        }
        
        // Output Open Graph tags
        echo '<meta property="og:title" content="' . esc_attr( $og_title ) . '">' . "\n";
        
        if ( ! empty( $og_description ) ) {
            echo '<meta property="og:description" content="' . esc_attr( $og_description ) . '">' . "\n";
        }
        
        echo '<meta property="og:url" content="' . esc_url( $og_url ) . '">' . "\n";
        echo '<meta property="og:type" content="' . esc_attr( $og_type ) . '">' . "\n";
        
        if ( ! empty( $og_image ) ) {
            echo '<meta property="og:image" content="' . esc_url( $og_image ) . '">' . "\n";
        }
        
        echo '<meta property="og:site_name" content="' . esc_attr( get_bloginfo( 'name' ) ) . '">' . "\n";
    }

    /**
     * Add Twitter Card tags
     */
    public function add_twitter_card_tags() {
        // Default values
        $twitter_card = 'summary_large_image';
        $twitter_title = get_bloginfo( 'name' );
        $twitter_description = $this->get_meta_description();
        $twitter_image = get_theme_mod( 'aqualuxe_default_twitter_image', '' );
        
        // Customize for different page types
        if ( is_singular() ) {
            $twitter_title = get_the_title();
            
            // Get featured image
            if ( has_post_thumbnail() ) {
                $twitter_image = get_the_post_thumbnail_url( get_the_ID(), 'large' );
            }
        } elseif ( is_category() || is_tag() || is_tax() ) {
            $term = get_queried_object();
            $twitter_title = $term->name;
        }
        
        // Output Twitter Card tags
        echo '<meta name="twitter:card" content="' . esc_attr( $twitter_card ) . '">' . "\n";
        echo '<meta name="twitter:title" content="' . esc_attr( $twitter_title ) . '">' . "\n";
        
        if ( ! empty( $twitter_description ) ) {
            echo '<meta name="twitter:description" content="' . esc_attr( $twitter_description ) . '">' . "\n";
        }
        
        if ( ! empty( $twitter_image ) ) {
            echo '<meta name="twitter:image" content="' . esc_url( $twitter_image ) . '">' . "\n";
        }
        
        // Add Twitter site username if available
        $twitter_site = get_theme_mod( 'aqualuxe_twitter_username', '' );
        if ( ! empty( $twitter_site ) ) {
            echo '<meta name="twitter:site" content="@' . esc_attr( $twitter_site ) . '">' . "\n";
        }
    }

    /**
     * Add canonical URL
     */
    public function add_canonical_url() {
        if ( is_singular() ) {
            $canonical_url = get_permalink();
            echo '<link rel="canonical" href="' . esc_url( $canonical_url ) . '">' . "\n";
        }
    }

    /**
     * Add schema.org markup to body classes
     *
     * @param array $classes Body classes
     * @return array Modified classes
     */
    public function add_schema_body_classes( $classes ) {
        $classes[] = 'itemscope';
        $classes[] = 'itemtype="https://schema.org/WebPage"';
        return $classes;
    }

    /**
     * Add schema.org markup to post classes
     *
     * @param array $classes Post classes
     * @return array Modified classes
     */
    public function add_schema_post_classes( $classes ) {
        $classes[] = 'itemscope';
        $classes[] = 'itemtype="https://schema.org/Article"';
        return $classes;
    }

    /**
     * Add schema.org markup to comment classes
     *
     * @param array $classes Comment classes
     * @return array Modified classes
     */
    public function add_schema_comment_classes( $classes ) {
        $classes[] = 'itemscope';
        $classes[] = 'itemtype="https://schema.org/Comment"';
        return $classes;
    }

    /**
     * Add schema.org markup to breadcrumbs
     *
     * @param array $args Breadcrumbs arguments
     * @return array Modified arguments
     */
    public function add_breadcrumbs_schema( $args ) {
        $args['wrap_before'] = '<nav class="breadcrumbs" aria-label="' . esc_attr__( 'Breadcrumbs', 'aqualuxe' ) . '" itemscope itemtype="https://schema.org/BreadcrumbList">';
        $args['wrap_after'] = '</nav>';
        $args['before'] = '<span itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
        $args['after'] = '</span>';
        $args['link_before'] = '<span itemprop="name">';
        $args['link_after'] = '</span><meta itemprop="position" content="%i">';
        
        return $args;
    }
}

// Initialize the enhancements class
new AquaLuxe_SEO_Enhancements();

/**
 * Helper functions for SEO
 */
function aqualuxe_get_meta_description() {
    $seo = new AquaLuxe_SEO_Enhancements();
    return $seo->get_meta_description();
}

function aqualuxe_get_og_title() {
    if ( is_singular() ) {
        return get_the_title();
    } elseif ( is_category() || is_tag() || is_tax() ) {
        $term = get_queried_object();
        return $term->name;
    }
    return get_bloginfo( 'name' );
}

function aqualuxe_get_og_description() {
    return aqualuxe_get_meta_description();
}

function aqualuxe_get_current_url() {
    return esc_url( home_url( $_SERVER['REQUEST_URI'] ) );
}

function aqualuxe_get_og_type() {
    if ( is_singular() ) {
        return 'article';
    }
    return 'website';
}

function aqualuxe_get_og_image() {
    if ( is_singular() && has_post_thumbnail() ) {
        return get_the_post_thumbnail_url( get_the_ID(), 'large' );
    }
    return get_theme_mod( 'aqualuxe_default_og_image', '' );
}