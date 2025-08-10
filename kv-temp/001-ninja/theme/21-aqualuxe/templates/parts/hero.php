<?php
/**
 * Template part for displaying hero section on homepage
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Get hero settings from customizer or default values
$hero_title = get_theme_mod('aqualuxe_hero_title', __('Bringing elegance to aquatic life – globally.', 'aqualuxe'));
$hero_description = get_theme_mod('aqualuxe_hero_description', __('Premium Ornamental Aquatic Solutions for local and international markets. Discover our exclusive collection of rare fish, aquatic plants, and custom aquarium designs.', 'aqualuxe'));
$hero_button_text = get_theme_mod('aqualuxe_hero_button_text', __('Shop Now', 'aqualuxe'));
$hero_button_url = get_theme_mod('aqualuxe_hero_button_url', home_url('/shop'));
$hero_button_text_2 = get_theme_mod('aqualuxe_hero_button_text_2', __('Our Services', 'aqualuxe'));
$hero_button_url_2 = get_theme_mod('aqualuxe_hero_button_url_2', home_url('/services'));
$hero_image = get_theme_mod('aqualuxe_hero_image', get_template_directory_uri() . '/assets/images/hero-default.jpg');
?>

<section class="hero relative bg-blue-900 text-white overflow-hidden">
    <div class="absolute inset-0 z-0">
        <img src="<?php echo esc_url($hero_image); ?>" alt="<?php echo esc_attr($hero_title); ?>" class="w-full h-full object-cover opacity-40">
        <div class="absolute inset-0 bg-gradient-to-r from-blue-900 to-transparent opacity-70"></div>
    </div>
    
    <div class="container mx-auto px-4 py-16 md:py-32 relative z-10">
        <div class="max-w-2xl">
            <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold mb-6 leading-tight">
                <?php echo esc_html($hero_title); ?>
            </h1>
            
            <p class="text-lg md:text-xl text-teal-100 mb-8">
                <?php echo esc_html($hero_description); ?>
            </p>
            
            <div class="flex flex-wrap gap-4">
                <a href="<?php echo esc_url($hero_button_url); ?>" class="inline-block bg-teal-500 hover:bg-teal-600 text-white font-bold py-3 px-6 rounded-lg transition-colors">
                    <?php echo esc_html($hero_button_text); ?>
                </a>
                
                <a href="<?php echo esc_url($hero_button_url_2); ?>" class="inline-block bg-transparent hover:bg-white text-white hover:text-blue-900 font-bold py-3 px-6 rounded-lg border-2 border-white transition-colors">
                    <?php echo esc_html($hero_button_text_2); ?>
                </a>
            </div>
        </div>
    </div>
    
    <?php if (class_exists('WooCommerce')) : ?>
    <div class="hero-categories absolute bottom-0 left-0 right-0 bg-gradient-to-t from-blue-900 to-transparent pb-6 pt-16">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <?php
                $product_categories = get_terms(array(
                    'taxonomy' => 'product_cat',
                    'hide_empty' => true,
                    'parent' => 0,
                    'number' => 4,
                ));

                if (!empty($product_categories) && !is_wp_error($product_categories)) {
                    foreach ($product_categories as $category) {
                        $thumbnail_id = get_term_meta($category->term_id, 'thumbnail_id', true);
                        $image = $thumbnail_id ? wp_get_attachment_image_url($thumbnail_id, 'thumbnail') : wc_placeholder_img_src();
                        ?>
                        <a href="<?php echo esc_url(get_term_link($category)); ?>" class="category-item flex items-center bg-white bg-opacity-10 backdrop-blur-sm rounded-lg p-3 hover:bg-opacity-20 transition-all">
                            <div class="category-image w-12 h-12 rounded-full overflow-hidden mr-3">
                                <img src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr($category->name); ?>" class="w-full h-full object-cover">
                            </div>
                            <h3 class="category-name text-sm font-medium"><?php echo esc_html($category->name); ?></h3>
                        </a>
                        <?php
                    }
                }
                ?>
            </div>
        </div>
    </div>
    <?php endif; ?>
</section>