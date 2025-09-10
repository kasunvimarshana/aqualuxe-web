<?php
/**
 * Template part for displaying a single testimonial in archive pages.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('bg-white rounded-xl shadow-lg overflow-hidden max-w-2xl mx-auto'); ?>>
    <div class="p-8">
        <div class="testimonial-content">
            <blockquote class="text-xl text-gray-700 leading-relaxed">
                <p>"<?php echo get_the_content(); ?>"</p>
            </blockquote>
        </div>
        <footer class="mt-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <?php if ( has_post_thumbnail() ) : ?>
                        <?php the_post_thumbnail( 'thumbnail', array( 'class' => 'h-12 w-12 rounded-full' ) ); ?>
                    <?php else : ?>
                        <div class="h-12 w-12 rounded-full bg-gray-200 flex items-center justify-center">
                            <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="ml-4">
                    <cite class="font-bold text-gray-900 not-italic"><?php the_title(); ?></cite>
                    <?php
                    $company = get_post_meta( get_the_ID(), 'testimonial_company', true );
                    if ( ! empty( $company ) ) : ?>
                        <div class="text-gray-600"><?php echo esc_html( $company ); ?></div>
                    <?php endif; ?>
                </div>
            </div>
        </footer>
    </div>
</article><!-- #post-<?php the_ID(); ?> -->
