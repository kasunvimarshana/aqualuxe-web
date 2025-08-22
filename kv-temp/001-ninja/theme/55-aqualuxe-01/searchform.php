<?php
/**
 * The template for displaying search forms
 *
 * @package AquaLuxe
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}
?>

<form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
    <label class="screen-reader-text" for="search-form-input"><?php echo esc_html_x('Search for:', 'label', 'aqualuxe'); ?></label>
    <div class="search-form-container">
        <input type="search" id="search-form-input" class="search-field" placeholder="<?php echo esc_attr_x('Search &hellip;', 'placeholder', 'aqualuxe'); ?>" value="<?php echo get_search_query(); ?>" name="s" />
        <button type="submit" class="search-submit" aria-label="<?php echo esc_attr_x('Search', 'submit button', 'aqualuxe'); ?>">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M18.031 16.617l4.283 4.282-1.415 1.415-4.282-4.283A8.96 8.96 0 0 1 11 20c-4.968 0-9-4.032-9-9s4.032-9 9-9 9 4.032 9 9a8.96 8.96 0 0 1-1.969 5.617zm-2.006-.742A6.977 6.977 0 0 0 18 11c0-3.868-3.133-7-7-7-3.868 0-7 3.132-7 7 0 3.867 3.132 7 7 7a6.977 6.977 0 0 0 4.875-1.975l.15-.15z"/></svg>
            <span class="screen-reader-text"><?php echo esc_html_x('Search', 'submit button', 'aqualuxe'); ?></span>
        </button>
    </div>
</form>