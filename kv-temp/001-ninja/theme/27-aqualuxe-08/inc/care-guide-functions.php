<?php
/**
 * Care Guide Functions
 *
 * Functions for the fish care guide system
 *
 * @package AquaLuxe
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Enqueue care guide scripts and styles
 */
function aqualuxe_enqueue_care_guide_assets() {
    // Only enqueue on care guide pages
    if (is_singular('care_guide') || is_post_type_archive('care_guide') || is_tax(array('fish_species', 'care_category', 'difficulty_level'))) {
        // Enqueue CSS
        wp_enqueue_style(
            'aqualuxe-care-guide-styles',
            get_template_directory_uri() . '/assets/css/care-guide.css',
            array(),
            AQUALUXE_VERSION
        );
        
        // Enqueue JavaScript
        wp_enqueue_script(
            'aqualuxe-care-guide-script',
            get_template_directory_uri() . '/assets/js/care-guide.js',
            array('jquery'),
            AQUALUXE_VERSION,
            true
        );
        
        // Localize script
        wp_localize_script(
            'aqualuxe-care-guide-script',
            'aqualuxe_care_guide',
            array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('aqualuxe_care_guide_nonce'),
            )
        );
    }
}
add_action('wp_enqueue_scripts', 'aqualuxe_enqueue_care_guide_assets');

/**
 * Register additional meta boxes for care guide content tabs
 */
function aqualuxe_add_care_guide_content_meta_boxes() {
    // Care Instructions Tab
    add_meta_box(
        'care_guide_care_instructions',
        __('Care Instructions Tab Content', 'aqualuxe'),
        'aqualuxe_care_guide_tab_content_callback',
        'care_guide',
        'normal',
        'high',
        array('field' => 'care_instructions')
    );
    
    // Feeding Tab
    add_meta_box(
        'care_guide_feeding',
        __('Feeding Tab Content', 'aqualuxe'),
        'aqualuxe_care_guide_tab_content_callback',
        'care_guide',
        'normal',
        'high',
        array('field' => 'feeding')
    );
    
    // Tank Setup Tab
    add_meta_box(
        'care_guide_tank_setup',
        __('Tank Setup Tab Content', 'aqualuxe'),
        'aqualuxe_care_guide_tab_content_callback',
        'care_guide',
        'normal',
        'high',
        array('field' => 'tank_setup')
    );
    
    // Common Issues Tab
    add_meta_box(
        'care_guide_common_issues',
        __('Common Issues Tab Content', 'aqualuxe'),
        'aqualuxe_care_guide_tab_content_callback',
        'care_guide',
        'normal',
        'high',
        array('field' => 'common_issues')
    );
}
add_action('add_meta_boxes', 'aqualuxe_add_care_guide_content_meta_boxes');

/**
 * Render tab content meta box
 */
function aqualuxe_care_guide_tab_content_callback($post, $metabox) {
    // Add nonce for security
    wp_nonce_field('aqualuxe_care_guide_tab_content', 'aqualuxe_care_guide_tab_content_nonce');
    
    // Get field name from args
    $field = $metabox['args']['field'];
    
    // Get saved content
    $content = get_post_meta($post->ID, '_' . $field, true);
    
    // Set up the editor
    $editor_id = 'care_guide_' . $field;
    $settings = array(
        'textarea_name' => $field,
        'textarea_rows' => 10,
        'media_buttons' => true,
        'teeny' => false,
        'quicktags' => true,
    );
    
    // Output the editor
    wp_editor($content, $editor_id, $settings);
    
    // Add description
    echo '<p class="description">';
    
    switch ($field) {
        case 'care_instructions':
            _e('Enter detailed care instructions for this fish species. Include water changes, maintenance routines, and general care tips.', 'aqualuxe');
            break;
        case 'feeding':
            _e('Enter information about feeding requirements, including types of food, feeding frequency, and special dietary needs.', 'aqualuxe');
            break;
        case 'tank_setup':
            _e('Enter details about the ideal tank setup, including substrate, plants, decorations, filtration, and lighting.', 'aqualuxe');
            break;
        case 'common_issues':
            _e('Enter information about common health issues, diseases, and troubleshooting tips for this fish species.', 'aqualuxe');
            break;
    }
    
    echo '</p>';
}

/**
 * Save tab content meta box data
 */
function aqualuxe_save_care_guide_tab_content($post_id) {
    // Check if nonce is set
    if (!isset($_POST['aqualuxe_care_guide_tab_content_nonce'])) {
        return;
    }
    
    // Verify nonce
    if (!wp_verify_nonce($_POST['aqualuxe_care_guide_tab_content_nonce'], 'aqualuxe_care_guide_tab_content')) {
        return;
    }
    
    // If this is an autosave, don't do anything
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    // Check user permissions
    if (isset($_POST['post_type']) && 'care_guide' === $_POST['post_type']) {
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
    }
    
    // Save tab content fields
    $fields = array(
        'care_instructions',
        'feeding',
        'tank_setup',
        'common_issues',
    );
    
    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, '_' . $field, wp_kses_post($_POST[$field]));
        }
    }
}
add_action('save_post', 'aqualuxe_save_care_guide_tab_content');

