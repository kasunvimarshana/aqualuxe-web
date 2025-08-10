<?php
/**
 * Template part for displaying the legal page related pages section
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

// Get page ID
$page_id = get_the_ID();

// Get section settings from page meta or theme options
$section_title = get_post_meta($page_id, '_aqualuxe_legal_related_title', true);
if (!$section_title) {
    $section_title = get_theme_mod('aqualuxe_legal_related_title', __('Related Legal Information', 'aqualuxe'));
}

$section_subtitle = get_post_meta($page_id, '_aqualuxe_legal_related_subtitle', true);
if (!$section_subtitle) {
    $section_subtitle = get_theme_mod('aqualuxe_legal_related_subtitle', __('Other Policies', 'aqualuxe'));
}

$section_background = get_post_meta($page_id, '_aqualuxe_legal_related_background', true);
if ($section_background === '') {
    $section_background = get_theme_mod('aqualuxe_legal_related_background', 'gray');
}

// Set background class based on setting
$bg_class = $section_background === 'gray' ? 'bg-gray-50' : 'bg-white';

// Get related legal pages
// First, try to get pages with the legal page template
$args = array(
    'post_type'      => 'page',
    'posts_per_page' => 4,
    'post__not_in'   => array($page_id),
    'meta_query'     => array(
        array(
            'key'     => '_wp_page_template',
            'value'   => 'templates/template-legal.php',
            'compare' => '=',
        ),
    ),
);

$related_pages = get_posts($args);

// If not enough pages found, get pages from a specific category or tag
if (count($related_pages) < 2) {
    // Try to get pages with legal-related titles
    $legal_keywords = array('terms', 'privacy', 'policy', 'policies', 'conditions', 'disclaimer', 'cookies', 'legal', 'gdpr', 'ccpa', 'shipping', 'returns');
    
    $args = array(
        'post_type'      => 'page',
        'posts_per_page' => 4 - count($related_pages),
        'post__not_in'   => array_merge(array($page_id), wp_list_pluck($related_pages, 'ID')),
        'meta_query'     => array(
            'relation' => 'OR',
        ),
    );
    
    foreach ($legal_keywords as $keyword) {
        $args['meta_query'][] = array(
            'key'     => '_wp_page_template',
            'value'   => 'templates/template-legal.php',
            'compare' => '=',
        );
        
        $args['meta_query'][] = array(
            'key'     => '_aqualuxe_legal_related_pages',
            'value'   => $page_id,
            'compare' => 'LIKE',
        );
    }
    
    $title_args = array(
        'post_type'      => 'page',
        'posts_per_page' => 4 - count($related_pages),
        'post__not_in'   => array_merge(array($page_id), wp_list_pluck($related_pages, 'ID')),
        's'              => implode(' OR ', $legal_keywords),
    );
    
    $additional_pages = get_posts($title_args);
    
    if ($additional_pages) {
        $related_pages = array_merge($related_pages, $additional_pages);
    }
}

// If still not enough pages, get the most recent pages
if (count($related_pages) < 2) {
    $args = array(
        'post_type'      => 'page',
        'posts_per_page' => 4 - count($related_pages),
        'post__not_in'   => array_merge(array($page_id), wp_list_pluck($related_pages, 'ID')),
    );
    
    $recent_pages = get_posts($args);
    
    if ($recent_pages) {
        $related_pages = array_merge($related_pages, $recent_pages);
    }
}

// Limit to 4 pages maximum
$related_pages = array_slice($related_pages, 0, 4);
?>

<?php if (!empty($related_pages)) : ?>
    <section class="legal-related-section py-16 <?php echo esc_attr($bg_class); ?>">
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
            </div>
            
            <div class="related-pages-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <?php foreach ($related_pages as $related_page) : ?>
                    <a href="<?php echo esc_url(get_permalink($related_page->ID)); ?>" class="related-page-card bg-white rounded-lg shadow-md p-6 transition-transform hover:transform hover:scale-105">
                        <div class="page-icon text-primary mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        
                        <h3 class="page-title text-xl font-bold mb-2">
                            <?php echo esc_html(get_the_title($related_page->ID)); ?>
                        </h3>
                        
                        <div class="page-excerpt text-gray-600">
                            <?php
                            $excerpt = has_excerpt($related_page->ID) ? get_the_excerpt($related_page->ID) : wp_trim_words(strip_shortcodes($related_page->post_content), 12);
                            echo wp_kses_post($excerpt);
                            ?>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
<?php endif; ?>