<?php
namespace AquaLuxe\Modules\DarkMode;

use function AquaLuxe\Core\asset_uri;

if (!defined('ABSPATH')) { exit; }

class DarkMode {
    public static function init(): void {
        \add_action('wp_enqueue_scripts', [__CLASS__, 'enqueue']);
    }
    public static function enqueue(): void {
        // Dark mode is in main app.js; just ensure a class on HTML based on preference pre-hydration.
        \add_action('wp_head', function () {
            echo "<script>(function(){try{var e=localStorage.getItem('al_dark');if(e===null){e=window.matchMedia('(prefers-color-scheme: dark)').matches?'1':'0';}if(e==='1'){document.documentElement.classList.add('dark');}}catch(a){}})();</script>"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        }, 0);
    }
}

function module(): DarkMode { return new DarkMode(); }
