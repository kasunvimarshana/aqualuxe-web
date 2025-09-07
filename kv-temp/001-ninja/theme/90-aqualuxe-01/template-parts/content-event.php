<?php
/**
 * The template for displaying the Loop for the 'event' CPT.
 *
 * @package AquaLuxe
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('event-item'); ?>>
    <header class="entry-header">
        <?php the_title('<h2 class="entry-title">', '</h2>'); ?>
    </header>

    <div class="entry-content">
        <div class="event-date">
            <?php echo get_post_meta(get_the_ID(), '_event_date', true); ?>
        </div>
        <?php the_excerpt(); ?>
        <a href="<?php the_permalink(); ?>" class="read-more"><?php _e('Read More', 'aqualuxe'); ?></a>
    </div>
</article>
