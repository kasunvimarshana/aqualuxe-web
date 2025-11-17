<?php
/**
 * Legal Pages Table of Contents Section
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Get table of contents settings from customizer or ACF
$show_table_of_contents = get_theme_mod( 'aqualuxe_legal_show_table_of_contents_section', false );

// Skip if table of contents section is disabled
// This is different from the sidebar TOC in the content.php file
if ( ! $show_table_of_contents ) {
    return;
}

// Get the content
$content = get_the_content();

// Extract headings for table of contents
$table_of_contents = array();

if ( $content ) {
    // Apply filters to the content
    $filtered_content = apply_filters( 'the_content', $content );
    
    // Match all h2, h3, h4 headings
    preg_match_all( '/<h([2-4])[^>]*>(.*?)<\/h\1>/i', $filtered_content, $matches, PREG_SET_ORDER );
    
    foreach ( $matches as $match ) {
        $level = $match[1]; // 2, 3, or 4
        $title = strip_tags( $match[2] );
        $id = sanitize_title( $title );
        
        // Add to table of contents
        $table_of_contents[] = array(
            'level' => $level,
            'title' => $title,
            'id'    => $id,
        );
    }
}

// Skip if no table of contents items
if ( empty( $table_of_contents ) ) {
    return;
}
?>

<section class="table-of-contents-section section bg-light">
    <div class="container">
        <div class="section-header text-center">
            <h2 class="section-title"><?php esc_html_e( 'Table of Contents', 'aqualuxe' ); ?></h2>
        </div>
        
        <div class="toc-grid">
            <div class="row">
                <?php
                // Split the table of contents into columns
                $columns = 2;
                $items_per_column = ceil( count( $table_of_contents ) / $columns );
                $toc_columns = array_chunk( $table_of_contents, $items_per_column );
                
                foreach ( $toc_columns as $column ) :
                ?>
                    <div class="col-md-6">
                        <ul class="toc-list">
                            <?php foreach ( $column as $item ) : ?>
                                <li class="toc-item level-<?php echo esc_attr( $item['level'] ); ?>">
                                    <a href="#<?php echo esc_attr( $item['id'] ); ?>" class="toc-link">
                                        <?php echo esc_html( $item['title'] ); ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>