<?php
/**
 * Asset Integration
 *
 * @package AquaLuxe
 * @subpackage Core
 * @since 1.3.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Initialize the enhanced asset system.
 *
 * @param \AquaLuxe\Core\Theme $theme Theme instance.
 * @return void
 */
function aqualuxe_init_enhanced_assets( $theme ) {
	// Include the enhanced assets class.
	require_once AQUALUXE_DIR . '/inc/core/class-enhanced-assets.php';
	
	// Register the Enhanced_Assets class with the service container.
	$theme->register_service( 'enhanced_assets', '\AquaLuxe\Core\Enhanced_Assets' );
	
	// Get the Enhanced_Assets instance.
	$enhanced_assets = \AquaLuxe\Core\Enhanced_Assets::get_instance();
	
	// Remove legacy asset loading hooks.
	remove_action( 'wp_enqueue_scripts', 'aqualuxe_legacy_scripts' );
	remove_action( 'admin_enqueue_scripts', 'aqualuxe_admin_styles' );
	remove_action( 'admin_init', 'aqualuxe_add_editor_styles' );
	
	// If the unified asset system is already initialized, remove its hooks too.
	if ( class_exists( '\AquaLuxe\Core\Assets' ) ) {
		$assets = \AquaLuxe\Core\Assets::get_instance();
		remove_action( 'wp_enqueue_scripts', array( $assets, 'enqueue_scripts' ) );
		remove_action( 'wp_enqueue_scripts', array( $assets, 'enqueue_styles' ) );
		remove_action( 'admin_enqueue_scripts', array( $assets, 'admin_enqueue_scripts' ) );
		remove_action( 'admin_enqueue_scripts', array( $assets, 'admin_enqueue_styles' ) );
		remove_action( 'enqueue_block_editor_assets', array( $assets, 'block_editor_assets' ) );
		remove_action( 'wp_head', array( $assets, 'add_preload_tags' ) );
	}
}

/**
 * Hook the initialization function.
 */
add_action( 'after_setup_theme', 'aqualuxe_init_enhanced_assets', 5 );

/**
 * Add build script to the admin menu.
 */
function aqualuxe_add_build_script_menu() {
	// Only add for administrators.
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	
	add_management_page(
		esc_html__( 'AquaLuxe Asset Builder', 'aqualuxe' ),
		esc_html__( 'Asset Builder', 'aqualuxe' ),
		'manage_options',
		'aqualuxe-asset-builder',
		'aqualuxe_asset_builder_page'
	);
}
add_action( 'admin_menu', 'aqualuxe_add_build_script_menu' );

/**
 * Asset builder page.
 */
