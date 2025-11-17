<?php
namespace AquaLuxe\TemplateTags;

function the_site_logo(): void
{
    if (\function_exists('the_custom_logo') && \has_custom_logo()) {
        	he_custom_logo();
        return;
    }
    echo '<a class="site-brand text-xl font-semibold" href="' . \esc_url(\home_url('/')) . '">' . \esc_html(\get_bloginfo('name')) . '</a>';
}

function meta_og_tags(): void
{
    echo '<meta property="og:site_name" content="' . \esc_attr(\get_bloginfo('name')) . '" />' . "\n";
    echo '<meta property="og:url" content="' . \esc_url(\home_url(\add_query_arg([]))) . '" />' . "\n";
    echo '<meta property="og:type" content="website" />' . "\n";
}
