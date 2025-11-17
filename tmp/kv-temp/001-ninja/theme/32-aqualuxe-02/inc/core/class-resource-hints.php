<?php
/**
 * Resource Hints Handler
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
 * Resource Hints Class
 *
 * Handles the implementation of resource hints (preconnect, prefetch, preload, dns-prefetch)
 * to improve page load performance.
 *
 * @since 1.1.0
 */
class Resource_Hints {

	/**
	 * External domains to preconnect to.
	 *
	 * @var array
	 */
	private $preconnect_domains = [];

	/**
	 * Resources to prefetch.
	 *
	 * @var array
	 */
	private $prefetch_resources = [];

	/**
	 * Resources to preload.
	 *
	 * @var array
	 */
	private $preload_resources = [];

	/**
	 * Domains to DNS prefetch.
	 *
	 * @var array
	 */
	private $dns_prefetch_domains = [];

	/**
	 * Initialize the class.
	 *
	 * @return void
	 */
	public function initialize() {
		$this->setup_default_hints();
	}

	/**
	 * Register hooks.
	 *
	 * @return void
	 */
	public function register_hooks() {
		add_filter( 'wp_resource_hints', array( $this, 'add_resource_hints' ), 10, 2 );
		add_action( 'wp_head', array( $this, 'add_preload_hints' ), 2 );
		add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
	}

	/**
	 * Setup default resource hints.
	 *
	 * @return void
	 */
	private function setup_default_hints() {
		// Get resource hints from options.
		$this->preconnect_domains = get_option( 'aqualuxe_preconnect_domains', [] );
		$this->prefetch_resources = get_option( 'aqualuxe_prefetch_resources', [] );
		$this->preload_resources = get_option( 'aqualuxe_preload_resources', [] );
		$this->dns_prefetch_domains = get_option( 'aqualuxe_dns_prefetch_domains', [] );

		// If no preconnect domains are set, add default ones.
		if ( empty( $this->preconnect_domains ) ) {
			$this->preconnect_domains = $this->get_default_preconnect_domains();
		}

		// If no DNS prefetch domains are set, add default ones.
		if ( empty( $this->dns_prefetch_domains ) ) {
			$this->dns_prefetch_domains = $this->get_default_dns_prefetch_domains();
		}

		// If no preload resources are set, add default ones.
		if ( empty( $this->preload_resources ) ) {
			$this->preload_resources = $this->get_default_preload_resources();
		}
	}

	/**
	 * Get default preconnect domains.
	 *
	 * @return array Default preconnect domains.
	 */
	private function get_default_preconnect_domains() {
		$domains = [
			'https://fonts.googleapis.com',
			'https://fonts.gstatic.com',
		];

		// Add Google Analytics if enabled.
		if ( get_theme_mod( 'aqualuxe_enable_google_analytics', false ) ) {
			$domains[] = 'https://www.google-analytics.com';
		}

		// Add WooCommerce external domains if WooCommerce is active.
		if ( class_exists( 'WooCommerce' ) ) {
			$domains[] = 'https://stats.wp.com'; // For WooCommerce analytics.
		}

		return $domains;
	}

	/**
	 * Get default DNS prefetch domains.
	 *
	 * @return array Default DNS prefetch domains.
	 */
	private function get_default_dns_prefetch_domains() {
		$domains = [
			'https://s.w.org',
		];

		// Add Gravatar if comments are enabled.
		if ( get_option( 'default_comment_status' ) === 'open' ) {
			$domains[] = 'https://secure.gravatar.com';
		}

		return $domains;
	}

	/**
	 * Get default preload resources.
	 *
	 * @return array Default preload resources.
	 */
	private function get_default_preload_resources() {
		$resources = [];

		// Add theme logo if available.
		$custom_logo_id = get_theme_mod( 'custom_logo' );
		if ( $custom_logo_id ) {
			$logo_url = wp_get_attachment_image_url( $custom_logo_id, 'full' );
			if ( $logo_url ) {
				$resources[] = [
					'url' => $logo_url,
					'type' => 'image',
					'media' => 'all',
				];
			}
		}

		// Add theme fonts.
		$resources[] = [
			'url' => 'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Playfair+Display:wght@400;700&display=swap',
			'type' => 'font',
			'media' => 'all',
		];

		return $resources;
	}

