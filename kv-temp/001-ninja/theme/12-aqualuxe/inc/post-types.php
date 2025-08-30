<?php
/**
 * Custom Post Types for AquaLuxe Theme
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

/**
 * Register custom post types
 */
function aqualuxe_register_post_types() {
    // Register Testimonial post type
    $labels = array(
        'name'               => _x( 'Testimonials', 'post type general name', 'aqualuxe' ),
        'singular_name'      => _x( 'Testimonial', 'post type singular name', 'aqualuxe' ),
        'menu_name'          => _x( 'Testimonials', 'admin menu', 'aqualuxe' ),
        'name_admin_bar'     => _x( 'Testimonial', 'add new on admin bar', 'aqualuxe' ),
        'add_new'            => _x( 'Add New', 'testimonial', 'aqualuxe' ),
        'add_new_item'       => __( 'Add New Testimonial', 'aqualuxe' ),
        'new_item'           => __( 'New Testimonial', 'aqualuxe' ),
        'edit_item'          => __( 'Edit Testimonial', 'aqualuxe' ),
        'view_item'          => __( 'View Testimonial', 'aqualuxe' ),
        'all_items'          => __( 'All Testimonials', 'aqualuxe' ),
        'search_items'       => __( 'Search Testimonials', 'aqualuxe' ),
        'parent_item_colon'  => __( 'Parent Testimonials:', 'aqualuxe' ),
        'not_found'          => __( 'No testimonials found.', 'aqualuxe' ),
        'not_found_in_trash' => __( 'No testimonials found in Trash.', 'aqualuxe' )
    );

    $args = array(
        'labels'             => $labels,
        'description'        => __( 'Customer testimonials for AquaLuxe.', 'aqualuxe' ),
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'testimonial' ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'menu_icon'          => 'dashicons-format-quote',
        'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' )
    );

    register_post_type( 'testimonial', $args );

    // Register Team post type
    $labels = array(
        'name'               => _x( 'Team', 'post type general name', 'aqualuxe' ),
        'singular_name'      => _x( 'Team Member', 'post type singular name', 'aqualuxe' ),
        'menu_name'          => _x( 'Team', 'admin menu', 'aqualuxe' ),
        'name_admin_bar'     => _x( 'Team Member', 'add new on admin bar', 'aqualuxe' ),
        'add_new'            => _x( 'Add New', 'team member', 'aqualuxe' ),
        'add_new_item'       => __( 'Add New Team Member', 'aqualuxe' ),
        'new_item'           => __( 'New Team Member', 'aqualuxe' ),
        'edit_item'          => __( 'Edit Team Member', 'aqualuxe' ),
        'view_item'          => __( 'View Team Member', 'aqualuxe' ),
        'all_items'          => __( 'All Team Members', 'aqualuxe' ),
        'search_items'       => __( 'Search Team Members', 'aqualuxe' ),
        'parent_item_colon'  => __( 'Parent Team Members:', 'aqualuxe' ),
        'not_found'          => __( 'No team members found.', 'aqualuxe' ),
        'not_found_in_trash' => __( 'No team members found in Trash.', 'aqualuxe' )
    );

    $args = array(
        'labels'             => $labels,
        'description'        => __( 'Team members for AquaLuxe.', 'aqualuxe' ),
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'team' ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'menu_icon'          => 'dashicons-groups',
        'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' )
    );

    register_post_type( 'team', $args );

    // Register FAQ post type
    $labels = array(
        'name'               => _x( 'FAQs', 'post type general name', 'aqualuxe' ),
        'singular_name'      => _x( 'FAQ', 'post type singular name', 'aqualuxe' ),
        'menu_name'          => _x( 'FAQs', 'admin menu', 'aqualuxe' ),
        'name_admin_bar'     => _x( 'FAQ', 'add new on admin bar', 'aqualuxe' ),
        'add_new'            => _x( 'Add New', 'faq', 'aqualuxe' ),
        'add_new_item'       => __( 'Add New FAQ', 'aqualuxe' ),
        'new_item'           => __( 'New FAQ', 'aqualuxe' ),
        'edit_item'          => __( 'Edit FAQ', 'aqualuxe' ),
        'view_item'          => __( 'View FAQ', 'aqualuxe' ),
        'all_items'          => __( 'All FAQs', 'aqualuxe' ),
        'search_items'       => __( 'Search FAQs', 'aqualuxe' ),
        'parent_item_colon'  => __( 'Parent FAQs:', 'aqualuxe' ),
        'not_found'          => __( 'No faqs found.', 'aqualuxe' ),
        'not_found_in_trash' => __( 'No faqs found in Trash.', 'aqualuxe' )
    );

    $args = array(
        'labels'             => $labels,
        'description'        => __( 'Frequently asked questions for AquaLuxe.', 'aqualuxe' ),
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'faq' ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'menu_icon'          => 'dashicons-editor-help',
        'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' )
    );

    register_post_type( 'faq', $args );

    // Register Slider post type
    $labels = array(
        'name'               => _x( 'Sliders', 'post type general name', 'aqualuxe' ),
        'singular_name'      => _x( 'Slider', 'post type singular name', 'aqualuxe' ),
        'menu_name'          => _x( 'Sliders', 'admin menu', 'aqualuxe' ),
        'name_admin_bar'     => _x( 'Slider', 'add new on admin bar', 'aqualuxe' ),
        'add_new'            => _x( 'Add New', 'slider', 'aqualuxe' ),
        'add_new_item'       => __( 'Add New Slider', 'aqualuxe' ),
        'new_item'           => __( 'New Slider', 'aqualuxe' ),
        'edit_item'          => __( 'Edit Slider', 'aqualuxe' ),
        'view_item'          => __( 'View Slider', 'aqualuxe' ),
        'all_items'          => __( 'All Sliders', 'aqualuxe' ),
        'search_items'       => __( 'Search Sliders', 'aqualuxe' ),
        'parent_item_colon'  => __( 'Parent Sliders:', 'aqualuxe' ),
        'not_found'          => __( 'No sliders found.', 'aqualuxe' ),
        'not_found_in_trash' => __( 'No sliders found in Trash.', 'aqualuxe' )
    );

    $args = array(
        'labels'             => $labels,
        'description'        => __( 'Sliders for AquaLuxe.', 'aqualuxe' ),
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'slider' ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'menu_icon'          => 'dashicons-images-alt2',
        'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' )
    );

    register_post_type( 'slider', $args );

    // Register Species post type
    $labels = array(
        'name'               => _x( 'Species', 'post type general name', 'aqualuxe' ),
        'singular_name'      => _x( 'Species', 'post type singular name', 'aqualuxe' ),
        'menu_name'          => _x( 'Species', 'admin menu', 'aqualuxe' ),
        'name_admin_bar'     => _x( 'Species', 'add new on admin bar', 'aqualuxe' ),
        'add_new'            => _x( 'Add New', 'species', 'aqualuxe' ),
        'add_new_item'       => __( 'Add New Species', 'aqualuxe' ),
        'new_item'           => __( 'New Species', 'aqualuxe' ),
        'edit_item'          => __( 'Edit Species', 'aqualuxe' ),
        'view_item'          => __( 'View Species', 'aqualuxe' ),
        'all_items'          => __( 'All Species', 'aqualuxe' ),
        'search_items'       => __( 'Search Species', 'aqualuxe' ),
        'parent_item_colon'  => __( 'Parent Species:', 'aqualuxe' ),
        'not_found'          => __( 'No species found.', 'aqualuxe' ),
        'not_found_in_trash' => __( 'No species found in Trash.', 'aqualuxe' )
    );

    $args = array(
        'labels'             => $labels,
        'description'        => __( 'Fish species information for AquaLuxe.', 'aqualuxe' ),
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'species' ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'menu_icon'          => 'dashicons-fish',
        'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' )
    );

    register_post_type( 'species', $args );

    // Register Gallery post type
    $labels = array(
        'name'               => _x( 'Gallery', 'post type general name', 'aqualuxe' ),
        'singular_name'      => _x( 'Gallery', 'post type singular name', 'aqualuxe' ),
        'menu_name'          => _x( 'Gallery', 'admin menu', 'aqualuxe' ),
        'name_admin_bar'     => _x( 'Gallery', 'add new on admin bar', 'aqualuxe' ),
        'add_new'            => _x( 'Add New', 'gallery', 'aqualuxe' ),
        'add_new_item'       => __( 'Add New Gallery', 'aqualuxe' ),
        'new_item'           => __( 'New Gallery', 'aqualuxe' ),
        'edit_item'          => __( 'Edit Gallery', 'aqualuxe' ),
        'view_item'          => __( 'View Gallery', 'aqualuxe' ),
        'all_items'          => __( 'All Galleries', 'aqualuxe' ),
        'search_items'       => __( 'Search Galleries', 'aqualuxe' ),
        'parent_item_colon'  => __( 'Parent Galleries:', 'aqualuxe' ),
        'not_found'          => __( 'No galleries found.', 'aqualuxe' ),
        'not_found_in_trash' => __( 'No galleries found in Trash.', 'aqualuxe' )
    );

    $args = array(
        'labels'             => $labels,
        'description'        => __( 'Photo galleries for AquaLuxe.', 'aqualuxe' ),
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'gallery' ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'menu_icon'          => 'dashicons-format-gallery',
        'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' )
    );

    register_post_type( 'gallery', $args );
}
add_action( 'init', 'aqualuxe_register_post_types' );

/**
 * Register custom taxonomies
 */
