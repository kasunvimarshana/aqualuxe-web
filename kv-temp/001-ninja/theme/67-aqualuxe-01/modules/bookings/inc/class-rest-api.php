<?php
/**
 * REST API Class
 *
 * @package AquaLuxe
 * @subpackage Modules\Bookings
 * @since 1.0.0
 */

namespace AquaLuxe\Modules\Bookings;

/**
 * REST API Class
 * 
 * This class handles REST API endpoints.
 */
class REST_API {
    /**
     * API namespace
     *
     * @var string
     */
    private $namespace = 'aqualuxe/v1';

    /**
     * Constructor
     */
    public function __construct() {
        // Nothing to do here
    }

    /**
     * Register routes
     *
     * @return void
     */
    public function register_routes() {
        // Register services routes
        register_rest_route(
            $this->namespace,
            '/services',
            [
                [
                    'methods'             => \WP_REST_Server::READABLE,
                    'callback'            => [ $this, 'get_services' ],
                    'permission_callback' => [ $this, 'get_items_permissions_check' ],
                    'args'                => $this->get_collection_params(),
                ],
            ]
        );

        register_rest_route(
            $this->namespace,
            '/services/(?P<id>[\d]+)',
            [
                [
                    'methods'             => \WP_REST_Server::READABLE,
                    'callback'            => [ $this, 'get_service' ],
                    'permission_callback' => [ $this, 'get_item_permissions_check' ],
                    'args'                => [
                        'id' => [
                            'validate_callback' => function( $param, $request, $key ) {
                                return is_numeric( $param );
                            },
                        ],
                    ],
                ],
            ]
        );

        // Register availability routes
        register_rest_route(
            $this->namespace,
            '/services/(?P<id>[\d]+)/availability',
            [
                [
                    'methods'             => \WP_REST_Server::READABLE,
                    'callback'            => [ $this, 'get_service_availability' ],
                    'permission_callback' => [ $this, 'get_item_permissions_check' ],
                    'args'                => [
                        'id' => [
                            'validate_callback' => function( $param, $request, $key ) {
                                return is_numeric( $param );
                            },
                        ],
                        'date' => [
                            'validate_callback' => function( $param, $request, $key ) {
                                return preg_match( '/^\d{4}-\d{2}-\d{2}$/', $param );
                            },
                        ],
                    ],
                ],
            ]
        );

        // Register bookings routes
        register_rest_route(
            $this->namespace,
            '/bookings',
            [
                [
                    'methods'             => \WP_REST_Server::READABLE,
                    'callback'            => [ $this, 'get_bookings' ],
                    'permission_callback' => [ $this, 'get_items_permissions_check' ],
                    'args'                => $this->get_collection_params(),
                ],
                [
                    'methods'             => \WP_REST_Server::CREATABLE,
                    'callback'            => [ $this, 'create_booking' ],
                    'permission_callback' => [ $this, 'create_item_permissions_check' ],
                    'args'                => $this->get_endpoint_args_for_item_schema( \WP_REST_Server::CREATABLE ),
                ],
            ]
        );

        register_rest_route(
            $this->namespace,
            '/bookings/(?P<id>[\d]+)',
            [
                [
                    'methods'             => \WP_REST_Server::READABLE,
                    'callback'            => [ $this, 'get_booking' ],
                    'permission_callback' => [ $this, 'get_item_permissions_check' ],
                    'args'                => [
                        'id' => [
                            'validate_callback' => function( $param, $request, $key ) {
                                return is_numeric( $param );
                            },
                        ],
                    ],
                ],
                [
                    'methods'             => \WP_REST_Server::EDITABLE,
                    'callback'            => [ $this, 'update_booking' ],
                    'permission_callback' => [ $this, 'update_item_permissions_check' ],
                    'args'                => $this->get_endpoint_args_for_item_schema( \WP_REST_Server::EDITABLE ),
                ],
                [
                    'methods'             => \WP_REST_Server::DELETABLE,
                    'callback'            => [ $this, 'delete_booking' ],
                    'permission_callback' => [ $this, 'delete_item_permissions_check' ],
                    'args'                => [
                        'id' => [
                            'validate_callback' => function( $param, $request, $key ) {
                                return is_numeric( $param );
                            },
                        ],
                    ],
                ],
            ]
        );
    }

