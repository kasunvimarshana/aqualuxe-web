<?php
/**
 * Event Category Class
 *
 * @package AquaLuxe
 * @subpackage Events
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * AquaLuxe_Event_Category class.
 */
class AquaLuxe_Event_Category {

    /**
     * Category ID.
     *
     * @var int
     */
    public $id = 0;

    /**
     * Category term object.
     *
     * @var WP_Term
     */
    public $term = null;

    /**
     * Category data.
     *
     * @var array
     */
    protected $data = array(
        'name'        => '',
        'slug'        => '',
        'description' => '',
        'parent'      => 0,
        'count'       => 0,
        'image_id'    => 0,
        'color'       => '',
    );

    /**
     * Constructor.
     *
     * @param int|WP_Term|AquaLuxe_Event_Category $category Category ID, term object, or category object.
     */
    public function __construct($category = 0) {
        if (is_numeric($category) && $category > 0) {
            $this->id = $category;
        } elseif ($category instanceof AquaLuxe_Event_Category) {
            $this->id = $category->id;
        } elseif ($category instanceof WP_Term || (is_object($category) && isset($category->term_id))) {
            $this->id = $category->term_id;
            $this->term = $category;
        }

        if ($this->id > 0) {
            $this->load_category_data();
        }
    }

    /**
     * Load category data.
     */
    protected function load_category_data() {
        if (!$this->term) {
            $this->term = get_term($this->id, 'aqualuxe_event_category');
        }

        if (!$this->term || is_wp_error($this->term)) {
            return;
        }

        // Set basic data
        $this->data['name'] = $this->term->name;
        $this->data['slug'] = $this->term->slug;
        $this->data['description'] = $this->term->description;
        $this->data['parent'] = $this->term->parent;
        $this->data['count'] = $this->term->count;

        // Load meta data
        $image_id = get_term_meta($this->id, 'category_image_id', true);
        $this->data['image_id'] = !empty($image_id) ? absint($image_id) : 0;

        $color = get_term_meta($this->id, 'category_color', true);
        $this->data['color'] = !empty($color) ? $color : '';
    }

    /**
     * Get category data.
     *
     * @param string $key Data key.
     * @return mixed
     */
    public function get_data($key) {
        return isset($this->data[$key]) ? $this->data[$key] : null;
    }

    /**
     * Set category data.
     *
     * @param string $key Data key.
     * @param mixed $value Data value.
     */
    public function set_data($key, $value) {
        if (array_key_exists($key, $this->data)) {
            $this->data[$key] = $value;
        }
    }

    /**
     * Save category data.
     *
     * @return int Category ID.
     */
    public function save() {
        $term_args = array(
            'description' => $this->data['description'],
        );

        if ($this->id > 0) {
            wp_update_term($this->id, 'aqualuxe_event_category', array_merge(
                array('name' => $this->data['name']),
                $term_args
            ));
        } else {
            $term = wp_insert_term($this->data['name'], 'aqualuxe_event_category', $term_args);
            
            if (!is_wp_error($term)) {
                $this->id = $term['term_id'];
            }
        }

        // Save meta data
        if ($this->id > 0) {
            update_term_meta($this->id, 'category_image_id', $this->data['image_id']);
            update_term_meta($this->id, 'category_color', $this->data['color']);
        }

        return $this->id;
    }

    /**
     * Get category name.
     *
     * @return string
     */
    public function get_name() {
        return $this->data['name'];
    }

    /**
     * Get category slug.
     *
     * @return string
     */
    public function get_slug() {
        return $this->data['slug'];
    }

    /**
     * Get category description.
     *
     * @return string
     */
    public function get_description() {
        return $this->data['description'];
    }

    /**
     * Get category parent ID.
     *
     * @return int
     */
    public function get_parent_id() {
        return $this->data['parent'];
    }

    /**
     * Get category count.
     *
     * @return int
     */
    public function get_count() {
        return $this->data['count'];
    }

    /**
     * Get category image URL.
     *
     * @param string $size Image size.
     * @return string
     */
    public function get_image_url($size = 'full') {
        if ($this->data['image_id'] > 0) {
            $image = wp_get_attachment_image_src($this->data['image_id'], $size);
            if ($image) {
                return $image[0];
            }
        }
        
        return '';
    }

    /**
     * Get category color.
     *
     * @return string
     */
    public function get_color() {
        return $this->data['color'];
    }

    /**
     * Get category permalink.
     *
     * @return string
     */
    public function get_permalink() {
        return get_term_link($this->id, 'aqualuxe_event_category');
    }

    /**
     * Get events in this category.
     *
     * @param array $args Query arguments.
     * @return array
     */
    public function get_events($args = array()) {
        $events = array();
        
        $default_args = array(
            'post_type'      => 'aqualuxe_event',
            'posts_per_page' => -1,
            'tax_query'      => array(
                array(
                    'taxonomy' => 'aqualuxe_event_category',
                    'field'    => 'term_id',
                    'terms'    => $this->id,
                ),
            ),
        );
        
        $args = wp_parse_args($args, $default_args);
        
        $posts = get_posts($args);
        
        foreach ($posts as $post) {
            $events[] = new AquaLuxe_Event($post);
        }
        
        return $events;
    }

    /**
     * Get subcategories.
     *
     * @return array
     */
    public function get_subcategories() {
        $subcategories = array();
        
        $terms = get_terms(array(
            'taxonomy'   => 'aqualuxe_event_category',
            'hide_empty' => false,
            'parent'     => $this->id,
        ));
        
        if (!is_wp_error($terms)) {
            foreach ($terms as $term) {
                $subcategories[] = new AquaLuxe_Event_Category($term);
            }
        }
        
        return $subcategories;
    }

    /**
     * Get parent category.
     *
     * @return AquaLuxe_Event_Category|false
     */
    public function get_parent() {
        if ($this->data['parent'] > 0) {
            return new AquaLuxe_Event_Category($this->data['parent']);
        }
        
        return false;
    }
}