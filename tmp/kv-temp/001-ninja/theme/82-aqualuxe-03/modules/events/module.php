<?php
// Events module: simple upcoming list
add_shortcode('alx_events', function(){
	$q = new WP_Query(['post_type'=>'event','posts_per_page'=>5]);
	ob_start(); echo '<ul class="space-y-3">';
	while($q->have_posts()){ $q->the_post(); echo '<li><a class="underline" href="'.esc_url(get_permalink()).'">'.esc_html(get_the_title()).'</a></li>'; }
	wp_reset_postdata(); echo '</ul>'; return ob_get_clean();
});