	/**
	 * Add resource hints to WordPress.
	 *
	 * @param array  $urls  URLs to print for resource hints.
	 * @param string $relation_type The relation type the URLs are printed for.
	 * @return array Modified URLs.
	 */
	public function add_resource_hints( $urls, $relation_type ) {
		// Skip if resource hints are disabled.
		if ( ! get_theme_mod( 'aqualuxe_enable_resource_hints', true ) ) {
			return $urls;
		}

		switch ( $relation_type ) {
			case 'preconnect':
				// Add preconnect domains.
				foreach ( $this->preconnect_domains as $domain ) {
					if ( ! empty( $domain ) ) {
						$urls[] = [
							'href' => $domain,
							'crossorigin' => 'anonymous',
						];
					}
				}
				break;

			case 'prefetch':
				// Add prefetch resources.
				foreach ( $this->prefetch_resources as $resource ) {
					if ( ! empty( $resource ) ) {
						$urls[] = $resource;
					}
				}
				break;

			case 'dns-prefetch':
				// Add DNS prefetch domains.
				foreach ( $this->dns_prefetch_domains as $domain ) {
					if ( ! empty( $domain ) ) {
						$urls[] = $domain;
					}
				}
				break;
		}

		return $urls;
	}

	/**
	 * Add preload hints to the head.
	 *
	 * @return void
	 */
	public function add_preload_hints() {
		// Skip if resource hints are disabled.
		if ( ! get_theme_mod( 'aqualuxe_enable_resource_hints', true ) ) {
			return;
		}

		// Add preload resources.
		foreach ( $this->preload_resources as $resource ) {
			if ( ! empty( $resource['url'] ) && ! empty( $resource['type'] ) ) {
				$media = ! empty( $resource['media'] ) ? ' media="' . esc_attr( $resource['media'] ) . '"' : '';
				echo '<link rel="preload" href="' . esc_url( $resource['url'] ) . '" as="' . esc_attr( $resource['type'] ) . '"' . $media . ' crossorigin="anonymous">';
			}
		}
	}

	/**
	 * Add admin menu for resource hints.
	 *
	 * @return void
	 */
	public function add_admin_menu() {
		add_submenu_page(
			'themes.php',
			__( 'Resource Hints', 'aqualuxe' ),
			__( 'Resource Hints', 'aqualuxe' ),
			'manage_options',
			'aqualuxe-resource-hints',
			array( $this, 'admin_page' )
		);
	}

	/**
	 * Register settings for resource hints.
	 *
	 * @return void
	 */
	public function register_settings() {
		register_setting( 'aqualuxe_resource_hints', 'aqualuxe_preconnect_domains', array( $this, 'sanitize_domains' ) );
		register_setting( 'aqualuxe_resource_hints', 'aqualuxe_prefetch_resources', array( $this, 'sanitize_resources' ) );
		register_setting( 'aqualuxe_resource_hints', 'aqualuxe_preload_resources', array( $this, 'sanitize_preload_resources' ) );
		register_setting( 'aqualuxe_resource_hints', 'aqualuxe_dns_prefetch_domains', array( $this, 'sanitize_domains' ) );
	}

	/**
	 * Sanitize domains.
	 *
	 * @param array $domains Domains to sanitize.
	 * @return array Sanitized domains.
	 */
	public function sanitize_domains( $domains ) {
		if ( ! is_array( $domains ) ) {
			return [];
		}

		$sanitized_domains = [];
		foreach ( $domains as $domain ) {
			$domain = trim( $domain );
			if ( ! empty( $domain ) ) {
				$sanitized_domains[] = esc_url_raw( $domain );
			}
		}

		return $sanitized_domains;
	}

	/**
	 * Sanitize resources.
	 *
	 * @param array $resources Resources to sanitize.
	 * @return array Sanitized resources.
	 */
	public function sanitize_resources( $resources ) {
		if ( ! is_array( $resources ) ) {
			return [];
		}

		$sanitized_resources = [];
		foreach ( $resources as $resource ) {
			$resource = trim( $resource );
			if ( ! empty( $resource ) ) {
				$sanitized_resources[] = esc_url_raw( $resource );
			}
		}

		return $sanitized_resources;
	}

