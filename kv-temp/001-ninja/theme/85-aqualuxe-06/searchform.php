<?php
/**
 * Accessible search form
 */

defined('ABSPATH') || exit;
$unique_id = esc_attr( uniqid('search-form-') );
$action = esc_url( home_url('/') );
$query  = get_search_query();
?>
<form role="search" method="get" class="search-form" action="<?php echo $action; ?>">
  <label for="<?php echo $unique_id; ?>" class="sr-only"><?php esc_html_e('Search for:', 'aqualuxe'); ?></label>
  <input type="search" id="<?php echo $unique_id; ?>" class="search-field" placeholder="<?php echo esc_attr_x('Search …', 'placeholder', 'aqualuxe'); ?>" value="<?php echo esc_attr($query); ?>" name="s" />
  <button type="submit" class="search-submit" aria-label="<?php esc_attr_e('Submit search', 'aqualuxe'); ?>">
    <span><?php echo esc_html_x('Search', 'submit button', 'aqualuxe'); ?></span>
  </button>
</form>
