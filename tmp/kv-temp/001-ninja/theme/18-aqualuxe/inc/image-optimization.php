<?php
/**
 * Image optimization functionality
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * AquaLuxe Image Optimization Class
 * 
 * Handles image optimization, WebP conversion, and responsive images
 */
class AquaLuxe_Image_Optimization {
    /**
     * Constructor
     */
    public function __construct() {
        // Add WebP support
        add_filter('upload_mimes', [$this, 'add_webp_mime_type']);
        add_filter('file_is_displayable_image', [$this, 'is_webp_displayable'], 10, 2);
        
        // Add responsive image sizes
        add_action('after_setup_theme', [$this, 'add_responsive_image_sizes']);
        
        // Add srcset and sizes attributes to images
        add_filter('wp_calculate_image_srcset', [$this, 'customize_image_srcset'], 10, 5);
        
        // Add lazy loading to images
        add_filter('wp_get_attachment_image_attributes', [$this, 'add_lazy_loading_attribute'], 10, 3);
        add_filter('the_content', [$this, 'add_lazy_loading_to_content_images'], 99);
        
        // Add WebP conversion option
        add_filter('wp_handle_upload', [$this, 'create_webp_version'], 10);
        
        // Add image compression
        add_filter('wp_handle_upload', [$this, 'compress_uploaded_image'], 20);
        
        // Add image optimization settings to Media page
        add_action('admin_init', [$this, 'register_image_optimization_settings']);
    }

    /**
     * Add WebP MIME type to allowed upload types
     * 
     * @param array $mimes Allowed MIME types
     * @return array Modified MIME types
     */
    public function add_webp_mime_type($mimes) {
        $mimes['webp'] = 'image/webp';
        return $mimes;
    }

    /**
     * Make WebP images displayable in WordPress
     * 
     * @param bool $result Current displayable status
     * @param string $path Path to the image file
     * @return bool Updated displayable status
     */
    public function is_webp_displayable($result, $path) {
        if (!$result) {
            $info = @getimagesize($path);
            if ($info && $info['mime'] === 'image/webp') {
                $result = true;
            }
        }
        return $result;
    }

    /**
     * Add responsive image sizes
     */
    public function add_responsive_image_sizes() {
        // Add responsive image sizes for different devices
        add_image_size('aqualuxe-small', 400, 9999);
        add_image_size('aqualuxe-medium', 800, 9999);
        add_image_size('aqualuxe-large', 1200, 9999);
        add_image_size('aqualuxe-xlarge', 1600, 9999);
        add_image_size('aqualuxe-xxlarge', 2000, 9999);
        
        // Add responsive image sizes for featured images
        add_image_size('aqualuxe-featured-small', 600, 400, true);
        add_image_size('aqualuxe-featured-medium', 900, 600, true);
        add_image_size('aqualuxe-featured-large', 1200, 800, true);
        
        // Add responsive image sizes for product images
        add_image_size('aqualuxe-product-thumbnail', 300, 300, true);
        add_image_size('aqualuxe-product-small', 600, 600, true);
        add_image_size('aqualuxe-product-medium', 800, 800, true);
        add_image_size('aqualuxe-product-large', 1000, 1000, true);
    }

    /**
     * Customize image srcset
     * 
     * @param array $sources Image sources
     * @param array $size_array Image size array
     * @param string $image_src Image source
     * @param array $image_meta Image meta
     * @param int $attachment_id Attachment ID
     * @return array Modified sources
     */
    public function customize_image_srcset($sources, $size_array, $image_src, $image_meta, $attachment_id) {
        // Check if WebP version exists and add it to srcset
        foreach ($sources as $width => $source) {
            $webp_url = $this->get_webp_url($source['url']);
            if ($webp_url) {
                // If WebP exists, add it as a source with higher priority
                $sources[$width]['url'] = $webp_url;
            }
        }
        
        return $sources;
    }

    /**
     * Add lazy loading attribute to images
     * 
     * @param array $attr Image attributes
     * @param WP_Post $attachment Attachment post
     * @param string|array $size Image size
     * @return array Modified attributes
     */
    public function add_lazy_loading_attribute($attr, $attachment, $size) {
        // Skip lazy loading for admin
        if (is_admin()) {
            return $attr;
        }
        
        // Skip lazy loading for specific image sizes
        $skip_lazy_sizes = ['thumbnail', 'aqualuxe-thumbnail'];
        if (in_array($size, $skip_lazy_sizes)) {
            return $attr;
        }
        
        // Add lazy loading attribute
        $attr['loading'] = 'lazy';
        
        // Add data attributes for custom lazy loading
        $attr['data-src'] = $attr['src'];
        $attr['class'] = isset($attr['class']) ? $attr['class'] . ' aqualuxe-lazy' : 'aqualuxe-lazy';
        
        return $attr;
    }

