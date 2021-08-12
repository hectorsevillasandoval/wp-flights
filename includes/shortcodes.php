<?php
// Add Shortcode
function wp_flights_shortcode( $atts ) {
	$output = '';
	// Attributes
	$atts = shortcode_atts(
		array(
			'id' => '',
		),
		$atts,
		'wp-flights'
	);

	$flight_id = $atts['id'];

	$current_flight = get_post( $flight_id );
	$output        .= "<article class='wp-flights__item'>";
	if ( $current_flight ) :
		$flight_number = $current_flight->post_title;
		$flight_info   = get_field( 'flight_information', $flight_id );
		$date_time     = $flight_info['date_time'];
		$dep_airport   = $flight_info['departure_airport'];
		$arr_airport   = $flight_info['arrival_airport'];
		$status        = $flight_info['status'];

		$output .= "
    
    <h2>$flight_number</h2>
    <table>
        <tr>
            <td><strong>Date & Time</strong></td>
            <td>$date_time</td>
        </tr>
        <tr>
            <td><strong>Departure Airport</strong></td>
            <td>$dep_airport</td>
        </tr>
        <tr>
            <td><strong>Arrival Airport</strong></td>
            <td>$arr_airport</td>
        </tr>
        <tr>
            <td><strong>Status</strong></td>
            <td>$status</td>
        </tr>
    </table>
    ";
	else :
		$output .= '<span class="wp-flight-error">Flight not found</span>';
	endif;
	$output .= '</article>';

	return $output;

}
add_shortcode( 'wp-flights', 'wp_flights_shortcode' );
