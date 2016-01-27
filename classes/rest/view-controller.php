<?php

class BEA_PVC_Counter_Rest_Controller extends WP_REST_Controller {

	/**
	 * Register the routes for the objects of the controller.
	 */
	public function register_routes() {
		$version = '1.0';
		$namespace = 'bea-post-view-counter/' . $version;

		register_rest_route( $namespace, '/(?P<id>[\d]+)', array(
			array(
				'methods'         => WP_REST_Server::CREATABLE,
				'callback'        => array( $this, 'create_item' ),
				'permission_callback' => array( $this, 'create_item_permissions_check' ),
				'args' => array(
					'id' => array(
						'validate_callback' => function($param, $request, $key) {
							return is_numeric( $param );
						}
					),
				),
			),
		) );

		register_rest_route( $namespace, '/(?P<id>[\d]+)', array(
			array(
				'methods'         => WP_REST_Server::READABLE,
				'callback'        => array( $this, 'get_item' ),
				'args' => array(
					'id' => array(
						'validate_callback' => function($param, $request, $key) {
							return is_numeric( $param );
						}
					),
				),
			),
		) );
	}

	public function get_item( $request ) {
		$item = $this->prepare_item_for_database( $request );

		$counter = new BEA_PVC_Counter($item['id']);
		$result = $counter->increment();

		if( true === $result ) {
			return new WP_REST_Response( $counter->get_data(), 200 );
		}
	}

	/**
	 * Create one item from the collection
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 * @return WP_Error|WP_REST_Request
	 */
	public function create_item( $request ) {

		$item = $this->prepare_item_for_database( $request );

		$counter = new BEA_PVC_Counter($item['id']);
		$result = $counter->increment();

		if( true === $result ) {
			return new WP_REST_Response( $counter->get_data(), 200 );
		}

		return new WP_Error( 'cant-create', __( 'Cannot add the view', 'bea-post-views-counter'), array( 'status' => 500 ) );
	}

	/**
	 * Check if a given request has access to create items
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 * @return WP_Error|bool
	 */
	public function create_item_permissions_check( WP_REST_Request $request ) {
		$id = $request->get_param('id');

		if ( empty( $id )  ) {
			return new WP_Error( 'rest_post_id', __( 'An ID is needed for the view to be counted' ), array( 'status' => 500 ) );
		}
		$counter = new BEA_PVC_Counter( $id );

		if( ! $counter->is_allowed_to_increment() ) {
			return new WP_Error( 'rest_auth', __( 'You are not allowed to add a view' ), array( 'status' => 500 ) );
		}

		return true;
	}

	/**
	 * Prepare the item for create or update operation
	 *
	 * @param WP_REST_Request $request Request object
	 * @return WP_Error|object $prepared_item
	 */
	protected function prepare_item_for_database( $request ) {
		return array( 'id' => (int) $request->get_param( 'id' ) );
	}
}