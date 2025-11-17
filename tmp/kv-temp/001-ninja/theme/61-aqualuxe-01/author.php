<?php
/**
 * The template for displaying author archives
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

get_header();
?>

<main id="primary" class="site-main">
    <div class="container">
        <?php if ( have_posts() ) : ?>

            <header class="page-header author-header">
                <?php
                $author_id = get_the_author_meta( 'ID' );
                ?>
                <div class="author-info">
                    <div class="author-avatar">
                        <?php echo get_avatar( $author_id, 120 ); ?>
                    </div>
                    <div class="author-bio">
                        <h1 class="page-title">
                            <?php
                            /* translators: %s: Author name */
                            printf( esc_html__( 'Author: %s', 'aqualuxe' ), '<span class="vcard">' . get_the_author() . '</span>' );
                            ?>
                        </h1>
                        <?php if ( get_the_author_meta( 'description' ) ) : ?>
                            <div class="author-description">
                                <?php echo wp_kses_post( get_the_author_meta( 'description' ) ); ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="author-meta">
                            <?php if ( get_the_author_meta( 'user_url' ) ) : ?>
                                <div class="author-website">
                                    <i class="fas fa-globe"></i>
                                    <a href="<?php echo esc_url( get_the_author_meta( 'user_url' ) ); ?>" target="_blank" rel="nofollow">
                                        <?php esc_html_e( 'Website', 'aqualuxe' ); ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                            
                            <?php
                            // Social media links
                            $social_profiles = array(
                                'twitter'   => array( 'icon' => 'fab fa-twitter', 'label' => 'Twitter' ),
                                'facebook'  => array( 'icon' => 'fab fa-facebook', 'label' => 'Facebook' ),
                                'instagram' => array( 'icon' => 'fab fa-instagram', 'label' => 'Instagram' ),
                                'linkedin'  => array( 'icon' => 'fab fa-linkedin', 'label' => 'LinkedIn' ),
                            );
                            
                            foreach ( $social_profiles as $profile_key => $profile_data ) :
                                $profile_url = get_the_author_meta( $profile_key );
                                if ( $profile_url ) :
                            ?>
                                <div class="author-social">
                                    <i class="<?php echo esc_attr( $profile_data['icon'] ); ?>"></i>
                                    <a href="<?php echo esc_url( $profile_url ); ?>" target="_blank" rel="nofollow">
                                        <?php echo esc_html( $profile_data['label'] ); ?>
                                    </a>
                                </div>
                            <?php
                                endif;
                            endforeach;
                            ?>
                            
                            <div class="author-posts-count">
                                <i class="fas fa-pencil-alt"></i>
                                <?php
                                $post_count = count_user_posts( $author_id );
                                /* translators: %d: Post count */
                                printf( esc_html( _n( '%d Article', '%d Articles', $post_count, 'aqualuxe' ) ), esc_html( $post_count ) );
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="archive-description">
                    <h2><?php esc_html_e( 'Latest Articles', 'aqualuxe' ); ?></h2>
                </div>
            </header><!-- .page-header -->
            
            <div class="posts-grid">
                <?php
                /* Start the Loop */
                while ( have_posts() ) :
                    the_post();
                    
                    /*
                     * Include the Post-Type-specific template for the content.
                     * If you want to override this in a child theme, then include a file
                     * called content-___.php (where ___ is the Post Type name) and that will be used instead.
                     */
                    get_template_part( 'templates/content', get_post_type() );
                    
                endwhile;
                ?>
            </div>
            
            <?php
            the_posts_pagination( array(
                'prev_text' => '<i class="fas fa-arrow-left"></i> ' . esc_html__( 'Previous', 'aqualuxe' ),
                'next_text' => esc_html__( 'Next', 'aqualuxe' ) . ' <i class="fas fa-arrow-right"></i>',
            ) );
            
        else :
            
            get_template_part( 'templates/content', 'none' );
            
        endif;
        ?>
    </div>
</main><!-- #main -->

<?php
get_sidebar();
get_footer();