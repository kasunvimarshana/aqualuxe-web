<?php
/**
 * AquaLuxe Fish Compatibility Checker
 *
 * Provides tools for checking compatibility between different fish species
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * AquaLuxe Compatibility Checker Class
 */
class AquaLuxe_Compatibility_Checker {
    /**
     * Singleton instance
     *
     * @var AquaLuxe_Compatibility_Checker
     */
    private static $instance = null;

    /**
     * Get singleton instance
     *
     * @return AquaLuxe_Compatibility_Checker
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    private function __construct() {
        $this->init_hooks();
    }

    /**
     * Initialize hooks
     */
    private function init_hooks() {
        // Add shortcodes
        add_shortcode('fish_compatibility_checker', array($this, 'compatibility_checker_shortcode'));
        
        // Add AJAX handlers
        add_action('wp_ajax_aqualuxe_check_compatibility', array($this, 'ajax_check_compatibility'));
        add_action('wp_ajax_nopriv_aqualuxe_check_compatibility', array($this, 'ajax_check_compatibility'));
        
        // Enqueue scripts and styles
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
    }

    /**
     * Enqueue scripts and styles
     */
    public function enqueue_scripts() {
        wp_enqueue_style(
            'aqualuxe-compatibility',
            AQUALUXE_ASSETS_URI . '/css/compatibility.css',
            array(),
            AQUALUXE_VERSION
        );
        
        wp_enqueue_script(
            'aqualuxe-compatibility',
            AQUALUXE_ASSETS_URI . '/js/compatibility.js',
            array('jquery'),
            AQUALUXE_VERSION,
            true
        );
        
        wp_localize_script(
            'aqualuxe-compatibility',
            'aqualuxeCompatibility',
            array(
                'ajaxurl' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('aqualuxe-compatibility-nonce'),
                'i18n' => array(
                    'error' => __('Error occurred. Please try again.', 'aqualuxe'),
                    'loading' => __('Checking compatibility...', 'aqualuxe'),
                    'select_fish' => __('Please select at least two fish species.', 'aqualuxe'),
                ),
            )
        );
    }

    /**
     * Compatibility checker shortcode
     *
     * @param array $atts Shortcode attributes.
     * @return string Shortcode output.
     */
    public function compatibility_checker_shortcode($atts) {
        $atts = shortcode_atts(array(
            'title' => __('Fish Compatibility Checker', 'aqualuxe'),
            'limit' => 100,
        ), $atts);
        
        // Get all fish species
        $args = array(
            'post_type' => 'fish_species',
            'posts_per_page' => intval($atts['limit']),
            'orderby' => 'title',
            'order' => 'ASC',
        );
        
        $fish_species = get_posts($args);
        
        ob_start();
        ?>
        <div class="aqualuxe-compatibility-checker">
            <h3><?php echo esc_html($atts['title']); ?></h3>
            
            <form class="compatibility-checker-form">
                <div class="form-row">
                    <label><?php esc_html_e('Select Fish Species to Compare:', 'aqualuxe'); ?></label>
                    <div class="fish-selection-container">
                        <div class="fish-selection-column">
                            <?php
                            $count = count($fish_species);
                            $half = ceil($count / 2);
                            $i = 0;
                            
                            foreach ($fish_species as $fish) {
                                if ($i === $half) {
                                    echo '</div><div class="fish-selection-column">';
                                }
                                ?>
                                <div class="fish-selection-item">
                                    <label>
                                        <input type="checkbox" name="fish_species[]" value="<?php echo esc_attr($fish->ID); ?>">
                                        <?php echo esc_html($fish->post_title); ?>
                                    </label>
                                </div>
                                <?php
                                $i++;
                            }
                            ?>
                        </div>
                    </div>
                </div>
                
                <div class="form-row">
                    <button type="submit" class="button check-compatibility-button"><?php esc_html_e('Check Compatibility', 'aqualuxe'); ?></button>
                </div>
            </form>
            
            <div class="compatibility-results" style="display: none;">
                <h4><?php esc_html_e('Compatibility Results', 'aqualuxe'); ?></h4>
                <div class="results-content"></div>
            </div>
            
            <div class="compatibility-notes">
                <h4><?php esc_html_e('Compatibility Guidelines', 'aqualuxe'); ?></h4>
                <ul>
                    <li><?php esc_html_e('Even compatible fish may have individual personalities that affect their behavior.', 'aqualuxe'); ?></li>
                    <li><?php esc_html_e('Provide adequate space and hiding places to reduce aggression.', 'aqualuxe'); ?></li>
                    <li><?php esc_html_e('Consider water parameter requirements when selecting fish for your aquarium.', 'aqualuxe'); ?></li>
                    <li><?php esc_html_e('Some fish may be compatible as juveniles but become territorial as adults.', 'aqualuxe'); ?></li>
                    <li><?php esc_html_e('Always research specific species before adding them to your aquarium.', 'aqualuxe'); ?></li>
                </ul>
            </div>
        </div>
        <?php
        
        wp_reset_postdata();
        
        return ob_get_clean();
    }

