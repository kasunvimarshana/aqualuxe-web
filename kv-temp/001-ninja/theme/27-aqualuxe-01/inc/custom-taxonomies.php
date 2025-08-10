<?php
/**
 * Custom Taxonomies for AquaLuxe Theme
 *
 * @package AquaLuxe
 */

/**
 * Register custom taxonomies
 */
function aqualuxe_register_custom_taxonomies() {
    // Service Tags Taxonomy
    $service_tag_labels = array(
        'name'                       => _x( 'Service Tags', 'taxonomy general name', 'aqualuxe' ),
        'singular_name'              => _x( 'Service Tag', 'taxonomy singular name', 'aqualuxe' ),
        'search_items'               => __( 'Search Service Tags', 'aqualuxe' ),
        'popular_items'              => __( 'Popular Service Tags', 'aqualuxe' ),
        'all_items'                  => __( 'All Service Tags', 'aqualuxe' ),
        'parent_item'                => null,
        'parent_item_colon'          => null,
        'edit_item'                  => __( 'Edit Service Tag', 'aqualuxe' ),
        'update_item'                => __( 'Update Service Tag', 'aqualuxe' ),
        'add_new_item'               => __( 'Add New Service Tag', 'aqualuxe' ),
        'new_item_name'              => __( 'New Service Tag Name', 'aqualuxe' ),
        'separate_items_with_commas' => __( 'Separate service tags with commas', 'aqualuxe' ),
        'add_or_remove_items'        => __( 'Add or remove service tags', 'aqualuxe' ),
        'choose_from_most_used'      => __( 'Choose from the most used service tags', 'aqualuxe' ),
        'not_found'                  => __( 'No service tags found.', 'aqualuxe' ),
        'menu_name'                  => __( 'Tags', 'aqualuxe' ),
    );

    register_taxonomy( 'service_tag', array( 'service' ), array(
        'hierarchical'          => false,
        'labels'                => $service_tag_labels,
        'show_ui'               => true,
        'show_admin_column'     => true,
        'update_count_callback' => '_update_post_term_count',
        'query_var'             => true,
        'rewrite'               => array( 'slug' => 'service-tag' ),
        'show_in_rest'          => true,
    ) );

    // Event Tags Taxonomy
    $event_tag_labels = array(
        'name'                       => _x( 'Event Tags', 'taxonomy general name', 'aqualuxe' ),
        'singular_name'              => _x( 'Event Tag', 'taxonomy singular name', 'aqualuxe' ),
        'search_items'               => __( 'Search Event Tags', 'aqualuxe' ),
        'popular_items'              => __( 'Popular Event Tags', 'aqualuxe' ),
        'all_items'                  => __( 'All Event Tags', 'aqualuxe' ),
        'parent_item'                => null,
        'parent_item_colon'          => null,
        'edit_item'                  => __( 'Edit Event Tag', 'aqualuxe' ),
        'update_item'                => __( 'Update Event Tag', 'aqualuxe' ),
        'add_new_item'               => __( 'Add New Event Tag', 'aqualuxe' ),
        'new_item_name'              => __( 'New Event Tag Name', 'aqualuxe' ),
        'separate_items_with_commas' => __( 'Separate event tags with commas', 'aqualuxe' ),
        'add_or_remove_items'        => __( 'Add or remove event tags', 'aqualuxe' ),
        'choose_from_most_used'      => __( 'Choose from the most used event tags', 'aqualuxe' ),
        'not_found'                  => __( 'No event tags found.', 'aqualuxe' ),
        'menu_name'                  => __( 'Tags', 'aqualuxe' ),
    );

    register_taxonomy( 'event_tag', array( 'event' ), array(
        'hierarchical'          => false,
        'labels'                => $event_tag_labels,
        'show_ui'               => true,
        'show_admin_column'     => true,
        'update_count_callback' => '_update_post_term_count',
        'query_var'             => true,
        'rewrite'               => array( 'slug' => 'event-tag' ),
        'show_in_rest'          => true,
    ) );

    // Project Tags Taxonomy
    $project_tag_labels = array(
        'name'                       => _x( 'Project Tags', 'taxonomy general name', 'aqualuxe' ),
        'singular_name'              => _x( 'Project Tag', 'taxonomy singular name', 'aqualuxe' ),
        'search_items'               => __( 'Search Project Tags', 'aqualuxe' ),
        'popular_items'              => __( 'Popular Project Tags', 'aqualuxe' ),
        'all_items'                  => __( 'All Project Tags', 'aqualuxe' ),
        'parent_item'                => null,
        'parent_item_colon'          => null,
        'edit_item'                  => __( 'Edit Project Tag', 'aqualuxe' ),
        'update_item'                => __( 'Update Project Tag', 'aqualuxe' ),
        'add_new_item'               => __( 'Add New Project Tag', 'aqualuxe' ),
        'new_item_name'              => __( 'New Project Tag Name', 'aqualuxe' ),
        'separate_items_with_commas' => __( 'Separate project tags with commas', 'aqualuxe' ),
        'add_or_remove_items'        => __( 'Add or remove project tags', 'aqualuxe' ),
        'choose_from_most_used'      => __( 'Choose from the most used project tags', 'aqualuxe' ),
        'not_found'                  => __( 'No project tags found.', 'aqualuxe' ),
        'menu_name'                  => __( 'Tags', 'aqualuxe' ),
    );

    register_taxonomy( 'project_tag', array( 'project' ), array(
        'hierarchical'          => false,
        'labels'                => $project_tag_labels,
        'show_ui'               => true,
        'show_admin_column'     => true,
        'update_count_callback' => '_update_post_term_count',
        'query_var'             => true,
        'rewrite'               => array( 'slug' => 'project-tag' ),
        'show_in_rest'          => true,
    ) );

    // Fish Species Taxonomy for Products
    if ( class_exists( 'WooCommerce' ) ) {
        $fish_species_labels = array(
            'name'              => _x( 'Fish Species', 'taxonomy general name', 'aqualuxe' ),
            'singular_name'     => _x( 'Fish Species', 'taxonomy singular name', 'aqualuxe' ),
            'search_items'      => __( 'Search Fish Species', 'aqualuxe' ),
            'all_items'         => __( 'All Fish Species', 'aqualuxe' ),
            'parent_item'       => __( 'Parent Fish Species', 'aqualuxe' ),
            'parent_item_colon' => __( 'Parent Fish Species:', 'aqualuxe' ),
            'edit_item'         => __( 'Edit Fish Species', 'aqualuxe' ),
            'update_item'       => __( 'Update Fish Species', 'aqualuxe' ),
            'add_new_item'      => __( 'Add New Fish Species', 'aqualuxe' ),
            'new_item_name'     => __( 'New Fish Species Name', 'aqualuxe' ),
            'menu_name'         => __( 'Fish Species', 'aqualuxe' ),
        );

        register_taxonomy( 'fish_species', array( 'product' ), array(
            'hierarchical'      => true,
            'labels'            => $fish_species_labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array( 'slug' => 'fish-species' ),
            'show_in_rest'      => true,
        ) );

        // Water Type Taxonomy for Products
        $water_type_labels = array(
            'name'              => _x( 'Water Type', 'taxonomy general name', 'aqualuxe' ),
            'singular_name'     => _x( 'Water Type', 'taxonomy singular name', 'aqualuxe' ),
            'search_items'      => __( 'Search Water Types', 'aqualuxe' ),
            'all_items'         => __( 'All Water Types', 'aqualuxe' ),
            'parent_item'       => __( 'Parent Water Type', 'aqualuxe' ),
            'parent_item_colon' => __( 'Parent Water Type:', 'aqualuxe' ),
            'edit_item'         => __( 'Edit Water Type', 'aqualuxe' ),
            'update_item'       => __( 'Update Water Type', 'aqualuxe' ),
            'add_new_item'      => __( 'Add New Water Type', 'aqualuxe' ),
            'new_item_name'     => __( 'New Water Type Name', 'aqualuxe' ),
            'menu_name'         => __( 'Water Types', 'aqualuxe' ),
        );

        register_taxonomy( 'water_type', array( 'product' ), array(
            'hierarchical'      => true,
            'labels'            => $water_type_labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array( 'slug' => 'water-type' ),
            'show_in_rest'      => true,
        ) );

        // Care Level Taxonomy for Products
        $care_level_labels = array(
            'name'              => _x( 'Care Level', 'taxonomy general name', 'aqualuxe' ),
            'singular_name'     => _x( 'Care Level', 'taxonomy singular name', 'aqualuxe' ),
            'search_items'      => __( 'Search Care Levels', 'aqualuxe' ),
            'all_items'         => __( 'All Care Levels', 'aqualuxe' ),
            'parent_item'       => __( 'Parent Care Level', 'aqualuxe' ),
            'parent_item_colon' => __( 'Parent Care Level:', 'aqualuxe' ),
            'edit_item'         => __( 'Edit Care Level', 'aqualuxe' ),
            'update_item'       => __( 'Update Care Level', 'aqualuxe' ),
            'add_new_item'      => __( 'Add New Care Level', 'aqualuxe' ),
            'new_item_name'     => __( 'New Care Level Name', 'aqualuxe' ),
            'menu_name'         => __( 'Care Levels', 'aqualuxe' ),
        );

        register_taxonomy( 'care_level', array( 'product' ), array(
            'hierarchical'      => true,
            'labels'            => $care_level_labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array( 'slug' => 'care-level' ),
            'show_in_rest'      => true,
        ) );

        // Tank Size Taxonomy for Products
        $tank_size_labels = array(
            'name'              => _x( 'Tank Size', 'taxonomy general name', 'aqualuxe' ),
            'singular_name'     => _x( 'Tank Size', 'taxonomy singular name', 'aqualuxe' ),
            'search_items'      => __( 'Search Tank Sizes', 'aqualuxe' ),
            'all_items'         => __( 'All Tank Sizes', 'aqualuxe' ),
            'parent_item'       => __( 'Parent Tank Size', 'aqualuxe' ),
            'parent_item_colon' => __( 'Parent Tank Size:', 'aqualuxe' ),
            'edit_item'         => __( 'Edit Tank Size', 'aqualuxe' ),
            'update_item'       => __( 'Update Tank Size', 'aqualuxe' ),
            'add_new_item'      => __( 'Add New Tank Size', 'aqualuxe' ),
            'new_item_name'     => __( 'New Tank Size Name', 'aqualuxe' ),
            'menu_name'         => __( 'Tank Sizes', 'aqualuxe' ),
        );

        register_taxonomy( 'tank_size', array( 'product' ), array(
            'hierarchical'      => true,
            'labels'            => $tank_size_labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array( 'slug' => 'tank-size' ),
            'show_in_rest'      => true,
        ) );
    }
}
add_action( 'init', 'aqualuxe_register_custom_taxonomies' );

