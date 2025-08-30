<?php
/**
 * Template part for displaying the fish compatibility checker
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

$checker_class = isset($class) ? ' ' . $class : '';
$checker_title = isset($title) ? $title : __('Fish Compatibility Checker', 'aqualuxe');
$fish_limit = isset($limit) ? absint($limit) : 20;

// Get all fish species
$fish_species = get_posts(array(
    'post_type' => 'fish_species',
    'posts_per_page' => $fish_limit,
    'orderby' => 'title',
    'order' => 'ASC',
));

// Get fish categories for filtering
$fish_categories = get_terms(array(
    'taxonomy' => 'fish_category',
    'hide_empty' => true,
));
?>

<div class="aqualuxe-compatibility-checker<?php echo esc_attr($checker_class); ?>">
    <h2 class="checker-title"><?php echo esc_html($checker_title); ?></h2>
    
    <div class="checker-description">
        <p><?php esc_html_e('Use this tool to check the compatibility between different fish species. Select the fish you want to keep together and click "Check Compatibility" to see if they are compatible.', 'aqualuxe'); ?></p>
    </div>
    
    <form class="compatibility-checker-form">
        <div class="fish-selection-controls">
            <div class="fish-search">
                <label for="fish-search-input"><?php esc_html_e('Search Fish:', 'aqualuxe'); ?></label>
                <input type="text" id="fish-search-input" placeholder="<?php esc_attr_e('Type to search...', 'aqualuxe'); ?>">
            </div>
            
            <?php if (!empty($fish_categories)) : ?>
            <div class="fish-category-filter">
                <label for="fish-category-filter"><?php esc_html_e('Filter by Category:', 'aqualuxe'); ?></label>
                <select id="fish-category-filter">
                    <option value=""><?php esc_html_e('All Categories', 'aqualuxe'); ?></option>
                    <?php foreach ($fish_categories as $category) : ?>
                        <option value="<?php echo esc_attr($category->slug); ?>"><?php echo esc_html($category->name); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <?php endif; ?>
            
            <div class="fish-selection-actions">
                <button type="button" id="select-all-fish" class="button-secondary"><?php esc_html_e('Select All', 'aqualuxe'); ?></button>
                <button type="button" id="deselect-all-fish" class="button-secondary"><?php esc_html_e('Deselect All', 'aqualuxe'); ?></button>
            </div>
        </div>
        
        <div class="fish-selection-container">
            <?php if (!empty($fish_species)) : ?>
                <div class="fish-selection-list">
                    <?php foreach ($fish_species as $fish) : 
                        // Get fish category
                        $fish_cats = get_the_terms($fish->ID, 'fish_category');
                        $fish_cat = !empty($fish_cats) ? $fish_cats[0]->slug : '';
                        
                        // Get fish temperament
                        $temperament = get_post_meta($fish->ID, '_temperament', true);
                        $temperament_class = !empty($temperament) ? ' temperament-' . $temperament : '';
                    ?>
                        <div class="fish-selection-item<?php echo esc_attr($temperament_class); ?>" data-category="<?php echo esc_attr($fish_cat); ?>">
                            <label>
                                <input type="checkbox" name="fish_species[]" value="<?php echo esc_attr($fish->ID); ?>">
                                <?php echo esc_html($fish->post_title); ?>
                                
                                <?php if (!empty($temperament)) : 
                                    $temperament_labels = array(
                                        'peaceful' => __('Peaceful', 'aqualuxe'),
                                        'semi-aggressive' => __('Semi-Aggressive', 'aqualuxe'),
                                        'aggressive' => __('Aggressive', 'aqualuxe'),
                                    );
                                    $temperament_label = isset($temperament_labels[$temperament]) ? $temperament_labels[$temperament] : $temperament;
                                ?>
                                    <span class="fish-temperament <?php echo esc_attr($temperament); ?>"><?php echo esc_html($temperament_label); ?></span>
                                <?php endif; ?>
                            </label>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else : ?>
                <p class="no-fish-found"><?php esc_html_e('No fish species found.', 'aqualuxe'); ?></p>
            <?php endif; ?>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="button check-compatibility-button"><?php esc_html_e('Check Compatibility', 'aqualuxe'); ?></button>
        </div>
    </form>
    
    <div class="compatibility-results" style="display: none;">
        <h3><?php esc_html_e('Compatibility Results', 'aqualuxe'); ?></h3>
        <div class="results-content"></div>
    </div>
    
    <div class="compatibility-notes">
        <h3><?php esc_html_e('Understanding Compatibility', 'aqualuxe'); ?></h3>
        <ul>
            <li><span class="compatibility-indicator compatible"></span> <?php esc_html_e('Compatible: These fish can generally live together peacefully.', 'aqualuxe'); ?></li>
            <li><span class="compatibility-indicator caution"></span> <?php esc_html_e('Caution: These fish may be compatible under certain conditions or with careful monitoring.', 'aqualuxe'); ?></li>
            <li><span class="compatibility-indicator incompatible"></span> <?php esc_html_e('Incompatible: These fish should not be kept together.', 'aqualuxe'); ?></li>
        </ul>
        <p><?php esc_html_e('Note: Compatibility can vary based on individual fish temperament, tank size, aquascaping, and other factors. Always research specific species before adding them to your aquarium.', 'aqualuxe'); ?></p>
    </div>
</div>