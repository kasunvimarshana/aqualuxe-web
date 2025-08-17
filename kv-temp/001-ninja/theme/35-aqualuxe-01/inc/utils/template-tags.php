<?php
/**
 * Custom template tags for this theme
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Prints HTML with meta information for the current post-date/time.
 */
if ( ! function_exists( 'aqualuxe_posted_on' ) ) :
	/**
	 * Prints HTML with meta information for the current post-date/time.
	 */
	function aqualuxe_posted_on() {
		if ( ! get_theme_mod( 'aqualuxe_show_post_date', true ) ) {
			return;
		}

		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
			$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
		}

		$time_string = sprintf(
			$time_string,
			esc_attr( get_the_date( DATE_W3C ) ),
			esc_html( get_the_date() ),
			esc_attr( get_the_modified_date( DATE_W3C ) ),
			esc_html( get_the_modified_date() )
		);

		$posted_on = sprintf(
			/* translators: %s: post date. */
			esc_html_x( 'Posted on %s', 'post date', 'aqualuxe' ),
			'<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
		);

		echo '<span class="posted-on">' . $posted_on . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
endif;

/**
 * Prints HTML with meta information for the current author.
 */
if ( ! function_exists( 'aqualuxe_posted_by' ) ) :
	/**
	 * Prints HTML with meta information for the current author.
	 */
	function aqualuxe_posted_by() {
		if ( ! get_theme_mod( 'aqualuxe_show_post_author', true ) ) {
			return;
		}

		$byline = sprintf(
			/* translators: %s: post author. */
			esc_html_x( 'by %s', 'post author', 'aqualuxe' ),
			'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
		);

		echo '<span class="byline"> ' . $byline . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
endif;

/**
 * Prints HTML with meta information for the categories.
 */
if ( ! function_exists( 'aqualuxe_post_categories' ) ) :
	/**
	 * Prints HTML with meta information for the categories.
	 */
	function aqualuxe_post_categories() {
		if ( ! get_theme_mod( 'aqualuxe_show_post_categories', true ) ) {
			return;
		}

		// Hide category text for pages.
		if ( 'post' === get_post_type() ) {
			/* translators: used between list items, there is a space after the comma */
			$categories_list = get_the_category_list( esc_html__( ', ', 'aqualuxe' ) );
			if ( $categories_list ) {
				/* translators: 1: list of categories. */
				printf( '<span class="cat-links">' . esc_html__( 'Posted in %1$s', 'aqualuxe' ) . '</span>', $categories_list ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
		}
	}
endif;

/**
 * Prints HTML with meta information for the tags.
 */
if ( ! function_exists( 'aqualuxe_post_tags' ) ) :
	/**
	 * Prints HTML with meta information for the tags.
	 */
	function aqualuxe_post_tags() {
		if ( ! get_theme_mod( 'aqualuxe_show_post_tags', true ) ) {
			return;
		}

		// Hide tag text for pages.
		if ( 'post' === get_post_type() ) {
			/* translators: used between list items, there is a space after the comma */
			$tags_list = get_the_tag_list( '', esc_html_x( ', ', 'list item separator', 'aqualuxe' ) );
			if ( $tags_list ) {
				/* translators: 1: list of tags. */
				printf( '<span class="tags-links">' . esc_html__( 'Tagged %1$s', 'aqualuxe' ) . '</span>', $tags_list ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
		}
	}
endif;

/**
 * Prints HTML with meta information for the comments.
 */
if ( ! function_exists( 'aqualuxe_post_comments' ) ) :
	/**
	 * Prints HTML with meta information for the comments.
	 */
	function aqualuxe_post_comments() {
		if ( ! get_theme_mod( 'aqualuxe_show_post_comments', true ) ) {
			return;
		}

		if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
			echo '<span class="comments-link">';
			comments_popup_link(
				sprintf(
					wp_kses(
						/* translators: %s: post title */
						__( 'Leave a Comment<span class="screen-reader-text"> on %s</span>', 'aqualuxe' ),
						array(
							'span' => array(
								'class' => array(),
							),
						)
					),
					wp_kses_post( get_the_title() )
				)
			);
			echo '</span>';
		}
	}
endif;

/**
 * Prints HTML with meta information for the current post.
 */
if ( ! function_exists( 'aqualuxe_entry_meta' ) ) :
	/**
	 * Prints HTML with meta information for the current post.
	 */
	function aqualuxe_entry_meta() {
		// Hide meta information on pages.
		if ( 'post' !== get_post_type() ) {
			return;
		}

		echo '<div class="entry-meta">';
		aqualuxe_posted_on();
		aqualuxe_posted_by();
		aqualuxe_post_categories();
		aqualuxe_post_comments();
		echo '</div><!-- .entry-meta -->';
	}
endif;

/**
 * Prints HTML with meta information for the current post in a compact format.
 */
if ( ! function_exists( 'aqualuxe_entry_meta_compact' ) ) :
	/**
	 * Prints HTML with meta information for the current post in a compact format.
	 */
	function aqualuxe_entry_meta_compact() {
		// Hide meta information on pages.
		if ( 'post' !== get_post_type() ) {
			return;
		}

		echo '<div class="entry-meta entry-meta-compact">';
		
		// Date.
		if ( get_theme_mod( 'aqualuxe_show_post_date', true ) ) {
			echo '<span class="posted-on">';
			echo '<span class="screen-reader-text">' . esc_html__( 'Posted on', 'aqualuxe' ) . '</span>';
			echo '<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . esc_html( get_the_date() ) . '</a>';
			echo '</span>';
		}
		
		// Author.
		if ( get_theme_mod( 'aqualuxe_show_post_author', true ) ) {
			echo '<span class="byline">';
			echo '<span class="screen-reader-text">' . esc_html__( 'Author', 'aqualuxe' ) . '</span>';
			echo '<a href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a>';
			echo '</span>';
		}
		
		// Comments.
		if ( get_theme_mod( 'aqualuxe_show_post_comments', true ) && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
			echo '<span class="comments-link">';
			echo '<span class="screen-reader-text">' . esc_html__( 'Comments', 'aqualuxe' ) . '</span>';
			comments_popup_link(
				'0',
				'1',
				'%'
			);
			echo '</span>';
		}
		
		echo '</div><!-- .entry-meta-compact -->';
	}
endif;

/**
 * Displays an optional post thumbnail.
 *
 * Wraps the post thumbnail in an anchor element on index views, or a div
 * element when on single views.
 */
if ( ! function_exists( 'aqualuxe_post_thumbnail' ) ) :
	/**
	 * Displays an optional post thumbnail.
	 */
	function aqualuxe_post_thumbnail() {
		if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
			return;
		}

		if ( is_singular() ) :
			?>
			<div class="post-thumbnail">
				<?php the_post_thumbnail( 'aqualuxe-featured', array( 'class' => 'featured-image' ) ); ?>
			</div><!-- .post-thumbnail -->
		<?php else : ?>
			<a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
				<?php
				the_post_thumbnail(
					'aqualuxe-card',
					array(
						'alt' => the_title_attribute(
							array(
								'echo' => false,
							)
						),
						'class' => 'card-image',
					)
				);
				?>
			</a>
			<?php
		endif; // End is_singular().
	}
endif;

/**
 * Displays post author bio.
 */
if ( ! function_exists( 'aqualuxe_author_bio' ) ) :
	/**
	 * Displays post author bio.
	 */
	function aqualuxe_author_bio() {
		if ( ! is_single() ) {
			return;
		}

		if ( get_the_author_meta( 'description' ) ) :
			?>
			<div class="author-bio">
				<div class="author-avatar">
					<?php echo get_avatar( get_the_author_meta( 'ID' ), 80 ); ?>
				</div>
				<div class="author-description">
					<h2 class="author-title">
						<?php
						/* translators: %s: Author name */
						printf( esc_html__( 'About %s', 'aqualuxe' ), esc_html( get_the_author() ) );
						?>
					</h2>
					<p class="author-bio-text"><?php the_author_meta( 'description' ); ?></p>
					<a class="author-link" href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" rel="author">
						<?php
						/* translators: %s: Author name */
						printf( esc_html__( 'View all posts by %s', 'aqualuxe' ), esc_html( get_the_author() ) );
						?>
					</a>
				</div>
			</div><!-- .author-bio -->
			<?php
		endif;
	}
endif;

/**
 * Displays post navigation.
 */
if ( ! function_exists( 'aqualuxe_post_navigation' ) ) :
	/**
	 * Displays post navigation.
	 */
	function aqualuxe_post_navigation() {
		if ( ! is_single() ) {
			return;
		}

		the_post_navigation(
			array(
				'prev_text' => '<span class="nav-subtitle">' . esc_html__( 'Previous:', 'aqualuxe' ) . '</span> <span class="nav-title">%title</span>',
				'next_text' => '<span class="nav-subtitle">' . esc_html__( 'Next:', 'aqualuxe' ) . '</span> <span class="nav-title">%title</span>',
			)
		);
	}
endif;

/**
 * Displays related posts.
 */
if ( ! function_exists( 'aqualuxe_related_posts' ) ) :
	/**
	 * Displays related posts.
	 */
	function aqualuxe_related_posts() {
		if ( ! is_single() || 'post' !== get_post_type() ) {
			return;
		}

		if ( ! get_theme_mod( 'aqualuxe_show_related_posts', true ) ) {
			return;
		}

		$related_count = get_theme_mod( 'aqualuxe_related_posts_count', 3 );
		$categories    = get_the_category( get_the_ID() );
		$category_ids  = array();

		foreach ( $categories as $category ) {
			$category_ids[] = $category->term_id;
		}

		if ( empty( $category_ids ) ) {
			return;
		}

		$args = array(
			'category__in'        => $category_ids,
			'post__not_in'        => array( get_the_ID() ),
			'posts_per_page'      => $related_count,
			'ignore_sticky_posts' => 1,
		);

		$related_query = new WP_Query( $args );

		if ( $related_query->have_posts() ) :
			?>
			<div class="related-posts">
				<h2 class="related-posts-title"><?php esc_html_e( 'Related Posts', 'aqualuxe' ); ?></h2>
				<div class="related-posts-grid">
					<?php
					while ( $related_query->have_posts() ) :
						$related_query->the_post();
						?>
						<article id="post-<?php the_ID(); ?>" <?php post_class( 'related-post' ); ?>>
							<?php if ( has_post_thumbnail() ) : ?>
								<a class="related-post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
									<?php
									the_post_thumbnail(
										'aqualuxe-card',
										array(
											'alt' => the_title_attribute(
												array(
													'echo' => false,
												)
											),
											'class' => 'related-post-image',
										)
									);
									?>
								</a>
							<?php endif; ?>
							<div class="related-post-content">
								<h3 class="related-post-title">
									<a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
								</h3>
								<div class="related-post-meta">
									<?php echo esc_html( get_the_date() ); ?>
								</div>
							</div>
						</article><!-- #post-<?php the_ID(); ?> -->
						<?php
					endwhile;
					?>
				</div>
			</div><!-- .related-posts -->
			<?php
			wp_reset_postdata();
		endif;
	}
endif;

/**
 * Displays pagination.
 */
if ( ! function_exists( 'aqualuxe_pagination' ) ) :
	/**
	 * Displays pagination.
	 */
	function aqualuxe_pagination() {
		if ( is_singular() ) {
			return;
		}

		global $wp_query;

		// Don't print empty markup if there's only one page.
		if ( $wp_query->max_num_pages < 2 ) {
			return;
		}

		$args = array(
			'mid_size'           => 2,
			'prev_text'          => esc_html__( 'Previous', 'aqualuxe' ),
			'next_text'          => esc_html__( 'Next', 'aqualuxe' ),
			'screen_reader_text' => esc_html__( 'Posts navigation', 'aqualuxe' ),
		);

		the_posts_pagination( $args );
	}
endif;

/**
 * Displays breadcrumbs.
 */
if ( ! function_exists( 'aqualuxe_breadcrumbs' ) ) :
	/**
	 * Displays breadcrumbs.
	 */
	function aqualuxe_breadcrumbs() {
		if ( ! get_theme_mod( 'aqualuxe_enable_breadcrumbs', true ) ) {
			return;
		}

		// Don't display breadcrumbs on the front page.
		if ( is_front_page() ) {
			return;
		}

		$breadcrumbs = array();

		// Home.
		$breadcrumbs[] = array(
			'label' => esc_html__( 'Home', 'aqualuxe' ),
			'url'   => esc_url( home_url( '/' ) ),
		);

		// Handle WooCommerce breadcrumbs.
		if ( class_exists( 'WooCommerce' ) ) {
			if ( is_shop() ) {
				$breadcrumbs[] = array(
					'label' => esc_html__( 'Shop', 'aqualuxe' ),
					'url'   => esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ),
				);
			} elseif ( is_product_category() || is_product_tag() ) {
				$breadcrumbs[] = array(
					'label' => esc_html__( 'Shop', 'aqualuxe' ),
					'url'   => esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ),
				);

				$term = get_queried_object();
				$breadcrumbs[] = array(
					'label' => esc_html( $term->name ),
					'url'   => esc_url( get_term_link( $term ) ),
				);
			} elseif ( is_product() ) {
				$breadcrumbs[] = array(
					'label' => esc_html__( 'Shop', 'aqualuxe' ),
					'url'   => esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ),
				);

				$terms = wp_get_post_terms( get_the_ID(), 'product_cat' );
				if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
					$term = $terms[0];
					$breadcrumbs[] = array(
						'label' => esc_html( $term->name ),
						'url'   => esc_url( get_term_link( $term ) ),
					);
				}

				$breadcrumbs[] = array(
					'label' => esc_html( get_the_title() ),
					'url'   => esc_url( get_permalink() ),
				);
			}
		} elseif ( is_singular( 'post' ) ) {
			// Blog post.
			$breadcrumbs[] = array(
				'label' => esc_html__( 'Blog', 'aqualuxe' ),
				'url'   => esc_url( get_permalink( get_option( 'page_for_posts' ) ) ),
			);

			$categories = get_the_category();
			if ( ! empty( $categories ) ) {
				$category = $categories[0];
				$breadcrumbs[] = array(
					'label' => esc_html( $category->name ),
					'url'   => esc_url( get_category_link( $category->term_id ) ),
				);
			}

			$breadcrumbs[] = array(
				'label' => esc_html( get_the_title() ),
				'url'   => esc_url( get_permalink() ),
			);
		} elseif ( is_page() ) {
			// Page.
			$ancestors = get_post_ancestors( get_the_ID() );
			if ( ! empty( $ancestors ) ) {
				$ancestors = array_reverse( $ancestors );
				foreach ( $ancestors as $ancestor ) {
					$breadcrumbs[] = array(
						'label' => esc_html( get_the_title( $ancestor ) ),
						'url'   => esc_url( get_permalink( $ancestor ) ),
					);
				}
			}

			$breadcrumbs[] = array(
				'label' => esc_html( get_the_title() ),
				'url'   => esc_url( get_permalink() ),
			);
		} elseif ( is_category() || is_tag() || is_tax() ) {
			// Category, tag, or custom taxonomy.
			$term = get_queried_object();
			$breadcrumbs[] = array(
				'label' => esc_html( $term->name ),
				'url'   => esc_url( get_term_link( $term ) ),
			);
		} elseif ( is_search() ) {
			// Search.
			$breadcrumbs[] = array(
				'label' => esc_html__( 'Search Results', 'aqualuxe' ),
				'url'   => esc_url( get_search_link() ),
			);
		} elseif ( is_404() ) {
			// 404.
			$breadcrumbs[] = array(
				'label' => esc_html__( 'Page Not Found', 'aqualuxe' ),
				'url'   => esc_url( home_url( '/404/' ) ),
			);
		}

		// Output breadcrumbs.
		if ( ! empty( $breadcrumbs ) ) :
			?>
			<nav class="breadcrumbs" aria-label="<?php esc_attr_e( 'Breadcrumb', 'aqualuxe' ); ?>">
				<div class="breadcrumbs-container">
					<ol class="breadcrumbs-list" itemscope itemtype="http://schema.org/BreadcrumbList">
						<?php foreach ( $breadcrumbs as $index => $breadcrumb ) : ?>
							<li class="breadcrumbs-item" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
								<?php if ( $index === count( $breadcrumbs ) - 1 ) : ?>
									<span class="breadcrumbs-item-current" itemprop="name"><?php echo esc_html( $breadcrumb['label'] ); ?></span>
								<?php else : ?>
									<a class="breadcrumbs-item-link" href="<?php echo esc_url( $breadcrumb['url'] ); ?>" itemprop="item">
										<span itemprop="name"><?php echo esc_html( $breadcrumb['label'] ); ?></span>
									</a>
								<?php endif; ?>
								<meta itemprop="position" content="<?php echo esc_attr( $index + 1 ); ?>" />
							</li>
							<?php if ( $index < count( $breadcrumbs ) - 1 ) : ?>
								<li class="breadcrumbs-separator">/</li>
							<?php endif; ?>
						<?php endforeach; ?>
					</ol>
				</div>
			</nav><!-- .breadcrumbs -->
			<?php
		endif;
	}
