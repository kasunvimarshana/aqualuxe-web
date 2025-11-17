<?php if ( ! defined( 'ABSPATH' ) ) { exit; }
$links = paginate_links( [ 'type' => 'array' ] );
if ( $links ) : ?>
<nav class="pagination py-6" role="navigation" aria-label="<?php esc_attr_e( 'Posts navigation', 'aqualuxe' ); ?>">
	<ul class="flex gap-2 justify-center">
		<?php foreach ( $links as $link ) : ?>
			<li class="px-3 py-1 border rounded" ><?php echo wp_kses_post( $link ); ?></li>
		<?php endforeach; ?>
	</ul>
</nav>
<?php endif; ?>
