<?php
/** Product filters module: adds basic filtering on product archives */

add_action('init', function(){
    if (!post_type_exists('product')) return;
    add_shortcode('aqualuxe_product_filters', 'aqlx_filters_shortcode');
    add_action('pre_get_posts', 'aqlx_filters_query');
});

function aqlx_filters_shortcode(){
    if (!post_type_exists('product')) return '';
    $min = isset($_GET['min']) ? absint($_GET['min']) : '';
    $max = isset($_GET['max']) ? absint($_GET['max']) : '';
    $cat = isset($_GET['cat']) ? sanitize_text_field($_GET['cat']) : '';
    ob_start(); ?>
    <form method="get" class="grid grid-cols-1 md:grid-cols-4 gap-3 mb-4" aria-label="Filters">
      <input class="border px-2 py-1" type="number" name="min" placeholder="Min Price" value="<?php echo esc_attr($min); ?>" />
      <input class="border px-2 py-1" type="number" name="max" placeholder="Max Price" value="<?php echo esc_attr($max); ?>" />
      <input class="border px-2 py-1" type="text" name="cat" placeholder="Category slug" value="<?php echo esc_attr($cat); ?>" />
      <button class="btn"><?php esc_html_e('Filter','aqualuxe'); ?></button>
    </form>
    <?php return ob_get_clean();
}

function aqlx_filters_query($q){
    if (is_admin() || !$q->is_main_query()) return;
    if (!post_type_exists('product')) return;
    if (!is_shop() && !is_post_type_archive('product') && !is_tax('product_cat')) return;
    $tax = [];
    if (!empty($_GET['cat']) && taxonomy_exists('product_cat')){
        $tax[] = [ 'taxonomy'=>'product_cat', 'field'=>'slug', 'terms'=> sanitize_text_field($_GET['cat']) ];
    }
    if (!empty($tax)) $q->set('tax_query', $tax);
    $meta = [];
    if (!empty($_GET['min'])){ $meta[] = ['key'=>'_price','value'=>absint($_GET['min']),'compare'=>'>=','type'=>'NUMERIC']; }
    if (!empty($_GET['max'])){ $meta[] = ['key'=>'_price','value'=>absint($_GET['max']),'compare'=>'<=','type'=>'NUMERIC']; }
    if (!empty($meta)) $q->set('meta_query', $meta);
}
