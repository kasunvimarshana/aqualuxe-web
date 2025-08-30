#!/bin/bash

# AquaLuxe Theme WooCommerce Testing Script
# This script provides a structured approach to test WooCommerce functionality

echo "====================================="
echo "AquaLuxe Theme WooCommerce Testing"
echo "====================================="

# Set variables
THEME_DIR=$(dirname "$(dirname "$0")")
RESULTS_DIR="$THEME_DIR/test-results"
DATE=$(date +"%Y-%m-%d_%H-%M-%S")
LOG_FILE="$RESULTS_DIR/woocommerce_test_log_$DATE.txt"

# Create results directory if it doesn't exist
mkdir -p "$RESULTS_DIR"

# Start logging
exec > >(tee -a "$LOG_FILE") 2>&1

echo "WooCommerce testing started at: $(date)"
echo "Theme directory: $THEME_DIR"
echo

# Create a checklist file
CHECKLIST_FILE="$RESULTS_DIR/woocommerce_checklist_$DATE.md"

cat > "$CHECKLIST_FILE" << EOL
# AquaLuxe Theme WooCommerce Testing Checklist

## Shop Page Testing

### Layout and Display
- [ ] Shop page layout matches design
- [ ] Products display correctly in grid view
- [ ] Products display correctly in list view (if applicable)
- [ ] Product images load correctly
- [ ] Product titles display correctly
- [ ] Product prices display correctly
- [ ] Sale badges display correctly
- [ ] "New" badges display correctly (if applicable)
- [ ] "Out of stock" badges display correctly
- [ ] Quick view buttons display correctly
- [ ] Wishlist buttons display correctly
- [ ] Add to cart buttons display correctly

### Filtering and Sorting
- [ ] Category filtering works correctly
- [ ] Price filtering works correctly
- [ ] Attribute filtering works correctly
- [ ] Sorting options work correctly
- [ ] Filter reset works correctly
- [ ] AJAX filtering works without page reload
- [ ] Filtering UI is responsive on mobile devices

### Pagination
- [ ] Pagination displays correctly
- [ ] Pagination links work correctly
- [ ] Products per page selector works correctly
- [ ] Infinite scroll works correctly (if applicable)
- [ ] Load more button works correctly (if applicable)

## Single Product Page Testing

### Layout and Display
- [ ] Product page layout matches design
- [ ] Product title displays correctly
- [ ] Product price displays correctly
- [ ] Sale price displays correctly (if applicable)
- [ ] Product description displays correctly
- [ ] Product short description displays correctly
- [ ] Product images display correctly
- [ ] Product gallery works correctly
- [ ] Image zoom works correctly
- [ ] Product meta (SKU, categories, tags) displays correctly

### Product Variations
- [ ] Variation selectors display correctly
- [ ] Selecting variations updates price correctly
- [ ] Selecting variations updates image correctly
- [ ] Selecting variations updates stock status correctly
- [ ] Variation combination validation works correctly

### Product Tabs
- [ ] Description tab displays correctly
- [ ] Additional information tab displays correctly
- [ ] Reviews tab displays correctly
- [ ] Custom tabs display correctly (if applicable)
- [ ] Tab switching works correctly

### Add to Cart
- [ ] Quantity selector works correctly
- [ ] Add to cart button works correctly
- [ ] Adding to cart shows confirmation message
- [ ] Adding to cart updates cart count in header
- [ ] Adding variable product to cart works correctly
- [ ] Adding grouped product to cart works correctly
- [ ] Adding external/affiliate product works correctly

### Related Products
- [ ] Related products display correctly
- [ ] Related products links work correctly

### Upsells and Cross-Sells
- [ ] Upsell products display correctly
- [ ] Cross-sell products display correctly

## Quick View Testing

### Quick View Modal
- [ ] Quick view button opens modal correctly
- [ ] Modal displays product information correctly
- [ ] Product image displays correctly
- [ ] Product gallery works in modal
- [ ] Price displays correctly
- [ ] Variations work correctly in modal
- [ ] Add to cart works from modal
- [ ] Modal closes correctly
- [ ] "View full details" link works correctly

## Wishlist Testing

