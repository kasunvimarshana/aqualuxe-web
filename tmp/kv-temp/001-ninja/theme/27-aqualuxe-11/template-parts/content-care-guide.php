<?php
/**
 * Template part for displaying care guides
 *
 * @package AquaLuxe
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('care-guide-article'); ?>>
    <div class="care-guide-container">
        <header class="care-guide-header">
            <?php if (has_post_thumbnail()) : ?>
                <div class="care-guide-featured-image">
                    <?php the_post_thumbnail('large', array('class' => 'care-guide-image')); ?>
                </div>
            <?php endif; ?>
            
            <div class="care-guide-header-content">
                <h1 class="care-guide-title"><?php the_title(); ?></h1>
                
                <div class="care-guide-meta">
                    <?php
                    // Display fish species
                    $species_terms = get_the_terms(get_the_ID(), 'fish_species');
                    if ($species_terms && !is_wp_error($species_terms)) {
                        echo '<div class="care-guide-taxonomy fish-species">';
                        echo '<span class="meta-label">' . __('Species:', 'aqualuxe') . '</span> ';
                        $species_links = array();
                        foreach ($species_terms as $term) {
                            $species_links[] = '<a href="' . esc_url(get_term_link($term)) . '">' . esc_html($term->name) . '</a>';
                        }
                        echo implode(', ', $species_links);
                        echo '</div>';
                    }
                    
                    // Display care categories
                    $category_terms = get_the_terms(get_the_ID(), 'care_category');
                    if ($category_terms && !is_wp_error($category_terms)) {
                        echo '<div class="care-guide-taxonomy care-category">';
                        echo '<span class="meta-label">' . __('Care Category:', 'aqualuxe') . '</span> ';
                        $category_links = array();
                        foreach ($category_terms as $term) {
                            $category_links[] = '<a href="' . esc_url(get_term_link($term)) . '">' . esc_html($term->name) . '</a>';
                        }
                        echo implode(', ', $category_links);
                        echo '</div>';
                    }
                    
                    // Display difficulty level
                    $difficulty_terms = get_the_terms(get_the_ID(), 'difficulty_level');
                    if ($difficulty_terms && !is_wp_error($difficulty_terms)) {
                        echo '<div class="care-guide-taxonomy difficulty-level">';
                        echo '<span class="meta-label">' . __('Difficulty:', 'aqualuxe') . '</span> ';
                        $difficulty_links = array();
                        foreach ($difficulty_terms as $term) {
                            $difficulty_links[] = '<a href="' . esc_url(get_term_link($term)) . '">' . esc_html($term->name) . '</a>';
                        }
                        echo implode(', ', $difficulty_links);
                        echo '</div>';
                    }
                    ?>
                </div>
            </div>
        </header>

        <div class="care-guide-details">
            <div class="care-guide-specs">
                <h3><?php _e('Fish Specifications', 'aqualuxe'); ?></h3>
                <div class="specs-grid">
                    <?php
                    // Get and display meta data
                    $tank_size = get_post_meta(get_the_ID(), '_tank_size', true);
                    $water_temp = get_post_meta(get_the_ID(), '_water_temp', true);
                    $ph_level = get_post_meta(get_the_ID(), '_ph_level', true);
                    $lifespan = get_post_meta(get_the_ID(), '_lifespan', true);
                    $diet = get_post_meta(get_the_ID(), '_diet', true);
                    $maintenance_level = get_post_meta(get_the_ID(), '_maintenance_level', true);
                    
                    // Display specs if they exist
                    if (!empty($tank_size)) {
                        echo '<div class="spec-item">';
                        echo '<span class="spec-label">' . __('Tank Size:', 'aqualuxe') . '</span>';
                        echo '<span class="spec-value">' . esc_html($tank_size) . ' ' . __('gallons', 'aqualuxe') . '</span>';
                        echo '</div>';
                    }
                    
                    if (!empty($water_temp)) {
                        echo '<div class="spec-item">';
                        echo '<span class="spec-label">' . __('Water Temperature:', 'aqualuxe') . '</span>';
                        echo '<span class="spec-value">' . esc_html($water_temp) . ' °F</span>';
                        echo '</div>';
                    }
                    
                    if (!empty($ph_level)) {
                        echo '<div class="spec-item">';
                        echo '<span class="spec-label">' . __('pH Level:', 'aqualuxe') . '</span>';
                        echo '<span class="spec-value">' . esc_html($ph_level) . '</span>';
                        echo '</div>';
                    }
                    
                    if (!empty($lifespan)) {
                        echo '<div class="spec-item">';
                        echo '<span class="spec-label">' . __('Average Lifespan:', 'aqualuxe') . '</span>';
                        echo '<span class="spec-value">' . esc_html($lifespan) . ' ' . __('years', 'aqualuxe') . '</span>';
                        echo '</div>';
                    }
                    
                    if (!empty($diet)) {
                        echo '<div class="spec-item">';
                        echo '<span class="spec-label">' . __('Diet:', 'aqualuxe') . '</span>';
                        echo '<span class="spec-value">' . esc_html($diet) . '</span>';
                        echo '</div>';
                    }
                    
                    if (!empty($maintenance_level)) {
                        echo '<div class="spec-item">';
                        echo '<span class="spec-label">' . __('Maintenance:', 'aqualuxe') . '</span>';
                        echo '<span class="spec-value maintenance-' . esc_attr($maintenance_level) . '">';
                        
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
                        
                        echo '</span>';
                        echo '</div>';
                    }
                    ?>
                </div>
            </div>
            
            <div class="care-guide-compatibility">
                <h3><?php _e('Compatibility', 'aqualuxe'); ?></h3>
                <?php
                $compatible_with = get_post_meta(get_the_ID(), '_compatible_with', true);
                $not_compatible_with = get_post_meta(get_the_ID(), '_not_compatible_with', true);
                
                if (!empty($compatible_with)) {
                    echo '<div class="compatibility-section compatible">';
                    echo '<h4>' . __('Compatible With:', 'aqualuxe') . '</h4>';
                    echo '<p>' . esc_html($compatible_with) . '</p>';
                    echo '</div>';
                }
                
                if (!empty($not_compatible_with)) {
                    echo '<div class="compatibility-section not-compatible">';
                    echo '<h4>' . __('Not Compatible With:', 'aqualuxe') . '</h4>';
                    echo '<p>' . esc_html($not_compatible_with) . '</p>';
                    echo '</div>';
                }
                ?>
            </div>
        </div>

        <div class="care-guide-content">
            <div class="care-guide-tabs">
                <ul class="tabs-nav">
                    <li class="tab-active" data-tab="overview"><?php _e('Overview', 'aqualuxe'); ?></li>
                    <li data-tab="care-instructions"><?php _e('Care Instructions', 'aqualuxe'); ?></li>
                    <li data-tab="feeding"><?php _e('Feeding', 'aqualuxe'); ?></li>
                    <li data-tab="tank-setup"><?php _e('Tank Setup', 'aqualuxe'); ?></li>
                    <li data-tab="common-issues"><?php _e('Common Issues', 'aqualuxe'); ?></li>
                </ul>
                
                <div class="tabs-content">
                    <div id="tab-overview" class="tab-content tab-active">
                        <div class="tab-content-inner">
                            <?php the_content(); ?>
                        </div>
                    </div>
                    
                    <div id="tab-care-instructions" class="tab-content">
                        <div class="tab-content-inner">
                            <?php 
                            $care_instructions = get_post_meta(get_the_ID(), '_care_instructions', true);
                            echo !empty($care_instructions) ? wpautop($care_instructions) : __('Care instructions not available.', 'aqualuxe');
                            ?>
                        </div>
                    </div>
                    
                    <div id="tab-feeding" class="tab-content">
                        <div class="tab-content-inner">
                            <?php 
                            $feeding = get_post_meta(get_the_ID(), '_feeding', true);
                            echo !empty($feeding) ? wpautop($feeding) : __('Feeding information not available.', 'aqualuxe');
                            ?>
                        </div>
                    </div>
                    
                    <div id="tab-tank-setup" class="tab-content">
                        <div class="tab-content-inner">
                            <?php 
                            $tank_setup = get_post_meta(get_the_ID(), '_tank_setup', true);
                            echo !empty($tank_setup) ? wpautop($tank_setup) : __('Tank setup information not available.', 'aqualuxe');
                            ?>
                        </div>
                    </div>
                    
                    <div id="tab-common-issues" class="tab-content">
                        <div class="tab-content-inner">
                            <?php 
                            $common_issues = get_post_meta(get_the_ID(), '_common_issues', true);
                            echo !empty($common_issues) ? wpautop($common_issues) : __('Common issues information not available.', 'aqualuxe');
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="care-guide-related">
            <h3><?php _e('Related Care Guides', 'aqualuxe'); ?></h3>
            <?php
            // Get current fish species
            $species_terms = get_the_terms(get_the_ID(), 'fish_species');
            $species_ids = array();
            
            if ($species_terms && !is_wp_error($species_terms)) {
                foreach ($species_terms as $term) {
                    $species_ids[] = $term->term_id;
                }
            }
            
            // Query related care guides
            $related_args = array(
                'post_type' => 'care_guide',
                'posts_per_page' => 3,
                'post__not_in' => array(get_the_ID()),
                'tax_query' => array(
                    array(
                        'taxonomy' => 'fish_species',
                        'field' => 'term_id',
                        'terms' => $species_ids,
                    ),
                ),
            );
            
            $related_query = new WP_Query($related_args);
            
            if ($related_query->have_posts()) {
                echo '<div class="related-guides-grid">';
                while ($related_query->have_posts()) {
                    $related_query->the_post();
                    ?>
                    <div class="related-guide-item">
                        <a href="<?php the_permalink(); ?>" class="related-guide-link">
                            <?php if (has_post_thumbnail()) : ?>
                                <div class="related-guide-image">
                                    <?php the_post_thumbnail('medium'); ?>
                                </div>
                            <?php endif; ?>
                            <h4 class="related-guide-title"><?php the_title(); ?></h4>
                        </a>
                    </div>
                    <?php
                }
                echo '</div>';
            } else {
                echo '<p>' . __('No related care guides found.', 'aqualuxe') . '</p>';
            }
            
            wp_reset_postdata();
            ?>
        </div>
    </div>
</article>