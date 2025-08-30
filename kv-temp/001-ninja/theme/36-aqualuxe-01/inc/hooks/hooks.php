<?php
/**
 * AquaLuxe Theme Hooks
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Theme hooks class.
 */
class AquaLuxe_Hooks {

	/**
	 * Constructor.
	 */
	public function __construct() {
		// Header hooks.
		add_action( 'aqualuxe_before_header', array( $this, 'skip_links' ), 5 );
		add_action( 'aqualuxe_before_header', array( $this, 'top_bar' ), 10 );
		add_action( 'aqualuxe_header', array( $this, 'header_content' ), 10 );
		add_action( 'aqualuxe_after_header', array( $this, 'breadcrumbs' ), 10 );

		// Footer hooks.
		add_action( 'aqualuxe_before_footer', array( $this, 'before_footer' ), 10 );
		add_action( 'aqualuxe_footer', array( $this, 'footer_widgets' ), 10 );
		add_action( 'aqualuxe_footer', array( $this, 'footer_bottom' ), 20 );
		add_action( 'aqualuxe_after_footer', array( $this, 'after_footer' ), 10 );

		// Content hooks.
		add_action( 'aqualuxe_before_content', array( $this, 'before_content' ), 10 );
		add_action( 'aqualuxe_after_content', array( $this, 'after_content' ), 10 );

		// Post hooks.
		add_action( 'aqualuxe_before_post', array( $this, 'before_post' ), 10 );
		add_action( 'aqualuxe_post_header', array( $this, 'post_header' ), 10 );
		add_action( 'aqualuxe_post_content', array( $this, 'post_content' ), 10 );
		add_action( 'aqualuxe_post_footer', array( $this, 'post_footer' ), 10 );
		add_action( 'aqualuxe_after_post', array( $this, 'after_post' ), 10 );

		// Page hooks.
		add_action( 'aqualuxe_before_page', array( $this, 'before_page' ), 10 );
		add_action( 'aqualuxe_page_header', array( $this, 'page_header' ), 10 );
		add_action( 'aqualuxe_page_content', array( $this, 'page_content' ), 10 );
		add_action( 'aqualuxe_page_footer', array( $this, 'page_footer' ), 10 );
		add_action( 'aqualuxe_after_page', array( $this, 'after_page' ), 10 );

		// WooCommerce hooks.
		if ( class_exists( 'WooCommerce' ) ) {
			add_action( 'aqualuxe_before_shop', array( $this, 'before_shop' ), 10 );
			add_action( 'aqualuxe_after_shop', array( $this, 'after_shop' ), 10 );
			add_action( 'aqualuxe_before_product', array( $this, 'before_product' ), 10 );
			add_action( 'aqualuxe_after_product', array( $this, 'after_product' ), 10 );
		}
	}

