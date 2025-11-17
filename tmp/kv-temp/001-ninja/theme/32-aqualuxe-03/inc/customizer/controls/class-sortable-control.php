<?php
/**
 * AquaLuxe Theme Customizer - Sortable Control
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'AquaLuxe_Sortable_Control' ) && class_exists( 'WP_Customize_Control' ) ) {

	/**
	 * Sortable Control
	 */
	class AquaLuxe_Sortable_Control extends WP_Customize_Control {

		/**
		 * The type of control being rendered
		 *
		 * @var string
		 */
		public $type = 'aqualuxe-sortable';

		/**
		 * Enqueue control related scripts/styles.
		 *
		 * @return void
		 */
		public function enqueue() {
			wp_enqueue_script(
				'aqualuxe-sortable-control',
				get_template_directory_uri() . '/assets/js/customizer/sortable-control.js',
				array( 'jquery', 'jquery-ui-sortable', 'customize-base' ),
				AQUALUXE_VERSION,
				true
			);

			wp_enqueue_style(
				'aqualuxe-sortable-control',
				get_template_directory_uri() . '/assets/css/customizer/sortable-control.css',
				array(),
				AQUALUXE_VERSION
			);
		}

		/**
		 * Render the control in the customizer
		 */
		public function render_content() {
			$values = $this->value();
			$values = ! is_array( $values ) ? explode( ',', $values ) : $values;
			?>
			<div class="aqualuxe-sortable-control">
				<?php if ( ! empty( $this->label ) ) : ?>
					<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
				<?php endif; ?>

				<?php if ( ! empty( $this->description ) ) : ?>
					<span class="customize-control-description"><?php echo esc_html( $this->description ); ?></span>
				<?php endif; ?>

				<ul class="aqualuxe-sortable-list">
					<?php foreach ( $values as $value ) : ?>
						<?php if ( isset( $this->choices[ $value ] ) ) : ?>
							<li class="aqualuxe-sortable-item" data-value="<?php echo esc_attr( $value ); ?>">
								<span class="aqualuxe-sortable-title"><?php echo esc_html( $this->choices[ $value ] ); ?></span>
								<span class="aqualuxe-sortable-handle dashicons dashicons-menu"></span>
							</li>
						<?php endif; ?>
					<?php endforeach; ?>

					<?php
					// Add any remaining choices that weren't in the current value.
					foreach ( $this->choices as $value => $label ) :
						if ( ! in_array( $value, $values, true ) ) :
							?>
							<li class="aqualuxe-sortable-item" data-value="<?php echo esc_attr( $value ); ?>">
								<span class="aqualuxe-sortable-title"><?php echo esc_html( $label ); ?></span>
								<span class="aqualuxe-sortable-handle dashicons dashicons-menu"></span>
							</li>
							<?php
						endif;
					endforeach;
					?>
				</ul>

				<input type="hidden" 
					id="<?php echo esc_attr( $this->id ); ?>" 
					name="<?php echo esc_attr( $this->id ); ?>" 
					value="<?php echo esc_attr( implode( ',', $values ) ); ?>" 
					<?php $this->link(); ?> 
				/>
			</div>
			<script>
				jQuery(document).ready(function($) {
					$('.aqualuxe-sortable-list').sortable({
						axis: 'y',
						update: function() {
							var values = [];
							$(this).find('.aqualuxe-sortable-item').each(function() {
								values.push($(this).attr('data-value'));
							});
							$(this).closest('.aqualuxe-sortable-control').find('input').val(values.join(',')).trigger('change');
						}
					});
				});
			</script>
			<?php
		}
	}
}