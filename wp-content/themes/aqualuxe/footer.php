<?php do_action('aqualuxe/before_footer'); ?>
<footer class="mt-20 border-t border-gray-100 dark:border-gray-800">
  <div class="container mx-auto px-4 py-10 grid gap-8 md:grid-cols-4">
    <?php if (is_active_sidebar('sidebar-1')) { dynamic_sidebar('sidebar-1'); } ?>
    <div>
      <h4 class="font-semibold mb-3"><?php esc_html_e('Newsletter', 'aqualuxe'); ?></h4>
      <form method="post" action="#" onsubmit="return false;" class="flex gap-2">
        <label class="sr-only" for="nl-email"><?php esc_html_e('Email', 'aqualuxe'); ?></label>
        <input id="nl-email" type="email" class="px-3 py-2 rounded-md bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700" placeholder="<?php esc_attr_e('Email address', 'aqualuxe'); ?>" required>
        <button class="btn-primary"><?php esc_html_e('Subscribe', 'aqualuxe'); ?></button>
      </form>
    </div>
    <div class="md:col-span-2 prose dark:prose-invert max-w-none">
      <p>&copy; <?php echo esc_html(date('Y')); ?> AquaLuxe — <?php esc_html_e('Bringing elegance to aquatic life – globally.', 'aqualuxe'); ?></p>
      <nav aria-label="Legal" class="mt-2">
        <?php wp_nav_menu(['theme_location' => 'footer', 'container' => false, 'menu_class' => 'flex gap-4 text-sm']); ?>
      </nav>
    </div>
  </div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
