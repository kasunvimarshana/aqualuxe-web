<?php
/**
 * The template for displaying fish category taxonomy archives
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
                <?php
                $term = get_queried_object();
                ?>
                <h1 class="page-title"><?php echo esc_html($term->name); ?> <?php esc_html_e('Fish', 'aqualuxe'); ?></h1>
                
                <?php if (!empty($term->description)) : ?>
                <div class="archive-description">
                    <?php echo wp_kses_post($term->description); ?>
                </div>
                <?php endif; ?>
                
                <div class="taxonomy-meta">
                    <?php
                    // Get water parameters for this category
                    $water_params = get_term_meta($term->term_id, 'water_parameters', true);
                    if (!empty($water_params)) {
                        echo '<div class="water-parameters">';
                        echo '<h3>' . esc_html__('Typical Water Parameters', 'aqualuxe') . '</h3>';
                        echo wp_kses_post($water_params);
                        echo '</div>';
                    }
                    ?>
                </div>
            </header><!-- .page-header -->

            <div class="fish-species-filters">
                <form class="fish-filter-form" method="get">
                    <?php
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
                    echo '<a href="' . esc_url(get_term_link($term)) . '" class="button reset-button">' . esc_html__('Reset', 'aqualuxe') . '</a>';
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
                                    
                                    // Display tank size
                                    $min_tank_size = get_post_meta(get_the_ID(), '_min_tank_size', true);
                                    if (!empty($min_tank_size)) {
                                        echo '<span class="fish-tank-size">';
                                        echo '<i class="fas fa-cube"></i> ' . esc_html($min_tank_size) . ' ' . esc_html__('gal', 'aqualuxe');
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

        <div class="category-care-guide">
            <h2><?php echo esc_html($term->name); ?> <?php esc_html_e('Care Guide', 'aqualuxe'); ?></h2>
            
            <?php
            // Get care guide for this category
            $care_guide = get_term_meta($term->term_id, 'care_guide', true);
            if (!empty($care_guide)) {
                echo wp_kses_post($care_guide);
            } else {
                // Default content if no care guide is set
                ?>
                <div class="care-guide-sections">
                    <div class="care-guide-section">
                        <h3><?php esc_html_e('Setting Up Your Tank', 'aqualuxe'); ?></h3>
                        <p><?php printf(esc_html__('Setting up a proper tank for %s fish requires careful consideration of water parameters, tank size, filtration, and decoration. Most %s fish thrive in a well-maintained aquarium with stable water conditions.', 'aqualuxe'), esc_html(strtolower($term->name)), esc_html(strtolower($term->name))); ?></p>
                    </div>
                    
                    <div class="care-guide-section">
                        <h3><?php esc_html_e('Feeding', 'aqualuxe'); ?></h3>
                        <p><?php printf(esc_html__('%s fish generally require a balanced diet consisting of high-quality commercial foods supplemented with live or frozen foods. Feed small amounts 2-3 times daily rather than one large feeding.', 'aqualuxe'), esc_html($term->name)); ?></p>
                    </div>
                    
                    <div class="care-guide-section">
                        <h3><?php esc_html_e('Maintenance', 'aqualuxe'); ?></h3>
                        <p><?php esc_html_e('Regular maintenance is essential for a healthy aquarium. This includes weekly water changes of 20-25%, filter cleaning, and water parameter testing. Keep an eye on ammonia, nitrite, and nitrate levels.', 'aqualuxe'); ?></p>
                    </div>
                    
                    <div class="care-guide-section">
                        <h3><?php esc_html_e('Common Health Issues', 'aqualuxe'); ?></h3>
                        <p><?php esc_html_e('Watch for signs of illness such as loss of appetite, unusual swimming behavior, spots on the body, or frayed fins. Quarantine new fish before adding them to your main tank to prevent disease spread.', 'aqualuxe'); ?></p>
                    </div>
                </div>
                <?php
            }
            ?>
            
            <div class="recommended-products">
                <h3><?php esc_html_e('Recommended Products', 'aqualuxe'); ?></h3>
                
                <?php
                // Get products for this fish category
                $args = array(
                    'post_type' => 'product',
                    'posts_per_page' => 4,
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'fish_category',
                            'field' => 'term_id',
                            'terms' => $term->term_id,
                        ),
                    ),
                );
                
                $products = new WP_Query($args);
                
                if ($products->have_posts()) {
                    echo '<div class="products-grid">';
                    
                    while ($products->have_posts()) {
                        $products->the_post();
                        
                        wc_get_template_part('content', 'product');
                    }
                    
                    echo '</div>';
                    
                    echo '<p class="view-all-link"><a href="' . esc_url(get_term_link($term, 'product_cat')) . '" class="button">' . esc_html__('View All Products', 'aqualuxe') . '</a></p>';
                    
                    wp_reset_postdata();
                } else {
                    echo '<p>' . esc_html__('No products found for this category.', 'aqualuxe') . '</p>';
                }
                ?>
            </div>
        </div>

    </main><!-- #main -->
</div><!-- #primary -->

<?php
get_sidebar();
get_footer();