<?php
/**
 * Template part for displaying content before the header
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Check if maintenance mode is enabled
$maintenance_mode = get_theme_mod( 'aqualuxe_maintenance_mode', false );
$maintenance_message = get_theme_mod( 'aqualuxe_maintenance_message', __( 'We are currently undergoing scheduled maintenance. Please check back soon.', 'aqualuxe' ) );

if ( $maintenance_mode && ! current_user_can( 'manage_options' ) ) :
    ?>
    <div class="maintenance-mode">
        <div class="maintenance-container">
            <div class="maintenance-content">
                <?php
                $logo = \AquaLuxe\Helpers\Utils::get_theme_logo();
                if ( $logo ) :
                    ?>
                    <div class="site-logo">
                        <img src="<?php echo esc_url( $logo ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
                    </div>
                    <?php
                else :
                    ?>
                    <h1 class="site-title"><?php bloginfo( 'name' ); ?></h1>
                    <?php
                endif;
                ?>
                <div class="maintenance-message">
                    <?php echo wp_kses_post( wpautop( $maintenance_message ) ); ?>
                </div>
            </div>
        </div>
    </div>
    <?php
    exit;
endif;

// Preloader
$preloader = get_theme_mod( 'aqualuxe_preloader', true );
if ( $preloader ) :
    ?>
    <div id="preloader" class="preloader">
        <div class="preloader-inner">
            <div class="preloader-icon"></div>
        </div>
    </div>
    <?php
endif;

// Schema.org markup
do_action( 'aqualuxe_schema_org_markup' );

// Open Graph tags
do_action( 'aqualuxe_open_graph_tags' );