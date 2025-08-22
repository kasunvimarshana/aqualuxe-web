<?php
/**
 * Template part for displaying posts in an archive
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Get blog layout
$blog_layout = aqualuxe_get_option( 'blog_layout', 'grid' );

// Get blog columns
$blog_columns = aqualuxe_get_option( 'blog_columns', '3' );

// Get excerpt length
$excerpt_length = aqualuxe_get_option( 'blog_excerpt_length', '55' );

// Get display options
$show_author = aqualuxe_get_option( 'blog_show_author', true );
$show_date = aqualuxe_get_option( 'blog_show_date', true );
$show_categories = aqualuxe_get_option( 'blog_show_categories', true );
$show_comments = aqualuxe_get_option( 'blog_show_comments', true );

// Post classes
$post_classes = [
    'archive-post',
    'layout-' . $blog_layout,
    'columns-' . $blog_columns,
];

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( $post_classes ); ?> <?php aqualuxe_attr( 'article' ); ?>>
    <?php if ( has_post_thumbnail() ) : ?>
        <div class="post-thumbnail">
            <a href="<?php the_permalink(); ?>">
                <?php the_post_thumbnail( 'medium_large', [ 'class' => 'post-thumbnail-image' ] ); ?>
            </a>
        </div>
    <?php endif; ?>

    <div class="post-content">
        <header class="entry-header">
            <?php if ( $show_categories && has_category() ) : ?>
                <div class="entry-categories">
                    <?php the_category( ', ' ); ?>
                </div>
            <?php endif; ?>

            <?php the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' ); ?>

            <div class="entry-meta">
                <?php if ( $show_author ) : ?>
                    <span class="entry-author">
                        <?php echo aqualuxe_get_icon( 'user' ); ?>
                        <a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>">
                            <?php echo esc_html( get_the_author() ); ?>
                        </a>
                    </span>
                <?php endif; ?>

                <?php if ( $show_date ) : ?>
                    <span class="entry-date">
                        <?php echo aqualuxe_get_icon( 'calendar' ); ?>
                        <time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
                            <?php echo esc_html( get_the_date() ); ?>
                        </time>
                    </span>
                <?php endif; ?>

                <?php if ( $show_comments && comments_open() ) : ?>
                    <span class="entry-comments">
                        <?php echo aqualuxe_get_icon( 'message-circle' ); ?>
                        <a href="<?php comments_link(); ?>">
                            <?php
                            printf(
                                _nx(
                                    '%s Comment',
                                    '%s Comments',
                                    get_comments_number(),
                                    'comments title',
                                    'aqualuxe'
                                ),
                                number_format_i18n( get_comments_number() )
                            );
                            ?>
                        </a>
                    </span>
                <?php endif; ?>
            </div>
        </header>

        <div class="entry-content">
            <?php echo aqualuxe_get_post_excerpt( get_the_ID(), $excerpt_length ); ?>
        </div>

        <div class="entry-footer">
            <a href="<?php the_permalink(); ?>" class="read-more-link">
                <?php echo esc_html__( 'Read More', 'aqualuxe' ); ?>
                <?php echo aqualuxe_get_icon( 'chevron-right' ); ?>
            </a>
        </div>
    </div>
</article>