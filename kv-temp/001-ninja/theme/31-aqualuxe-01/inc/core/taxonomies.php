<?php
/**
 * Custom taxonomies for the AquaLuxe theme
 *
 * @package AquaLuxe
 */

/**
 * Register custom taxonomies
 */
function aqualuxe_register_taxonomies() {
    // Service Categories
    register_taxonomy('service_category', 'aqualuxe_service', array(
        'labels' => array(
            'name'              => _x('Service Categories', 'taxonomy general name', 'aqualuxe'),
            'singular_name'     => _x('Service Category', 'taxonomy singular name', 'aqualuxe'),
            'search_items'      => __('Search Service Categories', 'aqualuxe'),
            'all_items'         => __('All Service Categories', 'aqualuxe'),
            'parent_item'       => __('Parent Service Category', 'aqualuxe'),
            'parent_item_colon' => __('Parent Service Category:', 'aqualuxe'),
            'edit_item'         => __('Edit Service Category', 'aqualuxe'),
            'update_item'       => __('Update Service Category', 'aqualuxe'),
            'add_new_item'      => __('Add New Service Category', 'aqualuxe'),
            'new_item_name'     => __('New Service Category Name', 'aqualuxe'),
            'menu_name'         => __('Categories', 'aqualuxe'),
        ),
        'hierarchical'      => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'service-category'),
        'show_in_rest'      => true,
    ));

    // Service Tags
    register_taxonomy('service_tag', 'aqualuxe_service', array(
        'labels' => array(
            'name'              => _x('Service Tags', 'taxonomy general name', 'aqualuxe'),
            'singular_name'     => _x('Service Tag', 'taxonomy singular name', 'aqualuxe'),
            'search_items'      => __('Search Service Tags', 'aqualuxe'),
            'all_items'         => __('All Service Tags', 'aqualuxe'),
            'parent_item'       => __('Parent Service Tag', 'aqualuxe'),
            'parent_item_colon' => __('Parent Service Tag:', 'aqualuxe'),
            'edit_item'         => __('Edit Service Tag', 'aqualuxe'),
            'update_item'       => __('Update Service Tag', 'aqualuxe'),
            'add_new_item'      => __('Add New Service Tag', 'aqualuxe'),
            'new_item_name'     => __('New Service Tag Name', 'aqualuxe'),
            'menu_name'         => __('Tags', 'aqualuxe'),
        ),
        'hierarchical'      => false,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'service-tag'),
        'show_in_rest'      => true,
    ));

    // Event Categories
    register_taxonomy('event_category', 'aqualuxe_event', array(
        'labels' => array(
            'name'              => _x('Event Categories', 'taxonomy general name', 'aqualuxe'),
            'singular_name'     => _x('Event Category', 'taxonomy singular name', 'aqualuxe'),
            'search_items'      => __('Search Event Categories', 'aqualuxe'),
            'all_items'         => __('All Event Categories', 'aqualuxe'),
            'parent_item'       => __('Parent Event Category', 'aqualuxe'),
            'parent_item_colon' => __('Parent Event Category:', 'aqualuxe'),
            'edit_item'         => __('Edit Event Category', 'aqualuxe'),
            'update_item'       => __('Update Event Category', 'aqualuxe'),
            'add_new_item'      => __('Add New Event Category', 'aqualuxe'),
            'new_item_name'     => __('New Event Category Name', 'aqualuxe'),
            'menu_name'         => __('Categories', 'aqualuxe'),
        ),
        'hierarchical'      => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'event-category'),
        'show_in_rest'      => true,
    ));

    // Event Tags
    register_taxonomy('event_tag', 'aqualuxe_event', array(
        'labels' => array(
            'name'              => _x('Event Tags', 'taxonomy general name', 'aqualuxe'),
            'singular_name'     => _x('Event Tag', 'taxonomy singular name', 'aqualuxe'),
            'search_items'      => __('Search Event Tags', 'aqualuxe'),
            'all_items'         => __('All Event Tags', 'aqualuxe'),
            'parent_item'       => __('Parent Event Tag', 'aqualuxe'),
            'parent_item_colon' => __('Parent Event Tag:', 'aqualuxe'),
            'edit_item'         => __('Edit Event Tag', 'aqualuxe'),
            'update_item'       => __('Update Event Tag', 'aqualuxe'),
            'add_new_item'      => __('Add New Event Tag', 'aqualuxe'),
            'new_item_name'     => __('New Event Tag Name', 'aqualuxe'),
            'menu_name'         => __('Tags', 'aqualuxe'),
        ),
        'hierarchical'      => false,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'event-tag'),
        'show_in_rest'      => true,
    ));

    // Team Departments
    register_taxonomy('team_department', 'aqualuxe_team', array(
        'labels' => array(
            'name'              => _x('Departments', 'taxonomy general name', 'aqualuxe'),
            'singular_name'     => _x('Department', 'taxonomy singular name', 'aqualuxe'),
            'search_items'      => __('Search Departments', 'aqualuxe'),
            'all_items'         => __('All Departments', 'aqualuxe'),
            'parent_item'       => __('Parent Department', 'aqualuxe'),
            'parent_item_colon' => __('Parent Department:', 'aqualuxe'),
            'edit_item'         => __('Edit Department', 'aqualuxe'),
            'update_item'       => __('Update Department', 'aqualuxe'),
            'add_new_item'      => __('Add New Department', 'aqualuxe'),
            'new_item_name'     => __('New Department Name', 'aqualuxe'),
            'menu_name'         => __('Departments', 'aqualuxe'),
        ),
        'hierarchical'      => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'department'),
        'show_in_rest'      => true,
    ));

    // Team Locations
    register_taxonomy('team_location', 'aqualuxe_team', array(
        'labels' => array(
            'name'              => _x('Locations', 'taxonomy general name', 'aqualuxe'),
            'singular_name'     => _x('Location', 'taxonomy singular name', 'aqualuxe'),
            'search_items'      => __('Search Locations', 'aqualuxe'),
            'all_items'         => __('All Locations', 'aqualuxe'),
            'parent_item'       => __('Parent Location', 'aqualuxe'),
            'parent_item_colon' => __('Parent Location:', 'aqualuxe'),
            'edit_item'         => __('Edit Location', 'aqualuxe'),
            'update_item'       => __('Update Location', 'aqualuxe'),
            'add_new_item'      => __('Add New Location', 'aqualuxe'),
            'new_item_name'     => __('New Location Name', 'aqualuxe'),
            'menu_name'         => __('Locations', 'aqualuxe'),
        ),
        'hierarchical'      => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'team-location'),
        'show_in_rest'      => true,
    ));

    // Project Categories
    register_taxonomy('project_category', 'aqualuxe_project', array(
        'labels' => array(
            'name'              => _x('Project Categories', 'taxonomy general name', 'aqualuxe'),
            'singular_name'     => _x('Project Category', 'taxonomy singular name', 'aqualuxe'),
            'search_items'      => __('Search Project Categories', 'aqualuxe'),
            'all_items'         => __('All Project Categories', 'aqualuxe'),
            'parent_item'       => __('Parent Project Category', 'aqualuxe'),
            'parent_item_colon' => __('Parent Project Category:', 'aqualuxe'),
            'edit_item'         => __('Edit Project Category', 'aqualuxe'),
            'update_item'       => __('Update Project Category', 'aqualuxe'),
            'add_new_item'      => __('Add New Project Category', 'aqualuxe'),
            'new_item_name'     => __('New Project Category Name', 'aqualuxe'),
            'menu_name'         => __('Categories', 'aqualuxe'),
        ),
        'hierarchical'      => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'project-category'),
        'show_in_rest'      => true,
    ));

    // Project Tags
    register_taxonomy('project_tag', 'aqualuxe_project', array(
        'labels' => array(
            'name'              => _x('Project Tags', 'taxonomy general name', 'aqualuxe'),
            'singular_name'     => _x('Project Tag', 'taxonomy singular name', 'aqualuxe'),
            'search_items'      => __('Search Project Tags', 'aqualuxe'),
            'all_items'         => __('All Project Tags', 'aqualuxe'),
            'parent_item'       => __('Parent Project Tag', 'aqualuxe'),
            'parent_item_colon' => __('Parent Project Tag:', 'aqualuxe'),
            'edit_item'         => __('Edit Project Tag', 'aqualuxe'),
            'update_item'       => __('Update Project Tag', 'aqualuxe'),
            'add_new_item'      => __('Add New Project Tag', 'aqualuxe'),
            'new_item_name'     => __('New Project Tag Name', 'aqualuxe'),
            'menu_name'         => __('Tags', 'aqualuxe'),
        ),
        'hierarchical'      => false,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'project-tag'),
        'show_in_rest'      => true,
    ));

    // Testimonial Categories
    register_taxonomy('testimonial_category', 'aqualuxe_testimonial', array(
        'labels' => array(
            'name'              => _x('Testimonial Categories', 'taxonomy general name', 'aqualuxe'),
            'singular_name'     => _x('Testimonial Category', 'taxonomy singular name', 'aqualuxe'),
            'search_items'      => __('Search Testimonial Categories', 'aqualuxe'),
            'all_items'         => __('All Testimonial Categories', 'aqualuxe'),
            'parent_item'       => __('Parent Testimonial Category', 'aqualuxe'),
            'parent_item_colon' => __('Parent Testimonial Category:', 'aqualuxe'),
            'edit_item'         => __('Edit Testimonial Category', 'aqualuxe'),
            'update_item'       => __('Update Testimonial Category', 'aqualuxe'),
            'add_new_item'      => __('Add New Testimonial Category', 'aqualuxe'),
            'new_item_name'     => __('New Testimonial Category Name', 'aqualuxe'),
            'menu_name'         => __('Categories', 'aqualuxe'),
        ),
        'hierarchical'      => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'testimonial-category'),
        'show_in_rest'      => true,
    ));

    // FAQ Categories
    register_taxonomy('faq_category', 'aqualuxe_faq', array(
        'labels' => array(
            'name'              => _x('FAQ Categories', 'taxonomy general name', 'aqualuxe'),
            'singular_name'     => _x('FAQ Category', 'taxonomy singular name', 'aqualuxe'),
            'search_items'      => __('Search FAQ Categories', 'aqualuxe'),
            'all_items'         => __('All FAQ Categories', 'aqualuxe'),
            'parent_item'       => __('Parent FAQ Category', 'aqualuxe'),
            'parent_item_colon' => __('Parent FAQ Category:', 'aqualuxe'),
            'edit_item'         => __('Edit FAQ Category', 'aqualuxe'),
            'update_item'       => __('Update FAQ Category', 'aqualuxe'),
            'add_new_item'      => __('Add New FAQ Category', 'aqualuxe'),
            'new_item_name'     => __('New FAQ Category Name', 'aqualuxe'),
            'menu_name'         => __('Categories', 'aqualuxe'),
        ),
        'hierarchical'      => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'faq-category'),
        'show_in_rest'      => true,
    ));

    // Care Guide Categories
    register_taxonomy('care_guide_category', 'aqualuxe_care_guide', array(
        'labels' => array(
            'name'              => _x('Care Guide Categories', 'taxonomy general name', 'aqualuxe'),
            'singular_name'     => _x('Care Guide Category', 'taxonomy singular name', 'aqualuxe'),
            'search_items'      => __('Search Care Guide Categories', 'aqualuxe'),
            'all_items'         => __('All Care Guide Categories', 'aqualuxe'),
            'parent_item'       => __('Parent Care Guide Category', 'aqualuxe'),
            'parent_item_colon' => __('Parent Care Guide Category:', 'aqualuxe'),
            'edit_item'         => __('Edit Care Guide Category', 'aqualuxe'),
            'update_item'       => __('Update Care Guide Category', 'aqualuxe'),
            'add_new_item'      => __('Add New Care Guide Category', 'aqualuxe'),
            'new_item_name'     => __('New Care Guide Category Name', 'aqualuxe'),
            'menu_name'         => __('Categories', 'aqualuxe'),
        ),
        'hierarchical'      => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'care-guide-category'),
        'show_in_rest'      => true,
    ));

    // Care Guide Tags
    register_taxonomy('care_guide_tag', 'aqualuxe_care_guide', array(
        'labels' => array(
            'name'              => _x('Care Guide Tags', 'taxonomy general name', 'aqualuxe'),
            'singular_name'     => _x('Care Guide Tag', 'taxonomy singular name', 'aqualuxe'),
            'search_items'      => __('Search Care Guide Tags', 'aqualuxe'),
            'all_items'         => __('All Care Guide Tags', 'aqualuxe'),
            'parent_item'       => __('Parent Care Guide Tag', 'aqualuxe'),
            'parent_item_colon' => __('Parent Care Guide Tag:', 'aqualuxe'),
            'edit_item'         => __('Edit Care Guide Tag', 'aqualuxe'),
            'update_item'       => __('Update Care Guide Tag', 'aqualuxe'),
            'add_new_item'      => __('Add New Care Guide Tag', 'aqualuxe'),
            'new_item_name'     => __('New Care Guide Tag Name', 'aqualuxe'),
            'menu_name'         => __('Tags', 'aqualuxe'),
        ),
        'hierarchical'      => false,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'care-guide-tag'),
        'show_in_rest'      => true,
    ));

    // Auction Categories
    register_taxonomy('auction_category', 'aqualuxe_auction', array(
        'labels' => array(
            'name'              => _x('Auction Categories', 'taxonomy general name', 'aqualuxe'),
            'singular_name'     => _x('Auction Category', 'taxonomy singular name', 'aqualuxe'),
            'search_items'      => __('Search Auction Categories', 'aqualuxe'),
            'all_items'         => __('All Auction Categories', 'aqualuxe'),
            'parent_item'       => __('Parent Auction Category', 'aqualuxe'),
            'parent_item_colon' => __('Parent Auction Category:', 'aqualuxe'),
            'edit_item'         => __('Edit Auction Category', 'aqualuxe'),
            'update_item'       => __('Update Auction Category', 'aqualuxe'),
            'add_new_item'      => __('Add New Auction Category', 'aqualuxe'),
            'new_item_name'     => __('New Auction Category Name', 'aqualuxe'),
            'menu_name'         => __('Categories', 'aqualuxe'),
        ),
        'hierarchical'      => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'auction-category'),
        'show_in_rest'      => true,
    ));

    // Trade-In Categories
    register_taxonomy('trade_in_category', 'aqualuxe_trade_in', array(
        'labels' => array(
            'name'              => _x('Trade-In Categories', 'taxonomy general name', 'aqualuxe'),
            'singular_name'     => _x('Trade-In Category', 'taxonomy singular name', 'aqualuxe'),
            'search_items'      => __('Search Trade-In Categories', 'aqualuxe'),
            'all_items'         => __('All Trade-In Categories', 'aqualuxe'),
            'parent_item'       => __('Parent Trade-In Category', 'aqualuxe'),
            'parent_item_colon' => __('Parent Trade-In Category:', 'aqualuxe'),
            'edit_item'         => __('Edit Trade-In Category', 'aqualuxe'),
            'update_item'       => __('Update Trade-In Category', 'aqualuxe'),
            'add_new_item'      => __('Add New Trade-In Category', 'aqualuxe'),
            'new_item_name'     => __('New Trade-In Category Name', 'aqualuxe'),
            'menu_name'         => __('Categories', 'aqualuxe'),
        ),
        'hierarchical'      => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'trade-in-category'),
        'show_in_rest'      => true,
    ));
}
add_action('init', 'aqualuxe_register_taxonomies');

