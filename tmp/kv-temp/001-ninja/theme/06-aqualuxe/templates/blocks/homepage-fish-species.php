<?php
/**
 * Homepage Fish Species Block
 *
 * @package AquaLuxe
 */

// Get args from template part
$args = $args ?? array();

// Default values
$defaults = array(
    'fish_species_title' => __( 'Explore Our Fish Species', 'aqualuxe' ),
    'fish_species_subtitle' => __( 'Discover our premium ornamental fish collection', 'aqualuxe' ),
    'fish_species_count' => 3,
    'fish_species_selection' => 'featured',
    'fish_species_ids' => array(),
    'fish_species_button_text' => __( 'View All Species', 'aqualuxe' ),
    'fish_species_button_url' => home_url( '/fish-species/' ),
);

// Merge defaults with args
$args = wp_parse_args( $args, $defaults );

// Extract variables
$title = $args['fish_species_title'];
$subtitle = $args['fish_species_subtitle'];
$count = $args['fish_species_count'];
$selection = $args['fish_species_selection'];
$species_ids = $args['fish_species_ids'];
$button_text = $args['fish_species_button_text'];
$button_url = $args['fish_species_button_url'];

// Get fish species based on selection method
// In a real implementation, this would query the custom post type
// For demonstration, we'll use placeholder data
$species = array();

