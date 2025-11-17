<?php
/**
 * Template part for displaying project inquiry form
 *
 * @package AquaLuxe
 */

// Get project inquiry form options from theme customizer
$show_inquiry_form = get_theme_mod('aqualuxe_show_project_inquiry_form', true);
$inquiry_form_title = get_theme_mod('aqualuxe_project_inquiry_form_title', __('Interested in a Similar Project?', 'aqualuxe'));
$inquiry_form_subtitle = get_theme_mod('aqualuxe_project_inquiry_form_subtitle', __('Contact us to discuss your project requirements.', 'aqualuxe'));
$inquiry_form_shortcode = get_theme_mod('aqualuxe_project_inquiry_form_shortcode', '');

// Check if inquiry form should be displayed
if (!$show_inquiry_form) {
    return;
}
?>

<div class="project-inquiry-form">
    <div class="inquiry-form-header">
        <?php if (!empty($inquiry_form_title)) : ?>
            <h3 class="inquiry-form-title"><?php echo esc_html($inquiry_form_title); ?></h3>
        <?php endif; ?>
        
        <?php if (!empty($inquiry_form_subtitle)) : ?>
            <div class="inquiry-form-subtitle"><?php echo esc_html($inquiry_form_subtitle); ?></div>
        <?php endif; ?>
    </div>
    
    <div class="inquiry-form-content">
        <?php
        if (!empty($inquiry_form_shortcode)) {
            echo do_shortcode($inquiry_form_shortcode);
        } else {
            // Default form if no shortcode is provided
            ?>
            <form id="project-inquiry-form" class="inquiry-form" method="post">
                <div class="form-group">
                    <label for="inquiry-name"><?php echo esc_html__('Your Name', 'aqualuxe'); ?> <span class="required">*</span></label>
                    <input type="text" id="inquiry-name" name="inquiry-name" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="inquiry-email"><?php echo esc_html__('Your Email', 'aqualuxe'); ?> <span class="required">*</span></label>
                    <input type="email" id="inquiry-email" name="inquiry-email" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="inquiry-phone"><?php echo esc_html__('Phone Number', 'aqualuxe'); ?></label>
                    <input type="tel" id="inquiry-phone" name="inquiry-phone" class="form-control">
                </div>
                
                <div class="form-group">
                    <label for="inquiry-project-type"><?php echo esc_html__('Project Type', 'aqualuxe'); ?> <span class="required">*</span></label>
                    <select id="inquiry-project-type" name="inquiry-project-type" class="form-control" required>
                        <option value=""><?php echo esc_html__('Select Project Type', 'aqualuxe'); ?></option>
                        <option value="residential"><?php echo esc_html__('Residential', 'aqualuxe'); ?></option>
                        <option value="commercial"><?php echo esc_html__('Commercial', 'aqualuxe'); ?></option>
                        <option value="public"><?php echo esc_html__('Public Space', 'aqualuxe'); ?></option>
                        <option value="other"><?php echo esc_html__('Other', 'aqualuxe'); ?></option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="inquiry-budget"><?php echo esc_html__('Estimated Budget', 'aqualuxe'); ?></label>
                    <select id="inquiry-budget" name="inquiry-budget" class="form-control">
                        <option value=""><?php echo esc_html__('Select Budget Range', 'aqualuxe'); ?></option>
                        <option value="under-5000"><?php echo esc_html__('Under $5,000', 'aqualuxe'); ?></option>
                        <option value="5000-10000"><?php echo esc_html__('$5,000 - $10,000', 'aqualuxe'); ?></option>
                        <option value="10000-25000"><?php echo esc_html__('$10,000 - $25,000', 'aqualuxe'); ?></option>
                        <option value="25000-50000"><?php echo esc_html__('$25,000 - $50,000', 'aqualuxe'); ?></option>
                        <option value="over-50000"><?php echo esc_html__('Over $50,000', 'aqualuxe'); ?></option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="inquiry-message"><?php echo esc_html__('Project Details', 'aqualuxe'); ?> <span class="required">*</span></label>
                    <textarea id="inquiry-message" name="inquiry-message" class="form-control" rows="4" required></textarea>
                </div>
                
                <div class="form-group">
                    <input type="hidden" name="project_id" value="<?php echo get_the_ID(); ?>">
                    <input type="hidden" name="project_title" value="<?php echo esc_attr(get_the_title()); ?>">
                    <input type="hidden" name="action" value="aqualuxe_project_inquiry">
                    <input type="hidden" name="nonce" value="<?php echo wp_create_nonce('aqualuxe_project_inquiry_nonce'); ?>">
                    <button type="submit" class="btn btn-primary btn-block"><?php echo esc_html__('Submit Inquiry', 'aqualuxe'); ?></button>
                </div>
                
                <div class="form-response"></div>
            </form>
            <?php
        }
        ?>
    </div>
</div>