/**
 * Add custom taxonomy filters to the admin screen
 */
function aqualuxe_add_taxonomy_filters() {
    global $typenow;

    // Service filters
    if ($typenow === 'aqualuxe_service') {
        $taxonomies = array('service_category', 'service_tag');
        foreach ($taxonomies as $tax_slug) {
            $tax_obj = get_taxonomy($tax_slug);
            $tax_name = $tax_obj->labels->name;
            $terms = get_terms($tax_slug);
            if (count($terms) > 0) {
                echo "<select name='$tax_slug' id='$tax_slug' class='postform'>";
                echo "<option value=''>" . sprintf(__('Show All %s', 'aqualuxe'), $tax_name) . "</option>";
                foreach ($terms as $term) {
                    $selected = isset($_GET[$tax_slug]) && $_GET[$tax_slug] === $term->slug ? ' selected="selected"' : '';
                    echo '<option value="' . $term->slug . '"' . $selected . '>' . $term->name . ' (' . $term->count . ')</option>';
                }
                echo "</select>";
            }
        }
    }

    // Event filters
    if ($typenow === 'aqualuxe_event') {
        $taxonomies = array('event_category', 'event_tag');
        foreach ($taxonomies as $tax_slug) {
            $tax_obj = get_taxonomy($tax_slug);
            $tax_name = $tax_obj->labels->name;
            $terms = get_terms($tax_slug);
            if (count($terms) > 0) {
                echo "<select name='$tax_slug' id='$tax_slug' class='postform'>";
                echo "<option value=''>" . sprintf(__('Show All %s', 'aqualuxe'), $tax_name) . "</option>";
                foreach ($terms as $term) {
                    $selected = isset($_GET[$tax_slug]) && $_GET[$tax_slug] === $term->slug ? ' selected="selected"' : '';
                    echo '<option value="' . $term->slug . '"' . $selected . '>' . $term->name . ' (' . $term->count . ')</option>';
                }
                echo "</select>";
            }
        }
    }

    // Team filters
    if ($typenow === 'aqualuxe_team') {
        $taxonomies = array('team_department', 'team_location');
        foreach ($taxonomies as $tax_slug) {
            $tax_obj = get_taxonomy($tax_slug);
            $tax_name = $tax_obj->labels->name;
            $terms = get_terms($tax_slug);
            if (count($terms) > 0) {
                echo "<select name='$tax_slug' id='$tax_slug' class='postform'>";
                echo "<option value=''>" . sprintf(__('Show All %s', 'aqualuxe'), $tax_name) . "</option>";
                foreach ($terms as $term) {
                    $selected = isset($_GET[$tax_slug]) && $_GET[$tax_slug] === $term->slug ? ' selected="selected"' : '';
                    echo '<option value="' . $term->slug . '"' . $selected . '>' . $term->name . ' (' . $term->count . ')</option>';
                }
                echo "</select>";
            }
        }
    }

    // Project filters
    if ($typenow === 'aqualuxe_project') {
        $taxonomies = array('project_category', 'project_tag');
        foreach ($taxonomies as $tax_slug) {
            $tax_obj = get_taxonomy($tax_slug);
            $tax_name = $tax_obj->labels->name;
            $terms = get_terms($tax_slug);
            if (count($terms) > 0) {
                echo "<select name='$tax_slug' id='$tax_slug' class='postform'>";
                echo "<option value=''>" . sprintf(__('Show All %s', 'aqualuxe'), $tax_name) . "</option>";
                foreach ($terms as $term) {
                    $selected = isset($_GET[$tax_slug]) && $_GET[$tax_slug] === $term->slug ? ' selected="selected"' : '';
                    echo '<option value="' . $term->slug . '"' . $selected . '>' . $term->name . ' (' . $term->count . ')</option>';
                }
                echo "</select>";
            }
        }
    }

    // Testimonial filters
    if ($typenow === 'aqualuxe_testimonial') {
        $taxonomies = array('testimonial_category');
        foreach ($taxonomies as $tax_slug) {
            $tax_obj = get_taxonomy($tax_slug);
            $tax_name = $tax_obj->labels->name;
            $terms = get_terms($tax_slug);
            if (count($terms) > 0) {
                echo "<select name='$tax_slug' id='$tax_slug' class='postform'>";
                echo "<option value=''>" . sprintf(__('Show All %s', 'aqualuxe'), $tax_name) . "</option>";
                foreach ($terms as $term) {
                    $selected = isset($_GET[$tax_slug]) && $_GET[$tax_slug] === $term->slug ? ' selected="selected"' : '';
                    echo '<option value="' . $term->slug . '"' . $selected . '>' . $term->name . ' (' . $term->count . ')</option>';
                }
                echo "</select>";
            }
        }
    }

    // FAQ filters
    if ($typenow === 'aqualuxe_faq') {
        $taxonomies = array('faq_category');
        foreach ($taxonomies as $tax_slug) {
            $tax_obj = get_taxonomy($tax_slug);
            $tax_name = $tax_obj->labels->name;
            $terms = get_terms($tax_slug);
            if (count($terms) > 0) {
                echo "<select name='$tax_slug' id='$tax_slug' class='postform'>";
                echo "<option value=''>" . sprintf(__('Show All %s', 'aqualuxe'), $tax_name) . "</option>";
                foreach ($terms as $term) {
                    $selected = isset($_GET[$tax_slug]) && $_GET[$tax_slug] === $term->slug ? ' selected="selected"' : '';
                    echo '<option value="' . $term->slug . '"' . $selected . '>' . $term->name . ' (' . $term->count . ')</option>';
                }
                echo "</select>";
            }
        }
    }

    // Care Guide filters
    if ($typenow === 'aqualuxe_care_guide') {
        $taxonomies = array('care_guide_category', 'care_guide_tag');
        foreach ($taxonomies as $tax_slug) {
            $tax_obj = get_taxonomy($tax_slug);
            $tax_name = $tax_obj->labels->name;
            $terms = get_terms($tax_slug);
            if (count($terms) > 0) {
                echo "<select name='$tax_slug' id='$tax_slug' class='postform'>";
                echo "<option value=''>" . sprintf(__('Show All %s', 'aqualuxe'), $tax_name) . "</option>";
                foreach ($terms as $term) {
                    $selected = isset($_GET[$tax_slug]) && $_GET[$tax_slug] === $term->slug ? ' selected="selected"' : '';
                    echo '<option value="' . $term->slug . '"' . $selected . '>' . $term->name . ' (' . $term->count . ')</option>';
                }
                echo "</select>";
            }
        }
    }

    // Auction filters
    if ($typenow === 'aqualuxe_auction') {
        $taxonomies = array('auction_category');
        foreach ($taxonomies as $tax_slug) {
            $tax_obj = get_taxonomy($tax_slug);
            $tax_name = $tax_obj->labels->name;
            $terms = get_terms($tax_slug);
            if (count($terms) > 0) {
                echo "<select name='$tax_slug' id='$tax_slug' class='postform'>";
                echo "<option value=''>" . sprintf(__('Show All %s', 'aqualuxe'), $tax_name) . "</option>";
                foreach ($terms as $term) {
                    $selected = isset($_GET[$tax_slug]) && $_GET[$tax_slug] === $term->slug ? ' selected="selected"' : '';
                    echo '<option value="' . $term->slug . '"' . $selected . '>' . $term->name . ' (' . $term->count . ')</option>';
                }
                echo "</select>";
            }
        }
    }

    // Trade-In filters
    if ($typenow === 'aqualuxe_trade_in') {
        $taxonomies = array('trade_in_category');
        foreach ($taxonomies as $tax_slug) {
            $tax_obj = get_taxonomy($tax_slug);
            $tax_name = $tax_obj->labels->name;
            $terms = get_terms($tax_slug);
            if (count($terms) > 0) {
                echo "<select name='$tax_slug' id='$tax_slug' class='postform'>";
                echo "<option value=''>" . sprintf(__('Show All %s', 'aqualuxe'), $tax_name) . "</option>";
                foreach ($terms as $term) {
                    $selected = isset($_GET[$tax_slug]) && $_GET[$tax_slug] === $term->slug ? ' selected="selected"' : '';
                    echo '<option value="' . $term->slug . '"' . $selected . '>' . $term->name . ' (' . $term->count . ')</option>';
                }
                echo "</select>";
            }
        }
    }
}
add_action('restrict_manage_posts', 'aqualuxe_add_taxonomy_filters');

