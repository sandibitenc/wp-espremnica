<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://fmed.eu
 * @since             1.0.0
 * @package           Espremnica_Export
 *
 * @wordpress-plugin
 * Plugin Name:       espremnica-export
 * Plugin URI:        http://fmed.eu/
 * Description:       Gathers woocommerce order info and exports it in CSV for printing shipping labes in eSpremnica from Post of Slovenia
 * Version:           1.0.0
 * Author:            Sandi Bitenc
 * Author URI:        http://fmed.eu/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       espremnica-export
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
define( 'PLUGIN_NAME_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-espremnica-export-activator.php
 */
function activate_espremnica_export() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-espremnica-export-activator.php';
	Espremnica_Export_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-espremnica-export-deactivator.php
 */
function deactivate_espremnica_export() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-espremnica-export-deactivator.php';
	Espremnica_Export_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_espremnica_export' );
register_deactivation_hook( __FILE__, 'deactivate_espremnica_export' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-espremnica-export.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_espremnica_export() {

	$plugin = new Espremnica_Export();
	$plugin->run();

}
run_espremnica_export();
