<?php
/**
 * Plugin Name: Oxygen 2.0 Admin Plus
 * Plugin URI: http://www.badabing.nl
 * Description: Enhances Oxygenbuilder 2.0 Dashboard
 * Version: 1.0.1
 * Author: Didou Schol
 * Domain Path: languages/
 * Text Domain: oxygen-admin-plus
 * Author URI: http://www.badabing.nl
 */

define( 'OXYGENADMINPLUS_URL' 		, plugins_url( '/', __FILE__ ) );

// load the files
oaplus_load_includes();

function oaplus_load_includes() {
	$load_files = array(
					'includes/oxygenbuilder2_add_columns_in_dash.php',
					'includes/oxygenbuilder2_add_row_buttons.php',
					'includes/oxygenbuilder2_plus_admin_options.php',
					);

	foreach ( $load_files as $file ) {
		include_once( $file );
	}
}

// when activating the plugin make sure to do some stuff
register_activation_hook( __FILE__ , 'oaplus_check_options' );

function oaplus_check_options() {

	// if this is the first time we're running the plugin
	// just assume we want to use it because we activated the plugin
	if ( !get_option('oadminplus_post_types') ) update_option( 'oadminplus_post_types', array('post', 'page', 'ct_template'));
	if ( !get_option('oadminplus_add_columns') ) update_option( 'oadminplus_add_columns', true);
	if ( !get_option('oadminplus_removeoxsc') ) update_option( 'oadminplus_removeoxsc', true);

}

/**
 * Load the Github Updater
 */
if( ! class_exists( 'Smashing_Updater' ) ){
	include_once( plugin_dir_path( __FILE__ ) . 'updater.php' );
}
$updater = new Smashing_Updater( __FILE__ );
$updater->set_username( 'badabingbreda' );
$updater->set_repository( 'oxygen-admin-plus' );
$updater->initialize();