function aqualuxe_register_taxonomies() {
    // Register FAQ Category taxonomy
    $labels = array(
        'name'              => _x( 'FAQ Categories', 'taxonomy general name', 'aqualuxe' ),
        'singular_name'     => _x( 'FAQ Category', 'taxonomy singular name', 'aqualuxe' ),
        'search_items'      => __( 'Search FAQ Categories', 'aqualuxe' ),
        'all_items'         => __( 'All FAQ Categories', 'aqualuxe' ),
        'parent_item'       => __( 'Parent FAQ Category', 'aqualuxe' ),
        'parent_item_colon' => __( 'Parent FAQ Category:', 'aqualuxe' ),
        'edit_item'         => __( 'Edit FAQ Category', 'aqualuxe' ),
        'update_item'       => __( 'Update FAQ Category', 'aqualuxe' ),
        'add_new_item'      => __( 'Add New FAQ Category', 'aqualuxe' ),
        'new_item_name'     => __( 'New FAQ Category Name', 'aqualuxe' ),
        'menu_name'         => __( 'FAQ Categories', 'aqualuxe' ),
    );

    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'faq-category' ),
    );

    register_taxonomy( 'faq_category', array( 'faq' ), $args );

    // Register Team Department taxonomy
    $labels = array(
        'name'              => _x( 'Departments', 'taxonomy general name', 'aqualuxe' ),
        'singular_name'     => _x( 'Department', 'taxonomy singular name', 'aqualuxe' ),
        'search_items'      => __( 'Search Departments', 'aqualuxe' ),
        'all_items'         => __( 'All Departments', 'aqualuxe' ),
        'parent_item'       => __( 'Parent Department', 'aqualuxe' ),
        'parent_item_colon' => __( 'Parent Department:', 'aqualuxe' ),
        'edit_item'         => __( 'Edit Department', 'aqualuxe' ),
        'update_item'       => __( 'Update Department', 'aqualuxe' ),
        'add_new_item'      => __( 'Add New Department', 'aqualuxe' ),
        'new_item_name'     => __( 'New Department Name', 'aqualuxe' ),
        'menu_name'         => __( 'Departments', 'aqualuxe' ),
    );

    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'department' ),
    );

    register_taxonomy( 'department', array( 'team' ), $args );

    // Register Testimonial Category taxonomy
    $labels = array(
        'name'              => _x( 'Testimonial Categories', 'taxonomy general name', 'aqualuxe' ),
        'singular_name'     => _x( 'Testimonial Category', 'taxonomy singular name', 'aqualuxe' ),
        'search_items'      => __( 'Search Testimonial Categories', 'aqualuxe' ),
        'all_items'         => __( 'All Testimonial Categories', 'aqualuxe' ),
        'parent_item'       => __( 'Parent Testimonial Category', 'aqualuxe' ),
        'parent_item_colon' => __( 'Parent Testimonial Category:', 'aqualuxe' ),
        'edit_item'         => __( 'Edit Testimonial Category', 'aqualuxe' ),
        'update_item'       => __( 'Update Testimonial Category', 'aqualuxe' ),
        'add_new_item'      => __( 'Add New Testimonial Category', 'aqualuxe' ),
        'new_item_name'     => __( 'New Testimonial Category Name', 'aqualuxe' ),
        'menu_name'         => __( 'Testimonial Categories', 'aqualuxe' ),
    );

    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'testimonial-category' ),
    );

    register_taxonomy( 'testimonial_category', array( 'testimonial' ), $args );

    // Register Slider Category taxonomy
    $labels = array(
        'name'              => _x( 'Slider Categories', 'taxonomy general name', 'aqualuxe' ),
        'singular_name'     => _x( 'Slider Category', 'taxonomy singular name', 'aqualuxe' ),
        'search_items'      => __( 'Search Slider Categories', 'aqualuxe' ),
        'all_items'         => __( 'All Slider Categories', 'aqualuxe' ),
        'parent_item'       => __( 'Parent Slider Category', 'aqualuxe' ),
        'parent_item_colon' => __( 'Parent Slider Category:', 'aqualuxe' ),
        'edit_item'         => __( 'Edit Slider Category', 'aqualuxe' ),
        'update_item'       => __( 'Update Slider Category', 'aqualuxe' ),
        'add_new_item'      => __( 'Add New Slider Category', 'aqualuxe' ),
        'new_item_name'     => __( 'New Slider Category Name', 'aqualuxe' ),
        'menu_name'         => __( 'Slider Categories', 'aqualuxe' ),
    );

    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'slider-category' ),
    );

    register_taxonomy( 'slider_category', array( 'slider' ), $args );

    // Register Species Category taxonomy
    $labels = array(
        'name'              => _x( 'Species Categories', 'taxonomy general name', 'aqualuxe' ),
        'singular_name'     => _x( 'Species Category', 'taxonomy singular name', 'aqualuxe' ),
        'search_items'      => __( 'Search Species Categories', 'aqualuxe' ),
        'all_items'         => __( 'All Species Categories', 'aqualuxe' ),
        'parent_item'       => __( 'Parent Species Category', 'aqualuxe' ),
        'parent_item_colon' => __( 'Parent Species Category:', 'aqualuxe' ),
        'edit_item'         => __( 'Edit Species Category', 'aqualuxe' ),
        'update_item'       => __( 'Update Species Category', 'aqualuxe' ),
        'add_new_item'      => __( 'Add New Species Category', 'aqualuxe' ),
        'new_item_name'     => __( 'New Species Category Name', 'aqualuxe' ),
        'menu_name'         => __( 'Species Categories', 'aqualuxe' ),
    );

    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'species-category' ),
    );

    register_taxonomy( 'species_category', array( 'species' ), $args );

    // Register Species Origin taxonomy
    $labels = array(
        'name'              => _x( 'Origins', 'taxonomy general name', 'aqualuxe' ),
        'singular_name'     => _x( 'Origin', 'taxonomy singular name', 'aqualuxe' ),
        'search_items'      => __( 'Search Origins', 'aqualuxe' ),
        'all_items'         => __( 'All Origins', 'aqualuxe' ),
        'parent_item'       => __( 'Parent Origin', 'aqualuxe' ),
        'parent_item_colon' => __( 'Parent Origin:', 'aqualuxe' ),
        'edit_item'         => __( 'Edit Origin', 'aqualuxe' ),
        'update_item'       => __( 'Update Origin', 'aqualuxe' ),
        'add_new_item'      => __( 'Add New Origin', 'aqualuxe' ),
        'new_item_name'     => __( 'New Origin Name', 'aqualuxe' ),
        'menu_name'         => __( 'Origins', 'aqualuxe' ),
    );

    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'origin' ),
    );

    register_taxonomy( 'origin', array( 'species', 'product' ), $args );

    // Register Gallery Category taxonomy
    $labels = array(
        'name'              => _x( 'Gallery Categories', 'taxonomy general name', 'aqualuxe' ),
        'singular_name'     => _x( 'Gallery Category', 'taxonomy singular name', 'aqualuxe' ),
        'search_items'      => __( 'Search Gallery Categories', 'aqualuxe' ),
        'all_items'         => __( 'All Gallery Categories', 'aqualuxe' ),
        'parent_item'       => __( 'Parent Gallery Category', 'aqualuxe' ),
        'parent_item_colon' => __( 'Parent Gallery Category:', 'aqualuxe' ),
        'edit_item'         => __( 'Edit Gallery Category', 'aqualuxe' ),
        'update_item'       => __( 'Update Gallery Category', 'aqualuxe' ),
        'add_new_item'      => __( 'Add New Gallery Category', 'aqualuxe' ),
        'new_item_name'     => __( 'New Gallery Category Name', 'aqualuxe' ),
        'menu_name'         => __( 'Gallery Categories', 'aqualuxe' ),
    );

    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'gallery-category' ),
    );

    register_taxonomy( 'gallery_category', array( 'gallery' ), $args );

    // Register Species Habitat taxonomy
    $labels = array(
        'name'              => _x( 'Habitats', 'taxonomy general name', 'aqualuxe' ),
        'singular_name'     => _x( 'Habitat', 'taxonomy singular name', 'aqualuxe' ),
        'search_items'      => __( 'Search Habitats', 'aqualuxe' ),
        'all_items'         => __( 'All Habitats', 'aqualuxe' ),
        'parent_item'       => __( 'Parent Habitat', 'aqualuxe' ),
        'parent_item_colon' => __( 'Parent Habitat:', 'aqualuxe' ),
        'edit_item'         => __( 'Edit Habitat', 'aqualuxe' ),
        'update_item'       => __( 'Update Habitat', 'aqualuxe' ),
        'add_new_item'      => __( 'Add New Habitat', 'aqualuxe' ),
        'new_item_name'     => __( 'New Habitat Name', 'aqualuxe' ),
        'menu_name'         => __( 'Habitats', 'aqualuxe' ),
    );

    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'habitat' ),
    );

    register_taxonomy( 'habitat', array( 'species', 'product' ), $args );

    // Register Species Care Level taxonomy
    $labels = array(
        'name'              => _x( 'Care Levels', 'taxonomy general name', 'aqualuxe' ),
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

    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'care-level' ),
    );

    register_taxonomy( 'care_level', array( 'species', 'product' ), $args );

    // Register Species Temperament taxonomy
    $labels = array(
        'name'              => _x( 'Temperaments', 'taxonomy general name', 'aqualuxe' ),
        'singular_name'     => _x( 'Temperament', 'taxonomy singular name', 'aqualuxe' ),
        'search_items'      => __( 'Search Temperaments', 'aqualuxe' ),
        'all_items'         => __( 'All Temperaments', 'aqualuxe' ),
        'parent_item'       => __( 'Parent Temperament', 'aqualuxe' ),
        'parent_item_colon' => __( 'Parent Temperament:', 'aqualuxe' ),
        'edit_item'         => __( 'Edit Temperament', 'aqualuxe' ),
        'update_item'       => __( 'Update Temperament', 'aqualuxe' ),
        'add_new_item'      => __( 'Add New Temperament', 'aqualuxe' ),
        'new_item_name'     => __( 'New Temperament Name', 'aqualuxe' ),
        'menu_name'         => __( 'Temperaments', 'aqualuxe' ),
    );

    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'temperament' ),
    );

    register_taxonomy( 'temperament', array( 'species', 'product' ), $args );

    // Register Species Diet taxonomy
    $labels = array(
        'name'              => _x( 'Diets', 'taxonomy general name', 'aqualuxe' ),
        'singular_name'     => _x( 'Diet', 'taxonomy singular name', 'aqualuxe' ),
        'search_items'      => __( 'Search Diets', 'aqualuxe' ),
        'all_items'         => __( 'All Diets', 'aqualuxe' ),
        'parent_item'       => __( 'Parent Diet', 'aqualuxe' ),
        'parent_item_colon' => __( 'Parent Diet:', 'aqualuxe' ),
        'edit_item'         => __( 'Edit Diet', 'aqualuxe' ),
        'update_item'       => __( 'Update Diet', 'aqualuxe' ),
        'add_new_item'      => __( 'Add New Diet', 'aqualuxe' ),
        'new_item_name'     => __( 'New Diet Name', 'aqualuxe' ),
        'menu_name'         => __( 'Diets', 'aqualuxe' ),
    );

    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'diet' ),
    );

    register_taxonomy( 'diet', array( 'species', 'product' ), $args );
}
add_action( 'init', 'aqualuxe_register_taxonomies' );

