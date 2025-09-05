<?php
/** Footer */
?>
  </main>
  <footer class="border-t border-slate-200/60 dark:border-slate-700/60 mt-10" role="contentinfo">
    <div class="container mx-auto px-4 py-8 text-sm flex flex-col md:flex-row items-center justify-between gap-4">
      <p>&copy; <?php echo esc_html(date('Y')); ?> <?php bloginfo('name'); ?>. <?php esc_html_e('All rights reserved.', 'aqualuxe'); ?></p>
      <nav aria-label="Footer">
        <?php wp_nav_menu([
          'theme_location' => 'footer',
          'menu_class' => 'flex gap-4',
          'container' => false,
          'fallback_cb' => false,
        ]); ?>
      </nav>
    </div>
  </footer>
  <?php wp_footer(); ?>
</body>
</html>
