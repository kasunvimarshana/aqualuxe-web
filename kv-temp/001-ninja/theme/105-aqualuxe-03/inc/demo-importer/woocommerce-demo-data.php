<?php
/**
 * WooCommerce Demo Data Generator
 *
 * Generates comprehensive demo data for WooCommerce products with aquatic theme
 *
 * @package AquaLuxe\DemoImporter
 * @since 1.0.0
 */

namespace AquaLuxe\DemoImporter;

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class WooCommerceDemoData
 */
class WooCommerceDemoData {
    
    /**
     * Generate aquatic product data
     */
    public static function get_aquatic_products() {
        return array(
            // Rare Fish Species
            array(
                'name' => 'Platinum Arowana - Premium Grade',
                'description' => 'Exceptional platinum-colored Asian Arowana, known for its distinctive metallic sheen and graceful swimming pattern. This rare specimen represents the pinnacle of aquatic elegance.',
                'short_description' => 'Premium platinum Asian Arowana with exceptional coloration',
                'price' => 25000,
                'sale_price' => null,
                'category' => 'rare-fish',
                'tags' => array('rare', 'premium', 'arowana', 'platinum'),
                'images' => array('arowana-platinum-1.jpg', 'arowana-platinum-2.jpg'),
                'attributes' => array(
                    'size' => array('15-18 inches'),
                    'origin' => array('Southeast Asia'),
                    'temperament' => array('Peaceful'),
                    'water_type' => array('Freshwater')
                ),
                'stock_status' => 'instock',
                'stock_quantity' => 2,
                'featured' => true,
                'type' => 'simple'
            ),
            
            array(
                'name' => 'Mandarin Dragonet Pair',
                'description' => 'Beautiful matched pair of Mandarin Dragonets featuring vibrant orange, blue, and green coloration. These small, peaceful fish are perfect for reef aquariums.',
                'short_description' => 'Stunning matched pair with vibrant reef-safe coloration',
                'price' => 450,
                'sale_price' => 380,
                'category' => 'marine-fish',
                'tags' => array('marine', 'reef-safe', 'peaceful', 'colorful'),
                'images' => array('mandarin-dragonet-1.jpg', 'mandarin-dragonet-2.jpg'),
                'attributes' => array(
                    'size' => array('2-3 inches'),
                    'origin' => array('Indo-Pacific'),
                    'temperament' => array('Peaceful'),
                    'water_type' => array('Saltwater')
                ),
                'stock_status' => 'instock',
                'stock_quantity' => 8,
                'featured' => true,
                'type' => 'simple'
            ),

            // Aquatic Plants
            array(
                'name' => 'Premium Aquascape Plant Collection',
                'description' => 'Carefully curated collection of premium aquatic plants perfect for creating stunning aquascapes. Includes foreground, midground, and background species.',
                'short_description' => 'Complete aquascaping plant collection for professionals',
                'price' => 0, // Variable product
                'category' => 'aquatic-plants',
                'tags' => array('plants', 'aquascape', 'collection', 'premium'),
                'images' => array('plant-collection-1.jpg', 'plant-collection-2.jpg'),
                'stock_status' => 'instock',
                'featured' => true,
                'type' => 'variable',
                'variations' => array(
                    array(
                        'attributes' => array('size' => 'Starter Kit'),
                        'price' => 120,
                        'stock_quantity' => 15
                    ),
                    array(
                        'attributes' => array('size' => 'Professional Kit'),
                        'price' => 280,
                        'stock_quantity' => 8
                    ),
                    array(
                        'attributes' => array('size' => 'Master Collection'),
                        'price' => 450,
                        'stock_quantity' => 5
                    )
                )
            ),

            // Premium Equipment
            array(
                'name' => 'AquaLuxe ProMax Filtration System',
                'description' => 'State-of-the-art multi-stage filtration system designed for luxury aquariums. Features silent operation, advanced biological filtration, and smart monitoring.',
                'short_description' => 'Professional-grade filtration with smart monitoring',
                'price' => 0, // Variable product
                'category' => 'filtration',
                'tags' => array('equipment', 'filtration', 'professional', 'smart'),
                'images' => array('promax-filter-1.jpg', 'promax-filter-2.jpg'),
                'stock_status' => 'instock',
                'featured' => true,
                'type' => 'variable',
                'variations' => array(
                    array(
                        'attributes' => array('capacity' => '50 Gallon'),
                        'price' => 680,
                        'stock_quantity' => 12
                    ),
                    array(
                        'attributes' => array('capacity' => '100 Gallon'),
                        'price' => 980,
                        'stock_quantity' => 8
                    ),
                    array(
                        'attributes' => array('capacity' => '200 Gallon'),
                        'price' => 1480,
                        'stock_quantity' => 4
                    )
                )
            ),

            // Care Supplies
            array(
                'name' => 'Premium Aquatic Nutrition Bundle',
                'description' => 'Complete nutrition solution for all aquatic life. Includes specialized foods for tropical fish, marine species, and aquatic plants.',
                'short_description' => 'Complete nutrition bundle for all aquatic life',
                'price' => 85,
                'sale_price' => 68,
                'category' => 'nutrition',
                'tags' => array('food', 'nutrition', 'bundle', 'complete'),
                'images' => array('nutrition-bundle-1.jpg'),
                'stock_status' => 'instock',
                'stock_quantity' => 50,
                'type' => 'simple'
            ),

            // Digital Products
            array(
                'name' => 'Professional Aquascaping Video Course',
                'description' => 'Comprehensive online course covering advanced aquascaping techniques, plant selection, and maintenance. Includes 12+ hours of video content and downloadable guides.',
                'short_description' => 'Master aquascaping with our comprehensive online course',
                'price' => 197,
                'sale_price' => 147,
                'category' => 'education',
                'tags' => array('digital', 'course', 'aquascaping', 'education'),
                'images' => array('aquascape-course-1.jpg'),
                'stock_status' => 'instock',
                'type' => 'simple',
                'virtual' => true,
                'downloadable' => true
            ),

            // Grouped Products
            array(
                'name' => 'Complete Aquarium Starter Kit',
                'description' => 'Everything needed to start your aquatic journey. This grouped product includes tank, filtration, lighting, and essential accessories.',
                'short_description' => 'Complete starter kit for new aquarium enthusiasts',
                'category' => 'kits',
                'tags' => array('starter', 'complete', 'beginner', 'kit'),
                'images' => array('starter-kit-1.jpg'),
                'type' => 'grouped',
                'children' => array(
                    'AquaLuxe 40-Gallon Tank',
                    'Basic Filtration System',
                    'LED Lighting Kit',
                    'Water Conditioning Set'
                )
            )
        );
    }

