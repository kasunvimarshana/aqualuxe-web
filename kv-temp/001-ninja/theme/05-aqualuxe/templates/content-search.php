<?php
/**
 * Template part for displaying results in search pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <?php
    // Featured image
    if ( has_post_thumbnail() && get_theme_mod( 'aqualuxe_search_featured_image', true ) ) {
        ?>
        <div class="post-thumbnail">
            <a href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
                <?php
                the_post_thumbnail(
                    'thumbnail',
                    array(
                        'alt' => the_title_attribute(
                            array(
                                'echo' => false,
                            )
                        ),
                        'class' => 'featured-image',
                    )
                );
                ?>
            </a>
        </div><!-- .post-thumbnail -->
        <?php
    }
    ?>

    <div class="post-content">
        <header class="entry-header">
            <?php
            // Post type
            $post_type = get_post_type();
            $post_type_obj = get_post_type_object( $post_type );
            
            if ( $post_type_obj && $post_type !== 'post' && $post_type !== 'page' ) {
                echo '<span class="post-type">' . esc_html( $post_type_obj->labels->singular_name ) . '</span>';
            }
            
            // Post title
            the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
            
            // Post meta
            if ( 'post' === $post_type ) {
                aqualuxe_entry_meta();
            }
            ?>
        </header><!-- .entry-header -->

        <div class="entry-summary">
            <?php the_excerpt(); ?>
        </div><!-- .entry-summary -->

        <footer class="entry-footer">
            <?php
            // Read more link
            if ( get_theme_mod( 'aqualuxe_search_show_read_more', true ) ) {
                echo '<a href="' . esc_url( get_permalink() ) . '" class="read-more">' . esc_html__( 'Read More', 'aqualuxe' ) . ' <i class="fa fa-angle-right"></i></a>';
            }
            ?>
        </footer><!-- .entry-footer -->
    </div><!-- .post-content -->
</article><!-- #post-<?php the_ID(); ?> -->