<?php
use Aqualuxe\Aqualuxe;
/** @var \Aqualuxe\Core\Services\OptionsService $options_service */
$options_service = Aqualuxe::get_container()->get( 'options' );
$footer_copyright = $options_service->get( 'footer_copyright', sprintf( 'Copyright &copy; %s %s', date( 'Y' ), get_bloginfo( 'name' ) ) );
?>
	<footer id="colophon" class="site-footer bg-gray-800 text-gray-300 mt-12">
		<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
			<div class="site-info text-center">
				<?php echo wp_kses_post( $footer_copyright ); ?>
			</div><!-- .site-info -->
		</div>
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
