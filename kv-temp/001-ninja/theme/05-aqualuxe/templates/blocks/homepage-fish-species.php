<?php
/**
 * Homepage Fish Species Showcase
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Get fish species settings from customizer or use defaults
$species_title = get_theme_mod( 'aqualuxe_fish_species_title', 'Exotic Fish Species' );
$species_subtitle = get_theme_mod( 'aqualuxe_fish_species_subtitle', 'Explore our collection of rare and beautiful aquatic species' );
$species_count = get_theme_mod( 'aqualuxe_fish_species_count', 3 );

// Check if fish_species post type exists
if ( ! post_type_exists( 'fish_species' ) ) {
    return;
}

// Get fish species
$args = array(
    'post_type'      => 'fish_species',
    'posts_per_page' => $species_count,
);

$fish_species = new WP_Query( $args );

// Return if no fish species found
if ( ! $fish_species->have_posts() ) {
    return;
}
?>

<section id="fish-species" class="fish-species-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title"><?php echo esc_html( $species_title ); ?></h2>
            <div class="section-subtitle"><?php echo wp_kses_post( $species_subtitle ); ?></div>
        </div>
        
        <div class="fish-species-grid">
            <?php
            while ( $fish_species->have_posts() ) :
                $fish_species->the_post();
                
                // Get fish species meta
                $scientific_name = get_post_meta( get_the_ID(), '_fish_scientific_name', true );
                $origin = get_post_meta( get_the_ID(), '_fish_origin', true );
                $size = get_post_meta( get_the_ID(), '_fish_size', true );
                $care_level = get_post_meta( get_the_ID(), '_fish_care_level', true );
                ?>
                <div class="fish-species-item">
                    <div class="fish-species-inner">
                        <div class="fish-species-image">
                            <a href="<?php the_permalink(); ?>">
                                <?php 
                                if ( has_post_thumbnail() ) {
                                    the_post_thumbnail( 'large' );
                                } else {
                                    echo '<img src="' . esc_url( get_template_directory_uri() . '/demo-content/images/fish-placeholder.jpg' ) . '" alt="' . esc_attr( get_the_title() ) . '">';
                                }
                                ?>
                            </a>
                        </div>
                        <div class="fish-species-content">
                            <h3 class="fish-species-title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h3>
                            <?php if ( $scientific_name ) : ?>
                                <div class="fish-scientific-name"><?php echo esc_html( $scientific_name ); ?></div>
                            <?php endif; ?>
                            
                            <div class="fish-species-meta">
                                <?php if ( $origin ) : ?>
                                    <div class="fish-meta-item">
                                        <span class="meta-label"><?php esc_html_e( 'Origin:', 'aqualuxe' ); ?></span>
                                        <span class="meta-value"><?php echo esc_html( $origin ); ?></span>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ( $size ) : ?>
                                    <div class="fish-meta-item">
                                        <span class="meta-label"><?php esc_html_e( 'Size:', 'aqualuxe' ); ?></span>
                                        <span class="meta-value"><?php echo esc_html( $size ); ?></span>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ( $care_level ) : ?>
                                    <div class="fish-meta-item">
                                        <span class="meta-label"><?php esc_html_e( 'Care Level:', 'aqualuxe' ); ?></span>
                                        <span class="meta-value"><?php echo esc_html( $care_level ); ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="fish-species-excerpt">
                                <?php the_excerpt(); ?>
                            </div>
                            
                            <a href="<?php the_permalink(); ?>" class="btn btn-secondary"><?php esc_html_e( 'Learn More', 'aqualuxe' ); ?></a>
                        </div>
                    </div>
                </div>
            <?php
            endwhile;
            wp_reset_postdata();
            ?>
        </div>
        
        <div class="section-footer">
            <a href="<?php echo esc_url( get_post_type_archive_link( 'fish_species' ) ); ?>" class="btn btn-primary"><?php esc_html_e( 'View All Species', 'aqualuxe' ); ?></a>
        </div>
    </div>
</section>