/**
 * Add custom meta boxes for post types
 */
function aqualuxe_add_meta_boxes() {
    // Add meta box for testimonials
    add_meta_box(
        'testimonial_details',
        __( 'Testimonial Details', 'aqualuxe' ),
        'aqualuxe_testimonial_details_callback',
        'testimonial',
        'normal',
        'high'
    );

    // Add meta box for team members
    add_meta_box(
        'team_details',
        __( 'Team Member Details', 'aqualuxe' ),
        'aqualuxe_team_details_callback',
        'team',
        'normal',
        'high'
    );

    // Add meta box for species
    add_meta_box(
        'species_details',
        __( 'Species Details', 'aqualuxe' ),
        'aqualuxe_species_details_callback',
        'species',
        'normal',
        'high'
    );

    // Add meta box for sliders
    add_meta_box(
        'slider_details',
        __( 'Slider Details', 'aqualuxe' ),
        'aqualuxe_slider_details_callback',
        'slider',
        'normal',
        'high'
    );

    // Add meta box for gallery
    add_meta_box(
        'gallery_details',
        __( 'Gallery Details', 'aqualuxe' ),
        'aqualuxe_gallery_details_callback',
        'gallery',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'aqualuxe_add_meta_boxes' );

/**
 * Testimonial details meta box callback
 *
 * @param WP_Post $post Post object.
 */
function aqualuxe_testimonial_details_callback( $post ) {
    // Add nonce for security
    wp_nonce_field( 'aqualuxe_testimonial_details', 'aqualuxe_testimonial_details_nonce' );

    // Get meta values
    $client_name = get_post_meta( $post->ID, '_testimonial_client_name', true );
    $client_company = get_post_meta( $post->ID, '_testimonial_client_company', true );
    $client_position = get_post_meta( $post->ID, '_testimonial_client_position', true );
    $client_location = get_post_meta( $post->ID, '_testimonial_client_location', true );
    $client_rating = get_post_meta( $post->ID, '_testimonial_client_rating', true );
    $client_email = get_post_meta( $post->ID, '_testimonial_client_email', true );
    $client_website = get_post_meta( $post->ID, '_testimonial_client_website', true );
    ?>
    <table class="form-table">
        <tr>
            <th><label for="testimonial_client_name"><?php esc_html_e( 'Client Name', 'aqualuxe' ); ?></label></th>
            <td><input type="text" id="testimonial_client_name" name="testimonial_client_name" value="<?php echo esc_attr( $client_name ); ?>" class="regular-text"></td>
        </tr>
        <tr>
            <th><label for="testimonial_client_company"><?php esc_html_e( 'Client Company', 'aqualuxe' ); ?></label></th>
            <td><input type="text" id="testimonial_client_company" name="testimonial_client_company" value="<?php echo esc_attr( $client_company ); ?>" class="regular-text"></td>
        </tr>
        <tr>
            <th><label for="testimonial_client_position"><?php esc_html_e( 'Client Position', 'aqualuxe' ); ?></label></th>
            <td><input type="text" id="testimonial_client_position" name="testimonial_client_position" value="<?php echo esc_attr( $client_position ); ?>" class="regular-text"></td>
        </tr>
        <tr>
            <th><label for="testimonial_client_location"><?php esc_html_e( 'Client Location', 'aqualuxe' ); ?></label></th>
            <td><input type="text" id="testimonial_client_location" name="testimonial_client_location" value="<?php echo esc_attr( $client_location ); ?>" class="regular-text"></td>
        </tr>
        <tr>
            <th><label for="testimonial_client_rating"><?php esc_html_e( 'Client Rating (1-5)', 'aqualuxe' ); ?></label></th>
            <td>
                <select id="testimonial_client_rating" name="testimonial_client_rating">
                    <option value=""><?php esc_html_e( 'Select Rating', 'aqualuxe' ); ?></option>
                    <option value="5" <?php selected( $client_rating, '5' ); ?>>5 - <?php esc_html_e( 'Excellent', 'aqualuxe' ); ?></option>
                    <option value="4" <?php selected( $client_rating, '4' ); ?>>4 - <?php esc_html_e( 'Very Good', 'aqualuxe' ); ?></option>
                    <option value="3" <?php selected( $client_rating, '3' ); ?>>3 - <?php esc_html_e( 'Good', 'aqualuxe' ); ?></option>
                    <option value="2" <?php selected( $client_rating, '2' ); ?>>2 - <?php esc_html_e( 'Fair', 'aqualuxe' ); ?></option>
                    <option value="1" <?php selected( $client_rating, '1' ); ?>>1 - <?php esc_html_e( 'Poor', 'aqualuxe' ); ?></option>
                </select>
            </td>
        </tr>
        <tr>
            <th><label for="testimonial_client_email"><?php esc_html_e( 'Client Email', 'aqualuxe' ); ?></label></th>
            <td><input type="email" id="testimonial_client_email" name="testimonial_client_email" value="<?php echo esc_attr( $client_email ); ?>" class="regular-text"></td>
        </tr>
        <tr>
            <th><label for="testimonial_client_website"><?php esc_html_e( 'Client Website', 'aqualuxe' ); ?></label></th>
            <td><input type="url" id="testimonial_client_website" name="testimonial_client_website" value="<?php echo esc_url( $client_website ); ?>" class="regular-text"></td>
        </tr>
    </table>
    <?php
}

/**
 * Team details meta box callback
 *
 * @param WP_Post $post Post object.
 */
function aqualuxe_team_details_callback( $post ) {
    // Add nonce for security
    wp_nonce_field( 'aqualuxe_team_details', 'aqualuxe_team_details_nonce' );

    // Get meta values
    $position = get_post_meta( $post->ID, '_team_position', true );
    $email = get_post_meta( $post->ID, '_team_email', true );
    $phone = get_post_meta( $post->ID, '_team_phone', true );
    $facebook = get_post_meta( $post->ID, '_team_facebook', true );
    $twitter = get_post_meta( $post->ID, '_team_twitter', true );
    $instagram = get_post_meta( $post->ID, '_team_instagram', true );
    $linkedin = get_post_meta( $post->ID, '_team_linkedin', true );
    ?>
    <table class="form-table">
        <tr>
            <th><label for="team_position"><?php esc_html_e( 'Position', 'aqualuxe' ); ?></label></th>
            <td><input type="text" id="team_position" name="team_position" value="<?php echo esc_attr( $position ); ?>" class="regular-text"></td>
        </tr>
        <tr>
            <th><label for="team_email"><?php esc_html_e( 'Email', 'aqualuxe' ); ?></label></th>
            <td><input type="email" id="team_email" name="team_email" value="<?php echo esc_attr( $email ); ?>" class="regular-text"></td>
        </tr>
        <tr>
            <th><label for="team_phone"><?php esc_html_e( 'Phone', 'aqualuxe' ); ?></label></th>
            <td><input type="text" id="team_phone" name="team_phone" value="<?php echo esc_attr( $phone ); ?>" class="regular-text"></td>
        </tr>
        <tr>
            <th><label for="team_facebook"><?php esc_html_e( 'Facebook', 'aqualuxe' ); ?></label></th>
            <td><input type="url" id="team_facebook" name="team_facebook" value="<?php echo esc_url( $facebook ); ?>" class="regular-text"></td>
        </tr>
        <tr>
            <th><label for="team_twitter"><?php esc_html_e( 'Twitter', 'aqualuxe' ); ?></label></th>
            <td><input type="url" id="team_twitter" name="team_twitter" value="<?php echo esc_url( $twitter ); ?>" class="regular-text"></td>
        </tr>
        <tr>
            <th><label for="team_instagram"><?php esc_html_e( 'Instagram', 'aqualuxe' ); ?></label></th>
            <td><input type="url" id="team_instagram" name="team_instagram" value="<?php echo esc_url( $instagram ); ?>" class="regular-text"></td>
        </tr>
        <tr>
            <th><label for="team_linkedin"><?php esc_html_e( 'LinkedIn', 'aqualuxe' ); ?></label></th>
            <td><input type="url" id="team_linkedin" name="team_linkedin" value="<?php echo esc_url( $linkedin ); ?>" class="regular-text"></td>
        </tr>
    </table>
    <?php
}

/**
 * Species details meta box callback
 *
 * @param WP_Post $post Post object.
 */
function aqualuxe_species_details_callback( $post ) {
    // Add nonce for security
    wp_nonce_field( 'aqualuxe_species_details', 'aqualuxe_species_details_nonce' );

    // Get meta values
    $scientific_name = get_post_meta( $post->ID, '_species_scientific_name', true );
    $common_name = get_post_meta( $post->ID, '_species_common_name', true );
    $size = get_post_meta( $post->ID, '_species_size', true );
    $lifespan = get_post_meta( $post->ID, '_species_lifespan', true );
    $min_tank_size = get_post_meta( $post->ID, '_species_min_tank_size', true );
    $water_temp = get_post_meta( $post->ID, '_species_water_temp', true );
    $water_ph = get_post_meta( $post->ID, '_species_water_ph', true );
    $water_hardness = get_post_meta( $post->ID, '_species_water_hardness', true );
    $breeding_difficulty = get_post_meta( $post->ID, '_species_breeding_difficulty', true );
    ?>
    <table class="form-table">
        <tr>
            <th><label for="species_scientific_name"><?php esc_html_e( 'Scientific Name', 'aqualuxe' ); ?></label></th>
            <td><input type="text" id="species_scientific_name" name="species_scientific_name" value="<?php echo esc_attr( $scientific_name ); ?>" class="regular-text"></td>
        </tr>
        <tr>
            <th><label for="species_common_name"><?php esc_html_e( 'Common Name', 'aqualuxe' ); ?></label></th>
            <td><input type="text" id="species_common_name" name="species_common_name" value="<?php echo esc_attr( $common_name ); ?>" class="regular-text"></td>
        </tr>
        <tr>
            <th><label for="species_size"><?php esc_html_e( 'Size (inches)', 'aqualuxe' ); ?></label></th>
            <td><input type="text" id="species_size" name="species_size" value="<?php echo esc_attr( $size ); ?>" class="regular-text"></td>
        </tr>
        <tr>
            <th><label for="species_lifespan"><?php esc_html_e( 'Lifespan (years)', 'aqualuxe' ); ?></label></th>
            <td><input type="text" id="species_lifespan" name="species_lifespan" value="<?php echo esc_attr( $lifespan ); ?>" class="regular-text"></td>
        </tr>
        <tr>
            <th><label for="species_min_tank_size"><?php esc_html_e( 'Minimum Tank Size (gallons)', 'aqualuxe' ); ?></label></th>
            <td><input type="text" id="species_min_tank_size" name="species_min_tank_size" value="<?php echo esc_attr( $min_tank_size ); ?>" class="regular-text"></td>
        </tr>
        <tr>
            <th><label for="species_water_temp"><?php esc_html_e( 'Water Temperature (°F)', 'aqualuxe' ); ?></label></th>
            <td><input type="text" id="species_water_temp" name="species_water_temp" value="<?php echo esc_attr( $water_temp ); ?>" class="regular-text"></td>
        </tr>
        <tr>
            <th><label for="species_water_ph"><?php esc_html_e( 'Water pH', 'aqualuxe' ); ?></label></th>
            <td><input type="text" id="species_water_ph" name="species_water_ph" value="<?php echo esc_attr( $water_ph ); ?>" class="regular-text"></td>
        </tr>
        <tr>
            <th><label for="species_water_hardness"><?php esc_html_e( 'Water Hardness (dGH)', 'aqualuxe' ); ?></label></th>
            <td><input type="text" id="species_water_hardness" name="species_water_hardness" value="<?php echo esc_attr( $water_hardness ); ?>" class="regular-text"></td>
        </tr>
        <tr>
            <th><label for="species_breeding_difficulty"><?php esc_html_e( 'Breeding Difficulty', 'aqualuxe' ); ?></label></th>
            <td>
                <select id="species_breeding_difficulty" name="species_breeding_difficulty">
                    <option value=""><?php esc_html_e( 'Select Difficulty', 'aqualuxe' ); ?></option>
                    <option value="easy" <?php selected( $breeding_difficulty, 'easy' ); ?>><?php esc_html_e( 'Easy', 'aqualuxe' ); ?></option>
                    <option value="moderate" <?php selected( $breeding_difficulty, 'moderate' ); ?>><?php esc_html_e( 'Moderate', 'aqualuxe' ); ?></option>
                    <option value="difficult" <?php selected( $breeding_difficulty, 'difficult' ); ?>><?php esc_html_e( 'Difficult', 'aqualuxe' ); ?></option>
                    <option value="very_difficult" <?php selected( $breeding_difficulty, 'very_difficult' ); ?>><?php esc_html_e( 'Very Difficult', 'aqualuxe' ); ?></option>
                </select>
            </td>
        </tr>
    </table>
    <?php
}

/**
 * Slider details meta box callback
 *
 * @param WP_Post $post Post object.
 */
function aqualuxe_slider_details_callback( $post ) {
    // Add nonce for security
    wp_nonce_field( 'aqualuxe_slider_details', 'aqualuxe_slider_details_nonce' );

    // Get meta values
    $subtitle = get_post_meta( $post->ID, '_slider_subtitle', true );
    $button_text = get_post_meta( $post->ID, '_slider_button_text', true );
    $button_url = get_post_meta( $post->ID, '_slider_button_url', true );
    $text_color = get_post_meta( $post->ID, '_slider_text_color', true );
    $overlay_color = get_post_meta( $post->ID, '_slider_overlay_color', true );
    $overlay_opacity = get_post_meta( $post->ID, '_slider_overlay_opacity', true );
    ?>
    <table class="form-table">
        <tr>
            <th><label for="slider_subtitle"><?php esc_html_e( 'Subtitle', 'aqualuxe' ); ?></label></th>
            <td><input type="text" id="slider_subtitle" name="slider_subtitle" value="<?php echo esc_attr( $subtitle ); ?>" class="regular-text"></td>
        </tr>
        <tr>
            <th><label for="slider_button_text"><?php esc_html_e( 'Button Text', 'aqualuxe' ); ?></label></th>
            <td><input type="text" id="slider_button_text" name="slider_button_text" value="<?php echo esc_attr( $button_text ); ?>" class="regular-text"></td>
        </tr>
        <tr>
            <th><label for="slider_button_url"><?php esc_html_e( 'Button URL', 'aqualuxe' ); ?></label></th>
            <td><input type="url" id="slider_button_url" name="slider_button_url" value="<?php echo esc_url( $button_url ); ?>" class="regular-text"></td>
        </tr>
        <tr>
            <th><label for="slider_text_color"><?php esc_html_e( 'Text Color', 'aqualuxe' ); ?></label></th>
            <td><input type="text" id="slider_text_color" name="slider_text_color" value="<?php echo esc_attr( $text_color ); ?>" class="color-picker" data-default-color="#ffffff"></td>
        </tr>
        <tr>
            <th><label for="slider_overlay_color"><?php esc_html_e( 'Overlay Color', 'aqualuxe' ); ?></label></th>
            <td><input type="text" id="slider_overlay_color" name="slider_overlay_color" value="<?php echo esc_attr( $overlay_color ); ?>" class="color-picker" data-default-color="#000000"></td>
        </tr>
        <tr>
            <th><label for="slider_overlay_opacity"><?php esc_html_e( 'Overlay Opacity', 'aqualuxe' ); ?></label></th>
            <td>
                <input type="range" id="slider_overlay_opacity" name="slider_overlay_opacity" value="<?php echo esc_attr( $overlay_opacity ? $overlay_opacity : '0.3' ); ?>" min="0" max="1" step="0.1">
                <span class="opacity-value"><?php echo esc_html( $overlay_opacity ? $overlay_opacity : '0.3' ); ?></span>
            </td>
        </tr>
    </table>
    <script>
        jQuery(document).ready(function($) {
            $('.color-picker').wpColorPicker();
            $('#slider_overlay_opacity').on('input', function() {
                $('.opacity-value').text($(this).val());
            });
        });
    </script>
    <?php
}

/**
 * Gallery details meta box callback
 *
 * @param WP_Post $post Post object.
 */
function aqualuxe_gallery_details_callback( $post ) {
    // Add nonce for security
    wp_nonce_field( 'aqualuxe_gallery_details', 'aqualuxe_gallery_details_nonce' );

    // Get meta values
    $gallery_images = get_post_meta( $post->ID, '_gallery_images', true );
    ?>
    <div class="gallery-images">
        <div class="gallery-preview">
            <?php
            if ( $gallery_images ) {
                $image_ids = explode( ',', $gallery_images );
                foreach ( $image_ids as $image_id ) {
                    echo '<div class="gallery-image-preview">';
                    echo wp_get_attachment_image( $image_id, 'thumbnail' );
                    echo '<a href="#" class="remove-image">×</a>';
                    echo '</div>';
                }
            }
            ?>
        </div>
        <input type="hidden" id="gallery_images" name="gallery_images" value="<?php echo esc_attr( $gallery_images ); ?>">
        <button type="button" class="button button-primary add-gallery-images"><?php esc_html_e( 'Add Images', 'aqualuxe' ); ?></button>
    </div>
    <script>
        jQuery(document).ready(function($) {
            // Add gallery images
            $('.add-gallery-images').on('click', function(e) {
                e.preventDefault();
                
                var frame = wp.media({
                    title: '<?php esc_html_e( 'Select Gallery Images', 'aqualuxe' ); ?>',
                    button: {
                        text: '<?php esc_html_e( 'Add to Gallery', 'aqualuxe' ); ?>'
                    },
                    multiple: true
                });
                
                frame.on('select', function() {
                    var attachments = frame.state().get('selection').toJSON();
                    var imageIds = $('#gallery_images').val() ? $('#gallery_images').val().split(',') : [];
                    
                    $.each(attachments, function(i, attachment) {
                        if ($.inArray(attachment.id.toString(), imageIds) === -1) {
                            imageIds.push(attachment.id);
                            $('.gallery-preview').append('<div class="gallery-image-preview">' + 
                                '<img src="' + attachment.sizes.thumbnail.url + '" alt="">' +
                                '<a href="#" class="remove-image">×</a>' +
                                '</div>');
                        }
                    });
                    
                    $('#gallery_images').val(imageIds.join(','));
                });
                
                frame.open();
            });
            
            // Remove gallery image
            $(document).on('click', '.remove-image', function(e) {
                e.preventDefault();
                
                var $this = $(this);
                var $preview = $this.parent();
                var index = $preview.index();
                var imageIds = $('#gallery_images').val().split(',');
                
                imageIds.splice(index, 1);
                $('#gallery_images').val(imageIds.join(','));
                $preview.remove();
            });
        });
    </script>
    <style>
        .gallery-preview {
            display: flex;
            flex-wrap: wrap;
            margin-bottom: 10px;
        }
        .gallery-image-preview {
            position: relative;
            margin: 0 10px 10px 0;
        }
        .gallery-image-preview img {
            display: block;
            max-width: 150px;
            height: auto;
        }
        .remove-image {
            position: absolute;
            top: 0;
            right: 0;
            background: rgba(0, 0, 0, 0.7);
            color: #fff;
            display: block;
            width: 20px;
            height: 20px;
            line-height: 18px;
            text-align: center;
            text-decoration: none;
            font-weight: bold;
        }
    </style>
    <?php
}

/**
 * Save testimonial details meta box
 *
 * @param int $post_id Post ID.
 */
function aqualuxe_save_testimonial_details( $post_id ) {
    // Check if nonce is set
    if ( ! isset( $_POST['aqualuxe_testimonial_details_nonce'] ) ) {
        return;
    }

    // Verify nonce
    if ( ! wp_verify_nonce( $_POST['aqualuxe_testimonial_details_nonce'], 'aqualuxe_testimonial_details' ) ) {
        return;
    }

    // If this is an autosave, our form has not been submitted, so we don't want to do anything
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    // Check the user's permissions
    if ( isset( $_POST['post_type'] ) && 'testimonial' === $_POST['post_type'] ) {
        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }
    }

    // Save meta values
    if ( isset( $_POST['testimonial_client_name'] ) ) {
        update_post_meta( $post_id, '_testimonial_client_name', sanitize_text_field( $_POST['testimonial_client_name'] ) );
    }

    if ( isset( $_POST['testimonial_client_company'] ) ) {
        update_post_meta( $post_id, '_testimonial_client_company', sanitize_text_field( $_POST['testimonial_client_company'] ) );
    }

    if ( isset( $_POST['testimonial_client_position'] ) ) {
        update_post_meta( $post_id, '_testimonial_client_position', sanitize_text_field( $_POST['testimonial_client_position'] ) );
    }

    if ( isset( $_POST['testimonial_client_location'] ) ) {
        update_post_meta( $post_id, '_testimonial_client_location', sanitize_text_field( $_POST['testimonial_client_location'] ) );
    }

    if ( isset( $_POST['testimonial_client_rating'] ) ) {
        update_post_meta( $post_id, '_testimonial_client_rating', sanitize_text_field( $_POST['testimonial_client_rating'] ) );
    }

    if ( isset( $_POST['testimonial_client_email'] ) ) {
        update_post_meta( $post_id, '_testimonial_client_email', sanitize_email( $_POST['testimonial_client_email'] ) );
    }

    if ( isset( $_POST['testimonial_client_website'] ) ) {
        update_post_meta( $post_id, '_testimonial_client_website', esc_url_raw( $_POST['testimonial_client_website'] ) );
    }
}
add_action( 'save_post_testimonial', 'aqualuxe_save_testimonial_details' );

