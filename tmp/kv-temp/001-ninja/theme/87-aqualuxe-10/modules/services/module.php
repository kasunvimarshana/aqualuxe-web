<?php
namespace AquaLuxe\Modules;

use AquaLuxe\Core\Contracts\ModuleInterface;
use AquaLuxe\Core\Services\ConsultationService;

/**
 * Services Module.
 *
 * Manages the 'service' CPT and its presentation.
 */
class Services implements ModuleInterface
{
    private ?ConsultationService $consultation_service = null;

    public function boot(): void
    {
        $this->consultation_service = new ConsultationService();

        \add_action('init', [$this->consultation_service, 'register_cpt_and_taxonomy']);
        \add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
        
        \add_shortcode('aqualuxe_services_list', [$this, 'render_services_list_shortcode']);

        // Use our own template for the single service page
        \add_filter('single_template', [$this, 'override_single_template']);
    }

    public function enqueue_assets(): void
    {
        $css_path = '/modules/services/assets/dist/services.css';
        if (file_exists(AQUALUXE_DIR . $css_path)) {
            \wp_enqueue_style(
                'aqualuxe-services',
                AQUALUXE_URI . $css_path,
                [],
                filemtime(AQUALUXE_DIR . $css_path)
            );
        }
    }

    /**
     * Renders the [aqualuxe_services_list] shortcode.
     *
     * @param array $atts Shortcode attributes.
     * @return string Rendered HTML.
     */
    public function render_services_list_shortcode($atts): string
    {
        $atts = \shortcode_atts([
            'count'    => -1,
            'category' => '',
            'columns'  => 3,
        ], $atts, 'aqualuxe_services_list');

        $query_args = [
            'post_type'      => ConsultationService::CPT_SLUG,
            'posts_per_page' => (int) $atts['count'],
        ];

        if (!empty($atts['category'])) {
            $query_args['tax_query'] = [
                [
                    'taxonomy' => ConsultationService::TAXONOMY_SLUG,
                    'field'    => 'slug',
                    'terms'    => $atts['category'],
                ],
            ];
        }

        $services_query = new \WP_Query($query_args);

        if (!$services_query->have_posts()) {
            return '<p>' . \__('No services found.', 'aqualuxe') . '</p>';
        }

        ob_start();
        ?>
        <div class="aqualuxe-services-list grid grid-cols-1 md:grid-cols-<?php echo esc_attr($atts['columns']); ?> gap-8">
            <?php
            while ($services_query->have_posts()) {
                $services_query->the_post();
                \get_template_part('modules/services/templates/service-summary');
            }
            \wp_reset_postdata();
            ?>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * Overrides the default single post template for the 'service' CPT.
     *
     * @param string $template The path to the template file.
     * @return string The modified template path.
     */
    public function override_single_template(string $template): string
    {
        if (is_singular(ConsultationService::CPT_SLUG)) {
            $new_template = \locate_template(['modules/services/templates/service-single.php']);
            if (!empty($new_template)) {
                return $new_template;
            }
        }
        return $template;
    }
}
