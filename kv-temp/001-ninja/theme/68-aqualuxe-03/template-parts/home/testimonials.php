<section class="testimonials-section container">
  <h2 class="section-title">What Our Clients Say</h2>
  <div class="testimonials-carousel">
    <?php
    $testimonials = get_posts([
      'post_type' => 'testimonial',
      'posts_per_page' => 6,
      'orderby' => 'date',
      'order' => 'DESC',
    ]);
    foreach ($testimonials as $post) : setup_postdata($post); ?>
      <div class="testimonial-card">
        <blockquote><?php the_content(); ?></blockquote>
        <div class="testimonial-meta">
          <span class="testimonial-author"><?php the_title(); ?></span>
          <?php if ($rating = get_post_meta(get_the_ID(), 'rating', true)) : ?>
            <span class="testimonial-rating">
              <?php for ($i = 0; $i < $rating; $i++) echo '★'; ?>
            </span>
          <?php endif; ?>
        </div>
      </div>
    <?php endforeach; wp_reset_postdata(); ?>
  </div>
</section>
