<?php
require_once ABSPATH . 'wp-includes/pluggable.php';
$api_key = get_option( 'wpflights_options' );

// Crear un hook


add_action( 'wp_flights_cron_hook', 'wp_flights_cron_exec' );

/**
 * This function will be getting the flights from: FLIGHTS_API
 *
 * @return void
 */
function wp_flights_cron_exec() {
	$api_key      = get_option( 'wpflights_options' );
	$response     = wp_remote_get( 'http://api.aviationstack.com/v1/flights?access_key=' . $api_key . '&limit=10' );
	$body         = wp_remote_retrieve_body( $response );
	$api_response = json_decode( $body, true );

	remove_previous_posts();
	foreach ( $api_response['data'] as $flight ) {
		$flight_number     = $flight['flight']['number'];
		$post_title        = "Flight #$flight_number";
		$departure_airport = $flight['departure']['airport'];
		$flight_date       = $flight['departure']['scheduled'];
		$arrival_airport   = $flight['arrival']['airport'];
		$flight_status     = $flight['flight_status'];
		$post_info         = array(
			'post_title'   => $post_title,
			'post_content' => '',
			'post_type'    => 'wp_flights',
			'post_status'  => 'publish',
		);

		$flight_ID = wp_insert_post( $post_info, true );

		if ( ! is_wp_error( $flight_ID ) ) {
			update_custom_fields( $flight_ID, $flight_date, $departure_airport, $arrival_airport, $flight_status );
		}
	}

}

function remove_previous_posts() {
	$all_flights = get_posts(
		array(
			'post_type'      => 'wp_flights',
			'posts_per_page' => -1,
		)
	);

	foreach ( $all_flights as $flight ) {
		wp_delete_post( $flight->ID, true );
	}
}

function update_custom_fields( $postID, $date = '', $dep_airport = '', $arr_airport = '', $status = '' ) {
	$values = array(
		'date_time'         => $date,
		'departure_airport' => $dep_airport,
		'arrival_airport'   => $arr_airport,
		'status'            => $status,
	);
	update_field( 'flight_information', $values, $postID );
}
// Schedule the event
if ( ! wp_next_scheduled( 'wp_flights_cron_hook' ) && $api_key !== '' ) {
	wp_schedule_event( time(), 'hourly', 'wp_flights_cron_hook' );
}

// Remover tarea programada
