<?php
/**
 * Testimonials Section Pattern
 *
 * @package AquaLuxe
 */

return <<<HTML
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"4rem","bottom":"4rem"}}},"backgroundColor":"white","layout":{"inherit":true,"type":"constrained"}} -->
<div class="wp-block-group alignfull has-white-background-color has-background" style="padding-top:4rem;padding-bottom:4rem"><!-- wp:heading {"textAlign":"center","style":{"spacing":{"margin":{"bottom":"3rem"}}}} -->
<h2 class="wp-block-heading has-text-align-center" style="margin-bottom:3rem">What Our Customers Say</h2>
<!-- /wp:heading -->

<!-- wp:columns {"align":"wide"} -->
<div class="wp-block-columns alignwide"><!-- wp:column -->
<div class="wp-block-column"><!-- wp:group {"style":{"border":{"width":"1px","radius":"8px"},"spacing":{"padding":{"top":"2rem","right":"2rem","bottom":"2rem","left":"2rem"}}},"borderColor":"light","className":"is-style-aqualuxe-card"} -->
<div class="wp-block-group is-style-aqualuxe-card has-border-color has-light-border-color" style="border-width:1px;border-radius:8px;padding-top:2rem;padding-right:2rem;padding-bottom:2rem;padding-left:2rem"><!-- wp:quote {"align":"center","className":"is-style-aqualuxe-fancy"} -->
<blockquote class="wp-block-quote has-text-align-center is-style-aqualuxe-fancy"><!-- wp:paragraph -->
<p>"AquaLuxe has completely transformed our online presence. The theme is not only beautiful but also incredibly easy to use. Our sales have increased by 30% since we launched our new website!"</p>
<!-- /wp:paragraph --></blockquote>
<!-- /wp:quote -->

<!-- wp:image {"align":"center","id":130,"width":80,"height":80,"sizeSlug":"thumbnail","linkDestination":"none","className":"is-style-rounded"} -->
<figure class="wp-block-image aligncenter size-thumbnail is-resized is-style-rounded"><img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=150&q=80" alt="Sarah Johnson" class="wp-image-130" width="80" height="80"/></figure>
<!-- /wp:image -->

<!-- wp:paragraph {"align":"center","style":{"typography":{"fontStyle":"normal","fontWeight":"600"}}} -->
<p class="has-text-align-center" style="font-style:normal;font-weight:600">Sarah Johnson</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"align":"center","fontSize":"small"} -->
<p class="has-text-align-center has-small-font-size">CEO, Fashion Boutique</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:group {"style":{"border":{"width":"1px","radius":"8px"},"spacing":{"padding":{"top":"2rem","right":"2rem","bottom":"2rem","left":"2rem"}}},"borderColor":"light","className":"is-style-aqualuxe-card"} -->
<div class="wp-block-group is-style-aqualuxe-card has-border-color has-light-border-color" style="border-width:1px;border-radius:8px;padding-top:2rem;padding-right:2rem;padding-bottom:2rem;padding-left:2rem"><!-- wp:quote {"align":"center","className":"is-style-aqualuxe-fancy"} -->
<blockquote class="wp-block-quote has-text-align-center is-style-aqualuxe-fancy"><!-- wp:paragraph -->
<p>"I've tried many WordPress themes over the years, but AquaLuxe stands out for its attention to detail and performance. My website now loads in under 2 seconds, and customers love the smooth experience!"</p>
<!-- /wp:paragraph --></blockquote>
<!-- /wp:quote -->

<!-- wp:image {"align":"center","id":131,"width":80,"height":80,"sizeSlug":"thumbnail","linkDestination":"none","className":"is-style-rounded"} -->
<figure class="wp-block-image aligncenter size-thumbnail is-resized is-style-rounded"><img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=150&q=80" alt="Michael Rodriguez" class="wp-image-131" width="80" height="80"/></figure>
<!-- /wp:image -->

<!-- wp:paragraph {"align":"center","style":{"typography":{"fontStyle":"normal","fontWeight":"600"}}} -->
<p class="has-text-align-center" style="font-style:normal;font-weight:600">Michael Rodriguez</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"align":"center","fontSize":"small"} -->
<p class="has-text-align-center has-small-font-size">Owner, Tech Store</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:group {"style":{"border":{"width":"1px","radius":"8px"},"spacing":{"padding":{"top":"2rem","right":"2rem","bottom":"2rem","left":"2rem"}}},"borderColor":"light","className":"is-style-aqualuxe-card"} -->
<div class="wp-block-group is-style-aqualuxe-card has-border-color has-light-border-color" style="border-width:1px;border-radius:8px;padding-top:2rem;padding-right:2rem;padding-bottom:2rem;padding-left:2rem"><!-- wp:quote {"align":"center","className":"is-style-aqualuxe-fancy"} -->
<blockquote class="wp-block-quote has-text-align-center is-style-aqualuxe-fancy"><!-- wp:paragraph -->
<p>"The WooCommerce integration in AquaLuxe is simply outstanding. Setting up our online store was a breeze, and the checkout process is so smooth that our cart abandonment rate has decreased significantly."</p>
<!-- /wp:paragraph --></blockquote>
<!-- /wp:quote -->

<!-- wp:image {"align":"center","id":132,"width":80,"height":80,"sizeSlug":"thumbnail","linkDestination":"none","className":"is-style-rounded"} -->
<figure class="wp-block-image aligncenter size-thumbnail is-resized is-style-rounded"><img src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=150&q=80" alt="Emily Chen" class="wp-image-132" width="80" height="80"/></figure>
<!-- /wp:image -->

<!-- wp:paragraph {"align":"center","style":{"typography":{"fontStyle":"normal","fontWeight":"600"}}} -->
<p class="has-text-align-center" style="font-style:normal;font-weight:600">Emily Chen</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"align":"center","fontSize":"small"} -->
<p class="has-text-align-center has-small-font-size">Marketing Director, Home Goods</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group -->
HTML;