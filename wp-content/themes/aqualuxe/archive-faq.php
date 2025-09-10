<?php
/**
 * The template for displaying archive pages for FAQs.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

get_header();
?>

<main id="primary" class="site-main py-12">
    <div class="container mx-auto px-4">
        <header class="page-header mb-8 text-center">
            <h1 class="page-title text-4xl font-extrabold text-gray-800 tracking-tight"><?php post_type_archive_title(); ?></h1>
            <?php
            the_archive_description( '<div class="archive-description text-lg text-gray-600 mt-2">', '</div>' );
            ?>
        </header><!-- .page-header -->

        <?php if ( have_posts() ) : ?>
            <div class="faq-accordion max-w-3xl mx-auto space-y-4">
                <?php
                /* Start the Loop */
                while ( have_posts() ) :
                    the_post();

                    /*
                     * Include the Post-Type-specific template for the content.
                     */
                    get_template_part( 'templates/parts/content', 'faq' );

                endwhile;
                ?>
            </div>
        <?php
            the_posts_navigation();

        else :

            get_template_part( 'templates/parts/content', 'none' );

        endif;
        ?>
    </div><!-- .container -->
</main><!-- #main -->

<script>
document.addEventListener('DOMContentLoaded', function() {
    const faqItems = document.querySelectorAll('.faq-item');

    faqItems.forEach(item => {
        const question = item.querySelector('.faq-question');
        const answer = item.querySelector('.faq-answer');
        const icon = question.querySelector('svg');

        question.addEventListener('click', () => {
            const isOpen = answer.classList.contains('active');

            // Close all other answers
            faqItems.forEach(otherItem => {
                otherItem.querySelector('.faq-answer').classList.remove('active');
                otherItem.querySelector('.faq-answer').style.maxHeight = null;
                otherItem.querySelector('.faq-question svg').classList.remove('rotate-180');
            });

            // Open or close the clicked answer
            if (!isOpen) {
                answer.classList.add('active');
                answer.style.maxHeight = answer.scrollHeight + 'px';
                icon.classList.add('rotate-180');
            }
        });
    });
});
</script>

<style>
    .faq-answer {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s ease-out;
    }
    .faq-answer.active {
        transition: max-height 0.5s ease-in;
    }
    .faq-question svg {
        transition: transform 0.3s ease;
    }
</style>

<?php
get_footer();
