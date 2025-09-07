<?php
/**
 * The template for displaying the Loop for the 'location' CPT.
 *
 * @package AquaLuxe
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('location-item'); ?>>
    <header class="entry-header">
        <?php the_title('<h3 class="entry-title">', '</h3>'); ?>
    </header>
    <div class="entry-content">
        <div class="location-address">
            <?php echo nl2br(get_post_meta(get_the_ID(), '_location_address', true)); ?>
        </div>
        <div class="location-phone">
            <?php echo get_post_meta(get_the_ID(), '_location_phone', true); ?>
        </div>
        <div class="location-hours">
            <?php echo nl2br(get_post_meta(get_the_ID(), '_location_hours', true)); ?>
        </div>
    </div>
</article>
