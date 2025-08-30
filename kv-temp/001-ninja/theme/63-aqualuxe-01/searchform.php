<?php
/**
 * The template for displaying search forms
 *
 * @package AquaLuxe
 */

?>

<form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
    <label class="screen-reader-text" for="search-form-input"><?php esc_html_e('Search for:', 'aqualuxe'); ?></label>
    <div class="search-form-container">
        <input type="search" id="search-form-input" class="search-field" placeholder="<?php echo esc_attr_x('Search &hellip;', 'placeholder', 'aqualuxe'); ?>" value="<?php echo get_search_query(); ?>" name="s" />
        <button type="submit" class="search-submit" aria-label="<?php esc_attr_e('Search', 'aqualuxe'); ?>">
            <i class="fas fa-search" aria-hidden="true"></i>
            <span class="screen-reader-text"><?php esc_html_e('Search', 'aqualuxe'); ?></span>
        </button>
    </div>
</form>