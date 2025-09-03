<?php
/**
 * Site header
 */
?><header class="site-header" role="banner">
  <div class="container">
    <a class="site-logo" href="<?php echo esc_url(home_url('/')); ?>">
      <?php echo esc_html(get_bloginfo('name')); ?>
    </a>
    <?php wp_nav_menu(['theme_location' => 'primary', 'container' => 'nav', 'container_class' => 'nav-primary', 'fallback_cb' => '__return_empty_string']); ?>
  </div>
</header>
