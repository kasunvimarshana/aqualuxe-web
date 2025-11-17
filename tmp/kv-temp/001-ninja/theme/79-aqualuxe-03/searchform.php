<?php
/** Accessible search form */
?>
<form role="search" method="get" class="aqlx-search flex items-stretch gap-2 justify-center" action="<?php echo esc_url( home_url( '/' ) ); ?>">
  <label for="s" class="sr-only"><?php esc_html_e('Search for:', 'aqualuxe'); ?></label>
  <input type="search" id="s" class="border rounded px-3 py-2 min-w-[240px]" placeholder="<?php echo esc_attr__('Search…', 'aqualuxe'); ?>" value="<?php echo get_search_query(); ?>" name="s" />
  <button type="submit" class="bg-slate-900 text-white dark:bg-slate-100 dark:text-slate-900 px-4 py-2 rounded"><?php esc_html_e('Search', 'aqualuxe'); ?></button>
</form>
