<?php
/**
 * The template for displaying the Loop for the 'aquascaping_tip' CPT.
 *
 * @package AquaLuxe
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('aquascaping-tip-item'); ?>>
    <header class="entry-header">
        <?php the_title('<h3 class="entry-title"><a href="' . get_permalink() . '">', '</a></h3>'); ?>
    </header>
    <div class="entry-summary">
        <?php the_excerpt(); ?>
    </div>
</article>
