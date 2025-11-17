<section class="featured-products-section container">
  <h2 class="section-title">Featured Products</h2>
  <div class="products-grid">
    <?php
    $products = wc_get_products([
      'status' => 'publish',
      'limit' => 4,
      'featured' => true,
      'orderby' => 'menu_order',
      'order' => 'ASC',
    ]);
    foreach ($products as $product) : ?>
      <div class="product-card">
        <a href="<?php echo get_permalink($product->get_id()); ?>">
          <?php echo $product->get_image('medium'); ?>
          <h3><?php echo $product->get_name(); ?></h3>
        </a>
        <div class="product-price"><?php echo $product->get_price_html(); ?></div>
        <a href="<?php echo esc_url($product->add_to_cart_url()); ?>" class="btn btn-primary add-to-cart">Add to Cart</a>
      </div>
    <?php endforeach; ?>
  </div>
</section>
