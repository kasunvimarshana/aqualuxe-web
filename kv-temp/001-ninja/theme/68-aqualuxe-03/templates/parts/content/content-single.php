<?php
/**
 * Template part for displaying single posts
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
$show_categories = get_theme_mod( 'aqualuxe_show_categories', true );
$show_tags = get_theme_mod( 'aqualuxe_show_tags', true );
$show_social_share = get_theme_mod( 'aqualuxe_show_social_share', true );
$social_share_networks = get_theme_mod( 'aqualuxe_social_share_networks', [ 'facebook', 'twitter', 'linkedin', 'pinterest', 'email' ] );
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <header class="entry-header">
        <?php
        if ( $show_categories && has_category() ) :
            ?>
            <div class="entry-categories">
                <?php the_category( ' ' ); ?>
            </div>
            <?php
        endif;

        the_title( '<h1 class="entry-title">', '</h1>' );

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
                        echo '<time datetime="' . esc_attr( get_the_date( 'c' ) ) . '">' . esc_html( get_the_date() ) . '</time>';
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

    <?php if ( has_post_thumbnail() && $show_featured_image ) : ?>
        <div class="post-thumbnail">
            <?php the_post_thumbnail( 'aqualuxe-featured' ); ?>
        </div>
    <?php endif; ?>

    <div class="entry-content">
        <?php
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

        if ( $show_social_share ) :
            $share_links = \AquaLuxe\Helpers\Utils::get_social_share_links( get_the_ID(), $social_share_networks );
            if ( ! empty( $share_links ) ) :
                ?>
                <div class="social-share">
                    <h3><?php esc_html_e( 'Share This Post', 'aqualuxe' ); ?></h3>
                    <ul class="share-links">
                        <?php foreach ( $share_links as $network => $link ) : ?>
                            <li class="share-<?php echo esc_attr( $network ); ?>">
                                <a href="<?php echo esc_url( $link['url'] ); ?>" target="_blank" rel="noopener noreferrer">
                                    <span class="screen-reader-text"><?php echo esc_html( $link['label'] ); ?></span>
                                    <i class="icon-<?php echo esc_attr( $link['icon'] ); ?>"></i>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php
            endif;
        endif;

        // Author bio
        if ( $show_author ) :
            get_template_part( 'templates/parts/content/author-bio' );
        endif;
        ?>
    </footer><!-- .entry-footer -->
</article><!-- #post-<?php the_ID(); ?> -->