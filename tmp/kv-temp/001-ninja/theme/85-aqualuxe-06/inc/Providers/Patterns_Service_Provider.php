<?php
namespace Aqualuxe\Providers;

use Aqualuxe\Support\Container;

class Patterns_Service_Provider
{
    public function register(Container $c): void
    {
        \add_action('init', [$this, 'register_patterns']);
    }
    public function boot(Container $c): void {}

    public function register_patterns(): void
    {
        if (!\function_exists('register_block_pattern')) { return; }

        if (\function_exists('register_block_pattern_category')) {
            \register_block_pattern_category('aqualuxe', [
                'label' => \__('AquaLuxe', 'aqualuxe'),
            ]);
        }

        // Hero Banner
        \register_block_pattern('aqualuxe/hero', [
            'title' => \__('Hero Banner', 'aqualuxe'),
            'categories' => ['aqualuxe'],
            'content' => '<!-- wp:cover {"dimRatio":40,"isDark":false} --><div class="wp-block-cover"><span aria-hidden="true" class="wp-block-cover__background has-background-dim"></span><div class="wp-block-cover__inner-container"><!-- wp:heading {"textAlign":"center","level":1} --><h1 class="has-text-align-center">Bringing elegance to aquatic life – globally.</h1><!-- /wp:heading --><!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} --><div class="wp-block-buttons"><!-- wp:button {"className":"is-style-fill"} --><div class="wp-block-button is-style-fill"><a class="wp-block-button__link">Shop Now</a></div><!-- /wp:button --><!-- wp:button {"className":"is-style-outline"} --><div class="wp-block-button is-style-outline"><a class="wp-block-button__link">Request a Quote</a></div><!-- /wp:button --></div><!-- /wp:buttons --></div></div><!-- /wp:cover -->',
        ]);

        // CTA Links
        \register_block_pattern('aqualuxe/cta-links', [
            'title' => \__('CTA Links', 'aqualuxe'),
            'categories' => ['aqualuxe'],
            'content' => '<!-- wp:columns --><div class="wp-block-columns"><!-- wp:column --><div class="wp-block-column"><!-- wp:heading {"level":3} --><h3>Wholesale & B2B</h3><!-- /wp:heading --><!-- wp:paragraph --><p>Bulk pricing and logistics support.</p><!-- /wp:paragraph --><!-- wp:buttons --><div class="wp-block-buttons"><!-- wp:button --><div class="wp-block-button"><a class="wp-block-button__link">Apply</a></div><!-- /wp:button --></div><!-- /wp:buttons --></div><!-- /wp:column --><!-- wp:column --><div class="wp-block-column"><!-- wp:heading {"level":3} --><h3>Export</h3><!-- /wp:heading --><!-- wp:paragraph --><p>Global export with certifications.</p><!-- /wp:paragraph --><!-- wp:buttons --><div class="wp-block-buttons"><!-- wp:button --><div class="wp-block-button"><a class="wp-block-button__link">Get Quote</a></div><!-- /wp:button --></div><!-- /wp:buttons --></div><!-- /wp:column --><!-- wp:column --><div class="wp-block-column"><!-- wp:heading {"level":3} --><h3>Services</h3><!-- /wp:heading --><!-- wp:paragraph --><p>Design, installation, maintenance.</p><!-- /wp:paragraph --><!-- wp:buttons --><div class="wp-block-buttons"><!-- wp:button --><div class="wp-block-button"><a class="wp-block-button__link">Book</a></div><!-- /wp:button --></div><!-- /wp:buttons --></div><!-- /wp:column --></div><!-- /wp:columns -->',
        ]);
    }
}