	/**
	 * Sanitize preload resources.
	 *
	 * @param array $resources Resources to sanitize.
	 * @return array Sanitized resources.
	 */
	public function sanitize_preload_resources( $resources ) {
		if ( ! is_array( $resources ) ) {
			return [];
		}

		$sanitized_resources = [];
		foreach ( $resources as $resource ) {
			if ( ! empty( $resource['url'] ) && ! empty( $resource['type'] ) ) {
				$sanitized_resources[] = [
					'url' => esc_url_raw( $resource['url'] ),
					'type' => sanitize_text_field( $resource['type'] ),
					'media' => ! empty( $resource['media'] ) ? sanitize_text_field( $resource['media'] ) : 'all',
				];
			}
		}

		return $sanitized_resources;
	}

	/**
	 * Admin page for resource hints.
	 *
	 * @return void
	 */
	public function admin_page() {
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'Resource Hints', 'aqualuxe' ); ?></h1>
			<p><?php esc_html_e( 'Resource hints help improve page load performance by telling the browser which resources it should connect to or fetch in advance.', 'aqualuxe' ); ?></p>
			
			<form method="post" action="options.php">
				<?php settings_fields( 'aqualuxe_resource_hints' ); ?>
				<?php do_settings_sections( 'aqualuxe_resource_hints' ); ?>
				
				<h2><?php esc_html_e( 'Preconnect Domains', 'aqualuxe' ); ?></h2>
				<p><?php esc_html_e( 'Preconnect allows the browser to set up early connections before an HTTP request is actually sent to the server. This includes DNS lookups, TLS negotiations, and TCP handshakes.', 'aqualuxe' ); ?></p>
				
				<div id="preconnect-domains">
					<?php
					$preconnect_domains = get_option( 'aqualuxe_preconnect_domains', [] );
					if ( ! empty( $preconnect_domains ) ) {
						foreach ( $preconnect_domains as $index => $domain ) {
							?>
							<div class="resource-hint-item">
								<input type="text" name="aqualuxe_preconnect_domains[]" value="<?php echo esc_attr( $domain ); ?>" class="regular-text" />
								<button type="button" class="button remove-item"><?php esc_html_e( 'Remove', 'aqualuxe' ); ?></button>
							</div>
							<?php
						}
					} else {
						?>
						<div class="resource-hint-item">
							<input type="text" name="aqualuxe_preconnect_domains[]" value="" class="regular-text" />
							<button type="button" class="button remove-item"><?php esc_html_e( 'Remove', 'aqualuxe' ); ?></button>
						</div>
						<?php
					}
					?>
				</div>
				<button type="button" class="button add-preconnect-domain"><?php esc_html_e( 'Add Preconnect Domain', 'aqualuxe' ); ?></button>
				
				<h2><?php esc_html_e( 'DNS Prefetch Domains', 'aqualuxe' ); ?></h2>
				<p><?php esc_html_e( 'DNS prefetch resolves domain names before resources are requested. This is useful for domains that will be used for resources later.', 'aqualuxe' ); ?></p>
				
				<div id="dns-prefetch-domains">
					<?php
					$dns_prefetch_domains = get_option( 'aqualuxe_dns_prefetch_domains', [] );
					if ( ! empty( $dns_prefetch_domains ) ) {
						foreach ( $dns_prefetch_domains as $index => $domain ) {
							?>
							<div class="resource-hint-item">
								<input type="text" name="aqualuxe_dns_prefetch_domains[]" value="<?php echo esc_attr( $domain ); ?>" class="regular-text" />
								<button type="button" class="button remove-item"><?php esc_html_e( 'Remove', 'aqualuxe' ); ?></button>
							</div>
							<?php
						}
					} else {
						?>
						<div class="resource-hint-item">
							<input type="text" name="aqualuxe_dns_prefetch_domains[]" value="" class="regular-text" />
							<button type="button" class="button remove-item"><?php esc_html_e( 'Remove', 'aqualuxe' ); ?></button>
						</div>
						<?php
					}
					?>
				</div>
				<button type="button" class="button add-dns-prefetch-domain"><?php esc_html_e( 'Add DNS Prefetch Domain', 'aqualuxe' ); ?></button>
				
