<?php
/**
 * The template for displaying all single team members.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Aqualuxe
 */

get_header();
?>

<div id="primary" class="content-area">
    <main id="main" class="site-main py-12">

        <div class="container mx-auto px-4">
            <?php
            while (have_posts()) :
                the_post();
            ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class('bg-white shadow-lg rounded-lg overflow-hidden'); ?>>
                    <div class="md:flex">
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="md:w-1/3 p-8">
                                <?php the_post_thumbnail('large', ['class' => 'w-full h-auto rounded-full aspect-square object-cover']); ?>
                            </div>
                        <?php endif; ?>

                        <div class="md:w-2/3 p-8">
                             <header class="entry-header mb-4">
                                <?php the_title('<h1 class="entry-title text-4xl font-bold text-gray-800">', '</h1>'); ?>
                                <?php
                                $role = get_post_meta(get_the_ID(), 'role', true);
                                if ($role) :
                                ?>
                                    <p class="text-2xl text-blue-600 font-semibold mt-1"><?php echo esc_html($role); ?></p>
                                <?php endif; ?>
                            </header><!-- .entry-header -->

                            <div class="entry-content prose lg:prose-xl max-w-none">
                                <?php
                                the_content();
                                ?>
                            </div><!-- .entry-content -->

                            <footer class="entry-footer mt-6">
                                <?php
                                // You can add social media links here if they are stored as post meta
                                ?>
                            </footer><!-- .entry-footer -->
                        </div>
                    </div>
                </article><!-- #post-<?php the_ID(); ?> -->

            <?php
            endwhile; // End of the loop.
            ?>
        </div><!-- .container -->

    </main><!-- #main -->
</div><!-- #primary -->

<?php
get_footer();
