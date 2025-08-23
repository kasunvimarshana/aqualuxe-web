<?php
/**
 * Template part for displaying results in search pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

$show_featured_image = get_theme_mod( 'aqualuxe_show_featured_image', true );
$show_post_meta = get_theme_mod( 'aqualuxe_show_post_meta', true );
$show_author = get_theme_mod( 'aqualuxe_show_author', true );
$show_date = get_theme_mod( 'aqualuxe_show_date', true );
$read_more_text = get_theme_mod( 'aqualuxe_read_more_text', __( 'Read More', 'aqualuxe' ) );
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'search-result-item' ); ?>>
    <?php if ( has_post_thumbnail() && $show_featured_image ) : ?>
        <div class="post-thumbnail">
            <a href="<?php the_permalink(); ?>">
                <?php the_post_thumbnail( 'aqualuxe-thumbnail' ); ?>
            </a>
        </div>
    <?php endif; ?>

    <div class="post-content">
        <header class="entry-header">
            <?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>

            <?php if ( 'post' === get_post_type() && $show_post_meta ) : ?>
                <div class="entry-meta">
                    <?php
                    if ( $show_author ) :
                        ?>
                        <span class="author">
                            <?php
                            echo esc_html__( 'By ', 'aqualuxe' );
                            the_author_posts_link();
                            ?>
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
                    ?>
                </div><!-- .entry-meta -->
            <?php endif; ?>
        </header><!-- .entry-header -->

        <div class="entry-summary">
            <?php the_excerpt(); ?>
            <div class="read-more">
                <a href="<?php the_permalink(); ?>" class="button"><?php echo esc_html( $read_more_text ); ?></a>
            </div>
        </div><!-- .entry-summary -->

        <footer class="entry-footer">
            <?php
            $post_type = get_post_type();
            echo '<span class="post-type">' . esc_html( get_post_type_object( $post_type )->labels->singular_name ) . '</span>';
            ?>
        </footer><!-- .entry-footer -->
    </div><!-- .post-content -->
</article><!-- #post-<?php the_ID(); ?> -->