/**
 * Add custom taxonomy filter to admin list tables
 */
function aqualuxe_add_taxonomy_filters() {
    global $typenow;
    
    // Service post type
    if ( 'service' === $typenow ) {
        // Service Category filter
        $taxonomy = 'service_category';
        $tax_obj = get_taxonomy( $taxonomy );
        $tax_name = $tax_obj->labels->name;
        $terms = get_terms( $taxonomy );
        
        if ( count( $terms ) > 0 ) {
            echo '<select name="' . esc_attr( $taxonomy ) . '" id="' . esc_attr( $taxonomy ) . '" class="postform">';
            echo '<option value="">' . esc_html( sprintf( __( 'All %s', 'aqualuxe' ), $tax_name ) ) . '</option>';
            
            foreach ( $terms as $term ) {
                printf(
                    '<option value="%1$s" %2$s>%3$s (%4$s)</option>',
                    esc_attr( $term->slug ),
                    ( ( isset( $_GET[$taxonomy] ) && $_GET[$taxonomy] === $term->slug ) ? ' selected="selected"' : '' ),
                    esc_html( $term->name ),
                    esc_html( $term->count )
                );
            }
            
            echo '</select>';
        }
        
        // Service Tag filter
        $taxonomy = 'service_tag';
        $tax_obj = get_taxonomy( $taxonomy );
        $tax_name = $tax_obj->labels->name;
        $terms = get_terms( $taxonomy );
        
        if ( count( $terms ) > 0 ) {
            echo '<select name="' . esc_attr( $taxonomy ) . '" id="' . esc_attr( $taxonomy ) . '" class="postform">';
            echo '<option value="">' . esc_html( sprintf( __( 'All %s', 'aqualuxe' ), $tax_name ) ) . '</option>';
            
            foreach ( $terms as $term ) {
                printf(
                    '<option value="%1$s" %2$s>%3$s (%4$s)</option>',
                    esc_attr( $term->slug ),
                    ( ( isset( $_GET[$taxonomy] ) && $_GET[$taxonomy] === $term->slug ) ? ' selected="selected"' : '' ),
                    esc_html( $term->name ),
                    esc_html( $term->count )
                );
            }
            
            echo '</select>';
        }
    }
    
    // Event post type
    if ( 'event' === $typenow ) {
        // Event Category filter
        $taxonomy = 'event_category';
        $tax_obj = get_taxonomy( $taxonomy );
        $tax_name = $tax_obj->labels->name;
        $terms = get_terms( $taxonomy );
        
        if ( count( $terms ) > 0 ) {
            echo '<select name="' . esc_attr( $taxonomy ) . '" id="' . esc_attr( $taxonomy ) . '" class="postform">';
            echo '<option value="">' . esc_html( sprintf( __( 'All %s', 'aqualuxe' ), $tax_name ) ) . '</option>';
            
            foreach ( $terms as $term ) {
                printf(
                    '<option value="%1$s" %2$s>%3$s (%4$s)</option>',
                    esc_attr( $term->slug ),
                    ( ( isset( $_GET[$taxonomy] ) && $_GET[$taxonomy] === $term->slug ) ? ' selected="selected"' : '' ),
                    esc_html( $term->name ),
                    esc_html( $term->count )
                );
            }
            
            echo '</select>';
        }
        
        // Event Tag filter
        $taxonomy = 'event_tag';
        $tax_obj = get_taxonomy( $taxonomy );
        $tax_name = $tax_obj->labels->name;
        $terms = get_terms( $taxonomy );
        
        if ( count( $terms ) > 0 ) {
            echo '<select name="' . esc_attr( $taxonomy ) . '" id="' . esc_attr( $taxonomy ) . '" class="postform">';
            echo '<option value="">' . esc_html( sprintf( __( 'All %s', 'aqualuxe' ), $tax_name ) ) . '</option>';
            
            foreach ( $terms as $term ) {
                printf(
                    '<option value="%1$s" %2$s>%3$s (%4$s)</option>',
                    esc_attr( $term->slug ),
                    ( ( isset( $_GET[$taxonomy] ) && $_GET[$taxonomy] === $term->slug ) ? ' selected="selected"' : '' ),
                    esc_html( $term->name ),
                    esc_html( $term->count )
                );
            }
            
            echo '</select>';
        }
    }
    
    // Project post type
    if ( 'project' === $typenow ) {
        // Project Category filter
        $taxonomy = 'project_category';
        $tax_obj = get_taxonomy( $taxonomy );
        $tax_name = $tax_obj->labels->name;
        $terms = get_terms( $taxonomy );
        
        if ( count( $terms ) > 0 ) {
            echo '<select name="' . esc_attr( $taxonomy ) . '" id="' . esc_attr( $taxonomy ) . '" class="postform">';
            echo '<option value="">' . esc_html( sprintf( __( 'All %s', 'aqualuxe' ), $tax_name ) ) . '</option>';
            
            foreach ( $terms as $term ) {
                printf(
                    '<option value="%1$s" %2$s>%3$s (%4$s)</option>',
                    esc_attr( $term->slug ),
                    ( ( isset( $_GET[$taxonomy] ) && $_GET[$taxonomy] === $term->slug ) ? ' selected="selected"' : '' ),
                    esc_html( $term->name ),
                    esc_html( $term->count )
                );
            }
            
            echo '</select>';
        }
        
        // Project Tag filter
        $taxonomy = 'project_tag';
        $tax_obj = get_taxonomy( $taxonomy );
        $tax_name = $tax_obj->labels->name;
        $terms = get_terms( $taxonomy );
        
        if ( count( $terms ) > 0 ) {
            echo '<select name="' . esc_attr( $taxonomy ) . '" id="' . esc_attr( $taxonomy ) . '" class="postform">';
            echo '<option value="">' . esc_html( sprintf( __( 'All %s', 'aqualuxe' ), $tax_name ) ) . '</option>';
            
            foreach ( $terms as $term ) {
                printf(
                    '<option value="%1$s" %2$s>%3$s (%4$s)</option>',
                    esc_attr( $term->slug ),
                    ( ( isset( $_GET[$taxonomy] ) && $_GET[$taxonomy] === $term->slug ) ? ' selected="selected"' : '' ),
                    esc_html( $term->name ),
                    esc_html( $term->count )
                );
            }
            
            echo '</select>';
        }
    }
    
    // Product post type
    if ( class_exists( 'WooCommerce' ) && 'product' === $typenow ) {
        // Fish Species filter
        $taxonomy = 'fish_species';
        $tax_obj = get_taxonomy( $taxonomy );
        $tax_name = $tax_obj->labels->name;
        $terms = get_terms( $taxonomy );
        
        if ( count( $terms ) > 0 ) {
            echo '<select name="' . esc_attr( $taxonomy ) . '" id="' . esc_attr( $taxonomy ) . '" class="postform">';
            echo '<option value="">' . esc_html( sprintf( __( 'All %s', 'aqualuxe' ), $tax_name ) ) . '</option>';
            
            foreach ( $terms as $term ) {
                printf(
                    '<option value="%1$s" %2$s>%3$s (%4$s)</option>',
                    esc_attr( $term->slug ),
                    ( ( isset( $_GET[$taxonomy] ) && $_GET[$taxonomy] === $term->slug ) ? ' selected="selected"' : '' ),
                    esc_html( $term->name ),
                    esc_html( $term->count )
                );
            }
            
            echo '</select>';
        }
        
        // Water Type filter
        $taxonomy = 'water_type';
        $tax_obj = get_taxonomy( $taxonomy );
        $tax_name = $tax_obj->labels->name;
        $terms = get_terms( $taxonomy );
        
        if ( count( $terms ) > 0 ) {
            echo '<select name="' . esc_attr( $taxonomy ) . '" id="' . esc_attr( $taxonomy ) . '" class="postform">';
            echo '<option value="">' . esc_html( sprintf( __( 'All %s', 'aqualuxe' ), $tax_name ) ) . '</option>';
            
            foreach ( $terms as $term ) {
                printf(
                    '<option value="%1$s" %2$s>%3$s (%4$s)</option>',
                    esc_attr( $term->slug ),
                    ( ( isset( $_GET[$taxonomy] ) && $_GET[$taxonomy] === $term->slug ) ? ' selected="selected"' : '' ),
                    esc_html( $term->name ),
                    esc_html( $term->count )
                );
            }
            
            echo '</select>';
        }
        
        // Care Level filter
        $taxonomy = 'care_level';
        $tax_obj = get_taxonomy( $taxonomy );
        $tax_name = $tax_obj->labels->name;
        $terms = get_terms( $taxonomy );
        
        if ( count( $terms ) > 0 ) {
            echo '<select name="' . esc_attr( $taxonomy ) . '" id="' . esc_attr( $taxonomy ) . '" class="postform">';
            echo '<option value="">' . esc_html( sprintf( __( 'All %s', 'aqualuxe' ), $tax_name ) ) . '</option>';
            
            foreach ( $terms as $term ) {
                printf(
                    '<option value="%1$s" %2$s>%3$s (%4$s)</option>',
                    esc_attr( $term->slug ),
                    ( ( isset( $_GET[$taxonomy] ) && $_GET[$taxonomy] === $term->slug ) ? ' selected="selected"' : '' ),
                    esc_html( $term->name ),
                    esc_html( $term->count )
                );
            }
            
            echo '</select>';
        }
        
        // Tank Size filter
        $taxonomy = 'tank_size';
        $tax_obj = get_taxonomy( $taxonomy );
        $tax_name = $tax_obj->labels->name;
        $terms = get_terms( $taxonomy );
        
        if ( count( $terms ) > 0 ) {
            echo '<select name="' . esc_attr( $taxonomy ) . '" id="' . esc_attr( $taxonomy ) . '" class="postform">';
            echo '<option value="">' . esc_html( sprintf( __( 'All %s', 'aqualuxe' ), $tax_name ) ) . '</option>';
            
            foreach ( $terms as $term ) {
                printf(
                    '<option value="%1$s" %2$s>%3$s (%4$s)</option>',
                    esc_attr( $term->slug ),
                    ( ( isset( $_GET[$taxonomy] ) && $_GET[$taxonomy] === $term->slug ) ? ' selected="selected"' : '' ),
                    esc_html( $term->name ),
                    esc_html( $term->count )
                );
            }
            
            echo '</select>';
        }
    }
}
add_action( 'restrict_manage_posts', 'aqualuxe_add_taxonomy_filters' );

