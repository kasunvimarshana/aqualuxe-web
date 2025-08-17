#!/usr/bin/env php
<?php
/**
 * AquaLuxe Theme Demo Content Generator
 * This script generates demo content for the theme
 */

// Configuration
$output_dir = dirname(__DIR__) . '/demo-content';
$site_title = 'AquaLuxe Demo';
$site_description = 'A premium WordPress theme for luxury businesses';
$admin_email = 'admin@example.com';
$categories = ['Spa', 'Wellness', 'Luxury', 'Treatments', 'Products'];
$tags = ['Relaxation', 'Massage', 'Facial', 'Skincare', 'Wellness', 'Luxury', 'Spa', 'Beauty', 'Health', 'Retreat'];
$post_count = 10;
$page_count = 5;
$product_count = 15;

// ANSI color codes for terminal output
$colors = [
    'reset' => "\033[0m",
    'red' => "\033[31m",
    'green' => "\033[32m",
    'yellow' => "\033[33m",
    'blue' => "\033[34m",
    'magenta' => "\033[35m",
    'cyan' => "\033[36m",
    'white' => "\033[37m",
    'bold' => "\033[1m",
];

echo "{$colors['bold']}{$colors['blue']}AquaLuxe Theme Demo Content Generator{$colors['reset']}\n";

// Create output directory if it doesn't exist
if (!is_dir($output_dir)) {
    if (mkdir($output_dir, 0755, true)) {
        echo "{$colors['green']}Created output directory: {$output_dir}{$colors['reset']}\n";
    } else {
        echo "{$colors['red']}Failed to create output directory: {$output_dir}{$colors['reset']}\n";
        exit(1);
    }
}

// Sample content data
$lorem_ipsum = [
    "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam in dui mauris. Vivamus hendrerit arcu sed erat molestie vehicula. Sed auctor neque eu tellus rhoncus ut eleifend nibh porttitor. Ut in nulla enim. Phasellus molestie magna non est bibendum non venenatis nisl tempor. Suspendisse dictum feugiat nisl ut dapibus. Mauris iaculis porttitor posuere. Praesent id metus massa, ut blandit odio.",
    "Proin quis tortor orci. Etiam at risus et justo dignissim congue. Donec congue lacinia dui, a porttitor lectus condimentum laoreet. Nunc eu ullamcorper orci. Quisque eget odio ac lectus vestibulum faucibus eget in metus. In pellentesque faucibus vestibulum. Nulla at nulla justo, eget luctus tortor. Nulla facilisi. Duis aliquet egestas purus in blandit.",
    "Fusce vulputate eleifend sapien. Vestibulum purus quam, scelerisque ut, mollis sed, nonummy id, metus. Nullam accumsan lorem in dui. Cras ultricies mi eu turpis hendrerit fringilla. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; In ac dui quis mi consectetuer lacinia. Nam pretium turpis et arcu.",
    "Curabitur ligula sapien, tincidunt non, euismod vitae, posuere imperdiet, leo. Maecenas malesuada. Praesent congue erat at massa. Sed cursus turpis vitae tortor. Donec posuere vulputate arcu. Phasellus accumsan cursus velit. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Sed aliquam, nisi quis porttitor congue.",
    "Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Curabitur a felis in nunc fringilla tristique. Morbi mattis ullamcorper velit. Phasellus gravida semper nisi. Nullam vel sem. Pellentesque libero tortor, tincidunt et, tincidunt eget, semper nec, quam. Sed hendrerit. Morbi ac felis. Nunc egestas, augue at pellentesque laoreet."
];

$post_titles = [
    'Experience Ultimate Relaxation with Our Signature Massage',
    'The Benefits of Hydrotherapy for Mind and Body',
    'Luxury Spa Treatments for the Modern Professional',
    'Skincare Secrets: How to Maintain a Youthful Glow',
    'Wellness Retreats: Finding Balance in a Busy World',
    'The Art of Aromatherapy: Essential Oils for Every Mood',
    'Meditation Techniques for Stress Relief',
    'Spa Etiquette: What to Expect During Your First Visit',
    'Seasonal Spa Treatments to Rejuvenate Your Senses',
    'The Connection Between Wellness and Productivity',
    'Luxury Bath Rituals to Try at Home',
    'The Science Behind Hot Stone Massage Therapy',
    'Facial Treatments for Different Skin Types',
    'Mindfulness and Spa: A Perfect Combination',
    'Detox Programs: Myths and Facts'
];

$page_titles = [
    'About Us',
    'Our Services',
    'Spa Facilities',
    'Membership',
    'Contact Us',
    'FAQ',
    'Testimonials',
    'Our Team',
    'Gift Cards',
    'Booking Information'
];

$product_titles = [
    'Luxury Facial Serum',
    'Aromatherapy Essential Oil Set',
    'Himalayan Salt Bath Soak',
    'Organic Body Scrub',
    'Hydrating Face Mask',
    'Bamboo Massage Tool Set',
    'Luxury Scented Candle',
    'Silk Eye Mask',
    'Wellness Tea Collection',
    'Natural Body Lotion',
    'Exfoliating Facial Brush',
    'Relaxation Gift Set',
    'Jade Facial Roller',
    'Luxury Bath Bombs',
    'Organic Hair Treatment Oil',
    'Spa Robe and Slippers Set',
    'Meditation Cushion',
    'Detox Bath Salts',
    'Collagen Boost Supplement',
    'Luxury Hand Cream'
];

