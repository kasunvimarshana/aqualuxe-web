    <footer class="site-footer mt-16 border-t border-slate-200 dark:border-slate-800" role="contentinfo">
      <div class="container mx-auto px-4 py-10 grid gap-8 md:grid-cols-4">
        <div>
          <h2 class="text-lg font-semibold mb-3"><?php esc_html_e('About AquaLuxe', 'aqualuxe'); ?></h2>
          <p class="text-sm opacity-80"><?php esc_html_e('Bringing elegance to aquatic life – globally.', 'aqualuxe'); ?></p>
        </div>
        <?php if (is_active_sidebar('footer-1')) dynamic_sidebar('footer-1'); ?>
      </div>
      <div class="container mx-auto px-4 py-4 text-sm opacity-70 flex items-center justify-between">
        <span>&copy; <?php echo esc_html(gmdate('Y')); ?> <?php bloginfo('name'); ?></span>
        <nav aria-label="Footer">
          <?php wp_nav_menu(['theme_location' => 'footer', 'container' => false, 'menu_class' => 'flex gap-4']); ?>
        </nav>
      </div>
    </footer>
    <?php wp_footer(); ?>
    <div id="qv-backdrop" class="modal-backdrop" hidden></div>
    <div id="qv-modal" class="modal" hidden aria-hidden="true" role="dialog" aria-modal="true" aria-labelledby="qv-title">
      <div class="dialog" role="document">
        <div class="sr-only" id="a11y-live" aria-live="polite" aria-atomic="true"></div>
        <div class="flex items-start justify-between gap-4">
          <h2 id="qv-title" class="text-lg font-semibold m-0"><?php echo function_exists('esc_html__') ? call_user_func('esc_html__', 'Quick View', 'aqualuxe') : 'Quick View'; ?></h2>
          <button type="button" id="qv-close" class="float-right" aria-label="Close">×</button>
        </div>
        <div id="qv-content" class="mt-6" aria-live="polite"></div>
      </div>
    </div>
  </body>
</html>
