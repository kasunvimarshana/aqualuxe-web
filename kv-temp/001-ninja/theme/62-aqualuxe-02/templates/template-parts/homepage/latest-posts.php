<?php
/**
 * Homepage Latest Posts Section
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Get latest posts settings from customizer or ACF
$section_title = get_theme_mod( 'aqualuxe_latest_posts_title', __( 'Latest from Our Blog', 'aqualuxe' ) );
$section_subtitle = get_theme_mod( 'aqualuxe_latest_posts_subtitle', __( 'Stay updated with our latest news and articles', 'aqualuxe' ) );
$posts_count = get_theme_mod( 'aqualuxe_latest_posts_count', 3 );
$columns = get_theme_mod( 'aqualuxe_latest_posts_columns', 3 );
$show_excerpt = get_theme_mod( 'aqualuxe_latest_posts_show_excerpt', true );
$excerpt_length = get_theme_mod( 'aqualuxe_latest_posts_excerpt_length', 20 );
$show_date = get_theme_mod( 'aqualuxe_latest_posts_show_date', true );
$show_author = get_theme_mod( 'aqualuxe_latest_posts_show_author', true );
$show_categories = get_theme_mod( 'aqualuxe_latest_posts_show_categories', true );
$view_all_text = get_theme_mod( 'aqualuxe_latest_posts_view_all_text', __( 'View All Posts', 'aqualuxe' ) );
$view_all_url = get_theme_mod( 'aqualuxe_latest_posts_view_all_url', get_permalink( get_option( 'page_for_posts' ) ) );

// Get latest posts
$args = array(
    'post_type'           => 'post',
    'post_status'         => 'publish',
    'ignore_sticky_posts' => 1,
    'posts_per_page'      => $posts_count,
);

$latest_posts = new WP_Query( $args );

// Skip if no posts
if ( ! $latest_posts->have_posts() ) {
    return;
}
?>

<section class="latest-posts-section section">
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
        
        <div class="posts-grid columns-<?php echo esc_attr( $columns ); ?>">
            <?php while ( $latest_posts->have_posts() ) : $latest_posts->the_post(); ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class( 'post-item' ); ?>>
                    <?php if ( has_post_thumbnail() ) : ?>
                        <div class="post-thumbnail">
                            <a href="<?php the_permalink(); ?>">
                                <?php the_post_thumbnail( 'medium_large' ); ?>
                            </a>
                        </div>
                    <?php endif; ?>
                    
                    <div class="post-content">
                        <?php if ( $show_categories ) : ?>
                            <div class="post-categories">
                                <?php the_category( ', ' ); ?>
                            </div>
                        <?php endif; ?>
                        
                        <h3 class="post-title">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h3>
                        
                        <?php if ( $show_excerpt ) : ?>
                            <div class="post-excerpt">
                                <?php 
                                if ( has_excerpt() ) {
                                    echo wp_kses_post( wp_trim_words( get_the_excerpt(), $excerpt_length, '...' ) );
                                } else {
                                    echo wp_kses_post( wp_trim_words( get_the_content(), $excerpt_length, '...' ) );
                                }
                                ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="post-meta">
                            <?php if ( $show_date ) : ?>
                                <span class="post-date">
                                    <i class="icon-calendar"></i>
                                    <?php echo get_the_date(); ?>
                                </span>
                            <?php endif; ?>
                            
                            <?php if ( $show_author ) : ?>
                                <span class="post-author">
                                    <i class="icon-user"></i>
                                    <?php the_author(); ?>
                                </span>
                            <?php endif; ?>
                        </div>
                        
                        <div class="post-link">
                            <a href="<?php the_permalink(); ?>" class="read-more"><?php esc_html_e( 'Read More', 'aqualuxe' ); ?> <i class="icon-arrow-right"></i></a>
                        </div>
                    </div>
                </article>
            <?php endwhile; ?>
        </div>
        
        <?php if ( $view_all_text && $view_all_url ) : ?>
            <div class="section-footer text-center">
                <a href="<?php echo esc_url( $view_all_url ); ?>" class="btn btn-outline-primary"><?php echo esc_html( $view_all_text ); ?></a>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php
wp_reset_postdata();