<?php
/**
 * Site header
 */
?>
<?php
// Guarded WP helpers for static analyzers
$esc_url = function_exists('esc_url') ? 'esc_url' : null;
$home_url = function_exists('home_url') ? 'home_url' : null;
$esc_html = function_exists('esc_html') ? 'esc_html' : null;
$bloginfo = function_exists('get_bloginfo') ? 'get_bloginfo' : null;
$wp_nav_menu = function_exists('wp_nav_menu') ? 'wp_nav_menu' : null;
$esc_attr__ = function_exists('esc_attr__') ? 'esc_attr__' : null;

$home_href = $home_url ? call_user_func($home_url, '/') : '/';
$wishlist_href = $home_url ? call_user_func($home_url, '/wishlist/') : '/wishlist/';
?>
<header class="site-header" role="banner">
  <div class="container">
    <a class="site-logo" href="<?php echo $esc_url ? call_user_func($esc_url, $home_href) : $home_href; ?>">
      <?php
        $name = $bloginfo ? call_user_func($bloginfo, 'name') : 'AquaLuxe';
        echo $esc_html ? call_user_func($esc_html, (string) $name) : (string) $name;
      ?>
    </a>
    <?php if ($wp_nav_menu) { call_user_func($wp_nav_menu, ['theme_location' => 'primary', 'container' => 'nav', 'container_class' => 'nav-primary', 'fallback_cb' => '__return_empty_string']); } ?>
    <div class="header-actions" style="display:flex; gap:.5rem; align-items:center;">
      <a class="aqlx-wishlist-link" href="<?php echo $esc_url ? call_user_func($esc_url, $wishlist_href) : $wishlist_href; ?>" aria-label="<?php echo $esc_attr__ ? call_user_func($esc_attr__, 'Wishlist', 'aqualuxe') : 'Wishlist'; ?>">
        ❤ <span class="aqlx-wishlist-count" aria-live="polite">0</span>
      </a>
      <button id="aqlx-dark-toggle" class="dark-toggle" aria-pressed="false" aria-label="Toggle dark mode">🌙</button>
    </div>
  </div>
</header>