/**
 * AJAX handler for care guide search
 */
function aqualuxe_search_care_guides() {
    // Check nonce
    if (!isset($_POST['security']) || !wp_verify_nonce($_POST['security'], 'aqualuxe_care_guide_nonce')) {
        wp_send_json_error(array('message' => 'Security check failed'));
        die();
    }
    
    // Get search term
    $search_term = isset($_POST['search_term']) ? sanitize_text_field($_POST['search_term']) : '';
    
    if (empty($search_term) || strlen($search_term) < 3) {
        wp_send_json_error(array('message' => 'Search term too short'));
        die();
    }
    
    // Set up query args
    $args = array(
        'post_type' => 'care_guide',
        'post_status' => 'publish',
        's' => $search_term,
        'posts_per_page' => 5,
    );
    
    // Run the query
    $search_query = new WP_Query($args);
    
    $results = array();
    
    if ($search_query->have_posts()) {
        while ($search_query->have_posts()) {
            $search_query->the_post();
            
            // Get thumbnail
            $thumbnail = '';
            if (has_post_thumbnail()) {
                $thumbnail = get_the_post_thumbnail(get_the_ID(), 'thumbnail');
            }
            
            // Add to results
            $results[] = array(
                'id' => get_the_ID(),
                'title' => get_the_title(),
                'permalink' => get_permalink(),
                'excerpt' => wp_trim_words(get_the_excerpt(), 20),
                'thumbnail' => $thumbnail,
            );
        }
        
        // Restore original post data
        wp_reset_postdata();
        
        // Check if there are more results
        $total_found = $search_query->found_posts;
        $more_results = $total_found > count($results);
        
        // Create search URL for "view all" link
        $search_url = add_query_arg(
            array(
                'post_type' => 'care_guide',
                's' => $search_term,
            ),
            home_url('/')
        );
        
        wp_send_json_success(array(
            'results' => $results,
            'more_results' => $more_results,
            'total_found' => $total_found,
            'search_url' => $search_url,
        ));
    } else {
        wp_send_json_success(array(
            'results' => array(),
            'more_results' => false,
            'total_found' => 0,
        ));
    }
    
    die();
}
add_action('wp_ajax_aqualuxe_search_care_guides', 'aqualuxe_search_care_guides');
add_action('wp_ajax_nopriv_aqualuxe_search_care_guides', 'aqualuxe_search_care_guides');

/**
 * AJAX handler for generating PDF of care guide
 */
