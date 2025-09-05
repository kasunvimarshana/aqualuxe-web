<?php
// Runtime guard: render a lightweight static preview if WordPress isn't loaded
if ( ! function_exists('get_header') ) : ?>
  <main id="primary" class="site-main" role="main">
    <section class="hero relative overflow-hidden">
      <div class="absolute inset-0 pointer-events-none" aria-hidden="true">
        <canvas id="aquatic-canvas" class="w-full h-full block"></canvas>
      </div>
      <div class="container mx-auto px-4 py-20 text-center relative">
        <h1 class="text-4xl md:text-6xl font-extrabold tracking-tight">AquaLuxe</h1>
        <p class="mt-4 text-lg opacity-80">Bringing elegance to aquatic life – globally.</p>
        <div class="mt-8 flex justify-center gap-4">
          <a class="btn btn-primary" href="#">Shop Now</a>
          <a class="btn btn-secondary" href="#">Book a Service</a>
        </div>
      </div>
    </section>
    <section class="stats container mx-auto px-4 py-12" aria-labelledby="stats-heading">
      <h2 id="stats-heading" class="sr-only">Key metrics</h2>
      <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
        <div class="card border border-slate-200 dark:border-slate-800 rounded-lg p-4 flex items-center gap-4">
          <div class="text-2xl font-bold">1,280</div>
          <div class="flex-1">
            <div class="text-sm opacity-80 mb-1">Live Species</div>
            <div data-sparkline data-values="3,5,4,6,7,6,8,9,8,10" class="text-sky-500 dark:text-sky-300"></div>
          </div>
        </div>
        <div class="card border border-slate-200 dark:border-slate-800 rounded-lg p-4 flex items-center gap-4">
          <div class="text-2xl font-bold">42</div>
          <div class="flex-1">
            <div class="text-sm opacity-80 mb-1">Countries Served</div>
            <div data-sparkline data-values="1,2,2,3,3,4,4,5,5,6" class="text-sky-500 dark:text-sky-300"></div>
          </div>
        </div>
        <div class="card border border-slate-200 dark:border-slate-800 rounded-lg p-4 flex items-center gap-4">
          <div class="text-2xl font-bold">3.2</div>
          <div class="flex-1">
            <div class="text-sm opacity-80 mb-1">Avg. Delivery (days)</div>
            <div data-sparkline data-values="5,4,4,3,3,3,2,3,2,3" class="text-sky-500 dark:text-sky-300"></div>
          </div>
        </div>
        <div class="card border border-slate-200 dark:border-slate-800 rounded-lg p-4 flex items-center gap-4">
          <div class="text-2xl font-bold">98%</div>
          <div class="flex-1">
            <div class="text-sm opacity-80 mb-1">Customer Satisfaction</div>
            <div data-sparkline data-values="90,92,93,95,96,97,97,98,98,98" class="text-sky-500 dark:text-sky-300"></div>
          </div>
        </div>
      </div>
    </section>
  </main>
