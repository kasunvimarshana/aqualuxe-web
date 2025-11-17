<?php
/**
 * Service Schema Class
 *
 * @package AquaLuxe
 * @subpackage Modules\Services\Inc
 * @since 1.0.0
 */

namespace AquaLuxe\Modules\Services\Inc;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Service Schema Class
 * 
 * This class handles schema markup for services.
 */
class Service_Schema {
    /**
     * Constructor
     */
    public function __construct() {
        $this->init_hooks();
    }

    /**
     * Initialize hooks
     *
     * @return void
     */
    private function init_hooks() {
        // Add schema markup to head
        add_action( 'wp_head', [ $this, 'add_service_schema' ] );
        
        // Add schema markup to service archive
        add_action( 'wp_footer', [ $this, 'add_service_list_schema' ] );
    }

    /**
     * Add service schema
     *
     * @return void
     */
    public function add_service_schema() {
        if ( ! is_singular( 'aqualuxe_service' ) ) {
            return;
        }

        global $post;
        
        // Create service object
        $service = new Service( $post->ID );
        $data = $service->get_data();
        
        if ( empty( $data ) ) {
            return;
        }

        // Create schema
        $schema = $this->generate_service_schema( $service );

        // Output schema
        if ( ! empty( $schema ) ) {
            echo '<script type="application/ld+json">' . wp_json_encode( $schema ) . '</script>';
        }
    }

    /**
     * Add service list schema
     *
     * @return void
     */
    public function add_service_list_schema() {
        if ( ! is_post_type_archive( 'aqualuxe_service' ) && ! is_tax( [ 'service_category', 'service_tag' ] ) ) {
            return;
        }

        global $wp_query;
        
        // Get services
        $services = [];
        foreach ( $wp_query->posts as $post ) {
            if ( 'aqualuxe_service' === $post->post_type ) {
                $services[] = new Service( $post->ID );
            }
        }
        
        if ( empty( $services ) ) {
            return;
        }

        // Create schema
        $schema = $this->generate_service_list_schema( $services );

        // Output schema
        if ( ! empty( $schema ) ) {
            echo '<script type="application/ld+json">' . wp_json_encode( $schema ) . '</script>';
        }
    }

    /**
     * Generate service schema
     *
     * @param Service $service
     * @return array
     */
    private function generate_service_schema( $service ) {
        $data = $service->get_data();
        
        if ( empty( $data ) ) {
            return [];
        }

        // Get organization info
        $organization = $this->get_organization_info();

        // Create schema
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Service',
            '@id' => $data['permalink'] . '#service',
            'name' => $data['title'],
            'description' => wp_strip_all_tags( $data['description'] ),
            'url' => $data['permalink'],
            'provider' => $organization,
        ];

        // Add image if available
        if ( ! empty( $data['thumbnail'] ) ) {
            $schema['image'] = $data['thumbnail'];
        }

        // Add service area if location is available
        if ( ! empty( $data['meta']['location'] ) ) {
            $schema['areaServed'] = [
                '@type' => 'Place',
                'name' => $data['meta']['location'],
            ];
        }

        // Add price if available
        if ( ! empty( $data['meta']['price'] ) ) {
            $schema['offers'] = [
                '@type' => 'Offer',
                'price' => $data['meta']['price'],
                'priceCurrency' => 'USD',
                'availability' => 'https://schema.org/InStock',
                'url' => $data['permalink'],
                'validFrom' => date( 'Y-m-d' ),
            ];

            // Add sale price if available
            if ( ! empty( $data['meta']['sale_price'] ) ) {
                $schema['offers']['price'] = $data['meta']['sale_price'];
                $schema['offers']['priceValidUntil'] = date( 'Y-m-d', strtotime( '+1 year' ) );
            }
        }

        // Add service type
        $categories = $service->get_categories();
        if ( ! empty( $categories ) ) {
            $schema['serviceType'] = $categories[0]->name;
        }

        // Add review aggregate if available
        $review_count = get_post_meta( $service->get_id(), '_aqualuxe_service_review_count', true );
        $review_rating = get_post_meta( $service->get_id(), '_aqualuxe_service_review_rating', true );
        
        if ( $review_count && $review_rating ) {
            $schema['aggregateRating'] = [
                '@type' => 'AggregateRating',
                'ratingValue' => $review_rating,
                'reviewCount' => $review_count,
            ];
        }