function aqualuxe_generate_care_guide_pdf() {
    // Check nonce
    if (!isset($_POST['security']) || !wp_verify_nonce($_POST['security'], 'aqualuxe_care_guide_nonce')) {
        wp_send_json_error(array('message' => 'Security check failed'));
        die();
    }
    
    // Get post ID
    $post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
    
    if (empty($post_id)) {
        wp_send_json_error(array('message' => 'Invalid post ID'));
        die();
    }
    
    // Check if post exists and is a care guide
    $post = get_post($post_id);
    if (!$post || 'care_guide' !== $post->post_type) {
        wp_send_json_error(array('message' => 'Care guide not found'));
        die();
    }
    
    // Get post data
    $post_title = get_the_title($post_id);
    $post_content = apply_filters('the_content', $post->post_content);
    
    // Get care guide meta data
    $tank_size = get_post_meta($post_id, '_tank_size', true);
    $water_temp = get_post_meta($post_id, '_water_temp', true);
    $ph_level = get_post_meta($post_id, '_ph_level', true);
    $lifespan = get_post_meta($post_id, '_lifespan', true);
    $diet = get_post_meta($post_id, '_diet', true);
    $maintenance_level = get_post_meta($post_id, '_maintenance_level', true);
    $compatible_with = get_post_meta($post_id, '_compatible_with', true);
    $not_compatible_with = get_post_meta($post_id, '_not_compatible_with', true);
    
    // Get tab content
    $care_instructions = get_post_meta($post_id, '_care_instructions', true);
    $feeding = get_post_meta($post_id, '_feeding', true);
    $tank_setup = get_post_meta($post_id, '_tank_setup', true);
    $common_issues = get_post_meta($post_id, '_common_issues', true);
    
    // Get featured image
    $featured_image = '';
    if (has_post_thumbnail($post_id)) {
        $featured_image = get_the_post_thumbnail_url($post_id, 'large');
    }
    
    // Get taxonomies
    $species_terms = get_the_terms($post_id, 'fish_species');
    $species_names = array();
    if ($species_terms && !is_wp_error($species_terms)) {
        foreach ($species_terms as $term) {
            $species_names[] = $term->name;
        }
    }
    
    $difficulty_terms = get_the_terms($post_id, 'difficulty_level');
    $difficulty_name = '';
    if ($difficulty_terms && !is_wp_error($difficulty_terms)) {
        $difficulty_name = $difficulty_terms[0]->name;
    }
    
    // Build HTML for PDF
    $html = '
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="utf-8">
        <title>' . esc_html($post_title) . ' - Care Guide</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                line-height: 1.6;
                color: #333;
                max-width: 800px;
                margin: 0 auto;
                padding: 20px;
            }
            h1 {
                color: #0056b3;
                margin-bottom: 10px;
            }
            h2 {
                color: #0056b3;
                margin-top: 30px;
                border-bottom: 1px solid #ddd;
                padding-bottom: 5px;
            }
            h3 {
                color: #444;
            }
            .meta-item {
                margin-bottom: 5px;
            }
            .meta-label {
                font-weight: bold;
            }
            .specs-grid {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 15px;
                margin: 20px 0;
            }
            .spec-item {
                padding: 10px;
                background-color: #f8f9fa;
                border-left: 3px solid #0056b3;
            }
            .compatibility-section {
                margin-bottom: 20px;
                padding: 10px;
                background-color: #f8f9fa;
            }
            .compatible {
                border-left: 3px solid #28a745;
            }
            .not-compatible {
                border-left: 3px solid #dc3545;
            }
            .featured-image {
                max-width: 100%;
                height: auto;
                margin-bottom: 20px;
            }
            .footer {
                margin-top: 40px;
                font-size: 12px;
                text-align: center;
                color: #666;
                border-top: 1px solid #ddd;
                padding-top: 10px;
            }
        </style>
    </head>
    <body>
        <h1>' . esc_html($post_title) . ' - Care Guide</h1>';
    
    // Add species if available
    if (!empty($species_names)) {
        $html .= '<div class="meta-item"><span class="meta-label">Species:</span> ' . esc_html(implode(', ', $species_names)) . '</div>';
    }
    
    // Add difficulty if available
    if (!empty($difficulty_name)) {
        $html .= '<div class="meta-item"><span class="meta-label">Difficulty:</span> ' . esc_html($difficulty_name) . '</div>';
    }
    
    // Add featured image if available
    if (!empty($featured_image)) {
        $html .= '<img src="' . esc_url($featured_image) . '" alt="' . esc_attr($post_title) . '" class="featured-image">';
    }
    
    // Add specifications
    $html .= '<h2>Fish Specifications</h2>';
    $html .= '<div class="specs-grid">';
    
    if (!empty($tank_size)) {
        $html .= '<div class="spec-item"><span class="meta-label">Tank Size:</span> ' . esc_html($tank_size) . ' gallons</div>';
    }
    
    if (!empty($water_temp)) {
        $html .= '<div class="spec-item"><span class="meta-label">Water Temperature:</span> ' . esc_html($water_temp) . ' °F</div>';
    }
    
    if (!empty($ph_level)) {
        $html .= '<div class="spec-item"><span class="meta-label">pH Level:</span> ' . esc_html($ph_level) . '</div>';
    }
    
    if (!empty($lifespan)) {
        $html .= '<div class="spec-item"><span class="meta-label">Average Lifespan:</span> ' . esc_html($lifespan) . ' years</div>';
    }
    
    if (!empty($diet)) {
        $html .= '<div class="spec-item"><span class="meta-label">Diet:</span> ' . esc_html($diet) . '</div>';
    }
    
    if (!empty($maintenance_level)) {
        $html .= '<div class="spec-item"><span class="meta-label">Maintenance:</span> ';
        
        switch ($maintenance_level) {
            case 'low':
                $html .= 'Low';
                break;
            case 'medium':
                $html .= 'Medium';
                break;
            case 'high':
                $html .= 'High';
                break;
            default:
                $html .= esc_html($maintenance_level);
        }
        
        $html .= '</div>';
    }
    
    $html .= '</div>'; // End specs-grid
    
    // Add compatibility
    $html .= '<h2>Compatibility</h2>';
    
    if (!empty($compatible_with)) {
        $html .= '<div class="compatibility-section compatible">';
        $html .= '<h3>Compatible With:</h3>';
        $html .= '<p>' . esc_html($compatible_with) . '</p>';
        $html .= '</div>';
    }
    
    if (!empty($not_compatible_with)) {
        $html .= '<div class="compatibility-section not-compatible">';
        $html .= '<h3>Not Compatible With:</h3>';
        $html .= '<p>' . esc_html($not_compatible_with) . '</p>';
        $html .= '</div>';
    }
    
    // Add overview
    $html .= '<h2>Overview</h2>';
    $html .= $post_content;
    
    // Add care instructions
    if (!empty($care_instructions)) {
        $html .= '<h2>Care Instructions</h2>';
        $html .= wpautop($care_instructions);
    }
    
    // Add feeding
    if (!empty($feeding)) {
        $html .= '<h2>Feeding</h2>';
        $html .= wpautop($feeding);
    }
    
    // Add tank setup
    if (!empty($tank_setup)) {
        $html .= '<h2>Tank Setup</h2>';
        $html .= wpautop($tank_setup);
    }
    
    // Add common issues
    if (!empty($common_issues)) {
        $html .= '<h2>Common Issues</h2>';
        $html .= wpautop($common_issues);
    }
    
    // Add footer
    $html .= '<div class="footer">';
    $html .= '<p>Generated from ' . get_bloginfo('name') . ' | ' . date('F j, Y') . '</p>';
    $html .= '<p>© ' . date('Y') . ' ' . get_bloginfo('name') . '. All rights reserved.</p>';
    $html .= '</div>';
    
    $html .= '</body></html>';
    
    // Generate PDF filename
    $filename = sanitize_title($post_title) . '-care-guide.pdf';
    
    // Generate PDF using wkhtmltopdf
    $upload_dir = wp_upload_dir();
    $pdf_path = $upload_dir['path'] . '/' . $filename;
    $pdf_url = $upload_dir['url'] . '/' . $filename;
    
    // Create temporary HTML file
    $temp_html_path = $upload_dir['path'] . '/temp-' . md5($post_id . time()) . '.html';
    file_put_contents($temp_html_path, $html);
    
    // Generate PDF using wkhtmltopdf
    $command = 'wkhtmltopdf --encoding utf-8 --margin-top 20 --margin-bottom 20 --margin-left 20 --margin-right 20 --footer-center "Page [page] of [topage]" "' . $temp_html_path . '" "' . $pdf_path . '"';
    exec($command);
    
    // Delete temporary HTML file
    unlink($temp_html_path);
    
    // Check if PDF was generated
    if (file_exists($pdf_path)) {
        wp_send_json_success(array(
            'pdf_url' => $pdf_url,
            'filename' => $filename,
        ));
    } else {
        wp_send_json_error(array('message' => 'Failed to generate PDF'));
    }
    
    die();
}
add_action('wp_ajax_aqualuxe_generate_care_guide_pdf', 'aqualuxe_generate_care_guide_pdf');
add_action('wp_ajax_nopriv_aqualuxe_generate_care_guide_pdf', 'aqualuxe_generate_care_guide_pdf');

