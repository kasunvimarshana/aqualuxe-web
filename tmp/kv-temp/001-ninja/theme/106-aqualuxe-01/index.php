<?php
/** Index fallback */
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_template_part( 'templates/header' );
if ( have_posts() ) :
	while ( have_posts() ) : the_post();
		get_template_part( 'templates/partials/content', get_post_type() );
	endwhile;
	get_template_part( 'templates/partials/pagination' );
else:
	get_template_part( 'templates/partials/empty' );
endif;
get_template_part( 'templates/footer' );
