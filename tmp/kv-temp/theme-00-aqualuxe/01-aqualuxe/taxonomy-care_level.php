
<?php
/**
 * The template for displaying care level taxonomy archives
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
                <h1 class="page-title"><?php echo esc_html($term->name); ?> <?php esc_html_e('Care Level Fish', 'aqualuxe'); ?></h1>
                
                <?php if (!empty($term->description)) : ?>
                <div class="archive-description">
                    <?php echo wp_kses_post($term->description); ?>
                </div>
                <?php endif; ?>
                
                <div class="care-level-badge <?php echo esc_attr(strtolower($term->name)); ?>">
                    <div class="care-level-icon">
                        <?php
                        // Display different icons based on care level
                        switch (strtolower($term->name)) {
                            case 'beginner':
                            case 'easy':
                                echo '<i class="fas fa-smile"></i>';
                                break;
                            case 'intermediate':
                            case 'moderate':
                                echo '<i class="fas fa-meh"></i>';
                                break;
                            case 'advanced':
                            case 'expert':
                            case 'difficult':
                                echo '<i class="fas fa-graduation-cap"></i>';
                                break;
                            default:
                                echo '<i class="fas fa-fish"></i>';
                        }
                        ?>
                    </div>
                    <div class="care-level-text">
                        <h3><?php echo esc_html($term->name); ?> <?php esc_html_e('Care Level', 'aqualuxe'); ?></h3>
                        <p>
                            <?php
                            // Display different descriptions based on care level
                            switch (strtolower($term->name)) {
                                case 'beginner':
                                case 'easy':
                                    esc_html_e('These fish are hardy, adaptable, and forgiving of minor water quality fluctuations. Perfect for beginners.', 'aqualuxe');
                                    break;
                                case 'intermediate':
                                case 'moderate':
                                    esc_html_e('These fish require more attention to water parameters and may have specific dietary needs. Suitable for aquarists with some experience.', 'aqualuxe');
                                    break;
                                case 'advanced':
                                case 'expert':
                                case 'difficult':
                                    esc_html_e('These fish have demanding requirements for water quality, diet, or behavior. Recommended for experienced aquarists.', 'aqualuxe');
                                    break;
                                default:
                                    esc_html_e('These fish have specific care requirements. Please research their needs before purchase.', 'aqualuxe');
                            }
                            ?>
                        </p>
                    </div>
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
                                    // Display fish category
                                    $categories = get_the_terms(get_the_ID(), 'fish_category');
                                    if (!empty($categories) && !is_wp_error($categories)) {
                                        echo '<span class="fish-category">';
                                        echo '<i class="fas fa-water"></i> ' . esc_html($categories[0]->name);
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

        <div class="care-level-guide">
            <h2><?php echo esc_html($term->name); ?> <?php esc_html_e('Care Level Guide', 'aqualuxe'); ?></h2>
            
            <?php
            // Get care guide for this care level
            $care_guide = get_term_meta($term->term_id, 'care_guide', true);
            if (!empty($care_guide)) {
                echo wp_kses_post($care_guide);
            } else {
                // Default content based on care level
                switch (strtolower($term->name)) {
                    case 'beginner':
                    case 'easy':
                        ?>
                        <div class="care-guide-sections">
                            <div class="care-guide-section">
                                <h3><?php esc_html_e('Why Choose Beginner-Friendly Fish', 'aqualuxe'); ?></h3>
                                <p><?php esc_html_e('Beginner-friendly fish are hardy species that can tolerate a range of water conditions, making them ideal for new aquarists who are still learning about water chemistry and tank maintenance. These fish are typically more forgiving of minor mistakes in care.', 'aqualuxe'); ?></p>
                            </div>
                            
                            <div class="care-guide-section">
                                <h3><?php esc_html_e('Basic Care Tips', 'aqualuxe'); ?></h3>
                                <ul>
                                    <li><?php esc_html_e('Perform regular water changes of 20-25% weekly', 'aqualuxe'); ?></li>
                                    <li><?php esc_html_e('Feed small amounts 1-2 times daily', 'aqualuxe'); ?></li>
                                    <li><?php esc_html_e('Monitor water temperature and keep it stable', 'aqualuxe'); ?></li>
                                    <li><?php esc_html_e('Test water parameters weekly with a basic test kit', 'aqualuxe'); ?></li>
                                    <li><?php esc_html_e('Clean filter media monthly (but never all at once)', 'aqualuxe'); ?></li>
                                </ul>
                            </div>
                            
                            <div class="care-guide-section">
                                <h3><?php esc_html_e('Common Mistakes to Avoid', 'aqualuxe'); ?></h3>
                                <ul>
                                    <li><?php esc_html_e('Overstocking your tank with too many fish', 'aqualuxe'); ?></li>
                                    <li><?php esc_html_e('Overfeeding, which leads to poor water quality', 'aqualuxe'); ?></li>
                                    <li><?php esc_html_e('Skipping the cycling process for a new tank', 'aqualuxe'); ?></li>
                                    <li><?php esc_html_e('Changing too much water at once', 'aqualuxe'); ?></li>
                                    <li><?php esc_html_e('Cleaning filter media with tap water (use tank water instead)', 'aqualuxe'); ?></li>
                                </ul>
                            </div>
                        </div>
                        <?php
                        break;
                        
                    case 'intermediate':
                    case 'moderate':
                        ?>
                        <div class="care-guide-sections">
                            <div class="care-guide-section">
                                <h3><?php esc_html_e('Intermediate Care Requirements', 'aqualuxe'); ?></h3>
                                <p><?php esc_html_e('Intermediate-level fish require more attention to water parameters and may have specific dietary or environmental needs. These fish are suitable for aquarists who have successfully kept beginner fish and are ready for more challenging species.', 'aqualuxe'); ?></p>
                            </div>
                            
                            <div class="care-guide-section">
                                <h3><?php esc_html_e('Advanced Care Tips', 'aqualuxe'); ?></h3>
                                <ul>
                                    <li><?php esc_html_e('Monitor water parameters more closely, including GH and KH', 'aqualuxe'); ?></li>
                                    <li><?php esc_html_e('Consider specialized diets with varied food types', 'aqualuxe'); ?></li>
                                    <li><?php esc_html_e('Pay attention to tank mates and compatibility', 'aqualuxe'); ?></li>
                                    <li><?php esc_html_e('Create appropriate habitats with plants, caves, or specific substrates', 'aqualuxe'); ?></li>
                                    <li><?php esc_html_e('Implement more sophisticated filtration systems', 'aqualuxe'); ?></li>
                                </ul>
                            </div>
                            
                            <div class="care-guide-section">
                                <h3><?php esc_html_e('Upgrading Your Skills', 'aqualuxe'); ?></h3>
                                <p><?php esc_html_e('To successfully keep intermediate-level fish, focus on developing these skills:', 'aqualuxe'); ?></p>
                                <ul>
                                    <li><?php esc_html_e('Understanding the nitrogen cycle in depth', 'aqualuxe'); ?></li>
                                    <li><?php esc_html_e('Recognizing early signs of fish stress or disease', 'aqualuxe'); ?></li>
                                    <li><?php esc_html_e('Maintaining stable water parameters over time', 'aqualuxe'); ?></li>
                                    <li><?php esc_html_e('Creating appropriate aquascapes for specific species', 'aqualuxe'); ?></li>
                                    <li><?php esc_html_e('Managing a quarantine tank for new additions', 'aqualuxe'); ?></li>
                                </ul>
                            </div>
                        </div>
                        <?php
                        break;
                        
                    case 'advanced':
                    case 'expert':
                    case 'difficult':
                        ?>
                        <div class="care-guide-sections">
                            <div class="care-guide-section">
                                <h3><?php esc_html_e('Advanced Care Requirements', 'aqualuxe'); ?></h3>
                                <p><?php esc_html_e('Advanced-level fish have demanding requirements for water quality, diet, or behavior. These species often require specialized care and are recommended only for experienced aquarists who have successfully maintained multiple aquariums over time.', 'aqualuxe'); ?></p>
                            </div>
                            
                            <div class="care-guide-section">
                                <h3><?php esc_html_e('Expert Care Techniques', 'aqualuxe'); ?></h3>
                                <ul>
                                    <li><?php esc_html_e('Maintain precise water parameters with minimal fluctuation', 'aqualuxe'); ?></li>
                                    <li><?php esc_html_e('Provide specialized diets, possibly including live foods or custom preparations', 'aqualuxe'); ?></li>
                                    <li><?php esc_html_e('Create species-specific biotopes that mimic natural habitats', 'aqualuxe'); ?></li>
                                    <li><?php esc_html_e('Implement advanced filtration, possibly including RO/DI water treatment', 'aqualuxe'); ?></li>
                                    <li><?php esc_html_e('Monitor and adjust water chemistry beyond basic parameters', 'aqualuxe'); ?></li>
                                </ul>
                            </div>
                            
                            <div class="care-guide-section">
                                <h3><?php esc_html_e('Special Considerations', 'aqualuxe'); ?></h3>
                                <p><?php esc_html_e('Advanced fish often require:', 'aqualuxe'); ?></p>
                                <ul>
                                    <li><?php esc_html_e('Dedicated tanks with specific parameters', 'aqualuxe'); ?></li>
                                    <li><?php esc_html_e('Careful selection of compatible tank mates', 'aqualuxe'); ?></li>
                                    <li><?php esc_html_e('Advanced disease prevention and treatment knowledge', 'aqualuxe'); ?></li>
                                    <li><?php esc_html_e('Understanding of species-specific behaviors and needs', 'aqualuxe'); ?></li>
                                    <li><?php esc_html_e('Commitment to more frequent maintenance routines', 'aqualuxe'); ?></li>
                                </ul>
                            </div>
                        </div>
                        <?php
                        break;
                        
                    default:
                        ?>
                        <div class="care-guide-sections">
                            <div class="care-guide-section">
                                <h3><?php esc_html_e('Care Requirements', 'aqualuxe'); ?></h3>
                                <p><?php esc_html_e('Each fish species has specific care requirements based on their natural habitat and behavior. Understanding these requirements is essential for keeping your fish healthy and thriving in an aquarium environment.', 'aqualuxe'); ?></p>
                            </div>
                            
                            <div class="care-guide-section">
                                <h3><?php esc_html_e('General Care Tips', 'aqualuxe'); ?></h3>
                                <ul>
                                    <li><?php esc_html_e('Research your specific fish species before purchase', 'aqualuxe'); ?></li>
                                    <li><?php esc_html_e('Maintain appropriate water parameters for your species', 'aqualuxe'); ?></li>
                                    <li><?php esc_html_e('Provide a suitable diet with variety', 'aqualuxe'); ?></li>
                                    <li><?php esc_html_e('Create an appropriate habitat with hiding places', 'aqualuxe'); ?></li>
                                    <li><?php esc_html_e('Perform regular water changes and tank maintenance', 'aqualuxe'); ?></li>
                                </ul>
                            </div>
                        </div>
                        <?php
                }
            }
            ?>
            
            <div class="recommended-starter-kit">
                <h3><?php esc_html_e('Recommended Starter Kit', 'aqualuxe'); ?></h3>
                
                <div class="starter-kit-products">
                    <?php
                    // Get recommended products based on care level
                    $args = array(
                        'post_type' => 'product',
                        'posts_per_page' => 4,
                        'meta_query' => array(
                            array(
                                'key' => '_recommended_care_level',
                                'value' => $term->slug,
                                'compare' => '=',
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
                        
                        wp_reset_postdata();
                    } else {
                        // Display default recommended products
                        switch (strtolower($term->name)) {
                            case 'beginner':
                            case 'easy':
                                ?>
                                <div class="starter-kit-items">
                                    <div class="starter-kit-item">
                                        <div class="item-icon"><i class="fas fa-tint"></i></div>
                                        <h4><?php esc_html_e('Water Conditioner', 'aqualuxe'); ?></h4>
                                        <p><?php esc_html_e('Essential for removing chlorine and chloramines from tap water.', 'aqualuxe'); ?></p>
                                    </div>
                                    
                                    <div class="starter-kit-item">
                                        <div class="item-icon"><i class="fas fa-vial"></i></div>
                                        <h4><?php esc_html_e('Basic Test Kit', 'aqualuxe'); ?></h4>
                                        <p><?php esc_html_e('For monitoring ammonia, nitrite, nitrate, and pH levels.', 'aqualuxe'); ?></p>
                                    </div>
                                    
                                    <div class="starter-kit-item">
                                        <div class="item-icon"><i class="fas fa-filter"></i></div>
                                        <h4><?php esc_html_e('Sponge Filter', 'aqualuxe'); ?></h4>
                                        <p><?php esc_html_e('Gentle filtration that's perfect for beginners and easy to maintain.', 'aqualuxe'); ?></p>
                                    </div>
                                    
                                    <div class="starter-kit-item">
                                        <div class="item-icon"><i class="fas fa-thermometer-half"></i></div>
                                        <h4><?php esc_html_e('Reliable Heater', 'aqualuxe'); ?></h4>
                                        <p><?php esc_html_e('To maintain stable water temperature for tropical fish.', 'aqualuxe'); ?></p>
                                    </div>
                                </div>
                                <?php
                                break;
                                
                            case 'intermediate':
                            case 'moderate':
                                ?>
                                <div class="starter-kit-items">
                                    <div class="starter-kit-item">
                                        <div class="item-icon"><i class="fas fa-vials"></i></div>
                                        <h4><?php esc_html_e('Advanced Test Kit', 'aqualuxe'); ?></h4>
                                        <p><?php esc_html_e('Includes tests for GH, KH, and other parameters beyond the basics.', 'aqualuxe'); ?></p>
                                    </div>
                                    
                                    <div class="starter-kit-item">
                                        <div class="item-icon"><i class="fas fa-filter"></i></div>
                                        <h4><?php esc_html_e('Canister Filter', 'aqualuxe'); ?></h4>
                                        <p><?php esc_html_e('Provides superior mechanical, biological, and chemical filtration.', 'aqualuxe'); ?></p>
                                    </div>
                                    
                                    <div class="starter-kit-item">
                                        <div class="item-icon"><i class="fas fa-leaf"></i></div>
                                        <h4><?php esc_html_e('Live Plants', 'aqualuxe'); ?></h4>
                                        <p><?php esc_html_e('Helps maintain water quality and provides natural habitat.', 'aqualuxe'); ?></p>
                                    </div>
                                    
                                    <div class="starter-kit-item">
                                        <div class="item-icon"><i class="fas fa-pills"></i></div>
                                        <h4><?php esc_html_e('Specialized Foods', 'aqualuxe'); ?></h4>
                                        <p><?php esc_html_e('Variety of foods to meet specific dietary requirements.', 'aqualuxe'); ?></p>
                                    </div>
                                </div>
                                <?php
                                break;
                                
                            case 'advanced':
                            case 'expert':
                            case 'difficult':
                                ?>
                                <div class="starter-kit-items">
                                    <div class="starter-kit-item">
                                        <div class="item-icon"><i class="fas fa-tint-slash"></i></div>
                                        <h4><?php esc_html_e('RO/DI System', 'aqualuxe'); ?></h4>
                                        <p><?php esc_html_e('For precise control over water parameters through remineralization.', 'aqualuxe'); ?></p>
                                    </div>
                                    
                                    <div class="starter-kit-item">
                                        <div class="item-icon"><i class="fas fa-microscope"></i></div>
                                        <h4><?php esc_html_e('Professional Test Kit', 'aqualuxe'); ?></h4>
                                        <p><?php esc_html_e('High-precision tests for all water parameters.', 'aqualuxe'); ?></p>
                                    </div>
                                    
                                    <div class="starter-kit-item">
                                        <div class="item-icon"><i class="fas fa-syringe"></i></div>
                                        <h4><?php esc_html_e('Dosing System', 'aqualuxe'); ?></h4>
                                        <p><?php esc_html_e('For precise addition of minerals and supplements.', 'aqualuxe'); ?></p>
                                    </div>
                                    
                                    <div class="starter-kit-item">
                                        <div class="item-icon"><i class="fas fa-bug"></i></div>
                                        <h4><?php esc_html_e('Live Food Cultures', 'aqualuxe'); ?></h4>
                                        <p><?php esc_html_e('Equipment for culturing live foods at home.', 'aqualuxe'); ?></p>
                                    </div>
                                </div>
                                <?php
                                break;
                                
                            default:
                                ?>
                                <div class="starter-kit-items">
                                    <div class="starter-kit-item">
                                        <div class="item-icon"><i class="fas fa-book"></i></div>
                                        <h4><?php esc_html_e('Research Resources', 'aqualuxe'); ?></h4>
                                        <p><?php esc_html_e('Books and guides specific to your fish species.', 'aqualuxe'); ?></p>
                                    </div>
                                    
                                    <div class="starter-kit-item">
                                        <div class="item-icon"><i class="fas fa-vial"></i></div>
                                        <h4><?php esc_html_e('Water Test Kit', 'aqualuxe'); ?></h4>
                                        <p><?php esc_html_e('For monitoring essential water parameters.', 'aqualuxe'); ?></p>
                                    </div>
                                    
                                    <div class="starter-kit-item">
                                        <div class="item-icon"><i class="fas fa-filter"></i></div>
                                        <h4><?php esc_html_e('Quality Filtration', 'aqualuxe'); ?></h4>
                                        <p><?php esc_html_e('Appropriate for your tank size and fish load.', 'aqualuxe'); ?></p>
                                    </div>
                                    
                                    <div class="starter-kit-item">
                                        <div class="item-icon"><i class="fas fa-utensils"></i></div>
                                        <h4><?php esc_html_e('Appropriate Foods', 'aqualuxe'); ?></h4>
                                        <p><?php esc_html_e('Quality nutrition for your specific fish species.', 'aqualuxe'); ?></p>
                                    </div>
                                </div>
                                <?php
                        }
                    }
                    ?>
                </div>
                
                <p class="shop-link">
                    <a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>" class="button"><?php esc_html_e('Shop All Aquarium Supplies', 'aqualuxe'); ?></a>
                </p>
            </div>
        </div>

    </main><!-- #main -->
</div><!-- #primary -->

<?php
get_sidebar();
get_footer();