### Wishlist Functionality
- [ ] Add to wishlist button works correctly
- [ ] Remove from wishlist button works correctly
- [ ] Wishlist page displays correctly
- [ ] Wishlist persists for logged-in users
- [ ] Wishlist persists for guest users (using cookies)
- [ ] Moving products from wishlist to cart works correctly
- [ ] Sharing wishlist works correctly (if applicable)

## Cart Testing

### Cart Page
- [ ] Cart page layout matches design
- [ ] Products in cart display correctly
- [ ] Product images in cart display correctly
- [ ] Product prices in cart display correctly
- [ ] Quantity selectors work correctly
- [ ] Updating quantities works correctly
- [ ] Removing products works correctly
- [ ] Cart totals calculate correctly
- [ ] Shipping calculator works correctly (if enabled)
- [ ] Coupon code field works correctly
- [ ] Applying valid coupon works correctly
- [ ] Applying invalid coupon shows error message
- [ ] Proceed to checkout button works correctly

### Mini Cart
- [ ] Mini cart displays correctly in header
- [ ] Mini cart shows correct number of items
- [ ] Mini cart shows correct total
- [ ] Adding to cart updates mini cart
- [ ] Removing from mini cart works correctly
- [ ] View cart link works correctly
- [ ] Checkout link works correctly

## Checkout Testing

### Checkout Page
- [ ] Checkout page layout matches design
- [ ] Customer details form displays correctly
- [ ] Billing details form works correctly
- [ ] Shipping details form works correctly
- [ ] Order notes field works correctly
- [ ] Order review section displays correctly
- [ ] Payment methods display correctly
- [ ] Terms and conditions checkbox works correctly
- [ ] Place order button works correctly

### Guest Checkout
- [ ] Guest checkout works correctly
- [ ] Create account option works correctly

### Logged-In Checkout
- [ ] Saved addresses load correctly
- [ ] Saved payment methods load correctly (if applicable)

### Shipping Methods
- [ ] Available shipping methods display correctly
- [ ] Selecting shipping method updates order total
- [ ] Shipping method validation works correctly

### Payment Methods
- [ ] Available payment methods display correctly
- [ ] Direct bank transfer works correctly
- [ ] Check payments works correctly
- [ ] Cash on delivery works correctly
- [ ] PayPal works correctly (if configured)
- [ ] Stripe works correctly (if configured)
- [ ] Other payment gateways work correctly (if configured)

## Order Confirmation Testing

### Thank You Page
- [ ] Thank you page displays correctly
- [ ] Order details display correctly
- [ ] Customer details display correctly
- [ ] Order confirmation email is sent correctly

## My Account Testing

### Dashboard
- [ ] Dashboard page displays correctly
- [ ] Welcome message displays correctly
- [ ] Account navigation works correctly

### Orders
- [ ] Orders page displays correctly
- [ ] Order history displays correctly
- [ ] Order details page displays correctly
- [ ] Order actions work correctly (if applicable)

### Addresses
- [ ] Addresses page displays correctly
- [ ] Edit address forms work correctly
- [ ] Adding new addresses works correctly

### Account Details
- [ ] Account details page displays correctly
- [ ] Updating account details works correctly
- [ ] Changing password works correctly

### Downloads
- [ ] Downloads page displays correctly (if applicable)
- [ ] Downloading files works correctly (if applicable)

## Multi-Currency Testing

### Currency Switcher
- [ ] Currency switcher displays correctly
- [ ] Switching currencies updates prices correctly
- [ ] Selected currency persists across pages
- [ ] Selected currency persists across sessions
- [ ] Currency symbol displays correctly
- [ ] Currency formatting is correct

### Checkout with Different Currencies
- [ ] Checkout works correctly with different currencies
- [ ] Order is processed in selected currency
- [ ] Order confirmation shows correct currency

## International Shipping Testing

### Shipping Zones
- [ ] Products ship to correct zones
- [ ] Shipping rates calculate correctly for different zones
- [ ] Shipping restrictions work correctly (if applicable)

### Address Validation
- [ ] International addresses validate correctly
- [ ] Required fields are appropriate for different countries
- [ ] State/province field changes based on country

## Testing with and without WooCommerce

### Theme without WooCommerce
- [ ] Theme loads correctly without WooCommerce activated
- [ ] No PHP errors or warnings appear
- [ ] No broken layouts or missing elements
- [ ] No references to WooCommerce functionality

