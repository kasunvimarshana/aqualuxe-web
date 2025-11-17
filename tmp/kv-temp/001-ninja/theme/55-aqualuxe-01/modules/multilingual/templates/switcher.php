<?php
/**
 * Language Switcher Template
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
$style = $module->get_option('switcher_style', 'dropdown');
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

// Dropdown style
if ($style === 'dropdown') :
?>
<div class="language-switcher language-switcher-dropdown" x-data="{ open: false }">
    <button 
        class="language-switcher-toggle" 
        @click="open = !open" 
        @click.away="open = false" 
        aria-expanded="false"
        :aria-expanded="open ? 'true' : 'false'"
    >
        <?php foreach ($languages as $language) : ?>
            <?php if ($language['current_lang']) : ?>
                <?php if ($show_flags) : ?>
                    <img src="<?php echo esc_url($language['flag']); ?>" alt="<?php echo esc_attr($language['name']); ?>" class="language-flag" />
                <?php endif; ?>
                
                <?php if ($show_names) : ?>
                    <span class="language-name"><?php echo esc_html($language['name']); ?></span>
                <?php endif; ?>
                
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" class="language-switcher-arrow">
                    <path fill="none" d="M0 0h24v24H0z"/>
                    <path d="M12 13.172l4.95-4.95 1.414 1.414L12 16 5.636 9.636 7.05 8.222z"/>
                </svg>
            <?php endif; ?>
        <?php endforeach; ?>
    </button>
    
    <ul class="language-switcher-dropdown-menu" x-show="open" x-transition>
        <?php foreach ($languages as $language) : ?>
            <li class="language-item <?php echo $language['current_lang'] ? 'current-lang' : ''; ?>">
                <a href="<?php echo esc_url($language['url']); ?>" lang="<?php echo esc_attr($language['locale']); ?>">
                    <?php if ($show_flags) : ?>
                        <img src="<?php echo esc_url($language['flag']); ?>" alt="<?php echo esc_attr($language['name']); ?>" class="language-flag" />
                    <?php endif; ?>
                    
                    <?php if ($show_names) : ?>
                        <span class="language-name"><?php echo esc_html($language['name']); ?></span>
                    <?php endif; ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</div>

<?php
// List style
elseif ($style === 'list') :
?>
<div class="language-switcher language-switcher-list">
    <ul class="language-list">
        <?php foreach ($languages as $language) : ?>
            <li class="language-item <?php echo $language['current_lang'] ? 'current-lang' : ''; ?>">
                <a href="<?php echo esc_url($language['url']); ?>" lang="<?php echo esc_attr($language['locale']); ?>">
                    <?php if ($show_flags) : ?>
                        <img src="<?php echo esc_url($language['flag']); ?>" alt="<?php echo esc_attr($language['name']); ?>" class="language-flag" />
                    <?php endif; ?>
                    
                    <?php if ($show_names) : ?>
                        <span class="language-name"><?php echo esc_html($language['name']); ?></span>
                    <?php endif; ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</div>

<?php
// Flags style
elseif ($style === 'flags') :
?>
<div class="language-switcher language-switcher-flags">
    <ul class="language-flags-list">
        <?php foreach ($languages as $language) : ?>
            <li class="language-item <?php echo $language['current_lang'] ? 'current-lang' : ''; ?>">
                <a href="<?php echo esc_url($language['url']); ?>" lang="<?php echo esc_attr($language['locale']); ?>" title="<?php echo esc_attr($language['name']); ?>">
                    <img src="<?php echo esc_url($language['flag']); ?>" alt="<?php echo esc_attr($language['name']); ?>" class="language-flag" />
                    <span class="screen-reader-text"><?php echo esc_html($language['name']); ?></span>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</div>

<?php
// Flags with names style
elseif ($style === 'flags_name') :
?>
<div class="language-switcher language-switcher-flags-name">
    <ul class="language-flags-name-list">
        <?php foreach ($languages as $language) : ?>
            <li class="language-item <?php echo $language['current_lang'] ? 'current-lang' : ''; ?>">
                <a href="<?php echo esc_url($language['url']); ?>" lang="<?php echo esc_attr($language['locale']); ?>">
                    <img src="<?php echo esc_url($language['flag']); ?>" alt="<?php echo esc_attr($language['name']); ?>" class="language-flag" />
                    <span class="language-name"><?php echo esc_html($language['name']); ?></span>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</div>
<?php endif; ?>