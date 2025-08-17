<?php
/**
 * Fish Compatibility Checker Functions
 *
 * Functions for the fish compatibility checker tool
 *
 * @package AquaLuxe
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Enqueue compatibility checker scripts and styles
 */
function aqualuxe_enqueue_compatibility_checker_assets() {
    // Only enqueue on pages with the shortcode
    global $post;
    if (is_a($post, 'WP_Post') && has_shortcode($post->post_content, 'compatibility_checker')) {
        // Enqueue CSS
        wp_enqueue_style(
            'aqualuxe-compatibility-checker-styles',
            get_template_directory_uri() . '/assets/css/compatibility-checker.css',
            array(),
            AQUALUXE_VERSION
        );
        
        // Enqueue JavaScript
        wp_enqueue_script(
            'aqualuxe-compatibility-checker-script',
            get_template_directory_uri() . '/assets/js/compatibility-checker.js',
            array('jquery'),
            AQUALUXE_VERSION,
            true
        );
        
        // Localize script
        wp_localize_script(
            'aqualuxe-compatibility-checker-script',
            'aqualuxe_compatibility',
            array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('aqualuxe_compatibility_checker_nonce'),
                'default_fish_image' => get_template_directory_uri() . '/assets/images/default-fish.jpg',
            )
        );
    }
}
add_action('wp_enqueue_scripts', 'aqualuxe_enqueue_compatibility_checker_assets');

/**
 * Fish Compatibility Checker shortcode
 */
function aqualuxe_compatibility_checker_shortcode($atts) {
    // Extract attributes
    $atts = shortcode_atts(
        array(
            'title' => __('Fish Compatibility Checker', 'aqualuxe'),
            'description' => __('Check if your fish are compatible with each other and suitable for your aquarium.', 'aqualuxe'),
        ),
        $atts,
        'compatibility_checker'
    );
    
    // Start output buffer
    ob_start();
    
    ?>
    <div class="compatibility-checker-container">
        <div class="compatibility-checker-header">
            <h2><?php echo esc_html($atts['title']); ?></h2>
            <p><?php echo esc_html($atts['description']); ?></p>
        </div>
        
        <div id="compatibility-loading" class="compatibility-loading">
            <div class="loading-spinner"></div>
            <p><?php _e('Loading fish database...', 'aqualuxe'); ?></p>
        </div>
        
        <form id="compatibility-checker-form" class="compatibility-checker-form">
            <div class="form-section">
                <h3 class="form-section-title"><?php _e('Tank Information', 'aqualuxe'); ?></h3>
                <div class="form-row">
                    <div class="form-group">
                        <label for="tank-size"><?php _e('Tank Size (gallons)', 'aqualuxe'); ?></label>
                        <input type="number" id="tank-size" name="tank_size" min="1" step="0.1" placeholder="<?php _e('Enter tank size', 'aqualuxe'); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="tank-type"><?php _e('Tank Type', 'aqualuxe'); ?></label>
                        <select id="tank-type" name="tank_type" required>
                            <option value="freshwater"><?php _e('Freshwater', 'aqualuxe'); ?></option>
                            <option value="saltwater"><?php _e('Saltwater', 'aqualuxe'); ?></option>
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="form-section">
                <h3 class="form-section-title"><?php _e('Select Fish', 'aqualuxe'); ?></h3>
                <div id="fish-selector" class="fish-selector">
                    <div class="fish-search-container">
                        <input type="text" id="fish-search" class="fish-search-input" placeholder="<?php _e('Search for fish by name...', 'aqualuxe'); ?>">
                        <span class="fish-search-icon">🔍</span>
                    </div>
                    <div id="fish-search-results" class="fish-search-results"></div>
                    
                    <div class="selected-fish-label"><?php _e('Selected Fish:', 'aqualuxe'); ?></div>
                    <div id="selected-fish" class="selected-fish-container">
                        <div class="empty-selection"><?php _e('Search and select fish to check compatibility', 'aqualuxe'); ?></div>
                    </div>
                </div>
            </div>
            
            <div class="form-actions">
                <button type="submit" id="check-compatibility" class="btn btn-primary" disabled><?php _e('Check Compatibility', 'aqualuxe'); ?></button>
                <button type="button" id="reset-compatibility" class="btn btn-secondary"><?php _e('Reset', 'aqualuxe'); ?></button>
            </div>
        </form>
        
        <div id="compatibility-results" class="compatibility-results">
            <div class="results-header">
                <h3><?php _e('Compatibility Analysis', 'aqualuxe'); ?></h3>
                <p><?php _e('Below is an analysis of the compatibility between your selected fish.', 'aqualuxe'); ?></p>
            </div>
            
            <div id="compatibility-matrix"></div>
            
            <div id="compatibility-recommendations"></div>
            
            <div class="results-actions">
                <button type="button" id="save-compatibility" class="btn btn-primary"><?php _e('Save as PDF', 'aqualuxe'); ?></button>
                <button type="button" id="print-compatibility" class="btn btn-secondary"><?php _e('Print Results', 'aqualuxe'); ?></button>
            </div>
        </div>
    </div>
    <?php
    
    // Return the output buffer contents
    return ob_get_clean();
}
add_shortcode('compatibility_checker', 'aqualuxe_compatibility_checker_shortcode');