/**
 * Add custom taxonomy terms to the body class
 */
function aqualuxe_add_taxonomy_body_classes( $classes ) {
    if ( is_singular( 'service' ) ) {
        $terms = get_the_terms( get_the_ID(), 'service_category' );
        if ( $terms && ! is_wp_error( $terms ) ) {
            foreach ( $terms as $term ) {
                $classes[] = 'service-category-' . $term->slug;
            }
        }
        
        $terms = get_the_terms( get_the_ID(), 'service_tag' );
        if ( $terms && ! is_wp_error( $terms ) ) {
            foreach ( $terms as $term ) {
                $classes[] = 'service-tag-' . $term->slug;
            }
        }
    }
    
    if ( is_singular( 'event' ) ) {
        $terms = get_the_terms( get_the_ID(), 'event_category' );
        if ( $terms && ! is_wp_error( $terms ) ) {
            foreach ( $terms as $term ) {
                $classes[] = 'event-category-' . $term->slug;
            }
        }
        
        $terms = get_the_terms( get_the_ID(), 'event_tag' );
        if ( $terms && ! is_wp_error( $terms ) ) {
            foreach ( $terms as $term ) {
                $classes[] = 'event-tag-' . $term->slug;
            }
        }
    }
    
    if ( is_singular( 'project' ) ) {
        $terms = get_the_terms( get_the_ID(), 'project_category' );
        if ( $terms && ! is_wp_error( $terms ) ) {
            foreach ( $terms as $term ) {
                $classes[] = 'project-category-' . $term->slug;
            }
        }
        
        $terms = get_the_terms( get_the_ID(), 'project_tag' );
        if ( $terms && ! is_wp_error( $terms ) ) {
            foreach ( $terms as $term ) {
                $classes[] = 'project-tag-' . $term->slug;
            }
        }
    }
    
    if ( class_exists( 'WooCommerce' ) && is_singular( 'product' ) ) {
        $terms = get_the_terms( get_the_ID(), 'fish_species' );
        if ( $terms && ! is_wp_error( $terms ) ) {
            foreach ( $terms as $term ) {
                $classes[] = 'fish-species-' . $term->slug;
            }
        }
        
        $terms = get_the_terms( get_the_ID(), 'water_type' );
        if ( $terms && ! is_wp_error( $terms ) ) {
            foreach ( $terms as $term ) {
                $classes[] = 'water-type-' . $term->slug;
            }
        }
        
        $terms = get_the_terms( get_the_ID(), 'care_level' );
        if ( $terms && ! is_wp_error( $terms ) ) {
            foreach ( $terms as $term ) {
                $classes[] = 'care-level-' . $term->slug;
            }
        }
        
        $terms = get_the_terms( get_the_ID(), 'tank_size' );
        if ( $terms && ! is_wp_error( $terms ) ) {
            foreach ( $terms as $term ) {
                $classes[] = 'tank-size-' . $term->slug;
            }
        }
    }
    
    return $classes;
}
add_filter( 'body_class', 'aqualuxe_add_taxonomy_body_classes' );

