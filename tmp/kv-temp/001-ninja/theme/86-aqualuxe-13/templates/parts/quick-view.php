<?php
/**
 * Quick view template part
 *
 * @package AquaLuxe
 * @var array $context
 */

if ( ! isset( $context ) ) {
	$context = array();
}
?>
<div class="qv p-6">
	<?php if ( ( $context['type'] ?? '' ) === 'product' && ! empty( $context['product'] ) ) : ?>
		<?php $p = $context['product']; ?>
    <div class="text-xl font-semibold mb-2"><?php echo esc_html($p->get_name()); ?></div>
    <div class="mb-4"><?php echo wp_kses_post($p->get_price_html()); ?></div>
    <div class="mb-4"><?php echo wp_kses_post($p->get_short_description()); ?></div>
    <a class="btn btn-primary" href="<?php echo esc_url(get_permalink($p->get_id())); ?>"><?php esc_html_e('View details', 'aqualuxe'); ?></a>
  <?php elseif (($context['type'] ?? '') === 'post' && !empty($context['post'])): $post = $context['post']; ?>
    <div class="text-xl font-semibold mb-2"><?php echo esc_html(get_the_title($post)); ?></div>
    <div class="mb-4"><?php echo wp_kses_post(wp_trim_words($post->post_content, 40)); ?></div>
    <a class="btn btn-primary" href="<?php echo esc_url(get_permalink($post)); ?>"><?php esc_html_e('Read more', 'aqualuxe'); ?></a>
  <?php else: ?>
    <div class="opacity-80"><?php esc_html_e('Preview unavailable.', 'aqualuxe'); ?></div>
  <?php endif; ?>
</div>
