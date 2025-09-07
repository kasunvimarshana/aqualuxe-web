<?php
/**
 * Template for the currency switcher.
 *
 * @var array $args {
 *     An array of arguments.
 *     @type array $currencies Standardized currency array.
 *     @type array|null $current_currency The current currency object.
 *     @type string $display_mode 'dropdown' or 'list'.
 * }
 */

if (empty($args['currencies'])) {
    return;
}

$currencies = $args['currencies'];
$current_currency = $args['current_currency'];
$display_mode = $args['display_mode'] ?? 'dropdown';

?>
<div class="aqualuxe-currency-switcher" data-display-mode="<?php echo esc_attr($display_mode); ?>">
    <?php if ($display_mode === 'dropdown' && $current_currency) : ?>
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" class="switcher-button">
                <span><?php echo esc_html($current_currency['code']); ?></span>
                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </button>
            <ul x-show="open" @click.away="open = false" class="switcher-dropdown" style="display: none;">
                <?php foreach ($currencies as $currency) : ?>
                    <?php if (!$currency['is_current']) : ?>
                        <li>
                            <a href="<?php echo esc_url($currency['switch_url']); ?>" class="switcher-item">
                                <span><?php echo esc_html($currency['code']); ?></span>
                                <span class="text-gray-400 ml-2"><?php echo esc_html($currency['symbol']); ?></span>
                            </a>
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php else : ?>
        <ul class="switcher-list">
            <?php foreach ($currencies as $currency) : ?>
                <li class="<?php echo $currency['is_current'] ? 'current-currency' : ''; ?>">
                    <a href="<?php echo esc_url($currency['switch_url']); ?>">
                        <span><?php echo esc_html($currency['code']); ?></span>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</div>