	/**
	 * Skip links.
	 */
	public function skip_links() {
		?>
		<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'aqualuxe' ); ?></a>
		<?php
	}

	/**
	 * Top bar.
	 */
	public function top_bar() {
		// Check if top bar is enabled.
		$enable_top_bar = get_theme_mod( 'aqualuxe_enable_top_bar', true );
		if ( ! $enable_top_bar ) {
			return;
		}

		// Get top bar content.
		$top_bar_content = get_theme_mod( 'aqualuxe_top_bar_content', __( 'Welcome to AquaLuxe - Bringing elegance to aquatic life – globally.', 'aqualuxe' ) );
		if ( empty( $top_bar_content ) ) {
			return;
		}
		?>
		<div class="top-bar">
			<div class="container">
				<div class="top-bar-content">
					<?php echo wp_kses_post( $top_bar_content ); ?>
				</div>
				<?php $this->top_bar_social(); ?>
			</div>
		</div>
		<?php
	}

	/**
	 * Top bar social icons.
	 */
	public function top_bar_social() {
		// Get social media URLs.
		$facebook_url = get_theme_mod( 'aqualuxe_facebook_url', '' );
		$twitter_url = get_theme_mod( 'aqualuxe_twitter_url', '' );
		$instagram_url = get_theme_mod( 'aqualuxe_instagram_url', '' );
		$linkedin_url = get_theme_mod( 'aqualuxe_linkedin_url', '' );
		$youtube_url = get_theme_mod( 'aqualuxe_youtube_url', '' );
		$pinterest_url = get_theme_mod( 'aqualuxe_pinterest_url', '' );

		// Check if any social media URL is set.
		if ( empty( $facebook_url ) && empty( $twitter_url ) && empty( $instagram_url ) && empty( $linkedin_url ) && empty( $youtube_url ) && empty( $pinterest_url ) ) {
			return;
		}
		?>
		<div class="social-icons">
			<?php if ( ! empty( $facebook_url ) ) : ?>
				<a href="<?php echo esc_url( $facebook_url ); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e( 'Facebook', 'aqualuxe' ); ?>">
					<i class="fab fa-facebook-f" aria-hidden="true"></i>
				</a>
			<?php endif; ?>

			<?php if ( ! empty( $twitter_url ) ) : ?>
				<a href="<?php echo esc_url( $twitter_url ); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e( 'Twitter', 'aqualuxe' ); ?>">
					<i class="fab fa-twitter" aria-hidden="true"></i>
				</a>
			<?php endif; ?>

			<?php if ( ! empty( $instagram_url ) ) : ?>
				<a href="<?php echo esc_url( $instagram_url ); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e( 'Instagram', 'aqualuxe' ); ?>">
					<i class="fab fa-instagram" aria-hidden="true"></i>
				</a>
			<?php endif; ?>

			<?php if ( ! empty( $linkedin_url ) ) : ?>
				<a href="<?php echo esc_url( $linkedin_url ); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e( 'LinkedIn', 'aqualuxe' ); ?>">
					<i class="fab fa-linkedin-in" aria-hidden="true"></i>
				</a>
			<?php endif; ?>

			<?php if ( ! empty( $youtube_url ) ) : ?>
				<a href="<?php echo esc_url( $youtube_url ); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e( 'YouTube', 'aqualuxe' ); ?>">
					<i class="fab fa-youtube" aria-hidden="true"></i>
				</a>
			<?php endif; ?>

			<?php if ( ! empty( $pinterest_url ) ) : ?>
				<a href="<?php echo esc_url( $pinterest_url ); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e( 'Pinterest', 'aqualuxe' ); ?>">
					<i class="fab fa-pinterest-p" aria-hidden="true"></i>
				</a>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * Header content.
	 */
	public function header_content() {
		// Get header layout.
		$header_layout = get_theme_mod( 'aqualuxe_header_layout', 'default' );

		// Load the appropriate header template.
		get_template_part( 'templates/layout/header', $header_layout );
	}

	/**
	 * Breadcrumbs.
	 */
	public function breadcrumbs() {
		// Check if breadcrumbs are enabled.
		$enable_breadcrumbs = get_theme_mod( 'aqualuxe_enable_breadcrumbs', true );
		if ( ! $enable_breadcrumbs ) {
			return;
		}

		// Don't show breadcrumbs on the front page.
		if ( is_front_page() ) {
			return;
		}

		// Load the breadcrumbs template.
		get_template_part( 'templates/components/breadcrumbs' );
	}

	/**
	 * Before footer.
	 */
	public function before_footer() {
		// Check if back to top button is enabled.
		$enable_back_to_top = get_theme_mod( 'aqualuxe_enable_back_to_top', true );
		if ( $enable_back_to_top ) {
			?>
			<button id="back-to-top" class="back-to-top" aria-label="<?php esc_attr_e( 'Back to top', 'aqualuxe' ); ?>">
				<i class="fas fa-chevron-up" aria-hidden="true"></i>
			</button>
			<?php
		}
	}

	/**
	 * Footer widgets.
	 */
	public function footer_widgets() {
		// Get footer layout.
		$footer_layout = get_theme_mod( 'aqualuxe_footer_layout', '4-columns' );

		// Don't show widgets if layout is set to 'none'.
		if ( 'none' === $footer_layout ) {
			return;
		}

		// Load the footer widgets template.
		get_template_part( 'templates/layout/footer', 'widgets' );
	}

	/**
	 * Footer bottom.
	 */
	public function footer_bottom() {
		// Get copyright text.
		$copyright_text = get_theme_mod(
			'aqualuxe_copyright_text',
			sprintf(
				/* translators: %1$s: Current year, %2$s: Site name */
				__( '© %1$s %2$s. All rights reserved.', 'aqualuxe' ),
				date( 'Y' ),
				get_bloginfo( 'name' )
			)
		);

		// Check if payment icons should be shown.
		$show_payment_icons = get_theme_mod( 'aqualuxe_show_payment_icons', true );
		?>
		<div class="footer-bottom">
			<div class="container">
				<div class="footer-bottom-content">
					<div class="copyright">
						<?php echo wp_kses_post( $copyright_text ); ?>
					</div>
					<?php if ( $show_payment_icons ) : ?>
						<div class="payment-icons">
							<?php $this->payment_icons(); ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Payment icons.
	 */
	public function payment_icons() {
		// Get payment icons.
		$payment_icons = get_theme_mod( 'aqualuxe_payment_icons', array( 'visa', 'mastercard', 'amex', 'paypal' ) );

		if ( empty( $payment_icons ) ) {
			return;
		}

		// Define payment icon classes.
		$icon_classes = array(
			'visa'       => 'fab fa-cc-visa',
			'mastercard' => 'fab fa-cc-mastercard',
			'amex'       => 'fab fa-cc-amex',
			'discover'   => 'fab fa-cc-discover',
			'paypal'     => 'fab fa-paypal',
			'apple-pay'  => 'fab fa-apple-pay',
			'google-pay' => 'fab fa-google-pay',
			'stripe'     => 'fab fa-stripe',
		);

		// Output payment icons.
		foreach ( $payment_icons as $icon ) {
			if ( isset( $icon_classes[ $icon ] ) ) {
				echo '<i class="' . esc_attr( $icon_classes[ $icon ] ) . '" aria-hidden="true"></i>';
			}
		}
	}

	/**
	 * After footer.
	 */
	public function after_footer() {
		// This hook is available for plugins or child themes to add content after the footer.
	}

	/**
	 * Before content.
	 */
	public function before_content() {
		// This hook is available for plugins or child themes to add content before the main content.
	}

	/**
	 * After content.
	 */
	public function after_content() {
		// This hook is available for plugins or child themes to add content after the main content.
	}

	/**
	 * Before post.
	 */
	public function before_post() {
		// This hook is available for plugins or child themes to add content before each post.
	}

	/**
	 * Post header.
	 */
	public function post_header() {
		// Check if post meta should be shown.
		$show_post_meta = get_theme_mod( 'aqualuxe_show_post_meta', true );
		?>
		<header class="entry-header">
			<?php
			if ( is_singular() ) :
				the_title( '<h1 class="entry-title">', '</h1>' );
			else :
				the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
			endif;

			if ( $show_post_meta ) :
				?>
				<div class="entry-meta">
					<?php
					aqualuxe_posted_on();
					aqualuxe_posted_by();
					?>
				</div><!-- .entry-meta -->
				<?php
			endif;
			?>
		</header><!-- .entry-header -->
		<?php
	}

	/**
	 * Post content.
	 */
	public function post_content() {
		// Check if featured image should be shown.
		$show_featured_image = get_theme_mod( 'aqualuxe_show_featured_image', true );

		// Show featured image if enabled and available.
		if ( $show_featured_image && has_post_thumbnail() ) {
			?>
			<div class="post-thumbnail">
				<?php
				if ( is_singular() ) :
					the_post_thumbnail( 'large', array( 'class' => 'featured-image' ) );
				else :
					?>
					<a href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
						<?php the_post_thumbnail( 'medium', array( 'class' => 'featured-image' ) ); ?>
					</a>
					<?php
				endif;
				?>
			</div><!-- .post-thumbnail -->
			<?php
		}
		?>

		<div class="entry-content">
			<?php
			if ( is_singular() ) :
				the_content(
					sprintf(
						wp_kses(
							/* translators: %s: Name of current post. Only visible to screen readers */
							__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'aqualuxe' ),
							array(
								'span' => array(
									'class' => array(),
								),
							)
						),
						wp_kses_post( get_the_title() )
					)
				);

				wp_link_pages(
					array(
						'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'aqualuxe' ),
						'after'  => '</div>',
					)
				);
			else :
				// Check if excerpt should be shown.
				$show_excerpt = get_theme_mod( 'aqualuxe_show_excerpt', true );
				if ( $show_excerpt ) {
					the_excerpt();
					$read_more_text = get_theme_mod( 'aqualuxe_read_more_text', __( 'Read More', 'aqualuxe' ) );
					echo '<p><a href="' . esc_url( get_permalink() ) . '" class="read-more">' . esc_html( $read_more_text ) . '</a></p>';
				} else {
					the_content();
				}
			endif;
			?>
		</div><!-- .entry-content -->
		<?php
	}

	/**
	 * Post footer.
	 */
	public function post_footer() {
		// Only show on single posts.
		if ( ! is_singular( 'post' ) ) {
			return;
		}

		// Show categories and tags.
		$categories_list = get_the_category_list( esc_html__( ', ', 'aqualuxe' ) );
		$tags_list = get_the_tag_list( '', esc_html_x( ', ', 'list item separator', 'aqualuxe' ) );

		if ( $categories_list || $tags_list ) {
			?>
			<footer class="entry-footer">
				<?php if ( $categories_list ) : ?>
					<div class="cat-links">
						<span class="cat-links-label"><?php esc_html_e( 'Categories:', 'aqualuxe' ); ?></span>
						<?php echo wp_kses_post( $categories_list ); ?>
					</div>
				<?php endif; ?>

				<?php if ( $tags_list ) : ?>
					<div class="tags-links">
						<span class="tags-links-label"><?php esc_html_e( 'Tags:', 'aqualuxe' ); ?></span>
						<?php echo wp_kses_post( $tags_list ); ?>
					</div>
				<?php endif; ?>
			</footer><!-- .entry-footer -->
			<?php
		}

		// Show author bio if enabled.
		$show_author_bio = get_theme_mod( 'aqualuxe_show_author_bio', true );
		if ( $show_author_bio ) {
			get_template_part( 'templates/components/author-bio' );
		}

		// Show post navigation if enabled.
		$show_post_navigation = get_theme_mod( 'aqualuxe_show_post_navigation', true );
		if ( $show_post_navigation ) {
			the_post_navigation(
				array(
					'prev_text' => '<span class="nav-subtitle">' . esc_html__( 'Previous:', 'aqualuxe' ) . '</span> <span class="nav-title">%title</span>',
					'next_text' => '<span class="nav-subtitle">' . esc_html__( 'Next:', 'aqualuxe' ) . '</span> <span class="nav-title">%title</span>',
				)
			);
		}

		// Show related posts if enabled.
		$show_related_posts = get_theme_mod( 'aqualuxe_show_related_posts', true );
		if ( $show_related_posts ) {
			get_template_part( 'templates/components/related-posts' );
		}

		// Show social sharing if enabled.
		$enable_social_sharing = get_theme_mod( 'aqualuxe_enable_social_sharing', true );
		if ( $enable_social_sharing ) {
			get_template_part( 'templates/components/social-sharing' );
		}
	}

	/**
	 * After post.
	 */
	public function after_post() {
		// Show comments if comments are open or we have at least one comment.
		if ( comments_open() || get_comments_number() ) {
			comments_template();
		}
	}

	/**
	 * Before page.
	 */
	public function before_page() {
		// This hook is available for plugins or child themes to add content before each page.
	}

	/**
	 * Page header.
	 */
	public function page_header() {
		?>
		<header class="entry-header">
			<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
		</header><!-- .entry-header -->
		<?php
	}

	/**
	 * Page content.
	 */
	public function page_content() {
		// Check if featured image should be shown.
		$show_featured_image = get_theme_mod( 'aqualuxe_show_featured_image', true );

		// Show featured image if enabled and available.
		if ( $show_featured_image && has_post_thumbnail() ) {
			?>
			<div class="post-thumbnail">
				<?php the_post_thumbnail( 'large', array( 'class' => 'featured-image' ) ); ?>
			</div><!-- .post-thumbnail -->
			<?php
		}
		?>

		<div class="entry-content">
			<?php
			the_content();

			wp_link_pages(
				array(
					'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'aqualuxe' ),
					'after'  => '</div>',
				)
			);
			?>
		</div><!-- .entry-content -->
		<?php
	}

	/**
	 * Page footer.
	 */
	public function page_footer() {
		// This hook is available for plugins or child themes to add content to the page footer.
	}

	/**
	 * After page.
	 */
	public function after_page() {
		// Show comments if comments are open or we have at least one comment.
		if ( comments_open() || get_comments_number() ) {
			comments_template();
		}
	}

	/**
	 * Before shop.
	 */
	public function before_shop() {
		// This hook is available for plugins or child themes to add content before the shop.
	}

	/**
	 * After shop.
	 */
	public function after_shop() {
		// This hook is available for plugins or child themes to add content after the shop.
	}

	/**
	 * Before product.
	 */
	public function before_product() {
		// This hook is available for plugins or child themes to add content before each product.
	}

	/**
	 * After product.
	 */
	public function after_product() {
		// This hook is available for plugins or child themes to add content after each product.
	}
}