### Theme with WooCommerce
- [ ] Theme integrates correctly with WooCommerce
- [ ] WooCommerce templates are overridden correctly
- [ ] WooCommerce styles are applied correctly
- [ ] WooCommerce functionality works correctly

## Notes

Use this section to record any issues found during testing:

1. 
2. 
3. 

## Recommendations

Use this section to record recommendations for improving WooCommerce functionality:

1. 
2. 
3. 
EOL

echo "WooCommerce testing checklist created: $CHECKLIST_FILE"
echo
echo "====================================="
echo "Instructions for WooCommerce Testing"
echo "====================================="
echo
echo "1. Test Environment Setup"
echo "   - Ensure WooCommerce is installed and activated"
echo "   - Set up test products (simple, variable, grouped, external)"
echo "   - Configure shipping zones and methods"
echo "   - Configure payment gateways (set to test mode)"
echo "   - Create test user accounts with different roles"
echo
echo "2. Testing Process"
echo "   - Follow the checklist systematically"
echo "   - Test as both guest and logged-in user"
echo "   - Test with different product types"
echo "   - Test with different payment methods"
echo "   - Test with different shipping methods"
echo "   - Test with different currencies (if multi-currency is enabled)"
echo "   - Test with different countries (for international shipping)"
echo
echo "3. Common Issues to Watch For"
echo "   - JavaScript errors affecting functionality"
echo "   - CSS issues affecting display"
echo "   - Responsive design issues on mobile devices"
echo "   - Form validation errors"
echo "   - Payment gateway integration issues"
echo "   - Cart calculation errors"
echo "   - Session handling issues"
echo
echo "====================================="
echo "Testing with and without WooCommerce"
echo "====================================="
echo
echo "1. Testing without WooCommerce"
echo "   - Deactivate WooCommerce plugin"
echo "   - Check all pages for errors or broken layouts"
echo "   - Verify that theme functions correctly without WooCommerce"
echo "   - Check PHP error log for any warnings or notices"
echo
echo "2. Testing with WooCommerce"
echo "   - Activate WooCommerce plugin"
echo "   - Verify that theme integrates correctly with WooCommerce"
echo "   - Check that all WooCommerce templates are styled correctly"
echo "   - Verify that all WooCommerce functionality works correctly"
echo
echo "====================================="
echo "WooCommerce testing completed at: $(date)"
echo "Log file: $LOG_FILE"
echo "Checklist file: $CHECKLIST_FILE"
echo "====================================="

# Create a WooCommerce test data generator script
TEST_DATA_SCRIPT="$RESULTS_DIR/generate_woocommerce_test_data.php"

cat > "$TEST_DATA_SCRIPT" << EOL
<?php
/**
 * WooCommerce Test Data Generator
 *
 * This script generates test data for WooCommerce testing.
 * To use this script, run it from the WordPress root directory:
 * php generate_woocommerce_test_data.php
 */

// Bootstrap WordPress
require_once 'wp-load.php';

// Check if WooCommerce is active
if (!class_exists('WooCommerce')) {
    die('WooCommerce is not active. Please activate WooCommerce before running this script.');
}

echo "Generating WooCommerce test data...\n";

