<?php
/**
 * Template part for displaying the language switcher
 *
 * @package AquaLuxe
 */

// Check if multilingual support is enabled and a compatible plugin is active
$multilingual_enabled = get_theme_mod( 'aqualuxe_multilingual_enable', true );

// Check for WPML
$wpml_active = function_exists( 'icl_get_languages' );

// Check for Polylang
$polylang_active = function_exists( 'pll_the_languages' );

// If multilingual is disabled or no compatible plugin is active, return
if ( ! $multilingual_enabled || ( ! $wpml_active && ! $polylang_active ) ) {
    return;
}
?>

<div class="language-switcher ml-4">
    <div class="language-switcher-wrapper relative">
        <button id="language-switcher-toggle" class="language-switcher-toggle flex items-center" aria-expanded="false" aria-haspopup="true">
            <span class="current-language flex items-center">
                <?php 
                // Display current language
                if ( $wpml_active ) {
                    $languages = apply_filters( 'wpml_active_languages', NULL, array( 'skip_missing' => 0 ) );
                    if ( ! empty( $languages ) ) {
                        foreach ( $languages as $language ) {
                            if ( $language['active'] ) {
                                if ( ! empty( $language['country_flag_url'] ) ) {
                                    echo '<img src="' . esc_url( $language['country_flag_url'] ) . '" alt="' . esc_attr( $language['language_code'] ) . '" class="w-4 h-4 mr-1">';
                                }
                                echo esc_html( $language['language_code'] );
                            }
                        }
                    }
                } elseif ( $polylang_active ) {
                    $current_lang = pll_current_language( 'slug' );
                    echo esc_html( strtoupper( $current_lang ) );
                }
                ?>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </span>
        </button>
        
        <div id="language-switcher-dropdown" class="language-switcher-dropdown absolute right-0 mt-2 py-2 w-32 bg-white dark:bg-gray-800 rounded-md shadow-lg z-50 hidden">
            <?php
            // Display language options
            if ( $wpml_active ) {
                $languages = apply_filters( 'wpml_active_languages', NULL, array( 'skip_missing' => 0 ) );
                if ( ! empty( $languages ) ) {
                    echo '<ul class="language-list">';
                    foreach ( $languages as $language ) {
                        $class = $language['active'] ? 'active' : '';
                        echo '<li class="' . esc_attr( $class ) . '">';
                        echo '<a href="' . esc_url( $language['url'] ) . '" class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700">';
                        if ( ! empty( $language['country_flag_url'] ) ) {
                            echo '<img src="' . esc_url( $language['country_flag_url'] ) . '" alt="' . esc_attr( $language['language_code'] ) . '" class="w-4 h-4 inline-block mr-1">';
                        }
                        echo esc_html( $language['native_name'] );
                        echo '</a>';
                        echo '</li>';
                    }
                    echo '</ul>';
                }
            } elseif ( $polylang_active ) {
                $args = array(
                    'show_flags' => 1,
                    'show_names' => 1,
                    'dropdown'   => 0,
                    'echo'       => 0,
                );
                $languages = pll_the_languages( $args );
                echo $languages; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            }
            ?>
        </div>
    </div>
</div>