<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

get_header(); ?>

<main id="primary" class="site-main" role="main">
    <?php if ( have_posts() ) : ?>
        <?php while ( have_posts() ) : the_post(); ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <header class="entry-header">
                    <?php
                    if ( is_singular() ) :
                        the_title( '<h1 class="entry-title">', '</h1>' );
                    else :
                        the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
                    endif;
                    ?>
                </header>

                <div class="entry-content">
                    <?php
                    if ( is_singular() ) :
                        the_content();
                    else :
                        the_excerpt();
                    endif;
                    ?>
                </div>

                <footer class="entry-footer">
                    <?php
                    if ( ! is_singular() ) :
                        echo '<a href="' . esc_url( get_permalink() ) . '">' . esc_html__( 'Read More', 'aqualuxe' ) . '</a>';
                    endif;
                    ?>
                </footer>
            </article>
        <?php endwhile; ?>

        <?php
        the_posts_navigation( array(
            'prev_text' => esc_html__( 'Older posts', 'aqualuxe' ),
            'next_text' => esc_html__( 'Newer posts', 'aqualuxe' ),
        ) );
        ?>

    <?php else : ?>
        <section class="no-results not-found">
            <header class="page-header">
                <h1 class="page-title"><?php esc_html_e( 'Nothing here', 'aqualuxe' ); ?></h1>
            </header>

            <div class="page-content">
                <?php if ( is_home() && current_user_can( 'publish_posts' ) ) : ?>
                    <p>
                        <?php
                        printf(
                            wp_kses(
                                __( 'Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'aqualuxe' ),
                                array(
                                    'a' => array(
                                        'href' => array(),
                                    ),
                                )
                            ),
                            esc_url( admin_url( 'post-new.php' ) )
                        );
                        ?>
                    </p>
                <?php else : ?>
                    <p><?php esc_html_e( 'It looks like nothing was found at this location. Maybe try a search?', 'aqualuxe' ); ?></p>
                    <?php get_search_form(); ?>
                <?php endif; ?>
            </div>
        </section>
    <?php endif; ?>
</main>

<?php
get_sidebar();
get_footer();