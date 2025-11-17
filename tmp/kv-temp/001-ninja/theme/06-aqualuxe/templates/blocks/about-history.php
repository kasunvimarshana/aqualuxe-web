<?php
/**
 * About History Block
 *
 * @package AquaLuxe
 */

// Get args from template part
$args = $args ?? array();

// Default values
$defaults = array(
    'history_title' => __( 'Our History', 'aqualuxe' ),
    'history_content' => '<p>AquaLuxe was founded in 2010 by a group of passionate aquarium enthusiasts with a vision to provide the highest quality ornamental fish and aquarium supplies to hobbyists and professionals alike.</p>
<p>What started as a small breeding operation in a local facility has grown into one of the premier ornamental fish suppliers in the region, with state-of-the-art breeding facilities and a commitment to sustainable aquaculture practices.</p>
<p>Over the years, we have expanded our operations to include a wide range of freshwater and saltwater species, as well as a comprehensive selection of aquarium supplies and equipment. Our team has grown from just three founders to over 50 dedicated professionals who share our passion for aquatic life.</p>
<p>Today, AquaLuxe continues to innovate and lead the industry with our focus on quality, sustainability, and customer satisfaction. We remain committed to our founding principles while embracing new technologies and practices to ensure the health and well-being of our fish and the success of our customers\' aquariums.</p>',
    'history_image' => get_template_directory_uri() . '/assets/images/about-history.jpg',
);

// Merge defaults with args
$args = wp_parse_args( $args, $defaults );

// Extract variables
$title = $args['history_title'];
$content = $args['history_content'];
$image = $args['history_image'];

// Set default image if empty
if ( empty( $image ) ) {
    $image = get_template_directory_uri() . '/assets/images/about-history.jpg';
}
?>

<section class="aqualuxe-about-history">
    <div class="aqualuxe-container">
        <div class="aqualuxe-section-header">
            <?php if ( ! empty( $title ) ) : ?>
                <h2 class="aqualuxe-section-title"><?php echo esc_html( $title ); ?></h2>
            <?php endif; ?>
        </div>
        
        <div class="aqualuxe-about-history-content">
            <div class="aqualuxe-about-history-text">
                <?php echo wp_kses_post( $content ); ?>
            </div>
            
            <?php if ( ! empty( $image ) ) : ?>
                <div class="aqualuxe-about-history-image">
                    <img src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( $title ); ?>" />
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>