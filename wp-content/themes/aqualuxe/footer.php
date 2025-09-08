<?php if (!defined('ABSPATH')) { exit; } ?>
</div>
<footer class="site_footer mt-16 border-t border-slate-200 dark:border-slate-800" role="contentinfo">
  <div class="container mx-auto px-4 py-10 grid grid-cols-1 md:grid-cols-4 gap-8">
    <section>
      <h2 class="text-sm font-semibold mb-3"><?php esc_html_e('About AquaLuxe', 'aqualuxe'); ?></h2>
      <p class="text-sm opacity-80"><?php esc_html_e('Bringing elegance to aquatic life – globally.', 'aqualuxe'); ?></p>
    </section>
    <?php if (is_active_sidebar('footer-1')) { dynamic_sidebar('footer-1'); } ?>
  </div>
  <div class="border-t border-slate-200 dark:border-slate-800">
    <div class="container mx-auto px-4 py-4 text-sm flex items-center justify-between">
      <span>&copy; <?php echo esc_html(date('Y')); ?> <?php bloginfo('name'); ?></span>
      <nav aria-label="Footer"><?php wp_nav_menu(['theme_location' => 'footer','container' => false,'menu_class' => 'flex gap-4 text-sm']); ?></nav>
    </div>
  </div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
