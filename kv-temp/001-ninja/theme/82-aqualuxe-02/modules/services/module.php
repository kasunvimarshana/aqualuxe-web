<?php
// Services module: archive enhancements and shortcodes
add_shortcode('alx_services_list', function(){
	$q = new WP_Query(['post_type'=>'service','posts_per_page'=>6]);
	ob_start(); echo '<div class="grid grid-cols-1 md:grid-cols-3 gap-6">';
	while($q->have_posts()){ $q->the_post(); echo '<article class="p-4 bg-white/5 rounded-md"><h3 class="font-semibold">'.esc_html(get_the_title()).'</h3><div class="mt-2 text-sm opacity-90">'.wp_kses_post(wpautop(get_the_excerpt())).'</div></article>'; }
	wp_reset_postdata(); echo '</div>'; return ob_get_clean();
});
