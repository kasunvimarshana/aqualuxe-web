<section class="blog-highlights-section py-16">
    <div class="container mx-auto text-center">
        <h2 class="text-3xl font-bold mb-8">From Our Blog</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <?php
            $args = array(
                'post_type' => 'post',
                'posts_per_page' => 3,
                'ignore_sticky_posts' => 1,
            );
            $blog_query = new WP_Query( $args );
            if ( $blog_query->have_posts() ) :
                while ( $blog_query->have_posts() ) : $blog_query->the_post();
                    ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class('bg-white rounded-lg shadow-md overflow-hidden'); ?>>
                        <?php if ( has_post_thumbnail() ) : ?>
                            <a href="<?php the_permalink(); ?>">
                                <?php the_post_thumbnail('medium_large', ['class' => 'w-full h-48 object-cover']); ?>
                            </a>
                        <?php endif; ?>
                        <div class="p-6">
                            <h3 class="text-xl font-bold mb-2"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                            <div class="text-gray-600">
                                <?php the_excerpt(); ?>
                            </div>
                        </div>
                    </article>
                    <?php
                endwhile;
            endif;
            wp_reset_postdata();
            ?>
        </div>
    </div>
</section>