switch ( $selection ) {
    case 'featured':
        // Get featured species
        $species = array(
            array(
                'id' => 1,
                'title' => __( 'Betta Fish', 'aqualuxe' ),
                'description' => __( 'Known for their vibrant colors and flowing fins, Betta fish are one of the most popular ornamental fish species.', 'aqualuxe' ),
                'image' => get_template_directory_uri() . '/assets/images/species/betta.jpg',
                'url' => home_url( '/fish-species/betta-fish/' ),
                'care_level' => __( 'Beginner', 'aqualuxe' ),
                'water_type' => __( 'Freshwater', 'aqualuxe' ),
            ),
            array(
                'id' => 2,
                'title' => __( 'Discus', 'aqualuxe' ),
                'description' => __( 'Discus are known as the "king of the aquarium" due to their striking colors and unique disc-shaped body.', 'aqualuxe' ),
                'image' => get_template_directory_uri() . '/assets/images/species/discus.jpg',
                'url' => home_url( '/fish-species/discus/' ),
                'care_level' => __( 'Advanced', 'aqualuxe' ),
                'water_type' => __( 'Freshwater', 'aqualuxe' ),
            ),
            array(
                'id' => 3,
                'title' => __( 'Angelfish', 'aqualuxe' ),
                'description' => __( 'Angelfish are elegant, triangular-shaped fish with long, flowing fins and a graceful swimming style.', 'aqualuxe' ),
                'image' => get_template_directory_uri() . '/assets/images/species/angelfish.jpg',
                'url' => home_url( '/fish-species/angelfish/' ),
                'care_level' => __( 'Intermediate', 'aqualuxe' ),
                'water_type' => __( 'Freshwater', 'aqualuxe' ),
            ),
        );
        break;
    
    case 'popular':
        // Get popular species
        $species = array(
            array(
                'id' => 4,
                'title' => __( 'Guppy', 'aqualuxe' ),
                'description' => __( 'Guppies are small, colorful fish known for their ease of care and vibrant tail patterns.', 'aqualuxe' ),
                'image' => get_template_directory_uri() . '/assets/images/species/guppy.jpg',
                'url' => home_url( '/fish-species/guppy/' ),
                'care_level' => __( 'Beginner', 'aqualuxe' ),
                'water_type' => __( 'Freshwater', 'aqualuxe' ),
            ),
            array(
                'id' => 5,
                'title' => __( 'Neon Tetra', 'aqualuxe' ),
                'description' => __( 'Neon Tetras are small, peaceful fish known for their bright blue and red coloration.', 'aqualuxe' ),
                'image' => get_template_directory_uri() . '/assets/images/species/neon-tetra.jpg',
                'url' => home_url( '/fish-species/neon-tetra/' ),
                'care_level' => __( 'Beginner', 'aqualuxe' ),
                'water_type' => __( 'Freshwater', 'aqualuxe' ),
            ),
            array(
                'id' => 6,
                'title' => __( 'Goldfish', 'aqualuxe' ),
                'description' => __( 'Goldfish are one of the most popular aquarium fish, known for their bright colors and variety of shapes.', 'aqualuxe' ),
                'image' => get_template_directory_uri() . '/assets/images/species/goldfish.jpg',
                'url' => home_url( '/fish-species/goldfish/' ),
                'care_level' => __( 'Beginner', 'aqualuxe' ),
                'water_type' => __( 'Freshwater', 'aqualuxe' ),
            ),
        );
        break;
    
    case 'newest':
        // Get newest species
        $species = array(
            array(
                'id' => 7,
                'title' => __( 'Koi', 'aqualuxe' ),
                'description' => __( 'Koi are ornamental varieties of domesticated common carp, known for their vibrant colors and patterns.', 'aqualuxe' ),
                'image' => get_template_directory_uri() . '/assets/images/species/koi.jpg',
                'url' => home_url( '/fish-species/koi/' ),
                'care_level' => __( 'Intermediate', 'aqualuxe' ),
                'water_type' => __( 'Pond', 'aqualuxe' ),
            ),
            array(
                'id' => 8,
                'title' => __( 'Plecostomus', 'aqualuxe' ),
                'description' => __( 'Plecostomus are algae-eating catfish known for their sucker-like mouths and armored appearance.', 'aqualuxe' ),
                'image' => get_template_directory_uri() . '/assets/images/species/pleco.jpg',
                'url' => home_url( '/fish-species/plecostomus/' ),
                'care_level' => __( 'Beginner', 'aqualuxe' ),
                'water_type' => __( 'Freshwater', 'aqualuxe' ),
            ),
            array(
                'id' => 9,
                'title' => __( 'Corydoras', 'aqualuxe' ),
                'description' => __( 'Corydoras are small, peaceful catfish that are excellent bottom cleaners for community aquariums.', 'aqualuxe' ),
                'image' => get_template_directory_uri() . '/assets/images/species/corydoras.jpg',
                'url' => home_url( '/fish-species/corydoras/' ),
                'care_level' => __( 'Beginner', 'aqualuxe' ),
                'water_type' => __( 'Freshwater', 'aqualuxe' ),
            ),
        );
        break;
    
    case 'specific':
        // Get specific species by ID
        if ( ! empty( $species_ids ) ) {
            // In a real implementation, this would query the custom post type for the specific species
            // For demonstration, we'll use placeholder data
            $all_species = array(
                1 => array(
                    'id' => 1,
                    'title' => __( 'Betta Fish', 'aqualuxe' ),
                    'description' => __( 'Known for their vibrant colors and flowing fins, Betta fish are one of the most popular ornamental fish species.', 'aqualuxe' ),
                    'image' => get_template_directory_uri() . '/assets/images/species/betta.jpg',
                    'url' => home_url( '/fish-species/betta-fish/' ),
                    'care_level' => __( 'Beginner', 'aqualuxe' ),
                    'water_type' => __( 'Freshwater', 'aqualuxe' ),
                ),
                2 => array(
                    'id' => 2,
                    'title' => __( 'Discus', 'aqualuxe' ),
                    'description' => __( 'Discus are known as the "king of the aquarium" due to their striking colors and unique disc-shaped body.', 'aqualuxe' ),
                    'image' => get_template_directory_uri() . '/assets/images/species/discus.jpg',
                    'url' => home_url( '/fish-species/discus/' ),
                    'care_level' => __( 'Advanced', 'aqualuxe' ),
                    'water_type' => __( 'Freshwater', 'aqualuxe' ),
                ),
                3 => array(
                    'id' => 3,
                    'title' => __( 'Angelfish', 'aqualuxe' ),
                    'description' => __( 'Angelfish are elegant, triangular-shaped fish with long, flowing fins and a graceful swimming style.', 'aqualuxe' ),
                    'image' => get_template_directory_uri() . '/assets/images/species/angelfish.jpg',
                    'url' => home_url( '/fish-species/angelfish/' ),
                    'care_level' => __( 'Intermediate', 'aqualuxe' ),
                    'water_type' => __( 'Freshwater', 'aqualuxe' ),
                ),
                4 => array(
                    'id' => 4,
                    'title' => __( 'Guppy', 'aqualuxe' ),
                    'description' => __( 'Guppies are small, colorful fish known for their ease of care and vibrant tail patterns.', 'aqualuxe' ),
                    'image' => get_template_directory_uri() . '/assets/images/species/guppy.jpg',
                    'url' => home_url( '/fish-species/guppy/' ),
                    'care_level' => __( 'Beginner', 'aqualuxe' ),
                    'water_type' => __( 'Freshwater', 'aqualuxe' ),
                ),
                5 => array(
                    'id' => 5,
                    'title' => __( 'Neon Tetra', 'aqualuxe' ),
                    'description' => __( 'Neon Tetras are small, peaceful fish known for their bright blue and red coloration.', 'aqualuxe' ),
                    'image' => get_template_directory_uri() . '/assets/images/species/neon-tetra.jpg',
                    'url' => home_url( '/fish-species/neon-tetra/' ),
                    'care_level' => __( 'Beginner', 'aqualuxe' ),
                    'water_type' => __( 'Freshwater', 'aqualuxe' ),
                ),
            );
            
            foreach ( $species_ids as $id ) {
                if ( isset( $all_species[ $id ] ) ) {
                    $species[] = $all_species[ $id ];
                }
            }
        }
        break;
}