    /**
     * Get services
     *
     * @param WP_REST_Request $request Request object.
     * @return WP_REST_Response
     */
    public function get_services( $request ) {
        // Query args
        $args = [
            'post_type'      => 'aqualuxe_service',
            'posts_per_page' => $request['per_page'] ? $request['per_page'] : 10,
            'paged'          => $request['page'] ? $request['page'] : 1,
            'orderby'        => $request['orderby'] ? $request['orderby'] : 'title',
            'order'          => $request['order'] ? $request['order'] : 'ASC',
        ];

        // Add category if specified
        if ( ! empty( $request['category'] ) ) {
            $args['tax_query'] = [
                [
                    'taxonomy' => 'aqualuxe_service_category',
                    'field'    => 'slug',
                    'terms'    => explode( ',', $request['category'] ),
                ],
            ];
        }

        // Get services
        $services_query = new \WP_Query( $args );
        $services = [];

        if ( $services_query->have_posts() ) {
            while ( $services_query->have_posts() ) {
                $services_query->the_post();
                $service_id = get_the_ID();
                $service = new Service( $service_id );
                $services[] = $this->prepare_service_for_response( $service, $request );
            }
        }

        wp_reset_postdata();

        // Create response
        $response = rest_ensure_response( $services );

        // Add pagination headers
        $total_services = $services_query->found_posts;
        $max_pages = ceil( $total_services / $args['posts_per_page'] );

        $response->header( 'X-WP-Total', $total_services );
        $response->header( 'X-WP-TotalPages', $max_pages );

        return $response;
    }

    /**
     * Get service
     *
     * @param WP_REST_Request $request Request object.
     * @return WP_REST_Response|WP_Error
     */
    public function get_service( $request ) {
        // Get service
        $service = new Service( $request['id'] );

        // Check if service exists
        if ( ! $service->get_id() ) {
            return new \WP_Error( 'rest_service_invalid_id', __( 'Invalid service ID.', 'aqualuxe' ), [ 'status' => 404 ] );
        }

        // Create response
        $response = $this->prepare_service_for_response( $service, $request );

        return rest_ensure_response( $response );
    }

    /**
     * Get service availability
     *
     * @param WP_REST_Request $request Request object.
     * @return WP_REST_Response|WP_Error
     */
    public function get_service_availability( $request ) {
        // Get service
        $service = new Service( $request['id'] );

        // Check if service exists
        if ( ! $service->get_id() ) {
            return new \WP_Error( 'rest_service_invalid_id', __( 'Invalid service ID.', 'aqualuxe' ), [ 'status' => 404 ] );
        }

        // Get date
        $date = $request['date'] ? $request['date'] : date( 'Y-m-d' );

        // Get availability
        $availability = new Availability( $service->get_id(), $date );
        $available_slots = $availability->get_available_time_slots();

        // Create response
        $response = [
            'service_id' => $service->get_id(),
            'date'       => $date,
            'available'  => $availability->is_available(),
            'slots'      => $available_slots,
        ];

        return rest_ensure_response( $response );
    }

    /**
     * Get bookings
     *
     * @param WP_REST_Request $request Request object.
     * @return WP_REST_Response
     */
    public function get_bookings( $request ) {
        // Query args
        $args = [
            'post_type'      => 'aqualuxe_booking',
            'posts_per_page' => $request['per_page'] ? $request['per_page'] : 10,
            'paged'          => $request['page'] ? $request['page'] : 1,
            'orderby'        => $request['orderby'] ? $request['orderby'] : 'date',
            'order'          => $request['order'] ? $request['order'] : 'DESC',
        ];

        // Add meta query
        $meta_query = [];

        // Add service filter
        if ( ! empty( $request['service_id'] ) ) {
            $meta_query[] = [
                'key'   => '_service_id',
                'value' => $request['service_id'],
            ];
        }

        // Add date filter
        if ( ! empty( $request['date'] ) ) {
            $meta_query[] = [
                'key'   => '_date',
                'value' => $request['date'],
            ];
        }

        // Add customer filter
        if ( ! empty( $request['customer_email'] ) ) {
            $meta_query[] = [
                'key'   => '_customer_email',
                'value' => $request['customer_email'],
            ];
        }

        // Add meta query to args
        if ( ! empty( $meta_query ) ) {
            $args['meta_query'] = $meta_query;
        }

        // Add status filter
        if ( ! empty( $request['status'] ) ) {
            $args['tax_query'] = [
                [
                    'taxonomy' => 'aqualuxe_booking_status',
                    'field'    => 'slug',
                    'terms'    => explode( ',', $request['status'] ),
                ],
            ];
        }

        // Get bookings
        $bookings_query = new \WP_Query( $args );
        $bookings = [];

        if ( $bookings_query->have_posts() ) {
            while ( $bookings_query->have_posts() ) {
                $bookings_query->the_post();
                $booking_id = get_the_ID();
                $booking = new Booking( $booking_id );
                $bookings[] = $this->prepare_booking_for_response( $booking, $request );
            }
        }

        wp_reset_postdata();

        // Create response
        $response = rest_ensure_response( $bookings );

        // Add pagination headers
        $total_bookings = $bookings_query->found_posts;
        $max_pages = ceil( $total_bookings / $args['posts_per_page'] );

        $response->header( 'X-WP-Total', $total_bookings );
        $response->header( 'X-WP-TotalPages', $max_pages );

        return $response;
    }

