<?php
/**
 * About Facilities Block
 *
 * @package AquaLuxe
 */

// Get args from template part
$args = $args ?? array();

// Default values
$defaults = array(
    'facilities_title' => __( 'Our Facilities', 'aqualuxe' ),
    'facilities_content' => '<p>Our state-of-the-art facilities are designed to provide the optimal environment for breeding and raising ornamental fish. We maintain strict quality control standards and employ the latest technology to ensure the health and well-being of our fish.</p>
<p>Our facilities include:</p>
<ul>
<li>Temperature-controlled breeding rooms</li>
<li>Advanced filtration systems</li>
<li>Specialized quarantine areas</li>
<li>Research and development laboratory</li>
<li>Dedicated packing and shipping department</li>
</ul>
<p>We invite you to take a virtual tour of our facilities through the gallery below.</p>',
    'facilities_gallery' => array(
        array(
            'url' => get_template_directory_uri() . '/assets/images/facilities/breeding-tanks.jpg',
            'title' => __( 'Breeding Tanks', 'aqualuxe' ),
            'caption' => __( 'Our specialized breeding tanks for rare species', 'aqualuxe' ),
        ),
        array(
            'url' => get_template_directory_uri() . '/assets/images/facilities/filtration-system.jpg',
            'title' => __( 'Filtration System', 'aqualuxe' ),
            'caption' => __( 'Advanced filtration system for optimal water quality', 'aqualuxe' ),
        ),
        array(
            'url' => get_template_directory_uri() . '/assets/images/facilities/research-lab.jpg',
            'title' => __( 'Research Lab', 'aqualuxe' ),
            'caption' => __( 'Our research and development laboratory', 'aqualuxe' ),
        ),
        array(
            'url' => get_template_directory_uri() . '/assets/images/facilities/shipping-department.jpg',
            'title' => __( 'Shipping Department', 'aqualuxe' ),
            'caption' => __( 'Specialized packing and shipping area', 'aqualuxe' ),
        ),
    ),
);

// Merge defaults with args
$args = wp_parse_args( $args, $defaults );

// Extract variables
$title = $args['facilities_title'];
$content = $args['facilities_content'];
$gallery = $args['facilities_gallery'];

// Ensure we have gallery items
if ( empty( $gallery ) ) {
    $gallery = $defaults['facilities_gallery'];
}
?>

<section class="aqualuxe-about-facilities">
    <div class="aqualuxe-container">
        <div class="aqualuxe-section-header">
            <?php if ( ! empty( $title ) ) : ?>
                <h2 class="aqualuxe-section-title"><?php echo esc_html( $title ); ?></h2>
            <?php endif; ?>
        </div>
        
        <div class="aqualuxe-facilities-content">
            <?php echo wp_kses_post( $content ); ?>
        </div>
        
        <?php if ( ! empty( $gallery ) ) : ?>
            <div class="aqualuxe-facilities-gallery">
                <?php foreach ( $gallery as $image ) : ?>
                    <div class="aqualuxe-gallery-item">
                        <?php if ( ! empty( $image['url'] ) ) : ?>
                            <img src="<?php echo esc_url( $image['url'] ); ?>" alt="<?php echo esc_attr( $image['title'] ); ?>" />
                        <?php else : ?>
                            <div class="aqualuxe-gallery-placeholder">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
                            </div>
                        <?php endif; ?>
                        <div class="aqualuxe-gallery-caption">
                            <h3><?php echo esc_html( $image['title'] ); ?></h3>
                            <p><?php echo esc_html( $image['caption'] ); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>