function aqualuxe_asset_builder_page() {
	// Check if the build action was triggered.
	if ( isset( $_POST['aqualuxe_build_assets'] ) && check_admin_referer( 'aqualuxe_build_assets' ) ) {
		// Run the build script.
		$result = aqualuxe_run_build_script();
		
		// Display the result.
		if ( $result['success'] ) {
			echo '<div class="notice notice-success"><p>' . esc_html__( 'Assets built successfully!', 'aqualuxe' ) . '</p></div>';
		} else {
			echo '<div class="notice notice-error"><p>' . esc_html__( 'Error building assets:', 'aqualuxe' ) . ' ' . esc_html( $result['message'] ) . '</p></div>';
		}
	}
	
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'AquaLuxe Asset Builder', 'aqualuxe' ); ?></h1>
		
		<p><?php esc_html_e( 'Use this tool to build the theme assets using the enhanced asset pipeline.', 'aqualuxe' ); ?></p>
		
		<form method="post" action="">
			<?php wp_nonce_field( 'aqualuxe_build_assets' ); ?>
			
			<table class="form-table">
				<tr>
					<th scope="row"><?php esc_html_e( 'Build Mode', 'aqualuxe' ); ?></th>
					<td>
						<select name="build_mode">
							<option value="production"><?php esc_html_e( 'Production (Optimized)', 'aqualuxe' ); ?></option>
							<option value="development"><?php esc_html_e( 'Development (Unoptimized)', 'aqualuxe' ); ?></option>
						</select>
					</td>
				</tr>
				<tr>
					<th scope="row"><?php esc_html_e( 'Options', 'aqualuxe' ); ?></th>
					<td>
						<label>
							<input type="checkbox" name="clean_first" value="1" checked>
							<?php esc_html_e( 'Clean existing assets first', 'aqualuxe' ); ?>
						</label>
						<br>
						<label>
							<input type="checkbox" name="generate_critical" value="1" checked>
							<?php esc_html_e( 'Generate critical CSS', 'aqualuxe' ); ?>
						</label>
					</td>
				</tr>
			</table>
			
			<p class="submit">
				<input type="submit" name="aqualuxe_build_assets" class="button button-primary" value="<?php esc_attr_e( 'Build Assets', 'aqualuxe' ); ?>">
			</p>
		</form>
		
		<h2><?php esc_html_e( 'Asset Information', 'aqualuxe' ); ?></h2>
		
		<table class="widefat">
			<thead>
				<tr>
					<th><?php esc_html_e( 'Asset Type', 'aqualuxe' ); ?></th>
					<th><?php esc_html_e( 'Count', 'aqualuxe' ); ?></th>
					<th><?php esc_html_e( 'Last Built', 'aqualuxe' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><?php esc_html_e( 'CSS Files', 'aqualuxe' ); ?></td>
					<td><?php echo esc_html( count( glob( AQUALUXE_DIR . '/assets/css/*.css' ) ) ); ?></td>
					<td><?php echo esc_html( aqualuxe_get_asset_last_modified( 'css' ) ); ?></td>
				</tr>
				<tr>
					<td><?php esc_html_e( 'JavaScript Files', 'aqualuxe' ); ?></td>
					<td><?php echo esc_html( count( glob( AQUALUXE_DIR . '/assets/js/*.js' ) ) ); ?></td>
					<td><?php echo esc_html( aqualuxe_get_asset_last_modified( 'js' ) ); ?></td>
				</tr>
				<tr>
					<td><?php esc_html_e( 'Images', 'aqualuxe' ); ?></td>
					<td><?php echo esc_html( count( glob( AQUALUXE_DIR . '/assets/images/*.*' ) ) ); ?></td>
					<td><?php echo esc_html( aqualuxe_get_asset_last_modified( 'images' ) ); ?></td>
				</tr>
				<tr>
					<td><?php esc_html_e( 'Fonts', 'aqualuxe' ); ?></td>
					<td><?php echo esc_html( count( glob( AQUALUXE_DIR . '/assets/fonts/*.*' ) ) ); ?></td>
					<td><?php echo esc_html( aqualuxe_get_asset_last_modified( 'fonts' ) ); ?></td>
				</tr>
				<tr>
					<td><?php esc_html_e( 'Critical CSS', 'aqualuxe' ); ?></td>
					<td><?php echo esc_html( count( glob( AQUALUXE_DIR . '/assets/css/critical/*.css' ) ) ); ?></td>
					<td><?php echo esc_html( aqualuxe_get_asset_last_modified( 'css/critical' ) ); ?></td>
				</tr>
			</tbody>
		</table>
	</div>
	<?php
}

/**
 * Run the build script.
 *
 * @return array
 */
function aqualuxe_run_build_script() {
	// Get the build mode.
	$build_mode = isset( $_POST['build_mode'] ) ? sanitize_text_field( wp_unslash( $_POST['build_mode'] ) ) : 'production';
	
	// Get the options.
	$clean_first = isset( $_POST['clean_first'] ) && '1' === $_POST['clean_first'];
	$generate_critical = isset( $_POST['generate_critical'] ) && '1' === $_POST['generate_critical'];
	
	// Build the command.
	$command = 'cd ' . escapeshellarg( AQUALUXE_DIR ) . ' && ';
	
	// Add clean command if needed.
	if ( $clean_first ) {
		$command .= 'npm run clean && ';
	}
	
	// Add build command.
	if ( 'production' === $build_mode ) {
		$command .= 'npm run production';
	} else {
		$command .= 'npm run development';
	}
	
	// Add critical CSS command if needed.
	if ( $generate_critical && 'production' === $build_mode ) {
		$command .= ' && npm run critical';
	}
	
	// Run the command.
	$output = array();
	$return_var = 0;
	exec( $command . ' 2>&1', $output, $return_var );
	
	// Check if the command was successful.
	if ( 0 === $return_var ) {
		return array(
			'success' => true,
			'message' => implode( "\n", $output ),
		);
	} else {
		return array(
			'success' => false,
			'message' => implode( "\n", $output ),
		);
	}
}

/**
 * Get the last modified time of an asset type.
 *
 * @param string $type Asset type.
 * @return string
 */
function aqualuxe_get_asset_last_modified( $type ) {
	$files = glob( AQUALUXE_DIR . '/assets/' . $type . '/*.*' );
	
	if ( empty( $files ) ) {
		return esc_html__( 'Never', 'aqualuxe' );
	}
	
	$latest_time = 0;
	
	foreach ( $files as $file ) {
		$file_time = filemtime( $file );
		
		if ( $file_time > $latest_time ) {
			$latest_time = $file_time;
		}
	}
	
	if ( 0 === $latest_time ) {
		return esc_html__( 'Unknown', 'aqualuxe' );
	}
	
	return date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), $latest_time );
}