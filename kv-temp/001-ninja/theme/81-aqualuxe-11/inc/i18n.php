<?php
/** i18n helpers */
if (!defined('ABSPATH')) { exit; }

add_filter('locale', function($locale){
    // Reserved for programmatic locale changes if needed
    return $locale;
});