endif;

/**
 * Displays social icons.
 */
if ( ! function_exists( 'aqualuxe_social_icons' ) ) :
	/**
	 * Displays social icons.
	 */
	function aqualuxe_social_icons() {
		$social_links = array(
			'facebook'  => get_theme_mod( 'aqualuxe_facebook_url' ),
			'twitter'   => get_theme_mod( 'aqualuxe_twitter_url' ),
			'instagram' => get_theme_mod( 'aqualuxe_instagram_url' ),
			'linkedin'  => get_theme_mod( 'aqualuxe_linkedin_url' ),
			'youtube'   => get_theme_mod( 'aqualuxe_youtube_url' ),
			'pinterest' => get_theme_mod( 'aqualuxe_pinterest_url' ),
		);

		$social_links = array_filter( $social_links );

		if ( empty( $social_links ) ) {
			return;
		}

		echo '<div class="social-icons">';
		echo '<ul class="social-icons-list">';

		foreach ( $social_links as $network => $url ) {
			echo '<li class="social-icons-item">';
			echo '<a class="social-icons-link social-icons-' . esc_attr( $network ) . '" href="' . esc_url( $url ) . '" target="_blank" rel="noopener noreferrer">';
			echo '<span class="screen-reader-text">' . esc_html( ucfirst( $network ) ) . '</span>';
			echo aqualuxe_get_social_icon( $network );
			echo '</a>';
			echo '</li>';
		}

		echo '</ul>';
		echo '</div><!-- .social-icons -->';
	}
