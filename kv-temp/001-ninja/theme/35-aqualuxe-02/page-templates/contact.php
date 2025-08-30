<?php
/**
 * Template Name: Contact Page
 *
 * A template for contact pages with a built-in contact form.
 *
 * @package AquaLuxe
 */

get_header();

// Get contact page settings
$show_map = get_theme_mod( 'aqualuxe_contact_show_map', true );
$map_address = get_theme_mod( 'aqualuxe_contact_map_address', '' );
$map_zoom = get_theme_mod( 'aqualuxe_contact_map_zoom', 15 );
$map_height = get_theme_mod( 'aqualuxe_contact_map_height', 400 );
$contact_info_layout = get_theme_mod( 'aqualuxe_contact_info_layout', 'side' ); // side or top
$contact_form_title = get_theme_mod( 'aqualuxe_contact_form_title', __( 'Send us a message', 'aqualuxe' ) );
$contact_info_title = get_theme_mod( 'aqualuxe_contact_info_title', __( 'Contact Information', 'aqualuxe' ) );
$contact_address = get_theme_mod( 'aqualuxe_contact_address', '' );
$contact_phone = get_theme_mod( 'aqualuxe_contact_phone', '' );
$contact_email = get_theme_mod( 'aqualuxe_contact_email', '' );
$contact_hours = get_theme_mod( 'aqualuxe_contact_hours', '' );
$show_social = get_theme_mod( 'aqualuxe_contact_show_social', true );

// Process contact form submission
$form_submitted = false;
$form_errors = array();
$form_success = false;

if ( isset( $_POST['aqualuxe_contact_submit'] ) && wp_verify_nonce( $_POST['aqualuxe_contact_nonce'], 'aqualuxe_contact_form' ) ) {
    $form_submitted = true;
    
    // Get form data
    $name = isset( $_POST['aqualuxe_contact_name'] ) ? sanitize_text_field( $_POST['aqualuxe_contact_name'] ) : '';
    $email = isset( $_POST['aqualuxe_contact_email'] ) ? sanitize_email( $_POST['aqualuxe_contact_email'] ) : '';
    $subject = isset( $_POST['aqualuxe_contact_subject'] ) ? sanitize_text_field( $_POST['aqualuxe_contact_subject'] ) : '';
    $message = isset( $_POST['aqualuxe_contact_message'] ) ? sanitize_textarea_field( $_POST['aqualuxe_contact_message'] ) : '';
    
    // Validate form data
    if ( empty( $name ) ) {
        $form_errors['name'] = __( 'Please enter your name.', 'aqualuxe' );
    }
    
    if ( empty( $email ) ) {
        $form_errors['email'] = __( 'Please enter your email address.', 'aqualuxe' );
    } elseif ( ! is_email( $email ) ) {
        $form_errors['email'] = __( 'Please enter a valid email address.', 'aqualuxe' );
    }
    
    if ( empty( $subject ) ) {
        $form_errors['subject'] = __( 'Please enter a subject.', 'aqualuxe' );
    }
    
    if ( empty( $message ) ) {
        $form_errors['message'] = __( 'Please enter your message.', 'aqualuxe' );
    }
    
    // If no errors, send email
    if ( empty( $form_errors ) ) {
        $to = get_option( 'admin_email' );
        $site_name = get_bloginfo( 'name' );
        $headers = array(
            'Content-Type: text/html; charset=UTF-8',
            'From: ' . $site_name . ' <' . $to . '>',
            'Reply-To: ' . $name . ' <' . $email . '>',
        );
        
        $email_subject = sprintf( '[%s] %s', $site_name, $subject );
        
        $email_message = '<p><strong>' . __( 'Name', 'aqualuxe' ) . ':</strong> ' . $name . '</p>';
        $email_message .= '<p><strong>' . __( 'Email', 'aqualuxe' ) . ':</strong> ' . $email . '</p>';
        $email_message .= '<p><strong>' . __( 'Subject', 'aqualuxe' ) . ':</strong> ' . $subject . '</p>';
        $email_message .= '<p><strong>' . __( 'Message', 'aqualuxe' ) . ':</strong></p>';
        $email_message .= '<p>' . nl2br( $message ) . '</p>';
        
        $sent = wp_mail( $to, $email_subject, $email_message, $headers );
        
        if ( $sent ) {
            $form_success = true;
        } else {
            $form_errors['general'] = __( 'There was an error sending your message. Please try again later.', 'aqualuxe' );
        }
    }
}
?>