/**
 * Save team details meta box
 *
 * @param int $post_id Post ID.
 */
function aqualuxe_save_team_details( $post_id ) {
    // Check if nonce is set
    if ( ! isset( $_POST['aqualuxe_team_details_nonce'] ) ) {
        return;
    }

    // Verify nonce
    if ( ! wp_verify_nonce( $_POST['aqualuxe_team_details_nonce'], 'aqualuxe_team_details' ) ) {
        return;
    }

    // If this is an autosave, our form has not been submitted, so we don't want to do anything
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    // Check the user's permissions
    if ( isset( $_POST['post_type'] ) && 'team' === $_POST['post_type'] ) {
        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }
    }

    // Save meta values
    if ( isset( $_POST['team_position'] ) ) {
        update_post_meta( $post_id, '_team_position', sanitize_text_field( $_POST['team_position'] ) );
    }

    if ( isset( $_POST['team_email'] ) ) {
        update_post_meta( $post_id, '_team_email', sanitize_email( $_POST['team_email'] ) );
    }

    if ( isset( $_POST['team_phone'] ) ) {
        update_post_meta( $post_id, '_team_phone', sanitize_text_field( $_POST['team_phone'] ) );
    }

    if ( isset( $_POST['team_facebook'] ) ) {
        update_post_meta( $post_id, '_team_facebook', esc_url_raw( $_POST['team_facebook'] ) );
    }

    if ( isset( $_POST['team_twitter'] ) ) {
        update_post_meta( $post_id, '_team_twitter', esc_url_raw( $_POST['team_twitter'] ) );
    }

    if ( isset( $_POST['team_instagram'] ) ) {
        update_post_meta( $post_id, '_team_instagram', esc_url_raw( $_POST['team_instagram'] ) );
    }

    if ( isset( $_POST['team_linkedin'] ) ) {
        update_post_meta( $post_id, '_team_linkedin', esc_url_raw( $_POST['team_linkedin'] ) );
    }
}
add_action( 'save_post_team', 'aqualuxe_save_team_details' );