// Create product categories
function create_product_categories() {
    echo "Creating product categories...\n";
    
    $categories = array(
        'Aquariums' => array(
            'description' => 'High-quality aquariums for all types of aquatic life.',
            'children' => array(
                'Freshwater Aquariums' => 'Aquariums designed for freshwater fish and plants.',
                'Saltwater Aquariums' => 'Aquariums designed for saltwater fish and coral.',
                'Planted Aquariums' => 'Specialized aquariums for aquatic plants.',
                'Nano Aquariums' => 'Small, compact aquariums for limited spaces.'
            )
        ),
        'Filtration' => array(
            'description' => 'Filtration systems for clean and healthy aquarium water.',
            'children' => array(
                'Canister Filters' => 'External canister filtration systems.',
                'HOB Filters' => 'Hang-on-back filters for easy maintenance.',
                'Sponge Filters' => 'Simple, effective filtration for sensitive species.',
                'UV Sterilizers' => 'UV light systems to eliminate harmful organisms.'
            )
        ),
        'Lighting' => array(
            'description' => 'Lighting systems for optimal aquarium conditions.',
            'children' => array(
                'LED Lights' => 'Energy-efficient LED lighting systems.',
                'T5 Fixtures' => 'High-output T5 fluorescent lighting.',
                'Metal Halide' => 'Powerful metal halide lighting for deep tanks.',
                'Specialized Lighting' => 'Specialized lighting for specific needs.'
            )
        ),
        'Livestock' => array(
            'description' => 'Healthy, ethically sourced aquatic life.',
            'children' => array(
                'Freshwater Fish' => 'Variety of freshwater fish species.',
                'Saltwater Fish' => 'Exotic saltwater fish species.',
                'Invertebrates' => 'Shrimp, snails, and other invertebrates.',
                'Coral' => 'Live coral specimens for reef aquariums.'
            )
        ),
        'Plants' => array(
            'description' => 'Live aquatic plants for natural aquascapes.',
            'children' => array(
                'Foreground Plants' => 'Low-growing plants for the front of the aquarium.',
                'Midground Plants' => 'Medium-height plants for the middle areas.',
                'Background Plants' => 'Tall plants for the back of the aquarium.',
                'Floating Plants' => 'Plants that float on the water surface.'
            )
        ),
        'Accessories' => array(
            'description' => 'Essential accessories for aquarium maintenance.',
            'children' => array(
                'Water Testing' => 'Test kits and equipment for water parameter monitoring.',
                'Cleaning Tools' => 'Tools for effective aquarium cleaning.',
                'Decorations' => 'Decorative items for aquarium aesthetics.',
                'Feeding Equipment' => 'Specialized equipment for feeding aquatic life.'
            )
        )
    );
    
    $category_ids = array();
    
    foreach ($categories as $category_name => $category_data) {
        $parent_term = wp_insert_term(
            $category_name,
            'product_cat',
            array(
                'description' => $category_data['description'],
                'slug' => sanitize_title($category_name)
            )
        );
        
        if (!is_wp_error($parent_term)) {
            $parent_id = $parent_term['term_id'];
            $category_ids[$category_name] = $parent_id;
            
            // Set category image
            $image_url = 'https://via.placeholder.com/800x600?text=' . urlencode($category_name);
            $attachment_id = upload_placeholder_image($image_url, $category_name . ' Category Image');
            if ($attachment_id) {
                update_term_meta($parent_id, 'thumbnail_id', $attachment_id);
            }
            
            // Create child categories
            foreach ($category_data['children'] as $child_name => $child_description) {
                $child_term = wp_insert_term(
                    $child_name,
                    'product_cat',
                    array(
                        'description' => $child_description,
                        'slug' => sanitize_title($child_name),
                        'parent' => $parent_id
                    )
                );
                
                if (!is_wp_error($child_term)) {
                    $child_id = $child_term['term_id'];
                    $category_ids[$child_name] = $child_id;
                    
                    // Set child category image
                    $child_image_url = 'https://via.placeholder.com/800x600?text=' . urlencode($child_name);
                    $child_attachment_id = upload_placeholder_image($child_image_url, $child_name . ' Category Image');
                    if ($child_attachment_id) {
                        update_term_meta($child_id, 'thumbnail_id', $child_attachment_id);
                    }
                }
            }
        }
    }
    
    echo "Created " . count($category_ids) . " product categories.\n";
    return $category_ids;
}

// Create product attributes
function create_product_attributes() {
    echo "Creating product attributes...\n";
    
    $attributes = array(
        'Size' => array('Small', 'Medium', 'Large', 'Extra Large'),
        'Color' => array('Black', 'White', 'Blue', 'Green', 'Red'),
        'Material' => array('Glass', 'Acrylic', 'Plastic', 'Stainless Steel', 'Ceramic'),
        'Wattage' => array('5W', '10W', '15W', '20W', '30W', '50W', '100W'),
        'Flow Rate' => array('100 GPH', '200 GPH', '300 GPH', '500 GPH', '800 GPH', '1000 GPH')
    );
    
    $attribute_ids = array();
    
    foreach ($attributes as $attribute_name => $attribute_values) {
        $attribute_id = wc_create_attribute(array(
            'name' => $attribute_name,
            'slug' => sanitize_title($attribute_name),
            'type' => 'select',
            'order_by' => 'menu_order',
            'has_archives' => false
        ));
        
        if (!is_wp_error($attribute_id)) {
            $attribute_ids[$attribute_name] = $attribute_id;
            
            // Register the taxonomy
            $taxonomy_name = 'pa_' . sanitize_title($attribute_name);
            register_taxonomy(
                $taxonomy_name,
                'product',
                array(
                    'labels' => array(
                        'name' => $attribute_name,
                        'singular_name' => $attribute_name,
                    ),
                    'hierarchical' => false,
                    'show_ui' => true,
                    'query_var' => true,
                )
            );
            
            // Add attribute values
            foreach ($attribute_values as $value) {
                wp_insert_term($value, $taxonomy_name);
            }
        }
    }
    
    echo "Created " . count($attribute_ids) . " product attributes.\n";
    return $attribute_ids;
}