/**
 * Add custom taxonomy terms to the post class
 */
function aqualuxe_add_taxonomy_post_classes( $classes ) {
    if ( is_singular( 'service' ) || get_post_type() === 'service' ) {
        $terms = get_the_terms( get_the_ID(), 'service_category' );
        if ( $terms && ! is_wp_error( $terms ) ) {
            foreach ( $terms as $term ) {
                $classes[] = 'service-category-' . $term->slug;
            }
        }
        
        $terms = get_the_terms( get_the_ID(), 'service_tag' );
        if ( $terms && ! is_wp_error( $terms ) ) {
            foreach ( $terms as $term ) {
                $classes[] = 'service-tag-' . $term->slug;
            }
        }
    }
    
    if ( is_singular( 'event' ) || get_post_type() === 'event' ) {
        $terms = get_the_terms( get_the_ID(), 'event_category' );
        if ( $terms && ! is_wp_error( $terms ) ) {
            foreach ( $terms as $term ) {
                $classes[] = 'event-category-' . $term->slug;
            }
        }
        
        $terms = get_the_terms( get_the_ID(), 'event_tag' );
        if ( $terms && ! is_wp_error( $terms ) ) {
            foreach ( $terms as $term ) {
                $classes[] = 'event-tag-' . $term->slug;
            }
        }
    }
    
    if ( is_singular( 'project' ) || get_post_type() === 'project' ) {
        $terms = get_the_terms( get_the_ID(), 'project_category' );
        if ( $terms && ! is_wp_error( $terms ) ) {
            foreach ( $terms as $term ) {
                $classes[] = 'project-category-' . $term->slug;
            }
        }
        
        $terms = get_the_terms( get_the_ID(), 'project_tag' );
        if ( $terms && ! is_wp_error( $terms ) ) {
            foreach ( $terms as $term ) {
                $classes[] = 'project-tag-' . $term->slug;
            }
        }
    }
    
    if ( class_exists( 'WooCommerce' ) && ( is_singular( 'product' ) || get_post_type() === 'product' ) ) {
        $terms = get_the_terms( get_the_ID(), 'fish_species' );
        if ( $terms && ! is_wp_error( $terms ) ) {
            foreach ( $terms as $term ) {
                $classes[] = 'fish-species-' . $term->slug;
            }
        }
        
        $terms = get_the_terms( get_the_ID(), 'water_type' );
        if ( $terms && ! is_wp_error( $terms ) ) {
            foreach ( $terms as $term ) {
                $classes[] = 'water-type-' . $term->slug;
            }
        }
        
        $terms = get_the_terms( get_the_ID(), 'care_level' );
        if ( $terms && ! is_wp_error( $terms ) ) {
            foreach ( $terms as $term ) {
                $classes[] = 'care-level-' . $term->slug;
            }
        }
        
        $terms = get_the_terms( get_the_ID(), 'tank_size' );
        if ( $terms && ! is_wp_error( $terms ) ) {
            foreach ( $terms as $term ) {
                $classes[] = 'tank-size-' . $term->slug;
            }
        }
    }
    
    return $classes;
}
add_filter( 'post_class', 'aqualuxe_add_taxonomy_post_classes' );

