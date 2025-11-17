<?php
/**
 * Homepage Blog/News Section
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Get blog settings from customizer or use defaults
$blog_title = get_theme_mod( 'aqualuxe_blog_title', 'Latest Articles & News' );
$blog_subtitle = get_theme_mod( 'aqualuxe_blog_subtitle', 'Stay updated with our latest aquatic insights and care guides' );
$blog_count = get_theme_mod( 'aqualuxe_blog_count', 3 );

// Get latest posts
$args = array(
    'post_type'      => 'post',
    'posts_per_page' => $blog_count,
    'post_status'    => 'publish',
);

$latest_posts = new WP_Query( $args );

// Return if no posts found
if ( ! $latest_posts->have_posts() ) {
    return;
}
?>

<section class="blog-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title"><?php echo esc_html( $blog_title ); ?></h2>
            <div class="section-subtitle"><?php echo wp_kses_post( $blog_subtitle ); ?></div>
        </div>
        
        <div class="blog-grid">
            <?php
            while ( $latest_posts->have_posts() ) :
                $latest_posts->the_post();
                ?>
                <article class="blog-item">
                    <div class="blog-inner">
                        <div class="blog-image">
                            <a href="<?php the_permalink(); ?>">
                                <?php 
                                if ( has_post_thumbnail() ) {
                                    the_post_thumbnail( 'medium_large' );
                                } else {
                                    echo '<img src="' . esc_url( get_template_directory_uri() . '/demo-content/images/blog-placeholder.jpg' ) . '" alt="' . esc_attr( get_the_title() ) . '">';
                                }
                                ?>
                            </a>
                        </div>
                        
                        <div class="blog-content">
                            <div class="blog-meta">
                                <span class="blog-date"><?php echo get_the_date(); ?></span>
                                <?php
                                $categories = get_the_category();
                                if ( ! empty( $categories ) ) {
                                    echo '<span class="blog-category">';
                                    echo '<a href="' . esc_url( get_category_link( $categories[0]->term_id ) ) . '">' . esc_html( $categories[0]->name ) . '</a>';
                                    echo '</span>';
                                }
                                ?>
                            </div>
                            
                            <h3 class="blog-title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h3>
                            
                            <div class="blog-excerpt">
                                <?php the_excerpt(); ?>
                            </div>
                            
                            <a href="<?php the_permalink(); ?>" class="read-more"><?php esc_html_e( 'Read More', 'aqualuxe' ); ?> <span class="icon-arrow-right"></span></a>
                        </div>
                    </div>
                </article>
            <?php
            endwhile;
            wp_reset_postdata();
            ?>
        </div>
        
        <div class="section-footer">
            <a href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ); ?>" class="btn btn-primary"><?php esc_html_e( 'View All Articles', 'aqualuxe' ); ?></a>
        </div>
    </div>
</section>