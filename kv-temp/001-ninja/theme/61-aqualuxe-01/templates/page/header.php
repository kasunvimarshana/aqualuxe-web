<?php
/**
 * Template part for displaying page header
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Get page title
$title = aqualuxe_get_page_title();

// Get page description
$description = aqualuxe_get_page_description();

// Return if no title
if ( ! $title ) {
    return;
}
?>

<div class="page-header">
    <div class="container">
        <div class="page-header-inner">
            <h1 class="page-title"><?php echo wp_kses_post( $title ); ?></h1>

            <?php if ( $description ) : ?>
                <div class="page-description">
                    <?php echo wp_kses_post( $description ); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>