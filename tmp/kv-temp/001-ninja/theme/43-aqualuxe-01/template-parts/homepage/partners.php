<?php
/**
 * Template part for displaying the partners/brands section on the homepage
 *
 * @package AquaLuxe
 */

// Get partners section settings from customizer or use defaults
$section_title = get_theme_mod( 'aqualuxe_partners_title', __( 'Our Partners', 'aqualuxe' ) );
$section_description = get_theme_mod( 'aqualuxe_partners_description', __( 'We work with the best brands in the aquatic industry to provide you with top-quality products.', 'aqualuxe' ) );
$show_section = get_theme_mod( 'aqualuxe_partners_show', true );

// If section is disabled, return
if ( ! $show_section ) {
    return;
}

// Check if we have custom partners post type
$has_partners_cpt = post_type_exists( 'partner' );

// Define default partners if custom post type doesn't exist
$default_partners = array(
    array(
        'name' => 'AquaTech',
        'logo' => get_template_directory_uri() . '/assets/dist/images/partner-1.png',
        'url'  => '#',
    ),
    array(
        'name' => 'Marine World',
        'logo' => get_template_directory_uri() . '/assets/dist/images/partner-2.png',
        'url'  => '#',
    ),
    array(
        'name' => 'Reef Systems',
        'logo' => get_template_directory_uri() . '/assets/dist/images/partner-3.png',
        'url'  => '#',
    ),
    array(
        'name' => 'Aqua Flora',
        'logo' => get_template_directory_uri() . '/assets/dist/images/partner-4.png',
        'url'  => '#',
    ),
    array(
        'name' => 'Ocean Tech',
        'logo' => get_template_directory_uri() . '/assets/dist/images/partner-5.png',
        'url'  => '#',
    ),
    array(
        'name' => 'Aqua Filters',
        'logo' => get_template_directory_uri() . '/assets/dist/images/partner-6.png',
        'url'  => '#',
    ),
);
?>

<section class="partners-section py-16">
    <div class="container mx-auto px-4">
        <div class="section-header text-center mb-12">
            <?php if ( $section_title ) : ?>
                <h2 class="text-3xl md:text-4xl font-bold mb-4"><?php echo esc_html( $section_title ); ?></h2>
            <?php endif; ?>
            
            <?php if ( $section_description ) : ?>
                <p class="text-xl text-gray-600 dark:text-gray-400 max-w-3xl mx-auto"><?php echo esc_html( $section_description ); ?></p>
            <?php endif; ?>
        </div>
        
        <div class="partners-grid grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-8">
            <?php
            if ( $has_partners_cpt ) {
                // Get partners from custom post type
                $args = array(
                    'post_type'      => 'partner',
                    'posts_per_page' => 6,
                    'orderby'        => 'menu_order',
                    'order'          => 'ASC',
                );
                
                $partners_query = new WP_Query( $args );
                
                if ( $partners_query->have_posts() ) {
                    while ( $partners_query->have_posts() ) {
                        $partners_query->the_post();
                        
                        // Get partner URL
                        $partner_url = get_post_meta( get_the_ID(), 'partner_url', true );
                        if ( empty( $partner_url ) ) {
                            $partner_url = '#';
                        }
                        ?>
                        <div class="partner-item flex items-center justify-center p-4">
                            <a href="<?php echo esc_url( $partner_url ); ?>" class="partner-link" target="_blank" rel="noopener">
                                <?php if ( has_post_thumbnail() ) : ?>
                                    <?php the_post_thumbnail( 'medium', array( 'class' => 'partner-logo max-h-16 w-auto mx-auto grayscale hover:grayscale-0 transition-all' ) ); ?>
                                <?php else : ?>
                                    <span class="partner-name text-center font-medium"><?php the_title(); ?></span>
                                <?php endif; ?>
                            </a>
                        </div>
                        <?php
                    }
                    wp_reset_postdata();
                } else {
                    // Use default partners if no custom partners found
                    foreach ( $default_partners as $partner ) {
                        ?>
                        <div class="partner-item flex items-center justify-center p-4">
                            <a href="<?php echo esc_url( $partner['url'] ); ?>" class="partner-link" target="_blank" rel="noopener">
                                <img src="<?php echo esc_url( $partner['logo'] ); ?>" alt="<?php echo esc_attr( $partner['name'] ); ?>" class="partner-logo max-h-16 w-auto mx-auto grayscale hover:grayscale-0 transition-all">
                            </a>
                        </div>
                        <?php
                    }
                }
            } else {
                // Use default partners if custom post type doesn't exist
                foreach ( $default_partners as $partner ) {
                    ?>
                    <div class="partner-item flex items-center justify-center p-4">
                        <a href="<?php echo esc_url( $partner['url'] ); ?>" class="partner-link" target="_blank" rel="noopener">
                            <img src="<?php echo esc_url( $partner['logo'] ); ?>" alt="<?php echo esc_attr( $partner['name'] ); ?>" class="partner-logo max-h-16 w-auto mx-auto grayscale hover:grayscale-0 transition-all">
                        </a>
                    </div>
                    <?php
                }
            }
            ?>
        </div>
    </div>
</section>