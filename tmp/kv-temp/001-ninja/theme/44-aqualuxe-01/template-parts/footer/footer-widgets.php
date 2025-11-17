<?php
/**
 * Template part for displaying the footer widgets
 *
 * @package AquaLuxe
 */

// Get footer options from theme customizer
$footer_columns = get_theme_mod('aqualuxe_footer_columns', 4);

// Check if any footer widget area has widgets
$has_widgets = false;
for ($i = 1; $i <= $footer_columns; $i++) {
    if (is_active_sidebar('footer-' . $i)) {
        $has_widgets = true;
        break;
    }
}

// Return if no widgets are active
if (!$has_widgets) {
    return;
}

// Column classes based on number of columns
$column_class = 'col-lg-3 col-md-6';
switch ($footer_columns) {
    case 1:
        $column_class = 'col-lg-12';
        break;
    case 2:
        $column_class = 'col-lg-6 col-md-6';
        break;
    case 3:
        $column_class = 'col-lg-4 col-md-6';
        break;
    case 4:
        $column_class = 'col-lg-3 col-md-6';
        break;
}
?>

<div class="footer-widgets">
    <div class="container">
        <div class="row">
            <?php
            // Display footer widgets
            for ($i = 1; $i <= $footer_columns; $i++) {
                if (is_active_sidebar('footer-' . $i)) {
                    ?>
                    <div class="<?php echo esc_attr($column_class); ?>">
                        <div class="footer-widget">
                            <?php dynamic_sidebar('footer-' . $i); ?>
                        </div>
                    </div>
                    <?php
                }
            }
            ?>
        </div>
    </div>
</div>

<?php
// Footer Newsletter
if (get_theme_mod('aqualuxe_show_footer_newsletter', true)) {
    $newsletter_title = get_theme_mod('aqualuxe_footer_newsletter_title', __('Subscribe to Our Newsletter', 'aqualuxe'));
    $newsletter_text = get_theme_mod('aqualuxe_footer_newsletter_text', __('Stay updated with our latest news and offers.', 'aqualuxe'));
    $newsletter_shortcode = get_theme_mod('aqualuxe_footer_newsletter_shortcode', '');
    
    if (!empty($newsletter_shortcode)) {
        ?>
        <div class="footer-newsletter">
            <div class="container">
                <div class="newsletter-inner">
                    <div class="row align-items-center">
                        <div class="col-lg-5">
                            <div class="newsletter-content">
                                <?php if (!empty($newsletter_title)) : ?>
                                    <h3 class="newsletter-title"><?php echo esc_html($newsletter_title); ?></h3>
                                <?php endif; ?>
                                
                                <?php if (!empty($newsletter_text)) : ?>
                                    <div class="newsletter-text"><?php echo wp_kses_post($newsletter_text); ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="col-lg-7">
                            <div class="newsletter-form">
                                <?php echo do_shortcode($newsletter_shortcode); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}