<?php
/**
 * Breadcrumb navigation template
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Don't show breadcrumbs on the home page
if (is_front_page()) {
    return;
}

// Get breadcrumb items
$breadcrumbs = aqualuxe_get_breadcrumbs();

if (empty($breadcrumbs)) {
    return;
}
?>

<nav class="breadcrumb-navigation py-4 bg-gray-50 border-b border-gray-200" aria-label="<?php esc_attr_e('Breadcrumb', 'aqualuxe'); ?>">
    <div class="<?php echo esc_attr(aqualuxe_get_container_classes()); ?>">
        
        <ol class="breadcrumb-list flex items-center space-x-2 text-sm" itemscope itemtype="https://schema.org/BreadcrumbList">
            
            <?php foreach ($breadcrumbs as $index => $breadcrumb) : ?>
                
                <li class="breadcrumb-item flex items-center" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                    
                    <?php if ($index > 0) : ?>
                        <!-- Separator -->
                        <svg class="breadcrumb-separator w-4 h-4 text-gray-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                    <?php endif; ?>

                    <?php if (isset($breadcrumb['url']) && !empty($breadcrumb['url']) && $index < count($breadcrumbs) - 1) : ?>
                        <!-- Linked breadcrumb -->
                        <a href="<?php echo esc_url($breadcrumb['url']); ?>" 
                           class="breadcrumb-link text-gray-600 hover:text-primary transition-colors" 
                           itemprop="item">
                            <span itemprop="name"><?php echo esc_html($breadcrumb['title']); ?></span>
                        </a>
                        <meta itemprop="position" content="<?php echo esc_attr($index + 1); ?>">
                    <?php else : ?>
                        <!-- Current page (no link) -->
                        <span class="breadcrumb-current text-gray-900 font-medium" 
                              itemprop="name" 
                              aria-current="page">
                            <?php echo esc_html($breadcrumb['title']); ?>
                        </span>
                        <meta itemprop="position" content="<?php echo esc_attr($index + 1); ?>">
                        <meta itemprop="item" content="<?php echo esc_url(get_permalink()); ?>">
                    <?php endif; ?>

                </li>

            <?php endforeach; ?>

        </ol>

        <!-- Mobile breadcrumb (simplified) -->
        <div class="mobile-breadcrumb md:hidden mt-2">
            <?php if (count($breadcrumbs) > 1) : ?>
                <button type="button" class="mobile-breadcrumb-toggle flex items-center text-sm text-gray-600 hover:text-primary transition-colors" aria-expanded="false">
                    <svg class="w-4 h-4 mr-1 transform transition-transform" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                    <?php esc_html_e('Show navigation', 'aqualuxe'); ?>
                </button>
                
                <div class="mobile-breadcrumb-list hidden mt-2 p-3 bg-white rounded-lg shadow-sm border border-gray-200">
                    <ol class="space-y-2">
                        <?php foreach ($breadcrumbs as $index => $breadcrumb) : ?>
                            <li class="flex items-center text-sm">
                                <span class="breadcrumb-number w-6 h-6 bg-gray-100 text-gray-600 rounded-full flex items-center justify-center text-xs mr-3">
                                    <?php echo esc_html($index + 1); ?>
                                </span>
                                
                                <?php if (isset($breadcrumb['url']) && !empty($breadcrumb['url']) && $index < count($breadcrumbs) - 1) : ?>
                                    <a href="<?php echo esc_url($breadcrumb['url']); ?>" class="text-gray-600 hover:text-primary transition-colors">
                                        <?php echo esc_html($breadcrumb['title']); ?>
                                    </a>
                                <?php else : ?>
                                    <span class="text-gray-900 font-medium">
                                        <?php echo esc_html($breadcrumb['title']); ?>
                                    </span>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                    </ol>
                </div>
            <?php endif; ?>
        </div>

    </div>
</nav>

<script>
// Mobile breadcrumb toggle functionality
document.addEventListener('DOMContentLoaded', function() {
    const toggleButton = document.querySelector('.mobile-breadcrumb-toggle');
    const breadcrumbList = document.querySelector('.mobile-breadcrumb-list');
    const toggleIcon = toggleButton?.querySelector('svg');
    
    if (toggleButton && breadcrumbList) {
        toggleButton.addEventListener('click', function() {
            const isExpanded = this.getAttribute('aria-expanded') === 'true';
            
            // Toggle visibility
            breadcrumbList.classList.toggle('hidden');
            
            // Update aria-expanded
            this.setAttribute('aria-expanded', !isExpanded);
            
            // Rotate icon
            if (toggleIcon) {
                toggleIcon.classList.toggle('rotate-180');
            }
            
            // Update button text
            const buttonText = this.querySelector('span') || this.lastChild;
            if (buttonText && buttonText.nodeType === Node.TEXT_NODE) {
                buttonText.textContent = isExpanded ? 
                    '<?php esc_html_e('Show navigation', 'aqualuxe'); ?>' : 
                    '<?php esc_html_e('Hide navigation', 'aqualuxe'); ?>';
            }
        });
    }
});
</script>

<style>
/* Breadcrumb specific styles */
.breadcrumb-navigation {
    font-size: 0.875rem;
}

.breadcrumb-separator {
    flex-shrink: 0;
}

.breadcrumb-link:hover {
    text-decoration: underline;
}

.mobile-breadcrumb-toggle svg {
    transition: transform 0.2s ease;
}

.mobile-breadcrumb-toggle svg.rotate-180 {
    transform: rotate(180deg);
}

@media (min-width: 768px) {
    .mobile-breadcrumb {
        display: none;
    }
}

/* RTL Support */
[dir="rtl"] .breadcrumb-separator {
    transform: rotate(180deg);
}

[dir="rtl"] .breadcrumb-list {
    direction: rtl;
}

[dir="rtl"] .mobile-breadcrumb-toggle svg {
    margin-left: 0.25rem;
    margin-right: 0;
}
</style>
