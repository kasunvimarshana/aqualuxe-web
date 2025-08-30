<?php
/**
 * Template part for displaying fish care guide
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Get fish species data
$fish_title = get_the_title($id);
$fish_content = get_post_field('post_content', $id);
$scientific_name = get_post_meta($id, '_scientific_name', true);
$care_instructions = get_post_meta($id, '_care_instructions', true);
$feeding_guide = get_post_meta($id, '_feeding_guide', true);
$tank_setup = get_post_meta($id, '_tank_setup', true);
$common_diseases = get_post_meta($id, '_common_diseases', true);
$breeding_tips = get_post_meta($id, '_breeding_tips', true);

// Get fish specifications
$adult_size = get_post_meta($id, '_adult_size', true);
$lifespan = get_post_meta($id, '_lifespan', true);
$min_tank_size = get_post_meta($id, '_min_tank_size', true);
$temperature_min = get_post_meta($id, '_temperature_min', true);
$temperature_max = get_post_meta($id, '_temperature_max', true);
$ph_min = get_post_meta($id, '_ph_min', true);
$ph_max = get_post_meta($id, '_ph_max', true);
$hardness_min = get_post_meta($id, '_hardness_min', true);
$hardness_max = get_post_meta($id, '_hardness_max', true);
$diet = get_post_meta($id, '_diet', true);
$temperament = get_post_meta($id, '_temperament', true);
$swimming_level = get_post_meta($id, '_swimming_level', true);

// Get taxonomies
$categories = get_the_terms($id, 'fish_category');
$families = get_the_terms($id, 'fish_family');
$origins = get_the_terms($id, 'fish_origin');
$care_levels = get_the_terms($id, 'care_level');

// Parse tabs to show
$tabs_to_show = isset($tabs_to_show) ? $tabs_to_show : array('care', 'specs', 'compatibility', 'breeding');
$guide_class = isset($class) ? ' ' . $class : '';
?>

<div class="aqualuxe-fish-care-guide<?php echo esc_attr($guide_class); ?>" data-fish-id="<?php echo esc_attr($id); ?>">
    <?php if (!empty($title)) : ?>
        <h2 class="guide-title"><?php echo esc_html($title); ?></h2>
    <?php else : ?>
        <h2 class="guide-title"><?php echo esc_html(sprintf(__('Care Guide for %s', 'aqualuxe'), $fish_title)); ?></h2>
    <?php endif; ?>
    
    <div class="fish-guide-header">
        <div class="fish-guide-image">
            <?php if (has_post_thumbnail($id)) : ?>
                <?php echo get_the_post_thumbnail($id, 'medium'); ?>
            <?php else : ?>
                <img src="<?php echo esc_url(AQUALUXE_ASSETS_URI . '/images/placeholder-fish.png'); ?>" alt="<?php echo esc_attr($fish_title); ?>" />
            <?php endif; ?>
        </div>
        
        <div class="fish-guide-info">
            <h3 class="fish-name"><?php echo esc_html($fish_title); ?></h3>
            
            <?php if (!empty($scientific_name)) : ?>
                <p class="scientific-name"><em><?php echo esc_html($scientific_name); ?></em></p>
            <?php endif; ?>
            
            <div class="fish-taxonomy">
                <?php if (!empty($categories)) : ?>
                    <span class="fish-category">
                        <i class="fas fa-water"></i> 
                        <?php 
                        $category_names = array();
                        foreach ($categories as $category) {
                            $category_names[] = $category->name;
                        }
                        echo esc_html(implode(', ', $category_names));
                        ?>
                    </span>
                <?php endif; ?>
                
                <?php if (!empty($origins)) : ?>
                    <span class="fish-origin">
                        <i class="fas fa-globe-americas"></i> 
                        <?php 
                        $origin_names = array();
                        foreach ($origins as $origin) {
                            $origin_names[] = $origin->name;
                        }
                        echo esc_html(implode(', ', $origin_names));
                        ?>
                    </span>
                <?php endif; ?>
                
                <?php if (!empty($care_levels)) : ?>
                    <span class="fish-care-level">
                        <i class="fas fa-hand-holding-heart"></i> 
                        <?php 
                        $care_level_names = array();
                        foreach ($care_levels as $care_level) {
                            $care_level_names[] = $care_level->name;
                        }
                        echo esc_html(implode(', ', $care_level_names));
                        ?>
                    </span>
                <?php endif; ?>
            </div>
            
            <div class="fish-key-specs">
                <?php if (!empty($temperament)) : 
                    $temperament_labels = array(
                        'peaceful' => __('Peaceful', 'aqualuxe'),
                        'semi-aggressive' => __('Semi-Aggressive', 'aqualuxe'),
                        'aggressive' => __('Aggressive', 'aqualuxe'),
                    );
                    $temperament_label = isset($temperament_labels[$temperament]) ? $temperament_labels[$temperament] : $temperament;
                ?>
                    <div class="key-spec temperament">
                        <span class="spec-label"><?php esc_html_e('Temperament', 'aqualuxe'); ?></span>
                        <span class="spec-value <?php echo esc_attr($temperament); ?>"><?php echo esc_html($temperament_label); ?></span>
                    </div>
                <?php endif; ?>
                
                <?php if (!empty($adult_size)) : ?>
                    <div class="key-spec size">
                        <span class="spec-label"><?php esc_html_e('Adult Size', 'aqualuxe'); ?></span>
                        <span class="spec-value"><?php echo esc_html($adult_size); ?></span>
                    </div>
                <?php endif; ?>
                
                <?php if (!empty($lifespan)) : ?>
                    <div class="key-spec lifespan">
                        <span class="spec-label"><?php esc_html_e('Lifespan', 'aqualuxe'); ?></span>
                        <span class="spec-value"><?php echo esc_html($lifespan); ?></span>
                    </div>
                <?php endif; ?>
                
                <?php if (!empty($min_tank_size)) : ?>
                    <div class="key-spec tank-size">
                        <span class="spec-label"><?php esc_html_e('Min Tank Size', 'aqualuxe'); ?></span>
                        <span class="spec-value"><?php echo esc_html($min_tank_size); ?> <?php esc_html_e('gallons', 'aqualuxe'); ?></span>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="fish-guide-content">
        <?php if (!empty($fish_content)) : ?>
            <div class="fish-overview">
                <h3><?php esc_html_e('Overview', 'aqualuxe'); ?></h3>
                <?php echo wp_kses_post($fish_content); ?>
            </div>
        <?php endif; ?>
        
        <?php if (in_array('care', $tabs_to_show)) : ?>
            <div class="fish-care-section">
                <h3><?php esc_html_e('Care Guide', 'aqualuxe'); ?></h3>
                
                <?php if (!empty($care_instructions)) : ?>
                    <div class="care-subsection care-instructions">
                        <h4><?php esc_html_e('General Care Instructions', 'aqualuxe'); ?></h4>
                        <?php echo wp_kses_post($care_instructions); ?>
                    </div>
                <?php endif; ?>
                
                <?php if (!empty($feeding_guide)) : ?>
                    <div class="care-subsection feeding-guide">
                        <h4><?php esc_html_e('Feeding Guide', 'aqualuxe'); ?></h4>
                        <p><?php echo esc_html($feeding_guide); ?></p>
                    </div>
                <?php endif; ?>
                
                <?php if (!empty($tank_setup)) : ?>
                    <div class="care-subsection tank-setup">
                        <h4><?php esc_html_e('Recommended Tank Setup', 'aqualuxe'); ?></h4>
                        <p><?php echo esc_html($tank_setup); ?></p>
                    </div>
                <?php endif; ?>
                
                <?php if (!empty($common_diseases)) : ?>
                    <div class="care-subsection common-diseases">
                        <h4><?php esc_html_e('Common Diseases & Prevention', 'aqualuxe'); ?></h4>
                        <p><?php echo esc_html($common_diseases); ?></p>
                    </div>
                <?php endif; ?>
                
                <?php if (empty($care_instructions) && empty($feeding_guide) && empty($tank_setup) && empty($common_diseases)) : ?>
                    <p class="no-care-info"><?php esc_html_e('Detailed care information for this species is currently being updated. Please check back soon.', 'aqualuxe'); ?></p>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        
        <?php if (in_array('specs', $tabs_to_show)) : ?>
            <div class="fish-specs-section">
                <h3><?php esc_html_e('Specifications', 'aqualuxe'); ?></h3>
                
                <div class="specs-grid">
                    <?php if (!empty($temperature_min) && !empty($temperature_max)) : ?>
                        <div class="spec-item">
                            <span class="spec-name"><?php esc_html_e('Temperature Range', 'aqualuxe'); ?></span>
                            <span class="spec-value"><?php echo esc_html($temperature_min); ?>°F - <?php echo esc_html($temperature_max); ?>°F</span>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($ph_min) && !empty($ph_max)) : ?>
                        <div class="spec-item">
                            <span class="spec-name"><?php esc_html_e('pH Range', 'aqualuxe'); ?></span>
                            <span class="spec-value"><?php echo esc_html($ph_min); ?> - <?php echo esc_html($ph_max); ?></span>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($hardness_min) && !empty($hardness_max)) : ?>
                        <div class="spec-item">
                            <span class="spec-name"><?php esc_html_e('Water Hardness', 'aqualuxe'); ?></span>
                            <span class="spec-value"><?php echo esc_html($hardness_min); ?> - <?php echo esc_html($hardness_max); ?> dGH</span>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($diet)) : ?>
                        <div class="spec-item">
                            <span class="spec-name"><?php esc_html_e('Diet', 'aqualuxe'); ?></span>
                            <span class="spec-value"><?php echo esc_html($diet); ?></span>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($swimming_level)) : 
                        $swimming_labels = array(
                            'top' => __('Top', 'aqualuxe'),
                            'middle' => __('Middle', 'aqualuxe'),
                            'bottom' => __('Bottom', 'aqualuxe'),
                            'all' => __('All Levels', 'aqualuxe'),
                        );
                        $swimming_label = isset($swimming_labels[$swimming_level]) ? $swimming_labels[$swimming_level] : $swimming_level;
                    ?>
                        <div class="spec-item">
                            <span class="spec-name"><?php esc_html_e('Swimming Level', 'aqualuxe'); ?></span>
                            <span class="spec-value"><?php echo esc_html($swimming_label); ?></span>
                        </div>
                    <?php endif; ?>
                </div>
                
                <?php if (!empty($temperature_min) && !empty($temperature_max) || !empty($ph_min) && !empty($ph_max) || !empty($hardness_min) && !empty($hardness_max)) : ?>
                    <div class="water-parameters-chart">
                        <h4><?php esc_html_e('Water Parameters', 'aqualuxe'); ?></h4>
                        
                        <?php if (!empty($temperature_min) && !empty($temperature_max)) : ?>
                            <div class="parameter-range">
                                <span class="parameter-label"><?php esc_html_e('Temperature', 'aqualuxe'); ?></span>
                                <div class="range-bar">
                                    <div class="range-fill" style="left: <?php echo esc_attr(($temperature_min - 65) / 25 * 100); ?>%; width: <?php echo esc_attr(($temperature_max - $temperature_min) / 25 * 100); ?>%;"></div>
                                    <div class="range-scale">
                                        <span>65°F</span>
                                        <span>70°F</span>
                                        <span>75°F</span>
                                        <span>80°F</span>
                                        <span>85°F</span>
                                        <span>90°F</span>
                                    </div>
                                </div>
                                <span class="range-values"><?php echo esc_html($temperature_min); ?>°F - <?php echo esc_html($temperature_max); ?>°F</span>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($ph_min) && !empty($ph_max)) : ?>
                            <div class="parameter-range">
                                <span class="parameter-label"><?php esc_html_e('pH Level', 'aqualuxe'); ?></span>
                                <div class="range-bar">
                                    <div class="range-fill" style="left: <?php echo esc_attr(($ph_min - 5) / 5 * 100); ?>%; width: <?php echo esc_attr(($ph_max - $ph_min) / 5 * 100); ?>%;"></div>
                                    <div class="range-scale">
                                        <span>5.0</span>
                                        <span>6.0</span>
                                        <span>7.0</span>
                                        <span>8.0</span>
                                        <span>9.0</span>
                                        <span>10.0</span>
                                    </div>
                                </div>
                                <span class="range-values"><?php echo esc_html($ph_min); ?> - <?php echo esc_html($ph_max); ?></span>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($hardness_min) && !empty($hardness_max)) : ?>
                            <div class="parameter-range">
                                <span class="parameter-label"><?php esc_html_e('Water Hardness', 'aqualuxe'); ?></span>
                                <div class="range-bar">
                                    <div class="range-fill" style="left: <?php echo esc_attr($hardness_min / 30 * 100); ?>%; width: <?php echo esc_attr(($hardness_max - $hardness_min) / 30 * 100); ?>%;"></div>
                                    <div class="range-scale">
                                        <span>0 dGH</span>
                                        <span>5 dGH</span>
                                        <span>10 dGH</span>
                                        <span>15 dGH</span>
                                        <span>20 dGH</span>
                                        <span>25+ dGH</span>
                                    </div>
                                </div>
                                <span class="range-values"><?php echo esc_html($hardness_min); ?> - <?php echo esc_html($hardness_max); ?> dGH</span>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        
        <?php if (in_array('compatibility', $tabs_to_show)) : 
            $compatible_with = get_post_meta($id, '_compatible_with', true);
            $incompatible_with = get_post_meta($id, '_incompatible_with', true);
            $compatibility_notes = get_post_meta($id, '_compatibility_notes', true);
        ?>
            <div class="fish-compatibility-section">
                <h3><?php esc_html_e('Compatibility Information', 'aqualuxe'); ?></h3>
                
                <div class="compatibility-grid">
                    <div class="compatibility-column compatible-with">
                        <h4><?php esc_html_e('Compatible With', 'aqualuxe'); ?></h4>
                        <?php if (!empty($compatible_with)) : ?>
                            <p><?php echo esc_html($compatible_with); ?></p>
                        <?php else : ?>
                            <p class="no-data"><?php esc_html_e('No specific compatibility information available.', 'aqualuxe'); ?></p>
                        <?php endif; ?>
                    </div>
                    
                    <div class="compatibility-column incompatible-with">
                        <h4><?php esc_html_e('Incompatible With', 'aqualuxe'); ?></h4>
                        <?php if (!empty($incompatible_with)) : ?>
                            <p><?php echo esc_html($incompatible_with); ?></p>
                        <?php else : ?>
                            <p class="no-data"><?php esc_html_e('No specific incompatibility information available.', 'aqualuxe'); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
                
                <?php if (!empty($compatibility_notes)) : ?>
                    <div class="compatibility-notes">
                        <h4><?php esc_html_e('Additional Notes', 'aqualuxe'); ?></h4>
                        <p><?php echo esc_html($compatibility_notes); ?></p>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        
        <?php if (in_array('breeding', $tabs_to_show)) : 
            $breeding_difficulty = get_post_meta($id, '_breeding_difficulty', true);
        ?>
            <div class="fish-breeding-section">
                <h3><?php esc_html_e('Breeding Information', 'aqualuxe'); ?></h3>
                
                <?php if (!empty($breeding_difficulty)) : 
                    $difficulty_labels = array(
                        'easy' => __('Easy', 'aqualuxe'),
                        'moderate' => __('Moderate', 'aqualuxe'),
                        'difficult' => __('Difficult', 'aqualuxe'),
                        'very-difficult' => __('Very Difficult', 'aqualuxe'),
                    );
                    $difficulty_label = isset($difficulty_labels[$breeding_difficulty]) ? $difficulty_labels[$breeding_difficulty] : $breeding_difficulty;
                    
                    $difficulty_percentage = 0;
                    switch ($breeding_difficulty) {
                        case 'easy':
                            $difficulty_percentage = 25;
                            break;
                        case 'moderate':
                            $difficulty_percentage = 50;
                            break;
                        case 'difficult':
                            $difficulty_percentage = 75;
                            break;
                        case 'very-difficult':
                            $difficulty_percentage = 100;
                            break;
                    }
                ?>
                    <div class="breeding-difficulty <?php echo esc_attr($breeding_difficulty); ?>">
                        <h4><?php esc_html_e('Breeding Difficulty', 'aqualuxe'); ?></h4>
                        <div class="difficulty-meter">
                            <div class="difficulty-label"><?php echo esc_html($difficulty_label); ?></div>
                            <div class="difficulty-bar">
                                <div class="difficulty-fill" style="width: <?php echo esc_attr($difficulty_percentage); ?>%;"></div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                
                <?php if (!empty($breeding_tips)) : ?>
                    <div class="breeding-tips">
                        <h4><?php esc_html_e('Breeding Tips', 'aqualuxe'); ?></h4>
                        <p><?php echo esc_html($breeding_tips); ?></p>
                    </div>
                <?php endif; ?>
                
                <?php if (empty($breeding_tips) && empty($breeding_difficulty)) : ?>
                    <p class="no-breeding-info"><?php esc_html_e('Detailed breeding information for this species is currently being updated. Please check back soon.', 'aqualuxe'); ?></p>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
    
    <div class="fish-guide-footer">
        <p class="print-guide">
            <a href="javascript:window.print();" class="print-button">
                <i class="fas fa-print"></i> <?php esc_html_e('Print Care Guide', 'aqualuxe'); ?>
            </a>
        </p>
    </div>
</div>