<?php
/**
 * Template part for displaying team member contact form
 *
 * @package AquaLuxe
 */

// Get team contact form options from theme customizer
$show_contact_form = get_theme_mod('aqualuxe_show_team_contact_form', true);
$contact_form_title = get_theme_mod('aqualuxe_team_contact_form_title', __('Contact This Team Member', 'aqualuxe'));
$contact_form_subtitle = get_theme_mod('aqualuxe_team_contact_form_subtitle', __('Have a question for this team member? Fill out the form below.', 'aqualuxe'));
$contact_form_shortcode = get_theme_mod('aqualuxe_team_contact_form_shortcode', '');

// Check if contact form should be displayed
if (!$show_contact_form) {
    return;
}
?>

<div class="team-contact-form">
    <div class="contact-form-header">
        <?php if (!empty($contact_form_title)) : ?>
            <h3 class="contact-form-title"><?php echo esc_html($contact_form_title); ?></h3>
        <?php endif; ?>
        
        <?php if (!empty($contact_form_subtitle)) : ?>
            <div class="contact-form-subtitle"><?php echo esc_html($contact_form_subtitle); ?></div>
        <?php endif; ?>
    </div>
    
    <div class="contact-form-content">
        <?php
        if (!empty($contact_form_shortcode)) {
            echo do_shortcode($contact_form_shortcode);
        } else {
            // Default form if no shortcode is provided
            ?>
            <form id="team-contact-form" class="contact-form" method="post">
                <div class="form-group">
                    <label for="contact-name"><?php echo esc_html__('Your Name', 'aqualuxe'); ?> <span class="required">*</span></label>
                    <input type="text" id="contact-name" name="contact-name" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="contact-email"><?php echo esc_html__('Your Email', 'aqualuxe'); ?> <span class="required">*</span></label>
                    <input type="email" id="contact-email" name="contact-email" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="contact-subject"><?php echo esc_html__('Subject', 'aqualuxe'); ?> <span class="required">*</span></label>
                    <input type="text" id="contact-subject" name="contact-subject" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="contact-message"><?php echo esc_html__('Your Message', 'aqualuxe'); ?> <span class="required">*</span></label>
                    <textarea id="contact-message" name="contact-message" class="form-control" rows="4" required></textarea>
                </div>
                
                <div class="form-group">
                    <input type="hidden" name="team_id" value="<?php echo get_the_ID(); ?>">
                    <input type="hidden" name="team_member_name" value="<?php echo esc_attr(get_the_title()); ?>">
                    <input type="hidden" name="action" value="aqualuxe_team_contact">
                    <input type="hidden" name="nonce" value="<?php echo wp_create_nonce('aqualuxe_team_contact_nonce'); ?>">
                    <button type="submit" class="btn btn-primary btn-block"><?php echo esc_html__('Send Message', 'aqualuxe'); ?></button>
                </div>
                
                <div class="form-response"></div>
            </form>
            <?php
        }
        ?>
    </div>
</div>