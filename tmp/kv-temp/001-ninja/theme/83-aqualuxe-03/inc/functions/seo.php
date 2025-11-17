<?php
/**
 * SEO Functions
 * 
 * Search Engine Optimization features including schema markup, meta tags, and sitemap generation
 * 
 * @package KV_Enterprise
 * @version 1.0.0
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit('Direct access forbidden.');
}

/**
 * SEO Manager Class
 * Comprehensive SEO optimization
 */
class KV_SEO_Manager {
    
    /**
     * Initialize SEO features
     * 
     * @return void
     */
    public static function init() {
        // Meta tags
        add_action('wp_head', [__CLASS__, 'add_meta_tags'], 1);
        
        // Open Graph tags
        add_action('wp_head', [__CLASS__, 'add_open_graph_tags'], 5);
        
        // Twitter Card tags
        add_action('wp_head', [__CLASS__, 'add_twitter_card_tags'], 6);
        
        // Schema markup
        add_action('wp_head', [__CLASS__, 'add_schema_markup'], 10);
        
        // XML Sitemap
        add_action('init', [__CLASS__, 'init_sitemap']);
        
        // Robots.txt
        add_filter('robots_txt', [__CLASS__, 'customize_robots_txt'], 10, 2);
        
        // Canonical URLs
        add_action('wp_head', [__CLASS__, 'add_canonical_url'], 10);
        
        // Breadcrumbs schema
        add_action('wp_head', [__CLASS__, 'add_breadcrumbs_schema'], 15);
        
        // Optimize title tags
        add_filter('wp_title', [__CLASS__, 'optimize_title_tag'], 10, 3);
        add_filter('document_title_separator', [__CLASS__, 'title_separator']);
        
        // Meta description
        add_action('wp_head', [__CLASS__, 'add_meta_description'], 5);
        
        // Remove unnecessary meta tags
        remove_action('wp_head', 'wp_generator');
        remove_action('wp_head', 'wlwmanifest_link');
        remove_action('wp_head', 'rsd_link');
        
        // Optimize permalinks
        add_filter('post_link', [__CLASS__, 'optimize_post_permalinks'], 10, 3);
        
        // Add JSON-LD structured data
        add_action('wp_footer', [__CLASS__, 'add_json_ld_data']);
        
        // Image SEO
        add_filter('wp_get_attachment_image_attributes', [__CLASS__, 'optimize_image_seo'], 10, 3);
        
        // Sitemap styles
        add_action('init', [__CLASS__, 'add_sitemap_styles']);
    }
    
    /**
     * Add meta tags
     * 
     * @return void
     */
    public static function add_meta_tags() {
        // Viewport meta tag for mobile
        echo '<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">' . "\n";
        
        // Charset
        echo '<meta charset="' . get_bloginfo('charset') . '">' . "\n";
        
        // Language
        echo '<meta name="language" content="' . get_locale() . '">' . "\n";
        
        // Author
        if (is_single()) {
            $author = get_the_author_meta('display_name');
            if ($author) {
                echo '<meta name="author" content="' . esc_attr($author) . '">' . "\n";
            }
        }
        
        // Keywords (if enabled)
        $keywords = self::get_meta_keywords();
        if ($keywords) {
            echo '<meta name="keywords" content="' . esc_attr($keywords) . '">' . "\n";
        }
        
        // Robots meta
        $robots = self::get_robots_meta();
        if ($robots) {
            echo '<meta name="robots" content="' . esc_attr($robots) . '">' . "\n";
        }
        
        // Publish date
        if (is_single()) {
            echo '<meta name="article:published_time" content="' . get_the_date('c') . '">' . "\n";
            echo '<meta name="article:modified_time" content="' . get_the_modified_date('c') . '">' . "\n";
        }
        
        // Theme color
        $theme_color = kv_get_theme_option('theme_color', '#007cba');
        echo '<meta name="theme-color" content="' . esc_attr($theme_color) . '">' . "\n";
        
        // Apple touch icon
        $apple_icon = kv_get_theme_option('apple_touch_icon', '');
        if ($apple_icon) {
            echo '<link rel="apple-touch-icon" href="' . esc_url($apple_icon) . '">' . "\n";
        }
        
        // Favicon
        $favicon = kv_get_theme_option('favicon', '');
        if ($favicon) {
            echo '<link rel="icon" href="' . esc_url($favicon) . '">' . "\n";
        }
    }
    