endif;

/**
 * Get social icon SVG.
 *
 * @param string $network Social network name.
 * @return string SVG icon.
 */
if ( ! function_exists( 'aqualuxe_get_social_icon' ) ) :
	/**
	 * Get social icon SVG.
	 *
	 * @param string $network Social network name.
	 * @return string SVG icon.
	 */
	function aqualuxe_get_social_icon( $network ) {
		$icons = array(
			'facebook'  => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path></svg>',
			'twitter'   => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z"></path></svg>',
			'instagram' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg>',
			'linkedin'  => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"></path><rect x="2" y="9" width="4" height="12"></rect><circle cx="4" cy="4" r="2"></circle></svg>',
			'youtube'   => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22.54 6.42a2.78 2.78 0 0 0-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 0 0-1.94 2A29 29 0 0 0 1 11.75a29 29 0 0 0 .46 5.33A2.78 2.78 0 0 0 3.4 19c1.72.46 8.6.46 8.6.46s6.88 0 8.6-.46a2.78 2.78 0 0 0 1.94-2 29 29 0 0 0 .46-5.25 29 29 0 0 0-.46-5.33z"></path><polygon points="9.75 15.02 15.5 11.75 9.75 8.48 9.75 15.02"></polygon></svg>',
			'pinterest' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 12a4 4 0 1 0 8 0 4 4 0 0 0-8 0z"></path><path d="M12 2a10 10 0 0 0-3.16 19.5c0-.35-.1-1.26-.1-1.9 0-.17-.03-.44-.03-.62 0 0-1.3.03-1.6.03-.53 0-1.02-.06-1.3-.33-.22-.2-.37-.5-.4-.85-.03-.3.05-.6.27-.8.03-.03.1-.1.17-.13-.33-.4-.5-.93-.5-1.47 0-1.07.9-1.97 2-1.97.15 0 .3.03.44.06.18-.33.4-.6.7-.8.3-.2.64-.33 1-.36.33-.03.63.03.9.17.3.13.5.33.7.57.2.23.3.5.36.77.07.27.1.53.1.8 0 .33-.06.67-.16.97-.1.3-.25.57-.45.8-.2.23-.45.4-.75.53-.3.13-.65.2-1 .2-.25 0-.5-.04-.72-.13-.22-.1-.4-.22-.55-.4-.1.35-.2.7-.36 1.04-.17.33-.35.63-.6.9-.23.27-.5.5-.8.67-.3.17-.65.27-1 .27-.38 0-.75-.1-1.08-.3-.33-.2-.6-.47-.8-.8-.22-.33-.38-.7-.5-1.1-.1-.4-.16-.83-.16-1.26 0-.58.1-1.15.3-1.68.2-.53.5-1 .9-1.4.38-.4.85-.72 1.35-.94.5-.22 1.02-.33 1.58-.33.55 0 1.1.1 1.6.3.5.2.96.5 1.35.87.4.37.7.8.92 1.3.22.5.33 1.04.33 1.6 0 .57-.1 1.12-.27 1.65-.18.53-.45 1-.8 1.42-.36.4-.8.74-1.3.97-.5.23-1.07.35-1.66.35-.6 0-1.15-.12-1.66-.35-.5-.23-.93-.57-1.3-.97-.36-.4-.63-.9-.8-1.42-.18-.53-.27-1.08-.27-1.65 0-.56.1-1.1.33-1.6.22-.5.53-.93.92-1.3.4-.37.85-.67 1.35-.87.5-.2 1.05-.3 1.6-.3.56 0 1.08.1 1.58.33.5.22.97.54 1.35.94.4.4.7.87.9 1.4.2.53.3 1.1.3 1.68 0 .43-.05.86-.16 1.26-.1.4-.27.77-.5 1.1-.2.33-.47.6-.8.8-.33.2-.7.3-1.08.3-.35 0-.7-.1-1-.27-.3-.17-.57-.4-.8-.67-.25-.27-.43-.57-.6-.9-.15-.34-.26-.7-.36-1.04-.15.18-.33.3-.55.4-.22.1-.47.13-.72.13-.35 0-.7-.07-1-.2-.3-.13-.55-.3-.75-.53-.2-.23-.35-.5-.45-.8-.1-.3-.16-.64-.16-.97 0-.27.03-.53.1-.8.06-.27.17-.54.36-.77.2-.24.4-.44.7-.57.27-.14.57-.2.9-.17.36.03.7.16 1 .36.3.2.52.47.7.8.14-.03.3-.06.44-.06 1.1 0 2 .9 2 1.97 0 .54-.17 1.07-.5 1.47.07.03.14.1.17.13.22.2.3.5.27.8-.03.35-.18.65-.4.85-.28.27-.77.33-1.3.33-.3 0-1.6-.03-1.6-.03 0 .18-.03.45-.03.62 0 .64-.1 1.55-.1 1.9A10 10 0 0 0 12 2z"></path></svg>',
		);

		return isset( $icons[ $network ] ) ? $icons[ $network ] : '';
	}
