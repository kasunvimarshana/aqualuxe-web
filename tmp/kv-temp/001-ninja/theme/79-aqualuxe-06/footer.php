<?php
/** Footer */
?>
</main>
<footer class="mt-10 border-t border-slate-200/60 dark:border-slate-700/60" itemscope itemtype="https://schema.org/Organization">
  <div class="container max-w-7xl mx-auto px-4 py-8 grid md:grid-cols-4 gap-8">
    <div>
      <h4 class="font-semibold mb-3"><?php esc_html_e('About AquaLuxe', 'aqualuxe'); ?></h4>
      <p class="text-sm opacity-80"><?php echo esc_html__('Bringing elegance to aquatic life – globally.', 'aqualuxe'); ?></p>
    </div>
    <div>
      <h4 class="font-semibold mb-3"><?php esc_html_e('Quick Links', 'aqualuxe'); ?></h4>
      <?php wp_nav_menu(['theme_location' => 'footer']); ?>
      <?php if (has_nav_menu('account')): ?>
        <div class="mt-3">
          <h5 class="font-medium mb-2"><?php esc_html_e('Account', 'aqualuxe'); ?></h5>
          <?php wp_nav_menu(['theme_location'=>'account','container'=>false,'menu_class'=>'space-y-1 text-sm','depth'=>1]); ?>
        </div>
      <?php elseif (class_exists('WooCommerce')): ?>
        <?php $acct = (int) get_option('woocommerce_myaccount_page_id'); if ($acct): ?>
          <div class="mt-3 text-sm"><a href="<?php echo esc_url(get_permalink($acct)); ?>"><?php esc_html_e('My Account','aqualuxe'); ?></a></div>
        <?php endif; ?>
      <?php endif; ?>
    </div>
    <div>
      <h4 class="font-semibold mb-3"><?php esc_html_e('Newsletter', 'aqualuxe'); ?></h4>
      <form method="post" action="#" class="flex flex-wrap md:flex-nowrap items-center gap-3">
        <label class="sr-only" for="nl_email"><?php esc_html_e('Email', 'aqualuxe'); ?></label>
        <input id="nl_email" type="email" required class="h-10 border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 rounded px-3 flex-1 min-w-0 focus:outline-none focus:ring-2 focus:ring-primary/40 focus:border-primary" placeholder="you@example.com" />
        <button type="submit" class="h-10 inline-flex items-center justify-center px-4 rounded bg-slate-900 text-white dark:bg-slate-100 dark:text-slate-900 hover:opacity-95 focus:outline-none focus:ring-2 focus:ring-primary/50">Subscribe</button>
      </form>
    </div>
    <div>
      <h4 class="font-semibold mb-3"><?php esc_html_e('Contact', 'aqualuxe'); ?></h4>
      <p class="text-sm">Email: <a href="mailto:info@aqualuxe.example">info@aqualuxe.example</a></p>
    </div>
  </div>
  <div class="text-center text-xs py-4 opacity-70">&copy; <?php echo esc_html(date('Y')); ?> AquaLuxe</div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