    /**
     * Add Open Graph tags
     * 
     * @return void
     */
    public static function add_open_graph_tags() {
        // Basic OG tags
        echo '<meta property="og:site_name" content="' . esc_attr(get_bloginfo('name')) . '">' . "\n";
        echo '<meta property="og:locale" content="' . esc_attr(get_locale()) . '">' . "\n";
        
        if (is_front_page()) {
            echo '<meta property="og:type" content="website">' . "\n";
            echo '<meta property="og:title" content="' . esc_attr(get_bloginfo('name')) . '">' . "\n";
            echo '<meta property="og:description" content="' . esc_attr(get_bloginfo('description')) . '">' . "\n";
            echo '<meta property="og:url" content="' . esc_url(home_url('/')) . '">' . "\n";
        } elseif (is_single() || is_page()) {
            echo '<meta property="og:type" content="article">' . "\n";
            echo '<meta property="og:title" content="' . esc_attr(get_the_title()) . '">' . "\n";
            
            $description = self::get_meta_description();
            echo '<meta property="og:description" content="' . esc_attr($description) . '">' . "\n";
            echo '<meta property="og:url" content="' . esc_url(get_permalink()) . '">' . "\n";
            
            // Article specific tags
            if (is_single()) {
                echo '<meta property="article:author" content="' . esc_attr(get_the_author_meta('display_name')) . '">' . "\n";
                echo '<meta property="article:published_time" content="' . get_the_date('c') . '">' . "\n";
                echo '<meta property="article:modified_time" content="' . get_the_modified_date('c') . '">' . "\n";
                
                // Categories
                $categories = get_the_category();
                foreach ($categories as $category) {
                    echo '<meta property="article:section" content="' . esc_attr($category->name) . '">' . "\n";
                }
                
                // Tags
                $tags = get_the_tags();
                if ($tags) {
                    foreach ($tags as $tag) {
                        echo '<meta property="article:tag" content="' . esc_attr($tag->name) . '">' . "\n";
                    }
                }
            }
        } elseif (is_category() || is_tag() || is_tax()) {
            echo '<meta property="og:type" content="website">' . "\n";
            echo '<meta property="og:title" content="' . esc_attr(single_term_title('', false)) . '">' . "\n";
            
            $description = term_description();
            if (empty($description)) {
                $description = sprintf(__('Browse %s archives', KV_THEME_TEXTDOMAIN), single_term_title('', false));
            }
            echo '<meta property="og:description" content="' . esc_attr(strip_tags($description)) . '">' . "\n";
            echo '<meta property="og:url" content="' . esc_url(get_term_link(get_queried_object())) . '">' . "\n";
        }
        
        // Featured image
        $image_url = self::get_featured_image_url();
        if ($image_url) {
            echo '<meta property="og:image" content="' . esc_url($image_url) . '">' . "\n";
            
            $image_data = wp_get_attachment_image_src(get_post_thumbnail_id(), 'large');
            if ($image_data) {
                echo '<meta property="og:image:width" content="' . esc_attr($image_data[1]) . '">' . "\n";
                echo '<meta property="og:image:height" content="' . esc_attr($image_data[2]) . '">' . "\n";
            }
            
            $image_alt = get_post_meta(get_post_thumbnail_id(), '_wp_attachment_image_alt', true);
            if ($image_alt) {
                echo '<meta property="og:image:alt" content="' . esc_attr($image_alt) . '">' . "\n";
            }
        }
    }
    
