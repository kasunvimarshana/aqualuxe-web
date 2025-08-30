<?php
/**
 * Homepage Testimonials Section
 *
 * @package AquaLuxe
 */

// Get section content from theme options or use default
$section_title = get_theme_mod('aqualuxe_testimonials_title', __('What Our Customers Say', 'aqualuxe'));
$section_description = get_theme_mod('aqualuxe_testimonials_description', __('Hear from hobbyists and businesses who trust AquaLuxe for their aquatic needs.', 'aqualuxe'));
$background_color = get_theme_mod('aqualuxe_testimonials_background', 'primary'); // primary, light, dark

// Set background class based on setting
$bg_class = '';
switch ($background_color) {
    case 'primary':
        $bg_class = 'bg-primary text-white';
        break;
    case 'light':
        $bg_class = 'bg-white';
        break;
    case 'dark':
        $bg_class = 'bg-gray-900 text-white';
        break;
    default:
        $bg_class = 'bg-primary text-white';
}

// Get testimonials
// First check if we have custom testimonials from theme options
$testimonials = array();

for ($i = 1; $i <= 3; $i++) {
    $name = get_theme_mod('aqualuxe_testimonial_name_' . $i, '');
    $role = get_theme_mod('aqualuxe_testimonial_role_' . $i, '');
    $content = get_theme_mod('aqualuxe_testimonial_content_' . $i, '');
    $image = get_theme_mod('aqualuxe_testimonial_image_' . $i, '');
    
    if (!empty($name) && !empty($content)) {
        $testimonials[] = array(
            'name' => $name,
            'role' => $role,
            'content' => $content,
            'image' => $image,
        );
    }
}

// If no custom testimonials, use defaults
if (empty($testimonials)) {
    $testimonials = array(
        array(
            'name' => __('John Smith', 'aqualuxe'),
            'role' => __('Aquarium Hobbyist', 'aqualuxe'),
            'content' => __('AquaLuxe has transformed my home aquarium experience. Their fish are always healthy and vibrant, and their customer service is exceptional. I highly recommend them to any fish enthusiast.', 'aqualuxe'),
            'image' => get_template_directory_uri() . '/assets/images/testimonial-1.jpg',
        ),
        array(
            'name' => __('Sarah Johnson', 'aqualuxe'),
            'role' => __('Pet Store Owner', 'aqualuxe'),
            'content' => __('As a business owner, I rely on consistent quality and reliable shipping. AquaLuxe delivers on both fronts. Their wholesale program has been instrumental in growing my store\'s aquatic section.', 'aqualuxe'),
            'image' => get_template_directory_uri() . '/assets/images/testimonial-2.jpg',
        ),
        array(
            'name' => __('Michael Chen', 'aqualuxe'),
            'role' => __('Marine Biologist', 'aqualuxe'),
            'content' => __('The attention to detail and commitment to sustainability at AquaLuxe is impressive. Their breeding programs and aquaculture practices are setting new standards in the industry.', 'aqualuxe'),
            'image' => get_template_directory_uri() . '/assets/images/testimonial-3.jpg',
        ),
    );
}

// Only show section if we have testimonials
if (!empty($testimonials)) :
?>

<section class="testimonials-section py-16 <?php echo esc_attr($bg_class); ?>">
    <div class="container">
        <?php if ($section_title || $section_description) : ?>
            <div class="section-header text-center mb-12">
                <?php if ($section_title) : ?>
                    <h2 class="section-title text-3xl md:text-4xl font-bold mb-4"><?php echo esc_html($section_title); ?></h2>
                <?php endif; ?>
                
                <?php if ($section_description) : ?>
                    <p class="section-description text-lg opacity-80 max-w-3xl mx-auto"><?php echo esc_html($section_description); ?></p>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        
        <div class="testimonials-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($testimonials as $testimonial) : ?>
                <div class="testimonial-item bg-white text-gray-800 rounded-lg shadow-lg p-6 relative">
                    <div class="testimonial-content mb-6">
                        <div class="quote-icon text-primary text-4xl mb-4">
                            <i class="fa fa-quote-left"></i>
                        </div>
                        <p class="text-gray-600"><?php echo esc_html($testimonial['content']); ?></p>
                    </div>
                    
                    <div class="testimonial-author flex items-center">
                        <?php if (!empty($testimonial['image'])) : ?>
                            <div class="author-image mr-4">
                                <img src="<?php echo esc_url($testimonial['image']); ?>" alt="<?php echo esc_attr($testimonial['name']); ?>" class="w-12 h-12 rounded-full object-cover">
                            </div>
                        <?php endif; ?>
                        
                        <div class="author-info">
                            <h4 class="author-name font-bold"><?php echo esc_html($testimonial['name']); ?></h4>
                            <?php if (!empty($testimonial['role'])) : ?>
                                <p class="author-role text-sm text-gray-500"><?php echo esc_html($testimonial['role']); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php endif; ?>