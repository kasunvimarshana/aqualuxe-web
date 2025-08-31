<?php
declare(strict_types=1);

add_shortcode('aqlx_home', static function () {
    ob_start();
    get_template_part('templates/parts/home');
    return ob_get_clean();
});
