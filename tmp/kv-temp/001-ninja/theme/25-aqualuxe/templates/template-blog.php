<?php
/**
 * Template Name: Blog
 *
 * The template for displaying the blog page
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

get_header();
?>

<main id="primary" class="site-main">
    <?php
    // Hero Section
    get_template_part('template-parts/blog/hero');
    
    // Blog Content Section
    get_template_part('template-parts/blog/content');
    
    // Categories Section
    if (get_theme_mod('aqualuxe_blog_show_categories', true)) {
        get_template_part('template-parts/blog/categories');
    }
    
    // Newsletter Section
    if (get_theme_mod('aqualuxe_blog_show_newsletter', true)) {
        get_template_part('template-parts/blog/newsletter');
    }
    ?>
</main><!-- #main -->

<?php
get_footer();