    /**
     * AJAX handler for checking fish compatibility
     */
    public function ajax_check_compatibility() {
        // Check nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'aqualuxe-compatibility-nonce')) {
            wp_send_json_error(array('message' => __('Security check failed.', 'aqualuxe')));
            exit;
        }
        
        // Get selected fish species
        $fish_ids = isset($_POST['fish_species']) ? array_map('intval', $_POST['fish_species']) : array();
        
        if (count($fish_ids) < 2) {
            wp_send_json_error(array('message' => __('Please select at least two fish species.', 'aqualuxe')));
            exit;
        }
        
        // Get fish data
        $fish_data = array();
        foreach ($fish_ids as $fish_id) {
            $fish = get_post($fish_id);
            if (!$fish || $fish->post_type !== 'fish_species') {
                continue;
            }
            
            $fish_data[$fish_id] = array(
                'id' => $fish_id,
                'name' => $fish->post_title,
                'scientific_name' => get_post_meta($fish_id, '_scientific_name', true),
                'temperament' => get_post_meta($fish_id, '_temperament', true),
                'min_tank_size' => get_post_meta($fish_id, '_min_tank_size', true),
                'temperature_min' => get_post_meta($fish_id, '_temperature_min', true),
                'temperature_max' => get_post_meta($fish_id, '_temperature_max', true),
                'ph_min' => get_post_meta($fish_id, '_ph_min', true),
                'ph_max' => get_post_meta($fish_id, '_ph_max', true),
                'hardness_min' => get_post_meta($fish_id, '_hardness_min', true),
                'hardness_max' => get_post_meta($fish_id, '_hardness_max', true),
                'swimming_level' => get_post_meta($fish_id, '_swimming_level', true),
                'compatible_with' => get_post_meta($fish_id, '_compatible_with', true),
                'incompatible_with' => get_post_meta($fish_id, '_incompatible_with', true),
                'compatibility_notes' => get_post_meta($fish_id, '_compatibility_notes', true),
                'thumbnail' => get_the_post_thumbnail_url($fish_id, 'thumbnail'),
            );
        }
        
        // Check compatibility between all fish pairs
        $compatibility_matrix = array();
        $compatibility_issues = array();
        $water_parameters = array(
            'temperature' => array('min' => 0, 'max' => 100),
            'ph' => array('min' => 0, 'max' => 14),
            'hardness' => array('min' => 0, 'max' => 30),
        );
        
        // Initialize water parameters with first fish
        foreach ($fish_data as $fish) {
            if (!empty($fish['temperature_min']) && !empty($fish['temperature_max'])) {
                $water_parameters['temperature']['min'] = max($water_parameters['temperature']['min'], $fish['temperature_min']);
                $water_parameters['temperature']['max'] = min($water_parameters['temperature']['max'], $fish['temperature_max']);
            }
            
            if (!empty($fish['ph_min']) && !empty($fish['ph_max'])) {
                $water_parameters['ph']['min'] = max($water_parameters['ph']['min'], $fish['ph_min']);
                $water_parameters['ph']['max'] = min($water_parameters['ph']['max'], $fish['ph_max']);
            }
            
            if (!empty($fish['hardness_min']) && !empty($fish['hardness_max'])) {
                $water_parameters['hardness']['min'] = max($water_parameters['hardness']['min'], $fish['hardness_min']);
                $water_parameters['hardness']['max'] = min($water_parameters['hardness']['max'], $fish['hardness_max']);
            }
            
            break;
        }
        