/**
 * Add custom taxonomy term meta fields
 */
function aqualuxe_add_taxonomy_meta_fields() {
    // Service Category meta fields
    add_action( 'service_category_add_form_fields', 'aqualuxe_add_service_category_meta_fields' );
    add_action( 'service_category_edit_form_fields', 'aqualuxe_edit_service_category_meta_fields', 10, 2 );
    add_action( 'created_service_category', 'aqualuxe_save_service_category_meta_fields' );
    add_action( 'edited_service_category', 'aqualuxe_save_service_category_meta_fields' );
    
    // Event Category meta fields
    add_action( 'event_category_add_form_fields', 'aqualuxe_add_event_category_meta_fields' );
    add_action( 'event_category_edit_form_fields', 'aqualuxe_edit_event_category_meta_fields', 10, 2 );
    add_action( 'created_event_category', 'aqualuxe_save_event_category_meta_fields' );
    add_action( 'edited_event_category', 'aqualuxe_save_event_category_meta_fields' );
    
    // Project Category meta fields
    add_action( 'project_category_add_form_fields', 'aqualuxe_add_project_category_meta_fields' );
    add_action( 'project_category_edit_form_fields', 'aqualuxe_edit_project_category_meta_fields', 10, 2 );
    add_action( 'created_project_category', 'aqualuxe_save_project_category_meta_fields' );
    add_action( 'edited_project_category', 'aqualuxe_save_project_category_meta_fields' );
    
    // Fish Species meta fields
    if ( class_exists( 'WooCommerce' ) ) {
        add_action( 'fish_species_add_form_fields', 'aqualuxe_add_fish_species_meta_fields' );
        add_action( 'fish_species_edit_form_fields', 'aqualuxe_edit_fish_species_meta_fields', 10, 2 );
        add_action( 'created_fish_species', 'aqualuxe_save_fish_species_meta_fields' );
        add_action( 'edited_fish_species', 'aqualuxe_save_fish_species_meta_fields' );
    }
}
add_action( 'init', 'aqualuxe_add_taxonomy_meta_fields' );