// Create simple products
function create_simple_products($categories) {
    echo "Creating simple products...\n";
    
    $products = array(
        'Premium Aquarium Filter System' => array(
            'price' => '199.99',
            'sale_price' => '179.99',
            'category' => 'Canister Filters',
            'description' => 'Our Premium Aquarium Filter System provides superior filtration for tanks up to 100 gallons. This advanced system combines mechanical, biological, and chemical filtration to ensure crystal clear water and optimal conditions for your aquatic life.',
            'short_description' => 'Advanced three-stage filtration system for aquariums up to 100 gallons.',
            'sku' => 'FILTER-001'
        ),
        'Ultra Clear Glass Aquarium - 50 Gallon' => array(
            'price' => '299.99',
            'sale_price' => '',
            'category' => 'Freshwater Aquariums',
            'description' => 'The Ultra Clear Glass Aquarium offers unparalleled clarity and durability. Made from premium 10mm thick glass with precision silicone sealing, this 50-gallon tank provides a perfect view of your underwater world. Includes reinforced bottom frame and polished edges for safety.',
            'short_description' => 'Premium 50-gallon glass aquarium with exceptional clarity and durability.',
            'sku' => 'TANK-050'
        ),
        'Professional LED Lighting System' => array(
            'price' => '149.99',
            'sale_price' => '129.99',
            'category' => 'LED Lights',
            'description' => 'Illuminate your aquarium with our Professional LED Lighting System. Featuring customizable color spectrum, programmable timing, and weather effects, this lighting system promotes healthy plant growth and showcases the vibrant colors of your fish. Energy-efficient LEDs provide years of reliable service.',
            'short_description' => 'Customizable LED lighting system with multiple spectrum options and effects.',
            'sku' => 'LIGHT-001'
        ),
        'Precision Water Testing Kit' => array(
            'price' => '49.99',
            'sale_price' => '',
            'category' => 'Water Testing',
            'description' => 'Our Precision Water Testing Kit provides accurate measurements of all critical water parameters. Test for pH, ammonia, nitrite, nitrate, hardness, and more with laboratory-grade reagents. Includes detailed color charts and comprehensive guide to water chemistry.',
            'short_description' => 'Complete water testing kit with accurate results for all critical parameters.',
            'sku' => 'TEST-001'
        ),
        'Natural Driftwood Centerpiece' => array(
            'price' => '79.99',
            'sale_price' => '69.99',
            'category' => 'Decorations',
            'description' => 'Add a stunning focal point to your aquarium with our Natural Driftwood Centerpiece. Each piece is unique, carefully selected and prepared to be aquarium-safe. The natural wood provides hiding places for fish and shrimp while also slowly releasing beneficial tannins.',
            'short_description' => 'Unique natural driftwood centerpiece for aquascaping and fish habitat.',
            'sku' => 'DECOR-001'
        )
    );
    
    $product_ids = array();
    
    foreach ($products as $product_name => $product_data) {
        $product = new WC_Product_Simple();
        $product->set_name($product_name);
        $product->set_regular_price($product_data['price']);
        
        if (!empty($product_data['sale_price'])) {
            $product->set_sale_price($product_data['sale_price']);
        }
        
        $product->set_description($product_data['description']);
        $product->set_short_description($product_data['short_description']);
        $product->set_sku($product_data['sku']);
        $product->set_manage_stock(true);
        $product->set_stock_quantity(rand(5, 50));
        $product->set_stock_status('instock');
        
        // Set product image
        $image_url = 'https://via.placeholder.com/800x800?text=' . urlencode($product_name);
        $image_id = upload_placeholder_image($image_url, $product_name);
        if ($image_id) {
            $product->set_image_id($image_id);
        }
        
        // Set product gallery
        $gallery_ids = array();
        for ($i = 1; $i <= 3; $i++) {
            $gallery_url = 'https://via.placeholder.com/800x800?text=' . urlencode($product_name . ' ' . $i);
            $gallery_id = upload_placeholder_image($gallery_url, $product_name . ' Gallery ' . $i);
            if ($gallery_id) {
                $gallery_ids[] = $gallery_id;
            }
        }
        if (!empty($gallery_ids)) {
            $product->set_gallery_image_ids($gallery_ids);
        }
        
        // Set product category
        if (isset($product_data['category'])) {
            $category_term = get_term_by('name', $product_data['category'], 'product_cat');
            if ($category_term) {
                $product->set_category_ids(array($category_term->term_id));
            }
        }
        
        $product_id = $product->save();
        $product_ids[$product_name] = $product_id;
    }
    
    echo "Created " . count($product_ids) . " simple products.\n";
    return $product_ids;
}

