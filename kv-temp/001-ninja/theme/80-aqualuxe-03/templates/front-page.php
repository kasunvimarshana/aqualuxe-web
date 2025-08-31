<?php /* Front Page */ get_header(); ?>
<section class="relative overflow-hidden" aria-label="Hero">
  <canvas id="oceanCanvas" class="absolute inset-0 w-full h-full"></canvas>
  <div class="relative container mx-auto px-4 py-24 md:py-40 text-center">
    <h1 class="text-4xl md:text-6xl font-extrabold tracking-tight">
      <?php echo esc_html(get_bloginfo('name')); ?>
    </h1>
    <p class="mt-4 text-lg opacity-90"><?php echo esc_html__('Bringing elegance to aquatic life – globally.', 'aqualuxe'); ?></p>
    <div class="mt-8 flex items-center justify-center gap-3">
      <a class="btn" href="<?php echo esc_url(site_url('/shop')); ?>"><?php esc_html_e('Shop Now', 'aqualuxe'); ?></a>
      <a class="btn bg-slate-800 hover:bg-slate-900" href="<?php echo esc_url(site_url('/services')); ?>"><?php esc_html_e('Book a Service', 'aqualuxe'); ?></a>
    </div>
  </div>
</section>

<section class="container mx-auto px-4 py-16">
  <h2 class="text-2xl font-semibold mb-6"><?php esc_html_e('Featured Products', 'aqualuxe'); ?></h2>
  <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
    <?php if (function_exists('wc_get_products')): $products = call_user_func('wc_get_products', ['limit'=>8, 'featured'=>true]); foreach ($products as $product): ?>
      <a href="<?php echo esc_url(get_permalink($product->get_id())); ?>" class="block group">
        <?php echo $product->get_image('medium', ['class'=>'w-full h-auto rounded', 'loading'=>'lazy']); ?>
        <h3 class="mt-2 font-medium group-hover:underline"><?php echo esc_html($product->get_name()); ?></h3>
        <p class="text-sm opacity-80"><?php echo wp_kses_post($product->get_price_html()); ?></p>
      </a>
    <?php endforeach; else: ?>
      <p><?php esc_html_e('WooCommerce not active. Add products to showcase here.', 'aqualuxe'); ?></p>
    <?php endif; ?>
  </div>
</section>

<section class="bg-slate-50 dark:bg-slate-900/40">
  <div class="container mx-auto px-4 py-16 grid md:grid-cols-3 gap-8">
    <div>
      <h2 class="text-2xl font-semibold mb-4"><?php esc_html_e('Testimonials', 'aqualuxe'); ?></h2>
      <p class="opacity-80"><?php esc_html_e('“AquaLuxe transformed our lobby with a breathtaking reef aquarium.” – Hotel Aurelia', 'aqualuxe'); ?></p>
    </div>
    <div>
      <h2 class="text-2xl font-semibold mb-4"><?php esc_html_e('Newsletter', 'aqualuxe'); ?></h2>
      <?php if (isset($_GET['nl'])): ?>
        <?php if ($_GET['nl'] === 'ok'): ?>
          <div class="aqlx-notice aqlx-notice--success" role="status"><?php esc_html_e('Thanks! You are on the list.','aqualuxe'); ?></div>
        <?php elseif ($_GET['nl'] === 'err'): ?>
          <div class="aqlx-notice aqlx-notice--error" role="alert"><?php esc_html_e('Please enter a valid email address.','aqualuxe'); ?></div>
        <?php elseif ($_GET['nl'] === 'consent'): ?>
          <div class="aqlx-notice aqlx-notice--error" role="alert"><?php esc_html_e('Please confirm consent to receive emails.','aqualuxe'); ?></div>
        <?php elseif ($_GET['nl'] === 'slow'): ?>
          <div class="aqlx-notice aqlx-notice--error" role="alert"><?php esc_html_e('Please wait a moment before trying again.','aqualuxe'); ?></div>
        <?php endif; ?>
      <?php endif; ?>
      <form id="newsletter-hero" action="" method="post" class="flex flex-col gap-3 max-w-xl">
        <?php aqualuxe_nonce_field('newsletter'); ?>
        <input type="hidden" name="aqlx_newsletter" value="1" />
        <label for="hero_email" class="sr-only"><?php esc_html_e('Email','aqualuxe'); ?></label>
        <div class="flex gap-2">
          <input id="hero_email" name="email" type="email" class="border px-3 py-2 flex-1" placeholder="you@example.com" required>
          <button class="btn whitespace-nowrap"><?php esc_html_e('Subscribe','aqualuxe'); ?></button>
        </div>
        <div class="text-sm leading-snug">
          <?php $pp = function_exists('get_privacy_policy_url') ? get_privacy_policy_url() : home_url('/privacy-policy/'); ?>
          <label class="inline-flex items-start gap-2">
            <input type="checkbox" name="consent" value="1" required>
            <span><?php echo wp_kses(sprintf(__('I agree to receive emails and accept the <a href="%s">Privacy Policy</a>.','aqualuxe'), esc_url($pp)), ['a'=>['href'=>[]]]); ?></span>
          </label>
        </div>
        <!-- Honeypot -->
        <div aria-hidden="true" style="position:absolute;left:-9999px;top:auto;width:1px;height:1px;overflow:hidden">
          <label for="aqlx_company_h">Company</label>
          <input id="aqlx_company_h" name="aqlx_company" type="text" tabindex="-1" autocomplete="off">
        </div>
      </form>
    </div>
    <div>
      <h2 class="text-2xl font-semibold mb-4"><?php esc_html_e('Upcoming Events', 'aqualuxe'); ?></h2>
      <ul class="list-disc pl-5">
        <li><?php esc_html_e('Aquascaping Expo – Oct 12', 'aqualuxe'); ?></li>
        <li><?php esc_html_e('Rare Koi Auction – Nov 3', 'aqualuxe'); ?></li>
      </ul>
    </div>
  </div>
</section>



<?php get_footer(); ?>
