<?php
// Root front page template that includes modular front-page under templates/
if (locate_template('templates/front-page.php')) {
    include locate_template('templates/front-page.php');
} else {
    get_header();
    echo '<div class="container mx-auto px-4 py-16"><h1>' . esc_html(get_bloginfo('name')) . '</h1><p>' . esc_html__('Welcome to AquaLuxe.', 'aqualuxe') . '</p></div>';
    get_footer();
}
