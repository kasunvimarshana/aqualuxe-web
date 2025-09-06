<?php
/** Archive template for AquaLuxe Classifieds (analyzer-safe) */
if (function_exists('get_header')) { call_user_func('get_header'); }
?>

<main id="primary" class="site-main">
    <header class="page-header">
        <h1 class="page-title"><?php echo function_exists('esc_html__') ? esc_html__('Classifieds', 'aqualuxe') : 'Classifieds'; ?></h1>
    </header>
    <?php
    if ( isset($_GET['submitted']) && $_GET['submitted'] === '1' ) {
        $msg = function_exists('esc_html__') ? esc_html__('Your listing was submitted and is pending review.', 'aqualuxe') : 'Your listing was submitted and is pending review.';
        echo '<div class="alx-alert alx-alert-success" role="status">'.( function_exists('esc_html') ? esc_html($msg) : $msg ).'</div>';
    }
    ?>

    <?php if ( function_exists('have_posts') && call_user_func('have_posts') ) : ?>
        <div class="alx-classifieds-grid">
            <?php while ( call_user_func('have_posts') ) : call_user_func('the_post');
                $post_id = function_exists('get_the_ID') ? (int) call_user_func('get_the_ID') : 0;
                $price = function_exists('get_post_meta') ? call_user_func('get_post_meta', $post_id, '_alx_price', true ) : '';
                $formatted = ($price !== '' && $price !== null) ? $price : '';
                if ($formatted !== '') {
                    if ( function_exists('AquaLuxe\\Modules\\Multicurrency\\format_current_amount') ) {
                        $formatted = call_user_func('AquaLuxe\\Modules\\Multicurrency\\format_current_amount', (float) $price );
                    } else {
                        $formatted = function_exists('esc_html') ? esc_html($price) : (string) $price;
                    }
                }
            ?>
            <article <?php if (function_exists('post_class')) { post_class('alx-classified-item'); } ?>>
                <h2 class="entry-title">
                    <?php if (function_exists('the_title')) {
                        $perma = function_exists('get_permalink') ? call_user_func('get_permalink', $post_id) : '#';
                        $perma = function_exists('esc_url') ? esc_url($perma) : $perma;
                        echo '<a href="'.$perma.'">'; the_title(); echo '</a>';
                    } ?>
                </h2>
                <?php if ($formatted !== ''): ?>
                    <p class="alx-price"><strong><?php echo function_exists('esc_html__') ? esc_html__('Price:', 'aqualuxe') : 'Price:'; ?></strong> <?php echo function_exists('wp_kses_post') ? wp_kses_post($formatted) : $formatted; ?></p>
                <?php endif; ?>
                <div class="entry-excerpt"><?php if (function_exists('the_excerpt')) { the_excerpt(); } ?></div>
            </article>
            <?php endwhile; ?>
        </div>

    <nav class="navigation posts-navigation">
            <div class="nav-links">
        <div class="nav-previous"><?php if (function_exists('previous_posts_link')) { call_user_func('previous_posts_link', ( function_exists('esc_html__') ? esc_html__('Newer', 'aqualuxe') : 'Newer' ) ); } ?></div>
        <div class="nav-next"><?php if (function_exists('next_posts_link')) { call_user_func('next_posts_link', ( function_exists('esc_html__') ? esc_html__('Older', 'aqualuxe') : 'Older' ) ); } ?></div>
            </div>
        </nav>
    <?php else: ?>
        <p><?php echo function_exists('esc_html__') ? esc_html__('No classifieds found.', 'aqualuxe') : 'No classifieds found.'; ?></p>
    <?php endif; ?>
</main>

<?php if (function_exists('get_footer')) { call_user_func('get_footer'); }
