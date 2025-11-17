<?php
namespace AquaLuxe\SEO;

add_action('wp_head', function(){
	if ( is_admin() ) return;
	$title = wp_get_document_title();
	$desc  = get_bloginfo('description');
	$url   = home_url( add_query_arg( [], $GLOBALS['wp']->request ) );
	$img   = get_site_icon_url( 512 );
	echo "\n<meta property=\"og:type\" content=\"website\" />\n";
	echo '<meta property="og:title" content="' . esc_attr( $title ) . '" />' . "\n";
	echo '<meta property="og:description" content="' . esc_attr( $desc ) . '" />' . "\n";
	echo '<meta property="og:url" content="' . esc_url( $url ) . '" />' . "\n";
	if ($img) echo '<meta property="og:image" content="' . esc_url( $img ) . '" />' . "\n";
	echo '<meta name="twitter:card" content="summary_large_image" />' . "\n";
}, 5);
