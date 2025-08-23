<?php
/**
 * Event Archive Template
 *
 * @package AquaLuxe\Modules\Events
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Get events and module from template args
$events = $args['events'] ?? [];
$module = $args['module'] ?? null;

if ( ! $module ) {
	return;
}
?>

<div class="aqualuxe-events">
	<div class="aqualuxe-events__filters">
		<form class="aqualuxe-events__filter-form" method="get">
			<div class="aqualuxe-events__filter-row">
				<div class="aqualuxe-events__filter-field">
					<label for="aqualuxe-events-category" class="aqualuxe-events__filter-label"><?php esc_html_e( 'Category', 'aqualuxe' ); ?></label>
					<select id="aqualuxe-events-category" name="category" class="aqualuxe-events__filter-select">
						<option value=""><?php esc_html_e( 'All Categories', 'aqualuxe' ); ?></option>
						<?php
						$categories = get_terms( [
							'taxonomy' => 'aqualuxe_event_category',
							'hide_empty' => true,
						] );

						if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) {
							foreach ( $categories as $category ) {
								echo '<option value="' . esc_attr( $category->slug ) . '" ' . selected( isset( $_GET['category'] ) ? sanitize_text_field( $_GET['category'] ) : '', $category->slug, false ) . '>' . esc_html( $category->name ) . '</option>';
							}
						}
						?>
					</select>
				</div>

				<div class="aqualuxe-events__filter-field">
					<label for="aqualuxe-events-date" class="aqualuxe-events__filter-label"><?php esc_html_e( 'Date', 'aqualuxe' ); ?></label>
					<select id="aqualuxe-events-date" name="date" class="aqualuxe-events__filter-select">
						<option value=""><?php esc_html_e( 'Any Date', 'aqualuxe' ); ?></option>
						<option value="today" <?php selected( isset( $_GET['date'] ) ? sanitize_text_field( $_GET['date'] ) : '', 'today' ); ?>><?php esc_html_e( 'Today', 'aqualuxe' ); ?></option>
						<option value="tomorrow" <?php selected( isset( $_GET['date'] ) ? sanitize_text_field( $_GET['date'] ) : '', 'tomorrow' ); ?>><?php esc_html_e( 'Tomorrow', 'aqualuxe' ); ?></option>
						<option value="this-week" <?php selected( isset( $_GET['date'] ) ? sanitize_text_field( $_GET['date'] ) : '', 'this-week' ); ?>><?php esc_html_e( 'This Week', 'aqualuxe' ); ?></option>
						<option value="this-month" <?php selected( isset( $_GET['date'] ) ? sanitize_text_field( $_GET['date'] ) : '', 'this-month' ); ?>><?php esc_html_e( 'This Month', 'aqualuxe' ); ?></option>
					</select>
				</div>

				<div class="aqualuxe-events__filter-field">
					<label for="aqualuxe-events-search" class="aqualuxe-events__filter-label"><?php esc_html_e( 'Search', 'aqualuxe' ); ?></label>
					<input type="text" id="aqualuxe-events-search" name="s" class="aqualuxe-events__filter-input" value="<?php echo esc_attr( isset( $_GET['s'] ) ? sanitize_text_field( $_GET['s'] ) : '' ); ?>" placeholder="<?php esc_attr_e( 'Search events...', 'aqualuxe' ); ?>">
				</div>

				<div class="aqualuxe-events__filter-actions">
					<button type="submit" class="aqualuxe-events__filter-button"><?php esc_html_e( 'Filter', 'aqualuxe' ); ?></button>
					<a href="<?php echo esc_url( get_post_type_archive_link( 'aqualuxe_event' ) ); ?>" class="aqualuxe-events__filter-reset"><?php esc_html_e( 'Reset', 'aqualuxe' ); ?></a>
				</div>
			</div>

			<div class="aqualuxe-events__filter-row">
				<div class="aqualuxe-events__filter-toggle">
					<label class="aqualuxe-events__filter-checkbox-label">
						<input type="checkbox" name="featured" value="1" class="aqualuxe-events__filter-checkbox" <?php checked( isset( $_GET['featured'] ) && $_GET['featured'] === '1' ); ?>>
						<?php esc_html_e( 'Featured Events Only', 'aqualuxe' ); ?>
					</label>
				</div>

				<div class="aqualuxe-events__filter-view">
					<span class="aqualuxe-events__filter-view-label"><?php esc_html_e( 'View:', 'aqualuxe' ); ?></span>
					<a href="<?php echo esc_url( add_query_arg( 'view', 'list', remove_query_arg( 'paged' ) ) ); ?>" class="aqualuxe-events__filter-view-button <?php echo ( ! isset( $_GET['view'] ) || $_GET['view'] === 'list' ) ? 'aqualuxe-events__filter-view-button--active' : ''; ?>">
						<span class="dashicons dashicons-list-view"></span>
						<span class="aqualuxe-events__filter-view-text"><?php esc_html_e( 'List', 'aqualuxe' ); ?></span>
					</a>
					<a href="<?php echo esc_url( add_query_arg( 'view', 'grid', remove_query_arg( 'paged' ) ) ); ?>" class="aqualuxe-events__filter-view-button <?php echo ( isset( $_GET['view'] ) && $_GET['view'] === 'grid' ) ? 'aqualuxe-events__filter-view-button--active' : ''; ?>">
						<span class="dashicons dashicons-grid-view"></span>
						<span class="aqualuxe-events__filter-view-text"><?php esc_html_e( 'Grid', 'aqualuxe' ); ?></span>
					</a>
					<a href="<?php echo esc_url( add_query_arg( 'view', 'calendar', remove_query_arg( 'paged' ) ) ); ?>" class="aqualuxe-events__filter-view-button <?php echo ( isset( $_GET['view'] ) && $_GET['view'] === 'calendar' ) ? 'aqualuxe-events__filter-view-button--active' : ''; ?>">
						<span class="dashicons dashicons-calendar"></span>
						<span class="aqualuxe-events__filter-view-text"><?php esc_html_e( 'Calendar', 'aqualuxe' ); ?></span>
					</a>
				</div>
			</div>
		</form>
	</div>

	<?php if ( isset( $_GET['view'] ) && $_GET['view'] === 'calendar' ) : ?>
		<div class="aqualuxe-events__calendar">
			<?php
			$calendar = new \AquaLuxe\Modules\Events\Calendar();
			$calendar_args = [
				'show_title' => false,
				'months' => 1,
				'start_date' => isset( $_GET['month'] ) ? sanitize_text_field( $_GET['month'] ) : date( 'Y-m-01' ),
				'category' => isset( $_GET['category'] ) ? sanitize_text_field( $_GET['category'] ) : '',
				'featured' => isset( $_GET['featured'] ) && $_GET['featured'] === '1',
				'past_events' => false,
				'compact' => false,
			];
			$calendar->render( $calendar_args );
			?>

			<div class="aqualuxe-events__calendar-navigation">
				<?php
				$prev_month = date( 'Y-m-01', strtotime( $calendar_args['start_date'] . ' -1 month' ) );
				$next_month = date( 'Y-m-01', strtotime( $calendar_args['start_date'] . ' +1 month' ) );
				$current_month = date( 'Y-m-01' );
				?>
				<a href="<?php echo esc_url( add_query_arg( 'month', $prev_month ) ); ?>" class="aqualuxe-events__calendar-nav-button aqualuxe-events__calendar-nav-button--prev">
					<span class="dashicons dashicons-arrow-left-alt2"></span>
					<span class="aqualuxe-events__calendar-nav-text"><?php echo esc_html( date_i18n( 'F Y', strtotime( $prev_month ) ) ); ?></span>
				</a>

				<a href="<?php echo esc_url( add_query_arg( 'month', $current_month ) ); ?>" class="aqualuxe-events__calendar-nav-button aqualuxe-events__calendar-nav-button--current">
					<span class="aqualuxe-events__calendar-nav-text"><?php esc_html_e( 'Current Month', 'aqualuxe' ); ?></span>
				</a>

				<a href="<?php echo esc_url( add_query_arg( 'month', $next_month ) ); ?>" class="aqualuxe-events__calendar-nav-button aqualuxe-events__calendar-nav-button--next">
					<span class="aqualuxe-events__calendar-nav-text"><?php echo esc_html( date_i18n( 'F Y', strtotime( $next_month ) ) ); ?></span>
					<span class="dashicons dashicons-arrow-right-alt2"></span>
				</a>
			</div>
		</div>
	<?php else : ?>
		<?php if ( ! empty( $events ) ) : ?>
			<div class="aqualuxe-events__list <?php echo ( isset( $_GET['view'] ) && $_GET['view'] === 'grid' ) ? 'aqualuxe-events__list--grid' : 'aqualuxe-events__list--list'; ?>">
				<?php foreach ( $events as $event ) : ?>
					<div class="aqualuxe-events__item <?php echo $event->get_featured() ? 'aqualuxe-events__item--featured' : ''; ?>">
						<a href="<?php echo esc_url( $event->get_permalink() ); ?>" class="aqualuxe-events__item-link">
							<?php if ( $event->get_image_url( 'medium' ) ) : ?>
								<div class="aqualuxe-events__item-image">
									<img src="<?php echo esc_url( $event->get_image_url( 'medium' ) ); ?>" alt="<?php echo esc_attr( $event->get_title() ); ?>">
									
									<?php if ( $event->get_featured() ) : ?>
										<span class="aqualuxe-events__item-featured-badge"><?php esc_html_e( 'Featured', 'aqualuxe' ); ?></span>
									<?php endif; ?>
								</div>
							<?php endif; ?>
							
							<div class="aqualuxe-events__item-content">
								<h3 class="aqualuxe-events__item-title"><?php echo esc_html( $event->get_title() ); ?></h3>
								
								<div class="aqualuxe-events__item-meta">
									<div class="aqualuxe-events__item-date">
										<span class="aqualuxe-events__item-icon dashicons dashicons-calendar-alt"></span>
										<span class="aqualuxe-events__item-text">
											<?php if ( $event->get_start_date() === $event->get_end_date() ) : ?>
												<?php echo esc_html( $event->get_formatted_start_date() ); ?>
											<?php else : ?>
												<?php echo esc_html( $event->get_formatted_start_date() ); ?> - <?php echo esc_html( $event->get_formatted_end_date() ); ?>
											<?php endif; ?>
										</span>
									</div>
									
									<div class="aqualuxe-events__item-time">
										<span class="aqualuxe-events__item-icon dashicons dashicons-clock"></span>
										<span class="aqualuxe-events__item-text">
											<?php echo esc_html( $event->get_formatted_start_time() ); ?> - <?php echo esc_html( $event->get_formatted_end_time() ); ?>
										</span>
									</div>
									
									<?php $venue = $event->get_venue_data(); ?>
									<?php if ( ! empty( $venue['name'] ) ) : ?>
										<div class="aqualuxe-events__item-venue">
											<span class="aqualuxe-events__item-icon dashicons dashicons-location"></span>
											<span class="aqualuxe-events__item-text">
												<?php echo esc_html( $venue['name'] ); ?>
											</span>
										</div>
									<?php endif; ?>
									
									<?php if ( $event->get_cost() > 0 ) : ?>
										<div class="aqualuxe-events__item-cost">
											<span class="aqualuxe-events__item-icon dashicons dashicons-money-alt"></span>
											<span class="aqualuxe-events__item-text">
												<?php echo esc_html( $event->get_formatted_cost() ); ?>
											</span>
										</div>
									<?php else : ?>
										<div class="aqualuxe-events__item-cost aqualuxe-events__item-cost--free">
											<span class="aqualuxe-events__item-icon dashicons dashicons-money-alt"></span>
											<span class="aqualuxe-events__item-text">
												<?php esc_html_e( 'Free', 'aqualuxe' ); ?>
											</span>
										</div>
									<?php endif; ?>
								</div>
								
								<?php if ( isset( $_GET['view'] ) && $_GET['view'] !== 'grid' ) : ?>
									<div class="aqualuxe-events__item-excerpt">
										<?php echo wp_kses_post( wpautop( $event->get_excerpt() ) ); ?>
									</div>
								<?php endif; ?>
								
								<div class="aqualuxe-events__item-status">
									<?php if ( $event->is_registration_open() ) : ?>
										<span class="aqualuxe-events__item-status-badge aqualuxe-events__item-status-badge--open">
											<?php esc_html_e( 'Registration Open', 'aqualuxe' ); ?>
										</span>
									<?php else : ?>
										<span class="aqualuxe-events__item-status-badge aqualuxe-events__item-status-badge--closed">
											<?php esc_html_e( 'Registration Closed', 'aqualuxe' ); ?>
										</span>
									<?php endif; ?>
									
									<?php
									$available = $event->get_available_capacity();
									if ( $available === 0 ) :
									?>
										<span class="aqualuxe-events__item-status-badge aqualuxe-events__item-status-badge--sold-out">
											<?php esc_html_e( 'Sold Out', 'aqualuxe' ); ?>
										</span>
									<?php endif; ?>
								</div>
							</div>
						</a>
					</div>
				<?php endforeach; ?>
			</div>
			
			<?php
			// Pagination
			$paged = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
			$total_pages = isset( $args['total_pages'] ) ? $args['total_pages'] : 1;
			
			if ( $total_pages > 1 ) :
			?>
				<div class="aqualuxe-events__pagination">
					<?php
					echo paginate_links( [
						'base' => str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ),
						'format' => '?paged=%#%',
						'current' => max( 1, $paged ),
						'total' => $total_pages,
						'prev_text' => '<span class="dashicons dashicons-arrow-left-alt2"></span> ' . __( 'Previous', 'aqualuxe' ),
						'next_text' => __( 'Next', 'aqualuxe' ) . ' <span class="dashicons dashicons-arrow-right-alt2"></span>',
					] );
					?>
				</div>
			<?php endif; ?>
		<?php else : ?>
			<div class="aqualuxe-events__empty">
				<div class="aqualuxe-events__empty-icon">
					<span class="dashicons dashicons-calendar-alt"></span>
				</div>
				<h3 class="aqualuxe-events__empty-title"><?php esc_html_e( 'No Events Found', 'aqualuxe' ); ?></h3>
				<p class="aqualuxe-events__empty-text"><?php esc_html_e( 'There are no events matching your search criteria. Please try different filters or check back later.', 'aqualuxe' ); ?></p>
				<a href="<?php echo esc_url( get_post_type_archive_link( 'aqualuxe_event' ) ); ?>" class="aqualuxe-events__empty-button"><?php esc_html_e( 'View All Events', 'aqualuxe' ); ?></a>
			</div>
		<?php endif; ?>
	<?php endif; ?>
</div>