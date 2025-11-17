<?php
get_header();
?>
<main id="primary" class="site-main container mx-auto px-4 py-8">
	<article <?php post_class('prose max-w-3xl mx-auto'); ?>>
		<header>
			<h1 class="text-3xl font-bold mb-2"><?php the_title(); ?></h1>
			<p class="opacity-70 text-sm"><?php echo get_the_date(); ?></p>
		</header>
		<?php if ( has_post_thumbnail() ) : ?>
			<div class="my-4"><?php the_post_thumbnail('large', ['class'=>'rounded']); ?></div>
		<?php endif; ?>
		<div class="mt-6"><?php the_content(); ?></div>
		<hr class="my-6"/>
		<div class="grid md:grid-cols-2 gap-4">
			<div>
				<strong><?php esc_html_e('Price','aqualuxe'); ?>:</strong>
				<?php echo esc_html( get_post_meta(get_the_ID(),'_alx_price', true) ?: __('Contact for price','aqualuxe') ); ?>
			</div>
			<div>
				<strong><?php esc_html_e('Contact','aqualuxe'); ?>:</strong>
				<?php echo esc_html( get_post_meta(get_the_ID(),'_alx_contact', true) ?: get_bloginfo('admin_email') ); ?>
			</div>
		</div>
	</article>
</main>
<?php get_footer(); ?>
