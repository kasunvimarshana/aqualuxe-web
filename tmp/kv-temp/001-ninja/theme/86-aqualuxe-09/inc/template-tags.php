<?php
namespace AquaLuxe\TemplateTags;

function the_site_logo(): void
{
    $has_logo = \function_exists('has_custom_logo') ? \call_user_func('has_custom_logo') : false;
    if (\function_exists('the_custom_logo') && $has_logo) {
        \call_user_func('the_custom_logo');
        return;
    }
    $home = \function_exists('home_url') ? \call_user_func('home_url', '/') : '/';
    $name = \function_exists('get_bloginfo') ? \call_user_func('get_bloginfo', 'name') : 'AquaLuxe';
    $home = \function_exists('esc_url') ? \call_user_func('esc_url', $home) : $home;
    $name = \function_exists('esc_html') ? \call_user_func('esc_html', $name) : $name;
    echo '<a class="site-brand text-xl font-semibold" href="' . $home . '">' . $name . '</a>';
}

function meta_og_tags(): void
{
    $site = \function_exists('get_bloginfo') ? \call_user_func('get_bloginfo', 'name') : '';
    $site = \function_exists('esc_attr') ? \call_user_func('esc_attr', $site) : $site;
    $url = '';
    if (\function_exists('add_query_arg') && \function_exists('home_url')) {
        $url = \call_user_func('home_url', \call_user_func('add_query_arg', []));
    }
    $url = \function_exists('esc_url') ? \call_user_func('esc_url', $url) : $url;
    echo '<meta property="og:site_name" content="' . $site . '" />' . "\n";
    echo '<meta property="og:url" content="' . $url . '" />' . "\n";
    echo '<meta property="og:type" content="website" />' . "\n";
}