    /**
     * Get booking
     *
     * @param WP_REST_Request $request Request object.
     * @return WP_REST_Response|WP_Error
     */
    public function get_booking( $request ) {
        // Get booking
        $booking = new Booking( $request['id'] );

        // Check if booking exists
        if ( ! $booking->get_id() ) {
            return new \WP_Error( 'rest_booking_invalid_id', __( 'Invalid booking ID.', 'aqualuxe' ), [ 'status' => 404 ] );
        }

        // Create response
        $response = $this->prepare_booking_for_response( $booking, $request );

        return rest_ensure_response( $response );
    }

    /**
     * Create booking
     *
     * @param WP_REST_Request $request Request object.
     * @return WP_REST_Response|WP_Error
     */
    public function create_booking( $request ) {
        // Create booking
        $booking = new Booking();
        $result = $booking->create( $request->get_params() );

        // Check for errors
        if ( is_wp_error( $result ) ) {
            return $result;
        }

        // Get booking
        $booking = new Booking( $result );

        // Create response
        $response = $this->prepare_booking_for_response( $booking, $request );
        $response->set_status( 201 );

        return $response;
    }

    /**
     * Update booking
     *
     * @param WP_REST_Request $request Request object.
     * @return WP_REST_Response|WP_Error
     */
    public function update_booking( $request ) {
        // Get booking
        $booking = new Booking( $request['id'] );

        // Check if booking exists
        if ( ! $booking->get_id() ) {
            return new \WP_Error( 'rest_booking_invalid_id', __( 'Invalid booking ID.', 'aqualuxe' ), [ 'status' => 404 ] );
        }

        // Update booking
        $result = $booking->update( $request->get_params() );

        // Check for errors
        if ( is_wp_error( $result ) ) {
            return $result;
        }

        // Create response
        $response = $this->prepare_booking_for_response( $booking, $request );

        return $response;
    }

    /**
     * Delete booking
     *
     * @param WP_REST_Request $request Request object.
     * @return WP_REST_Response|WP_Error
     */
    public function delete_booking( $request ) {
        // Get booking
        $booking = new Booking( $request['id'] );

        // Check if booking exists
        if ( ! $booking->get_id() ) {
            return new \WP_Error( 'rest_booking_invalid_id', __( 'Invalid booking ID.', 'aqualuxe' ), [ 'status' => 404 ] );
        }

        // Get booking data for response
        $data = $this->prepare_booking_for_response( $booking, $request );

        // Delete booking
        $result = $booking->delete();

        // Check for errors
        if ( is_wp_error( $result ) ) {
            return $result;
        }

        // Create response
        $response = rest_ensure_response( $data );

        return $response;
    }

    /**
     * Prepare service for response
     *
     * @param Service         $service Service object.
     * @param WP_REST_Request $request Request object.
     * @return array
     */
    private function prepare_service_for_response( $service, $request ) {
        // Get service data
        $data = [
            'id'          => $service->get_id(),
            'title'       => $service->get_title(),
            'description' => $service->get_description(),
            'duration'    => $service->get_duration(),
            'price'       => $service->get_price(),
            'sale_price'  => $service->get_sale_price(),
            'location'    => $service->get_location(),
            'capacity'    => $service->get_capacity(),
            'categories'  => [],
            'thumbnail'   => $service->get_thumbnail_url( 'medium' ),
            'url'         => $service->get_url(),
        ];

        // Add categories
        $categories = $service->get_categories();
        foreach ( $categories as $category ) {
            $data['categories'][] = [
                'id'   => $category->term_id,
                'name' => $category->name,
                'slug' => $category->slug,
            ];
        }

        return $data;
    }