/**
 * Add custom taxonomy columns to the admin screen
 */
function aqualuxe_add_taxonomy_columns($columns) {
    global $typenow;

    // Service columns
    if ($typenow === 'aqualuxe_service') {
        $columns['service_category'] = __('Categories', 'aqualuxe');
        $columns['service_tag'] = __('Tags', 'aqualuxe');
    }

    // Event columns
    if ($typenow === 'aqualuxe_event') {
        $columns['event_category'] = __('Categories', 'aqualuxe');
        $columns['event_tag'] = __('Tags', 'aqualuxe');
    }

    // Team columns
    if ($typenow === 'aqualuxe_team') {
        $columns['team_department'] = __('Department', 'aqualuxe');
        $columns['team_location'] = __('Location', 'aqualuxe');
    }

    // Project columns
    if ($typenow === 'aqualuxe_project') {
        $columns['project_category'] = __('Categories', 'aqualuxe');
        $columns['project_tag'] = __('Tags', 'aqualuxe');
    }

    // Testimonial columns
    if ($typenow === 'aqualuxe_testimonial') {
        $columns['testimonial_category'] = __('Categories', 'aqualuxe');
    }

    // FAQ columns
    if ($typenow === 'aqualuxe_faq') {
        $columns['faq_category'] = __('Categories', 'aqualuxe');
    }

    // Care Guide columns
    if ($typenow === 'aqualuxe_care_guide') {
        $columns['care_guide_category'] = __('Categories', 'aqualuxe');
        $columns['care_guide_tag'] = __('Tags', 'aqualuxe');
    }

    // Auction columns
    if ($typenow === 'aqualuxe_auction') {
        $columns['auction_category'] = __('Categories', 'aqualuxe');
    }

    // Trade-In columns
    if ($typenow === 'aqualuxe_trade_in') {
        $columns['trade_in_category'] = __('Categories', 'aqualuxe');
    }

    return $columns;
}
add_filter('manage_posts_columns', 'aqualuxe_add_taxonomy_columns');

