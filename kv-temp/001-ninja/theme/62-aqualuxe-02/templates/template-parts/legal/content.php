<?php
/**
 * Legal Pages Content Section
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Get content settings from customizer or ACF
$show_last_updated = get_theme_mod( 'aqualuxe_legal_show_last_updated', true );
$show_print_button = get_theme_mod( 'aqualuxe_legal_show_print_button', true );
$show_download_button = get_theme_mod( 'aqualuxe_legal_show_download_button', true );
$show_table_of_contents = get_theme_mod( 'aqualuxe_legal_show_table_of_contents', true );
$table_of_contents_title = get_theme_mod( 'aqualuxe_legal_table_of_contents_title', __( 'Table of Contents', 'aqualuxe' ) );

// Get the content
$content = get_the_content();

// Process the content to add IDs to headings for the table of contents
$processed_content = '';
$table_of_contents = array();

if ( $content ) {
    // Use output buffering to capture the processed content
    ob_start();
    
    // Apply filters to the content
    $filtered_content = apply_filters( 'the_content', $content );
    
    // Output the filtered content
    echo $filtered_content;
    
    // Get the processed content
    $processed_content = ob_get_clean();
    
    // Extract headings for table of contents if enabled
    if ( $show_table_of_contents ) {
        // Match all h2, h3, h4 headings
        preg_match_all( '/<h([2-4])[^>]*>(.*?)<\/h\1>/i', $processed_content, $matches, PREG_SET_ORDER );
        
        foreach ( $matches as $match ) {
            $level = $match[1]; // 2, 3, or 4
            $title = strip_tags( $match[2] );
            $id = sanitize_title( $title );
            
            // Add ID to the heading in the content
            $processed_content = str_replace(
                $match[0],
                '<h' . $level . ' id="' . $id . '">' . $match[2] . '</h' . $level . '>',
                $processed_content
            );
            
            // Add to table of contents
            $table_of_contents[] = array(
                'level' => $level,
                'title' => $title,
                'id'    => $id,
            );
        }
    }
}
?>

<section class="legal-content-section section">
    <div class="container">
        <div class="row">
            <?php if ( $show_table_of_contents && ! empty( $table_of_contents ) ) : ?>
                <div class="col-lg-3">
                    <div class="legal-sidebar">
                        <div class="table-of-contents">
                            <h3 class="toc-title"><?php echo esc_html( $table_of_contents_title ); ?></h3>
                            
                            <nav class="toc-nav">
                                <ul class="toc-list">
                                    <?php foreach ( $table_of_contents as $item ) : ?>
                                        <li class="toc-item level-<?php echo esc_attr( $item['level'] ); ?>">
                                            <a href="#<?php echo esc_attr( $item['id'] ); ?>" class="toc-link">
                                                <?php echo esc_html( $item['title'] ); ?>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </nav>
                        </div>
                        
                        <div class="legal-actions">
                            <?php if ( $show_print_button ) : ?>
                                <button type="button" class="btn btn-outline-primary btn-sm print-button" onclick="window.print();">
                                    <i class="icon-print"></i> <?php esc_html_e( 'Print', 'aqualuxe' ); ?>
                                </button>
                            <?php endif; ?>
                            
                            <?php if ( $show_download_button ) : ?>
                                <a href="<?php echo esc_url( add_query_arg( 'download', 'pdf', get_permalink() ) ); ?>" class="btn btn-outline-primary btn-sm download-button">
                                    <i class="icon-download"></i> <?php esc_html_e( 'Download PDF', 'aqualuxe' ); ?>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-9">
            <?php endif; ?>
            
            <div class="legal-content">
                <?php if ( $show_last_updated && ! is_page_template( 'templates/template-privacy-policy.php' ) ) : ?>
                    <div class="last-updated">
                        <?php printf( __( 'Last updated: %s', 'aqualuxe' ), get_the_modified_date( 'F j, Y' ) ); ?>
                    </div>
                <?php endif; ?>
                
                <?php echo $processed_content; ?>
            </div>
            
            <?php if ( $show_table_of_contents && ! empty( $table_of_contents ) ) : ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>