    /**
     * Prepare booking for response
     *
     * @param Booking         $booking Booking object.
     * @param WP_REST_Request $request Request object.
     * @return array
     */
    private function prepare_booking_for_response( $booking, $request ) {
        // Get service
        $service = new Service( $booking->get_service_id() );

        // Get booking data
        $data = [
            'id'              => $booking->get_id(),
            'service_id'      => $booking->get_service_id(),
            'service_name'    => $service->get_title(),
            'date'            => $booking->get_date(),
            'time'            => $booking->get_time(),
            'duration'        => $booking->get_duration(),
            'status'          => $booking->get_status(),
            'customer_name'   => $booking->get_customer_name(),
            'customer_email'  => $booking->get_customer_email(),
            'customer_phone'  => $booking->get_customer_phone(),
            'customer_address' => $booking->get_customer_address(),
            'customer_city'   => $booking->get_customer_city(),
            'customer_state'  => $booking->get_customer_state(),
            'customer_zip'    => $booking->get_customer_zip(),
            'customer_country' => $booking->get_customer_country(),
            'customer_notes'  => $booking->get_customer_notes(),
            'booking_notes'   => $booking->get_booking_notes(),
        ];

        return $data;
    }

    /**
     * Get collection params
     *
     * @return array
     */
    private function get_collection_params() {
        return [
            'page'     => [
                'description'       => __( 'Current page of the collection.', 'aqualuxe' ),
                'type'              => 'integer',
                'default'           => 1,
                'sanitize_callback' => 'absint',
                'validate_callback' => 'rest_validate_request_arg',
                'minimum'           => 1,
            ],
            'per_page' => [
                'description'       => __( 'Maximum number of items to be returned in result set.', 'aqualuxe' ),
                'type'              => 'integer',
                'default'           => 10,
                'minimum'           => 1,
                'maximum'           => 100,
                'sanitize_callback' => 'absint',
                'validate_callback' => 'rest_validate_request_arg',
            ],
            'orderby'  => [
                'description'       => __( 'Sort collection by object attribute.', 'aqualuxe' ),
                'type'              => 'string',
                'default'           => 'date',
                'enum'              => [
                    'date',
                    'title',
                    'id',
                ],
                'validate_callback' => 'rest_validate_request_arg',
            ],
            'order'    => [
                'description'       => __( 'Order sort attribute ascending or descending.', 'aqualuxe' ),
                'type'              => 'string',
                'default'           => 'desc',
                'enum'              => [
                    'asc',
                    'desc',
                ],
                'validate_callback' => 'rest_validate_request_arg',
            ],
        ];
    }

