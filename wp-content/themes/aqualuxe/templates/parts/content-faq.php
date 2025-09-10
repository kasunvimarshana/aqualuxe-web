<?php
/**
 * Template part for displaying a single FAQ item.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */
?>

<div id="post-<?php the_ID(); ?>" <?php post_class('faq-item border-b border-gray-200'); ?>>
    <div class="faq-question cursor-pointer py-4 flex justify-between items-center">
        <h2 class="text-lg font-semibold text-gray-800"><?php the_title(); ?></h2>
        <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
    </div>
    <div class="faq-answer pb-4 text-gray-600">
        <?php the_content(); ?>
    </div>
</div>
