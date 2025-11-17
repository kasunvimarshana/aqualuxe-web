<?php if (!defined('ABSPATH')) exit;
// Simple shortcodes used in demo content
add_shortcode('aqualuxe_home', function(){ return '<div class="p-6">AquaLuxe Home</div>'; });
add_shortcode('aqualuxe_services', function(){ return '<div class="grid gap-4">Service listing placeholder</div>'; });
add_shortcode('aqualuxe_contact', function(){ return '<div class="p-6">Contact form placeholder</div>'; });
add_shortcode('aqualuxe_faq', function(){ return '<div class="p-6">FAQ placeholder</div>'; });
add_shortcode('aqualuxe_privacy', function(){ return '<div class="prose">Privacy policy placeholder</div>'; });
add_shortcode('aqualuxe_terms', function(){ return '<div class="prose">Terms placeholder</div>'; });

// New: Wholesale & B2B
add_shortcode('aqualuxe_wholesale', function(){
	$html = '<div class="prose max-w-none">'
		.'<h2>Who We Serve</h2><ul><li>Pet stores & retailers</li><li>Hotels & designers</li><li>Exporters & distributors</li></ul>'
		.'<h2>Bulk Order Benefits</h2><ul><li>Tiered pricing</li><li>Logistics support</li><li>Priority stock</li></ul>'
		.'<p><a class="ax-btn" href="'.esc_url( home_url('/contact/') ).'">Get a Wholesale Account</a></p>'
		.'</div>';
	return $html;
});

// New: Buy, Sell & Trade
add_shortcode('aqualuxe_trade', function(){
	$html = '<div class="prose max-w-none">'
		.'<h2>Trade-In & Exchange</h2><p>Swap fish/plants for credits or cash. Read the marketplace guidelines and submit your item.</p>'
		.( shortcode_exists('ax_tradein_form') ? do_shortcode('[ax_tradein_form]') : '<p>Trade-in form requires the Trade-ins module.</p>' )
		.'</div>';
	return $html;
});

// New: Export
add_shortcode('aqualuxe_export', function(){
	$html = '<div class="prose max-w-none">'
		.'<h2>Countries We Export To</h2><p>Europe, Asia, Middle East (and expanding).</p>'
		.'<h2>Order Process</h2><ol><li>Quotation</li><li>Compliance & Certification</li><li>Packing</li><li>Dispatch</li></ol>'
		.'<p><a class="ax-btn" href="'.esc_url( home_url('/contact/') ).'">Request a Quote</a></p>'
		.'</div>';
	return $html;
});

// New: Events Hub (links to Events archive)
add_shortcode('aqualuxe_events_hub', function(){
	$link = get_post_type_archive_link('event');
	if (!$link) $link = home_url('/events/');
	return '<div class="p-6"><a class="ax-btn" href="'.esc_url($link).'">View Upcoming Events</a></div>';
});

// New: Learning Hub (latest posts)
add_shortcode('aqualuxe_learning_hub', function(){
	$q = new WP_Query(['post_type'=>'post','posts_per_page'=>6]);
	$out = '<div class="grid md:grid-cols-3 gap-6">';
	if ($q->have_posts()){
		while ($q->have_posts()){ $q->the_post();
			$out .= '<article class="ax-card p-4">'
				.'<a href="'.esc_url(get_permalink()).'" class="font-semibold block mb-2">'.esc_html(get_the_title()).'</a>'
				.'<div class="text-sm opacity-80">'.esc_html( wp_trim_words( wp_strip_all_tags(get_the_excerpt()), 18 ) ).'</div>'
				.'</article>';
		}
		wp_reset_postdata();
	} else {
		$out .= '<p>No posts yet.</p>';
	}
	$out .= '</div>';
	return $out;
});
