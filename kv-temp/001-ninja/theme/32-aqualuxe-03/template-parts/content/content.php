<?php
/**
 * Template part for displaying posts
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php do_action( 'aqualuxe_entry_header' ); ?>

	<?php aqualuxe_post_thumbnail(); ?>

	<?php do_action( 'aqualuxe_entry_content' ); ?>

	<?php do_action( 'aqualuxe_entry_footer' ); ?>
</article><!-- #post-<?php the_ID(); ?> -->