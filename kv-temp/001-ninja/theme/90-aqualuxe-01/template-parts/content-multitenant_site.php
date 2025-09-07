<?php
/**
 * The template for displaying the Loop for the 'multitenant_site' CPT.
 *
 * @package AquaLuxe
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('multitenant-site-item'); ?>>
    <header class="entry-header">
        <?php the_title('<h3 class="entry-title">', '</h3>'); ?>
    </header>
    <div class="entry-summary">
        <?php the_excerpt(); ?>
        <a href="<?php echo get_post_meta(get_the_ID(), '_site_url', true); ?>" target="_blank" rel="noopener noreferrer">
            <?php _e('Visit Site', 'aqualuxe'); ?>
        </a>
    </div>
</article>
