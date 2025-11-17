<?php
/**
 * Accessible search form with progressive enhancement.
 */
?>
<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>" data-aqlx-search>
    <label>
        <span class="screen-reader-text"><?php echo esc_html_x( 'Search for:', 'label', 'aqualuxe' ); ?></span>
        <input type="search" class="search-field" placeholder="<?php echo esc_attr_x( 'Search …', 'placeholder', 'aqualuxe' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>" name="s" />
    </label>
    <button type="submit" class="search-submit"><?php echo esc_html_x( 'Search', 'submit button', 'aqualuxe' ); ?></button>
    <ul data-aqlx-results aria-live="polite"></ul>
    <noscript><small><?php esc_html_e( 'JavaScript disabled; results will load after submit.', 'aqualuxe' ); ?></small></noscript>
    <input type="hidden" name="post_type" value="listing" />
    <input type="hidden" name="per_page" value="10" />
</form>
