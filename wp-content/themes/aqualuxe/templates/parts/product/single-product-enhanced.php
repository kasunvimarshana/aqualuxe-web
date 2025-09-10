<?php
/**
 * Advanced Single Product Enhancement
 *
 * @package AquaLuxe
 */

global $product;

if (!$product) {
    return;
}

$product_id = $product->get_id();
$gallery_image_ids = $product->get_gallery_image_ids();
$has_gallery = !empty($gallery_image_ids);
?>

<div class="single-product-enhanced">
    <!-- Breadcrumb -->
    <div class="breadcrumb-container mb-6">
        <?php woocommerce_breadcrumb(); ?>
    </div>

    <div class="product-main grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
        <!-- Product Images -->
        <div class="product-images">
            <div class="main-image-container relative mb-4">
                <div class="main-image-wrapper relative overflow-hidden rounded-lg bg-gray-100" style="aspect-ratio: 1/1;">
                    <?php
                    $image_id = $product->get_image_id();
                    if ($image_id) {
                        echo wp_get_attachment_image($image_id, 'woocommerce_single', false, [
                            'class' => 'main-product-image w-full h-full object-cover cursor-zoom-in',
                            'data-zoom' => wp_get_attachment_image_url($image_id, 'full')
                        ]);
                    } else {
                        echo '<div class="w-full h-full flex items-center justify-center text-gray-400">';
                        echo '<span>No image available</span>';
                        echo '</div>';
                    }
                    ?>

                    <!-- Image Badges -->
                    <div class="absolute top-4 left-4 space-y-2">
                        <?php if ($product->is_on_sale()): ?>
                            <span class="sale-badge bg-red-500 text-white px-3 py-1 rounded-full text-sm font-medium">
                                Sale
                            </span>
                        <?php endif; ?>

                        <?php if (!$product->is_in_stock()): ?>
                            <span class="stock-badge bg-gray-500 text-white px-3 py-1 rounded-full text-sm font-medium">
                                Out of Stock
                            </span>
                        <?php elseif ($product->get_stock_quantity() && $product->get_stock_quantity() <= 5): ?>
                            <span class="low-stock-badge bg-orange-500 text-white px-3 py-1 rounded-full text-sm font-medium">
                                Only <?php echo $product->get_stock_quantity(); ?> left
                            </span>
                        <?php endif; ?>

                        <?php if ($product->is_featured()): ?>
                            <span class="featured-badge bg-yellow-500 text-white px-3 py-1 rounded-full text-sm font-medium">
                                Featured
                            </span>
                        <?php endif; ?>
                    </div>

                    <!-- Zoom Controls -->
                    <div class="absolute top-4 right-4">
                        <button class="zoom-btn bg-white bg-opacity-80 hover:bg-opacity-100 p-2 rounded-full shadow-md transition-all duration-200" data-action="zoom">
                            <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Navigation arrows for gallery -->
                <?php if ($has_gallery): ?>
                    <button class="gallery-nav prev-btn absolute left-2 top-1/2 transform -translate-y-1/2 bg-white bg-opacity-80 hover:bg-opacity-100 p-2 rounded-full shadow-md transition-all duration-200">
                        <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>
                    <button class="gallery-nav next-btn absolute right-2 top-1/2 transform -translate-y-1/2 bg-white bg-opacity-80 hover:bg-opacity-100 p-2 rounded-full shadow-md transition-all duration-200">
                        <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                <?php endif; ?>
            </div>

            <!-- Thumbnail Gallery -->
            <?php if ($has_gallery): ?>
                <div class="thumbnail-gallery">
                    <div class="thumbnails-container flex space-x-2 overflow-x-auto pb-2">
                        <!-- Main image thumbnail -->
                        <?php if ($image_id): ?>
                            <div class="thumbnail-item active flex-shrink-0 cursor-pointer" data-image="<?php echo wp_get_attachment_image_url($image_id, 'full'); ?>">
                                <?php echo wp_get_attachment_image($image_id, 'woocommerce_gallery_thumbnail', false, [
                                    'class' => 'w-20 h-20 object-cover rounded-lg border-2 border-cyan-500'
                                ]); ?>
                            </div>
                        <?php endif; ?>

                        <!-- Gallery thumbnails -->
                        <?php foreach ($gallery_image_ids as $gallery_image_id): ?>
                            <div class="thumbnail-item flex-shrink-0 cursor-pointer" data-image="<?php echo wp_get_attachment_image_url($gallery_image_id, 'full'); ?>">
                                <?php echo wp_get_attachment_image($gallery_image_id, 'woocommerce_gallery_thumbnail', false, [
                                    'class' => 'w-20 h-20 object-cover rounded-lg border-2 border-transparent hover:border-cyan-300 transition-colors duration-200'
                                ]); ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Product Information -->
        <div class="product-info">
            <!-- Product Title and Rating -->
            <div class="product-header mb-6">
                <h1 class="product-title text-3xl font-bold text-gray-900 mb-3">
                    <?php echo $product->get_name(); ?>
                </h1>

                <!-- Product Rating -->
                <div class="product-rating flex items-center mb-4">
                    <?php if ($product->get_average_rating()): ?>
                        <div class="stars flex items-center mr-3">
                            <?php
                            $rating = $product->get_average_rating();
                            for ($i = 1; $i <= 5; $i++):
                                if ($i <= $rating): ?>
                                    <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                <?php elseif ($i - $rating < 1): ?>
                                    <svg class="w-5 h-5 text-yellow-400" viewBox="0 0 20 20">
                                        <defs>
                                            <linearGradient id="half-star">
                                                <stop offset="50%" stop-color="#fbbf24" />
                                                <stop offset="50%" stop-color="#e5e7eb" />
                                            </linearGradient>
                                        </defs>
                                        <path fill="url(#half-star)" d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                <?php else: ?>
                                    <svg class="w-5 h-5 text-gray-300 fill-current" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                <?php endif;
                            endfor; ?>
                        </div>
                        <span class="text-sm text-gray-600">
                            <?php echo number_format($rating, 1); ?> (<?php echo $product->get_review_count(); ?> reviews)
                        </span>
                    <?php else: ?>
                        <span class="text-sm text-gray-500">No reviews yet</span>
                    <?php endif; ?>
                </div>

                <!-- Product Price -->
                <div class="product-price mb-6">
                    <div class="price-container flex items-center space-x-3">
                        <?php echo $product->get_price_html(); ?>
                        <?php if ($product->is_on_sale()): ?>
                            <span class="savings bg-green-100 text-green-800 px-2 py-1 rounded-full text-sm font-medium">
                                Save <?php echo wc_format_decimal($product->get_regular_price() - $product->get_sale_price(), 2); ?>
                            </span>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Stock Status -->
                <div class="stock-info mb-6">
                    <?php if ($product->is_in_stock()): ?>
                        <div class="flex items-center text-green-600">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span class="font-medium">In Stock</span>
                            <?php if ($product->get_stock_quantity()): ?>
                                <span class="ml-2 text-sm text-gray-600">
                                    (<?php echo $product->get_stock_quantity(); ?> available)
                                </span>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <div class="flex items-center text-red-600">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            <span class="font-medium">Out of Stock</span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Short Description -->
            <?php if ($product->get_short_description()): ?>
                <div class="product-short-description mb-8">
                    <div class="prose prose-sm max-w-none text-gray-700">
                        <?php echo $product->get_short_description(); ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Add to Cart Form -->
            <div class="add-to-cart-section mb-8">
                <?php woocommerce_template_single_add_to_cart(); ?>
            </div>

            <!-- Product Meta Information -->
            <div class="product-meta border-t border-gray-200 pt-6">
                <dl class="grid grid-cols-1 gap-4 text-sm">
                    <!-- SKU -->
                    <?php if ($product->get_sku()): ?>
                        <div class="flex justify-between">
                            <dt class="font-medium text-gray-900">SKU:</dt>
                            <dd class="text-gray-700"><?php echo $product->get_sku(); ?></dd>
                        </div>
                    <?php endif; ?>

                    <!-- Categories -->
                    <?php
                    $categories = get_the_terms($product_id, 'product_cat');
                    if ($categories && !is_wp_error($categories)): ?>
                        <div class="flex justify-between">
                            <dt class="font-medium text-gray-900">Categories:</dt>
                            <dd class="text-gray-700">
                                <?php
                                $category_links = array_map(function($cat) {
                                    return '<a href="' . get_term_link($cat) . '" class="text-cyan-600 hover:text-cyan-800">' . $cat->name . '</a>';
                                }, $categories);
                                echo implode(', ', $category_links);
                                ?>
                            </dd>
                        </div>
                    <?php endif; ?>

                    <!-- Tags -->
                    <?php
                    $tags = get_the_terms($product_id, 'product_tag');
                    if ($tags && !is_wp_error($tags)): ?>
                        <div class="flex justify-between">
                            <dt class="font-medium text-gray-900">Tags:</dt>
                            <dd class="text-gray-700">
                                <?php
                                $tag_links = array_map(function($tag) {
                                    return '<a href="' . get_term_link($tag) . '" class="text-cyan-600 hover:text-cyan-800">' . $tag->name . '</a>';
                                }, $tags);
                                echo implode(', ', $tag_links);
                                ?>
                            </dd>
                        </div>
                    <?php endif; ?>

                    <!-- Weight -->
                    <?php if ($product->has_weight()): ?>
                        <div class="flex justify-between">
                            <dt class="font-medium text-gray-900">Weight:</dt>
                            <dd class="text-gray-700"><?php echo $product->get_weight() . ' ' . get_option('woocommerce_weight_unit'); ?></dd>
                        </div>
                    <?php endif; ?>

                    <!-- Dimensions -->
                    <?php if ($product->has_dimensions()): ?>
                        <div class="flex justify-between">
                            <dt class="font-medium text-gray-900">Dimensions:</dt>
                            <dd class="text-gray-700"><?php echo $product->get_dimensions(); ?></dd>
                        </div>
                    <?php endif; ?>
                </dl>
            </div>

            <!-- Social Sharing -->
            <div class="social-sharing border-t border-gray-200 pt-6 mt-6">
                <h4 class="font-medium text-gray-900 mb-3">Share this product:</h4>
                <div class="flex space-x-3">
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_permalink()); ?>"
                       target="_blank"
                       class="social-btn facebook bg-blue-600 hover:bg-blue-700 text-white p-3 rounded-lg transition-colors duration-200">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M20 10c0-5.523-4.477-10-10-10S0 4.477 0 10c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V10h2.54V7.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V10h2.773l-.443 2.89h-2.33v6.988C16.343 19.128 20 14.991 20 10z" clip-rule="evenodd" />
                        </svg>
                    </a>
                    <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode(get_permalink()); ?>&text=<?php echo urlencode(get_the_title()); ?>"
                       target="_blank"
                       class="social-btn twitter bg-blue-400 hover:bg-blue-500 text-white p-3 rounded-lg transition-colors duration-200">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M6.29 18.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0020 3.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.073 4.073 0 01.8 7.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 010 16.407a11.616 11.616 0 006.29 1.84" />
                        </svg>
                    </a>
                    <a href="https://www.pinterest.com/pin/create/button/?url=<?php echo urlencode(get_permalink()); ?>&description=<?php echo urlencode(get_the_title()); ?>"
                       target="_blank"
                       class="social-btn pinterest bg-red-600 hover:bg-red-700 text-white p-3 rounded-lg transition-colors duration-200">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 0C4.477 0 0 4.477 0 10c0 4.237 2.636 7.855 6.356 9.312-.088-.791-.167-2.005.035-2.868.181-.78 1.172-4.97 1.172-4.97s-.299-.6-.299-1.486c0-1.39.806-2.428 1.81-2.428.852 0 1.264.64 1.264 1.408 0 .858-.546 2.14-.828 3.33-.236.995.5 1.807 1.48 1.807 1.778 0 3.144-1.874 3.144-4.58 0-2.393-1.72-4.068-4.176-4.068-2.845 0-4.516 2.135-4.516 4.34 0 .859.331 1.781.745 2.281a.3.3 0 01.069.288l-.278 1.133c-.044.183-.145.223-.334.134-1.249-.581-2.03-2.407-2.03-3.874 0-3.154 2.292-6.052 6.608-6.052 3.469 0 6.165 2.473 6.165 5.776 0 3.447-2.173 6.22-5.19 6.22-1.013 0-1.97-.527-2.297-1.155l-.624 2.378c-.226.869-.835 1.958-1.244 2.621.937.29 1.931.446 2.962.446 5.523 0 10-4.477 10-10S15.523 0 10 0z" />
                        </svg>
                    </a>
                    <button class="social-btn copy-link bg-gray-600 hover:bg-gray-700 text-white p-3 rounded-lg transition-colors duration-200" onclick="copyProductLink()">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Image Zoom Modal -->
