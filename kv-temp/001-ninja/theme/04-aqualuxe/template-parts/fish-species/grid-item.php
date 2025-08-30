<?php
/**
 * Template part for displaying fish species grid item
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Get fish data
$scientific_name = get_post_meta(get_the_ID(), '_scientific_name', true);
$temperament = get_post_meta(get_the_ID(), '_temperament', true);
$care_level_terms = get_the_terms(get_the_ID(), 'care_level');
$care_level = !empty($care_level_terms) ? $care_level_terms[0]->name : '';
?>

<div class="fish-species-item">
    <a href="<?php the_permalink(); ?>" class="fish-species-link">
        <div class="fish-species-image">
            <?php if (has_post_thumbnail()) : ?>
                <?php the_post_thumbnail('medium', array('class' => 'lazy', 'data-src' => get_the_post_thumbnail_url(get_the_ID(), 'medium'))); ?>
            <?php else : ?>
                <img src="<?php echo esc_url(AQUALUXE_ASSETS_URI . '/images/placeholder-fish.png'); ?>" alt="<?php echo esc_attr(get_the_title()); ?>" class="lazy" data-src="<?php echo esc_url(AQUALUXE_ASSETS_URI . '/images/placeholder-fish.png'); ?>" />
            <?php endif; ?>
        </div>
        
        <div class="fish-species-content">
            <h3 class="fish-species-title"><?php the_title(); ?></h3>
            
            <?php if (!empty($scientific_name)) : ?>
                <p class="fish-scientific-name"><em><?php echo esc_html($scientific_name); ?></em></p>
            <?php endif; ?>
            
            <div class="fish-species-meta">
                <?php if (!empty($care_level)) : ?>
                    <span class="fish-care-level"><?php esc_html_e('Care Level:', 'aqualuxe'); ?> <?php echo esc_html($care_level); ?></span>
                <?php endif; ?>
                
                <?php if (!empty($temperament)) : 
                    $temperament_labels = array(
                        'peaceful' => __('Peaceful', 'aqualuxe'),
                        'semi-aggressive' => __('Semi-Aggressive', 'aqualuxe'),
                        'aggressive' => __('Aggressive', 'aqualuxe'),
                    );
                    $temperament_label = isset($temperament_labels[$temperament]) ? $temperament_labels[$temperament] : $temperament;
                ?>
                    <span class="fish-temperament <?php echo esc_attr($temperament); ?>"><?php esc_html_e('Temperament:', 'aqualuxe'); ?> <?php echo esc_html($temperament_label); ?></span>
                <?php endif; ?>
            </div>
            
            <div class="fish-species-excerpt">
                <?php the_excerpt(); ?>
            </div>
            
            <span class="fish-species-more"><?php esc_html_e('Learn More', 'aqualuxe'); ?></span>
        </div>
    </a>
</div>