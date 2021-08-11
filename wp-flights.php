<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://github.com/barcadictni
 * @since             1.0.0
 * @package           Wp_Flights
 *
 * @wordpress-plugin
 * Plugin Name:       WP Flights
 * Plugin URI:        https://github.com/barcadictni/wp-flights
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Héctor Sevilla
 * Author URI:        https://github.com/barcadictni
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-flights
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'WP_FLIGHTS_VERSION', '1.0.0' );
define( 'FLIGHTS_API', 'http://api.aviationstack.com/v1/flights?access_key=50ac63283b52cb320d6ad32f195db10d&limit=10' );

/**
 * Defining Post Type
 */
if ( ! function_exists( 'flights_post_type' ) ) :
	function flights_post_type() {

			$labels = array(
				'name'                  => _x( 'WP Flights', 'Post Type General Name', 'wp_flights' ),
				'singular_name'         => _x( 'WP Flights', 'Post Type Singular Name', 'wp_flights' ),
				'menu_name'             => __( 'WP Flights', 'wp_flights' ),
				'name_admin_bar'        => __( 'WP Flights', 'wp_flights' ),
				'archives'              => __( 'Flight Archives', 'wp_flights' ),
				'attributes'            => __( 'Flight Attributes', 'wp_flights' ),
				'parent_item_colon'     => __( 'Parent Item:', 'wp_flights' ),
				'all_items'             => __( 'All Flights', 'wp_flights' ),
				'add_new_item'          => __( 'Add New Flight', 'wp_flights' ),
				'add_new'               => __( 'Add New', 'wp_flights' ),
				'new_item'              => __( 'New Flight', 'wp_flights' ),
				'edit_item'             => __( 'Edit Flight', 'wp_flights' ),
				'update_item'           => __( 'Update Flight', 'wp_flights' ),
				'view_item'             => __( 'View Flight', 'wp_flights' ),
				'view_items'            => __( 'View Flights', 'wp_flights' ),
				'search_items'          => __( 'Search Flight', 'wp_flights' ),
				'not_found'             => __( 'Flight Not found', 'wp_flights' ),
				'not_found_in_trash'    => __( 'Flight Not found in Trash', 'wp_flights' ),
				'featured_image'        => __( 'Featured Image', 'wp_flights' ),
				'set_featured_image'    => __( 'Set featured image', 'wp_flights' ),
				'remove_featured_image' => __( 'Remove featured image', 'wp_flights' ),
				'use_featured_image'    => __( 'Use as featured image', 'wp_flights' ),
				'insert_into_item'      => __( 'Insert into Flight', 'wp_flights' ),
				'uploaded_to_this_item' => __( 'Uploaded to this Flight', 'wp_flights' ),
				'items_list'            => __( 'Flights list', 'wp_flights' ),
				'items_list_navigation' => __( 'Flights list navigation', 'wp_flights' ),
				'filter_items_list'     => __( 'Flight items list', 'wp_flights' ),
			);
			$args   = array(
				'label'               => __( 'WP Flights', 'wp_flights' ),
				'description'         => __( 'Registering Flights', 'wp_flights' ),
				'labels'              => $labels,
				'supports'            => array( 'title', 'thumbnail', 'page-attributes' ),
				'hierarchical'        => false,
				'public'              => true,
				'show_ui'             => true,
				'show_in_menu'        => true,
				'menu_position'       => 5,
				'menu_icon'           => 'dashicons-airplane',
				'show_in_admin_bar'   => true,
				'show_in_nav_menus'   => true,
				'can_export'          => true,
				'has_archive'         => true,
				'exclude_from_search' => false,
				'publicly_queryable'  => true,
				'capability_type'     => 'page',
			);
			register_post_type( 'wp_flights', $args );

	}
	add_action( 'init', 'flights_post_type' );
endif;


/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wp-flights-activator.php
 */
function activate_wp_flights() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-flights-activator.php';
	Wp_Flights_Activator::activate();
	flights_post_type();
	flush_rewrite_rules();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wp-flights-deactivator.php
 */
function deactivate_wp_flights() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-flights-deactivator.php';
	Wp_Flights_Deactivator::deactivate();
	unregister_post_type( 'wp_flights' );
	flush_rewrite_rules();
}

register_activation_hook( __FILE__, 'activate_wp_flights' );
register_deactivation_hook( __FILE__, 'deactivate_wp_flights' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wp-flights.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wp_flights() {

	$plugin = new Wp_Flights();
	$plugin->run();

}
run_wp_flights();