<div class="zoom-modal fixed inset-0 bg-black bg-opacity-90 z-50 flex items-center justify-center overlay-hidden" id="zoom-modal">
    <div class="relative max-w-screen-lg max-h-screen-lg">
        <img src="" alt="" class="max-w-full max-h-full object-contain" id="zoom-image">
        <button class="absolute top-4 right-4 text-white hover:text-gray-300 transition-colors" onclick="closeZoomModal()">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
</div>

<style>
.product-price .price {
    font-size: 1.5rem;
    font-weight: 700;
    color: #0891b2;
}

.product-price .price del {
    color: #9ca3af;
    text-decoration: line-through;
    margin-right: 0.5rem;
}

.thumbnail-item.active img {
    border-color: #0891b2 !important;
}

.social-btn {
    transition: all 0.2s ease;
}

.social-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.gallery-nav {
    opacity: 0;
    transition: opacity 0.2s ease;
}

.product-images:hover .gallery-nav {
    opacity: 1;
}

.main-image-wrapper:hover .zoom-btn {
    opacity: 1;
}

.zoom-btn {
    opacity: 0;
    transition: opacity 0.2s ease;
}

@media (max-width: 1024px) {
    .gallery-nav,
    .zoom-btn {
        opacity: 1;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Image gallery functionality
    const thumbnails = document.querySelectorAll('.thumbnail-item');
    const mainImage = document.querySelector('.main-product-image');

    thumbnails.forEach((thumbnail, index) => {
        thumbnail.addEventListener('click', function() {
            const imageUrl = this.dataset.image;

            // Update main image
            mainImage.src = imageUrl;
            mainImage.dataset.zoom = imageUrl;

            // Update active thumbnail
            thumbnails.forEach(t => t.classList.remove('active'));
            this.classList.add('active');
        });
    });

    // Gallery navigation
    const prevBtn = document.querySelector('.prev-btn');
    const nextBtn = document.querySelector('.next-btn');
    let currentIndex = 0;

    if (prevBtn && nextBtn) {
        prevBtn.addEventListener('click', () => navigateGallery(-1));
        nextBtn.addEventListener('click', () => navigateGallery(1));
    }

    function navigateGallery(direction) {
        const totalImages = thumbnails.length;
        currentIndex = (currentIndex + direction + totalImages) % totalImages;
        thumbnails[currentIndex].click();
    }

    // Image zoom functionality
    const zoomBtn = document.querySelector('.zoom-btn');
    const zoomModal = document.getElementById('zoom-modal');
    const zoomImage = document.getElementById('zoom-image');

    if (zoomBtn && zoomModal && zoomImage) {
        zoomBtn.addEventListener('click', function() {
            const imageUrl = mainImage.dataset.zoom || mainImage.src;
            zoomImage.src = imageUrl;
            zoomModal.classList.remove('overlay-hidden');
        });

        // Close zoom modal
        zoomModal.addEventListener('click', function(e) {
            if (e.target === zoomModal) {
                closeZoomModal();
            }
        });
    }

    // Keyboard navigation
    document.addEventListener('keydown', function(e) {
        if (zoomModal && !zoomModal.classList.contains('overlay-hidden')) {
            if (e.key === 'Escape') {
                closeZoomModal();
            } else if (e.key === 'ArrowLeft') {
                navigateGallery(-1);
            } else if (e.key === 'ArrowRight') {
                navigateGallery(1);
            }
        }
    });
});

// Global functions
function closeZoomModal() {
    const zoomModal = document.getElementById('zoom-modal');
    if (zoomModal) {
        zoomModal.classList.add('overlay-hidden');
    }
}

function copyProductLink() {
    navigator.clipboard.writeText(window.location.href).then(function() {
        // Show success message
        const button = event.target.closest('.copy-link');
        const originalContent = button.innerHTML;

        button.innerHTML = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>';
        button.classList.add('bg-green-600');

        setTimeout(() => {
            button.innerHTML = originalContent;
            button.classList.remove('bg-green-600');
            button.classList.add('bg-gray-600');
        }, 2000);
    }).catch(function(err) {
        console.error('Could not copy text: ', err);
    });
}
</script>
