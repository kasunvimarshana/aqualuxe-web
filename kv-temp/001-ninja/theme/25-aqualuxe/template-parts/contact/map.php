<?php
/**
 * Template part for displaying the contact page map section
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

// Get page ID
$page_id = get_the_ID();

// Get section settings from page meta or theme options
$section_title = get_post_meta($page_id, '_aqualuxe_contact_map_title', true);
if (!$section_title) {
    $section_title = get_theme_mod('aqualuxe_contact_map_title', __('Find Us', 'aqualuxe'));
}

$section_subtitle = get_post_meta($page_id, '_aqualuxe_contact_map_subtitle', true);
if (!$section_subtitle) {
    $section_subtitle = get_theme_mod('aqualuxe_contact_map_subtitle', __('Our Location', 'aqualuxe'));
}

$map_embed_code = get_post_meta($page_id, '_aqualuxe_contact_map_embed', true);
if (!$map_embed_code) {
    $map_embed_code = get_theme_mod('aqualuxe_contact_map_embed', '');
}

$map_address = get_post_meta($page_id, '_aqualuxe_contact_map_address', true);
if (!$map_address) {
    $map_address = get_theme_mod('aqualuxe_contact_map_address', '123 Aquarium Street, Marine City, FL 12345, USA');
}

$map_api_key = get_theme_mod('aqualuxe_google_maps_api_key', '');

$section_background = get_post_meta($page_id, '_aqualuxe_contact_map_background', true);
if ($section_background === '') {
    $section_background = get_theme_mod('aqualuxe_contact_map_background', 'gray');
}

// Set background class based on setting
$bg_class = $section_background === 'gray' ? 'bg-gray-50' : 'bg-white';
?>

<section class="contact-map-section py-16 <?php echo esc_attr($bg_class); ?>">
    <div class="container mx-auto px-4">
        <?php if ($section_title || $section_subtitle) : ?>
            <div class="section-header text-center mb-12">
                <?php if ($section_subtitle) : ?>
                    <div class="section-subtitle text-primary text-lg mb-2">
                        <?php echo esc_html($section_subtitle); ?>
                    </div>
                <?php endif; ?>
                
                <?php if ($section_title) : ?>
                    <h2 class="section-title text-3xl md:text-4xl font-bold mb-4">
                        <?php echo esc_html($section_title); ?>
                    </h2>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        
        <div class="map-container rounded-lg overflow-hidden shadow-lg">
            <?php
            if ($map_embed_code) {
                // Use custom embed code if provided
                echo $map_embed_code;
            } elseif ($map_api_key && $map_address) {
                // Use Google Maps API if key is provided
                $map_address_encoded = urlencode($map_address);
                $map_url = "https://www.google.com/maps/embed/v1/place?key={$map_api_key}&q={$map_address_encoded}";
                ?>
                <iframe
                    width="100%"
                    height="500"
                    frameborder="0"
                    style="border:0"
                    src="<?php echo esc_url($map_url); ?>"
                    allowfullscreen
                ></iframe>
                <?php
            } else {
                // Fallback to a static map or message
                ?>
                <div class="map-placeholder bg-gray-200 h-96 flex items-center justify-center">
                    <div class="text-center p-8">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <p class="text-gray-600 text-lg"><?php esc_html_e('Map location will be displayed here. Please add a Google Maps API key or embed code in the theme options.', 'aqualuxe'); ?></p>
                        <?php if ($map_address) : ?>
                            <p class="mt-4 font-medium"><?php echo esc_html($map_address); ?></p>
                            <a href="https://maps.google.com/?q=<?php echo esc_attr(urlencode($map_address)); ?>" target="_blank" rel="noopener noreferrer" class="button button-primary mt-4">
                                <?php esc_html_e('View on Google Maps', 'aqualuxe'); ?>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
</section>