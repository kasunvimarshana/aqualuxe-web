<?php
/**
 * Template for displaying search forms
 *
 * @package AquaLuxe
 * @version 1.0.0
 */

$unique_id = esc_attr(uniqid('search-form-'));
?>

<form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
    <label for="<?php echo $unique_id; ?>">
        <span class="screen-reader-text"><?php echo esc_html_x('Search for:', 'label', 'aqualuxe'); ?></span>
    </label>
    <div class="search-form-wrapper">
        <input type="search" id="<?php echo $unique_id; ?>" class="search-field" placeholder="<?php echo esc_attr_x('Search &hellip;', 'placeholder', 'aqualuxe'); ?>" value="<?php echo get_search_query(); ?>" name="s" />
        <button type="submit" class="search-submit">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <circle cx="11" cy="11" r="8"></circle>
                <path d="m21 21-4.35-4.35"></path>
            </svg>
            <span class="screen-reader-text"><?php echo esc_html_x('Search', 'submit button', 'aqualuxe'); ?></span>
        </button>
    </div>
</form>