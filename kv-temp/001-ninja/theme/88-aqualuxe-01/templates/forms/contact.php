<?php
/**
 * Progressive enhancement contact form.
 * Submits via REST (JS) or admin-ajax (no-JS fallback).
 *
 * @package Aqualuxe
 */
if (!defined('ABSPATH')) { exit; }
$ajax_url = esc_url(admin_url('admin-ajax.php'));
$nonce    = wp_create_nonce('aqualuxe_contact');
?>
<section class="contact-form-section">
	<h2><?php esc_html_e('Contact Us', 'aqualuxe'); ?></h2>
	<form id="aqualuxe-contact" class="contact-form" method="post" action="<?php echo $ajax_url; ?>" novalidate>
		<input type="hidden" name="action" value="aqualuxe_contact">
		<input type="hidden" name="_wpnonce" value="<?php echo esc_attr($nonce); ?>">
		<p>
			<label for="cf-name"><?php esc_html_e('Name', 'aqualuxe'); ?></label>
			<input id="cf-name" name="name" type="text" required autocomplete="name">
		</p>
		<p>
			<label for="cf-email"><?php esc_html_e('Email', 'aqualuxe'); ?></label>
			<input id="cf-email" name="email" type="email" required autocomplete="email">
		</p>
		<p>
			<label for="cf-message"><?php esc_html_e('Message', 'aqualuxe'); ?></label>
			<textarea id="cf-message" name="message" rows="5" required></textarea>
		</p>
		<p>
			<button type="submit" class="button">
				<span class="js-only" aria-hidden="true">📨</span> <?php esc_html_e('Send', 'aqualuxe'); ?>
			</button>
		</p>
		<div class="form-feedback" role="status" aria-live="polite"></div>
	</form>
</section>
