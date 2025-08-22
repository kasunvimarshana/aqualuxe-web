<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('aqualuxe-page'); ?>>
    <?php do_action('aqualuxe_page_before'); ?>

    <header class="entry-header">
        <?php do_action('aqualuxe_page_header'); ?>
    </header><!-- .entry-header -->

    <?php do_action('aqualuxe_page_content'); ?>

    <footer class="entry-footer">
        <?php do_action('aqualuxe_page_footer'); ?>
    </footer><!-- .entry-footer -->

    <?php do_action('aqualuxe_page_after'); ?>
</article><!-- #post-<?php the_ID(); ?> -->