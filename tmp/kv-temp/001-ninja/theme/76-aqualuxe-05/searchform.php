<?php defined('ABSPATH') || exit; ?>
<form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
  <label class="sr-only" for="s"><?php esc_html_e('Search for:', 'aqualuxe'); ?></label>
  <input type="search" id="s" class="border rounded px-3 py-2" placeholder="<?php echo esc_attr_x('Search …', 'placeholder', 'aqualuxe'); ?>" value="<?php echo get_search_query(); ?>" name="s" />
  <button class="aqlx-btn" type="submit"><?php echo esc_html_x('Search', 'submit button', 'aqualuxe'); ?></button>
</form>
