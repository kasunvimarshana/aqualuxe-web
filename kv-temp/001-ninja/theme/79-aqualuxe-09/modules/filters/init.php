<?php
declare(strict_types=1);

namespace Aqualuxe\Modules\Filters;

// Attribute/category filters for Woo archives. Dual-state: safe when WooCommerce inactive.

// Render filters above product loop
add_action('woocommerce_before_shop_loop', function(){
    if (!class_exists('WooCommerce')) return;
    // Fetch product categories and selected attributes
    $cats = get_terms(['taxonomy'=>'product_cat','hide_empty'=>true]);
    $attrs = [];
  if (function_exists('wc_get_attribute_taxonomies')) {
    foreach (call_user_func('wc_get_attribute_taxonomies') as $tax) {
            $tax_name = 'pa_' . $tax->attribute_name;
            if (taxonomy_exists($tax_name)) {
                $attrs[$tax_name] = get_terms(['taxonomy'=>$tax_name,'hide_empty'=>true]);
            }
        }
    }
    $current = [
        'cat' => isset($_GET['filter_cat']) ? (int) $_GET['filter_cat'] : 0,
        'attrs' => array_map('sanitize_text_field', (array) ($_GET['filter_attr'] ?? [])),
    ];
    ?>
  <form class="aqlx-filters mb-4 flex flex-wrap gap-3 items-end" method="get">
      <label class="flex flex-col">
        <span><?php esc_html_e('Category', 'aqualuxe'); ?></span>
        <select name="filter_cat" class="min-w-[200px]">
          <option value="">--</option>
          <?php foreach ($cats as $c): ?>
            <option value="<?php echo esc_attr((string) $c->term_id); ?>" <?php selected($current['cat'], $c->term_id); ?>><?php echo esc_html($c->name); ?></option>
          <?php endforeach; ?>
        </select>
      </label>
      <?php foreach ($attrs as $tax => $terms): ?>
      <label class="flex flex-col">
        <span><?php echo esc_html(get_taxonomy($tax)->labels->singular_name ?? $tax); ?></span>
        <select name="filter_attr[<?php echo esc_attr($tax); ?>]" class="min-w-[200px]">
          <option value="">--</option>
          <?php foreach ($terms as $t): ?>
            <option value="<?php echo esc_attr($t->slug); ?>" <?php selected($current['attrs'][$tax] ?? '', $t->slug); ?>><?php echo esc_html($t->name); ?></option>
          <?php endforeach; ?>
        </select>
      </label>
      <?php endforeach; ?>
      <button type="submit" class="button"><?php esc_html_e('Apply', 'aqualuxe'); ?></button>
      <?php
        $clear_url = remove_query_arg(['filter_cat','filter_attr']);
      ?>
      <a href="<?php echo esc_url($clear_url); ?>" class="ml-2 text-sm opacity-80 hover:opacity-100"><?php esc_html_e('Clear filters', 'aqualuxe'); ?></a>
    </form>
    <?php
});

// Modify product queries based on filters
add_action('pre_get_posts', function($q){
    if (!class_exists('WooCommerce')) return;
    if (is_admin() || !$q->is_main_query()) return;
  $on_shop = function_exists('is_shop') ? (bool) call_user_func('is_shop') : false;
  $on_prod_tax = function_exists('is_product_taxonomy') ? (bool) call_user_func('is_product_taxonomy') : false;
  if (! ($on_shop || $on_prod_tax)) return;
    $taxq = (array) $q->get('tax_query');
    if (!empty($_GET['filter_cat'])) {
        $taxq[] = [ 'taxonomy' => 'product_cat', 'field' => 'term_id', 'terms' => [(int) $_GET['filter_cat']] ];
    }
    if (!empty($_GET['filter_attr']) && is_array($_GET['filter_attr'])) {
        foreach ($_GET['filter_attr'] as $tax => $slug) {
            $slug = sanitize_text_field((string) $slug);
            $tax = sanitize_key((string) $tax);
            if ($slug && taxonomy_exists($tax)) {
                $taxq[] = [ 'taxonomy' => $tax, 'field' => 'slug', 'terms' => [$slug] ];
            }
        }
    }
    if ($taxq) { $q->set('tax_query', $taxq); }
});
