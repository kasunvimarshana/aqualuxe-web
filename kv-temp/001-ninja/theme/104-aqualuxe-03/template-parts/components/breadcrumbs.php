<?php
/**
 * Template part for displaying breadcrumbs
 *
 * @package AquaLuxe
 */

// Don't show breadcrumbs on home page
if (is_front_page()) {
    return;
}

$breadcrumbs = aqualuxe_get_breadcrumbs();

if (empty($breadcrumbs)) {
    return;
}
?>

<nav class="breadcrumbs py-4 bg-gray-50 dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700" aria-label="<?php esc_attr_e('Breadcrumb', 'aqualuxe'); ?>">
    <div class="container mx-auto px-4">
        <ol class="flex items-center space-x-2 text-sm" itemscope itemtype="https://schema.org/BreadcrumbList">
            <?php foreach ($breadcrumbs as $index => $breadcrumb) : ?>
                <li class="flex items-center" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                    <?php if ($index > 0) : ?>
                        <svg class="w-4 h-4 text-gray-400 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    <?php endif; ?>
                    
                    <?php if (!empty($breadcrumb['url']) && $index < count($breadcrumbs) - 1) : ?>
                        <a href="<?php echo esc_url($breadcrumb['url']); ?>" 
                           class="text-gray-600 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors duration-200"
                           itemprop="item">
                            <span itemprop="name"><?php echo esc_html($breadcrumb['title']); ?></span>
                        </a>
                    <?php else : ?>
                        <span class="text-gray-900 dark:text-white font-medium" itemprop="name">
                            <?php echo esc_html($breadcrumb['title']); ?>
                        </span>
                    <?php endif; ?>
                    
                    <meta itemprop="position" content="<?php echo esc_attr($index + 1); ?>">
                </li>
            <?php endforeach; ?>
        </ol>
    </div>
</nav>