				<h2><?php esc_html_e( 'Prefetch Resources', 'aqualuxe' ); ?></h2>
				<p><?php esc_html_e( 'Prefetch is used to fetch resources that will be used in the next navigation. This is useful for resources that will be needed soon.', 'aqualuxe' ); ?></p>
				
				<div id="prefetch-resources">
					<?php
					$prefetch_resources = get_option( 'aqualuxe_prefetch_resources', [] );
					if ( ! empty( $prefetch_resources ) ) {
						foreach ( $prefetch_resources as $index => $resource ) {
							?>
							<div class="resource-hint-item">
								<input type="text" name="aqualuxe_prefetch_resources[]" value="<?php echo esc_attr( $resource ); ?>" class="regular-text" />
								<button type="button" class="button remove-item"><?php esc_html_e( 'Remove', 'aqualuxe' ); ?></button>
							</div>
							<?php
						}
					} else {
						?>
						<div class="resource-hint-item">
							<input type="text" name="aqualuxe_prefetch_resources[]" value="" class="regular-text" />
							<button type="button" class="button remove-item"><?php esc_html_e( 'Remove', 'aqualuxe' ); ?></button>
						</div>
						<?php
					}
					?>
				</div>
				<button type="button" class="button add-prefetch-resource"><?php esc_html_e( 'Add Prefetch Resource', 'aqualuxe' ); ?></button>
				
				<h2><?php esc_html_e( 'Preload Resources', 'aqualuxe' ); ?></h2>
				<p><?php esc_html_e( 'Preload is used to load resources that will be needed for the current page. This is useful for resources that are discovered late in the loading process.', 'aqualuxe' ); ?></p>
				
				<div id="preload-resources">
					<?php
					$preload_resources = get_option( 'aqualuxe_preload_resources', [] );
					if ( ! empty( $preload_resources ) ) {
						foreach ( $preload_resources as $index => $resource ) {
							?>
							<div class="resource-hint-item preload-item">
								<input type="text" name="aqualuxe_preload_resources[<?php echo esc_attr( $index ); ?>][url]" value="<?php echo esc_attr( $resource['url'] ); ?>" class="regular-text" placeholder="<?php esc_attr_e( 'URL', 'aqualuxe' ); ?>" />
								<select name="aqualuxe_preload_resources[<?php echo esc_attr( $index ); ?>][type]">
									<option value="image" <?php selected( $resource['type'], 'image' ); ?>><?php esc_html_e( 'Image', 'aqualuxe' ); ?></option>
									<option value="font" <?php selected( $resource['type'], 'font' ); ?>><?php esc_html_e( 'Font', 'aqualuxe' ); ?></option>
									<option value="style" <?php selected( $resource['type'], 'style' ); ?>><?php esc_html_e( 'Style', 'aqualuxe' ); ?></option>
									<option value="script" <?php selected( $resource['type'], 'script' ); ?>><?php esc_html_e( 'Script', 'aqualuxe' ); ?></option>
								</select>
								<input type="text" name="aqualuxe_preload_resources[<?php echo esc_attr( $index ); ?>][media]" value="<?php echo esc_attr( $resource['media'] ); ?>" placeholder="<?php esc_attr_e( 'Media (e.g., all)', 'aqualuxe' ); ?>" />
								<button type="button" class="button remove-item"><?php esc_html_e( 'Remove', 'aqualuxe' ); ?></button>
							</div>
							<?php
						}
					} else {
						?>
						<div class="resource-hint-item preload-item">
							<input type="text" name="aqualuxe_preload_resources[0][url]" value="" class="regular-text" placeholder="<?php esc_attr_e( 'URL', 'aqualuxe' ); ?>" />
							<select name="aqualuxe_preload_resources[0][type]">
								<option value="image"><?php esc_html_e( 'Image', 'aqualuxe' ); ?></option>
								<option value="font"><?php esc_html_e( 'Font', 'aqualuxe' ); ?></option>
								<option value="style"><?php esc_html_e( 'Style', 'aqualuxe' ); ?></option>
								<option value="script"><?php esc_html_e( 'Script', 'aqualuxe' ); ?></option>
							</select>
							<input type="text" name="aqualuxe_preload_resources[0][media]" value="all" placeholder="<?php esc_attr_e( 'Media (e.g., all)', 'aqualuxe' ); ?>" />
							<button type="button" class="button remove-item"><?php esc_html_e( 'Remove', 'aqualuxe' ); ?></button>
						</div>
						<?php
					}
					?>
				</div>
				<button type="button" class="button add-preload-resource"><?php esc_html_e( 'Add Preload Resource', 'aqualuxe' ); ?></button>
				
