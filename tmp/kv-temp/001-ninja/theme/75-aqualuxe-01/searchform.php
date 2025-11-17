<?php
/**
 * Search form template
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

$unique_id = wp_unique_id( 'search-form-' );
?>

<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
    <div class="search-form-wrapper relative">
        <label for="<?php echo esc_attr( $unique_id ); ?>" class="sr-only">
            <?php esc_html_e( 'Search for:', 'aqualuxe' ); ?>
        </label>
        
        <div class="search-input-wrapper relative">
            <input type="search" 
                   id="<?php echo esc_attr( $unique_id ); ?>"
                   class="search-field w-full px-4 py-3 pl-12 pr-16 text-gray-900 dark:text-white bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:focus:border-primary-500 transition-colors"
                   placeholder="<?php echo esc_attr_x( 'Search...', 'placeholder', 'aqualuxe' ); ?>"
                   value="<?php echo get_search_query(); ?>"
                   name="s"
                   autocomplete="off" />
            
            <!-- Search Icon -->
            <div class="search-icon absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 dark:text-gray-500">
                <i class="fas fa-search" aria-hidden="true"></i>
            </div>
            
            <!-- Clear Button (shown when there's text) -->
            <button type="button" 
                    class="search-clear absolute right-12 top-1/2 transform -translate-y-1/2 text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300 transition-colors opacity-0 invisible"
                    aria-label="<?php esc_attr_e( 'Clear search', 'aqualuxe' ); ?>">
                <i class="fas fa-times" aria-hidden="true"></i>
            </button>
            
            <!-- Submit Button -->
            <button type="submit" 
                    class="search-submit absolute right-3 top-1/2 transform -translate-y-1/2 px-3 py-1.5 bg-primary-500 hover:bg-primary-600 text-white rounded-md transition-colors focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800"
                    aria-label="<?php esc_attr_e( 'Submit search', 'aqualuxe' ); ?>">
                <i class="fas fa-arrow-right text-sm" aria-hidden="true"></i>
            </button>
        </div>
        
        <!-- Search Suggestions (populated via AJAX) -->
        <div class="search-suggestions absolute top-full left-0 right-0 mt-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg max-h-80 overflow-y-auto z-50 hidden">
            <div class="search-suggestions-content">
                <!-- Suggestions will be populated here -->
            </div>
        </div>
    </div>
    
    <!-- Advanced Search Options (expandable) -->
    <div class="search-options mt-4 hidden">
        <div class="search-options-content p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
            <h3 class="text-sm font-medium text-gray-900 dark:text-white mb-3">
                <?php esc_html_e( 'Advanced Search', 'aqualuxe' ); ?>
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Post Type Filter -->
                <div class="search-filter">
                    <label for="search-post-type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        <?php esc_html_e( 'Content Type', 'aqualuxe' ); ?>
                    </label>
                    <select id="search-post-type" name="post_type" class="form-select w-full text-sm">
                        <option value=""><?php esc_html_e( 'All Content', 'aqualuxe' ); ?></option>
                        <option value="post"><?php esc_html_e( 'Posts', 'aqualuxe' ); ?></option>
                        <option value="page"><?php esc_html_e( 'Pages', 'aqualuxe' ); ?></option>
                        <?php if ( class_exists( 'WooCommerce' ) ) : ?>
                            <option value="product"><?php esc_html_e( 'Products', 'aqualuxe' ); ?></option>
                        <?php endif; ?>
                    </select>
                </div>
                
                <!-- Category Filter -->
                <?php
                $categories = get_categories( array( 'hide_empty' => false ) );
                if ( ! empty( $categories ) ) :
                ?>
                <div class="search-filter">
                    <label for="search-category" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        <?php esc_html_e( 'Category', 'aqualuxe' ); ?>
                    </label>
                    <select id="search-category" name="category_name" class="form-select w-full text-sm">
                        <option value=""><?php esc_html_e( 'All Categories', 'aqualuxe' ); ?></option>
                        <?php foreach ( $categories as $category ) : ?>
                            <option value="<?php echo esc_attr( $category->slug ); ?>">
                                <?php echo esc_html( $category->name ); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <?php endif; ?>
                
                <!-- Date Range -->
                <div class="search-filter">
                    <label for="search-date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        <?php esc_html_e( 'Date Range', 'aqualuxe' ); ?>
                    </label>
                    <select id="search-date" name="date_query" class="form-select w-full text-sm">
                        <option value=""><?php esc_html_e( 'All Dates', 'aqualuxe' ); ?></option>
                        <option value="last-week"><?php esc_html_e( 'Last Week', 'aqualuxe' ); ?></option>
                        <option value="last-month"><?php esc_html_e( 'Last Month', 'aqualuxe' ); ?></option>
                        <option value="last-year"><?php esc_html_e( 'Last Year', 'aqualuxe' ); ?></option>
                    </select>
                </div>
                
                <!-- Sort Order -->
                <div class="search-filter">
                    <label for="search-orderby" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        <?php esc_html_e( 'Sort By', 'aqualuxe' ); ?>
                    </label>
                    <select id="search-orderby" name="orderby" class="form-select w-full text-sm">
                        <option value="relevance"><?php esc_html_e( 'Relevance', 'aqualuxe' ); ?></option>
                        <option value="date"><?php esc_html_e( 'Date', 'aqualuxe' ); ?></option>
                        <option value="title"><?php esc_html_e( 'Title', 'aqualuxe' ); ?></option>
                        <option value="comment_count"><?php esc_html_e( 'Most Commented', 'aqualuxe' ); ?></option>
                    </select>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Toggle Advanced Search -->
    <button type="button" 
            class="search-options-toggle mt-3 text-sm text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 transition-colors"
            aria-expanded="false">
        <span class="toggle-text"><?php esc_html_e( 'Show Advanced Options', 'aqualuxe' ); ?></span>
        <i class="fas fa-chevron-down ml-1 transition-transform" aria-hidden="true"></i>
    </button>
</form>