/**
 * Add care guide shortcode
 */
function aqualuxe_care_guide_shortcode($atts) {
    // Extract attributes
    $atts = shortcode_atts(
        array(
            'species' => '',
            'category' => '',
            'difficulty' => '',
            'limit' => 3,
            'columns' => 3,
            'orderby' => 'date',
            'order' => 'DESC',
        ),
        $atts,
        'care_guide'
    );
    
    // Set up query args
    $args = array(
        'post_type' => 'care_guide',
        'posts_per_page' => intval($atts['limit']),
        'orderby' => $atts['orderby'],
        'order' => $atts['order'],
    );
    
    // Add taxonomy queries if specified
    $tax_query = array();
    
    if (!empty($atts['species'])) {
        $tax_query[] = array(
            'taxonomy' => 'fish_species',
            'field' => 'slug',
            'terms' => explode(',', $atts['species']),
        );
    }
    
    if (!empty($atts['category'])) {
        $tax_query[] = array(
            'taxonomy' => 'care_category',
            'field' => 'slug',
            'terms' => explode(',', $atts['category']),
        );
    }
    
    if (!empty($atts['difficulty'])) {
        $tax_query[] = array(
            'taxonomy' => 'difficulty_level',
            'field' => 'slug',
            'terms' => explode(',', $atts['difficulty']),
        );
    }
    
    if (!empty($tax_query)) {
        $args['tax_query'] = $tax_query;
    }
    
    // Run the query
    $care_guides = new WP_Query($args);
    
    // Start output buffer
    ob_start();
    
    if ($care_guides->have_posts()) {
        // Calculate column width based on columns attribute
        $column_class = 'care-guide-column-' . $atts['columns'];
        
        echo '<div class="care-guides-shortcode">';
        echo '<div class="care-guides-grid ' . esc_attr($column_class) . '">';
        
        while ($care_guides->have_posts()) {
            $care_guides->the_post();
            
            // Get difficulty level
            $difficulty_terms = get_the_terms(get_the_ID(), 'difficulty_level');
            $difficulty_name = '';
            if ($difficulty_terms && !is_wp_error($difficulty_terms)) {
                $difficulty_name = $difficulty_terms[0]->name;
            }
            
            // Get maintenance level
            $maintenance_level = get_post_meta(get_the_ID(), '_maintenance_level', true);
            
            echo '<article class="care-guide-card">';
            echo '<a href="' . esc_url(get_permalink()) . '" class="care-guide-card-link">';
            
            if (has_post_thumbnail()) {
                echo '<div class="care-guide-card-image">';
                the_post_thumbnail('medium');
                echo '</div>';
            }
            
            echo '<div class="care-guide-card-content">';
            echo '<h3 class="care-guide-card-title">' . get_the_title() . '</h3>';
            
            echo '<div class="care-guide-card-meta">';
            
            if (!empty($difficulty_name)) {
                echo '<div class="care-guide-difficulty">';
                echo '<span class="meta-label">' . __('Difficulty:', 'aqualuxe') . '</span> ';
                echo esc_html($difficulty_name);
                echo '</div>';
            }
            
            if (!empty($maintenance_level)) {
                echo '<div class="care-guide-maintenance">';
                echo '<span class="meta-label">' . __('Maintenance:', 'aqualuxe') . '</span> ';
                
                switch ($maintenance_level) {
                    case 'low':
                        _e('Low', 'aqualuxe');
                        break;
                    case 'medium':
                        _e('Medium', 'aqualuxe');
                        break;
                    case 'high':
                        _e('High', 'aqualuxe');
                        break;
                    default:
                        echo esc_html($maintenance_level);
                }
                
                echo '</div>';
            }
            
            echo '</div>'; // .care-guide-card-meta
            
            echo '<div class="care-guide-card-excerpt">';
            echo wp_trim_words(get_the_excerpt(), 15);
            echo '</div>';
            
            echo '<div class="care-guide-card-footer">';
            echo '<span class="read-more-link">' . __('Read Full Guide', 'aqualuxe') . '</span>';
            echo '</div>';
            
            echo '</div>'; // .care-guide-card-content
            echo '</a>';
            echo '</article>';
        }
        
        echo '</div>'; // .care-guides-grid
        
        // Add "View All" link if there are more posts than the limit
        if ($care_guides->found_posts > $atts['limit']) {
            echo '<div class="care-guides-view-all">';
            echo '<a href="' . esc_url(get_post_type_archive_link('care_guide')) . '" class="button view-all-button">' . __('View All Care Guides', 'aqualuxe') . '</a>';
            echo '</div>';
        }
        
        echo '</div>'; // .care-guides-shortcode
    } else {
        echo '<div class="care-guides-shortcode no-guides">';
        echo '<p>' . __('No care guides found.', 'aqualuxe') . '</p>';
        echo '</div>';
    }
    
    // Restore original post data
    wp_reset_postdata();
    
    // Return the output buffer contents
    return ob_get_clean();
}
add_shortcode('care_guide', 'aqualuxe_care_guide_shortcode');

