<?php
/**
 * Template part for displaying the dark mode toggle
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;
?>

<div x-data class="dark-mode-toggle">
    <button 
        @click="$store.theme.toggleDarkMode()" 
        class="flex items-center justify-center w-10 h-10 rounded-full hover:bg-gray-100 dark:hover:bg-dark-700 focus:outline-none focus:ring-2 focus:ring-primary-500 transition-colors"
        aria-label="<?php esc_attr_e( 'Toggle dark mode', 'aqualuxe' ); ?>"
    >
        <!-- Sun icon (shown in dark mode) -->
        <svg 
            x-cloak
            x-show="$store.theme.darkMode" 
            class="w-5 h-5 text-yellow-400" 
            xmlns="http://www.w3.org/2000/svg" 
            fill="none" 
            viewBox="0 0 24 24" 
            stroke="currentColor"
        >
            <path 
                stroke-linecap="round" 
                stroke-linejoin="round" 
                stroke-width="2" 
                d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" 
            />
        </svg>
        
        <!-- Moon icon (shown in light mode) -->
        <svg 
            x-cloak
            x-show="!$store.theme.darkMode" 
            class="w-5 h-5 text-dark-700" 
            xmlns="http://www.w3.org/2000/svg" 
            fill="none" 
            viewBox="0 0 24 24" 
            stroke="currentColor"
        >
            <path 
                stroke-linecap="round" 
                stroke-linejoin="round" 
                stroke-width="2" 
                d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" 
            />
        </svg>
    </button>
</div>