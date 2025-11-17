<?php
$unique_id = function_exists('esc_attr') ? call_user_func('esc_attr', uniqid('search-form-')) : uniqid('search-form-');
$action = function_exists('esc_url') && function_exists('home_url') ? call_user_func('esc_url', call_user_func('home_url','/')) : '/';
$label = function_exists('esc_html__') ? call_user_func('esc_html__','Search for:','aqualuxe') : 'Search for:';
$placeholder = function_exists('esc_attr_x') ? call_user_func('esc_attr_x','Search…','placeholder','aqualuxe') : 'Search…';
$query_val = function_exists('get_search_query') ? call_user_func('get_search_query') : '';
$submit = function_exists('esc_html_x') ? call_user_func('esc_html_x','Search','submit button','aqualuxe') : 'Search';
?>
<form role="search" method="get" class="search-form flex gap-2" action="<?php echo $action; ?>">
	<label for="<?php echo $unique_id; ?>" class="sr-only"><?php echo $label; ?></label>
	<input type="search" id="<?php echo $unique_id; ?>" class="search-field px-4 py-2 rounded-md bg-white/10 text-white placeholder-white/60 focus:outline-none focus:ring-2 focus:ring-sky-500" placeholder="<?php echo $placeholder; ?>" value="<?php echo $query_val; ?>" name="s" />
	<button type="submit" class="btn btn-primary" data-cta="search_submit"><?php echo $submit; ?></button>
</form>
