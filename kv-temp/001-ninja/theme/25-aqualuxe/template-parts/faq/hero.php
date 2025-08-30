<?php
/**
 * Template part for displaying the FAQ page hero section
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

// Get page ID
$page_id = get_the_ID();

// Get hero settings from page meta or theme options
$hero_title = get_post_meta($page_id, '_aqualuxe_faq_hero_title', true);
if (!$hero_title) {
    $hero_title = get_theme_mod('aqualuxe_faq_hero_title', __('Frequently Asked Questions', 'aqualuxe'));
}

$hero_subtitle = get_post_meta($page_id, '_aqualuxe_faq_hero_subtitle', true);
if (!$hero_subtitle) {
    $hero_subtitle = get_theme_mod('aqualuxe_faq_hero_subtitle', __('Find Answers', 'aqualuxe'));
}

$hero_text = get_post_meta($page_id, '_aqualuxe_faq_hero_text', true);
if (!$hero_text) {
    $hero_text = get_theme_mod('aqualuxe_faq_hero_text', __('Find answers to the most common questions about our products and services.', 'aqualuxe'));
}

$hero_image = get_post_meta($page_id, '_aqualuxe_faq_hero_image', true);
if (!$hero_image) {
    $hero_image = get_theme_mod('aqualuxe_faq_hero_image', get_template_directory_uri() . '/assets/images/faq-hero.jpg');
}

$hero_overlay = get_post_meta($page_id, '_aqualuxe_faq_hero_overlay', true);
if ($hero_overlay === '') {
    $hero_overlay = get_theme_mod('aqualuxe_faq_hero_overlay', true);
}

// Set overlay class if enabled
$overlay_class = $hero_overlay ? 'after:absolute after:inset-0 after:bg-black after:bg-opacity-40 after:z-10' : '';

// Search form settings
$show_search = get_post_meta($page_id, '_aqualuxe_faq_hero_search', true);
if ($show_search === '') {
    $show_search = get_theme_mod('aqualuxe_faq_hero_search', true);
}

$search_placeholder = get_post_meta($page_id, '_aqualuxe_faq_hero_search_placeholder', true);
if (!$search_placeholder) {
    $search_placeholder = get_theme_mod('aqualuxe_faq_hero_search_placeholder', __('Search for answers...', 'aqualuxe'));
}
?>

<section class="faq-hero-section relative min-h-[40vh] <?php echo esc_attr($overlay_class); ?> flex items-center">
    <?php if ($hero_image) : ?>
        <div class="hero-background absolute inset-0 z-0">
            <img src="<?php echo esc_url($hero_image); ?>" alt="<?php echo esc_attr($hero_title); ?>" class="w-full h-full object-cover">
        </div>
    <?php endif; ?>
    
    <div class="container mx-auto px-4 relative z-20">
        <div class="hero-content max-w-3xl mx-auto text-center text-white">
            <?php if ($hero_subtitle) : ?>
                <div class="hero-subtitle mb-2 text-lg md:text-xl font-light tracking-wider">
                    <?php echo esc_html($hero_subtitle); ?>
                </div>
            <?php endif; ?>
            
            <?php if ($hero_title) : ?>
                <h1 class="hero-title text-4xl md:text-5xl lg:text-6xl font-bold mb-6">
                    <?php echo esc_html($hero_title); ?>
                </h1>
            <?php endif; ?>
            
            <?php if ($hero_text) : ?>
                <div class="hero-text text-lg mb-8 text-white text-opacity-90">
                    <?php echo wp_kses_post($hero_text); ?>
                </div>
            <?php endif; ?>
            
            <?php if ($show_search) : ?>
                <div class="faq-search-form max-w-2xl mx-auto">
                    <form role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>" class="relative">
                        <input type="hidden" name="post_type" value="faq">
                        <input type="search" class="w-full px-6 py-4 rounded-full text-gray-800 bg-white bg-opacity-90 focus:bg-opacity-100 focus:outline-none focus:ring-2 focus:ring-primary" placeholder="<?php echo esc_attr($search_placeholder); ?>" value="<?php echo get_search_query(); ?>" name="s">
                        <button type="submit" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <span class="sr-only"><?php esc_html_e('Search', 'aqualuxe'); ?></span>
                        </button>
                    </form>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>