// Create variable products
function create_variable_products($categories) {
    echo "Creating variable products...\n";
    
    $products = array(
        'AquaLuxe Rimless Aquarium' => array(
            'category' => 'Freshwater Aquariums',
            'description' => 'The AquaLuxe Rimless Aquarium combines elegant design with superior functionality. The seamless, rimless construction provides an unobstructed view of your aquatic world, while the high-clarity glass ensures true color representation. Available in multiple sizes to fit any space.',
            'short_description' => 'Elegant rimless aquarium with high-clarity glass in multiple sizes.',
            'sku' => 'RIMLESS-',
            'attributes' => array(
                'Size' => array('Small (10 Gallon)', 'Medium (20 Gallon)', 'Large (40 Gallon)', 'Extra Large (60 Gallon)'),
                'Material' => array('Glass', 'Acrylic')
            ),
            'variations' => array(
                array('Size' => 'Small (10 Gallon)', 'Material' => 'Glass', 'price' => '129.99'),
                array('Size' => 'Small (10 Gallon)', 'Material' => 'Acrylic', 'price' => '149.99'),
                array('Size' => 'Medium (20 Gallon)', 'Material' => 'Glass', 'price' => '199.99'),
                array('Size' => 'Medium (20 Gallon)', 'Material' => 'Acrylic', 'price' => '229.99'),
                array('Size' => 'Large (40 Gallon)', 'Material' => 'Glass', 'price' => '299.99'),
                array('Size' => 'Large (40 Gallon)', 'Material' => 'Acrylic', 'price' => '349.99'),
                array('Size' => 'Extra Large (60 Gallon)', 'Material' => 'Glass', 'price' => '399.99'),
                array('Size' => 'Extra Large (60 Gallon)', 'Material' => 'Acrylic', 'price' => '449.99')
            )
        ),
        'AquaFlow Power Filter' => array(
            'category' => 'HOB Filters',
            'description' => 'The AquaFlow Power Filter provides efficient filtration for aquariums of all sizes. With its three-stage filtration system, it removes physical debris, chemical impurities, and biological waste. The adjustable flow rate allows you to customize the filtration to your specific needs.',
            'short_description' => 'Efficient three-stage filtration with adjustable flow rate for various tank sizes.',
            'sku' => 'AQUAFLOW-',
            'attributes' => array(
                'Size' => array('Small', 'Medium', 'Large'),
                'Flow Rate' => array('100 GPH', '200 GPH', '300 GPH', '500 GPH')
            ),
            'variations' => array(
                array('Size' => 'Small', 'Flow Rate' => '100 GPH', 'price' => '39.99'),
                array('Size' => 'Small', 'Flow Rate' => '200 GPH', 'price' => '49.99'),
                array('Size' => 'Medium', 'Flow Rate' => '200 GPH', 'price' => '59.99'),
                array('Size' => 'Medium', 'Flow Rate' => '300 GPH', 'price' => '69.99'),
                array('Size' => 'Large', 'Flow Rate' => '300 GPH', 'price' => '79.99'),
                array('Size' => 'Large', 'Flow Rate' => '500 GPH', 'price' => '89.99')
            )
        )
    );
    
    $product_ids = array();
    
    foreach ($products as $product_name => $product_data) {
        // Create the product
        $product = new WC_Product_Variable();
        $product->set_name($product_name);
        $product->set_description($product_data['description']);
        $product->set_short_description($product_data['short_description']);
        $product->set_sku($product_data['sku'] . 'BASE');
        
        // Set product image
        $image_url = 'https://via.placeholder.com/800x800?text=' . urlencode($product_name);
        $image_id = upload_placeholder_image($image_url, $product_name);
        if ($image_id) {
            $product->set_image_id($image_id);
        }
        
        // Set product gallery
        $gallery_ids = array();
        for ($i = 1; $i <= 3; $i++) {
            $gallery_url = 'https://via.placeholder.com/800x800?text=' . urlencode($product_name . ' ' . $i);
            $gallery_id = upload_placeholder_image($gallery_url, $product_name . ' Gallery ' . $i);
            if ($gallery_id) {
                $gallery_ids[] = $gallery_id;
            }
        }
        if (!empty($gallery_ids)) {
            $product->set_gallery_image_ids($gallery_ids);
        }
        
        // Set product category
        if (isset($product_data['category'])) {
            $category_term = get_term_by('name', $product_data['category'], 'product_cat');
            if ($category_term) {
                $product->set_category_ids(array($category_term->term_id));
            }
        }
        
        // Set product attributes
        $attributes = array();
        foreach ($product_data['attributes'] as $attribute_name => $attribute_values) {
            $attribute = new WC_Product_Attribute();
            $attribute->set_name('pa_' . sanitize_title($attribute_name));
            $attribute->set_options($attribute_values);
            $attribute->set_position(0);
            $attribute->set_visible(true);
            $attribute->set_variation(true);
            $attributes[] = $attribute;
        }
        $product->set_attributes($attributes);
        
        // Save the product to get an ID
        $product_id = $product->save();
        $product_ids[$product_name] = $product_id;
        
        // Create variations
        foreach ($product_data['variations'] as $variation_data) {
            $variation = new WC_Product_Variation();
            $variation->set_parent_id($product_id);
            $variation->set_regular_price($variation_data['price']);
            
            // Set variation attributes
            $variation_attributes = array();
            foreach ($product_data['attributes'] as $attribute_name => $attribute_values) {
                if (isset($variation_data[$attribute_name])) {
                    $variation_attributes['pa_' . sanitize_title($attribute_name)] = sanitize_title($variation_data[$attribute_name]);
                }
            }
            $variation->set_attributes($variation_attributes);
            
            // Set variation SKU
            $sku_suffix = '';
            foreach ($variation_attributes as $key => $value) {
                $sku_suffix .= '-' . $value;
            }
            $variation->set_sku($product_data['sku'] . strtoupper($sku_suffix));
            
            // Set stock
            $variation->set_manage_stock(true);
            $variation->set_stock_quantity(rand(5, 20));
            $variation->set_stock_status('instock');
            
            $variation->save();
        }
    }
    
    echo "Created " . count($product_ids) . " variable products.\n";
    return $product_ids;
}

