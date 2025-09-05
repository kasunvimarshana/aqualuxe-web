<?php
/** SEO & Meta */
namespace AquaLuxe\Core;
if ( ! defined( 'ABSPATH' ) ) { exit; }

\add_action( 'wp_head', function () {
	$title = \wp_get_document_title();
	$desc  = \get_bloginfo( 'description', 'display' );
	$url   = \home_url( \add_query_arg( [], \wp_parse_url( $_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH ) ) );
	$img   = \get_site_icon_url() ?: ( AQUALUXE_URI . '/assets/dist/img/og.png' );
	echo '<meta name="description" content="' . esc_attr( $desc ) . '" />';
	echo '<meta property="og:title" content="' . esc_attr( $title ) . '" />';
	echo '<meta property="og:description" content="' . esc_attr( $desc ) . '" />';
	echo '<meta property="og:url" content="' . esc_url( $url ) . '" />';
	echo '<meta property="og:type" content="website" />';
	echo '<meta property="og:image" content="' . esc_url( $img ) . '" />';

	$schema = [
		'@context' => 'https://schema.org',
		'@type'    => \is_singular() ? 'Article' : 'WebSite',
		'name'     => \get_bloginfo( 'name' ),
		'url'      => $url,
	];
	echo '<script type="application/ld+json">' . wp_json_encode( $schema ) . '</script>';
}, 20 );
