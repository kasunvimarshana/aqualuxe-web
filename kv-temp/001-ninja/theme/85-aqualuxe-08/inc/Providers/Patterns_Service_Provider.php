<?php
namespace Aqualuxe\Providers;

use Aqualuxe\Support\Container;

class Patterns_Service_Provider
{
    public function register(Container $c): void
    {
        if (\function_exists('add_action')) {
            \call_user_func('add_action', 'init', [$this, 'register_patterns']);
        }
    }
    public function boot(Container $c): void {}

    public function register_patterns(): void
    {
        if (!\function_exists('register_block_pattern')) { return; }

        // Local translation helper to avoid undefined-function warnings when WP isn't loaded
        $__ = static function (string $text): string {
            if (\function_exists('__')) {
                return \call_user_func('__', $text, 'aqualuxe');
            }
            return $text;
        };

        if (\function_exists('register_block_pattern_category')) {
            \call_user_func('register_block_pattern_category', 'aqualuxe', [
                'label' => $__('AquaLuxe'),
            ]);
        }

        // Hero Banner
        \call_user_func('register_block_pattern', 'aqualuxe/hero', [
            'title' => $__('Hero Banner'),
            'categories' => ['aqualuxe'],
            'content' => '<!-- wp:cover {"dimRatio":40,"isDark":false} --><div class="wp-block-cover"><span aria-hidden="true" class="wp-block-cover__background has-background-dim"></span><div class="wp-block-cover__inner-container"><!-- wp:heading {"textAlign":"center","level":1} --><h1 class="has-text-align-center">Bringing elegance to aquatic life \u2013 globally.</h1><!-- /wp:heading --><!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} --><div class="wp-block-buttons"><!-- wp:button {"className":"is-style-fill"} --><div class="wp-block-button is-style-fill"><a class="wp-block-button__link" href="/shop">Shop Now</a></div><!-- /wp:button --><!-- wp:button {"className":"is-style-outline"} --><div class="wp-block-button is-style-outline"><a class="wp-block-button__link" href="/export">Request a Quote</a></div><!-- /wp:button --></div><!-- /wp:buttons --></div></div><!-- /wp:cover -->',
        ]);

        // CTA Links
        \call_user_func('register_block_pattern', 'aqualuxe/cta-links', [
            'title' => $__('CTA Links'),
            'categories' => ['aqualuxe'],
            'content' => '<!-- wp:columns --><div class="wp-block-columns"><!-- wp:column --><div class="wp-block-column"><!-- wp:heading {"level":3} --><h3>Wholesale & B2B</h3><!-- /wp:heading --><!-- wp:paragraph --><p>Bulk pricing and logistics support.</p><!-- /wp:paragraph --><!-- wp:buttons --><div class="wp-block-buttons"><!-- wp:button --><div class="wp-block-button"><a class="wp-block-button__link" href="/wholesale-b2b">Apply</a></div><!-- /wp:button --></div><!-- /wp:buttons --></div><!-- /wp:column --><!-- wp:column --><div class="wp-block-column"><!-- wp:heading {"level":3} --><h3>Export</h3><!-- /wp:heading --><!-- wp:paragraph --><p>Global export with certifications.</p><!-- /wp:paragraph --><!-- wp:buttons --><div class="wp-block-buttons"><!-- wp:button --><div class="wp-block-button"><a class="wp-block-button__link" href="/export">Get Quote</a></div><!-- /wp:button --></div><!-- /wp:buttons --></div><!-- /wp:column --><!-- wp:column --><div class="wp-block-column"><!-- wp:heading {"level":3} --><h3>Services</h3><!-- /wp:heading --><!-- wp:paragraph --><p>Design, installation, maintenance.</p><!-- /wp:paragraph --><!-- wp:buttons --><div class="wp-block-buttons"><!-- wp:button --><div class="wp-block-button"><a class="wp-block-button__link" href="/services">Book</a></div><!-- /wp:button --></div><!-- /wp:buttons --></div><!-- /wp:column --></div><!-- /wp:columns -->',
        ]);

        // Hero (Dark) with actionable links
        \call_user_func('register_block_pattern', 'aqualuxe/hero-dark', [
            'title' => $__('Hero Banner (Dark)'),
            'categories' => ['aqualuxe'],
            'content' => '<!-- wp:cover {"dimRatio":70,"isDark":true} --><div class="wp-block-cover is-dark"><span aria-hidden="true" class="wp-block-cover__background has-background-dim"></span><div class="wp-block-cover__inner-container"><!-- wp:heading {"textAlign":"center","level":1} --><h1 class="has-text-align-center">AquaLuxe — premium aquaria reimagined</h1><!-- /wp:heading --><!-- wp:paragraph {"align":"center"} --><p class="has-text-align-center">Retail • Wholesale • Export • Services</p><!-- /wp:paragraph --><!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} --><div class="wp-block-buttons"><!-- wp:button {"className":"is-style-fill"} --><div class="wp-block-button is-style-fill"><a class="wp-block-button__link" href="/shop">Explore</a></div><!-- /wp:button --><!-- wp:button {"className":"is-style-outline"} --><div class="wp-block-button is-style-outline"><a class="wp-block-button__link" href="/contact">Contact</a></div><!-- /wp:button --></div><!-- /wp:buttons --></div></div><!-- /wp:cover -->',
        ]);

        // Feature Grid (3-up)
        \call_user_func('register_block_pattern', 'aqualuxe/feature-grid', [
            'title' => $__('Feature Grid (3)'),
            'categories' => ['aqualuxe'],
            'content' => '<!-- wp:columns --><div class="wp-block-columns"><!-- wp:column --><div class="wp-block-column"><!-- wp:image {"sizeSlug":"large","linkDestination":"none"} --><figure class="wp-block-image size-large"><img alt=""/></figure><!-- /wp:image --><!-- wp:heading {"level":3} --><h3>Quality & Quarantine</h3><!-- /wp:heading --><!-- wp:paragraph --><p>Best-in-class health protocols for livestock.</p><!-- /wp:paragraph --></div><!-- /wp:column --><!-- wp:column --><div class="wp-block-column"><!-- wp:image {"sizeSlug":"large","linkDestination":"none"} --><figure class="wp-block-image size-large"><img alt=""/></figure><!-- /wp:image --><!-- wp:heading {"level":3} --><h3>Design & Build</h3><!-- /wp:heading --><!-- wp:paragraph --><p>Bespoke aquariums and aquascapes.</p><!-- /wp:paragraph --></div><!-- /wp:column --><!-- wp:column --><div class="wp-block-column"><!-- wp:image {"sizeSlug":"large","linkDestination":"none"} --><figure class="wp-block-image size-large"><img alt=""/></figure><!-- /wp:image --><!-- wp:heading {"level":3} --><h3>Global Logistics</h3><!-- /wp:heading --><!-- wp:paragraph --><p>Certified exports with compliance.</p><!-- /wp:paragraph --></div><!-- /wp:column --></div><!-- /wp:columns -->',
        ]);
    }
}