// Helper function to upload a placeholder image
function upload_placeholder_image($image_url, $title) {
    $upload_dir = wp_upload_dir();
    $image_data = file_get_contents($image_url);
    
    if ($image_data === false) {
        return false;
    }
    
    $filename = sanitize_file_name($title . '.jpg');
    $file_path = $upload_dir['path'] . '/' . $filename;
    
    file_put_contents($file_path, $image_data);
    
    $wp_filetype = wp_check_filetype($filename, null);
    $attachment = array(
        'post_mime_type' => $wp_filetype['type'],
        'post_title' => $title,
        'post_content' => '',
        'post_status' => 'inherit'
    );
    
    $attachment_id = wp_insert_attachment($attachment, $file_path);
    
    if (!is_wp_error($attachment_id)) {
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        $attachment_data = wp_generate_attachment_metadata($attachment_id, $file_path);
        wp_update_attachment_metadata($attachment_id, $attachment_data);
        return $attachment_id;
    }
    
    return false;
}

// Main execution
try {
    // Create product categories
    $categories = create_product_categories();
    
    // Create product attributes
    $attributes = create_product_attributes();
    
    // Create simple products
    $simple_products = create_simple_products($categories);
    
    // Create variable products
    $variable_products = create_variable_products($categories);
    
    echo "WooCommerce test data generation completed successfully!\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
EOL

echo "WooCommerce test data generator script created: $TEST_DATA_SCRIPT"