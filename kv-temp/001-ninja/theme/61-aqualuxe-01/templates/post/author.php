<?php
/**
 * Template part for displaying post author
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Get author ID
$author_id = get_the_author_meta( 'ID' );

// Return if no author
if ( ! $author_id ) {
    return;
}

// Get author data
$author_name = get_the_author_meta( 'display_name', $author_id );
$author_description = get_the_author_meta( 'description', $author_id );
$author_url = get_author_posts_url( $author_id );
$author_avatar = get_avatar( $author_id, 100 );
?>

<div class="post-author">
    <div class="post-author-avatar">
        <?php echo $author_avatar; ?>
    </div>

    <div class="post-author-content">
        <h3 class="post-author-name">
            <a href="<?php echo esc_url( $author_url ); ?>">
                <?php echo esc_html( $author_name ); ?>
            </a>
        </h3>

        <?php if ( $author_description ) : ?>
            <div class="post-author-description">
                <?php echo wpautop( $author_description ); ?>
            </div>
        <?php endif; ?>

        <a href="<?php echo esc_url( $author_url ); ?>" class="post-author-link">
            <?php
            printf(
                /* translators: %s: author name */
                esc_html__( 'View all posts by %s', 'aqualuxe' ),
                esc_html( $author_name )
            );
            ?>
        </a>
    </div>
</div>