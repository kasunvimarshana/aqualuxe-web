<section class="featured-services-section container">
  <h2 class="section-title">Our Featured Services</h2>
  <div class="services-grid">
    <?php
    $services = get_posts([
      'post_type' => 'service',
      'posts_per_page' => 4,
      'orderby' => 'menu_order',
      'order' => 'ASC',
    ]);
    foreach ($services as $post) : setup_postdata($post); ?>
      <div class="service-card">
        <?php if (has_post_thumbnail()) : ?>
          <div class="service-image"><?php the_post_thumbnail('medium'); ?></div>
        <?php endif; ?>
        <h3><?php the_title(); ?></h3>
        <p><?php echo wp_trim_words(get_the_excerpt(), 18); ?></p>
        <a href="<?php the_permalink(); ?>" class="btn btn-secondary">Learn More</a>
      </div>
    <?php endforeach; wp_reset_postdata(); ?>
  </div>
</section>