        // Check compatibility between all fish pairs
        foreach ($fish_data as $fish1_id => $fish1) {
            foreach ($fish_data as $fish2_id => $fish2) {
                if ($fish1_id === $fish2_id) {
                    continue;
                }
                
                $compatibility = $this->check_pair_compatibility($fish1, $fish2);
                $compatibility_matrix[$fish1_id][$fish2_id] = $compatibility;
                
                // Track issues
                if ($compatibility['score'] < 3) {
                    $compatibility_issues[] = array(
                        'fish1' => $fish1['name'],
                        'fish2' => $fish2['name'],
                        'issues' => $compatibility['issues'],
                    );
                }
                
                // Update water parameters
                if (!empty($fish2['temperature_min']) && !empty($fish2['temperature_max'])) {
                    $water_parameters['temperature']['min'] = max($water_parameters['temperature']['min'], $fish2['temperature_min']);
                    $water_parameters['temperature']['max'] = min($water_parameters['temperature']['max'], $fish2['temperature_max']);
                }
                
                if (!empty($fish2['ph_min']) && !empty($fish2['ph_max'])) {
                    $water_parameters['ph']['min'] = max($water_parameters['ph']['min'], $fish2['ph_min']);
                    $water_parameters['ph']['max'] = min($water_parameters['ph']['max'], $fish2['ph_max']);
                }
                
                if (!empty($fish2['hardness_min']) && !empty($fish2['hardness_max'])) {
                    $water_parameters['hardness']['min'] = max($water_parameters['hardness']['min'], $fish2['hardness_min']);
                    $water_parameters['hardness']['max'] = min($water_parameters['hardness']['max'], $fish2['hardness_max']);
                }
            }
        }
        
        // Check for water parameter conflicts
        $water_conflicts = array();
        if ($water_parameters['temperature']['min'] > $water_parameters['temperature']['max']) {
            $water_conflicts[] = __('Temperature ranges do not overlap.', 'aqualuxe');
        }
        
        if ($water_parameters['ph']['min'] > $water_parameters['ph']['max']) {
            $water_conflicts[] = __('pH ranges do not overlap.', 'aqualuxe');
        }
        
        if ($water_parameters['hardness']['min'] > $water_parameters['hardness']['max']) {
            $water_conflicts[] = __('Water hardness ranges do not overlap.', 'aqualuxe');
        }
        
        // Calculate overall compatibility score
        $total_score = 0;
        $total_pairs = 0;
        
        foreach ($compatibility_matrix as $fish1_id => $compatibilities) {
            foreach ($compatibilities as $fish2_id => $compatibility) {
                $total_score += $compatibility['score'];
                $total_pairs++;
            }
        }
        
        $overall_score = $total_pairs > 0 ? $total_score / $total_pairs : 0;
        $overall_compatibility = $this->get_compatibility_level($overall_score);
        
        // Determine minimum tank size
        $min_tank_size = 0;
        foreach ($fish_data as $fish) {
            if (!empty($fish['min_tank_size'])) {
                $min_tank_size = max($min_tank_size, intval($fish['min_tank_size']));
            }
        }
        
        // Generate HTML output
        $html = '';
        
        // Overall compatibility
        $html .= '<div class="overall-compatibility ' . esc_attr($overall_compatibility['class']) . '">';
        $html .= '<h4>' . __('Overall Compatibility:', 'aqualuxe') . ' <span>' . esc_html($overall_compatibility['label']) . '</span></h4>';
        $html .= '<div class="compatibility-meter">';
        $html .= '<div class="compatibility-meter-bar">';
        $html .= '<div class="compatibility-meter-fill" style="width: ' . esc_attr(min(100, $overall_score * 20)) . '%;"></div>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        
        // Water parameters
        $html .= '<div class="water-parameters">';
        $html .= '<h4>' . __('Recommended Water Parameters', 'aqualuxe') . '</h4>';
        
        if (!empty($water_conflicts)) {
            $html .= '<div class="water-conflicts">';
            $html .= '<p><strong>' . __('Warning: Water Parameter Conflicts', 'aqualuxe') . '</strong></p>';
            $html .= '<ul>';
            foreach ($water_conflicts as $conflict) {
                $html .= '<li>' . esc_html($conflict) . '</li>';
            }
            $html .= '</ul>';
            $html .= '</div>';
        } else {
            $html .= '<ul>';
            
            if ($water_parameters['temperature']['min'] < $water_parameters['temperature']['max']) {
                $html .= '<li><strong>' . __('Temperature:', 'aqualuxe') . '</strong> ' . 
                    number_format($water_parameters['temperature']['min'], 1) . '°F - ' . 
                    number_format($water_parameters['temperature']['max'], 1) . '°F</li>';
            }
            
            if ($water_parameters['ph']['min'] < $water_parameters['ph']['max']) {
                $html .= '<li><strong>' . __('pH:', 'aqualuxe') . '</strong> ' . 
                    number_format($water_parameters['ph']['min'], 1) . ' - ' . 
                    number_format($water_parameters['ph']['max'], 1) . '</li>';
            }
            
            if ($water_parameters['hardness']['min'] < $water_parameters['hardness']['max']) {
                $html .= '<li><strong>' . __('Water Hardness (dGH):', 'aqualuxe') . '</strong> ' . 
                    number_format($water_parameters['hardness']['min'], 1) . ' - ' . 
                    number_format($water_parameters['hardness']['max'], 1) . '</li>';
            }
            
            $html .= '</ul>';
        }
        
