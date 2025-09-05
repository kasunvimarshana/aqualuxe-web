<?php
namespace AquaLuxe\Modules;

use AquaLuxe\Core\Contracts\ModuleInterface;
use AquaLuxe\Core\Services\TradeInService;

/**
 * Trade-Ins Module.
 *
 * Manages the trade-in request system.
 */
class TradeIns implements ModuleInterface
{
    private ?TradeInService $tradein_service = null;

    public function boot(): void
    {
        $this->tradein_service = new TradeInService();

        \add_action('init', [$this->tradein_service, 'register_cpt']);
        \add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
        
        \add_shortcode('aqualuxe_trade_in_form', [$this, 'render_form_shortcode']);

        // AJAX handler for form submission
        \add_action('wp_ajax_aqualuxe_submit_trade_in', [$this, 'handle_ajax_submission']);
        \add_action('wp_ajax_nopriv_aqualuxe_submit_trade_in', [$this, 'handle_ajax_submission']);
    }

    public function enqueue_assets(): void
    {
        // Enqueue CSS
        $css_path = '/modules/trade-ins/assets/dist/trade-ins.css';
        if (file_exists(AQUALUXE_DIR . $css_path)) {
            \wp_enqueue_style(
                'aqualuxe-trade-ins',
                AQUALUXE_URI . $css_path,
                [],
                filemtime(AQUALUXE_DIR . $css_path)
            );
        }

        // Enqueue JS, only when the shortcode is present
        if (is_singular() && has_shortcode(\get_the_content(), 'aqualuxe_trade_in_form')) {
            $js_path = '/modules/trade-ins/assets/dist/trade-ins.js';
            \wp_enqueue_script(
                'aqualuxe-trade-ins',
                AQUALUXE_URI . $js_path,
                ['jquery'],
                filemtime(AQUALUXE_DIR . $js_path),
                true
            );
            \wp_localize_script('aqualuxe-trade-ins', 'aqualuxeTradeIn', [
                'ajax_url' => \admin_url('admin-ajax.php'),
                'nonce'    => \wp_create_nonce('aqualuxe_trade_in_nonce'),
            ]);
        }
    }

    /**
     * Renders the [aqualuxe_trade_in_form] shortcode.
     */
    public function render_form_shortcode(): string
    {
        ob_start();
        \get_template_part('modules/trade-ins/templates/trade-in-form');
        return ob_get_clean();
    }

    /**
     * Handles the AJAX submission of the trade-in form.
     */
    public function handle_ajax_submission(): void
    {
        check_ajax_referer('aqualuxe_trade_in_nonce', 'nonce');

        $required_fields = ['name', 'email', 'product_name', 'condition', 'message'];
        $form_data = [];

        foreach ($required_fields as $field) {
            if (empty($_POST[$field])) {
                \wp_send_json_error(['message' => "Field '{$field}' is required."], 400);
            }
            $form_data[$field] = \sanitize_text_field(wp_unslash($_POST[$field]));
        }
        
        if (!\is_email($form_data['email'])) {
            \wp_send_json_error(['message' => 'Invalid email address.'], 400);
        }

        $result = $this->tradein_service->create_request($form_data);

        if (\is_wp_error($result)) {
            \wp_send_json_error(['message' => $result->get_error_message()], 500);
        }

        \wp_send_json_success(['message' => 'Your trade-in request has been submitted successfully!']);
    }
}
