<?php get_header(); ?>

<main class="page-content">
    
    <?php while (have_posts()) : the_post(); ?>
        
        <article id="page-<?php the_ID(); ?>" <?php post_class('single-page'); ?>>
            
            <!-- Page Header -->
            <header class="page-header py-16 lg:py-24 bg-gradient-to-br from-primary-600 via-secondary-500 to-aqua-400 text-white">
                <div class="container mx-auto px-4">
                    <div class="max-w-4xl mx-auto text-center">
                        
                        <h1 class="page-title text-4xl lg:text-6xl font-bold font-secondary mb-6" data-aos="fade-up">
                            <?php the_title(); ?>
                        </h1>
                        
                        <?php if (get_the_excerpt()) : ?>
                            <p class="page-excerpt text-xl lg:text-2xl text-gray-100 font-light leading-relaxed max-w-3xl mx-auto" data-aos="fade-up" data-aos-delay="200">
                                <?php echo wp_kses_post(get_the_excerpt()); ?>
                            </p>
                        <?php endif; ?>
                        
                        <!-- Breadcrumbs -->
                        <nav class="breadcrumbs mt-8" data-aos="fade-up" data-aos-delay="400">
                            <ol class="flex items-center justify-center space-x-2 text-gray-200">
                                <li>
                                    <a href="<?php echo esc_url(home_url('/')); ?>" 
                                       class="hover:text-white transition-colors">
                                        <?php esc_html_e('Home', 'aqualuxe'); ?>
                                    </a>
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-4 h-4 mx-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="text-accent-400"><?php the_title(); ?></span>
                                </li>
                            </ol>
                        </nav>
                        
                    </div>
                </div>
            </header>

            <!-- Featured Image -->
            <?php if (has_post_thumbnail()) : ?>
                <div class="featured-image -mt-16 relative z-10 mb-16" data-aos="fade-up">
                    <div class="container mx-auto px-4">
                        <div class="max-w-5xl mx-auto">
                            <div class="image-container relative rounded-2xl overflow-hidden shadow-xl">
                                <?php the_post_thumbnail('large', array(
                                    'class' => 'w-full h-auto object-cover',
                                    'alt' => get_the_title()
                                )); ?>
                                <div class="image-overlay absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Page Content -->
            <div class="page-main-content py-16 lg:py-24">
                <div class="container mx-auto px-4">
                    <div class="max-w-4xl mx-auto">
                        
                        <div class="prose prose-lg prose-gray max-w-none" data-aos="fade-up">
                            <?php
                            the_content();
                            
                            wp_link_pages(array(
                                'before' => '<div class="page-links mt-8 p-6 bg-gray-50 rounded-lg">',
                                'after' => '</div>',
                                'link_before' => '<span class="page-number inline-block bg-primary-600 text-white px-3 py-1 rounded mr-2">',
                                'link_after' => '</span>',
                            ));
                            ?>
                        </div>
                        
                    </div>
                </div>
            </div>

            <!-- Child Pages -->
            <?php
            $child_pages = get_children(array(
                'post_parent' => get_the_ID(),
                'post_type' => 'page',
                'post_status' => 'publish',
                'orderby' => 'menu_order',
                'order' => 'ASC'
            ));
            
            if ($child_pages) :
                ?>
                <section class="child-pages py-16 bg-gray-50">
                    <div class="container mx-auto px-4">
                        <div class="max-w-6xl mx-auto">
                            
                            <h2 class="text-3xl lg:text-4xl font-bold font-secondary text-gray-900 mb-12 text-center" data-aos="fade-up">
                                <?php esc_html_e('Related Pages', 'aqualuxe'); ?>
                            </h2>
                            
                            <div class="child-pages-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                                <?php
                                $delay = 0;
                                foreach ($child_pages as $child_page) :
                                    ?>
                                    <div class="child-page-card" data-aos="fade-up" data-aos-delay="<?php echo $delay; ?>">
                                        <a href="<?php echo esc_url(get_permalink($child_page->ID)); ?>" class="block group">
                                            <div class="card bg-white rounded-2xl overflow-hidden shadow-soft hover:shadow-lg transition-all duration-300 transform group-hover:-translate-y-1">
                                                
                                                <?php if (has_post_thumbnail($child_page->ID)) : ?>
                                                    <div class="page-image relative h-48 overflow-hidden">
                                                        <?php echo get_the_post_thumbnail($child_page->ID, 'medium', array(
                                                            'class' => 'w-full h-full object-cover group-hover:scale-105 transition-transform duration-300'
                                                        )); ?>
                                                        <div class="image-overlay absolute inset-0 bg-gradient-to-t from-black/30 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                                    </div>
                                                <?php else : ?>
                                                    <div class="page-image-placeholder h-48 bg-gradient-to-br from-primary-500 to-secondary-400 flex items-center justify-center">
                                                        <svg class="w-16 h-16 text-white opacity-50" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path>
                                                        </svg>
                                                    </div>
                                                <?php endif; ?>
                                                
                                                <div class="page-content p-6">
                                                    <h3 class="page-title text-lg font-semibold text-gray-900 mb-3 group-hover:text-primary-600 transition-colors line-clamp-2">
                                                        <?php echo esc_html($child_page->post_title); ?>
                                                    </h3>
                                                    
                                                    <?php if ($child_page->post_excerpt) : ?>
                                                        <p class="page-excerpt text-gray-600 text-sm line-clamp-3">
                                                            <?php echo wp_kses_post($child_page->post_excerpt); ?>
                                                        </p>
                                                    <?php endif; ?>
                                                </div>
                                                
                                            </div>
                                        </a>
                                    </div>
                                    <?php
                                    $delay += 100;
                                endforeach;
                                ?>
                            </div>
                            
                        </div>
                    </div>
                </section>
                <?php
            endif;
            ?>

            <!-- Contact Form (for contact page) -->
            <?php if (is_page('contact') || is_page('contact-us')) : ?>
                <section class="contact-form-section py-16">
                    <div class="container mx-auto px-4">
                        <div class="max-w-4xl mx-auto">
                            
                            <div class="contact-form-wrapper bg-white p-8 lg:p-12 rounded-2xl shadow-lg" data-aos="fade-up">
                                <h2 class="text-2xl lg:text-3xl font-bold font-secondary text-gray-900 mb-8 text-center">
                                    <?php esc_html_e('Get in Touch', 'aqualuxe'); ?>
                                </h2>
                                
                                <form class="contact-form" method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                                    <input type="hidden" name="action" value="aqualuxe_contact_form">
                                    <?php wp_nonce_field('aqualuxe_contact_form', 'contact_nonce'); ?>
                                    
                                    <div class="form-grid grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                        <div class="form-group">
                                            <label for="contact_name" class="block text-sm font-medium text-gray-700 mb-2">
                                                <?php esc_html_e('Full Name', 'aqualuxe'); ?> *
                                            </label>
                                            <input type="text" 
                                                   id="contact_name" 
                                                   name="contact_name" 
                                                   required
                                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-colors">
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="contact_email" class="block text-sm font-medium text-gray-700 mb-2">
                                                <?php esc_html_e('Email Address', 'aqualuxe'); ?> *
                                            </label>
                                            <input type="email" 
                                                   id="contact_email" 
                                                   name="contact_email" 
                                                   required
                                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-colors">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group mb-6">
                                        <label for="contact_subject" class="block text-sm font-medium text-gray-700 mb-2">
                                            <?php esc_html_e('Subject', 'aqualuxe'); ?> *
                                        </label>
                                        <input type="text" 
                                               id="contact_subject" 
                                               name="contact_subject" 
                                               required
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-colors">
                                    </div>
                                    
                                    <div class="form-group mb-6">
                                        <label for="contact_message" class="block text-sm font-medium text-gray-700 mb-2">
                                            <?php esc_html_e('Message', 'aqualuxe'); ?> *
                                        </label>
                                        <textarea id="contact_message" 
                                                  name="contact_message" 
                                                  rows="6" 
                                                  required
                                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-colors resize-vertical"></textarea>
                                    </div>
                                    
                                    <div class="form-group mb-6">
                                        <label class="flex items-start space-x-3">
                                            <input type="checkbox" 
                                                   name="contact_consent" 
                                                   required
                                                   class="mt-1 w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                                            <span class="text-sm text-gray-600">
                                                <?php esc_html_e('I agree to the', 'aqualuxe'); ?> 
                                                <a href="<?php echo esc_url(get_permalink(get_page_by_path('privacy-policy'))); ?>" 
                                                   class="text-primary-600 hover:text-primary-700 underline">
                                                    <?php esc_html_e('Privacy Policy', 'aqualuxe'); ?>
                                                </a> 
                                                <?php esc_html_e('and consent to my data being processed.', 'aqualuxe'); ?>
                                            </span>
                                        </label>
                                    </div>
                                    
                                    <div class="form-submit text-center">
                                        <button type="submit" 
                                                class="btn btn-primary bg-primary-600 hover:bg-primary-700 text-white px-8 py-4 rounded-full transition-colors focus:outline-none focus:ring-4 focus:ring-primary-300">
                                            <?php esc_html_e('Send Message', 'aqualuxe'); ?>
                                        </button>
                                    </div>
                                </form>
                            </div>
                            
                        </div>
                    </div>
                </section>
            <?php endif; ?>

        </article>
        
    <?php endwhile; ?>

    <!-- Comments Section -->
    <?php if (comments_open() || get_comments_number()) : ?>
        <section class="comments-section py-16 bg-gray-50">
            <div class="container mx-auto px-4">
                <div class="max-w-4xl mx-auto" data-aos="fade-up">
                    <?php comments_template(); ?>
                </div>
            </div>
        </section>
    <?php endif; ?>

</main>

<?php get_footer(); ?>
