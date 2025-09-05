<?php
/**
 * The template for displaying a single Service post type.
 */

\get_header();
?>

<div id="primary" class="content-area">
    <main id="main" class="site-main">

        <?php
        while (have_posts()) :
            the_post();
            ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class('aqualuxe-service-single container mx-auto py-12 px-4'); ?>>
                <header class="entry-header text-center mb-12">
                    <?php the_title('<h1 class="entry-title text-4xl md:text-5xl font-extrabold text-gray-900 dark:text-white">', '</h1>'); ?>
                </header>

                <?php if (has_post_thumbnail()) : ?>
                    <div class="post-thumbnail mb-12 rounded-lg overflow-hidden shadow-xl">
                        <?php the_post_thumbnail('full', ['class' => 'w-full h-auto']); ?>
                    </div>
                <?php endif; ?>

                <div class="entry-content prose lg:prose-xl max-w-none dark:prose-invert">
                    <?php
                    the_content();

                    // Example of how to add a contact/booking form link
                    $booking_page_url = '/contact-us'; // Replace with your actual booking page URL
                    echo '<div class="mt-12 text-center">';
                    echo '<a href="' . esc_url($booking_page_url) . '" class="aqualuxe-btn-primary text-lg">' . __('Book a Consultation', 'aqualuxe') . '</a>';
                    echo '</div>';
                    ?>
                </div>

            </article>

        <?php endwhile; // End of the loop. ?>

    </main>
</div>

<?php
\get_footer();