endif;

/**
 * Displays contact information.
 */
if ( ! function_exists( 'aqualuxe_contact_info' ) ) :
	/**
	 * Displays contact information.
	 */
	function aqualuxe_contact_info() {
		$phone   = get_theme_mod( 'aqualuxe_phone' );
		$email   = get_theme_mod( 'aqualuxe_email' );
		$address = array(
			'street'  => get_theme_mod( 'aqualuxe_address_street' ),
			'city'    => get_theme_mod( 'aqualuxe_address_city' ),
			'state'   => get_theme_mod( 'aqualuxe_address_state' ),
			'zip'     => get_theme_mod( 'aqualuxe_address_zip' ),
			'country' => get_theme_mod( 'aqualuxe_address_country' ),
		);

		$has_address = $address['street'] && $address['city'] && $address['state'];

		if ( ! $phone && ! $email && ! $has_address ) {
			return;
		}

		echo '<div class="contact-info">';
		echo '<ul class="contact-info-list">';

		if ( $phone ) {
			echo '<li class="contact-info-item contact-info-phone">';
			echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>';
			echo '<a href="tel:' . esc_attr( preg_replace( '/[^0-9+]/', '', $phone ) ) . '">' . esc_html( $phone ) . '</a>';
			echo '</li>';
		}

		if ( $email ) {
			echo '<li class="contact-info-item contact-info-email">';
			echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>';
			echo '<a href="mailto:' . esc_attr( $email ) . '">' . esc_html( $email ) . '</a>';
			echo '</li>';
		}

		if ( $has_address ) {
			$address_text = $address['street'];
			if ( $address['city'] && $address['state'] ) {
				$address_text .= ', ' . $address['city'] . ', ' . $address['state'];
			}
			if ( $address['zip'] ) {
				$address_text .= ' ' . $address['zip'];
			}
			if ( $address['country'] ) {
				$address_text .= ', ' . $address['country'];
			}

			$address_url = 'https://maps.google.com/?q=' . urlencode( $address_text );

			echo '<li class="contact-info-item contact-info-address">';
			echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>';
			echo '<a href="' . esc_url( $address_url ) . '" target="_blank" rel="noopener noreferrer">' . esc_html( $address_text ) . '</a>';
			echo '</li>';
		}

		echo '</ul>';
		echo '</div><!-- .contact-info -->';
	}
endif;

/**
 * Displays back to top button.
 */
if ( ! function_exists( 'aqualuxe_back_to_top' ) ) :
	/**
	 * Displays back to top button.
	 */
	function aqualuxe_back_to_top() {
		if ( ! get_theme_mod( 'aqualuxe_enable_back_to_top', true ) ) {
			return;
		}

		echo '<a href="#page" class="back-to-top" aria-label="' . esc_attr__( 'Back to top', 'aqualuxe' ) . '">';
		echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="18 15 12 9 6 15"></polyline></svg>';
		echo '</a>';
	}
endif;

/**
 * Displays dark mode toggle.
 */
if ( ! function_exists( 'aqualuxe_dark_mode_toggle' ) ) :
	/**
	 * Displays dark mode toggle.
	 */
	function aqualuxe_dark_mode_toggle() {
		if ( ! get_theme_mod( 'aqualuxe_enable_dark_mode', true ) ) {
			return;
		}

		echo '<button class="dark-mode-toggle" aria-label="' . esc_attr__( 'Toggle dark mode', 'aqualuxe' ) . '">';
		echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="dark-mode-toggle-sun"><circle cx="12" cy="12" r="5"></circle><line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line><line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line></svg>';
		echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="dark-mode-toggle-moon"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg>';
		echo '</button>';
	}
endif;

/**
 * Displays search form.
 */
if ( ! function_exists( 'aqualuxe_search_form' ) ) :
	/**
	 * Displays search form.
	 */
	function aqualuxe_search_form() {
		$search_placeholder = esc_attr__( 'Search...', 'aqualuxe' );
		$search_submit      = esc_attr__( 'Search', 'aqualuxe' );

		echo '<form role="search" method="get" class="search-form" action="' . esc_url( home_url( '/' ) ) . '">';
		echo '<label>';
		echo '<span class="screen-reader-text">' . esc_html__( 'Search for:', 'aqualuxe' ) . '</span>';
		echo '<input type="search" class="search-field" placeholder="' . esc_attr( $search_placeholder ) . '" value="' . get_search_query() . '" name="s" />';
		echo '</label>';
		echo '<button type="submit" class="search-submit">';
		echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>';
		echo '<span class="screen-reader-text">' . esc_html( $search_submit ) . '</span>';
		echo '</button>';
		echo '</form>';
	}
endif;

/**
 * Displays header search icon.
 */
if ( ! function_exists( 'aqualuxe_header_search_icon' ) ) :
	/**
	 * Displays header search icon.
	 */
	function aqualuxe_header_search_icon() {
		if ( ! get_theme_mod( 'aqualuxe_header_search', true ) ) {
			return;
		}

		echo '<div class="header-search">';
		echo '<button class="header-search-toggle" aria-expanded="false" aria-label="' . esc_attr__( 'Toggle search', 'aqualuxe' ) . '">';
		echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>';
		echo '</button>';
		echo '<div class="header-search-dropdown">';
		aqualuxe_search_form();
		echo '</div>';
		echo '</div>';
	}
endif;
add_action( 'aqualuxe_header_icons', 'aqualuxe_header_search_icon', 10 );

/**
 * Displays mobile menu toggle.
 */
