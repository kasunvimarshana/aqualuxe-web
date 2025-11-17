<?php
/**
 * About Page Partners Section
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Get partners settings from customizer or ACF
$section_title = get_theme_mod( 'aqualuxe_about_partners_title', __( 'Our Partners', 'aqualuxe' ) );
$section_subtitle = get_theme_mod( 'aqualuxe_about_partners_subtitle', __( 'Companies we proudly work with', 'aqualuxe' ) );
$columns = get_theme_mod( 'aqualuxe_about_partners_columns', 5 );
$show_grayscale = get_theme_mod( 'aqualuxe_about_partners_grayscale', true );

// Default partners if not set in customizer
$default_partners = array(
    array(
        'name'  => 'Partner 1',
        'logo'  => get_template_directory_uri() . '/assets/images/about/partners/partner-1.png',
        'url'   => '#',
    ),
    array(
        'name'  => 'Partner 2',
        'logo'  => get_template_directory_uri() . '/assets/images/about/partners/partner-2.png',
        'url'   => '#',
    ),
    array(
        'name'  => 'Partner 3',
        'logo'  => get_template_directory_uri() . '/assets/images/about/partners/partner-3.png',
        'url'   => '#',
    ),
    array(
        'name'  => 'Partner 4',
        'logo'  => get_template_directory_uri() . '/assets/images/about/partners/partner-4.png',
        'url'   => '#',
    ),
    array(
        'name'  => 'Partner 5',
        'logo'  => get_template_directory_uri() . '/assets/images/about/partners/partner-5.png',
        'url'   => '#',
    ),
    array(
        'name'  => 'Partner 6',
        'logo'  => get_template_directory_uri() . '/assets/images/about/partners/partner-6.png',
        'url'   => '#',
    ),
    array(
        'name'  => 'Partner 7',
        'logo'  => get_template_directory_uri() . '/assets/images/about/partners/partner-7.png',
        'url'   => '#',
    ),
    array(
        'name'  => 'Partner 8',
        'logo'  => get_template_directory_uri() . '/assets/images/about/partners/partner-8.png',
        'url'   => '#',
    ),
    array(
        'name'  => 'Partner 9',
        'logo'  => get_template_directory_uri() . '/assets/images/about/partners/partner-9.png',
        'url'   => '#',
    ),
    array(
        'name'  => 'Partner 10',
        'logo'  => get_template_directory_uri() . '/assets/images/about/partners/partner-10.png',
        'url'   => '#',
    ),
);

// Get partners from customizer or use defaults
$partners = array();
for ( $i = 1; $i <= 10; $i++ ) {
    $partner_name = get_theme_mod( 'aqualuxe_about_partner_' . $i . '_name', $default_partners[$i-1]['name'] );
    $partner_logo = get_theme_mod( 'aqualuxe_about_partner_' . $i . '_logo', $default_partners[$i-1]['logo'] );
    $partner_url = get_theme_mod( 'aqualuxe_about_partner_' . $i . '_url', $default_partners[$i-1]['url'] );
    
    if ( $partner_name && $partner_logo ) {
        $partners[] = array(
            'name' => $partner_name,
            'logo' => $partner_logo,
            'url'  => $partner_url,
        );
    }
}

// Skip if no partners
if ( empty( $partners ) ) {
    return;
}

// Partners grid classes
$grid_classes = array( 'partners-grid', 'columns-' . $columns );
if ( $show_grayscale ) {
    $grid_classes[] = 'grayscale';
}
?>

<section class="partners-section section">
    <div class="container">
        <div class="section-header text-center">
            <?php if ( $section_title ) : ?>
                <h2 class="section-title"><?php echo esc_html( $section_title ); ?></h2>
            <?php endif; ?>
            
            <?php if ( $section_subtitle ) : ?>
                <div class="section-subtitle">
                    <p><?php echo wp_kses_post( $section_subtitle ); ?></p>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="<?php echo esc_attr( implode( ' ', $grid_classes ) ); ?>">
            <?php foreach ( $partners as $partner ) : ?>
                <div class="partner-item">
                    <?php if ( $partner['url'] ) : ?>
                        <a href="<?php echo esc_url( $partner['url'] ); ?>" target="_blank" rel="noopener noreferrer" class="partner-link">
                            <img src="<?php echo esc_url( $partner['logo'] ); ?>" alt="<?php echo esc_attr( $partner['name'] ); ?>" class="partner-logo">
                        </a>
                    <?php else : ?>
                        <img src="<?php echo esc_url( $partner['logo'] ); ?>" alt="<?php echo esc_attr( $partner['name'] ); ?>" class="partner-logo">
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>