// Initialize the hooks.
new AquaLuxe_Hooks();

/**
 * Template hooks for the theme.
 */
function aqualuxe_template_hooks() {
	/**
	 * Header hooks.
	 *
	 * @hooked AquaLuxe_Hooks::skip_links - 5
	 * @hooked AquaLuxe_Hooks::top_bar - 10
	 */
	do_action( 'aqualuxe_before_header' );

	/**
	 * Header content hook.
	 *
	 * @hooked AquaLuxe_Hooks::header_content - 10
	 */
	do_action( 'aqualuxe_header' );

	/**
	 * After header hook.
	 *
	 * @hooked AquaLuxe_Hooks::breadcrumbs - 10
	 */
	do_action( 'aqualuxe_after_header' );

	/**
	 * Before content hook.
	 *
	 * @hooked AquaLuxe_Hooks::before_content - 10
	 */
	do_action( 'aqualuxe_before_content' );

	/**
	 * After content hook.
	 *
	 * @hooked AquaLuxe_Hooks::after_content - 10
	 */
	do_action( 'aqualuxe_after_content' );

	/**
	 * Before footer hook.
	 *
	 * @hooked AquaLuxe_Hooks::before_footer - 10
	 */
	do_action( 'aqualuxe_before_footer' );

	/**
	 * Footer hooks.
	 *
	 * @hooked AquaLuxe_Hooks::footer_widgets - 10
	 * @hooked AquaLuxe_Hooks::footer_bottom - 20
	 */
	do_action( 'aqualuxe_footer' );

	/**
	 * After footer hook.
	 *
	 * @hooked AquaLuxe_Hooks::after_footer - 10
	 */
	do_action( 'aqualuxe_after_footer' );
}