    /**
     * Add lazy loading to content images
     * 
     * @param string $content Post content
     * @return string Modified content
     */
    public function add_lazy_loading_to_content_images($content) {
        // Skip for admin or feeds
        if (is_admin() || is_feed()) {
            return $content;
        }
        
        // Add loading="lazy" to all images in content
        $content = preg_replace('/<img(.*?)>/i', '<img$1 loading="lazy">', $content);
        
        return $content;
    }

    /**
     * Create WebP version of uploaded image
     * 
     * @param array $upload Upload data
     * @return array Upload data
     */
    public function create_webp_version($upload) {
        // Check if WebP conversion is enabled
        if (get_option('aqualuxe_enable_webp', 'yes') !== 'yes') {
            return $upload;
        }
        
        // Check if the uploaded file is an image
        $file = $upload['file'];
        $type = $upload['type'];
        
        if (!in_array($type, ['image/jpeg', 'image/png'])) {
            return $upload;
        }
        
        // Get image resource
        switch ($type) {
            case 'image/jpeg':
                $image = imagecreatefromjpeg($file);
                break;
            case 'image/png':
                $image = imagecreatefrompng($file);
                break;
            default:
                return $upload;
        }
        
        if (!$image) {
            return $upload;
        }
        
        // Create WebP version
        $webp_file = preg_replace('/\.(jpe?g|png)$/i', '.webp', $file);
        $quality = get_option('aqualuxe_webp_quality', 80);
        
        // Convert to WebP
        imagewebp($image, $webp_file, $quality);
        imagedestroy($image);
        
        return $upload;
    }

    /**
     * Compress uploaded image
     * 
     * @param array $upload Upload data
     * @return array Upload data
     */
    public function compress_uploaded_image($upload) {
        // Check if image compression is enabled
        if (get_option('aqualuxe_enable_compression', 'yes') !== 'yes') {
            return $upload;
        }
        
        // Check if the uploaded file is an image
        $file = $upload['file'];
        $type = $upload['type'];
        
        if (!in_array($type, ['image/jpeg', 'image/png'])) {
            return $upload;
        }
        
        // Get compression quality
        $quality = get_option('aqualuxe_compression_quality', 85);
        
        // Compress image based on type
        switch ($type) {
            case 'image/jpeg':
                $image = imagecreatefromjpeg($file);
                if ($image) {
                    imagejpeg($image, $file, $quality);
                    imagedestroy($image);
                }
                break;
            case 'image/png':
                $image = imagecreatefrompng($file);
                if ($image) {
                    // PNG quality is 0-9, convert from percentage
                    $png_quality = round(9 - (($quality / 100) * 9));
                    imagepng($image, $file, $png_quality);
                    imagedestroy($image);
                }
                break;
        }
        
        return $upload;
    }

    /**
     * Get WebP URL if it exists
     * 
     * @param string $url Original image URL
     * @return string|false WebP URL or false if not found
     */
    private function get_webp_url($url) {
        // Convert URL to file path
        $upload_dir = wp_upload_dir();
        $file_path = str_replace($upload_dir['baseurl'], $upload_dir['basedir'], $url);
        
        // Check if WebP version exists
        $webp_path = preg_replace('/\.(jpe?g|png)$/i', '.webp', $file_path);
        if (file_exists($webp_path)) {
            return preg_replace('/\.(jpe?g|png)$/i', '.webp', $url);
        }
        
        return false;
    }

