<?php

class BEA_PVC_Counter_Rest_Field {

	/**
	 * BEA_PVC_Counter_Rest_Field constructor.
	 */
	public function __construct() {
	}

	public static function rest_api_init() {
		register_rest_field( 'post',
			'views',
			array(
				'get_callback'    => array( __CLASS__, 'get_item' ),
				'schema'          => [
					'day_counter' => [
						'description'        => __( 'Current day view counter.' ),
						'type'               => 'integer',
						'default'            => 0,
					],
					'previous_day_counter' => [
						'description'        => __( 'Current day view counter.' ),
						'type'               => 'integer',
						'default'            => 0,
					]
				],
			)
		);

		add_filter( 'rest_prepare_post_type', array( __CLASS__, 'rest_prepare_post_type' ), 10, 3 );
		add_filter( 'rest_prepare_post', array( __CLASS__, 'rest_prepare_post_type' ), 10, 3 );
	}

	public static function rest_prepare_post_type( WP_REST_Response $response, $post_type, WP_REST_Request $request ) {
			$response->add_links( [
				'https://github.com/BeAPI/bea-post-views-counter' => [
					'href' => rest_url( sprintf( 'bea-post-view-counter/1.0/%s', $request->get_param( 'id' ) ) )
				]
			] );
			return $response;
	}

	public static function get_item( $object ) {
		$counter = new BEA_PVC_Counter( $object['id'] );
		return [
			'day_counter' => $counter->get_data_value( 'day_counter' ),
			'previous_day_counter' => $counter->get_data_value( 'previous_day_counter' ),

			'week_counter' => $counter->get_data_value( 'week_counter' ),
			'previous_week_counter' => $counter->get_data_value( 'previous_week_counter' ),

			'month_counter' => $counter->get_data_value( 'month_counter' ),
			'previous_month_counter' => $counter->get_data_value( 'previous_month_counter' ),

			'year_counter' => $counter->get_data_value( 'year_counter' ),
			'previous_year_counter' => $counter->get_data_value( 'previous_year_counter' ),

			'total' => $counter->get_data_value( 'total' ),
		];
	}
}