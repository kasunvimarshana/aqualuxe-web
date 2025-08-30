<?php
/**
 * AquaLuxe back compat functionality
 *
 * Prevents AquaLuxe from running on WordPress versions prior to 5.8,
 * since this theme is not meant to be backward compatible beyond that and
 * relies on many newer functions and markup changes introduced in 5.8.
 *
 * @package AquaLuxe
 * @since AquaLuxe 1.0.0
 */

/**
 * Prevent switching to AquaLuxe on old versions of WordPress.
 *
 * Switches to the default theme.
 *
 * @since AquaLuxe 1.0.0
 */
function aqualuxe_switch_theme() {
	switch_theme( WP_DEFAULT_THEME );
	unset( $_GET['activated'] );
	add_action( 'admin_notices', 'aqualuxe_upgrade_notice' );
}
add_action( 'after_switch_theme', 'aqualuxe_switch_theme' );

/**
 * Adds a message for unsuccessful theme switch.
 *
 * Prints an update nag after an unsuccessful attempt to switch to
 * AquaLuxe on WordPress versions prior to 5.8.
 *
 * @since AquaLuxe 1.0.0
 */
function aqualuxe_upgrade_notice() {
	$message = sprintf(
		/* translators: %1$s: WordPress version. %2$s: Link to WordPress updates page. */
		__( 'AquaLuxe requires at least WordPress version 5.8. You are running version %1$s. Please <a href="%2$s">update WordPress</a>.', 'aqualuxe' ),
		$GLOBALS['wp_version'],
		admin_url( 'update-core.php' )
	);
	printf( '<div class="error"><p>%s</p></div>', $message ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Prevents the Customizer from being loaded on WordPress versions prior to 5.8.
 *
 * @since AquaLuxe 1.0.0
 */
function aqualuxe_customize() {
	wp_die(
		sprintf(
			/* translators: %1$s: WordPress version. %2$s: Link to WordPress updates page. */
			__( 'AquaLuxe requires at least WordPress version 5.8. You are running version %1$s. Please <a href="%2$s">update WordPress</a>.', 'aqualuxe' ),
			$GLOBALS['wp_version'],
			admin_url( 'update-core.php' )
		),
		'',
		array(
			'back_link' => true,
		)
	);
}
add_action( 'load-customize.php', 'aqualuxe_customize' );

/**
 * Prevents the Theme Preview from being loaded on WordPress versions prior to 5.8.
 *
 * @since AquaLuxe 1.0.0
 */
function aqualuxe_preview() {
	if ( isset( $_GET['preview'] ) ) {
		wp_die(
			sprintf(
				/* translators: %1$s: WordPress version. %2$s: Link to WordPress updates page. */
				__( 'AquaLuxe requires at least WordPress version 5.8. You are running version %1$s. Please <a href="%2$s">update WordPress</a>.', 'aqualuxe' ),
				$GLOBALS['wp_version'],
				admin_url( 'update-core.php' )
			)
		);
	}
}
add_action( 'template_redirect', 'aqualuxe_preview' );