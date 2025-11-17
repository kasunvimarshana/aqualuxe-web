<?php
namespace AquaLuxe\Modules;

use AquaLuxe\Core\Contracts\ModuleInterface;
use AquaLuxe\Core\Services\AffiliateService;

/**
 * Affiliate Module.
 *
 * Manages the affiliate dashboard and referral tools.
 */
class Affiliate implements ModuleInterface
{
    private ?AffiliateService $affiliate_service = null;

    public function boot(): void
    {
        $this->affiliate_service = new AffiliateService();

        if (!$this->affiliate_service->is_active()) {
            return;
        }

        \add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
        
        // Shortcode to display the affiliate dashboard
        \add_shortcode('aqualuxe_affiliate_dashboard', [$this, 'render_dashboard_shortcode']);
    }

    public function enqueue_assets(): void
    {
        // Enqueue CSS
        $css_path = '/modules/affiliate/assets/dist/affiliate.css';
        if (file_exists(AQUALUXE_DIR . $css_path)) {
            \wp_enqueue_style(
                'aqualuxe-affiliate',
                AQUALUXE_URI . $css_path,
                [],
                filemtime(AQUALUXE_DIR . $css_path)
            );
        }

        // Enqueue JS for dashboard interactions (e.g., copy to clipboard)
        if (is_singular() && has_shortcode(\get_the_content(), 'aqualuxe_affiliate_dashboard')) {
            $js_path = '/modules/affiliate/assets/dist/affiliate.js';
            \wp_enqueue_script(
                'aqualuxe-affiliate',
                AQUALUXE_URI . $js_path,
                ['jquery'],
                filemtime(AQUALUXE_DIR . $js_path),
                true
            );
        }
    }

    /**
     * Renders the [aqualuxe_affiliate_dashboard] shortcode.
     */
    public function render_dashboard_shortcode(): string
    {
        if (!\is_user_logged_in()) {
            return '<p>' . \sprintf(
                \__('You must be <a href="%s">logged in</a> to view the affiliate dashboard.', 'aqualuxe'),
                \wp_login_url(\get_permalink())
            ) . '</p>';
        }

        if (!$this->affiliate_service->is_affiliate()) {
            return '<p>' . \sprintf(
                \__('This content is for affiliates only. <a href="%s">Join our affiliate program</a>.', 'aqualuxe'),
                $this->affiliate_service->get_affiliate_area_url()
            ) . '</p>';
        }

        $stats = $this->affiliate_service->get_affiliate_stats();
        $referral_url = $this->affiliate_service->get_referral_url();

        ob_start();
        // Pass data to the template
        \set_query_var('stats', $stats);
        \set_query_var('referral_url', $referral_url);
        \get_template_part('modules/affiliate/templates/affiliate-dashboard');
        return ob_get_clean();
    }
}
