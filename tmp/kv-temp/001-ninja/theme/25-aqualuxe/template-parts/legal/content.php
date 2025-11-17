<?php
/**
 * Template part for displaying the legal page content section
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

// Get page ID
$page_id = get_the_ID();

// Get section settings from page meta or theme options
$show_toc = get_post_meta($page_id, '_aqualuxe_legal_show_toc', true);
if ($show_toc === '') {
    $show_toc = get_theme_mod('aqualuxe_legal_show_toc', true);
}

$section_background = get_post_meta($page_id, '_aqualuxe_legal_content_background', true);
if ($section_background === '') {
    $section_background = get_theme_mod('aqualuxe_legal_content_background', 'white');
}

// Set background class based on setting
$bg_class = $section_background === 'gray' ? 'bg-gray-50' : 'bg-white';

// Get the page content
$content = get_the_content();

// Extract headings for table of contents if enabled
$headings = array();
if ($show_toc && !empty($content)) {
    preg_match_all('/<h([2-3])[^>]*>(.*?)<\/h\1>/', $content, $matches, PREG_SET_ORDER);
    
    foreach ($matches as $match) {
        $level = $match[1];
        $title = strip_tags($match[2]);
        $id = sanitize_title($title);
        
        // Add id attribute to the heading in the content
        $content = str_replace($match[0], '<h' . $level . ' id="' . $id . '">' . $match[2] . '</h' . $level . '>', $content);
        
        $headings[] = array(
            'level' => $level,
            'title' => $title,
            'id'    => $id,
        );
    }
}
?>

<section class="legal-content-section py-16 <?php echo esc_attr($bg_class); ?>">
    <div class="container mx-auto px-4">
        <div class="legal-content-container max-w-4xl mx-auto">
            <?php if ($show_toc && !empty($headings)) : ?>
                <div class="table-of-contents bg-gray-50 p-6 rounded-lg mb-10">
                    <h2 class="text-xl font-bold mb-4"><?php esc_html_e('Table of Contents', 'aqualuxe'); ?></h2>
                    <ul class="toc-list space-y-2">
                        <?php foreach ($headings as $heading) : ?>
                            <li class="<?php echo $heading['level'] == 3 ? 'ml-6' : ''; ?>">
                                <a href="#<?php echo esc_attr($heading['id']); ?>" class="text-primary hover:underline">
                                    <?php echo esc_html($heading['title']); ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            
            <div class="legal-content prose max-w-none">
                <?php
                // Output the modified content with heading IDs
                if (!empty($content)) {
                    echo apply_filters('the_content', $content);
                } else {
                    the_content();
                }
                ?>
            </div>
            
            <div class="legal-meta mt-12 pt-6 border-t border-gray-200 text-gray-600 text-sm">
                <div class="flex flex-wrap justify-between items-center">
                    <div class="legal-dates mb-4 md:mb-0">
                        <p>
                            <?php printf(esc_html__('Published: %s', 'aqualuxe'), get_the_date('F j, Y')); ?>
                            <?php if (get_the_date() !== get_the_modified_date()) : ?>
                                <span class="mx-2">|</span>
                                <?php printf(esc_html__('Last Updated: %s', 'aqualuxe'), get_the_modified_date('F j, Y')); ?>
                            <?php endif; ?>
                        </p>
                    </div>
                    
                    <div class="legal-actions">
                        <button onclick="window.print();" class="inline-flex items-center text-primary hover:text-primary-dark">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                            </svg>
                            <?php esc_html_e('Print', 'aqualuxe'); ?>
                        </button>
                        
                        <button onclick="navigator.clipboard.writeText(window.location.href);" class="inline-flex items-center text-primary hover:text-primary-dark ml-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                            </svg>
                            <?php esc_html_e('Copy Link', 'aqualuxe'); ?>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>