        // Minimum tank size
        if ($min_tank_size > 0) {
            $html .= '<p><strong>' . __('Minimum Tank Size:', 'aqualuxe') . '</strong> ' . 
                $min_tank_size . ' ' . __('gallons', 'aqualuxe') . '</p>';
        }
        
        $html .= '</div>';
        
        // Compatibility issues
        if (!empty($compatibility_issues)) {
            $html .= '<div class="compatibility-issues">';
            $html .= '<h4>' . __('Potential Compatibility Issues', 'aqualuxe') . '</h4>';
            $html .= '<ul>';
            
            foreach ($compatibility_issues as $issue) {
                $html .= '<li><strong>' . esc_html($issue['fish1']) . ' + ' . esc_html($issue['fish2']) . ':</strong> ';
                $html .= implode(', ', array_map('esc_html', $issue['issues']));
                $html .= '</li>';
            }
            
            $html .= '</ul>';
            $html .= '</div>';
        }
        
        // Fish details
        $html .= '<div class="fish-details">';
        $html .= '<h4>' . __('Selected Fish Details', 'aqualuxe') . '</h4>';
        $html .= '<div class="fish-grid">';
        
        foreach ($fish_data as $fish) {
            $html .= '<div class="fish-card">';
            
            if (!empty($fish['thumbnail'])) {
                $html .= '<div class="fish-image"><img src="' . esc_url($fish['thumbnail']) . '" alt="' . esc_attr($fish['name']) . '"></div>';
            } else {
                $html .= '<div class="fish-image"><img src="' . esc_url(AQUALUXE_ASSETS_URI . '/images/placeholder-fish.png') . '" alt="' . esc_attr($fish['name']) . '"></div>';
            }
            
            $html .= '<div class="fish-info">';
            $html .= '<h5>' . esc_html($fish['name']) . '</h5>';
            
            if (!empty($fish['scientific_name'])) {
                $html .= '<p class="scientific-name"><em>' . esc_html($fish['scientific_name']) . '</em></p>';
            }
            
            $html .= '<ul class="fish-specs">';
            
            if (!empty($fish['temperament'])) {
                $temperament_labels = array(
                    'peaceful' => __('Peaceful', 'aqualuxe'),
                    'semi-aggressive' => __('Semi-Aggressive', 'aqualuxe'),
                    'aggressive' => __('Aggressive', 'aqualuxe'),
                );
                $temperament = isset($temperament_labels[$fish['temperament']]) ? $temperament_labels[$fish['temperament']] : $fish['temperament'];
                $html .= '<li><strong>' . __('Temperament:', 'aqualuxe') . '</strong> ' . esc_html($temperament) . '</li>';
            }
            
            if (!empty($fish['min_tank_size'])) {
                $html .= '<li><strong>' . __('Min Tank:', 'aqualuxe') . '</strong> ' . esc_html($fish['min_tank_size']) . ' ' . __('gal', 'aqualuxe') . '</li>';
            }
            
            if (!empty($fish['temperature_min']) && !empty($fish['temperature_max'])) {
                $html .= '<li><strong>' . __('Temp:', 'aqualuxe') . '</strong> ' . 
                    number_format($fish['temperature_min'], 1) . '-' . 
                    number_format($fish['temperature_max'], 1) . '°F</li>';
            }
            
            if (!empty($fish['ph_min']) && !empty($fish['ph_max'])) {
                $html .= '<li><strong>' . __('pH:', 'aqualuxe') . '</strong> ' . 
                    number_format($fish['ph_min'], 1) . '-' . 
                    number_format($fish['ph_max'], 1) . '</li>';
            }
            
            if (!empty($fish['swimming_level'])) {
                $swimming_labels = array(
                    'top' => __('Top', 'aqualuxe'),
                    'middle' => __('Middle', 'aqualuxe'),
                    'bottom' => __('Bottom', 'aqualuxe'),
                    'all' => __('All Levels', 'aqualuxe'),
                );
                $swimming = isset($swimming_labels[$fish['swimming_level']]) ? $swimming_labels[$fish['swimming_level']] : $fish['swimming_level'];
                $html .= '<li><strong>' . __('Swims:', 'aqualuxe') . '</strong> ' . esc_html($swimming) . '</li>';
            }
            
            $html .= '</ul>';
            
            $html .= '<a href="' . esc_url(get_permalink($fish['id'])) . '" class="button fish-details-link">' . __('View Details', 'aqualuxe') . '</a>';
            
            $html .= '</div>'; // .fish-info
            $html .= '</div>'; // .fish-card
        }
        
