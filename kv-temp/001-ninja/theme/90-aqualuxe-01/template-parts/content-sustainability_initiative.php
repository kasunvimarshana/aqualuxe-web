<?php
/**
 * The template for displaying the Loop for the 'sustainability_initiative' CPT.
 *
 * @package AquaLuxe
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('sustainability-initiative-item'); ?>>
    <header class="entry-header">
        <?php the_title('<h3 class="entry-title">', '</h3>'); ?>
    </header>
    <div class="entry-summary">
        <?php the_excerpt(); ?>
        <a href="<?php the_permalink(); ?>" class="read-more"><?php _e('Learn More', 'aqualuxe'); ?></a>
    </div>
</article>
