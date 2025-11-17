<?php

declare(strict_types=1);

namespace AquaLuxe\Core\Modules\Importer;

use AquaLuxe\Core\Helpers;

final class Service
{
    private const JOB_KEY = 'aqualuxe_import_job';

    public static function flush(array $scope): array
    {
        $report = [];

        if (! empty($scope['posts'])) {
            $report['posts'] = self::delete_posts(['post', 'page', 'service', 'event', 'product']);
        }
        if (! empty($scope['media'])) {
            $report['media'] = self::delete_attachments();
        }
        if (! empty($scope['tax'])) {
            $report['tax'] = self::delete_taxonomies(['category', 'post_tag', 'service_cat', 'product_cat', 'product_tag']);
        }

        return ['ok' => true, 'report' => $report];
    }

    public static function start(array $args): array
    {
        $defaults = [
            'volume' => 25,
            'locale' => 'en_US',
            'include_products' => true,
        ];
        $cfg = array_merge($defaults, $args);
        $job = [
            'id' => wp_generate_password(12, false),
            'total' => (int) $cfg['volume'],
            'done' => 0,
            'steps' => [
                'pages' => true,
                'posts' => true,
                'services' => true,
                'events' => true,
                'products' => (bool) $cfg['include_products'],
            ],
            'log' => [],
        ];
        set_transient(self::JOB_KEY, $job, HOUR_IN_SECONDS);
        return ['ok' => true, 'job' => $job];
    }

    public static function step(): array
    {
        $job = get_transient(self::JOB_KEY);
        if (! is_array($job)) {
            return ['ok' => false, 'error' => 'no_job'];
        }

        if ($job['done'] >= $job['total']) {
            return ['ok' => true, 'job' => $job, 'complete' => true];
        }

        // Create one item per step to allow progress UI to update smoothly.
        $created = self::create_demo_entity($job);
        $job['done'] += 1;
        $job['log'][] = $created['type'] . ':' . $created['id'];
        set_transient(self::JOB_KEY, $job, HOUR_IN_SECONDS);
        return ['ok' => true, 'job' => $job, 'entity' => $created, 'complete' => $job['done'] >= $job['total']];
    }

    private static function create_demo_entity(array $job): array
    {
        // Rotate among enabled steps for diversity.
        $types = array_keys(array_filter($job['steps']));
        if (empty($types)) {
            $types = ['posts'];
        }
        $pick = $types[array_rand($types)];
        switch ($pick) {
            case 'pages':
                $id = wp_insert_post([
                    'post_type' => 'page',
                    'post_status' => 'publish',
                    'post_title' => self::lipsum('AquaLuxe Page'),
                    'post_content' => self::rich_text(),
                ]);
                return ['type' => 'page', 'id' => (int) $id];
            case 'services':
                $id = wp_insert_post([
                    'post_type' => 'service',
                    'post_status' => 'publish',
                    'post_title' => self::lipsum('Aquarium Design'),
                    'post_content' => self::rich_text(),
                ]);
                if ($id && ! is_wp_error($id)) {
                    wp_set_object_terms($id, ['Design'], 'service_cat', true);
                }
                return ['type' => 'service', 'id' => (int) $id];
            case 'events':
                $id = wp_insert_post([
                    'post_type' => 'event',
                    'post_status' => 'publish',
                    'post_title' => self::lipsum('Aquascaping Workshop'),
                    'post_content' => self::rich_text(),
                ]);
                return ['type' => 'event', 'id' => (int) $id];
            case 'products':
                if (class_exists('WooCommerce')) {
                    $id = wp_insert_post([
                        'post_type' => 'product',
                        'post_status' => 'publish',
                        'post_title' => self::lipsum('Premium Koi'),
                        'post_content' => self::rich_text(),
                    ]);
                    if (! is_wp_error($id) && $id) {
                        update_post_meta($id, '_price', '199.00');
                        update_post_meta($id, '_regular_price', '199.00');
                        wp_set_object_terms($id, 'simple', 'product_type');
                    }
                    return ['type' => 'product', 'id' => (int) $id];
                }
                // Fallback to post if WooCommerce absent.
                // no break
            default:
                $id = wp_insert_post([
                    'post_type' => 'post',
                    'post_status' => 'publish',
                    'post_title' => self::lipsum('Aquatic News'),
                    'post_content' => self::rich_text(),
                ]);
                return ['type' => 'post', 'id' => (int) $id];
        }
    }

    private static function lipsum(string $prefix): string
    {
        $samples = [
            'Elegance in every ripple',
            'Refined aquatic living',
            'Sustainable beauty underwater',
            'Global aquatic excellence',
        ];
        return $prefix . ' — ' . $samples[array_rand($samples)];
    }

    private static function rich_text(): string
    {
        return wp_kses_post('<p>We craft exquisite aquatic experiences with ethical sourcing and premium care. Discover rare species, immersive aquascapes, and tailored services.</p><ul><li>Premium livestock</li><li>Custom aquariums</li><li>Sustainable practices</li></ul>');
    }

    private static function delete_posts(array $types): int
    {
        $count = 0;
        foreach ($types as $pt) {
            $q = new \WP_Query(['post_type' => $pt, 'posts_per_page' => -1, 'post_status' => 'any', 'fields' => 'ids']);
            foreach ($q->posts as $pid) {
                wp_delete_post((int) $pid, true);
                $count++;
            }
        }
        return $count;
    }

    private static function delete_attachments(): int
    {
        $q = new \WP_Query(['post_type' => 'attachment', 'posts_per_page' => -1, 'post_status' => 'any', 'fields' => 'ids']);
        $count = 0;
    foreach ($q->posts as $pid) { Helpers::wp('wp_delete_attachment', [(int) $pid, true]); $count++; }
        return $count;
    }

    private static function delete_taxonomies(array $taxes): int
    {
        $count = 0;
        foreach ($taxes as $tax) {
            $terms = get_terms(['taxonomy' => $tax, 'hide_empty' => false]);
            if (is_wp_error($terms)) { continue; }
            foreach ($terms as $t) {
                wp_delete_term($t->term_id, $tax);
                $count++;
            }
        }
        return $count;
    }
}
