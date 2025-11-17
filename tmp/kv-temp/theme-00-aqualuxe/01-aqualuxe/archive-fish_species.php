
<?php
/**
 * The template for displaying fish species archive
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>

<div id="primary" class="content-area">
    <main id="main" class="site-main">

        <?php if (have_posts()) : ?>

            <header class="page-header">
                <h1 class="page-title"><?php esc_html_e('Fish Species', 'aqualuxe'); ?></h1>
                <div class="archive-description">
                    <p><?php esc_html_e('Browse our comprehensive collection of ornamental fish species. Find detailed information about care requirements, compatibility, and more.', 'aqualuxe'); ?></p>
                </div>
            </header><!-- .page-header -->

            <div class="fish-species-filters">
                <form class="fish-filter-form" method="get">
                    <?php
                    // Category filter
                    $categories = get_terms(array(
                        'taxonomy' => 'fish_category',
                        'hide_empty' => true,
                    ));

                    if (!empty($categories) && !is_wp_error($categories)) {
                        echo '<div class="filter-group">';
                        echo '<label for="fish-category">' . esc_html__('Category:', 'aqualuxe') . '</label>';
                        echo '<select name="fish_category" id="fish-category">';
                        echo '<option value="">' . esc_html__('All Categories', 'aqualuxe') . '</option>';
                        
                        foreach ($categories as $category) {
                            $selected = isset($_GET['fish_category']) && $_GET['fish_category'] === $category->slug ? 'selected' : '';
                            echo '<option value="' . esc_attr($category->slug) . '" ' . $selected . '>' . esc_html($category->name) . '</option>';
                        }
                        
                        echo '</select>';
                        echo '</div>';
                    }

                    // Care level filter
                    $care_levels = get_terms(array(
                        'taxonomy' => 'care_level',
                        'hide_empty' => true,
                    ));

                    if (!empty($care_levels) && !is_wp_error($care_levels)) {
                        echo '<div class="filter-group">';
                        echo '<label for="care-level">' . esc_html__('Care Level:', 'aqualuxe') . '</label>';
                        echo '<select name="care_level" id="care-level">';
                        echo '<option value="">' . esc_html__('All Care Levels', 'aqualuxe') . '</option>';
                        
                        foreach ($care_levels as $care_level) {
                            $selected = isset($_GET['care_level']) && $_GET['care_level'] === $care_level->slug ? 'selected' : '';
                            echo '<option value="' . esc_attr($care_level->slug) . '" ' . $selected . '>' . esc_html($care_level->name) . '</option>';
                        }
                        
                        echo '</select>';
                        echo '</div>';
                    }

                    // Temperament filter
                    $temperaments = array(
                        'peaceful' => __('Peaceful', 'aqualuxe'),
                        'semi-aggressive' => __('Semi-Aggressive', 'aqualuxe'),
                        'aggressive' => __('Aggressive', 'aqualuxe'),
                    );

                    echo '<div class="filter-group">';
                    echo '<label for="temperament">' . esc_html__('Temperament:', 'aqualuxe') . '</label>';
                    echo '<select name="temperament" id="temperament">';
                    echo '<option value="">' . esc_html__('All Temperaments', 'aqualuxe') . '</option>';
                    
                    foreach ($temperaments as $value => $label) {
                        $selected = isset($_GET['temperament']) && $_GET['temperament'] === $value ? 'selected' : '';
                        echo '<option value="' . esc_attr($value) . '" ' . $selected . '>' . esc_html($label) . '</option>';
                    }
                    
                    echo '</select>';
                    echo '</div>';

                    // Tank size filter
                    $tank_sizes = array(
                        'nano' => __('Nano (< 10 gallons)', 'aqualuxe'),
                        'small' => __('Small (10-20 gallons)', 'aqualuxe'),
                        'medium' => __('Medium (20-50 gallons)', 'aqualuxe'),
                        'large' => __('Large (50+ gallons)', 'aqualuxe'),
                    );

                    echo '<div class="filter-group">';
                    echo '<label for="tank-size">' . esc_html__('Tank Size:', 'aqualuxe') . '</label>';
                    echo '<select name="tank_size" id="tank-size">';
                    echo '<option value="">' . esc_html__('All Tank Sizes', 'aqualuxe') . '</option>';
                    
                    foreach ($tank_sizes as $value => $label) {
                        $selected = isset($_GET['tank_size']) && $_GET['tank_size'] === $value ? 'selected' : '';
                        echo '<option value="' . esc_attr($value) . '" ' . $selected . '>' . esc_html($label) . '</option>';
                    }
                    
                    echo '</select>';
                    echo '</div>';

                    // Search filter
                    echo '<div class="filter-group search-group">';
                    echo '<label for="fish-search">' . esc_html__('Search:', 'aqualuxe') . '</label>';
                    echo '<input type="text" name="fish_search" id="fish-search" placeholder="' . esc_attr__('Search fish species...', 'aqualuxe') . '" value="' . (isset($_GET['fish_search']) ? esc_attr($_GET['fish_search']) : '') . '">';
                    echo '</div>';

                    // Submit button
                    echo '<div class="filter-group submit-group">';
                    echo '<button type="submit" class="button filter-button">' . esc_html__('Apply Filters', 'aqualuxe') . '</button>';
                    echo '<a href="' . esc_url(get_post_type_archive_link('fish_species')) . '" class="button reset-button">' . esc_html__('Reset', 'aqualuxe') . '</a>';
                    echo '</div>';
                    ?>
                </form>
            </div>

            <div class="fish-species-grid">
                <?php
                /* Start the Loop */
                while (have_posts()) :
                    the_post();
                    ?>
                    <div class="fish-species-item">
                        <a href="<?php the_permalink(); ?>" class="fish-species-link">
                            <div class="fish-species-image">
                                <?php 
                                if (has_post_thumbnail()) {
                                    the_post_thumbnail('medium');
                                } else {
                                    echo '<img src="' . esc_url(AQUALUXE_ASSETS_URI . '/images/placeholder-fish.png') . '" alt="' . esc_attr(get_the_title()) . '" />';
                                }
                                ?>
                            </div>
                            
                            <div class="fish-species-content">
                                <h2 class="fish-species-title"><?php the_title(); ?></h2>
                                
                                <?php 
                                $scientific_name = get_post_meta(get_the_ID(), '_scientific_name', true);
                                if (!empty($scientific_name)) {
                                    echo '<p class="fish-scientific-name"><em>' . esc_html($scientific_name) . '</em></p>';
                                }
                                ?>
                                
                                <div class="fish-species-meta">
                                    <?php
                                    // Display fish category
                                    $categories = get_the_terms(get_the_ID(), 'fish_category');
                                    if (!empty($categories) && !is_wp_error($categories)) {
                                        echo '<span class="fish-category">';
                                        echo '<i class="fas fa-water"></i> ' . esc_html($categories[0]->name);
                                        echo '</span>';
                                    }
                                    
                                    // Display care level
                                    $care_levels = get_the_terms(get_the_ID(), 'care_level');
                                    if (!empty($care_levels) && !is_wp_error($care_levels)) {
                                        echo '<span class="fish-care-level">';
                                        echo '<i class="fas fa-hand-holding-heart"></i> ' . esc_html($care_levels[0]->name);
                                        echo '</span>';
                                    }
                                    
                                    // Display temperament
                                    $temperament = get_post_meta(get_the_ID(), '_temperament', true);
                                    if (!empty($temperament)) {
                                        $temperament_labels = array(
                                            'peaceful' => __('Peaceful', 'aqualuxe'),
                                            'semi-aggressive' => __('Semi-Aggressive', 'aqualuxe'),
                                            'aggressive' => __('Aggressive', 'aqualuxe'),
                                        );
                                        $temperament_label = isset($temperament_labels[$temperament]) ? $temperament_labels[$temperament] : $temperament;
                                        
                                        echo '<span class="fish-temperament ' . esc_attr($temperament) . '">';
                                        echo '<i class="fas fa-fish"></i> ' . esc_html($temperament_label);
                                        echo '</span>';
                                    }
                                    ?>
                                </div>
                                
                                <div class="fish-species-excerpt">
                                    <?php the_excerpt(); ?>
                                </div>
                                
                                <span class="button fish-species-more"><?php esc_html_e('Learn More', 'aqualuxe'); ?></span>
                            </div>
                        </a>
                    </div>
                    <?php
                endwhile;
                ?>
            </div>

            <?php
            the_posts_pagination(array(
                'prev_text' => '<i class="fas fa-chevron-left"></i> ' . __('Previous', 'aqualuxe'),
                'next_text' => __('Next', 'aqualuxe') . ' <i class="fas fa-chevron-right"></i>',
            ));

        else :

            get_template_part('template-parts/content', 'none');

        endif;
        ?>

        <div class="fish-species-tools">
            <h2><?php esc_html_e('Helpful Aquarium Tools', 'aqualuxe'); ?></h2>
            <div class="tools-grid">
                <div class="tool-card">
                    <i class="fas fa-check-circle tool-icon"></i>
                    <h3><?php esc_html_e('Fish Compatibility Checker', 'aqualuxe'); ?></h3>
                    <p><?php esc_html_e('Check which fish species can live together peacefully in your aquarium.', 'aqualuxe'); ?></p>
                    <a href="<?php echo esc_url(home_url('/fish-compatibility-checker/')); ?>" class="button tool-button"><?php esc_html_e('Check Compatibility', 'aqualuxe'); ?></a>
                </div>
                
                <div class="tool-card">
                    <i class="fas fa-calculator tool-icon"></i>
                    <h3><?php esc_html_e('Water Parameter Calculator', 'aqualuxe'); ?></h3>
                    <p><?php esc_html_e('Calculate the right water parameters for your fish and aquarium setup.', 'aqualuxe'); ?></p>
                    <a href="<?php echo esc_url(home_url('/water-parameter-calculator/')); ?>" class="button tool-button"><?php esc_html_e('Calculate Parameters', 'aqualuxe'); ?></a>
                </div>
                
                <div class="tool-card">
                    <i class="fas fa-tachometer-alt tool-icon"></i>
                    <h3><?php esc_html_e('Tank Volume Calculator', 'aqualuxe'); ?></h3>
                    <p><?php esc_html_e('Calculate the volume of your aquarium to ensure proper stocking levels.', 'aqualuxe'); ?></p>
                    <a href="<?php echo esc_url(home_url('/tank-volume-calculator/')); ?>" class="button tool-button"><?php esc_html_e('Calculate Volume', 'aqualuxe'); ?></a>
                </div>
                
                <div class="tool-card">
                    <i class="fas fa-book tool-icon"></i>
                    <h3><?php esc_html_e('Fish Care Guides', 'aqualuxe'); ?></h3>
                    <p><?php esc_html_e('Access detailed care guides for popular fish species and aquarium setups.', 'aqualuxe'); ?></p>
                    <a href="<?php echo esc_url(home_url('/fish-care-guides/')); ?>" class="button tool-button"><?php esc_html_e('View Guides', 'aqualuxe'); ?></a>
                </div>
            </div>
        </div>

    </main><!-- #main -->
</div><!-- #primary -->

<?php
get_sidebar();
get_footer();