$product_descriptions = [
    'Our premium product is crafted with the finest ingredients to provide an unparalleled luxury experience. Each application delivers immediate results while working to improve long-term skin health and appearance.',
    'This exclusive formula combines cutting-edge science with natural extracts to deliver powerful results. Developed by our team of expert formulators, it addresses multiple concerns simultaneously.',
    'Indulge in the ultimate self-care ritual with this luxurious product. The rich texture and subtle fragrance transform your routine into a spa-like experience in the comfort of your home.',
    'Experience the perfect balance of efficacy and indulgence with our bestselling product. The lightweight formula absorbs quickly while delivering potent active ingredients deep into the skin.',
    'This signature product represents the pinnacle of our luxury line. Infused with rare botanicals and advanced peptides, it delivers visible results from the very first use.'
];

$product_short_descriptions = [
    'A luxury treatment for radiant, youthful skin.',
    'Premium quality for the ultimate self-care experience.',
    'Expertly formulated for visible, lasting results.',
    'Transform your daily routine into a spa ritual.',
    'The perfect addition to your wellness collection.'
];

$product_categories = [
    'Skincare',
    'Body Care',
    'Aromatherapy',
    'Wellness',
    'Gift Sets',
    'Accessories'
];

$product_tags = [
    'Organic',
    'Vegan',
    'Cruelty-Free',
    'Natural',
    'Luxury',
    'Bestseller',
    'Limited Edition',
    'Exclusive',
    'Hydrating',
    'Anti-aging'
];

// Function to generate random paragraphs
function generate_paragraphs($min = 3, $max = 6) {
    global $lorem_ipsum;
    $count = rand($min, $max);
    $paragraphs = [];
    
    for ($i = 0; $i < $count; $i++) {
        $paragraphs[] = $lorem_ipsum[array_rand($lorem_ipsum)];
    }
    
    return implode("\n\n", $paragraphs);
}

// Function to generate random tags
function generate_tags($tags, $min = 2, $max = 5) {
    $count = rand($min, $max);
    shuffle($tags);
    return array_slice($tags, 0, $count);
}

// Function to generate random categories
function generate_categories($categories, $min = 1, $max = 3) {
    $count = rand($min, $max);
    shuffle($categories);
    return array_slice($categories, 0, $count);
}

// Function to generate random price
function generate_price($min = 19.99, $max = 199.99) {
    return round(rand($min * 100, $max * 100) / 100, 2);
}

// Function to generate random sale price
function generate_sale_price($regular_price) {
    $discount = rand(10, 30) / 100; // 10% to 30% discount
    return round($regular_price * (1 - $discount), 2);
}

// Generate WXR (WordPress eXtended RSS) file
$wxr_file = $output_dir . '/demo-content.xml';

// Start XML
$xml = '<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0"
    xmlns:excerpt="http://wordpress.org/export/1.2/excerpt/"
    xmlns:content="http://purl.org/rss/1.0/modules/content/"
    xmlns:wfw="http://wellformedweb.org/CommentAPI/"
    xmlns:dc="http://purl.org/dc/elements/1.1/"
    xmlns:wp="http://wordpress.org/export/1.2/">
    <channel>
        <title>' . htmlspecialchars($site_title) . '</title>
        <link>https://example.com</link>
        <description>' . htmlspecialchars($site_description) . '</description>
        <pubDate>' . date('D, d M Y H:i:s +0000') . '</pubDate>
        <language>en-US</language>
        <wp:wxr_version>1.2</wp:wxr_version>
        <wp:base_site_url>https://example.com</wp:base_site_url>
        <wp:base_blog_url>https://example.com</wp:base_blog_url>
        <wp:author>
            <wp:author_id>1</wp:author_id>
            <wp:author_login>admin</wp:author_login>
            <wp:author_email>' . $admin_email . '</wp:author_email>
            <wp:author_display_name><![CDATA[Admin]]></wp:author_display_name>
            <wp:author_first_name><![CDATA[Admin]]></wp:author_first_name>
            <wp:author_last_name><![CDATA[User]]></wp:author_last_name>
        </wp:author>';

// Add categories
foreach ($categories as $category) {
    $xml .= '
        <wp:category>
            <wp:term_id>' . (array_search($category, $categories) + 1) . '</wp:term_id>
            <wp:category_nicename>' . strtolower(str_replace(' ', '-', $category)) . '</wp:category_nicename>
            <wp:category_parent></wp:category_parent>
            <wp:cat_name><![CDATA[' . $category . ']]></wp:cat_name>
        </wp:category>';
}

// Add tags
foreach ($tags as $tag) {
    $xml .= '
        <wp:tag>
            <wp:term_id>' . (array_search($tag, $tags) + 1) . '</wp:term_id>
            <wp:tag_slug>' . strtolower(str_replace(' ', '-', $tag)) . '</wp:tag_slug>
            <wp:tag_name><![CDATA[' . $tag . ']]></wp:tag_name>
        </wp:tag>';
}