/**
 * AJAX handler for getting fish database
 */
function aqualuxe_get_fish_database() {
    // Check nonce
    if (!isset($_POST['security']) || !wp_verify_nonce(sanitize_text_field($_POST['security']), 'aqualuxe_compatibility_checker_nonce')) {
        wp_send_json_error(array('message' => 'Security check failed'));
        die();
    }
    
    // Get fish database
    $fish_database = aqualuxe_get_fish_compatibility_data();
    
    // Filter out any sensitive information if needed
    foreach ($fish_database as &$fish) {
        // Ensure all image paths are valid
        if (isset($fish['image']) && !filter_var($fish['image'], FILTER_VALIDATE_URL)) {
            $fish['image'] = get_template_directory_uri() . '/assets/images/default-fish.jpg';
        }
    }
    
    wp_send_json_success(array('fish' => $fish_database));
    die();
}
add_action('wp_ajax_aqualuxe_get_fish_database', 'aqualuxe_get_fish_database');
add_action('wp_ajax_nopriv_aqualuxe_get_fish_database', 'aqualuxe_get_fish_database');

/**
 * AJAX handler for generating PDF of compatibility results
 */
function aqualuxe_generate_compatibility_pdf() {
    // Check nonce
    if (!isset($_POST['security']) || !wp_verify_nonce($_POST['security'], 'aqualuxe_compatibility_checker_nonce')) {
        wp_send_json_error(array('message' => 'Security check failed'));
        die();
    }
    
    // Get data
    $data = isset($_POST['data']) ? json_decode(wp_unslash($_POST['data']), true) : array();
    
    if (empty($data)) {
        wp_send_json_error(array('message' => 'Invalid data'));
        die();
    }
    
    // Get fish database
    $fish_database = aqualuxe_get_fish_compatibility_data();
    
    // Get selected fish data
    $selected_fish = array();
    foreach ($data['fish'] as $fish_id) {
        if (isset($fish_database[$fish_id])) {
            $selected_fish[] = $fish_database[$fish_id];
        }
    }
    
    // Build HTML for PDF
    $html = '
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="utf-8">
        <title>Fish Compatibility Report</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                line-height: 1.6;
                color: #333;
                max-width: 800px;
                margin: 0 auto;
                padding: 20px;
            }
            h1, h2, h3 {
                color: #0056b3;
            }
            table {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 20px;
            }
            th, td {
                padding: 8px;
                border: 1px solid #ddd;
                text-align: left;
            }
            th {
                background-color: #f8f9fa;
                font-weight: bold;
            }
            .compatible {
                background-color: rgba(40, 167, 69, 0.1);
                color: #28a745;
            }
            .caution {
                background-color: rgba(255, 193, 7, 0.1);
                color: #ffc107;
            }
            .incompatible {
                background-color: rgba(220, 53, 69, 0.1);
                color: #dc3545;
            }
            .recommendation {
                padding: 10px;
                margin-bottom: 10px;
                border-radius: 4px;
            }
            .recommendation.high {
                background-color: rgba(220, 53, 69, 0.1);
                border-left: 4px solid #dc3545;
            }
            .recommendation.medium {
                background-color: rgba(255, 193, 7, 0.1);
                border-left: 4px solid #ffc107;
            }
            .recommendation.low {
                background-color: rgba(40, 167, 69, 0.1);
                border-left: 4px solid #28a745;
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
        <h1>Fish Compatibility Report</h1>
        <p>Generated on ' . date('F j, Y') . ' at ' . date('g:i a') . '</p>
        
        <h2>Tank Information</h2>
        <table>
            <tr>
                <th>Tank Size</th>
                <td>' . esc_html($data['tankSize']) . ' gallons</td>
            </tr>
            <tr>
                <th>Tank Type</th>
                <td>' . esc_html(ucfirst($data['tankType'])) . '</td>
            </tr>
        </table>
        
        <h2>Selected Fish</h2>
        <table>
            <tr>
                <th>Fish Name</th>
                <th>Scientific Name</th>
                <th>Size</th>
                <th>Temperament</th>
            </tr>';
    
    foreach ($selected_fish as $fish) {
        $html .= '
            <tr>
                <td>' . esc_html($fish['name']) . '</td>
                <td><em>' . esc_html($fish['scientific_name']) . '</em></td>
                <td>' . esc_html($fish['size']) . ' inches</td>
                <td>' . esc_html(ucfirst($fish['temperament'])) . '</td>
            </tr>';
    }
    
    $html .= '
        </table>
        
        <h2>Compatibility Matrix</h2>
        <table>
            <tr>
                <th></th>';
    
    foreach ($selected_fish as $fish) {
        $html .= '<th>' . esc_html($fish['name']) . '</th>';
    }
    
    $html .= '</tr>';
    
    foreach ($selected_fish as $fish1) {
        $html .= '<tr><th>' . esc_html($fish1['name']) . '</th>';
        
        foreach ($selected_fish as $fish2) {
            if ($fish1['id'] === $fish2['id']) {
                $html .= '<td>—</td>';
            } else {
                if (isset($data['compatibilityMatrix'][$fish1['id']][$fish2['id']])) {
                    $compatibility = $data['compatibilityMatrix'][$fish1['id']][$fish2['id']];
                    $status_class = isset($compatibility['status']) ? $compatibility['status'] : 'unknown';
                    $status_text = '';
                    
                    switch ($status_class) {
                    case 'compatible':
                        $status_text = '✓';
                        break;
                    case 'caution':
                        $status_text = '!';
                        break;
                    case 'incompatible':
                        $status_text = '✗';
                        break;
                }
                    
                    $html .= '<td class="' . esc_attr($status_class) . '">' . $status_text . '</td>';
                } else {
                    $html .= '<td class="unknown">?</td>';
                }
            }
        }
        
        $html .= '</tr>';
    }
    
    $html .= '
        </table>
        
        <h2>Space Requirements</h2>
        <p>Tank Utilization: ' . round(isset($data['spaceRequirements']['spaceUtilization']) ? $data['spaceRequirements']['spaceUtilization'] : 0) . '%</p>
        <table>
            <tr>
                <th>Fish</th>
                <th>Minimum Group</th>
                <th>Space Required</th>
            </tr>';
    
    foreach ($selected_fish as $fish) {
        if (isset($data['spaceRequirements']['fishSpaceNeeds'][$fish['id']])) {
            $space_needs = $data['spaceRequirements']['fishSpaceNeeds'][$fish['id']];
            $gallons_needed = round($space_needs['totalSpace'] / 231, 1); // Convert cubic inches to gallons
            
            $html .= '
                <tr>
                    <td>' . esc_html($fish['name']) . '</td>
                    <td>' . esc_html($space_needs['minGroup']) . '</td>
                    <td>' . esc_html($gallons_needed) . ' gallons</td>
                </tr>';
        } else {
            $html .= '
                <tr>
                    <td>' . esc_html($fish['name']) . '</td>
                    <td>N/A</td>
                    <td>N/A</td>
                </tr>';
        }
    }
    
    $html .= '
        </table>
        
        <h2>Water Parameter Compatibility</h2>
        <table>
            <tr>
                <th>Parameter</th>
                <th>Compatible Range</th>
                <th>Status</th>
            </tr>';
    
    // pH row
    $html .= '<tr><td>pH</td>';
    
    if (isset($data['parameterCompatibility']['ph']) && $data['parameterCompatibility']['ph']['compatible']) {
        $html .= '<td>' . floatval($data['parameterCompatibility']['ph']['min']) . ' - ' . floatval($data['parameterCompatibility']['ph']['max']) . '</td>';
        $html .= '<td class="compatible">Compatible</td>';
    } else {
        $html .= '<td>No compatible range</td>';
        $html .= '<td class="incompatible">Incompatible</td>';
    }
    $html .= '</tr>';
    
    // Temperature row
    $html .= '<tr><td>Temperature</td>';
    
    if ($data['parameterCompatibility']['temperature']['compatible']) {
        $html .= '<td>' . $data['parameterCompatibility']['temperature']['min'] . '°F - ' . $data['parameterCompatibility']['temperature']['max'] . '°F</td>';
        $html .= '<td class="compatible">Compatible</td>';
    } else {
        $html .= '<td>No compatible range</td>';
        $html .= '<td class="incompatible">Incompatible</td>';
    }
    $html .= '</tr>';
    
    // Hardness row
    $html .= '<tr><td>Hardness</td>';
    
    if ($data['parameterCompatibility']['hardness']['compatible']) {
        $html .= '<td>' . $data['parameterCompatibility']['hardness']['min'] . ' dGH - ' . $data['parameterCompatibility']['hardness']['max'] . ' dGH</td>';
        $html .= '<td class="compatible">Compatible</td>';
    } else {
        $html .= '<td>No compatible range</td>';
        $html .= '<td class="incompatible">Incompatible</td>';
    }
    $html .= '</tr>';
    
    $html .= '
        </table>';
    
    // Recommendations
    if (!empty($data['recommendations'])) {
        $html .= '<h2>Recommendations</h2>';
        
        foreach ($data['recommendations'] as $rec) {
            $priority = isset($rec['priority']) ? $rec['priority'] : 'medium';
            $message = isset($rec['message']) ? $rec['message'] : '';
            $solution = isset($rec['solution']) ? $rec['solution'] : '';
            
            $html .= '<div class="recommendation ' . esc_attr($priority) . '">';
            $html .= '<p><strong>' . esc_html($message) . '</strong></p>';
            $html .= '<p>' . esc_html($solution) . '</p>';
            $html .= '</div>';
        }
    } else {
        $html .= '
        <h2>Recommendations</h2>
        <div class="recommendation low">
            <p><strong>All Compatible!</strong></p>
            <p>Your selected fish are compatible with each other and suitable for your tank size.</p>
        </div>';
    }
    
    $html .= '
        <div class="footer">
            <p>Generated from ' . get_bloginfo('name') . ' | ' . date('F j, Y') . '</p>
            <p>© ' . date('Y') . ' ' . get_bloginfo('name') . '. All rights reserved.</p>
        </div>
    </body>
    </html>';
    
    // Generate PDF filename with sanitized name
    $filename = 'fish-compatibility-' . sanitize_file_name(date('Y-m-d-H-i-s')) . '.pdf';
    
    // Generate PDF using wkhtmltopdf
    $upload_dir = wp_upload_dir();
    $pdf_path = trailingslashit($upload_dir['path']) . $filename;
    $pdf_url = trailingslashit($upload_dir['url']) . $filename;
    
    // Create temporary HTML file with sanitized name
    $temp_html_name = 'temp-' . md5(time() . wp_rand()) . '.html';
    $temp_html_path = trailingslashit($upload_dir['path']) . $temp_html_name;
    file_put_contents($temp_html_path, $html);
    
    // Generate PDF using wkhtmltopdf with proper path escaping
    $command = sprintf(
        'wkhtmltopdf --encoding utf-8 --margin-top 20 --margin-bottom 20 --margin-left 20 --margin-right 20 --footer-center "Page [page] of [topage]" %s %s',
        escapeshellarg($temp_html_path),
        escapeshellarg($pdf_path)
    );
    
    $output = array();
    $return_var = 0;
    exec($command, $output, $return_var);
    
    // Delete temporary HTML file
    if (file_exists($temp_html_path)) {
        unlink($temp_html_path);
    }
    
    // Check if PDF was generated
    if (file_exists($pdf_path)) {
        wp_send_json_success(array(
            'pdf_url' => $pdf_url,
            'filename' => $filename,
        ));
    } else {
        wp_send_json_error(array(
            'message' => 'Failed to generate PDF',
            'command' => $command,
            'return_var' => $return_var,
            'output' => $output
        ));
    }
    
    die();
}
add_action('wp_ajax_aqualuxe_generate_compatibility_pdf', 'aqualuxe_generate_compatibility_pdf');
add_action('wp_ajax_nopriv_aqualuxe_generate_compatibility_pdf', 'aqualuxe_generate_compatibility_pdf');

