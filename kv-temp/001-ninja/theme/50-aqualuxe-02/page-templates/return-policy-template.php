<?php
/**
 * Template Name: Return Policy
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
                $subtitle = __( 'Our policy for returns, refunds, and exchanges', 'aqualuxe' );
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
                            __( 'Return Policy Overview', 'aqualuxe' ),
                            __( 'Return Eligibility', 'aqualuxe' ),
                            __( 'Return Process', 'aqualuxe' ),
                            __( 'Refunds', 'aqualuxe' ),
                            __( 'Exchanges', 'aqualuxe' ),
                            __( 'Damaged or Defective Items', 'aqualuxe' ),
                            __( 'Livestock and Live Plants', 'aqualuxe' ),
                            __( 'Custom Orders', 'aqualuxe' ),
                            __( 'International Orders', 'aqualuxe' ),
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
        // Return policy summary
        $return_periods = get_post_meta( get_the_ID(), 'return_periods', true );
        
        if ( empty( $return_periods ) ) {
            // Default return periods
            $return_periods = array(
                array(
                    'category' => __( 'Equipment & Supplies', 'aqualuxe' ),
                    'period' => __( '30 days', 'aqualuxe' ),
                    'condition' => __( 'Unopened, original packaging', 'aqualuxe' ),
                    'refund_type' => __( 'Full refund or store credit', 'aqualuxe' ),
                ),
                array(
                    'category' => __( 'Opened Equipment', 'aqualuxe' ),
                    'period' => __( '14 days', 'aqualuxe' ),
                    'condition' => __( 'Undamaged, with all parts', 'aqualuxe' ),
                    'refund_type' => __( 'Store credit or exchange', 'aqualuxe' ),
                ),
                array(
                    'category' => __( 'Furniture & Décor', 'aqualuxe' ),
                    'period' => __( '30 days', 'aqualuxe' ),
                    'condition' => __( 'Unused, original condition', 'aqualuxe' ),
                    'refund_type' => __( 'Full refund or store credit', 'aqualuxe' ),
                ),
                array(
                    'category' => __( 'Livestock', 'aqualuxe' ),
                    'period' => __( '48 hours', 'aqualuxe' ),
                    'condition' => __( 'DOA with photo evidence', 'aqualuxe' ),
                    'refund_type' => __( 'Store credit or replacement', 'aqualuxe' ),
                ),
                array(
                    'category' => __( 'Live Plants', 'aqualuxe' ),
                    'period' => __( '7 days', 'aqualuxe' ),
                    'condition' => __( 'Dead on arrival with photo', 'aqualuxe' ),
                    'refund_type' => __( 'Store credit or replacement', 'aqualuxe' ),
                ),
                array(
                    'category' => __( 'Custom Orders', 'aqualuxe' ),
                    'period' => __( 'N/A', 'aqualuxe' ),
                    'condition' => __( 'Non-returnable', 'aqualuxe' ),
                    'refund_type' => __( 'Case-by-case basis', 'aqualuxe' ),
                ),
            );
        }
        
        if ( ! empty( $return_periods ) && is_array( $return_periods ) ) :
        ?>
        <div class="return-policy-summary">
            <h2><?php esc_html_e( 'Return Policy Summary', 'aqualuxe' ); ?></h2>
            <div class="return-table-wrapper">
                <table class="return-table">
                    <thead>
                        <tr>
                            <th><?php esc_html_e( 'Product Category', 'aqualuxe' ); ?></th>
                            <th><?php esc_html_e( 'Return Period', 'aqualuxe' ); ?></th>
                            <th><?php esc_html_e( 'Condition Requirements', 'aqualuxe' ); ?></th>
                            <th><?php esc_html_e( 'Refund Type', 'aqualuxe' ); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ( $return_periods as $item ) : ?>
                            <tr>
                                <td><?php echo esc_html( $item['category'] ); ?></td>
                                <td><?php echo esc_html( $item['period'] ); ?></td>
                                <td><?php echo esc_html( $item['condition'] ); ?></td>
                                <td><?php echo esc_html( $item['refund_type'] ); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php endif; ?>

        <?php
        // Return process steps
        $return_steps = get_post_meta( get_the_ID(), 'return_steps', true );
        
        if ( empty( $return_steps ) ) {
            // Default return steps
            $return_steps = array(
                array(
                    'number' => '1',
                    'title' => __( 'Contact Customer Service', 'aqualuxe' ),
                    'description' => __( 'Contact our customer service team within the eligible return period to initiate your return. You can reach us by email, phone, or through our contact form.', 'aqualuxe' ),
                ),
                array(
                    'number' => '2',
                    'title' => __( 'Complete Return Form', 'aqualuxe' ),
                    'description' => __( 'Fill out the return form provided by our customer service team. Include your order number, the items you wish to return, and the reason for the return.', 'aqualuxe' ),
                ),
                array(
                    'number' => '3',
                    'title' => __( 'Receive Return Authorization', 'aqualuxe' ),
                    'description' => __( 'Once your return request is approved, you will receive a Return Authorization (RA) number and shipping instructions for returning the items.', 'aqualuxe' ),
                ),
                array(
                    'number' => '4',
                    'title' => __( 'Package Items Securely', 'aqualuxe' ),
                    'description' => __( 'Package the items securely in their original packaging if possible. Include all accessories, manuals, and parts that came with the product.', 'aqualuxe' ),
                ),
                array(
                    'number' => '5',
                    'title' => __( 'Ship the Return', 'aqualuxe' ),
                    'description' => __( 'Ship the items back to us using the shipping method specified in your return instructions. Include the RA number on the outside of the package.', 'aqualuxe' ),
                ),
                array(
                    'number' => '6',
                    'title' => __( 'Refund Processing', 'aqualuxe' ),
                    'description' => __( 'Once we receive and inspect your return, we will process your refund or exchange. This typically takes 3-5 business days after the return is received.', 'aqualuxe' ),
                ),
            );
        }
        
        if ( ! empty( $return_steps ) && is_array( $return_steps ) ) :
        ?>
        <div class="return-process">
            <h2><?php esc_html_e( 'Return Process', 'aqualuxe' ); ?></h2>
            <div class="process-steps">
                <?php foreach ( $return_steps as $step ) : ?>
                    <div class="process-step">
                        <?php if ( ! empty( $step['number'] ) ) : ?>
                            <div class="step-number"><?php echo esc_html( $step['number'] ); ?></div>
                        <?php endif; ?>
                        
                        <div class="step-content">
                            <?php if ( ! empty( $step['title'] ) ) : ?>
                                <h3 class="step-title"><?php echo esc_html( $step['title'] ); ?></h3>
                            <?php endif; ?>
                            
                            <?php if ( ! empty( $step['description'] ) ) : ?>
                                <p class="step-description"><?php echo esc_html( $step['description'] ); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <?php
        // Return form CTA
        $show_return_form = get_post_meta( get_the_ID(), 'show_return_form', true );
        
        if ( $show_return_form !== 'no' ) :
        ?>
        <div class="return-form-cta">
            <h2><?php esc_html_e( 'Need to Return an Item?', 'aqualuxe' ); ?></h2>
            <p><?php esc_html_e( 'Complete our online return form to start the process.', 'aqualuxe' ); ?></p>
            <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="btn btn-primary"><?php esc_html_e( 'Start Return Process', 'aqualuxe' ); ?></a>
        </div>
        <?php endif; ?>

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
                    'title' => __( 'Terms and Conditions', 'aqualuxe' ),
                    'url' => home_url( '/terms-conditions/' ),
                ),
                array(
                    'title' => __( 'Shipping Policy', 'aqualuxe' ),
                    'url' => home_url( '/shipping-policy/' ),
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

        <div class="legal-contact">
            <h2><?php esc_html_e( 'Questions About Returns?', 'aqualuxe' ); ?></h2>
            <p><?php esc_html_e( 'If you have any questions about our return policy or need assistance with a return, please contact our customer service team.', 'aqualuxe' ); ?></p>
            <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="btn btn-primary"><?php esc_html_e( 'Contact Customer Service', 'aqualuxe' ); ?></a>
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
});
</script>

<?php
get_footer();