// Add product categories
foreach ($product_categories as $category) {
    $xml .= '
        <wp:term>
            <wp:term_id>' . (array_search($category, $product_categories) + 100) . '</wp:term_id>
            <wp:term_taxonomy>product_cat</wp:term_taxonomy>
            <wp:term_slug>' . strtolower(str_replace(' ', '-', $category)) . '</wp:term_slug>
            <wp:term_parent></wp:term_parent>
            <wp:term_name><![CDATA[' . $category . ']]></wp:term_name>
        </wp:term>';
}

// Add product tags
foreach ($product_tags as $tag) {
    $xml .= '
        <wp:term>
            <wp:term_id>' . (array_search($tag, $product_tags) + 200) . '</wp:term_id>
            <wp:term_taxonomy>product_tag</wp:term_taxonomy>
            <wp:term_slug>' . strtolower(str_replace(' ', '-', $tag)) . '</wp:term_slug>
            <wp:term_name><![CDATA[' . $tag . ']]></wp:term_name>
        </wp:term>';
}

// Generate posts
echo "{$colors['cyan']}Generating {$post_count} blog posts...{$colors['reset']}\n";
shuffle($post_titles);
for ($i = 0; $i < $post_count; $i++) {
    $post_id = 1000 + $i;
    $title = $post_titles[$i % count($post_titles)];
    $slug = strtolower(str_replace(' ', '-', $title));
    $date = date('Y-m-d H:i:s', strtotime('-' . rand(1, 60) . ' days'));
    $gmt_date = gmdate('Y-m-d H:i:s', strtotime($date));
    $content = generate_paragraphs(4, 8);
    $excerpt = substr(strip_tags($content), 0, 150) . '...';
    $post_categories = generate_categories($categories);
    $post_tags = generate_tags($tags);
    
    $xml .= '
        <item>
            <title>' . htmlspecialchars($title) . '</title>
            <link>https://example.com/' . $slug . '/</link>
            <pubDate>' . date('D, d M Y H:i:s +0000', strtotime($date)) . '</pubDate>
            <dc:creator>admin</dc:creator>
            <guid isPermaLink="false">https://example.com/?p=' . $post_id . '</guid>
            <description></description>
            <content:encoded><![CDATA[' . $content . ']]></content:encoded>
            <excerpt:encoded><![CDATA[' . $excerpt . ']]></excerpt:encoded>
            <wp:post_id>' . $post_id . '</wp:post_id>
            <wp:post_date>' . $date . '</wp:post_date>
            <wp:post_date_gmt>' . $gmt_date . '</wp:post_date_gmt>
            <wp:comment_status>open</wp:comment_status>
            <wp:ping_status>open</wp:ping_status>
            <wp:post_name>' . $slug . '</wp:post_name>
            <wp:status>publish</wp:status>
            <wp:post_parent>0</wp:post_parent>
            <wp:menu_order>0</wp:menu_order>
            <wp:post_type>post</wp:post_type>
            <wp:post_password></wp:post_password>
            <wp:is_sticky>0</wp:is_sticky>';
    
    // Add categories
    foreach ($post_categories as $category) {
        $cat_id = array_search($category, $categories) + 1;
        $xml .= '
            <category domain="category" nicename="' . strtolower(str_replace(' ', '-', $category)) . '"><![CDATA[' . $category . ']]></category>';
    }
    
    // Add tags
    foreach ($post_tags as $tag) {
        $xml .= '
            <category domain="post_tag" nicename="' . strtolower(str_replace(' ', '-', $tag)) . '"><![CDATA[' . $tag . ']]></category>';
    }
    
    $xml .= '
        </item>';
}

// Generate pages
echo "{$colors['cyan']}Generating {$page_count} pages...{$colors['reset']}\n";
shuffle($page_titles);
for ($i = 0; $i < $page_count; $i++) {
    $page_id = 2000 + $i;
    $title = $page_titles[$i % count($page_titles)];
    $slug = strtolower(str_replace(' ', '-', $title));
    $date = date('Y-m-d H:i:s', strtotime('-' . rand(1, 30) . ' days'));
    $gmt_date = gmdate('Y-m-d H:i:s', strtotime($date));
    $content = generate_paragraphs(3, 6);
    
    $xml .= '
        <item>
            <title>' . htmlspecialchars($title) . '</title>
            <link>https://example.com/' . $slug . '/</link>
            <pubDate>' . date('D, d M Y H:i:s +0000', strtotime($date)) . '</pubDate>
            <dc:creator>admin</dc:creator>
            <guid isPermaLink="false">https://example.com/?page_id=' . $page_id . '</guid>
            <description></description>
            <content:encoded><![CDATA[' . $content . ']]></content:encoded>
            <excerpt:encoded><![CDATA[]]></excerpt:encoded>
            <wp:post_id>' . $page_id . '</wp:post_id>
            <wp:post_date>' . $date . '</wp:post_date>
            <wp:post_date_gmt>' . $gmt_date . '</wp:post_date_gmt>
            <wp:comment_status>closed</wp:comment_status>
            <wp:ping_status>closed</wp:ping_status>
            <wp:post_name>' . $slug . '</wp:post_name>
            <wp:status>publish</wp:status>
            <wp:post_parent>0</wp:post_parent>
            <wp:menu_order>' . $i . '</wp:menu_order>
            <wp:post_type>page</wp:post_type>
            <wp:post_password></wp:post_password>
        </item>';
}

