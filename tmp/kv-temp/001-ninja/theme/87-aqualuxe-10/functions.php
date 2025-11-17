<?php
/**
 * Theme bootstrap for AquaLuxe
 *
 * @package AquaLuxe
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Lightweight shims for template functions when WP isn't loaded (for static analysis/CI).
// These are guarded and won't override real WP functions at runtime.
if (!function_exists('comment_class')) { function comment_class($class = '', $comment_id = null, $post_id = null, $echo = true) { if ($echo) { echo ''; } return ''; } }
if (!function_exists('comment_ID')) { function comment_ID() { echo '0'; } }
if (!function_exists('get_avatar')) { function get_avatar($id_or_email, $size = 96, $default = '', $alt = '', $args = []) { return ''; } }
if (!function_exists('get_comment_author_link')) { function get_comment_author_link() { return 'Anonymous'; } }
if (!function_exists('esc_url')) { function esc_url($url = '') { return (string) $url; } }
if (!function_exists('get_comment_link')) { function get_comment_link($comment_id = null, $args = [], $cpage = null) { return '#'; } }
if (!function_exists('get_comment_date')) { function get_comment_date($format = '', $comment_ID = 0) { return gmdate('Y-m-d'); } }
if (!function_exists('get_comment_time')) { function get_comment_time($format = '', $gmt = false, $translate = true) { return gmdate('H:i'); } }
if (!function_exists('edit_comment_link')) { function edit_comment_link($link = null, $before = '', $after = '') { /* no-op */ } }
if (!function_exists('comment_text')) { function comment_text($comment_ID = 0, $args = []) { echo ''; } }
if (!function_exists('comment_reply_link')) { function comment_reply_link($args = [], $post = null, $parent = null) { /* no-op */ } }

// Define constants
define('AQUALUXE_VERSION', '1.0.24');
define('AQUALUXE_MIN_WP', '6.0');
define('AQUALUXE_MIN_PHP', '8.0');
define('AQUALUXE_DIR', get_template_directory());
define('AQUALUXE_URI', get_template_directory_uri());
define('AQUALUXE_ASSETS_DIST', AQUALUXE_DIR . '/assets/dist');
define('AQUALUXE_ASSETS_URI', AQUALUXE_URI . '/assets/dist');
define('AQUALUXE_INC', AQUALUXE_DIR . '/inc');
define('AQUALUXE_MODULES', AQUALUXE_DIR . '/modules');
define('AQUALUXE_TEMPLATES', AQUALUXE_DIR . '/templates');

// Composer-like simple autoloader for theme classes
require_once AQUALUXE_INC . '/autoload.php';
require_once AQUALUXE_INC . '/helpers.php';
require_once AQUALUXE_INC . '/security.php';
require_once AQUALUXE_INC . '/template-tags.php';
require_once AQUALUXE_INC . '/shortcodes.php';
require_once AQUALUXE_INC . '/admin/importer.php';
require_once AQUALUXE_INC . '/admin/module-manager.php';
require_once AQUALUXE_INC . '/integrations/woocommerce.php';

// Initialize theme core
\AquaLuxe\Core\Theme::init();

// Optional debug level for logger via constant
if (function_exists('add_filter')) {
	\add_filter('aqualuxe/logger/level', function ($level) {
		if (\defined('AQUALUXE_DEBUG') && (bool) \constant('AQUALUXE_DEBUG')) {
			return 'debug';
		}
		return $level;
	});
}

// Load modules
\AquaLuxe\Core\Modules::bootstrap();

// Admin tools (demo importer)
\AquaLuxe\Admin\Importer::init();
\AquaLuxe\Admin\ModuleManager::init();

// WooCommerce integration (dual-state)
\AquaLuxe\Integrations\WooCommerce::init();

/**
 * Custom comment callback function for styling comments
 *
 * @param WP_Comment $comment The comment object.
 * @param array      $args    An array of arguments.
 * @param int        $depth   The depth of the current comment.
 */
function aqualuxe_comment_callback( $comment, $args, $depth ) {
	if ( 'div' === $args['style'] ) {
		$tag       = 'div';
		$add_below = 'comment';
	} else {
		$tag       = 'li';
		$add_below = 'div-comment';
	}
	?>
	<<?php echo esc_html( $tag ); ?> <?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ); ?> id="comment-<?php comment_ID(); ?>">
		<?php if ( 'div' !== $args['style'] ) : ?>
			<div id="div-comment-<?php comment_ID(); ?>" class="comment-body border border-slate-200 dark:border-slate-700 rounded-lg p-4 bg-white dark:bg-slate-800">
		<?php endif; ?>
		
		<div class="comment-author vcard flex items-start space-x-3">
			<?php if ( $args['avatar_size'] !== 0 ) : ?>
				<div class="avatar-wrapper flex-shrink-0">
					<?php echo get_avatar( $comment, $args['avatar_size'], '', '', array( 'class' => 'rounded-full' ) ); ?>
				</div>
			<?php endif; ?>
			<div class="comment-metadata flex-1">
				<div class="comment-author-name">
					<?php printf( '<cite class="fn font-semibold text-slate-900 dark:text-slate-100">%s</cite>', get_comment_author_link() ); ?>
				</div>
				<div class="comment-meta commentmetadata text-sm text-slate-600 dark:text-slate-400 mb-2">
					<a href="<?php echo esc_url( htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ); ?>" class="hover:text-blue-600 dark:hover:text-blue-400">
						<?php
						printf(
							/* translators: 1: Date, 2: Time. */
							esc_html__( '%1$s at %2$s', 'aqualuxe' ),
							get_comment_date(),
							get_comment_time()
						);
						?>
					</a>
					<?php edit_comment_link( esc_html__( '(Edit)', 'aqualuxe' ), ' ', '' ); ?>
				</div>
				
				<?php if ( $comment->comment_approved === '0' ) : ?>
					<em class="comment-awaiting-moderation text-yellow-600 dark:text-yellow-400 text-sm">
						<?php esc_html_e( 'Your comment is awaiting moderation.', 'aqualuxe' ); ?>
					</em>
					<br />
				<?php endif; ?>

				<div class="comment-content prose prose-sm dark:prose-invert max-w-none">
					<?php comment_text(); ?>
				</div>

				<div class="reply mt-3">
					<?php
					comment_reply_link(
						array_merge(
							$args,
							array(
								'add_below' => $add_below,
								'depth'     => $depth,
								'max_depth' => $args['max_depth'],
								'class'     => 'text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-200 text-sm font-medium',
							)
						)
					);
					?>
				</div>
			</div>
		</div>
		
		<?php if ( 'div' !== $args['style'] ) : ?>
			</div>
		<?php endif; ?>
	<?php
}
