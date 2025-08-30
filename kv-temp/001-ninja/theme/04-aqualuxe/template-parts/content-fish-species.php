<?php
/**
 * Template part for displaying fish species content
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('fish-species-single'); ?>>
    <div class="fish-species-header">
        <div class="fish-species-image">
            <?php 
            if (has_post_thumbnail()) {
                the_post_thumbnail('large');
            } else {
                echo '<img src="' . esc_url(AQUALUXE_ASSETS_URI . '/images/placeholder-fish.png') . '" alt="' . esc_attr(get_the_title()) . '" />';
            }
            ?>
        </div>
        
        <div class="fish-species-title-area">
            <header class="entry-header">
                <?php the_title('<h1 class="entry-title">', '</h1>'); ?>
                
                <?php 
                $scientific_name = get_post_meta(get_the_ID(), '_scientific_name', true);
                if (!empty($scientific_name)) {
                    echo '<h2 class="scientific-name"><em>' . esc_html($scientific_name) . '</em></h2>';
                }
                ?>
                
                <div class="fish-taxonomy">
                    <?php
                    $categories = get_the_terms(get_the_ID(), 'fish_category');
                    if (!empty($categories) && !is_wp_error($categories)) {
                        echo '<span class="fish-category">';
                        echo '<i class="fas fa-water"></i> ';
                        
                        $category_links = array();
                        foreach ($categories as $category) {
                            $category_links[] = '<a href="' . esc_url(get_term_link($category)) . '">' . esc_html($category->name) . '</a>';
                        }
                        echo implode(', ', $category_links);
                        
                        echo '</span>';
                    }
                    
                    $families = get_the_terms(get_the_ID(), 'fish_family');
                    if (!empty($families) && !is_wp_error($families)) {
                        echo '<span class="fish-family">';
                        echo '<i class="fas fa-sitemap"></i> ';
                        
                        $family_links = array();
                        foreach ($families as $family) {
                            $family_links[] = '<a href="' . esc_url(get_term_link($family)) . '">' . esc_html($family->name) . '</a>';
                        }
                        echo implode(', ', $family_links);
                        
                        echo '</span>';
                    }
                    
                    $origins = get_the_terms(get_the_ID(), 'fish_origin');
                    if (!empty($origins) && !is_wp_error($origins)) {
                        echo '<span class="fish-origin">';
                        echo '<i class="fas fa-globe-americas"></i> ';
                        
                        $origin_links = array();
                        foreach ($origins as $origin) {
                            $origin_links[] = '<a href="' . esc_url(get_term_link($origin)) . '">' . esc_html($origin->name) . '</a>';
                        }
                        echo implode(', ', $origin_links);
                        
                        echo '</span>';
                    }
                    
                    $care_levels = get_the_terms(get_the_ID(), 'care_level');
                    if (!empty($care_levels) && !is_wp_error($care_levels)) {
                        echo '<span class="fish-care-level">';
                        echo '<i class="fas fa-hand-holding-heart"></i> ';
                        
                        $care_level_links = array();
                        foreach ($care_levels as $care_level) {
                            $care_level_links[] = '<a href="' . esc_url(get_term_link($care_level)) . '">' . esc_html($care_level->name) . '</a>';
                        }
                        echo implode(', ', $care_level_links);
                        
                        echo '</span>';
                    }
                    ?>
                </div>
            </header>
            
            <div class="fish-species-key-specs">
                <?php
                $temperament = get_post_meta(get_the_ID(), '_temperament', true);
                $adult_size = get_post_meta(get_the_ID(), '_adult_size', true);
                $lifespan = get_post_meta(get_the_ID(), '_lifespan', true);
                $min_tank_size = get_post_meta(get_the_ID(), '_min_tank_size', true);
                
                if (!empty($temperament)) {
                    $temperament_labels = array(
                        'peaceful' => __('Peaceful', 'aqualuxe'),
                        'semi-aggressive' => __('Semi-Aggressive', 'aqualuxe'),
                        'aggressive' => __('Aggressive', 'aqualuxe'),
                    );
                    $temperament_label = isset($temperament_labels[$temperament]) ? $temperament_labels[$temperament] : $temperament;
                    
                    echo '<div class="key-spec temperament">';
                    echo '<span class="spec-label">' . esc_html__('Temperament', 'aqualuxe') . '</span>';
                    echo '<span class="spec-value ' . esc_attr($temperament) . '">' . esc_html($temperament_label) . '</span>';
                    echo '</div>';
                }
                
                if (!empty($adult_size)) {
                    echo '<div class="key-spec size">';
                    echo '<span class="spec-label">' . esc_html__('Adult Size', 'aqualuxe') . '</span>';
                    echo '<span class="spec-value">' . esc_html($adult_size) . '</span>';
                    echo '</div>';
                }
                
                if (!empty($lifespan)) {
                    echo '<div class="key-spec lifespan">';
                    echo '<span class="spec-label">' . esc_html__('Lifespan', 'aqualuxe') . '</span>';
                    echo '<span class="spec-value">' . esc_html($lifespan) . '</span>';
                    echo '</div>';
                }
                
                if (!empty($min_tank_size)) {
                    echo '<div class="key-spec tank-size">';
                    echo '<span class="spec-label">' . esc_html__('Min Tank Size', 'aqualuxe') . '</span>';
                    echo '<span class="spec-value">' . esc_html($min_tank_size) . ' ' . esc_html__('gallons', 'aqualuxe') . '</span>';
                    echo '</div>';
                }
                ?>
            </div>
            
            <?php
            // Check if this fish is available as a product
            $args = array(
                'post_type' => 'product',
                'posts_per_page' => 1,
                'meta_query' => array(
                    array(
                        'key' => '_fish_species_id',
                        'value' => get_the_ID(),
                        'compare' => '=',
                    ),
                ),
            );
            
            $products = new WP_Query($args);
            
            if ($products->have_posts()) {
                while ($products->have_posts()) {
                    $products->the_post();
                    global $product;
                    
                    echo '<div class="fish-product-link">';
                    echo '<a href="' . esc_url(get_permalink()) . '" class="button buy-fish-button">';
                    echo '<i class="fas fa-shopping-cart"></i> ' . esc_html__('Buy This Fish', 'aqualuxe');
                    echo '</a>';
                    
                    if ($product->is_in_stock()) {
                        echo '<span class="in-stock">' . esc_html__('In Stock', 'aqualuxe') . '</span>';
                    } else {
                        echo '<span class="out-of-stock">' . esc_html__('Out of Stock', 'aqualuxe') . '</span>';
                    }
                    
                    echo '<span class="fish-price">' . $product->get_price_html() . '</span>';
                    echo '</div>';
                }
                wp_reset_postdata();
            }
            ?>
        </div>
    </div>
    
    <div class="fish-species-tabs">
        <ul class="tabs-nav">
            <li class="active" data-tab="overview"><?php esc_html_e('Overview', 'aqualuxe'); ?></li>
            <li data-tab="specs"><?php esc_html_e('Specifications', 'aqualuxe'); ?></li>
            <li data-tab="care"><?php esc_html_e('Care Guide', 'aqualuxe'); ?></li>
            <li data-tab="compatibility"><?php esc_html_e('Compatibility', 'aqualuxe'); ?></li>
            <li data-tab="breeding"><?php esc_html_e('Breeding', 'aqualuxe'); ?></li>
        </ul>
        
        <div class="tabs-content">
            <div class="tab-pane active" id="overview">
                <div class="entry-content">
                    <?php the_content(); ?>
                </div>
            </div>
            
            <div class="tab-pane" id="specs">
                <div class="fish-specifications">
                    <h3><?php esc_html_e('Fish Specifications', 'aqualuxe'); ?></h3>
                    
                    <div class="specs-grid">
                        <?php
                        $temperature_min = get_post_meta(get_the_ID(), '_temperature_min', true);
                        $temperature_max = get_post_meta(get_the_ID(), '_temperature_max', true);
                        $ph_min = get_post_meta(get_the_ID(), '_ph_min', true);
                        $ph_max = get_post_meta(get_the_ID(), '_ph_max', true);
                        $hardness_min = get_post_meta(get_the_ID(), '_hardness_min', true);
                        $hardness_max = get_post_meta(get_the_ID(), '_hardness_max', true);
                        $diet = get_post_meta(get_the_ID(), '_diet', true);
                        $swimming_level = get_post_meta(get_the_ID(), '_swimming_level', true);
                        $breeding_difficulty = get_post_meta(get_the_ID(), '_breeding_difficulty', true);
                        
                        if (!empty($temperature_min) && !empty($temperature_max)) {
                            echo '<div class="spec-item">';
                            echo '<span class="spec-name">' . esc_html__('Temperature Range', 'aqualuxe') . '</span>';
                            echo '<span class="spec-value">' . esc_html($temperature_min) . '°F - ' . esc_html($temperature_max) . '°F</span>';
                            echo '</div>';
                        }
                        
                        if (!empty($ph_min) && !empty($ph_max)) {
                            echo '<div class="spec-item">';
                            echo '<span class="spec-name">' . esc_html__('pH Range', 'aqualuxe') . '</span>';
                            echo '<span class="spec-value">' . esc_html($ph_min) . ' - ' . esc_html($ph_max) . '</span>';
                            echo '</div>';
                        }
                        
                        if (!empty($hardness_min) && !empty($hardness_max)) {
                            echo '<div class="spec-item">';
                            echo '<span class="spec-name">' . esc_html__('Water Hardness', 'aqualuxe') . '</span>';
                            echo '<span class="spec-value">' . esc_html($hardness_min) . ' - ' . esc_html($hardness_max) . ' dGH</span>';
                            echo '</div>';
                        }
                        
                        if (!empty($diet)) {
                            echo '<div class="spec-item">';
                            echo '<span class="spec-name">' . esc_html__('Diet', 'aqualuxe') . '</span>';
                            echo '<span class="spec-value">' . esc_html($diet) . '</span>';
                            echo '</div>';
                        }
                        
                        if (!empty($swimming_level)) {
                            $swimming_labels = array(
                                'top' => __('Top', 'aqualuxe'),
                                'middle' => __('Middle', 'aqualuxe'),
                                'bottom' => __('Bottom', 'aqualuxe'),
                                'all' => __('All Levels', 'aqualuxe'),
                            );
                            $swimming_label = isset($swimming_labels[$swimming_level]) ? $swimming_labels[$swimming_level] : $swimming_level;
                            
                            echo '<div class="spec-item">';
                            echo '<span class="spec-name">' . esc_html__('Swimming Level', 'aqualuxe') . '</span>';
                            echo '<span class="spec-value">' . esc_html($swimming_label) . '</span>';
                            echo '</div>';
                        }
                        
                        if (!empty($breeding_difficulty)) {
                            $difficulty_labels = array(
                                'easy' => __('Easy', 'aqualuxe'),
                                'moderate' => __('Moderate', 'aqualuxe'),
                                'difficult' => __('Difficult', 'aqualuxe'),
                                'very-difficult' => __('Very Difficult', 'aqualuxe'),
                            );
                            $difficulty_label = isset($difficulty_labels[$breeding_difficulty]) ? $difficulty_labels[$breeding_difficulty] : $breeding_difficulty;
                            
                            echo '<div class="spec-item">';
                            echo '<span class="spec-name">' . esc_html__('Breeding Difficulty', 'aqualuxe') . '</span>';
                            echo '<span class="spec-value">' . esc_html($difficulty_label) . '</span>';
                            echo '</div>';
                        }
                        ?>
                    </div>
                    
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
                </div>
            </div>
            
            <div class="tab-pane" id="care">
                <div class="fish-care-guide">
                    <h3><?php esc_html_e('Care Guide', 'aqualuxe'); ?></h3>
                    
                    <?php
                    $care_instructions = get_post_meta(get_the_ID(), '_care_instructions', true);
                    $feeding_guide = get_post_meta(get_the_ID(), '_feeding_guide', true);
                    $tank_setup = get_post_meta(get_the_ID(), '_tank_setup', true);
                    $common_diseases = get_post_meta(get_the_ID(), '_common_diseases', true);
                    
                    if (!empty($care_instructions)) {
                        echo '<div class="care-section care-instructions">';
                        echo '<h4>' . esc_html__('General Care Instructions', 'aqualuxe') . '</h4>';
                        echo wp_kses_post($care_instructions);
                        echo '</div>';
                    }
                    
                    if (!empty($feeding_guide)) {
                        echo '<div class="care-section feeding-guide">';
                        echo '<h4>' . esc_html__('Feeding Guide', 'aqualuxe') . '</h4>';
                        echo '<p>' . esc_html($feeding_guide) . '</p>';
                        echo '</div>';
                    }
                    
                    if (!empty($tank_setup)) {
                        echo '<div class="care-section tank-setup">';
                        echo '<h4>' . esc_html__('Recommended Tank Setup', 'aqualuxe') . '</h4>';
                        echo '<p>' . esc_html($tank_setup) . '</p>';
                        echo '</div>';
                    }
                    
                    if (!empty($common_diseases)) {
                        echo '<div class="care-section common-diseases">';
                        echo '<h4>' . esc_html__('Common Diseases & Prevention', 'aqualuxe') . '</h4>';
                        echo '<p>' . esc_html($common_diseases) . '</p>';
                        echo '</div>';
                    }
                    
                    // If no care information is available
                    if (empty($care_instructions) && empty($feeding_guide) && empty($tank_setup) && empty($common_diseases)) {
                        echo '<p class="no-care-info">' . esc_html__('Detailed care information for this species is currently being updated. Please check back soon.', 'aqualuxe') . '</p>';
                    }
                    ?>
                    
                    <div class="care-tools">
                        <h4><?php esc_html_e('Helpful Tools', 'aqualuxe'); ?></h4>
                        <div class="tool-buttons">
                            <a href="<?php echo esc_url(home_url('/water-parameter-calculator/')); ?>" class="button tool-button">
                                <i class="fas fa-calculator"></i> <?php esc_html_e('Water Parameter Calculator', 'aqualuxe'); ?>
                            </a>
                            <a href="<?php echo esc_url(home_url('/fish-compatibility-checker/')); ?>" class="button tool-button">
                                <i class="fas fa-check-circle"></i> <?php esc_html_e('Compatibility Checker', 'aqualuxe'); ?>
                            </a>
                            <a href="<?php echo esc_url(home_url('/tank-volume-calculator/')); ?>" class="button tool-button">
                                <i class="fas fa-tachometer-alt"></i> <?php esc_html_e('Tank Volume Calculator', 'aqualuxe'); ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="tab-pane" id="compatibility">
                <div class="fish-compatibility">
                    <h3><?php esc_html_e('Compatibility Information', 'aqualuxe'); ?></h3>
                    
                    <?php
                    $compatible_with = get_post_meta(get_the_ID(), '_compatible_with', true);
                    $incompatible_with = get_post_meta(get_the_ID(), '_incompatible_with', true);
                    $compatibility_notes = get_post_meta(get_the_ID(), '_compatibility_notes', true);
                    
                    echo '<div class="compatibility-grid">';
                    
                    echo '<div class="compatibility-column compatible-with">';
                    echo '<h4>' . esc_html__('Compatible With', 'aqualuxe') . '</h4>';
                    if (!empty($compatible_with)) {
                        echo '<p>' . esc_html($compatible_with) . '</p>';
                    } else {
                        echo '<p class="no-data">' . esc_html__('No specific compatibility information available.', 'aqualuxe') . '</p>';
                    }
                    echo '</div>';
                    
                    echo '<div class="compatibility-column incompatible-with">';
                    echo '<h4>' . esc_html__('Incompatible With', 'aqualuxe') . '</h4>';
                    if (!empty($incompatible_with)) {
                        echo '<p>' . esc_html($incompatible_with) . '</p>';
                    } else {
                        echo '<p class="no-data">' . esc_html__('No specific incompatibility information available.', 'aqualuxe') . '</p>';
                    }
                    echo '</div>';
                    
                    echo '</div>';
                    
                    if (!empty($compatibility_notes)) {
                        echo '<div class="compatibility-notes">';
                        echo '<h4>' . esc_html__('Additional Notes', 'aqualuxe') . '</h4>';
                        echo '<p>' . esc_html($compatibility_notes) . '</p>';
                        echo '</div>';
                    }
                    
                    // Compatibility checker shortcode
                    echo do_shortcode('[fish_compatibility_checker title="Check Compatibility with Other Fish" limit="10"]');
                    ?>
                </div>
            </div>
            
            <div class="tab-pane" id="breeding">
                <div class="fish-breeding">
                    <h3><?php esc_html_e('Breeding Information', 'aqualuxe'); ?></h3>
                    
                    <?php
                    $breeding_tips = get_post_meta(get_the_ID(), '_breeding_tips', true);
                    $breeding_difficulty = get_post_meta(get_the_ID(), '_breeding_difficulty', true);
                    
                    if (!empty($breeding_difficulty)) {
                        $difficulty_labels = array(
                            'easy' => __('Easy', 'aqualuxe'),
                            'moderate' => __('Moderate', 'aqualuxe'),
                            'difficult' => __('Difficult', 'aqualuxe'),
                            'very-difficult' => __('Very Difficult', 'aqualuxe'),
                        );
                        $difficulty_label = isset($difficulty_labels[$breeding_difficulty]) ? $difficulty_labels[$breeding_difficulty] : $breeding_difficulty;
                        
                        echo '<div class="breeding-difficulty ' . esc_attr($breeding_difficulty) . '">';
                        echo '<h4>' . esc_html__('Breeding Difficulty', 'aqualuxe') . '</h4>';
                        echo '<div class="difficulty-meter">';
                        echo '<div class="difficulty-label">' . esc_html($difficulty_label) . '</div>';
                        echo '<div class="difficulty-bar">';
                        
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
                        
                        echo '<div class="difficulty-fill" style="width: ' . esc_attr($difficulty_percentage) . '%;"></div>';
                        echo '</div>';
                        echo '</div>';
                    }
                    
                    if (!empty($breeding_tips)) {
                        echo '<div class="breeding-tips">';
                        echo '<h4>' . esc_html__('Breeding Tips', 'aqualuxe') . '</h4>';
                        echo '<p>' . esc_html($breeding_tips) . '</p>';
                        echo '</div>';
                    }
                    
                    // If no breeding information is available
                    if (empty($breeding_tips) && empty($breeding_difficulty)) {
                        echo '<p class="no-breeding-info">' . esc_html__('Detailed breeding information for this species is currently being updated. Please check back soon.', 'aqualuxe') . '</p>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    
    <div class="fish-species-related">
        <h3><?php esc_html_e('Related Fish Species', 'aqualuxe'); ?></h3>
        
        <?php
        // Get fish from the same family or category
        $terms = get_the_terms(get_the_ID(), 'fish_family');
        if (empty($terms) || is_wp_error($terms)) {
            $terms = get_the_terms(get_the_ID(), 'fish_category');
        }
        
        if (!empty($terms) && !is_wp_error($terms)) {
            $term_ids = wp_list_pluck($terms, 'term_id');
            
            $args = array(
                'post_type' => 'fish_species',
                'posts_per_page' => 4,
                'post__not_in' => array(get_the_ID()),
                'tax_query' => array(
                    array(
                        'taxonomy' => !empty(get_the_terms(get_the_ID(), 'fish_family')) ? 'fish_family' : 'fish_category',
                        'field' => 'term_id',
                        'terms' => $term_ids,
                    ),
                ),
            );
            
            $related_fish = new WP_Query($args);
            
            if ($related_fish->have_posts()) {
                echo '<div class="related-fish-grid">';
                
                while ($related_fish->have_posts()) {
                    $related_fish->the_post();
                    
                    echo '<div class="related-fish-item">';
                    echo '<a href="' . esc_url(get_permalink()) . '" class="related-fish-link">';
                    
                    if (has_post_thumbnail()) {
                        echo '<div class="related-fish-image">';
                        the_post_thumbnail('medium');
                        echo '</div>';
                    } else {
                        echo '<div class="related-fish-image">';
                        echo '<img src="' . esc_url(AQUALUXE_ASSETS_URI . '/images/placeholder-fish.png') . '" alt="' . esc_attr(get_the_title()) . '" />';
                        echo '</div>';
                    }
                    
                    echo '<h4 class="related-fish-title">' . get_the_title() . '</h4>';
                    
                    $scientific_name = get_post_meta(get_the_ID(), '_scientific_name', true);
                    if (!empty($scientific_name)) {
                        echo '<p class="related-fish-scientific-name"><em>' . esc_html($scientific_name) . '</em></p>';
                    }
                    
                    echo '</a>';
                    echo '</div>';
                }
                
                echo '</div>';
            } else {
                echo '<p class="no-related-fish">' . esc_html__('No related fish species found.', 'aqualuxe') . '</p>';
            }
            
            wp_reset_postdata();
        } else {
            echo '<p class="no-related-fish">' . esc_html__('No related fish species found.', 'aqualuxe') . '</p>';
        }
        ?>
    </div>
    
    <div class="fish-species-footer">
        <div class="fish-species-share">
            <h4><?php esc_html_e('Share This Fish', 'aqualuxe'); ?></h4>
            <div class="social-share-buttons">
                <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo esc_url(get_permalink()); ?>" target="_blank" rel="noopener noreferrer" class="share-button facebook">
                    <i class="fab fa-facebook-f"></i> <?php esc_html_e('Facebook', 'aqualuxe'); ?>
                </a>
                <a href="https://twitter.com/intent/tweet?url=<?php echo esc_url(get_permalink()); ?>&text=<?php echo esc_attr(get_the_title()); ?>" target="_blank" rel="noopener noreferrer" class="share-button twitter">
                    <i class="fab fa-twitter"></i> <?php esc_html_e('Twitter', 'aqualuxe'); ?>
                </a>
                <a href="https://pinterest.com/pin/create/button/?url=<?php echo esc_url(get_permalink()); ?>&media=<?php echo esc_url(get_the_post_thumbnail_url(get_the_ID(), 'large')); ?>&description=<?php echo esc_attr(get_the_title()); ?>" target="_blank" rel="noopener noreferrer" class="share-button pinterest">
                    <i class="fab fa-pinterest-p"></i> <?php esc_html_e('Pinterest', 'aqualuxe'); ?>
                </a>
                <a href="mailto:?subject=<?php echo esc_attr(get_the_title()); ?>&body=<?php echo esc_url(get_permalink()); ?>" class="share-button email">
                    <i class="fas fa-envelope"></i> <?php esc_html_e('Email', 'aqualuxe'); ?>
                </a>
            </div>
        </div>
        
        <div class="fish-species-meta">
            <?php
            // Display post meta information
            echo '<div class="entry-meta">';
            
            // Last updated date
            $u_time = get_the_time('U');
            $u_modified_time = get_the_modified_time('U');
            
            if ($u_modified_time > $u_time) {
                echo '<span class="updated-date">';
                echo esc_html__('Last Updated: ', 'aqualuxe') . get_the_modified_date();
                echo '</span>';
            }
            
            echo '</div>';
            ?>
        </div>
    </div>
</article>