// Generate products
echo "{$colors['cyan']}Generating {$product_count} products...{$colors['reset']}\n";
shuffle($product_titles);
for ($i = 0; $i < $product_count; $i++) {
    $product_id = 3000 + $i;
    $title = $product_titles[$i % count($product_titles)];
    $slug = strtolower(str_replace(' ', '-', $title));
    $date = date('Y-m-d H:i:s', strtotime('-' . rand(1, 45) . ' days'));
    $gmt_date = gmdate('Y-m-d H:i:s', strtotime($date));
    $content = $product_descriptions[array_rand($product_descriptions)] . "\n\n" . generate_paragraphs(2, 4);
    $short_description = $product_short_descriptions[array_rand($product_short_descriptions)];
    $regular_price = generate_price();
    $sale_price = (rand(0, 1) == 1) ? generate_sale_price($regular_price) : '';
    $product_cats = generate_categories($product_categories);
    $product_ts = generate_tags($product_tags);
    $sku = 'AQL-' . str_pad($i + 1, 3, '0', STR_PAD_LEFT);
    $stock = rand(5, 100);
    $featured = (rand(0, 5) == 0) ? 'yes' : 'no'; // 1 in 6 chance of being featured
    
    $xml .= '
        <item>
            <title>' . htmlspecialchars($title) . '</title>
            <link>https://example.com/product/' . $slug . '/</link>
            <pubDate>' . date('D, d M Y H:i:s +0000', strtotime($date)) . '</pubDate>
            <dc:creator>admin</dc:creator>
            <guid isPermaLink="false">https://example.com/product/' . $slug . '/</guid>
            <description></description>
            <content:encoded><![CDATA[' . $content . ']]></content:encoded>
            <excerpt:encoded><![CDATA[' . $short_description . ']]></excerpt:encoded>
            <wp:post_id>' . $product_id . '</wp:post_id>
            <wp:post_date>' . $date . '</wp:post_date>
            <wp:post_date_gmt>' . $gmt_date . '</wp:post_date_gmt>
            <wp:comment_status>open</wp:comment_status>
            <wp:ping_status>closed</wp:ping_status>
            <wp:post_name>' . $slug . '</wp:post_name>
            <wp:status>publish</wp:status>
            <wp:post_parent>0</wp:post_parent>
            <wp:menu_order>0</wp:menu_order>
            <wp:post_type>product</wp:post_type>
            <wp:post_password></wp:post_password>';
    
    // Add product categories
    foreach ($product_cats as $category) {
        $cat_id = array_search($category, $product_categories) + 100;
        $xml .= '
            <category domain="product_cat" nicename="' . strtolower(str_replace(' ', '-', $category)) . '"><![CDATA[' . $category . ']]></category>';
    }
    
    // Add product tags
    foreach ($product_ts as $tag) {
        $xml .= '
            <category domain="product_tag" nicename="' . strtolower(str_replace(' ', '-', $tag)) . '"><![CDATA[' . $tag . ']]></category>';
    }
    
    // Add product meta
    $xml .= '
            <wp:postmeta>
                <wp:meta_key>_sku</wp:meta_key>
                <wp:meta_value><![CDATA[' . $sku . ']]></wp:meta_value>
            </wp:postmeta>
            <wp:postmeta>
                <wp:meta_key>_regular_price</wp:meta_key>
                <wp:meta_value><![CDATA[' . $regular_price . ']]></wp:meta_value>
            </wp:postmeta>';
    
    if (!empty($sale_price)) {
        $xml .= '
            <wp:postmeta>
                <wp:meta_key>_sale_price</wp:meta_key>
                <wp:meta_value><![CDATA[' . $sale_price . ']]></wp:meta_value>
            </wp:postmeta>
            <wp:postmeta>
                <wp:meta_key>_price</wp:meta_key>
                <wp:meta_value><![CDATA[' . $sale_price . ']]></wp:meta_value>
            </wp:postmeta>';
    } else {
        $xml .= '
            <wp:postmeta>
                <wp:meta_key>_price</wp:meta_key>
                <wp:meta_value><![CDATA[' . $regular_price . ']]></wp:meta_value>
            </wp:postmeta>';
    }
    
    $xml .= '
            <wp:postmeta>
                <wp:meta_key>_stock</wp:meta_key>
                <wp:meta_value><![CDATA[' . $stock . ']]></wp:meta_value>
            </wp:postmeta>
            <wp:postmeta>
                <wp:meta_key>_stock_status</wp:meta_key>
                <wp:meta_value><![CDATA[instock]]></wp:meta_value>
            </wp:postmeta>
            <wp:postmeta>
                <wp:meta_key>_manage_stock</wp:meta_key>
                <wp:meta_value><![CDATA[yes]]></wp:meta_value>
            </wp:postmeta>
            <wp:postmeta>
                <wp:meta_key>_featured</wp:meta_key>
                <wp:meta_value><![CDATA[' . $featured . ']]></wp:meta_value>
            </wp:postmeta>
            <wp:postmeta>
                <wp:meta_key>_weight</wp:meta_key>
                <wp:meta_value><![CDATA[' . rand(1, 10) / 10 . ']]></wp:meta_value>
            </wp:postmeta>
            <wp:postmeta>
                <wp:meta_key>_length</wp:meta_key>
                <wp:meta_value><![CDATA[' . rand(5, 20) . ']]></wp:meta_value>
            </wp:postmeta>
            <wp:postmeta>
                <wp:meta_key>_width</wp:meta_key>
                <wp:meta_value><![CDATA[' . rand(5, 20) . ']]></wp:meta_value>
            </wp:postmeta>
            <wp:postmeta>
                <wp:meta_key>_height</wp:meta_key>
                <wp:meta_value><![CDATA[' . rand(5, 20) . ']]></wp:meta_value>
            </wp:postmeta>
        </item>';
}

