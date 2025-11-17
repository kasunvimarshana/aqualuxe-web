<?php
/**
 * Browser Caching Handler
 *
 * @package AquaLuxe
 * @subpackage Core
 * @since 1.1.0
 */

namespace AquaLuxe\Core;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Browser Caching Class
 *
 * Handles the implementation of browser caching strategies to improve page load performance.
 * Browser caching allows browsers to store static resources locally, reducing the need for repeated downloads.
 *
 * @since 1.1.0
 */
class Browser_Caching {

	/**
	 * Initialize the class.
	 *
	 * @return void
	 */
	public function initialize() {
		// Nothing to initialize.
	}

	/**
	 * Register hooks.
	 *
	 * @return void
	 */
	public function register_hooks() {
		// Skip if browser caching is disabled.
		if ( ! get_theme_mod( 'aqualuxe_enable_browser_caching', true ) ) {
			return;
		}

		// Add browser caching headers.
		add_action( 'wp_loaded', array( $this, 'add_browser_caching_headers' ) );

		// Add version to assets.
		add_filter( 'style_loader_src', array( $this, 'add_version_to_assets' ), 10, 2 );
		add_filter( 'script_loader_src', array( $this, 'add_version_to_assets' ), 10, 2 );

		// Add admin menu.
		add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );

