<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

get_header();
?>

<main id="primary" class="site-main">

    <?php
    // Archive header
    $archive_header_bg = get_theme_mod('aqualuxe_blog_archive_header_bg', '');
    ?>

    <section class="page-header-wrapper" <?php if (!empty($archive_header_bg)) : ?>style="background-image: url('<?php echo esc_url($archive_header_bg); ?>');"<?php endif; ?>>
        <div class="container">
            <div class="page-header text-center">
                <?php
                the_archive_title('<h1 class="page-title">', '</h1>');
                the_archive_description('<div class="archive-description">', '</div>');
                
                if (function_exists('aqualuxe_breadcrumbs') && get_theme_mod('aqualuxe_breadcrumbs_enable', true)) {
                    aqualuxe_breadcrumbs();
                }
                ?>
            </div>
        </div>
    </section>

    <section class="blog-archive-section section">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <?php if (have_posts()) : ?>
                        <div class="blog-posts" data-aos="fade-up">
                            <?php
                            // Get blog layout
                            $blog_layout = get_theme_mod('aqualuxe_blog_layout', 'grid');
                            
                            if ($blog_layout === 'grid') :
                                echo '<div class="row">';
                            endif;
                            
                            // Start the Loop
                            while (have_posts()) :
                                the_post();
                                
                                if ($blog_layout === 'grid') :
                                    echo '<div class="col-md-6">';
                                endif;
                                
                                /*
                                 * Include the Post-Type-specific template for the content.
                                 * If you want to override this in a child theme, then include a file
                                 * called content-___.php (where ___ is the Post Type name) and that will be used instead.
                                 */
                                get_template_part('template-parts/content/content', get_post_type());
                                
                                if ($blog_layout === 'grid') :
                                    echo '</div>';
                                endif;
                                
                            endwhile;
                            
                            if ($blog_layout === 'grid') :
                                echo '</div>';
                            endif;
                            
                            // Pagination
                            the_posts_pagination(array(
                                'prev_text' => '<i class="fas fa-chevron-left"></i> ' . esc_html__('Previous', 'aqualuxe'),
                                'next_text' => esc_html__('Next', 'aqualuxe') . ' <i class="fas fa-chevron-right"></i>',
                                'screen_reader_text' => esc_html__('Posts navigation', 'aqualuxe'),
                            ));
                            ?>
                        </div>
                    <?php
                    else :
                        get_template_part('template-parts/content/content', 'none');
                    endif;
                    ?>
                </div>
                
                <div class="col-lg-4">
                    <?php get_sidebar(); ?>
                </div>
            </div>
        </div>
    </section>

</main><!-- #primary -->

<?php
get_footer();