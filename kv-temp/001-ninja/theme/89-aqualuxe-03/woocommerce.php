<?php
/** WooCommerce fallback wrapper */
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header(); ?>
<div class="alx-container py-8">
	<?php woocommerce_content(); ?>
</div>
<?php get_footer(); ?>
