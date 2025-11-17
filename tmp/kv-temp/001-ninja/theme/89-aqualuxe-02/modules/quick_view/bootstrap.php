<?php
/** Quick View (minimal) */
namespace AquaLuxe\Modules\Quick_View;
if ( ! defined( 'ABSPATH' ) ) { exit; }

\add_action( 'wp_ajax_alx_quick_view', __NAMESPACE__ . '\\handle' );
\add_action( 'wp_ajax_nopriv_alx_quick_view', __NAMESPACE__ . '\\handle' );

function handle() {
	$id = isset( $_REQUEST['id'] ) ? absint( $_REQUEST['id'] ) : 0;
	if ( ! $id ) { \wp_send_json_error( [ 'message' => 'Missing id' ], 400 ); }
	\ob_start();
	$post = get_post( $id );
	if ( $post ) {
		echo '<div class="p-4"><h2 class="text-xl font-bold">' . esc_html( get_the_title( $id ) ) . '</h2>';
		echo '<div class="mt-2 text-sm">' . wp_kses_post( wpautop( get_post_field( 'post_excerpt', $id ) ?: get_post_field( 'post_content', $id ) ) ) . '</div></div>';
	}
	\wp_send_json_success( [ 'html' => (string) \ob_get_clean() ] );
}