/**
 * Add care guide search shortcode
 */
function aqualuxe_care_guide_search_shortcode($atts) {
    // Extract attributes
    $atts = shortcode_atts(
        array(
            'placeholder' => __('Search care guides...', 'aqualuxe'),
        ),
        $atts,
        'care_guide_search'
    );
    
    // Start output buffer
    ob_start();
    
    echo '<div class="care-guide-search-container">';
    echo '<input type="text" id="care-guide-search-input" class="care-guide-search-input" placeholder="' . esc_attr($atts['placeholder']) . '">';
    echo '<div id="care-guide-search-results" class="care-guide-search-results" style="display: none;"></div>';
    echo '</div>';
    
    // Return the output buffer contents
    return ob_get_clean();
}
add_shortcode('care_guide_search', 'aqualuxe_care_guide_search_shortcode');

/**
 * Add care guide widget
 */
class Aqualuxe_Care_Guide_Widget extends WP_Widget {
    /**
     * Register widget with WordPress
     */
    public function __construct() {
        parent::__construct(
            'aqualuxe_care_guide_widget', // Base ID
            __('AquaLuxe Care Guides', 'aqualuxe'), // Name
            array('description' => __('Display recent or featured care guides', 'aqualuxe')) // Args
        );
    }
    
    /**
     * Front-end display of widget
     */
    public function widget($args, $instance) {
        echo $args['before_widget'];
        
        if (!empty($instance['title'])) {
            echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];
        }
        
        // Set up query args
        $query_args = array(
            'post_type' => 'care_guide',
            'posts_per_page' => !empty($instance['number']) ? intval($instance['number']) : 5,
            'orderby' => !empty($instance['orderby']) ? $instance['orderby'] : 'date',
            'order' => !empty($instance['order']) ? $instance['order'] : 'DESC',
        );
        