		// Add .htaccess rules.
		add_action( 'admin_init', array( $this, 'add_htaccess_rules' ) );
	}

	/**
	 * Add browser caching headers.
	 *
	 * @return void
	 */
	public function add_browser_caching_headers() {
		// Skip if headers are already sent.
		if ( headers_sent() ) {
			return;
		}

		// Skip if this is an admin page.
		if ( is_admin() ) {
			return;
		}

		// Skip if this is a POST request.
		if ( $_SERVER['REQUEST_METHOD'] === 'POST' ) {
			return;
		}

		// Get cache control settings.
		$cache_control = get_theme_mod( 'aqualuxe_cache_control', 'public, max-age=31536000' );

		// Add cache control header.
		header( 'Cache-Control: ' . $cache_control );

		// Add expires header.
		$expires = get_theme_mod( 'aqualuxe_expires', '1 year' );
		if ( ! empty( $expires ) ) {
			header( 'Expires: ' . gmdate( 'D, d M Y H:i:s', strtotime( '+' . $expires ) ) . ' GMT' );
		}

		// Add ETag header.
		if ( get_theme_mod( 'aqualuxe_enable_etag', true ) ) {
			$etag = md5( $_SERVER['REQUEST_URI'] . filemtime( get_template_directory() . '/style.css' ) );
			header( 'ETag: "' . $etag . '"' );
		}

		// Add Last-Modified header.
		if ( get_theme_mod( 'aqualuxe_enable_last_modified', true ) ) {
			$last_modified = filemtime( get_template_directory() . '/style.css' );
			header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s', $last_modified ) . ' GMT' );
		}
	}

	/**
	 * Add version to assets.
	 *
	 * @param string $src    The source URL of the asset.
	 * @param string $handle The asset handle.
	 * @return string Modified source URL.
	 */
	public function add_version_to_assets( $src, $handle ) {
		// Skip if source is empty.
		if ( empty( $src ) ) {
			return $src;
		}

		// Skip if this is not a theme asset.
		if ( strpos( $src, get_template_directory_uri() ) === false ) {
			return $src;
		}

		// Skip if version query parameter is already present.
		if ( strpos( $src, '?ver=' ) !== false || strpos( $src, '&ver=' ) !== false ) {
			return $src;
		}

		// Get file path from URL.
		$file_path = str_replace(
			get_template_directory_uri(),
			get_template_directory(),
			strtok( $src, '?' )
		);

		// Skip if file doesn't exist.
		if ( ! file_exists( $file_path ) ) {
			return $src;
		}

		// Add version based on file modification time.
		$version = filemtime( $file_path );
		$src = add_query_arg( 'ver', $version, $src );

		return $src;
	}

	/**
	 * Add .htaccess rules for browser caching.
	 *
	 * @return void
	 */
	public function add_htaccess_rules() {
		// Skip if .htaccess rules are disabled.
		if ( ! get_theme_mod( 'aqualuxe_enable_htaccess_rules', false ) ) {
			return;
		}

		// Skip if .htaccess is not writable.
		if ( ! is_writable( ABSPATH . '.htaccess' ) ) {
			return;
		}

		// Get .htaccess file content.
		$htaccess_file = ABSPATH . '.htaccess';
		$htaccess_content = file_get_contents( $htaccess_file );

		// Check if browser caching rules are already present.
		if ( strpos( $htaccess_content, '# BEGIN AquaLuxe Browser Caching' ) !== false ) {
			return;
		}

		// Get browser caching rules.
		$rules = $this->get_htaccess_rules();

		// Add rules to .htaccess file.
		$htaccess_content = $rules . "\n\n" . $htaccess_content;
		file_put_contents( $htaccess_file, $htaccess_content );
	}

	/**
	 * Get .htaccess rules for browser caching.
	 *
	 * @return string .htaccess rules.
	 */
	private function get_htaccess_rules() {
		$rules = "# BEGIN AquaLuxe Browser Caching
<IfModule mod_expires.c>
    ExpiresActive On
    
    # Images
    ExpiresByType image/jpeg &quot;access plus 1 year&quot;
    ExpiresByType image/gif &quot;access plus 1 year&quot;
    ExpiresByType image/png &quot;access plus 1 year&quot;
    ExpiresByType image/webp &quot;access plus 1 year&quot;
    ExpiresByType image/svg+xml &quot;access plus 1 year&quot;
    ExpiresByType image/x-icon &quot;access plus 1 year&quot;
    
    # Video
    ExpiresByType video/mp4 &quot;access plus 1 year&quot;
    ExpiresByType video/mpeg &quot;access plus 1 year&quot;
    
    # CSS, JavaScript
    ExpiresByType text/css &quot;access plus 1 month&quot;
    ExpiresByType text/javascript &quot;access plus 1 month&quot;
    ExpiresByType application/javascript &quot;access plus 1 month&quot;
    
    # Fonts
    ExpiresByType font/ttf &quot;access plus 1 year&quot;
    ExpiresByType font/otf &quot;access plus 1 year&quot;
    ExpiresByType font/woff &quot;access plus 1 year&quot;
    ExpiresByType font/woff2 &quot;access plus 1 year&quot;
    ExpiresByType application/font-woff &quot;access plus 1 year&quot;
    ExpiresByType application/font-woff2 &quot;access plus 1 year&quot;
    
    # Others
    ExpiresByType application/pdf &quot;access plus 1 month&quot;
    ExpiresByType application/x-shockwave-flash &quot;access plus 1 month&quot;
</IfModule>

<IfModule mod_deflate.c>
    # Compress HTML, CSS, JavaScript, Text, XML and fonts
    AddOutputFilterByType DEFLATE application/javascript
    AddOutputFilterByType DEFLATE application/rss+xml
    AddOutputFilterByType DEFLATE application/vnd.ms-fontobject
    AddOutputFilterByType DEFLATE application/x-font
    AddOutputFilterByType DEFLATE application/x-font-opentype
    AddOutputFilterByType DEFLATE application/x-font-otf
    AddOutputFilterByType DEFLATE application/x-font-truetype
    AddOutputFilterByType DEFLATE application/x-font-ttf
    AddOutputFilterByType DEFLATE application/x-javascript
    AddOutputFilterByType DEFLATE application/xhtml+xml
    AddOutputFilterByType DEFLATE application/xml
    AddOutputFilterByType DEFLATE font/opentype
    AddOutputFilterByType DEFLATE font/otf
    AddOutputFilterByType DEFLATE font/ttf
    AddOutputFilterByType DEFLATE image/svg+xml
    AddOutputFilterByType DEFLATE image/x-icon
    AddOutputFilterByType DEFLATE text/css
    AddOutputFilterByType DEFLATE text/html
    AddOutputFilterByType DEFLATE text/javascript
    AddOutputFilterByType DEFLATE text/plain
    AddOutputFilterByType DEFLATE text/xml
    
    # Remove browser bugs (only needed for really old browsers)
    BrowserMatch ^Mozilla/4 gzip-only-text/html
    BrowserMatch ^Mozilla/4\.0[678] no-gzip
    BrowserMatch \bMSIE !no-gzip !gzip-only-text/html
    Header append Vary User-Agent
</IfModule>

<IfModule mod_headers.c>
    # Set cache control header
    <FilesMatch &quot;\.(ico|pdf|flv|jpg|jpeg|png|gif|webp|js|css|swf|x-html|css|xml|js|woff|woff2|ttf|svg|eot)(\.gz)?$&quot;>
        Header set Cache-Control &quot;max-age=31536000, public&quot;
    </FilesMatch>
    
    # Set ETag header
    FileETag MTime Size
    
    # Remove ETag header for files served with far-future expires headers
    <FilesMatch &quot;\.(ico|pdf|flv|jpg|jpeg|png|gif|webp|js|css|swf|x-html|css|xml|js|woff|woff2|ttf|svg|eot)(\.gz)?$&quot;>
        Header unset ETag
    </FilesMatch>
</IfModule>
# END AquaLuxe Browser Caching";

		return $rules;
	}

	/**
	 * Add admin menu for browser caching.
	 *
	 * @return void
	 */
	public function add_admin_menu() {
		add_submenu_page(
			'themes.php',
			__( 'Browser Caching', 'aqualuxe' ),
			__( 'Browser Caching', 'aqualuxe' ),
			'manage_options',
			'aqualuxe-browser-caching',
			array( $this, 'admin_page' )
		);
	}

	/**
	 * Register settings for browser caching.
	 *
	 * @return void
	 */
	public function register_settings() {
		register_setting( 'aqualuxe_browser_caching', 'aqualuxe_enable_browser_caching' );
		register_setting( 'aqualuxe_browser_caching', 'aqualuxe_cache_control' );
		register_setting( 'aqualuxe_browser_caching', 'aqualuxe_expires' );
		register_setting( 'aqualuxe_browser_caching', 'aqualuxe_enable_etag' );
		register_setting( 'aqualuxe_browser_caching', 'aqualuxe_enable_last_modified' );
		register_setting( 'aqualuxe_browser_caching', 'aqualuxe_enable_htaccess_rules' );
	}

	/**
	 * Admin page for browser caching.
	 *
	 * @return void
	 */
	public function admin_page() {
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'Browser Caching', 'aqualuxe' ); ?></h1>
			<p><?php esc_html_e( 'Browser caching allows browsers to store static resources locally, reducing the need for repeated downloads and improving page load performance.', 'aqualuxe' ); ?></p>
			
			<form method="post" action="options.php">
				<?php settings_fields( 'aqualuxe_browser_caching' ); ?>
				<?php do_settings_sections( 'aqualuxe_browser_caching' ); ?>
				
				<h2><?php esc_html_e( 'Browser Caching Settings', 'aqualuxe' ); ?></h2>
				<table class="form-table">
					<tr>
						<th scope="row"><?php esc_html_e( 'Enable Browser Caching', 'aqualuxe' ); ?></th>
						<td>
							<input type="checkbox" name="aqualuxe_enable_browser_caching" value="1" <?php checked( get_theme_mod( 'aqualuxe_enable_browser_caching', true ) ); ?> />
							<p class="description"><?php esc_html_e( 'Enable browser caching to improve page load performance.', 'aqualuxe' ); ?></p>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php esc_html_e( 'Cache-Control Header', 'aqualuxe' ); ?></th>
						<td>
							<input type="text" name="aqualuxe_cache_control" value="<?php echo esc_attr( get_theme_mod( 'aqualuxe_cache_control', 'public, max-age=31536000' ) ); ?>" class="regular-text" />
							<p class="description"><?php esc_html_e( 'The Cache-Control header value. Default: public, max-age=31536000 (1 year).', 'aqualuxe' ); ?></p>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php esc_html_e( 'Expires Header', 'aqualuxe' ); ?></th>
						<td>
							<input type="text" name="aqualuxe_expires" value="<?php echo esc_attr( get_theme_mod( 'aqualuxe_expires', '1 year' ) ); ?>" class="regular-text" />
							<p class="description"><?php esc_html_e( 'The Expires header value. Default: 1 year. Leave empty to disable.', 'aqualuxe' ); ?></p>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php esc_html_e( 'Enable ETag Header', 'aqualuxe' ); ?></th>
						<td>
							<input type="checkbox" name="aqualuxe_enable_etag" value="1" <?php checked( get_theme_mod( 'aqualuxe_enable_etag', true ) ); ?> />
							<p class="description"><?php esc_html_e( 'Enable ETag header for conditional requests.', 'aqualuxe' ); ?></p>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php esc_html_e( 'Enable Last-Modified Header', 'aqualuxe' ); ?></th>
						<td>
							<input type="checkbox" name="aqualuxe_enable_last_modified" value="1" <?php checked( get_theme_mod( 'aqualuxe_enable_last_modified', true ) ); ?> />
							<p class="description"><?php esc_html_e( 'Enable Last-Modified header for conditional requests.', 'aqualuxe' ); ?></p>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php esc_html_e( 'Add .htaccess Rules', 'aqualuxe' ); ?></th>
						<td>
							<input type="checkbox" name="aqualuxe_enable_htaccess_rules" value="1" <?php checked( get_theme_mod( 'aqualuxe_enable_htaccess_rules', false ) ); ?> />
							<p class="description"><?php esc_html_e( 'Add browser caching rules to .htaccess file. This works only on Apache servers with mod_expires and mod_headers enabled.', 'aqualuxe' ); ?></p>
							<?php if ( ! is_writable( ABSPATH . '.htaccess' ) ) : ?>
								<p class="description" style="color: red;"><?php esc_html_e( 'Warning: .htaccess file is not writable. You need to make it writable to add browser caching rules.', 'aqualuxe' ); ?></p>
							<?php endif; ?>
						</td>
					</tr>
				</table>
				
				<?php submit_button(); ?>
			</form>
			
			<h2><?php esc_html_e( '.htaccess Rules', 'aqualuxe' ); ?></h2>
			<p><?php esc_html_e( 'If you cannot add browser caching rules automatically, you can add the following rules to your .htaccess file manually:', 'aqualuxe' ); ?></p>
			<textarea readonly="readonly" class="large-text code" rows="20"><?php echo esc_textarea( $this->get_htaccess_rules() ); ?></textarea>
			
			<h2><?php esc_html_e( 'Browser Caching Test', 'aqualuxe' ); ?></h2>
			<p><?php esc_html_e( 'You can test if browser caching is working correctly by using the following tools:', 'aqualuxe' ); ?></p>
			<ul>
				<li><a href="https://developers.google.com/speed/pagespeed/insights/" target="_blank"><?php esc_html_e( 'Google PageSpeed Insights', 'aqualuxe' ); ?></a></li>
				<li><a href="https://www.webpagetest.org/" target="_blank"><?php esc_html_e( 'WebPageTest', 'aqualuxe' ); ?></a></li>
				<li><a href="https://tools.pingdom.com/" target="_blank"><?php esc_html_e( 'Pingdom Tools', 'aqualuxe' ); ?></a></li>
				<li><a href="https://gtmetrix.com/" target="_blank"><?php esc_html_e( 'GTmetrix', 'aqualuxe' ); ?></a></li>
			</ul>
		</div>
		<?php
	}
}