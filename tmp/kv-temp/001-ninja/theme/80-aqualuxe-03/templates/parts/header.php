<?php
?><header class="site-header" role="banner">
  <div class="container mx-auto px-4 flex items-center justify-between py-4">
    <a class="logo text-xl font-semibold" href="<?php echo esc_url(home_url('/')); ?>" aria-label="<?php bloginfo('name'); ?>">
      <?php if (has_custom_logo()) { the_custom_logo(); } else { bloginfo('name'); } ?>
    </a>
    <nav class="primary-nav" aria-label="Primary">
      <?php wp_nav_menu([
        'theme_location' => 'primary',
        'container' => false,
        'fallback_cb' => '__return_false',
        'menu_class' => 'flex items-center gap-6',
        'depth' => 2,
      ]); ?>
    </nav>
    <button id="darkModeToggle" class="ml-4" aria-pressed="false" aria-label="Toggle dark mode">🌙</button>
  </div>
</header>
