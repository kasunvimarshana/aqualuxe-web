<?php
/**
 * Services archive description template part
 *
 * @package AquaLuxe
 * @subpackage Modules/Services
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Get the archive description
$description = '';

if (is_tax('service_category') || is_tax('service_tag')) {
    $description = term_description();
} else {
    $services_page_id = get_option('aqualuxe_services_page_id');
    if ($services_page_id) {
        $services_page = get_post($services_page_id);
        if ($services_page) {
            $description = $services_page->post_content;
        }
    }
}

// Output description if available
if ($description) :
?>
<div class="aqualuxe-services-archive-description">
    <?php echo wp_kses_post($description); ?>
</div>
<?php endif; ?>