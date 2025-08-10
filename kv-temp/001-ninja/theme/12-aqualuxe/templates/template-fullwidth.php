<?php
/**
 * Template Name: Full Width Page
 *
 * The template for displaying full width pages without sidebar.
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
    // Page header
    if (get_theme_mod('aqualuxe_page_header_enable', true)) :
        $page_title = get_the_title();
        $page_subtitle = get_post_meta(get_the_ID(), 'page_subtitle', true);
        $page_header_bg = get_post_meta(get_the_ID(), 'page_header_bg', true);
        
        if (empty($page_header_bg)) {
            $page_header_bg = get_theme_mod('aqualuxe_page_header_bg', '');
        }
    ?>
        <section class="page-header-wrapper" <?php if (!empty($page_header_bg)) : ?>style="background-image: url('<?php echo esc_url($page_header_bg); ?>');"<?php endif; ?>>
            <div class="container">
                <div class="page-header text-center">
                    <h1 class="page-title"><?php echo esc_html($page_title); ?></h1>
                    
                    <?php if (!empty($page_subtitle)) : ?>
                        <div class="page-subtitle"><?php echo esc_html($page_subtitle); ?></div>
                    <?php endif; ?>
                    
                    <?php
                    if (function_exists('aqualuxe_breadcrumbs') && get_theme_mod('aqualuxe_breadcrumbs_enable', true)) {
                        aqualuxe_breadcrumbs();
                    }
                    ?>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <section class="page-content-section section">
        <div class="container">
            <?php
            while (have_posts()) :
                the_post();
            ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class('page-content'); ?>>
                    <?php if (has_post_thumbnail() && get_theme_mod('aqualuxe_page_featured_image', true)) : ?>
                        <div class="page-thumbnail" data-aos="fade-up">
                            <?php the_post_thumbnail('full', array('class' => 'img-fluid')); ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="entry-content" data-aos="fade-up">
                        <?php
                        the_content();
                        
                        wp_link_pages(
                            array(
                                'before' => '<div class="page-links">' . esc_html__('Pages:', 'aqualuxe'),
                                'after'  => '</div>',
                            )
                        );
                        ?>
                    </div>
                    
                    <?php
                    // If comments are open or we have at least one comment, load up the comment template.
                    if (comments_open() || get_comments_number()) :
                        comments_template();
                    endif;
                    ?>
                </article>
            <?php endwhile; ?>
        </div>
    </section>

</main><!-- #primary -->

<?php
get_footer();