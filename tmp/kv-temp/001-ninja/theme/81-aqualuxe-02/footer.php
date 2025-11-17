<?php if (!defined('ABSPATH')) exit; ?>
</main>
<footer class="mt-12 border-t border-slate-200/50 dark:border-slate-700/50">
  <div class="mx-auto px-4 <?php echo esc_attr(get_theme_mod('aqualuxe_container_width','max-w-7xl')); ?> py-10 grid md:grid-cols-3 gap-8">
    <div>
      <h2 class="font-semibold mb-2"><?php bloginfo('name'); ?></h2>
      <p class="opacity-75"><?php bloginfo('description'); ?></p>
    </div>
    <div>
      <?php wp_nav_menu(['theme_location'=>'footer','container'=>false,'menu_class'=>'grid gap-2']); ?>
    </div>
    <div>
      <form method="post" action="#" class="grid gap-2" aria-label="Newsletter">
        <label class="sr-only" for="newsletter-email"><?php esc_html_e('Email','aqualuxe'); ?></label>
        <input id="newsletter-email" class="border p-2" type="email" placeholder="<?php esc_attr_e('Your email','aqualuxe'); ?>" required />
        <button class="bg-cyan-600 text-white px-4 py-2 rounded" type="submit"><?php esc_html_e('Subscribe','aqualuxe'); ?></button>
      </form>
    </div>
  </div>
  <div class="text-center text-sm opacity-70 py-4">&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?> — <?php esc_html_e('Bringing elegance to aquatic life – globally','aqualuxe'); ?></div>
</footer>
<?php wp_footer(); ?>
</body></html>