/**
 * Display custom taxonomy column content
 */
function aqualuxe_taxonomy_column_content($column, $post_id) {
    // Service columns
    if ($column === 'service_category') {
        $terms = get_the_terms($post_id, 'service_category');
        if (!empty($terms)) {
            $term_links = array();
            foreach ($terms as $term) {
                $term_links[] = '<a href="' . esc_url(admin_url('edit.php?post_type=aqualuxe_service&service_category=' . $term->slug)) . '">' . esc_html($term->name) . '</a>';
            }
            echo implode(', ', $term_links);
        } else {
            echo '—';
        }
    }

    if ($column === 'service_tag') {
        $terms = get_the_terms($post_id, 'service_tag');
        if (!empty($terms)) {
            $term_links = array();
            foreach ($terms as $term) {
                $term_links[] = '<a href="' . esc_url(admin_url('edit.php?post_type=aqualuxe_service&service_tag=' . $term->slug)) . '">' . esc_html($term->name) . '</a>';
            }
            echo implode(', ', $term_links);
        } else {
            echo '—';
        }
    }

    // Event columns
    if ($column === 'event_category') {
        $terms = get_the_terms($post_id, 'event_category');
        if (!empty($terms)) {
            $term_links = array();
            foreach ($terms as $term) {
                $term_links[] = '<a href="' . esc_url(admin_url('edit.php?post_type=aqualuxe_event&event_category=' . $term->slug)) . '">' . esc_html($term->name) . '</a>';
            }
            echo implode(', ', $term_links);
        } else {
            echo '—';
        }
    }

    if ($column === 'event_tag') {
        $terms = get_the_terms($post_id, 'event_tag');
        if (!empty($terms)) {
            $term_links = array();
            foreach ($terms as $term) {
                $term_links[] = '<a href="' . esc_url(admin_url('edit.php?post_type=aqualuxe_event&event_tag=' . $term->slug)) . '">' . esc_html($term->name) . '</a>';
            }
            echo implode(', ', $term_links);
        } else {
            echo '—';
        }
    }

    // Team columns
    if ($column === 'team_department') {
        $terms = get_the_terms($post_id, 'team_department');
        if (!empty($terms)) {
            $term_links = array();
            foreach ($terms as $term) {
                $term_links[] = '<a href="' . esc_url(admin_url('edit.php?post_type=aqualuxe_team&team_department=' . $term->slug)) . '">' . esc_html($term->name) . '</a>';
            }
            echo implode(', ', $term_links);
        } else {
            echo '—';
        }
    }

    if ($column === 'team_location') {
        $terms = get_the_terms($post_id, 'team_location');
        if (!empty($terms)) {
            $term_links = array();
            foreach ($terms as $term) {
                $term_links[] = '<a href="' . esc_url(admin_url('edit.php?post_type=aqualuxe_team&team_location=' . $term->slug)) . '">' . esc_html($term->name) . '</a>';
            }
            echo implode(', ', $term_links);
        } else {
            echo '—';
        }
    }

    // Project columns
    if ($column === 'project_category') {
        $terms = get_the_terms($post_id, 'project_category');
        if (!empty($terms)) {
            $term_links = array();
            foreach ($terms as $term) {
                $term_links[] = '<a href="' . esc_url(admin_url('edit.php?post_type=aqualuxe_project&project_category=' . $term->slug)) . '">' . esc_html($term->name) . '</a>';
            }
            echo implode(', ', $term_links);
        } else {
            echo '—';
        }
    }

    if ($column === 'project_tag') {
        $terms = get_the_terms($post_id, 'project_tag');
        if (!empty($terms)) {
            $term_links = array();
            foreach ($terms as $term) {
                $term_links[] = '<a href="' . esc_url(admin_url('edit.php?post_type=aqualuxe_project&project_tag=' . $term->slug)) . '">' . esc_html($term->name) . '</a>';
            }
            echo implode(', ', $term_links);
        } else {
            echo '—';
        }
    }

    // Testimonial columns
    if ($column === 'testimonial_category') {
        $terms = get_the_terms($post_id, 'testimonial_category');
        if (!empty($terms)) {
            $term_links = array();
            foreach ($terms as $term) {
                $term_links[] = '<a href="' . esc_url(admin_url('edit.php?post_type=aqualuxe_testimonial&testimonial_category=' . $term->slug)) . '">' . esc_html($term->name) . '</a>';
            }
            echo implode(', ', $term_links);
        } else {
            echo '—';
        }
    }

    // FAQ columns
    if ($column === 'faq_category') {
        $terms = get_the_terms($post_id, 'faq_category');
        if (!empty($terms)) {
            $term_links = array();
            foreach ($terms as $term) {
                $term_links[] = '<a href="' . esc_url(admin_url('edit.php?post_type=aqualuxe_faq&faq_category=' . $term->slug)) . '">' . esc_html($term->name) . '</a>';
            }
            echo implode(', ', $term_links);
        } else {
            echo '—';
        }
    }

    // Care Guide columns
    if ($column === 'care_guide_category') {
        $terms = get_the_terms($post_id, 'care_guide_category');
        if (!empty($terms)) {
            $term_links = array();
            foreach ($terms as $term) {
                $term_links[] = '<a href="' . esc_url(admin_url('edit.php?post_type=aqualuxe_care_guide&care_guide_category=' . $term->slug)) . '">' . esc_html($term->name) . '</a>';
            }
            echo implode(', ', $term_links);
        } else {
            echo '—';
        }
    }

    if ($column === 'care_guide_tag') {
        $terms = get_the_terms($post_id, 'care_guide_tag');
        if (!empty($terms)) {
            $term_links = array();
            foreach ($terms as $term) {
                $term_links[] = '<a href="' . esc_url(admin_url('edit.php?post_type=aqualuxe_care_guide&care_guide_tag=' . $term->slug)) . '">' . esc_html($term->name) . '</a>';
            }
            echo implode(', ', $term_links);
        } else {
            echo '—';
        }
    }

    // Auction columns
    if ($column === 'auction_category') {
        $terms = get_the_terms($post_id, 'auction_category');
        if (!empty($terms)) {
            $term_links = array();
            foreach ($terms as $term) {
                $term_links[] = '<a href="' . esc_url(admin_url('edit.php?post_type=aqualuxe_auction&auction_category=' . $term->slug)) . '">' . esc_html($term->name) . '</a>';
            }
            echo implode(', ', $term_links);
        } else {
            echo '—';
        }
    }

    // Trade-In columns
    if ($column === 'trade_in_category') {
        $terms = get_the_terms($post_id, 'trade_in_category');
        if (!empty($terms)) {
            $term_links = array();
            foreach ($terms as $term) {
                $term_links[] = '<a href="' . esc_url(admin_url('edit.php?post_type=aqualuxe_trade_in&trade_in_category=' . $term->slug)) . '">' . esc_html($term->name) . '</a>';
            }
            echo implode(', ', $term_links);
        } else {
            echo '—';
        }
    }
}
add_action('manage_posts_custom_column', 'aqualuxe_taxonomy_column_content', 10, 2);

