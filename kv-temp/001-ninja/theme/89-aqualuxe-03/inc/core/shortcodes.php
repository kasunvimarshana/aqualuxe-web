<?php
/** Shortcodes */
namespace AquaLuxe\Core;
if ( ! defined( 'ABSPATH' ) ) { exit; }

\add_shortcode( 'alx_cta', function ( $atts, $content = '' ) {
	$atts = shortcode_atts( [ 'url' => home_url( '/' ), 'label' => __( 'Learn more', 'aqualuxe' ) ], $atts, 'alx_cta' );
	return '<a class="px-4 py-2 bg-blue-600 text-white rounded" href="' . esc_url( $atts['url'] ) . '">' . esc_html( $atts['label'] ) . '</a>';
} );

\add_shortcode( 'alx_contact_form', function () {
	ob_start(); ?>
	<form method="post" class="grid gap-3" action="<?php echo esc_url( home_url( '/' ) ); ?>">
		<label>
			<span class="sr-only"><?php esc_html_e( 'Your Name', 'aqualuxe' ); ?></span>
			<input type="text" name="alx_name" required class="border p-2 w-full" placeholder="<?php esc_attr_e( 'Your Name', 'aqualuxe' ); ?>">
		</label>
		<label>
			<span class="sr-only"><?php esc_html_e( 'Email', 'aqualuxe' ); ?></span>
			<input type="email" name="alx_email" required class="border p-2 w-full" placeholder="<?php esc_attr_e( 'Email', 'aqualuxe' ); ?>">
		</label>
		<label>
			<span class="sr-only"><?php esc_html_e( 'Message', 'aqualuxe' ); ?></span>
			<textarea name="alx_message" required class="border p-2 w-full" rows="5" placeholder="<?php esc_attr_e( 'How can we help?', 'aqualuxe' ); ?>"></textarea>
		</label>
		<?php \wp_nonce_field( 'alx_contact', '_alx_contact' ); ?>
		<button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded"><?php esc_html_e( 'Send', 'aqualuxe' ); ?></button>
	</form>
	<?php return (string) ob_get_clean();
} );

\add_shortcode( 'alx_services', function ( $atts ) {
	$atts = shortcode_atts( [ 'limit' => 6 ], $atts, 'alx_services' );
	$q = new \WP_Query( [ 'post_type' => 'service', 'posts_per_page' => (int) $atts['limit'] ] );
	ob_start(); echo '<div class="grid gap-6 md:grid-cols-3">';
	while ( $q->have_posts() ) { $q->the_post();
		echo '<article class="border rounded p-4"><h3 class="font-semibold">' . esc_html( get_the_title() ) . '</h3><div class="text-sm opacity-80">' . wp_kses_post( wpautop( get_the_excerpt() ) ) . '</div></article>';
	}
	\wp_reset_postdata(); echo '</div>'; return (string) ob_get_clean();
} );

\add_shortcode( 'alx_testimonials', function ( $atts ) {
	$atts = shortcode_atts( [ 'limit' => 3 ], $atts, 'alx_testimonials' );
	$q = new \WP_Query( [ 'post_type' => 'testimonial', 'posts_per_page' => (int) $atts['limit'] ] );
	ob_start(); echo '<div class="grid gap-6 md:grid-cols-3">';
	while ( $q->have_posts() ) { $q->the_post();
		echo '<blockquote class="border rounded p-4 bg-white/60"><p class="italic">' . esc_html( wp_trim_words( get_the_content(), 30 ) ) . '</p><footer class="mt-2 text-sm font-semibold">— ' . esc_html( get_the_title() ) . '</footer></blockquote>';
	}
	\wp_reset_postdata(); echo '</div>'; return (string) ob_get_clean();
} );
