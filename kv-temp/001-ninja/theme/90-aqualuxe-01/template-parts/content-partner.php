<?php
/**
 * The template for displaying the Loop for the 'partner' CPT.
 *
 * @package AquaLuxe
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('partner-item'); ?>>
    <?php if (has_post_thumbnail()) : ?>
        <div class="partner-logo">
            <?php the_post_thumbnail('medium'); ?>
        </div>
    <?php endif; ?>
    <div class="partner-details">
        <h3 class="partner-name"><?php the_title(); ?></h3>
        <div class="partner-website">
            <a href="<?php echo get_post_meta(get_the_ID(), '_partner_website', true); ?>" target="_blank" rel="noopener noreferrer">
                <?php _e('Visit Website', 'aqualuxe'); ?>
            </a>
        </div>
    </div>
</article>