if ( ! function_exists( 'aqualuxe_mobile_menu_toggle' ) ) :
	/**
	 * Displays mobile menu toggle.
	 */
	function aqualuxe_mobile_menu_toggle() {
		echo '<button class="mobile-menu-toggle" aria-controls="primary-menu" aria-expanded="false" aria-label="' . esc_attr__( 'Toggle menu', 'aqualuxe' ) . '">';
		echo '<span class="mobile-menu-toggle-bar"></span>';
		echo '<span class="mobile-menu-toggle-bar"></span>';
		echo '<span class="mobile-menu-toggle-bar"></span>';
		echo '</button>';
	}
endif;

/**
 * Displays skip link.
 */
if ( ! function_exists( 'aqualuxe_skip_link' ) ) :
	/**
	 * Displays skip link.
	 */
	function aqualuxe_skip_link() {
		echo '<a class="skip-link screen-reader-text" href="#content">' . esc_html__( 'Skip to content', 'aqualuxe' ) . '</a>';
	}
endif;
add_action( 'wp_body_open', 'aqualuxe_skip_link', 5 );

/**
 * Displays site logo.
 */
if ( ! function_exists( 'aqualuxe_site_logo' ) ) :
	/**
	 * Displays site logo.
	 */
	function aqualuxe_site_logo() {
		if ( has_custom_logo() ) {
			the_custom_logo();
		} else {
			echo '<a href="' . esc_url( home_url( '/' ) ) . '" rel="home" class="site-title">' . esc_html( get_bloginfo( 'name' ) ) . '</a>';
			$description = get_bloginfo( 'description', 'display' );
			if ( $description || is_customize_preview() ) {
				echo '<p class="site-description">' . esc_html( $description ) . '</p>';
			}
		}
	}
endif;

/**
 * Displays site header.
 */
if ( ! function_exists( 'aqualuxe_site_header' ) ) :
	/**
	 * Displays site header.
	 */
	function aqualuxe_site_header() {
		$header_layout = get_theme_mod( 'aqualuxe_header_layout', 'default' );
		$sticky_header = get_theme_mod( 'aqualuxe_sticky_header', true );
		$header_class  = 'site-header';
		$header_class .= ' site-header-' . $header_layout;
		if ( $sticky_header ) {
			$header_class .= ' site-header-sticky';
		}
		?>
		<header id="masthead" class="<?php echo esc_attr( $header_class ); ?>">
			<div class="site-header-container">
				<div class="site-branding">
					<?php aqualuxe_site_logo(); ?>
				</div><!-- .site-branding -->

				<div class="site-navigation-wrapper">
					<nav id="site-navigation" class="main-navigation" aria-label="<?php esc_attr_e( 'Main Navigation', 'aqualuxe' ); ?>">
						<?php
						wp_nav_menu(
							array(
								'theme_location' => 'primary',
								'menu_id'        => 'primary-menu',
								'container'      => false,
								'fallback_cb'    => 'aqualuxe_primary_menu_fallback',
							)
						);
						?>
					</nav><!-- #site-navigation -->
				</div><!-- .site-navigation-wrapper -->

				<div class="site-header-icons">
					<?php
					/**
					 * Hook: aqualuxe_header_icons.
					 *
					 * @hooked aqualuxe_header_search_icon - 10
					 * @hooked aqualuxe_header_cart_icon - 20 (if WooCommerce is active)
					 * @hooked aqualuxe_header_account_icon - 30 (if WooCommerce is active)
					 * @hooked aqualuxe_header_wishlist_icon - 40 (if WooCommerce is active and wishlist is enabled)
					 */
					do_action( 'aqualuxe_header_icons' );
					?>

					<?php aqualuxe_dark_mode_toggle(); ?>
					<?php aqualuxe_mobile_menu_toggle(); ?>
				</div><!-- .site-header-icons -->
			</div><!-- .site-header-container -->
		</header><!-- #masthead -->
		<?php
	}
endif;
add_action( 'aqualuxe_header', 'aqualuxe_site_header', 10 );

/**
 * Primary menu fallback.
 */
if ( ! function_exists( 'aqualuxe_primary_menu_fallback' ) ) :
	/**
	 * Primary menu fallback.
	 */
	function aqualuxe_primary_menu_fallback() {
		if ( current_user_can( 'edit_theme_options' ) ) {
			echo '<ul id="primary-menu" class="menu">';
			echo '<li class="menu-item">';
			echo '<a href="' . esc_url( admin_url( 'nav-menus.php' ) ) . '">' . esc_html__( 'Create a menu', 'aqualuxe' ) . '</a>';
			echo '</li>';
			echo '</ul>';
		}
	}
endif;

/**
 * Displays site footer.
 */