// Add homepage
$xml .= '
        <item>
            <title>Home</title>
            <link>https://example.com/</link>
            <pubDate>' . date('D, d M Y H:i:s +0000', strtotime('-60 days')) . '</pubDate>
            <dc:creator>admin</dc:creator>
            <guid isPermaLink="false">https://example.com/?page_id=2</guid>
            <description></description>
            <content:encoded><![CDATA[<!-- wp:cover {"url":"https://example.com/wp-content/uploads/2023/01/hero-image.jpg","id":3001,"dimRatio":50,"overlayColor":"primary","minHeight":800,"contentPosition":"center center","align":"full"} -->
<div class="wp-block-cover alignfull has-primary-background-color" style="min-height:800px"><span aria-hidden="true" class="wp-block-cover__background has-primary-background-color has-background-dim"></span><div class="wp-block-cover__inner-container"><!-- wp:heading {"textAlign":"center","level":1,"textColor":"white","fontSize":"huge"} -->
<h1 class="has-text-align-center has-white-color has-text-color has-huge-font-size">Welcome to AquaLuxe</h1>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center","textColor":"white","fontSize":"large"} -->
<p class="has-text-align-center has-white-color has-text-color has-large-font-size">Experience luxury wellness and rejuvenation</p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons"><!-- wp:button {"backgroundColor":"accent","textColor":"white"} -->
<div class="wp-block-button"><a class="wp-block-button__link has-white-color has-accent-background-color has-text-color has-background">Book Now</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div></div>
<!-- /wp:cover -->

<!-- wp:group {"align":"full","backgroundColor":"white","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull has-white-background-color has-background"><!-- wp:columns {"align":"wide","style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60"}}}} -->
<div class="wp-block-columns alignwide" style="padding-top:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--60)"><!-- wp:column -->
<div class="wp-block-column"><!-- wp:heading {"textAlign":"center"} -->
<h2 class="has-text-align-center">Our Services</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">Discover our range of luxury treatments designed to rejuvenate your body and mind.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->

<!-- wp:columns {"align":"wide","style":{"spacing":{"padding":{"bottom":"var:preset|spacing|60"}}}} -->
<div class="wp-block-columns alignwide" style="padding-bottom:var(--wp--preset--spacing--60)"><!-- wp:column -->
<div class="wp-block-column"><!-- wp:image {"align":"center","id":3002,"sizeSlug":"large","linkDestination":"none"} -->
<figure class="wp-block-image aligncenter size-large"><img src="https://example.com/wp-content/uploads/2023/01/service-1.jpg" alt="Luxury Massage" class="wp-image-3002"/></figure>
<!-- /wp:image -->

<!-- wp:heading {"textAlign":"center","level":3} -->
<h3 class="has-text-align-center">Luxury Massages</h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">Our signature massages combine traditional techniques with modern approaches for ultimate relaxation.</p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons"><!-- wp:button {"className":"is-style-outline"} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link">Learn More</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:image {"align":"center","id":3003,"sizeSlug":"large","linkDestination":"none"} -->
<figure class="wp-block-image aligncenter size-large"><img src="https://example.com/wp-content/uploads/2023/01/service-2.jpg" alt="Facial Treatments" class="wp-image-3003"/></figure>
<!-- /wp:image -->

<!-- wp:heading {"textAlign":"center","level":3} -->
<h3 class="has-text-align-center">Facial Treatments</h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">Rejuvenate your skin with our premium facial treatments using high-quality organic products.</p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons"><!-- wp:button {"className":"is-style-outline"} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link">Learn More</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:image {"align":"center","id":3004,"sizeSlug":"large","linkDestination":"none"} -->
<figure class="wp-block-image aligncenter size-large"><img src="https://example.com/wp-content/uploads/2023/01/service-3.jpg" alt="Hydrotherapy" class="wp-image-3004"/></figure>
<!-- /wp:image -->

