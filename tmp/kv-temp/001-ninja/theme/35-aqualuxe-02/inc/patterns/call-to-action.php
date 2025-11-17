<?php
/**
 * Call to Action Pattern
 *
 * @package AquaLuxe
 */

return <<<HTML
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"4rem","bottom":"4rem"}},"color":{"gradient":"linear-gradient(135deg, #0073aa 0%, #00a0d2 100%)"}},"layout":{"inherit":true,"type":"constrained"}} -->
<div class="wp-block-group alignfull has-background" style="background:linear-gradient(135deg, #0073aa 0%, #00a0d2 100%);padding-top:4rem;padding-bottom:4rem"><!-- wp:heading {"textAlign":"center","textColor":"white"} -->
<h2 class="wp-block-heading has-text-align-center has-white-color has-text-color">Ready to Get Started?</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center","textColor":"white","fontSize":"large"} -->
<p class="has-text-align-center has-white-color has-text-color has-large-font-size">Join thousands of satisfied customers who have transformed their websites with AquaLuxe.</p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"},"style":{"spacing":{"margin":{"top":"2rem"}}}} -->
<div class="wp-block-buttons" style="margin-top:2rem"><!-- wp:button {"backgroundColor":"white","textColor":"primary","className":"is-style-fill"} -->
<div class="wp-block-button is-style-fill"><a class="wp-block-button__link has-primary-color has-white-background-color has-text-color has-background wp-element-button">Purchase Now</a></div>
<!-- /wp:button -->

<!-- wp:button {"textColor":"white","className":"is-style-outline"} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link has-white-color has-text-color wp-element-button">View Demo</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons -->

<!-- wp:paragraph {"align":"center","textColor":"white","fontSize":"small","style":{"spacing":{"margin":{"top":"2rem"}}}} -->
<p class="has-text-align-center has-white-color has-text-color has-small-font-size" style="margin-top:2rem">30-day money-back guarantee. No questions asked.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->
HTML;