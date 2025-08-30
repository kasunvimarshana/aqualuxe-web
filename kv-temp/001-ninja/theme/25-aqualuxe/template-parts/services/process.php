<?php
/**
 * Template part for displaying the services page process section
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

// Get page ID
$page_id = get_the_ID();

// Get section settings from page meta or theme options
$section_title = get_post_meta($page_id, '_aqualuxe_services_process_title', true);
if (!$section_title) {
    $section_title = get_theme_mod('aqualuxe_services_process_title', __('Our Process', 'aqualuxe'));
}

$section_subtitle = get_post_meta($page_id, '_aqualuxe_services_process_subtitle', true);
if (!$section_subtitle) {
    $section_subtitle = get_theme_mod('aqualuxe_services_process_subtitle', __('How We Work', 'aqualuxe'));
}

$section_description = get_post_meta($page_id, '_aqualuxe_services_process_description', true);
if (!$section_description) {
    $section_description = get_theme_mod('aqualuxe_services_process_description', __('Our streamlined process ensures a seamless experience from consultation to completion.', 'aqualuxe'));
}

// Process steps - these would typically be managed through theme options or custom fields
$process_steps = array();

// Step 1
$step1_title = get_post_meta($page_id, '_aqualuxe_services_process_step1_title', true);
if (!$step1_title) {
    $step1_title = get_theme_mod('aqualuxe_services_process_step1_title', __('Initial Consultation', 'aqualuxe'));
}

$step1_description = get_post_meta($page_id, '_aqualuxe_services_process_step1_description', true);
if (!$step1_description) {
    $step1_description = get_theme_mod('aqualuxe_services_process_step1_description', __('We begin with a thorough consultation to understand your needs, preferences, and goals for your aquatic environment.', 'aqualuxe'));
}

$process_steps[] = array(
    'title' => $step1_title,
    'description' => $step1_description,
    'icon' => 'chat-bubble-left-right',
);

// Step 2
$step2_title = get_post_meta($page_id, '_aqualuxe_services_process_step2_title', true);
if (!$step2_title) {
    $step2_title = get_theme_mod('aqualuxe_services_process_step2_title', __('Custom Planning', 'aqualuxe'));
}

$step2_description = get_post_meta($page_id, '_aqualuxe_services_process_step2_description', true);
if (!$step2_description) {
    $step2_description = get_theme_mod('aqualuxe_services_process_step2_description', __('Our experts develop a tailored plan that addresses your specific requirements, including species selection, equipment needs, and maintenance considerations.', 'aqualuxe'));
}

$process_steps[] = array(
    'title' => $step2_title,
    'description' => $step2_description,
    'icon' => 'clipboard-document-list',
);

// Step 3
$step3_title = get_post_meta($page_id, '_aqualuxe_services_process_step3_title', true);
if (!$step3_title) {
    $step3_title = get_theme_mod('aqualuxe_services_process_step3_title', __('Implementation', 'aqualuxe'));
}

$step3_description = get_post_meta($page_id, '_aqualuxe_services_process_step3_description', true);
if (!$step3_description) {
    $step3_description = get_theme_mod('aqualuxe_services_process_step3_description', __('Our skilled team executes the plan with precision, ensuring every detail is addressed from setup to stocking and system activation.', 'aqualuxe'));
}

$process_steps[] = array(
    'title' => $step3_title,
    'description' => $step3_description,
    'icon' => 'wrench-screwdriver',
);

// Step 4
$step4_title = get_post_meta($page_id, '_aqualuxe_services_process_step4_title', true);
if (!$step4_title) {
    $step4_title = get_theme_mod('aqualuxe_services_process_step4_title', __('Ongoing Support', 'aqualuxe'));
}

$step4_description = get_post_meta($page_id, '_aqualuxe_services_process_step4_description', true);
if (!$step4_description) {
    $step4_description = get_theme_mod('aqualuxe_services_process_step4_description', __('We provide continuous support and maintenance services to ensure the long-term health and beauty of your aquatic environment.', 'aqualuxe'));
}

$process_steps[] = array(
    'title' => $step4_title,
    'description' => $step4_description,
    'icon' => 'lifebuoy',
);

$section_background = get_post_meta($page_id, '_aqualuxe_services_process_background', true);
if ($section_background === '') {
    $section_background = get_theme_mod('aqualuxe_services_process_background', 'white');
}

// Set background class based on setting
$bg_class = $section_background === 'gray' ? 'bg-gray-50' : 'bg-white';
?>

<section class="services-process-section py-16 <?php echo esc_attr($bg_class); ?>">
    <div class="container mx-auto px-4">
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
            
            <?php if ($section_description) : ?>
                <div class="section-description max-w-3xl mx-auto text-gray-600">
                    <?php echo wp_kses_post($section_description); ?>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="process-steps">
            <div class="process-timeline relative">
                <!-- Timeline line -->
                <div class="hidden md:block absolute left-1/2 transform -translate-x-1/2 h-full w-1 bg-primary bg-opacity-20"></div>
                
                <?php
                foreach ($process_steps as $index => $step) :
                    $step_number = $index + 1;
                    $is_even = $index % 2 === 0;
                    $alignment_class = $is_even ? 'md:flex-row' : 'md:flex-row-reverse';
                    ?>
                    
                    <div class="process-step relative mb-12 last:mb-0">
                        <div class="flex flex-col <?php echo esc_attr($alignment_class); ?> items-center">
                            <!-- Step number for mobile -->
                            <div class="md:hidden flex items-center justify-center w-12 h-12 rounded-full bg-primary text-white text-xl font-bold mb-4">
                                <?php echo esc_html($step_number); ?>
                            </div>
                            
                            <!-- Content -->
                            <div class="w-full md:w-5/12 mb-6 md:mb-0 <?php echo $is_even ? 'md:pr-12 md:text-right' : 'md:pl-12'; ?>">
                                <h3 class="step-title text-2xl font-bold mb-3">
                                    <?php echo esc_html($step['title']); ?>
                                </h3>
                                <div class="step-description text-gray-600">
                                    <?php echo wp_kses_post(wpautop($step['description'])); ?>
                                </div>
                            </div>
                            
                            <!-- Step number for desktop -->
                            <div class="hidden md:flex items-center justify-center w-16 h-16 rounded-full bg-primary text-white text-2xl font-bold z-10">
                                <?php echo esc_html($step_number); ?>
                            </div>
                            
                            <!-- Icon -->
                            <div class="w-full md:w-5/12 <?php echo $is_even ? 'md:pl-12' : 'md:pr-12 md:text-right'; ?>">
                                <div class="step-icon text-primary text-opacity-80 <?php echo $is_even ? '' : 'md:ml-auto'; ?>">
                                    <?php if (isset($step['icon'])) : ?>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto md:mx-0 <?php echo $is_even ? '' : 'md:ml-auto'; ?>" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <?php
                                            switch ($step['icon']) {
                                                case 'chat-bubble-left-right':
                                                    echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.34.027-.68.052-1.02.072v3.091l-3-3c-1.354 0-2.694-.055-4.02-.163a2.115 2.115 0 01-.825-.242m9.345-8.334a2.126 2.126 0 00-.476-.095 48.64 48.64 0 00-8.048 0c-1.131.094-1.976 1.057-1.976 2.192v4.286c0 .837.46 1.58 1.155 1.951m9.345-8.334V6.637c0-1.621-1.152-3.026-2.76-3.235A48.455 48.455 0 0011.25 3c-2.115 0-4.198.137-6.24.402-1.608.209-2.76 1.614-2.76 3.235v6.226c0 1.621 1.152 3.026 2.76 3.235.577.075 1.157.14 1.74.194V21l4.155-4.155" />';
                                                    break;
                                                case 'clipboard-document-list':
                                                    echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />';
                                                    break;
                                                case 'wrench-screwdriver':
                                                    echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 11-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 004.486-6.336l-3.276 3.277a3.004 3.004 0 01-2.25-2.25l3.276-3.276a4.5 4.5 0 00-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.26 4.26m-1.745 1.437l1.745-1.437m6.615 8.206L15.75 15.75M4.867 19.125h.008v.008h-.008v-.008z" />';
                                                    break;
                                                case 'lifebuoy':
                                                    echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16.712 4.33a9.027 9.027 0 011.652 1.306c.51.51.944 1.064 1.306 1.652M16.712 4.33l-3.448 4.138m3.448-4.138a9.014 9.014 0 00-9.424 0M19.67 7.288l-4.138 3.448m4.138-3.448a9.014 9.014 0 010 9.424m-4.138-5.976a3.736 3.736 0 00-.88-1.388 3.737 3.737 0 00-1.388-.88m2.268 2.268a3.765 3.765 0 010 2.528m-2.268-4.796a3.765 3.765 0 00-2.528 0m4.796 4.796c-.181.506-.475.982-.88 1.388a3.736 3.736 0 01-1.388.88m2.268-2.268l4.138 3.448m0 0a9.027 9.027 0 01-1.306 1.652c-.51.51-1.064.944-1.652 1.306m0 0l-3.448-4.138m3.448 4.138a9.014 9.014 0 01-9.424 0m5.976-4.138a3.765 3.765 0 01-2.528 0m0 0a3.736 3.736 0 01-1.388-.88 3.737 3.737 0 01-.88-1.388m2.268 2.268L7.288 19.67m0 0a9.024 9.024 0 01-1.652-1.306 9.027 9.027 0 01-1.306-1.652m0 0l4.138-3.448M4.33 16.712a9.014 9.014 0 010-9.424m4.138 5.976a3.765 3.765 0 010-2.528m0 0c.181-.506.475-.982.88-1.388a3.736 3.736 0 011.388-.88m-2.268 2.268L4.33 7.288m6.406 1.18L7.288 4.33m0 0a9.024 9.024 0 00-1.652 1.306A9.025 9.025 0 004.33 7.288" />';
                                                    break;
                                                default:
                                                    echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />';
                                            }
                                            ?>
                                        </svg>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>