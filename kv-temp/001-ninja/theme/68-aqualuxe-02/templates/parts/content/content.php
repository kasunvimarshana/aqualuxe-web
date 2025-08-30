<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

$blog_layout = get_theme_mod( 'aqualuxe_blog_layout', 'grid' );
$show_featured_image = get_theme_mod( 'aqualuxe_show_featured_image', true );
$show_post_meta = get_theme_mod( 'aqualuxe_show_post_meta', true );
$show_author = get_theme_mod( 'aqualuxe_show_author', true );
$show_date = get_theme_mod( 'aqualuxe_show_date', true );
$show_categories = get_theme_mod( 'aqualuxe_show_categories', true );
$show_tags = get_theme_mod( 'aqualuxe_show_tags', true );
$show_comments_count = get_theme_mod( 'aqualuxe_show_comments_count', true );
$read_more_text = get_theme_mod( 'aqualuxe_read_more_text', __( 'Read More', 'aqualuxe' ) );

$post_classes = array( 'post-item' );
$post_classes[] = 'layout-' . $blog_layout;

if ( $blog_layout === 'grid' || $blog_layout === 'masonry' ) {
    $columns = get_theme_mod( 'aqualuxe_blog_columns', '3' );
    $post_classes[] = 'columns-' . $columns;
}

if ( has_post_thumbnail() && $show_featured_image ) {
    $post_classes[] = 'has-thumbnail';
} else {
    $post_classes[] = 'no-thumbnail';
}
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( $post_classes ); ?>>
    <?php if ( has_post_thumbnail() && $show_featured_image ) : ?>
        <div class="post-thumbnail">
            <a href="<?php the_permalink(); ?>">
                <?php the_post_thumbnail( 'aqualuxe-featured' ); ?>
            </a>
        </div>
    <?php endif; ?>

    <div class="post-content">
        <header class="entry-header">
            <?php
            if ( $show_categories && has_category() ) :
                ?>
                <div class="entry-categories">
                    <?php the_category( ' ' ); ?>
                </div>
                <?php
            endif;

            if ( is_singular() ) :
                the_title( '<h1 class="entry-title">', '</h1>' );
            else :
                the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
            endif;

            if ( $show_post_meta ) :
                ?>
                <div class="entry-meta">
                    <?php
                    if ( $show_author ) :
                        ?>
                        <span class="author">
                            <?php
                            echo get_avatar( get_the_author_meta( 'ID' ), 32 );
                            ?>
                            <span class="author-name">
                                <?php
                                echo esc_html__( 'By ', 'aqualuxe' );
                                the_author_posts_link();
                                ?>
                            </span>
                        </span>
                        <?php
                    endif;

                    if ( $show_date ) :
                        ?>
                        <span class="posted-on">
                            <?php
                            echo esc_html__( 'Posted on ', 'aqualuxe' );
                            echo '<a href="' . esc_url( get_permalink() ) . '">';
                            echo '<time datetime="' . esc_attr( get_the_date( 'c' ) ) . '">' . esc_html( get_the_date() ) . '</time>';
                            echo '</a>';
                            ?>
                        </span>
                        <?php
                    endif;

                    if ( $show_comments_count && comments_open() ) :
                        ?>
                        <span class="comments-link">
                            <?php
                            comments_popup_link(
                                sprintf(
                                    wp_kses(
                                        /* translators: %s: post title */
                                        __( 'Leave a Comment<span class="screen-reader-text"> on %s</span>', 'aqualuxe' ),
                                        array(
                                            'span' => array(
                                                'class' => array(),
                                            ),
                                        )
                                    ),
                                    wp_kses_post( get_the_title() )
                                )
                            );
                            ?>
                        </span>
                        <?php
                    endif;
                    ?>
                </div><!-- .entry-meta -->
                <?php
            endif;
            ?>
        </header><!-- .entry-header -->

        <div class="entry-content">
            <?php
            if ( is_singular() ) :
                the_content(
                    sprintf(
                        wp_kses(
                            /* translators: %s: Name of current post. Only visible to screen readers */
                            __( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'aqualuxe' ),
                            array(
                                'span' => array(
                                    'class' => array(),
                                ),
                            )
                        ),
                        wp_kses_post( get_the_title() )
                    )
                );

                wp_link_pages(
                    array(
                        'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'aqualuxe' ),
                        'after'  => '</div>',
                    )
                );
            else :
                the_excerpt();
                ?>
                <div class="read-more">
                    <a href="<?php the_permalink(); ?>" class="button"><?php echo esc_html( $read_more_text ); ?></a>
                </div>
                <?php
            endif;
            ?>
        </div><!-- .entry-content -->

        <footer class="entry-footer">
            <?php
            if ( $show_tags && has_tag() ) :
                ?>
                <div class="entry-tags">
                    <?php the_tags( '<span class="tags-links">', ' ', '</span>' ); ?>
                </div>
                <?php
            endif;
            ?>
        </footer><!-- .entry-footer -->
    </div><!-- .post-content -->
</article><!-- #post-<?php the_ID(); ?> -->