<!-- wp:heading {"textAlign":"center","level":3} -->
<h3 class="has-text-align-center">Hydrotherapy</h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">Experience the healing power of water with our state-of-the-art hydrotherapy treatments.</p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons"><!-- wp:button {"className":"is-style-outline"} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link">Learn More</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group -->

<!-- wp:cover {"url":"https://example.com/wp-content/uploads/2023/01/parallax-bg.jpg","id":3005,"dimRatio":70,"overlayColor":"secondary","minHeight":500,"contentPosition":"center center","align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60"}}}} -->
<div class="wp-block-cover alignfull has-secondary-background-color" style="padding-top:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--60);min-height:500px"><span aria-hidden="true" class="wp-block-cover__background has-secondary-background-color has-background-dim-70 has-background-dim"></span><div class="wp-block-cover__inner-container"><!-- wp:heading {"textAlign":"center","textColor":"white"} -->
<h2 class="has-text-align-center has-white-color has-text-color">Experience Luxury Wellness</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center","textColor":"white"} -->
<p class="has-text-align-center has-white-color has-text-color">Our spa offers a sanctuary of peace and tranquility where you can escape the stresses of everyday life.</p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons"><!-- wp:button {"backgroundColor":"white","textColor":"secondary"} -->
<div class="wp-block-button"><a class="wp-block-button__link has-secondary-color has-white-background-color has-text-color has-background">Book Your Experience</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div></div>
<!-- /wp:cover -->

<!-- wp:group {"align":"full","backgroundColor":"white","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull has-white-background-color has-background"><!-- wp:columns {"align":"wide","style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60"}}}} -->
<div class="wp-block-columns alignwide" style="padding-top:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--60)"><!-- wp:column -->
<div class="wp-block-column"><!-- wp:heading {"textAlign":"center"} -->
<h2 class="has-text-align-center">Featured Products</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">Continue your wellness journey at home with our premium products.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->

<!-- wp:woocommerce/product-new {"columns":4,"rows":1,"alignButtons":true,"contentVisibility":{"image":true,"title":true,"price":true,"rating":true,"button":true}} /--></div>
<!-- /wp:group -->

<!-- wp:group {"align":"full","backgroundColor":"secondary","textColor":"white","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull has-white-color has-secondary-background-color has-text-color has-background"><!-- wp:columns {"align":"wide","style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60"}}}} -->
<div class="wp-block-columns alignwide" style="padding-top:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--60)"><!-- wp:column {"width":"60%"} -->
<div class="wp-block-column" style="flex-basis:60%"><!-- wp:heading -->
<h2>Subscribe to Our Newsletter</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Stay updated with our latest offers, new treatments, and wellness tips.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column -->

<!-- wp:column {"width":"40%"} -->
<div class="wp-block-column" style="flex-basis:40%"><!-- wp:html -->
<form class="newsletter-form">
  <div class="flex">
    <input type="email" placeholder="Your email address" class="w-full px-4 py-2 rounded-l-md">
    <button type="submit" class="px-4 py-2 bg-accent-500 text-white rounded-r-md">Subscribe</button>
  </div>
</form>
<!-- /wp:html --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group -->]]></content:encoded>
            <excerpt:encoded><![CDATA[]]></excerpt:encoded>
            <wp:post_id>2</wp:post_id>
            <wp:post_date>' . date('Y-m-d H:i:s', strtotime('-60 days')) . '</wp:post_date>
            <wp:post_date_gmt>' . gmdate('Y-m-d H:i:s', strtotime('-60 days')) . '</wp:post_date_gmt>
            <wp:comment_status>closed</wp:comment_status>
            <wp:ping_status>closed</wp:ping_status>
            <wp:post_name>home</wp:post_name>
            <wp:status>publish</wp:status>
            <wp:post_parent>0</wp:post_parent>
            <wp:menu_order>0</wp:menu_order>
            <wp:post_type>page</wp:post_type>
            <wp:post_password></wp:post_password>
            <wp:postmeta>
                <wp:meta_key>_wp_page_template</wp:meta_key>
                <wp:meta_value><![CDATA[front-page.php]]></wp:meta_value>
            </wp:postmeta>
        </item>';

// Close XML
$xml .= '
    </channel>
</rss>';

// Write XML to file
if (file_put_contents($wxr_file, $xml)) {
    echo "{$colors['green']}Successfully generated demo content: {$wxr_file}{$colors['reset']}\n";
} else {
    echo "{$colors['red']}Failed to write demo content to file: {$wxr_file}{$colors['reset']}\n";
    exit(1);
}

