<?php

/**
 * Registering Settings
 */
/*define( 'FLIGHTS_API', 'http://api.aviationstack.com/v1/flights?access_key=50ac63283b52cb320d6ad32f195db10d&limit=10' );*/
function wpflights_settings_init() {
	// Register a new setting for "wpflights_settings" page.
	register_setting( 'wpflights_settings', 'wpflights_options' );

	// Register a new section in the "wpflights_settings" page.
	add_settings_section(
		'wpflights_section',
		__( 'Enter the following information bellow:', 'wp-flights' ),
		null,
		'wpflights_settings'
	);

	// Register a new field in the "wporg_section_developers" section, inside the "wporg" page.
	add_settings_field(
		'wpflights_field_api', // As of WP 4.6 this value is used only internally.
		// Use $args' label_for to populate the id inside the callback.
			__( 'API Key', 'wp-flights' ),
		'wpflights_field_api_cb',
		'wpflights_settings',
		'wpflights_section',
		array(
			'label_for' => 'wpflights_field_api',
			'class'     => 'wpflights_row',
		)
	);
}

 /**
 * Register our wporg_settings_init to the admin_init action hook.
 */
add_action( 'admin_init', 'wpflights_settings_init' );

function wpflights_section_callback( $args ) {
	?>
	<p id="<?php echo esc_attr( $args['id'] ); ?>"><?php esc_html_e( 'Follow the white rabbit.', 'wp-flights' ); ?></p>
	<?php
}

/**
 * Pill field callbakc function.
 *
 * WordPress has magic interaction with the following keys: label_for, class.
 * - the "label_for" key value is used for the "for" attribute of the <label>.
 * - the "class" key value is used for the "class" attribute of the <tr> containing the field.
 * Note: you can add custom key value pairs to be used inside your callbacks.
 *
 * @param array $args
 */
function wpflights_field_api_cb( $args ) {
	// Get the value of the setting we've registered with register_setting()
	$options = get_option( 'wpflights_options' );
	?>
	<input type="text" name="wpflights_options" value="<?php echo isset( $options ) ? esc_attr( $options ) : ''; ?>">
	
	<p class="description">
		<?php esc_html_e( 'Get your API Key by accessing here: https://aviationstack.com/', 'wp-flights' ); ?>
	</p>
	
	<?php
}

function wpflights_options_page_html() {
	// check user capabilities
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	// add error/update messages

	// check if the user have submitted the settings
	// WordPress will add the "settings-updated" $_GET parameter to the url
	if ( isset( $_GET['settings-updated'] ) ) {
		// add settings saved message with the class of "updated"
		add_settings_error( 'wporg_messages', 'wporg_message', __( 'Settings Saved', 'wp-flights' ), 'updated' );
	}
	// show error/update messages
	settings_errors( 'wporg_messages' );
	?>
	<div class="wrap">
		<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
		<form action="options.php" method="post">
			<?php
			// output security fields for the registered setting "wpflights_options"
			settings_fields( 'wpflights_settings' );
			// output setting sections and their fields
			// (sections are registered for "wporg", each field is registered to a specific section)
			do_settings_sections( 'wpflights_settings' );
			// output save settings button
			submit_button( __( 'Save Settings', 'wpflights' ) );
			?>
		</form>
	</div>
	<?php
}

function wpflights_options_page() {
	add_submenu_page(
		'edit.php?post_type=wp_flights',
		'WP Flights Options',
		'Settings',
		'manage_options',
		'wpflights_settings',
		'wpflights_options_page_html'
	);
}
add_action( 'admin_menu', 'wpflights_options_page' );
