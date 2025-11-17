<?php
/**
 * Template part for displaying related posts
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Get related posts
$related_posts = aqualuxe_get_related_posts();

// Return if no related posts
if ( empty( $related_posts ) ) {
    return;
}
?>

<div class="related-posts">
    <h3 class="related-posts-title"><?php esc_html_e( 'Related Posts', 'aqualuxe' ); ?></h3>

    <div class="related-posts-grid">
        <?php foreach ( $related_posts as $related_post ) : ?>
            <div class="related-post">
                <?php if ( has_post_thumbnail( $related_post->ID ) ) : ?>
                    <div class="related-post-thumbnail">
                        <a href="<?php echo esc_url( get_permalink( $related_post->ID ) ); ?>">
                            <?php echo get_the_post_thumbnail( $related_post->ID, 'aqualuxe-thumbnail' ); ?>
                        </a>
                    </div>
                <?php endif; ?>

                <h4 class="related-post-title">
                    <a href="<?php echo esc_url( get_permalink( $related_post->ID ) ); ?>">
                        <?php echo esc_html( get_the_title( $related_post->ID ) ); ?>
                    </a>
                </h4>

                <div class="related-post-meta">
                    <?php echo aqualuxe_get_post_date( $related_post->ID ); ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>