/**
 * Save species details meta box
 *
 * @param int $post_id Post ID.
 */
function aqualuxe_save_species_details( $post_id ) {
    // Check if nonce is set
    if ( ! isset( $_POST['aqualuxe_species_details_nonce'] ) ) {
        return;
    }

    // Verify nonce
    if ( ! wp_verify_nonce( $_POST['aqualuxe_species_details_nonce'], 'aqualuxe_species_details' ) ) {
        return;
    }

    // If this is an autosave, our form has not been submitted, so we don't want to do anything
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    // Check the user's permissions
    if ( isset( $_POST['post_type'] ) && 'species' === $_POST['post_type'] ) {
        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }
    }

    // Save meta values
    if ( isset( $_POST['species_scientific_name'] ) ) {
        update_post_meta( $post_id, '_species_scientific_name', sanitize_text_field( $_POST['species_scientific_name'] ) );
    }

    if ( isset( $_POST['species_common_name'] ) ) {
        update_post_meta( $post_id, '_species_common_name', sanitize_text_field( $_POST['species_common_name'] ) );
    }

    if ( isset( $_POST['species_size'] ) ) {
        update_post_meta( $post_id, '_species_size', sanitize_text_field( $_POST['species_size'] ) );
    }

    if ( isset( $_POST['species_lifespan'] ) ) {
        update_post_meta( $post_id, '_species_lifespan', sanitize_text_field( $_POST['species_lifespan'] ) );
    }

    if ( isset( $_POST['species_min_tank_size'] ) ) {
        update_post_meta( $post_id, '_species_min_tank_size', sanitize_text_field( $_POST['species_min_tank_size'] ) );
    }

    if ( isset( $_POST['species_water_temp'] ) ) {
        update_post_meta( $post_id, '_species_water_temp', sanitize_text_field( $_POST['species_water_temp'] ) );
    }

    if ( isset( $_POST['species_water_ph'] ) ) {
        update_post_meta( $post_id, '_species_water_ph', sanitize_text_field( $_POST['species_water_ph'] ) );
    }

    if ( isset( $_POST['species_water_hardness'] ) ) {
        update_post_meta( $post_id, '_species_water_hardness', sanitize_text_field( $_POST['species_water_hardness'] ) );
    }

    if ( isset( $_POST['species_breeding_difficulty'] ) ) {
        update_post_meta( $post_id, '_species_breeding_difficulty', sanitize_text_field( $_POST['species_breeding_difficulty'] ) );
    }
}
add_action( 'save_post_species', 'aqualuxe_save_species_details' );

/**
 * Save slider details meta box
 *
 * @param int $post_id Post ID.
 */
function aqualuxe_save_slider_details( $post_id ) {
    // Check if nonce is set
    if ( ! isset( $_POST['aqualuxe_slider_details_nonce'] ) ) {
        return;
    }

    // Verify nonce
    if ( ! wp_verify_nonce( $_POST['aqualuxe_slider_details_nonce'], 'aqualuxe_slider_details' ) ) {
        return;
    }

    // If this is an autosave, our form has not been submitted, so we don't want to do anything
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    // Check the user's permissions
    if ( isset( $_POST['post_type'] ) && 'slider' === $_POST['post_type'] ) {
        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }
    }

    // Save meta values
    if ( isset( $_POST['slider_subtitle'] ) ) {
        update_post_meta( $post_id, '_slider_subtitle', sanitize_text_field( $_POST['slider_subtitle'] ) );
    }

    if ( isset( $_POST['slider_button_text'] ) ) {
        update_post_meta( $post_id, '_slider_button_text', sanitize_text_field( $_POST['slider_button_text'] ) );
    }

    if ( isset( $_POST['slider_button_url'] ) ) {
        update_post_meta( $post_id, '_slider_button_url', esc_url_raw( $_POST['slider_button_url'] ) );
    }

    if ( isset( $_POST['slider_text_color'] ) ) {
        update_post_meta( $post_id, '_slider_text_color', sanitize_hex_color( $_POST['slider_text_color'] ) );
    }

    if ( isset( $_POST['slider_overlay_color'] ) ) {
        update_post_meta( $post_id, '_slider_overlay_color', sanitize_hex_color( $_POST['slider_overlay_color'] ) );
    }

    if ( isset( $_POST['slider_overlay_opacity'] ) ) {
        update_post_meta( $post_id, '_slider_overlay_opacity', sanitize_text_field( $_POST['slider_overlay_opacity'] ) );
    }
}
add_action( 'save_post_slider', 'aqualuxe_save_slider_details' );

/**
 * Save gallery details meta box
 *
 * @param int $post_id Post ID.
 */
function aqualuxe_save_gallery_details( $post_id ) {
    // Check if nonce is set
    if ( ! isset( $_POST['aqualuxe_gallery_details_nonce'] ) ) {
        return;
    }

    // Verify nonce
    if ( ! wp_verify_nonce( $_POST['aqualuxe_gallery_details_nonce'], 'aqualuxe_gallery_details' ) ) {
        return;
    }

    // If this is an autosave, our form has not been submitted, so we don't want to do anything
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    // Check the user's permissions
    if ( isset( $_POST['post_type'] ) && 'gallery' === $_POST['post_type'] ) {
        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }
    }

    // Save meta values
    if ( isset( $_POST['gallery_images'] ) ) {
        update_post_meta( $post_id, '_gallery_images', sanitize_text_field( $_POST['gallery_images'] ) );
    }
}
add_action( 'save_post_gallery', 'aqualuxe_save_gallery_details' );

/**
 * Add custom columns to testimonial post type
 */
function aqualuxe_testimonial_columns( $columns ) {
    $columns = array(
        'cb' => $columns['cb'],
        'title' => __( 'Title', 'aqualuxe' ),
        'client_name' => __( 'Client Name', 'aqualuxe' ),
        'client_company' => __( 'Company', 'aqualuxe' ),
        'client_rating' => __( 'Rating', 'aqualuxe' ),
        'thumbnail' => __( 'Thumbnail', 'aqualuxe' ),
        'testimonial_category' => __( 'Categories', 'aqualuxe' ),
        'date' => $columns['date'],
    );
    return $columns;
}
add_filter( 'manage_testimonial_posts_columns', 'aqualuxe_testimonial_columns' );

/**
 * Add custom column content to testimonial post type
 */
function aqualuxe_testimonial_column_content( $column, $post_id ) {
    switch ( $column ) {
        case 'client_name':
            echo esc_html( get_post_meta( $post_id, '_testimonial_client_name', true ) );
            break;
        case 'client_company':
            echo esc_html( get_post_meta( $post_id, '_testimonial_client_company', true ) );
            break;
        case 'client_rating':
            $rating = get_post_meta( $post_id, '_testimonial_client_rating', true );
            if ( $rating ) {
                for ( $i = 1; $i <= 5; $i++ ) {
                    if ( $i <= $rating ) {
                        echo '<span class="dashicons dashicons-star-filled" style="color: #ffb900;"></span>';
                    } else {
                        echo '<span class="dashicons dashicons-star-empty" style="color: #ffb900;"></span>';
                    }
                }
            }
            break;
        case 'thumbnail':
            if ( has_post_thumbnail( $post_id ) ) {
                echo get_the_post_thumbnail( $post_id, array( 50, 50 ) );
            } else {
                echo '—';
            }
            break;
        case 'testimonial_category':
            $terms = get_the_terms( $post_id, 'testimonial_category' );
            if ( ! empty( $terms ) ) {
                $term_names = array();
                foreach ( $terms as $term ) {
                    $term_names[] = $term->name;
                }
                echo esc_html( implode( ', ', $term_names ) );
            } else {
                echo '—';
            }
            break;
    }
}
add_action( 'manage_testimonial_posts_custom_column', 'aqualuxe_testimonial_column_content', 10, 2 );