        // Show only featured guides if selected
        if (!empty($instance['show_featured']) && $instance['show_featured']) {
            $query_args['meta_key'] = '_featured_guide';
            $query_args['meta_value'] = '1';
        }
        
        // Filter by species if selected
        if (!empty($instance['species'])) {
            $query_args['tax_query'] = array(
                array(
                    'taxonomy' => 'fish_species',
                    'field' => 'term_id',
                    'terms' => intval($instance['species']),
                ),
            );
        }
        
        // Run the query
        $care_guides = new WP_Query($query_args);
        
        if ($care_guides->have_posts()) {
            echo '<ul class="care-guide-widget-list">';
            
            while ($care_guides->have_posts()) {
                $care_guides->the_post();
                
                echo '<li class="care-guide-widget-item">';
                echo '<a href="' . esc_url(get_permalink()) . '" class="care-guide-widget-link">';
                
                if (has_post_thumbnail() && !empty($instance['show_thumbnail']) && $instance['show_thumbnail']) {
                    echo '<div class="care-guide-widget-thumbnail">';
                    the_post_thumbnail('thumbnail');
                    echo '</div>';
                }
                
                echo '<div class="care-guide-widget-content">';
                echo '<h4 class="care-guide-widget-title">' . get_the_title() . '</h4>';
                
                if (!empty($instance['show_date']) && $instance['show_date']) {
                    echo '<span class="care-guide-widget-date">' . get_the_date() . '</span>';
                }
                
                echo '</div>'; // .care-guide-widget-content
                echo '</a>';
                echo '</li>';
            }
            
            echo '</ul>';
            
            // Add "View All" link if selected
            if (!empty($instance['show_view_all']) && $instance['show_view_all']) {
                echo '<a href="' . esc_url(get_post_type_archive_link('care_guide')) . '" class="care-guide-widget-view-all">' . __('View All Care Guides', 'aqualuxe') . '</a>';
            }
        } else {
            echo '<p>' . __('No care guides found.', 'aqualuxe') . '</p>';
        }
        
        // Restore original post data
        wp_reset_postdata();
        