/**
 * Get fish compatibility data
 * 
 * This function returns a database of fish with their compatibility information.
 * In a real-world scenario, this would likely be stored in a database table.
 * 
 * @return array Fish compatibility data
 */
function aqualuxe_get_fish_compatibility_data() {
    // This is a simplified database of fish for demonstration purposes
    // In a real-world scenario, this would be stored in a database table
    return array(
        'neon_tetra' => array(
            'id' => 'neon_tetra',
            'name' => 'Neon Tetra',
            'scientific_name' => 'Paracheirodon innesi',
            'common_names' => array('Neon Tetra', 'Neon'),
            'image' => get_template_directory_uri() . '/assets/images/fish/neon_tetra.jpg',
            'size' => 1.5, // inches
            'min_group' => 6,
            'tank_type' => 'freshwater',
            'temperament' => 'peaceful',
            'water_level' => 'middle',
            'diet' => 'omnivore',
            'parameters' => array(
                'ph' => array('min' => 6.0, 'max' => 7.0),
                'temperature' => array('min' => 72, 'max' => 78),
                'hardness' => array('min' => 5, 'max' => 12)
            ),
            'compatibility' => array(
                'incompatible' => array('oscar', 'jack_dempsey', 'green_terror', 'flowerhorn', 'tiger_barb'),
                'caution' => array('angelfish', 'betta')
            )
        ),
        'guppy' => array(
            'id' => 'guppy',
            'name' => 'Guppy',
            'scientific_name' => 'Poecilia reticulata',
            'common_names' => array('Guppy', 'Million Fish', 'Rainbow Fish'),
            'image' => get_template_directory_uri() . '/assets/images/fish/guppy.jpg',
            'size' => 2.0, // inches
            'min_group' => 3,
            'tank_type' => 'freshwater',
            'temperament' => 'peaceful',
            'water_level' => 'top',
            'diet' => 'omnivore',
            'parameters' => array(
                'ph' => array('min' => 6.8, 'max' => 7.8),
                'temperature' => array('min' => 72, 'max' => 82),
                'hardness' => array('min' => 8, 'max' => 15)
            ),
            'compatibility' => array(
                'incompatible' => array('oscar', 'jack_dempsey', 'green_terror', 'flowerhorn'),
                'caution' => array('tiger_barb', 'angelfish')
            )
        ),
        'betta' => array(
            'id' => 'betta',
            'name' => 'Betta',
            'scientific_name' => 'Betta splendens',
            'common_names' => array('Siamese Fighting Fish', 'Betta'),
            'image' => get_template_directory_uri() . '/assets/images/fish/betta.jpg',
            'size' => 3.0, // inches
            'min_group' => 1,
            'tank_type' => 'freshwater',
            'temperament' => 'aggressive',
            'water_level' => 'top',
            'diet' => 'carnivore',
            'parameters' => array(
                'ph' => array('min' => 6.5, 'max' => 7.5),
                'temperature' => array('min' => 76, 'max' => 82),
                'hardness' => array('min' => 5, 'max' => 12)
            ),
            'compatibility' => array(
                'incompatible' => array('betta', 'gourami', 'angelfish', 'guppy', 'tiger_barb'),
                'caution' => array('neon_tetra', 'corydoras', 'platy')
            )
        ),
        'angelfish' => array(
            'id' => 'angelfish',
            'name' => 'Angelfish',
            'scientific_name' => 'Pterophyllum scalare',
            'common_names' => array('Freshwater Angelfish', 'Angelfish'),
            'image' => get_template_directory_uri() . '/assets/images/fish/angelfish.jpg',
            'size' => 6.0, // inches
            'min_group' => 1,
            'tank_type' => 'freshwater',
            'temperament' => 'semi-aggressive',
            'water_level' => 'middle',
            'diet' => 'omnivore',
            'parameters' => array(
                'ph' => array('min' => 6.5, 'max' => 7.5),
                'temperature' => array('min' => 76, 'max' => 84),
                'hardness' => array('min' => 5, 'max' => 13)
            ),
            'compatibility' => array(
                'incompatible' => array('betta', 'tiger_barb'),
                'caution' => array('neon_tetra', 'guppy', 'platy')
            )
        ),
        'corydoras' => array(
            'id' => 'corydoras',
            'name' => 'Corydoras Catfish',
            'scientific_name' => 'Corydoras aeneus',
            'common_names' => array('Bronze Cory', 'Cory Catfish'),
            'image' => get_template_directory_uri() . '/assets/images/fish/corydoras.jpg',
            'size' => 2.5, // inches
            'min_group' => 6,
            'tank_type' => 'freshwater',
            'temperament' => 'peaceful',
            'water_level' => 'bottom',
            'diet' => 'omnivore',
            'parameters' => array(
                'ph' => array('min' => 6.0, 'max' => 7.5),
                'temperature' => array('min' => 72, 'max' => 78),
                'hardness' => array('min' => 5, 'max' => 12)
            ),
            'compatibility' => array(
                'incompatible' => array('oscar', 'jack_dempsey', 'green_terror', 'flowerhorn'),
                'caution' => array('betta')
            )
        ),
        'platy' => array(
            'id' => 'platy',
            'name' => 'Platy',
            'scientific_name' => 'Xiphophorus maculatus',
            'common_names' => array('Southern Platyfish', 'Platy'),
            'image' => get_template_directory_uri() . '/assets/images/fish/platy.jpg',
            'size' => 2.5, // inches
            'min_group' => 3,
            'tank_type' => 'freshwater',
            'temperament' => 'peaceful',
            'water_level' => 'middle',
            'diet' => 'omnivore',
            'parameters' => array(
                'ph' => array('min' => 7.0, 'max' => 8.0),
                'temperature' => array('min' => 70, 'max' => 80),
                'hardness' => array('min' => 10, 'max' => 20)
            ),
            'compatibility' => array(
                'incompatible' => array('oscar', 'jack_dempsey', 'green_terror', 'flowerhorn'),
                'caution' => array('betta', 'tiger_barb')
            )
        ),
        'tiger_barb' => array(
            'id' => 'tiger_barb',
            'name' => 'Tiger Barb',
            'scientific_name' => 'Puntigrus tetrazona',
            'common_names' => array('Tiger Barb', 'Sumatra Barb'),
            'image' => get_template_directory_uri() . '/assets/images/fish/tiger_barb.jpg',
            'size' => 3.0, // inches
            'min_group' => 6,
            'tank_type' => 'freshwater',
            'temperament' => 'semi-aggressive',
            'water_level' => 'middle',
            'diet' => 'omnivore',
            'parameters' => array(
                'ph' => array('min' => 6.0, 'max' => 7.5),
                'temperature' => array('min' => 74, 'max' => 79),
                'hardness' => array('min' => 5, 'max' => 15)
            ),
            'compatibility' => array(
                'incompatible' => array('betta', 'angelfish', 'guppy', 'neon_tetra'),
                'caution' => array('platy', 'corydoras')
            )
        ),
        'oscar' => array(
            'id' => 'oscar',
            'name' => 'Oscar',
            'scientific_name' => 'Astronotus ocellatus',
            'common_names' => array('Oscar', 'Velvet Cichlid'),
            'image' => get_template_directory_uri() . '/assets/images/fish/oscar.jpg',
            'size' => 12.0, // inches
            'min_group' => 1,
            'tank_type' => 'freshwater',
            'temperament' => 'aggressive',
            'water_level' => 'all',
            'diet' => 'carnivore',
            'parameters' => array(
                'ph' => array('min' => 6.5, 'max' => 7.5),
                'temperature' => array('min' => 74, 'max' => 80),
                'hardness' => array('min' => 5, 'max' => 15)
            ),
            'compatibility' => array(
                'incompatible' => array('neon_tetra', 'guppy', 'betta', 'angelfish', 'corydoras', 'platy', 'tiger_barb'),
                'caution' => array('jack_dempsey', 'green_terror', 'flowerhorn')
            )
        ),
        'jack_dempsey' => array(
            'id' => 'jack_dempsey',
            'name' => 'Jack Dempsey',
            'scientific_name' => 'Rocio octofasciata',
            'common_names' => array('Jack Dempsey', 'Electric Blue Jack Dempsey'),
            'image' => get_template_directory_uri() . '/assets/images/fish/jack_dempsey.jpg',
            'size' => 10.0, // inches
            'min_group' => 1,
            'tank_type' => 'freshwater',
            'temperament' => 'aggressive',
            'water_level' => 'middle',
            'diet' => 'carnivore',
            'parameters' => array(
                'ph' => array('min' => 6.5, 'max' => 7.5),
                'temperature' => array('min' => 74, 'max' => 80),
                'hardness' => array('min' => 8, 'max' => 15)
            ),
            'compatibility' => array(
                'incompatible' => array('neon_tetra', 'guppy', 'betta', 'angelfish', 'corydoras', 'platy', 'tiger_barb'),
                'caution' => array('oscar', 'green_terror', 'flowerhorn')
            )
        ),
        'green_terror' => array(
            'id' => 'green_terror',
            'name' => 'Green Terror',
            'scientific_name' => 'Andinoacara rivulatus',
            'common_names' => array('Green Terror', 'Gold Saum'),
            'image' => get_template_directory_uri() . '/assets/images/fish/green_terror.jpg',
            'size' => 8.0, // inches
            'min_group' => 1,
            'tank_type' => 'freshwater',
            'temperament' => 'aggressive',
            'water_level' => 'middle',
            'diet' => 'carnivore',
            'parameters' => array(
                'ph' => array('min' => 6.5, 'max' => 8.0),
                'temperature' => array('min' => 72, 'max' => 79),
                'hardness' => array('min' => 8, 'max' => 15)
            ),
            'compatibility' => array(
                'incompatible' => array('neon_tetra', 'guppy', 'betta', 'angelfish', 'corydoras', 'platy', 'tiger_barb'),
                'caution' => array('oscar', 'jack_dempsey', 'flowerhorn')
            )
        ),
        'flowerhorn' => array(
            'id' => 'flowerhorn',
            'name' => 'Flowerhorn Cichlid',
            'scientific_name' => 'Hybrid Cichlid',
            'common_names' => array('Flowerhorn', 'Flowerhorn Cichlid'),
            'image' => get_template_directory_uri() . '/assets/images/fish/flowerhorn.jpg',
            'size' => 12.0, // inches
            'min_group' => 1,
            'tank_type' => 'freshwater',
            'temperament' => 'aggressive',
            'water_level' => 'middle',
            'diet' => 'carnivore',
            'parameters' => array(
                'ph' => array('min' => 7.0, 'max' => 8.0),
                'temperature' => array('min' => 80, 'max' => 86),
                'hardness' => array('min' => 9, 'max' => 20)
            ),
            'compatibility' => array(
                'incompatible' => array('neon_tetra', 'guppy', 'betta', 'angelfish', 'corydoras', 'platy', 'tiger_barb'),
                'caution' => array('oscar', 'jack_dempsey', 'green_terror')
            )
        ),
        'clownfish' => array(
            'id' => 'clownfish',
            'name' => 'Clownfish',
            'scientific_name' => 'Amphiprion ocellaris',
            'common_names' => array('Clownfish', 'Anemonefish', 'Nemo'),
            'image' => get_template_directory_uri() . '/assets/images/fish/clownfish.jpg',
            'size' => 3.0, // inches
            'min_group' => 2,
            'tank_type' => 'saltwater',
            'temperament' => 'semi-aggressive',
            'water_level' => 'middle',
            'diet' => 'omnivore',
            'parameters' => array(
                'ph' => array('min' => 8.0, 'max' => 8.4),
                'temperature' => array('min' => 74, 'max' => 79),
                'hardness' => array('min' => 8, 'max' => 12)
            ),
            'compatibility' => array(
                'incompatible' => array('lionfish', 'triggerfish'),
                'caution' => array('damselfish')
            )
        ),
        'yellow_tang' => array(
            'id' => 'yellow_tang',
            'name' => 'Yellow Tang',
            'scientific_name' => 'Zebrasoma flavescens',
            'common_names' => array('Yellow Tang', 'Yellow Sailfin Tang'),
            'image' => get_template_directory_uri() . '/assets/images/fish/yellow_tang.jpg',
            'size' => 8.0, // inches
            'min_group' => 1,
            'tank_type' => 'saltwater',
            'temperament' => 'semi-aggressive',
            'water_level' => 'middle',
            'diet' => 'herbivore',
            'parameters' => array(
                'ph' => array('min' => 8.1, 'max' => 8.4),
                'temperature' => array('min' => 75, 'max' => 80),
                'hardness' => array('min' => 8, 'max' => 12)
            ),
            'compatibility' => array(
                'incompatible' => array('lionfish', 'triggerfish'),
                'caution' => array('other_tangs')
            )
        ),
        'royal_gramma' => array(
            'id' => 'royal_gramma',
            'name' => 'Royal Gramma',
            'scientific_name' => 'Gramma loreto',
            'common_names' => array('Royal Gramma', 'Fairy Basslet'),
            'image' => get_template_directory_uri() . '/assets/images/fish/royal_gramma.jpg',
            'size' => 3.0, // inches
            'min_group' => 1,
            'tank_type' => 'saltwater',
            'temperament' => 'peaceful',
            'water_level' => 'middle',
            'diet' => 'carnivore',
            'parameters' => array(
                'ph' => array('min' => 8.1, 'max' => 8.4),
                'temperature' => array('min' => 72, 'max' => 78),
                'hardness' => array('min' => 8, 'max' => 12)
            ),
            'compatibility' => array(
                'incompatible' => array('lionfish', 'triggerfish'),
                'caution' => array()
            )
        ),
        'damselfish' => array(
            'id' => 'damselfish',
            'name' => 'Blue Damselfish',
            'scientific_name' => 'Chrysiptera cyanea',
            'common_names' => array('Blue Damselfish', 'Blue Devil'),
            'image' => get_template_directory_uri() . '/assets/images/fish/damselfish.jpg',
            'size' => 3.0, // inches
            'min_group' => 3,
            'tank_type' => 'saltwater',
            'temperament' => 'aggressive',
            'water_level' => 'middle',
            'diet' => 'omnivore',
            'parameters' => array(
                'ph' => array('min' => 8.1, 'max' => 8.4),
                'temperature' => array('min' => 75, 'max' => 80),
                'hardness' => array('min' => 8, 'max' => 12)
            ),
            'compatibility' => array(
                'incompatible' => array(),
                'caution' => array('clownfish', 'royal_gramma')
            )
        ),
        'lionfish' => array(
            'id' => 'lionfish',
            'name' => 'Lionfish',
            'scientific_name' => 'Pterois volitans',
            'common_names' => array('Lionfish', 'Turkeyfish', 'Zebrafish'),
            'image' => get_template_directory_uri() . '/assets/images/fish/lionfish.jpg',
            'size' => 15.0, // inches
            'min_group' => 1,
            'tank_type' => 'saltwater',
            'temperament' => 'aggressive',
            'water_level' => 'middle',
            'diet' => 'carnivore',
            'parameters' => array(
                'ph' => array('min' => 8.1, 'max' => 8.4),
                'temperature' => array('min' => 74, 'max' => 80),
                'hardness' => array('min' => 8, 'max' => 12)
            ),
            'compatibility' => array(
                'incompatible' => array('clownfish', 'royal_gramma', 'damselfish', 'yellow_tang'),
                'caution' => array('triggerfish')
            )
        ),
        'triggerfish' => array(
            'id' => 'triggerfish',
            'name' => 'Triggerfish',
            'scientific_name' => 'Balistoides conspicillum',
            'common_names' => array('Clown Triggerfish', 'Triggerfish'),
            'image' => get_template_directory_uri() . '/assets/images/fish/triggerfish.jpg',
            'size' => 20.0, // inches
            'min_group' => 1,
            'tank_type' => 'saltwater',
            'temperament' => 'aggressive',
            'water_level' => 'middle',
            'diet' => 'carnivore',
            'parameters' => array(
                'ph' => array('min' => 8.1, 'max' => 8.4),
                'temperature' => array('min' => 75, 'max' => 82),
                'hardness' => array('min' => 8, 'max' => 12)
            ),
            'compatibility' => array(
                'incompatible' => array('clownfish', 'royal_gramma', 'damselfish', 'yellow_tang'),
                'caution' => array('lionfish')
            )
        ),
    );
}

