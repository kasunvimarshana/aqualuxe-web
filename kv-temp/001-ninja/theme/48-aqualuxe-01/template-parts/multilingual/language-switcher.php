<?php
/**
 * Template part for displaying language switcher
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

// Get available languages (this would typically come from a multilingual plugin like WPML or Polylang)
$available_languages = array(
    'en' => array(
        'code' => 'en',
        'name' => 'English',
        'flag' => 'us',
        'url' => '#',
        'active' => true
    ),
    'es' => array(
        'code' => 'es',
        'name' => 'Español',
        'flag' => 'es',
        'url' => '#',
        'active' => false
    ),
    'fr' => array(
        'code' => 'fr',
        'name' => 'Français',
        'flag' => 'fr',
        'url' => '#',
        'active' => false
    ),
    'de' => array(
        'code' => 'de',
        'name' => 'Deutsch',
        'flag' => 'de',
        'url' => '#',
        'active' => false
    ),
    'zh' => array(
        'code' => 'zh',
        'name' => '中文',
        'flag' => 'cn',
        'url' => '#',
        'active' => false
    ),
);

// Current language (for demo purposes)
$current_language = 'en';
?>

<div class="language-switcher relative">
    <button id="language-dropdown-button" class="flex items-center space-x-1 text-sm focus:outline-none">
        <span class="flag-icon flag-icon-<?php echo esc_attr($available_languages[$current_language]['flag']); ?>"></span>
        <span class="language-code"><?php echo esc_html(strtoupper($current_language)); ?></span>
        <i class="fas fa-chevron-down text-xs"></i>
    </button>
    
    <div id="language-dropdown" class="language-dropdown absolute right-0 mt-2 w-48 bg-white dark:bg-dark-medium rounded-lg shadow-lg py-2 hidden z-50">
        <?php foreach ($available_languages as $lang) : ?>
            <a href="<?php echo esc_url($lang['url']); ?>" class="language-item flex items-center px-4 py-2 hover:bg-light-dark dark:hover:bg-dark-light <?php echo $lang['active'] ? 'bg-light-dark dark:bg-dark-light' : ''; ?>">
                <span class="flag-icon flag-icon-<?php echo esc_attr($lang['flag']); ?> mr-2"></span>
                <span class="language-name"><?php echo esc_html($lang['name']); ?></span>
            </a>
        <?php endforeach; ?>
    </div>
</div>

<script>
    // Simple toggle for language dropdown
    document.addEventListener('DOMContentLoaded', function() {
        const button = document.getElementById('language-dropdown-button');
        const dropdown = document.getElementById('language-dropdown');
        
        if (button && dropdown) {
            button.addEventListener('click', function() {
                dropdown.classList.toggle('hidden');
            });
            
            // Close dropdown when clicking outside
            document.addEventListener('click', function(event) {
                if (!button.contains(event.target) && !dropdown.contains(event.target)) {
                    dropdown.classList.add('hidden');
                }
            });
        }
    });
</script>