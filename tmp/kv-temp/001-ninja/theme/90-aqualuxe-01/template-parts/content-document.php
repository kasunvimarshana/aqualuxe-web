<?php
/**
 * The template for displaying the Loop for the 'document' CPT.
 *
 * @package AquaLuxe
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('document-item'); ?>>
    <header class="entry-header">
        <?php the_title('<h3 class="entry-title">', '</h3>'); ?>
    </header>
    <div class="entry-summary">
        <a href="<?php echo get_post_meta(get_the_ID(), '_document_file', true); ?>" target="_blank" rel="noopener noreferrer">
            <?php _e('Download Document', 'aqualuxe'); ?>
        </a>
    </div>
</article>
