<?php
/**
 * Template Name: Services Page
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

get_header();
?>

<div class="container">
    <div class="row no-sidebar">
        <main id="primary" class="site-main">
            <?php
            while (have_posts()) :
                the_post();
                ?>
                
                <article id="post-<?php the_ID(); ?>" <?php post_class('aqualuxe-services-page'); ?>>
                    <header class="aqualuxe-services-header">
                        <?php the_title('<h1 class="aqualuxe-services-title">', '</h1>'); ?>
                        
                        <?php if (has_excerpt()) : ?>
                            <div class="aqualuxe-services-excerpt">
                                <?php the_excerpt(); ?>
                            </div>
                        <?php endif; ?>
                    </header>

                    <?php if (has_post_thumbnail()) : ?>
                        <div class="aqualuxe-services-thumbnail">
                            <?php the_post_thumbnail('full'); ?>
                        </div>
                    <?php endif; ?>

                    <div class="aqualuxe-services-content">
                        <?php the_content(); ?>
                    </div>
                </article>
                
                <?php
                // Get ACF fields if available
                if (function_exists('get_field')) :
                    // Services section
                    $services = get_field('services');
                    
                    if ($services) :
                        ?>
                        <section class="aqualuxe-services-list">
                            <div class="aqualuxe-services-grid">
                                <?php foreach ($services as $service) : ?>
                                    <div class="aqualuxe-service-item">
                                        <?php if (!empty($service['icon'])) : ?>
                                            <div class="aqualuxe-service-icon">
                                                <img src="<?php echo esc_url($service['icon']['url']); ?>" alt="<?php echo esc_attr($service['title']); ?>">
                                            </div>
                                        <?php endif; ?>
                                        
                                        <div class="aqualuxe-service-content">
                                            <h2 class="aqualuxe-service-title"><?php echo esc_html($service['title']); ?></h2>
                                            
                                            <?php if (!empty($service['description'])) : ?>
                                                <div class="aqualuxe-service-description">
                                                    <?php echo wp_kses_post($service['description']); ?>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <?php if (!empty($service['price'])) : ?>
                                                <div class="aqualuxe-service-price">
                                                    <?php echo esc_html($service['price']); ?>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <?php if (!empty($service['link'])) : ?>
                                                <div class="aqualuxe-service-link">
                                                    <a href="<?php echo esc_url($service['link']['url']); ?>" class="button">
                                                        <?php echo esc_html($service['link']['title'] ? $service['link']['title'] : __('Learn More', 'aqualuxe')); ?>
                                                    </a>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </section>
                        <?php
                    endif;
                    
                    // Process section
                    $process_title = get_field('process_title');
                    $process_description = get_field('process_description');
                    $process_steps = get_field('process_steps');
                    
                    if ($process_steps) :
                        ?>
                        <section class="aqualuxe-services-process">
                            <div class="aqualuxe-services-section-inner">
                                <div class="aqualuxe-services-process-header">
                                    <h2><?php echo esc_html($process_title ? $process_title : __('Our Process', 'aqualuxe')); ?></h2>
                                    
                                    <?php if ($process_description) : ?>
                                        <div class="aqualuxe-services-process-description">
                                            <?php echo wp_kses_post($process_description); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="aqualuxe-services-process-steps">
                                    <?php foreach ($process_steps as $index => $step) : ?>
                                        <div class="aqualuxe-services-process-step">
                                            <div class="aqualuxe-services-process-step-number">
                                                <?php echo esc_html($index + 1); ?>
                                            </div>
                                            
                                            <div class="aqualuxe-services-process-step-content">
                                                <h3 class="aqualuxe-services-process-step-title"><?php echo esc_html($step['title']); ?></h3>
                                                
                                                <?php if (!empty($step['description'])) : ?>
                                                    <div class="aqualuxe-services-process-step-description">
                                                        <?php echo wp_kses_post($step['description']); ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </section>
                        <?php
                    endif;
                    
                    // Testimonials section
                    $testimonials_title = get_field('testimonials_title');
                    $testimonials = get_field('testimonials');
                    
                    if ($testimonials) :
                        ?>
                        <section class="aqualuxe-services-testimonials">
                            <div class="aqualuxe-services-section-inner">
                                <div class="aqualuxe-services-testimonials-header">
                                    <h2><?php echo esc_html($testimonials_title ? $testimonials_title : __('What Our Clients Say', 'aqualuxe')); ?></h2>
                                </div>
                                
                                <div class="aqualuxe-services-testimonials-list">
                                    <?php foreach ($testimonials as $testimonial) : ?>
                                        <div class="aqualuxe-services-testimonial">
                                            <div class="aqualuxe-services-testimonial-content">
                                                <?php echo wp_kses_post($testimonial['content']); ?>
                                            </div>
                                            
                                            <div class="aqualuxe-services-testimonial-author">
                                                <?php if (!empty($testimonial['photo'])) : ?>
                                                    <div class="aqualuxe-services-testimonial-author-photo">
                                                        <img src="<?php echo esc_url($testimonial['photo']['url']); ?>" alt="<?php echo esc_attr($testimonial['name']); ?>">
                                                    </div>
                                                <?php endif; ?>
                                                
                                                <div class="aqualuxe-services-testimonial-author-info">
                                                    <div class="aqualuxe-services-testimonial-author-name">
                                                        <?php echo esc_html($testimonial['name']); ?>
                                                    </div>
                                                    
                                                    <?php if (!empty($testimonial['position'])) : ?>
                                                        <div class="aqualuxe-services-testimonial-author-position">
                                                            <?php echo esc_html($testimonial['position']); ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </section>
                        <?php
                    endif;
                    
                    // CTA section
                    $cta_title = get_field('cta_title');
                    $cta_description = get_field('cta_description');
                    $cta_button = get_field('cta_button');
                    
                    if ($cta_title || $cta_description) :
                        ?>
                        <section class="aqualuxe-services-cta">
                            <div class="aqualuxe-services-section-inner">
                                <div class="aqualuxe-services-cta-content">
                                    <?php if ($cta_title) : ?>
                                        <h2 class="aqualuxe-services-cta-title"><?php echo esc_html($cta_title); ?></h2>
                                    <?php endif; ?>
                                    
                                    <?php if ($cta_description) : ?>
                                        <div class="aqualuxe-services-cta-description">
                                            <?php echo wp_kses_post($cta_description); ?>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if ($cta_button) : ?>
                                        <div class="aqualuxe-services-cta-button">
                                            <a href="<?php echo esc_url($cta_button['url']); ?>" class="button button-primary">
                                                <?php echo esc_html($cta_button['title']); ?>
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </section>
                        <?php
                    endif;
                else :
                    // Fallback if ACF is not available
                    ?>
                    <section class="aqualuxe-services-list">
                        <div class="aqualuxe-services-grid">
                            <div class="aqualuxe-service-item">
                                <div class="aqualuxe-service-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="64" height="64"><path d="M12 3L1 9l4 2.18v6L12 21l7-3.82v-6l2-1.09V17h2V9L12 3zm6.82 6L12 12.72 5.18 9 12 5.28 18.82 9zM17 15.99l-5 2.73-5-2.73v-3.72L12 15l5-2.73v3.72z"></path></svg>
                                </div>
                                
                                <div class="aqualuxe-service-content">
                                    <h2 class="aqualuxe-service-title"><?php esc_html_e('Aquarium Design', 'aqualuxe'); ?></h2>
                                    
                                    <div class="aqualuxe-service-description">
                                        <p><?php esc_html_e('Custom aquarium design for homes, offices, and commercial spaces.', 'aqualuxe'); ?></p>
                                    </div>
                                    
                                    <div class="aqualuxe-service-link">
                                        <a href="#" class="button"><?php esc_html_e('Learn More', 'aqualuxe'); ?></a>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="aqualuxe-service-item">
                                <div class="aqualuxe-service-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="64" height="64"><path d="M19.35 10.04C18.67 6.59 15.64 4 12 4 9.11 4 6.6 5.64 5.35 8.04 2.34 8.36 0 10.91 0 14c0 3.31 2.69 6 6 6h13c2.76 0 5-2.24 5-5 0-2.64-2.05-4.78-4.65-4.96zM19 18H6c-2.21 0-4-1.79-4-4 0-2.05 1.53-3.76 3.56-3.97l1.07-.11.5-.95C8.08 7.14 9.94 6 12 6c2.62 0 4.88 1.86 5.39 4.43l.3 1.5 1.53.11c1.56.1 2.78 1.41 2.78 2.96 0 1.65-1.35 3-3 3zM8 13h2.55v3h2.9v-3H16l-4-4z"></path></svg>
                                </div>
                                
                                <div class="aqualuxe-service-content">
                                    <h2 class="aqualuxe-service-title"><?php esc_html_e('Maintenance', 'aqualuxe'); ?></h2>
                                    
                                    <div class="aqualuxe-service-description">
                                        <p><?php esc_html_e('Regular maintenance services to keep your aquarium in perfect condition.', 'aqualuxe'); ?></p>
                                    </div>
                                    
                                    <div class="aqualuxe-service-link">
                                        <a href="#" class="button"><?php esc_html_e('Learn More', 'aqualuxe'); ?></a>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="aqualuxe-service-item">
                                <div class="aqualuxe-service-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="64" height="64"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 17h-2v-2h2v2zm2.07-7.75l-.9.92C13.45 12.9 13 13.5 13 15h-2v-.5c0-1.1.45-2.1 1.17-2.83l1.24-1.26c.37-.36.59-.86.59-1.41 0-1.1-.9-2-2-2s-2 .9-2 2H8c0-2.21 1.79-4 4-4s4 1.79 4 4c0 .88-.36 1.68-.93 2.25z"></path></svg>
                                </div>
                                
                                <div class="aqualuxe-service-content">
                                    <h2 class="aqualuxe-service-title"><?php esc_html_e('Consultation', 'aqualuxe'); ?></h2>
                                    
                                    <div class="aqualuxe-service-description">
                                        <p><?php esc_html_e('Expert consultation for aquarium setup, fish selection, and care.', 'aqualuxe'); ?></p>
                                    </div>
                                    
                                    <div class="aqualuxe-service-link">
                                        <a href="#" class="button"><?php esc_html_e('Learn More', 'aqualuxe'); ?></a>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="aqualuxe-service-item">
                                <div class="aqualuxe-service-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="64" height="64"><path d="M12 7V3H2v18h20V7H12zM6 19H4v-2h2v2zm0-4H4v-2h2v2zm0-4H4V9h2v2zm0-4H4V5h2v2zm4 12H8v-2h2v2zm0-4H8v-2h2v2zm0-4H8V9h2v2zm0-4H8V5h2v2zm10 12h-8v-2h2v-2h-2v-2h2v-2h-2V9h8v10zm-2-8h-2v2h2v-2zm0 4h-2v2h2v-2z"></path></svg>
                                </div>
                                
                                <div class="aqualuxe-service-content">
                                    <h2 class="aqualuxe-service-title"><?php esc_html_e('Installation', 'aqualuxe'); ?></h2>
                                    
                                    <div class="aqualuxe-service-description">
                                        <p><?php esc_html_e('Professional installation of aquariums and related equipment.', 'aqualuxe'); ?></p>
                                    </div>
                                    
                                    <div class="aqualuxe-service-link">
                                        <a href="#" class="button"><?php esc_html_e('Learn More', 'aqualuxe'); ?></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                    <?php
                endif;
                
                // If comments are open or we have at least one comment, load up the comment template.
                if (comments_open() || get_comments_number()) :
                    comments_template();
                endif;
            endwhile; // End of the loop.
            ?>
        </main><!-- #main -->
    </div>
</div>

<?php
get_footer();