/**
 * Add Service Category meta fields
 */
function aqualuxe_add_service_category_meta_fields() {
    ?>
    <div class="form-field">
        <label for="service_category_icon"><?php esc_html_e( 'Icon', 'aqualuxe' ); ?></label>
        <input type="text" name="service_category_icon" id="service_category_icon" value="" />
        <p class="description"><?php esc_html_e( 'Enter an icon class (e.g. "fa-fish" for Font Awesome).', 'aqualuxe' ); ?></p>
    </div>
    <div class="form-field">
        <label for="service_category_color"><?php esc_html_e( 'Color', 'aqualuxe' ); ?></label>
        <input type="color" name="service_category_color" id="service_category_color" value="#1e40af" />
        <p class="description"><?php esc_html_e( 'Select a color for this category.', 'aqualuxe' ); ?></p>
    </div>
    <?php
}

/**
 * Edit Service Category meta fields
 */
function aqualuxe_edit_service_category_meta_fields( $term, $taxonomy ) {
    $icon = get_term_meta( $term->term_id, 'service_category_icon', true );
    $color = get_term_meta( $term->term_id, 'service_category_color', true );
    if ( empty( $color ) ) {
        $color = '#1e40af';
    }
    ?>
    <tr class="form-field">
        <th scope="row"><label for="service_category_icon"><?php esc_html_e( 'Icon', 'aqualuxe' ); ?></label></th>
        <td>
            <input type="text" name="service_category_icon" id="service_category_icon" value="<?php echo esc_attr( $icon ); ?>" />
            <p class="description"><?php esc_html_e( 'Enter an icon class (e.g. "fa-fish" for Font Awesome).', 'aqualuxe' ); ?></p>
        </td>
    </tr>
    <tr class="form-field">
        <th scope="row"><label for="service_category_color"><?php esc_html_e( 'Color', 'aqualuxe' ); ?></label></th>
        <td>
            <input type="color" name="service_category_color" id="service_category_color" value="<?php echo esc_attr( $color ); ?>" />
            <p class="description"><?php esc_html_e( 'Select a color for this category.', 'aqualuxe' ); ?></p>
        </td>
    </tr>
    <?php
}

/**
 * Save Service Category meta fields
 */
function aqualuxe_save_service_category_meta_fields( $term_id ) {
    if ( isset( $_POST['service_category_icon'] ) ) {
        update_term_meta( $term_id, 'service_category_icon', sanitize_text_field( $_POST['service_category_icon'] ) );
    }
    
    if ( isset( $_POST['service_category_color'] ) ) {
        update_term_meta( $term_id, 'service_category_color', sanitize_hex_color( $_POST['service_category_color'] ) );
    }
}

/**
 * Add Event Category meta fields
 */
function aqualuxe_add_event_category_meta_fields() {
    ?>
    <div class="form-field">
        <label for="event_category_icon"><?php esc_html_e( 'Icon', 'aqualuxe' ); ?></label>
        <input type="text" name="event_category_icon" id="event_category_icon" value="" />
        <p class="description"><?php esc_html_e( 'Enter an icon class (e.g. "fa-calendar" for Font Awesome).', 'aqualuxe' ); ?></p>
    </div>
    <div class="form-field">
        <label for="event_category_color"><?php esc_html_e( 'Color', 'aqualuxe' ); ?></label>
        <input type="color" name="event_category_color" id="event_category_color" value="#1e40af" />
        <p class="description"><?php esc_html_e( 'Select a color for this category.', 'aqualuxe' ); ?></p>
    </div>
    <?php
}

/**
 * Edit Event Category meta fields
 */
function aqualuxe_edit_event_category_meta_fields( $term, $taxonomy ) {
    $icon = get_term_meta( $term->term_id, 'event_category_icon', true );
    $color = get_term_meta( $term->term_id, 'event_category_color', true );
    if ( empty( $color ) ) {
        $color = '#1e40af';
    }
    ?>
    <tr class="form-field">
        <th scope="row"><label for="event_category_icon"><?php esc_html_e( 'Icon', 'aqualuxe' ); ?></label></th>
        <td>
            <input type="text" name="event_category_icon" id="event_category_icon" value="<?php echo esc_attr( $icon ); ?>" />
            <p class="description"><?php esc_html_e( 'Enter an icon class (e.g. "fa-calendar" for Font Awesome).', 'aqualuxe' ); ?></p>
        </td>
    </tr>
    <tr class="form-field">
        <th scope="row"><label for="event_category_color"><?php esc_html_e( 'Color', 'aqualuxe' ); ?></label></th>
        <td>
            <input type="color" name="event_category_color" id="event_category_color" value="<?php echo esc_attr( $color ); ?>" />
            <p class="description"><?php esc_html_e( 'Select a color for this category.', 'aqualuxe' ); ?></p>
        </td>
    </tr>
    <?php
}

/**
 * Save Event Category meta fields
 */
function aqualuxe_save_event_category_meta_fields( $term_id ) {
    if ( isset( $_POST['event_category_icon'] ) ) {
        update_term_meta( $term_id, 'event_category_icon', sanitize_text_field( $_POST['event_category_icon'] ) );
    }
    
    if ( isset( $_POST['event_category_color'] ) ) {
        update_term_meta( $term_id, 'event_category_color', sanitize_hex_color( $_POST['event_category_color'] ) );
    }
}

/**
 * Add Project Category meta fields
 */