        $html .= '</div>'; // .fish-grid
        $html .= '</div>'; // .fish-details
        
        // Compatibility matrix
        $html .= '<div class="compatibility-matrix">';
        $html .= '<h4>' . __('Compatibility Matrix', 'aqualuxe') . '</h4>';
        $html .= '<table class="matrix-table">';
        $html .= '<thead><tr><th></th>';
        
        foreach ($fish_data as $fish) {
            $html .= '<th>' . esc_html($fish['name']) . '</th>';
        }
        
        $html .= '</tr></thead>';
        $html .= '<tbody>';
        
        foreach ($fish_data as $fish1_id => $fish1) {
            $html .= '<tr>';
            $html .= '<th>' . esc_html($fish1['name']) . '</th>';
            
            foreach ($fish_data as $fish2_id => $fish2) {
                if ($fish1_id === $fish2_id) {
                    $html .= '<td class="same-fish">-</td>';
                } else {
                    $compatibility = $compatibility_matrix[$fish1_id][$fish2_id];
                    $level = $this->get_compatibility_level($compatibility['score']);
                    $html .= '<td class="' . esc_attr($level['class']) . '" title="' . esc_attr(implode(', ', $compatibility['issues'])) . '">';
                    $html .= esc_html($level['short']);
                    $html .= '</td>';
                }
            }
            
            $html .= '</tr>';
        }
        
        $html .= '</tbody>';
        $html .= '</table>';
        $html .= '</div>';
        
        // Legend
        $html .= '<div class="compatibility-legend">';
        $html .= '<h4>' . __('Compatibility Legend', 'aqualuxe') . '</h4>';
        $html .= '<ul>';
        $html .= '<li class="compatible">' . __('Compatible: These fish should get along well.', 'aqualuxe') . '</li>';
        $html .= '<li class="mostly-compatible">' . __('Mostly Compatible: These fish should generally get along, but watch for occasional issues.', 'aqualuxe') . '</li>';
        $html .= '<li class="caution">' . __('Caution: These fish may have some compatibility issues. Provide extra space and hiding places.', 'aqualuxe') . '</li>';
        $html .= '<li class="not-recommended">' . __('Not Recommended: These fish are likely to have significant compatibility problems.', 'aqualuxe') . '</li>';
        $html .= '<li class="incompatible">' . __('Incompatible: These fish should not be kept together.', 'aqualuxe') . '</li>';
        $html .= '</ul>';
        $html .= '</div>';
        
