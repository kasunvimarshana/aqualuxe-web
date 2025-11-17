<?php
/** Footer */
?>
</main>
<footer class="mt-16 border-t border-slate-200/60 dark:border-slate-700/60" itemscope itemtype="https://schema.org/Organization">
  <div class="container max-w-7xl mx-auto px-4 py-10 grid md:grid-cols-4 gap-8">
    <div>
      <h4 class="font-semibold mb-3"><?php esc_html_e('About AquaLuxe', 'aqualuxe'); ?></h4>
      <p class="text-sm opacity-80"><?php echo esc_html__('Bringing elegance to aquatic life – globally.', 'aqualuxe'); ?></p>
    </div>
    <div>
      <h4 class="font-semibold mb-3"><?php esc_html_e('Quick Links', 'aqualuxe'); ?></h4>
      <?php wp_nav_menu(['theme_location' => 'footer']); ?>
    </div>
    <div>
      <h4 class="font-semibold mb-3"><?php esc_html_e('Newsletter', 'aqualuxe'); ?></h4>
      <form method="post" action="#" class="flex gap-2">
        <label class="sr-only" for="nl_email"><?php esc_html_e('Email', 'aqualuxe'); ?></label>
        <input id="nl_email" type="email" required class="border rounded px-3 py-2 flex-1" placeholder="you@example.com" />
        <button type="submit" class="bg-slate-900 text-white dark:bg-slate-100 dark:text-slate-900 px-4 rounded">Subscribe</button>
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
