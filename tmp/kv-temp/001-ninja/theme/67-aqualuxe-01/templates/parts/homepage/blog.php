<?php
/**
 * Template part for displaying homepage blog section
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Get blog settings
$title = get_theme_mod( 'aqualuxe_homepage_blog_title', __( 'Latest Articles', 'aqualuxe' ) );
$subtitle = get_theme_mod( 'aqualuxe_homepage_blog_subtitle', __( 'Our Blog', 'aqualuxe' ) );
$text = get_theme_mod( 'aqualuxe_homepage_blog_text', __( 'Stay updated with the latest news, tips, and insights from the world of aquatics.', 'aqualuxe' ) );
$count = get_theme_mod( 'aqualuxe_homepage_blog_count', 3 );
$button_text = get_theme_mod( 'aqualuxe_homepage_blog_button_text', __( 'View All Posts', 'aqualuxe' ) );
$button_url = get_theme_mod( 'aqualuxe_homepage_blog_button_url', '' );

// If button URL is empty, use blog page URL
if ( empty( $button_url ) ) {
    $button_url = get_permalink( get_option( 'page_for_posts' ) );
}

// Query arguments
$args = array(
    'post_type'           => 'post',
    'post_status'         => 'publish',
    'posts_per_page'      => $count,
    'ignore_sticky_posts' => true,
);

// Get posts
$posts = new WP_Query( $args );

// Check if posts exist
if ( $posts->have_posts() ) :
?>

<section class="homepage-blog">
    <div class="<?php echo esc_attr( aqualuxe_get_container_class() ); ?>">
        <div class="section-header">
            <?php if ( ! empty( $subtitle ) ) : ?>
                <div class="section-subtitle"><?php echo esc_html( $subtitle ); ?></div>
            <?php endif; ?>
            
            <?php if ( ! empty( $title ) ) : ?>
                <h2 class="section-title"><?php echo esc_html( $title ); ?></h2>
            <?php endif; ?>
            
            <?php if ( ! empty( $text ) ) : ?>
                <div class="section-text"><?php echo wp_kses_post( $text ); ?></div>
            <?php endif; ?>
        </div>
        
        <div class="blog-posts">
            <?php
            while ( $posts->have_posts() ) :
                $posts->the_post();
                ?>
                <article class="blog-post">
                    <?php if ( has_post_thumbnail() ) : ?>
                        <div class="post-thumbnail">
                            <a href="<?php the_permalink(); ?>">
                                <?php the_post_thumbnail( 'aqualuxe-blog-thumb' ); ?>
                            </a>
                        </div>
                    <?php endif; ?>
                    
                    <div class="post-content">
                        <div class="post-meta">
                            <span class="post-date"><?php echo get_the_date(); ?></span>
                            
                            <?php
                            $categories = get_the_category();
                            if ( ! empty( $categories ) ) :
                                ?>
                                <span class="post-category">
                                    <a href="<?php echo esc_url( get_category_link( $categories[0]->term_id ) ); ?>"><?php echo esc_html( $categories[0]->name ); ?></a>
                                </span>
                                <?php
                            endif;
                            ?>
                        </div>
                        
                        <h3 class="post-title">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h3>
                        
                        <div class="post-excerpt">
                            <?php echo wp_kses_post( wp_trim_words( get_the_excerpt(), 20 ) ); ?>
                        </div>
                        
                        <a href="<?php the_permalink(); ?>" class="read-more"><?php esc_html_e( 'Read More', 'aqualuxe' ); ?></a>
                    </div>
                </article>
                <?php
            endwhile;
            
            wp_reset_postdata();
            ?>
        </div>
        
        <?php if ( ! empty( $button_text ) && ! empty( $button_url ) ) : ?>
            <div class="section-footer">
                <a href="<?php echo esc_url( $button_url ); ?>" class="button"><?php echo esc_html( $button_text ); ?></a>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php
endif;