// Limit species to the specified count
$species = array_slice( $species, 0, $count );
?>

<section class="aqualuxe-fish-species">
    <div class="aqualuxe-container">
        <div class="aqualuxe-section-header">
            <?php if ( ! empty( $title ) ) : ?>
                <h2 class="aqualuxe-section-title"><?php echo esc_html( $title ); ?></h2>
            <?php endif; ?>
            
            <?php if ( ! empty( $subtitle ) ) : ?>
                <p class="aqualuxe-section-subtitle"><?php echo esc_html( $subtitle ); ?></p>
            <?php endif; ?>
        </div>
        
        <div class="aqualuxe-species-grid">
            <?php foreach ( $species as $fish ) : ?>
                <div class="aqualuxe-species-card">
                    <div class="aqualuxe-species-image">
                        <a href="<?php echo esc_url( $fish['url'] ); ?>">
                            <img src="<?php echo esc_url( $fish['image'] ); ?>" alt="<?php echo esc_attr( $fish['title'] ); ?>" />
                        </a>
                    </div>
                    <div class="aqualuxe-species-content">
                        <h3 class="aqualuxe-species-title">
                            <a href="<?php echo esc_url( $fish['url'] ); ?>"><?php echo esc_html( $fish['title'] ); ?></a>
                        </h3>
                        <div class="aqualuxe-species-meta">
                            <span class="aqualuxe-species-care-level">
                                <strong><?php esc_html_e( 'Care Level:', 'aqualuxe' ); ?></strong> <?php echo esc_html( $fish['care_level'] ); ?>
                            </span>
                            <span class="aqualuxe-species-water-type">
                                <strong><?php esc_html_e( 'Water Type:', 'aqualuxe' ); ?></strong> <?php echo esc_html( $fish['water_type'] ); ?>
                            </span>
                        </div>
                        <div class="aqualuxe-species-description">
                            <?php echo esc_html( $fish['description'] ); ?>
                        </div>
                        <div class="aqualuxe-species-actions">
                            <a href="<?php echo esc_url( $fish['url'] ); ?>" class="aqualuxe-button aqualuxe-button-small"><?php esc_html_e( 'Learn More', 'aqualuxe' ); ?></a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <?php if ( ! empty( $button_text ) && ! empty( $button_url ) ) : ?>
            <div class="aqualuxe-section-footer">
                <a href="<?php echo esc_url( $button_url ); ?>" class="aqualuxe-button aqualuxe-button-secondary"><?php echo esc_html( $button_text ); ?></a>
            </div>
        <?php endif; ?>
    </div>
</section>