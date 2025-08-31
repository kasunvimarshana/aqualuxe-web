<?php
/**
 * Template Name: Wishlist
 * Description: Renders the AquaLuxe wishlist.
 */
declare(strict_types=1);

get_header(); ?>
<div class="container max-w-7xl mx-auto px-4 py-8">
  <h1 class="text-2xl font-semibold mb-6"><?php echo esc_html( get_the_title() ); ?></h1>
  <div class="prose dark:prose-invert max-w-none">
    <?php
      // Fallback if module is missing: show a friendly message
      $html = do_shortcode('[aqlx_wishlist]');
      if (empty($html)) {
        echo '<p>' . esc_html__( 'Wishlist feature is currently unavailable.', 'aqualuxe' ) . '</p>';
      } else {
        echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
      }
    ?>
  </div>
  <div class="mt-6">
    <a class="text-sm underline" href="<?php echo esc_url( home_url('/') ); ?>"><?php esc_html_e('Continue shopping', 'aqualuxe'); ?></a>
  </div>
  </div>
<?php get_footer();