if ( ! function_exists( 'aqualuxe_site_footer' ) ) :
	/**
	 * Displays site footer.
	 */
	function aqualuxe_site_footer() {
		$footer_layout = get_theme_mod( 'aqualuxe_footer_layout', '4-columns' );
		$footer_text   = get_theme_mod(
			'aqualuxe_footer_text',
			sprintf(
				/* translators: %1$s: Current year, %2$s: Site name */
				esc_html__( '© %1$s %2$s. All rights reserved.', 'aqualuxe' ),
				date_i18n( 'Y' ),
				get_bloginfo( 'name' )
			)
		);
		$show_social   = get_theme_mod( 'aqualuxe_footer_social', true );
		$show_payment  = get_theme_mod( 'aqualuxe_footer_payment', true ) && class_exists( 'WooCommerce' );
		?>
		<footer id="colophon" class="site-footer site-footer-<?php echo esc_attr( $footer_layout ); ?>">
			<?php if ( is_active_sidebar( 'footer-1' ) || is_active_sidebar( 'footer-2' ) || is_active_sidebar( 'footer-3' ) || is_active_sidebar( 'footer-4' ) ) : ?>
				<div class="footer-widgets">
					<div class="footer-widgets-container">
						<div class="footer-widgets-row">
							<?php if ( is_active_sidebar( 'footer-1' ) ) : ?>
								<div class="footer-widget-column footer-widget-1">
									<?php dynamic_sidebar( 'footer-1' ); ?>
								</div>
							<?php endif; ?>

							<?php if ( is_active_sidebar( 'footer-2' ) && '1-column' !== $footer_layout ) : ?>
								<div class="footer-widget-column footer-widget-2">
									<?php dynamic_sidebar( 'footer-2' ); ?>
								</div>
							<?php endif; ?>

							<?php if ( is_active_sidebar( 'footer-3' ) && '1-column' !== $footer_layout && '2-columns' !== $footer_layout ) : ?>
								<div class="footer-widget-column footer-widget-3">
									<?php dynamic_sidebar( 'footer-3' ); ?>
								</div>
							<?php endif; ?>

							<?php if ( is_active_sidebar( 'footer-4' ) && '4-columns' === $footer_layout ) : ?>
								<div class="footer-widget-column footer-widget-4">
									<?php dynamic_sidebar( 'footer-4' ); ?>
								</div>
							<?php endif; ?>
						</div>
					</div>
				</div><!-- .footer-widgets -->
			<?php endif; ?>

			<div class="site-info">
				<div class="site-info-container">
					<div class="site-info-row">
						<div class="site-info-copyright">
							<?php echo wp_kses_post( $footer_text ); ?>
						</div>

						<?php if ( $show_social ) : ?>
							<div class="site-info-social">
								<?php aqualuxe_social_icons(); ?>
							</div>
						<?php endif; ?>

						<?php if ( $show_payment ) : ?>
							<div class="site-info-payment">
								<div class="payment-icons">
									<span class="payment-icon payment-icon-visa">
										<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 38 24" width="38" height="24"><path opacity=".07" d="M35 0H3C1.3 0 0 1.3 0 3v18c0 1.7 1.4 3 3 3h32c1.7 0 3-1.3 3-3V3c0-1.7-1.4-3-3-3z"/><path fill="#fff" d="M35 1c1.1 0 2 .9 2 2v18c0 1.1-.9 2-2 2H3c-1.1 0-2-.9-2-2V3c0-1.1.9-2 2-2h32"/><path d="M28.3 10.1H28c-.4 1-.7 1.5-1 3h1.9c-.3-1.5-.3-2.2-.6-3zm2.9 5.9h-1.7c-.1 0-.1 0-.2-.1l-.2-.9-.1-.2h-2.4c-.1 0-.2 0-.2.2l-.3.9c0 .1-.1.1-.1.1h-2.1l.2-.5L27 8.7c0-.5.3-.7.8-.7h1.5c.1 0 .2 0 .2.2l1.4 6.5c.1.4.2.7.2 1.1.1.1.1.1.1.2zm-13.4-.3l.4-1.8c.1 0 .2.1.2.1.7.3 1.4.5 2.1.4.2 0 .5-.1.7-.2.5-.2.5-.7.1-1.1-.2-.2-.5-.3-.8-.5-.4-.2-.8-.4-1.1-.7-1.2-1-.8-2.4-.1-3.1.6-.4.9-.8 1.7-.8 1.2 0 2.5 0 3.1.2h.1c-.1.6-.2 1.1-.4 1.7-.5-.2-1-.4-1.5-.4-.3 0-.6 0-.9.1-.2 0-.3.1-.4.2-.2.2-.2.5 0 .7l.5.4c.4.2.8.4 1.1.6.5.3 1 .8 1.1 1.4.2.9-.1 1.7-.9 2.3-.5.4-.7.6-1.4.6-1.4 0-2.5.1-3.4-.2-.1.2-.1.2-.2.1zm-3.5.3c.1-.7.1-.7.2-1 .5-2.2 1-4.5 1.4-6.7.1-.2.1-.3.3-.3H18c-.2 1.2-.4 2.1-.7 3.2-.3 1.5-.6 3-1 4.5 0 .2-.1.2-.3.2M5 8.2c0-.1.2-.2.3-.2h3.4c.5 0 .9.3 1 .8l.9 4.4c0 .1 0 .1.1.2 0-.1.1-.1.1-.1l2.1-5.1c-.1-.1 0-.2.1-.2h2.1c0 .1 0 .1-.1.2l-3.1 7.3c-.1.2-.1.3-.2.4-.1.1-.3 0-.5 0H9.7c-.1 0-.2 0-.2-.2L7.9 9.5c-.2-.2-.5-.5-.9-.6-.6-.3-1.7-.5-1.9-.5L5 8.2z" fill="#142688"/></svg>
									</span>
									<span class="payment-icon payment-icon-mastercard">
										<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 38 24" width="38" height="24"><path opacity=".07" d="M35 0H3C1.3 0 0 1.3 0 3v18c0 1.7 1.4 3 3 3h32c1.7 0 3-1.3 3-3V3c0-1.7-1.4-3-3-3z"/><path fill="#fff" d="M35 1c1.1 0 2 .9 2 2v18c0 1.1-.9 2-2 2H3c-1.1 0-2-.9-2-2V3c0-1.1.9-2 2-2h32"/><circle fill="#EB001B" cx="15" cy="12" r="7"/><circle fill="#F79E1B" cx="23" cy="12" r="7"/><path fill="#FF5F00" d="M22 12c0-2.4-1.2-4.5-3-5.7-1.8 1.3-3 3.4-3 5.7s1.2 4.5 3 5.7c1.8-1.2 3-3.3 3-5.7z"/></svg>
									</span>
									<span class="payment-icon payment-icon-amex">
										<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 38 24" width="38" height="24"><path opacity=".07" d="M35 0H3C1.3 0 0 1.3 0 3v18c0 1.7 1.4 3 3 3h32c1.7 0 3-1.3 3-3V3c0-1.7-1.4-3-3-3z"/><path fill="#fff" d="M35 1c1.1 0 2 .9 2 2v18c0 1.1-.9 2-2 2H3c-1.1 0-2-.9-2-2V3c0-1.1.9-2 2-2h32"/><path d="M27.5 10.1c0-2.7-2.1-4.9-4.8-5H15v10h7.7c2.7 0 4.8-2.2 4.8-5zm-1.6 0c0 1.7-1.4 3.1-3.2 3.1h-5.9v-6.1h5.9c1.8-.1 3.2 1.3 3.2 3zm-15.2 5h-2.5v-10h2.5v10zm-5-10H0v10h5.7c2.2 0 3.8-1.2 3.8-3.4 0-1.5-.8-2.3-2.1-2.8 1-.5 1.5-1.4 1.5-2.4-.1-1.3-1.1-1.4-2.2-1.4zm-.4 5.1H2.9v-3.1h2.4c1 0 1.9.3 1.9 1.5 0 1.1-.9 1.6-1.9 1.6zm0 3.8H2.9v-3h2.4c1.1 0 2 .5 2 1.6-.1 1.1-.9 1.4-2 1.4zm9.1-5.9v1.8h4.2v1.8h-4.2v1.8h4.7v1.8h-7.2v-9h7.1v1.8h-4.6z" fill="#006FCF"/></svg>
									</span>
									<span class="payment-icon payment-icon-paypal">
										<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 38 24" width="38" height="24"><path opacity=".07" d="M35 0H3C1.3 0 0 1.3 0 3v18c0 1.7 1.4 3 3 3h32c1.7 0 3-1.3 3-3V3c0-1.7-1.4-3-3-3z"/><path fill="#fff" d="M35 1c1.1 0 2 .9 2 2v18c0 1.1-.9 2-2 2H3c-1.1 0-2-.9-2-2V3c0-1.1.9-2 2-2h32"/><path fill="#003087" d="M23.9 8.3c.2-1 0-1.7-.6-2.3-.6-.7-1.7-1-3.1-1h-4.1c-.3 0-.5.2-.6.5L14 15.6c0 .2.1.4.3.4H17l.4-3.4 1.8-2.2 4.7-2.1z"/><path fill="#3086C8" d="M23.9 8.3l-.2.2c-.5 2.8-2.2 3.8-4.6 3.8H18c-.3 0-.5.2-.6.5l-.6 3.9-.2 1c0 .2.1.4.3.4H19c.3 0 .5-.2.5-.4v-.1l.4-2.4v-.1c0-.2.3-.4.5-.4h.3c2.1 0 3.7-.8 4.1-3.2.2-1 .1-1.8-.4-2.4-.1-.5-.3-.7-.5-.8z"/><path fill="#012169" d="M23.3 8.1c-.1-.1-.2-.1-.3-.1-.1 0-.2 0-.3-.1-.3-.1-.7-.1-1.1-.1h-3c-.1 0-.2 0-.2.1-.2.1-.3.2-.3.4l-.7 4.4v.1c0-.3.3-.5.6-.5h1.3c2.5 0 4.1-1 4.6-3.8v-.2c-.1-.1-.3-.2-.5-.2h-.1z"/></svg>
									</span>
									<span class="payment-icon payment-icon-apple-pay">
										<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 38 24" width="38" height="24"><path opacity=".07" d="M35 0H3C1.3 0 0 1.3 0 3v18c0 1.7 1.4 3 3 3h32c1.7 0 3-1.3 3-3V3c0-1.7-1.4-3-3-3z"/><path fill="#fff" d="M35 1c1.1 0 2 .9 2 2v18c0 1.1-.9 2-2 2H3c-1.1 0-2-.9-2-2V3c0-1.1.9-2 2-2h32"/><path d="M23.5 11.5c0-.8.4-1.6 1-2.1.6-.5 1.4-.8 2.2-.8.1 0 .2 0 .3.1-.7.8-1.1 1.8-1 2.8.1 1 .6 1.8 1.3 2.5-.5.4-1.2.6-1.8.6-.6 0-1.1-.2-1.5-.5-.5-.3-.8-.8-.8-1.3-.1-.5-.1-.9.3-1.3zM19 7c-.5 0-1 .2-1.3.5-.4.4-.6.8-.6 1.3v7.5c0 .5.2 1 .6 1.3.3.4.8.5 1.3.5h11c.5 0 1-.2 1.3-.5.4-.4.6-.8.6-1.3V8.8c0-.5-.2-1-.6-1.3-.3-.4-.8-.5-1.3-.5H19z" fill="#000"/></svg>
									</span>
									<span class="payment-icon payment-icon-google-pay">
										<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 38 24" width="38" height="24"><path opacity=".07" d="M35 0H3C1.3 0 0 1.3 0 3v18c0 1.7 1.4 3 3 3h32c1.7 0 3-1.3 3-3V3c0-1.7-1.4-3-3-3z"/><path fill="#fff" d="M35 1c1.1 0 2 .9 2 2v18c0 1.1-.9 2-2 2H3c-1.1 0-2-.9-2-2V3c0-1.1.9-2 2-2h32"/><path d="M18.93 11.5c-.7 0-1.27.56-1.27 1.26 0 .7.57 1.25 1.27 1.25.7 0 1.27-.56 1.27-1.25-.01-.7-.58-1.26-1.27-1.26zm0 2.01c-.42 0-.77-.34-.77-.75 0-.42.35-.75.77-.75.42 0 .77.34.77.75 0 .41-.35.75-.77.75zm-2.73-2.01c-.7 0-1.27.56-1.27 1.26 0 .7.57 1.25 1.27 1.25.7 0 1.27-.56 1.27-1.25-.01-.7-.57-1.26-1.27-1.26zm0 2.01c-.42 0-.77-.34-.77-.75 0-.42.35-.75.77-.75.42 0 .77.34.77.75 0 .41-.35.75-.77.75zm5.48-1.28v-.5h1.2v-.47c0-1.11-.91-2.01-2.02-2.01-1.12 0-2.03.9-2.03 2.01 0 1.11.91 2.01 2.03 2.01.54 0 1.05-.21 1.41-.6l-.35-.35c-.25.25-.6.4-.97.4-.67 0-1.22-.53-1.22-1.2 0-.67.55-1.2 1.22-1.2.63 0 1.15.49 1.2 1.12h-.73v.5h1.26zm1.81-1.5h.36v2.99h-.36V11.74zm.93 1.5c0-.83.68-1.5 1.5-1.5.83 0 1.5.67 1.5 1.5s-.67 1.5-1.5 1.5c-.82 0-1.5-.67-1.5-1.5zm2.65 0c0-.64-.52-1.17-1.15-1.17-.64 0-1.15.53-1.15 1.17s.51 1.17 1.15 1.17c.63 0 1.15-.53 1.15-1.17zm-8.01-1.5h.36v2.99h-.36V11.74zm13.52 0h.36v2.99h-.36V11.74zm-6.11 0h.36v2.99h-.36V11.74zm-4.36 0h.36v2.99h-.36V11.74zm1.71 1.01h.49v.36h-.49v.61h-.36v-.61h-1.3v-.36l1.28-1.82h.38v1.82zm-.36 0v-1.15l-.84 1.15h.84z" fill="#3C4043"/><path d="M21.46 8.7c0-.24.02-.48.05-.71h-4.5v1.34h2.57c-.11.59-.45 1.1-.95 1.44v1.19h1.54c.9-.83 1.41-2.06 1.29-3.26z" fill="#4285F4"/><path d="M17.01 13.71c1.29 0 2.37-.43 3.16-1.16l-1.54-1.19c-.43.29-.98.45-1.62.45-1.24 0-2.3-.84-2.68-1.98h-1.6v1.23c.8 1.9 2.68 3.13 4.78 2.65z" fill="#34A853"/><path d="M14.33 9.83c-.25-.72-.25-1.51.01-2.22V6.38h-1.59c-.8 1.13-.83 2.58-.16 3.75l1.74-1.42z" fill="#FBBC04"/><path d="M17.01 5.64c.69-.02 1.37.25 1.88.75l1.36-1.36c-.87-.82-2.01-1.27-3.19-1.27-2.1.01-3.99 1.27-4.78 3.22l1.59 1.24c.4-1.15 1.45-1.94 2.69-1.97z" fill="#EA4335"/></svg>
									</span>
								</div>
							</div>
						<?php endif; ?>
					</div>
				</div>
			</div><!-- .site-info -->
		</footer><!-- #colophon -->
		<?php
	}