        echo $args['after_widget'];
    }
    
    /**
     * Back-end widget form
     */
    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : __('Fish Care Guides', 'aqualuxe');
        $number = !empty($instance['number']) ? intval($instance['number']) : 5;
        $orderby = !empty($instance['orderby']) ? $instance['orderby'] : 'date';
        $order = !empty($instance['order']) ? $instance['order'] : 'DESC';
        $show_thumbnail = !empty($instance['show_thumbnail']) ? (bool) $instance['show_thumbnail'] : true;
        $show_date = !empty($instance['show_date']) ? (bool) $instance['show_date'] : false;
        $show_featured = !empty($instance['show_featured']) ? (bool) $instance['show_featured'] : false;
        $show_view_all = !empty($instance['show_view_all']) ? (bool) $instance['show_view_all'] : true;
        $species = !empty($instance['species']) ? intval($instance['species']) : 0;
        
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e('Title:', 'aqualuxe'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>
        
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('number')); ?>"><?php esc_html_e('Number of guides to show:', 'aqualuxe'); ?></label>
            <input class="tiny-text" id="<?php echo esc_attr($this->get_field_id('number')); ?>" name="<?php echo esc_attr($this->get_field_name('number')); ?>" type="number" step="1" min="1" value="<?php echo esc_attr($number); ?>" size="3">
        </p>
        
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('orderby')); ?>"><?php esc_html_e('Order by:', 'aqualuxe'); ?></label>
            <select id="<?php echo esc_attr($this->get_field_id('orderby')); ?>" name="<?php echo esc_attr($this->get_field_name('orderby')); ?>" class="widefat">
                <option value="date" <?php selected($orderby, 'date'); ?>><?php esc_html_e('Date', 'aqualuxe'); ?></option>
                <option value="title" <?php selected($orderby, 'title'); ?>><?php esc_html_e('Title', 'aqualuxe'); ?></option>
                <option value="rand" <?php selected($orderby, 'rand'); ?>><?php esc_html_e('Random', 'aqualuxe'); ?></option>
                <option value="comment_count" <?php selected($orderby, 'comment_count'); ?>><?php esc_html_e('Comment count', 'aqualuxe'); ?></option>
            </select>
        </p>
        
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('order')); ?>"><?php esc_html_e('Order:', 'aqualuxe'); ?></label>
            <select id="<?php echo esc_attr($this->get_field_id('order')); ?>" name="<?php echo esc_attr($this->get_field_name('order')); ?>" class="widefat">
                <option value="DESC" <?php selected($order, 'DESC'); ?>><?php esc_html_e('Descending', 'aqualuxe'); ?></option>
                <option value="ASC" <?php selected($order, 'ASC'); ?>><?php esc_html_e('Ascending', 'aqualuxe'); ?></option>
            </select>
        </p>
        
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('species')); ?>"><?php esc_html_e('Filter by species:', 'aqualuxe'); ?></label>
            <select id="<?php echo esc_attr($this->get_field_id('species')); ?>" name="<?php echo esc_attr($this->get_field_name('species')); ?>" class="widefat">
                <option value="0"><?php esc_html_e('All species', 'aqualuxe'); ?></option>
                <?php
                $species_terms = get_terms(array(
                    'taxonomy' => 'fish_species',
                    'hide_empty' => false,
                ));
                
                if (!empty($species_terms) && !is_wp_error($species_terms)) {
                    foreach ($species_terms as $term) {
                        echo '<option value="' . esc_attr($term->term_id) . '" ' . selected($species, $term->term_id, false) . '>' . esc_html($term->name) . '</option>';
                    }
                }
                ?>
            </select>
        </p>
        
        <p>
            <input class="checkbox" type="checkbox" <?php checked($show_thumbnail); ?> id="<?php echo esc_attr($this->get_field_id('show_thumbnail')); ?>" name="<?php echo esc_attr($this->get_field_name('show_thumbnail')); ?>">
            <label for="<?php echo esc_attr($this->get_field_id('show_thumbnail')); ?>"><?php esc_html_e('Display thumbnail', 'aqualuxe'); ?></label>
        </p>
        
        <p>
            <input class="checkbox" type="checkbox" <?php checked($show_date); ?> id="<?php echo esc_attr($this->get_field_id('show_date')); ?>" name="<?php echo esc_attr($this->get_field_name('show_date')); ?>">
            <label for="<?php echo esc_attr($this->get_field_id('show_date')); ?>"><?php esc_html_e('Display post date', 'aqualuxe'); ?></label>
        </p>
        
        <p>
            <input class="checkbox" type="checkbox" <?php checked($show_featured); ?> id="<?php echo esc_attr($this->get_field_id('show_featured')); ?>" name="<?php echo esc_attr($this->get_field_name('show_featured')); ?>">
            <label for="<?php echo esc_attr($this->get_field_id('show_featured')); ?>"><?php esc_html_e('Show only featured guides', 'aqualuxe'); ?></label>
        </p>
        
        <p>
            <input class="checkbox" type="checkbox" <?php checked($show_view_all); ?> id="<?php echo esc_attr($this->get_field_id('show_view_all')); ?>" name="<?php echo esc_attr($this->get_field_name('show_view_all')); ?>">
            <label for="<?php echo esc_attr($this->get_field_id('show_view_all')); ?>"><?php esc_html_e('Display "View All" link', 'aqualuxe'); ?></label>
        </p>
        <?php
    }
    
    /**
     * Sanitize widget form values as they are saved
     */
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? sanitize_text_field($new_instance['title']) : '';
        $instance['number'] = (!empty($new_instance['number'])) ? intval($new_instance['number']) : 5;
        $instance['orderby'] = (!empty($new_instance['orderby'])) ? sanitize_text_field($new_instance['orderby']) : 'date';
        $instance['order'] = (!empty($new_instance['order'])) ? sanitize_text_field($new_instance['order']) : 'DESC';
        $instance['show_thumbnail'] = (!empty($new_instance['show_thumbnail'])) ? 1 : 0;
        $instance['show_date'] = (!empty($new_instance['show_date'])) ? 1 : 0;
        $instance['show_featured'] = (!empty($new_instance['show_featured'])) ? 1 : 0;
        $instance['show_view_all'] = (!empty($new_instance['show_view_all'])) ? 1 : 0;
        $instance['species'] = (!empty($new_instance['species'])) ? intval($new_instance['species']) : 0;
        
        return $instance;
    }
}

/**
 * Register care guide widget
 */
function aqualuxe_register_care_guide_widget() {
    register_widget('Aqualuxe_Care_Guide_Widget');
}
add_action('widgets_init', 'aqualuxe_register_care_guide_widget');

/**
 * Add featured guide checkbox to care guide edit screen
 */
function aqualuxe_add_featured_guide_meta_box() {
    add_meta_box(
        'featured_guide',
        __('Featured Guide', 'aqualuxe'),
        'aqualuxe_featured_guide_meta_box_callback',
        'care_guide',
        'side',
        'high'
    );
}
add_action('add_meta_boxes', 'aqualuxe_add_featured_guide_meta_box');

/**
 * Render featured guide meta box
 */
function aqualuxe_featured_guide_meta_box_callback($post) {
    // Add nonce for security
    wp_nonce_field('aqualuxe_featured_guide', 'aqualuxe_featured_guide_nonce');
    
    // Get current value
    $featured = get_post_meta($post->ID, '_featured_guide', true);
    
    // Output checkbox
    ?>
    <p>
        <input type="checkbox" id="featured_guide" name="featured_guide" value="1" <?php checked($featured, '1'); ?>>
        <label for="featured_guide"><?php _e('Mark as featured guide', 'aqualuxe'); ?></label>
    </p>
    <p class="description"><?php _e('Featured guides can be displayed in widgets and shortcodes.', 'aqualuxe'); ?></p>
    <?php
}