// Create theme options file
$theme_options_file = $output_dir . '/theme-options.json';
$theme_options = [
    'aqualuxe_primary_color' => '#0073aa',
    'aqualuxe_secondary_color' => '#23282d',
    'aqualuxe_accent_color' => '#00a0d2',
    'aqualuxe_container_width' => '1200',
    'aqualuxe_body_font' => 'Inter, system-ui, sans-serif',
    'aqualuxe_heading_font' => 'Merriweather, Georgia, serif',
    'aqualuxe_footer_text' => '© ' . date('Y') . ' AquaLuxe. All rights reserved.',
    'aqualuxe_logo_size' => '180',
    'aqualuxe_enable_dark_mode' => true,
    'aqualuxe_enable_woocommerce_integration' => true,
    'aqualuxe_header_layout' => 'centered',
    'aqualuxe_footer_layout' => 'four-columns',
    'aqualuxe_blog_layout' => 'grid',
    'aqualuxe_shop_layout' => 'grid',
    'aqualuxe_product_layout' => 'standard',
    'aqualuxe_sidebar_position' => 'right',
    'aqualuxe_enable_breadcrumbs' => true,
    'aqualuxe_enable_back_to_top' => true,
    'aqualuxe_enable_sticky_header' => true,
    'aqualuxe_enable_ajax_cart' => true,
    'aqualuxe_enable_quick_view' => true,
    'aqualuxe_enable_wishlist' => true,
];

if (file_put_contents($theme_options_file, json_encode($theme_options, JSON_PRETTY_PRINT))) {
    echo "{$colors['green']}Successfully generated theme options: {$theme_options_file}{$colors['reset']}\n";
} else {
    echo "{$colors['red']}Failed to write theme options to file: {$theme_options_file}{$colors['reset']}\n";
    exit(1);
}

// Create widget data file
$widget_data_file = $output_dir . '/widget-data.json';
$widget_data = [
    'sidebar-1' => [
        [
            'id_base' => 'search',
            'name' => 'Search',
            'settings' => [
                'title' => 'Search'
            ]
        ],
        [
            'id_base' => 'recent-posts',
            'name' => 'Recent Posts',
            'settings' => [
                'title' => 'Recent Posts',
                'number' => 5,
                'show_date' => true
            ]
        ],
        [
            'id_base' => 'categories',
            'name' => 'Categories',
            'settings' => [
                'title' => 'Categories',
                'count' => true,
                'hierarchical' => true
            ]
        ],
        [
            'id_base' => 'tag_cloud',
            'name' => 'Tag Cloud',
            'settings' => [
                'title' => 'Tags',
                'count' => true
            ]
        ]
    ],
    'shop-sidebar' => [
        [
            'id_base' => 'woocommerce_product_search',
            'name' => 'Product Search',
            'settings' => [
                'title' => 'Search Products'
            ]
        ],
        [
            'id_base' => 'woocommerce_price_filter',
            'name' => 'Filter by Price',
            'settings' => [
                'title' => 'Filter by Price'
            ]
        ],
        [
            'id_base' => 'woocommerce_product_categories',
            'name' => 'Product Categories',
            'settings' => [
                'title' => 'Product Categories',
                'count' => true,
                'hierarchical' => true
            ]
        ],
        [
            'id_base' => 'woocommerce_products',
            'name' => 'Products',
            'settings' => [
                'title' => 'Featured Products',
                'number' => 5,
                'show' => 'featured',
                'orderby' => 'date',
                'order' => 'desc',
                'hide_free' => 0,
                'show_hidden' => 0
            ]
        ]
    ],
    'footer-1' => [
        [
            'id_base' => 'text',
            'name' => 'Text',
            'settings' => [
                'title' => 'About Us',
                'text' => 'AquaLuxe is a premium spa and wellness center dedicated to providing luxury treatments and products for your well-being.',
                'filter' => true
            ]
        ]
    ],
    'footer-2' => [
        [
            'id_base' => 'nav_menu',
            'name' => 'Navigation Menu',
            'settings' => [
                'title' => 'Quick Links',
                'nav_menu' => 1
            ]
        ]
    ],
    'footer-3' => [
        [
            'id_base' => 'woocommerce_products',
            'name' => 'Products',
            'settings' => [
                'title' => 'Latest Products',
                'number' => 3,
                'show' => 'latest',
                'orderby' => 'date',
                'order' => 'desc',
                'hide_free' => 0,
                'show_hidden' => 0
            ]
        ]
    ],
    'footer-4' => [
        [
            'id_base' => 'text',
            'name' => 'Text',
            'settings' => [
                'title' => 'Contact Us',
                'text' => "123 Spa Street\nLuxury City, LC 12345\nPhone: (123) 456-7890\nEmail: info@aqualuxe.example.com",
                'filter' => true
            ]
        ]
    ]
];

if (file_put_contents($widget_data_file, json_encode($widget_data, JSON_PRETTY_PRINT))) {
    echo "{$colors['green']}Successfully generated widget data: {$widget_data_file}{$colors['reset']}\n";
} else {
    echo "{$colors['red']}Failed to write widget data to file: {$widget_data_file}{$colors['reset']}\n";
    exit(1);
}

