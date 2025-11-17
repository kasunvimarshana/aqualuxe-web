<?php
/** R&D / Sustainability highlights module (block/shortcode) */

add_shortcode('aqualuxe_sustainability', function(){
    $items = [
        ['title'=>'Ethical Sourcing','text'=>'We prioritize responsible breeders and eco-friendly logistics.'],
        ['title'=>'Quarantine & Health','text'=>'Rigorous quarantine protocols to ensure livestock well-being.'],
        ['title'=>'Eco Packaging','text'=>'Sustainable materials to minimize environmental impact.'],
    ];
    ob_start(); echo '<section class="grid gap-4 md:grid-cols-3">';
    foreach($items as $it){
        echo '<div class="p-4 border rounded"><h3 class="font-semibold">' . esc_html($it['title']) . '</h3><p class="opacity-80">' . esc_html($it['text']) . '</p></div>';
    }
    echo '</section>'; return ob_get_clean();
});