/**
 * Register Fish Compatibility Checker widget
 */
class Aqualuxe_Compatibility_Checker_Widget extends WP_Widget {
    /**
     * Register widget with WordPress
     */
    public function __construct() {
        parent::__construct(
            'aqualuxe_compatibility_checker_widget', // Base ID
            __('AquaLuxe Compatibility Checker', 'aqualuxe'), // Name
            array('description' => __('Display a link to the fish compatibility checker', 'aqualuxe')) // Args
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
        
        $checker_page = !empty($instance['checker_page']) ? get_permalink($instance['checker_page']) : '';
        $button_text = !empty($instance['button_text']) ? $instance['button_text'] : __('Check Fish Compatibility', 'aqualuxe');
        $description = !empty($instance['description']) ? $instance['description'] : '';
        
        ?>
        <div class="compatibility-checker-widget">
            <?php if (!empty($description)) : ?>
                <p><?php echo esc_html($description); ?></p>
            <?php endif; ?>
            
            <?php if (!empty($checker_page)) : ?>
                <a href="<?php echo esc_url($checker_page); ?>" class="compatibility-checker-widget-button"><?php echo esc_html($button_text); ?></a>
            <?php else : ?>
                <p class="compatibility-checker-widget-error"><?php _e('Please select a checker page in the widget settings.', 'aqualuxe'); ?></p>
            <?php endif; ?>
        </div>
        
        <style>
            .compatibility-checker-widget {
                text-align: center;
                padding: 1rem;
                background-color: var(--color-background-light, #f8f9fa);
                border-radius: 8px;
            }
            
            .compatibility-checker-widget-button {
                display: inline-block;
                padding: 0.75rem 1.5rem;
                background-color: var(--color-primary, #0056b3);
                color: white;
                text-decoration: none;
                border-radius: 4px;
                font-weight: 500;
                margin-top: 0.5rem;
                transition: background-color 0.2s ease;
            }
            
            .compatibility-checker-widget-button:hover {
                background-color: var(--color-primary-dark, #004494);
                color: white;
            }
            
            .compatibility-checker-widget-error {
                color: var(--color-danger, #dc3545);
                font-size: 0.9rem;
            }
        </style>
        <?php
        
        echo $args['after_widget'];
    }
    
    /**
     * Back-end widget form
     */
    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : __('Fish Compatibility Checker', 'aqualuxe');
        $checker_page = !empty($instance['checker_page']) ? $instance['checker_page'] : '';
        $button_text = !empty($instance['button_text']) ? $instance['button_text'] : __('Check Fish Compatibility', 'aqualuxe');
        $description = !empty($instance['description']) ? $instance['description'] : __('Check if your fish are compatible with each other before adding them to your tank.', 'aqualuxe');
        
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e('Title:', 'aqualuxe'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>
        
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('description')); ?>"><?php esc_html_e('Description:', 'aqualuxe'); ?></label>
            <textarea class="widefat" id="<?php echo esc_attr($this->get_field_id('description')); ?>" name="<?php echo esc_attr($this->get_field_name('description')); ?>" rows="3"><?php echo esc_textarea($description); ?></textarea>
        </p>
        
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('checker_page')); ?>"><?php esc_html_e('Checker Page:', 'aqualuxe'); ?></label>
            <?php
            wp_dropdown_pages(array(
                'name' => $this->get_field_name('checker_page'),
                'id' => $this->get_field_id('checker_page'),
                'selected' => $checker_page,
                'show_option_none' => __('Select a page', 'aqualuxe'),
            ));
            ?>
            <small><?php _e('Select the page where you have added the [compatibility_checker] shortcode.', 'aqualuxe'); ?></small>
        </p>
        
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('button_text')); ?>"><?php esc_html_e('Button Text:', 'aqualuxe'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('button_text')); ?>" name="<?php echo esc_attr($this->get_field_name('button_text')); ?>" type="text" value="<?php echo esc_attr($button_text); ?>">
        </p>
        <?php
    }
    
    /**
     * Sanitize widget form values as they are saved
     */
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? sanitize_text_field($new_instance['title']) : '';
        $instance['checker_page'] = (!empty($new_instance['checker_page'])) ? absint($new_instance['checker_page']) : '';
        $instance['button_text'] = (!empty($new_instance['button_text'])) ? sanitize_text_field($new_instance['button_text']) : '';
        $instance['description'] = (!empty($new_instance['description'])) ? sanitize_textarea_field($new_instance['description']) : '';
        
        return $instance;
    }
}

/**
 * Register compatibility checker widget
 */
function aqualuxe_register_compatibility_checker_widget() {
    register_widget('Aqualuxe_Compatibility_Checker_Widget');
}
add_action('widgets_init', 'aqualuxe_register_compatibility_checker_widget');