/**
 * Add custom columns to team post type
 */
function aqualuxe_team_columns( $columns ) {
    $columns = array(
        'cb' => $columns['cb'],
        'title' => __( 'Name', 'aqualuxe' ),
        'position' => __( 'Position', 'aqualuxe' ),
        'thumbnail' => __( 'Photo', 'aqualuxe' ),
        'department' => __( 'Department', 'aqualuxe' ),
        'date' => $columns['date'],
    );
    return $columns;
}
add_filter( 'manage_team_posts_columns', 'aqualuxe_team_columns' );

/**
 * Add custom column content to team post type
 */
function aqualuxe_team_column_content( $column, $post_id ) {
    switch ( $column ) {
        case 'position':
            echo esc_html( get_post_meta( $post_id, '_team_position', true ) );
            break;
        case 'thumbnail':
            if ( has_post_thumbnail( $post_id ) ) {
                echo get_the_post_thumbnail( $post_id, array( 50, 50 ) );
            } else {
                echo '—';
            }
            break;
        case 'department':
            $terms = get_the_terms( $post_id, 'department' );
            if ( ! empty( $terms ) ) {
                $term_names = array();
                foreach ( $terms as $term ) {
                    $term_names[] = $term->name;
                }
                echo esc_html( implode( ', ', $term_names ) );
            } else {
                echo '—';
            }
            break;
    }
}
add_action( 'manage_team_posts_custom_column', 'aqualuxe_team_column_content', 10, 2 );

/**
 * Add custom columns to species post type
 */
function aqualuxe_species_columns( $columns ) {
    $columns = array(
        'cb' => $columns['cb'],
        'title' => __( 'Name', 'aqualuxe' ),
        'scientific_name' => __( 'Scientific Name', 'aqualuxe' ),
        'thumbnail' => __( 'Photo', 'aqualuxe' ),
        'species_category' => __( 'Category', 'aqualuxe' ),
        'origin' => __( 'Origin', 'aqualuxe' ),
        'care_level' => __( 'Care Level', 'aqualuxe' ),
        'date' => $columns['date'],
    );
    return $columns;
}
add_filter( 'manage_species_posts_columns', 'aqualuxe_species_columns' );

/**
 * Add custom column content to species post type
 */
function aqualuxe_species_column_content( $column, $post_id ) {
    switch ( $column ) {
        case 'scientific_name':
            echo esc_html( get_post_meta( $post_id, '_species_scientific_name', true ) );
            break;
        case 'thumbnail':
            if ( has_post_thumbnail( $post_id ) ) {
                echo get_the_post_thumbnail( $post_id, array( 50, 50 ) );
            } else {
                echo '—';
            }
            break;
        case 'species_category':
            $terms = get_the_terms( $post_id, 'species_category' );
            if ( ! empty( $terms ) ) {
                $term_names = array();
                foreach ( $terms as $term ) {
                    $term_names[] = $term->name;
                }
                echo esc_html( implode( ', ', $term_names ) );
            } else {
                echo '—';
            }
            break;
        case 'origin':
            $terms = get_the_terms( $post_id, 'origin' );
            if ( ! empty( $terms ) ) {
                $term_names = array();
                foreach ( $terms as $term ) {
                    $term_names[] = $term->name;
                }
                echo esc_html( implode( ', ', $term_names ) );
            } else {
                echo '—';
            }
            break;
        case 'care_level':
            $terms = get_the_terms( $post_id, 'care_level' );
            if ( ! empty( $terms ) ) {
                $term_names = array();
                foreach ( $terms as $term ) {
                    $term_names[] = $term->name;
                }
                echo esc_html( implode( ', ', $term_names ) );
            } else {
                echo '—';
            }
            break;
    }
}
add_action( 'manage_species_posts_custom_column', 'aqualuxe_species_column_content', 10, 2 );

/**
 * Add custom columns to slider post type
 */
function aqualuxe_slider_columns( $columns ) {
    $columns = array(
        'cb' => $columns['cb'],
        'title' => __( 'Title', 'aqualuxe' ),
        'subtitle' => __( 'Subtitle', 'aqualuxe' ),
        'thumbnail' => __( 'Image', 'aqualuxe' ),
        'slider_category' => __( 'Category', 'aqualuxe' ),
        'date' => $columns['date'],
    );
    return $columns;
}
add_filter( 'manage_slider_posts_columns', 'aqualuxe_slider_columns' );

/**
 * Add custom column content to slider post type
 */
function aqualuxe_slider_column_content( $column, $post_id ) {
    switch ( $column ) {
        case 'subtitle':
            echo esc_html( get_post_meta( $post_id, '_slider_subtitle', true ) );
            break;
        case 'thumbnail':
            if ( has_post_thumbnail( $post_id ) ) {
                echo get_the_post_thumbnail( $post_id, array( 100, 50 ) );
            } else {
                echo '—';
            }
            break;
        case 'slider_category':
            $terms = get_the_terms( $post_id, 'slider_category' );
            if ( ! empty( $terms ) ) {
                $term_names = array();
                foreach ( $terms as $term ) {
                    $term_names[] = $term->name;
                }
                echo esc_html( implode( ', ', $term_names ) );
            } else {
                echo '—';
            }
            break;
    }
}
add_action( 'manage_slider_posts_custom_column', 'aqualuxe_slider_column_content', 10, 2 );

/**
 * Add custom columns to gallery post type
 */
function aqualuxe_gallery_columns( $columns ) {
    $columns = array(
        'cb' => $columns['cb'],
        'title' => __( 'Title', 'aqualuxe' ),
        'thumbnail' => __( 'Featured Image', 'aqualuxe' ),
        'images_count' => __( 'Images Count', 'aqualuxe' ),
        'gallery_category' => __( 'Category', 'aqualuxe' ),
        'date' => $columns['date'],
    );
    return $columns;
}
add_filter( 'manage_gallery_posts_columns', 'aqualuxe_gallery_columns' );

/**
 * Add custom column content to gallery post type
 */
function aqualuxe_gallery_column_content( $column, $post_id ) {
    switch ( $column ) {
        case 'thumbnail':
            if ( has_post_thumbnail( $post_id ) ) {
                echo get_the_post_thumbnail( $post_id, array( 100, 50 ) );
            } else {
                echo '—';
            }
            break;
        case 'images_count':
            $gallery_images = get_post_meta( $post_id, '_gallery_images', true );
            if ( $gallery_images ) {
                $image_ids = explode( ',', $gallery_images );
                echo count( $image_ids );
            } else {
                echo '0';
            }
            break;
        case 'gallery_category':
            $terms = get_the_terms( $post_id, 'gallery_category' );
            if ( ! empty( $terms ) ) {
                $term_names = array();
                foreach ( $terms as $term ) {
                    $term_names[] = $term->name;
                }
                echo esc_html( implode( ', ', $term_names ) );
            } else {
                echo '—';
            }
            break;
    }
}
add_action( 'manage_gallery_posts_custom_column', 'aqualuxe_gallery_column_content', 10, 2 );

/**
 * Make custom columns sortable
 */
function aqualuxe_sortable_columns( $columns ) {
    $columns['client_name'] = 'client_name';
    $columns['client_company'] = 'client_company';
    $columns['client_rating'] = 'client_rating';
    $columns['position'] = 'position';
    $columns['scientific_name'] = 'scientific_name';
    $columns['subtitle'] = 'subtitle';
    $columns['images_count'] = 'images_count';
    return $columns;
}
add_filter( 'manage_edit-testimonial_sortable_columns', 'aqualuxe_sortable_columns' );
add_filter( 'manage_edit-team_sortable_columns', 'aqualuxe_sortable_columns' );
add_filter( 'manage_edit-species_sortable_columns', 'aqualuxe_sortable_columns' );
add_filter( 'manage_edit-slider_sortable_columns', 'aqualuxe_sortable_columns' );
add_filter( 'manage_edit-gallery_sortable_columns', 'aqualuxe_sortable_columns' );

/**
 * Sort custom columns
 */
function aqualuxe_sort_custom_columns( $query ) {
    if ( ! is_admin() ) {
        return;
    }

    $orderby = $query->get( 'orderby' );

    if ( 'client_name' === $orderby ) {
        $query->set( 'meta_key', '_testimonial_client_name' );
        $query->set( 'orderby', 'meta_value' );
    }

    if ( 'client_company' === $orderby ) {
        $query->set( 'meta_key', '_testimonial_client_company' );
        $query->set( 'orderby', 'meta_value' );
    }

    if ( 'client_rating' === $orderby ) {
        $query->set( 'meta_key', '_testimonial_client_rating' );
        $query->set( 'orderby', 'meta_value_num' );
    }

    if ( 'position' === $orderby ) {
        $query->set( 'meta_key', '_team_position' );
        $query->set( 'orderby', 'meta_value' );
    }

    if ( 'scientific_name' === $orderby ) {
        $query->set( 'meta_key', '_species_scientific_name' );
        $query->set( 'orderby', 'meta_value' );
    }

    if ( 'subtitle' === $orderby ) {
        $query->set( 'meta_key', '_slider_subtitle' );
        $query->set( 'orderby', 'meta_value' );
    }

    if ( 'images_count' === $orderby ) {
        $query->set( 'meta_key', '_gallery_images' );
        $query->set( 'orderby', 'meta_value' );
    }
}
add_action( 'pre_get_posts', 'aqualuxe_sort_custom_columns' );

/**
 * Add custom meta boxes to WooCommerce products
 */