<?php else: ?>
<?php if (function_exists('get_header')) { call_user_func('get_header'); } ?>
<main id="primary" class="site-main" role="main">
  <section class="hero relative overflow-hidden">
    <div class="absolute inset-0 pointer-events-none" aria-hidden="true">
      <canvas id="aquatic-canvas" class="w-full h-full block"></canvas>
    </div>
    <div class="container mx-auto px-4 py-20 text-center relative">
      <h1 class="text-4xl md:text-6xl font-extrabold tracking-tight">
        <?php
          $name = function_exists('get_bloginfo') ? call_user_func('get_bloginfo','name') : 'AquaLuxe';
          $val = function_exists('get_theme_mod') ? call_user_func('get_theme_mod','blogname', $name) : $name;
          echo function_exists('esc_html') ? call_user_func('esc_html', $val) : $val;
        ?>
      </h1>
  <p class="mt-4 text-lg opacity-80"><?php echo function_exists('esc_html__') ? call_user_func('esc_html__','Bringing elegance to aquatic life – globally.', 'aqualuxe') : 'Bringing elegance to aquatic life – globally.'; ?></p>
  <div class="mt-8 flex justify-center gap-4">
        <?php
          $shop = function_exists('home_url') ? call_user_func('home_url','/shop') : '#';
          $services = function_exists('home_url') ? call_user_func('home_url','/services') : '#';
          $shop = function_exists('esc_url') ? call_user_func('esc_url', $shop) : $shop;
          $services = function_exists('esc_url') ? call_user_func('esc_url', $services) : $services;
          $shopTxt = function_exists('esc_html__') ? call_user_func('esc_html__','Shop Now','aqualuxe') : 'Shop Now';
          $svcTxt = function_exists('esc_html__') ? call_user_func('esc_html__','Book a Service','aqualuxe') : 'Book a Service';
        ?>
        <a class="btn btn-primary" href="<?php echo $shop; ?>"><?php echo $shopTxt; ?></a>
        <a class="btn btn-secondary" href="<?php echo $services; ?>"><?php echo $svcTxt; ?></a>
      </div>
    </div>
  <?php if ( function_exists('get_template_part') ) { call_user_func('get_template_part','templates/parts/svg-wave'); } ?>
  </section>

  <section class="stats container mx-auto px-4 py-12" aria-labelledby="stats-heading">
  <h2 id="stats-heading" class="sr-only"><?php echo function_exists('esc_html__') ? call_user_func('esc_html__','Key metrics','aqualuxe') : 'Key metrics'; ?></h2>
    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
      <?php
      $metrics = [
        [ 'label' => (function_exists('__') ? call_user_func('__','Live Species','aqualuxe') : 'Live Species'), 'value' => '1,280', 'series' => '3,5,4,6,7,6,8,9,8,10' ],
        [ 'label' => (function_exists('__') ? call_user_func('__','Countries Served','aqualuxe') : 'Countries Served'), 'value' => '42', 'series' => '1,2,2,3,3,4,4,5,5,6' ],
        [ 'label' => (function_exists('__') ? call_user_func('__','Avg. Delivery (days)','aqualuxe') : 'Avg. Delivery (days)'), 'value' => '3.2', 'series' => '5,4,4,3,3,3,2,3,2,3' ],
        [ 'label' => (function_exists('__') ? call_user_func('__','Customer Satisfaction','aqualuxe') : 'Customer Satisfaction'), 'value' => '98%', 'series' => '90,92,93,95,96,97,97,98,98,98' ],
      ];
      foreach ( $metrics as $i => $m ) {
        $id = 'metric-'.($i+1);
        echo '<div class="card border border-slate-200 dark:border-slate-800 rounded-lg p-4 flex items-center gap-4">';
  $safeId = function_exists('esc_attr') ? call_user_func('esc_attr', $id) : $id;
  $safeLabel = function_exists('esc_html') ? call_user_func('esc_html', $m['label']) : $m['label'];
  $safeVal = function_exists('esc_html') ? call_user_func('esc_html', $m['value']) : $m['value'];
  $safeSeries = function_exists('esc_attr') ? call_user_func('esc_attr', $m['series']) : $m['series'];
  echo '<div class="text-2xl font-bold" aria-describedby="'.$safeId.'">'.$safeVal.'</div>';
  echo '<div id="'.$safeId.'" class="flex-1">';
  echo '<div class="text-sm opacity-80 mb-1">'.$safeLabel.'</div>';
  echo '<div data-sparkline data-values="'.$safeSeries.'" class="text-sky-500 dark:text-sky-300"></div>';
        echo '</div>';
        echo '</div>';
      }
      ?>
    </div>
  </section>

  <section class="featured container mx-auto px-4 py-16" aria-labelledby="featured-heading">
    <h2 id="featured-heading" class="text-2xl font-semibold mb-6"><?php echo function_exists('esc_html__') ? call_user_func('esc_html__','Featured','aqualuxe') : 'Featured'; ?></h2>
    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
	<?php
  if ( function_exists( 'wc_get_products' ) ) {
    $products = call_user_func( 'wc_get_products', array( 'status' => 'publish', 'limit' => 6 ) );
    foreach ( $products as $product ) {
      $product_link = function_exists('get_permalink') ? call_user_func('get_permalink', $product->get_id() ) : '#';
      $product_link_safe = function_exists('esc_url') ? call_user_func('esc_url', $product_link) : $product_link;
      echo '<div class="card border border-slate-200 dark:border-slate-800 rounded-lg overflow-hidden flex flex-col">';
      echo '<a class="block" href="' . $product_link_safe . '">';
      $img = method_exists($product, 'get_image') ? $product->get_image( 'medium' ) : '';
      echo function_exists('wp_kses_post') ? call_user_func('wp_kses_post', $img) : $img;
      echo '</a>';
      echo '<div class="p-4 flex-1 flex flex-col gap-3">';
      $name = method_exists($product, 'get_name') ? $product->get_name() : '';
      echo '<div class="font-semibold">' . ( function_exists('esc_html') ? call_user_func('esc_html', $name) : $name ) . '</div>';
      $price_html = method_exists($product, 'get_price_html') ? $product->get_price_html() : '';
      echo '<div class="opacity-80">' . ( function_exists('wp_kses_post') ? call_user_func('wp_kses_post', $price_html) : $price_html ) . '</div>';
      // Actions: View and Quick View (link fallback)
      $pid = method_exists($product, 'get_id') ? $product->get_id() : 0;
      $viewTxt = function_exists('esc_html__') ? call_user_func('esc_html__','View','aqualuxe') : 'View';
      $qvTxt = function_exists('esc_html__') ? call_user_func('esc_html__','Quick View','aqualuxe') : 'Quick View';
      $pid_safe = function_exists('esc_attr') ? call_user_func('esc_attr', (string) $pid) : (string) $pid;
      echo '<div class="mt-auto flex items-center gap-3">';
      echo '<a class="btn btn-secondary" href="' . $product_link_safe . '">' . $viewTxt . '</a>';
      echo '<a class="btn btn-primary" href="' . $product_link_safe . '" data-qv-id="' . $pid_safe . '" aria-haspopup="dialog" aria-controls="qv-modal">' . $qvTxt . '</a>';
      echo '</div>';
      echo '</div>';
      echo '</div>';
    }
  } else if ( function_exists('get_posts') ) {
    $posts = call_user_func('get_posts', array('numberposts' => 6) );
    foreach ( (array)$posts as $p ) {
      echo '<article class="card border border-slate-200 dark:border-slate-800 rounded-lg overflow-hidden">';
      $perma = function_exists('get_permalink') ? call_user_func('get_permalink', $p) : '#';
      $perma = function_exists('esc_url') ? call_user_func('esc_url', $perma) : $perma;
      $thumb = function_exists('get_the_post_thumbnail') ? call_user_func('get_the_post_thumbnail', $p, 'medium', array('class'=>'w-full h-auto')) : '';
      if ( $thumb ) {
        echo '<a class="block" href="' . $perma . '">' . ( function_exists('wp_kses_post') ? call_user_func('wp_kses_post', $thumb) : $thumb ) . '</a>';
      }
      echo '<div class="p-4">';
      $title = function_exists('get_the_title') ? call_user_func('get_the_title', $p) : 'Untitled';
      $title = function_exists('esc_html') ? call_user_func('esc_html', $title) : $title;
      echo '<h3 class="font-semibold text-lg"><a href="' . $perma . '">' . $title . '</a></h3>';
      $excerpt = function_exists('has_excerpt') && call_user_func('has_excerpt', $p) ? call_user_func('get_the_excerpt', $p) : '';
      if ( ! $excerpt && function_exists('wp_trim_words') && function_exists('wp_strip_all_tags') && function_exists('get_post_field') ) {
        $excerpt = call_user_func('wp_trim_words', call_user_func('wp_strip_all_tags', call_user_func('get_post_field','post_content', $p )), 20 );
      }
      echo '<div class="prose opacity-80">' . ( function_exists('wp_kses_post') ? call_user_func('wp_kses_post', $excerpt) : $excerpt ) . '</div>';
      echo '</div>';
      echo '</article>';
    }
  } else {
    // Static placeholders when WordPress is not available
    for ($i=0; $i<3; $i++) {
      echo '<article class="card border border-slate-200 dark:border-slate-800 rounded-lg overflow-hidden">';
      echo '<div class="p-4">';
      echo '<h3 class="font-semibold text-lg"><a href="#">Sample item</a></h3>';
      echo '<div class="prose opacity-80">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</div>';
      echo '</div>';
      echo '</article>';
    }
  }
	?>
    </div>
  </section>

  <section class="testimonials bg-slate-50 dark:bg-slate-900/40 py-16" aria-labelledby="testimonials-heading">
    <div class="container mx-auto px-4">
  <h2 id="testimonials-heading" class="text-2xl font-semibold mb-6"><?php echo function_exists('esc_html__') ? call_user_func('esc_html__','What our clients say','aqualuxe') : 'What our clients say'; ?></h2>
  <?php echo function_exists('do_shortcode') ? call_user_func('do_shortcode','[aqualuxe_testimonials count="6"]') : ''; ?>
    </div>
  </section>

  <section class="newsletter bg-slate-50 dark:bg-slate-900 py-16" aria-labelledby="newsletter-heading">
    <div class="container mx-auto px-4">
  <h2 id="newsletter-heading" class="text-2xl font-semibold mb-2"><?php echo function_exists('esc_html__') ? call_user_func('esc_html__','Stay in the current','aqualuxe') : 'Stay in the current'; ?></h2>
  <p class="opacity-80 mb-6"><?php echo function_exists('esc_html__') ? call_user_func('esc_html__','Subscribe to our newsletter for rare arrivals and care tips.','aqualuxe') : 'Subscribe to our newsletter for rare arrivals and care tips.'; ?></p>
  <?php echo function_exists('do_shortcode') ? call_user_func('do_shortcode','[aqualuxe_subscribe_form]') : ''; ?>
    </div>
    </section>
</main>
<?php
if (function_exists('get_footer')) { call_user_func('get_footer'); }
endif; // runtime guard
