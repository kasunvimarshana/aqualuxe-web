<?php
/**
 * Page Template: Wishlist
 *
 * Template Name: Wishlist
 * Description: Displays the user's wishlist products.
 */
get_header();
?>
<div class="container wishlist-page">
    <h1><?php _e('My Wishlist', 'aqualuxe'); ?></h1>
    <?php get_template_part('template-parts/wishlist/list'); ?>
</div>
<?php get_footer(); ?>