    /**
     * Register image optimization settings
     */
    public function register_image_optimization_settings() {
        // Add settings section
        add_settings_section(
            'aqualuxe_image_optimization_section',
            __('AquaLuxe Image Optimization', 'aqualuxe'),
            [$this, 'image_optimization_section_callback'],
            'media'
        );
        
        // Add settings fields
        add_settings_field(
            'aqualuxe_enable_webp',
            __('Enable WebP Conversion', 'aqualuxe'),
            [$this, 'enable_webp_callback'],
            'media',
            'aqualuxe_image_optimization_section'
        );
        
        add_settings_field(
            'aqualuxe_webp_quality',
            __('WebP Quality', 'aqualuxe'),
            [$this, 'webp_quality_callback'],
            'media',
            'aqualuxe_image_optimization_section'
        );
        
        add_settings_field(
            'aqualuxe_enable_compression',
            __('Enable Image Compression', 'aqualuxe'),
            [$this, 'enable_compression_callback'],
            'media',
            'aqualuxe_image_optimization_section'
        );
        
        add_settings_field(
            'aqualuxe_compression_quality',
            __('Compression Quality', 'aqualuxe'),
            [$this, 'compression_quality_callback'],
            'media',
            'aqualuxe_image_optimization_section'
        );
        
        // Register settings
        register_setting('media', 'aqualuxe_enable_webp');
        register_setting('media', 'aqualuxe_webp_quality', [
            'type' => 'integer',
            'sanitize_callback' => [$this, 'sanitize_quality'],
        ]);
        register_setting('media', 'aqualuxe_enable_compression');
        register_setting('media', 'aqualuxe_compression_quality', [
            'type' => 'integer',
            'sanitize_callback' => [$this, 'sanitize_quality'],
        ]);
    }

    /**
     * Image optimization section callback
     */
    public function image_optimization_section_callback() {
        echo '<p>' . __('Configure image optimization settings for the AquaLuxe theme.', 'aqualuxe') . '</p>';
    }

    /**
     * Enable WebP callback
     */
    public function enable_webp_callback() {
        $value = get_option('aqualuxe_enable_webp', 'yes');
        echo '<select name="aqualuxe_enable_webp">
            <option value="yes" ' . selected($value, 'yes', false) . '>' . __('Yes', 'aqualuxe') . '</option>
            <option value="no" ' . selected($value, 'no', false) . '>' . __('No', 'aqualuxe') . '</option>
        </select>';
        echo '<p class="description">' . __('Convert uploaded JPEG and PNG images to WebP format for better performance.', 'aqualuxe') . '</p>';
    }

    /**
     * WebP quality callback
     */
    public function webp_quality_callback() {
        $value = get_option('aqualuxe_webp_quality', 80);
        echo '<input type="range" min="1" max="100" step="1" name="aqualuxe_webp_quality" value="' . esc_attr($value) . '" class="aqualuxe-range" />';
        echo '<span class="aqualuxe-range-value">' . esc_html($value) . '%</span>';
        echo '<p class="description">' . __('Set the quality of WebP images (higher quality = larger file size).', 'aqualuxe') . '</p>';
        
        // Add JavaScript to update range value
        echo '<script>
            document.addEventListener("DOMContentLoaded", function() {
                const range = document.querySelector(".aqualuxe-range");
                const value = document.querySelector(".aqualuxe-range-value");
                
                range.addEventListener("input", function() {
                    value.textContent = this.value + "%";
                });
            });
        </script>';
    }

    /**
     * Enable compression callback
     */
    public function enable_compression_callback() {
        $value = get_option('aqualuxe_enable_compression', 'yes');
        echo '<select name="aqualuxe_enable_compression">
            <option value="yes" ' . selected($value, 'yes', false) . '>' . __('Yes', 'aqualuxe') . '</option>
            <option value="no" ' . selected($value, 'no', false) . '>' . __('No', 'aqualuxe') . '</option>
        </select>';
        echo '<p class="description">' . __('Compress uploaded images to reduce file size.', 'aqualuxe') . '</p>';
    }

    /**
     * Compression quality callback
     */
    public function compression_quality_callback() {
        $value = get_option('aqualuxe_compression_quality', 85);
        echo '<input type="range" min="1" max="100" step="1" name="aqualuxe_compression_quality" value="' . esc_attr($value) . '" class="aqualuxe-range-compression" />';
        echo '<span class="aqualuxe-range-compression-value">' . esc_html($value) . '%</span>';
        echo '<p class="description">' . __('Set the quality of compressed images (higher quality = larger file size).', 'aqualuxe') . '</p>';
        
        // Add JavaScript to update range value
        echo '<script>
            document.addEventListener("DOMContentLoaded", function() {
                const range = document.querySelector(".aqualuxe-range-compression");
                const value = document.querySelector(".aqualuxe-range-compression-value");
                
                range.addEventListener("input", function() {
                    value.textContent = this.value + "%";
                });
            });
        </script>';
    }

    /**
     * Sanitize quality value
     * 
     * @param mixed $value Quality value
     * @return int Sanitized quality value
     */
    public function sanitize_quality($value) {
        $value = absint($value);
        return max(1, min(100, $value));
    }
}

// Initialize the image optimization class
new AquaLuxe_Image_Optimization();