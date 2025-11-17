<section class="latest-events-section container">
  <h2 class="section-title">Latest Events</h2>
  <div class="events-grid">
    <?php
    $events = get_posts([
      'post_type' => 'event',
      'posts_per_page' => 3,
      'orderby' => 'date',
      'order' => 'DESC',
    ]);
    foreach ($events as $post) : setup_postdata($post); ?>
      <div class="event-card">
        <h3><?php the_title(); ?></h3>
        <div class="event-meta">
          <span><?php echo get_post_meta(get_the_ID(), 'event_date', true); ?></span>
          <span><?php echo get_post_meta(get_the_ID(), 'venue', true); ?></span>
        </div>
        <p><?php echo wp_trim_words(get_the_excerpt(), 20); ?></p>
        <a href="<?php the_permalink(); ?>" class="btn btn-secondary">Details</a>
      </div>
    <?php endforeach; wp_reset_postdata(); ?>
  </div>
</section>
