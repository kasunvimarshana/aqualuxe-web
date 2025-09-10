<?php
/**
 * Template part for displaying team member posts.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Aqualuxe
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('group text-center'); ?>>
    <a href="<?php the_permalink(); ?>" class="block">
        <header class="entry-header mb-4">
            <?php if (has_post_thumbnail()) : ?>
                <div class="w-48 h-48 mx-auto rounded-full overflow-hidden shadow-lg transition-shadow duration-300 group-hover:shadow-xl">
                    <?php the_post_thumbnail('medium', ['class' => 'w-full h-full object-cover']); ?>
                </div>
            <?php else : ?>
                <div class="w-48 h-48 mx-auto rounded-full bg-gray-200 flex items-center justify-center">
                    <span class="text-gray-500 text-4xl"><?php echo esc_html(get_the_title()[0]); ?></span>
                </div>
            <?php endif; ?>
        </header><!-- .entry-header -->

        <div class="entry-content">
            <?php
            the_title(sprintf('<h2 class="entry-title text-xl font-semibold mb-1"><a href="%s" rel="bookmark" class="hover:text-blue-600">', esc_url(get_permalink())), '</a></h2>');

            $role = get_post_meta(get_the_ID(), 'role', true);
            if ($role) :
            ?>
                <p class="text-md text-gray-600"><?php echo esc_html($role); ?></p>
            <?php endif; ?>
        </div><!-- .entry-content -->
    </a>
</article><!-- #post-<?php the_ID(); ?> -->