<div class="aqualuxe-container">
	<div id="primary" class="content-area">
		<main id="main" class="site-main">

			<?php
			while ( have_posts() ) :
				the_post();

				// Display page content if any
				if ( '' !== get_the_content() ) :
					?>
					<div class="aqualuxe-contact-intro">
						<?php the_content(); ?>
					</div>
					<?php
				endif;

			endwhile; // End of the loop.
			?>

			<?php if ( $show_map && ! empty( $map_address ) ) : ?>
				<div class="aqualuxe-contact-map" style="height: <?php echo esc_attr( $map_height ); ?>px;">
					<iframe
						width="100%"
						height="100%"
						frameborder="0"
						style="border:0"
						src="https://www.google.com/maps/embed/v1/place?key=<?php echo esc_attr( get_theme_mod( 'aqualuxe_google_maps_api_key', '' ) ); ?>&q=<?php echo esc_attr( urlencode( $map_address ) ); ?>&zoom=<?php echo esc_attr( $map_zoom ); ?>"
						allowfullscreen
					></iframe>
				</div>
			<?php endif; ?>

			<div class="aqualuxe-contact-container aqualuxe-contact-layout-<?php echo esc_attr( $contact_info_layout ); ?>">
				<div class="aqualuxe-contact-form-container">
					<?php if ( ! empty( $contact_form_title ) ) : ?>
						<h2 class="aqualuxe-contact-form-title"><?php echo esc_html( $contact_form_title ); ?></h2>
					<?php endif; ?>

					<?php if ( $form_submitted && $form_success ) : ?>
						<div class="aqualuxe-contact-form-success">
							<p><?php esc_html_e( 'Thank you for your message! We will get back to you as soon as possible.', 'aqualuxe' ); ?></p>
						</div>
					<?php else : ?>
						<?php if ( isset( $form_errors['general'] ) ) : ?>
							<div class="aqualuxe-contact-form-error">
								<p><?php echo esc_html( $form_errors['general'] ); ?></p>
							</div>
						<?php endif; ?>

						<form class="aqualuxe-contact-form" method="post" action="#aqualuxe-contact-form">
							<?php wp_nonce_field( 'aqualuxe_contact_form', 'aqualuxe_contact_nonce' ); ?>
							
							<div class="aqualuxe-contact-form-row">
								<div class="aqualuxe-contact-form-field <?php echo isset( $form_errors['name'] ) ? 'aqualuxe-has-error' : ''; ?>">
									<label for="aqualuxe-contact-name"><?php esc_html_e( 'Name', 'aqualuxe' ); ?> <span class="required">*</span></label>
									<input type="text" id="aqualuxe-contact-name" name="aqualuxe_contact_name" value="<?php echo isset( $_POST['aqualuxe_contact_name'] ) ? esc_attr( $_POST['aqualuxe_contact_name'] ) : ''; ?>" required>
									<?php if ( isset( $form_errors['name'] ) ) : ?>
										<span class="aqualuxe-contact-form-error-message"><?php echo esc_html( $form_errors['name'] ); ?></span>
									<?php endif; ?>
								</div>
								
								<div class="aqualuxe-contact-form-field <?php echo isset( $form_errors['email'] ) ? 'aqualuxe-has-error' : ''; ?>">
									<label for="aqualuxe-contact-email"><?php esc_html_e( 'Email', 'aqualuxe' ); ?> <span class="required">*</span></label>
									<input type="email" id="aqualuxe-contact-email" name="aqualuxe_contact_email" value="<?php echo isset( $_POST['aqualuxe_contact_email'] ) ? esc_attr( $_POST['aqualuxe_contact_email'] ) : ''; ?>" required>
									<?php if ( isset( $form_errors['email'] ) ) : ?>
										<span class="aqualuxe-contact-form-error-message"><?php echo esc_html( $form_errors['email'] ); ?></span>
									<?php endif; ?>
								</div>
							</div>
							
							<div class="aqualuxe-contact-form-field <?php echo isset( $form_errors['subject'] ) ? 'aqualuxe-has-error' : ''; ?>">
								<label for="aqualuxe-contact-subject"><?php esc_html_e( 'Subject', 'aqualuxe' ); ?> <span class="required">*</span></label>
								<input type="text" id="aqualuxe-contact-subject" name="aqualuxe_contact_subject" value="<?php echo isset( $_POST['aqualuxe_contact_subject'] ) ? esc_attr( $_POST['aqualuxe_contact_subject'] ) : ''; ?>" required>
								<?php if ( isset( $form_errors['subject'] ) ) : ?>
									<span class="aqualuxe-contact-form-error-message"><?php echo esc_html( $form_errors['subject'] ); ?></span>
								<?php endif; ?>
							</div>
							
							<div class="aqualuxe-contact-form-field <?php echo isset( $form_errors['message'] ) ? 'aqualuxe-has-error' : ''; ?>">
								<label for="aqualuxe-contact-message"><?php esc_html_e( 'Message', 'aqualuxe' ); ?> <span class="required">*</span></label>
								<textarea id="aqualuxe-contact-message" name="aqualuxe_contact_message" rows="6" required><?php echo isset( $_POST['aqualuxe_contact_message'] ) ? esc_textarea( $_POST['aqualuxe_contact_message'] ) : ''; ?></textarea>
								<?php if ( isset( $form_errors['message'] ) ) : ?>
									<span class="aqualuxe-contact-form-error-message"><?php echo esc_html( $form_errors['message'] ); ?></span>
								<?php endif; ?>
							</div>
							
							<div class="aqualuxe-contact-form-submit">
								<button type="submit" name="aqualuxe_contact_submit" class="aqualuxe-button"><?php esc_html_e( 'Send Message', 'aqualuxe' ); ?></button>
							</div>
						</form>
					<?php endif; ?>
				</div>

				<div class="aqualuxe-contact-info-container">
					<?php if ( ! empty( $contact_info_title ) ) : ?>
						<h2 class="aqualuxe-contact-info-title"><?php echo esc_html( $contact_info_title ); ?></h2>
					<?php endif; ?>

					<div class="aqualuxe-contact-info">
						<?php if ( ! empty( $contact_address ) ) : ?>
							<div class="aqualuxe-contact-info-item aqualuxe-contact-address">
								<div class="aqualuxe-contact-info-icon">
									<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
								</div>
								<div class="aqualuxe-contact-info-content">
									<h3><?php esc_html_e( 'Address', 'aqualuxe' ); ?></h3>
									<p><?php echo nl2br( esc_html( $contact_address ) ); ?></p>
								</div>
							</div>
						<?php endif; ?>

						<?php if ( ! empty( $contact_phone ) ) : ?>
							<div class="aqualuxe-contact-info-item aqualuxe-contact-phone">
								<div class="aqualuxe-contact-info-icon">
									<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
								</div>
								<div class="aqualuxe-contact-info-content">
									<h3><?php esc_html_e( 'Phone', 'aqualuxe' ); ?></h3>
									<p><a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $contact_phone ) ); ?>"><?php echo esc_html( $contact_phone ); ?></a></p>
								</div>
							</div>
						<?php endif; ?>

						<?php if ( ! empty( $contact_email ) ) : ?>
							<div class="aqualuxe-contact-info-item aqualuxe-contact-email">
								<div class="aqualuxe-contact-info-icon">
									<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
								</div>
								<div class="aqualuxe-contact-info-content">
									<h3><?php esc_html_e( 'Email', 'aqualuxe' ); ?></h3>
									<p><a href="mailto:<?php echo esc_attr( $contact_email ); ?>"><?php echo esc_html( $contact_email ); ?></a></p>
								</div>
							</div>
						<?php endif; ?>

						<?php if ( ! empty( $contact_hours ) ) : ?>
							<div class="aqualuxe-contact-info-item aqualuxe-contact-hours">
								<div class="aqualuxe-contact-info-icon">
									<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
								</div>
								<div class="aqualuxe-contact-info-content">
									<h3><?php esc_html_e( 'Business Hours', 'aqualuxe' ); ?></h3>
									<p><?php echo nl2br( esc_html( $contact_hours ) ); ?></p>
								</div>
							</div>
						<?php endif; ?>
					</div>

					<?php if ( $show_social ) : ?>
						<div class="aqualuxe-contact-social">
							<h3><?php esc_html_e( 'Follow Us', 'aqualuxe' ); ?></h3>
							<?php
							// Display social icons
							if ( function_exists( 'aqualuxe_social_icons' ) ) {
								aqualuxe_social_icons();
							}
							?>
						</div>
					<?php endif; ?>
				</div>
			</div>

		</main><!-- #main -->
	</div><!-- #primary -->
</div><!-- .aqualuxe-container -->

<?php
get_footer();