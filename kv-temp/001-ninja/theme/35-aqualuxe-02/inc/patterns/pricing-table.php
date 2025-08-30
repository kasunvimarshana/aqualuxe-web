<?php
/**
 * Pricing Table Pattern
 *
 * @package AquaLuxe
 */

return <<<HTML
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"4rem","bottom":"4rem"}}},"backgroundColor":"light","layout":{"inherit":true,"type":"constrained"}} -->
<div class="wp-block-group alignfull has-light-background-color has-background" style="padding-top:4rem;padding-bottom:4rem"><!-- wp:heading {"textAlign":"center","style":{"spacing":{"margin":{"bottom":"1rem"}}}} -->
<h2 class="wp-block-heading has-text-align-center" style="margin-bottom:1rem">Simple, Transparent Pricing</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center","style":{"spacing":{"margin":{"bottom":"3rem"}}}} -->
<p class="has-text-align-center" style="margin-bottom:3rem">Choose the perfect plan for your needs. No hidden fees.</p>
<!-- /wp:paragraph -->

<!-- wp:columns {"align":"wide"} -->
<div class="wp-block-columns alignwide"><!-- wp:column -->
<div class="wp-block-column"><!-- wp:group {"style":{"border":{"width":"1px","radius":"8px"},"spacing":{"padding":{"top":"2rem","right":"2rem","bottom":"2rem","left":"2rem"}}},"backgroundColor":"white","className":"is-style-aqualuxe-card"} -->
<div class="wp-block-group is-style-aqualuxe-card has-white-background-color has-background" style="border-width:1px;border-radius:8px;padding-top:2rem;padding-right:2rem;padding-bottom:2rem;padding-left:2rem"><!-- wp:heading {"textAlign":"center","level":3} -->
<h3 class="wp-block-heading has-text-align-center">Basic</h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center","style":{"typography":{"fontWeight":"700","fontSize":"2.5rem"}}} -->
<p class="has-text-align-center" style="font-size:2.5rem;font-weight:700">$49</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"align":"center","fontSize":"small"} -->
<p class="has-text-align-center has-small-font-size">One-time payment</p>
<!-- /wp:paragraph -->

<!-- wp:separator {"backgroundColor":"light","className":"is-style-wide"} -->
<hr class="wp-block-separator has-text-color has-light-color has-alpha-channel-opacity has-light-background-color has-background is-style-wide"/>
<!-- /wp:separator -->

<!-- wp:list {"style":{"spacing":{"margin":{"top":"1.5rem","bottom":"1.5rem"}}}} -->
<ul style="margin-top:1.5rem;margin-bottom:1.5rem"><!-- wp:list-item -->
<li>Single site license</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>6 months support</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Basic theme features</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>WooCommerce compatible</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Regular updates</li>
<!-- /wp:list-item --></ul>
<!-- /wp:list -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons"><!-- wp:button {"backgroundColor":"primary","width":100} -->
<div class="wp-block-button has-custom-width has-100-width"><a class="wp-block-button__link has-primary-background-color has-background wp-element-button">Get Started</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:group {"style":{"border":{"width":"2px","radius":"8px"},"spacing":{"padding":{"top":"2rem","right":"2rem","bottom":"2rem","left":"2rem"}}},"borderColor":"primary","backgroundColor":"white","className":"is-style-aqualuxe-card"} -->
<div class="wp-block-group is-style-aqualuxe-card has-border-color has-primary-border-color has-white-background-color has-background" style="border-width:2px;border-radius:8px;padding-top:2rem;padding-right:2rem;padding-bottom:2rem;padding-left:2rem"><!-- wp:heading {"textAlign":"center","level":3,"textColor":"primary"} -->
<h3 class="wp-block-heading has-text-align-center has-primary-color has-text-color">Professional</h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center","style":{"typography":{"fontWeight":"700","fontSize":"2.5rem"}}} -->
<p class="has-text-align-center" style="font-size:2.5rem;font-weight:700">$99</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"align":"center","fontSize":"small"} -->
<p class="has-text-align-center has-small-font-size">One-time payment</p>
<!-- /wp:paragraph -->

<!-- wp:separator {"backgroundColor":"light","className":"is-style-wide"} -->
<hr class="wp-block-separator has-text-color has-light-color has-alpha-channel-opacity has-light-background-color has-background is-style-wide"/>
<!-- /wp:separator -->

<!-- wp:list {"style":{"spacing":{"margin":{"top":"1.5rem","bottom":"1.5rem"}}}} -->
<ul style="margin-top:1.5rem;margin-bottom:1.5rem"><!-- wp:list-item -->
<li>5 site license</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>1 year premium support</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>All theme features</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Advanced WooCommerce features</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Priority updates</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Premium patterns library</li>
<!-- /wp:list-item --></ul>
<!-- /wp:list -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons"><!-- wp:button {"width":100} -->
<div class="wp-block-button has-custom-width has-100-width"><a class="wp-block-button__link wp-element-button">Best Value</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:group {"style":{"border":{"width":"1px","radius":"8px"},"spacing":{"padding":{"top":"2rem","right":"2rem","bottom":"2rem","left":"2rem"}}},"backgroundColor":"white","className":"is-style-aqualuxe-card"} -->
<div class="wp-block-group is-style-aqualuxe-card has-white-background-color has-background" style="border-width:1px;border-radius:8px;padding-top:2rem;padding-right:2rem;padding-bottom:2rem;padding-left:2rem"><!-- wp:heading {"textAlign":"center","level":3} -->
<h3 class="wp-block-heading has-text-align-center">Agency</h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center","style":{"typography":{"fontWeight":"700","fontSize":"2.5rem"}}} -->
<p class="has-text-align-center" style="font-size:2.5rem;font-weight:700">$199</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"align":"center","fontSize":"small"} -->
<p class="has-text-align-center has-small-font-size">One-time payment</p>
<!-- /wp:paragraph -->

<!-- wp:separator {"backgroundColor":"light","className":"is-style-wide"} -->
<hr class="wp-block-separator has-text-color has-light-color has-alpha-channel-opacity has-light-background-color has-background is-style-wide"/>
<!-- /wp:separator -->

<!-- wp:list {"style":{"spacing":{"margin":{"top":"1.5rem","bottom":"1.5rem"}}}} -->
<ul style="margin-top:1.5rem;margin-bottom:1.5rem"><!-- wp:list-item -->
<li>Unlimited site license</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Lifetime premium support</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>All theme features</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Advanced WooCommerce features</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Priority updates</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Premium patterns library</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>White labeling option</li>
<!-- /wp:list-item --></ul>
<!-- /wp:list -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons"><!-- wp:button {"backgroundColor":"primary","width":100} -->
<div class="wp-block-button has-custom-width has-100-width"><a class="wp-block-button__link has-primary-background-color has-background wp-element-button">Get Started</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group -->
HTML;