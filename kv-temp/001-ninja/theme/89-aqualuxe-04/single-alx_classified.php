<?php
/** Single template for AquaLuxe Classifieds (analyzer-safe) */
if (function_exists('get_header')) { call_user_func('get_header'); }
?>

<main id="primary" class="site-main">
<?php
// Submission success notice
if ( isset($_GET['submitted']) && $_GET['submitted'] === '1' ) {
    $msg = function_exists('esc_html__') ? esc_html__('Your listing was submitted and is pending review.', 'aqualuxe') : 'Your listing was submitted and is pending review.';
    echo '<div class="alx-alert alx-alert-success" role="status">'.( function_exists('esc_html') ? esc_html($msg) : $msg ).'</div>';
}
?>
<?php if ( function_exists('have_posts') && call_user_func('have_posts') ) :
    while ( call_user_func('have_posts') ) : call_user_func('the_post');
        $get_the_ID = function_exists('get_the_ID') ? (int) call_user_func('get_the_ID') : 0;
        $price = function_exists('get_post_meta') ? call_user_func('get_post_meta', $get_the_ID, '_alx_price', true ) : '';
        $formatted = ($price !== '' && $price !== null) ? $price : '';
        if ($formatted !== '') {
            if ( function_exists('AquaLuxe\\Modules\\Multicurrency\\format_current_amount') ) {
                $formatted = call_user_func('AquaLuxe\\Modules\\Multicurrency\\format_current_amount', (float) $price );
            } else {
                $formatted = function_exists('esc_html') ? esc_html( $price ) : (string) $price;
            }
        }
?>
    <article id="post-<?php echo function_exists('the_ID') ? esc_attr(get_the_ID()) : '0'; ?>" <?php if (function_exists('post_class')) { post_class('alx-classified'); } ?>>
        <header class="entry-header">
            <h1 class="entry-title"><?php if (function_exists('the_title')) { the_title(); } ?></h1>
            <?php if ($formatted !== ''): ?>
            <p class="alx-price"><strong><?php echo function_exists('esc_html__') ? esc_html__('Price:', 'aqualuxe') : 'Price:'; ?></strong> <?php echo function_exists('wp_kses_post') ? wp_kses_post($formatted) : $formatted; ?></p>
            <?php endif; ?>
            <div class="entry-meta">
                <?php
                $cat_list = function_exists('get_the_term_list') ? call_user_func('get_the_term_list', $get_the_ID, 'alx_classified_category', '', ', ') : '';
                $loc_list = function_exists('get_the_term_list') ? call_user_func('get_the_term_list', $get_the_ID, 'alx_classified_location', '', ', ') : '';
                if ($cat_list) {
                    echo '<span class="alx-cats">'.( function_exists('esc_html__') ? esc_html__('Category:', 'aqualuxe') : 'Category:' ).' '.( function_exists('wp_kses_post') ? wp_kses_post($cat_list) : $cat_list ).'</span> ';
                }
                if ($loc_list) {
                    echo '<span class="alx-locs">'.( function_exists('esc_html__') ? esc_html__('Location:', 'aqualuxe') : 'Location:' ).' '.( function_exists('wp_kses_post') ? wp_kses_post($loc_list) : $loc_list ).'</span>';
                }
                ?>
            </div>
        </header>

        <div class="entry-content">
            <?php if (function_exists('the_content')) { the_content(); } ?>
        </div>
    </article>
<?php endwhile; endif; ?>
</main>

<?php if (function_exists('get_footer')) { call_user_func('get_footer'); }
