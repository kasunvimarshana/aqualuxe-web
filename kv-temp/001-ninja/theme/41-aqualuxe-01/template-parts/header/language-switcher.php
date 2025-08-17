<?php
/**
 * Template part for displaying the language switcher in the header
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

// Only display if multilingual is active
if ( ! aqualuxe_is_multilingual_active() ) {
    return;
}

// Get available languages
$languages = aqualuxe_get_available_languages();
$current_language = aqualuxe_get_current_language();

// If no languages or only one language, don't show switcher
if ( empty( $languages ) || count( $languages ) <= 1 ) {
    return;
}
?>

<div class="language-switcher relative" x-data="{ languageOpen: false }">
    <button 
        @click="languageOpen = !languageOpen" 
        class="flex items-center justify-center space-x-1 px-3 py-2 rounded-md hover:bg-gray-100 dark:hover:bg-dark-700 focus:outline-none focus:ring-2 focus:ring-primary-500 transition-colors"
        aria-label="<?php esc_attr_e( 'Switch language', 'aqualuxe' ); ?>"
    >
        <span class="text-dark-700 dark:text-white text-sm font-medium">
            <?php echo esc_html( $current_language['native_name'] ?? $current_language['code'] ); ?>
        </span>
        <svg class="w-4 h-4 text-dark-500 dark:text-dark-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
    </button>
    
    <!-- Language dropdown -->
    <div 
        x-cloak
        x-show="languageOpen" 
        @click.away="languageOpen = false"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="absolute right-0 mt-2 w-48 bg-white dark:bg-dark-800 rounded-lg shadow-lg z-50"
    >
        <div class="py-2">
            <?php foreach ( $languages as $code => $language ) : ?>
                <?php
                $is_current = $code === $current_language['code'];
                $url = $language['url'] ?? aqualuxe_get_language_url( $code );
                ?>
                <a 
                    href="<?php echo esc_url( $url ); ?>" 
                    class="flex items-center justify-between px-4 py-2 text-sm <?php echo $is_current ? 'bg-gray-100 dark:bg-dark-700 text-primary-600 dark:text-primary-400' : 'text-dark-700 dark:text-dark-300 hover:bg-gray-50 dark:hover:bg-dark-700'; ?>"
                    <?php echo $is_current ? 'aria-current="true"' : ''; ?>
                >
                    <span><?php echo esc_html( $language['native_name'] ); ?></span>
                    <?php if ( $is_current ) : ?>
                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    <?php endif; ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</div>