function aqualuxe_add_project_category_meta_fields() {
    ?>
    <div class="form-field">
        <label for="project_category_icon"><?php esc_html_e( 'Icon', 'aqualuxe' ); ?></label>
        <input type="text" name="project_category_icon" id="project_category_icon" value="" />
        <p class="description"><?php esc_html_e( 'Enter an icon class (e.g. "fa-project-diagram" for Font Awesome).', 'aqualuxe' ); ?></p>
    </div>
    <div class="form-field">
        <label for="project_category_color"><?php esc_html_e( 'Color', 'aqualuxe' ); ?></label>
        <input type="color" name="project_category_color" id="project_category_color" value="#1e40af" />
        <p class="description"><?php esc_html_e( 'Select a color for this category.', 'aqualuxe' ); ?></p>
    </div>
    <?php
}

/**
 * Edit Project Category meta fields
 */
function aqualuxe_edit_project_category_meta_fields( $term, $taxonomy ) {
    $icon = get_term_meta( $term->term_id, 'project_category_icon', true );
    $color = get_term_meta( $term->term_id, 'project_category_color', true );
    if ( empty( $color ) ) {
        $color = '#1e40af';
    }
    ?>
    <tr class="form-field">
        <th scope="row"><label for="project_category_icon"><?php esc_html_e( 'Icon', 'aqualuxe' ); ?></label></th>
        <td>
            <input type="text" name="project_category_icon" id="project_category_icon" value="<?php echo esc_attr( $icon ); ?>" />
            <p class="description"><?php esc_html_e( 'Enter an icon class (e.g. "fa-project-diagram" for Font Awesome).', 'aqualuxe' ); ?></p>
        </td>
    </tr>
    <tr class="form-field">
        <th scope="row"><label for="project_category_color"><?php esc_html_e( 'Color', 'aqualuxe' ); ?></label></th>
        <td>
            <input type="color" name="project_category_color" id="project_category_color" value="<?php echo esc_attr( $color ); ?>" />
            <p class="description"><?php esc_html_e( 'Select a color for this category.', 'aqualuxe' ); ?></p>
        </td>
    </tr>
    <?php
}

/**
 * Save Project Category meta fields
 */
function aqualuxe_save_project_category_meta_fields( $term_id ) {
    if ( isset( $_POST['project_category_icon'] ) ) {
        update_term_meta( $term_id, 'project_category_icon', sanitize_text_field( $_POST['project_category_icon'] ) );
    }
    
    if ( isset( $_POST['project_category_color'] ) ) {
        update_term_meta( $term_id, 'project_category_color', sanitize_hex_color( $_POST['project_category_color'] ) );
    }
}

/**
 * Add Fish Species meta fields
 */
function aqualuxe_add_fish_species_meta_fields() {
    ?>
    <div class="form-field">
        <label for="fish_species_scientific_name"><?php esc_html_e( 'Scientific Name', 'aqualuxe' ); ?></label>
        <input type="text" name="fish_species_scientific_name" id="fish_species_scientific_name" value="" />
        <p class="description"><?php esc_html_e( 'Enter the scientific name for this fish species.', 'aqualuxe' ); ?></p>
    </div>
    <div class="form-field">
        <label for="fish_species_origin"><?php esc_html_e( 'Origin', 'aqualuxe' ); ?></label>
        <input type="text" name="fish_species_origin" id="fish_species_origin" value="" />
        <p class="description"><?php esc_html_e( 'Enter the geographical origin for this fish species.', 'aqualuxe' ); ?></p>
    </div>
    <div class="form-field">
        <label for="fish_species_temperature"><?php esc_html_e( 'Temperature Range', 'aqualuxe' ); ?></label>
        <input type="text" name="fish_species_temperature" id="fish_species_temperature" value="" />
        <p class="description"><?php esc_html_e( 'Enter the temperature range for this fish species (e.g. "72-78°F").', 'aqualuxe' ); ?></p>
    </div>
    <div class="form-field">
        <label for="fish_species_ph"><?php esc_html_e( 'pH Range', 'aqualuxe' ); ?></label>
        <input type="text" name="fish_species_ph" id="fish_species_ph" value="" />
        <p class="description"><?php esc_html_e( 'Enter the pH range for this fish species (e.g. "6.5-7.5").', 'aqualuxe' ); ?></p>
    </div>
    <?php
}

/**
 * Edit Fish Species meta fields
 */
function aqualuxe_edit_fish_species_meta_fields( $term, $taxonomy ) {
    $scientific_name = get_term_meta( $term->term_id, 'fish_species_scientific_name', true );
    $origin = get_term_meta( $term->term_id, 'fish_species_origin', true );
    $temperature = get_term_meta( $term->term_id, 'fish_species_temperature', true );
    $ph = get_term_meta( $term->term_id, 'fish_species_ph', true );
    ?>
    <tr class="form-field">
        <th scope="row"><label for="fish_species_scientific_name"><?php esc_html_e( 'Scientific Name', 'aqualuxe' ); ?></label></th>
        <td>
            <input type="text" name="fish_species_scientific_name" id="fish_species_scientific_name" value="<?php echo esc_attr( $scientific_name ); ?>" />
            <p class="description"><?php esc_html_e( 'Enter the scientific name for this fish species.', 'aqualuxe' ); ?></p>
        </td>
    </tr>
    <tr class="form-field">
        <th scope="row"><label for="fish_species_origin"><?php esc_html_e( 'Origin', 'aqualuxe' ); ?></label></th>
        <td>
            <input type="text" name="fish_species_origin" id="fish_species_origin" value="<?php echo esc_attr( $origin ); ?>" />
            <p class="description"><?php esc_html_e( 'Enter the geographical origin for this fish species.', 'aqualuxe' ); ?></p>
        </td>
    </tr>
    <tr class="form-field">
        <th scope="row"><label for="fish_species_temperature"><?php esc_html_e( 'Temperature Range', 'aqualuxe' ); ?></label></th>
        <td>
            <input type="text" name="fish_species_temperature" id="fish_species_temperature" value="<?php echo esc_attr( $temperature ); ?>" />
            <p class="description"><?php esc_html_e( 'Enter the temperature range for this fish species (e.g. "72-78°F").', 'aqualuxe' ); ?></p>
        </td>
    </tr>
    <tr class="form-field">
        <th scope="row"><label for="fish_species_ph"><?php esc_html_e( 'pH Range', 'aqualuxe' ); ?></label></th>
        <td>
            <input type="text" name="fish_species_ph" id="fish_species_ph" value="<?php echo esc_attr( $ph ); ?>" />
            <p class="description"><?php esc_html_e( 'Enter the pH range for this fish species (e.g. "6.5-7.5").', 'aqualuxe' ); ?></p>
        </td>
    </tr>
    <?php
}

