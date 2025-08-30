<footer class="mt-16 bg-slate-50 dark:bg-slate-800">
  <div class="container mx-auto px-4 py-10 grid md:grid-cols-4 gap-6">
    <div>
      <div class="site-brand mb-3"><?php \AquaLuxe\Core\Template_Tags::site_brand(); ?></div>
      <p class="text-sm opacity-80"><?php echo esc_html(get_bloginfo('description')); ?></p>
    </div>
    <?php if (is_active_sidebar('footer-1')) dynamic_sidebar('footer-1'); ?>
    <div>
      <h3 class="font-semibold mb-2"><?php esc_html_e('Newsletter', 'aqualuxe'); ?></h3>
      <form class="flex gap-2" method="post" action="#">
        <label class="sr-only" for="nl-email"><?php esc_html_e('Email', 'aqualuxe'); ?></label>
        <input id="nl-email" class="px-3 py-2 rounded border w-full" type="email" placeholder="you@example.com" required>
        <button class="btn-primary" type="submit"><?php esc_html_e('Subscribe', 'aqualuxe'); ?></button>
      </form>
    </div>
  </div>
  <div class="border-t border-slate-200 dark:border-slate-700 py-4 text-center text-sm opacity-80">
    &copy; <?php echo esc_html(date('Y')); ?> AquaLuxe. <?php esc_html_e('All rights reserved.', 'aqualuxe'); ?>
  </div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