// Create menu data file
$menu_data_file = $output_dir . '/menu-data.json';
$menu_data = [
    'menus' => [
        [
            'name' => 'Main Menu',
            'location' => 'primary',
            'items' => [
                [
                    'title' => 'Home',
                    'url' => 'https://example.com/',
                    'position' => 1
                ],
                [
                    'title' => 'About',
                    'url' => 'https://example.com/about-us/',
                    'position' => 2
                ],
                [
                    'title' => 'Services',
                    'url' => 'https://example.com/our-services/',
                    'position' => 3,
                    'children' => [
                        [
                            'title' => 'Massages',
                            'url' => 'https://example.com/our-services/massages/',
                            'position' => 1
                        ],
                        [
                            'title' => 'Facials',
                            'url' => 'https://example.com/our-services/facials/',
                            'position' => 2
                        ],
                        [
                            'title' => 'Body Treatments',
                            'url' => 'https://example.com/our-services/body-treatments/',
                            'position' => 3
                        ],
                        [
                            'title' => 'Hydrotherapy',
                            'url' => 'https://example.com/our-services/hydrotherapy/',
                            'position' => 4
                        ]
                    ]
                ],
                [
                    'title' => 'Shop',
                    'url' => 'https://example.com/shop/',
                    'position' => 4,
                    'children' => [
                        [
                            'title' => 'All Products',
                            'url' => 'https://example.com/shop/',
                            'position' => 1
                        ],
                        [
                            'title' => 'Skincare',
                            'url' => 'https://example.com/product-category/skincare/',
                            'position' => 2
                        ],
                        [
                            'title' => 'Body Care',
                            'url' => 'https://example.com/product-category/body-care/',
                            'position' => 3
                        ],
                        [
                            'title' => 'Aromatherapy',
                            'url' => 'https://example.com/product-category/aromatherapy/',
                            'position' => 4
                        ],
                        [
                            'title' => 'Gift Sets',
                            'url' => 'https://example.com/product-category/gift-sets/',
                            'position' => 5
                        ]
                    ]
                ],
                [
                    'title' => 'Blog',
                    'url' => 'https://example.com/blog/',
                    'position' => 5
                ],
                [
                    'title' => 'Contact',
                    'url' => 'https://example.com/contact-us/',
                    'position' => 6
                ]
            ]
        ],
        [
            'name' => 'Footer Menu',
            'location' => 'footer',
            'items' => [
                [
                    'title' => 'Home',
                    'url' => 'https://example.com/',
                    'position' => 1
                ],
                [
                    'title' => 'About',
                    'url' => 'https://example.com/about-us/',
                    'position' => 2
                ],
                [
                    'title' => 'Services',
                    'url' => 'https://example.com/our-services/',
                    'position' => 3
                ],
                [
                    'title' => 'Shop',
                    'url' => 'https://example.com/shop/',
                    'position' => 4
                ],
                [
                    'title' => 'Blog',
                    'url' => 'https://example.com/blog/',
                    'position' => 5
                ],
                [
                    'title' => 'Contact',
                    'url' => 'https://example.com/contact-us/',
                    'position' => 6
                ],
                [
                    'title' => 'Privacy Policy',
                    'url' => 'https://example.com/privacy-policy/',
                    'position' => 7
                ],
                [
                    'title' => 'Terms & Conditions',
                    'url' => 'https://example.com/terms-conditions/',
                    'position' => 8
                ]
            ]
        ]
    ]
];

if (file_put_contents($menu_data_file, json_encode($menu_data, JSON_PRETTY_PRINT))) {
    echo "{$colors['green']}Successfully generated menu data: {$menu_data_file}{$colors['reset']}\n";
} else {
    echo "{$colors['red']}Failed to write menu data to file: {$menu_data_file}{$colors['reset']}\n";
    exit(1);
}

// Create README file for demo content
$readme_file = $output_dir . '/README.md';
$readme_content = "# AquaLuxe Theme Demo Content

This directory contains demo content for the AquaLuxe WordPress theme.

## Files

- `demo-content.xml`: WordPress WXR (XML) file containing posts, pages, and products
- `theme-options.json`: Theme customizer options
- `widget-data.json`: Widget configuration data
- `menu-data.json`: Menu structure data

## Installation Instructions

### Import Demo Content

1. Log in to your WordPress admin dashboard
2. Go to Tools > Import
3. Select 'WordPress' and install the importer if prompted
4. Choose the `demo-content.xml` file from this directory
5. Check 'Download and import file attachments' if you want to import demo images
6. Click 'Submit' to import the content

### Import Theme Options

1. Go to Appearance > Customize
2. Click on 'Import/Export' in the customizer menu
3. Choose the `theme-options.json` file from this directory
4. Click 'Import' to apply the theme options

### Import Widgets

1. Go to Appearance > Widgets
2. Click on 'Import Widgets' button
3. Choose the `widget-data.json` file from this directory
4. Click 'Import' to apply the widget configuration

### Import Menus

1. Go to Appearance > Menus
2. Click on 'Import' button
3. Choose the `menu-data.json` file from this directory
4. Click 'Import' to create the menus

## Note

The demo content is designed to showcase the features of the AquaLuxe theme. You may want to modify or remove some of the content before using the theme in a production environment.

";

if (file_put_contents($readme_file, $readme_content)) {
    echo "{$colors['green']}Successfully generated README: {$readme_file}{$colors['reset']}\n";
} else {
    echo "{$colors['red']}Failed to write README to file: {$readme_file}{$colors['reset']}\n";
    exit(1);
}

echo "\n{$colors['bold']}{$colors['green']}Demo content generation complete!{$colors['reset']}\n";
echo "{$colors['cyan']}Files created in: {$output_dir}{$colors['reset']}\n";