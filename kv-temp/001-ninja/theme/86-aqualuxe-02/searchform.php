<?php
/* Accessible Search Form */
?>
<form role="search" method="get" class="search-form flex gap-2" action="<?php echo esc_url(home_url('/')); ?>">
  <label for="s" class="sr-only"><?php esc_html_e('Search for:', 'aqualuxe'); ?></label>
  <input type="search" id="s" class="rounded border px-3 py-2 flex-1" placeholder="<?php echo esc_attr_x('Search…', 'placeholder', 'aqualuxe'); ?>" value="<?php echo esc_attr(get_search_query()); ?>" name="s" />
  <button type="submit" class="btn btn-primary"><?php echo esc_html_x('Search', 'submit button', 'aqualuxe'); ?></button>
  <input type="hidden" name="post_type" value="any" />
</form>
