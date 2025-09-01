<?php
/** Events module: archive template overrides + widgets */
if (!defined('ABSPATH')) { exit; }

add_filter('template_include', function($template){
    if (is_post_type_archive('event')) {
        $t = locate_template('templates/archive-event.php');
        if ($t) return $t;
    }
    return $template;
});
