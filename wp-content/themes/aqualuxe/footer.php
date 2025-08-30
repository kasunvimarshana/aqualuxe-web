<?php
defined('ABSPATH') || exit;
?>
</main>
<?php aqualuxe_do_footer_top(); ?>
<footer class="mt-12 border-t border-slate-200 dark:border-slate-800 py-8">
  <div class="<?php echo esc_attr(aqlx_container_class()); ?>">
    <div class="grid md:grid-cols-4 gap-8">
      <div>
        <?php aqualuxe_the_logo(); ?>
        <p class="mt-3 text-sm opacity-80"><?php echo esc_html__('Bringing elegance to aquatic life – globally.', 'aqualuxe'); ?></p>
      </div>
      <div><?php wp_nav_menu(['theme_location' => 'footer', 'container' => false]); ?></div>
      <div>
        <h3 class="font-semibold mb-2"><?php esc_html_e('Newsletter', 'aqualuxe'); ?></h3>
        <form class="flex gap-2" method="post" action="#" onsubmit="return false;">
          <label class="sr-only" for="newsletter-email"><?php esc_html_e('Email', 'aqualuxe'); ?></label>
          <input id="newsletter-email" type="email" class="border rounded px-3 py-2 flex-1 dark:bg-slate-800" placeholder="you@example.com" required />
          <button class="bg-sky-600 hover:bg-sky-700 text-white px-4 py-2 rounded"><?php esc_html_e('Subscribe', 'aqualuxe'); ?></button>
        </form>
      </div>
      <div class="text-sm opacity-80">
        <p>&copy; <?php echo esc_html(date('Y')); ?> <?php bloginfo('name'); ?></p>
      </div>
    </div>
  </div>
</footer>
<?php aqualuxe_do_footer_bottom(); ?>
<?php locate_template('templates/quick-view-modal.php', true, false); ?>
<?php locate_template('templates/tradein-modal.php', true, false); ?>
<?php locate_template('templates/quote-modal.php', true, false); ?>
<?php wp_footer(); ?>
</body>
</html>
