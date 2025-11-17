<?php
/**
 * Template part for displaying featured content on the front page when WooCommerce is not active
 *
 * @package AquaLuxe
 */

// Get section settings from customizer or default values
$section_title = get_theme_mod( 'aqualuxe_featured_content_title', __( 'Featured Content', 'aqualuxe' ) );
$section_description = get_theme_mod( 'aqualuxe_featured_content_description', __( 'Discover our collection of premium aquatic products and services.', 'aqualuxe' ) );
$number_of_posts = get_theme_mod( 'aqualuxe_featured_content_count', 3 );
$columns = get_theme_mod( 'aqualuxe_featured_content_columns', 3 );
$content_type = get_theme_mod( 'aqualuxe_featured_content_type', 'post' );
$category_id = get_theme_mod( 'aqualuxe_featured_content_category', 0 );
$view_all_text = get_theme_mod( 'aqualuxe_featured_content_view_all_text', __( 'View All', 'aqualuxe' ) );
$view_all_url = get_theme_mod( 'aqualuxe_featured_content_view_all_url', get_permalink( get_option( 'page_for_posts' ) ) );

// Section classes
$section_classes = array(
    'section',
    'featured-content-section',
);

// Check if we should show the section
$show_section = get_theme_mod( 'aqualuxe_show_featured_content', true );

if ( ! $show_section ) {
    return;
}

// Query arguments
$args = array(
    'post_type'      => $content_type,
    'posts_per_page' => $number_of_posts,
    'orderby'        => 'date',
    'order'          => 'DESC',
    'post_status'    => 'publish',
);

// Add category filter if specified
if ( $category_id > 0 && $content_type === 'post' ) {
    $args['cat'] = $category_id;
}

// Get posts
$featured_posts = new WP_Query( $args );
?>

<section id="featured-content" class="<?php echo esc_attr( implode( ' ', $section_classes ) ); ?>">
    <div class="container">
        <div class="section-header">
            <?php if ( $section_title ) : ?>
                <h2 class="section-title"><?php echo esc_html( $section_title ); ?></h2>
            <?php endif; ?>
            
            <?php if ( $section_description ) : ?>
                <div class="section-description"><?php echo wp_kses_post( $section_description ); ?></div>
            <?php endif; ?>
        </div>
        
        <?php if ( $featured_posts->have_posts() ) : ?>
            <div class="section-content">
                <div class="featured-content-grid grid-cols-<?php echo esc_attr( $columns ); ?>">
                    <?php while ( $featured_posts->have_posts() ) : $featured_posts->the_post(); ?>
                        <div class="featured-content-item">
                            <div class="featured-content-inner">
                                <?php if ( has_post_thumbnail() ) : ?>
                                    <div class="featured-content-thumbnail">
                                        <a href="<?php the_permalink(); ?>">
                                            <?php the_post_thumbnail( 'medium_large', array( 'class' => 'featured-content-image' ) ); ?>
                                        </a>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="featured-content-details">
                                    <h3 class="featured-content-title">
                                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                    </h3>
                                    
                                    <div class="featured-content-excerpt">
                                        <?php the_excerpt(); ?>
                                    </div>
                                    
                                    <a href="<?php the_permalink(); ?>" class="button button-text"><?php esc_html_e( 'Read More', 'aqualuxe' ); ?></a>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
            
            <?php wp_reset_postdata(); ?>
            
            <?php if ( $view_all_text && $view_all_url ) : ?>
                <div class="section-footer">
                    <a href="<?php echo esc_url( $view_all_url ); ?>" class="button"><?php echo esc_html( $view_all_text ); ?></a>
                </div>
            <?php endif; ?>
            
        <?php else : ?>
            <div class="section-content">
                <p><?php esc_html_e( 'No featured content found.', 'aqualuxe' ); ?></p>
            </div>
        <?php endif; ?>
    </div>
</section>