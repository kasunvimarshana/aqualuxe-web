<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('aqualuxe-post'); ?>>
    <?php do_action('aqualuxe_post_before'); ?>

    <header class="entry-header">
        <?php do_action('aqualuxe_post_header'); ?>
    </header><!-- .entry-header -->

    <?php do_action('aqualuxe_post_content'); ?>

    <footer class="entry-footer">
        <?php do_action('aqualuxe_post_footer'); ?>
    </footer><!-- .entry-footer -->

    <?php do_action('aqualuxe_post_after'); ?>
</article><!-- #post-<?php the_ID(); ?> -->