    /**
     * Generate product categories
     */
    public static function get_product_categories() {
        return array(
            array(
                'name' => 'Rare Fish Species',
                'slug' => 'rare-fish',
                'description' => 'Exceptional and rare fish species for collectors and enthusiasts',
                'parent' => 0,
                'image' => 'category-rare-fish.jpg'
            ),
            array(
                'name' => 'Marine Fish',
                'slug' => 'marine-fish',
                'description' => 'Saltwater fish species for reef and marine aquariums',
                'parent' => 0,
                'image' => 'category-marine-fish.jpg'
            ),
            array(
                'name' => 'Freshwater Fish',
                'slug' => 'freshwater-fish',
                'description' => 'Freshwater fish species for tropical and temperate aquariums',
                'parent' => 0,
                'image' => 'category-freshwater-fish.jpg'
            ),
            array(
                'name' => 'Aquatic Plants',
                'slug' => 'aquatic-plants',
                'description' => 'Premium aquatic plants for natural aquascaping',
                'parent' => 0,
                'image' => 'category-plants.jpg'
            ),
            array(
                'name' => 'Foreground Plants',
                'slug' => 'foreground-plants',
                'description' => 'Small carpeting plants for aquarium foregrounds',
                'parent' => 'aquatic-plants',
                'image' => 'subcategory-foreground.jpg'
            ),
            array(
                'name' => 'Background Plants',
                'slug' => 'background-plants',
                'description' => 'Tall plants perfect for aquarium backgrounds',
                'parent' => 'aquatic-plants',
                'image' => 'subcategory-background.jpg'
            ),
            array(
                'name' => 'Premium Equipment',
                'slug' => 'premium-equipment',
                'description' => 'High-end aquarium equipment and technology',
                'parent' => 0,
                'image' => 'category-equipment.jpg'
            ),
            array(
                'name' => 'Filtration Systems',
                'slug' => 'filtration',
                'description' => 'Advanced filtration solutions',
                'parent' => 'premium-equipment',
                'image' => 'subcategory-filtration.jpg'
            ),
            array(
                'name' => 'Lighting',
                'slug' => 'lighting',
                'description' => 'Professional aquarium lighting systems',
                'parent' => 'premium-equipment',
                'image' => 'subcategory-lighting.jpg'
            ),
            array(
                'name' => 'Care Supplies',
                'slug' => 'care-supplies',
                'description' => 'Essential supplies for aquarium maintenance',
                'parent' => 0,
                'image' => 'category-supplies.jpg'
            ),
            array(
                'name' => 'Nutrition',
                'slug' => 'nutrition',
                'description' => 'Premium fish food and plant fertilizers',
                'parent' => 'care-supplies',
                'image' => 'subcategory-nutrition.jpg'
            ),
            array(
                'name' => 'Water Care',
                'slug' => 'water-care',
                'description' => 'Water conditioning and testing products',
                'parent' => 'care-supplies',
                'image' => 'subcategory-water-care.jpg'
            ),
            array(
                'name' => 'Education & Courses',
                'slug' => 'education',
                'description' => 'Digital courses and educational materials',
                'parent' => 0,
                'image' => 'category-education.jpg'
            ),
            array(
                'name' => 'Complete Kits',
                'slug' => 'kits',
                'description' => 'All-in-one aquarium solutions',
                'parent' => 0,
                'image' => 'category-kits.jpg'
            )
        );
    }