    /**
     * Add Twitter Card tags
     * 
     * @return void
     */
    public static function add_twitter_card_tags() {
        $twitter_username = kv_get_theme_option('twitter_username', '');
        
        // Determine card type
        $card_type = 'summary';
        if (self::get_featured_image_url()) {
            $card_type = 'summary_large_image';
        }
        
        echo '<meta name="twitter:card" content="' . esc_attr($card_type) . '">' . "\n";
        
        if ($twitter_username) {
            echo '<meta name="twitter:site" content="@' . esc_attr($twitter_username) . '">' . "\n";
        }
        
        if (is_front_page()) {
            echo '<meta name="twitter:title" content="' . esc_attr(get_bloginfo('name')) . '">' . "\n";
            echo '<meta name="twitter:description" content="' . esc_attr(get_bloginfo('description')) . '">' . "\n";
        } elseif (is_single() || is_page()) {
            echo '<meta name="twitter:title" content="' . esc_attr(get_the_title()) . '">' . "\n";
            
            $description = self::get_meta_description();
            echo '<meta name="twitter:description" content="' . esc_attr($description) . '">' . "\n";
            
            if (is_single()) {
                $author_twitter = get_the_author_meta('twitter');
                if ($author_twitter) {
                    echo '<meta name="twitter:creator" content="@' . esc_attr($author_twitter) . '">' . "\n";
                }
            }
        }
        
        $image_url = self::get_featured_image_url();
        if ($image_url) {
            echo '<meta name="twitter:image" content="' . esc_url($image_url) . '">' . "\n";
            
            $image_alt = get_post_meta(get_post_thumbnail_id(), '_wp_attachment_image_alt', true);
            if ($image_alt) {
                echo '<meta name="twitter:image:alt" content="' . esc_attr($image_alt) . '">' . "\n";
            }
        }
    }
    
    /**
     * Add schema markup
     * 
     * @return void
     */
    public static function add_schema_markup() {
        if (is_front_page()) {
            self::add_organization_schema();
        } elseif (is_single()) {
            self::add_article_schema();
        } elseif (is_page()) {
            self::add_webpage_schema();
        } elseif (is_author()) {
            self::add_person_schema();
        }
    }
    
    /**
     * Add Organization schema
     * 
     * @return void
     */
    private static function add_organization_schema() {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => get_bloginfo('name'),
            'description' => get_bloginfo('description'),
            'url' => home_url('/'),
        ];
        
        // Logo
        $logo = kv_get_theme_option('logo_url', '');
        if ($logo) {
            $schema['logo'] = $logo;
        }
        
        // Contact info
        $email = kv_get_theme_option('contact_email', '');
        $phone = kv_get_theme_option('contact_phone', '');
        $address = kv_get_theme_option('contact_address', '');
        
        if ($email || $phone || $address) {
            $contact_point = ['@type' => 'ContactPoint'];
            
            if ($email) $contact_point['email'] = $email;
            if ($phone) $contact_point['telephone'] = $phone;
            if ($address) $contact_point['address'] = $address;
            
            $schema['contactPoint'] = $contact_point;
        }
        
        // Social media profiles
        $social_profiles = [];
        $social_networks = ['facebook', 'twitter', 'instagram', 'linkedin', 'youtube'];
        
        foreach ($social_networks as $network) {
            $url = kv_get_theme_option("social_{$network}", '');
            if ($url) {
                $social_profiles[] = $url;
            }
        }
        
        if (!empty($social_profiles)) {
            $schema['sameAs'] = $social_profiles;
        }
        
