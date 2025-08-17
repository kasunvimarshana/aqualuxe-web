<?php
/**
 * Minification Handler
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
 * Minification Class
 *
 * Handles the minification and combination of CSS and JS files to improve page load performance.
 *
 * @since 1.1.0
 */
class Minification {

	/**
	 * Minified files cache.
	 *
	 * @var array
	 */
	private $cache = [];

	/**
	 * Initialize the class.
	 *
	 * @return void
	 */
	public function initialize() {
		// Create cache directory if it doesn't exist.
		$this->create_cache_directory();
	}

	/**
	 * Register hooks.
	 *
	 * @return void
	 */
	public function register_hooks() {
		// Skip if minification is disabled.
		if ( ! get_theme_mod( 'aqualuxe_enable_minification', true ) ) {
			return;
		}

		// Add minification hooks.
		add_action( 'wp_enqueue_scripts', array( $this, 'process_styles' ), 999 );
		add_action( 'wp_enqueue_scripts', array( $this, 'process_scripts' ), 999 );

		// Add admin menu.
		add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );

		// Add AJAX handler for cache clearing.
		add_action( 'wp_ajax_aqualuxe_clear_minification_cache', array( $this, 'ajax_clear_cache' ) );
	}

	/**
	 * Create cache directory.
	 *
	 * @return void
	 */
	private function create_cache_directory() {
		$cache_dir = $this->get_cache_directory();
		if ( ! file_exists( $cache_dir ) ) {
			wp_mkdir_p( $cache_dir );
		}

		// Create .htaccess file to protect cache directory.
		$htaccess_file = $cache_dir . '/.htaccess';
		if ( ! file_exists( $htaccess_file ) ) {
			$htaccess_content = "# Protect the cache directory\n";
			$htaccess_content .= "<IfModule mod_authz_core.c>\n";
			$htaccess_content .= "    Require all granted\n";
			$htaccess_content .= "</IfModule>\n";
			$htaccess_content .= "<IfModule !mod_authz_core.c>\n";
			$htaccess_content .= "    Order allow,deny\n";
			$htaccess_content .= "    Allow from all\n";
			$htaccess_content .= "</IfModule>\n";
			$htaccess_content .= "<IfModule mod_headers.c>\n";
			$htaccess_content .= "    Header set Cache-Control &quot;max-age=31536000, public&quot;\n";
			$htaccess_content .= "</IfModule>\n";
			file_put_contents( $htaccess_file, $htaccess_content );
		}

		// Create index.php file to prevent directory listing.
		$index_file = $cache_dir . '/index.php';
		if ( ! file_exists( $index_file ) ) {
			file_put_contents( $index_file, '<?php // Silence is golden.' );
		}
	}

	/**
	 * Get cache directory.
	 *
	 * @return string Cache directory path.
	 */
	private function get_cache_directory() {
		$upload_dir = wp_upload_dir();
		return $upload_dir['basedir'] . '/aqualuxe-cache';
	}

	/**
	 * Get cache URL.
	 *
	 * @return string Cache URL.
	 */
	private function get_cache_url() {
		$upload_dir = wp_upload_dir();
		return $upload_dir['baseurl'] . '/aqualuxe-cache';
	}

	/**
	 * Process styles.
	 *
	 * @return void
	 */
	public function process_styles() {
		// Skip if minification is disabled.
		if ( ! get_theme_mod( 'aqualuxe_enable_minification', true ) ) {
			return;
		}

		// Skip if CSS minification is disabled.
		if ( ! get_theme_mod( 'aqualuxe_minify_css', true ) ) {
			return;
		}

		// Skip if this is an admin page.
		if ( is_admin() ) {
			return;
		}

		// Skip if SCRIPT_DEBUG is enabled.
		if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) {
			return;
		}

		// Get all enqueued styles.
		global $wp_styles;
		if ( ! is_object( $wp_styles ) ) {
			return;
		}

		// Get styles to process.
		$styles = $this->get_styles_to_process();
		if ( empty( $styles ) ) {
			return;
		}

		// Process styles.
		$this->process_style_groups( $styles );
	}

	/**
	 * Get styles to process.
	 *
	 * @return array Styles to process.
	 */
	private function get_styles_to_process() {
		global $wp_styles;

		// Get all enqueued styles.
		$styles = array();
		foreach ( $wp_styles->queue as $handle ) {
			// Skip if style is not registered.
			if ( ! isset( $wp_styles->registered[ $handle ] ) ) {
				continue;
			}

			// Get style data.
			$style = $wp_styles->registered[ $handle ];

			// Skip if style has no source.
			if ( ! isset( $style->src ) || empty( $style->src ) ) {
				continue;
			}

			// Skip if style is a remote URL.
			if ( $this->is_remote_url( $style->src ) ) {
				continue;
			}

			// Skip if style is in the exclusion list.
			if ( $this->is_excluded_style( $handle ) ) {
				continue;
			}

			// Add style to the list.
			$styles[ $handle ] = $style;
		}

		return $styles;
	}

	/**
	 * Process style groups.
	 *
	 * @param array $styles Styles to process.
	 * @return void
	 */
	private function process_style_groups( $styles ) {
		global $wp_styles;

		// Skip if no styles to process.
		if ( empty( $styles ) ) {
			return;
		}

		// Group styles by media.
		$groups = array();
		foreach ( $styles as $handle => $style ) {
			$media = isset( $style->args ) ? $style->args : 'all';
			$groups[ $media ][] = $handle;
		}

		// Process each group.
		foreach ( $groups as $media => $handles ) {
			// Skip if group is empty.
			if ( empty( $handles ) ) {
				continue;
			}

			// Skip if group has only one style and combination is enabled.
			if ( count( $handles ) === 1 && get_theme_mod( 'aqualuxe_combine_css', true ) ) {
				$handle = $handles[0];
				$style = $styles[ $handle ];
				$minified_url = $this->minify_css( $style->src, $handle );
				if ( $minified_url ) {
					$wp_styles->registered[ $handle ]->src = $minified_url;
				}
				continue;
			}

			// Combine styles if enabled.
			if ( get_theme_mod( 'aqualuxe_combine_css', true ) ) {
				$combined_url = $this->combine_css( $handles, $media );
				if ( $combined_url ) {
					// Dequeue original styles.
					foreach ( $handles as $handle ) {
						wp_dequeue_style( $handle );
					}

					// Enqueue combined style.
					wp_enqueue_style( 'aqualuxe-combined-' . md5( implode( ',', $handles ) ), $combined_url, array(), null, $media );
				}
			} else {
				// Minify each style.
				foreach ( $handles as $handle ) {
					$style = $styles[ $handle ];
					$minified_url = $this->minify_css( $style->src, $handle );
					if ( $minified_url ) {
						$wp_styles->registered[ $handle ]->src = $minified_url;
					}
				}
			}
		}
	}

	/**
	 * Minify CSS.
	 *
	 * @param string $url    CSS URL.
	 * @param string $handle Style handle.
	 * @return string|bool Minified CSS URL or false on failure.
	 */
	private function minify_css( $url, $handle ) {
		// Skip if URL is empty.
		if ( empty( $url ) ) {
			return false;
		}

		// Skip if URL is a data URI.
		if ( strpos( $url, 'data:' ) === 0 ) {
			return false;
		}

		// Get file path from URL.
		$file_path = $this->get_file_path_from_url( $url );
		if ( ! $file_path || ! file_exists( $file_path ) ) {
			return false;
		}

		// Get file content.
		$content = file_get_contents( $file_path );
		if ( ! $content ) {
			return false;
		}

		// Minify CSS.
		$minified = $this->minify_css_content( $content );
		if ( ! $minified ) {
			return false;
		}

		// Create cache file.
		$cache_file = $this->get_cache_directory() . '/css/' . md5( $url ) . '.css';
		$cache_url = $this->get_cache_url() . '/css/' . md5( $url ) . '.css';

		// Create directory if it doesn't exist.
		wp_mkdir_p( dirname( $cache_file ) );

		// Save minified CSS to cache file.
		if ( file_put_contents( $cache_file, $minified ) ) {
			return $cache_url;
		}

		return false;
	}

	/**
	 * Combine CSS.
	 *
	 * @param array  $handles Style handles.
	 * @param string $media   Media attribute.
	 * @return string|bool Combined CSS URL or false on failure.
	 */
	private function combine_css( $handles, $media ) {
		global $wp_styles;

		// Skip if no handles.
		if ( empty( $handles ) ) {
			return false;
		}

		// Create cache file name.
		$cache_key = md5( implode( ',', $handles ) . $media );
		$cache_file = $this->get_cache_directory() . '/css/' . $cache_key . '.css';
		$cache_url = $this->get_cache_url() . '/css/' . $cache_key . '.css';

		// Check if cache file exists and is newer than all source files.
		if ( file_exists( $cache_file ) ) {
			$cache_time = filemtime( $cache_file );
			$is_valid = true;

			foreach ( $handles as $handle ) {
				$style = $wp_styles->registered[ $handle ];
				$file_path = $this->get_file_path_from_url( $style->src );
				if ( $file_path && file_exists( $file_path ) && filemtime( $file_path ) > $cache_time ) {
					$is_valid = false;
					break;
				}
			}

			if ( $is_valid ) {
				return $cache_url;
			}
		}

		// Create directory if it doesn't exist.
		wp_mkdir_p( dirname( $cache_file ) );

		// Combine CSS.
		$combined = '';
		foreach ( $handles as $handle ) {
			$style = $wp_styles->registered[ $handle ];
			$file_path = $this->get_file_path_from_url( $style->src );
			if ( $file_path && file_exists( $file_path ) ) {
				$content = file_get_contents( $file_path );
				if ( $content ) {
					// Fix relative URLs.
					$content = $this->fix_relative_urls( $content, dirname( $style->src ) );
					// Minify CSS.
					$content = $this->minify_css_content( $content );
					// Add to combined CSS.
					$combined .= "/* {$handle} */\n{$content}\n";
				}
			}
		}

		// Save combined CSS to cache file.
		if ( file_put_contents( $cache_file, $combined ) ) {
			return $cache_url;
		}

		return false;
	}

	/**
	 * Fix relative URLs in CSS.
	 *
	 * @param string $css      CSS content.
	 * @param string $base_url Base URL.
	 * @return string Modified CSS content.
	 */
	private function fix_relative_urls( $css, $base_url ) {
		// Add trailing slash to base URL.
		$base_url = trailingslashit( $base_url );

		// Fix relative URLs.
		$pattern = '/url\s*\(\s*[\'"]?\s*([^\'"\)]+)\s*[\'"]?\s*\)/i';
		$css = preg_replace_callback( $pattern, function( $matches ) use ( $base_url ) {
			$url = $matches[1];

			// Skip if URL is absolute.
			if ( preg_match( '/^(https?:\/\/|\/\/|data:)/', $url ) ) {
				return $matches[0];
			}

			// Skip if URL is root-relative.
			if ( strpos( $url, '/' ) === 0 ) {
				return $matches[0];
			}

			// Make URL absolute.
			$absolute_url = $base_url . $url;

			return 'url("' . $absolute_url . '")';
		}, $css );

		return $css;
	}

	/**
	 * Minify CSS content.
	 *
	 * @param string $css CSS content.
	 * @return string Minified CSS content.
	 */
	private function minify_css_content( $css ) {
		// Remove comments.
		$css = preg_replace( '!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css );

		// Remove whitespace.
		$css = preg_replace( '/\s+/', ' ', $css );
		$css = preg_replace( '/\s*({|}|;|,|:|>)\s*/', '$1', $css );
		$css = preg_replace( '/;}/', '}', $css );

		return trim( $css );
	}

	/**
	 * Process scripts.
	 *
	 * @return void
	 */
	public function process_scripts() {
		// Skip if minification is disabled.
		if ( ! get_theme_mod( 'aqualuxe_enable_minification', true ) ) {
			return;
		}

		// Skip if JS minification is disabled.
		if ( ! get_theme_mod( 'aqualuxe_minify_js', true ) ) {
			return;
		}

		// Skip if this is an admin page.
		if ( is_admin() ) {
			return;
		}

		// Skip if SCRIPT_DEBUG is enabled.
		if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) {
			return;
		}

		// Get all enqueued scripts.
		global $wp_scripts;
		if ( ! is_object( $wp_scripts ) ) {
			return;
		}

		// Get scripts to process.
		$scripts = $this->get_scripts_to_process();
		if ( empty( $scripts ) ) {
			return;
		}

		// Process scripts.
		$this->process_script_groups( $scripts );
	}

	/**
	 * Get scripts to process.
	 *
	 * @return array Scripts to process.
	 */
	private function get_scripts_to_process() {
		global $wp_scripts;

		// Get all enqueued scripts.
		$scripts = array();
		foreach ( $wp_scripts->queue as $handle ) {
			// Skip if script is not registered.
			if ( ! isset( $wp_scripts->registered[ $handle ] ) ) {
				continue;
			}

			// Get script data.
			$script = $wp_scripts->registered[ $handle ];

			// Skip if script has no source.
			if ( ! isset( $script->src ) || empty( $script->src ) ) {
				continue;
			}

			// Skip if script is a remote URL.
			if ( $this->is_remote_url( $script->src ) ) {
				continue;
			}

			// Skip if script is in the exclusion list.
			if ( $this->is_excluded_script( $handle ) ) {
				continue;
			}

			// Add script to the list.
			$scripts[ $handle ] = $script;
		}

		return $scripts;
	}

	/**
	 * Process script groups.
	 *
	 * @param array $scripts Scripts to process.
	 * @return void
	 */
	private function process_script_groups( $scripts ) {
		global $wp_scripts;

		// Skip if no scripts to process.
		if ( empty( $scripts ) ) {
			return;
		}

		// Group scripts by location (header/footer).
		$groups = array(
			'header' => array(),
			'footer' => array(),
		);

		foreach ( $scripts as $handle => $script ) {
			$in_footer = isset( $script->extra['group'] ) && $script->extra['group'] === 1;
			$groups[ $in_footer ? 'footer' : 'header' ][] = $handle;
		}

		// Process each group.
		foreach ( $groups as $location => $handles ) {
			// Skip if group is empty.
			if ( empty( $handles ) ) {
				continue;
			}

			// Skip if group has only one script and combination is enabled.
			if ( count( $handles ) === 1 && get_theme_mod( 'aqualuxe_combine_js', true ) ) {
				$handle = $handles[0];
				$script = $scripts[ $handle ];
				$minified_url = $this->minify_js( $script->src, $handle );
				if ( $minified_url ) {
					$wp_scripts->registered[ $handle ]->src = $minified_url;
				}
				continue;
			}

			// Combine scripts if enabled.
			if ( get_theme_mod( 'aqualuxe_combine_js', true ) ) {
				$combined_url = $this->combine_js( $handles, $location );
				if ( $combined_url ) {
					// Dequeue original scripts.
					foreach ( $handles as $handle ) {
						wp_dequeue_script( $handle );
					}

					// Enqueue combined script.
					wp_enqueue_script( 'aqualuxe-combined-' . md5( implode( ',', $handles ) ), $combined_url, array(), null, $location === 'footer' );
				}
			} else {
				// Minify each script.
				foreach ( $handles as $handle ) {
					$script = $scripts[ $handle ];
					$minified_url = $this->minify_js( $script->src, $handle );
					if ( $minified_url ) {
						$wp_scripts->registered[ $handle ]->src = $minified_url;
					}
				}
			}
		}
	}

	/**
	 * Minify JS.
	 *
	 * @param string $url    JS URL.
	 * @param string $handle Script handle.
	 * @return string|bool Minified JS URL or false on failure.
	 */
	private function minify_js( $url, $handle ) {
		// Skip if URL is empty.
		if ( empty( $url ) ) {
			return false;
		}

		// Skip if URL is a data URI.
		if ( strpos( $url, 'data:' ) === 0 ) {
			return false;
		}

		// Get file path from URL.
		$file_path = $this->get_file_path_from_url( $url );
		if ( ! $file_path || ! file_exists( $file_path ) ) {
			return false;
		}

		// Get file content.
		$content = file_get_contents( $file_path );
		if ( ! $content ) {
			return false;
		}

		// Minify JS.
		$minified = $this->minify_js_content( $content );
		if ( ! $minified ) {
			return false;
		}

		// Create cache file.
		$cache_file = $this->get_cache_directory() . '/js/' . md5( $url ) . '.js';
		$cache_url = $this->get_cache_url() . '/js/' . md5( $url ) . '.js';

		// Create directory if it doesn't exist.
		wp_mkdir_p( dirname( $cache_file ) );

		// Save minified JS to cache file.
		if ( file_put_contents( $cache_file, $minified ) ) {
			return $cache_url;
		}

		return false;
	}

	/**
	 * Combine JS.
	 *
	 * @param array  $handles  Script handles.
	 * @param string $location Script location (header/footer).
	 * @return string|bool Combined JS URL or false on failure.
	 */
	private function combine_js( $handles, $location ) {
		global $wp_scripts;

		// Skip if no handles.
		if ( empty( $handles ) ) {
			return false;
		}

		// Create cache file name.
		$cache_key = md5( implode( ',', $handles ) . $location );
		$cache_file = $this->get_cache_directory() . '/js/' . $cache_key . '.js';
		$cache_url = $this->get_cache_url() . '/js/' . $cache_key . '.js';

		// Check if cache file exists and is newer than all source files.
		if ( file_exists( $cache_file ) ) {
			$cache_time = filemtime( $cache_file );
			$is_valid = true;

			foreach ( $handles as $handle ) {
				$script = $wp_scripts->registered[ $handle ];
				$file_path = $this->get_file_path_from_url( $script->src );
				if ( $file_path && file_exists( $file_path ) && filemtime( $file_path ) > $cache_time ) {
					$is_valid = false;
					break;
				}
			}

			if ( $is_valid ) {
				return $cache_url;
			}
		}

		// Create directory if it doesn't exist.
		wp_mkdir_p( dirname( $cache_file ) );

		// Combine JS.
		$combined = '';
		foreach ( $handles as $handle ) {
			$script = $wp_scripts->registered[ $handle ];
			$file_path = $this->get_file_path_from_url( $script->src );
			if ( $file_path && file_exists( $file_path ) ) {
				$content = file_get_contents( $file_path );
				if ( $content ) {
					// Minify JS.
					$content = $this->minify_js_content( $content );
					// Add to combined JS.
					$combined .= "/* {$handle} */\n{$content};\n";
				}
			}
		}

		// Save combined JS to cache file.
		if ( file_put_contents( $cache_file, $combined ) ) {
			return $cache_url;
		}

		return false;
	}

	/**
	 * Minify JS content.
	 *
	 * @param string $js JS content.
	 * @return string Minified JS content.
	 */
	private function minify_js_content( $js ) {
		// Check if JSMin is available.
		if ( class_exists( 'JSMin' ) ) {
			try {
				return \JSMin::minify( $js );
			} catch ( \Exception $e ) {
				// JSMin failed, use fallback.
			}
		}

		// Fallback: Simple minification.
		// Remove comments.
		$js = preg_replace( '!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $js );
		$js = preg_replace( '!//.*$!m', '', $js );

		// Remove whitespace.
		$js = preg_replace( '/\s+/', ' ', $js );
		$js = preg_replace( '/\s*({|}|;|,|:|=|\+|-|\*|\/|>|<|&|\||\?|!|%|\^)\s*/', '$1', $js );

		return trim( $js );
	}

	/**
	 * Check if URL is remote.
	 *
	 * @param string $url URL to check.
	 * @return bool True if URL is remote.
	 */
	private function is_remote_url( $url ) {
		// Skip if URL is empty.
		if ( empty( $url ) ) {
			return true;
		}

		// Skip if URL is a data URI.
		if ( strpos( $url, 'data:' ) === 0 ) {
			return true;
		}

		// Check if URL is from this site.
		$site_url = site_url();
		$site_url = preg_replace( '/^https?:\/\//', '', $site_url );
		$url = preg_replace( '/^https?:\/\//', '', $url );

		return strpos( $url, $site_url ) !== 0;
	}

	/**
	 * Get file path from URL.
	 *
	 * @param string $url URL to get file path from.
	 * @return string|bool File path or false on failure.
	 */
	private function get_file_path_from_url( $url ) {
		// Skip if URL is empty.
		if ( empty( $url ) ) {
			return false;
		}

		// Skip if URL is a data URI.
		if ( strpos( $url, 'data:' ) === 0 ) {
			return false;
		}

		// Remove query string.
		$url = strtok( $url, '?' );

		// Get file path from URL.
		$file_path = '';

		// Check if URL is from theme.
		$theme_url = get_template_directory_uri();
		if ( strpos( $url, $theme_url ) === 0 ) {
			$file_path = str_replace( $theme_url, get_template_directory(), $url );
		}

		// Check if URL is from child theme.
		if ( empty( $file_path ) && get_stylesheet_directory_uri() !== get_template_directory_uri() ) {
			$child_theme_url = get_stylesheet_directory_uri();
			if ( strpos( $url, $child_theme_url ) === 0 ) {
				$file_path = str_replace( $child_theme_url, get_stylesheet_directory(), $url );
			}
		}

		// Check if URL is from plugins.
		if ( empty( $file_path ) ) {
			$plugins_url = plugins_url();
			if ( strpos( $url, $plugins_url ) === 0 ) {
				$file_path = str_replace( $plugins_url, WP_PLUGIN_DIR, $url );
			}
		}

		// Check if URL is from content directory.
		if ( empty( $file_path ) ) {
			$content_url = content_url();
			if ( strpos( $url, $content_url ) === 0 ) {
				$file_path = str_replace( $content_url, WP_CONTENT_DIR, $url );
			}
		}

		// Check if URL is from includes directory.
		if ( empty( $file_path ) ) {
			$includes_url = includes_url();
			if ( strpos( $url, $includes_url ) === 0 ) {
				$file_path = str_replace( $includes_url, ABSPATH . WPINC, $url );
			}
		}

		// Check if URL is from admin directory.
		if ( empty( $file_path ) ) {
			$admin_url = admin_url();
			if ( strpos( $url, $admin_url ) === 0 ) {
				$file_path = str_replace( $admin_url, ABSPATH . 'wp-admin', $url );
			}
		}

		// Check if URL is from site root.
		if ( empty( $file_path ) ) {
			$site_url = site_url();
			if ( strpos( $url, $site_url ) === 0 ) {
				$file_path = str_replace( $site_url, ABSPATH, $url );
			}
		}

		// Check if file exists.
		if ( ! empty( $file_path ) && file_exists( $file_path ) ) {
			return $file_path;
		}

		return false;
	}

	/**
	 * Check if style is excluded.
	 *
	 * @param string $handle Style handle.
	 * @return bool True if style is excluded.
	 */
	private function is_excluded_style( $handle ) {
		// Get excluded styles.
		$excluded = get_theme_mod( 'aqualuxe_excluded_styles', array() );
		if ( empty( $excluded ) ) {
			$excluded = array();
		}

		// Add default excluded styles.
		$excluded = array_merge( $excluded, array(
			'admin-bar',
			'dashicons',
			'wp-block-library',
			'wp-block-library-theme',
			'wp-mediaelement',
			'wp-embed',
		) );

		return in_array( $handle, $excluded, true );
	}

	/**
	 * Check if script is excluded.
	 *
	 * @param string $handle Script handle.
	 * @return bool True if script is excluded.
	 */
	private function is_excluded_script( $handle ) {
		// Get excluded scripts.
		$excluded = get_theme_mod( 'aqualuxe_excluded_scripts', array() );
		if ( empty( $excluded ) ) {
			$excluded = array();
		}

		// Add default excluded scripts.
		$excluded = array_merge( $excluded, array(
			'jquery',
			'jquery-core',
			'jquery-migrate',
			'admin-bar',
			'wp-mediaelement',
			'wp-embed',
		) );

		return in_array( $handle, $excluded, true );
	}

	/**
	 * Clear cache.
	 *
	 * @return bool True on success, false on failure.
	 */
	public function clear_cache() {
		$cache_dir = $this->get_cache_directory();
		if ( ! file_exists( $cache_dir ) ) {
			return true;
		}

		// Clear CSS cache.
		$css_dir = $cache_dir . '/css';
		if ( file_exists( $css_dir ) ) {
			$files = glob( $css_dir . '/*.css' );
			foreach ( $files as $file ) {
				if ( is_file( $file ) ) {
					unlink( $file );
				}
			}
		}

		// Clear JS cache.
		$js_dir = $cache_dir . '/js';
		if ( file_exists( $js_dir ) ) {
			$files = glob( $js_dir . '/*.js' );
			foreach ( $files as $file ) {
				if ( is_file( $file ) ) {
					unlink( $file );
				}
			}
		}

		return true;
	}

	/**
	 * AJAX handler for clearing cache.
	 *
	 * @return void
	 */
	public function ajax_clear_cache() {
		// Check nonce.
		if ( ! check_ajax_referer( 'aqualuxe_clear_minification_cache', 'nonce', false ) ) {
			wp_send_json_error( array( 'message' => __( 'Invalid nonce.', 'aqualuxe' ) ) );
		}

		// Check user capability.
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array( 'message' => __( 'You do not have permission to clear cache.', 'aqualuxe' ) ) );
		}

		// Clear cache.
		$result = $this->clear_cache();
		if ( $result ) {
			wp_send_json_success( array( 'message' => __( 'Cache cleared successfully.', 'aqualuxe' ) ) );
		} else {
			wp_send_json_error( array( 'message' => __( 'Failed to clear cache.', 'aqualuxe' ) ) );
		}
	}

	/**
	 * Add admin menu for minification.
	 *
	 * @return void
	 */
	public function add_admin_menu() {
		add_submenu_page(
			'themes.php',
			__( 'Minification', 'aqualuxe' ),
			__( 'Minification', 'aqualuxe' ),
			'manage_options',
			'aqualuxe-minification',
			array( $this, 'admin_page' )
		);
	}

	/**
	 * Register settings for minification.
	 *
	 * @return void
	 */
	public function register_settings() {
		register_setting( 'aqualuxe_minification', 'aqualuxe_enable_minification' );
		register_setting( 'aqualuxe_minification', 'aqualuxe_minify_css' );
		register_setting( 'aqualuxe_minification', 'aqualuxe_minify_js' );
		register_setting( 'aqualuxe_minification', 'aqualuxe_combine_css' );
		register_setting( 'aqualuxe_minification', 'aqualuxe_combine_js' );
		register_setting( 'aqualuxe_minification', 'aqualuxe_excluded_styles' );
		register_setting( 'aqualuxe_minification', 'aqualuxe_excluded_scripts' );
	}

	/**
	 * Admin page for minification.
	 *
	 * @return void
	 */
	public function admin_page() {
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'Minification', 'aqualuxe' ); ?></h1>
			<p><?php esc_html_e( 'Minification reduces the size of CSS and JavaScript files by removing whitespace, comments, and other unnecessary characters. Combining files reduces the number of HTTP requests, which can improve page load performance.', 'aqualuxe' ); ?></p>
			
			<form method="post" action="options.php">
				<?php settings_fields( 'aqualuxe_minification' ); ?>
				<?php do_settings_sections( 'aqualuxe_minification' ); ?>
				
				<h2><?php esc_html_e( 'Minification Settings', 'aqualuxe' ); ?></h2>
				<table class="form-table">
					<tr>
						<th scope="row"><?php esc_html_e( 'Enable Minification', 'aqualuxe' ); ?></th>
						<td>
							<input type="checkbox" name="aqualuxe_enable_minification" value="1" <?php checked( get_theme_mod( 'aqualuxe_enable_minification', true ) ); ?> />
							<p class="description"><?php esc_html_e( 'Enable minification to improve page load performance.', 'aqualuxe' ); ?></p>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php esc_html_e( 'Minify CSS', 'aqualuxe' ); ?></th>
						<td>
							<input type="checkbox" name="aqualuxe_minify_css" value="1" <?php checked( get_theme_mod( 'aqualuxe_minify_css', true ) ); ?> />
							<p class="description"><?php esc_html_e( 'Minify CSS files to reduce their size.', 'aqualuxe' ); ?></p>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php esc_html_e( 'Minify JavaScript', 'aqualuxe' ); ?></th>
						<td>
							<input type="checkbox" name="aqualuxe_minify_js" value="1" <?php checked( get_theme_mod( 'aqualuxe_minify_js', true ) ); ?> />
							<p class="description"><?php esc_html_e( 'Minify JavaScript files to reduce their size.', 'aqualuxe' ); ?></p>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php esc_html_e( 'Combine CSS', 'aqualuxe' ); ?></th>
						<td>
							<input type="checkbox" name="aqualuxe_combine_css" value="1" <?php checked( get_theme_mod( 'aqualuxe_combine_css', true ) ); ?> />
							<p class="description"><?php esc_html_e( 'Combine CSS files to reduce the number of HTTP requests.', 'aqualuxe' ); ?></p>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php esc_html_e( 'Combine JavaScript', 'aqualuxe' ); ?></th>
						<td>
							<input type="checkbox" name="aqualuxe_combine_js" value="1" <?php checked( get_theme_mod( 'aqualuxe_combine_js', true ) ); ?> />
							<p class="description"><?php esc_html_e( 'Combine JavaScript files to reduce the number of HTTP requests.', 'aqualuxe' ); ?></p>
						</td>
					</tr>
				</table>
				
				<h2><?php esc_html_e( 'Exclusions', 'aqualuxe' ); ?></h2>
				<p><?php esc_html_e( 'Exclude specific CSS and JavaScript files from minification and combination.', 'aqualuxe' ); ?></p>
				
				<h3><?php esc_html_e( 'Excluded CSS Files', 'aqualuxe' ); ?></h3>
				<div id="excluded-styles">
					<?php
					$excluded_styles = get_theme_mod( 'aqualuxe_excluded_styles', array() );
					if ( ! empty( $excluded_styles ) ) {
						foreach ( $excluded_styles as $index => $handle ) {
							?>
							<div class="excluded-item">
								<input type="text" name="aqualuxe_excluded_styles[]" value="<?php echo esc_attr( $handle ); ?>" class="regular-text" />
								<button type="button" class="button remove-item"><?php esc_html_e( 'Remove', 'aqualuxe' ); ?></button>
							</div>
							<?php
						}
					} else {
						?>
						<div class="excluded-item">
							<input type="text" name="aqualuxe_excluded_styles[]" value="" class="regular-text" />
							<button type="button" class="button remove-item"><?php esc_html_e( 'Remove', 'aqualuxe' ); ?></button>
						</div>
						<?php
					}
					?>
				</div>
				<button type="button" class="button add-excluded-style"><?php esc_html_e( 'Add Excluded CSS File', 'aqualuxe' ); ?></button>
				
				<h3><?php esc_html_e( 'Excluded JavaScript Files', 'aqualuxe' ); ?></h3>
				<div id="excluded-scripts">
					<?php
					$excluded_scripts = get_theme_mod( 'aqualuxe_excluded_scripts', array() );
					if ( ! empty( $excluded_scripts ) ) {
						foreach ( $excluded_scripts as $index => $handle ) {
							?>
							<div class="excluded-item">
								<input type="text" name="aqualuxe_excluded_scripts[]" value="<?php echo esc_attr( $handle ); ?>" class="regular-text" />
								<button type="button" class="button remove-item"><?php esc_html_e( 'Remove', 'aqualuxe' ); ?></button>
							</div>
							<?php
						}
					} else {
						?>
						<div class="excluded-item">
							<input type="text" name="aqualuxe_excluded_scripts[]" value="" class="regular-text" />
							<button type="button" class="button remove-item"><?php esc_html_e( 'Remove', 'aqualuxe' ); ?></button>
						</div>
						<?php
					}
					?>
				</div>
				<button type="button" class="button add-excluded-script"><?php esc_html_e( 'Add Excluded JavaScript File', 'aqualuxe' ); ?></button>
				
				<?php submit_button(); ?>
			</form>
			
			<h2><?php esc_html_e( 'Clear Cache', 'aqualuxe' ); ?></h2>
			<p><?php esc_html_e( 'Clear the minification cache to regenerate minified and combined files.', 'aqualuxe' ); ?></p>
			<p><button id="clear-cache" class="button button-primary"><?php esc_html_e( 'Clear Cache', 'aqualuxe' ); ?></button></p>
			
			<script>
				jQuery(document).ready(function($) {
					// Add excluded style.
					$('.add-excluded-style').on('click', function() {
						var item = '<div class="excluded-item">' +
							'<input type="text" name="aqualuxe_excluded_styles[]" value="" class="regular-text" />' +
							'<button type="button" class="button remove-item"><?php esc_html_e( 'Remove', 'aqualuxe' ); ?></button>' +
							'</div>';
						$('#excluded-styles').append(item);
					});
					
					// Add excluded script.
					$('.add-excluded-script').on('click', function() {
						var item = '<div class="excluded-item">' +
							'<input type="text" name="aqualuxe_excluded_scripts[]" value="" class="regular-text" />' +
							'<button type="button" class="button remove-item"><?php esc_html_e( 'Remove', 'aqualuxe' ); ?></button>' +
							'</div>';
						$('#excluded-scripts').append(item);
					});
					
					// Remove item.
					$(document).on('click', '.remove-item', function() {
						$(this).parent().remove();
					});
					
					// Clear cache.
					$('#clear-cache').on('click', function() {
						$.ajax({
							url: ajaxurl,
							type: 'POST',
							data: {
								action: 'aqualuxe_clear_minification_cache',
								nonce: '<?php echo esc_js( wp_create_nonce( 'aqualuxe_clear_minification_cache' ) ); ?>'
							},
							success: function(response) {
								if (response.success) {
									alert(response.data.message);
								} else {
									alert(response.data.message);
								}
							},
							error: function() {
								alert('<?php esc_html_e( 'An error occurred while clearing cache.', 'aqualuxe' ); ?>');
							}
						});
					});
				});
			</script>
			
			<style>
				.excluded-item {
					margin-bottom: 10px;
				}
				.excluded-item input {
					margin-right: 10px;
				}
			</style>
		</div>
		<?php
	}
}