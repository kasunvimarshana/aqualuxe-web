<?php
/**
 * Template for the language switcher.
 *
 * @var array $args {
 *     An array of arguments.
 *     @type array $languages Standardized language array.
 *     @type string $display_mode 'dropdown' or 'list'.
 * }
 */

if (empty($args['languages'])) {
    return;
}

$languages = $args['languages'];
$display_mode = $args['display_mode'] ?? 'dropdown';
$current_lang = null;

foreach ($languages as $lang) {
    if ($lang['is_current']) {
        $current_lang = $lang;
        break;
    }
}
?>
<div class="aqualuxe-language-switcher" data-display-mode="<?php echo esc_attr($display_mode); ?>">
    <?php if ($display_mode === 'dropdown' && $current_lang) : ?>
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" class="switcher-button">
                <img src="<?php echo esc_url($current_lang['flag']); ?>" alt="<?php echo esc_attr($current_lang['name']); ?>" class="w-4 h-4 mr-2">
                <span><?php echo esc_html($current_lang['slug']); ?></span>
                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </button>
            <ul x-show="open" @click.away="open = false" class="switcher-dropdown" style="display: none;">
                <?php foreach ($languages as $lang) : ?>
                    <?php if (!$lang['is_current']) : ?>
                        <li>
                            <a href="<?php echo esc_url($lang['url']); ?>" class="switcher-item">
                                <img src="<?php echo esc_url($lang['flag']); ?>" alt="<?php echo esc_attr($lang['name']); ?>" class="w-4 h-4 mr-2">
                                <span><?php echo esc_html($lang['name']); ?></span>
                            </a>
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php else : ?>
        <ul class="switcher-list">
            <?php foreach ($languages as $lang) : ?>
                <li class="<?php echo $lang['is_current'] ? 'current-lang' : ''; ?>">
                    <a href="<?php echo esc_url($lang['url']); ?>">
                        <img src="<?php echo esc_url($lang['flag']); ?>" alt="<?php echo esc_attr($lang['name']); ?>">
                        <span><?php echo esc_html($lang['name']); ?></span>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</div>
