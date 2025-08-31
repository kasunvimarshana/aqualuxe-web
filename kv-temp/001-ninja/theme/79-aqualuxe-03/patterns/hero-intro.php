<?php
/**
 * Title: Hero Intro
 * Slug: aqualuxe/hero-intro
 * Categories: aqualuxe
 * Block Types: core/group
 * Description: A simple full-width hero with headline, text, and CTA.
 */
?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"60px","bottom":"60px"}}},"backgroundColor":"slate-100"} -->
<div class="wp-block-group alignfull has-slate-100-background-color has-background" style="padding-top:60px;padding-bottom:60px"><div class="wp-block-group__inner-container">
<!-- wp:group {"layout":{"type":"constrained","contentSize":"800px"}} -->
<div class="wp-block-group"><div class="wp-block-group__inner-container">
<!-- wp:heading {"textAlign":"center","level":1} -->
<h1 class="has-text-align-center"><?php echo esc_html__( 'Elevate your aquatic world', 'aqualuxe' ); ?></h1>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center"><?php echo esc_html__( 'Premium aquariums, rare species, and expert care—crafted for elegance.', 'aqualuxe' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons"><!-- wp:button {"backgroundColor":"primary"} -->
<div class="wp-block-button"><a class="wp-block-button__link has-primary-background-color has-background"><?php echo esc_html__( 'Shop now', 'aqualuxe' ); ?></a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons -->
</div></div>
<!-- /wp:group -->
</div></div>
<!-- /wp:group -->