endif;
add_action( 'aqualuxe_footer', 'aqualuxe_site_footer', 10 );

/**
 * Displays no posts found message.
 */
if ( ! function_exists( 'aqualuxe_no_posts_found' ) ) :
	/**
	 * Displays no posts found message.
	 */
	function aqualuxe_no_posts_found() {
		?>
		<section class="no-results not-found">
			<header class="page-header">
				<h1 class="page-title"><?php esc_html_e( 'Nothing Found', 'aqualuxe' ); ?></h1>
			</header><!-- .page-header -->

			<div class="page-content">
				<?php
				if ( is_home() && current_user_can( 'publish_posts' ) ) :
					printf(
						'<p>' . wp_kses(
							/* translators: 1: link to WP admin new post page. */
							__( 'Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'aqualuxe' ),
							array(
								'a' => array(
									'href' => array(),
								),
							)
						) . '</p>',
						esc_url( admin_url( 'post-new.php' ) )
					);
				elseif ( is_search() ) :
					?>
					<p><?php esc_html_e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'aqualuxe' ); ?></p>
					<?php
					get_search_form();
				else :
					?>
					<p><?php esc_html_e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'aqualuxe' ); ?></p>
					<?php
					get_search_form();
				endif;
				?>
			</div><!-- .page-content -->
		</section><!-- .no-results -->
		<?php
	}
endif;

/**
 * Displays 404 content.
 */