/**
 * Make custom taxonomy columns sortable
 */
function aqualuxe_sortable_taxonomy_columns($columns) {
    global $typenow;

    // Service columns
    if ($typenow === 'aqualuxe_service') {
        $columns['service_category'] = 'service_category';
        $columns['service_tag'] = 'service_tag';
    }

    // Event columns
    if ($typenow === 'aqualuxe_event') {
        $columns['event_category'] = 'event_category';
        $columns['event_tag'] = 'event_tag';
    }

    // Team columns
    if ($typenow === 'aqualuxe_team') {
        $columns['team_department'] = 'team_department';
        $columns['team_location'] = 'team_location';
    }

    // Project columns
    if ($typenow === 'aqualuxe_project') {
        $columns['project_category'] = 'project_category';
        $columns['project_tag'] = 'project_tag';
    }

    // Testimonial columns
    if ($typenow === 'aqualuxe_testimonial') {
        $columns['testimonial_category'] = 'testimonial_category';
    }

    // FAQ columns
    if ($typenow === 'aqualuxe_faq') {
        $columns['faq_category'] = 'faq_category';
    }

    // Care Guide columns
    if ($typenow === 'aqualuxe_care_guide') {
        $columns['care_guide_category'] = 'care_guide_category';
        $columns['care_guide_tag'] = 'care_guide_tag';
    }

    // Auction columns
    if ($typenow === 'aqualuxe_auction') {
        $columns['auction_category'] = 'auction_category';
    }

    // Trade-In columns
    if ($typenow === 'aqualuxe_trade_in') {
        $columns['trade_in_category'] = 'trade_in_category';
    }

    return $columns;
}
add_filter('manage_edit-post_sortable_columns', 'aqualuxe_sortable_taxonomy_columns');