<?php
/**
 * Template Name: About Page
 *
 * The template for displaying the about page.
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

get_header();
?>

<main id="primary" class="site-main">
    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <?php if (has_post_thumbnail()) : ?>
            <div class="page-header page-header--with-image" style="background-image: url('<?php echo esc_url(get_the_post_thumbnail_url(get_the_ID(), 'full')); ?>');">
                <div class="container">
                    <div class="page-header__content">
                        <h1 class="page-title"><?php the_title(); ?></h1>
                        <?php if (function_exists('yoast_breadcrumb')) : ?>
                            <?php yoast_breadcrumb('<div class="breadcrumbs">', '</div>'); ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php else : ?>
            <div class="page-header">
                <div class="container">
                    <h1 class="page-title"><?php the_title(); ?></h1>
                    <?php if (function_exists('yoast_breadcrumb')) : ?>
                        <?php yoast_breadcrumb('<div class="breadcrumbs">', '</div>'); ?>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>

        <div class="about-intro">
            <div class="container">
                <div class="about-intro__inner">
                    <div class="about-intro__content">
                        <h2 class="about-intro__title"><?php esc_html_e('Welcome to AquaLuxe', 'aqualuxe'); ?></h2>
                        <p class="about-intro__text"><?php esc_html_e('AquaLuxe is a premium aquatic retail brand dedicated to providing high-quality products for aquarium enthusiasts and professionals. With years of experience and a passion for aquatic life, we offer a curated selection of luxury aquariums, advanced filtration systems, premium foods, and expert-designed accessories.', 'aqualuxe'); ?></p>
                        <p class="about-intro__text"><?php esc_html_e('Our mission is to enhance the beauty and health of aquatic environments through innovative products and exceptional customer service. We believe that every aquarium should be a stunning, thriving ecosystem that brings joy and tranquility to its owners.', 'aqualuxe'); ?></p>
                    </div>
                    <div class="about-intro__image">
                        <img src="<?php echo esc_url(get_theme_file_uri('/assets/dist/images/placeholder/about-intro.jpg')); ?>" alt="<?php esc_attr_e('About AquaLuxe', 'aqualuxe'); ?>">
                    </div>
                </div>
            </div>
        </div>

        <div class="about-values">
            <div class="container">
                <div class="section-header">
                    <h2 class="section-title"><?php esc_html_e('Our Core Values', 'aqualuxe'); ?></h2>
                    <p class="section-description"><?php esc_html_e('The principles that guide everything we do', 'aqualuxe'); ?></p>
                </div>

                <div class="values-grid">
                    <div class="value-card">
                        <div class="value-card__icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M20.083 15.2l1.202.721a.5.5 0 0 1 0 .858l-8.77 5.262a1 1 0 0 1-1.03 0l-8.77-5.262a.5.5 0 0 1 0-.858l1.202-.721L12 20.05l8.083-4.85zm0-4.7l1.202.721a.5.5 0 0 1 0 .858L12 17.65l-9.285-5.571a.5.5 0 0 1 0-.858l1.202-.721L12 15.35l8.083-4.85zm-7.569-9.191l8.771 5.262a.5.5 0 0 1 0 .858L12 13 2.715 7.429a.5.5 0 0 1 0-.858l8.77-5.262a1 1 0 0 1 1.03 0zM12 3.332L5.887 7 12 10.668 18.113 7 12 3.332z"/></svg>
                        </div>
                        <h3 class="value-card__title"><?php esc_html_e('Quality', 'aqualuxe'); ?></h3>
                        <p class="value-card__text"><?php esc_html_e('We never compromise on quality. Every product in our collection is carefully selected and tested to ensure it meets our high standards.', 'aqualuxe'); ?></p>
                    </div>

                    <div class="value-card">
                        <div class="value-card__icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M7 5V2a1 1 0 0 1 1-1h8a1 1 0 0 1 1 1v3h4a1 1 0 0 1 1 1v14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1h4zm2 8H4v6h16v-6h-5v3H9v-3zm11-6H4v4h5V9h6v2h5V7zm-9 4v3h2v-3h-2zM9 3v2h6V3H9z"/></svg>
                        </div>
                        <h3 class="value-card__title"><?php esc_html_e('Innovation', 'aqualuxe'); ?></h3>
                        <p class="value-card__text"><?php esc_html_e('We constantly seek innovative solutions and cutting-edge technologies to enhance the aquatic experience for our customers.', 'aqualuxe'); ?></p>
                    </div>

                    <div class="value-card">
                        <div class="value-card__icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M17.841 15.659l.176.177.178-.177a2.25 2.25 0 0 1 3.182 3.182l-3.36 3.359-3.358-3.359a2.25 2.25 0 0 1 3.182-3.182zM12 14v2a6 6 0 0 0-6 6H4a8 8 0 0 1 7.75-7.996L12 14zm0-13c3.315 0 6 2.685 6 6a5.998 5.998 0 0 1-5.775 5.996L12 13c-3.315 0-6-2.685-6-6a5.998 5.998 0 0 1 5.775-5.996L12 1zm0 2C9.79 3 8 4.79 8 7s1.79 4 4 4 4-1.79 4-4-1.79-4-4-4z"/></svg>
                        </div>
                        <h3 class="value-card__title"><?php esc_html_e('Sustainability', 'aqualuxe'); ?></h3>
                        <p class="value-card__text"><?php esc_html_e('We are committed to environmental responsibility and sustainable practices in all aspects of our business.', 'aqualuxe'); ?></p>
                    </div>

                    <div class="value-card">
                        <div class="value-card__icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M12 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10zm0-2a8 8 0 1 0 0-16 8 8 0 0 0 0 16zm-1-5h2v2h-2v-2zm2-1.645V14h-2v-1.5a1 1 0 0 1 1-1 1.5 1.5 0 1 0-1.471-1.794l-1.962-.393A3.501 3.501 0 1 1 13 13.355z"/></svg>
                        </div>
                        <h3 class="value-card__title"><?php esc_html_e('Expertise', 'aqualuxe'); ?></h3>
                        <p class="value-card__text"><?php esc_html_e('Our team consists of passionate aquatic experts who provide knowledgeable advice and exceptional service.', 'aqualuxe'); ?></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="about-story">
            <div class="container">
                <div class="about-story__inner">
                    <div class="about-story__image">
                        <img src="<?php echo esc_url(get_theme_file_uri('/assets/dist/images/placeholder/about-story.jpg')); ?>" alt="<?php esc_attr_e('Our Story', 'aqualuxe'); ?>">
                    </div>
                    <div class="about-story__content">
                        <h2 class="about-story__title"><?php esc_html_e('Our Story', 'aqualuxe'); ?></h2>
                        <p class="about-story__text"><?php esc_html_e('AquaLuxe began in 2010 when our founder, a lifelong aquarium enthusiast, recognized the need for premium aquatic products that combined functionality with elegant design. What started as a small specialty shop has grown into a leading provider of luxury aquatic supplies.', 'aqualuxe'); ?></p>
                        <p class="about-story__text"><?php esc_html_e('Over the years, we\'ve expanded our collection to include custom-designed aquariums, state-of-the-art filtration systems, and exclusive accessories sourced from around the world. Our commitment to quality and customer satisfaction has earned us a loyal following of hobbyists and professionals alike.', 'aqualuxe'); ?></p>
                        <p class="about-story__text"><?php esc_html_e('Today, AquaLuxe continues to innovate and set new standards in the aquatic industry, always with the goal of helping our customers create and maintain beautiful, thriving aquatic environments.', 'aqualuxe'); ?></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="about-team">
            <div class="container">
                <div class="section-header">
                    <h2 class="section-title"><?php esc_html_e('Meet Our Team', 'aqualuxe'); ?></h2>
                    <p class="section-description"><?php esc_html_e('The experts behind AquaLuxe', 'aqualuxe'); ?></p>
                </div>

                <div class="team-grid">
                    <div class="team-member">
                        <div class="team-member__image">
                            <img src="<?php echo esc_url(get_theme_file_uri('/assets/dist/images/placeholder/team-1.jpg')); ?>" alt="<?php esc_attr_e('Team Member', 'aqualuxe'); ?>">
                        </div>
                        <div class="team-member__content">
                            <h3 class="team-member__name"><?php esc_html_e('Robert Johnson', 'aqualuxe'); ?></h3>
                            <p class="team-member__position"><?php esc_html_e('Founder & CEO', 'aqualuxe'); ?></p>
                            <p class="team-member__bio"><?php esc_html_e('With over 20 years of experience in aquatic systems, Robert leads our company with passion and expertise.', 'aqualuxe'); ?></p>
                            <div class="team-member__social">
                                <a href="#" class="social-link"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M12 2C6.477 2 2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.879V14.89h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.989C18.343 21.129 22 16.99 22 12c0-5.523-4.477-10-10-10z"/></svg></a>
                                <a href="#" class="social-link"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M22.162 5.656a8.384 8.384 0 0 1-2.402.658A4.196 4.196 0 0 0 21.6 4c-.82.488-1.719.83-2.656 1.015a4.182 4.182 0 0 0-7.126 3.814 11.874 11.874 0 0 1-8.62-4.37 4.168 4.168 0 0 0-.566 2.103c0 1.45.738 2.731 1.86 3.481a4.168 4.168 0 0 1-1.894-.523v.052a4.185 4.185 0 0 0 3.355 4.101 4.21 4.21 0 0 1-1.89.072A4.185 4.185 0 0 0 7.97 16.65a8.394 8.394 0 0 1-6.191 1.732 11.83 11.83 0 0 0 6.41 1.88c7.693 0 11.9-6.373 11.9-11.9 0-.18-.005-.362-.013-.54a8.496 8.496 0 0 0 2.087-2.165z"/></svg></a>
                                <a href="#" class="social-link"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M18.335 18.339H15.67v-4.177c0-.996-.02-2.278-1.39-2.278-1.389 0-1.601 1.084-1.601 2.205v4.25h-2.666V9.75h2.56v1.17h.035c.358-.674 1.228-1.387 2.528-1.387 2.7 0 3.2 1.778 3.2 4.091v4.715zM7.003 8.575a1.546 1.546 0 0 1-1.548-1.549 1.548 1.548 0 1 1 1.547 1.549zm1.336 9.764H5.666V9.75H8.34v8.589zM19.67 3H4.329C3.593 3 3 3.58 3 4.297v15.406C3 20.42 3.594 21 4.328 21h15.338C20.4 21 21 20.42 21 19.703V4.297C21 3.58 20.4 3 19.666 3h.003z"/></svg></a>
                            </div>
                        </div>
                    </div>

                    <div class="team-member">
                        <div class="team-member__image">
                            <img src="<?php echo esc_url(get_theme_file_uri('/assets/dist/images/placeholder/team-2.jpg')); ?>" alt="<?php esc_attr_e('Team Member', 'aqualuxe'); ?>">
                        </div>
                        <div class="team-member__content">
                            <h3 class="team-member__name"><?php esc_html_e('Emily Chen', 'aqualuxe'); ?></h3>
                            <p class="team-member__position"><?php esc_html_e('Marine Biologist', 'aqualuxe'); ?></p>
                            <p class="team-member__bio"><?php esc_html_e('Emily brings scientific expertise to our product development, ensuring optimal conditions for aquatic life.', 'aqualuxe'); ?></p>
                            <div class="team-member__social">
                                <a href="#" class="social-link"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M12 2C6.477 2 2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.879V14.89h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.989C18.343 21.129 22 16.99 22 12c0-5.523-4.477-10-10-10z"/></svg></a>
                                <a href="#" class="social-link"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M22.162 5.656a8.384 8.384 0 0 1-2.402.658A4.196 4.196 0 0 0 21.6 4c-.82.488-1.719.83-2.656 1.015a4.182 4.182 0 0 0-7.126 3.814 11.874 11.874 0 0 1-8.62-4.37 4.168 4.168 0 0 0-.566 2.103c0 1.45.738 2.731 1.86 3.481a4.168 4.168 0 0 1-1.894-.523v.052a4.185 4.185 0 0 0 3.355 4.101 4.21 4.21 0 0 1-1.89.072A4.185 4.185 0 0 0 7.97 16.65a8.394 8.394 0 0 1-6.191 1.732 11.83 11.83 0 0 0 6.41 1.88c7.693 0 11.9-6.373 11.9-11.9 0-.18-.005-.362-.013-.54a8.496 8.496 0 0 0 2.087-2.165z"/></svg></a>
                                <a href="#" class="social-link"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M18.335 18.339H15.67v-4.177c0-.996-.02-2.278-1.39-2.278-1.389 0-1.601 1.084-1.601 2.205v4.25h-2.666V9.75h2.56v1.17h.035c.358-.674 1.228-1.387 2.528-1.387 2.7 0 3.2 1.778 3.2 4.091v4.715zM7.003 8.575a1.546 1.546 0 0 1-1.548-1.549 1.548 1.548 0 1 1 1.547 1.549zm1.336 9.764H5.666V9.75H8.34v8.589zM19.67 3H4.329C3.593 3 3 3.58 3 4.297v15.406C3 20.42 3.594 21 4.328 21h15.338C20.4 21 21 20.42 21 19.703V4.297C21 3.58 20.4 3 19.666 3h.003z"/></svg></a>
                            </div>
                        </div>
                    </div>

                    <div class="team-member">
                        <div class="team-member__image">
                            <img src="<?php echo esc_url(get_theme_file_uri('/assets/dist/images/placeholder/team-3.jpg')); ?>" alt="<?php esc_attr_e('Team Member', 'aqualuxe'); ?>">
                        </div>
                        <div class="team-member__content">
                            <h3 class="team-member__name"><?php esc_html_e('David Martinez', 'aqualuxe'); ?></h3>
                            <p class="team-member__position"><?php esc_html_e('Design Director', 'aqualuxe'); ?></p>
                            <p class="team-member__bio"><?php esc_html_e('David combines functionality with aesthetics to create our signature elegant aquarium designs.', 'aqualuxe'); ?></p>
                            <div class="team-member__social">
                                <a href="#" class="social-link"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M12 2C6.477 2 2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.879V14.89h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.989C18.343 21.129 22 16.99 22 12c0-5.523-4.477-10-10-10z"/></svg></a>
                                <a href="#" class="social-link"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M22.162 5.656a8.384 8.384 0 0 1-2.402.658A4.196 4.196 0 0 0 21.6 4c-.82.488-1.719.83-2.656 1.015a4.182 4.182 0 0 0-7.126 3.814 11.874 11.874 0 0 1-8.62-4.37 4.168 4.168 0 0 0-.566 2.103c0 1.45.738 2.731 1.86 3.481a4.168 4.168 0 0 1-1.894-.523v.052a4.185 4.185 0 0 0 3.355 4.101 4.21 4.21 0 0 1-1.89.072A4.185 4.185 0 0 0 7.97 16.65a8.394 8.394 0 0 1-6.191 1.732 11.83 11.83 0 0 0 6.41 1.88c7.693 0 11.9-6.373 11.9-11.9 0-.18-.005-.362-.013-.54a8.496 8.496 0 0 0 2.087-2.165z"/></svg></a>
                                <a href="#" class="social-link"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M18.335 18.339H15.67v-4.177c0-.996-.02-2.278-1.39-2.278-1.389 0-1.601 1.084-1.601 2.205v4.25h-2.666V9.75h2.56v1.17h.035c.358-.674 1.228-1.387 2.528-1.387 2.7 0 3.2 1.778 3.2 4.091v4.715zM7.003 8.575a1.546 1.546 0 0 1-1.548-1.549 1.548 1.548 0 1 1 1.547 1.549zm1.336 9.764H5.666V9.75H8.34v8.589zM19.67 3H4.329C3.593 3 3 3.58 3 4.297v15.406C3 20.42 3.594 21 4.328 21h15.338C20.4 21 21 20.42 21 19.703V4.297C21 3.58 20.4 3 19.666 3h.003z"/></svg></a>
                            </div>
                        </div>
                    </div>

                    <div class="team-member">
                        <div class="team-member__image">
                            <img src="<?php echo esc_url(get_theme_file_uri('/assets/dist/images/placeholder/team-4.jpg')); ?>" alt="<?php esc_attr_e('Team Member', 'aqualuxe'); ?>">
                        </div>
                        <div class="team-member__content">
                            <h3 class="team-member__name"><?php esc_html_e('Sarah Williams', 'aqualuxe'); ?></h3>
                            <p class="team-member__position"><?php esc_html_e('Customer Experience Manager', 'aqualuxe'); ?></p>
                            <p class="team-member__bio"><?php esc_html_e('Sarah ensures that every customer receives exceptional service and support throughout their journey with us.', 'aqualuxe'); ?></p>
                            <div class="team-member__social">
                                <a href="#" class="social-link"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M12 2C6.477 2 2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.879V14.89h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.989C18.343 21.129 22 16.99 22 12c0-5.523-4.477-10-10-10z"/></svg></a>
                                <a href="#" class="social-link"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M22.162 5.656a8.384 8.384 0 0 1-2.402.658A4.196 4.196 0 0 0 21.6 4c-.82.488-1.719.83-2.656 1.015a4.182 4.182 0 0 0-7.126 3.814 11.874 11.874 0 0 1-8.62-4.37 4.168 4.168 0 0 0-.566 2.103c0 1.45.738 2.731 1.86 3.481a4.168 4.168 0 0 1-1.894-.523v.052a4.185 4.185 0 0 0 3.355 4.101 4.21 4.21 0 0 1-1.89.072A4.185 4.185 0 0 0 7.97 16.65a8.394 8.394 0 0 1-6.191 1.732 11.83 11.83 0 0 0 6.41 1.88c7.693 0 11.9-6.373 11.9-11.9 0-.18-.005-.362-.013-.54a8.496 8.496 0 0 0 2.087-2.165z"/></svg></a>
                                <a href="#" class="social-link"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M18.335 18.339H15.67v-4.177c0-.996-.02-2.278-1.39-2.278-1.389 0-1.601 1.084-1.601 2.205v4.25h-2.666V9.75h2.56v1.17h.035c.358-.674 1.228-1.387 2.528-1.387 2.7 0 3.2 1.778 3.2 4.091v4.715zM7.003 8.575a1.546 1.546 0 0 1-1.548-1.549 1.548 1.548 0 1 1 1.547 1.549zm1.336 9.764H5.666V9.75H8.34v8.589zM19.67 3H4.329C3.593 3 3 3.58 3 4.297v15.406C3 20.42 3.594 21 4.328 21h15.338C20.4 21 21 20.42 21 19.703V4.297C21 3.58 20.4 3 19.666 3h.003z"/></svg></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="about-cta">
            <div class="container">
                <div class="cta-container">
                    <h2 class="cta-title"><?php esc_html_e('Ready to Transform Your Aquatic Experience?', 'aqualuxe'); ?></h2>
                    <p class="cta-text"><?php esc_html_e('Explore our premium collection of aquatic products and accessories', 'aqualuxe'); ?></p>
                    <div class="cta-buttons">
                        <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="btn btn-primary"><?php esc_html_e('Shop Now', 'aqualuxe'); ?></a>
                        <a href="<?php echo esc_url(get_permalink(get_page_by_path('contact'))); ?>" class="btn btn-outline"><?php esc_html_e('Contact Us', 'aqualuxe'); ?></a>
                    </div>
                </div>
            </div>
        </div>

        <?php
        // If comments are open or we have at least one comment, load up the comment template.
        if (comments_open() || get_comments_number()) :
            comments_template();
        endif;
        ?>
    </article><!-- #post-<?php the_ID(); ?> -->
</main><!-- #main -->

<?php
get_footer();