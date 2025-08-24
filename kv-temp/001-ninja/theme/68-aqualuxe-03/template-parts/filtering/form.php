<?php
/**
 * Filtering form template for shop/archive pages
 * Place in template-parts/filtering/form.php
 */
if ( ! defined( 'ABSPATH' ) ) exit;

$min = isset( $_GET['filter_price_min'] ) ? esc_attr( $_GET['filter_price_min'] ) : '';
$max = isset( $_GET['filter_price_max'] ) ? esc_attr( $_GET['filter_price_max'] ) : '';
$rating = isset( $_GET['filter_rating'] ) ? esc_attr( $_GET['filter_rating'] ) : '';
$action = get_post_type_archive_link( 'product' );
?>
<form class="aqualuxe-filter-form" method="get" action="<?php echo esc_url( $action ); ?>">
  <div class="filter-group">
    <label for="filter_price_min">Min Price</label>
    <input type="number" name="filter_price_min" id="filter_price_min" value="<?php echo $min; ?>" min="0" step="1">
  </div>
  <div class="filter-group">
    <label for="filter_price_max">Max Price</label>
    <input type="number" name="filter_price_max" id="filter_price_max" value="<?php echo $max; ?>" min="0" step="1">
  </div>
  <div class="filter-group">
    <label for="filter_rating">Rating</label>
    <select name="filter_rating" id="filter_rating">
      <option value="">Any</option>
      <option value="1" <?php selected( $rating, '1' ); ?>>1+</option>
      <option value="2" <?php selected( $rating, '2' ); ?>>2+</option>
      <option value="3" <?php selected( $rating, '3' ); ?>>3+</option>
      <option value="4" <?php selected( $rating, '4' ); ?>>4+</option>
      <option value="5" <?php selected( $rating, '5' ); ?>>5</option>
    </select>
  </div>
  <button type="submit">Apply Filters</button>
</form>
