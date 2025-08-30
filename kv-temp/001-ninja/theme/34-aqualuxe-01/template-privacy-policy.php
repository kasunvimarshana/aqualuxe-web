<?php
/**
 * Template Name: Privacy Policy
 *
 * The template for displaying the privacy policy page.
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

get_header();
?>

<main id="primary" class="site-main">
    <article id="post-<?php the_ID(); ?>" <?php post_class('legal-page privacy-policy-page'); ?>>
        <div class="page-header">
            <div class="container">
                <h1 class="page-title"><?php the_title(); ?></h1>
                <?php if (function_exists('yoast_breadcrumb')) : ?>
                    <?php yoast_breadcrumb('<div class="breadcrumbs">', '</div>'); ?>
                <?php endif; ?>
            </div>
        </div>

        <div class="legal-container">
            <div class="container">
                <div class="legal-content">
                    <div class="legal-sidebar">
                        <div class="legal-sidebar__inner">
                            <h3 class="legal-sidebar__title"><?php esc_html_e('Legal Documents', 'aqualuxe'); ?></h3>
                            <ul class="legal-sidebar__menu">
                                <li class="legal-sidebar__item current"><a href="<?php echo esc_url(get_permalink(get_page_by_path('privacy-policy'))); ?>"><?php esc_html_e('Privacy Policy', 'aqualuxe'); ?></a></li>
                                <li class="legal-sidebar__item"><a href="<?php echo esc_url(get_permalink(get_page_by_path('terms-of-service'))); ?>"><?php esc_html_e('Terms of Service', 'aqualuxe'); ?></a></li>
                                <li class="legal-sidebar__item"><a href="<?php echo esc_url(get_permalink(get_page_by_path('shipping-policy'))); ?>"><?php esc_html_e('Shipping Policy', 'aqualuxe'); ?></a></li>
                                <li class="legal-sidebar__item"><a href="<?php echo esc_url(get_permalink(get_page_by_path('return-policy'))); ?>"><?php esc_html_e('Return Policy', 'aqualuxe'); ?></a></li>
                                <li class="legal-sidebar__item"><a href="<?php echo esc_url(get_permalink(get_page_by_path('cookie-policy'))); ?>"><?php esc_html_e('Cookie Policy', 'aqualuxe'); ?></a></li>
                            </ul>

                            <div class="legal-sidebar__contact">
                                <h4><?php esc_html_e('Questions?', 'aqualuxe'); ?></h4>
                                <p><?php esc_html_e('If you have any questions about our policies, please contact us:', 'aqualuxe'); ?></p>
                                <a href="<?php echo esc_url(get_permalink(get_page_by_path('contact'))); ?>" class="btn btn-outline btn-sm"><?php esc_html_e('Contact Us', 'aqualuxe'); ?></a>
                            </div>
                        </div>
                    </div>

                    <div class="legal-main">
                        <div class="legal-main__inner">
                            <div class="legal-main__header">
                                <div class="legal-meta">
                                    <div class="legal-meta__item">
                                        <span class="legal-meta__label"><?php esc_html_e('Last Updated:', 'aqualuxe'); ?></span>
                                        <span class="legal-meta__value"><?php echo get_the_modified_date(); ?></span>
                                    </div>
                                    <div class="legal-meta__item">
                                        <span class="legal-meta__label"><?php esc_html_e('Effective Date:', 'aqualuxe'); ?></span>
                                        <span class="legal-meta__value"><?php echo get_the_date(); ?></span>
                                    </div>
                                </div>
                                <div class="legal-actions">
                                    <button class="btn btn-sm btn-outline print-button" onclick="window.print();">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18"><path fill="none" d="M0 0h24v24H0z"/><path d="M7 17h10v5H7v-5zm12 3v-5H5v5H3a1 1 0 0 1-1-1V9a1 1 0 0 1 1-1h18a1 1 0 0 1 1 1v10a1 1 0 0 1-1 1h-2zM5 10v2h3v-2H5zm2-8h10a1 1 0 0 1 1 1v3H6V3a1 1 0 0 1 1-1z"/></svg>
                                        <?php esc_html_e('Print', 'aqualuxe'); ?>
                                    </button>
                                    <button class="btn btn-sm btn-outline download-button" onclick="window.location.href='<?php echo esc_url(home_url('wp-content/uploads/legal/privacy-policy.pdf')); ?>'">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18"><path fill="none" d="M0 0h24v24H0z"/><path d="M3 19h18v2H3v-2zm10-5.828L19.071 7.1l1.414 1.414L12 17 3.515 8.515 4.929 7.1 11 13.17V2h2v11.172z"/></svg>
                                        <?php esc_html_e('Download PDF', 'aqualuxe'); ?>
                                    </button>
                                </div>
                            </div>

                            <div class="legal-main__content">
                                <?php
                                // Check if there's custom content in the WordPress editor
                                if (have_posts()) :
                                    while (have_posts()) :
                                        the_post();
                                        the_content();
                                    endwhile;
                                else :
                                    // Default privacy policy content if no custom content is provided
                                ?>
                                    <div class="legal-section">
                                        <h2><?php esc_html_e('1. Introduction', 'aqualuxe'); ?></h2>
                                        <p><?php esc_html_e('AquaLuxe ("we," "our," or "us") is committed to protecting your privacy. This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you visit our website aqualuxe.com, including any other media form, media channel, mobile website, or mobile application related or connected thereto (collectively, the "Site").', 'aqualuxe'); ?></p>
                                        <p><?php esc_html_e('Please read this Privacy Policy carefully. If you do not agree with the terms of this Privacy Policy, please do not access the Site.', 'aqualuxe'); ?></p>
                                        <p><?php esc_html_e('We reserve the right to make changes to this Privacy Policy at any time and for any reason. We will alert you about any changes by updating the "Last Updated" date of this Privacy Policy. Any changes or modifications will be effective immediately upon posting the updated Privacy Policy on the Site, and you waive the right to receive specific notice of each such change or modification.', 'aqualuxe'); ?></p>
                                        <p><?php esc_html_e('You are encouraged to periodically review this Privacy Policy to stay informed of updates. You will be deemed to have been made aware of, will be subject to, and will be deemed to have accepted the changes in any revised Privacy Policy by your continued use of the Site after the date such revised Privacy Policy is posted.', 'aqualuxe'); ?></p>
                                    </div>

                                    <div class="legal-section">
                                        <h2><?php esc_html_e('2. Collection of Your Information', 'aqualuxe'); ?></h2>
                                        <p><?php esc_html_e('We may collect information about you in a variety of ways. The information we may collect on the Site includes:', 'aqualuxe'); ?></p>
                                        
                                        <h3><?php esc_html_e('Personal Data', 'aqualuxe'); ?></h3>
                                        <p><?php esc_html_e('Personally identifiable information, such as your name, shipping address, email address, and telephone number, and demographic information, such as your age, gender, hometown, and interests, that you voluntarily give to us when you register with the Site or when you choose to participate in various activities related to the Site, such as online chat and message boards. You are under no obligation to provide us with personal information of any kind, however your refusal to do so may prevent you from using certain features of the Site.', 'aqualuxe'); ?></p>
                                        
                                        <h3><?php esc_html_e('Derivative Data', 'aqualuxe'); ?></h3>
                                        <p><?php esc_html_e('Information our servers automatically collect when you access the Site, such as your IP address, your browser type, your operating system, your access times, and the pages you have viewed directly before and after accessing the Site.', 'aqualuxe'); ?></p>
                                        
                                        <h3><?php esc_html_e('Financial Data', 'aqualuxe'); ?></h3>
                                        <p><?php esc_html_e('Financial information, such as data related to your payment method (e.g., valid credit card number, card brand, expiration date) that we may collect when you purchase, order, return, exchange, or request information about our services from the Site. We store only very limited, if any, financial information that we collect. Otherwise, all financial information is stored by our payment processor, and you are encouraged to review their privacy policy and contact them directly for responses to your questions.', 'aqualuxe'); ?></p>
                                        
                                        <h3><?php esc_html_e('Mobile Device Data', 'aqualuxe'); ?></h3>
                                        <p><?php esc_html_e('Device information, such as your mobile device ID, model, and manufacturer, and information about the location of your device, if you access the Site from a mobile device.', 'aqualuxe'); ?></p>
                                        
                                        <h3><?php esc_html_e('Third-Party Data', 'aqualuxe'); ?></h3>
                                        <p><?php esc_html_e('Information from third parties, such as personal information or network friends, if you connect your account to the third party and grant the Site permission to access this information.', 'aqualuxe'); ?></p>
                                        
                                        <h3><?php esc_html_e('Data From Contests, Giveaways, and Surveys', 'aqualuxe'); ?></h3>
                                        <p><?php esc_html_e('Personal and other information you may provide when entering contests or giveaways and/or responding to surveys.', 'aqualuxe'); ?></p>
                                    </div>

                                    <div class="legal-section">
                                        <h2><?php esc_html_e('3. Use of Your Information', 'aqualuxe'); ?></h2>
                                        <p><?php esc_html_e('Having accurate information about you permits us to provide you with a smooth, efficient, and customized experience. Specifically, we may use information collected about you via the Site to:', 'aqualuxe'); ?></p>
                                        <ul>
                                            <li><?php esc_html_e('Create and manage your account.', 'aqualuxe'); ?></li>
                                            <li><?php esc_html_e('Process your orders and manage your transactions.', 'aqualuxe'); ?></li>
                                            <li><?php esc_html_e('Send you a newsletter with product updates, tips, and recommendations.', 'aqualuxe'); ?></li>
                                            <li><?php esc_html_e('Email you regarding your account or order.', 'aqualuxe'); ?></li>
                                            <li><?php esc_html_e('Fulfill and manage purchases, orders, payments, and other transactions related to the Site.', 'aqualuxe'); ?></li>
                                            <li><?php esc_html_e('Administer sweepstakes, promotions, and contests.', 'aqualuxe'); ?></li>
                                            <li><?php esc_html_e('Compile anonymous statistical data and analysis for use internally or with third parties.', 'aqualuxe'); ?></li>
                                            <li><?php esc_html_e('Deliver targeted advertising, newsletters, and other information regarding promotions and the Site to you.', 'aqualuxe'); ?></li>
                                            <li><?php esc_html_e('Enable user-to-user communications.', 'aqualuxe'); ?></li>
                                            <li><?php esc_html_e('Increase the efficiency and operation of the Site.', 'aqualuxe'); ?></li>
                                            <li><?php esc_html_e('Monitor and analyze usage and trends to improve your experience with the Site.', 'aqualuxe'); ?></li>
                                            <li><?php esc_html_e('Notify you of updates to the Site.', 'aqualuxe'); ?></li>
                                            <li><?php esc_html_e('Offer new products, services, and/or recommendations to you.', 'aqualuxe'); ?></li>
                                            <li><?php esc_html_e('Perform other business activities as needed.', 'aqualuxe'); ?></li>
                                            <li><?php esc_html_e('Prevent fraudulent transactions, monitor against theft, and protect against criminal activity.', 'aqualuxe'); ?></li>
                                            <li><?php esc_html_e('Process payments and refunds.', 'aqualuxe'); ?></li>
                                            <li><?php esc_html_e('Request feedback and contact you about your use of the Site.', 'aqualuxe'); ?></li>
                                            <li><?php esc_html_e('Resolve disputes and troubleshoot problems.', 'aqualuxe'); ?></li>
                                            <li><?php esc_html_e('Respond to product and customer service requests.', 'aqualuxe'); ?></li>
                                            <li><?php esc_html_e('Send you marketing and promotional communications.', 'aqualuxe'); ?></li>
                                        </ul>
                                    </div>

                                    <div class="legal-section">
                                        <h2><?php esc_html_e('4. Disclosure of Your Information', 'aqualuxe'); ?></h2>
                                        <p><?php esc_html_e('We may share information we have collected about you in certain situations. Your information may be disclosed as follows:', 'aqualuxe'); ?></p>
                                        
                                        <h3><?php esc_html_e('By Law or to Protect Rights', 'aqualuxe'); ?></h3>
                                        <p><?php esc_html_e('If we believe the release of information about you is necessary to respond to legal process, to investigate or remedy potential violations of our policies, or to protect the rights, property, and safety of others, we may share your information as permitted or required by any applicable law, rule, or regulation. This includes exchanging information with other entities for fraud protection and credit risk reduction.', 'aqualuxe'); ?></p>
                                        
                                        <h3><?php esc_html_e('Third-Party Service Providers', 'aqualuxe'); ?></h3>
                                        <p><?php esc_html_e('We may share your information with third parties that perform services for us or on our behalf, including payment processing, data analysis, email delivery, hosting services, customer service, and marketing assistance.', 'aqualuxe'); ?></p>
                                        
                                        <h3><?php esc_html_e('Marketing Communications', 'aqualuxe'); ?></h3>
                                        <p><?php esc_html_e('With your consent, or with an opportunity for you to withdraw consent, we may share your information with third parties for marketing purposes, as permitted by law.', 'aqualuxe'); ?></p>
                                        
                                        <h3><?php esc_html_e('Interactions with Other Users', 'aqualuxe'); ?></h3>
                                        <p><?php esc_html_e('If you interact with other users of the Site, those users may see your name, profile photo, and descriptions of your activity, including sending invitations to other users, chatting with other users, liking posts, following blogs.', 'aqualuxe'); ?></p>
                                        
                                        <h3><?php esc_html_e('Online Postings', 'aqualuxe'); ?></h3>
                                        <p><?php esc_html_e('When you post comments, contributions or other content to the Site, your posts may be viewed by all users and may be publicly distributed outside the Site in perpetuity.', 'aqualuxe'); ?></p>
                                        
                                        <h3><?php esc_html_e('Third-Party Advertisers', 'aqualuxe'); ?></h3>
                                        <p><?php esc_html_e('We may use third-party advertising companies to serve ads when you visit the Site. These companies may use information about your visits to the Site and other websites that are contained in web cookies in order to provide advertisements about goods and services of interest to you.', 'aqualuxe'); ?></p>
                                        
                                        <h3><?php esc_html_e('Business Partners', 'aqualuxe'); ?></h3>
                                        <p><?php esc_html_e('We may share your information with our business partners to offer you certain products, services or promotions.', 'aqualuxe'); ?></p>
                                        
                                        <h3><?php esc_html_e('Social Media Contacts', 'aqualuxe'); ?></h3>
                                        <p><?php esc_html_e('If you connect to the Site through a social network, your contacts on the social network will see your name, profile photo, and descriptions of your activity.', 'aqualuxe'); ?></p>
                                        
                                        <h3><?php esc_html_e('Other Third Parties', 'aqualuxe'); ?></h3>
                                        <p><?php esc_html_e('We may share your information with advertisers and investors for the purpose of conducting general business analysis. We may also share your information with such third parties for marketing purposes, as permitted by law.', 'aqualuxe'); ?></p>
                                        
                                        <h3><?php esc_html_e('Sale or Bankruptcy', 'aqualuxe'); ?></h3>
                                        <p><?php esc_html_e('If we reorganize or sell all or a portion of our assets, undergo a merger, or are acquired by another entity, we may transfer your information to the successor entity. If we go out of business or enter bankruptcy, your information would be an asset transferred or acquired by a third party. You acknowledge that such transfers may occur and that the transferee may decline honor commitments we made in this Privacy Policy.', 'aqualuxe'); ?></p>
                                        <p><?php esc_html_e('We are not responsible for the actions of third parties with whom you share personal or sensitive data, and we have no authority to manage or control third-party solicitations. If you no longer wish to receive correspondence, emails or other communications from third parties, you are responsible for contacting the third party directly.', 'aqualuxe'); ?></p>
                                    </div>

                                    <div class="legal-section">
                                        <h2><?php esc_html_e('5. Tracking Technologies', 'aqualuxe'); ?></h2>
                                        
                                        <h3><?php esc_html_e('Cookies and Web Beacons', 'aqualuxe'); ?></h3>
                                        <p><?php esc_html_e('We may use cookies, web beacons, tracking pixels, and other tracking technologies on the Site to help customize the Site and improve your experience. When you access the Site, your personal information is not collected through the use of tracking technology. Most browsers are set to accept cookies by default. You can remove or reject cookies, but be aware that such action could affect the availability and functionality of the Site. You may not decline web beacons. However, they can be rendered ineffective by declining all cookies or by modifying your web browser\'s settings to notify you each time a cookie is tendered, permitting you to accept or decline cookies on an individual basis.', 'aqualuxe'); ?></p>
                                        <p><?php esc_html_e('We may use cookies, web beacons, tracking pixels, and other tracking technologies on the Site to help customize the Site and improve your experience. For more information on how we use cookies, please refer to our Cookie Policy posted on the Site, which is incorporated into this Privacy Policy. By using the Site, you agree to be bound by our Cookie Policy.', 'aqualuxe'); ?></p>
                                        
                                        <h3><?php esc_html_e('Internet-Based Advertising', 'aqualuxe'); ?></h3>
                                        <p><?php esc_html_e('Additionally, we may use third-party software to serve ads on the Site, implement email marketing campaigns, and manage other interactive marketing initiatives. This third-party software may use cookies or similar tracking technology to help manage and optimize your online experience with us. For more information about opting-out of interest-based ads, visit the Network Advertising Initiative Opt-Out Tool or Digital Advertising Alliance Opt-Out Tool.', 'aqualuxe'); ?></p>
                                        
                                        <h3><?php esc_html_e('Website Analytics', 'aqualuxe'); ?></h3>
                                        <p><?php esc_html_e('We may also partner with selected third-party vendors, such as Google Analytics, to allow tracking technologies and remarketing services on the Site through the use of first party cookies and third-party cookies, to, among other things, analyze and track users' use of the Site, determine the popularity of certain content and better understand online activity. By accessing the Site, you consent to the collection and use of your information by these third-party vendors. You are encouraged to review their privacy policy and contact them directly for responses to your questions. We do not transfer personal information to these third-party vendors.', 'aqualuxe'); ?></p>
                                        <p><?php esc_html_e('You should be aware that getting a new computer, installing a new browser, upgrading an existing browser, or erasing or otherwise altering your browser\'s cookies files may also clear certain opt-out cookies, plug-ins, or settings.', 'aqualuxe'); ?></p>
                                    </div>

                                    <div class="legal-section">
                                        <h2><?php esc_html_e('6. Third-Party Websites', 'aqualuxe'); ?></h2>
                                        <p><?php esc_html_e('The Site may contain links to third-party websites and applications of interest, including advertisements and external services, that are not affiliated with us. Once you have used these links to leave the Site, any information you provide to these third parties is not covered by this Privacy Policy, and we cannot guarantee the safety and privacy of your information. Before visiting and providing any information to any third-party websites, you should inform yourself of the privacy policies and practices (if any) of the third party responsible for that website, and should take those steps necessary to, in your discretion, protect the privacy of your information. We are not responsible for the content or privacy and security practices and policies of any third parties, including other sites, services or applications that may be linked to or from the Site.', 'aqualuxe'); ?></p>
                                    </div>

                                    <div class="legal-section">
                                        <h2><?php esc_html_e('7. Security of Your Information', 'aqualuxe'); ?></h2>
                                        <p><?php esc_html_e('We use administrative, technical, and physical security measures to help protect your personal information. While we have taken reasonable steps to secure the personal information you provide to us, please be aware that despite our efforts, no security measures are perfect or impenetrable, and no method of data transmission can be guaranteed against any interception or other type of misuse. Any information disclosed online is vulnerable to interception and misuse by unauthorized parties. Therefore, we cannot guarantee complete security if you provide personal information.', 'aqualuxe'); ?></p>
                                    </div>

                                    <div class="legal-section">
                                        <h2><?php esc_html_e('8. Policy for Children', 'aqualuxe'); ?></h2>
                                        <p><?php esc_html_e('We do not knowingly solicit information from or market to children under the age of 13. If you become aware of any data we have collected from children under age 13, please contact us using the contact information provided below.', 'aqualuxe'); ?></p>
                                    </div>

                                    <div class="legal-section">
                                        <h2><?php esc_html_e('9. Controls for Do-Not-Track Features', 'aqualuxe'); ?></h2>
                                        <p><?php esc_html_e('Most web browsers and some mobile operating systems include a Do-Not-Track ("DNT") feature or setting you can activate to signal your privacy preference not to have data about your online browsing activities monitored and collected. No uniform technology standard for recognizing and implementing DNT signals has been finalized. As such, we do not currently respond to DNT browser signals or any other mechanism that automatically communicates your choice not to be tracked online. If a standard for online tracking is adopted that we must follow in the future, we will inform you about that practice in a revised version of this Privacy Policy.', 'aqualuxe'); ?></p>
                                    </div>

                                    <div class="legal-section">
                                        <h2><?php esc_html_e('10. Options Regarding Your Information', 'aqualuxe'); ?></h2>
                                        
                                        <h3><?php esc_html_e('Account Information', 'aqualuxe'); ?></h3>
                                        <p><?php esc_html_e('You may at any time review or change the information in your account or terminate your account by:', 'aqualuxe'); ?></p>
                                        <ul>
                                            <li><?php esc_html_e('Logging into your account settings and updating your account', 'aqualuxe'); ?></li>
                                            <li><?php esc_html_e('Contacting us using the contact information provided below', 'aqualuxe'); ?></li>
                                        </ul>
                                        <p><?php esc_html_e('Upon your request to terminate your account, we will deactivate or delete your account and information from our active databases. However, some information may be retained in our files to prevent fraud, troubleshoot problems, assist with any investigations, enforce our Terms of Use and/or comply with legal requirements.', 'aqualuxe'); ?></p>
                                        
                                        <h3><?php esc_html_e('Emails and Communications', 'aqualuxe'); ?></h3>
                                        <p><?php esc_html_e('If you no longer wish to receive correspondence, emails, or other communications from us, you may opt-out by:', 'aqualuxe'); ?></p>
                                        <ul>
                                            <li><?php esc_html_e('Noting your preferences at the time you register your account with the Site', 'aqualuxe'); ?></li>
                                            <li><?php esc_html_e('Logging into your account settings and updating your preferences', 'aqualuxe'); ?></li>
                                            <li><?php esc_html_e('Contacting us using the contact information provided below', 'aqualuxe'); ?></li>
                                        </ul>
                                        <p><?php esc_html_e('If you no longer wish to receive correspondence, emails, or other communications from third parties, you are responsible for contacting the third party directly.', 'aqualuxe'); ?></p>
                                    </div>

                                    <div class="legal-section">
                                        <h2><?php esc_html_e('11. California Privacy Rights', 'aqualuxe'); ?></h2>
                                        <p><?php esc_html_e('California Civil Code Section 1798.83, also known as the "Shine The Light" law, permits our users who are California residents to request and obtain from us, once a year and free of charge, information about categories of personal information (if any) we disclosed to third parties for direct marketing purposes and the names and addresses of all third parties with which we shared personal information in the immediately preceding calendar year. If you are a California resident and would like to make such a request, please submit your request in writing to us using the contact information provided below.', 'aqualuxe'); ?></p>
                                        <p><?php esc_html_e('If you are under 18 years of age, reside in California, and have a registered account with the Site, you have the right to request removal of unwanted data that you publicly post on the Site. To request removal of such data, please contact us using the contact information provided below, and include the email address associated with your account and a statement that you reside in California. We will make sure the data is not publicly displayed on the Site, but please be aware that the data may not be completely or comprehensively removed from our systems.', 'aqualuxe'); ?></p>
                                    </div>

                                    <div class="legal-section">
                                        <h2><?php esc_html_e('12. Contact Us', 'aqualuxe'); ?></h2>
                                        <p><?php esc_html_e('If you have questions or comments about this Privacy Policy, please contact us at:', 'aqualuxe'); ?></p>
                                        <p>
                                            <?php esc_html_e('AquaLuxe', 'aqualuxe'); ?><br>
                                            <?php esc_html_e('123 Aquatic Avenue', 'aqualuxe'); ?><br>
                                            <?php esc_html_e('San Francisco, CA 94107', 'aqualuxe'); ?><br>
                                            <?php esc_html_e('Phone: (800) 555-1234', 'aqualuxe'); ?><br>
                                            <?php esc_html_e('Email: privacy@aqualuxe.com', 'aqualuxe'); ?>
                                        </p>
                                    </div>
                                <?php
                                endif;
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </article><!-- #post-<?php the_ID(); ?> -->
</main><!-- #main -->

<?php
get_footer();