if ( ! function_exists( 'aqualuxe_404_content' ) ) :
	/**
	 * Displays 404 content.
	 */
	function aqualuxe_404_content() {
		?>
		<section class="error-404 not-found">
			<header class="page-header">
				<h1 class="page-title"><?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'aqualuxe' ); ?></h1>
			</header><!-- .page-header -->

			<div class="page-content">
				<p><?php esc_html_e( 'It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'aqualuxe' ); ?></p>

				<?php get_search_form(); ?>

				<div class="error-404-widgets">
					<div class="error-404-widget">
						<h2><?php esc_html_e( 'Recent Posts', 'aqualuxe' ); ?></h2>
						<ul>
							<?php
							wp_get_archives(
								array(
									'type'     => 'postbypost',
									'limit'    => 5,
									'format'   => 'html',
									'show_post_count' => false,
								)
							);
							?>
						</ul>
					</div>

					<div class="error-404-widget">
						<h2><?php esc_html_e( 'Most Used Categories', 'aqualuxe' ); ?></h2>
						<ul>
							<?php
							wp_list_categories(
								array(
									'orderby'    => 'count',
									'order'      => 'DESC',
									'show_count' => 1,
									'title_li'   => '',
									'number'     => 5,
								)
							);
							?>
						</ul>
					</div>

					<div class="error-404-widget">
						<h2><?php esc_html_e( 'Archives', 'aqualuxe' ); ?></h2>
						<ul>
							<?php
							wp_get_archives(
								array(
									'type'     => 'monthly',
									'limit'    => 5,
									'format'   => 'html',
									'show_post_count' => true,
								)
							);
							?>
						</ul>
					</div>
				</div>
			</div><!-- .page-content -->
		</section><!-- .error-404 -->
		<?php
	}
endif;

/**
 * Displays archive header.
 */
if ( ! function_exists( 'aqualuxe_archive_header' ) ) :
	/**
	 * Displays archive header.
	 */
	function aqualuxe_archive_header() {
		if ( ! is_archive() ) {
			return;
		}
		?>
		<header class="page-header">
			<?php
			the_archive_title( '<h1 class="page-title">', '</h1>' );
			the_archive_description( '<div class="archive-description">', '</div>' );
			?>
		</header><!-- .page-header -->
		<?php
	}
endif;

/**
 * Displays search header.
 */
if ( ! function_exists( 'aqualuxe_search_header' ) ) :
	/**
	 * Displays search header.
	 */
	function aqualuxe_search_header() {
		if ( ! is_search() ) {
			return;
		}
		?>
		<header class="page-header">
			<h1 class="page-title">
				<?php
				/* translators: %s: search query. */
				printf( esc_html__( 'Search Results for: %s', 'aqualuxe' ), '<span>' . get_search_query() . '</span>' );
				?>
			</h1>
		</header><!-- .page-header -->
		<?php
	}
endif;

/**
 * Displays page header.
 */
if ( ! function_exists( 'aqualuxe_page_header' ) ) :
	/**
	 * Displays page header.
	 */
	function aqualuxe_page_header() {
		if ( ! is_page() ) {
			return;
		}
		?>
		<header class="entry-header">
			<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
		</header><!-- .entry-header -->
		<?php
	}
endif;

/**
 * Displays post header.
 */
if ( ! function_exists( 'aqualuxe_post_header' ) ) :
	/**
	 * Displays post header.
	 */
	function aqualuxe_post_header() {
		if ( ! is_singular( 'post' ) ) {
			return;
		}
		?>
		<header class="entry-header">
			<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
			<?php aqualuxe_entry_meta(); ?>
		</header><!-- .entry-header -->
		<?php
	}
endif;

/**
 * Displays post card.
 */
if ( ! function_exists( 'aqualuxe_post_card' ) ) :
	/**
	 * Displays post card.
	 */
	function aqualuxe_post_card() {
		?>
		<article id="post-<?php the_ID(); ?>" <?php post_class( 'post-card' ); ?>>
			<?php aqualuxe_post_thumbnail(); ?>
			<div class="post-card-content">
				<header class="post-card-header">
					<?php the_title( '<h2 class="post-card-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' ); ?>
					<?php aqualuxe_entry_meta_compact(); ?>
				</header><!-- .post-card-header -->

				<div class="post-card-excerpt">
					<?php the_excerpt(); ?>
				</div><!-- .post-card-excerpt -->

				<footer class="post-card-footer">
					<a href="<?php the_permalink(); ?>" class="post-card-link"><?php esc_html_e( 'Read More', 'aqualuxe' ); ?></a>
				</footer><!-- .post-card-footer -->
			</div><!-- .post-card-content -->
		</article><!-- #post-<?php the_ID(); ?> -->
		<?php
	}
endif;

/**
 * Displays post navigation.
 */
if ( ! function_exists( 'aqualuxe_the_post_navigation' ) ) :
	/**
	 * Displays post navigation.
	 */
	function aqualuxe_the_post_navigation() {
		if ( ! is_singular( 'post' ) ) {
			return;
		}

		the_post_navigation(
			array(
				'prev_text' => '<span class="nav-subtitle">' . esc_html__( 'Previous:', 'aqualuxe' ) . '</span> <span class="nav-title">%title</span>',
				'next_text' => '<span class="nav-subtitle">' . esc_html__( 'Next:', 'aqualuxe' ) . '</span> <span class="nav-title">%title</span>',
			)
		);
	}
endif;

/**
 * Displays comments.
 */
if ( ! function_exists( 'aqualuxe_comments' ) ) :
	/**
	 * Displays comments.
	 */
	function aqualuxe_comments() {
		if ( ! is_singular() ) {
			return;
		}

		// If comments are open or we have at least one comment, load up the comment template.
		if ( comments_open() || get_comments_number() ) {
			comments_template();
		}
	}
endif;

/**
 * Displays sidebar.
 */
if ( ! function_exists( 'aqualuxe_get_sidebar' ) ) :
	/**
	 * Displays sidebar.
	 */
	function aqualuxe_get_sidebar() {
		$sidebar_id = 'sidebar-1';

		// Blog sidebar.
		if ( is_home() || is_archive() || is_search() || is_singular( 'post' ) ) {
			$blog_sidebar = get_theme_mod( 'aqualuxe_blog_sidebar', 'right' );
			if ( 'none' === $blog_sidebar ) {
				return;
			}
		}

		// WooCommerce sidebar.
		if ( class_exists( 'WooCommerce' ) && ( is_shop() || is_product_category() || is_product_tag() || is_product() ) ) {
			$shop_sidebar = get_theme_mod( 'aqualuxe_shop_sidebar', 'right' );
			if ( 'none' === $shop_sidebar ) {
				return;
			}
			$sidebar_id = 'shop';
		}

		// Apply filters.
		$sidebar_id = apply_filters( 'aqualuxe_sidebar_id', $sidebar_id );

		// Display sidebar.
		if ( is_active_sidebar( $sidebar_id ) ) {
			get_sidebar( $sidebar_id );
		}
	}
endif;
add_action( 'aqualuxe_sidebar', 'aqualuxe_get_sidebar', 10 );

/**
 * Displays WooCommerce notice.
 */
if ( ! function_exists( 'aqualuxe_woocommerce_notice' ) ) :
	/**
	 * Displays WooCommerce notice.
	 */
	function aqualuxe_woocommerce_notice() {
		if ( class_exists( 'WooCommerce' ) ) {
			return;
		}

		$message = get_theme_mod( 'aqualuxe_fallback_message', esc_html__( 'Our shop is currently being updated. Please check back soon.', 'aqualuxe' ) );
		?>
		<div class="woocommerce-notice">
			<div class="woocommerce-notice-content">
				<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
					<circle cx="12" cy="12" r="10"></circle>
					<line x1="12" y1="8" x2="12" y2="12"></line>
					<line x1="12" y1="16" x2="12.01" y2="16"></line>
				</svg>
				<p><?php echo wp_kses_post( $message ); ?></p>
			</div>
		</div>
		<?php
	}
endif;