function aqualuxe_add_product_meta_boxes() {
    add_meta_box(
        'product_aquatic_details',
        __( 'Aquatic Details', 'aqualuxe' ),
        'aqualuxe_product_aquatic_details_callback',
        'product',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'aqualuxe_add_product_meta_boxes' );

/**
 * Product aquatic details meta box callback
 *
 * @param WP_Post $post Post object.
 */
function aqualuxe_product_aquatic_details_callback( $post ) {
    // Add nonce for security
    wp_nonce_field( 'aqualuxe_product_aquatic_details', 'aqualuxe_product_aquatic_details_nonce' );

    // Get meta values
    $scientific_name = get_post_meta( $post->ID, '_product_scientific_name', true );
    $common_name = get_post_meta( $post->ID, '_product_common_name', true );
    $size = get_post_meta( $post->ID, '_product_size', true );
    $lifespan = get_post_meta( $post->ID, '_product_lifespan', true );
    $min_tank_size = get_post_meta( $post->ID, '_product_min_tank_size', true );
    $water_temp = get_post_meta( $post->ID, '_product_water_temp', true );
    $water_ph = get_post_meta( $post->ID, '_product_water_ph', true );
    $water_hardness = get_post_meta( $post->ID, '_product_water_hardness', true );
    $breeding_difficulty = get_post_meta( $post->ID, '_product_breeding_difficulty', true );
    $care_instructions = get_post_meta( $post->ID, '_product_care_instructions', true );
    ?>
    <table class="form-table">
        <tr>
            <th><label for="product_scientific_name"><?php esc_html_e( 'Scientific Name', 'aqualuxe' ); ?></label></th>
            <td><input type="text" id="product_scientific_name" name="product_scientific_name" value="<?php echo esc_attr( $scientific_name ); ?>" class="regular-text"></td>
        </tr>
        <tr>
            <th><label for="product_common_name"><?php esc_html_e( 'Common Name', 'aqualuxe' ); ?></label></th>
            <td><input type="text" id="product_common_name" name="product_common_name" value="<?php echo esc_attr( $common_name ); ?>" class="regular-text"></td>
        </tr>
        <tr>
            <th><label for="product_size"><?php esc_html_e( 'Size (inches)', 'aqualuxe' ); ?></label></th>
            <td><input type="text" id="product_size" name="product_size" value="<?php echo esc_attr( $size ); ?>" class="regular-text"></td>
        </tr>
        <tr>
            <th><label for="product_lifespan"><?php esc_html_e( 'Lifespan (years)', 'aqualuxe' ); ?></label></th>
            <td><input type="text" id="product_lifespan" name="product_lifespan" value="<?php echo esc_attr( $lifespan ); ?>" class="regular-text"></td>
        </tr>
        <tr>
            <th><label for="product_min_tank_size"><?php esc_html_e( 'Minimum Tank Size (gallons)', 'aqualuxe' ); ?></label></th>
            <td><input type="text" id="product_min_tank_size" name="product_min_tank_size" value="<?php echo esc_attr( $min_tank_size ); ?>" class="regular-text"></td>
        </tr>
        <tr>
            <th><label for="product_water_temp"><?php esc_html_e( 'Water Temperature (°F)', 'aqualuxe' ); ?></label></th>
            <td><input type="text" id="product_water_temp" name="product_water_temp" value="<?php echo esc_attr( $water_temp ); ?>" class="regular-text"></td>
        </tr>
        <tr>
            <th><label for="product_water_ph"><?php esc_html_e( 'Water pH', 'aqualuxe' ); ?></label></th>
            <td><input type="text" id="product_water_ph" name="product_water_ph" value="<?php echo esc_attr( $water_ph ); ?>" class="regular-text"></td>
        </tr>
        <tr>
            <th><label for="product_water_hardness"><?php esc_html_e( 'Water Hardness (dGH)', 'aqualuxe' ); ?></label></th>
            <td><input type="text" id="product_water_hardness" name="product_water_hardness" value="<?php echo esc_attr( $water_hardness ); ?>" class="regular-text"></td>
        </tr>
        <tr>
            <th><label for="product_breeding_difficulty"><?php esc_html_e( 'Breeding Difficulty', 'aqualuxe' ); ?></label></th>
            <td>
                <select id="product_breeding_difficulty" name="product_breeding_difficulty">
                    <option value=""><?php esc_html_e( 'Select Difficulty', 'aqualuxe' ); ?></option>
                    <option value="easy" <?php selected( $breeding_difficulty, 'easy' ); ?>><?php esc_html_e( 'Easy', 'aqualuxe' ); ?></option>
                    <option value="moderate" <?php selected( $breeding_difficulty, 'moderate' ); ?>><?php esc_html_e( 'Moderate', 'aqualuxe' ); ?></option>
                    <option value="difficult" <?php selected( $breeding_difficulty, 'difficult' ); ?>><?php esc_html_e( 'Difficult', 'aqualuxe' ); ?></option>
                    <option value="very_difficult" <?php selected( $breeding_difficulty, 'very_difficult' ); ?>><?php esc_html_e( 'Very Difficult', 'aqualuxe' ); ?></option>
                </select>
            </td>
        </tr>
        <tr>
            <th><label for="product_care_instructions"><?php esc_html_e( 'Care Instructions', 'aqualuxe' ); ?></label></th>
            <td><textarea id="product_care_instructions" name="product_care_instructions" rows="5" class="large-text"><?php echo esc_textarea( $care_instructions ); ?></textarea></td>
        </tr>
    </table>
    <?php
}

/**
 * Save product aquatic details meta box
 *
 * @param int $post_id Post ID.
 */
function aqualuxe_save_product_aquatic_details( $post_id ) {
    // Check if nonce is set
    if ( ! isset( $_POST['aqualuxe_product_aquatic_details_nonce'] ) ) {
        return;
    }

    // Verify nonce
    if ( ! wp_verify_nonce( $_POST['aqualuxe_product_aquatic_details_nonce'], 'aqualuxe_product_aquatic_details' ) ) {
        return;
    }

    // If this is an autosave, our form has not been submitted, so we don't want to do anything
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    // Check the user's permissions
    if ( isset( $_POST['post_type'] ) && 'product' === $_POST['post_type'] ) {
        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }
    }

    // Save meta values
    if ( isset( $_POST['product_scientific_name'] ) ) {
        update_post_meta( $post_id, '_product_scientific_name', sanitize_text_field( $_POST['product_scientific_name'] ) );
    }

    if ( isset( $_POST['product_common_name'] ) ) {
        update_post_meta( $post_id, '_product_common_name', sanitize_text_field( $_POST['product_common_name'] ) );
    }

    if ( isset( $_POST['product_size'] ) ) {
        update_post_meta( $post_id, '_product_size', sanitize_text_field( $_POST['product_size'] ) );
    }

    if ( isset( $_POST['product_lifespan'] ) ) {
        update_post_meta( $post_id, '_product_lifespan', sanitize_text_field( $_POST['product_lifespan'] ) );
    }

    if ( isset( $_POST['product_min_tank_size'] ) ) {
        update_post_meta( $post_id, '_product_min_tank_size', sanitize_text_field( $_POST['product_min_tank_size'] ) );
    }

    if ( isset( $_POST['product_water_temp'] ) ) {
        update_post_meta( $post_id, '_product_water_temp', sanitize_text_field( $_POST['product_water_temp'] ) );
    }

    if ( isset( $_POST['product_water_ph'] ) ) {
        update_post_meta( $post_id, '_product_water_ph', sanitize_text_field( $_POST['product_water_ph'] ) );
    }

    if ( isset( $_POST['product_water_hardness'] ) ) {
        update_post_meta( $post_id, '_product_water_hardness', sanitize_text_field( $_POST['product_water_hardness'] ) );
    }

    if ( isset( $_POST['product_breeding_difficulty'] ) ) {
        update_post_meta( $post_id, '_product_breeding_difficulty', sanitize_text_field( $_POST['product_breeding_difficulty'] ) );
    }

    if ( isset( $_POST['product_care_instructions'] ) ) {
        update_post_meta( $post_id, '_product_care_instructions', sanitize_textarea_field( $_POST['product_care_instructions'] ) );
    }
}
add_action( 'save_post_product', 'aqualuxe_save_product_aquatic_details' );

/**
 * Add custom product tabs
 *
 * @param array $tabs Product tabs.
 * @return array
 */
function aqualuxe_product_tabs( $tabs ) {
    global $post;

    // Add care instructions tab
    $care_instructions = get_post_meta( $post->ID, '_product_care_instructions', true );
    if ( $care_instructions ) {
        $tabs['care_instructions'] = array(
            'title'    => __( 'Care Instructions', 'aqualuxe' ),
            'priority' => 30,
            'callback' => 'aqualuxe_product_care_instructions_tab',
        );
    }

    // Add aquatic details tab
    $scientific_name = get_post_meta( $post->ID, '_product_scientific_name', true );
    $common_name = get_post_meta( $post->ID, '_product_common_name', true );
    $size = get_post_meta( $post->ID, '_product_size', true );
    $lifespan = get_post_meta( $post->ID, '_product_lifespan', true );
    $min_tank_size = get_post_meta( $post->ID, '_product_min_tank_size', true );
    $water_temp = get_post_meta( $post->ID, '_product_water_temp', true );
    $water_ph = get_post_meta( $post->ID, '_product_water_ph', true );
    $water_hardness = get_post_meta( $post->ID, '_product_water_hardness', true );
    $breeding_difficulty = get_post_meta( $post->ID, '_product_breeding_difficulty', true );

    if ( $scientific_name || $common_name || $size || $lifespan || $min_tank_size || $water_temp || $water_ph || $water_hardness || $breeding_difficulty ) {
        $tabs['aquatic_details'] = array(
            'title'    => __( 'Aquatic Details', 'aqualuxe' ),
            'priority' => 20,
            'callback' => 'aqualuxe_product_aquatic_details_tab',
        );
    }

    return $tabs;
}
add_filter( 'woocommerce_product_tabs', 'aqualuxe_product_tabs' );

/**
 * Product care instructions tab content
 */
function aqualuxe_product_care_instructions_tab() {
    global $post;

    $care_instructions = get_post_meta( $post->ID, '_product_care_instructions', true );
    if ( $care_instructions ) {
        echo '<h2>' . esc_html__( 'Care Instructions', 'aqualuxe' ) . '</h2>';
        echo wp_kses_post( wpautop( $care_instructions ) );
    }
}

/**
 * Product aquatic details tab content
 */