/**
 * Save Fish Species meta fields
 */
function aqualuxe_save_fish_species_meta_fields( $term_id ) {
    if ( isset( $_POST['fish_species_scientific_name'] ) ) {
        update_term_meta( $term_id, 'fish_species_scientific_name', sanitize_text_field( $_POST['fish_species_scientific_name'] ) );
    }
    
    if ( isset( $_POST['fish_species_origin'] ) ) {
        update_term_meta( $term_id, 'fish_species_origin', sanitize_text_field( $_POST['fish_species_origin'] ) );
    }
    
    if ( isset( $_POST['fish_species_temperature'] ) ) {
        update_term_meta( $term_id, 'fish_species_temperature', sanitize_text_field( $_POST['fish_species_temperature'] ) );
    }
    
    if ( isset( $_POST['fish_species_ph'] ) ) {
        update_term_meta( $term_id, 'fish_species_ph', sanitize_text_field( $_POST['fish_species_ph'] ) );
    }
}

/**
 * Add custom taxonomy columns to admin list tables
 */
function aqualuxe_add_taxonomy_columns( $columns ) {
    $new_columns = array();
    
    // Add columns after the name column
    foreach ( $columns as $key => $value ) {
        $new_columns[$key] = $value;
        
        if ( 'name' === $key ) {
            // Service Category columns
            if ( isset( $_GET['taxonomy'] ) && 'service_category' === $_GET['taxonomy'] ) {
                $new_columns['icon'] = __( 'Icon', 'aqualuxe' );
                $new_columns['color'] = __( 'Color', 'aqualuxe' );
            }
            
            // Event Category columns
            if ( isset( $_GET['taxonomy'] ) && 'event_category' === $_GET['taxonomy'] ) {
                $new_columns['icon'] = __( 'Icon', 'aqualuxe' );
                $new_columns['color'] = __( 'Color', 'aqualuxe' );
            }
            
            // Project Category columns
            if ( isset( $_GET['taxonomy'] ) && 'project_category' === $_GET['taxonomy'] ) {
                $new_columns['icon'] = __( 'Icon', 'aqualuxe' );
                $new_columns['color'] = __( 'Color', 'aqualuxe' );
            }
            
            // Fish Species columns
            if ( isset( $_GET['taxonomy'] ) && 'fish_species' === $_GET['taxonomy'] ) {
                $new_columns['scientific_name'] = __( 'Scientific Name', 'aqualuxe' );
                $new_columns['origin'] = __( 'Origin', 'aqualuxe' );
            }
        }
    }
    
    return $new_columns;
}
add_filter( 'manage_edit-service_category_columns', 'aqualuxe_add_taxonomy_columns' );
add_filter( 'manage_edit-event_category_columns', 'aqualuxe_add_taxonomy_columns' );
add_filter( 'manage_edit-project_category_columns', 'aqualuxe_add_taxonomy_columns' );
add_filter( 'manage_edit-fish_species_columns', 'aqualuxe_add_taxonomy_columns' );

/**
 * Add custom taxonomy column content
 */
function aqualuxe_taxonomy_column_content( $content, $column_name, $term_id ) {
    switch ( $column_name ) {
        case 'icon':
            $taxonomy = $_GET['taxonomy'];
            $icon = '';
            
            if ( 'service_category' === $taxonomy ) {
                $icon = get_term_meta( $term_id, 'service_category_icon', true );
            } elseif ( 'event_category' === $taxonomy ) {
                $icon = get_term_meta( $term_id, 'event_category_icon', true );
            } elseif ( 'project_category' === $taxonomy ) {
                $icon = get_term_meta( $term_id, 'project_category_icon', true );
            }
            
            if ( ! empty( $icon ) ) {
                $content = '<i class="' . esc_attr( $icon ) . '"></i> ' . esc_html( $icon );
            }
            break;
            
        case 'color':
            $taxonomy = $_GET['taxonomy'];
            $color = '';
            
            if ( 'service_category' === $taxonomy ) {
                $color = get_term_meta( $term_id, 'service_category_color', true );
            } elseif ( 'event_category' === $taxonomy ) {
                $color = get_term_meta( $term_id, 'event_category_color', true );
            } elseif ( 'project_category' === $taxonomy ) {
                $color = get_term_meta( $term_id, 'project_category_color', true );
            }
            
            if ( ! empty( $color ) ) {
                $content = '<div style="background-color: ' . esc_attr( $color ) . '; width: 20px; height: 20px; border-radius: 3px;"></div>';
            }
            break;
            
        case 'scientific_name':
            $scientific_name = get_term_meta( $term_id, 'fish_species_scientific_name', true );
            if ( ! empty( $scientific_name ) ) {
                $content = '<em>' . esc_html( $scientific_name ) . '</em>';
            }
            break;
            
        case 'origin':
            $origin = get_term_meta( $term_id, 'fish_species_origin', true );
            if ( ! empty( $origin ) ) {
                $content = esc_html( $origin );
            }
            break;
    }
    
    return $content;
}
add_filter( 'manage_service_category_custom_column', 'aqualuxe_taxonomy_column_content', 10, 3 );
add_filter( 'manage_event_category_custom_column', 'aqualuxe_taxonomy_column_content', 10, 3 );
add_filter( 'manage_project_category_custom_column', 'aqualuxe_taxonomy_column_content', 10, 3 );
add_filter( 'manage_fish_species_custom_column', 'aqualuxe_taxonomy_column_content', 10, 3 );