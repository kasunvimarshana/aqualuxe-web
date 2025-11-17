<?php
/**
 * The template for displaying the front page
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

get_header();

// Check if we're showing the static front page or the blog
if ( get_option( 'show_on_front' ) === 'page' && is_front_page() && ! is_home() ) :
    
    // Get homepage sections and their order from customizer
    $sections = array(
        'hero' => get_theme_mod( 'aqualuxe_homepage_hero_enabled', true ),
        'features' => get_theme_mod( 'aqualuxe_homepage_features_enabled', true ),
        'about' => get_theme_mod( 'aqualuxe_homepage_about_enabled', true ),
        'services' => get_theme_mod( 'aqualuxe_homepage_services_enabled', true ),
        'portfolio' => get_theme_mod( 'aqualuxe_homepage_portfolio_enabled', false ),
        'testimonials' => get_theme_mod( 'aqualuxe_homepage_testimonials_enabled', true ),
        'team' => get_theme_mod( 'aqualuxe_homepage_team_enabled', false ),
        'blog' => get_theme_mod( 'aqualuxe_homepage_blog_enabled', true ),
        'cta' => get_theme_mod( 'aqualuxe_homepage_cta_enabled', true ),
    );
    
    // Get section order
    $section_order = get_theme_mod( 'aqualuxe_homepage_section_order', array(
        'hero',
        'features',
        'about',
        'services',
        'portfolio',
        'testimonials',
        'team',
        'blog',
        'cta',
    ));
    
    // Display sections according to order
    foreach ( $section_order as $section ) {
        if ( isset( $sections[$section] ) && $sections[$section] ) {
            get_template_part( 'template-parts/homepage/' . $section );
        }
    }
    
    // If page content should be displayed
    $show_content = get_theme_mod( 'aqualuxe_homepage_content_enabled', false );
    
    if ( $show_content ) :
        ?>
        <div class="container mx-auto px-4 py-12">
            <div class="max-w-4xl mx-auto">
                <?php
                while ( have_posts() ) :
                    the_post();
                    
                    get_template_part( 'template-parts/content/content', 'page' );
                    
                endwhile; // End of the loop.
                ?>
            </div>
        </div>
        <?php
    endif;

else :
    // If showing blog posts on the front page, use the index template
    get_template_part( 'index' );
endif;

get_footer();