function aqualuxe_product_aquatic_details_tab() {
    global $post;

    $scientific_name = get_post_meta( $post->ID, '_product_scientific_name', true );
    $common_name = get_post_meta( $post->ID, '_product_common_name', true );
    $size = get_post_meta( $post->ID, '_product_size', true );
    $lifespan = get_post_meta( $post->ID, '_product_lifespan', true );
    $min_tank_size = get_post_meta( $post->ID, '_product_min_tank_size', true );
    $water_temp = get_post_meta( $post->ID, '_product_water_temp', true );
    $water_ph = get_post_meta( $post->ID, '_product_water_ph', true );
    $water_hardness = get_post_meta( $post->ID, '_product_water_hardness', true );
    $breeding_difficulty = get_post_meta( $post->ID, '_product_breeding_difficulty', true );

    echo '<h2>' . esc_html__( 'Aquatic Details', 'aqualuxe' ) . '</h2>';
    echo '<table class="aquatic-details-table">';

    if ( $scientific_name ) {
        echo '<tr>';
        echo '<th>' . esc_html__( 'Scientific Name', 'aqualuxe' ) . '</th>';
        echo '<td>' . esc_html( $scientific_name ) . '</td>';
        echo '</tr>';
    }

    if ( $common_name ) {
        echo '<tr>';
        echo '<th>' . esc_html__( 'Common Name', 'aqualuxe' ) . '</th>';
        echo '<td>' . esc_html( $common_name ) . '</td>';
        echo '</tr>';
    }

    if ( $size ) {
        echo '<tr>';
        echo '<th>' . esc_html__( 'Size', 'aqualuxe' ) . '</th>';
        echo '<td>' . esc_html( $size ) . ' ' . esc_html__( 'inches', 'aqualuxe' ) . '</td>';
        echo '</tr>';
    }

    if ( $lifespan ) {
        echo '<tr>';
        echo '<th>' . esc_html__( 'Lifespan', 'aqualuxe' ) . '</th>';
        echo '<td>' . esc_html( $lifespan ) . ' ' . esc_html__( 'years', 'aqualuxe' ) . '</td>';
        echo '</tr>';
    }

    if ( $min_tank_size ) {
        echo '<tr>';
        echo '<th>' . esc_html__( 'Minimum Tank Size', 'aqualuxe' ) . '</th>';
        echo '<td>' . esc_html( $min_tank_size ) . ' ' . esc_html__( 'gallons', 'aqualuxe' ) . '</td>';
        echo '</tr>';
    }

    if ( $water_temp ) {
        echo '<tr>';
        echo '<th>' . esc_html__( 'Water Temperature', 'aqualuxe' ) . '</th>';
        echo '<td>' . esc_html( $water_temp ) . ' ' . esc_html__( '°F', 'aqualuxe' ) . '</td>';
        echo '</tr>';
    }

    if ( $water_ph ) {
        echo '<tr>';
        echo '<th>' . esc_html__( 'Water pH', 'aqualuxe' ) . '</th>';
        echo '<td>' . esc_html( $water_ph ) . '</td>';
        echo '</tr>';
    }

    if ( $water_hardness ) {
        echo '<tr>';
        echo '<th>' . esc_html__( 'Water Hardness', 'aqualuxe' ) . '</th>';
        echo '<td>' . esc_html( $water_hardness ) . ' ' . esc_html__( 'dGH', 'aqualuxe' ) . '</td>';
        echo '</tr>';
    }

    if ( $breeding_difficulty ) {
        echo '<tr>';
        echo '<th>' . esc_html__( 'Breeding Difficulty', 'aqualuxe' ) . '</th>';
        echo '<td>';
        switch ( $breeding_difficulty ) {
            case 'easy':
                esc_html_e( 'Easy', 'aqualuxe' );
                break;
            case 'moderate':
                esc_html_e( 'Moderate', 'aqualuxe' );
                break;
            case 'difficult':
                esc_html_e( 'Difficult', 'aqualuxe' );
                break;
            case 'very_difficult':
                esc_html_e( 'Very Difficult', 'aqualuxe' );
                break;
            default:
                echo esc_html( $breeding_difficulty );
                break;
        }
        echo '</td>';
        echo '</tr>';
    }

    echo '</table>';
}

/**
 * Add custom product meta to single product page
 */
function aqualuxe_product_meta() {
    global $product;

    $scientific_name = get_post_meta( $product->get_id(), '_product_scientific_name', true );
    $common_name = get_post_meta( $product->get_id(), '_product_common_name', true );
    $size = get_post_meta( $product->get_id(), '_product_size', true );
    $min_tank_size = get_post_meta( $product->get_id(), '_product_min_tank_size', true );

    if ( $scientific_name || $common_name || $size || $min_tank_size ) {
        echo '<div class="product-meta">';

        if ( $scientific_name ) {
            echo '<div class="product-meta-item scientific-name">';
            echo '<span class="label">' . esc_html__( 'Scientific Name:', 'aqualuxe' ) . '</span> ';
            echo '<span class="value">' . esc_html( $scientific_name ) . '</span>';
            echo '</div>';
        }

        if ( $common_name ) {
            echo '<div class="product-meta-item common-name">';
            echo '<span class="label">' . esc_html__( 'Common Name:', 'aqualuxe' ) . '</span> ';
            echo '<span class="value">' . esc_html( $common_name ) . '</span>';
            echo '</div>';
        }

        if ( $size ) {
            echo '<div class="product-meta-item size">';
            echo '<span class="label">' . esc_html__( 'Size:', 'aqualuxe' ) . '</span> ';
            echo '<span class="value">' . esc_html( $size ) . ' ' . esc_html__( 'inches', 'aqualuxe' ) . '</span>';
            echo '</div>';
        }

        if ( $min_tank_size ) {
            echo '<div class="product-meta-item min-tank-size">';
            echo '<span class="label">' . esc_html__( 'Minimum Tank Size:', 'aqualuxe' ) . '</span> ';
            echo '<span class="value">' . esc_html( $min_tank_size ) . ' ' . esc_html__( 'gallons', 'aqualuxe' ) . '</span>';
            echo '</div>';
        }

        echo '</div>';
    }
}
add_action( 'woocommerce_single_product_summary', 'aqualuxe_product_meta', 25 );

/**
 * Add custom product taxonomies to WooCommerce
 */
function aqualuxe_register_product_taxonomies() {
    // Register Origin taxonomy
    $labels = array(
        'name'              => _x( 'Origins', 'taxonomy general name', 'aqualuxe' ),
        'singular_name'     => _x( 'Origin', 'taxonomy singular name', 'aqualuxe' ),
        'search_items'      => __( 'Search Origins', 'aqualuxe' ),
        'all_items'         => __( 'All Origins', 'aqualuxe' ),
        'parent_item'       => __( 'Parent Origin', 'aqualuxe' ),
        'parent_item_colon' => __( 'Parent Origin:', 'aqualuxe' ),
        'edit_item'         => __( 'Edit Origin', 'aqualuxe' ),
        'update_item'       => __( 'Update Origin', 'aqualuxe' ),
        'add_new_item'      => __( 'Add New Origin', 'aqualuxe' ),
        'new_item_name'     => __( 'New Origin Name', 'aqualuxe' ),
        'menu_name'         => __( 'Origins', 'aqualuxe' ),
    );

    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'origin' ),
    );

    register_taxonomy( 'origin', array( 'product' ), $args );

    // Register Habitat taxonomy
    $labels = array(
        'name'              => _x( 'Habitats', 'taxonomy general name', 'aqualuxe' ),
        'singular_name'     => _x( 'Habitat', 'taxonomy singular name', 'aqualuxe' ),
        'search_items'      => __( 'Search Habitats', 'aqualuxe' ),
        'all_items'         => __( 'All Habitats', 'aqualuxe' ),
        'parent_item'       => __( 'Parent Habitat', 'aqualuxe' ),
        'parent_item_colon' => __( 'Parent Habitat:', 'aqualuxe' ),
        'edit_item'         => __( 'Edit Habitat', 'aqualuxe' ),
        'update_item'       => __( 'Update Habitat', 'aqualuxe' ),
        'add_new_item'      => __( 'Add New Habitat', 'aqualuxe' ),
        'new_item_name'     => __( 'New Habitat Name', 'aqualuxe' ),
        'menu_name'         => __( 'Habitats', 'aqualuxe' ),
    );

    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'habitat' ),
    );

    register_taxonomy( 'habitat', array( 'product' ), $args );

    // Register Care Level taxonomy
    $labels = array(
        'name'              => _x( 'Care Levels', 'taxonomy general name', 'aqualuxe' ),
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

    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'care-level' ),
    );

    register_taxonomy( 'care_level', array( 'product' ), $args );

    // Register Temperament taxonomy
    $labels = array(
        'name'              => _x( 'Temperaments', 'taxonomy general name', 'aqualuxe' ),
        'singular_name'     => _x( 'Temperament', 'taxonomy singular name', 'aqualuxe' ),
        'search_items'      => __( 'Search Temperaments', 'aqualuxe' ),
        'all_items'         => __( 'All Temperaments', 'aqualuxe' ),
        'parent_item'       => __( 'Parent Temperament', 'aqualuxe' ),
        'parent_item_colon' => __( 'Parent Temperament:', 'aqualuxe' ),
        'edit_item'         => __( 'Edit Temperament', 'aqualuxe' ),
        'update_item'       => __( 'Update Temperament', 'aqualuxe' ),
        'add_new_item'      => __( 'Add New Temperament', 'aqualuxe' ),
        'new_item_name'     => __( 'New Temperament Name', 'aqualuxe' ),
        'menu_name'         => __( 'Temperaments', 'aqualuxe' ),
    );

    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'temperament' ),
    );

    register_taxonomy( 'temperament', array( 'product' ), $args );

    // Register Diet taxonomy
    $labels = array(
        'name'              => _x( 'Diets', 'taxonomy general name', 'aqualuxe' ),
        'singular_name'     => _x( 'Diet', 'taxonomy singular name', 'aqualuxe' ),
        'search_items'      => __( 'Search Diets', 'aqualuxe' ),
        'all_items'         => __( 'All Diets', 'aqualuxe' ),
        'parent_item'       => __( 'Parent Diet', 'aqualuxe' ),
        'parent_item_colon' => __( 'Parent Diet:', 'aqualuxe' ),
        'edit_item'         => __( 'Edit Diet', 'aqualuxe' ),
        'update_item'       => __( 'Update Diet', 'aqualuxe' ),
        'add_new_item'      => __( 'Add New Diet', 'aqualuxe' ),
        'new_item_name'     => __( 'New Diet Name', 'aqualuxe' ),
        'menu_name'         => __( 'Diets', 'aqualuxe' ),
    );

    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'diet' ),
    );

    register_taxonomy( 'diet', array( 'product' ), $args );
}
add_action( 'init', 'aqualuxe_register_product_taxonomies' );

/**
 * Add custom product taxonomies to product filter widget
 *
 * @param array $args Widget args.
 * @return array
 */
function aqualuxe_product_filter_taxonomies( $args ) {
    $args['taxonomy'] = array( 'product_cat', 'product_tag', 'origin', 'habitat', 'care_level', 'temperament', 'diet' );
    return $args;
}
add_filter( 'woocommerce_product_categories_widget_args', 'aqualuxe_product_filter_taxonomies' );