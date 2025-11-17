<?php
/**
 * Template part for displaying author bio
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

$author_id = get_the_author_meta( 'ID' );
$author_name = get_the_author_meta( 'display_name' );
$author_description = get_the_author_meta( 'description' );
$author_posts_url = get_author_posts_url( $author_id );
$author_avatar = get_avatar( $author_id, 100 );

if ( empty( $author_description ) ) {
    return;
}
?>

<div class="author-bio">
    <div class="author-avatar">
        <?php echo $author_avatar; ?>
    </div>
    <div class="author-info">
        <h3 class="author-title">
            <?php
            /* translators: %s: Author name */
            printf( esc_html__( 'About %s', 'aqualuxe' ), esc_html( $author_name ) );
            ?>
        </h3>
        <div class="author-description">
            <?php echo wpautop( $author_description ); ?>
        </div>
        <a class="author-link" href="<?php echo esc_url( $author_posts_url ); ?>">
            <?php
            /* translators: %s: Author name */
            printf( esc_html__( 'View all posts by %s', 'aqualuxe' ), esc_html( $author_name ) );
            ?>
        </a>
    </div>
</div><!-- .author-bio -->