        wp_send_json_success(array('html' => $html));
    }

    /**
     * Check compatibility between two fish
     * 
     * @param array $fish1 First fish data.
     * @param array $fish2 Second fish data.
     * @return array Compatibility score and issues.
     */
    private function check_pair_compatibility($fish1, $fish2) {
        $score = 5; // Start with perfect compatibility
        $issues = array();
        
        // Check explicit compatibility/incompatibility
        if (!empty($fish1['compatible_with']) && stripos($fish1['compatible_with'], $fish2['name']) !== false) {
            return array('score' => 5, 'issues' => array());
        }
        
        if (!empty($fish1['incompatible_with']) && stripos($fish1['incompatible_with'], $fish2['name']) !== false) {
            return array('score' => 1, 'issues' => array(__('Explicitly listed as incompatible', 'aqualuxe')));
        }
        
        // Check temperament compatibility
        if (!empty($fish1['temperament']) && !empty($fish2['temperament'])) {
            if ($fish1['temperament'] === 'aggressive' && $fish2['temperament'] === 'peaceful') {
                $score -= 2;
                $issues[] = __('Aggressive fish with peaceful fish', 'aqualuxe');
            } elseif ($fish1['temperament'] === 'aggressive' && $fish2['temperament'] === 'semi-aggressive') {
                $score -= 1;
                $issues[] = __('Aggressive fish with semi-aggressive fish', 'aqualuxe');
            } elseif ($fish1['temperament'] === 'aggressive' && $fish2['temperament'] === 'aggressive') {
                $score -= 1;
                $issues[] = __('Two aggressive fish may fight', 'aqualuxe');
            } elseif ($fish1['temperament'] === 'semi-aggressive' && $fish2['temperament'] === 'peaceful') {
                $score -= 1;
                $issues[] = __('Semi-aggressive fish with peaceful fish', 'aqualuxe');
            }
        }
        
        // Check water parameter compatibility
        if (!empty($fish1['temperature_min']) && !empty($fish1['temperature_max']) && 
            !empty($fish2['temperature_min']) && !empty($fish2['temperature_max'])) {
            
            if ($fish1['temperature_max'] < $fish2['temperature_min'] || $fish1['temperature_min'] > $fish2['temperature_max']) {
                $score -= 2;
                $issues[] = __('Temperature ranges do not overlap', 'aqualuxe');
            } elseif (abs($fish1['temperature_min'] - $fish2['temperature_min']) > 5 || abs($fish1['temperature_max'] - $fish2['temperature_max']) > 5) {
                $score -= 1;
                $issues[] = __('Temperature preferences differ significantly', 'aqualuxe');
            }
        }
        
        if (!empty($fish1['ph_min']) && !empty($fish1['ph_max']) && 
            !empty($fish2['ph_min']) && !empty($fish2['ph_max'])) {
            
            if ($fish1['ph_max'] < $fish2['ph_min'] || $fish1['ph_min'] > $fish2['ph_max']) {
                $score -= 2;
                $issues[] = __('pH ranges do not overlap', 'aqualuxe');
            } elseif (abs($fish1['ph_min'] - $fish2['ph_min']) > 1 || abs($fish1['ph_max'] - $fish2['ph_max']) > 1) {
                $score -= 1;
                $issues[] = __('pH preferences differ significantly', 'aqualuxe');
            }
        }
        
        if (!empty($fish1['hardness_min']) && !empty($fish1['hardness_max']) && 
            !empty($fish2['hardness_min']) && !empty($fish2['hardness_max'])) {
            
            if ($fish1['hardness_max'] < $fish2['hardness_min'] || $fish1['hardness_min'] > $fish2['hardness_max']) {
                $score -= 1;
                $issues[] = __('Water hardness ranges do not overlap', 'aqualuxe');
            }
        }
        
        // Check swimming level compatibility
        if (!empty($fish1['swimming_level']) && !empty($fish2['swimming_level'])) {
            if ($fish1['swimming_level'] === $fish2['swimming_level'] && 
                $fish1['swimming_level'] !== 'all' && 
                $fish1['temperament'] === 'aggressive' && 
                $fish2['temperament'] === 'aggressive') {
                
                $score -= 1;
                $issues[] = __('Aggressive fish competing for same swimming level', 'aqualuxe');
            }
        }
        
        // Ensure score is between 1 and 5
        $score = max(1, min(5, $score));
        
        // If no issues but score is reduced, add a generic issue
        if (empty($issues) && $score < 5) {
            $issues[] = __('Minor compatibility concerns', 'aqualuxe');
        }
        
        return array('score' => $score, 'issues' => $issues);
    }

    /**
     * Get compatibility level based on score
     * 
     * @param int $score Compatibility score (1-5).
     * @return array Compatibility level data.
     */
    private function get_compatibility_level($score) {
        if ($score >= 4.5) {
            return array(
                'label' => __('Compatible', 'aqualuxe'),
                'short' => __('C', 'aqualuxe'),
                'class' => 'compatible',
            );
        } elseif ($score >= 3.5) {
            return array(
                'label' => __('Mostly Compatible', 'aqualuxe'),
                'short' => __('MC', 'aqualuxe'),
                'class' => 'mostly-compatible',
            );
        } elseif ($score >= 2.5) {
            return array(
                'label' => __('Caution', 'aqualuxe'),
                'short' => __('CA', 'aqualuxe'),
                'class' => 'caution',
            );
        } elseif ($score >= 1.5) {
            return array(
                'label' => __('Not Recommended', 'aqualuxe'),
                'short' => __('NR', 'aqualuxe'),
                'class' => 'not-recommended',
            );
        } else {
            return array(
                'label' => __('Incompatible', 'aqualuxe'),
                'short' => __('I', 'aqualuxe'),
                'class' => 'incompatible',
            );
        }
    }
}