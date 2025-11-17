<?php
?><footer class="site-footer mt-12" role="contentinfo">
  <div class="container mx-auto px-4 py-8 grid grid-cols-1 md:grid-cols-3 gap-6">
    <div>
      <p class="text-sm">&copy; <?php echo esc_html(date('Y')); ?> <?php bloginfo('name'); ?>. <?php esc_html_e('All rights reserved.', 'aqualuxe'); ?></p>
      <p class="text-xs opacity-75"><?php esc_html_e('Bringing elegance to aquatic life – globally.', 'aqualuxe'); ?></p>
    </div>
    <div>
      <?php wp_nav_menu(['theme_location'=>'footer','container'=>false]); ?>
    </div>
    <div>
      <form id="newsletter" action="#" method="post" class="flex gap-2">
        <?php aqualuxe_nonce_field('newsletter'); ?>
        <label for="email" class="sr-only">Email</label>
        <input id="email" name="email" type="email" class="border px-3 py-2 w-full" placeholder="you@example.com" required>
        <button class="btn">Subscribe</button>
      </form>
    </div>
  </div>
</footer>