				<?php submit_button(); ?>
			</form>
			
			<script>
				jQuery(document).ready(function($) {
					// Add preconnect domain
					$('.add-preconnect-domain').on('click', function() {
						var item = '<div class="resource-hint-item">' +
							'<input type="text" name="aqualuxe_preconnect_domains[]" value="" class="regular-text" />' +
							'<button type="button" class="button remove-item"><?php esc_html_e( 'Remove', 'aqualuxe' ); ?></button>' +
							'</div>';
						$('#preconnect-domains').append(item);
					});
					
					// Add DNS prefetch domain
					$('.add-dns-prefetch-domain').on('click', function() {
						var item = '<div class="resource-hint-item">' +
							'<input type="text" name="aqualuxe_dns_prefetch_domains[]" value="" class="regular-text" />' +
							'<button type="button" class="button remove-item"><?php esc_html_e( 'Remove', 'aqualuxe' ); ?></button>' +
							'</div>';
						$('#dns-prefetch-domains').append(item);
					});
					
					// Add prefetch resource
					$('.add-prefetch-resource').on('click', function() {
						var item = '<div class="resource-hint-item">' +
							'<input type="text" name="aqualuxe_prefetch_resources[]" value="" class="regular-text" />' +
							'<button type="button" class="button remove-item"><?php esc_html_e( 'Remove', 'aqualuxe' ); ?></button>' +
							'</div>';
						$('#prefetch-resources').append(item);
					});
					
					// Add preload resource
					$('.add-preload-resource').on('click', function() {
						var index = $('#preload-resources .resource-hint-item').length;
						var item = '<div class="resource-hint-item preload-item">' +
							'<input type="text" name="aqualuxe_preload_resources[' + index + '][url]" value="" class="regular-text" placeholder="<?php esc_attr_e( 'URL', 'aqualuxe' ); ?>" />' +
							'<select name="aqualuxe_preload_resources[' + index + '][type]">' +
							'<option value="image"><?php esc_html_e( 'Image', 'aqualuxe' ); ?></option>' +
							'<option value="font"><?php esc_html_e( 'Font', 'aqualuxe' ); ?></option>' +
							'<option value="style"><?php esc_html_e( 'Style', 'aqualuxe' ); ?></option>' +
							'<option value="script"><?php esc_html_e( 'Script', 'aqualuxe' ); ?></option>' +
							'</select>' +
							'<input type="text" name="aqualuxe_preload_resources[' + index + '][media]" value="all" placeholder="<?php esc_attr_e( 'Media (e.g., all)', 'aqualuxe' ); ?>" />' +
							'<button type="button" class="button remove-item"><?php esc_html_e( 'Remove', 'aqualuxe' ); ?></button>' +
							'</div>';
						$('#preload-resources').append(item);
					});
					
					// Remove item
					$(document).on('click', '.remove-item', function() {
						$(this).parent().remove();
						
						// Reindex preload resources
						$('#preload-resources .preload-item').each(function(index) {
							$(this).find('input, select').each(function() {
								var name = $(this).attr('name');
								name = name.replace(/\[\d+\]/, '[' + index + ']');
								$(this).attr('name', name);
							});
						});
					});
				});
			</script>
			
			<style>
				.resource-hint-item {
					margin-bottom: 10px;
				}
				.resource-hint-item input,
				.resource-hint-item select {
					margin-right: 10px;
				}
				.preload-item input[type="text"]:nth-of-type(2) {
					width: 150px;
				}
			</style>
		</div>
		<?php
	}
}