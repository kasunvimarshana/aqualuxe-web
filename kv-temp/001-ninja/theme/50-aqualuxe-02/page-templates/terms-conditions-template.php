<?php
/**
 * Template Name: Terms and Conditions
 *
 * @package AquaLuxe
 */

get_header();
?>

<main id="primary" class="site-main">
    <div class="container">
        <?php
        // Display breadcrumbs if a breadcrumb plugin is active
        if ( function_exists( 'yoast_breadcrumb' ) ) {
            yoast_breadcrumb( '<div class="breadcrumbs">', '</div>' );
        } elseif ( function_exists( 'bcn_display' ) ) {
            echo '<div class="breadcrumbs">';
            bcn_display();
            echo '</div>';
        }
        ?>

        <div class="page-header">
            <h1 class="page-title"><?php the_title(); ?></h1>
            
            <?php
            // Get page subtitle
            $subtitle = get_post_meta( get_the_ID(), 'page_subtitle', true );
            if ( empty( $subtitle ) ) {
                $subtitle = __( 'Please read these terms carefully before using our services', 'aqualuxe' );
            }
            ?>
            
            <p class="page-subtitle"><?php echo esc_html( $subtitle ); ?></p>
            
            <?php
            // Last updated date
            $last_updated = get_post_meta( get_the_ID(), 'last_updated', true );
            if ( empty( $last_updated ) ) {
                $last_updated = get_the_modified_date( 'F j, Y' );
            }
            ?>
            <p class="last-updated"><?php printf( esc_html__( 'Last Updated: %s', 'aqualuxe' ), esc_html( $last_updated ) ); ?></p>
        </div>

        <div class="legal-content">
            <?php
            // Table of contents
            $enable_toc = get_post_meta( get_the_ID(), 'enable_toc', true );
            
            if ( $enable_toc !== 'no' ) :
            ?>
            <div class="legal-toc">
                <h2><?php esc_html_e( 'Table of Contents', 'aqualuxe' ); ?></h2>
                <ol class="toc-list">
                    <?php
                    // Get headings from content
                    $content = get_the_content();
                    preg_match_all( '/<h([2-3])[^>]*>(.*?)<\/h\1>/', $content, $matches, PREG_SET_ORDER );
                    
                    if ( ! empty( $matches ) ) {
                        foreach ( $matches as $index => $match ) {
                            $level = $match[1];
                            $title = strip_tags( $match[2] );
                            $anchor = 'section-' . ( $index + 1 );
                            
                            echo '<li class="toc-item level-' . esc_attr( $level ) . '"><a href="#' . esc_attr( $anchor ) . '">' . esc_html( $title ) . '</a></li>';
                        }
                    } else {
                        // Default TOC items if no headings found
                        $default_sections = array(
                            __( 'Acceptance of Terms', 'aqualuxe' ),
                            __( 'Changes to Terms', 'aqualuxe' ),
                            __( 'Account Registration', 'aqualuxe' ),
                            __( 'Products and Services', 'aqualuxe' ),
                            __( 'Pricing and Payment', 'aqualuxe' ),
                            __( 'Shipping and Delivery', 'aqualuxe' ),
                            __( 'Intellectual Property', 'aqualuxe' ),
                            __( 'User Content', 'aqualuxe' ),
                            __( 'Limitation of Liability', 'aqualuxe' ),
                            __( 'Indemnification', 'aqualuxe' ),
                            __( 'Governing Law', 'aqualuxe' ),
                            __( 'Dispute Resolution', 'aqualuxe' ),
                            __( 'Termination', 'aqualuxe' ),
                            __( 'Contact Information', 'aqualuxe' ),
                        );
                        
                        foreach ( $default_sections as $index => $section ) {
                            $anchor = 'section-' . ( $index + 1 );
                            echo '<li class="toc-item"><a href="#' . esc_attr( $anchor ) . '">' . esc_html( $section ) . '</a></li>';
                        }
                    }
                    ?>
                </ol>
            </div>
            <?php endif; ?>
            
            <div class="legal-document">
                <?php
                while ( have_posts() ) :
                    the_post();
                    
                    // Process content to add IDs to headings
                    $content = get_the_content();
                    $content = apply_filters( 'the_content', $content );
                    
                    if ( $enable_toc !== 'no' ) {
                        $content = preg_replace_callback(
                            '/<h([2-3][^>]*)>(.*?)<\/h([2-3])>/',
                            function( $matches ) {
                                static $index = 0;
                                $index++;
                                return '<h' . $matches[1] . ' id="section-' . $index . '">' . $matches[2] . '</h' . $matches[3] . '>';
                            },
                            $content
                        );
                    }
                    
                    echo $content;
                endwhile;
                ?>
            </div>
        </div>

        <?php
        // Related legal documents
        $related_documents = get_post_meta( get_the_ID(), 'related_documents', true );
        
        if ( empty( $related_documents ) ) {
            // Default related documents
            $related_documents = array(
                array(
                    'title' => __( 'Privacy Policy', 'aqualuxe' ),
                    'url' => home_url( '/privacy-policy/' ),
                ),
                array(
                    'title' => __( 'Return Policy', 'aqualuxe' ),
                    'url' => home_url( '/return-policy/' ),
                ),
                array(
                    'title' => __( 'Cookie Policy', 'aqualuxe' ),
                    'url' => home_url( '/cookie-policy/' ),
                ),
            );
        }
        
        if ( ! empty( $related_documents ) && is_array( $related_documents ) ) :
        ?>
        <div class="related-documents">
            <h2><?php esc_html_e( 'Related Documents', 'aqualuxe' ); ?></h2>
            <ul class="document-list">
                <?php foreach ( $related_documents as $document ) : ?>
                    <li>
                        <a href="<?php echo esc_url( $document['url'] ); ?>" class="document-link">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="20" height="20">
                                <path fill-rule="evenodd" d="M5.625 1.5c-1.036 0-1.875.84-1.875 1.875v17.25c0 1.035.84 1.875 1.875 1.875h12.75c1.035 0 1.875-.84 1.875-1.875V12.75A3.75 3.75 0 0016.5 9h-1.875a1.875 1.875 0 01-1.875-1.875V5.25A3.75 3.75 0 009 1.5H5.625zM7.5 15a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5A.75.75 0 017.5 15zm.75 2.25a.75.75 0 000 1.5H12a.75.75 0 000-1.5H8.25z" clip-rule="evenodd" />
                                <path d="M12.971 1.816A5.23 5.23 0 0114.25 5.25v1.875c0 .207.168.375.375.375H16.5a5.23 5.23 0 013.434 1.279 9.768 9.768 0 00-6.963-6.963z" />
                            </svg>
                            <?php echo esc_html( $document['title'] ); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>

        <div class="legal-acceptance">
            <h2><?php esc_html_e( 'Acceptance of Terms', 'aqualuxe' ); ?></h2>
            <p><?php esc_html_e( 'By accessing or using our website, products, or services, you acknowledge that you have read, understood, and agree to be bound by these Terms and Conditions.', 'aqualuxe' ); ?></p>
            
            <div class="acceptance-checkbox">
                <label>
                    <input type="checkbox" id="terms-acceptance">
                    <span><?php esc_html_e( 'I have read and agree to the Terms and Conditions', 'aqualuxe' ); ?></span>
                </label>
            </div>
            
            <div class="acceptance-actions">
                <button id="accept-terms" class="btn btn-primary" disabled><?php esc_html_e( 'Accept Terms', 'aqualuxe' ); ?></button>
                <a href="<?php echo esc_url( home_url() ); ?>" class="btn btn-outline"><?php esc_html_e( 'Decline', 'aqualuxe' ); ?></a>
            </div>
        </div>

        <div class="legal-contact">
            <h2><?php esc_html_e( 'Questions About Our Terms?', 'aqualuxe' ); ?></h2>
            <p><?php esc_html_e( 'If you have any questions or concerns about our terms and conditions, please contact us.', 'aqualuxe' ); ?></p>
            <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="btn btn-primary"><?php esc_html_e( 'Contact Us', 'aqualuxe' ); ?></a>
        </div>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Smooth scroll for TOC links
    const tocLinks = document.querySelectorAll('.toc-list a');
    
    tocLinks.forEach(function(link) {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            const targetId = this.getAttribute('href').substring(1);
            const targetElement = document.getElementById(targetId);
            
            if (targetElement) {
                const headerOffset = 100; // Adjust based on your header height
                const elementPosition = targetElement.getBoundingClientRect().top;
                const offsetPosition = elementPosition + window.pageYOffset - headerOffset;
                
                window.scrollTo({
                    top: offsetPosition,
                    behavior: 'smooth'
                });
            }
        });
    });
    
    // Terms acceptance functionality
    const termsCheckbox = document.getElementById('terms-acceptance');
    const acceptButton = document.getElementById('accept-terms');
    
    if (termsCheckbox && acceptButton) {
        termsCheckbox.addEventListener('change', function() {
            acceptButton.disabled = !this.checked;
        });
        
        acceptButton.addEventListener('click', function() {
            if (termsCheckbox.checked) {
                // Store acceptance in localStorage
                localStorage.setItem('termsAccepted', 'true');
                localStorage.setItem('termsAcceptedDate', new Date().toISOString());
                
                // Redirect to homepage or previous page
                const referrer = document.referrer;
                if (referrer && referrer.indexOf(window.location.hostname) !== -1) {
                    window.location.href = referrer;
                } else {
                    window.location.href = '<?php echo esc_js( home_url() ); ?>';
                }
            }
        });
    }
});
</script>

<?php
get_footer();