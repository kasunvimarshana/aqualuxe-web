<?php
namespace AquaLuxe\Modules\Multilingual;

class Module
{
    public function boot(): void
    {
        // Placeholder: Integrate with Polylang/WPML if present, otherwise provide a minimal language switcher based on site languages.
        \add_action('wp_footer', [$this, 'render_switcher']);
    }

    public function render_switcher(): void
    {
        if (! function_exists('pll_the_languages')) { return; }
        echo '<div class="fixed bottom-4 right-4 bg-white/80 dark:bg-slate-800/80 backdrop-blur rounded px-3 py-2 text-sm">';
        pll_the_languages(['dropdown'=>1]);
        echo '</div>';
    }
}