        echo '<script type="application/ld+json">' . wp_json_encode($schema) . '</script>' . "\n";
    }
    
    /**
     * Add Article schema
     * 
     * @return void
     */
    private static function add_article_schema() {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => get_the_title(),
            'description' => self::get_meta_description(),
            'url' => get_permalink(),
            'datePublished' => get_the_date('c'),
            'dateModified' => get_the_modified_date('c'),
            'author' => [
                '@type' => 'Person',
                'name' => get_the_author_meta('display_name'),
                'url' => get_author_posts_url(get_the_author_meta('ID')),
            ],
            'publisher' => [
                '@type' => 'Organization',
                'name' => get_bloginfo('name'),
                'url' => home_url('/'),
            ],
        ];
        
        // Featured image
        if (has_post_thumbnail()) {
            $image_data = wp_get_attachment_image_src(get_post_thumbnail_id(), 'large');
            $schema['image'] = [
                '@type' => 'ImageObject',
                'url' => $image_data[0],
                'width' => $image_data[1],
                'height' => $image_data[2],
            ];
        }
        
        // Article section (category)
        $categories = get_the_category();
        if (!empty($categories)) {
            $schema['articleSection'] = $categories[0]->name;
        }
        
        // Keywords (tags)
        $tags = get_the_tags();
        if ($tags) {
            $keywords = array_map(function($tag) {
                return $tag->name;
            }, $tags);
            $schema['keywords'] = implode(', ', $keywords);
        }
        
        // Word count
        $content = get_post_field('post_content', get_the_ID());
        $word_count = str_word_count(strip_tags($content));
        $schema['wordCount'] = $word_count;
        
        echo '<script type="application/ld+json">' . wp_json_encode($schema) . '</script>' . "\n";
    }
    
    /**
     * Add WebPage schema
     * 
     * @return void
     */
    private static function add_webpage_schema() {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'WebPage',
            'name' => get_the_title(),
            'description' => self::get_meta_description(),
            'url' => get_permalink(),
            'datePublished' => get_the_date('c'),
            'dateModified' => get_the_modified_date('c'),
            'inLanguage' => get_locale(),
            'isPartOf' => [
                '@type' => 'WebSite',
                'name' => get_bloginfo('name'),
                'url' => home_url('/'),
            ],
        ];
        
        echo '<script type="application/ld+json">' . wp_json_encode($schema) . '</script>' . "\n";
    }
    
    /**
     * Add Person schema
     * 
     * @return void
     */
    private static function add_person_schema() {
        $author_id = get_queried_object_id();
        
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Person',
            'name' => get_the_author_meta('display_name', $author_id),
            'description' => get_the_author_meta('description', $author_id),
            'url' => get_author_posts_url($author_id),
        ];
        
        // Avatar
        $avatar_url = get_avatar_url($author_id, ['size' => 200]);
        if ($avatar_url) {
            $schema['image'] = $avatar_url;
        }
        
        // Social media
        $website = get_the_author_meta('user_url', $author_id);
        if ($website) {
            $schema['url'] = $website;
        }
        
        echo '<script type="application/ld+json">' . wp_json_encode($schema) . '</script>' . "\n";
    }
    
    /**
     * Initialize sitemap
     * 
     * @return void
     */
    public static function init_sitemap() {
        add_action('init', function() {
            add_rewrite_rule('^sitemap\.xml$', 'index.php?kv_sitemap=xml', 'top');
            add_rewrite_rule('^sitemap\.xsl$', 'index.php?kv_sitemap=xsl', 'top');
        });
        
        add_filter('query_vars', function($vars) {
            $vars[] = 'kv_sitemap';
            return $vars;
        });
        
        add_action('template_redirect', [__CLASS__, 'serve_sitemap']);
    }
    
    /**
     * Serve sitemap
     * 
     * @return void
     */
    public static function serve_sitemap() {
        $sitemap_type = get_query_var('kv_sitemap');
        
        if ($sitemap_type === 'xml') {
            self::generate_xml_sitemap();
        } elseif ($sitemap_type === 'xsl') {
            self::generate_sitemap_stylesheet();
        }
    }
    
    /**
     * Generate XML sitemap
     * 
     * @return void
     */
    private static function generate_xml_sitemap() {
        header('Content-Type: application/xml; charset=utf-8');
        
        echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        echo '<?xml-stylesheet type="text/xsl" href="' . home_url('/sitemap.xsl') . '"?>' . "\n";
        echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        
        // Homepage
        echo '<url>' . "\n";
        echo '<loc>' . esc_url(home_url('/')) . '</loc>' . "\n";
        echo '<lastmod>' . date('c') . '</lastmod>' . "\n";
        echo '<changefreq>daily</changefreq>' . "\n";
        echo '<priority>1.0</priority>' . "\n";
        echo '</url>' . "\n";
        
        // Posts
        $posts = get_posts([
            'numberposts' => -1,
            'post_type' => ['post', 'page'],
            'post_status' => 'publish',
            'meta_query' => [
                'relation' => 'OR',
                [
                    'key' => '_seo_noindex',
                    'compare' => 'NOT EXISTS',
                ],
                [
                    'key' => '_seo_noindex',
                    'value' => '1',
                    'compare' => '!=',
                ],
            ],
        ]);
        
        foreach ($posts as $post) {
            echo '<url>' . "\n";
            echo '<loc>' . esc_url(get_permalink($post)) . '</loc>' . "\n";
            echo '<lastmod>' . date('c', strtotime($post->post_modified)) . '</lastmod>' . "\n";
            
            if ($post->post_type === 'post') {
                echo '<changefreq>monthly</changefreq>' . "\n";
                echo '<priority>0.8</priority>' . "\n";
            } else {
                echo '<changefreq>yearly</changefreq>' . "\n";
                echo '<priority>0.6</priority>' . "\n";
            }
            
            echo '</url>' . "\n";
        }
        
        // Categories and tags
        $terms = get_terms([
            'taxonomy' => ['category', 'post_tag'],
            'hide_empty' => true,
        ]);
        
        foreach ($terms as $term) {
            echo '<url>' . "\n";
            echo '<loc>' . esc_url(get_term_link($term)) . '</loc>' . "\n";
            echo '<changefreq>weekly</changefreq>' . "\n";
            echo '<priority>0.4</priority>' . "\n";
            echo '</url>' . "\n";
        }
        
        echo '</urlset>' . "\n";
        exit;
    }
    
    /**
     * Generate sitemap stylesheet
     * 
     * @return void
     */
    private static function generate_sitemap_stylesheet() {
        header('Content-Type: text/xsl; charset=utf-8');
        
        echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        ?>
        <xsl:stylesheet version="2.0" xmlns:html="http://www.w3.org/TR/REC-html40" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1" xmlns:sitemap="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
            <xsl:output method="html" version="1.0" encoding="UTF-8" indent="yes"/>
            <xsl:template match="/">
                <html xmlns="http://www.w3.org/1999/xhtml">
                    <head>
                        <title>XML Sitemap</title>
                        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
                        <style type="text/css">
                            body { font-family: Arial, sans-serif; font-size: 13px; color: #545353; }
                            table { border: none; border-collapse: collapse; }
                            #sitemap tr:nth-child(odd) td { background-color: #eee !important; }
                            #sitemap tbody tr:hover td { background-color: #ccc; }
                            #sitemap tbody tr:hover td, #sitemap tbody tr:hover td a { color: #000; }
                            #content { margin: 0 auto; width: 1000px; }
                            .expl { margin: 18px 3px; line-height: 1.2em; }
                            .expl a { color: #da3114; font-weight: 600; }
                            .expl a:visited { color: #da3114; }
                            a { color: #000; text-decoration: none; }
                            a:visited { color: #777; }
                            a:hover { text-decoration: underline; }
                            td { font-size: 11px; }
                            th { text-align: left; padding-right: 30px; font-size: 11px; }
                            thead th { border-bottom: 1px solid #000; cursor: pointer; }
                        </style>
                    </head>
                    <body>
                        <div id="content">
                            <h1>XML Sitemap</h1>
                            <p class="expl">
                                This is a XML Sitemap which is supposed to be processed by search engines which follow the XML Sitemap standard like Ask.com, Bing, Google and Yahoo.<br/>
                                Learn more about <a href="http://www.sitemaps.org/">XML sitemaps on sitemaps.org</a>.
                            </p>
                            <div id="sitemap">
                                <table id="sitemap-table">
                                    <thead>
                                        <tr>
                                            <th width="75%">URL</th>
                                            <th width="5%">Priority</th>
                                            <th width="5%">Change Frequency</th>
                                            <th width="15%">Last Change</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <xsl:variable name="lower" select="'abcdefghijklmnopqrstuvwxyz'"/>
                                        <xsl:variable name="upper" select="'ABCDEFGHIJKLMNOPQRSTUVWXYZ'"/>
                                        <xsl:for-each select="sitemap:urlset/sitemap:url">
                                            <tr>
                                                <td><xsl:variable name="itemURL"><xsl:value-of select="sitemap:loc"/></xsl:variable><a href="{$itemURL}"><xsl:value-of select="sitemap:loc"/></a></td>
                                                <td><xsl:value-of select="concat(sitemap:priority*100,'%')"/></td>
                                                <td><xsl:value-of select="concat(translate(substring(sitemap:changefreq, 1, 1),concat($lower, $upper),concat($upper, $lower)),substring(sitemap:changefreq, 2))"/></td>
                                                <td><xsl:value-of select="concat(substring(sitemap:lastmod,0,11),concat(' ', substring(sitemap:lastmod,12,5)))"/></td>
                                            </tr>
                                        </xsl:for-each>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </body>
                </html>
            </xsl:template>
        </xsl:stylesheet>
        <?php
        exit;
    }
    
    /**
     * Customize robots.txt
     * 
     * @param string $output Robots.txt output
     * @param bool   $public Whether site is public
     * @return string Modified output
     */
    public static function customize_robots_txt($output, $public) {
        if ($public) {
            $output .= "Sitemap: " . home_url('/sitemap.xml') . "\n";
            
            // Allow crawling of CSS and JS files
            $output .= "Allow: /wp-content/uploads/\n";
            $output .= "Allow: /wp-includes/js/\n";
            $output .= "Allow: /wp-includes/css/\n";
            
            // Disallow unnecessary directories
            $output .= "Disallow: /wp-admin/\n";
            $output .= "Disallow: /wp-includes/\n";
            $output .= "Disallow: /wp-content/plugins/\n";
            $output .= "Disallow: /wp-content/themes/\n";
            $output .= "Disallow: /trackback/\n";
            $output .= "Disallow: /feed/\n";
            $output .= "Disallow: /comments/\n";
            $output .= "Disallow: /search\n";
            $output .= "Disallow: /?s=\n";
            $output .= "Disallow: /author/\n";
            
            // Custom disallowed paths
            $disallowed_paths = kv_get_theme_option('robots_disallow', []);
            foreach ($disallowed_paths as $path) {
                $output .= "Disallow: {$path}\n";
            }
        }
        
        return $output;
    }
    
    /**
     * Add canonical URL
     * 
     * @return void
     */
    public static function add_canonical_url() {
        $canonical_url = self::get_canonical_url();
        if ($canonical_url) {
            echo '<link rel="canonical" href="' . esc_url($canonical_url) . '">' . "\n";
        }
    }
    
    /**
     * Get canonical URL
     * 
     * @return string Canonical URL
     */
    private static function get_canonical_url() {
        if (is_front_page()) {
            return home_url('/');
        } elseif (is_single() || is_page()) {
            return get_permalink();
        } elseif (is_category() || is_tag() || is_tax()) {
            return get_term_link(get_queried_object());
        } elseif (is_author()) {
            return get_author_posts_url(get_queried_object_id());
        } elseif (is_search()) {
            return home_url('/search/' . urlencode(get_search_query()));
        }
        
        return '';
    }
    
    /**
     * Add breadcrumbs schema
     * 
     * @return void
     */
    public static function add_breadcrumbs_schema() {
        if (is_front_page()) {
            return;
        }
        
        $breadcrumbs = kv_get_breadcrumbs();
        
        if (empty($breadcrumbs)) {
            return;
        }
        
        $schema_items = [];
        $position = 1;
        
        foreach ($breadcrumbs as $breadcrumb) {
            $schema_items[] = [
                '@type' => 'ListItem',
                'position' => $position,
                'name' => $breadcrumb['title'],
                'item' => $breadcrumb['url'] ?: get_permalink(),
            ];
            $position++;
        }
        
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $schema_items,
        ];
        
        echo '<script type="application/ld+json">' . wp_json_encode($schema) . '</script>' . "\n";
    }
    
    /**
     * Optimize title tag
     * 
     * @param string $title    Title
     * @param string $sep      Separator
     * @param string $seplocation Separator location
     * @return string Optimized title
     */
    public static function optimize_title_tag($title, $sep, $seplocation) {
        if (is_front_page()) {
            $site_name = get_bloginfo('name');
            $site_description = get_bloginfo('description');
            
            if ($site_description) {
                return $site_name . ' ' . $sep . ' ' . $site_description;
            }
            
            return $site_name;
        }
        
        return $title;
    }
    
    /**
     * Title separator
     * 
     * @return string Separator
     */
    public static function title_separator() {
        return '|';
    }
    
    /**
     * Add meta description
     * 
     * @return void
     */
    public static function add_meta_description() {
        $description = self::get_meta_description();
        
        if ($description) {
            echo '<meta name="description" content="' . esc_attr($description) . '">' . "\n";
        }
    }
    
    /**
     * Get meta description
     * 
     * @return string Meta description
     */
    private static function get_meta_description() {
        if (is_front_page()) {
            $description = kv_get_theme_option('home_meta_description', '');
            if (empty($description)) {
                $description = get_bloginfo('description');
            }
        } elseif (is_single() || is_page()) {
            // Custom meta description
            $description = get_post_meta(get_the_ID(), '_seo_description', true);
            
            if (empty($description)) {
                // Use excerpt or trimmed content
                $description = get_the_excerpt();
                if (empty($description)) {
                    $content = get_post_field('post_content', get_the_ID());
                    $description = wp_trim_words(strip_tags($content), 25);
                }
            }
        } elseif (is_category() || is_tag() || is_tax()) {
            $description = term_description();
            if (empty($description)) {
                $description = sprintf(__('Browse %s archives', KV_THEME_TEXTDOMAIN), single_term_title('', false));
            }
        } elseif (is_author()) {
            $description = get_the_author_meta('description');
            if (empty($description)) {
                $description = sprintf(__('Posts by %s', KV_THEME_TEXTDOMAIN), get_the_author_meta('display_name'));
            }
        } elseif (is_search()) {
            $description = sprintf(__('Search results for "%s"', KV_THEME_TEXTDOMAIN), get_search_query());
        } else {
            $description = get_bloginfo('description');
        }
        
        return wp_trim_words(strip_tags($description), 25);
    }
    
    /**
     * Get meta keywords
     * 
     * @return string Meta keywords
     */
    private static function get_meta_keywords() {
        $keywords_enabled = kv_get_theme_option('enable_meta_keywords', false);
        
        if (!$keywords_enabled) {
            return '';
        }
        
        $keywords = [];
        
        if (is_single() || is_page()) {
            // Custom keywords
            $custom_keywords = get_post_meta(get_the_ID(), '_seo_keywords', true);
            if ($custom_keywords) {
                $keywords[] = $custom_keywords;
            }
            
            // Tags as keywords
            $tags = get_the_tags();
            if ($tags) {
                foreach ($tags as $tag) {
                    $keywords[] = $tag->name;
                }
            }
            
            // Categories
            $categories = get_the_category();
            foreach ($categories as $category) {
                $keywords[] = $category->name;
            }
        }
        
        return implode(', ', array_unique($keywords));
    }
    
    /**
     * Get robots meta
     * 
     * @return string Robots meta
     */
    private static function get_robots_meta() {
        $robots = [];
        
        if (is_single() || is_page()) {
            $noindex = get_post_meta(get_the_ID(), '_seo_noindex', true);
            $nofollow = get_post_meta(get_the_ID(), '_seo_nofollow', true);
            
            if ($noindex) {
                $robots[] = 'noindex';
            } else {
                $robots[] = 'index';
            }
            
            if ($nofollow) {
                $robots[] = 'nofollow';
            } else {
                $robots[] = 'follow';
            }
        } elseif (is_search() || is_404()) {
            $robots[] = 'noindex';
            $robots[] = 'nofollow';
        } else {
            $robots[] = 'index';
            $robots[] = 'follow';
        }
        
        return implode(', ', $robots);
    }
    
    /**
     * Get featured image URL
     * 
     * @return string|false Image URL or false
     */
    private static function get_featured_image_url() {
        if (has_post_thumbnail()) {
            $image_data = wp_get_attachment_image_src(get_post_thumbnail_id(), 'large');
            return $image_data[0];
        }
        
        // Fallback to default image
        $default_image = kv_get_theme_option('default_og_image', '');
        return $default_image ?: false;
    }
    
    /**
     * Optimize post permalinks
     * 
     * @param string  $permalink Post permalink
     * @param WP_Post $post      Post object
     * @param bool    $leavename Whether to leave name
     * @return string Optimized permalink
     */
    public static function optimize_post_permalinks($permalink, $post, $leavename) {
        // Remove stop words from permalinks if enabled
        $remove_stop_words = kv_get_theme_option('remove_stop_words_permalinks', false);
        
        if ($remove_stop_words && $post->post_type === 'post') {
            $stop_words = ['a', 'an', 'and', 'are', 'as', 'at', 'be', 'by', 'for', 'from', 'has', 'he', 'in', 'is', 'it', 'its', 'of', 'on', 'that', 'the', 'to', 'was', 'will', 'with'];
            
            $slug_parts = explode('-', $post->post_name);
            $filtered_parts = array_diff($slug_parts, $stop_words);
            
            if (count($filtered_parts) !== count($slug_parts)) {
                $new_slug = implode('-', $filtered_parts);
                $permalink = str_replace($post->post_name, $new_slug, $permalink);
            }
        }
        
        return $permalink;
    }
    
    /**
     * Add JSON-LD data
     * 
     * @return void
     */
    public static function add_json_ld_data() {
        // Website schema for all pages
        $website_schema = [
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'name' => get_bloginfo('name'),
            'url' => home_url('/'),
            'potentialAction' => [
                '@type' => 'SearchAction',
                'target' => home_url('/?s={search_term_string}'),
                'query-input' => 'required name=search_term_string',
            ],
        ];
        
        echo '<script type="application/ld+json">' . wp_json_encode($website_schema) . '</script>' . "\n";
    }
    
    /**
     * Optimize image SEO
     * 
     * @param array   $attr       Image attributes
     * @param WP_Post $attachment Attachment object
     * @param string  $size       Image size
     * @return array Enhanced attributes
     */
    public static function optimize_image_seo($attr, $attachment, $size) {
        // Ensure alt text is present and meaningful
        if (empty($attr['alt'])) {
            $alt_text = get_post_meta($attachment->ID, '_wp_attachment_image_alt', true);
            
            if (empty($alt_text)) {
                // Generate alt text from filename or title
                $alt_text = $attachment->post_title;
                if (empty($alt_text)) {
                    $filename = basename(get_attached_file($attachment->ID));
                    $alt_text = sanitize_text_field(pathinfo($filename, PATHINFO_FILENAME));
                    $alt_text = str_replace(['-', '_'], ' ', $alt_text);
                }
            }
            
            $attr['alt'] = $alt_text;
        }
        
        // Add title attribute for better accessibility
        if (empty($attr['title'])) {
            $attr['title'] = $attachment->post_title ?: $attr['alt'];
        }
        
        return $attr;
    }
    
    /**
     * Add sitemap styles
     * 
     * @return void
     */
    public static function add_sitemap_styles() {
        // This is handled in the XSL stylesheet
    }
}

// Initialize SEO features
add_action('init', ['KV_SEO_Manager', 'init']);
