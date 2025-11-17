<?php
/**
 * AquaLuxe Dark Mode Module
 *
 * Namespace-compliant module loaded by the Bootstrap. Handles:
 * - Early theme preference application (avoid FOUC)
 * - Accessible toggle button
 * - Persistence via localStorage with cookie fallback
 *
 * @package AquaLuxe\Modules\DarkMode
 * @since 2.0.0
 */

namespace AquaLuxe\Modules\DarkMode;

use AquaLuxe\Core\Bootstrap;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Module {
    /** @var Bootstrap */
    private $bootstrap;

    public function __construct( Bootstrap $bootstrap ) {
        $this->bootstrap = $bootstrap;

    \add_action( 'wp_head', [ $this, 'output_early_script' ], 0 );
    \add_filter( 'body_class', [ $this, 'filter_body_class' ] );
    \add_action( 'wp_footer', [ $this, 'render_toggle' ], 99 );
    }

    /**
     * Output a tiny inline script in <head> to apply the preferred theme before paint.
     */
    public function output_early_script(): void {
        // phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
        echo "<script>(function(){try{var d=document.documentElement;var k='aqualuxe:theme';var v=localStorage.getItem(k);var m=window.matchMedia&&window.matchMedia('(prefers-color-scheme: dark)').matches;var t=v?v:(m?'dark':'light');if(t==='dark'){d.classList.add('dark');}else{d.classList.remove('dark');}}catch(e){/* noop */}})();</script>";
        // phpcs:enable
    }

    /**
     * Add body class for dark mode (progressive enhancement fallback).
     *
     * @param array $classes
     * @return array
     */
    public function filter_body_class( array $classes ): array {
        // Let CSS rely on .dark at root; body class is auxiliary
        return $classes;
    }

    /**
     * Render an accessible dark mode toggle button in the footer.
     */
    public function render_toggle(): void {
        // Allow themes to disable the auto-rendered toggle when providing their own.
        if ( false === \apply_filters( 'aqualuxe_dark_mode_render_toggle', true ) ) {
            return;
        }
        echo '<button type="button" id="aqualuxe-dark-toggle" class="aqlx-dark-toggle" aria-pressed="false" aria-label="Toggle dark mode" data-dark-toggle>
            <span class="aqlx-dark-toggle__icon" aria-hidden="true">🌙</span>
            <span class="aqlx-dark-toggle__label">'. \esc_html__( 'Dark Mode', 'aqualuxe' ) .'</span>
        </button>';
    }
}