/**
 * Save featured guide meta box data
 */
function aqualuxe_save_featured_guide_meta_box_data($post_id) {
    // Check if nonce is set
    if (!isset($_POST['aqualuxe_featured_guide_nonce'])) {
        return;
    }
    
    // Verify nonce
    if (!wp_verify_nonce($_POST['aqualuxe_featured_guide_nonce'], 'aqualuxe_featured_guide')) {
        return;
    }
    
    // If this is an autosave, don't do anything
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    // Check user permissions
    if (isset($_POST['post_type']) && 'care_guide' === $_POST['post_type']) {
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
    }
    
    // Save featured guide status
    $featured = isset($_POST['featured_guide']) ? '1' : '0';
    update_post_meta($post_id, '_featured_guide', $featured);
}
add_action('save_post', 'aqualuxe_save_featured_guide_meta_box_data');

/**
 * Add care guide columns to admin list
 */
function aqualuxe_add_care_guide_admin_columns($columns) {
    $new_columns = array();
    
    // Insert thumbnail after checkbox
    $new_columns['cb'] = $columns['cb'];
    $new_columns['thumbnail'] = __('Image', 'aqualuxe');
    
    // Add other columns
    $new_columns['title'] = $columns['title'];
    $new_columns['fish_species'] = __('Fish Species', 'aqualuxe');
    $new_columns['difficulty_level'] = __('Difficulty', 'aqualuxe');
    $new_columns['featured'] = __('Featured', 'aqualuxe');
    
    // Add remaining columns
    $new_columns['date'] = $columns['date'];
    
    return $new_columns;
}
add_filter('manage_care_guide_posts_columns', 'aqualuxe_add_care_guide_admin_columns');

/**
 * Display care guide column content
 */
function aqualuxe_care_guide_custom_column($column, $post_id) {
    switch ($column) {
        case 'thumbnail':
            if (has_post_thumbnail($post_id)) {
                echo '<a href="' . esc_url(get_edit_post_link($post_id)) . '">';
                echo get_the_post_thumbnail($post_id, array(50, 50));
                echo '</a>';
            } else {
                echo '—';
            }
            break;
        
        case 'fish_species':
            $terms = get_the_terms($post_id, 'fish_species');
            if (!empty($terms) && !is_wp_error($terms)) {
                $species_links = array();
                foreach ($terms as $term) {
                    $species_links[] = '<a href="' . esc_url(add_query_arg(array('post_type' => 'care_guide', 'fish_species' => $term->slug), 'edit.php')) . '">' . esc_html($term->name) . '</a>';
                }
                echo implode(', ', $species_links);
            } else {
                echo '—';
            }
            break;
        
        case 'difficulty_level':
            $terms = get_the_terms($post_id, 'difficulty_level');
            if (!empty($terms) && !is_wp_error($terms)) {
                $difficulty_links = array();
                foreach ($terms as $term) {
                    $difficulty_links[] = '<a href="' . esc_url(add_query_arg(array('post_type' => 'care_guide', 'difficulty_level' => $term->slug), 'edit.php')) . '">' . esc_html($term->name) . '</a>';
                }
                echo implode(', ', $difficulty_links);
            } else {
                echo '—';
            }
            break;
        
        case 'featured':
            $featured = get_post_meta($post_id, '_featured_guide', true);
            if ($featured === '1') {
                echo '<span class="dashicons dashicons-star-filled" style="color: #ffb900;"></span>';
            } else {
                echo '<span class="dashicons dashicons-star-empty"></span>';
            }
            break;
    }
}
add_action('manage_care_guide_posts_custom_column', 'aqualuxe_care_guide_custom_column', 10, 2);

/**
 * Make care guide columns sortable
 */
function aqualuxe_care_guide_sortable_columns($columns) {
    $columns['featured'] = 'featured';
    $columns['difficulty_level'] = 'difficulty_level';
    return $columns;
}
add_filter('manage_edit-care_guide_sortable_columns', 'aqualuxe_care_guide_sortable_columns');

/**
 * Handle custom column sorting
 */
function aqualuxe_care_guide_column_orderby($query) {
    if (!is_admin() || !$query->is_main_query()) {
        return;
    }
    
    $orderby = $query->get('orderby');
    
    if ('featured' === $orderby) {
        $query->set('meta_key', '_featured_guide');
        $query->set('orderby', 'meta_value');
    }
}
add_action('pre_get_posts', 'aqualuxe_care_guide_column_orderby');