        return $schema;
    }

    /**
     * Generate service list schema
     *
     * @param array $services
     * @return array
     */
    private function generate_service_list_schema( $services ) {
        if ( empty( $services ) ) {
            return [];
        }

        // Get organization info
        $organization = $this->get_organization_info();

        // Create schema
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'ItemList',
            'itemListElement' => [],
        ];

        // Add services to list
        $position = 1;
        foreach ( $services as $service ) {
            $data = $service->get_data();
            
            if ( empty( $data ) ) {
                continue;
            }

            $service_schema = [
                '@type' => 'ListItem',
                'position' => $position,
                'item' => [
                    '@type' => 'Service',
                    'name' => $data['title'],
                    'description' => wp_strip_all_tags( $data['excerpt'] ? $data['excerpt'] : $data['description'] ),
                    'url' => $data['permalink'],
                    'provider' => $organization,
                ],
            ];

            // Add image if available
            if ( ! empty( $data['thumbnail'] ) ) {
                $service_schema['item']['image'] = $data['thumbnail'];
            }

            // Add price if available
            if ( ! empty( $data['meta']['price'] ) ) {
                $service_schema['item']['offers'] = [
                    '@type' => 'Offer',
                    'price' => $data['meta']['price'],
                    'priceCurrency' => 'USD',
                ];

                // Add sale price if available
                if ( ! empty( $data['meta']['sale_price'] ) ) {
                    $service_schema['item']['offers']['price'] = $data['meta']['sale_price'];
                }
            }

            $schema['itemListElement'][] = $service_schema;
            $position++;
        }

        return $schema;
    }

    /**
     * Get organization info
     *
     * @return array
     */
    private function get_organization_info() {
        // Get site info
        $site_name = get_bloginfo( 'name' );
        $site_url = get_site_url();
        $site_logo = $this->get_site_logo_url();

        // Create organization schema
        $organization = [
            '@type' => 'Organization',
            'name' => $site_name,
            'url' => $site_url,
        ];

        // Add logo if available
        if ( $site_logo ) {
            $organization['logo'] = $site_logo;
        }

        return $organization;
    }

    /**
     * Get site logo URL
     *
     * @return string
     */
    private function get_site_logo_url() {
        $custom_logo_id = get_theme_mod( 'custom_logo' );
        
        if ( $custom_logo_id ) {
            $logo_info = wp_get_attachment_image_src( $custom_logo_id, 'full' );
            if ( $logo_info ) {
                return $logo_info[0];
            }
        }

        return '';
    }

    /**
     * Generate service breadcrumb schema
     *
     * @param Service $service
     * @return array
     */
    public static function generate_breadcrumb_schema( $service ) {
        $data = $service->get_data();
        
        if ( empty( $data ) ) {
            return [];
        }

        // Get site info
        $site_name = get_bloginfo( 'name' );
        $site_url = get_site_url();

        // Create breadcrumb schema
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => [
                [
                    '@type' => 'ListItem',
                    'position' => 1,
                    'name' => $site_name,
                    'item' => $site_url,
                ],
                [
                    '@type' => 'ListItem',
                    'position' => 2,
                    'name' => __( 'Services', 'aqualuxe' ),
                    'item' => get_post_type_archive_link( 'aqualuxe_service' ),
                ],
            ],
        ];

        // Add category if available
        $categories = $service->get_categories();
        if ( ! empty( $categories ) ) {
            $schema['itemListElement'][] = [
                '@type' => 'ListItem',
                'position' => 3,
                'name' => $categories[0]->name,
                'item' => get_term_link( $categories[0] ),
            ];

            $schema['itemListElement'][] = [
                '@type' => 'ListItem',
                'position' => 4,
                'name' => $data['title'],
                'item' => $data['permalink'],
            ];
        } else {
            $schema['itemListElement'][] = [
                '@type' => 'ListItem',
                'position' => 3,
                'name' => $data['title'],
                'item' => $data['permalink'],
            ];
        }

        return $schema;
    }

    /**
     * Generate service FAQ schema
     *
     * @param array $faqs
     * @return array
     */
    public static function generate_faq_schema( $faqs ) {
        if ( empty( $faqs ) ) {
            return [];
        }

        // Create FAQ schema
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => [],
        ];

        foreach ( $faqs as $faq ) {
            $schema['mainEntity'][] = [
                '@type' => 'Question',
                'name' => $faq['question'],
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => $faq['answer'],
                ],
            ];
        }

        return $schema;
    }
}

// Initialize the class
new Service_Schema();