    /**
     * Generate product tags
     */
    public static function get_product_tags() {
        return array(
            'rare', 'premium', 'exotic', 'collectible', 'limited-edition',
            'marine', 'freshwater', 'reef-safe', 'peaceful', 'aggressive',
            'beginner-friendly', 'advanced', 'professional', 'commercial',
            'plants', 'aquascape', 'natural', 'artificial',
            'equipment', 'technology', 'smart', 'automated',
            'nutrition', 'health', 'growth', 'color-enhancing',
            'maintenance', 'cleaning', 'testing', 'monitoring',
            'digital', 'course', 'education', 'guide',
            'starter', 'complete', 'bundle', 'kit',
            'luxury', 'elegant', 'high-end', 'exclusive'
        );
    }

    /**
     * Generate sample orders for analytics
     */
    public static function get_sample_orders() {
        return array(
            array(
                'status' => 'completed',
                'customer_email' => 'customer1@example.com',
                'billing' => array(
                    'first_name' => 'Marina',
                    'last_name' => 'Rodriguez',
                    'email' => 'marina.rodriguez@example.com',
                    'phone' => '+1-555-0123',
                    'address_1' => '123 Ocean Drive',
                    'city' => 'Miami',
                    'state' => 'FL',
                    'postcode' => '33101',
                    'country' => 'US'
                ),
                'items' => array(
                    array('product_name' => 'Mandarin Dragonet Pair', 'quantity' => 1, 'price' => 380),
                    array('product_name' => 'Premium Aquatic Nutrition Bundle', 'quantity' => 2, 'price' => 68)
                ),
                'total' => 516,
                'date_created' => '2024-01-15'
            ),
            array(
                'status' => 'processing',
                'customer_email' => 'customer2@example.com',
                'billing' => array(
                    'first_name' => 'David',
                    'last_name' => 'Chen',
                    'email' => 'david.chen@example.com',
                    'phone' => '+1-555-0456',
                    'address_1' => '456 Reef Street',
                    'city' => 'San Diego',
                    'state' => 'CA',
                    'postcode' => '92101',
                    'country' => 'US'
                ),
                'items' => array(
                    array('product_name' => 'AquaLuxe ProMax Filtration System', 'quantity' => 1, 'price' => 980)
                ),
                'total' => 980,
                'date_created' => '2024-01-20'
            )
        );
    }

    /**
     * Generate customer reviews
     */
    public static function get_product_reviews() {
        return array(
            array(
                'product_name' => 'Mandarin Dragonet Pair',
                'rating' => 5,
                'reviewer_name' => 'Alex Thompson',
                'reviewer_email' => 'alex.thompson@example.com',
                'review' => 'Absolutely stunning fish! The colors are even more vibrant than in the photos. They adapted quickly to my reef tank and are very peaceful with other tank mates.',
                'date' => '2024-01-10'
            ),
            array(
                'product_name' => 'Premium Aquascape Plant Collection',
                'rating' => 5,
                'reviewer_name' => 'Sarah Williams',
                'reviewer_email' => 'sarah.williams@example.com',
                'review' => 'This collection transformed my aquarium into a underwater garden. The variety and quality of plants exceeded my expectations. Great for creating natural aquascapes.',
                'date' => '2024-01-12'
            ),
            array(
                'product_name' => 'AquaLuxe ProMax Filtration System',
                'rating' => 5,
                'reviewer_name' => 'Michael Foster',
                'reviewer_email' => 'michael.foster@example.com',
                'review' => 'Professional-grade equipment that delivers on its promises. Silent operation and the smart monitoring feature gives me peace of mind. Worth every penny.',
                'date' => '2024-01-14'
            )
        );
    }

    /**
     * Get wholesale pricing data
     */
    public static function get_wholesale_data() {
        return array(
            'pricing_tiers' => array(
                'bronze' => array(
                    'name' => 'Bronze Partner',
                    'minimum_order' => 1000,
                    'discount' => 15,
                    'benefits' => array('15% discount', 'Priority support', 'Quarterly catalogs')
                ),
                'silver' => array(
                    'name' => 'Silver Partner',
                    'minimum_order' => 5000,
                    'discount' => 25,
                    'benefits' => array('25% discount', 'Dedicated account manager', 'Monthly training sessions')
                ),
                'gold' => array(
                    'name' => 'Gold Partner',
                    'minimum_order' => 15000,
                    'discount' => 35,
                    'benefits' => array('35% discount', 'Custom pricing', 'Exclusive product access')
                )
            ),
            'partner_benefits' => array(
                'Free shipping on orders over $500',
                'Extended payment terms (Net 30)',
                'Marketing support materials',
                'Technical training programs',
                'Exclusive partner events'
            )
        );
    }
}