/**
 * Post template hooks.
 */
function aqualuxe_post_template_hooks() {
	/**
	 * Before post hook.
	 *
	 * @hooked AquaLuxe_Hooks::before_post - 10
	 */
	do_action( 'aqualuxe_before_post' );

	/**
	 * Post header hook.
	 *
	 * @hooked AquaLuxe_Hooks::post_header - 10
	 */
	do_action( 'aqualuxe_post_header' );

	/**
	 * Post content hook.
	 *
	 * @hooked AquaLuxe_Hooks::post_content - 10
	 */
	do_action( 'aqualuxe_post_content' );

	/**
	 * Post footer hook.
	 *
	 * @hooked AquaLuxe_Hooks::post_footer - 10
	 */
	do_action( 'aqualuxe_post_footer' );

	/**
	 * After post hook.
	 *
	 * @hooked AquaLuxe_Hooks::after_post - 10
	 */
	do_action( 'aqualuxe_after_post' );
}

/**
 * Page template hooks.
 */
function aqualuxe_page_template_hooks() {
	/**
	 * Before page hook.
	 *
	 * @hooked AquaLuxe_Hooks::before_page - 10
	 */
	do_action( 'aqualuxe_before_page' );

	/**
	 * Page header hook.
	 *
	 * @hooked AquaLuxe_Hooks::page_header - 10
	 */
	do_action( 'aqualuxe_page_header' );

	/**
	 * Page content hook.
	 *
	 * @hooked AquaLuxe_Hooks::page_content - 10
	 */
	do_action( 'aqualuxe_page_content' );

	/**
	 * Page footer hook.
	 *
	 * @hooked AquaLuxe_Hooks::page_footer - 10
	 */
	do_action( 'aqualuxe_page_footer' );

	/**
	 * After page hook.
	 *
	 * @hooked AquaLuxe_Hooks::after_page - 10
	 */
	do_action( 'aqualuxe_after_page' );
}

/**
 * WooCommerce template hooks.
 */
function aqualuxe_woocommerce_template_hooks() {
	// Only run if WooCommerce is active.
	if ( ! class_exists( 'WooCommerce' ) ) {
		return;
	}

	/**
	 * Before shop hook.
	 *
	 * @hooked AquaLuxe_Hooks::before_shop - 10
	 */
	do_action( 'aqualuxe_before_shop' );

	/**
	 * After shop hook.
	 *
	 * @hooked AquaLuxe_Hooks::after_shop - 10
	 */
	do_action( 'aqualuxe_after_shop' );

	/**
	 * Before product hook.
	 *
	 * @hooked AquaLuxe_Hooks::before_product - 10
	 */
	do_action( 'aqualuxe_before_product' );

	/**
	 * After product hook.
	 *
	 * @hooked AquaLuxe_Hooks::after_product - 10
	 */
	do_action( 'aqualuxe_after_product' );
}