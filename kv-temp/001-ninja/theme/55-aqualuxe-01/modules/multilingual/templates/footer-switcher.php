<?php
/**
 * Footer Language Switcher Template
 *
 * @package AquaLuxe
 * @subpackage Modules/Multilingual
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Get module options
$module = $GLOBALS['aqualuxe_theme']->modules['multilingual'];
$show_current = $module->get_option('show_current', true);
$show_names = $module->get_option('show_names', true);
$show_flags = $module->get_option('show_flags', true);

// Get current language
$current_lang = pll_current_language('slug');

// Get languages
$languages = pll_the_languages([
    'raw' => true,
    'hide_if_empty' => 0,
    'hide_current' => !$show_current,
    'display_names_as' => 'name',
]);

if (empty($languages)) {
    return;
}
?>

<div class="footer-language-switcher">
    <ul class="footer-language-list">
        <?php foreach ($languages as $language) : ?>
            <li class="footer-language-item <?php echo $language['current_lang'] ? 'current-lang' : ''; ?>">
                <a href="<?php echo esc_url($language['url']); ?>" lang="<?php echo esc_attr($language['locale']); ?>">
                    <?php if ($show_flags) : ?>
                        <img src="<?php echo esc_url($language['flag']); ?>" alt="<?php echo esc_attr($language['name']); ?>" class="footer-language-flag" />
                    <?php endif; ?>
                    
                    <?php if ($show_names) : ?>
                        <span class="footer-language-name"><?php echo esc_html($language['name']); ?></span>
                    <?php endif; ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</div>