    /**
     * Get endpoint args for item schema
     *
     * @param string $method HTTP method.
     * @return array
     */
    private function get_endpoint_args_for_item_schema( $method = \WP_REST_Server::CREATABLE ) {
        $args = [];

        if ( \WP_REST_Server::CREATABLE === $method || \WP_REST_Server::EDITABLE === $method ) {
            $args['service_id'] = [
                'description'       => __( 'Service ID.', 'aqualuxe' ),
                'type'              => 'integer',
                'required'          => \WP_REST_Server::CREATABLE === $method,
                'sanitize_callback' => 'absint',
                'validate_callback' => 'rest_validate_request_arg',
            ];

            $args['date'] = [
                'description'       => __( 'Booking date (YYYY-MM-DD).', 'aqualuxe' ),
                'type'              => 'string',
                'required'          => \WP_REST_Server::CREATABLE === $method,
                'validate_callback' => function( $param, $request, $key ) {
                    return preg_match( '/^\d{4}-\d{2}-\d{2}$/', $param );
                },
            ];

            $args['time'] = [
                'description'       => __( 'Booking time (HH:MM).', 'aqualuxe' ),
                'type'              => 'string',
                'required'          => \WP_REST_Server::CREATABLE === $method,
                'validate_callback' => function( $param, $request, $key ) {
                    return preg_match( '/^\d{2}:\d{2}$/', $param );
                },
            ];

            $args['duration'] = [
                'description'       => __( 'Booking duration in minutes.', 'aqualuxe' ),
                'type'              => 'integer',
                'sanitize_callback' => 'absint',
                'validate_callback' => 'rest_validate_request_arg',
            ];

            $args['status'] = [
                'description'       => __( 'Booking status.', 'aqualuxe' ),
                'type'              => 'string',
                'enum'              => [
                    'pending',
                    'confirmed',
                    'completed',
                    'cancelled',
                    'rescheduled',
                ],
                'validate_callback' => 'rest_validate_request_arg',
            ];

            $args['customer_name'] = [
                'description'       => __( 'Customer name.', 'aqualuxe' ),
                'type'              => 'string',
                'required'          => \WP_REST_Server::CREATABLE === $method,
                'sanitize_callback' => 'sanitize_text_field',
                'validate_callback' => 'rest_validate_request_arg',
            ];

            $args['customer_email'] = [
                'description'       => __( 'Customer email.', 'aqualuxe' ),
                'type'              => 'string',
                'required'          => \WP_REST_Server::CREATABLE === $method,
                'sanitize_callback' => 'sanitize_email',
                'validate_callback' => function( $param, $request, $key ) {
                    return is_email( $param );
                },
            ];

            $args['customer_phone'] = [
                'description'       => __( 'Customer phone.', 'aqualuxe' ),
                'type'              => 'string',
                'sanitize_callback' => 'sanitize_text_field',
                'validate_callback' => 'rest_validate_request_arg',
            ];

            $args['customer_address'] = [
                'description'       => __( 'Customer address.', 'aqualuxe' ),
                'type'              => 'string',
                'sanitize_callback' => 'sanitize_text_field',
                'validate_callback' => 'rest_validate_request_arg',
            ];

            $args['customer_city'] = [
                'description'       => __( 'Customer city.', 'aqualuxe' ),
                'type'              => 'string',
                'sanitize_callback' => 'sanitize_text_field',
                'validate_callback' => 'rest_validate_request_arg',
            ];

            $args['customer_state'] = [
                'description'       => __( 'Customer state.', 'aqualuxe' ),
                'type'              => 'string',
                'sanitize_callback' => 'sanitize_text_field',
                'validate_callback' => 'rest_validate_request_arg',
            ];

            $args['customer_zip'] = [
                'description'       => __( 'Customer ZIP code.', 'aqualuxe' ),
                'type'              => 'string',
                'sanitize_callback' => 'sanitize_text_field',
                'validate_callback' => 'rest_validate_request_arg',
            ];

            $args['customer_country'] = [
                'description'       => __( 'Customer country.', 'aqualuxe' ),
                'type'              => 'string',
                'sanitize_callback' => 'sanitize_text_field',
                'validate_callback' => 'rest_validate_request_arg',
            ];

            $args['customer_notes'] = [
                'description'       => __( 'Customer notes.', 'aqualuxe' ),
                'type'              => 'string',
                'sanitize_callback' => 'sanitize_textarea_field',
                'validate_callback' => 'rest_validate_request_arg',
            ];

            $args['booking_notes'] = [
                'description'       => __( 'Booking notes.', 'aqualuxe' ),
                'type'              => 'string',
                'sanitize_callback' => 'sanitize_textarea_field',
                'validate_callback' => 'rest_validate_request_arg',
            ];
        }

        return $args;
    }

    /**
     * Check if current user can get items
     *
     * @param WP_REST_Request $request Request object.
     * @return bool|WP_Error
     */
    public function get_items_permissions_check( $request ) {
        return true;
    }

    /**
     * Check if current user can get item
     *
     * @param WP_REST_Request $request Request object.
     * @return bool|WP_Error
     */
    public function get_item_permissions_check( $request ) {
        return true;
    }

    /**
     * Check if current user can create item
     *
     * @param WP_REST_Request $request Request object.
     * @return bool|WP_Error
     */
    public function create_item_permissions_check( $request ) {
        return true;
    }

    /**
     * Check if current user can update item
     *
     * @param WP_REST_Request $request Request object.
     * @return bool|WP_Error
     */
    public function update_item_permissions_check( $request ) {
        if ( ! current_user_can( 'edit_posts' ) ) {
            return new \WP_Error( 'rest_forbidden', __( 'You are not allowed to update bookings.', 'aqualuxe' ), [ 'status' => rest_authorization_required_code() ] );
        }

        return true;
    }

    /**
     * Check if current user can delete item
     *
     * @param WP_REST_Request $request Request object.
     * @return bool|WP_Error
     */
    public function delete_item_permissions_check( $request ) {
        if ( ! current_user_can( 'delete_posts' ) ) {
            return new \WP_Error( 'rest_forbidden', __( 'You are not allowed to delete bookings.', 'aqualuxe' ), [ 'status' => rest_authorization_required_code() ] );
        }

        return true;
    }
}