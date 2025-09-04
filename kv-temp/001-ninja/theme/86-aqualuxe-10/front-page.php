<?php
get_header();
?>
<main id="primary" class="site-main" role="main">
  <section class="hero relative overflow-hidden">
    <div class="absolute inset-0 pointer-events-none" aria-hidden="true">
      <canvas id="aquatic-canvas" class="w-full h-full block"></canvas>
    </div>
    <div class="container mx-auto px-4 py-20 text-center relative">
      <h1 class="text-4xl md:text-6xl font-extrabold tracking-tight">
        <?php echo esc_html(get_theme_mod('blogname', get_bloginfo('name'))); ?>
      </h1>
      <p class="mt-4 text-lg opacity-80"><?php esc_html_e('Bringing elegance to aquatic life – globally.', 'aqualuxe'); ?></p>
  <div class="mt-8 flex justify-center gap-4">
        <a class="btn btn-primary" href="<?php echo esc_url(home_url('/shop')); ?>"><?php esc_html_e('Shop Now', 'aqualuxe'); ?></a>
        <a class="btn btn-secondary" href="<?php echo esc_url(home_url('/services')); ?>"><?php esc_html_e('Book a Service', 'aqualuxe'); ?></a>
      </div>
    </div>
  </section>

  <section class="featured container mx-auto px-4 py-16" aria-labelledby="featured-heading">
    <h2 id="featured-heading" class="text-2xl font-semibold mb-6"><?php esc_html_e('Featured', 'aqualuxe'); ?></h2>
    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
	<?php
  if ( function_exists( 'wc_get_products' ) ) {
    $products = wc_get_products( array( 'status' => 'publish', 'limit' => 6 ) );
    foreach ( $products as $product ) {
      $product_link = get_permalink( $product->get_id() );
      echo '<div class="card border border-slate-200 dark:border-slate-800 rounded-lg overflow-hidden flex flex-col">';
      echo '<a class="block" href="' . esc_url( $product_link ) . '">';
      echo wp_kses_post( $product->get_image( 'medium' ) );
      echo '</a>';
      echo '<div class="p-4 flex-1 flex flex-col gap-3">';
      echo '<div class="font-semibold">' . esc_html( $product->get_name() ) . '</div>';
      echo '<div class="opacity-80">' . wp_kses_post( $product->get_price_html() ) . '</div>';
      // Actions: View and Quick View (link fallback)
      $pid = $product->get_id();
      echo '<div class="mt-auto flex items-center gap-3">';
      echo '<a class="btn btn-secondary" href="' . esc_url( $product_link ) . '">' . esc_html__( 'View', 'aqualuxe' ) . '</a>';
      echo '<a class="btn btn-primary" href="' . esc_url( $product_link ) . '" data-qv-id="' . esc_attr( (string) $pid ) . '" aria-haspopup="dialog" aria-controls="qv-modal">' . esc_html__( 'Quick View', 'aqualuxe' ) . '</a>';
      echo '</div>';
      echo '</div>';
      echo '</div>';
    }
  } else {
    $q = new WP_Query( array( 'posts_per_page' => 6 ) );
    if ( $q->have_posts() ) {
      while ( $q->have_posts() ) {
        $q->the_post();
        $thumb = get_the_post_thumbnail( get_the_ID(), 'medium', array( 'class' => 'w-full h-auto' ) );
        echo '<article class="card border border-slate-200 dark:border-slate-800 rounded-lg overflow-hidden">';
        if ( $thumb ) { echo '<a class="block" href="' . esc_url( get_permalink() ) . '">' . wp_kses_post( $thumb ) . '</a>'; }
        echo '<div class="p-4">';
        echo '<h3 class="font-semibold text-lg"><a href="' . esc_url( get_permalink() ) . '">' . esc_html( get_the_title() ) . '</a></h3>';
        echo '<div class="prose opacity-80">' . wp_kses_post( wp_trim_words( get_the_excerpt(), 20 ) ) . '</div>';
        echo '</div>';
        echo '</article>';
      }
      wp_reset_postdata();
    }
  }
	?>
    </div>
  </section>

  <section class="newsletter bg-slate-50 dark:bg-slate-900 py-16" aria-labelledby="newsletter-heading">
    <div class="container mx-auto px-4">
      <h2 id="newsletter-heading" class="text-2xl font-semibold mb-2"><?php esc_html_e('Stay in the current', 'aqualuxe'); ?></h2>
      <p class="opacity-80 mb-6"><?php esc_html_e('Subscribe to our newsletter for rare arrivals and care tips.', 'aqualuxe'); ?></p>
      <?php echo function_exists('do_shortcode') ? do_shortcode('[aqualuxe_subscribe_form]') : ''; ?>
    </div>
    </section>
</main>
<?php
get_footer();
